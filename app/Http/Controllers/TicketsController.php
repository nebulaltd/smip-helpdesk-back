<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Ticket;
use App\Models\CustomField;
use App\Models\FieldsOption;
use App\Notifications\TicketNotification;
use App\Traits\ApiTrait;
use App\Traits\EmailTrait;
use App\Traits\CustomFieldTrait;
use Illuminate\Http\Request;
use App\Mailers\AppMailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TicketsController extends Controller
{
    use EmailTrait, CustomFieldTrait, ApiTrait;

    public function index()
    {
        $departments = Department::all();

        return $this->successResponse($departments);
    }

    public function getTicketData(Request $request)
    {
        $user = Auth::user();

        if ($user->is_admin) {
            $data = Ticket::query();
        } else if ($user->user_type == 1) {
            $data = Ticket::where('department_id', $user->department_id);
        } else {
            $data = Ticket::where('user_id', $user->id);
        }

        return Datatables::of($data->when($request->ticketType != 'all', function ($q) use ($request) {
            $q->where('status', $request->ticketType);
        }))
            ->filter(function ($query) use ($request, $user) {
                $search = $request->search['value'];
                if (($request->has('startDate')) && ($request->has('endDate'))) {
                    $query->whereBetween(DB::raw('DATE(created_at)'), [$request->startDate, $request->endDate]);
                }
                if (($request->has('ticketDepartment')) && ($request->ticketDepartment != 'all')) {
                    $query->where('department_id', $request->ticketDepartment);
                }
                if (($request->has('ticketPriority')) && ($request->ticketPriority != 'all')) {
                    $query->where('priority', $request->ticketPriority);
                }
                if ($request->has('search') && $user->user_type == 0 && $user->is_admin == 0) {
                    $query->where('user_id', $user->id)
                        ->Where('ticket_id', 'LIKE', "%$search%");
                } else {
                    if ($request->has('search') && $search != null) {
                        $query->where('title', 'LIKE', "%$search%")
                            ->orWhere('ticket_id', 'LIKE', "%$search%")
                            ->orWhere('priority', 'LIKE', "%$search%");
                    }
                }
            })
            ->addColumn('ticket_title', function ($data) {
                $ticketRoute = route('ticket.show', $data->ticket_id);
                $val = '<a href="' . $ticketRoute . '">' . $data->title . '</a>';
                return $val;
            })
            ->addColumn('department', function ($data) {
                return $data->department->title;
            })
            ->addColumn('user_name', function ($data) {
                return optional($data->user)->name;
            })
            ->addColumn('ticket_status', function ($data) {
                if ($data->status === "Open") {
                    $statusValue = '<span class="badge badge-warning">' . $data->status . '</span>';
                } else {
                    $statusValue = '<span class="badge badge-success">' . $data->status . '</span>';
                }
                return $statusValue;
            })
            ->addColumn('updated', function ($data) {
                return $data->updated_at->format('Y m d, h:i A');
            })
            ->addColumn('action', function ($data) use ($user) {
                $closeRoute = route('close_ticket.close', $data->ticket_id);
                $viewRoute = route('ticket.show', $data->ticket_id);
                $reopenRoute = route('ticketReOpen', $data->ticket_id);
                $assign = '';
                $reopen = '';
                if ($user->is_admin) {
                    $assign = '<button type="button" class="badge bg-info pointer" id="getAssignedTicketData" data-id="' . $data->id . '" title="Re-Assign Department">' . __('lang.reassign') . '</button>';
                    $reopen = '<form action="' . $reopenRoute . '" method="post" id="reopen_form_' . $data->id . '">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                                    <button title="Reopen Ticket" type="submit"  class="badge bg-red pointer" data-id="reopen_form_' . $data->id . '">' . __("lang.reopen") . '</button>
                                </form>';
                }

                if ($data->status === "Open") {
                    $value = '<a href="' . $viewRoute . '"
                                   class="badge bg-primary text-white" title="Reply">' . __("lang.reply") . '</a>
                               <form action="' . $closeRoute . '" method="post" id="close_form_' . $data->id . '">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <button title="Close" type="submit"  class="badge bg-red pointer" data-id="close_form_' . $data->id . '">' . __("lang.close") . '</button>
                            </form>
                            ' . $assign;
                } else {
                    $value = $reopen;
                }

                return $value;
            })
            ->rawColumns(['ticket_title', 'action', 'ticket_status'])->make(true);
    }

    public function openedTickets()
    {
        $departments = Department::all();

        return $this->successResponse($departments);
    }

    public function ClosedTickets()
    {
        $departments = Department::all();
        return $this->successResponse($departments);
    }

    public function create()
    {
        $departments = Department::all();
        $fields = CustomField::with('options')->where("status", CustomField::ACTIVE)->get();
        //FieldsOption::with('field')->get();

        return $this->successResponse(['departments' => $departments, 'fields' => $fields]);
    }

    public function store(Request $request, AppMailer $mailer)
    {
        $this->validate($request, [
            'title' => 'required',
            'department' => 'required',
            'priority' => 'required',
            'message' => 'required'
        ]);

        $authUser = Auth::user();

        $deptUser = Department::with('user')->findOrFail($request->input('department'));

        $ticket = new Ticket([
            'title' => $request->input('title'),
            'user_id' => $authUser->id,
            'ticket_id' => strtoupper(str_random(10)),
            'department_id' => $request->input('department'),
            'priority' => $request->input('priority'),
            'message' => $request->input('message'),
            'status' => "Open",
        ]);

        if ($ticket->save()) {

            $this->customFieldStoreLogic($request, $ticket->id);

            $mailText = $this->newTicketSubmitTemplate($authUser, $ticket);

            $subject = "[Ticket ID: $ticket->ticket_id] $ticket->title";

            $mailer->sendEmail($mailText, $authUser->email, $subject);
            $details = ['title' => $subject, 'ticket_id' => $ticket->ticket_id];
            // send notification
            if ($deptUser->user->isNotEmpty()) {
                $deptUser->user[0]->notify(new TicketNotification($details));
            } else {
                $authUser->isAdmin()->notify(new TicketNotification($details));
            }

            return $this->successResponse([]);
        } else {
            return $this->generalError('Something Went Wrong');
        }
    }

    public function show($ticket_id)
    {
        $ticket = Ticket::with('ticketCustomField')->where('ticket_id', $ticket_id)->firstOrFail();
        //dd($ticket);
        $comments = $ticket->comments;

        $department = $ticket->department;
        $departments = Department::all();

        return $this->successResponse([
            'ticket' => $ticket,
            'department' => $department,
            'comments' => $comments,
            'departments' => $departments,
        ]);
    }

    public function close($ticket_id, AppMailer $mailer)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

        $ticket->status = 'Closed';

        if ($ticket->save()) {
            $ticketOwner = $ticket->user;
            $mailText = $this->sendTicketStatusNotification($ticketOwner, $ticket);

            $email = $ticketOwner->email;
            $subject = "Your ticket status changed";

            $details = ['title' => $subject, 'ticket_id' => $ticket_id];
            // send notification
            $ticketOwner->notify(new TicketNotification($details));

            $mailer->sendEmail($mailText, $email, $subject);

            return $this->successResponse([]);
        } else {
            return $this->generalError('Something Went Wrong');
        }
    }

    public function reOpen($ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

        $ticket->status = 'Open';

        if ($ticket->save()) {
            $notify = storeNotify('Ticket Re-Open');
        } else {
            $notify = errorNotify("Ticket Re-Open");
        }

        return redirect()->back()->with($notify);
    }

    public function assignTo($id)
    {
        $data = Ticket::with('department')->findOrFail($id);
        $departments = Department::all();

        $html = view('tickets.change-dept', compact('data', 'departments'))->render();

        return response()->json(['html' => $html]);
    }

    public function assignToDepartment(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'department' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $ticket = Ticket::find($id);
        $ticket->department_id = $request->department;

        $ticket->save();

        return response()->json(['success' => 'Ticket assigned successfully']);

    }
}

<?php

namespace App\Http\Controllers;

use App\Helpers\ApiCodes;
use App\Helpers\Permissions;
use App\Models\Department;
use App\Models\KnowledgeBase;
use App\Models\Ticket;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class DepartmentsController extends Controller
{
    use ApiTrait;

    public function permissionCheck()
    {
        $permission = new Permissions;
        return $permission;
    }


    public function index()
    {
        $departments = Department::all();

        return $this->successResponse($departments);
    }

    public function getDepartmentData(Request $request)
    {
        $data = Department::query();

        return DataTables::of($data)
            ->addColumn('total_tickets', function ($data){
                $ticketRoute = route('departmentTickets', $data->id);
                return '<a href="'.$ticketRoute.'">'.$data->tickets->count().'</a>';
            })->addColumn('total_kb', function ($data){
                $kbRoute = route('Knowledge.categoryPost', $data->id);
                return '<a href="'.$kbRoute.'" target="_blank">'.$data->knowledgeBase->count().'</a>';
            })
            ->addColumn('action', function ($data) {

                $value = '<button type="button" class="btn btn-primary btn-sm" id="getEditDepartmentData" data-id="'.$data->id.'" title="Edit"><i class="fa fa-edit"></i></button>
                           <button type="button" data-id="'.$data->id.'" data-toggle="modal" data-target="#DeleteProductModal" class="btn btn-danger btn-sm" id="getDeleteId" title="Delete"><i class="la la-trash-o"></i></button>
                        ';

                return $value;
            })
            ->rawColumns(['total_tickets','total_kb','ticket_title','action','ticket_status'])->make(true);
    }

    public function departmentTickets(Request $request,$id)
    {
        $user = Auth::user();
        $tickets = Ticket::with('department')->where('department_id', $id)->paginate(15);

        if ($request->ajax()){
            if (($request->has('startDate')) && ($request->has('endDate'))) {
                $data = Ticket::with('department')->where('department_id', $id)->whereBetween(DB::raw('DATE(created_at)'), [$request->startDate, $request->endDate]);
            }else{
                $data = Ticket::with('department')->where('department_id', $id);
            }

            return Datatables::of($data)
                ->addColumn('ticket_title', function ($data) {
                    $ticketRoute = route('ticket.show', $data->ticket_id);
                    $val = '<a href="' . $ticketRoute . '">'.$data->title.'</a>';
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
                        $statusValue = '<span class="badge badge-warning">'.$data->status.'</span>';
                    } else {
                        $statusValue = '<span class="badge badge-success">'.$data->status.'</span>';
                    }
                    return $statusValue;
                })
                ->addColumn('updated', function ($data) {
                    return $data->updated_at->format('Y m d, h:i A');
                })
                ->addColumn('action', function ($data) use($user) {
                    $closeRoute = route('close_ticket.close', $data->ticket_id);
                    $viewRoute = route('ticket.show', $data->ticket_id);
                    $reopenRoute = route('ticketReOpen', $data->ticket_id);
                    $assign = '';
                    $reopen = '';
                    if ($user->is_admin){
                        $assign = '<button type="button" class="badge bg-info pointer" id="getAssignedTicketData" data-id="'.$data->id.'" title="Re-Assign Department">'.__('lang.reassign').'</button>';
                        $reopen = '<form action="' . $reopenRoute . '" method="post" id="reopen_form_' . $data->id . '">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                                    <button title="Reopen Ticket" type="submit"  class="badge bg-red pointer" data-id="reopen_form_' . $data->id . '">'.__("lang.reopen").'</button>
                                </form>';
                    }

                    if ($data->status === "Open") {
                        $value = '<a href="' . $viewRoute . '"
                                   class="badge bg-primary text-white" title="Reply">'.__("lang.reply").'</a>
                               <form action="' . $closeRoute . '" method="post" id="close_form_' . $data->id . '">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <button title="Close" type="submit"  class="badge bg-red pointer" data-id="close_form_' . $data->id . '">'.__("lang.close").'</button>
                            </form>
                            '.$assign;
                    } else {
                        $value = $reopen;
                    }

                    return $value;
                })
                ->rawColumns(['ticket_title','action','ticket_status'])->make(true);
        }
        
        return view('departments.departmenttcickets', compact('tickets'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'     => 'required',
            'description'     => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $ticket = new Department([
            'title'     => $request->input('title'),
            'description'   => $request->input('description'),
        ]);

        if ($ticket->save()) {
            return $this->jsonResponse('New department added', ApiCodes::SUCCESS);
        } else{
            return $this->generalError('Something went wrong');
        }
    }


    public function show($id)
    {
        $department = Department::find($id);

        return $this->successResponse($department);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'     => 'required',
            'description'     => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $department = Department::find($id);
        $department->title = $request->title;
        $department->description = $request->description;
        $department->save();

        return $this->jsonResponse('Ticket assigned successfully', ApiCodes::SUCCESS);
    }

    public function destroy($id)
    {
        $department = Department::find($id);

        $used = Ticket::where('department_id', $department->id)->count();
        $usekb = KnowledgeBase::where('department_id', $department->id)->count();

        if($used == 0 && $usekb == 0){
            $department->delete();
            return $this->jsonResponse('Deleted successfully', ApiCodes::SUCCESS);
        } else {
            return $this->generalError("This department is used, you can't delete this.");
        }
    }
}

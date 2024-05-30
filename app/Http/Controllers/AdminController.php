<?php

namespace App\Http\Controllers;

use App\Helpers\ApiCodes;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    use ApiTrait;

    public function staffList()
    {
        if (\request()->ajax()) {
            $data = User::with('department', 'role')
                ->where('users.user_type', 1);

            return DataTables::of($data)
                ->addColumn('department', function ($data) {
                    return optional($data->department)->title;
                })->addColumn('role', function ($data) {
                    return optional($data->role)->title;
                })->addColumn('status', function ($data) {
                    if ($data->status === 1) {
                        $statusValue = '<span class="badge badge-success">' . __('lang.active') . '</span>';
                    } else {
                        $statusValue = '<span class="badge badge-danger">' . __('lang.inactive') . '</span>';
                    }
                    return $statusValue;
                })
                ->addColumn('action', function ($data) {
                    $statusRoute = route('staff-status.action', $data->id);
                    $editRoute = route('staff-edit.staffEdit', $data->id);
                    if ($data->status == '1') {
                        $status = '<form action="' . $statusRoute . '" method="POST">
                                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                                <input type="hidden" name="status" value="0">
                                                <button type="submit" class="badge bg-red pointer">' . __("lang.inactive") . '</button>
                                            </form>';
                    } else {
                        $status = '<form action="' . $statusRoute . '" method="POST">
                                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                                <input type="hidden" name="status" value="1">
                                                <button type="submit" class="badge bg-red pointer">' . __("lang.active") . '</button>
                                            </form>';
                    }
                    return '<a href="' . $editRoute . '" class="badge bg-primary text-white" title="Edit">
                                            <i class="fa fa-pencil"></i>
                                        </a>' . $status;
                })
                ->rawColumns(['status', 'action'])->make(true);
        }
        return view('staff.index');
    }

    public function userList()
    {
        if (\request()->ajax()) {
            $data = User::with('tickets')->where(['is_admin' => 0, 'user_type' => 0]);

            return DataTables::of($data)
                ->addColumn('tickets', function ($data) {
                    return $data->tickets->count();
                })
                ->addColumn('action', function ($data) {
                    $statusRoute = route('staff-status.action', $data->id);
                    $editRoute = route('userEdit', $data->id);
                    if ($data->status == '1') {
                        $status = '<form action="' . $statusRoute . '" method="POST">
                                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                                <input type="hidden" name="status" value="0">
                                                <button type="submit" class="badge bg-red pointer">' . __("lang.block") . '</button>
                                            </form>';
                    } else {
                        $status = '<form action="' . $statusRoute . '" method="POST">
                                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                                <input type="hidden" name="status" value="1">
                                                <button type="submit" class="badge bg-red pointer">' . __("lang.unblock") . '</button>
                                            </form>';
                    }
                    return '<a href="' . $editRoute . '" class="badge bg-primary text-white mb-1" title="Edit">
                                            <i class="fa fa-pencil"></i>
                                        </a>' . $status;
                })
                ->rawColumns(['status', 'action'])->make(true);
        }

        return view('users.index');
    }

    public function staffEdit($id)
    {
        $user = User::findOrFail($id);
        $departments = Department::all();
        $roles = Role::all();

        return $this->successResponse([
            'user' => $user,
            'departments' => $departments,
            'roles' => $roles,
        ]);
    }

    public function createStaff()
    {
        $departments = Department::all();
        $roles = Role::all();

        return $this->successResponse([
            'departments' => $departments,
            'roles' => $roles,
        ]);
    }

    public function saveStaff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'department' => 'required',
            'role' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $name = $request->name;
        $email = $request->email;
        $role = $request->role;
        $department = $request->department;
        $status = $request->status;

        $saved = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($request->password),
            'user_type' => 1,
            'role_id' => $role,
            'department_id' => $department,
            'status' => $status,
        ]);

        if ($saved) {
            return $this->jsonResponse('Staff was added', ApiCodes::SUCCESS);
        } else {
            return $this->generalError('Something went wrong');
        }
    }

    public function staffUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'department' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $name = $request->name;
        $email = $request->email;
        $department = $request->department;
        $status = $request->status;
        $role = $request->role;

        $user = User::find($id);

        if ($user->email == $email) {
            $user->name = $name;
            $user->department_id = $department;
            $user->status = $status;
            $user->role_id = $role;
        } else {
            $user->name = $name;
            $user->email = $email;
            $user->department_id = $department;
            $user->status = $status;
            $user->role_id = $role;
        }

        if ($user->save()) {
            return $this->jsonResponse('Staff was updated', ApiCodes::SUCCESS);
        } else {
            return $this->generalError('Something went wrong');
        }
    }

    public function action(Request $request, $id)
    {
        $status = $request->status;

        $done = User::where('id', $id)->update(['status' => $status]);

        if ($done) {
            return $this->jsonResponse('Staff status was updated', ApiCodes::SUCCESS);
        } else {
            return $this->generalError('Something went wrong');
        }
    }
}

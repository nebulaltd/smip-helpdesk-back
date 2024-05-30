<?php

namespace App\Http\Controllers;

use App\Helpers\ApiCodes;
use App\Models\Role;
use App\Traits\ApiTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    use ApiTrait;

    public function index()
    {
        $roles = Role::orderBy('id', 'desc')->paginate(10);

        return $this->successResponse($roles);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $title = $request->input('title');
        $permissions = $request->input('permissions');
        $serializePermission = '';
        if ($permissions == null) {
            $serializePermission = 'a:1:{i:0;s:15:"can_manage_null";}';
        } else {
            $serializePermission = serialize($permissions);
        }

        $created_by = Auth::user()->id;

        $role = new Role(
            [
                'title' => $title,
                'permissions' => $serializePermission,
                'created_by' => $created_by
            ]
        );

        if ($role->save()) {
            return $this->successResponse();
        } else {
            return $this->generalError("Something went wrong!");
        }
    }

    public function editPermission($id)
    {
        $data = Role::find($id);
        $permissions = unserialize($data->permissions);
        $data->permissions = $permissions;

        $role = Role::select('title', 'permissions')->where('id', $id)->first();

        return $this->successResponse([
            'role' => $role,
            'data' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $title = $request->input('title');
        $permissions = serialize($request->input('permissions'));

        $role = Role::findOrFail($id);

        if ($role) {
            $role->title = $title;
            $role->permissions = $permissions;

            $role->save();

            return $this->successResponse();
        } else {
            return $this->generalError("Something went wrong!");
        }
    }

    public function delete($id)
    {
        $role = Role::find($id);

        $user = User::where('role_id', $role->id)->count();

        if ($user == 0) {
            $role->delete();

            return $this->successResponse();
        } else {
            return $this->generalError("This role is used, you can't delete this!");
        }
    }
}

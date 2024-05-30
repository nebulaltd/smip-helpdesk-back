<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiCodes;
use App\Http\Controllers\Controller;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    use ApiTrait;

    public function contactMessage()
    {
    	$messages = Contact::latest()->paginate(15);

        return $this->successResponse($messages);
    }

    public function readMessage(Contact $contact)
    {
    	if ($contact->status == 0) {
    		Contact::where('id', $contact->id)->update(['status' => 1]);
    	}

        return $this->successResponse($contact);
    }

    public function destroy($id)
    {
    	$message = Contact::find($id);
    	$done = $message->delete();

        if ($done) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function saveUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $saved = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'status' => $request->status,
                ]);

        if ($saved) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function userEdit($id)
    {
        $user = User::find($id);

        return $this->successResponse($user);
    }

    public function userUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $name = $request->name;
        $email = $request->email;
        $password = $request->email;
        $status = $request->status;

        $user = User::find($id);

        if ($user->email == $email){
            $user->name = $name;
            $user->password = Hash::make($password);
            $user->status = $status;
        } else {
            $user->name = $name;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->status = $status;
        }

        if ($user->save()) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }
}

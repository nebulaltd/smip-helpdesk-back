<?php

namespace App\Http\Controllers;

use App\Helpers\ApiCodes;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use ApiTrait;

    public function index()
    {
        $user = Auth::user();

        return $this->successResponse($user);
    }

    public function profileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $user = Auth::id();
        $profile = User::find($user);
        $profile->name = $request->name;
        $profile->email = $request->email;

        $oldImage = $profile->image;
        $img = $request->file('avatar');
            if($img)
                $profile->avatar = storeThumb($img);

            if ($oldImage)
                Storage::delete("/public/" . $oldImage);

        if ($profile->save()) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function changedPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $password = $request->password;

        $user = Auth::user();
        $user->password = Hash::make($password);

        if ($user->save()) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }
}

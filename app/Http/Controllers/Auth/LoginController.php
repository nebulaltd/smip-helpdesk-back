<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use function response;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (\Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                return response()->json(['token' => $token, 'user' => $user], 200);
            } else {
                $response = ["message" => "Incorrect credentials"];
                return response()->json($response, 422);
            }
        } else {
            $response = ["message" => 'Incorrect credentials'];
            return response()->json($response, 422);
        }
    }
}

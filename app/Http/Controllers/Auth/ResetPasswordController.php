<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mailers\AppMailer;
use App\Traits\EmailTrait;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords, EmailTrait;

    public function redirectTo()
    {
        if (auth()->user()->is_admin || auth()->user()->user_type) {
            return '/dashboard';
        } else {
            return '/';
        }
    }

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function reset(Request $request, AppMailer $mailer)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        $password = $request->password;
        $token = $request->token;

        $hasData = DB::table('password_resets')->select('email')->where('token',$token)->first();
        $user = User::where('email',$hasData->email)->first();

        if($user)
        {
            $user->password = Hash::make($password);
            $user->setRememberToken(Str::random(60));

            if ($user->save()){
                DB::table('password_resets')->where('token',$token)->delete();

                $mailText = $this->passwordChangedNotification();
                $subject = 'Password Changed Successfully';

                $mailer->sendEmail($mailText,$user->email,$subject);

                $this->guard()->login($user);

                $notify = emailNotify('Password changed');

                return redirect()->route('dashboard')->with($notify);
            }
        }
        return back();
    }
}

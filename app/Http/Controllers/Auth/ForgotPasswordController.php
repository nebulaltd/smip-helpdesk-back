<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mailers\AppMailer;
use App\Models\EmailTemplate;
use App\Models\Setting;
use App\Traits\EmailTrait;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails, EmailTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function recoverResetLinkEmail(Request $request, AppMailer $mailer)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(60);
        $email = $request->get('email');

        try {
            $exist = DB::table('password_resets')->where('email', $email)->first();

            if ($exist) {
                DB::table('password_resets')
                    ->where('email', $email)
                    ->update([
                        'token' => $token,
                        "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                        ]);
            }else {
                DB::table('password_resets')->insert([
                    'email' => $email,
                    'token' => $token,
                    "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                ]);
            }

            $mailText = $this->resetPasswordTemplate($token);

            $subject = "Reset Password";

            if ($mailer->sendEmail($mailText,$email,$subject)) {
                $notify = emailNotify('Your password reset link send');
            }else{
                $notify = errorNotify("Password reset link sending");
            }
            
            return redirect()->back()->with($notify);

        } catch (\Exception $e) {
            //Return with error
            $notify = errorNotify($e->getMessage());
            
            return back()->with($notify);
        }
    }
}

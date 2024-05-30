<?php

namespace App\Http\Controllers;

use App\Helpers\ApiCodes;
use App\Traits\ApiTrait;
use App\Traits\EmailSettingTrait;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Mailers\AppMailer;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    use EmailSettingTrait, ApiTrait;

    public function settingIndex()
    {
        $setting = GeneralSetting::first();

        return $this->successResponse($setting);
    }

    public function appSettingUpdate(Request $request, GeneralSetting $setting)
    {
        $data = $request->all();
        $update = $setting->update($data);

        if ($update) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function emailSetting()
    {
        $setting = GeneralSetting::first();

        return $this->successResponse($setting);
    }

    public function emailSettingUpdate(Request $request, GeneralSetting $setting)
    {
        $validator = Validator::make($request->all(), [
            'from_name' => 'required',
            'from_email' => 'required',
            'mail_driver' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $update = $this->emailSettingUpdateData($setting, $request);

        if ($update) {
            if ($request->test_mail) {

                if ($this->testMail($request->test_mail)) {
                    return $this->successResponse();
                } else {
                    return $this->generalError('Email setting updated & Test Email send fail!');
                }
            } else {
                return $this->successResponse();
            }
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function testMail($email)
    {
        $app = GeneralSetting::first();
        $subject = 'Test email';
        $emailHeader = '<html>
                           <div style="width: 35%; color: #333333; font-family: Helvetica; margin:auto; font-size: 125%; padding-bottom: 10px;">
                               <div style="text-align:center; padding-top: 10px; padding-bottom: 10px;">
                                   <h1>' . $app->app_name . '</h1>
                               </div>
                               <div style="padding: 35px;padding-left:20px; border-bottom: 1px solid #cccccc; border-top: 1px solid #cccccc;">';
        $emailFooter = '        </div>
                           </div>
                       </html>';

        $mailText = $emailHeader . 'This is a test email. Your email config working' . $emailFooter;
        $mailer = new AppMailer;

        try {
            $mailer->sendEmail($mailText, $email, $subject);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}

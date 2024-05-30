<?php

namespace App\Traits;

use App\Models\EmailTemplate;
use App\Models\GeneralSetting;

trait EmailSettingTrait
{
    protected function emailSettingUpdateData($setting, $request)
    {
        if ($request->mail_driver == 'sendmail') {
            $update = $setting->update([
                'from_name' => $request->from_name,
                'from_email' => $request->from_email,
                'mail_driver' => $request->mail_driver,
                "smtp_host" => null,
                "smtp_port" => null,
                "smtp_username" => null,
                "smtp_encryption" => null,
                "smtp_password" => null,
                "mailgun_domain" => null,
                "mailgun_api" => null,
            ]);
            return $update;
        } elseif ($request->mail_driver == 'mailgun') {
            $update = $setting->update([
                'from_name' => $request->from_name,
                'from_email' => $request->from_email,
                'mail_driver' => $request->mail_driver,
                "smtp_host" => null,
                "smtp_port" => null,
                "smtp_username" => null,
                "smtp_encryption" => null,
                "smtp_password" => null,
                "mailgun_domain" => $request->mailgun_domain,
                "mailgun_api" => $request->mailgun_api,
            ]);
            return $update;
        } elseif ($request->mail_driver == 'smtp') {
            $update = $setting->update([
                'from_name' => $request->from_name,
                'from_email' => $request->from_email,
                'mail_driver' => $request->mail_driver,
                "smtp_host" => $request->smtp_host,
                "smtp_port" => $request->smtp_port,
                "smtp_username" => $request->smtp_username,
                "smtp_password" => $request->smtp_password,
                "smtp_encryption" => $request->smtp_encryption,
                "mailgun_domain" => null,
                "mailgun_api" => null,
            ]);
            return $update;
        }
    }
}

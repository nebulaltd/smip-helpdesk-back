<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailTemplateTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Email Header
        $emailHeader = '<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; color: #74787E; height: 100%; hyphens: auto; line-height: 1.4; margin: 0; -moz-hyphens: auto; -ms-word-break: break-all; width: 100% !important; -webkit-hyphens: auto; -webkit-text-size-adjust: none; word-break: break-word;">
    <style>
        @media  only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media  only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
        @media  only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media  only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>

    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
        <tr>
            <td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">

                    <tr>
                        <td class="header" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 25px 0; text-align: center;">
                            <a href="#" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 19px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;">
                                Helpy
                            </a>
                        </td>
                    </tr>';
        $emailFooter = '<tr>
                        <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                            <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0 auto; padding: 0; text-align: center; width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px;">
                                <tr>
                                    <td class="content-cell" align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px;">
                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #AEAEAE; font-size: 12px; text-align: center;">© 2020 Helpy. All rights reserved.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';
        
        // Email Header
        $emailHeader = '<html>
                            <div style="max-width: 60%; color: #000000 !important; font-family: Helvetica; margin:auto; padding-bottom: 10px;">
                                <div style="text-align:center; padding-top: 10px; padding-bottom: 10px; background: #e9ecef;box-shadow: 0 5px 5px -6px #777;">
                                    <h1>{app_name}</h1>
                                </div>
                                <div style="padding: 35px;padding-left:20px; font-size:17px; border-bottom: 1px solid #cccccc; border-top: 1px solid #cccccc;">';
        $emailFooter = '        </div>
                            </div>
                        </html>';
        
        DB::table("email_templates")->insert([
            'template_type' => 'user_invitation',
            'template_subject' => 'You are invited',
            'default_content' =>
                $emailHeader.'Hi,<br>
                {invited_by} invited you to join with the team on {app_name}.<br>
                Please click the link below to accept the invitation!<br>
                {verification_link}'.$emailFooter
        ]);

        DB::table("email_templates")->insert([
            'template_type' => 'account_verification',
            'template_subject' => 'Account verification',
            'default_content' =>
                        $emailHeader.'Hi {user_name},<br>
                        Your account has been created.<br>
                        Please click the link below to verify your email.<br>
                        {verification_link}'.$emailFooter
        ]);


        DB::table("email_templates")->insert([
            'template_type' => 'reset_password',
            'template_subject' => 'Reset password',
            'default_content' =>
                        $emailHeader.'Hi,<br>
                        You have requested to change your password.<br>
                        Please click the link below to change your password!<br><br>
                        <center><a href="{reset_password_link}" class="button button-primary" target="_blank" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-radius: 3px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16); color: #FFF; display: inline-block; text-decoration: none; -webkit-text-size-adjust: none; background-color: #3097D1; border-top: 10px solid #3097D1; border-right: 18px solid #3097D1; border-bottom: 10px solid #3097D1; border-left: 18px solid #3097D1;">Reset Password</a><br>
                        <a href="{reset_password_link}">{reset_password_link}</a></center>'.$emailFooter
        ]);

        DB::table("email_templates")->insert([
            'template_type' => 'ticket_status',
            'template_subject' => 'Your ticket status changed',
            'default_content' =>
                $emailHeader.'Hello {ticket_owner_name},<br>
                        <p>
                            Your support ticket with ID #{ticket_id} has been marked resolved and closed.
                        </p>
                        <p>You can view the ticket at any time at <a href="{ticket_view_url}">{ticket_view_url}</a></p><br>
                        <p>Thank you</p>'.$emailFooter
        ]);

        DB::table("email_templates")->insert([
            'template_type' => 'ticket_comments',
            'template_subject' => 'Replied your ticket {user_name}',
            'default_content' =>
                $emailHeader.'<p>Replied by: {reply_by_name}</p><br>
                        <p>Title: {ticket_title}</p><br>
                        <p>ID: {ticket_id}</p><br>
                        <br>{comment_text},<br><br>
                        <p>Status: {ticket_status}</p><br>
                        <br>
                        <p>You can view the ticket at any time at <a href="{ticket_view_url}">{ticket_view_url}</a></p><br>
                        
                        <p>Thank you</p>'.$emailFooter
        ]);

        DB::table("email_templates")->insert([
            'template_type' => 'ticket_info',
            'template_subject' => 'Your Ticket Information',
            'default_content' =>
                $emailHeader.'<p>
        Thank you {user_name} for contacting our support team. A support ticket has been opened for you. You will be notified when a response is made by email. The details of your ticket are shown below:
    </p>
                         <br>
                        <p>Priority: {ticket_priority}</p>
                        <p>Status: {ticket_status}</p><br>
                        <p>You can view the ticket at any time at <a href="{ticket_view_url}">{ticket_view_url}</a></p><br>'.$emailFooter
        ]);

        DB::table("email_templates")->insert([
            'template_type' => 'password_changed',
            'template_subject' => 'Password Changed Successfully',
            'default_content' =>
                $emailHeader.'<p>
        Your have successfully changed password.
    </p>
                         <br>
                        <p>Thank for stay with us.</p><br>'.$emailFooter
        ]);
    }
}

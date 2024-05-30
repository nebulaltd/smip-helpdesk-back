<?php

namespace App\Traits;

use App\Models\EmailTemplate;
use App\Models\GeneralSetting;

trait EmailTrait
{
    protected function appSetting()
    {
        $gs = GeneralSetting::first();

        return $gs;
    }

    protected function getEmailTemplate($type)
    {
        $commentText = EmailTemplate::where('template_type',$type)->first();

        if ($commentText->custom_content) {
            $text = $commentText->custom_content;
        } else {
            $text = $commentText->default_content;
        }

        return $text;
    }

    protected function newTicketSubmitTemplate($user,$ticket)
    {
        $type = 'ticket_info';
        $text = $this->getEmailTemplate($type);
        $ticketLink = \Request::root().'/ticket/'.$ticket->ticket_id;
        $template = str_replace('{app_name}', $this->appSetting()->app_name,str_replace('{user_name}', $user->name, str_replace('{ticket_priority}', ucfirst($ticket->priority), str_replace('{ticket_status}',$ticket->status,str_replace('{ticket_view_url}',$ticketLink, $text)))));

        return $template;
    }

    protected function commentTemplate($user,$ticket,$subject,$comment)
    {
        $type = 'ticket_comments';
        $text = $this->getEmailTemplate($type);
        $ticketViewUrl = \Request::root().'/ticket/'.$ticket->ticket_id;
        $template = str_replace('{ticket_title}', $subject, str_replace('{ticket_id}', $ticket->ticket_id, str_replace('{reply_by_name}', $user->name, str_replace('{comment_text}', $comment->comment, str_replace('{ticket_status}', $ticket->status, str_replace('{ticket_view_url}', $ticketViewUrl,str_replace('{app_name}',$this->appSetting()->app_name, $text)))))));

        return $template;
    }

    protected function resetPasswordTemplate($token)
    {
        $type = "reset_password";
        $text = $this->getEmailTemplate($type);
        $resetLink = \Request::root().'/password/reset/'.$token;
        $template = str_replace('{reset_password_link}', $resetLink,str_replace('{app_name}', $this->appSetting()->app_name, $text));

        return $template;
    }

    protected function passwordChangedTemplate()
    {
        $type = "password_changed";
        $text = $this->getEmailTemplate($type);
        $template = str_replace('{app_name}',$this->appSetting()->app_name, $text);

        return $template;
    }

    protected function sendTicketStatusNotification($ticketOwner, $ticket)
    {
        $type = "ticket_status";
        $text = $this->getEmailTemplate($type);
        $ticketViewUrl = \Request::root().'/ticket/'.$ticket->ticket_id;
        $template = str_replace('{app_name}',$this->appSetting()->app_name, str_replace('{ticket_owner_name}',$ticketOwner->name, str_replace('{ticket_id}',$ticket->ticket_id, str_replace('{ticket_view_url}',$ticketViewUrl,$text))));

        return $template;
    }

    protected function passwordChangedNotification()
    {
        $type = "password_changed";
        $text = $this->getEmailTemplate($type);
        $template = str_replace('{app_name}',$this->appSetting()->app_name, $text);

        return $template;
    }
}

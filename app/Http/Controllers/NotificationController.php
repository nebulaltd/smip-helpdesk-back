<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Traits\ApiTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    use ApiTrait;

    public function allNotification()
    {
        $notifications =  Auth::user()->notifications()->paginate(15);

        return $this->successResponse($notifications);
    }

    public function notificationCount()
    {
        return Auth::user()->unreadNotifications()->count();
    }

    public function notificationRead($id)
    {
        $notification = Notification::findOrFail(\request()->id);
        $notification->read_at = now();
        $notification->save();
        return $this->successResponse();
    }
}

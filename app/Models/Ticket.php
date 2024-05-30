<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
    'user_id', 'department_id', 'ticket_id', 'title', 'priority', 'message', 'status'
];

public function department()
	{
	    return $this->belongsTo(Department::class);
	}

	public function comments()
	{
	    return $this->hasMany(Comment::class);
	}

	public function user()
	{
	    return $this->belongsTo(User::class);
	}
	
	public function ticketCustomField()
    {
        return $this->hasMany('App\Models\TicketCustomField');
    }

}

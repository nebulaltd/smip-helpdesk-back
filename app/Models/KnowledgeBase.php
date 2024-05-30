<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBase extends Model
{
	const PINNED = 1;
	const UNPINNED = 0;
    //article status
    const PUBLISHED = 1;
    const UNPUBLISHED = 0;

    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function satisfiedVote()
    {
        return $this->hasMany('App\Models\Vote','voteable_id','id')->where('satisfied','!=',0);
    }

    public function disSatisfiedVote()
    {
        return $this->hasMany('App\Models\Vote', 'voteable_id','id')->where('satisfied','=',0);
    }

    public function vote()
    {
        return $this->morphMany(Vote::class, 'voteable');
    }
}

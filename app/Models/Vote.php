<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $guarded = [];
    
    const SATISFIED = 1;
    const DISSATISFIED = 0;
	
    public function voteable()
    {
        return $this->morphTo();
    }
}

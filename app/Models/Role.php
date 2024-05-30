<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['title','permissions','created_by'];

    public function user()
    {
        return $this->hasMany(User::class);
    }
}

<?php

namespace App\Models;
use App\Models\FieldsOption;

use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    protected $guarded = [];

    const ACTIVE = 1;
    const INACTIVE = 0;

    public function options()
    {
        return $this->hasMany(FieldsOption::class, 'field_id');
    }
}

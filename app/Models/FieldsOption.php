<?php

namespace App\Models;
use App\Models\CustomField;

use Illuminate\Database\Eloquent\Model;

class FieldsOption extends Model
{
    protected $guarded = [];

    public function field()
    {
        return $this->belongsTo(CustomField::class, 'field_id');
    }
}

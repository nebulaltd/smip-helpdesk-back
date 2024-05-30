<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketCustomField extends Model
{
    protected $guarded = [];
    
    public function ticket()
    {
        return $this->belongsToMany('App\Models\Ticket', 'ticket_custom_field', 'custom_field_id', 'ticket_id');
    }

    public function fields()
    {
        return $this->belongsTo("App\Models\CustomField", "custom_field_id");
    }
}

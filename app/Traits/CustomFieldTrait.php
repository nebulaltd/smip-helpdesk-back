<?php

namespace App\Traits;

use App\Models\KnowledgeBase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Models\TicketCustomField;

trait CustomFieldTrait
{
    protected function customFieldStoreLogic($request, $ticketID)
    {
        if($request->text_field_id && $request->text_value){
            $this->storeTextFields($request, $ticketID);
        }

        if($request->option_field_id && $request->option_value){
            $this->storeOptionFields($request, $ticketID);
        }

        if($request->radio_field_id && $request->radio_value){
            $this->storeRadioFields($request, $ticketID);
        }

        if($request->checkbox_field_id && $request->checkbox_value){
            $this->storeCheckboxFields($request, $ticketID);
        }

        if($request->file_field_id && $request->file_value){
            $this->storeFileFields($request, $ticketID);
        }
    }

    protected function storeTextFields($request, $ticketID)
    {
        $textFieldCount = count($request->text_field_id);

        $tfArray = array();

        for ($i=0; $i < $textFieldCount; $i++) {
            $tfArray = [
                'ticket_id' => $ticketID,
                'custom_field_id' => $request->text_field_id[$i],
                'value' => $request->text_value[$i]
            ];
        }

        $this->customFieldSave($tfArray);
    }

    protected function storeOptionFields($request, $ticketID)
    {
        $optionFieldCount = count($request->option_field_id);

        $ofArray = array();

        for ($i=0; $i < $optionFieldCount; $i++) {
            $ofArray = [
                'ticket_id' => $ticketID,
                'custom_field_id' => $request->option_field_id[$i],
                'value' => $request->option_value[$i]
            ];
        }

        $this->customFieldSave($ofArray);
    }

    protected function storeRadioFields($request, $ticketID)
    {
        $radioFieldCount = count($request->radio_field_id);

        $rfArray = array();

        for ($i=0; $i < $radioFieldCount; $i++) {
            $rfArray = [
                'ticket_id' => $ticketID,
                'custom_field_id' => $request->radio_field_id[$i],
                'value' => $request->radio_value[$i]
            ];
        }

        $this->customFieldSave($rfArray);
    }

    protected function storeCheckboxFields($request, $ticketID)
    {
        $checkboxFieldCount = count($request->checkbox_field_id);

        $cfArray = array();

        for ($i=0; $i < $checkboxFieldCount; $i++) {
            $cfArray = [
                'ticket_id' => $ticketID,
                'custom_field_id' => $request->checkbox_field_id[$i],
                'value' => $request->checkbox_value[$i]
            ];
        }

        $this->customFieldSave($cfArray);
    }

    protected function storeFileFields($request, $ticketID)
    {
        $fileFieldCount = count($request->file_field_id);
        //dd($request->all());
        $ffArray = array();
        $value = '';
        for ($i=0; $i < $fileFieldCount; $i++) {

            if ($request->hasFile('file_value')) {
                $image = $request->file('file_value');
                $value = storeOriginalImage($image[$i], 'uploads/customs');
            }
            $ffArray = [
                'ticket_id' => $ticketID,
                'custom_field_id' => $request->file_field_id[$i],
                'value' => $value
            ];
        }
        
        $this->customFieldSave($ffArray);
    }

    protected function customFieldSave($data)
    {
        TicketCustomField::insert($data);
    }
}

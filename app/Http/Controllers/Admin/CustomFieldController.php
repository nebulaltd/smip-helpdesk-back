<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiCodes;
use App\Http\Controllers\Controller;
use App\Models\TicketCustomField;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Models\CustomField;
use App\Models\FieldsOption;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CustomFieldController extends Controller
{
    use ApiTrait;

    public function getCustomFieldData(Request $request)
    {
        $data = CustomField::query();

        return DataTables::of($data)
            ->addColumn('required', function($data){
                if($data->required == 1){
                    return "Yes";
                }else{
                    return "No";
                }
            })
            ->addColumn('status', function($data){
                if($data->status == 1){
                    return "Active";
                }else{
                    return "Inactive";
                }
            })
            ->addColumn('action', function ($data) {
                $optionRoute = route('CustomFieldOptions', $data->id);
                $option = '';
                if($data->type == 'select' || $data->type == 'radio'){
                    $option = '<a href="'.$optionRoute.'" class="btn btn-info btn-sm"><i class="fa fa-cog"></i> Options</a>';
                }else{
                    $option = '';
                }

                $value = '<button type="button" class="btn btn-primary btn-sm" id="getEditCustomFieldData" data-id="'.$data->id.'" title="Edit"><i class="fa fa-edit"></i></button>
                           <button type="button" data-id="'.$data->id.'" data-toggle="modal" data-target="#DeleteCustomFieldModal" class="btn btn-danger btn-sm" id="getDeleteId" title="Delete"><i class="la la-trash-o"></i></button>
                            '.$option;

                return $value;
            })
            ->rawColumns(['require','status','action'])->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $data = $request->all();
        $saved = CustomField::create($data);

        if ($saved) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function edit($id)
    {
        $field = CustomField::findOrFail($id);

        return $this->successResponse($field);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'type'     => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $field = CustomField::find($id);
        $field->name = $request->name;
        $field->type = $request->type;
        $field->placeholder = $request->placeholder;
        $field->field_length = $request->field_length;
        $field->required = $request->required;
        $field->status = $request->status;
        $field->save();

        return response()->json(['success'=>'Custom field updated successfully']);
    }

    public function fieldOptions($id)
    {
        $field = CustomField::findOrFail($id);

        return $this->successResponse($field);
    }

    public function fieldOptionsData(Request $request,$id)
    {
        $data = FieldsOption::where('field_id', $id);
        
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                // <button type="button" data-id="'.$data->id.'" data-toggle="modal" data-target="#DeleteOptionFieldModal" class="btn btn-danger btn-sm" id="getDeleteId" title="Delete"><i class="la la-trash-o"></i></button>
                $value = '<button type="button" class="btn btn-primary btn-sm" id="getEditOptionFieldData" data-id="'.$data->id.'" title="Edit"><i class="fa fa-edit"></i></button>
                           
                        ';

                return $value;
            })
            ->rawColumns(['action'])->make(true);
    }

    public function storeOption(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $option = new FieldsOption();
        $option->value = $request->value;
        $option->field_id = $id;

        if ($option->save()) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function optionEdit($id)
    {
        $option = FieldsOption::findOrFail($id);

        return $this->successResponse($option);
    }

    public function updateOption(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $option = FieldsOption::find($id);
        $option->value = $request->value;

        if ($option->save()) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function destroy($id)
    {
        $customField = CustomField::find($id);

        $used = TicketCustomField::where('custom_field_id', $customField->id)->count();

        if($used == 0){
            $customField->delete();

            return response()->json(['success'=>'Deleted successfully']);
        } else {
            return response()->json(['error'=>'This custom field is used, you can\'t delete this.']);
        }
    }
}

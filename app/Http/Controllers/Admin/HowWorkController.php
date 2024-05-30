<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiCodes;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\HowWork;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HowWorkController extends Controller
{
    use ApiTrait;

    public function index()
    {
        $setting = GeneralSetting::first();
        $works = HowWork::paginate(10);

        return $this->successResponse([
            'setting' => $setting,
            'works' => $works,
        ]);
    }


    public function update(Request $request, $id)
    {
        $data = $request->all();

        $work = HowWork::findOrFail($id);

        $saved = $work->update($data);

        if ($saved) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function destroy($id)
    {
        $done = HowWork::destroy($id);

        if ($done) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function howWorkUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
            'how_work_title' => 'required',
            'how_work_details' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $setting = GeneralSetting::first();
        $data = $request->all();
        $saved = $setting->update($data);

        if ($saved) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }
}

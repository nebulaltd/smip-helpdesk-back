<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiCodes;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\Service;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    use ApiTrait;

    public function index()
    {
        $setting = GeneralSetting::first();
        $services = Service::paginate(10);

        return $this->successResponse([
            'setting' => $setting,
            'services' => $services,
        ]);
    }

    public function store(Request $request, Service $service)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'icon' => 'required',
            'details' => 'required|max:150',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $data = $request->all();
        $saved = Service::create($data);

        if ($saved) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function update(Request $request, Service $service)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'icon' => 'required|max:150',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $data = $request->all();

        $saved = $service->update($data);

        if ($saved) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }


    public function destroy($id)
    {
        $done = Service::destroy($id);

        if ($done) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function servicesUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_title' => 'required|max:255',
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

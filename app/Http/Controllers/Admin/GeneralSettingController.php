<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiCodes;
use App\Models\Service;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Social;
use App\Models\GeneralSetting;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Artisan;
use Intervention\Image\Image;

class GeneralSettingController extends Controller
{
    use ApiTrait;

    public function social()
    {
        $socialList = Social::all();

        return $this->successResponse($socialList);
    }

    public function socialAdd(Request $request, Social $social) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:socials|max:150',
            'code' => 'required|unique:socials|max:150',
            'link' => 'required|max:150',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $data = $request->all();

        $saved = $social->create($data);

        if ($saved) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function socialUpdate(Request $request, Social $social) {

        $validator = Validator::make($request->all(), [
            'name' => ['required',
                Rule::unique('socials')->ignore($social->id), 'max:150',
            ],
            'code' => ['required',
                Rule::unique('socials')->ignore($social->id), 'max:150',
            ],
            'link' => 'required|max:150',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $data = $request->all();

        $saved = $social->update($data);

        if ($saved) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function socialDestroy($id)
    {
        $done = Social::destroy($id);

        if ($done) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function headerTextSetting()
    {
        $setting = GeneralSetting::first();

        return $this->successResponse($setting);
    }

    public function headerTextSettingUpdate(Request $request, GeneralSetting $setting)
    {
        $data = $request->all();
        $saved = $setting->update($data);

        if ($saved) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function aboutus()
    {
        $setting = GeneralSetting::first();
        return $this->successResponse($setting);
    }

    public function updateAboutUs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'aboutus_title' => 'max:255',
            'aboutus_details' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        if ($request->hasFile('aboutus_image')) {
            $image = $request->aboutus_image;
            $imageObj = Image::make($image);
            $imageObj->resize(530, 400)->save(public_path('images/bg/about_details.jpg'));
        }

        $id = $request->get('id');
        $setting = GeneralSetting::find($id);
        $data = $request->only('aboutus_title','aboutus_details');
        $saved = $setting->update($data);

        if ($saved) {
            Artisan::call('cache:clear');
            $notify = updateNotify('Information');
        }else{
            $notify = errorNotify('Information update');
        }

        //cache clear
        Artisan::call('cache:clear');

        return back()->with($notify);
    }

    public function contactus() {
        $setting = GeneralSetting::first();
        return $this->successResponse($setting);
    }

    public function updateContactus(Request $request) {

        $validator = Validator::make($request->all(), [
            'contact_title' => 'max:255',
            'contact_phone' => 'max:255',
            'contact_email' => 'email|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $id = $request->get('id');
        $setting = GeneralSetting::find($id);
        $data = $request->all();
        $saved = $setting->update($data);

        if ($saved) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function footer() {
        $setting = GeneralSetting::first();
        return $this->successResponse($setting);
    }

    public function updateFooter(Request $request) {

        $id = $request->get('id');
        $setting = GeneralSetting::find($id);
        $data = $request->all();
        $saved = $setting->update($data);

        if ($saved) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function services() {
        $setting = GeneralSetting::first();
        $servicesList = Service::all();
        return $this->successResponse(['setting' => $setting, 'servicesList' => $servicesList]);
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

    public function storeNewServices(Request $request, Service $service) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'icon' => 'required|max:150',
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

    public function updateNewServices(Request $request, Service $services) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'icon' => 'required|max:150',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $data = $request->all();

        $saved = $services->update($data);

        if ($saved) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function deleteServices($id) {
        $done = Service::destroy($id);

        if ($done) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }

    public function counter() {
        $setting = GeneralSetting::first();
        return $this->successResponse($setting);
    }

    public function updateCounter(Request $request, GeneralSetting $setting) {
        $validator = Validator::make($request->all(), [
            'ticket_counter' => 'required',
            'ticket_solved' => 'required',
            'kb_counter' => 'required',
            'client_counter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }
        
        $data = $request->all();
        $saved = $setting->update($data);

        if ($saved) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }
}

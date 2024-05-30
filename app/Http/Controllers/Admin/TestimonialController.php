<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiCodes;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Testimonial;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    use ApiTrait;

    public function index()
    {
        $setting = GeneralSetting::first();
        $testimonials = Testimonial::paginate(10);

        return $this->successResponse([
            'setting' => $setting,
            'testimonials' => $testimonials,
        ]);
    }

    public function store(Request $request, Testimonial $testimonial)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'designation' => 'max:255',
            'comment' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data['image'] = storeImage($image, 'uploads/testimonials',352, 352);
        }

        $saved = $testimonial->create($data);

        if ($saved) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }


    public function update(Request $request, Testimonial $testimonial) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'designation' => 'max:255',
            'comment' => 'required',
            'image' => 'image|mimes:jpeg,jpg,png',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $data = $request->all();

        if ($request->hasFile('image')) {
            if (!$testimonial->image == 'images/testimonials/testimonial.png'){
                if ($testimonial->image) {
                    unlink( symImagePath().$testimonial->image);
                }
            }

            $image = $request->image;
            $data['image'] = storeImage($image, 'uploads/testimonials',352, 352);
        }

        $saved = $testimonial->update($data);

        if ($saved) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }


    public function testimonialUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
            'testimonial_title' => 'required|max:255',
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


    public function destroy(Testimonial $testimonial) {

        if ($testimonial->image) {
            Storage::delete($testimonial->image);
        }
        $done = Testimonial::destroy($testimonial->id);

        if ($done) {
            return $this->successResponse();
        } else {
            return $this->generalError('Something went wrong!');
        }
    }
}

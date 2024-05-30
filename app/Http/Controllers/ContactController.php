<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Social;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
    	$socials = Social::all();
    	return view('contact', compact('socials'));
    }

    public function store(Request $request)
    {
    	$this->validate($request,[
    		'name' => 'required',
    		'phone' => 'required',
    		'email' => 'required|email',
    		'message' => 'required'
    	]);

    	$contact = Contact::create($request->all());

        if ($contact) {
            $notify = storeNotify('Your message submitted');
        }else{
            $notify = errorNotify('Your contact message submit fail!');
        }

    	return redirect()->back()->with($notify);
    }
}

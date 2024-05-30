<?php

namespace App\Http\Controllers;

use App\Models\HowWork;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Models\Service;
use App\Models\Department;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $testimonials = Testimonial::all();
        $services = Service::all();
        $departments = Department::inRandomOrder()->limit(6)->get();
        $works = HowWork::latest()->limit(3)->get();

        return view('welcome', compact('testimonials','services','departments','works'));
    }

    public function aboutusPage()
    {
        return view('aboutus');
    }
}

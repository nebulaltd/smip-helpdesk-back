<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Traits\ApiTrait;

class DashboardController extends Controller
{
    use ApiTrait;

    public function dashboard()
    {
        $departments = Department::all();

        return $this->successResponse($departments);
    }
}

<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class StaffHomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:staff');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // view/staff/home.blade.php
        return view('staff.home');
    }
}

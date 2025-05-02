<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PresidentDashboardController extends Controller
{
    public function index() {
    
        return view('president.dashboard');
    }
}

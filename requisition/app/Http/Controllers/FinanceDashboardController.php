<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinanceDashboardController extends Controller
{
    public function index() {
    
        return view('finance.dashboard');
    }
}

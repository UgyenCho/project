<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View; // Import the View class

class AboutPageController extends Controller
{
    /**
     * Display the about page.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // This simply returns the view file located at
        // resources/views/about.blade.php
        return view('aboutus');
    }
}
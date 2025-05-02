<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Use Auth
use App\Models\Requisition; // Use Requisition Model

class DashboardController extends Controller // Or your relevant controller
{
    /**
     * Display the user's dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $requisitions = Requisition::where('user_id', Auth::id())
                                    ->with('department')
                                    ->latest() // Order by most recent first
                                    ->get(); 
        // Pass the collection to the view
        return view('dashboard', compact('requisitions'));
        // Equivalent: return view('dashboard', ['requisitions' => $requisitions]);
    }
}
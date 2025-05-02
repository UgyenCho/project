<?php

namespace App\Http\Controllers;

use App\Models\Requisition; // Import Requisition model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Keep Auth facade
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse; // For redirects
use Illuminate\Support\Facades\Log;
use App\Models\User;

class HodDashboardController extends Controller
{
        // Inside app/Http/Controllers/HodDashboardController.php

        public function index(): View
        {
            $hodUser = Auth::user(); // Get the logged-in HOD
    
            // --- Add check for user and department ID ---
            if (!$hodUser || !$hodUser->department_id) {
                Log::warning('HOD Dashboard access attempt without valid department ID.', ['user_id' => $hodUser->id ?? null]);
                return view('hod.dashboard', ['requisitions' => collect()])->withErrors('User department information missing.');
            }
            // --- End check ---
    
            $hodDepartmentId = $hodUser->department_id; // <<< Get the HOD's actual department ID
    
            $statusForHodReview = 'Pending'; // Status HOD needs to see
    
            Log::info("HOD Dashboard: Fetching requisitions where department_id = {$hodDepartmentId} AND status = '{$statusForHodReview}'"); // Updated Log
    
            // --- Corrected Fetch Logic ---
            $requisitions = Requisition::where('department_id', $hodDepartmentId) // <<< FILTER BY HOD's DEPARTMENT
                                       ->where('status', $statusForHodReview)   // AND by status
                                       ->with('user:id,name') // Eager load user who submitted
                                       ->latest()             // Show newest first
                                       ->paginate(15);        // Paginate results
            // --- End Corrected Fetch Logic ---
            
            Log::info("HOD Dashboard: Found {$requisitions->total()} requisitions for Dept ID {$hodDepartmentId}."); // Updated Log
    
            return view('hod.dashboard', compact('requisitions')); // Pass filtered data to the view
        }

    // --------------------------------------------------------------------
    // --- Other Methods (show, approve, reject) - Status check updated ---
    // --------------------------------------------------------------------

    /**
     * Display the specified requisition details for HOD.
     * Uses Route Model Binding (type-hint Requisition)
     */
    public function show(Requisition $requisition): View|RedirectResponse
    {
        // TODO: Review Authorization: Should HOD see details of *any* LRC request,
        // or only those submitted for their specific department?
        // Example check (remove or modify if HOD can see any LRC req detail):
        $hodUser = Auth::user();
        $hodDepartment = $hodUser->department; // ** ADAPT department logic **
        if ($requisition->department !== $hodDepartment && $requisition->requester_designation != 14 /* Allow if LRC submitted */) {
            // This example allows viewing if it's for HOD's dept OR if submitted by LRC. Adjust as needed.
             return redirect()->route('hod.dashboard')
                              ->with('error', 'You are not authorized to view this specific requisition detail.');
        }
        // --- End Authorization Review ---

        $requisition->load('items');
        // Ensure this view exists or change to 'user.requisitions.show'
        return view('hod.requisitions.show', compact('requisition'));
    }

    /**
     * Approve the specified requisition.
     * Uses Route Model Binding
     */
    public function approve(Requisition $requisition): RedirectResponse
    {
        // TODO: Review Authorization (similar to show method)
        $hodUser = Auth::user();
        $hodDepartment = $hodUser->department; // ** ADAPT department logic **
        if ($requisition->department !== $hodDepartment && $requisition->requester_designation != 14) {
             return redirect()->route('hod.dashboard')->with('error', 'Unauthorized action.');
        }
        // --- End Authorization Review ---

        // Check if the status is the one HODs should approve ('Pending')
        if ($requisition->status !== 'Pending') { // <<< MODIFIED STATUS CHECK
             return redirect()->route('hod.dashboard')->with('warning', 'This requisition is not pending HOD approval.');
        }

        // --- UPDATE STATUS ---
        $requisition->status = 'Approved'; // <<< SET NEXT STATUS
        // Optionally record approver details
        // $requisition->hod_approver_id = $hodUser->id;
        // $requisition->hod_approved_at = now();
        $requisition->save();

        // Optionally: Send notification

        return redirect()->route('hod.dashboard')
                         ->with('success', "Requisition ID {$requisition->id} approved successfully.");
    }

    /**
     * Reject the specified requisition.
     * Uses Route Model Binding
     */
    public function reject(Request $request, Requisition $requisition): RedirectResponse
    {
       // TODO: Review Authorization (similar to show method)
        $hodUser = Auth::user();
        $hodDepartment = $hodUser->department; // ** ADAPT department logic **
        if ($requisition->department !== $hodDepartment && $requisition->requester_designation != 14) {
             return redirect()->route('hod.dashboard')->with('error', 'Unauthorized action.');
        }
        // --- End Authorization Review ---

        // Check if the status is the one HODs should reject ('Pending')
         if ($requisition->status !== 'Pending') { // <<< MODIFIED STATUS CHECK
             return redirect()->route('hod.dashboard')->with('warning', 'This requisition is not pending HOD approval.');
        }

        // --- UPDATE STATUS ---
        $requisition->status = 'Rejected by HOD'; // <<< SET REJECTED STATUS
        // Optionally record rejecter details and reason
        // $requisition->hod_rejecter_id = $hodUser->id;
        // $requisition->hod_rejected_at = now();
        // $requisition->rejection_reason = $request->input('rejection_reason'); // If reason is submitted
        $requisition->save();

        // Optionally: Send notification

        return redirect()->route('hod.dashboard')
                         ->with('success', "Requisition ID {$requisition->id} rejected successfully.");
    }
    
}
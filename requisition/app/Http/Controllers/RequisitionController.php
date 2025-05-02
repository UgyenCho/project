<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use App\Models\RequisitionItem; // Assuming you have an Item model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;    // Import DB facade for transactions
use Illuminate\Support\Facades\Log;    // Import Log facade for error logging
// use Illuminate\Support\Facades\Validator; // Keep if needed elsewhere

class RequisitionController extends Controller
{
    // --- Methods for displaying dashboards ---

    /**
     * Display dashboard for the logged-in user (LRC) showing THEIR requisitions.
     */
    public function Dashboard() {
        // Fetch requisitions created BY the currently logged-in user
        $requisitions = Requisition::where('user_id', Auth::id()) // Filter by logged-in user ID
                                   ->orderBy('created_at', 'desc') // Show latest first
                                   ->with('items') // Eager load items
                                   ->get();

        // Ensure view exists: resources/views/dashboard.blade.php
        return view('dashboard', compact('requisitions'));
     }

    /**
     * Display dashboard for the Head of Department (HOD), showing PENDING
     * requisitions submitted for THEIR department.
     */
    public function hodDashboard() {
        // Get the authenticated HOD user
        $hodUser = Auth::user();

        // Basic check if user is logged in and has a department_id
        if (!$hodUser || !$hodUser->department_id) {
            Log::warning('HOD Dashboard access attempt by user without valid department ID.', ['user_id' => $hodUser->id ?? null]);
            return view('hod.dashboard', ['requisitions' => collect()])->withErrors('User department information missing.');
        }

        // Get the HOD's department ID
        $hodDepartmentId = $hodUser->department_id;

        // --- THIS IS THE CORRECT QUERY ---
        $requisitions = Requisition::query() // Use query builder
            // Filter by the HOD's specific department ID
            ->where('department_id', $hodDepartmentId)
            // Filter for requisitions with the 'Pending' status (matching the store method)
            ->where('status', 'Pending')           // *** CORRECTED STATUS ***
            ->with('user', 'items')                // Eager load related data
            ->orderBy('created_at', 'asc')         // Order results
            ->get();
        // --- END CORRECT QUERY ---

        // Ensure view exists: resources/views/hod/dashboard.blade.php
        return view('hod.dashboard', compact('requisitions'));
     }


    /**
     * Store a newly created requisition in storage.
     */
    public function store(Request $request)
    {
        // 1. --- VALIDATION ---
        $validatedData = $request->validate([
            'requisition_date' => 'required|date|before_or_equal:today',
            'department_id' => 'required|integer|exists:departments,id', // Target department
            'requester_name' => 'required|string|max:255',
            'requester_designation' => 'required|integer',
            'item_name' => 'required|array|min:1',
            'item_name.*' => 'required|string|max:255',
            'item_description' => 'nullable|array',
            'item_description.*' => 'nullable|string',
            'item_quantity' => 'required|array|min:1',
            'item_quantity.*' => 'required|integer|min:1',
            'item_remarks' => 'nullable|array',
            'item_remarks.*' => 'nullable|string',
        ]);

        // 2. --- DATABASE TRANSACTION ---
        DB::beginTransaction();

        try {
            // 3. --- CREATE REQUISITION ---
            $requisition = Requisition::create([
                'user_id' => Auth::id(), // Logged-in LRC user
                'requisition_date' => $validatedData['requisition_date'],
                'department_id' => $validatedData['department_id'], // Target Dept ID from form
                'requester_name' => $validatedData['requester_name'],
                'requester_designation' => $validatedData['requester_designation'],
                'status' => 'Pending', // *** Correct initial status ***
            ]);

            // 4. --- CREATE REQUISITION ITEMS ---
            foreach ($validatedData['item_name'] as $index => $itemName) {
                RequisitionItem::create([
                    'requisition_id'    => $requisition->id,
                    'item_name'         => $itemName,
                    'item_description'  => $validatedData['item_description'][$index] ?? null,
                    'item_quantity'     => $validatedData['item_quantity'][$index],
                    'remarks'           => $validatedData['item_remarks'][$index] ?? null,
                ]);
            }

            // 5. --- COMMIT TRANSACTION ---
            DB::commit();
            // Redirect LRC user back to their dashboard
            return redirect()->route('dashboard')
                             ->with('success', 'Requisition submitted successfully!');

        } catch (\Exception $e) {
            // 7. --- ROLLBACK ON ERROR ---
            DB::rollBack();

            // 8. --- LOG THE ERROR ---
            Log::error('Error storing requisition for user ID ' . (Auth::id() ?? 'N/A') . ': ' . $e->getMessage(), ['exception' => $e]);

            // 9. --- REDIRECT BACK WITH ERROR MESSAGE & INPUT ---
            return redirect()->back()
                             ->with('error', 'Failed to submit requisition due to a server error. Please try again.')
                             ->withInput();
        }
    }
    

    /**
     * Display a specific requisition.
     */
    public function show(Requisition $requisition)
    {
        // Basic Authorization: Allow creator OR HOD of the target department to view.
        $user = Auth::user();
        if (!$user) {
             abort(403, 'Unauthorized action. Please log in.');
        }
        if ($requisition->user_id !== $user->id && $requisition->department_id !== $user->department_id) {
             // Add admin check here if needed: && !$user->isAdmin()
            abort(403, 'Unauthorized action.');
        }

        $requisition->load('items');
        // Ensure view exists: resources/views/user/requisitions/show.blade.php
        return view('user.requisitions.show', compact('requisition'));
    }

    // ... other methods like approve, reject ...

} // End of Controller
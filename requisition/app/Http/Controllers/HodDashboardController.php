<?php

namespace App\Http\Controllers;

use App\Models\Requisition; // Import Requisition model
use App\Models\RequisitionItem; // <-- Added for updateQuantities
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Keep Auth facade
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse; // For redirects
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\DB; // <-- Added for updateQuantities (Transactions)
use Illuminate\Validation\Rule; // <-- Added for updateQuantities (Validation)

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

        // ** ADAPT department logic: Ensure $hodUser->department relationship/property exists **
        // It's safer to check if the department exists before accessing its properties
        $hodDepartment = $hodUser->department;
        if (!$hodDepartment) {
            Log::error("HOD User ID {$hodUser->id} does not have a department assigned.");
             return redirect()->route('hod.dashboard')->with('error', 'Your department information is missing.');
        }

        // Check if the requisition's department matches the HOD's department.
        // Also check if the designation matches a specific ID (14 in this case, assumed LRC).
        // This logic allows HOD to see requests for their dept OR requests submitted by LRC. Adjust as needed.
        if ($requisition->department_id !== $hodDepartment->id && $requisition->requester_designation != 14) {
             return redirect()->route('hod.dashboard')
                              ->with('error', 'You are not authorized to view this specific requisition detail.');
        }
        // --- End Authorization Review ---

        $requisition->load('items'); // Eager load items relationship
        // Ensure this view exists or change path
        return view('hod.requisitions.show', compact('requisition'));
    }

    /**
     * Approve the specified requisition.
     * Uses Route Model Binding
     */
    public function approve(Requisition $requisition): RedirectResponse
    {
        // --- Authorization Checks ---
        // ... your checks ...

        // --- Status Check ---
        if (!in_array($requisition->status, ['Pending', 'Waiting for HOD approval'])) {
             Log::warning("Approval attempt on requisition ID: {$requisition->id} with invalid status: {$requisition->status}");
             return redirect()->route('hod.dashboard')->with('warning', 'This requisition is not currently pending HOD approval.');
        }

        try {
            Log::info("Attempting to set status to 'Waiting for Finance approval' for Req ID: {$requisition->id}");

            $requisition->status = 'Waiting for Finance approval';

            // Ensure these columns exist if you uncomment them!
            // $requisition->hod_approver_id = Auth::id();
            // $requisition->hod_approved_at = now();

            $requisition->save(); // The core action

            Log::info("Successfully saved status for Req ID: {$requisition->id}");

        } catch (\Illuminate\Database\QueryException $qe) { // Catch specific DB errors
            Log::error("Database Query Error updating status for Req ID {$requisition->id}: " . $qe->getMessage());
            // Provide the specific DB error back to the user
            return redirect()->route('hod.dashboard')
                             ->with('error', "Database error updating status. Please check logs. Error: " . $qe->getMessage());
        } catch (\Exception $e) { // Catch any other general errors
            Log::error("General Error updating status for Req ID {$requisition->id}: " . $e->getMessage());
            return redirect()->route('hod.dashboard')
                             ->with('error', "An unexpected error occurred: " . $e->getMessage());
        }

        Log::info("Requisition ID {$requisition->id} approved by HOD. Status set to 'Waiting for Finance approval'.");

        return redirect()->route('hod.dashboard')
                         ->with('success', "Requisition ID {$requisition->id} approved and forwarded for Finance review.");
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
        if (!$hodDepartment) {
            return redirect()->route('hod.dashboard')->with('error', 'Your department information is missing.');
        }

        if ($requisition->department_id !== $hodDepartment->id && $requisition->requester_designation != 14) {
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

        // TODO: Optionally: Send notification

        return redirect()->route('hod.dashboard')
                         ->with('success', "Requisition ID {$requisition->id} rejected successfully.");
    }

    // =========================================
    // START: Added Method to Update Quantities
    // =========================================

    /**
     * Update the quantities for items in a specific requisition.
     * Handles the submission from the show view's form.
     */
        /**
     * Update the quantities for items in a specific requisition.
     * Handles the submission from the show view's form.
     */
    public function updateQuantities(Request $request, Requisition $requisition): RedirectResponse
    {
        // --- Authorization Check ---
        $hodUser = Auth::user();
        $hodDepartment = $hodUser->department; // Assumes 'department' relationship exists
        if (!$hodDepartment) {
             Log::error("HOD User ID {$hodUser->id} lacks department info for Req ID: {$requisition->id} quantity update.");
             return redirect()->route('hod.dashboard')->with('error', 'Your department information is missing.');
         }

        // =====================================================
        // START: SOLUTION - Cast IDs to Integer for Comparison
        // =====================================================
        // Compare department IDs as integers to avoid type mismatch issues (e.g., 1 !== "1")
        if ((int)$requisition->department_id !== (int)$hodDepartment->id /* && $requisition->requester_designation != 14 */ ) {
        // =====================================================
        // END: SOLUTION
        // =====================================================
            Log::warning("Unauthorized quantity update attempt - Req ID Dept: {$requisition->department_id} (type: ".gettype($requisition->department_id)."), HOD Dept: {$hodDepartment->id} (type: ".gettype($hodDepartment->id)."), HOD ID: {$hodUser->id}"); // Added type logging for debugging
            return redirect()->route('hod.dashboard')->with('error', 'You are not authorized to modify this requisition.');
        }

        // --- Status Check ---
        // Verify the requisition is in a state that allows quantity editing
        if (!in_array($requisition->status, ['Pending', 'Pending HOD Approval'])) { // Adjust statuses if needed
            Log::info("Quantity update denied for Req ID: {$requisition->id} due to status: {$requisition->status}");
            return redirect()->route('hod.requisitions.show', $requisition->id)
                             ->with('warning', 'Quantities cannot be updated as the requisition is not pending.');
        }

        // --- Validation ---
        // (Validation code remains the same as provided previously)
        $validatedData = $request->validate([
            'quantities'   => 'required|array|min:1',
            'quantities.*' => ['required', 'integer', 'min:0'],
            'quantities'   => [function ($attribute, $value, $fail) use ($requisition) {
                $submittedItemIds = array_keys($value);
                $validItemCount = RequisitionItem::where('requisition_id', $requisition->id)
                                                 ->whereIn('id', $submittedItemIds)
                                                 ->count();
                if ($validItemCount !== count($submittedItemIds)) {
                    $fail('Invalid item data submitted. Please refresh and try again.');
                }
            }],
        ], [
            'quantities.*.min' => 'Item quantity cannot be negative.',
            'quantities.*.integer' => 'Item quantity must be a whole number.',
        ]);

        Log::info("Validated quantity update data for Req ID: {$requisition->id}", ['data' => $validatedData['quantities']]);

        // --- Database Update within a Transaction ---
        // (Database update code remains the same as provided previously)
        DB::beginTransaction();
        try {
            $itemsUpdated = 0;
            foreach ($validatedData['quantities'] as $itemId => $newQuantity) {
                $item = RequisitionItem::find($itemId);
                if ($item && $item->item_quantity != $newQuantity) {
                    $item->item_quantity = $newQuantity;
                    $item->save();
                    $itemsUpdated++;
                }
            }
            DB::commit();
            Log::info("Successfully updated quantities for Req ID: {$requisition->id}. Items changed: {$itemsUpdated}");
            return redirect()->route('hod.requisitions.show', $requisition->id)
                             ->with('success', 'Item quantities updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Database error updating quantities for Req ID: {$requisition->id}. Error: " . $e->getMessage());
            return redirect()->route('hod.requisitions.show', $requisition->id)
                             ->with('error', 'Failed to update quantities due to a server error.');
        }
        // --- End Database Update ---
    }
}
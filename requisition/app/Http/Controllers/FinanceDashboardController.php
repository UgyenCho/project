<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Illuminate\Http\Request; // Keep Request for reject method potentially
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class FinanceDashboardController extends Controller
{
    // index() method remains the same...
    public function index(): View
    {
        $statusForFinanceReview = 'Waiting for Finance approval';
        Log::info("Finance Dashboard: Fetching requisitions with status '{$statusForFinanceReview}'");
        $requisitions = Requisition::where('status', $statusForFinanceReview)
                                   ->with(['user:id,name', 'department:id,name'])
                                   ->latest()
                                   ->paginate(20);
        Log::info("Finance Dashboard: Found {$requisitions->total()} requisitions for review.");
        return view('finance.dashboard', compact('requisitions'));
    }

    // show() method remains the same...
    public function show(Requisition $requisition): View | RedirectResponse
    {
        $user = Auth::user();
        // Basic Authorization/Status Check Example (Adapt as needed)
        $allowedStatuses = [
            'Waiting for Finance approval', 'Rejected by Finance',
            'Waiting for President approval', 'Rejected by President',
            'Waiting for Store Manager action', 'Approved'
        ];
        if (!in_array($requisition->status, $allowedStatuses)) {
             return redirect()->route('finance.dashboard')->with('warning', 'This requisition is not at the correct stage for this view.');
        }
        $requisition->load(['items', 'user', 'department']);
        return view('finance.requisitions.show', compact('requisition'));
    }


    // ****** IMPLEMENTED APPROVE METHOD ******
    /**
     * Approve the requisition from Finance perspective.
     */
    public function approve(Requisition $requisition): RedirectResponse
    {
        $user = Auth::user();
        Log::info("Finance approval attempt for Req ID {$requisition->id} by User ID {$user->id}");

        // 1. Authorization (Example: Ensure user has 'finance' role)
        // if (!$user || !$user->hasRole('finance')) { // Requires hasRole() logic
        //     Log::warning("Unauthorized finance approval attempt for Req ID {$requisition->id} by User ID {$user->id}");
        //     return redirect()->route('finance.dashboard')->with('error', 'Unauthorized action.');
        // }

        // 2. Status Check
        if ($requisition->status !== 'Waiting for Finance approval') {
            Log::warning("Finance approval attempt on Req ID {$requisition->id} with invalid status: {$requisition->status}");
            return redirect()->route('finance.dashboard')->with('warning', 'This requisition is not currently waiting for finance approval.');
        }

        // 3. Perform Update
        try {
            // Set the next status in your workflow
            $requisition->status = 'Waiting for President approval'; // <<< ADJUST IF NEEDED

            // ** Optional: Add these columns to your requisitions table via migration **
            // $requisition->finance_approver_id = $user->id;
            // $requisition->finance_approved_at = now();

            $requisition->save();

            Log::info("Req ID {$requisition->id} approved by Finance User ID {$user->id}. Status changed to '{$requisition->status}'.");

            // TODO: Notify President? Notify Requester?

            return redirect()->route('finance.dashboard')->with('success', "Requisition ID {$requisition->id} approved successfully.");

        } catch (\Exception $e) {
            Log::error("Error during finance approval for Req ID {$requisition->id} by User ID {$user->id}: " . $e->getMessage());
            return redirect()->route('finance.dashboard')->with('error', 'An error occurred while approving the requisition.');
        }
    }
    // ****** END IMPLEMENTED APPROVE METHOD ******


    // ****** IMPLEMENTED REJECT METHOD ******
    /**
     * Reject the requisition from Finance perspective.
     */
    public function reject(Request $request, Requisition $requisition): RedirectResponse
    {
        // Note: Added Request $request if you want to add a rejection reason later

        $user = Auth::user();
        Log::info("Finance rejection attempt for Req ID {$requisition->id} by User ID {$user->id}");

        // 1. Authorization (Example: Ensure user has 'finance' role)
        // if (!$user || !$user->hasRole('finance')) { // Requires hasRole() logic
        //     Log::warning("Unauthorized finance rejection attempt for Req ID {$requisition->id} by User ID {$user->id}");
        //     return redirect()->route('finance.dashboard')->with('error', 'Unauthorized action.');
        // }

        // 2. Status Check
        if ($requisition->status !== 'Waiting for Finance approval') {
            Log::warning("Finance rejection attempt on Req ID {$requisition->id} with invalid status: {$requisition->status}");
            return redirect()->route('finance.dashboard')->with('warning', 'This requisition cannot be rejected at its current stage.');
        }

        // 3. Perform Update
        try {
            $requisition->status = 'Rejected by Finance'; // Set rejected status

            // ** Optional: Add these columns to your requisitions table via migration **
            // $requisition->finance_rejecter_id = $user->id;
            // $requisition->finance_rejected_at = now();
            // $requisition->rejection_reason = $request->input('rejection_reason'); // Requires a reason field in the form

            $requisition->save();

            Log::info("Req ID {$requisition->id} rejected by Finance User ID {$user->id}.");

            // TODO: Notify Requester/HOD?

            return redirect()->route('finance.dashboard')->with('success', "Requisition ID {$requisition->id} rejected successfully.");

        } catch (\Exception $e) {
            Log::error("Error during finance rejection for Req ID {$requisition->id} by User ID {$user->id}: " . $e->getMessage());
            return redirect()->route('finance.dashboard')->with('error', 'An error occurred while rejecting the requisition.');
        }
    }
    // ****** END IMPLEMENTED REJECT METHOD ******

} // End of class definition
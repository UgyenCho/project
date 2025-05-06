<?php

namespace App\Http\Controllers;

use App\Models\Requisition; // <-- Import the Requisition model
use Illuminate\Http\Request;
use Illuminate\View\View; // <-- Use View type hint
use Illuminate\Http\RedirectResponse; // <-- Use RedirectResponse type hint
use Illuminate\Support\Facades\Log; // <-- Use Log facade
use Illuminate\Support\Facades\Auth; // <-- Use Auth facade

class PresidentDashboardController extends Controller
{
    /**
     * Display the president's dashboard with requisitions awaiting their approval.
     */
    public function index(): View // <-- Type hint the return
    {
        // Define the status the President needs to review
        $statusForPresidentReview = 'Waiting for President approval';
        Log::info("President Dashboard: Fetching requisitions with status '{$statusForPresidentReview}'");

        // Query the database for requisitions with the correct status
        $requisitions = Requisition::where('status', $statusForPresidentReview)
                                   ->with(['user:id,name', 'department:id,name']) // Eager load relationships for efficiency
                                   ->latest() // Show the newest first
                                   ->paginate(20); // Use pagination

        Log::info("President Dashboard: Found {$requisitions->total()} requisitions for review.");

        // Pass the fetched requisitions to the view
        return view('president.dashboard', compact('requisitions')); // <-- Pass $requisitions
    }

    /**
     * Display the specified requisition details for the President.
     */
    public function show(Requisition $requisition): View | RedirectResponse
    {
         $user = Auth::user();
         // Define statuses the President should be able to view in detail
         $allowedStatuses = [
             'Waiting for President approval', 'Rejected by President',
             'Waiting for Store Manager action', 'Approved', // Maybe subsequent statuses too?
             // Add 'Rejected by Finance' if president should see why finance rejected? Maybe not.
         ];

         // Simple authorization: check if status allows viewing at this stage
         if (!in_array($requisition->status, $allowedStatuses)) {
             Log::warning("President attempt to view Req ID {$requisition->id} with invalid status: {$requisition->status}");
             return redirect()->route('president.dashboard')->with('warning', 'This requisition is not at the correct stage for detailed view by the President.');
         }

         $requisition->load(['items', 'user', 'department', /* other needed relations */]);
         // Ensure you have a 'resources/views/president/requisitions/show.blade.php' view file
         return view('president.requisitions.show', compact('requisition'));
    }

     /**
      * Approve the requisition from President perspective (Final Approval/Next Step).
      */
     public function approve(Requisition $requisition): RedirectResponse
     {
         $user = Auth::user();
         Log::info("President approval attempt for Req ID {$requisition->id} by User ID {$user->id}");

         // 1. Authorization (Optional: Check role - you should implement proper role middleware)
         // if (!$user || !$user->hasRole('president')) {
         //     Log::warning("Unauthorized president approval attempt for Req ID {$requisition->id} by User ID {$user->id}");
         //     return redirect()->route('president.dashboard')->with('error', 'Unauthorized action.');
         // }

         // 2. Status Check
         if ($requisition->status !== 'Waiting for President approval') {
             Log::warning("President approval attempt on Req ID {$requisition->id} with invalid status: {$requisition->status}");
             return redirect()->route('president.dashboard')->with('warning', 'This requisition is not currently waiting for president approval.');
         }

         // 3. Perform Update
         try {
             // Determine the *next* status based on your workflow
             // Example: If this is the final approval step before store manager action
              $requisition->status = 'Waiting for Store Manager action'; // <<< ADJUST THIS TO YOUR WORKFLOW
             // Example: If this is the absolute final approval
             // $requisition->status = 'Approved';

             // ** Optional: Track president approval details if needed **
             // $requisition->president_approver_id = $user->id;
             // $requisition->president_approved_at = now();

             $requisition->save();

             Log::info("Req ID {$requisition->id} approved by President User ID {$user->id}. Status changed to '{$requisition->status}'.");

             // TODO: Send Notifications (e.g., to Store Manager, Requester)

             return redirect()->route('president.dashboard')->with('success', "Requisition ID {$requisition->id} approved successfully.");

         } catch (\Exception $e) {
             Log::error("Error during president approval for Req ID {$requisition->id} by User ID {$user->id}: " . $e->getMessage());
             return redirect()->route('president.dashboard')->with('error', 'An error occurred while approving the requisition.');
         }
     }

     /**
      * Reject the requisition from President perspective.
      */
     public function reject(Request $request, Requisition $requisition): RedirectResponse
     {
         // Note: Added Request $request if you want to add a rejection reason later

         $user = Auth::user();
         Log::info("President rejection attempt for Req ID {$requisition->id} by User ID {$user->id}");

         // 1. Authorization (Optional: Check role)
         // if (!$user || !$user->hasRole('president')) { ... }

         // 2. Status Check
         if ($requisition->status !== 'Waiting for President approval') {
             Log::warning("President rejection attempt on Req ID {$requisition->id} with invalid status: {$requisition->status}");
             return redirect()->route('president.dashboard')->with('warning', 'This requisition cannot be rejected at its current stage by the President.');
         }

         // 3. Perform Update
         try {
             $requisition->status = 'Rejected by President'; // Set final rejected status

             // ** Optional: Track president rejection details **
             // $requisition->president_rejecter_id = $user->id;
             // $requisition->president_rejected_at = now();
             // $requisition->rejection_reason = $request->input('rejection_reason'); // If you add a reason field to the form

             $requisition->save();

             Log::info("Req ID {$requisition->id} rejected by President User ID {$user->id}.");

             // TODO: Send Notifications (e.g., to Requester, HOD, Finance)

             return redirect()->route('president.dashboard')->with('success', "Requisition ID {$requisition->id} rejected successfully.");

         } catch (\Exception $e) {
             Log::error("Error during president rejection for Req ID {$requisition->id} by User ID {$user->id}: " . $e->getMessage());
             return redirect()->route('president.dashboard')->with('error', 'An error occurred while rejecting the requisition.');
         }
     }
}
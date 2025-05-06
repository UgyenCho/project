<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Models\Requisition; // <-- Already imported
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    protected $allowedRoles = ['Admin', 'LRC', 'HOD', 'Finance', 'President'];
    protected $roleUserTypeMap = [ 'Admin'=>4, 'HOD'=>1, 'LRC'=>0, 'Finance'=>2, 'President'=>3 ];

    // Status constants for clarity
    const STATUS_PENDING_ADMIN = 'Approved by President';
    const STATUS_FINAL_APPROVED = 'Approved'; // Or 'Processed', 'Completed'
    const STATUS_FINAL_REJECTED = 'Rejected'; // Generic final rejected status

    /**
     * Display the admin dashboard.
     */
    public function showDashboard(): View
    {
        $users = User::orderBy('name')->get();
        $departments = Department::orderBy('name')->get(['id', 'name']);

        // --- Fetch Requisitions for Admin ---
        $statusForAdminReview = self::STATUS_PENDING_ADMIN; // Use constant
        Log::info("Admin Dashboard: Fetching requisitions with status '{$statusForAdminReview}'");

        $requisitions = Requisition::where('status', $statusForAdminReview)
                                    ->with(['user:id,name', 'department:id,name'])
                                    ->latest()
                                    ->paginate(15, ['*'], 'req_page'); // Use specific page name

        Log::info("Admin Dashboard: Found {$requisitions->total()} requisitions for final action.");

        return view('admin.dashboard', [
            'users' => $users,
            'departments' => $departments,
            'roles' => $this->allowedRoles,
            'requisitions' => $requisitions // Pass requisitions
        ]);
    }

    // =============================================
    // NEW METHODS FOR ADMIN REQUISITION ACTIONS
    // =============================================

    /**
     * Display the specified requisition details for the Admin.
     * (Requires a view: resources/views/admin/requisitions/show.blade.php)
     */
    public function showRequisition(Requisition $requisition): View | RedirectResponse
    {
         // Add any status checks if Admin should only see certain stages
         Log::info("Admin viewing details for Req ID {$requisition->id}");
         $requisition->load(['items', 'user', 'department']); // Load all needed data
         // Ensure the view path is correct
         return view('admin.requisitions.show', compact('requisition'));
    }

    /**
     * Finalize/Approve the requisition from Admin perspective.
     */
    public function finalizeRequisition(Requisition $requisition): RedirectResponse
    {
        $user = Auth::user(); // Get current Admin user
        Log::info("Admin final approval attempt for Req ID {$requisition->id} by Admin User ID {$user->id}");

        // 1. Status Check: Ensure it's pending admin action
        if ($requisition->status !== self::STATUS_PENDING_ADMIN) {
            Log::warning("Admin final approval attempt on Req ID {$requisition->id} with invalid status: {$requisition->status}");
            return redirect()->route('admin.dashboard')->with('warning', 'This requisition is not currently pending final action.');
        }

        // 2. Perform Update
        try {
            $requisition->status = self::STATUS_FINAL_APPROVED; // Set final approved status

            // Optional: Add final admin approver details if needed
            // $requisition->admin_approver_id = $user->id;
            // $requisition->admin_approved_at = now();

            $requisition->save();

            Log::info("Req ID {$requisition->id} finalized/approved by Admin User ID {$user->id}. Status changed to '{$requisition->status}'.");

            // TODO: Send final notifications (e.g., to Requester, Store?)

            return redirect(route('admin.dashboard') . '#view-requisitions-content')
                   ->with('success', "Requisition ID {$requisition->id} finalized successfully.");

        } catch (\Exception $e) {
            Log::error("Error during admin final approval for Req ID {$requisition->id} by Admin User ID {$user->id}: " . $e->getMessage());
             // Check fillable status again if errors occur
             if ($e instanceof \Illuminate\Database\Eloquent\MassAssignmentException) {
                 Log::error("Potential Mass Assignment issue. Ensure 'status' and admin fields are in \$fillable array.");
             }
            return redirect(route('admin.dashboard') . '#view-requisitions-content')
                   ->with('error', 'An error occurred while finalizing the requisition.');
        }
    }

     /**
      * Reject the requisition from Admin perspective.
      */
     public function rejectRequisition(Request $request, Requisition $requisition): RedirectResponse
     {
         // Use Request to potentially get a rejection reason
         $user = Auth::user();
         Log::info("Admin rejection attempt for Req ID {$requisition->id} by Admin User ID {$user->id}");

         // 1. Status Check
         if ($requisition->status !== self::STATUS_PENDING_ADMIN) {
             Log::warning("Admin rejection attempt on Req ID {$requisition->id} with invalid status: {$requisition->status}");
             return redirect()->route('admin.dashboard')->with('warning', 'This requisition cannot be rejected at its current stage by Admin.');
         }

         // Optional: Validate rejection reason if you add a field
         // $request->validate(['admin_rejection_reason' => 'required|string|max:500']);

         // 2. Perform Update
         try {
             $requisition->status = self::STATUS_FINAL_REJECTED; // Set final rejected status

             // Optional: Track admin rejection details
             // $requisition->admin_rejecter_id = $user->id;
             // $requisition->admin_rejected_at = now();
             // $requisition->admin_rejection_reason = $request->input('admin_rejection_reason'); // Ensure field is fillable

             $requisition->save();

             Log::info("Req ID {$requisition->id} rejected by Admin User ID {$user->id}.");

             // TODO: Send rejection notifications

             return redirect(route('admin.dashboard') . '#view-requisitions-content')
                    ->with('success', "Requisition ID {$requisition->id} rejected successfully.");

         } catch (\Exception $e) {
             Log::error("Error during admin rejection for Req ID {$requisition->id} by Admin User ID {$user->id}: " . $e->getMessage());
              // Check fillable status again if errors occur
              if ($e instanceof \Illuminate\Database\Eloquent\MassAssignmentException) {
                 Log::error("Potential Mass Assignment issue. Ensure 'status' and admin rejection fields are in \$fillable array.");
              }
             return redirect(route('admin.dashboard') . '#view-requisitions-content')
                    ->with('error', 'An error occurred while rejecting the requisition.');
         }
     }


    // =============================================
    // USER MANAGEMENT METHODS (Existing)
    // =============================================

    /**
     * Enable a specific user.
     */
    public function enableUser(User $user): RedirectResponse
    {
        $user->update(['is_active' => true]);
        return redirect(route('admin.dashboard') . '#user-management-content')->with('success', $user->name . ' enabled successfully.');
    }

    /**
     * Disable a specific user.
     */
    public function disableUser(User $user): RedirectResponse
    {
        if (Auth::id() === $user->id) {
             return redirect(route('admin.dashboard') . '#user-management-content')->with('error', 'You cannot disable your own account.');
        }
        $user->update(['is_active' => false]);
         return redirect(route('admin.dashboard') . '#user-management-content')->with('success', $user->name . ' disabled successfully.');
    }

    /**
     * Store a new user.
     */
    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'string', Rule::in($this->allowedRoles)],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
        ]);

        $userType = $this->roleUserTypeMap[$validated['role']] ?? null;
        if ($userType === null && array_key_exists($validated['role'], $this->roleUserTypeMap)) {
             Log::warning("User type mapping potentially missing for role: " . $validated['role']);
        }

        try {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'password' => Hash::make($validated['password']),
                'is_active' => true,
                'email_verified_at' => now(),
                'department_id' => $validated['department_id'] ?? null,
                'user_type' => $userType,
            ]);
            return redirect(route('admin.dashboard') . '#user-management-content')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
             Log::error("Error creating user: " . $e->getMessage());
            return redirect(route('admin.dashboard') . '#user-management-content')->with('error', 'Failed to create user. Check logs and model fillable attributes.');
        }
    }

    /**
     * Update an existing user.
     */
    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'role' => ['required', 'string', Rule::in($this->allowedRoles)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
        ]);

        $userType = $this->roleUserTypeMap[$validated['role']] ?? $user->user_type;
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'department_id' => $validated['department_id'] ?? null,
            'user_type' => $userType,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        try {
            $user->update($updateData);
            return redirect(route('admin.dashboard') . '#user-management-content')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
             Log::error("Error updating user {$user->id}: " . $e->getMessage());
             return redirect(route('admin.dashboard') . '#user-management-content')->with('error', 'Failed to update user. Check logs and model fillable attributes.');
        }
    }

    /**
     * Delete a specific user.
     */
    public function destroyUser(User $user): RedirectResponse
    {
        if (Auth::id() === $user->id) {
            return redirect(route('admin.dashboard') . '#user-management-content')
                   ->with('error', 'You cannot delete your own account.');
        }
        try {
            $userName = $user->name;
            $user->delete();
            return redirect(route('admin.dashboard') . '#user-management-content')
                   ->with('success', 'User "' . $userName . '" deleted successfully.');
        } catch (\Exception $e) {
             Log::error("Error deleting user {$user->id}: " . $e->getMessage());
             return redirect(route('admin.dashboard') . '#user-management-content')
                    ->with('error', 'Failed to delete user. Please try again.');
        }
    }

} // End of AdminDashboardController class
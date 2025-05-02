<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department; // Assuming you still use departments
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
    // Define Allowed Roles (Used for validation and mapping keys)
    protected $allowedRoles = ['Admin', 'LRC', 'HOD', 'Finance', 'President'];

    // Define Role to User Type Mapping
    protected $roleUserTypeMap = [
        'Admin'     => 4, // Ensure these integer values match your system's logic
        'HOD'       => 1,
        'LRC'       => 0,
        'Finance'   => 2,
        'President' => 3,
    ];

    /**
     * Display the admin dashboard.
     */
    public function showDashboard()
    {
        $users = User::orderBy('name')->get();
        // Fetch departments only if needed for the dropdowns in the view
        $departments = Department::orderBy('name')->get(['id', 'name']);

        return view('admin.dashboard', [
            'users' => $users,
            'departments' => $departments, // For department dropdown
            'roles' => $this->allowedRoles  // For role dropdown
        ]);
    }

    /**
     * Enable a specific user.
     */
    public function enableUser(User $user): RedirectResponse
    {
        // Ensure 'is_active' is fillable in User model
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
         // Ensure 'is_active' is fillable in User model
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
            'role' => ['required', 'string', Rule::in($this->allowedRoles)], // Validate role
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'], // Validate department
        ]);

        // Determine user_type based on validated role
        $userType = $this->roleUserTypeMap[$validated['role']] ?? null;

        if ($userType === null && array_key_exists($validated['role'], $this->roleUserTypeMap)) {
             Log::warning("User type mapping potentially missing for role: " . $validated['role']);
             // If user_type can be null in DB, this might proceed, otherwise it might fail below
        }

        try {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'], // Ensure 'role' is fillable
                'password' => Hash::make($validated['password']),
                'is_active' => true, // Ensure 'is_active' is fillable
                'email_verified_at' => now(),
                'department_id' => $validated['department_id'] ?? null, // Ensure 'department_id' is fillable
                'user_type' => $userType, // Ensure 'user_type' is fillable
            ]);

            return redirect(route('admin.dashboard') . '#user-management-content')->with('success', 'User created successfully.');

        } catch (\Exception $e) {
             Log::error("Error creating user: " . $e->getMessage());
             // Provide a more specific error if possible (e.g., check fillable attributes)
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
            'role' => ['required', 'string', Rule::in($this->allowedRoles)], // Validate role
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'], // Validate department
        ]);

        // Determine user_type based on validated role
        $userType = $this->roleUserTypeMap[$validated['role']] ?? $user->user_type; // Keep existing if map fails

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'], // Ensure 'role' is fillable
            'department_id' => $validated['department_id'] ?? null, // Ensure 'department_id' is fillable
            'user_type' => $userType, // Ensure 'user_type' is fillable
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        try {
            $user->update($updateData);
            return redirect(route('admin.dashboard') . '#user-management-content')->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
             Log::error("Error updating user {$user->id}: " . $e->getMessage());
              // Provide a more specific error if possible (e.g., check fillable attributes)
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
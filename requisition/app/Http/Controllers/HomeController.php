<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Correct facade path
use App\Models\User;
// Remove Illuminate\View\View as we are only redirecting now
use Illuminate\Http\RedirectResponse; // For return type hint

class HomeController extends Controller
{
    /**
     * Redirect the user to the appropriate dashboard route based on user type,
     * or redirect to login if not authenticated.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse // Now always returns a RedirectResponse
     */
    public function redirect(Request $request): RedirectResponse // Updated return type
    {
        // Check if user is authenticated using the default guard
        if (Auth::check()) {
            // Get the currently authenticated user
            $user = Auth::user();

            // Check if the user object and user_type property exist
            if ($user && isset($user->user_type)) {

                // Get the user_type for clarity
                $userType = $user->user_type;

                // Redirect based on user_type
                switch ($userType) {
                    case 0: // User type 0 (e.g., LRC/Standard User)
                        return redirect()->route('dashboard'); // Redirect to user dashboard route

                    case 1: // User type 1 (e.g., HOD)
                        return redirect()->route('hod.dashboard'); // Redirect to HOD dashboard route

                    case 2: // User type 2 (Finance)
                        return redirect()->route('finance.dashboard'); // Redirect to Finance dashboard route

                    case 3: 
                        return redirect()->route('president.dashboard');
                    case 4: 
                        return redirect()->route('admin.dashboard'); 
                    default:
                        // Handle unknown user types - Log out for safety
                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                        return redirect()->route('login')->with('error', 'Invalid user role assigned. Please contact support.');
                }

            } else {
                // User is authenticated but missing user_type or user object is invalid
                Auth::logout(); // Log them out for safety
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('error', 'User account configuration issue. Please log in again.');
            }

        } else {
            // User is not authenticated, redirect to login page
            return redirect()->route('login'); // Redirect to the named login route
        }
    }
}
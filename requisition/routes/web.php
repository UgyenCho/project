<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequisitionController; // Likely for user actions
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HodDashboardController;
use App\Http\Controllers\FinanceDashboardController;
use App\Http\Controllers\PresidentDashboardController;
use App\Http\Controllers\AdminDashboardController;
// Removed UserRequisitionController assuming RequisitionController handles user actions
use App\Http\Controllers\DashboardController; // Controller for the main user dashboard
// use App\Http\Controllers\AboutPageController; // Uncomment if you create this

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// --- Public Routes ---
Route::get('/', function () {
    return view('welcome');
});

// Example public route (uncomment controller import if used)
Route::get('/aboutus', [AboutPageController::class, 'index'])->name('about.page'); // Corrected if used


// --- Authenticated Routes ---
// All routes requiring login and email verification (if enabled) go here
Route::middleware([
    'auth:sanctum', // Use sanctum guard for web
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // --- Role-Based Redirect Logic ---
    // Redirects user to appropriate dashboard after login based on role
    Route::get('/home', [HomeController::class,'redirect'])->name('home');

    // --- Standard User (LRC) Dashboard & Actions ---
    // Main dashboard shown after login (might be overridden by /home redirect)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User (LRC) actions for creating/viewing their own requisitions
    // Note: Name prefix 'user.' is applied implicitly if needed later, keeping it simple for now
    Route::post('/requisitions', [RequisitionController::class, 'store'])->name('user.requisitions.store'); // User submits a new one
    Route::get('/requisitions/{requisition}', [RequisitionController::class, 'show'])->name('user.requisitions.show'); // User views their own submitted one


    // --- HOD Routes ---
    Route::middleware(['auth', /* 'role:hod' */]) // Add HOD role middleware here when ready!
        ->prefix('hod')
        ->name('hod.')
        ->group(function () {

        Route::get('/dashboard', [HodDashboardController::class, 'index'])->name('dashboard');

        // HOD actions on requisitions
        Route::get('/requisitions/{requisition}', [HodDashboardController::class, 'show'])->name('requisitions.show'); // DEFINES the missing route
        Route::patch('/requisitions/{requisition}/approve', [HodDashboardController::class, 'approve'])->name('requisitions.approve');
        Route::patch('/requisitions/{requisition}/reject', [HodDashboardController::class, 'reject'])->name('requisitions.reject');

        // START: Added Route for Updating Quantities
        Route::patch('/requisitions/{requisition}/update-quantities', [HodDashboardController::class, 'updateQuantities'])
             ->name('requisitions.updateQuantities');
        // END: Added Route for Updating Quantities

    });


    // --- President Routes ---
    Route::middleware(['auth', /* 'role:president' */]) // Add President role middleware here!
        ->prefix('president')
        ->name('president.')
        ->group(function() {
            Route::get('/dashboard', [PresidentDashboardController::class, 'index'])->name('dashboard');
            // Add other president-specific routes (e.g., viewing reports, final approvals)
    });


    // --- Finance Routes ---
    // Inside routes/web.php

// ... other use statements ...


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // ... other route groups (home, user, hod, president, admin) ...

    // --- Finance Routes ---
    Route::middleware(['auth', /* 'role:finance' */]) // Add Finance role middleware here!
        ->prefix('finance')
        ->name('finance.')
        ->group(function() {
            Route::get('/dashboard', [FinanceDashboardController::class, 'index'])->name('dashboard');
            Route::get('/requisitions/{requisition}', [FinanceDashboardController::class, 'show'])->name('requisitions.show'); // View details

            // ****** ADD THESE TWO LINES ******
            Route::patch('/requisitions/{requisition}/approve', [FinanceDashboardController::class, 'approve'])->name('requisitions.approve');
            Route::patch('/requisitions/{requisition}/reject', [FinanceDashboardController::class, 'reject'])->name('requisitions.reject');
            // *********************************

             // Add other finance-specific routes (e.g., budget checks)
    });

    // ... (admin routes)

}); // End Authenticated Group
        // ***************************

        // Add routes for Finance approval/rejection later
        // Route::patch('/requisitions/{requisition}/approve', [FinanceDashboardController::class, 'approve'])->name('requisitions.approve');
        // Route::patch('/requisitions/{requisition}/reject', [FinanceDashboardController::class, 'reject'])->name('requisitions.reject');

         // Add other finance-specific routes (e.g., budget checks)
});

    // --- Admin Routes ---
    Route::middleware(['auth', /* 'role:admin' */]) // Add Admin role middleware here!
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'showDashboard'])->name('dashboard');

        // User Management Routes (relative names: users.e nable, users.disable etc.)
        Route::patch('/users/{user}/enable', [AdminDashboardController::class, 'enableUser'])->name('users.enable');
        Route::patch('/users/{user}/disable', [AdminDashboardController::class, 'disableUser'])->name('users.disable');
        Route::post('/users', [AdminDashboardController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{user}', [AdminDashboardController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminDashboardController::class, 'destroyUser'])->name('users.destroy');

        // Add other admin routes...
    });


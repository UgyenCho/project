<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request; // Use standard Request or create a FormRequest
// use App\Models\Requisition;
// use App\Models\RequisitionItem;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB; // Use Database Transactions
// use Illuminate\Support\Facades\Log; // Use Logging
// use Illuminate\Support\Facades\Redirect; // Use Redirect facade

// // TODO: Later - Import Notification classes
// // use App\Models\User;
// // use App\Notifications\NewRequisitionSubmitted;
// // use Illuminate\Support\Facades\Notification;


// class UserRequisitionController extends Controller // Or your relevant controller
// {
//     /**
//      * Store a newly created requisition in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @return \Illuminate\Http\RedirectResponse
//      */
//     public function store(Request $request) // Consider using a FormRequest for cleaner validation
//     {
//         // 1. Validation
//         $validatedData = $request->validate([
//             'requisition_date' => 'required|date|before_or_equal:today',
//             'department' => 'required|string|max:255', // Ensure this department exists if needed
//             'requester_name' => 'required|string|max:255',
//             'requester_designation' => 'required|integer|min:1', // Validate it's a valid integer key
//             'item_name' => 'required|array|min:1', // At least one item row required
//             'item_name.*' => 'required|string|max:255', // Each item name is required
//             'item_description' => 'nullable|array',
//             'item_description.*' => 'nullable|string|max:65535', // Optional, max length for TEXT type
//             'item_quantity' => 'required|array|min:1', // Quantity array required
//             'item_quantity.*' => 'required|integer|min:1', // Each quantity must be a positive integer
//             'item_remarks' => 'nullable|array',
//             'item_remarks.*' => 'nullable|string|max:65535', // Optional remarks
//         ]);

//         // Start Database Transaction
//         DB::beginTransaction();

//         try {
//             // 2. Create the Main Requisition Record
//             $requisition = Requisition::create([
//                 'user_id' => Auth::id(), // Link to the logged-in LRC user
//                 'requisition_date' => $validatedData['requisition_date'],
//                 'department_id' => $validatedData['department_id'],
//                 'requester_name' => $validatedData['requester_name'],
//                 'requester_designation' => $validatedData['requester_designation'], // Save the integer ID
//                 'status' => 'Waiting for HOD approval', // Set initial status (matches ENUM value)
//                 // Add 'remarks' => $validatedData['main_remarks'] if you have a main remarks field
//             ]);

//             // 3. Create the Requisition Item Records
//             $itemCount = count($validatedData['item_name']);
//             for ($i = 0; $i < $itemCount; $i++) {
//                 RequisitionItem::create([
//                     'requisition_id' => $requisition->id, // Link item to the created requisition
//                     'item_name' => $validatedData['item_name'][$i],
//                     'item_description' => $validatedData['item_description'][$i] ?? null, // Use null if not provided
//                     'quantity' => $validatedData['item_quantity'][$i],
//                     'item_remarks' => $validatedData['item_remarks'][$i] ?? null, // Use null if not provided
//                 ]);
//             }

//             // If everything went well, commit the transaction
//             DB::commit();

//             // --- 4. Notification (Future Step) ---
//             // Find the HOD for the specific department
//             // Example (Requires logic to find HOD based on department):
//             // $hodUsers = User::where('role', 'hod')->where('department', $requisition->department)->get();
//             // if ($hodUsers->isNotEmpty()) {
//             //     Notification::send($hodUsers, new NewRequisitionSubmitted($requisition));
//             // } else {
//             //     Log::warning("No HOD found for department: " . $requisition->department . " for Requisition ID: " . $requisition->id);
//             // }
//             // ------------------------------------

//             // 5. Redirect back to dashboard with success message
//             return Redirect::route('dashboard') // Assumes your dashboard route is named 'dashboard'
//                      ->with('success', 'Requisition submitted successfully and pending HOD approval.');


//         } catch (\Exception $e) {
//             // Something went wrong, rollback the transaction
//             DB::rollBack();

//             // Log the detailed error for debugging
//             Log::error('Requisition Submission Failed: ' . $e->getMessage(), [
//                 'user_id' => Auth::id(),
//                 'request_data' => $request->except(['_token', 'password', 'password_confirmation']), // Avoid logging sensitive data
//                 'exception' => $e // Log the full exception trace
//             ]);

//             // Redirect back to the form with an error message and the old input
//             return Redirect::back()
//                      ->withInput() // Keep the user's input in the form
//                      ->with('error', 'Failed to submit requisition due to a server error. Please check input or try again.');
//         }
//     }

//     // Optional: Method to show a single requisition's details (for the "View" button)
//     public function show(Requisition $requisition) // Route model binding
//     {
//         // Ensure the logged-in user owns this requisition or has permission to view it
//         if ($requisition->user_id !== Auth::id() /* && !Auth::user()->can('view_any_requisition') */) {
//              abort(403, 'Unauthorized action.');
//         }

//         // Eager load items to avoid N+1 queries in the view
//         $requisition->load('items', 'user');

//         // Return a view to display the details (e.g., 'requisitions.show')
//         return view('requisitions.show', compact('requisition')); // You'll need to create this view
//     }

// }
{{-- resources/views/user/requisitions/show.blade.php --}}

<x-app-layout>
    {{-- Slot for page header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Requisition Details') }} - ID: {{ $requisition->id }}
        </h2>
    </x-slot>

    {{-- Main content area --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- START: Form Wrapper for submitting quantity changes --}}
            {{-- Ensure the route 'hod.requisitions.updateQuantities' is defined in routes/web.php --}}
            <form action="{{ route('hod.requisitions.updateQuantities', $requisition->id) }}" method="POST">
                @csrf {{-- CSRF protection token --}}
                @method('PATCH') {{-- Use PATCH or PUT method for updates --}}
            {{-- END: Form Wrapper --}}

                {{-- White background card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 space-y-6">

                        {{-- Back Button to HOD Dashboard --}}
                        <div class="mb-4">
                            <a href="{{ route('hod.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{-- Back arrow icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                                </svg>
                                Back to HOD Dashboard
                            </a>
                        </div>

                        {{-- Requisition Header Information Section --}}
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">
                            Requisition Summary
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 text-sm">
                            <div><strong>Requisition Date:</strong> {{ optional($requisition->requisition_date)->format('Y-m-d') ?? 'N/A' }}</div>
                            <div><strong>Department:</strong> {{ $requisition->department->name ?? 'N/A' }}</div>
                            <div>
                                <strong>Status:</strong>
                                {{-- Status Badge --}}
                               <span class="badge {{ $requisition->status_badge_class ?? 'bg-secondary' }}">
                                    {{ $requisition->status ?? 'N/A' }}
                               </span>
                            </div>
                            <div><strong>Requested By:</strong> {{ $requisition->requester_name ?? ($requisition->user->name ?? 'N/A') }}</div>
                            <div><strong>Designation:</strong> {{ $requisition->designation_text ?? ($requisition->requester_designation ?? 'N/A') }}</div>
                            <div><strong>Submitted At:</strong> {{ optional($requisition->created_at)->format('Y-m-d H:i') ?? 'N/A' }}</div>
                        </div>

                         {{-- Optional: Approval/Issuance Info (if applicable in your system) --}}
                        {{-- ... (optional section remains the same) ... --}}


                        {{-- Requested Items Table Section --}}
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">
                            Requested Items
                        </h3>
                        {{-- Check if items exist --}}
                        @if($requisition->items && $requisition->items->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border">
                                    {{-- Table Header --}}
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Sl.</th>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Item Name</th>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Description/Specs</th>
                                            <th scope="col" class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Quantity (Editable)</th>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks/Purpose</th>
                                        </tr>
                                    </thead>
                                    {{-- Table Body --}}
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        {{-- Loop through each item --}}
                                        @foreach ($requisition->items as $index => $item)
                                            <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }}"> {{-- Alternating row colors --}}
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 border-r text-center">{{ $index + 1 }}</td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 border-r">{{ $item->item_name ?? 'N/A' }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-600 border-r">{{ $item->item_description ?? 'N/A' }}</td>
                                                {{-- Quantity Input Cell --}}
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 border-r text-center">
                                                    {{-- Editable number input for quantity --}}
                                                    <input type="number"
                                                           {{-- Submits as an array: quantities[item_id] = value --}}
                                                           name="quantities[{{ $item->id }}]"
                                                           {{-- Value: old input on validation error, otherwise current db value --}}
                                                           value="{{ old('quantities.'.$item->id, $item->item_quantity ?? 0) }}"
                                                           min="0" {{-- Prevent negative quantities --}}
                                                           step="1" {{-- Allow whole numbers --}}
                                                           {{-- Basic Tailwind styling for the input --}}
                                                           class="w-20 text-center border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                                           {{-- Make input readonly if status is not Pending --}}
                                                           @if(!in_array($requisition->status, ['Pending', 'Pending HOD Approval'])) readonly @endif
                                                           >
                                                </td>
                                                <td class="px-4 py-2 text-sm text-gray-600">{{ $item->item_remarks ?? '--' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            {{-- Message if no items are found --}}
                            <p class="text-gray-500">No items found for this requisition.</p>
                        @endif

                        {{-- ============================================= --}}
                        {{-- START: Save and Cancel Buttons Section       = --}}
                        {{-- ============================================= --}}
                        {{-- Conditionally display buttons if status allows editing --}}
                        @if(in_array($requisition->status, ['Pending', 'Pending HOD Approval']))
                            <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end space-x-3">
                                {{-- Cancel Button (Red) - Links back to dashboard --}}
                                <a href="{{ route('hod.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Cancel
                                </a>
                                {{-- Save Button (Changed to Blue) - Submits the form --}}
                                {{-- Save Button (Blue) - Verify these classes exist in compiled CSS --}}
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Save Quantity Changes
                                </button>
                            </div>
                        @endif
                        {{-- =========================================== --}}
                        {{-- END: Save and Cancel Buttons Section       = --}}
                        {{-- =========================================== --}}

                    </div> {{-- End p-6 content padding --}}
                </div> {{-- End bg-white card --}}

            </form> {{-- End Form Wrapper --}}
        </div> {{-- End max-w-7xl container --}}
    </div> {{-- End py-12 main padding --}}

    {{-- Push custom styles for badges and buttons if not globally available --}}
    @push('styles')
    <style>
        /* Badge Styles */
        .badge { display: inline-block; padding: .35em .65em; font-size: .75em; font-weight: 700; line-height: 1; color: #fff; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; }
        .bg-warning { background-color: #ffc107 !important; color: #212529 !important;}
        .bg-success { background-color: #198754 !important; }
        .bg-danger { background-color: #dc3545 !important; }
        .bg-info { background-color: #0dcaf0 !important; color: #000 !important; }
        .bg-secondary { background-color: #6c757d !important; }
        .bg-primary { background-color: #0d6efd !important; }

        /* Added button styles (Tailwind classes used in HTML) */
        /* --- Blue Button Styles (New for Save) --- */
        .bg-blue-600 { background-color: #2563eb; }
        .hover\:bg-blue-700:hover { background-color: #1d4ed8; }
        .active\:bg-blue-900:active { background-color: #1e3a8a; }
        .focus\:border-blue-900:focus { border-color: #1e3a8a; }
        .focus\:ring.ring-blue-300:focus { --tw-ring-color: #93c5fd; box-shadow: var(--tw-ring-inset) 0 0 0 calc(3px + var(--tw-ring-offset-width)) var(--tw-ring-color); }

        /* --- Red Button Styles (For Cancel) --- */
        .bg-red-600 { background-color: #dc2626; }
        .hover\:bg-red-700:hover { background-color: #b91c1c; }
        .active\:bg-red-900:active { background-color: #7f1d1d; }
        .focus\:border-red-900:focus { border-color: #7f1d1d; }
        .focus\:ring.ring-red-300:focus { --tw-ring-color: #fca5a5; box-shadow: var(--tw-ring-inset) 0 0 0 calc(3px + var(--tw-ring-offset-width)) var(--tw-ring-color); }

        /* --- Gray Button Styles (Used for Back Button) --- */
        .bg-gray-500 { background-color: #6b7280; }
        .hover\:bg-gray-700:hover { background-color: #374151; }
        .active\:bg-gray-900:active { background-color: #111827; }
        .focus\:border-gray-900:focus { border-color: #111827; }
        .focus\:ring.ring-gray-300:focus { --tw-ring-color: #d1d5db; box-shadow: var(--tw-ring-inset) 0 0 0 calc(3px + var(--tw-ring-offset-width)) var(--tw-ring-color); }
    </style>
    @endpush

</x-app-layout>
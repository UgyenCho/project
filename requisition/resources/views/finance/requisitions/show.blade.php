{{-- resources/views/user/requisitions/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Requisition Details') }} - ID: {{ $requisition->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 space-y-6">

                    {{-- Back Button --}}
                    <div class="mb-4">
                        <a href="{{ route('finance.dashboard') }}#form-status" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                            Back to Dashboard
                        </a>
                    </div>

                    {{-- Requisition Header Info --}}
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">
                        Requisition Summary
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 text-sm">
                        <div><strong>Requisition Date:</strong> {{ optional($requisition->requisition_date)->format('Y-m-d') ?? 'N/A' }}</div>
                        <div><strong>Department:</strong> {{ $requisition->department->name ?? 'N/A' }}</div>
                        <div><strong>Status:</strong>
                           <span class="badge {{ $requisition->status_badge_class ?? 'bg-secondary' }}">
                                {{ $requisition->status ?? 'N/A' }}
                           </span>
                        </div>
                        <div><strong>Requested By:</strong> {{ $requisition->requester_name ?? 'N/A' }}</div>
                        <div><strong>Designation:</strong> {{ $requisition->designation_text ?? ($requisition->requester_designation ?? 'N/A') }}</div>
                        <div><strong>Submitted At:</strong> {{ optional($requisition->created_at)->format('Y-m-d H:i') ?? 'N/A' }}</div>
                    </div>

                     {{-- Approval/Issuance Info (Optional - Add if applicable) --}}
                    {{-- ... (optional section remains the same) ... --}}


                    {{-- Items Table --}}
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">
                        Requested Items
                    </h3>
                    @if($requisition->items && $requisition->items->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Sl.</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Item Name</th>
                                        {{-- Name Corrected Below --}}
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Description/Specs</th>
                                        {{-- Name Corrected Below --}}
                                        <th scope="col" class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Quantity</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks/Purpose</th>
                                        {{-- Optional: Add Issued Qty if needed --}}
                                        {{-- <th scope="col" class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Issued Qty</th> --}}
                                    </tr>
                                </thead>
                                {{-- ****** MODIFIED SECTION START ****** --}}
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($requisition->items as $index => $item)
                                        <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }}">
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 border-r text-center">{{ $index + 1 }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 border-r">{{ $item->item_name ?? 'N/A' }}</td>
                                            {{-- Use item_description (Matches DB column) --}}
                                            <td class="px-4 py-2 text-sm text-gray-600 border-r">{{ $item->item_description ?? 'N/A' }}</td>
                                            {{-- Use item_quantity (Matches DB column) --}}
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 border-r text-center">{{ $item->item_quantity ?? 'N/A' }}</td>
                                            {{-- Remarks name is correct, data is NULL in DB --}}
                                            <td class="px-4 py-2 text-sm text-gray-600">{{ $item->item_remarks ?? '--' }}</td>
                                             {{-- Optional: Add Issued Qty if needed --}}
                                            {{-- <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 text-center">{{ $item->issued_quantity ?? 'N/A' }}</td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                                {{-- ****** MODIFIED SECTION END ****** --}}
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No items found for this requisition.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- Include badge styles if not globally available --}}
    @push('styles')
    <style>
        .badge { display: inline-block; padding: .35em .65em; font-size: .75em; font-weight: 700; line-height: 1; color: #fff; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; }
        .bg-warning { background-color: #ffc107 !important; color: #212529 !important;}
        .bg-success { background-color: #198754 !important; }
        .bg-danger { background-color: #dc3545 !important; }
        .bg-info { background-color: #0dcaf0 !important; color: #000 !important; }
        .bg-secondary { background-color: #6c757d !important; }
        .bg-primary { background-color: #0d6efd !important; }
    </style>
    @endpush

</x-app-layout>
{{-- resources/views/president/dashboard.blade.php --}}

<x-app-layout>
    {{-- Optional: Add a header slot if your layout supports it --}}
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('President Dashboard') }}
        </h2>
    </x-slot> --}}

    {{-- Include the same CSS --}}
    <style>
        /* === Dashboard Container === */
        .dashboard-container { display: flex; width: 100%; max-width: 100%; min-height: calc(100vh - 0rem); /* Adjust as needed */ }
        /* === Sidebar === */
        .sidebar { width: 250px; background-color: rgb(20, 133, 133); /* Keeping Finance color for consistency as requested, or change e.g., #004080 for Presidential Blue */ color: #fff; padding: 20px 15px; display: flex; flex-direction: column; flex-shrink: 0; overflow-y: auto; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; color: #eee; font-size: 1.5em; font-weight: bold; padding-bottom: 15px; border-bottom: 1px solid #34495e; }
        .sidebar ul { list-style: none; flex-grow: 1; padding-left: 0; margin-bottom: 1rem; }
        .sidebar ul li { margin-bottom: 8px; }
        .sidebar ul li a.nav-link { color: #ccc; text-decoration: none; display: flex; align-items: center; padding: 12px 15px; border-radius: 5px; transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out; font-size: 0.95rem; }
        .sidebar ul li a i.fa-fw { margin-right: 12px; width: 20px; text-align: center; font-size: 1.1em; }
        .sidebar ul li a.nav-link:hover { background-color: #34495e; color: #fff; }
        .sidebar ul li a.nav-link.active { background-color: #1abc9c; /* Example Active Color */ color: #fff; font-weight: 600; }
        /* === Content Area === */
        .content-area { flex-grow: 1; padding: 30px; background-color: #f8f9fa; overflow-y: auto; }
        .content-section { display: none; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .content-section.active { display: block; }
        .content-section h2 { font-size: 1.8em; color: #333; margin-bottom: 25px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        /* Alerts & Tables (reused styles) */
        .alert { padding: 15px; border: 1px solid transparent; border-radius: 4px; margin-bottom: 20px; }
        .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .alert-info { background-color: #d1ecf1; color: #0c5460; border-color: #bee5eb; }
        .alert-warning { background-color: #fff3cd; color: #856404; border-color: #ffeeba; }
        .table-responsive { overflow-x: auto; margin-bottom: 10px;}
        .table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; background-color: transparent; }
        .table th, .table td { padding: .75rem; vertical-align: middle; border-top: 1px solid #dee2e6; }
        .table thead th { vertical-align: bottom; border-bottom: 2px solid #dee2e6; background-color: #e9ecef; text-align: left; font-weight: bold; }
        .table tbody + tbody { border-top: 2px solid #dee2e6; }
        .table-striped tbody tr:nth-of-type(odd) { background-color: rgba(0,0,0,.05); }
        .table-hover tbody tr:hover { color: #212529; background-color: rgba(0,0,0,.075); }
        .badge { display: inline-block; padding: .35em .65em; font-size: .75em; font-weight: 700; line-height: 1; color: #fff; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; }
        .bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
        .bg-success { background-color: #198754 !important; }
        .bg-danger { background-color: #dc3545 !important; }
        .bg-info { background-color: #0dcaf0 !important; color: #000 !important;}
        .bg-secondary { background-color: #6c757d !important; }
        .bg-primary { background-color: #0d6efd !important; }
        .btn { display: inline-block; font-weight: 400; color: #212529; text-align: center; vertical-align: middle; cursor: pointer; user-select: none; background-color: transparent; border: 1px solid transparent; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out; }
        .btn-sm { padding: .25rem .5rem; font-size: .875rem; line-height: 1.5; border-radius: .2rem; }
        .btn-info { color: #fff; background-color: #17a2b8; border-color: #17a2b8; }
        .btn-success { color: #fff; background-color: #28a745; border-color: #28a745; }
         .btn-success:hover { color: #fff; background-color: #218838; border-color: #1e7e34; }
        .btn-danger { color: #fff; background-color: #dc3545; border-color: #dc3545; }
        .btn-danger:hover { color: #fff; background-color: #c82333; border-color: #bd2130; }
        .btn i.fas { margin-right: .3em; }
        /* Responsive adjustments */
         @media (max-width: 767.98px) { .dashboard-container { flex-direction: column; } .sidebar { width: 100%; height: auto; position: relative; border-bottom: 1px solid #444; min-height: auto; } .sidebar ul { display: flex; justify-content: space-around; flex-wrap: wrap; flex-grow: 0; } .content-area { min-height: auto; } }
         /* Ensure buttons align well */
         .table-actions-cell .btn, .table-actions-cell form {
             margin-right: 5px;
             margin-bottom: 5px; /* Spacing for wrapping on small screens */
             vertical-align: middle; /* Align buttons nicely */
             display: inline-block; /* Make forms behave like buttons */
         }
        /* Optional: Styles for notification list */
        .list-group { padding-left: 0; margin-bottom: 0; border-radius: .25rem; }
        .list-group-item { position: relative; display: block; padding: .75rem 1.25rem; background-color: #fff; border: 1px solid rgba(0,0,0,.125); }
        .list-group-item:first-child { border-top-left-radius: inherit; border-top-right-radius: inherit; }
        .list-group-item:last-child { border-bottom-right-radius: inherit; border-bottom-left-radius: inherit; margin-bottom: 0; }
        .list-group-item + .list-group-item { border-top-width: 0; }
        .list-group-item-action { width: 100%; color: #495057; text-align: inherit; }
        .list-group-item-action:hover, .list-group-item-action:focus { z-index: 1; color: #495057; text-decoration: none; background-color: #f8f9fa; }
        .list-group-item-warning { color: #856404; background-color: #fff3cd; border-color: #ffeeba; }
        .list-group-item-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .list-group-item-info { color: #0c5460; background-color: #d1ecf1; border-color: #bee5eb; }
        .list-group-item-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }

    </style>

    <div class="dashboard-container">
        {{-- President Sidebar --}}
        <nav class="sidebar">
            <h2>President Dashboard</h2>
            <ul>
                <li><a href="#" data-target="review-requisitions" class="nav-link active"><i class="fas fa-fw fa-clipboard-check"></i> Review Requisitions</a></li>
                {{-- *** MODIFIED LINK *** --}}
                <li><a href="#" data-target="notifications-content" class="nav-link"><i class="fas fa-fw fa-bell"></i> Notifications</a></li>
                {{-- Add more President links as needed --}}
            </ul>
        </nav>

        {{-- Main Content Area --}}
        <main class="content-area">

            {{-- Session Feedback --}}
            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops! Errors occurred:</strong>
                    <ul style="margin-top: 10px; padding-left: 20px;"> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
                </div>
            @endif

            {{-- Section 1: Requisitions for Review --}}
            <div id="review-requisitions" class="content-section active">
                <h2>Requisitions Pending Presidential Approval</h2>

                @isset($requisitions)
                    @if($requisitions->isEmpty())
                        <div class="alert alert-info" role="alert">
                            There are no requisitions currently awaiting presidential approval.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Req. ID</th>
                                        <th>Req. Date</th>
                                        <th>Department</th>
                                        <th>Requester</th>
                                        <th>Status</th>
                                        <th>Submitted At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requisitions as $requisition)
                                        <tr>
                                             <td>{{ $requisition->id }}</td>
                                            <td>{{ optional($requisition->requisition_date)->format('Y-m-d') ?? 'N/A' }}</td>
                                            <td>{{ $requisition->department->name ?? ($requisition->department_id ?? 'N/A') }}</td>
                                            <td>{{ $requisition->requester_name ?? ($requisition->user->name ?? 'N/A') }}</td>
                                            <td>
                                                <span class="badge {{ $requisition->status_badge_class }}">
                                                    {{ $requisition->status ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>{{ optional($requisition->created_at)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                            <td class="table-actions-cell">
                                                <a href="{{ route('president.requisitions.show', $requisition->id) }}"
                                                   class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <form action="{{ route('president.requisitions.approve', $requisition->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success" title="Approve Requisition"
                                                            onclick="return confirm('Are you sure you want to grant final approval for requisition ID {{ $requisition->id }}?')">
                                                        <i class="fas fa-check-double"></i> Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('president.requisitions.reject', $requisition->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Reject Requisition"
                                                            onclick="return confirm('Are you sure you want to reject requisition ID {{ $requisition->id }}?')">
                                                         <i class="fas fa-times-circle"></i> Reject
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- Pagination Links --}}
                        @if($requisitions->hasPages())
                            <div style="margin-top: 20px;">
                                {{ $requisitions->links() }}
                            </div>
                        @endif
                    @endif
                @else
                    <div class="alert alert-warning" role="alert">
                         Could not load requisition data for presidential review.
                    </div>
                @endisset
            </div> {{-- End #review-requisitions --}}

            {{-- *** MODIFIED SECTION *** --}}
            {{-- Section 2: Notifications --}}
            <div id="notifications-content" class="content-section"> {{-- Changed ID --}}
                <h2>Notifications</h2> {{-- Changed Title --}}
                <p>System notifications and important alerts will appear here.</p> {{-- Adjusted Text --}}

                {{-- Placeholder for actual notification logic --}}
                {{-- You would fetch notifications from your backend (e.g., using Laravel's Notification system)
                     and loop through them here. --}}
                @php
                    // Example: Fetch notifications (replace with your actual logic)
                    // $notifications = Auth::user()->unreadNotifications;
                    $notifications = []; // Replace with actual fetched notifications later
                @endphp

                @if (!empty($notifications) && count($notifications) > 0)
                    <ul class="list-group">
                        @foreach ($notifications as $notification)
                            {{-- Adjust structure based on your notification data --}}
                            <li class="list-group-item list-group-item-action {{ $notification->data['type'] ?? 'list-group-item-info' }}"> {{-- Example: using type for color --}}
                                {{ $notification->data['message'] ?? 'Notification content missing.' }}
                                <small class="text-muted float-right">{{ $notification->created_at->diffForHumans() }}</small>
                                {{-- Add action buttons like 'Mark as Read' if needed --}}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="alert alert-info">You have no new notifications.</div>
                @endif

                {{-- Example hardcoded notifications (Remove when using real data) --}}
                {{-- <ul class="list-group" style="margin-top: 20px;">
                    <li class="list-group-item list-group-item-success">Requisition #567 was fully approved.</li>
                    <li class="list-group-item list-group-item-warning">Budget review meeting scheduled for Friday.</li>
                    <li class="list-group-item">User 'Finance Manager' sent you a message.</li>
                </ul> --}}
            </div>

        </main> {{-- End Content Area --}}
    </div> {{-- End Dashboard Container --}}

    {{-- JavaScript for Tab Switching (Identical - No changes needed) --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            const sections = document.querySelectorAll('.content-area .content-section');
            const defaultSectionId = 'review-requisitions'; // Default section for President

            function activateSection(targetId) {
                 sections.forEach(section => {
                     section.id === targetId ? section.classList.add('active') : section.classList.remove('active');
                 });
            }
            function activateLink(clickedLink) {
                 navLinks.forEach(link => link.classList.remove('active'));
                 if (clickedLink) clickedLink.classList.add('active');
            }

            navLinks.forEach(link => {
                 link.addEventListener('click', function(e) {
                     e.preventDefault();
                     const targetId = this.getAttribute('data-target');
                     const targetSection = document.getElementById(targetId);
                     if (targetId && targetSection) {
                         activateLink(this);
                         activateSection(targetId);
                     }
                 });
             });

            // Activate initial section based on hash or default
            let initialTargetId = window.location.hash.substring(1) || defaultSectionId;
            let initialActiveLink = document.querySelector(`.sidebar .nav-link[data-target="${initialTargetId}"]`);

             if (!document.getElementById(initialTargetId)) { // Fallback if hash target doesn't exist
                 initialTargetId = defaultSectionId;
                 initialActiveLink = document.querySelector(`.sidebar .nav-link[data-target="${initialTargetId}"]`);
             }

            if (document.getElementById(initialTargetId)) {
                activateSection(initialTargetId);
                activateLink(initialActiveLink);
            } else if (sections.length > 0) { // Fallback if default also fails
                sections[0].classList.add('active');
                if(navLinks.length > 0) activateLink(navLinks[0]);
            }
        });
    </script>

</x-app-layout>
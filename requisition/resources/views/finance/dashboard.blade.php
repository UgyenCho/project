{{-- resources/views/admin/dashboard.blade.php --}}

<x-app-layout>
    {{-- Admin doesn't have the separate header slot in the example --}}

    {{-- ====================================================================== --}}
    {{-- == ADMIN DASHBOARD - Overview / Reqs / Notifications == --}}
    {{-- ====================================================================== --}}

    {{-- FontAwesome CSS assumed to be included --}}

    {{-- Embedded Styles (Using Admin Sidebar Style) --}}
    <style>
        /* === Base & Layout === */
        :root {
             --admin-sidebar-bg: rgb(20, 110, 110); /* Teal */
             --admin-sidebar-text: #e0f2f1;
             --admin-sidebar-hover-bg: rgba(255, 255, 255, 0.1);
             --admin-sidebar-active-bg: #2563eb; /* Blue */
             --admin-sidebar-active-text: #fff;
             --admin-sidebar-active-border: #facc15; /* Yellow */
             --admin-card-bg: #fff;
             --admin-content-bg: #f8f9fa;
        }
        .dashboard-container { display: flex; width: 100%; max-width: 100%; min-height: 100vh; }

        /* === Sidebar === */
        .sidebar { width: 250px; background-color: var(--admin-sidebar-bg); color: var(--admin-sidebar-text); padding: 20px 0px; display: flex; flex-direction: column; flex-shrink: 0; overflow-y: auto; }
        .sidebar .sidebar-header { padding: 0px 15px 20px 15px; text-align: center; border-bottom: 1px solid rgba(255, 255, 255, 0.1); margin-bottom: 20px; }
        .sidebar .sidebar-header img { max-height: 45px; margin-bottom: 10px; }
        .sidebar .sidebar-header h2 { color: #fff; font-size: 1.1em; font-weight: 600; margin-top: 5px; letter-spacing: 0.5px; }
        .sidebar ul { list-style: none; flex-grow: 1; padding-left: 0; margin-bottom: 1rem; }
        .sidebar ul li { margin-bottom: 2px; }
        .sidebar ul li a.nav-link { color: var(--admin-sidebar-text); text-decoration: none; display: flex; align-items: center; padding: 14px 20px; border-left: 4px solid transparent; transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out, border-left-color 0.2s ease-in-out; font-size: 0.95rem; }
        .sidebar ul li a i.fa-fw { margin-right: 15px; width: 20px; text-align: center; font-size: 1.1em; }
        .sidebar ul li a.nav-link:hover { background-color: var(--admin-sidebar-hover-bg); color: #fff; }
        .sidebar ul li a.nav-link.active { background-color: rgba(0, 0, 0, 0.1); /* Slightly darker active */ color: var(--admin-sidebar-active-text); font-weight: 600; border-left-color: var(--admin-sidebar-active-border); }

        /* === Content Area === */
        .content-area { flex-grow: 1; padding: 30px; background-color: var(--admin-content-bg); overflow-y: auto; }
        .content-section { display: none; margin-bottom: 20px; } /* Basic sections hidden */
        .content-section.active { display: block; } /* Show active one */
        /* Style titles within content sections */
        .content-section h1.section-main-title { font-size: 1.8em; font-weight: 600; color: #333; margin-bottom: 5px; }
        .content-section .section-subtitle { font-size: 1rem; color: #666; margin-bottom: 30px; }
        .content-section h2.section-title { font-size: 1.6em; color: #333; margin-bottom: 25px; padding-bottom: 10px; border-bottom: 1px solid #eee; }

        /* === Admin Overview Cards === */
        .overview-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; }
        .card-link { text-decoration: none; color: inherit; display: block; transition: transform 0.2s ease-out, box-shadow 0.2s ease-out; }
        .card-link:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0,0,0,0.08); }
        .card { background-color: var(--admin-card-bg); border-radius: 8px; padding: 20px 25px; box-shadow: 0 3px 10px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: flex-start; }
        .card .card-content { flex-grow: 1; padding-right: 15px; }
        .card .card-category { font-size: 0.75rem; font-weight: 600; color: #0ea5e9; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px; }
        .card .card-title { font-size: 1.25rem; font-weight: 600; color: #333; margin-bottom: 4px; }
        .card .card-description { font-size: 0.9rem; color: #666; }
        .card .card-icon i { font-size: 2.5rem; color: #9ca3af; }

         /* === Table Styles (for View Requisitions Section) === */
         .table-responsive { overflow-x: auto; margin-bottom: 10px;}
         .table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; background-color: #fff; }
         .table th, .table td { padding: .75rem; vertical-align: middle; border-top: 1px solid #dee2e6; }
         .table thead th { vertical-align: bottom; border-bottom: 2px solid #dee2e6; background-color: #e9ecef; text-align: left; font-weight: bold; }
         .table-striped tbody tr:nth-of-type(odd) { background-color: rgba(0,0,0,.05); }
         .table-hover tbody tr:hover { color: #212529; background-color: rgba(0,0,0,.075); }
         .badge { display: inline-block; padding: .35em .65em; font-size: .75em; font-weight: 700; line-height: 1; color: #fff; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; }
         .bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
         .bg-success { background-color: #198754 !important; }
         .bg-danger { background-color: #dc3545 !important; }
         .bg-info { background-color: #0dcaf0 !important; color: #000 !important;}
         .bg-secondary { background-color: #6c757d !important; } /* Pending */
         .bg-primary { background-color: #0d6efd !important; } /* Issued */
         .table-actions-cell .btn, .table-actions-cell form { margin-right: 5px; margin-bottom: 5px; vertical-align: middle; }
        .btn { display: inline-block; font-weight: 400; color: #212529; text-align: center; vertical-align: middle; cursor: pointer; user-select: none; background-color: transparent; border: 1px solid transparent; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out; }
        .btn-sm { padding: .25rem .5rem; font-size: .875rem; line-height: 1.5; border-radius: .2rem; }
        .btn-info { color: #fff; background-color: #17a2b8; border-color: #17a2b8; }
        .btn-info:hover { color: #fff; background-color: #138496; border-color: #117a8b; }
        .btn-danger { color: #fff; background-color: #dc3545; border-color: #dc3545; }
        .btn-danger:hover { color: #fff; background-color: #c82333; border-color: #bd2130; }
        a.btn, button.btn { text-decoration: none; }
        .btn i.fas { margin-right: .3em; }
        /* Alerts */
        .alert { padding: 15px; border: 1px solid transparent; border-radius: 4px; margin-bottom: 20px; }
        .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .alert-info { background-color: #d1ecf1; color: #0c5460; border-color: #bee5eb; }
        .alert-warning { background-color: #fff3cd; color: #856404; border-color: #ffeeba; }

    </style>

    {{-- Main Dashboard Container --}}
    <div class="dashboard-container">

        {{-- Sidebar Navigation (Admin Style) --}}
        <nav class="sidebar">
             <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}">
                     <img src="{{ asset('images/1.png') }}" alt="Logo"> {{-- UPDATE LOGO PATH --}}
                </a>
                <h2>REQUISITION SYSTEM</h2>
            </div>
             <ul>
                 {{-- Links point to content section IDs --}}
                 <li><a href="#" data-target="admin-overview" class="nav-link active"><i class="fas fa-fw fa-tachometer-alt"></i> Dashboard</a></li>
                 <li><a href="#" data-target="view-requisitions" class="nav-link"><i class="fas fa-fw fa-clipboard-list"></i> View Requisitions</a></li>
                 {{-- REMOVED User Management Link --}}
                 <li><a href="#" data-target="notification-content" class="nav-link"><i class="fas fa-fw fa-bell"></i> Notification</a></li>
             </ul>
         </nav>

        {{-- Main Content Area --}}
        <main class="content-area">

            {{-- Session Feedback --}}
            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops! Please correct the errors below:</strong>
                    <ul style="margin-top: 10px; padding-left: 20px;"> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
                </div>
            @endif

            {{-- ====================================================================== --}}
            {{-- == Section 1: Admin Overview (Cards)                              == --}}
            {{-- ====================================================================== --}}
            <div id="admin-overview" class="content-section active"> {{-- Default visible section --}}
                <h1 class="section-main-title">Admin Dashboard</h1>
                <p class="section-subtitle">Manage requisitions and system settings.</p> {{-- Updated subtitle --}}

                <div class="overview-cards">
                    {{-- Card 1: View Requisitions --}}
                    <a href="#" data-target="view-requisitions" class="card-link overview-card"> {{-- Link targets the table section --}}
                        <div class="card">
                            <div class="card-content">
                                <p class="card-category">Requisitions</p>
                                {{-- Changed Card Title --}}
                                <h3 class="card-title">View Requisitions</h3>
                                <p class="card-description">Review all submitted forms.</p>
                            </div>
                            <div class="card-icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                        </div>
                    </a>

                    {{-- Card 2: User Management REMOVED --}}

                    {{-- Card 3: Notifications --}}
                    <a href="#" data-target="notification-content" class="card-link overview-card"> {{-- Link targets notification section --}}
                        <div class="card">
                            <div class="card-content">
                                <p class="card-category">Updates</p>
                                <h3 class="card-title">Notifications</h3>
                                <p class="card-description">View system alerts.</p>
                            </div>
                            <div class="card-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                        </div>
                    </a>
                     {{-- Add more cards for other admin functions if needed --}}
                </div>
            </div> {{-- End #admin-overview --}}

            {{-- ====================================================================== --}}
            {{-- == Section 2: View All Requisitions (Table Content)               == --}}
            {{-- ====================================================================== --}}
            <div id="view-requisitions" class="content-section"> {{-- Hidden by default --}}
                <h2 class="section-title">View All Requisitions</h2>

                {{-- Controller MUST pass $requisitions (all or filtered for Admin) --}}
                @isset($requisitions)
                    @if($requisitions->isEmpty())
                        <div class="alert alert-info" role="alert">
                            No requisitions have been submitted yet.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
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
                                            <td>{{ $requisition->department ?? 'N/A' }}</td>
                                            <td>{{ $requisition->requester_name ?? ($requisition->user->name ?? 'N/A') }}</td>
                                            <td>
                                                <span class="badge {{ $requisition->status_badge_class ?? 'bg-secondary' }}">
                                                    {{ $requisition->status ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>{{ optional($requisition->created_at)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                            <td class="table-actions-cell">
                                                {{-- Admin View Button --}}
                                                <a href="{{ route('admin.requisitions.show', $requisition->id) }}" {{-- Use Admin route --}}
                                                   class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                {{-- Example Delete Button (Add route/controller logic) --}}
                                                {{-- <form action="{{ route('admin.requisitions.destroy', $requisition->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('DELETE Requisition ID {{ $requisition->id }}? This is permanent.')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form> --}}
                                                {{-- Add other admin actions (e.g., override status) as needed --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- Pagination Links --}}
                        @if($requisitions instanceof \Illuminate\Pagination\LengthAwarePaginator && $requisitions->hasPages())
                            <div style="margin-top: 20px;">
                                {{ $requisitions->withQueryString()->links() }}
                            </div>
                        @endif
                    @endif
                @else
                    <div class="alert alert-warning" role="alert">
                         Could not load requisition data. Ensure the $requisitions variable is passed from the Admin controller.
                    </div>
                @endisset
            </div> {{-- End #view-requisitions --}}

            {{-- Section 3: User Management REMOVED --}}

            {{-- =================================================== --}}
            {{-- Section 4: Notification Content Placeholder      == --}}
            {{-- =================================================== --}}
            <div id="notification-content" class="content-section"> {{-- Hidden by default --}}
                <h2 class="section-title">Notifications</h2>
                <p>System-wide notifications or alerts relevant to the Admin will be displayed here.</p>
                 <div class="alert alert-info">No new notifications at this time.</div>
                 {{-- TODO: Add logic to display admin notifications --}}
            </div>

        </main> {{-- End Content Area --}}

    </div> {{-- End Dashboard Container --}}

    {{-- JavaScript for Tab Switching & Card Clicks --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
            const cardLinks = document.querySelectorAll('.overview-card'); // Links wrapping the cards
            const sections = document.querySelectorAll('.content-area .content-section');
            const defaultSectionId = 'admin-overview'; // Default is the card overview

            function activateSection(targetId) {
                sections.forEach(section => {
                    section.id === targetId ? section.classList.add('active') : section.classList.remove('active');
                });
            }

            function activateSidebarLink(targetId) {
                sidebarLinks.forEach(link => {
                    if (link.getAttribute('data-target') === targetId) {
                        link.classList.add('active');
                    } else {
                        link.classList.remove('active');
                    }
                });
            }

            // Function to handle clicks on both sidebar and card links
            function handleLinkClick(targetId) {
                 const targetSection = document.getElementById(targetId);
                 if (targetId && targetSection) {
                     activateSection(targetId);
                     activateSidebarLink(targetId); // Highlight corresponding sidebar link

                    // Update URL hash only if it's not the default overview
                    if (targetId !== defaultSectionId) {
                        if(history.pushState) { history.pushState(null, null, '#' + targetId); }
                        else { window.location.hash = '#' + targetId; }
                    } else {
                        // If going back to overview, remove hash
                         if(history.pushState) { history.pushState("", document.title, window.location.pathname + window.location.search); }
                         else { window.location.hash = ''; }
                    }
                 } else {
                     console.warn(`Target section '${targetId}' not found.`);
                 }
            }

            // --- Event Listeners ---
            sidebarLinks.forEach(link => {
                 link.addEventListener('click', function(e) {
                     e.preventDefault();
                     const targetId = this.getAttribute('data-target');
                     handleLinkClick(targetId);
                 });
             });

             cardLinks.forEach(link => {
                 link.addEventListener('click', function(e) {
                     e.preventDefault();
                     const targetId = this.getAttribute('data-target');
                     handleLinkClick(targetId);
                 });
             });

            // --- Initial State Logic ---
            let initialTargetId = defaultSectionId; // Default to overview
            const currentHash = window.location.hash.substring(1);

            if (currentHash && document.getElementById(currentHash)) {
                initialTargetId = currentHash; // If hash exists and is valid, use it
            }

            // Activate the initial section and corresponding sidebar link
            if (document.getElementById(initialTargetId)) {
                 activateSection(initialTargetId);
                 activateSidebarLink(initialTargetId);
            } else if (sections.length > 0) { // Absolute fallback
                 const firstSection = sections[0];
                 firstSection.classList.add('active');
                 const firstSidebarLink = document.querySelector(`.sidebar .nav-link[data-target="${firstSection.id}"]`);
                 if(firstSidebarLink) activateSidebarLink(firstSection.id);
            }
        });
    </script>

</x-app-layout>
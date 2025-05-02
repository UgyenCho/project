{{-- resources/views/hod/dashboard.blade.php (Example Path) --}}

<x-app-layout>
    {{-- HEADER SLOT REMOVED --}}

    {{-- ====================================================================== --}}
    {{-- == HOD DASHBOARD - View Requisitions / Notifications Layout == --}}
    {{-- ====================================================================== --}}

    {{-- FontAwesome CSS assumed to be included via layouts/app.blade.php or x-app-layout --}}

    {{-- Embedded Styles for the Custom Dashboard Layout --}}
    <style>
        /* === Dashboard Container === */
        .dashboard-container {
            display: flex;
            width: 100%;
            max-width: 100%;
            /* --- ADDED: Make container fill remaining vertical space --- */
            /* Adjust '4.1rem' if your main layout header has a different height,
               or remove the subtraction if the layout header is removed entirely */
            min-height: calc(100vh - 0rem); /* Set to 0rem if header space is fully gone */
            /* Or potentially just min-height: 100%; if the parent fills viewport */
        }
        /* === Sidebar === */
        .sidebar {
            width: 250px;
            /* Use the teal color requested for HOD, apply here if desired */
            background-color: rgb(15, 101, 122); */
            /* background-color: #333;  */
            color: #fff;
            padding: 20px 15px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            overflow-y: auto;
        }
        .sidebar h2 { text-align: center; margin-bottom: 30px; color: #eee; font-size: 1.5em; font-weight: bold; padding-bottom: 15px; border-bottom: 1px solid #444; }
        .sidebar ul { list-style: none; flex-grow: 1; padding-left: 0; margin-bottom: 1rem; }
        .sidebar ul li { margin-bottom: 8px; }
        .sidebar ul li a.nav-link { color: #ccc; text-decoration: none; display: flex; align-items: center; padding: 12px 15px; border-radius: 5px; transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out; font-size: 0.95rem; }
        .sidebar ul li a i.fa-fw { margin-right: 12px; width: 20px; text-align: center; font-size: 1.1em; }
        .sidebar ul li a.nav-link:hover { background-color: #444; color: #fff; }
        .sidebar ul li a.nav-link.active { background-color: #007bff; color: #fff; font-weight: 600; }
        /* === Content Area === */
        .content-area { flex-grow: 1; padding: 30px; background-color: #f8f9fa; overflow-y: auto; }
        .content-section { display: none; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .content-section.active { display: block; }
        .content-section h2 { font-size: 1.8em; color: #333; margin-bottom: 25px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        .content-section p { color: #555; font-size: 1rem; margin-bottom: 1rem; }
        /* Alerts */
        .alert { padding: 15px; border: 1px solid transparent; border-radius: 4px; margin-bottom: 20px; }
        .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .alert-info { background-color: #d1ecf1; color: #0c5460; border-color: #bee5eb; }
        .alert-warning { background-color: #fff3cd; color: #856404; border-color: #ffeeba; }
        /* Responsive */
        @media (max-width: 991.98px) { .sidebar { width: 200px; } .content-area { padding: 25px; } }
        @media (max-width: 767.98px) { .dashboard-container { flex-direction: column; } .sidebar { width: 100%; height: auto; position: relative; border-bottom: 1px solid #444; min-height: auto; } .sidebar ul { display: flex; justify-content: space-around; flex-wrap: wrap; flex-grow: 0; } .sidebar h2 { font-size: 1.3em; margin-bottom: 15px; padding-bottom: 10px; } .sidebar ul li { margin: 3px; } .sidebar ul li a.nav-link { padding: 8px 12px; } .content-area { width: 100%; padding: 20px; min-height: auto; } }

         /* General Table Styles */
         .table-responsive { overflow-x: auto; margin-bottom: 10px;}
         .table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; background-color: transparent; }
         .table th, .table td { padding: .75rem; vertical-align: middle; border-top: 1px solid #dee2e6; /* Centered vertically */ }
         .table thead th { vertical-align: bottom; border-bottom: 2px solid #dee2e6; background-color: #e9ecef; text-align: left; font-weight: bold; }
         .table tbody + tbody { border-top: 2px solid #dee2e6; }
         .table-striped tbody tr:nth-of-type(odd) { background-color: rgba(0,0,0,.05); }
         .table-hover tbody tr:hover { color: #212529; background-color: rgba(0,0,0,.075); }
         /* Status Badges */
         .badge { display: inline-block; padding: .35em .65em; font-size: .75em; font-weight: 700; line-height: 1; color: #fff; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; }
         .bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
         .bg-success { background-color: #198754 !important; }
         .bg-danger { background-color: #dc3545 !important; }
         .bg-info { background-color: #0dcaf0 !important; color: #000 !important;}
         .bg-secondary { background-color: #6c757d !important; }
         .bg-primary { background-color: #0d6efd !important; }
         /* Action Buttons in Table */
         .table-actions-cell .btn, .table-actions-cell form {
             margin-right: 5px;
             margin-bottom: 5px; /* Spacing for wrapping on small screens */
             vertical-align: middle; /* Align buttons nicely */
         }
          /* Basic Button Styles (if not using a framework like Bootstrap) */
        .btn { display: inline-block; font-weight: 400; color: #212529; text-align: center; vertical-align: middle; cursor: pointer; user-select: none; background-color: transparent; border: 1px solid transparent; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out; }
        .btn-sm { padding: .25rem .5rem; font-size: .875rem; line-height: 1.5; border-radius: .2rem; }
        .btn-info { color: #fff; background-color: #17a2b8; border-color: #17a2b8; }
        .btn-info:hover { color: #fff; background-color: #138496; border-color: #117a8b; }
        .btn-success { color: #fff; background-color: #28a745; border-color: #28a745; }
        .btn-success:hover { color: #fff; background-color: #218838; border-color: #1e7e34; }
        .btn-danger { color: #fff; background-color: #dc3545; border-color: #dc3545; }
        .btn-danger:hover { color: #fff; background-color: #c82333; border-color: #bd2130; }
        a.btn, button.btn { text-decoration: none; } /* Ensure links look like buttons */
        /* Ensure buttons with icons align text */
        .btn i.fas { margin-right: .3em; }

    </style>

    {{-- Main Dashboard Container --}}
    <div class="dashboard-container">

        {{-- Sidebar Navigation --}}
        <nav class="sidebar">
             <h2>HOD Dashboard</h2>
             <ul>
                 <li><a href="#" data-target="view-requisitions" class="nav-link active"><i class="fas fa-fw fa-clipboard-list"></i> View Requisitions</a></li>
                 <li><a href="#" data-target="notification-content" class="nav-link"><i class="fas fa-fw fa-bell"></i> Notification</a></li>
             </ul>
         </nav>

        {{-- Main Content Area --}}
        <main class="content-area">

            {{-- Session Feedback & Validation Errors --}}
            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops! Please correct the errors below:</strong>
                    <ul style="margin-top: 10px; padding-left: 20px;"> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
                </div>
            @endif

            {{-- REMOVED: Requisition Form Section --}}

            {{-- ====================================================================== --}}
            {{-- == Section 1: View Submitted Requisitions                             == --}}
            {{-- ====================================================================== --}}
            <div id="view-requisitions" class="content-section active">
                <h2>View Submitted Requisitions</h2>

                {{-- Controller needs to pass $requisitions for the HOD --}}
                @isset($requisitions)
                    @if($requisitions->isEmpty())
                        <div class="alert alert-info" role="alert">
                            There are no requisitions currently requiring your attention or submitted by LRC staff for your department.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Req. Date</th>
                                        <th>Requester</th>
                                        <th>Designation</th>
                                        <th>Status</th>
                                        <th>Submitted At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requisitions as $requisition)
                                        <tr>
                                            <td>{{ optional($requisition->requisition_date)->format('Y-m-d') ?? 'N/A' }}</td>
                                            <td>{{ $requisition->requester_name ?? ($requisition->user->name ?? 'N/A') }}</td>
                                            <td>{{ $requisition->designation_text ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge {{ $requisition->status_badge_class ?? 'bg-secondary' }}">
                                                    {{ $requisition->status ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>{{ optional($requisition->created_at)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                            <td class="table-actions-cell">
                                                {{-- View Details Button --}}
                                                <a href="{{ route('hod.requisitions.show', $requisition->id) }}"
                                                   class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i> View
                                                </a>

                                                {{-- Conditional Actions --}}
                                                @if ($requisition->status === 'Pending' || $requisition->status === 'Pending HOD Approval')
                                                    {{-- Approve Button Form --}}
                                                    <form action="{{ route('hod.requisitions.approve', $requisition->id) }}" method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-success" title="Approve" onclick="return confirm('Are you sure you want to approve requisition ID {{ $requisition->id }}?')">
                                                            <i class="fas fa-check"></i> Approve
                                                        </button>
                                                    </form>
                                                    {{-- Reject Button Form --}}
                                                    <form action="{{ route('hod.requisitions.reject', $requisition->id) }}" method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Reject" onclick="return confirm('Are you sure you want to reject requisition ID {{ $requisition->id }}?')">
                                                             <i class="fas fa-times"></i> Reject
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- Optional: Pagination Links --}}
                        {{-- @if($requisitions->hasPages()) <div style="margin-top: 20px;">{{ $requisitions->links() }}</div> @endif --}}
                    @endif
                @else
                    <div class="alert alert-warning" role="alert">
                         Could not load requisition data. Ensure the $requisitions variable is passed from the HOD controller.
                    </div>
                @endisset
            </div> {{-- End #view-requisitions --}}


            {{-- =================================================== --}}
            {{-- Section 2: Notification                  --}}
            {{-- =================================================== --}}
            <div id="notification-content" class="content-section">
                <h2>Notifications</h2>
                <p>Important notifications relevant to the HOD will be displayed here.</p>
                 <div class="alert alert-info">No new notifications at this time.</div>
            </div>

        </main> {{-- End Content Area --}}

    </div> {{-- End Dashboard Container --}}

    {{-- JavaScript for Tab Switching --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            const sections = document.querySelectorAll('.content-area .content-section');
            const defaultSection = document.getElementById('view-requisitions');

            function activateSection(targetId) {
                sections.forEach(section => {
                    section.id === targetId ? section.classList.add('active') : section.classList.remove('active');
                });
            }
            function activateLink(clickedLink) {
                navLinks.forEach(link => link.classList.remove('active'));
                if (clickedLink) {
                    clickedLink.classList.add('active');
                }
            }

            navLinks.forEach(link => {
                 link.addEventListener('click', function(e) {
                     e.preventDefault();
                     const targetId = this.getAttribute('data-target');
                     const targetSection = document.getElementById(targetId);
                     if (targetId && targetSection) {
                         activateLink(this);
                         activateSection(targetId);
                         if(history.pushState) { history.pushState(null, null, '#' + targetId); }
                         else { window.location.hash = '#' + targetId; }
                     } else { console.warn(`Target section '${targetId}' not found.`);}
                 });
             });

            let initialTargetId = null;
            let initialActiveLink = null;
            const currentHash = window.location.hash.substring(1);
            const linkForHash = currentHash ? document.querySelector(`.sidebar .nav-link[data-target="${currentHash}"]`) : null;

            if (linkForHash && document.getElementById(currentHash)) {
                initialTargetId = currentHash;
                initialActiveLink = linkForHash;
            } else if (defaultSection) {
                 initialTargetId = defaultSection.id;
                 initialActiveLink = document.querySelector(`.sidebar .nav-link[data-target="${initialTargetId}"]`);
            } else if (navLinks.length > 0 && sections.length > 0) {
                 initialActiveLink = navLinks[0];
                 initialTargetId = initialActiveLink?.getAttribute('data-target');
            }

            if (initialTargetId && document.getElementById(initialTargetId)) {
                activateSection(initialTargetId);
                activateLink(initialActiveLink);
            } else if (sections.length > 0) {
                 sections[0].classList.add('active');
                 if(navLinks.length > 0) { activateLink(navLinks[0]); }
            }
        });
    </script>

</x-app-layout>
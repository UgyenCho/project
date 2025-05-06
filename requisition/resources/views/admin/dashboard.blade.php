{{-- Ensure app.blade.php uses container-fluid or no container for full width --}}
<x-app-layout>
    {{-- Styles Section --}}
    <style>
        /* === Dashboard Container === */
        .dashboard-container { display: flex; width: 100%; max-width: 100%; min-height: calc(100vh - 0rem); /* Adjust as needed */ }

        /* === Sidebar === */
        .sidebar { width: 260px; background-color:rgb(15, 101, 122); color: #fff; padding: 20px 15px; display: flex; flex-direction: column; flex-shrink: 0; overflow-y: auto; position: sticky; top: 0; height: 100vh; }
        .sidebar .sidebar-header { padding: 1rem 1.5rem; text-align: center; margin-bottom: 1rem; border-bottom: 1px solid #495057; }
        .sidebar .sidebar-header h2 { text-align: center; margin-bottom: 0; color: #fff; font-size: 1.1em; font-weight: bold; padding-bottom: 0; border-bottom: none; }
        .sidebar ul { list-style: none; flex-grow: 1; padding-left: 0; margin-bottom: 1rem; }
        .sidebar ul li { margin-bottom: 8px; }
        .sidebar ul li a.nav-link { color: #ccc; text-decoration: none; display: flex; align-items: center; padding: 12px 15px; border-radius: 5px; transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out; font-size: 0.95rem; }
        .sidebar ul li a i.fa-fw { margin-right: 12px; width: 20px; text-align: center; font-size: 1.1em; }
        .sidebar ul li a.nav-link:hover { background-color: #343a40; color: #fff; }
        .sidebar ul li a.nav-link.active { background-color: #0d6efd; color: #fff; font-weight: 600; }
        .sidebar .sidebar-footer { margin-top: auto; padding: 1rem; text-align: center; color: rgba(255, 255, 255, 0.7); font-size: 0.85rem; border-top: 1px solid #495057; }

        /* === Content Area === */
        .content-area { flex-grow: 1; padding: 30px; background-color: #f8f9fa; overflow-y: auto; }

        /* --- Content Section Visibility --- */
        .content-section { display: none; width: 100%; margin-bottom: 20px; }
        .content-section.active { display: block; }

        /* Page Heading */
        .page-heading { font-size: 1.8em; color: #333; margin-bottom: 25px; padding-bottom: 10px; border-bottom: 1px solid #eee; }

        /* --- Card Styles --- */
        .card { margin-bottom: 1.5rem; border: 1px solid #e3e6f0; box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15); border-radius: .35rem; background-color: #ffffff; }
        .card-header { padding: .75rem 1.25rem; margin-bottom: 0; background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0; font-weight: bold; color: #4e73df; }
        .card-body { padding: 1.25rem; }

        /* User Form Container Visibility */
        .user-form-container { display: none; /* Keep hidden initially */ }

        /* Alerts & Tables */
        .alert { padding: 1rem; border: 1px solid transparent; border-radius: .35rem; margin-bottom: 1.5rem; position: relative; }
        .alert-dismissible .btn-close { position: absolute; top: 0; right: 0; z-index: 2; padding: 1.25rem 1rem; }
        .alert-success { background-color: #d1e7dd; color: #0f5132; border-color: #badbcc; }
        .alert-danger { background-color: #f8d7da; color: #842029; border-color: #f5c2c7; }
        .alert-info { background-color: #cff4fc; color: #055160; border-color: #b6effb; }
        .alert-warning { background-color: #fff3cd; color: #664d03; border-color: #ffecb5; }

        .table-responsive { overflow-x: auto; margin-bottom: 1rem; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; background-color: transparent; color: #5a5c69; }
        .table th, .table td { padding: .75rem; vertical-align: middle; border-top: 1px solid #e3e6f0; }
        .table thead th { vertical-align: bottom; border-bottom: 2px solid #e3e6f0; background-color: #f8f9fc; text-align: left; font-weight: bold; color: #4e73df; }
        .table tbody + tbody { border-top: 2px solid #e3e6f0; }
        .table-hover tbody tr:hover { color: #212529; background-color: #f8f9fc; }
        .badge { display: inline-block; padding: .35em .65em; font-size: .75em; font-weight: 700; line-height: 1; color: #fff; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; }
        .bg-warning { background-color: #ffc107 !important; color: #212937 !important;}
        .bg-success { background-color: #1cc88a !important; }
        .bg-danger { background-color: #e74a3b !important; }
        .bg-info { background-color: #36b9cc !important; }
        .bg-secondary { background-color: #858796 !important; }
        .bg-primary { background-color: #4e73df !important; }


        /* Action Buttons Styling */
        .action-buttons form, .action-buttons .btn { margin: 0 2px; display: inline-block; vertical-align: middle; }
        .action-buttons .btn { padding: .25rem .5rem; font-size: .875rem; line-height: 1.5; border-radius: .2rem; }
        .btn-warning { color: #1f2937; background-color: #f6c23e; border-color: #f6c23e; }
        .btn-warning:hover { color: #1f2937; background-color: #f4b619; border-color: #f4b619; }
        .btn-danger { color: #fff; background-color: #e74a3b; border-color: #e74a3b; }
        .btn-danger:hover { color: #fff; background-color: #e02d1b; border-color: #d52a1a; }
        .btn-primary { color: #fff; background-color: #4e73df; border-color: #4e73df; }
        .btn-primary:hover { color: #fff; background-color: #2e59d9; border-color: #2653d4; }
        .btn-secondary { color: #fff; background-color: #858796; border-color: #858796; }
        .btn-secondary:hover { color: #fff; background-color: #717384; border-color: #6b6d7d; }
        .btn i.fas, .btn i.fa { margin-right: .3em; } /* Added fa for older icons if used */
        .btn-info { color: #fff; background-color: #36b9cc; border-color: #36b9cc;}
        .btn-info:hover { color: #fff; background-color: #2a96a5; border-color: #288f9c;}


        /* Horizontal Form Styling */
        .form-label { margin-bottom: .5rem; }
        .col-form-label { padding-top: calc(.375rem + 1px); padding-bottom: calc(.375rem + 1px); margin-bottom: 0; font-size: inherit; line-height: 1.5; }
        .text-md-end { text-align: right!important; } /* Ensure right alignment */
        @media (min-width: 768px) {
            .text-md-end { text-align: right!important; }
        }

        /* --- Responsive --- */
         @media (max-width: 767.98px) {
             .dashboard-container { flex-direction: column; }
             .sidebar { width: 100%; height: auto; position: relative; border-bottom: 1px solid #495057; min-height: auto; padding: 10px; height: auto; /* Override sticky height */ }
             .sidebar .sidebar-header { margin-bottom: 10px; padding: 0.5rem; }
             .sidebar .sidebar-header h2 { font-size: 1rem; }
             .sidebar ul { display: flex; justify-content: space-around; flex-wrap: wrap; flex-grow: 0; }
              .sidebar ul li { margin-bottom: 5px; }
              .sidebar ul li a.nav-link { padding: 8px 10px; font-size: 0.9rem; }
              .sidebar ul li a i.fa-fw { margin-right: 8px; }
             .sidebar .sidebar-footer { display: none; }
             .content-area { padding: 15px; }
             .page-heading { font-size: 1.5em; margin-bottom: 15px; }
             .action-buttons { display: flex; flex-wrap: wrap; }
             .action-buttons form, .action-buttons .btn { margin-bottom: 5px; }
             /* Stack form elements on small screens */
             .text-md-end { text-align: left !important; }
         }
    </style>
    {{-- End Styles Section --}}

    {{-- Main Dashboard Container --}}
    <div class="dashboard-container">

        {{-- Sidebar --}}
        <nav class="sidebar">
             <div class="sidebar-header">
                 <h2>ADMIN DASHBOARD</h2>
             </div>
            <ul>
                {{-- Set View Requisitions as active by default --}}
                <li><a href="#" data-target="view-requisitions-content" class="nav-link active"><i class="fas fa-fw fa-list-check"></i> View Requisitions</a></li>
                <li><a href="#" data-target="user-management-content" class="nav-link"><i class="fas fa-fw fa-users"></i> User Management</a></li>
                <li><a href="#" data-target="notifications-content" class="nav-link"><i class="fas fa-fw fa-bell"></i> Notification</a></li>
            </ul>
            <div class="sidebar-footer">© {{ date('Y') }} JNEC Admin</div>
        </nav>
        {{-- End Sidebar --}}

        {{-- Main Content Area --}}
        <main class="content-area">

            {{-- Dynamic Page Heading --}}
            <h1 class="page-heading" id="page-heading">View Submitted Requisitions</h1>

            {{-- Session Feedback & Validation Errors --}}
            @if (session('success'))<div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>@endif
            @if (session('error'))<div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>@endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                     <strong>Whoops! Errors occurred:</strong>
                    <ul class="mb-0 mt-2 ps-4">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            {{-- End Feedback Area --}}


            {{-- === START: View Requisitions Content === --}}
            <div id="view-requisitions-content" class="content-section active">
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0">Submitted Requisitions</h6></div>
                    <div class="card-body">
                        {{-- *** START: Requisitions Table *** --}}
                        {{-- Assumes a $requisitions variable is passed from controller --}}
                        {{-- Add appropriate routes like 'admin.requisitions.show' etc. --}}
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="requisitionsTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Department</th>
                                        <th>Requester</th>
                                        <th>Status</th>
                                        <th>Submitted At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @isset($requisitions) {{-- Check if data is passed --}}
                                       @forelse($requisitions as $requisition)
                                       <tr>
                                           <td>{{ $requisition->id }}</td>
                                           <td>{{ optional($requisition->requisition_date)->format('Y-m-d') ?? 'N/A' }}</td>
                                           {{-- Assuming department relationship exists --}}
                                           <td>{{ $requisition->department->name ?? ($requisition->department_id ?? 'N/A') }}</td>
                                           {{-- Assuming user relationship exists, or use requester_name if stored --}}
                                           <td>{{ $requisition->user->name ?? ($requisition->requester_name ?? 'N/A') }}</td>
                                           {{-- Assuming an accessor 'status_badge_class' exists in Requisition model --}}
                                           <td><span class="badge {{ $requisition->status_badge_class ?? 'bg-secondary' }}">{{ $requisition->status ?? 'N/A' }}</span></td>
                                           <td>{{ optional($requisition->created_at)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                           <td class="action-buttons">
                                                {{-- Example View Action Button --}}
                                                {{-- Replace '#' with actual route --}}
                                                <a href="{{-- route('admin.requisitions.show', $requisition->id) --}}#" class="btn btn-sm btn-info" title="View Details"><i class="fas fa-eye"></i></a>

                                                {{-- Placeholder for other Admin actions (Approve/Reject/Modify?) --}}
                                                {{-- <button class="btn btn-sm btn-success"><i class="fas fa-check"></i></button> --}}
                                                {{-- <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button> --}}
                                           </td>
                                       </tr>
                                       @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No requisitions found matching the criteria.</td>
                                            </tr>
                                       @endforelse
                                     @else {{-- Handle case where $requisitions isn't passed --}}
                                        <tr><td colspan="7" class="text-center text-danger">Requisition data is currently unavailable.</td></tr>
                                     @endisset
                                </tbody>
                            </table>
                        </div>
                         {{-- Pagination Links --}}
                         @isset($requisitions)
                            @if($requisitions instanceof \Illuminate\Pagination\LengthAwarePaginator && $requisitions->hasPages())
                                <div class="mt-3 d-flex justify-content-center">
                                     {{ $requisitions->links() }}
                                </div>
                            @endif
                         @endisset
                         {{-- *** END: Requisitions Table *** --}}
                    </div>
                </div>
            </div>
            {{-- === END: View Requisitions Content === --}}


            {{-- === START: User Management Content === --}}
            <div id="user-management-content" class="content-section">

                {{-- 1. User Table Card --}}
                <div class="card shadow mb-4" id="user-table-card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0">System Users</h6>
                        <button class="btn btn-primary btn-sm" id="add-user-btn"><i class="fas fa-plus me-1"></i> Add User</button>
                    </div>
                    <div class="card-body">
                          <div class="table-responsive">
                              <table class="table table-bordered table-hover" id="userTable" width="100%" cellspacing="0">
                                  <thead>
                                      <tr>
                                          <th>ID</th>
                                          <th>Name</th>
                                          <th>Email</th>
                                          <th>Role</th>
                                          <th>Actions</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @isset($users)
                                          @forelse ($users as $user)
                                          <tr>
                                              <td>{{ $user->id }}</td>
                                              <td>{{ $user->name }}</td>
                                              <td>{{ $user->email }}</td>
                                              <td>{{ $user->role }}</td>
                                              <td class="action-buttons d-flex flex-nowrap">
                                                  <button class="btn btn-sm btn-warning edit-user-btn me-1"
                                                          title="Edit"
                                                          data-id="{{ $user->id }}"
                                                          data-name="{{ $user->name }}"
                                                          data-email="{{ $user->email }}"
                                                          data-role="{{ $user->role }}"
                                                          data-department-id="{{ $user->department_id }}"
                                                          data-action="{{ route('admin.users.update', $user->id) }}">
                                                      <i class="fas fa-edit"></i>
                                                  </button>
                                                  {{-- Prevent deleting self --}}
                                                  @if(Auth::id() !== $user->id)
                                                      <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('DELETE this user? This cannot be undone.');">
                                                          @csrf
                                                          @method('DELETE')
                                                          <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                                      </form>
                                                  @endif
                                              </td>
                                          </tr>
                                          @empty
                                          <tr><td colspan="5" class="text-center">No users found.</td></tr>
                                          @endforelse
                                      @else
                                          <tr><td colspan="5" class="text-center text-danger">User data not available.</td></tr>
                                      @endisset
                                  </tbody>
                              </table>
                          </div>
                    </div>
                </div>

                {{-- 2. Add User Form Container (Improved Horizontal Layout) --}}
                <div class="card shadow mb-4 user-form-container" id="add-user-form-container">
                    <div class="card-header py-3"><h6 class="m-0">Add New User</h6></div>
                    <div class="card-body">
                        <form action="{{ route('admin.users.store') }}" method="POST" id="add-user-form">
                            @csrf
                            {{-- Name --}}
                            <div class="row mb-3 align-items-center">
                                <label for="add-name" class="col-md-3 col-form-label text-md-end">Name <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control @error('name', 'store') is-invalid @enderror" id="add-name" name="name" value="{{ old('name') }}" required>
                                    @error('name', 'store') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            {{-- Email --}}
                            <div class="row mb-3 align-items-center">
                                <label for="add-email" class="col-md-3 col-form-label text-md-end">Email <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <input type="email" class="form-control @error('email', 'store') is-invalid @enderror" id="add-email" name="email" value="{{ old('email') }}" required>
                                    @error('email', 'store') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            {{-- Role --}}
                            <div class="row mb-3 align-items-center">
                                <label for="add-role" class="col-md-3 col-form-label text-md-end">Role <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <select class="form-select @error('role', 'store') is-invalid @enderror" id="add-role" name="role" required>
                                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select Role...</option>
                                        <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="LRC" {{ old('role') == 'LRC' ? 'selected' : '' }}>LRC</option>
                                        <option value="HOD" {{ old('role') == 'HOD' ? 'selected' : '' }}>HOD</option>
                                    </select>
                                    @error('role', 'store') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            {{-- Department --}}
                            <div class="row mb-3 align-items-center">
                                <label for="add-department_id" class="col-md-3 col-form-label text-md-end">Department <small>(Optional)</small></label>
                                <div class="col-md-7">
                                    <select class="form-select @error('department_id', 'store') is-invalid @enderror" id="add-department_id" name="department_id">
                                        <option value="">-- Select Department --</option>
                                        @isset($departments)
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                    {{ $department->name }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    @error('department_id', 'store') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            {{-- Password --}}
                            <div class="row mb-3 align-items-center">
                                <label for="add-password" class="col-md-3 col-form-label text-md-end">Password <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <input type="password" class="form-control @error('password', 'store') is-invalid @enderror" id="add-password" name="password" required>
                                    @error('password', 'store') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            {{-- Confirm Password --}}
                             <div class="row mb-3 align-items-center">
                                <label for="add-password-confirmation" class="col-md-3 col-form-label text-md-end">Confirm Password <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <input type="password" class="form-control" id="add-password-confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                            {{-- Action Buttons --}}
                            <div class="row mt-4">
                                <div class="col-md-7 offset-md-3">
                                    <button type="submit" class="btn btn-primary">Add User</button>
                                    <button type="button" class="btn btn-secondary ms-2 cancel-user-form-btn">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                 {{-- 3. Edit User Form Container (Improved Horizontal Layout) --}}
                <div class="card shadow mb-4 user-form-container" id="edit-user-form-container">
                    <div class="card-header py-3"><h6 class="m-0">Edit User</h6></div>
                    <div class="card-body">
                        <form action="" method="POST" id="edit-user-form"> {{-- Action set by JS --}}
                            @csrf
                            @method('PUT')
                             {{-- Name --}}
                            <div class="row mb-3 align-items-center">
                                <label for="edit-name" class="col-md-3 col-form-label text-md-end">Name <span class="text-danger">:</span></label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control @error('name', 'update') is-invalid @enderror" id="edit-name" name="name" required>
                                    @error('name', 'update') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            {{-- Email --}}
                            <div class="row mb-3 align-items-center">
                                <label for="edit-email" class="col-md-3 col-form-label text-md-end">Email <span class="text-danger">:</span></label>
                                <div class="col-md-7">
                                    <input type="email" class="form-control @error('email', 'update') is-invalid @enderror" id="edit-email" name="email" required>
                                    @error('email', 'update') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            {{-- Role --}}
                            <div class="row mb-3 align-items-center">
                                <label for="edit-role" class="col-md-3 col-form-label text-md-end">Role <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <select class="form-select @error('role', 'update') is-invalid @enderror" id="edit-role" name="role" required>
                                        <option value="" disabled>Select Role...</option>
                                        <option value="Admin">Admin</option>
                                        <option value="LRC">LRC</option>
                                        <option value="HOD">HOD</option>
                                    </select>
                                    @error('role', 'update') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            {{-- Department --}}
                            <div class="row mb-3 align-items-center">
                                <label for="edit-department_id" class="col-md-3 col-form-label text-md-end">Department <small>(Optional)</small></label>
                                <div class="col-md-7">
                                    <select class="form-select @error('department_id', 'update') is-invalid @enderror" id="edit-department_id" name="department_id">
                                        <option value="">-- Select Department --</option>
                                         @isset($departments)
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                         @endisset
                                    </select>
                                    @error('department_id', 'update') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            {{-- New Password --}}
                            <div class="row mb-3 align-items-center">
                                <label for="edit-password" class="col-md-3 col-form-label text-md-end">New Password <small>(Optional)</small></label>
                                <div class="col-md-7">
                                    <input type="password" class="form-control @error('password', 'update') is-invalid @enderror" id="edit-password" name="password" placeholder="Leave blank to keep current password">
                                    @error('password', 'update') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            {{-- Confirm New Password --}}
                            <div class="row mb-3 align-items-center">
                                <label for="edit-password-confirmation" class="col-md-3 col-form-label text-md-end">Confirm New Password</label>
                                <div class="col-md-7">
                                    <input type="password" class="form-control" id="edit-password-confirmation" name="password_confirmation">
                                </div>
                            </div>
                             {{-- Action Buttons --}}
                            <div class="row mt-4">
                                <div class="col-md-7 offset-md-3">
                                    <button type="submit" class="btn btn-primary">Update User</button>
                                    <button type="button" class="btn btn-secondary ms-2 cancel-user-form-btn">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            {{-- === END: User Management Content === --}}


            {{-- === START: Notifications Content === --}}
            <div id="notifications-content" class="content-section">
                 <div class="card shadow mb-4">
                     <div class="card-header py-3"><h6 class="m-0">Notifications</h6></div>
                     <div class="card-body">
                         <p>PLACEHOLDER - System notifications and alerts will appear here.</p>
                         {{-- Example Notification Item Structure --}}
                         {{--
                         <div class="list-group">
                             <a href="#" class="list-group-item list-group-item-action">
                                 <div class="d-flex w-100 justify-content-between">
                                     <h6 class="mb-1">New Requisition Submitted</h6>
                                     <small>3 days ago</small>
                                 </div>
                                 <p class="mb-1 small">Requisition #123 from DIT needs review.</p>
                             </a>
                             <a href="#" class="list-group-item list-group-item-action list-group-item-warning">
                                 <div class="d-flex w-100 justify-content-between">
                                     <h6 class="mb-1">User Password Reset</h6>
                                     <small class="text-muted">5 days ago</small>
                                 </div>
                                 <p class="mb-1 small">User 'john.doe' requested a password reset.</p>
                             </a>
                         </div>
                         --}}
                     </div>
                 </div>
            </div>
            {{-- === END: Notifications Content === --}}

        </main>
        {{-- End Content Area --}}

    </div>
    {{-- End Dashboard Container --}}


    {{-- Scripts Section --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- Elements ---
            const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
            const contentSections = document.querySelectorAll('.content-area .content-section');
            const pageHeading = document.getElementById('page-heading');

            // User Management Specific Elements
            const userManagementSection = document.getElementById('user-management-content');
            const userTableCard = document.getElementById('user-table-card');
            const addUserBtn = document.getElementById('add-user-btn');
            const addUserFormContainer = document.getElementById('add-user-form-container');
            const addUserForm = document.getElementById('add-user-form');
            const editUserFormContainer = document.getElementById('edit-user-form-container');
            const editUserForm = document.getElementById('edit-user-form');
            const cancelUserFormBtns = document.querySelectorAll('.cancel-user-form-btn');
            const userTableBody = document.getElementById('userTable')?.querySelector('tbody'); // Use optional chaining

            // --- Default Section ---
            const defaultSectionId = 'view-requisitions-content';
            const defaultHeading = 'View Submitted Requisitions';

            // --- Functions ---
            function activateSection(targetId) {
                contentSections.forEach(section => {
                    if (section.id === targetId) {
                        section.classList.add('active');
                        // Ensure user table is shown when navigating TO user management
                        if (targetId === 'user-management-content') {
                            showUserTable();
                        }
                    } else {
                        section.classList.remove('active');
                    }
                });
            }

            function activateLink(targetId) {
                sidebarLinks.forEach(link => {
                    if (link.getAttribute('data-target') === targetId) {
                        link.classList.add('active');
                    } else {
                        link.classList.remove('active');
                    }
                });
            }

            function updateHeading(text) {
                if (pageHeading) pageHeading.textContent = text;
            }

            function handleNavClick(targetId, headingText) {
                const targetSection = document.getElementById(targetId);
                if (targetSection) {
                    activateLink(targetId);
                    activateSection(targetId);
                    updateHeading(headingText);
                    // Optional: Update URL hash or use history API
                    // window.location.hash = targetId;
                } else {
                    console.warn(`Navigation target section not found: #${targetId}`);
                }
            }

            function showUserTable() {
                if (userTableCard) userTableCard.style.display = 'block';
                if (addUserFormContainer) addUserFormContainer.style.display = 'none';
                if (editUserFormContainer) editUserFormContainer.style.display = 'none';
            }

            function showAddUserForm() {
                if (userTableCard) userTableCard.style.display = 'none';
                if (addUserFormContainer) addUserFormContainer.style.display = 'block';
                if (editUserFormContainer) editUserFormContainer.style.display = 'none';
                addUserForm?.reset(); // Reset form fields
                // Clear previous validation states
                addUserForm?.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            }

            function showEditUserForm() {
                if (userTableCard) userTableCard.style.display = 'none';
                if (addUserFormContainer) addUserFormContainer.style.display = 'none';
                if (editUserFormContainer) editUserFormContainer.style.display = 'block';
                 // Clear previous validation states
                 editUserForm?.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            }

            // --- Event Listeners ---
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-target');
                    let headingText = defaultHeading; // Default
                    switch(targetId) {
                        case 'user-management-content': headingText = 'User Management'; break;
                        case 'notifications-content': headingText = 'Notifications'; break;
                        case 'view-requisitions-content': headingText = 'View Submitted Requisitions'; break;
                        // Add other cases if needed
                    }
                    handleNavClick(targetId, headingText);
                });
            });

            // User Management - Add Button
             if (addUserBtn) {
                 addUserBtn.addEventListener('click', showAddUserForm);
             }

            // User Management - Edit Button (Event Delegation on Table Body)
             if (userTableBody) {
                userTableBody.addEventListener('click', function(event) {
                    const editButton = event.target.closest('.edit-user-btn');
                    if (editButton && editUserForm) { // Ensure form exists
                        event.preventDefault();
                        // Safely access dataset properties
                        const userName = editButton.dataset.name ?? '';
                        const userEmail = editButton.dataset.email ?? '';
                        const userRole = editButton.dataset.role ?? '';
                        const userDepartmentId = editButton.dataset.departmentId ?? '';
                        const formAction = editButton.dataset.action ?? '#';

                        // Populate the edit form fields using querySelector for robustness
                        const nameInput = editUserForm.querySelector('#edit-name');
                        const emailInput = editUserForm.querySelector('#edit-email');
                        const roleSelect = editUserForm.querySelector('#edit-role');
                        const departmentSelect = editUserForm.querySelector('#edit-department_id');
                        const passwordInput = editUserForm.querySelector('#edit-password');
                        const passwordConfirmInput = editUserForm.querySelector('#edit-password-confirmation');

                        if (nameInput) nameInput.value = userName;
                        if (emailInput) emailInput.value = userEmail;
                        if (roleSelect) roleSelect.value = userRole;
                        if (departmentSelect) departmentSelect.value = userDepartmentId; // Handles empty string correctly
                        if (passwordInput) passwordInput.value = ''; // Clear password fields
                        if (passwordConfirmInput) passwordConfirmInput.value = '';
                        editUserForm.action = formAction;

                        showEditUserForm(); // Show the populated edit form
                    }
                });
            }

             // User Management - Cancel Buttons (Add/Edit Forms)
             cancelUserFormBtns.forEach(button => {
                 button.addEventListener('click', showUserTable);
             });


            // --- Initial Page Load Logic ---
            let initialTargetId = defaultSectionId;
            let initialHeading = defaultHeading;
            let showUserFormOnError = null; // Tracks if 'add' or 'edit' form should show due to errors

            // Check for URL parameters (e.g., ?tab=users)
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
             if (tabParam) {
                 const potentialTargetId = tabParam + '-content'; // e.g., 'users-content'
                 if (document.getElementById(potentialTargetId)) {
                     initialTargetId = potentialTargetId;
                     // Update heading based on tab
                     switch(tabParam) {
                         case 'users': initialHeading = 'User Management'; break;
                         case 'requisitions': initialHeading = 'View Submitted Requisitions'; break;
                         case 'notifications': initialHeading = 'Notifications'; break;
                     }
                 }
             }

            // Check for validation errors to potentially override the initial view
            const validationErrors = document.querySelector('.alert-danger ul');
            if (validationErrors) {
                // Check if errors originated from Add User form
                if (addUserForm?.querySelector('.is-invalid') && userManagementSection) {
                    initialTargetId = 'user-management-content';
                    initialHeading = 'User Management';
                    showUserFormOnError = 'add';
                }
                // Check if errors originated from Edit User form
                else if (editUserForm?.querySelector('.is-invalid') && userManagementSection) {
                    initialTargetId = 'user-management-content';
                    initialHeading = 'User Management';
                    showUserFormOnError = 'edit';
                }
            }

            // Activate the determined initial section, link, and heading
            // Note: HTML already sets the default active classes, this JS handles overrides
            activateLink(initialTargetId);
            activateSection(initialTargetId);
            updateHeading(initialHeading);

            // If validation errors occurred in a user form, ensure that form is visible
            if (showUserFormOnError === 'add') {
                showAddUserForm();
            } else if (showUserFormOnError === 'edit') {
                 // We don't know *which* user failed validation here without more info,
                 // but we can ensure the edit form container itself is visible.
                 // The backend using old() should repopulate the fields correctly.
                 showEditUserForm();
            }
            // --- End Initial Page Load Logic ---

        }); // End DOMContentLoaded
    </script>
    {{-- End Scripts Section --}}

</x-app-layout>
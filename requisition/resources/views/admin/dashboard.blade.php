<x-app-layout> {{-- Ensure app.blade.php uses container-fluid or no container --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JNEC Requisition - Admin Portal</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        /* --- Base Layout Styles --- */
        :root { --sidebar-width: 260px; }
        body { display: flex; min-height: 100vh; background-color: #f8f9fa; }
        .sidebar { width: var(--sidebar-width); background-color:rgb(15, 101, 122); color:white; padding-top: 1rem; position: fixed; top: 0; bottom: 0; left: 0; z-index: 100; box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1); overflow-y: auto; display: flex; flex-direction: column; }
        .sidebar .nav-link { color:white; padding: 0.75rem 1.5rem; display: flex; align-items: center; font-size: 0.95rem; transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out; cursor: pointer; text-decoration: none; }
        .sidebar .nav-link .fa-fw { width: 1.2em; margin-right: 0.75rem; text-align: center; }
        .sidebar .nav-link:hover { color: #fff; background-color: #343a40; }
        .sidebar .nav-link.active { color: #fff; background-color: #0d6efd; font-weight: 500; }
        .sidebar-header { padding: 1rem 1.5rem; text-align: center; margin-bottom: 1rem; border-bottom: 1px solid #495057; }
        .sidebar-header img { max-height: 60px; max-width: 100%; object-fit: contain; }
        .sidebar-header h5 { margin-top: 0.5rem; font-weight: bold; color: #fff; }
        .sidebar-footer { margin-top: auto; padding: 1rem; text-align: center; color: rgba(255, 255, 255, 0.7); font-size: 0.85rem; border-top: 1px solid #495057; }
        .main-content { margin-left: var(--sidebar-width); padding: 0; width: calc(100% - var(--sidebar-width)); flex-grow: 1; display: flex; flex-direction: column; }
        .page-content { padding: 1.5rem; flex-grow: 1; overflow-y: auto; }

        /* --- Card Styles --- */
        .card { transition: background-color 0.2s ease; margin-bottom: 1.5rem; border: 1px solid #e3e6f0; box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15); border-radius: .35rem;}
        .card-header { padding: .75rem 1.25rem; margin-bottom: 0; background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0; }
        .card-body { padding: 1.25rem; }
        .card a.stretched-link { position: absolute; top: 0; right: 0; bottom: 0; left: 0; z-index: 1; pointer-events: auto; content: ""; background-color: rgba(0, 0, 0, 0); cursor: pointer; }
        .card:hover .card-body { background-color: #f8f9fc; }

        /* --- Content Section Visibility --- */
        .content-section { display: none; width: 100%; }
        #admin-dashboard-content { display: block; }
        .user-form-container { display: none; } /* Hide forms initially */
        .action-buttons form, .action-buttons .btn { margin: 0 2px; } /* Spacing for buttons */

        /* --- Responsive --- */
        @media (max-width: 768px) { :root { --sidebar-width: 0; } .sidebar { left: -260px; transition: left 0.3s ease-in-out; } .main-content { margin-left: 0; width: 100%; } .page-content { padding: 1rem; } }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <nav class="sidebar">
         <div class="sidebar-header"><img src="{{ asset('images/1.png') }}" alt="JNEC Logo"><h5 class="mt-3 mb-1">REQUISITION SYSTEM</h5></div>
        <ul class="nav flex-column flex-grow-1">
            <li class="nav-item"><a class="nav-link active" id="show-dashboard-link" href="#"><i class="fas fa-tachometer-alt fa-fw"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" id="show-requisitions-link" href="#"><i class="fas fa-list-check fa-fw"></i> View Requisitions</a></li>
            <li class="nav-item"><a class="nav-link" id="show-users-link" href="#"><i class="fas fa-users fa-fw"></i> User Management</a></li>
            <li class="nav-item"><a class="nav-link" id="show-notifications-link" href="#"><i class="fas fa-bell fa-fw"></i> Notification</a></li>
        </ul>
        <div class="sidebar-footer">© 2024 JNEC Admin</div>
    </nav>

    <!-- Main Content Area -->
    <div class="main-content">
        <main class="page-content">

            <!-- Page Heading -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                 <h1 class="h3 mb-0 text-gray-800" id="page-heading">Admin Dashboard</h1>
            </div>

             <!-- Session Messages & Validation Errors -->
             @if (session('success'))<div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>@endif
             @if (session('error'))<div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>@endif
             @if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif


            <!-- === START: Admin Dashboard Content === -->
            <div id="admin-dashboard-content" class="content-section">
                 <p class="lead mb-4">Manage requisitions, users, and system settings.</p>
                <div class="row"> <!-- Dashboard cards -->
                     <div class="col-xl-4 col-md-6 mb-4"> <div class="card border-left-info shadow h-100 py-2"> <div class="card-body"><div class="row g-0 align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-info text-uppercase mb-1">Requisitions</div><div class="h5 mb-0 font-weight-bold text-gray-800">View Submitted</div><p class="card-text small mt-2">Review forms.</p></div><div class="col-auto"><i class="fas fa-list-check fa-3x text-gray-300"></i></div></div><a href="#" id="show-requisitions-card" class="stretched-link"></a></div></div></div>
                    <div class="col-xl-4 col-md-6 mb-4"> <div class="card border-left-secondary shadow h-100 py-2"> <div class="card-body"><div class="row g-0 align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Users</div><div class="h5 mb-0 font-weight-bold text-gray-800">User Management</div><p class="card-text small mt-2">Manage accounts.</p></div><div class="col-auto"><i class="fas fa-users fa-3x text-gray-300"></i></div></div><a href="#" id="show-users-card" class="stretched-link"></a></div></div></div>
                    <div class="col-xl-4 col-md-6 mb-4"> <div class="card border-left-warning shadow h-100 py-2"> <div class="card-body"><div class="row g-0 align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Updates</div><div class="h5 mb-0 font-weight-bold text-gray-800">Notifications</div><p class="card-text small mt-2">View alerts.</p></div><div class="col-auto"><i class="fas fa-bell fa-3x text-gray-300"></i></div></div><a href="#" id="show-notifications-card" class="stretched-link"></a></div></div></div>
                </div>
            </div>
            <!-- === END: Admin Dashboard Content === -->

            <!-- === START: View Requisitions Content === -->
            <div id="view-requisitions-content" class="content-section">
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Submitted Requisitions</h6></div>
                    <div class="card-body"> <p>PLACEHOLDER - Requisitions Table</p> </div>
                </div>
            </div>
            <!-- === END: View Requisitions Content === -->

            <!-- === START: User Management Content === -->
            <div id="user-management-content" class="content-section">

                {{-- 1. User Table Card --}}
                <div class="card shadow mb-4" id="user-table-card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">System Users</h6>
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
                                          {{-- Status Column Header REMOVED --}}
                                          <th>Actions</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @forelse ($users as $user)
                                      <tr>
                                          <td>{{ $user->id }}</td>
                                          <td>{{ $user->name }}</td>
                                          <td>{{ $user->email }}</td>
                                          <td>{{ $user->role }}</td>
                                          {{-- Status Data Cell REMOVED --}}
                                          <td class="action-buttons d-flex flex-nowrap">
                                              {{-- Edit Button: Added data-department-id --}}
                                              <button class="btn btn-sm btn-warning edit-user-btn me-1"
                                                      title="Edit"
                                                      data-id="{{ $user->id }}"
                                                      data-name="{{ $user->name }}"
                                                      data-email="{{ $user->email }}"
                                                      data-role="{{ $user->role }}"
                                                      data-department-id="{{ $user->department_id }}" {{-- +++ Added +++ --}}
                                                      data-action="{{ route('admin.users.update', $user->id) }}">
                                                  <i class="fas fa-edit"></i>
                                              </button>

                                              {{-- Delete Button Form --}}
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
                                      <tr>
                                          <td colspan="5" class="text-center">No users found.</td>
                                      </tr>
                                      @endforelse
                                  </tbody>
                              </table>
                          </div>
                    </div>
                </div>

                {{-- 2. Add User Form Container --}}
                <div class="card shadow mb-4 user-form-container" id="add-user-form-container">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Add New User</h6></div>
                    <div class="card-body">
                        <form action="{{ route('admin.users.store') }}" method="POST">
                            @csrf
                            <div class="mb-3"><label for="add-name" class="form-label">Name <span class="text-danger">*</span></label><input type="text" class="form-control @error('name') is-invalid @enderror" id="add-name" name="name" value="{{ old('name') }}" required>@error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                            <div class="mb-3"><label for="add-email" class="form-label">Email <span class="text-danger">*</span></label><input type="email" class="form-control @error('email') is-invalid @enderror" id="add-email" name="email" value="{{ old('email') }}" required>@error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                            <div class="mb-3"><label for="add-role" class="form-label">Role <span class="text-danger">*</span></label><select class="form-select @error('role') is-invalid @enderror" id="add-role" name="role" required><option value="" disabled {{ old('role') ? '' : 'selected' }}>Select Role...</option><option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option><option value="LRC" {{ old('role') == 'LRC' ? 'selected' : '' }}>LRC</option><option value="HOD" {{ old('role') == 'HOD' ? 'selected' : '' }}>HOD</option></select>@error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                            {{-- +++ START: Add Department Dropdown +++ --}}
                            <div class="mb-3">
                                <label for="add-department_id" class="form-label">Department (Optional)</label>
                                <select class="form-select @error('department_id') is-invalid @enderror" id="add-department_id" name="department_id">
                                    <option value="">-- Select Department --</option>
                                    @isset($departments) {{-- Check if $departments exists --}}
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                                @error('department_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            {{-- +++ END: Add Department Dropdown +++ --}}
                            <div class="row"><div class="col-md-6 mb-3"><label for="add-password" class="form-label">Password <span class="text-danger">*</span></label><input type="password" class="form-control @error('password') is-invalid @enderror" id="add-password" name="password" required>@error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror</div><div class="col-md-6 mb-3"><label for="add-password-confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label><input type="password" class="form-control" id="add-password-confirmation" name="password_confirmation" required></div></div>
                            <div class="d-flex justify-content-end"><button type="button" class="btn btn-secondary me-2 cancel-user-form-btn">Cancel</button><button type="submit" class="btn btn-primary">Add User</button></div>
                        </form>
                    </div>
                </div>

                 {{-- 3. Edit User Form Container --}}
                <div class="card shadow mb-4 user-form-container" id="edit-user-form-container">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Edit User</h6></div>
                    <div class="card-body">
                        <form action="" method="POST" id="edit-user-form">
                            @csrf
                            @method('PUT')
                            <div class="mb-3"><label for="edit-name" class="form-label">Name <span class="text-danger">*</span></label><input type="text" class="form-control @error('name', 'update') is-invalid @enderror" id="edit-name" name="name" value="{{ old('name') }}" required>@error('name', 'update') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                            <div class="mb-3"><label for="edit-email" class="form-label">Email <span class="text-danger">*</span></label><input type="email" class="form-control @error('email', 'update') is-invalid @enderror" id="edit-email" name="email" value="{{ old('email') }}" required>@error('email', 'update') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                            <div class="mb-3"><label for="edit-role" class="form-label">Role <span class="text-danger">*</span></label><select class="form-select @error('role', 'update') is-invalid @enderror" id="edit-role" name="role" required><option value="" disabled>Select Role...</option><option value="Admin">Admin</option><option value="LRC">LRC</option><option value="HOD">HOD</option></select>@error('role', 'update') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                            {{-- +++ START: Edit Department Dropdown +++ --}}
                            <div class="mb-3">
                                <label for="edit-department_id" class="form-label">Department (Optional)</label>
                                <select class="form-select @error('department_id', 'update') is-invalid @enderror" id="edit-department_id" name="department_id">
                                    <option value="">-- Select Department --</option>
                                     @isset($departments)
                                        @foreach ($departments as $department)
                                            {{-- JS will set the selected attribute based on user data --}}
                                            <option value="{{ $department->id }}">
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                     @endisset
                                </select>
                                @error('department_id', 'update') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            {{-- +++ END: Edit Department Dropdown +++ --}}
                            <div class="mb-3"><label for="edit-password" class="form-label">New Password (Optional)</label><input type="password" class="form-control @error('password', 'update') is-invalid @enderror" id="edit-password" name="password" placeholder="Leave blank to keep current password">@error('password', 'update') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                            <div class="mb-3"><label for="edit-password-confirmation" class="form-label">Confirm New Password</label><input type="password" class="form-control" id="edit-password-confirmation" name="password_confirmation"></div>
                            <div class="d-flex justify-content-end"><button type="button" class="btn btn-secondary me-2 cancel-user-form-btn">Cancel</button><button type="submit" class="btn btn-primary">Update User</button></div>
                        </form>
                    </div>
                </div>

            </div>
            <!-- === END: User Management Content === -->


            <!-- === START: Notifications Content === -->
            <div id="notifications-content" class="content-section">
                 <div class="card shadow mb-4">
                     <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Notifications</h6></div>
                     <div class="card-body"> <p>PLACEHOLDER - Notifications List</p> </div>
                 </div>
            </div>
            <!-- === END: Notifications Content === -->

        </main>
    </div> <!-- End Main Content -->

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const contentSections = document.querySelectorAll('.content-section');
            const pageHeading = document.getElementById('page-heading');
            const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
            const triggerLinks = { dashboard: [document.getElementById('show-dashboard-link')], requisitions: [document.getElementById('show-requisitions-link'), document.getElementById('show-requisitions-card')], users: [document.getElementById('show-users-link'), document.getElementById('show-users-card')], notifications: [document.getElementById('show-notifications-link'), document.getElementById('show-notifications-card')] };
            const userManagementSection = document.getElementById('user-management-content');
            const userTableCard = document.getElementById('user-table-card');
            const addUserBtn = document.getElementById('add-user-btn');
            const addUserFormContainer = document.getElementById('add-user-form-container');
            const editUserFormContainer = document.getElementById('edit-user-form-container');
            const editUserForm = document.getElementById('edit-user-form');
            const cancelUserFormBtns = document.querySelectorAll('.cancel-user-form-btn');
            const userTableBody = document.getElementById('userTable')?.querySelector('tbody');

            function showUserTable() { if (userTableCard) userTableCard.style.display = 'block'; if (addUserFormContainer) addUserFormContainer.style.display = 'none'; if (editUserFormContainer) editUserFormContainer.style.display = 'none'; }
            function showAddUserForm() { if (userTableCard) userTableCard.style.display = 'none'; if (addUserFormContainer) addUserFormContainer.style.display = 'block'; if (editUserFormContainer) editUserFormContainer.style.display = 'none'; addUserFormContainer.querySelector('form')?.reset(); }
            function showEditUserForm() { if (userTableCard) userTableCard.style.display = 'none'; if (addUserFormContainer) addUserFormContainer.style.display = 'none'; if (editUserFormContainer) editUserFormContainer.style.display = 'block'; }
            function hideAllSections() { contentSections.forEach(s => s && (s.style.display = 'none')); }
            function setActiveSidebarLink(targetId) { sidebarLinks.forEach(l => l && l.classList.remove('active')); const activeLink = document.getElementById(targetId); if (activeLink) activeLink.classList.add('active'); }
            function showSection(sectionId, headingText, activeLinkId) { hideAllSections(); const sectionToShow = document.getElementById(sectionId); if (sectionToShow) { sectionToShow.style.display = 'block'; if (sectionId === 'user-management-content') { showUserTable(); } } if (pageHeading) pageHeading.textContent = headingText; setActiveSidebarLink(activeLinkId); }

            for (const sectionKey in triggerLinks) { triggerLinks[sectionKey].forEach(link => { if (link) { link.addEventListener('click', (e) => { e.preventDefault(); let sectionId = '', headingText = '', activeLinkId = ''; switch (sectionKey) { case 'dashboard': sectionId = 'admin-dashboard-content'; headingText = 'Admin Dashboard'; activeLinkId = 'show-dashboard-link'; break; case 'requisitions': sectionId = 'view-requisitions-content'; headingText = 'View Submitted Requisitions'; activeLinkId = 'show-requisitions-link'; break; case 'users': sectionId = 'user-management-content'; headingText = 'User Management'; activeLinkId = 'show-users-link'; break; case 'notifications': sectionId = 'notifications-content'; headingText = 'Notifications'; activeLinkId = 'show-notifications-link'; break; } if (sectionId) showSection(sectionId, headingText, activeLinkId); }); } }); }
            if (addUserBtn) { addUserBtn.addEventListener('click', function() { showAddUserForm(); }); }

            // --- MODIFIED: Event Listener for Edit Button ---
             if (userTableBody) {
                userTableBody.addEventListener('click', function(event) {
                    const editButton = event.target.closest('.edit-user-btn');
                    if (editButton) {
                        event.preventDefault();
                        const userName = editButton.dataset.name;
                        const userEmail = editButton.dataset.email;
                        const userRole = editButton.dataset.role;
                        const userDepartmentId = editButton.dataset.departmentId; // +++ Get department ID +++
                        const formAction = editButton.dataset.action;

                        // Populate the edit form fields
                        document.getElementById('edit-name').value = userName || '';
                        document.getElementById('edit-email').value = userEmail || '';
                        document.getElementById('edit-role').value = userRole || '';
                        document.getElementById('edit-department_id').value = userDepartmentId || ''; // +++ Set department dropdown +++
                        document.getElementById('edit-password').value = '';
                        document.getElementById('edit-password-confirmation').value = '';
                        if(editUserForm) { editUserForm.action = formAction || '#'; }

                        showEditUserForm(); // Show the populated edit form
                    }
                });
            }
            // --- END MODIFIED: Event Listener for Edit Button ---

            cancelUserFormBtns.forEach(button => { button.addEventListener('click', function() { showUserTable(); }); });
            const urlParams = new URLSearchParams(window.location.search); const tab = urlParams.get('tab'); const errors = document.querySelector('.alert-danger ul'); if (tab === 'users' || (errors && userManagementSection && userManagementSection.contains(errors))) { showSection('user-management-content', 'User Management', 'show-users-link'); } else { showSection('admin-dashboard-content', 'Admin Dashboard', 'show-dashboard-link'); }
        });
    </script>

</body>
</html>
</x-app-layout>
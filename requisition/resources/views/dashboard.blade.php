{{-- resources/views/dashboard.blade.php --}}

<x-app-layout>
    {{-- Header Slot Removed (Previously contained "User Dashboard" title) --}}

    {{-- Main content starts here, previously within @section('content') --}}

    {{-- ====================================================================== --}}
    {{-- == USER DASHBOARD - Requisition Form / Status / Notifications Layout == --}}
    {{-- ====================================================================== --}}

    {{-- FontAwesome CSS is already included in layouts/app.blade.php head (or x-app-layout) --}}

    {{-- Embedded Styles for the Custom Dashboard Layout --}}
    <style>
        /* === Dashboard Container === */
        .dashboard-container { display: flex; width: 100%; max-width: 100%; min-height: calc(100vh - 0rem); }
        /* === Sidebar === */
        .sidebar { width: 250px; background-color: rgb(15, 101, 122); color: #fff; padding: 20px 15px; display: flex; flex-direction: column; flex-shrink: 0; overflow-y: auto; }
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
    </style>

    {{-- Styles SPECIFICALLY for the Requisition Form --}}
    <style>
         #requisition-form h1, #requisition-form h2, #requisition-form h4 { color: #333; margin-bottom: 0.5em;}
         #requisition-form p { margin-bottom: 1em; }
         #requisition-form .form-header-jnec { text-align: center; margin-bottom: 20px; }
         #requisition-form .form-header-jnec h2 { font-size: 1.4em; margin-bottom: 0.2em; }
         #requisition-form .form-header-jnec p { font-size: 0.9em; margin-bottom: 0.5em; color: #555; }
         #requisition-form .form-header-jnec h1 { font-size: 1.6em; text-transform: uppercase; margin-bottom: 1em; color: #000; font-weight: bold; border-bottom: 2px solid #333; padding-bottom: 5px; display: inline-block; }
         .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px; }
         .form-grid label, .requester-grid label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
         .form-grid input[type="text"], .form-grid input[type="date"], .form-grid select, .requester-grid input[type="text"], .requester-grid select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 1rem; background-color: #fff; }
         .requester-grid { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
         .table-responsive { overflow-x: auto; margin-bottom: 10px;}
         /* General Table Styles */
         .table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; background-color: transparent; }
         .table th, .table td { padding: .75rem; vertical-align: middle; border-top: 1px solid #dee2e6; }
         .table thead th { vertical-align: bottom; border-bottom: 2px solid #dee2e6; background-color: #e9ecef; text-align: left; font-weight: bold; }
         .table tbody + tbody { border-top: 2px solid #dee2e6; }
         .table-striped tbody tr:nth-of-type(odd) { background-color: rgba(0,0,0,.05); }
         .table-hover tbody tr:hover { color: #212529; background-color: rgba(0,0,0,.075); }
         /* Requisition Items Table specific styles */
         #requisition-items-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
         #requisition-items-table th, #requisition-items-table td { border: 1px solid #ccc; padding: 8px; text-align: left; vertical-align: top;}
         #requisition-items-table thead th { background-color: #f2f2f2; font-weight: bold; white-space: nowrap; vertical-align: middle; text-align: center;}
         #requisition-items-table th.th-checkbox-delete { width: 40px; padding: 8px 5px; }
         #requisition-items-table td.td-checkbox-delete { width: 40px; text-align: center; vertical-align: middle; padding: 6px 5px; }
         #requisition-items-table .row-checkbox { cursor: pointer; width: 16px; height: 16px; margin: 0 auto; display: block; }
         #requisition-items-table th.sl-header { width: 50px; }
         #requisition-items-table td.sl-cell { text-align: center; vertical-align: middle; font-weight: bold; padding: 6px 5px; }
         #requisition-items-table td .item-input { width: 100%; padding: 6px; border: 1px solid #dcdcdc; border-radius: 3px; box-sizing: border-box; font-size: 0.95rem; background-color: #fff; transition: border-color 0.2s ease, box-shadow 0.2s ease; }
         #requisition-items-table td .item-input:focus { border-color: #007bff; box-shadow: 0 0 0 1px rgba(0, 123, 255, 0.25); outline: none; }
         #requisition-items-table td textarea.item-input { resize: vertical; min-height: 40px; }
         .table-actions { display: flex; justify-content: flex-end; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
         .action-btn { padding: 8px 15px; cursor: pointer; border-radius: 4px; border: 1px solid; font-size: 0.95em; white-space: nowrap; transition: background-color 0.2s ease, border-color 0.2s ease, opacity 0.2s ease; }
         .add-row-btn { background-color: #28a745; color: white; border-color: #28a745; margin-left: auto; }
         .add-row-btn:hover { background-color: #218838; }
         .delete-mode-btn { background-color: #dc3545; color: white; border-color: #dc3545; }
         .delete-mode-btn:hover { background-color: #c82333; }
         .confirm-delete-btn { background-color: #ffc107; color: #333; border-color: #ffc107; }
         .confirm-delete-btn:hover { background-color: #e0a800; }
         .cancel-delete-btn { background-color: #6c757d; color: white; border-color: #6c757d; }
         .cancel-delete-btn:hover { background-color: #5a6268; }
         .action-btn:disabled { background-color: #cccccc; border-color: #cccccc; color: #666; cursor: not-allowed; opacity: 0.6; }
         .form-section { margin-top: 25px; padding-top: 15px; border-top: 1px solid #eee; }
         .form-section h4 { margin-bottom: 15px; }
         .form-submit-actions { text-align: center; margin-top: 30px; display: flex; justify-content: center; align-items: center; gap: 15px; flex-wrap: wrap; }
         .submit-button { background-color: #007bff; color: white; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-size: 1.1em; transition: background-color 0.3s ease; order: 2; }
         .submit-button:hover { background-color: #0056b3; }
         .back-button { background-color: #6c757d; color: white; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-size: 1.1em; transition: background-color 0.3s ease; order: 1; }
         .back-button:hover { background-color: #5a6268; }
         /* Styling for Status Badges & Buttons */
         .badge { display: inline-block; padding: .35em .65em; font-size: .75em; font-weight: 700; line-height: 1; color: #fff; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; }
         .bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
         .bg-success { background-color: #198754 !important; }
         .bg-danger { background-color: #dc3545 !important; }
         .bg-info { background-color: #0dcaf0 !important; color: #000 !important; }
         .bg-secondary { background-color: #6c757d !important; }
         .bg-primary { background-color: #0d6efd !important; }
         /* Basic Button Styles */
        .btn { display: inline-block; font-weight: 400; color: #212529; text-align: center; vertical-align: middle; cursor: pointer; user-select: none; background-color: transparent; border: 1px solid transparent; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out; }
        .btn-sm { padding: .25rem .5rem; font-size: .875rem; line-height: 1.5; border-radius: .2rem; }
        .btn-info { color: #fff; background-color: #17a2b8; border-color: #17a2b8; }
        .btn-info:hover { color: #fff; background-color: #138496; border-color: #117a8b; }
        a.btn { text-decoration: none; }

         /* Responsive styles for form elements */
         @media (max-width: 768px) { .table-responsive { margin-bottom: 10px; } #requisition-items-table th, #requisition-items-table td { padding: 6px; } #requisition-items-table th.th-checkbox-delete, #requisition-items-table td.td-checkbox-delete { width: 35px; padding: 6px 4px;} #requisition-items-table th.sl-header { width: auto; } #requisition-items-table td .item-input { padding: 5px; font-size: 0.9rem; } .table-actions { justify-content: flex-start; flex-direction: column; align-items: stretch; } .action-btn { width: 100%; margin-left: 0 !important; box-sizing: border-box; text-align: center; } .add-row-btn { order: 3; } .delete-mode-btn { order: 1; } .confirm-delete-btn { order: 1; } .cancel-delete-btn { order: 2; } .form-grid, .requester-grid { grid-template-columns: 1fr; gap: 15px; } .form-submit-actions { flex-direction: column; align-items: stretch; } .submit-button, .back-button { width: 100%; order: 0; } .back-button { margin-bottom: 10px; } }
    </style>

    {{-- Main Dashboard Container (Sidebar + Content) --}}
    <div class="dashboard-container">

        {{-- Sidebar Navigation --}}
        <nav class="sidebar">
             <h2>Dashboard</h2>
             <ul>
                 {{-- Initial active state based on errors or default --}}
                 <li><a href="#" data-target="requisition-form" class="nav-link {{ $errors->any() ? 'active' : (request()->routeIs('dashboard') && !$errors->any() ? 'active' : '') }}"><i class="fas fa-fw fa-file-alt"></i> Requisition Form</a></li>
                 <li><a href="#" data-target="form-status" class="nav-link"><i class="fas fa-fw fa-clipboard-list"></i> Form Status</a></li>
                 <li><a href="#" data-target="notification-content" class="nav-link"><i class="fas fa-fw fa-bell"></i> Notification</a></li>
             </ul>
         </nav>

        {{-- Main Content Area --}}
        <main class="content-area">

            {{-- Display Session Feedback & Validation Errors --}}
            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
            {{-- Display Validation Errors from Form Submission --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops! Please correct the errors below:</strong>
                    <ul style="margin-top: 10px; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Section 1: Requisition Form --}}
            <div id="requisition-form" class="content-section {{ $errors->any() ? 'active' : (request()->routeIs('dashboard') && !$errors->any() ? 'active' : '') }}"> {{-- Default active --}}
                <!-- Form Header -->
                <div class="form-header-jnec"><h2>Jigme Namgyel Engineering College</h2><p>DEOTHANG, SAMDRUP JONGKHAR, BHUTAN</p><h1>REQUISITION FORM</h1></div>
                {{-- Form --}}
                <form action="{{ route('user.requisitions.store') }}" method="POST" id="new-requisition">
                @csrf {{-- CSRF Protection Token --}}
                    <div class="form-grid">
                        <div>
                            <label for="req-date">Date:</label>
                            <input type="date" id="req-date" name="requisition_date" required value="{{ old('requisition_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label for="req-department-id">Department:</label>
                            {{-- =========================================== --}}
                            {{-- == CORRECTED DEPARTMENT SELECT DROPDOWN === --}}
                            {{-- =========================================== --}}
                            <select id="req-department-id" name="department_id" required>
                                <option value="" disabled {{ old('department_id') ? '' : 'selected' }}>-- Select Department --</option>
                                {{-- Verify these IDs match your 'departments' table or user table IDs --}}
                                <option value="1" {{ old('department_id') == '1' ? 'selected' : '' }}>Department of Information Technology</option>
                                <option value="2" {{ old('department_id') == '2' ? 'selected' : '' }}>Department of Civil & Surveying Engineering</option> {{-- Assuming ID 2 = Civil --}}
                                <option value="3" {{ old('department_id') == '3' ? 'selected' : '' }}>Department of Electrical & Electronics Engineering</option> {{-- Assuming ID 3 = EEE --}}
                                <option value="4" {{ old('department_id') == '4' ? 'selected' : '' }}>Department of Mechanical Engineering</option> {{-- CORRECTED: DME has value="4" --}}
                                <option value="5" {{ old('department_id') == '5' ? 'selected' : '' }}>Department of Humanities & Management</option> {{-- CORRECTED: Assuming H&M/DMPM has value="5" --}}
                                <option value="6" {{ old('department_id') == '6' ? 'selected' : '' }}>Administration</option> {{-- Assuming ID 6 = Admin --}}
                                <option value="7" {{ old('department_id') == '7' ? 'selected' : '' }}>Examination Cell</option> {{-- Assuming ID 7 = Exam Cell --}}
                                <option value="8" {{ old('department_id') == '8' ? 'selected' : '' }}>Games and Sports</option> {{-- Assuming ID 8 = Games --}}
                                <option value="9" {{ old('department_id') == '9' ? 'selected' : '' }}>Central Library</option> {{-- Assuming ID 9 = Library --}}
                                <option value="10" {{ old('department_id') == '10' ? 'selected' : '' }}>Learning Resource centre (LRC)</option> {{-- Assuming ID 10 = LRC --}}
                                <option value="11" {{ old('department_id') == '11' ? 'selected' : '' }}>Estate & Maintenance</option> {{-- Assuming ID 11 = Estate --}}
                                <option value="12" {{ old('department_id') == '12' ? 'selected' : '' }}>Accounts Section</option> {{-- Assuming ID 12 = Accounts --}}
                                <option value="13" {{ old('department_id') == '13' ? 'selected' : '' }}>Student Affairs</option> {{-- Assuming ID 13 = DSA --}}
                                <option value="14" {{ old('department_id') == '14' ? 'selected' : '' }}>Research & Innovation</option> {{-- Assuming ID 14 = Research --}}
                                <option value="99" {{ old('department_id') == '99' ? 'selected' : '' }}>Others</option> {{-- Consistent ID for 'Others' --}}
                            </select>
                            @error('department_id')
                                <span class="text-danger text-sm" style="color:red;">{{ $message }}</span> {{-- Added inline style for visibility --}}
                            @enderror
                            {{-- =========================================== --}}
                            {{-- ===== END DEPARTMENT SELECT CORRECTION ==== --}}
                            {{-- =========================================== --}}
                        </div>
                        <div style="grid-column: 1 / -1;"><label>To: The President</label></div>
                    </div>
                    {{-- ... rest of your form ... --}}
                    <p style="margin-top:15px; margin-bottom: 15px;">Sir, Kindly arrange to supply the following items:</p>
                    <!-- Items Table -->
                    <div class="table-responsive">
                        <table id="requisition-items-table">
                            <thead>
                                <tr>
                                    <th class="th-checkbox-delete"></th>
                                    <th class="sl-header">Sl. No.</th>
                                    <th>Item Name <span style="color:red;">*</span></th>
                                    <th>Description / Specs</th>
                                    <th>Quantity <span style="color:red;">*</span></th>
                                    <th>Remarks / Purpose</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Handle old input for items if validation fails --}}
                                @if(is_array(old('item_name')) && count(old('item_name')) > 0)
                                    @foreach(old('item_name') as $index => $itemName)
                                    <tr>
                                        <td class="td-checkbox-delete"></td>
                                        <td class="sl-cell"><span class="sl-no">{{ $index + 1 }}</span></td>
                                        <td><input type="text" class="item-input" name="item_name[]" placeholder="Enter item name" required value="{{ $itemName }}"></td>
                                        <td><textarea class="item-input" name="item_description[]" placeholder="Enter description/specs" rows="2">{{ old('item_description')[$index] ?? '' }}</textarea></td>
                                        <td><input type="number" min="1" step="1" class="item-input" name="item_quantity[]" placeholder="e.g., 5" required value="{{ old('item_quantity')[$index] ?? '' }}"></td>
                                        <td><textarea class="item-input" name="item_remarks[]" placeholder="Enter purpose/remarks" rows="2">{{ old('item_remarks')[$index] ?? '' }}</textarea></td>
                                    </tr>
                                    @endforeach
                                @else
                                    {{-- Default first row --}}
                                    <tr>
                                        <td class="td-checkbox-delete"></td>
                                        <td class="sl-cell"><span class="sl-no">1</span></td>
                                        <td><input type="text" class="item-input" name="item_name[]" placeholder="Enter item name" required></td>
                                        <td><textarea class="item-input" name="item_description[]" placeholder="Enter description/specs" rows="2"></textarea></td>
                                        <td><input type="number" min="1" step="1" class="item-input" name="item_quantity[]" placeholder="e.g., 5" required></td>
                                        <td><textarea class="item-input" name="item_remarks[]" placeholder="Enter purpose/remarks" rows="2"></textarea></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- Action Buttons -->
                    <div class="table-actions">
                        <button type="button" id="delete-mode-btn" class="action-btn delete-mode-btn" onclick="toggleDeleteMode()">Delete Items</button>
                        <button type="button" id="confirm-delete-btn" class="action-btn confirm-delete-btn" onclick="performBulkDelete()" style="display: none;">Confirm Delete Selected</button>
                        <button type="button" id="cancel-delete-btn" class="action-btn cancel-delete-btn" onclick="toggleDeleteMode(true)" style="display: none;">Cancel</button>
                        <button type="button" id="add-row-btn" class="action-btn add-row-btn" onclick="addItemRow()">+ Add Item Row</button>
                    </div>
                    <!-- Requester Info -->
                    <div class="form-section">
                        <h4>Requested By (Indenter):</h4>
                        <div class="form-grid requester-grid">
                            <div>
                                <label for="requester-name">Name:</label>
                                <input type="text" id="requester-name" name="requester_name" placeholder="Your Name" required value="{{ old('requester_name', Auth::user()->name ?? '') }}">
                            </div>
                            <div>
                                <label for="requester-designation">Designation:</label>
                                {{-- Ensure these values match your intended mapping (e.g., in Requisition model accessor) --}}
                                <select id="requester-designation" name="requester_designation" required>
                                    <option value="" disabled {{ old('requester_designation') ? '' : 'selected' }}>-- Select Designation --</option>
                                    {{-- Use the same integer values as defined in your Requisition Model Accessor --}}
                                    <option value="1" {{ old('requester_designation') == '1' ? 'selected' : '' }}>President</option>
                                    <option value="2" {{ old('requester_designation') == '2' ? 'selected' : '' }}>Dean</option>
                                    <option value="3" {{ old('requester_designation') == '3' ? 'selected' : '' }}>Head of Department (HOD)</option>
                                    <option value="4" {{ old('requester_designation') == '4' ? 'selected' : '' }}>Lecturer</option>
                                    <option value="5" {{ old('requester_designation') == '5' ? 'selected' : '' }}>Associate Lecturer</option>
                                    <option value="6" {{ old('requester_designation') == '6' ? 'selected' : '' }}>Assistant Lecturer</option>
                                    <option value="7" {{ old('requester_designation') == '7' ? 'selected' : '' }}>Lab Technician/Assistant</option>
                                    <option value="8" {{ old('requester_designation') == '8' ? 'selected' : '' }}>Librarian/Assistant</option>
                                    <option value="9" {{ old('requester_designation') == '9' ? 'selected' : '' }}>Admin Officer</option>
                                    <option value="10" {{ old('requester_designation') == '10' ? 'selected' : '' }}>Accounts Officer/Assistant</option>
                                    <option value="11" {{ old('requester_designation') == '11' ? 'selected' : '' }}>Store Keeper/Assistant</option>
                                    <option value="12" {{ old('requester_designation') == '12' ? 'selected' : '' }}>Estate Manager</option>
                                    <option value="13" {{ old('requester_designation') == '13' ? 'selected' : '' }}>Dean Student Affairs (DSA)</option>
                                    <option value="14" {{ old('requester_designation') == '14' ? 'selected' : '' }}>LRC</option>
                                    <option value="15" {{ old('requester_designation') == '15' ? 'selected' : '' }}>Technician</option>
                                    <option value="16" {{ old('requester_designation') == '16' ? 'selected' : '' }}>General Staff</option>
                                    <option value="99" {{ old('requester_designation') == '99' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Submit/Back Buttons -->
                    <div class="form-submit-actions">
                        <button type="button" class="back-button" onclick="goBack()">Back</button>
                        <button type="submit" class="submit-button">Submit Requisition</button>
                    </div>
                </form> {{-- End Form --}}
            </div> {{-- End #requisition-form --}}

            {{-- ===================================================== --}}
            {{-- == Section 2: Form Status (USING SINGLE ENUM STATUS) === --}}
            {{-- ===================================================== --}}
            <div id="form-status" class="content-section">
                <h2>My Requisition Status</h2>
                @isset($requisitions) {{-- Check if $requisitions exists --}}
                    @if($requisitions->isEmpty())
                        <div class="alert alert-info" role="alert">
                             You haven't submitted any requisitions yet. Status updates will appear here once you submit a form.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Req. Date</th>
                                        <th>Department</th>
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
                                            {{-- Assuming you have a 'department' relationship defined in Requisition model --}}
                                            <td>{{ $requisition->department->name ?? 'Dept ID: ' . $requisition->department_id }}</td> {{-- Fallback to ID if name missing --}}
                                            <td>{{ $requisition->requester_name ?? 'N/A' }}</td>
                                            <td>{{ $requisition->designation_text ?? ($requisition->requester_designation ?? 'N/A') }}</td> {{-- Use accessor --}}
                                            <td>
                                                {{-- Use accessor for badge class --}}
                                                <span class="badge {{ $requisition->status_badge_class ?? 'bg-secondary' }}">
                                                    {{ $requisition->status ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>{{ optional($requisition->created_at)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                            <td>
                                                {{-- Ensure the route name 'user.requisitions.show' is correct --}}
                                                <a href="{{ route('user.requisitions.show', $requisition->id) }}" class="btn btn-sm btn-info">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- Pagination Links (check if $requisitions is a Paginator instance) --}}
                        @if($requisitions instanceof \Illuminate\Pagination\LengthAwarePaginator && $requisitions->hasPages())
                            <div style="margin-top: 20px;">
                                {{ $requisitions->links() }}
                            </div>
                        @endif
                    @endif
                @else
                    <div class="alert alert-warning" role="alert">
                         Could not load your requisition status data. Check controller.
                    </div>
                @endisset
            </div> {{-- End #form-status --}}


            {{-- =================================================== --}}
            {{-- Section 3: Notification --}}
            {{-- =================================================== --}}
            <div id="notification-content" class="content-section">
                <h2>Notifications</h2>
                <p>Important notifications relevant to you will be displayed here.</p>
                 <div class="alert alert-info">No new notifications at this time.</div>
                 {{-- TODO: Fetch and display actual notifications later --}}
            </div>

        </main> {{-- End Content Area --}}

    </div> {{-- End Dashboard Container --}}

    {{-- JavaScript for Tab Switching --}}
    <script>
        // Tab switching logic remains the same...
        document.addEventListener('DOMContentLoaded', () => {
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            const sections = document.querySelectorAll('.content-area .content-section');
            const requisitionFormSection = document.getElementById('requisition-form');

            function activateSection(targetId) { sections.forEach(section => { section.id === targetId ? section.classList.add('active') : section.classList.remove('active'); }); }
            function activateLink(clickedLink) { navLinks.forEach(link => link.classList.remove('active')); if (clickedLink) clickedLink.classList.add('active'); }

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
                    } else { console.warn(`Target section '${targetId}' not found.`); }
                });
            });

            let initialTargetId = null;
            let initialActiveLink = null;
            const currentHash = window.location.hash.substring(1);
            const hasValidationErrors = {{ $errors->any() ? 'true' : 'false' }};

            if (currentHash && document.getElementById(currentHash)) {
                initialTargetId = currentHash;
                initialActiveLink = document.querySelector(`.sidebar .nav-link[data-target="${currentHash}"]`);
            } else if (hasValidationErrors && requisitionFormSection) {
                initialTargetId = 'requisition-form';
                initialActiveLink = document.querySelector(`.sidebar .nav-link[data-target="${initialTargetId}"]`);
            } else if (navLinks.length > 0 && sections.length > 0) {
                const defaultLink = navLinks[0];
                const defaultTargetId = defaultLink.getAttribute('data-target');
                if (defaultTargetId && document.getElementById(defaultTargetId)) {
                    initialTargetId = defaultTargetId;
                    initialActiveLink = defaultLink;
                }
            }

            if (initialTargetId && document.getElementById(initialTargetId)) {
                activateSection(initialTargetId);
                if (initialActiveLink && !initialActiveLink.classList.contains('active')) {
                    activateLink(initialActiveLink);
                }
            } else if (sections.length > 0) {
                 sections[0].classList.add('active');
                 if(navLinks.length > 0 && !navLinks[0].classList.contains('active')) { activateLink(navLinks[0]); }
            }
        });
    </script>

    {{-- JavaScript SPECIFICALLY for the Requisition Form (Add/Delete Row Logic) --}}
    <script>
        // Add/Delete row logic remains the same...
        document.addEventListener('DOMContentLoaded', function() {
            const requisitionForm = document.getElementById('new-requisition');
            if (!requisitionForm) { return; }
            const tableBody = document.getElementById('requisition-items-table')?.getElementsByTagName('tbody')[0];
            const tableHead = document.getElementById('requisition-items-table')?.getElementsByTagName('thead')[0];
            const checkboxHeaderCell = tableHead?.querySelector('.th-checkbox-delete');
            const deleteModeBtn = document.getElementById('delete-mode-btn');
            const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
            const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
            const addRowBtn = document.getElementById('add-row-btn');
            let isInDeleteMode = false;
            if (!tableBody || !tableHead || !checkboxHeaderCell || !deleteModeBtn || !confirmDeleteBtn || !cancelDeleteBtn || !addRowBtn) {
                console.warn("One or more requisition form elements not found. Add/Delete JS functionality might be affected.");
                if(deleteModeBtn) deleteModeBtn.disabled = true;
                if(addRowBtn) addRowBtn.disabled = true;
            }
            window.goBack = function() { history.back(); }
            window.toggleDeleteMode = function(isCancelling = false) {
                if (!tableBody || !checkboxHeaderCell || !deleteModeBtn || !confirmDeleteBtn || !cancelDeleteBtn || !addRowBtn) return;
                const allRows = tableBody.getElementsByTagName('tr');
                if (!isInDeleteMode && allRows.length === 0 && !isCancelling) { alert("There are no items to delete."); return; }
                const enteringDeleteMode = !isInDeleteMode && !isCancelling;
                const exitingDeleteMode = isInDeleteMode && (isCancelling || tableBody.getElementsByTagName('tr').length === 0);
                if (enteringDeleteMode || exitingDeleteMode) {
                    isInDeleteMode = enteringDeleteMode;
                    deleteModeBtn.style.display = isInDeleteMode ? 'none' : 'inline-block';
                    confirmDeleteBtn.style.display = isInDeleteMode ? 'inline-block' : 'none';
                    cancelDeleteBtn.style.display = isInDeleteMode ? 'inline-block' : 'none';
                    addRowBtn.disabled = isInDeleteMode;
                    addRowBtn.style.opacity = isInDeleteMode ? '0.6' : '1';
                    addRowBtn.style.cursor = isInDeleteMode ? 'not-allowed' : 'pointer';
                    checkboxHeaderCell.innerHTML = isInDeleteMode ? '<i class="fas fa-trash-alt" style="color: #dc3545;" title="Delete Mode Active"></i>' : '';
                    checkboxHeaderCell.classList.toggle('delete-mode-active', isInDeleteMode);
                    if (tableBody) {
                        const currentRows = tableBody.getElementsByTagName('tr');
                        for (let i = 0; i < currentRows.length; i++) {
                            const row = currentRows[i]; const checkboxCell = row.cells[0];
                            if (checkboxCell) {
                                if (isInDeleteMode) {
                                    if (!checkboxCell.querySelector('.row-checkbox')) {
                                        const slNo = row.cells[1]?.querySelector('.sl-no')?.textContent || (i + 1);
                                        checkboxCell.innerHTML = `<input type="checkbox" class="row-checkbox" value="${i}" name="select_item_for_delete[]" aria-label="Select item ${slNo} for deletion">`;
                                    }
                                } else { checkboxCell.innerHTML = ''; }
                            }
                        }
                    } if (!isInDeleteMode) { renumberRows(); }
                }
            }
            window.addItemRow = function() {
                if (!tableBody || !addRowBtn) return;
                if (isInDeleteMode) { alert("Cannot add items while in delete mode. Please cancel or confirm deletion first."); return; }
                const newRow = tableBody.insertRow(); const rowCount = tableBody.rows.length; let cellIndex = 0;
                const cellCheckbox = newRow.insertCell(cellIndex++); cellCheckbox.className = 'td-checkbox-delete'; cellCheckbox.innerHTML = '';
                const cellSlNo = newRow.insertCell(cellIndex++); cellSlNo.className = 'sl-cell'; cellSlNo.innerHTML = `<span class="sl-no">${rowCount}</span>`;
                const cellItemName = newRow.insertCell(cellIndex++); cellItemName.innerHTML = `<input type="text" class="item-input" name="item_name[]" placeholder="Enter item name" required>`;
                const cellDesc = newRow.insertCell(cellIndex++); cellDesc.innerHTML = `<textarea class="item-input" name="item_description[]" placeholder="Enter description/specs" rows="2"></textarea>`;
                const cellQty = newRow.insertCell(cellIndex++); cellQty.innerHTML = `<input type="number" min="1" step="1" class="item-input" name="item_quantity[]" placeholder="e.g., 5" required>`;
                const cellRemarks = newRow.insertCell(cellIndex++); cellRemarks.innerHTML = `<textarea class="item-input" name="item_remarks[]" placeholder="Enter purpose/remarks" rows="2"></textarea>`;
                newRow.querySelector('input[name="item_name[]"]')?.focus();
                if (deleteModeBtn) deleteModeBtn.disabled = false;
            }
            window.performBulkDelete = function() {
                if (!tableBody || !confirmDeleteBtn) return; if (!isInDeleteMode) return;
                const selectedCheckboxes = Array.from(tableBody.querySelectorAll('td.td-checkbox-delete input.row-checkbox:checked'));
                if (selectedCheckboxes.length === 0) { alert('Please select at least one item row to delete.'); return; }
                if (!confirm(`Are you sure you want to delete ${selectedCheckboxes.length} selected item(s)? This action cannot be undone.`)) { return; }
                for (let i = selectedCheckboxes.length - 1; i >= 0; i--) { const row = selectedCheckboxes[i].closest('tr'); if (row) { tableBody.removeChild(row); } }
                 toggleDeleteMode(true); if (deleteModeBtn && tableBody.getElementsByTagName('tr').length === 0) { deleteModeBtn.disabled = true; }
            }
            function renumberRows() {
                 if (!tableBody) return; const allRows = tableBody.getElementsByTagName('tr');
                 for (let i = 0; i < allRows.length; i++) { const slNoCell = allRows[i].cells[1]; const slNoSpan = slNoCell?.querySelector('.sl-no'); if (slNoSpan) { slNoSpan.textContent = i + 1; } }
            }
            requisitionForm.addEventListener('submit', function(event) {
                let hasValidItem = false;
                if (tableBody) {
                    const itemRows = tableBody.getElementsByTagName('tr');
                    if (itemRows.length > 0) {
                        for (let row of itemRows) {
                             const nameInput = row.querySelector('input[name="item_name[]"]'); const qtyInput = row.querySelector('input[name="item_quantity[]"]');
                             if (nameInput?.value.trim() !== '' && qtyInput?.value.trim() !== '' && parseInt(qtyInput.value) > 0) { hasValidItem = true; break; }
                        }
                    }
                } else { console.error("Item table body not found for validation."); }
                if (!hasValidItem) {
                    event.preventDefault(); alert("Please add at least one item with both a name and a valid quantity (greater than 0).");
                    tableBody?.querySelector('input[name="item_name[]"]')?.focus(); return;
                }
                if (!requisitionForm.checkValidity()) {
                    event.preventDefault(); const firstInvalid = requisitionForm.querySelector(':invalid'); firstInvalid?.focus();
                    alert("Please fill in all required fields marked with * and ensure item details are complete."); return;
                }
                const submitButton = requisitionForm.querySelector('.submit-button');
                if (submitButton) { submitButton.disabled = true; submitButton.textContent = 'Submitting...'; }
                console.log("Client-side checks passed. Submitting form...");
            });
            if(confirmDeleteBtn) confirmDeleteBtn.style.display = 'none';
            if(cancelDeleteBtn) cancelDeleteBtn.style.display = 'none';
            if(checkboxHeaderCell) checkboxHeaderCell.innerHTML = '';
            renumberRows();
             if (addRowBtn && !isInDeleteMode) { addRowBtn.disabled = false; addRowBtn.style.opacity = '1'; addRowBtn.style.cursor = 'pointer'; }
             if (deleteModeBtn && (!tableBody || tableBody.getElementsByTagName('tr').length === 0)) { deleteModeBtn.disabled = true; }
        });
    </script>

</x-app-layout>
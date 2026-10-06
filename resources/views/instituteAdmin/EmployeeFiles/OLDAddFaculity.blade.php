@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Add Members Slide Panel</title>
<style>
    .error {
        color: red;
        font-size: 12px;
        margin-top: 4px;
    }

    .mainDiv {
        position: relative;
    }

    .childContent {
        position: absolute;
        color: #000;
        top: 65%;
        left: 7%;
    }

    .child1 {
        background: linear-gradient(90deg, #4B3F72, #F6C667);
        padding: 40px 20px;
        border-radius: 8px 8px 0 0;
        text-align: center;
        color: white;
    }

    .profile-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        margin-top: -50px;
        border: 5px solid white;
    }

    .add-btn {
        margin: 20px;
        padding: 10px 20px;
        background-color: #007BFF;
        border: none;
        color: white;
        cursor: pointer;
        border-radius: 5px;
    }

        /* Overlay */
    .overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.3);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease;
        z-index: 100;
    }

    .overlay.show {
        opacity: 1;
        visibility: visible;
    }

        /* Side Panel */
    .side-panel {
        position: fixed;
        top: 0;
        right: -100%;
        width: 100%;
        max-width: 800px;
        height: 100vh;
        background-color: #F8FAFF;
        box-shadow: -2px 0 8px rgba(0, 0, 0, 0.2);
        transition: right 0.4s ease;
        z-index: 200; /* BELOW view panel */
        display: flex;
        flex-direction: column;
    }

    .side-panel.open {
        right: 0;
    }

    .panel-header {
        background-color: #DCEEFF;
        padding: 15px;
        font-size: 18px;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
    }

        /* Panel Content (scrolls whole side panel) */
    .panel-content {
        flex: 1;
        overflow-y: auto;
        padding-bottom: 100px;
        max-height: calc(100vh - 60px);
        box-sizing: border-box;
    }

        /* Accordion */
    #sidePanel .accordion {
        background-color: #fff;
        cursor: pointer;
        padding: 15px;
        width: 100%;
        border: none;
        border-bottom: 1px solid #ccc;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .accordion.active {
        background-color: #E8F4FF;
    }

    .accordion:after {
        content: '\25BC';
        transition: transform 0.3s ease;
    }

    .accordion.active:after {
        transform: rotate(180deg);
    }

    .panel {
        max-height: 0;
        overflow: hidden;
        background-color: #fff;
        transition: max-height 0.3s ease;
        padding: 0 15px;
    }

    .panel.open {
        max-height: none;
        padding: 15px;
    }

        /* Form Styling */
    form {
        margin: 10px 0;
    }

    label {
        display: block;
        margin-top: 8px;
        font-size: 14px;
    }

    input,
    select,
    textarea {
        width: 100%;
        padding: 8px;
        margin-top: 4px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        box-sizing: border-box;
    }

    .gender-options {
        display: flex;
        gap: 15px;
        margin-top: 5px;
    }

    .gender-options label {
        display: flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        font-size: 14px;
    }

    .gender-options input[type="radio"] {
        appearance: none;
        width: 16px;
        height: 16px;
        border: 2px solid #007BFF;
        border-radius: 50%;
        outline: none;
        cursor: pointer;
        position: relative;
    }

    .gender-options input[type="radio"]:checked::before {
        content: '';
        position: absolute;
        top: 3px;
        left: 3px;
        width: 8px;
        height: 8px;
        background-color: #007BFF;
        border-radius: 50%;
    }

    .phone-input {
        display: flex;
        align-items: center;
    }

    .phone-input span {
        background: #eee;
        padding: 8px;
        border: 1px solid #ccc;
        border-right: none;
        border-radius: 4px 0 0 4px;
    }

    .phone-input input {
        border-radius: 0 4px 4px 0;
        border-left: none;
        flex: 1;
    }

    /* Footer buttons */
    .save-close {
        padding: 15px;
        background-color: #E8F4FF;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        position: absolute;
        bottom: 0;
        width: 100%;
        box-sizing: border-box;
        z-index: 11;
    }

    .save-close button {
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .save-btn {
        background-color: #007BFF;
        color: white;
    }

    .close-btn-footer {
        background-color: #ccc;
    }

    .form-group {
        margin-bottom: 15px;
    }

    /* Ensure main content scrollable */
    .container.mt-5 {
        overflow: visible;
    }

    .table-responsive {
        overflow-x: auto;
    }

        /* View Panel Styles */
    #viewEmployeePanel {
        position: fixed;
        top: 0;
        right: -100%;
        width: 100%;
        max-width: 800px;
        height: 100vh;
        background: #fff;
        box-shadow: -2px 0 8px rgba(0, 0, 0, .2);
        z-index: 300; /* ALWAYS ABOVE side-panel */
        overflow-y: auto;
        transition: right 0.4s ease;
    }

    #viewEmployeePanel.open {
        right: 0;
    }

    .view-panel-header {
        padding: 15px;
        font-size: 18px;
        font-weight: bold;
        background-color: #DCEEFF;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #ccc;
    }

    .view-panel-content {
        padding: 15px;
    }

    .view-section {
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
    }

    .view-section-header {
        background-color: #F5F5F5;
        padding: 10px 15px;
        font-weight: bold;
        border-bottom: 1px solid #ddd;
    }

    .view-section-body {
        padding: 15px;
    }

    .view-row {
        display: flex;
        margin-bottom: 10px;
    }

    .view-label {
        font-weight: bold;
        width: 40%;
        color: #555;
    }

    .view-value {
        width: 60%;
    }

    /* Document viewer */
    .doc-viewer {
        max-width: 100%;
        max-height: 400px;
        margin: 0 auto;
        display: block;
    }

    .doc-iframe {
        width: 100%;
        height: 500px;
        border: none;
    }

    /* Tabs styling */
    .view-tabs {
        display: flex;
        border-bottom: 1px solid #ddd;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .view-tab {
        padding: 10px 20px;
        cursor: pointer;
        border: 1px solid transparent;
        border-bottom: none;
        margin-right: 5px;
        border-radius: 5px 5px 0 0;
        background-color: #F5F5F5;
        margin-bottom: 5px;
    }

    .view-tab.active {
        background-color: #fff;
        border-color: #ddd;
        border-bottom: 1px solid #fff;
        margin-bottom: -1px;
    }

    .view-tab-content {
        display: none;
    }

    .view-tab-content.active {
        display: block;
    }

    /* Reference section styling */
    .reference-section {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px dashed #ccc;
    }

    .reference-header {
        font-weight: bold;
        margin-bottom: 10px;
        color: #555;
    }

    #signupFormContainer label {
        margin-top: 0;
        margin-bottom: 0;
    }

    #signupFormContainer input {
        width: 100%;
        margin-top: 0;
    }

    #signupFormContainer #terms {
        width: auto;
        margin-top: 0;
        margin-right: 8px;
    }
</style>
<style>
    /* Filter container */
    .filter-box {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        padding: 15px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    /* Input wrapper */
    .filter-input {
        position: relative;
    }

    .filter-input i {
        position: absolute;
        top: 59%;
        left: 12px;
        transform: translateY(-50%);
        font-size: 18px;
        color: #888;
    }

    /* Input design */
    .filter-input input {
        padding: 10px 12px 10px 40px;
        /* space for icon */
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        width: 177px;
        transition: 0.25s ease;
        background: #fafafa;
    }

    .filter-input input:focus {
        outline: none;
        border-color: #4a70dc;
        background: #fff;
        box-shadow: 0 0 4px rgba(74, 112, 220, 0.4);
    }
    .banner {
        height: 260px;
        border-radius: 18px;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #2154BE, #3E70B3);
        display: flex;
        align-items: center;
    }
    .banner img.bg {
        width: 100%;
        height: 260px;
        object-fit: cover;
        filter: brightness(0.8);
    }
    .banner .meta {
        position: absolute;
        left: 28px;
        bottom: 22px;
        background: rgba(255, 255, 255, 0.95);
        padding: 14px 20px;
        border-radius: 12px;
    }
</style>

    <!-- Header with merchant name -->
    <div class="mainDiv1">
        <div class="mainDiv" style="height: 270px;">
<div class="banner mb-4">
        @if(!empty($bannerPath))
        <!-- <img src="{{ asset('storage/'.$bannerPath) }}" alt="Institute Image" class="bg"> -->
        <img src="{{ asset('/image/'.$fincapMerchants->documents->first()->institute_image_path)   }}"
            alt="institute image" class="bg">
        @else
        <img src="{{ asset('/image/'.$fincapMerchants->documents->first()->institute_image_path)   }}"
            alt="institute image" class="bg">
        @endif
        <div class="meta">
            <h3 style="margin:0;">
                {{ $fincapMerchants->name ?? $fincapMerchants->fincap_merchant_name ?? 'Institute Name' }}</h3>
            <p style="margin:0;color:#444;">
                {{ $fincapMerchants->state ?? $fincapMerchants->fincap_merchant_state ?? '' }},
                {{ $fincapMerchants->city ?? $fincapMerchants->fincap_merchant_city ?? '' }}</p>
        </div>
    </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center my-2">
            <h2>Employee Details</h2>
            <!-- Add Employee Button -->
            <button class="add-btn">
                <a href="/institute/admin/addemployeesdetails" style="text-decoration:none; color: white;">
                Add Employees
                </a>
            </button>
        </div>

        {{-- Messages (optional) --}}
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Employee List Table  Filter--}}
        <form method="GET" class="mb-4 filter-box">
            <!-- <div class="form-row "> -->

            {{-- Department --}}
            <div class="filter-input">

                <i class="bi bi-building"></i>

                <!-- <span class="input-group-text"><i class="bi bi-building"></i></span> -->
                <input list="employeeDepartmentList" name="department" class="form-control"
                    value="{{ request('department') }}" placeholder="Department">
                <datalist id="employeeDepartmentList">
                    @foreach($departments as $dept)
                    <option value="{{ $dept->department }}">
                        @endforeach
                </datalist>
            </div>

            {{-- Designation --}}
            <div class="filter-input">
                <i class="bi bi-person-workspace"></i>

                <!-- <span class="input-group-text"><i class="bi bi-person-badge"></i></span> -->
                <input list="employeeDesignationList" name="designation" class="form-control"
                    value="{{ request('designation') }}" placeholder="Designation">
                <datalist id="employeeDesignationList">
                    @foreach($designations as $ds)
                    <option value="{{ $ds->designations }}">
                        @endforeach
                </datalist>
            </div>

            {{-- Employee Code --}}
            <div class="filter-input">
                <i class="bi bi-hash"></i>


                <!-- <span class="input-group-text"><i class="bi bi-upc-scan"></i></span> -->
                <input list="employeeCodeList" name="employee_code" class="form-control"
                    value="{{ request('employee_code') }}" placeholder="Employee Code">
                <datalist id="employeeCodeList">
                    @foreach($employeeCodes as $code)
                    <option value="{{ $code->employee_code }}">
                        @endforeach
                </datalist>
            </div>

            {{-- Name --}}
            <div class="filter-input">
                <i class="bi bi-person"></i>

                <!-- <span class="input-group-text"><i class="bi bi-person"></i></span> -->
                <input list="employeeNamesList" name="name" class="form-control"
                    value="{{ request('name') }}" placeholder="Name">
                <datalist id="employeeNamesList">
                    @foreach($employeeNames as $emp)
                    <option value="{{ $emp->name }}">
                        @endforeach
                </datalist>
            </div>

            {{-- Buttons --}}
            <div class="col-md-2 mt-2 d-flex align-items-end">
                <button class="btn btn-primary mr-2">Filter</button>
                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
            </div>

            <!-- </div> -->
        </form>

        {{-- Employee List Table --}}
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Employee Code</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody id="CoursesDisplayTable">
                    @foreach($employees as $emp)
                    <tr>
                        <td>{{ $emp->employee_code }}</td>
                        <td>{{ $emp->name }}</td>
                        <td>{{ $emp->email }}</td>
                        <td>{{ $emp->department_name ?? 'N/A' }}</td>
                        <td>{{ $emp->designation }}</td>
                        <td class="text-center d-flex">
                            <a href="{{ route('employees.assign-responsibilities.form', $emp->employee_id) }}" class="btn btn-outline-success" style="width:127px;margin:5px;">Assign Duties</a>
                            <button class="btn btn-outline-primary mr-1"
                                onclick="viewEmployee('{{ $emp->id }}')">View</button>
                            <a href="{{ route('employees.edit', $emp->employee_id) }}" class="btn btn-outline-danger">Edit</a>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <div>
            {{ $employees->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <div class="overlay" id="overlay" onclick="closePanels()"></div>
    <div class="overlay" id="overlayView" onclick="closePanels()"></div>
    <!-- View Employee Panel -->
    <div id="viewEmployeePanel">
        <div class="view-panel-header">
            Employee Details
            <button class="close-btn" onclick="closePanels()">×</button>
        </div>

        <div class="view-tabs">
            <div class="view-tab active" data-tab="basic">Basic</div>
            <div class="view-tab" data-tab="professional">Professional</div>
            <div class="view-tab" data-tab="contact">Contact</div>
            <div class="view-tab" data-tab="documents">Legal</div>
            <div class="view-tab" data-tab="bank">Bank</div>
        </div>

        <!-- REMOVED EDIT BUTTON FROM HERE -->
        <!-- <div class="mx-3 text-right">
            <button class="btn btn-sm btn-outline-primary edit-employee-btn">
                <i class="fas fa-edit"></i> Edit
            </button>
        </div> -->

        <div class="view-panel-content">
            <!-- Basic Details Tab -->
            <div class="view-tab-content active" id="basic-tab">
                <div class="view-section">
                    <div class="view-section-header">Employee Information</div>
                    <div class="view-section-body" id="employee-info">
                        <!-- Employee info will be populated here -->
                    </div>
                </div>

                <div class="view-section">
                    <div class="view-section-header">Address Information</div>
                    <div class="view-section-body" id="address-info">
                        <!-- Address info will be populated here -->
                    </div>
                </div>
            </div>

            <!-- Professional Information Tab -->
            <div class="view-tab-content" id="professional-tab">
                <div class="view-section">
                    <div class="view-section-header">Professional Details</div>
                    <div class="view-section-body" id="professional-info">
                        <!-- Professional info will be populated here -->
                    </div>
                </div>
            </div>

            <!-- Contact Information Tab -->
            <div class="view-tab-content" id="contact-tab">
                <div class="view-section">
                    <div class="view-section-header">Emergency Contact</div>
                    <div class="view-section-body" id="contact-info">
                        <!-- Contact info will be populated here -->
                    </div>
                </div>

                <div class="view-section">
                    <div class="view-section-header">Reference Details</div>
                    <div class="view-section-body" id="reference-info">
                        <!-- Reference info will be populated here -->
                    </div>
                </div>
            </div>

            <!-- Legal Documents Tab -->
            <div class="view-tab-content" id="documents-tab">
                <div class="view-section">
                    <div class="view-section-header">Legal Documents</div>
                    <div class="view-section-body" id="documents-info">
                        <!-- Documents info will be populated here -->
                    </div>
                </div>
            </div>

            <!-- Bank Details Tab -->
            <div class="view-tab-content" id="bank-tab">
                <div class="view-section">
                    <div class="view-section-header">Bank Information</div>
                    <div class="view-section-body" id="bank-info">
                        <!-- Bank info will be populated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Document View Modal -->
    <div class="modal fade" id="docModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Document Viewer</h5>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">X</button>
                </div>
                <div class="modal-body text-center" id="docPreview">
                    <!-- Document will be shown here -->
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <!-- Signup Modal -->
    <div class="modal fade" id="signupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title">Signup Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="signupFormContainer">
                    <!-- Form will be injected here dynamically -->
                    <p>Loading...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery (must be above your script that uses $) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // === Side Panel Functions ===
        function openPanel() {
            document.getElementById("sidePanel").classList.add("open");
            document.getElementById("overlay").classList.add("show");
            document.body.style.overflow = 'hidden';
        }

        function openViewPanel() {
            document.getElementById("viewEmployeePanel").classList.add("open");
            document.getElementById("overlayView").classList.add("show");
            document.body.style.overflow = "hidden";
        }

        function closePanels() {
            const sidePanel = document.getElementById("sidePanel");
            const overlay = document.getElementById("overlay");

            const viewPanel = document.getElementById("viewEmployeePanel");
            const overlayView = document.getElementById("overlayView");

            if (sidePanel) {
                sidePanel.classList.remove("open");
                sidePanel.style.right = "";  // clear inline style
            }

            if (overlay) overlay.classList.remove("show");

            if (viewPanel) {
                viewPanel.classList.remove("open");
                viewPanel.style.right = "";  // clear inline style
            }

            if (overlayView) overlayView.classList.remove("show");

            document.body.style.overflow = "";
        }

        // Tab switching for view panel
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.view-tab');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs and contents
                    document.querySelectorAll('.view-tab').forEach(t => t.classList.remove(
                        'active'));
                    document.querySelectorAll('.view-tab-content').forEach(c => c.classList.remove(
                        'active'));

                    // Add active class to clicked tab
                    this.classList.add('active');

                    // Show corresponding content
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(`${tabId}-tab`).classList.add('active');
                });
            });
        });

        // Event Delegation for all "View" buttons
        $(document).on('click', '.view-doc', function() {
            let filePath = $(this).data('file');
            let previewHtml = '';

            // Add a timestamp to prevent caching
            let cacheBuster = '?t=' + new Date().getTime();

            if (filePath.match(/\.(jpg|jpeg|png|gif|webp|bmp)$/i)) {
                previewHtml =
                    `<img src="${filePath}${cacheBuster}" class="doc-viewer" alt="Document">`;
            } else if (filePath.match(/\.(pdf)$/i)) {
                previewHtml =
                    `<iframe src="${filePath}${cacheBuster}#view=fitH" class="doc-iframe" frameborder="0"></iframe>`;
            } else {
                previewHtml =
                    `<div class="p-3"><a href="${filePath}" target="_blank" class="btn btn-primary">Open Document</a></div>`;
            }

            $('#docPreview').html(previewHtml);
            $('#docModal').modal('show');
        });

        // === Employee View Functions ===
        function viewEmployee(id) {
            let panel = document.getElementById("viewEmployeePanel");
            panel.style.right = "0"; // open panel

            // reset
            $("#employee-info, #address-info, #professional-info, #contact-info, #documents-info, #bank-info, #reference-info")
                .html("<p>Loading...</p>");

            $.ajax({
                url: '/employee-details/' + id,
                method: 'GET',
                success: function(e) {
                    // EMPLOYEE INFO SECTION
                    $('#employee-info').html(`
                    <div class="view-row">
                        <div class="view-label">Employee Code</div>
                        <div class="view-value">${e.employee_code || '-'}</div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">Name</div>
                        <div class="view-value">${e.name || '-'}</div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">Gender</div>
                        <div class="view-value">${e.gender || '-'}</div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">Email</div>
                        <div class="view-value">${e.email || '-'}</div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">Phone</div>
                        <div class="view-value">${e.mobile_number || '-'}</div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">Date of Birth</div>
                        <div class="view-value">${e.dob || '-'}</div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">Department</div>
                        <div class="view-value">${e.department_name || '-'}</div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">Designation</div>
                        <div class="view-value">${e.designation || '-'}</div>
                    </div>
                `);

                    // ADDRESS INFO SECTION
                    $('#address-info').html(`
                    <div class="view-row">
                        <div class="view-label">Address Line 1</div>
                        <div class="view-value">${e.addressline1 || '-'}</div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">Address Line 2</div>
                        <div class="view-value">${e.addressline2 || '-'}</div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">City</div>
                        <div class="view-value">${e.city || '-'}</div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">State</div>
                        <div class="view-value">${e.state || '-'}</div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">Pincode</div>
                        <div class="view-value">${e.pincode || '-'}</div>
                    </div>
                `);

                    // PROFESSIONAL INFO SECTION
                    $('#professional-info').html(`
                    <div class="view-row">
                        <div class="view-label">Employment Type</div>
                        <div class="view-value">${e.employment_type || '-'}</div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">Salary Type</div>
                        <div class="view-value">${e.salary_type || '-'}</div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">Date of Joining</div>
                        <div class="view-value">${e.doj || '-'}</div>
                    </div>
                `);

                    // CONTACT INFO SECTION
                    $('#contact-info').html(`
                    <div class="view-row">
                        <div class="view-label">Emergency Contact</div>
                        <div class="view-value">${e.emergency_contact_number || '-'}</div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">Contact Person</div>
                        <div class="view-value">${e.contact_person_name || '-'}</div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">Relation</div>
                        <div class="view-value">${e.relation_with_contact || '-'}</div>
                    </div>
                `);

                    // REFERENCE INFO SECTION (Now under Contact tab)
                    $('#reference-info').html(`
                    <div class="view-row">
                        <div class="view-label">Reference Name</div>
                        <div class="view-value">${e.reference_name || '-'}</div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">Reference Contact</div>
                        <div class="view-value">${e.reference_contact_number || '-'}</div>
                    </div>
                `);

                    // DOCUMENTS INFO SECTION
                    $('#documents-info').html(`
                    <div class="view-row">
                        <div class="view-label">Aadhaar Card</div>
                        <div class="view-value">
                            ${e.aadhaar_card 
                                ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${e.aadhaar_card}">View</button>` 
                                : 'Not Uploaded'}
                        </div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">PAN Card</div>
                        <div class="view-value">
                            ${e.pan_card 
                                ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${e.pan_card}">View</button>` 
                                : 'Not Uploaded'}
                        </div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">Driving License</div>
                        <div class="view-value">
                            ${e.driving_license 
                                ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${e.driving_license}">View</button>` 
                                : 'Not Uploaded'}
                        </div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">Passport Photo</div>
                        <div class="view-value">
                            ${e.passport_photo 
                                ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${e.passport_photo}">View</button>` 
                                : 'Not Uploaded'}
                        </div>
                    </div>
                `);

                    // BANK INFO SECTION
                    $('#bank-info').html(`
                    <div class="view-row">
                        <div class="view-label">Bank Name</div>
                        <div class="view-value">${e.bank_name || '-'}</div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">Branch Name</div>
                        <div class="view-value">${e.branch_name || '-'}</div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">Account Number</div>
                        <div class="view-value">${e.account_number || '-'}</div>
                    </div>
                    <div class="view-row">
                        <div class="view-label">IFSC Code</div>
                        <div class="view-value">${e.ifsc_code || '-'}</div>
                    </div>
                `);
                },
                error: function() {
                    alert("Failed to load employee details.");
                }
            });
        }

        // === Initialize on page load ===
        document.addEventListener('DOMContentLoaded', function() {
            // Load departments if category is already selected
            const selectedCategory = document.getElementById('department_category_id').value;
            if (selectedCategory) {
                loadDepartments(selectedCategory);
            }
        });
    </script>
    @endsection
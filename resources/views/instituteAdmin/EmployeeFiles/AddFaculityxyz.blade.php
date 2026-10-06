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
    background-color: #007bff;
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 5px;
}

/* Overlay */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.3);
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease;
    z-index: 9;
}

.overlay.show {
    opacity: 1;
    visibility: visible;
}

/* Side Panel */
.side-panel {
    position: fixed;
    top: 0;
    right: -550px;
    width: 550px;
    height: 100%;
    background-color: #f8faff;
    box-shadow: -2px 0 8px rgba(0, 0, 0, 0.2);
    transition: right 0.4s ease;
    z-index: 10;
    display: flex;
    flex-direction: column;
}

.side-panel.open {
    right: 0;
}

.panel-header {
    background-color: #dceeff;
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
    padding: 0;
    padding-bottom: 100px;
    max-height: calc(100vh - 60px);
    /* full screen minus header */
    box-sizing: border-box;
}

/* Accordion */
#sidePanel .accordion {
    background-color: #fff;
    cursor: pointer;
    padding: 15px;
    width: 100%;
    text-align: left;
    border: none;
    outline: none;
    transition: background-color 0.3s ease;
    border-bottom: 1px solid #ccc;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.accordion.active {
    background-color: #e8f4ff;
}

.accordion:after {
    content: '\25BC';
    transition: transform 0.3s ease;
}

.accordion.active:after {
    transform: rotate(180deg);
}

/* Accordion Panels */
.panel {
    max-height: 0;
    overflow: hidden;
    background-color: #fff;
    transition: max-height 0.3s ease;
    padding: 0 15px;
}

.panel.open {
    max-height: none;
    /* allow full content */
    padding: 15px;
    overflow: visible;
    /* no cutoff */
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
    border: 2px solid #007bff;
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
    background-color: #007bff;
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
    background-color: #e8f4ff;
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
    background-color: #007bff;
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

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.bg-success {
    background-color: #28a745 !important;
    color: white;
}

.bg-warning {
    background-color: #ffc107 !important;
    color: black;
}
</style>

<body>
    <!-- Header with merchant name -->
    <div class="mainDiv1">
        <div class="mainDiv" style="height: 270px;">
            <div class="child1" style="height: 200px;">
                <div class="childContent">
                    <img src="/images/allen-logo.webp" alt="Profile Image" class="profile-image">
                    <h3>Vignesh Ramesh</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center my-2">
            <h2>Employee Details</h2>
            <!-- Add Employee Button -->
            <button class="add-btn" onclick="openPanel()">Add Employees</button>
        </div>

        {{-- Messages (optional) --}}
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

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
                        <th>Profile Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody id="CoursesDisplayTable">
                    @foreach($employees as $emp)
                    <tr>
                        <td>{{ $emp->employee_code }}</td>
                        <td>{{ $emp->name }}</td>
                        <td>{{ $emp->email }}</td>
                        <td>{{ $emp->department->department ?? 'N/A' }}</td>
                        <td>{{ $emp->designation }}</td>
                        <td>
                            <span class="badge {{ $emp->profile_status == 'completed' ? 'bg-success' : 'bg-warning' }}">
                                {{ $emp->profile_status == 'completed' ? 'Completed' : 'Basic Details' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info" onclick="viewEmployee('{{ $emp->id }}')">View</button>
                            <button class="btn btn-sm btn-warning" onclick="editEmployee('{{ $emp->id }}')">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="openSignupModal('{{ $emp->id }}')">Create Credentials</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="overlay" id="overlay" onclick="closePanel()"></div>

    <div class="side-panel" id="sidePanel">
        <div class="panel-header">
            Add Employee Details
            <button class="close-btn" onclick="closePanel()">×</button>
        </div>

        <form id="employeeForm" action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="panel-content">
                <!-- 1. Basic Details (Required) -->
                <button type="button" class="accordion active" id="basicAccordion" onclick="toggleAccordion(this)">Basic Details *</button>
                <div class="panel open">
                    <div class="form-group">
                        <label>Employee Code *</label>
                        <input type="text" name="employee_code" class="form-control" value="{{ old('employee_code') }}" required>
                        @error('employee_code')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Mobile Number *</label>
                        <input type="tel" name="mobile_number" class="form-control" value="{{ old('mobile_number') }}" required>
                        @error('mobile_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Department *</label>
                        <select name="department_id" class="form-select" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->department }}
                            </option>
                            @endforeach
                        </select>
                        @error('department_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Designation</label>
                        <select name="designation" class="form-select">
                            <option value="">Select Designation</option>
                            @foreach($designations as $desig)
                            <option value="{{ $desig->designations }}" {{ old('designation') == $desig->designations ? 'selected' : '' }}>
                                {{ $desig->designations }}
                            </option>
                            @endforeach
                        </select>
                        @error('designation')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="d-block">Gender</label>
                        <div class="gender-options">
                            <label><input type="radio" name="gender" value="male"
                                    {{ old('gender') == 'male' ? 'checked' : '' }}> Male</label>
                            <label><input type="radio" name="gender" value="female"
                                    {{ old('gender') == 'female' ? 'checked' : '' }}> Female</label>
                            <label><input type="radio" name="gender" value="other"
                                    {{ old('gender') == 'other' ? 'checked' : '' }}> Other</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" class="form-control" value="{{ old('dob') }}">
                        @error('dob')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- 2. Professional Information (Optional) -->
                <button type="button" class="accordion" id="jobAccordion" onclick="toggleAccordion(this)">Professional Information</button>
                <div class="panel">
                    <div class="form-group">
                        <label>Employment Type</label>
                        <select name="employment_type" class="form-select">
                            <option value="">Select</option>
                            <option value="Full-time" {{ old('employment_type') == 'Full-time' ? 'selected' : '' }}>
                                Full-time</option>
                            <option value="Part-time" {{ old('employment_type') == 'Part-time' ? 'selected' : '' }}>
                                Part-time</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Salary Type</label>
                        <select name="salary_type" class="form-select">
                            <option value="">Select</option>
                            <option value="Monthly" {{ old('salary_type') == 'Monthly' ? 'selected' : '' }}>Monthly
                            </option>
                            <option value="Hourly" {{ old('salary_type') == 'Hourly' ? 'selected' : '' }}>Hourly
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Date of Joining</label>
                        <input type="date" name="doj" class="form-control" value="{{ old('doj') }}">
                    </div>
                </div>

                <!-- 3. Address Information (Optional) -->
                <button type="button" class="accordion" id="addressAccordion" onclick="toggleAccordion(this)">Address Information</button>
                <div class="panel">
                    <div class="form-group">
                        <label>Address Line 1</label>
                        <textarea name="addressline1" class="form-control" rows="2">{{ old('addressline1') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Address Line 2</label>
                        <textarea name="addressline2" class="form-control" rows="2">{{ old('addressline2') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>State</label>
                        <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                    </div>
                    <div class="form-group">
                        <label>Pincode</label>
                        <input type="text" name="pincode" class="form-control" value="{{ old('pincode') }}">
                    </div>
                </div>

                <!-- 4. Contact Information (Optional) -->
                <button type="button" class="accordion" id="contactAccordion" onclick="toggleAccordion(this)">Contact Information</button>
                <div class="panel">
                    <div class="form-group">
                        <label>Emergency Contact Number</label>
                        <div class="phone-input">
                            <span>+91</span>
                            <input type="tel" name="emergency_contact_number" value="{{ old('emergency_contact_number') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Contact Person Name</label>
                        <input type="text" name="contact_person_name" class="form-control" value="{{ old('contact_person_name') }}">
                    </div>

                    <div class="form-group">
                        <label>Relation with the Contact</label>
                        <input type="text" name="relation_with_contact" class="form-control" value="{{ old('relation_with_contact') }}">
                    </div>
                </div>

                <!-- 5. Bank Details (Optional) -->
                <button type="button" class="accordion" id="bankAccordion" onclick="toggleAccordion(this)">Bank Details</button>
                <div class="panel">
                    <div class="form-group">
                        <label>Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}">
                    </div>

                    <div class="form-group">
                        <label>Branch Name</label>
                        <input type="text" name="branch_name" class="form-control" value="{{ old('branch_name') }}">
                    </div>

                    <div class="form-group">
                        <label>Account Number</label>
                        <input type="text" name="account_number" class="form-control" value="{{ old('account_number') }}">
                    </div>

                    <div class="form-group">
                        <label>IFSC Code</label>
                        <input type="text" name="ifsc_code" class="form-control" value="{{ old('ifsc_code') }}">
                    </div>
                </div>

                <!-- 6. Legal Documents (Optional) -->
                <button type="button" class="accordion" id="legalAccordion" onclick="toggleAccordion(this)">Legal Documents</button>
                <div class="panel">
                    <div class="form-group">
                        <label>Aadhaar Card (img/pdf)</label>
                        <input type="file" name="aadhaar_card" class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf">
                    </div>

                    <div class="form-group">
                        <label>PAN Card (img/pdf)</label>
                        <input type="file" name="pan_card" class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf">
                    </div>

                    <div class="form-group">
                        <label>Driving License (img/pdf)</label>
                        <input type="file" name="driving_license" class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf">
                    </div>

                    <div class="form-group">
                        <label>Passport Size Photo (image)</label>
                        <input type="file" name="passport_photo" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                    </div>
                </div>

                <!-- 7. Reference (Optional) -->
                <button type="button" class="accordion" onclick="toggleAccordion(this)">Reference</button>
                <div class="panel">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="reference_name" class="form-control" value="{{ old('reference_name') }}">
                    </div>

                    <div class="form-group">
                        <label>Contact Number</label>
                        <div class="phone-input">
                            <span>+91</span>
                            <input type="tel" name="reference_contact_number" value="{{ old('reference_contact_number') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="save-close">
                <button type="button" class="close-btn-footer" onclick="closePanel()">Close</button>
                <button type="submit" class="save-btn">Save Details</button>
            </div>
        </form>
    </div>

    <!-- View Employee Panel -->
    <div id="viewEmployeePanel" style="
        position: fixed; top: 0; right: -100%;
        width: 40%; max-width: 900px;
        height: 100%; background: #fff;
        box-shadow: -2px 0 8px rgba(0,0,0,.2);
        z-index: 11; overflow-y: auto;
        transition: right 0.4s ease;">

        <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="m-0">Employee Details</h5>
            <button onclick="closeViewPanel()" style="border:none; background:none; font-size:20px;">×</button>
        </div>

        <!-- Nav Tabs -->
        <ul class="nav nav-tabs px-3 pt-2" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#tabEmpBasic" role="tab">Basic</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tabEmpJob" role="tab">Job</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tabdocument" role="tab">Documents</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tabEmpMeta" role="tab">Bank & Meta</a>
            </li>
        </ul>

        <!-- Tab Contents -->
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tabEmpBasic" role="tabpanel"></div>
            <div class="tab-pane fade" id="tabEmpJob" role="tabpanel"></div>
            <div class="tab-pane fade" id="tabdocument" role="tabpanel"></div>
            <div class="tab-pane fade" id="tabEmpMeta" role="tabpanel"></div>
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
                    <!-- <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Close</button> -->
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

    function closePanel() {
        document.getElementById("sidePanel").classList.remove("open");
        document.getElementById("overlay").classList.remove("show");
        document.body.style.overflow = '';
    }

    // === Accordion Toggle ===
    function toggleAccordion(element) {
        const panel = element.nextElementSibling;

        if (panel.classList.contains("open")) {
            // If it's already open, close it
            element.classList.remove("active");
            panel.classList.remove("open");
            panel.style.maxHeight = null;
        } else {
            // Open this one (without closing others)
            element.classList.add("active");
            panel.classList.add("open");
            panel.style.maxHeight = panel.scrollHeight + "px";
        }
    }

    // === Auto Open Accordion with Validation Errors ===
    document.addEventListener("DOMContentLoaded", function() {
        @if($errors->any())
            // Open panel if any validation fails
            openPanel();

            // Check which accordion to open
            @if($errors->hasAny(['employee_code','name','mobile_number','email','gender','dob','addressline1','state','city','pincode']))
                toggleAccordion(document.getElementById('basicAccordion'));
            @endif

            @if($errors->hasAny(['department_id','designation','employment_type','salary_type','doj']))
                toggleAccordion(document.getElementById('jobAccordion'));
            @endif

            @if($errors->hasAny(['emergency_contact_number','contact_person_name','relation_with_contact']))
                toggleAccordion(document.getElementById('contactAccordion'));
            @endif

            @if($errors->hasAny(['aadhaar_card','pan_card','driving_license','passport_photo']))
                toggleAccordion(document.getElementById('legalAccordion'));
            @endif

            @if($errors->hasAny(['bank_name','branch_name','account_number','ifsc_code']))
                toggleAccordion(document.getElementById('bankAccordion'));
            @endif

            @if($errors->hasAny(['reference_name','reference_contact_number']))
                toggleAccordion(document.getElementById('referenceAccordion'));
            @endif

            // ✅ Focus first invalid input
            const firstError = document.querySelector(".text-danger");
            if(firstError){
                const input = firstError.previousElementSibling; 
                if(input && input.focus){
                    input.focus();
                }
            }
        @endif
    });

    function editEmployee(id) {
        // Fetch employee data and open edit modal
        $.ajax({
            url: '/employee-details/' + id,
            method: 'GET',
            success: function(employee) {
                // Populate the form with existing data
                populateEditForm(employee);
                // Open the side panel for editing
                openPanel();
            },
            error: function() {
                alert('Failed to load employee data');
            }
        });
    }

    function populateEditForm(employee) {
        // Change form action to update route
        $('#employeeForm').attr('action', '/employees/' + employee.id + '/update');
        $('#employeeForm').append('<input type="hidden" name="_method" value="PUT">');

        // Populate all form fields with employee data
        $('input[name="employee_code"]').val(employee.employee_code);
        $('input[name="name"]').val(employee.name);
        $('input[name="mobile_number"]').val(employee.mobile_number);
        $('input[name="email"]').val(employee.email);
        $('select[name="department_id"]').val(employee.department_id);
        $('select[name="designation"]').val(employee.designation);
        $('input[name="gender"][value="' + employee.gender + '"]').prop('checked', true);
        $('input[name="dob"]').val(employee.dob);
        $('textarea[name="addressline1"]').val(employee.addressline1);
        $('textarea[name="addressline2"]').val(employee.addressline2);
        $('input[name="state"]').val(employee.state);
        $('input[name="city"]').val(employee.city);
        $('input[name="pincode"]').val(employee.pincode);
        $('select[name="employment_type"]').val(employee.employment_type);
        $('select[name="salary_type"]').val(employee.salary_type);
        $('input[name="doj"]').val(employee.doj);
        $('input[name="bank_name"]').val(employee.bank_name);
        $('input[name="branch_name"]').val(employee.branch_name);
        $('input[name="account_number"]').val(employee.account_number);
        $('input[name="ifsc_code"]').val(employee.ifsc_code);
        $('input[name="emergency_contact_number"]').val(employee.emergency_contact_number);
        $('input[name="contact_person_name"]').val(employee.contact_person_name);
        $('input[name="relation_with_contact"]').val(employee.relation_with_contact);
        $('input[name="reference_name"]').val(employee.reference_name);
        $('input[name="reference_contact_number"]').val(employee.reference_contact_number);

        // Update panel header for edit mode
        $('.panel-header').text('Edit Employee Details');
    }

    // Reset form when opening for new employee
    function openPanel() {
        document.getElementById("sidePanel").classList.add("open");
        document.getElementById("overlay").classList.add("show");
        document.body.style.overflow = 'hidden';

        // Reset form for new employee
        $('#employeeForm').attr('action', '{{ route("employees.store") }}');
        $('#employeeForm input[name="_method"]').remove();
        $('#employeeForm')[0].reset();
        $('.panel-header').text('Add Employee Details');
    }

    function viewEmployee(id) {
        let panel = document.getElementById("viewEmployeePanel");
        panel.style.right = "0"; // open panel

        // reset
        $("#tabEmpBasic, #tabEmpJob, #tabdocument, #tabEmpMeta").html("<p>Loading...</p>");

        $.ajax({
            url: '/employee-details/' + id,
            method: 'GET',
            success: function(e) {
                // BASIC TAB
                $('#tabEmpBasic').html(`
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th class="bg-light" style="width:35%;">Employee Code</th><td>${e.employee_code || '-'}</td></tr>
                                <tr><th class="bg-light">Name</th><td>${e.name || '-'}</td></tr>
                                <tr><th class="bg-light">Gender</th><td>${e.gender || '-'}</td></tr>
                                <tr><th class="bg-light">Email</th><td>${e.email || '-'}</td></tr>
                                <tr><th class="bg-light">Phone</th><td>${e.mobile_number  || '-'}</td></tr>
                                <tr><th class="bg-light">DOB</th><td>${e.dob || '-'}</td></tr>
                                <tr><th class="bg-light">Emergency Contact No.</th><td>${e.emergency_contact_number || '-'}</td></tr>
                                <tr><th class="bg-light">Contact Person Name</th><td>${e.contact_person_name || '-'}</td></tr>
                                <tr><th class="bg-light">Relation with Contact</th><td>${e.relation_with_contact || '-'}</td></tr>
                                <tr>
                                    <th class="bg-light" style="width:35%;">Address</th>
                                    <td>
                                        ${[
                                            e.addressline1,
                                            e.addressline2,
                                            e.city,
                                            e.state,
                                            e.pincode
                                        ].filter(Boolean).join(', ') || '-'}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            `);

                // JOB TAB
                $('#tabEmpJob').html(`
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th class="bg-light" style="width:35%;">Department</th><td>${e.department?.department || '-'}</td></tr>
                                <tr><th class="bg-light">Designation</th><td>${e.designation || '-'}</td></tr>
                                <tr><th class="bg-light">Joining Date</th><td>${e.doj || '-'}</td></tr>
                                <tr><th class="bg-light">Employment Type</th><td>${e.employment_type || '-'}</td></tr>
                                <tr><th class="bg-light">Salary Type</th><td>${e.salary_type || '-'}</td></tr>
                                <tr><th class="bg-light">Reference Name</th><td>${e.reference_name || '-'}</td></tr>                    
                                <tr><th class="bg-light">Reference Contact Number</th><td>${e.reference_contact_number || '-'}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            `);

                // DOCUMENT TAB
                $('#tabdocument').html(`
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="bg-light" style="width:35%;">Aadhaar Card</th>
                                    <td>
                                        ${e.aadhaar_card 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${e.aadhaar_card}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Pan Card</th>
                                    <td>
                                        ${e.pan_card 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${e.pan_card}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Driving License</th>
                                    <td>
                                        ${e.driving_license 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${e.driving_license}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Passport Photo</th>
                                    <td>
                                        ${e.passport_photo 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${e.passport_photo}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            `);

                // META TAB
                $('#tabEmpMeta').html(`
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <table class="table table-bordered">
                            <tbody>
                            <tr><th class="bg-light">Bank Name</th><td>${e.bank_name || '-'}</td></tr>
                            <tr><th class="bg-light">Branch Name</th><td>${e.branch_name || '-'}</td></tr>
                            <tr><th class="bg-light">Account Number</th><td>${e.account_number || '-'}</td></tr>
                            <tr><th class="bg-light">IFSC Code</th><td>${e.ifsc_code || '-'}</td></tr>
                                <tr><th class="bg-light" style="width:35%;">Created At</th><td>${e.created_at || '-'}</td></tr>
                                <tr><th class="bg-light">Updated At</th><td>${e.updated_at || '-'}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            `);
            },
            error: function() {
                alert("Failed to load employee details.");
            }
        });
    }

    function closeViewPanel() {
        document.getElementById("viewEmployeePanel").style.right = "-100%";
    }

    // Event Delegation for all "View" buttons
    $(document).on('click', '.view-doc', function() {
        let filePath = $(this).data('file');
        let previewHtml = '';

        // Add a timestamp to prevent caching
        let cacheBuster = '?t=' + new Date().getTime();

        if (filePath.match(/\.(jpg|jpeg|png|gif|webp|bmp)$/i)) {
            previewHtml =
                `<img src="${filePath}${cacheBuster}" class="img-fluid" alt="Document" style="max-height: 80vh;">`;
        } else if (filePath.match(/\.(pdf)$/i)) {
            previewHtml =
                `<iframe src="${filePath}${cacheBuster}#view=fitH" width="100%" height="600px" frameborder="0"></iframe>`;
        } else {
            previewHtml =
                `<div class="p-3"><a href="${filePath}" target="_blank" class="btn btn-primary">Open Document</a></div>`;
        }

        $('#docPreview').html(previewHtml);
        $('#docModal').modal('show');
    });

    function openSignupModal(id) {
        // Show modal
        $("#signupModal").modal("show");
        // Show loading while fetching
        $("#signupFormContainer").html("<p>Loading...</p>");
        $.ajax({
            url: '/employee-details/' + id,
            method: 'GET',
            success: function(e) {
                // Inject form dynamically inside modal body
                $("#signupFormContainer").html(`
                        <form id="signupForm">
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" value="${e.name || ''}" class="form-control" placeholder="Enter your full name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" value="${e.email || ''}" class="form-control" placeholder="Enter email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile_number" value="${e.mobile_number || ''}" class="form-control" placeholder="Enter mobile number" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-control" required>
                                    <option value="">-- Select Role --</option>
                                    <option value="manager" ${e.role === 'manager' ? 'selected' : ''}>Manager</option>
                                    <option value="supervisor" ${e.role === 'supervisor' ? 'selected' : ''}>Supervisor</option>
                                    <option value="employee" ${e.role === 'employee' ? 'selected' : ''}>Employee</option>
                                </select>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#">Terms & Conditions</a>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Signup</button>
                        </form>
                    `);
                // Form submit handler
                $("#signupForm").on("submit", function(ev) {
                    ev.preventDefault();
                    let formData = $(this).serialize();
                    $.ajax({
                        url: "/create-multiple-user", // change if needed
                        method: "POST",
                        data: formData,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function(res) {
                            alert("Signup successful!");
                            $("#signupModal").modal("hide"); // close modal after success
                        },
                        error: function(xhr) {
                            alert("Signup failed: " + (xhr.responseJSON?.message || "Unknown error"));
                        }
                    });
                });
            },
            error: function() {
                $("#signupFormContainer").html("<p class='text-danger'>Failed to load employee details.</p>");
            }
        });
    }
    </script>
</body>
@endsection
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Loan Schema Configuration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    body {
        min-height: 100vh;
        padding: 50px 0;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .container {
        max-width: 1000px;
        margin: auto;
    }

    .card {
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        border: none;
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px 20px 0 0 !important;
        padding: 20px;
        font-size: 24px;
        font-weight: bold;
    }

    .form-label {
        font-weight: 600;
        color: #333;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px;
        font-weight: bold;
        font-size: 16px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .loading {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1000;
        background: rgba(0, 0, 0, 0.7);
        padding: 20px;
        border-radius: 10px;
        color: white;
    }

    select:disabled {
        background-color: #e9ecef;
        cursor: not-allowed;
    }

    .info-box {
        background: #f8f9fa;
        border-left: 4px solid #28a745;
        padding: 15px;
        margin-top: 20px;
        border-radius: 10px;
    }

    .required-field::after {
        content: " *";
        color: red;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="loading" id="loading">
            <div class="spinner-border text-light" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading data...</p>
        </div>

        <div class="card">
            <div class="card-header text-center">
                🏫 Loan Schema Configuration
            </div>
            <div class="card-body p-4">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form action="{{ route('loan-schema.store') }}" method="POST" id="loanSchemaForm">
                    @csrf

                    <!-- Institute Selection -->
                    <div class="mb-4">
                        <label class="form-label required-field">Select Institute</label>
                        <select name="institute_id" id="institute_id" class="form-select" required>
                            <option value="">-- Choose Institute --</option>
                            @foreach($institutes as $institute)
                            <option value="{{ $institute->fincap_merchant_id }}">
                                {{ $institute->name }} (ID: {{ $institute->fincap_merchant_id }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Department Selection -->
                    <div class="mb-4">
                        <label class="form-label">Select Department</label>
                        <select name="department_id" id="department_id" class="form-select" disabled>
                            <option value="">-- First Select Institute --</option>
                        </select>
                        <input type="hidden" name="department_name" id="department_name">
                    </div>

                    <!-- Course Selection (Course Type from your existing system) -->
                    <div class="mb-4">
                        <label class="form-label">Select Course</label>
                        <select name="course_type_id" id="course_id" class="form-select" disabled>
                            <option value="">-- First Select Department --</option>
                        </select>
                        <input type="hidden" name="course_name" id="course_name">
                        <small class="text-muted">This is the main course/program</small>
                    </div>

                    <!-- Course Sub Type / Branch Selection -->
                    <div class="mb-4">
                        <label class="form-label">Select Course Branch / Sub Type</label>
                        <select name="course_subtype_id" id="course_type_id" class="form-select" disabled>
                            <option value="">-- First Select Course --</option>
                        </select>
                        <input type="hidden" name="course_type_name" id="course_type_name">
                        <small class="text-muted">Specific branch or specialization of the course</small>
                    </div>

                    <!-- Loan Schema Details -->
                    <div class="row mt-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Tenure</label>
                            <input type="number" name="tenure" id="tenure" class="form-control">
                            
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Subvention Rate (%)</label>
                            <input type="number" step="any" name="subvention_rate" id="subvention_rate"
                                class="form-control" placeholder="e.g., 5.9">
                            <small class="text-muted">Interest subvention rate</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">ROI Rate (%)</label>
                            <input type="number" step="any" name="roi_rate" id="roi_rate" class="form-control"
                                placeholder="e.g., 8.5">
                            <small class="text-muted">Return on Investment rate</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Number of EMIs</label>
                            <input type="number" name="no_of_emis" id="no_of_emis" class="form-control"
                                placeholder="e.g., 12">
                            <small class="text-muted">Number of EMIs for downpayment</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Processing Fee (%)</label>
                            <input type="number" step="any" name="processing_fee_percent" id="processing_fee_percent"
                                class="form-control" placeholder="e.g., 2.5">
                            <small class="text-muted">Processing fee as percentage</small>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Processing Fee Amount (₹)</label>
                            <input type="number" step="any" name="processing_fee_amount" id="processing_fee_amount"
                                class="form-control" placeholder="e.g., 5000">
                            <small class="text-muted">Fixed processing fee amount</small>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-submit">
                            💾 Save 
                        </button>
                    </div>
                </form>

                <div class="info-box" id="infoBox" style="display: none;">
                    <strong>📌 Loan Schema Status:</strong>
                    <div id="loanSchemaInfo"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        let currentInstituteId = null;
        let currentDepartmentId = null;
        let currentCourseId = null;

        // When institute changes
        $('#institute_id').change(function() {
            currentInstituteId = $(this).val();
            if (currentInstituteId) {
                resetDepartmentAndCourseFields();
                loadDepartmentsByInstitute(currentInstituteId);
            } else {
                resetAllFields();
            }
        });

        // Function to load departments by institute
        function loadDepartmentsByInstitute(instituteId) {
            $('#loading').show();

            $.ajax({
                url: '{{ route("ajax.departments.by.institute") }}',
                type: 'GET',
                data: {
                    institute_id: instituteId
                },
                dataType: 'json',
                success: function(response) {
                    var departmentSelect = $('#department_id');
                    departmentSelect.empty();

                    if (response.success && response.departments && response.departments.length >
                        0) {
                        departmentSelect.append(
                        '<option value="">-- Select Department --</option>');
                        $.each(response.departments, function(key, department) {
                            departmentSelect.append('<option value="' + department
                                .department_id + '">' +
                                department.department + '</option>');
                        });
                        departmentSelect.prop('disabled', false);
                    } else {
                        departmentSelect.append('<option value="">No departments found</option>');
                        departmentSelect.prop('disabled', true);
                    }
                    $('#loading').hide();
                },
                error: function(xhr, status, error) {
                    $('#loading').hide();
                    console.error('Error loading departments:', xhr.responseJSON);

                    // Check if it's an authentication error
                    if (xhr.status === 401) {
                        alert('Session expired. Please refresh the page and login again.');
                        window.location.reload();
                    } else {
                        alert('Error loading departments. Please try again.');
                    }
                }
            });
        }

        // Function to load courses by department
        function loadCoursesByDepartment(departmentId) {
            $('#loading').show();

            $.ajax({
                url: '{{ route("ajax.course.types.by.department") }}',
                type: 'GET',
                data: {
                    department_id: departmentId
                },
                dataType: 'json',
                success: function(response) {
                    var courseSelect = $('#course_id');
                    courseSelect.empty();

                    if (response.status === 'success' && response.courses && response.courses
                        .length > 0) {
                        courseSelect.append('<option value="">-- Select Course --</option>');
                        $.each(response.courses, function(key, course) {
                            courseSelect.append('<option value="' + course
                                .finacp_merchant_sub_category_id + '">' +
                                course.finacp_merchant_sub_category_type + '</option>');
                        });
                        courseSelect.prop('disabled', false);
                    } else {
                        courseSelect.append('<option value="">No courses found</option>');
                        courseSelect.prop('disabled', true);
                    }
                    $('#loading').hide();
                },
                error: function(xhr) {
                    $('#loading').hide();
                    console.error('Error loading courses:', xhr.responseJSON);
                    if (xhr.status === 401) {
                        alert('Session expired. Please refresh the page.');
                    }
                }
            });
        }

        // Function to load course branches
        function loadCourseBranches(departmentId, courseType) {
            $('#loading').show();

            $.ajax({
                url: '{{ route("ajax.schema.branch.by.course") }}',
                type: 'GET',
                data: {
                    department_id: departmentId,
                    course_type: courseType
                },
                dataType: 'json',
                success: function(response) {
                    var branchSelect = $('#course_type_id');
                    branchSelect.empty();

                    if (response.status === 'success' && response.branches && response.branches
                        .length > 0) {
                        branchSelect.append('<option value="">-- Select Course Branch --</option>');
                        $.each(response.branches, function(key, branch) {
                            branchSelect.append('<option value="' + branch.product_id +
                                '">' +
                                branch.sub_type + '</option>');
                        });
                        branchSelect.prop('disabled', false);
                    } else {
                        branchSelect.append('<option value="">No branches found</option>');
                        branchSelect.prop('disabled', true);
                    }
                    $('#loading').hide();
                },
                error: function(xhr) {
                    $('#loading').hide();
                    console.error('Error loading branches:', xhr.responseJSON);
                    var branchSelect = $('#course_type_id');
                    branchSelect.empty();
                    branchSelect.append('<option value="">No branches available</option>');
                    branchSelect.prop('disabled', true);
                }
            });
        }

        // Reset functions
        function resetDepartmentAndCourseFields() {
            $('#department_id').html('<option value="">-- First Select Institute --</option>').prop('disabled',
                true);
            $('#course_id').html('<option value="">-- First Select Department --</option>').prop('disabled',
                true);
            $('#course_type_id').html('<option value="">-- First Select Course --</option>').prop('disabled',
                true);
            $('#department_name').val('');
            $('#course_name').val('');
            $('#course_type_name').val('');
        }

        function resetCourseFields() {
            $('#course_id').html('<option value="">-- Select Course --</option>').prop('disabled', true);
            $('#course_type_id').html('<option value="">-- First Select Course --</option>').prop('disabled',
                true);
            $('#course_name').val('');
            $('#course_type_name').val('');
        }

        function resetAllFields() {
            resetDepartmentAndCourseFields();
            $('#tenure').val('');
            $('#subvention_rate').val('');
            $('#roi_rate').val('');
            $('#no_of_emis').val('');
            $('#processing_fee_percent').val('');
            $('#processing_fee_amount').val('');
            $('#infoBox').hide();
        }

        // Event handlers
        $('#department_id').change(function() {
            currentDepartmentId = $(this).val();
            var departmentName = $('#department_id option:selected').text();
            $('#department_name').val(departmentName);

            if (currentDepartmentId) {
                loadCoursesByDepartment(currentDepartmentId);
                resetCourseFields();
            } else {
                $('#course_id').html('<option value="">-- First Select Department --</option>').prop(
                    'disabled', true);
                $('#course_type_id').html('<option value="">-- First Select Course --</option>').prop(
                    'disabled', true);
            }
        });

        $('#course_id').change(function() {
            currentCourseId = $(this).val();
            var courseName = $('#course_id option:selected').text();
            $('#course_name').val(courseName);

            if (currentCourseId) {
                loadCourseBranches(currentDepartmentId, currentCourseId);
            } else {
                $('#course_type_id').html('<option value="">-- First Select Course --</option>').prop(
                    'disabled', true);
            }
        });

    });
    </script>
</body>

</html>
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .file-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .required::after {
        content: " *";
        color: #dc3545;
    }
</style>

<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-upload me-2"></i> Bulk Upload Employees</h4>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('employee.bulk.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="file-info mb-4">
                    <h5><i class="bi bi-info-circle text-primary"></i> File Format Information</h5>
                    <p class="mb-2">Your CSV file must contain these columns (case-insensitive):</p>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="mb-0">
                                <li><strong>SN</strong> (Optional - Serial Number)</li>
                                <li><strong>Mobile Number</strong> <span class="text-danger">*Required</span> (10 digits)</li>
                                <li><strong>First Name</strong> <span class="text-danger">*Required</span></li>
                                <li><strong>Date of Birth</strong> <span class="text-danger">*Required</span> (DD-MM-YYYY)</li>
                                <li><strong>Gender</strong> (Optional - Male/Female/Other)</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="mb-0">
                                <li><strong>User Role</strong> (Optional - will use designation's role)</li>
                                <li><strong>Address Line 1</strong> (Optional)</li>
                                <li><strong>E-mail ID</strong> (Optional - for login credentials)</li>
                                <li><strong>Department</strong> (Optional - will use selected department)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold required">Default Department:</label>
                        <select name="default_department_id" class="form-control" required>
                            <option value="">-- Select Department --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->department_id }}">
                                    {{ $dept->department }} 
                                    @if($dept->category)
                                        - ({{ $dept->category->category_name }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold required">Default Designation:</label>
                        <select name="default_designation_id" class="form-control" required>
                            <option value="">-- Select Designation --</option>
                            @foreach($designations as $desig)
                                <option value="{{ $desig->designation_id }}">
                                    {{ $desig->designations }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Employment Type (Optional):</label>
                        <select name="default_employment_type" class="form-control">
                            <option value="">-- Select Type (Optional) --</option>
                            <option value="Full-time">Full-time</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Contract-based">Contract-based</option>
                        </select>
                        <div class="form-text">If not selected, defaults to "Full-time"</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Salary Type (Optional):</label>
                        <select name="default_salary_type" class="form-control">
                            <option value="">-- Select Type (Optional) --</option>
                            <option value="Monthly">Monthly</option>
                            <option value="Hourly">Hourly</option>
                        </select>
                        <div class="form-text">If not selected, defaults to "Monthly"</div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold required">Upload CSV File:</label>
                        <input type="file" name="csv_file" class="form-control" accept=".csv,.txt" required>
                        <div class="form-text">Max file size: 10MB. All employees will be assigned to the selected department and designation above.</div>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <h6><i class="bi bi-exclamation-triangle"></i> Important Notes:</h6>
                    <ol class="mb-0">
                        <li><strong>Login Credentials:</strong> If email is provided, employees will receive login credentials</li>
                        <li><strong>Username:</strong> Email ID (if provided) or Mobile Number</li>
                        <li><strong>Password:</strong> Default password is <code>12345678</code></li>
                        <li><strong>Status:</strong> All employees will be marked as 'active'</li>
                        <li><strong>Default Values:</strong> 
                            <ul class="mb-0">
                                <li>Nationality: 'Indian'</li>
                                <li>Employment Type: 'Full-time' (if not specified)</li>
                                <li>Salary Type: 'Monthly' (if not specified)</li>
                            </ul>
                        </li>
                    </ol>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('employee.bulk.upload.template') }}" class="btn btn-outline-primary">
                        <i class="bi bi-download"></i> Download CSV Template
                    </a>
                    
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-upload"></i> Upload Employees
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "-- Select --",
        width: '100%'
    });
});
</script>
@endsection
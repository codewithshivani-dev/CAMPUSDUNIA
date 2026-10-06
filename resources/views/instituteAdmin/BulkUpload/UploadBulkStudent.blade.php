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
            <h4 class="mb-0"><i class="bi bi-upload me-2"></i> Bulk Upload Students</h4>
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

            <form action="{{ route('student.bulk.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="file-info mb-4">
                    <h5><i class="bi bi-info-circle text-primary"></i> File Format Information</h5>
                    <p class="mb-2">Your CSV file must contain these columns (case-insensitive):</p>
                    <!-- <div class="row">
                        <div class="col-md-6">
                            <ul class="mb-0">
                                <li><strong>REGISTRATION NO.</strong> (Optional)</li>
                                <li><strong>STUDENT NAME</strong> <span class="text-danger">*Required</span></li>
                                <li><strong>MOTHER NAME</strong> (Optional)</li>
                                <li><strong>SECTION</strong> (Optional - A, B, C, etc.)</li>
                                <li><strong>DOB</strong> <span class="text-danger">*Required</span> (DD-MM-YYYY)</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="mb-0">
                                <li><strong>ADDRESS</strong> (Optional)</li>
                                <li><strong>FATHER NAME</strong> <span class="text-danger">*Required</span></li>
                                <li><strong>FATHER CONTACT</strong> <span class="text-danger">*Required</span> (10 digits)</li>
                                <li><strong>MOTHER CONTACT</strong> (Optional - 10 digits)</li>
                                <li><strong>Note:</strong> CLASS column will be ignored (selected below)</li>
                            </ul>
                        </div>
                    </div> -->
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold required">Select Class:</label>
                        <select name="course_detail_id" class="form-control" required>
                            <option value="">-- Select Class --</option>
                            @foreach($subTypes as $subType)
                                <option value="{{ $subType->product_id }}">
                                    {{ $subType->sub_type }} ({{ $subType->course_type }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">All students will be assigned to this class</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold required">Upload CSV File:</label>
                        <input type="file" name="csv_file" class="form-control" accept=".csv,.txt" required>
                        <div class="form-text">Max file size: 10MB</div>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <h6><i class="bi bi-exclamation-triangle"></i> Important Notes:</h6>
                    <ol class="mb-0">
                        <li><strong>Login Credentials:</strong> Students will login using Father's Contact number (or Mother's if Father's is not available)</li>
                        <li><strong>Password:</strong> Default password is <code>12345678</code></li>
                        <li><strong>Name Parsing:</strong> Student names will be split into First, Middle, Last names automatically</li>
                    </ol>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('bulk.upload.template') }}" class="btn btn-outline-primary">
                        <i class="bi bi-download"></i> Download CSV Template
                    </a>
                    
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-upload"></i> Upload Students
                    </button>
                    <!--<a href="{{ route('update.student.email') }}" -->
                    <!--    class="btn btn-primary"-->
                    <!--    onclick="return confirm('Are you sure you want to update student emails?')">-->
                    <!--    Update Student Emails-->
                    <!--</a>-->
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
        placeholder: "-- Select Class --",
        width: '100%'
    });
});
</script>
@endsection
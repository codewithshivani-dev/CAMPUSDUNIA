@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<!-- Add Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --success-color: #10b981;
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
        --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
    }

    /* Page Header - Enhanced */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 30px;
        background: var(--primary-gradient);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .page-title i {
        font-size: 32px;
        filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
    }

    /* Main Card */
    .main-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .card-header {
        background: var(--primary-gradient);
        border-bottom: none;
        padding: 20px 30px;
    }

    .card-header h4 {
        font-weight: 700;
        font-size: 20px;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header h4 i {
        font-size: 24px;
        background: rgba(255, 255, 255, 0.2);
        padding: 8px;
        border-radius: 12px;
    }

    .card-body {
        padding: 30px;
        background: white;
    }

    /* Form Labels */
    .form-label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .form-label.fw-bold {
        color: var(--primary-color);
    }

    /* Form Controls - Enhanced */
    .form-control,
    .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
        height: auto;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    .form-control:hover,
    .form-select:hover {
        border-color: var(--secondary-color);
    }

    .form-control:disabled,
    .form-select:disabled {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-color: #e2e8f0;
    }

    /* Input Group */
    .input-group-text {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px solid #e2e8f0;
        border-right: none;
        border-radius: 10px 0 0 10px;
        color: var(--primary-color);
        font-weight: 500;
    }

    .input-group .form-control {
        border-left: none;
        border-radius: 0 10px 10px 0;
    }

    /* Student List Container */
    #studentList {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9) !important;
        border: 2px solid #e2e8f0 !important;
        border-radius: 12px !important;
        padding: 20px !important;
        max-height: 350px;
        overflow-y: auto;
    }

    #studentList::-webkit-scrollbar {
        width: 8px;
    }

    #studentList::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    #studentList::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        border-radius: 4px;
    }

    .student-list .form-check {
        padding: 12px;
        margin-bottom: 8px;
        border-radius: 10px;
        background: white;
        border: 1px solid #e2e8f0;
        transition: all 0.3s;
    }

    .student-list .form-check:hover {
        transform: translateX(5px);
        border-color: var(--primary-color);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.1);
    }

    .student-list .form-check-input {
        width: 18px;
        height: 18px;
        margin-right: 10px;
        cursor: pointer;
        border: 2px solid #cbd5e1;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .student-list .form-check-input:hover {
        border-color: var(--primary-color);
        transform: scale(1.1);
    }

    .student-list .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
    }

    .student-list .form-check-label {
        cursor: pointer;
        color: #334155;
        font-weight: 500;
    }

    .student-list .form-check-label strong {
        color: var(--primary-color);
        font-weight: 600;
    }

    /* Select All Checkbox */
    #selectAllStudents {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border: 2px solid #cbd5e1;
        border-radius: 4px;
        margin-right: 8px;
    }

    #selectAllStudents:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    label[for="selectAllStudents"] {
        font-weight: 600;
        color: var(--primary-color);
        cursor: pointer;
    }

    /* Leave Rows */
    .leave-row {
        padding: 15px;
        margin-bottom: 10px;
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s;
        animation: fadeIn 0.3s ease-out;
    }

    .leave-row:hover {
        border-color: var(--primary-color);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.1);
        transform: translateY(-2px);
    }

    .leave-row .col-md-5,
    .leave-row .col-md-4,
    .leave-row .col-md-2 {
        display: flex;
        align-items: center;
    }

    /* Buttons */
    .btn {
        border-radius: 10px;
        font-weight: 500;
        padding: 10px 20px;
        transition: all 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .btn:active {
        transform: translateY(-1px);
    }

    .btn-primary {
        background: var(--primary-gradient);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-primary:hover::before {
        left: 100%;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        color: #475569;
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        color: #1e293b;
    }

    .btn-success {
        background: var(--success-gradient);
        color: white;
    }

    .btn-danger {
        background: var(--danger-gradient);
        color: white;
    }

    .btn-outline-secondary {
        background: transparent;
        border: 2px solid #e2e8f0;
        color: #475569;
    }

    .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-color: #cbd5e1;
        color: #1e293b;
        transform: translateY(-2px);
    }

    .btn-outline-secondary:disabled {
        background: #f1f5f9;
        border-color: #e2e8f0;
        color: #94a3b8;
        cursor: not-allowed;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    /* Add More Button */
    #addRow {
        background: transparent;
        border: 2px dashed var(--primary-color);
        color: var(--primary-color);
        transition: all 0.3s;
    }

    #addRow:hover {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
    }

    /* Custom Leave Container */
    #customLeaveContainer {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9) !important;
        border: 2px solid #e2e8f0 !important;
        border-radius: 12px !important;
        padding: 20px !important;
        margin-top: 15px !important;
        border-left: 4px solid var(--warning-gradient) !important;
    }

    /* Checkbox Styling */
    .form-check-input {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border: 2px solid #cbd5e1;
        border-radius: 4px;
        transition: all 0.2s;
        margin-top: 0;
    }

    .form-check-input:hover {
        border-color: var(--primary-color);
        transform: scale(1.1);
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .form-check-label {
        color: #475569;
        font-weight: 500;
        cursor: pointer;
    }

    .form-check-label.fw-bold {
        color: var(--primary-color);
    }

    /* Alert Messages - Enhanced */
    .alert {
        border: none;
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .alert-success {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        border: 1px solid #86efac;
        color: #166534;
    }

    .alert-danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border: 1px solid #fca5a5;
        color: #991b1b;
    }

    .alert-warning {
        background: linear-gradient(135deg, #fed7aa, #fdba74);
        border: 1px solid #fcd34d;
        color: #92400e;
    }

    .alert-info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border: 1px solid #93c5fd;
        color: #1e40af;
    }

    .alert .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Small text */
    small.text-muted {
        color: #94a3b8 !important;
        font-size: 12px;
        margin-top: 4px;
        display: block;
    }

    /* Loading Spinner */
    .spinner-border {
        width: 2rem;
        height: 2rem;
        border-width: 0.2rem;
        border-color: var(--primary-color);
        border-right-color: transparent;
    }

    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
        }

        .card-body {
            padding: 20px;
        }

        .leave-row .row {
            gap: 10px;
        }

        .leave-row .col-md-5,
        .leave-row .col-md-4,
        .leave-row .col-md-2 {
            width: 100%;
            margin-bottom: 8px;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        #addRow {
            width: 100%;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 10px;
        }

        .d-flex.justify-content-between .btn {
            width: 100%;
        }
    }
</style>

<div class="container-fluid">

    <!-- Alerts -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Main Card -->
    <div class="main-card">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-calendar-plus-fill"></i>
            Leave Quota<small style="color: white; font-size: 0.5em;">(annually)</small>
        </h1>
    </div>
        <div class="card-body">
            <form action="{{ route('student.leave.assign.store') }}" method="POST" id="assignLeaveForm">
                @csrf

                {{-- 🔹 Select Department --}}
                <div class="form-group mb-4">
                    <label for="department" class="form-label fw-bold">Select Department</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-building-fill text-primary"></i>
                        </span>
                        <select id="department" name="department_id" class="form-control" required>
                            <option value="">-- Select Department --</option>
                            @foreach($departments as $dept)
                            <option value="{{ $dept->department_id }}" {{ old('department_id') == $dept->department_id ? 'selected' : '' }}>
                                {{ $dept->department }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- 🔹 Student List (Loaded via AJAX) --}}
                <div class="form-group mb-4">
                    <label class="form-label fw-bold">Select Students</label>
                    <div id="studentList" class="border rounded p-3 bg-light">
                        <p class="text-muted mb-0">Select a department to view Students.</p>
                    </div>
                    <div class="mt-3">
                        <div class="form-check">
                            <input type="checkbox" id="selectAllStudents" class="form-check-input">
                            <label class="form-check-label fw-bold" for="selectAllStudents">Select All Students</label>
                        </div>
                    </div>
                </div>

                {{-- 🔹 Session Year --}}
                @php
                $month = date('m');
                $year = date('Y');
                $currentSessionStart = $month >= 4 ? $year : $year - 1;
                @endphp
                <div class="form-group mb-4">
                    <label for="session_year" class="form-label fw-bold">Session Year</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-calendar-fill text-primary"></i>
                        </span>
                        <input type="text" name="session_year" id="session_year" class="form-control"
                            value="{{ old('session_year', $currentSessionStart . '-' . ($currentSessionStart + 1)) }}" required>
                    </div>
                    <small class="text-muted">Format: YYYY-YYYY (e.g., 2024-2025)</small>
                </div>

                {{-- 🔹 Leave Type Rows --}}
                <h5 class="fw-bold mb-3" style="color: var(--primary-color);">
                    <i class="bi bi-list-check me-2"></i>Leave Types
                </h5>
                <div id="leaveRows">
                    @php
                    $leaveTypes = old('leave_types', [['type' => 'Sick', 'days' => '']]);
                    @endphp
                    
                    @foreach($leaveTypes as $index => $leaveType)
                    <div class="leave-row">
                        <div class="row">
                            <div class="col-md-5">
                                <select name="leave_types[{{ $index }}][type]" class="form-control" required>
                                    <option value="Sick" {{ $leaveType['type'] == 'Sick' ? 'selected' : '' }}>Sick Leave</option>
                                    <option value="Casual" {{ $leaveType['type'] == 'Casual' ? 'selected' : '' }}>Casual Leave</option>
                                    <option value="Earned" {{ $leaveType['type'] == 'Earned' ? 'selected' : '' }}>Earned Leave</option>
                                    <option value="Unpaid" {{ $leaveType['type'] == 'Unpaid' ? 'selected' : '' }}>Unpaid Leave</option>
                                    <option value="Medical" {{ $leaveType['type'] == 'Medical' ? 'selected' : '' }}>Medical Leave</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="leave_types[{{ $index }}][days]" class="form-control" 
                                       placeholder="No. of Days" value="{{ $leaveType['days'] }}" required min="0">
                            </div>
                            <div class="col-md-2">
                                @if($index == 0)
                                <button type="button" class="btn btn-outline-secondary" disabled>
                                    <i class="bi bi-check-lg"></i> Required
                                </button>
                                @else
                                <button type="button" class="btn btn-danger remove-row">
                                    <i class="bi bi-trash-fill"></i> Remove
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Add More Button --}}
                <button type="button" class="btn btn-secondary mt-2" id="addRow">
                    <i class="bi bi-plus-circle-fill me-1"></i>Add More Leave Types
                </button>

                {{-- ✅ Checkbox for Custom Leave --}}
                <div class="form-check mt-4">
                    <input class="form-check-input" type="checkbox" id="enableCustomLeave" {{ old('enableCustomLeave') ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold" for="enableCustomLeave">
                        <i class="bi bi-plus-square-fill me-1"></i>Add Custom Leave Type
                    </label>
                </div>

                <div id="customLeaveContainer" class="border rounded p-3 mt-2 bg-light" style="display: {{ old('enableCustomLeave') ? 'block' : 'none' }};">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <input type="text" name="custom_leave[type]" class="form-control"
                                placeholder="Enter Custom Leave Type" value="{{ old('custom_leave.type') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="custom_leave[days]" class="form-control" 
                                   placeholder="No. of Days" value="{{ old('custom_leave.days') }}" min="0">
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="addCustomLeave" class="btn btn-success w-100">
                                <i class="bi bi-plus-circle-fill me-1"></i>Add
                            </button>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset Form
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle-fill me-1"></i>Assign Leave to Students
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let leaveRowCounter = {{ count($leaveTypes) }};
    
    // 🔹 Load Students by Department (AJAX)
    document.getElementById('department').addEventListener('change', function() {
        const deptId = this.value;
        const studentList = document.getElementById('studentList');
        
        // Clear previous selection
        const selectAllCheckbox = document.getElementById('selectAllStudents');
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = false;
        }
        
        if (!deptId) {
            studentList.innerHTML = '<p class="text-muted mb-0">Select a department to view Students.</p>';
            return;
        }
        
        studentList.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 mb-0">Loading students...</p>
            </div>`;
        
        fetch(`/instituteAdmin/get-students/${deptId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Students data:', data); // For debugging
                
                if (data.error) {
                    studentList.innerHTML = `<div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>${data.error}</div>`;
                    return;
                }
                
                if (Array.isArray(data) && data.length > 0) {
                    let html = `<div class="student-list">`;
                    
                    data.forEach((student, index) => {
                        html += `
                            <div class="form-check mb-2">
                                <input type="checkbox" name="student_ids[]" value="${student.student_hash_id}" 
                                       class="student-checkbox form-check-input" id="student_${index}">
                                <label class="form-check-label" for="student_${index}">
                                    <strong>${student.registration_number || 'N/A'}</strong> - ${student.name}
                                    ${student.last_name ? ` ${student.last_name}` : ''}
                                </label>
                            </div>
                        `;
                    });
                    
                    html += `</div>`;
                    html += `<div class="mt-3 text-success"><i class="bi bi-check-circle-fill me-1"></i> Found ${data.length} student(s)</div>`;
                    
                    studentList.innerHTML = html;
                } else {
                    studentList.innerHTML = `
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> No students found in this department.
                        </div>`;
                }
            })
            .catch(err => {
                console.error('Error loading students:', err);
                studentList.innerHTML = `
                    <div class="alert alert-danger mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Error loading students. Please try again.
                        <br><small class="text-muted mt-1 d-block">${err.message}</small>
                    </div>`;
            });
    });
    
    // 🔹 Select All Students
    document.getElementById('selectAllStudents').addEventListener('change', function() {
        document.querySelectorAll('.student-checkbox').forEach(chk => {
            chk.checked = this.checked;
        });
    });
    
    // 🔹 Add new Leave Type Row
    document.getElementById('addRow').addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.classList.add('leave-row');
        newRow.innerHTML = `
            <div class="row">
                <div class="col-md-5">
                    <select name="leave_types[${leaveRowCounter}][type]" class="form-control" required>
                        <option value="Sick">Sick Leave</option>
                        <option value="Casual">Casual Leave</option>
                        <option value="Earned">Earned Leave</option>
                        <option value="Unpaid">Unpaid Leave</option>
                        <option value="Medical">Medical Leave</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" name="leave_types[${leaveRowCounter}][days]" class="form-control" 
                           placeholder="No. of Days" required min="0">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-row">
                        <i class="bi bi-trash-fill"></i> Remove
                    </button>
                </div>
            </div>
        `;
        document.getElementById('leaveRows').appendChild(newRow);
        leaveRowCounter++;
    });
    
    // 🔹 Remove Row (using event delegation)
    document.getElementById('leaveRows').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row') || e.target.closest('.remove-row')) {
            const row = e.target.closest('.leave-row');
            if (row && row.previousElementSibling) { // Don't remove the first row
                row.remove();
            }
        }
    });
    
    // ✅ Toggle Custom Leave input fields
    document.getElementById('enableCustomLeave').addEventListener('change', function() {
        const container = document.getElementById('customLeaveContainer');
        container.style.display = this.checked ? 'block' : 'none';
        
        if (!this.checked) {
            // Clear custom leave inputs when unchecked
            document.querySelector('input[name="custom_leave[type]"]').value = '';
            document.querySelector('input[name="custom_leave[days]"]').value = '';
        }
    });
    
    // ✅ Add Custom Leave Button
    document.getElementById('addCustomLeave').addEventListener('click', function() {
        const typeInput = document.querySelector('input[name="custom_leave[type]"]');
        const daysInput = document.querySelector('input[name="custom_leave[days]"]');
        const checkbox = document.getElementById('enableCustomLeave');
        
        if (!typeInput.value.trim() || !daysInput.value.trim()) {
            alert('Please enter both leave type and number of days');
            return;
        }
        
        const newRow = document.createElement('div');
        newRow.classList.add('leave-row');
        newRow.innerHTML = `
            <div class="row">
                <div class="col-md-5">
                    <input type="text" name="leave_types[${leaveRowCounter}][type]" 
                           value="${typeInput.value.trim()}" class="form-control" readonly>
                </div>
                <div class="col-md-4">
                    <input type="number" name="leave_types[${leaveRowCounter}][days]" 
                           value="${daysInput.value.trim()}" class="form-control" readonly>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-row">
                        <i class="bi bi-trash-fill"></i> Remove
                    </button>
                </div>
            </div>
        `;
        document.getElementById('leaveRows').appendChild(newRow);
        leaveRowCounter++;
        
        // Reset custom leave inputs
        typeInput.value = '';
        daysInput.value = '';
        checkbox.checked = false;
        document.getElementById('customLeaveContainer').style.display = 'none';
    });
    
    // ✅ Form validation before submission
    document.getElementById('assignLeaveForm').addEventListener('submit', function(e) {
        // Check if at least one student is selected
        const selectedStudents = document.querySelectorAll('.student-checkbox:checked');
        if (selectedStudents.length === 0) {
            e.preventDefault();
            alert('Please select at least one student.');
            return;
        }
        
        // Check if at least one leave type is filled
        const leaveTypeInputs = document.querySelectorAll('input[name$="[days]"]');
        let hasLeaveDays = false;
        leaveTypeInputs.forEach(input => {
            if (input.value && parseInt(input.value) > 0) {
                hasLeaveDays = true;
            }
        });
        
        if (!hasLeaveDays) {
            e.preventDefault();
            alert('Please add at least one leave type with days greater than 0.');
            return;
        }
        
        // Show loading indicator
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner-border me-1"></i>Processing...';
        submitBtn.disabled = true;
    });
    
    // Initialize Bootstrap tooltips if needed
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>
@endsection
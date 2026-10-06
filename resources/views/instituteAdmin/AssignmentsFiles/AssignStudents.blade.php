@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
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
}



/* Card Styles */
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    /*overflow: hidden;*/
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.98);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.12);
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
    background: var(--primary-gradient) !important;
    color: white;
    font-weight: 600;
    padding: 29px 20px;
    border: none;
}

.card-header h3 {
    font-size: 25px;
    font-weight:600;
    margin: 0;
}

.card-body {
    padding: 20px;
}

/* Alert Styles */
.alert {
    border-radius: 12px;
    border: none;
    padding: 18px 20px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}

.alert-info {
    background: linear-gradient(135deg, #e0f2fe, #bae6fd);
    border-left: 4px solid var(--info-gradient);
    color: #075985;
}

.alert-success {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    border-left: 4px solid var(--success-gradient);
    color: #065f46;
}

.alert-danger {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border-left: 4px solid var(--danger-gradient);
    color: #991b1b;
}

.alert-heading {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.alert strong {
    color: var(--primary-color);
}

/* Badge Styles */
.badge {
    font-size: 0.75rem;
    border-radius: 20px;
    font-weight: 600;
    letter-spacing: 0.3px;
}

.bg-primary {
    background: var(--primary-gradient) !important;
    color: white;
}

.bg-info {
    background: var(--info-gradient) !important;
    color: white;
}

.bg-warning {
    background: var(--warning-gradient) !important;
    color: white !important;
}

.bg-secondary {
    background: linear-gradient(135deg, #64748b, #475569) !important;
    color: white;
}

/* Table Styles */
.table-responsive {
    border-radius: 12px;
    overflow: hidden;
    margin-top: 15px;
}

.table {
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.85rem;
}

/*.table thead th {*/
/*    background: var(--primary-gradient) !important;*/
/*    color: white;*/
/*    font-weight: 600;*/
/*    text-transform: uppercase;*/
/*    font-size: 0.8rem;*/
/*    letter-spacing: 0.5px;*/
/*    padding: 14px 12px;*/
/*    border: none;*/
/*    white-space: nowrap;*/
/*}*/

.table thead th:first-child {
    border-top-left-radius: 12px;
}

.table thead th:last-child {
    border-top-right-radius: 12px;
}

.table tbody tr {
    background: white;
    border-bottom: 1px solid rgba(67, 97, 238, 0.1);
    transition: all 0.3s ease;
    cursor: pointer;
}

.table tbody tr:hover {
    background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(58, 12, 163, 0.05)) !important;
    transform: scale(1.002);
}

.table tbody td {
    padding: 12px;
    vertical-align: middle;
    border: none;
}

.table-active {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.08), rgba(5, 150, 105, 0.08)) !important;
    border-left: 3px solid var(--success-color) !important;
}

/* Checkbox Styles */
.form-check-input {
    width: 1.1rem;
    height: 1.1rem;
    cursor: pointer;
    border: 2px solid var(--primary-color);
    border-radius: 4px;
    transition: all 0.2s ease;
}

.form-check-input:checked {
    /*background: var(--success-gradient);*/
    /*border-color: var(--success-color);*/
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.form-check-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.student-checkbox {
    transform: scale(1.1);
}

#select_all {
    transform: scale(1.1);
}

.form-check-label {
    font-weight: 600;
    color: var(--primary-color);
    cursor: pointer;
}

/* Button Styles */
.btn {
    padding: 10px 20px;
    font-weight: 600;
    font-size: 0.85rem;
    border-radius: 40px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: none;
    position: relative;
    overflow: hidden;
}

.btn i {
    margin-right: 5px;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.2);
    transition: left 0.3s ease;
}

.btn:hover::before {
    left: 100%;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
}

.btn-success {
    background: var(--success-gradient);
    box-shadow: 0 3px 10px rgba(16, 185, 129, 0.25);
}

.btn-outline-secondary {
    background: transparent;
    border: 2px solid #64748b;
    color: #64748b;
}

.btn-outline-secondary:hover {
    background: linear-gradient(135deg, #64748b, #475569);
    border-color: transparent;
    color: white;
}

.btn-outline-primary {
    background: transparent;
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
}

.btn-outline-primary:hover {
    background: var(--primary-gradient);
    border-color: transparent;
    color: white;
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Loading Animation */
.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Selected Count Badge */
#selected_count {
    background: var(--primary-gradient);
    color: white;
    font-size: 0.9rem;
    padding: 4px 10px;
    border-radius: 20px;
    display: inline-block;
    min-width: 30px;
    text-align: center;
}

/* Empty State */
.table tbody td[colspan] {
    padding: 40px;
    text-align: center;
}

.table tbody td[colspan] i {
    color: var(--primary-color);
    opacity: 0.5;
}

/* Quick Actions Section */
.d-flex.justify-content-between {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    padding: 12px 15px;
    border-radius: 10px;
    margin-bottom: 15px;
}

/* Links */
a {
    color: var(--primary-color);
    text-decoration: none;
    transition: color 0.3s ease;
}

a:hover {
    color: var(--secondary-color);
}

/* Small Text */
small.text-muted {
    color: #64748b !important;
    font-size: 0.8rem;
}

/* Assignment Details Grid */
.alert-info .row {
    margin-top: 8px;
}

.alert-info .col-md-6 {
    padding: 5px 0;
}

.alert-info strong {
    color: var(--primary-color);
    margin-right: 5px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container-fluid {
        padding: 10px;
    }
    
    .card-header h4 {
        font-size: 1rem;
    }
    
    .btn {
        padding: 8px 15px;
        font-size: 0.8rem;
    }
    
    .table {
        font-size: 0.8rem;
    }
    
    .table thead {
        display: none;
    }
    
    .table tbody tr {
        display: block;
        margin-bottom: 10px;
        border: 1px solid rgba(67, 97, 238, 0.1);
        border-radius: 10px;
        padding: 10px;
    }
    
    .table tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 5px;
        text-align: right;
        border-radius: 0 !important;
    }
    
    .table tbody td::before {
        content: attr(data-label);
        font-weight: bold;
        color: var(--primary-color);
        margin-right: 10px;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 10px;
    }
    
    .alert-info .col-md-6 {
        margin-bottom: 5px;
    }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 8px;
}

::-webkit-scrollbar-thumb {
    background: var(--primary-gradient);
    border-radius: 8px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--secondary-color);
}

/* Text Styles */
.text-muted {
    color: #64748b !important;
}

.fw-bold {
    color: var(--primary-color);
}

/* Icons */
.fas, .far {
    color: inherit;
}

/* Animation for alerts */
.alert-dismissible {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

        /* ERP Table Styles */
        .erp-table {
            width: 100%;
            background: #fff;
            border-collapse: collapse;
        }
        
        .erp-table thead {
            background: var(--primary-gradient);
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .erp-table th {
            padding: 15px 8px;
            font-weight: 600;
            color: white;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            cursor: pointer;
            user-select: none;
            transition: background-color 0.2s;
            position: relative;
        }

        .erp-table th h6 {
            color: rgba(255,255,255,0.9);
            font-size: 11px;
            margin: 2px 0 0;
        }

        .erp-table th:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .erp-table th.sortable {
            padding-right: 30px;
        }

        .sort-icons {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sort-icon {
            color: rgba(255,255,255,0.5);
            font-size: 12px;
            line-height: 1;
        }

        .sort-icon.active {
            color: white;
        }

        .erp-table td {
            padding: 15px 8px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 14px;
            vertical-align: middle;
        }

        .erp-table tbody tr {
            transition: background-color 0.2s, transform 0.2s;
        }

        .erp-table tbody tr:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .erp-table tbody tr:last-child td {
            border-bottom: none;
        }
</style>

@php
    $courseLabel = (isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School')
    ? 'Class'
    : 'Course';
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="mb-0">
                        <i class="fas fa-tasks me-2"></i>Assign Assignment to Students
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Assignment Info -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>Assignment Details:
                        </h6>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong><i class="fas fa-heading me-1"></i>Title:</strong> 
                                <span class="fw-semibold">{{ $assignment->title }}</span>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="far fa-calendar-alt me-1"></i>Due Date:</strong>
                                <span class="fw-semibold">{{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong><i class="fas fa-layer-group me-1"></i>{{ $courseLabel }} Type:</strong>
                                <span class="badge bg-info">{{ $courseTypeName ?? 'N/A' }}</span>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fas fa-book me-1"></i>{{ $courseLabel }}:</strong>
                                <span class="badge bg-warning">{{ $subtypeTypeName ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong><i class="fas fa-calendar me-1"></i>Semester:</strong>
                                <span class="badge bg-secondary">{{ $assignmentDetails['semester'] ?? 'N/A' }}</span>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fas fa-book-open me-1"></i>Subject:</strong>
                                @php
                                    $subject = \App\Models\SubjectsCoursewise::where('subject_id', $assignment->subject_id)->first();
                                @endphp
                                <span class="fw-semibold">{{ $subject->subject_name ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <strong><i class="fas fa-align-left me-1"></i>Description:</strong>
                                <span>{{ $assignment->description ?? 'No description provided' }}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <strong><i class="fas fa-users me-1"></i>Total Available Students:</strong>
                                <span class="badge bg-primary">{{ $students->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Student Selection Form -->
                    <form action="{{ route('assignments.assignStudents', $assignment->id) }}" method="POST">
                        @csrf
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <!-- Quick Actions -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="select_all"
                                    {{ $students->count() == 0 ? 'disabled' : '' }}>
                                <label class="form-check-label fw-bold" for="select_all">
                                    <i class="fas fa-check-double me-1"></i>Select All Students
                                </label>
                            </div>
                            <div>
                                <span class="badge" id="selected_count">0</span> 
                                <span class="fw-semibold">students selected</span>
                                <!--<small class="text-muted ms-2">-->
                                <!--    <i class="fas fa-users me-1"></i>(Total: {{ $students->count() }} students)-->
                                <!--</small>-->
                            </div>
                        </div>

                        <div>
                            <!-- Students Table -->
                            <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                                <table class="erp-table table table-hover table-bordered" id="students_table">
                                    <thead>
                                        <tr>
                                            <th class="sticky-main-2 sortable" width="50px"><i class="fas fa-check-square me-1"></i>Select</th>
                                            <th class="sortable"><i class="fas fa-id-card me-1"></i>Registration No.</th>
                                            <th class="sortable"><i class="fas fa-user me-1"></i>Name</th>
                                            <th class="sortable"><i class="fas fa-envelope me-1"></i>Email</th>
                                            <th class="sortable"><i class="fas fa-layer-group me-1"></i>Section</th>
                                            <th class="sortable"><i class="fas fa-info-circle me-1"></i>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($students as $student)
                                        <tr>
                                            <td class="sticky-main-2">
                                                <div class="form-check">
                                                    <input class="form-check-input student-checkbox" type="checkbox"
                                                        name="student_ids[]" value="{{ $student->student_hash_id }}"
                                                        data-student-name="{{ $student->first_name }} {{ $student->last_name }}">
                                                </div>
                                            </td>
                                            <td>
                                                <span><i class="fas fa-hashtag me-1"></i>{{ $student->registration_number ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <strong><i class="fas fa-user-graduate me-1"></i>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                            </td>
                                            <td>
                                                <span><i class="far fa-envelope me-1"></i>{{ $student->email ?? 'N/A' }}</span>
                                            </td>
                                            <td><i class="fas fa-door-open me-1"></i>{{ $student->section_id ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-info me-1">{{ $assignmentDetails['course_type'] }}</span>
                                                <span class="badge bg-warning">{{ $assignmentDetails['course_subtype'] }}</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="fas fa-users fa-2x mb-3"></i>
                                                <p class="fw-bold mt-2">No students found for this {{ $courseLabel }} and semester combination.</p>
                                                <p class="small">
                                                    <i class="fas fa-tag me-1"></i>Course Type: <strong>{{ $assignmentDetails['course_type'] }}</strong> |
                                                    <i class="fas fa-book me-1"></i>{{ $courseLabel }}: <strong>{{ $assignmentDetails['course_subtype'] }}</strong> |
                                                    <i class="fas fa-calendar me-1"></i>Semester: <strong>{{ $assignmentDetails['semester'] }}</strong>
                                                </p>
                                                <a href="{{ route('assignments.create') }}"
                                                    class="btn btn-outline-primary mt-2">
                                                    <i class="fas fa-arrow-left me-2"></i>Back to Create Assignment
                                                </a>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Floating Horizontal Scrollbar -->
                            <div class="table-scroll-top" id="tableScrollTop">
                                <div class="table-scroll-inner"></div>
                            </div>
                        </div>
                        <!-- Submit Button -->
                        @if(count($students) > 0)
                            <div class="mt-4 text-end">
                                <a href="{{ route('assignments.index') }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Assignments
                                </a>
                                <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Assign to Students
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select_all');
    const checkboxes = document.querySelectorAll('.student-checkbox');
    const selectedCount = document.getElementById('selected_count');
    const submitBtn = document.getElementById('submitBtn');

    // Update selected count and button state
    function updateSelectedCount() {
        const selected = document.querySelectorAll('.student-checkbox:checked').length;
        selectedCount.textContent = selected;

        // Update button text with count
        if (submitBtn) {
            if (selected > 0) {
                submitBtn.innerHTML = `<i class="fas fa-paper-plane me-2"></i>Assign to ${selected} Student(s)`;
                submitBtn.disabled = false;
            } else {
                submitBtn.innerHTML = `<i class="fas fa-paper-plane me-2"></i>Assign to Students`;
                submitBtn.disabled = true;
            }
        }
    }

    // Select all functionality
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const isChecked = this.checked;
            checkboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
                // Add visual feedback
                checkbox.closest('tr').classList.toggle('table-active', isChecked);
            });
            updateSelectedCount();
        });
    }

    // Individual checkbox change
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Add visual feedback
            this.closest('tr').classList.toggle('table-active', this.checked);

            updateSelectedCount();

            // Update select all checkbox state
            if (selectAll) {
                const totalCheckboxes = checkboxes.length;
                const checkedCount = document.querySelectorAll('.student-checkbox:checked')
                    .length;

                selectAll.checked = checkedCount === totalCheckboxes;
                selectAll.indeterminate = checkedCount > 0 && checkedCount < totalCheckboxes;
            }
        });

        // Add row click functionality
        checkbox.closest('tr').addEventListener('click', function(e) {
            if (e.target.type !== 'checkbox') {
                const checkbox = this.querySelector('.student-checkbox');
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change'));
            }
        });
    });

    // Form submission confirmation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const selected = document.querySelectorAll('.student-checkbox:checked').length;
            if (selected === 0) {
                e.preventDefault();
                showAlert('Please select at least one student to assign the assignment.', 'warning');
                return false;
            }

            const studentNames = Array.from(document.querySelectorAll('.student-checkbox:checked'))
                .map(cb => cb.getAttribute('data-student-name'))
                .slice(0, 3); // Show first 3 names

            let message = `Are you sure you want to assign this assignment to ${selected} student(s)?`;
            if (studentNames.length > 0) {
                message += `\n\nIncluding: ${studentNames.join(', ')}${selected > 3 ? '...' : ''}`;
            }

            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>Assigning...`;
                submitBtn.disabled = true;
            }
        });
    }

    // Helper function for alerts
    function showAlert(message, type = 'info') {
        alert(message);
    }

    // Initialize
    updateSelectedCount();
});
</script>
@endsection
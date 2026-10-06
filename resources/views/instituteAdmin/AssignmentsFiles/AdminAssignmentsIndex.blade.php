@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<!-- Bootstrap Icons CDN -->
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

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px 30px;
    background: var(--primary-gradient);
    border-radius: 16px;
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

/* View Statistics Button */
.btn-outline-primary {
    padding: 10px 20px;
    background: var(--primary-gradient);
    border: none;
    color: #fff !important;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s;
}

.btn-outline-primary:hover {
    transform: translateY(-3px);
    border-color: transparent;
    text-decoration: none;
}

/* Statistics Cards - Enhanced */
.stats-card {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    transition: all 0.4s;
    margin-bottom: 1.5rem;
    background: white;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.stats-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(67, 97, 238, 0.15);
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
}

.stats-card.total::before {
    background: var(--primary-gradient);
}

.stats-card.students::before {
    background: var(--success-gradient);
}

.stats-card.submitted::before {
    background: var(--info-gradient);
}

.stats-card.graded::before {
    background: var(--warning-gradient);
}

.stats-card .card-body {
    padding: 25px;
}

.stats-number {
    font-size: 36px;
    font-weight: 800;
    margin-bottom: 0.25rem;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stats-card.students .stats-number {
    background: var(--success-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stats-card.submitted .stats-number {
    background: var(--info-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stats-card.graded .stats-number {
    background: var(--warning-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stats-label {
    font-size: 14px;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.stats-card i {
    font-size: 48px;
    opacity: 0.2;
}

/* Filter Section - Enhanced */
.filter-section {
    background: linear-gradient(135deg, #ffffff, #f8fafc);
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 30px;
    border: 2px solid #e2e8f0;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.search-box {
    position: relative;
}

.search-box .form-control {
    padding-left: 45px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    height: 50px;
    font-size: 15px;
}

.search-box .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary-color);
    font-size: 18px;
    z-index: 1;
}

/* Form Controls */
.form-label {
    font-weight: 600;
    color: #475569;
    margin-bottom: 8px;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.form-control,
.form-select {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 16px;
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

/* Buttons */
.btn {
    border-radius: 12px;
    font-weight: 600;
    padding: 10px 24px;
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

/* Assignment Card - Enhanced */
.assignment-card {
    border: 2px solid #e2e8f0;
    border-radius: 20px;
    transition: all 0.4s;
    margin-bottom: 20px;
    overflow: hidden;
    background: white;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.assignment-card:hover {
    transform: translateY(-5px);
    border-color: var(--primary-color);
    box-shadow: 0 20px 40px rgba(67, 97, 238, 0.15);
}

.assignment-header {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    padding: 15px 20px;
    border-bottom: 2px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.assignment-body {
    padding: 25px;
}

.assignment-title {
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 10px;
    font-size: 18px;
}

/* Badges */
.badge-department {
    background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
    color: var(--primary-color);
    font-weight: 600;
    padding: 6px 16px;
    border-radius: 30px;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid rgba(67, 97, 238, 0.2);
}

.badge.bg-primary {
    background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
    padding: 6px 16px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
}

/* Stat Badges */
.stat-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 16px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    border: none;
    color: white;
}

.stat-badge.pending {
    background: var(--warning-gradient);
}

.stat-badge.submitted {
    background: var(--info-gradient);
}

.stat-badge.graded {
    background: var(--success-gradient);
}

/* Teacher Info */
.teacher-info {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
    padding: 15px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 16px;
}

.teacher-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 18px;
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.2);
}

.teacher-info h6 {
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}

.teacher-info small {
    color: #64748b;
}

/* Assignment Meta */
.assignment-meta {
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px solid #e2e8f0;
}

.meta-item-assignments {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #475569;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    padding: 8px 16px;
    border-radius: 30px;
}

.meta-icon {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    color: var(--primary-color);
}

/* Due Date Colors */
.due-date {
    font-weight: 600;
}

.due-date.overdue {
    color: #ef4444;
}

.due-date.upcoming {
    color: #f59e0b;
}

.due-date.future {
    color: #10b981;
}

/* Action Buttons */
.admin-actions {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    border: 2px solid #e2e8f0;
    background: white;
    color: #475569;
    transition: all 0.3s;
    font-size: 16px;
}

.action-btn:hover {
    background: var(--primary-gradient);
    color: white;
    border-color: transparent;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.2);
}

/* Empty State - Enhanced */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 20px;
    border: 3px dashed #cbd5e1;
    margin: 30px 0;
}

.empty-state i {
    font-size: 64px;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 20px;
}

.empty-state h5 {
    color: #1e293b;
    font-weight: 700;
    margin-bottom: 10px;
}

/* Pagination - Enhanced */
.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 30px;
}

.pagination {
    gap: 8px;
}

.page-link {
    border-radius: 10px;
    border: 2px solid #e2e8f0;
    color: #475569;
    padding: 10px 16px;
    transition: all 0.3s;
    font-weight: 500;
}

.page-link:hover {
    background: var(--primary-gradient);
    border-color: transparent;
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.2);
}

.page-item.active .page-link {
    background: var(--primary-gradient);
    border-color: transparent;
    color: white;
}

.page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Student Stats */
.student-stats {
    display: flex;
    gap: 15px;
    margin-top: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
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

    .btn-outline-primary {
        width: 100%;
        justify-content: center;
    }

    .assignment-meta {
        flex-direction: column;
        gap: 10px;
    }

    .student-stats {
        flex-direction: column;
        gap: 10px;
    }

    .stat-badge {
        width: 100%;
        justify-content: center;
    }

    .admin-actions {
        flex-direction: column;
    }

    .action-btn {
        width: 100%;
    }
}

/* Course Type Badge */
.meta-item-assignments span {
    font-weight: 500;
}

/* File Count */
.meta-item-assignments i {
    color: var(--primary-color);
}

/* Description */
.text-muted {
    color: #64748b !important;
    line-height: 1.6;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #ccc;
    border-top-color: #007bff;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>


<div id="pageLoader" style="
    display:none;
    position:fixed;
    inset:0;
    background:rgba(255,255,255,0.7);
    z-index:9999;
    align-items:center;
    justify-content:center;
    ">
    <div class="spinner"></div>
</div>


<div class="container-fluid">
    <div class="admin-assignments-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-list-check"></i>
                Institute Assignments
            </h1>
            <div>
                <a href="{{ route('admin.assignments.statistics') }}" class="btn-outline-primary">
                    <i class="bi bi-bar-chart-fill me-1"></i>View Statistics
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card total">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="stats-label">Total Assignments</div>
                                <div class="stats-number">{{ $totalAssignments }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-file-text-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card students">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="stats-label">Students Assigned</div>
                                <div class="stats-number">{{ $totalStudentsAssigned }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card submitted">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="stats-label">Submitted Work</div>
                                <div class="stats-number">{{ $totalSubmissions }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-send-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card graded">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="stats-label">Graded Work</div>
                                <div class="stats-number">{{ $totalGraded }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <form id="filterForm" method="GET" action="{{ route('admin.assignments.index') }}">
                <div class="row g-4">

                    <!-- Search -->
                    <div class="col-md-12">
                        <div class="search-box">
                            <i class="bi bi-search search-icon"></i>
                            <input type="text" name="search" class="form-control filter-input"
                                placeholder="Search assignments by title, description, or teacher..."
                                value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Department -->
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="bi bi-building-fill me-1"></i>Department
                        </label>

                        <input type="text" id="departmentName" class="form-control filter-input" list="departmentList"
                            placeholder="Search Department"
                            value="{{ optional($departments->firstWhere('department_id', request('department_id')))->department }}">

                        <input type="hidden" name="department_id" id="departmentId"
                            value="{{ request('department_id') }}">

                        <datalist id="departmentList">
                            @foreach($departments as $department)
                            <option data-id="{{ $department->department_id }}" value="{{ $department->department }}">
                            </option>
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Teacher -->
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="bi bi-person-badge-fill me-1"></i>Teacher
                        </label>

                        <input type="text" id="teacherName" class="form-control filter-input" list="teacherList"
                            placeholder="Search Teacher"
                            value="{{ optional($employees->firstWhere('employee_id', request('employee_id')))->name }}">

                        <input type="hidden" name="employee_id" id="teacherId" value="{{ request('employee_id') }}">

                        <datalist id="teacherList">
                            @foreach($employees as $employee)
                            <option data-id="{{ $employee->employee_id }}" value="{{ $employee->name }}">
                            </option>
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <label class="form-label"><i class="bi bi-flag-fill me-1"></i>Status</label>

                        <input list="statusList" name="status" class="form-control filter-input"
                            placeholder="Search Status" value="{{ request('status') }}">

                        <datalist id="statusList">
                            <option value="pending">
                            <option value="submitted">
                            <option value="graded">
                        </datalist>

                    </div>

                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" onclick="resetFilters()" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-repeat me-1"></i>Reset
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>

        <!-- Assignments List -->
        <div class="row">
            <div class="col-12">
                @if($assignments->count() > 0)
                @foreach($assignments as $assignment)
                @php
                $dueDate = $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date) : null;
                $isOverdue = $dueDate && $dueDate->isPast();
                $isDueSoon = $dueDate && !$dueDate->isPast() && $dueDate->diffInDays(now()) <= 2; // Get student counts
                    $studentCounts=[ 'total'=> $assignment->assignedStudents->first()->total ?? 0,
                    'pending' => $assignment->assignedStudentsWithStatus->where('status', 'pending')->first()->count ??
                    0,
                    'submitted' => $assignment->assignedStudentsWithStatus->where('status', 'submitted')->first()->count
                    ?? 0,
                    'graded' => $assignment->assignedStudentsWithStatus->where('status', 'graded')->first()->count ?? 0,
                    ];
                    @endphp

                    <div class="assignment-card">
                        <div class="assignment-header">
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge-department">
                                        <i class="bi bi-building-fill me-1"></i>
                                        {{ $assignment->department->department ?? 'No Department' }}
                                    </span>
                                    @if($studentCounts['total'] > 0)
                                    <span class="badge bg-primary">
                                        <i class="bi bi-people-fill me-1"></i>{{ $studentCounts['total'] }} Students
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="admin-actions">
                                <a href="{{ route('admin.assignments.show', $assignment->id) }}" class="action-btn"
                                    title="View Details">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('admin.assignments.submissions', $assignment->id) }}"
                                    class="action-btn" title="View Submissions">
                                    <i class="bi bi-list-check"></i>
                                </a>
                            </div>
                        </div>

                        <div class="assignment-body">
                            <div class="assignment-title">
                                {{ $assignment->title }}
                            </div>

                            @if($assignment->description)
                            <p class="text-muted mb-3">
                                {{ \Illuminate\Support\Str::limit($assignment->description, 150) }}
                            </p>
                            @endif

                            <div class="teacher-info">
                                <div class="teacher-avatar">
                                    {{ substr($assignment->employee->name ?? 'T', 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $assignment->employee->name ?? 'Unknown Teacher' }}</h6>
                                    <small
                                        class="text-muted">{{ $assignment->employee->designation ?? 'Teacher' }}</small>
                                </div>
                            </div>

                            <!-- Student Statistics -->
                            @if($studentCounts['total'] > 0)
                            <div class="student-stats">
                                @if($studentCounts['pending'] > 0)
                                <span class="stat-badge pending">
                                    <i class="bi bi-clock-fill"></i> {{ $studentCounts['pending'] }} Pending
                                </span>
                                @endif
                                @if($studentCounts['submitted'] > 0)
                                <span class="stat-badge submitted">
                                    <i class="bi bi-send-fill"></i> {{ $studentCounts['submitted'] }} Submitted
                                </span>
                                @endif
                                @if($studentCounts['graded'] > 0)
                                <span class="stat-badge graded">
                                    <i class="bi bi-check-circle-fill"></i> {{ $studentCounts['graded'] }} Graded
                                </span>
                                @endif
                            </div>
                            @endif

                            <div class="assignment-meta">
                                <div class="meta-item-assignments">
                                    <div class="meta-icon">
                                        <i class="bi bi-book-fill"></i>
                                    </div>
                                    <span>{{ $assignment->courseType->course_type ?? 'No Course' }}</span>
                                </div>

                                @if($dueDate)
                                <div class="meta-item-assignments">
                                    <div class="meta-icon">
                                        <i class="bi bi-calendar-fill"></i>
                                    </div>
                                    <span
                                        class="due-date {{ $isOverdue ? 'overdue' : ($isDueSoon ? 'upcoming' : 'future') }}">
                                        Due: {{ $dueDate->format('M d, Y') }}
                                    </span>
                                </div>
                                @endif

                                <div class="meta-item-assignments">
                                    <div class="meta-icon">
                                        <i class="bi bi-file-earmark-fill"></i>
                                    </div>
                                    <span>{{ $assignment->files->count() }} Files</span>
                                </div>

                                <div class="meta-item-assignments">
                                    <div class="meta-icon">
                                        <i class="bi bi-clock-fill"></i>
                                    </div>
                                    <span>Created: {{ $assignment->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="pagination-container">
                        {{ $assignments->withQueryString()->links() }}
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="bi bi-list-check"></i>
                        <h5 class="mb-2">No Assignments Found</h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['search', 'department_id', 'employee_id', 'status']))
                            Try changing your search criteria
                            @else
                            No assignments have been created yet in the institute.
                            @endif
                        </p>
                    </div>
                    @endif
            </div>
        </div>
    </div>
</div>

<script>
function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}

document.addEventListener("DOMContentLoaded", function() {

    const form = document.getElementById("filterForm");

    function handleFilter(inputId, listId, hiddenId) {

        const input = document.getElementById(inputId);
        const hidden = document.getElementById(hiddenId);

        if (!input || !hidden) return;

        const options = document.querySelectorAll(`#${listId} option`);

        /* Restore name when page reloads */
        if (hidden.value) {
            options.forEach(option => {
                if (option.dataset.id == hidden.value) {
                    input.value = option.value;
                }
            });
        }

        /* Convert name -> id when selecting */
        input.addEventListener("change", function() {

            hidden.value = "";

            options.forEach(option => {
                if (option.value === input.value) {
                    hidden.value = option.dataset.id;
                }
            });

            showLoader();
            form.submit();
        });
    }

    handleFilter("departmentName", "departmentList", "departmentId");
    handleFilter("teacherName", "teacherList", "teacherId");


    /* Auto filter for search + status */
    document.querySelectorAll(".filter-input").forEach(function(input) {

        input.addEventListener("change", function() {
            showLoader();
            form.submit();
        });

        input.addEventListener("keyup", function(e) {
            if (e.key === "Enter") {
                showLoader();
                form.submit();
            }
        });

    });

});

function resetFilters() {
    window.location = "{{ route('admin.assignments.index') }}";
}
</script>
@endsection
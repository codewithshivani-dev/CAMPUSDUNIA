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

    body {
        background-color: #f8fafc;
    }

    .container-fluid {
        background-color: #f8fafc;
    }

    /* Page Header */
    .page-header {
        background: var(--primary-gradient);
        padding: 20px 25px;
        border-radius: 16px;
        margin-bottom: 25px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
    }

    .page-header h4 {
        color: white;
        margin: 0;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.35rem;
    }

    .page-header h4 i {
        background: rgba(255,255,255,0.2);
        padding: 10px;
        border-radius: 12px;
        font-size: 1.1rem;
    }

    .breadcrumb {
        margin-bottom: 0;
        background: transparent;
        padding: 0;
    }

    .breadcrumb-item a {
        color: rgba(255,255,255,0.8);
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        color: white;
    }

    .breadcrumb-item.active {
        color: white;
    }

    /* Employee Info Card */
    .employee-info-card {
        background: var(--primary-gradient);
        color: white;
        border-radius: 20px;
        padding: 25px 30px;
        margin-bottom: 25px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
    }

    .employee-avatar-lg {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        border: 3px solid rgba(255, 255, 255, 0.3);
        color: white;
    }

    .employee-info-card h4 {
        color: white;
        font-weight: 700;
    }

    .employee-info-card p {
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 0.25rem;
    }

    .btn-light {
        background: rgba(255,255,255,0.15);
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-light:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-2px);
        color: white;
    }

    /* Cards */
    .card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        /*overflow: hidden;*/
        margin-bottom: 25px;
    }

    .card-header {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        border-radius: 20px 20px 0 0 !important;
        padding: 1.25rem 1.5rem;
    }

    .card-header h5 {
        color: #1e293b;
        font-weight: 700;
        margin: 0;
    }

    .card-body {
        padding: 0;
    }

    /* Stats Cards */
    .stats-card {
        text-align: center;
        padding: 1.5rem 1rem;
        border-left: 4px solid var(--primary-color);
        border-radius: 20px;
        background: white;
    }

    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.25rem;
    }

    .stats-label {
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        border: none;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .filter-section h6 {
        color: #1e293b;
        font-weight: 700;
    }

    /* Table Styles */
    .table {
        margin-bottom: 0;
    }

    /*.table th {*/
    /*    background: #f8fafc;*/
    /*    border-bottom: 2px solid #e2e8f0;*/
    /*    font-weight: 600;*/
    /*    color: #475569;*/
    /*    padding: 1rem 0.75rem;*/
    /*    font-size: 0.875rem;*/
    /*    letter-spacing: 0.3px;*/
    /*}*/

    .table td {
        padding: 1rem 0.75rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
        color: #1e293b;
    }

    .assignment-row:hover {
        background-color: #f8fafc;
    }

    /* Badges */
    .badge {
        font-size: 0.75em;
        padding: 0.4em 0.8em;
        border-radius: 30px;
        font-weight: 500;
    }

    .badge.bg-light {
        background: #f1f5f9 !important;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    /* Buttons */
    .btn {
        border-radius: 10px;
        font-weight: 500;
        padding: 0.5rem 1rem;
        transition: all 0.3s;
    }

    .btn-primary {
        background: var(--primary-gradient);
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
    }

    .btn-outline-primary {
        color: var(--primary-color);
        border: 2px solid #e2e8f0;
        background: transparent;
    }

    .btn-outline-primary:hover {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
    }

    .btn-outline-secondary {
        border: 2px solid #e2e8f0;
        color: #475569;
        background: transparent;
    }

    .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-color: #cbd5e1;
        transform: translateY(-2px);
    }

    .btn-outline-info {
        color: #3b82f6;
        border: 2px solid #e2e8f0;
        background: transparent;
    }

    .btn-outline-info:hover {
        background: var(--info-gradient);
        border-color: transparent;
        color: white;
    }

    .btn-outline-success {
        color: var(--success-color);
        border: 2px solid #e2e8f0;
        background: transparent;
    }

    .btn-outline-success:hover {
        background: var(--success-gradient);
        border-color: transparent;
        color: white;
    }

    .btn-group-sm .btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 8px;
    }

    /* Form Controls */
    .form-control,
    .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 15px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    .form-control[readonly] {
        background-color: #f8fafc;
        cursor: default;
    }

    .form-label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        font-size: 13px;
        letter-spacing: 0.3px;
    }

    /* File Count Badge */
    .file-count {
        background: #f1f5f9;
        border-radius: 8px;
        padding: 0.25rem 0.5rem;
        font-size: 0.75em;
        font-weight: 600;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }

    .modal-header {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
    }

    .modal-title {
        font-weight: 700;
        color: #1e293b;
    }

    /* Student Item in Modal */
    .student-item {
        border-left: 3px solid var(--primary-color);
        margin-bottom: 0.5rem;
        border-radius: 12px !important;
        transition: all 0.3s;
    }

    .student-item:hover {
        background-color: #f8fafc;
        transform: translateX(3px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #64748b;
    }

    .empty-state i {
        font-size: 3.5rem;
        margin-bottom: 1.5rem;
        color: #cbd5e1;
    }

    .empty-state h5 {
        color: #1e293b;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    /* Pagination */
    .pagination .page-link {
        border: 2px solid #e2e8f0;
        color: var(--primary-color);
        border-radius: 10px;
        margin: 0 3px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .pagination .page-item.active .page-link {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white;
    }

    .pagination .page-link:hover {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
    }

    /* Text Colors */
    .text-gray-800 {
        color: #1e293b !important;
    }

    .text-muted {
        color: #64748b !important;
    }

    /* Status Colors */
    .text-success {
        color: var(--success-color) !important;
    }

    .text-danger {
        color: #ef4444 !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 15px;
        }

        .page-header {
            padding: 15px 20px;
        }

        .employee-info-card {
            padding: 20px;
        }

        .employee-avatar-lg {
            width: 70px;
            height: 70px;
            font-size: 2rem;
        }

        .employee-info-card h4 {
            font-size: 1.2rem;
        }

        .btn-light {
            padding: 8px 16px;
            font-size: 13px;
        }

        .filter-section {
            padding: 1rem;
        }

        .stats-card {
            padding: 1rem;
        }

        .stats-number {
            font-size: 1.5rem;
        }
    }

.btnn {
    width:200px;
    text-align:left;
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
        
        .table-responsive{
            overflow-x: hidden;
        }
        
        .erp-table thead .sticky-main,
        .erp-table tbody .sticky-main{
            left: 28px;
        }
</style>

@php
    $courseLabel = (isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School')
    ? 'Class'
    : 'Course';
@endphp

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4>
                    <i class="fas fa-tasks"></i>
                    Assignments
                </h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('assignments.index') }}">Assignments</a></li>
                        <li class="breadcrumb-item active">Overview</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Employee Information Header -->
    <div class="employee-info-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <div class="employee-avatar-lg me-3">
                    {{ substr($employee->name ?? 'T', 0, 1) }}
                </div>
                <div>
                    <h4 class="mb-1">{{ $employee->name }}</h4>
                    <p class="mb-1">{{ $employee->designation }} | ID: {{ $employee->employee_id }}</p>
                    @if($employeeDepartment)
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-building me-1"></i>{{ $employeeDepartment->department }}
                        </p>
                    @endif
                </div>
            </div>
            <a href="{{ route('assignments.create') }}" class="btn btn-light">
                <i class="fas fa-plus-circle me-2"></i>Create New Assignment
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body stats-card">
                    <div class="stats-number">{{ $assignments->total() }}</div>
                    <div class="stats-label">My Assignments</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body stats-card" style="border-left-color: #10b981;">
                    <div class="stats-number" style="color: #10b981;">
                        @php
                        $totalStudents = 0;
                        foreach($assignments as $assignment) {
                            $totalStudents += $assignment->assignedStudents->count();
                        }
                        @endphp
                        {{ $totalStudents }}
                    </div>
                    <div class="stats-label">Total Students Assigned</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body stats-card" style="border-left-color: #3b82f6;">
                    <div class="stats-number" style="color: #3b82f6;">
                        @php
                        $submitted = 0;
                        foreach($assignments as $assignment) {
                            $submitted += $assignment->assignedStudents->where('status', 'submitted')->count();
                        }
                        @endphp
                        {{ $submitted }}
                    </div>
                    <div class="stats-label">Submitted Work</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body stats-card" style="border-left-color: #f59e0b;">
                    <div class="stats-number" style="color: #f59e0b;">
                        @php
                        $graded = 0;
                        foreach($assignments as $assignment) {
                            $graded += $assignment->assignedStudents->where('status', 'graded')->count();
                        }
                        @endphp
                        {{ $graded }}
                    </div>
                    <div class="stats-label">Graded Work</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-section">
        <h6 class="mb-3 text-gray-800">
            <i class="fas fa-filter me-2"></i>Filter My Assignments
        </h6>
        <form id="filterForm">
            <div class="row g-3">
                @if($employeeDepartment)
                <div class="col-md-4">
                    <label class="form-label">
                        <i class="fas fa-building me-1"></i>My Department
                    </label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $employeeDepartment->department }}" readonly>
                        <span class="input-group-text bg-light">
                            <i class="fas fa-lock text-muted"></i>
                        </span>
                    </div>
                    <small class="text-muted">Your assigned department</small>
                </div>
                @endif
                
                <div class="col-md-4">
                    <label class="form-label">
                        <i class="fas fa-book-open me-1"></i>{{$courseLabel}} Type
                    </label>
                    <select name="course_type_id" class="form-select">
                        <option value="">All Course Types</option>
                        @foreach($courseTypes as $courseType)
                            <option value="{{ $courseType->finacp_merchant_sub_category_type }}"
                                    {{ request('course_type_id') == $courseType->finacp_merchant_sub_category_type ? 'selected' : '' }}>
                                {{ $courseType->finacp_merchant_sub_category_type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">
                        <i class="fas fa-tag me-1"></i>Status
                    </label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="graded" {{ request('status') == 'graded' ? 'selected' : '' }}>Graded</option>
                    </select>
                </div>
                
                <div class="col-12">
                    <div class="d-flex gap-2 justify-content-end" style="margin-top: 20px;">
                        <button type="button" id="resetFilters" class="btn btn-outline-secondary" style="margin-right: 5px;">
                            <i class="fas fa-redo me-2"></i>Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Assignments Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 text-gray-800">
                        <i class="fas fa-list-ul me-2" style="color: var(--primary-color);"></i>My Assignments
                    </h5>
                    <p class="mb-0 text-muted small">Showing only assignments created by you</p>
                </div>
                <div class="card-body p-0">
                    @if($assignments->count() > 0)
                    <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                        <table class="erp-table table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="sticky-checkbox" width="40">#</th>
                                    <th class="sticky-main sortable">Title</th>
                                    <th class="sortable">{{$courseLabel}} Type</th>
                                    <th class="sortable">{{$courseLabel}}</th>
                                    <th class="sortable">Subject</th>
                                    <th class="sortable">Semester</th>
                                    <th class="sortable">Due Date</th>
                                    <th class="sortable">Files</th>
                                    <th class="sortable">Students</th>
                                    <th class="sortable">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $index => $assignment)
                                <tr class="assignment-row">
                                    <td class="sticky-checkbox text-muted">{{ $index + 1 }}</td>
                                    <td class="sticky-main">
                                        <strong>{{ $assignment->title }}</strong>
                                        @if($assignment->description)
                                            <small class="d-block text-muted mt-1">{{ Str::limit($assignment->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $assignment->courseType->course_type ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $assignment->courseType->sub_type ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $subject = \App\Models\SubjectsCoursewise::where('subject_id', $assignment->subject_id)->first();
                                        @endphp
                                        <small class="text-muted">{{ $subject->subject_name ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span>{{ $assignment->semester_id ?? 'All' }}</span>
                                    </td>
                                    <td>
                                        @if($assignment->due_date)
                                        @php
                                            $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                                            $isOverdue = $dueDate->isPast();
                                        @endphp
                                        <div class="{{ $isOverdue ? 'text-danger' : 'text-success' }}">
                                            {{ $dueDate->format('M d, Y') }}
                                            <br>
                                            <small>{{ $dueDate->format('h:i A') }}</small>
                                        </div>
                                        @if($isOverdue)
                                            <small class="badge bg-danger text-white">Overdue</small>
                                        @endif
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($assignment->files->count())
                                        <span class="file-count">{{ $assignment->files->count() }}</span>
                                        <button class="btn btn-sm btn-outline-primary ms-1"
                                            onclick="viewFiles({{ $assignment->id }})" title="View Files">
                                            <i class="fas fa-eye"></i> View Files
                                        </button>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span>
                                            {{ $assignment->assignedStudents->count() }}
                                        </span>
                                        <button class="btn btn-sm btn-outline-primary ms-1" data-bs-toggle="modal"
                                            data-bs-target="#studentsModal"
                                            onclick="loadStudents({{ $assignment->id }})" title="View Students">
                                            <i class="fas fa-users"></i> View Students
                                        </button>
                                    </td>
                                    <td>
                                    <div class="">
                                        <a href="{{ route('assignments.assignStudentsForm', $assignment->id) }}" 
                                        class="btn btnn btn-outline-primary" title="Assign Students">
                                            <i class="fas fa-user-plus"></i>
                                            <small>Assign Students</small>
                                        </a>
                                        <button class="btn btnn btn-outline-info" 
                                                onclick="viewAssignmentDetails({{ $assignment->id }})"
                                                title="View Details">
                                            <i class="fas fa-info-circle"></i>
                                            <small>View Details</small>
                                        </button>
                                    
                                        <a href="{{ route('assignments.view.submissions', $assignment->id) }}" 
                                        class="btn btnn btn-outline-success" title="View Submissions">
                                            <i class="fas fa-eye"></i>
                                            <small>View Submissions</small>
                                        </a>
                                    </div>
                                </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Floating Horizontal Scrollbar -->
                    <div class="table-scroll-top" id="tableScrollTop">
                        <div class="table-scroll-inner"></div>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center p-3 border-top">
                        <div class="text-muted small">
                            Showing {{ $assignments->firstItem() }} to {{ $assignments->lastItem() }} of
                            {{ $assignments->total() }} entries
                        </div>
                        {{ $assignments->links() }}
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-tasks"></i>
                        <h5 class="text-gray-800">No Assignments Found</h5>
                        <p class="text-muted">You haven't created any assignments yet.</p>
                        <a href="{{ route('assignments.create') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-plus-circle me-1"></i>Create Your First Assignment
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Students Modal -->
<div class="modal fade" id="studentsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-users me-2" style="color: var(--primary-color);"></i>Assigned Students
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="studentsList"></div>
            </div>
        </div>
    </div>
</div>

<!-- Files Modal -->
<div class="modal fade" id="filesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-paperclip me-2" style="color: var(--primary-color);"></i>Assignment Files
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="filesList"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Assignment Details Modal -->
<div class="modal fade" id="assignmentDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2" style="color: var(--primary-color);"></i>Assignment Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="assignmentDetails"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Filter form submission
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });

    // Reset filters
    $('#resetFilters').on('click', function() {
        $('#filterForm')[0].reset();
        applyFilters();
    });

    // Load branches when course type changes
    $('select[name="course_type_id"]').on('change', function() {
        const courseType = $(this).val();
        const departmentId = "{{ $employee->department_id ?? '' }}";
        
        if (courseType && departmentId) {
            $.ajax({
                url: '{{ route("ajax.branches.by.course") }}',
                type: 'GET',
                data: {
                    department_id: departmentId,
                    course_type: courseType
                },
                success: function(data) {
                    console.log('Branches loaded:', data);
                }
            });
        }
    });
    
    // Set initial course type if from URL
    const urlParams = new URLSearchParams(window.location.search);
    const courseTypeId = urlParams.get('course_type_id');
    if (courseTypeId) {
        $('select[name="course_type_id"]').val(courseTypeId);
    }
});

function applyFilters() {
    const formData = $('#filterForm').serialize();
    window.location.href = '{{ route("assignments.index") }}?' + formData;
}

function loadStudents(assignmentId) {
    $('#studentsList').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading students...</p>
        </div>
    `);

    $.get('/assignments/' + assignmentId + '/students', function(data) {
        if (data.students && data.students.length > 0) {
            let html = `
                <div class="mb-3">
                    <strong>Total Students: </strong>
                    <span>${data.students.length}</span>
                </div>
                <div class="list-group">
            `;

            data.students.forEach((student, index) => {
                const statusClass = {
                    'pending': 'bg-warning',
                    'submitted': 'bg-info',
                    'graded': 'bg-success'
                }[student.status] || 'bg-secondary';

                html += `
                    <div class="list-group-item student-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">${student.first_name} ${student.last_name}</h6>
                                <small class="text-muted">Reg: ${student.registration_number || 'N/A'}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge ${statusClass}">${student.status}</span>
                                ${student.marks ? `<div class="mt-1"><small class="text-muted">Marks: ${student.marks}</small></div>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            $('#studentsList').html(html);
        } else {
            $('#studentsList').html(`
                <div class="text-center py-4">
                    <i class="fas fa-users fa-2x text-muted mb-3"></i>
                    <p class="text-muted">No students assigned to this assignment.</p>
                    <a href="/assignments/${assignmentId}/assign-students" class="btn btn-primary btn-sm">
                        <i class="fas fa-user-plus me-1"></i>Assign Students
                    </a>
                </div>
            `);
        }
    }).fail(function() {
        $('#studentsList').html(`
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Error loading students. Please try again.
            </div>
        `);
    });
}

function viewFiles(assignmentId) {
    $('#filesList').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading files...</p>
        </div>
    `);

    $.get('/assignments/' + assignmentId + '/files', function(data) {
        if (data.files && data.files.length > 0) {
            let html = '<div class="list-group">';

            data.files.forEach((file, index) => {
                const fileExt = file.file_type.toLowerCase();
                const fileName = file.file_name;
                
                let fileIcon = 'fa-file';
                let previewButton = '';
                
                if (fileExt === 'pdf') {
                    fileIcon = 'fa-file-pdf text-danger';
                    previewButton = `
                        <button class="btn btn-sm btn-outline-primary ms-1" onclick="previewFile('${file.download_url}', '${fileName}')" title="Preview">
                            <i class="fas fa-eye"></i>
                        </button>`;
                } else if (['jpg', 'jpeg', 'png', 'gif', 'bmp'].includes(fileExt)) {
                    fileIcon = 'fa-file-image text-success';
                    previewButton = `
                        <button class="btn btn-sm btn-outline-primary ms-1" onclick="previewImage('${file.download_url}', '${fileName}')" title="Preview Image">
                            <i class="fas fa-eye"></i>
                        </button>`;
                } else if (['doc', 'docx'].includes(fileExt)) {
                    fileIcon = 'fa-file-word text-primary';
                } else if (['ppt', 'pptx'].includes(fileExt)) {
                    fileIcon = 'fa-file-powerpoint text-warning';
                } else if (['xls', 'xlsx'].includes(fileExt)) {
                    fileIcon = 'fa-file-excel text-success';
                } else if (['zip', 'rar'].includes(fileExt)) {
                    fileIcon = 'fa-file-archive text-secondary';
                } else if (['txt'].includes(fileExt)) {
                    fileIcon = 'fa-file-alt text-info';
                }

                html += `
                    <div class="list-group-item list-group-item-action">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas ${fileIcon} me-2 fa-lg"></i>
                                <div>
                                    <div class="fw-medium">${fileName}</div>
                                    <small class="text-muted">${fileExt.toUpperCase()} File</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                ${previewButton}
                                <a href="${file.download_url}" target="_blank" class="btn btn-sm btn-outline-primary" title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            $('#filesList').html(html);
        } else {
            $('#filesList').html(`
                <div class="text-center py-4">
                    <i class="fas fa-file fa-2x text-muted mb-3"></i>
                    <p class="text-muted">No files attached to this assignment.</p>
                </div>
            `);
        }

        new bootstrap.Modal(document.getElementById('filesModal')).show();
    }).fail(function() {
        $('#filesList').html(`
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Error loading files. Please try again.
            </div>
        `);
        new bootstrap.Modal(document.getElementById('filesModal')).show();
    });
}

function previewFile(fileUrl, fileName) {
    window.open(fileUrl, '_blank', 'noopener,noreferrer');
}

function previewImage(imageUrl, imageName) {
    const modalHtml = `
        <div class="modal fade" id="imagePreviewModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${imageName}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="${imageUrl}" class="img-fluid rounded" alt="${imageName}" style="max-height: 70vh;">
                    </div>
                    <div class="modal-footer">
                        <a href="${imageUrl}" target="_blank" class="btn btn-primary">
                            <i class="fas fa-download me-1"></i>Download
                        </a>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('#imagePreviewModal').remove();
    $('body').append(modalHtml);
    const imageModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
    imageModal.show();
}

function showImageGallery(images) {
    let galleryHtml = `
        <div class="modal fade" id="imageGalleryModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Assignment Images</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row" id="galleryImages">
    `;
    
    images.forEach((image, index) => {
        galleryHtml += `
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="${image.url}" class="card-img-top" alt="${image.name}" style="height: 200px; object-fit: cover;">
                    <div class="card-body p-2">
                        <small class="text-muted">${image.name}</small>
                    </div>
                </div>
            </div>
        `;
    });
    
    galleryHtml += `
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('#imageGalleryModal').remove();
    $('body').append(galleryHtml);
    const galleryModal = new bootstrap.Modal(document.getElementById('imageGalleryModal'));
    galleryModal.show();
}

function viewAssignmentDetails(assignmentId) {
    $('#assignmentDetails').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading assignment details...</p>
        </div>
    `);

    $.get('/assignments/' + assignmentId + '/details', function(data) {
        if (data.assignment) {
            const assignment = data.assignment;
            const dueDate = new Date(assignment.due_date);
            const createdDate = new Date(assignment.created_at);
            
            let html = `
                <div class="row">
                    <div class="col-md-8">
                        <h4>${assignment.title}</h4>
                        ${assignment.description ? `<p class="text-muted">${assignment.description}</p>` : ''}
                    </div>
                    <div class="col-md-4 text-end">
                        <span class="badge ${dueDate < new Date() ? 'bg-danger' : 'bg-success'}">
                            ${dueDate < new Date() ? 'Overdue' : 'Active'}
                        </span>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6>Assignment Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td width="140"><strong>Course Type:</strong></td>
                                <td>${assignment.course_type || 'N/A'}</td>
                            </tr>
                            <tr>
                                <td><strong>Branch:</strong></td>
                                <td>${assignment.branch || 'N/A'}</td>
                            </tr>
                            <tr>
                                <td><strong>Subject:</strong></td>
                                <td>${assignment.subject || 'N/A'}</td>
                            </tr>
                            <tr>
                                <td><strong>Semester:</strong></td>
                                <td>${assignment.semester_id || 'All'}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Timeline</h6>
                        <table class="table table-sm">
                            <tr>
                                <td width="140"><strong>Created On:</strong></td>
                                <td>${createdDate.toLocaleDateString()}</td>
                            </tr>
                            <tr>
                                <td><strong>Due Date:</strong></td>
                                <td>${dueDate.toLocaleDateString()} ${dueDate.toLocaleTimeString()}</td>
                            </tr>
                            <tr>
                                <td><strong>Students Assigned:</strong></td>
                                <td>${assignment.students_count || 0}</td>
                            </tr>
                            <tr>
                                <td><strong>Files Attached:</strong></td>
                                <td>${assignment.files_count || 0}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="mt-3">
                    <a href="/assignments/${assignmentId}/assign-students" class="btn btn-primary me-2">
                        <i class="fas fa-user-plus me-1"></i>Manage Students
                    </a>
                   
                </div>
            `;
            
            $('#assignmentDetails').html(html);
        } else {
            $('#assignmentDetails').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Assignment details not found.
                </div>
            `);
        }
        
        new bootstrap.Modal(document.getElementById('assignmentDetailsModal')).show();
    }).fail(function() {
        $('#assignmentDetails').html(`
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Error loading assignment details.
            </div>
        `);
        new bootstrap.Modal(document.getElementById('assignmentDetailsModal')).show();
    });
}
</script>
@endsection
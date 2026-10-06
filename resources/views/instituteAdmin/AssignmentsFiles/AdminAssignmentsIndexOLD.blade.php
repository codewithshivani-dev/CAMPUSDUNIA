@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<style>
    .admin-assignments-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .stats-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        transition: transform 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .stats-card.total::before { background: linear-gradient(90deg, #4e73df 0%, #2e59d9 100%); }
    .stats-card.students::before { background: linear-gradient(90deg, #1cc88a 0%, #16a085 100%); }
    .stats-card.submitted::before { background: linear-gradient(90deg, #36b9cc 0%, #2a96a5 100%); }
    .stats-card.graded::before { background: linear-gradient(90deg, #f6c23e 0%, #f0ad4e 100%); }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.25rem;
    }

    .stats-label {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .filter-section {
        background: #f8f9fc;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid #e3e6f0;
    }

    .assignment-card {
        border: 1px solid #e3e6f0;
        border-radius: 10px;
        transition: all 0.3s ease;
        margin-bottom: 1rem;
        overflow: hidden;
    }

    .assignment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border-color: #4e73df;
    }

    .assignment-header {
        background: #f8f9fc;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e3e6f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .assignment-body {
        padding: 1.5rem;
    }

    .assignment-title {
        font-weight: 700;
        color: #2e59d9;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    .assignment-meta {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e3e6f0;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #6c757d;
    }

    .meta-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }

    .teacher-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .teacher-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4e73df 0%, #2e59d9 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1rem;
    }

    .student-stats {
        display: flex;
        gap: 1.5rem;
        margin-top: 1rem;
    }

    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .stat-badge.pending { background: #fff3cd; color: #856404; }
    .stat-badge.submitted { background: #d1ecf1; color: #0c5460; }
    .stat-badge.graded { background: #d4edda; color: #155724; }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        background: #f8f9fc;
        border-radius: 12px;
        border: 2px dashed #e3e6f0;
        margin: 2rem 0;
    }

    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1.5rem;
    }

    .search-box {
        position: relative;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }

    .admin-actions {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid #e3e6f0;
        background: white;
        color: #6c757d;
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        background: #f8f9fc;
        color: #4e73df;
        border-color: #4e73df;
        transform: translateY(-1px);
    }

    .badge-department {
        background: #e8f4fd;
        color: #2e59d9;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
    }

    .due-date {
        font-weight: 600;
    }

    .due-date.overdue {
        color: #e74a3b;
    }

    .due-date.upcoming {
        color: #f6c23e;
    }

    .due-date.future {
        color: #1cc88a;
    }

    @media (max-width: 768px) {
        .assignment-meta {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .student-stats {
            flex-wrap: wrap;
            gap: 0.75rem;
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="admin-assignments-container">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 text-gray-800">
                            <i class="fas fa-tasks me-2"></i>Institute Assignments
                        </h4>
                        <p class="mb-0 text-muted">Manage and monitor all assignments across the institute</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.assignments.statistics') }}" class="btn btn-outline-primary">
                            <i class="fas fa-chart-bar me-1"></i>View Statistics
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card total">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="stats-label">Total Assignments</div>
                                <div class="stats-number text-primary">{{ $totalAssignments }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-file-alt fa-2x text-primary opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card students">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="stats-label">Students Assigned</div>
                                <div class="stats-number text-success">{{ $totalStudentsAssigned }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-success opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card submitted">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="stats-label">Submitted Work</div>
                                <div class="stats-number text-info">{{ $totalSubmissions }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-paper-plane fa-2x text-info opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card graded">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="stats-label">Graded Work</div>
                                <div class="stats-number text-warning">{{ $totalGraded }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-warning opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <form id="filterForm" method="GET" action="{{ route('admin.assignments.index') }}">
                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" 
                                   name="search" 
                                   class="form-control ps-4" 
                                   placeholder="Search assignments by title, description, or teacher..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-control">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->department_id }}" 
                                    {{ request('department_id') == $department->department_id ? 'selected' : '' }}>
                                    {{ $department->department }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Teacher</label>
                        <select name="employee_id" class="form-control">
                            <option value="">All Teachers</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->employee_id }}" 
                                    {{ request('employee_id') == $employee->employee_id ? 'selected' : '' }}>
                                    {{ $employee->name }} ({{ $employee->employee_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="graded" {{ request('status') == 'graded' ? 'selected' : '' }}>Graded</option>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" onclick="resetFilters()" class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-1"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i>Apply Filters
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
                            $isDueSoon = $dueDate && !$dueDate->isPast() && $dueDate->diffInDays(now()) <= 2;
                            
                            // Get student counts
                            $studentCounts = [
                                'total' => $assignment->assignedStudents->first()->total ?? 0,
                                'pending' => $assignment->assignedStudentsWithStatus->where('status', 'pending')->first()->count ?? 0,
                                'submitted' => $assignment->assignedStudentsWithStatus->where('status', 'submitted')->first()->count ?? 0,
                                'graded' => $assignment->assignedStudentsWithStatus->where('status', 'graded')->first()->count ?? 0,
                            ];
                        @endphp
                        
                        <div class="assignment-card">
                            <div class="assignment-header">
                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge-department">
                                            {{ $assignment->department->department ?? 'No Department' }}
                                        </span>
                                        @if($studentCounts['total'] > 0)
                                            <span class="badge bg-primary">
                                                <i class="fas fa-users me-1"></i>{{ $studentCounts['total'] }} Students
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="admin-actions">
                                    <a href="{{ route('admin.assignments.show', $assignment->id) }}" 
                                       class="action-btn" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.assignments.submissions', $assignment->id) }}" 
                                       class="action-btn" title="View Submissions">
                                        <i class="fas fa-list-check"></i>
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
                                        <small class="text-muted">{{ $assignment->employee->designation ?? 'Teacher' }}</small>
                                    </div>
                                </div>
                                
                                <!-- Student Statistics -->
                                @if($studentCounts['total'] > 0)
                                    <div class="student-stats">
                                        @if($studentCounts['pending'] > 0)
                                            <span class="stat-badge pending">
                                                <i class="fas fa-clock"></i> {{ $studentCounts['pending'] }} Pending
                                            </span>
                                        @endif
                                        @if($studentCounts['submitted'] > 0)
                                            <span class="stat-badge submitted">
                                                <i class="fas fa-paper-plane"></i> {{ $studentCounts['submitted'] }} Submitted
                                            </span>
                                        @endif
                                        @if($studentCounts['graded'] > 0)
                                            <span class="stat-badge graded">
                                                <i class="fas fa-check-circle"></i> {{ $studentCounts['graded'] }} Graded
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="assignment-meta">
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="fas fa-book"></i>
                                        </div>
                                        <span>{{ $assignment->courseType->course_type ?? 'No Course' }}</span>
                                    </div>
                                    
                                    @if($dueDate)
                                        <div class="meta-item">
                                            <div class="meta-icon">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                            <span class="due-date {{ $isOverdue ? 'overdue' : ($isDueSoon ? 'upcoming' : 'future') }}">
                                                Due: {{ $dueDate->format('M d, Y') }}
                                            </span>
                                        </div>
                                    @endif
                                    
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="fas fa-file"></i>
                                        </div>
                                        <span>{{ $assignment->files->count() }} Files</span>
                                    </div>
                                    
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="fas fa-clock"></i>
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
                        <i class="fas fa-tasks"></i>
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
function resetFilters() {
    document.getElementById('filterForm').reset();
    window.location.href = "{{ route('admin.assignments.index') }}";
}

// Auto-submit filters on change
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const filterInputs = filterForm.querySelectorAll('select[name], input[name="search"]');
    
    filterInputs.forEach(input => {
        if (input.name !== 'search') { // Don't auto-submit on search keystrokes
            input.addEventListener('change', function() {
                filterForm.submit();
            });
        }
    });
    
    // Debounced search
    let searchTimeout;
    const searchInput = filterForm.querySelector('input[name="search"]');
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 500);
    });
});
</script>
@endsection
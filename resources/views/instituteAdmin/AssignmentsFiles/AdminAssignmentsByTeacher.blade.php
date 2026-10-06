@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<style>
    .teacher-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .teacher-profile {
        display: flex;
        align-items: center;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .teacher-avatar-xl {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 2.5rem;
        border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .teacher-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid #e3e6f0;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
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

    .student-stats {
        display: flex;
        gap: 1rem;
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

    @media (max-width: 768px) {
        .teacher-profile {
            flex-direction: column;
            text-align: center;
        }
        
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

<div class="container-fluid">
    <div class="teacher-container">
        <!-- Header -->
        <div class="teacher-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-2">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Teacher Assignments
                    </h3>
                    <p class="mb-0 opacity-75">All assignments created by {{ $employee->name }}</p>
                </div>
                <div>
                    <a href="{{ route('admin.assignments.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-1"></i>All Teachers
                    </a>
                </div>
            </div>
        </div>

        <!-- Teacher Profile -->
        <div class="teacher-profile">
            <div class="teacher-avatar-xl">
                {{ substr($employee->name, 0, 1) }}
            </div>
            <div>
                <h4 class="mb-1">{{ $employee->name }}</h4>
                <p class="mb-1">{{ $employee->designation }}</p>
                <p class="mb-0 text-light opacity-75">
                    <i class="fas fa-id-card me-1"></i>Employee ID: {{ $employee->employee_id }}
                </p>
            </div>
        </div>

        <!-- Teacher Statistics -->
        <div class="teacher-stats">
            <div class="stat-card">
                <div class="stat-number text-primary">{{ $teacherStats['total_assignments'] }}</div>
                <div class="stat-label">Total Assignments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number text-success">{{ $teacherStats['total_students'] }}</div>
                <div class="stat-label">Students Assigned</div>
            </div>
            <div class="stat-card">
                <div class="stat-number text-info">{{ $teacherStats['submitted'] }}</div>
                <div class="stat-label">Submitted Work</div>
            </div>
            <div class="stat-card">
                <div class="stat-number text-warning">{{ $teacherStats['graded'] }}</div>
                <div class="stat-label">Graded Work</div>
            </div>
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
                        @endphp
                        
                        <div class="assignment-card">
                            <div class="assignment-header">
                                <div>
                                    <span class="badge-department">
                                        {{ $assignment->department->department ?? 'No Department' }}
                                    </span>
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
                                
                                <!-- Student Statistics (You might need to load these separately) -->
                                @php
                                    // In a real implementation, you would load these stats
                                    $studentStats = [
                                        'pending' => 0,
                                        'submitted' => 0,
                                        'graded' => 0
                                    ];
                                @endphp
                                
                                @if(array_sum($studentStats) > 0)
                                    <div class="student-stats">
                                        @if($studentStats['pending'] > 0)
                                            <span class="stat-badge pending">
                                                <i class="fas fa-clock"></i> {{ $studentStats['pending'] }} Pending
                                            </span>
                                        @endif
                                        @if($studentStats['submitted'] > 0)
                                            <span class="stat-badge submitted">
                                                <i class="fas fa-paper-plane"></i> {{ $studentStats['submitted'] }} Submitted
                                            </span>
                                        @endif
                                        @if($studentStats['graded'] > 0)
                                            <span class="stat-badge graded">
                                                <i class="fas fa-check-circle"></i> {{ $studentStats['graded'] }} Graded
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
                                            <span class="{{ $isOverdue ? 'text-danger' : ($isDueSoon ? 'text-warning' : 'text-success') }}">
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
                        <p class="text-muted">{{ $employee->name }} hasn't created any assignments yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// Auto-update page when filters change
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    }
});
</script>
@endsection
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<style>
    .detail-header {
        background: linear-gradient(135deg, #4e73df 0%, #2e59d9 100%);
        border-radius: 12px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .stats-grid {
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

    .info-card {
        background: white;
        border-radius: 10px;
        border: 1px solid #e3e6f0;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .info-card-header {
        background: #f8f9fc;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e3e6f0;
        font-weight: 600;
        color: #2e59d9;
    }

    .info-card-body {
        padding: 1.5rem;
    }

    .teacher-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f8f9fc;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .teacher-avatar-lg {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4e73df 0%, #2e59d9 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.5rem;
    }

    .file-list {
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        overflow: hidden;
    }

    .file-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #e3e6f0;
        transition: background 0.2s ease;
    }

    .file-item:last-child {
        border-bottom: none;
    }

    .file-item:hover {
        background: #f8f9fc;
    }

    .file-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        margin-right: 1rem;
        font-size: 1.2rem;
    }

    .file-icon.pdf { background: #ffe8e6; color: #e74a3b; }
    .file-icon.image { background: #e8f6f3; color: #1cc88a; }
    .file-icon.document { background: #e8f4fd; color: #3498db; }
    .file-icon.archive { background: #f8f9fc; color: #6c757d; }

    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-download {
        background: #4e73df;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .btn-download:hover {
        background: #2e59d9;
        transform: translateY(-1px);
    }

    .progress-container {
        margin-top: 0.5rem;
    }

    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
    }

    .timeline-info {
        color: #6c757d;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .timeline-info i {
        margin-right: 0.5rem;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
        }
    }
</style>

<div class="container-fluid">
    <div class="assignment-detail-container">
        <!-- Header -->
        <div class="detail-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h3 class="mb-2">
                        <i class="fas fa-file-alt me-2"></i>{{ $assignment->title }}
                    </h3>
                    <p class="mb-0 opacity-75">{{ $assignment->description }}</p>
                </div>
                <div>
                    <a href="{{ route('admin.assignments.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-1"></i>Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number text-primary">{{ $studentStats['total'] }}</div>
                <div class="stat-label">Total Students</div>
            </div>
            <div class="stat-card">
                <div class="stat-number text-warning">{{ $studentStats['pending'] }}</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number text-info">{{ $studentStats['submitted'] }}</div>
                <div class="stat-label">Submitted</div>
            </div>
            <div class="stat-card">
                <div class="stat-number text-success">{{ $studentStats['graded'] }}</div>
                <div class="stat-label">Graded</div>
            </div>
            @if($averageMarks)
                <div class="stat-card">
                    <div class="stat-number" style="color: #764ba2;">{{ number_format($averageMarks, 1) }}</div>
                    <div class="stat-label">Average Marks</div>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons mb-4">
            <a href="{{ route('admin.assignments.submissions', $assignment->id) }}" class="btn btn-primary">
                <i class="fas fa-list-check me-1"></i>View All Submissions
            </a>
            <button class="btn btn-success" onclick="window.print()">
                <i class="fas fa-print me-1"></i>Print Report
            </button>
            <form action="{{ route('admin.assignments.destroy', $assignment->id) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this assignment? This will also delete all student submissions.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i>Delete Assignment
                </button>
            </form>
        </div>

        <div class="row">
            <!-- Left Column - Assignment Info -->
            <div class="col-lg-8">
                <!-- Teacher Info -->
                <div class="info-card">
                    <div class="info-card-header">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Teacher Information
                    </div>
                    <div class="info-card-body">
                        <div class="teacher-card">
                            <div class="teacher-avatar-lg">
                                {{ substr($assignment->employee->name ?? 'T', 0, 1) }}
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $assignment->employee->name ?? 'Unknown Teacher' }}</h5>
                                <p class="mb-1 text-muted">{{ $assignment->employee->designation ?? 'Teacher' }}</p>
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-id-card me-1"></i>ID: {{ $assignment->employee->employee_id ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assignment Details -->
                <div class="info-card">
                    <div class="info-card-header">
                        <i class="fas fa-info-circle me-2"></i>Assignment Details
                    </div>
                    <div class="info-card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="40%"><strong>Department:</strong></td>
                                        <td>{{ $assignment->department->department ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Course Type:</strong></td>
                                        <td>{{ $assignment->courseType->course_type ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Subject:</strong></td>
                                        <td>
                                            @php
                                                $subject = \App\Models\SubjectsCoursewise::where('subject_id', $assignment->subject_id)->first();
                                            @endphp
                                            {{ $subject->subject_name ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Semester:</strong></td>
                                        <td>{{ $assignment->semester_id ?? 'All' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="40%"><strong>Created On:</strong></td>
                                        <td>{{ $assignment->created_at->format('M d, Y h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Due Date:</strong></td>
                                        <td>
                                            @if($assignment->due_date)
                                                @php
                                                    $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                                                    $isOverdue = $dueDate->isPast();
                                                @endphp
                                                <span class="{{ $isOverdue ? 'text-danger' : 'text-success' }}">
                                                    {{ $dueDate->format('M d, Y h:i A') }}
                                                    @if($isOverdue)
                                                        <br><small class="text-danger">Overdue</small>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-muted">No due date</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            @php
                                                $status = 'Active';
                                                if ($assignment->due_date && \Carbon\Carbon::parse($assignment->due_date)->isPast()) {
                                                    $status = 'Completed';
                                                }
                                            @endphp
                                            <span class="badge bg-info">{{ $status }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Files & Progress -->
            <div class="col-lg-4">
                <!-- Attached Files -->
                @if($assignment->files->count() > 0)
                    <div class="info-card">
                        <div class="info-card-header">
                            <i class="fas fa-paperclip me-2"></i>Attached Files ({{ $assignment->files->count() }})
                        </div>
                        <div class="info-card-body">
                            <div class="file-list">
                                @foreach($assignment->files as $file)
                                    <div class="file-item">
                                        <div class="file-icon {{ $file->file_type == 'pdf' ? 'pdf' : 
                                                               (in_array($file->file_type, ['jpg','jpeg','png','gif']) ? 'image' : 
                                                               (in_array($file->file_type, ['zip','rar']) ? 'archive' : 'document')) }}">
                                            <i class="fas fa-file"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-medium">{{ $file->original_name }}</div>
                                            <small class="text-muted">{{ strtoupper($file->file_type) }} • 
                                                {{ \Carbon\Carbon::parse($file->created_at)->format('M d') }}
                                            </small>
                                        </div>
                                        <a href="{{ route('admin.assignments.download.file', ['assignment' => $assignment->id, 'file' => $file->id]) }}" 
                                           class="btn-download" download>
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Submission Progress -->
                <div class="info-card">
                    <div class="info-card-header">
                        <i class="fas fa-chart-line me-2"></i>Submission Progress
                    </div>
                    <div class="info-card-body">
                        @if($studentStats['total'] > 0)
                            <div class="progress-container">
                                <div class="progress-label">
                                    <span>Pending</span>
                                    <span>{{ $studentStats['pending'] }} ({{ round(($studentStats['pending']/$studentStats['total'])*100) }}%)</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: {{ ($studentStats['pending']/$studentStats['total'])*100 }}%"></div>
                                </div>
                            </div>
                            
                            <div class="progress-container">
                                <div class="progress-label">
                                    <span>Submitted</span>
                                    <span>{{ $studentStats['submitted'] }} ({{ round(($studentStats['submitted']/$studentStats['total'])*100) }}%)</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-info" style="width: {{ ($studentStats['submitted']/$studentStats['total'])*100 }}%"></div>
                                </div>
                            </div>
                            
                            <div class="progress-container">
                                <div class="progress-label">
                                    <span>Graded</span>
                                    <span>{{ $studentStats['graded'] }} ({{ round(($studentStats['graded']/$studentStats['total'])*100) }}%)</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: {{ ($studentStats['graded']/$studentStats['total'])*100 }}%"></div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No students assigned yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Print styles
const printStyles = `
    @media print {
        .detail-header { background: #4e73df !important; -webkit-print-color-adjust: exact; }
        .stat-card { border: 1px solid #ddd !important; }
        .action-buttons { display: none !important; }
        .btn { display: none !important; }
        .info-card { break-inside: avoid; }
    }
`;

// Add print styles
const style = document.createElement('style');
style.innerHTML = printStyles;
document.head.appendChild(style);
</script>
@endsection
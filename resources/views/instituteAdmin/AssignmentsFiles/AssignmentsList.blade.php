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

.page-header
{
    background:var(--primary-gradient);
    padding:30px 20px;
}

/* Card Styles */
.card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
    margin-bottom: 20px;
    border: 2px solid rgba(67, 97, 238, 0.1);
    background: white;
}

.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.15);
}

/* Assignment Card */
.assignment-card {
    border-left: 5px solid var(--primary-color);
    transition: all 0.3s ease;
}

.assignment-card.pending {
    border-left-color: #f59e0b;
}

.assignment-card.submitted {
    border-left-color: #3b82f6;
}

.assignment-card.graded {
    border-left-color: var(--success-color);
}

.assignment-card.overdue {
    border-left-color: #ef4444;
    background: linear-gradient(135deg, #fff5f5 0%, #fee2e2 100%);
}

/* Badge Styles */
.badge {
    font-size: 0.7rem;
    padding: 0.4em 0.9em;
    border-radius: 30px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.badge-pending {
    background: var(--warning-gradient);
    color: white;
    box-shadow: 0 3px 10px rgba(245, 158, 11, 0.3);
}

.badge-submitted {
    background: var(--info-gradient);
    color: white;
    box-shadow: 0 3px 10px rgba(59, 130, 246, 0.3);
}

.badge-graded {
    background: var(--success-gradient);
    color: white;
    box-shadow: 0 3px 10px rgba(16, 185, 129, 0.3);
}

.badge-overdue {
    background: var(--danger-gradient);
    color: white;
    box-shadow: 0 3px 10px rgba(239, 68, 68, 0.3);
}

/* Button Styles */
.btn {
    border-radius: 30px;
    font-weight: 600;
    padding: 0.5rem 1.25rem;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.8rem;
}

.btn-primary {
    background: var(--primary-gradient);
    border: none;
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
}

.btn-outline-secondary {
    border: 2px solid #cbd5e1;
    color: #64748b;
}

.btn-outline-secondary:hover {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    border-color: #94a3b8;
}

.btn-outline-primary {
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
}

.btn-outline-primary:hover {
    background: var(--primary-gradient);
    border-color: transparent;
    color: white;
}

.btn-success {
    background: var(--success-gradient);
    border: none;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    color: white;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

/* Stats Cards */
.stats-card {
    border: none;
    border-radius: 18px;
    overflow: hidden;
    position: relative;
    transition: transform 0.3s ease;
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

.stats-card.pending::before {
    background: var(--warning-gradient);
}

.stats-card.submitted::before {
    background: var(--info-gradient);
}

.stats-card.graded::before {
    background: var(--success-gradient);
}

.stats-card.overdue::before {
    background: var(--danger-gradient);
}

.stats-number {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 0.25rem;
}

.stats-label {
    font-size: 0.8rem;
    color: #64748b;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Assignment Header */
.assignment-header {
    background: var(--primary-gradient);
    padding: 1.5rem;
    color: white;
    margin-bottom: 0;
    position: relative;
    overflow: hidden;
}

.assignment-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: headerPulse 4s ease-in-out infinite;
}

@keyframes headerPulse {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.1); opacity: 0.3; }
}

.assignment-header h5,
.assignment-header p {
    position: relative;
    z-index: 1;
}

.meta-icon {
    background: rgba(255, 255, 255, 0.2);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}

/* Due Date */
.due-date {
    font-size: 0.95rem;
    font-weight: 700;
}

.due-date.overdue {
    color: #ef4444;
    animation: pulse 2s infinite;
}

.due-date.upcoming {
    color: #f59e0b;
}

.due-date.future {
    color: var(--success-color);
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

/* Assignment Title */
.assignment-title {
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

/* File Preview */
.file-preview {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 12px;
    padding: 0.75rem;
    margin-top: 0.5rem;
    border: 1px solid rgba(67, 97, 238, 0.1);
}

.file-item {
    display: flex;
    align-items: center;
    padding: 0.5rem;
    border-radius: 10px;
    margin-bottom: 0.5rem;
    background: white;
    border: 1px solid rgba(67, 97, 238, 0.1);
    transition: all 0.2s ease;
}

.file-item:hover {
    background: linear-gradient(135deg, rgba(67, 97, 238, 0.04), rgba(58, 12, 163, 0.04));
    transform: translateX(4px);
}

.file-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    margin-right: 1rem;
    font-size: 1.2rem;
}

.file-icon.pdf {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #dc2626;
}

.file-icon.image {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #059669;
}

.file-icon.document {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #2563eb;
}

/* Graded Result */
.graded-result {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    border-radius: 12px;
    padding: 1rem;
    border: 2px solid var(--success-color);
    margin-top: 1rem;
}

.grade-display {
    font-size: 2.5rem;
    font-weight: 800;
    background: var(--success-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-align: center;
    margin-bottom: 0.5rem;
}

.grade-label {
    text-align: center;
    color: #059669;
    font-weight: 700;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Submission Info */
.submission-info {
    background: linear-gradient(135deg, #f8fafc, #eef2ff);
    border-radius: 12px;
    padding: 1rem;
    margin-top: 1rem;
    border-left: 4px solid var(--primary-color);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    background: linear-gradient(135deg, #f8fafc, #eef2ff);
    border-radius: 16px;
    margin: 2rem 0;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    opacity: 0.7;
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.modal-header {
    background: var(--primary-gradient);
    color: white;
    border-bottom: none;
    padding: 1.2rem 1.5rem;
}

.modal-title {
    font-weight: 700;
}

.modal-body {
    padding: 1.5rem;
}

.btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
}

/* Text Colors */
.text-warning {
    background: var(--warning-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.text-success {
    background: var(--success-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.text-danger {
    background: var(--danger-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.text-info {
    background: var(--info-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
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

/* Responsive */
@media (max-width: 768px) {
    .assignment-header .d-flex {
        flex-direction: column;
        gap: 10px;
    }
    
    .stats-number {
        font-size: 1.5rem;
    }
}
</style>

@php
$courseLabel = (isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School')
? 'Class'
: 'Course';
@endphp

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1" style="color: #fff;">
                                <i class="fas fa-tasks me-2"></i>My Assignments
                            </h4>
                            <p class="mb-0 text-muted" style="color: #fff!important;" >View, submit, and track your academic assignments</p>
                        </div>
                        <div class="text-muted">
                            <div class="d-flex align-items-center gap-2" style="color:#fff!immportant;">
                                <div class="d-flex align-items-center text-white">
                                    <div class="me-2" style="width: 10px; height: 10px; border-radius: 50%; background: #f59e0b;"></div>
                                    <small>Pending</small>
                                </div>
                                <div class="d-flex align-items-center text-white">
                                    <div class="me-2" style="width: 10px; height: 10px; border-radius: 50%; background: #3b82f6;"></div>
                                    <small>Submitted</small>
                                </div>
                                <div class="d-flex align-items-center text-white">
                                    <div class="me-2" style="width: 10px; height: 10px; border-radius: 50%; background: #10b981;"></div>
                                    <small>Graded</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card pending">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="stats-label">Pending</div>
                            <div class="stats-number text-warning">
                                {{ $assignments->where('status', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x opacity-50" style="color: #f59e0b;"></i>
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
                            <div class="stats-label">Submitted</div>
                            <div class="stats-number text-info">
                                {{ $assignments->where('status', 'submitted')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-paper-plane fa-2x opacity-50" style="color: #3b82f6;"></i>
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
                            <div class="stats-label">Graded</div>
                            <div class="stats-number text-success">
                                {{ $assignments->where('status', 'graded')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x opacity-50" style="color: #10b981;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card overdue">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="stats-label">Overdue</div>
                            <div class="stats-number text-danger">
                                @php
                                $overdueCount = 0;
                                foreach($assignments as $a) {
                                if (\Carbon\Carbon::parse($a->assignment->due_date ?? now())->isPast() && $a->status ==
                                'pending') {
                                $overdueCount++;
                                }
                                }
                                @endphp
                                {{ $overdueCount }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x opacity-50" style="color: #ef4444;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assignments List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="assignment-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 text-white">
                                <i class="fas fa-list-check me-2"></i>Assignment List
                            </h5>
                            <p class="mb-0 opacity-75">Total {{ $assignments->count() }} assignments</p>
                        </div>
                        <div class="assignment-meta">
                            <div class="meta-item">
                                <div class="meta-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="meta-text">
                                    {{ now()->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if($assignments->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($assignments as $a)
                        @php
                        $dueDate = \Carbon\Carbon::parse($a->assignment->due_date ?? now());
                        $now = \Carbon\Carbon::now();
                        $isOverdue = $dueDate->isPast() && $a->status == 'pending';
                        $isDueSoon = $dueDate->diffInDays($now) <= 2 && !$dueDate->isPast();
                            $statusClass = $a->status;
                            if ($isOverdue) $statusClass = 'overdue';
                            @endphp

                            <div class="list-group-item border-0 p-4 assignment-card {{ $statusClass }}">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="assignment-title">
                                                        <i class="fas fa-file-alt me-2"></i>{{ $a->assignment->title }}
                                                    </div>
                                                    @if($isOverdue)
                                                    <span class="badge badge-overdue ms-2">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>OVERDUE
                                                    </span>
                                                    @endif
                                                </div>

                                                @if($a->assignment->description)
                                                <div class="assignment-description mb-3" style="color: #64748b;">
                                                    {{ $a->assignment->description }}
                                                </div>
                                                @endif

                                                <!-- Assignment Files Preview -->
                                                @if($a->assignment->files && $a->assignment->files->count() > 0)
                                                <div class="file-preview">
                                                    <small class="text-muted mb-2 d-block">
                                                        <i class="fas fa-paperclip me-1"></i>
                                                        Assignment Files ({{ $a->assignment->files->count() }})
                                                    </small>
                                                    <div class="row">
                                                        @foreach($a->assignment->files->take(2) as $file)
                                                        @php
                                                        $fileIcon = $file->file_type == 'pdf' ? 'fa-file-pdf text-danger' :
                                                        (in_array($file->file_type, ['jpg', 'jpeg', 'png', 'gif']) ?
                                                        'fa-file-image text-success' :
                                                        'fa-file text-primary');
                                                        @endphp
                                                        <div class="col-sm-6 mb-2">
                                                            <div class="file-item">
                                                                <div class="file-icon {{ $file->file_type == 'pdf' ? 'pdf' : (in_array($file->file_type, ['jpg', 'jpeg', 'png', 'gif']) ? 'image' : 'document') }}">
                                                                    <i class="fas {{ $fileIcon }}"></i>
                                                                </div>
                                                                <div class="file-info">
                                                                    <div class="file-name text-truncate" style="max-width: 150px;">
                                                                        {{ basename($file->original_name) }}
                                                                    </div>
                                                                    <div class="file-size" style="color: #94a3b8;">
                                                                        {{ strtoupper($file->file_type) }}
                                                                    </div>
                                                                </div>
                                                                <button class="btn btn-sm btn-outline-primary" onclick="previewFile('{{ $file->url }}', '{{ $file->file_type }}')">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif

                                                <!-- Submission Info -->
                                                @if($a->status == 'submitted' && $a->submissions->count() > 0)
                                                <div class="submission-info">
                                                    <small class="text-muted mb-1 d-block">
                                                        <i class="fas fa-paper-plane me-1"></i>
                                                        Last Submitted:
                                                        @php
                                                        $submittedAt = $a->submitted_at ? \Carbon\Carbon::parse($a->submitted_at) : null;
                                                        @endphp
                                                        @if($submittedAt)
                                                        {{ $submittedAt->format('M d, Y h:i A') }}
                                                        @else
                                                        N/A
                                                        @endif
                                                    </small>
                                                    @foreach($a->submissions->take(1) as $submission)
                                                    @php
                                                    $submissionDate = $submission->submitted_at ? \Carbon\Carbon::parse($submission->submitted_at) : null;
                                                    @endphp
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div>
                                                            <small>
                                                                <i class="fas fa-file me-1"></i>
                                                                {{ basename($submission->original_name) }}
                                                            </small>
                                                            @if($submissionDate)
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-clock me-1"></i>
                                                                {{ $submissionDate->format('M d, Y h:i A') }}
                                                            </small>
                                                            @endif
                                                        </div>
                                                        <button class="btn btn-sm btn-outline-primary btn-sm" onclick="previewFile('{{ $submission->url }}', '{{ $submission->file_type }}')">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="d-flex flex-column gap-3">
                                            <div class="text-center mb-3">
                                                <span class="badge badge-{{ $a->status }}">
                                                    <i class="fas {{ $a->status == 'pending' ? 'fa-clock' : ($a->status == 'submitted' ? 'fa-paper-plane' : 'fa-check-circle') }} me-1"></i>
                                                    {{ ucfirst($a->status) }}
                                                </span>
                                            </div>

                                            <div class="text-center mb-3">
                                                <small class="text-muted d-block mb-1">
                                                    <i class="fas fa-calendar-alt me-1"></i>Due Date
                                                </small>
                                                <div class="due-date {{ $isOverdue ? 'overdue' : ($isDueSoon ? 'upcoming' : 'future') }}">
                                                    {{ $dueDate->format('M d, Y') }}
                                                    <br>
                                                    <small>{{ $dueDate->format('h:i A') }}</small>
                                                    @if($isOverdue)
                                                    <br>
                                                    <small class="text-danger fw-bold">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        {{ $dueDate->diffForHumans() }}
                                                    </small>
                                                    @elseif($isDueSoon)
                                                    <br>
                                                    <small class="text-warning fw-bold">
                                                        Due in {{ $dueDate->diffInDays($now) }} days
                                                    </small>
                                                    @endif
                                                </div>
                                            </div>

                                            @if($a->status == 'graded' && $a->marks)
                                            <div class="graded-result">
                                                <div class="grade-display">{{ $a->marks }}</div>
                                                <div class="grade-label">Marks Obtained</div>
                                                @if($a->feedback)
                                                <div class="feedback mt-2 p-2 bg-light rounded">
                                                    <small class="text-muted">
                                                        <i class="fas fa-comment me-1"></i>
                                                        <strong>Feedback:</strong> {{ $a->feedback }}
                                                    </small>
                                                </div>
                                                @endif
                                            </div>
                                            @endif

                                            <div class="action-buttons justify-content-center d-flex gap-2 flex-wrap">
                                                @if($a->status == 'pending' || $a->status == 'submitted')
                                                <a href="{{ route('student.assignments.submit', $a->id) }}" class="btn btn-primary">
                                                    <i class="fas fa-paper-plane me-1"></i>
                                                    {{ $a->status == 'submitted' ? 'Resubmit' : 'Submit' }}
                                                </a>
                                                <button class="btn btn-outline-secondary" onclick="viewAssignmentDetails({{ $a->assignment->id }})">
                                                    <i class="fas fa-eye me-1"></i>View
                                                </button>
                                                @elseif($a->status == 'graded')
                                                <button class="btn btn-success" onclick="viewGradeDetails({{ $a->id }})">
                                                    <i class="fas fa-chart-line me-1"></i>View Results
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-tasks"></i>
                        <h5 style="color: var(--primary-color);">No Assignments Found</h5>
                        <p class="text-muted">You don't have any assignments assigned to you at the moment.</p>
                        <div class="mt-3">
                            <button class="btn btn-primary" onclick="location.reload()">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- File Preview Modal -->
<div class="modal fade" id="filePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filePreviewTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="filePreviewContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a id="downloadFileBtn" href="#" target="_blank" class="btn btn-primary">
                    <i class="fas fa-download me-1"></i>Download
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Assignment Details Modal -->
<div class="modal fade" id="assignmentDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assignment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="assignmentDetailsContent"></div>
            </div>
        </div>
    </div>
</div>

<!-- Grade Details Modal -->
<div class="modal fade" id="gradeDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Grade Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="gradeDetailsContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printGradeDetails()">
                    <i class="fas fa-print me-1"></i>Print
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    
const instituteType = "{{ $serviceInstitutedetails->type ?? '' }}";
const courseLabel = instituteType === 'School' ? 'Class' : 'Course';
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltips.map(function(tooltip) {
        return new bootstrap.Tooltip(tooltip);
    });
});

// File preview function
function previewFile(fileUrl, fileType) {
    const modal = new bootstrap.Modal(document.getElementById('filePreviewModal'));
    const title = document.getElementById('filePreviewTitle');
    const content = document.getElementById('filePreviewContent');
    const downloadBtn = document.getElementById('downloadFileBtn');

    // Set download link
    downloadBtn.href = fileUrl;

    // Check file type and display accordingly
    const fileExt = fileType.toLowerCase();
    const fileName = fileUrl.split('/').pop();

    title.textContent = fileName;

    if (['pdf'].includes(fileExt)) {
        // For PDF, use iframe for better compatibility
        content.innerHTML = `
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle me-2"></i>
                PDF files are best viewed by downloading.
            </div>
            <iframe src="${fileUrl}" width="100%" height="600px" style="border: none;"></iframe>
        `;
    } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
        // For images
        content.innerHTML = `
            <img src="${fileUrl}" class="img-fluid rounded" alt="${fileName}" 
                 style="max-height: 70vh; object-fit: contain;">
        `;
    } else if (['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'].includes(fileExt)) {
        // For office documents - show message
        content.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-file fa-4x text-primary mb-3"></i>
                <h5 class="mb-3">${fileName}</h5>
                <p class="text-muted">This file (${fileExt.toUpperCase()}) cannot be previewed in the browser.</p>
                <p class="text-muted">Please download the file to view it.</p>
                <div class="mt-3">
                    <a href="${fileUrl}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-download me-1"></i>Download File
                    </a>
                </div>
            </div>
        `;
    } else {
        // For other file types
        content.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-file fa-4x text-secondary mb-3"></i>
                <h5 class="mb-3">${fileName}</h5>
                <p class="text-muted">File type: ${fileExt.toUpperCase()}</p>
                <div class="mt-3">
                    <a href="${fileUrl}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-download me-1"></i>Download File
                    </a>
                </div>
            </div>
        `;
    }

    modal.show();
}

// View assignment details - Update this function in your blade file
function viewAssignmentDetails(assignmentId) {
    const modal = new bootstrap.Modal(document.getElementById('assignmentDetailsModal'));
    const content = document.getElementById('assignmentDetailsContent');

    // Show loading
    content.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Loading assignment details...</p>
        </div>
    `;

    modal.show();

    // Use the correct route for students
    fetch(`/student/assignments/${assignmentId}/details`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.assignment) {
                const assignment = data.assignment;
                let filesHtml = '';

                if (assignment.files && assignment.files.length > 0) {
                    filesHtml = `
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Attached Files</h6>
                            <div class="row">
                                ${assignment.files.map(file => `
                                    <div class="col-md-6 mb-2">
                                        <div class="file-item d-flex align-items-center p-2 border rounded">
                                            <i class="fas ${file.icon} me-2 ${file.file_type == 'pdf' ? 'text-danger' : 
                                                         (['jpg','jpeg','png','gif'].includes(file.file_type) ? 'text-success' : 
                                                          'text-primary')}"></i>
                                            <span class="flex-grow-1">${file.file_name}</span>
                                            <button class="btn btn-sm btn-outline-primary ms-2" 
                                                    onclick="previewFile('${file.download_url}', '${file.file_type}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `;
                }

                content.innerHTML = `
                    <div class="assignment-details">
                        <h4 class="mb-3">${assignment.title}</h4>
                        
                        ${assignment.description ? `
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Description</h6>
                                <div class="p-3 bg-light rounded">${assignment.description}</div>
                            </div>
                        ` : ''}
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Assignment Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>${courseLabel} Type:</strong></td>
                                        <td>${assignment.course_type || 'N/A'}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>${courseLabel}</strong></td>
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
                                <h6 class="text-muted mb-2">Timeline</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Created On:</strong></td>
                                        <td>${new Date(assignment.created_at).toLocaleDateString()}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Due Date:</strong></td>
                                        <td>${new Date(assignment.due_date).toLocaleDateString()}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td><span class="badge bg-info">${assignment.status || 'Active'}</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        ${filesHtml}
                        
                        <div class="mt-4">
                            <button class="btn btn-primary" onclick="window.location.href='/student/assignments/${assignmentId}/submit'">
                                <i class="fas fa-paper-plane me-1"></i>Submit Assignment
                            </button>
                        </div>
                    </div>
                `;
            } else if (data.error) {
                content.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ${data.error}
                    </div>
                `;
            } else {
                content.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Assignment details not found.
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error loading assignment details. Please try again.
                </div>
            `;
        });
}

// View grade details with AJAX
function viewGradeDetails(assignmentStudentId) {
    const modal = new bootstrap.Modal(document.getElementById('gradeDetailsModal'));
    const content = document.getElementById('gradeDetailsContent');
    
    // Show loading
    content.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Loading grade details...</p>
        </div>
    `;
    
    modal.show();
    
    // Fetch grade details via AJAX
    fetch(`/student/assignments/${assignmentStudentId}/grade-details`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.grade_details) {
                const grade = data.grade_details;
                const assignment = grade.assignment;
                const student = grade.student;
                
                // Format dates
                const submittedDate = grade.submitted_at ? new Date(grade.submitted_at).toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }) : 'Not submitted';
                
                const gradedDate = grade.graded_at ? new Date(grade.graded_at).toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }) : 'Not graded yet';
                
                const dueDate = assignment.due_date ? new Date(assignment.due_date).toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }) : 'No due date';
                
                // Calculate grade percentage and letter grade
                const percentage = grade.marks;
                const letterGrade = calculateLetterGrade(percentage);
                const gradeColor = getGradeColor(letterGrade);
                
                content.innerHTML = `
                    <div id="gradeDetailsPrint">
                        <!-- Header -->
                        <div class="text-center mb-4 border-bottom pb-3">
                            <h3 class="mb-2" style="color: #2e59d9;">
                                <i class="fas fa-award me-2"></i>Assignment Grade Report
                            </h3>
                            <p class="text-muted mb-0">Detailed grade information for your submission</p>
                        </div>
                        
                        <!-- Student & Assignment Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-user-graduate me-2"></i>Student Information
                                        </h6>
                                    </div>
                                    <div class="card-body ">
                                        <table class="table table-sm">
                                            <tr>
                                                <td width="40%"><strong>Name:</strong></td>
                                                <td>${student.first_name} ${student.last_name}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Registration No:</strong></td>
                                                <td>${student.registration_number}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email:</strong></td>
                                                <td>${student.email || 'N/A'}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-file-alt me-2"></i>Assignment Information
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tr>
                                                <td width="40%"><strong>Title:</strong></td>
                                                <td>${assignment.title}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Course Type:</strong></td>
                                                <td>${assignment.course_type || 'N/A'}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Subject:</strong></td>
                                                <td>${assignment.subject || 'N/A'}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Grade Summary -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-bar me-2"></i>Grade Summary
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-3 mb-3">
                                        <div class="grade-circle mx-auto" style="width: 120px; height: 120px; border-radius: 50%; border: 6px solid ${gradeColor}; display: flex; align-items: center; justify-content: center; background: ${gradeColor}20;">
                                            <div>
                                                <div class="display-4 fw-bold" style="color: ${gradeColor};">${percentage}</div>
                                                <small class="text-muted">Marks</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="p-3 border rounded">
                                            <div class="display-4 fw-bold" style="color: ${gradeColor};">${letterGrade}</div>
                                            <small class="text-muted">Letter Grade</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="grade-scale p-3 border rounded">
                                            <h6 class="mb-2">Grading Scale:</h6>
                                            <div class="row small">
                                                <div class="col-4">A: 90-100%</div>
                                                <div class="col-4">B: 80-89%</div>
                                                <div class="col-4">C: 70-79%</div>
                                                <div class="col-4">D: 60-69%</div>
                                                <div class="col-4">F: 0-59%</div>
                                            </div>
                                            <div class="mt-2">
                                                <div class="progress" style="height: 10px;">
                                                    <div class="progress-bar bg-success" style="width: ${percentage}%"></div>
                                                </div>
                                                <small class="text-muted">Your Score: ${percentage}%</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Timeline & Feedback -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-calendar-alt me-2"></i>Timeline
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="timeline">
                                            <div class="timeline-item ${grade.status === 'submitted' || grade.status === 'graded' ? 'completed' : ''}">
                                                <div class="timeline-marker"></div>
                                                <div class="timeline-content">
                                                    <h6 class="mb-1">Assignment Given</h6>
                                                    <p class="mb-0 text-muted small">${new Date(assignment.created_at).toLocaleDateString()}</p>
                                                </div>
                                            </div>
                                            <div class="timeline-item ${grade.status === 'submitted' || grade.status === 'graded' ? 'completed' : ''}">
                                                <div class="timeline-marker"></div>
                                                <div class="timeline-content">
                                                    <h6 class="mb-1">Due Date</h6>
                                                    <p class="mb-0 text-muted small">${dueDate}</p>
                                                </div>
                                            </div>
                                            <div class="timeline-item ${grade.status === 'submitted' || grade.status === 'graded' ? 'completed' : ''}">
                                                <div class="timeline-marker"></div>
                                                <div class="timeline-content">
                                                    <h6 class="mb-1">Submitted</h6>
                                                    <p class="mb-0 text-muted small">${submittedDate}</p>
                                                </div>
                                            </div>
                                            <div class="timeline-item ${grade.status === 'graded' ? 'completed' : ''}">
                                                <div class="timeline-marker"></div>
                                                <div class="timeline-content">
                                                    <h6 class="mb-1">Graded</h6>
                                                    <p class="mb-0 text-muted small">${gradedDate}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-comment-dots me-2"></i>Teacher's Feedback
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        ${grade.feedback ? `
                                            <div class="alert alert-info">
                                                <i class="fas fa-quote-left me-2"></i>
                                                ${grade.feedback}
                                            </div>
                                            <div class="mt-3">
                                                <h6 class="mb-2">Performance Analysis:</h6>
                                                <ul class="list-unstyled">
                                                    ${getPerformanceAnalysis(percentage)}
                                                </ul>
                                            </div>
                                        ` : `
                                            <div class="text-center py-4">
                                                <i class="fas fa-comment-slash fa-2x text-muted mb-3"></i>
                                                <p class="text-muted">No feedback provided by the teacher.</p>
                                            </div>
                                        `}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submitted Files (if any) -->
                        ${grade.submissions && grade.submissions.length > 0 ? `
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-paperclip me-2"></i>Your Submitted Files
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        ${grade.submissions.map(submission => `
                                            <div class="col-md-4 mb-3">
                                                <div class="file-card border rounded p-3">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <div class="file-icon me-2">
                                                            <i class="fas ${getFileIcon(submission.file_type)}"></i>
                                                        </div>
                                                        <div class="file-info">
                                                            <div class="file-name text-truncate" style="max-width: 150px;">
                                                                ${submission.original_name}
                                                            </div>
                                                            <small class="text-muted">${submission.file_type.toUpperCase()}</small>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-sm btn-outline-primary flex-grow-1"
                                                                onclick="previewFile('${submission.download_url}', '${submission.file_type}')">
                                                            <i class="fas fa-eye me-1"></i>Preview
                                                        </button>
                                                        <a href="${submission.download_url}" 
                                                           class="btn btn-sm btn-outline-success"
                                                           download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </div>
                                                    <small class="text-muted mt-2 d-block">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Submitted: ${new Date(submission.submitted_at).toLocaleDateString()}
                                                    </small>
                                                </div>
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                            </div>
                        ` : ''}
                        
                        <!-- Grade Breakdown (if available) -->
                        ${grade.grade_breakdown ? `
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-list-check me-2"></i>Grade Breakdown
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Criteria</th>
                                                    <th>Weight</th>
                                                    <th>Your Score</th>
                                                    <th>Max Score</th>
                                                    <th>Percentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${grade.grade_breakdown.map(item => `
                                                    <tr>
                                                        <td>${item.criteria}</td>
                                                        <td>${item.weight}%</td>
                                                        <td>${item.your_score}</td>
                                                        <td>${item.max_score}</td>
                                                        <td>
                                                            <div class="progress" style="height: 6px;">
                                                                <div class="progress-bar ${item.percentage >= 70 ? 'bg-success' : item.percentage >= 50 ? 'bg-warning' : 'bg-danger'}" 
                                                                     style="width: ${item.percentage}%"></div>
                                                            </div>
                                                            <small>${item.percentage}%</small>
                                                        </td>
                                                    </tr>
                                                `).join('')}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                `;
                
                // Add CSS for timeline
                addTimelineStyles();
            } else if (data.error) {
                content.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ${data.error}
                    </div>
                `;
            } else {
                content.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        Grade details not available yet.
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error loading grade details. Please try again.
                </div>
            `;
        });
}

// Helper functions
function calculateLetterGrade(percentage) {
    if (percentage >= 90) return 'A';
    if (percentage >= 80) return 'B';
    if (percentage >= 70) return 'C';
    if (percentage >= 60) return 'D';
    return 'F';
}

function getGradeColor(letterGrade) {
    const colors = {
        'A': '#1cc88a',
        'B': '#36b9cc',
        'C': '#f6c23e',
        'D': '#fd7e14',
        'F': '#e74a3b'
    };
    return colors[letterGrade] || '#6c757d';
}

function getPerformanceAnalysis(percentage) {
    if (percentage >= 90) {
        return `
            <li><i class="fas fa-check-circle text-success me-2"></i>Excellent work! You've mastered the concepts.</li>
            <li><i class="fas fa-check-circle text-success me-2"></i>Very thorough and well-organized submission.</li>
            <li><i class="fas fa-star text-warning me-2"></i>Outstanding performance!</li>
        `;
    } else if (percentage >= 80) {
        return `
            <li><i class="fas fa-check-circle text-success me-2"></i>Good understanding of the material.</li>
            <li><i class="fas fa-info-circle text-info me-2"></i>Some minor areas could use more detail.</li>
            <li><i class="fas fa-thumbs-up text-primary me-2"></i>Solid work overall.</li>
        `;
    } else if (percentage >= 70) {
        return `
            <li><i class="fas fa-check text-success me-2"></i>Satisfactory performance.</li>
            <li><i class="fas fa-info-circle text-info me-2"></i>Consider reviewing key concepts.</li>
            <li><i class="fas fa-lightbulb text-warning me-2"></i>Room for improvement in some areas.</li>
        `;
    } else if (percentage >= 60) {
        return `
            <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Basic understanding demonstrated.</li>
            <li><i class="fas fa-book text-info me-2"></i>Recommend reviewing the course materials.</li>
            <li><i class="fas fa-hand-point-right text-primary me-2"></i>Consider seeking additional help.</li>
        `;
    } else {
        return `
            <li><i class="fas fa-exclamation-circle text-danger me-2"></i>Needs significant improvement.</li>
            <li><i class="fas fa-book-open text-info me-2"></i>Please review all course materials.</li>
            <li><i class="fas fa-hands-helping text-primary me-2"></i>Consider meeting with the instructor.</li>
        `;
    }
}

function getFileIcon(fileType) {
    const fileExt = fileType.toLowerCase();
    if (fileExt === 'pdf') return 'fa-file-pdf text-danger';
    if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) return 'fa-file-image text-success';
    if (['doc', 'docx'].includes(fileExt)) return 'fa-file-word text-primary';
    if (['ppt', 'pptx'].includes(fileExt)) return 'fa-file-powerpoint text-warning';
    if (['xls', 'xlsx'].includes(fileExt)) return 'fa-file-excel text-success';
    if (['zip', 'rar'].includes(fileExt)) return 'fa-file-archive text-secondary';
    return 'fa-file text-muted';
}

function addTimelineStyles() {
    const style = document.createElement('style');
    style.innerHTML = `
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 11px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e3e6f0;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        .timeline-item.completed .timeline-marker {
            background: #1cc88a;
            border-color: #1cc88a;
        }
        .timeline-item.completed .timeline-content {
            color: #000;
        }
        .timeline-marker {
            position: absolute;
            left: -30px;
            top: 5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #e3e6f0;
            background: white;
            z-index: 1;
        }
        .timeline-content {
            padding-left: 10px;
        }
        .file-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 1.2rem;
        }
        .file-card {
            transition: all 0.3s ease;
        }
        .file-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    `;
    document.head.appendChild(style);
}

// Print grade details
function printGradeDetails() {
    const printContent = document.getElementById('gradeDetailsPrint').innerHTML;
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Grade Report</title>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                @media print {
                    .no-print { display: none !important; }
                    body { padding: 20px; }
                    .card { border: 1px solid #ddd !important; box-shadow: none !important; }
                    .progress { height: 10px !important; }
                }
                .grade-circle {
                    width: 120px;
                    height: 120px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 20px;
                }
                .timeline {
                    position: relative;
                    padding-left: 30px;
                }
                .timeline::before {
                    content: '';
                    position: absolute;
                    left: 11px;
                    top: 0;
                    bottom: 0;
                    width: 2px;
                    background: #e3e6f0;
                }
                .timeline-item {
                    position: relative;
                    margin-bottom: 20px;
                }
                .timeline-marker {
                    position: absolute;
                    left: -30px;
                    top: 5px;
                    width: 20px;
                    height: 20px;
                    border-radius: 50%;
                    border: 2px solid #e3e6f0;
                    background: white;
                    z-index: 1;
                }
                .timeline-item.completed .timeline-marker {
                    background: #1cc88a;
                    border-color: #1cc88a;
                }
            </style>
        </head>
        <body>
            <div class="container">
                ${printContent}
                <div class="text-center mt-4 no-print">
                    <hr>
                    <p class="text-muted small">
                        Printed on: ${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}
                    </p>
                </div>
            </div>
        </body>
        </html>
    `;
    
    window.print();
    document.body.innerHTML = originalContent;
    window.location.reload(); // Reload to restore original content
}
// Add this modal management script
document.addEventListener('DOMContentLoaded', function() {
    // Track modal count
    let modalCount = 0;
    
    // When modal is shown
    document.addEventListener('shown.bs.modal', function(event) {
        modalCount++;
        const modalElement = event.target;
        
        // Update z-index for proper stacking
        const modalBackdrops = document.querySelectorAll('.modal-backdrop');
        const modals = document.querySelectorAll('.modal');
        
        modalBackdrops.forEach((backdrop, index) => {
            backdrop.style.zIndex = 1040 + index;
        });
        
        modals.forEach((modal, index) => {
            modal.style.zIndex = 1050 + index;
        });
        
        // Enable body scrolling if it's the only modal
        if (modalCount === 1) {
            document.body.classList.add('modal-open');
            document.body.style.overflow = 'hidden';
            document.body.style.paddingRight = '0px';
        }
    });
    
    // When modal is hidden
    document.addEventListener('hidden.bs.modal', function(event) {
        modalCount--;
        
        // Remove the modal backdrop
        const modalBackdrops = document.querySelectorAll('.modal-backdrop');
        if (modalBackdrops.length > 0 && modalCount === 0) {
            modalBackdrops.forEach(backdrop => {
                backdrop.parentNode.removeChild(backdrop);
            });
            
            // Clean up body classes
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }
        
        // Update z-index for remaining modals
        if (modalCount > 0) {
            const remainingBackdrops = document.querySelectorAll('.modal-backdrop');
            const remainingModals = document.querySelectorAll('.modal.show');
            
            remainingBackdrops.forEach((backdrop, index) => {
                backdrop.style.zIndex = 1040 + index;
            });
            
            remainingModals.forEach((modal, index) => {
                modal.style.zIndex = 1050 + index;
            });
        }
    });
    
    // Fix for close buttons
    document.querySelectorAll('.btn-close, [data-bs-dismiss="modal"]').forEach(button => {
        button.addEventListener('click', function() {
            setTimeout(() => {
                // Check if any modals are still open
                const openModals = document.querySelectorAll('.modal.show');
                if (openModals.length === 0) {
                    // Clean up completely
                    const backdrops = document.querySelectorAll('.modal-backdrop');
                    backdrops.forEach(backdrop => {
                        backdrop.parentNode.removeChild(backdrop);
                    });
                    
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                    modalCount = 0;
                }
            }, 150);
        });
    });
});
</script>
@endsection
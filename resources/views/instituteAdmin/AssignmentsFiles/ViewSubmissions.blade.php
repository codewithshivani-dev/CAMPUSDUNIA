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

/* Assignment Header */
.assignment-header {
    background: var(--primary-gradient);
    border-radius: 20px;
    color: white;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(67, 97, 238, 0.2);
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

.assignment-header h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.assignment-header .btn-light {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    border-radius: 30px;
    padding: 10px 20px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.assignment-header .btn-light:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Statistics Cards */
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.98);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.12);
}

.stats-number {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 5px;
}

.stats-label {
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
}

.text-warning {
    background: var(--warning-gradient);
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

.text-success {
    background: var(--success-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Custom Student Card */
.custom-student-card {
    border: 2px solid rgba(67, 97, 238, 0.1);
    border-radius: 15px;
    transition: all 0.3s ease;
    margin-bottom: 1rem;
    overflow: hidden;
    background: white;
}

.custom-student-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.15);
    border-color: var(--primary-color);
}

.custom-card-header {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    padding: 1rem 1.5rem;
    border-bottom: 2px solid rgba(67, 97, 238, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    transition: background 0.3s ease;
}

.custom-card-header:hover {
    background: linear-gradient(135deg, #eef2ff, #e0e7ff);
}

.student-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.student-avatar {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
    box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
}

/* Status Badges */
.status-badge {
    padding: 0.3rem 1rem;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending { 
    background: var(--warning-gradient); 
    color: white;
    box-shadow: 0 3px 10px rgba(245, 158, 11, 0.3);
}

.status-submitted { 
    background: var(--info-gradient); 
    color: white;
    box-shadow: 0 3px 10px rgba(59, 130, 246, 0.3);
}

.status-graded { 
    background: var(--success-gradient); 
    color: white;
    box-shadow: 0 3px 10px rgba(16, 185, 129, 0.3);
}

.badge.bg-success {
    background: var(--success-gradient) !important;
    box-shadow: 0 3px 10px rgba(16, 185, 129, 0.3);
}

/* Button Styles */
.btn {
    /*padding: 10px 20px;*/
    font-weight: 600;
    /*font-size: 0.85rem;*/
    border-radius: 40px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: none;
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
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
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

.btn-success {
    background: var(--success-gradient);
    box-shadow: 0 3px 10px rgba(16, 185, 129, 0.25);
}

.btn-outline-warning {
    background: transparent;
    border: 2px solid #f59e0b;
    color: #f59e0b;
}

.btn-outline-warning:hover {
    background: var(--warning-gradient);
    border-color: transparent;
    color: white;
}

.btn-outline-info {
    background: transparent;
    border: 2px solid var(--info-gradient);
    color: #3b82f6;
}

.btn-outline-info:hover {
    background: var(--info-gradient);
    border-color: transparent;
    color: white;
}

/* Toggle Button */
.custom-toggle-btn {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: white;
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    cursor: pointer;
    transform: rotate(360deg);
}

.custom-toggle-btn:hover {
    background: var(--primary-gradient);
    color: white;
    transform: rotate(360deg);
}

.custom-toggle-btn i {
    transition: transform 0.3s ease;
}

/* Custom Collapse Content */
.custom-collapse-content {
    padding: 0;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease-out;
}

.custom-collapse-content.show {
    max-height: 2000px;
    transition: max-height 0.5s ease-in;
}

.submission-details {
    padding: 1.5rem;
    background: linear-gradient(135deg, #ffffff, #f8fafc);
}

/* File Item */
.file-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border: 2px solid rgba(67, 97, 238, 0.1);
    border-radius: 12px;
    margin-bottom: 0.75rem;
    background: white;
    transition: all 0.2s ease;
}

.file-item:hover {
    background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(58, 12, 163, 0.05));
    border-color: var(--primary-color);
    transform: translateX(5px);
}

.file-icon {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
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

.file-icon.archive { 
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb); 
    color: #4b5563;
}

/* Grade Section */
.grade-section {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    border-radius: 15px;
    padding: 1.5rem;
    margin-top: 1.5rem;
    border-left: 5px solid transparent;
    border-image: var(--primary-gradient);
    border-image-slice: 1;
}

.marks-display {
    font-size: 3rem;
    font-weight: 800;
    background: var(--success-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-align: center;
    margin-bottom: 0.5rem;
}

.marks-input {
    font-size: 1.5rem;
    font-weight: 700;
    text-align: center;
    border: 2px solid var(--primary-color);
    border-radius: 12px;
    padding: 0.5rem;
    width: 120px;
    transition: all 0.3s ease;
}

.marks-input:focus {
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    outline: none;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    border-radius: 20px;
    border: 2px dashed rgba(67, 97, 238, 0.3);
}

.empty-state i {
    background: white;
    /*-webkit-background-clip: text;*/
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: 20px;
    overflow: hidden;
}

.modal-header {
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 1.2rem 1.5rem;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.modal-footer {
    border-top: 1px solid rgba(67, 97, 238, 0.1);
    padding: 1.2rem 1.5rem;
}

/* Alert Styles */
.alert {
    border-radius: 12px;
    border: none;
    padding: 1rem 1.2rem;
}

.alert-info {
    background: linear-gradient(135deg, #e0f2fe, #bae6fd);
    border-left: 4px solid var(--info-gradient);
    color: #075985;
}

/* Form Controls */
.form-control {
    border-radius: 10px;
    border: 2px solid rgba(67, 97, 238, 0.2);
    padding: 10px 15px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.form-label {
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

/* Card Header */
.card-header {
    background: var(--primary-gradient);
    color: white;
    font-weight: 600;
    padding: 15px 20px;
    border: none;
}

.card-header.bg-white {
    background: white !important;
    color: var(--primary-color);
    border-bottom: 2px solid rgba(67, 97, 238, 0.1);
}

/* Text Styles */
.fw-medium {
    font-weight: 600;
    color: var(--primary-color);
}

.submission-meta {
    color: #64748b;
    font-size: 0.85rem;
    margin-top: 0.5rem;
}

.submission-meta i {
    margin-right: 0.5rem;
    color: var(--primary-color);
}

/* Preview Button */
.preview-btn {
    background: var(--info-gradient);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.4rem 0.8rem;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    box-shadow: 0 3px 10px rgba(59, 130, 246, 0.3);
}

.preview-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
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

/* Responsive Design */
@media (max-width: 768px) {
    .assignment-header {
        padding: 1.5rem;
    }
    
    .assignment-header h3 {
        font-size: 1.2rem;
    }
    
    .stats-number {
        font-size: 2rem;
    }
    
    .custom-card-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .student-info {
        width: 100%;
    }
    
    .marks-input {
        width: 100%;
        font-size: 1.2rem;
    }
}

/* Animation for content */
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

.custom-collapse-content.show .submission-details {
    animation: slideDown 0.3s ease;
}
</style>

<div class="container-fluid py-4">
    <div class="submission-container">
        <!-- Assignment Header -->
        <div class="assignment-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h3 class="mb-2">
                        <i class="fas fa-file-alt me-2"></i>{{ $assignment->title }}
                    </h3>
                    <p class="mb-0 opacity-75">{{ $assignment->description }}</p>
                    <div class="mt-3">
                        <div class="d-flex align-items-center gap-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-alt me-2"></i>
                                <span>Due: {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users me-2"></i>
                                <span>Students: {{ $assignedStudents->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <a href="{{ route('assignments.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-1"></i>Back to Assignments
                    </a>
                </div>
            </div>
        </div>

        <!-- Submission Statistics -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="stats-number text-warning">
                            {{ $assignedStudents->where('status', 'pending')->count() }}
                        </div>
                        <div class="stats-label">Pending</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="stats-number text-info">
                            {{ $assignedStudents->where('status', 'submitted')->count() }}
                        </div>
                        <div class="stats-label">Submitted</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="stats-number text-success">
                            {{ $assignedStudents->where('status', 'graded')->count() }}
                        </div>
                        <div class="stats-label">Graded</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        @php
                            $totalMarks = $assignedStudents->where('status', 'graded')->sum('marks');
                            $gradedCount = $assignedStudents->where('status', 'graded')->count();
                            $averageMarks = $gradedCount > 0 ? round($totalMarks / $gradedCount, 1) : 0;
                        @endphp
                        <div class="stats-number" style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            {{ $averageMarks }}
                        </div>
                        <div class="stats-label">Average Marks</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students List -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Student Submissions
                </h5>
            </div>
            <div class="card-body">
                @if($assignedStudents->count() > 0)
                    <div class="custom-accordion">
                        @foreach($assignedStudents as $index => $assignedStudent)
                        <div class="custom-student-card">
                            <div class="custom-card-header" data-target="collapse-content-{{ $index }}">
                                <div class="student-info">
                                    <div class="student-avatar">
                                        {{ substr($assignedStudent->student->first_name ?? 'S', 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">
                                            {{ $assignedStudent->student->first_name }} {{ $assignedStudent->student->last_name }}
                                        </h6>
                                        <small class="text-muted">
                                            <i class="fas fa-id-card me-1"></i>
                                            Reg: {{ $assignedStudent->student->registration_number }}
                                        </small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="status-badge status-{{ $assignedStudent->status }}">
                                        {{ ucfirst($assignedStudent->status) }}
                                    </span>
                                    @if($assignedStudent->status == 'graded')
                                        <span class="badge bg-success">
                                            <i class="fas fa-star me-1"></i>{{ $assignedStudent->marks }} marks
                                        </span>
                                    @endif
                                    <div class="custom-toggle-btn">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="collapse-content-{{ $index }}" class="custom-collapse-content">
                                <div class="submission-details">
                                    <!-- Submission Files -->
                                    @if($assignedStudent->submissions->count() > 0)
                                        <h6 class="mb-3">
                                            <i class="fas fa-paperclip me-1"></i>Submitted Files
                                        </h6>
                                        @foreach($assignedStudent->submissions as $submission)
                                        <div class="file-item">
                                            <div class="file-icon {{ $submission->file_type == 'pdf' ? 'pdf' : 
                                                                   (in_array($submission->file_type, ['jpg','jpeg','png','gif']) ? 'image' : 
                                                                   (in_array($submission->file_type, ['zip','rar']) ? 'archive' : 'document')) }}">
                                                <i class="fas fa-file"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-medium">{{ $submission->original_name }}</div>
                                                <div class="submission-meta">
                                                    <i class="fas fa-clock"></i>
                                                    {{ \Carbon\Carbon::parse($submission->submitted_at)->format('M d, Y h:i A') }}
                                                    @if($submission->notes)
                                                        <br><i class="fas fa-sticky-note"></i>{{ $submission->notes }}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('assignments.download.submission', $submission->id) }}" 
                                                   class="btn btn-sm btn-primary"
                                                   download>
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button class="btn btn-sm preview-btn"
                                                        onclick="previewFile('{{ route('image', ['path' => str_replace('storage/', '', $submission->file_path)]) }}', '{{ $submission->file_type }}')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="empty-state py-3">
                                            <i class="fas fa-file-upload"></i>
                                            <p class="mb-0 text-muted">No files submitted yet.</p>
                                        </div>
                                    @endif

                                    <!-- Grade Section -->
                                    <div class="grade-section">
                                        <h6 class="mb-3">
                                            <i class="fas fa-chart-line me-1"></i>Grade Submission
                                        </h6>
                                        
                                        @if($assignedStudent->status == 'graded')
                                            <div class="text-center">
                                                <div class="marks-display">
                                                    {{ $assignedStudent->marks }}
                                                </div>
                                                <div class="mb-3">
                                                    <span class="badge bg-success">Graded</span>
                                                    <small class="text-muted ms-2">
                                                        on {{ \Carbon\Carbon::parse($assignedStudent->graded_at)->format('M d, Y') }}
                                                    </small>
                                                </div>
                                                @if($assignedStudent->feedback)
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-comment-dots me-2"></i>
                                                        <strong>Feedback:</strong> {{ $assignedStudent->feedback }}
                                                    </div>
                                                @endif
                                                <button class="btn btn-outline-warning" 
                                                        onclick="showGradeForm({{ $assignedStudent->id }}, {{ $assignedStudent->marks }})">
                                                    <i class="fas fa-edit me-1"></i>Update Grade
                                                </button>
                                            </div>
                                        @else
                                            <form id="gradeForm{{ $assignedStudent->id }}" 
                                                  action="{{ route('assignments.grade.submission', $assignedStudent->id) }}" 
                                                  method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Marks (0-100)</label>
                                                        <input type="number" 
                                                               name="marks" 
                                                               class="form-control marks-input" 
                                                               min="0" 
                                                               max="100" 
                                                               step="0.1"
                                                               required>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <label class="form-label">Feedback (Optional)</label>
                                                        <textarea name="feedback" 
                                                                  class="form-control" 
                                                                  rows="2" 
                                                                  placeholder="Add feedback for the student..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-check me-1"></i>Submit Grade
                                                    </button>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-users"></i>
                        <h5 class="mb-2">No Students Assigned</h5>
                        <p class="text-muted">No students have been assigned to this assignment yet.</p>
                        <a href="{{ route('assignments.assignStudentsForm', $assignment->id) }}" class="btn btn-primary">
                            <i class="fas fa-user-plus me-1"></i>Assign Students
                        </a>
                    </div>
                @endif
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

<!-- Update Grade Modal -->
<div class="modal fade" id="updateGradeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Grade</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="updateGradeForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="mb-3">
                        <label class="form-label">Marks (0-100)</label>
                        <input type="number" 
                               name="marks" 
                               id="updateMarksInput" 
                               class="form-control" 
                               min="0" 
                               max="100" 
                               step="0.1"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Feedback (Optional)</label>
                        <textarea name="feedback" 
                                  id="updateFeedbackInput" 
                                  class="form-control" 
                                  rows="3" 
                                  placeholder="Add feedback..."></textarea>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Update Grade</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// File preview function
function previewFile(fileUrl, fileType) {
    const modal = new bootstrap.Modal(document.getElementById('filePreviewModal'));
    const title = document.getElementById('filePreviewTitle');
    const content = document.getElementById('filePreviewContent');
    const downloadBtn = document.getElementById('downloadFileBtn');
    
    downloadBtn.href = fileUrl;
    const fileName = fileUrl.split('/').pop();
    title.textContent = fileName;
    
    const fileExt = fileType.toLowerCase();
    
    if (['pdf'].includes(fileExt)) {
        content.innerHTML = `
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle me-2"></i>
                PDF files are best viewed by downloading.
            </div>
            <iframe src="${fileUrl}" width="100%" height="600px" style="border: none;"></iframe>
        `;
    } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
        content.innerHTML = `
            <img src="${fileUrl}" class="img-fluid rounded" alt="${fileName}" 
                 style="max-height: 70vh; object-fit: contain;">
        `;
    } else {
        content.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-file fa-4x mb-3" style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                <h5 class="mb-3">${fileName}</h5>
                <p class="text-muted">This file (${fileExt.toUpperCase()}) cannot be previewed in the browser.</p>
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

// Show update grade form
function showGradeForm(studentId, currentMarks) {
    const modal = new bootstrap.Modal(document.getElementById('updateGradeModal'));
    const form = document.getElementById('updateGradeForm');
    const marksInput = document.getElementById('updateMarksInput');
    const feedbackInput = document.getElementById('updateFeedbackInput');
    
    // Set form action
    form.action = `/assignments/${studentId}/grade`;
    
    // Set current values
    marksInput.value = currentMarks || '';
    
    modal.show();
}

// Custom Accordion Functionality
document.addEventListener('DOMContentLoaded', function() {
    const cardHeaders = document.querySelectorAll('.custom-card-header');
    
    cardHeaders.forEach(header => {
        header.addEventListener('click', function(e) {
            // Don't trigger if clicking on buttons or links inside the header
            if (e.target.closest('a') || e.target.closest('button') || e.target.closest('.custom-toggle-btn')) {
                return;
            }
            
            toggleAccordion(this);
        });
        
        // Also trigger when clicking on the toggle button specifically
        const toggleBtn = header.querySelector('.custom-toggle-btn');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleAccordion(header);
            });
        }
    });
    
    function toggleAccordion(header) {
        const targetId = header.getAttribute('data-target');
        const content = document.getElementById(targetId);
        const icon = header.querySelector('.custom-toggle-btn i');
        
        if (content.classList.contains('show')) {
            // Close accordion
            content.classList.remove('show');
            content.style.maxHeight = '0';
            if (icon) {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        } else {
            // Close all other accordions first (optional - remove if you want multiple open)
            const allContents = document.querySelectorAll('.custom-collapse-content');
            const allIcons = document.querySelectorAll('.custom-toggle-btn i');
            
            allContents.forEach(c => {
                c.classList.remove('show');
                c.style.maxHeight = '0';
            });
            
            allIcons.forEach(i => {
                i.classList.remove('fa-chevron-up');
                i.classList.add('fa-chevron-down');
            });
            
            // Open this accordion
            content.classList.add('show');
            content.style.maxHeight = content.scrollHeight + 'px';
            if (icon) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        }
    }
    
    // Handle window resize to adjust max-height
    window.addEventListener('resize', function() {
        const openContents = document.querySelectorAll('.custom-collapse-content.show');
        openContents.forEach(content => {
            content.style.maxHeight = content.scrollHeight + 'px';
        });
    });
});
</script>
@endsection
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<style>
    .submission-header {
        background: linear-gradient(135deg, #4e73df 0%, #2e59d9 100%);
        border-radius: 12px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .student-card {
        border: 1px solid #e3e6f0;
        border-radius: 10px;
        margin-bottom: 1rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .student-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .student-card-header {
        background: #f8f9fc;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e3e6f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .student-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .student-avatar {
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

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending { background: #f6c23e; color: #000; }
    .status-submitted { background: #36b9cc; color: #fff; }
    .status-graded { background: #1cc88a; color: #fff; }

    .submission-details {
        padding: 1.5rem;
    }

    .file-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        margin-bottom: 0.75rem;
        background: white;
        transition: all 0.2s ease;
    }

    .file-item:hover {
        background: #f8f9fc;
        border-color: #4e73df;
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

    .grade-section {
        background: #f8f9fc;
        border-radius: 10px;
        padding: 1.5rem;
        margin-top: 1.5rem;
        border-left: 4px solid #4e73df;
    }

    .marks-display {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1cc88a;
        text-align: center;
        margin-bottom: 0.5rem;
    }

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

    .grade-form {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid #e3e6f0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .marks-input {
        font-size: 2rem;
        font-weight: 700;
        text-align: center;
        border: 2px solid #4e73df;
        border-radius: 8px;
        padding: 0.5rem;
        width: 120px;
    }

    .preview-btn {
        background: #4e73df;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .preview-btn:hover {
        background: #2e59d9;
        transform: translateY(-1px);
    }

    .submission-meta {
        color: #6c757d;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .submission-meta i {
        margin-right: 0.5rem;
    }

    .filter-section {
        background: #f8f9fc;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid #e3e6f0;
    }

    .stats-card {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        border: 1px solid #e3e6f0;
        text-align: center;
        margin-bottom: 1rem;
    }

    .stats-number {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.25rem;
    }

    .stats-label {
        color: #6c757d;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .accordion-button:not(.collapsed) {
        background-color: #f8f9fc;
        color: #2e59d9;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }

    .download-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #4e73df;
        color: white;
        border: none;
        transition: all 0.2s ease;
    }

    .download-btn:hover {
        background: #2e59d9;
        transform: translateY(-1px);
    }

    .no-submissions {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
        font-style: italic;
    }

    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }

    @media (max-width: 768px) {
        .student-card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .grade-form .row {
            flex-direction: column;
        }
        
        .marks-input {
            width: 100%;
            margin-bottom: 1rem;
        }
    }
</style>

<div class="container-fluid">
    <div class="submissions-container">
        <!-- Header -->
        <div class="submission-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h3 class="mb-2">
                        <i class="fas fa-list-check me-2"></i>{{ $assignment->title }}
                    </h3>
                    <p class="mb-0 opacity-75">Student Submissions</p>
                    <div class="mt-3">
                        <div class="d-flex align-items-center gap-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users me-2"></i>
                                <span>Total Students: {{ $assignedStudents->total() }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-alt me-2"></i>
                                <span>Due: {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <a href="{{ route('admin.assignments.show', $assignment->id) }}" class="btn btn-light me-2">
                        <i class="fas fa-arrow-left me-1"></i>Back to Assignment
                    </a>
                    <a href="{{ route('admin.assignments.index') }}" class="btn btn-outline-light">
                        All Assignments
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            @php
                $totalStudents = $assignedStudents->total();
                $pendingCount = $assignedStudents->where('status', 'pending')->count();
                $submittedCount = $assignedStudents->where('status', 'submitted')->count();
                $gradedCount = $assignedStudents->where('status', 'graded')->count();
            @endphp
            
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-primary">{{ $totalStudents }}</div>
                    <div class="stats-label">Total Students</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-warning">{{ $pendingCount }}</div>
                    <div class="stats-label">Pending</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-info">{{ $submittedCount }}</div>
                    <div class="stats-label">Submitted</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-success">{{ $gradedCount }}</div>
                    <div class="stats-label">Graded</div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.assignments.submissions', $assignment->id) }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Filter by Status</label>
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="graded" {{ request('status') == 'graded' ? 'selected' : '' }}>Graded</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Search Student</label>
                        <div class="input-group">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Search by name or registration number..."
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Students List -->
        @if($assignedStudents->count() > 0)
            <div class="accordion" id="studentsAccordion">
                @foreach($assignedStudents as $index => $assignedStudent)
                    @php
                        // Safely get student info
                        $student = $assignedStudent->student;
                        $studentName = 'Unknown Student';
                        $registrationNumber = 'N/A';
                        $studentInitial = 'S';
                        
                        if ($student instanceof \Illuminate\Support\Collection && $student->count() > 0) {
                            $student = $student->first();
                            $studentName = ($student->first_name ?? '') . ' ' . ($student->last_name ?? '');
                            $registrationNumber = $student->registration_number ?? 'N/A';
                            $studentInitial = substr($student->first_name ?? 'S', 0, 1);
                        } elseif ($student) {
                            $studentName = ($student->first_name ?? '') . ' ' . ($student->last_name ?? '');
                            $registrationNumber = $student->registration_number ?? 'N/A';
                            $studentInitial = substr($student->first_name ?? 'S', 0, 1);
                        }
                    @endphp
                    
                    <div class="student-card">
                        <div class="student-card-header" id="heading{{ $index }}">
                            <div class="student-info">
                                <div class="student-avatar">
                                    {{ $studentInitial }}
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $studentName }}</h6>
                                    <small class="text-muted">Reg: {{ $registrationNumber }}</small>
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
                                <button class="btn btn-sm btn-outline-primary" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#collapse{{ $index }}"
                                        aria-expanded="false" 
                                        aria-controls="collapse{{ $index }}">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div id="collapse{{ $index }}" 
                             class="collapse" 
                             aria-labelledby="heading{{ $index }}"
                             data-bs-parent="#studentsAccordion">
                            <div class="submission-details">
                                <!-- Submission Files -->
                                @if($assignedStudent->submissions->count() > 0)
                                    <h6 class="mb-3">
                                        <i class="fas fa-paperclip me-1"></i>Submitted Files
                                    </h6>
                                    @foreach($assignedStudent->submissions as $submission)
                                        @php
                                            // Generate file URL
                                            $filePath = str_replace('storage/', '', $submission->file_path);
                                            $fileUrl = route('image', ['path' => $filePath]);
                                        @endphp
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
                                                <a href="{{ $fileUrl }}" 
                                                   class="download-btn"
                                                   download>
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-info preview-btn"
                                                        onclick="previewFile('{{ $fileUrl }}', '{{ $submission->file_type }}')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="no-submissions">
                                        <i class="fas fa-file-upload"></i>
                                        <p class="mb-0">No files submitted yet.</p>
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
                                                    <strong>Feedback:</strong> {{ $assignedStudent->feedback }}
                                                </div>
                                            @endif
                                            <p class="text-muted">
                                                <small>This submission has been graded. Only teachers can update grades.</small>
                                            </p>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Note:</strong> Only the assigned teacher can grade submissions. 
                                            Please contact {{ $assignment->employee->name ?? 'the teacher' }} to request grading.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="pagination-container">
                {{ $assignedStudents->withQueryString()->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h5 class="mb-2">No Students Found</h5>
                <p class="text-muted">
                    @if(request()->hasAny(['status', 'search']))
                        Try changing your search criteria
                    @else
                        No students have been assigned to this assignment yet.
                    @endif
                </p>
            </div>
        @endif
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
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
                <i class="fas fa-file fa-4x text-primary mb-3"></i>
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

// Initialize accordion behavior
document.addEventListener('DOMContentLoaded', function() {
    const accordionButtons = document.querySelectorAll('[data-bs-toggle="collapse"]');
    accordionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-chevron-down')) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        });
    });
});

// Auto-submit status filter
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.querySelector('select[name="status"]');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            this.form.submit();
        });
    }
});
</script>
@endsection
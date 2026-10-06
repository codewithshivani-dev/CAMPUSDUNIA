@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<style>
    .exam-marking-container {
        padding: 20px;
    }

    .employee-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .employee-info-card .card-title,
    .employee-info-card p {
        color: white;
    }

    .stats-card {
        background: rgba(255, 255, 255, 0.2);
        padding: 15px;
        border-radius: 8px;
        display: inline-block;
    }

    .stats-card h3 {
        margin: 0;
        font-size: 2.5rem;
        font-weight: bold;
    }

    .exam-card {
        height: 100%;
        transition: transform 0.2s;
        border: 1px solid #dee2e6;
    }

    .exam-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .exam-card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 15px;
    }

    .exam-card-body {
        padding: 15px;
    }

    .exam-card-footer {
        background: #f8f9fa;
        border-top: 1px solid #dee2e6;
        padding: 15px;
    }

    .progress-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }

    .progress-low {
        background: #ffc107;
        color: #212529;
    }

    .progress-medium {
        background: #fd7e14;
        color: white;
    }

    .progress-high {
        background: #28a745;
        color: white;
    }

    .progress-complete {
        background: #198754;
        color: white;
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-scheduled { background: #0d6efd; color: white; }
    .status-ongoing { background: #ffc107; color: #212529; }
    .status-completed { background: #198754; color: white; }
    .status-cancelled { background: #dc3545; color: white; }

    .action-buttons .btn {
        padding: 5px 10px;
        font-size: 14px;
    }

    .marks-input {
        width: 100px;
        text-align: center;
    }

    .grade-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        min-width: 40px;
        text-align: center;
    }

    .grade-a { background: #198754; color: white; }
    .grade-b { background: #0dcaf0; color: white; }
    .grade-c { background: #6f42c1; color: white; }
    .grade-f { background: #dc3545; color: white; }

    /* Toast Styles */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .custom-toast {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    @media (max-width: 768px) {
        .exam-card {
            margin-bottom: 20px;
        }
        
        .modal-dialog {
            margin: 10px;
        }
        
        .modal-content {
            padding: 10px;
        }
        
        .toast-container {
            top: 10px;
            right: 10px;
            left: 10px;
        }
    }
</style>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<div class="dashboard-container">
    <main class="main-content">
        <div class="exam-marking-container">
            <!-- Success Message Display -->
            @if(session('exam_mark_success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Marks Saved Successfully!</h5>
                            <p class="mb-0">{{ session('exam_mark_success')['message'] }}</p>
                        </div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
            
            <!-- Error Message Display -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Error!</h5>
                            <p class="mb-0">{{ session('error') }}</p>
                        </div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            <!-- Page Header -->
            <div class="page-header">
                <h1><i class="fas fa-clipboard-check me-2"></i> Exam Marking System</h1>
                <p class="text-muted">Mark student exam numbers for your assigned subjects</p>
            </div>

            <!-- Employee Info Card -->
            <div class="card employee-info-card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="card-title">
                                <i class="fas fa-user-tie me-2"></i> Welcome, {{ $employee->name }}
                            </h5>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Employee ID:</strong> {{ $employee->employee_id }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Designation:</strong> {{ $employee->designation ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Date:</strong> {{ date('F j, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="stats-card">
                                <h3 id="totalExams">0</h3>
                                <p class="text-muted mb-0">Total Exams</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i> Filter Exams</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="filterDateFrom" class="form-label">From Date</label>
                            <input type="date" id="filterDateFrom" class="form-control" 
                                value="{{ \Carbon\Carbon::now()->subMonths(3)->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="filterDateTo" class="form-label">To Date</label>
                            <input type="date" id="filterDateTo" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="filterStatus" class="form-label">Status</label>
                            <select id="filterStatus" class="form-control">
                                <option value="">All Status</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filterSubject" class="form-label">Subject</label>
                            <select id="filterSubject" class="form-control">
                                <option value="">All Subjects</option>
                                @foreach($assignedSubjects as $subject)
                                    <option value="{{ $subject['subject_id'] }}">{{ $subject['subject_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button id="applyFilters" class="btn btn-primary">
                                    <i class="fas fa-filter me-2"></i> Apply Filters
                                </button>
                                <button id="clearFilters" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i> Clear Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading your exams...</p>
            </div>

            <!-- No Exams Message -->
            <div id="noExams" class="text-center py-5" style="display: none;">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h4>No Exams Found</h4>
                <p class="text-muted">No exams found for your assigned subjects with the current filters.</p>
            </div>

            <!-- Exams Grid -->
            <div id="examsGrid" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" style="display: none;">
                <!-- Exams will be loaded here -->
            </div>
        </div>
    </main>
</div>

<!-- Mark Attendance Modal -->
<div class="modal fade" id="markMarksModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markMarksModalLabel">
                    <i class="fas fa-edit me-2"></i> Mark Exam Numbers
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Exam Info -->
                <div class="exam-info-section mb-4 p-3 bg-light rounded">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 id="modalExamName"></h6>
                            <p class="mb-1"><strong>Subject:</strong> <span id="modalSubjectName"></span></p>
                            <p class="mb-1"><strong>Date:</strong> <span id="modalExamDate"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Total Marks:</strong> <span id="modalTotalMarks"></span></p>
                            <p class="mb-1"><strong>Passing Marks:</strong> <span id="modalPassingMarks"></span></p>
                            <p class="mb-1"><strong>Sections:</strong> <span id="modalSections"></span></p>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="progress-section mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Marking Progress</span>
                        <span id="progressText">0/0 (0%)</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>

                <!-- Students Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="studentsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Reg. No.</th>
                                <th>Student Name</th>
                                <th>Section</th>
                                <th>Obtained Marks</th>
                                <th>Grade</th>
                                <th>Remarks</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="studentsTableBody">
                            <!-- Students will be loaded here -->
                        </tbody>
                    </table>
                </div>

                <!-- Bulk Actions -->
                <div class="bulk-actions-section mt-3 p-3 bg-light rounded">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">Bulk Marks</span>
                                <input type="number" class="form-control" id="bulkMarks" 
                                       placeholder="Enter marks" min="0" step="0.01">
                                <button class="btn btn-outline-secondary" type="button" 
                                        onclick="applyBulkMarks()">Apply</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" id="bulkStatus" onchange="applyBulkStatus()">
                                <option value="">Select Status</option>
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                            </select>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-info me-2" onclick="clearAllMarks()">
                                <i class="fas fa-eraser me-1"></i> Clear All
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveMarksBtn" onclick="saveExamMarks()">
                    <i class="fas fa-save me-1"></i> Save Marks
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Summary Modal -->
<div class="modal fade" id="summaryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Exam Marks Summary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="summaryContent">
                    <!-- Summary will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="exportMarks('pdf')">
                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                </button>
                <button type="button" class="btn btn-success" onclick="exportMarks('excel')">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
// Global variables
let currentExamId = null;
let currentExamData = null;
let studentsData = [];
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() { 
    // Load exams
    loadEmployeeExams();
    
    // Setup filter event listeners
    document.getElementById('applyFilters').addEventListener('click', loadEmployeeExams);
    document.getElementById('clearFilters').addEventListener('click', clearFilters);
    
    // Setup global event delegation for dynamically created buttons
    document.addEventListener('click', function(event) {
        // Handle "Mark" button clicks
        if (event.target.closest('.mark-exam-btn')) {
            const button = event.target.closest('.mark-exam-btn');
            const examId = button.getAttribute('data-exam-id');
            if (examId && examId !== 'null' && examId !== 'undefined') {
                openMarkMarksModal(examId);
            }
        }
        
        // Handle "View Summary" button clicks
        if (event.target.closest('.view-summary-btn')) {
            const button = event.target.closest('.view-summary-btn');
            const examId = button.getAttribute('data-exam-id');
            if (examId) {
                viewSummary(examId);
            }
        }
    });
});

function loadEmployeeExams() {
    const loadingSpinner = document.getElementById('loadingSpinner');
    const examsGrid = document.getElementById('examsGrid');
    const noExams = document.getElementById('noExams');
    
    // Show loading
    loadingSpinner.style.display = 'block';
    examsGrid.style.display = 'none';
    noExams.style.display = 'none';
    
    // Get filter values
    const filters = {
        date_from: document.getElementById('filterDateFrom').value,
        date_to: document.getElementById('filterDateTo').value,
        status: document.getElementById('filterStatus').value,
        subject_id: document.getElementById('filterSubject').value
    };
    
    // Build query string
    const queryParams = new URLSearchParams(filters).toString();
    const url = `{{ route('employee.exams.data') }}${queryParams ? '?' + queryParams : ''}`;
    
    fetch(url, {
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        loadingSpinner.style.display = 'none';
        
        if (data.success) {
            // Update total exams count
            document.getElementById('totalExams').textContent = data.total_exams;
            
            if (data.exams && data.exams.length > 0) {
                renderExamsGrid(data.exams);
                examsGrid.style.display = 'flex';
                noExams.style.display = 'none';
            } else {
                examsGrid.style.display = 'none';
                noExams.style.display = 'block';
            }
        } else {
            showToast('error', data.message || 'Error loading exams');
            examsGrid.style.display = 'none';
            noExams.style.display = 'block';
        }
    })
    .catch(error => {
        loadingSpinner.style.display = 'none';
        showToast('error', 'Error loading exams. Please try again.');
        examsGrid.style.display = 'none';
        noExams.style.display = 'block';
    });
}

function renderExamsGrid(exams) {
    const grid = document.getElementById('examsGrid');
    if (!grid) {
        console.error('Exams grid element not found');
        return;
    }
    
    grid.innerHTML = '';
    
    exams.forEach((exam, index) => {
        const progressPercentage = exam.progress_percentage || 0;
        let progressClass = 'progress-low';
        if (progressPercentage >= 50) progressClass = 'progress-medium';
        if (progressPercentage >= 80) progressClass = 'progress-high';
        if (progressPercentage >= 100) progressClass = 'progress-complete';
        
        // Check exam date status
        const today = new Date().toISOString().split('T')[0];
        const examDate = exam.exam_date;
        
        // Calculate days difference
        const examDateObj = new Date(examDate);
        const todayObj = new Date(today);
        const timeDiff = examDateObj.getTime() - todayObj.getTime();
        const daysDiff = Math.floor(timeDiff / (1000 * 3600 * 24));
        
        // Determine status and button state
        let dateStatus = '';
        let dateBadge = '';
        
        // Fix: Allow marking for ALL past exams
        const canMark = daysDiff <= 0; // Today or any past date
        
        if (daysDiff === 0) {
            dateStatus = 'today';
            dateBadge = '<span class="badge bg-success ms-1">Today</span>';
        } else if (daysDiff < 0) {
            // All past exams
            const daysAgo = Math.abs(daysDiff);
            dateStatus = 'past';
            if (daysAgo <= 7) {
                dateBadge = `<span class="badge bg-warning ms-1">${daysAgo} day${daysAgo > 1 ? 's' : ''} ago</span>`;
            } else {
                dateBadge = `<span class="badge bg-secondary ms-1">${daysAgo} days ago</span>`;
            }
        } else {
            // Future exam
            dateStatus = 'upcoming';
            dateBadge = '<span class="badge bg-info ms-1">Upcoming</span>';
        }
        
        const examCard = document.createElement('div');
        examCard.className = 'col';
        examCard.id = `exam-card-${exam.exam_id}`;
        
        examCard.innerHTML = `
            <div class="card exam-card">
                <div class="exam-card-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">${escapeHtml(exam.subject_name)}</h6>
                            <small class="text-muted">${escapeHtml(exam.exam_name)}</small>
                        </div>
                        <div>
                            <span class="status-badge status-${exam.status}">${exam.status_formatted}</span>
                            ${dateBadge}
                        </div>
                    </div>
                </div>
                <div class="exam-card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Date</small>
                            <strong>${escapeHtml(exam.exam_date_formatted)}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Time</small>
                            <strong>${escapeHtml(exam.start_time_formatted)}</strong>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Total Marks</small>
                            <strong>${escapeHtml(exam.total_marks)}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Students</small>
                            <strong id="student-count-${exam.exam_id}">${exam.total_students}</strong>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <small class="text-muted d-block">Sections</small>
                            <strong>${escapeHtml(exam.sections)}</strong>
                        </div>
                    </div>
                    <div class="progress-section">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Marking Progress</small>
                            <small id="progress-text-${exam.exam_id}">${exam.marked_students}/${exam.total_students} (${exam.progress_percentage}%)</small>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div id="progress-bar-${exam.exam_id}" class="progress-bar ${progressClass}" 
                                 role="progressbar" 
                                 style="width: ${exam.progress_percentage}%">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="exam-card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">
                            <i class="fas fa-building me-1"></i> ${escapeHtml(exam.department_name)}
                        </span>
                        <div class="action-buttons d-flex gap-2">
                            <button class="btn btn-sm btn-info view-summary-btn" 
                                    data-exam-id="${escapeHtml(exam.exam_id)}">
                                <i class="fas fa-chart-bar"></i>
                            </button>
                            <button class="btn btn-sm ${canMark ? 'btn-primary' : 'btn-secondary'} mark-exam-btn" 
                                    data-exam-id="${escapeHtml(exam.exam_id)}"
                                    ${!canMark ? 'disabled' : ''}>
                                <i class="fas fa-edit"></i> ${canMark ? 'Mark' : 'Upcoming'}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        grid.appendChild(examCard);
    });
}

function openMarkMarksModal(examId) {
    if (!examId || examId === 'undefined' || examId === 'null') {
        console.error('Invalid examId:', examId);
        showToast('error', 'Invalid exam ID');
        return;
    }
    
    currentExamId = examId;
    
    // Show loading overlay instead of replacing entire content
    const modalBody = document.querySelector('#markMarksModal .modal-body');
    if (!modalBody) {
        console.error('Modal body not found!');
        showToast('error', 'Modal not found. Please refresh the page.');
        return;
    }
    
    // Save original content
    const originalContent = modalBody.innerHTML;
    
    // Add loading overlay
    modalBody.innerHTML = originalContent + `
        <div class="loading-overlay" style="
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 1050;
        ">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading students...</p>
        </div>
    `;
    
    // Fetch students data
    const url = `{{ route('employee.exam.students', ['examId' => ':examId']) }}`.replace(':examId', encodeURIComponent(examId));
    fetch(url, {
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {      
        // Remove loading overlay
        const loadingOverlay = modalBody.querySelector('.loading-overlay');
        if (loadingOverlay) {
            loadingOverlay.remove();
        }
        
        if (data.success) {
            currentExamData = data.exam;
            studentsData = data.students;
            renderStudentsTable();
        } else {
            console.error('API error:', data.message);
            showToast('error', data.message);
            hideModal('#markMarksModal');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        
        // Remove loading overlay
        const loadingOverlay = modalBody.querySelector('.loading-overlay');
        if (loadingOverlay) {
            loadingOverlay.remove();
        }
        
        showToast('error', 'Error loading students: ' + error.message);
        hideModal('#markMarksModal');
    });
    
    // Show the modal
    showModal('#markMarksModal');
}

function showModal(modalSelector) {
    const modalElement = document.querySelector(modalSelector);
    if (!modalElement) {
        console.error('Modal element not found:', modalSelector);
        return;
    }
    
    // Try Bootstrap 5 first
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    } 
    // Try jQuery Bootstrap
    else if (typeof $ !== 'undefined' && $.fn.modal) {
        $(modalElement).modal('show');
    }
    // Fallback to vanilla JS
    else {
        modalElement.style.display = 'block';
        modalElement.classList.add('show');
        modalElement.setAttribute('aria-modal', 'true');
        modalElement.setAttribute('role', 'dialog');
        modalElement.style.backgroundColor = 'rgba(0,0,0,0.5)';
        document.body.classList.add('modal-open');
        
        // Add backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        document.body.appendChild(backdrop);
    }
}

function hideModal(modalSelector) {
    const modalElement = document.querySelector(modalSelector);
    if (!modalElement) return;
    
    // Try Bootstrap 5 first
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        // Get existing instance or create new one
        let modalInstance;
        if (modalInstance) {
            modalInstance.hide();
        } else {
            // Create new instance and hide
            const modal = new bootstrap.Modal(modalElement);
            modal.hide();
        }
    }
    // Try jQuery Bootstrap
    else if (typeof $ !== 'undefined' && $.fn.modal) {
        $(modalElement).modal('hide');
    }
    // Fallback to vanilla JS
    else {
        modalElement.style.display = 'none';
        modalElement.classList.remove('show');
        document.body.classList.remove('modal-open');
        
        // Remove backdrop
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) backdrop.remove();
    }
}

function renderStudentsTable() {  
    if (!currentExamData) {
        console.error('No exam data available');
        showToast('error', 'No exam data available');
        return;
    }
    
    // Update modal header info
    const updateElement = (id, value) => {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = value || 'N/A';
        } else {
            console.error(`Element #${id} not found in DOM`);
        }
    };
    
    updateElement('modalExamName', currentExamData.exam_name);
    updateElement('modalSubjectName', currentExamData.subject_name);
    updateElement('modalExamDate', currentExamData.exam_date_formatted);
    updateElement('modalTotalMarks', currentExamData.total_marks);
    updateElement('modalPassingMarks', currentExamData.passing_marks);
    updateElement('modalSections', currentExamData.sections);
    
    // Render students table
    const tableBody = document.getElementById('studentsTableBody');
    if (!tableBody) {
        console.error('Students table body not found');
        showToast('error', 'Students table not found');
        return;
    }
    
    tableBody.innerHTML = '';
    
    let markedCount = 0;
    
    if (!studentsData || studentsData.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4">
                    <i class="fas fa-users-slash fa-2x text-muted mb-2"></i>
                    <p class="mb-0">No students found for this exam</p>
                    <small class="text-muted">Check if students are enrolled in the sections for this exam</small>
                </td>
            </tr>
        `;
        
        // Update progress for empty state
        updateProgress(0, 0);
        return;
    }  
    
    studentsData.forEach((student, index) => {
        const isMarked = student.is_marked;
        const existingMarks = student.existing_marks;
        
        if (isMarked) markedCount++;
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${escapeHtml(student.registration_number || 'N/A')}</td>
            <td>${escapeHtml(student.student_name || 'N/A')}</td>
            <td>${escapeHtml(student.section_name || 'N/A')}</td>
            <td>
                <input type="number" 
                       class="form-control marks-input" 
                       value="${isMarked && existingMarks ? existingMarks.obtained_marks : ''}"
                       min="0" 
                       max="${currentExamData.total_marks || 100}"
                       step="0.01"
                       onchange="calculateGrade(${index}, this.value)"
                       data-student-id="${student.student_hash_id || ''}">
            </td>
            <td>
                <span id="grade_${index}" class="grade-badge">
                    ${isMarked && existingMarks ? existingMarks.grade : '-'}
                </span>
            </td>
            <td>
                <input type="text" 
                       class="form-control remarks-input" 
                       value="${isMarked && existingMarks ? (existingMarks.remarks || '') : ''}"
                       placeholder="Remarks"
                       data-student-id="${student.student_hash_id || ''}">
            </td>
            <td>
                <span class="status-badge ${isMarked ? 'bg-success' : 'bg-warning'}">
                    ${isMarked ? 'Marked' : 'Pending'}
                </span>
            </td>
        `;
        tableBody.appendChild(row);
        
        // Calculate grade if marks exist
        if (isMarked && existingMarks) {
            calculateGrade(index, existingMarks.obtained_marks);
        }
    });
    
    // Update progress
    updateProgress(markedCount, studentsData.length);
}

function updateProgress(markedCount, totalCount) {
    const percentage = totalCount > 0 ? Math.round((markedCount / totalCount) * 100) : 0;
    
    const progressText = document.getElementById('progressText');
    if (progressText) {
        progressText.textContent = `${markedCount}/${totalCount} (${percentage}%)`;
    }
    
    const progressBar = document.getElementById('progressBar');
    if (progressBar) {
        progressBar.style.width = `${percentage}%`;
        progressBar.setAttribute('aria-valuenow', percentage);
        
        // Update progress bar color
        progressBar.className = 'progress-bar';
        if (percentage >= 100) {
            progressBar.classList.add('bg-success');
        } else if (percentage >= 50) {
            progressBar.classList.add('bg-warning');
        } else {
            progressBar.classList.add('bg-info');
        }
    }
}

function calculateGrade(index, marks) {
    const totalMarks = currentExamData?.total_marks || 100;
    const passingMarks = currentExamData?.passing_marks || 0;
    
    if (!marks || marks === '' || marks === null) {
        const gradeElement = document.getElementById(`grade_${index}`);
        if (gradeElement) {
            gradeElement.textContent = '-';
            gradeElement.className = 'grade-badge';
        }
        return;
    }
    
    const percentage = (parseFloat(marks) / totalMarks) * 100;
    let grade = 'F';
    let gradeClass = 'grade-f';
    
    if (percentage >= 90) {
        grade = 'A+';
        gradeClass = 'grade-a';
    } else if (percentage >= 80) {
        grade = 'A';
        gradeClass = 'grade-a';
    } else if (percentage >= 70) {
        grade = 'B+';
        gradeClass = 'grade-b';
    } else if (percentage >= 60) {
        grade = 'B';
        gradeClass = 'grade-b';
    } else if (percentage >= 50) {
        grade = 'C+';
        gradeClass = 'grade-c';
    } else if (parseFloat(marks) >= passingMarks) {
        grade = 'C';
        gradeClass = 'grade-c';
    }
    
    const gradeElement = document.getElementById(`grade_${index}`);
    if (gradeElement) {
        gradeElement.textContent = grade;
        gradeElement.className = `grade-badge ${gradeClass}`;
    }
}

function applyBulkMarks() {
    const bulkMarksInput = document.getElementById('bulkMarks');
    if (!bulkMarksInput) return;
    
    const bulkMarks = bulkMarksInput.value;
    if (!bulkMarks) {
        showToast('warning', 'Please enter marks to apply');
        return;
    }
    
    const marksInputs = document.querySelectorAll('.marks-input');
    marksInputs.forEach((input, index) => {
        input.value = bulkMarks;
        calculateGrade(index, bulkMarks);
    });
    
    showToast('success', 'Bulk marks applied successfully');
}

function clearAllMarks() {
    if (!confirm('Are you sure you want to clear all marks?')) return;
    
    const marksInputs = document.querySelectorAll('.marks-input');
    const remarksInputs = document.querySelectorAll('.remarks-input');
    
    marksInputs.forEach((input, index) => {
        input.value = '';
        calculateGrade(index, '');
    });
    
    remarksInputs.forEach(input => {
        input.value = '';
    });
    
    showToast('info', 'All marks cleared');
}

function saveExamMarks() {
    const marksInputs = document.querySelectorAll('.marks-input');
    const remarksInputs = document.querySelectorAll('.remarks-input');
    
    const marksData = [];
    let hasErrors = false;
    
    marksInputs.forEach((input, index) => {
        const studentId = input.getAttribute('data-student-id');
        const marks = input.value.trim();
        const remarks = remarksInputs[index]?.value.trim() || '';
        
        if (marks && currentExamData && parseFloat(marks) > currentExamData.total_marks) {
            showToast('error', `Marks for student ${index + 1} exceed total marks`);
            hasErrors = true;
            return;
        }
        
        if (marks) {
            marksData.push({
                student_hash_id: studentId,
                obtained_marks: parseFloat(marks),
                remarks: remarks
            });
        }
    });
    
    if (hasErrors) return;
    
    if (marksData.length === 0) {
        showToast('warning', 'No marks to save');
        return;
    }
    
    // Disable save button and show loading
    const saveBtn = document.getElementById('saveMarksBtn');
    if (!saveBtn) return;
    
    const originalHtml = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
    saveBtn.disabled = true;
    
    // Send data to server
    fetch('{{ route("employee.exam.marks.save") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            exam_id: currentExamId,
            marks: marksData
        })
    })
    .then(response => response.json())
    .then(data => {
        // Always re-enable the button first
        saveBtn.innerHTML = originalHtml;
        saveBtn.disabled = false;
        
        if (data.success) {
            // Show success toast
            showToast('success', data.message);
            
            // Update the specific exam card in the grid
            updateExamCardProgress(currentExamId);
            
            // Close modal after 2 seconds
            setTimeout(() => {
                hideModal('#markMarksModal');
                
                // Refresh exams list (optional)
                // loadEmployeeExams();
            }, 2000);
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        // Always re-enable the button on error
        saveBtn.innerHTML = originalHtml;
        saveBtn.disabled = false;
        showToast('error', 'Error saving marks. Please try again.');
    });
}

// NEW FUNCTION: Update specific exam card progress
function updateExamCardProgress(examId) {
    if (!examId) return;
    
    // Fetch updated exam data
    const url = `{{ route('employee.exam.students', ['examId' => ':examId']) }}`.replace(':examId', encodeURIComponent(examId));
    
    fetch(url, {
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const markedStudents = data.marked_students || 0;
            const totalStudents = data.total_students || 0;
            const progressPercentage = totalStudents > 0 ? Math.round((markedStudents / totalStudents) * 100) : 0;
            
            // Update progress text in exam card
            const progressTextElement = document.getElementById(`progress-text-${examId}`);
            if (progressTextElement) {
                progressTextElement.textContent = `${markedStudents}/${totalStudents} (${progressPercentage}%)`;
            }
            
            // Update student count
            const studentCountElement = document.getElementById(`student-count-${examId}`);
            if (studentCountElement) {
                studentCountElement.textContent = totalStudents;
            }
            
            // Update progress bar width
            const progressBarElement = document.getElementById(`progress-bar-${examId}`);
            if (progressBarElement) {
                progressBarElement.style.width = `${progressPercentage}%`;
                progressBarElement.setAttribute('aria-valuenow', progressPercentage);
                
                // Update progress bar color
                progressBarElement.className = 'progress-bar';
                if (progressPercentage >= 100) {
                    progressBarElement.classList.add('bg-success');
                } else if (progressPercentage >= 80) {
                    progressBarElement.classList.add('progress-high');
                } else if (progressPercentage >= 50) {
                    progressBarElement.classList.add('progress-medium');
                } else {
                    progressBarElement.classList.add('progress-low');
                }
            }
            
            console.log(`Updated exam ${examId} progress: ${markedStudents}/${totalStudents} (${progressPercentage}%)`);
        }
    })
    .catch(error => {
        console.error('Error updating exam card progress:', error);
    });
}

function viewSummary(examId) {
    const url = `{{ route('employee.exam.summary', ['examId' => ':examId']) }}`.replace(':examId', encodeURIComponent(examId));
    
    fetch(url, {
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderSummary(data.summary, data.grade_distribution);
            showModal('#summaryModal');
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        showToast('error', 'Error loading summary');
    });
}

function renderSummary(summary, gradeDistribution) {
    const content = document.getElementById('summaryContent');
    if (!content) return;
    
    let gradeDistributionHtml = '';
    if (gradeDistribution && Object.keys(gradeDistribution).length > 0) {
        gradeDistributionHtml = '<div class="row mt-3"><div class="col-12"><h6>Grade Distribution</h6>';
        for (const [grade, count] of Object.entries(gradeDistribution)) {
            gradeDistributionHtml += `
                <div class="d-flex justify-content-between mb-1">
                    <span>${grade}</span>
                    <span>${count} students</span>
                </div>
            `;
        }
        gradeDistributionHtml += '</div></div>';
    }
    
    content.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Statistics</h5>
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center p-3">
                                    <h2 class="text-primary">${summary.total_students || 0}</h2>
                                    <p class="text-muted mb-0">Total Students</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3">
                                    <h2 class="text-success">${summary.passed || 0}</h2>
                                    <p class="text-muted mb-0">Passed</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3">
                                    <h2 class="text-danger">${summary.failed || 0}</h2>
                                    <p class="text-muted mb-0">Failed</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3">
                                    <h2 class="text-warning">${summary.pass_percentage || 0}%</h2>
                                    <p class="text-muted mb-0">Pass Percentage</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Marks Analysis</h5>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Average Marks</span>
                                <strong>${summary.average_marks || 0}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Highest Marks</span>
                                <strong class="text-success">${summary.highest_marks || 0}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Lowest Marks</span>
                                <strong class="text-danger">${summary.lowest_marks || 0}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ${gradeDistributionHtml}
    `;
}

function exportMarks(format) {
    const url = `{{ route('employee.exam.export', ['examId' => ':examId', 'format' => ':format']) }}`
        .replace(':examId', currentExamId)
        .replace(':format', format);
    
    window.open(url, '_blank');
}

function clearFilters() {
    document.getElementById('filterDateFrom').value = '{{ \Carbon\Carbon::now()->subMonths(3)->format('Y-m-d') }}';
    document.getElementById('filterDateTo').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterSubject').value = '';
    loadEmployeeExams();
}

// New improved toast function
function showToast(type, message, duration = 5000) {
    const toastContainer = document.getElementById('toastContainer');
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast custom-toast align-items-center border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    // Set background color based on type
    let bgColor = '#28a745'; // default success
    let icon = 'fa-check-circle';
    
    switch(type) {
        case 'success':
            bgColor = '#28a745';
            icon = 'fa-check-circle';
            break;
        case 'error':
            bgColor = '#dc3545';
            icon = 'fa-exclamation-circle';
            break;
        case 'warning':
            bgColor = '#ffc107';
            icon = 'fa-exclamation-triangle';
            break;
        case 'info':
            bgColor = '#17a2b8';
            icon = 'fa-info-circle';
            break;
    }
    
    toast.style.background = `linear-gradient(135deg, ${bgColor}, ${darkenColor(bgColor, 20)})`;
    
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="toast-body d-flex align-items-center">
                <i class="fas ${icon} fa-lg me-3"></i>
                <span class="me-3">${message}</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                    data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    // Add to container
    toastContainer.appendChild(toast);
    
    // Initialize Bootstrap toast
    const bsToast = new bootstrap.Toast(toast, {
        animation: true,
        autohide: true,
        delay: duration
    });
    
    // Show toast
    bsToast.show();
    
    // Remove from DOM after hiding
    toast.addEventListener('hidden.bs.toast', function () {
        toast.remove();
    });
    
    return toast;
}

function darkenColor(color, percent) {
    // Simple color darkening function
    const num = parseInt(color.replace("#", ""), 16);
    const amt = Math.round(2.55 * percent);
    const R = (num >> 16) - amt;
    const G = (num >> 8 & 0x00FF) - amt;
    const B = (num & 0x0000FF) - amt;
    
    return "#" + (
        0x1000000 +
        (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
        (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
        (B < 255 ? B < 1 ? 0 : B : 255)
    ).toString(16).slice(1);
}

function escapeHtml(text) {
    if (text === null || text === undefined) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
@endsection
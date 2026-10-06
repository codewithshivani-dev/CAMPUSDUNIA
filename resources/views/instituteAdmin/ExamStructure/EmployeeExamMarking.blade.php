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

    .dashboard-container {
        background-color: #f8fafc;
    }

    .exam-marking-container {
        padding: 20px;
    }

    /* Page Header */
    .page-header {
        background: var(--primary-gradient);
        padding: 20px 25px;
        border-radius: 16px;
        margin-bottom: 25px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
    }

    .page-header h1 {
        color: white;
        margin: 0 0 8px 0;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.5rem;
    }

    .page-header h1 i {
        background: rgba(255,255,255,0.2);
        padding: 12px;
        border-radius: 12px;
        font-size: 1.2rem;
    }

    .page-header p {
        color: rgba(255,255,255,0.9);
        margin: 0 0 0 56px;
        font-size: 0.95rem;
    }

    /* Employee Info Card */
    .employee-info-card {
        background: var(--primary-gradient) !important;
        color: white;
        border: none;
        border-radius: 20px;
        margin-bottom: 25px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
    }

    .employee-info-card .card-body {
        padding: 25px 30px;
    }

    .employee-info-card .card-title {
        color: white;
        font-weight: 700;
        font-size: 1.3rem;
        margin-bottom: 1rem;
    }

    .employee-info-card p {
        color: rgba(255,255,255,0.9);
        margin-bottom: 0.25rem;
    }

    .employee-info-card strong {
        color: white;
    }

    .stats-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 20px 25px;
        border-radius: 16px;
        display: inline-block;
        border: 1px solid rgba(255,255,255,0.2);
        text-align: center;
        min-width: 140px;
    }

    .stats-card h3 {
        margin: 0;
        font-size: 2.8rem;
        font-weight: 700;
        color: white;
        line-height: 1.2;
    }

    .stats-card p {
        margin: 5px 0 0 0;
        color: rgba(255,255,255,0.9);
        font-weight: 500;
        font-size: 0.9rem;
    }

    /* Cards */
    .card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 25px;
        background: white;
    }

    .card-header {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
    }

    .card-header h5 {
        color: #1e293b;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-header h5 i {
        color: var(--primary-color);
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Exam Cards */
    .exam-card {
        height: 100%;
        transition: all 0.3s ease;
        border: none;
        border-radius: 16px;
        background: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .exam-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.15);
    }

    .exam-card-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-bottom: 1px solid #e2e8f0;
        padding: 18px 20px;
        border-radius: 16px 16px 0 0;
    }

    .exam-card-header h6 {
        color: #1e293b;
        font-weight: 700;
        margin-bottom: 4px;
        font-size: 1rem;
    }

    .exam-card-body {
        padding: 20px;
    }

    .exam-card-footer {
        background: #fafbfc;
        border-top: 1px solid #e2e8f0;
        padding: 15px 20px;
        border-radius: 0 0 16px 16px;
    }

    /* Progress Bar */
    .progress {
        height: 8px;
        border-radius: 10px;
        background: #e2e8f0;
    }

    .progress-bar {
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .progress-low {
        background: var(--warning-gradient);
    }

    .progress-medium {
        background: linear-gradient(135deg, #f97316, #ea580c);
    }

    .progress-high {
        background: var(--success-gradient);
    }

    .progress-complete {
        background: linear-gradient(135deg, #059669, #047857);
    }

    .bg-success {
        background: var(--success-gradient) !important;
    }

    /* Status Badges */
    .status-badge {
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    .status-scheduled { 
        background: var(--info-gradient); 
        color: white; 
    }
    .status-ongoing { 
        background: var(--warning-gradient); 
        color: white; 
    }
    .status-completed { 
        background: var(--success-gradient); 
        color: white; 
    }
    .status-cancelled { 
        background: var(--danger-gradient); 
        color: white; 
    }

    .badge.bg-success {
        background: var(--success-gradient) !important;
    }

    .badge.bg-warning {
        background: var(--warning-gradient) !important;
        color: white !important;
    }

    .badge.bg-info {
        background: var(--info-gradient) !important;
        color: white !important;
    }

    .badge.bg-secondary {
        background: linear-gradient(135deg, #64748b, #475569) !important;
        color: white !important;
    }

    /* Form Controls */
    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 15px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    .form-label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        font-size: 13px;
        letter-spacing: 0.3px;
    }

    /* Buttons */
    .btn {
        border-radius: 10px;
        font-weight: 500;
        padding: 0.5rem 1.25rem;
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

    .btn-secondary {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border: 1px solid #cbd5e1;
        color: #475569;
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        transform: translateY(-2px);
    }

    .btn-info {
        background: var(--info-gradient);
        border: none;
        color: white;
    }

    .btn-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        color: white;
    }

    .btn-success {
        background: var(--success-gradient);
        border: none;
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .action-buttons .btn {
        padding: 8px 12px;
        font-size: 13px;
        border-radius: 10px;
    }

    .action-buttons .btn-sm {
        padding: 6px 10px;
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

    .modal-footer {
        background: #fafbfc;
        border-top: 1px solid #e2e8f0;
        padding: 1rem 1.5rem;
    }

    /* List Group in Modal */
    .list-group-item {
        border: none;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 0;
        background: transparent;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    /* Text Colors */
    .text-muted {
        color: #64748b !important;
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    .text-success {
        color: var(--success-color) !important;
    }

    .text-danger {
        color: #ef4444 !important;
    }

    .text-warning {
        color: #f59e0b !important;
    }

    /* Alert Styles */
    .alert {
        border: none;
        border-radius: 16px;
        padding: 15px 20px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: var(--success-gradient);
        color: white;
    }

    .alert-danger {
        background: var(--danger-gradient);
        color: white;
    }

    .alert .btn-close {
        filter: brightness(0) invert(1);
    }

    /* Toast Styles */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .custom-toast {
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        margin-bottom: 10px;
    }

    /* Loading Spinner */
    #loadingSpinner .spinner-border {
        color: var(--primary-color) !important;
        width: 3rem;
        height: 3rem;
    }

    /* No Exams State */
    #noExams {
        background: white;
        border-radius: 20px;
        padding: 50px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    #noExams i {
        color: #cbd5e1 !important;
    }

    #noExams h4 {
        color: #1e293b;
        font-weight: 700;
    }

    /* Progress Section */
    .progress-section {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e2e8f0;
    }

    .progress-section small {
        color: #64748b;
        font-weight: 500;
    }

    /* Page Link */
    .page-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
    }

    .page-link:hover {
        text-decoration: underline;
        color: var(--secondary-color);
    }

    .disabled-link {
        pointer-events: none;
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Strong Text */
    strong {
        color: #1e293b;
        font-weight: 600;
    }

    /* Small text */
    small {
        font-size: 0.8rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .exam-marking-container {
            padding: 15px;
        }

        .page-header {
            padding: 15px 20px;
        }

        .page-header h1 {
            font-size: 1.2rem;
        }

        .page-header h1 i {
            padding: 8px;
            font-size: 1rem;
        }

        .page-header p {
            margin-left: 48px;
            font-size: 0.85rem;
        }

        .employee-info-card .card-body {
            padding: 20px;
        }

        .employee-info-card .card-title {
            font-size: 1.1rem;
        }

        .stats-card {
            padding: 15px 20px;
            min-width: 120px;
        }

        .stats-card h3 {
            font-size: 2.2rem;
        }

        .exam-card {
            margin-bottom: 20px;
        }

        .card-body {
            padding: 1rem;
        }

        .toast-container {
            top: 10px;
            right: 10px;
            left: 10px;
        }

        .d-flex.gap-2 {
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 0.4rem 1rem;
            font-size: 13px;
        }
    }

    @media (max-width: 576px) {
        .employee-info-card .row {
            text-align: center;
        }
        
        .employee-info-card .text-end {
            text-align: center !important;
            margin-top: 15px;
        }
        
        .stats-card {
            width: 100%;
        }
        
        .exam-card-header {
            padding: 15px;
        }
        
        .exam-card-body {
            padding: 15px;
        }
        
        .exam-card-footer {
            padding: 12px 15px;
        }
    }

    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .exam-card {
        animation: fadeIn 0.5s ease;
    }

    /* Icon colors */
    .fa-building {
        color: #64748b;
    }

    .fa-chart-bar {
        color: white;
    }

    /* Row gap for grid */
    .g-4 {
        --bs-gutter-y: 1.5rem;
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
                <h1>
                    <i class="fas fa-clipboard-check"></i>
                    Exam Marking System
                </h1>
                <p>Mark student exam numbers for your assigned subjects</p>
            </div>

            <!-- Employee Info Card -->
            <div class="card employee-info-card">
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
                                <p class="mb-0">Total Exams</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-filter me-2"></i> Filter Exams</h5>
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
                            <select id="filterStatus" class="form-select">
                                <option value="">All Status</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filterSubject" class="form-label">Subject</label>
                            <select id="filterSubject" class="form-select">
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
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Loading your exams...</p>
            </div>

            <!-- No Exams Message -->
            <div id="noExams" class="text-center py-5" style="display: none;">
                <i class="fas fa-calendar-times fa-4x mb-3"></i>
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

<!-- Summary Modal -->
<div class="modal fade" id="summaryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-chart-pie me-2" style="color: var(--primary-color);"></i>Exam Marks Summary
                </h5>
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
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() { 
    console.log('Dashboard page loaded');
    
    // Check for refresh flags immediately
    checkDashboardRefresh();
    
    // Load exams
    loadEmployeeExams();
    
    // Setup filter event listeners
    document.getElementById('applyFilters').addEventListener('click', loadEmployeeExams);
    document.getElementById('clearFilters').addEventListener('click', clearFilters);
    
    // Setup global event delegation for dynamically created buttons
    document.addEventListener('click', function(event) {
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

// Check if we need to refresh dashboard (called from marking page)
function checkDashboardRefresh() {
    console.log('Checking for dashboard refresh...');
    
    const refreshFlag = localStorage.getItem('refreshDashboard');
    const refreshTime = localStorage.getItem('refreshDashboardTime');
    const refreshExamId = localStorage.getItem('refreshExamId');
    
    console.log('Refresh flag:', refreshFlag);
    console.log('Refresh time:', refreshTime);
    console.log('Refresh exam ID:', refreshExamId);
    
    if (refreshFlag === 'true' && refreshTime) {
        const now = new Date().getTime();
        const fiveMinutes = 5 * 60 * 1000; // 5 minutes
        
        console.log('Current time:', now);
        console.log('Time difference:', now - refreshTime);
        
        if (now - refreshTime < fiveMinutes) {
            console.log('Refreshing dashboard due to marks update for exam:', refreshExamId);
            
            // Clear flags FIRST (important!)
            localStorage.removeItem('refreshDashboard');
            localStorage.removeItem('refreshDashboardTime');
            localStorage.removeItem('refreshExamId');
            
            // Show a toast to indicate refresh
            showToast('info', 'Refreshing exam data...', 2000);
            
            // Refresh all exams
            setTimeout(() => {
                loadEmployeeExams();
                
                // If specific exam ID provided, update it immediately after load
                if (refreshExamId) {
                    setTimeout(() => {
                        updateExamCardProgress(refreshExamId);
                    }, 1000);
                }
            }, 500);
            
        } else {
            console.log('Refresh flag expired, clearing...');
            // Clear expired flags
            localStorage.removeItem('refreshDashboard');
            localStorage.removeItem('refreshDashboardTime');
            localStorage.removeItem('refreshExamId');
        }
    } else {
        console.log('No refresh flags found');
    }
}

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
        
        // Allow marking for ALL past exams
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
        
        // Create mark page URL
        const markPageUrl = `{{ route('employee.exam.marking.page', '') }}/${exam.exam_id}`;
        
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
                        <div class="progress">
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
                            <a href="${markPageUrl}" 
                               class="btn btn-sm ${canMark ? 'btn-primary' : 'btn-secondary'} ${!canMark ? 'disabled-link' : ''}"
                               ${!canMark ? 'onclick="return false;"' : ''}>
                                <i class="fas fa-edit"></i> ${canMark ? 'Mark' : 'Upcoming'}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        grid.appendChild(examCard);
    });
}

function updateExamCardProgress(examId) {
    if (!examId) return;
    
    console.log('Updating exam card progress for:', examId);
    
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
            
            console.log('Updated progress:', markedStudents, '/', totalStudents, '=', progressPercentage + '%');
            
            updateExamCardUI(examId, markedStudents, totalStudents, progressPercentage);
        }
    })
    .catch(error => {
        console.error('Error updating exam card progress:', error);
    });
}

function updateExamCardUI(examId, markedStudents, totalStudents, progressPercentage) {
    console.log('Updating UI for exam:', examId, 'Progress:', progressPercentage + '%');
    
    // Update progress text in exam card
    const progressTextElement = document.getElementById(`progress-text-${examId}`);
    if (progressTextElement) {
        progressTextElement.textContent = `${markedStudents}/${totalStudents} (${progressPercentage}%)`;
        console.log('Updated progress text');
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
        
        console.log('Updated progress bar width to:', progressPercentage + '%');
    }
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
        gradeDistributionHtml = '<div class="row mt-3"><div class="col-12"><h6 class="fw-bold mb-3">Grade Distribution</h6>';
        for (const [grade, count] of Object.entries(gradeDistribution)) {
            gradeDistributionHtml += `
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">${grade}</span>
                    <span class="fw-semibold">${count} students</span>
                </div>
            `;
        }
        gradeDistributionHtml += '</div></div>';
    }
    
    content.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <div class="card" style="box-shadow: none; border: 1px solid #e2e8f0;">
                    <div class="card-body">
                        <h5 class="card-title" style="border-bottom: none; padding-bottom: 0;">
                            <i class="fas fa-chart-bar me-2" style="color: var(--primary-color);"></i>Statistics
                        </h5>
                        <div class="row mt-3">
                            <div class="col-6">
                                <div class="text-center p-3" style="background: #f8fafc; border-radius: 12px;">
                                    <h2 class="text-primary mb-1">${summary.total_students || 0}</h2>
                                    <p class="text-muted mb-0 small">Total Students</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3" style="background: #f8fafc; border-radius: 12px;">
                                    <h2 class="text-success mb-1">${summary.passed || 0}</h2>
                                    <p class="text-muted mb-0 small">Passed</p>
                                </div>
                            </div>
                            <div class="col-6 mt-3">
                                <div class="text-center p-3" style="background: #f8fafc; border-radius: 12px;">
                                    <h2 class="text-danger mb-1">${summary.failed || 0}</h2>
                                    <p class="text-muted mb-0 small">Failed</p>
                                </div>
                            </div>
                            <div class="col-6 mt-3">
                                <div class="text-center p-3" style="background: #f8fafc; border-radius: 12px;">
                                    <h2 class="text-warning mb-1">${summary.pass_percentage || 0}%</h2>
                                    <p class="text-muted mb-0 small">Pass Percentage</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card" style="box-shadow: none; border: 1px solid #e2e8f0; height: 100%;">
                    <div class="card-body">
                        <h5 class="card-title" style="border-bottom: none; padding-bottom: 0;">
                            <i class="fas fa-calculator me-2" style="color: var(--primary-color);"></i>Marks Analysis
                        </h5>
                        <div class="list-group list-group-flush mt-2">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Average Marks</span>
                                <strong class="text-primary">${summary.average_marks || 0}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Highest Marks</span>
                                <strong class="text-success">${summary.highest_marks || 0}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Lowest Marks</span>
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

// ALTERNATIVE: Add this function to force refresh when page becomes visible
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        console.log('Dashboard page became visible, checking for updates...');
        
        // Check for refresh flags
        const refreshFlag = localStorage.getItem('refreshDashboard');
        if (refreshFlag === 'true') {
            console.log('Refresh flag found, reloading exams...');
            loadEmployeeExams();
            
            // Clear the flag after use
            setTimeout(() => {
                localStorage.removeItem('refreshDashboard');
                localStorage.removeItem('refreshDashboardTime');
                localStorage.removeItem('refreshExamId');
            }, 1000);
        }
    }
});

// Also check when window gains focus
window.addEventListener('focus', function() {
    console.log('Dashboard gained focus, checking for updates...');
    
    const refreshFlag = localStorage.getItem('refreshDashboard');
    if (refreshFlag === 'true') {
        console.log('Refresh flag found on focus, reloading...');
        loadEmployeeExams();
        
        // Clear the flag
        setTimeout(() => {
            localStorage.removeItem('refreshDashboard');
            localStorage.removeItem('refreshDashboardTime');
            localStorage.removeItem('refreshExamId');
        }, 1000);
    }
});

// Toast function
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

// Modal functions
function showModal(modalSelector) {
    const modalElement = document.querySelector(modalSelector);
    if (!modalElement) return;
    
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
}

function hideModal(modalSelector) {
    const modalElement = document.querySelector(modalSelector);
    if (!modalElement) return;
    
    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
    modal.hide();
}

// Add this to manually trigger a refresh (for testing)
function manualRefresh() {
    console.log('Manual refresh triggered');
    loadEmployeeExams();
    showToast('info', 'Refreshing exam data...', 2000);
}

// Add this function to your dashboard JavaScript
function updateExamProgress(examId) {
    if (!examId) return;
    
    // Make API call to get updated exam data
    fetch(`/employee/exam/${examId}/progress`, {
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateExamCardUI(examId, data.marked_students, data.total_students);
        }
    })
    .catch(error => {
        console.error('Error updating exam progress:', error);
    });
}

// Listen for storage events (from other tabs)
window.addEventListener('storage', function(event) {
    if (event.key === 'examProgressUpdate') {
        try {
            const data = JSON.parse(event.newValue);
            if (data && data.examId) {
                updateExamCardUI(data.examId, data.markedStudents, data.totalStudents);
            }
        } catch (e) {
            console.error('Error parsing progress update:', e);
        }
    }
    
    if (event.key === 'refreshExamProgress') {
        const examId = localStorage.getItem('refreshExamId');
        if (examId) {
            updateExamProgress(examId);
        }
    }
});

// Listen for messages from other windows
window.addEventListener('message', function(event) {
    if (event.data && event.data.type === 'examProgressUpdate') {
        updateExamCardUI(event.data.examId, event.data.markedStudents, event.data.totalStudents);
    }
});

// Check for updates on page load/visibility
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        checkForProgressUpdates();
    }
});

function checkForProgressUpdates() {
    const refreshFlag = localStorage.getItem('refreshExamProgress');
    const refreshTime = localStorage.getItem('refreshTime');
    
    if (refreshFlag === 'true' && refreshTime) {
        const now = new Date().getTime();
        const fiveMinutes = 5 * 60 * 1000;
        
        if (now - refreshTime < fiveMinutes) {
            const examId = localStorage.getItem('refreshExamId');
            if (examId) {
                updateExamProgress(examId);
            }
            
            // Clear the flag
            localStorage.removeItem('refreshExamProgress');
            localStorage.removeItem('refreshExamId');
            localStorage.removeItem('refreshTime');
        }
    }
}
</script>
@endsection
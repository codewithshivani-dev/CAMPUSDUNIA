@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

.exam-schedule-container {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
    border: 2px solid rgba(67, 97, 238, 0.1);
}

/* Student Info Card */
.student-info-card {
    background: var(--primary-gradient);
    color: white;
    border-radius: 18px;
    padding: 22px 25px;
    margin-bottom: 25px;
    box-shadow: 0 10px 30px rgba(67, 97, 238, 0.25);
    position: relative;
    overflow: hidden;
}

.student-info-card::before {
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

.student-info-card h5 {
    color: white;
    margin-bottom: 18px;
    font-weight: 700;
    font-size: 1.1rem;
    position: relative;
    z-index: 1;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    position: relative;
    z-index: 1;
}

.info-item {
    display: flex;
    flex-direction: column;
}

.info-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
}

.info-value {
    font-size: 1rem;
    font-weight: 700;
}

/* Page Header */
.page-header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-header-row h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin: 0;
}

.page-header-row h2 i {
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.exam-count-badge {
    background: var(--primary-gradient);
    color: white;
    padding: 10px 20px;
    border-radius: 30px;
    font-size: 0.9rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

/* Filter Container */
.filter-container {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 18px;
    padding: 22px;
    margin-bottom: 25px;
    border: 2px solid rgba(67, 97, 238, 0.1);
}

.filter-container h5 {
    color: var(--primary-color);
    font-weight: 700;
    margin-bottom: 18px;
}

.filter-row{
    display: flex;
    gap: 12px;
}

.filter-group{
    min-width: 180px;
}

.filter-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 700;
    color: var(--primary-color);
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-group select,
.filter-group input {
    width: 100%;
    padding: 10px 14px;
    border-radius: 12px;
    border: 2px solid rgba(67, 97, 238, 0.2);
    background: white;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.filter-group select:focus,
.filter-group input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    outline: none;
}

.filter-actions {
    place-content: end;
}

/* Button Styles */
.btn {
    border-radius: 30px;
    font-weight: 600;
    padding: 10px 22px;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
    border: none;
}

.btn-primary {
    background: var(--primary-gradient);
    color: white;
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
}

.btn-secondary {
    background: linear-gradient(135deg, #64748b, #475569);
    color: white;
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(100, 116, 139, 0.4);
}

/* Exam Cards */
.exam-cards-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.exam-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    border: 2px solid rgba(67, 97, 238, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.exam-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(67, 97, 238, 0.15);
    border-color: var(--primary-color);
}

.exam-card-header {
    background: var(--primary-gradient);
    color: white;
    padding: 18px 20px;
    position: relative;
    overflow: hidden;
}

.exam-card-header h5 {
    color: white;
    margin: 0;
    font-size: 1.1rem;
    font-weight: 700;
    position: relative;
    z-index: 1;
}

.exam-status-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 5px 14px;
    border-radius: 30px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-scheduled { background: var(--success-gradient); color: white; box-shadow: 0 3px 10px rgba(16, 185, 129, 0.3); }
.status-ongoing { background: var(--warning-gradient); color: white; box-shadow: 0 3px 10px rgba(245, 158, 11, 0.3); }
.status-completed { background: linear-gradient(135deg, #64748b, #475569); color: white; box-shadow: 0 3px 10px rgba(100, 116, 139, 0.3); }
.status-draft { background: var(--info-gradient); color: white; box-shadow: 0 3px 10px rgba(59, 130, 246, 0.3); }

.exam-card-body {
    padding: 20px;
}

.exam-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 15px;
}

.exam-info-item {
    display: flex;
    flex-direction: column;
}

.exam-info-label {
    font-size: 0.7rem;
    color: #94a3b8;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.exam-info-value {
    font-size: 0.9rem;
    font-weight: 700;
    color: #1e293b;
}

.exam-card-footer {
    padding: 15px 20px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-top: 1px solid rgba(67, 97, 238, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.view-details-btn {
    background: var(--success-gradient);
    color: white;
    border: none;
    padding: 8px 18px;
    border-radius: 25px;
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 3px 10px rgba(16, 185, 129, 0.3);
}

.view-details-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4);
}

/* No Exams */
.no-exams {
    text-align: center;
    padding: 60px 20px;
    color: #64748b;
}

.no-exams i {
    font-size: 4rem;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 20px;
    opacity: 0.6;
}

.no-exams h4 {
    color: var(--primary-color);
    margin-bottom: 10px;
    font-weight: 700;
}

/* Loading Spinner */
.loading-spinner {
    text-align: center;
    padding: 60px 20px;
}

.loading-spinner i {
    font-size: 2.5rem;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
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
    border: none;
    padding: 1.2rem 1.5rem;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.modal-title {
    font-weight: 700;
}

.modal-body {
    padding: 1.5rem;
}

/* Detail Items */
.detail-item {
    margin-bottom: 12px;
}

.detail-label {
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 5px;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    color: #1e293b;
    padding: 10px 14px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 10px;
    border: 1px solid rgba(67, 97, 238, 0.1);
    font-size: 0.9rem;
}

.full-width {
    grid-column: 1 / -1;
}

/* Text Styles */
.text-muted {
    color: #94a3b8 !important;
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
    .exam-cards-container {
        grid-template-columns: 1fr;
    }
    
    .filter-group {
        min-width: 100%;
    }
    
    .exam-info-grid {
        grid-template-columns: 1fr;
    }
    
    .exam-details-grid {
        grid-template-columns: 1fr;
    }
    
    .page-header-row {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    .btn
    {
        font-size:0.55rem;
    }
}
</style>
<div class="container-fluid">
    <div class="dashboard-container">
        <main class="main-content">
            <div class="exam-schedule-container">
                <!-- Page Header -->
                <div class="page-header-row">
                    <h2><i class="fas fa-calendar-alt me-2"></i>Exam Schedule</h2>
                    <span class="exam-count-badge" id="examCountBadge">
                        <i class="fas fa-calendar-check"></i> 
                        <span id="examCount">0</span> Exams
                    </span>
                </div>
    
                <!-- Student Information Card -->
                <div class="student-info-card" id="studentInfoCard">
                    <!--<h5><i class="fas fa-user-graduate me-2"></i> Student Information</h5>-->
                    <div class="info-grid" id="studentInfoGrid">
                        <div class="info-item">
                            <span class="info-label">Name</span>
                            <span class="info-value">{{ $student->first_name }} {{ $student->last_name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Registration Number</span>
                            <span class="info-value">{{ $student->registration_number ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Course</span>
                            <span class="info-value" id="studentCourse">Loading...</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Department</span>
                            <span class="info-value" id="studentDepartment">Loading...</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Section</span>
                            <span class="info-value" id="studentSections">Loading...</span>
                        </div>
                    </div>
                </div>
    
                <!-- Filter Section -->
                <div class="filter-container">
                    <h5><i class="fas fa-filter me-2"></i> Filter Exams</h5>
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="filterDateFrom">From Date</label>
                            <input type="date" id="filterDateFrom" class="form-control">
                        </div>
                        <div class="filter-group">
                            <label for="filterDateTo">To Date</label>
                            <input type="date" id="filterDateTo" class="form-control">
                        </div>
                        <div class="filter-group">
                            <label for="filterStatus">Status</label>
                            <select id="filterStatus" class="form-control">
                                <option value="">All Status</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        
                        <div class="filter-actions">
                            <button id="applyFilters" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i> Apply Filters
                            </button>
                            <button id="clearFilters" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i> Clear Filters
                            </button>
                        </div>
                    </div>
                </div>
    
                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Loading your exam schedule...</p>
                </div>
    
                <!-- No Exams Message -->
                <div id="noExams" class="no-exams" style="display: none;">
                    <i class="fas fa-calendar-times"></i>
                    <h4>No Upcoming Exams</h4>
                    <p>You don't have any exams scheduled at the moment.</p>
                    <p class="text-muted">Check back later or contact your department if you believe this is an error.</p>
                </div>
    
                <!-- Exams Cards Container -->
                <div id="examsContainer" class="exam-cards-container" style="display: none;">
                    <!-- Exam cards will be loaded here -->
                </div>
            </div>
        </main>
    </div>
</div>

<!-- View Exam Details Modal -->
<div class="modal fade" id="viewExamModal" tabindex="-1" aria-labelledby="viewExamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewExamModalLabel">Exam Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="exam-details-grid" id="examDetailsContent">
                    <!-- Details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Global variables
    let currentExamId = null;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    // Helper function to escape HTML to prevent XSS
    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Initialize
    loadExamSchedule();
    
    // Test function for debugging
    window.testViewExam = function() {
        const sampleExamId = prompt('Enter a sample exam ID to test:');
        if (sampleExamId) {
            viewExamDetails(sampleExamId);
        }
    };
    
    // Add test button for debugging
    const testButton = document.createElement('button');
    testButton.onclick = window.testViewExam;
    
    // Event Listeners
    document.getElementById('applyFilters').addEventListener('click', function() {
        loadExamSchedule();
    });
    
    document.getElementById('clearFilters').addEventListener('click', function() {
        clearFilters();
        loadExamSchedule();
    });
    
    // Filter inputs - add change listeners
    ['filterDateFrom', 'filterDateTo', 'filterStatus'].forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('chachangenge', function() {
                loadExamSchedule();
            });
        }
    });
    
    // Main function to load exam schedule
    function loadExamSchedule() {
        const loadingSpinner = document.getElementById('loadingSpinner');
        const examsContainer = document.getElementById('examsContainer');
        const noExams = document.getElementById('noExams');
        
        // Show loading state
        loadingSpinner.style.display = 'block';
        examsContainer.style.display = 'none';
        noExams.style.display = 'none';
        
        // Prepare filters
        const filters = {
            date_from: document.getElementById('filterDateFrom').value,
            date_to: document.getElementById('filterDateTo').value,
            status: document.getElementById('filterStatus').value
        };
        
        // Remove empty filters
        Object.keys(filters).forEach(key => {
            if (!filters[key]) {
                delete filters[key];
            }
        });
        
        // Build query string
        const queryParams = new URLSearchParams(filters).toString();
        const url = `{{ route("student.exam-schedule.data") }}${queryParams ? '?' + queryParams : ''}`;
        
        // Fetch exam schedule data
        fetch(url, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            method: 'GET',
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            loadingSpinner.style.display = 'none';
            
            if (data.success) {
                // Update student information
                updateStudentInfo(data.student);
                // Update exam count badge
                document.getElementById('examCount').textContent = data.total_exams;
                
                if (data.exams && data.exams.length > 0) {
                    renderExamCards(data.exams);
                    examsContainer.style.display = 'grid';
                    noExams.style.display = 'none';
                } else {
                    examsContainer.style.display = 'none';
                    noExams.style.display = 'block';
                }
            } else {
                alert('Error loading exam schedule: ' + data.message);
                examsContainer.style.display = 'none';
                noExams.style.display = 'block';
            }
        })
        .catch(error => {
            loadingSpinner.style.display = 'none';
            alert('Error loading exam schedule. Please try again.');
            examsContainer.style.display = 'none';
            noExams.style.display = 'block';
        });
    }
    
    // Update student information in the card
    function updateStudentInfo(student) {
        const courseElement = document.getElementById('studentCourse');
        const deptElement = document.getElementById('studentDepartment');
        const sectionsElement = document.getElementById('studentSections');
        
        if (courseElement) {
            courseElement.textContent = student.course || 'Not specified';
        }
        if (deptElement) {
            deptElement.textContent = student.department;
        }
        if (sectionsElement) {
            sectionsElement.textContent = Array.isArray(student.sections) 
                ? student.sections.join(', ') 
                : (student.sections || 'Not specified');
        }
    }
    
    // Render exam cards in the grid
    function renderExamCards(exams) {
        const container = document.getElementById('examsContainer');
        container.innerHTML = '';
        
        exams.forEach(exam => {
            const examCard = document.createElement('div');
            examCard.className = 'exam-card';
            
            // Format status badge
            const statusClass = `status-${exam.status}`;
            const statusText = exam.status_formatted || exam.status.charAt(0).toUpperCase() + exam.status.slice(1);
            
            // Format time display
            const timeDisplay = exam.start_time_formatted && exam.end_time_formatted 
                ? `${escapeHtml(exam.start_time_formatted)} - ${escapeHtml(exam.end_time_formatted)}`
                : 'Time not specified';
            
            // Format classroom
            const classroomDisplay = exam.classroom_id 
                ? escapeHtml(exam.classroom_id)
                : 'To be announced';
            
            examCard.innerHTML = `
                <div class="exam-card-header">
                    <h5>${escapeHtml(exam.subject_name || 'No Subject')}</h5>
                    
                </div>
                <div class="exam-card-body">
                    <div class="exam-info-grid">
                        <div class="exam-info-item">
                            <span class="exam-info-label">Exam Name</span>
                            <span class="exam-info-value">${escapeHtml(exam.exam_name || 'N/A')}</span>
                        </div>
                        <div class="exam-info-item">
                            <span class="exam-info-label">Exam ID</span>
                            <span class="exam-info-value">${escapeHtml(exam.exam_id || 'N/A')}</span>
                        </div>
                        <div class="exam-info-item">
                            <span class="exam-info-label">Date</span>
                            <span class="exam-info-value">${escapeHtml(exam.exam_date_formatted || 'N/A')}</span>
                        </div>
                        <div class="exam-info-item">
                            <span class="exam-info-label">Time</span>
                            <span class="exam-info-value">${timeDisplay}</span>
                        </div>
                        <div class="exam-info-item">
                            <span class="exam-info-label">Duration</span>
                            <span class="exam-info-value">${escapeHtml(exam.duration_formatted || 'N/A')}</span>
                        </div>
                        <div class="exam-info-item">
                            <span class="exam-info-label">Classroom</span>
                            <span class="exam-info-value">${classroomDisplay}</span>
                        </div>
                        <div class="exam-info-item">
                            <span class="exam-info-label">Total Marks</span>
                            <span class="exam-info-value">${escapeHtml(exam.total_marks || 'N/A')}</span>
                        </div>
                        <div class="exam-info-item">
                            <span class="exam-info-label">Passing Marks</span>
                            <span class="exam-info-value">${escapeHtml(exam.passing_marks || 'N/A')}</span>
                        </div>
                    </div>
                    <div class="exam-info-item">
                        <span class="exam-info-label">Sections</span>
                        <span class="exam-info-value">${escapeHtml(exam.student_sections || 'All Sections')}</span>
                    </div>
                </div>
                <div class="exam-card-footer">
                    <span class="text-muted">
                        <i class="fas fa-building me-1"></i> ${escapeHtml(exam.department_name)}
                    </span>
                    <button class="view-details-btn" data-exam-id="${escapeHtml(exam.exam_id)}">
                        <i class="fas fa-info-circle me-1"></i> View Details
                    </button>
                </div>
            `;
            
            container.appendChild(examCard);
        });
        
        // Add event listeners for view details buttons
        container.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', function() {
                const examId = this.getAttribute('data-exam-id');
                if (examId && examId !== 'N/A') {
                    viewExamDetails(examId);
                } else {
                    alert('Cannot load exam details: Exam ID is missing or invalid');
                }
            });
        });
    }
    
    // Clear all filters
    function clearFilters() {
        document.getElementById('filterDateFrom').value = '';
        document.getElementById('filterDateTo').value = '';
        document.getElementById('filterStatus').value = '';
    }
    
    // View exam details in modal
    window.viewExamDetails = function(examId) {        
        // Validate exam ID
        if (!examId || examId === 'N/A' || examId.trim() === '') {
            alert('Cannot load exam details: Exam ID is missing or invalid');
            return;
        }
        
        currentExamId = examId;
        
        // Construct URL using Laravel route
        const url = `{{ route("student.exam-schedule.details", ":id") }}`.replace(':id', encodeURIComponent(examId));
        
        fetch(url, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            method: 'GET',
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                renderExamDetails(data.exam);
                showExamModal();
            } else {
                alert('Error loading exam details: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            alert('Error loading exam details: ' + error.message);
        });
    };
    
    // Show exam details modal
    function showExamModal() {
        const modalElement = document.getElementById('viewExamModal');
        if (!modalElement) {
            alert('Cannot show exam details: Modal not found');
            return;
        }
        
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else {
            // Fallback for when Bootstrap is not available
            modalElement.style.display = 'block';
            modalElement.classList.add('show');
            modalElement.style.backgroundColor = 'rgba(0,0,0,0.5)';
            document.body.classList.add('modal-open');
            
            // Add close button functionality
            const closeButtons = modalElement.querySelectorAll('[data-bs-dismiss="modal"], .btn-close, .btn-secondary');
            closeButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    modalElement.style.display = 'none';
                    modalElement.classList.remove('show');
                    document.body.classList.remove('modal-open');
                });
            });
        }
    }
    
    // Render exam details in modal
    function renderExamDetails(exam) {
        const container = document.getElementById('examDetailsContent');
        if (!container) {
            return;
        }
        
        // Calculate passing percentage if possible
        let passingPercentage = 'N/A';
        if (exam.total_marks && exam.passing_marks && exam.total_marks > 0) {
            const percentage = (exam.passing_marks / exam.total_marks) * 100;
            passingPercentage = percentage.toFixed(2) + '%';
        }
        
        // Format status badge
        const statusClass = `status-${exam.status}`;
        const statusText = exam.status_formatted || exam.status.charAt(0).toUpperCase() + exam.status.slice(1);
        
        container.innerHTML = `
            <div class="detail-item">
                <div class="detail-label">Exam ID</div>
                <div class="detail-value">${escapeHtml(exam.exam_id || 'N/A')}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Exam Name</div>
                <div class="detail-value">${escapeHtml(exam.exam_name || 'N/A')}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Subject</div>
                <div class="detail-value">${escapeHtml(exam.subject_name || 'N/A')}</div>
            </div>
           
            <div class="detail-item">
                <div class="detail-label">Department</div>
                <div class="detail-value">${escapeHtml(exam.department_name)}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Date</div>
                <div class="detail-value">${escapeHtml(exam.exam_date_formatted || 'N/A')}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Time</div>
                <div class="detail-value">${escapeHtml(exam.start_time_formatted || 'N/A')} to ${escapeHtml(exam.end_time_formatted || 'N/A')}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Duration</div>
                <div class="detail-value">${escapeHtml(exam.duration_formatted || 'N/A')}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Classroom</div>
                <div class="detail-value">${escapeHtml(exam.classroom_details || exam.classroom_id || 'To be announced')}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Total Marks</div>
                <div class="detail-value">${escapeHtml(exam.total_marks || 'N/A')}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Passing Marks</div>
                <div class="detail-value">${escapeHtml(exam.passing_marks || 'N/A')} (${passingPercentage})</div>
            </div>
      
            <div class="detail-item">
                <div class="detail-label">Sections</div>
                <div class="detail-value">${escapeHtml(exam.student_sections || exam.sections || 'All Sections')}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Department Category</div>
                <div class="detail-value">${escapeHtml(exam.department_category || 'N/A')}</div>
            </div>
            <div class="detail-item full-width">
                <div class="detail-label">Instructions</div>
                <div class="detail-value">${escapeHtml(exam.instructions || 'No special instructions provided')}</div>
            </div>
            <div class="detail-item full-width">
                <div class="detail-label">Remarks</div>
                <div class="detail-value">${escapeHtml(exam.remarks || 'No remarks')}</div>
            </div>
        `;
    }
    
    // Set default date range (next 30 days)
    function setDefaultDateRange() {
        const today = new Date();
        const nextMonth = new Date();
        nextMonth.setDate(today.getDate() + 30);
        
        // Format dates as YYYY-MM-DD
        const formatDate = (date) => date.toISOString().split('T')[0];
        
        const todayFormatted = formatDate(today);
        const nextMonthFormatted = formatDate(nextMonth);
        
        const fromInput = document.getElementById('filterDateFrom');
        const toInput = document.getElementById('filterDateTo');
        
        if (fromInput && toInput) {
            fromInput.value = todayFormatted;
            toInput.value = nextMonthFormatted;
            
            // Set min attribute to prevent selecting past dates
            fromInput.min = todayFormatted;
            toInput.min = todayFormatted;
        }
    }
    
    // Initialize default date range
    setDefaultDateRange();
    
    // Error handling for missing elements
    const requiredElements = [
        'loadingSpinner', 'examsContainer', 'noExams', 'applyFilters', 
        'clearFilters', 'filterDateFrom', 'filterDateTo', 'filterStatus'
    ];
    
    requiredElements.forEach(id => {
        if (!document.getElementById(id)) {
        }
    });
});
</script>
@endsection
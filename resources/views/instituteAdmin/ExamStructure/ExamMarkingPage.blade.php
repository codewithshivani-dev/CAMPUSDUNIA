@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    /* Add to your existing styles */
.grade-summary-card {
    border-left: 4px solid #6f42c1;
    margin-bottom: 20px;
}

.grade-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.grade-table th {
    background-color: #f8f9fa;
    text-align: center;
    padding: 8px;
    font-weight: 600;
    border: 1px solid #dee2e6;
}

.grade-table td {
    text-align: center;
    padding: 8px;
    border: 1px solid #dee2e6;
}

.grade-row:hover {
    background-color: #f8f9fa;
}

.grade-badge-small {
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 600;
    font-size: 12px;
    display: inline-block;
    min-width: 40px;
}

.system-name {
    color: #6f42c1;
    font-weight: 600;
}
.badge bg-success{
    color:rgb(237, 234, 241); 
}
.percentage-range {
    color: #6c757d;
    font-size: 13px;
}
    .exam-marking-page {
        padding: 20px;
    }
    
    .header-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        margin-bottom: 20px;
    }
    
    .header-card h2,
    .header-card p {
        color: white;
    }
    
    .stats-card {
        background: rgba(255, 255, 255, 0.2);
        padding: 15px;
        border-radius: 8px;
        text-align: center;
    }
    
    .stats-card h3 {
        margin: 0;
        font-size: 2rem;
        font-weight: bold;
    }
    .bg-success {
    background-color: #03dd8f !important;
    color: white;
}
.bg-danger {
    background-color: #e74a3b !important;
    color: white;
}
    .filters-card {
        margin-bottom: 20px;
    }
    
    .student-row {
        transition: all 0.3s;
    }
    
    .student-row:hover {
        background-color: #f8f9fa;
    }
    
    .marks-input {
        width: 120px;
        text-align: center;
    }
    
    .grade-badge {
        padding: 5px 10px;
        border-radius: 4px;
        font-weight: 600;
        min-width: 50px;
        text-align: center;
        display: inline-block;
    }
    
    .grade-A { background: #198754; color: white; }
    .grade-B { background: #0dcaf0; color: white; }
    .grade-C { background: #6f42c1; color: white; }
    .grade-D { background: #fd7e14; color: white; }
    .grade-F { background: #dc3545; color: white; }
    
    .status-badge {
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .status-pending { background: #6c757d; color: white; }
    .status-passed { background: #198754; color: white; }
    .status-failed { background: #dc3545; color: white; }
    
    .save-btn {
        padding: 6px 12px;
        font-size: 13px;
    }
    
    .bulk-actions {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }
    
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
        .action-buttons {
            flex-direction: column;
            gap: 5px;
        }
        
        .marks-input {
            width: 100px;
        }
    }
</style>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<div class="dashboard-container">
    <main class="main-content">
        <div class="exam-marking-page">
            <!-- Success Message Display -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Success!</h5>
                            <p class="mb-0">{{ session('success') }}</p>
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

            <!-- Header Section -->
            <div class="card header-card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h2 class="mb-2">
                                <i class="fas fa-edit me-2"></i> Exam Marking
                            </h2>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Exam:</strong> {{ $exam->exam_name }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Subject:</strong> {{ $exam->subject->subject_name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Date:</strong> {{ date('F j, Y', strtotime($exam->exam_date)) }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Total Marks:</strong> {{ $exam->total_marks }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Passing Marks:</strong> {{ $exam->passing_marks }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Sections:</strong> {{ $sectionNames }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-6">
                                    <div class="stats-card">
                                        <h3 id="totalStudents">0</h3>
                                        <p class="mb-0">Total Students</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stats-card">
                                        <h3 id="markedStudents">0</h3>
                                        <p class="mb-0">Marked</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('employee.exam.marking.view') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i> Back to Exams
                        </a>
                    </div>
                </div>
            </div>
             <!-- Grade Summary Section -->
            @if($gradeSystemData && isset($gradeSystemData['grade_ranges']) && count($gradeSystemData['grade_ranges']) > 0)
            <div class="card grade-summary-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="card-title">
                                <i class="fas fa-graduation-cap me-2"></i> 
                                Grade System: 
                                <span class="system-name">{{ $gradeSystemData['name'] ?? 'Standard Grading' }}</span>
                            </h5>
                            <p class="text-muted mb-0">Grades are calculated based on the following ranges:</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="badge bg-info">Total Marks: {{ $exam->total_marks }}</div>
                            <div class="badge bg-warning mt-1">Passing: {{ $exam->passing_marks }}</div>
                        </div>
                    </div>
                    
                    <div class="table-responsive mt-3">
                        <table class="grade-table">
                            <thead>
                                <tr>
                                    <th>Grade</th>
                                    <th>Description</th>
                                    <th>Percentage Range</th>
                                    <th>Grade Point</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $gradeRanges = $gradeSystemData['grade_ranges'];
                                    // Sort by min_percentage descending for display
                                    usort($gradeRanges, function($a, $b) {
                                        return $b['min_percentage'] <=> $a['min_percentage'];
                                    });
                                @endphp
                                
                                @foreach($gradeRanges as $range)
                                    @php
                                        $gradeClass = 'grade-badge-small ';
                                        switch($range['grade']) {
                                            case 'A+':
                                            case 'A':
                                            case 'A-':
                                                $gradeClass .= 'grade-A';
                                                break;
                                            case 'B+':
                                            case 'B':
                                            case 'B-':
                                                $gradeClass .= 'grade-B';
                                                break;
                                            case 'C+':
                                            case 'C':
                                            case 'C-':
                                                $gradeClass .= 'grade-C';
                                                break;
                                            case 'D+':
                                            case 'D':
                                            case 'D-':
                                                $gradeClass .= 'grade-D';
                                                break;
                                            default:
                                                $gradeClass .= 'grade-F';
                                        }
                                        
                                        $isPassing = $range['grade'] !== 'M' && 
                                                $range['grade'] !== 'F' && 
                                                $range['grade_point'] > 0;
                                    @endphp
                                    
                                    <tr class="grade-row">
                                        <td>
                                            <span class="{{ $gradeClass }}">
                                                {{ $range['grade'] }}
                                            </span>
                                        </td>
                                        <td>{{ $range['description'] ?? 'N/A' }}</td>
                                        <td class="percentage-range">
                                            {{ number_format($range['min_percentage'], 1) }}% 
                                            - 
                                            {{ number_format($range['max_percentage'], 1) }}%
                                        </td>
                                        <td>
                                            @if($range['grade_point'] > 0)
                                                <span class="badge bg-success">{{ $range['grade_point'] }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ $range['grade_point'] }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($isPassing)
                                                <span class="badge bg-success">Pass</span>
                                            @else
                                                <span class="badge bg-danger">Fail</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Passing Percentage: 
                                <strong>{{ number_format(($exam->passing_marks / $exam->total_marks) * 100, 1) }}%</strong>
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Grades are calculated in real-time
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-info mb-4">
                <i class="fas fa-info-circle me-2"></i>
                Using default grading system. Contact admin to configure custom grade ranges.
            </div>
            @endif

            <!-- Bulk Actions -->
            <div class="bulk-actions">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text">Bulk Marks</span>
                            <input type="number" class="form-control" id="bulkMarksInput" 
                                   placeholder="Enter marks" min="0" step="0.01"
                                   max="{{ $exam->total_marks }}">
                            <button class="btn btn-outline-primary" type="button" 
                                    onclick="applyBulkMarks()">
                                <i class="fas fa-check me-1"></i> Apply
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" class="form-control" id="bulkRemarksInput" 
                                   placeholder="Bulk remarks (optional)">
                            <button class="btn btn-outline-secondary" type="button" 
                                    onclick="applyBulkRemarks()">
                                <i class="fas fa-comment me-1"></i> Apply Remarks
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAllStudents">
                            <label class="form-check-label" for="selectAllStudents">
                                Select All
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 text-end">
                        <button class="btn btn-success" onclick="saveAllChanges()">
                            <i class="fas fa-save me-1"></i> Save All Changes
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="card filters-card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="searchInput" class="form-label">Search Students</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" id="searchInput" class="form-control" 
                                       placeholder="Search by name, registration">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="statusFilter" class="form-label">Status</label>
                            <select id="statusFilter" class="form-control">
                                <option value="all">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="passed">Passed</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="sectionFilter" class="form-label">Section</label>
                            <select id="sectionFilter" class="form-control">
                                <option value="all">All Sections</option>
                                @foreach(explode(', ', $sectionNames) as $section)
                                    <option value="{{ $section }}">{{ $section }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100" onclick="loadStudents()">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading students...</p>
            </div>

            <!-- No Students Message -->
            <div id="noStudents" class="text-center py-5" style="display: none;">
                <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                <h4>No Students Found</h4>
                <p class="text-muted">No students found for this exam with the current filters.</p>
            </div>

            <!-- Students Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="studentsTable">
                    <thead class="table-light">
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="checkAll">
                            </th>
                            <th width="50">#</th>
                            <th>Registration No.</th>
                            <th>Student Name</th>
                            <th>Section</th>
                            <th>Obtained Marks</th>
                            <th>Grade</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTableBody">
                        <!-- Students will be loaded here -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination (if needed) -->
            <div class="d-flex justify-content-between align-items-center mt-4" id="paginationContainer" style="display: none;">
                <div>
                    Showing <span id="startIndex">0</span> to <span id="endIndex">0</span> of 
                    <span id="totalRecords">0</span> entries
                </div>
                <div>
                    <button class="btn btn-outline-primary btn-sm" onclick="previousPage()">
                        <i class="fas fa-chevron-left"></i> Previous
                    </button>
                    <span class="mx-2">Page <span id="currentPage">1</span></span>
                    <button class="btn btn-outline-primary btn-sm" onclick="nextPage()">
                        Next <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </main>
</div>
<!-- Add this hidden div near the top of your blade file -->
<div id="gradeSystemData" data-grade-system="{{ json_encode($gradeSystemData) }}"></div>
<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="confirmModalBody">
                Are you sure you want to perform this action?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmActionBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
// Global variables
const examId = "{{ $examId }}";
const totalMarks = {{ $exam->total_marks }};
const passingMarks = {{ $exam->passing_marks }};
let currentPage = 1;
const pageSize = 50; // Students per page
let allStudents = [];
let filteredStudents = [];
let selectedStudents = new Set();
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    loadStudents();
    
    // Setup event listeners
    document.getElementById('searchInput').addEventListener('keyup', function(event) {
        if (event.key === 'Enter') {
            loadStudents();
        }
    });
    
    // Fix the checkAll functionality
    document.getElementById('checkAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.student-checkbox');
        
        // Check/uncheck all checkboxes on current page
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
            if (this.checked) {
                selectedStudents.add(checkbox.value);
            } else {
                selectedStudents.delete(checkbox.value);
            }
        });
        
        // Update "Select All Students" checkbox
        document.getElementById('selectAllStudents').checked = 
            selectedStudents.size === filteredStudents.length;
            
        updateBulkButtonState();
    });
    
    // Fix the selectAllStudents functionality
    document.getElementById('selectAllStudents').addEventListener('change', function() {
        if (this.checked) {
            // Select all students across all pages
            filteredStudents.forEach(student => {
                selectedStudents.add(student.student_hash_id);
            });
        } else {
            // Clear all selections
            selectedStudents.clear();
        }
        
        updateStudentCheckboxes();
        updateBulkButtonState();
    });
});

function loadStudents() {
    const loadingSpinner = document.getElementById('loadingSpinner');
    const studentsTableBody = document.getElementById('studentsTableBody');
    const noStudents = document.getElementById('noStudents');
    const paginationContainer = document.getElementById('paginationContainer');
    
    // Show loading
    loadingSpinner.style.display = 'block';
    studentsTableBody.style.display = 'none';
    noStudents.style.display = 'none';
    paginationContainer.style.display = 'none';
    
    // Get filter values
    const filters = {
        search: document.getElementById('searchInput').value,
        status: document.getElementById('statusFilter').value
    };
    
    // Build query string
    const queryParams = new URLSearchParams(filters).toString();
    const url = `{{ route('employee.exam.students.with.marks', ['examId' => $examId]) }}${queryParams ? '?' + queryParams : ''}`;
    
    fetch(url, {
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        loadingSpinner.style.display = 'none';
        
        if (data.success) {
            allStudents = data.students;
            filteredStudents = [...allStudents];
            
            // Update stats
            updateStats();
            
            if (filteredStudents.length > 0) {
                renderStudentsTable();
                studentsTableBody.style.display = 'table-row-group';
                noStudents.style.display = 'none';
                
                // Show pagination if needed
                if (filteredStudents.length > pageSize) {
                    paginationContainer.style.display = 'flex';
                    updatePagination();
                }
            } else {
                studentsTableBody.style.display = 'none';
                noStudents.style.display = 'block';
                paginationContainer.style.display = 'none';
            }
        } else {
            showToast('error', data.message || 'Error loading students');
            studentsTableBody.style.display = 'none';
            noStudents.style.display = 'block';
        }
    })
    .catch(error => {
        loadingSpinner.style.display = 'none';
        showToast('error', 'Error loading students. Please try again.');
        studentsTableBody.style.display = 'none';
        noStudents.style.display = 'block';
    });
}

// Add this to your JavaScript section
function showGradeInfo(grade) {
    const gradeSystemElement = document.getElementById('gradeSystemData');
    let gradeSystemData = null;
    
    if (gradeSystemElement && gradeSystemElement.dataset.gradeSystem) {
        try {
            gradeSystemData = JSON.parse(gradeSystemElement.dataset.gradeSystem);
            
            if (gradeSystemData && gradeSystemData.grade_ranges) {
                let ranges = gradeSystemData.grade_ranges;
                if (typeof ranges === 'string') {
                    ranges = JSON.parse(ranges);
                }
                
                if (Array.isArray(ranges)) {
                    const gradeInfo = ranges.find(range => range.grade === grade);
                    if (gradeInfo) {
                        return `${gradeInfo.description} (${gradeInfo.min_percentage}%-${gradeInfo.max_percentage}%)`;
                    }
                }
            }
        } catch (e) {
            console.error('Error getting grade info:', e);
        }
    }
    
    return 'Grade information not available';
}

function renderStudentsTable() {
    const tableBody = document.getElementById('studentsTableBody');
    const startIndex = (currentPage - 1) * pageSize;
    const endIndex = Math.min(startIndex + pageSize, filteredStudents.length);
    const pageStudents = filteredStudents.slice(startIndex, endIndex);
    
    tableBody.innerHTML = '';
    
    pageStudents.forEach((student, index) => {
        const globalIndex = startIndex + index + 1;
        const isSelected = selectedStudents.has(student.student_hash_id);
        const marks = student.existing_marks ? student.existing_marks.obtained_marks : '';
        const remarks = student.existing_marks ? (student.existing_marks.remarks || '') : '';
        
        const row = document.createElement('tr');
        row.className = 'student-row';
        row.id = `student-row-${student.student_hash_id}`;
        row.dataset.originalMarks = marks; // Store original marks for comparison
        
        row.innerHTML = `
                <td>
                    <input type="checkbox" class="student-checkbox" 
                        value="${student.student_hash_id}" 
                        ${isSelected ? 'checked' : ''}
                        onchange="toggleStudentSelection('${student.student_hash_id}', this.checked)">
                </td>
                <td>${globalIndex}</td>
                <td>${escapeHtml(student.registration_number)}</td>
                <td>${escapeHtml(student.student_name)}</td>
                <td>${escapeHtml(student.section_name)}</td>
                <td>
                    <input type="number" 
                        class="form-control marks-input" 
                        id="marks-${student.student_hash_id}"
                        value="${marks}"
                        min="0" 
                        max="${totalMarks}"
                        step="0.01"
                        onchange="calculateGradeForStudent('${student.student_hash_id}', this.value)"
                        oninput="enableSaveButton('${student.student_hash_id}')">
                </td>
                <td>
                    <span id="grade-${student.student_hash_id}" 
                        class="grade-badge"
                        title="${student.existing_marks ? showGradeInfo(student.existing_marks.grade) : 'Enter marks to calculate grade'}">
                        ${student.existing_marks ? student.existing_marks.grade : '-'}
                    </span>
                </td>
                <td>
                    <input type="text" 
                        class="form-control remarks-input" 
                        id="remarks-${student.student_hash_id}"
                        value="${remarks}"
                        placeholder="Remarks"
                        oninput="enableSaveButton('${student.student_hash_id}')">
                </td>
                <td>
                    <span class="status-badge status-${student.status}">
                        ${student.status.charAt(0).toUpperCase() + student.status.slice(1)}
                    </span>
                </td>
                <td>
                    <small class="text-muted">
                        ${student.existing_marks ? formatDate(student.existing_marks.marked_at) : 'Not marked'}
                    </small>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="btn btn-sm btn-primary save-btn" 
                                onclick="saveStudentMarks('${student.student_hash_id}')"
                                id="save-btn-${student.student_hash_id}"
                                ${student.is_marked ? 'disabled' : ''}>
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" 
                                onclick="viewStudentHistory('${student.student_hash_id}')">
                            <i class="fas fa-history"></i>
                        </button>
                    </div>
                </td>
            `;
        
        tableBody.appendChild(row);
        
        // Calculate grade if marks exist
        if (marks) {
            calculateGradeForStudent(student.student_hash_id, marks);
        }
    });
    
    // Update pagination info
    updatePaginationInfo(startIndex, endIndex, filteredStudents.length);
}

function enableSaveButton(studentHashId) {
    const saveButton = document.getElementById(`save-btn-${studentHashId}`);
    if (saveButton) {
        saveButton.disabled = false;
    }
}
function calculateGradeForStudent(studentHashId, marks) {
    if (!marks || marks === '' || marks === null) {
        const gradeElement = document.getElementById(`grade-${studentHashId}`);
        if (gradeElement) {
            gradeElement.textContent = '-';
            gradeElement.className = 'grade-badge';
        }
        return;
    }
    
    const percentage = (parseFloat(marks) / totalMarks) * 100;
    
    // Get grade system from the hidden element
    const gradeSystemElement = document.getElementById('gradeSystemData');
    let gradeSystemData = null;
    
    if (gradeSystemElement && gradeSystemElement.dataset.gradeSystem) {
        try {
            gradeSystemData = JSON.parse(gradeSystemElement.dataset.gradeSystem);
            console.log('Grade System Data:', gradeSystemData);
        } catch (e) {
            console.error('Error parsing grade system:', e);
        }
    }
    
    // Use grade system if available, otherwise use default
    if (gradeSystemData && gradeSystemData.grade_ranges) {
        const gradeRanges = gradeSystemData.grade_ranges;
        
        // Check if grade_ranges is a string (JSON) or already an array
        let ranges = gradeRanges;
        if (typeof gradeRanges === 'string') {
            try {
                ranges = JSON.parse(gradeRanges);
            } catch (e) {
                console.error('Error parsing grade ranges JSON:', e);
                ranges = null;
            }
        }
        
        if (Array.isArray(ranges) && ranges.length > 0) {
            // Sort ranges by min_percentage descending for proper matching
            const sortedRanges = ranges.sort((a, b) => b.min_percentage - a.min_percentage);
            
            // Find the matching grade
            for (const range of sortedRanges) {
                if (percentage >= range.min_percentage) {
                    updateGradeDisplay(studentHashId, range.grade, getGradeClass(range.grade));
                    return;
                }
            }
        }
    }
    
    // Fallback to default grading
    const defaultGrade = getDefaultGrade(percentage, marks);
    updateGradeDisplay(studentHashId, defaultGrade.grade, defaultGrade.class);
}

// Helper function to get grade class
function getGradeClass(grade) {
    const gradeMap = {
        'A+': 'grade-A', 'A': 'grade-A', 'A-': 'grade-A',
        'B+': 'grade-B', 'B': 'grade-B', 'B-': 'grade-B',
        'C+': 'grade-C', 'C': 'grade-C', 'C-': 'grade-C',
        'D+': 'grade-D', 'D': 'grade-D', 'D-': 'grade-D',
        'E': 'grade-E', 'F': 'grade-F', 'M': 'grade-F'
    };
    return gradeMap[grade] || 'grade-F';
}

function getGradeClass(grade) {
    const gradeMap = {
        'A+': 'grade-A', 'A': 'grade-A',
        'B+': 'grade-B', 'B': 'grade-B',
        'C+': 'grade-C', 'C': 'grade-C',
        'D+': 'grade-D', 'D': 'grade-D',
        'E': 'grade-E', 'F': 'grade-F'
    };
    return gradeMap[grade] || 'grade-F';
}

function updateGradeDisplay(studentHashId, grade, gradeClass) {
    const gradeElement = document.getElementById(`grade-${studentHashId}`);
    if (gradeElement) {
        gradeElement.textContent = grade;
        gradeElement.className = `grade-badge ${gradeClass}`;
    }
}
function saveStudentMarks(studentHashId) {
    const marksInput = document.getElementById(`marks-${studentHashId}`);
    const remarksInput = document.getElementById(`remarks-${studentHashId}`);
    
    const marks = marksInput ? marksInput.value.trim() : '';
    const remarks = remarksInput ? remarksInput.value.trim() : '';
    
    if (!marks) {
        showToast('warning', 'Please enter marks for this student');
        marksInput.focus();
        return;
    }
    
    const marksValue = parseFloat(marks);
    if (marksValue > totalMarks) {
        showToast('error', `Marks cannot exceed total marks (${totalMarks})`);
        return;
    }
    
    // Disable save button and show loading
    const saveButton = document.getElementById(`save-btn-${studentHashId}`);
    const originalHtml = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    saveButton.disabled = true;
    
    // Send data to server
    fetch(`{{ route('employee.exam.marks.update', '') }}/${studentHashId}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            exam_id: examId,
            obtained_marks: marksValue,
            remarks: remarks
        })
    })
    .then(response => response.json())
    .then(data => {
        // Re-enable button
        saveButton.innerHTML = originalHtml;
        
        if (data.success) {
            showToast('success', data.message);
            
            // Update the student object in the local array
            const studentIndex = filteredStudents.findIndex(s => s.student_hash_id === studentHashId);
            if (studentIndex !== -1) {
                filteredStudents[studentIndex].existing_marks = {
                    obtained_marks: marksValue,
                    remarks: remarks,
                    grade: data.data.grade,
                    marked_at: new Date().toISOString()
                };
                filteredStudents[studentIndex].is_marked = true;
                filteredStudents[studentIndex].status = data.data.status;
            }
            
            // Update student status in the table UI
            updateStudentRowStatus(studentHashId, data.data.status, marksValue);
            
            // Update stats
            updateStats();
            
            // Remove from selected students if selected
            selectedStudents.delete(studentHashId);
            updateStudentCheckboxes();
            
            // Update dashboard progress using localStorage or broadcast
            setDashboardRefreshFlag(examId);
            
        } else {
            saveButton.disabled = false;
            showToast('error', data.message);
        }
    })
    .catch(error => {
        saveButton.innerHTML = originalHtml;
        saveButton.disabled = false;
        showToast('error', 'Error saving marks. Please try again.');
    });
}

function updateStudentRowStatus(studentHashId, status, marks) {
    const row = document.getElementById(`student-row-${studentHashId}`);
    if (!row) return;
    
    // Update status badge (9th column)
    const statusCell = row.querySelector('td:nth-child(9)');
    if (statusCell) {
        const badge = statusCell.querySelector('.status-badge');
        if (badge) {
            // Remove existing status classes
            badge.classList.remove('status-pending', 'status-passed', 'status-failed');
            // Add new status class
            badge.classList.add(`status-${status}`);
            badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        }
    }
    
    // Update last updated time (10th column)
    const timeCell = row.querySelector('td:nth-child(10)');
    if (timeCell) {
        const timeElement = timeCell.querySelector('small');
        if (timeElement) {
            timeElement.textContent = formatDate(new Date().toISOString());
        }
    }
    
    // Calculate and update grade (7th column)
    calculateGradeForStudent(studentHashId, marks);
    
    // Disable save button since marks are now saved
    const saveButton = document.getElementById(`save-btn-${studentHashId}`);
    if (saveButton) {
        saveButton.disabled = true;
    }
    
    // Update the row's original marks dataset
    row.dataset.originalMarks = marks;
}

function applyBulkMarks() {
    const bulkMarksInput = document.getElementById('bulkMarksInput');
    const bulkMarks = bulkMarksInput ? bulkMarksInput.value.trim() : '';
    
    if (!bulkMarks) {
        showToast('warning', 'Please enter marks to apply');
        bulkMarksInput.focus();
        return;
    }
    
    const marksValue = parseFloat(bulkMarks);
    if (marksValue > totalMarks) {
        showToast('error', `Marks cannot exceed total marks (${totalMarks})`);
        return;
    }
    
    if (selectedStudents.size === 0) {
        showToast('warning', 'Please select at least one student');
        return;
    }
    
    // Show confirmation
    showConfirmation(
        `Apply ${marksValue} marks to ${selectedStudents.size} selected student(s)?`,
        function() {
            performBulkUpdate(marksValue, null);
        }
    );
}

function applyBulkRemarks() {
    const bulkRemarksInput = document.getElementById('bulkRemarksInput');
    const bulkRemarks = bulkRemarksInput ? bulkRemarksInput.value.trim() : '';
    
    if (selectedStudents.size === 0) {
        showToast('warning', 'Please select at least one student');
        return;
    }
    
    // Apply remarks to selected students in UI
    selectedStudents.forEach(studentHashId => {
        const remarksInput = document.getElementById(`remarks-${studentHashId}`);
        if (remarksInput) {
            remarksInput.value = bulkRemarks;
        }
    });
    
    showToast('success', `Remarks applied to ${selectedStudents.size} student(s)`);
}

function performBulkUpdate(marks, remarks) {
    // Disable bulk action buttons
    const buttons = document.querySelectorAll('.bulk-actions button');
    buttons.forEach(btn => btn.disabled = true);
    
    // Convert Set to Array for sending to server
    const studentIds = Array.from(selectedStudents);
    
    fetch('{{ route("employee.exam.marks.bulk.update") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            exam_id: examId,
            student_ids: studentIds,
            marks: marks,
            remarks: remarks
        })
    })
    .then(response => response.json())
    .then(data => {
        // Re-enable buttons
        buttons.forEach(btn => btn.disabled = false);
        
        if (data.success) {
            showToast('success', data.message);
            
            // Update ONLY selected students in UI and local array
            selectedStudents.forEach(studentHashId => {
                // Update local array
                const studentIndex = filteredStudents.findIndex(s => s.student_hash_id === studentHashId);
                if (studentIndex !== -1) {
                    filteredStudents[studentIndex].existing_marks = {
                        obtained_marks: marks,
                        remarks: remarks,
                        grade: data.data.grade,
                        marked_at: new Date().toISOString()
                    };
                    filteredStudents[studentIndex].is_marked = true;
                    filteredStudents[studentIndex].status = data.data.status;
                }
                
                // Update UI
                const marksInput = document.getElementById(`marks-${studentHashId}`);
                if (marksInput) {
                    marksInput.value = marks;
                    calculateGradeForStudent(studentHashId, marks);
                }
                
                if (remarks !== null) {
                    const remarksInput = document.getElementById(`remarks-${studentHashId}`);
                    if (remarksInput) {
                        remarksInput.value = remarks;
                    }
                }
                
                // Update status
                updateStudentRowStatus(studentHashId, data.data.status, marks);
            });
            
            // Clear selection
            selectedStudents.clear();
            updateStudentCheckboxes();
            
            // Update stats
            updateStats();
            
            // Clear bulk input fields
            if (document.getElementById('bulkMarksInput')) {
                document.getElementById('bulkMarksInput').value = '';
            }
            if (document.getElementById('bulkRemarksInput')) {
                document.getElementById('bulkRemarksInput').value = '';
            }
            
            // Set flag to refresh dashboard
            setDashboardRefreshFlag(examId);
            
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        buttons.forEach(btn => btn.disabled = false);
        showToast('error', 'Error performing bulk update');
    });
}

function saveAllChanges() {
    const studentsToSave = [];
    
    // Get all rows and check for changes
    document.querySelectorAll('.student-row').forEach(row => {
        const studentHashId = row.id.replace('student-row-', '');
        const marksInput = document.getElementById(`marks-${studentHashId}`);
        const remarksInput = document.getElementById(`remarks-${studentHashId}`);
        
        if (marksInput && marksInput.value.trim() !== '') {
            const newMarks = parseFloat(marksInput.value.trim());
            const originalMarks = row.dataset.originalMarks ? parseFloat(row.dataset.originalMarks) : null;
            const remarks = remarksInput ? remarksInput.value.trim() : '';
            
            // Only save if marks have changed or are different from existing marks
            if (newMarks !== originalMarks) {
                studentsToSave.push({
                    student_hash_id: studentHashId,
                    marks: newMarks,
                    remarks: remarks
                });
            }
        }
    });
    
    if (studentsToSave.length === 0) {
        showToast('info', 'No changes detected to save');
        return;
    }
    
    showConfirmation(
        `Save marks for ${studentsToSave.length} student(s)?`,
        async function() {
            // Show loading
            showToast('info', `Saving marks for ${studentsToSave.length} student(s)...`, 3000);
            
            let savedCount = 0;
            let failedCount = 0;
            
            // Save each student sequentially to avoid overwhelming the server
            for (const studentData of studentsToSave) {
                try {
                    // Use the main save function but with promise
                    await saveStudentMarksPromise(studentData.student_hash_id, studentData.marks, studentData.remarks);
                    savedCount++;
                    
                } catch (error) {
                    console.error(`Error saving student ${studentData.student_hash_id}:`, error);
                    failedCount++;
                }
            }
            
            // Update stats after all saves
            updateStats();
            
            // Show final message
            if (failedCount > 0) {
                showToast('warning', `Saved ${savedCount}/${studentsToSave.length} students. ${failedCount} failed.`);
            } else {
                showToast('success', `Successfully saved marks for ${savedCount} student(s)`);
            }
            
            // Set flag to refresh dashboard
            setDashboardRefreshFlag(examId);
        }
    );
}

// Helper function to save marks and return a promise
function saveStudentMarksPromise(studentHashId, marks, remarks) {
    return new Promise((resolve, reject) => {
        fetch(`{{ route('employee.exam.marks.update', '') }}/${studentHashId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                exam_id: examId,
                obtained_marks: marks,
                remarks: remarks
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update local array and UI
                const studentIndex = filteredStudents.findIndex(s => s.student_hash_id === studentHashId);
                if (studentIndex !== -1) {
                    const status = marks >= passingMarks ? 'passed' : 'failed';
                    const grade = calculateGradeValue(marks);
                    
                    filteredStudents[studentIndex].existing_marks = {
                        obtained_marks: marks,
                        remarks: remarks,
                        grade: grade,
                        marked_at: new Date().toISOString()
                    };
                    filteredStudents[studentIndex].is_marked = true;
                    filteredStudents[studentIndex].status = status;
                    
                    // Update the row's original marks dataset
                    const row = document.getElementById(`student-row-${studentHashId}`);
                    if (row) {
                        row.dataset.originalMarks = marks;
                    }
                    
                    // Update UI immediately
                    updateStudentRowStatus(studentHashId, status, marks);
                }
                
                resolve(data);
            } else {
                reject(new Error(data.message));
            }
        })
        .catch(error => {
            reject(error);
        });
    });
}

// Helper function to calculate grade value
function calculateGradeValue(marks) {
    const percentage = (parseFloat(marks) / totalMarks) * 100;
    
    if (percentage >= 90) return 'A+';
    if (percentage >= 80) return 'A';
    if (percentage >= 70) return 'B+';
    if (percentage >= 60) return 'B';
    if (percentage >= 50) return 'C+';
    if (parseFloat(marks) >= passingMarks) return 'C';
    if (percentage >= 40) return 'D';
    return 'F';
}


// Update this function in marking page
function setDashboardRefreshFlag(examId) {
    console.log('Setting dashboard refresh flag for exam:', examId);
    
    // Set the refresh flag
    localStorage.setItem('refreshDashboard', 'true');
    localStorage.setItem('refreshDashboardTime', new Date().getTime().toString());
    localStorage.setItem('refreshExamId', examId);
    
    console.log('Refresh flags set in localStorage:', {
        refreshDashboard: localStorage.getItem('refreshDashboard'),
        refreshDashboardTime: localStorage.getItem('refreshDashboardTime'),
        refreshExamId: localStorage.getItem('refreshExamId')
    });
}

function toggleStudentSelection(studentHashId, isSelected) {
    if (isSelected) {
        selectedStudents.add(studentHashId);
    } else {
        selectedStudents.delete(studentHashId);
    }
    
    updateBulkButtonState();
    
    // Update "Select All" checkbox state
    document.getElementById('selectAllStudents').checked = 
        selectedStudents.size === filteredStudents.length;
}

function updateStudentCheckboxes() {
    const checkboxes = document.querySelectorAll('.student-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectedStudents.has(checkbox.value);
    });
    document.getElementById('checkAll').checked = 
        selectedStudents.size === checkboxes.length;
}

function updateBulkButtonState() {
    const bulkButtons = document.querySelectorAll('.bulk-actions button:not([type="button"])');
    const hasSelection = selectedStudents.size > 0;
    
    bulkButtons.forEach(button => {
        button.disabled = !hasSelection;
    });
}

function updateStats() {
    const totalStudents = filteredStudents.length;
    const markedStudents = filteredStudents.filter(s => s.is_marked).length;
    
    document.getElementById('totalStudents').textContent = totalStudents;
    document.getElementById('markedStudents').textContent = markedStudents;
}

function updatePagination() {
    const totalPages = Math.ceil(filteredStudents.length / pageSize);
    document.getElementById('currentPage').textContent = currentPage;
    
    // Disable/enable buttons
    const prevButton = document.querySelector('button[onclick="previousPage()"]');
    const nextButton = document.querySelector('button[onclick="nextPage()"]');
    
    prevButton.disabled = currentPage === 1;
    nextButton.disabled = currentPage === totalPages;
}

function updatePaginationInfo(startIndex, endIndex, totalRecords) {
    document.getElementById('startIndex').textContent = startIndex + 1;
    document.getElementById('endIndex').textContent = endIndex;
    document.getElementById('totalRecords').textContent = totalRecords;
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        renderStudentsTable();
        updatePagination();
    }
}

function nextPage() {
    const totalPages = Math.ceil(filteredStudents.length / pageSize);
    if (currentPage < totalPages) {
        currentPage++;
        renderStudentsTable();
        updatePagination();
    }
}

function viewStudentHistory(studentHashId) {
    // Implement history view if needed
    showToast('info', 'Student history feature coming soon');
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

    // Bootstrap 4 (jQuery-based)
    if (window.$ && $.fn.modal) {
        $(modalElement).modal('hide');
        return;
    }

    // Bootstrap 5 fallback
    if (bootstrap?.Modal) {
        let modal = bootstrap.Modal.getInstance(modalElement);
        if (!modal) {
            modal = new bootstrap.Modal(modalElement);
        }
        modal.hide();
    }
}

function showConfirmation(message, callback) {
    document.getElementById('confirmModalBody').textContent = message;

    const confirmBtn = document.getElementById('confirmActionBtn');
    confirmBtn.onclick = null;

    confirmBtn.onclick = async function () {
        confirmBtn.disabled = true;

        try {
            await callback();          // ✅ waits correctly
            hideModal('#confirmModal'); // ✅ NOW closes
        } catch (error) {
            console.error(error);
            showToast('error', error.message || 'Action failed');
        } finally {
            confirmBtn.disabled = false;
        }
    };

    showModal('#confirmModal');
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    
    const date = new Date(dateString);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
}

function showToast(type, message, duration = 5000) {
    const toastContainer = document.getElementById('toastContainer');
    
    const toast = document.createElement('div');
    toast.className = `toast custom-toast align-items-center`;
    toast.setAttribute('role', 'alert');
    
    let bgColor, icon;
    switch(type) {
        case 'success': bgColor = '#28a745'; icon = 'fa-check-circle'; break;
        case 'error': bgColor = '#dc3545'; icon = 'fa-exclamation-circle'; break;
        case 'warning': bgColor = '#ffc107'; icon = 'fa-exclamation-triangle'; break;
        case 'info': bgColor = '#17a2b8'; icon = 'fa-info-circle'; break;
    }
    
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="toast-body d-flex align-items-center">
                <i class="fas ${icon} fa-lg me-3"></i>
                <span class="me-3">${message}</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                    data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast, {
        animation: true,
        autohide: true,
        delay: duration
    });
    
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
}

function escapeHtml(text) {
    if (text === null || text === undefined) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Check for unsaved changes before leaving
window.addEventListener('beforeunload', function(event) {
    let hasUnsavedChanges = false;
    
    document.querySelectorAll('.marks-input').forEach(input => {
        const studentHashId = input.id.replace('marks-', '');
        const row = document.getElementById(`student-row-${studentHashId}`);
        
        if (row) {
            const currentMarks = input.value.trim();
            const originalMarks = row.dataset.originalMarks || '';
            
            if (currentMarks && currentMarks !== originalMarks) {
                hasUnsavedChanges = true;
            }
        }
    });
    
    if (hasUnsavedChanges) {
        // Standard way to show confirmation
        event.preventDefault();
        event.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
    }
});

</script>
@endsection
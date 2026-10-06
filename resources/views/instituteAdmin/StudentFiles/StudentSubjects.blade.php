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


/* Card Header */
.card-header-custom {
    background: var(--primary-gradient) !important;
    border-radius: 20px 20px 0 0 !important;
    padding: 25px 30px !important;
    position: relative;
    overflow: hidden;
}

.card-header-custom h3 {
    font-size: 1.4rem;
    font-weight: 700;
    position: relative;
    z-index: 1;
}

.card-header-custom p {
    position: relative;
    z-index: 1;
}

/* Shadow Card */
.card.border-0.shadow {
    border-radius: 20px !important;
    box-shadow: 0 10px 30px rgba(67, 97, 238, 0.15) !important;
    border: 2px solid rgba(67, 97, 238, 0.1) !important;
    overflow: hidden;
}

/* Section Title */
.section-title {
    color: var(--primary-color);
    font-weight: 700;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid rgba(67, 97, 238, 0.15);
    position: relative;
}

.section-title:after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -2px;
    width: 60px;
    height: 3px;
    background: var(--primary-gradient);
    border-radius: 3px;
}

/* Info Card */
.info-card {
    background: white;
    border-radius: 16px;
    padding: 22px 25px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
    margin-bottom: 25px;
    border: 2px solid rgba(67, 97, 238, 0.08);
}

.info-card h6 {
    color: var(--primary-color);
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 18px;
    font-weight: 700;
}

.info-item {
    display: flex;
    align-items: center;
    margin: 5px;
}

.info-item i {
    width: 34px;
    height: 34px;
    background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(58, 12, 163, 0.1));
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 14px;
    color: var(--primary-color);
    font-size: 0.9rem;
}

.info-label {
    font-weight: 700;
    color: #475569;
    font-size: 0.85rem;
    gap: 10px;
}

.info-value {
    color: #334155;
    font-weight: 600;
    font-size: 0.9rem;
    margin-left: 8px;
}

/* Stats Card */
.stats-card {
    text-align: center;
    padding: 22px 20px;
    border-radius: 16px;
    background: white;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
    border: 2px solid rgba(67, 97, 238, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.12);
}

.stats-number {
    font-size: 2.2rem;
    font-weight: 800;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 6px;
}

.stats-label {
    color: #64748b;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}

/* Subject Card */
.subject-card {
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
    border: none;
    margin-bottom: 20px;
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.subject-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(67, 97, 238, 0.12);
}

.subject-card.individual {
    border-top: 4px solid var(--success-color);
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
}

.subject-card.deafault {
    border-top: 4px solid var(--primary-color);
    background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
}

.subject-card-header {
    padding: 20px 25px 15px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.04);
    background: rgba(255, 255, 255, 0.6);
}

.subject-card-body {
    padding: 20px 25px;
}

.subject-badge {
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.badge-individual {
    background: var(--success-gradient);
    color: white;
    box-shadow: 0 3px 10px rgba(16, 185, 129, 0.3);
}

.badge-default {
    background: var(--primary-gradient);
    color: white;
    box-shadow: 0 3px 10px rgba(67, 97, 238, 0.3);
}

.subject-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 1.2rem;
    color: white;
}

.subject-icon.individual {
    background: var(--success-gradient);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.subject-icon.default {
    background: var(--primary-gradient);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

.subject-meta {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    color: #64748b;
    font-size: 0.85rem;
}

.subject-meta i {
    width: 20px;
    margin-right: 8px;
    color: var(--primary-color);
}

.subject-tag {
    display: inline-block;
    padding: 4px 12px;
    background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(58, 12, 163, 0.1));
    color: var(--primary-color);
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    margin-right: 6px;
    margin-bottom: 6px;
}

.subject-tag.semester {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
    color: var(--success-color);
}

.subject-tag.id {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1));
    color: #d97706;
}

/* Search Box */
.search-box {
    position: relative;
    max-width: 300px;
}

.search-box input {
    padding-left: 42px;
    border-radius: 30px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    height: 42px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    color: white !important;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.search-box input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.search-box input:focus {
    border-color: rgba(255, 255, 255, 0.5);
    background: rgba(255, 255, 255, 0.25);
    outline: none;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
}

.search-box i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.8);
    z-index: 1;
}

/* Toggle View Button */
.toggle-view-btn {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
    padding: 8px 18px;
    border-radius: 30px;
    color: white;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.toggle-view-btn.active {
    background: white;
    color: var(--primary-color);
    border-color: white;
}

.toggle-view-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    color:#fff;
}

/* Floating Action Button */
.floating-action-btn {
    position: fixed;
    bottom: 35px;
    right: 35px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: var(--primary-gradient);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    box-shadow: 0 10px 30px rgba(67, 97, 238, 0.4);
    border: none;
    cursor: pointer;
    z-index: 1000;
    transition: all 0.3s ease;
}

.floating-action-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 15px 35px rgba(67, 97, 238, 0.5);
}

/* Loading */
.loading-container {
    min-height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.loading-spinner {
    width: 50px;
    height: 50px;
    border: 3px solid rgba(67, 97, 238, 0.1);
    border-top-color: var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.no-data-icon {
    font-size: 60px;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 20px;
    opacity: 0.5;
}

/* Syllabus Modal */
.modal-content {
    border-radius: 20px;
    border: none;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.modal-header {
    background: var(--primary-gradient);
    color: white;
    border-radius: 20px 20px 0 0;
    border: none;
    padding: 18px 24px;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.modal-title {
    font-weight: 700;
    font-size: 1.1rem;
}

.modal-footer {
    border-top: 1px solid rgba(67, 97, 238, 0.1);
    padding: 15px 24px;
}

/* Syllabus Item */
.syllabus-item {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    transition: all 0.3s ease;
    border: 2px solid rgba(67, 97, 238, 0.1) !important;
    border-radius: 12px !important;
}

.syllabus-item:hover {
    background: white;
    box-shadow: 0 5px 20px rgba(67, 97, 238, 0.1);
    border-color: var(--primary-color) !important;
}

.syllabus-list {
    max-height: 500px;
    overflow-y: auto;
}

/* Button Styles */
.btn-sm {
    padding: 8px 16px;
    font-size: 0.8rem;
    border-radius: 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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
    border: none;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
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
    .card-header-custom h3 {
        font-size: 1.2rem;
    }
    
    .toggle-view-btn {
        padding: 6px 12px;
        font-size: 0.75rem;
    }
    
    .search-box {
        max-width: 200px;
    }
    
    .subject-card {
        margin-bottom: 15px;
    }
}

/* Alert */
.alert-danger {
    border-radius: 12px;
    border: none;
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border-left: 4px solid #dc2626;
    color: #991b1b;
}
</style>

<!-- Floating Action Button for Print/Download -->
<button class="d-none floating-action-btn" onclick="printSubjects()">
    <i class="fas fa-print"></i>
</button>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow">
                <div class="card-header-custom text-white border-0">
                    <div class="d-flex flex-wrap justify-content-between align-items-center" style="gap: 6px;">
                        <div>
                            <h3 class="mb-1"><i class="fas fa-book-open me-2"></i>Subjects</h3>
                            <p class="mb-0 opacity-75">View all your assigned subjects and course curriculum</p>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" id="searchSubjects" class="form-control"
                                    placeholder="Search subjects...">
                            </div>
                            <button class="d-none toggle-view-btn active" onclick="toggleView('grid')">
                                <i class="fas fa-th-large"></i> Grid
                            </button>
                            <button class="d-none toggle-view-btn" onclick="toggleView('list')">
                                <i class="fas fa-list"></i> List
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Info & Stats -->
    <div class="row">
        <div class="col-md-12">
            <div class="info-card">
                <h6><i class="fas fa-user-graduate me-2"></i>Student Information</h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-item">
                            <i class="fas fa-user"></i>
                            <span class="info-label">Name:</span>
                            <span class="info-value" id="studentName">Loading...</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-item">
                            <i class="fas fa-file-alt"></i>
                            <span class="info-label">Reg No:</span>
                            <span class="info-value" id="registrationNumber">Loading...</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span class="info-label">Course:</span>
                            <span class="info-value" id="courseInfo">Loading...</span>
                        </div>
                    </div>
                    <div class="d-none col-md-4">
                        <div class="info-item">
                            <i class="fas fa-id-card"></i>
                            <span class="info-label">Section:</span>
                            <span class="info-value" id="studentId">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-none col-lg-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="stats-card">
                        <div class="stats-number" id="totalSubjects">0</div>
                        <div class="stats-label">Total Subjects</div>
                    </div>
                </div>
                <div class="d-none col-md-6 col-lg-12">
                    <div class="stats-card">
                        <div id="assignmentType">
                            <div class="stats-number">-</div>
                            <div class="stats-label">Assignment Type</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subjects Loading -->
    <div id="loadingSubjects" class="loading-container">
        <div class="text-center">
            <div class="loading-spinner mb-3"></div>
            <p class="text-muted">Fetching your subjects...</p>
        </div>
    </div>

    <!-- Main Subjects Section -->
    <div id="mainSubjectsSection" style="display: none;">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="section-title mb-0"><i class="fas fa-book me-2"></i>Subjects</h4>
            <div class="text-muted">
                <span id="mainSubjectsCount">0</span> Subjects Found
            </div>
        </div>
        <div id="mainSubjectsList" class="row mt-3"></div>
    </div>

    <!-- Sub Subjects Section -->
    <div id="subSubjectsSection" style="display: none;">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="section-title mb-0"><i class="fas fa-book-reader me-2"></i>Sub Subjects</h4>
            <div class="text-muted">
                <span id="subSubjectsCount">0</span> Sub Subjects Found
            </div>
        </div>
        <div id="subSubjectsList" class="row"></div>
    </div>

    <!-- Syllabus Modal -->
    <div class="modal fade" id="syllabusModal" tabindex="-1" aria-labelledby="syllabusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="syllabusModalLabel">
                        <i class="fas fa-book-open me-2"></i>Subject Syllabus
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="syllabusContent">
                        <!-- Syllabus content will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- No Subjects Message -->
    <div id="noSubjectsMessage" class="empty-state" style="display: none;">
        <div class="no-data-icon">
            <i class="fas fa-book-open"></i>
        </div>
        <h4 class="mb-3">No Subjects Assigned</h4>
        <p class="text-muted mb-4">You don't have any subjects assigned yet. Please contact your institute
            administrator.</p>
        <button class="btn btn-primary" onclick="location.reload()">
            <i class="fas fa-sync-alt me-2"></i> Refresh
        </button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script>
let currentView = 'grid';
let allSubjects = [];
let allSubSubjects = [];

// Initialize Bootstrap modal
let syllabusModal = null;

$(document).ready(function() {
    // Initialize modal
    syllabusModal = new bootstrap.Modal(document.getElementById('syllabusModal'));

    loadStudentSubjects();

    // Initialize toggle buttons
    $('.toggle-view-btn').on('click', function() {
        const viewType = $(this).text().trim() === 'Grid' ? 'grid' : 'list';
        toggleView(viewType);
    });

    // Initialize search
    $('#searchSubjects').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        filterSubjects(searchTerm);
    });

    // Event delegation for syllabus buttons
    $(document).on('click', '.syllabus-btn', function() {
        const subjectName = $(this).data('subject-name');
        const syllabusDataStr = $(this).data('syllabus-data');
        showSyllabus(subjectName, syllabusDataStr);
    });
});

function loadStudentSubjects() {
    $.ajax({
        url: '{{ route("student.my-subjects.data") }}',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                displayStudentInfo(response.data);
                allSubjects = response.data.main_subjects || [];
                allSubSubjects = response.data.sub_subjects || [];
                displaySubjects(response.data);
            } else {
                showError(response.message);
            }
        },
        error: function(xhr) {
            console.error('Error loading subjects:', xhr);
            showError('Failed to load subjects. Please try again.');
        },
        complete: function() {
            $('#loadingSubjects').hide();
        }
    });
}

function displayStudentInfo(data) {
    // console.log('Student data:', data);
    $('#studentName').text(data.student.name);
    $('#studentId').text(data.student.student_hash_id);
    $('#registrationNumber').text(data.student.registration_number || 'N/A');
    $('#courseInfo').text(data.student.course);
    $('#totalSubjects').text(data.total_subjects);

    if (data.has_individual_assignments) {
        $('#assignmentType').html(`
            <div class="stats-number">Individual</div>
            <div class="stats-label">Assigned</div>
        `);
    } else {
        $('#assignmentType').html(`
            <div class="stats-number">Course</div>
            <div class="stats-label">Default</div>
        `);
    }
}

function displaySubjects(data) {
    // console.log('Subjects data:', data);

    // Display Main Subjects
    if (data.main_subjects && data.main_subjects.length > 0) {
        $('#mainSubjectsCount').text(data.main_subjects.length);
        let mainHtml = '';

        data.main_subjects.forEach(function(subject, index) {
            const isIndividual = subject.assigned_individually;
            const cardClass = isIndividual ? 'individual' : 'default';
            const badgeText = isIndividual ? 'Individual' : 'Course Default';
            const badgeClass = isIndividual ? 'badge-individual' : 'badge-default';
            const iconClass = isIndividual ? 'subject-icon individual' : 'subject-icon default';
            const icon = isIndividual ? 'fas fa-user-check' : 'fas fa-book';
            const hasSyllabus = subject.syllabus && subject.syllabus.length > 0;

            const escapedSubjectName = subject.subject_name.replace(/'/g, "&#39;");
            const syllabusDataStr = hasSyllabus ? JSON.stringify(subject.syllabus) : '[]';

            mainHtml += `
                <div class="col-md-4" data-subject-name="${subject.subject_name.toLowerCase()}" 
                    data-subject-id="${subject.subject_id.toLowerCase()}">
                    <div class="subject-card ${cardClass}">
                        <div class="subject-card-header">
                            <div class="d-flex align-items-center">
                                <div class="${iconClass}">
                                    <i class="${icon}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">${subject.subject_name}</h5>
                                    <span class="subject-badge ${badgeClass}">${badgeText}</span>
                                </div>
                            </div>
                        </div>
                        <div class="subject-card-body">
                            <div class="subject-meta">
                                <i class="fas fa-fingerprint"></i>
                                <span>ID: <strong>${subject.subject_id}</strong></span>
                            </div>
                            <div class="subject-meta">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Semester: <strong>${subject.semester_id === 'all_semesters' ? 'All Semesters' : 'Semester ' + subject.semester_id}</strong></span>
                            </div>
                            ${subject.assigned_date ? `
                                <div class="subject-meta">
                                    <i class="fas fa-calender-check"></i>
                                    <span>Assigned: <strong>${new Date(subject.assigned_date).toLocaleDateString('en-GB')}</strong></span>
                                </div>
                            ` : ''}
                            ${hasSyllabus ? `
                                <div class="subject-meta">
                                    <i class="fas fa-file-alt text-success"></i>
                                    <span>Syllabus: <strong>${subject.syllabus.length} file(s) available</strong></span>
                                </div>
                            ` : ''}
                            
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="subject-tag id">${subject.subject_id}</span>
                                    <span class="subject-tag semester">${subject.semester_id === 'all_semesters' ? 'All Sem' : 'Sem ' + subject.semester_id}</span>
                                    ${isIndividual ? '<span class="subject-tag" style="background:rgba(16,185,129,0.1);color:#059669;">Personalized</span>' : ''}
                                </div>
                                ${hasSyllabus ? `
                                    <button class="btn btn-sm btn-outline-primary syllabus-btn" 
                                            data-subject-name="${escapedSubjectName}"
                                            data-syllabus-data='${syllabusDataStr}'>
                                        <i class="fas fa-book-open me-1"></i> Syllabus
                                    </button>
                                ` : `
                                    <span class="text-muted small">
                                        <i class="fas fa-info-circle"></i> No syllabus
                                    </span>
                                `}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        $('#mainSubjectsList').html(mainHtml);
        $('#mainSubjectsSection').show();
    }

    // Display Sub Subjects
    if (data.sub_subjects && data.sub_subjects.length > 0) {
        $('#subSubjectsCount').text(data.sub_subjects.length);
        let subHtml = '';

        data.sub_subjects.forEach(function(subSubject, index) {
            const isIndividual = subSubject.assigned_individually;
            const cardClass = isIndividual ? 'individual' : 'default';
            const badgeText = isIndividual ? 'Individual' : 'Course Default';
            const badgeClass = isIndividual ? 'badge-individual' : 'badge-default';
            const iconClass = isIndividual ? 'subject-icon individual' : 'subject-icon default';
            const icon = isIndividual ? 'fas fa-user-graduate' : 'fas fa-book-reader';

            subHtml += `
                <div class="col-lg-4 col-md-6 mb-4" data-subject-name="${subSubject.sub_subject_name.toLowerCase()}" 
                     data-subject-id="${subSubject.sub_subject_id.toLowerCase()}">
                    <div class="subject-card ${cardClass}">
                        <div class="subject-card-header">
                            <div class="d-flex align-items-center">
                                <div class="${iconClass}">
                                    <i class="${icon}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">${subSubject.sub_subject_name}</h5>
                                    <span class="subject-badge ${badgeClass}">${badgeText}</span>
                                </div>
                            </div>
                        </div>
                        <div class="subject-card-body">
                            <div class="subject-meta">
                                <i class="fas fa-fingerprint"></i>
                                <span>ID: <strong>${subSubject.sub_subject_id}</strong></span>
                            </div>
                            <div class="subject-meta">
                                <i class="fas fa-layer-group"></i>
                                <span>Parent: <strong>${subSubject.subject_name}</strong></span>
                            </div>
                            <div class="subject-meta">
                                <i class="fas fa-hashtag"></i>
                                <span>Parent ID: <strong>${subSubject.subject_id}</strong></span>
                            </div>
                            ${subSubject.assigned_date ? `
                                <div class="subject-meta">
                                    <i class="fas fa-calendar-check"></i>
                                    <span>Assigned: <strong>${new Date(subSubject.assigned_date).toLocaleDateString('en-GB')}</strong></span>
                                </div>
                            ` : ''}
                            <div class="mt-3">
                                <span class="subject-tag id">${subSubject.sub_subject_id}</span>
                                <span class="subject-tag" style="background:rgba(67,97,238,0.1);color:#4361ee;">Sub-Subject</span>
                                ${isIndividual ? '<span class="subject-tag" style="background:rgba(16,185,129,0.1);color:#059669;">Personalized</span>' : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        $('#subSubjectsList').html(subHtml);
        $('#subSubjectsSection').show();
    }

    if ((!data.main_subjects || data.main_subjects.length === 0) &&
        (!data.sub_subjects || data.sub_subjects.length === 0)) {
        $('#noSubjectsMessage').show();
    }
}

function showSyllabus(subjectName, syllabusData) {
    const modalLabel = document.getElementById('syllabusModalLabel');
    const syllabusContent = document.getElementById('syllabusContent');

    modalLabel.innerHTML = `<i class="fas fa-book-open me-2"></i>Syllabus - ${subjectName}`;

    let syllabusArray = [];

    if (typeof syllabusData === 'string') {
        try {
            syllabusArray = JSON.parse(syllabusData);
        } catch (e) {
            console.error('Error parsing syllabus data string:', e);
            try {
                const fixedData = syllabusData.replace(/'/g, '"');
                syllabusArray = JSON.parse(fixedData);
            } catch (e2) {
                console.error('Second parsing attempt failed:', e2);
                syllabusArray = [];
            }
        }
    } else if (Array.isArray(syllabusData)) {
        syllabusArray = syllabusData;
    } else if (syllabusData && typeof syllabusData === 'object') {
        syllabusArray = [syllabusData];
    }

    if (!syllabusArray || syllabusArray.length === 0) {
        syllabusContent.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                <h4>No Syllabus Available</h4>
                <p class="text-muted">No syllabus has been uploaded for this subject yet.</p>
            </div>
        `;
    } else {
        let syllabusHtml = '<div class="syllabus-list">';

        syllabusArray.forEach((syllabus, index) => {
            const uploadDate = syllabus.uploaded_date ?
                new Date(syllabus.uploaded_date).toLocaleDateString('en-GB') : 'N/A';

            const fileUrl = syllabus.file_path ?
                '{{ route("image", ["path" => "__FILE_PATH__"]) }}'.replace('__FILE_PATH__', syllabus.file_path) :
                (syllabus.file_url || '#');

            syllabusHtml += `
                <div class="syllabus-item mb-4 p-3 border rounded">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="mb-1">${syllabus.title || 'Untitled Syllabus'}</h6>
                            ${syllabus.description ? `<p class="text-muted small mb-2">${syllabus.description}</p>` : ''}
                        </div>
                        <span class="badge bg-primary">${syllabus.syllabus_for ? 
                            syllabus.syllabus_for.replace('_', ' ').toUpperCase() : 'SYLLABUS'}</span>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-calendar-alt text-muted me-2"></i>
                                <small class="text-muted">Uploaded: ${uploadDate}</small>
                            </div>
                            ${syllabus.semester_id && syllabus.semester_id !== 'all_semesters' ? `
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-graduation-cap text-muted me-2"></i>
                                    <small class="text-muted">Semester: ${syllabus.semester_id}</small>
                                </div>
                            ` : ''}
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-file text-muted me-2"></i>
                                <small class="text-muted">${syllabus.file_name || 'No file name'}</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-3">
                        ${syllabus.file_path || syllabus.file_url ? `
                            <button class="btn btn-sm btn-primary me-2 view-syllabus-file-btn"
                                    data-file-url="${fileUrl}"
                                    data-file-name="${syllabus.file_name || 'syllabus'}"
                                    data-title="${syllabus.title || 'Syllabus'}">
                                <i class="fas fa-eye me-1"></i> View
                            </button>
                            <a href="${fileUrl}" download="${syllabus.file_name || 'syllabus'}" class="btn btn-sm btn-success">
                                <i class="fas fa-download me-1"></i> Download
                            </a>
                        ` : ''}
                    </div>
                </div>
            `;
        });

        syllabusHtml += '</div>';
        syllabusContent.innerHTML = syllabusHtml;

        $('.view-syllabus-file-btn').on('click', function() {
            const fileUrl = $(this).data('file-url');
            const fileName = $(this).data('file-name');
            const title = $(this).data('title');
            window.open(fileUrl, '_blank');
        });
    }

    syllabusModal.show();
}

function filterSubjects(searchTerm) {
    $('[data-subject-name]').each(function() {
        const subjectName = $(this).data('subject-name');
        const subjectId = $(this).data('subject-id');
        if (subjectName.includes(searchTerm) || subjectId.includes(searchTerm)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });

    const visibleMainSubjects = $('#mainSubjectsList [data-subject-name]:visible').length;
    const visibleSubSubjects = $('#subSubjectsList [data-subject-name]:visible').length;
    $('#mainSubjectsCount').text(visibleMainSubjects);
    $('#subSubjectsCount').text(visibleSubSubjects);
}

function toggleView(viewType) {
    currentView = viewType;
    $('.toggle-view-btn').removeClass('active');
    $(`.toggle-view-btn:contains(${viewType === 'grid' ? 'Grid' : 'List'})`).addClass('active');
    if (viewType === 'list') {
        $('#mainSubjectsList').removeClass('row').addClass('list-view');
        $('#subSubjectsList').removeClass('row').addClass('list-view');
        $('.subject-card').css('width', '100%');
    } else {
        $('#mainSubjectsList').removeClass('list-view').addClass('row');
        $('#subSubjectsList').removeClass('list-view').addClass('row');
        $('.subject-card').css('width', '');
    }
}

function printSubjects() {
    window.print();
}

function showError(message) {
    $('#loadingSubjects').html(`
        <div class="alert alert-danger border-0 shadow-sm">
            <i class="fas fa-exclamation-circle me-2"></i> ${message}
        </div>
    `);
}
</script>
@endsection
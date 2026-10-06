@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Creation System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #4bb543;
            --warning-color: #ff9e00;
            --danger-color: #e63946;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gradient-primary: linear-gradient(135deg, #4361ee, #3a0ca3);
            --gradient-success: linear-gradient(135deg, #4bb543, #2a9d40);
            --gradient-warning: linear-gradient(135deg, #ff9e00, #ff7b00);
            --gradient-danger: linear-gradient(135deg, #e63946, #d00000);
            --gradient-gold: linear-gradient(135deg, #FFD700, #FFA500);
            --gradient-purple: linear-gradient(135deg, #8A2BE2, #4B0082);
        }

        .header h1 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background: var(--gradient-primary);
            color: white;
            font-weight: 600;
            padding: 1rem 1.5rem;
            border-bottom: none;
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #444;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 0.75rem;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }

        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }

        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
        }

        .section-checkbox {
            margin-right: 10px;
        }

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .checkbox-item {
            background-color: #f0f3ff;
            border-radius: 6px;
            padding: 8px 15px;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }

        .checkbox-item:hover {
            background-color: #e2e7ff;
            transform: translateY(-2px);
        }

        .checkbox-item.checked {
            background-color: #e0f7fa;
            border: 1px solid #00bcd4;
        }

        .section-controls {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .exam-summary {
            background-color: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            margin-top: 2rem;
        }

        .summary-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f0f3ff;
        }

        .summary-item {
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .summary-label {
            font-weight: 600;
            color: #666;
        }

        .summary-value {
            font-weight: 600;
            color: #333;
        }

        .alert-success {
            background: var(--gradient-success);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
        }

        footer {
            text-align: center;
            margin-top: 2rem;
            color: #777;
            font-size: 0.9rem;
        }

        .section-selector {
            max-height: 200px;
            overflow-y: auto;
            padding: 15px;
            background-color: #f8f9ff;
            border-radius: 10px;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }

        .step-indicator:before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #e0e0e0;
            z-index: 1;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
            flex: 1;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e0e0e0;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 8px;
            transition: all 0.3s;
        }

        .step.active .step-circle {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
        }

        .step.completed .step-circle {
            background: var(--gradient-success);
            color: white;
        }

        .step-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #666;
            text-align: center;
        }

        .step.active .step-label {
            color: var(--primary-color);
        }

        .duration-input-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .duration-input {
            flex: 1;
        }

        .duration-unit {
            min-width: 80px;
        }

        .selected-count {
            font-size: 0.9rem;
            color: #666;
            margin-top: 5px;
        }

        .loading-spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .select-placeholder {
            color: #999;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }
            
            .step-indicator {
                flex-wrap: wrap;
            }
            
            .step {
                flex: 0 0 33.333%;
                margin-bottom: 15px;
            }
            
            .duration-input-group {
                flex-direction: column;
                align-items: stretch;
            }
            
            .duration-unit {
                min-width: auto;
            }
            
            .section-controls {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="container-fluid">
            <h1><i class="fas fa-file-alt me-2"></i>Offline Exam Creation System</h1>
            <p>Create and schedule exams for your students with ease</p>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step active">
                <div class="step-circle">1</div>
                <div class="step-label">Category</div>
            </div>
            <div class="step">
                <div class="step-circle">2</div>
                <div class="step-label">Select Department</div>
            </div>
            <div class="step">
                <div class="step-circle">3</div>
                <div class="step-label">Select Class & Sections</div>
            </div>
            <div class="step">
                <div class="step-circle">4</div>
                <div class="step-label">Select Subject</div>
            </div>
            <div class="step">
                <div class="step-circle">5</div>
                <div class="step-label">Exam Details</div>
            </div>
        </div>

        <!-- Exam Form -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-plus-circle me-2"></i>Create New Exam
            </div>
            <div class="card-body">
                <form id="examForm">
                    <!-- CSRF Token for AJAX requests -->
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    
                    <!-- Step 1: Select Category (Academic/Non-Academic) -->
                    <div class="mb-4">
                        <h5 class="mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-layer-group me-2"></i>Step 1: Select Category
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="categorySelect" class="form-label">Exam Category</label>
                                <select class="form-select" id="categorySelect" required>
                                    <option value="" selected disabled>Select a category</option>
                                    <option value="1">Academic</option>
                                    <option value="2">Non-Academic</option>
                                </select>
                                <div class="form-text">Select whether this is an academic or non-academic exam</div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Select Department -->
                    <div class="mb-4">
                        <h5 class="mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-building me-2"></i>Step 2: Select Department
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="departmentSelect" class="form-label">Department</label>
                                <select class="form-select" id="departmentSelect" required disabled>
                                    <option value="" selected class="select-placeholder">Please select a category first</option>
                                </select>
                                <div class="form-text" id="departmentLoading" style="display: none;">
                                    <span class="loading-spinner"></span> Loading departments...
                                </div>
                                <div class="form-text" id="departmentText">Select the department for which you want to create the exam</div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Select Class/Course -->
                    <div class="mb-4">
                        <h5 class="mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-graduation-cap me-2"></i>Step 3: Select Class/Course & Sections
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="courseSelect" class="form-label">Class/Course</label>
                                <select class="form-select" id="courseSelect" required disabled>
                                    <option value="" selected class="select-placeholder">Please select a department first</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Select Section(s)</label>
                                <!-- Section Controls -->
                                <div class="section-controls">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="selectAllBtn">
                                        <i class="fas fa-check-square me-1"></i>Select All
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="deselectAllBtn">
                                        <i class="fas fa-times-circle me-1"></i>Deselect All
                                    </button>
                                    <button type="button" class="btn btn-outline-info btn-sm" id="invertSelectionBtn">
                                        <i class="fas fa-exchange-alt me-1"></i>Invert Selection
                                    </button>
                                </div>
                                <div class="selected-count">
                                    Selected: <span id="selectedCount">0</span> out of <span id="totalSections">0</span> sections
                                </div>
                                <div class="section-selector">
                                    <div class="checkbox-group" id="sectionsContainer">
                                        <div class="text-center py-3">
                                            <span class="text-muted">Sections will appear here after selecting a class</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Select Subject -->
                    <div class="mb-4">
                        <h5 class="mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-book me-2"></i>Step 4: Select Subject
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="subjectSelect" class="form-label">Subject</label>
                                <select class="form-select" id="subjectSelect" required disabled>
                                    <option value="" selected class="select-placeholder">Please select a class first</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Exam Details -->
                    <div class="mb-4">
                        <h5 class="mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-calendar-alt me-2"></i>Step 5: Exam Details
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="examDate" class="form-label">Exam Date</label>
                                <input type="date" class="form-control" id="examDate" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="passingMarks" class="form-label">Passing Marks (out of 100)</label>
                                <input type="number" class="form-control" id="passingMarks" min="0" max="100" value="40" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="classroom" class="form-label">Classroom</label>
                                <select class="form-select" id="classroom" required>
                                    <option value="" selected disabled>Select classroom</option>
                                    <option value="room101">Room 101</option>
                                    <option value="room102">Room 102</option>
                                    <option value="room103">Room 103</option>
                                    <option value="room201">Room 201</option>
                                    <option value="room202">Room 202</option>
                                    <option value="auditorium">Auditorium</option>
                                    <option value="lab1">Computer Lab 1</option>
                                    <option value="lab2">Computer Lab 2</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="examTime" class="form-label">Exam Start Time</label>
                                <input type="time" class="form-control" id="examTime" required>
                            </div>
                            <!-- Exam Duration Field -->
                            <div class="col-md-6 mb-3">
                                <label for="examDuration" class="form-label">Exam Duration</label>
                                <div class="duration-input-group">
                                    <input type="number" class="form-control duration-input" id="examDuration" min="0.5" max="8" step="0.5" value="3" style="width:75px;" required>
                                    <select class="form-select duration-unit" id="durationUnit">
                                        <option value="hours">Hours</option>
                                        <option value="minutes">Minutes</option>
                                    </select>
                                </div>
                                <div class="form-text">Enter duration (e.g., 2.5 for 2 hours 30 minutes)</div>
                            </div>
                            <!-- Calculate End Time Display -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Exam End Time</label>
                                <div class="form-control bg-light" id="examEndTime" style="height: 46px; line-height: 30px;">
                                    Calculated automatically
                                </div>
                                <div class="form-text">End time will be calculated based on start time and duration</div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="button" class="btn btn-secondary me-md-2" id="resetBtn">
                            <i class="fas fa-redo me-2"></i>Reset Form
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-check-circle me-2"></i>Create Exam
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Exam Summary (Initially Hidden) -->
        <div id="examSummary" class="exam-summary" style="display: none;">
            <h3 class="summary-title"><i class="fas fa-file-alt me-2"></i>Exam Created Successfully!</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="summary-item">
                        <span class="summary-label">Category:</span>
                        <span class="summary-value" id="summaryCategory"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Department:</span>
                        <span class="summary-value" id="summaryDepartment"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Class/Course:</span>
                        <span class="summary-value" id="summaryCourse"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Section(s):</span>
                        <span class="summary-value" id="summarySections"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Subject:</span>
                        <span class="summary-value" id="summarySubject"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="summary-item">
                        <span class="summary-label">Exam Date:</span>
                        <span class="summary-value" id="summaryDate"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Passing Marks:</span>
                        <span class="summary-value" id="summaryPassing"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Classroom:</span>
                        <span class="summary-value" id="summaryClassroom"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Exam Time:</span>
                        <span class="summary-value" id="summaryTime"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Exam Duration:</span>
                        <span class="summary-value" id="summaryDuration"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Exam End Time:</span>
                        <span class="summary-value" id="summaryEndTime"></span>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-success mt-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <span id="successMessage">Exam has been successfully created and saved to the system.</span>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                <button type="button" class="btn btn-outline-primary me-md-2" id="printBtn">
                    <i class="fas fa-print me-2"></i>Print Exam Details
                </button>
                <button type="button" class="btn btn-primary" id="newExamBtn">
                    <i class="fas fa-plus-circle me-2"></i>Create Another Exam
                </button>
            </div>
        </div>
    </div>

    <footer class="container mt-5">
        <p>© 2026 Offline Exam Creation System | Designed for Educational Institutions</p>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set default date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('examDate').value = today;
            
            // Set default time to 10:00 AM
            document.getElementById('examTime').value = "10:00";
            
            // CSRF token for AJAX requests
            const csrfToken = document.querySelector('input[name="_token"]').value;
            
            // Form submission handler
            document.getElementById('examForm').addEventListener('submit', function(e) {
                e.preventDefault();
                createExam();
            });
            
            // Reset form handler
            document.getElementById('resetBtn').addEventListener('click', resetForm);
            
            // Print button handler
            document.getElementById('printBtn').addEventListener('click', function() {
                window.print();
            });
            
            // New exam button handler
            document.getElementById('newExamBtn').addEventListener('click', function() {
                document.getElementById('examSummary').style.display = 'none';
                document.getElementById('examForm').style.display = 'block';
                resetForm();
            });
            
            // Category change handler
            document.getElementById('categorySelect').addEventListener('change', function() {
                const categoryId = this.value;
                if (categoryId) {
                    loadDepartments(categoryId);
                    updateStepIndicator();
                }
            });
            
            // Department change handler
            document.getElementById('departmentSelect').addEventListener('change', function() {
                const departmentId = this.value;
                if (departmentId) {
                    loadCategoryFullData(departmentId);
                    updateStepIndicator();
                }
            });
            
            // Course/Class change handler
            document.getElementById('courseSelect').addEventListener('change', function() {
                const courseId = this.value;
                if (courseId) {
                    loadSectionsForCourse(courseId);
                    loadSubjectsForCourse(courseId);
                    updateStepIndicator();
                }
            });
            
            // Section control buttons
            document.getElementById('selectAllBtn').addEventListener('click', selectAllSections);
            document.getElementById('deselectAllBtn').addEventListener('click', deselectAllSections);
            document.getElementById('invertSelectionBtn').addEventListener('click', invertSelection);
            
            // Update step indicator when form fields are interacted with
            document.querySelectorAll('#examForm input, #examForm select').forEach(element => {
                element.addEventListener('change', function() {
                    updateStepIndicator();
                    // If time or duration changes, update end time
                    if (this.id === 'examTime' || this.id === 'examDuration' || this.id === 'durationUnit') {
                        updateEndTime();
                    }
                });
            });
            
            // Initialize step indicator
            updateStepIndicator();
            
            // Initialize end time calculation
            updateEndTime();
            
            // Initialize selected count
            updateSelectedCount();
            
            // Also update end time on input (for real-time updates)
            document.getElementById('examTime').addEventListener('input', updateEndTime);
            document.getElementById('examDuration').addEventListener('input', updateEndTime);
            document.getElementById('durationUnit').addEventListener('change', updateEndTime);
        });
        
        // Function to load departments based on category
        function loadDepartments(categoryId) {
            const departmentSelect = document.getElementById('departmentSelect');
            const loadingElement = document.getElementById('departmentLoading');
            
            // Show loading
            departmentSelect.disabled = true;
            departmentSelect.innerHTML = '<option value="" selected class="select-placeholder">Loading departments...</option>';
            loadingElement.style.display = 'block';
            
            // Fetch departments from server
            fetch(`/departments-by-category?category=${categoryId}`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Clear and populate department dropdown
                departmentSelect.innerHTML = '<option value="" selected disabled>Select a department</option>';
                
                if (data.departments && data.departments.length > 0) {
                    data.departments.forEach(department => {
                        const option = document.createElement('option');
                        option.value = department.id;
                        option.textContent = department.name;
                        departmentSelect.appendChild(option);
                    });
                    departmentSelect.disabled = false;
                } else {
                    departmentSelect.innerHTML = '<option value="" selected disabled>No departments found</option>';
                }
                
                // Hide loading
                loadingElement.style.display = 'none';
                
                // Reset dependent fields
                resetDependentFields('department');
            })
            .catch(error => {
                console.error('Error loading departments:', error);
                departmentSelect.innerHTML = '<option value="" selected disabled>Error loading departments</option>';
                loadingElement.style.display = 'none';
            });
        }
        
        // Function to load full category data (classes and sections)
        function loadCategoryFullData(departmentId) {
            const courseSelect = document.getElementById('courseSelect');
            const sectionsContainer = document.getElementById('sectionsContainer');
            
            // Show loading for courses
            courseSelect.disabled = true;
            courseSelect.innerHTML = '<option value="" selected class="select-placeholder">Loading classes...</option>';
            
            // Clear sections
            sectionsContainer.innerHTML = '<div class="text-center py-3"><span class="text-muted">Loading sections...</span></div>';
            document.getElementById('totalSections').textContent = '0';
            updateSelectedCount();
            
            // Fetch category full data from server
            fetch(`/category-full-data/${departmentId}`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Clear and populate course dropdown
                courseSelect.innerHTML = '<option value="" selected disabled>Select a class/course</option>';
                
                if (data.classes && data.classes.length > 0) {
                    data.classes.forEach(classItem => {
                        const option = document.createElement('option');
                        option.value = classItem.id;
                        option.textContent = classItem.name;
                        courseSelect.appendChild(option);
                    });
                    courseSelect.disabled = false;
                } else {
                    courseSelect.innerHTML = '<option value="" selected disabled>No classes found</option>';
                }
                
                // Reset dependent fields
                resetDependentFields('class');
            })
            .catch(error => {
                console.error('Error loading category data:', error);
                courseSelect.innerHTML = '<option value="" selected disabled>Error loading classes</option>';
                sectionsContainer.innerHTML = '<div class="text-center py-3"><span class="text-muted">Error loading sections</span></div>';
            });
        }
        
        // Function to load sections for a specific course
        function loadSectionsForCourse(courseId) {
            const sectionsContainer = document.getElementById('sectionsContainer');
            
            // Show loading for sections
            sectionsContainer.innerHTML = '<div class="text-center py-3"><span class="text-muted">Loading sections...</span></div>';
            
            // In a real implementation, you would fetch sections from the server
            // For now, we'll simulate with dummy data
            setTimeout(() => {
                // Simulated sections data
                const sections = [
                    { id: 'sectionA', name: 'Section A' },
                    { id: 'sectionB', name: 'Section B' },
                    { id: 'sectionC', name: 'Section C' },
                    { id: 'sectionD', name: 'Section D' },
                    { id: 'sectionE', name: 'Section E' }
                ];
                
                // Clear sections container
                sectionsContainer.innerHTML = '';
                
                // Create section checkboxes
                sections.forEach(section => {
                    const checkboxItem = document.createElement('div');
                    checkboxItem.className = 'checkbox-item';
                    
                    const checkboxId = `section_${section.id}`;
                    
                    checkboxItem.innerHTML = `
                        <input class="form-check-input section-checkbox" type="checkbox" 
                               id="${checkboxId}" value="${section.id}" data-name="${section.name}">
                        <label class="form-check-label ms-2" for="${checkboxId}">${section.name}</label>
                    `;
                    
                    sectionsContainer.appendChild(checkboxItem);
                });
                
                // Update total sections count
                document.getElementById('totalSections').textContent = sections.length;
                
                // Add event listeners to new checkboxes
                document.querySelectorAll('.section-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        updateCheckboxVisuals();
                        updateSelectedCount();
                        updateStepIndicator();
                    });
                });
                
                // Initialize checkbox visuals
                updateCheckboxVisuals();
                updateSelectedCount();
            }, 500);
        }
        
        // Function to load subjects for a specific course
        function loadSubjectsForCourse(courseId) {
            const subjectSelect = document.getElementById('subjectSelect');
            
            // Show loading for subjects
            subjectSelect.disabled = true;
            subjectSelect.innerHTML = '<option value="" selected class="select-placeholder">Loading subjects...</option>';
            
            // In a real implementation, you would fetch subjects from the server
            // For now, we'll simulate with dummy data
            setTimeout(() => {
                // Simulated subjects data
                const subjects = [
                    { id: 'mathematics', name: 'Mathematics' },
                    { id: 'physics', name: 'Physics' },
                    { id: 'chemistry', name: 'Chemistry' },
                    { id: 'biology', name: 'Biology' },
                    { id: 'english', name: 'English' },
                    { id: 'computer_science', name: 'Computer Science' }
                ];
                
                // Clear and populate subject dropdown
                subjectSelect.innerHTML = '<option value="" selected disabled>Select a subject</option>';
                
                subjects.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.textContent = subject.name;
                    subjectSelect.appendChild(option);
                });
                
                subjectSelect.disabled = false;
            }, 500);
        }
        
        // Function to reset dependent fields when parent field changes
        function resetDependentFields(resetFrom) {
            const subjectSelect = document.getElementById('subjectSelect');
            const sectionsContainer = document.getElementById('sectionsContainer');
            
            switch(resetFrom) {
                case 'department':
                    // Reset course, sections, and subject
                    document.getElementById('courseSelect').innerHTML = '<option value="" selected class="select-placeholder">Please select a department first</option>';
                    document.getElementById('courseSelect').disabled = true;
                    
                    sectionsContainer.innerHTML = '<div class="text-center py-3"><span class="text-muted">Sections will appear here after selecting a class</span></div>';
                    document.getElementById('totalSections').textContent = '0';
                    
                    subjectSelect.innerHTML = '<option value="" selected class="select-placeholder">Please select a class first</option>';
                    subjectSelect.disabled = true;
                    break;
                    
                case 'class':
                    // Reset sections and subject
                    sectionsContainer.innerHTML = '<div class="text-center py-3"><span class="text-muted">Sections will appear here after selecting a class</span></div>';
                    document.getElementById('totalSections').textContent = '0';
                    
                    subjectSelect.innerHTML = '<option value="" selected class="select-placeholder">Please select a class first</option>';
                    subjectSelect.disabled = true;
                    break;
            }
            
            updateSelectedCount();
            updateStepIndicator();
        }
        
        // Section control functions
        function selectAllSections() {
            document.querySelectorAll('.section-checkbox').forEach(checkbox => {
                checkbox.checked = true;
            });
            updateCheckboxVisuals();
            updateSelectedCount();
            updateStepIndicator();
        }
        
        function deselectAllSections() {
            document.querySelectorAll('.section-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            updateCheckboxVisuals();
            updateSelectedCount();
            updateStepIndicator();
        }
        
        function invertSelection() {
            document.querySelectorAll('.section-checkbox').forEach(checkbox => {
                checkbox.checked = !checkbox.checked;
            });
            updateCheckboxVisuals();
            updateSelectedCount();
            updateStepIndicator();
        }
        
        function updateCheckboxVisuals() {
            document.querySelectorAll('.checkbox-item').forEach(item => {
                const checkbox = item.querySelector('.section-checkbox');
                if (checkbox && checkbox.checked) {
                    item.classList.add('checked');
                } else {
                    item.classList.remove('checked');
                }
            });
        }
        
        function updateSelectedCount() {
            const selectedCount = document.querySelectorAll('.section-checkbox:checked').length;
            const totalCount = document.querySelectorAll('.section-checkbox').length;
            document.getElementById('selectedCount').textContent = selectedCount;
            
            // Update the text color based on selection
            const countElement = document.getElementById('selectedCount');
            if (selectedCount === 0) {
                countElement.style.color = '#dc3545'; // Red for none selected
            } else if (selectedCount === totalCount) {
                countElement.style.color = '#198754'; // Green for all selected
            } else {
                countElement.style.color = '#0d6efd'; // Blue for some selected
            }
        }
        
        function updateEndTime() {
            const startTime = document.getElementById('examTime').value;
            const duration = parseFloat(document.getElementById('examDuration').value) || 0;
            const unit = document.getElementById('durationUnit').value;
            
            if (!startTime || duration <= 0) {
                document.getElementById('examEndTime').textContent = 'Calculated automatically';
                return;
            }
            
            // Parse start time
            const [startHours, startMinutes] = startTime.split(':').map(Number);
            let totalMinutes = startHours * 60 + startMinutes;
            
            // Add duration in minutes
            if (unit === 'hours') {
                totalMinutes += duration * 60;
            } else {
                totalMinutes += duration;
            }
            
            // Calculate end time
            const endHours = Math.floor(totalMinutes / 60) % 24;
            const endMinutes = totalMinutes % 60;
            
            // Format end time
            const formattedEndTime = `${endHours.toString().padStart(2, '0')}:${endMinutes.toString().padStart(2, '0')}`;
            
            // Format for display
            const formattedDisplay = formatTimeForDisplay(formattedEndTime);
            document.getElementById('examEndTime').textContent = formattedDisplay;
        }
        
        function formatTimeForDisplay(time24) {
            const [hours, minutes] = time24.split(':').map(Number);
            const period = hours >= 12 ? 'PM' : 'AM';
            const displayHours = hours % 12 || 12;
            return `${displayHours}:${minutes.toString().padStart(2, '0')} ${period}`;
        }
        
        function updateStepIndicator() {
            const steps = document.querySelectorAll('.step');
            
            // Step 1: Check if category is selected
            const categorySelected = document.getElementById('categorySelect').value !== '';
            
            // Step 2: Check if department is selected
            const departmentSelected = document.getElementById('departmentSelect').value !== '';
            
            // Step 3: Check if class and at least one section is selected
            const courseSelected = document.getElementById('courseSelect').value !== '';
            const sectionsSelected = document.querySelectorAll('.section-checkbox:checked').length > 0;
            
            // Step 4: Check if subject is selected
            const subjectSelected = document.getElementById('subjectSelect').value !== '';
            
            // Step 5: Check if exam details are filled
            const dateFilled = document.getElementById('examDate').value !== '';
            const passingFilled = document.getElementById('passingMarks').value !== '';
            const classroomFilled = document.getElementById('classroom').value !== '';
            const timeFilled = document.getElementById('examTime').value !== '';
            const durationFilled = document.getElementById('examDuration').value !== '';
            
            // Update step indicators
            if (categorySelected) {
                steps[0].classList.add('completed');
                steps[1].classList.add('active');
            } else {
                steps[0].classList.add('active');
                steps[1].classList.remove('active', 'completed');
                steps[2].classList.remove('active', 'completed');
                steps[3].classList.remove('active', 'completed');
                steps[4].classList.remove('active', 'completed');
                return;
            }
            
            if (departmentSelected) {
                steps[1].classList.add('completed');
                steps[2].classList.add('active');
            } else {
                steps[1].classList.add('active');
                steps[2].classList.remove('active', 'completed');
                steps[3].classList.remove('active', 'completed');
                steps[4].classList.remove('active', 'completed');
                return;
            }
            
            if (courseSelected && sectionsSelected) {
                steps[2].classList.add('completed');
                steps[3].classList.add('active');
            } else {
                steps[2].classList.add('active');
                steps[3].classList.remove('active', 'completed');
                steps[4].classList.remove('active', 'completed');
                return;
            }
            
            if (subjectSelected) {
                steps[3].classList.add('completed');
                steps[4].classList.add('active');
            } else {
                steps[3].classList.add('active');
                steps[4].classList.remove('active', 'completed');
                return;
            }
            
            if (dateFilled && passingFilled && classroomFilled && timeFilled && durationFilled) {
                steps[4].classList.add('completed');
            }
        }
        
        function createExam() {
            // Validate category selection
            const categorySelect = document.getElementById('categorySelect');
            const category = categorySelect.value;
            if (!category) {
                alert('Please select a category (Academic or Non-Academic).');
                return;
            }
            
            // Validate department selection
            const departmentSelect = document.getElementById('departmentSelect');
            const department = departmentSelect.value;
            if (!department) {
                alert('Please select a department.');
                return;
            }
            
            // Validate at least one section is selected
            const sectionCheckboxes = document.querySelectorAll('.section-checkbox:checked');
            if (sectionCheckboxes.length === 0) {
                alert('Please select at least one section.');
                return;
            }
            
            // Validate duration
            const duration = parseFloat(document.getElementById('examDuration').value);
            if (duration <= 0) {
                alert('Please enter a valid exam duration.');
                return;
            }
            
            // Get form values
            const categoryText = categorySelect.options[categorySelect.selectedIndex].text;
            const departmentText = departmentSelect.options[departmentSelect.selectedIndex].text;
            
            const courseSelect = document.getElementById('courseSelect');
            const course = courseSelect.value;
            const courseText = courseSelect.options[courseSelect.selectedIndex].text;
            
            // Get selected sections
            const selectedSections = Array.from(sectionCheckboxes).map(cb => ({
                id: cb.value,
                name: cb.getAttribute('data-name')
            }));
            
            const subjectSelect = document.getElementById('subjectSelect');
            const subject = subjectSelect.value;
            const subjectText = subjectSelect.options[subjectSelect.selectedIndex].text;
            
            const examDate = document.getElementById('examDate').value;
            const passingMarks = document.getElementById('passingMarks').value;
            const classroom = document.getElementById('classroom').value;
            const classroomText = document.getElementById('classroom').options[document.getElementById('classroom').selectedIndex].text;
            const examTime = document.getElementById('examTime').value;
            const examDuration = document.getElementById('examDuration').value;
            const durationUnit = document.getElementById('durationUnit').value;
            
            // Calculate end time
            const [startHours, startMinutes] = examTime.split(':').map(Number);
            let totalMinutes = startHours * 60 + startMinutes;
            if (durationUnit === 'hours') {
                totalMinutes += examDuration * 60;
            } else {
                totalMinutes += parseFloat(examDuration);
            }
            const endHours = Math.floor(totalMinutes / 60) % 24;
            const endMinutes = totalMinutes % 60;
            const endTime = `${endHours.toString().padStart(2, '0')}:${endMinutes.toString().padStart(2, '0')}`;
            
            // Format date
            const formattedDate = new Date(examDate).toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            // Format times
            const formattedStartTime = formatTimeForDisplay(examTime);
            const formattedEndTime = formatTimeForDisplay(endTime);
            
            // Format duration
            let formattedDuration;
            if (durationUnit === 'hours') {
                const hours = Math.floor(examDuration);
                const minutes = (examDuration % 1) * 60;
                if (minutes === 0) {
                    formattedDuration = `${hours} hour${hours !== 1 ? 's' : ''}`;
                } else {
                    formattedDuration = `${hours} hour${hours !== 1 ? 's' : ''} ${minutes} minute${minutes !== 1 ? 's' : ''}`;
                }
            } else {
                formattedDuration = `${examDuration} minute${examDuration != 1 ? 's' : ''}`;
            }
            
            // Update summary
            document.getElementById('summaryCategory').textContent = categoryText;
            document.getElementById('summaryDepartment').textContent = departmentText;
            document.getElementById('summaryCourse').textContent = courseText;
            document.getElementById('summarySections').textContent = selectedSections.map(s => s.name).join(', ');
            document.getElementById('summarySubject').textContent = subjectText;
            document.getElementById('summaryDate').textContent = formattedDate;
            document.getElementById('summaryPassing').textContent = `${passingMarks}/100`;
            document.getElementById('summaryClassroom').textContent = classroomText;
            document.getElementById('summaryTime').textContent = `${formattedStartTime}`;
            document.getElementById('summaryDuration').textContent = formattedDuration;
            document.getElementById('summaryEndTime').textContent = `${formattedEndTime}`;
            
            // Update success message
            const successMessage = document.getElementById('successMessage');
            successMessage.textContent = `${categoryText} exam for ${subjectText} in ${departmentText} has been successfully scheduled for ${formattedDate} from ${formattedStartTime} to ${formattedEndTime} (Duration: ${formattedDuration}) in ${classroomText}.`;
            
            // Show summary and hide form
            document.getElementById('examForm').style.display = 'none';
            document.getElementById('examSummary').style.display = 'block';
            
            // Scroll to summary
            document.getElementById('examSummary').scrollIntoView({ behavior: 'smooth' });
            
            // Prepare data for server submission
            const examData = {
                category_id: category,
                department_id: department,
                course_id: course,
                sections: selectedSections.map(s => s.id),
                subject_id: subject,
                exam_date: examDate,
                passing_marks: passingMarks,
                classroom: classroom,
                exam_time: examTime,
                exam_duration: examDuration,
                duration_unit: durationUnit,
                end_time: endTime,
                _token: csrfToken
            };
            
            // Log exam data
            console.log('Exam created with details:', examData);
            
            // In a real implementation, you would send this data to the server
            /*
            fetch('/save-exam', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(examData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Exam saved successfully:', data);
            })
            .catch(error => {
                console.error('Error saving exam:', error);
            });
            */
        }
        
        function resetForm() {
            if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
                // Reset the form
                document.getElementById('examForm').reset();
                
                // Reset date to today
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('examDate').value = today;
                
                // Reset time to 10:00
                document.getElementById('examTime').value = "10:00";
                
                // Reset passing marks
                document.getElementById('passingMarks').value = "40";
                
                // Reset duration
                document.getElementById('examDuration').value = "3";
                document.getElementById('durationUnit').value = "hours";
                
                // Reset category dropdown
                document.getElementById('categorySelect').selectedIndex = 0;
                
                // Reset department dropdown
                document.getElementById('departmentSelect').innerHTML = '<option value="" selected class="select-placeholder">Please select a category first</option>';
                document.getElementById('departmentSelect').disabled = true;
                
                // Reset course dropdown
                document.getElementById('courseSelect').innerHTML = '<option value="" selected class="select-placeholder">Please select a department first</option>';
                document.getElementById('courseSelect').disabled = true;
                
                // Reset sections
                const sectionsContainer = document.getElementById('sectionsContainer');
                sectionsContainer.innerHTML = '<div class="text-center py-3"><span class="text-muted">Sections will appear here after selecting a class</span></div>';
                document.getElementById('totalSections').textContent = '0';
                
                // Reset subject dropdown
                document.getElementById('subjectSelect').innerHTML = '<option value="" selected class="select-placeholder">Please select a class first</option>';
                document.getElementById('subjectSelect').disabled = true;
                
                // Reset loading indicators
                document.getElementById('departmentLoading').style.display = 'none';
                
                // Update end time display
                updateEndTime();
                
                // Update selected count
                updateSelectedCount();
                
                // Reset step indicator
                const steps = document.querySelectorAll('.step');
                steps.forEach((step, index) => {
                    if (index === 0) {
                        step.classList.add('active');
                    } else {
                        step.classList.remove('active', 'completed');
                    }
                });
                
                // If summary is showing, hide it and show form
                if (document.getElementById('examSummary').style.display === 'block') {
                    document.getElementById('examSummary').style.display = 'none';
                    document.getElementById('examForm').style.display = 'block';
                }
            }
        }
    </script>
</body>
</html>
@endsection
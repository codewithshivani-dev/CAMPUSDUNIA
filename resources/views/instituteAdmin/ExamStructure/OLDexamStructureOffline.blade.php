@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
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
        
        .classroom-input-group {
            display: flex;
            gap: 10px;
        }
        
        .classroom-input-group .btn {
            white-space: nowrap;
        }
        
        .modal-header {
            background: var(--gradient-primary);
            color: white;
        }
        
        .semester-container {
            display: none;
            margin-top: 15px;
            padding: 15px;
            background-color: #f8f9ff;
            border-radius: 8px;
            border: 1px solid #e0e0ff;
        }
        
        .semester-info {
            font-size: 0.9rem;
            color: #666;
            margin-top: 5px;
            display: none;
        }

        /* New Styles for Multiple Subjects */
        .subject-checkbox-container {
            border: 1px solid #ebebeb;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all 0.2s;
        }

        .subject-checkbox-container:hover {
            background-color: #eef2ff;
            transform: translateX(5px);
        }

        .subject-checkbox-container .form-check-label {
            font-weight: 500;
            cursor: pointer;
        }

        .subject-exam-section {
            border-left: 4px solid var(--primary-color);
        }

        .subject-exam-section .card-header {
            background: linear-gradient(135deg, #f8f9ff, #eef2ff);
            color: var(--dark-color);
        }

        .section-selector-container {
            padding: 6px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .section-checkbox {
            margin-right: 8px;
        }

        .generate-code-btn:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .apply-all-checkbox:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Custom scrollbar for section selector */
        .section-selector-container::-webkit-scrollbar {
            width: 6px;
        }

        .section-selector-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .section-selector-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        .section-selector-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Full width subject grid */
        /* .subject-grid-container {
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            background: #f8f9ff;
        } */

        .subject-row {
            display: flex;
            /* flex-wrap: wrap; */
            /* margin: -5px; */
        }

        .subject-col {
            flex: 0 0 33.333%;
            max-width: 33.333%;
            padding: 5px;
        }

        .action-buttons {
            display: none !important;
            transition: opacity 0.3s ease;
        }

        .action-buttons.show {
            display: flex !important;
            opacity: 1;
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
            
            .classroom-input-group {
                flex-direction: column;
            }
            
            .subject-checkbox-container {
                padding: 8px 12px;
            }

            .subject-col {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>

    <div class="container">
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
                    <div class="">
                        <h5 class="mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-layer-group me-2"></i>Select Category & Department
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="categorySelect" class="form-label"> Category</label>
                                <select class="form-select" id="categorySelect" required>
                                    <option value="" selected disabled>Select a category</option>
                                    @if(isset($categories) && count($categories) > 0)
                                        @foreach($categories as $category)
                                            <option value="{{ $category->department_category_id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="departmentSelect" class="form-label">Department</label>
                                <select class="form-select" id="departmentSelect" required disabled>
                                    <option value="" selected class="select-placeholder">Please select a category first</option>
                                </select>
                                <div class="form-text" id="departmentLoading" style="display: none;">
                                    <span class="loading-spinner"></span> Loading departments...
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Select Course -->
                    <div class="">
                        <h5 class="mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-graduation-cap me-2"></i>Select Course/Branch
                        </h5>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="courseSelect" class="form-label">Course</label>
                                <select class="form-select" id="courseSelect" required disabled>
                                    <option value="" selected class="select-placeholder">Please select a department first</option>
                                </select>
                                <div class="form-text" id="courseLoading" style="display: none;">
                                    <span class="loading-spinner"></span> Loading courses...
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="branchSelect" class="form-label">Branch / Class</label>
                                <select class="form-select" id="branchSelect" required disabled>
                                    <option value="" selected class="select-placeholder">Please select a course first</option>
                                </select>
                                <div class="form-text" id="branchLoading" style="display: none;">
                                    <span class="loading-spinner"></span> Loading branches...
                                </div>
                                <div class="form-text" id="singleBranchMessage" style="display: none; color: var(--success-color);">
                                    <i class="fas fa-check-circle"></i> Only one branch available
                                </div>
                            </div>

                            <!-- Sections Selection -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Select Sections</label>
                                <div class="section-selector-container" id="sectionsContainer">
                                    <div class="form-text">Please select a branch first</div>
                                </div>
                                <div id="sectionsLoading" style="display: none;" class="form-text">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Semester and Subjects -->
                    <div class="">
                        <h5 class="mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-book me-2"></i>Select Semester & Subject
                        </h5>
                        <div class="row">
                            <!-- Semester Selection -->
                            <div class="col-md-6 mb-3">
                                <div id="semesterContainer" class="semester-container">
                                    <label for="semesterSelect" class="form-label">Select Semester</label>
                                    <select class="form-select" id="semesterSelect">
                                        <option value="">-- Select Semester --</option>
                                    </select>
                                    <div class="semester-info" id="semesterInfo">
                                        <!-- Dynamic semester info will appear here -->
                                    </div>
                                </div>
                                
                                <!-- Original subject dropdown -->
                                <div id="subjectDropdownContainer">
                                    <label for="subjectSelect" class="form-label">Subject</label>
                                    <select class="form-select" id="subjectSelect" required disabled>
                                        <option value="" selected class="select-placeholder">Please select a branch first</option>
                                    </select>
                                </div>
                                
                                <!-- Container for multiple subjects checkboxes -->
                                <div id="multipleSubjectsContainer" style="display: none;"></div>
                                
                                <div class="form-text" id="subjectLoading" style="display: none;">
                                    <span class="loading-spinner"></span> Loading subjects...
                                </div>
                                <div class="form-text" id="subjectInfo" style="display: none;">
                                    <!-- Subject distribution info will appear here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3.5: Common Exam Name -->
                    <div id="commonExamNameSection" style="display: none;">
                        <h5 class="mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-file-signature me-2"></i>Exam Name
                        </h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="commonExamName" class="form-label">Exam Name (Common for all selected subjects)</label>
                                <input type="text" class="form-control" id="commonExamName" placeholder="e.g., Mid Term Examination, Final Exam" value="Mid Term Examination" required>
                                <!-- <div class="form-text">This exam name will be applied to all selected subjects.</div> -->
                            </div>
                            <div class="col-md-4">
                                <label for="gradeSystemSelect" class="form-label">Grade System</label>
                                <select class="form-select" id="gradeSystemSelect" name="grade_system_id">
                                    @foreach($gradeSystems as $system)
                                        <option value="{{ $system->id }}">{{ $system->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Optional: Select a custom grade system for this exam</div>
                            </div>
                            <div class="col-md-4 mb-3 text-end" style="place-content: end !important;">
                                <button type="button" class="btn btn-primary" id="configureExamDetailsBtn">
                                    <i class="fas fa-cog me-2"></i>Configure Exam Details
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Exam Details for Multiple Subjects -->
                    <div id="examSectionsContainer" style="display: none;">
                        <!-- Exam sections for each subject will be dynamically inserted here -->
                    </div>

                    <!-- Original Step 4: Exam Schedule (Single Subject) -->
                    <div class="mb-4" id="singleExamDetails" style="display: none;">
                        <h5 class="mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-calendar-alt me-2"></i>Exam Schedule
                        </h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="classroom" class="form-label">Classroom</label>
                                <div class="classroom-input-group">
                                    <select class="form-select" id="classroomSelect" required>
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
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="examDate" class="form-label">Exam Date</label>
                                <input type="date" class="form-control" id="examDate" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="examTime" class="form-label">Exam Start Time</label>
                                <input type="time" class="form-control" id="examTime" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="examDuration" class="form-label">Exam Duration (minutes)</label>
                                <input type="number" class="form-control" id="examDuration" min="15" max="480" value="180" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Exam End Time</label>
                                <div class="form-control bg-light" id="examEndTime">
                                    Calculated automatically
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="totalMarks" class="form-label">Total Marks</label>
                                <input type="number" class="form-control" id="totalMarks" min="1" max="1000" value="100" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="passingMarks" class="form-label">Passing Marks</label>
                                <input type="number" class="form-control" id="passingMarks" min="0" max="100" value="40" required>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons (Initially Hidden) -->
                    <div class="action-buttons d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="button" class="btn btn-secondary me-md-2" id="resetBtn">
                            <i class="fas fa-redo me-2"></i>Reset Form
                        </button>
                        <button type="button" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-check-circle me-2"></i>Create Exam(s)
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
                        <span class="summary-label">Course:</span>
                        <span class="summary-value" id="summaryCourse"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Branch:</span>
                        <span class="summary-value" id="summaryBranch"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Subject(s):</span>
                        <span class="summary-value" id="summarySubject"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="summary-item">
                        <span class="summary-label">Total Exams Created:</span>
                        <span class="summary-value" id="summaryExamCount"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Exam Name:</span>
                        <span class="summary-value" id="summaryExamName"></span>
                    </div>
                </div>
            </div>
            
            <!-- Summary Table -->
            <div id="summaryContent" class="mt-4"></div>
            
            <div class="alert alert-success mt-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <span id="successMessage">Exams have been successfully created and saved to the system.</span>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the application
            initializeApp();
        });

        // Main application initialization
        function initializeApp() {
            // Set default date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('examDate').value = today;
            
            // Set default time to 10:00 AM
            document.getElementById('examTime').value = "10:00";
            
            // CSRF token for AJAX requests
            const csrfToken = document.querySelector('input[name="_token"]').value;
            window.csrfToken = csrfToken;
            
            // Initialize event listeners
            setupEventListeners();
            
            // Initialize end time calculation
            updateEndTime();
        }

        // Setup all event listeners
        function setupEventListeners() {
            // Form submission handler
            document.getElementById('submitBtn').addEventListener('click', createExam);
            
            // Reset form handler
            document.getElementById('resetBtn').addEventListener('click', resetForm);
            
            // Print button handler
            document.getElementById('printBtn')?.addEventListener('click', function() {
                window.print();
            });
            
            // New exam button handler
            document.getElementById('newExamBtn')?.addEventListener('click', function() {
                document.getElementById('examSummary').style.display = 'none';
                document.getElementById('examForm').style.display = 'block';
                resetForm();
            });
            
            // Configure Exam Details button handler
            document.getElementById('configureExamDetailsBtn').addEventListener('click', function() {
                createExamSectionsForSubjects();
            });
            
            // Form field change handlers with session storage saving
            document.getElementById('categorySelect').addEventListener('change', function() {
                const categoryId = this.value;
                if (categoryId) {
                    loadDepartments(categoryId);
                }
                
            });
            
            document.getElementById('departmentSelect').addEventListener('change', function() {
                const departmentId = this.value;
                if (departmentId) {
                    loadCoursesByDepartment(departmentId);
                }
                
            });
            
            document.getElementById('courseSelect').addEventListener('change', function() {
                const courseType = this.value;
                const departmentId = document.getElementById('departmentSelect').value;
                if (courseType && departmentId) {
                    loadBranchesByCourse(departmentId, courseType);
                }
                
            });
            
            document.getElementById('branchSelect').addEventListener('change', function() {
                const productId = this.value;
                if (productId) {
                    fetchSections(productId);
                    checkSubjectDistribution(productId);
                }
                
            });
            
            document.getElementById('semesterSelect').addEventListener('change', function() {
                const semesterId = this.value;
                const productId = document.getElementById('branchSelect').value;
                if (semesterId && productId) {
                    loadSubjectsBySemester(productId, semesterId);
                }
                
            });
            
            document.getElementById('subjectSelect').addEventListener('change', function() {
                if (this.value) {
                    showCommonExamNameSection();
                }
                
            });
            
            // Update end time when time or duration changes
            document.getElementById('examTime').addEventListener('change', updateEndTime);
            document.getElementById('examTime').addEventListener('input', updateEndTime);
            document.getElementById('examDuration').addEventListener('change', updateEndTime);
            document.getElementById('examDuration').addEventListener('input', updateEndTime);
        }

        function restoreFormData(formData) {
            // Restore department if available
            if (formData.department && document.getElementById('departmentSelect').value !== formData.department) {
                document.getElementById('departmentSelect').value = formData.department;
                document.getElementById('departmentSelect').dispatchEvent(new Event('change'));
                
                setTimeout(() => {
                    restoreCourseData(formData);
                }, 500);
            } else {
                restoreCourseData(formData);
            }
        }

        function restoreCourseData(formData) {
            // Restore course if available
            if (formData.course && document.getElementById('courseSelect').value !== formData.course) {
                document.getElementById('courseSelect').value = formData.course;
                document.getElementById('courseSelect').dispatchEvent(new Event('change'));
                
                setTimeout(() => {
                    restoreBranchData(formData);
                }, 500);
            } else {
                restoreBranchData(formData);
            }
        }

        function restoreBranchData(formData) {
            // Restore branch if available
            if (formData.branch && document.getElementById('branchSelect').value !== formData.branch) {
                document.getElementById('branchSelect').value = formData.branch;
                document.getElementById('branchSelect').dispatchEvent(new Event('change'));
                
                setTimeout(() => {
                    restoreRemainingData(formData);
                }, 1000);
            } else {
                restoreRemainingData(formData);
            }
        }

        function restoreRemainingData(formData) {
            // Restore remaining fields
            if (formData.semester) document.getElementById('semesterSelect').value = formData.semester;
            if (formData.subject) document.getElementById('subjectSelect').value = formData.subject;
            if (formData.commonExamName) document.getElementById('commonExamName').value = formData.commonExamName;
            if (formData.examDate) document.getElementById('examDate').value = formData.examDate;
            if (formData.examTime) document.getElementById('examTime').value = formData.examTime;
            if (formData.examDuration) document.getElementById('examDuration').value = formData.examDuration;
            if (formData.classroom) document.getElementById('classroomSelect').value = formData.classroom;
            if (formData.totalMarks) document.getElementById('totalMarks').value = formData.totalMarks;
            if (formData.passingMarks) document.getElementById('passingMarks').value = formData.passingMarks;
            
            // Update end time
            updateEndTime();
            
            // Show common exam name section if subject is selected
            if (formData.subject || (formData.selectedSubjects && formData.selectedSubjects.length > 0)) {
                showCommonExamNameSection();
            }
        }

        // Function to show common exam name section
        function showCommonExamNameSection() {
            const commonExamNameSection = document.getElementById('commonExamNameSection');
            commonExamNameSection.style.display = 'block';
            
            // Hide single exam details initially
            document.getElementById('singleExamDetails').style.display = 'none';
            document.getElementById('examSectionsContainer').style.display = 'none';
            
            // Scroll to common exam name section
            commonExamNameSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        // Function to load departments based on category
        function loadDepartments(categoryId) {
            const departmentSelect = document.getElementById('departmentSelect');
            const loadingElement = document.getElementById('departmentLoading');
            
            // Show loading
            departmentSelect.disabled = true;
            departmentSelect.innerHTML = '<option value="" selected class="select-placeholder">Loading departments...</option>';
            loadingElement.style.display = 'block';
            
            // Fetch departments from server
            fetch(`/ajax/departments-by-category?category_id=${categoryId}`, {
                headers: {
                    'X-CSRF-TOKEN': window.csrfToken,
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
                
                if (data.success && data.departments) {
                    data.departments.forEach(department => {
                        const option = document.createElement('option');
                        option.value = department.department_id;
                        option.textContent = department.department;
                        if (department.shift_name) {
                            option.textContent += ` (${department.shift_name})`;
                        }
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
        
        // Function to load courses by department
        function loadCoursesByDepartment(departmentId) {
            const courseSelect = document.getElementById('courseSelect');
            const loadingElement = document.getElementById('courseLoading');
            
            // Show loading
            courseSelect.disabled = true;
            courseSelect.innerHTML = '<option value="" selected class="select-placeholder">Loading courses...</option>';
            loadingElement.style.display = 'block';
            
            // Fetch courses from server
            fetch(`/ajax/course-types-by-department?department_id=${departmentId}`, {
                headers: {
                    'X-CSRF-TOKEN': window.csrfToken,
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
                courseSelect.innerHTML = '<option value="" selected disabled>Select a course</option>';
                
                if (data.status === 'success' && data.courses) {
                    data.courses.forEach(course => {
                        const option = document.createElement('option');
                        option.value = course.finacp_merchant_sub_category_type;
                        option.textContent = course.finacp_merchant_sub_category_type;
                        option.setAttribute('data-id', course.finacp_merchant_sub_category_id);
                        courseSelect.appendChild(option);
                    });
                    courseSelect.disabled = false;
                } else {
                    courseSelect.innerHTML = '<option value="" selected disabled>No courses found</option>';
                }
                
                // Hide loading
                loadingElement.style.display = 'none';
                
                // Reset dependent fields
                resetDependentFields('course');
            })
            .catch(error => {
                console.error('Error loading courses:', error);
                courseSelect.innerHTML = '<option value="" selected disabled>Error loading courses</option>';
                loadingElement.style.display = 'none';
            });
        }
        
        // Function to load branches by course
        function loadBranchesByCourse(departmentId, courseType) {
            const branchSelect = document.getElementById('branchSelect');
            const loadingElement = document.getElementById('branchLoading');
            const singleBranchMessage = document.getElementById('singleBranchMessage');
            
            // Show loading
            branchSelect.disabled = true;
            branchSelect.innerHTML = '<option value="" selected class="select-placeholder">Loading branches...</option>';
            loadingElement.style.display = 'block';
            singleBranchMessage.style.display = 'none';
            
            // Fetch branches from server
            fetch(`/ajax/get-branches-by-course?department_id=${departmentId}&course_type=${encodeURIComponent(courseType)}`, {
                headers: {
                    'X-CSRF-TOKEN': window.csrfToken,
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
                // Clear and populate branch dropdown
                branchSelect.innerHTML = '<option value="" selected disabled>Select a branch/class</option>';
                
                if (data.status === 'success' && data.branches) {
                    data.branches.forEach(branch => {
                        const option = document.createElement('option');
                        option.value = branch.product_id;
                        option.textContent = branch.sub_type;
                        option.setAttribute('data-name', branch.sub_type);
                        branchSelect.appendChild(option);
                    });
                    branchSelect.disabled = false;
                    
                    // If only one branch, auto-select it and show message
                    if (data.branches.length === 1) {
                        branchSelect.value = data.branches[0].product_id;
                        singleBranchMessage.style.display = 'block';
                        // Trigger change event to load subjects and sections
                        branchSelect.dispatchEvent(new Event('change'));
                    }
                } else {
                    branchSelect.innerHTML = '<option value="" selected disabled>No branches found</option>';
                }
                
                // Hide loading
                loadingElement.style.display = 'none';
                
                // Reset dependent fields
                resetDependentFields('branch');
            })
            .catch(error => {
                console.error('Error loading branches:', error);
                branchSelect.innerHTML = '<option value="" selected disabled>Error loading branches</option>';
                loadingElement.style.display = 'none';
            });
        }
        
        // Function to fetch sections based on branch/class id
        async function fetchSections(branchId) {
            const sectionsContainer = document.getElementById('sectionsContainer');
            const sectionsLoading = document.getElementById('sectionsLoading');
            
            // Show loading
            sectionsContainer.innerHTML = '';
            sectionsLoading.style.display = 'block';
            
            try {
                const response = await fetch(`/ajax/get-sections-by-branch?branch_id=${branchId}`, {
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken,
                        'Accept': 'application/json'
                    }
                });
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                
                // Hide loading
                sectionsLoading.style.display = 'none';
                
                if (data.success === true && Array.isArray(data.sections)) {
                    populateSections(data.sections);
                } else {
                    sectionsContainer.innerHTML = '<div class="alert alert-warning">No sections found for this branch</div>';
                }
            } catch (error) {
                console.error('Error fetching sections:', error);
                sectionsLoading.style.display = 'none';
                sectionsContainer.innerHTML = '<div class="alert alert-danger">Error loading sections</div>';
            }
        }
        
        // Function to populate sections - MODIFIED to auto-check "Select All"
        function populateSections(sections) {
            const container = document.getElementById('sectionsContainer');
            
            if (!sections || sections.length === 0) {
                container.innerHTML = '<div class="alert alert-warning">No sections found</div>';
                return;
            }
            
            // Add "Select All" checkbox - auto-checked by default
            const selectAllDiv = document.createElement('div');
            selectAllDiv.className = 'mb-2';
            selectAllDiv.innerHTML = `
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAllSections" checked>
                    <label class="form-check-label" for="selectAllSections">
                        <strong>Select All</strong>
                    </label>
                </div>
            `;
            container.appendChild(selectAllDiv);
            
            // Create sections grid
            const row = document.createElement('div');
            row.className = 'row';
            
            sections.forEach(section => {
                const col = document.createElement('div');
                col.className = 'col-md-6 col-lg-4 mb-2';
                
                col.innerHTML = `
                    <div class="form-check">
                        <input class="form-check-input section-checkbox"
                            type="checkbox"
                            value="${section.section_id}"
                            id="section_${section.section_id}"
                            data-name="${section.section_name}"
                            checked>
                        <label class="form-check-label" for="section_${section.section_id}">
                            ${section.section_name}
                        </label>
                    </div>
                `;
                
                row.appendChild(col);
            });
            
            container.appendChild(row);
            
            // Select All logic
            document.getElementById('selectAllSections').addEventListener('change', function() {
                container.querySelectorAll('.section-checkbox')
                    .forEach(cb => cb.checked = this.checked);
            });
        }
        
        // Function to check subject distribution for a branch
        function checkSubjectDistribution(productId) {
            const subjectSelect = document.getElementById('subjectSelect');
            const subjectLoading = document.getElementById('subjectLoading');
            const subjectInfo = document.getElementById('subjectInfo');
            const semesterContainer = document.getElementById('semesterContainer');
            const semesterSelect = document.getElementById('semesterSelect');
            const semesterInfo = document.getElementById('semesterInfo');
            const subjectDropdownContainer = document.getElementById('subjectDropdownContainer');
            const multipleSubjectsContainer = document.getElementById('multipleSubjectsContainer');
            const commonExamNameSection = document.getElementById('commonExamNameSection');
            
            // Reset
            subjectSelect.disabled = true;
            subjectSelect.innerHTML = '<option value="" selected class="select-placeholder">Loading subjects...</option>';
            subjectDropdownContainer.style.display = 'block';
            multipleSubjectsContainer.style.display = 'none';
            subjectLoading.style.display = 'block';
            subjectInfo.style.display = 'none';
            semesterContainer.style.display = 'none';
            semesterSelect.innerHTML = '<option value="">-- Select Semester --</option>';
            semesterInfo.style.display = 'none';
            commonExamNameSection.style.display = 'none';
            
            // Hide exam sections container and single exam details
            document.getElementById('examSectionsContainer').style.display = 'none';
            document.getElementById('singleExamDetails').style.display = 'none';
            
            // Step 1: Load available semesters
            fetch(`/ajax/get-semesters/${productId}`, {
                headers: {
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(semesterData => {
                let availableSemesters = [];
                if (semesterData.success && semesterData.semesters && semesterData.semesters.length > 0) {
                    availableSemesters = semesterData.semesters;
                }
                
                // Step 2: Load subjects for this branch
                fetch(`/ajax/get-subjects-by-course?course_detail_id=${productId}`, {
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(subjectData => {
                    subjectLoading.style.display = 'none';
                    
                    if (subjectData.status === 'success' && subjectData.subjects && subjectData.subjects.length > 0) {
                        const subjects = subjectData.subjects;
                        
                        // Analyze subject distribution
                        const allSubjectsAllSemesters = subjects.every(subject => 
                            subject.semester_id === 'all_semesters' || !subject.semester_id);
                        const allSubjectsSpecificSemesters = subjects.every(subject => 
                            subject.semester_id && subject.semester_id !== 'all_semesters');
                        const hasMixedDistribution = subjects.some(subject => 
                            subject.semester_id === 'all_semesters') && 
                            subjects.some(subject => subject.semester_id && subject.semester_id !== 'all_semesters');
                        
                        if (allSubjectsAllSemesters || availableSemesters.length === 0) {
                            // Case 1: All subjects are for all semesters OR no semesters defined
                            // Show all subjects directly
                            handleSubjectsDisplay(subjects);
                            semesterContainer.style.display = 'none';
                            subjectInfo.style.display = 'block';
                            // subjectInfo.innerHTML = `<i class="fas fa-info-circle"></i> ${subjects.length} subject(s) available`;
                        } 
                        else if (allSubjectsSpecificSemesters && availableSemesters.length > 0) {
                            // Case 2: All subjects have specific semesters
                            // Show semester selector first
                            semesterContainer.style.display = 'block';
                            populateSemesterSelect(availableSemesters);
                            semesterInfo.style.display = 'block';
                            semesterInfo.innerHTML = `<i class="fas fa-info-circle"></i> Please select a semester to view subjects`;
                            
                            // Store subjects globally to filter later
                            window.allSubjects = subjects;
                            subjectSelect.innerHTML = '<option value="">Select semester first</option>';
                        }
                        else if (hasMixedDistribution && availableSemesters.length > 0) {
                            // Case 3: Mixed distribution
                            semesterContainer.style.display = 'block';
                            populateSemesterSelect(availableSemesters);
                            
                            // Add "All Semesters" option
                            const allOption = document.createElement('option');
                            allOption.value = 'all_semesters';
                            allOption.textContent = 'All Semesters';
                            semesterSelect.appendChild(allOption);
                            
                            semesterInfo.style.display = 'block';
                            semesterInfo.innerHTML = `<i class="fas fa-info-circle"></i> Mixed subject types. Select "All Semesters" or specific semester`;
                            
                            // Store subjects globally
                            window.allSubjects = subjects;
                            subjectSelect.innerHTML = '<option value="">Select semester first</option>';
                        }
                        else {
                            // Default case
                            handleSubjectsDisplay(subjects);
                            subjectInfo.style.display = 'block';
                            subjectInfo.innerHTML = `<i class="fas fa-info-circle"></i> ${subjects.length} subject(s) available`;
                        }
                    } else {
                        subjectSelect.innerHTML = '<option value="" selected disabled>No subjects found for this branch</option>';
                        subjectInfo.style.display = 'block';
                        subjectInfo.innerHTML = `<i class="fas fa-exclamation-triangle"></i> No subjects configured for this branch`;
                    }
                })
                .catch(error => {
                    console.error('Error loading subjects:', error);
                    subjectSelect.innerHTML = '<option value="" selected disabled>Error loading subjects</option>';
                    subjectLoading.style.display = 'none';
                });
            })
            .catch(error => {
                console.error('Error loading semesters:', error);
                // Try to load subjects directly if semester fetch fails
                loadSubjectsDirectly(productId);
            });
        }
        
        // Function to load subjects directly (fallback)
        function loadSubjectsDirectly(productId) {
            fetch(`/ajax/get-subjects-by-course?course_detail_id=${productId}`, {
                headers: {
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.subjects) {
                    handleSubjectsDisplay(data.subjects);
                } else {
                    document.getElementById('subjectSelect').innerHTML = '<option value="" selected disabled>No subjects found</option>';
                }
                document.getElementById('subjectLoading').style.display = 'none';
            })
            .catch(error => {
                console.error('Error loading subjects directly:', error);
                document.getElementById('subjectSelect').innerHTML = '<option value="" selected disabled>Error loading subjects</option>';
                document.getElementById('subjectLoading').style.display = 'none';
            });
        }
        
        // Function to handle subjects display (single or multiple)
        function handleSubjectsDisplay(subjects) {
            if (subjects.length > 1) {
                // For multiple subjects, show checkbox interface
                showMultipleSubjects(subjects);
            } else {
                // For single subject, show dropdown
                showSingleSubject(subjects);
            }
        }
        
        // Function to show single subject dropdown
        function showSingleSubject(subjects) {
            const subjectSelect = document.getElementById('subjectSelect');
            const subjectDropdownContainer = document.getElementById('subjectDropdownContainer');
            const multipleSubjectsContainer = document.getElementById('multipleSubjectsContainer');
            
            subjectSelect.innerHTML = '<option value="" selected disabled>Select a subject</option>';
            
            if (subjects && subjects.length > 0) {
                subjects.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.subject_id;
                    option.textContent = subject.subject_name;
                    if (subject.semester_id && subject.semester_id !== 'all_semesters') {
                        option.textContent += ` (Semester ${subject.semester_id})`;
                    }
                    option.setAttribute('data-name', subject.subject_name);
                    subjectSelect.appendChild(option);
                });
                subjectSelect.disabled = false;
            } else {
                subjectSelect.innerHTML = '<option value="" selected disabled>No subjects available</option>';
            }
            
            // Show single subject interface
            subjectDropdownContainer.style.display = 'block';
            multipleSubjectsContainer.style.display = 'none';
            document.getElementById('examSectionsContainer').style.display = 'none';
            document.getElementById('singleExamDetails').style.display = 'none';
        }
        
        // Function to show multiple subjects checkboxes - MODIFIED for col-md-4 layout
        function showMultipleSubjects(subjects) {
            const subjectDropdownContainer = document.getElementById('subjectDropdownContainer');
            const multipleSubjectsContainer = document.getElementById('multipleSubjectsContainer');
            const commonExamNameSection = document.getElementById('commonExamNameSection');
            
            // Hide single subject dropdown
            subjectDropdownContainer.style.display = 'none';
            multipleSubjectsContainer.style.display = 'block';
            
            // Clear previous content
            multipleSubjectsContainer.innerHTML = '';
            
            // Add header
            const header = document.createElement('h6');
            header.className = 'mb-3';
            header.innerHTML = '<i class="fas fa-tasks me-2"></i>Select Subjects';
            multipleSubjectsContainer.appendChild(header);
            
            // Create select all checkbox
            const selectAllDiv = document.createElement('div');
            selectAllDiv.className = 'mb-3';
            selectAllDiv.innerHTML = `
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAllSubjects">
                    <label class="form-check-label" for="selectAllSubjects">
                        <strong>Select All</strong>
                    </label>
                </div>
            `;
            multipleSubjectsContainer.appendChild(selectAllDiv);
            
            // Create container for full-width grid
            const gridContainer = document.createElement('div');
            gridContainer.className = 'subject-grid-container';
            
            // Create subjects grid with row layout
            const grid = document.createElement('div');
            grid.className = 'subject-row';
            
            subjects.forEach((subject, index) => {
                const col = document.createElement('div');
                col.className = 'subject-col';
                
                col.innerHTML = `
                    <div class="form-check subject-checkbox-container">
                        <input class="form-check-input subject-checkbox" type="checkbox" 
                               value="${subject.subject_id}" 
                               id="subject_${subject.subject_id}"
                               data-name="${subject.subject_name}"
                               data-semester="${subject.semester_id || ''}">
                        <label class="form-check-label" for="subject_${subject.subject_id}">
                            ${subject.subject_name}
                            ${subject.semester_id && subject.semester_id !== 'all_semesters' ? 
                              ` (Semester ${subject.semester_id})` : ''}
                        </label>
                    </div>
                `;
                
                grid.appendChild(col);
            });
            
            gridContainer.appendChild(grid);
            multipleSubjectsContainer.appendChild(gridContainer);
            
            // Add selected count display
            const countDiv = document.createElement('div');
            countDiv.className = 'mt-2 text-muted';
            countDiv.id = 'selectedSubjectsCount';
            countDiv.innerHTML = '<i class="fas fa-check-circle me-1"></i> 0 subjects selected';
            multipleSubjectsContainer.appendChild(countDiv);
            
            // Add event listeners
            document.getElementById('selectAllSubjects').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.subject-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectedSubjectsCount();
                // Show common exam name section if any subject is selected
                if (this.checked || document.querySelectorAll('.subject-checkbox:checked').length > 0) {
                    showCommonExamNameSection();
                }
            });
            
            const subjectCheckboxes = document.querySelectorAll('.subject-checkbox');
            subjectCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectedSubjectsCount();
                    
                    // Show common exam name section if any subject is selected
                    if (document.querySelectorAll('.subject-checkbox:checked').length > 0) {
                        showCommonExamNameSection();
                    } else {
                        commonExamNameSection.style.display = 'none';
                    }
                });
            });
        }
        
        // Function to update selected subjects count
        function updateSelectedSubjectsCount() {
            const selectedCount = document.querySelectorAll('.subject-checkbox:checked').length;
            const countDiv = document.getElementById('selectedSubjectsCount');
            
            if (selectedCount > 0) {
                countDiv.innerHTML = `<i class="fas fa-check-circle me-1"></i> ${selectedCount} subject(s) selected`;
                countDiv.style.color = 'var(--success-color)';
            } else {
                countDiv.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i> Please select at least one subject';
                countDiv.style.color = 'var(--danger-color)';
            }
        }
        
        // Function to populate semester dropdown
        function populateSemesterSelect(semesters) {
            const semesterSelect = document.getElementById('semesterSelect');
            semesterSelect.innerHTML = '<option value="">-- Select Semester --</option>';
            
            semesters.forEach(semester => {
                const option = document.createElement('option');
                option.value = semester;
                option.textContent = semester;
                semesterSelect.appendChild(option);
            });
        }
        
        // Function to load subjects by semester
        function loadSubjectsBySemester(productId, semesterId) {
            const subjectSelect = document.getElementById('subjectSelect');
            const subjectInfo = document.getElementById('subjectInfo');
            
            subjectSelect.disabled = true;
            subjectSelect.innerHTML = '<option value="" selected class="select-placeholder">Loading subjects...</option>';
            
            if (semesterId === 'all_semesters') {
                // Show all subjects (including those marked for all semesters)
                const filteredSubjects = window.allSubjects.filter(subject => 
                    !subject.semester_id || subject.semester_id === 'all_semesters' || subject.semester_id === semesterId
                );
                handleSubjectsDisplay(filteredSubjects);
                subjectInfo.style.display = 'block';
                subjectInfo.innerHTML = `<i class="fas fa-info-circle"></i> ${filteredSubjects.length} subject(s) available for all semesters`;
            } else {
                // Show subjects for specific semester OR all_semesters
                const filteredSubjects = window.allSubjects.filter(subject => 
                    subject.semester_id === semesterId || subject.semester_id === 'all_semesters'
                );
                handleSubjectsDisplay(filteredSubjects);
                subjectInfo.style.display = 'block';
                subjectInfo.innerHTML = `<i class="fas fa-info-circle"></i> ${filteredSubjects.length} subject(s) available for Semester ${semesterId}`;
            }
        }
        
        // Function to create exam sections dynamically for each selected subject
        function createExamSectionsForSubjects() {
            let selectedSubjects = [];
            
            // Check if we're in single subject or multiple subjects mode
            const subjectSelect = document.getElementById('subjectSelect');
            const multipleSubjectsContainer = document.getElementById('multipleSubjectsContainer');
            
            if (multipleSubjectsContainer.style.display === 'block') {
                // Multiple subjects mode
                selectedSubjects = document.querySelectorAll('.subject-checkbox:checked');
                if (selectedSubjects.length === 0) {
                    alert('Please select at least one subject');
                    return;
                }
            } else {
                // Single subject mode
                if (!subjectSelect.value) {
                    alert('Please select a subject');
                    return;
                }
                // Create a mock checkbox element for single subject
                const mockCheckbox = {
                    value: subjectSelect.value,
                    getAttribute: function(attr) {
                        if (attr === 'data-name') {
                            return subjectSelect.options[subjectSelect.selectedIndex].textContent;
                        }
                        if (attr === 'data-semester') {
                            return document.getElementById('semesterSelect').value || '';
                        }
                        return '';
                    }
                };
                selectedSubjects = [mockCheckbox];
            }
            
            // Show action buttons
            const actionButtons = document.querySelector('.action-buttons');
            actionButtons.classList.add('show');
            
            // Hide common exam name section
            document.getElementById('commonExamNameSection').style.display = 'none';
            
            // Create container for exam sections
            let examSectionsContainer = document.getElementById('examSectionsContainer');
            if (!examSectionsContainer) {
                examSectionsContainer = document.createElement('div');
                examSectionsContainer.id = 'examSectionsContainer';
                examSectionsContainer.className = 'mb-4';
                
                // Insert after the common exam name section
                const commonSection = document.getElementById('commonExamNameSection');
                commonSection.parentNode.insertBefore(examSectionsContainer, commonSection.nextSibling);
            }
            
            examSectionsContainer.innerHTML = '';
            examSectionsContainer.style.display = 'block';
            
            // Add header
            const header = document.createElement('h5');
            header.className = 'mb-3';
            header.style.color = 'var(--primary-color)';
            header.innerHTML = '<i class="fas fa-calendar-alt me-2"></i>Exam Details for Each Subject';
            examSectionsContainer.appendChild(header);
            
            // Create exam section for each selected subject
            selectedSubjects.forEach((checkbox, index) => {
                const subjectId = checkbox.value;
                const subjectName = checkbox.getAttribute('data-name');
                const semester = checkbox.getAttribute('data-semester');
                
                const subjectSection = document.createElement('div');
                subjectSection.className = 'card mb-3 subject-exam-section';
                subjectSection.id = `subject_exam_${subjectId}`;
                subjectSection.dataset.subjectId = subjectId;
                subjectSection.dataset.subjectName = subjectName;
                subjectSection.dataset.semester = semester;
                
                subjectSection.innerHTML = `
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-book me-2"></i>
                            <strong>${subjectName}</strong>
                            ${semester && semester !== 'all_semesters' ? `<span class="badge bg-info ms-2">Semester ${semester}</span>` : ''}
                            <span class="badge bg-primary ms-2">Subject ${index + 1}</span>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input apply-all-checkbox" type="checkbox" 
                                   id="apply_all_${subjectId}">
                            <label class="form-check-label" for="apply_all_${subjectId}">
                                Apply to all
                            </label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Exam Name for each subject (pre-filled with common name) -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Exam Name</label>
                                <input type="text" class="form-control exam-name" 
                                       data-subject="${subjectId}" 
                                       placeholder="e.g., Mid Term, Final Exam" 
                                       value="${document.getElementById('commonExamName').value}"
                                       required>
                            </div>
                            
                            <!-- Classroom -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Classroom</label>
                                <select class="form-select classroom" data-subject="${subjectId}" required>
                                    <option value="">Select Classroom</option>
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
                            
                            <!-- Exam Date -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Exam Date</label>
                                <input type="date" class="form-control exam-date" 
                                       data-subject="${subjectId}" 
                                       value="${document.getElementById('examDate').value}"
                                       required>
                            </div>
                            
                            <!-- Start Time -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Start Time</label>
                                <input type="time" class="form-control start-time" 
                                       data-subject="${subjectId}" 
                                       value="${document.getElementById('examTime').value || '10:00'}" 
                                       required>
                            </div>
                            
                            <!-- Duration -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Duration (minutes)</label>
                                <input type="number" class="form-control duration-minutes" 
                                       min="15" max="360" 
                                       value="${document.getElementById('examDuration').value || 180}" 
                                       data-subject="${subjectId}" required>
                            </div>
                            
                            <!-- End Time (Auto-calculated) -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label">End Time</label>
                                <div class="form-control bg-light end-time-display" 
                                     data-subject="${subjectId}">
                                    Calculated automatically
                                </div>
                            </div>
                            
                            <!-- Total Marks -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Total Marks</label>
                                <input type="number" class="form-control total-marks" 
                                       min="1" max="1000" 
                                       value="${document.getElementById('totalMarks').value || 100}" 
                                       data-subject="${subjectId}" required>
                            </div>
                            
                            <!-- Passing Marks -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Passing Marks</label>
                                <input type="number" class="form-control passing-marks" 
                                       min="1" max="100" 
                                       value="${document.getElementById('passingMarks').value || 40}" 
                                       data-subject="${subjectId}" required>
                            </div>
                        </div>
                    </div>
                `;
                
                examSectionsContainer.appendChild(subjectSection);
                
                // Add event listeners for this section
                setupExamSectionEvents(subjectId);
            });
            
            // Scroll to exam sections
            examSectionsContainer.scrollIntoView({ behavior: 'smooth' });
        }
        
        // Function to setup event listeners for an exam section
        function setupExamSectionEvents(subjectId) {
            // Calculate end time when start time or duration changes
            const startTimeInput = document.querySelector(`.start-time[data-subject="${subjectId}"]`);
            const durationInput = document.querySelector(`.duration-minutes[data-subject="${subjectId}"]`);
            const endTimeDisplay = document.querySelector(`.end-time-display[data-subject="${subjectId}"]`);
            
            const updateEndTime = () => {
                const startTime = startTimeInput.value;
                const duration = parseInt(durationInput.value) || 0;
                
                if (startTime && duration > 0) {
                    const [hours, minutes] = startTime.split(':').map(Number);
                    const totalMinutes = hours * 60 + minutes + duration;
                    const endHours = Math.floor(totalMinutes / 60) % 24;
                    const endMinutes = totalMinutes % 60;
                    const endTime = `${endHours.toString().padStart(2, '0')}:${endMinutes.toString().padStart(2, '0')}`;
                    
                    // Format for display
                    const period = endHours >= 12 ? 'PM' : 'AM';
                    const displayHours = endHours % 12 || 12;
                    endTimeDisplay.textContent = `${displayHours}:${endMinutes.toString().padStart(2, '0')} ${period}`;
                    endTimeDisplay.dataset.endTime = endTime;
                }
            };
            
            if (startTimeInput) {
                startTimeInput.addEventListener('change', updateEndTime);
                startTimeInput.addEventListener('input', updateEndTime);
            }
            
            if (durationInput) {
                durationInput.addEventListener('change', updateEndTime);
                durationInput.addEventListener('input', updateEndTime);
            }
            
            // Initialize end time
            updateEndTime();
            
            // Apply to all functionality
            const applyAllCheckbox = document.querySelector(`#apply_all_${subjectId}`);
            if (applyAllCheckbox) {
                applyAllCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        // Get values from this section
                        const examName = document.querySelector(`.exam-name[data-subject="${subjectId}"]`).value;
                        const examDate = document.querySelector(`.exam-date[data-subject="${subjectId}"]`).value;
                        const startTime = startTimeInput.value;
                        const duration = durationInput.value;
                        const classroom = document.querySelector(`.classroom[data-subject="${subjectId}"]`).value;
                        const totalMarks = document.querySelector(`.total-marks[data-subject="${subjectId}"]`).value;
                        const passingMarks = document.querySelector(`.passing-marks[data-subject="${subjectId}"]`).value;
                        
                        // Apply to all other sections
                        document.querySelectorAll('.subject-exam-section').forEach(section => {
                            const otherSubjectId = section.dataset.subjectId;
                            if (otherSubjectId !== subjectId) {
                                const otherNameInput = document.querySelector(`.exam-name[data-subject="${otherSubjectId}"]`);
                                const otherDateInput = document.querySelector(`.exam-date[data-subject="${otherSubjectId}"]`);
                                const otherTimeInput = document.querySelector(`.start-time[data-subject="${otherSubjectId}"]`);
                                const otherDurationInput = document.querySelector(`.duration-minutes[data-subject="${otherSubjectId}"]`);
                                const otherClassroomInput = document.querySelector(`.classroom[data-subject="${otherSubjectId}"]`);
                                const otherTotalMarksInput = document.querySelector(`.total-marks[data-subject="${otherSubjectId}"]`);
                                const otherPassingMarksInput = document.querySelector(`.passing-marks[data-subject="${otherSubjectId}"]`);
                                
                                if (otherNameInput) otherNameInput.value = examName;
                                if (otherDateInput) otherDateInput.value = examDate;
                                if (otherTimeInput) otherTimeInput.value = startTime;
                                if (otherDurationInput) otherDurationInput.value = duration;
                                if (otherClassroomInput) otherClassroomInput.value = classroom;
                                if (otherTotalMarksInput) otherTotalMarksInput.value = totalMarks;
                                if (otherPassingMarksInput) otherPassingMarksInput.value = passingMarks;
                                
                                // Trigger end time calculation for other sections
                                if (otherTimeInput) otherTimeInput.dispatchEvent(new Event('change'));
                            }
                        });
                    }
                    
                });
            }
        }
        
        // Function to reset dependent fields when parent field changes
        function resetDependentFields(resetFrom) {
            const courseSelect = document.getElementById('courseSelect');
            const branchSelect = document.getElementById('branchSelect');
            const subjectSelect = document.getElementById('subjectSelect');
            const subjectDropdownContainer = document.getElementById('subjectDropdownContainer');
            const multipleSubjectsContainer = document.getElementById('multipleSubjectsContainer');
            const semesterContainer = document.getElementById('semesterContainer');
            const subjectInfo = document.getElementById('subjectInfo');
            const examSectionsContainer = document.getElementById('examSectionsContainer');
            const sectionsContainer = document.getElementById('sectionsContainer');
            const commonExamNameSection = document.getElementById('commonExamNameSection');
            const actionButtons = document.querySelector('.action-buttons');
            
            switch(resetFrom) {
                case 'department':
                    // Reset course, branch, and subject
                    courseSelect.innerHTML = '<option value="" selected class="select-placeholder">Please select a department first</option>';
                    courseSelect.disabled = true;
                    
                    branchSelect.innerHTML = '<option value="" selected class="select-placeholder">Please select a course first</option>';
                    branchSelect.disabled = true;
                    
                    subjectSelect.innerHTML = '<option value="" selected class="select-placeholder">Please select a branch first</option>';
                    subjectSelect.disabled = true;
                    
                    sectionsContainer.innerHTML = '<div class="form-text">Please select a branch first</div>';
                    
                    subjectDropdownContainer.style.display = 'block';
                    multipleSubjectsContainer.style.display = 'none';
                    semesterContainer.style.display = 'none';
                    subjectInfo.style.display = 'none';
                    examSectionsContainer.style.display = 'none';
                    commonExamNameSection.style.display = 'none';
                    document.getElementById('singleExamDetails').style.display = 'none';
                    actionButtons.classList.remove('show');
                    break;
                    
                case 'course':
                    // Reset branch and subject
                    branchSelect.innerHTML = '<option value="" selected class="select-placeholder">Please select a course first</option>';
                    branchSelect.disabled = true;
                    
                    subjectSelect.innerHTML = '<option value="" selected class="select-placeholder">Please select a branch first</option>';
                    subjectSelect.disabled = true;
                    
                    sectionsContainer.innerHTML = '<div class="form-text">Please select a branch first</div>';
                    
                    subjectDropdownContainer.style.display = 'block';
                    multipleSubjectsContainer.style.display = 'none';
                    semesterContainer.style.display = 'none';
                    subjectInfo.style.display = 'none';
                    examSectionsContainer.style.display = 'none';
                    commonExamNameSection.style.display = 'none';
                    document.getElementById('singleExamDetails').style.display = 'none';
                    actionButtons.classList.remove('show');
                    break;
                    
                case 'branch':
                    // Reset subject
                    subjectSelect.innerHTML = '<option value="" selected class="select-placeholder">Please select a branch first</option>';
                    subjectSelect.disabled = true;                   
                    subjectDropdownContainer.style.display = 'block';
                    multipleSubjectsContainer.style.display = 'none';
                    semesterContainer.style.display = 'none';
                    subjectInfo.style.display = 'none';
                    examSectionsContainer.style.display = 'none';
                    commonExamNameSection.style.display = 'none';
                    document.getElementById('singleExamDetails').style.display = 'none';
                    actionButtons.classList.remove('show');
                    break;
            }
        }
        
        function updateEndTime() {
            const startTime = document.getElementById('examTime').value;
            const duration = parseInt(document.getElementById('examDuration').value) || 0;
            
            if (!startTime || duration <= 0) {
                document.getElementById('examEndTime').textContent = 'Calculated automatically';
                return;
            }
            
            // Parse start time
            const [startHours, startMinutes] = startTime.split(':').map(Number);
            let totalMinutes = startHours * 60 + startMinutes;
            
            // Add duration in minutes
            totalMinutes += duration;
            
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
        
        // Main function to create exam(s)
        async function createExam() {
            // Check if we're in single subject or multiple subjects mode
            const subjectSelect = document.getElementById('subjectSelect');
            const multipleSubjectsContainer = document.getElementById('multipleSubjectsContainer');
            const examSectionsContainer = document.getElementById('examSectionsContainer');
            let examDataArray = [];
            if (examSectionsContainer.style.display === 'block' && examSectionsContainer.children.length > 0) {
                // Multiple subjects mode (or single subject with exam sections)
                examDataArray = await prepareMultipleExamsData();
                if (examDataArray.length === 0) return;
            } else {
                // Single subject mode with single exam details
                examDataArray = await prepareSingleExamData();
                if (examDataArray.length === 0) return;
            }
                // Check grade system is selected
            const gradeSystemSelect = document.getElementById('gradeSystemSelect');
            if (!gradeSystemSelect || !gradeSystemSelect.value) {
                alert('Please select a grade system');
                gradeSystemSelect.focus();
                return;
            }
            console.log('Submitting exam data:', examDataArray);
            // Submit to server
            try {
                const response = await fetch('/save-multiple-exams', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ exams: examDataArray })
                });
                // Get response text first
                const responseText = await response.text();
                console.log('Raw response:', responseText.substring(0, 200) + '...'); // Log first 200 chars
                if (!response.ok) {
                    // Try to parse as JSON for error responses
                    try {
                        const errorData = JSON.parse(responseText);
                        throw new Error(errorData.message || `Server error: ${response.status}`);
                    } catch (e) {
                        // If not JSON, show raw error
                        throw new Error(`Server returned ${response.status}. Response: ${responseText.substring(0, 100)}`);
                    }
                }
                // Parse successful JSON response
                const result = JSON.parse(responseText);
                if (result.success) {
                    showSuccessSummary(examDataArray);
                } else {
                    alert('Error creating exams: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error creating exams: ' + error.message);
            }
        }
        
        // Function to prepare data for multiple exams
        async function prepareMultipleExamsData() {
            const examSections = document.querySelectorAll('.subject-exam-section');
            const examDataArray = [];
            const errors = [];
            
            // Get grade system ID from the dropdown
            const gradeSystemSelect = document.getElementById('gradeSystemSelect');
            const gradeSystemId = gradeSystemSelect ? gradeSystemSelect.value : null;
            
            if (!gradeSystemId) {
                errors.push('Please select a grade system');
                alert(errors.join('\n'));
                return [];
            }
            
            examSections.forEach(section => {
                const subjectId = section.dataset.subjectId;
                const subjectName = section.dataset.subjectName;
                
                // Validate required fields - ADD GRADE SYSTEM VALIDATION
                const requiredFields = [
                    { selector: `.exam-name[data-subject="${subjectId}"]`, name: 'Exam Name' },
                    { selector: `.exam-date[data-subject="${subjectId}"]`, name: 'Exam Date' },
                    { selector: `.start-time[data-subject="${subjectId}"]`, name: 'Start Time' },
                    { selector: `.duration-minutes[data-subject="${subjectId}"]`, name: 'Duration' },
                    { selector: `.classroom[data-subject="${subjectId}"]`, name: 'Classroom' },
                    { selector: `.total-marks[data-subject="${subjectId}"]`, name: 'Total Marks' },
                    { selector: `.passing-marks[data-subject="${subjectId}"]`, name: 'Passing Marks' }
                ];
                
                requiredFields.forEach(field => {
                    const element = document.querySelector(field.selector);
                    if (!element || !element.value.trim()) {
                        errors.push(`${field.name} is required for subject: ${subjectName}`);
                    }
                });
                
                // Validate sections
                const sectionCheckboxes = document.querySelectorAll('.section-checkbox:checked');
                if (sectionCheckboxes.length === 0) {
                    errors.push(`Please select at least one section for subject: ${subjectName}`);
                }
                
                if (errors.length === 0) {
                    // Get selected sections
                    const selectedSections = Array.from(sectionCheckboxes).map(checkbox => ({
                        section_id: checkbox.value,
                        section_name: checkbox.getAttribute('data-name')
                    }));
                    
                    // Get exam details - ADD GRADE SYSTEM ID
                    const examData = {
                        institute_id: '{{ auth()->user()->institute_id ?? "" }}',
                        branch_id: document.getElementById('branchSelect').value,
                        department_category_id: document.getElementById('categorySelect').value,
                        department_id: document.getElementById('departmentSelect').value,
                        course_id: document.getElementById('courseSelect').value,
                        subtype_id: document.getElementById('branchSelect').value,
                        semester_id: section.dataset.semester || document.getElementById('semesterSelect').value || null,
                        subject_id: subjectId,
                        section_id: selectedSections.map(s => s.section_id).join(','),
                        exam_name: document.querySelector(`.exam-name[data-subject="${subjectId}"]`).value,
                        grade_system_id: gradeSystemId, // ADD THIS LINE
                        exam_date: document.querySelector(`.exam-date[data-subject="${subjectId}"]`).value,
                        start_time: document.querySelector(`.start-time[data-subject="${subjectId}"]`).value,
                        end_time: document.querySelector(`.end-time-display[data-subject="${subjectId}"]`).dataset.endTime,
                        duration_minutes: parseInt(document.querySelector(`.duration-minutes[data-subject="${subjectId}"]`).value),
                        classroom_id: document.querySelector(`.classroom[data-subject="${subjectId}"]`).value,
                        total_marks: parseInt(document.querySelector(`.total-marks[data-subject="${subjectId}"]`).value),
                        passing_marks: parseInt(document.querySelector(`.passing-marks[data-subject="${subjectId}"]`).value),
                        status: 'draft',
                        is_published: false,
                        _token: csrfToken
                    };
                    examDataArray.push(examData);
                }
            });
            
            if (errors.length > 0) {
                alert(errors.join('\n'));
                return [];
            }
            
            return examDataArray;
        }
        
        // Function to prepare data for single exam
        async function prepareSingleExamData() {
            const errors = [];
            
            // Get grade system ID
            const gradeSystemSelect = document.getElementById('gradeSystemSelect');
            const gradeSystemId = gradeSystemSelect ? gradeSystemSelect.value : null;
            
            if (!gradeSystemId) {
                errors.push('Please select a grade system');
                alert(errors.join('\n'));
                return [];
            }
            
            // Check if we have exam sections (multiple subjects mode)
            const examSections = document.querySelectorAll('.subject-exam-section');
            if (examSections.length > 0) {
                // We're in multiple subjects mode with exam sections
                return prepareMultipleExamsData();
            }
            
            // Original single subject mode
            const requiredFields = [
                { id: 'commonExamName', name: 'Exam Name' },
                { id: 'examDate', name: 'Exam Date' },
                { id: 'examTime', name: 'Start Time' },
                { id: 'examDuration', name: 'Duration' },
                { id: 'classroomSelect', name: 'Classroom' },
                { id: 'totalMarks', name: 'Total Marks' },
                { id: 'passingMarks', name: 'Passing Marks' },
                { id: 'subjectSelect', name: 'Subject' }
            ];
            
            requiredFields.forEach(field => {
                const element = document.getElementById(field.id);
                if (!element || !element.value) {
                    errors.push(`${field.name} is required`);
                }
            });
            
            // Validate sections
            const sectionCheckboxes = document.querySelectorAll('.section-checkbox:checked');
            if (sectionCheckboxes.length === 0) {
                errors.push('Please select at least one section');
            }
            
            if (errors.length > 0) {
                alert(errors.join('\n'));
                return [];
            }
            
            // Get selected sections
            const selectedSections = Array.from(sectionCheckboxes).map(checkbox => ({
                section_id: checkbox.value,
                section_name: checkbox.getAttribute('data-name')
            }));
            
            // Calculate end time
            const startTime = document.getElementById('examTime').value;
            const duration = parseInt(document.getElementById('examDuration').value);
            const [hours, minutes] = startTime.split(':').map(Number);
            const totalMinutes = hours * 60 + minutes + duration;
            const endHours = Math.floor(totalMinutes / 60) % 24;
            const endMinutes = totalMinutes % 60;
            const endTime = `${endHours.toString().padStart(2, '0')}:${endMinutes.toString().padStart(2, '0')}`;
            
            // Get selected subject info
            const subjectSelect = document.getElementById('subjectSelect');
            const selectedSubject = subjectSelect.options[subjectSelect.selectedIndex];
            const subjectName = selectedSubject.textContent;
            
            const examData = {
                institute_id: '{{ auth()->user()->institute_id ?? "" }}',
                branch_id: document.getElementById('branchSelect').value,
                department_category_id: document.getElementById('categorySelect').value,
                department_id: document.getElementById('departmentSelect').value,
                course_id: document.getElementById('courseSelect').value,
                subtype_id: document.getElementById('branchSelect').value,
                semester_id: document.getElementById('semesterSelect').value || null,
                subject_id: subjectSelect.value,
                subject_name: subjectName,
                section_id: selectedSections.map(s => s.section_id).join(','),
                exam_name: document.getElementById('commonExamName').value,
                grade_system_id: gradeSystemId, // ADD THIS LINE
                exam_date: document.getElementById('examDate').value,
                start_time: startTime,
                end_time: endTime,
                duration_minutes: duration,
                classroom_id: document.getElementById('classroomSelect').value,
                total_marks: parseInt(document.getElementById('totalMarks').value),
                passing_marks: parseInt(document.getElementById('passingMarks').value),
                status: 'draft',
                is_published: false,
                _token: csrfToken
            };
            
            return [examData];
        }
        // Function to show success summary
        function showSuccessSummary(examDataArray) {
            // Update basic summary info
            document.getElementById('summaryCategory').textContent = document.getElementById('categorySelect').options[document.getElementById('categorySelect').selectedIndex].text;
            document.getElementById('summaryDepartment').textContent = document.getElementById('departmentSelect').options[document.getElementById('departmentSelect').selectedIndex].text;
            document.getElementById('summaryCourse').textContent = document.getElementById('courseSelect').options[document.getElementById('courseSelect').selectedIndex].text;
            document.getElementById('summaryBranch').textContent = document.getElementById('branchSelect').options[document.getElementById('branchSelect').selectedIndex].text;
            document.getElementById('summaryExamCount').textContent = examDataArray.length + ' exam(s)';
            
            // Add exam name to summary
            if (examDataArray.length > 0) {
                document.getElementById('summaryExamName').textContent = examDataArray[0].exam_name;
            }
            
            // Get unique subject names
            const subjectNames = [...new Set(examDataArray.map(exam => {
                if (exam.subject_name) return exam.subject_name;
                // Try to get from DOM
                const subjectElement = document.querySelector(`[data-subject-id="${exam.subject_id}"]`);
                return subjectElement ? subjectElement.dataset.subjectName : `Subject ID: ${exam.subject_id}`;
            }))];
            document.getElementById('summarySubject').textContent = subjectNames.join(', ');
            
            // Create summary table
            let tableHtml = `
                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <th>Exam Name</th>
                                <th>Date & Time</th>
                                <th>Duration</th>
                                <th>Sections</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            examDataArray.forEach((exam, index) => {
                const subjectName = exam.subject_name || `Subject ID: ${exam.subject_id}`;
                const formattedDate = new Date(exam.exam_date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
                const formattedTime = formatTimeForDisplay(exam.start_time);
                const sectionCount = exam.section_id ? exam.section_id.split(',').length : 0;
                
                tableHtml += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${subjectName}</td>
                        <td><strong>${exam.exam_name}</strong></td>
                        <td>${formattedDate} at ${formattedTime}</td>
                        <td>${exam.duration_minutes} minutes</td>
                        <td>${sectionCount} section(s)</td>
                    </tr>
                `;
            });
            
            tableHtml += `
                        </tbody>
                    </table>
                </div>
            `;
            
            document.getElementById('summaryContent').innerHTML = tableHtml;
            
            // Update success message
            document.getElementById('successMessage').textContent = 
                `${examDataArray.length} exam(s) have been successfully created and saved to the system.`;
            
            // Show summary and hide form
            document.getElementById('examForm').style.display = 'none';
            document.getElementById('examSummary').style.display = 'block';
            
            // Scroll to summary
            document.getElementById('examSummary').scrollIntoView({ behavior: 'smooth' });
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
                
                // Reset common exam name
                document.getElementById('commonExamName').value = "Mid Term Examination";
                
                // Reset passing marks
                document.getElementById('passingMarks').value = "40";
                
                // Reset total marks
                document.getElementById('totalMarks').value = "100";
                
                // Reset duration
                document.getElementById('examDuration').value = "180";
                
                // Reset category dropdown
                document.getElementById('categorySelect').selectedIndex = 0;
                
                // Reset department dropdown
                document.getElementById('departmentSelect').innerHTML = '<option value="" selected class="select-placeholder">Please select a category first</option>';
                document.getElementById('departmentSelect').disabled = true;
                
                // Reset course dropdown
                document.getElementById('courseSelect').innerHTML = '<option value="" selected class="select-placeholder">Please select a department first</option>';
                document.getElementById('courseSelect').disabled = true;
                
                // Reset branch dropdown
                document.getElementById('branchSelect').innerHTML = '<option value="" selected class="select-placeholder">Please select a course first</option>';
                document.getElementById('branchSelect').disabled = true;
                document.getElementById('singleBranchMessage').style.display = 'none';
                
                // Reset sections container
                document.getElementById('sectionsContainer').innerHTML = '<div class="form-text">Please select a branch first</div>';
                
                // Reset subject dropdown
                document.getElementById('subjectSelect').innerHTML = '<option value="" selected class="select-placeholder">Please select a branch first</option>';
                document.getElementById('subjectSelect').disabled = true;
                document.getElementById('subjectDropdownContainer').style.display = 'block';
                
                // Reset semester container
                document.getElementById('semesterContainer').style.display = 'none';
                document.getElementById('semesterSelect').innerHTML = '<option value="">-- Select Semester --</option>';
                document.getElementById('semesterInfo').style.display = 'none';
                
                // Reset multiple subjects container
                const multipleContainer = document.getElementById('multipleSubjectsContainer');
                if (multipleContainer) {
                    multipleContainer.style.display = 'none';
                    multipleContainer.innerHTML = '';
                }
                
                // Reset common exam name section
                document.getElementById('commonExamNameSection').style.display = 'none';
                
                // Reset exam sections container
                const examSectionsContainer = document.getElementById('examSectionsContainer');
                if (examSectionsContainer) {
                    examSectionsContainer.style.display = 'none';
                    examSectionsContainer.innerHTML = '';
                }
                
                // Reset single exam details
                document.getElementById('singleExamDetails').style.display = 'none';
                
                // Reset info displays
                document.getElementById('subjectInfo').style.display = 'none';
                document.getElementById('subjectLoading').style.display = 'none';
                
                // Reset loading indicators
                document.getElementById('departmentLoading').style.display = 'none';
                document.getElementById('courseLoading').style.display = 'none';
                document.getElementById('branchLoading').style.display = 'none';
                document.getElementById('sectionsLoading').style.display = 'none';
                
                // Hide action buttons
                const actionButtons = document.querySelector('.action-buttons');
                actionButtons.classList.remove('show');
                
                // Clear global subjects
                window.allSubjects = null;
                
                // Update end time display
                updateEndTime();
                
                // If summary is showing, hide it and show form
                if (document.getElementById('examSummary').style.display === 'block') {
                    document.getElementById('examSummary').style.display = 'none';
                    document.getElementById('examForm').style.display = 'block';
                }
            }
        }
    </script>
@endsection
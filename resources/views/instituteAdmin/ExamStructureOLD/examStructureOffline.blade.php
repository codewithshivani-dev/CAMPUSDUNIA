@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --primary-light: rgba(67, 97, 238, 0.1);
            --success-gradient: linear-gradient(135deg, #10b981, #059669);
            --success-color: #10b981;
            --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
            --danger-color: #ef4444;
            --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
            --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
            --dark: #1f2937;
            --gray: #6b7280;
            --light-gray: #f9fafb;
            --border: #e5e7eb;
            --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px 30px;
            background: var(--primary-gradient);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: white;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .page-title i {
            font-size: 32px;
            filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
        }

        .back-btn {
            padding: 12px 24px;
            background: var(--primary-gradient);
            border: none;
            color: #fff !important;
            cursor: pointer;
            border-radius: 10px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .back-btn:hover {
            transform: translateY(-3px);
            text-decoration: none;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(67, 97, 238, 0.15);
        }

        .card-header {
            background: var(--primary-gradient);
            color: white;
            font-weight: 600;
            padding: 1.2rem 1.5rem;
            border-bottom: none;
            font-size: 1.2rem;
        }

        .card-header i {
            margin-right: 10px;
            font-size: 1.3rem;
        }

        .card-body {
            padding: 1.8rem;
            background: white;
        }

        h5 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1.2rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-light);
        }

        h5 i {
            margin-right: 8px;
            color: var(--primary-color);
        }

        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            letter-spacing: 0.3px;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid var(--border);
            padding: 0.6rem 1rem;
            transition: all 0.3s;
            font-size: 0.95rem;
            height: 45px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
            transform: translateY(-2px);
        }

        .form-control:hover, .form-select:hover {
            border-color: var(--secondary-color);
        }

        textarea.form-control {
            height: auto;
            min-height: 60px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #64748b, #475569);
            color: white;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #475569, #334155);
        }

        .btn-outline-primary {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
        }

        .btn-outline-secondary {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid var(--border);
        }

        .btn-outline-secondary:hover {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
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
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 8px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            transition: all 0.2s;
            border: 1px solid var(--border);
        }

        .checkbox-item:hover {
            background: var(--primary-light);
            transform: translateY(-2px);
            border-color: var(--primary-color);
        }

        .checkbox-item.checked {
            background: var(--primary-light);
            border: 1px solid var(--primary-color);
        }

        .section-controls {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .exam-summary {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin-top: 2rem;
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .exam-summary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--success-gradient);
        }

        .summary-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--primary-light);
            font-size: 1.5rem;
        }

        .summary-item {
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px dashed var(--border);
        }

        .summary-label {
            font-weight: 600;
            color: var(--gray);
        }

        .summary-value {
            font-weight: 600;
            color: var(--dark);
        }

        .alert-success {
            background: var(--success-gradient);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 1rem 1.5rem;
        }

        footer {
            text-align: center;
            margin-top: 2rem;
            color: var(--gray);
            font-size: 0.9rem;
        }

        .section-selector {
            max-height: 200px;
            overflow-y: auto;
            padding: 15px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
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
            background: linear-gradient(90deg, var(--border), var(--primary-color), var(--border));
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
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
            color: var(--dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 8px;
            transition: all 0.3s;
            border: 2px solid white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .step.active .step-circle {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.4);
            transform: scale(1.1);
        }

        .step.completed .step-circle {
            background: var(--success-gradient);
            color: white;
        }

        .step-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--gray);
            text-align: center;
        }

        .step.active .step-label {
            color: var(--primary-color);
            font-weight: 700;
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
            color: var(--success-color);
            margin-top: 5px;
            font-weight: 500;
        }

        .loading-spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .select-placeholder {
            color: var(--gray);
        }
        
        .classroom-input-group {
            display: flex;
            gap: 10px;
        }
        
        .classroom-input-group .btn {
            white-space: nowrap;
        }
        
        .modal-header {
            background: var(--primary-gradient);
            color: white;
        }
        
        .semester-container {
            display: none;
            margin-top: 15px;
            padding: 20px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 12px;
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .semester-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--info-gradient);
        }
        
        .semester-info {
            font-size: 0.9rem;
            color: var(--gray);
            margin-top: 5px;
            display: none;
        }

        /* Subject Checkbox Container */
        .subject-checkbox-container {
            border: 1px solid var(--border);
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all 0.2s;
            background: white;
        }

        .subject-checkbox-container:hover {
            background: var(--primary-light);
            transform: translateX(5px);
            border-color: var(--primary-color);
        }

        .subject-checkbox-container .form-check-label {
            font-weight: 500;
            cursor: pointer;
            color: var(--dark);
        }

        .subject-exam-section {
            border-left: 4px solid var(--primary-color);
            margin-bottom: 20px;
        }

        .subject-exam-section .card-header {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            color: var(--dark);
            border-bottom: 1px solid var(--border);
        }

        .subject-exam-section .card-header strong {
            color: var(--primary-color);
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .badge.bg-info {
            background: var(--info-gradient) !important;
            color: white;
        }

        .badge.bg-primary {
            background: var(--primary-gradient) !important;
            color: white;
        }

        .section-selector-container {
            padding: 15px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 10px;
            border: 1px solid var(--border);
            max-height: 250px;
            overflow-y: auto;
        }

        .section-selector-container .form-check {
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .section-selector-container .form-check:hover {
            background: var(--primary-light);
        }

        .section-checkbox:checked + .form-check-label {
            color: var(--primary-color);
            font-weight: 600;
        }

        .apply-all-checkbox:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .generate-code-btn:hover {
            background: var(--primary-gradient);
            color: white;
        }

        /* Custom scrollbar */
        .section-selector-container::-webkit-scrollbar,
        .section-selector::-webkit-scrollbar {
            width: 6px;
        }

        .section-selector-container::-webkit-scrollbar-track,
        .section-selector::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .section-selector-container::-webkit-scrollbar-thumb,
        .section-selector::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 3px;
        }

        .section-selector-container::-webkit-scrollbar-thumb:hover,
        .section-selector::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }

        /* Subject Grid */
        .subject-grid-container {
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 15px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        }

        .subject-row {
            display: flex;
            flex-wrap: wrap;
            margin: -5px;
        }

        .subject-col {
            flex: 0 0 33.333%;
            max-width: 33.333%;
            padding: 5px;
        }

        @media (max-width: 992px) {
            .subject-col {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 768px) {
            .subject-col {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        .action-buttons {
            display: none !important;
            transition: opacity 0.3s ease;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .action-buttons.show {
            display: flex !important;
            opacity: 1;
        }

        /* Form text */
        .form-text {
            font-size: 0.8rem;
            color: var(--gray);
            margin-top: 5px;
        }

        .text-muted {
            color: var(--gray) !important;
        }

        .bg-light {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9) !important;
            border: 1px solid var(--border);
        }

        /* Table styles */
        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead th {
            background: var(--primary-gradient);
            color: white;
            font-weight: 600;
            border: none;
            padding: 12px;
        }

        .table tbody tr {
            transition: all 0.2s;
        }

        .table tbody tr:hover {
            background: var(--primary-light);
        }

        .table-bordered {
            border: 1px solid var(--border);
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid var(--border);
        }

        .table-hover tbody tr:hover {
            background: var(--primary-light);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
                padding: 20px;
            }

            .page-title {
                font-size: 24px;
            }

            .back-btn {
                width: 100%;
                justify-content: center;
            }

            .card-body {
                padding: 1.2rem;
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
                padding: 10px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }

            .action-buttons .btn {
                width: 100%;
            }
        }
    </style>

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-plus-circle"></i>
                Create New Exam
            </h1>
            <a href="javascript:history.back()" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Go Back
            </a>
        </div>

        <!-- Exam Form -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-pen-alt me-2"></i>Exam Creation Form
            </div>
            @php
                    $courseLabel = (isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School')
                        ? 'Class'
                        : 'Course';
                @endphp
            <div class="card-body">
                <form id="examForm">
                    <!-- CSRF Token for AJAX requests -->
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    
                    <!-- Step 1: Select Category (Academic/Non-Academic) -->
                    <div class="mb-4">
                        <h5>
                            <i class="fas fa-layer-group"></i>Select Category & Department
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="categorySelect" class="form-label">Category</label>
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
                    <div>
                        <h5>
                            <i class="fas fa-graduation-cap"></i>Select {{$courseLabel}}/Stream
                        </h5>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="courseSelect" class="form-label">{{$courseLabel}}</label>
                                <select class="form-select" id="courseSelect" required disabled>
                                    <option value="" selected class="select-placeholder">Please select a department first</option>
                                </select>
                                <div class="form-text" id="courseLoading" style="display: none;">
                                    <span class="loading-spinner"></span> Loading Classes...
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="branchSelect" class="form-label">Stream / Class</label>
                                <select class="form-select" id="branchSelect" required disabled>
                                    <option value="" selected class="select-placeholder">Please select a stream first</option>
                                </select>
                                <div class="form-text" id="branchLoading" style="display: none;">
                                    <span class="loading-spinner"></span> Loading streams...
                                </div>
                                <div class="form-text" id="singleBranchMessage" style="display: none; color: var(--success-color);">
                                    <i class="fas fa-check-circle"></i> Only one Stream available
                                </div>
                            </div>

                            <!-- Sections Selection -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Select Sections</label>
                                <div class="section-selector-container" id="sectionsContainer">
                                    <div class="form-text">Please select a Stream first</div>
                                </div>
                                <div id="sectionsLoading" style="display: none;" class="form-text">
                                    <span class="loading-spinner"></span> Loading sections...
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Semester and Subjects -->
                    <div class="mb-4">
                        <h5>
                            <i class="fas fa-book"></i>Select Subject
                        </h5>
                        <div class="row">
                            <!-- Semester Selection -->
                            <div class="col-md-12 mb-3">
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
                    
                    <!-- Step 3.5: Select Exam Name -->
                    <div class="col-md-12" id="selectExamNameSection" style="display: none;" class="mb-4">
                        <h5>
                            <i class="fas fa-file-signature"></i>Select Exam Name
                        </h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="examNameSelect" class="form-label">Exam Name</label>
                                <select class="form-select" id="examNameSelect" required>
                                    <option value="" selected disabled>Select Exam Name</option>
                                    <!-- Options will be loaded via AJAX -->
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="academicYearDisplay" class="form-label">Academic Year</label>
                                <div class="form-control bg-light" id="academicYearDisplay" style="height: auto; min-height: 45px; display: flex; align-items: center;">
                                    Will be auto-filled based on exam name selection
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="gradeSystemSelect" class="form-label">Grade System</label>
                                <select class="form-select" id="gradeSystemSelect" name="grade_system_id">
                                    <option value="">-- Select Grade System --</option>
                                    @foreach($gradeSystems as $system)
                                        <option value="{{ $system->id }}">{{ $system->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Optional: Select a custom grade system for this exam</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="examDescriptionDisplay" class="form-label">Description</label>
                                <div class="form-control bg-light" id="examDescriptionDisplay" style="min-height: 60px; height: auto;">
                                    No description available
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-primary w-100" id="loadExamNamesBtn">
                                    <i class="fas fa-sync-alt me-2"></i>Refresh Exam Names
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Common Exam Name Section -->
                    <div id="commonExamNameSection" style="display: none;" class="mb-3">
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Hidden input to store the common exam name -->
                                <input type="hidden" id="commonExamName" value="">
                            </div>
                            <div class="col-md-4 text-end">
                                <button type="button" class="btn btn-primary w-100" id="configureExamDetailsBtn">
                                    <i class="fas fa-cog me-2"></i>Configure Exam Details
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Exam Details for Multiple Subjects -->
                    <div id="examSectionsContainer" style="display: none;" class="mb-4">
                        <!-- Exam sections for each subject will be dynamically inserted here -->
                    </div>

                    <!-- Original Step 4: Exam Schedule (Single Subject) -->
                    <div class="mb-4" id="singleExamDetails" style="display: none;">
                        <h5>
                            <i class="fas fa-calendar-alt"></i>Exam Schedule
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
                                <div class="form-control bg-light" id="examEndTime" style="height: auto; min-height: 45px; display: flex; align-items: center;">
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
                            <i class="fas fa-redo-alt me-2"></i>Reset Form
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
        
        // Load exam names when category is selected
        document.getElementById('categorySelect').addEventListener('change', function() {
            loadExamNames();
        });
        
        // Refresh button for exam names
        document.getElementById('loadExamNamesBtn').addEventListener('click', loadExamNames);
        
        // Exam name selection change
        document.getElementById('examNameSelect').addEventListener('change', function() {
            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                document.getElementById('academicYearDisplay').textContent = 
                    selectedOption.getAttribute('data-academic-year') || 'N/A';
                document.getElementById('examDescriptionDisplay').textContent = 
                    selectedOption.getAttribute('data-description') || 'No description available';
                
                // Store the exam name in hidden input
                const examNameInput = document.getElementById('commonExamName');
                examNameInput.value = selectedOption.textContent.split(' (')[0]; // Remove academic year
                
                // Show common exam name section
                showCommonExamNameSection();
            }
        });
    }

    // Function to load exam names
    function loadExamNames() {
        const categorySelect = document.getElementById('categorySelect');
        const examNameSelect = document.getElementById('examNameSelect');
        const selectExamNameSection = document.getElementById('selectExamNameSection');
        
        if (!categorySelect.value) {
            alert('Please select a category first');
            return;
        }
        
        // Get category type (academic/non-academic) from selected option
        const selectedCategory = categorySelect.options[categorySelect.selectedIndex];
        const categoryName = selectedCategory.textContent.toLowerCase();
        const isAcademic = !categoryName.includes('non-academic') && !categoryName.includes('non_academic');
        const examType = isAcademic ? 'academic' : 'non_academic';
        
        // Show exam name section
        selectExamNameSection.style.display = 'block';
        examNameSelect.disabled = true;
        examNameSelect.innerHTML = '<option value="">Loading exam names...</option>';
        
        // Fetch exam names from server
        fetch(`/ajax/exam-names?type=${examType}`, {
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            examNameSelect.innerHTML = '<option value="" selected disabled>Select Exam Name</option>';
            
            if (data.success && data.exam_names && data.exam_names.length > 0) {
                data.exam_names.forEach(exam => {
                    const option = document.createElement('option');
                    option.value = exam.exam_name_id;
                    option.textContent = `${exam.name} (${exam.academic_year})`;
                    option.setAttribute('data-academic-year', exam.academic_year);
                    option.setAttribute('data-type', exam.type);
                    option.setAttribute('data-description', exam.description || '');
                    option.setAttribute('data-term', exam.term);
                    examNameSelect.appendChild(option);
                });
                examNameSelect.disabled = false;
            } else {
                examNameSelect.innerHTML = '<option value="" selected disabled>No exam names found</option>';
                examNameSelect.disabled = true;
                
                // Show message with link to create exam names
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-warning mt-2';
                alertDiv.innerHTML = `
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No exam names found for ${examType.replace('_', ' ')} exams. 
                    <a href="/institute/exam-names/create?type=${examType}" target="_blank" class="alert-link">
                        Create exam names first
                    </a>
                `;
                selectExamNameSection.appendChild(alertDiv);
            }
        })
        .catch(error => {
            console.error('Error loading exam names:', error);
            examNameSelect.innerHTML = '<option value="" selected disabled>Error loading exam names</option>';
        });
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
        
        // Form field change handlers
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
                // Check if exam name is already selected
                const examNameSelect = document.getElementById('examNameSelect');
                if (examNameSelect && examNameSelect.value) {
                    showCommonExamNameSection();
                } else {
                    // Show exam name selection section first
                    document.getElementById('selectExamNameSection').style.display = 'block';
                    document.getElementById('selectExamNameSection').scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
        
        // Update end time when time or duration changes
        document.getElementById('examTime').addEventListener('change', updateEndTime);
        document.getElementById('examTime').addEventListener('input', updateEndTime);
        document.getElementById('examDuration').addEventListener('change', updateEndTime);
        document.getElementById('examDuration').addEventListener('input', updateEndTime);
    }

    // Function to show common exam name section
    function showCommonExamNameSection() {
        const examNameSelect = document.getElementById('examNameSelect');
        if (!examNameSelect || !examNameSelect.value) {
            alert('Please select an exam name first');
            examNameSelect.focus();
            return;
        }
        
        const commonExamNameSection = document.getElementById('commonExamNameSection');
        commonExamNameSection.style.display = 'block';
        
        // Hide single exam details initially
        document.getElementById('singleExamDetails').style.display = 'none';
        document.getElementById('examSectionsContainer').style.display = 'none';
        
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
        courseSelect.innerHTML = '<option value="" selected class="select-placeholder">Loading classes...</option>';
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
            courseSelect.innerHTML = '<option value="" selected disabled>Select a Class</option>';
            
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
                courseSelect.innerHTML = '<option value="" selected disabled>No classes found</option>';
            }
            
            // Hide loading
            loadingElement.style.display = 'none';
            
            // Reset dependent fields
            resetDependentFields('course');
        })
        .catch(error => {
            console.error('Error loading courses:', error);
            courseSelect.innerHTML = '<option value="" selected disabled>Error loading classes</option>';
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
        const selectExamNameSection = document.getElementById('selectExamNameSection');
        
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
        selectExamNameSection.style.display = 'none';
        
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
                        subjectInfo.innerHTML = `<i class="fas fa-info-circle"></i> ${subjects.length} subject(s) available`;
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
        const selectExamNameSection = document.getElementById('selectExamNameSection');
        
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
                          ` <small>(Semester ${subject.semester_id})</small>` : ''}
                    </label>
                </div>
            `;
            
            grid.appendChild(col);
        });
        
        gridContainer.appendChild(grid);
        multipleSubjectsContainer.appendChild(gridContainer);
        
        // Add selected count display
        const countDiv = document.createElement('div');
        countDiv.className = 'selected-count mt-2';
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
            // Show exam name selection section if any subject is selected
            if (this.checked || document.querySelectorAll('.subject-checkbox:checked').length > 0) {
                selectExamNameSection.style.display = 'block';
                selectExamNameSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
        
        const subjectCheckboxes = document.querySelectorAll('.subject-checkbox');
        subjectCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectedSubjectsCount();
                
                // Show exam name selection section if any subject is selected
                if (document.querySelectorAll('.subject-checkbox:checked').length > 0) {
                    selectExamNameSection.style.display = 'block';
                    selectExamNameSection.scrollIntoView({ behavior: 'smooth' });
                } else {
                    commonExamNameSection.style.display = 'none';
                    selectExamNameSection.style.display = 'none';
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
        const selectExamNameSection = document.getElementById('selectExamNameSection');
        
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
            
            // Show exam name selection section
            if (filteredSubjects.length > 0) {
                selectExamNameSection.style.display = 'block';
            }
        } else {
            // Show subjects for specific semester OR all_semesters
            const filteredSubjects = window.allSubjects.filter(subject => 
                subject.semester_id === semesterId || subject.semester_id === 'all_semesters'
            );
            handleSubjectsDisplay(filteredSubjects);
            subjectInfo.style.display = 'block';
            subjectInfo.innerHTML = `<i class="fas fa-info-circle"></i> ${filteredSubjects.length} subject(s) available for Semester ${semesterId}`;
            
            // Show exam name selection section
            if (filteredSubjects.length > 0) {
                selectExamNameSection.style.display = 'block';
            }
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
            
            // Get exam name from hidden input
            const examNameValue = document.getElementById('commonExamName').value || 'Mid Term Examination';
            
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
                                   value="${examNameValue}"
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
                                 data-subject="${subjectId}" style="height: auto; min-height: 45px; display: flex; align-items: center;">
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
        const selectExamNameSection = document.getElementById('selectExamNameSection');
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
                selectExamNameSection.style.display = 'none';
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
                selectExamNameSection.style.display = 'none';
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
                selectExamNameSection.style.display = 'none';
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
        
        // Check if exam name is selected
        const examNameSelect = document.getElementById('examNameSelect');
        if (!examNameSelect || !examNameSelect.value) {
            alert('Please select an exam name first');
            examNameSelect.focus();
            return;
        }
        
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
        
        // Get exam name details
        const examNameSelect = document.getElementById('examNameSelect');
        if (!examNameSelect || !examNameSelect.value) {
            errors.push('Please select an exam name');
            alert(errors.join('\n'));
            return [];
        }
        
        const examNameId = examNameSelect.value;
        const academicYear = examNameSelect.options[examNameSelect.selectedIndex].getAttribute('data-academic-year');
        const examTerm = examNameSelect.options[examNameSelect.selectedIndex].getAttribute('data-term');
        const examDescription = examNameSelect.options[examNameSelect.selectedIndex].getAttribute('data-description');
        
        examSections.forEach(section => {
            const subjectId = section.dataset.subjectId;
            const subjectName = section.dataset.subjectName;
            
            // Validate required fields
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
                
                // Get exam details
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
                    exam_name_id: examNameId,
                    academic_year: academicYear,
                    exam_term: examTerm,
                    exam_description: examDescription,
                    exam_name: document.querySelector(`.exam-name[data-subject="${subjectId}"]`).value,
                    grade_system_id: gradeSystemId,
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
        
        // Get exam name details
        const examNameSelect = document.getElementById('examNameSelect');
        if (!examNameSelect || !examNameSelect.value) {
            errors.push('Please select an exam name');
            alert(errors.join('\n'));
            return [];
        }
        
        const examNameId = examNameSelect.value;
        const academicYear = examNameSelect.options[examNameSelect.selectedIndex].getAttribute('data-academic-year');
        const examTerm = examNameSelect.options[examNameSelect.selectedIndex].getAttribute('data-term');
        const examDescription = examNameSelect.options[examNameSelect.selectedIndex].getAttribute('data-description');
        
        // Check if we have exam sections (multiple subjects mode)
        const examSections = document.querySelectorAll('.subject-exam-section');
        if (examSections.length > 0) {
            // We're in multiple subjects mode with exam sections
            return prepareMultipleExamsData();
        }
        
        // Original single subject mode
        const requiredFields = [
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
        
        // Get exam name from hidden input
        const examNameValue = document.getElementById('commonExamName').value;
        
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
            exam_name_id: examNameId,
            academic_year: academicYear,
            exam_term: examTerm,
            exam_description: examDescription,
            section_id: selectedSections.map(s => s.section_id).join(','),
            exam_name: examNameValue,
            grade_system_id: gradeSystemId,
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
                    <thead>
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
                    <td><strong>${subjectName}</strong></td>
                    <td>${exam.exam_name}</td>
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
            
            // Reset common exam name hidden input
            document.getElementById('commonExamName').value = "";
            
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
            
            // Reset exam name selection section
            document.getElementById('selectExamNameSection').style.display = 'none';
            
            // Reset exam sections container
            const examSectionsContainer = document.getElementById('examSectionsContainer');
            if (examSectionsContainer) {
                examSectionsContainer.style.display = 'none';
                examSectionsContainer.innerHTML = '';
            }
            
            // Reset single exam details
            document.getElementById('singleExamDetails').style.display = 'none';
            
            // Reset exam name dropdown
            const examNameSelect = document.getElementById('examNameSelect');
            if (examNameSelect) {
                examNameSelect.innerHTML = '<option value="" selected disabled>Select Exam Name</option>';
            }
            
            // Reset info displays
            document.getElementById('subjectInfo').style.display = 'none';
            document.getElementById('subjectLoading').style.display = 'none';
            document.getElementById('academicYearDisplay').textContent = 'Will be auto-filled based on exam name selection';
            document.getElementById('examDescriptionDisplay').textContent = 'No description available';
            
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
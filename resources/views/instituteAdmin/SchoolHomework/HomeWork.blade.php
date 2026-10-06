@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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

        .container-fluid {
            background-color: #f8fafc;
        }

        /* Page Header */
        .page-header {
            background: var(--primary-gradient);
            padding: 20px 25px;
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        }

        .page-header h2 {
            color: white;
            margin: 0;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.5rem;
        }

        .page-header h2 i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 12px;
            font-size: 1.2rem;
        }

        .page-header p {
            margin: 8px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
            color:#fff;
        }

        /* Tabs */
        .tabs {
            display: flex;
            background: white;
            border-radius: 16px;
            margin-bottom: 25px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }
        
        .tab {
            flex: 1;
            padding: 15px 20px;
            text-align: center;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            border-right: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s;
            color: #64748b;
        }
        
        .tab:last-child {
            border-right: none;
        }
        
        .tab:hover {
            background: #f8fafc;
            color: var(--primary-color);
        }
        
        .tab.active {
            background: var(--primary-gradient);
            color: white;
        }
        
        .tab i {
            font-size: 1.1rem;
        }

        /* Content */
        .content {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }
        
        .page {
            display: none;
        }
        
        .page.active {
            display: block;
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #475569;
            font-weight: 600;
            font-size: 13px;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        label i {
            color: var(--primary-color);
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s;
            background: white;
        }
        
        input:focus, select:focus, textarea:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
            background: white;
        }
        
        textarea {
            height: 100px;
            resize: vertical;
        }

        .btnn {
            background: var(--primary-gradient);
            color: white !important;
            padding: 12px 30px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .btnn:active {
            transform: translateY(0);
        }
        
        .btn-small {
            padding: 8px 20px;
            font-size: 13px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .full-width {
            grid-column: 1 / -1;
        }
        
        /* Homework Item */
        .homework-item {
            background: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
            transition: all 0.3s;
        }
        
        .homework-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            border-left-color: var(--secondary-color);
        }
        
        .meta {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .meta i {
            margin-right: 6px;
            color: var(--primary-color);
            opacity: 0.8;
        }
        
        .status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 0;
        }
        
        .status.created {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .status.assigned {
            background: #d1fae5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }
        
        /* Student Container */
        .student-container {
            max-height: 350px;
            overflow-y: auto;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            margin-top: 10px;
            background: #f8fafc;
        }
        
        .student-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 15px;
        }
        
        .student-card {
            background: white;
            padding: 15px;
            border-radius: 14px;
            border: 2px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
        }
        
        .student-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.1);
        }
        
        .student-card.selected {
            border-color: var(--primary-color);
            background: #eff6ff;
            box-shadow: 0 2px 8px rgba(67, 97, 238, 0.15);
        }
        
        .student-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }
        
        .student-info {
            flex: 1;
        }
        
        .student-name {
            font-weight: 700;
            font-size: 14px;
            color: #1e293b;
            margin-bottom: 4px;
        }
        
        .student-details {
            color: #64748b;
            font-size: 11px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .student-details i {
            margin-right: 4px;
            color: var(--primary-color);
            opacity: 0.7;
        }
        
        .student-roll {
            font-family: 'Courier New', monospace;
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 10px;
        }
        
        .selected-indicator {
            width: 22px;
            height: 22px;
            border-radius: 6px;
            border: 2px solid #cbd5e1;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        
        .student-card.selected .selected-indicator {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .student-card.selected .selected-indicator::after {
            content: "✓";
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
        
        .selection-controls {
            padding: 15px 20px;
            background: #eff6ff;
            border-radius: 14px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #dbeafe;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .selection-controls-buttons {
            display: flex;
            gap: 10px;
        }
        
        .hierarchy-info {
            background: #eff6ff;
            padding: 15px 20px;
            border-radius: 14px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid #dbeafe;
        }
        
        .hierarchy-info i {
            color: var(--primary-color);
            font-size: 18px;
        }
        
        .hierarchy-info span {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px;
            color: #94a3b8;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }
        
        .counter-badge {
            background: var(--primary-color);
            color: white;
            padding: 2px 10px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 8px;
        }
        
        /* Month Tabs */
        .month-tabs-container {
            margin-bottom: 25px;
        }
        
        .month-tabs {
            display: flex;
            background: white;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            flex-wrap: wrap;
        }
        
        .month-tab {
            padding: 12px 24px;
            text-align: center;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            border-right: 1px solid #e2e8f0;
            flex: 1;
            min-width: 100px;
            transition: all 0.3s;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .month-tab:last-child {
            border-right: none;
        }
        
        .month-tab:hover {
            background: #f8fafc;
        }
        
        .month-tab.active {
            background: var(--primary-gradient);
            color: white;
        }
        
        .month-tab .month-count {
            background: rgba(255,255,255,0.2);
            padding: 2px 8px;
            border-radius: 30px;
            font-size: 11px;
        }
        
        .month-tab.active .month-count {
            background: rgba(255,255,255,0.3);
        }
        
        /* Week Section */
        .week-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
        }
        
        .week-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .week-section-title {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .week-section-title i {
            color: var(--primary-color);
        }
        
        .week-section-count {
            background: var(--primary-color);
            color: white;
            padding: 4px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
        }
        
        /* Statistics Cards */
        .homework-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 13px;
            color: #64748b;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        
        .stat-icon {
            font-size: 28px;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        /* Homework Actions */
        .homework-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .action-btn {
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 12px;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .action-btn.view {
            background: #eff6ff;
            color: var(--primary-color);
            border: 1px solid #dbeafe;
        }
        
        .action-btn.view:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .action-btn.edit {
            background: #fffbeb;
            color: #d97706;
            border: 1px solid #fde68a;
        }
        
        .action-btn.edit:hover {
            background: #d97706;
            color: white;
        }
        
        .action-btn.delete {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .action-btn.delete:hover {
            background: #dc2626;
            color: white;
        }
        
        /* Loading Spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 15px;
            }
            
            .content {
                padding: 20px;
            }
            
            .tabs {
                flex-direction: column;
            }
            
            .tab {
                border-right: none;
                border-bottom: 1px solid #e2e8f0;
            }
            
            .tab:last-child {
                border-bottom: none;
            }
            
            .student-grid {
                grid-template-columns: 1fr;
            }
            
            .selection-controls {
                flex-direction: column;
                text-align: center;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .month-tab {
                min-width: 80px;
                padding: 10px 12px;
                font-size: 12px;
            }
            
            .homework-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h2>
                <i class="bi bi-journal-bookmark-fill"></i>
                Homework
            </h2>
            <p>Create, assign and track homework efficiently</p>
        </div>
        
        <!-- Tabs -->
        <div class="tabs">
            <button class="tab active" data-page="create">
                <i class="bi bi-plus-circle-fill"></i> Create Homework
            </button>
            <button class="tab" data-page="assign">
                <i class="bi bi-person-check-fill"></i> Assign to Students
            </button>
            <button class="tab" data-page="view">
                <i class="bi bi-list-ul"></i> View Homework
            </button>
        </div>
        
        <div class="content">
            <!-- Create Homework -->
            <div id="create-page" class="page active">
                <h2><i class="bi bi-pencil-square"></i> Create New Homework</h2>
                <form id="homework-form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="bi bi-people-fill"></i> Class *</label>
                            <select id="course_id" name="course_id" required>
                                <option value="">Select Class</option>
                            </select>
                        </div>
                        <input type="hidden" id="department_id" value="{{ $departments->department_id }}">
                        <div class="form-group">
                            <label><i class="bi bi-diagram-3-fill"></i> Branch *</label>
                            <select id="branch_id" name="branch_id" required>
                                <option value="">Select Branch</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="bi bi-book-fill"></i> Subject *</label>
                            <select id="subject_id" name="subject_id" required>
                                <option value="">Select Subject</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><i class="bi bi-grid-3x3-gap-fill"></i> Section *</label>
                            <select id="section_id" name="section_id" required>
                                <option value="">Select Section</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row" id="semester_container" style="display:none;">
                        <div class="form-group">
                            <label><i class="bi bi-layers-fill"></i> Semester</label>
                            <select id="semester_id" name="semester_id"></select>
                        </div>
                        <div id="semester_info" style="margin-top:5px; color:#64748b; font-size:12px;"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label><i class="bi bi-heading"></i> Homework Title *</label>
                            <input type="text" id="title" name="title" required placeholder="Enter homework title">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label><i class="bi bi-calendar-event-fill"></i> Due Date *</label>
                            <input type="datetime-local" id="due_date" name="due_date" required>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label><i class="bi bi-file-text-fill"></i> Instructions *</label>
                        <textarea id="instructions" name="instructions" required placeholder="Provide detailed instructions for the homework..." rows="5"></textarea>
                    </div>

                    <button type="submit" class="btn btnn" id="create-btn">
                        <i class="bi bi-save-fill"></i> Create Homework
                    </button>
                </form>
            </div>
            
            <!-- Assign to Students -->
            <div id="assign-page" class="page">
                <h2><i class="bi bi-person-check-fill"></i> Assign Homework to Students</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="bi bi-list-task"></i> Select Homework *</label>
                        <select id="homework-select">
                            <option value="">Select homework to assign</option>
                        </select>
                    </div>
                </div>
                
                <div class="hierarchy-info" id="hierarchy-info">
                    <i class="bi bi-diagram-3-fill"></i>
                    <span id="hierarchy-text">Select homework to see hierarchy details</span>
                </div>
                
                <div class="form-group">
                    <label><i class="bi bi-people-fill"></i> Select Students <span class="counter-badge" id="selected-count">0 selected</span></label>
                    
                    <div class="selection-controls">
                        <div style="font-weight: 600; color: #475569;">
                            <i class="bi bi-person-check-fill"></i> Student Selection Controls
                        </div>
                        <div class="selection-controls-buttons">
                            <button type="button" class="btn btnn btn-small" id="select-all-btn">
                                <i class="bi bi-check-all"></i> Select All 
                            </button>
                            <button type="button" class="btn btnn btn-small" id="clear-all-btn" style="background: linear-gradient(135deg, #94a3b8, #64748b);">
                                <i class="bi bi-x-circle-fill"></i> Clear All
                            </button>
                        </div>
                    </div>
                    
                    <div class="student-container" id="student-container">
                        <div class="empty-state">
                            <i class="bi bi-person-video3"></i>
                            <p>Select a homework to see students</p>
                        </div>
                    </div>
                </div>
                
                <button class="btn btnn" id="assign-btn">
                    <i class="bi bi-send-fill"></i> Assign to Selected Students 
                </button>
            </div>
            
            <!-- View Homework -->
            <div id="view-page" class="page">
                <h2><i class="bi bi-list-ul"></i> Homework Assignments</h2>
                
                <!-- Statistics -->
                <div class="homework-stats">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-journal-bookmark-fill"></i></div>
                        <div class="stat-value" id="total-homework">0</div>
                        <div class="stat-label">Total Homework</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
                        <div class="stat-value" id="assigned-homework">0</div>
                        <div class="stat-label">Assigned</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-clock-history"></i></div>
                        <div class="stat-value" id="pending-homework">0</div>
                        <div class="stat-label">Pending</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-person-arms-up"></i></div>
                        <div class="stat-value" id="total-students">0</div>
                        <div class="stat-label">Students Assigned</div>
                    </div>
                </div>
                
                <!-- Month View Container -->
                <div id="month-view-container" class="view-container active">
                    <!-- Month Tabs -->
                    <div class="month-tabs-container">
                        <div class="month-tabs" id="month-tabs">
                            <!-- Months will be dynamically generated -->
                        </div>
                    </div>
                    
                    <!-- Month Content -->
                    <div id="month-content-container">
                        <!-- Content will be dynamically loaded -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            const departmentId = $('#department_id').val();
            let instituteType = $('#institute_type').val() || 'Institute';
            let subjectDistributionType = 'unknown';
            let homeworkList = []; // This will store homework data from backend
            let currentSelectedHomework = null; // Store currently selected homework for assignment

            // CSRF token setup for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // --------------------------
            // Utilities
            // --------------------------
            function populateSelect($el, items, valueKey, textKey, defaultText = 'Select') {
                let options = `<option value="">${defaultText}</option>`;
                items.forEach(item => {
                    options += `<option value="${item[valueKey]}">${item[textKey]}</option>`;
                });
                $el.html(options);
            }

            function setEmptyState($container, message) {
                $container.html(`<div class="empty-state"><i class="bi bi-person-video3"></i><p>${message}</p></div>`);
            }

            function populateSubjects(subjects) {
                const $subject = $('#subject_id');
                $subject.html('<option value="">Select Subject</option>');
                subjects.forEach(sub => {
                    $subject.append(`<option value="${sub.subject_id}">${sub.subject_name}</option>`);
                });
            }

            function showNotification(type, message) {
                const icon = type === 'success' ? 'bi-check-circle-fill' : 
                            type === 'error' ? 'bi-exclamation-circle-fill' : 
                            type === 'warning' ? 'bi-exclamation-triangle-fill' : 'bi-info-circle-fill';
                const color = type === 'success' ? '#10b981' : 
                            type === 'error' ? '#ef4444' : 
                            type === 'warning' ? '#f59e0b' : '#4361ee';
                
                const div = document.createElement('div');
                div.style.cssText = `
                    position: fixed;
                    top: 25px;
                    right: 25px;
                    background: white;
                    color: ${color};
                    padding: 15px 20px;
                    border-radius: 12px;
                    border-left: 4px solid ${color};
                    z-index: 1000;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    min-width: 320px;
                    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                    animation: slideIn 0.3s ease-out;
                `;
                
                div.innerHTML = `<i class="bi ${icon}" style="font-size: 20px;"></i><div style="flex: 1;">${message}</div>`;
                document.body.appendChild(div);
                setTimeout(() => { 
                    div.style.animation = 'slideOut 0.3s ease-in'; 
                    setTimeout(() => div.remove(), 300); 
                }, 3000);
            }

            // Add CSS for animations
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideIn { 
                    from { transform: translateX(100%); opacity: 0; } 
                    to { transform: translateX(0); opacity: 1; } 
                }
                @keyframes slideOut { 
                    from { transform: translateX(0); opacity: 1; } 
                    to { transform: translateX(100%); opacity: 0; } 
                }
            `;
            document.head.appendChild(style);

            // --------------------------
            // 1️⃣ Load Courses (Classes)
            // --------------------------
            function loadCourses() {
                if (!departmentId) return;

                $.getJSON("{{ route('ajax.course.types.by.department') }}", { 
                    department_id: departmentId 
                })
                .done(response => {
                    const courses = response.courses || [];
                    populateSelect($('#course_id'), courses, 'finacp_merchant_sub_category_id', 'finacp_merchant_sub_category_type', 'Select Class');
                })
                .fail(() => showNotification('error', 'Failed to load classes'));
            }

            loadCourses();

            // --------------------------
            // 2️⃣ Course Change → Load Branches & Subjects
            // --------------------------
            $('#course_id').on('change', function () {
                const courseDetailId = $(this).val();
                const courseName = $(this).find('option:selected').text();

                // Reset dependent selects
                populateSelect($('#branch_id'), [], 'product_id', 'sub_type', 'Select Branch');
                populateSelect($('#subject_id'), [], 'subject_id', 'subject_name', 'Select Subject');
                populateSelect($('#section_id'), [], 'section_id', 'section_name', 'Select Section');
                setEmptyState($('#student-container'), 'Select a section to see students');

                if (!courseDetailId) return;

                // -------- Branches --------
                $.getJSON("{{ route('ajax.branches.by.course') }}", {
                    department_id: departmentId,
                    course_type: courseName
                })
                .done(response => {
                    const branches = response.branches || [];
                    populateSelect($('#branch_id'), branches, 'product_id', 'sub_type', 'Select Branch');
                    if (branches.length === 1) {
                        $('#branch_id').val(branches[0].product_id).trigger('change');
                    }
                })
                .fail(() => showNotification('error', 'Failed to load branches'));

                // -------- Subjects (semester-aware) --------
                loadSubjectsSemesterAware(courseDetailId, courseName);
            });

            // --------------------------
            // 3️⃣ Load Subjects (Semester-Aware)
            // --------------------------
            function loadSubjectsSemesterAware(courseDetailId, courseName) {
                $.getJSON("/ajax/get-subjects-by-course", { 
                    course_detail_id: $('#branch_id').val()
                })
                .done(data => {
                    const subjects = Array.isArray(data.subjects) ? data.subjects : [];
                    const $subject = $('#subject_id');
                    $subject.html('<option value="">Select Subject</option>');

                    if (!subjects.length) {
                        $subject.html('<option value="">No subjects available for this course</option>');
                        $('#semester_container').hide();
                        $('#semester_info').hide();
                        return;
                    }

                    const allAllSemesters = subjects.every(sub => sub.semester_id === 'all_semesters');
                    const allSpecificSemesters = subjects.every(sub => sub.semester_id && sub.semester_id !== 'all_semesters');
                    const hasAllSemesters = subjects.some(sub => sub.semester_id === 'all_semesters');
                    const hasSpecificSemesters = subjects.some(sub => sub.semester_id && sub.semester_id !== 'all_semesters');

                    const semesterContainer = $('#semester_container');
                    const semesterInfo = $('#semester_info');

                    if (allAllSemesters) {
                        subjectDistributionType = 'all_semesters';
                        semesterContainer.hide();
                        semesterInfo.show().text('Subjects are available for all semesters');
                        populateSubjects(subjects);
                    } else if (allSpecificSemesters) {
                        subjectDistributionType = 'specific_semesters';
                        semesterContainer.show();
                        semesterInfo.show().text('Please select a semester to view subjects');
                        populateSemesterSelect(subjects);
                        $subject.html('<option value="">Select semester first</option>');
                    } else if (hasAllSemesters && hasSpecificSemesters) {
                        subjectDistributionType = 'mixed';
                        semesterContainer.show();
                        semesterInfo.show().html('<i class="bi bi-info-circle"></i> Mixed subject types. Select a semester or "All Semesters"');
                        populateSemesterSelect(subjects);
                        const semesterSelect = document.getElementById('semester_id');
                        const allSemOption = document.createElement('option');
                        allSemOption.value = 'all_semesters';
                        allSemOption.textContent = 'All Semesters';
                        semesterSelect.insertBefore(allSemOption, semesterSelect.firstChild.nextSibling);
                        $subject.html('<option value="">Select semester first</option>');
                    } else {
                        subjectDistributionType = 'unknown';
                        semesterContainer.hide();
                        populateSubjects(subjects);
                    }
                })
                .fail(() => showNotification('error', 'Failed to load subjects'));
            }

            // --------------------------
            // Populate Semester Dropdown
            // --------------------------
            function populateSemesterSelect(subjects) {
                const semesters = [...new Set(subjects
                    .filter(s => s.semester_id && s.semester_id !== 'all_semesters')
                    .map(s => s.semester_id)
                )];
                
                const $semester = $('#semester_id');
                $semester.html('<option value="">-- Select Semester --</option>');
                semesters.forEach(sem => $semester.append(`<option value="${sem}">${sem}</option>`));
            }

            // --------------------------
            // Semester Change → Load subjects
            // --------------------------
            $('#semester_id').on('change', function () {
                const selectedSemester = $(this).val();
                const courseDetailId = $('#course_id').val();
                if (!courseDetailId || !selectedSemester) return;

                $.getJSON("{{ route('ajax.subjects.by.course.semester') }}", {
                    course_detail_id: courseDetailId,
                    semester_id: selectedSemester
                })
                .done(data => {
                    if (data.status === 'success') {
                        const subjects = Array.isArray(data.subjects) ? data.subjects : [];
                        populateSubjects(subjects);
                    } else {
                        showNotification('error', data.message || 'Failed to load subjects');
                    }
                })
                .fail(() => showNotification('error', 'Failed to load subjects for selected semester'));
            });

            // --------------------------
            // 4️⃣ Branch Change → Load Sections
            // --------------------------
            $('#branch_id').on('change', function () {
                const branchId = $(this).val();
                populateSelect($('#section_id'), [], 'section_id', 'section_name', 'Select Section');
                setEmptyState($('#student-container'), 'Select a section to see students');

                if (!branchId) return;

                $.getJSON("{{ url('ajax/get-sections-by-branch') }}", { branch_id: branchId })
                .done(response => {
                    const sections = response.sections || [];
                    populateSelect($('#section_id'), sections, 'section_id', 'section_name', 'Select Section');
                })
                .fail(() => showNotification('error', 'Failed to load sections'));

                // Reload subjects if branch changes
                const courseDetailId = $('#course_id').val();
                if (courseDetailId) {
                    const courseName = $('#course_id option:selected').text();
                    loadSubjectsSemesterAware(courseDetailId, courseName);
                }
            });

            // --------------------------
            // 5️⃣ CREATE HOMEWORK FORM SUBMISSION
            // --------------------------
            $('#homework-form').on('submit', function(e) {
                e.preventDefault();
                
                const formData = $(this).serialize();
                const submitBtn = $('#create-btn');
                const originalBtnText = submitBtn.html();
                
                // Show loading state
                submitBtn.prop('disabled', true);
                submitBtn.html('<span class="loading-spinner"></span> Creating...');
                
                $.ajax({
                    url: "{{ route('homework.store') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            showNotification('success', `Homework "${response.homework.title}" created successfully!`);
                            $('#homework-form')[0].reset();
                            setDefaultDueDate();
                            
                            // Refresh homework list and populate homework select
                            loadHomeworks();
                            populateHomeworkSelect();
                            
                            // Switch to assign tab after a delay
                            setTimeout(() => {
                                document.querySelector('[data-page="assign"]').click();
                            }, 500);
                        } else {
                            showNotification('error', response.message || 'Failed to create homework');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to create homework. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showNotification('error', errorMessage);
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                        submitBtn.html(originalBtnText);
                    }
                });
            });

            // --------------------------
            // 6️⃣ LOAD HOMEWORKS FROM BACKEND
            // --------------------------
            function loadHomeworks() {
                $.ajax({
                    url: "{{ route('homework.list') }}",
                    type: 'GET',
                    data: {
                        department_id: departmentId,
                        // Add other filters if needed
                    },
                    success: function(response) {
                        if (response.success) {
                            homeworkList = response.homeworks || [];
                            updateStatistics(response.statistics);
                            initializeMonthTabs();
                            displayHomeworkByMonth();
                            populateHomeworkSelect();
                        } else {
                            showNotification('error', response.message || 'Failed to load homework');
                        }
                    },
                    error: function() {
                        showNotification('error', 'Failed to load homework data');
                    }
                });
            }

            // Initial load
            loadHomeworks();

            // --------------------------
            // 7️⃣ POPULATE HOMEWORK SELECT FOR ASSIGNMENT
            // --------------------------
            function populateHomeworkSelect() {
                const select = $('#homework-select');
                select.html('<option value="">Select homework to assign</option>');

                // Filter only created (unassigned) homeworks
                const createdHomeworks = homeworkList.filter(h => h.status === 'created');
                
                createdHomeworks.forEach(homework => {
                    const option = document.createElement('option');
                    option.value = homework.id;
                    option.textContent = `${homework.title} (${homework.classText} → ${homework.branchText} → ${homework.sectionText} → ${homework.subjectText})`;
                    option.dataset.homework = JSON.stringify(homework);
                    select.append(option);
                });

                if (createdHomeworks.length === 0) {
                    select.append('<option value="" disabled>No unassigned homework available</option>');
                }
            }

            // --------------------------
            // 8️⃣ HOMEWORK SELECTION FOR ASSIGNMENT
            // --------------------------
            $('#homework-select').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                if (!selectedOption.val()) {
                    $('#hierarchy-info').html('<i class="bi bi-diagram-3-fill"></i><span>Select homework to see hierarchy details</span>');
                    setEmptyState($('#student-container'), 'Select a homework to see students');
                    currentSelectedHomework = null;
                    return;
                }

                const homework = selectedOption.data('homework');
                currentSelectedHomework = homework;
                
                // Show hierarchy info
                $('#hierarchy-info').html(`
                    <i class="bi bi-diagram-3-fill"></i>
                    <span>
                        ${homework.classText} →
                        ${homework.branchText} →
                        ${homework.sectionText} →
                        ${homework.subjectText}
                    </span>
                `);

                // Load students for this homework
                loadStudentsForHomework(homework);
            });

            // --------------------------
            // 9️⃣ LOAD STUDENTS FOR HOMEWORK
            // --------------------------
            function loadStudentsForHomework(homework) {
                console.log('HOMEWORK OBJECT:', homework);
                const container = $('#student-container');
                
                container.html(`
                    <div class="empty-state">
                        <i class="bi bi-arrow-repeat spinning"></i>
                        <p>Loading students...</p>
                    </div>
                `);
                
                updateSelectedCount(0);
                
                $.getJSON("{{ route('ajax.students.by.product') }}", {
                    course_id: homework.class,
                    product_id: homework.product_id,
                    section_id: homework.section
                })
                .done(response => {
                    const students = response.students || [];
                    
                    if (students.length === 0) {
                        container.html(`
                            <div class="empty-state">
                                <i class="bi bi-person-slash"></i>
                                <p>No students found for this hierarchy</p>
                            </div>
                        `);
                        return;
                    }
                    
                    // Create student grid
                    container.html('<div class="student-grid" id="student-grid"></div>');
                    const grid = $('#student-grid');
                    
                    students.forEach(student => {
                        const fullName = `${student.first_name || ''} ${student.middle_name || ''} ${student.last_name || ''}`.trim();
                        const initials = fullName.split(' ')
                            .map(name => name[0])
                            .join('')
                            .toUpperCase();
                        
                        // Check if student is already assigned to this homework
                        const isAssigned = homework.assignedStudents && 
                                          homework.assignedStudents.includes(student.student_hash_id);
                        
                        const card = $(`
                            <div class="student-card ${isAssigned ? 'selected' : ''}" 
                                 data-id="${student.student_hash_id}"
                                 data-selected="${isAssigned}">
                                <div class="student-avatar">${initials}</div>
                                <div class="student-info">
                                    <div class="student-name">${fullName}</div>
                                    <div class="student-details">
                                        <span class="student-roll"><i class="bi bi-upc-scan"></i> Roll: ${student.registration_number || 'N/A'}</span>
                                    </div>
                                    <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">
                                        <i class="bi bi-book-fill"></i> ${homework.subjectText}
                                    </div>
                                </div>
                                <div class="selected-indicator"></div>
                                <input type="checkbox" class="student-checkbox" ${isAssigned ? 'checked' : ''} hidden>
                            </div>
                        `);
                        
                        card.on('click', function() {
                            const $this = $(this);
                            $this.toggleClass('selected');
                            const isNowSelected = $this.hasClass('selected');
                            $this.data('selected', isNowSelected);
                            $this.find('.student-checkbox').prop('checked', isNowSelected);
                            
                            // Update selected indicator
                            const indicator = $this.find('.selected-indicator');
                            
                            updateSelectedCount();
                        });
                        
                        grid.append(card);
                    });
                    
                    updateSelectedCount();
                    setupStudentControls();
                })
                .fail(() => {
                    container.html(`
                        <div class="empty-state">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <p>Failed to load students. Please try again.</p>
                        </div>
                    `);
                });
            }

            // --------------------------
            // 🔟 ASSIGN HOMEWORK TO STUDENTS
            // --------------------------
            $('#assign-btn').on('click', function() {
                if (!currentSelectedHomework) {
                    showNotification('error', 'Please select a homework first');
                    return;
                }
                
                const selectedStudents = $('.student-card.selected').map(function() {
                    return $(this).data('id');
                }).get();
                
                if (selectedStudents.length === 0) {
                    showNotification('error', 'Please select at least one student');
                    return;
                }
                
                const assignBtn = $(this);
                const originalBtnText = assignBtn.html();
                
                assignBtn.prop('disabled', true);
                assignBtn.html('<span class="loading-spinner"></span> Assigning...');
                
                $.ajax({
                    url: "{{ route('homework.assign') }}",
                    type: 'POST',
                    data: {
                        homework_id: currentSelectedHomework.id,
                        student_hash_ids: selectedStudents
                    },
                    success: function(response) {
                        if (response.success) {
                            showNotification('success', response.message);
                            
                            // Refresh homework list
                            loadHomeworks();
                            populateHomeworkSelect();
                            
                            // Switch to view tab
                            setTimeout(() => {
                                document.querySelector('[data-page="view"]').click();
                            }, 500);
                        } else {
                            showNotification('error', response.message || 'Failed to assign homework');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to assign homework. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showNotification('error', errorMessage);
                    },
                    complete: function() {
                        assignBtn.prop('disabled', false);
                        assignBtn.html(originalBtnText);
                    }
                });
            });

            // --------------------------
            // 🎯 STUDENT SELECTION CONTROLS
            // --------------------------
            function setupStudentControls() {
                $('#select-all-btn').off('click').on('click', function() {
                    $('.student-card').each(function() {
                        const $card = $(this);
                        if (!$card.hasClass('selected')) {
                            $card.click();
                        }
                    });
                });
                
                $('#clear-all-btn').off('click').on('click', function() {
                    $('.student-card.selected').each(function() {
                        $(this).click();
                    });
                });
            }

            function updateSelectedCount() {
                const count = $('.student-card.selected').length;
                $('#selected-count').text(`${count} selected`);
            }

            // --------------------------
            // 📊 VIEW HOMEWORK FUNCTIONS
            // --------------------------
            function updateStatistics(stats) {
                if (stats) {
                    $('#total-homework').text(stats.total || 0);
                    $('#assigned-homework').text(stats.assigned || 0);
                    $('#pending-homework').text(stats.created || 0);
                    $('#total-students').text(stats.total_students || 0);
                }
            }

            // Months for tabs
            const months = [
                { id: 'jan', name: 'January', short: 'Jan', index: 0 },
                { id: 'feb', name: 'February', short: 'Feb', index: 1 },
                { id: 'mar', name: 'March', short: 'Mar', index: 2 },
                { id: 'apr', name: 'April', short: 'Apr', index: 3 },
                { id: 'may', name: 'May', short: 'May', index: 4 },
                { id: 'jun', name: 'June', short: 'Jun', index: 5 },
                { id: 'jul', name: 'July', short: 'Jul', index: 6 },
                { id: 'aug', name: 'August', short: 'Aug', index: 7 },
                { id: 'sep', name: 'September', short: 'Sep', index: 8 },
                { id: 'oct', name: 'October', short: 'Oct', index: 9 },
                { id: 'nov', name: 'November', short: 'Nov', index: 10 },
                { id: 'dec', name: 'December', short: 'Dec', index: 11 }
            ];

            // Initialize month tabs
            function initializeMonthTabs() {
                const monthTabs = $('#month-tabs');
                monthTabs.html('');
                
                const currentYear = new Date().getFullYear();
                const currentMonth = new Date().getMonth();
                
                // Show only current and next month
                const monthsToShow = [
                    months[currentMonth],
                    months[(currentMonth + 1) % 12]
                ];
                
                monthsToShow.forEach(month => {
                    const monthHomework = homeworkList.filter(homework => {
                        const dueDate = new Date(homework.dueDate);
                        return dueDate.getMonth() === month.index && dueDate.getFullYear() === currentYear;
                    });
                    
                    const tab = $(`
                        <button class="month-tab" data-month="${month.id}">
                            <i class="bi bi-calendar-month-fill"></i>
                            ${month.name} ${currentYear}
                            <span class="month-count" id="count-${month.id}">${monthHomework.length}</span>
                        </button>
                    `);
                    
                    tab.on('click', function() {
                        $('.month-tab').removeClass('active');
                        $(this).addClass('active');
                        displayMonthContent($(this).data('month'));
                    });
                    
                    monthTabs.append(tab);
                });
                
                // Set first month as active
                const firstTab = monthTabs.find('.month-tab').first();
                if (firstTab.length) {
                    firstTab.addClass('active');
                    displayMonthContent(firstTab.data('month'));
                }
            }

            // Display month content
            function displayMonthContent(monthId) {
                const container = $('#month-content-container');
                const month = months.find(m => m.id === monthId);
                
                // Filter homework for this month
                const currentYear = new Date().getFullYear();
                const monthHomework = homeworkList.filter(homework => {
                    const dueDate = new Date(homework.dueDate);
                    return dueDate.getMonth() === month.index && dueDate.getFullYear() === currentYear;
                });
                
                // Apply filters
                const filteredHomework = applyFilters(monthHomework);
                
                // Update month count
                $(`#count-${monthId}`).text(filteredHomework.length);
                
                if (filteredHomework.length === 0) {
                    container.html(`
                        <div class="empty-state">
                            <i class="bi bi-inbox-fill"></i>
                            <p>No homework for ${month.name} ${currentYear}</p>
                        </div>
                    `);
                    return;
                }
                
                // Get weeks in this month
                const weeksInMonth = getWeeksInMonth(month.index, currentYear);
                let weeksHTML = '';
                
                weeksInMonth.forEach((week, weekIndex) => {
                    const weekHomework = filteredHomework.filter(homework => {
                        const dueDate = new Date(homework.dueDate);
                        return dueDate >= week.start && dueDate <= week.end;
                    });
                    
                    if (weekHomework.length > 0) {
                        weeksHTML += `
                            <div class="week-section">
                                <div class="week-section-header">
                                    <div class="week-section-title">
                                        <i class="bi bi-calendar-week-fill"></i> 
                                        Week ${weekIndex + 1}: ${formatDate(week.start)} - ${formatDate(week.end)}
                                    </div>
                                    <span class="week-section-count">${weekHomework.length} assignments</span>
                                </div>
                                <div class="homework-list-week">
                                    ${weekHomework.map(homework => createHomeworkItemHTML(homework)).join('')}
                                </div>
                            </div>
                        `;
                    }
                });
                
                container.html(`
                    <div class="month-content active">
                        <h3 style="color: #1e293b; margin-bottom: 20px; font-weight: 700;">
                            <i class="bi bi-calendar3"></i> ${month.name} ${currentYear}
                            <span style="font-size: 14px; color: #64748b; margin-left: 10px; font-weight: 400;">
                                (${filteredHomework.length} homework assignments)
                            </span>
                        </h3>
                        ${weeksHTML || `<div class="empty-state" style="padding: 20px;"><i class="bi bi-inbox-fill"></i><p>No homework for this month</p></div>`}
                    </div>
                `);
            }

            // Get weeks in a specific month
            function getWeeksInMonth(monthIndex, year) {
                const weeks = [];
                const firstDay = new Date(year, monthIndex, 1);
                const lastDay = new Date(year, monthIndex + 1, 0);
                
                let currentWeekStart = new Date(firstDay);
                
                while (currentWeekStart <= lastDay) {
                    const weekEnd = new Date(currentWeekStart);
                    weekEnd.setDate(currentWeekStart.getDate() + 6);
                    
                    if (weekEnd >= firstDay) {
                        const weekStartInMonth = currentWeekStart < firstDay ? firstDay : currentWeekStart;
                        const weekEndInMonth = weekEnd > lastDay ? lastDay : weekEnd;
                        
                        weeks.push({
                            start: new Date(weekStartInMonth),
                            end: new Date(weekEndInMonth)
                        });
                    }
                    
                    currentWeekStart.setDate(currentWeekStart.getDate() + 7);
                }
                
                return weeks;
            }

            // Create homework item HTML
            function createHomeworkItemHTML(homework) {
                const dueDate = new Date(homework.dueDate);
                const statusClass = homework.status === 'assigned' ? 'assigned' : 'created';
                const statusIcon = homework.status === 'assigned' ? 'bi-check-circle-fill' : 'bi-clock-history';
                const statusText = homework.status === 'assigned' ? 
                    `Assigned to ${homework.assignedTo} students` : 
                    'Not assigned yet';
                
                return `
                    <div class="homework-item">
                        <div style="font-weight: 700; color: #1e293b; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                            <span><i class="bi bi-book-fill" style="color: var(--primary-color);"></i> ${homework.title}</span>
                            <span class="status ${statusClass}" style="margin: 0;">
                                <i class="bi ${statusIcon}"></i> ${statusText}
                            </span>
                        </div>
                        
                        <div class="meta">
                            <span><i class="bi bi-people-fill"></i> ${homework.classText}</span>
                            <span><i class="bi bi-diagram-3-fill"></i> ${homework.branchText}</span>
                            <span><i class="bi bi-book-fill"></i> ${homework.subjectText}</span>
                            <span><i class="bi bi-grid-3x3-gap-fill"></i> ${homework.sectionText}</span>
                            <span><i class="bi bi-calendar-event-fill"></i> Due: ${dueDate.toLocaleDateString()} ${dueDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                        </div>
                        
                        <div style="color: #64748b; font-size: 13px; margin-bottom: 12px; line-height: 1.5;">
                            <i class="bi bi-file-text-fill" style="color: var(--primary-color);"></i> ${homework.instructions.substring(0, 100)}${homework.instructions.length > 100 ? '...' : ''}
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                            <div style="font-size: 12px; color: #94a3b8;">
                                <span><i class="bi bi-clock-fill"></i> Created: ${homework.createdAt}</span>
                                ${homework.assignedAt ? `<span style="margin-left: 15px;"><i class="bi bi-send-fill"></i> Assigned: ${homework.assignedAt}</span>` : ''}
                            </div>
                            
                            <div class="homework-actions">
                                <button class="action-btn view" onclick="viewHomework(${homework.id})">
                                    <i class="bi bi-eye-fill"></i> View
                                </button>
                                ${homework.status === 'created' ? `
                                    <button class="action-btn edit d-none" onclick="editHomework(${homework.id})">
                                        <i class="bi bi-pencil-fill"></i> Edit
                                    </button>
                                ` : ''}
                                <button class="action-btn delete" onclick="deleteHomework(${homework.id})">
                                    <i class="bi bi-trash-fill"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Apply filters
            function applyFilters(homeworkArray) {
                const department = $('#filter-department').val();
                const status = $('#filter-status').val();
                const classFilter = $('#filter-class').val();
                
                return homeworkArray.filter(homework => {
                    let pass = true;
                    
                    if (department && homework.department !== department) pass = false;
                    if (status && homework.status !== status) pass = false;
                    if (classFilter && homework.class !== classFilter) pass = false;
                    
                    return pass;
                });
            }

            function filterHomework() {
                const activeView = $('.view-toggle-btn.active').data('view');
                if (activeView === 'month') {
                    displayHomeworkByMonth();
                } else {
                    const activeWeekTab = $('.week-tab.active');
                    if (activeWeekTab.length) {
                        displayWeekContent(parseInt(activeWeekTab.data('weekIndex')));
                    }
                }
            }

            function resetFilters() {
                $('#filter-department, #filter-status, #filter-class').val('');
                filterHomework();
            }

            function displayHomeworkByMonth() {
                const currentYear = new Date().getFullYear();
                const currentMonth = new Date().getMonth();
                
                // Update counts for displayed months
                const displayedMonths = [months[currentMonth], months[(currentMonth + 1) % 12]];
                
                displayedMonths.forEach(month => {
                    const monthHomework = homeworkList.filter(homework => {
                        const dueDate = new Date(homework.dueDate);
                        return dueDate.getMonth() === month.index && dueDate.getFullYear() === currentYear;
                    });
                    
                    const filteredHomework = applyFilters(monthHomework);
                    $(`#count-${month.id}`).text(filteredHomework.length);
                });
                
                // Display active month content
                const activeTab = $('.month-tab.active');
                if (activeTab.length) {
                    displayMonthContent(activeTab.data('month'));
                }
            }

            // --------------------------
            // HOMEWORK ACTION FUNCTIONS
            // --------------------------
            window.viewHomework = function(id) {
                $.ajax({
                    url: "{{ route('homework.show', '') }}/" + id,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const homework = response.homework;
                            const dueDate = new Date(homework.due_date);
                            
                            // Create modal for viewing homework
                            const modal = $(`
                                <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; animation: fadeIn 0.3s;">
                                    <div style="background: white; padding: 30px; border-radius: 20px; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto; box-shadow: 0 20px 40px rgba(0,0,0,0.3);">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                            <h3 style="margin: 0; color: var(--primary-color);">
                                                <i class="bi bi-book-fill"></i> Homework Details
                                            </h3>
                                            <button class="close-modal" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b;">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                        <div style="line-height: 1.6;">
                                            <p><strong><i class="bi bi-heading"></i> Title:</strong> ${homework.title}</p>
                                            <p><strong><i class="bi bi-file-text-fill"></i> Instructions:</strong> ${homework.instructions}</p>
                                            <p><strong><i class="bi bi-calendar-event-fill"></i> Due Date:</strong> ${dueDate.toLocaleString()}</p>
                                            <p><strong><i class="bi bi-info-circle-fill"></i> Status:</strong> ${homework.status}</p>
                                            <p><strong><i class="bi bi-person-fill"></i> Created By:</strong> ${homework.creator ? homework.creator.name : 'Unknown'}</p>
                                            <p><strong><i class="bi bi-people-fill"></i> Assigned To:</strong> ${homework.assigned_to_count} students</p>
                                        </div>
                                        <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
                                            <button class="btn btnn close-modal">
                                                <i class="bi bi-x-lg"></i> Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `);
                            
                            $('body').append(modal);
                            
                            // Close modal handlers
                            modal.find('.close-modal').on('click', function() {
                                modal.remove();
                            });
                        } else {
                            showNotification('error', response.message || 'Failed to load homework details');
                        }
                    },
                    error: function() {
                        showNotification('error', 'Failed to load homework details');
                    }
                });
            };

            window.editHomework = function(id) {
                showNotification('info', 'Edit functionality would open a form to edit homework details.');
                // Implement edit functionality
            };

            window.deleteHomework = function(id) {
                if (confirm('Are you sure you want to delete this homework?')) {
                    $.ajax({
                        url: "{{ route('homework.destroy', '') }}/" + id,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                showNotification('success', 'Homework deleted successfully!');
                                loadHomeworks();
                            } else {
                                showNotification('error', response.message || 'Failed to delete homework');
                            }
                        },
                        error: function() {
                            showNotification('error', 'Failed to delete homework');
                        }
                    });
                }
            };

            // --------------------------
            // TAB SWITCHING
            // --------------------------
            $('.tab').on('click', function() {
                $('.tab').removeClass('active');
                $(this).addClass('active');
                
                $('.page').removeClass('active');
                $('#' + $(this).data('page') + '-page').addClass('active');
                
                if ($(this).data('page') === 'view') {
                    loadHomeworks();
                }
            });

            // --------------------------
            // HELPER FUNCTIONS
            // --------------------------
            function setDefaultDueDate() {
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                tomorrow.setHours(17, 0, 0, 0);
                $('#due_date').val(tomorrow.toISOString().slice(0, 16));
            }

            function formatDate(date) {
                return date.toLocaleDateString('en-US', { 
                    month: 'short', 
                    day: 'numeric',
                    year: 'numeric'
                });
            }

            // Set default due date on page load
            setDefaultDueDate();
        });
    </script>
@endsection
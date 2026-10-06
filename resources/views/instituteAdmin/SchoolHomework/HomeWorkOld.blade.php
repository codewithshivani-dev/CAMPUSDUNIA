@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@section('content')
    <style>
        .header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            color: white;
            text-align: center;
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
        }
        
        .tabs {
            display: flex;
            background: white;
            border-radius: 10px;
            margin-bottom: 25px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 1px solid #e3e6f0;
        }
        
        .tab {
            flex: 1;
            padding: 14px;
            text-align: center;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 14px;
            border-right: 1px solid #e3e6f0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .tab:last-child {
            border-right: none;
        }
        
        .tab:hover {
            background: #f8f9fc;
        }
        
        .tab.active {
            background: #4e73df;
            color: white;
        }
        
        .content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid #e3e6f0;
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
        
        h2 {
            margin-bottom: 25px;
            font-size: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f8f9fc;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #5a5c69;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e3e6f0;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s;
            background: #f8f9fc;
        }
        
        input:focus, select:focus, textarea:focus {
            border-color: #4e73df;
            outline: none;
            box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
            background: white;
        }
        
        textarea {
            height: 100px;
        }
        
        .btn {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .btn-small {
            padding: 10px 20px;
            font-size: 13px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .homework-item {
            background: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #e3e6f0;
            border-left: 4px solid #4e73df;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
            transition: all 0.3s;
        }
        
        .homework-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-left-color: #224abe;
        }
        
        .meta {
            color: #858796;
            font-size: 13px;
            margin-bottom: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .meta i {
            margin-right: 6px;
            color: #4e73df;
            opacity: 0.8;
        }
        
        .status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }
        
        .status.created {
            background: #e8f4ff;
            color: #224abe;
            border: 1px solid #d1e3ff;
        }
        
        .status.assigned {
            background: #e8f7f0;
            color: #1cc88a;
            border: 1px solid #c4f1d7;
        }
        
        /* Student list */
        .student-container {
            max-height: 350px;
            overflow-y: auto;
            border: 2px solid #e3e6f0;
            border-radius: 8px;
            padding: 20px;
            margin-top: 10px;
            background: #f8f9fc;
        }
        
        .student-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }
        
        .student-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 2px solid #e3e6f0;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .student-card:hover {
            border-color: #4e73df;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(78, 115, 223, 0.1);
        }
        
        .student-card.selected {
            border-color: #4e73df;
            background: #f0f5ff;
            box-shadow: 0 2px 8px rgba(78, 115, 223, 0.15);
        }
        
        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }
        
        .student-info {
            flex: 1;
        }
        
        .student-name {
            font-weight: 600;
            font-size: 14px;
            color: #5a5c69;
            margin-bottom: 2px;
        }
        
        .student-details {
            color: #858796;
            font-size: 12px;
        }
        
        .student-details i {
            margin-right: 4px;
            color: #4e73df;
            opacity: 0.7;
        }
        
        .selection-controls {
            padding: 12px 15px;
            background: #f0f5ff;
            border-radius: 6px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #d1e3ff;
        }
        
        .selection-controls-buttons {
            display: flex;
            gap: 10px;
        }
        
        .hierarchy-info {
            background: #f0f5ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #d1e3ff;
        }
        
        .hierarchy-info i {
            color: #4e73df;
            font-size: 16px;
        }
        
        .hierarchy-info span {
            color: #224abe;
            font-weight: 600;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #b7b9cc;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }
        
        .counter-badge {
            background: #4e73df;
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 8px;
        }
        
        .student-roll {
            font-family: 'Courier New', monospace;
            background: #f8f9fc;
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid #e3e6f0;
            font-size: 11px;
        }
        
        .student-section {
            color: #4e73df;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        .student-checkbox {
            position: absolute;
            opacity: 0;
        }
        
        .student-card .selected-indicator {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 2px solid #e3e6f0;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
        }
        
        .student-card.selected .selected-indicator {
            background: #4e73df;
            border-color: #4e73df;
        }
        
        .student-card.selected .selected-indicator::after {
            content: "✓";
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
        
        /* Month Tabs Styles */
        .month-tabs-container {
            margin-bottom: 25px;
        }
        
        .month-tabs {
            display: flex;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            border: 1px solid #e3e6f0;
            flex-wrap: wrap;
        }
        
        .month-tab {
            padding: 12px 20px;
            text-align: center;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            border-right: 1px solid #e3e6f0;
            flex: 1;
            min-width: 80px;
            transition: all 0.3s;
            color: #5a5c69;
        }
        
        .month-tab:last-child {
            border-right: none;
        }
        
        .month-tab:hover {
            background: #f8f9fc;
        }
        
        .month-tab.active {
            background: #4e73df;
            color: white;
        }
        
        .month-tab .month-count {
            background: rgba(255,255,255,0.2);
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            margin-left: 6px;
        }
        
        .month-content {
            display: none;
        }
        
        .month-content.active {
            display: block;
            animation: fadeIn 0.3s ease-in;
        }
        
        .filter-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .filter-group {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .filter-select {
            padding: 8px 15px;
            border: 2px solid #e3e6f0;
            border-radius: 6px;
            font-size: 13px;
            background: #f8f9fc;
            min-width: 180px;
        }
        
        .homework-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e3e6f0;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #4e73df;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 13px;
            color: #858796;
            font-weight: 600;
        }
        
        .stat-icon {
            font-size: 24px;
            color: #4e73df;
            margin-bottom: 10px;
        }
        
        .homework-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        
        .action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            border: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .action-btn.view {
            background: #e8f4ff;
            color: #224abe;
            border: 1px solid #d1e3ff;
        }
        
        .action-btn.edit {
            background: #fff8e1;
            color: #ff9800;
            border: 1px solid #ffeaa7;
        }
        
        .action-btn.delete {
            background: #ffeaea;
            color: #e74a3b;
            border: 1px solid #f5c6cb;
        }
        
        /* Week View Styles */
        .week-view-container {
            margin-top: 20px;
        }
        
        .week-tabs-container {
            margin-bottom: 25px;
        }
        
        .week-tabs {
            display: flex;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            border: 1px solid #e3e6f0;
            flex-wrap: wrap;
        }
        
        .week-tab {
            padding: 10px 15px;
            text-align: center;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            border-right: 1px solid #e3e6f0;
            flex: 1;
            min-width: 70px;
            transition: all 0.3s;
            color: #5a5c69;
        }
        
        .week-tab:last-child {
            border-right: none;
        }
        
        .week-tab:hover {
            background: #f8f9fc;
        }
        
        .week-tab.active {
            background: #4e73df;
            color: white;
        }
        
        .week-tab .week-count {
            background: rgba(255,255,255,0.2);
            padding: 1px 6px;
            border-radius: 20px;
            font-size: 10px;
            margin-left: 4px;
        }
        
        .week-content {
            display: none;
        }
        
        .week-content.active {
            display: block;
            animation: fadeIn 0.3s ease-in;
        }
        
        .week-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding: 15px;
            background: #f0f5ff;
            border-radius: 8px;
            border: 1px solid #d1e3ff;
        }
        
        .week-range {
            font-size: 14px;
            font-weight: 600;
            color: #224abe;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .week-range i {
            font-size: 16px;
        }
        
        .week-stats {
            display: flex;
            gap: 20px;
            font-size: 13px;
            color: #5a5c69;
        }
        
        .week-stat {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .week-stat i {
            color: #4e73df;
        }
        
        /* Day-based view */
        .days-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .day-card {
            background: white;
            border-radius: 8px;
            border: 1px solid #e3e6f0;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        }
        
        .day-header {
            background: #f8f9fc;
            padding: 12px 15px;
            border-bottom: 1px solid #e3e6f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .day-title {
            font-weight: 600;
            color: #5a5c69;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .day-count {
            background: #4e73df;
            color: white;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .day-homework-list {
            padding: 15px;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .day-homework-item {
            padding: 12px;
            margin-bottom: 10px;
            background: #f8f9fc;
            border-radius: 6px;
            border-left: 3px solid #4e73df;
            border: 1px solid #e3e6f0;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .day-homework-item:hover {
            background: #eef2ff;
            transform: translateY(-1px);
        }
        
        .day-homework-item:last-child {
            margin-bottom: 0;
        }
        
        .day-homework-title {
            font-weight: 600;
            font-size: 13px;
            color: #5a5c69;
            margin-bottom: 5px;
        }
        
        .day-homework-meta {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #858796;
        }
        
        .day-homework-subject {
            color: #4e73df;
            font-weight: 600;
        }
        
        .day-homework-time {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        /* View Toggle */
        .view-toggle {
            display: flex;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            border: 1px solid #e3e6f0;
            width: fit-content;
        }
        
        .view-toggle-btn {
            padding: 10px 25px;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #5a5c69;
            transition: all 0.3s;
        }
        
        .view-toggle-btn:first-child {
            border-right: 1px solid #e3e6f0;
        }
        
        .view-toggle-btn:hover {
            background: #f8f9fc;
        }
        
        .view-toggle-btn.active {
            background: #4e73df;
            color: white;
        }
        
        /* Empty state for week view */
        .empty-day {
            text-align: center;
            padding: 30px;
            color: #b7b9cc;
            font-size: 13px;
        }
        
        .view-container {
            display: none;
        }
        
        .view-container.active {
            display: block;
        }
        
        /* Additional styles for better organization */
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        .week-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fc;
            border-radius: 8px;
            border: 1px solid #e3e6f0;
        }
        
        .week-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e3e6f0;
        }
        
        .week-section-title {
            font-size: 16px;
            font-weight: 600;
            color: #5a5c69;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .week-section-count {
            background: #4e73df;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        /* Loading spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #4e73df;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .days-container {
                grid-template-columns: 1fr;
            }
            
            .week-tab {
                min-width: 60px;
                padding: 8px 10px;
                font-size: 11px;
            }
            
            .month-tab {
                min-width: 60px;
                padding: 10px 12px;
                font-size: 12px;
            }
            
            .filter-group {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-select {
                min-width: auto;
                width: 100%;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .week-stats {
                flex-wrap: wrap;
                gap: 10px;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="header d-none">
            <h2 style="margin: 0; display: flex; justify-content: center; gap: 10px;">
                <i class="fas fa-book-open"></i> Homework Management System
            </h2>
            <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 14px;">
                Create, assign and track homework efficiently
            </p>
        </div>
        
        <div class="tabs">
            <button class="tab active" data-page="create">
                <i class="fas fa-plus-circle"></i> Create Homework
            </button>
            <button class="tab" data-page="assign">
                <i class="fas fa-user-check"></i> Assign to Students
            </button>
            <button class="tab" data-page="view">
                <i class="fas fa-list-alt"></i> View Homework
            </button>
        </div>
        
        <div class="content">
            <!-- Create Homework -->
            <div id="create-page" class="page active">
                <h2><i class="fas fa-edit"></i> Create New Homework</h2>
                <form id="homework-form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-users"></i> Class *</label>
                            <select id="course_id" name="course_id" required>
                                <option value="">Select Class</option>
                            </select>
                        </div>
                        <input type="hidden" id="department_id" value="{{ $departments->department_id }}">
                        <div class="form-group">
                            <label><i class="fas fa-code-branch"></i> Branch *</label>
                            <select id="branch_id" name="branch_id" required>
                                <option value="">Select Branch</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-book"></i> Subject *</label>
                            <select id="subject_id" name="subject_id" required>
                                <option value="">Select Subject</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-th-large"></i> Section *</label>
                            <select id="section_id" name="section_id" required>
                                <option value="">Select Section</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row" id="semester_container" style="display:none;">
                        <div class="form-group">
                            <label>Semester</label>
                            <select id="semester_id" name="semester_id"></select>
                        </div>
                        <div id="semester_info" style="margin-top:5px; color:#555;"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label><i class="fas fa-heading"></i> Homework Title *</label>
                            <input type="text" id="title" name="title" required placeholder="Enter homework title">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label><i class="fas fa-calendar-day"></i> Due Date *</label>
                            <input type="datetime-local" id="due_date" name="due_date" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-file-alt"></i> Instructions *</label>
                        <textarea id="instructions" name="instructions" required placeholder="Provide detailed instructions for the homework..." rows="5"></textarea>
                    </div>

                    <button type="submit" class="btn" id="create-btn">
                        <i class="fas fa-save"></i> Create Homework
                    </button>
                </form>
            </div>
            
            <!-- Assign to Students -->
            <div id="assign-page" class="page">
                <h2><i class="fas fa-user-check"></i> Assign Homework to Students</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-tasks"></i> Select Homework *</label>
                        <select id="homework-select">
                            <option value="">Select homework to assign</option>
                        </select>
                    </div>
                </div>
                
                <div class="hierarchy-info" id="hierarchy-info">
                    <i class="fas fa-sitemap"></i>
                    <span id="hierarchy-text">Select homework to see hierarchy details</span>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-users"></i> Select Students <span class="counter-badge" id="selected-count">0 selected</span></label>
                    
                    <div class="selection-controls">
                        <div style="font-weight: 600; color: #5a5c69;">
                            <i class="fas fa-user-check"></i> Student Selection Controls
                        </div>
                        <div class="selection-controls-buttons">
                            <button type="button" class="btn btn-small" id="select-all-btn">
                                <i class="fas fa-check-square"></i> Select All 
                            </button>
                            <button type="button" class="btn btn-small" id="clear-all-btn" style="background: #858796;">
                                <i class="fas fa-times-circle"></i> Clear All
                            </button>
                        </div>
                    </div>
                    
                    <div class="student-container" id="student-container">
                        <div class="empty-state">
                            <i class="fas fa-user-graduate"></i>
                            <p>Select a homework to see students</p>
                        </div>
                    </div>
                </div>
                
                <button class="btn" id="assign-btn">
                    <i class="fas fa-paper-plane"></i> Assign to Selected Students 
                </button>
            </div>
            
            <!-- View Homework -->
            <div id="view-page" class="page">
                <h2><i class="fas fa-list-alt"></i> Homework Assignments</h2>
                
                <!-- View Toggle -->
                <div class="view-toggle d-none">
                    <button class="view-toggle-btn active" data-view="month">
                        <i class="fas fa-calendar-alt"></i> Month View
                    </button>
                    <button class="view-toggle-btn" data-view="week">
                        <i class="fas fa-calendar-week"></i> Week View
                    </button>
                </div>
                
                <!-- Statistics -->
                <div class="homework-stats">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-tasks"></i></div>
                        <div class="stat-value" id="total-homework">0</div>
                        <div class="stat-label">Total Homework</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="stat-value" id="assigned-homework">0</div>
                        <div class="stat-label">Assigned</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-clock"></i></div>
                        <div class="stat-value" id="pending-homework">0</div>
                        <div class="stat-label">Pending</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
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
                
                <!-- Week View Container -->
                <div id="week-view-container" class="view-container" style="display: none;">
                    <div class="week-tabs-container">
                        <div class="week-tabs" id="week-tabs">
                            <!-- Weeks will be dynamically generated -->
                        </div>
                    </div>
                    
                    <div id="week-content-container">
                        <!-- Week content will be dynamically loaded -->
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
                $container.html(`<div class="empty-state"><i class="fas fa-user-graduate"></i><p>${message}</p></div>`);
            }

            function populateSubjects(subjects) {
                const $subject = $('#subject_id');
                $subject.html('<option value="">Select Subject</option>');
                subjects.forEach(sub => {
                    $subject.append(`<option value="${sub.subject_id}">${sub.subject_name}</option>`);
                });
            }

            function showNotification(type, message) {
                const icon = type === 'success' ? 'fa-check-circle' : 
                            type === 'error' ? 'fa-exclamation-circle' : 
                            type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
                const color = type === 'success' ? '#1cc88a' : 
                            type === 'error' ? '#e74a3b' : 
                            type === 'warning' ? '#f6c23e' : '#4e73df';
                const bgColor = type === 'success' ? '#d4edda' : 
                              type === 'error' ? '#f8d7da' : 
                              type === 'warning' ? '#fff3cd' : '#d1ecf1';
                const borderColor = type === 'success' ? '#c3e6cb' : 
                                  type === 'error' ? '#f5c6cb' : 
                                  type === 'warning' ? '#ffeaa7' : '#bee5eb';
                
                const div = document.createElement('div');
                div.style.cssText = `
                    position: fixed;
                    top: 25px;
                    right: 25px;
                    background: ${bgColor};
                    color: ${color};
                    padding: 15px 20px;
                    border-radius: 8px;
                    border-left: 4px solid ${borderColor};
                    z-index: 1000;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    min-width: 320px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    animation: slideIn 0.3s ease-out;
                `;
                
                div.innerHTML = `<i class="fas ${icon}" style="font-size: 20px;"></i><div style="flex: 1;">${message}</div>`;
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
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
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

            // Refresh button
            $('#refresh-homework').on('click', function() {
                loadHomeworks();
                showNotification('info', 'Refreshing homework data...');
            });

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
                    $('#hierarchy-info').html('<i class="fas fa-sitemap"></i><span>Select homework to see hierarchy details</span>');
                    setEmptyState($('#student-container'), 'Select a homework to see students');
                    currentSelectedHomework = null;
                    return;
                }

                const homework = selectedOption.data('homework');
                currentSelectedHomework = homework;
                
                // Show hierarchy info
                $('#hierarchy-info').html(`
                    <i class="fas fa-sitemap"></i>
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
                        <i class="fas fa-spinner fa-spin"></i>
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
                                <i class="fas fa-user-slash"></i>
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
                                        <span class="student-roll">Roll: ${student.registration_number || 'N/A'}</span>
                                    </div>
                                    <div style="font-size: 11px; color: #b7b9cc; margin-top: 2px;">
                                        <i class="fas fa-book"></i> ${homework.subjectText}
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
                            <i class="fas fa-exclamation-triangle"></i>
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
                $('#select-all-btn').on('click', function() {
                    $('.student-card').each(function() {
                        const $card = $(this);
                        if (!$card.hasClass('selected')) {
                            $card.click();
                        }
                    });
                });
                
                $('#clear-all-btn').on('click', function() {
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
                            <i class="fas fa-inbox"></i>
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
                                        <i class="fas fa-calendar-week"></i> 
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
                        <h3 style="color: #5a5c69; margin-bottom: 20px;">
                            <i class="fas fa-calendar-alt"></i> ${month.name} ${currentYear}
                            <span style="font-size: 14px; color: #858796; margin-left: 10px;">
                                (${filteredHomework.length} homework assignments)
                            </span>
                        </h3>
                        ${weeksHTML || `<div class="empty-state" style="padding: 20px;"><i class="fas fa-inbox"></i><p>No homework for this month</p></div>`}
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
                const statusIcon = homework.status === 'assigned' ? 'fa-check-circle' : 'fa-clock';
                const statusText = homework.status === 'assigned' ? 
                    `Assigned to ${homework.assignedTo} students` : 
                    'Not assigned yet';
                
                return `
                    <div class="homework-item">
                        <div style="font-weight: 600; color: #5a5c69; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center;">
                            <span><i class="fas fa-book" style="color: #4e73df;"></i> ${homework.title}</span>
                            <span class="status ${statusClass}" style="margin: 0;">
                                <i class="fas ${statusIcon}"></i> ${statusText}
                            </span>
                        </div>
                        
                        <div class="meta">
                            <span><i class="fas fa-users"></i> ${homework.classText}</span>
                            <span><i class="fas fa-code-branch"></i> ${homework.branchText}</span>
                            <span><i class="fas fa-book"></i> ${homework.subjectText}</span>
                            <span><i class="fas fa-th-large"></i> ${homework.sectionText}</span>
                            <span><i class="fas fa-calendar"></i> Due: ${dueDate.toLocaleDateString()} ${dueDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                        </div>
                        
                        <div style="color: #858796; font-size: 13px; margin-bottom: 10px; line-height: 1.5;">
                            <i class="fas fa-file-alt" style="color: #4e73df;"></i> ${homework.instructions.substring(0, 100)}${homework.instructions.length > 100 ? '...' : ''}
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="font-size: 12px; color: #b7b9cc;">
                                <span><i class="fas fa-clock"></i> Created: ${homework.createdAt}</span>
                                ${homework.assignedAt ? `<span style="margin-left: 15px;"><i class="fas fa-paper-plane"></i> Assigned: ${homework.assignedAt}</span>` : ''}
                            </div>
                            
                            <div class="homework-actions">
                                <button class="action-btn view" onclick="viewHomework(${homework.id})">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                ${homework.status === 'created' ? `
                                    <button class="action-btn edit d-none" onclick="editHomework(${homework.id})">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                ` : ''}
                                <button class="action-btn delete" onclick="deleteHomework(${homework.id})">
                                    <i class="fas fa-trash"></i> Delete
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
                                    <div style="background: white; padding: 30px; border-radius: 10px; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                            <h3 style="margin: 0; color: #4e73df;">
                                                <i class="fas fa-book"></i> Homework Details
                                            </h3>
                                            <button class="close-modal" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #858796;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div style="line-height: 1.6;">
                                            <p><strong>Title:</strong> ${homework.title}</p>
                                            <p><strong>Instructions:</strong> ${homework.instructions}</p>
                                            <p><strong>Due Date:</strong> ${dueDate.toLocaleString()}</p>
                                            <p><strong>Status:</strong> ${homework.status}</p>
                                            <p><strong>Created By:</strong> ${homework.creator ? homework.creator.name : 'Unknown'}</p>
                                            <p><strong>Assigned To:</strong> ${homework.assigned_to_count} students</p>
                                        </div>
                                        <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
                                            <button class="btn close-modal">
                                                <i class="fas fa-times"></i> Close
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
            // FILTER EVENTS
            // --------------------------
            $('#filter-department, #filter-status, #filter-class').on('change', filterHomework);
            $('#reset-filters').on('click', resetFilters);

            // --------------------------
            // VIEW TOGGLE
            // --------------------------
            $('.view-toggle-btn').on('click', function() {
                $('.view-toggle-btn').removeClass('active');
                $(this).addClass('active');
                
                const view = $(this).data('view');
                $('.view-container').removeClass('active').hide();
                
                if (view === 'month') {
                    $('#month-view-container').addClass('active').show();
                    displayHomeworkByMonth();
                } else {
                    $('#week-view-container').addClass('active').show();
                    // Implement week view if needed
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
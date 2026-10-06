@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@section('content')
    <style>
        .container {
            margin: 0 auto;
            max-width: 1400px;
            padding: 20px;
        }
        
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

    <div class="container">
        <div class="header">
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
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-heading"></i> Homework Title *</label>
                            <input type="text" id="title" required placeholder="Enter homework title">
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-building"></i> Department *</label>
                            <select id="department" required>
                                <option value="">Select Department</option>
                                <option value="academics">Academics</option>
                                <option value="sports">Sports</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group" id="category-group">
                            <label><i class="fas fa-layer-group"></i> Category *</label>
                            <select id="category" required>
                                <option value="">Select Category</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-users"></i> Class *</label>
                            <select id="class" required>
                                <option value="">Select Class</option>
                                <option value="5">5th</option>
                                <option value="6">6th</option>
                                <option value="7">7th</option>
                                <option value="8">8th</option>
                                <option value="9">9th</option>
                                <option value="10">10th</option>
                                <option value="11">11th</option>
                                <option value="12">12th</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-code-branch"></i> Branch *</label>
                            <select id="branch" required>
                                <option value="">Auto-selecting...</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-th-large"></i> Section *</label>
                            <select id="section" required>
                                <option value="">Select Section</option>
                                <option value="A">Section A</option>
                                <option value="B">Section B</option>
                                <option value="C">Section C</option>
                                <option value="D">Section D</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-book"></i> Subject *</label>
                            <select id="subject" required>
                                <option value="">Select Subject</option>
                                <option value="Science">Science</option>
                                <option value="Mathematics">Mathematics</option>
                                <option value="English">English</option>
                                <option value="Physics">Physics</option>
                                <option value="Chemistry">Chemistry</option>
                                <option value="Biology">Biology</option>
                                <option value="History">History</option>
                                <option value="Geography">Geography</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-calendar-day"></i> Due Date *</label>
                            <input type="datetime-local" id="due-date" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-file-alt"></i> Instructions *</label>
                        <textarea id="instructions" required placeholder="Provide detailed instructions for the homework..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn">
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
                <div class="view-toggle">
                    <button class="view-toggle-btn active" data-view="month">
                        <i class="fas fa-calendar-alt"></i> Month View
                    </button>
                    <button class="view-toggle-btn" data-view="week">
                        <i class="fas fa-calendar-week"></i> Week View
                    </button>
                </div>
                
                <!-- Filter Controls -->
                <div class="filter-controls">
                    <div class="filter-group">
                        <select class="filter-select" id="filter-department">
                            <option value="">All Departments</option>
                            <option value="academics">Academics</option>
                            <option value="sports">Sports</option>
                        </select>
                        <select class="filter-select" id="filter-status">
                            <option value="">All Status</option>
                            <option value="created">Not Assigned</option>
                            <option value="assigned">Assigned</option>
                        </select>
                        <select class="filter-select" id="filter-class">
                            <option value="">All Classes</option>
                            <option value="5">5th</option>
                            <option value="6">6th</option>
                            <option value="7">7th</option>
                            <option value="8">8th</option>
                            <option value="9">9th</option>
                            <option value="10">10th</option>
                            <option value="11">11th</option>
                            <option value="12">12th</option>
                        </select>
                    </div>
                    <button class="btn btn-small" id="reset-filters">
                        <i class="fas fa-redo"></i> Reset Filters
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

    <script>
        // Sample data with real student names
        let homeworkList = [];
        
        // Department Categories
        const categories = {
            academics: ['Mathematics', 'Science', 'English', 'History', 'Geography'],
            sports: ['Football', 'Cricket', 'Basketball', 'Hockey', 'Athletics']
        };
        
        // ===================== DUMMY STUDENTS DATA =====================
        let students = [
            // Class 10th, Section A, Science (10 students)
            { id: 1, name: "Aarav Sharma", department: "academics", category: "Science", class: "10", branch: "10th", section: "A", roll: "1001" },
            { id: 2, name: "Vivaan Patel", department: "academics", category: "Science", class: "10", branch: "10th", section: "A", roll: "1002" },
            { id: 3, name: "Aditya Kumar", department: "academics", category: "Science", class: "10", branch: "10th", section: "A", roll: "1003" },
            { id: 4, name: "Vihaan Gupta", department: "academics", category: "Science", class: "10", branch: "10th", section: "A", roll: "1004" },
            { id: 5, name: "Arjun Singh", department: "academics", category: "Science", class: "10", branch: "10th", section: "A", roll: "1005" },
            { id: 6, name: "Reyansh Reddy", department: "academics", category: "Science", class: "10", branch: "10th", section: "A", roll: "1006" },
            { id: 7, name: "Sai Deshmukh", department: "academics", category: "Science", class: "10", branch: "10th", section: "A", roll: "1007" },
            { id: 8, name: "Ishaan Gupta", department: "academics", category: "Science", class: "10", branch: "10th", section: "A", roll: "1008" },
            { id: 9, name: "Dhruv Joshi", department: "academics", category: "Science", class: "10", branch: "10th", section: "A", roll: "1009" },
            { id: 10, name: "Atharva Kulkarni", department: "academics", category: "Science", class: "10", branch: "10th", section: "A", roll: "1010" },
       
            // Class 10th, Section B, Science (10 students)
            { id: 11, name: "Kabir Verma", department: "academics", category: "Science", class: "10", branch: "10th", section: "B", roll: "1011" },
            { id: 12, name: "Rudra Sharma", department: "academics", category: "Science", class: "10", branch: "10th", section: "B", roll: "1012" },
            { id: 13, name: "Veer Malhotra", department: "academics", category: "Science", class: "10", branch: "10th", section: "B", roll: "1013" },
            { id: 14, name: "Ayaan Kapoor", department: "academics", category: "Science", class: "10", branch: "10th", section: "B", roll: "1014" },
            { id: 15, name: "Krish Nair", department: "academics", category: "Science", class: "10", branch: "10th", section: "B", roll: "1015" },
            { id: 16, name: "Rohan Mehta", department: "academics", category: "Science", class: "10", branch: "10th", section: "B", roll: "1016" },
            { id: 17, name: "Yash Patel", department: "academics", category: "Science", class: "10", branch: "10th", section: "B", roll: "1017" },
            { id: 18, name: "Anirudh Iyer", department: "academics", category: "Science", class: "10", branch: "10th", section: "B", roll: "1018" },
            { id: 19, name: "Vihaan Reddy", department: "academics", category: "Science", class: "10", branch: "10th", section: "B", roll: "1019" },
            { id: 20, name: "Aryan Singh", department: "academics", category: "Science", class: "10", branch: "10th", section: "B", roll: "1020" },
        ];

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

        // Initialize
        window.addEventListener('DOMContentLoaded', () => {
            setDefaultDueDate();
            populateHomeworkSelect();
            initializeMonthTabs();
            initializeWeekTabs();
            
            // Department change event
            document.getElementById('department').addEventListener('change', function() {
                const dept = this.value;
                const categorySelect = document.getElementById('category');
                categorySelect.innerHTML = '<option value="">Select Category</option>';
                
                if (dept && categories[dept]) {
                    categories[dept].forEach(cat => {
                        const option = document.createElement('option');
                        option.value = cat;
                        option.textContent = cat;
                        categorySelect.appendChild(option);
                    });
                }
            });
            
            // Class change event - auto-select branch
            document.getElementById('class').addEventListener('change', function() {
                const classVal = this.value;
                const branchSelect = document.getElementById('branch');
                
                // Auto-select branch as "5th", "6th", etc.
                if (classVal) {
                    branchSelect.innerHTML = `<option value="${classVal}th" selected>${classVal}th</option>`;
                } else {
                    branchSelect.innerHTML = '<option value="">Select Class First</option>';
                }
            });
            
            // Filter events
            document.getElementById('filter-department').addEventListener('change', filterHomework);
            document.getElementById('filter-status').addEventListener('change', filterHomework);
            document.getElementById('filter-class').addEventListener('change', filterHomework);
            document.getElementById('reset-filters').addEventListener('click', resetFilters);
            
            // View toggle
            document.querySelectorAll('.view-toggle-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.view-toggle-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    const view = this.dataset.view;
                    document.querySelectorAll('.view-container').forEach(container => {
                        container.classList.remove('active');
                        container.style.display = 'none';
                    });
                    
                    if (view === 'month') {
                        document.getElementById('month-view-container').classList.add('active');
                        document.getElementById('month-view-container').style.display = 'block';
                        displayHomeworkByMonth();
                    } else {
                        document.getElementById('week-view-container').classList.add('active');
                        document.getElementById('week-view-container').style.display = 'block';
                        displayWeekContent();
                    }
                });
            });
            
            // Trigger initial population
            document.getElementById('department').dispatchEvent(new Event('change'));
            
            // Add sample homework for demo
            addSampleHomework();
            
            // Initialize view
            updateStatistics();
            autoSelectFirstMonthWithHomework();
            
            // Set week 1 as active
            const firstWeekTab = document.querySelector('.week-tab');
            if (firstWeekTab) {
                firstWeekTab.classList.add('active');
                displayWeekContent(0);
            }
        });

        // ==================== MONTH VIEW FUNCTIONS ====================
        
        // Initialize month tabs
        function initializeMonthTabs() {
            const monthTabs = document.getElementById('month-tabs');
            monthTabs.innerHTML = '';
            
            months.forEach(month => {
                const tab = document.createElement('button');
                tab.className = 'month-tab';
                tab.dataset.month = month.id;
                tab.innerHTML = `
                    ${month.short}
                    <span class="month-count" id="count-${month.id}">0</span>
                `;
                
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.month-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    displayMonthContent(this.dataset.month);
                });
                
                monthTabs.appendChild(tab);
            });
        }

        // Display month content with weekly breakdown
        function displayMonthContent(monthId) {
            const container = document.getElementById('month-content-container');
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
            const countElement = document.getElementById(`count-${monthId}`);
            if (countElement) {
                countElement.textContent = filteredHomework.length;
            }
            
            if (filteredHomework.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No homework for ${month.name} ${currentYear}</p>
                    </div>
                `;
                return;
            }
            
            // Group homework by week within the month
            const weeksInMonth = getWeeksInMonth(month.index, currentYear);
            let weeksHTML = '';
            
            weeksInMonth.forEach((week, weekIndex) => {
                const weekHomework = filteredHomework.filter(homework => {
                    const dueDate = new Date(homework.dueDate);
                    return dueDate >= week.start && dueDate <= week.end;
                });
                
                if (weekHomework.length > 0) {
                    weeksHTML += `
                        <div class="week-section" style="margin-bottom: 30px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #f8f9fc;">
                                <h4 style="margin: 0; color: #5a5c69; font-size: 16px;">
                                    <i class="fas fa-calendar-week"></i> 
                                    Week ${weekIndex + 1}: ${formatDate(week.start)} - ${formatDate(week.end)}
                                    <span style="font-size: 13px; color: #858796; margin-left: 10px;">
                                        (${weekHomework.length} assignments)
                                    </span>
                                </h4>
                            </div>
                            <div class="homework-list-week">
                                ${weekHomework.map(homework => createHomeworkItemHTML(homework)).join('')}
                            </div>
                        </div>
                    `;
                }
            });
            
            container.innerHTML = `
                <div class="month-content active">
                    <h3 style="color: #5a5c69; margin-bottom: 20px;">
                        <i class="fas fa-calendar-alt"></i> ${month.name} ${currentYear}
                        <span style="font-size: 14px; color: #858796; margin-left: 10px;">
                            (${filteredHomework.length} homework assignments)
                        </span>
                    </h3>
                    ${weeksHTML || `<div class="empty-state" style="padding: 20px;"><i class="fas fa-inbox"></i><p>No homework for this month</p></div>`}
                </div>
            `;
        }

        // Get weeks in a specific month
        function getWeeksInMonth(monthIndex, year) {
            const weeks = [];
            const firstDay = new Date(year, monthIndex, 1);
            const lastDay = new Date(year, monthIndex + 1, 0);
            
            let currentWeekStart = new Date(firstDay);
            // Adjust to Monday start
            const dayOfWeek = currentWeekStart.getDay();
            const diff = dayOfWeek === 0 ? -6 : 1 - dayOfWeek;
            currentWeekStart.setDate(firstDay.getDate() + diff);
            
            while (currentWeekStart <= lastDay) {
                const weekEnd = new Date(currentWeekStart);
                weekEnd.setDate(currentWeekStart.getDate() + 6);
                
                // Only include weeks that overlap with the month
                if (weekEnd >= firstDay) {
                    const weekStartInMonth = currentWeekStart < firstDay ? firstDay : currentWeekStart;
                    const weekEndInMonth = weekEnd > lastDay ? lastDay : weekEnd;
                    
                    weeks.push({
                        start: new Date(weekStartInMonth),
                        end: new Date(weekEndInMonth)
                    });
                }
                
                // Move to next week
                currentWeekStart.setDate(currentWeekStart.getDate() + 7);
            }
            
            return weeks;
        }

        // ==================== WEEK VIEW FUNCTIONS ====================
        
        // Initialize week tabs
        function initializeWeekTabs() {
            const weekTabs = document.getElementById('week-tabs');
            weekTabs.innerHTML = '';
            
            // Generate 4 weeks (current week + next 3 weeks)
            for (let i = 0; i < 4; i++) {
                const weekStart = getWeekStartDate(i);
                const weekEnd = new Date(weekStart);
                weekEnd.setDate(weekStart.getDate() + 6);
                
                const weekTab = document.createElement('button');
                weekTab.className = 'week-tab';
                weekTab.dataset.week = i;
                weekTab.innerHTML = `
                    Week ${i + 1}
                    <span class="week-count" id="week-count-${i}">0</span>
                `;
                
                weekTab.addEventListener('click', function() {
                    document.querySelectorAll('.week-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    displayWeekContent(this.dataset.week);
                });
                
                weekTabs.appendChild(weekTab);
            }
        }
        
        // Get week start date (Monday)
        function getWeekStartDate(weekOffset = 0) {
            const now = new Date();
            const currentDay = now.getDay();
            const diff = currentDay === 0 ? 6 : currentDay - 1; // Monday as first day
            const monday = new Date(now);
            monday.setDate(now.getDate() - diff + (weekOffset * 7));
            monday.setHours(0, 0, 0, 0);
            return monday;
        }
        
        // Display week content
        function displayWeekContent(weekIndex = 0) {
            const container = document.getElementById('week-content-container');
            const weekStart = getWeekStartDate(parseInt(weekIndex));
            const weekEnd = new Date(weekStart);
            weekEnd.setDate(weekStart.getDate() + 6);
            
            // Filter homework for this week
            const weekHomework = homeworkList.filter(homework => {
                const dueDate = new Date(homework.dueDate);
                return dueDate >= weekStart && dueDate <= weekEnd;
            });
            
            // Apply filters
            const filteredHomework = applyFilters(weekHomework);
            
            // Update week count
            const countElement = document.getElementById(`week-count-${weekIndex}`);
            if (countElement) {
                countElement.textContent = filteredHomework.length;
            }
            
            if (filteredHomework.length === 0) {
                container.innerHTML = `
                    <div class="week-header">
                        <div class="week-range">
                            <i class="fas fa-calendar-week"></i>
                            Week ${parseInt(weekIndex) + 1}: ${formatDate(weekStart)} - ${formatDate(weekEnd)}
                        </div>
                        <div class="week-stats">
                            <div class="week-stat">
                                <i class="fas fa-tasks"></i> 0 Homework
                            </div>
                        </div>
                    </div>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No homework for this week</p>
                    </div>
                `;
                return;
            }
            
            // Group homework by day
            const homeworkByDay = groupHomeworkByDay(filteredHomework, weekStart);
            
            // Create HTML for week view
            let daysHTML = '';
            for (let i = 0; i < 7; i++) {
                const dayDate = new Date(weekStart);
                dayDate.setDate(weekStart.getDate() + i);
                const dayKey = formatDate(dayDate);
                const dayHomework = homeworkByDay[dayKey] || [];
                
                daysHTML += `
                    <div class="day-card">
                        <div class="day-header">
                            <div class="day-title">
                                <i class="fas fa-calendar-day"></i>
                                ${getDayName(dayDate)}
                                <span style="font-size: 12px; color: #858796;">(${formatDateShort(dayDate)})</span>
                            </div>
                            <span class="day-count">${dayHomework.length}</span>
                        </div>
                        <div class="day-homework-list">
                            ${dayHomework.length > 0 ? 
                                dayHomework.map(h => `
                                    <div class="day-homework-item" onclick="viewHomework(${h.id})">
                                        <div class="day-homework-title">${h.title}</div>
                                        <div class="day-homework-meta">
                                            <span class="day-homework-subject">${h.subject}</span>
                                            <span class="day-homework-time">
                                                <i class="far fa-clock"></i>
                                                ${formatTime(new Date(h.dueDate))}
                                            </span>
                                        </div>
                                    </div>
                                `).join('') : 
                                `<div class="empty-day">No homework</div>`
                            }
                        </div>
                    </div>
                `;
            }
            
            // Calculate week stats
            const assignedCount = filteredHomework.filter(h => h.status === 'assigned').length;
            const pendingCount = filteredHomework.filter(h => h.status === 'created').length;
            const today = new Date().toDateString();
            const dueToday = filteredHomework.filter(h => 
                new Date(h.dueDate).toDateString() === today
            ).length;
            
            container.innerHTML = `
                <div class="week-header">
                    <div class="week-range">
                        <i class="fas fa-calendar-week"></i>
                        Week ${parseInt(weekIndex) + 1}: ${formatDate(weekStart)} - ${formatDate(weekEnd)}
                    </div>
                    <div class="week-stats">
                        <div class="week-stat">
                            <i class="fas fa-tasks"></i> ${filteredHomework.length} Homework
                        </div>
                        <div class="week-stat">
                            <i class="fas fa-check-circle"></i> ${assignedCount} Assigned
                        </div>
                        <div class="week-stat">
                            <i class="fas fa-clock"></i> ${pendingCount} Pending
                        </div>
                        <div class="week-stat">
                            <i class="fas fa-exclamation-circle"></i> ${dueToday} Due Today
                        </div>
                    </div>
                </div>
                <div class="days-container">
                    ${daysHTML}
                </div>
            `;
        }
        
        // Group homework by day
        function groupHomeworkByDay(homeworkArray, weekStart) {
            const grouped = {};
            
            homeworkArray.forEach(homework => {
                const dueDate = new Date(homework.dueDate);
                const dayKey = formatDate(dueDate);
                
                if (!grouped[dayKey]) {
                    grouped[dayKey] = [];
                }
                
                grouped[dayKey].push(homework);
            });
            
            // Sort homework within each day by time
            Object.keys(grouped).forEach(day => {
                grouped[day].sort((a, b) => 
                    new Date(a.dueDate) - new Date(b.dueDate)
                );
            });
            
            return grouped;
        }

        // ==================== HELPER FUNCTIONS ====================
        
        // Helper functions for date formatting
        function formatDate(date) {
            return date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric',
                year: 'numeric'
            });
        }
        
        function formatDateShort(date) {
            return date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric'
            });
        }
        
        function formatTime(date) {
            return date.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit'
            });
        }
        
        function getDayName(date) {
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            return days[date.getDay()];
        }

        // Create homework item HTML
        function createHomeworkItemHTML(homework) {
            const dueDate = new Date(homework.dueDate);
            const now = new Date();
            const isOverdue = dueDate < now && homework.status === 'assigned';
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
                        <span><i class="fas fa-building"></i> ${homework.department.toUpperCase()}</span>
                        <span><i class="fas fa-layer-group"></i> ${homework.category}</span>
                        <span><i class="fas fa-users"></i> Class ${homework.class}${homework.section}</span>
                        <span><i class="fas fa-book"></i> ${homework.subject}</span>
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
                                <button class="action-btn edit" onclick="editHomework(${homework.id})">
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

        // Apply filters to homework
        function applyFilters(homeworkArray) {
            const department = document.getElementById('filter-department').value;
            const status = document.getElementById('filter-status').value;
            const classFilter = document.getElementById('filter-class').value;
            
            return homeworkArray.filter(homework => {
                let pass = true;
                
                if (department && homework.department !== department) pass = false;
                if (status && homework.status !== status) pass = false;
                if (classFilter && homework.class !== classFilter) pass = false;
                
                return pass;
            });
        }

        // Filter homework
        function filterHomework() {
            updateStatistics();
            
            // Update current view
            const activeView = document.querySelector('.view-toggle-btn.active');
            if (activeView.dataset.view === 'month') {
                displayHomeworkByMonth();
            } else {
                const activeWeekTab = document.querySelector('.week-tab.active');
                if (activeWeekTab) {
                    displayWeekContent(activeWeekTab.dataset.week);
                }
            }
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('filter-department').value = '';
            document.getElementById('filter-status').value = '';
            document.getElementById('filter-class').value = '';
            filterHomework();
        }

        // Display homework by month
        function displayHomeworkByMonth() {
            // Update counts for all months
            months.forEach(month => {
                const currentYear = new Date().getFullYear();
                const monthHomework = homeworkList.filter(homework => {
                    const dueDate = new Date(homework.dueDate);
                    return dueDate.getMonth() === month.index && dueDate.getFullYear() === currentYear;
                });
                
                const filteredHomework = applyFilters(monthHomework);
                const countElement = document.getElementById(`count-${month.id}`);
                if (countElement) {
                    countElement.textContent = filteredHomework.length;
                }
            });
            
            // Display active month content
            const activeTab = document.querySelector('.month-tab.active');
            if (activeTab) {
                displayMonthContent(activeTab.dataset.month);
            }
        }

        // Auto select first month with homework
        function autoSelectFirstMonthWithHomework() {
            const currentYear = new Date().getFullYear();

            for (let month of months) {
                const hasHomework = homeworkList.some(hw => {
                    const d = new Date(hw.dueDate);
                    return d.getMonth() === month.index && d.getFullYear() === currentYear;
                });

                if (hasHomework) {
                    document.querySelectorAll('.month-tab').forEach(t => t.classList.remove('active'));

                    const tab = document.querySelector(`.month-tab[data-month="${month.id}"]`);
                    if (tab) {
                        tab.classList.add('active');
                        displayMonthContent(month.id);
                    }
                    return;
                }
            }

            // If NO homework exists at all
            document.getElementById('month-content-container').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>No homework available</p>
                </div>
            `;
        }

        // Update statistics
        function updateStatistics() {
            const currentYear = new Date().getFullYear();
            const allHomework = homeworkList.filter(h => {
                const dueDate = new Date(h.dueDate);
                return dueDate.getFullYear() === currentYear;
            });
            
            const filteredHomework = applyFilters(allHomework);
            
            const total = filteredHomework.length;
            const assigned = filteredHomework.filter(h => h.status === 'assigned').length;
            const pending = filteredHomework.filter(h => h.status === 'created').length;
            
            // Calculate unique students
            const assignedStudents = new Set();
            filteredHomework.forEach(h => {
                if (h.assignedStudents) {
                    h.assignedStudents.forEach(s => assignedStudents.add(s));
                }
            });
            
            document.getElementById('total-homework').textContent = total;
            document.getElementById('assigned-homework').textContent = assigned;
            document.getElementById('pending-homework').textContent = pending;
            document.getElementById('total-students').textContent = assignedStudents.size;
        }

        // ==================== EVENT HANDLERS ====================

        // Tab switching
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                document.querySelectorAll('.page').forEach(page => page.classList.remove('active'));
                document.getElementById(this.dataset.page + '-page').classList.add('active');
                
                if (this.dataset.page === 'view') {
                    updateStatistics();
                    displayHomeworkByMonth();
                    autoSelectFirstMonthWithHomework();
                }
            });
        });

        // Create homework form
        document.getElementById('homework-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const homework = {
                id: Date.now(),
                title: document.getElementById('title').value,
                department: document.getElementById('department').value,
                category: document.getElementById('category').value,
                class: document.getElementById('class').value,
                branch: document.getElementById('branch').value,
                section: document.getElementById('section').value,
                subject: document.getElementById('subject').value,
                dueDate: document.getElementById('due-date').value,
                instructions: document.getElementById('instructions').value,
                createdAt: new Date().toLocaleString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }),
                status: 'created',
                assignedTo: 0,
                assignedStudents: []
            };
            
            homeworkList.push(homework);
            
            showNotification('success', `✅ Homework "${homework.title}" created successfully!`);
            this.reset();
            setDefaultDueDate();
            
            // Reset department and auto-select class branch
            document.getElementById('department').dispatchEvent(new Event('change'));
            document.getElementById('class').dispatchEvent(new Event('change'));
            
            populateHomeworkSelect();
            updateStatistics();
            displayHomeworkByMonth();
            
            // Switch to assign tab
            setTimeout(() => {
                document.querySelector('[data-page="assign"]').click();
            }, 500);
        });

        // Populate homework select
        function populateHomeworkSelect() {
            const select = document.getElementById('homework-select');
            select.innerHTML = '<option value="">Select homework to assign</option>';
            
            homeworkList.forEach(homework => {
                if (homework.status === 'created') {
                    const option = document.createElement('option');
                    option.value = homework.id;
                    option.textContent = `${homework.title} (Class ${homework.class}${homework.section} - ${homework.subject})`;
                    select.appendChild(option);
                }
            });
            
            // Add change event to show students
            select.addEventListener('change', function() {
                const homeworkId = this.value;
                if (homeworkId) {
                    const homework = homeworkList.find(h => h.id == homeworkId);
                    if (homework) {
                        showHierarchyInfo(homework);
                        showStudentsForHomework(homework);
                    }
                } else {
                    document.getElementById('hierarchy-text').textContent = 'Select homework to see hierarchy details';
                    document.getElementById('student-container').innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-user-graduate"></i>
                            <p>Select a homework to see students</p>
                        </div>
                    `;
                    updateSelectedCount(0);
                }
            });
        }

        // Show hierarchy info
        function showHierarchyInfo(homework) {
            const infoDiv = document.getElementById('hierarchy-info');
            infoDiv.innerHTML = `
                <i class="fas fa-sitemap"></i>
                <span>${homework.department.toUpperCase()} → ${homework.category} → Class ${homework.class} → ${homework.branch} → ${homework.section} → ${homework.subject}</span>
            `;
        }

        // Show students for selected homework
        function showStudentsForHomework(homework) {
            const container = document.getElementById('student-container');
            
            // Filter students based on homework hierarchy
            const filteredStudents = students.filter(student => 
                student.department === homework.department &&
                student.category === homework.category &&
                student.class === homework.class &&
                student.branch === homework.branch &&
                student.section === homework.section
            );
            
            if (filteredStudents.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-user-slash"></i>
                        <p>No students found for this class and section</p> 
                    </div> 
                `;
                updateSelectedCount(0);
                return;
            }
            
            container.innerHTML = '<div class="student-grid" id="student-grid"></div>';
            const grid = document.getElementById('student-grid');
            
            filteredStudents.forEach(student => {
                const card = document.createElement('div');
                card.className = 'student-card';
                card.dataset.id = student.id;
                card.dataset.selected = 'true';
                
                const initials = student.name.split(' ').map(n => n[0]).join('').toUpperCase();
                
                card.innerHTML = `
                    <div class="student-avatar">${initials}</div>
                    <div class="student-info">
                        <div class="student-name">${student.name}</div>
                        <div class="student-details">
                            <span><i class="fas fa-id-badge"></i> Roll: <span class="student-roll">${student.roll}</span></span>
                            <span><i class="fas fa-graduation-cap"></i> Class ${student.class}<span class="student-section">${student.section}</span></span>
                        </div>
                    </div>
                    <div class="selected-indicator"></div>
                    <input type="checkbox" class="student-checkbox" style="display: none;" checked>
                `;
                
                card.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    const isSelected = this.dataset.selected === 'true';
                    this.dataset.selected = !isSelected;
                    
                    if (isSelected) {
                        this.classList.remove('selected');
                        this.querySelector('.student-checkbox').checked = false;
                    } else {
                        this.classList.add('selected');
                        this.querySelector('.student-checkbox').checked = true;
                    }
                    
                    updateSelectedCount();
                });
                
                // Set initial selection
                card.classList.add('selected');
                
                grid.appendChild(card);
            });
            
            updateSelectedCount();
            setupStudentControls();
        }

        // Setup student selection controls
        function setupStudentControls() {
            // Select All button
            document.getElementById('select-all-btn').onclick = function() {
                document.querySelectorAll('.student-card').forEach(card => {
                    card.classList.add('selected');
                    card.dataset.selected = 'true';
                    card.querySelector('.student-checkbox').checked = true;
                });
                updateSelectedCount();
            };
            
            // Clear All button
            document.getElementById('clear-all-btn').onclick = function() {
                document.querySelectorAll('.student-card').forEach(card => {
                    card.classList.remove('selected');
                    card.dataset.selected = 'false';
                    card.querySelector('.student-checkbox').checked = false;
                });
                updateSelectedCount();
            };
        }

        // Update selected count
        function updateSelectedCount() {
            const count = document.querySelectorAll('.student-card.selected').length;
            document.getElementById('selected-count').textContent = `${count} selected`;
        }

        // Assign homework
        document.getElementById('assign-btn').addEventListener('click', function() {
            const homeworkId = document.getElementById('homework-select').value;
            
            if (!homeworkId) {
                showNotification('error', '⚠️ Please select a homework first');
                return;
            }
            
            const selectedStudents = Array.from(
                document.querySelectorAll('.student-card.selected')
            ).map(card => card.dataset.id);
            
            if (selectedStudents.length === 0) {
                showNotification('error', '⚠️ Please select at least one student'); 
                return;
            }
            
            const homework = homeworkList.find(h => h.id == homeworkId);
            homework.status = 'assigned';
            homework.assignedTo = selectedStudents.length;
            homework.assignedAt = new Date().toLocaleString('en-US', {  
                year: 'numeric', 
                month: 'short', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            homework.assignedStudents = selectedStudents; 
            
            showNotification('success', `✅ Homework "${homework.title}" assigned to ${selectedStudents.length} student(s)!`);
            
            populateHomeworkSelect();   
            updateStatistics();
            displayHomeworkByMonth();
            
            // Update week view if active
            const activeView = document.querySelector('.view-toggle-btn.active');
            if (activeView && activeView.dataset.view === 'week') {
                const activeWeekTab = document.querySelector('.week-tab.active');
                if (activeWeekTab) {
                    displayWeekContent(activeWeekTab.dataset.week);
                }
            }
            
            // Switch to view tab
            setTimeout(() => {
                document.querySelector('[data-page="view"]').click();          
            }, 500);
        });

        // ==================== UTILITY FUNCTIONS ====================

        function setDefaultDueDate() {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            tomorrow.setHours(17, 0, 0, 0);
            document.getElementById('due-date').value = tomorrow.toISOString().slice(0, 16);
        }

        function showNotification(type, message) {
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            const color = type === 'success' ? '#1cc88a' : '#e74a3b';
            const bgColor = type === 'success' ? '#d4edda' : '#f8d7da';
            const borderColor = type === 'success' ? '#c3e6cb' : '#f5c6cb';
            
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
            
            div.innerHTML = `
                <i class="fas ${icon}" style="font-size: 20px;"></i>
                <div style="flex: 1;">${message}</div>
            `;
            
            document.body.appendChild(div);
            
            setTimeout(() => {
                div.style.animation = 'slideOut 0.3s ease-in';
                setTimeout(() => div.remove(), 300);
            }, 3000);
        }

        // Homework actions
        function viewHomework(id) {
            const homework = homeworkList.find(h => h.id == id);
            if (homework) {
                const dueDate = new Date(homework.dueDate);
                let message = `
                    📚 <strong>${homework.title}</strong>\n
                    📁 Department: ${homework.department.toUpperCase()}\n
                    📂 Category: ${homework.category}\n
                    👥 Class: ${homework.class}${homework.section} (${homework.branch})\n
                    📖 Subject: ${homework.subject}\n
                    📅 Due Date: ${dueDate.toLocaleDateString()} ${dueDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}\n
                    📝 Instructions: ${homework.instructions}\n
                    ⏰ Created: ${homework.createdAt}\n
                    📊 Status: ${homework.status === 'assigned' ? `Assigned to ${homework.assignedTo} students` : 'Not assigned yet'}
                `;
                
                showNotification('info', message);
            }
        }
        
        function editHomework(id) {
            const homework = homeworkList.find(h => h.id == id);
            if (homework && homework.status === 'created') {
                showNotification('info', 'Edit functionality would open a form to edit homework details.');
            }
        }
        
        function deleteHomework(id) {
            if (confirm('Are you sure you want to delete this homework?')) {
                homeworkList = homeworkList.filter(h => h.id !== id);
                showNotification('success', 'Homework deleted successfully!');
                populateHomeworkSelect();
                updateStatistics();
                displayHomeworkByMonth();
                
                // Update week view if active
                const activeView = document.querySelector('.view-toggle-btn.active');
                if (activeView && activeView.dataset.view === 'week') {
                    const activeWeekTab = document.querySelector('.week-tab.active');
                    if (activeWeekTab) {
                        displayWeekContent(activeWeekTab.dataset.week);
                    }
                }
            }
        }

        // Add sample homework for demo
        function addSampleHomework() {
            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();
            const currentMonth = currentDate.getMonth(); 
            
            // Add homework for different dates
            const sampleDates = [
                new Date(currentYear, currentMonth, currentDate.getDate() + 1), // Tomorrow
                new Date(currentYear, currentMonth, currentDate.getDate() + 2), // Day after tomorrow
                new Date(currentYear, currentMonth, currentDate.getDate() + 3), // 3 days
                new Date(currentYear, currentMonth, currentDate.getDate() + 5), // 5 days
                new Date(currentYear, currentMonth, currentDate.getDate() + 7), // 1 week
                new Date(currentYear, currentMonth, currentDate.getDate() + 10), // 10 days
                new Date(currentYear, currentMonth + 1, 5), // Next month
                new Date(currentYear, currentMonth + 1, 15), // Next month
            ];
            
            const subjects = ['Mathematics', 'Science', 'English', 'Physics', 'Chemistry', 'Biology', 'History', 'Geography'];
            
            sampleDates.forEach((date, index) => {
                const subject = subjects[index % subjects.length];
                
                homeworkList.push({
                    id: Date.now() + index,
                    title: `${subject} Assignment ${index + 1}`,
                    department: "academics",
                    category: index % 2 === 0 ? "Science" : "Mathematics",
                    class: "10",
                    branch: "10th",
                    section: index % 2 === 0 ? "A" : "B",
                    subject: subject,
                    dueDate: date.toISOString().slice(0, 16),
                    instructions: `Complete chapter ${index + 1} exercises. Show all calculations and submit before due date. Make sure to follow all instructions carefully.`,
                    createdAt: new Date(date.getTime() - 86400000).toLocaleString('en-US', { 
                        year: 'numeric', 
                        month: 'short', 
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }),
                    status: index % 3 === 0 ? 'created' : 'assigned',
                    assignedTo: index % 3 === 0 ? 0 : Math.floor(Math.random() * 10) + 1,
                    assignedStudents: index % 3 === 0 ? [] : Array.from({length: Math.floor(Math.random() * 10) + 1}, (_, i) => i + 1)
                });
            });
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
    </script>
@endsection
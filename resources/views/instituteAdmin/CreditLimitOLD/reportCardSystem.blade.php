@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <title>Complete Report Card System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }


        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            padding: 30px 0;
            background: var(--gradient-primary);
            color: white;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 30px;
        }

        @media (max-width: 1200px) {
            .main-content {
                grid-template-columns: 1fr;
            }
        }

        .panel {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }

        .panel-title {
            font-size: 1.5rem;
            color: var(--secondary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .panel-title i {
            margin-right: 10px;
        }

        .tabs {
            display: flex;
            border-bottom: 2px solid #eee;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .tab {
            padding: 12px 25px;
            cursor: pointer;
            font-weight: 600;
            color: #666;
            transition: all 0.3s;
            border-bottom: 3px solid transparent;
            white-space: nowrap;
        }

        .tab:hover {
            color: var(--primary-color);
        }

        .tab.active {
            color: var(--primary-color);
            border-bottom: 3px solid var(--primary-color);
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.5s;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .student-list {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .student-item {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            background: #f8f9ff;
        }

        .student-item:hover {
            background-color: #eef1ff;
            transform: translateY(-3px);
        }

        .student-item.selected {
            border-color: var(--primary-color);
            background-color: #e6e9ff;
        }

        .student-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .student-info h3 {
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .student-info p {
            color: #666;
            font-size: 0.85rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--secondary-color);
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 25px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn:hover {
            background: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: var(--shadow);
        }

        .btn-success {
            background: var(--success-color);
        }

        .btn-success:hover {
            background: #3a9c35;
        }

        .btn-warning {
            background: var(--warning-color);
        }

        .btn-warning:hover {
            background: #e68a00;
        }

        .btn-danger {
            background: var(--danger-color);
        }

        .btn-danger:hover {
            background: #c1121f;
        }

        .btn-sm {
            padding: 8px 15px;
            font-size: 0.9rem;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .field-controls {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .dynamic-fields-list {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        .field-item {
            display: flex;
            align-items: center;
            padding: 12px;
            background: #f8f9ff;
            border-radius: 8px;
            margin-bottom: 10px;
            border-left: 4px solid var(--primary-color);
        }

        .field-name {
            flex: 1;
            font-weight: 600;
        }

        .field-type {
            background: #e9ecef;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-right: 10px;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .design-options {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .design-option {
            border: 3px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }

        .design-option:hover {
            border-color: var(--primary-color);
            transform: translateY(-5px);
        }

        .design-option.selected {
            border-color: var(--primary-color);
            background-color: #f0f2ff;
        }

        .design-preview {
            height: 120px;
            margin-bottom: 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            color: white;
        }

        .design-1 .design-preview { background: linear-gradient(135deg, #4361ee, #3a0ca3); }
        .design-2 .design-preview { background: linear-gradient(135deg, #4bb543, #2a9d40); }
        .design-3 .design-preview { background: linear-gradient(135deg, #ff9e00, #e76f51); }
        .design-4 .design-preview { background: linear-gradient(135deg, #7209b7, #560bad); }
        .design-5 .design-preview { background: linear-gradient(135deg, #e63946, #c1121f); }

        .report-card-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: var(--shadow);
            min-height: 600px;
            position: relative;
        }

        .report-card-preview {
            height: 100%;
            overflow-y: auto;
            padding: 20px;
            border: 2px dashed #ddd;
            border-radius: 10px;
        }

        .pdf-controls {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
        }

        /* Marks Entry Styles */
        .marks-entry-container {
            max-height: 500px;
            overflow-y: auto;
        }

        .marks-subject-group {
            background: #f8f9ff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            border-left: 4px solid var(--primary-color);
        }

        .marks-subject-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .subject-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .marks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .mark-input-group {
            display: flex;
            flex-direction: column;
        }

        .mark-input-group label {
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .mark-input {
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
        }

        .mark-input:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .grade-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-left: 10px;
        }

        .grade-a { background-color: #d4edda; color: #155724; }
        .grade-b { background-color: #cce5ff; color: #004085; }
        .grade-c { background-color: #fff3cd; color: #856404; }
        .grade-d { background-color: #f8d7da; color: #721c24; }
        .grade-f { background-color: #dc3545; color: white; }

        .marks-summary {
            background: var(--gradient-primary);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            text-align: center;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Report Card Design Templates */
        .report-card {
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-family: 'Times New Roman', Times, serif;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Design 1: Classic */
        .report-card.classic {
            border: 2px solid var(--primary-color);
            background: linear-gradient(to bottom, white, #f8f9ff);
        }

        .report-card.classic .header {
            text-align: center;
            border-bottom: 3px double var(--primary-color);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .report-card.classic .school-name {
            font-size: 2.2rem;
            color: var(--primary-color);
            margin-bottom: 5px;
            font-weight: bold;
        }

        .report-card.classic .report-title {
            font-size: 1.8rem;
            color: var(--secondary-color);
            margin-bottom: 10px;
        }

        /* Design 2: Modern */
        .report-card.modern {
            background: white;
            border-left: 10px solid var(--success-color);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .report-card.modern .header {
            background: var(--gradient-primary);
            color: white;
            padding: 25px;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 30px -30px;
        }

        .report-card.modern .school-name {
            font-size: 2rem;
            margin-bottom: 5px;
        }

        /* Design 3: Elegant */
        .report-card.elegant {
            background: #fffaf0;
            border: 1px solid #e6d4b3;
            font-family: 'Georgia', serif;
        }

        .report-card.elegant .header {
            text-align: center;
            padding-bottom: 20px;
            margin-bottom: 30px;
            position: relative;
        }

        .report-card.elegant .header:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 25%;
            width: 50%;
            height: 1px;
            background: linear-gradient(to right, transparent, #d4af37, transparent);
        }

        .report-card.elegant .school-name {
            font-size: 2.5rem;
            color: #8b7355;
            font-weight: normal;
            letter-spacing: 2px;
        }

        /* Design 4: Minimal */
        .report-card.minimal {
            background: white;
            border: none;
            box-shadow: none;
        }

        .report-card.minimal .header {
            text-align: center;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .report-card.minimal .school-name {
            font-size: 1.8rem;
            color: var(--dark-color);
            font-weight: 300;
            letter-spacing: 5px;
        }

        /* Design 5: Colorful */
        .report-card.colorful {
            background: linear-gradient(135deg, #f8f9ff, #eef1ff);
            border-radius: 20px;
            border: 5px solid transparent;
            border-image: var(--gradient-primary) 1;
        }

        .report-card.colorful .header {
            text-align: center;
            padding: 20px;
            background: var(--gradient-primary);
            color: white;
            border-radius: 15px 15px 0 0;
            margin: -30px -30px 30px -30px;
        }

        /* Common report card elements */
        .student-info-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .info-item {
            margin-bottom: 10px;
        }

        .info-label {
            font-weight: bold;
            color: var(--secondary-color);
            margin-right: 10px;
        }

        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
        }

        .grades-table th {
            background: var(--gradient-primary);
            color: white;
            padding: 15px;
            text-align: left;
        }

        .grades-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }

        .grades-table tr:hover {
            background-color: #f5f5f5;
        }

        .remarks-section {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
        }

        .signature {
            text-align: center;
        }

        .signature-line {
            width: 200px;
            border-top: 1px solid #333;
            margin: 30px auto 10px;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            color: #888;
            font-size: 0.9rem;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .loading.active {
            display: flex;
        }

        .spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #888;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #ddd;
        }

        .highlight {
            color: var(--primary-color);
            font-weight: 600;
        }

        .marks-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .quick-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .quick-action-btn {
            padding: 8px 15px;
            background: #e9ecef;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .quick-action-btn:hover {
            background: #dee2e6;
        }

        .status-message {
            padding: 10px 15px;
            margin: 10px 0;
            border-radius: 6px;
            display: none;
        }

        .status-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }

        .status-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }
        
        .marks-info-box {
            background: #e6f7ff;
            border-left: 4px solid #1890ff;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        
        .current-student-display {
            font-size: 1rem;
            color: var(--primary-color);
            font-weight: 600;
            margin-left: 10px;
        }
        
        /* Preview indicator */
        .preview-indicator {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--primary-color);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            z-index: 5;
        }
        
        /* Auto-save indicator */
        .auto-save-indicator {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--success-color);
            color: white;
            padding: 10px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: none;
            z-index: 100;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }
        
        .auto-save-indicator.show {
            display: block;
            animation: fadeInOut 2s ease-in-out;
        }
        
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(10px); }
            20% { opacity: 1; transform: translateY(0); }
            80% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-10px); }
        }
    </style>

    <div class="container-fluid">
        <header>
            <h1><i class="fas fa-graduation-cap"></i> Complete Report Card System</h1>
            <p class="subtitle">Manage students, enter marks, customize designs, and export professional report cards</p>
        </header>

        <div class="tabs">
            <div class="tab active" data-tab="students">Manage Students</div>
            <div class="tab" data-tab="marks">Enter Marks</div>
            <div class="tab" data-tab="fields">Customize Fields</div>
            <div class="tab" data-tab="design">Choose Design</div>
            <div class="tab" data-tab="preview">Preview & Export</div>
        </div>

        <div class="main-content">
            <div class="left-panel">
                <!-- Students Management Tab -->
                <div class="tab-content active" id="students-tab">
                    <div class="panel">
                        <h2 class="panel-title"><i class="fas fa-users"></i> Students List</h2>
                        <div class="button-group">
                            <button class="btn btn-success btn-sm" id="addStudentBtn"><i class="fas fa-plus"></i> Add Student</button>
                            <button class="btn btn-warning btn-sm" id="selectAllBtn"><i class="fas fa-check-square"></i> Select All</button>
                            <button class="btn btn-danger btn-sm" id="deselectAllBtn"><i class="fas fa-times-circle"></i> Deselect All</button>
                        </div>
                        <div class="student-list" id="studentList">
                            <!-- Student list will be populated here -->
                        </div>
                    </div>

                    <div class="panel">
                        <h2 class="panel-title"><i class="fas fa-user-edit"></i> Student Details</h2>
                        <form id="studentForm">
                            <input type="hidden" id="studentId">
                            
                            <div class="form-group">
                                <label for="studentName">Full Name</label>
                                <input type="text" id="studentName" required placeholder="Enter student's full name">
                            </div>
                            
                            <div class="form-group">
                                <label for="rollNumber">Roll Number</label>
                                <input type="text" id="rollNumber" required placeholder="Enter roll number">
                            </div>
                            
                            <div class="form-group">
                                <label for="studentClass">Class/Grade</label>
                                <input type="text" id="studentClass" required placeholder="e.g., 10th Grade">
                            </div>
                            
                            <div class="form-group">
                                <label for="studentSection">Section</label>
                                <input type="text" id="studentSection" placeholder="e.g., A">
                            </div>
                            
                            <div class="form-group">
                                <label for="studentDOB">Date of Birth</label>
                                <input type="date" id="studentDOB">
                            </div>
                            
                            <div class="form-group">
                                <label for="parentName">Parent/Guardian Name</label>
                                <input type="text" id="parentName" placeholder="Enter parent's name">
                            </div>
                            
                            <div class="button-group">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Student</button>
                                <button type="button" id="clearStudentBtn" class="btn btn-warning"><i class="fas fa-eraser"></i> Clear</button>
                                <button type="button" id="deleteStudentBtn" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Marks Entry Tab -->
                <div class="tab-content" id="marks-tab">
                    <div class="panel">
                        <h2 class="panel-title"><i class="fas fa-edit"></i> Enter Student Marks 
                            <span id="currentMarksStudent" class="current-student-display"></span>
                        </h2>
                        
                        <div class="marks-info-box">
                            <p><strong>Note:</strong> The report card preview updates automatically as you select a student or change marks. 
                            Use "Save All Marks" to save to database.</p>
                        </div>
                        
                        <div id="marksStatusMessage" class="status-message"></div>
                        
                        <div class="form-group">
                            <label for="marksStudentSelect">Select Student</label>
                            <select id="marksStudentSelect">
                                <option value="">-- Choose a student --</option>
                            </select>
                        </div>
                        
                        <div class="quick-actions">
                            <button class="quick-action-btn" id="copyMarksBtn"><i class="fas fa-copy"></i> Copy from another student</button>
                            <button class="quick-action-btn" id="clearMarksBtn"><i class="fas fa-eraser"></i> Clear all marks</button>
                            <button class="quick-action-btn" id="autoGenerateBtn"><i class="fas fa-magic"></i> Auto-generate marks</button>
                        </div>
                        
                        <div class="marks-entry-container" id="marksEntryContainer">
                            <!-- Marks entry fields will be populated here -->
                        </div>
                        
                        <div class="marks-summary" id="marksSummary">
                            <!-- Marks summary will be populated here -->
                        </div>
                        
                        <div class="marks-actions">
                            <button class="btn btn-success" id="saveMarksBtn"><i class="fas fa-save"></i> Save All Marks</button>
                            <button class="btn" id="saveAndNextBtn"><i class="fas fa-save"></i> Save & Next Student</button>
                        </div>
                    </div>
                    
                    <div class="panel">
                        <h2 class="panel-title"><i class="fas fa-chart-line"></i> Class Performance Overview</h2>
                        <div id="classPerformance">
                            <!-- Class performance chart will be here -->
                        </div>
                        <div class="button-group">
                            <button class="btn btn-sm" id="viewClassStatsBtn"><i class="fas fa-chart-bar"></i> View Class Statistics</button>
                            <button class="btn btn-sm btn-warning" id="bulkUpdateBtn"><i class="fas fa-sync-alt"></i> Bulk Update Marks</button>
                        </div>
                    </div>
                </div>

                <!-- Customize Fields Tab -->
                <div class="tab-content" id="fields-tab">
                    <div class="panel">
                        <h2 class="panel-title"><i class="fas fa-list-alt"></i> Dynamic Fields Setup</h2>
                        <p>Add custom fields to include in report cards. These will appear in addition to standard fields.</p>
                        
                        <div class="field-controls">
                            <input type="text" id="newFieldName" placeholder="Field Name (e.g., Art, Music)" style="flex: 2;">
                            <select id="newFieldType" style="flex: 1;">
                                <option value="number">Number (0-100)</option>
                                <option value="text">Text</option>
                                <option value="grade">Grade (A-F)</option>
                                <option value="percentage">Percentage</option>
                            </select>
                            <button class="btn btn-success" id="addFieldBtn"><i class="fas fa-plus"></i> Add</button>
                        </div>
                        
                        <div class="dynamic-fields-list" id="dynamicFieldsList">
                            <!-- Dynamic fields will be listed here -->
                        </div>
                        
                        <h3 class="panel-title"><i class="fas fa-cog"></i> Report Card Settings</h3>
                        <div class="form-group">
                            <label for="schoolName">School Name</label>
                            <input type="text" id="schoolName" value="Greenwood International School" placeholder="Enter school name">
                        </div>
                        
                        <div class="form-group">
                            <label for="schoolAddress">School Address</label>
                            <textarea id="schoolAddress" rows="3" placeholder="Enter school address">123 Education Lane, Knowledge City</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="academicYear">Academic Year</label>
                            <input type="text" id="academicYear" value="2023-2024" placeholder="e.g., 2023-2024">
                        </div>
                        
                        <div class="form-group">
                            <label for="principalName">Principal's Name</label>
                            <input type="text" id="principalName" value="Dr. Robert Johnson" placeholder="Enter principal's name">
                        </div>
                        
                        <div class="checkbox-group">
                            <input type="checkbox" id="showLogo" checked>
                            <label for="showLogo">Show school logo placeholder</label>
                        </div>
                        
                        <div class="checkbox-group">
                            <input type="checkbox" id="showWatermark" checked>
                            <label for="showWatermark">Show "OFFICIAL TRANSCRIPT" watermark</label>
                        </div>
                    </div>
                </div>

                <!-- Design Selection Tab -->
                <div class="tab-content" id="design-tab">
                    <div class="panel">
                        <h2 class="panel-title"><i class="fas fa-palette"></i> Choose Report Card Design</h2>
                        <p>Select a design template for your report cards. You can preview each design on the right.</p>
                        
                        <div class="design-options" id="designOptions">
                            <div class="design-option design-1 selected" data-design="classic">
                                <div class="design-preview">Classic Design</div>
                                <h3>Classic</h3>
                                <p>Traditional format with formal styling</p>
                            </div>
                            
                            <div class="design-option design-2" data-design="modern">
                                <div class="design-preview">Modern Design</div>
                                <h3>Modern</h3>
                                <p>Clean layout with gradient headers</p>
                            </div>
                            
                            <div class="design-option design-3" data-design="elegant">
                                <div class="design-preview">Elegant Design</div>
                                <h3>Elegant</h3>
                                <p>Sophisticated styling for premium look</p>
                            </div>
                            
                            <div class="design-option design-4" data-design="minimal">
                                <div class="design-preview">Minimal Design</div>
                                <h3>Minimal</h3>
                                <p>Simple and clean, focused on content</p>
                            </div>
                            
                            <div class="design-option design-5" data-design="colorful">
                                <div class="design-preview">Colorful Design</div>
                                <h3>Colorful</h3>
                                <p>Vibrant colors for younger students</p>
                            </div>
                        </div>
                        
                        <h3 class="panel-title" style="margin-top: 30px;"><i class="fas fa-sliders-h"></i> Customize Colors</h3>
                        
                        <div class="form-group">
                            <label for="primaryColor">Primary Color</label>
                            <input type="color" id="primaryColor" value="#4361ee">
                        </div>
                        
                        <div class="form-group">
                            <label for="secondaryColor">Secondary Color</label>
                            <input type="color" id="secondaryColor" value="#3a0ca3">
                        </div>
                        
                        <button class="btn" id="applyColorsBtn"><i class="fas fa-paint-brush"></i> Apply Colors to Design</button>
                    </div>
                </div>

                <!-- Preview & Export Tab -->
                <div class="tab-content" id="preview-tab">
                    <div class="panel">
                        <h2 class="panel-title"><i class="fas fa-file-export"></i> Export Options</h2>
                        
                        <div class="form-group">
                            <label>Select Students to Export</label>
                            <div class="student-list" style="max-height: 200px; margin-bottom: 20px;" id="exportStudentList">
                                <!-- Export student list will be populated here -->
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="exportFormat">Export Format</label>
                            <select id="exportFormat">
                                <option value="pdf">PDF Document</option>
                                <option value="png">PNG Image</option>
                                <option value="combined">Combined PDF (All Students)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="fileName">File Name</label>
                            <input type="text" id="fileName" value="Report_Card" placeholder="Enter file name">
                        </div>
                        
                        <div class="checkbox-group">
                            <input type="checkbox" id="includeMarks" checked>
                            <label for="includeMarks">Include detailed marks</label>
                        </div>
                        
                        <div class="checkbox-group">
                            <input type="checkbox" id="includeRemarks" checked>
                            <label for="includeRemarks">Include teacher remarks</label>
                        </div>
                        
                        <div class="checkbox-group">
                            <input type="checkbox" id="includeSignatures" checked>
                            <label for="includeSignatures">Include signature sections</label>
                        </div>
                        
                        <div class="button-group">
                            <button class="btn btn-success" id="exportSingleBtn"><i class="fas fa-file-pdf"></i> Export Current</button>
                            <button class="btn btn-primary" id="exportSelectedBtn"><i class="fas fa-file-archive"></i> Export Selected</button>
                            <button class="btn" id="printBtn"><i class="fas fa-print"></i> Print</button>
                        </div>
                        
                        <div class="form-group" style="margin-top: 30px;">
                            <label for="remarksTemplate">Remarks Template</label>
                            <select id="remarksTemplate">
                                <option value="default">Default Remarks</option>
                                <option value="positive">Positive Performance</option>
                                <option value="average">Average Performance</option>
                                <option value="improve">Needs Improvement</option>
                                <option value="custom">Custom Remarks</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="customRemarks">Custom Remarks</label>
                            <textarea id="customRemarks" rows="4" placeholder="Enter custom remarks for all selected students">Shows good understanding of concepts. Consistent performance throughout the term. Should participate more in class discussions.</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="right-panel">
                <div class="report-card-container">
                    <div class="preview-indicator" id="previewIndicator">Preview</div>
                    <div class="pdf-controls">
                        <button class="btn btn-sm" id="refreshPreviewBtn"><i class="fas fa-sync-alt"></i> Refresh Preview</button>
                    </div>
                    
                    <div class="report-card-preview" id="reportCardPreview">
                        <!-- Report card preview will be rendered here -->
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Complete Report Card System &copy; 2023 | <span class="highlight">All data is stored locally in your browser</span></p>
            <p>Use the tabs above to navigate between different sections of the system</p>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading" id="loadingOverlay">
        <div class="spinner"></div>
        <h3>Generating PDF...</h3>
        <p>Please wait while we create your report cards</p>
    </div>

    <!-- Auto-save indicator -->
    <div class="auto-save-indicator" id="autoSaveIndicator">
        <i class="fas fa-check-circle"></i> Preview Updated
    </div>

    <script>
        // Initialize global variables
        const { jsPDF } = window.jspdf;
        let students = [];
        let selectedStudents = new Set();
        let customFields = [];
        let currentDesign = 'classic';
        let schoolConfig = {
            name: "Greenwood International School",
            address: "123 Education Lane, Knowledge City",
            year: "2023-2024",
            principal: "Dr. Robert Johnson",
            showLogo: true,
            showWatermark: true
        };

        // Define standard subjects with full configuration
        const standardSubjects = [
            {
                id: 'math',
                name: 'Mathematics',
                type: 'number',
                maxMarks: 100,
                weightage: 1.0,
                components: [
                    { name: 'Theory', max: 80, weight: 0.8 },
                    { name: 'Practical', max: 20, weight: 0.2 }
                ]
            },
            {
                id: 'science',
                name: 'Science',
                type: 'number',
                maxMarks: 100,
                weightage: 1.0,
                components: [
                    { name: 'Physics', max: 40, weight: 0.4 },
                    { name: 'Chemistry', max: 30, weight: 0.3 },
                    { name: 'Biology', max: 30, weight: 0.3 }
                ]
            },
            {
                id: 'english',
                name: 'English',
                type: 'number',
                maxMarks: 100,
                weightage: 1.0,
                components: [
                    { name: 'Grammar', max: 30, weight: 0.3 },
                    { name: 'Comprehension', max: 40, weight: 0.4 },
                    { name: 'Composition', max: 30, weight: 0.3 }
                ]
            },
            {
                id: 'history',
                name: 'History',
                type: 'number',
                maxMarks: 100,
                weightage: 0.8,
                components: [
                    { name: 'Written Exam', max: 80, weight: 0.8 },
                    { name: 'Project Work', max: 20, weight: 0.2 }
                ]
            },
            {
                id: 'computer',
                name: 'Computer Science',
                type: 'number',
                maxMarks: 100,
                weightage: 1.2,
                components: [
                    { name: 'Theory', max: 60, weight: 0.6 },
                    { name: 'Practical', max: 40, weight: 0.4 }
                ]
            }
        ];

        // Sample data with more students
        const sampleStudents = [
            {
                id: 1,
                name: "Alex Johnson",
                rollNumber: "S001",
                class: "10th Grade",
                section: "A",
                dob: "2008-05-15",
                parent: "Michael Johnson",
                avatar: "AJ",
                marks: {
                    math: { total: 92, components: { theory: 75, practical: 17 } },
                    science: { total: 88, components: { physics: 35, chemistry: 27, biology: 26 } },
                    english: { total: 85, components: { grammar: 26, comprehension: 34, composition: 25 } },
                    history: { total: 78, components: { written: 63, project: 15 } },
                    computer: { total: 95, components: { theory: 58, practical: 37 } }
                },
                attendance: {
                    total: 180,
                    present: 172,
                    percentage: 95.6
                },
                remarks: "Excellent performance. Shows strong analytical skills."
            },
            {
                id: 2,
                name: "Maria Garcia",
                rollNumber: "S002",
                class: "10th Grade",
                section: "B",
                dob: "2008-08-22",
                parent: "Carlos Garcia",
                avatar: "MG",
                marks: {
                    math: { total: 78, components: { theory: 62, practical: 16 } },
                    science: { total: 82, components: { physics: 33, chemistry: 25, biology: 24 } },
                    english: { total: 90, components: { grammar: 28, comprehension: 36, composition: 26 } },
                    history: { total: 88, components: { written: 71, project: 17 } },
                    computer: { total: 85, components: { theory: 51, practical: 34 } }
                },
                attendance: {
                    total: 180,
                    present: 175,
                    percentage: 97.2
                },
                remarks: "Consistent performer. Excellent language skills."
            },
            {
                id: 3,
                name: "David Smith",
                rollNumber: "S003",
                class: "9th Grade",
                section: "A",
                dob: "2009-02-10",
                parent: "Sarah Smith",
                avatar: "DS",
                marks: {
                    math: { total: 65, components: { theory: 52, practical: 13 } },
                    science: { total: 70, components: { physics: 28, chemistry: 21, biology: 21 } },
                    english: { total: 72, components: { grammar: 22, comprehension: 29, composition: 21 } },
                    history: { total: 68, components: { written: 55, project: 13 } },
                    computer: { total: 80, components: { theory: 48, practical: 32 } }
                },
                attendance: {
                    total: 180,
                    present: 165,
                    percentage: 91.7
                },
                remarks: "Needs to work on mathematics and science concepts."
            },
            {
                id: 4,
                name: "Sarah Williams",
                rollNumber: "S004",
                class: "10th Grade",
                section: "A",
                dob: "2008-11-30",
                parent: "James Williams",
                avatar: "SW",
                marks: {
                    math: { total: 95, components: { theory: 77, practical: 18 } },
                    science: { total: 92, components: { physics: 37, chemistry: 28, biology: 27 } },
                    english: { total: 88, components: { grammar: 27, comprehension: 35, composition: 26 } },
                    history: { total: 90, components: { written: 73, project: 17 } },
                    computer: { total: 96, components: { theory: 58, practical: 38 } }
                },
                attendance: {
                    total: 180,
                    present: 178,
                    percentage: 98.9
                },
                remarks: "Outstanding performance across all subjects."
            }
        ];

        // Initialize custom fields
        const sampleFields = [
            { id: 1, name: "Art", type: "number", value: 85, maxMarks: 100 },
            { id: 2, name: "Music", type: "number", value: 90, maxMarks: 100 },
            { id: 3, name: "Physical Education", type: "grade", value: "A" },
            { id: 4, name: "Attendance", type: "percentage", value: "95%" }
        ];

        // DOM Elements
        const studentListEl = document.getElementById('studentList');
        const exportStudentListEl = document.getElementById('exportStudentList');
        const studentForm = document.getElementById('studentForm');
        const dynamicFieldsListEl = document.getElementById('dynamicFieldsList');
        const designOptionsEl = document.getElementById('designOptions');
        const reportCardPreviewEl = document.getElementById('reportCardPreview');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const marksEntryContainer = document.getElementById('marksEntryContainer');
        const marksSummaryEl = document.getElementById('marksSummary');
        const marksStudentSelect = document.getElementById('marksStudentSelect');
        const marksStatusMessage = document.getElementById('marksStatusMessage');
        const currentMarksStudentDisplay = document.getElementById('currentMarksStudent');
        const previewIndicator = document.getElementById('previewIndicator');
        const autoSaveIndicator = document.getElementById('autoSaveIndicator');
        
        // Tab elements
        const tabs = document.querySelectorAll('.tab');
        const tabContents = document.querySelectorAll('.tab-content');
        
        // Form inputs
        const studentIdInput = document.getElementById('studentId');
        const studentNameInput = document.getElementById('studentName');
        const rollNumberInput = document.getElementById('rollNumber');
        const studentClassInput = document.getElementById('studentClass');
        const studentSectionInput = document.getElementById('studentSection');
        const studentDOBInput = document.getElementById('studentDOB');
        const parentNameInput = document.getElementById('parentName');
        
        // Settings inputs
        const schoolNameInput = document.getElementById('schoolName');
        const schoolAddressInput = document.getElementById('schoolAddress');
        const academicYearInput = document.getElementById('academicYear');
        const principalNameInput = document.getElementById('principalName');
        const showLogoInput = document.getElementById('showLogo');
        const showWatermarkInput = document.getElementById('showWatermark');
        
        // Field management
        const newFieldNameInput = document.getElementById('newFieldName');
        const newFieldTypeInput = document.getElementById('newFieldType');
        
        // Design inputs
        const primaryColorInput = document.getElementById('primaryColor');
        const secondaryColorInput = document.getElementById('secondaryColor');
        
        // Export inputs
        const exportFormatInput = document.getElementById('exportFormat');
        const fileNameInput = document.getElementById('fileName');
        const includeMarksInput = document.getElementById('includeMarks');
        const includeRemarksInput = document.getElementById('includeRemarks');
        const includeSignaturesInput = document.getElementById('includeSignatures');
        const remarksTemplateInput = document.getElementById('remarksTemplate');
        const customRemarksInput = document.getElementById('customRemarks');

        // Current marks student
        let currentMarksStudentId = null;
        let previewUpdateTimeout = null;
        let currentPreviewStudent = null;

        // Initialize the application
        function init() {
            // Load data from localStorage or use sample data
            const savedStudents = localStorage.getItem('studentReportCardData');
            const savedFields = localStorage.getItem('customFieldsData');
            const savedConfig = localStorage.getItem('schoolConfig');
            
            students = savedStudents ? JSON.parse(savedStudents) : [...sampleStudents];
            customFields = savedFields ? JSON.parse(savedFields) : [...sampleFields];
            
            if (savedConfig) {
                schoolConfig = JSON.parse(savedConfig);
                updateConfigInputs();
            }
            
            // Set up event listeners
            setupEventListeners();
            
            // Initialize UI
            renderStudentList();
            renderExportStudentList();
            renderDynamicFields();
            populateMarksStudentSelect();
            renderReportCardPreview();
            
            // Select first student by default
            if (students.length > 0) {
                selectStudent(students[0].id);
                selectMarksStudent(students[0].id);
                // Update preview indicator
                updatePreviewIndicator();
            }
        }

        // Set up all event listeners
        function setupEventListeners() {
            // Tab switching
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    switchTab(tabId);
                });
            });
            
            // Student form
            studentForm.addEventListener('submit', handleStudentSubmit);
            
            // Student buttons
            document.getElementById('addStudentBtn').addEventListener('click', addNewStudent);
            document.getElementById('clearStudentBtn').addEventListener('click', clearStudentForm);
            document.getElementById('deleteStudentBtn').addEventListener('click', deleteStudent);
            document.getElementById('selectAllBtn').addEventListener('click', selectAllStudents);
            document.getElementById('deselectAllBtn').addEventListener('click', deselectAllStudents);
            
            // Marks entry
            marksStudentSelect.addEventListener('change', function() {
                const studentId = parseInt(this.value);
                if (studentId) {
                    selectMarksStudent(studentId);
                    // Update preview when student is selected
                    updatePreviewFromMarksTab();
                }
            });
            
            document.getElementById('saveMarksBtn').addEventListener('click', function() {
                saveAllMarks(false); // false means don't go to next student
            });
            document.getElementById('saveAndNextBtn').addEventListener('click', function() {
                saveAllMarks(true); // true means go to next student after saving
            });
            document.getElementById('clearMarksBtn').addEventListener('click', clearAllMarks);
            document.getElementById('copyMarksBtn').addEventListener('click', copyMarksFromAnother);
            document.getElementById('autoGenerateBtn').addEventListener('click', autoGenerateMarks);
            document.getElementById('bulkUpdateBtn').addEventListener('click', bulkUpdateMarks);
            document.getElementById('viewClassStatsBtn').addEventListener('click', viewClassStatistics);
            
            // Field management
            document.getElementById('addFieldBtn').addEventListener('click', addCustomField);
            
            // Design selection
            designOptionsEl.addEventListener('click', handleDesignSelection);
            document.getElementById('applyColorsBtn').addEventListener('click', applyCustomColors);
            
            // Settings inputs
            [schoolNameInput, schoolAddressInput, academicYearInput, principalNameInput].forEach(input => {
                input.addEventListener('change', updateSchoolConfig);
            });
            
            showLogoInput.addEventListener('change', updateSchoolConfig);
            showWatermarkInput.addEventListener('change', updateSchoolConfig);
            
            // Export buttons
            document.getElementById('exportSingleBtn').addEventListener('click', exportCurrentReportCard);
            document.getElementById('exportSelectedBtn').addEventListener('click', exportSelectedReportCards);
            document.getElementById('printBtn').addEventListener('click', printReportCard);
            document.getElementById('refreshPreviewBtn').addEventListener('click', renderReportCardPreview);
            
            // Update preview when settings change
            [remarksTemplateInput, customRemarksInput].forEach(input => {
                input.addEventListener('change', renderReportCardPreview);
            });
        }

        // Tab switching function
        function switchTab(tabId) {
            // Update active tab
            tabs.forEach(tab => {
                tab.classList.toggle('active', tab.getAttribute('data-tab') === tabId);
            });
            
            // Show active tab content
            tabContents.forEach(content => {
                content.classList.toggle('active', content.id === `${tabId}-tab`);
            });
            
            // Update preview indicator based on current tab
            updatePreviewIndicator();
            
            // Refresh preview when switching to preview tab
            if (tabId === 'preview') {
                renderExportStudentList();
                renderReportCardPreview();
            }
            
            // Refresh marks when switching to marks tab
            if (tabId === 'marks') {
                populateMarksStudentSelect();
                if (currentMarksStudentId) {
                    selectMarksStudent(currentMarksStudentId);
                    // Update preview to show current marks student
                    updatePreviewFromMarksTab();
                } else if (students.length > 0) {
                    selectMarksStudent(students[0].id);
                    updatePreviewFromMarksTab();
                }
            }
        }

        // Update preview indicator based on current tab
        function updatePreviewIndicator() {
            const activeTab = document.querySelector('.tab.active').getAttribute('data-tab');
            let indicatorText = "Preview";
            
            switch(activeTab) {
                case 'marks':
                    indicatorText = "Preview - Marks Entry Mode";
                    break;
                case 'design':
                    indicatorText = "Preview - Design Mode";
                    break;
                case 'preview':
                    indicatorText = "Preview - Export Mode";
                    break;
                case 'fields':
                    indicatorText = "Preview - Settings Mode";
                    break;
                default:
                    indicatorText = "Preview";
            }
            
            if (currentMarksStudentId && activeTab === 'marks') {
                const student = students.find(s => s.id === currentMarksStudentId);
                if (student) {
                    indicatorText = `Preview: ${student.name}`;
                }
            }
            
            previewIndicator.textContent = indicatorText;
        }

        // Show auto-save indicator
        function showAutoSaveIndicator() {
            autoSaveIndicator.classList.add('show');
            setTimeout(() => {
                autoSaveIndicator.classList.remove('show');
            }, 2000);
        }

        // Render student list
        function renderStudentList() {
            if (students.length === 0) {
                studentListEl.innerHTML = '<div class="empty-state"><p>No students added yet. Click "Add Student" to get started.</p></div>';
                return;
            }
            
            studentListEl.innerHTML = students.map(student => {
                const average = calculateStudentAverage(student);
                const grade = calculateGrade(average);
                const gradeClass = `grade-${grade.charAt(0).toLowerCase()}`;
                
                return `
                    <div class="student-item ${selectedStudents.has(student.id) ? 'selected' : ''}" data-id="${student.id}">
                        <div class="student-avatar">${student.avatar}</div>
                        <div class="student-info">
                            <h3>${student.name}</h3>
                            <p>${student.class} • Sec: ${student.section || 'N/A'} • Roll: ${student.rollNumber}</p>
                            <p>Avg: ${average}% <span class="grade-badge ${gradeClass}">${grade}</span></p>
                        </div>
                    </div>
                `;
            }).join('');
            
            // Add click event listeners
            document.querySelectorAll('.student-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    // Check if click was on a button inside
                    if (e.target.closest('.action-buttons')) return;
                    
                    const studentId = parseInt(this.getAttribute('data-id'));
                    
                    // If Ctrl or Cmd key is pressed, toggle selection
                    if (e.ctrlKey || e.metaKey) {
                        toggleStudentSelection(studentId);
                    } else {
                        // Otherwise, select single student
                        selectStudent(studentId);
                        // Update preview
                        renderReportCardPreview();
                        updatePreviewIndicator();
                    }
                });
            });
        }

        // Render export student list
        function renderExportStudentList() {
            if (students.length === 0) {
                exportStudentListEl.innerHTML = '<div class="empty-state"><p>No students to export.</p></div>';
                return;
            }
            
            exportStudentListEl.innerHTML = students.map(student => `
                <div class="checkbox-group">
                    <input type="checkbox" id="export-${student.id}" ${selectedStudents.has(student.id) ? 'checked' : ''} data-id="${student.id}">
                    <label for="export-${student.id}">${student.name} (${student.rollNumber}) - Avg: ${calculateStudentAverage(student)}%</label>
                </div>
            `).join('');
            
            // Add event listeners to checkboxes
            document.querySelectorAll('#exportStudentList input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const studentId = parseInt(this.getAttribute('data-id'));
                    if (this.checked) {
                        selectedStudents.add(studentId);
                    } else {
                        selectedStudents.delete(studentId);
                    }
                    updateStudentSelectionInList();
                });
            });
        }

        // Select a single student
        function selectStudent(id) {
            selectedStudents.clear();
            selectedStudents.add(id);
            updateStudentSelectionInList();
            
            const student = students.find(s => s.id === id);
            if (!student) return;
            
            // Populate form
            studentIdInput.value = student.id;
            studentNameInput.value = student.name;
            rollNumberInput.value = student.rollNumber;
            studentClassInput.value = student.class;
            studentSectionInput.value = student.section || '';
            studentDOBInput.value = student.dob || '';
            parentNameInput.value = student.parent || '';
            
            // Render preview
            renderReportCardPreview();
        }

        // Toggle student selection
        function toggleStudentSelection(id) {
            if (selectedStudents.has(id)) {
                selectedStudents.delete(id);
            } else {
                selectedStudents.add(id);
            }
            updateStudentSelectionInList();
        }

        // Select all students
        function selectAllStudents() {
            students.forEach(student => selectedStudents.add(student.id));
            updateStudentSelectionInList();
            renderExportStudentList();
        }

        // Deselect all students
        function deselectAllStudents() {
            selectedStudents.clear();
            updateStudentSelectionInList();
            renderExportStudentList();
        }

        // Update student selection in list
        function updateStudentSelectionInList() {
            document.querySelectorAll('.student-item').forEach(item => {
                const studentId = parseInt(item.getAttribute('data-id'));
                item.classList.toggle('selected', selectedStudents.has(studentId));
            });
        }

        // Populate marks student select dropdown
        function populateMarksStudentSelect() {
            if (students.length === 0) {
                marksStudentSelect.innerHTML = '<option value="">No students available</option>';
                return;
            }
            
            marksStudentSelect.innerHTML = '<option value="">-- Choose a student --</option>' +
                students.map(student => {
                    const average = calculateStudentAverage(student);
                    return `<option value="${student.id}">${student.name} (${student.rollNumber}) - Avg: ${average}%</option>`;
                }).join('');
            
            if (currentMarksStudentId) {
                marksStudentSelect.value = currentMarksStudentId;
            }
        }

        // Select student for marks entry
        function selectMarksStudent(id) {
            currentMarksStudentId = id;
            marksStudentSelect.value = id;
            
            const student = students.find(s => s.id === id);
            renderMarksEntry(student);
            
            // Update the current student display
            if (currentMarksStudentDisplay && student) {
                currentMarksStudentDisplay.textContent = `Currently editing: ${student.name}`;
            }
            
            // Update preview indicator
            updatePreviewIndicator();
        }

        // Update preview from marks tab when student is selected
        function updatePreviewFromMarksTab() {
            if (!currentMarksStudentId) {
                showStatusMessage('No student selected for marks entry.', 'error');
                return;
            }
            
            // Select this student in the main selection
            selectStudent(currentMarksStudentId);
            
            // Update the report card preview
            renderReportCardPreview();
            
            showStatusMessage(`Now viewing ${students.find(s => s.id === currentMarksStudentId).name}'s report card`, 'success');
        }

        // Render marks entry form
        function renderMarksEntry(student) {
            if (!student) {
                marksEntryContainer.innerHTML = '<div class="empty-state"><p>No student selected for marks entry.</p></div>';
                marksSummaryEl.innerHTML = '';
                return;
            }
            
            // Generate marks entry form
            let marksHTML = '';
            
            // Standard subjects
            standardSubjects.forEach(subject => {
                // Get marks from student data
                const subjectMarks = student.marks && student.marks[subject.id] ? 
                    student.marks[subject.id] : { total: 0, components: {} };
                
                // Ensure components object exists
                if (!subjectMarks.components) {
                    subjectMarks.components = {};
                }
                
                const grade = getGradeLetter(subjectMarks.total || 0);
                const gradeClass = `grade-${grade}`;
                
                marksHTML += `
                    <div class="marks-subject-group">
                        <div class="marks-subject-header">
                            <div class="subject-title">${subject.name}</div>
                            <div>
                                <span class="grade-badge ${gradeClass}">${grade.toUpperCase()}</span>
                                <span style="font-weight: 600; margin-left: 10px;">Total: ${subjectMarks.total || 0}/${subject.maxMarks}</span>
                            </div>
                        </div>
                        
                        <div class="marks-grid">
                            ${subject.components.map(component => {
                                const compKey = component.name.toLowerCase().replace(' ', '_');
                                const compValue = subjectMarks.components[compKey] || 0;
                                return `
                                    <div class="mark-input-group">
                                        <label for="marks-${student.id}-${subject.id}-${component.name}">${component.name} (Max: ${component.max})</label>
                                        <input type="number" 
                                               id="marks-${student.id}-${subject.id}-${component.name}"
                                               class="mark-input component-input"
                                               data-student="${student.id}"
                                               data-subject="${subject.id}"
                                               data-component="${component.name}"
                                               min="0" 
                                               max="${component.max}"
                                               value="${compValue}"
                                               placeholder="Enter marks">
                                    </div>
                                `;
                            }).join('')}
                            
                            <div class="mark-input-group">
                                <label for="marks-${student.id}-${subject.id}-total">Total Marks</label>
                                <input type="number" 
                                       id="marks-${student.id}-${subject.id}-total"
                                       class="mark-input total-marks"
                                       data-student="${student.id}"
                                       data-subject="${subject.id}"
                                       data-component="total"
                                       min="0" 
                                       max="${subject.maxMarks}"
                                       value="${subjectMarks.total || 0}"
                                       placeholder="Auto-calculated"
                                       readonly
                                       style="background-color: #f0f0f0;">
                            </div>
                        </div>
                    </div>
                `;
            });
            
            // Custom fields
            if (customFields.length > 0) {
                marksHTML += `<div class="marks-subject-group" style="border-left-color: var(--success-color);">
                    <div class="marks-subject-header">
                        <div class="subject-title">Additional Subjects</div>
                    </div>
                    <div class="marks-grid">`;
                
                customFields.forEach(field => {
                    const fieldValue = student.customFields && student.customFields[field.id] ? 
                        student.customFields[field.id] : field.value || '';
                    
                    marksHTML += `
                        <div class="mark-input-group">
                            <label for="marks-${student.id}-custom-${field.id}">${field.name}</label>
                            ${field.type === 'number' ? `
                                <input type="number" 
                                       id="marks-${student.id}-custom-${field.id}"
                                       class="mark-input custom-field"
                                       data-student="${student.id}"
                                       data-field="${field.id}"
                                       min="0" 
                                       max="100"
                                       value="${fieldValue}"
                                       placeholder="Enter marks">
                            ` : field.type === 'grade' ? `
                                <select id="marks-${student.id}-custom-${field.id}"
                                        class="mark-input custom-field"
                                        data-student="${student.id}"
                                        data-field="${field.id}">
                                    <option value="A+" ${fieldValue === 'A+' ? 'selected' : ''}>A+ (Outstanding)</option>
                                    <option value="A" ${fieldValue === 'A' ? 'selected' : ''}>A (Excellent)</option>
                                    <option value="B" ${fieldValue === 'B' ? 'selected' : ''}>B (Good)</option>
                                    <option value="C" ${fieldValue === 'C' ? 'selected' : ''}>C (Satisfactory)</option>
                                    <option value="D" ${fieldValue === 'D' ? 'selected' : ''}>D (Needs Improvement)</option>
                                    <option value="F" ${fieldValue === 'F' ? 'selected' : ''}>F (Fail)</option>
                                </select>
                            ` : field.type === 'percentage' ? `
                                <input type="number" 
                                       id="marks-${student.id}-custom-${field.id}"
                                       class="mark-input custom-field"
                                       data-student="${student.id}"
                                       data-field="${field.id}"
                                       min="0" 
                                       max="100"
                                       value="${parseInt(fieldValue) || 0}"
                                       placeholder="Enter percentage">
                                <span style="font-size: 0.9rem; color: #666;">%</span>
                            ` : `
                                <input type="text" 
                                       id="marks-${student.id}-custom-${field.id}"
                                       class="mark-input custom-field"
                                       data-student="${student.id}"
                                       data-field="${field.id}"
                                       value="${fieldValue}"
                                       placeholder="Enter value">
                            `}
                        </div>
                    `;
                });
                
                marksHTML += `</div></div>`;
            }
            
            // Attendance
            marksHTML += `
                <div class="marks-subject-group" style="border-left-color: var(--warning-color);">
                    <div class="marks-subject-header">
                        <div class="subject-title">Attendance</div>
                    </div>
                    <div class="marks-grid">
                        <div class="mark-input-group">
                            <label for="attendance-total-${student.id}">Total Working Days</label>
                            <input type="number" 
                                   id="attendance-total-${student.id}"
                                   class="mark-input attendance-input"
                                   data-student="${student.id}"
                                   data-type="total"
                                   min="0" 
                                   max="365"
                                   value="${student.attendance?.total || 180}"
                                   placeholder="Total days">
                        </div>
                        <div class="mark-input-group">
                            <label for="attendance-present-${student.id}">Days Present</label>
                            <input type="number" 
                                   id="attendance-present-${student.id}"
                                   class="mark-input attendance-input"
                                   data-student="${student.id}"
                                   data-type="present"
                                   min="0" 
                                   max="365"
                                   value="${student.attendance?.present || 172}"
                                   placeholder="Days present">
                        </div>
                        <div class="mark-input-group">
                            <label for="attendance-percentage-${student.id}">Attendance Percentage</label>
                            <input type="number" 
                                   id="attendance-percentage-${student.id}"
                                   class="mark-input"
                                   data-student="${student.id}"
                                   data-type="percentage"
                                   min="0" 
                                   max="100"
                                   value="${student.attendance?.percentage || 95.6}"
                                   placeholder="Auto-calculated"
                                   readonly
                                   style="background-color: #f0f0f0;">
                        </div>
                    </div>
                </div>
            `;
            
            marksEntryContainer.innerHTML = marksHTML;
            
            // Add event listeners to mark inputs
            document.querySelectorAll('.component-input, .custom-field, .attendance-input').forEach(input => {
                input.addEventListener('input', function() {
                    const studentId = this.getAttribute('data-student');
                    const subjectId = this.getAttribute('data-subject');
                    const component = this.getAttribute('data-component');
                    
                    // If it's a component mark, update total
                    if (subjectId && component && component !== 'total') {
                        updateSubjectTotal(studentId, subjectId);
                    }
                    
                    // If it's attendance, update percentage
                    if (this.classList.contains('attendance-input')) {
                        updateAttendancePercentage(studentId);
                    }
                    
                    // Update summary
                    updateMarksSummary(studentId);
                    
                    // Update report card preview automatically with debounce
                    clearTimeout(previewUpdateTimeout);
                    previewUpdateTimeout = setTimeout(() => {
                        updateReportCardPreviewFromMarks(studentId);
                    }, 500);
                });
            });
            
            // Also listen for change events on select elements
            document.querySelectorAll('select.custom-field').forEach(select => {
                select.addEventListener('change', function() {
                    const studentId = this.getAttribute('data-student');
                    updateMarksSummary(studentId);
                    
                    // Update report card preview
                    clearTimeout(previewUpdateTimeout);
                    previewUpdateTimeout = setTimeout(() => {
                        updateReportCardPreviewFromMarks(studentId);
                    }, 500);
                });
            });
            
            // Initialize totals and summary
            standardSubjects.forEach(subject => {
                updateSubjectTotal(student.id, subject.id);
            });
            updateAttendancePercentage(student.id);
            updateMarksSummary(student.id);
        }

        // Update subject total based on component marks
        function updateSubjectTotal(studentId, subjectId) {
            const subject = standardSubjects.find(s => s.id === subjectId);
            if (!subject) return;
            
            let total = 0;
            let isValid = true;
            
            // Calculate total from components
            subject.components.forEach(component => {
                const input = document.getElementById(`marks-${studentId}-${subjectId}-${component.name}`);
                if (input) {
                    const value = parseInt(input.value) || 0;
                    const max = parseInt(input.max) || 0;
                    
                    if (value > max) {
                        input.style.borderColor = 'var(--danger-color)';
                        isValid = false;
                    } else {
                        input.style.borderColor = '';
                        // Add weighted contribution
                        total += (value / max) * component.weight * subject.maxMarks;
                    }
                }
            });
            
            // Round to nearest integer
            total = Math.round(total);
            
            // Update total input
            const totalInput = document.getElementById(`marks-${studentId}-${subjectId}-total`);
            if (totalInput) {
                totalInput.value = isValid ? total : 0;
            }
        }

        // Update attendance percentage
        function updateAttendancePercentage(studentId) {
            const totalInput = document.getElementById(`attendance-total-${studentId}`);
            const presentInput = document.getElementById(`attendance-present-${studentId}`);
            const percentageInput = document.getElementById(`attendance-percentage-${studentId}`);
            
            if (!totalInput || !presentInput || !percentageInput) return;
            
            const total = parseInt(totalInput.value) || 0;
            const present = parseInt(presentInput.value) || 0;
            
            if (total > 0 && present <= total) {
                const percentage = (present / total) * 100;
                percentageInput.value = percentage.toFixed(1);
            } else {
                percentageInput.value = 0;
            }
        }

        // Update marks summary
        function updateMarksSummary(studentId) {
            const student = students.find(s => s.id === studentId);
            if (!student) return;
            
            // Calculate weighted average
            let totalWeightedMarks = 0;
            let totalWeightage = 0;
            let subjectCount = 0;
            
            standardSubjects.forEach(subject => {
                const totalInput = document.getElementById(`marks-${studentId}-${subject.id}-total`);
                if (totalInput) {
                    const marks = parseInt(totalInput.value) || 0;
                    totalWeightedMarks += marks * subject.weightage;
                    totalWeightage += subject.weightage;
                    subjectCount++;
                }
            });
            
            // Calculate average
            const average = totalWeightage > 0 ? (totalWeightedMarks / totalWeightage) : 0;
            const grade = calculateGrade(average);
            const gradeClass = `grade-${grade.charAt(0).toLowerCase()}`;
            
            // Get attendance percentage
            const attendanceInput = document.getElementById(`attendance-percentage-${studentId}`);
            const attendance = attendanceInput ? parseFloat(attendanceInput.value) || 0 : 0;
            
            // Get highest and lowest subjects
            let highestSubject = { name: 'N/A', marks: 0 };
            let lowestSubject = { name: 'N/A', marks: 100 };
            
            standardSubjects.forEach(subject => {
                const totalInput = document.getElementById(`marks-${studentId}-${subject.id}-total`);
                if (totalInput) {
                    const marks = parseInt(totalInput.value) || 0;
                    if (marks > highestSubject.marks) {
                        highestSubject = { name: subject.name, marks: marks };
                    }
                    if (marks < lowestSubject.marks) {
                        lowestSubject = { name: subject.name, marks: marks };
                    }
                }
            });
            
            marksSummaryEl.innerHTML = `
                <h3 style="margin-bottom: 15px; color: white;">Performance Summary</h3>
                <div class="summary-stats">
                    <div>
                        <div class="stat-value">${Math.round(average)}%</div>
                        <div class="stat-label">Weighted Average</div>
                    </div>
                    <div>
                        <div class="stat-value"><span class="grade-badge ${gradeClass}">${grade}</span></div>
                        <div class="stat-label">Overall Grade</div>
                    </div>
                    <div>
                        <div class="stat-value">${attendance.toFixed(1)}%</div>
                        <div class="stat-label">Attendance</div>
                    </div>
                    <div>
                        <div class="stat-value">${subjectCount}</div>
                        <div class="stat-label">Subjects</div>
                    </div>
                </div>
                <div style="margin-top: 15px; font-size: 0.9rem; opacity: 0.9;">
                    <p>Highest: ${highestSubject.name} (${highestSubject.marks}%) | Lowest: ${lowestSubject.name} (${lowestSubject.marks}%)</p>
                </div>
            `;
        }

        // Update report card preview from marks tab input changes
        function updateReportCardPreviewFromMarks(studentId) {
            const student = students.find(s => s.id === studentId);
            if (!student) return;
            
            // Create a deep copy of the student with current form values
            const updatedStudent = JSON.parse(JSON.stringify(student));
            
            // Initialize marks object if not exists
            if (!updatedStudent.marks) updatedStudent.marks = {};
            
            // Update standard subjects from form inputs
            standardSubjects.forEach(subject => {
                const totalInput = document.getElementById(`marks-${studentId}-${subject.id}-total`);
                if (totalInput) {
                    if (!updatedStudent.marks[subject.id]) {
                        updatedStudent.marks[subject.id] = { total: 0, components: {} };
                    }
                    
                    updatedStudent.marks[subject.id].total = parseInt(totalInput.value) || 0;
                    
                    // Update component marks
                    subject.components.forEach(component => {
                        const compInput = document.getElementById(`marks-${studentId}-${subject.id}-${component.name}`);
                        if (compInput) {
                            const compKey = component.name.toLowerCase().replace(' ', '_');
                            if (!updatedStudent.marks[subject.id].components) {
                                updatedStudent.marks[subject.id].components = {};
                            }
                            updatedStudent.marks[subject.id].components[compKey] = parseInt(compInput.value) || 0;
                        }
                    });
                }
            });
            
            // Update custom fields
            if (!updatedStudent.customFields) updatedStudent.customFields = {};
            customFields.forEach(field => {
                const fieldInput = document.getElementById(`marks-${studentId}-custom-${field.id}`);
                if (fieldInput) {
                    updatedStudent.customFields[field.id] = fieldInput.value;
                }
            });
            
            // Update attendance
            const totalAttendance = document.getElementById(`attendance-total-${studentId}`);
            const presentAttendance = document.getElementById(`attendance-present-${studentId}`);
            const percentageAttendance = document.getElementById(`attendance-percentage-${studentId}`);
            
            if (totalAttendance && presentAttendance && percentageAttendance) {
                updatedStudent.attendance = {
                    total: parseInt(totalAttendance.value) || 0,
                    present: parseInt(presentAttendance.value) || 0,
                    percentage: parseFloat(percentageAttendance.value) || 0
                };
            }
            
            // Store this updated student for preview
            currentPreviewStudent = updatedStudent;
            
            // Update the report card preview
            renderReportCardPreview();
            
            // Show auto-save indicator
            showAutoSaveIndicator();
        }

        // Save all marks for current student
        function saveAllMarks(goToNext = false) {
            if (!currentMarksStudentId) {
                showStatusMessage('No student selected for marks entry.', 'error');
                return;
            }
            
            const student = students.find(s => s.id === currentMarksStudentId);
            if (!student) {
                showStatusMessage('Student not found.', 'error');
                return;
            }
            
            // Initialize marks object if not exists
            if (!student.marks) student.marks = {};
            
            let hasErrors = false;
            
            // Save standard subjects
            standardSubjects.forEach(subject => {
                const totalInput = document.getElementById(`marks-${student.id}-${subject.id}-total`);
                if (totalInput) {
                    if (!student.marks[subject.id]) student.marks[subject.id] = { total: 0, components: {} };
                    
                    const totalValue = parseInt(totalInput.value) || 0;
                    if (totalValue > subject.maxMarks) {
                        showStatusMessage(`Error: ${subject.name} marks (${totalValue}) exceed maximum (${subject.maxMarks})`, 'error');
                        hasErrors = true;
                        return;
                    }
                    
                    student.marks[subject.id].total = totalValue;
                    
                    // Ensure components object exists
                    if (!student.marks[subject.id].components) {
                        student.marks[subject.id].components = {};
                    }
                    
                    // Save component marks
                    subject.components.forEach(component => {
                        const compInput = document.getElementById(`marks-${student.id}-${subject.id}-${component.name}`);
                        if (compInput) {
                            const compKey = component.name.toLowerCase().replace(' ', '_');
                            const compValue = parseInt(compInput.value) || 0;
                            
                            if (compValue > component.max) {
                                showStatusMessage(`Error: ${subject.name} - ${component.name} marks (${compValue}) exceed maximum (${component.max})`, 'error');
                                hasErrors = true;
                                return;
                            }
                            
                            student.marks[subject.id].components[compKey] = compValue;
                        }
                    });
                }
            });
            
            if (hasErrors) return;
            
            // Save custom fields
            if (!student.customFields) student.customFields = {};
            customFields.forEach(field => {
                const fieldInput = document.getElementById(`marks-${student.id}-custom-${field.id}`);
                if (fieldInput) {
                    student.customFields[field.id] = fieldInput.value;
                }
            });
            
            // Save attendance
            const totalAttendance = document.getElementById(`attendance-total-${student.id}`);
            const presentAttendance = document.getElementById(`attendance-present-${student.id}`);
            const percentageAttendance = document.getElementById(`attendance-percentage-${student.id}`);
            
            if (totalAttendance && presentAttendance && percentageAttendance) {
                const total = parseInt(totalAttendance.value) || 0;
                const present = parseInt(presentAttendance.value) || 0;
                
                if (present > total) {
                    showStatusMessage('Error: Days present cannot exceed total working days.', 'error');
                    return;
                }
                
                student.attendance = {
                    total: total,
                    present: present,
                    percentage: parseFloat(percentageAttendance.value) || 0
                };
            }
            
            // Save to localStorage
            saveData();
            
            // Update UI
            renderStudentList();
            renderExportStudentList();
            renderReportCardPreview();
            
            showStatusMessage('Marks saved successfully!', 'success');
            
            // If goToNext is true, move to next student
            if (goToNext) {
                // Find current student index
                const currentIndex = students.findIndex(s => s.id === currentMarksStudentId);
                if (currentIndex !== -1) {
                    // Get next student
                    const nextIndex = (currentIndex + 1) % students.length;
                    const nextStudent = students[nextIndex];
                    
                    // Select next student after a brief delay
                    setTimeout(() => {
                        selectMarksStudent(nextStudent.id);
                        marksStudentSelect.value = nextStudent.id;
                        showStatusMessage(`Now editing: ${nextStudent.name}`, 'success');
                        // Update preview
                        updatePreviewFromMarksTab();
                    }, 1000);
                }
            }
        }

        // Show status message
        function showStatusMessage(message, type = 'success') {
            marksStatusMessage.textContent = message;
            marksStatusMessage.className = `status-message status-${type}`;
            marksStatusMessage.style.display = 'block';
            
            // Auto-hide success messages after 3 seconds
            if (type === 'success') {
                setTimeout(() => {
                    marksStatusMessage.style.display = 'none';
                }, 3000);
            }
        }

        // Clear all marks for current student
        function clearAllMarks() {
            if (!currentMarksStudentId) {
                showStatusMessage('No student selected.', 'error');
                return;
            }
            
            if (confirm('Are you sure you want to clear all marks for this student?')) {
                const student = students.find(s => s.id === currentMarksStudentId);
                if (!student) return;
                
                // Reset all mark inputs
                document.querySelectorAll('.mark-input').forEach(input => {
                    if (!input.readOnly && input.id.includes(`marks-${student.id}`)) {
                        input.value = '';
                    }
                });
                
                // Reset attendance
                const totalAttendance = document.getElementById(`attendance-total-${student.id}`);
                const presentAttendance = document.getElementById(`attendance-present-${student.id}`);
                if (totalAttendance) totalAttendance.value = '180';
                if (presentAttendance) presentAttendance.value = '172';
                
                // Update calculations
                standardSubjects.forEach(subject => {
                    updateSubjectTotal(student.id, subject.id);
                });
                updateAttendancePercentage(student.id);
                updateMarksSummary(student.id);
                
                // Update report card preview
                updateReportCardPreviewFromMarks(student.id);
                
                showStatusMessage('All marks cleared for this student.', 'success');
            }
        }

        // Copy marks from another student
        function copyMarksFromAnother() {
            if (!currentMarksStudentId) {
                showStatusMessage('Please select a student first.', 'error');
                return;
            }
            
            // Create list of other students
            const otherStudents = students.filter(s => s.id !== currentMarksStudentId);
            if (otherStudents.length === 0) {
                showStatusMessage('No other students available to copy from.', 'error');
                return;
            }
            
            const studentNames = otherStudents.map(s => `${s.name} (${s.rollNumber})`).join('\n');
            const sourceName = prompt(`Enter the name of student to copy from:\n\n${studentNames}`);
            
            if (sourceName) {
                const sourceStudent = students.find(s => 
                    s.name.includes(sourceName) || 
                    `${s.name} (${s.rollNumber})`.includes(sourceName)
                );
                
                if (sourceStudent) {
                    const targetStudent = students.find(s => s.id === currentMarksStudentId);
                    
                    // Copy marks
                    targetStudent.marks = JSON.parse(JSON.stringify(sourceStudent.marks || {}));
                    targetStudent.attendance = sourceStudent.attendance ? {...sourceStudent.attendance} : null;
                    
                    // Save and refresh
                    saveData();
                    renderMarksEntry(targetStudent);
                    renderStudentList();
                    renderReportCardPreview();
                    
                    showStatusMessage(`Marks copied from ${sourceStudent.name} to ${targetStudent.name}`, 'success');
                } else {
                    showStatusMessage('Student not found. Please check the name and try again.', 'error');
                }
            }
        }

        // Auto-generate marks based on performance level
        function autoGenerateMarks() {
            if (!currentMarksStudentId) {
                showStatusMessage('Please select a student first.', 'error');
                return;
            }
            
            const performanceLevel = prompt('Enter performance level:\n1. Excellent (90-100%)\n2. Good (80-89%)\n3. Average (70-79%)\n4. Below Average (60-69%)\n5. Needs Improvement (50-59%)', '3');
            
            if (!performanceLevel) return;
            
            const level = parseInt(performanceLevel);
            const ranges = [
                { min: 90, max: 100 },
                { min: 80, max: 89 },
                { min: 70, max: 79 },
                { min: 60, max: 69 },
                { min: 50, max: 59 }
            ];
            
            const range = ranges[level - 1] || ranges[2]; // Default to average
            
            const student = students.find(s => s.id === currentMarksStudentId);
            if (!student) return;
            
            // Generate marks for each subject
            standardSubjects.forEach(subject => {
                // Generate total within range
                const total = Math.floor(Math.random() * (range.max - range.min + 1)) + range.min;
                
                // Update total input
                const totalInput = document.getElementById(`marks-${student.id}-${subject.id}-total`);
                if (totalInput) {
                    totalInput.value = total;
                }
                
                // Generate component marks proportionally
                subject.components.forEach(component => {
                    const compInput = document.getElementById(`marks-${student.id}-${subject.id}-${component.name}`);
                    if (compInput) {
                        // Calculate component mark based on weight
                        const compMark = Math.round((total / subject.maxMarks) * component.max * (0.9 + Math.random() * 0.2));
                        compInput.value = Math.min(compMark, component.max);
                    }
                });
                
                // Update subject total
                updateSubjectTotal(student.id, subject.id);
            });
            
            // Update attendance (higher performance = better attendance)
            const attendanceBase = 85 + (level * 2);
            const attendanceTotal = document.getElementById(`attendance-total-${student.id}`);
            const attendancePresent = document.getElementById(`attendance-present-${student.id}`);
            
            if (attendanceTotal && attendancePresent) {
                const total = 180;
                const present = Math.round(total * (attendanceBase / 100));
                
                attendanceTotal.value = total;
                attendancePresent.value = present;
                updateAttendancePercentage(student.id);
            }
            
            // Update summary
            updateMarksSummary(student.id);
            
            // Update report card preview
            updateReportCardPreviewFromMarks(student.id);
            
            showStatusMessage('Marks auto-generated successfully!', 'success');
        }

        // Handle student form submission
        function handleStudentSubmit(e) {
            e.preventDefault();
            
            const studentId = studentIdInput.value ? parseInt(studentIdInput.value) : null;
            const studentData = {
                name: studentNameInput.value,
                rollNumber: rollNumberInput.value,
                class: studentClassInput.value,
                section: studentSectionInput.value,
                dob: studentDOBInput.value,
                parent: parentNameInput.value,
                avatar: generateAvatar(studentNameInput.value),
                marks: {},
                attendance: {
                    total: 180,
                    present: 172,
                    percentage: 95.6
                }
            };
            
            if (studentId) {
                // Update existing student
                const index = students.findIndex(s => s.id === studentId);
                if (index !== -1) {
                    // Preserve existing data
                    studentData.marks = students[index].marks || {};
                    studentData.attendance = students[index].attendance || studentData.attendance;
                    studentData.avatar = students[index].avatar;
                    students[index] = { ...students[index], ...studentData };
                }
            } else {
                // Add new student
                const newId = students.length > 0 ? Math.max(...students.map(s => s.id)) + 1 : 1;
                studentData.id = newId;
                
                // Initialize marks for all subjects
                standardSubjects.forEach(subject => {
                    studentData.marks[subject.id] = {
                        total: 0,
                        components: {}
                    };
                    subject.components.forEach(comp => {
                        const compKey = comp.name.toLowerCase().replace(' ', '_');
                        studentData.marks[subject.id].components[compKey] = 0;
                    });
                });
                
                students.push(studentData);
                selectedStudents.add(newId);
            }
            
            // Save to localStorage
            saveData();
            
            // Update UI
            renderStudentList();
            renderExportStudentList();
            populateMarksStudentSelect();
            renderReportCardPreview();
            
            // Clear form if adding new
            if (!studentId) {
                clearStudentForm();
            }
            
            showStatusMessage(`Student ${studentId ? 'updated' : 'added'} successfully!`, 'success');
        }

        // Add new student
        function addNewStudent() {
            clearStudentForm();
            studentNameInput.focus();
        }

        // Clear student form
        function clearStudentForm() {
            studentForm.reset();
            studentIdInput.value = '';
        }

        // Delete student
        function deleteStudent() {
            const studentId = parseInt(studentIdInput.value);
            if (!studentId) {
                showStatusMessage('No student selected to delete.', 'error');
                return;
            }
            
            if (confirm('Are you sure you want to delete this student? This action cannot be undone.')) {
                const index = students.findIndex(s => s.id === studentId);
                if (index !== -1) {
                    students.splice(index, 1);
                    selectedStudents.delete(studentId);
                    
                    // Save to localStorage
                    saveData();
                    
                    // Update UI
                    renderStudentList();
                    renderExportStudentList();
                    populateMarksStudentSelect();
                    clearStudentForm();
                    
                    // Select first student if available
                    if (students.length > 0) {
                        selectStudent(students[0].id);
                        selectMarksStudent(students[0].id);
                    } else {
                        reportCardPreviewEl.innerHTML = '<div class="empty-state"><p>No students available. Add a student to see preview.</p></div>';
                    }
                    
                    showStatusMessage('Student deleted successfully!', 'success');
                }
            }
        }

        // Render dynamic fields
        function renderDynamicFields() {
            if (customFields.length === 0) {
                dynamicFieldsListEl.innerHTML = '<div class="empty-state"><p>No custom fields added yet.</p></div>';
                return;
            }
            
            dynamicFieldsListEl.innerHTML = customFields.map(field => `
                <div class="field-item">
                    <div class="field-name">${field.name}</div>
                    <div class="field-type">${field.type}</div>
                    <div class="action-buttons">
                        <button class="btn btn-sm btn-warning edit-field" data-id="${field.id}"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger delete-field" data-id="${field.id}"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            `).join('');
            
            // Add event listeners to field buttons
            document.querySelectorAll('.edit-field').forEach(btn => {
                btn.addEventListener('click', function() {
                    const fieldId = parseInt(this.getAttribute('data-id'));
                    editCustomField(fieldId);
                });
            });
            
            document.querySelectorAll('.delete-field').forEach(btn => {
                btn.addEventListener('click', function() {
                    const fieldId = parseInt(this.getAttribute('data-id'));
                    deleteCustomField(fieldId);
                });
            });
        }

        // Add custom field
        function addCustomField() {
            const name = newFieldNameInput.value.trim();
            const type = newFieldTypeInput.value;
            
            if (!name) {
                showStatusMessage('Please enter a field name.', 'error');
                return;
            }
            
            const newId = customFields.length > 0 ? Math.max(...customFields.map(f => f.id)) + 1 : 1;
            customFields.push({
                id: newId,
                name,
                type,
                value: type === 'number' ? 0 : type === 'grade' ? 'A' : type === 'percentage' ? '100%' : ''
            });
            
            // Save to localStorage
            localStorage.setItem('customFieldsData', JSON.stringify(customFields));
            
            // Update UI
            renderDynamicFields();
            newFieldNameInput.value = '';
            
            // Update preview
            renderReportCardPreview();
            
            showStatusMessage('Custom field added successfully!', 'success');
        }

        // Edit custom field
        function editCustomField(id) {
            const field = customFields.find(f => f.id === id);
            if (!field) return;
            
            const newName = prompt('Enter new name for this field:', field.name);
            if (newName && newName.trim()) {
                field.name = newName.trim();
                
                // Save to localStorage
                localStorage.setItem('customFieldsData', JSON.stringify(customFields));
                
                // Update UI
                renderDynamicFields();
                renderReportCardPreview();
                
                showStatusMessage('Field updated successfully!', 'success');
            }
        }

        // Delete custom field
        function deleteCustomField(id) {
            if (confirm('Are you sure you want to delete this field?')) {
                const index = customFields.findIndex(f => f.id === id);
                if (index !== -1) {
                    customFields.splice(index, 1);
                    
                    // Save to localStorage
                    localStorage.setItem('customFieldsData', JSON.stringify(customFields));
                    
                    // Update UI
                    renderDynamicFields();
                    renderReportCardPreview();
                    
                    showStatusMessage('Field deleted successfully!', 'success');
                }
            }
        }

        // Handle design selection
        function handleDesignSelection(e) {
            const designOption = e.target.closest('.design-option');
            if (!designOption) return;
            
            // Update selected design
            document.querySelectorAll('.design-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            designOption.classList.add('selected');
            currentDesign = designOption.getAttribute('data-design');
            
            // Update preview
            renderReportCardPreview();
        }

        // Apply custom colors
        function applyCustomColors() {
            const primaryColor = primaryColorInput.value;
            const secondaryColor = secondaryColorInput.value;
            
            // Update CSS variables
            document.documentElement.style.setProperty('--primary-color', primaryColor);
            document.documentElement.style.setProperty('--secondary-color', secondaryColor);
            document.documentElement.style.setProperty('--gradient-primary', `linear-gradient(135deg, ${primaryColor}, ${secondaryColor})`);
            
            // Update preview
            renderReportCardPreview();
            
            showStatusMessage('Colors applied successfully!', 'success');
        }

        // Update school configuration
        function updateSchoolConfig() {
            schoolConfig = {
                name: schoolNameInput.value,
                address: schoolAddressInput.value,
                year: academicYearInput.value,
                principal: principalNameInput.value,
                showLogo: showLogoInput.checked,
                showWatermark: showWatermarkInput.checked
            };
            
            // Save to localStorage
            localStorage.setItem('schoolConfig', JSON.stringify(schoolConfig));
            
            // Update preview
            renderReportCardPreview();
        }

        // Update config inputs from saved config
        function updateConfigInputs() {
            schoolNameInput.value = schoolConfig.name;
            schoolAddressInput.value = schoolConfig.address;
            academicYearInput.value = schoolConfig.year;
            principalNameInput.value = schoolConfig.principal;
            showLogoInput.checked = schoolConfig.showLogo;
            showWatermarkInput.checked = schoolConfig.showWatermark;
        }

        // Render report card preview
        function renderReportCardPreview() {
            if (students.length === 0 || selectedStudents.size === 0) {
                reportCardPreviewEl.innerHTML = '<div class="empty-state"><p>No student selected. Select a student from the list to see preview.</p></div>';
                return;
            }
            
            // Get first selected student for preview
            const firstSelectedId = Array.from(selectedStudents)[0];
            let student = students.find(s => s.id === firstSelectedId);
            
            if (!student) {
                reportCardPreviewEl.innerHTML = '<div class="empty-state"><p>Student not found.</p></div>';
                return;
            }
            
            // If we have a preview student from marks tab, use that instead
            if (currentPreviewStudent && currentPreviewStudent.id === firstSelectedId) {
                student = currentPreviewStudent;
            }
            
            // Generate report card HTML based on selected design
            reportCardPreviewEl.innerHTML = generateReportCardHTML(student);
            
            // Update preview indicator
            updatePreviewIndicator();
        }

        // Generate report card HTML
        function generateReportCardHTML(student) {
            const average = calculateStudentAverage(student);
            const grade = calculateGrade(average);
            const remarks = generateRemarks(student);
            
            return `
                <div class="report-card ${currentDesign}">
                    ${generateHeaderHTML()}
                    
                    <div class="student-info-section">
                        <div class="info-item"><span class="info-label">Student Name:</span> ${student.name}</div>
                        <div class="info-item"><span class="info-label">Roll Number:</span> ${student.rollNumber}</div>
                        <div class="info-item"><span class="info-label">Class:</span> ${student.class} ${student.section ? `- Section ${student.section}` : ''}</div>
                        <div class="info-item"><span class="info-label">Academic Year:</span> ${schoolConfig.year}</div>
                        ${student.dob ? `<div class="info-item"><span class="info-label">Date of Birth:</span> ${formatDate(student.dob)}</div>` : ''}
                        ${student.parent ? `<div class="info-item"><span class="info-label">Parent/Guardian:</span> ${student.parent}</div>` : ''}
                    </div>
                    
                    <h3 style="margin: 25px 0 15px 0; color: var(--secondary-color);">Academic Performance</h3>
                    
                    <table class="grades-table">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Marks</th>
                                <th>Grade</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${generateSubjectRowsHTML(student)}
                            ${generateCustomFieldsRowsHTML(student)}
                            <tr style="background-color: #f8f9fa; font-weight: bold;">
                                <td>Overall</td>
                                <td>${average}%</td>
                                <td>${grade}</td>
                                <td>${getGradeRemark(grade)}</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="remarks-section">
                        <h4 style="margin-bottom: 15px; color: var(--secondary-color);">Teacher's Remarks:</h4>
                        <p>${remarks}</p>
                    </div>
                    
                    <div class="signature-section">
                        <div class="signature">
                            <div class="signature-line"></div>
                            <p>Class Teacher</p>
                        </div>
                        <div class="signature">
                            <div class="signature-line"></div>
                            <p>Principal</p>
                        </div>
                        <div class="signature">
                            <div class="signature-line"></div>
                            <p>Parent/Guardian</p>
                        </div>
                    </div>
                    
                    <div class="footer">
                        <p>Report generated on ${new Date().toLocaleDateString()} | ${schoolConfig.name}</p>
                        ${schoolConfig.showWatermark ? '<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 3rem; color: rgba(0,0,0,0.1); pointer-events: none;">OFFICIAL TRANSCRIPT</div>' : ''}
                    </div>
                </div>
            `;
        }

        // Generate header HTML based on design
        function generateHeaderHTML() {
            if (currentDesign === 'modern') {
                return `
                    <div class="header">
                        ${schoolConfig.showLogo ? '<div style="float: right; width: 80px; height: 80px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem;">🏫</div>' : ''}
                        <h1 class="school-name">${schoolConfig.name}</h1>
                        <p>${schoolConfig.address}</p>
                        <h2 class="report-title">ACADEMIC REPORT CARD</h2>
                        <p>Academic Year: ${schoolConfig.year}</p>
                    </div>
                `;
            } else if (currentDesign === 'elegant') {
                return `
                    <div class="header">
                        <h1 class="school-name">${schoolConfig.name}</h1>
                        <p style="color: #8b7355; font-style: italic;">${schoolConfig.address}</p>
                        <h2 style="color: #8b7355; font-weight: normal; margin: 10px 0;">Academic Report Card</h2>
                        <p>Academic Year: ${schoolConfig.year}</p>
                    </div>
                `;
            } else if (currentDesign === 'minimal') {
                return `
                    <div class="header">
                        <h1 class="school-name">${schoolConfig.name}</h1>
                        <p>${schoolConfig.address}</p>
                        <h2 style="margin: 15px 0; font-weight: 300;">Report Card</h2>
                        <p>${schoolConfig.year}</p>
                    </div>
                `;
            } else if (currentDesign === 'colorful') {
                return `
                    <div class="header">
                        <h1 class="school-name">${schoolConfig.name}</h1>
                        <p>${schoolConfig.address}</p>
                        <h2 style="margin: 10px 0; font-size: 1.8rem;">ACADEMIC REPORT CARD</h2>
                        <p>Academic Year: ${schoolConfig.year}</p>
                    </div>
                `;
            } else {
                // Classic design (default)
                return `
                    <div class="header">
                        ${schoolConfig.showLogo ? '<div style="float: right; width: 70px; height: 70px; background: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 2rem; border: 2px solid var(--primary-color);">🏫</div>' : ''}
                        <h1 class="school-name">${schoolConfig.name}</h1>
                        <p>${schoolConfig.address}</p>
                        <h2 class="report-title">ACADEMIC REPORT CARD</h2>
                        <p>Academic Year: ${schoolConfig.year} | Principal: ${schoolConfig.principal}</p>
                    </div>
                `;
            }
        }

        // Generate subject rows HTML
        function generateSubjectRowsHTML(student) {
            return standardSubjects.map(subject => {
                // Get marks from student data
                const marks = student.marks && student.marks[subject.id] ? 
                    student.marks[subject.id].total || 0 : 0;
                const grade = getGradeLetter(marks);
                const gradeClass = `grade-${grade}`;
                
                return `
                    <tr>
                        <td>${subject.name}</td>
                        <td>${marks}/${subject.maxMarks}</td>
                        <td class="${gradeClass}">${grade.toUpperCase()}</td>
                        <td>${getSubjectRemark(marks)}</td>
                    </tr>
                `;
            }).join('');
        }

        // Generate custom fields rows HTML
        function generateCustomFieldsRowsHTML(student) {
            return customFields.map(field => {
                const value = student.customFields && student.customFields[field.id] ? 
                    student.customFields[field.id] : field.value || '';
                return `
                    <tr>
                        <td>${field.name}</td>
                        <td>${value}</td>
                        <td>${field.type === 'grade' ? value : '—'}</td>
                        <td>${getCustomFieldRemark(field, value)}</td>
                    </tr>
                `;
            }).join('');
        }

        // Generate remarks based on template
        function generateRemarks(student) {
            const template = remarksTemplateInput.value;
            const average = calculateStudentAverage(student);
            
            if (template === 'custom') {
                return customRemarksInput.value;
            }
            
            const remarks = {
                default: `${student.name} has shown ${average >= 80 ? 'excellent' : average >= 60 ? 'good' : 'satisfactory'} progress this term. ${average >= 80 ? 'Keep up the excellent work!' : average >= 60 ? 'There is room for improvement in some areas.' : 'Needs to focus more on studies.'}`,
                positive: `Excellent performance throughout the term. ${student.name} demonstrates strong understanding of concepts and consistently produces high-quality work. Shows great potential for further academic growth.`,
                average: `Satisfactory performance. ${student.name} has met the basic requirements but could benefit from more consistent effort. Shows improvement in some areas but needs to work harder to reach full potential.`,
                improve: `Needs improvement in several areas. ${student.name} should focus on completing assignments on time and participating more actively in class. Additional support and practice would be beneficial.`
            };
            
            return remarks[template] || remarks.default;
        }

        // Utility functions
        function calculateStudentAverage(student) {
            if (!student || !student.marks) return 0;
            
            let totalWeightedMarks = 0;
            let totalWeightage = 0;
            
            standardSubjects.forEach(subject => {
                if (student.marks[subject.id] && student.marks[subject.id].total !== undefined) {
                    totalWeightedMarks += student.marks[subject.id].total * subject.weightage;
                    totalWeightage += subject.weightage;
                }
            });
            
            return totalWeightage > 0 ? Math.round(totalWeightedMarks / totalWeightage) : 0;
        }

        function calculateGrade(average) {
            if (average >= 90) return 'A+';
            if (average >= 80) return 'A';
            if (average >= 70) return 'B';
            if (average >= 60) return 'C';
            if (average >= 50) return 'D';
            return 'F';
        }

        function getGradeLetter(score) {
            if (score >= 90) return 'a';
            if (score >= 80) return 'b';
            if (score >= 70) return 'c';
            if (score >= 60) return 'd';
            return 'f';
        }

        function getGradeRemark(grade) {
            const remarks = {
                'A+': 'Outstanding',
                'A': 'Excellent',
                'B': 'Good',
                'C': 'Satisfactory',
                'D': 'Needs Improvement',
                'F': 'Fail - Requires Remedial Classes'
            };
            return remarks[grade] || '—';
        }

        function getSubjectRemark(score) {
            if (score >= 90) return 'Excellent performance';
            if (score >= 80) return 'Very good';
            if (score >= 70) return 'Good effort';
            if (score >= 60) return 'Satisfactory';
            if (score >= 50) return 'Needs improvement';
            return 'Requires attention';
        }

        function getCustomFieldRemark(field, value) {
            if (field.type === 'number') {
                const score = parseInt(value) || 0;
                return getSubjectRemark(score);
            } else if (field.type === 'grade') {
                return getGradeRemark(value);
            } else if (field.type === 'percentage') {
                const percent = parseInt(value) || 0;
                if (percent >= 90) return 'Excellent attendance';
                if (percent >= 80) return 'Good attendance';
                if (percent >= 70) return 'Satisfactory attendence';
                return 'Needs improvement';
            }
            return '—';
        }

        function generateAvatar(name) {
            return name.split(' ').map(part => part[0]).join('').toUpperCase().substring(0, 2);
        }

        function formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
        }

        function saveData() {
            localStorage.setItem('studentReportCardData', JSON.stringify(students));
            localStorage.setItem('customFieldsData', JSON.stringify(customFields));
            localStorage.setItem('schoolConfig', JSON.stringify(schoolConfig));
        }

        // Bulk update marks for multiple students
        function bulkUpdateMarks() {
            if (students.length === 0) {
                showStatusMessage('No students available.', 'error');
                return;
            }
            
            const subjectName = prompt('Enter subject name to update (Math, Science, English, History, Computer):', 'Math');
            if (!subjectName) return;
            
            const subject = standardSubjects.find(s => s.name.toLowerCase().includes(subjectName.toLowerCase()));
            if (!subject) {
                showStatusMessage('Subject not found. Please enter a valid subject name.', 'error');
                return;
            }
            
            const marksValue = prompt(`Enter marks for ${subject.name} (0-${subject.maxMarks}):`, '75');
            if (!marksValue) return;
            
            const marks = parseInt(marksValue);
            if (isNaN(marks) || marks < 0 || marks > subject.maxMarks) {
                showStatusMessage(`Please enter a valid number between 0 and ${subject.maxMarks}.`, 'error');
                return;
            }
            
            const selectedIds = selectedStudents.size > 0 ? Array.from(selectedStudents) : students.map(s => s.id);
            
            if (confirm(`Update ${subject.name} marks to ${marks} for ${selectedIds.length} student(s)?`)) {
                selectedIds.forEach(studentId => {
                    const student = students.find(s => s.id === studentId);
                    if (student) {
                        if (!student.marks) student.marks = {};
                        if (!student.marks[subject.id]) student.marks[subject.id] = { total: 0, components: {} };
                        
                        student.marks[subject.id].total = marks;
                        
                        // Update component marks proportionally
                        subject.components.forEach(component => {
                            const compKey = component.name.toLowerCase().replace(' ', '_');
                            student.marks[subject.id].components[compKey] = Math.round((marks / subject.maxMarks) * component.max);
                        });
                    }
                });
                
                // Save and refresh
                saveData();
                renderStudentList();
                renderExportStudentList();
                
                // If current marks student is among those updated, refresh marks display
                if (currentMarksStudentId && selectedIds.includes(currentMarksStudentId)) {
                    const student = students.find(s => s.id === currentMarksStudentId);
                    renderMarksEntry(student);
                    renderReportCardPreview();
                }
                
                showStatusMessage(`${selectedIds.length} student(s) updated successfully!`, 'success');
            }
        }

        // View class statistics
        function viewClassStatistics() {
            if (students.length === 0) {
                showStatusMessage('No students available.', 'error');
                return;
            }
            
            // Calculate class averages
            const classAverages = {};
            const gradeDistribution = { 'A+': 0, 'A': 0, 'B': 0, 'C': 0, 'D': 0, 'F': 0 };
            
            students.forEach(student => {
                const average = calculateStudentAverage(student);
                const grade = calculateGrade(average);
                gradeDistribution[grade] = (gradeDistribution[grade] || 0) + 1;
                
                // Calculate subject averages
                standardSubjects.forEach(subject => {
                    if (!classAverages[subject.name]) classAverages[subject.name] = { total: 0, count: 0 };
                    if (student.marks?.[subject.id]?.total !== undefined) {
                        classAverages[subject.name].total += student.marks[subject.id].total;
                        classAverages[subject.name].count++;
                    }
                });
            });
            
            // Prepare statistics message
            let statsMessage = `CLASS STATISTICS (${students.length} students)\n\n`;
            statsMessage += `OVERALL PERFORMANCE:\n`;
            
            Object.entries(gradeDistribution).forEach(([grade, count]) => {
                if (count > 0) {
                    const percentage = (count / students.length) * 100;
                    statsMessage += `${grade}: ${count} students (${percentage.toFixed(1)}%)\n`;
                }
            });
            
            statsMessage += `\nSUBJECT AVERAGES:\n`;
            Object.entries(classAverages).forEach(([subject, data]) => {
                if (data.count > 0) {
                    const average = data.total / data.count;
                    statsMessage += `${subject}: ${average.toFixed(1)}%\n`;
                }
            });
            
            // Calculate overall class average
            const totalAverage = students.reduce((sum, student) => sum + calculateStudentAverage(student), 0);
            const classAverage = totalAverage / students.length;
            
            statsMessage += `\nCLASS AVERAGE: ${classAverage.toFixed(1)}%\n`;
            statsMessage += `BEST STUDENT: ${getTopPerformer().name} (${calculateStudentAverage(getTopPerformer())}%)\n`;
            
            alert(statsMessage);
        }

        // Get top performer
        function getTopPerformer() {
            if (students.length === 0) return null;
            
            return students.reduce((top, student) => {
                const topAvg = calculateStudentAverage(top);
                const studentAvg = calculateStudentAverage(student);
                return studentAvg > topAvg ? student : top;
            }, students[0]);
        }

        // Export functions (simplified for this example)
        function exportCurrentReportCard() {
            alert('Export functionality would generate PDF here');
        }

        function exportSelectedReportCards() {
            alert('Export selected functionality would generate PDF here');
        }

        function printReportCard() {
            window.print();
        }

        // Initialize the app
        document.addEventListener('DOMContentLoaded', init);
    </script>
@endsection

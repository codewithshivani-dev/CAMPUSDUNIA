<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>📚 Daily Lesson Planner</title>
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    
    @yield('styles')
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #f0f4f9;
            color: #0b1a33;
        }

        .top-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            padding: 14px 28px;
            border-radius: 60px;
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.08);
            margin: 20px 32px 16px 32px;
            border: 1px solid rgba(37, 99, 235, 0.06);
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 0 0 auto;
        }
        .logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        .logo-text {
            font-weight: 800;
            font-size: 22px;
            letter-spacing: -0.3px;
            color: #0a1e3c;
        }
        .logo-text span { color: #2563eb; }

        .nav-wrapper {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: nowrap;
            justify-content: flex-end;
            flex: 1 1 auto;
            min-width: 0;
        }

        .employee-selector {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border: 1px solid #dce4ed;
            border-radius: 12px;
            background: #f8fafc;
            color: #4b6a8b;
            font-size: 12px;
        }
        .employee-selector i { color: #2563eb; }
        .employee-selector select {
            width: 125px !important;
            padding: 6px 8px;
            border: 0;
            background: transparent;
            color: #0a1e3c;
            font: inherit;
            outline: none;
            cursor: pointer;
        }

        .nav-tabs {
            display: flex;
            background: #f1f5f9;
            padding: 5px;
            border-radius: 60px;
            gap: 2px;
            flex: 0 1 auto;
            min-width: 0;
        }

        .nav-tab {
            border: none;
            background: transparent;
            padding: 8px 10px;
            border-radius: 60px;
            font-weight: 500;
            font-size: 14px;
            color: #4b6a8b;
            cursor: pointer;
            transition: 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .nav-tab i { font-size: 14px; }
        .nav-tab:hover {
            background: rgba(255, 255, 255, 0.8);
            color: #0a1e3c;
        }
        .nav-tab.active {
            background: white;
            color: #2563eb;
            box-shadow: 0 2px 12px rgba(37, 99, 235, 0.12);
        }

        .btn-new {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            color: white;
            padding: 10px 18px;
            border-radius: 60px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex: 0 0 auto;
            white-space: nowrap;
        }
        .btn-new i { font-size: 14px; }
        .btn-new:hover {
            background: linear-gradient(135deg, #1d4ed8, #1a3fb5);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
        }

        .content-wrapper {
            margin: 0 32px 32px 32px;
            border-radius: 28px;
            overflow: hidden;
            background: #f0f4f9;
            min-height: calc(100vh - 160px);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(10, 30, 60, 0.5);
            backdrop-filter: blur(8px);
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .modal.active { display: flex; }
        .modal-content {
            background: white;
            border-radius: 32px;
            padding: 32px 36px;
            max-width: 960px;
            width: 94%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
            animation: modalSlideIn 0.3s ease;
        }
        @keyframes modalSlideIn {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 24px;
            color: #0a1e3c;
        }
        .modal-header i { color: #2563eb; margin-right: 10px; }
        .close-btn {
            background: none;
            border: none;
            font-size: 32px;
            cursor: pointer;
            color: #8a9bb5;
            line-height: 1;
            transition: 0.2s;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .close-btn:hover { 
            color: #0a1e3c;
            background: #f1f5f9;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px 24px;
        }
        .form-group { margin-bottom: 8px; }
        .form-label {
            display: block;
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 6px;
            color: #0a1e3c;
        }
        .form-label i { color: #2563eb; margin-right: 6px; width: 18px; }
        .form-required { color: #ef4444; }

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 20px;
            margin-top: 4px;
        }
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
        }
        .checkbox-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #2563eb;
            cursor: pointer;
        }
        textarea { min-height: 70px; resize: vertical; width: 100%; }

        select, input[type="date"], input[type="text"], input[type="url"], input[type="file"], input[type="number"], textarea {
            padding: 10px 16px;
            border: 1.5px solid #dce4ed;
            border-radius: 40px;
            font-size: 14px;
            background: #fafcff;
            font-family: inherit;
            outline: none;
            transition: 0.2s;
            width: 100%;
        }
        select:focus, input:focus, textarea:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08);
            background: white;
        }
        input[type="file"] {
            padding: 8px 12px;
            background: #fafcff;
        }

        .success-message {
            background: #d1fae5;
            color: #0b6e4f;
            padding: 14px 22px;
            border-radius: 60px;
            display: none;
            font-weight: 500;
            margin-bottom: 20px;
        }
        .success-message.show { display: block; }

        .action-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .btn {
            border: none;
            padding: 8px 20px;
            border-radius: 60px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: 0.2s;
            background: #eef2f6;
            color: #1f334f;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn i { font-size: 14px; }
        .btn-primary {
            background: #2563eb;
            color: white;
        }
        .btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); }
        .btn-success {
            background: #10b981;
            color: white;
        }
        .btn-success:hover { background: #059669; transform: translateY(-1px); }
        .btn-danger {
            background: #ef4444;
            color: white;
        }
        .btn-danger:hover { background: #dc2626; transform: translateY(-1px); }
        .btn-secondary {
            background: #e6ecf3;
            color: #1f334f;
        }
        .btn-secondary:hover { background: #d1d9e6; transform: translateY(-1px); }
        .btn-warning {
            background: #f59e0b;
            color: white;
        }
        .btn-warning:hover { background: #d97706; transform: translateY(-1px); }
        .btn-small { padding: 6px 14px; font-size: 12px; }

        .topic-row {
            border: 1.5px solid #dce4ed;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 12px;
            background: #fafcff;
            transition: 0.2s;
        }
        .topic-row:hover {
            border-color: #2563eb;
            box-shadow: 0 2px 12px rgba(37, 99, 235, 0.06);
        }
        .topic-row .topic-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .topic-row .topic-fields-full {
            grid-column: 1 / -1;
        }

        .topic-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 12px;
            border-bottom: 1px solid #eef2f6;
            flex-wrap: wrap;
            gap: 8px;
        }
        .topic-item:last-child { border-bottom: none; }
        .topic-number {
            font-weight: 600;
            color: #2563eb;
            min-width: 30px;
        }
        .topic-title { flex: 1; margin: 0 12px; min-width: 100px; }
        .topic-resources {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }
        .topic-resources a {
            color: #2563eb;
            text-decoration: none;
            font-size: 13px;
        }
        .topic-resources .file-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #eef2f6;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 12px;
            color: #3b4e6b;
        }
        .topic-video {
            width: 160px;
            height: 90px;
            border-radius: 8px;
            overflow: hidden;
        }
        .topic-video iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .coverage-badge {
            display: inline-block;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }
        .coverage-badge.covered { background: #d1fae5; color: #0b6e4f; }
        .coverage-badge.partial { background: #fef3c7; color: #a16207; }
        .coverage-badge.not-covered { background: #fee2e2; color: #dc2626; }

        .status-badge {
            display: inline-block;
            padding: 4px 16px;
            border-radius: 60px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-draft { background: #eef2f6; color: #4b6a8b; }
        .status-active { background: #d1fae5; color: #0b6e4f; }
        .status-review { background: #fef3c7; color: #a16207; }

        @media (max-width: 820px) {
            .top-bar {
                flex-direction: column;
                align-items: stretch;
                gap: 14px;
                border-radius: 32px;
                padding: 16px 20px;
                margin: 12px 16px;
            }
            .nav-wrapper {
                flex-direction: column;
                align-items: stretch;
            }
            .nav-tabs { 
                overflow-x: auto; 
                flex-wrap: nowrap;
            }
            .nav-tab { 
                white-space: nowrap;
                padding: 6px 14px;
                font-size: 13px;
            }
            .content-wrapper { margin: 0 16px 16px 16px; }
            .form-grid { grid-template-columns: 1fr; }
            .topic-row .topic-fields { grid-template-columns: 1fr; }
            .modal-content { padding: 20px; }
        }
    </style>
</head>
<body>

    <!-- ===== TOP BAR ===== -->
    @hasSection('topbar')
        @yield('topbar')
    @else
        @php
            $plannerUser = auth()->user();
            $plannerIsAdmin = $plannerUser && $plannerUser->hasAnyRole(['admin', 'superadmin', 'super_admin', 'institute_admin']);
            $plannerEmployees = $plannerIsAdmin
                ? \App\Models\EmployeeDetails::where('institute_id', $plannerUser->institute_id)->orderBy('name')->get()
                : collect();
            $plannerDepartments = $plannerIsAdmin
                ? \App\Models\Departments::where('institute_id', $plannerUser->institute_id)->orderBy('department')->get()
                : collect();
            $plannerSelectedEmployee = $plannerEmployees->firstWhere('employee_id', request('employee_id'));
            $plannerSelectedDepartmentId = request('department_id') ?: optional($plannerSelectedEmployee)->department_id;
            $plannerContextQuery = request()->only(['department_id', 'employee_id']);
        @endphp
        <div class="top-bar">
            <div class="logo-area">
                <div class="logo-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <span class="logo-text">Daily<span>Planner</span></span>
            </div>
            <div class="nav-wrapper">
                @if($plannerIsAdmin)
                    <label class="employee-selector" for="planner-employee">
                        <i class="fas fa-user-tie"></i>
                        <span>Department</span>
                        <select id="planner-department" onchange="filterPlannerEmployees(this.value)">
                            <option value="">Select department</option>
                            @foreach($plannerDepartments as $plannerDepartment)
                                <option value="{{ $plannerDepartment->department_id }}" @if((string) $plannerSelectedDepartmentId === (string) $plannerDepartment->department_id) selected @endif>
                                    {{ $plannerDepartment->department }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <label class="employee-selector" for="planner-employee">
                        <i class="fas fa-user-tie"></i>
                        <span>Employee</span>
                        <select id="planner-employee" onchange="changePlannerEmployee(this.value)" disabled>
                            <option value="">Select employee</option>
                            @foreach($plannerEmployees as $plannerEmployee)
                                @php
                                    $plannerEmployeeName = $plannerEmployee->name;
                                    if (!$plannerEmployeeName) {
                                        $plannerEmployeeName = $plannerEmployee->employee_id;
                                    }
                                @endphp
                                <option value="{{ $plannerEmployee->employee_id }}" data-department="{{ $plannerEmployee->department_id }}" {{ (string) request('employee_id') === (string) $plannerEmployee->employee_id ? 'selected' : '' }}>
                                    {{ $plannerEmployeeName }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                @endif
                <div class="nav-tabs">
                    <a href="{{ route('lesson-planner.dashboard', $plannerContextQuery) }}" class="nav-tab {{ request()->routeIs('lesson-planner.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i> Dashboard
                    </a>
                    <a href="{{ route('lesson-planner.plans', $plannerContextQuery) }}" class="nav-tab {{ request()->routeIs('lesson-planner.plans') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i> Calendar
                    </a>
                    <a href="{{ route('lesson-planner.review', $plannerContextQuery) }}" class="nav-tab {{ request()->routeIs('lesson-planner.review') ? 'active' : '' }}">
                        <i class="fas fa-check-double"></i> Performane
                    </a>
                    <!-- <a href="{{ route('lesson-planner.coverage') }}" class="nav-tab {{ request()->routeIs('lesson-planner.coverage') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i> Coverage
                    </a> -->
                    <a href="{{ route('lesson-planner.reports', $plannerContextQuery) }}" class="nav-tab {{ request()->routeIs('lesson-planner.reports') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i> Reports
                    </a>
                </div>
                <a href="{{ route('lesson-planner.new', $plannerContextQuery) }}" class="btn-new">
                    <i class="fas fa-plus-circle"></i> New Plan
                </a>
            </div>
        </div>
    @endif

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function filterPlannerEmployees(departmentId) {
            const employeeSelect = document.getElementById('planner-employee');
            const selectedEmployeeId = @json(request('employee_id'));
            employeeSelect.disabled = !departmentId;

            Array.from(employeeSelect.options).forEach(option => {
                const isEmployee = option.value !== '';
                const matchesDepartment = option.dataset.department === departmentId;
                option.hidden = isEmployee && !matchesDepartment;
                if (isEmployee && !matchesDepartment) option.selected = false;
            });

            const selectedOption = selectedEmployeeId
                ? employeeSelect.querySelector(`option[value="${selectedEmployeeId}"]`)
                : null;
            employeeSelect.value = selectedOption && !selectedOption.hidden ? selectedEmployeeId : '';
        }

        function changePlannerEmployee(employeeId) {
            const url = new URL(window.location.href);
            if (employeeId) {
                url.searchParams.set('employee_id', employeeId);
                url.searchParams.set('department_id', document.getElementById('planner-department').value);
            } else {
                url.searchParams.delete('employee_id');
            }
            window.location.href = url.toString();
        }

        document.addEventListener('DOMContentLoaded', function () {
            const departmentSelect = document.getElementById('planner-department');
            if (departmentSelect) filterPlannerEmployees(departmentSelect.value);
        });
    </script>

    <!-- ===== CONTENT ===== -->
    <div class="content-wrapper">
        @yield('content')
    </div>

    <!-- ===== SHARED MODAL ===== -->
    @include('instituteAdmin.Lesson-planner.partials.modal')

    <script>
        // ===== DATA STORAGE =====
        const lessonPlansStorageKey = 'dailyLessonPlannerData';

        function getDefaultPlans() {
            const today = new Date();
            const dates = [];
            for (let i = 0; i < 5; i++) {
                const d = new Date(today);
                d.setDate(d.getDate() + i);
                dates.push(d.toISOString().split('T')[0]);
            }

            return [
                { 
                    id: 1, 
                    title: 'Introduction to English', 
                    class: 'KG / A', 
                    section: 'A',
                    subject: 'English', 
                    month: 'August 2026',
                    week: 'Week 1',
                    periods: 4, 
                    status: 'draft', 
                    teacher: 'John Doe', 
                    objectives: 'Learn basic English vocabulary',
                    methods: ['Lecture', 'Activity'],
                    aids: ['Textbook', 'Worksheets'],
                    resources: 'Black board, flashcards',
                    coverage: 20,
                    dailyTopics: {
                        [dates[0]]: [
                            { 
                                number: 1, 
                                title: 'Alphabet Recognition', 
                                video: '', 
                                files: [],
                                resources: [], 
                                covered: false 
                            },
                            { 
                                number: 2, 
                                title: 'Basic Vocabulary', 
                                video: '', 
                                files: [],
                                resources: [], 
                                covered: false 
                            }
                        ],
                        [dates[1]]: [
                            { 
                                number: 1, 
                                title: 'Simple Sentences', 
                                video: 'https://www.youtube.com/embed/dQw4w9WgXcQ', 
                                files: [
                                    { name: 'Lesson Plan.pdf', url: '#', type: 'pdf' }
                                ],
                                resources: ['Worksheet'], 
                                covered: false 
                            }
                        ]
                    }
                }
            ];
        }

        function loadPlans() { 
            try {
                const stored = localStorage.getItem(lessonPlansStorageKey);
                if (stored) {
                    const parsed = JSON.parse(stored);
                    if (Array.isArray(parsed) && parsed.length) {
                        return parsed;
                    }
                }
            } catch (e) {
                console.warn('Could not load lesson plans from storage:', e);
            }
            return getDefaultPlans();
        }

        function savePlans(plans) {
            window.plansData = plans;
            localStorage.setItem(lessonPlansStorageKey, JSON.stringify(plans));
        }

        window.plansData = loadPlans();

        // ===== HELPER FUNCTIONS =====
        window.getPlans = function() { return window.plansData; };

        window.getStats = function() {
            const plans = window.getPlans();
            return {
                total: plans.length,
                draft: plans.filter(p => p.status === 'draft').length,
                active: plans.filter(p => p.status === 'active').length
            };
        };

        window.capitalize = function(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        };

        window.getStatusBadge = function(status) {
            const colors = {
                draft: 'status-draft',
                active: 'status-active',
                review: 'status-review'
            };
            return `<span class="status-badge ${colors[status] || 'status-draft'}">${window.capitalize(status)}</span>`;
        };

        // ===== MODAL FUNCTIONS =====
        function openNewLessonModal() {
            $('#lessonModal').addClass('active');
            $('#lessonForm')[0].reset();
            $('#successMessage').removeClass('show');
            $('#topicContainer').empty();
            topicCounter = 0;
            addTopicRow();
            
            const now = new Date();
            const monthYear = now.toLocaleString('default', { month: 'long', year: 'numeric' });
            $('#formMonth').val(monthYear);
            $('#formDate').val(now.toISOString().split('T')[0]);
        }

        function closeModal() {
            $('#lessonModal').removeClass('active');
        }

        // ===== TOPIC MANAGEMENT =====
        let topicCounter = 0;

        function addTopicRow() {
            const container = $('#topicContainer');
            const num = ++topicCounter;
            
            const row = `
                <div class="topic-row" data-topic="${num}">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                        <strong style="color:#2563eb;"><i class="fas fa-book"></i> Topic #${num}</strong>
                        <button type="button" class="btn btn-danger btn-small" onclick="removeTopicRow(this)"><i class="fas fa-trash-alt"></i> Remove</button>
                    </div>
                    <div class="topic-fields">
                        <div class="topic-fields-full">
                            <label style="font-size:13px; font-weight:500; color:#3b4e6b;"><i class="fas fa-heading" style="color:#2563eb;"></i> Topic Title <span class="form-required">*</span></label>
                            <input type="text" class="topic-title-input" placeholder="Enter topic title" style="margin-top:4px;" />
                        </div>
                        <div>
                            <label style="font-size:13px; font-weight:500; color:#3b4e6b;"><i class="fab fa-youtube" style="color:#ff0000;"></i> YouTube Video URL</label>
                            <input type="url" class="topic-video-input" placeholder="https://youtube.com/..." style="margin-top:4px;" />
                        </div>
                        <div>
                            <label style="font-size:13px; font-weight:500; color:#3b4e6b;"><i class="fas fa-upload" style="color:#2563eb;"></i> Upload PDF/File</label>
                            <input type="file" class="topic-file-input" accept=".pdf,.doc,.docx,.ppt,.pptx" multiple style="margin-top:4px;" />
                            <div class="file-list" style="margin-top:6px;"></div>
                        </div>
                        <div class="topic-fields-full">
                            <label style="font-size:13px; font-weight:500; color:#3b4e6b;"><i class="fas fa-cubes" style="color:#2563eb;"></i> Resources (comma separated)</label>
                            <input type="text" class="topic-resources-input" placeholder="e.g. Textbook, Worksheet" style="margin-top:4px;" />
                        </div>
                    </div>
                </div>
            `;
            
            container.append(row);
            
            // Handle file input change
            const fileInput = container.find('.topic-file-input').last();
            fileInput.on('change', function() {
                const fileList = $(this).siblings('.file-list');
                fileList.empty();
                
                const files = this.files;
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const fileSize = (file.size / 1024).toFixed(1);
                    const icon = file.type.includes('pdf') ? 'fa-file-pdf' : 
                                 file.type.includes('word') ? 'fa-file-word' :
                                 file.type.includes('powerpoint') ? 'fa-file-powerpoint' : 'fa-file';
                    const color = file.type.includes('pdf') ? '#dc2626' : 
                                 file.type.includes('word') ? '#2563eb' :
                                 file.type.includes('powerpoint') ? '#f59e0b' : '#6b7280';
                    
                    fileList.append(`
                        <div style="display:flex; align-items:center; gap:8px; padding:4px 12px; background:white; border-radius:8px; margin-bottom:4px; font-size:13px; border:1px solid #e6ecf3;">
                            <i class="fas ${icon}" style="color:${color};"></i>
                            <span style="flex:1;">${file.name}</span>
                            <span style="color:#8a9bb5; font-size:11px;">(${fileSize} KB)</span>
                            <button type="button" class="btn btn-danger btn-small" onclick="removeFile(this)" style="padding:2px 8px; font-size:10px;"><i class="fas fa-times"></i></button>
                        </div>
                    `);
                }
            });
        }

        function removeFile(btn) {
            $(btn).closest('div').remove();
        }

        function removeTopicRow(btn) {
            $(btn).closest('.topic-row').remove();
        }

        function addTopic() {
            addTopicRow();
        }

        function getTopicsFromForm() {
            const topics = [];
            $('#topicContainer .topic-row').each(function() {
                const title = $(this).find('.topic-title-input').val().trim();
                const video = $(this).find('.topic-video-input').val().trim();
                const resources = $(this).find('.topic-resources-input').val().trim();
                const files = [];
                
                // Get file list from the file input
                $(this).find('.file-list div').each(function() {
                    const name = $(this).find('span:first-child').text().trim();
                    if (name) {
                        files.push({
                            name: name,
                            url: '#',
                            type: 'file'
                        });
                    }
                });
                
                if (title) {
                    topics.push({
                        number: topics.length + 1,
                        title: title,
                        video: video,
                        files: files,
                        resources: resources ? resources.split(',').map(r => r.trim()) : [],
                        covered: false
                    });
                }
            });
            return topics;
        }

        // ===== SAVE LESSON PLAN =====
        function saveLessonPlan(e) {
            e.preventDefault();

            const topics = getTopicsFromForm();
            const date = $('#formDate').val();
            
            if (topics.length === 0) {
                alert('Please add at least one topic');
                return;
            }
            
            if (!date) {
                alert('Please select a date');
                return;
            }
            
            const newPlan = {
                id: Date.now(),
                title: $('#formTitle').val().trim() || ($('#formSubject').val() + ' - ' + $('#formClass').val()),
                class: $('#formClass').val(),
                section: $('#formSection').val(),
                subject: $('#formSubject').val(),
                month: $('#formMonth').val(),
                week: $('#formWeek').val(),
                periods: $('#formPeriods').val() || 5,
                status: window.pendingSubmit ? 'active' : 'draft',
                teacher: 'You',
                objectives: $('#formObjectives').val(),
                methods: $('input[name="methods"]:checked').map(function() { return this.value; }).get(),
                aids: $('input[name="aids"]:checked').map(function() { return this.value; }).get(),
                resources: $('#formResources').val(),
                coverage: Math.floor(Math.random() * 50),
                dailyTopics: {
                    [date]: topics
                }
            };

            const updatedPlans = [...window.plansData, newPlan];
            savePlans(updatedPlans);
            window.pendingSubmit = false;
            
            $('#successMessage').addClass('show');
            setTimeout(() => {
                closeModal();
                if (window.refreshLessonPlannerViews) {
                    window.refreshLessonPlannerViews();
                } else {
                    location.reload();
                }
            }, 800);
        }

        function submitLessonPlan() {
            const topics = getTopicsFromForm();
            if (topics.length === 0) {
                alert('Please add at least one topic');
                return;
            }
            if (!$('#formClass').val() || !$('#formSubject').val() || !$('#formObjectives').val()) {
                alert('Please fill required fields');
                return;
            }
            window.pendingSubmit = true;
            const form = document.getElementById('lessonForm');
            form.querySelector('button[type="submit"]').click();
        }

        // ===== GLOBAL ACTION FUNCTIONS =====
        window.approvePlan = function(id) {
            const plan = window.plansData.find(p => p.id === id);
            if (plan) {
                plan.status = 'active';
                savePlans([...window.plansData]);
                alert('Plan activated!');
                if (window.refreshLessonPlannerViews) {
                    window.refreshLessonPlannerViews();
                } else {
                    location.reload();
                }
            }
        };

        window.rejectPlan = function(id) {
            const plan = window.plansData.find(p => p.id === id);
            if (plan) {
                plan.status = 'draft';
                savePlans([...window.plansData]);
                alert('Plan rejected and returned to draft!');
                if (window.refreshLessonPlannerViews) {
                    window.refreshLessonPlannerViews();
                } else {
                    location.reload();
                }
            }
        };

        window.editPlan = function(id) {
            const plan = window.plansData.find(p => p.id === id);
            if (plan) {
                openNewLessonModal();
                $('#formTitle').val(plan.title);
                $('#formClass').val(plan.class);
                $('#formSection').val(plan.section);
                $('#formSubject').val(plan.subject);
                $('#formObjectives').val(plan.objectives);
                $('#formPeriods').val(plan.periods);
                $('#formMonth').val(plan.month);
                $('#formWeek').val(plan.week);
                
                const dates = Object.keys(plan.dailyTopics || {});
                if (dates.length > 0) {
                    $('#formDate').val(dates[0]);
                    const topics = plan.dailyTopics[dates[0]] || [];
                    $('#topicContainer').empty();
                    topics.forEach((topic, index) => {
                        addTopicRow();
                        const row = $('#topicContainer .topic-row').last();
                        row.find('.topic-title-input').val(topic.title);
                        row.find('.topic-video-input').val(topic.video || '');
                        row.find('.topic-resources-input').val(topic.resources ? topic.resources.join(', ') : '');
                        
                        if (topic.files && topic.files.length > 0) {
                            const fileList = row.find('.file-list');
                            topic.files.forEach(file => {
                                fileList.append(`
                                    <div style="display:flex; align-items:center; gap:8px; padding:4px 12px; background:white; border-radius:8px; margin-bottom:4px; font-size:13px; border:1px solid #e6ecf3;">
                                        <i class="fas fa-file-pdf" style="color:#dc2626;"></i>
                                        <span style="flex:1;">${file.name}</span>
                                        <button type="button" class="btn btn-danger btn-small" onclick="removeFile(this)" style="padding:2px 8px; font-size:10px;"><i class="fas fa-times"></i></button>
                                    </div>
                                `);
                            });
                        }
                    });
                }
                
                plan.methods.forEach(method => {
                    $(`input[name="methods"][value="${method}"]`).prop('checked', true);
                });
                
                const updatedPlans = window.plansData.filter(p => p.id !== id);
                savePlans(updatedPlans);
            }
        };

        window.duplicatePlan = function(id) {
            const plan = window.plansData.find(p => p.id === id); 
            if (plan) {
                const newPlan = {
                    ...plan,
                    id: Date.now(),
                    title: plan.title + ' (Copy)',
                    status: 'draft',
                };
                const updatedPlans = [...window.plansData, newPlan];
                savePlans(updatedPlans);
                alert('Plan duplicated successfully!');
                if (window.refreshLessonPlannerViews) {
                    window.refreshLessonPlannerViews();
                } else {
                    location.reload();
                }
            }
        };

        window.markCoverage = function(planId, date, topicIndex) {
            const plan = window.plansData.find(p => p.id === planId);
            if (plan && plan.dailyTopics && plan.dailyTopics[date]) {
                const topic = plan.dailyTopics[date][topicIndex];
                if (topic) {
                    topic.covered = !topic.covered;
                    savePlans([...window.plansData]);
                    if (window.refreshLessonPlannerViews) {
                        window.refreshLessonPlannerViews();
                    } else {
                        location.reload();
                    }
                }
            }
        };

        window.getYoutubeId = function(url) {
            if (!url) return null;
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            const match = url.match(regExp);
            if (match && match[2].length === 11) {
                return match[2];
            }
            return null;
        };

        window.renderTopics = function(topics) {
            if (!topics || topics.length === 0) return '<span style="color:#8a9bb5;">No topics</span>';
            
            return topics.map((t, index) => {
                const videoId = window.getYoutubeId(t.video);
                const isCovered = t.covered || false;
                const badgeClass = isCovered ? 'covered' : 'not-covered';
                const badgeText = isCovered ? '<i class="fas fa-check-circle"></i> Covered' : '<i class="fas fa-clock"></i> Pending';
                
                // Render files
                const fileHtml = t.files && t.files.length > 0 ? 
                    t.files.map(f => `
                        <span class="file-link">
                            <i class="fas fa-file"></i> ${f.name}
                        </span>
                    `).join('') : '';
                
                return `
                    <div class="topic-item">
                        <span class="topic-number">#${t.number}</span>
                        <span class="topic-title">${t.title}</span>
                        <span class="coverage-badge ${badgeClass}" onclick="window.markCoverage(window.currentPlanId, window.currentDate, ${index})">
                            ${badgeText}
                        </span>
                        <div class="topic-resources">
                            ${t.resources && t.resources.length ? t.resources.map(r => `<span style="background:#eef2f6; padding:2px 10px; border-radius:20px; font-size:11px;"><i class="fas fa-tag"></i> ${r}</span>`).join('') : ''}
                            ${fileHtml}
                            ${t.video ? `<a href="${t.video}" target="_blank"><i class="fab fa-youtube" style="color:#ff0000;"></i> Watch</a>` : ''}
                            ${videoId ? `<div class="topic-video"><iframe src="https://www.youtube.com/embed/${videoId}" allowfullscreen></iframe></div>` : ''}
                        </div>
                    </div>
                `;
            }).join('');
        };
    </script>
</body>
</html>
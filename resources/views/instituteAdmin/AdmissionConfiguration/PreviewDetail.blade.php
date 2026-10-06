@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
@section('content')
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #1e293b;
            --light-bg: #f8fafc;
            --border: #e2e8f0;
            --success: #059669;
            --warning: #f59e0b;
            --danger: #dc2626;
            --text-primary: #334155;
            --text-secondary: #64748b;
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .back-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }
        
        .back-btn {
            padding: 10px 16px;
            background: white;
            color: var(--primary);
            border: 1px solid var(--primary);
            border-radius: var(--radius-sm);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .back-btn:hover {
            background: var(--primary);
            color: white;
        }
        
        .header-actions {
            display: flex;
            gap: 12px;
        }
        
        .edit-btn {
            padding: 10px 20px;
            background: #fbbf24;
            color: #92400e;
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .edit-btn:hover {
            background: #f59e0b;
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }
        
        .page-title {
            color: var(--secondary);
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
        }
        
        .main-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            border: 1px solid var(--border);
        }
        
        .config-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .config-header h1 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }
        
        .config-header p {
            font-size: 1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        /* Edit Mode Styles */
        .edit-mode {
            border: 2px solid var(--primary);
        }
        
        .edit-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 1rem;
            background: white;
        }
        
        .edit-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 1rem;
            background: white;
        }
        
        .tabs {
            display: flex;
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            gap: 4px;
            overflow-x: auto;
        }
        
        .tab-btn {
            padding: 16px 0;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 500;
            color: var(--text-secondary);
            font-size: 0.875rem;
            position: relative;
            transition: color 0.2s ease;
            margin: 0 16px 0 0;
            white-space: nowrap;
        }
        
        .tab-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: transparent;
        }
        
        .tab-btn:hover {
            color: var(--primary);
        }
        
        .tab-btn.active {
            color: var(--primary);
            font-weight: 600;
        }
        
        .tab-btn.active::after {
            background: var(--primary);
        }
        
        .tab-content {
            padding: 24px;
            max-height: 100vh;
            overflow-y: auto;
        }
        
        .tab-pane {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        
        .tab-pane.active {
            display: block;
        }
        
        .section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 0 0 20px 0;
            color: var(--secondary);
            font-size: 1.125rem;
            font-weight: 600;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--light-bg);
        }
        
        .toggle-switch {
            position: relative;
            width: 44px;
            height: 24px;
        }
        
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .toggle-slider {
            background-color: var(--success);
        }
        
        input:checked + .toggle-slider:before {
            transform: translateX(20px);
        }
        
        /* Product Configuration Sections */
        .product-section {
            background: var(--light-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .product-section.hidden {
            display: none;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .form-group {
            margin-bottom: 16px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: var(--text-primary);
            font-size: 0.875rem;
        }
        
        .info-card {
            background: white;
            padding: 16px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            margin-bottom: 10px;
            transition: all 0.2s ease;
        }
        
        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .info-card.full {
            background: #fef2f2;
            border-color: #fecaca;
        }
        
        .info-card.limited {
            background: #fffbeb;
            border-color: #fed7aa;
        }
        
        .info-label {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            margin-bottom: 6px;
            font-weight: 500;
        }
        
        .info-value {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 1rem;
            line-height: 1.4;
        }
        
        /* Tables */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            overflow: hidden;
        }
        
        .details-table thead {
            background: var(--light-bg);
        }
        
        .details-table th {
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            color: var(--secondary);
            font-size: 0.8125rem;
            border-bottom: 1px solid var(--border);
        }
        
        .details-table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            font-size: 0.875rem;
        }
        
        .details-table tr:hover {
            background: #f8fafc;
        }
        
        /* Availability badges */
        .availability-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .availability-badge.available {
            background: #05966915;
            color: #059669;
        }
        
        .availability-badge.limited {
            background: #f59e0b15;
            color: #f59e0b;
        }
        
        .availability-badge.full {
            background: #dc262615;
            color: #dc2626;
        }
        
        /* Progress bar */
        .progress-bar {
            width: 100%;
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 4px;
        }
        
        .progress-fill {
            height: 100%;
            transition: width 0.3s ease;
            animation: fillProgress 1s ease-out;
        }
        
        @keyframes fillProgress {
            from { width: 0; }
            to { width: 100%; }
        }
        
        /* Stats grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin-top: 12px;
        }
        
        .stat-box {
            background: #f8fafc;
            border-radius: var(--radius-sm);
            padding: 10px;
            text-align: center;
        }
        
        .stat-label {
            font-size: 0.7rem;
            color: var(--text-secondary);
            margin-bottom: 4px;
        }
        
        .stat-value {
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .stat-value.available {
            color: #059669;
        }
        
        .stat-value.booked {
            color: #64748b;
        }
        
        /* Time slots grid */
        .time-slots-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 16px;
            margin-top: 16px;
        }
        
        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 20px 24px;
            background: var(--light-bg);
            border-top: 1px solid var(--border);
        }
        
        .btn {
            padding: 10px 24px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        
        .btn-cancel {
            background: white;
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }
        
        .btn-save {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }
        
        /* Edit Mode Actions */
        .edit-actions {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }
        
        /* Full badge */
        .full-badge {
            position: absolute;
            top: 10px;
            right: -30px;
            background: #dc2626;
            color: white;
            padding: 4px 30px;
            transform: rotate(45deg); 
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .container { padding: 16px; } 
            .form-grid { grid-template-columns: 1fr; } 
            .tabs { padding: 0 16px; }
            .tab-content { padding: 16px; } 
            .back-header { flex-direction: column; gap: 16px; }
            .header-actions { width: 100%; justify-content: center; }
            .time-slots-grid { grid-template-columns: 1fr; }
        }
    </style> 

    <div class="container">
        <div class="back-header">
            <a href="{{ route('config.list') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Back to List
            </a>
            <h1 class="page-title">Configuration Details</h1>
            <div class="header-actions">
                <button id="editToggle" class="edit-btn">
                    <i class="fas fa-edit"></i>
                    Edit Configuration
                </button>
            </div>
        </div>

        @php
            $selectedFee = collect($customFees)->firstWhere('custom_reference_id', $config['admission_form_fee_amount']);
            $classes = $config['product_id'];
            $classNames = collect($classes)->pluck('name')->implode(', ');
            
            // Parse dates for display
            $formStartDate = \Carbon\Carbon::parse($config['admission_form_start_date'])->format('d M Y');
            $formEndDate = \Carbon\Carbon::parse($config['admission_form_end_date'])->format('d M Y');
            $sessionStartDate = \Carbon\Carbon::parse($config['onboarding_start_date'])->format('d M Y');
            $classesStartDate = \Carbon\Carbon::parse($config['onboarding_classes_start_date'])->format('d M Y');
            
            // Counselling time slots
            $counsellingSlots = $config->counsellingTimeSlots ?? [];
            
            // Entrance tests
            $entranceTests = $config->entranceTestSlots ?? [];

            $formsSubmitted = $formsSubmitted ?? 0;
            $formMax = $config['admission_form_max'] ?? 0;
            $formSubmissionPercentage = $formMax > 0 ? min(100, round(($formsSubmitted / $formMax) * 100, 2)) : 0;
        @endphp

        <div class="main-card" id="configCard">
            <!-- Config Header -->
            <div class="config-header">
                <h1 id="configTitle">{{ $classNames }}</h1>
                <p id="configSubtitle">{{ $config->department->department }} • {{ $config['academic_year'] }}</p>
            </div>
            
            <!-- Tabs -->
            <div class="tabs">
                <button class="tab-btn active" data-tab="basic">
                    <i class="fas fa-info-circle"></i>
                    Basic Info
                </button>
                <button class="tab-btn" data-tab="form">
                    <i class="fas fa-file-alt"></i>
                    Admission Form
                </button>
                <button class="tab-btn" data-tab="tests">
                    <i class="fas fa-clipboard-check"></i>
                    Entrance Tests
                </button>
                <button class="tab-btn" data-tab="counselling">
                    <i class="fas fa-comments"></i>
                    Counselling
                </button>
                <button class="tab-btn" data-tab="onboarding">
                    <i class="fas fa-user-graduate"></i>
                    Onboarding
                </button>
            </div>
            
            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Basic Info Tab -->
                <div class="tab-pane active" id="basic-tab">
                    <h4 class="section-title">Basic Information</h4>
                    <div class="form-grid">
                        <div class="form-group">
                            <div class="info-label">Classes Name</div>
                            <div class="info-value" id="displayName">{{ $classNames }}</div>
                            <input type="text" id="editName" class="edit-input" value="{{ $classNames }}" style="display: none;">
                        </div>
                        
                        <div class="form-group">
                            <div class="info-label">Department</div>
                            <div class="info-value" id="displayDepartment">{{ $config->department->department }}</div>
                            <select id="editDepartment" class="edit-select" style="display: none;">
                                <option value="Primary Class" {{ $config->department->department == 'Primary Class' ? 'selected' : '' }}>Primary Class</option>
                                <option value="Computer Science" {{ $config->department->department == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                                <option value="Mechanical Engineering" {{ $config->department->department == 'Mechanical Engineering' ? 'selected' : '' }}>Mechanical Engineering</option>
                                <option value="Electronics" {{ $config->department->department == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <div class="info-label">Academic Year</div>
                            <div class="info-value" id="displaySession">{{ $config['academic_year'] }}</div>
                            <input type="text" id="editSession" class="edit-input" value="{{ $config['academic_year'] }}" style="display: none;">
                        </div>
                        
                        <div class="form-group">
                            <div class="info-label">Status</div>
                            <div class="info-value" id="displayStatus">{{ $config['is_active'] == 1 ? 'Active' : 'Inactive' }}</div>
                            <select id="editStatus" class="edit-select" style="display: none;">
                                <option value="1" {{ $config['is_active'] == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $config['is_active'] == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <div class="info-label">Configuration ID</div>
                            <div class="info-value">{{ $config['admissionprocess_confun_id'] }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Admission Form Tab -->
                <div class="tab-pane" id="form-tab">
                    <div class="section-title">
                        <span>Admission Form Configuration</span>
                        <label class="toggle-switch" id="formToggleContainer">
                            <input type="checkbox" id="formToggle" {{ $config['admission_form_enabled'] == 1 ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    
                    <div class="product-section {{ $config['admission_form_enabled'] == 0 ? 'hidden' : '' }}" id="formSection">
                        <div class="form-grid">
                            <div class="form-group">
                                <div class="info-label">Submission Mode</div>
                                <div class="info-value" id="displayFormMode">{{ ucfirst($config['admission_form_mode']) }}</div>
                                <select id="editFormMode" class="edit-select" style="display: none;">
                                    <option value="online" {{ $config['admission_form_mode'] == 'online' ? 'selected' : '' }}>Online Only</option>
                                    <option value="offline" {{ $config['admission_form_mode'] == 'offline' ? 'selected' : '' }}>Offline Only</option>
                                    <option value="both" {{ $config['admission_form_mode'] == 'both' ? 'selected' : '' }}>Both</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="info-label">Application Fee</div>
                                <div class="info-value" id="displayFormFee">₹{{ $selectedFee['custom_fee_value'] }}</div>
                                <input type="number" id="editFormFee" class="edit-input" value="{{ $config['admission_form_fee_amount'] }}" style="display: none;">
                            </div>
                            
                            <div class="form-group">
                                <div class="info-label">Maximum Forms</div>
                                <div class="info-value" id="displayMaxForms">{{ number_format($config['admission_form_max']) }}</div>
                                <input type="number" id="editMaxForms" class="edit-input" value="{{ $config['admission_form_max'] }}" style="display: none;">
                            </div>
                            
                            <div class="form-group">
                                <div class="info-label">Start Date</div>
                                <div class="info-value" id="displayFormStart">{{ $formStartDate }}</div>
                                <input type="date" id="editFormStart" class="edit-input" value="{{ $config['admission_form_start_date'] }}" style="display: none;">
                            </div>
                            
                            <div class="form-group">
                                <div class="info-label">End Date</div>
                                <div class="info-value" id="displayFormEnd">{{ $formEndDate }}</div>
                                <input type="date" id="editFormEnd" class="edit-input" value="{{ $config['admission_form_end_date'] }}" style="display: none;">
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 20px;">
                            <div class="info-label">Forms Submitted</div>
                            <div class="info-value" style="color: var(--primary);">
                                {{ number_format($formsSubmitted) }} / {{ number_format($formMax) }} forms submitted
                            </div>
                            <div class="progress-bar" style="margin-top: 8px;">
                                <div class="progress-fill" style="width: {{ $formSubmissionPercentage }}%; background: var(--primary);"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tests Tab -->
                <div class="tab-pane" id="tests-tab">
                    <div class="section-title">
                        <span>Entrance Tests Configuration</span>
                        <label class="toggle-switch" id="testsToggleContainer">
                            <input type="checkbox" id="testsToggle" {{ $config['entrance_tests_enabled'] == 1 ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    
                    <div class="product-section {{ $config['entrance_tests_enabled'] == 0 ? 'hidden' : '' }}" id="testsSection">
                        <div id="testsTable">
                            @if($entranceTests->count() > 0)
                                <table class="details-table">
                                    <thead>
                                        <tr>
                                            <th>Test Name</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Duration</th>
                                            <th>Capacity</th>
                                            <th>Availability</th>
                                        </tr>
                                    </thead>
                                    <tbody id="testsTableBody">
                                        @foreach($entranceTests as $test)
                                            @php
                                                $testDate = \Carbon\Carbon::parse($test['test_date'])->format('d M Y');
                                                $startTime = \Carbon\Carbon::parse($test['start_time'])->format('h:i A');
                                                $endTime = \Carbon\Carbon::parse($test['end_time'])->format('h:i A');
                                                
                                                $bookedCount = $test['booked_count'] ?? $test->booked_count ?? 0;
                                                $availableCount = $test['available_count'] ?? max(0, ($test['capacity'] ?? 0) - $bookedCount);
                                                $capacity = $test['capacity'] ?? 0;
                                                $availabilityPercentage = $capacity > 0 ? round(($bookedCount / $capacity) * 100, 2) : 0;
                                                
                                                $status = 'available';
                                                $statusColor = '#059669';
                                                if ($availableCount == 0) {
                                                    $status = 'full';
                                                    $statusColor = '#dc2626';
                                                } elseif ($availableCount < 10) {
                                                    $status = 'limited';
                                                    $statusColor = '#f59e0b';
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $test['test_name'] }}</td>
                                                <td>{{ $testDate }}</td>
                                                <td>{{ $startTime }} - {{ $endTime }}</td>
                                                <td>{{ $test['duration_minutes'] }} mins</td>
                                                <td>{{ number_format($test['capacity']) }}</td>
                                                <td>
                                                    <div style="min-width: 200px;">
                                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                                            <div>
                                                                <span class="availability-badge {{ $status }}" style="background: {{ $statusColor }}15; color: {{ $statusColor }};">
                                                                    {{ ucfirst($status) }}
                                                                </span>
                                                            </div>
                                                            <div style="font-size: 0.875rem;">
                                                                <span style="color: {{ $statusColor }}; font-weight: 600;">{{ number_format($availableCount) }}</span>
                                                                <span style="color: var(--text-secondary);">/ {{ number_format($test['capacity']) }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Progress Bar -->
                                                        <div class="progress-bar">
                                                            <div class="progress-fill" style="width: {{ $availabilityPercentage }}%; background: {{ $statusColor }};"></div>
                                                        </div>
                                                        
                                                        <!-- Stats -->
                                                        <div class="stats-grid">
                                                            <div class="stat-box">
                                                                <div class="stat-label">Available</div>
                                                                <div class="stat-value available">{{ number_format($availableCount) }}</div>
                                                            </div>
                                                            <div class="stat-box">
                                                                <div class="stat-label">Booked</div>
                                                                <div class="stat-value booked">{{ number_format($bookedCount) }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div style="text-align: center; padding: 40px; color: var(--text-secondary);">
                                    <i class="fas fa-clipboard-list" style="font-size: 48px; margin-bottom: 16px;"></i>
                                    <p>No entrance tests configured</p>
                                </div>
                            @endif
                        </div>
                        
                        <div id="editTestsSection" style="display: none;">
                            <div id="testsEditContainer">
                                @foreach($entranceTests as $index => $test)
                                    @php
                                        $bookedCount = $test['booked_count'] ?? $test->booked_count ?? 0;
                                    @endphp
                                    <div class="test-edit-item" style="background: white; padding: 15px; border: 1px solid var(--border); border-radius: var(--radius-md); margin-bottom: 10px;" data-test-id="{{ $test['id'] }}">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                            <strong>Test {{ $index + 1 }}</strong>
                                            <div style="display: flex; gap: 10px; align-items: center;">
                                                <span style="font-size: 0.875rem; color: var(--text-secondary);">
                                                    Booked: <strong>{{ $bookedCount }}</strong> / {{ $test['capacity'] }}
                                                </span>
                                                <button type="button" onclick="removeTestEdit({{ $index }})" style="background: #fef2f2; color: #dc2626; border: 1px solid #dc2626; border-radius: var(--radius-sm); padding: 4px 8px; font-size: 0.75rem;">
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                        <div class="form-grid">
                                            <div class="form-group">
                                                <label class="form-label">Test Name</label>
                                                <input type="text" class="edit-input test-name" value="{{ $test['test_name'] }}" data-index="{{ $index }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Date</label>
                                                <input type="date" class="edit-input test-date" value="{{ $test['test_date'] }}" data-index="{{ $index }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Start Time</label>
                                                <input type="time" class="edit-input test-start-time" value="{{ $test['start_time'] }}" data-index="{{ $index }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">End Time</label>
                                                <input type="time" class="edit-input test-end-time" value="{{ $test['end_time'] }}" data-index="{{ $index }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Duration (mins)</label>
                                                <input type="number" class="edit-input test-duration" value="{{ $test['duration_minutes'] }}" data-index="{{ $index }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Capacity</label>
                                                <input type="number" class="edit-input test-capacity" value="{{ $test['capacity'] }}" data-index="{{ $index }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-primary" onclick="addTest()" style="margin-top: 10px;">
                                <i class="fas fa-plus"></i> Add Test
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Counselling Tab -->
                <div class="tab-pane" id="counselling-tab">
                    <div class="section-title">
                        <span>Counselling Configuration</span>
                        <label class="toggle-switch" id="counsellingToggleContainer">
                            <input type="checkbox" id="counsellingToggle" {{ $config['counselling_enabled'] == 1 ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    
                    <div class="product-section {{ $config['counselling_enabled'] == 0 ? 'hidden' : '' }}" id="counsellingSection">
                        <div class="form-grid">
                            <div class="form-group">
                                <div class="info-label">Counselling Mode</div>
                                <div class="info-value" id="displayCounsellingMode">{{ ucfirst($config['counselling_mode']) }}</div>
                                <select id="editCounsellingMode" class="edit-select" style="display: none;">
                                    <option value="online" {{ $config['counselling_mode'] == 'online' ? 'selected' : '' }}>Online</option>
                                    <option value="offline" {{ $config['counselling_mode'] == 'offline' ? 'selected' : '' }}>Offline</option>
                                    <option value="both" {{ $config['counselling_mode'] == 'both' ? 'selected' : '' }}>Both</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <div class="info-label">Session Duration</div>
                                <div class="info-value" id="displaySessionDuration">{{ $config['counselling_session_duration'] }} minutes</div>
                                <input type="number" id="editSessionDuration" class="edit-input" value="{{ $config['counselling_session_duration'] }}" style="display: none;">
                            </div>
                            
                            <div class="form-group">
                                <div class="info-label">Max per Session</div>
                                <div class="info-value" id="displayMaxCandidates">{{ $config['counselling_max_candidates'] }} students</div>
                                <input type="number" id="editMaxCandidates" class="edit-input" value="{{ $config['counselling_max_candidates'] }}" style="display: none;">
                            </div>
                            
                            <div class="form-group">
                                <div class="info-label">Counselling Start Date</div>
                                <div class="info-value">{{ \Carbon\Carbon::parse($config['counselling_start_date'])->format('d M Y') }}</div>
                            </div>
                            
                            <div class="form-group">
                                <div class="info-label">Counselling End Date</div>
                                <div class="info-value">{{ \Carbon\Carbon::parse($config['counselling_end_date'])->format('d M Y') }}</div>
                            </div>
                        </div>
                        
                        <h4 style="margin: 20px 0 12px 0;">Time Slots</h4>
                        <div id="timeSlotsDisplay">
                            @if($counsellingSlots->count() > 0)
                                <div class="time-slots-grid">
                                    @foreach($counsellingSlots as $slot)
                                        @php
                                            $slotTime = \Carbon\Carbon::parse($slot['start_time'])->format('h:i A');
                                            $endTime = \Carbon\Carbon::parse($slot['end_time'])->format('h:i A');
                                            $slotDate = \Carbon\Carbon::parse($slot['slot_date'])->format('d M Y');
                                            
                                            $bookedCount = $slot['booked_count'] ?? $slot->booked_count ?? 0;
                                            $capacity = $slot['capacity'] ?? 0;
                                            $availableCount = $slot['available_count'] ?? max(0, $capacity - $bookedCount);
                                            $filledPercentage = $capacity > 0 ? round(($bookedCount / $capacity) * 100, 2) : 0;
                                            
                                            $cardClass = 'info-card';
                                            $statusText = 'Available';
                                            $statusColor = '#059669';
                                            
                                            if ($availableCount == 0) {
                                                $cardClass = 'info-card full';
                                                $statusText = 'Full';
                                                $statusColor = '#dc2626';
                                            } elseif ($availableCount < 3) {
                                                $cardClass = 'info-card limited';
                                                $statusText = 'Limited';
                                                $statusColor = '#f59e0b';
                                            }
                                            
                                            $fillColor = '#059669';
                                            if ($filledPercentage >= 90) {
                                                $fillColor = '#dc2626';
                                            } elseif ($filledPercentage >= 70) {
                                                $fillColor = '#f59e0b';
                                            }
                                        @endphp
                                        <div class="{{ $cardClass }}" style="position: relative; overflow: hidden;">
                                            @if($availableCount == 0)
                                                <div class="full-badge">FULL</div>
                                            @endif
                                            
                                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                                                <div>
                                                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 2px;">Date</div>
                                                    <div style="font-weight: 600; color: var(--text-primary);">{{ $slotDate }}</div>
                                                </div>
                                                <div style="background: {{ $statusColor }}15; color: {{ $statusColor }}; padding: 4px 8px; border-radius: 12px; font-size: 0.7rem; font-weight: 600;">
                                                    {{ $statusText }}
                                                </div>
                                            </div>
                                            
                                            <div style="margin-bottom: 12px;">
                                                <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 2px;">Time</div>
                                                <div style="font-weight: 500;">{{ $slotTime }} - {{ $endTime }}</div>
                                            </div>
                                            
                                            <div style="background: #f8fafc; border-radius: var(--radius-sm); padding: 12px;">
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                                    <span style="font-size: 0.85rem; color: var(--text-secondary);">Total Capacity</span>
                                                    <span style="font-weight: 600;">{{ number_format($slot['capacity']) }}</span>
                                                </div>
                                                
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                                    <div>
                                                        <div style="font-size: 0.7rem; color: var(--text-secondary);">Booked</div>
                                                        <div style="font-weight: 600; color: #64748b;">{{ number_format($bookedCount) }}</div>
                                                    </div>
                                                    <div style="text-align: right;">
                                                        <div style="font-size: 0.7rem; color: var(--text-secondary);">Available</div>
                                                        <div style="font-weight: 600; color: {{ $availableCount > 0 ? '#059669' : '#dc2626' }};">{{ number_format($availableCount) }}</div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Progress Bar -->
                                                <div class="progress-bar">
                                                    <div class="progress-fill" style="width: {{ $filledPercentage }}%; background: {{ $fillColor }};"></div>
                                                </div>
                                                
                                                <!-- Compact Stats -->
                                                <div class="stats-grid" style="margin-top: 8px;">
                                                    <div class="stat-box">
                                                        <div class="stat-label">Available</div>
                                                        <div class="stat-value available">{{ number_format($availableCount) }}</div>
                                                    </div>
                                                    <div class="stat-box">
                                                        <div class="stat-label">Booked</div>
                                                        <div class="stat-value booked">{{ number_format($bookedCount) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div style="text-align: center; padding: 40px; color: var(--text-secondary);">
                                    <i class="fas fa-calendar-times" style="font-size: 48px; margin-bottom: 16px;"></i>
                                    <p>No time slots configured</p>
                                </div>
                            @endif
                        </div>
                        
                        <div id="editTimeSlotsSection" style="display: none;">
                            <div id="timeSlotsEditContainer">
                                @foreach($counsellingSlots as $index => $slot)
                                    @php
                                        $bookedCount = $slot['booked_count'] ?? $slot->booked_count ?? 0;
                                    @endphp
                                    <div class="slot-edit-item" style="display: flex; gap: 10px; margin-bottom: 10px; align-items: flex-end;" data-slot-id="{{ $slot['id'] }}">
                                        <div style="flex: 1;">
                                            <label  class="form-label">Date</label> 
                                            <input type="date" class="edit-input slot-date" value="{{ $slot['slot_date'] }}" data-index="{{ $index }}"> 
                                        </div> 
                                        <div style="flex: 1;">
                                            <label class="form-label">Start Time</label>
                                            <input type="time" class="edit-input slot-start-time" value="{{ $slot['start_time'] }}" data-index="{{ $index }}">
                                        </div>
                                        <div style="flex: 1;">
                                            <label class="form-label">End Time</label>
                                            <input type="time" class="edit-input slot-end-time" value="{{ $slot['end_time'] }}" data-index="{{ $index }}">
                                        </div>
                                        <div style="flex: 1;">
                                            <label class="form-label">Capacity</label>
                                            <input type="number" class="edit-input slot-capacity" value="{{ $slot['capacity'] }}" data-index="{{ $index }}" min="1">
                                        </div>
                                        <div style="min-width: 100px; text-align: center;">
                                            <span style="font-size: 0.875rem; color: var(--text-secondary);">
                                                Booked: <strong>{{ $bookedCount }}</strong>
                                            </span> 
                                        </div> 
                                        <button type="button" onclick="removeSlotEdit({{ $index }})" style="background: #fef2f2; color: #dc2626; border: 1px solid #dc2626; border-radius: var(--radius-sm); padding: 8px 12px; height: fit-content;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                @endforeach 
                            </div>
                            <button type="button" class="btn btn-primary" onclick="addTimeSlot()" style="margin-top: 10px;">
                                <i class="fas fa-plus"></i> Add Time Slot
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Onboarding Tab -->
                <div class="tab-pane" id="onboarding-tab">
                    <div class="section-title">
                        <span>Onboarding Configuration</span>
                        <label class="toggle-switch" id="onboardingToggleContainer">
                            <input type="checkbox" id="onboardingToggle" {{ $config['onboarding_enabled'] == 1 ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    
                    <div class="product-section {{ $config['onboarding_enabled'] == 0 ? 'hidden' : '' }}" id="onboardingSection">
                        <div class="form-grid">
                            <div class="form-group">
                                <div class="info-label">Onboarding Start Date</div>
                                <div class="info-value" id="displaySessionStart">{{ $sessionStartDate }}</div>
                                <input type="date" id="editSessionStart" class="edit-input" value="{{ $config['onboarding_start_date'] }}" style="display: none;">
                            </div>
                            
                            <div class="form-group">
                                <div class="info-label">Classes Start Date</div>
                                <div class="info-value" id="displayClassesStart">{{ $classesStartDate }}</div>
                                <input type="date" id="editClassesStart" class="edit-input" value="{{ $config['onboarding_classes_start_date'] }}" style="display: none;">
                            </div>
                        </div> 
                          
                        <h4 style="margin: 20px 0 12px 0;">Fee Structure</h4>
                        <div id="feesDisplay"> 
                            <div class="info-card">
                                <div class="info-label">Admission Fee</div>
                                <div class="info-value">₹{{ number_format($config['onboarding_admission_fee'], 2) }}</div>
                            </div>
                            <div class="info-card">
                                <div class="info-label">Security Deposit</div>
                                <div class="info-value">₹{{ number_format($config['onboarding_security_deposit'], 2) }}</div>
                            </div>
                            @if($config['onboarding_other_charges'] > 0)
                                <div class="info-card">
                                    <div class="info-label">Other Charges</div>
                                    <div class="info-value">₹{{ number_format($config['onboarding_other_charges'], 2) }}</div>
                                </div>
                            @endif 
                        </div>
                        
                        <div id="editFeesSection" style="display: none;">
                            <div id="feesEditContainer">
                                <div class="fee-edit-item" style="display: flex; gap: 10px; margin-bottom: 10px; align-items: flex-end;">
                                    <div style="flex: 2;">
                                        <label class="form-label">Admission Fee</label>
                                        <input type="number" class="edit-input fee-admission" value="{{ $config['onboarding_admission_fee'] }}">
                                    </div>
                                </div> 
                                <div class="fee-edit-item" style="display: flex; gap: 10px; margin-bottom: 10px; align-items: flex-end;">
                                    <div style="flex: 2;">
                                        <label class="form-label">Security Deposit</label>
                                        <input type="number" class="edit-input fee-security" value="{{ $config['onboarding_security_deposit'] }}">
                                    </div> 
                                </div>
                                <div class="fee-edit-item" style="display: flex; gap: 10px; margin-bottom: 10px; align-items: flex-end;">
                                    <div style="flex: 2;">
                                        <label class="form-label">Other Charges</label>
                                        <input type="number" class="edit-input fee-other" value="{{ $config['onboarding_other_charges'] }}">
                                    </div> 
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 20px;">
                            <div class="info-label">Students Onboarded</div>
                            <div class="info-value" style="color: var(--primary);">
                                {{ number_format($onboardedStudents ?? 0) }} students onboarded
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Edit Actions (Hidden by default) -->
        <div class="edit-actions" id="editActions" style="display: none;">
            <button class="btn btn-cancel" onclick="cancelEdit()">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button class="btn btn-save" onclick="saveChanges()">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </div>

    <script>
        // Configuration data from PHP
        const configData = {
            id: '{{ $config["id"] }}',
            name: '{{ addslashes($classNames) }}',
            department: '{{ addslashes($config->department->department) }}',
            session: '{{ $config["academic_year"] }}',
            status: {{ $config["is_active"] }},
            
            // Admission Form
            form: {
                enabled: {{ $config['admission_form_enabled'] }},
                mode: "{{ $config["admission_form_mode"] }}",
                fee: "{{ $config['admission_form_fee_amount'] }}",
                max_forms: {{ $config['admission_form_max'] }},
                start_date: '{{ $config["admission_form_start_date"] }}',
                end_date: '{{ $config["admission_form_end_date"] }}'
            },
            
            // Tests
            tests: [
                @foreach($entranceTests as $test)
                {
                    id: {{ $test['id'] }},
                    name: '{{ addslashes($test['test_name']) }}',
                    date: '{{ $test["test_date"] }}',
                    start_time: '{{ $test["start_time"] }}',
                    end_time: '{{ $test["end_time"] }}',
                    duration: {{ $test['duration_minutes'] }},
                    capacity: {{ $test['capacity'] }},
                    booked_count: {{ $test['booked_count'] ?? $test->booked_count ?? 0 }}
                },
                @endforeach
            ],
            
            // Counselling
            counselling: {
                enabled: {{ $config['counselling_enabled'] }},
                mode: '{{ $config["counselling_mode"] }}',
                session_duration: {{ $config['counselling_session_duration'] }},
                max_candidates: {{ $config['counselling_max_candidates'] }},
                start_date: '{{ $config["counselling_start_date"] }}',
                end_date: '{{ $config["counselling_end_date"] }}',
                slots: [
                    @foreach($counsellingSlots as $slot)
                    {
                        id: {{ $slot['id'] }},
                        date: '{{ $slot["slot_date"] }}',
                        start_time: '{{ $slot["start_time"] }}',
                        end_time: '{{ $slot["end_time"] }}',
                        capacity: {{ $slot['capacity'] }},
                        booked_count: {{ $slot['booked_count'] ?? $slot->booked_count ?? 0 }}
                    },
                    @endforeach
                ]
            },
            
            // Onboarding
            onboarding: {
                enabled: {{ $config['onboarding_enabled'] }},
                session_start: '{{ $config["onboarding_start_date"] }}',
                classes_start: '{{ $config["onboarding_classes_start_date"] }}',
                admission_fee: {{ $config['onboarding_admission_fee'] }},
                security_deposit: {{ $config['onboarding_security_deposit'] }},
                other_charges: {{ $config['onboarding_other_charges'] }}
            }
        };

        // State management
        let isEditMode = false;
        let originalData = JSON.parse(JSON.stringify(configData));
        
        document.addEventListener('DOMContentLoaded', function() {
            // Setup tabs
            setupTabs();
            
            // Setup edit toggle
            document.getElementById('editToggle').addEventListener('click', toggleEditMode);
            
            // Setup product toggles
            setupProductToggles();
        });
        
        function setupTabs() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            
            tabButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const tab = this.getAttribute('data-tab');
                    
                    // Update active tab
                    tabButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Show correct content
                    document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
                    document.getElementById(`${tab}-tab`).classList.add('active');
                });
            });
        }
        
        function setupProductToggles() {
            const toggles = ['formToggle', 'testsToggle', 'counsellingToggle', 'onboardingToggle'];
            
            toggles.forEach(toggleId => {
                const toggle = document.getElementById(toggleId);
                if (toggle) {
                    toggle.addEventListener('change', function() {
                        const sectionId = toggleId.replace('Toggle', 'Section');
                        toggleSection(sectionId, this.checked);
                        
                        // Update config data
                        updateConfigFromToggle(toggleId, this.checked);
                    });
                }
            });
        }
        
        function toggleSection(sectionId, show) {
            const section = document.getElementById(sectionId);
            if (section) {
                section.classList.toggle('hidden', !show);
            }
        }
        
        function updateConfigFromToggle(toggleId, enabled) {
            switch(toggleId) {
                case 'formToggle':
                    configData.form.enabled = enabled;
                    break;
                case 'testsToggle':
                    // If enabling tests but no tests exist, add a default test
                    if (enabled && configData.tests.length === 0) {
                        configData.tests = [{
                            name: 'Aptitude Test',
                            date: new Date().toISOString().split('T')[0],
                            start_time: '09:00',
                            end_time: '11:00',
                            duration: 120,
                            capacity: 50,
                            booked_count: 0
                        }];
                        loadTestsData();
                    } else if (!enabled) {
                        configData.tests = [];
                        loadTestsData();
                    }
                    break;
                case 'counsellingToggle':
                    configData.counselling.enabled = enabled;
                    if (enabled && configData.counselling.slots.length === 0) {
                        const today = new Date().toISOString().split('T')[0];
                        configData.counselling.slots = [{
                            date: today,
                            start_time: '09:00',
                            end_time: '09:45',
                            capacity: 2,
                            booked_count: 0
                        }];
                        loadCounsellingData();
                    }
                    break;
                case 'onboardingToggle':
                    configData.onboarding.enabled = enabled;
                    break;
            }
        }
        
        // Edit Mode Functions
        function toggleEditMode() {
            isEditMode = !isEditMode;
            
            const card = document.getElementById('configCard');
            const editToggle = document.getElementById('editToggle');
            const editActions = document.getElementById('editActions');
            
            if (isEditMode) {
                // Enter edit mode
                card.classList.add('edit-mode');
                editToggle.innerHTML = '<i class="fas fa-times"></i> Cancel Edit';
                editActions.style.display = 'flex';
                
                // Show edit inputs
                showEditInputs();
            } else {
                // Exit edit mode
                card.classList.remove('edit-mode');
                editToggle.innerHTML = '<i class="fas fa-edit"></i> Edit Configuration';
                editActions.style.display = 'none';
                
                // Show display values
                showDisplayValues();
                
                // Reload original data
                Object.assign(configData, JSON.parse(JSON.stringify(originalData)));
                loadConfigurationData();
            }
        }
        
        function showEditInputs() {
            // Basic info
            document.getElementById('displayName').style.display = 'none';
            document.getElementById('editName').style.display = 'block';
            
            document.getElementById('displayDepartment').style.display = 'none';
            document.getElementById('editDepartment').style.display = 'block';
            
            document.getElementById('displaySession').style.display = 'none';
            document.getElementById('editSession').style.display = 'block';
            
            document.getElementById('displayStatus').style.display = 'none';
            document.getElementById('editStatus').style.display = 'block';
            
            // Admission form
            document.getElementById('displayFormMode').style.display = 'none';
            document.getElementById('editFormMode').style.display = 'block';
            
            document.getElementById('displayFormFee').style.display = 'none';
            document.getElementById('editFormFee').style.display = 'block';
            
            document.getElementById('displayMaxForms').style.display = 'none';
            document.getElementById('editMaxForms').style.display = 'block';
            
            document.getElementById('displayFormStart').style.display = 'none';
            document.getElementById('editFormStart').style.display = 'block';
            
            document.getElementById('displayFormEnd').style.display = 'none';
            document.getElementById('editFormEnd').style.display = 'block';
            
            // Tests
            document.getElementById('testsTable').style.display = 'none';
            document.getElementById('editTestsSection').style.display = 'block';
            
            // Counselling
            document.getElementById('displayCounsellingMode').style.display = 'none';
            document.getElementById('editCounsellingMode').style.display = 'block';
            
            document.getElementById('displaySessionDuration').style.display = 'none';
            document.getElementById('editSessionDuration').style.display = 'block';
            
            document.getElementById('displayMaxCandidates').style.display = 'none';
            document.getElementById('editMaxCandidates').style.display = 'block';
            
            document.getElementById('timeSlotsDisplay').style.display = 'none';
            document.getElementById('editTimeSlotsSection').style.display = 'block';
            
            // Onboarding
            document.getElementById('displaySessionStart').style.display = 'none';
            document.getElementById('editSessionStart').style.display = 'block';
            
            document.getElementById('displayClassesStart').style.display = 'none';
            document.getElementById('editClassesStart').style.display = 'block';
            
            document.getElementById('feesDisplay').style.display = 'none';
            document.getElementById('editFeesSection').style.display = 'block';
        }
        
        function showDisplayValues() {
            // Hide all edit inputs
            document.querySelectorAll('.edit-input, .edit-select').forEach(el => {
                el.style.display = 'none';
            });
            
            // Show all display values
            document.querySelectorAll('.info-value').forEach(el => {
                el.style.display = 'block';
            });
            
            // Show display sections
            document.getElementById('testsTable').style.display = 'block';
            document.getElementById('editTestsSection').style.display = 'none';
            
            document.getElementById('timeSlotsDisplay').style.display = 'block';
            document.getElementById('editTimeSlotsSection').style.display = 'none';
            
            document.getElementById('feesDisplay').style.display = 'block';
            document.getElementById('editFeesSection').style.display = 'none';
        }
        
        // Load configuration data for edit
        function loadConfigurationData() {
            // Update basic info display
            document.getElementById('displayName').textContent = configData.name;
            document.getElementById('displayDepartment').textContent = configData.department;
            document.getElementById('displaySession').textContent = configData.session;
            document.getElementById('displayStatus').textContent = configData.status == 1 ? 'Active' : 'Inactive';
            
            // Update config header
            document.getElementById('configTitle').textContent = configData.name;
            document.getElementById('configSubtitle').textContent = `${configData.department} • ${configData.session}`;
            
            // Update admission form data
            if (configData.form.enabled) {
                document.getElementById('displayFormMode').textContent = configData.form.mode.charAt(0).toUpperCase() + configData.form.mode.slice(1);
                document.getElementById('displayFormFee').textContent = `₹${parseFloat(configData.form.fee).toLocaleString()}`;
                document.getElementById('displayMaxForms').textContent = configData.form.max_forms.toLocaleString();
                document.getElementById('displayFormStart').textContent = formatDate(configData.form.start_date);
                document.getElementById('displayFormEnd').textContent = formatDate(configData.form.end_date);
                
                // Set edit values
                document.getElementById('editFormMode').value = configData.form.mode;
                document.getElementById('editFormFee').value = configData.form.fee;
                document.getElementById('editMaxForms').value = configData.form.max_forms;
                document.getElementById('editFormStart').value = configData.form.start_date;
                document.getElementById('editFormEnd').value = configData.form.end_date;
            }
            
            // Set toggle states
            document.getElementById('formToggle').checked = configData.form.enabled;
            document.getElementById('testsToggle').checked = configData.tests.length > 0;
            document.getElementById('counsellingToggle').checked = configData.counselling.enabled;
            document.getElementById('onboardingToggle').checked = configData.onboarding.enabled;
        }
        
        // Load tests data for edit
        function loadTestsData() {
            const editContainer = document.getElementById('testsEditContainer');
            editContainer.innerHTML = '';
            
            configData.tests.forEach((test, index) => {
                editContainer.innerHTML += `
                    <div class="test-edit-item" style="background: white; padding: 15px; border: 1px solid var(--border); border-radius: var(--radius-md); margin-bottom: 10px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <strong>Test ${index + 1}</strong>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <span style="font-size: 0.875rem; color: var(--text-secondary);">
                                    Booked: <strong>${test.booked_count || 0}</strong> / ${test.capacity}
                                </span>
                                <button type="button" onclick="removeTestEdit(${index})" style="background: #fef2f2; color: #dc2626; border: 1px solid #dc2626; border-radius: var(--radius-sm); padding: 4px 8px; font-size: 0.75rem;">
                                    Remove
                                </button>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Test Name</label>
                                <input type="text" class="edit-input test-name" value="${test.name}" data-index="${index}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Date</label>
                                <input type="date" class="edit-input test-date" value="${test.date}" data-index="${index}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Start Time</label>
                                <input type="time" class="edit-input test-start-time" value="${test.start_time}" data-index="${index}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">End Time</label>
                                <input type="time" class="edit-input test-end-time" value="${test.end_time}" data-index="${index}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Duration (mins)</label>
                                <input type="number" class="edit-input test-duration" value="${test.duration}" data-index="${index}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Capacity</label>
                                <input type="number" class="edit-input test-capacity" value="${test.capacity}" data-index="${index}">
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        
        // Load counselling data for edit
        function loadCounsellingData() {
            const editContainer = document.getElementById('timeSlotsEditContainer');
            editContainer.innerHTML = '';
            
            configData.counselling.slots.forEach((slot, index) => {
                editContainer.innerHTML += `
                    <div class="slot-edit-item" style="display: flex; gap: 10px; margin-bottom: 10px; align-items: flex-end;">
                        <div style="flex: 1;">
                            <label class="form-label">Date</label>
                            <input type="date" class="edit-input slot-date" value="${slot.date}" data-index="${index}">
                        </div>
                        <div style="flex: 1;">
                            <label class="form-label">Start Time</label>
                            <input type="time" class="edit-input slot-start-time" value="${slot.start_time}" data-index="${index}">
                        </div>
                        <div style="flex: 1;">
                            <label class="form-label">End Time</label>
                            <input type="time" class="edit-input slot-end-time" value="${slot.end_time}" data-index="${index}">
                        </div>
                        <div style="flex: 1;">
                            <label class="form-label">Capacity</label>
                            <input type="number" class="edit-input slot-capacity" value="${slot.capacity}" data-index="${index}" min="1">
                        </div>
                        <div style="min-width: 100px; text-align: center;">
                            <span style="font-size: 0.875rem; color: var(--text-secondary);">
                                Booked: <strong>${slot.booked_count || 0}</strong>
                            </span>
                        </div>
                        <button type="button" onclick="removeSlotEdit(${index})" style="background: #fef2f2; color: #dc2626; border: 1px solid #dc2626; border-radius: var(--radius-sm); padding: 8px 12px; height: fit-content;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            });
        }
        
        // CRUD Functions for Tests
        function addTest() {
            const newTest = {
                name: 'New Test',
                date: new Date().toISOString().split('T')[0],
                start_time: '09:00',
                end_time: '10:00',
                duration: 60,
                capacity: 50,
                booked_count: 0
            };
            
            configData.tests.push(newTest);
            loadTestsData();
        }
        
        function removeTestEdit(index) {
            if (configData.tests.length > 1) {
                configData.tests.splice(index, 1);
                loadTestsData();
            } else {
                alert('At least one test must remain');
            }
        }
        
        // CRUD Functions for Time Slots
        function addTimeSlot() {
            const today = new Date().toISOString().split('T')[0];
            const newSlot = {
                date: today,
                start_time: '09:00',
                end_time: '09:45',
                capacity: 2,
                booked_count: 0
            };
            
            configData.counselling.slots.push(newSlot);
            loadCounsellingData();
        }
        
        function removeSlotEdit(index) {
            if (configData.counselling.slots.length > 1) {
                configData.counselling.slots.splice(index, 1);
                loadCounsellingData();
            } else {
                alert('At least one time slot must remain');
            }
        }
        
        // Save and Cancel Functions
        function saveChanges() {
            // Collect updated data
            configData.name = document.getElementById('editName').value;
            configData.department = document.getElementById('editDepartment').value;
            configData.session = document.getElementById('editSession').value;
            configData.status = document.getElementById('editStatus').value;
            
            // Update admission form
            configData.form.mode = document.getElementById('editFormMode').value;
            configData.form.fee = parseFloat(document.getElementById('editFormFee').value) || 0;
            configData.form.max_forms = parseInt(document.getElementById('editMaxForms').value) || 0;
            configData.form.start_date = document.getElementById('editFormStart').value;
            configData.form.end_date = document.getElementById('editFormEnd').value;
            configData.form.enabled = document.getElementById('formToggle').checked;
            
            // Update tests (preserve booked counts)
            const updatedTests = [];
            document.querySelectorAll('.test-edit-item').forEach((item, index) => {
                const oldTest = configData.tests[index] || { booked_count: 0 };
                updatedTests.push({
                    id: oldTest.id,
                    name: item.querySelector('.test-name').value,
                    date: item.querySelector('.test-date').value,
                    start_time: item.querySelector('.test-start-time').value,
                    end_time: item.querySelector('.test-end-time').value,
                    duration: parseInt(item.querySelector('.test-duration').value) || 0,
                    capacity: parseInt(item.querySelector('.test-capacity').value) || 0,
                    booked_count: oldTest.booked_count || 0
                });
            });
            configData.tests = updatedTests;
            
            // Update counselling
            configData.counselling.mode = document.getElementById('editCounsellingMode').value;
            configData.counselling.session_duration = parseInt(document.getElementById('editSessionDuration').value) || 0;
            configData.counselling.max_candidates = parseInt(document.getElementById('editMaxCandidates').value) || 0;
            configData.counselling.enabled = document.getElementById('counsellingToggle').checked;
            
            // Update time slots (preserve booked counts)
            const updatedSlots = [];
            document.querySelectorAll('.slot-edit-item').forEach((item, index) => {
                const oldSlot = configData.counselling.slots[index] || { booked_count: 0 };
                updatedSlots.push({
                    id: oldSlot.id,
                    date: item.querySelector('.slot-date').value,
                    start_time: item.querySelector('.slot-start-time').value,
                    end_time: item.querySelector('.slot-end-time').value,
                    capacity: parseInt(item.querySelector('.slot-capacity').value) || 0,
                    booked_count: oldSlot.booked_count || 0
                });
            });
            configData.counselling.slots = updatedSlots;
            
            // Update onboarding
            configData.onboarding.session_start = document.getElementById('editSessionStart').value;
            configData.onboarding.classes_start = document.getElementById('editClassesStart').value;
            configData.onboarding.admission_fee = parseFloat(document.querySelector('.fee-admission').value) || 0;
            configData.onboarding.security_deposit = parseFloat(document.querySelector('.fee-security').value) || 0;
            configData.onboarding.other_charges = parseFloat(document.querySelector('.fee-other').value) || 0;
            configData.onboarding.enabled = document.getElementById('onboardingToggle').checked;
            
            // Save to original data
            originalData = JSON.parse(JSON.stringify(configData));
            
            // Exit edit mode
            toggleEditMode();
            
            // Reload display
            loadConfigurationData();
            
            // Show success message
            alert('Configuration updated successfully!');
            
            // In real app, send data to server
            saveToServer();
        }
        
        function cancelEdit() {
            toggleEditMode();
        }
        
        function saveToServer() {
            // Prepare data for server
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('name', configData.name);
            formData.append('department', configData.department);
            formData.append('academic_year', configData.session);
            formData.append('is_active', configData.status);
            
            // Admission form
            formData.append('admission_form_enabled', configData.form.enabled ? 1 : 0);
            formData.append('admission_form_mode', configData.form.mode);
            formData.append('admission_form_fee_amount', configData.form.fee);
            formData.append('admission_form_max', configData.form.max_forms);
            formData.append('admission_form_start_date', configData.form.start_date);
            formData.append('admission_form_end_date', configData.form.end_date);
            
            // Tests
            formData.append('entrance_tests_enabled', configData.tests.length > 0 ? 1 : 0);
            formData.append('entrance_tests', JSON.stringify(configData.tests));
            
            // Counselling
            formData.append('counselling_enabled', configData.counselling.enabled ? 1 : 0);
            formData.append('counselling_mode', configData.counselling.mode);
            formData.append('counselling_session_duration', configData.counselling.session_duration);
            formData.append('counselling_max_candidates', configData.counselling.max_candidates);
            formData.append('counselling_slots', JSON.stringify(configData.counselling.slots));
            
            // Onboarding
            formData.append('onboarding_enabled', configData.onboarding.enabled ? 1 : 0);
            formData.append('onboarding_start_date', configData.onboarding.session_start);
            formData.append('onboarding_classes_start_date', configData.onboarding.classes_start);
            formData.append('onboarding_admission_fee', configData.onboarding.admission_fee);
            formData.append('onboarding_security_deposit', configData.onboarding.security_deposit);
            formData.append('onboarding_other_charges', configData.onboarding.other_charges);
            
            // AJAX call to save configuration
            fetch(`/configurations/${configData.id}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Configuration saved:', data);
                if (data.success) {
                    // Reload page or update data
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error saving configuration:', error);
                alert('Error saving configuration. Please try again.');
            });
        }
        
        // Utility Functions
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
        }
    </script>
@endsection
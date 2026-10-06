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
            --success-light: #d1fae5;
            --warning: #d97706;
            --warning-light: #fef3c7;
            --danger: #dc2626;
            --danger-light: #fee2e2;
            --text-primary: #334155;
            --text-secondary: #64748b;
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding: 0 0 16px 0;
            border-bottom: 1px solid var(--border);
        }
        
        .page-title {
            color: var(--secondary);
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s ease;
            border: none;
            text-decoration: none;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: var(--shadow-sm);
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }
        
        .config-table {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            overflow-x: auto;
            overflow-y: hidden;
            border: 1px solid var(--border);
        }
        
        .config-table table {
            width: max-content;
            min-width: 100%;
            border-collapse: collapse;
        }
        
        .config-table th {
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            color: var(--secondary);
            border-bottom: 1px solid var(--border);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: var(--light-bg);
        }
        
        .config-table td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        
        .config-table tr:hover {
            background: #f8fafc;
        }
        
        .status-badge {
            padding: 4px 12px;  
            border-radius: 20px; 
            font-size: 0.75rem; 
            font-weight: 600;
            display: inline-block;
            text-transform: uppercase; 
            letter-spacing: 0.05em;
        }
        
        .badge-published {
            background: var(--success-light);
            color: var(--success);
            border: 1px solid var(--success);
        }
        
        .badge-draft {
            background: var(--warning-light);
            color: var(--warning);
            border: 1px solid var(--warning);
        }
        
        .status-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .status-icon {
            font-size: 1.1rem;
        }
        
        .status-icon.published {
            color: var(--success);
        }
        
        .status-icon.draft {
            color: var(--warning);
        }
        
        .action-btns {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .btn-view {
            background: var(--primary);
            color: white !important;
        }
        
        .btn-edit {
            background: #fbbf24;
            color: #92400e;
        }
        
        .btn-delete {
            background: #fef2f2;
            color: var(--danger);
            border: 1px solid var(--danger);
        }
        
        .product-indicators {
            display: flex;
            align-items: center;
            /* gap: 10px; */
            margin-top: 4px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 12px 12px;
        }
        .product-indicator {
            width: 76px;
            padding: 8px 6px;
            border-radius: 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            background: transparent;
            color: #334155;
            text-align: center;
        }
        .product-indicator-title {
            display: block;
            font-size: 0.66rem;
            font-weight: 700;
            text-transform: none;
            letter-spacing: 0.02em;
            color: #475569;
            line-height: 1.2;
            white-space: normal;
        }
        .product-icon-box {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e2e8f0;
            color: #475569;
            font-size: 0.95rem;
        }
        .product-icon-box.enabled {
            color: #fff;
        }
        .product-indicator.indicator-form .product-icon-box.enabled {
            background: #3b82f6;
        }
        .product-indicator.indicator-tests .product-icon-box.enabled {
            background: #f59e0b;
        }
        .product-indicator.indicator-counselling .product-icon-box.enabled {
            background: #10b981;
        }
        .product-indicator.indicator-onboarding .product-icon-box.enabled {
            background: #8b5cf6;
        }
        .product-indicator-status {
            display: none;
        }
        .step-arrow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            min-width: 18px;
            color: #94a3b8;
            font-size: 0.9rem;
        }
        .step-arrow::before {
            content: '→';
        }
        .indicator-form { color: #3b82f6; }
        .indicator-tests { color: #f59e0b; }
        .indicator-counselling { color: #10b981; }
        .indicator-onboarding { color: #8b5cf6; }
        
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: var(--text-secondary);
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            color: #cbd5e1;
        }
        
        @media (max-width: 768px) {
            .container { padding: 16px; }
            .header { flex-direction: column; gap: 16px; }
            .action-btns { flex-wrap: wrap; }
        }
    </style>

    <div class="container">
        <div class="header">
            <h1 class="page-title">
                <i class="fas fa-sliders-h"></i>  
                Admission Configurations
            </h1>
            <a href="/admission-process/configuration" class="btn btn-primary"> 
                <i class="fas fa-plus-circle"></i> 
                New Configuration 
            </a>
        </div>
        
        <!-- Status Legend -->
        <div style="background: #f0fdf4; border-left: 4px solid var(--success); border-radius: 8px; padding: 16px; margin-bottom: 24px; display: flex; gap: 24px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="background: var(--success-light); padding: 8px 16px; border-radius: 20px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-check-circle" style="color: var(--success); font-size: 1.2rem;"></i>
                    <span style="color: var(--success); font-weight: 600;">Published</span> 
                </div>
                <span style="color: var(--text-secondary); font-size: 0.9rem;">Configuration is active and live</span>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;"> 
                <div style="background: var(--warning-light); padding: 8px 16px; border-radius: 20px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-edit" style="color: var(--warning); font-size: 1.2rem;"></i>
                    <span style="color: var(--warning); font-weight: 600;">Draft</span>
                </div>
                <span style="color: var(--text-secondary); font-size: 0.9rem;">Work in progress - not yet published</span> 
            </div>
        </div>
        
        <div class="config-table"> 
            <table>
                <thead>
                    <tr>
                        <th>Config ID</th>  
                        <th>Session</th>
                        <th>Department</th>  
                        <th>Class Name</th>    
                        <th>Steps</th>  
                        <th>Status</th> 
                        <th>Add On</th> 
                        <th>Actions</th>  
                    </tr>
                </thead>
                <tbody>
                    @foreach($configurations as $config)
                    <tr>
                        <td><strong>{{ $config['admissionprocess_confun_id'] }}</strong></td>
                        <td>{{ $config['academic_year'] }}</td>
                        <td>{{ data_get($config, 'department.department') ?? '-' }}</td>
                        <td>
                            @php
                                $classes = $config['product_id'] ?? [];
                        
                                if (!is_array($classes)) {
                                    $classes = json_decode($classes, true) ?: [];
                                }
                            @endphp
                        
                            @forelse($classes as $class)
                                <span>{{ $class['name'] ?? '-' }}</span>
                                @if(!$loop->last), @endif
                            @empty
                                <span>-</span>
                            @endforelse
                        </td>
                        <td>
                            @php
                                $steps = [];
                                if(!empty($config['admission_form_enabled']) && !empty($config['admission_form_start_date']) && !empty($config['admission_form_end_date'])) {
                                    $steps[] = ['title' => 'Application', 'icon' => 'fa-file-alt', 'class' => 'indicator-form'];
                                }
                                if(!empty($config['entrance_tests_enabled']) && !empty($config['entrance_tests']) && !empty($config['entrance_tests_mode'])) {
                                    $steps[] = ['title' => 'Entrance Test', 'icon' => 'fa-clipboard-check', 'class' => 'indicator-tests'];
                                }
                                if(!empty($config['counselling_enabled']) && !empty($config['counselling_start_date']) && !empty($config['counselling_mode'])) {
                                    $steps[] = ['title' => 'Counselling', 'icon' => 'fa-comments', 'class' => 'indicator-counselling'];
                                }
                                if(!empty($config['onboarding_enabled']) && !empty($config['onboarding_start_date']) && !empty($config['onboarding_classes_start_date'])) {
                                    $steps[] = ['title' => 'Onboarding', 'icon' => 'fa-user-graduate', 'class' => 'indicator-onboarding'];
                                }
                            @endphp
                            <div class="product-indicators">
                                @if(empty($steps))
                                    <span style="color:#64748b; font-size:0.85rem;">No configured steps</span>
                                @else
                                    @foreach($steps as $index => $step)
                                        <div class="product-indicator {{ $step['class'] }}" data-tooltip="{{ $step['title'] }}">
                                            <span class="product-indicator-title">{{ $step['title'] }}</span>
                                            <div class="product-icon-box enabled">
                                                <i class="fas {{ $step['icon'] }}"></i>
                                            </div>
                                        </div>
                                        @if($index < count($steps) - 1)
                                            <div class="step-arrow"></div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($config['is_active'] == 1)
                                <span class="status-badge badge-published">
                                    <i class="fas fa-star"></i> Published
                                </span>
                            @else
                                <a href="{{ route('admission-process.config', ['config_id' => $config['id']]) }}" class="status-badge badge-draft" title="Edit Draft">
                                    <i class="fas fa-pen-to-square"></i> Draft
                                </a>
                            @endif
                        </td>
                        <td>{{ $config['created_at'] }}</td>
                        <td>
                            <div class="action-btns">
                                <a href="{{ route('config.details', $config['id']) }}" class="action-btn btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <button class="action-btn btn-delete" onclick="deleteConfig('{{ $config['id'] }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function deleteConfig(id) {
            if(confirm('Are you sure you want to delete this configuration?')) {
                fetch(`/configurations/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload();
                    }
                });
            }
        }
    </script>
@endsection
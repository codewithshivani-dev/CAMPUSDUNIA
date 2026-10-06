@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e2e8f0;
        animation: fadeIn 0.5s ease;
    }
    
    .page-title {
        font-size: 24px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .add-btn {
        padding: 10px 20px;
        background-color: #007BFF;
        border: none;
        color: white;
        cursor: pointer;
        border-radius: 5px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
    }
    
    .add-btn:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }
    
    /* Filter container - Updated styles */
    .filter-container {
        background: #fff;
        border-radius: 8px;
        padding: 6px 14px;
        margin-bottom: 20px;
        border: 1px solid #e2e8f0;
        animation: slideUp 0.3s ease;
    }

    .filter-form {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .filter-grid {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        flex: 1;
    }

    .filter-group {
        position: relative;
        min-width: 200px;
    }

    .filter-group .bi {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        z-index: 1;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 8px 10px 8px 35px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 14px;
        background: #fff;
        transition: all 0.2s;
        height: 40px;
        box-sizing: border-box;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        transform: translateY(-1px);
    }

    .filter-group input:hover,
    .filter-group select:hover {
        border-color: #cbd5e1;
    }

    .filter-actions {
        display: flex;
        gap: 12px;
        margin-bottom: 4px;
    }

    .btn-filter {
        padding: 8px 20px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        height: 40px;
        box-sizing: border-box;
        text-decoration: none;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        text-decoration: none;
    }

    .btn-filter:active {
        transform: translateY(0);
    }

    .btn-filter-primary {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .btn-filter-primary:hover {
        background: #2563eb;
        color: white;
    }

    .btn-filter-secondary {
        background: #f1f5f9;
        color: #475569;
        border-color: #e2e8f0;
    }

    .btn-filter-secondary:hover {
        background: #e2e8f0;
        color: #475569;
        text-decoration: none;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive adjustments for filters */
    @media (max-width: 1024px) {
        .filter-form {
            flex-direction: column;
            gap: 15px;
        }
        
        .filter-grid {
            width: 100%;
        }
        
        .filter-group {
            min-width: calc(50% - 8px);
        }
        
        .filter-actions {
            width: 100%;
            justify-content: flex-end;
            margin-top: 10px;
        }
    }

    @media (max-width: 768px) {
        .filter-group {
            min-width: 100%;
        }
        
        .filter-actions {
            flex-wrap: wrap;
            justify-content: center;
        }
    }
    
    /* Bulk Actions */
    .bulk-actions-container {
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        animation: slideDown 0.3s ease;
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .bulk-actions-container.active {
        display: flex;
    }
    
    .selected-count {
        font-weight: 500;
        color: #475569;
        margin-right: auto;
        font-size: 14px;
    }
    
    .bulk-action-btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        color: #475569;
        transition: all 0.2s;
        font-size: 14px;
        display: flex;
        align-items: center;
        border: 1px solid transparent;
        margin-right: 5px;
    }
    
    .bulk-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .bulk-action-btn:active {
        transform: translateY(0);
    }
    
    .bulk-action-btn.download {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    
    .bulk-action-btn.download:hover {
        background: #bbf7d0;
    }
    
    .bulk-action-btn.delete {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    
    .bulk-action-btn.delete:hover {
        background: #fecaca;
    }
    
    .bulk-action-btn.clear {
        background: transparent;
        color: #64748b;
        border: 1px solid #cbd5e1;
    }
    
    .bulk-action-btn.clear:hover {
        background: #f1f5f9;
    }
    
    .bulk-action-btn i {
        margin-right: 5px;
    }
    
    /* ERP Table Styles */
    .erp-table {
        width: 100%;
        background: #fff;
        border-collapse: collapse;
    }

    .erp-table thead {
        background: var(--primary-gradient);
        border-bottom: 2px solid #e2e8f0;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .erp-table th {
        padding: 15px 8px;
        font-weight: 600;
        color: white;
        text-align: left;
        font-size: 14px;
        border-bottom: 1px solid #e2e8f0;
        cursor: pointer;
        user-select: none;
        transition: background-color 0.2s;
        position: relative;
    }
    
    .erp-table th.sortable {
        padding-right: 30px;
    }
    
    .sort-icons {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .sort-icon {
        color: #cbd5e1;
        font-size: 12px;
        line-height: 1;
    }
    
    .sort-icon.active {
        color: #3b82f6;
    }
    
    .erp-table td {
        padding: 15px 8px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 14px;
        vertical-align: middle;
    }
    
    .erp-table tbody tr {
        transition: background-color 0.2s;
    }
    
    .erp-table tbody tr:hover {
        background-color: #f8fafc;
    }
    
    .erp-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Checkbox styling */
    .select-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border-radius: 4px;
        border: 2px solid #cbd5e1;
        transition: all 0.2s;
        margin: 0;
    }
    
    .select-checkbox:hover {
        border-color: #3b82f6;
    }
    
    .select-checkbox:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
    }
    
    /* Status Badges */
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }
    
    .badge-primary {
        background: #dbeafe;
        color: #1d4ed8;
        border: 1px solid #93c5fd;
    }
    
    .badge-info {
        font-size: 12px;
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }
    
    .badge-success {
        font-size: 12px;
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    
    .badge-warning {
        font-size: 12px;
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    
    .badge-danger {
        font-size: 12px;
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    .badge-secondary {
        font-size: 12px;
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #d1d5db;
    }
    
    .badge-dark {
        font-size: 12px;
        background: #1f2937;
        color: #f9fafb;
        border: 1px solid #111827;
    }
    
    /* Action Buttons */
    .table-actions {
        display: flex;
        gap: 5px;
        justify-content: center;
    }
    
    .action-btn {
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 4px;
        justify-content: center;
        /* min-width: 60px; */
        text-decoration: none;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        text-decoration: none;
    }
    
    .action-btn:active {
        transform: translateY(0);
    }
    
    .action-btn-view {
        background: #e0f2fe;
        color: #0369a1;
        border-color: #bae6fd;
    }
    
    .action-btn-edit {
        background: #fef2c8;
        color: #92400e;
        border-color: #fde68a;
    }
    
    .action-btn-delete {
        background: #fee2e2;
        color: #dc2626;
        border-color: #fecaca;
    }
    
    .action-btn-view:hover {
        background: #bae6fd;
        color: #0369a1;
    }
    
    .action-btn-edit:hover {
        background: #fde68a;
        color: #92400e;
    }
    
    .action-btn-delete:hover {
        background: #fecaca;
        color: #dc2626;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #64748b;
    }
    
    .empty-state-icon {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 16px;
    }
    
    /* Loading Animation */
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
        }
        
        .filter-group {
            min-width: 100%;
        }
        
        .bulk-actions-container {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }
        
        .bulk-actions-container .d-flex {
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .table-actions {
            flex-direction: column;
            gap: 4px;
        }
        
        .action-btn {
            width: 100%;
        }
    }
    .table-responsive{
        overflow-x: hidden;
    }
</style>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            Employee Duties Management
        </h1>
        <button class="add-btn" onclick="window.location.href='{{ route('institute.duties.create') }}'">
            <i class="bi bi-plus-circle"></i>
            Assign New Duty
        </button>
    </div>

    {{-- Messages --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif


    {{-- Filters --}}
    <div class="filter-container">
        <form method="GET" class="filter-form" id="filterForm" action="{{ route('institute.duties.index') }}">
            <div class="filter-grid">
                <div class="filter-group">
                    <i class="bi bi-person"></i>
                    <input list="employeeNamesList" name="employee_id" class="filter-input"
                        value="{{ request('') }}" placeholder="All Employees">
                    <datalist id="employeeNamesList">
                        @foreach($employees as $employee)
                        <option value="{{ $employee->name }}">
                            {{ $employee->name }}
                        </option>
                        @endforeach
                    </datalist>
                </div>

                <div class="filter-group d-none">
                    <i class="bi bi-card-checklist"></i>
                    <select class="filter-input" id="duty_type" name="duty_type">
                        <option value="">All Types</option>
                        @foreach($dutyTypes as $key => $type)
                            <option value="{{ $key }}" {{ request('duty_type') == $key ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <i class="bi bi-geo-alt"></i>
                    <input type="text" class="filter-input" id="location" name="location" 
                        value="{{ request('location') }}" placeholder="Enter location">
                </div>

                <div class="filter-group">
                    <i class="bi bi-calendar"></i>
                    <input type="text" class="filter-input" id="date_range" name="date_range" 
                        value="{{ request('date_range') }}" placeholder="Select date range">
                </div>

                <div class="filter-group d-none">
                    <i class="bi bi-circle-fill"></i>
                    <select class="filter-input" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter btn-filter-primary">
                    <i class="bi bi-funnel"></i>
                    Apply Filters
                </button>
                <a href="{{ route('institute.duties.index') }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-x-circle"></i>
                    Reset Filters
                </a>
            </div>
            
            <!-- Hidden sort inputs -->
            <input type="hidden" name="sort_by" id="sortBy" value="{{ request('sort_by', 'created_at') }}">
            <input type="hidden" name="sort_order" id="sortOrder" value="{{ request('sort_order', 'desc') }}">
        </form>
    </div>

    {{-- Bulk Actions Container --}}
    <div class="bulk-actions-container" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 duties selected</div>
        <div class="d-flex flex-wrap">
            <button class="bulk-action-btn download" onclick="bulkAction('download')">
                <i class="bi bi-download"></i>
                Download
            </button>
            <button class="bulk-action-btn delete d-none" onclick="bulkAction('bulk_delete')">
                <i class="bi bi-trash"></i>
                Bulk Delete
            </button>
            <button class="bulk-action-btn clear mr-0" onclick="clearSelection()">
                <i class="bi bi-x-lg"></i>
                Clear Selection
            </button>
        </div>
    </div>
    
    <div>
        {{-- Duties Table --}}
        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
            <table class="erp-table" id="dutiesTable">
                <thead>
                    <tr>
                        <th class="sticky-checkbox" width="40">
                            <input type="checkbox" id="selectAll" class="select-checkbox">
                        </th>
                        <th class="sticky-main sortable" onclick="sortTable('employee')">
                            Employee
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'employee' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'employee' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('date')">
                            Date/Time
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'date' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'date' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('frequency')">
                            Frequency
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'frequency' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'frequency' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('location')">
                            Location
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'location' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'location' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('priority')">
                            Priority
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'priority' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'priority' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('status')">
                            Status
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'status' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'status' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($duties as $duty)
                    <tr>
                        <td class="sticky-checkbox">
                            <input type="checkbox" class="duty-checkbox select-checkbox" value="{{ $duty->id }}">
                        </td>
                        <td class="sticky-main">
                            <div class="fw-medium">{{ $duty->employee->name ?? 'N/A' }}</div>
                            <small class="badge badge-primary">{{ $dutyTypes[$duty->duty_type] ?? $duty->duty_type }}</small>
                        </td>
                        <td>
                            @if ($duty->frequency === 'once')
                                <div class="">
                                    <div>{{ \Carbon\Carbon::parse($duty->date)->format('d/m/Y') }}</div>
                                    <div class=" small text-muted">
                                        {{ \Carbon\Carbon::parse($duty->start_time)->format('h:i A') }}
                                        -
                                        {{ \Carbon\Carbon::parse($duty->end_time)->format('h:i A') }}
                                    </div>
                                </div>
                            @else
                                <div class="">
                                    <div>
                                        {{ \Carbon\Carbon::parse($duty->from_date)->format('d/m/Y') }}
                                        -
                                        {{ \Carbon\Carbon::parse($duty->to_date)->format('d/m/Y') }}
                                    </div>
                                    <div class="small text-muted">
                                        {{ \Carbon\Carbon::parse($duty->start_time)->format('h:i A') }}
                                        -
                                        {{ \Carbon\Carbon::parse($duty->end_time)->format('h:i A') }}
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="">{{ ucfirst($duty->frequency) }}</span>
                        </td>
                        <td>{{ $duty->location ?? 'N/A' }}</td>
                        <td>
                            
                            <span class=" ">
                                {{ ucfirst($duty->priority) }}
                            </span>
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'assigned' => 'primary',
                                    'in_progress' => 'info',
                                    'completed' => 'success',
                                    'cancelled' => 'secondary'
                                ];
                            @endphp
                            <span class="badge badge-{{ $statusColors[$duty->status] ?? 'secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $duty->status)) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="table-actions">
                                <a href="{{ route('institute.duties.show', $duty->id) }}" class="action-btn action-btn-view d-block" title="View">
                                    <div>
                                        <i class="bi bi-eye"></i>
                                    </div>
                                    <div>
                                        <span class="small">View</span>
                                    </div>
                                </a>
                                <a href="{{ route('institute.duties.edit', $duty->id) }}" class="action-btn action-btn-edit d-block" title="Edit">
                                    <div>
                                        <i class="bi bi-pencil"></i>
                                    </div>
                                    <div>
                                        <span class="small">Edit</span>
                                    </div>
    
                                </a>
                                <button class="action-btn action-btn-delete delete-duty  d-none" data-id="{{ $duty->id }}" title="Delete">
                                    <div>
                                        <i class="bi bi-trash"></i>
                                    </div>
                                    <div>
                                        <span class="small">Delete</span>
                                    </div>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-calendar-x"></i>
                                </div>
                                <h4>No Duties Assigned</h4>
                                <p>No duties have been assigned yet.</p>
                                <button class="add-btn" onclick="window.location.href='{{ route('institute.duties.create') }}'">
                                    <i class="bi bi-plus-circle"></i>
                                    Assign Your First Duty
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Floating Horizontal Scrollbar -->
        <div class="table-scroll-top" id="tableScrollTop">
            <div class="table-scroll-inner"></div>
        </div>
        {{-- Pagination --}}
        @if($duties->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-sm text-gray-600">
                Showing {{ $duties->firstItem() ?? 0 }} to {{ $duties->lastItem() ?? 0 }} of {{ $duties->total() }} duties
            </div>
            <div>
                {{ $duties->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>    
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this duty assignment? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>-->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2
        $('.select2').select2({
            width: '100%'
        });

        // Initialize date range picker
        flatpickr("#date_range", {
            mode: "range",
            dateFormat: "Y-m-d",
            placeholder: "Select date range"
        });

        // Bulk Selection Management
        const selectAllCheckbox = document.getElementById('selectAll');
        const bulkActionsContainer = document.getElementById('bulkActionsContainer');
        const selectedCountElement = document.getElementById('selectedCount');
        
        function updateSelectionUI() {
            const selectedCount = document.querySelectorAll('.duty-checkbox:checked').length;
            
            if (selectedCount > 0) {
                bulkActionsContainer.classList.add('active');
                selectedCountElement.textContent = selectedCount + ' duty(s) selected';
                
                // Update select all checkbox state
                const totalDuties = document.querySelectorAll('.duty-checkbox').length;
                selectAllCheckbox.checked = selectedCount === totalDuties;
                selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < totalDuties;
            } else {
                bulkActionsContainer.classList.remove('active');
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
        }

        // Add event listeners to checkboxes
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('duty-checkbox')) {
                updateSelectionUI();
            }
        });

        // Select All functionality
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.duty-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectionUI();
        });

        // Bulk Action Functions
        window.clearSelection = function() {
            document.querySelectorAll('.duty-checkbox:checked').forEach(checkbox => {
                checkbox.checked = false;
            });
            document.getElementById('selectAll').checked = false;
            document.getElementById('bulkActionsContainer').classList.remove('active');
        }

        window.bulkAction = function(action) {
            const selectedDuties = Array.from(document.querySelectorAll('.duty-checkbox:checked'))
                .map(checkbox => checkbox.value);
            
            if (selectedDuties.length === 0) {
                alert('Please select at least one duty.');
                return;
            }
            
            switch(action) {
                case 'download':
                    if (confirm(`Download data for ${selectedDuties.length} duty(s)?`)) {
                        // Show loading state
                        const btn = event.target.closest('button');
                        const originalHTML = btn.innerHTML;
                        btn.innerHTML = '<span class="loading-spinner"></span> Processing...';
                        btn.disabled = true;
                        
                        // Simulate download
                        setTimeout(() => {
                            btn.innerHTML = originalHTML;
                            btn.disabled = false;
                            alert('Download started for ' + selectedDuties.length + ' duties');
                        }, 1000);
                    }
                    break;
                    
                case 'bulk_delete':
                    if (confirm(`Are you sure you want to delete ${selectedDuties.length} duty(s)? This action cannot be undone.`)) {
                        // Show loading state
                        const btn = event.target.closest('button');
                        const originalHTML = btn.innerHTML;
                        btn.innerHTML = '<span class="loading-spinner"></span> Deleting...';
                        btn.disabled = true;
                        
                        // Submit bulk delete request
                        fetch('/admin/duties/bulk-delete', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ ids: selectedDuties })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Error deleting duties: ' + data.message);
                                btn.innerHTML = originalHTML;
                                btn.disabled = false;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error deleting duties');
                            btn.innerHTML = originalHTML;
                            btn.disabled = false;
                        });
                    }
                    break;
                    
                default:
                    alert(`${action} action triggered for ${selectedDuties.length} duties`);
            }
        }

        // Sorting Functionality
        function sortTable(column) {
            const currentSortBy = document.getElementById('sortBy').value;
            const currentSortOrder = document.getElementById('sortOrder').value;
            
            let newSortOrder = 'asc';
            
            if (currentSortBy === column) {
                newSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
            }
            
            document.getElementById('sortBy').value = column;
            document.getElementById('sortOrder').value = newSortOrder;
            
            // Submit the form
            document.getElementById('filterForm').submit();
        }

        // Delete duty
        document.querySelectorAll('.delete-duty').forEach(button => {
            button.addEventListener('click', function() {
                const dutyId = this.getAttribute('data-id');
                const deleteForm = document.getElementById('deleteForm');
                deleteForm.action = '/admin/duties/' + dutyId;
                
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                deleteModal.show();
            });
        });

        // Form submission with loading state
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('.btn-filter-primary');
            const originalHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="loading-spinner"></span> Applying...';
            submitBtn.disabled = true;
            
            // Re-enable button after 2 seconds in case of error
            setTimeout(() => {
                submitBtn.innerHTML = originalHTML;
                submitBtn.disabled = false;
            }, 2000);
        });

        // Auto-submit filter on change for select elements
        ['employee_id', 'duty_type', 'status'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }
        });
    });
</script>

@endsection
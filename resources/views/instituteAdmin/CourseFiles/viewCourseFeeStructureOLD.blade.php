@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Fee Structure Management</title>
<style>
    /* ERP Table Styles */
    .erp-table {
        width: 100%;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        margin-bottom: 20px;
    }
    
    .erp-table thead {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .erp-table th {
        padding: 12px 16px;
        font-weight: 600;
        color: #475569;
        text-align: left;
        font-size: 14px;
        border-bottom: 1px solid #e2e8f0;
        cursor: pointer;
        user-select: none;
        transition: background-color 0.2s;
        position: relative;
    }
    
    .erp-table th:hover {
        background-color: #f1f5f9;
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
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 14px;
        vertical-align: middle;
    }
    
    .erp-table tbody tr {
        transition: background-color 0.2s, transform 0.2s;
    }
    
    .erp-table tbody tr:hover {
        background-color: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .erp-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Filter container */
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
        gap: 16px;
    }

    .filter-group {
        position: relative;
        flex: 1;
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
    
    .filter-grid {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        flex: 1;
    }
    
    .filter-actions {
        display: flex;
        gap: 12px;
        align-items: center;
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
        white-space: nowrap;
    }

    .btn-filter-secondary:hover {
        text-decoration: none;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
    }
    
    /* Header */
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
    
    .page-title i {
        color: #3b82f6;
    }
    
    /* Action Buttons */
    .action-btn {
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
    
    .action-btn-secondary {
        background: #f1f5f9;
        color: #475569;
        border-color: #e2e8f0;
    }
    
    .action-btn-view:hover {
        background: #bae6fd;
    }
    
    .action-btn-edit:hover {
        background: #fde68a;
    }
    
    .action-btn-secondary:hover {
        background: #e2e8f0;
    }
    .custom-gap{
        gap: 5px;
    }
    
    /* Status Badges */
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
        transition: transform 0.2s;
    }
    
    .status-badge:hover {
        transform: scale(1.05);
    }
    
    .status-active {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    
    .status-inactive {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
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
    
    /* Alert Messages */
    .alert {
        border: none;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .alert-success {
        background: #dcfce7;
        border: 1px solid #bbf7d0;
        color: #166534;
    }
    
    .alert-danger {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: #991b1b;
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
    
    /* Add New Button */
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
        text-decoration: none;
    }

    .add-btn:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }
    
    /* Course Group Header */
    .course-group-header {
        background: linear-gradient(to right, #f0f9ff, #e0f2fe);
        border-left: 4px solid #3b82f6;
        font-weight: 600;
        color: #1e293b;
    }
    
    .course-group-header td {
        padding: 15px;
        font-size: 1.05rem;
    }
    
    /* Course Badge */
    .course-badge {
        background: #e0f2fe;
        color: #0369a1;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        margin: 2px;
        display: inline-block;
        border: 1px solid #bae6fd;
    }
    
    /* Fee Amount Styles (Keep original design) */
    .fee-amount {
        font-weight: 600;
        color: #28a745;
        font-size: 1rem;
    }
    
    /* Seats Info (Keep original design) */
    .seats-info {
        display: flex;
        gap: 10px;
        font-size: 0.85rem;
    }
    
    .seats-total {
        color: #495057;
    }
    
    .seats-allocated {
        color: #28a745;
        font-weight: 500;
    }
    
    .seats-available {
        color: #007bff;
        font-weight: 500;
    }
    
    .progress {
        height: 5px;
    }
    
    .progress-bar {
        background-color: #28a745;
    }
    
    /* Modal Styles (Keep original design with some improvements) */
    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .modal-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        border-radius: 12px 12px 0 0;
        padding: 20px 24px;
    }
    
    .modal-title {
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .modal-close-button {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #64748b;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close-button:hover {
        color: #dc2626;
    }
    
    .modal-body {
        padding: 24px;
    }
    
    /* Fee Details Panel (Keep original design) */
    .fee-details-panel {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .fee-detail-item {
        padding: 10px;
        background: #f8f9fa;
        border-radius: 6px;
        margin-bottom: 8px;
        border-left: 3px solid #28a745;
    }
    
    .fee-detail-item.no-fee {
        border-left-color: #6c757d;
        background: #e9ecef;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .filter-grid {
            flex-direction: column;
            gap: 12px;
        }
        
        .filter-group {
            min-width: 100%;
        }
        
        .filter-actions {
            width: 100%;
            justify-content: flex-end;
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        
        .erp-table th,
        .erp-table td {
            padding: 8px 12px;
            font-size: 13px;
        }
        
        .action-btn {
            padding: 4px 8px;
            font-size: 11px;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-cash-coin"></i>
            Fee Structure Management
        </h1>
        <a href="{{ route('course.fee.form') }}" class="btn-filter btn-filter-primary">
            <i class="bi bi-plus-circle"></i>
            Create New
        </a>
    </div>

    <!-- Messages -->
    @if(session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle"></i>{{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle"></i>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Filters -->
    <div class="filter-container">
        <form method="GET" action="{{ route('fee.structure.view') }}" class="filter-form">
            <div class="filter-grid">
                <div class="filter-group">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                    <select name="course_type" class="filter-input">
                        <option value="">All Classes</option>
                        @foreach($filterData['course_types'] as $courseType)
                        <option value="{{ $courseType }}" {{ request('course_type') == $courseType ? 'selected' : '' }}>
                            {{ $courseType }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <i class="bi bi-calendar"></i>
                    <select name="batch_year" class="filter-input">
                        <option value="">All Years</option>
                        @foreach($filterData['batch_years'] as $year)
                        <option value="{{ $year }}" {{ request('batch_year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group d-none">
                    <i class="bi bi-diagram-3"></i>
                    <select name="product_id" class="filter-input">
                        <option value="">All Branches</option>
                        @foreach($filterData['products'] as $id => $product)
                        <option value="{{ $id }}" {{ request('product_id') == $id ? 'selected' : '' }}>
                            {{ $product }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <i class="bi bi-calendar-range"></i>
                    <select name="academic_year" class="filter-input">
                        <option value="">All Academic Years</option>
                        @foreach($filterData['academic_years'] as $academicYear)
                        <option value="{{ $academicYear }}" {{ request('academic_year') == $academicYear ? 'selected' : '' }}>
                            {{ $academicYear }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter btn-filter-primary">
                    <i class="bi bi-funnel"></i>
                    Apply Filters
                </button>
                <a href="{{ route('fee.structure.view') }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-x-circle"></i>
                    Reset Filters
                </a>
            </div>
        </form>
    </div>

    @if($feeStructures->count() > 0)
    @php
        $groupedStructures = $feeStructures->groupBy(function($item) {
            return $item->course_type . '|' . $item->product_id;
        });
    @endphp

    <div class="table-responsive">
        <table class="erp-table">
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Fees</th>
                    <th>Seats</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupedStructures as $groupKey => $structures)
                    @php
                        $firstStructure = $structures->first();
                        $courseType = $firstStructure->course_type;
                        $subType = $firstStructure->sub_type;
                        $totalInGroup = $structures->count();
                    @endphp
                    
                    <!-- Course Group Header -->
                    <tr class="course-group-header">
                        <td colspan="5">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $courseType }}</strong>
                                    <small class="text-muted ms-2">
                                        <i class="bi bi-diagram-3"></i> {{ $subType }}
                                    </small>
                                </div>
                                <div>
                                    <span class="course-badge">
                                        {{ $totalInGroup }} Academic Year(s)
                                    </span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Academic Year Rows -->
                    @foreach($structures as $feeStructure)
                    <tr>
                        <!-- Course/Branch Info -->
                        <td>
                            <div class="font-medium">{{ $feeStructure->course_type }}</div>
                            <div class="mb-1">
                                <span class="course-badge">
                                    <i class="bi bi-calendar"></i>
                                    {{ $feeStructure->academic_year }}
                                </span>
                                <!-- <span class="course-badge">
                                    <i class="bi bi-calendar-range"></i>
                                    {{ $feeStructure->batch }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $feeStructure->sub_type }}
                            </div> -->
                        </td>
                        
                        <!-- Fees (Keep original design) -->
                        <td>
                            <div class="fee-amount">
                                ₹{{ number_format($feeStructure->total_fee, 2) }}
                            </div>
                            <div class="text-sm text-gray-500">
                                @php
                                    $courseFee = $feeStructure->course_fee;
                                    $registrationFee = $feeStructure->registration_fee;
                                    $hasCourseFee = is_array($courseFee) && !empty($courseFee['payments']);
                                    $hasRegistrationFee = is_array($registrationFee) && !empty($registrationFee['payments']);
                                @endphp
                                
                                @if($hasCourseFee || $hasRegistrationFee)
                                    <div>
                                        @if($hasCourseFee)
                                        <span class="text-primary">Class: ₹{{ 
                                            array_sum(array_column($courseFee['payments'], 'amount')) 
                                        }}</span>
                                        @endif
                                        
                                        @if($hasRegistrationFee)
                                        <br>
                                        <span class="text-success">Registration: ₹{{ 
                                            array_sum(array_column($registrationFee['payments'], 'amount')) 
                                        }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">No fees configured</span>
                                @endif
                            </div>
                        </td>
                        
                        <!-- Seats (Keep original design) -->
                        <td>
                            <div class="seats-info">
                                <span class="seats-total" title="Total Seats">
                                    <i class="bi bi-chair"></i> {{ $feeStructure->total_seats }}
                                </span>
                                <span class="seats-allocated" title="Allocated Seats">
                                    <i class="bi bi-check-circle"></i> {{ $feeStructure->allocated_seats }}
                                </span>
                                <span class="seats-available" title="Available Seats">
                                    <i class="bi bi-clock"></i> {{ $feeStructure->available_seats }}
                                </span>
                            </div>
                            
                            @if($feeStructure->total_seats > 0)
                                @php
                                    $allocationPercentage = ($feeStructure->allocated_seats / $feeStructure->total_seats) * 100;
                                @endphp
                                <div class="progress mt-1" style="height: 5px;">
                                    <div class="progress-bar" 
                                         role="progressbar" 
                                         style="width: {{ $allocationPercentage }}%"
                                         title="{{ number_format($allocationPercentage, 1) }}% allocated">
                                    </div>
                                </div>
                                <small class="text-gray-500" style="font-size: 0.75rem;">
                                    {{ number_format($allocationPercentage, 1) }}% allocated
                                </small>
                            @endif
                        </td>
                        
                        <!-- Status -->
                        <td>
                            <span class="status-badge {{ $feeStructure->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $feeStructure->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        
                        <!-- Actions (ERP styled) -->
                        <td class="text-center">
                            <div class="d-flex justify-content-center custom-gap">
                                <button class="action-btn action-btn-view d-block" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#detailsModal{{ $feeStructure->id }}"
                                        title="View Details">
                                       <div>
                                            <i class="bi bi-eye"></i>
                                       </div>
                                       <div>
                                            <span>View</span>
                                       </div>
                                </button>
                                <a href="{{ route('course.fee.structure.edit', $feeStructure->product_id) }}" 
                                   class="action-btn action-btn-edit d-block"
                                   title="Edit">
                                    <div>
                                        <i class="bi bi-pencil"></i>
                                    </div>
                                    <div>
                                        <span>Edit</span>
                                    </div>
                                </a>
                                
                                @if($feeStructure->batch_id)
                                <button class="action-btn action-btn-secondary d-block" 
                                        onclick="viewBatchDetails('{{ $feeStructure->batch_id }}')"
                                        title="View Batch">
                                        <div>
                                            <i class="bi bi-list"></i>
                                        </div>
                                        <div>
                                            <span>Batch</span>
                                        </div>
                                </button>
                                @endif
                            </div>
                            
                            <div class="text-xs text-gray-500 mt-1">
                                <i class="bi bi-clock"></i> 
                                {{ \Carbon\Carbon::parse($feeStructure->created_at)->format('d M Y') }}
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Details Modal (Keep original design with ERP improvements) -->
                    <div class="modal fade" id="detailsModal{{ $feeStructure->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="bi bi-info-circle"></i>
                                        Fee Structure Details
                                    </h5>
                                    <button type="button" class="modal-close-button" data-bs-dismiss="modal">×</button>
                                </div>
                                <div class="modal-body">
                                    <!-- Keep original modal body content exactly as it was -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="details-row">
                                                <span class="details-label">Class:</span>
                                                <span class="details-value">{{ $feeStructure->course_type }}</span>
                                            </div>
                                            <div class="details-row">
                                                <span class="details-label">Branch:</span>
                                                <span class="details-value">{{ $feeStructure->sub_type }}</span>
                                            </div>
                                            <div class="details-row">
                                                <span class="details-label">Academic Year:</span>
                                                <span class="details-value">{{ $feeStructure->academic_year }}</span>
                                            </div>
                                            <div class="details-row">
                                                <span class="details-label">Batch Year:</span>
                                                <span class="details-value">{{ $feeStructure->batch_year }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="details-row">
                                                <span class="details-label">Batch:</span>
                                                <span class="details-value">{{ $feeStructure->batch }}</span>
                                            </div>
                                          
                                            <div class="details-row">
                                                <span class="details-label">Status:</span>
                                                <span class="details-value">
                                                    <span class="status-badge {{ $feeStructure->is_active ? 'status-active' : 'status-inactive' }}">
                                                        {{ $feeStructure->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="details-row">
                                                <span class="details-label">Session:</span>
                                                <span class="details-value">{{ $feeStructure->session_range }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <!-- Seats Details -->
                                    <h6 class="mb-3"><i class="fas fa-chair me-2"></i>Seats Information</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="details-row">
                                                <span class="details-label">Total Seats:</span>
                                                <span class="details-value">{{ $feeStructure->total_seats }}</span>
                                            </div>
                                            <div class="details-row">
                                                <span class="details-label">Available Seats:</span>
                                                <span class="details-value">{{ $feeStructure->available_seats }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="details-row">
                                                <span class="details-label">Allocated Seats:</span>
                                                <span class="details-value">{{ $feeStructure->allocated_seats }}</span>
                                            </div>
                                            <div class="details-row">
                                                <span class="details-label">Allocation:</span>
                                                <span class="details-value">
                                                    @if($feeStructure->total_seats > 0)
                                                        {{ number_format(($feeStructure->allocated_seats / $feeStructure->total_seats) * 100, 1) }}%
                                                    @else
                                                        0%
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Sections -->
                                    @if(count($feeStructure->sections) > 0)
                                    <h6 class="mb-3"><i class="fas fa-th-list me-2"></i>Sections</h6>
                                    <div class="row">
                                        @foreach($feeStructure->sections as $section)
                                        <div class="col-md-4 mb-2">
                                            <div class="card p-2">
                                                <div class="d-flex justify-content-between">
                                                    <strong>{{ $section['name'] ?? 'Unnamed' }}</strong>
                                                    <span class="text-success">{{ $section['seats'] ?? 0 }} seats</span>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <hr>
                                    @endif
                                    
                                    <!-- Fee Details -->
                                    <h6 class="mb-3"><i class="fas fa-money-bill-wave me-2"></i>Fee Details</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="fee-details-panel">
                                                @if(is_array($feeStructure->course_fee) && !empty($feeStructure->course_fee['payments']))
                                                    @foreach($feeStructure->course_fee['payments'] as $index => $payment)
                                                    <div class="fee-detail-item">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <strong>{{ isset($feeStructure->course_fee['duration']) && $feeStructure->course_fee['duration'] === 'One Time' ? 'Fee' : $feeStructure->course_fee['duration'].' Payment ' . ($index + 1) }}</strong>
                                                                @if(isset($payment['start_date']) && $payment['start_date'])
                                                                <div class="text-muted" style="font-size: 0.8rem;">
                                                                    {{ $payment['start_date'] }} - {{ $payment['end_date'] ?? 'N/A' }}
                                                                </div>
                                                                @endif
                                                            </div>
                                                            <div class="fee-amount">
                                                                ₹{{ number_format($payment['amount'] ?? 0, 2) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    
                                                    @if(isset($feeStructure->course_fee['late_fee']))
                                                    <div class="fee-detail-item">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <strong class="text-warning">
                                                                    <i class="fas fa-clock me-1"></i>Late Fee
                                                                </strong>
                                                                <div class="text-muted" style="font-size: 0.8rem;">
                                                                    {{ ucfirst($feeStructure->course_fee['late_fee']['type'] ?? 'flat') }}
                                                                </div>
                                                            </div>
                                                            <div class="text-warning">
                                                                ₹{{ number_format($feeStructure->course_fee['late_fee']['amount'] ?? 0, 2) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    
                                                    @if(isset($feeStructure->course_fee['partial_payment']))
                                                    <div class="fee-detail-item">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <strong class="text-info">
                                                                    <i class="fas fa-percentage me-1"></i>Partial Payment
                                                                </strong>
                                                                <div class="text-muted" style="font-size: 0.8rem;">
                                                                    {{ ucfirst($feeStructure->course_fee['partial_payment']['type'] ?? 'percentage') }}
                                                                </div>
                                                            </div>
                                                            <div class="text-info">
                                                                ₹{{ number_format($feeStructure->course_fee['partial_payment']['amount'] ?? 0, 2) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                @else
                                                    <div class="fee-detail-item no-fee">
                                                        <div class="text-center text-muted">
                                                            No class fee configured
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="fee-details-panel">
                                                @if(is_array($feeStructure->registration_fee) && !empty($feeStructure->registration_fee['payments']))
                                                    @foreach($feeStructure->registration_fee['payments'] as $index => $payment)
                                                    <div class="fee-detail-item">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <strong>Registration Fee</strong>
                                                                @if(isset($payment['start_date']) && $payment['start_date'])
                                                                <div class="text-muted" style="font-size: 0.8rem;">
                                                                    {{ $payment['start_date'] }} - {{ $payment['end_date'] ?? 'N/A' }}
                                                                </div>
                                                                @endif
                                                            </div>
                                                            <div class="fee-amount">
                                                                ₹{{ number_format($payment['amount'] ?? 0, 2) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    
                                                    @if(isset($feeStructure->registration_fee['late_fee']))
                                                    <div class="fee-detail-item">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <strong class="text-warning">
                                                                    <i class="fas fa-clock me-1"></i>Late Fee
                                                                </strong>
                                                                <div class="text-muted" style="font-size: 0.8rem;">
                                                                    {{ ucfirst($feeStructure->registration_fee['late_fee']['type'] ?? 'flat') }}
                                                                </div>
                                                            </div>
                                                            <div class="text-warning">
                                                                ₹{{ number_format($feeStructure->registration_fee['late_fee']['amount'] ?? 0, 2) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    
                                                    @if(isset($feeStructure->registration_fee['partial_payment']))
                                                    <div class="fee-detail-item">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <strong class="text-info">
                                                                    <i class="fas fa-percentage me-1"></i>Partial Payment
                                                                </strong>
                                                                <div class="text-muted" style="font-size: 0.8rem;">
                                                                    {{ ucfirst($feeStructure->registration_fee['partial_payment']['type'] ?? 'percentage') }}
                                                                </div>
                                                            </div>
                                                            <div class="text-info">
                                                                ₹{{ number_format($feeStructure->registration_fee['partial_payment']['amount'] ?? 0, 2) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                @else
                                                    <div class="fee-detail-item no-fee">
                                                        <div class="text-center text-muted">
                                                            No registration fee configured
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Total Fee -->
                                    <div class="text-end mt-4">
                                        <h4 class="text-success">
                                            <i class="fas fa-file-invoice-dollar me-2"></i>
                                            Total Fee: ₹{{ number_format($feeStructure->total_fee, 2) }}
                                        </h4>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <a href="{{ route('course.fee.structure.edit', $feeStructure->product_id) }}" 
                                       class="btn btn-primary">
                                        <i class="bi bi-pencil me-1"></i> Edit Fee Structure
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    @if(!$loop->last)
                    <!-- Spacer between course groups -->
                    <tr>
                        <td colspan="5" style="padding: 10px; background: #f8f9fa;"></td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Batch Details Modal -->
    <div class="modal fade" id="batchDetailsModal" tabindex="-1" aria-labelledby="batchDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="batchDetailsModalLabel">
                        <i class="bi bi-list"></i>
                        Batch Details
                    </h5>
                    <button type="button" class="modal-close-button" data-bs-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body" id="batchDetailsContent">
                    Loading...
                </div>
            </div>
        </div>
    </div>
    
    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="bi bi-cash-coin"></i>
        </div>
        <h4>No Fee Structures Found</h4>
        <p class="text-muted">
            @if(count(array_filter($filters)) > 0)
            No fee structures match your current filters.
            @else
            No fee structures have been created yet.
            @endif
        </p>
        <a href="{{ route('course.fee.form') }}" class="btn-filter btn-filter-primary mt-2">
            <i class="bi bi-plus-circle"></i>Create New Fee Structure
        </a>
    </div>
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Filter form submission with loading state
    $('.filter-form').on('submit', function(e) {
        const submitBtn = $(this).find('.btn-filter-primary');
        const originalHTML = submitBtn.html();
        submitBtn.html('<span class="loading-spinner"></span> Applying...');
        submitBtn.prop('disabled', true);
        
        setTimeout(() => {
            submitBtn.html(originalHTML);
            submitBtn.prop('disabled', false);
        }, 2000);
    });
});

function viewBatchDetails(batchId) {
    $.ajax({
        url: "{{ route('batch.details.view') }}",
        method: "GET",
        data: { batch_id: batchId },
        beforeSend: function() {
            $('#batchDetailsContent').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading batch details...</p>
                </div>
            `);
        },
        success: function(response) {
            $('#batchDetailsContent').html(response);
            $('#batchDetailsModal').modal('show');
        },
        error: function() {
            $('#batchDetailsContent').html(`
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Error loading batch details. Please try again.
                </div>
            `);
            $('#batchDetailsModal').modal('show');
        }
    });
}
</script>
@endsection
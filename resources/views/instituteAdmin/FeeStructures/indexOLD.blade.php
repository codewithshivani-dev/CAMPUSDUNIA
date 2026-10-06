@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Hostel Fee Structure Management</title>
<style>
    /* ERP Table Styles */
    .erp-table {
        width: 100%;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
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
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    /* Bulk Actions */
    .bulk-actions-container {
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        animation: slideDown 0.3s ease;
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

    /* Checkbox styling */
    .select-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border-radius: 4px;
        border: 2px solid #cbd5e1;
        transition: all 0.2s;
    }
    
    .select-checkbox:hover {
        border-color: #3b82f6;
    }
    
    .select-checkbox:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
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
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
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
    
    .filter-actions {
        display: flex;
        gap: 12px;
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
    }
    
    /* Banner */
    .banner {
        height: 260px;
        border-radius: 18px;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #2154BE, #3E70B3);
        display: flex;
        align-items: center;
        animation: fadeIn 0.8s ease;
        margin-bottom: 30px;
    }
    
    .banner img.bg {
        width: 100%;
        height: 260px;
        object-fit: cover;
        filter: brightness(0.8);
    }
    
    .banner .meta {
        position: absolute;
        left: 28px;
        bottom: 22px;
        background: rgba(255, 255, 255, 0.95);
        padding: 14px 20px;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        animation: slideInLeft 0.5s ease;
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    /* Action Buttons */
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
        text-decoration: none;
        color: white;
    }
    
    .table-actions {
        display: flex;
        gap: 5px;
        justify-content: center;
    }
    
    .action-btn {
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 5px;
        justify-content: center;
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
        border-color: #fef2c8;
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
        background: #fef2c8;
        color: #92400e;
    }
    
    .action-btn-delete:hover {
        background: #fecaca;
        color: #dc2626;
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
    
    /* Fee styling */
    .fee-amount {
        font-weight: 600;
        color: #1e293b;
        font-size: 14px;
    }

    
    .hostel-type-badge {
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: capitalize;
        display: inline-block;
    }
    
    .type-boys {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }
    
    .type-girls {
        background: #fce7f3;
        color: #9d174d;
        border: 1px solid #fbcfe8;
    }
    
    .type-coed {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    
    /* Alert styling */
    .alert {
        border-radius: 8px;
        border: 1px solid transparent;
        padding: 12px 16px;
        margin-bottom: 20px;
        animation: fadeIn 0.3s ease;
    }
    
    .alert-success {
        background-color: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }
    
    .alert-danger {
        background-color: #fee2e2;
        color: #991b1b;
        border-color: #fecaca;
    }
    
    .alert .btn-close {
        padding: 12px;
    }
    
    /* Pagination styling */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #e2e8f0;
    }
    
    .pagination-info {
        font-size: 14px;
        color: #64748b;
    }
    
    /* Fee details tooltip */
    .fee-details {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .fee-item {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: #64748b;
    }
    
    .fee-label {
        font-weight: 500;
    }
    
    .fee-value {
        font-weight: 600;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-grid {
            flex-direction: column;
            gap: 10px;
        }
        
        .filter-group {
            min-width: 100%;
        }
        
        .filter-actions {
            width: 100%;
            justify-content: center;
        }
        
        .bulk-actions-container {
            flex-direction: column;
            align-items: stretch;
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
</style>

<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">Hostel Fee Structure</h1>
        <a href="{{ route('hostel.fees.create') }}" class="add-btn">
            <i class="bi bi-plus-circle"></i>
            Add New Fee Structure
        </a>
    </div>

    {{-- Messages --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Filters --}}
    <div class="filter-container">
        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-grid">
                {{-- Hostel Name --}}
                <div class="filter-group">
                    <i class="bi bi-building"></i>
                    <input type="text" name="hostel_name" class="filter-input"
                        value="{{ request('hostel_name') }}" placeholder="Hostel Name">
                </div>

                {{-- Hostel Type --}}
                <div class="filter-group">
                    <i class="bi bi-people"></i>
                    <select name="hostel_type" class="filter-input">
                        <option value="">All Types</option>
                        <option value="boys" {{ request('hostel_type') == 'boys' ? 'selected' : '' }}>Boys</option>
                        <option value="girls" {{ request('hostel_type') == 'girls' ? 'selected' : '' }}>Girls</option>
                        <option value="coed" {{ request('hostel_type') == 'coed' ? 'selected' : '' }}>Co-ed</option>
                    </select>
                </div>

                {{-- Academic Year --}}
                <div class="filter-group">
                    <i class="bi bi-calendar"></i>
                    <input type="text" name="academic_year" class="filter-input"
                        value="{{ request('academic_year') }}" placeholder="Academic Year">
                </div>

                {{-- Status --}}
                <div class="filter-group">
                    <i class="bi bi-info-circle"></i>
                    <select name="status" class="filter-input">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter btn-filter-primary">
                    <i class="bi bi-funnel"></i>
                    Apply Filters
                </button>
                <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary">
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
        <div class="selected-count" id="selectedCount">0 fee structures selected</div>
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
                Clear
            </button>
        </div>
    </div>

    {{-- Hostel Fees Table --}}
    <div class="table-responsive">
        <table class="erp-table">
            <thead>
                <tr>
                    <th width="40">
                        <input type="checkbox" id="selectAll" class="select-checkbox">
                    </th>
                    <th>Reference ID</th>
                    <th>Hostel Name</th>
                    <th>Type</th>
                    <th>Academic Year</th>
                    <th>Fee Details</th>
                    <th>Total Fee</th>
                    <th>Status</th>
                    <!-- <th class="text-center">Actions</th> -->
                </tr>
            </thead>
            <tbody>
                @forelse($hostelFees as $fee)
                <tr>
                    <td>
                        <input type="checkbox" class="fee-checkbox select-checkbox" value="{{ $fee->id }}">
                    </td>
                    <td>
                        <span class="fee-reference-id">{{ $fee->hostel_fee_reference_id }}</span>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $fee->hostel_name }}</div>
                    </td>
                    <td>
                        <span class="">
                            {{ ucfirst($fee->hostel_type) }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $fee->academic_year }}</div>
                    </td>
                    <td>
                        <div class="fee-details">
                            <div class="fee-item">
                                <span class="fee-label">Security Deposit:</span>
                                <span class="fee-value">₹{{ number_format($fee->security_deposit, 2) }}</span>
                            </div>
                            <div class="fee-item">
                                <span class="fee-label">Maintenance:</span>
                                <span class="fee-value">₹{{ number_format($fee->maintenance_fee, 2) }}</span>
                            </div>
                            <div class="fee-item">
                                <span class="fee-label">Utilities:</span>
                                <span class="fee-value">₹{{ number_format($fee->utility_charges, 2) }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fee-amount">₹{{ number_format($fee->total_fee, 2) }}</div>
                        <small class="text-muted">per month</small>
                    </td>
                    <td>
                        @if($fee->status)
                            <span class="status-badge status-active">Active</span>
                        @else
                            <span class="status-badge status-inactive">Inactive</span>
                        @endif
                    </td>
                    <!-- <td class="text-center">
                        <div class="table-actions">
                            <div>
                                <button class="action-btn action-btn-view d-block" onclick="viewFeeDetails('{{ $fee->id }}')" title="View Details">
                                    <div>
                                        <i class="fa-regular fa-eye"></i>
                                    </div>
                                    <div>
                                        <span class="small">View</span>
                                    </div>
                                </button>
                            </div>

                        </div>
                    </td> -->
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-house-door"></i>
                            </div>
                            <h4>No hostel fee structures found</h4>
                            <p>Try adjusting your filters or add a new fee structure</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>


</div>

<!-- Fee Details Modal -->
<div class="modal fade" id="viewFeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-cash-stack me-2"></i>
                    Fee Structure Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="feeDetailsContent">
                <!-- Content loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery and Bootstrap Bundle JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    // Bulk Selection Management
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const feeCheckboxes = document.querySelectorAll('.fee-checkbox');
        const bulkActionsContainer = document.getElementById('bulkActionsContainer');
        const selectedCountElement = document.getElementById('selectedCount');

        // Select All functionality
        selectAllCheckbox.addEventListener('change', function() {
            feeCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectionUI();
        });

        // Individual checkbox change
        feeCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectionUI);
        });

        function updateSelectionUI() {
            const selectedCount = document.querySelectorAll('.fee-checkbox:checked').length;
            
            if (selectedCount > 0) {
                bulkActionsContainer.classList.add('active');
                selectedCountElement.textContent = selectedCount + ' fee structure(s) selected';
                
                // Update select all checkbox state
                selectAllCheckbox.checked = selectedCount === feeCheckboxes.length;
                selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < feeCheckboxes.length;
            } else {
                bulkActionsContainer.classList.remove('active');
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
        }
    });

    // Bulk Action Functions
    function clearSelection() {
        document.querySelectorAll('.fee-checkbox:checked').forEach(checkbox => {
            checkbox.checked = false;
        });
        document.getElementById('selectAll').checked = false;
        document.getElementById('bulkActionsContainer').classList.remove('active');
    }

    function bulkAction(action) {
        const selectedFees = Array.from(document.querySelectorAll('.fee-checkbox:checked'))
            .map(checkbox => checkbox.value);
        
        if (selectedFees.length === 0) {
            alert('Please select at least one fee structure.');
            return;
        }
        
        switch(action) {
            case 'download':
                if (confirm(`Download data for ${selectedFees.length} fee structure(s)?`)) {
                    // Show loading state
                    const btn = event.target.closest('button');
                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = '<span class="loading-spinner"></span> Processing...';
                    btn.disabled = true;
                    
                    // Simulate download
                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                        alert('Download started for ' + selectedFees.length + ' fee structures');
                    }, 1000);
                }
                break;
                
            case 'bulk_delete':
                if (confirm(`Are you sure you want to delete ${selectedFees.length} fee structure(s)? This action cannot be undone.`)) {
                    // Show loading state
                    const btn = event.target.closest('button');
                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = '<span class="loading-spinner"></span> Deleting...';
                    btn.disabled = true;
                    
                    // Simulate delete
                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                        clearSelection();
                        alert(`${selectedFees.length} fee structures deleted successfully`);
                    }, 1500);
                }
                break;
                
            default:
                alert(`${action} action triggered for ${selectedFees.length} fee structures`);
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

    // Filter form submission with loading state
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

    // View Fee Details Function
    function viewFeeDetails(feeId) {
        // Show loading
        $('#feeDetailsContent').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">Loading fee details...</p>
            </div>
        `);

        const modal = new bootstrap.Modal(document.getElementById('viewFeeModal'));
        modal.show();

        // Simulate AJAX call - Replace with actual API endpoint
        setTimeout(() => {
            // This is a simulation - replace with actual data from your server
            const feeData = {
                id: feeId,
                reference_id: 'HF-2024-' + feeId,
                hostel_name: 'Sample Hostel',
                hostel_type: 'boys',
                academic_year: '2024-2025',
                security_deposit: 5000,
                maintenance_fee: 2000,
                utility_charges: 1000,
                total_fee: 8000,
                status: true,
                created_at: '2024-01-15',
                updated_at: '2024-01-15'
            };

            const getHostelTypeClass = (type) => {
                switch(type) {
                    case 'boys': return 'type-boys';
                    case 'girls': return 'type-girls';
                    case 'coed': return 'type-coed';
                    default: return 'type-boys';
                }
            };

            $('#feeDetailsContent').html(`
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title mb-3"><i class="bi bi-building me-2"></i>Hostel Information</h6>
                                <p><strong>Hostel Name:</strong> ${feeData.hostel_name}</p>
                                <p><strong>Reference ID:</strong> <code>${feeData.reference_id}</code></p>
                                <p><strong>Hostel Type:</strong> 
                                    <span class="hostel-type-badge ${getHostelTypeClass(feeData.hostel_type)}">
                                        ${feeData.hostel_type.charAt(0).toUpperCase() + feeData.hostel_type.slice(1)}
                                    </span>
                                </p>
                                <p><strong>Academic Year:</strong> ${feeData.academic_year}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title mb-3"><i class="bi bi-info-circle me-2"></i>Status Information</h6>
                                <p><strong>Status:</strong> 
                                    ${feeData.status ? 
                                        '<span class="status-badge status-active">Active</span>' : 
                                        '<span class="status-badge status-inactive">Inactive</span>'}
                                </p>
                                <p><strong>Created:</strong> ${new Date(feeData.created_at).toLocaleDateString()}</p>
                                <p><strong>Last Updated:</strong> ${new Date(feeData.updated_at).toLocaleDateString()}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title mb-4"><i class="bi bi-cash-stack me-2"></i>Fee Breakdown</h6>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded mb-3">
                                    <div class="text-muted mb-2">Security Deposit</div>
                                    <div class="h4 text-primary">₹${feeData.security_deposit.toLocaleString()}</div>
                                    <small class="text-muted">One-time payment</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded mb-3">
                                    <div class="text-muted mb-2">Monthly Maintenance</div>
                                    <div class="h4 text-success">₹${feeData.maintenance_fee.toLocaleString()}</div>
                                    <small class="text-muted">Per month</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded mb-3">
                                    <div class="text-muted mb-2">Utility Charges</div>
                                    <div class="h4 text-warning">₹${feeData.utility_charges.toLocaleString()}</div>
                                    <small class="text-muted">Per month</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-primary mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Total Monthly Fee:</strong>
                                    <div class="text-muted">Including all charges</div>
                                </div>
                                <div class="h3 text-success">₹${feeData.total_fee.toLocaleString()}</div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h6 class="mb-3">Annual Cost Calculation</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-center p-3 bg-light rounded">
                                        <div class="text-muted mb-2">Quarterly (3 Months)</div>
                                        <div class="h5">₹${(feeData.total_fee * 3).toLocaleString()}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-3 bg-light rounded">
                                        <div class="text-muted mb-2">Half Yearly (6 Months)</div>
                                        <div class="h5">₹${(feeData.total_fee * 6).toLocaleString()}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-3 bg-light rounded">
                                        <div class="text-muted mb-2">Annual (12 Months)</div>
                                        <div class="h5">₹${(feeData.total_fee * 12).toLocaleString()}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        }, 1000);
    }
</script>
@endsection
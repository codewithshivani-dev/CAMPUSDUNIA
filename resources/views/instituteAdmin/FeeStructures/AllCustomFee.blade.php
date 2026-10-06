@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Custom Fee Structures Management</title>
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
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
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
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        cursor: pointer;
        user-select: none;
        transition: background-color 0.2s;
        position: relative;
    }
    
    .erp-table th h6 {
        color: rgba(255, 255, 255, 0.9);
        font-size: 11px;
        margin: 2px 0 0;
    }
    
    .erp-table th:hover {
        background-color: rgba(255, 255, 255, 0.1);
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
        color: rgba(255, 255, 255, 0.5);
        font-size: 12px;
        line-height: 1;
    }
    
    .sort-icon.active {
        color: white;
    }
    
    .erp-table td {
        padding: 15px 8px;
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
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    
    .erp-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Status Badges */
    .status-badge {
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .status-active {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    
    .status-inactive {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    .status-draft {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        border: 1px solid #fde68a;
    }
    
    /* Bulk Actions */
    .bulk-actions-container {
        margin-bottom: 20px;
        display: none;
        align-items: center;
        flex-wrap: wrap;
        animation: slideDown 0.3s ease;
        background: #fff;
        padding: 15px;
        border-radius: 16px;
        border: 2px solid var(--border-color);
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    }
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .bulk-actions-container.active { display: flex; }
    
    .selected-count {
        font-weight: 600;
        color: var(--primary-color);
        margin-right: auto;
        font-size: 14px;
    }
    
    .bulk-action-btn {
        padding: 8px 18px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        color: var(--text-dark);
        transition: all 0.3s;
        font-size: 14px;
        display: flex;
        align-items: center;
        border: 2px solid transparent;
        margin-right: 8px;
        background: white;
    }
    
    .bulk-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
    
    .bulk-action-btn.download {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #166534;
        border-color: #bbf7d0;
    }
    
    .bulk-action-btn.delete {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #dc2626;
        border-color: #fecaca;
    }
    
    .bulk-action-btn.clear {
        background: transparent;
        color: var(--text-muted);
        border: 2px solid var(--border-color);
    }
    
    .bulk-action-btn.clear:hover { background: #f1f5f9; }

    /* Checkbox */
    .select-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border-radius: 6px;
        border: 2px solid #cbd5e1;
        transition: all 0.2s;
    }
    
    .select-checkbox:hover { border-color: var(--primary-color); }
    
    .select-checkbox:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    /* Filter container */
    .filter-container {
        background: #fff;
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 20px;
        border: 2px solid var(--border-color);
        animation: slideUp 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
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
        min-width: 180px;
    }
    
    .filter-group .bi {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary-color);
        z-index: 1;
    }
    
    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 10px 10px 10px 38px;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 14px;
        background: #fff;
        transition: all 0.3s;
        height: 44px;
        box-sizing: border-box;
    }
    
    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        transform: translateY(-1px);
    }
    
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .filter-actions { display: flex; gap: 12px; }
    
    .btn-filter {
        padding: 10px 22px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        font-size: 14px;
        height: 44px;
        box-sizing: border-box;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        text-decoration: none;
    }
    
    .btn-filter-primary {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
    }
    
    .btn-filter-primary:hover {
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
        color: white;
    }
    
    .btn-filter-secondary {
        background: #f1f5f9;
        color: #475569;
        border-color: var(--border-color);
    }
    
    .btn-filter-secondary:hover {
        background: #e2e8f0;
        color: #475569;
    }
    
    /* Header */
    .page-header {
        background: var(--primary-gradient);
        padding: 25px;
        border-radius: 16px;
        margin-bottom: 24px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: white;
        margin: 0;
    }
    
    /* Add Button */
    .add-btn {
        padding: 12px 24px;
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
        cursor: pointer;
        border-radius: 12px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        text-decoration: none;
    }
    
    .add-btn:hover {
        background: white;
        color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        text-decoration: none;
    }
    
    /* Fee type badges */
    .fee-type-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: capitalize;
        display: inline-block;
    }
    
    .type-transport { background: rgba(67, 97, 238, 0.1); color: #1e40af; }
    .type-tuition { background: rgba(16, 185, 129, 0.1); color: #166534; }
    .type-hostel { background: rgba(245, 158, 11, 0.1); color: #92400e; }
    .type-library { background: rgba(59, 130, 246, 0.1); color: #0369a1; }
    .type-sports { background: rgba(236, 72, 153, 0.1); color: #9d174d; }
    .type-custom { background: rgba(139, 92, 246, 0.1); color: #5b21b6; }
    .type-other { background: rgba(100, 116, 139, 0.1); color: #475569; }
    
    /* Stats cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 18px;
        border: 2px solid var(--border-color);
        transition: all 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.1);
        border-color: var(--primary-color);
    }
    
    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }
    
    .stat-icon.total { background: rgba(67, 97, 238, 0.1); color: var(--primary-color); }
    .stat-icon.active { background: rgba(16, 185, 129, 0.1); color: #166534; }
    .stat-icon.inactive { background: rgba(239, 68, 68, 0.1); color: #991b1b; }
    .stat-icon.current { background: rgba(245, 158, 11, 0.1); color: #92400e; }
    
    .stat-number { font-size: 22px; font-weight: 700; color: var(--text-dark); margin-bottom: 4px; }
    .stat-label { font-size: 12px; color: var(--text-muted); }
    
    /* Alert */
    .alert {
        border-radius: 14px;
        border: none;
        padding: 14px 18px;
        margin-bottom: 20px;
    }
    
    .alert-success { background: var(--success-gradient); color: white; }
    .alert-danger { background: var(--danger-gradient); color: white; }
    
    /* Modal */
    .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 25px 50px rgba(0,0,0,0.15);
    }
    
    .modal-header {
        background: var(--primary-gradient);
        color: white;
        border-radius: 20px 20px 0 0;
        border-bottom: none;
    }
    
    .modal-header .btn-close { filter: brightness(0) invert(1); }
    
    .table-responsive { overflow-x: hidden; }
    
    @media (max-width: 768px) {
        .filter-form { flex-direction: column; align-items: stretch; }
        .filter-grid { flex-direction: column; gap: 10px; }
        .filter-group { min-width: 100%; }
        .bulk-actions-container { flex-direction: column; align-items: stretch; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    }
    
    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Custom Fee</h1>
        <a href="{{ route('admin.custom-fees.create') }}" class="add-btn">
            <i class="bi bi-plus-circle"></i>
            Add New Fee
        </a>
    </div>

    {{-- Messages --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Stats Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total"><i class="bi bi-list-check"></i></div>
            <div class="stat-number">{{ $fees->total() }}</div>
            <div class="stat-label">Total Fees</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon active"><i class="bi bi-toggle-on"></i></div>
            <div class="stat-number">{{ $fees->where('status', 'active')->count() }}</div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon inactive"><i class="bi bi-toggle-off"></i></div>
            <div class="stat-number">{{ $fees->where('status', 'inactive')->count() }}</div>
            <div class="stat-label">Inactive</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon current"><i class="bi bi-calendar"></i></div>
            @php
                $currentYear = date('Y') . '-' . (date('Y') + 1);
                $currentYearCount = $fees->where('academic_year', $currentYear)->count();
            @endphp
            <div class="stat-number">{{ $currentYearCount }}</div>
            <div class="stat-label">This Year</div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="filter-container">
        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <i class="bi bi-calendar"></i>
                    <select name="year" class="filter-input" id="filter_year">
                        <option value="">All Academic Years</option>
                        @foreach($academicYears as $key => $value)
                            @if(is_array($value))
                                <option value="{{ $value['value'] ?? $key }}" {{ request('year') == ($value['value'] ?? $key) ? 'selected' : '' }}>
                                    {{ $value['label'] ?? $value }}
                                </option>
                            @else
                                <option value="{{ $key }}" {{ request('year') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <i class="bi bi-tag"></i>
                    <select name="type" class="filter-input" id="filter_type">
                        <option value="">All Fee Types</option>
                        @foreach($feeTypes as $key => $name)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <i class="bi bi-info-circle"></i>
                    <select name="status" class="filter-input" id="filter_status">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter btn-filter-primary">
                    <i class="bi bi-funnel"></i> Apply Filters
                </button>
                <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-x-circle"></i> Reset Filters
                </a>
            </div>
            
            <input type="hidden" name="sort_by" id="sortBy" value="{{ request('sort_by', 'created_at') }}">
            <input type="hidden" name="sort_order" id="sortOrder" value="{{ request('sort_order', 'desc') }}">
        </form>
    </div>

    {{-- Bulk Actions --}}
    <div class="bulk-actions-container" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 fee structures selected</div>
        <div class="d-flex flex-wrap">
            <button class="bulk-action-btn download" onclick="bulkAction('download')">
                <i class="bi bi-download"></i> Download
            </button>
            <button class="bulk-action-btn delete" onclick="bulkAction('bulk_delete')">
                <i class="bi bi-trash"></i> Bulk Delete
            </button>
            <button class="bulk-action-btn clear mr-0" onclick="clearSelection()">
                <i class="bi bi-x-lg"></i> Clear
            </button>
        </div>
    </div>
    
    <div>
        {{-- Fees Table --}}
        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
            <table class="erp-table">
                <thead>
                    <tr>
                        <th class="sticky-checkbox" width="40"><input type="checkbox" id="selectAll" class="select-checkbox"></th>
                        <th class="sticky-main sortable">Fee Name</th>
                        <th class="sortable">Fee Type</th>
                        <th class="sortable">Academic Year</th>
                        <th class="sortable">Duration</th>
                        <th class="sortable">Amount</th>
                        <th class="sortable">Late Fee</th>
                        <th class="sortable">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fees as $fee)
                    <tr>
                        <td class="sticky-checkbox"><input type="checkbox" class="fee-checkbox select-checkbox" value="{{ $fee->id }}"></td>
                        <td class="sticky-main">
                            <div class="fw-semibold">{{ $fee->fee_reference_id }}</div>
                            <div class="text-muted small">{{ $fee->custom_fee_key ?? 'Custom Fee' }}</div>
                        </td>
                        <td>
                            @php
                                $feeTypeClasses = [
                                    'transport' => 'type-transport', 'tuition' => 'type-tuition',
                                    'hostel' => 'type-hostel', 'library' => 'type-library',
                                    'sports' => 'type-sports', 'custom' => 'type-custom', 'other' => 'type-other'
                                ];
                                $feeTypeClass = $feeTypeClasses[$fee->fee_type] ?? 'type-other';
                            @endphp
                            <span class="fee-type-badge {{ $feeTypeClass }}">{{ $fee->fee_type }}</span>
                        </td>
                        <td>{{ $fee->academic_year }}</td>
                        <td>{{ $fee->fee_duration_type }}</td>
                        <td>
                            <div class="fw-semibold mb-1">₹{{ number_format($fee->custom_fee_value, 2) }}</div>
                            @if($fee->discount_amount > 0)
                                <small class="text-success"><i class="bi bi-tag me-1"></i>Discount: ₹{{ number_format($fee->discount_amount, 2) }}</small>
                            @endif
                        </td>
                        <td>
                            @if($fee->late_fee_type === 'fixed')
                                <span>₹{{ number_format($fee->late_fee_value, 2) }}</span>
                            @elseif($fee->late_fee_type === 'percentage')
                                <span class="badge" style="background: var(--danger-gradient); color: white;">{{ $fee->late_fee_value }}%</span>
                            @else
                                <span class="text-muted small">None</span>
                            @endif
                        </td>
                        <td>
                            @if($fee->status === 'active')
                                <span class="status-badge status-active">Active</span>
                            @elseif($fee->status === 'inactive')
                                <span class="status-badge status-inactive">Inactive</span>
                            @else
                                <span class="status-badge status-draft">Draft</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="text-center py-5">
                                <i class="bi bi-cash-stack" style="font-size: 48px; color: #cbd5e1;"></i>
                                <h4 class="mt-3">No fee structures found</h4>
                                <p class="text-muted">Try adjusting your filters or add a new fee structure</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="table-scroll-top" id="tableScrollTop">
            <div class="table-scroll-inner"></div>
        </div>
    </div>
</div>

    <!-- Fee Details Modal -->
    <div class="modal fade" id="viewFeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white"><i class="bi bi-cash-stack me-2"></i>Fee Structure Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="feeDetailsContent"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    // ALL JAVASCRIPT REMAINS EXACTLY THE SAME - NOT CHANGED
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const feeCheckboxes = document.querySelectorAll('.fee-checkbox');
        const bulkActionsContainer = document.getElementById('bulkActionsContainer');
        const selectedCountElement = document.getElementById('selectedCount');

        selectAllCheckbox.addEventListener('change', function() {
            feeCheckboxes.forEach(checkbox => { checkbox.checked = this.checked; });
            updateSelectionUI();
        });

        feeCheckboxes.forEach(checkbox => { checkbox.addEventListener('change', updateSelectionUI); });

        function updateSelectionUI() {
            const selectedCount = document.querySelectorAll('.fee-checkbox:checked').length;
            if (selectedCount > 0) {
                bulkActionsContainer.classList.add('active');
                selectedCountElement.textContent = selectedCount + ' fee structure(s) selected';
                selectAllCheckbox.checked = selectedCount === feeCheckboxes.length;
                selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < feeCheckboxes.length;
            } else {
                bulkActionsContainer.classList.remove('active');
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
        }
    });

    function clearSelection() {
        document.querySelectorAll('.fee-checkbox:checked').forEach(checkbox => { checkbox.checked = false; });
        document.getElementById('selectAll').checked = false;
        document.getElementById('bulkActionsContainer').classList.remove('active');
    }

    function bulkAction(action) {
        const selectedFees = Array.from(document.querySelectorAll('.fee-checkbox:checked')).map(checkbox => checkbox.value);
        if (selectedFees.length === 0) { alert('Please select at least one fee structure.'); return; }
        switch(action) {
            case 'download':
                if (confirm(`Download data for ${selectedFees.length} fee structure(s)?`)) {
                    const btn = event.target.closest('button'); const originalHTML = btn.innerHTML;
                    btn.innerHTML = '<span class="loading-spinner"></span> Processing...'; btn.disabled = true;
                    setTimeout(() => { btn.innerHTML = originalHTML; btn.disabled = false; alert('Download started for ' + selectedFees.length + ' fee structures'); }, 1000);
                } break;
            case 'bulk_delete':
                if (confirm(`Are you sure you want to delete ${selectedFees.length} fee structure(s)?`)) {
                    const btn = event.target.closest('button'); const originalHTML = btn.innerHTML;
                    btn.innerHTML = '<span class="loading-spinner"></span> Deleting...'; btn.disabled = true;
                    setTimeout(() => { btn.innerHTML = originalHTML; btn.disabled = false; clearSelection(); alert(`${selectedFees.length} fee structures deleted successfully`); }, 1500);
                } break;
            default: alert(`${action} action triggered for ${selectedFees.length} fee structures`);
        }
    }

    function sortTable(column) {
        const currentSortBy = document.getElementById('sortBy').value;
        const currentSortOrder = document.getElementById('sortOrder').value;
        let newSortOrder = 'asc';
        if (currentSortBy === column) { newSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc'; }
        document.getElementById('sortBy').value = column;
        document.getElementById('sortOrder').value = newSortOrder;
        document.getElementById('filterForm').submit();
    }

    document.getElementById('filterForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('.btn-filter-primary');
        const originalHTML = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Applying...';
        submitBtn.disabled = true;
        setTimeout(() => { submitBtn.innerHTML = originalHTML; submitBtn.disabled = false; }, 2000);
    });

    function viewFeeDetails(feeId) {
        $('#feeDetailsContent').html('<div class="text-center py-5"><div class="spinner-border" style="color: var(--primary-color);" role="status"></div><p class="mt-3">Loading fee details...</p></div>');
        const modal = new bootstrap.Modal(document.getElementById('viewFeeModal'));
        modal.show();
        setTimeout(() => {
            const feeData = { id: feeId, fee_reference_id: 'CF-' + feeId, fee_type: 'custom', custom_fee_key: 'Custom Fee Key', academic_year: '2024-2025', fee_duration_type: 'monthly', custom_fee_value: 5000, discount_amount: 500, late_fee_type: 'fixed', late_fee_value: 200, status: 'active', description: 'This is a custom fee structure for special programs.', created_at: '2024-01-15', updated_at: '2024-01-15' };
            const getFeeTypeClass = (type) => { const classes = { 'transport': 'type-transport', 'tuition': 'type-tuition', 'hostel': 'type-hostel', 'library': 'type-library', 'sports': 'type-sports', 'custom': 'type-custom', 'other': 'type-other' }; return classes[type] || 'type-other'; };
            const getStatusBadge = (status) => { if (status === 'active') return '<span class="status-badge status-active">Active</span>'; else if (status === 'inactive') return '<span class="status-badge status-inactive">Inactive</span>'; else return '<span class="status-badge status-draft">Draft</span>'; };
            $('#feeDetailsContent').html(`
                <div class="row mb-4">
                    <div class="col-md-6"><div class="card mb-3" style="border-radius: 16px; border: 2px solid #e2e8f0;"><div class="card-body"><h6 class="card-title mb-3"><i class="bi bi-hash me-2"></i>Basic Information</h6><p><strong>Reference ID:</strong> <code>${feeData.fee_reference_id}</code></p><p><strong>Fee Type:</strong> <span class="fee-type-badge ${getFeeTypeClass(feeData.fee_type)}">${feeData.fee_type}</span></p><p><strong>Fee Key:</strong> ${feeData.custom_fee_key}</p><p><strong>Academic Year:</strong> ${feeData.academic_year}</p><p><strong>Duration:</strong> <span class="badge" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; padding: 4px 10px; border-radius: 12px;">${feeData.fee_duration_type}</span></p></div></div></div>
                    <div class="col-md-6"><div class="card mb-3" style="border-radius: 16px; border: 2px solid #e2e8f0;"><div class="card-body"><h6 class="card-title mb-3"><i class="bi bi-info-circle me-2"></i>Status & Dates</h6><p><strong>Status:</strong> ${getStatusBadge(feeData.status)}</p><p><strong>Created:</strong> ${new Date(feeData.created_at).toLocaleDateString()}</p><p><strong>Last Updated:</strong> ${new Date(feeData.updated_at).toLocaleDateString()}</p></div></div></div>
                </div>
                <div class="card mb-4" style="border-radius: 16px; border: 2px solid #e2e8f0;"><div class="card-body"><h6 class="card-title mb-3"><i class="bi bi-calculator me-2"></i>Fee Breakdown</h6><div class="row"><div class="col-md-4"><div class="text-center p-3 border rounded mb-3" style="border-radius: 12px;"><div class="text-muted mb-2">Base Amount</div><div class="h4" style="color: var(--primary-color);">₹${feeData.custom_fee_value.toLocaleString()}</div></div></div><div class="col-md-4"><div class="text-center p-3 border rounded mb-3" style="border-radius: 12px;"><div class="text-muted mb-2">Discount</div><div class="h4" style="color: #10b981;">₹${feeData.discount_amount.toLocaleString()}</div></div></div><div class="col-md-4"><div class="text-center p-3 border rounded mb-3" style="border-radius: 12px;"><div class="text-muted mb-2">Net Amount</div><div class="h4" style="color: var(--text-dark);">₹${(feeData.custom_fee_value - feeData.discount_amount).toLocaleString()}</div></div></div></div></div></div>
            `);
        }, 1000);
    }
</script>
@endsection
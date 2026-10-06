@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .page-header {
        background: var(--primary-gradient);
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header h1 {
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.5rem;
    }

    .page-header h1 i {
        background: rgba(255, 255, 255, 0.2);
        padding: 10px;
        border-radius: 12px;
        font-size: 1.3rem;
    }

    .btn-primary-custom {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.7rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary-custom:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        color: white;
    }

    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        border: 2px solid var(--border-color);
        margin-bottom: 1.5rem;
    }

    .table-container {
        background: white;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        border: 2px solid var(--border-color);
        overflow-x: auto;
    }

    .table {
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    .table th {
        background: #f8fafc;
        color: var(--text-dark);
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--border-color);
        padding: 0.8rem 0.8rem;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table td {
        padding: 0.8rem 0.8rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .table tbody tr {
        transition: var(--transition);
    }

    .table tbody tr:hover {
        background: #f8fafc;
    }

    .table .store-name {
        font-weight: 600;
        color: var(--text-dark);
    }

    .table .store-code {
        background: #dbeafe;
        color: #1e40af;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
    }

    .status-badge {
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }

    .status-badge.active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-badge.inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-badge.default {
        background: #fef3c7;
        color: #92400e;
    }

    .utilization-bar {
        width: 100%;
        min-width: 80px;
        height: 6px;
        background: #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 4px;
    }

    .utilization-bar .fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.6s ease;
    }

    .utilization-bar .fill.low {
        background: #10b981;
    }

    .utilization-bar .fill.medium {
        background: #f59e0b;
    }

    .utilization-bar .fill.high {
        background: #ef4444;
    }

    .btn-action {
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-action.view {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn-action.edit {
        background: #fef3c7;
        color: #92400e;
    }

    .btn-action.delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-action.toggle {
        background: #e5e7eb;
        color: #374151;
        border: none;
        padding: 0.25rem 0.6rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-action.toggle:hover {
        transform: translateY(-2px);
    }

    .btn-action.toggle.active-toggle {
        background: #d1fae5;
        color: #065f46;
    }

    .btn-action.toggle.inactive-toggle {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-action.logs {
        background: #e0e7ff;
        color: #3730a3;
    }

    .btn-action.stock {
        background: #d1fae5;
        color: #065f46;
    }

    .btn-action-group {
        display: flex;
        gap: 4px;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    .empty-state h4 {
        color: var(--text-dark);
        font-weight: 600;
    }

    .empty-state p {
        color: var(--text-muted);
    }

    .table-responsive-wrapper {
        position: relative;
        overflow-x: auto;
    }

    a {
        text-decoration: none;
    }

    .badge-count {
        background: #e5e7eb;
        color: var(--text-dark);
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .warehouse-name {
        color: var(--text-dark);
        font-weight: 500;
    }

    .contact-info {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .contact-info .phone {
        display: block;
    }

    .contact-info .email {
        display: block;
        font-size: 0.75rem;
    }

    .warehouse-missing {
        color: #dc3545;
        font-size: 0.7rem;
        display: block;
    }

    /* SweetAlert2 custom styles */
    .swal2-popup {
        border-radius: 16px !important;
    }

    .swal2-title {
        font-weight: 700 !important;
        color: #1e293b !important;
    }

    .swal2-content {
        color: #64748b !important;
    }

    .swal2-confirm {
        border-radius: 10px !important;
        font-weight: 600 !important;
        padding: 0.6rem 2rem !important;
    }

    .swal2-cancel {
        border-radius: 10px !important;
        font-weight: 600 !important;
        padding: 0.6rem 2rem !important;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .table-container {
            padding: 0.5rem;
        }

        .table {
            font-size: 0.8rem;
        }

        .table th,
        .table td {
            padding: 0.5rem;
        }

        .btn-action {
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
        }

        .btn-action-group {
            gap: 2px;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-store"></i>Store</h1>
            <p>Manage all your inventory stores</p>
        </div>
        <div>
            <a href="{{ route('inventory.stores.create') }}" class="btn-primary-custom">
                <i class="fas fa-plus-circle"></i> Add Store
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <form method="GET" action="{{ route('inventory.stores.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" 
                    placeholder="Search by name, code or contact..." 
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Warehouse</label>
                <select name="warehouse_id" class="form-select">
                    <option value="">All Warehouses</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" 
                            {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->warehouse_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Stores Table -->
    @if($stores->count() > 0)
        <div class="table-container">
            <div class="table-responsive-wrapper">
                <table class="table table-hover" id="storesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="sortable">Store Name</th>
                            <th class="sortable">Code</th>
                            <th class="sortable">Warehouse</th>
                            <th class="sortable">Contact</th>
                            <th class="sortable">Capacity</th>
                            <th class="sortable">Utilization</th>
                            <th class="sortable">Items</th>
                            <th class="sortable">Status</th>
                            <th class="sortable">Stock Summary</th>
                            <th class="sortable">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stores as $index => $store)
                            @php
                                // Fix for warehouse relationship
                                $warehouseName = 'N/A';
                                $warehouseMissing = false;
                                
                                if($store->warehouse) {
                                    $warehouseName = $store->warehouse->warehouse_name;
                                } else if($store->warehouse_id) {
                                    // Try to find warehouse directly if relationship failed
                                    try {
                                        $warehouse = \App\Models\Inventory\InventoryWarehouse::where('id', (int)$store->warehouse_id)
                                            ->where('institute_id', $store->institute_id)
                                            ->first();
                                        if($warehouse) {
                                            $warehouseName = $warehouse->warehouse_name;
                                            // Store it in relation for future use
                                            $store->setRelation('warehouse', $warehouse);
                                        } else {
                                            $warehouseMissing = true;
                                        }
                                    } catch(\Exception $e) {
                                        $warehouseMissing = true;
                                    }
                                } else {
                                    $warehouseMissing = true;
                                }
                            @endphp
                            <tr id="store-row-{{ $store->id }}">
                                <td>{{ $stores->firstItem() + $index }}</td>
                                <td>
                                    <span class="store-name">{{ $store->store_name }}</span>
                                    @if($store->is_default)
                                        <span class="status-badge default" style="font-size: 0.65rem; padding: 2px 8px; margin-left: 4px;">
                                            <i class="fas fa-star"></i> Default
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="store-code">{{ $store->store_code }}</span>
                                </td>
                                <td>
                                    <span class="warehouse-name">
                                        <i class="fas fa-warehouse" style="color: #4361ee; font-size: 0.75rem;"></i>
                                        {{ $warehouseName }}
                                    </span>
                                    @if($warehouseMissing)
                                        <span class="warehouse-missing">
                                            <i class="fas fa-exclamation-triangle"></i> Warehouse not found (ID: {{ $store->warehouse_id }})
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="contact-info">
                                        <strong>{{ $store->contact_person ?? 'N/A' }}</strong>
                                        @if($store->phone)
                                            <span class="phone"><i class="fas fa-phone" style="font-size: 0.7rem;"></i> {{ $store->phone }}</span>
                                        @endif
                                        @if($store->email)
                                            <span class="email"><i class="fas fa-envelope" style="font-size: 0.7rem;"></i> {{ $store->email }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-count">{{ $store->capacity ?? 0 }}</span>
                                    <small class="text-muted">units</small>
                                </td>
                                <td>
                                    <div>
                                        <span class="fw-bold">{{ $store->getUtilizationPercentageAttribute() }}%</span>
                                        <div class="utilization-bar">
                                            @php
                                                $percentage = $store->getUtilizationPercentageAttribute();
                                                $class = 'low';
                                                if ($percentage > 70) $class = 'high';
                                                elseif ($percentage > 40) $class = 'medium';
                                            @endphp
                                            <div class="fill {{ $class }}" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <i class="fas fa-boxes"></i> {{ $store->items_count }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $store->status ? 'active' : 'inactive' }}">
                                        <button type="button" class="btn-action toggle {{ $store->status ? 'active-toggle' : 'inactive-toggle' }}" 
                                            onclick="toggleStoreStatus({{ $store->id }}, {{ $store->status ? 'true' : 'false' }})">
                                            <i class="fas {{ $store->status ? 'fa-pause' : 'fa-play' }}"></i> 
                                            {{ $store->status ? 'Active' : 'Inactive' }}
                                        </button>
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('inventory.stores.stock-summary', $store->id) }}" class="btn-action stock" title="Stock Summary">
                                        <i class="fas fa-chart-bar"></i> Stock Summary
                                    </a>
                                </td>
                                <td>
                                    <div class="btn-action-group">
                                        <a href="{{ route('inventory.stores.view', $store->id) }}" class="btn-action view" title="View">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('inventory.stores.edit', $store->id) }}" class="btn-action edit" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button type="button" class="btn-action delete" onclick="deleteStore({{ $store->id }}, '{{ $store->store_name }}')" title="Delete">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <div>
                Showing {{ $stores->firstItem() }} to {{ $stores->lastItem() }} of {{ $stores->total() }} stores
            </div>
            <div>
                {{ $stores->appends(request()->query())->links() }}
            </div>
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-store"></i>
            <h4>No Stores Found</h4>
            <p>Start by creating your first store.</p>
            <a href="{{ route('inventory.stores.create') }}" class="d-none btn btn-primary mt-3">
                <i class="fas fa-plus-circle"></i> Create Store
            </a>
        </div>
    @endif
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // CSRF Token setup for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    /**
     * Toggle Store Status with Two-Step Confirmation
     */
    function toggleStoreStatus(storeId, currentStatus) {
        const action = currentStatus ? 'deactivate' : 'activate';
        const actionLabel = currentStatus ? 'Deactivate' : 'Activate';
        const icon = currentStatus ? 'warning' : 'info';
        const confirmButtonColor = currentStatus ? '#dc3545' : '#10b981';
        const statusText = currentStatus ? 'Active' : 'Inactive';
        const newStatusText = currentStatus ? 'Inactive' : 'Active';

        // Step 1: First confirmation - Ask if user wants to proceed
        Swal.fire({
            title: `${actionLabel} Store?`,
            html: `
                <div style="text-align: left; padding: 10px 0;">
                    <p style="margin-bottom: 10px;">You are about to <strong>${action}</strong> this store.</p>
                    <div style="background: #f8fafc; padding: 12px; border-radius: 8px; border-left: 4px solid ${confirmButtonColor};">
                        <p style="margin: 0; font-size: 0.9rem;">
                            <strong>Current Status:</strong> 
                            <span class="status-badge ${currentStatus ? 'active' : 'inactive'}" style="font-size: 0.8rem; padding: 2px 12px;">
                                ${statusText}
                            </span>
                            <br>
                            <strong>New Status:</strong> 
                            <span class="status-badge ${currentStatus ? 'inactive' : 'active'}" style="font-size: 0.8rem; padding: 2px 12px;">
                                ${newStatusText}
                            </span>
                        </p>
                    </div>
                    <p style="margin-top: 10px; color: #64748b; font-size: 0.85rem;">
                        <i class="fas fa-info-circle"></i> 
                        ${currentStatus ? 'Deactivating will make this store unavailable for inventory operations.' : 'Activating will make this store available for inventory operations.'}
                    </p>
                </div>
            `,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            cancelButtonColor: '#94a3b8',
            confirmButtonText: `<i class="fas fa-${currentStatus ? 'pause' : 'play'}"></i> Yes, ${actionLabel}`,
            cancelButtonText: '<i class="fas fa-times"></i> Cancel',
            reverseButtons: true,
            customClass: {
                popup: 'swal2-popup',
                confirmButton: 'swal2-confirm',
                cancelButton: 'swal2-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Step 2: Second confirmation - Final confirmation with store name
                Swal.fire({
                    title: `Are you absolutely sure?`,
                    html: `
                        <div style="text-align: left; padding: 10px 0;">
                            <p style="margin-bottom: 10px;">
                                Please confirm that you want to <strong>${action}</strong> this store.
                            </p>
                            <div style="background: #fef3c7; padding: 12px; border-radius: 8px; border-left: 4px solid #f59e0b;">
                                <p style="margin: 0; font-size: 0.9rem; color: #92400e;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    This action will ${currentStatus ? 'deactivate' : 'activate'} the store and 
                                    ${currentStatus ? 'prevent' : 'allow'} inventory operations.
                                </p>
                            </div>
                            <p style="margin-top: 10px; color: #64748b; font-size: 0.85rem;">
                                <i class="fas fa-arrow-right"></i> Click "Confirm" to proceed with the ${action}action.
                            </p>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: confirmButtonColor,
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: `<i class="fas fa-check"></i> Confirm ${actionLabel}`,
                    cancelButtonText: '<i class="fas fa-times"></i> Cancel',
                    reverseButtons: true,
                    customClass: {
                        popup: 'swal2-popup',
                        confirmButton: 'swal2-confirm',
                        cancelButton: 'swal2-cancel'
                    }
                }).then((secondResult) => {
                    if (secondResult.isConfirmed) {
                        // Proceed with the actual toggle
                        $.ajax({
                            url: '{{ route("inventory.stores.toggle-status", "") }}/' + storeId,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'POST'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Success!',
                                        html: `
                                            <div style="text-align: left; padding: 10px 0;">
                                                <p style="margin-bottom: 5px;">
                                                    Store status has been successfully 
                                                    <strong>${response.new_status ? 'activated' : 'deactivated'}</strong>.
                                                </p>
                                                <div style="background: #d1fae5; padding: 10px; border-radius: 8px; border-left: 4px solid #10b981;">
                                                    <p style="margin: 0; font-size: 0.9rem; color: #065f46;">
                                                        <i class="fas fa-check-circle"></i>
                                                        The store is now 
                                                        <span class="status-badge ${response.new_status ? 'active' : 'inactive'}" style="font-size: 0.8rem; padding: 2px 12px;">
                                                            ${response.new_status ? 'Active' : 'Inactive'}
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        `,
                                        icon: 'success',
                                        confirmButtonColor: '#10b981',
                                        confirmButtonText: '<i class="fas fa-check"></i> OK',
                                        timer: 3000,
                                        timerProgressBar: true
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: response.message || 'Failed to toggle store status.',
                                        icon: 'error',
                                        confirmButtonColor: '#dc3545',
                                        confirmButtonText: '<i class="fas fa-times"></i> OK'
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'An error occurred while toggling the store status.',
                                    icon: 'error',
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: '<i class="fas fa-times"></i> OK'
                                });
                            }
                        });
                    }
                });
            }
        });
    }

    /**
     * Delete Store with Two-Step Confirmation
     */
    function deleteStore(storeId, storeName) {
        // Step 1: First confirmation - Ask if user wants to proceed
        Swal.fire({
            title: 'Delete Store?',
            html: `
                <div style="text-align: left; padding: 10px 0;">
                    <p style="margin-bottom: 10px;">
                        You are about to delete the store <strong>"${storeName}"</strong>.
                    </p>
                    <div style="background: #fee2e2; padding: 12px; border-radius: 8px; border-left: 4px solid #dc3545;">
                        <p style="margin: 0; font-size: 0.9rem; color: #991b1b;">
                            <i class="fas fa-exclamation-triangle"></i>
                            This action cannot be undone. All data associated with this store will be permanently removed.
                        </p>
                    </div>
                    <p style="margin-top: 10px; color: #64748b; font-size: 0.85rem;">
                        <i class="fas fa-info-circle"></i> 
                        This includes inventory items, stock records, and transaction history.
                    </p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="fas fa-trash"></i> Yes, Delete',
            cancelButtonText: '<i class="fas fa-times"></i> Cancel',
            reverseButtons: true,
            customClass: {
                popup: 'swal2-popup',
                confirmButton: 'swal2-confirm',
                cancelButton: 'swal2-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Step 2: Second confirmation - Final confirmation with store name
                Swal.fire({
                    title: 'Are you absolutely sure?',
                    html: `
                        <div style="text-align: left; padding: 10px 0;">
                            <p style="margin-bottom: 10px;">
                                Please confirm that you want to permanently delete <strong>"${storeName}"</strong>.
                            </p>
                            <div style="background: #fef3c7; padding: 12px; border-radius: 8px; border-left: 4px solid #f59e0b;">
                                <p style="margin: 0; font-size: 0.9rem; color: #92400e;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    This is a permanent action and cannot be reversed.
                                </p>
                            </div>
                            <p style="margin-top: 10px; color: #64748b; font-size: 0.85rem;">
                                <i class="fas fa-arrow-right"></i> Type <strong>"${storeName}"</strong> to confirm deletion.
                            </p>
                            <div style="margin-top: 10px;">
                                <input type="text" id="confirmDeleteInput" class="form-control" 
                                    placeholder="Type the store name to confirm..." 
                                    style="border: 2px solid var(--border-color); border-radius: 10px; padding: 0.6rem 1rem;">
                            </div>
                        </div>
                    `,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: '<i class="fas fa-trash"></i> Permanently Delete',
                    cancelButtonText: '<i class="fas fa-times"></i> Cancel',
                    reverseButtons: true,
                    preConfirm: () => {
                        const input = document.getElementById('confirmDeleteInput');
                        if (!input) return false;
                        const value = input.value.trim();
                        if (value !== storeName) {
                            Swal.showValidationMessage(
                                `<i class="fas fa-times-circle" style="color: #dc3545;"></i> 
                                Please type the store name correctly to confirm deletion.`
                            );
                            return false;
                        }
                        return true;
                    },
                    customClass: {
                        popup: 'swal2-popup',
                        confirmButton: 'swal2-confirm',
                        cancelButton: 'swal2-cancel'
                    }
                }).then((secondResult) => {
                    if (secondResult.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Deleting Store...',
                            html: 'Please wait while we delete the store.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Proceed with the actual deletion
                        $.ajax({
                            url: '{{ route("inventory.stores.delete", "") }}/' + storeId,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        html: `
                                            <div style="text-align: left; padding: 10px 0;">
                                                <p style="margin-bottom: 5px;">
                                                    Store <strong>"${storeName}"</strong> has been successfully deleted.
                                                </p>
                                                <div style="background: #d1fae5; padding: 10px; border-radius: 8px; border-left: 4px solid #10b981;">
                                                    <p style="margin: 0; font-size: 0.9rem; color: #065f46;">
                                                        <i class="fas fa-check-circle"></i>
                                                        The store has been permanently removed from the system.
                                                    </p>
                                                </div>
                                            </div>
                                        `,
                                        icon: 'success',
                                        confirmButtonColor: '#10b981',
                                        confirmButtonText: '<i class="fas fa-check"></i> OK',
                                        timer: 3000,
                                        timerProgressBar: true
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: response.message || 'Failed to delete store.',
                                        icon: 'error',
                                        confirmButtonColor: '#dc3545',
                                        confirmButtonText: '<i class="fas fa-times"></i> OK'
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'An error occurred while deleting the store.',
                                    icon: 'error',
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: '<i class="fas fa-times"></i> OK'
                                });
                            }
                        });
                    }
                });
            }
        });
    }
</script>
@endsection
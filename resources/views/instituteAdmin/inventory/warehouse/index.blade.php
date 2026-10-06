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

    .page-header p {
        opacity: 0.9;
        margin: 0;
    }

    .btn-back {
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

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        color: white;
    }

    .btn-create {
        background: white;
        color: var(--primary-color);
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

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
        color: var(--primary-color);
    }

    .btn-stock {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .btn-stock:hover {
        background: #bfdbfe;
    }

    .btn-logs {
        background: #fef3c7;
        color: #92400e;
        /* padding: 0.7rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none; */
    }

    .btn-logs:hover {
        background: #fde68a;
        /* transform: translateY(-2px);
        color: #92400e; */
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        border: 2px solid var(--border-color);
        transition: var(--transition);
        text-align: center;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--card-shadow);
        border-color: #4361ee;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #4361ee;
    }

    .stat-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
    }

    .utilization-bar {
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 8px;
    }

    .utilization-bar .fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.5s ease;
    }

    .table-responsive-custom {
        overflow-x: auto;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        background: white;
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    .table-custom thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 1rem 1.25rem;
        border-bottom: 2px solid var(--border-color);
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .table-custom tbody td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
        background-color: white;
    }

    .table-custom tbody tr:last-child td {
        border-bottom: none;
    }

    .table-custom tbody tr {
        transition: var(--transition);
    }

    .table-custom tbody tr:hover {
        background-color: #f8fafc;
    }

    .btn-action {
        border: none;
        border-radius: 8px;
        padding: 0.35rem 0.75rem;
        font-size: 0.7rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-view {
        background: #e0e7ff;
        color: #4338ca;
    }
    .btn-view:hover {
        background: #c7d2fe;
    }

    .btn-edit {
        background: #fef3c7;
        color: #92400e;
    }
    .btn-edit:hover {
        background: #fde68a;
    }

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }
    .btn-delete:hover {
        background: #fecaca;
    }

    .btn-delete-disabled {
        background: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .btn-toggle {
        border: none;
        border-radius: 8px;
        padding: 0.35rem 0.75rem;
        font-size: 0.65rem;
        font-weight: 700;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-toggle:hover {
        transform: translateY(-2px);
    }

    .btn-toggle-active {
        background: #dcfce7;
        color: #15803d;
    }
    .btn-toggle-active:hover {
        background: #bbf7d0;
    }

    .btn-toggle-inactive {
        background: #fee2e2;
        color: #991b1b;
    }
    .btn-toggle-inactive:hover {
        background: #fecaca;
    }

    .badge-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-active {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-default {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .empty-state {
        text-align: center;
        padding: 3.5rem 1.5rem;
    }

    .empty-state .empty-icon {
        font-size: 3.5rem;
        color: #e2e8f0;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: var(--text-dark);
        font-weight: 600;
    }

    .empty-state p {
        color: var(--text-muted);
        max-width: 400px;
        margin: 0 auto;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .stat-card {
            padding: 1rem;
        }

        .stat-number {
            font-size: 1.5rem;
        }

        .table-custom thead th,
        .table-custom tbody td {
            padding: 0.75rem 0.9rem;
            font-size: 0.8rem;
        }

        .action-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.3rem;
        }

        .btn-action,
        .btn-toggle {
            font-size: 0.6rem;
            padding: 0.25rem 0.5rem;
        }
    }

    .action-group {
        display: flex;
        gap: 0.3rem;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-warehouse"></i> Warehouse</h1>
            <p>Manage all your warehouse locations</p>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('inventory.dashboard') }}" class="d-none btn-back">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('inventory.warehouses.create') }}" class="btn-create">
                <i class="fas fa-plus-circle"></i> Add Warehouse
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-number">{{ $warehouses->count() }}</div>
                <div class="stat-label">Total Warehouses</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-number">{{ $warehouses->where('status', 1)->count() }}</div>
                <div class="stat-label">Active Warehouses</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-number">{{ $warehouses->sum('items_count') }}</div>
                <div class="stat-label">Total Items</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-number">{{ $warehouses->where('is_default', 1)->count() }}</div>
                <div class="stat-label">Default Warehouse</div>
            </div>
        </div>
    </div>

    <!-- Search/Filter -->
    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" id="searchWarehouse" class="form-control" placeholder="🔍 Search by name or code...">
        </div>
        <div class="col-md-3">
            <select id="filterStatus" class="form-control">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <div class="col-md-3">
            <select id="filterDefault" class="form-control">
                <option value="">All Warehouses</option>
                <option value="1">Default Only</option>
            </select>
        </div>
        <div class="col-md-2">
            <button onclick="resetFilters()" class="btn btn-secondary w-100" style="border-radius: 12px;">
                <i class="fas fa-undo"></i> Reset
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive-custom">
        <table class="table-custom" id="warehouseTable">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th class="sortable">Code</th>
                    <th class="sortable">Warehouse Name</th>
                    <th class="sortable">Contact</th>
                    <th class="sortable">Items</th>
                    <th class="sortable">Utilization</th>
                    <th class="sortable">Status</th>
                    <th class="sortable">Default</th>
                    <th class="sortable">Stock Summary</th>
                    <th class="sortable">Logs</th>
                    <th class="sortable">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($warehouses as $warehouse)
                <tr data-status="{{ $warehouse->status }}" data-default="{{ $warehouse->is_default }}">
                    <td>{{ $loop->iteration }}</td>
                    <td><span class="badge bg-secondary">{{ $warehouse->warehouse_code }}</span></td>
                    <td>
                        <strong>{{ $warehouse->warehouse_name }}</strong>
                        @if($warehouse->address)
                            <br><small class="text-muted">{{ Str::limit($warehouse->address, 50) }}</small>
                        @endif
                    </td>
                    <td>
                        @if($warehouse->contact_person)
                            <div>{{ $warehouse->contact_person }}</div>
                            <small class="text-muted">{{ $warehouse->phone ?? '' }}</small>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $warehouse->items_count ?? 0 }}</span>
                    </td>
                    <td>
                        @if($warehouse->capacity)
                            <div class="d-flex justify-content-between">
                                <span>{{ number_format($warehouse->current_utilization ?? 0) }}</span>
                                <span>/ {{ number_format($warehouse->capacity) }}</span>
                            </div>
                            <div class="utilization-bar">
                                @php
                                    $percentage = $warehouse->capacity > 0 
                                        ? min(100, ($warehouse->current_utilization / $warehouse->capacity) * 100) 
                                        : 0;
                                    $color = $percentage > 80 ? '#ef4444' : ($percentage > 60 ? '#f59e0b' : '#10b981');
                                @endphp
                                <div class="fill" style="width: {{ $percentage }}%; background: {{ $color }};"></div>
                            </div>
                            <small class="text-muted">{{ round($percentage, 1) }}% utilized</small>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        {{-- Status Toggle --}}
                        @if($warehouse->status)
                            <button onclick="toggleStatus({{ $warehouse->id }}, 0)" 
                                    class="btn-toggle btn-toggle-active" title="Deactivate this warehouse">
                                <i class="fas fa-toggle-on"></i> Active
                            </button>
                        @else
                            <button onclick="toggleStatus({{ $warehouse->id }}, 1)" 
                                    class="btn-toggle btn-toggle-inactive" title="Activate this warehouse">
                                <i class="fas fa-toggle-off"></i> Inactive
                            </button>
                        @endif
                    </td>
                    <td>
                        @if($warehouse->is_default)
                            <span class="badge badge-default"><i class="fas fa-check"></i> Default</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        {{-- Stock Summary --}}
                        <a href="{{ route('inventory.warehouses.stock-summary', $warehouse->id) }}" 
                        class="btn-action btn-stock" title="View Stock Summary">
                            <i class="fas fa-boxes"></i> View Stock Summary
                        </a>
                    </td>
                    <td>
                        {{-- Logs --}}
                        <a href="{{ route('inventory.warehouses.logs', $warehouse->id) }}" class="btn-action btn-logs">
                            <i class="fas fa-history"></i> Logs
                        </a>
                    </td>
                    <td>
                        <div class="action-group">
                            {{-- View --}}
                            <a href="{{ route('inventory.warehouses.view', $warehouse->id) }}" 
                            class="btn-action btn-view" title="View Details">
                                <i class="fas fa-eye"></i> View
                            </a>

                            {{-- Edit --}}
                            <a href="{{ route('inventory.warehouses.edit', $warehouse->id) }}" 
                            class="btn-action btn-edit" title="Edit">
                                <i class="fas fa-pen"></i> Edit
                            </a>

                            {{-- Delete --}}
                            @if(!$warehouse->is_default && $warehouse->items_count == 0)
                                <button onclick="deleteWarehouse({{ $warehouse->id }})" 
                                        class="btn-action btn-delete" title="Delete">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            @else
                                <button class="btn-action btn-delete-disabled" disabled title="Cannot delete">
                                    <i class="fas fa-lock"></i> Delete
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-warehouse"></i></div>
                            <h5>No Warehouses Found</h5>
                            <p>Click "Add Warehouse" to create your first warehouse location.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Status Toggle Confirmation Modal --}}
<div class="modal fade" id="statusToggleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
            <div class="modal-header" style="border-bottom: 2px solid #fef3c7; background: #fffbeb; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title" style="color: #92400e; font-weight: 700;">
                    <i class="fas fa-exclamation-triangle" style="color: #f59e0b;"></i>
                    Confirm Status Change
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <i class="fas fa-exchange-alt" style="font-size: 3rem; color: #f59e0b;"></i>
                </div>
                <h6 style="color: var(--text-dark); font-weight: 600; margin-bottom: 0.5rem; text-align: center;">
                    Are you sure you want to <span id="statusActionText" style="font-weight: 700;"></span> this warehouse?
                </h6>
                <p style="color: var(--text-muted); text-align: center; font-size: 0.9rem;" id="statusDescription">
                    This will <span id="statusEffectText"></span> the warehouse <strong id="warehouseNameDisplay"></strong> and make it <span id="statusResultText"></span> for inventory operations.
                </p>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 12px; border: 1px solid var(--border-color); margin-top: 1rem;">
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">
                        <i class="fas fa-info-circle"></i> 
                        <span id="statusInfoText"></span>
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 2px solid var(--border-color); background: #f8fafc; border-radius: 0 0 16px 16px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 0.6rem 2rem; border-radius: 10px;">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" id="confirmStatusToggle" style="padding: 0.6rem 2rem; border-radius: 10px; background: var(--primary-gradient); color: white; border: none; font-weight: 600;">
                    <i class="fas fa-check"></i> Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let toggleWarehouseId = null;
    let toggleNewStatus = null;
    let warehouseName = '';

    // ============================================
    // SEARCH & FILTER
    // ============================================

    document.getElementById('searchWarehouse').addEventListener('keyup', filterTable);
    document.getElementById('filterStatus').addEventListener('change', filterTable);
    document.getElementById('filterDefault').addEventListener('change', filterTable);

    function filterTable() {
        const search = document.getElementById('searchWarehouse').value.toLowerCase();
        const status = document.getElementById('filterStatus').value;
        const isDefault = document.getElementById('filterDefault').value;

        const rows = document.querySelectorAll('#warehouseTable tbody tr');

        rows.forEach(row => {
            const name = row.querySelector('td:nth-child(3)')?.textContent?.toLowerCase() || '';
            const code = row.querySelector('td:nth-child(2)')?.textContent?.toLowerCase() || '';
            const rowStatus = row.dataset.status || '';
            const rowDefault = row.dataset.default || '';

            let show = true;

            if (search && !name.includes(search) && !code.includes(search)) {
                show = false;
            }

            if (status !== '' && rowStatus !== status) {
                show = false;
            }

            if (isDefault === '1' && rowDefault !== '1') {
                show = false;
            }

            row.style.display = show ? '' : 'none';
        });
    }

    function resetFilters() {
        document.getElementById('searchWarehouse').value = '';
        document.getElementById('filterStatus').value = '';
        document.getElementById('filterDefault').value = '';
        filterTable();
    }

    // ============================================
    // STATUS TOGGLE
    // ============================================

    function toggleStatus(id, newStatus) {
        toggleWarehouseId = id;
        toggleNewStatus = newStatus;

        // Get warehouse name from the row
        const rows = document.querySelectorAll('#warehouseTable tbody tr');
        let name = '';
        rows.forEach(row => {
            if (row.dataset.warehouseId == id) {
                name = row.querySelector('td:nth-child(3)')?.textContent?.trim() || '';
            }
        });

        warehouseName = name;

        const modal = document.getElementById('statusToggleModal');
        const actionText = document.getElementById('statusActionText');
        const effectText = document.getElementById('statusEffectText');
        const resultText = document.getElementById('statusResultText');
        const infoText = document.getElementById('statusInfoText');
        const nameDisplay = document.getElementById('warehouseNameDisplay');

        nameDisplay.textContent = name || 'this warehouse';

        if (newStatus == 1) {
            actionText.textContent = 'ACTIVATE';
            actionText.style.color = '#15803d';
            effectText.textContent = 'activate';
            resultText.textContent = 'available';
            infoText.textContent = 'Activated warehouses can be used for inventory operations and transfers.';
        } else {
            actionText.textContent = 'DEACTIVATE';
            actionText.style.color = '#991b1b';
            effectText.textContent = 'deactivate';
            resultText.textContent = 'unavailable';
            infoText.textContent = 'Deactivated warehouses will not be available for new inventory operations. Existing items will remain but cannot be moved to this warehouse.';
        }

        const bootstrap = window.bootstrap;
        const modalInstance = new bootstrap.Modal(modal);
        modalInstance.show();
    }

    document.getElementById('confirmStatusToggle').addEventListener('click', function() {
        if (!toggleWarehouseId || toggleNewStatus === null) return;

        const modal = document.getElementById('statusToggleModal');
        const bootstrap = window.bootstrap;
        const modalInstance = bootstrap.Modal.getInstance(modal);
        if (modalInstance) {
            modalInstance.hide();
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('inventory.warehouses.toggle-status', '') }}/${toggleWarehouseId}`;
        form.innerHTML = `
            @csrf
            @method('POST')
        `;
        document.body.appendChild(form);
        form.submit();
    });

    // ============================================
    // DELETE WAREHOUSE
    // ============================================

    function deleteWarehouse(id) {
        if (confirm('⚠️ Are you sure you want to delete this warehouse?\n\nThis action cannot be undone.')) {
            fetch(`/inventory/warehouses/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Failed to delete warehouse');
                }
            })
            .catch(error => {
                alert('Error deleting warehouse');
                console.error(error);
            });
        }
    }
</script>
@endsection
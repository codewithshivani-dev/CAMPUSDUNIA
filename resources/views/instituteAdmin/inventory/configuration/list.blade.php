@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .page-header {
        background: var(--primary-gradient);
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
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

    .btn-create {
        background: white;
        color: var(--primary-color);
        padding: 0.7rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        transition: all 0.3s;
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

    .btn-back {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.7rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        transition: all 0.3s;
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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        border: 2px solid var(--border-color);
        text-align: center;
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--card-shadow);
        border-color: var(--primary-color);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    .stat-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
    }

    .table-container {
        background: white;
        border-radius: 20px;
        /* padding: 1.5rem; */
        border: 2px solid var(--border-color);
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .table th {
        background: #f8fafc;
        padding: 0.75rem 1rem;
        text-align: left;
        font-weight: 700;
        color: var(--text-dark);
        border-bottom: 2px solid var(--border-color);
        white-space: nowrap;
    }

    .table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    .table tr:hover {
        background: #f8fafc;
    }

    .config-name {
        font-weight: 600;
        color: var(--text-dark);
    }

    .config-code {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-family: monospace;
        background: #f1f5f9;
        padding: 0.2rem 0.5rem;
        border-radius: 6px;
    }

    .badge-method {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        border-radius: 12px;
        font-size: 0.65rem;
        font-weight: 700;
        background: var(--primary-gradient);
        color: white;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        border: none;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
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

    .btn-logs {
        background: #fef3c7;
        color: #92400e;
    }

    .btn-logs:hover {
        background: #fde68a;
    }

    .btn-status-toggle {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .btn-status-toggle:hover {
        background: #bfdbfe;
    }

    .btn-toggle {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        border: none;
        font-size: 0.7rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
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

    /* Hidden Edit/Delete buttons (for future use) */
    .btn-edit-hidden {
        background: #d1fae5;
        color: #065f46;
        display: none !important;
    }

    .btn-edit-hidden:hover {
        background: #a7f3d0;
    }

    .btn-delete-hidden {
        background: #fee2e2;
        color: #991b1b;
        display: none !important;
    }

    .btn-delete-hidden:hover {
        background: #fecaca;
    }

    /* Show hidden buttons only for super admin or specific role - future implementation */
    .show-edit-delete .btn-edit-hidden,
    .show-edit-delete .btn-delete-hidden {
        display: inline-flex !important;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 3rem;
        color: var(--border-color);
        margin-bottom: 1rem;
    }

    .empty-state h4 {
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .search-bar {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .search-input {
        flex: 1;
        min-width: 200px;
        padding: 0.7rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .feature-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }

    .feature-badge {
        background: #e0e7ff;
        color: #4338ca;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }

    .no-features {
        color: var(--text-muted);
        font-size: 0.8rem;
        font-style: italic;
    }

    .alert-modern {
        border: none;
        border-radius: 14px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideDown 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .alert-modern.alert-success {
        background: #f0fdf4;
        color: #166534;
        border-left: 4px solid #22c55e;
    }

    .alert-modern.alert-danger {
        background: #fef2f2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }

    .alert-modern i {
        font-size: 1.25rem;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .table-container {
            padding: 0.5rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 0.3rem;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }

        .feature-badges {
            flex-direction: column;
            gap: 3px;
        }

        .feature-badge {
            font-size: 0.6rem;
            padding: 1px 8px;
            text-align: center;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-cogs"></i> Inventory Configurations</h1>
            <p>Manage all your inventory configurations</p>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('inventory.dashboard') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('inventory.configuration.create') }}" class="btn-create">
                <i class="fas fa-plus-circle"></i> Create Configuration
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert-modern alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.7rem;"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-modern alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.7rem;"></button>
        </div>
    @endif

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $configurations->count() }}</div>
            <div class="stat-label">Total Configurations</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $configurations->where('status', 'active')->count() }}</div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $configurations->where('status', 'inactive')->count() }}</div>
            <div class="stat-label">Inactive</div>
        </div>
        @foreach($configurations as $configuration)
            <div class="stat-card">
                <div class="stat-number">{{ $configuration->costing_method }}</div>
                <div class="stat-label">Costing Method</div>
            </div>
        @endforeach
    </div>

    <!-- Search -->
    <div class="search-bar">
        <input type="text" class="search-input" id="searchInput" placeholder="🔍 Search by name or code...">
        <select class="search-input" id="methodFilter" style="max-width: 200px;">
            <option value="">All Methods</option>
            <option value="FIFO">FIFO</option>
            <option value="LIFO">LIFO</option>
            <option value="WEIGHTED_AVERAGE">Weighted Average</option>
        </select>
        <select class="search-input" id="statusFilter" style="max-width: 150px;">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
        <button onclick="resetFilters()" class="btn-back" style="background: #f1f5f9; color: #475569; padding: 0.7rem 1.5rem; border-radius: 12px; font-weight: 600; border: none; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
            <i class="fas fa-undo"></i> Reset
        </button>
    </div>

    <!-- Table -->
    <div class="table-container">
        <table class="table" id="configTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="sortable">Name / Code</th>
                    <th class="sortable">Costing Method</th>
                    <th class="sortable">Features</th>
                    <th class="sortable">Status</th>
                    <th class="sortable">Created</th>
                    <th class="sortable">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($configurations as $index => $config)
                <tr data-status="{{ $config->status }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div class="config-name">{{ $config->configuration_name }}</div>
                        <span class="config-code">{{ $config->configuration_code }}</span>
                    </td>
                    <td>
                        <span class="badge-method">{{ str_replace('_', ' ', $config->costing_method) }}</span>
                    </td>
                    <td>
                        @php
                            $features = [];
                            if($config->multi_warehouse) $features[] = 'Multi Warehouse';
                            if($config->barcode_enabled) $features[] = 'Barcode';
                            if($config->qr_enabled) $features[] = 'QR Code';
                            if($config->batch_tracking) $features[] = 'Batch Tracking';
                            if($config->serial_tracking) $features[] = 'Serial Tracking';
                        @endphp

                        @if(count($features) > 0)
                            <div class="feature-badges">
                                @foreach($features as $feature)
                                    <span class="feature-badge">{{ $feature }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="no-features">No features</span>
                        @endif
                    </td>
                    <td>
                        <button onclick="toggleStatus({{ $config->id }}, '{{ $config->status === 'active' ? 'inactive' : 'active' }}')" 
                                class="btn-toggle {{ $config->status === 'active' ? 'btn-toggle-active' : 'btn-toggle-inactive' }}" 
                                title="{{ $config->status === 'active' ? 'Click to deactivate' : 'Click to activate' }}">
                            <i class="fas {{ $config->status === 'active' ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                            {{ $config->status === 'active' ? 'Active' : 'Inactive' }}
                        </button>
                    </td>
                    <td style="font-size: 0.8rem; color: var(--text-muted);">
                        {{ $config->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('inventory.configuration.view', $config->id) }}" 
                               class="btn-action btn-view" title="View">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('inventory.configuration.logs', $config->id) }}" 
                               class="btn-action btn-logs" title="Logs">
                                <i class="fas fa-history"></i> Logs
                            </a>
                            <button 
                                onclick="toggleStatus({{ $config->id }}, '{{ $config->status === 'active' ? 'inactive' : 'active' }}')"
                                class="btn-action btn-status-toggle" 
                                title="{{ $config->status === 'active' ? 'Deactivate' : 'Activate' }} Configuration"
                            >
                                <i class="fas {{ $config->status === 'active' ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                {{ $config->status === 'active' ? 'Deactivate' : 'Activate' }}
                            </button>

                            {{-- Hidden Edit button (for future use) --}}
                            <a href="{{ route('inventory.configuration.edit', $config->id) }}" 
                               class="btn-action btn-edit-hidden" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            {{-- Hidden Delete button (for future use) --}}
                            @if($config->canBeDeleted())
                                <button onclick="deleteConfig({{ $config->id }})" 
                                        class="btn-action btn-delete-hidden" title="Delete">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            @else
                                <button onclick="showDeleteBlockedMessage({{ $config->id }})" 
                                        class="btn-action btn-delete-hidden" title="Cannot delete" 
                                        style="background: #fef3c7; color: #92400e; cursor: not-allowed; opacity: 0.7;">
                                    <i class="fas fa-lock"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-cogs"></i>
                            <h4>No Configurations Found</h4>
                            <p>Create your first inventory configuration to get started.</p>
                            <a href="{{ route('inventory.configuration.create') }}" class="d-none btn-create" style="display: inline-block; margin-top: 1rem;">
                                <i class="fas fa-plus-circle"></i> Create Configuration
                            </a>
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
                    Are you sure you want to <span id="statusActionText" style="font-weight: 700;"></span> this configuration?
                </h6>
                <p style="color: var(--text-muted); text-align: center; font-size: 0.9rem;" id="statusDescription">
                    This will <span id="statusEffectText"></span> the configuration <strong id="statusConfigName"></strong> and make it <span id="statusResultText"></span> for use in inventory operations.
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

{{-- Delete Blocked Modal --}}
<div class="modal fade" id="deleteBlockedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
            <div class="modal-header" style="border-bottom: 2px solid #fef3c7; background: #fffbeb; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title" style="color: #92400e; font-weight: 700;">
                    <i class="fas fa-lock" style="color: #92400e;"></i> Cannot Delete Configuration
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #f59e0b;"></i>
                </div>
                <h6 style="color: var(--text-dark); font-weight: 600; margin-bottom: 1rem; text-align: center;">
                    This configuration cannot be deleted because it has associated data.
                </h6>
                <div id="blockerMessages" style="margin-bottom: 1.5rem;">
                    <!-- Dynamic messages will be inserted here -->
                </div>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 12px; border: 1px solid var(--border-color);">
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">
                        <i class="fas fa-info-circle"></i> 
                        To delete this configuration, first remove all associated data:
                    </p>
                    <ul style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--text-dark); padding-left: 1.5rem;">
                        <li>Delete all categories using this configuration</li>
                        <li>Delete all items under those categories</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 2px solid var(--border-color); background: #f8fafc; border-radius: 0 0 16px 16px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 0.6rem 2rem; border-radius: 10px;">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // ============================================
    // FILTER FUNCTIONALITY
    // ============================================

    document.getElementById('searchInput').addEventListener('keyup', function() {
        filterTable();
    });

    document.getElementById('methodFilter').addEventListener('change', function() {
        filterTable();
    });

    document.getElementById('statusFilter').addEventListener('change', function() {
        filterTable();
    });

    function filterTable() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const method = document.getElementById('methodFilter').value;
        const status = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('#configTable tbody tr');
        
        rows.forEach(row => {
            const name = row.querySelector('.config-name')?.textContent?.toLowerCase() || '';
            const code = row.querySelector('.config-code')?.textContent?.toLowerCase() || '';
            const methodText = row.querySelector('.badge-method')?.textContent || '';
            const rowStatus = row.dataset.status || '';
            
            let show = true;
            
            if (search && !name.includes(search) && !code.includes(search)) {
                show = false;
            }
            
            if (method && methodText !== method) {
                show = false;
            }

            if (status && rowStatus !== status) {
                show = false;
            }
            
            row.style.display = show ? '' : 'none';
        });
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('methodFilter').value = '';
        document.getElementById('statusFilter').value = '';
        filterTable();
    }

    // ============================================
    // STATUS TOGGLE FUNCTIONALITY
    // ============================================

    let toggleConfigId = null;
    let toggleNewStatus = null;

    function toggleStatus(id, newStatus) {
        toggleConfigId = id;
        toggleNewStatus = newStatus;

        const modal = document.getElementById('statusToggleModal');
        const actionText = document.getElementById('statusActionText');
        const effectText = document.getElementById('statusEffectText');
        const resultText = document.getElementById('statusResultText');
        const infoText = document.getElementById('statusInfoText');
        const configName = document.getElementById('statusConfigName');

        // Get config name from the row
        const row = document.querySelector(`#configTable tbody tr`);
        // Find the row with matching status toggle button
        const rows = document.querySelectorAll('#configTable tbody tr');
        let name = 'this configuration';
        rows.forEach(r => {
            const btn = r.querySelector(`button[onclick*="toggleStatus(${id},"]`);
            if (btn) {
                const nameEl = r.querySelector('.config-name');
                if (nameEl) name = `"${nameEl.textContent.trim()}"`;
            }
        });
        configName.textContent = name;

        if (newStatus === 'active') {
            actionText.textContent = 'ACTIVATE';
            actionText.style.color = '#15803d';
            effectText.textContent = 'activate';
            resultText.textContent = 'available';
            infoText.textContent = 'Activated configurations can be used in inventory operations and category assignments.';
        } else {
            actionText.textContent = 'DEACTIVATE';
            actionText.style.color = '#991b1b';
            effectText.textContent = 'deactivate';
            resultText.textContent = 'unavailable';
            infoText.textContent = 'Deactivated configurations will not be available for new categories or inventory operations. Existing data will remain intact.';
        }

        const bootstrap = window.bootstrap;
        const modalInstance = new bootstrap.Modal(modal);
        modalInstance.show();
    }

    document.getElementById('confirmStatusToggle').addEventListener('click', function() {
        if (!toggleConfigId || !toggleNewStatus) return;

        const modal = document.getElementById('statusToggleModal');
        const bootstrap = window.bootstrap;
        const modalInstance = bootstrap.Modal.getInstance(modal);
        if (modalInstance) {
            modalInstance.hide();
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('inventory.configuration.toggle-status', '') }}/${toggleConfigId}`;
        form.innerHTML = `
            @csrf
            @method('POST')
        `;
        document.body.appendChild(form);
        form.submit();
    });

    // ============================================
    // DELETE CONFIGURATION (Hidden - for future use)
    // ============================================

    function deleteConfig(id) {
        if (confirm('⚠️ Are you sure you want to delete this configuration?\n\nThis action cannot be undone.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('inventory.configuration.delete', '') }}/${id}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    // ============================================
    // DELETE BLOCKED MODAL
    // ============================================

    function showDeleteBlockedMessage(id) {
        fetch(`{{ route('inventory.configuration.check-delete', '') }}/${id}`)
            .then(response => response.json())
            .then(data => {
                const modal = document.getElementById('deleteBlockedModal');
                const messagesContainer = document.getElementById('blockerMessages');
                
                messagesContainer.innerHTML = '';
                
                if (data.blockers && data.blockers.length > 0) {
                    data.blockers.forEach(blocker => {
                        const div = document.createElement('div');
                        div.style.cssText = `
                            display: flex;
                            align-items: center;
                            gap: 12px;
                            padding: 10px 12px;
                            margin-bottom: 8px;
                            background: #fef3c7;
                            border-radius: 8px;
                            border-left: 4px solid #f59e0b;
                        `;
                        const iconMap = {
                            'categories': 'fa-folder-tree',
                            'items': 'fa-box'
                        };
                        div.innerHTML = `
                            <i class="fas ${iconMap[blocker.type] || 'fa-exclamation-circle'}" style="color: #92400e;"></i>
                            <span style="color: var(--text-dark);">
                                <strong>${blocker.count}</strong> ${blocker.type.replace('_', ' ')} found
                                <span style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-top: 2px;">
                                    ${blocker.message}
                                </span>
                            </span>
                        `;
                        messagesContainer.appendChild(div);
                    });
                } else {
                    messagesContainer.innerHTML = `
                        <div style="display: flex; align-items: center; gap: 12px; padding: 10px 12px; background: #fef3c7; border-radius: 8px; border-left: 4px solid #f59e0b;">
                            <i class="fas fa-info-circle" style="color: #92400e;"></i>
                            <span style="color: var(--text-dark);">
                                <strong>Associated data exists</strong>
                                <span style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-top: 2px;">
                                    Please delete all associated categories and items first.
                                </span>
                            </span>
                        </div>
                    `;
                }
                
                const bootstrap = window.bootstrap;
                const modalInstance = new bootstrap.Modal(modal);
                modalInstance.show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Unable to check deletion status. Please try again.');
            });
    }
</script>
@endsection
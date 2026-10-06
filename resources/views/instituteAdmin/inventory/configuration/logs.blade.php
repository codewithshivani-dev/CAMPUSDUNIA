{{-- resources/views/instituteAdmin/Inventory/configuration/logs.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Configuration Logs</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
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

    .btn-back {
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

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
        color: var(--primary-color);
    }

    .btn-view {
        background: #e0e7ff;
        color: #4338ca;
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

    .btn-view:hover {
        background: #c7d2fe;
        transform: translateY(-2px);
        color: #4338ca;
    }

    .config-info-bar {
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 16px;
        border: 2px solid var(--border-color);
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .config-info-bar .name {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--text-dark);
    }

    .config-info-bar .code {
        font-family: monospace;
        background: #f1f5f9;
        padding: 0.25rem 0.75rem;
        border-radius: 8px;
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .config-info-bar .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .config-info-bar .status-badge.active {
        background: #d1fae5;
        color: #065f46;
    }

    .config-info-bar .status-badge.inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .log-container {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        border: 2px solid var(--border-color);
    }

    .log-item {
        padding: 1.2rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        transition: all 0.3s;
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }

    .log-item:last-child {
        border-bottom: none;
    }

    .log-item:hover {
        background: #f8fafc;
    }

    .log-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.2rem;
    }

    .log-icon.created {
        background: #d1fae5;
        color: #065f46;
    }

    .log-icon.updated {
        background: #e0e7ff;
        color: #4338ca;
    }

    .log-icon.deleted {
        background: #fee2e2;
        color: #991b1b;
    }

    .log-icon.status_toggle {
        background: #fef3c7;
        color: #92400e;
    }

    .log-content {
        flex: 1;
    }

    .log-content .action-header {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 4px;
    }

    .log-content .action {
        font-weight: 700;
        font-size: 1rem;
        color: var(--text-dark);
    }

    .log-content .action-badge {
        display: inline-block;
        padding: 0.15rem 0.8rem;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .action-badge.created {
        background: #d1fae5;
        color: #065f46;
    }

    .action-badge.updated {
        background: #e0e7ff;
        color: #4338ca;
    }

    .action-badge.deleted {
        background: #fee2e2;
        color: #991b1b;
    }

    .action-badge.status_toggle {
        background: #fef3c7;
        color: #92400e;
    }

    .log-content .timestamp {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .log-content .timestamp i {
        margin-right: 4px;
    }

    .log-content .summary {
        font-size: 0.9rem;
        color: var(--text-dark);
        margin-top: 6px;
        padding: 8px 12px;
        background: #f8fafc;
        border-radius: 8px;
        border-left: 3px solid var(--primary-color);
    }

    .changes-container {
        margin-top: 10px;
        background: #f8fafc;
        border-radius: 10px;
        padding: 12px 16px;
        border: 1px solid var(--border-color);
    }

    .changes-container .change-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .change-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 6px 0;
        border-bottom: 1px dashed #e8edf2;
    }

    .change-item:last-child {
        border-bottom: none;
    }

    .change-item .field-name {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--text-dark);
        min-width: 140px;
    }

    .change-item .old-value {
        background: #fee2e2;
        color: #991b1b;
        padding: 2px 10px;
        border-radius: 6px;
        font-size: 0.85rem;
        text-decoration: line-through;
    }

    .change-item .arrow-icon {
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    .change-item .new-value {
        background: #d1fae5;
        color: #065f46;
        padding: 2px 10px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .change-item .value-label {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-right: 4px;
    }

    .log-meta {
        font-size: 0.75rem;
        color: var(--text-muted);
        white-space: nowrap;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.5rem;
        min-width: 140px;
    }

    .log-meta .user {
        font-weight: 600;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .log-meta .user .user-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--primary-gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .log-meta .time {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.7rem;
    }

    .log-meta .time .time-ago {
        color: var(--text-muted);
        font-weight: 500;
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

    .filter-bar {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-bar .filter-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-muted);
    }

    .filter-bar select, .filter-bar input {
        padding: 0.7rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 0.9rem;
        transition: all 0.3s;
        background: #f8fafc;
    }

    .filter-bar select:focus, .filter-bar input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .pagination-container {
        margin-top: 1.5rem;
        text-align: center;
    }

    .pagination-container .pagination {
        display: inline-flex;
        gap: 4px;
        list-style: none;
        padding: 0;
    }

    .pagination-container .pagination li {
        display: inline-block;
    }

    .pagination-container .pagination li a,
    .pagination-container .pagination li span {
        padding: 6px 14px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-dark);
        text-decoration: none;
        font-size: 0.85rem;
        transition: all 0.3s;
    }

    .pagination-container .pagination li a:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .pagination-container .pagination li.active span {
        background: var(--primary-gradient);
        color: white;
        border-color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .config-info-bar {
            flex-direction: column;
            text-align: center;
        }

        .log-item {
            flex-direction: column;
        }

        .log-meta {
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: flex-start;
            width: 100%;
            min-width: auto;
        }

        .filter-bar {
            flex-direction: column;
        }

        .filter-bar select, .filter-bar input {
            width: 100%;
        }

        .change-item {
            flex-wrap: wrap;
        }

        .change-item .field-name {
            min-width: 100%;
        }
    }

    #actionFilter {
        cursor: pointer;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-history"></i> Configuration Logs</h1>
            <p>Track all changes made to this configuration</p>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('inventory.configuration.view', $configuration->id) }}" class="btn-view">
                <i class="fas fa-eye"></i> View Configuration
            </a>
            <a href="{{ route('inventory.configuration') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Config Info -->
    <div class="config-info-bar">
        <div>
            <span class="name">{{ $configuration->configuration_name }}</span>
            <span class="code">{{ $configuration->configuration_code }}</span>
            <span class="status-badge {{ $configuration->status === 'active' ? 'active' : 'inactive' }} ms-2">
                {{ $configuration->status === 'active' ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <div style="font-size: 0.85rem; color: var(--text-muted);">
            <i class="fas fa-clock"></i> 
            {{ $logs->total() }} activity {{ $logs->total() > 1 ? 'entries' : 'entry' }}
        </div>
    </div>

    <!-- Filter -->
    <div class="filter-bar">
        <span class="filter-label"><i class="fas fa-filter"></i> Filter:</span>
        <select id="actionFilter" style="min-width: 150px;">
            <option value="">All Actions</option>
            <option value="created">📝 Created</option>
            <option value="updated">✏️ Updated</option>
            <option value="status_toggle">🔄 Status Changed</option>
        </select>
        <input type="text" id="searchLogs" placeholder="🔍 Search logs..." style="flex: 1; min-width: 200px;">
        <button onclick="clearFilters()" style="
            padding: 0.7rem 1.5rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            background: white;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.3s;
        ">
            <i class="fas fa-undo"></i> Clear
        </button>
    </div>

    <!-- Logs -->
    <div class="log-container">
        @forelse($logs as $log)
        <div class="log-item" data-action="{{ $log->action }}" data-search="{{ $log->action }} {{ $log->remarks ?? '' }} {{ $log->user?->name ?? '' }}">
            <div class="log-icon {{ $log->action }}">
                @if($log->action == 'created')
                    <i class="fas fa-plus"></i>
                @elseif($log->action == 'updated')
                    <i class="fas fa-edit"></i>
                @elseif($log->action == 'deleted')
                    <i class="fas fa-trash"></i>
                @elseif($log->action == 'status_toggle')
                    <i class="fas fa-exchange-alt"></i>
                @else
                    <i class="fas fa-circle"></i>
                @endif
            </div>
            <div class="log-content">
                <div class="d-flex justify-content-between">
                    <!-- Action and Timestamp -->
                    <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                        <span style="font-weight: 700; font-size: 1rem; color: var(--text-dark);">
                            @if($log->action == 'created')
                                <span style="color: #065f46;">📝 Created</span>
                            @elseif($log->action == 'updated')
                                <span style="color: #4338ca;">✏️ Updated</span>
                            @elseif($log->action == 'deleted')
                                <span style="color: #991b1b;">🗑️ Deleted</span>
                            @elseif($log->action == 'status_toggle')
                                <span style="color: #92400e;">🔄 Status Changed</span>
                            @else
                                <span style="color: var(--text-muted);">{{ ucfirst($log->action) }}</span>
                            @endif
                        </span>
                        <span style="font-size: 0.75rem; color: var(--text-muted);">
                            <i class="fas fa-clock"></i>
                            {{ $log->created_at->format('d M Y, h:i A') }}
                            <span style="color: var(--text-muted); font-weight: 400;">
                                ({{ $log->created_at->diffForHumans() }})
                            </span>
                        </span>
                    </div>

                    <!-- User -->
                    <div style="display: flex; align-items: center; gap: 6px; font-size: 0.8rem; color: var(--text-muted);">
                        <span class="user-avatar" style="width: 24px; height: 24px; border-radius: 50%; background: var(--primary-gradient); color: white; display: inline-flex; align-items: center; justify-content: center; font-size: 0.6rem; font-weight: 700;">
                            {{ $log->user ? strtoupper(substr($log->user->name, 0, 1)) : 'S' }}
                        </span>
                        {{ $log->user?->name ?? 'System' }}
                    </div>
                </div>

                <!-- Summary -->
                <div class="summary">
                    @if($log->action == 'created')
                        Configuration <strong>"{{ $configuration->configuration_name }}"</strong> was created
                    @elseif($log->action == 'updated')
                        Configuration was updated
                    @elseif($log->action == 'deleted')
                        Configuration <strong>"{{ $configuration->configuration_name }}"</strong> was deleted
                    @elseif($log->action == 'status_toggle')
                        Configuration status was changed
                        @if($log->remarks)
                            <br><small style="color: var(--text-muted);">{{ $log->remarks }}</small>
                        @endif
                    @else
                        {{ $log->remarks ?? 'Activity recorded' }}
                    @endif
                </div>

                <!-- Changes Display for Update Actions -->
                @if($log->action == 'updated' && $log->old_data && $log->new_data)
                <div class="changes-container">
                    <div class="change-title">
                        <i class="fas fa-exchange-alt"></i> Changes Made
                    </div>
                    @php
                        $changes = [];
                        $oldData = is_array($log->old_data) ? $log->old_data : json_decode($log->old_data, true);
                        $newData = is_array($log->new_data) ? $log->new_data : json_decode($log->new_data, true);
                        
                        $fieldLabels = [
                            'configuration_name' => 'Configuration Name',
                            'costing_method' => 'Costing Method',
                            'multi_warehouse' => 'Multi Warehouse',
                            'barcode_enabled' => 'Barcode',
                            'qr_enabled' => 'QR Code',
                            'batch_tracking' => 'Batch Tracking',
                            'serial_tracking' => 'Serial Tracking',
                            'is_configured' => 'Status',
                            'status' => 'Status'
                        ];

                        $booleanFields = [
                            'multi_warehouse', 'barcode_enabled', 'qr_enabled', 
                            'batch_tracking', 'serial_tracking', 'is_configured'
                        ];

                        if (is_array($oldData) && is_array($newData)) {
                            foreach ($newData as $key => $value) {
                                if (in_array($key, ['id', 'institute_id', 'configuration_code', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_by'])) {
                                    continue;
                                }
                                
                                if (isset($oldData[$key]) && $oldData[$key] != $value) {
                                    $oldValue = $oldData[$key];
                                    $newValue = $value;
                                    
                                    if (in_array($key, $booleanFields)) {
                                        $oldValue = $oldValue ? '✅ Enabled' : '❌ Disabled';
                                        $newValue = $newValue ? '✅ Enabled' : '❌ Disabled';
                                    }
                                    
                                    if ($key == 'costing_method') {
                                        $oldValue = str_replace('_', ' ', $oldValue);
                                        $newValue = str_replace('_', ' ', $newValue);
                                    }
                                    
                                    if ($key == 'status') {
                                        $oldValue = ucfirst($oldValue);
                                        $newValue = ucfirst($newValue);
                                    }
                                    
                                    $changes[] = [
                                        'field' => $fieldLabels[$key] ?? str_replace('_', ' ', ucfirst($key)),
                                        'old' => $oldValue ?: 'Not Set',
                                        'new' => $newValue ?: 'Not Set'
                                    ];
                                }
                            }
                        }
                    @endphp

                    @if(count($changes) > 0)
                        @foreach($changes as $change)
                        <div class="change-item">
                            <span class="field-name">{{ $change['field'] }}</span>
                            <span class="old-value">{{ $change['old'] }}</span>
                            <span class="arrow-icon"><i class="fas fa-arrow-right"></i></span>
                            <span class="new-value">{{ $change['new'] }}</span>
                        </div>
                        @endforeach
                    @else
                        <div style="font-size: 0.85rem; color: var(--text-muted); padding: 4px 0;">
                            <i class="fas fa-info-circle"></i> No significant changes detected
                        </div>
                    @endif
                </div>
                @endif

                @if($log->remarks && $log->action != 'status_toggle')
                <div style="margin-top: 8px; font-size: 0.85rem; color: var(--text-muted);">
                    <i class="fas fa-comment"></i> Note: {{ $log->remarks }}
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-history"></i>
            <h4>No Logs Available</h4>
            <p>No activities have been recorded for this configuration yet.</p>
        </div>
        @endforelse

        @if($logs->hasPages())
        <div class="pagination-container">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    // Filter logs by action
    document.getElementById('actionFilter').addEventListener('change', function() {
        filterLogs();
    });

    // Search logs
    document.getElementById('searchLogs').addEventListener('keyup', function() {
        filterLogs();
    });

    function filterLogs() {
        const action = document.getElementById('actionFilter').value;
        const search = document.getElementById('searchLogs').value.toLowerCase();
        const items = document.querySelectorAll('.log-item');

        items.forEach(item => {
            let show = true;
            const itemAction = item.dataset.action || '';
            const itemSearch = item.dataset.search?.toLowerCase() || '';

            if (action && itemAction !== action) {
                show = false;
            }

            if (search && !itemSearch.includes(search)) {
                show = false;
            }

            item.style.display = show ? 'flex' : 'none';
        });
    }

    function clearFilters() {
        document.getElementById('actionFilter').value = '';
        document.getElementById('searchLogs').value = '';
        filterLogs();
    }
</script>

@endsection
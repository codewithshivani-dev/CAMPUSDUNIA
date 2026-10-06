{{-- resources/views/instituteAdmin/Inventory/configuration/view.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

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

    .btn-logs {
        background: #fef3c7;
        color: #92400e;
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

    .btn-logs:hover {
        background: #fde68a;
        transform: translateY(-2px);
        color: #92400e;
    }

    /* Hidden Edit button (for future use) */
    .btn-edit-hidden {
        background: #d1fae5;
        color: #065f46;
        padding: 0.7rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        transition: all 0.3s;
        display: none !important;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-edit-hidden:hover {
        background: #a7f3d0;
        transform: translateY(-2px);
        color: #065f46;
    }

    .show-edit-delete .btn-edit-hidden {
        display: inline-flex !important;
    }

    .form-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: var(--card-shadow);
        border: 2px solid var(--border-color);
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .detail-item label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 0.25rem;
    }

    .detail-item p {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
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

    .badge-method {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        border-radius: 12px;
        font-size: 0.65rem;
        font-weight: 700;
        background: var(--primary-gradient);
        color: white;
    }

    .config-code {
        font-family: monospace;
        background: #f1f5f9;
        padding: 0.25rem 0.75rem;
        border-radius: 8px;
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .feature-tag {
        display: inline-block;
        background: #e0e7ff;
        color: #4338ca;
        padding: 0.2rem 0.8rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        margin: 0.2rem 0.2rem 0.2rem 0;
    }

    .no-features {
        color: var(--text-muted);
        font-size: 0.8rem;
        font-style: italic;
    }

    .meta-info {
        background: #f8fafc;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        margin-top: 1.5rem;
    }

    .meta-info .meta-item {
        display: inline-block;
        margin-right: 2rem;
        font-size: 0.85rem;
    }

    .meta-info .meta-item .label {
        color: var(--text-muted);
        font-weight: 500;
    }

    .meta-info .meta-item .value {
        font-weight: 600;
        color: var(--text-dark);
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .form-card {
            padding: 1.5rem;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .meta-info .meta-item {
            display: block;
            margin-right: 0;
            margin-bottom: 0.5rem;
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.5rem;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-eye"></i> Configuration Details</h1>
            <p>View configuration: <strong>{{ $configuration->configuration_name }}</strong></p>
        </div>
        <div class="action-buttons">
            <a href="{{ route('inventory.configuration.logs', $configuration->id) }}" class="btn-logs">
                <i class="fas fa-history"></i> View Logs
            </a>
            <a href="{{ route('inventory.configuration') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>

            {{-- Hidden Edit button (for future use) --}}
            <a href="{{ route('inventory.configuration.edit', $configuration->id) }}" class="btn-edit-hidden">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    <!-- Main Card -->
    <div class="form-card">
        <!-- Basic Details -->
        <div class="detail-grid">
            <div class="detail-item">
                <label>Configuration Name</label>
                <p>{{ $configuration->configuration_name }}</p>
            </div>
            <div class="detail-item">
                <label>Configuration Code</label>
                <p><span class="config-code">{{ $configuration->configuration_code }}</span></p>
            </div>
            <div class="detail-item">
                <label>Costing Method</label>
                <p><span class="badge-method">{{ $configuration->costing_method }}</span></p>
            </div>
            <div class="detail-item">
                <label>Status</label>
                <p>
                    <span class="badge-status {{ $configuration->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                        {{ $configuration->status === 'active' ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Features -->
        <div style="margin-top: 2rem;">
            <h6 style="font-weight: 700; color: var(--text-dark); margin-bottom: 0.75rem;">
                <i class="fas fa-cogs" style="color: var(--primary-color);"></i> Features
            </h6>
            <div>
                @php
                    $features = [];
                    if($configuration->multi_warehouse) $features[] = 'Multi Warehouse';
                    if($configuration->barcode_enabled) $features[] = 'Barcode Enabled';
                    if($configuration->qr_enabled) $features[] = 'QR Enabled';
                    if($configuration->batch_tracking) $features[] = 'Batch Tracking';
                    if($configuration->serial_tracking) $features[] = 'Serial Tracking';
                @endphp

                @if(count($features) > 0)
                    @foreach($features as $feature)
                        <span class="feature-tag"><i class="fas fa-check-circle" style="font-size: 0.6rem;"></i> {{ $feature }}</span>
                    @endforeach
                @else
                    <span class="no-features">No features enabled</span>
                @endif
            </div>
        </div>

        <!-- Meta Information -->
        <div class="meta-info">
            <span class="meta-item">
                <span class="label">Created:</span>
                <span class="value">{{ $configuration->created_at->format('d M Y, h:i A') }}</span>
            </span>
            @if($configuration->creator)
                <span class="meta-item">
                    <span class="label">Created By:</span>
                    <span class="value">{{ $configuration->creator->name }}</span>
                </span>
            @endif
            @if($configuration->updated_at && $configuration->updated_at != $configuration->created_at)
                <span class="meta-item">
                    <span class="label">Last Updated:</span>
                    <span class="value">{{ $configuration->updated_at->format('d M Y, h:i A') }}</span>
                </span>
            @endif
            @if($configuration->updater)
                <span class="meta-item">
                    <span class="label">Updated By:</span>
                    <span class="value">{{ $configuration->updater->name }}</span>
                </span>
            @endif
        </div>
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
                    This will <span id="statusEffectText"></span> the configuration <strong>"{{ $configuration->configuration_name }}"</strong> and make it <span id="statusResultText"></span> for use in inventory operations.
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
</script>

@endsection
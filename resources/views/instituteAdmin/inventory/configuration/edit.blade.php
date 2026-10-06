{{-- resources/views/instituteAdmin/Inventory/configuration/edit.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Configuration</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* Reuse the same styles from your original form */
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
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

    .form-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: var(--card-shadow);
        border: 2px solid var(--border-color);
    }

    .form-card h3 {
        color: var(--text-dark);
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
        font-weight: 700;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-card h3 i {
        color: var(--primary-color);
        font-size: 1.4rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-label .required {
        color: #dc3545;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s;
        background: #f8fafc;
        color: var(--text-dark);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        background: white;
    }

    .form-control:disabled {
        background: #f1f5f9;
        cursor: not-allowed;
    }

    .form-hint {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 0.5rem;
    }

    .config-section {
        margin-bottom: 2rem;
    }

    .config-section-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .config-section-title i {
        color: var(--primary-color);
        font-size: 0.9rem;
    }

    .costing-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.75rem;
    }

    .costing-option {
        padding: 1rem 1.25rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .costing-option i {
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .costing-option:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.1);
    }

    .costing-option.selected {
        background: var(--primary-gradient);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .costing-option.selected i {
        color: white;
    }

    .costing-option input[type="radio"] {
        display: none;
    }

    .toggle-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1rem;
    }

    .toggle-card {
        background: #f8fafc;
        border: 2px solid var(--border-color);
        border-radius: 14px;
        padding: 1.25rem;
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .toggle-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.1);
        transform: translateY(-2px);
        background: white;
    }

    .toggle-card.selected {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, #f0f4ff, #e8edff);
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.15);
    }

    .toggle-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .toggle-icon.default {
        background: #f1f5f9;
        color: #64748b;
    }

    .toggle-icon.active {
        background: var(--primary-gradient);
        color: white;
    }

    .toggle-info {
        flex: 1;
    }

    .toggle-info h4 {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }

    .toggle-info p {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.4;
    }

    .toggle-checkbox {
        display: none;
    }

    .toggle-indicator {
        width: 24px;
        height: 24px;
        border-radius: 8px;
        border: 2px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.3s ease;
        background: white;
    }

    .toggle-card.selected .toggle-indicator {
        background: var(--primary-color);
        border-color: var(--primary-color);
    }

    .toggle-card.selected .toggle-indicator::after {
        content: '\f00c';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        color: white;
        font-size: 0.7rem;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 2px solid var(--border-color);
    }

    .btn {
        padding: 0.85rem 2rem;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: var(--text-dark);
        border: 2px solid var(--border-color);
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }

    .btn-danger {
        background: var(--danger-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
    }

    .loading-spinner {
        display: inline-block;
        width: 18px;
        height: 18px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-top: 3px solid white;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: var(--success-gradient);
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 1001;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .toast.show {
        transform: translateY(0);
        opacity: 1;
    }

    .toast.error {
        background: var(--danger-gradient);
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .form-card {
            padding: 1.5rem;
        }

        .toggle-grid {
            grid-template-columns: 1fr;
        }

        .costing-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-edit"></i> Edit Configuration</h1>
            <p>Update configuration: <strong>{{ $configuration->configuration_name }}</strong></p>
        </div>
        <a href="{{ route('inventory.configuration.view', $configuration->id) }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to View
        </a>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <h3><i class="fas fa-sliders-h"></i> Configuration Settings</h3>

        <form method="POST" action="{{ route('inventory.configuration.update', $configuration->id) }}" id="editForm">
            @csrf
            @method('PUT')

            <!-- Configuration Name -->
            <div class="config-section">
                <div class="form-group">
                    <label class="form-label">
                        Configuration Name <span class="required">*</span>
                    </label>
                    <input type="text" 
                           name="configuration_name" 
                           class="form-control" 
                           value="{{ old('configuration_name', $configuration->configuration_name) }}"
                           placeholder="e.g., School Inventory" 
                           required>
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i> 
                        Configuration code: <strong>{{ $configuration->configuration_code }}</strong> (auto-generated)
                    </div>
                    @error('configuration_name')
                        <div class="text-danger" style="font-size: 0.85rem; margin-top: 0.5rem;">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Costing Method -->
            <div class="config-section">
                <div class="config-section-title">
                    <i class="fas fa-calculator"></i> Costing Method
                </div>
                <div class="costing-grid">
                    @php
                        $methods = ['FIFO', 'LIFO', 'FILO', 'WEIGHTED_AVERAGE'];
                        $methodIcons = [
                            'FIFO' => 'fa-layer-group',
                            'LIFO' => 'fa-layer-group fa-flip-vertical',
                            'FILO' => 'fa-exchange-alt',
                            'WEIGHTED_AVERAGE' => 'fa-balance-scale'
                        ];
                    @endphp
                    @foreach($methods as $method)
                    <label class="costing-option {{ $configuration->costing_method == $method ? 'selected' : '' }}" 
                           data-value="{{ $method }}" 
                           onclick="selectCostingMethod('{{ $method }}', this)">
                        <i class="fas {{ $methodIcons[$method] }}"></i> 
                        {{ str_replace('_', ' ', $method) }}
                        <input type="radio" name="costing_method" value="{{ $method }}" 
                               {{ $configuration->costing_method == $method ? 'checked' : '' }}>
                    </label>
                    @endforeach
                </div>
                @error('costing_method')
                    <div class="text-danger" style="font-size: 0.85rem; margin-top: 0.5rem;">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Feature Toggles -->
            <div class="config-section">
                <div class="config-section-title">
                    <i class="fas fa-toggle-on"></i> Features & Integrations
                </div>
                <div class="toggle-grid">
                    @php
                        $features = [
                            'multi_warehouse' => ['icon' => 'fa-warehouse', 'title' => 'Multi Warehouse', 'desc' => 'Manage inventory across multiple warehouse locations'],
                            'barcode_enabled' => ['icon' => 'fa-barcode', 'title' => 'Barcode Enabled', 'desc' => 'Use barcode scanning for inventory operations'],
                            'qr_enabled' => ['icon' => 'fa-qrcode', 'title' => 'QR Enabled', 'desc' => 'Use QR codes for quick inventory identification'],
                            'batch_tracking' => ['icon' => 'fa-boxes', 'title' => 'Batch Tracking', 'desc' => 'Track inventory by batch or lot numbers'],
                            'serial_tracking' => ['icon' => 'fa-hashtag', 'title' => 'Serial Tracking', 'desc' => 'Track individual items by serial numbers'],
                            'accounting_enabled' => ['icon' => 'fa-file-invoice-dollar', 'title' => 'Accounting Integration', 'desc' => 'Sync inventory with accounting software']
                        ];
                    @endphp
                    @foreach($features as $key => $feature)
                    <label class="toggle-card {{ $configuration->$key ? 'selected' : '' }}" onclick="toggleCard(this)">
                        <div class="toggle-icon {{ $configuration->$key ? 'active' : 'default' }}" id="icon-{{ $key }}">
                            <i class="fas {{ $feature['icon'] }}"></i>
                        </div>
                        <div class="toggle-info">
                            <h4>{{ $feature['title'] }}</h4>
                            <p>{{ $feature['desc'] }}</p>
                        </div>
                        <div class="toggle-indicator"></div>
                        <input type="checkbox" name="{{ $key }}" value="1" 
                               class="toggle-checkbox" {{ $configuration->$key ? 'checked' : '' }}>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <div>
                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Update Configuration
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="toast">
    <i class="fas fa-check-circle"></i>
    <span id="toast-message"></span>
</div>

<script>
    // Initialize with existing values
    document.addEventListener('DOMContentLoaded', function() {
        // The toggles are already set from the server-side rendering
    });

    function selectCostingMethod(value, element) {
        document.querySelectorAll('.costing-option').forEach(opt => {
            opt.classList.remove('selected');
        });
        element.classList.add('selected');
        const radio = element.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
    }

    function toggleCard(card) {
        const checkbox = card.querySelector('.toggle-checkbox');
        const iconDiv = card.querySelector('.toggle-icon');
        
        checkbox.checked = !checkbox.checked;
        
        if (checkbox.checked) {
            card.classList.add('selected');
            iconDiv.classList.remove('default');
            iconDiv.classList.add('active');
        } else {
            card.classList.remove('selected');
            iconDiv.classList.remove('active');
            iconDiv.classList.add('default');
        }
    }

    function resetForm() {
        if (confirm('Reset all fields to their current saved values?')) {
            location.reload();
        }
    }

    // Form submission
    document.getElementById('editForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        const originalHTML = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Updating...';
        submitBtn.disabled = true;
    });

    // Toast notification
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        
        toastMessage.textContent = message;
        toast.classList.remove('error');
        
        if (type === 'error') {
            toast.classList.add('error');
            toast.querySelector('i').className = 'fas fa-exclamation-circle';
        } else {
            toast.querySelector('i').className = 'fas fa-check-circle';
        }
        
        toast.classList.add('show');
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    @if(session('success'))
        showToast('{{ session('success') }}');
    @endif

    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
</script>
@endsection
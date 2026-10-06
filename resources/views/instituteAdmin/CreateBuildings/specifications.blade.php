@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

@php
function formatSpecKey($key) {
    // Remove special characters, keep only letters, numbers, spaces, and underscores
    $cleaned = preg_replace('/[^a-zA-Z0-9\s_]/', '', $key);
    
    // Replace underscores and hyphens with spaces
    $cleaned = str_replace(['_', '-'], ' ', $cleaned);
    
    // Convert to lowercase first
    $cleaned = strtolower($cleaned);
    
    // Capitalize first letter of each word
    $cleaned = ucwords($cleaned);
    
    return $cleaned;
}
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Specifications - {{ $asset->asset_name }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --info-gradient: linear-gradient(135deg, #0ea5e9, #0284c7);
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    body {
        background: #f0f2f5;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .main-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 25px 30px;
    }

    /* Header */
    .page-header {
        background: var(--primary-gradient);
        padding: 30px 35px;
        border-radius: 16px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.3);
    }

    .page-header .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-header .header-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .page-header .header-left .icon {
        font-size: 2.5rem;
        background: rgba(255, 255, 255, 0.15);
        padding: 15px;
        border-radius: 14px;
    }

    .page-header .header-left .info h1 {
        font-weight: 700;
        margin-bottom: 5px;
        font-size: 1.8rem;
    }

    .page-header .header-left .info p {
        opacity: 0.9;
        margin-bottom: 0;
        font-size: 0.95rem;
    }

    .page-header .header-right .badge-category {
        background: rgba(255, 255, 255, 0.2);
        padding: 8px 20px;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    /* Stats */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .stats-card {
        background: white;
        padding: 18px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        text-align: center;
    }

    .stats-card .stats-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-dark);
    }

    .stats-card .stats-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .stats-card .stats-icon {
        font-size: 1.5rem;
        margin-bottom: 5px;
    }

    .stats-card.purple .stats-icon {
        color: var(--primary-color);
    }

    .stats-card.green .stats-icon {
        color: #10b981;
    }

    .stats-card.orange .stats-icon {
        color: #f59e0b;
    }

    /* Buttons */
    .btn-purple {
        background: var(--primary-gradient);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-purple:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
        color: white;
    }

    .btn-outline-purple {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        background: transparent;
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-purple:hover {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
    }

    .btn-outline-primary {
        background: white;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
        padding: 8px 18px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
    }

    .btn-outline-danger {
        background: white;
        color: #dc3545;
        border: 2px solid #dc3545;
        padding: 5px 14px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }

    .btn-outline-danger:hover {
        background: var(--danger-gradient);
        color: white;
        border-color: transparent;
    }

    .btn-success {
        background: var(--success-gradient);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .back-btn {
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .back-btn:hover {
        transform: translateX(-5px);
    }

    /* Specifications Table */
    .specs-card {
        background: white;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    .specs-card .card-header {
        background: #f8fafc;
        padding: 18px 25px;
        border-bottom: 2px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .specs-card .card-header h5 {
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }

    .specs-card .card-body {
        padding: 25px;
    }

    /* Table Styles */
    .specs-table {
        width: 100%;
        border-collapse: collapse;
    }

    .specs-table thead th {
        background: #f8fafc;
        padding: 12px 15px;
        text-align: left;
        font-weight: 700;
        color: var(--text-dark);
        border-bottom: 2px solid var(--border-color);
        font-size: 0.9rem;
    }

    .specs-table tbody td {
        padding: 10px 15px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    .specs-table tbody tr:hover {
        background: #f8fafc;
    }

    .spec-key-input,
    .spec-value-input {
        width: 100%;
        padding: 8px 12px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background: white;
    }

    .spec-key-input:focus,
    .spec-value-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        outline: none;
    }

    .spec-key-input {
        font-weight: 600;
        color: var(--text-dark);
    }
    
    .spec-key-input[readonly] {
        background: #f1f5f9;
        cursor: default;
        color: #1e293b;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #a0aec0;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        color: var(--primary-color);
    }

    .empty-state h5 {
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 10px;
    }

    /* Toast */
    .toast-container {
        z-index: 9999;
    }

    .toast {
        border-radius: 12px;
        border: none;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .toast-header {
        border-bottom: 1px solid var(--border-color);
    }

    /* Loading */
    .loading-spinner {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.85);
        z-index: 9998;
        justify-content: center;
        align-items: center;
    }

    .loading-spinner .spinner-border {
        width: 3.5rem;
        height: 3.5rem;
        color: var(--primary-color);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .main-container {
            padding: 15px;
        }

        .page-header {
            padding: 20px;
        }

        .page-header .header-left .icon {
            font-size: 2rem;
            padding: 12px;
        }

        .page-header .header-left .info h1 {
            font-size: 1.3rem;
        }

        .page-header .header-top {
            flex-direction: column;
            text-align: center;
        }

        .page-header .header-left {
            flex-direction: column;
            text-align: center;
        }

        .specs-card .card-header {
            flex-direction: column;
            text-align: center;
        }

        .specs-table thead th,
        .specs-table tbody td {
            padding: 8px 10px;
            font-size: 0.85rem;
        }

        .spec-key-input,
        .spec-value-input {
            font-size: 0.8rem;
            padding: 6px 10px;
        }

        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .stats-row {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .stats-card .stats-number {
            font-size: 1.4rem;
        }

        .specs-table {
            font-size: 0.8rem;
        }

        .specs-table thead th,
        .specs-table tbody td {
            padding: 6px 8px;
        }
    }
    </style>
</head>

<body>

    <div class="loading-spinner" id="loadingSpinner">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="container-fluid main-container">
        <div class="row">
            <div class="col-12">
                <!-- Back Button -->
                <a href="{{ url()->previous() }}" class="btn btn-outline-purple back-btn">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>

                <!-- Page Header -->
                <div class="page-header">
                    <div class="header-top">
                        <div class="header-left">
                            <div class="icon">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="info">
                                <h1>Manage Specifications</h1>
                                <p>
                                    <i class="fas fa-cube me-2"></i>
                                    {{ $asset->asset_name }}
                                    <span class="ms-3">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ $asset->asset_id }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="header-right">
                            <span class="badge-category">
                                <i class="fas fa-folder me-1"></i>
                                {{ $asset->category ? $asset->category->name : 'No Category' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="stats-row">
                    <div class="stats-card purple">
                        <div class="stats-icon"><i class="fas fa-list-ul"></i></div>
                        <div class="stats-number" id="totalSpecs">{{ count($specs) }}</div>
                        <div class="stats-label">Total Specifications</div>
                    </div>
                    <div class="stats-card green">
                        <div class="stats-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="stats-number" id="filledSpecs">{{ count(array_filter($specs)) }}</div>
                        <div class="stats-label">Filled</div>
                    </div>
                    <div class="stats-card orange">
                        <div class="stats-icon"><i class="fas fa-clock"></i></div>
                        <div class="stats-number" id="emptySpecs">{{ count($specs) - count(array_filter($specs)) }}
                        </div>
                        <div class="stats-label">Empty</div>
                    </div>
                </div>

                <!-- Specifications Table -->
                <div class="specs-card">
                    <div class="card-header">
                        <h5>
                            <i class="fas fa-edit me-2" style="color: var(--primary-color);"></i>
                            Specifications
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="addSpecificationRow()">
                                <i class="fas fa-plus me-1"></i>Add Field
                            </button>
                            <button class="btn btn-success btn-sm" onclick="saveSpecifications()">
                                <i class="fas fa-save me-1"></i>Save All
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="specs-table" id="specsTable">
                                <thead>
                                    <tr>
                                        <th style="width: 35%;">Field Name</th>
                                        <th style="width: 50%;">Value</th>
                                        <th style="width: 15%; text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="specsBody">
                                    @if(count($specs) > 0)
                                    @foreach($specs as $key => $value)
                                    <tr data-key="{{ $key }}">
                                        <td>
                                            <input type="text" class="spec-key-input" value="{{ formatSpecKey($key) }}" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="spec-value-input" value="{{ $value }}" placeholder="Value">
                                        </td>
                                        <td style="text-align: center;">
                                            <button class="btn btn-sm btn-outline-danger" onclick="removeSpecificationRow(this)" title="Remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="3">
                                            <div class="empty-state">
                                                <i class="fas fa-cube"></i>
                                                <h5>No Specifications Found</h5>
                                                <p class="text-muted">Click "Add Field" to add a new specification.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas fa-circle me-2" style="color: #10b981;"></i>
                <strong class="me-auto">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="toastMessage">Operation completed successfully.</div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function() {
        updateStats();
    });

    // ==========================================
    // FORMAT KEY INPUT
    // ==========================================
    function formatKeyInput(input) {
        // Remove special characters, keep only letters, numbers, spaces, and underscores
        let value = input.value.replace(/[^a-zA-Z0-9\s_]/g, '');
        
        // Replace underscores and hyphens with spaces
        value = value.replace(/[_-]/g, ' ');
        
        // Convert to lowercase and capitalize each word
        value = value.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
        
        input.value = value;
    }

    // ==========================================
    // ADD SPECIFICATION ROW
    // ==========================================
    function addSpecificationRow() {
        var tableBody = $('#specsBody');
        var emptyState = tableBody.find('.empty-state');

        // Remove empty state if exists
        if (emptyState.length > 0) {
            emptyState.closest('tr').remove();
        }

        var row = '<tr>' +
            '<td><input type="text" class="spec-key-input" placeholder="e.g., Manufacturer"></td>' +
            '<td><input type="text" class="spec-value-input" placeholder="e.g., Dell"></td>' +
            '<td style="text-align: center;">' +
            '<button class="btn btn-sm btn-outline-danger" onclick="removeSpecificationRow(this)" title="Remove">' +
            '<i class="fas fa-times"></i>' +
            '</button>' +
            '</td>' +
            '</tr>';

        tableBody.append(row);
        updateStats();

        // Focus on the new key input and add formatting
        var lastRow = tableBody.find('tr:last');
        var keyInput = lastRow.find('.spec-key-input');
        
        // Format on input
        keyInput.on('input', function() {
            formatKeyInput(this);
        });
        
        // Format on blur
        keyInput.on('blur', function() {
            formatKeyInput(this);
        });
        
        keyInput.focus();
    }

    // ==========================================
    // REMOVE SPECIFICATION ROW
    // ==========================================
    function removeSpecificationRow(button) {
        var row = $(button).closest('tr');
        var tableBody = $('#specsBody');

        row.fadeOut(300, function() {
            row.remove();
            updateStats();

            // Show empty state if no rows
            if (tableBody.find('tr').length === 0) {
                tableBody.html(
                    '<tr>' +
                    '<td colspan="3">' +
                    '<div class="empty-state">' +
                    '<i class="fas fa-cube"></i>' +
                    '<h5>No Specifications Found</h5>' +
                    '<p class="text-muted">Click "Add Field" to add a new specification.</p>' +
                    '</div>' +
                    '</td>' +
                    '</tr>'
                );
            }
        });
    }

    // ==========================================
    // UPDATE STATS
    // ==========================================
    function updateStats() {
        var rows = $('#specsBody tr');
        var total = 0;
        var filled = 0;
        var empty = 0;

        rows.each(function() {
            var key = $(this).find('.spec-key-input').val().trim();
            var value = $(this).find('.spec-value-input').val().trim();

            if (key || value) {
                total++;
                if (key && value) {
                    filled++;
                } else {
                    empty++;
                }
            }
        });

        $('#totalSpecs').text(total);
        $('#filledSpecs').text(filled);
        $('#emptySpecs').text(empty);
    }

    // ==========================================
    // SAVE SPECIFICATIONS
    // ==========================================
    function saveSpecifications() {
        var specs = {};
        var hasErrors = false;
        var duplicateKeys = [];
        var hasEmptyKey = false;

        $('#specsBody tr').each(function() {
            var key = $(this).find('.spec-key-input').val().trim();
            var value = $(this).find('.spec-value-input').val().trim();

            // Skip if both empty
            if (!key && !value) {
                return true;
            }

            // Check for empty key
            if (!key) {
                hasEmptyKey = true;
                return true;
            }

            // Check for duplicates
            if (specs[key] !== undefined) {
                duplicateKeys.push(key);
                hasErrors = true;
                return false;
            }

            specs[key] = value || null;
        });

        if (hasEmptyKey) {
            showToast('warning', 'Please fill in the Field Name for all rows.');
            return;
        }

        if (hasErrors) {
            showToast('warning', 'Duplicate field names found: ' + duplicateKeys.join(', '));
            return;
        }

        if (Object.keys(specs).length === 0) {
            if (!confirm('All specifications will be removed. Are you sure?')) {
                return;
            }
        }

        // Show loading
        $('#loadingSpinner').fadeIn();

        var assetId = '{{ $asset->asset_id }}';

        $.ajax({
            url: '{{ url("/institute/admin/specifications/asset") }}/' + assetId,
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                specifications: specs
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message || 'Specifications saved successfully!');
                    updateStats();
                } else {
                    showToast('error', response.message || 'Failed to save specifications.');
                }
            },
            error: function(xhr) {
                var message = xhr.responseJSON?.message || 'An error occurred while saving.';
                showToast('error', message);
            },
            complete: function() {
                $('#loadingSpinner').fadeOut();
            }
        });
    }

    // ==========================================
    // TOAST NOTIFICATION
    // ==========================================
    function showToast(type, message) {
        var toast = $('#liveToast');
        var icon = type === 'success' ? 'fa-check-circle' :
            type === 'warning' ? 'fa-exclamation-triangle' : 'fa-exclamation-circle';
        var color = type === 'success' ? '#10b981' :
            type === 'warning' ? '#f59e0b' : '#ef4444';

        toast.find('.toast-header i')
            .attr('class', 'fas ' + icon + ' me-2')
            .css('color', color);
        toast.find('.toast-body').text(message);

        var bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 4000
        });
        bsToast.show();
    }

    // ==========================================
    // KEYBOARD SHORTCUTS
    // ==========================================
    $(document).on('keydown', function(e) {
        // Ctrl+Enter to save
        if (e.ctrlKey && e.key === 'Enter') {
            e.preventDefault();
            saveSpecifications();
        }

        // Escape to go back
        if (e.key === 'Escape') {
            window.history.back();
        }
    });

    // Update stats on input change
    $(document).on('input', '.spec-key-input, .spec-value-input', function() {
        updateStats();
    });

    // Enter key to add new row
    $(document).on('keydown', '.spec-value-input', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            var currentRow = $(this).closest('tr');
            var nextRow = currentRow.next('tr');

            if (nextRow.length > 0) {
                nextRow.find('.spec-key-input').focus();
            } else {
                addSpecificationRow();
            }
        }
    });

    // Enter key on key input goes to value input
    $(document).on('keydown', '.spec-key-input', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $(this).closest('tr').find('.spec-value-input').focus();
        }
    });
    </script>

</body>

</html>
@endsection
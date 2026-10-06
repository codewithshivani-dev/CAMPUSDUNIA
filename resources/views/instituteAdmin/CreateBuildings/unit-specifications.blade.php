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
    <title>Unit Specifications - {{ $unit->name }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
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
    }

    .page-header {
        background: var(--primary-gradient);
        padding: 30px 35px;
        border-radius: 16px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-header .unit-id {
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
    }

    .page-header h1 {
        font-weight: 700;
        margin-bottom: 5px;
        font-size: 1.8rem;
    }

    .page-header .header-right {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-outline-light {
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        background: rgba(255, 255, 255, 0.1);
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        transform: translateY(-2px);
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

    .btn-secondary {
        background: #e2e8f0;
        color: var(--text-dark);
        border: 1px solid var(--border-color);
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #cbd5e1;
        transform: translateY(-2px);
    }

    .back-btn {
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        transform: translateX(-5px);
    }

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
        background: #f1f5f9;
        cursor: not-allowed;
    }

    .spec-key-input[readonly] {
        background: #f1f5f9;
        cursor: not-allowed;
        color: #1e293b;
        opacity: 0.9;
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

    .info-box {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .info-box .info-label {
        font-weight: 600;
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .info-box .info-value {
        font-weight: 600;
        color: var(--text-dark);
    }

    .info-box .info-divider {
        width: 1px;
        height: 25px;
        background: var(--border-color);
    }

    /* Warning for readonly fields */
    .field-readonly-hint {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-top: 2px;
        display: block;
        font-style: italic;
    }

    @media (max-width: 768px) {
        .main-container {
            padding: 15px;
        }

        .page-header {
            padding: 20px;
        }

        .page-header h1 {
            font-size: 1.3rem;
        }

        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .page-header .header-right {
            justify-content: center;
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

        .info-box {
            flex-direction: column;
            text-align: center;
        }

        .info-box .info-divider {
            display: none;
        }
    }

    @media (max-width: 480px) {
        .specs-table {
            font-size: 0.75rem;
        }

        .btn-outline-light {
            font-size: 0.8rem;
            padding: 6px 12px;
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
                <!-- Page Header -->
                <div class="page-header">
                    <div>
                        <h1>
                            <i class="fas fa-cog me-3"></i>Unit Specifications
                        </h1>
                        <p>
                            <span class="unit-id">
                                <i class="fas fa-hashtag me-1"></i>
                                {{ $unit->unit_id }}
                            </span>
                            <span class="ms-3">
                                <i class="fas fa-cube me-1"></i>
                                {{ $unit->name }}
                            </span>
                            <span class="ms-3">
                                <span class="badge bg-success">{{ $unit->status }}</span>
                            </span>
                        </p>
                    </div>
                    <div class="header-right">
                        <a href="{{ url('/campus/amenities') }}" class="btn btn-outline-light back-btn">
                            <i class="fas fa-arrow-left me-2"></i>Back to Campus Amenities
                        </a>
                        <span class="badge bg-primary">{{ $unit->amenity->name ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="info-box">
                    <span class="info-label">Unit ID:</span>
                    <span class="info-value">{{ $unit->unit_id }}</span>
                    <span class="info-divider"></span>
                    <span class="info-label">Unit Number:</span>
                    <span class="info-value">{{ $unit->unit_number }}</span>
                    <span class="info-divider"></span>
                    <span class="info-label">Status:</span>
                    <span class="info-value"><span class="badge bg-success">{{ $unit->status }}</span></span>
                    <span class="info-divider"></span>
                    <span class="info-label">Amenity:</span>
                    <span class="info-value">{{ $unit->amenity->name ?? 'N/A' }}</span>
                </div>

                <!-- Specifications Card -->
                <div class="specs-card">
                    <div class="card-header">
                        <h5>
                            <i class="fas fa-list-ul me-2" style="color: var(--primary-color);"></i>
                            Specifications
                            <small class="text-muted ms-2" style="font-size: 0.7rem;">
                                <i class="fas fa-lock me-1"></i>Field names are read-only
                            </small>
                        </h5>
                        <div class="d-flex gap-2 flex-wrap">
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
                                        <th style="width: 35%;">Field Name <span class="text-muted" style="font-weight:400;font-size:0.75rem;">(read-only)</span></th>
                                        <th style="width: 50%;">Value</th>
                                        <th style="width: 15%; text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="specsBody">
                                    @if(count($specs) > 0)
                                    @foreach($specs as $key => $value)
                                    <tr data-key="{{ $key }}">
                                        <td>
                                            <input type="text" class="form-control form-control-sm spec-key-input"
                                                value="{{ formatSpecKey($key) }}" 
                                                placeholder="Field name"
                                                readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm spec-value-input"
                                                value="{{ $value }}" placeholder="Value">
                                        </td>
                                        <td style="text-align: center;">
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="removeSpecificationRow(this)" title="Remove">
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
                                                <p class="text-muted">Click "Add Field" to add a new specification for
                                                    this unit.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="text-muted small mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Add specifications for this unit. Click <strong>"Save All"</strong> to save your changes.
                            Field names are automatically formatted (no underscores, capitalized words).
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $(document).ready(function() {
        var unitId = '{{ $unit->unit_id }}';
        var specs = @json($specs ?? []);
        
        // Get return URL from URL parameters
        var urlParams = new URLSearchParams(window.location.search);
        var returnUrl = urlParams.get('return_url');
        
        // Store in global variable
        window.returnUrl = returnUrl || '';
        
        renderSpecificationForm(specs);
    });

    // ==========================================
    // FORMAT KEY INPUT FOR DISPLAY
    // ==========================================
    function formatKeyDisplay(key) {
        // Remove special characters, keep only letters, numbers, spaces, and underscores
        let cleaned = key.replace(/[^a-zA-Z0-9\s_]/g, '');
        
        // Replace underscores and hyphens with spaces
        cleaned = cleaned.replace(/[_-]/g, ' ');
        
        // Convert to lowercase and capitalize each word
        cleaned = cleaned.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
        
        return cleaned;
    }

    // ==========================================
    // RENDER SPECIFICATION FORM
    // ==========================================
    function renderSpecificationForm(specs) {
        var html = '';
        var specKeys = Object.keys(specs);

        if (specKeys.length === 0) {
            html =
                '<tr>' +
                    '<td colspan="3">' +
                        '<div class="empty-state">' +
                            '<i class="fas fa-cube"></i>' +
                            '<h5>No Specifications Found</h5>' +
                            '<p class="text-muted">Click "Add Field" to add a new specification for this unit.</p>' +
                        '</div>' +
                    '</td>' +
                '</tr>';
        } else {
            $.each(specKeys, function(index, key) {
                var value = (specs[key] !== null && specs[key] !== undefined) ? specs[key] : '';
                var formattedKey = formatKeyDisplay(String(key));
                var escapedKey = escapeHtml(String(key));
                var escapedValue = escapeHtml(String(value));

                html +=
                    '<tr data-key="' + escapedKey + '">' +
                        '<td>' +
                            '<input type="text" class="form-control form-control-sm spec-key-input" ' +
                                'value="' + formattedKey + '" placeholder="Field name" readonly>' +
                        '</td>' +
                        '<td>' +
                            '<input type="text" class="form-control form-control-sm spec-value-input" ' +
                                'value="' + escapedValue + '" placeholder="Value">' +
                        '</td>' +
                        '<td style="text-align:center;">' +
                            '<button class="btn btn-sm btn-outline-danger" onclick="removeSpecificationRow(this)" title="Remove">' +
                                '<i class="fas fa-times"></i>' +
                            '</button>' +
                        '</td>' +
                    '</tr>';
            });
        }

        $('#specsBody').html(html);
    }

    // ==========================================
    // ADD SPECIFICATION ROW
    // ==========================================
    function addSpecificationRow() {
        var tableBody = $('#specsBody');
        var emptyState = tableBody.find('.empty-state');

        if (emptyState.length > 0) {
            emptyState.closest('tr').remove();
            tableBody = $('#specsBody');
        }

        var row = '<tr>' +
            '<td>' +
                '<input type="text" class="form-control form-control-sm spec-key-input" ' +
                    'placeholder="e.g., Manufacturer" readonly>' +
            '</td>' +
            '<td><input type="text" class="form-control form-control-sm spec-value-input" placeholder="e.g., Dell"></td>' +
            '<td style="text-align: center;">' +
                '<button class="btn btn-sm btn-outline-danger" onclick="removeSpecificationRow(this)" title="Remove">' +
                    '<i class="fas fa-times"></i>' +
                '</button>' +
            '</td>' +
        '</tr>';

        tableBody.append(row);
        var lastRow = tableBody.find('tr:last');
        lastRow.find('.spec-value-input').focus();
        
        showToast('Enter a value for the specification. Field names are read-only.', 'info');
    }

    // ==========================================
    // REMOVE SPECIFICATION ROW
    // ==========================================
    function removeSpecificationRow(button) {
        var row = $(button).closest('tr');
        var tableBody = $('#specsBody');

        row.fadeOut(300, function() {
            row.remove();
            updateTableState();
        });
    }

    // ==========================================
    // UPDATE TABLE STATE (Show empty state if no rows)
    // ==========================================
    function updateTableState() {
        var tableBody = $('#specsBody');
        var rows = tableBody.find('tr');
        var hasInputRows = false;

        rows.each(function() {
            if ($(this).find('.spec-key-input').length > 0 || $(this).find('.spec-value-input').length > 0) {
                hasInputRows = true;
                return false;
            }
        });

        if (!hasInputRows) {
            tableBody.html(
                '<tr>' +
                    '<td colspan="3">' +
                        '<div class="empty-state">' +
                            '<i class="fas fa-cube"></i>' +
                            '<h5>No Specifications Found</h5>' +
                            '<p class="text-muted">Click "Add Field" to add a new specification for this unit.</p>' +
                        '</div>' +
                    '</td>' +
                '</tr>'
            );
        }
    }

    // ==========================================
    // SAVE SPECIFICATIONS
    // ==========================================
    function saveSpecifications() {
        var specs = {};
        var hasErrors = false;
        var duplicateKeys = [];
        var hasEmptyKey = false;
        var hasValidRow = false;

        var rows = $('#specsBody tr');

        rows.each(function() {
            var keyInput = $(this).find('.spec-key-input');
            var valueInput = $(this).find('.spec-value-input');

            if (keyInput.length === 0 || valueInput.length === 0) {
                return true;
            }

            var key = keyInput.val().trim();
            var value = valueInput.val().trim();

            if (!key && !value) {
                return true;
            }

            hasValidRow = true;

            if (!key) {
                hasEmptyKey = true;
                return true;
            }

            // Check for duplicates (case insensitive)
            var keyLower = key.toLowerCase();
            var foundDuplicate = false;
            for (var existingKey in specs) {
                if (existingKey.toLowerCase() === keyLower) {
                    duplicateKeys.push(key);
                    hasErrors = true;
                    foundDuplicate = true;
                    break;
                }
            }
            if (foundDuplicate) {
                return false;
            }

            specs[key] = value || null;
        });

        if (hasEmptyKey) {
            showToast('Please fill in the Field Name for all rows.', 'warning');
            return;
        }

        if (hasErrors) {
            showToast('Duplicate field names found: ' + duplicateKeys.join(', '), 'warning');
            return;
        }

        if (Object.keys(specs).length === 0 && !hasValidRow) {
            if (!confirm('All specifications will be removed. Are you sure?')) {
                return;
            }
        }

        $('#loadingSpinner').fadeIn();
        var unitId = '{{ $unit->unit_id }}';

        $.ajax({
            url: '{{ url("/institute/admin/campus/amenity-unit") }}/' + unitId + '/specifications',
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                specifications: specs
            },
            success: function(response) {
                if (response.success) {
                    showToast(response.message || 'Specifications saved successfully!', 'success');

                    // Use the global returnUrl variable
                    var returnUrl = window.returnUrl;
                    
                    if (returnUrl) {
                        // If return URL exists, redirect there
                        setTimeout(function() {
                            window.location.href = returnUrl;
                        }, 1500);
                    } else {
                        // If no return URL, go to campus amenities page
                        setTimeout(function() {
                            window.location.href = '{{ url("/institute/admin/campus/amenities") }}';
                        }, 1500);
                    }
                } else {
                    showToast(response.message || 'Failed to save specifications.', 'error');
                }
            },
            error: function(xhr) {
                var message = xhr.responseJSON?.message || 'An error occurred while saving.';
                showToast(message, 'error');
            },
            complete: function() {
                $('#loadingSpinner').fadeOut();
            }
        });
    }

    // ==========================================
    // ESCAPE HTML
    // ====================================

    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    // ==========================================
    // TOAST NOTIFICATION
    // ==========================================
    function showToast(message, type) {
        var toastEl = document.getElementById('liveToast');
        var toastMessageEl = document.getElementById('toastMessage');

        var icon = 'fa-check-circle';
        var color = '#10b981';

        if (type === 'warning') {
            icon = 'fa-exclamation-triangle';
            color = '#f59e0b';
        } else if (type === 'error') {
            icon = 'fa-exclamation-circle';
            color = '#ef4444';
        } else if (type === 'info') {
            icon = 'fa-info-circle';
            color = '#0ea5e9';
        }

        var iconEl = toastEl.querySelector('.toast-header i');
        if (iconEl) {
            iconEl.className = 'fas ' + icon + ' me-2';
            iconEl.style.color = color;
        }

        toastMessageEl.textContent = message;

        var bsToast = new bootstrap.Toast(toastEl, {
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
            var returnUrl = window.returnUrl;
            if (returnUrl) {
                window.location.href = returnUrl;
            } else {
                window.location.href = '{{ url("/institute/admin/campus/amenities") }}';
            }
        }
    });

    // Enter key to add new row
    $(document).on('keydown', '.spec-value-input', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            var currentRow = $(this).closest('tr');
            var nextRow = currentRow.next('tr');

            if (nextRow.length > 0 && nextRow.find('.spec-value-input').length > 0) {
                nextRow.find('.spec-value-input').focus();
            } else {
                addSpecificationRow();
            }
        }
    });
    </script>

</body>

</html>
@endsection
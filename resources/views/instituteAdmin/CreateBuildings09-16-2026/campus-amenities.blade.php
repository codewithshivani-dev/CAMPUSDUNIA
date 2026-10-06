@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Campus Amenities Management</title>
    
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
            max-width: 1400px;
            margin: 0 auto;
           
        }

        .page-header {
            background: var(--primary-gradient);
            padding: 30px 35px;
            border-radius: 16px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(67,97,238,0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-header h1 {
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 2rem;
        }

        .page-header p {
            opacity: 0.9;
            margin-bottom: 0;
            font-size: 1rem;
        }

        .page-header .header-stats {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .page-header .header-stats .stat-item {
            background: rgba(255,255,255,0.15);
            padding: 5px 18px;
            border-radius: 20px;
            font-size: 0.9rem;
        }

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
            box-shadow: 0 5px 15px rgba(67,97,238,0.4);
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
            box-shadow: 0 5px 15px rgba(16,185,129,0.4);
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
            padding: 5px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
        }

        .btn-outline-secondary {
            background: white;
            color: var(--text-muted);
            border: 2px solid var(--border-color);
            padding: 5px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background: var(--border-color);
            color: var(--text-dark);
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

        .btn-light {
            background: white;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-light:hover {
            background: #f8fafc;
            transform: translateY(-2px);
        }

        .back-btn {
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            transform: translateX(-5px);
        }

        /* Building Selector */
        .building-selector {
            background: white;
            padding: 20px 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .building-selector label {
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }

        .building-selector select {
            padding: 10px 20px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.95rem;
            min-width: 250px;
            background: white;
            transition: all 0.3s ease;
        }

        .building-selector select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67,97,238,0.15);
            outline: none;
        }

        /* Amenities Table */
        .amenities-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .amenities-card .card-header {
            background: #f8fafc;
            padding: 18px 25px;
            border-bottom: 2px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .amenities-card .card-header h5 {
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        .amenities-card .card-body {
            padding: 25px;
        }

        .amenities-table {
            width: 100%;
            border-collapse: collapse;
        }

        .amenities-table thead th {
            background: #f8fafc;
            padding: 12px 15px;
            text-align: left;
            font-weight: 700;
            color: var(--text-dark);
            border-bottom: 2px solid var(--border-color);
            font-size: 0.9rem;
        }

        .amenities-table tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .amenities-table tbody tr:hover {
            background: #f8fafc;
        }

        /* Add Unit Button */
        .btn-add-unit {
            background: var(--success-gradient);
            color: white;
            border: none;
            padding: 6px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .btn-add-unit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(16,185,129,0.4);
            color: white;
        }

        /* Units Button */
        .units-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .units-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(67,97,238,0.3);
            color: white;
        }

        .units-btn .badge-count {
            background: rgba(255,255,255,0.2);
            padding: 1px 8px;
            border-radius: 10px;
            font-size: 0.7rem;
        }

        /* Empty State */
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

        .empty-state h4 {
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
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
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
            background: rgba(255,255,255,0.85);
            z-index: 9998;
            justify-content: center;
            align-items: center;
        }

        .loading-spinner .spinner-border {
            width: 3.5rem;
            height: 3.5rem;
            color: var(--primary-color);
        }

        /* Unit Creation Loading Modal */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .modal-header {
            border-radius: 16px 16px 0 0;
            padding: 20px 25px;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            padding: 15px 25px;
            border-top: 1px solid var(--border-color);
            justify-content: center;
        }

        .modal-icon {
            font-size: 4rem;
            margin-bottom: 15px;
        }

        .modal-icon.success { color: #10b981; }
        .modal-icon.error { color: #ef4444; }
        .modal-icon.warning { color: #f59e0b; }

        /* Success Toast */
        .toast-success-custom {
            background: var(--success-gradient);
            color: white;
            border: none;
        }

        .toast-success-custom .toast-header {
            background: rgba(255,255,255,0.15);
            color: white;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .toast-success-custom .toast-header .btn-close {
            filter: brightness(0) invert(1);
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
            .building-selector {
                flex-direction: column;
                align-items: stretch;
            }
            .building-selector select {
                min-width: auto;
            }
            .amenities-card .card-header {
                flex-direction: column;
                text-align: center;
            }
            .amenities-table thead th,
            .amenities-table tbody td {
                padding: 8px 10px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .amenities-table {
                font-size: 0.75rem;
            }
            .btn-add-unit {
                font-size: 0.7rem;
                padding: 4px 12px;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid main-container">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="page-header">
                <div>
                    <h1>
                        <i class="fas fa-building me-3"></i>Campus Amenities Management
                    </h1>
                    <p>Manage amenity counts and units for your campus buildings</p>
                    <div class="header-stats">
                        <span class="stat-item">
                            <i class="fas fa-cubes"></i>
                            <strong id="totalAmenities">0</strong> Total Amenities
                        </span>
                        <span class="stat-item">
                            <i class="fas fa-cube"></i>
                            <strong id="totalUnits">0</strong> Total Units
                        </span>
                    </div> 
                </div>
                <div>
                    <a href="{{ route('institute.admin.amenities.index') }}" class="btn btn-light">
                        <i class="fas fa-plus-circle me-2"></i>Add New Amenity
                    </a>
                </div>
            </div>

            <!-- Building Selector -->
            <div class="building-selector">
                <label for="buildingSelect"><i class="fas fa-building me-2"></i>Select Building:</label>
                <select id="buildingSelect" onchange="changeBuilding()">
                    <option value="">-- Select a Building --</option>
                    @foreach($buildings as $building)
                        <option value="{{ $building->id }}" {{ $selectedBuilding && $selectedBuilding->id == $building->id ? 'selected' : '' }}>
                            {{ $building->name }}
                        </option>
                    @endforeach
                </select>
                @if($selectedBuilding)
                    <span class="badge bg-primary">{{ $selectedBuilding->name }}</span>
                @endif
            </div>

            <!-- Amenities List -->
            <div class="amenities-card">
                <div class="card-header">
                    <h5>
                        <i class="fas fa-list-ul me-2" style="color: var(--primary-color);"></i>
                        Amenities
                        <span class="badge bg-purple ms-2" id="amenityCountBadge">0</span>
                    </h5>
                    <div>
                        <button class="btn btn-sm btn-outline-primary" onclick="refreshAmenities()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body" id="amenitiesContainer">
                    <div class="text-center py-5">
                        <i class="fas fa-building" style="font-size: 3rem; color: #a0aec0;"></i>
                        <h5 class="mt-3 text-muted">Select a building to view amenities</h5>
                        <p class="text-muted">Choose a building from the dropdown above to manage its amenities.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Unit Creation Loading Modal -->
<div class="modal fade" id="unitCreationModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 class="mt-3">Creating Unit...</h5>
                <p class="text-muted">Please wait while we create your new unit.</p>
            </div>
        </div>
    </div>
</div>

<!-- Success Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="successToast" class="toast toast-success-custom" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="fas fa-check-circle me-2"></i>
            <strong class="me-auto">Success</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="successToastMessage">Unit and specifications added successfully!</div>
    </div>
</div>

<!-- Regular Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="bottom: 80px;">
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
const CSRF_TOKEN = '{{ csrf_token() }}';
const CAMPUS_AMENITY_URL = '{{ url("/institute/admin/campus/amenity-unit") }}';

// Check for success message from URL parameter
$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var success = urlParams.get('success');
    var message = urlParams.get('message');
    
    if (success === 'true' && message) {
        showSuccessToast(decodeURIComponent(message));
    }
    
    var buildingId = document.getElementById('buildingSelect').value;
    if (buildingId) {
        loadAmenities(buildingId);
    }
});

// ==========================================
// SHOW SUCCESS TOAST
// ==========================================
function showSuccessToast(message) {
    var toast = document.getElementById('successToast');
    document.getElementById('successToastMessage').textContent = message;
    var bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 6000
    });
    bsToast.show();
}

// ==========================================
// CHANGE BUILDING
// ==========================================
function changeBuilding() {
    var buildingId = document.getElementById('buildingSelect').value;
    if (buildingId) {
        loadAmenities(buildingId);
    } else {
        document.getElementById('amenitiesContainer').innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-building" style="font-size: 3rem; color: #a0aec0;"></i>
                <h5 class="mt-3 text-muted">Select a building to view amenities</h5>
                <p class="text-muted">Choose a building from the dropdown above to manage its amenities.</p>
            </div>
        `;
        document.getElementById('amenityCountBadge').textContent = '0';
        document.getElementById('totalAmenities').textContent = '0';
        document.getElementById('totalUnits').textContent = '0';
    }
}

// ==========================================
// LOAD AMENITIES
// ==========================================
function loadAmenities(buildingId) {
    $('#loadingSpinner').fadeIn();
    
    $.ajax({
        url: '/institute/admin/campus/amenities/' + buildingId,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                renderAmenities(response.data, response.building);
                document.getElementById('amenityCountBadge').textContent = response.total || 0;
                document.getElementById('totalAmenities').textContent = response.total || 0;
                
                // Count total units
                var totalUnits = 0;
                if (response.data) {
                    $.each(response.data, function(index, amenity) {
                        totalUnits += amenity.unit_count || 0;
                    });
                }
                document.getElementById('totalUnits').textContent = totalUnits;
            } else {
                showToast(response.message || 'Failed to load amenities.', 'error');
            }
        },
        error: function(xhr) {
            showToast('Unable to load amenities. Please try again.', 'error');
            console.error('Error loading amenities:', xhr);
        },
        complete: function() {
            $('#loadingSpinner').fadeOut();
        }
    });
}

// ==========================================
// RENDER AMENITIES
// ==========================================
function renderAmenities(amenities, building) {
    var container = document.getElementById('amenitiesContainer');
    
    if (!amenities || amenities.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-cube"></i>
                <h4>No Amenities Found</h4>
                <p class="text-muted">This building has no amenities configured yet.</p>
                <a href="{{ route('institute.admin.amenities.index') }}" class="btn btn-purple mt-3">
                    <i class="fas fa-plus-circle me-2"></i>Add Amenities
                </a>
            </div>
        `;
        return;
    }
    
    var html = `
        <div class="table-responsive">
            <table class="amenities-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Asset Name</th>
                        <th>Category</th>
                        <th style="width: 160px;">Units</th>
                        <th style="width: 140px;">Action</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    $.each(amenities, function(index, amenity) {
        var unitCount = amenity.unit_count || 0;
        html += `
            <tr>
                <td>${index + 1}</td>
                <td>
                    <i class="fas fa-cube text-primary me-2"></i>
                    ${escapeHtml(amenity.name)}
                </td>
                <td>
                    <span class="badge bg-info">${escapeHtml(amenity.category_name || 'N/A')}</span>
                </td>
                <td>
                    <button class="units-btn" onclick="viewAmenityUnits('${amenity.amenity_id}')" title="View Units">
                        <i class="fas fa-eye me-1"></i>
                        View
                        <span class="badge-count" id="unit-count-${amenity.amenity_id}">${unitCount}</span>
                    </button>
                </td>
                <td>
                    <button class="btn-add-unit" onclick="addUnit('${amenity.amenity_id}', '${escapeHtml(amenity.name)}')">
                        <i class="fas fa-plus me-1"></i>Add Unit
                    </button>
                </td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
        <div class="text-muted small mt-2">
            <i class="fas fa-info-circle me-1"></i>
            Click <strong>"Add Unit"</strong> to create a new unit and redirect to the specifications page.
            Click <strong>"Counts"</strong> to view all units for an amenity.
        </div>
    `;
    
    container.innerHTML = html;
}

// ==========================================
// ADD UNIT - Redirect to Specifications Page
// ==========================================
function addUnit(amenityId, amenityName) {
    // Show loading modal
    var modal = new bootstrap.Modal(document.getElementById('unitCreationModal'));
    modal.show();
    
    $.ajax({
        url: '/institute/admin/campus/amenities/' + amenityId + '/units',
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            count: 1
        },
        success: function(response) {
            console.log('Success response:', response);
            if (response.success && response.data.units && response.data.units.length > 0) {
                var unit = response.data.units[0];
                var unitId = unit.unit_id;
                var unitName = unit.name;
                var amenityName = response.data.amenity_name || 'Amenity';
                
                // Close the loading modal
                modal.hide();
                
                // Create return URL with success message
                var returnUrl = encodeURIComponent(
                    window.location.href + 
                    '?success=true&message=' + 
                    encodeURIComponent('Unit "' + unitName + '" created successfully! Please add specifications.')
                );
                
                // Redirect to unit specifications page
                window.location.href = CAMPUS_AMENITY_URL + '/' + unitId + '/specifications?return_url=' + returnUrl;
            } else {
                modal.hide();
                showToast(response.message || 'Failed to create unit. Please try again.', 'error');
            }
        },
        error: function(xhr) {
            modal.hide();
            console.error('Error response:', xhr);
            var errorMessage = xhr.responseJSON?.message || 'Failed to create unit. Please try again.';
            showToast(errorMessage, 'error');
        }
    });
}

// ==========================================
// VIEW AMENITY UNITS - Redirect to New Page
// ==========================================
function viewAmenityUnits(amenityId) {
    // Redirect to the units view page
    window.location.href = '/institute/admin/campus/amenities/' + amenityId + '/units/view';
}

// ==========================================
// REFRESH AMENITIES
// ==========================================
function refreshAmenities() {
    var buildingId = document.getElementById('buildingSelect').value;
    if (buildingId) {
        loadAmenities(buildingId);
        showToast('Amenities refreshed!', 'success');
    } else {
        showToast('Please select a building first.', 'warning');
    }
}

// ==========================================
// TOAST NOTIFICATION
// ==========================================
function showToast(message, type = 'success') {
    var toast = document.getElementById('liveToast');
    var toastMessage = document.getElementById('toastMessage');
    var icon = type === 'success' ? 'fa-check-circle' : 
               type === 'warning' ? 'fa-exclamation-triangle' : 'fa-exclamation-circle';
    var color = type === 'success' ? '#10b981' : 
                type === 'warning' ? '#f59e0b' : '#ef4444';
    
    toast.querySelector('.toast-header i')
        .className = 'fas ' + icon + ' me-2';
    toast.querySelector('.toast-header i').style.color = color;
    toastMessage.textContent = message;
    
    var bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 4000
    });
    bsToast.show();
}

// ==========================================
// ESCAPE HTML
// ==========================================
function escapeHtml(text) {
    if (!text) return '';
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
}
</script>

</body>
</html>
@endsection
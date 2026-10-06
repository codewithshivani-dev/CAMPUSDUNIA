@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Units - {{ $amenity->name }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

        /* Page Header */
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

        .btn-outline-light {
            border: 2px solid rgba(255,255,255,0.3);
            color: white;
            background: rgba(255,255,255,0.1);
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-outline-light:hover {
            background: rgba(255,255,255,0.2);
            color: white;
            transform: translateY(-2px);
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

        .back-btn {
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            transform: translateX(-5px);
        }

        /* Stats Row */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .stats-card {
            background: white;
            padding: 18px 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
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

        .stats-card.purple .stats-icon { color: var(--primary-color); }
        .stats-card.green .stats-icon { color: #10b981; }
        .stats-card.orange .stats-icon { color: #f59e0b; }
        .stats-card.red .stats-icon { color: #ef4444; }

        /* Units Card */
        .units-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .units-card .card-header {
            background: #f8fafc;
            padding: 18px 25px;
            border-bottom: 2px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .units-card .card-header h5 {
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        .units-card .card-body {
            padding: 25px;
        }

        /* Units Table */
        .units-table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        .units-table thead th {
            background: #f8fafc;
            padding: 12px 15px;
            text-align: left;
            font-weight: 700;
            color: var(--text-dark);
            border-bottom: 2px solid var(--border-color);
            font-size: 0.9rem;
        }

        .units-table tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .units-table tbody tr:hover {
            background: #f8fafc;
        }

        /* Status Badges */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-available {
            background: #d4edda;
            color: #155724;
        }

        .status-assigned {
            background: #fff3cd;
            color: #856404;
        }

        .status-maintenance {
            background: #f8d7da;
            color: #721c24;
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

        /* Date formatting */
        .date-text {
            font-size: 0.85rem;
            color: var(--text-muted);
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
            .units-card .card-header {
                flex-direction: column;
                text-align: center;
            }
            .units-table thead th,
            .units-table tbody td {
                padding: 8px 10px;
                font-size: 0.8rem;
            }
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
            .units-table {
                white-space: normal;
            }
        }

        @media (max-width: 480px) {
            .units-table {
                font-size: 0.75rem;
            }
            .stats-row {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }
            .stats-card .stats-number {
                font-size: 1.4rem;
            }
            .date-text {
                font-size: 0.7rem;
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
                        <i class="fas fa-cubes me-3"></i>Units
                    </h1>
                    <p>
                        <i class="fas fa-cube me-2"></i>
                        {{ $amenity->name }}
                        <span class="ms-3">
                            <i class="fas fa-tag me-1"></i>
                            {{ $amenity->category_name ?? 'No Category' }}
                        </span>
                        <span class="ms-3">
                            <i class="fas fa-hashtag me-1"></i>
                            {{ $amenity->amenity_id }}
                        </span>
                    </p>
                    <div class="header-stats">
                        <span class="stat-item">
                            <i class="fas fa-cube"></i>
                            <strong>{{ $units->count() }}</strong> Total Units
                        </span>
                        <span class="stat-item" style="background: rgba(255,255,255,0.25);">
                            <i class="fas fa-check-circle"></i>
                            <strong>{{ $units->where('status', 'available')->count() }}</strong> Available
                        </span>
                        <span class="stat-item" style="background: rgba(255,255,255,0.15);">
                            <i class="fas fa-clock"></i>
                            <strong>{{ $units->where('status', 'assigned')->count() }}</strong> Assigned
                        </span>
                        <span class="stat-item" style="background: rgba(255,255,255,0.1);">
                            <i class="fas fa-tools"></i>
                            <strong>{{ $units->where('status', 'maintenance')->count() }}</strong> Maintenance
                        </span>
                    </div>
                </div>
                <div>
                    <a href="{{ route('institute.admin.campus.amenities') }}" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>Back to Amenities
                    </a>
                </div>
            </div>

            <!-- Units List -->
            <div class="units-card">
                <div class="card-header">
                    <h5>
                        <i class="fas fa-list-ul me-2" style="color: var(--primary-color);"></i>
                        Units List
                        <span class="badge bg-purple ms-2">{{ $units->count() }}</span>
                    </h5>
                    <div>
                        <button class="btn btn-sm btn-outline-primary" onclick="window.location.reload();">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($units->count() > 0)
                        <div class="table-responsive">
                            <table class="units-table" id="unitsTable">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">#</th>
                                        <th style="width: 160px;">Unit ID</th>
                                        <th style="width: 100px;">Unit Number</th>
                                        <th>Name</th>
                                        <th style="width: 100px;">Status</th>
                                        <th style="width: 180px;">Assigned To</th>
                                        <th style="width: 150px;">Created At</th>
                                        <th style="width: 150px;">Updated At</th>
                                        <th style="width: 80px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($units as $index => $unit)
                                        @php
                                            $statusClass = 'status-available';
                                            $statusText = 'Available';
                                            if($unit->status == 'assigned') {
                                                $statusClass = 'status-assigned';
                                                $statusText = 'Assigned';
                                            } elseif($unit->status == 'maintenance') {
                                                $statusClass = 'status-maintenance';
                                                $statusText = 'Maintenance';
                                            }
                                            
                                            $assignedLocation = 'Not Assigned';
                                            if($unit->assigned_to_block) {
                                                $assignedLocation = $unit->assigned_to_block;
                                                if($unit->assigned_to_floor) {
                                                    $assignedLocation .= ' - Floor ' . $unit->assigned_to_floor;
                                                }
                                                if($unit->assigned_to_room) {
                                                    $assignedLocation .= ' - Room ' . $unit->assigned_to_room;
                                                }
                                            }
                                            
                                            $createdAt = $unit->created_at ? date('d M Y h:i A', strtotime($unit->created_at)) : 'N/A';
                                            $updatedAt = $unit->updated_at ? date('d M Y h:i A', strtotime($unit->updated_at)) : 'N/A';
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <code class="badge bg-primary" style="font-size: 0.85rem; padding: 6px 12px;">
                                                    {{ $unit->unit_id }}
                                                </code>
                                            </td>
                                            <td><strong>{{ $unit->unit_number }}</strong></td>
                                            <td>{{ $unit->name }}</td>
                                            <td>
                                                <span class="status-badge {{ $statusClass }}">
                                                    {{ $statusText }}
                                                </span>
                                            </td>
                                            <td>{{ $assignedLocation }}</td>
                                            <td class="date-text">{{ $createdAt }}</td>
                                            <td class="date-text">{{ $updatedAt }}</td>
                                            <td>
                                                <a href="{{ url("/institute/admin/campus/amenity-unit") }}/{{ $unit->unit_id }}/specifications" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   target="_blank"
                                                   title="Edit Specifications">
                                                    <i class="fas fa-cog"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-muted small mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Click the <strong><i class="fas fa-cog"></i></strong> button to edit specifications for a unit.
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-cube"></i>
                            <h4>No Units Found</h4>
                            <p class="text-muted">This amenity has no units yet.</p>
                            <a href="{{ route('institute.admin.campus.amenities') }}" class="btn btn-purple mt-3">
                                <i class="fas fa-arrow-left me-2"></i>Go Back
                            </a>
                        </div>
                    @endif
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
        <div class="toast-body" id="toastMessage"> Operation completed successfully.</div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // You can add DataTable initialization here if needed
    // Example: $('#unitsTable').DataTable();
});

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
</script>

</body>
</html>
@endsection
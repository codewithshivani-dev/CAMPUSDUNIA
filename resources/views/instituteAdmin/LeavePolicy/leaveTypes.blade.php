{{-- resources/views/instituteAdmin/LeavePolicy/leaveTypes.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --success-color: #10b981;
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    /* Page Header */
    .page-header {
        background: var(--primary-gradient);
        border-radius: 20px;
        padding: 25px 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.25);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    }

    .page-title {
        color: white;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 1;
    }

    .page-title i {
        font-size: 2rem;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
    }

    /* Form Card */
    .form-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border: 2px solid rgba(67, 97, 238, 0.1);
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .form-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.12);
    }

    .form-card h5 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-color);
        display: flex;
        align-items: center;
    }

    .form-card h5 i {
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Form Controls */
    .form-label {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 0.9rem;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control {
        border-radius: 12px;
        border: 2px solid rgba(67, 97, 238, 0.2);
        padding: 12px 18px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #fafbfc;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        background: white;
        outline: none;
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    .text-danger {
        color: #dc2626 !important;
    }

    .text-muted {
        color: #64748b !important;
        font-size: 0.85rem;
    }

    small.text-muted {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    small.text-muted::before {
        content: '\F431';
        font-family: 'bootstrap-icons';
        color: #3b82f6;
    }

    /* Button Styles */
    .btn {
        padding: 12px 24px;
        font-weight: 700;
        border-radius: 40px;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .btn i {
        margin-right: 6px;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        transition: left 0.3s ease;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }

    .btn-success {
        background: var(--success-gradient);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-success:hover {
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }

    .btn-outline-danger {
        background: transparent;
        border: 2px solid #ef4444;
        color: #ef4444;
    }

    .btn-outline-danger:hover {
        background: var(--danger-gradient);
        border-color: transparent;
        color: white;
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 0.8rem;
    }

    /* Table Card */
    .table-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border: 2px solid rgba(67, 97, 238, 0.1);
        backdrop-filter: blur(10px);
    }

    .table-responsive {
        border-radius: 20px;
        overflow-x: auto;
        overflow-y: hidden;
    }

    /* Hide horizontal scrollbar */
    .table-responsive::-webkit-scrollbar {
        width: 6px;
        height: 0;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 8px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: var(--primary-gradient);
        border-radius: 8px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: var(--secondary-color);
    }

    .table-responsive::-webkit-scrollbar-horizontal {
        display: none;
    }

    /* Table Styles */
    .table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table thead{
        background: var(--primary-gradient);
    }

    .table thead th {
        color: white;
        border: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 16px 20px;
    }

    .table tbody tr {
        border-bottom: 1px solid rgba(67, 97, 238, 0.08);
        transition: background 0.3s ease;
    }

    .table tbody tr:last-child {
        border-bottom: none;
    }

    .table tbody tr:hover {
        background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(58, 12, 163, 0.05));
    }

    .table tbody td {
        padding: 16px 20px;
        vertical-align: middle;
        border: none;
    }

    .table tbody td strong {
        color: var(--primary-color);
    }

    /* Custom Type Row */
    .custom-type-row {
        background: linear-gradient(135deg, #fef3c7, #fde68a) !important;
        border-left: 4px solid #f59e0b !important;
    }

    .custom-type-row:hover {
        background: linear-gradient(135deg, #fde68a, #fcd34d) !important;
    }

    /* Status Badges */
    .status-badge-active {
        background: var(--success-gradient);
        color: white;
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 3px 10px rgba(16, 185, 129, 0.3);
        display: inline-block;
    }

    .status-badge-inactive {
        background: var(--danger-gradient);
        color: white;
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 3px 10px rgba(239, 68, 68, 0.3);
        display: inline-block;
    }

    /* Badge Styles */
    .badge {
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge.bg-warning {
        background: var(--warning-gradient) !important;
        color: white;
        box-shadow: 0 3px 10px rgba(245, 158, 11, 0.3);
    }

    .badge.bg-secondary {
        background: linear-gradient(135deg, #64748b, #475569) !important;
        color: white;
        box-shadow: 0 3px 10px rgba(100, 116, 139, 0.3);
    }

    /* Animation for table rows */
    @keyframes fadeInRow {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .table tbody tr {
        animation: fadeInRow 0.4s ease;
    }

    /* Custom Scrollbar for body */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 8px;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--primary-gradient);
        border-radius: 8px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--secondary-color);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .page-header {
            padding: 20px;
        }
        
        .form-card {
            padding: 20px;
        }
        
        .table tbody tr {
            display: block;
            margin-bottom: 15px;
            border: 2px solid rgba(67, 97, 238, 0.1);
            border-radius: 15px;
            padding: 15px;
        }
        
        .table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 5px;
            text-align: right;
        }
        
        .table tbody td::before {
            content: attr(data-label);
            font-weight: bold;
            color: var(--primary-color);
            margin-right: 10px;
            text-transform: uppercase;
        }
    }
</style>

<div class="container-fluid">
    <div class="page-header">
        <h2 class="page-title">
            <i class="bi bi-calendar-check"></i>
            Leave Types 
        </h1>
    </div>

    <!-- Add Custom Leave Type Form -->
    <div class="form-card">
        <h5 class="fw-semibold mb-4">
            <i class="bi bi-plus-circle me-2"></i>
            Add Custom Leave Type
        </h5>
        
        <form id="customLeaveForm">
            @csrf
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label">
                        <i class="bi bi-pencil-square me-1"></i>Custom Leave Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="custom_leave_type" id="customLeaveTypeName" class="form-control" 
                           placeholder="e.g., Study Leave, Marriage Leave" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-plus-circle me-1"></i>Add Leave Type
                    </button>
                </div>
            </div>
            <div>
                <div class="mt-2">
                    <small class="text-muted">
                        After adding, configure deduction percentages in the Deduction Configuration page.
                    </small>
                </div>
            </div>
        </form>
    </div>

    <!-- Leave Types Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="sortable">Leave Type</th>
                        <th class="sortable">Type</th>
                        <th class="sortable">Status</th>
                        <th class="sortable">Actions</th>
                    </tr>
                </thead>
                <tbody id="leaveTypesTableBody">
                    @foreach($leaveTypes as $index => $leaveType)
                    <tr class="{{ $leaveType['is_custom'] ? 'custom-type-row' : '' }}" data-id="{{ $leaveType['id'] ?? '' }}">
                        <td data-label="#">{{ $index + 1 }}</td>
                        <td data-label="Leave Type">
                            <strong>
                                <i class="bi bi-bookmark-fill me-2" style="color: var(--primary-color);"></i>
                                {{ $leaveType['leave_type'] }}
                            </strong>
                         </td>
                        <td data-label="Type">
                            @if($leaveType['is_custom'])
                                <span class="badge bg-warning">
                                    <i class="bi bi-gear-fill me-1"></i>Custom
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-shield-fill me-1"></i>Default
                                </span>
                            @endif
                         </td>
                        <td data-label="Status">
                            <span class="status-badge-{{ $leaveType['is_active'] ? 'active' : 'inactive' }}">
                                @if($leaveType['is_active'])
                                    <i class="bi bi-check-circle-fill me-1"></i>
                                @else
                                    <i class="bi bi-x-circle-fill me-1"></i>
                                @endif
                                {{ $leaveType['is_active'] ? 'Active' : 'Inactive' }}
                            </span>
                         </td>
                        <td data-label="Actions">
                            @if($leaveType['is_custom'])
                                <button class="btn btn-sm btn-outline-danger delete-custom-btn" 
                                        data-id="{{ $leaveType['id'] }}" 
                                        data-name="{{ $leaveType['leave_type'] }}">
                                    <i class="bi bi-trash me-1"></i>Delete
                                </button>
                            @else
                                <span class="text-muted">
                                    <i class="bi bi-lock me-1"></i>Default Type
                                </span>
                            @endif
                         </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
let csrfToken = '{{ csrf_token() }}';

$(document).ready(function() {
    
    // Add custom leave type
    $('#customLeaveForm').on('submit', function(e) {
        e.preventDefault();
        
        let leaveType = $('#customLeaveTypeName').val().trim();
        
        if (!leaveType) {
            Swal.fire({
                icon: 'warning',
                title: 'Name Required',
                text: 'Please enter custom leave type name',
                confirmButtonColor: '#4361ee'
            });
            return;
        }
        
        // Check if leave type already exists
        let exists = false;
        $('tbody tr').each(function() {
            let typeName = $(this).find('td:eq(1) strong').text();
            if (typeName.toLowerCase() === leaveType.toLowerCase()) {
                exists = true;
                return false;
            }
        });
        
        if (exists) {
            Swal.fire({
                icon: 'warning',
                title: 'Already Exists',
                text: 'This leave type already exists in the list!',
                confirmButtonColor: '#4361ee'
            });
            return;
        }
        
        // Save to database
        $.ajax({
            url: '{{ route("leave.types.store") }}',
            method: 'POST',
            data: {
                leave_type: leaveType,
                is_active: 1,
                _token: csrfToken
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Added Successfully!',
                        text: response.message,
                        confirmButtonColor: '#4361ee',
                        timer: 3000,
                        showConfirmButton: true
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: response.message,
                        confirmButtonColor: '#4361ee'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to add leave type',
                    confirmButtonColor: '#4361ee'
                });
            }
        });
    });

    // Delete custom leave type
    $(document).on('click', '.delete-custom-btn', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        
        Swal.fire({
            title: 'Delete Custom Leave Type?',
            text: `Are you sure you want to delete "${name}"? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ route("leave.types.destroy", "") }}/${id}`,
                    method: 'DELETE',
                    data: { _token: csrfToken },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Custom leave type has been deleted.',
                                confirmButtonColor: '#4361ee',
                                timer: 1500,
                                showConfirmButton: true
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed',
                                text: response.message,
                                confirmButtonColor: '#4361ee'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete. Please try again.',
                            confirmButtonColor: '#4361ee'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endsection 
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<style>
    .card-header-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .stat-card {
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: bold;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .discount-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-percentage {
        background: #d1e7dd;
        color: #0f5132;
        border: 1px solid #badbcc;
    }

    .badge-flat {
        background: #cfe2ff;
        color: #084298;
        border: 1px solid #b6d4fe;
    }

    .applicability-badge {
        background: #fff3cd;
        color: #664d03;
        border: 1px solid #ffecb5;
    }

    .student-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
    }

    .action-buttons .btn {
        padding: 4px 12px;
        font-size: 0.85rem;
    }

    .search-box {
        position: relative;
        max-width: 300px;
    }

    .search-box .form-control {
        padding-left: 40px;
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .date-filter {
        max-width: 200px;
    }

    .table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
        border-top: none;
    }

    .table td {
        vertical-align: middle;
    }

    .no-data {
        text-align: center;
        padding: 50px 20px;
        color: #6c757d;
    }

    .no-data i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.3;
    }

    .pagination-container {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-top: 20px;
    }

    .revoke-btn {
        transition: all 0.3s ease;
    }

    .revoke-btn:hover {
        transform: scale(1.05);
    }

    .modal-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .discount-amount {
        font-weight: bold;
        font-size: 1.1rem;
    }

    .text-success {
        color: #198754 !important;
    }

    .filter-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .status-active {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #198754;
        margin-right: 5px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state-icon {
        font-size: 60px;
        color: #dee2e6;
        margin-bottom: 20px;
    }
</style>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-header card-header-custom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0"><i class="fas fa-tags me-2"></i>Assigned Discounts</h4>
                    <p class="mb-0 opacity-75">View and manage all assigned class fee discounts</p>
                </div>
                <a href="{{ route('course-fee.discount.assign') }}" class="btn btn-light">
                    <i class="fas fa-plus me-2"></i>Assign New Discount
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-users stat-icon text-primary"></i>
                        </div>
                        <div>
                            <div class="stat-value text-primary">{{ $totalAssigned }}</div>
                            <div class="stat-label">Total Discounts Assigned</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-user-graduate stat-icon text-success"></i>
                        </div>
                        <div>
                            <div class="stat-value text-success">{{ $uniqueStudents }}</div>
                            <div class="stat-label">Unique Students</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-rupee-sign stat-icon text-danger"></i>
                        </div>
                        <div>
                            <div class="stat-value text-danger">₹{{ number_format($totalDiscountAmount, 2) }}</div>
                            <div class="stat-label">Total Discount Amount</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filter-card shadow-sm mb-4">
        <form method="GET" action="{{ route('course-fee.discounts.assigned') }}" id="filterForm">
            <div class="row">
                <div class="col-md-4">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Search by student, discount, course..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <input type="date" 
                           name="start_date" 
                           class="form-control date-filter" 
                           value="{{ request('start_date') }}"
                           placeholder="From Date">
                </div>
                <div class="col-md-3">
                    <input type="date" 
                           name="end_date" 
                           class="form-control date-filter" 
                           value="{{ request('end_date') }}"
                           placeholder="To Date">
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('course-fee.discounts.assigned') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Discounts Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            @if($assignedDiscounts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Discount Details</th>
                                <th>Amount</th>
                                <th>Applicability</th>
                                <th>Assigned On</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignedDiscounts as $index => $assignment)
                                @php
                                    $student = $assignment->student;
                                    $product = $assignment->product;
                                    $discount = $assignment->discount;
                                    $firstName = $student->first_name ? explode(' ', $student->first_name)[0] : '';
                                    $avatarText = $firstName ? strtoupper(substr($firstName, 0, 1)) : '?';
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 + (($assignedDiscounts->currentPage() - 1) * $assignedDiscounts->perPage()) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="student-avatar me-3">
                                                {{ $avatarText }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $student->first_name ?? 'N/A' }} {{ $student->middle_name ?? '' }} {{ $student->last_name ?? '' }}</div>
                                                <small class="text-muted">{{ $student->registration_number ?? 'No Reg No' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold">{{ $product->sub_type ?? 'N/A' }}</div>
                                            <small class="text-muted">ID: {{ $assignment->product_id }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold mb-1">{{ $assignment->discount_name }}</div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-primary">{{ $assignment->discount_coupon_id ?? 'N/A' }}</span>
                                                <span class="discount-badge {{ $assignment->discount_type === 'percentage' ? 'badge-percentage' : 'badge-flat' }}">
                                                    {{ $assignment->discount_type === 'percentage' ? $assignment->discount_value . '%' : '₹' . number_format($assignment->discount_value, 2) }}
                                                </span>
                                            </div>
                                            @if($discount && $discount->description)
                                                <small class="text-muted mt-1 d-block">{{ Str::limit($discount->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="discount-amount text-success">
                                            ₹{{ number_format($assignment->discount_amount, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="applicability-badge discount-badge">
                                            {{ ucfirst(str_replace('_', ' ', $assignment->discount_applicability)) }}
                                            @if($assignment->discount_duration_type)
                                                <br><small class="text-muted">({{ $assignment->discount_duration_type }})</small>
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <div>{{ $assignment->created_at->format('d M, Y') }}</div>
                                            <small class="text-muted">{{ $assignment->created_at->format('h:i A') }}</small>
                                            @if($assignment->applied_by)
                                                <div class="small text-muted mt-1">
                                                    By: {{ $assignment->appliedBy->name ?? 'Admin' }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-flex align-items-center">
                                            <span class="status-active"></span>
                                            <span class="text-success">Active</span>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons d-flex gap-2">
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger revoke-btn"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#revokeModal"
                                                    data-id="{{ $assignment->id }}"
                                                    data-student="{{ $student->full_name }}"
                                                    data-discount="{{ $assignment->discount_name }}">
                                                <i class="fas fa-ban me-1"></i>Revoke
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-info"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailsModal"
                                                    onclick="showDiscountDetails({{ json_encode($assignment) }}, {{ json_encode($student) }}, {{ json_encode($product) }}, {{ json_encode($discount) }})">
                                                <i class="fas fa-eye me-1"></i>View
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-container">
                    {{ $assignedDiscounts->appends(request()->query())->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h4>No Discounts Assigned Yet</h4>
                    <p class="text-muted mb-4">No students have been assigned discounts yet.</p>
                    <a href="{{ route('course-fee.discount.assign') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Assign First Discount
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Revoke Discount Modal -->
<div class="modal fade" id="revokeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-ban me-2"></i>Revoke Discount</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Are you sure you want to revoke this discount? This action cannot be undone.
                </div>
                <p>You are about to revoke the discount <strong id="discountNameRevoke"></strong> from student <strong id="studentNameRevoke"></strong>.</p>
                <p class="text-danger"><small>This will deactivate the discount for this student.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmRevoke">Revoke Discount</button>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Discount Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="discountDetailsContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let currentDiscountId = null;

    // Handle revoke modal
    $('#revokeModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        currentDiscountId = button.data('id');
        const studentName = button.data('student');
        const discountName = button.data('discount');
        
        $('#studentNameRevoke').text(studentName);
        $('#discountNameRevoke').text(discountName);
    });

    // Handle revoke confirmation
    $('#confirmRevoke').on('click', function() {
        if (!currentDiscountId) return;

        const button = $(this);
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Processing...');

        $.ajax({
            url: '{{ url("/revoke-discount") }}/' + currentDiscountId,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                    $('#revokeModal').modal('hide');
                    // Reload the page after 1 second
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showToast(response.message, 'danger');
                    button.prop('disabled', false).html('Revoke Discount');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Failed to revoke discount';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showToast(errorMessage, 'danger');
                button.prop('disabled', false).html('Revoke Discount');
            }
        });
    });

    // Date filter validation
    $('input[type="date"]').on('change', function() {
        const startDate = $('input[name="start_date"]').val();
        const endDate = $('input[name="end_date"]').val();
        
        if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
            alert('End date cannot be before start date');
            $(this).val('');
        }
    });

    // Toast notification function
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        const toastContainer = document.querySelector('.toast-container') || (() => {
            const container = document.createElement('div');
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(container);
            return container;
        })();
        
        toastContainer.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
    }
});

// Show discount details
function showDiscountDetails(assignment, student, product, discount) {
    const content = `
        <div class="row">
            <div class="col-md-6">
                <h6 class="border-bottom pb-2 mb-3">Student Information</h6>
                <div class="mb-3">
                    <strong>Name:</strong> ${student.first_name || 'N/A'} ${student.middle_name || ''} ${student.last_name || ''}<br>
                    <strong>Registration No:</strong> ${student.registration_number || 'N/A'}<br>
                    <strong>Student ID:</strong> ${assignment.student_hash_id}<br>
                    <strong>Contact:</strong> ${student.mobile || 'N/A'}
                </div>
            </div>
            <div class="col-md-6">
                <h6 class="border-bottom pb-2 mb-3">Class Information</h6>
                <div class="mb-3">
                    <strong>Class:</strong> ${product.sub_type || 'N/A'}<br>
    
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <h6 class="border-bottom pb-2 mb-3">Discount Information</h6>
                <div class="mb-3">
                    <strong>Discount Name:</strong> ${assignment.discount_name}<br>
                    <strong>Coupon Code:</strong> <span class="badge bg-primary">${assignment.discount_coupon_id || 'N/A'}</span><br>
                    <strong>Type:</strong> <span class="badge ${assignment.discount_type === 'percentage' ? 'bg-success' : 'bg-info'}">
                        ${assignment.discount_type.toUpperCase()}
                    </span><br>
                   
                    <strong>Amount:</strong> <span class="text-success fw-bold">₹${assignment.discount_amount}</span>
                </div>
            </div>
            <div class="col-md-6">
                <h6 class="border-bottom pb-2 mb-3">Assignment Details</h6>
                <div class="mb-3">
                    <strong>Applicability:</strong> ${assignment.discount_applicability}<br>
                    <strong>Assigned On:</strong> ${new Date(assignment.created_at).toLocaleString()}<br>
                    <strong>Status:</strong> <span class="badge bg-success">Active</span>
                </div>
            </div>
        </div>

        ${discount && discount.description ? `
            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="border-bottom pb-2 mb-3">Discount Description</h6>
                    <div class="alert alert-info">
                        ${discount.description}
                    </div>
                </div>
            </div>
        ` : ''}
    `;

    $('#discountDetailsContent').html(content);
}

// Auto-submit form on date change for better UX
$(document).on('change', 'input[name="start_date"], input[name="end_date"]', function() {
    if ($(this).val()) {
        $('#filterForm').submit();
    }
});
</script>

<!-- Bootstrap Toast CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Toast JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection
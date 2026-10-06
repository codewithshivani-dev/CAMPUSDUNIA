{{-- resources/views/instituteAdmin/Discounts/index.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
.card {
    border-radius: 12px;
    border: 1px solid #e0e0e0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.table th {
    border-top: none;
    font-weight: 600;
    color: #2d3748;
    background-color: #f8fafc;
}
.table td {
    padding: 10px;
    vertical-align: middle;
}
.badge {
    font-size: 0.85em;
    padding: 0.4em 0.8em;
}
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
.bg-course, .bg-custom, .bg-transportation, .bg-hostel{
    background: teal;
}
</style>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3><i class="fas fa-tags me-2"></i>Discounts Management</h3>
                <a href="{{ route('discounts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Create New Discount
                </a>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            @if($discounts->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-tag fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Discounts Found</h5>
                    <p class="text-muted">Create your first discount to get started.</p>
                    <a href="{{ route('discounts.create') }}" class="btn btn-primary mt-2">
                        <i class="fas fa-plus me-1"></i> Create Discount
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Discount ID</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Fee Type</th>
                                <th>Value</th>
                                <th>Valid Period</th>
                                <th>Status</th>
                                <th>Assignments</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($discounts as $discount)
                                <tr>
                                    <td>{{ $loop->iteration + ($discounts->currentPage() - 1) * $discounts->perPage() }}</td>
                                    <td>
                                        <code class="text-primary">{{ $discount->discount_hash_id }}</code>
                                        <br>
                                        <small class="text-muted">{{ $discount->coupon_code }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $discount->name }}</strong>
                                        @if($discount->description)
                                            <br><small class="text-muted">{{ Str::limit($discount->description, 40) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge text-white bg-{{ $discount->type == 'flat' ? 'info' : 'success' }}">
                                            {{ ucfirst($discount->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge text-white bg-{{ $discount->fee_type }}">
                                            {{ ucfirst($discount->fee_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($discount->type == 'percentage')
                                            {{ number_format($discount->value, 0) }}%
                                        @else
                                            ₹{{ number_format($discount->value, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            {{ \Carbon\Carbon::parse($discount->valid_from)->format('d M Y') }}<br>
                                            <strong>to</strong><br>
                                            {{ \Carbon\Carbon::parse($discount->valid_to)->format('d M Y') }}
                                        </small>
                                        <br>
                                        <small class="{{ $discount->isValid() ? 'text-success' : 'text-danger' }}">
                                            {{ $discount->isValid() ? 'Valid' : 'Expired' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($discount->is_active && $discount->isValid())
                                            <span class="badge text-white bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge text-white bg-primary">
                                            {{ $discount->assignments_count ?? 0 }} assigned
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $assignRoute = null;   

                                            switch ($discount->fee_type) {
                                                case 'transportation':
                                                    $assignRoute = route('admin.transport.assign-fee.form', $discount->discount_hash_id);
                                                    break;

                                                case 'hostel':
                                                    $assignRoute = route('admin.hostel-fees.assign.form', $discount->discount_hash_id);
                                                    break;

                                                case 'custom':
                                                    $assignRoute = route('admin.custom-fees.assign.form', $discount->discount_hash_id);
                                                    break;

                                                case 'course':
                                                    $assignRoute = route('course.fee.discount.form', $discount->discount_hash_id);
                                                    break;
                                            }
                                        @endphp
                                        <div class="btn-group btn-group-sm">
                                            @if(($discount->assignments_count ?? 0) > 0)
                                                <button class="btn btn-outline-success" disabled title="Already Assigned">
                                                    <i class="fas fa-check"></i> Assigned
                                                </button>
                                                <a href="#" class="btn btn-outline-info" title="View Assignments">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @else
                                                <a href="{{ $assignRoute }}" 
                                                    class="btn btn-primary" title="Assign Discount">
                                                        <i class="fas fa-link me-1"></i> Assign
                                                </a>
                                            @endif
                                            <!-- <button class="btn btn-outline-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button> -->
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $discounts->firstItem() }} to {{ $discounts->lastItem() }} of {{ $discounts->total() }} entries
                    </div>
                    {{ $discounts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Debug Modal (Optional - remove in production) -->
<div class="modal fade" id="debugModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Discount Debug Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre id="debugContent"></pre>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Debug function to check discount data
    window.debugDiscount = function(discountId) {
        $.ajax({
            url: '/discounts/debug/' + discountId,
            method: 'GET',
            success: function(response) {
                $('#debugContent').text(JSON.stringify(response, null, 2));
                $('#debugModal').modal('show');
            }
        });
    };
    
    // Add hover effects
    $('tr').hover(
        function() {
            $(this).css('background-color', '#f8f9fa');
        },
        function() {
            $(this).css('background-color', '');
        }
    );
});
</script>
@endsection
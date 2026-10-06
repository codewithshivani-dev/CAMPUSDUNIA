@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<div class="container-fluid mt-3">
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-money-bill-wave text-primary me-2"></i>Custom Fee Structures
                    </h4>
                    <p class="text-muted mb-0 small">Manage and track all custom fee configurations</p>
                </div>
                <a href="{{ route('admin.custom-fees.create') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-plus-circle me-2"></i>Add New Fee
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- Filters Card -->
            <div class="card border-light shadow-sm mb-4">
                <div class="card-body p-3">
                    <h6 class="mb-3 fw-semibold text-dark">
                        <i class="fas fa-filter text-secondary me-2"></i>Filter Options
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small text-muted mb-1">Academic Year</label>
                            <select class="form-select form-select-sm" id="filter_year">
                                <option value="">All Years</option>
                                @foreach($academicYears as $key => $value)
                                    @if(is_array($value))
                                        <option value="{{ $value['value'] ?? $key }}">{{ $value['label'] ?? $value }}</option>
                                    @else
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted mb-1">Fee Type</label>
                            <select class="form-select form-select-sm" id="filter_type">
                                <option value="">All Types</option>
                                @foreach($feeTypes as $key => $name)
                                    <option value="{{ $key }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted mb-1">Status</label>
                            <select class="form-select form-select-sm" id="filter_status">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-outline-secondary btn-sm w-100 rounded-pill" onclick="applyFilters()">
                                <i class="fas fa-sliders-h me-1"></i>Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 bg-gradient-primary text-white shadow-sm">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 opacity-75">Total Fees</h6>
                                    <h3 class="mb-0 fw-bold">{{ $fees->total() }}</h3>
                                </div>
                                <i class="fas fa-list-alt fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-gradient-success text-white shadow-sm">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 opacity-75">Active</h6>
                                    <h3 class="mb-0 fw-bold">{{ $fees->where('status', 'active')->count() }}</h3>
                                </div>
                                <i class="fas fa-toggle-on fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-gradient-warning text-white shadow-sm">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 opacity-75">Inactive</h6>
                                    <h3 class="mb-0 fw-bold">{{ $fees->where('status', 'inactive')->count() }}</h3>
                                </div>
                                <i class="fas fa-toggle-off fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-gradient-info text-white shadow-sm">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 opacity-75">This Year</h6>
                                    <h3 class="mb-0 fw-bold">{{ $fees->where('academic_year', date('Y') . '-' . (date('Y')+1))->count() }}</h3>
                                </div>
                                <i class="fas fa-calendar-alt fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fees Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 ps-4">Reference ID</th>
                                    <th class="py-3">Fee Type</th>
                                    <th class="py-3">Academic Year</th>
                                    <th class="py-3">Duration</th>
                                    <th class="py-3">Amount</th>
                                    <th class="py-3">Late Fee</th>
                                    <th class="py-3">Status</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fees as $fee)
                                    <tr class="border-bottom">
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded p-2 me-3">
                                                    <i class="fas fa-hashtag text-primary"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-semibold text-dark">{{ $fee->fee_reference_id }}</span>
                                                    <br>
                                                    <small class="text-muted">{{ $fee->custom_fee_key ?? 'Custom Fee' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $feeTypeNames = [
                                                    'transport' => '<span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">Transport</span>',
                                                    'tuition' => '<span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">Tuition</span>',
                                                    'hostel' => '<span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Hostel</span>',
                                                    'library' => '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Library</span>',
                                                    'sports' => '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">Sports</span>',
                                                    'other' => '<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">Other</span>',
                                                    'custom' => '<span class="badge bg-purple bg-opacity-10 text-purple border border-purple border-opacity-25">Custom</span>'
                                                ];
                                                $feeTypeDisplay = $feeTypeNames[$fee->fee_type] ?? '<span class="badge bg-light text-dark">'.ucfirst($fee->fee_type).'</span>';
                                            @endphp
                                            {!! $feeTypeDisplay !!}
                                            <div class="mt-1 small text-muted">
                                                {{ $fee->custom_fee_key ?? '' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $fee->academic_year }}</div>
                                            <small class="text-muted">Year</small>
                                        </td>
                                        <td>
                                            @php
                                                $durationNames = [
                                                    'monthly' => '<span class="badge bg-light text-dark">Monthly</span>',
                                                    'quarterly' => '<span class="badge bg-light text-dark">Quarterly</span>',
                                                    'half_yearly' => '<span class="badge bg-light text-dark">Half Yearly</span>',
                                                    'yearly' => '<span class="badge bg-light text-dark">Yearly</span>',
                                                    'custom' => '<span class="badge bg-light text-dark">Custom</span>'
                                                ];
                                                $durationBadge = $durationNames[$fee->fee_duration_type] ?? '<span class="badge bg-light text-dark">'.ucfirst($fee->fee_duration_type).'</span>';
                                            @endphp
                                            {!! $durationBadge !!}
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark fs-5">₹{{ number_format($fee->custom_fee_value, 2) }}</div>
                                            @if($fee->discount_amount > 0)
                                                <small class="text-success">
                                                    <i class="fas fa-tag me-1"></i>Discount: ₹{{ number_format($fee->discount_amount, 2) }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($fee->late_fee_type === 'fixed')
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">
                                                    <i class="fas fa-clock me-1"></i>₹{{ number_format($fee->late_fee_value, 2) }}
                                                </span>
                                            @elseif($fee->late_fee_type === 'percentage')
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">
                                                    <i class="fas fa-percentage me-1"></i>{{ $fee->late_fee_value }}%
                                                </span>
                                            @else
                                                <span class="badge bg-light text-muted">
                                                    <i class="fas fa-ban me-1"></i>None
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($fee->status === 'active')
                                                <span class="badge bg-success rounded-pill px-3 py-1">
                                                    <i class="fas fa-check-circle me-1"></i>Active
                                                </span>
                                            @elseif($fee->status === 'inactive')
                                                <span class="badge bg-secondary rounded-pill px-3 py-1">
                                                    <i class="fas fa-times-circle me-1"></i>Inactive
                                                </span>
                                            @else
                                                <span class="badge bg-warning rounded-pill px-3 py-1">
                                                    <i class="fas fa-edit me-1"></i>Draft
                                                </span>
                                            @endif
                                        </td>
                                        
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="py-5">
                                                <i class="fas fa-inbox fa-4x text-muted opacity-25 mb-3"></i>
                                                <h5 class="text-muted mb-2">No fee structures found</h5>
                                                <p class="text-muted mb-4">Start by creating your first custom fee structure</p>
                                                <a href="{{ route('admin.custom-fees.create') }}" class="btn btn-primary rounded-pill px-4">
                                                    <i class="fas fa-plus-circle me-2"></i>Create First Fee
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if($fees->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Showing {{ $fees->firstItem() }} to {{ $fees->lastItem() }} of {{ $fees->total() }} entries
                    </div>
                    <nav aria-label="Page navigation">
                        {{ $fees->links('vendor.pagination.bootstrap-5') }}
                    </nav>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}
.bg-gradient-success {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%) !important;
}
.bg-gradient-warning {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%) !important;
}
.bg-gradient-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
}
.bg-purple {
    background-color: #6f42c1 !important;
}
.text-purple {
    color: #6f42c1 !important;
}
.border-purple {
    border-color: #6f42c1 !important;
}
.rounded-circle {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}
.badge.bg-opacity-10 {
    background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function applyFilters() {
    const year = $('#filter_year').val();
    const type = $('#filter_type').val();
    const status = $('#filter_status').val();
    
    let url = '{{ route("admin.custom-fees.index") }}';
    const params = new URLSearchParams();
    
    if (year) params.append('year', year);
    if (type) params.append('type', type);
    if (status) params.append('status', status);
    
    if (params.toString()) {
        url += '?' + params.toString();
    }
    
    window.location.href = url;
}

// Set filter values from URL
$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    const urlParams = new URLSearchParams(window.location.search);
    $('#filter_year').val(urlParams.get('year') || '');
    $('#filter_type').val(urlParams.get('type') || '');
    $('#filter_status').val(urlParams.get('status') || '');
});
</script>
@endsection
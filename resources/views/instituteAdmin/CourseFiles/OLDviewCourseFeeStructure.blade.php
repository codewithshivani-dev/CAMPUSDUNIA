@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
.main-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    border: none;
    margin-bottom: 20px;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0 !important;
    padding: 15px 20px;
    border: none;
}

.batch-header {
    background: #f8f9fa;
    border-left: 4px solid #007bff;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 0 8px 8px 0;
}

.fee-category-compact {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.fee-category-compact:hover {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.fee-category-compact.has-data {
    border-left: 4px solid #28a745;
}

.fee-category-compact.no-data {
    border-left: 4px solid #6c757d;
    opacity: 0.7;
}

.category-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.category-title {
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
    font-size: 0.95rem;
}

.fee-amount {
    font-size: 1rem;
    font-weight: bold;
    color: #28a745;
}

.fee-detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 0;
    border-bottom: 1px solid #f8f9fa;
    font-size: 0.85rem;
}

.fee-detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    color: #6c757d;
}

.detail-value {
    font-weight: 500;
    color: #2c3e50;
}

.total-card-small {
    background: #28a745;
    color: white;
    border-radius: 8px;
    padding: 12px 15px;
    text-align: center;
    margin-bottom: 0;
}

.filter-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
}

.stats-badge {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    padding: 4px 10px;
    margin: 2px;
    font-size: 0.8rem;
}

.icon-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    font-size: 0.9rem;
}

.batch-title {
    font-size: 1.1rem;
    font-weight: 600;
}

/* Seats and Sections Styles */
.seats-summary-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
}

.seats-summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.seats-summary-item:last-child {
    margin-bottom: 0;
}

.sections-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
}

.sections-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e9ecef;
}

.sections-title {
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
    font-size: 0.95rem;
}

.section-item-compact {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 10px 12px;
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.section-item-compact:last-child {
    margin-bottom: 0;
}

.section-name-compact {
    font-weight: 500;
    color: #2c3e50;
    font-size: 0.9rem;
}

.section-seats-compact {
    font-weight: 600;
    color: #28a745;
    font-size: 0.9rem;
}

.no-sections {
    text-align: center;
    padding: 20px;
    color: #6c757d;
}

.progress-section {
    margin-top: 10px;
}

.progress {
    height: 8px;
    background-color: #e9ecef;
    border-radius: 4px;
}

.progress-bar {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 4px;
}

.allocation-stats {
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 5px;
    text-align: center;
}

.seats-badge {
    font-size: 0.75rem;
    padding: 4px 8px;
}

/* Fix for batch card structure */
.batch-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

.batch-card:last-child {
    margin-bottom: 0;
}

/* Ensure proper row and column structure */
.row {
    margin-left: -10px;
    margin-right: -10px;
}

.row>[class*="col-"] {
    padding-left: 10px;
    padding-right: 10px;
}

/* Fix for fee categories grid */
.fee-categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

/* Clear floats and ensure proper container flow */
.clearfix::after {
    content: "";
    clear: both;
    display: table;
}

/* Ensure proper spacing */
.mb-20 {
    margin-bottom: 20px;
}

.mt-20 {
    margin-top: 20px;
}
</style>

<div class="container-fluid py-3">
    <div class="main-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-money-bill-wave me-2"></i>Fee Structure Overview
                </h5>
                <div>
                    <a href="{{ route('course.fee.form') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus me-1"></i>Create New
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <div class="filter-section">
                <form method="GET" action="{{ route('fee.structure.view') }}">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <select name="batch_id" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="">All Batches</option>
                                @foreach($filterData['batches'] as $batch)
                                <option value="{{ $batch }}" {{ request('batch') == $batch ? 'selected' : '' }}>
                                    {{ $batch }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="batch_year" class="form-control form-control-sm"
                                onchange="this.form.submit()">
                                <option value="">All Years</option>
                                @foreach($filterData['batch_years'] as $year)
                                <option value="{{ $year }}" {{ request('batch_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="product_id" class="form-control form-control-sm"
                                onchange="this.form.submit()">
                                <option value="">All Products</option>
                                @foreach($filterData['products'] as $product)
                                <option value="{{ $product }}"
                                    {{ request('product_id') == $product ? 'selected' : '' }}>
                                    {{ $product }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('fee.structure.view') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-refresh me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            @if($feeStructures->count() > 0)
            @foreach($feeStructures as $feeStructure)
            <div class="batch-card">
                <!-- Batch Header - Compact -->
                <div class="batch-header">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <div class="batch-title text-primary mb-1">{{ $feeStructure->batch }}</div>
                            <div class="d-flex flex-wrap">
                                <span class="stats-badge">
                                    <i class="fas fa-calendar me-1"></i>{{ $feeStructure->batch_year }}
                                </span>
                                <span class="stats-badge">
                                    <i class="fas fa-graduation-cap me-1"></i>{{ $feeStructure->academic_year }}
                                </span>
                                <span class="stats-badge">
                                    <i class="fas fa-book me-1"></i>{{ $feeStructure->course_type }}
                                </span>
                                <span class="stats-badge">
                                    <i class="fas fa-code-branch me-1"></i>{{ $feeStructure->sub_type }}
                                </span>
                                <span
                                    class="stats-badge {{ $feeStructure->is_active ? 'bg-light text-success' : 'bg-light text-secondary' }}">
                                    <i
                                        class="fas fa-circle me-1"></i>{{ $feeStructure->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 text-end">
                            <!-- Smaller Total Fee Card -->
                            <div class="total-card-small">
                                <div class="small">Total Fee</div>
                                <div class="h5 mb-0">₹{{ number_format($feeStructure->total_fee, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seats and Sections Information -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="seats-summary-card">
                            <div class="seats-summary-item">
                                <span>Total Seats:</span>
                                <strong>{{ $feeStructure->total_seats ?? 0 }}</strong>
                            </div>
                            <div class="seats-summary-item">
                                <span>Allocated Seats:</span>
                                <strong>{{ $feeStructure->allocated_seats ?? 0 }}</strong>
                            </div>
                            <div class="seats-summary-item">
                                <span>Available Seats:</span>
                                <strong>{{ $feeStructure->available_seats ?? 0 }}</strong>
                            </div>
                        </div>

                        <!-- Allocation Progress -->
                        @php
                        $totalSeats = $feeStructure->total_seats ?? 0;
                        $allocatedSeats = $feeStructure->allocated_seats ?? 0;

                        if ($totalSeats > 0) {
                        $allocationPercentage = ($allocatedSeats / $totalSeats) * 100;
                        } else {
                        $allocationPercentage = 0;
                        }
                        @endphp

                        @if($totalSeats > 0)
                        <div class="progress-section">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: {{ $allocationPercentage }}%"
                                    aria-valuenow="{{ $allocationPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <div class="allocation-stats">
                                {{ number_format($allocationPercentage, 1) }}% Allocated
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="col-md-8">
                        <div class="sections-card">
                            <div class="sections-header">
                                <h6 class="sections-title mb-0">
                                    Sections ({{ count($feeStructure->sections ?? []) }})
                                </h6>
                                <span>
                                    {{ $allocatedSeats }} / {{ $totalSeats }} Seats
                                </span>
                            </div>

                            @if(count($feeStructure->sections ?? []) > 0)
                            <div class="sections-list">
                                @foreach($feeStructure->sections as $section)
                                <div class="section-item-compact">
                                    <div class="section-name-compact">
                                        {{ $section['name'] ?? 'Unnamed Section' }}
                                    </div>
                                    <div class="section-seats-compact">
                                        {{ $section['seats'] ?? 0 }} seats
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="no-sections">
                                <p class="mb-0">No sections configured</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Fee Categories Grid - Only Course and Registration Fees -->
                <div class="row clearfix">
                   @php
                        $courseLabel = (isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School')
                            ? 'Class'
                            : 'Course';

                        $feeCategories = [
                            'course_fee' => [
                                'title' => $courseLabel . ' Fee',
                                'icon'  => 'fas fa-book',
                                'color' => '#667eea'
                            ],
                            'registration_fee' => [
                                'title' => 'Registration Fee',
                                'icon'  => 'fas fa-file-signature',
                                'color' => '#dc3545'
                            ],
                        ];
                    @endphp


                    @foreach($feeCategories as $feeKey => $feeCategory)
                    @php
                    $feeData = $feeStructure->$feeKey;
                    $hasData = is_array($feeData) && !empty($feeData);
                    $totalAmount = 0;
                    $paymentCount = 0;

                    if ($hasData && isset($feeData['payments']) && is_array($feeData['payments'])) {
                    foreach ($feeData['payments'] as $payment) {
                    if (isset($payment['amount'])) {
                    $totalAmount += floatval($payment['amount']);
                    $paymentCount++;
                    }
                    }
                    }
                    @endphp

                    <div class="col-md-6 mb-3">
                        <div class="fee-category-compact {{ $hasData ? 'has-data' : 'no-data' }}">
                            <div class="category-header">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle"
                                        style="background: {{ $feeCategory['color'] }}20; color: {{ $feeCategory['color'] }};">
                                        <i class="{{ $feeCategory['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <div class="category-title">{{ $feeCategory['title'] }}</div>
                                        @if($hasData && isset($feeData['duration']))
                                        <small class="text-muted">{{ $feeData['duration'] }}</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="fee-amount">
                                    @if($hasData && $totalAmount > 0)
                                    ₹{{ number_format($totalAmount, 2) }}
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </div>

                            @if($hasData)
                            <!-- Payment Details -->
                            @if($paymentCount > 0)
                            @foreach($feeData['payments'] as $index => $payment)
                            @if($index < 2)
                            <div class="fee-detail-item">
                                <span class="detail-label">
                                    @if(($feeData['duration'] ?? '') === 'One Time')
                                    One Time
                                    @else
                                    {{ $feeData['duration'] ?? 'Payment' }} {{ $index + 1 }}
                                    @endif
                                </span>
                                <span class="detail-value">₹{{ number_format($payment['amount'] ?? 0, 2) }}</span>
                            </div>
                            @endif
                            @endforeach
                            @if($paymentCount > 2)
                            <div class="fee-detail-item">
                                <span class="detail-label">+{{ $paymentCount - 2 }} more</span>
                                <span class="detail-value text-muted">-</span>
                            </div>
                            @endif
                            @else
                            <div class="fee-detail-item">
                                <span class="detail-label">No payments</span>
                                <span class="detail-value text-muted">-</span>
                            </div>
                            @endif

                            <!-- Late Fee -->
                            @if(isset($feeData['late_fee']['amount']))
                            <div class="fee-detail-item">
                                <span class="detail-label text-warning">
                                    <i class="fas fa-clock me-1"></i>Late Fee
                                </span>
                                <span class="detail-value text-warning">
                                    ₹{{ number_format($feeData['late_fee']['amount'], 2) }}
                                </span>
                            </div>
                            @endif

                            <!-- Partial Payment -->
                            @if(isset($feeData['partial_payment']['amount']) && $feeData['partial_payment']['amount'])
                            <div class="fee-detail-item">
                                <span class="detail-label text-info">
                                    <i class="fas fa-percentage me-1"></i>Partial Payment
                                </span>
                                <span class="detail-value text-info">
                                    ₹{{ number_format($feeData['partial_payment']['amount'], 2) }}
                                </span>
                            </div>
                            @endif
                            @else
                            <div class="fee-detail-item">
                                <span class="detail-label text-muted">No data configured</span>
                                <span class="detail-value text-muted">-</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Additional Info -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center text-muted">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                Session: {{ $feeStructure->session_range }}
                            </small>
                            <small>
                                Created: {{ \Carbon\Carbon::parse($feeStructure->created_at)->format('M d, Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            @if(!$loop->last)
            <hr class="my-4">
            @endif
            @endforeach
            @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-search fa-3x mb-3"></i>
                <h5>No Fee Structures Found</h5>
                <p>
                    @if(count(array_filter($filters)) > 0)
                    No fee structures match your current filters.
                    @else
                    No fee structures have been created yet.
                    @endif
                </p>
                <a href="{{ route('course.fee.form') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create New Fee Structure
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Auto-submit form when filters change
    $('select').on('change', function() {
        $(this).closest('form').submit();
    });
});
</script>
@endsection
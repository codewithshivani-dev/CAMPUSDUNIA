@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.container {
    margin-top: 30px;
    margin-bottom: 30px;
}

.form-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    border: none;
    margin-bottom: 20px;
}

.card-header {
    display: flex;
    justify-content: space-between;
    border-radius: 12px 12px 0 0 !important;
    padding: 20px 25px;
    border: none;
}

.card-header h5 {
    margin: 0;
    font-weight: 600;
}

.card-body {
    padding: 25px;
}

/* Add/edit specific styles */
.edit-badge {
    background: #17a2b8;
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    margin-left: 10px;
}

.current-data {
    background: #e7f3ff;
    border-left: 4px solid #17a2b8;
    padding: 10px;
    border-radius: 5px;
    margin: 5px 0;
}
</style>

<div class="container">
    <div class="form-card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i> 
                Edit Fee Structure 
                <span class="edit-badge">{{ $feeStructure->sub_type }}</span>
            </h5>
            <a href="{{ route('fee.structure.view') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i> Back to List
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Current Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h6><i class="fas fa-info-circle me-2"></i>Current Information</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="current-data">
                                <small class="text-muted">Branch</small><br>
                                <strong>{{ $feeStructure->sub_type }}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="current-data">
                                <small class="text-muted">Batch Year</small><br>
                                <strong>{{ $feeStructure->batch_year }}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="current-data">
                                <small class="text-muted">Academic Year</small><br>
                                <strong>{{ $feeStructure->academic_year }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FIXED FORM START -->
            <form id="edit-fee-form" method="POST" action="{{ route('course.fee.structure.update', $feeStructure->product_id) }}">
                @csrf
                @method('PUT')
                
                <input type="hidden" name="fee_structure_id" value="{{ $feeStructure->id }}">
                <input type="hidden" name="product_id" value="{{ $feeStructure->product_id }}">
                <input type="hidden" name="batch_id" value="{{ $feeStructure->batch_id }}">
                <input type="hidden" name="academic_year_id" value="{{ $feeStructure->academic_year_id }}">
                
                <!-- Add hidden fields for course details -->
                <input type="hidden" name="course_duration" id="course_duration" value="{{ $ProductDetail->course_duration }}">
                <input type="hidden" name="course_length" id="course_length" value="{{  $ProductDetail->course_length }}">

                <!-- Course Duration Display -->
                <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
                    <h5 class="mb-4 text-primary">
                        <i class="fas fa-calendar-alt me-2"></i>Branch Duration & Dates
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Course Duration Type</label>
                            <input type="text" class="form-control bg-light border-0 shadow-sm" 
                                value="{{ $ProductDetail->course_duration ?? 'N/A' }}" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Course Length</label>
                            <input type="text" class="form-control bg-light border-0 shadow-sm" 
                                value="{{ $ProductDetail->course_length ?? 'N/A' }}" readonly>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Academic Year</label>
                            <input type="text" class="form-control bg-light border-0 shadow-sm" 
                                value="{{ $feeStructure->academic_year }}" readonly>
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Start Date</label>
                            <input type="text" class="form-control bg-light border-0 shadow-sm" 
                                value="{{ \Carbon\Carbon::parse($feeStructure->course_start_date)->format('d M Y') }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">End Date</label>
                            <input type="text" class="form-control bg-light border-0 shadow-sm" 
                                value="{{ \Carbon\Carbon::parse($feeStructure->course_end_date)->format('d M Y') }}" readonly>
                        </div>
                    </div>
                </div>

                <!-- Seats and Sections Management -->
                <div class="batch-in-fee-section mt-4">
                    <h6><i class="fas fa-chair me-2"></i>Seats and Sections Management</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total Number of Seats *</label>
                            <input type="number" name="total_seats" id="total_seats" class="form-control"
                                value="{{ $feeStructure->total_seats }}" min="1" >
                            <small class="text-muted">Total capacity for this batch</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Available Seats</label>
                            <input type="number" name="available_seats" id="available_seats" class="form-control"
                                value="{{ $feeStructure->available_seats }}" readonly>
                            <small class="text-muted">Automatically calculated based on sections</small>
                        </div>
                    </div>

                    <!-- Sections Container -->
                    <div class="sections-container mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label mb-0">Sections</label>
                            <button type="button" class="btn btn-sm btn-success" id="add-section-btn">
                                <i class="fas fa-plus me-1"></i>Add Section
                            </button>
                        </div>

                        <div id="sections-list">
                            <!-- Sections will be dynamically added here -->
                        </div>

                        <div class="seats-summary mt-3">
                            <div class="alert alert-info py-2">
                                <i class="fas fa-info-circle me-2"></i>
                                <span id="seats-summary-text">Loading seats summary...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Batch Information -->
                <div class="batch-in-fee-section mt-4">
                    <h6><i class="fas fa-users me-2"></i>Batch Information</h6>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Batch Name *</label>
                            <input type="text" name="batch" id="batch_name" class="form-control"
                                value="{{ $feeStructure->batch }}" >
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Batch Year *</label>
                            <select name="batch_year" id="batch_year" class="form-control" >
                                @for($year = date('Y') - 5; $year <= date('Y') + 5; $year++)
                                <option value="{{ $year }}" {{ $feeStructure->batch_year == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Batch Status</label>
                            <select name="batch_status" id="batch_status" class="form-control">
                                <option value="running" {{ $feeStructure->batch_status == 'running' ? 'selected' : '' }}>Running</option>
                                <option value="complete" {{ $feeStructure->batch_status == 'complete' ? 'selected' : '' }}>Complete</option>
                                <option value="upcoming" {{ $feeStructure->batch_status == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Fee Categories -->
                <div class="fee-section-with-batch mt-4">
                    <h6 class="mb-3"><i class="fas fa-money-bill-wave me-2"></i>Fee Categories</h6>

                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Change payment duration to reconfigure fee structure. Existing fee amounts will be preserved where possible.
                    </div>

                    @php
                        $courseFeeLabel = 'Courses Fee';
                        if (isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School') {
                            $courseFeeLabel = 'Class Fee';
                        }
                        
                        // Decode JSON data
                        $courseFeeData = json_decode($feeStructure->course_fee, true) ?? [];
                        $registrationFeeData = json_decode($feeStructure->registration_fee, true) ?? [];
                        $sectionsData = json_decode($feeStructure->sections, true) ?? [];
                    @endphp

                    <!-- Course Fee -->
                    <div class="fee-category mb-4 border rounded p-3">
                        <div class="fee-category-header d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input fee-checkbox" type="checkbox"
                                    id="courseFeeCheckbox" data-type="course" 
                                    {{ !empty($courseFeeData) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="courseFeeCheckbox">
                                    {{ $courseFeeLabel }}
                                </label>
                            </div>
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </div>

                        <div class="fee-category-content" id="courseFeeDetails" 
                            style="{{ !empty($courseFeeData) ? '' : 'display: none;' }}">
                            
                            <div class="mb-3">
                                <label class="form-label">Payment Duration *</label>
                                <select class="form-select fee-duration-select" name="course_fee_duration"
                                    id="course_fee_duration" data-type="course">
                                    <option value="">Select Duration</option>
                                    @php
                                        $courseDurations = ['One Time', 'Monthly', 'Quarterly', 'Half_yearly', 'Yearly'];
                                        $currentCourseDuration = $courseFeeData['duration'] ?? '';
                                    @endphp
                                    @foreach($courseDurations as $duration)
                                    <option value="{{ $duration }}" 
                                        {{ $currentCourseDuration == $duration ? 'selected' : '' }}>
                                        {{ $duration }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Dynamic Input Container -->
                            <div id="course-input-container" class="mt-3">
                                @if(!empty($courseFeeData['payments']))
                                    @foreach($courseFeeData['payments'] as $index => $payment)
                                    <div class="duration-block mb-3 p-3 border rounded" data-index="{{ $index + 1 }}">
                                        <h6>
                                            @if($currentCourseDuration == 'One Time')
                                                One Time Payment
                                            @else
                                                {{ $currentCourseDuration }} Payment {{ $index + 1 }}
                                            @endif
                                        </h6>
                                        <div class="mb-2">
                                            <label class="form-label">Amount *</label>
                                            <input type="number" 
                                                name="course_fee_amount[]" 
                                                class="form-control fee-amount-input"
                                                value="{{ $payment['amount'] ?? '' }}" 
                                                placeholder="Enter amount" 
                                            >
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label">Start Date</label>
                                                <input type="date" 
                                                    name="course_start_date[]" 
                                                    class="form-control"
                                                    value="{{ $payment['start_date'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Due Date</label>
                                                <input type="date" 
                                                    name="course_end_date[]" 
                                                    class="form-control"
                                                    value="{{ $payment['end_date'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>

                            <!-- Late Fee Options -->
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input late-fee-checkbox"
                                            id="course_late_fee" data-type="course"
                                            {{ isset($courseFeeData['late_fee']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="course_late_fee">Apply Late Fee</label>
                                    </div>
                                    <div class="late-fee-options mt-2" id="course_late_fee_options" 
                                        style="{{ isset($courseFeeData['late_fee']) ? '' : 'display: none;' }}">
                                        <div class="mb-2">
                                            <label>Late Fee Type *</label>
                                            <div class="form-check">
                                                <input type="radio" name="course_late_fee_type" value="flat"
                                                    class="form-check-input"
                                                    {{ isset($courseFeeData['late_fee']['type']) && $courseFeeData['late_fee']['type'] == 'flat' ? 'checked' : '' }}
                                                    {{ isset($courseFeeData['late_fee'])}}>
                                                <label class="form-check-label">Flat Amount</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" name="course_late_fee_type" value="percentage"
                                                    class="form-check-input"
                                                    {{ isset($courseFeeData['late_fee']['type']) && $courseFeeData['late_fee']['type'] == 'percentage' ? 'checked' : '' }}
                                                    {{ isset($courseFeeData['late_fee']) }}>
                                                <label class="form-check-label">Percentage</label>
                                            </div>
                                        </div>
                                        <div class="late-fee-amount mt-2">
                                            <label class="form-label">Late Fee Amount *</label>
                                            <input type="number" name="course_late_fee_amount"
                                                class="form-control" 
                                                value="{{ $courseFeeData['late_fee']['amount'] ?? '' }}"
                                                placeholder="Enter amount" 
                                                {{ isset($courseFeeData['late_fee']) }}>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input partial-fee-checkbox"
                                            id="course_partial_fee" data-type="course"
                                            {{ isset($courseFeeData['partial_payment']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="course_partial_fee">Allow Partial Payment</label>
                                    </div>
                                    <div class="partial-fee-options mt-2" id="course_partial_fee_options"
                                        style="{{ isset($courseFeeData['partial_payment']) ? '' : 'display: none;' }}">
                                        <div class="mb-2">
                                            <label>Partial Payment Type *</label>
                                            <div class="form-check">
                                                <input type="radio" name="course_partial_fee_type" value="fixed"
                                                    class="form-check-input"
                                                    {{ isset($courseFeeData['partial_payment']['type']) && $courseFeeData['partial_payment']['type'] == 'fixed' ? 'checked' : '' }}
                                                    {{ isset($courseFeeData['partial_payment']) }}>
                                                <label class="form-check-label">Fixed Amount</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" name="course_partial_fee_type" value="percentage"
                                                    class="form-check-input"
                                                    {{ isset($courseFeeData['partial_payment']['type']) && $courseFeeData['partial_payment']['type'] == 'percentage' ? 'checked' : '' }}
                                                    {{ isset($courseFeeData['partial_payment']) }}>
                                                <label class="form-check-label">Percentage</label>
                                            </div>
                                        </div>
                                        <div class="partial-fee-amount mt-2">
                                            <label class="form-label">Partial Fee Amount *</label>
                                            <input type="number" name="course_partial_fee_amount"
                                                class="form-control" 
                                                value="{{ $courseFeeData['partial_payment']['amount'] ?? '' }}"
                                                placeholder="Enter amount" 
                                                {{ isset($courseFeeData['partial_payment']) }}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Registration Fee -->
                    <div class="fee-category mb-4 border rounded p-3">
                        <div class="fee-category-header d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input fee-checkbox" type="checkbox"
                                    id="registrationFeeCheckbox" data-type="registration"
                                    {{ !empty($registrationFeeData) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="registrationFeeCheckbox">
                                    Registration Fee
                                </label>
                            </div>
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </div>

                        <div class="fee-category-content" id="registrationFeeDetails" 
                            style="{{ !empty($registrationFeeData) ? '' : 'display: none;' }}">
                            
                            <div class="mb-3">
                                <label class="form-label">Payment Duration *</label>
                                <select class="form-select fee-duration-select" name="registration_fee_duration"
                                    id="registration_fee_duration" data-type="registration">
                                    <option value="One Time" selected>One Time</option>
                                </select>
                            </div>

                            <!-- Dynamic Input Container -->
                            <div id="registration-input-container" class="mt-3">
                                @if(!empty($registrationFeeData['payments']))
                                    @foreach($registrationFeeData['payments'] as $index => $payment)
                                    <div class="duration-block mb-3 p-3 border rounded" data-index="{{ $index + 1 }}">
                                        <h6>One Time Payment</h6>
                                        <div class="mb-2">
                                            <label class="form-label">Amount *</label>
                                            <input type="number" 
                                                name="registration_fee_amount[]" 
                                                class="form-control fee-amount-input"
                                                value="{{ $payment['amount'] ?? '' }}" 
                                                placeholder="Enter amount" 
                                                >
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label">Start Date</label>
                                                <input type="date" 
                                                    name="registration_start_date[]" 
                                                    class="form-control"
                                                    value="{{ $payment['start_date'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Due Date</label>
                                                <input type="date" 
                                                    name="registration_end_date[]" 
                                                    class="form-control"
                                                    value="{{ $payment['end_date'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>

                            <!-- Late Fee Options -->
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input late-fee-checkbox"
                                            id="registration_late_fee" data-type="registration"
                                            {{ isset($registrationFeeData['late_fee']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="registration_late_fee">Apply Late Fee</label>
                                    </div>
                                    <div class="late-fee-options mt-2" id="registration_late_fee_options"
                                        style="{{ isset($registrationFeeData['late_fee']) ? '' : 'display: none;' }}">
                                        <div class="mb-2">
                                            <label>Late Fee Type</label>
                                            <div class="form-check">
                                                <input type="radio" name="registration_late_fee_type" value="flat"
                                                    class="form-check-input"
                                                    {{ isset($registrationFeeData['late_fee']['type']) && $registrationFeeData['late_fee']['type'] == 'flat' ? 'checked' : '' }}
                                                    {{ isset($registrationFeeData['late_fee']) }}>
                                                <label class="form-check-label">Flat Amount</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" name="registration_late_fee_type" value="percentage"
                                                    class="form-check-input"
                                                    {{ isset($registrationFeeData['late_fee']['type']) && $registrationFeeData['late_fee']['type'] == 'percentage' ? 'checked' : '' }}
                                                    {{ isset($registrationFeeData['late_fee'])}}>
                                                <label class="form-check-label">Percentage</label>
                                            </div>
                                        </div>
                                        <div class="late-fee-amount mt-2">
                                            <label class="form-label">Late Fee Amount *</label>
                                            <input type="number" name="registration_late_fee_amount"
                                                class="form-control" 
                                                value="{{ $registrationFeeData['late_fee']['amount'] ?? '' }}"
                                                placeholder="Enter amount" 
                                                {{ isset($registrationFeeData['late_fee']) }}>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input partial-fee-checkbox"
                                            id="registration_partial_fee" data-type="registration"
                                            {{ isset($registrationFeeData['partial_payment']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="registration_partial_fee">Allow Partial Payment</label>
                                    </div>
                                    <div class="partial-fee-options mt-2" id="registration_partial_fee_options"
                                        style="{{ isset($registrationFeeData['partial_payment']) ? '' : 'display: none;' }}">
                                        <div class="mb-2">
                                            <label>Partial Payment Type</label>
                                            <div class="form-check">
                                                <input type="radio" name="registration_partial_fee_type" value="fixed"
                                                    class="form-check-input"
                                                    {{ isset($registrationFeeData['partial_payment']['type']) && $registrationFeeData['partial_payment']['type'] == 'fixed' ? 'checked' : '' }}
                                                    {{ isset($registrationFeeData['partial_payment'])  }}>
                                                <label class="form-check-label">Fixed Amount</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" name="registration_partial_fee_type" value="percentage"
                                                    class="form-check-input"
                                                    {{ isset($registrationFeeData['partial_payment']['type']) && $registrationFeeData['partial_payment']['type'] == 'percentage' ? 'checked' : '' }}
                                                    {{ isset($registrationFeeData['partial_payment'])  }}>
                                                <label class="form-check-label">Percentage</label>
                                            </div>
                                        </div>
                                        <div class="partial-fee-amount mt-2">
                                            <label class="form-label">Partial Fee Amount *</label>
                                            <input type="number" name="registration_partial_fee_amount"
                                                class="form-control" 
                                                value="{{ $registrationFeeData['partial_payment']['amount'] ?? '' }}"
                                                placeholder="Enter amount" 
                                                {{ isset($registrationFeeData['partial_payment'])  }}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Fee -->
                <div class="mb-4 mt-4">
                    <label class="form-label" id="totalFeeLabel">Total Fee</label>
                    <input type="text" name="total_fee" class="form-control form-control-lg" 
                        value="{{ $feeStructure->total_fee }}" readonly style="font-weight: bold;">
                </div>

                <!-- Active Status -->
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                            {{ $feeStructure->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>

                <div class="text-end d-flex mt-4 justify-content-between">
                    <a href="{{ route('fee.structure.view') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i> Back to List
                    </a>
                    <button type="submit" class="btn btn-success" id="update-fee-btn">
                        <i class="fas fa-save me-2"></i>Update Fee Structure
                    </button>
                </div>
            </form>
           <!-- FIXED FORM END -->
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

<script>
$(document).ready(function() {
    // Initialize variables
    let sections = @json($sectionsData ?? []);
    let sectionCounter = sections.length;
    
    // Course details for fee calculation - WITH DEFAULT VALUES
    const courseDuration = '{{ $ProductDetail->course_duration ?? "Monthly" }}';
    const courseLength = {{ $ProductDetail->course_length ?? 1 }};
    
    console.log('Course Duration:', courseDuration);
    console.log('Course Length:', courseLength);
    
    // Fee duration mapping
    const feeDurationLimits = {
        "Hourly": ["Hourly", "One Time"],
        "Weekly": ["Hourly", "Weekly", "One Time"],
        "Monthly": ["Monthly", "One Time"],
        "Quarterly": ["Monthly", "Quarterly", "One Time"],
        "Half_yearly": ["Monthly", "Quarterly", "Half_yearly", "One Time"],
        "Yearly": ["Monthly", "Quarterly", "Half_yearly", "Yearly", "One Time"]
    };
    
    const durationToMonths = {
        "Hourly": 1 / (24 * 30),
        "Weekly": 1 / 4,
        "Monthly": 1,
        "Quarterly": 3,
        "Half_yearly": 6,
        "Yearly": 12,
        "One Time": 0
    };
    
    // Initialize the page
    initializeSections();
    updateSeatsSummary();
    initializeFeeInputs();
    
    // ========== SECTION MANAGEMENT ==========
    
    // Initialize sections in the DOM
    function initializeSections() {
        const sectionsList = $('#sections-list');
        sectionsList.empty();
        
        if (sections.length === 0) {
            sectionsList.html(`
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No sections added yet. Click "Add Section" to create one.
                </div>
            `);
            return;
        }
        
        sections.forEach((section, index) => {
            const sectionId = section.id || `section_${index + 1}`;
            const sectionHtml = `
                <div class="section-item card mb-3" id="${sectionId}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">
                                <i class="fas fa-layer-group me-2"></i>
                                <span class="section-name-display">${section.name || 'Unnamed Section'}</span>
                            </h6>
                            <button type="button" class="btn btn-sm btn-danger remove-section" 
                                    onclick="removeSection('${sectionId}')">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Section Name *</label>
                                <input type="text" class="form-control section-name-input" 
                                       value="${section.name || ''}"
                                       placeholder="e.g., A, B, Morning, Evening" 
                                       oninput="updateSection('${sectionId}')"
                                >
                                <input type="hidden" name="section_names[]" value="${section.name || ''}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Number of Seats *</label>
                                <input type="number" class="form-control section-seats-input" 
                                       value="${section.seats || 1}"
                                       min="1" 
                                       placeholder="Enter seats" 
                                       oninput="updateSection('${sectionId}')"
                                >
                                <input type="hidden" name="section_seats[]" value="${section.seats || 1}">
                            </div>
                        </div>
                    </div>
                </div>
            `;
            sectionsList.append(sectionHtml);
        });
        
        sectionCounter = sections.length;
    }
    
    // Global function to remove a section
    window.removeSection = function(sectionId) {
        // Remove from DOM
        $(`#${sectionId}`).remove();
        
        // Remove from sections array
        sections = sections.filter(section => section.id !== sectionId);
        
        // Update hidden inputs
        updateSectionHiddenInputs();
        
        // Update UI
        updateSeatsSummary();
        
        // Show message if no sections left
        if (sections.length === 0) {
            $('#sections-list').html(`
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No sections added yet. Click "Add Section" to create one.
                </div>
            `);
        }
    };
    
    // Global function to update a section
    window.updateSection = function(sectionId) {
        const sectionElement = $(`#${sectionId}`);
        const name = sectionElement.find('.section-name-input').val();
        const seats = parseInt(sectionElement.find('.section-seats-input').val()) || 0;
        
        // Update in array
        const index = sections.findIndex(s => s.id === sectionId);
        if (index !== -1) {
            sections[index].name = name;
            sections[index].seats = seats;
            sectionElement.find('.section-name-display').text(name || 'Unnamed Section');
            
            // Update hidden inputs
            sectionElement.find('input[name="section_names[]"]').val(name);
            sectionElement.find('input[name="section_seats[]"]').val(seats);
        }
        
        updateSeatsSummary();
    };
    
    // Update hidden inputs for sections
    function updateSectionHiddenInputs() {
        // Remove existing hidden inputs
        $('input[name="section_names[]"]').remove();
        $('input[name="section_seats[]"]').remove();
        
        // Add new hidden inputs
        sections.forEach((section, index) => {
            $('<input>').attr({
                type: 'hidden',
                name: 'section_names[]',
                value: section.name || ''
            }).appendTo('#edit-fee-form');
            
            $('<input>').attr({
                type: 'hidden',
                name: 'section_seats[]',
                value: section.seats || 0
            }).appendTo('#edit-fee-form');
        });
    }
    
    // Add new section
    $('#add-section-btn').on('click', function() {
        const totalSeats = parseInt($('#total_seats').val()) || 0;
        const allocatedSeats = calculateAllocatedSeats();
        const availableSeats = totalSeats - allocatedSeats;
        
        if (availableSeats <= 0) {
            Toastify({
                text: "No available seats left",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
            }).showToast();
            return;
        }
        
        sectionCounter++;
        const sectionId = `section_new_${sectionCounter}`;
        
        // Clear info message if present
        if ($('#sections-list .alert').length) {
            $('#sections-list').empty();
        }
        
        const sectionHtml = `
            <div class="section-item card mb-3" id="${sectionId}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">
                            <i class="fas fa-layer-group me-2"></i>
                            <span class="section-name-display">New Section</span>
                        </h6>
                        <button type="button" class="btn btn-sm btn-danger remove-section" 
                                onclick="removeSection('${sectionId}')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Section Name *</label>
                            <input type="text" class="form-control section-name-input" 
                                   placeholder="e.g., A, B, Morning, Evening" 
                                   oninput="updateSection('${sectionId}')"
                                   >
                            <input type="hidden" name="section_names[]" value="">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Number of Seats *</label>
                            <input type="number" class="form-control section-seats-input" 
                                   min="1" 
                                   max="${availableSeats}"
                                   value="1"
                                   placeholder="Max: ${availableSeats}" 
                                   oninput="updateSection('${sectionId}')"
                                   >
                            <input type="hidden" name="section_seats[]" value="1">
                            <small class="text-muted">Max: ${availableSeats} seats available</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('#sections-list').append(sectionHtml);
        
        // Add to sections array
        sections.push({
            id: sectionId,
            name: 'New Section',
            seats: 1
        });
        
        updateSeatsSummary();
    });
    
    // Calculate allocated seats
    function calculateAllocatedSeats() {
        let total = 0;
        $('.section-seats-input').each(function() {
            const val = parseInt($(this).val()) || 0;
            total += val;
        });
        return total;
    }
    
    // Update seats summary
    function updateSeatsSummary() {
        const totalSeats = parseInt($('#total_seats').val()) || 0;
        const allocatedSeats = calculateAllocatedSeats();
        const availableSeats = totalSeats - allocatedSeats;
        
        $('#available_seats').val(availableSeats);
        
        let summaryText = `Total seats: ${totalSeats} | Allocated: ${allocatedSeats} | Available: ${availableSeats}`;
        
        if (availableSeats < 0) {
            summaryText += ' | ❌ Overallocated!';
            $('#seats-summary-text').addClass('text-danger');
        } else if (availableSeats === 0) {
            summaryText += ' | ✅ Fully allocated';
            $('#seats-summary-text').removeClass('text-danger');
        } else {
            summaryText += ` | ⚠️ ${availableSeats} seats available for new sections`;
            $('#seats-summary-text').removeClass('text-danger');
        }
        
        $('#seats-summary-text').html(summaryText);
    }
    
    // ========== FEE MANAGEMENT ==========
    
    // Initialize fee inputs based on existing data
    function initializeFeeInputs() {
        // Course fee
        const courseFeeDuration = $('#course_fee_duration').val();
        if (courseFeeDuration) {
            createInputFields('course', courseFeeDuration);
        }
        
        // Registration fee (always One Time)
        createInputFields('registration', 'One Time');
        
        // Calculate initial total fee
        calculateTotalFee();
    }
    
    // Generate dynamic fee inputs
    function createInputFields(type, duration = null) {
        const feeDuration = duration || $(`#${type}_fee_duration`).val();
        const container = $(`#${type}-input-container`);
        
        if (!feeDuration || !container.length) return;
        
        // Clear container
        container.html('');
        
        // Get existing fee data
        const existingData = getExistingFeeData(type);
        
        if (feeDuration === "One Time") {
            const blockHtml = `
                <div class="card shadow-sm p-3 mb-3 rounded duration-block" data-index="1">
                    <h6 class="text-primary mb-3">One Time Payment</h6>
                    <div class="mb-3">
                        <label class="form-label">Fee Amount *</label>
                        <input type="number" name="${type}_fee_amount[]" 
                               class="form-control fee-amount-input" 
                               placeholder="Enter amount" 
                               value="${existingData.amount || ''}" 
                               oninput="calculateTotalFee()"
                        />
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Fee Start Date</label>
                            <input type="date" name="${type}_start_date[]" 
                                   class="form-control" 
                                   value="${existingData.start_date || ''}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fee Due Date</label>
                            <input type="date" name="${type}_end_date[]" 
                                   class="form-control" 
                                   value="${existingData.end_date || ''}" />
                        </div>
                    </div>
                </div>
            `;
            container.append(blockHtml);
            
        } else {
            // Calculate number of payments
            const courseMonths = durationToMonths[courseDuration] * courseLength;
            const feeMonths = durationToMonths[feeDuration];
            
            if (!courseMonths || !feeMonths) {
                container.html('<div class="alert alert-warning">Unable to calculate payment schedule</div>');
                return;
            }
            
            const rawCount = courseMonths / feeMonths;
            const count = Math.floor(rawCount);
            const hasPartial = rawCount > count;
            const maxFields = 50;
            let totalBlocks = hasPartial ? count + 1 : count;
            if (totalBlocks > maxFields) totalBlocks = maxFields;
            
            for (let i = 1; i <= totalBlocks; i++) {
                const isPartial = (i === totalBlocks && hasPartial);
                const existingPayment = existingData.payments && existingData.payments[i - 1];
                
                const blockHtml = `
                    <div class="card shadow-sm p-3 mb-3 rounded duration-block" data-index="${i}">
                        <h6 class="text-primary mb-3">${feeDuration} ${i}${isPartial ? ' (Partial)' : ''}</h6>
                        <div class="mb-3">
                            <label class="form-label">Fee Amount *</label>
                            <input type="number" name="${type}_fee_amount[]" 
                                   class="form-control fee-amount-input" 
                                   placeholder="Enter amount" 
                                   value="${existingPayment ? existingPayment.amount : ''}" 
                                   oninput="calculateTotalFee()"
                            />
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Fee Start Date</label>
                                <input type="date" name="${type}_start_date[]" 
                                       class="form-control" 
                                       value="${existingPayment ? existingPayment.start_date : ''}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fee Due Date</label>
                                <input type="date" name="${type}_end_date[]" 
                                       class="form-control" 
                                       value="${existingPayment ? existingPayment.end_date : ''}" />
                            </div>
                        </div>
                    </div>
                `;
                
                container.append(blockHtml);
            }
            
            // Add same fee checkbox for multiple payments
            if (totalBlocks > 1) {
                const sameFeeHtml = `
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input same-fee-checkbox" 
                               id="${type}_sameFeeCheckbox" data-type="${type}">
                        <label class="form-check-label" for="${type}_sameFeeCheckbox">
                            Apply same fee amount to all ${feeDuration.toLowerCase()} payments
                        </label>
                    </div>
                `;
                container.append(sameFeeHtml);
            }
        }
        
        calculateTotalFee();
    }
    
    // Get existing fee data for a specific type
    function getExistingFeeData(type) {
        const feeData = { amount: '', start_date: '', end_date: '', payments: [] };
        
        // Get data from PHP variables passed to the view
        if (type === 'course') {
            // Use the courseFeeData passed from controller
            const existingFee = @json($courseFeeData ?? []);
            if (existingFee && existingFee.payments) {
                return {
                    amount: existingFee.payments[0]?.amount || '',
                    start_date: existingFee.payments[0]?.start_date || '',
                    end_date: existingFee.payments[0]?.end_date || '',
                    payments: existingFee.payments || []
                };
            }
        } else if (type === 'registration') {
            // Use the registrationFeeData passed from controller
            const existingFee = @json($registrationFeeData ?? []);
            if (existingFee && existingFee.payments) {
                return {
                    amount: existingFee.payments[0]?.amount || '',
                    start_date: existingFee.payments[0]?.start_date || '',
                    end_date: existingFee.payments[0]?.end_date || '',
                    payments: existingFee.payments || []
                };
            }
        }
        
        return feeData;
    }
    
    // Same fee checkbox functionality
    function setSameFee(type, isChecked) {
        const container = $(`#${type}-input-container`);
        const inputs = container.find('.fee-amount-input');
        if (inputs.length === 0) return;
        
        const firstValue = inputs.first().val();
        inputs.each(function(index) {
            if (index !== 0) {
                $(this).val(isChecked ? firstValue : '');
            }
        });
        calculateTotalFee();
    }
    
    // Calculate total fee
    function calculateTotalFee() {
        let total = 0;
        let selectedFees = [];
        
        $('.fee-checkbox:checked').each(function() {
            const type = $(this).data('type');
            const capitalizedType = type.charAt(0).toUpperCase() + type.slice(1);
            const container = $(`#${type}-input-container`);
            let typeTotal = 0;
            
            container.find('.fee-amount-input').each(function() {
                const val = parseFloat($(this).val());
                if (!isNaN(val)) {
                    typeTotal += val;
                }
            });
            
            if (typeTotal > 0) {
                selectedFees.push(capitalizedType + ' Fee');
                total += typeTotal;
            }
        });
        
        const label = selectedFees.length > 0 ? `Total Fee (${selectedFees.join(' + ')})` : 'Total Fee';
        $('#totalFeeLabel').text(label);
        $('input[name="total_fee"]').val(total.toFixed(2));
    }
    
    // ========== EVENT HANDLERS ==========
    
    // Total seats input
    $('#total_seats').on('input', updateSeatsSummary);
    
    // Fee amount inputs (dynamic event delegation)
    $(document).on('input', '.fee-amount-input', calculateTotalFee);
    
    // Toggle fee category content
    $('.fee-category-header').on('click', function() {
        $(this).closest('.fee-category').toggleClass('active');
        const icon = $(this).find('.fa-chevron-down');
        icon.toggleClass('fa-chevron-down fa-chevron-up');
        $(this).closest('.fee-category').find('.fee-category-content').slideToggle();
    });
    
    // Fee checkbox toggle
    $('.fee-checkbox').on('change', function() {
        const type = $(this).data('type');
        const container = $(`#${type}FeeDetails`);
        const durationSelect = $(`#${type}_fee_duration`);
        
        if (this.checked) {
            container.slideDown();
            // Generate inputs if not already present
            if ($(`#${type}-input-container`).children().length === 0) {
                createInputFields(type, durationSelect.val());
            }
        } else {
            container.slideUp();
        }
        calculateTotalFee();
    });
    
    // Fee duration change
    $('.fee-duration-select').on('change', function() {
        const type = $(this).data('type');
        createInputFields(type, $(this).val());
    });
    
    // Same fee checkbox (dynamic event delegation)
    $(document).on('change', '.same-fee-checkbox', function() {
        const type = $(this).data('type');
        setSameFee(type, this.checked);
    });
    
    // Late fee toggle - FIXED: Make optional not required
    $('.late-fee-checkbox').on('change', function() {
        const type = $(this).data('type');
        const options = $(`#${type}_late_fee_options`);
        options.toggle(this.checked);
        
        // Make fields optional, not required
        options.find('input[type="radio"]').prop('required', this.checked);
        options.find('input[type="number"]').prop('required', this.checked);
    });
    
    // Late fee type change
    $(document).on('change', 'input[type=radio][name$="_late_fee_type"]', function() {
        const name = $(this).attr('name');
        const type = name.replace('_late_fee_type', '');
        const selectedType = $(this).val();
        const labelText = selectedType === 'flat' ? 'Late Fee Amount (Flat ₹)' : 'Late Fee Percentage (%)';
        $(`#${type}_late_fee_options .late-fee-amount label`).text(labelText);
    });
    
    // Partial fee toggle - FIXED: Make optional not required
    $('.partial-fee-checkbox').on('change', function() {
        const type = $(this).data('type');
        const options = $(`#${type}_partial_fee_options`);
        options.toggle(this.checked);
        
        // Make fields optional, not required
        options.find('input[type="radio"]').prop('required', this.checked);
        options.find('input[type="number"]').prop('required', this.checked);
    });
    
    // Partial fee type change
    $(document).on('change', 'input[type=radio][name$="_partial_fee_type"]', function() {
        const name = $(this).attr('name');
        const type = name.replace('_partial_fee_type', '');
        const selectedType = $(this).val();
        const labelText = selectedType === 'fixed' ? 'Partial Fee Amount (₹)' : 'Partial Fee Percentage (%)';
        $(`#${type}_partial_fee_options .partial-fee-amount label`).text(labelText);
    });
    
    // Form submission validation
    $('#edit-fee-form').on('submit', function(e) {
        // Validate sections
        const totalSeats = parseInt($('#total_seats').val()) || 0;
        const allocatedSeats = calculateAllocatedSeats();
        
        if (allocatedSeats > totalSeats) {
            e.preventDefault();
            Toastify({
                text: `Error: Allocated seats (${allocatedSeats}) exceed total seats (${totalSeats})`,
                duration: 4000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
            }).showToast();
            return false;
        }
        
        // Validate at least one section
        if (sections.length === 0) {
            e.preventDefault();
            Toastify({
                text: "Please add at least one section",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
            }).showToast();
            return false;
        }
        
        // Validate fee categories
        const hasCourseFee = $('#courseFeeCheckbox').is(':checked');
        const hasRegistrationFee = $('#registrationFeeCheckbox').is(':checked');
        
        if (!hasCourseFee && !hasRegistrationFee) {
            e.preventDefault();
            Toastify({
                text: "Please select at least one fee category",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
            }).showToast();
            return false;
        }
        
        // Validate course fee inputs
        if (hasCourseFee) {
            const courseInputs = $('#course-input-container .fee-amount-input');
            let hasCourseAmount = false;
            courseInputs.each(function() {
                if ($(this).val().trim() !== '') {
                    hasCourseAmount = true;
                    return false; // break loop
                }
            });
            
            if (!hasCourseAmount) {
                e.preventDefault();
                Toastify({
                    text: "Please enter course fee amount(s)",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                }).showToast();
                return false;
            }
        }
        
        // Validate registration fee inputs
        if (hasRegistrationFee) {
            const regInputs = $('#registration-input-container .fee-amount-input');
            let hasRegAmount = false;
            regInputs.each(function() {
                if ($(this).val().trim() !== '') {
                    hasRegAmount = true;
                    return false; // break loop
                }
            });
            
            if (!hasRegAmount) {
                e.preventDefault();
                Toastify({
                    text: "Please enter registration fee amount",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                }).showToast();
                return false;
            }
        }
        
        // Update section hidden inputs
        updateSectionHiddenInputs();
        
        // Show loading state
        $('#update-fee-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
        
        return true;
    });
    
    // Initialize fee checkboxes state
    $('.fee-checkbox').each(function() {
        if ($(this).is(':checked')) {
            const type = $(this).data('type');
            $(`#${type}FeeDetails`).show();
        }
    });
    
    // Also need to fix the existing fee input fields in HTML to add oninput attribute
    $(document).ready(function() {
        // Add oninput to existing fee inputs
        $('.fee-amount-input').on('input', calculateTotalFee);
        
        // Remove required attribute from late fee and partial fee fields in HTML
        $('.late-fee-options input, .partial-fee-options input').prop('required', false);
    });
});
</script>
@endsection
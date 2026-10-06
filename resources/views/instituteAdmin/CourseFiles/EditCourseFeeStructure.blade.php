@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
.form-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    border: none;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0 !important;
    padding: 20px 25px;
    border: none;
}

.card-header h4 {
    margin: 0;
    font-weight: 600;
}

/* Add/edit specific styles */
.edit-badge {
    background: #17a2b8;
    color: white;
    /*padding: 5px 15px;*/
    border-radius: 20px;
    font-size: 0.85rem;
    /*margin-left: 10px;*/
}

.current-data {
    background: #e7f3ff;
    border-left: 4px solid #17a2b8;
    padding: 10px;
    border-radius: 5px;
    margin: 5px 0;
}
.btn-back {
    background: #ffff;
    color: #838383;
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 14px;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;

}
</style>

<div class="container-fluid">
    <div class="form-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-edit"></i>
                Edit Fee Structure
                <span class="badge edit-badge">{{ $feeStructure->sub_type }}</span>
            </h4>

            <a href="{{ url()->previous() }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back
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
            <form id="edit-fee-form"
                method="POST"
                action="{{ route('course.fee.structure.update', [
                    'fee_structure_id' => $feeStructure->id
                ]) }}">
                @csrf
                @method('PUT')
               <input type="hidden"
                    name="fee_structure_id"
                    value="{{ $feeStructure->id }}">

                <input type="hidden"
                    name="product_id"
                    value="{{ $feeStructure->product_id }}">

                <input type="hidden"
                    name="batch_id"
                    value="{{ $feeStructure->batch_id }}">

                <input type="hidden"
                    name="academic_year_id"
                    value="{{ $feeStructure->academic_year_id }}">

                <input type="hidden"
                    name="academic_year"
                    value="{{ $feeStructure->academic_year }}">
                <!-- Add hidden fields for course details -->
                <input type="hidden" name="course_duration" id="course_duration" value="{{ $ProductDetail->course_duration }}">
                <input type="hidden" name="course_length" id="course_length" value="{{  $ProductDetail->course_length }}">

                <!-- Course Duration Display -->
                <div class="card shadow-sm border-0 rounded-4 p-3 mb-4">
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
                            <label class="form-label">Total Number of Seats</label>
                            <input type="number" name="total_seats" id="total_seats" class="form-control"
                                value="{{ $feeStructure->total_seats }}" min="1">
                            <small class="text-muted">Total capacity for this batch</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Available Seats</label>
                            <input type="number" name="available_seats" id="available_seats" class="form-control"
                                value="{{ $feeStructure->available_seats }}">
                            <small class="text-muted">Will be calculated automatically</small>
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
                            <label class="form-label">Batch Name</label>
                            <input type="text" name="batch" id="batch_name" class="form-control"
                                value="{{ $feeStructure->batch }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Batch Year</label>
                            <select name="batch_year" id="batch_year" class="form-control">
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
                                    id="courseFeeCheckbox" name="course_fee_checkbox" data-type="course" 
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
                                <label class="form-label">Payment Duration</label>
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
                                            <label class="form-label">Amount</label>
                                            <input type="number" 
                                                name="course_fee_amount[]" 
                                                class="form-control fee-amount-input"
                                                value="{{ $payment['amount'] ?? '' }}" 
                                                placeholder="Enter amount">
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
                                            id="course_late_fee" name="course_late_fee" data-type="course"
                                            {{ isset($courseFeeData['late_fee']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="course_late_fee">Apply Late Fee</label>
                                    </div>
                                    <div class="late-fee-options mt-2" id="course_late_fee_options" 
                                        style="{{ isset($courseFeeData['late_fee']) ? '' : 'display: none;' }}">
                                        <div class="mb-2">
                                            <label>Late Fee Type</label>
                                            <div class="form-check">
                                                <input type="radio" name="course_late_fee_type" value="flat"
                                                    class="form-check-input"
                                                    {{ isset($courseFeeData['late_fee']['type']) && $courseFeeData['late_fee']['type'] == 'flat' ? 'checked' : '' }}>
                                                <label class="form-check-label">Flat Amount</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" name="course_late_fee_type" value="percentage"
                                                    class="form-check-input"
                                                    {{ isset($courseFeeData['late_fee']['type']) && $courseFeeData['late_fee']['type'] == 'percentage' ? 'checked' : '' }}>
                                                <label class="form-check-label">Percentage</label>
                                            </div>
                                        </div>
                                        <div class="late-fee-amount mt-2">
                                            <label class="form-label">Late Fee Amount</label>
                                            <input type="number" name="course_late_fee_amount"
                                                class="form-control" 
                                                value="{{ $courseFeeData['late_fee']['amount'] ?? '' }}"
                                                placeholder="Enter amount">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input partial-fee-checkbox"
                                            id="course_partial_fee" name="course_partial_fee" data-type="course"
                                            {{ isset($courseFeeData['partial_payment']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="course_partial_fee">Allow Partial Payment</label>
                                    </div>
                                    <div class="partial-fee-options mt-2" id="course_partial_fee_options"
                                        style="{{ isset($courseFeeData['partial_payment']) ? '' : 'display: none;' }}">
                                        <div class="mb-2">
                                            <label>Partial Payment Type</label>
                                            <div class="form-check">
                                                <input type="radio" name="course_partial_fee_type" value="fixed"
                                                    class="form-check-input"
                                                    {{ isset($courseFeeData['partial_payment']['type']) && $courseFeeData['partial_payment']['type'] == 'fixed' ? 'checked' : '' }}>
                                                <label class="form-check-label">Fixed Amount</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" name="course_partial_fee_type" value="percentage"
                                                    class="form-check-input"
                                                    {{ isset($courseFeeData['partial_payment']['type']) && $courseFeeData['partial_payment']['type'] == 'percentage' ? 'checked' : '' }}>
                                                <label class="form-check-label">Percentage</label>
                                            </div>
                                        </div>
                                        <div class="partial-fee-amount mt-2">
                                            <label class="form-label">Partial Fee Amount</label>
                                            <input type="number" name="course_partial_fee_amount"
                                                class="form-control" 
                                                value="{{ $courseFeeData['partial_payment']['amount'] ?? '' }}"
                                                placeholder="Enter amount">
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
                                    id="registrationFeeCheckbox" name="registration_fee_checkbox" data-type="registration"
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
                                <label class="form-label">Payment Duration</label>
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
                                            <label class="form-label">Amount</label>
                                            <input type="number" 
                                                name="registration_fee_amount[]" 
                                                class="form-control fee-amount-input"
                                                value="{{ $payment['amount'] ?? '' }}" 
                                                placeholder="Enter amount">
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
                                            id="registration_late_fee" name="registration_late_fee" data-type="registration"
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
                                                    {{ isset($registrationFeeData['late_fee']['type']) && $registrationFeeData['late_fee']['type'] == 'flat' ? 'checked' : '' }}>
                                                <label class="form-check-label">Flat Amount</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" name="registration_late_fee_type" value="percentage"
                                                    class="form-check-input"
                                                    {{ isset($registrationFeeData['late_fee']['type']) && $registrationFeeData['late_fee']['type'] == 'percentage' ? 'checked' : '' }}>
                                                <label class="form-check-label">Percentage</label>
                                            </div>
                                        </div>
                                        <div class="late-fee-amount mt-2">
                                            <label class="form-label">Late Fee Amount</label>
                                            <input type="number" name="registration_late_fee_amount"
                                                class="form-control" 
                                                value="{{ $registrationFeeData['late_fee']['amount'] ?? '' }}"
                                                placeholder="Enter amount">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input partial-fee-checkbox"
                                            id="registration_partial_fee" name="registration_partial_fee" data-type="registration"
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
                                                    {{ isset($registrationFeeData['partial_payment']['type']) && $registrationFeeData['partial_payment']['type'] == 'fixed' ? 'checked' : '' }}>
                                                <label class="form-check-label">Fixed Amount</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" name="registration_partial_fee_type" value="percentage"
                                                    class="form-check-input"
                                                    {{ isset($registrationFeeData['partial_payment']['type']) && $registrationFeeData['partial_payment']['type'] == 'percentage' ? 'checked' : '' }}>
                                                <label class="form-check-label">Percentage</label>
                                            </div>
                                        </div>
                                        <div class="partial-fee-amount mt-2">
                                            <label class="form-label">Partial Fee Amount</label>
                                            <input type="number" name="registration_partial_fee_amount"
                                                class="form-control" 
                                                value="{{ $registrationFeeData['partial_payment']['amount'] ?? '' }}"
                                                placeholder="Enter amount">
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

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('fee.structure.view') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Back
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
    
    // Course details for fee calculation
    const courseDuration = '{{ $ProductDetail->course_duration ?? "Monthly" }}';
    const courseLength = {{ $ProductDetail->course_length ?? 1 }};
    
    // Calculate total course months based on course_duration and course_length
    function calculateTotalMonths() {
        let totalMonths = courseLength;
        
        switch(courseDuration.toLowerCase()) {
            case 'hourly':
                // Convert hours to months (assuming 720 hours per month = 30 days * 24 hours)
                totalMonths = Math.ceil(courseLength / (30 * 24));
                break;
            case 'weekly':
                // Convert weeks to months (assuming 4 weeks per month)
                totalMonths = Math.ceil(courseLength / 4);
                break;
            case 'monthly':
                // Already in months
                totalMonths = courseLength;
                break;
            case 'quarterly':
                // Convert quarters to months (3 months per quarter)
                totalMonths = courseLength * 3;
                break;
            case 'half_yearly':
            case 'half-yearly':
                // Convert half years to months (6 months per half year)
                totalMonths = courseLength * 6;
                break;
            case 'yearly':
                // Convert years to months (12 months per year)
                totalMonths = courseLength * 12;
                break;
            default:
                totalMonths = courseLength;
        }
        
        console.log('Course Duration:', courseDuration);
        console.log('Course Length:', courseLength);
        console.log('Total Months:', totalMonths);
        
        return totalMonths;
    }
    
    const totalCourseMonths = calculateTotalMonths();
    
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
            const sectionId = section.id || `section_${index}`;
            const sectionHtml = `
                <div class="section-item card mb-3" id="${sectionId}" data-section-id="${sectionId}">
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
                                <label class="form-label">Section Name</label>
                                <input type="text" class="form-control section-name-input" 
                                    value="${section.name || ''}"
                                    placeholder="e.g., A, B, Morning, Evening" 
                                    oninput="updateSection('${sectionId}')">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Number of Seats</label>
                                <input type="number" class="form-control section-seats-input" 
                                    value="${section.seats || 1}"
                                    min="1" 
                                    placeholder="Enter seats" 
                                    oninput="updateSection('${sectionId}')">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            <label class="form-label">Available Seats</label>
                            <input type="number"
                                class="form-control section-available-seats"
                                value="${section.available_seats ?? 0}"
                                readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Occupied Seats</label>
                            <input type="number"
                                class="form-control section-occupied-seats"
                                value="${section.occupied_seats ?? 0}"
                                readonly>
                        </div>
                        </div>
                        <input type="hidden" class="section-id-input" value="${sectionId}">
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
        const index = parseInt(sectionId.replace('section_', ''));
        sections.splice(index, 1);
        
        // Re-index sections
        reindexSections();
        
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
        const id = sectionElement.find('.section-id-input').val() || sectionId;
        const name = sectionElement.find('.section-name-input').val();
        const seats = parseInt(sectionElement.find('.section-seats-input').val()) || 0;
        
        // Find and update in array
        const sectionIndex = sections.findIndex(s => s.id === id);
        if (sectionIndex !== -1) {
            sections[sectionIndex].name = name;
            sections[sectionIndex].seats = seats;
            sections[sectionIndex].id = id;
            sectionElement.find('.section-name-display').text(name || 'Unnamed Section');
            } else {
                // If not found, add new
                sections.push({
                    id: id,
                    name: name,
                    seats: seats
                });
            }
            
            updateSeatsSummary();
    };
    
    // Reindex sections after removal
    function reindexSections() {
        const sectionsList = $('#sections-list');
        sectionsList.empty();
        
        sections.forEach((section, index) => {
            const sectionId = `section_${index}`;
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
                                <label class="form-label">Section Name</label>
                                <input type="text" class="form-control section-name-input" 
                                       value="${section.name || ''}"
                                       placeholder="e.g., A, B, Morning, Evening" 
                                       oninput="updateSection('${sectionId}')">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Number of Seats</label>
                                <input type="number" class="form-control section-seats-input" 
                                       value="${section.seats || 1}"
                                       min="1" 
                                       placeholder="Enter seats" 
                                       oninput="updateSection('${sectionId}')">
                            </div>
                        </div>
                    </div>
                </div>
            `;
            sectionsList.append(sectionHtml);
        });
    }
    
    // Add new section
    $('#add-section-btn').on('click', function() {
        const totalSeats = parseInt($('#total_seats').val()) || 0;
        const allocatedSeats = calculateAllocatedSeats();
        const availableSeats = totalSeats - allocatedSeats;
        
        if (totalSeats > 0 && availableSeats <= 0) {
            Toastify({
                text: "No available seats left. Increase total seats first.",
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
        
        const maxSeats = totalSeats > 0 ? Math.min(availableSeats, totalSeats) : '';
        
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
                            <label class="form-label">Section Name</label>
                            <input type="text" class="form-control section-name-input" 
                                   placeholder="e.g., A, B, Morning, Evening" 
                                   oninput="updateSection('${sectionId}')">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Number of Seats</label>
                            <input type="number" class="form-control section-seats-input" 
                                   min="1" 
                                   ${maxSeats ? `max="${maxSeats}"` : ''}
                                   value="1"
                                   placeholder="${maxSeats ? 'Max: ' + maxSeats : 'Enter seats'}" 
                                   oninput="updateSection('${sectionId}')">
                            ${maxSeats ? `<small class="text-muted">Max: ${maxSeats} seats available</small>` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('#sections-list').append(sectionHtml);
        
        // Add to sections array
        sections.push({
            name: 'New Section',
            seats: 1
        });
        
        updateSeatsSummary();
    });
    
    // Calculate allocated seats
    function calculateAllocatedSeats() {
        let total = 0;
        sections.forEach(section => {
            total += parseInt(section.seats) || 0;
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
        } else if (availableSeats === 0 && totalSeats > 0) {
            summaryText += ' | ✅ Fully allocated';
            $('#seats-summary-text').removeClass('text-danger');
        } else if (totalSeats > 0) {
            summaryText += ` | ⚠️ ${availableSeats} seats available for new sections`;
            $('#seats-summary-text').removeClass('text-danger');
        } else {
            summaryText += ' | ℹ️ Set total seats to enable seat allocation';
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
        
        console.log('Creating inputs for:', type, 'with duration:', feeDuration);
        console.log('Total Course Months:', totalCourseMonths);
        
        // Clear container
        container.html('');
        
        // Get existing fee data
        const existingData = getExistingFeeData(type);
        
        if (feeDuration === "One Time") {
            const blockHtml = `
                <div class="card shadow-sm p-3 mb-3 rounded duration-block" data-index="1">
                    <h6 class="text-primary mb-3">One Time Payment</h6>
                    <div class="mb-3">
                        <label class="form-label">Fee Amount</label>
                        <input type="number" name="${type}_fee_amount[]" 
                               class="form-control fee-amount-input" 
                               placeholder="Enter amount" 
                               value="${existingData.amount || ''}" 
                               oninput="calculateTotalFee()" />
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
            // Calculate number of payments based on fee duration and total course months
            let totalBlocks = 0;
            
            switch(feeDuration) {
                case "Monthly":
                    totalBlocks = totalCourseMonths;
                    break;
                case "Quarterly":
                    totalBlocks = Math.ceil(totalCourseMonths / 3);
                    break;
                case "Half_yearly":
                    totalBlocks = Math.ceil(totalCourseMonths / 6);
                    break;
                case "Yearly":
                    totalBlocks = Math.ceil(totalCourseMonths / 12);
                    break;
                case "Weekly":
                    totalBlocks = Math.ceil(totalCourseMonths * 4);
                    break;
                case "Hourly":
                    totalBlocks = Math.ceil(totalCourseMonths * 30 * 24);
                    break;
                default:
                    totalBlocks = 1;
            }
            
            // Ensure at least 1 payment
            totalBlocks = Math.max(1, totalBlocks);
            
            console.log('Calculated totalBlocks for', feeDuration + ':', totalBlocks);
            
            // Limit to maximum 50 fields
            const maxFields = 50;
            if (totalBlocks > maxFields) {
                console.warn('Limiting total blocks from', totalBlocks, 'to', maxFields);
                totalBlocks = maxFields;
            }
            
            for (let i = 1; i <= totalBlocks; i++) {
                const existingPayment = existingData.payments && existingData.payments[i - 1];
                
                // Calculate month range for label
                let label = `${feeDuration} Payment ${i}`;
                let startMonth = 0;
                let endMonth = 0;
                
                switch(feeDuration) {
                    case "Monthly":
                        startMonth = i;
                        endMonth = i;
                        label += ` (Month ${i})`;
                        break;
                    case "Quarterly":
                        startMonth = ((i - 1) * 3) + 1;
                        endMonth = Math.min(i * 3, totalCourseMonths);
                        label += ` (Months ${startMonth}-${endMonth})`;
                        break;
                    case "Half_yearly":
                        startMonth = ((i - 1) * 6) + 1;
                        endMonth = Math.min(i * 6, totalCourseMonths);
                        label += ` (Months ${startMonth}-${endMonth})`;
                        break;
                    case "Yearly":
                        startMonth = ((i - 1) * 12) + 1;
                        endMonth = Math.min(i * 12, totalCourseMonths);
                        label += ` (Months ${startMonth}-${endMonth})`;
                        break;
                    case "Weekly":
                        startWeek = ((i - 1) * 4) + 1;
                        endWeek = Math.min(i * 4, totalCourseMonths * 4);
                        label += ` (Weeks ${startWeek}-${endWeek})`;
                        break;
                }
                
                const blockHtml = `
                    <div class="card shadow-sm p-3 mb-3 rounded duration-block" data-index="${i}">
                        <h6 class="text-primary mb-3">${label}</h6>
                        <div class="mb-3">
                            <label class="form-label">Fee Amount</label>
                            <input type="number" name="${type}_fee_amount[]" 
                                   class="form-control fee-amount-input" 
                                   placeholder="Enter amount" 
                                   value="${existingPayment ? existingPayment.amount : ''}" 
                                   oninput="calculateTotalFee()" />
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
    
    // Late fee toggle
    $('.late-fee-checkbox').on('change', function() {
        const type = $(this).data('type');
        const options = $(`#${type}_late_fee_options`);
        options.toggle(this.checked);
    });
    
    // Late fee type change
    $(document).on('change', 'input[type=radio][name$="_late_fee_type"]', function() {
        const name = $(this).attr('name');
        const type = name.replace('_late_fee_type', '');
        const selectedType = $(this).val();
        const labelText = selectedType === 'flat' ? 'Late Fee Amount (Flat ₹)' : 'Late Fee Percentage (%)';
        $(`#${type}_late_fee_options .late-fee-amount label`).text(labelText);
    });
    
    // Partial fee toggle
    $('.partial-fee-checkbox').on('change', function() {
        const type = $(this).data('type');
        const options = $(`#${type}_partial_fee_options`);
        options.toggle(this.checked);
    });
    
    // Partial fee type change
    $(document).on('change', 'input[type=radio][name$="_partial_fee_type"]', function() {
        const name = $(this).attr('name');
        const type = name.replace('_partial_fee_type', '');
        const selectedType = $(this).val();
        const labelText = selectedType === 'fixed' ? 'Partial Fee Amount (₹)' : 'Partial Fee Percentage (%)';
        $(`#${type}_partial_fee_options .partial-fee-amount label`).text(labelText);
    });
    
    // Form submission - FIXED: Add section inputs before submitting
    $('#edit-fee-form').on('submit', function(e) {
        // Update section hidden inputs before submitting
        updateSectionInputs();
        
        // Update checkbox hidden inputs
        updateCheckboxInputs();
        
        // Show loading state
        $('#update-fee-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
        
        return true;
    });
    
    // Update section inputs for form submission
    function updateSectionInputs() {
        // Remove any existing section inputs
        $('input[name="section_names[]"], input[name="section_seats[]"], input[name="section_ids[]"]').remove();
        
        // Add current sections as hidden inputs
        sections.forEach((section, index) => {
            // Add section ID input
            $('<input>').attr({
                type: 'hidden',
                name: 'section_ids[]',
                value: section.id || `section_${index + 1}`
            }).appendTo('#edit-fee-form');
            
            // Add section name input
            $('<input>').attr({
                type: 'hidden',
                name: 'section_names[]',
                value: section.name || ''
            }).appendTo('#edit-fee-form');
            
            // Add section seats input
                $('<input>').attr({
                    type: 'hidden',
                    name: 'section_seats[]',
                    value: section.seats || 0
                }).appendTo('#edit-fee-form');
            });
    }
    
    // Update checkbox inputs for form submission
    function updateCheckboxInputs() {
        // Course fee checkbox
        if ($('#courseFeeCheckbox').is(':checked')) {
            $('<input>').attr({
                type: 'hidden',
                name: 'course_fee_checkbox',
                value: 'on'
            }).appendTo('#edit-fee-form');
        }
        
        // Registration fee checkbox
        if ($('#registrationFeeCheckbox').is(':checked')) {
            $('<input>').attr({
                type: 'hidden',
                name: 'registration_fee_checkbox',
                value: 'on'
            }).appendTo('#edit-fee-form');
        }
        
        // Late fee checkboxes
        if ($('#course_late_fee').is(':checked')) {
            $('<input>').attr({
                type: 'hidden',
                name: 'course_late_fee',
                value: 'on'
            }).appendTo('#edit-fee-form');
        }
        
        if ($('#registration_late_fee').is(':checked')) {
            $('<input>').attr({
                type: 'hidden',
                name: 'registration_late_fee',
                value: 'on'
            }).appendTo('#edit-fee-form');
        }
        
        // Partial fee checkboxes
        if ($('#course_partial_fee').is(':checked')) {
            $('<input>').attr({
                type: 'hidden',
                name: 'course_partial_fee',
                value: 'on'
            }).appendTo('#edit-fee-form');
        }
        
        if ($('#registration_partial_fee').is(':checked')) {
            $('<input>').attr({
                type: 'hidden',
                name: 'registration_partial_fee',
                value: 'on'
            }).appendTo('#edit-fee-form');
        }
    }
    
    // Initialize fee checkboxes state
    $('.fee-checkbox').each(function() {
        if ($(this).is(':checked')) {
            const type = $(this).data('type');
            $(`#${type}FeeDetails`).show();
        }
    });
});
</script>
@endsection
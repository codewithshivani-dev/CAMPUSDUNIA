@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .form-section {
        background: #f8f9fa;
        border-radius: 5px;
        padding: 20px;
        margin-bottom: 20px;
        border-left: 4px solid #007bff;
    }
    .form-section h5 {
        color: #007bff;
        margin-bottom: 20px;
    }
    .required:after { 
        content: " *";
        color: red;
    }
    .time-slot-preview {
        background: #e9f7fe;
        border: 1px solid #b3e0ff;
        border-radius: 5px;
        padding: 15px;
        margin-top: 20px;
    }
    .employee-details {
        background: #e9ecef;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    .availability-check {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 5px;
        padding: 15px;
        margin-top: 20px;
    }
    .conflict-list {
        max-height: 300px;
        overflow-y: auto;
        margin-top: 10px;
    }
    .conflict-item {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 3px;
        padding: 10px;
        margin-bottom: 5px;
        font-size: 0.9em;
    }
    .conflict-time {
        color: #dc3545;
        font-weight: bold;
    }
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .conflict-badge {
        font-size: 0.7em;
        padding: 2px 6px;
        margin-left: 5px;
    }

    .conflict-time {
        font-weight: bold;
        font-size: 0.9em;
        padding: 2px 8px;
    }

    .conflict-details {
        background: #f8f9fa;
        border-radius: 4px;
        padding: 10px;
        margin-top: 10px;
    }

    .conflict-details small {
        display: block;
        margin-bottom: 2px;
    }

    .lecture-conflict {
        border-left-color: #3498db !important;
    }

    .duty-conflict {
        border-left-color: #f39c12 !important;
    }
    
    .border-left-lecture {
        border-left: 4px solid #3498db !important;
    }
    .border-left-duty {
        border-left: 4px solid #f39c12 !important;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0"><i class="fas fa-plus-circle"></i> Assign New Duty</h4>
                        </div>
                        <div>
                            <a href="{{ route('institute.duties.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="assignDutyForm" method="POST" action="{{ route('institute.duties.store') }}">
                        @csrf
                        <div class="form-section">
                            <h5><i class="fas fa-info-circle"></i> Basic Information</h5>
                            <div class="row">
                                <div class="col-md-6 d-flex">
                                    <div class="form-group col-md-8">
                                        <label for="duty_type_id" class="required">Select or Add Duty Type</label>
                                        <select class="form-control" id="duty_type_id" name="duty_type_id" required>
                                            <option value="">Select Duty Type</option>
                                            @foreach($dutyTypes as $id => $type)
                                                <option value="{{ $id }}" 
                                                        data-description="{{ $dutyTypeDescriptions[$id] ?? '' }}"
                                                        {{ old('duty_type_id') == $id ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('duty_type_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group p-0 col-md-4 d-flex align-items-end justify-content-end">
                                        <button type="button" class="btn btn-outline-primary" id="addDutyTypeBtn">
                                            <i class="fas fa-plus"></i> Add Duty Type
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" 
                                            rows="1" placeholder="Enter duty description">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Department Selection Section -->
                        <div class="form-section">
                            <h5><i class="fas fa-building"></i> Department & Employee Selection</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="department_id" class="required">Select Department</label>
                                        <select class="form-control" id="department_id" name="department_id" required>
                                            <option value="">Select Department</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->department_id }}"
                                                    {{ old('department_id') == $department->department_id ? 'selected' : '' }}>
                                                    {{ $department->department }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('department_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="employee_id" class="required">Select Employee</label>
                                        <select class="form-control" id="employee_id" name="employee_id" required 
                                                {{ empty(old('department_id')) ? 'disabled' : '' }}>
                                            <option value="">Select Employee</option>
                                            @if(old('employee_id') && old('department_id'))
                                            @endif
                                        </select>
                                        @error('employee_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <!-- Schedule Section -->
                        <div class="form-section">
                            <h5><i class="fas fa-calendar-alt"></i> Schedule</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="frequency" class="required">Frequency</label>
                                        <select class="form-control" id="frequency" name="frequency" required>
                                            <option value="">Select Frequency</option>
                                            <option value="once" {{ old('frequency') == 'once' ? 'selected' : '' }}>Once</option>
                                            <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>Daily</option>
                                            <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                            <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        </select>
                                        @error('frequency')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="priority" class="required">Priority</label>
                                        <select class="form-control" id="priority" name="priority" required>
                                            <option value="">Select Priority</option>
                                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                        </select>
                                        @error('priority')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Date/Time Fields -->
                            <div id="onceDateField" style="display: {{ old('frequency') == 'once' ? 'block' : 'none' }};">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date" class="required">Date</label>
                                            <input type="date" class="form-control" id="date" name="date" 
                                                   value="{{ old('date', date('Y-m-d')) }}">
                                            @error('date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="recurringDateFields" style="display: {{ in_array(old('frequency'), ['daily', 'weekly', 'monthly']) ? 'block' : 'none' }};">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="from_date" class="required">From Date</label>
                                            <input type="date" class="form-control" id="from_date" name="from_date" 
                                                   value="{{ old('from_date', date('Y-m-d')) }}">
                                            @error('from_date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="to_date" class="required">To Date</label>
                                            <input type="date" class="form-control" id="to_date" name="to_date" 
                                                   value="{{ old('to_date', date('Y-m-d', strtotime('+7 days'))) }}">
                                            @error('to_date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_time" class="required">Start Time</label>
                                        <input type="time" class="form-control" id="start_time" name="start_time" 
                                               value="{{ old('start_time', '09:00') }}" required>
                                        @error('start_time')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_time" class="required">End Time</label>
                                        <input type="time" class="form-control" id="end_time" name="end_time" 
                                               value="{{ old('end_time', '17:00') }}" required>
                                        @error('end_time')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Days of Week (Weekly) -->
                            <div id="weeklyDays" style="display: {{ old('frequency') == 'weekly' ? 'block' : 'none' }};">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Days of Week</label>
                                        <div class="form-group">
                                            @php
                                                $days = [
                                                    ['value' => 0, 'label' => 'Sunday'],
                                                    ['value' => 1, 'label' => 'Monday'],
                                                    ['value' => 2, 'label' => 'Tuesday'],
                                                    ['value' => 3, 'label' => 'Wednesday'],
                                                    ['value' => 4, 'label' => 'Thursday'],
                                                    ['value' => 5, 'label' => 'Friday'],
                                                    ['value' => 6, 'label' => 'Saturday']
                                                ];
                                                $oldDays = old('days_of_week', []);
                                            @endphp
                                            @foreach($days as $day)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="day_{{ $day['value'] }}" 
                                                       name="days_of_week[]" 
                                                       value="{{ $day['value'] }}"
                                                       {{ in_array($day['value'], $oldDays) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="day_{{ $day['value'] }}">
                                                    {{ $day['label'] }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                        @error('days_of_week')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Day of Month (Monthly) -->
                            <div id="monthlyDay" style="display: {{ old('frequency') == 'monthly' ? 'block' : 'none' }};">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="day_of_month">Day of Month  (1-31)</label>
                                            <select class="form-control" id="day_of_month" name="day_of_month">
                                                @for($i = 1; $i <= 31; $i++)
                                                    <option value="{{ $i }}" {{ old('day_of_month') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Employee Availability Check -->
                        <div class="availability-check" id="availabilitySection" style="display: none;">
                            <h6><i class="fas fa-user-clock"></i> Employee Availability Check</h6>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-info btn-sm" id="checkAvailability">
                                        <i class="fas fa-search"></i> Check Availability
                                    </button>
                                    <span id="availabilityResult" class="ml-3"></span>
                                </div>
                            </div>
                            <div id="availabilityDetails" style="display: none;" class="mt-3">
                                <div id="availabilityMessage"></div>
                                <div id="conflictDetails" class="conflict-list"></div>
                            </div>
                        </div>

                        <!-- Location & Additional Details -->
                        <div class="form-section">
                            <h5><i class="fas fa-map-marker-alt"></i> Location & Additional Details</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="block_id">Block - Building</label>
                                        <select class="form-control" id="block_id" name="block_id">
                                            <option value="">Select Block</option>
                                            @if(isset($blocks) && count($blocks) > 0)
                                                @foreach($blocks as $block)
                                                    <option value="{{ $block->id }}" {{ old('block_id') == $block->id ? 'selected' : '' }}>
                                                        {{ $block->name }} - {{ $block->building->name ?? 'No Building Assigned' }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                         <label for="floor_id">Floor</label>
                                        <select class="form-control" id="floor_id" name="floor_id" disabled>
                                            <option value="">Select Floor</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="room_id">Room</label>
                                        <select class="form-control" id="room_id" name="room_id" disabled>
                                            <option value="">Select Room</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="instructions">Special Instructions</label>
                                        <textarea class="form-control" id="instructions" name="instructions" 
                                                  rows="1" placeholder="Any special instructions...">{{ old('instructions') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="required_materials">Required Materials</label>
                                        <textarea class="form-control" id="required_materials" name="required_materials" 
                                                  rows="1" placeholder="List required materials...">{{ old('required_materials') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('institute.duties.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                            <i class="fas fa-save"></i> Assign Duty
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Conflict Check Results -->
                    <div id="conflictResults" class="mt-4" style="display: none;">
                        <div class="alert" id="conflictAlert">
                            <!-- Results will be shown here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Duty Type Modal -->
<div class="modal fade" id="addDutyTypeModal" tabindex="-1" aria-labelledby="addDutyTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addDutyTypeModalLabel">
                    <i class="fas fa-plus-circle"></i> Add New Duty Type
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addDutyTypeForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="new_duty_type" class="required">Duty Type Name</label>
                        <input type="text" class="form-control" id="new_duty_type" name="duty_type" 
                               required placeholder="e.g., Exam Invigilator, Field Trip Supervisor">
                        <div class="invalid-feedback" id="duty_type_error"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_duty_description">Description (Optional)</label>
                        <textarea class="form-control" id="new_duty_description" name="description" 
                                  rows="3" placeholder="Enter description for this duty type"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveDutyTypeBtn">
                        <i class="fas fa-save"></i> Save Duty Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            width: '100%'
        });

        // Handle duty type change to auto-fill description
        $('#duty_type_id').change(function() {
            const selectedOption = $(this).find(':selected');
            const description = selectedOption.data('description');
            
            if (description) {
                $('#description').val(description);
            }
        });

        // Handle add duty type button click
        $('#addDutyTypeBtn').click(function(e) {
            e.preventDefault();
            $('#addDutyTypeModal').modal('show');
        });

        // Handle duty type form submission
        $('#addDutyTypeForm').submit(function(e) {
            e.preventDefault();
            
            // Clear previous errors
            $('#new_duty_type').removeClass('is-invalid');
            $('#duty_type_error').text('');
            
            // Show loading state
            const saveBtn = $('#saveDutyTypeBtn');
            const originalText = saveBtn.html();
            saveBtn.prop('disabled', true).html('<span class="loading-spinner"></span> Saving...');
            
            // Submit form via AJAX
            $.ajax({
                url: '{{ route("institute.duties.duty-type.store") }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        // Add new option to select dropdown
                        const dutyTypeSelect = $('#duty_type_id');
                        const newOption = new Option(
                            response.duty_type.duty_type,
                            response.duty_type.id,
                            true,
                            true
                        );
                        
                        // Add description as data attribute
                        $(newOption).data('description', response.duty_type.description);
                        
                        dutyTypeSelect.append(newOption);
                        dutyTypeSelect.trigger('change');
                        
                        // Auto-fill description field
                        if (response.duty_type.description) {
                            $('#description').val(response.duty_type.description);
                        }
                        
                        // Show success message
                        showToast('success', 'Duty type added successfully!');
                        
                        // Close modal
                        $('#addDutyTypeModal').modal('hide');
                        
                        // Reset form
                        $('#addDutyTypeForm')[0].reset();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // Validation errors
                        const errors = xhr.responseJSON.errors;
                        if (errors.duty_type) {
                            $('#new_duty_type').addClass('is-invalid');
                            $('#duty_type_error').text(errors.duty_type[0]);
                        }
                    } else {
                        showToast('error', 'Failed to save duty type. Please try again.');
                    }
                },
                complete: function() {
                    // Reset button state
                    saveBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Clear validation on modal close
        $('#addDutyTypeModal').on('hidden.bs.modal', function() {
            $('#addDutyTypeForm')[0].reset();
            $('#new_duty_type').removeClass('is-invalid');
            $('#duty_type_error').text('');
        });

        // Handle department change
        $('#department_id').change(function() {
            const departmentId = $(this).val();
            const employeeSelect = $('#employee_id');
            
            if (departmentId) {
                // Enable employee select
                employeeSelect.prop('disabled', false);
                
                // Clear existing options
                employeeSelect.empty().append('<option value="">Loading employees...</option>');
                
                // Fetch employees by department
                $.ajax({
                    url: '{{ route("ajax.employees.by.department") }}',
                    type: 'GET',
                    data: {
                        department_id: departmentId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            employeeSelect.empty().append('<option value="">Select Employee</option>');
                            
                            $.each(response.employees, function(index, employee) {
                                employeeSelect.append(
                                    $('<option></option>')
                                        .attr('value', employee.employee_id)
                                        .text(employee.name)
                                        .data('employee', employee)
                                );
                            });
                            
                            // If there's an old value, select it
                            @if(old('employee_id'))
                                employeeSelect.val('{{ old('employee_id') }}').trigger('change');
                            @endif
                            
                            // Show availability section
                            $('#availabilitySection').show();
                        } else {
                            employeeSelect.empty().append('<option value="">Error loading employees</option>');
                            showToast('error', response.message || 'Failed to load employees');
                        }
                    },
                    error: function(xhr) {
                        employeeSelect.empty().append('<option value="">Error loading employees</option>');
                        showToast('error', 'Failed to load employees. Please try again.');
                    }
                });
            } else {
                // Disable employee select and clear it
                employeeSelect.prop('disabled', true).empty().append('<option value="">Select Employee</option>');
                
                $('#availabilitySection').hide();
            }
        });

        // Handle employee selection
        $('#employee_id').change(function() {
            const selectedOption = $(this).find(':selected');
            const employeeData = selectedOption.data('employee');
            // Clear previous availability check
            clearAvailabilityCheck();
        });

        // Handle frequency change
        $('#frequency').change(function() {
            const frequency = $(this).val();
            
            // Hide all conditional fields
            $('#onceDateField, #recurringDateFields, #weeklyDays, #monthlyDay').hide();
            
            // Show relevant fields based on frequency
            if (frequency === 'once') {
                $('#onceDateField').show();
            } else if (frequency === 'weekly') {
                $('#recurringDateFields').show();
                $('#weeklyDays').show();
            } else if (frequency === 'monthly') {
                $('#recurringDateFields').show();
                $('#monthlyDay').show();
            } else if (frequency === 'daily') {
                $('#recurringDateFields').show();
            }
            
            // Clear availability check when schedule changes
            clearAvailabilityCheck();
        });

        // Clear availability check results
        function clearAvailabilityCheck() {
            $('#availabilityResult').empty();
            $('#availabilityDetails').hide();
            $('#availabilityMessage').empty();
            $('#conflictDetails').empty();
        }

        // Check employee availability
        $('#checkAvailability').click(function() {
            const employeeId = $('#employee_id').val();
            const frequency = $('#frequency').val();
            const startTime = $('#start_time').val();
            const endTime = $('#end_time').val();
            
            if (!employeeId) {
                showToast('warning', 'Please select an employee first');
                return;
            }
            
            if (!frequency) {
                showToast('warning', 'Please select frequency');
                return;
            }
            
            if (!startTime || !endTime) {
                showToast('warning', 'Please set start and end time');
                return;
            }
            
            // Prepare time slot data
            const timeSlot = {
                start_time: startTime,
                end_time: endTime,
                frequency: frequency
            };
            
            // Add date based on frequency
            if (frequency === 'once') {
                const date = $('#date').val();
                if (!date) {
                    showToast('warning', 'Please select a date');
                    return;
                }
                timeSlot.date = date;
               
            } else {
                const fromDate = $('#from_date').val();
                const toDate = $('#to_date').val();
                
                if (!fromDate || !toDate) {
                    showToast('warning', 'Please select from and to dates');
                    return;
                }
                
                timeSlot.valid_from = fromDate;
                timeSlot.valid_to = toDate;
                
                if (frequency === 'weekly') {
                    const days = [];
                    $('input[name="days_of_week[]"]:checked').each(function() {
                        days.push(parseInt($(this).val()));
                    });
                    timeSlot.days_of_week = days;
            
                } else if (frequency === 'monthly') {
                    const dayOfMonth = $('#day_of_month').val();
                    timeSlot.day_of_month = dayOfMonth;
                    
                }
            }
            
            // Prepare context
            const context = {
                institute_id: '{{ auth()->user()->institute_id }}',
                @if(auth()->user()->is_branch_admin && auth()->user()->branch_id)
                branch_id: '{{ auth()->user()->branch_id }}',
                is_branch_admin: true
                @else
                branch_id: null,
                is_branch_admin: false
                @endif
            };
            
            // Show loading
            $('#checkAvailability').prop('disabled', true).html('<span class="loading-spinner"></span> Checking...');
            $('#availabilityResult').html('<span class="text-info">Checking availability...</span>');
            $('#availabilityDetails').hide();
            
            // Make AJAX call to check availability
            $.ajax({
                url: '{{ route("institute.duties.check.availability") }}',
                type: 'POST',
                data: {
                    employee_id: employeeId,
                    time_slot: timeSlot,
                    context: context,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    
                    $('#checkAvailability').prop('disabled', false).html('<i class="fas fa-search"></i> Check Availability');
                    
                    if (response.available) {
                        $('#availabilityResult').html('<span class="text-success"><i class="fas fa-check-circle"></i> Employee is available</span>');
                        $('#availabilityDetails').hide();
                    } else {
                        $('#availabilityResult').html('<span class="text-danger"><i class="fas fa-exclamation-circle"></i> Employee is not available</span>');
                        
                        // Show detailed conflict information
                        let message = '<div class="alert alert-warning">';
                        message += '<strong>Note:</strong> Employee has existing schedule during this time.<br>';
                        
                        // Count lecture vs duty conflicts
                        let lectureCount = response.conflicting_assignments.filter(c => c.type === 'lecture').length;
                        let dutyCount = response.conflicting_assignments.filter(c => c.type === 'duty').length;
                        
                        if (lectureCount > 0 && dutyCount > 0) {
                            message += `Found ${lectureCount} lecture(s) and ${dutyCount} duty/duties conflicting. `;
                        } else if (lectureCount > 0) {
                            message += `Found ${lectureCount} lecture(s) conflicting. `;
                        } else if (dutyCount > 0) {
                            message += `Found ${dutyCount} duty/duties conflicting. `;
                        }
                        
                        message += 'If you assign this duty, the existing assignments will be marked as "On Hold" and this duty will be active.';
                        message += '</div>';
                        
                        let conflictDetails = '';
                        if (response.conflicting_assignments && response.conflicting_assignments.length > 0) {
                            conflictDetails += '<h6>Conflicting Schedules:</h6>';
                            
                            // Group by type
                            const lectures = response.conflicting_assignments.filter(c => c.type === 'lecture');
                            const duties = response.conflicting_assignments.filter(c => c.type === 'duty');
                            
                            // Show lectures first
                            if (lectures.length > 0) {
                                conflictDetails += '<div class="mb-3">';
                                conflictDetails += '<h6 class="text-primary"><i class="fas fa-chalkboard-teacher"></i> Lecture<small>(s)</small>:</h6>';
                                lectures.forEach(function(conflict) {
                                    conflictDetails += formatLectureConflict(conflict);
                                });
                                conflictDetails += '</div>';
                            }
                            
                            // Show duties
                            if (duties.length > 0) {
                                conflictDetails += '<div>';
                                conflictDetails += '<h6 class="text-warning"><i class="fas fa-tasks"></i> Duties:</h6>';
                                duties.forEach(function(conflict) {
                                    conflictDetails += formatDutyConflict(conflict);
                                });
                                conflictDetails += '</div>';
                            }
                        }
                        
                        $('#availabilityMessage').html(message);
                        $('#conflictDetails').html(conflictDetails);
                        $('#availabilityDetails').show();
                    }
                },
                error: function(xhr) {
                    $('#checkAvailability').prop('disabled', false).html('<i class="fas fa-search"></i> Check Availability');
                    $('#availabilityResult').html('<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Error checking availability</span>');
                    showToast('error', 'Failed to check availability. Please try again.');
                }
            });
        });

        // Helper functions to format conflicts
        function formatLectureConflict(conflict) {
            let html = '<div class="conflict-item mb-2 border-left-lecture">';
            html += '<div class="d-flex justify-content-between">';
            html += '<div class="">';
            html += '<span class="badge text-white bg-info">Lecture</span>';
            html += '</div>';
            html += '<span>Timing: ' + conflict.time + '</span>';
            html += '</div>';
            
            html += '<div class="row">';
            if (conflict.main_subject) {
                html += '<div class="col-md-3"><small>Subject: ' + conflict.main_subject + '</small></div>';
            }
            if (conflict.sub_subject) {
                html += '<div><small>Sub Subject: ' + conflict.sub_subject + '</small></div>';
            }

            if (conflict.course_type) {
                html += '<div class="col-md-3"><small>Course: ' + conflict.course_type + '</small></div>';
            }
           
            if (conflict.location) {
                html += '<div class="col-md-3"><small>Room: ' + conflict.location + '</small></div>';
            }
            
            if (conflict.valid_from && conflict.valid_to) {
                html += '<div class="col-md-3"><small>From: ' + conflict.valid_from + ' to ' + conflict.valid_to + '</small></div>';
            }
            html += '</div>';
            
            html += '</div>';
            return html;
        }

        function formatDutyConflict(conflict) {
            let html = '<div class="conflict-item mb-2 border-left-duty">';
            html += '<div class="d-flex justify-content-between align-items-start">';
            html += '<div>';
            html += '<strong>' + conflict.title + '</strong>';
            html += '<span class="badge bg-warning text-dark ms-2">Duty</span>';
            if (conflict.duty_type) {
                html += '<span class="badge text-white bg-secondary ms-1">' + conflict.duty_type + '</span>';
            }
            html += '</div>';
            html += '<span class="conflict-time text-white badge text-white bg-danger">' + conflict.time + '</span>';
            html += '</div>';
            
            if (conflict.description) {
                html += '<div><small>Description: ' + conflict.description + '</small></div>';
            }
        
            // Date details
            if (conflict.frequency === 'once' && conflict.date) {
                html += '<div><small>Date: ' + conflict.date + '</small></div>';
            } else if (conflict.from_date && conflict.to_date) {
                html += '<div><small>Period: ' + conflict.from_date + ' to ' + conflict.to_date + '</small></div>';
            }
            
            if (conflict.location) {
                html += '<div><small>Location: ' + conflict.location + '</small></div>';
            }
            if (conflict.venue) {
                html += '<div><small>Venue: ' + conflict.venue + '</small></div>';
            }
   
            html += '</div>';
            return html;
        }

        // Add CSS for different border colors based on type
        const style = document.createElement('style');
        style.textContent = `
            .border-left-lecture {
                border-left: 4px solid #3498db !important;
            }
            .border-left-duty {
                border-left: 4px solid #f39c12 !important;
            }
            .conflict-item {
                background: #fff;
                border: 1px solid #dee2e6;
                border-radius: 4px;
                padding: 10px;
                transition: all 0.3s;
            }
            .conflict-item:hover {
                background: #f8f9fa;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
        `;
        document.head.appendChild(style);
        // Form submission
        $('#assignDutyForm').submit(function(e) {
            e.preventDefault();
            
            const employeeId = $('#employee_id').val();
            
            if (!employeeId) {
                showToast('error', 'Please select an employee');
                return;
            }
            
            // Check if employee is available
            const availabilityResult = $('#availabilityResult').text();
            
            // Get confirmation message based on availability
            let confirmMessage = 'Are you sure you want to assign this duty?';
            let confirmButtonText = 'Yes, Assign Duty';
            
            if (availabilityResult.includes('not available')) {
                confirmMessage = '⚠️ Employee is not available during this time!\n\n' +
                                'Conflicting lectures will be marked as "On Hold".\n\n' +
                                'Do you want to assign this duty anyway?';
                confirmButtonText = 'Yes, Assign Anyway';
            }
            
            // Show custom confirmation dialog
            showCustomConfirmation(
                'Confirm Duty Assignment',
                confirmMessage,
                confirmButtonText,
                'Cancel'
            ).then((confirmed) => {
                if (confirmed) {
                    // Show loading on submit button
                    const submitBtn = $('#submitBtn');
                    const originalText = submitBtn.html();
                    submitBtn.prop('disabled', true).html('<span class="loading-spinner"></span> Assigning...');
                    
                    // Submit the form programmatically
                    $(this).off('submit').submit();
                }
            });
        });

        function showCustomConfirmation(title, message, confirmText, cancelText) {
            return new Promise((resolve) => {
                const modalId = 'confirmationModal';
                
                // Remove existing modal if any
                $(`#${modalId}`).remove();
                
                // Create modal HTML
                const modalHtml = `
                    <div class="modal fade" id="${modalId}" tabindex="-1" aria-labelledby="${modalId}Label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="${modalId}Label">
                                        <i class="fas fa-exclamation-triangle"></i> ${title}
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        ${message.replace(/\n/g, '<br>')}
                                    </div>
                                 
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" id="cancelBtn">
                                        <i class="fas fa-times"></i> ${cancelText}
                                    </button>
                                    <button type="button" class="btn btn-primary" id="confirmBtn">
                                        <i class="fas fa-check"></i> ${confirmText}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Append modal to body
                $('body').append(modalHtml);
                
                // Show modal
                const modal = new bootstrap.Modal(document.getElementById(modalId));
                modal.show();
                
                // Handle confirm button
                $(`#${modalId} #confirmBtn`).on('click', function() {
                    modal.hide();
                    setTimeout(() => {
                        $(`#${modalId}`).remove();
                        resolve(true);
                    }, 300);
                });
                
                // Handle cancel button
                $(`#${modalId} #cancelBtn`).on('click', function() {
                    modal.hide();
                    setTimeout(() => {
                        $(`#${modalId}`).remove();
                        resolve(false);
                    }, 300);
                });
                
                // Handle modal close
                $(`#${modalId}`).on('hidden.bs.modal', function() {
                    $(this).remove();
                    resolve(false);
                });
            });
        }
        // Time input change triggers availability clear
        $('#start_time, #end_time, #date, #from_date, #to_date').change(function() {
            clearAvailabilityCheck();
        });
        

          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }); 
            // When block is selected, load floors
        $('#block_id').on('change', function() {
            let blockId = $(this).val();
            let floorSelect = $('#floor_id');
            let roomSelect = $('#room_id');
            
            if (blockId) {
                // Reset and disable dependent selects
                floorSelect.prop('disabled', false).html('<option value="">Loading floors...</option>');
                roomSelect.prop('disabled', true).html('<option value="">Select Room</option>');
                
                $.ajax({
                    url: '/ajax/get-floors-by-block',
                    type: 'POST',
                    data: {
                        block_id: blockId
                    },
                    success: function(response) {
                        if (response.success) {
                            let options = '<option value="">Select Floor</option>';
                            $.each(response.floors, function(key, floor) {
                                options += '<option value="' + floor.id + '">' + ' ' + floor.floor_number + '</option>';
                            });
                            floorSelect.html(options);
                        } else {
                            floorSelect.html('<option value="">No floors found</option>');
                            toastr.error(response.message || 'Failed to load floors');
                        }
                    },
                    error: function(xhr) {
                        floorSelect.html('<option value="">Error loading floors</option>');
                        toastr.error('Error loading floors. Please try again.');
                    }
                });
            } else {
                // Reset if no block selected
                floorSelect.prop('disabled', true).html('<option value="">Select Floor</option>');
                roomSelect.prop('disabled', true).html('<option value="">Select Room</option>');
            }
        });

        // When floor is selected, load rooms
        $('#floor_id').on('change', function() {
            let floorId = $(this).val();
            let blockId = $('#block_id').val();
            let roomSelect = $('#room_id');
            
            if (floorId && blockId) {
                roomSelect.prop('disabled', false).html('<option value="">Loading rooms...</option>');
                
                $.ajax({
                    url: '/ajax/get-rooms-by-floor',
                    type: 'POST',
                    data: {
                        floor_id: floorId,
                        block_id: blockId
                    },
                    success: function(response) {
                        if (response.success) {
                            let options = '<option value="">Select Room</option>';
                            $.each(response.rooms, function(key, room) {
                                options += '<option value="' + room.id + '">' + room.room_name  + '</option>';
                            });
                            roomSelect.html(options);
                        } else {
                            roomSelect.html('<option value="">No rooms found</option>');
                            toastr.error(response.message || 'Failed to load rooms');
                        }
                    },
                    error: function(xhr) {
                        roomSelect.html('<option value="">Error loading rooms</option>');
                        toastr.error('Error loading rooms. Please try again.');
                    }
                });
            } else {
                roomSelect.prop('disabled', true).html('<option value="">Select Room</option>');
            }
        });
        // Toast notification function
        function showToast(type, message) {
            const toast = $(`
                <div class="toast align-items-center text-white bg-${type} border-0 position-fixed top-0 end-0 m-3" role="alert" style="z-index: 1060">
                    <div class="d-flex">
                        <div class="toast-body">${message}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `);
            
            $('body').append(toast);
            const bsToast = new bootstrap.Toast(toast[0]);
            bsToast.show();
            
            toast.on('hidden.bs.toast', function() {
                $(this).remove();
            });
        }
        // Trigger department change on page load if department is selected
        @if(old('department_id'))
            $('#department_id').trigger('change');
        @endif
        
        // Trigger frequency change on page load if frequency is set
        @if(old('frequency'))
            $('#frequency').trigger('change');
        @endif
    });
</script>
@endsection
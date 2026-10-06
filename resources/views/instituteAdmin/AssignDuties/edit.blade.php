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
        border-left: 4px solid #28a745;
    }
    .form-section h5 {
        color: #28a745;
        margin-bottom: 20px;
    }
    .required:after {
        content: " *";
        color: red;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0"><i class="fas fa-edit"></i> Edit Duty Assignment</h4>
                            <small class="mb-0">ID: {{ $duty->id }} | Created: {{ $duty->created_at->format('d/m/Y') }}</small>
                        </div>
                        <div>
                            <a href="{{ route('institute.duties.show', $duty->id) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('institute.duties.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="editDutyForm" method="POST" action="{{ route('institute.duties.update', $duty->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information Section -->
                        <div class="form-section">
                            <h5><i class="fas fa-info-circle"></i> Basic Information</h5>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="employee_duty_type_id" class="required">Select or Add Duty Type</label>
                                        <select class="form-control" id="employee_duty_type_id" name="employee_duty_type_id" required>
                                            <option value="">Select Duty Type</option>
                                            @foreach($dutyTypes as $id => $type)
                                                <option value="{{ $id }}"
                                                    data-description="{{ $dutyTypeDescriptions[$id] ?? '' }}"
                                                     {{ old('employee_duty_type_id', $duty->employee_duty_type_id) == $id ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @error('employee_duty_type_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="1">{{ $duty->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Department Selection Section -->
                        <div class="form-section">
                            <h5><i class="fas fa-building"></i> Department & Employee Details</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="department_id" class="required">Department</label>
                                        <input class="form-control" id="department_id" name="department_id" value="{{ $departments }}" readonly></input>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="employee_id" class="required">Employee</label>
                                        <input class="form-control" id="employee_id" name="employee_id" value="{{ $duty->employee->name }}" readonly></input>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Section -->
                        <div class="form-section">
                            <h5><i class="fas fa-calendar-alt"></i> Schedule</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="frequency" class="required">Frequency</label>
                                        <select class="form-control" id="frequency" name="frequency" required>
                                            <option value="">Select Frequency</option>
                                            <option value="once" {{ $duty->frequency == 'once' ? 'selected' : '' }}>Once</option>
                                            <option value="daily" {{ $duty->frequency == 'daily' ? 'selected' : '' }}>Daily</option>
                                            <option value="weekly" {{ $duty->frequency == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                            <option value="monthly" {{ $duty->frequency == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="priority" class="required">Priority</label>
                                        <select class="form-control" id="priority" name="priority" required>
                                            <option value="">Select Priority</option>
                                            <option value="low" {{ $duty->priority == 'low' ? 'selected' : '' }}>Low</option>
                                            <option value="medium" {{ $duty->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="high" {{ $duty->priority == 'high' ? 'selected' : '' }}>High</option>
                                            <option value="urgent" {{ $duty->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status" class="required">Status</label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="pending" {{ $duty->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="assigned" {{ $duty->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                            <option value="in_progress" {{ $duty->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="completed" {{ $duty->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $duty->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Date/Time Fields -->
                            <div id="onceDateField" style="display: {{ $duty->frequency == 'once' ? 'block' : 'none' }};">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date" class="required">Date</label>
                                            <input type="date" class="form-control" id="date" name="date" 
                                                   value="{{ $duty->date ? \Carbon\Carbon::parse($duty->date)->format('Y-m-d') : '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="recurringDateFields" style="display: {{ in_array($duty->frequency, ['daily', 'weekly', 'monthly']) ? 'block' : 'none' }};">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="from_date" class="required">From Date</label>
                                            <input type="date" class="form-control" id="from_date" name="from_date" 
                                                   value="{{ $duty->from_date ? \Carbon\Carbon::parse($duty->from_date)->format('Y-m-d') : '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="to_date" class="required">To Date</label>
                                            <input type="date" class="form-control" id="to_date" name="to_date" 
                                                   value="{{ $duty->to_date ? \Carbon\Carbon::parse($duty->to_date)->format('Y-m-d') : '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_time" class="required">Start Time</label>
                                        <input type="time"
                                            class="form-control"
                                            id="start_time"
                                            name="start_time"
                                            value="{{ old('start_time', optional($duty->start_time)->format('H:i')) }}"
                                            required>

                                        @error('start_time')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_time" class="required">End Time</label>
                                        <input type="time"
                                            class="form-control"
                                            id="end_time"
                                            name="end_time"
                                            value="{{ old('end_time', optional($duty->end_time)->format('H:i')) }}"
                                            required>

                                        @error('end_time')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Days of Week (Weekly) -->
                            <div id="weeklyDays" style="display: {{ $duty->frequency == 'weekly' ? 'block' : 'none' }};">
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
                                                $assignedDays = is_array($duty->days_of_week) ? $duty->days_of_week
                                                    : json_decode($duty->days_of_week ?? '[]', true);
                                            @endphp
                                            @foreach($days as $day)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="day_{{ $day['value'] }}" 
                                                       name="days_of_week[]" 
                                                       value="{{ $day['value'] }}"
                                                       {{ in_array($day['value'], $assignedDays) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="day_{{ $day['value'] }}">
                                                    {{ $day['label'] }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Day of Month (Monthly) -->
                            <div id="monthlyDay" style="display: {{ $duty->frequency == 'monthly' ? 'block' : 'none' }};">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="day_of_month">Day of Month</label>
                                            <select class="form-control" id="day_of_month" name="day_of_month">
                                                @for($i = 1; $i <= 31; $i++)
                                                    <option value="{{ $i }}" {{ $duty->day_of_month == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
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
                                            @foreach($blocks as $block)
                                                <option value="{{ $block->id }}"
                                                    {{ old('block_id', $duty->block_id) == $block->id ? 'selected' : '' }}>
                                                    {{ $block->name }} - {{ $block->building->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="floor_id">Floor</label>
                                        <select class="form-control" id="floor_id" name="floor_id">
                                            <option value="">Select Floor</option>
                                            @foreach($floors->where('block_id', old('block_id', $duty->block_id)) as $floor)
                                                <option value="{{ $floor->id }}"
                                                    {{ old('floor_id', $duty->floor_id) == $floor->id ? 'selected' : '' }}>
                                                    {{ $floor->floor_number  }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="room_id">Room</label>
                                        <select class="form-control" id="room_id" name="room_id">
                                            <option value="">Select Room</option>
                                            @foreach($rooms->where('floor_id', old('floor_id', $duty->floor_id)) as $room)
                                                <option value="{{ $room->id }}"
                                                    {{ old('room_id', $duty->room_id) == $room->id ? 'selected' : '' }}>
                                                    {{ $room->room_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="instructions">Special Instructions</label>
                                        <textarea class="form-control" id="instructions" name="instructions" 
                                                  rows="1">{{ $duty->instructions }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="required_materials">Required Materials</label>
                                        <textarea class="form-control" id="required_materials" name="required_materials" 
                                                  rows="1">{{ $duty->required_materials }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Supervisor Section -->
                        <div class="form-section d-none">
                            <h5><i class="fas fa-user-tie"></i> Supervisor (Optional)</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="supervisor_id">Supervisor</label>
                                        <select class="form-control" id="supervisor_id" name="supervisor_id">
                                            <option value="">Select Supervisor</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ $duty->supervisor_id == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('institute.duties.show', $duty->id) }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-info" id="checkConflict">
                                            <i class="fas fa-search"></i> Check for Conflicts
                                        </button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save"></i> Update Duty
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            width: '100%'
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
        });

        // Trigger change on page load
        $('#frequency').trigger('change');

        // Form submission with confirmation
        $('#editDutyForm').submit(function(e) {
            e.preventDefault();
            
            // Check for conflicts first
            $('#checkConflict').click();
            
            // Wait a moment and then check if there are conflicts
            setTimeout(function() {
                const conflictAlert = $('#conflictAlert');
                if (conflictAlert.hasClass('alert-danger')) {
                    // Conflicts found, ask user to confirm
                    if (confirm('Conflicts found! Do you want to update the duty anyway?')) {
                        $('#editDutyForm').off('submit').submit();
                    }
                } else {
                    // No conflicts, submit form
                    $('#editDutyForm').off('submit').submit();
                }
            }, 500);
        });

        // Auto-save status change to completed
        $('#status').change(function() {
            if ($(this).val() === 'completed') {
                if (confirm('Mark this duty as completed? This will set the completion date to now.')) {
                    // You can add additional logic here to set completion date
                }
            }
        });
    });
</script>
@endsection
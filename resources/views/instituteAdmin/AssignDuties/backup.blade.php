@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .timeline-day {
        min-height: 800px;
        border-right: 1px solid #dee2e6;
    }
    .time-slot {
        height: 60px;
        border-bottom: 1px solid #f0f0f0;
        position: relative;
    }
    .time-label {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 12px;
        color: #6c757d;
    }
    .duty-block {
        position: absolute;
        background: #e3f2fd;
        border-left: 3px solid #2196f3;
        border-radius: 3px;
        padding: 5px;
        font-size: 12px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s;
    }
    .duty-block:hover {
        transform: scale(1.02);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .duty-conflict {
        background: #ffebee;
        border-left: 3px solid #f44336;
    }
    .avatar-sm {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .schedule-header {
        background: #f8f9fa;
        font-weight: bold;
        text-align: center;
        padding: 10px;
    }
</style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-tasks"></i> Assign Duties to Employees</h4>
                    </div>
                    <div class="card-body">
                        <!-- Filters Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="fas fa-filter"></i> Filters</h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="filterForm">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="department_id">Department</label>
                                                        <select class="form-control select2" id="department_id" name="department_id">
                                                            <option value="">All Departments</option>
                                                            @foreach($departments as $department)
                                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="section_id">Section</label>
                                                        <select class="form-control select2" id="section_id" name="section_id">
                                                            <option value="">All Sections</option>
                                                            @foreach($sections as $section)
                                                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="date_range">Date Range</label>
                                                        <input type="text" class="form-control date-range-picker" id="date_range" name="date_range" placeholder="Select Date Range">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="subject_type">Subject Type</label>
                                                        <select class="form-control select2" id="subject_type" name="subject_type">
                                                            <option value="">All Types</option>
                                                            <option value="lecture">Lecture</option>
                                                            <option value="practical">Practical</option>
                                                            <option value="tutorial">Tutorial</option>
                                                            <option value="lab">Lab</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 text-right">
                                                    <button type="button" id="applyFilters" class="btn btn-primary">
                                                        <i class="fas fa-search"></i> Apply Filters
                                                    </button>
                                                    <button type="button" id="resetFilters" class="btn btn-secondary">
                                                        <i class="fas fa-redo"></i> Reset
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Employee Schedule Timeline View -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Employee Schedule Timeline</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="timelineControls" class="mb-3">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-outline-primary active" data-view="day">
                                                    <i class="fas fa-calendar-day"></i> Day
                                                </button>
                                                <button type="button" class="btn btn-outline-primary" data-view="week">
                                                    <i class="fas fa-calendar-week"></i> Week
                                                </button>
                                                <button type="button" class="btn btn-outline-primary" data-view="month">
                                                    <i class="fas fa-calendar"></i> Month
                                                </button>
                                            </div>
                                            <div class="float-right">
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#assignDutyModal">
                                                    <i class="fas fa-plus-circle"></i> Assign New Duty
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div id="scheduleTimeline">
                                            <!-- Timeline will be loaded dynamically via AJAX -->
                                            <div class="text-center py-5">
                                                <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Select filters and apply to view schedule</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assigned Duties Table -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="fas fa-list-alt"></i> Assigned Duties</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="dutiesTable">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Employee</th>
                                                        <th>Subject</th>
                                                        <th>Time Slot</th>
                                                        <th>Days</th>
                                                        <th>Section</th>
                                                        <th>Location</th>
                                                        <th>Validity</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($duties as $duty)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm mr-2">
                                                                    <span class="avatar-title bg-primary rounded-circle">
                                                                        {{ substr($duty->employee->name ?? 'E', 0, 1) }}
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    <h6 class="mb-0">{{ $duty->employee->name ?? 'N/A' }}</h6>
                                                                    <small class="text-muted">{{ $duty->employee->employee_id ?? '' }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <strong>{{ $duty->subject->name ?? 'N/A' }}</strong><br>
                                                            <small class="text-muted">{{ $duty->subject_type }}</small>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-light">
                                                                {{ \Carbon\Carbon::parse($duty->start_time)->format('h:i A') }} - 
                                                                {{ \Carbon\Carbon::parse($duty->end_time)->format('h:i A') }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $days = json_decode($duty->days_of_week);
                                                                $dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                                                            @endphp
                                                            @if(is_array($days))
                                                                @foreach($days as $day)
                                                                    <span class="badge badge-info mr-1">{{ $dayNames[$day] ?? $day }}</span>
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td>{{ $duty->section->name ?? 'N/A' }}</td>
                                                        <td>{{ $duty->location ?? 'N/A' }}</td>
                                                        <td>
                                                            <small>
                                                                {{ \Carbon\Carbon::parse($duty->valid_from)->format('d/m/Y') }} -<br>
                                                                {{ \Carbon\Carbon::parse($duty->valid_to)->format('d/m/Y') }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $now = now();
                                                                $validFrom = \Carbon\Carbon::parse($duty->valid_from);
                                                                $validTo = \Carbon\Carbon::parse($duty->valid_to);
                                                            @endphp
                                                            @if($now->between($validFrom, $validTo))
                                                                <span class="badge badge-success">Active</span>
                                                            @elseif($now->lt($validFrom))
                                                                <span class="badge badge-warning">Upcoming</span>
                                                            @else
                                                                <span class="badge badge-secondary">Expired</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="btn-group" role="group">
                                                                <button class="btn btn-sm btn-outline-primary edit-duty" data-id="{{ $duty->id }}">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-outline-danger delete-duty" data-id="{{ $duty->id }}">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="10" class="text-center text-muted py-4">
                                                            <i class="fas fa-tasks fa-2x mb-3"></i>
                                                            <p>No duties assigned yet</p>
                                                        </td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Duty Modal -->
    <div class="modal fade" id="assignDutyModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Assign New Duty</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="assignDutyForm" method="POST" action="{{ route('admin.duties.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="emp_assign_subject_id">Employee & Subject *</label>
                                    <select class="form-control select2" id="emp_assign_subject_id" name="emp_assign_subject_id" required>
                                        <option value="">Select Employee & Subject</option>
                                        @foreach($employeeAssignments as $assignment)
                                            <option value="{{ $assignment->id }}">
                                                {{ $assignment->employee->name }} - {{ $assignment->subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject_type">Subject Type *</label>
                                    <select class="form-control" id="subject_type" name="subject_type" required>
                                        <option value="">Select Type</option>
                                        <option value="lecture">Lecture</option>
                                        <option value="practical">Practical</option>
                                        <option value="tutorial">Tutorial</option>
                                        <option value="lab">Lab</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="section_id">Section *</label>
                                    <select class="form-control select2" id="section_id" name="section_id" required>
                                        <option value="">Select Section</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="frequency">Frequency *</label>
                                    <select class="form-control" id="frequency" name="frequency" required>
                                        <option value="">Select Frequency</option>
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="once">Once</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_time">Start Time *</label>
                                    <input type="time" class="form-control" id="start_time" name="start_time" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_time">End Time *</label>
                                    <input type="time" class="form-control" id="end_time" name="end_time" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="valid_from">Valid From *</label>
                                    <input type="date" class="form-control" id="valid_from" name="valid_from" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="valid_to">Valid To *</label>
                                    <input type="date" class="form-control" id="valid_to" name="valid_to" required>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="weeklyDays" style="display: none;">
                            <div class="col-md-12">
                                <label>Days of Week *</label>
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
                                    @endphp
                                    @foreach($days as $day)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" 
                                            id="day_{{ $day['value'] }}" 
                                            name="days_of_week[]" 
                                            value="{{ $day['value'] }}">
                                        <label class="form-check-label" for="day_{{ $day['value'] }}">
                                            {{ $day['label'] }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="row" id="monthlyDay" style="display: none;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="day_of_month">Day of Month *</label>
                                    <select class="form-control" id="day_of_month" name="day_of_month">
                                        @for($i = 1; $i <= 31; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <input type="text" class="form-control" id="location" name="location" placeholder="e.g., Room 101, Lab 2">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Assign Duty</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Time Slot Conflict Modal -->
    <div class="modal fade" id="conflictModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Time Slot Conflict</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="conflictDetails">
                        <!-- Conflict details will be shown here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="forceAssign">Assign Anyway</button>
                </div>
            </div>
        </div>
    </div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            width: '100%'
        });

        // Initialize date range picker
        flatpickr("#date_range", {
            mode: "range",
            dateFormat: "Y-m-d",
        });

        // Handle frequency change
        $('#frequency').change(function() {
            const frequency = $(this).val();
            $('#weeklyDays, #monthlyDay').hide();
            
            if (frequency === 'weekly') {
                $('#weeklyDays').show();
            } else if (frequency === 'monthly') {
                $('#monthlyDay').show();
            }
        });

        // Check for time slot conflicts
        $('#assignDutyForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = $(this).serialize();
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData + '&check_conflict=1',
                success: function(response) {
                    if (response.has_conflict) {
                        // Show conflict details
                        let conflictHtml = '<p>The selected time slot conflicts with existing duties:</p>';
                        response.conflicts.forEach(conflict => {
                            conflictHtml += `
                                <div class="alert alert-warning">
                                    <strong>${conflict.employee}</strong><br>
                                    ${conflict.subject} (${conflict.subject_type})<br>
                                    Time: ${conflict.start_time} - ${conflict.end_time}<br>
                                    Days: ${conflict.days}<br>
                                    Location: ${conflict.location}
                                </div>
                            `;
                        });
                        $('#conflictDetails').html(conflictHtml);
                        $('#conflictModal').modal('show');
                    } else {
                        // No conflict, submit form
                        $('#assignDutyForm').off('submit').submit();
                    }
                }
            });
        });

        // Force assign despite conflicts
        $('#forceAssign').click(function() {
            $('#assignDutyForm').off('submit').submit();
            $('#conflictModal').modal('hide');
        });

        // Load schedule timeline
        $('#applyFilters').click(function() {
            loadScheduleTimeline();
        });

        // Reset filters
        $('#resetFilters').click(function() {
            $('#filterForm')[0].reset();
            $('.select2').val(null).trigger('change');
            $('#scheduleTimeline').html(`
                <div class="text-center py-5">
                    <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Select filters and apply to view schedule</p>
                </div>
            `);
        });

        // Timeline view switching
        $('[data-view]').click(function() {
            $('[data-view]').removeClass('active');
            $(this).addClass('active');
            loadScheduleTimeline();
        });

        // Edit duty
        $('.edit-duty').click(function() {
            const dutyId = $(this).data('id');
            // Load duty data and show in modal
            $.ajax({
                url: `/admin/duties/${dutyId}/edit`,
                type: 'GET',
                success: function(response) {
                    // Populate form and show modal
                    // Implementation depends on your backend structure
                }
            });
        });

        // Delete duty
        $('.delete-duty').click(function() {
            const dutyId = $(this).data('id');
            if (confirm('Are you sure you want to delete this duty?')) {
                $.ajax({
                    url: `/admin/duties/${dutyId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        location.reload();
                    }
                });
            }
        });

        function loadScheduleTimeline() {
            const filters = {
                department_id: $('#department_id').val(),
                section_id: $('#section_id').val(),
                date_range: $('#date_range').val(),
                subject_type: $('#subject_type').val(),
                view: $('[data-view].active').data('view')
            };

            $.ajax({
                url: '{{ route("admin.duties.timeline") }}',
                type: 'GET',
                data: filters,
                beforeSend: function() {
                    $('#scheduleTimeline').html(`
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="mt-2">Loading schedule...</p>
                        </div>
                    `);
                },
                success: function(response) {
                    $('#scheduleTimeline').html(response.html);
                }
            });
        }

        // Set default dates
        const today = new Date();
        const nextWeek = new Date(today);
        nextWeek.setDate(nextWeek.getDate() + 7);
        
        $('#valid_from').val(today.toISOString().split('T')[0]);
        $('#valid_to').val(nextWeek.toISOString().split('T')[0]);
    });
</script>
@endsection
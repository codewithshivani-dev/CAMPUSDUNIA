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
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-between align-items-center">
                                <h4 class="mb-0"><i class="fas fa-tasks"></i> Assign Duties to Employees</h4>
                                <div>
                                    <a href="{{ route('institute.duties.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle"></i> Assign New Duty
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Action Buttons -->



                        <!-- Assigned Duties Table -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="fas fa-list-alt"></i> All Assigned Duties</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="dutiesTable">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Employee</th>
                                                        <th>Date/Time</th>
                                                        <th>Frequency</th>
                                                        <th>Location</th>
                                                        <th>Priority</th>
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
                                                                <div>
                                                                    <h6 class="mb-0">{{ $duty->employee->name ?? 'N/A' }}</h6>
                                                                    <small class="badge badge-primary">{{ $dutyTypes[$duty->duty_type] ?? $duty->duty_type }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if ($duty->frequency === 'once')
                                                                <small>
                                                                    {{ \Carbon\Carbon::parse($duty->date)->format('d/m/Y') }}<br>
                                                                    {{ \Carbon\Carbon::parse($duty->start_time)->format('h:i A') }}
                                                                    -
                                                                    {{ \Carbon\Carbon::parse($duty->end_time)->format('h:i A') }}
                                                                </small>
                                                            @else
                                                                <small>
                                                                    {{ \Carbon\Carbon::parse($duty->from_date)->format('d/m/Y') }}
                                                                    -
                                                                    {{ \Carbon\Carbon::parse($duty->to_date)->format('d/m/Y') }}<br>
                                                                    {{ \Carbon\Carbon::parse($duty->start_time)->format('h:i A') }}
                                                                    -
                                                                    {{ \Carbon\Carbon::parse($duty->end_time)->format('h:i A') }}
                                                                </small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-info">{{ ucfirst($duty->frequency) }}</span>
                                                        </td>
                                                        <td>{{ $duty->location ?? 'N/A' }}</td>
                                                        <td>
                                                            @php
                                                                $priorityColors = [
                                                                    'low' => 'success',
                                                                    'medium' => 'warning',
                                                                    'high' => 'danger',
                                                                    'urgent' => 'dark'
                                                                ];
                                                            @endphp
                                                            <span class="badge badge-{{ $priorityColors[$duty->priority] ?? 'secondary' }}">
                                                                {{ ucfirst($duty->priority) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $statusColors = [
                                                                    'pending' => 'warning',
                                                                    'assigned' => 'primary',
                                                                    'in_progress' => 'info',
                                                                    'completed' => 'success',
                                                                    'cancelled' => 'secondary'
                                                                ];
                                                            @endphp
                                                            <span class="badge badge-{{ $statusColors[$duty->status] ?? 'secondary' }}">
                                                                {{ ucfirst(str_replace('_', ' ', $duty->status)) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group" role="group">
                                                                <a href="{{ route('institute.duties.show', $duty->id) }}" class="btn btn-sm btn-outline-info" title="View">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('institute.duties.edit', $duty->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <button class="btn btn-sm btn-outline-danger delete-duty" data-id="{{ $duty->id }}" title="Delete">
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
                                                            <a href="{{ route('institute.duties.create') }}" class="btn btn-primary">
                                                                <i class="fas fa-plus-circle"></i> Assign Your First Duty
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        @if($duties->count())
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <div>
                                                    Showing {{ $duties->firstItem() }} to {{ $duties->lastItem() }} of {{ $duties->total() }} entries
                                                </div>
                                                <div>
                                                    {{ $duties->links() }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-download"></i> Export Duties</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="export_format">Format</label>
                            <select class="form-control" id="export_format" name="export_format" required>
                                <option value="excel">Excel (.xlsx)</option>
                                <option value="csv">CSV (.csv)</option>
                                <option value="pdf">PDF (.pdf)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="export_columns">Columns to Export</label>
                            <select class="form-control select2" id="export_columns" name="export_columns[]" multiple>
                                <option value="employee" selected>Employee</option>
                                <option value="duty_type" selected>Duty Type</option>
                                <option value="title" selected>Title</option>
                                <option value="date" selected>Date/Time</option>
                                <option value="location" selected>Location</option>
                                <option value="priority" selected>Priority</option>
                                <option value="status" selected>Status</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="export_date_range">Date Range (Optional)</label>
                            <input type="text" class="form-control" id="export_date_range" name="export_date_range" placeholder="Select date range">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Export</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this duty assignment?</p>
                    <p class="text-danger"><strong>This action cannot be undone.</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        flatpickr("#export_date_range", {
            mode: "range",
            dateFormat: "Y-m-d",
        });

        // Apply filters
        $('#applyFilters').click(function() {
            const formData = $('#filterForm').serialize();
            window.location.href = '{{ route("institute.duties.index") }}?' + formData;
        });

        // Reset filters
        $('#resetFilters').click(function() {
            $('#filterForm')[0].reset();
            $('.select2').val(null).trigger('change');
            window.location.href = '{{ route("institute.duties.index") }}';
        });

        // Delete duty
        $('.delete-duty').click(function() {
            const dutyId = $(this).data('id');
            $('#deleteForm').attr('action', '/admin/duties/' + dutyId);
            $('#deleteModal').modal('show');
        });

        // Auto-submit filter on change
        $('#employee_id, #duty_type, #status').change(function() {
            $('#applyFilters').click();
        });
    });
</script>
@endsection
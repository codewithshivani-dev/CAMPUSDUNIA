@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    .detail-card {
        border-left: 4px solid #007bff;
        margin-bottom: 20px;
    }
    .detail-item {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .detail-label {
        font-weight: 600;
        color: #495057;
    }
    .detail-value {
        color: #4e73df;
    }
    .status-badge {
        font-size: 0.9em;
        padding: 5px 10px;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0"><i class="fas fa-eye"></i> Duty Assignment Details</h4>
                        </div>
                        <div>
                            <a href="{{ route('institute.duties.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                            <a href="{{ route('institute.duties.edit', $duty->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Basic Information -->
                    <div class="card detail-card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <div class="detail-label">Duty Type</div>
                                        <div class="detail-value">
                                            <span class="badge badge-primary">{{ $dutyTypes[$duty->duty_type] ?? $duty->duty_type }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    @if($duty->description)
                                    <div class="detail-item">
                                        <div class="detail-label">Description</div>
                                        <div class="detail-value">{{ $duty->description }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Department Selection Section -->
                    <div class="card detail-card">
                        <div class="card-header bg-light">
                            <h5><i class="fas fa-building"></i> Department & Employee Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <div class="detail-label">DEPARTMENT</div>
                                        <div class="detail-value">
                                            <span class="badge badge-primary">{{ $departments }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <div class="detail-label">Employee</div>
                                        <div class="detail-value">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <strong>{{ $duty->employee->name }}</strong><br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Information -->
                    <div class="card detail-card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Schedule Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="detail-item">
                                        <div class="detail-label">Frequency</div>
                                        <div class="detail-value">
                                            <span class="badge badge-info">{{ ucfirst($duty->frequency) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="detail-item">
                                        <div class="detail-label">Priority</div>
                                        <div class="detail-value">
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
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="detail-item">
                                        <div class="detail-label">Status</div>
                                        <div class="detail-value">
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
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <div class="detail-label">Time</div>
                                        <div class="detail-value">
                                            {{ \Carbon\Carbon::parse($duty->start_time)->format('h:i A') }} - 
                                            {{ \Carbon\Carbon::parse($duty->end_time)->format('h:i A') }}
                                        </div>
                                    </div>
                                </div>
                                
                                @if($duty->frequency == 'once')
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <div class="detail-label">Date</div>
                                        <div class="detail-value">
                                            {{ \Carbon\Carbon::parse($duty->date)->format('d F Y') }}
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <div class="detail-label">Date Range</div>
                                        <div class="detail-value">
                                            {{ \Carbon\Carbon::parse($duty->from_date)->format('d M Y') }} - 
                                            {{ \Carbon\Carbon::parse($duty->to_date)->format('d M Y') }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            @if($duty->frequency == 'weekly' && $duty->days_of_week)
                            <div class="detail-item">
                                <div class="detail-label">Days of Week</div>
                                <div class="detail-value">
                                    @php
                                        $days = json_decode($duty->days_of_week);
                                        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                    @endphp
                                    @if(is_array($days))
                                        @foreach($days as $day)
                                            <span class="badge badge-secondary mr-1">{{ $dayNames[$day] ?? $day }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($duty->frequency == 'monthly' && $duty->day_of_month)
                            <div class="detail-item">
                                <div class="detail-label">Day of Month</div>
                                <div class="detail-value">
                                    <span class="badge badge-info">{{ $duty->day_of_month }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Location & Additional Details -->
                    <div class="card detail-card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Location & Additional Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($block)
                                <div class="col-md-4">
                                    <div class="detail-item">
                                        <div class="detail-label">Block - Building</div>
                                        <div class="detail-value">{{ $block->name }}</div>
                                    </div>
                                </div>
                                @endif

                                @if($floor)
                                <div class="col-md-4">
                                    <div class="detail-item">
                                        <div class="detail-label">Floor</div>
                                        <div class="detail-value">{{ $floor->floor_number }}</div>
                                    </div>
                                </div>
                                @endif
                                
                                @if($room)
                                <div class="col-md-4">
                                    <div class="detail-item">
                                        <div class="detail-label">Room</div>
                                        <div class="detail-value">{{ $room->room_number }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <div class="row">
                                @if($duty->instructions)
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <div class="detail-label">Special Instructions</div>
                                        <div class="detail-value">{{ $duty->instructions }}</div>
                                    </div>
                                </div>
                                @endif

                                @if($duty->required_materials)
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <div class="detail-label">Required Materials</div>
                                        <div class="detail-value">{{ $duty->required_materials }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Supervisor & Assignment Details -->
                    <div class="card detail-card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-user-tie"></i> Assignment Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($duty->supervisor)
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <div class="detail-label">Supervisor</div>
                                        <div class="detail-value">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <strong>{{ $duty->supervisor->name }}</strong><br>
                                                    <small class="text-muted">{{ $duty->supervisor->employee_id ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <div class="detail-label">Assigned By</div>
                                        <div class="detail-value">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <strong>{{ $duty->assignedBy->name }}</strong><br>
                                                    <small class="text-muted">{{ $duty->assigned_at->format('d M Y, h:i A') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($duty->completed_at)
                            <div class="detail-item">
                                <div class="detail-label">Completed At</div>
                                <div class="detail-value">
                                    {{ \Carbon\Carbon::parse($duty->completed_at)->format('d F Y, h:i A') }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ route('institute.duties.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back to List
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('institute.duties.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle"></i> Assign New Duty
                                    </a>
                                    <a href="{{ route('institute.duties.edit', $duty->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit Duty
                                    </a>
                                    <form action="{{ route('institute.duties.destroy', $duty->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this duty?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
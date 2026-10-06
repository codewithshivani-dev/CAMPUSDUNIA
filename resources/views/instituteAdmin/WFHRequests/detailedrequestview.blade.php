@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'WFH Request Details')

@section('content')
<style>
    .detail-card-modern {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.06);
        margin-bottom: 20px;
        border: none;
        transition: all 0.3s ease;
    }
    
    .detail-card-modern:hover {
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    }
    
    .detail-card-modern .detail-label {
        font-size: 12px;
        color: #636e72;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }
    
    .detail-card-modern .detail-value {
        font-size: 16px;
        color: #2d3436;
        font-weight: 500;
    }
    
    .status-badge-large-modern {
        padding: 8px 24px;
        border-radius: 25px;
        font-size: 15px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .status-badge-large-modern.pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-badge-large-modern.approved {
        background: #d4edda;
        color: #155724;
    }
    
    .status-badge-large-modern.rejected {
        background: #f8d7da;
        color: #721c24;
    }
    
    .status-badge-large-modern.completed {
        background: #cce5ff;
        color: #004085;
    }
    
    .status-badge-large-modern.cancelled {
        background: #e2e3e5;
        color: #383d41;
    }
    
    .timeline-container-modern {
        position: relative;
        padding-left: 40px;
    }
    
    .timeline-container-modern::before {
        content: '';
        position: absolute;
        left: 14px;
        top: 0;
        bottom: 0;
        width: 3px;
        background: #e9ecef;
        border-radius: 10px;
    }
    
    .timeline-item-modern {
        position: relative;
        margin-bottom: 30px;
    }
    
    .timeline-item-modern:last-child {
        margin-bottom: 0;
    }
    
    .timeline-item-modern .timeline-icon-modern {
        position: absolute;
        left: -26px;
        top: 0;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        z-index: 1;
        border: 3px solid white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .timeline-item-modern .timeline-icon-modern.primary { background: linear-gradient(135deg, #667eea, #764ba2); }
    .timeline-item-modern .timeline-icon-modern.success { background: linear-gradient(135deg, #00b894, #00cec9); }
    .timeline-item-modern .timeline-icon-modern.danger { background: linear-gradient(135deg, #fd79a8, #e17055); }
    .timeline-item-modern .timeline-icon-modern.warning { background: linear-gradient(135deg, #fdcb6e, #f39c12); }
    .timeline-item-modern .timeline-icon-modern.info { background: linear-gradient(135deg, #74b9ff, #0984e3); }
    
    .timeline-item-modern .timeline-content-modern {
        background: #f8f9fa;
        padding: 15px 20px;
        border-radius: 12px;
        margin-left: 20px;
        transition: all 0.3s ease;
    }
    
    .timeline-item-modern .timeline-content-modern:hover {
        background: #f1f2f6;
    }
    
    .timeline-item-modern .timeline-content-modern .timeline-title-modern {
        font-weight: 600;
        color: #2d3436;
        font-size: 15px;
    }
    
    .timeline-item-modern .timeline-content-modern .timeline-date-modern {
        font-size: 12px;
        color: #636e72;
        margin-top: 2px;
    }
    
    .timeline-item-modern .timeline-content-modern .timeline-desc-modern {
        color: #636e72;
        font-size: 14px;
        margin-top: 5px;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .info-item {
        padding: 12px 0;
        border-bottom: 1px solid #f1f2f6;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .quick-action-btn-modern {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
        margin-bottom: 10px;
        border: none;
    }
    
    .quick-action-btn-modern:last-child {
        margin-bottom: 0;
    }
    
    .quick-action-btn-modern.primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    
    .quick-action-btn-modern.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }
    
    .quick-action-btn-modern.secondary {
        background: #f8f9fa;
        color: #636e72;
        border: 1px solid #e9ecef;
    }
    
    .quick-action-btn-modern.secondary:hover {
        background: #e9ecef;
    }
    
    .btn-back-modern {
        padding: 10px 25px;
        border-radius: 10px;
        background: #f8f9fa;
        color: #636e72;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    .btn-back-modern:hover {
        background: #e9ecef;
        transform: translateX(-3px);
        color: #2d3436;
    }
    
    .btn-cancel-modern {
        padding: 12px 30px;
        border-radius: 10px;
        background: linear-gradient(135deg, #fd79a8, #e17055);
        color: white;
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-cancel-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(225, 112, 85, 0.4);
        color: white;
    }
    
    .alert-remark {
        background: linear-gradient(135deg, #dfe6e9, #f1f2f6);
        border: none;
        border-radius: 12px;
        padding: 15px 20px;
        color: #2d3436;
        border-left: 4px solid #667eea;
    }
    
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h4 class="mb-2">
                    <i class="fas fa-file-alt text-primary mr-2"></i> Request Details
                </h4>
                <a href="{{ route('employee.wfh-requests.trackrequest') }}" class="btn-back-modern">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Requests
                </a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Request Information -->
            <div class="detail-card-modern">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <div class="detail-label">Request ID</div>
                        <div class="detail-value" style="font-size: 18px;">
                            <i class="fas fa-hashtag text-muted mr-1"></i>
                            {{ $request->request_id }}
                        </div>
                    </div>
                    <span class="status-badge-large-modern {{ $request->request_status }}">
                        <i class="fas 
                            @if($request->request_status == 'pending') fa-clock
                            @elseif($request->request_status == 'approved') fa-check-circle
                            @elseif($request->request_status == 'rejected') fa-times-circle
                            @elseif($request->request_status == 'completed') fa-flag-checkered
                            @elseif($request->request_status == 'cancelled') fa-ban
                            @endif">
                        </i>
                        {{ ucfirst($request->request_status) }}
                    </span>
                </div>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="detail-label">Date Range</div>
                        <div class="detail-value">
                            <i class="far fa-calendar-alt text-muted mr-1"></i>
                            {{ \Carbon\Carbon::parse($request->start_date)->format('d M, Y') }}
                            <span class="mx-1">→</span>
                            {{ \Carbon\Carbon::parse($request->end_date)->format('d M, Y') }}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="detail-label">Duration</div>
                        <div class="detail-value">
                            <i class="far fa-clock text-muted mr-1"></i>
                            {{ $request->duration }}
                        </div>
                    </div>
                    @if($request->start_time && $request->end_time)
                    <div class="info-item">
                        <div class="detail-label">Working Hours</div>
                        <div class="detail-value">
                            <i class="fas fa-clock text-muted mr-1"></i>
                            {{ date('h:i A', strtotime($request->start_time)) }} - 
                            {{ date('h:i A', strtotime($request->end_time)) }}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="detail-label">Shift Used</div>
                        <div class="detail-value">
                            <i class="fas fa-briefcase text-muted mr-1"></i>
                            {{ $request->additional_data['shift_used'] ?? 'Not specified' }}
                        </div>
                    </div>
                    @endif
                    <div class="info-item">
                        <div class="detail-label">Request Date</div>
                        <div class="detail-value">
                            <i class="fas fa-calendar-plus text-muted mr-1"></i>
                            {{ $request->created_at->format('d M, Y H:i') }}
                        </div>
                    </div>
                    @if($request->processor)
                    <div class="info-item">
                        <div class="detail-label">Processed By</div>
                        <div class="detail-value">
                            <i class="fas fa-user-check text-muted mr-1"></i>
                            {{ $request->processor->name }}
                        </div>
                    </div>
                    @endif
                </div>
                
                @if($request->reason)
                <div class="mt-3">
                    <div class="detail-label">Reason</div>
                    <div class="detail-value">{{ $request->reason }}</div>
                </div>
                @endif
                
                @if($request->work_plan)
                <div class="mt-3">
                    <div class="detail-label">Work Plan</div>
                    <div class="detail-value">{{ $request->work_plan }}</div>
                </div>
                @endif
                
                @if($request->admin_remarks)
                <div class="mt-3">
                    <div class="detail-label">Admin Remarks</div>
                    <div class="alert-remark">
                        <i class="fas fa-comment mr-2"></i>
                        {{ $request->admin_remarks }}
                    </div>
                </div>
                @endif
                
                @if($request->isPending())
                <div class="mt-4">
                    <button onclick="cancelRequest({{ $request->id }})" class="btn-cancel-modern">
                        <i class="fas fa-times mr-2"></i> Cancel Request
                    </button>
                </div>
                @endif
            </div>
            
            <!-- Emergency Contact -->
            @if($request->emergency_contact)
            <div class="detail-card-modern">
                <h6 class="mb-3" style="font-weight: 600; color: #2d3436;">
                    <i class="fas fa-phone-alt text-primary mr-2"></i> Emergency Contact
                </h6>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="detail-label">Contact Person</div>
                        <div class="detail-value">
                            <i class="fas fa-user text-muted mr-1"></i>
                            {{ $request->additional_data['emergency_contact_person'] ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="detail-label">Contact Number</div>
                        <div class="detail-value">
                            <i class="fas fa-phone text-muted mr-1"></i>
                            {{ $request->emergency_contact }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-lg-4">
            <!-- Status Timeline -->
            <div class="detail-card-modern">
                <h6 class="mb-4" style="font-weight: 600; color: #2d3436;">
                    <i class="fas fa-history text-primary mr-2"></i> Request Timeline
                </h6>
                
                @if(!empty($timeline) && count($timeline) > 0)
                    <div class="timeline-container-modern">
                        @foreach($timeline as $event)
                            <div class="timeline-item-modern">
                                <div class="timeline-icon-modern {{ $event['color'] }}">
                                    <i class="{{ $event['icon'] }}"></i>
                                </div>
                                <div class="timeline-content-modern">
                                    <div class="timeline-title-modern">{{ $event['title'] }}</div>
                                    <div class="timeline-date-modern">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ $event['date']->format('d M, Y H:i') }}
                                        <span class="text-muted ml-2">
                                            ({{ $event['date']->diffForHumans() }})
                                        </span>
                                    </div>
                                    <div class="timeline-desc-modern">{{ $event['description'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No timeline events available.</p>
                    </div>
                @endif
            </div>
            
            
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
function cancelRequest(id) {
    if (confirm('Are you sure you want to cancel this request?')) {

        let url = "{{ route('employee.wfh-requests.cancel', ':id') }}";
        url = url.replace(':id', id);

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                toastr.success('Request cancelled successfully');
                setTimeout(() => location.reload(), 1000);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error cancelling request');
            }
        });
    }
}
</script>
@endsection
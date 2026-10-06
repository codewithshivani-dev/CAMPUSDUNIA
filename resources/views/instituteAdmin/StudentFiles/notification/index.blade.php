@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'Student Notifications')

@section('content')
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #10b981, #059669);
    --primary-color: #10b981;
    --secondary-color: #059669;
    --success-gradient: linear-gradient(135deg, #10b981, #059669);
    --success-color: #10b981;
    --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
    --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
    --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
}

/* Page Header */
.page-header {
    background: linear-gradient(135deg, #4361ee, #3a0ca3);;
    border-radius: 20px;
    padding: 22px 28px;
    margin-bottom: 25px;
}

.page-header h1 {
    font-size: 1.4rem;
    font-weight: 700;
    color: #fff;
    margin: 0;
}

/* Button Styles */
.btn {
    border-radius: 30px;
    font-weight: 600;
    padding: 10px 22px;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.8rem;
    /*border: none;*/
}

.btn-primary {
    background: var(--primary-gradient);
    color: white;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.btn-danger {
    background: var(--danger-gradient);
    color: white;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

/*.btn-outline-success {*/
/*    border: 2px solid var(--success-color);*/
/*    color: var(--success-color);*/
/*    background: transparent;*/
/*}*/

/*.btn-outline-success:hover {*/
/*    background: var(--success-gradient);*/
/*    border-color: transparent;*/
/*    color: white;*/
/*}*/

/* Active Filter Badge */
.active-filter-badge {
    display: none;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #eef2ff, #e0e7ff);
    color: var(--primary-color);
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 600;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.active-filter-badge i {
    cursor: pointer;
    font-size: 0.7rem;
    transition: color 0.2s;
}

.active-filter-badge i:hover {
    color: #dc2626;
}

/* Stats Cards */
.stat-card {
    background: white;
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 2px solid rgba(16, 185, 129, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.stat-card::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.stat-card.total::before { background: var(--primary-gradient); }
.stat-card.unread::before { background: var(--warning-gradient); }
.stat-card.read::before { background: var(--success-gradient); }
.stat-card.fee::before { background: var(--info-gradient); }

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.12);
}

.stat-card.active-filter {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    background: linear-gradient(135deg, #f8fafc, #eef2ff);
}

.stat-card .card-body {
    padding: 0;
}

.stat-card .h5 {
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.stat-card .h5.text-primary { color: var(--primary-color) !important; }
.stat-card .h5.text-warning { color: #f59e0b !important; }
.stat-card .h5.text-success { color: var(--success-color) !important; }
.stat-card .h5.text-info { color: #3b82f6 !important; }

.stat-card .fa-2x {
    opacity: 0.3;
}

/* Main Card */
.main-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 2px solid rgba(16, 185, 129, 0.1);
}

.main-card .card-header {
    background: linear-gradient(135deg, #f8fafc, #eef2ff);
    padding: 18px 25px;
    border-bottom: 2px solid rgba(16, 185, 129, 0.1);
}

.main-card .card-header h6 {
    color: var(--primary-color);
    font-weight: 700;
    font-size: 1rem;
    margin: 0;
}

/* Notification Items */
.notification-item {
    transition: all 0.3s;
    border-radius: 0 12px 12px 0;
    margin-bottom: 16px;
    background: white;
    padding: 10px;
    border: 2px solid rgba(16, 185, 129, 0.08);
}

.notification-item:first-child {
    margin-top: 20px;
}

.notification-item:last-child {
    margin-bottom: 20px;
}

.notification-item .fa-2x {
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    opacity: 0.7;
}

.notification-item h6 {
    /*color: var(--primary-color);*/
    font-weight: 600;
}

.notification-item .badge.bg-danger {
    background: var(--danger-gradient) !important;
    font-size: 0.65rem;
    padding: 3px 8px;
    margin-left: 8px;
}

.mark-read-btn {
    border-radius: 20px;
    font-size: 0.7rem;
    padding: 5px 12px;
    font-weight: 600;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state i.fa-bell-slash {
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    opacity: 0.5;
}

.empty-state h5 {
    color: var(--primary-color);
    font-weight: 700;
}

/* Pagination */
.pagination {
    gap: 5px;
    justify-content: center;
}

.pagination .page-link {
    border-radius: 10px;
    border: 2px solid rgba(16, 185, 129, 0.15);
    color: var(--primary-color);
    font-weight: 600;
    font-size: 0.85rem;
    padding: 8px 14px;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
    border-color: var(--primary-color);
}

.pagination .active .page-link {
    background: var(--primary-gradient);
    border-color: transparent;
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 15px;
    }
    
    .page-header .d-flex {
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .notification-item .d-flex {
        flex-direction: column;
        text-align: center;
    }
    
    .notification-item .col-md-10,
    .notification-item .col-md-2 {
        width: 100%;
        justify-content: center;
    }
    
    .stat-card {
        cursor: pointer;
    }
    
    .btn-primary {
        margin-top: 12px;
    }
}
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header d-sm-flex align-items-center justify-content-between">
        <h1>
            <i class="fas fa-bell"></i> Student Notifications
        </h1>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" id="markAllReadBtn">
                <i class="fas fa-check-double"></i> Mark All as Read
            </button>
            <button class="btn btn-danger" id="deleteAllBtn">
                <i class="fas fa-trash"></i> Delete All
            </button>
        </div>
    </div>

    <!-- Active Filter Display -->
    <div id="activeFilterDisplay" class="mb-3" style="display: none;">
        <span class="active-filter-badge">
            Filter: <strong id="activeFilterText"></strong>
            <i class="fas fa-times" onclick="clearFilter()"></i>
        </span>
    </div>

    <!-- Notification Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-4">
            <div class="stat-card total" onclick="filterNotifications('all')" id="statTotal">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="h5 text-primary">All</div>
                        </div>
                        <div class="col-auto">
                            <div class="position-relative d-inline-block">
                                <i class="fas fa-bell fa-2x"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                                    {{ $notifications->total() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="stat-card unread" onclick="filterNotifications('unread')" id="statUnread">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="h5 text-warning">Unread</div>
                        </div>
                        <div class="col-auto">
                            <div class="position-relative d-inline-block">
                                <i class="fas fa-envelope fa-2x"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                                    {{ $unreadCount }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="stat-card read" onclick="filterNotifications('read')" id="statRead">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="h5 text-success">Read</div>
                        </div>
                        <div class="col-auto">
                            <div class="position-relative d-inline-block">
                                <i class="fas fa-check-circle fa-2x"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                    {{ $readCount }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="stat-card fee" onclick="filterNotifications('fee')" id="statFee">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="h5 text-info">Fee Related</div>
                        </div>
                        <div class="col-auto">
                            <div class="position-relative d-inline-block">
                                <i class="fas fa-rupee-sign fa-2x"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info">
                                    {{ $categoryCounts['fee'] ?? 0 }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Notifications List -->
    <div class="main-card">
        <div class="card-header d-flex flex-row align-items-center justify-content-between">
            <h6 id="notificationListTitle">All Notifications</h6>
        </div>
        <div class="card-body">
            @if($notifications->count() > 0)
                <div class="list-group notification-list">
                    @foreach($notifications as $notification)
                        @php
                            $data = $notification->data;
                            $isUnread = is_null($notification->read_at);
                            
                            // Determine notification type and styling
                            $typeIcon = 'fa-bell';
                            $typeText = 'General';
                            
                            switch($data['type'] ?? '') {
                                case 'fee_due':
                                case 'fee_paid':
                                case 'fee_reminder':
                                    $typeIcon = 'fa-rupee-sign';
                                    $typeText = 'Fee';
                                    break;
                                case 'notice_created':
                                    $typeIcon = 'fa-bullhorn';
                                    $typeText = 'Notice';
                                    break;
                                case 'attendance':
                                    $typeIcon = 'fa-calendar-check';
                                    $typeText = 'Attendance';
                                    break;
                                case 'exam_schedule':
                                    $typeIcon = 'fa-file-alt';
                                    $typeText = 'Exam';
                                    break;
                                case 'result_declared':
                                    $typeIcon = 'fa-chart-line';
                                    $typeText = 'Result';
                                    break;
                                case 'holiday':
                                    $typeIcon = 'fa-umbrella-beach';
                                    $typeText = 'Holiday';
                                    break;
                                case 'event':
                                    $typeIcon = 'fa-calendar-alt';
                                    $typeText = 'Event';
                                    break;
                            }
                        @endphp
                        
                        <div class="notification-item {{ $isUnread ? 'unread' : '' }}" data-id="{{ $notification->id }}" data-read="{{ $isUnread ? 'false' : 'true' }}" data-date="{{ $notification->created_at->timestamp }}" data-status="{{ $isUnread ? 'unread' : 'read' }}" data-type="{{ $data['type'] ?? 'general' }}">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div class="col-md-10 d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas {{ $typeIcon }} fa-2x"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 {{ $isUnread ? 'font-weight-bold' : '' }}">
                                            {{ $data['title'] ?? 'Notification' }}
                                            @if($isUnread)
                                                <span class="badge rounded-pill bg-danger">New</span>
                                            @endif
                                        </h6>
                                        <p class="mb-1">{{ $data['message'] ?? '' }}</p>
                                        
                                        @if(isset($data['fee_amount']))
                                            <small class="text-muted">
                                                <i class="fas fa-rupee-sign"></i> Amount: ₹{{ number_format($data['fee_amount'], 2) }}
                                                @if(isset($data['due_date']))
                                                    | Due: {{ \Carbon\Carbon::parse($data['due_date'])->format('d M Y') }}
                                                @endif
                                            </small>
                                            <br/>
                                        @endif
                                        
                                        @if(isset($data['attendance_percentage']))
                                            <small class="text-muted">
                                                <i class="fas fa-chart-simple"></i> Attendance: {{ $data['attendance_percentage'] }}%
                                            </small>
                                            <br/>
                                        @endif
                                        
                                        @if(isset($data['exam_name']))
                                            <small class="text-muted">
                                                <i class="fas fa-file-alt"></i> {{ $data['exam_name'] }}
                                                @if(isset($data['exam_date']))
                                                    | Date: {{ \Carbon\Carbon::parse($data['exam_date'])->format('d M Y') }}
                                                @endif
                                            </small>
                                            <br/>
                                        @endif
                                        
                                        <small class="text-muted">
                                            <i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                            | {{ $notification->created_at->format('d M Y, h:i A') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    @if($isUnread)
                                        <button class="btn btn-sm btn-outline-success mt-2 mark-read-btn" data-id="{{ $notification->id }}" onclick="event.preventDefault(); event.stopPropagation();">
                                            <i class="fas fa-check"></i> Mark Read
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="mb-4">
                        <i class="fas fa-bell-slash fa-4x"></i>
                    </div>
                    <h5>No Notifications Found</h5>
                    <p class="text-muted">You're all caught up! Check back later for new notifications.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Check for active filter on page load
    const urlParams = new URLSearchParams(window.location.search);
    const activeFilter = urlParams.get('filter');
    if (activeFilter) {
        applyFilterUI(activeFilter);
    }

    // Mark as read
    $('.mark-read-btn').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var notificationId = $(this).data('id');
        markAsRead(notificationId);
    });

    // Mark all as read
    $('#markAllReadBtn').click(function() {
        if(confirm('Mark all notifications as read?')) {
            $.ajax({
                url: '{{ route("student.notifications.mark-all-read") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Error marking notifications as read');
                }
            });
        }
    });

    // Delete all notifications
    $('#deleteAllBtn').click(function() {
        if(confirm('Delete all notifications? This action cannot be undone.')) {
            $.ajax({
                url: '{{ route("student.notifications.delete-all") }}',
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Error deleting notifications');
                }
            });
        }
    });
});

// Filter notifications by clicking stat cards
function filterNotifications(filterType) {
    const currentFilter = new URLSearchParams(window.location.search).get('filter');
    
    // If clicking the same filter, clear it
    if (currentFilter === filterType) {
        window.location.href = window.location.pathname;
        return;
    }
    
    // Apply the filter
    window.location.href = window.location.pathname + '?filter=' + filterType;
}

// Clear filter
function clearFilter() {
    window.location.href = window.location.pathname;
}

// Apply filter UI
function applyFilterUI(filterType) {
    // Remove active class from all stat cards
    $('.stat-card').removeClass('active-filter');
    
    // Add active class to the selected stat card
    switch(filterType) {
        case 'all':
            $('#statTotal').addClass('active-filter');
            $('#notificationListTitle').text('All Notifications');
            $('#activeFilterText').text('All');
            break;
        case 'unread':
            $('#statUnread').addClass('active-filter');
            $('#notificationListTitle').text('Unread Notifications');
            $('#activeFilterText').text('Unread');
            break;
        case 'read':
            $('#statRead').addClass('active-filter');
            $('#notificationListTitle').text('Read Notifications');
            $('#activeFilterText').text('Read');
            break;
        case 'fee':
            $('#statFee').addClass('active-filter');
            $('#notificationListTitle').text('Fee Related Notifications');
            $('#activeFilterText').text('Fee Related');
            break;
    }
    
    // Show active filter display
    $('#activeFilterDisplay').show();
    
    // Filter notification items client-side
    filterNotificationItems(filterType);
}

// Filter notification items
function filterNotificationItems(filterType) {
    $('.notification-item').each(function() {
        const isUnread = $(this).data('status') === 'unread';
        const isRead = $(this).data('status') === 'read';
        const notificationType = $(this).data('type');
        const isFeeRelated = notificationType === 'fee_due' || notificationType === 'fee_paid' || notificationType === 'fee_reminder';
        
        switch(filterType) {
            case 'all':
                $(this).show();
                break;
            case 'unread':
                $(this).toggle(isUnread);
                break;
            case 'read':
                $(this).toggle(isRead);
                break;
            case 'fee':
                $(this).toggle(isFeeRelated);
                break;
        }
    });
}

function markAsRead(notificationId) {
    $.ajax({
        url: '{{ url("student/notifications") }}/' + notificationId + '/mark-as-read',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if(response.success) {
                location.reload();
            }
        },
        error: function(xhr) {
            alert('Error marking notification as read');
        }
    });
}
</script>
@endsection
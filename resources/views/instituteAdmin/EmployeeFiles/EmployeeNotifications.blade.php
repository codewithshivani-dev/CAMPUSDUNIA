@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('title', 'Notifications')
@section('content')
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --primary-color: #4361ee;
    --secondary-color: #3a0ca3;
    --success-gradient: linear-gradient(135deg, #10b981, #059669);
    --success-color: #10b981;
    --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
    --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
    --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
}

/* Page Header */
.page-header {
    background: var(--primary-gradient);
    border-radius: 20px;
    padding: 22px 28px;
    margin-bottom: 25px;
    
}

.page-header h1 {
    font-size: 1.4rem;
    font-weight: 700;
    color: #fff;
}

.page-header h1 i {
    background: #fff;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
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
    border: none;
}

.btn-primary {
    background: var(--primary-gradient);
    color: white;
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
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

.btn-outline-success {
    border: 2px solid var(--success-color);
    color: var(--success-color);
    background: transparent;
}

.btn-outline-success:hover {
    background: var(--success-gradient);
    border-color: transparent;
    color: white;
}

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
    border: 1px solid rgba(67, 97, 238, 0.2);
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
    border: 2px solid rgba(67, 97, 238, 0.1);
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
.stat-card.month::before { background: var(--info-gradient); }

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(67, 97, 238, 0.12);
}

.stat-card.active-filter {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    background: linear-gradient(135deg, #f8fafc, #eef2ff);
}

.stat-card.active-filter .card-body::after {
    content: '✓';
    position: absolute;
    top: 10px;
    display:none;
    right: 15px;
    font-size: 1.2rem;
    color: var(--primary-color);
    font-weight: 700;
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

.badge.rounded-pill {
    padding: 5px 10px;
    font-size: 0.7rem;
    font-weight: 700;
}

.badge.bg-primary {
    background: var(--primary-gradient) !important;
}

.badge.bg-warning {
    background: var(--warning-gradient) !important;
}

.badge.bg-success {
    background: var(--success-gradient) !important;
    color: white !important;
}

.badge.bg-info {
    background: var(--info-gradient) !important;
}

/* Main Card */
.main-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 2px solid rgba(67, 97, 238, 0.1);
}

.main-card .card-header {
    background: linear-gradient(135deg, #f8fafc, #eef2ff);
    padding: 18px 25px;
    border-bottom: 2px solid rgba(67, 97, 238, 0.1);
}

.main-card .card-header h6 {
    color: var(--primary-color);
    font-weight: 700;
    font-size: 1rem;
}

/* Notification Items */
.notification-item {
    transition: all 0.3s;
    border-left: 4px solid transparent;
    border-radius: 0 12px 12px 0;
    margin: 8px 15px;
    background: white;
    border: 2px solid rgba(67, 97, 238, 0.08);
    border-left-width: 4px;
}

.notification-item:first-child {
    margin-top: 20px;
}

.notification-item:last-child {
    margin-bottom: 20px;
}

.notification-item.unread {
    border-left-color: #f59e0b;
    background: linear-gradient(135deg, #fef9e7, #fef3c7);
}

.notification-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 20px rgba(67, 97, 238, 0.1);
    border-color: rgba(67, 97, 238, 0.2);
}

.notification-item.list-group-item-warning {
    border-left-color: #f59e0b;
}

.notification-item.list-group-item-success {
    border-left-color: var(--success-color);
}

.notification-item.list-group-item-info {
    border-left-color: #3b82f6;
}

.notification-item.list-group-item-primary {
    border-left-color: var(--primary-color);
}

.notification-item.list-group-item-danger {
    border-left-color: #ef4444;
}

.notification-item .fa-2x {
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    opacity: 0.7;
}

.notification-item h6 {
    color: var(--primary-color);
    font-weight: 600;
}

.notification-item .badge.bg-danger {
    background: var(--danger-gradient) !important;
    font-size: 0.65rem;
    padding: 3px 8px;
    margin-left: 8px;
}

.notification-item .text-muted {
    color: #94a3b8 !important;
    font-size: 0.8rem;
}

.notification-item .text-muted i {
    color: var(--primary-color);
    margin-right: 4px;
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
}

.pagination .page-link {
    border-radius: 10px;
    border: 2px solid rgba(67, 97, 238, 0.15);
    color: var(--primary-color);
    font-weight: 600;
    font-size: 0.85rem;
    padding: 8px 14px;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(58, 12, 163, 0.1));
    border-color: var(--primary-color);
}

.pagination .active .page-link {
    background: var(--primary-gradient);
    border-color: transparent;
    color: white;
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.modal-header {
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 18px 24px;
}

.modal-header .close {
    color: white;
    opacity: 0.8;
}

.modal-title {
    font-weight: 700;
}

.modal-footer {
    border-top: 1px solid rgba(67, 97, 238, 0.1);
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 8px;
}

::-webkit-scrollbar-thumb {
    background: var(--primary-gradient);
    border-radius: 8px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--secondary-color);
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
    .btn-primary
    {
        margin-top:12px;
    }
}
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header d-sm-flex align-items-center justify-content-between">
        <h1 class="mb-0">
            <i class="fas fa-bell"></i> Notifications
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

        <div class="col-md-3 mb-4 d-none">
            <div class="stat-card month" onclick="filterNotifications('month')" id="statMonth">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="h5 text-info">This Month</div>
                        </div>
                        <div class="col-auto">
                            <div class="position-relative d-inline-block">
                                <i class="fas fa-calendar fa-2x"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info">
                                    {{ $thisMonthCount }}
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
            <h6 class="m-0" id="notificationListTitle">All Notifications</h6>
            <div class="dropdown no-arrow d-none">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                    <div class="dropdown-header">Sort By:</div>
                    <a class="dropdown-item sort-option" href="#" data-sort="newest">Newest First</a>
                    <a class="dropdown-item sort-option" href="#" data-sort="oldest">Oldest First</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" id="refreshBtn">
                        <i class="fas fa-sync-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Refresh
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($notifications->count() > 0)
                <div class="list-group notification-list">
                    @foreach($notifications as $notification)
                        @php
                            $data = $notification->data;
                            $isUnread = is_null($notification->read_at);
                            
                            // Determine notification type and styling
                            $typeClass = '';
                            $typeIcon = 'fa-bell';
                            $typeText = 'General';
                            
                            switch($data['type'] ?? '') {
                                case 'notice_created':
                                    $typeClass = $isUnread ? 'list-group-item-warning' : 'list-group-item-light';
                                    $typeIcon = 'fa-bullhorn';
                                    $typeText = 'Notice';
                                    break;
                                case 'employee_onboarding':
                                    $typeClass = $isUnread ? 'list-group-item-success' : 'list-group-item-light';
                                    $typeIcon = 'fa-user-plus';
                                    $typeText = 'Employee Update';
                                    break;
                                case 'leave_application':
                                    $typeClass = $isUnread ? 'list-group-item-info' : 'list-group-item-light';
                                    $typeIcon = 'fa-calendar-check';
                                    $typeText = 'Leave';
                                    break;
                                case 'salary_update':
                                    $typeClass = $isUnread ? 'list-group-item-primary' : 'list-group-item-light';
                                    $typeIcon = 'fa-money-bill';
                                    $typeText = 'Salary';
                                    break;
                                case 'meeting':
                                    $typeClass = $isUnread ? 'list-group-item-danger' : 'list-group-item-light';
                                    $typeIcon = 'fa-users';
                                    $typeText = 'Meeting';
                                    break;
                            }
                        @endphp
                        
                        <a class="list-group-item list-group-item-action notification-item {{ $typeClass }} {{ $isUnread ? 'unread' : '' }}" data-id="{{ $notification->id }}" data-read="{{ $isUnread ? 'false' : 'true' }}" data-date="{{ $notification->created_at->timestamp }}" data-status="{{ $isUnread ? 'unread' : 'read' }}" data-month="{{ $notification->created_at->format('Y-m') }}">
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
                                        
                                        <!-- Additional Info based on type -->
                                        @if(isset($data['notice_title']))
                                            <small class="text-muted">
                                                <i class="fas fa-tag"></i> {{ $data['notice_title'] }}
                                            </small>
                                        @endif
                                        
                                        @if(isset($data['employee_name']))
                                            <small class="text-muted">
                                                <i class="fas fa-user"></i> {{ $data['employee_name'] }}
                                                @if(isset($data['employee_code']))
                                                    ({{ $data['employee_code'] }})
                                                @endif
                                            </small>
                                        @endif
                                        
                                        @if(isset($data['department_name']))
                                            <small class="text-muted">
                                                <i class="fas fa-building"></i> {{ $data['department_name'] }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <small class="text-muted">
                                        <i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                    <br/>
                                    <small class="text-muted">
                                        {{ $notification->created_at->format('d M Y, h:i A') }}
                                    </small>
                                    
                                    @if($isUnread)
                                        <button class="btn btn-sm btn-outline-success mt-2 mark-read-btn" data-id="{{ $notification->id }}" onclick="event.preventDefault(); event.stopPropagation();">
                                            <i class="fas fa-check"></i> Mark Read
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </a>
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

    <!-- Notification Settings Modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Notification Settings</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="notificationSettingsForm">
                        <div class="form-group">
                            <label>Receive notifications for:</label>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="notifyNotices" checked>
                                <label class="custom-control-label" for="notifyNotices">Notices & Announcements</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="notifyMeetings" checked>
                                <label class="custom-control-label" for="notifyMeetings">Meetings & Events</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="notifyHR" checked>
                                <label class="custom-control-label" for="notifyHR">HR Updates</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="notifySalary" checked>
                                <label class="custom-control-label" for="notifySalary">Salary & Benefits</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Email notifications:</label>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="emailImmediate" name="emailNotification" class="custom-control-input" checked>
                                <label class="custom-control-label" for="emailImmediate">Immediate</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="emailDaily" name="emailNotification" class="custom-control-input">
                                <label class="custom-control-label" for="emailDaily">Daily Digest</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="emailWeekly" name="emailNotification" class="custom-control-input">
                                <label class="custom-control-label" for="emailWeekly">Weekly Digest</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="emailNone" name="emailNotification" class="custom-control-input">
                                <label class="custom-control-label" for="emailNone">None</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveSettings">Save Settings</button>
                </div>
            </div>
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
                url: '{{ route("employee.notifications.mark-all-read") }}',
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

    // Delete single notification
    $('.delete-btn').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var notificationId = $(this).data('id');
        if(confirm('Delete this notification?')) {
            $.ajax({
                url: '{{ url("employee/notifications") }}/' + notificationId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        $(this).closest('.notification-item').fadeOut();
                        location.reload();
                    }
                }.bind(this),
                error: function(xhr) {
                    alert('Error deleting notification');
                }
            });
        }
    });

    // Delete all notifications
    $('#deleteAllBtn').click(function() {
        if(confirm('Delete all notifications? This action cannot be undone.')) {
            $.ajax({
                url: '{{ route("employee.notifications.delete-all") }}',
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

    // Sort options
    $('.sort-option').click(function(e) {
        e.preventDefault();
        var sortBy = $(this).data('sort');
        var items = $('.notification-item').get();
        
        items.sort(function(a, b) {
            var dateA = $(a).data('date');
            var dateB = $(b).data('date');
            
            if(sortBy === 'newest') {
                return dateB - dateA;
            } else {
                return dateA - dateB;
            }
        });
        
        $.each(items, function(index, item) {
            $('.notification-list').append(item);
        });
    });

    // Refresh button
    $('#refreshBtn').click(function(e) {
        e.preventDefault();
        location.reload();
    });

    // Save notification settings
    $('#saveSettings').click(function() {
        var settings = {
            notices: $('#notifyNotices').is(':checked'),
            meetings: $('#notifyMeetings').is(':checked'),
            hr: $('#notifyHR').is(':checked'),
            salary: $('#notifySalary').is(':checked'),
            emailFrequency: $('input[name="emailNotification"]:checked').attr('id')
        };
        
        $.ajax({
            url: '{{ route("employee.notifications.settings") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                settings: settings
            },
            success: function(response) {
                if(response.success) {
                    $('#settingsModal').modal('hide');
                    toastr.success('Notification settings saved successfully');
                }
            },
            error: function() {
                toastr.error('Error saving settings');
            }
        });
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
        case 'month':
            $('#statMonth').addClass('active-filter');
            $('#notificationListTitle').text('This Month\'s Notifications');
            $('#activeFilterText').text('This Month');
            break;
    }
    
    // Show active filter display
    $('#activeFilterDisplay').show();
    
    // Filter notification items client-side
    filterNotificationItems(filterType);
}

// Filter notification items
function filterNotificationItems(filterType) {
    const currentMonth = new Date().getFullYear() + '-' + String(new Date().getMonth() + 1).padStart(2, '0');
    
    $('.notification-item').each(function() {
        const isUnread = $(this).data('status') === 'unread';
        const isRead = $(this).data('status') === 'read';
        const itemMonth = $(this).data('month');
        
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
            case 'month':
                $(this).toggle(itemMonth === currentMonth);
                break;
        }
    });
}

function markAsRead(notificationId) {
    $.ajax({
        url: '{{ url("employee/notifications") }}/' + notificationId + '/mark-as-read',
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

// Real-time notification check
setInterval(function() {
    $.get('{{ route("employee.notifications.unread-count") }}', function(data) {
        if(data.count > 0) {
            $('#notificationBadge').text(data.count).show();
        } else {
            $('#notificationBadge').hide();
        }
    });
}, 30000); // Check every 30 seconds
</script>
@endsection
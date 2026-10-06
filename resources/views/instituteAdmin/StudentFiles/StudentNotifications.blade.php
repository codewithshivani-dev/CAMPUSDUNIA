@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('title', 'My Notifications')
@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-bell"></i> My Notifications
        </h1>
        <div>
            <button class="btn btn-sm btn-primary" id="markAllReadBtn">
                <i class="fas fa-check-double"></i> Mark All as Read
            </button>
            <button class="btn btn-sm btn-danger" id="deleteAllBtn">
                <i class="fas fa-trash"></i> Delete All
            </button>
        </div>
    </div>

    <!-- Notification Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Notifications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $notifications->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bell fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Unread Notifications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $unreadCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Read Notifications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $readCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                This Month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $thisMonthCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Notifications</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control" id="typeFilter">
                        <option value="all">All Types</option>
                        <option value="notice_created">Notices</option>
                        <option value="announcement">Announcements</option>
                        <option value="event">Events</option>
                        <option value="exam">Exams</option>
                        <option value="result">Results</option>
                        <option value="fee">Fee Related</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="statusFilter">
                        <option value="all">All Status</option>
                        <option value="unread">Unread</option>
                        <option value="read">Read</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="searchFilter" placeholder="Search notifications...">
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Notification History</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                    <div class="dropdown-header">Export Options:</div>
                    <a class="dropdown-item" href="#" id="exportPDF">
                        <i class="fas fa-file-pdf fa-sm fa-fw mr-2 text-gray-400"></i>
                        Export as PDF
                    </a>
                    <a class="dropdown-item" href="#" id="exportExcel">
                        <i class="fas fa-file-excel fa-sm fa-fw mr-2 text-gray-400"></i>
                        Export as Excel
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($notifications->count() > 0)
                <div class="notification-timeline">
                    @foreach($notifications as $notification)
                        @php
                            $data = $notification->data;
                            $icon = $data['icon'] ?? 'bell';
                            $color = $data['color'] ?? 'secondary';
                            $title = $data['title'] ?? 'Notification';
                            $message = $data['message'] ?? '';
                            $time = $notification->created_at;
                            $isUnread = is_null($notification->read_at);
                            
                            // Set icon and color based on notification type if not provided
                            if(!isset($data['icon'])) {
                                switch($data['type'] ?? '') {
                                    case 'notice_created':
                                        $icon = 'bullhorn';
                                        $color = 'warning';
                                        break;
                                    case 'exam_schedule':
                                        $icon = 'calendar-alt';
                                        $color = 'info';
                                        break;
                                    case 'result_declared':
                                        $icon = 'chart-line';
                                        $color = 'success';
                                        break;
                                    case 'fee_reminder':
                                        $icon = 'rupee-sign';
                                        $color = 'danger';
                                        break;
                                    default:
                                        $icon = 'bell';
                                        $color = 'primary';
                                }
                            }
                        @endphp
                        
                        <div class="notification-item {{ $isUnread ? 'unread' : '' }}" data-id="{{ $notification->id }}" data-type="{{ $data['type'] ?? 'general' }}" data-status="{{ $isUnread ? 'unread' : 'read' }}">
                            <div class="d-flex align-items-start">
                                <div class="notification-icon mr-3">
                                    <div class="icon-circle bg-{{ $color }} light">
                                        <i class="fas fa-{{ $icon }} text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-1 font-weight-bold {{ $isUnread ? 'text-dark' : 'text-secondary' }}">
                                            {{ $title }}
                                            @if($isUnread)
                                                <span class="badge badge-warning ml-2">New</span>
                                            @endif
                                        </h6>
                                        <small class="text-muted">{{ $time->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1 {{ $isUnread ? '' : 'text-muted' }}">{{ $message }}</p>
                                    
                                    <!-- Additional details based on notification type -->
                                    @if(isset($data['notice_title']))
                                        <div class="mt-2 p-2 bg-light rounded">
                                            <small><strong>Notice:</strong> {{ $data['notice_title'] }}</small>
                                        </div>
                                    @endif
                                    
                                    @if(isset($data['content']))
                                        <div class="mt-2 text-muted">
                                            <small>{{ $data['content'] }}</small>
                                        </div>
                                    @endif
                                    
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="far fa-clock"></i> {{ $time->format('d M Y, h:i A') }}
                                        </small>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="mt-3 action-buttons">
                                        @if(isset($data['action_url']))
                                            <a href="{{ $data['action_url'] }}" class="btn btn-sm btn-primary" onclick="markAsRead('{{ $notification->id }}')">
                                                <i class="fas fa-eye"></i> View Details
                                            </a>
                                        @endif
                                        
                                        @if($isUnread)
                                            <button class="btn btn-sm btn-success mark-read-btn" data-id="{{ $notification->id }}">
                                                <i class="fas fa-check"></i> Mark as Read
                                            </button>
                                        @endif
                                        
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $notification->id }}">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr class="my-3">
                        @endif
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <img src="{{ asset('images/no-notifications.svg') }}" alt="No notifications" style="max-width: 200px;" class="mb-4">
                    <h5>No Notifications</h5>
                    <p class="text-muted">You don't have any notifications at the moment.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .notification-item {
        transition: background-color 0.3s;
        padding: 15px;
        border-radius: 8px;
    }
    .notification-item.unread {
        background-color: #f8f9fc;
        border-left: 4px solid #f6c23e;
    }
    .notification-item:hover {
        background-color: #f8f9fc;
    }
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bg-primary.light { background-color: #4e73df; }
    .bg-success.light { background-color: #1cc88a; }
    .bg-info.light { background-color: #36b9cc; }
    .bg-warning.light { background-color: #f6c23e; }
    .bg-danger.light { background-color: #e74a3b; }
    .bg-secondary.light { background-color: #858796; }
    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .notification-timeline {
        max-height: 800px;
        overflow-y: auto;
        padding-right: 10px;
    }
    .notification-timeline::-webkit-scrollbar {
        width: 5px;
    }
    .notification-timeline::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .notification-timeline::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 5px;
    }
    .notification-timeline::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Mark as Read
    $('.mark-read-btn').click(function() {
        var notificationId = $(this).data('id');
        markAsRead(notificationId);
    });

    // Mark All as Read
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
                }
            });
        }
    });

    // Delete Single Notification
    $('.delete-btn').click(function() {
        var notificationId = $(this).data('id');
        if(confirm('Delete this notification?')) {
            $.ajax({
                url: '{{ url("student/notifications") }}/' + notificationId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        location.reload();
                    }
                }
            });
        }
    });

    // Delete All Notifications
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
                }
            });
        }
    });

    // Filters
    function filterNotifications() {
        var type = $('#typeFilter').val();
        var status = $('#statusFilter').val();
        var search = $('#searchFilter').val().toLowerCase();

        $('.notification-item').each(function() {
            var show = true;
            var itemType = $(this).data('type');
            var itemStatus = $(this).data('status');
            var itemText = $(this).text().toLowerCase();

            if(type !== 'all' && itemType !== type) show = false;
            if(status !== 'all' && itemStatus !== status) show = false;
            if(search && !itemText.includes(search)) show = false;

            $(this).toggle(show);
            $(this).next('hr').toggle(show);
        });
    }

    $('#typeFilter, #statusFilter, #searchFilter').on('change keyup', function() {
        filterNotifications();
    });

    // Export functionality
    $('#exportPDF').click(function(e) {
        e.preventDefault();
        window.location.href = '{{ route("student.notifications.export.pdf") }}';
    });

    $('#exportExcel').click(function(e) {
        e.preventDefault();
        window.location.href = '{{ route("student.notifications.export.excel") }}';
    });
});

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
        }
    });
}
</script>
@endpush
@endsection
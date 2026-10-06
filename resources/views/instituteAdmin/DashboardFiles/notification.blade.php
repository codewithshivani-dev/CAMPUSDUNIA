@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'My Notifications')

@section('content')
<style>
.notification-item {
    transition: all 0.3s;
    padding: 20px;
    border-radius: 10px;
}
.notification-item.unread {
    background-color: #f0f7ff;
    border-left: 4px solid #4e73df;
}
.notification-item:hover {
    background-color: #f8f9fc;
}
.notification-thumbnail-lg {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    object-fit: cover;
}
.icon-circle-lg {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    color: white;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}
.bg-purple { background: linear-gradient(135deg, #6f42c1, #9b6fe0); }
.category-card {
    cursor: pointer;
    transition: transform 0.2s;
}
.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
.notification-timeline {
    max-height: 800px;
    overflow-y: auto;
    padding-right: 15px;
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
</style>

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

    <!-- Stats Cards -->
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
                                Unread</div>
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
                                Read</div>
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

    <!-- Category Cards -->
    <div class="row mb-4">
        @if(isset($categoryCounts['notice_created']) && $categoryCounts['notice_created'] > 0)
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white shadow category-card" data-type="notice_created">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Notices</div>
                            <div class="h4 mb-0">{{ $categoryCounts['notice_created'] }}</div>
                        </div>
                        <i class="fas fa-bullhorn fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(isset($categoryCounts['gallery_upload']) && $categoryCounts['gallery_upload'] > 0)
        <div class="col-md-3 mb-3">
            <div class="card bg-purple text-white shadow category-card" data-type="gallery_upload">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Gallery</div>
                            <div class="h4 mb-0">{{ $categoryCounts['gallery_upload'] }}</div>
                        </div>
                        <i class="fas fa-images fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($userType === 'student')
            @if(isset($categoryCounts['fee']) && $categoryCounts['fee'] > 0)
            <div class="col-md-3 mb-3">
                <div class="card bg-danger text-white shadow category-card" data-type="fee">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small">Fee</div>
                                <div class="h4 mb-0">{{ $categoryCounts['fee'] }}</div>
                            </div>
                            <i class="fas fa-rupee-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(isset($categoryCounts['attendance']) && $categoryCounts['attendance'] > 0)
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white shadow category-card" data-type="attendance">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small">Attendance</div>
                                <div class="h4 mb-0">{{ $categoryCounts['attendance'] }}</div>
                            </div>
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endif

        @if($userType === 'employee')
            @if(isset($categoryCounts['leave']) && $categoryCounts['leave'] > 0)
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white shadow category-card" data-type="leave">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small">Leave</div>
                                <div class="h4 mb-0">{{ $categoryCounts['leave'] }}</div>
                            </div>
                            <i class="fas fa-calendar-minus fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(isset($categoryCounts['attendance']) && $categoryCounts['attendance'] > 0)
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white shadow category-card" data-type="attendance">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small">Attendance</div>
                                <div class="h4 mb-0">{{ $categoryCounts['attendance'] }}</div>
                            </div>
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endif

        @if($userType === 'admin')
            @if(isset($categoryCounts['hr_update']) && $categoryCounts['hr_update'] > 0)
            <div class="col-md-3 mb-3">
                <div class="card bg-dark text-white shadow category-card" data-type="hr_update">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small">HR Updates</div>
                                <div class="h4 mb-0">{{ $categoryCounts['hr_update'] }}</div>
                            </div>
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endif
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter"></i> Filter Notifications
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <select class="form-control" id="typeFilter">
                        <option value="all">All Types</option>
                        <option value="notice_created">Notices</option>
                        <option value="gallery_upload">Gallery Uploads</option>
                        @if($userType === 'student')
                            <option value="fee">Fee</option>
                            <option value="attendance">Attendance</option>
                        @endif
                        @if($userType === 'employee')
                            <option value="leave">Leave</option>
                            <option value="attendance">Attendance</option>
                        @endif
                        @if($userType === 'admin')
                            <option value="hr_update">HR Updates</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <select class="form-control" id="statusFilter">
                        <option value="all">All Status</option>
                        <option value="unread">Unread</option>
                        <option value="read">Read</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <input type="text" class="form-control" id="searchFilter" placeholder="Search notifications...">
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Notification History
            </h6>
            <div>
                
            </div>
        </div>
        <div class="card-body">
            @if($notifications->count() > 0)
                <div class="notification-timeline">
                    @foreach($notifications as $notification)
                        @php
                            $data = $notification->data;
                            $isUnread = is_null($notification->read_at);
                            $type = $data['type'] ?? 'general';
                            $icon = $data['icon'] ?? ($type === 'gallery_upload' ? 'images' : 'bell');
                            $color = $data['color'] ?? ($type === 'gallery_upload' ? 'purple' : 'secondary');
                        @endphp
                        
                        <div class="notification-item {{ $isUnread ? 'unread' : '' }}" data-id="{{ $notification->id }}" data-type="{{ $type }}" data-status="{{ $isUnread ? 'unread' : 'read' }}" data-date="{{ $notification->created_at->timestamp }}">
                            <div class="d-flex align-items-start">
                                <div class="notification-icon-wrapper me-3">
                                    @if($type === 'gallery_upload' && isset($data['thumbnail']))
                                        <img src="{{ Storage::url($data['thumbnail']) }}" alt="thumb" class="notification-thumbnail-lg">
                                    @else
                                        <div class="icon-circle-lg bg-{{ $color }} text-black">
                                            <i class="fas fa-{{ $icon }}"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="mb-1 {{ $isUnread ? 'font-weight-bold' : '' }}">
                                                {{ $data['title'] ?? 'Notification' }}
                                                @if($isUnread)
                                                    <span class="badge bg-danger ml-2">New</span>
                                                @endif
                                            </h5>
                                            <p class="mb-2">{{ $data['message'] ?? '' }}</p>
                                        </div>
                                        <small class="text-muted">{{ $notification->created_at->format('d M Y, h:i A') }}</small>
                                    </div>
                                    
                                    <!-- Additional details -->
                                    <div class="notification-details mb-2">
                                        @if($type === 'gallery_upload' && isset($data['uploaded_count']))
                                            <span class="badge bg-info mr-2">
                                                <i class="fas fa-images"></i> {{ $data['uploaded_count'] }} images
                                            </span>
                                        @endif
                                        
                                        @if(isset($data['folder_name']))
                                            <span class="badge bg-secondary mr-2">
                                                <i class="fas fa-folder"></i> {{ $data['folder_name'] }}
                                            </span>
                                        @endif
                                        
                                        @if(isset($data['department_name']))
                                            <span class="badge bg-secondary mr-2">
                                                <i class="fas fa-building"></i> {{ $data['department_name'] }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Time -->
                                    <small class="text-muted">
                                        <i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                    
                                    <!-- Actions -->
                                    <div class="mt-3">
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
                    <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                    <h5>No Notifications</h5>
                    <p class="text-muted">You don't have any notifications at the moment.</p>
                </div>
            @endif
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Mark single notification as read - WITHOUT page reload
    $(document).on('click', '.mark-read-btn', function() {
        var notificationId = $(this).data('id');

        $.ajax({
            url: '{{ url("notifications") }}/' + notificationId + '/mark-as-read',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Force full reload
                window.location.reload();
            },
            error: function() {
                alert('Failed to mark as read');
            }
        });
    });

    // Mark as read when clicking view details link
    window.markAsRead = function(notificationId) {
        $.ajax({
            url: '{{ url("notifications") }}/' + notificationId + '/mark-as-read',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    // Counts will update on next page load
                }
            }
        });
        return true; // Allow link to continue
    };

    // Mark all as read - WITHOUT page reload
    $('#markAllReadBtn').click(function() {
        var $btn = $(this);
        
        if(confirm('Mark all notifications as read?')) {
            // Show loading state
            var originalHtml = $btn.html();
            $btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
            
            $.ajax({
                url: '{{ route("notifications.mark-all-read") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        // Update all notifications in UI
                        $('.notification-item').each(function() {
                            var $item = $(this);
                            $item.removeClass('unread');
                            $item.find('.bg-danger').remove();
                            $item.find('h5').removeClass('font-weight-bold');
                            $item.find('.mark-read-btn').remove();
                        });
                        
                        showToast('success', 'All notifications marked as read');
                    }
                    $btn.html(originalHtml).prop('disabled', false);
                },
                error: function() {
                    showToast('error', 'Failed to mark all as read');
                    $btn.html(originalHtml).prop('disabled', false);
                }
            });
        }
    });

    // Delete single notification - WITHOUT page reload
    $(document).on('click', '.delete-btn', function() {
        var $btn = $(this);
        var $notificationItem = $btn.closest('.notification-item');
        var $hr = $notificationItem.next('hr');
        var notificationId = $btn.data('id');
        
        if(confirm('Delete this notification?')) {
            // Show loading state
            var originalHtml = $btn.html();
            $btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
            
            $.ajax({
                url: '{{ url("notifications") }}/' + notificationId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        // Animate and remove the notification
                        $notificationItem.fadeOut(300, function() {
                            $(this).remove();
                            if($hr.length) $hr.fadeOut(300, function() { $(this).remove(); });
                            
                            // Check if no notifications left
                            if($('.notification-item').length === 0) {
                                location.reload(); 
                            }
                        });
                        
                        showToast('success', 'Notification deleted');
                    }
                },
                error: function() {
                    showToast('error', 'Failed to delete');
                    $btn.html(originalHtml).prop('disabled', false);
                }
            });
        }
    });

    // Delete all notifications - WITH page reload (since it's a major change)
    $('#deleteAllBtn').click(function() {
        var $btn = $(this);
        
        if(confirm('Delete all notifications? This action cannot be undone.')) {
            // Show loading state
            var originalHtml = $btn.html();
            $btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
            
            $.ajax({
                url: '{{ route("notifications.delete-all") }}',
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        // Reload to show empty state properly
                        location.reload();
                    }
                },
                error: function() {
                    showToast('error', 'Failed to delete all');
                    $btn.html(originalHtml).prop('disabled', false);
                }
            });
        }
    });

    // Filter by type
    $('#typeFilter, #statusFilter, #searchFilter').on('change keyup', function() {
        filterNotifications();
    });

    // Category card click
    $('.category-card').click(function() {
        var type = $(this).data('type');
        $('#typeFilter').val(type).trigger('change');
    });

});

/**
 * Show toast notification
 */
function showToast(type, message) {
    // Check if toastr is available
    if(typeof toastr !== 'undefined') {
        toastr[type](message);
    } else {
        // Fallback alert
        alert(message);
    }
}

/**
 * Mark notification as read (called from view details link)
 */
function markAsRead(notificationId) {
    $.ajax({
        url: '{{ url("notifications") }}/' + notificationId + '/mark-as-read',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        complete: function() {
            window.location.reload();
        }
    });

    return false; // prevent instant redirect
}

/**
 * Filter notifications based on selected criteria
 */
function filterNotifications() {
    var type = $('#typeFilter').val();
    var status = $('#statusFilter').val();
    var search = $('#searchFilter').val().toLowerCase();

    var visibleCount = 0;
    
    $('.notification-item').each(function() {
        var show = true;
        var $item = $(this);
        var $hr = $item.next('hr');
        
        var itemType = $item.data('type');
        var itemStatus = $item.data('status');
        var itemText = $item.text().toLowerCase();

        if(type !== 'all' && itemType !== type) show = false;
        if(status !== 'all' && itemStatus !== status) show = false;
        if(search && !itemText.includes(search)) show = false;

        $item.toggle(show);
        if($hr.length) $hr.toggle(show);
        
        if(show) visibleCount++;
    });
    
    // Show/hide no results message
    if(visibleCount === 0) {
        if($('#noResultsMessage').length === 0) {
            $('.notification-timeline').append(
                '<div id="noResultsMessage" class="text-center py-5">' +
                '<i class="fas fa-search fa-4x text-muted mb-3"></i>' +
                '<h5>No matching notifications</h5>' +
                '<p class="text-muted">Try adjusting your filters</p>' +
                '</div>'
            );
        }
    } else {
        $('#noResultsMessage').remove();
    }
}
</script>
@endsection
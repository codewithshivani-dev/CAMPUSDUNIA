@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
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

/* Page Header Box */
.page-header-box {
    background: var(--primary-gradient);
    border-radius: 15px;
    padding: 29px 15px;
    margin-bottom: 25px;
    box-shadow: 0 5px 20px rgba(67, 97, 238, 0.15);
    border-left: 5px solid var(--primary-color);
}

.page-header-box h2 {
    background: #fff;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 700;
    margin: 0;
    display: inline-block;
}

.page-header-box i {
    margin-right: 15px;
    background: #fff;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Card Styles */
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.95);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    font-size: 0.9rem;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.12);
}

.card-body {
    padding: 18px 20px;
}

.card-header {
    background: var(--primary-gradient);
    color: white;
    font-weight: 600;
    font-size: 1rem;
    padding: 12px 20px;
    border: none;
}

.card-footer {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 12px 20px;
    border-top: 1px solid rgba(67, 97, 238, 0.1);
    font-size: 0.9rem;
}

/* Welcome Card Special Style */
.card.mb-3:first-of-type .card-body {
    background: linear-gradient(135deg, rgba(67, 97, 238, 0.03), rgba(58, 12, 163, 0.03));
}

.card.mb-3:first-of-type h5 {
    color: var(--primary-color);
    font-weight: 700;
    font-size: 1.2rem;
    margin-bottom: 10px;
}

.card.mb-3:first-of-type p {
    color: var(--secondary-color);
    font-weight: 500;
    font-size: 0.9rem;
    margin-bottom: 0;
}

.card.mb-3:first-of-type strong {
    color: var(--primary-color);
    background: linear-gradient(135deg, #4361ee15, #3a0ca315);
    padding: 3px 12px;
    border-radius: 20px;
    display: inline-block;
    font-size: 0.9rem;
}

/* Button Styles */
.btn {
    padding: 10px 28px;
    font-weight: 600;
    font-size: 0.9rem;
    border-radius: 40px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: none;
    position: relative;
    overflow: hidden;
}

.btn i {
    font-size: 0.9rem;
    margin-right: 6px;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.2);
    transition: left 0.3s ease;
}

.btn:hover::before {
    left: 100%;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
}

.btn-success {
    background: var(--success-gradient);
    box-shadow: 0 3px 10px rgba(16, 185, 129, 0.25);
}

.btn-danger {
    background: var(--danger-gradient);
    box-shadow: 0 3px 10px rgba(239, 68, 68, 0.25);
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Table Styles */
.table {
    padding: 5px;
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0 5px;
    font-size: 0.85rem;
}

/*.table thead th {*/
/*    background: var(--primary-gradient);*/
/*    color: white;*/
/*    font-weight: 600;*/
/*    text-transform: uppercase;*/
/*    font-size: 0.8rem;*/
/*    letter-spacing: 0.3px;*/
/*    padding: 12px 12px;*/
/*    border: none;*/
/*}*/

.table thead th i {
    font-size: 0.8rem;
    margin-right: 5px;
}

.table thead th:first-child {
    border-top-left-radius: 10px;
}

.table thead th:last-child {
    border-top-right-radius: 10px;
}

.table tbody tr {
    background: white;
    border-radius: 10px;
    box-shadow: 0 1px 5px rgba(0,0,0,0.03);
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    transform: scale(1.005);
    box-shadow: 0 3px 12px rgba(67, 97, 238, 0.1);
}

.table tbody td {
    padding: 12px 12px;
    vertical-align: middle;
    border: none;
    font-size: 0.85rem;
}

.table tbody td:first-child {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
}

.table tbody td:last-child {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
}

.table tbody td i {
    font-size: 0.8rem;
}

/* Latest Log Highlight */
.latest-log {
    background: linear-gradient(135deg, #d4edda, #a8e6cf) !important;
    font-weight: bold;
    border: 2px solid var(--success-color) !important;
    box-shadow: 0 0 15px rgba(16, 185, 129, 0.2) !important;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
    }
    70% {
        box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
    }
}

/* Check Type Badge */
.table tbody td:first-child {
    font-weight: 600;
    position: relative;
}

/* Footer Stats */
.card-footer strong {
    color: var(--primary-color);
    margin-right: 3px;
    font-size: 0.85rem;
}

#totalLogs, #totalHours, #status {
    font-weight: 700;
    color: var(--secondary-color);
    background: linear-gradient(135deg, #4361ee12, #3a0ca312);
    padding: 2px 10px;
    border-radius: 15px;
    display: inline-block;
    margin: 0 3px;
    font-size: 0.85rem;
}

.card-footer i {
    font-size: 0.85rem;
    margin-right: 5px;
}

/* Time Display */
small {
    color: #6c757d;
    font-size: 0.75rem;
    margin-left: 3px;
}

/* Empty State */
.table tbody td.text-center {
    padding: 30px;
    font-size: 0.9rem;
}

.table tbody td.text-center i {
    font-size: 1.5rem;
    margin-bottom: 8px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-header-box h2 {
        font-size: 1.5rem;
    }
    
    .btn {
        padding: 8px 20px;
        font-size: 0.85rem;
    }
    
    .table tbody tr {
        display: block;
        margin-bottom: 10px;
        padding: 8px;
    }
    
    .table tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px;
        text-align: right;
        border-radius: 0 !important;
        font-size: 0.8rem;
    }
    
    .table tbody td::before {
        content: attr(data-label);
        font-weight: bold;
        color: var(--primary-color);
        margin-right: 8px;
        font-size: 0.8rem;
    }
    
    .card-footer {
        text-align: center;
    }
    
    .card-footer span {
        display: block;
        margin: 5px 0;
    }
}

/* Loading Animation */
.loading {
    position: relative;
    overflow: hidden;
}

.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 200%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        left: -100%;
    }
    100% {
        left: 100%;
    }
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

        /* ERP Table Styles */
        .erp-table {
            width: 100%;
            background: #fff;
            border-collapse: collapse;
        }
        
        .erp-table thead {
            background: var(--primary-gradient);
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .erp-table th {
            padding: 15px 8px;
            font-weight: 600;
            color: white;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            cursor: pointer;
            user-select: none;
            transition: background-color 0.2s;
            position: relative;
        }

        .erp-table th h6 {
            color: rgba(255,255,255,0.9);
            font-size: 11px;
            margin: 2px 0 0;
        }

        .erp-table th:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .erp-table th.sortable {
            padding-right: 30px;
        }

        .sort-icons {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sort-icon {
            color: rgba(255,255,255,0.5);
            font-size: 12px;
            line-height: 1;
        }

        .sort-icon.active {
            color: white;
        }

        .erp-table td {
            padding: 15px 8px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 14px;
            vertical-align: middle;
        }

        .erp-table tbody tr {
            transition: background-color 0.2s, transform 0.2s;
        }

        .erp-table tbody tr:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .erp-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .table-responsive{
            overflow-x: hidden;
        }
</style>

<div class="container-fluid">
    <!-- Page Header Box -->
    <div class="page-header-box">
        <h2><i class="fas fa-user-clock"></i>Mark Attendance</h2>
    </div>

    {{-- Show employee basic info --}}
    <div class="card mb-3">
        <div class="card-body">
            <h5>
                <i class="fas fa-user-circle" style="color: var(--primary-color); margin-right: 8px;"></i>
                Welcome, {{ Auth::user()->name }}
            </h5>
            <p>
                <i class="far fa-calendar-alt" style="color: var(--primary-color); margin-right: 8px;"></i>
                Date: <strong>{{ \Carbon\Carbon::now('Asia/Kolkata')->format('d-m-Y') }} (IST)</strong>
            </p>
        </div>
    </div>

    {{-- Buttons --}}
    <div class="mb-3 d-flex justify-content-between">
        <button id="btnCheckIn" class="btn btn-success">
            <i class="fas fa-sign-in-alt"></i>
            Check In
        </button>
        <button id="btnCheckOut" class="btn btn-danger">
            <i class="fas fa-sign-out-alt"></i>
            Check Out
        </button>
    </div>

    {{-- Logs --}}
    <div class="card mb-3">
        <div class="card-header">
            <i class="fas fa-history" style="margin-right: 8px;"></i>
            Today's Attendance Logs
        </div>
        <div class="card-body">
            <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                <table class="erp-table table table-bordered" id="attendanceTable">
                    <thead>
                        <tr>
                            <th class="sticky-main-2 sortable"><i class="fas fa-tag"></i> Check Type</th>
                            <th class="sortable"><i class="far fa-clock"></i> Time (IST)</th>
                            <th class="sortable"><i class="fas fa-network-wired"></i> IP Address</th>
                            <th class="sortable"><i class="fas fa-laptop"></i> Device</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($attendance && $attendance->logs && $attendance->logs->count() > 0)
                            @foreach($attendance->logs->unique('id') as $log)
                                <tr class="{{ $loop->last ? 'latest-log' : '' }}">
                                    <td class="sticky-main-2">
                                        @if($log->check_type == 'IN')
                                            <i class="fas fa-sign-in-alt" style="color: var(--success-color); margin-right: 5px;"></i>
                                        @else
                                            <i class="fas fa-sign-out-alt" style="color: #dc2626; margin-right: 5px;"></i>
                                        @endif
                                        {{ $log->check_type }}
                                    </td>
                                    <td>
                                        <i class="far fa-clock" style="color: var(--primary-color); margin-right: 5px;"></i>
                                        {{ \Carbon\Carbon::parse($log->check_time, 'UTC')->setTimezone('Asia/Kolkata')->format('d-m-Y H:i:s') }} 
                                        <small>IST</small>
                                    </td>
                                    <td>
                                        <i class="fas fa-globe" style="color: var(--primary-color); margin-right: 5px;"></i>
                                        {{ $log->ip_address }}
                                    </td>
                                    <td>
                                        <i class="fas fa-mobile-alt" style="color: var(--primary-color); margin-right: 5px;"></i>
                                        {{ $log->device_info }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center">
                                    <i class="fas fa-info-circle" style="color: var(--primary-color);"></i>
                                    <br>
                                    <span style="color: #6c757d;">No logs for today</span>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <!-- Floating Horizontal Scrollbar -->
            <div class="table-scroll-top" id="tableScrollTop">
                <div class="table-scroll-inner"></div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-around flex-wrap">
                <span>
                    <i class="fas fa-list-ol" style="color: var(--primary-color);"></i>
                    <strong>Total Logs:</strong> 
                    <span id="totalLogs">{{ $attendance ? $attendance->logs->count() : 0 }}</span>
                </span>
                <span>
                    <i class="fas fa-hourglass-half" style="color: var(--primary-color);"></i>
                    <strong>Total Hours:</strong> 
                    <span id="totalHours">{{ $attendance ? $attendance->total_hours : 0 }}</span> hrs
                </span>
                <span>
                    <i class="fas fa-check-circle" style="color: var(--primary-color);"></i>
                    <strong>Status:</strong> 
                    <span id="status">{{ $attendance ? $attendance->status : 'N/A' }}</span>
                </span>
            </div>
        </div>
    </div>
</div>

{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<script>
const employeeId = "{{ $employee->employee_id ?? '' }}";
console.log(employeeId);
let liveSeconds = 0;

$(document).ready(function() {
    setButtonState();
    startLiveTimer();
    resetLiveTimer();

    // Check-in
    $('#btnCheckIn').click(function() {
        $(this).addClass('loading');
        $.ajax({
            url: "{{ route('attendance.checkin') }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}", employee_id: employeeId },
            success: function(res) {
                const time = new Date().toLocaleTimeString('en-IN', { hour12: false, timeZone: 'Asia/Kolkata' });
                alert(`${res.success} at ${time} IST`);
                updateLogs();
            },
            error: function(err) {
                alert(err.responseJSON.error);
            },
            complete: function() {
                $('#btnCheckIn').removeClass('loading');
            }
        });
    });

    // Check-out
    $('#btnCheckOut').click(function() {
        $(this).addClass('loading');
        $.ajax({
            url: "{{ route('attendance.checkout') }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}", employee_id: employeeId },
            success: function(res) {
                const time = new Date().toLocaleTimeString('en-IN', { hour12: false, timeZone: 'Asia/Kolkata' });
                alert(`Checked out at ${time} IST | Total Hours: ${res.total_hours} | Status: ${res.status}`);
                updateLogs();
            },
            error: function(err) {
                alert(err.responseJSON.error);
            },
            complete: function() {
                $('#btnCheckOut').removeClass('loading');
            }
        });
    });

    // Auto-refresh every 60 seconds
    setInterval(updateLogs, 60000);
});

function updateLogs() {
    $('#attendanceTable').addClass('loading');
    $.get("{{ route('attendance.show') }}", function(html) {
        const newLogs = $(html).find('#attendanceTable tbody').html();
        $('#attendanceTable tbody').html(newLogs);

        $('#attendanceTable tbody tr').removeClass('latest-log');
        $('#attendanceTable tbody tr:last').addClass('latest-log');

        $('#totalLogs').text($(html).find('#totalLogs').text());
        $('#totalHours').text($(html).find('#totalHours').text());
        $('#status').text($(html).find('#status').text());

        setButtonState();
        resetLiveTimer();
        $('#attendanceTable').removeClass('loading');
    });
}

function setButtonState() {
    $.get("{{ route('attendance.show') }}", function(html) {
        const logs = $(html).find('#attendanceTable tbody tr');
        const hasIn = logs.filter(function() { return $(this).find('td:first').text().trim() === 'IN'; }).length > 0;
        const hasOut = logs.filter(function() { return $(this).find('td:first').text().trim() === 'OUT'; }).length > 0;

        $('#btnCheckIn').prop('disabled', hasIn);
        $('#btnCheckOut').prop('disabled', !hasIn || hasOut);
    });
}

function startLiveTimer() {
    setInterval(() => {
        if ($('#btnCheckOut').prop('disabled') === false) { // checked in
            liveSeconds++;
            const hours = Math.floor(liveSeconds / 3600);
            const minutes = Math.floor((liveSeconds % 3600) / 60);
            const seconds = liveSeconds % 60;
            $('#totalHours').text((hours + minutes/60 + seconds/3600).toFixed(2));
        }
    }, 1000);
}

function resetLiveTimer() {
    const lastInRow = $('#attendanceTable tbody tr').filter(function() {
        return $(this).find('td:first').text().trim() === 'IN';
    }).last();

    if (lastInRow.length === 0) {
        liveSeconds = 0;
        return;
    }

    // Extract only the time portion safely (ignores "IST" or date)
    const lastInTimeText = lastInRow.find('td:nth-child(2)').text().trim().split(' ')[1] || lastInRow.find('td:nth-child(2)').text().trim();
    if (!lastInTimeText.match(/^\d{2}:\d{2}:\d{2}$/)) {
        liveSeconds = 0;
        return;
    }

    const [h, m, s] = lastInTimeText.split(':').map(Number);

    // Get current IST time
    const now = new Date(new Date().toLocaleString('en-US', { timeZone: 'Asia/Kolkata' }));

    // Set IN time also in IST
    const inDate = new Date(now);
    inDate.setHours(h, m, s, 0);

    // Calculate seconds difference
    liveSeconds = Math.floor((now - inDate) / 1000);

    if (isNaN(liveSeconds)) liveSeconds = 0; // safety fallback
}
</script>
@endsection
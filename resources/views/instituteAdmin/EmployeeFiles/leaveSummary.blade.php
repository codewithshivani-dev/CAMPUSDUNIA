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
        --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
        --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        --hover-shadow: 0 15px 40px rgba(67, 97, 238, 0.12);
    }
    
    body {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }
    
    .container-fluid {
        animation: fadeIn 0.5s ease;
        padding: 20px;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Employee Info Card */
    .employee-info-card {
        background: var(--primary-gradient);
        color: white;
        border-radius: 20px;
        padding: 25px;
        position: relative;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .employee-info-card::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
    }
    
    .employee-info-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--hover-shadow);
    }
    
    .employee-info-card h4 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 8px;
    }
    
    /* Session Card */
    .session-card {
        background: white;
        border-radius: 20px;
        border: none;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .session-card:hover {
        box-shadow: var(--hover-shadow);
        transform: translateY(-2px);
    }
    
    .session-card .card-body {
        padding: 25px;
    }
    
    .session-badge {
        background: var(--primary-gradient);
        color: white;
        padding: 8px 16px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    /* Stats Cards */
    .stat-card {
        transition: all 0.3s ease;
        border-radius: 20px;
        background: white;
        border: none;
        box-shadow: var(--card-shadow);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--primary-gradient);
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--hover-shadow);
    }
    
    .stat-card .card-body {
        padding: 20px;
    }
    
    .stat-icon {
        width: 55px;
        height: 55px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    
    .stat-icon i {
        font-size: 1.5rem;
    }
    
    .stat-icon.success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #059669;
    }
    
    .stat-icon.warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #d97706;
    }
    
    .stat-icon.info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #2563eb;
    }
    
    .stat-icon.primary {
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: #4f46e5;
    }
    
    .stat-value {
        font-size: 1.8rem;
        font-weight: 800;
        margin-bottom: 2px;
    }
    
    .stat-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
    }
    
    .stat-sub {
        font-size: 0.7rem;
        color: #94a3b8;
        margin-top: 5px;
    }
    
    /* Progress Bar */
    .progress-custom {
        height: 8px;
        border-radius: 10px;
        background: #e2e8f0;
        overflow: hidden;
        margin-top: 12px;
    }
    
    .progress-bar-custom {
        height: 100%;
        border-radius: 10px;
        transition: width 0.6s ease;
    }
    
    .progress-bar-custom.success {
        background: var(--success-gradient);
    }
    
    .progress-bar-custom.warning {
        background: var(--warning-gradient);
    }
    
    .progress-bar-custom.danger {
        background: var(--danger-gradient);
    }
    
    /* Main Card */
    .main-card {
        background: white;
        border-radius: 20px;
        border: none;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .main-card:hover {
        box-shadow: var(--hover-shadow);
    }
    
    .main-card .card-header {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border-bottom: 2px solid #e2e8f0;
        padding: 20px 25px;
    }
    
    .main-card .card-header h5 {
        font-weight: 700;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 5px;
    }
    
    .main-card .card-header p {
        margin-bottom: 0;
        font-size: 0.8rem;
    }
    
    /* Button Styling */
    .btn-apply-leave {
        background: var(--primary-gradient);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    
    .btn-apply-leave:hover {
        transform: translateY(-2px);
        box-shadow: var(--accent-glow);
        color: white;
    }
    
    /* Table Styling */
    .table-custom {
        margin-bottom: 0;
    }
    
    .table-custom thead th {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 15px 20px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .table-custom tbody td {
        padding: 16px 20px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .table-custom tbody tr {
        transition: all 0.3s ease;
    }
    
    .table-custom tbody tr:hover {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
    }
    
    /* Leave Type Icon */
    .leave-type-icon {
        width: 45px;
        height: 45px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    
    .leave-type-icon i {
        font-size: 1.2rem;
    }
    
    .leave-type-icon.bg-danger-subtle {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
    }
    
    .leave-type-icon.bg-info-subtle {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    }
    
    .leave-type-icon.bg-success-subtle {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    }
    
    .leave-type-icon.bg-pink-subtle {
        background: linear-gradient(135deg, #fce7f3, #fbcfe8);
    }
    
    .leave-type-icon.bg-warning-subtle {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
    }
    
    .leave-type-icon.bg-secondary-subtle {
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
    }
    
    .text-danger {
        color: #dc2626 !important;
    }
    
    .text-info {
        color: #2563eb !important;
    }
    
    .text-success {
        color: #059669 !important;
    }
    
    .text-pink {
        color: #db2777 !important;
    }
    
    .text-warning {
        color: #d97706 !important;
    }
    
    .text-secondary {
        color: #475569 !important;
    }
    
    /* Badge Styling */
    .badge-leave {
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .badge-leave.bg-success {
        background: var(--success-gradient) !important;
    }
    
    .badge-leave.bg-warning {
        background: var(--warning-gradient) !important;
    }
    
    .badge-leave.bg-danger {
        background: var(--danger-gradient) !important;
    }
    
    /* Info Card */
    .info-card {
        background: white;
        border-radius: 20px;
        border: none;
        box-shadow: var(--card-shadow);
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        box-shadow: var(--hover-shadow);
        transform: translateY(-2px);
    }
    
    .info-card .card-header {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border-bottom: 2px solid #e2e8f0;
        padding: 18px 22px;
    }
    
    .info-card .card-header h6 {
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .info-card .card-body {
        padding: 22px;
    }
    
    .policy-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .policy-list li {
        margin-bottom: 12px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 0.85rem;
        color: #334155;
    }
    
    .policy-list li i {
        margin-top: 2px;
        font-size: 0.9rem;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
    }
    
    .empty-state i {
        font-size: 3.5rem;
        color: #cbd5e1;
        margin-bottom: 15px;
    }
    
    .empty-state h5 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
    }
    
    .empty-state p {
        font-size: 0.85rem;
        color: #94a3b8;
    }
    
    /* Alert Styling */
    .alert-info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border: none;
        color: #1e40af;
        border-radius: 12px;
        padding: 12px 16px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 15px;
        }
        
        .employee-info-card {
            margin-bottom: 15px;
        }
        
        .stat-card {
            margin-bottom: 15px;
        }
        
        .table-custom thead th,
        .table-custom tbody td {
            padding: 10px 12px;
        }
        
        .leave-type-icon {
            width: 35px;
            height: 35px;
            margin-right: 10px;
        }
        
        .leave-type-icon i {
            font-size: 0.9rem;
        }
        
        .stat-value {
            font-size: 1.3rem;
        }
        
        .btn-apply-leave {
            width: 100%;
            justify-content: center;
            margin-top: 10px;
        }
        
        .main-card .card-header {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header with Employee Info -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="employee-info-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1">
                            <i class="bi bi-person-badge me-2"></i>
                            {{ $employee->name ?? 'Employee' }}
                        </h4>
                        <p class="mb-0 opacity-75">
                            <i class="bi bi-person-vcard me-2" style="font-size:1.2rem;"></i> {{ $employee->employee_id ?? 'N/A' }}
                            @if($employee->department)
                            <span class="ms-3">
                                <i class="bi bi-building me-1"></i> {{ $employee->department->department ?? 'N/A' }}
                            </span>
                            @endif
                        </p>
                    </div>
                    @if($isAdmin)
                    <span class="badge bg-light text-dark">
                        <i class="bi bi-shield-check me-1"></i> Admin View
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="session-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">Session</h5>
                            <!--<p class="text-muted mb-0">Select academic session to view leave data</p>-->
                        </div>
                        <span class="session-badge">
                            <i class="bi bi-calendar-week me-1"></i> {{ $sessionYear }}
                        </span>
                    </div>

                    @if($availableSessions->count() > 1)
                    <form method="GET" action="{{ request()->url() }}" class="mt-2">
                        <div class="input-group">
                            <select name="session_year" class="form-control" onchange="this.form.submit()">
                                @foreach($availableSessions as $session)
                                <option value="{{ $session }}" {{ $session == $sessionYear ? 'selected' : '' }}>
                                    Academic Session {{ $session }}
                                </option>
                                @endforeach
                            </select>
                            @if($employeeId && $isAdmin)
                            <input type="hidden" name="employee_id" value="{{ $employeeId }}">
                            @endif
                        </div>
                    </form>
                    @else
                    <div class="d-none alert alert-info mt-3 mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Only one session available ({{ $sessionYear }})
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon success">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $totalAllocatedLeaves ?? 0 }}</div>
                            <div class="stat-label">Total Allocated</div>
                        </div>
                    </div>
                    <div class="stat-sub">Leaves assigned for session</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon warning">
                            <i class="bi bi-calendar-minus-fill"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $totalUsedLeaves ?? 0 }}</div>
                            <div class="stat-label">Total Used</div>
                        </div>
                    </div>
                    <div class="stat-sub">Leaves consumed</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon info">
                            <i class="bi bi-calendar-plus-fill"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $totalRemainingLeaves ?? 0 }}</div>
                            <div class="stat-label">Total Remaining</div>
                        </div>
                    </div>
                    <div class="stat-sub">Available balance</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon primary">
                            <i class="bi bi-percent"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $usagePercentage ?? 0 }}%</div>
                            <div class="stat-label">Usage Percentage</div>
                        </div>
                    </div>
                    <div class="progress-custom">
                        <div class="progress-bar-custom {{ ($usagePercentage ?? 0) > 80 ? 'danger' : (($usagePercentage ?? 0) > 50 ? 'warning' : 'success') }}"
                            style="width: {{ min($usagePercentage ?? 0, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Details Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="main-card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="fw-semibold mb-0">
                            <i class="bi bi-card-checklist me-2"></i>Leave Breakdown
                        </h5>
                        <p class="text-muted small mb-0">Complete details of each leave type for session
                            {{ $sessionYear }}</p>
                    </div>
                    @if(!$isAdmin && $totalRemainingLeaves > 0)
                    <a href="{{ route('leaves.apply.form') }}" class="btn-apply-leave">
                        <i class="bi bi-plus-circle me-1"></i>Apply New Leave
                    </a>
                    @endif
                </div>
                <div class="card-body p-0">
                    @if($leaveBalances->count())
                    <div class="table-responsive">
                        <table class="table-custom table">
                            <thead>
                                <tr>
                                    <th class="ps-4">Leave Type</th>
                                    <th class="text-center">Allocated</th>
                                    <th class="text-center">Used</th>
                                    <th class="text-center">Remaining</th>
                                    <th class="text-center">Usage %</th>
                                    <th class="text-center">Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaveBalances as $balance)
                                @php
                                $usagePercentage = $balance->total_allocated > 0
                                ? round(($balance->used / $balance->total_allocated) * 100, 1)
                                : 0;
                                $progressColor = $usagePercentage > 80 ? 'danger' : ($usagePercentage > 50 ?
                                'warning' : 'success');

                                // Determine icon and color based on leave type
                                if($balance->leave_type == 'Sick') {
                                $icon = 'bi-thermometer-half'; $color = 'danger';
                                } elseif($balance->leave_type == 'Casual') {
                                $icon = 'bi-cup-straw'; $color = 'info';
                                } elseif($balance->leave_type == 'Earned') {
                                $icon = 'bi-award'; $color = 'success';
                                } elseif($balance->leave_type == 'Maternity') {
                                $icon = 'bi-gender-female'; $color = 'pink';
                                } elseif($balance->leave_type == 'half_days') {
                                $icon = 'bi-clock-history'; $color = 'warning';
                                } elseif($balance->leave_type == 'short_leave') {
                                $icon = 'bi-clock'; $color = 'secondary';
                                } else {
                                $icon = 'bi-calendar'; $color = 'secondary';
                                }
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="leave-type-icon bg-{{ $color }}-subtle">
                                                <i class="{{ $icon }} text-{{ $color }}"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-semibold mb-0">
                                                    {{ ucfirst(str_replace('_', ' ', $balance->leave_type)) }} Leave
                                                </h6>
                                                <small
                                                    class="text-muted">{{ $balance->description ?? 'Annual leave allocation' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold">{{ $balance->total_allocated }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="fw-bold {{ $balance->used > 0 ? 'text-warning' : 'text-muted' }}">{{ $balance->used }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($balance->remaining == 0)
                                        <span class="badge-leave bg-danger">{{ $balance->remaining }}</span>
                                        @elseif($balance->remaining < 2) 
                                            <span class="badge-leave bg-warning">{{ $balance->remaining }}</span>
                                        @else
                                            <span class="fw-bold text-success">{{ $balance->remaining }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="fw-bold {{ $usagePercentage > 80 ? 'text-danger' : ($usagePercentage > 50 ? 'text-warning' : 'text-success') }}">
                                            {{ $usagePercentage }}%
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="progress-custom" style="width: 150px; margin: 0 auto;">
                                            <div class="progress-bar-custom {{ $progressColor }}" role="progressbar"
                                                style="width: {{ min($usagePercentage, 100) }}%"
                                                aria-valuenow="{{ $usagePercentage }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="bi bi-calendar-x"></i>
                        <h5>No Leave Balance Found</h5>
                        <p>No leave allocations found for session {{ $sessionYear }}.</p>
                        @if($availableSessions->count() > 0)
                        <a href="?session_year={{ $availableSessions->first() }}" class="btn-apply-leave mt-2">
                            <i class="bi bi-calendar2-week me-1"></i>View Other Sessions
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="row">
        <div class="col-md-6">
            <div class="info-card">
                <div class="card-header">
                    <h6>
                        <i class="bi bi-info-circle me-2"></i>Leave Policy Highlights
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="policy-list">
                        <li>
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <strong>Sick Leave:</strong> Requires medical certificate if more than 2 days
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <strong>Casual Leave:</strong> Advance notice of at least 2 days required
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <strong>Earned Leave:</strong> Can be encashed at the end of the year
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill text-success"></i>
                            Leave applications require approval from department head
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill text-success"></i>
                            Unused leaves may be carried forward as per policy
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
</script>
@endsection
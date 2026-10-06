@extends('instituteAdmin.Payroll.PayrollManagement')
@section('payroll-content')

<title>Payroll Policies</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    /* ── base styles ── */
    .page-header-modern {
        background: white;
        border-radius: 28px;
        padding: 20px 28px;
        margin-bottom: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 14px rgba(0,0,0,0.02), 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #eef2f9;
        flex-wrap: wrap;
        gap: 16px;
    }
    .page-title-modern {
        font-size: 1.75rem;
        font-weight: 700;
        background: linear-gradient(135deg, #1e293b, #2d3a4e);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin: 0;
    }
    .page-title-modern i { background: none; -webkit-background-clip: unset; color: #4f46e5; margin-right: 12px; }
    .btn-primary-round {
        border-radius: 40px;
        padding: 10px 24px;
        font-weight: 600;
        background: #4f46e5;
        color: white;
        border: none;
        box-shadow: 0 2px 6px rgba(79,70,229,0.2);
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .btn-primary-round:hover { background: #4338ca; color: white; transform: translateY(-1px); text-decoration: none; }
    .btn-outline-secondary {
        border: 1px solid #e2e8f0;
        background: white;
        color: #334155;
    }
    .btn-outline-secondary:hover { background: #f8fafc; border-color: #cbd5e1; }

    .filter-glass-card {
        background: white;
        border-radius: 24px;
        padding: 18px 24px;
        margin-bottom: 28px;
        border: 1px solid #eef2f9;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .filter-row-flex {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: flex-end;
    }
    .filter-group {
        flex: 1 1 200px;
    }
    .filter-group label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #5b6e8c;
        margin-bottom: 6px;
        display: block;
    }
    .form-control-sm-custom {
        height: 42px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        background: #fefefe;
        width: 100%;
        padding: 0 14px;
        font-size: 0.9rem;
    }
    .form-control-sm-custom:focus {
        border-color: #4f46e5;
        outline: none;
        box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
    }

    /* ── STAT CARDS ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }
    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 18px 22px;
        border: 1px solid #eef2f9;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        transition: all 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    }
    .stat-card .stat-icon {
        font-size: 1.4rem;
        color: #4f46e5;
        margin-bottom: 8px;
    }
    .stat-card .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
    }
    .stat-card .stat-label {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .stat-card .stat-sub {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 4px;
    }
    .stat-card.green .stat-icon { color: #10b981; }
    .stat-card.amber .stat-icon { color: #f59e0b; }
    .stat-card.rose .stat-icon { color: #ef4444; }
    .stat-card.blue .stat-icon { color: #3b82f6; }
    .stat-card.purple .stat-icon { color: #8b5cf6; }

    /* ── POLICY TABLE ── */
    .policy-table-wrap {
        overflow-x: auto;
        padding: 0 4px 4px 4px;
    }
    .policy-table-wrap table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
        /* min-width: 1200px; */
    }
    .policy-table-wrap th {
        background: #f8fafc;
        color: #1e293b;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 14px 16px;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .policy-table-wrap td {
        padding: 12px 16px;
        border-bottom: 1px solid #edf2f7;
        vertical-align: middle;
        color: #1e293b;
    }
    .policy-table-wrap tr:last-child td {
        border-bottom: none;
    }
    .policy-table-wrap tr:hover td {
        background: #fafcff;
    }

    .policy-table-wrap tr.inactive-policy td {
        background: #f8fafc;
        opacity: 0.75;
    }
    .policy-table-wrap tr.inactive-policy:hover td {
        background: #f1f5f9;
    }

    .sno-column {
        text-align: center;
        font-weight: 600;
        color: #64748b;
        width: 60px;
        min-width: 60px;
    }

    .emp-name-cell {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 4px;
    }
    .emp-name-cell .name {
        font-weight: 500;
    }
    .emp-name-cell .code {
        font-size: 0.7rem;
        color: #64748b;
        margin-left: 4px;
    }

    .badge-dept {
        background: #f1f5f9;
        border-radius: 40px;
        padding: 4px 12px;
        font-size: 0.7rem;
        font-weight: 600;
        color: #334155;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .policy-badge-sm {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .policy-active { background: #dcfce7; color: #15803d; }
    .policy-inactive { background: #fee2e2; color: #991b1b; }
    .policy-draft { background: #fff3e3; color: #b45309; }
    .policy-none { background: #f1f3f6; color: #4b5565; }
    .policy-type-dept { background: #dbeafe; color: #1e40af; }
    .policy-type-emp { background: #e0e7ff; color: #3730a3; }

    .action-btn-group {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    .btn-sm-pill {
        border-radius: 40px;
        padding: 4px 12px;
        font-size: 0.7rem;
        font-weight: 500;
        border: 1px solid transparent;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s;
    }
    .btn-outline-primary {
        border-color: #c7d2fe;
        color: #4338ca;
        background: transparent;
    }
    .btn-outline-primary:hover {
        background: #eef2ff;
        border-color: #818cf8;
        text-decoration: none;
    }
    .btn-outline-warning {
        border-color: #fde68a;
        color: #92400e;
        background: transparent;
    }
    .btn-outline-warning:hover {
        background: #fef3c7;
        border-color: #f59e0b;
        text-decoration: none;
    }
    .btn-outline-success {
        border-color: #86efac;
        color: #166534;
        background: transparent;
    }
    .btn-outline-success:hover {
        background: #dcfce7;
        border-color: #4ade80;
        text-decoration: none;
    }
    .btn-outline-info {
        border-color: #b7d4fd;
        color: #1e4b8a;
        background: transparent;
    }
    .btn-outline-info:hover {
        background: #dbeafe;
        border-color: #60a5fa;
        text-decoration: none;
    }

    .empty-state {
        text-align: center;
        padding: 40px 16px;
        color: #6c757d;
        background: white;
        border-radius: 28px;
        border: 1px solid #edf2f7;
    }
    .selected-year-badge {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 40px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    /* ── pagination ── */
    .pagination-wrapper {
        margin-top: 20px;
        padding: 12px 24px 20px 24px;
        display: flex;
        justify-content: center;
    }
    .pagination-wrapper .pagination {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        margin: 0;
    }
    .pagination-wrapper .page-item {
        display: inline-block;
    }
    .pagination-wrapper .page-link {
        min-width: 42px;
        height: 42px;
        padding: 0 14px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #334155;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .pagination-wrapper .page-link:hover {
        background: #eef2ff;
        border-color: #c7d2fe;
        color: #4338ca;
        transform: translateY(-1px);
        text-decoration: none;
    }
    .pagination-wrapper .active .page-link {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: #ffffff;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(79,70,229,0.25);
    }
    .pagination-wrapper .disabled .page-link {
        background: #f8fafc;
        color: #94a3b8;
        cursor: not-allowed;
        opacity: 0.7;
        pointer-events: none;
    }
    .pagination-wrapper .page-link svg {
        width: 16px;
        height: 16px;
    }

    /* ── department filter chips ── */
    .department-chip-row {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 12px;
    }
    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        border-radius: 40px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border: 2px solid transparent;
        background: #f1f4f9;
        color: #475569;
    }
    .filter-chip:hover {
        text-decoration: none;
        transform: translateY(-1px);
        background: #e8edf5;
    }
    .filter-chip .chip-label {
        color: inherit;
    }
    .filter-chip .chip-count {
        background: rgba(0,0,0,0.06);
        padding: 0 8px;
        border-radius: 20px;
        font-size: 0.75rem;
        min-width: 20px;
        text-align: center;
        color: inherit;
    }
    .filter-chip.active {
        border-color: #4f46e5;
        background: #eef2ff;
        color: #4338ca;
    }
    .filter-chip.active .chip-count {
        background: rgba(79,70,229,0.15);
        color: #4338ca;
    }

    .department-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #eef2f9;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        padding: 20px 22px 8px 22px;
    }
    .department-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .department-card-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    .department-card-subtitle {
        color: #64748b;
        font-size: 0.9rem;
        margin: 4px 0 0;
    }

    @media (max-width: 700px) {
        .page-header-modern {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }
        .filter-row-flex {
            flex-direction: column;
            align-items: stretch;
        }
        .policy-table-wrap table {
            min-width: 900px;
        }
        .action-btn-group {
            justify-content: center;
        }
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .department-chip-row {
            justify-content: center;
        }
    }

    #pageLoader {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(5px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #ccc;
        border-top-color: #4f46e5;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div id="pageLoader"><div class="spinner"></div></div>

<div class="payroll-policy-container">

    <!-- header -->
    <div class="page-header-modern">
        <div>
            <h2 class="page-title-modern"><i class="fas fa-file-invoice"></i> Payroll Policies</h2>
            <div class="mt-2">
                <span class="selected-year-badge" id="selectedYearBadge">
                    <i class="fas fa-calendar-alt"></i> Current: <span id="currentYearDisplay">{{ $selectedFinancialYear ?? date('Y') . '-' . (date('Y') + 1) }}</span>
                </span>
            </div>
        </div>
        <div class="d-flex gap-2" style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('institute.payroll.policy.logs') }}" class="btn btn-primary-round d-none">
                <i class="fas fa-history"></i> View Logs
            </a>
            <a href="{{ route('institute.payroll.configuration') }}" class="btn btn-primary-round">
                <i class="fas fa-plus-circle"></i> Create New Policy
            </a>
        </div>
    </div>

    <!-- STAT CARDS -->
    @php
        $totalPolicies = $policies->count();
        $activePolicies = $policies->where('status', 'active')->count();
        $inactivePolicies = $policies->where('status', 'inactive')->count();
        $deptPolicies = $policies->where('payroll_type', 'department')->count();
        $employeePolicies = $policies->where('payroll_type', 'employee')->count();
        
        // Calculate serial number helper
        function getPolicySerialNumber($index, $paginator) {
            if ($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                return $paginator->perPage() * ($paginator->currentPage() - 1) + $index + 1;
            }
            return $index + 1;
        }
    @endphp

    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-file-invoice"></i></div>
            <div class="stat-number">{{ $totalPolicies }}</div>
            <div class="stat-label">Total Policies</div>
            <div class="stat-sub">All payroll policies</div>
        </div>
        <div class="stat-card green d-none">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">{{ $activePolicies }}</div>
            <div class="stat-label">Active Policies</div>
            <div class="stat-sub">Currently active policies</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-icon"><i class="fas fa-building"></i></div>
            <div class="stat-number">{{ $deptPolicies }}</div>
            <div class="stat-label">Department Policies</div>
            <div class="stat-sub">Policies at department level</div>
        </div>
        <div class="stat-card amber">
            <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
            <div class="stat-number">{{ $employeePolicies }}</div>
            <div class="stat-label">Employee Policies</div>
            <div class="stat-sub">Policies at employee level</div>
        </div>
    </div>

    <!-- filters -->
    <div class="filter-glass-card">
        <form method="GET" action="{{ route('institute.payroll.policy.management') }}" id="filterForm">
            <div class="filter-row-flex">
                <div class="filter-group">
                    <label><i class="fas fa-calendar-alt"></i> FINANCIAL YEAR</label>
                    <select name="financial_year" id="filterYearSelect" class="form-control-sm-custom">
                        <option value="">All Years</option>
                        @php
                            $currentYear = date('Y');
                            $financialYearsList = [];
                            for($i = -2; $i <= 2; $i++) {
                                $start = $currentYear + $i;
                                $end = $start + 1;
                                $financialYearsList[] = $start . '-' . $end;
                            }
                            $currentFinancialYear = date('Y') . '-' . (date('Y') + 1);
                            if(date('m') < 4) {
                                $currentFinancialYear = (date('Y') - 1) . '-' . date('Y');
                            }
                        @endphp
                        @foreach($financialYearsList as $year)
                            <option value="{{ $year }}" {{ ($selectedFinancialYear ?? '') == $year ? 'selected' : '' }}>
                                {{ $year }} {{ $year == $currentFinancialYear ? '(Current)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- DEPARTMENT FILTER -->
                <div class="filter-group">
                    <label><i class="fas fa-building"></i> DEPARTMENT</label>
                    <select name="department_id" id="filterDepartmentSelect" class="form-control-sm-custom">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->department_id }}" {{ ($selectedDepartmentId ?? '') == $dept->department_id ? 'selected' : '' }}>
                                {{ $dept->department }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label><i class="fas fa-search"></i> SEARCH</label>
                    <input type="text" name="search" id="searchInputText" class="form-control-sm-custom"
                           placeholder="Name, code or department" value="{{ $searchQuery ?? '' }}">
                </div>
                <div class="filter-group" style="flex: 0 0 auto;">
                    <a href="{{ route('institute.payroll.policy.management') }}" class="btn btn-outline-secondary btn-sm-pill" style="padding:8px 20px; border-radius:40px;">
                        <i class="fas fa-undo-alt"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Department Filter Chips -->
    @if(!empty($selectedDepartmentId))
        @php
            $selectedDept = $departments->firstWhere('department_id', $selectedDepartmentId);
        @endphp
        @if($selectedDept)
            <div class="department-chip-row" style="margin-bottom: 16px;">
                <span class="filter-chip active">
                    <span class="chip-label"><i class="fas fa-building"></i> {{ $selectedDept->department }}</span>
                    <span class="chip-count">{{ $policies->count() }}</span>
                </span>
                <a href="{{ route('institute.payroll.policy.management', array_merge(request()->query(), ['department_id' => ''])) }}" class="filter-chip">
                    <span class="chip-label"><i class="fas fa-times"></i> Clear Filter</span>
                </a>
            </div>
        @endif
    @endif

    <!-- Policy Table -->
    @php
        $policyItems = isset($policiesPaginator) && method_exists($policiesPaginator, 'items')
            ? $policiesPaginator->items()
            : ($policies ?? collect());
        $showPolicyPagination = isset($policiesPaginator) && method_exists($policiesPaginator, 'hasPages') && $policiesPaginator->hasPages();
    @endphp

    @if(!empty($selectedDepartmentId))
        @php
            $selectedDept = $departments->firstWhere('department_id', $selectedDepartmentId);
        @endphp
        @if($selectedDept)
            <div class="department-card">
                <div class="department-card-header">
                    <div>
                        <h4 class="department-card-title"><i class="fas fa-building"></i> {{ $selectedDept->department }}</h4>
                        <p class="department-card-subtitle">Showing {{ isset($policiesPaginator) ? $policiesPaginator->total() : $policies->count() }} policy record(s) for this department.</p>
                    </div>
                </div>
                <div class="policy-table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th class="sno-column">S.No</th>
                                <th>Policy ID</th>
                                <th>Target</th>
                                <th>Department</th>
                                <th>Financial Year</th>
                                <th>Employment Type</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($policyItems as $index => $policy)
                                @php
                                    $sno = getPolicySerialNumber($index, $policiesPaginator ?? $policies);
                                    $isActive = $policy->status === 'active' || $policy->status === null;
                                    $rowClass = !$isActive ? 'inactive-policy' : '';
                                    $targetName = 'N/A';
                                    $departmentName = 'N/A';

                                    if($policy->employee_id) {
                                        $emp = $employees->firstWhere('employee_id', $policy->employee_id);
                                        $targetName = $emp ? $emp->name : 'Unknown Employee';
                                        if($emp && $emp->department_id) {
                                            $dept = $departments->firstWhere('department_id', $emp->department_id);
                                            $departmentName = $dept ? $dept->department : 'N/A';
                                        }
                                    } elseif($policy->department_id) {
                                        $dept = $departments->firstWhere('department_id', $policy->department_id);
                                        $targetName = $dept ? $dept->department : 'Unknown Department';
                                        $departmentName = $dept ? $dept->department : 'N/A';
                                    }

                                    $viewUrl = route('institute.payroll.policy.details', $policy->payroll_policy_id);
                                    $editUrl = route('institute.payroll.policy.edit', $policy->payroll_policy_id);
                                    $typeClass = $policy->payroll_type === 'department' ? 'policy-type-dept' : 'policy-type-emp';
                                    $typeLabel = ucfirst($policy->payroll_type ?? 'N/A');
                                    $createdAtDisplay = $policy->created_at ? \Carbon\Carbon::parse($policy->created_at)->format('d M Y') : '—';
                                @endphp

                                <tr class="{{ $rowClass }}">
                                    <td class="sno-column">{{ $sno }}</td>
                                    <td><strong>{{ $policy->payroll_policy_id }}</strong></td>
                                    <td>
                                        <div class="emp-name-cell">
                                            <span class="name">{{ $targetName }}</span>
                                            <span class="policy-badge-sm {{ $typeClass }}">{{ $typeLabel }}</span>
                                        </div>
                                    </td>
                                    <td><span class="badge-dept">{{ $departmentName }}</span></td>
                                    <td>{{ $policy->financial_year ?? '—' }}</td>
                                    <td>{{ ucfirst($policy->policy_employment_type ?? 'N/A') }}</td>
                                    <td>{{ $createdAtDisplay }}</td>
                                    <td>
                                        <div class="action-btn-group">
                                            <a href="{{ $viewUrl }}" class="btn-outline-primary btn-sm-pill"><i class="fas fa-eye"></i> View</a>
                                            <a href="{{ $editUrl }}" class="btn-outline-warning btn-sm-pill"><i class="fas fa-edit"></i> Edit</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <i class="fas fa-file-invoice fa-2x text-muted mb-2 d-block"></i>
                                            <p>No payroll policies found for the current filters.</p>
                                            <a href="{{ route('institute.payroll.configuration') }}" class="btn btn-primary-round mt-2" style="display:inline-flex;">
                                                <i class="fas fa-plus-circle"></i> Create New Policy
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($showPolicyPagination)
                    <div class="pagination-wrapper">
                        {{ $policiesPaginator->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-building fa-2x text-muted mb-2 d-block"></i>
                <p>No department matches the current filter.</p>
            </div>
        @endif
    @else
        <div class="policy-table-wrap">
            <table>
                <thead>
                    <tr>
                        <th class="sno-column">S.No</th>
                        <th>Policy ID</th>
                        <th>Target</th>
                        <th>Department</th>
                        <th>Financial Year</th>
                        <th>Employment Type</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($policyItems as $index => $policy)
                        @php
                            $sno = getPolicySerialNumber($index, $policiesPaginator ?? $policies);
                            $isActive = $policy->status === 'active' || $policy->status === null;
                            $rowClass = !$isActive ? 'inactive-policy' : '';
                            $targetName = 'N/A';
                            $departmentName = 'N/A';

                            if($policy->employee_id) {
                                $emp = $employees->firstWhere('employee_id', $policy->employee_id);
                                $targetName = $emp ? $emp->name : 'Unknown Employee';
                                if($emp && $emp->department_id) {
                                    $dept = $departments->firstWhere('department_id', $emp->department_id);
                                    $departmentName = $dept ? $dept->department : 'N/A';
                                }
                            } elseif($policy->department_id) {
                                $dept = $departments->firstWhere('department_id', $policy->department_id);
                                $targetName = $dept ? $dept->department : 'Unknown Department';
                                $departmentName = $dept ? $dept->department : 'N/A';
                            }

                            $viewUrl = route('institute.payroll.policy.details', $policy->payroll_policy_id);
                            $editUrl = route('institute.payroll.policy.edit', $policy->payroll_policy_id);
                            $typeClass = $policy->payroll_type === 'department' ? 'policy-type-dept' : 'policy-type-emp';
                            $typeLabel = ucfirst($policy->payroll_type ?? 'N/A');
                            $createdAtDisplay = $policy->created_at ? \Carbon\Carbon::parse($policy->created_at)->format('d M Y') : '—';
                        @endphp

                        <tr class="{{ $rowClass }}">
                            <td class="sno-column">{{ $sno }}</td>
                            <td><strong>{{ $policy->payroll_policy_id }}</strong></td>
                            <td>
                                <div class="emp-name-cell">
                                    <span class="name">{{ $targetName }}</span>
                                    <span class="policy-badge-sm {{ $typeClass }}">{{ $typeLabel }}</span>
                                </div>
                            </td>
                            <td><span class="badge-dept">{{ $departmentName }}</span></td>
                            <td>{{ $policy->financial_year ?? '—' }}</td>
                            <td>{{ ucfirst($policy->policy_employment_type ?? 'N/A') }}</td>
                            <td>{{ $createdAtDisplay }}</td>
                            <td>
                                <div class="action-btn-group">
                                    <a href="{{ $viewUrl }}" class="btn-outline-primary btn-sm-pill"><i class="fas fa-eye"></i> View</a>
                                    <a href="{{ $editUrl }}" class="btn-outline-warning btn-sm-pill"><i class="fas fa-edit"></i> Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-file-invoice fa-2x text-muted mb-2 d-block"></i>
                                    <p>No payroll policies found for the current filters.</p>
                                    <a href="{{ route('institute.payroll.configuration') }}" class="btn btn-primary-round mt-2" style="display:inline-flex;">
                                        <i class="fas fa-plus-circle"></i> Create New Policy
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($showPolicyPagination)
            <div class="pagination-wrapper">
                {{ $policiesPaginator->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        @endif
    @endif

</div>

<script>
    function showLoader() {
        const loader = document.getElementById('pageLoader');
        if (loader) loader.style.display = 'flex';
    }

    document.getElementById('filterYearSelect')?.addEventListener('change', function() {
        showLoader();
        document.getElementById('filterForm').submit();
    });

    document.getElementById('filterDepartmentSelect')?.addEventListener('change', function() {
        showLoader();
        document.getElementById('filterForm').submit();
    });

    let searchTimeout;
    document.getElementById('searchInputText')?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            showLoader();
            document.getElementById('filterForm').submit();
        }, 500);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const yearSelect = document.getElementById('filterYearSelect');
        const yearDisplay = document.getElementById('currentYearDisplay');
        if(yearSelect && yearDisplay) {
            const updateYearDisplay = () => {
                const selectedOption = yearSelect.options[yearSelect.selectedIndex];
                yearDisplay.textContent = selectedOption.value || '{{ date("Y") . "-" . (date("Y")+1) }}';
            };
            yearSelect.addEventListener('change', updateYearDisplay);
            updateYearDisplay();
        }
    });
</script>

@endsection
@extends('instituteAdmin.Payroll.PayrollManagement')
@section('payroll-content')

<title>Salary Management</title>

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

    /* ── DEPARTMENT CARDS ── */
    .department-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #eef2f9;
        margin-bottom: 28px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        transition: all 0.2s;
    }
    .department-card:hover {
        box-shadow: 0 4px 14px rgba(0,0,0,0.05);
    }
    .department-card-header {
        padding: 20px 24px;
        background: linear-gradient(90deg, #f8faff 0%, #ffffff 100%);
        border-bottom: 1px solid #eef2f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
    .department-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .department-card-title i {
        color: #4f46e5;
    }
    .department-card-subtitle {
        font-size: 0.85rem;
        color: #64748b;
        margin: 4px 0 0 0;
    }
    .department-chip-row {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
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
    .filter-chip.with-structure.active {
        border-color: #10b981;
        background: #dcfce7;
        color: #166534;
    }
    .filter-chip.with-structure.active .chip-count {
        background: rgba(16,185,129,0.15);
        color: #166534;
    }
    .filter-chip.without-structure.active {
        border-color: #ef4444;
        background: #fee2e2;
        color: #991b1b;
    }
    .filter-chip.without-structure.active .chip-count {
        background: rgba(239,68,68,0.15);
        color: #991b1b;
    }

    /* ── TABLE VIEW ── */
    .employee-table-wrap {
        overflow-x: auto;
        padding: 0 4px 4px 4px;
    }
    .employee-table-wrap table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
        min-width: 1200px; /* Increased to accommodate S.No column */
    }
    .employee-table-wrap th {
        background: #f8fafc;
        color: #1e293b;
        font-weight: 600;
        font-size: 0.8rem;
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
    .employee-table-wrap td {
        padding: 12px 16px;
        border-bottom: 1px solid #edf2f7;
        vertical-align: middle;
        color: #1e293b;
    }
    .employee-table-wrap tr:last-child td {
        border-bottom: none;
    }
    .employee-table-wrap tr:hover td {
        background: #fafcff;
    }

    .employee-table-wrap tr.inactive-structure td {
        background: #f8fafc;
        opacity: 0.75;
    }
    .employee-table-wrap tr.inactive-structure:hover td {
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

    .status-badge {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .status-active { background: #dcfce7; color: #15803d; }
    .status-inactive { background: #fee2e2; color: #991b1b; }
    .status-draft { background: #fff3e3; color: #b45309; }
    .status-none { background: #f1f3f6; color: #4b5565; }

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
        .employee-table-wrap table {
            min-width: 900px;
        }
        .action-btn-group {
            justify-content: center;
        }
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .department-card-header {
            flex-direction: column;
            align-items: stretch;
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

<div class="salary-management-container">

    <!-- header -->
    <div class="page-header-modern">
        <div>
            <h2 class="page-title-modern"><i class="fas fa-chart-line"></i> Salary Management</h2>
            <div class="mt-2">
                <span class="selected-year-badge" id="selectedYearBadge">
                    <i class="fas fa-calendar-alt"></i> Current: <span id="currentYearDisplay">{{ $selectedFinancialYear ?? date('Y') . '-' . (date('Y') + 1) }}</span>
                </span>
            </div>
        </div>
        <div class="d-flex gap-2" style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('institute.payroll.salary.logs') }}" class="btn btn-primary-round d-none">
                <i class="fas fa-history"></i> View Logs
            </a>
            <a href="{{ route('institute.ctc.salary-structure.create') }}" class="btn btn-primary-round">
                <i class="fas fa-plus-circle"></i> Create Salary Structure
            </a>
        </div>
    </div>

    <!-- STAT CARDS -->
    @php
        $totalEmployees = $employees->count();
        $totalWithStructures = 0;
        $totalActive = 0;
        $totalInactive = 0;
        $totalNoStructure = 0;
        $totalCTC = 0;

        foreach($employees as $emp) {
            $structure = $allStructuresByEmployee[$emp->employee_id] ?? null;
            if($structure) {
                $totalWithStructures++;
                if($structure->status === 'active' || $structure->status === null) {
                    $totalActive++;
                } else {
                    $totalInactive++;
                }
                $totalCTC += $structure->total_ctc_annual ?? 0;
            } else {
                $totalNoStructure++;
            }
        }
    @endphp

    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-number">{{ $totalEmployees }}</div>
            <div class="stat-label">Total Employees</div>
            <div class="stat-sub">Active employees in institute</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">{{ $totalActive }}</div>
            <div class="stat-label">Active Structures</div>
            <div class="stat-sub">{{ $totalWithStructures }} employees have structures</div>
        </div>
        <div class="stat-card amber">
            <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
            <div class="stat-number">{{ $totalNoStructure }}</div>
            <div class="stat-label">No Structure</div>
            <div class="stat-sub">Employees without salary structure</div>
        </div>
        <div class="stat-card rose d-none">
            <div class="stat-icon"><i class="fas fa-archive"></i></div>
            <div class="stat-number">{{ $totalInactive }}</div>
            <div class="stat-label">Inactive Structures</div>
            <div class="stat-sub">Structures that are archived</div>
        </div>
        @if($totalCTC > 0)
        <div class="stat-card d-none" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; border-color: transparent;">
            <div class="stat-icon" style="color: rgba(255,255,255,0.8);"><i class="fas fa-coins"></i></div>
            <div class="stat-number" style="color: white;">₹{{ number_format($totalCTC / 100000, 1) }}L</div>
            <div class="stat-label" style="color: rgba(255,255,255,0.8);">Total CTC (Annual)</div>
            <div class="stat-sub" style="color: rgba(255,255,255,0.6);">Sum of all active structures</div>
        </div>
        @endif
    </div>

    <!-- filters -->
    <div class="filter-glass-card">
        <form method="GET" action="{{ route('institute.ctc.salary-management') }}" id="filterForm">
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
                        @foreach($allDepartmentsList ?? [] as $dept)
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
                    <a href="{{ route('institute.ctc.salary-management') }}" class="btn btn-outline-secondary btn-sm-pill" style="padding:8px 20px; border-radius:40px;">
                        <i class="fas fa-undo-alt"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="department-view-section">
        @php
            $departmentMap = [];
            foreach($allDepartmentsList ?? [] as $dept) {
                $departmentMap[$dept->department_id] = $dept->department;
            }
            
            // Helper function to calculate serial number
            function getSerialNumber($index, $paginator) {
                if ($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                    return $paginator->perPage() * ($paginator->currentPage() - 1) + $index + 1;
                }
                return $index + 1;
            }
        @endphp

        @if(empty($selectedDepartmentId))
            <div class="department-card">
                <div class="department-card-header">
                    <div>
                        <h4 class="department-card-title"><i class="fas fa-list-alt"></i> Active Salary Structures</h4>
                        <p class="department-card-subtitle">Showing all active salary structures for the current filters.</p>
                    </div>
                </div>
                <div class="employee-table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th class="sno-column">S.No</th>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <!-- <th>Policy Type</th> -->
                                <th>Employment Type</th>
                                <th>FY</th>
                                <th>CTC (Annual)</th>
                                <th>Basic (Monthly)</th>
                                <th>Net (Monthly)</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($structureRowsPaginator instanceof \Illuminate\Pagination\LengthAwarePaginator ? $structureRowsPaginator : collect($structureRows ?? []) as $index => $row)
                                @php
                                    $employee = $row['employee'];
                                    $structure = $row['structure'];
                                    $preview = $row['preview'];
                                    $hasStructure = !empty($structure);
                                    $isActive = $hasStructure && ($structure->status === 'active' || $structure->status === null);
                                    $viewUrl = route('institute.ctc.salary.details', $structure->salary_structure_id);
                                    $historyUrl = route('institute.ctc.employee.salary.structures', $employee->employee_id);
                                    $netMonthly = $preview ? $preview->net_salary_monthly : ($structure ? $structure->total_ctc_annual / 12 : null);
                                    $basicMonthly = $structure ? $structure->basic_salary_monthly : null;
                                    $employmentType = $structure ? $structure->employment_type ?? 'N/A' : ($employee->employment_type ?? 'N/A');
                                    $policyType = $structure ? $structure->policy_employment_type ?? 'N/A' : 'N/A';
                                    $createdAtDisplay = $structure && $structure->created_at ? \Carbon\Carbon::parse($structure->created_at)->format('d M Y') : '—';
                                    $financialYear = $structure ? $structure->financial_year : '—';
                                    $ctcAnnual = $structure ? $structure->total_ctc_annual : null;
                                    $departmentName = $departmentMap[$employee->department_id] ?? 'N/A';
                                    $statusClass = $hasStructure ? ($isActive ? 'status-active' : 'status-inactive') : 'status-none';
                                    $statusLabel = $hasStructure ? ($isActive ? 'Active' : 'Inactive') : 'No Structure';
                                    $sno = getSerialNumber($index, $structureRowsPaginator);
                                @endphp

                                <tr>
                                    <td class="sno-column">{{ $sno }}</td>
                                    <td>
                                        <div class="emp-name-cell">
                                            <span class="name">{{ $employee->name }}</span>
                                            <span class="code">({{ $employee->employee_code ?? $employee->employee_id }})</span>
                                        </div>
                                    </td>
                                    <td><span class="badge-dept">{{ $departmentName }}</span></td>
                                    <td>{{ $row['designation_name'] ?? ($employee->designation ?? 'N/A') }}</td>
                                    <!-- <td>{{ ucfirst($policyType) }}</td> -->
                                    <td>{{ ucfirst($employmentType) }}</td>
                                    <td>{{ $financialYear }}</td>
                                    <td>{{ $hasStructure ? '₹' . number_format($ctcAnnual ?? 0) : '—' }}</td>
                                    <td>{{ $hasStructure ? '₹' . number_format($basicMonthly ?? 0) : '—' }}</td>
                                    <td>{{ $hasStructure ? '₹' . number_format($netMonthly ?? 0) : '—' }}</td>
                                    <td><span class="status-badge {{ $statusClass }}"><i class="fas fa-circle" style="font-size:6px;"></i> {{ $statusLabel }}</span></td>
                                    <td>{{ $createdAtDisplay }}</td>
                                    <td>
                                        <div class="action-btn-group">
                                            <a href="{{ $viewUrl }}" class="btn-outline-primary btn-sm-pill"><i class="fas fa-eye"></i> View</a>
                                            <a href="{{ $historyUrl }}" class="btn-outline-info btn-sm-pill"><i class="fas fa-history"></i> History</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13">
                                        <div class="empty-state">
                                            <i class="fas fa-users-slash fa-2x text-muted mb-2 d-block"></i>
                                            <p>No active salary structures found for the current filters.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($structureRowsPaginator instanceof \Illuminate\Pagination\LengthAwarePaginator && $structureRowsPaginator->hasPages())
                    <div class="pagination-wrapper">
                        {{ $structureRowsPaginator->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        @else
            @php
                $departmentCards = collect($departmentSummaries ?? [])->filter(function ($summary) use ($selectedDepartmentId) {
                    return (string) $summary['department']->department_id === (string) $selectedDepartmentId;
                })->values();
            @endphp

            @if($departmentCards->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-users-slash fa-2x text-muted mb-2 d-block"></i>
                    <p>No departments match the current filters.</p>
                    <a href="{{ route('institute.ctc.salary-management') }}" class="btn btn-outline-secondary btn-sm-pill mt-2">
                        <i class="fas fa-undo-alt"></i> Clear Filters
                    </a>
                </div>
            @else
                @foreach($departmentCards as $summary)
                    @php
                        $department = $summary['department'];
                        $departmentEmployees = $summary['employees'] ?? [];
                        $departmentPaginator = $summary['employees'] ?? null;
                        $departmentName = $department->department ?? 'Department';
                        $baseFilterQuery = array_filter(request()->query(), function ($value, $key) {
                            return !in_array($key, ['page', 'structure_filter', 'department_id'], true);
                        }, ARRAY_FILTER_USE_BOTH);
                        $baseFilterQuery['department_id'] = $department->department_id;
                        $defaultFilterQuery = $baseFilterQuery;
                        $activeFilter = $selectedStructureFilter ?? null;
                        $isDepartmentSelected = (string)($selectedDepartmentId ?? '') === (string)$department->department_id;
                    @endphp

                    <div class="department-card">
                        <div class="department-card-header">
                            <div>
                                <h4 class="department-card-title"><i class="fas fa-building"></i> {{ $departmentName }}</h4>
                                <p class="department-card-subtitle">
                                    {{ $summary['total_employees'] }} employees ·
                                    {{ $summary['with_structures'] }} with structures ·
                                    {{ $summary['without_structures'] }} without structures
                                </p>
                            </div>
                            <div class="department-chip-row">
                                <a href="{{ route('institute.ctc.salary-management', array_merge($defaultFilterQuery, ['structure_filter' => null])) }}"
                                   class="filter-chip {{ $isDepartmentSelected && empty($activeFilter) ? 'active' : '' }}">
                                    <span class="chip-label">All</span>
                                    <span class="chip-count">{{ $summary['total_employees'] }}</span>
                                </a>
                                <a href="{{ route('institute.ctc.salary-management', array_merge($defaultFilterQuery, ['structure_filter' => 'with'])) }}"
                                   class="filter-chip with-structure {{ $isDepartmentSelected && $activeFilter === 'with' ? 'active' : '' }}">
                                    <span class="chip-label">With Structure</span>
                                    <span class="chip-count">{{ $summary['with_structures'] }}</span>
                                </a>
                                <a href="{{ route('institute.ctc.salary-management', array_merge($defaultFilterQuery, ['structure_filter' => 'without'])) }}"
                                   class="filter-chip without-structure {{ $isDepartmentSelected && $activeFilter === 'without' ? 'active' : '' }}">
                                    <span class="chip-label">No Structure</span>
                                    <span class="chip-count">{{ $summary['without_structures'] }}</span>
                                </a>
                            </div>
                        </div>

                        <div class="employee-table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="sno-column">S.No</th>
                                        <th>Employee</th>
                                        <th>Designation</th>
                                        <th>Policy Type</th>
                                        <th>Employment Type</th>
                                        <th>FY</th>
                                        <th>CTC (Annual)</th>
                                        <th>Basic (Monthly)</th>
                                        <th>Net (Monthly)</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($departmentPaginator instanceof \Illuminate\Pagination\LengthAwarePaginator ? $departmentPaginator : collect($departmentEmployees) as $index => $entry)
                                        @php
                                            $employee = $entry['employee'];
                                            $structure = $entry['structure'];
                                            $hasStructure = $entry['has_structure'];
                                            $isActive = $hasStructure && ($structure->status === 'active' || $structure->status === null);
                                            $isInactive = $hasStructure && $structure->status === 'inactive';

                                            $viewUrl = $hasStructure ? route('institute.ctc.salary.details', $structure->salary_structure_id) : '#';
                                            $historyUrl = $hasStructure ? route('institute.ctc.employee.salary.structures', $employee->employee_id) : '#';
                                            $createUrl = route('institute.payroll.structure');

                                            $preview = $hasStructure ? ($previews[$structure->salary_structure_id] ?? null) : null;
                                            $netMonthly = $preview ? $preview->net_salary_monthly : ($structure ? $structure->total_ctc_annual / 12 : null);
                                            $basicMonthly = $structure ? $structure->basic_salary_monthly : null;
                                            $employmentType = $structure ? $structure->employment_type ?? 'N/A' : ($employee->employment_type ?? 'N/A');
                                            $policyType = $structure ? $structure->policy_employment_type ?? 'N/A' : 'N/A';
                                            $designation = $entry['designation_name'] ?? ($employee->designation ?? 'N/A');
                                            $createdAt = $structure ? $structure->created_at : null;
                                            $createdAtDisplay = $createdAt ? \Carbon\Carbon::parse($createdAt)->format('d M Y') : '—';
                                            $financialYear = $structure ? $structure->financial_year : '—';
                                            $ctcAnnual = $structure ? $structure->total_ctc_annual : null;

                                            $statusClass = $hasStructure ? ($isActive ? 'status-active' : 'status-inactive') : 'status-none';
                                            $statusLabel = $hasStructure ? ($isActive ? 'Active' : 'Inactive') : 'No Structure';
                                            $rowClass = $isInactive ? 'inactive-structure' : '';
                                            $sno = getSerialNumber($index, $departmentPaginator);
                                        @endphp

                                        <tr class="{{ $rowClass }}">
                                            <td class="sno-column">{{ $sno }}</td>
                                            <td>
                                                <div class="emp-name-cell">
                                                    <span class="name">{{ $employee->name }}</span>
                                                    <span class="code">({{ $employee->employee_code ?? $employee->employee_id }})</span>
                                                </div>
                                            </td>
                                            <td>{{ $designation }}</td>
                                            <td>{{ ucfirst($policyType) }}</td>
                                            <td>{{ ucfirst($employmentType) }}</td>
                                            <td>{{ $financialYear }}</td>
                                            <td>{{ $hasStructure ? '₹' . number_format($ctcAnnual ?? 0) : '—' }}</td>
                                            <td>{{ $hasStructure ? '₹' . number_format($basicMonthly ?? 0) : '—' }}</td>
                                            <td>{{ $hasStructure ? '₹' . number_format($netMonthly ?? 0) : '—' }}</td>
                                            <td><span class="status-badge {{ $statusClass }}"><i class="fas fa-circle" style="font-size:6px;"></i> {{ $statusLabel }}</span></td>
                                            <td>{{ $createdAtDisplay }}</td>
                                            <td>
                                                <div class="action-btn-group">
                                                    @if($hasStructure)
                                                        <a href="{{ $viewUrl }}" class="btn-outline-primary btn-sm-pill"><i class="fas fa-eye"></i> View</a>
                                                        <a href="{{ $historyUrl }}" class="btn-outline-info btn-sm-pill"><i class="fas fa-history"></i> History</a>
                                                    @else
                                                        <a href="{{ $createUrl }}" class="btn-outline-success btn-sm-pill"><i class="fas fa-plus"></i> Create</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12">
                                                <div class="empty-state">
                                                    <i class="fas fa-filter fa-2x text-muted mb-2 d-block"></i>
                                                    <p>No employees match the current filters for this department.</p>
                                                    <a href="{{ route('institute.ctc.salary-management') }}" class="btn btn-outline-secondary btn-sm-pill mt-2">
                                                        <i class="fas fa-undo-alt"></i> Clear Filters
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($departmentPaginator instanceof \Illuminate\Pagination\LengthAwarePaginator && $departmentPaginator->hasPages())
                            <div class="pagination-wrapper">
                                {{ $departmentPaginator->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        @endif
    </div>

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
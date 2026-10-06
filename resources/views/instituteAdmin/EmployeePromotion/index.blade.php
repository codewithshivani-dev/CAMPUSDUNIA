{{-- resources/views/instituteAdmin/EmployeePromotion/index.blade.php --}}

@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Employee Promotion</title>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.page-header {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding: 20px 25px;
    background: var(--primary-gradient);
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

.page-title {
    font-weight: 600;
    color: white;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-title i {
    background: rgba(255, 255, 255, 0.2);
    padding: 10px 12px;
    border-radius: 12px;
}

.filter-container {
    background: #fff;
    border-radius: 12px;
    padding: 20px 25px;
    margin-bottom: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: var(--shadow-sm);
}

.filter-grid {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    flex: 1;
}

.filter-group {
    flex: 1;
    min-width: 160px;
}

.filter-group label {
    font-size: 12px;
    font-weight: 600;
    color: #475569;
    margin-bottom: 4px;
    display: block;
}

.filter-input {
    padding: 10px 14px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    background: #fff;
    transition: all 0.2s;
    width: 100%;
}

.filter-input:focus {
    outline: none;
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.table-responsive {
    overflow-x: auto;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    background: white;
    box-shadow: var(--shadow-sm);
}

.erp-table {
    width: 100%;
    min-width: 1000px;
    background: #fff;
    border-collapse: collapse;
}

.erp-table thead {
    background: var(--primary-gradient);
    position: sticky;
    top: 0;
    z-index: 10;
}

.erp-table th {
    padding: 14px 12px;
    font-weight: 600;
    color: white;
    text-align: left;
    font-size: 13px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    white-space: nowrap;
}

.erp-table td {
    padding: 14px 12px;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    font-size: 13px;
    vertical-align: middle;
}

.erp-table tbody tr:hover {
    background-color: #f8fafc;
}

.employee-name {
    font-weight: 600;
    color: #0f172a;
}

.employee-code {
    color: #64748b;
    font-size: 12px;
}

.emp-type-badge {
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

.emp-type-full-time {
    background: #dcfce7;
    color: #166534;
}

.emp-type-part-time {
    background: #f1f5f9;
    color: #475569;
}

.emp-type-contract {
    background: #dbeafe;
    color: #1e40af;
}

.emp-type-probation {
    background: #fef3c7;
    color: #92400e;
}

.designation-badge {
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
    background: #e0e7ff;
    color: #4338ca;
}

.action-buttons {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    justify-content: center;
}

.action-btn {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 4px;
    text-decoration: none;
    white-space: nowrap;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-decoration: none;
}

.action-btn-update {
    background: #dbeafe;
    color: #1e40af;
    border-color: #bfdbfe;
}

.action-btn-update:hover {
    background: #bfdbfe;
    color: #1e40af;
}

.action-btn-view {
    background: #e0f2fe;
    color: #0369a1;
    border-color: #bae6fd;
}

.action-btn-view:hover {
    background: #bae6fd;
    color: #0369a1;
}

.action-btn-salary {
    background: #fef3c7;
    color: #92400e;
    border-color: #fde68a;
    animation: pulse-salary 2s ease-in-out infinite;
}

.action-btn-salary:hover {
    background: #fde68a;
    color: #92400e;
}

.action-btn-salary-assign {
    background: #dbeafe;
    color: #1e40af;
    border-color: #bfdbfe;
}

.action-btn-salary-assign:hover {
    background: #bfdbfe;
    color: #1e40af;
}

@keyframes pulse-salary {

    0%,
    100% {
        opacity: 1;
    }

    50% {
        opacity: 0.7;
    }
}

.promotion-count {
    display: inline-block;
    background: #e2e8f0;
    border-radius: 20px;
    padding: 2px 10px;
    font-size: 12px;
    color: #475569;
}

.status-badge {
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 600;
}

.status-active {
    background: #dcfce7;
    color: #166534;
}

.status-inactive {
    background: #fee2e2;
    color: #991b1b;
}

.status-awaiting {
    background: #fef3c7;
    color: #92400e;
}

.status-not-assigned {
    background: #f1f5f9;
    color: #64748b;
}

.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 20px;
}

.pagination-info {
    font-size: 14px;
    color: #64748b;
}

/* Salary Structure Column Styles */
.salary-structure-cell {
    min-width: 220px;
}

.salary-structure-wrapper {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.salary-status {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.salary-actions {
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
}

.salary-action-btn {
    padding: 3px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 500;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 4px;
    text-decoration: none;
    white-space: nowrap;
}

.salary-action-btn:hover {
    transform: translateY(-1px);
    text-decoration: none;
}

.salary-action-view {
    background: #e0f2fe;
    color: #0369a1;
    border-color: #bae6fd;
}

.salary-action-view:hover {
    background: #bae6fd;
    color: #0369a1;
}

.salary-action-assign {
    background: #fef3c7;
    color: #92400e;
    border-color: #fde68a;
    animation: pulse-salary 2s ease-in-out infinite;
}

.salary-action-assign:hover {
    background: #fde68a;
    color: #92400e;
}

.salary-action-create {
    background: #dbeafe;
    color: #1e40af;
    border-color: #bfdbfe;
}

.salary-action-create:hover {
    background: #bfdbfe;
    color: #1e40af;
}

.salary-action-not-allowed {
    background: #f1f5f9;
    color: #94a3b8;
    border-color: #e2e8f0;
    cursor: not-allowed;
    opacity: 0.7;
}

.probation-badge {
    background: #fef3c7;
    color: #92400e;
    font-size: 9px;
    padding: 1px 6px;
    border-radius: 8px;
    margin-left: 4px;
    font-weight: 500;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
        padding: 16px 20px;
    }

    .filter-grid {
        flex-direction: column;
    }

    .filter-group {
        min-width: 100%;
    }

    .action-buttons {
        flex-direction: column;
        gap: 4px;
    }

    .action-btn {
        width: 100%;
        justify-content: center;
    }

    .salary-structure-cell {
        min-width: 200px;
    }

    .salary-actions {
        flex-direction: column;
    }
}
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-12">
            <!-- Page Header -->
            <div class="page-header">
                <h4 class="page-title">
                    <i class="fas fa-user-graduate"></i>
                    Employee's Promotion
                    <span class="badge bg-light text-dark ms-2 d-none" id="headerTotal">{{ $employees->total() }}</span>
                </h4>
                <div style="display: flex; gap: 10px;">
                    <button type="button" class="btn btn-light btn-sm" onclick="location.reload()"
                        style="border-radius: 8px; font-weight: 500;">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-container">
                <form id="filterForm" method="GET" action="{{ route('employee.promotion.index') }}">
                    <div class="filter-grid">
                        <div class="filter-group">
                            <label for="name"><i class="fas fa-user me-1"></i> Employee Name</label>
                            <input type="text" class="filter-input" name="name" id="name" value="{{ request('name') }}"
                                placeholder="Search by name..." oninput="this.form.submit()">
                        </div>

                        <div class="filter-group">
                            <label for="employee_code"><i class="fas fa-id-badge me-1"></i> Employee Code</label>
                            <input type="text" class="filter-input" name="employee_code" id="employee_code"
                                value="{{ request('employee_code') }}" placeholder="Search by code..."
                                oninput="this.form.submit()">
                        </div>

                        <div class="filter-group">
                            <label for="department_id"><i class="fas fa-building me-1"></i> Department</label>
                            <select class="filter-input" name="department_id" id="department_id"
                                onchange="this.form.submit()">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                <option value="{{ $dept->department_id }}"
                                    {{ request('department_id') == $dept->department_id ? 'selected' : '' }}>
                                    {{ $dept->department }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="designation_id"><i class="fas fa-briefcase me-1"></i> Designation</label>
                            <select class="filter-input" name="designation_id" id="designation_id"
                                onchange="this.form.submit()">
                                <option value="">All Designations</option>
                                @foreach($designations as $desig)
                                <option value="{{ $desig->designation_id }}"
                                    {{ request('designation_id') == $desig->designation_id ? 'selected' : '' }}>
                                    {{ $desig->designations }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="sort_by"><i class="fas fa-sort me-1"></i> Sort By</label>
                            <select class="filter-input" name="sort_by" id="sort_by" onchange="this.form.submit()">
                                <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                                <option value="employee_code"
                                    {{ request('sort_by') == 'employee_code' ? 'selected' : '' }}>Employee Code</option>
                                <option value="department" {{ request('sort_by') == 'department' ? 'selected' : '' }}>
                                    Department</option>
                                <option value="designation" {{ request('sort_by') == 'designation' ? 'selected' : '' }}>
                                    Designation</option>
                                <option value="employment_type"
                                    {{ request('sort_by') == 'employment_type' ? 'selected' : '' }}>Employment Type
                                </option>
                                <option value="doj" {{ request('sort_by') == 'doj' ? 'selected' : '' }}>Date of Joining
                                </option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="sort_order"><i class="fas fa-arrow-up-wide-short me-1"></i> Order</label>
                            <select class="filter-input" name="sort_order" id="sort_order"
                                onchange="this.form.submit()">
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending
                                </option>
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-2">
                        @if(request()->hasAny(['name', 'employee_code', 'department_id', 'designation_id']))
                        <a href="{{ route('employee.promotion.index') }}" class="text-danger"
                            style="text-decoration: none; font-weight: 500;">
                            <i class="fas fa-times-circle"></i> Clear Filters
                        </a>
                        @endif
                        <span class="ms-3 text-muted small">{{ $employees->total() }} results found</span>
                    </div>
                </form>
            </div>

            <!-- Employees Table -->
            <div class="table-responsive">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Employment Type</th>
                            <th>DOJ</th>
                            <th class="salary-structure-cell">Salary Structure</th>
                            <th>Promotions count</th>
                            <th class="text-center">Promote</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $index => $employee)
                        <tr>
                            <td>{{ $employees->firstItem() + $index }}</td>
                            <td>
                                <div class="employee-name">{{ $employee->name }}</div>
                                <div class="employee-code"><i class="fas fa-id-badge me-1"></i>
                                    {{ $employee->employee_code }}</div>
                                <div style="font-size: 11px; color: #64748b;">
                                    <i class="fas fa-envelope me-1"></i> {{ $employee->email ?? 'N/A' }}
                                </div>
                            </td>
                            <td>{{ $employee->department_name ?? 'N/A' }}</td>
                            <td><span class="designation-badge">{{ $employee->designation ?? 'N/A' }}</span></td>
                            <td>
                                <span class="emp-type-badge 
                                    @if($employee->employment_type == 'Full-time') emp-type-full-time
                                    @elseif($employee->employment_type == 'Part-time') emp-type-part-time
                                    @elseif($employee->employment_type == 'Contract-based') emp-type-contract
                                    @else emp-type-probation @endif">
                                    {{ $employee->employment_type ?? 'N/A' }}
                                </span>
                                @if($employee->employment_type == 'Probation-Period')
                                <span class="probation-badge">(Probation)</span>
                                @endif
                            </td>
                            <td>{{ $employee->doj ? \Carbon\Carbon::parse($employee->doj)->format('d-m-Y') : 'N/A' }}
                            </td>
                            <td class="salary-structure-cell">
                                <div class="salary-structure-wrapper">
                                    <div class="salary-status">
                                        @if($employee->has_active_salary_structure)
                                        <span class="status-badge status-active">
                                            <i class="fas fa-check-circle me-1"></i> Active
                                        </span>
                                       
                                        @elseif($employee->has_inactive_salary_structure)
                                        <span class="status-badge status-awaiting">
                                            <i class="fas fa-clock me-1"></i> Awaiting Assignment
                                        </span>
                                        
                                        @else
                                        <span class="status-badge status-not-assigned">
                                            <i class="fas fa-times-circle me-1"></i> Not Assigned
                                        </span>
                                        @endif
                                    </div>

                                    <div class="salary-actions">
                                        @php
                                        // Determine if employee is on probation
                                        $isProbation = ($employee->employment_type == 'Probation-Period');
                                        @endphp

                                        @if($employee->has_active_salary_structure &&
                                        isset($employee->salary_structure_id))
                                        {{-- Active Salary Structure - Only Show View Button --}}
                                        <a href="{{ url('/institute/admin/ctc-salary-configuration/ctc-salary-details/' . $employee->salary_structure_id) }}"
                                            class="salary-action-btn salary-action-view"
                                            title="View Salary Structure Details" target="_blank">
                                            <i class="fas fa-eye"></i> View
                                        </a>

                                        @elseif($employee->has_inactive_salary_structure &&
                                        isset($employee->salary_structure_id))
                                        {{-- Inactive Structure - Show View and Assign --}}
                                        <a class="d-none" href="{{ url('/institute/admin/ctc-salary-configuration/ctc-salary-details/' . $employee->salary_structure_id) }}"
                                            class="salary-action-btn salary-action-view"
                                            title="View Salary Structure Details" target="_blank">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        {{-- Direct link to salary structure page --}}
                                        <a href="{{ url('/institute/admin/ctc-salary-configuration/salary-structure/create') }}"
                                            class="salary-action-btn salary-action-create"
                                            title="Assign Salary Structure" target="_blank">
                                            <i class="fas fa-plus-circle"></i> Assign
                                        </a>

                                        @else
                                        {{-- No Salary Structure Assigned --}}
                                        {{-- Direct link to salary structure page for all employees --}}
                                        <a href="{{ url('/institute/admin/ctc-salary-configuration/salary-structure/create') }}"
                                            class="salary-action-btn salary-action-create"
                                            title="Assign Salary Structure" target="_blank">
                                            <i class="fas fa-plus-circle"></i> Assign
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="promotion-count">
                                    <i class="fas fa-arrow-up me-1"></i>
                                    {{ $employee->promotion_count ?? 0 }}
                                </span>
                                @if(isset($employee->latest_promotion) && $employee->latest_promotion)
                                <div style="font-size: 10px; color: #94a3b8; margin-top: 2px;">
                                    Last:
                                    {{ \Carbon\Carbon::parse($employee->latest_promotion->promotion_date)->format('d-m-Y') }}
                                </div>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <a href="{{ route('employee.promotion.edit', $employee->id) }}"
                                        class="action-btn action-btn-update" title="Promote Employee">
                                        <i class="fas fa-edit"></i> Update
                                    </a>
                                    <a href="{{ route('employee.promotion.history', $employee->id) }}"
                                        class="action-btn action-btn-view" title="View Promotion History">
                                        <i class="fas fa-history"></i> View
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">
                                <div class="text-center py-5">
                                    <i class="fas fa-users" style="font-size: 48px; color: #cbd5e1;"></i>
                                    <h4 class="mt-3">No Employees Found</h4>
                                    <p class="text-muted">Try adjusting your filters or search criteria.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($employees->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} of
                    {{ $employees->total() }} results
                </div>
                <div>
                    {{ $employees->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
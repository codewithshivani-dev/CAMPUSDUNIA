@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --primary: #4f46e5;
    --primary-dark: #4338ca;
    --primary-light: #eef2ff;
    --success: #10b981;
    --success-light: #d1fae5;
    --warning: #f59e0b;
    --warning-light: #fed7aa;
    --danger: #ef4444;
    --danger-light: #fee2e2;
    --info: #3b82f6;
    --info-light: #dbeafe;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    margin-bottom: 32px;
}

.stat-card {
    background: white;
    border-radius: 20px;
    padding: 20px 24px;
    border: 1px solid var(--gray-200);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.1);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: var(--primary-light);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
}

.stat-icon i {
    font-size: 24px;
    color: var(--primary);
}

.stat-value {
    font-size: 32px;
    font-weight: 800;
    color: var(--gray-900);
    line-height: 1.2;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 13px;
    font-weight: 500;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-bar {
    background: white;
    border-radius: 16px;
    padding: 20px 24px;
    margin-bottom: 24px;
    border: 1px solid var(--gray-200);
}

.filter-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-input {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--gray-200);
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.2s;
}

.filter-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.employees-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 20px;
}

.employee-card {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    transition: all 0.3s ease;
    cursor: pointer;
}

.employee-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.15);
    border-color: var(--primary-light);
}

.card-header {
    padding: 20px;
    background: linear-gradient(135deg, var(--gray-50), white);
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    align-items: center;
    gap: 16px;
}

.employee-avatar {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 20px;
    box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
}

.employee-info {
    flex: 1;
}

.employee-name {
    font-size: 16px;
    font-weight: 700;
    color: var(--gray-800);
    margin-bottom: 4px;
}

.employee-code {
    font-size: 12px;
    color: var(--gray-500);
    font-family: monospace;
    margin-bottom: 6px;
}

.department-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    background: var(--info-light);
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
    color: var(--info);
}

.card-body {
    padding: 16px 20px;
}

.leaves-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.leave-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 500;
    background: var(--gray-100);
    color: var(--gray-700);
    transition: all 0.2s;
}

.leave-tag:hover {
    transform: scale(1.02);
}

.leave-tag-individual {
    border-left: 3px solid var(--primary);
}

.leave-tag-department {
    border-left: 3px solid var(--success);
}

.leave-tag i {
    font-size: 11px;
    opacity: 0.7;
}

.leave-allocated {
    margin-left: auto;
    font-weight: 600;
    color: var(--primary);
    font-size: 11px;
}

.no-leaves {
    text-align: center;
    padding: 20px;
    color: var(--gray-400);
}

.no-leaves i {
    font-size: 32px;
    margin-bottom: 8px;
    display: block;
}

.no-leaves p {
    font-size: 13px;
    margin: 0;
}

.card-footer {
    padding: 12px 20px;
    background: var(--gray-50);
    border-top: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.usage-stats {
    display: flex;
    gap: 16px;
}

.usage-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
}

.usage-label {
    color: var(--gray-500);
}

.usage-value {
    font-weight: 600;
    color: var(--gray-700);
}

.view-detail-btn {
    padding: 6px 14px;
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: 10px;
    font-size: 12px;
    font-weight: 500;
    color: var(--primary);
    transition: all 0.2s;
}

.view-detail-btn:hover {
    background: var(--primary-light);
    border-color: var(--primary);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: var(--gray-100);
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.empty-icon i {
    font-size: 40px;
    color: var(--gray-400);
}

.pagination-modern {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 32px;
}

.pagination-modern .page-link {
    padding: 8px 14px;
    border-radius: 10px;
    border: 1px solid var(--gray-200);
    color: var(--gray-600);
    font-size: 13px;
    font-weight: 500;
    background: white;
}

.pagination-modern .active .page-link {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.modern-modal .modal-content {
    border-radius: 24px;
    border: none;
    overflow: hidden;
}

.modern-modal .modal-header {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    padding: 20px 24px;
    border: none;
}

.modern-modal .modal-header h5 {
    color: white;
    font-weight: 600;
}

.modern-modal .btn-close {
    filter: brightness(0) invert(1);
}

.active-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid var(--gray-200);
}

.filter-tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 5px 12px;
    background: var(--gray-100);
    border-radius: 20px;
    font-size: 12px;
    color: var(--gray-700);
}

.filter-tag i {
    cursor: pointer;
}

@media (max-width: 768px) {
    .modern-container {
        padding: 16px;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    .employees-grid {
        grid-template-columns: 1fr;
    }
}
</style>
<div class="container-fluid">
    <div class="modern-container">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: var(--gray-800); margin-bottom: 4px;">
                <i class="bi bi-pie-chart-fill" style="color: var(--primary); margin-right: 12px;"></i>
                Leave Quota
            </h1>
            <p style="color: var(--gray-500); font-size: 14px;">View and manage employee leave allocations</p>
        </div>
        <a href="{{ route('leaves.assign.form') }}" class="btn"
            style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; border-radius: 12px; padding: 10px 24px;">
            <i class="bi bi-plus-circle me-2"></i>Assign Quotas
        </a>
    </div>

    <!-- Stats -->
    <div class="stats-grid d-none">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-value">{{ number_format($totalEmployees ?? 0) }}</div>
            <div class="stat-label">Total Employees</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-calendar-check-fill"></i></div>
            <div class="stat-value">{{ number_format($totalLeavesAssigned ?? 0) }}</div>
            <div class="stat-label">Leaves Assigned</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-value">{{ number_format($avgUtilization ?? 0, 1) }}%</div>
            <div class="stat-label">Avg. Utilization</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-building"></i></div>
            <div class="stat-value">{{ number_format($totalDepartments ?? 0) }}</div>
            <div class="stat-label">Departments</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-bar">
        <form method="GET" action="{{ route('leaves.quota.management') }}" id="filterForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="filter-label"><i class="bi bi-search me-1"></i>Search</label>
                    <input type="text" name="search" class="filter-input auto-submit"
                        placeholder="Name or employee code..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label class="filter-label"><i class="bi bi-building me-1"></i>Department</label>
                    <select name="department_id" class="filter-input auto-submit">
                        <option value="">All Departments</option>
                        @foreach($departments ?? [] as $dept)
                        <option value="{{ $dept->department_id }}"
                            {{ request('department_id') == $dept->department_id ? 'selected' : '' }}>
                            {{ $dept->department }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="filter-label"><i class="bi bi-calendar me-1"></i>Session</label>
                    <select name="session_year" class="filter-input auto-submit">
                        @foreach($availableSessions ?? [] as $session)
                        <option value="{{ $session }}"
                            {{ request('session_year', $currentSession) == $session ? 'selected' : '' }}>
                            {{ $session }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if(request('search') || request('department_id') || (request('session_year') && request('session_year') !=
            $currentSession))
            <div class="active-filters">
                <span style="font-size: 12px; color: var(--gray-500);">Active:</span>
                @if(request('search'))
                <span class="filter-tag">Search: {{ request('search') }} <i class="bi bi-x-lg"
                        onclick="removeFilter('search')"></i></span>
                @endif
                @if(request('department_id'))
                <span class="filter-tag">Dept:
                    {{ $departments->where('department_id', request('department_id'))->first()->department ?? '' }} <i
                        class="bi bi-x-lg" onclick="removeFilter('department_id')"></i></span>
                @endif
                @if(request('session_year') && request('session_year') != $currentSession)
                <span class="filter-tag">Session: {{ request('session_year') }} <i class="bi bi-x-lg"
                        onclick="removeFilter('session_year')"></i></span>
                @endif
                <a href="{{ route('leaves.quota.management') }}" class="filter-tag"
                    style="background: var(--danger-light); color: var(--danger);">
                    <i class="bi bi-trash3"></i> Clear
                </a>
            </div>
            @endif
        </form>
    </div>

    <!-- Employee Cards Grid -->
    <div class="employees-grid">
        @forelse($employees ?? [] as $employee)
        @php
        $leaveBalances = $employee->leaveBalances ?? collect();
        $totalAllocated = $leaveBalances->sum('total_allocated');
        $totalUsed = $leaveBalances->sum('used');
        $totalRemaining = $leaveBalances->sum('remaining');
        $employeeId = $employee->employee_id;
        $employeeName = addslashes($employee->name);
        @endphp

        <div class="employee-card" onclick="showEmployeeDetails('{{ $employeeId }}', '{{ $employeeName }}')">
            <div class="card-header">
                <div class="employee-avatar">
                    {{ strtoupper(substr($employee->name ?? 'NA', 0, 2)) }}
                </div>
                <div class="employee-info">
                    <div class="employee-name">{{ $employee->name ?? 'N/A' }}</div>
                    <div class="employee-code">{{ $employee->employee_code ?? $employee->employee_id }}</div>
                    <div class="department-tag">
                        <i class="bi bi-building"></i>
                        {{ $employee->department_name ?? $employee->department->department ?? 'No Department' }}
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($leaveBalances->count() > 0)
                <div class="leaves-list">
                    @foreach($leaveBalances as $balance)
                    <div
                        class="leave-tag {{ isset($balance->assignment_source) && $balance->assignment_source == 'department' ? 'leave-tag-department' : 'leave-tag-individual' }}">
                        <i
                            class="bi {{ isset($balance->assignment_source) && $balance->assignment_source == 'department' ? 'bi-building' : 'bi-person' }}"></i>
                        {{ ucfirst(str_replace('_', ' ', $balance->leave_type)) }}
                        <span class="leave-allocated">{{ number_format($balance->total_allocated, 0) }} days</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="no-leaves">
                    <i class="bi bi-calendar-x"></i>
                    <p>No leave quotas assigned</p>
                </div>
                @endif
            </div>

            <div class="card-footer">
                <div class="usage-stats">
                    <div class="usage-item">
                        <span class="usage-label">Allocated:</span>
                        <span class="usage-value">{{ number_format($totalAllocated, 0) }}</span>
                    </div>
                    <div class="usage-item">
                        <span class="usage-label">Used:</span>
                        <span class="usage-value"
                            style="color: var(--warning);">{{ number_format($totalUsed, 0) }}</span>
                    </div>
                    <div class="usage-item">
                        <span class="usage-label">Remaining:</span>
                        <span class="usage-value"
                            style="color: var(--success);">{{ number_format($totalRemaining, 0) }}</span>
                    </div>
                </div>
                <button class="view-detail-btn"
                    onclick="event.stopPropagation(); showEmployeeDetails('{{ $employeeId }}', '{{ $employeeName }}')">
                    <i class="bi bi-eye"></i> View Details
                </button>
            </div>
        </div>
        @empty
        <div class="empty-state" style="grid-column: 1/-1;">
            <div class="empty-icon">
                <i class="bi bi-inbox"></i>
            </div>
            <h4 style="font-size: 18px; font-weight: 600; color: var(--gray-700);">No employees found</h4>
            <p style="color: var(--gray-500);">Try adjusting your filters</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($employees) && method_exists($employees, 'links') && $employees->total() > 0)
    <div class="pagination-modern">
        {{ $employees->appends(request()->query())->links() }}
    </div>
    @endif
</div>
</div>
<!-- Modal -->
<div class="modal fade modern-modal" id="employeeDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-badge me-2"></i>
                    Leave Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="employeeDetailsContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2">Loading details...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function removeFilter(param) {
    const url = new URL(window.location.href);
    url.searchParams.delete(param);
    window.location.href = url.toString();
}

function showEmployeeDetails(employeeId, employeeName) {
    const modal = new bootstrap.Modal(document.getElementById('employeeDetailsModal'));
    const contentDiv = document.getElementById('employeeDetailsContent');

    contentDiv.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2">Loading leave details for ${employeeName}...</p>
        </div>
    `;

    modal.show();

    fetch(`/ajax/employee-leave-details/${employeeId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                contentDiv.innerHTML = data.html;
            } else {
                contentDiv.innerHTML = `
                <div class="text-center py-4 text-danger">
                    <i class="bi bi-exclamation-triangle fs-1"></i>
                    <p class="mt-2">${data.message || 'Failed to load details'}</p>
                </div>
            `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            contentDiv.innerHTML = `
            <div class="text-center py-4 text-danger">
                <i class="bi bi-bug fs-1"></i>
                <p class="mt-2">An error occurred while loading details</p>
            </div>
        `;
        });
}

document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit on filter change
    const autoSubmitElements = document.querySelectorAll('.auto-submit');
    let debounceTimer;

    function submitForm() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    }

    autoSubmitElements.forEach(element => {
        if (element.tagName === 'SELECT') {
            element.addEventListener('change', submitForm);
        } else if (element.tagName === 'INPUT') {
            element.addEventListener('keyup', submitForm);
        }
    });
});
</script>
@endsection
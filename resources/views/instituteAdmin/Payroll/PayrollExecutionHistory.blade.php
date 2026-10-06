@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<title>Payroll Execution History</title>

<style>
    .page-header {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        padding: 20px 25px;
        border-radius: 15px;
        display: flex;
        justify-content: space-between;
    }
    
    .page-header h1 {
        color: white;
        font-weight: 600;
        margin: 0;
        font-size: 28px;
    }
    
    .filter-section {
        background: #f8fafc;
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 25px;
    }
    
    .table-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    
    .status-completed {
        background: #d1fae5;
        color: #059669;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        display: inline-block;
    }
    
    .status-partial {
        background: #fed7aa;
        color: #c2410c;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        display: inline-block;
    }
    
    .status-failed {
        background: #fee2e2;
        color: #dc2626;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        display: inline-block;
    }
    
    .status-processing { 
        background: #dbeafe;
        color: #2563eb;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        display: inline-block;
    }
    
    .date-badge {
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 12px;
        background: #f1f5f9;
    }
    
    .filter-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #475569;
        display: block;
        margin-top: 10px;
    }
    
    .filter-select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.9rem;
        background: white;
    }
    
    .execution-type-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    
    .execution-type-department {
        background: #dbeafe;
        color: #2563eb;
    }
    
    .execution-type-multiple {
        background: #fed7aa;
        color: #c2410c;
    }
    
    .execution-type-individual {
        background: #d1fae5;
        color: #059669;
    }
    
    .employee-info {
        display: flex;
        flex-direction: column;
    }
    .employee-name {
        font-weight: 600;
        color: #1e293b;
    }
    .employee-detail {
        font-size: 0.7rem;
        color: #64748b;
    }
    .department-name {
        font-size: 0.75rem;
        color: #3b82f6;
        margin-top: 2px;
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
    <div class="page-header">
        <h1><i class="fas fa-clock-history"></i> Payroll Execution History</h1>
        <a href="{{ route('payroll.viewPage') }}" class="btn btn-light"><i class="fas fa-arrow-left"></i> Back</a>
    </div>

    <div class="filter-section">
        <form method="GET" action="{{ route('execution.history') }}" class="row" id="filterForm">
            <div class="col-md-3">
                <label class="filter-label"><i class="fas fa-calendar-year"></i> Financial Year</label>
                <select name="financial_year" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Years</option>
                    @foreach($financialYears ?? [] as $fy)
                    <option value="{{ $fy }}" {{ request('financial_year') == $fy ? 'selected' : '' }}>
                        {{ $fy }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="filter-label"><i class="fas fa-calendar-month"></i> Month</label>
                <select name="month" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Months</option>
                    @foreach($months as $m => $mName)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ $mName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="filter-label"><i class="fas fa-building"></i> Department</label>
                <select name="department_id" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Departments</option>
                    @foreach($departmentsList ?? [] as $dept)
                    <option value="{{ $dept->department_id }}" {{ request('department_id') == $dept->department_id ? 'selected' : '' }}>
                        {{ $dept->department }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="filter-label"><i class="fas fa-user"></i> Employee</label>
                <select name="employee_id" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Employees</option>
                    @foreach($employeesList ?? [] as $emp)
                    <option value="{{ $emp->employee_id }}" {{ request('employee_id') == $emp->employee_id ? 'selected' : '' }}>
                        {{ $emp->name }} ({{ $emp->employee_code }})
                    </option>
                    @endforeach
                </select>
            </div>
            <!-- <div class="col-md-2">
                <label class="filter-label"><i class="fas fa-chart-line"></i> Status</label>
                <select name="status" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="partial" {{ $status == 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="failed" {{ $status == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Processing</option>
                </select>
            </div> -->
            <div class="col-md-3">
                <label class="filter-label"><i class="fas fa-chart-line"></i> Status</label>
                <select name="status" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ $status == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Processing</option>
                </select>
            </div>
            <div class="col-md-3" style="place-content: end;">
                <label class="filter-label">&nbsp;</label>
                <a href="{{ route('execution.history') }}" class="btn btn-secondary">
                    <i class="fas fa-undo-alt"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <div class="table-card">
        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
            <table class="erp-table table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="sticky-main-2 sortable">Execution ID</th>
                        <th class="sortable">Financial Year</th>
                        <th class="sortable">Period</th>
                        <th class="sortable">Employee Details</th>
                        <th class="sortable">Department</th>
                        <th class="sortable">Execution Date</th>
                        <th class="sortable">Executed Date</th>
                        <th class="sortable">Type</th>
                        <th class="sortable">Status</th>
                        <th class="d-none">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($executions as $exec)
                    @php
                        $planned = \Carbon\Carbon::parse($exec->planned_execution_date);
                        $actual = \Carbon\Carbon::parse($exec->actual_execution_date);
                        $diffDays = $planned->diffInDays($actual, false);
                        $isLate = $actual->gt($planned);
                        $isEarly = $actual->lt($planned);
                    @endphp
                    <tr>
                        <td class="sticky-main-2">
                            <code>{{ $exec->execution_id }}</code>
                            @if($exec->execution_type == 'department' || $exec->execution_type == 'multiple')
                                <br>
                                <small class="text-muted d-none">Batch ID</small>
                            @endif
                        </td>
                        <td>
                            @if($exec->financial_year)
                                {{ $exec->financial_year }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::create($exec->year, $exec->month, 1)->format('M Y') }}</td>
                        <td>
                            <div class="employee-info">
                                @if($exec->employee_id)
                                    <span class="employee-name">
                                        <i class="fas fa-user-circle text-primary"></i> 
                                        {{ $exec->employee_name ?? 'N/A' }}
                                    </span>
                                    <span class="employee-detail">
                                        <i class="fas fa-id-card"></i> {{ $exec->employee_code ?? 'N/A' }}
                                    </span>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-users"></i> Multiple Employees
                                    </span>
                                    @if($exec->execution_summary && isset($exec->execution_summary['total_employees']))
                                        <span class="employee-detail">
                                            Total: {{ $exec->execution_summary['total_employees'] }} employees
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($exec->department_id)
                                @php
                                    // Try to get department name from relationship or stored data
                                    $deptName = $exec->department ? $exec->department->department : 
                                                (\App\Models\Departments::find($exec->department_id)->department ?? 'N/A');
                                @endphp
                                <i class="fas fa-building text-secondary"></i>
                                <strong>{{ $deptName }}</strong>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div>
                                {{ $planned->format('d M Y') }}
                                @if($isEarly)
                                    <span class="badge bg-success ms-1" style="font-size: 0.65rem;">
                                        {{ abs($diffDays) }}d early
                                    </span>
                                @elseif($isLate)
                                    <span class="badge bg-danger ms-1" style="font-size: 0.65rem;">
                                        {{ $diffDays }}d late
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div>
                                {{ $actual->format('d M Y') }}
                            </div>
                        </td>
                        <td>
                            @if($exec->execution_type == 'department')
                                <span class="execution-type-badge execution-type-department">
                                    <i class="fas fa-building"></i> Department
                                </span>
                            @elseif($exec->execution_type == 'multiple')
                                <span class="execution-type-badge execution-type-multiple">
                                    <i class="fas fa-users"></i> Multiple
                                </span>
                            @else
                                <span class="execution-type-badge execution-type-individual">
                                    <i class="fas fa-user"></i> Individual
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($exec->status == 'completed')
                                <span class="status-completed">
                                    <i class="fas fa-check-circle"></i> Completed
                                </span>
                            @elseif($exec->status == 'partial')
                                <span class="status-partial">
                                    <i class="fas fa-exclamation-triangle"></i> Partial
                                </span>
                            @elseif($exec->status == 'failed')
                                <span class="status-failed">
                                    <i class="fas fa-times-circle"></i> Failed
                                </span>
                            @else
                                <span class="status-processing">
                                    <i class="fas fa-spinner fa-spin"></i> Processing
                                </span>
                            @endif
                        </td>
                        <td class="d-none">
                            <a href="{{ route('execution.view.details', $exec->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="fas fa-history fa-3x mb-3 d-block"></i>
                            No execution records found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Floating Horizontal Scrollbar -->
        <div class="table-scroll-top" id="tableScrollTop">
            <div class="table-scroll-inner"></div>
        </div>
    </div>
    <div class="mt-3 d-flex justify-content-center">{{ $executions->links() }}</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#10b981',
        timer: 3000,
        showConfirmButton: true
    });
    @endif
    
    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session('error') }}',
        confirmButtonColor: '#dc2626'
    });
    @endif
</script>
@endsection
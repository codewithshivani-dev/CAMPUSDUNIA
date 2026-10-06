{{-- resources/views/instituteAdmin/EmployeePromotion/history.blade.php --}}

@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Promotion History - {{ $employee->name }}</title>

<style>
.history-container {
    background: #fff;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

.employee-header {
    display: flex;
    align-items: center;
    gap: 20px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f1f5f9;
    margin-bottom: 25px;
}

.employee-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4361ee, #3a0ca3);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    font-weight: 600;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
}

.stat-card {
    background: #f8fafc;
    border-radius: 10px;
    padding: 15px;
    text-align: center;
    border: 1px solid #e2e8f0;
}

.stat-card .number {
    font-size: 28px;
    font-weight: 700;
    color: #0f172a;
}

.stat-card .label {
    font-size: 12px;
    color: #64748b;
    margin-top: 2px;
}

.history-table {
    width: 100%;
    border-collapse: collapse;
}

.history-table th {
    background: #f1f5f9;
    padding: 12px 15px;
    font-weight: 600;
    color: #0f172a;
    font-size: 13px;
    text-align: left;
    border-bottom: 2px solid #e2e8f0;
}

.history-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
    vertical-align: middle;
}

.history-table tr:hover {
    background-color: #f8fafc;
}

.type-badge {
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

.type-employment {
    background: #dbeafe;
    color: #1e40af;
}

.type-designation {
    background: #dcfce7;
    color: #166534;
}

.type-salary {
    background: #fef3c7;
    color: #92400e;
}

.type-salary-inactivation {
    background: #fee2e2;
    color: #991b1b;
}

.type-salary-assignment {
    background: #d1fae5;
    color: #065f46;
}

.type-other {
    background: #f1f5f9;
    color: #475569;
}

.old-value {
    color: #dc2626;
    text-decoration: line-through;
}

.new-value {
    color: #059669;
    font-weight: 600;
}

.arrow-icon {
    color: #94a3b8;
    margin: 0 5px;
}

.salary-status-badge {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    margin-top: 3px;
}

.salary-status-badge.kept {
    background: #d1fae5;
    color: #065f46;
}

.salary-status-badge.inactivated {
    background: #fee2e2;
    color: #991b1b;
}

.salary-status-badge.assigned {
    background: #dbeafe;
    color: #1e40af;
}

.salary-status-badge.awaiting {
    background: #fef3c7;
    color: #92400e;
}

.salary-status-badge.active {
    background: #dcfce7;
    color: #166534;
}

.salary-view-btn {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 500;
    background: #e0f2fe;
    color: #0369a1;
    border: 1px solid #bae6fd;
    text-decoration: none;
    transition: all 0.2s;
    margin-top: 3px;
}

.salary-view-btn:hover {
    background: #bae6fd;
    color: #0369a1;
    text-decoration: none;
}

.salary-detail-box {
    background: #f8fafc;
    border-radius: 6px;
    padding: 6px 10px;
    margin-top: 4px;
    font-size: 12px;
    border-left: 3px solid #94a3b8;
}

.salary-detail-box.active-box {
    border-left-color: #22c55e;
}

.salary-detail-box.inactive-box {
    border-left-color: #ef4444;
}

.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 20px;
}

.btn-back {
    padding: 10px 25px;
    background: #e2e8f0;
    color: #475569;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}

.btn-back:hover {
    background: #cbd5e1;
    color: #1e293b;
    text-decoration: none;
}

.detail-row {
    display: flex;
    flex-wrap: wrap;
    gap: 5px 15px;
    margin-top: 4px;
}

.detail-item {
    font-size: 12px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 4px;
}

.detail-item i {
    font-size: 11px;
    width: 14px;
}

.promotion-id-badge {
    font-size: 12px;
    font-weight: 600;
    color: #4361ee;
    background: #eef2ff;
    padding: 2px 10px;
    border-radius: 12px;
    display: inline-block;
    white-space: nowrap;
}

@media (max-width: 768px) {
    .employee-header {
        flex-direction: column;
        text-align: center;
    }

    .history-table {
        font-size: 12px;
    }

    .history-table th,
    .history-table td {
        padding: 8px 10px;
    }

    .detail-row {
        flex-direction: column;
        gap: 2px;
    }
}
</style>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="history-container">
                <!-- Header -->
                <div class="employee-header">
                    <div class="employee-avatar">
                        {{ strtoupper(substr($employee->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $employee->name }}</h3>
                        <div class="text-muted">
                            <i class="fas fa-id-badge me-1"></i> {{ $employee->employee_code }} &nbsp;|&nbsp;
                            <i class="fas fa-briefcase me-1"></i> {{ $employee->designation ?? 'N/A' }} &nbsp;|&nbsp;
                            <i class="fas fa-user-tag me-1"></i> {{ $employee->employment_type ?? 'N/A' }}
                        </div>
                        <div class="text-muted small">
                            <i class="fas fa-calendar-alt me-1"></i> Joined:
                            {{ $employee->doj ? \Carbon\Carbon::parse($employee->doj)->format('d-m-Y') : 'N/A' }}
                        </div>
                    </div>
                    <div style="margin-left: auto;">
                        <a href="{{ route('employee.promotion.edit', $employee->id) }}" class="btn-back"
                            style="margin-right: 10px;">
                            <i class="fas fa-edit me-1"></i> Promote
                        </a>
                        <a href="{{ route('employee.promotion.index') }}" class="btn-back">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="number">{{ $stats['total'] }}</div>
                        <div class="label">Total Promotions</div>
                    </div>
                    @foreach($stats['by_type'] as $type)
                    <div class="stat-card">
                        <div class="number">{{ $type->count }}</div>
                        <div class="label">{{ ucfirst(str_replace('_', ' ', $type->promotion_type)) }}</div>
                    </div>
                    @endforeach
                    @if($stats['latest'])
                    <div class="stat-card">
                        <div class="number" style="font-size: 16px;">
                            {{ $stats['latest']->promotion_date ? \Carbon\Carbon::parse($stats['latest']->promotion_date)->format('d-m-Y') : 'N/A' }}
                        </div>
                        <div class="label">Latest Promotion</div>
                    </div>
                    @endif
                </div>

                <!-- History Table -->
                @if($promotionHistory->count() > 0)
                <div class="table-responsive">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Promotion ID</th>
                                <th>Type</th>
                                <th>Details</th>
                                <th>Salary Structure</th>
                                <th>Date</th>
                                <th>Performed By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($promotionHistory as $index => $log)
                            <tr>
                                <td>{{ $promotionHistory->firstItem() + $index }}</td>
                                <td>
                                    <span class="promotion-id-badge">
                                        {{ $log->additional_data['promotion_id'] ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                @php
                                $promotionType = $log->promotion_type ?? 'other';
                                $badgeClass = 'type-other';
                                $displayType = ucfirst(str_replace('_', ' ', $promotionType));
                                
                                // Remove the word "type" from the display if it exists
                                $displayType = str_replace(' Type', '', $displayType);
                                $displayType = str_replace(' type', '', $displayType);
                                
                                if (str_contains($promotionType, 'employment')) {
                                    $badgeClass = 'type-employment';
                                    $displayType = 'Employment'; // Simplified display
                                } elseif (str_contains($promotionType, 'designation')) {
                                    $badgeClass = 'type-designation';
                                    $displayType = 'Designation'; // Simplified display
                                } elseif (str_contains($promotionType, 'salary_inactivation')) {
                                    $badgeClass = 'type-salary-inactivation';
                                    $displayType = 'Salary Inactivation';
                                } elseif (str_contains($promotionType, 'salary_assignment')) {
                                    $badgeClass = 'type-salary-assignment';
                                    $displayType = 'Salary Assignment';
                                } elseif (str_contains($promotionType, 'salary')) {
                                    $badgeClass = 'type-salary';
                                    $displayType = 'Salary';
                                }
                                @endphp
                                <span class="type-badge {{ $badgeClass }}">
                                    {{ $displayType }}
                                </span>
                            </td>
                                <td>
                                    @php
                                    $oldValue = $log->additional_data['old_value'] ?? null;
                                    $newValue = $log->additional_data['new_value'] ?? null;
                                    $type = $log->additional_data['type'] ?? null;
                                    @endphp

                                    @if($oldValue && $newValue)
                                    <span class="old-value">{{ $oldValue }}</span>
                                    <span class="arrow-icon"><i class="fas fa-arrow-right"></i></span>
                                    <span class="new-value">{{ $newValue }}</span>
                                    @elseif($type)
                                    <span>{{ $type }}</span>
                                    @else
                                    <span class="text-muted">Updated</span>
                                    @endif

                                    <!-- Additional Details -->
                                    @php
                                    $additionalDetails = [];
                                    if (isset($log->additional_data['old_employment_type'])) {
                                    $additionalDetails[] = 'Old: ' . $log->additional_data['old_employment_type'];
                                    }
                                    if (isset($log->additional_data['new_employment_type'])) {
                                    $additionalDetails[] = 'New: ' . $log->additional_data['new_employment_type'];
                                    }
                                    if (isset($log->additional_data['old_designation'])) {
                                    $additionalDetails[] = 'Old: ' . $log->additional_data['old_designation'];
                                    }
                                    if (isset($log->additional_data['new_designation'])) {
                                    $additionalDetails[] = 'New: ' . $log->additional_data['new_designation'];
                                    }
                                    if (isset($log->additional_data['probation_days'])) {
                                    $additionalDetails[] = 'Probation: ' . $log->additional_data['probation_days'] . '
                                    days';
                                    }
                                    if (isset($log->additional_data['effective_from'])) {
                                    $additionalDetails[] = 'Effective: ' .
                                    \Carbon\Carbon::parse($log->additional_data['effective_from'])->format('d-m-Y');
                                    }
                                    @endphp

                                    @if(!empty($additionalDetails))
                                    <div class="detail-row">
                                        @foreach($additionalDetails as $detail)
                                        <span class="detail-item">
                                            <i class="fas fa-circle" style="font-size: 4px; color: #94a3b8;"></i>
                                            {{ $detail }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @endif
                                </td>

                                <td>
                                    @php
                                    $salaryKept = $log->additional_data['salary_structure_kept'] ?? null;
                                    $inactivatedId = $log->inactivated_salary_structure_id ?? null;
                                    $salaryBefore = $log->additional_data['salary_structure_before'] ?? null;
                                    $salaryAfter = $log->additional_data['salary_structure_after'] ?? null;
                                    $promotionType = $log->promotion_type ?? 'other';
                                    $salaryStatus = $log->additional_data['salary_structure_status'] ??
                                    'not_applicable';
                                    $statusMessage = $log->additional_data['salary_structure_status_message'] ?? 'No
                                    salary structure change';
                                    $needsNewStructure = $log->additional_data['needs_new_salary_structure'] ?? false;

                                    // Determine if salary structure was inactive
                                    $wasInactivated = ($salaryStatus === 'inactivated') ||
                                    ($inactivatedId !== null) ||
                                    (isset($salaryAfter['is_active']) && $salaryAfter['is_active'] === false) ||
                                    (isset($salaryBefore['is_active']) && $salaryBefore['is_active'] === true &&
                                    isset($salaryAfter['is_active']) && $salaryAfter['is_active'] === false);

                                    // Determine if salary structure actually exists
                                    $hasSalaryStructure = ($salaryBefore || $salaryAfter);
                                    
                                    // Get the active structure for view
                                    $activeStructure = $salaryAfter ?: $salaryBefore;
                                    $structureId = $activeStructure['salary_structure_id'] ?? null;
                                    $isActive = $activeStructure['is_active'] ?? false;
                                    $ctc = $activeStructure['total_ctc_annual'] ?? null;
                                    $basicMonthly = $activeStructure['basic_salary_monthly'] ?? null;
                                    @endphp

                                    <!-- Salary Structure Status -->
                                    @if($wasInactivated || $salaryStatus === 'inactivated')
                                        <span class="salary-status-badge inactivated">
                                            <i class="fas fa-times-circle"></i> Inactive
                                        </span>
                                        
                                       
                                        
                                        @if($ctc)
                                            <div class="salary-detail-box inactive-box" style="margin-top: 4px;">
                                                 <div><strong>Id:</strong> #{{ $structureId }}</div>
                                                <div><strong>CTC:</strong> ₹{{ number_format($ctc, 2) }}</div>
                                              
                                                
                                            </div>
                                        @endif

                                         @if($structureId)
                                            <div style="margin-top: 4px;">
                                                <a href="{{ url('/institute/admin/payroll/salary-details/' . $structureId) }}"
                                                   class="salary-view-btn" target="_blank">
                                                    <i class="fas fa-eye"></i> View  
                                                </a>
                                            </div>
                                        @endif
                                        
                                        @if($inactivatedId && !$structureId)
                                            <div style="font-size: 11px; color: #991b1b; margin-top: 2px;">
                                                Structure #{{ $inactivatedId }} was inactivated
                                            </div>
                                        @endif

                                    @elseif($salaryStatus === 'kept_active' || $salaryStatus === 'assigned' || ($hasSalaryStructure && !$wasInactivated))
                                        <span class="salary-status-badge active">
                                            <i class="fas fa-check-circle"></i> Active
                                        </span>
                                        
                                     
                                        
                                        @if($ctc)
                                            <div class="salary-detail-box active-box" style="margin-top: 4px;">
                                                <div><strong>Id:</strong> #{{ $structureId }}</div>
                                                <div><strong>CTC:</strong> ₹{{ number_format($ctc, 2) }}</div>
                                              
                                                @if($salaryStatus === 'assigned')
                                                    <div style="font-size: 11px; color: #065f46; margin-top: 2px;">
                                                        <i class="fas fa-check-circle"></i> Assigned on: {{ $log->promotion_date ? \Carbon\Carbon::parse($log->promotion_date)->format('d-m-Y H:i') : 'N/A' }}
                                                    </div>
                                              
                                                @endif
                                            </div>
                                        @endif

                                           @if($structureId)
                                            <div style="margin-top: 4px;">
                                                <a href="{{ url('/institute/admin/payroll/salary-details/' . $structureId) }}"
                                                   class="salary-view-btn" target="_blank">
                                                    <i class="fas fa-eye"></i> View  
                                                </a>
                                            </div>
                                        @endif

                                    @else
                                        <!-- No salary structure change -->
                                        <span style="font-size: 12px; color: #94a3b8;">
                                            <i class="fas fa-minus-circle"></i> No Change
                                        </span>
                                        @if($hasSalaryStructure && $structureId)
                                            <div style="margin-top: 4px;">
                                                <a href="{{ url('/institute/admin/payroll/salary-details/' . $structureId) }}"
                                                   class="salary-view-btn" target="_blank">
                                                    <i class="fas fa-eye"></i> View 
                                                </a>
                                            </div>
                                            @if($ctc)
                                                <div class="salary-detail-box" style="margin-top: 4px; border-left-color: #94a3b8;">
                                                    <div><strong>CTC:</strong> ₹{{ number_format($ctc, 2) }}</div>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                </td>
                                
                                <td>
                                    <div style="white-space: nowrap;">
                                        {{ $log->promotion_date ? \Carbon\Carbon::parse($log->promotion_date)->format('d-m-Y') : 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $log->promoted_by ?? 'System' }}</div>
                                    @if($log->promoted_by_user_id)
                                    <div class="d-none" style="font-size: 11px; color: #94a3b8;">ID: {{ $log->promoted_by_user_id }}
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($promotionHistory->hasPages())
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Showing {{ $promotionHistory->firstItem() ?? 0 }} to {{ $promotionHistory->lastItem() ?? 0 }} of
                        {{ $promotionHistory->total() }} results
                    </div>
                    <div>
                        {{ $promotionHistory->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                @endif

                @else
                <div class="text-center py-5">
                    <i class="fas fa-history" style="font-size: 48px; color: #cbd5e1;"></i>
                    <h4 class="mt-3">No Promotion History</h4>
                    <p class="text-muted">This employee has no promotion or update history yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
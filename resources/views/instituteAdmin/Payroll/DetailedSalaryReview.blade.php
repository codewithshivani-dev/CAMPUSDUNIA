@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<title>Review Salary</title>

<style>
:root {
    --primary: #4361ee;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --secondary: #64748b;
    --light: #f8fafc;
    --border: #e2e8f0;
}

/* Header */
.page-header {
    background: linear-gradient(135deg, var(--primary), #3a0ca3);
    color: white;
    padding: 25px 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.page-header h1 {
    font-size: 24px;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.btn {
    width: max-content;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Employee Card */
.employee-header {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 25px;
    flex-wrap: wrap;
}

.employee-avatar {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary), #3a0ca3);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    font-weight: 700;
}

.employee-info h2 {
    font-size: 22px;
    margin-bottom: 8px;
    color: #1e293b;
}

.employee-info p {
    margin: 4px 0;
    color: var(--secondary);
    font-size: 13px;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-badge.pending {
    background: #fef3c7;
    color: #d97706;
}

.status-badge.reviewed {
    background: #dbeafe;
    color: #2563eb;
}

.status-badge.finalized {
    background: #d1fae5;
    color: #059669;
}

/* Sections */
.section {
    background: white;
    border-radius: 12px;
    border: 1px solid var(--border);
    overflow: hidden;
    margin-bottom: 25px;
}

.section-header {
    background: var(--light);
    padding: 15px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    font-size: 16px;
}

.section-header i {
    color: var(--primary);
    font-size: 18px;
}

.section-content {
    padding: 20px;
}

/* Salary Cards */
.salary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.salary-card {
    padding: 18px;
    border-radius: 10px;
    text-align: center;
    border: 1px solid var(--border);
    background: var(--light);
}

.salary-card.basic {
    border-top: 3px solid var(--primary);
}

.salary-card.gross {
    border-top: 3px solid var(--warning);
}

.salary-card.net {
    border-top: 3px solid var(--success);
}

.salary-card.payable {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border: none;
}

.salary-card-value {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 5px;
}

.salary-card-label {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

/* Breakdown Tables */
.breakdown-table {
    width: 100%;
    margin-bottom: 20px;
}

.breakdown-table th {
    text-align: left;
    padding: 12px;
    background: var(--light);
    font-weight: 600;
    font-size: 13px;
}

.breakdown-table td {
    padding: 10px 12px;
    border-bottom: 1px solid var(--border);
}

.breakdown-table tr:last-child td {
    border-bottom: none;
}

.breakdown-label {
    font-weight: 500;
}

.breakdown-value {
    text-align: right;
    font-weight: 600;
}

.breakdown-value.positive {
    color: var(--success);
}

.breakdown-value.negative {
    color: var(--danger);
}

.breakdown-total {
    background: #f0fdf4;
    font-weight: 700;
}

.breakdown-total td {
    border-top: 2px solid var(--border);
    padding: 12px;
    font-weight: 700;
}

/* Two Column Layout */
.two-column {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
}

/* Attendance Grid */
.attendance-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}

.attendance-item {
    background: var(--light);
    padding: 12px;
    border-radius: 8px;
    text-align: center;
}

.attendance-item-value {
    font-size: 20px;
    font-weight: 700;
    color: var(--primary);
}

.attendance-item-label {
    font-size: 11px;
    color: var(--secondary);
}

/* Leave Items */
.leave-item {
    background: var(--light);
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 10px;
    border-left: 3px solid var(--warning);
}

.leave-quota-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background: var(--light);
    border-radius: 8px;
    margin-bottom: 8px;
}

/* Sidebar */
.sidebar-section {
    background: white;
    border-radius: 12px;
    border: 1px solid var(--border);
    padding: 20px;
    margin-bottom: 20px;
}

.sidebar-section h4 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--border);
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid var(--border);
    font-size: 13px;
}

.info-row:last-child {
    border-bottom: none;
}

/* Actions */
.btn-finalize {
    background: linear-gradient(135deg, var(--success), #059669);
    color: white !important;
    flex: 1;
    justify-content: center;
}

.btn-review {
    background: linear-gradient(135deg, var(--primary), #3a0ca3);
    color: white;
    flex: 1;
    justify-content: center;
}

.btn-back {
    background: var(--border);
    color: var(--secondary);
}

/* Loader */
.loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.loader-overlay.active {
    display: flex;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #e2e8f0;
    border-top: 4px solid var(--primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.actions-section{
    width: 100%;
    display: flex;
    justify-content: space-between;
}
</style>

<div class="container-fluid container-custom">
    <!-- Page Header -->
    <div class="page-header">
        <h1>Review Salary</h1>
        <div>
            <a href="{{ route('salary.review.index', ['year' => $request->year, 'month' => $request->month]) }}"
                class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <button class="btn btn-secondary d-none" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>

    <!-- Employee Header -->
    <div class="employee-header">
        <div class="employee-avatar">{{ strtoupper(substr($employee->name, 0, 2)) }}</div>
        <div class="employee-info">
            <h2>{{ $employee->name }}</h2>
            <p><strong>Employee Code:</strong> {{ $employee->employee_code }} | <strong>Department:</strong>
                {{ $employee->department_name ?? 'N/A' }}</p>
            <p><strong>Salary Month:</strong> {{ $monthName }}</p>
            <span class="status-badge {{ $reviewStatus }}">{{ ucfirst($reviewStatus) }}</span>
        </div>
    </div>

    <div class="content-grid">
        <div>
            <!-- Attendance Summary -->
            @if($attendanceReview)
            <div class="section">
                <div class="section-header"><i class="fas fa-calendar-check"></i> Attendance Summary</div>
                <div class="section-content">
                    <div class="attendance-grid">
                        <div class="attendance-item">
                            <div class="attendance-item-value">{{ $attendanceReview->present_days ?? 0 }}</div>
                            <div class="attendance-item-label">Present</div>
                        </div>
                        <div class="attendance-item">
                            <div class="attendance-item-value">{{ $attendanceReview->absent_days ?? 0 }}</div>
                            <div class="attendance-item-label">Absent</div>
                        </div>
                        <div class="attendance-item">
                            <div class="attendance-item-value">{{ $attendanceReview->leave_days ?? 0 }}</div>
                            <div class="attendance-item-label">Leave</div>
                        </div>
                        <div class="attendance-item">
                            <div class="attendance-item-value">
                                {{ number_format($attendanceReview->attendance_percentage ?? 0, 1) }}%</div>
                            <div class="attendance-item-label">Attendance %</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Payroll Execution Details -->
            @if(!empty($payrollExecutionDetails))
            <div class="section">
                <div class="section-header"><i class="fas fa-chart-line"></i> Payroll Execution Details</div>
                <div class="section-content">
                    <div style="background: #f0fdf4; border-radius: 8px; padding: 15px;">
                        <div class="info-row"
                            style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                            <span><i class="fas fa-calendar-alt"></i> Financial Year:</span>
                            <strong>{{ $payrollExecutionDetails['financial_year'] ?? 'N/A' }}</strong>
                        </div>
                        <div class="info-row"
                            style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                            <span><i class="fas fa-sync-alt"></i> Payroll Cycle:</span>
                            <strong>
                                @if(($payrollExecutionDetails['payroll_cycle'] ?? '') == 'days')
                                Daily Cycle ({{ $payrollExecutionDetails['cycle_days'] ?? 0 }} days)
                                @else
                                Monthly Cycle
                                @endif
                            </strong>
                        </div>
                        <div class="info-row"
                            style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                            <span><i class="fas fa-calendar-day"></i> Execution Day:</span>
                            <strong>{{ $payrollExecutionDetails['execution_day'] ?? 'End of Month' }} of month</strong>
                        </div>
                        <div class="info-row" style="display: flex; justify-content: space-between; padding: 8px 0;">
                            <span><i class="fas fa-money-bill-wave"></i> Pay Date:</span>
                            <strong
                                class="text-success">{{ $payrollExecutionDetails['pay_date'] ?? 'End of Month' }}</strong>
                        </div>
                    </div>
                    @if(!empty($payrollExecutionDetails['calculation_basis']))
                    <div class="alert alert-info mt-3" style="background: #e0f2fe; font-size: 12px;">
                        <i class="fas fa-info-circle"></i> {{ $payrollExecutionDetails['calculation_basis'] }}
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Salary Cards -->
            <div class="section">
                <div class="section-header"><i class="fas fa-chart-line"></i> Salary Summary</div>
                <div class="section-content">
                    <div class="salary-cards">
                        <div class="salary-card basic">
                            <div class="salary-card-value">₹{{ number_format($salaryData['basic_salary'] ?? 0, 2) }}
                            </div>
                            <div class="salary-card-label">Monthly Basic Salary</div>
                        </div>
                        <div class="salary-card gross">
                            <div class="salary-card-value">₹{{ number_format($salaryData['gross_salary'] ?? 0, 2) }}
                            </div>
                            <div class="salary-card-label">Monthly Gross Salary</div>
                        </div>
                        <div class="salary-card net">
                            <div class="salary-card-value">
                                ₹{{ number_format($salaryData['monthly_net_salary'] ?? 0, 2) }}</div>
                            <div class="salary-card-label">Monthly Net Salary</div>
                        </div>
                        <div class="salary-card deduction">
                            <div class="salary-card-value">
                                ₹{{ number_format($salaryData['attendance_deductions_total'] ?? 0, 2) }}</div>
                            <div class="salary-card-label">Attendance Deductions</div>
                        </div>
                        <div class="salary-card payable">
                            <div class="salary-card-value">₹{{ number_format($salaryData['payable_salary'] ?? 0, 2) }}
                            </div>
                            <div class="salary-card-label">Final Payable Salary</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EARNINGS BREAKDOWN -->
            <div class="section">
                <div class="section-header"><i class="fas fa-plus-circle"></i> Earnings Breakdown</div>
                <div class="section-content">
                    <table class="breakdown-table">
                        <thead>
                            <tr>
                                <th>Earning Component</th>
                                <th class="breakdown-value">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $earningsTotal = 0; @endphp
                            @if(!empty($salaryData['earnings_breakdown']))
                            @foreach($salaryData['earnings_breakdown'] as $key => $value)
                            @php
                            $earningsTotal += $value;
                            $displayName = ucwords(str_replace(['_', '-'], ' ', $key));
                            @endphp
                            <tr>
                                <td class="breakdown-label">{{ $displayName }}</td>
                                <td class="breakdown-value positive">+ ₹{{ number_format($value, 2) }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="2" class="text-muted">No earnings data available</td>
                            </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="breakdown-total">
                                <td><strong>Total Earnings (Gross Salary)</strong></td>
                                <td class="breakdown-value positive"><strong>+
                                        ₹{{ number_format($earningsTotal, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- STANDARD DEDUCTIONS SECTION -->
            <div class="section">
                <div class="section-header"><i class="fas fa-building"></i> Standard Deductions (Statutory)</div>
                <div class="section-content">
                    <table class="breakdown-table">
                        <thead>
                            <tr>
                                <th>Deduction Type</th>
                                <th class="breakdown-value">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $standardTotal = 0; @endphp
                            @if(!empty($salaryData['standard_deductions_breakdown']))
                            @foreach($salaryData['standard_deductions_breakdown'] as $key => $value)
                            @if($value > 0)
                            @php
                            $standardTotal += $value;
                            $displayName = ucwords(str_replace(['_', '-'], ' ', $key));
                            @endphp
                            <tr>
                                <td class="breakdown-label">{{ $displayName }}</td>
                                <td class="breakdown-value negative">- ₹{{ number_format($value, 2) }}</td>
                            </tr>
                            @endif
                            @endforeach
                            @endif

                            @if($standardTotal == 0)
                            <tr>
                                <td colspan="2" class="text-muted">No standard deductions configured</td>
                            </tr>
                            @endif
                        </tbody>
                        @if($standardTotal > 0)
                        <tfoot>
                            <tr class="breakdown-total">
                                <td><strong>Total Standard Deductions</strong></td>
                                <td class="breakdown-value negative"><strong>-
                                        ₹{{ number_format($standardTotal, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            <!-- ATTENDANCE DEDUCTIONS SECTION with detailed leave breakdown -->
            <div class="section">
                <div class="section-header"><i class="fas fa-clock"></i> Attendance Deductions</div>
                <div class="section-content">
                    <table class="breakdown-table">
                        <thead>
                            <tr>
                                <th>Deduction Type</th>
                                <th class="breakdown-value">Days/Hours</th>
                                <th class="breakdown-value">Deduction %</th>
                                <th class="breakdown-value">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $attendanceTotal = 0;
                            $leaveBreakdown = $salaryData['leave_breakdown'] ?? [];
                            $attendanceDeductionsBreakdown = $salaryData['attendance_deductions_breakdown'] ?? [];

                            // Track which leave types have been shown from leave_breakdown
                            $shownLeaveTypes = [];
                            @endphp

                            <!-- Absent Deduction -->
                            @if(($salaryData['absent_deduction'] ?? 0) > 0)
                            @php $attendanceTotal += $salaryData['absent_deduction']; @endphp
                            <tr>
                                <td class="breakdown-label">❌ Absent</td>
                                <td class="breakdown-value">{{ $attendanceReview->absent_days ?? 0 }} day(s)</td>
                                <td class="breakdown-value">100%</td>
                                <td class="breakdown-value negative">-
                                    ₹{{ number_format($salaryData['absent_deduction'], 2) }}</td>
                            </tr>
                            @endif

                            <!-- Short Leave (Within Quota) - Auto detected from attendance -->
                            @if(($salaryData['short_leaves_within_quota_deduction'] ?? 0) > 0)
                            @php
                            $shortWithinCount = $salaryData['short_leaves_within_quota'] ?? 0;
                            $shortWithinDeduction = $salaryData['short_leaves_within_quota_deduction'] ?? 0;
                            $attendanceTotal += $shortWithinDeduction;
                            @endphp
                            <tr>
                                <td class="breakdown-label">⏱️ Short Leave (Approved)</td>
                                <td class="breakdown-value">{{ $shortWithinCount }} instance(s)</td>
                                <td class="breakdown-value">25%</td>
                                <td class="breakdown-value negative">- ₹{{ number_format($shortWithinDeduction, 2) }}
                                </td>
                            </tr>
                            @endif

                            <!-- Short Leave (Exceeded Quota) -->
                            <!-- Short Leave (Exceeded Quota) -->
                                    @if(($salaryData['short_leaves_exceeded_deduction'] ?? 0) > 0)
                                    @php
                                    $shortExceededCount = $salaryData['short_leaves_exceeded'] ?? 0;
                                    $shortExceededDeduction = $salaryData['short_leaves_exceeded_deduction'] ?? 0;
                                    $dailyRate = $salaryData['daily_rate'] ?? 0;
                                    // Calculate percentage from deduction amount
                                    $shortExceededPercentage = $dailyRate > 0 ? round(($shortExceededDeduction / ($dailyRate * $shortExceededCount)) * 100, 0) : 50;
                                    $attendanceTotal += $shortExceededDeduction;
                                    @endphp
                                    <tr>
                                        <td class="breakdown-label">⏱️ Short Leave (Unapproved)</td>
                                        <td class="breakdown-value">{{ $shortExceededCount }} instance(s)</td>
                                        <td class="breakdown-value">{{ $shortExceededPercentage }}%</td>
                                        <td class="breakdown-value negative">- ₹{{ number_format($shortExceededDeduction, 2) }}</td>
                                    </tr>
                                    @endif

                            <!-- Half Day (Within Quota) - Auto detected from attendance -->
                            @if(($salaryData['half_days_within_quota_deduction'] ?? 0) > 0)
                            @php
                            $halfWithinCount = $salaryData['half_days_within_quota'] ?? 0;
                            $halfWithinDeduction = $salaryData['half_days_within_quota_deduction'] ?? 0;
                            $attendanceTotal += $halfWithinDeduction;
                            @endphp
                            <tr>
                                <td class="breakdown-label">🌓 Half Day (Approved)</td>
                                <td class="breakdown-value">{{ $halfWithinCount }} instance(s)</td>
                                <td class="breakdown-value">50%</td>
                                <td class="breakdown-value negative">- ₹{{ number_format($halfWithinDeduction, 2) }}
                                </td>
                            </tr>
                            @endif

                            <!-- Half Day (Exceeded Quota) -->
                            @if(($salaryData['half_days_exceeded_deduction'] ?? 0) > 0)
                            @php
                            $halfExceededCount = $salaryData['half_days_exceeded'] ?? 0;
                            $halfExceededDeduction = $salaryData['half_days_exceeded_deduction'] ?? 0;
                            $dailyRate = $salaryData['daily_rate'] ?? 0;
                            // Calculate percentage from deduction amount
                            $halfExceededPercentage = $dailyRate > 0 ? round(($halfExceededDeduction / ($dailyRate * $halfExceededCount)) * 100, 0) : 100;
                            $attendanceTotal += $halfExceededDeduction;
                            @endphp
                            <tr>
                                <td class="breakdown-label">🌓 Half Day (Unapproved)</td>
                                <td class="breakdown-value">{{ $halfExceededCount }} instance(s)</td>
                                <td class="breakdown-value">{{ $halfExceededPercentage }}%</td>
                                <td class="breakdown-value negative">- ₹{{ number_format($halfExceededDeduction, 2) }}</td>
                            </tr>
                            @endif

                            <!-- Short Attendance Deduction -->
                            @if(($salaryData['short_attendance_deduction'] ?? 0) > 0)
                            @php $attendanceTotal += $salaryData['short_attendance_deduction']; @endphp
                            <tr>
                                <td class="breakdown-label">⚠️ Short Attendance (Very Poor Attendance)</td>
                                <td class="breakdown-value">{{ $attendanceReview->short_attendance_days ?? 0 }} day(s)
                                </td>
                                <td class="breakdown-value">100%</td>
                                <td class="breakdown-value negative">-
                                    ₹{{ number_format($salaryData['short_attendance_deduction'], 2) }}</td>
                            </tr>
                            @endif

                            <!-- FULL DAY LEAVES - Only from leave_breakdown (Casual, Sick, Earned, etc.) -->
                            <!-- Skip Short Leave and Half Day from leave_breakdown since they are already shown above -->
                            @if(!empty($leaveBreakdown) && is_array($leaveBreakdown))
                            @foreach($leaveBreakdown as $leave)
                            @php
                            $leaveType = $leave['leave_type'] ?? 'Leave';
                            $deductionPercentage = $leave['deduction_percentage'] ?? 0;
                            $deductionAmount = $leave['deduction_amount'] ?? 0;
                            $daysToDeduct = $leave['days_to_deduct'] ?? 1;

                            // SKIP Short Leave and Half Day - they are already shown above
                            $lowerLeaveType = strtolower($leaveType);
                            if (strpos($lowerLeaveType, 'short') !== false || strpos($lowerLeaveType, 'half') !== false)
                            {
                            continue;
                            }

                            if($deductionAmount > 0) $attendanceTotal += $deductionAmount;

                            // Determine icon based on leave type
                            $icon = '📅';
                            if (strpos($lowerLeaveType, 'casual') !== false) {
                            $icon = '🏖️';
                            } elseif (strpos($lowerLeaveType, 'sick') !== false) {
                            $icon = '🤒';
                            } elseif (strpos($lowerLeaveType, 'earned') !== false) {
                            $icon = '⭐';
                            } elseif (strpos($lowerLeaveType, 'maternity') !== false) {
                            $icon = '👶';
                            } elseif (strpos($lowerLeaveType, 'unpaid') !== false) {
                            $icon = '💰';
                            }

                            // Determine display text
                            if ($deductionPercentage >= 100) {
                            $displayText = $leaveType . ' (Unpaid - 100% deduction)';
                            } elseif ($deductionPercentage > 0) {
                            $displayText = $leaveType . ' (' . $deductionPercentage . '% deduction)';
                            } else {
                            $displayText = $leaveType . ' (Approved - No Deduction)';
                            }
                            @endphp
                            <tr>
                                <td class="breakdown-label">{{ $icon }} {{ $displayText }}</td>
                                <td class="breakdown-value">{{ $daysToDeduct }} day(s)</td>
                                <td class="breakdown-value">{{ $deductionPercentage }}%</td>
                                <td class="breakdown-value negative">
                                    @if($deductionAmount > 0)
                                    - ₹{{ number_format($deductionAmount, 2) }}
                                    @else
                                    ₹0.00
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif

                            @if($attendanceTotal == 0)
                            <tr>
                                <td colspan="4" class="text-muted">No attendance deductions applied</td>
                            </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="breakdown-total">
                                <td colspan="3"><strong>Total Attendance Deductions</strong></td>
                                <td class="breakdown-value negative"><strong>-
                                        ₹{{ number_format($attendanceTotal, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- OTHER DEDUCTIONS SECTION - Only show if enabled in payroll policy -->
            @if(($enabledOtherDeductions['loan_enabled'] ?? false) || ($enabledOtherDeductions['advance_enabled'] ??
            false) || !empty($enabledOtherDeductions['custom_deductions']) || ($salaryData['other_deductions'] ?? 0) >
            0)
            <div class="section">
                <div class="section-header"><i class="fas fa-plus-circle"></i> Other Deductions</div>
                <div class="section-content">
                    <table class="breakdown-table">
                        <thead>
                            <tr>
                                <th>Deduction Type</th>
                                <th class="breakdown-value">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $otherDeductionsTotal = 0; @endphp

                            <!-- Loan Deduction (if enabled in policy) -->
                            @if($enabledOtherDeductions['loan_enabled'] ?? false)
                            @php
                            $loanAmount = 0;
                            if(!empty($salaryData['other_deductions_details'])) {
                            foreach($salaryData['other_deductions_details'] as $ded) {
                            if(isset($ded['type']) && $ded['type'] === 'loan') {
                            $loanAmount = $ded['amount'] ?? 0;
                            break;
                            }
                            }
                            }
                            $otherDeductionsTotal += $loanAmount;
                            @endphp
                            <tr>
                                <td class="breakdown-label">💰 Loan Deduction</td>
                                <td class="breakdown-value negative">
                                    @if($reviewStatus !== 'finalized')
                                    <input type="number" class="form-control other-deduction-input"
                                        data-deduction-type="loan" data-deduction-name="Loan Deduction"
                                        value="{{ $loanAmount }}" step="0.01" style="width: 120px; text-align: right;">
                                    @else
                                    - ₹{{ number_format($loanAmount, 2) }}
                                    @endif
                                </td>
                            </tr>
                            @endif

                            <!-- Advance Deduction (if enabled in policy) -->
                            @if($enabledOtherDeductions['advance_enabled'] ?? false)
                            @php
                            $advanceAmount = 0;
                            if(!empty($salaryData['other_deductions_details'])) {
                            foreach($salaryData['other_deductions_details'] as $ded) {
                            if(isset($ded['type']) && $ded['type'] === 'advance') {
                            $advanceAmount = $ded['amount'] ?? 0;
                            break;
                            }
                            }
                            }
                            $otherDeductionsTotal += $advanceAmount;
                            @endphp
                            <tr>
                                <td class="breakdown-label">🏦 Advance Salary Deduction</td>
                                <td class="breakdown-value negative">
                                    @if($reviewStatus !== 'finalized')
                                    <input type="number" class="form-control other-deduction-input"
                                        data-deduction-type="advance" data-deduction-name="Advance Salary"
                                        value="{{ $advanceAmount }}" step="0.01"
                                        style="width: 120px; text-align: right;">
                                    @else
                                    - ₹{{ number_format($advanceAmount, 2) }}
                                    @endif
                                </td>
                            </tr>
                            @endif

                            <!-- Custom Deductions (if enabled in policy) -->
                            @if(!empty($enabledOtherDeductions['custom_deductions']) &&
                            is_array($enabledOtherDeductions['custom_deductions']))
                            @foreach($enabledOtherDeductions['custom_deductions'] as $customIndex => $custom)
                            @php
                            $customAmount = 0;
                            if(!empty($salaryData['other_deductions_details'])) {
                            foreach($salaryData['other_deductions_details'] as $ded) {
                            if(isset($ded['type']) && $ded['type'] === 'custom' && isset($ded['custom_index']) &&
                            $ded['custom_index'] == $customIndex) {
                            $customAmount = $ded['amount'] ?? 0;
                            break;
                            }
                            }
                            }
                            $otherDeductionsTotal += $customAmount;
                            @endphp
                            <tr>
                                <td class="breakdown-label">📋 {{ $custom['name'] ?? 'Custom Deduction' }}</td>
                                <td class="breakdown-value negative">
                                    @if($reviewStatus !== 'finalized')
                                    <input type="number" class="form-control other-deduction-input"
                                        data-deduction-type="custom"
                                        data-deduction-name="{{ $custom['name'] ?? 'Custom Deduction' }}"
                                        data-custom-index="{{ $customIndex }}" value="{{ $customAmount }}" step="0.01"
                                        style="width: 120px; text-align: right;">
                                    @else
                                    - ₹{{ number_format($customAmount, 2) }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif

                            <!-- Display existing other deductions that are not from policy (for backward compatibility) -->
                            @if(!empty($salaryData['other_deductions_details']))
                            @foreach($salaryData['other_deductions_details'] as $ded)
                            @if(!in_array($ded['type'] ?? '', ['loan', 'advance', 'custom']))
                            @php $otherDeductionsTotal += $ded['amount'] ?? 0; @endphp
                            <tr>
                                <td class="breakdown-label">{{ $ded['name'] ?? 'Other Deduction' }}</td>
                                <td class="breakdown-value negative">
                                    @if($reviewStatus !== 'finalized')
                                    <input type="number" class="form-control other-deduction-input"
                                        data-deduction-type="manual"
                                        data-deduction-name="{{ $ded['name'] ?? 'Other Deduction' }}"
                                        value="{{ $ded['amount'] ?? 0 }}" step="0.01"
                                        style="width: 120px; text-align: right;">
                                    @else
                                    - ₹{{ number_format($ded['amount'] ?? 0, 2) }}
                                    @endif
                                </td>
                            </tr>
                            @endif
                            @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="breakdown-total">
                                <td><strong>Total Other Deductions</strong></td>
                                <td class="breakdown-value negative">
                                    <strong id="totalOtherDeductionsDisplay">-
                                        ₹{{ number_format($otherDeductionsTotal, 2) }}</strong>
                                    <input type="hidden" id="totalOtherDeductionsValue"
                                        value="{{ $otherDeductionsTotal }}">
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    @if($reviewStatus !== 'finalized')
                    <div class="alert alert-info mt-3" style="background: #e0f2fe; font-size: 13px;">
                        <i class="fas fa-info-circle"></i>
                        Enter the deduction amounts for the enabled deduction types above. These will be subtracted from
                        the payable salary.
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- FINAL CALCULATION SUMMARY -->
            <div class="section">
                <div class="section-header"><i class="fas fa-calculator"></i> Final Salary Calculation</div>
                <div class="section-content">
                    <table class="breakdown-table" id="finalCalculationTable">
                        <tbody>
                            <tr>
                                <td class="breakdown-label">Gross Salary (Total Earnings)</td>
                                <td class="breakdown-value positive" id="calc-gross-value">+
                                    ₹{{ number_format($salaryData['gross_salary'] ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="breakdown-label">Less: Standard Deductions (PF, ESI, PT, TDS)</td>
                                <td class="breakdown-value negative" id="calc-standard-value">-
                                    ₹{{ number_format($standardTotal ?? 0, 2) }}</td>
                            </tr>
                            <tr style="background: #f8fafc;">
                                <td class="breakdown-label"><strong>= Net Salary (After Standard Deductions)</strong>
                                </td>
                                <td class="breakdown-value" id="calc-net-standard-value">
                                    <strong>₹{{ number_format(($salaryData['gross_salary'] ?? 0) - ($standardTotal ?? 0), 2) }}</strong>
                                </td>
                            </tr>
                            <!-- Attendance Deductions Summary Row -->
                            <tr>
                                <td class="breakdown-label">Less: Attendance Deductions</td>
                                <td class="breakdown-value negative" id="calc-attendance-value">-
                                    ₹{{ number_format($attendanceTotal ?? 0, 2) }}</td>
                            </tr>
                            <!-- Other Deductions row will be inserted here dynamically -->
                            <tr class="breakdown-total" id="finalPayableRow">
                                <td class="breakdown-label"><strong>Final Payable Salary</strong></td>
                                <td class="breakdown-value positive" id="finalPayableValue">
                                    <strong>₹{{ number_format(($salaryData['payable_salary'] ?? 0), 2) }}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Leave Details -->
            <!-- Full Day Leave Deductions (Approved vs Unapproved) -->
            @if(!empty($salaryData['leave_breakdown']) && is_array($salaryData['leave_breakdown']))
            <div class="section">
                <div class="section-header"><i class="fas fa-calendar-times"></i> Leave Details</div>
                <div class="section-content">
                    <table class="breakdown-table">
                        <thead>
                            <tr>
                                <th>Leave Type</th>
                                <th>Date Range</th>
                                <th class="breakdown-value">Days</th>
                                <th class="breakdown-value">Deduction %</th>
                                <th class="breakdown-value">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalLeaveDeduction = 0; @endphp
                            @foreach($salaryData['leave_breakdown'] as $leave)
                                @php
                                    $leaveType = $leave['leave_type'] ?? 'Leave';
                                    $deductionPercentage = $leave['deduction_percentage'] ?? 0;
                                    $deductionAmount = $leave['deduction_amount'] ?? 0;
                                    $daysToDeduct = $leave['days_to_deduct'] ?? 1;
                                    $startDate = $leave['start_date'] ?? '';
                                    $endDate = $leave['end_date'] ?? '';
                                    
                                    // SKIP Short Leave and Half Day from Leave Details
                                    $lowerLeaveType = strtolower($leaveType);
                                    if (strpos($lowerLeaveType, 'short') !== false || strpos($lowerLeaveType, 'half') !== false) {
                                        continue;
                                    }
                                    
                                    // Format date range
                                    $dateRange = '';
                                    if ($startDate && $endDate) {
                                        $start = \Carbon\Carbon::parse($startDate)->format('d M');
                                        $end = \Carbon\Carbon::parse($endDate)->format('d M Y');
                                        $dateRange = $startDate == $endDate ? $start : $start . ' - ' . $end;
                                    }
                                    
                                    // Determine icon based on leave type
                                    $icon = '📅';
                                    if (strpos($lowerLeaveType, 'casual') !== false) {
                                        $icon = '🏖️';
                                    } elseif (strpos($lowerLeaveType, 'sick') !== false) {
                                        $icon = '🤒';
                                    } elseif (strpos($lowerLeaveType, 'earned') !== false) {
                                        $icon = '⭐';
                                    } elseif (strpos($lowerLeaveType, 'maternity') !== false) {
                                        $icon = '👶';
                                    } elseif (strpos($lowerLeaveType, 'unpaid') !== false) {
                                        $icon = '💰';
                                    }
                                    
                                    // Determine deduction text
                                    if ($deductionPercentage >= 100) {
                                        $deductionText = $deductionPercentage . '% (Unpaid)';
                                    } elseif ($deductionPercentage > 0) {
                                        $deductionText = $deductionPercentage . '%';
                                    } else {
                                        $deductionText = '0% (Approved)';
                                    }
                                    
                                    $totalLeaveDeduction += $deductionAmount;
                                @endphp
                                <tr>
                                    <td>{{ $icon }} {{ $leaveType }}</td>
                                    <td>{{ $dateRange }}</td>
                                    <td class="breakdown-value">{{ number_format($daysToDeduct, 1) }} day(s)</td>
                                    <td class="breakdown-value">{{ $deductionText }}</td>
                                    <td class="breakdown-value {{ $deductionAmount > 0 ? 'negative' : '' }}">
                                        @if($deductionAmount > 0)
                                            - ₹{{ number_format($deductionAmount, 2) }}
                                        @else
                                            ₹0.00
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        @if($totalLeaveDeduction > 0)
                        <tfoot>
                            <tr class="breakdown-total">
                                <td colspan="4"><strong>Total Leave Deductions</strong></td>
                                <td class="breakdown-value negative"><strong>- ₹{{ number_format($totalLeaveDeduction, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
            @endif
            
            <div class="sidebar-section" style="padding: 20px;">
                <div class="actions-section">
                    <div>
                        <a href="{{ route('salary.review.index', ['year' => $request->year, 'month' => $request->month]) }}"
                            class="btn btn-back">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                    <div>
                        @if($reviewStatus === 'reviewed' || $reviewStatus === 'pending')
                        <button class="btn btn-finalize" onclick="confirmFinalizeSalary()">
                            <i class="fas fa-lock"></i> Finalize
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="">
            <div class="sidebar-section d-none">
                <h4><i class="fas fa-chart-pie"></i> Deduction Summary</h4>
                <div class="info-row">
                    <span>Standard Deductions:</span>
                    <span class="text-danger">-₹{{ number_format($standardTotal ?? 0, 2) }}</span>
                </div>
                <div class="info-row">
                    <span>Attendance Deductions:</span>
                    <span class="text-danger">-₹{{ number_format($attendanceTotal ?? 0, 2) }}</span>
                </div>
                @if(($salaryData['other_deductions'] ?? 0) > 0)
                <div class="info-row">
                    <span>Other Deductions:</span>
                    <span class="text-danger">-₹{{ number_format($salaryData['other_deductions'], 2) }}</span>
                </div>
                @endif
                <div class="info-row" style="border-top: 2px solid var(--border); margin-top: 10px; padding-top: 10px;">
                    <span><strong>Net Payable:</strong></span>
                    <span><strong
                            style="color: #059669; font-size: 18px;">₹{{ number_format($salaryData['payable_salary'] ?? 0, 2) }}</strong></span>
                </div>
            </div>

            <div class="sidebar-section d-none">
                <h4><i class="fas fa-info-circle"></i> Salary Info</h4>
                <div class="info-row"><span>Basic
                        Salary:</span><span>₹{{ number_format($salaryData['basic_salary'] ?? 0, 2) }}</span></div>
                <div class="info-row"><span>Gross
                        Salary:</span><span>₹{{ number_format($earningsTotal ?? 0, 2) }}</span></div>
                <div class="info-row"><span>Total Deductions:</span><span class="text-danger">-₹<span
                            id="totalDeductionsAmount">{{ number_format(($standardTotal ?? 0) + ($attendanceTotal ?? 0) + ($salaryData['other_deductions'] ?? 0), 2) }}</span></span>
                </div>
            </div>

            <!-- Actions -->

        </div>
    </div>
</div>

<div id="loaderOverlay" class="loader-overlay">
    <div class="spinner"></div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const employeeId = '{{ $employee->employee_id }}';
const currentYear = '{{ $request->year }}';
const currentMonth = '{{ $request->month }}';
const employeeName = '{{ $employee->name }}';

// Get base values from backend (already calculated)
const baseFinalPayable = {{ $salaryData['payable_salary'] ?? 0 }};
let currentOtherDeductionsTotal = {{ $salaryData['other_deductions'] ?? 0 }};

function showLoader() {
    document.getElementById('loaderOverlay').classList.add('active');
}

function hideLoader() {
    document.getElementById('loaderOverlay').classList.remove('active');
}

function editSalary() {
    Swal.fire({
        title: 'Edit Salary Review',
        text: 'This will open the review form for this employee.',
        icon: 'info',
        confirmButtonColor: '#4361ee',
        confirmButtonText: 'Continue',
        showCancelButton: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href =
                `{{ route('salary.review.index') }}?employee_id={{ $employee->employee_id }}&year={{ $request->year }}&month={{ $request->month }}`;
        }
    });
}

// Update the Final Salary Calculation table when Other Deductions change
function updateFinalCalculationTable(otherDeductionsTotal) {
    console.log('Updating final calculation with other deductions:', otherDeductionsTotal);

    // Calculate new final payable = base final payable - other deductions
    const newFinalPayable = baseFinalPayable - otherDeductionsTotal;
    console.log('Base Final Payable:', baseFinalPayable, 'New Final Payable:', newFinalPayable);

    // Get the table
    const finalTable = document.getElementById('finalCalculationTable');
    if (!finalTable) {
        console.error('finalCalculationTable not found');
        return;
    }

    // Check if Other Deductions row exists
    let otherDeductionsRow = document.querySelector('#finalCalculationTable .other-deductions-row');

    if (otherDeductionsTotal > 0) {
        if (!otherDeductionsRow) {
            // Insert new row before the final row
            const finalRow = document.getElementById('finalPayableRow');
            const newRow = document.createElement('tr');
            newRow.className = 'other-deductions-row';
            newRow.innerHTML = `
                <td class="breakdown-label">Less: Other Deductions (Loan, Advance, etc.)</td>
                <td class="breakdown-value negative" id="calc-other-value">- ₹${otherDeductionsTotal.toLocaleString('en-IN', {minimumFractionDigits: 2})}</td>
            `;
            finalRow.parentNode.insertBefore(newRow, finalRow);
            console.log('Added new Other Deductions row');
        } else {
            const otherDeductionsCell = otherDeductionsRow.querySelector('td:last-child');
            if (otherDeductionsCell) {
                otherDeductionsCell.innerHTML =
                    `- ₹${otherDeductionsTotal.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
                console.log('Updated existing Other Deductions row');
            }
        }
    } else {
        if (otherDeductionsRow) {
            otherDeductionsRow.remove();
            console.log('Removed Other Deductions row');
        }
    }

    // Update Final Payable Salary
    const finalPayableCell = document.getElementById('finalPayableValue');
    if (finalPayableCell) {
        finalPayableCell.innerHTML =
            `<strong>₹${Math.max(0, newFinalPayable).toLocaleString('en-IN', {minimumFractionDigits: 2})}</strong>`;
        console.log('Updated final payable cell to:', newFinalPayable);
    }

    // Update the salary card
    const payableCard = document.querySelector('.salary-card.payable .salary-card-value');
    if (payableCard) {
        payableCard.innerHTML = `₹${Math.max(0, newFinalPayable).toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
    }

    // Update sidebar net payable
    const netPayableSpan = document.querySelector('.sidebar-section .info-row:last-child .info-value strong');
    if (netPayableSpan) {
        netPayableSpan.innerHTML =
            `₹${Math.max(0, newFinalPayable).toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
    }
}

// Handle other deduction inputs
function updateOtherDeductions() {
    let totalOtherDeductions = 0;
    const otherDeductionsList = [];

    console.log('updateOtherDeductions called - scanning inputs');

    document.querySelectorAll('.other-deduction-input').forEach(input => {
        const amount = parseFloat(input.value) || 0;
        console.log('Input:', input.dataset.deductionName, 'Amount:', amount);
        if (amount > 0) {
            totalOtherDeductions += amount;
            otherDeductionsList.push({
                name: input.dataset.deductionName,
                amount: amount,
                type: input.dataset.deductionType,
                custom_index: input.dataset.customIndex ? parseInt(input.dataset.customIndex) : null
            });
        }
    });

    console.log('Total Other Deductions:', totalOtherDeductions);

    // Update display in Other Deductions table
    const totalDisplay = document.getElementById('totalOtherDeductionsDisplay');
    const totalHidden = document.getElementById('totalOtherDeductionsValue');

    if (totalDisplay) {
        totalDisplay.innerHTML = `- ₹${totalOtherDeductions.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
    }
    if (totalHidden) {
        totalHidden.value = totalOtherDeductions;
    }

    // Update the Final Salary Calculation table
    updateFinalCalculationTable(totalOtherDeductions);

    currentOtherDeductionsTotal = totalOtherDeductions;
    window.currentOtherDeductionsList = otherDeductionsList;
}

function confirmFinalizeSalary() {
    const otherDeductionsTotal = currentOtherDeductionsTotal;
    const finalPayable = baseFinalPayable - otherDeductionsTotal;

    Swal.fire({
        title: 'Finalize Salary?',
        html: `
            <div class="text-left">
                <div style="background: #f0fdf4; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                    <h4 style="color: #059669;">Salary Summary</h4>
                    <p><strong>Employee:</strong> ${employeeName}</p>
                    <p><strong>Month:</strong> {{ $monthName }}</p>
                    <p><strong>Gross Salary:</strong> ₹{{ number_format($earningsTotal ?? 0, 2) }}</p>
                    <p><strong>Standard Deductions:</strong> -₹{{ number_format($standardTotal ?? 0, 2) }}</p>
                    <p><strong>Attendance Deductions:</strong> -₹{{ number_format($attendanceTotal ?? 0, 2) }}</p>
                    <p><strong>Other Deductions:</strong> -₹${otherDeductionsTotal.toLocaleString('en-IN', {minimumFractionDigits: 2})}</p>
                    <hr>
                    <p><strong>Final Payable:</strong> <span style="color: #059669; font-size: 20px;">₹${Math.max(0, finalPayable).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span></p>
                </div>
                <textarea id="finalizeNotes" class="form-control" rows="2" placeholder="Add finalization notes (optional)"></textarea>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        confirmButtonText: 'Yes, Finalize',
        preConfirm: () => ({
            finalize_notes: document.getElementById('finalizeNotes').value
        })
    }).then((result) => {
        if (result.isConfirmed) {
            finalizeSalary(result.value.finalize_notes);
        }
    });
}

function finalizeSalary(notes) {
    showLoader();

    // Collect all other deductions separately
    let loanAmount = 0;
    let advanceAmount = 0;
    const customDeductionsList = [];

    document.querySelectorAll('.other-deduction-input').forEach(input => {
        const amount = parseFloat(input.value) || 0;
        const type = input.dataset.deductionType;

        if (amount > 0) {
            if (type === 'loan') {
                loanAmount = amount;
            } else if (type === 'advance') {
                advanceAmount = amount;
            } else if (type === 'custom') {
                customDeductionsList.push({
                    name: input.dataset.deductionName,
                    amount: amount,
                    type: 'custom',
                    custom_index: input.dataset.customIndex ? parseInt(input.dataset.customIndex) : null
                });
            }
        }
    });

    const saveData = {
        employee_id: employeeId,
        year: currentYear,
        month: currentMonth,
        final_payable_salary: baseFinalPayable - currentOtherDeductionsTotal,
        review_notes: notes,
        other_deductions: currentOtherDeductionsTotal,
        other_deductions_details: window.currentOtherDeductionsList || [],
        loan_deduction: loanAmount,
        advance_deduction: advanceAmount,
    };

    // First save the review
    fetch('{{ route("salary.review.save") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(saveData)
        })
        .then(response => response.json())
        .then(saveResult => {
            if (!saveResult.success) throw new Error(saveResult.message);

            // Then finalize
            return fetch('{{ route("salary.review.finalize") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    employee_id: employeeId,
                    year: currentYear,
                    month: currentMonth,
                    finalize_notes: notes
                })
            });
        })
        .then(response => response.json())
        .then(data => {
            hideLoader();
            if (data.success) {
                // Show success message with redirect option
                Swal.fire({
                    icon: 'success',
                    title: 'Salary Finalized!',
                    html: `
                    <div class="text-center">
                        <i class="fas fa-check-circle" style="font-size: 48px; color: #10b981; margin-bottom: 15px;"></i>
                        <h4>Salary has been successfully finalized!</h4>
                        <p><strong>Employee:</strong> ${employeeName}</p>
                        <p><strong>Month:</strong> {{ $monthName }}</p>
                        <p><strong>Final Payable Amount:</strong> <span style="color: #059669; font-size: 20px; font-weight: 700;">₹${(baseFinalPayable - currentOtherDeductionsTotal).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span></p>
                        <hr>
                        <p class="text-muted">Click "View Details" to see complete salary breakdown with payroll execution details.</p>
                    </div>
                `,
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#4361ee',
                    confirmButtonText: '<i class="fas fa-eye"></i> View Details',
                    cancelButtonText: '<i class="fas fa-list"></i> Back to List'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to view finalized salary page
                        window.location.href = data.redirect_url;
                    } else {
                        // Redirect to review list
                        window.location.href = '{{ route("salary.review.index") }}?year=' + currentYear +
                            '&month=' + currentMonth;
                    }
                });
            } else {
                Swal.fire('Error!', data.message, 'error');
            }
        })
        .catch(error => {
            hideLoader();
            Swal.fire('Error!', error.message, 'error');
        });
}

function saveSalaryReview() {
    const finalPayable = baseFinalPayable - currentOtherDeductionsTotal;

    Swal.fire({
        title: 'Save Review',
        text: 'Are you sure you want to save these salary details?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4361ee',
        confirmButtonText: 'Yes, Save'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoader();

            fetch('{{ route("salary.review.save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        employee_id: employeeId,
                        year: currentYear,
                        month: currentMonth,
                        net_salary: finalPayable,
                        review_notes: document.getElementById('reviewNotes')?.value || '',
                        other_deductions: currentOtherDeductionsTotal,
                        other_deductions_details: window.currentOtherDeductionsList || []
                    })
                })
                .then(response => response.json())
                .then(data => {
                    hideLoader();
                    if (data.success) {
                        Swal.fire('Success!', 'Salary reviewed successfully', 'success').then(() => location
                            .reload());
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                })
                .catch(error => {
                    hideLoader();
                    Swal.fire('Error!', 'Something went wrong', 'error');
                });
        }
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - initializing event listeners');
    const otherInputs = document.querySelectorAll('.other-deduction-input');
    console.log('Found other deduction inputs:', otherInputs.length);
    otherInputs.forEach(input => {
        input.addEventListener('input', function() {
            console.log('Input changed:', this.value);
            updateOtherDeductions();
        });
    });

    // Initialize display
    updateFinalCalculationTable(currentOtherDeductionsTotal);
});

window.editSalary = editSalary;
window.confirmFinalizeSalary = confirmFinalizeSalary;
window.saveSalaryReview = saveSalaryReview;
</script>
@endsection
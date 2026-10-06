@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<title>Finalized Salary Details</title>

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
    background: linear-gradient(135deg, var(--success), #059669);
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

.finalized-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 14px;
}

.btn {
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

.btn-primary {
    background: var(--primary);
    color: white;
}

.btn-primary:hover {
    background: #3a0ca3;
    transform: translateY(-2px);
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

.finalized-status {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    background: #d1fae5;
    color: #059669;
}

/* Grid Layout */
.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 25px;
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

/* Deductions Table */
.deductions-table {
    width: 100%;
}

.deductions-table td {
    padding: 10px 12px;
    border-bottom: 1px solid var(--border);
}

.deductions-table tr:last-child td {
    border-bottom: none;
}

.deduction-category {
    background: #f1f5f9;
    font-weight: 600;
}

.deduction-category td {
    background: #f1f5f9;
    padding: 10px 12px;
}

.deduction-label {
    font-weight: 500;
    padding-left: 30px !important;
}

.deduction-value {
    text-align: right;
    font-weight: 600;
}

.deduction-value.negative {
    color: var(--danger);
}

.deduction-value.positive {
    color: var(--success);
}

.deduction-total {
    background: #f0fdf4;
    font-weight: 700;
}

.deduction-total td {
    border-top: 2px solid var(--border);
    padding: 12px;
    font-weight: 700;
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

/* Payroll Card */
.payroll-card {
    background: var(--light);
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
}

.payroll-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid var(--border);
}

.payroll-row:last-child {
    border-bottom: none;
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
.actions-section {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

@media (max-width: 992px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
}

/* Print Styles */
@media print {
    .page-header, .actions-section, .btn, .btn-secondary {
        display: none !important;
    }
}
</style>

<div class="container-fluid container-custom">
    <!-- Page Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-check-circle"></i>
            Finalized Salary Details
            <span class="finalized-badge"><i class="fas fa-lock"></i> FINALIZED</span>
        </h1>
        <div>
            <button class="btn btn-secondary" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </button>
            <a href="{{ route('salary.review.index', ['year' => $request->year, 'month' => $request->month]) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Employee Header -->
    <div class="employee-header">
        <div class="employee-avatar">{{ strtoupper(substr($employee->name, 0, 2)) }}</div>
        <div class="employee-info">
            <h2>{{ $employee->name }}</h2>
            <p><strong>Employee Code:</strong> {{ $employee->employee_code }} | <strong>Department:</strong> {{ $employee->department_name ?? 'N/A' }}</p>
            <p><strong>Salary Month:</strong> {{ $monthName }}</p>
            <span class="finalized-status">
                <i class="fas fa-check-circle"></i> Finalized on {{ \Carbon\Carbon::parse($salaryReview->finalized_at)->format('d M Y h:i A') }}
            </span>
        </div>
    </div>

    <div class="content-grid">
        <!-- Left Column -->
        <div>
            <!-- Payroll Execution Card -->
            @if(!empty($payrollExecutionDetails))
            <div class="section">
                <div class="section-header"><i class="fas fa-chart-line"></i> Payroll Execution Details</div>
                <div class="section-content">
                    <div class="payroll-card">
                        <div class="payroll-row">
                            <span><i class="fas fa-calendar-alt"></i> Financial Year:</span>
                            <strong>{{ $payrollExecutionDetails['financial_year'] ?? 'N/A' }}</strong>
                        </div>
                        <div class="payroll-row">
                            <span><i class="fas fa-sync-alt"></i> Payroll Cycle:</span>
                            <strong>
                                @if(($payrollExecutionDetails['payroll_cycle'] ?? '') == 'days')
                                    Daily Cycle ({{ $payrollExecutionDetails['cycle_days'] ?? 0 }} days)
                                @else
                                    Monthly Cycle
                                @endif
                            </strong>
                        </div>
                        <div class="payroll-row">
                            <span><i class="fas fa-calendar-day"></i> Execution Day:</span>
                            <strong>{{ $payrollExecutionDetails['execution_day'] ?? 'End of Month' }} of month</strong>
                        </div>
                        <div class="payroll-row">
                            <span><i class="fas fa-money-bill-wave"></i> Pay Date:</span>
                            <strong class="text-success">{{ $payrollExecutionDetails['pay_date'] ?? 'End of Month' }}</strong>
                        </div>
                        <div class="payroll-row">
                            <span><i class="fas fa-info-circle"></i> Calculation Basis:</span>
                            <span class="text-muted">{{ $payrollExecutionDetails['calculation_basis'] ?? 'Monthly cycle' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

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
                            <div class="attendance-item-value">{{ number_format($attendanceReview->attendance_percentage ?? 0, 1) }}%</div>
                            <div class="attendance-item-label">Attendance %</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Salary Cards -->
            <div class="section">
                <div class="section-header"><i class="fas fa-chart-line"></i> Salary Summary</div>
                <div class="section-content">
                    <div class="salary-cards">
                        <div class="salary-card basic">
                            <div class="salary-card-value">₹{{ number_format($salaryReview->basic_salary ?? 0, 2) }}</div>
                            <div class="salary-card-label">Basic Salary</div>
                        </div>
                        <div class="salary-card gross">
                            <div class="salary-card-value">₹{{ number_format($salaryReview->gross_salary ?? 0, 2) }}</div>
                            <div class="salary-card-label">Gross Salary</div>
                        </div>
                        <div class="salary-card net">
                            <div class="salary-card-value">₹{{ number_format($salaryReview->monthly_net_salary ?? 0, 2) }}</div>
                            <div class="salary-card-label">Net Salary</div>
                        </div>
                        <div class="salary-card payable">
                            <div class="salary-card-value">₹{{ number_format($salaryReview->final_payable_salary ?? 0, 2) }}</div>
                            <div class="salary-card-label">Final Payable</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Unified Deductions Section -->
            <div class="section">
                <div class="section-header"><i class="fas fa-minus-circle"></i> Deductions Breakdown</div>
                <div class="section-content" style="padding: 0;">
                    <table class="deductions-table">
                        <tbody>
                            <!-- ==================== STANDARD DEDUCTIONS ==================== -->
                            <tr class="deduction-category">
                                <td colspan="2"><strong><i class="fas fa-building"></i> Standard Deductions (Statutory)</strong></td>
                            </tr>
                            @php $standardTotal = 0; @endphp
                            
                            @if(($salaryReview->pt_deduction ?? 0) > 0)
                                @php $standardTotal += $salaryReview->pt_deduction; @endphp
                                <tr><td class="deduction-label">Professional Tax (PT)</td><td class="deduction-value negative">- ₹{{ number_format($salaryReview->pt_deduction, 2) }}</td></tr>
                            @endif
                            
                            @if(($salaryReview->lst_deduction ?? 0) > 0)
                                @php $standardTotal += $salaryReview->lst_deduction; @endphp
                                <tr><td class="deduction-label">Labour Welfare (LST)</td><td class="deduction-value negative">- ₹{{ number_format($salaryReview->lst_deduction, 2) }}</td></tr>
                            @endif
                            
                            @if(($salaryReview->tds_deduction ?? 0) > 0)
                                @php $standardTotal += $salaryReview->tds_deduction; @endphp
                                <tr><td class="deduction-label">TDS</td><td class="deduction-value negative">- ₹{{ number_format($salaryReview->tds_deduction, 2) }}</td></tr>
                            @endif
                            
                            @if(($salaryReview->insurance_premium ?? 0) > 0)
                                @php $standardTotal += $salaryReview->insurance_premium; @endphp
                                <tr><td class="deduction-label">Insurance Premium</td><td class="deduction-value negative">- ₹{{ number_format($salaryReview->insurance_premium, 2) }}</td></tr>
                            @endif
                            
                            @if(($salaryReview->advance_salary_deduction ?? 0) > 0)
                                @php $standardTotal += $salaryReview->advance_salary_deduction; @endphp
                                <tr><td class="deduction-label">Advance Salary</td><td class="deduction-value negative">- ₹{{ number_format($salaryReview->advance_salary_deduction, 2) }}</td></tr>
                            @endif
                            
                            @if(($salaryReview->pf_employee_deduction ?? 0) > 0)
                                @php $standardTotal += $salaryReview->pf_employee_deduction; @endphp
                                <tr><td class="deduction-label">Employee PF</td><td class="deduction-value negative">- ₹{{ number_format($salaryReview->pf_employee_deduction, 2) }}</td></tr>
                            @endif
                            
                            @if(($salaryReview->esi_employee_deduction ?? 0) > 0)
                                @php $standardTotal += $salaryReview->esi_employee_deduction; @endphp
                                <tr><td class="deduction-label">Employee ESI</td><td class="deduction-value negative">- ₹{{ number_format($salaryReview->esi_employee_deduction, 2) }}</td></tr>
                            @endif
                            
                            @if(($salaryReview->nps_employee_deduction ?? 0) > 0)
                                @php $standardTotal += $salaryReview->nps_employee_deduction; @endphp
                                <tr><td class="deduction-label">Employee NPS</td><td class="deduction-value negative">- ₹{{ number_format($salaryReview->nps_employee_deduction, 2) }}</td></tr>
                            @endif

                            @if($standardTotal == 0)
                                <tr><td colspan="2" class="deduction-label text-muted">No standard deductions configured</td></tr>
                            @endif

                            <!-- ==================== ATTENDANCE DEDUCTIONS ==================== -->
                            <tr class="deduction-category">
                                <td colspan="2"><strong><i class="fas fa-clock"></i> Attendance Deductions</strong></td>
                            </tr>
                            @php $attendanceTotal = 0; @endphp
                            
                            @if(($salaryReview->absent_deduction ?? 0) > 0)
                                @php $attendanceTotal += $salaryReview->absent_deduction; @endphp
                                <tr><td class="deduction-label">❌ Absent Deduction</td><td class="deduction-value negative">- ₹{{ number_format($salaryReview->absent_deduction, 2) }}</td></tr>
                            @endif
                            
                            @if(($salaryReview->leave_deduction ?? 0) > 0)
                                @php $attendanceTotal += $salaryReview->leave_deduction; @endphp
                                <tr><td class="deduction-label">📅 Leave Deduction</td><td class="deduction-value negative">- ₹{{ number_format($salaryReview->leave_deduction, 2) }}</td></tr>
                            @endif
                            
                            @if(($salaryReview->unpaid_leave_deduction ?? 0) > 0)
                                @php $attendanceTotal += $salaryReview->unpaid_leave_deduction; @endphp
                                <tr><td class="deduction-label">⚠️ Unpaid Leave Deduction</td><td class="deduction-value negative">- ₹{{ number_format($salaryReview->unpaid_leave_deduction, 2) }}</td></tr>
                            @endif
                            
                            @if(($salaryReview->unapproved_leave_deduction ?? 0) > 0)
                                @php $attendanceTotal += $salaryReview->unapproved_leave_deduction; @endphp
                                <tr><td class="deduction-label">🚫 Unapproved Leave Deduction</td><td class="deduction-value negative">- ₹{{ number_format($salaryReview->unapproved_leave_deduction, 2) }}</td></tr>
                            @endif
                            
                            @if(($salaryReview->short_attendance_deduction ?? 0) > 0)
                                @php $attendanceTotal += $salaryReview->short_attendance_deduction; @endphp
                                <tr><td class="deduction-label">⏰ Short Attendance Deduction</td><td class="deduction-value negative">- ₹{{ number_format($salaryReview->short_attendance_deduction, 2) }}</td></tr>
                            @endif

                            @if($attendanceTotal == 0)
                                <tr><td colspan="2" class="deduction-label text-muted">No attendance deductions applied</td></tr>
                            @endif

                            <!-- ==================== OTHER DEDUCTIONS (NO DUPLICATES) ==================== -->
                            @php
                                $otherDeductionsTotal = 0;
                                $displayedDeductions = [];
                            @endphp

                            <!-- Check if there are any other deductions to display -->
                            @php
                                $hasLoan = ($salaryReview->loan_deduction ?? 0) > 0;
                                $hasAdvance = ($salaryReview->advance_deduction ?? 0) > 0;
                                $hasCustom = !empty($salaryReview->other_deductions_details);
                            @endphp

                            @if($hasLoan || $hasAdvance || $hasCustom)
                            <tr class="deduction-category">
                                <td colspan="2"><strong><i class="fas fa-plus-circle"></i> Other Deductions</strong></td>
                            </tr>
                            @endif

                            <!-- Display Loan Deduction (only from column, not from details) -->
                            @if($hasLoan)
                                @php 
                                    $otherDeductionsTotal += $salaryReview->loan_deduction;
                                    $displayedDeductions[] = 'loan_' . $salaryReview->loan_deduction;
                                @endphp
                                <tr>
                                    <td class="deduction-label">💰 Loan Deduction</td>
                                    <td class="deduction-value negative">- ₹{{ number_format($salaryReview->loan_deduction, 2) }}</td>
                                </tr>
                            @endif

                            <!-- Display Advance Deduction (only from column, not from details) -->
                            @if($hasAdvance)
                                @php 
                                    $otherDeductionsTotal += $salaryReview->advance_deduction;
                                    $displayedDeductions[] = 'advance_' . $salaryReview->advance_deduction;
                                @endphp
                                <tr>
                                    <td class="deduction-label">🏦 Advance Salary Deduction</td>
                                    <td class="deduction-value negative">- ₹{{ number_format($salaryReview->advance_deduction, 2) }}</td>
                                </tr>
                            @endif

                            <!-- Display Custom Deductions from other_deductions_details (skip if already shown) -->
                            @if($hasCustom)
                                @foreach($salaryReview->other_deductions_details as $deduction)
                                    @php
                                        $deductionAmount = $deduction['amount'] ?? 0;
                                        $deductionName = $deduction['name'] ?? 'Custom Deduction';
                                        $deductionType = $deduction['type'] ?? '';
                                        
                                        // Skip if this is a loan or advance that we already displayed
                                        if (($deductionType === 'loan' && $hasLoan) || ($deductionType === 'advance' && $hasAdvance)) {
                                            continue;
                                        }
                                        
                                        $otherDeductionsTotal += $deductionAmount;
                                        
                                        // Determine icon
                                        $icon = '📋';
                                        if ($deductionType === 'loan') $icon = '💰';
                                        elseif ($deductionType === 'advance') $icon = '🏦';
                                    @endphp
                                    <tr>
                                        <td class="deduction-label">{{ $icon }} {{ $deductionName }}</td>
                                        <td class="deduction-value negative">- ₹{{ number_format($deductionAmount, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            <!-- ==================== TOTAL DEDUCTIONS ==================== -->
                            @php 
                                $totalDeductions = $standardTotal + $attendanceTotal + $otherDeductionsTotal;
                            @endphp
                            <tr class="deduction-total">
                                <td><strong>Total Deductions</strong></td>
                                <td class="deduction-value negative"><strong>- ₹{{ number_format($totalDeductions, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Final Salary Calculation -->
            <div class="section">
                <div class="section-header"><i class="fas fa-calculator"></i> Final Salary Calculation</div>
                <div class="section-content">
                    <table class="deductions-table">
                        <tbody>
                            <tr><td class="deduction-label">Gross Salary</td><td class="deduction-value positive">+ ₹{{ number_format($salaryReview->gross_salary ?? 0, 2) }}</td></tr>
                            <tr><td class="deduction-label">Less: Standard Deductions</td><td class="deduction-value negative">- ₹{{ number_format($standardTotal, 2) }}</td></tr>
                            <tr style="background: #f8fafc;"><td class="deduction-label"><strong>= Net Salary</strong></td><td class="deduction-value"><strong>₹{{ number_format(($salaryReview->gross_salary ?? 0) - $standardTotal, 2) }}</strong></td></tr>
                            <tr><td class="deduction-label">Less: Attendance Deductions</td><td class="deduction-value negative">- ₹{{ number_format($attendanceTotal, 2) }}</td></tr>
                            <tr><td class="deduction-label">Less: Other Deductions</td><td class="deduction-value negative">- ₹{{ number_format($otherDeductionsTotal, 2) }}</td></tr>
                            <tr class="deduction-total"><td class="deduction-label"><strong>Final Payable Salary</strong></td><td class="deduction-value positive"><strong>₹{{ number_format($salaryReview->final_payable_salary ?? 0, 2) }}</strong></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div>
            <!-- Finalization Info -->
            <div class="sidebar-section">
                <h4><i class="fas fa-info-circle"></i> Finalization Info</h4>
                <div class="info-row"><span>Finalized On:</span><span>{{ \Carbon\Carbon::parse($salaryReview->finalized_at)->format('d M Y h:i A') }}</span></div>
                <div class="info-row"><span>Finalized By:</span><span>{{ $finalizedByName ?? 'System' }}</span></div>
                @if($salaryReview->finalize_notes)
                <div class="info-row"><span>Notes:</span><span class="text-muted">{{ $salaryReview->finalize_notes }}</span></div>
                @endif
            </div>

            <!-- Earnings Summary -->
            <div class="sidebar-section">
                <h4><i class="fas fa-chart-line"></i> Earnings Summary</h4>
                @if(!empty($salaryReview->earnings_breakdown))
                    @foreach($salaryReview->earnings_breakdown as $key => $value)
                        @if($value > 0)
                        <div class="info-row">
                            <span>{{ ucwords(str_replace(['_', '-'], ' ', $key)) }}</span>
                            <span class="text-success">+₹{{ number_format($value, 2) }}</span>
                        </div>
                        @endif
                    @endforeach
                    <div class="info-row" style="border-top: 1px solid var(--border); margin-top: 8px; padding-top: 8px;">
                        <span><strong>Total Earnings</strong></span>
                        <span><strong>₹{{ number_format($salaryReview->gross_salary ?? 0, 2) }}</strong></span>
                    </div>
                @else
                    <div class="text-muted">No earnings data available</div>
                @endif
            </div>

            <!-- Actions -->
            <div class="sidebar-section" style="padding: 20px;">
                <div class="actions-section">
                    <a href="{{ route('salary.review.index', ['year' => $request->year, 'month' => $request->month]) }}" class="btn btn-primary">
                        <i class="fas fa-list"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto print option (optional)
// window.print();
</script>

@endsection
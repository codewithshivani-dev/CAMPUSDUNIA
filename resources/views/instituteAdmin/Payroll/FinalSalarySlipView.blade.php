@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<title>Final Salary Slip - {{ $salarySlip->employee_name }}</title>

<style>
.salary-slip-container {
    max-width: 210mm;
    margin: 0 auto;
    background: white;
    border: 2px solid #333;
    padding: 0;
    font-family: 'Arial', sans-serif;
    font-size: 12px;
    line-height: 1.4;
}

/* Company Header */
.company-header {
    background: linear-gradient(135deg, #1e3a8a, #3730a3);
    color: white;
    padding: 20px;
    text-align: center;
    border-bottom: 3px solid #fbbf24;
}

.company-name {
    font-size: 24px;
    font-weight: bold;
    margin: 0;
    letter-spacing: 1px;
}

.company-address {
    font-size: 11px;
    margin: 5px 0;
    opacity: 0.9;
}

.slip-title {
    font-size: 18px;
    font-weight: bold;
    margin: 10px 0 0;
    padding: 8px;
    background: #fbbf24;
    color: #1e3a8a;
    border-radius: 4px;
    display: inline-block;
}

/* Employee Details Section */
.employee-section {
    padding: 15px 20px;
    background: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
}

.employee-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.employee-field {
    display: flex;
    justify-content: space-between;
    padding: 4px 0;
    border-bottom: 1px dotted #cbd5e1;
}

.field-label {
    font-weight: 600;
    color: #374151;
    min-width: 120px;
}

.field-value {
    color: #1f2937;
    font-weight: 500;
}

/* Salary Details Table */
.salary-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

.salary-table th {
    background: #1e3a8a;
    color: white;
    padding: 12px 8px;
    text-align: left;
    font-weight: 600;
    font-size: 11px;
    border: 1px solid #333;
}

.salary-table td {
    padding: 10px 8px;
    border: 1px solid #ddd;
    vertical-align: top;
}

.earnings-section {
    background: #f0fdf4;
}

.deductions-section {
    background: #fef2f2;
}

.amount-positive {
    color: #059669;
    font-weight: 600;
}

.amount-negative {
    color: #dc2626;
    font-weight: 600;
}

/* Summary Section */
.summary-section {
    background: linear-gradient(135deg, #1e3a8a, #3730a3);
    color: white;
    padding: 20px;
    margin: 20px 0;
    border-radius: 8px;
}

.summary-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 20px;
    text-align: center;
}

.summary-item {
    padding: 10px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 6px;
}

.summary-label {
    font-size: 11px;
    opacity: 0.8;
    margin-bottom: 5px;
}

.summary-value {
    font-size: 16px;
    font-weight: bold;
}

.final-amount {
    background: #10b981;
    color: white;
    padding: 15px;
    text-align: center;
    font-size: 18px;
    font-weight: bold;
    margin: 20px 0;
    border-radius: 8px;
}

/* Bank Details */
.bank-section {
    padding: 15px 20px;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    margin: 20px 0;
}

.bank-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

/* Authorization */
.authorization-section {
    padding: 20px;
    text-align: center;
    border-top: 2px solid #333;
    margin-top: 30px;
}

.signature-line {
    border-top: 1px solid #333;
    width: 200px;
    margin: 40px auto 10px;
}

.signature-text {
    font-size: 11px;
    color: #6b7280;
}

/* Footer */
.slip-footer {
    padding: 15px 20px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    text-align: center;
    font-size: 10px;
    color: #6b7280;
}

.slip-id {
    font-weight: bold;
    color: #1e3a8a;
}

/* Print Styles */
@media print {
    .no-print {
        display: none !important;
    }
    
    .salary-slip-container {
        border: 2px solid #333;
        box-shadow: none;
        margin: 0;
        max-width: 100%;
    }
    
    .page-break {
        page-break-inside: avoid;
    }
    
    body {
        margin: 0;
        padding: 0;
    }
}

/* Responsive for mobile */
@media (max-width: 768px) {
    .employee-grid,
    .bank-grid {
        grid-template-columns: 1fr;
    }
    
    .summary-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .salary-slip-container {
        margin: 10px;
        border: 1px solid #333;
    }
}
</style>

<div class="container-fluid py-4">
    <div class="no-print text-right mb-3">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Print Slip
        </button>
        <a href="{{ route('final-salary-slips.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="salary-slip-container">
        <!-- Company Header -->
        <div class="company-header">
            <h1 class="company-name">{{ $salarySlip->institute->name ?? 'Campusdunia' }}</h1>
            <div class="company-address">
                {{ $salarySlip->institute->address_line_1 ?? 'Payroll Management System' }} | 
                {{ $salarySlip->institute->contact_number ?? 'Contact: +91-XXXXXXXXXX' }} | 
                {{ $salarySlip->institute->email ?? 'info@campusdunia.com' }}
            </div>
            <div class="slip-title">
                <i class="fas fa-file-invoice-dollar"></i>  SALARY SLIP
            </div>
        </div>

        <!-- Employee Details -->
        <div class="employee-section">
            <div class="employee-grid">
                <div>
                    <div class="employee-field">
                        <span class="field-label">Employee Name:</span>
                        <span class="field-value">{{ $salarySlip->employee_name }}</span>
                    </div>
                    <div class="employee-field">
                        <span class="field-label">Employee Code:</span>
                        <span class="field-value">{{ $salarySlip->employee_code ?? $salarySlip->employee_id }}</span>
                    </div>
                    <div class="employee-field">
                        <span class="field-label">Department:</span>
                        <span class="field-value">{{ $salarySlip->employee->department->department ?? 'N/A' }}</span>
                    </div>
                </div>
                <div>
                    <div class="employee-field">
                        <span class="field-label">Salary Month:</span>
                        <span class="field-value">{{ \Carbon\Carbon::parse($salarySlip->salary_month)->format('F Y') }}</span>
                    </div>
                    <div class="employee-field">
                        <span class="field-label">Slip ID:</span>
                        <span class="field-value slip-id">{{ $salarySlip->slip_id }}</span>
                    </div>
                    <div class="employee-field">
                        <span class="field-label">Generated On:</span>
                        <span class="field-value">{{ \Carbon\Carbon::parse($salarySlip->generated_at)->format('d M Y h:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings Table -->
        <table class="salary-table earnings-section page-break">
            <thead>
                <tr>
                    <th colspan="2" style="text-align: center; background: #059669;">
                        <i class="fas fa-plus-circle"></i> EARNINGS & ALLOWANCES
                    </th>
                </tr>
                <tr>
                    <th style="width: 70%;">Particulars</th>
                    <th style="width: 30%; text-align: right;">Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salarySlip->earnings_breakdown ?? [] as $key => $value)
                    @if($value > 0)
                    <tr>
                        <td>{{ ucwords(str_replace(['_', '-'], ' ', $key)) }}</td>
                        <td class="text-right amount-positive">₹{{ number_format($value, 2) }}</td>
                    </tr>
                    @endif
                @endforeach
                <tr style="background: #e8f5e8; font-weight: bold; border-top: 2px solid #059669;">
                    <td><strong>GROSS EARNINGS</strong></td>
                    <td class="text-right amount-positive"><strong>₹{{ number_format($salarySlip->gross_salary, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Deductions Table -->
        <table class="salary-table deductions-section page-break">
            <thead>
                <tr>
                    <th colspan="2" style="text-align: center; background: #dc2626;">
                        <i class="fas fa-minus-circle"></i> DEDUCTIONS
                    </th>
                </tr>
                <tr>
                    <th style="width: 70%;">Particulars</th>
                    <th style="width: 30%; text-align: right;">Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salarySlip->standard_deductions ?? [] as $key => $value)
                    @if($value > 0)
                    <tr>
                        <td>{{ ucwords(str_replace(['_', '-'], ' ', $key)) }}</td>
                        <td class="text-right amount-negative">- ₹{{ number_format($value, 2) }}</td>
                    </tr>
                    @endif
                @endforeach
                @foreach($salarySlip->attendance_deductions ?? [] as $key => $value)
                    @if($value > 0)
                    <tr>
                        <td>{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                        <td class="text-right amount-negative">- ₹{{ number_format($value, 2) }}</td>
                    </tr>
                    @endif
                @endforeach
                @foreach($salarySlip->other_deductions ?? [] as $deduction)
                    @if(($deduction['amount'] ?? 0) > 0)
                    <tr>
                        <td>{{ $deduction['name'] ?? 'Other Deduction' }}</td>
                        <td class="text-right amount-negative">- ₹{{ number_format($deduction['amount'], 2) }}</td>
                    </tr>
                    @endif
                @endforeach
                <tr style="background: #fee2e2; font-weight: bold; border-top: 2px solid #dc2626;">
                    <td><strong>TOTAL DEDUCTIONS</strong></td>
                    <td class="text-right amount-negative"><strong>- ₹{{ number_format($salarySlip->total_deductions, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Summary Section -->
        <div class="summary-section">
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">Basic Salary</div>
                    <div class="summary-value">₹{{ number_format($salarySlip->basic_salary, 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Gross Salary</div>
                    <div class="summary-value">₹{{ number_format($salarySlip->gross_salary, 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Total Deductions</div>
                    <div class="summary-value">₹{{ number_format($salarySlip->total_deductions, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Final Amount -->
        <div class="final-amount">
            <i class="fas fa-money-check-alt"></i> 
            NET PAYABLE AMOUNT: ₹{{ number_format($salarySlip->final_payable, 2) }}
        </div>

        <!-- Bank Details -->
        @if($salarySlip->bank_name || $salarySlip->account_number)
        <div class="bank-section">
            <h5 style="margin: 0 0 15px 0; color: #1e3a8a;">
                <i class="fas fa-university"></i> BANK DETAILS FOR CREDIT
            </h5>
            <div class="bank-grid">
                <div>
                    <div class="employee-field">
                        <span class="field-label">Bank Name:</span>
                        <span class="field-value">{{ $salarySlip->bank_name ?? 'N/A' }}</span>
                    </div>
                    <div class="employee-field">
                        <span class="field-label">Account Number:</span>
                        <span class="field-value">{{ $salarySlip->account_number ?? 'N/A' }}</span>
                    </div>
                </div>
                <div>
                    <div class="employee-field">
                        <span class="field-label">IFSC Code:</span>
                        <span class="field-value">{{ $salarySlip->ifsc_code ?? 'N/A' }}</span>
                    </div>
                    <div class="employee-field">
                        <span class="field-label">PAN Number:</span>
                        <span class="field-value">{{ $salarySlip->pan_number ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Attendance Summary -->
        @if(isset($salarySlip->attendance_summary) && is_array($salarySlip->attendance_summary))
        <div class="bank-section">
            <h5 style="margin: 0 0 15px 0; color: #1e3a8a;">
                <i class="fas fa-calendar-check"></i> ATTENDANCE SUMMARY
            </h5>
            <div class="bank-grid">
                <div>
                    <div class="employee-field">
                        <span class="field-label">Present Days:</span>
                        <span class="field-value">{{ $salarySlip->attendance_summary['present_days'] ?? 0 }} days</span>
                    </div>
                    <div class="employee-field">
                        <span class="field-label">Absent Days:</span>
                        <span class="field-value">{{ $salarySlip->attendance_summary['absent_days'] ?? 0 }} days</span>
                    </div>
                </div>
                <div>
                    <div class="employee-field">
                        <span class="field-label">Leave Days:</span>
                        <span class="field-value">{{ $salarySlip->attendance_summary['leave_days'] ?? 0 }} days</span>
                    </div>
                    <div class="employee-field">
                        <span class="field-label">Attendance %:</span>
                        <span class="field-value">{{ $salarySlip->attendance_summary['attendance_percentage'] ?? 0 }}%</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Authorization Section -->
        <div class="authorization-section">
            <div class="signature-line"></div>
            <div class="signature-text">
                Authorized Signatory<br>
                <small>(Computer Generated Salary Slip - No Signature Required)</small>
            </div>
        </div>

        <!-- Footer -->
        <div class="slip-footer">
            <div class="slip-id">Slip ID: {{ $salarySlip->slip_id }}</div>
            <div>This is a computer generated final salary slip valid for salary payment.</div>
            <div>Generated on: {{ \Carbon\Carbon::parse($salarySlip->generated_at)->format('d M Y h:i A') }}</div>
        </div>
    </div>
</div>

@endsection
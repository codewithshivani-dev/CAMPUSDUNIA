<!DOCTYPE html>
<html>
<head>
    <title>Salary Slip - {{ $salarySlip->name }}</title>
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #4361ee;
        }
        .employee-info {
            margin-bottom: 20px;
            padding: 10px;
            background: #f5f5f5;
        }
        .employee-info table {
            width: 100%;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            background: #4361ee;
            color: white;
            padding: 8px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .total {
            font-weight: bold;
            background: #f0fdf4;
        }
        .amount {
            text-align: right;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
        @media print {
            body { margin: 0; padding: 0; }
            .container { border: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SALARY SLIP</h1>
            <p>Month: {{ \Carbon\Carbon::parse($salarySlip->salary_month)->format('F Y') }}</p>
        </div>
        
        <div class="employee-info">
            <table>
                <tr><td width="30%"><strong>Employee Name:</strong></td><td>{{ $salarySlip->name }}</td></tr>
                <tr><td><strong>Employee Code:</strong></td><td>{{ $salarySlip->employee_id }}</td></tr>
                <tr><td><strong>Department:</strong></td><td>{{ $details['department'] ?? 'N/A' }}</td></tr>
                <tr><td><strong>Designation:</strong></td><td>{{ $details['designation'] ?? 'N/A' }}</td></tr>
                <tr><td><strong>Slip ID:</strong></td><td>{{ $salarySlip->salaryslip_id }}</td></tr>
            </table>
        </div>
        
        <div class="section">
            <div class="section-title">Earnings</div>
            <table>
                @foreach($details['earnings_breakdown'] ?? [] as $key => $value)
                    @if($value > 0)
                    <tr><td>{{ ucwords(str_replace('_', ' ', $key)) }}</td><td class="amount">₹{{ number_format($value, 2) }}</td></tr>
                    @endif
                @endforeach
                <tr class="total"><td><strong>Gross Salary</strong></td><td class="amount"><strong>₹{{ number_format($salarySlip->gross_salary, 2) }}</strong></td></tr>
            </table>
        </div>
        
        <div class="section">
            <div class="section-title">Deductions</div>
            <table>
                @foreach($details['standard_deductions_breakdown'] ?? [] as $key => $value)
                    @if($value > 0)
                    <tr><td>{{ $key }}</td><td class="amount">₹{{ number_format($value, 2) }}</td></tr>
                    @endif
                @endforeach
                @foreach($details['attendance_deductions_breakdown'] ?? [] as $key => $value)
                    @if($value > 0)
                    <tr><td>{{ ucwords(str_replace('_', ' ', $key)) }}</td><td class="amount">₹{{ number_format($value, 2) }}</td></tr>
                    @endif
                @endforeach
                @foreach($details['other_deductions_breakdown'] ?? [] as $deduction)
                    @if(($deduction['amount'] ?? 0) > 0)
                    <tr><td>{{ $deduction['name'] ?? 'Other Deduction' }}</td><td class="amount">₹{{ number_format($deduction['amount'], 2) }}</td></tr>
                    @endif
                @endforeach
                <tr class="total"><td><strong>Total Deductions</strong></td><td class="amount"><strong>₹{{ number_format($salarySlip->gross_salary - $salarySlip->payable_salary, 2) }}</strong></td></tr>
            </table>
        </div>
        
        <div class="section">
            <div class="section-title">Net Payable</div>
            <table>
                <tr class="total"><td><strong>Net Salary Payable</strong></td><td class="amount"><strong>₹{{ number_format($salarySlip->payable_salary, 2) }}</strong></td></tr>
            </table>
        </div>
        
        <div class="footer">
            <p>This is a computer generated salary slip. No signature required.</p>
        </div>
    </div>
</body>
</html>
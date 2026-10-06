<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Salary Finalized</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            margin: -20px -20px 20px -20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .salary-summary {
            background-color: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .info-table td {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            width: 45%;
        }
        .info-value {
            color: #333;
            width: 55%;
        }
        .net-salary {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            text-align: center;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #218838;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
        .deduction-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }
        .total-deduction {
            border-top: 2px solid #ddd;
            margin-top: 10px;
            padding-top: 10px;
            font-weight: bold;
        }
        @media (max-width: 480px) {
            .container {
                margin: 10px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💰 Salary Finalized</h1>
            <p>{{ $monthName }} {{ $year }}</p>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $employeeName }}</strong>,</p>
            
            <p>Your salary for the month of <strong>{{ $monthName }} {{ $year }}</strong> has been finalized by the HR/Admin team.</p>
            
            <div class="salary-summary">
                <h3 style="margin-top: 0;">📊 Salary Summary</h3>
                <table class="info-table">
                    <tr>
                        <td class="info-label">💰 Basic Salary:</td>
                        <td class="info-value">₹ {{ number_format($basicSalary, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">📈 Gross Salary:</td>
                        <td class="info-value">₹ {{ number_format($grossSalary, 2) }}</td>
                    </tr>
                </table>
                
                <div class="net-salary">
                    💵 Net Payable: ₹ {{ number_format($netSalary, 2) }}
                </div>
                
                @if(!empty($deductionsBreakdown))
                <h4>📋 Deductions Breakdown</h4>
                @foreach($deductionsBreakdown as $label => $amount)
                    @if($amount > 0)
                    <div class="deduction-item">
                        <span>{{ $label }}:</span>
                        <span>₹ {{ number_format($amount, 2) }}</span>
                    </div>
                    @endif
                @endforeach
                <div class="deduction-item total-deduction">
                    <span><strong>Total Deductions:</strong></span>
                    <span><strong>₹ {{ number_format($totalDeductions, 2) }}</strong></span>
                </div>
                @endif
            </div>
            
            @if(!empty($attendanceSummary))
            <div style="background-color: #e8eefd; padding: 15px; border-radius: 8px; margin: 15px 0;">
                <h4 style="margin-top: 0;">📅 Attendance Summary</h4>
                <table class="info-table">
                    <tr><td class="info-label">Present Days:</td><td>{{ $attendanceSummary['present_days'] ?? 0 }} days</td></tr>
                    @if(($attendanceSummary['absent_days'] ?? 0) > 0)
                    <tr><td class="info-label">Absent Days:</td><td>{{ $attendanceSummary['absent_days'] ?? 0 }} days</td></tr>
                    @endif
                    @if(($attendanceSummary['leave_days'] ?? 0) > 0)
                    <tr><td class="info-label">Leave Days:</td><td>{{ $attendanceSummary['leave_days'] ?? 0 }} days</td></tr>
                    @endif
                    <tr><td class="info-label">Attendance Percentage:</td><td>{{ number_format($attendanceSummary['attendance_percentage'] ?? 0, 2) }}%</td></tr>
                </table>
            </div>
            @endif
            
            <div style="background-color: #e8f5e9; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <h4 style="margin-top: 0;">📌 Important Information:</h4>
                <ul>
                    <li>Your salary will be credited to your registered bank account by <strong>{{ $payDate ?? 'end of month' }}</strong>.</li>
                    <li>Please check your salary slip in the employee portal for detailed breakdown.</li>
                    <li>This is a system-generated salary statement.</li>
                </ul>
            </div>
            
            <p style="text-align: center; display:none;">
                <a href="{{ url('/employee/payroll/dashboard') }}" class="button">📄 View My Salary Slip</a>
            </p>
        </div>
        
       
    </div>
</body>
</html>
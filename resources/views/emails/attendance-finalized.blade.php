<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Attendance Finalized - {{ config('app.name') }}</title>
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
        .attendance-summary {
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
        .percentage-good {
            color: #28a745;
            font-weight: bold;
        }
        .percentage-average {
            color: #ffc107;
            font-weight: bold;
        }
        .percentage-poor {
            color: #dc3545;
            font-weight: bold;
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
        .leave-breakdown {
            background-color: #e8eefd;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
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
            <h1>✅ Attendance Finalized</h1>
            <p>{{ $monthName }} {{ $year }}</p>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $employeeName }}</strong>,</p>
            
            <p>Your attendance for the month of <strong>{{ $monthName }} {{ $year }}</strong> has been finalized by the HR/Admin team.</p>
            
            <div class="attendance-summary">
                <h3 style="margin-top: 0;">📊 Attendance Summary</h3>
                <table class="info-table">
                    <tr>
                        <td class="info-label">📅 Working Days:</td>
                        <td class="info-value"><strong>{{ $workingDays }}</strong> days</td>
                    </tr>
                    <tr>
                        <td class="info-label">✅ Present Days:</td>
                        <td class="info-value"><strong>{{ $presentDays }}</strong> days</td>
                    </tr>
                    @if($absentDays > 0)
                    <tr>
                        <td class="info-label">❌ Absent Days:</td>
                        <td class="info-value">{{ $absentDays }} days</td>
                    </tr>
                    @endif
                    @if($leaveDays > 0)
                    <tr>
                        <td class="info-label">📋 Leave Days:</td>
                        <td class="info-value">{{ $leaveDays }} days</td>
                    </tr>
                    @endif
                    @if($weekendDays > 0)
                    <tr>
                        <td class="info-label">🎯 Weekly Offs:</td>
                        <td class="info-value">{{ $weekendDays }} days</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="info-label">📈 Attendance Percentage:</td>
                        <td class="info-value">
                            @php
                                $percentage = $attendancePercentage;
                                $class = $percentage >= 90 ? 'percentage-good' : ($percentage >= 75 ? 'percentage-average' : 'percentage-poor');
                            @endphp
                            <span class="{{ $class }}">{{ number_format($percentage, 2) }}%</span>
                        </td>
                    </tr>
                </table>
            </div>
            
            @if(!empty($leaveBreakdown))
            <div class="leave-breakdown">
                <h4>📋 Leave Breakdown</h4>
                <table class="info-table">
                    <thead>
                        <tr><th>Leave Type</th><th>Days Taken</th><th>Within Quota?</th></tr>
                    </thead>
                    <tbody>
                        @foreach($leaveBreakdown as $type => $days)
                        <tr>
                            <td>{{ ucfirst(str_replace('_', ' ', $type)) }}</td>
                            <td>{{ $days }}</td>
                            <td>{{ $days <= ($leaveQuotas[$type] ?? 0) ? '✅ Yes' : '⚠️ Exceeded' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
                       
            <p style="text-align: center; display:none;">
                <a href="{{ url('/institute/admin/my-attendance') }}" class="button">View My Attendance</a>
            </p>
        </div>
        
        
    </div>
</body>
</html>
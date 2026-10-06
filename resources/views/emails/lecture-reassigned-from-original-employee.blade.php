<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Lecture Has Been Reassigned</title>
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
            padding: 0;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .lecture-details {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .lecture-details h3 {
            margin-top: 0;
            color: #d97706;
        }
        .detail-row {
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #fde68a;
        }
        .detail-label {
            font-weight: 600;
            color: #92400e;
            display: inline-block;
            width: 120px;
        }
        .detail-value {
            color: #333;
        }
        .reason-box {
            background: #fef3c7;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #f59e0b;
        }
        .reason-box p {
            margin: 0;
            color: #92400e;
        }
        .info-box {
            background: #e0f2fe;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #0ea5e9;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: 600;
        }
        .footer {
            background: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .status-badge {
            display: inline-block;
            background: #f59e0b;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📤 Your Lecture Has Been Reassigned</h1>
            <p style="margin: 5px 0 0; opacity: 0.9;">Lecture Reassignment Notification</p>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $employee->name }}</strong>,</p>
            
            <p>Your lecture has been reassigned to another instructor. Please find the details below:</p>
            
            <div class="lecture-details">
                <h3>📖 Lecture Details</h3>
                <div class="detail-row">
                    <span class="detail-label">Subject:</span>
                    <span class="detail-value"><strong>{{ $details['subject_name'] }}</strong></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Original Date:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($details['date'])->format('l, F j, Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Time:</span>
                    <span class="detail-value">{{ $details['start_time'] }} - {{ $details['end_time'] }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Location:</span>
                    <span class="detail-value">{{ $details['location'] ?? 'Not specified' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Department:</span>
                    <span class="detail-value">{{ $details['department'] ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Course:</span>
                    <span class="detail-value">{{ $details['course'] ?? 'N/A' }}</span>
                </div>
                @if(!empty($details['section_name']))
                <div class="detail-row">
                    <span class="detail-label">Section:</span>
                    <span class="detail-value">{{ $details['section_name'] }}</span>
                </div>
                @endif
            </div>
            
            <div class="reason-box">
                <p><strong>📝 Reason for Reassignment:</strong></p>
                <p>{{ $details['reason'] }}</p>
            </div>
            
            <div class="info-box">
                <p><strong>ℹ️ Important Information:</strong></p>
                <p>This is a one-time reassignment for the specified date only. Your regular schedule will resume normally after this date.</p>
                <p style="margin-top: 10px;"><strong>New Instructor:</strong> {{ $details['new_employee_name'] }}</p>
                <p><strong>Reassignment Date:</strong> {{ \Carbon\Carbon::parse($details['date'])->format('l, F j, Y') }}</p>
            </div>
            
            <div style="text-align: center; display:none;">
                <a href="{{ url('/employee/schedule') }}" class="button">View My Schedule</a>
            </div>
        </div>
        
        <div class="footer">
            <p>This is an automated notification from the Lecture Management System.</p>
            <p>&copy; {{ date('Y') }} Institute Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
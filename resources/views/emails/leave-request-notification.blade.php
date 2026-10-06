<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Leave Request</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: auto; padding: 20px; background: #f9f9f9; }
        .header { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: white; padding: 20px; text-align: center; }
        .content { background: white; padding: 20px; border-radius: 8px; margin-top: 20px; }
        .info-box { background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .button { background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; }
        .button-approve { background: #28a745; }
        .button-reject { background: #dc3545; margin-left: 10px; }
        .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #888; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📝 New Leave Request</h2>
            <p>Action Required</p>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $approverName }}</strong>,</p>
            
            <p><strong>{{ $employeeName }}</strong> has submitted a leave request that requires your approval.</p>
            
            <div class="info-box">
                <h3>Leave Details:</h3>
                <table style="width: 100%;">
                    <tr><td><strong>Employee:</strong></td><td>{{ $employeeName }} ({{ $employeeCode }})</td></tr>
                    <tr><td><strong>Department:</strong></td><td>{{ $departmentName }}</td></tr>
                    <tr><td><strong>Leave Type:</strong></td><td>{{ $leaveType }}</td></tr>
                    <tr><td><strong>Duration:</strong></td><td>{{ $startDate }} to {{ $endDate }}</td></tr>
                    <tr><td><strong>Total Days:</strong></td><td>{{ $totalDays }} days</td></tr>
                    <tr><td><strong>Reason:</strong></td><td>{{ $reason }}</td></tr>
                    <tr><td><strong>Applied On:</strong></td><td>{{ $appliedDate }}</td></tr>
                </table>
            </div>
            
          
        </div>
       
    </div>
</body>
</html>
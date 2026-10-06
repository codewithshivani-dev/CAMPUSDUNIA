<!DOCTYPE html>
<html>
<head>
    <title>Account Suspension Notice</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; background: #f9fafb; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #666; }
        .reason-box { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
        .button { background: #f59e0b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Account Suspension Notice</h2>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $studentName }}</strong>,</p>
            
            <p>Your account at <strong>{{ $instituteName }}</strong> has been <strong style="color: #f59e0b;">TEMPORARILY SUSPENDED</strong>.</p>
            
            <div class="reason-box">
                <strong>Suspension Reason:</strong><br>
                {{ $reason }}
            </div>
            @if(($totalSuspensions ?? 0) > 1)
            <div class="reason-box" style="background: #ffebee; border-left-color: #f44336;">
                <strong>⚠️ Notice:</strong> This is suspension #{{ $suspensionNumber ?? $totalSuspensions }}. Repeated violations may lead to permanent action.
            </div>
            @endif
            <p><strong>During suspension:</strong></p>
            <ul>
                <li>❌ Your credits access is blocked</li>
                <li>❌ You cannot use credit-based services</li>
                <li>❌ Account login may be restricted</li>
            </ul>
            
            <p><strong>What to do next:</strong></p>
            <ul>
                <li>📞 Contact the administration for resolution</li>
               
            </ul>
            
            <p>Date: {{ $date }}</p>
            <p>Registration Number: {{ $registrationNumber }}</p>
        </div>
        <div class="footer">
            <p>This is an automated message from {{ $instituteName }}. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{ $instituteName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
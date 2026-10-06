<!DOCTYPE html>
<html>
<head>
    <title>Account Access Restored</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; background: #f9fafb; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #666; }
        .reason-box { background: #e6f7e6; border-left: 4px solid #10b981; padding: 15px; margin: 20px 0; }
        .button { background: #10b981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Account Access Restored</h2>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $studentName }}</strong>,</p>
            
            <p>Your account at <strong>{{ $instituteName }}</strong> has been <strong style="color: #10b981;">UNSUSPENDED</strong>.</p>
            
            @if($reason)
            <div class="reason-box">
                <strong>Previous Suspension Reason:</strong><br>
                {{ $reason }}
            </div>
            @endif
            
            @if($unsuspensionReason ?? false)
            <div class="reason-box" style="background: #e3f2fd; border-left-color: #2196f3;">
                <strong>Unsuspension Reason/Note:</strong><br>
                {{ $unsuspensionReason }}
            </div>
            @endif
            
            <p><strong>What has been restored:</strong></p>
            <ul>
                <li>✅ Your credits access has been restored</li>
                <li>✅ You can now use all services normally</li>
            </ul>
            
            @if(($totalSuspensions ?? 0) > 1)
            <div class="reason-box" style="background: #fff3cd; border-left-color: #ffc107;">
                <strong>⚠️ Notice:</strong> This is suspension #{{ $suspensionNumber ?? $totalSuspensions }}. Please ensure compliance with institute policies.
            </div>
            @endif
            
            <p>You can now login to your account and continue using our services.</p>
            
            <p>Date: {{ $date }}</p>
            <p>Registration Number: {{ $registrationNumber }}</p>
            
            <hr>
            <p style="font-size: 12px; color: #666;">If you have any questions, please contact the administration.</p>
        </div>
        <div class="footer">
            <p>This is an automated message from {{ $instituteName }}. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{ $instituteName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
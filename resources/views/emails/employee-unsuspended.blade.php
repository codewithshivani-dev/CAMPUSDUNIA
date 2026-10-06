<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employee Account Unsuspended</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .content { padding: 20px; }
        .footer { background: #f5f5f5; padding: 15px; text-align: center; border-radius: 0 0 8px 8px; font-size: 12px; color: #777; }
        .badge { display: inline-block; padding: 4px 12px; background: #dcfce7; color: #166534; border-radius: 20px; font-size: 14px; }
        .info-row { margin-bottom: 10px; }
        .info-label { font-weight: bold; color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin:0;">Account Access Restored</h2>
            <p style="margin:5px 0 0; opacity:0.9;">{{ $instituteName }}</p>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $employeeName }}</strong>,</p>
            
            <p>Your employee account with <strong>{{ $instituteName }}</strong> has been <strong class="text-success">unsuspended</strong>.</p>
            
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <h4 style="margin-top:0;">Restoration Details</h4>
                <div class="info-row"><span class="info-label">Employee Code:</span> {{ $employeeCode }}</div>
                <div class="info-row"><span class="info-label">Department:</span> {{ $departmentName }}</div>
                <div class="info-row"><span class="info-label">Designation:</span> {{ $designation }}</div>
                <div class="info-row"><span class="info-label">Restored Date:</span> {{ $date }}</div>
                <div class="info-row"><span class="info-label">Status:</span> <span class="badge">Active</span></div>
                @if(!empty($unsuspensionReason))
                <div class="info-row"><span class="info-label">Restoration Reason:</span> {{ $unsuspensionReason }}</div>
                @endif
                @if(!empty($reason))
                <div class="info-row"><span class="info-label">Previous Suspension Reason:</span> {{ $reason }}</div>
                @endif
            </div>
            
            <p><strong>Your access has been fully restored.</strong> You can now:</p>
            <ul>
                <li>Log in to the system normally</li>
                <li>Access all your work resources</li>
                <li>Continue your duties as usual</li>
            </ul>
            
            <p style="margin-top: 30px;">We appreciate your cooperation and understanding.</p>
            
            <p style="margin-top: 20px;">Regards,<br>
            <strong>{{ $instituteName }}</strong><br>
            HR Department</p>
        </div>
        
        <div class="footer">
            <p>{{ $instituteName }} &bull; This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
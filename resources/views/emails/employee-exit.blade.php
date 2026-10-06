<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employee Exit Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { background: linear-gradient(135deg, #4361ee, #3a0ca3); color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .content { padding: 20px; }
        .footer { background: #f5f5f5; padding: 15px; text-align: center; border-radius: 0 0 8px 8px; font-size: 12px; color: #777; }
        .badge { display: inline-block; padding: 4px 12px; background: #fef3c7; color: #92400e; border-radius: 20px; font-size: 14px; }
        .info-row { margin-bottom: 10px; }
        .info-label { font-weight: bold; color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin:0;">Employee Exit Confirmation</h2>
            <p style="margin:5px 0 0; opacity:0.9;">{{ $instituteName }}</p>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $employeeName }}</strong>,</p>
            
            <p>This is to confirm that your employment with <strong>{{ $instituteName }}</strong> has been formally concluded.</p>
            
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <h4 style="margin-top:0;">Exit Details</h4>
                <div class="info-row"><span class="info-label">Employee Code:</span> {{ $employeeCode }}</div>
                <div class="info-row"><span class="info-label">Department:</span> {{ $departmentName }}</div>
                <div class="info-row"><span class="info-label">Designation:</span> {{ $designation }}</div>
                <div class="info-row"><span class="info-label">Exit Date:</span> {{ $exitDate }}</div>
                <div class="info-row"><span class="info-label">Status:</span> <span class="badge">Exited</span></div>
                @if(!empty($exitReason) && $exitReason !== 'Not specified')
                <div class="info-row"><span class="info-label">Exit Reason:</span> {{ $exitReason }}</div>
                @endif
            </div>
            
            <p><strong>Important Notes:</strong></p>
            <ul>
                <li>Your employee account has been deactivated.</li>
                <li>Please return all company assets (laptop, ID card, etc.) to the HR department.</li>
                <li>For any queries regarding your final settlement, please contact HR.</li>
            </ul>
            
            <p style="margin-top: 30px;">We appreciate your contributions during your tenure and wish you all the best in your future endeavors.</p>
            
            <p style="margin-top: 20px;">Sincerely,<br>
            <strong>{{ $instituteName }}</strong><br>
            HR Department</p>
        </div>
        
        <div class="footer">
            <p>{{ $instituteName }} &bull; This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
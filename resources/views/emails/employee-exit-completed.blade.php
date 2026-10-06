<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exit Process Completed</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2d3748; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f7fafc; padding: 30px; border-radius: 0 0 5px 5px; }
        .completed-box { background: #ebf8ff; border-left: 4px solid #3182ce; padding: 15px; margin: 15px 0; }
        .footer { text-align: center; padding: 20px; color: #718096; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Exit Process Completed</h2>
            <p>{{ $instituteName ?? 'Institute' }}</p>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $employeeName }}</strong>,</p>

            <div class="completed-box">
                <h3>✅ Exit Process Completed</h3>
                <p>Your exit process has been completed successfully.</p>
                <p><strong>Last Working Day:</strong> {{ $exitDate }}</p>
                <p><strong>Notice Period End Date:</strong> {{ $noticeEndDate ?? 'N/A' }}</p>
            </div>

            <div style="background: #fefcbf; padding: 15px; border-radius: 5px; margin: 15px 0;">
                <p><strong>⚠️ Important:</strong> Your account has been deactivated.</p>
                <p>Thank you for your contributions to {{ $instituteName ?? 'the organization' }}.</p>
            </div>

            <p>If you have any questions regarding your final settlement or experience letter, please contact HR.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $instituteName ?? 'Institute' }}. All rights reserved.</p>
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
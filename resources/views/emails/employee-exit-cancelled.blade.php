<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exit Process Cancelled</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2d3748; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f7fafc; padding: 30px; border-radius: 0 0 5px 5px; }
        .cancelled-box { background: #fffaf0; border-left: 4px solid #ed8936; padding: 15px; margin: 15px 0; }
        .info-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .info-table td { padding: 10px; border-bottom: 1px solid #e2e8f0; }
        .info-table .label { font-weight: bold; width: 40%; background: #edf2f7; }
        .btn { display: inline-block; padding: 10px 20px; background: #2b6cb0; color: white; text-decoration: none; border-radius: 5px; }
        .footer { text-align: center; padding: 20px; color: #718096; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Exit Process Cancelled</h2>
            <p>{{ $instituteName ?? 'Institute' }}</p>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $employeeName }}</strong>,</p>

            <div class="cancelled-box">
                <h3>🔄 Exit Process Cancelled</h3>
                <p>
                    @if(isset($initiationSource) && $initiationSource === 'admin')
                        <strong>Admin</strong> has cancelled your exit process.
                    @else
                        Your exit request has been cancelled.
                    @endif
                </p>
                @if(isset($reason) && $reason)
                    <p><strong>Reason:</strong> {{ $reason }}</p>
                @endif
            </div>

            <div style="background: #e2e8f0; padding: 15px; border-radius: 5px; margin: 15px 0;">
                <p><strong>✅ Your account remains active.</strong></p>
                <p>You can continue working as usual.</p>
            </div>

            <p style="margin-top: 20px;">
                <a href="{{ route('employee.exit.dashboard') }}" class="btn">View Exit Dashboard</a>
            </p>

            <p>If you have any questions, please contact HR.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $instituteName ?? 'Institute' }}. All rights reserved.</p>
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
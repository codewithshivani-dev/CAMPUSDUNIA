<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exit Approval Request</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2d3748; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f7fafc; padding: 30px; border-radius: 0 0 5px 5px; }
        .info-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .info-table td { padding: 10px; border-bottom: 1px solid #e2e8f0; }
        .info-table .label { font-weight: bold; width: 40%; background: #edf2f7; }
        .btn { display: inline-block; padding: 10px 20px; background: #2b6cb0; color: white; text-decoration: none; border-radius: 5px; }
        .footer { text-align: center; padding: 20px; color: #718096; font-size: 12px; }
        .button-group { margin: 20px 0; }
        .btn-approve { background: #48bb78; }
        .btn-reject { background: #fc8181; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Exit Approval Request</h2>
            <p>{{ $instituteName ?? 'Institute' }}</p>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $approverName }}</strong>,</p>

            <p>
                <strong>{{ $employeeName }}</strong> ({{ $employeeCode }}) has submitted an exit request.
                @if(isset($initiationSource) && $initiationSource === 'admin')
                    This request was initiated by admin.
                @endif
            </p>

            <h3>Request Details</h3>
            <table class="info-table">
                <tr>
                    <td class="label">Employee</td>
                    <td>{{ $employeeName }}</td>
                </tr>
                <tr>
                    <td class="label">Employee Code</td>
                    <td>{{ $employeeCode }}</td>
                </tr>
                <tr>
                    <td class="label">Exit Reason</td>
                    <td>{{ $exitReason ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Request ID</td>
                    <td>#EX{{ $exitId }}</td>
                </tr>
            </table>

          
            <p>Please review and take appropriate action.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $instituteName ?? 'Institute' }}. All rights reserved.</p>
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exit Process Initiated</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2d3748; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f7fafc; padding: 30px; border-radius: 0 0 5px 5px; }
        .status-badge { display: inline-block; padding: 5px 15px; border-radius: 20px; font-size: 14px; font-weight: bold; }
        .status-pending { background: #f6ad55; color: #744210; }
        .status-approved { background: #68d391; color: #22543d; }
        .status-notice { background: #63b3ed; color: #2a4365; }
        .status-rejected { background: #fc8181; color: #742a2a; }
        .status-cancelled { background: #a0aec0; color: #2d3748; }
        .info-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .info-table td { padding: 10px; border-bottom: 1px solid #e2e8f0; }
        .info-table .label { font-weight: bold; width: 40%; background: #edf2f7; }
        .footer { text-align: center; padding: 20px; color: #718096; font-size: 12px; }
        .btn { display: inline-block; padding: 10px 20px; background: #2b6cb0; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Exit Process Initiated</h2>
            <p>{{ $instituteName ?? 'Institute' }}</p>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $employeeName }}</strong>,</p>

            <p>
                @if(isset($initiationSource) && $initiationSource === 'admin')
                    <strong>Admin</strong> has initiated your exit process.
                @else
                    Your resignation request has been submitted successfully.
                @endif
            </p>

            <h3>Exit Details</h3>
            <table class="info-table">
                <tr>
                    <td class="label">Employee Code</td>
                    <td>{{ $employeeCode }}</td>
                </tr>
                <tr>
                    <td class="label">Status</td>
                    <td>
                        <span class="status-badge status-{{ $status ?? 'pending' }}">
                            {{ ucfirst($status ?? 'Pending') }}
                        </span>
                    </td>
                </tr>
                @if(isset($exitReason) && $exitReason)
                <tr>
                    <td class="label">Exit Reason</td>
                    <td>{{ $exitReason }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Notice Period</td>
                    <td>{{ $noticeDays ?? 'N/A' }} days</td>
                </tr>
                <tr>
                    <td class="label">Notice Start Date</td>
                    <td>{{ $noticeStartDate ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Notice End Date</td>
                    <td>{{ $noticeEndDate ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Proposed Last Working Day</td>
                    <td>{{ $proposedLastWorkingDate ?? 'N/A' }}</td>
                </tr>
            </table>

            @if(isset($status) && $status === 'pending_approval')
                <div style="background: #fefcbf; padding: 15px; border-radius: 5px; margin: 15px 0;">
                    <p><strong>⏳ Your request is ON HOLD pending admin approval.</strong></p>
                    <p>You will be notified once a decision is made.</p>
                </div>
            @endif

            <p>If you have any questions, please contact HR.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $instituteName ?? 'Institute' }}. All rights reserved.</p>
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
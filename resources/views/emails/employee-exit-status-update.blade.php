<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exit Request Status Update</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { 
            background: #2d3748; 
            color: white; 
            padding: 20px; 
            text-align: center; 
            border-radius: 5px 5px 0 0; 
        }
        .header .status-approved { 
            background: #48bb78; 
            color: white; 
            padding: 5px 15px; 
            border-radius: 20px; 
            display: inline-block;
        }
        .header .status-rejected { 
            background: #fc8181; 
            color: white; 
            padding: 5px 15px; 
            border-radius: 20px; 
            display: inline-block;
        }
        .content { background: #f7fafc; padding: 30px; border-radius: 0 0 5px 5px; }
        .approved-box { 
            background: #f0fff4; 
            border-left: 4px solid #48bb78; 
            padding: 15px; 
            margin: 15px 0; 
        }
        .rejected-box { 
            background: #fff5f5; 
            border-left: 4px solid #fc8181; 
            padding: 15px; 
            margin: 15px 0; 
        }
        .info-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .info-table td { padding: 10px; border-bottom: 1px solid #e2e8f0; }
        .info-table .label { font-weight: bold; width: 40%; background: #edf2f7; }
        .btn { 
            display: inline-block; 
            padding: 10px 20px; 
            background: #2b6cb0; 
            color: #ffffff !important; 
            text-decoration: none; 
            border-radius: 5px; 
            font-weight: 600;
        }
        .btn:hover {
            background: #1a4f7a;
            color: #ffffff !important;
        }
        .footer { text-align: center; padding: 20px; color: #718096; font-size: 12px; }
        .performed-by {
            color: #4a5568;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Exit Request Status Update</h2>
            <p>{{ $instituteName ?? 'Institute' }}</p>
            <span class="status-{{ $status }}">
                {{ ucfirst($status) }}
            </span>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $employeeName }}</strong>,</p>

            @if($status === 'approved')
                <div class="approved-box">
                    <h3>✅ Your Exit Request Has Been Approved!</h3>
                    <p>Your notice period starts from <strong>{{ $noticeStartDate }}</strong>.</p>
                    @if(isset($performedBy) && $performedBy)
                        <p class="performed-by">Action by: <strong>{{ $performedBy }}</strong></p>
                    @endif
                </div>

                <h3>Notice Period Details</h3>
                <table class="info-table">
                    <tr>
                        <td class="label">Employee Code</td>
                        <td>{{ $employeeCode }}</td>
                    </tr>
                    <tr>
                        <td class="label">Status</td>
                        <td><span style="color: #48bb78; font-weight: bold;">Notice Period</span></td>
                    </tr>
                    <tr>
                        <td class="label">Notice Period</td>
                        <td>{{ $noticeDays ?? 'N/A' }} days</td>
                    </tr>
                    <tr>
                        <td class="label">Notice Start Date</td>
                        <td><strong>{{ $noticeStartDate }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Notice End Date</td>
                        <td><strong>{{ $noticeEndDate }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Proposed Last Working Day</td>
                        <td><strong>{{ $proposedLastWorkingDate }}</strong></td>
                    </tr>
                </table>

                <div style="background: #e2e8f0; padding: 15px; border-radius: 5px; margin: 15px 0;">
                    <p><strong>📌 Important:</strong> Your account will be deactivated on {{ $proposedLastWorkingDate }}.</p>
                    <p>Please complete all pending tasks and handover before your last working day.</p>
                </div>

            @elseif($status === 'rejected')
                <div class="rejected-box">
                    <h3>❌ Your Exit Request Has Been Rejected</h3>
                    @if(isset($reason) && $reason)
                        <p><strong>Reason:</strong> {{ $reason }}</p>
                    @endif
                    @if(isset($performedBy) && $performedBy)
                        <p class="performed-by">Action by: <strong>{{ $performedBy }}</strong></p>
                    @endif
                    <p>You can continue working as usual.</p>
                </div>
            @endif

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
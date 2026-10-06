<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Leave Request {{ ucfirst($status) }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: auto; padding: 20px; background: #f9f9f9; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; }
        .header.approved { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
        .header.rejected { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); }
        .content { background: white; padding: 20px; border-radius: 8px; margin-top: 20px; }
        .info-box { background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #667eea; }
        .info-box.approved { border-left-color: #28a745; }
        .info-box.rejected { border-left-color: #dc3545; }
        .status-badge { display: inline-block; padding: 5px 15px; border-radius: 20px; font-weight: bold; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        .status-pending { background: #fff3cd; color: #856404; }
        .button { background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; }
        .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #888; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header {{ $status }}">
            <h2>
                @if($status === 'approved') ✅ Leave Request Approved
                @elseif($status === 'rejected') ❌ Leave Request Rejected
                @else 📝 Leave Request Update
                @endif
            </h2>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $employeeName }}</strong>,</p>
            
            <p>Your leave request has been <strong>{{ ucfirst($status) }}</strong> by <strong>{{ $approvedByName }}</strong>.</p>
            
            <div class="info-box {{ $status }}">
                <h3>Leave Details:</h3>
                <table style="width: 100%;">
                    <tr><td><strong>Leave Type:</strong></td><td>{{ $leaveType }}</td></tr>
                    <tr><td><strong>Duration:</strong></td><td>{{ $startDate }} to {{ $endDate }}</td></tr>
                    <tr><td><strong>Total Days:</strong></td><td>{{ $totalDays }} days</td></tr>
                    <tr><td><strong>Reason:</strong></td><td>{{ $reason }}</td></tr>
                    @if($status === 'approved')
                    <tr><td><strong>Status:</strong></td><td><span class="status-badge status-approved">Approved</span></td></tr>
                    @elseif($status === 'rejected')
                    <tr><td><strong>Status:</strong></td><td><span class="status-badge status-rejected">Rejected</span></td></tr>
                    <tr><td><strong>Rejection Reason:</strong></td><td>{{ $comments ?? 'No comments provided' }}</td></tr>
                    @endif
                </table>
            </div>
            
            @if($status === 'approved')
            <div style="background: #d4edda; padding: 15px; border-radius: 8px; margin: 15px 0;">
                <h4 style="margin-top: 0;">📌 Important Notes:</h4>
                <ul>
                    <li>Your leave has been approved and recorded in the system</li>
                    <li>Please ensure all pending work is handed over before your leave</li>
                    <li>Mark your attendance accordingly during leave period</li>
                </ul>
            </div>
            @elseif($status === 'rejected')
            <div style="background: #f8d7da; padding: 15px; border-radius: 8px; margin: 15px 0;">
                <h4 style="margin-top: 0;">📌 What to do next:</h4>
                <ul>
                    <li>Contact your reporting manager for clarification</li>
                    <li>You can re-apply with additional information</li>
                    <li>Consider applying for a different leave type</li>
                </ul>
            </div>
            @endif
            
           
        </div>
      
    </div>
</body>
</html>
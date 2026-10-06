<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Leave Quota Assigned</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: auto; padding: 20px; background: #f9f9f9; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; }
        .content { background: white; padding: 20px; border-radius: 8px; margin-top: 20px; }
        .leave-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .leave-table th { background: #667eea; color: white; padding: 10px; text-align: left; }
        .leave-table td { padding: 8px 10px; border-bottom: 1px solid #ddd; }
        .total-row { background: #f0f0f0; font-weight: bold; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 12px; }
        .badge-full-day { background: #d4edda; color: #155724; }
        .badge-half-day { background: #fff3cd; color: #856404; }
        .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #888; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📋 Leave Quota Assigned</h2>
            <p>Your leave balance has been updated</p>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $employeeName }}</strong>,</p>
            <p>Your leave quota for the session <strong>{{ $sessionYear }}</strong> has been assigned.</p>
            
            <h3>Leave Details:</h3>
            <table class="leave-table">
                <thead>
                    <tr>
                        <th>Leave Type</th>
                        <th>Allocated Days</th>
                        <th>Effective Days</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leaveDetails as $leave)
                    <tr>
                        <td>
                            {{ ucfirst(str_replace('_', ' ', $leave['type'])) }}
                            @if($leave['category'] === 'half_day')
                                <span class="badge badge-half-day">Half Day</span>
                            @elseif($leave['category'] === 'short_leave')
                                <span class="badge badge-half-day">Short Leave</span>
                            @else
                                <span class="badge badge-full-day">Full Day</span>
                            @endif
                        </td>
                        <td>{{ $leave['raw_days'] }} days</td>
                        <td><strong>{{ number_format($leave['effective_days'], 2) }} days</strong></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td><strong>Total</strong></td>
                        <td colspan="2"><strong>{{ $totalRawDays }} days</strong></td>
                    </tr>
                </tfoot>
            </table>
            
            <p>Please log in to your employee portal to view your updated leave balance and plan your leaves accordingly.</p>   
          
        </div>
       
    </div>
</body>
</html>
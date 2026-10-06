<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Probation Completed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            padding: 30px 25px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            opacity: 0.9;
        }
        .content {
            background: #f8faff;
            padding: 30px 25px;
            border-radius: 0 0 10px 10px;
            border: 1px solid #e2e8f0;
            border-top: none;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 15px;
        }
        .message {
            font-size: 15px;
            color: #334155;
            margin-bottom: 20px;
        }
        .details-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }
        .details-table .label {
            font-weight: 600;
            color: #475569;
            width: 40%;
        }
        .details-table .value {
            color: #0f172a;
            width: 60%;
        }
        .badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .footer {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 13px;
            color: #64748b;
            text-align: center;
        }
        .highlight {
            color: #059669;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>🎉 Congratulations!</h2>
        <p>Your Probation Period Has Been Completed</p>
    </div>
    
    <div class="content">
        <div class="greeting">
            Dear {{ $employee->name }},
        </div>
        
        <div class="message">
            We are pleased to inform you that your probation period has been successfully completed. 
            You have been promoted to a <strong>Full-Time Employee</strong>.
        </div>
        
        <div style="text-align: center; margin: 20px 0;">
            <span class="badge">✅ Promoted to Full-Time</span>
        </div>
        
        <table class="details-table">
            <tr>
                <td class="label">Employee Code</td>
                <td class="value"><strong>{{ $employee->employee_code }}</strong></td>
            </tr>
            <tr>
                <td class="label">Date of Joining</td>
                <td class="value">{{ $dateOfJoining }}</td>
            </tr>
            <tr>
                <td class="label">Probation End Date</td>
                <td class="value">{{ $probationEndDate }}</td>
            </tr>
            <tr>
                <td class="label">Department</td>
                <td class="value">{{ $departmentName }}</td>
            </tr>
            <tr>
                <td class="label">Designation</td>
                <td class="value">{{ $designationName }}</td>
            </tr>
            <tr>
                <td class="label">Employment Type</td>
                <td class="value"><span style="color: #059669; font-weight: 600;">Full-Time</span></td>
            </tr>
            <tr>  
                <td class="label">Promotion Date</td>
                <td class="value"><strong>{{ $promotionDate ?? date('d-m-Y') }}</strong></td>
            </tr>
        </table>
        
        <div class="message" style="margin-top: 20px;">
            <p>We appreciate your dedication, hard work, and contribution during your probation period. 
            We look forward to your continued growth and success with us.</p>
            
            <p style="margin-top: 15px;">
                <strong>What's next?</strong>
            </p>
            <ul style="color: #334155; padding-left: 20px;">
                <li>You are now eligible for all full-time employee benefits</li>
                <li>Your performance will be reviewed regularly</li>
                <li>Feel free to reach out to your manager or HR for any questions</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin-top: 25px; padding: 15px; background: #d1fae5; border-radius: 8px;">
            <p style="margin: 0; color: #065f46; font-weight: 500;">
                🚀 Welcome aboard! We're excited to have you as a full-time member of our team.
            </p>
        </div>
        
        <div class="footer">
            <p style="margin: 0;">
                This is an automated notification from <strong>{{ $companyName ?? 'Our Institute' }}</strong>.
            </p>
            <p style="margin: 5px 0 0; font-size: 12px;">
                If you have any questions, please contact the HR department.
            </p>
        </div>
    </div>
</body>
</html>
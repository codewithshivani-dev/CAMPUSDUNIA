<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Designation Promotion Notification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            padding: 30px 25px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: #ffffff;
        }
        .header .subtitle {
            margin-top: 8px;
            font-size: 14px;
            opacity: 0.9;
            color: #ffffff;
        }
        .content {
            padding: 30px 25px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 15px;
        }
        .greeting span {
            color: #4361ee;
        }
        .message-box {
            background: #f8faff;
            border-left: 4px solid #4361ee;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .message-box p {
            margin: 0;
            color: #1e293b;
        }
        .promotion-details {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .promotion-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .promotion-details td {
            padding: 10px 8px;
            border-bottom: 1px solid #f1f5f9;
        }
        .promotion-details tr:last-child td {
            border-bottom: none;
        }
        .promotion-details .label {
            font-weight: 600;
            color: #475569;
            width: 40%;
        }
        .promotion-details .value {
            color: #0f172a;
        }
        .designation-change {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            margin: 15px 0;
        }
        .designation-change .old-designation {
            color: #64748b;
            text-decoration: line-through;
            font-size: 16px;
        }
        .designation-change .arrow {
            color: #4361ee;
            font-size: 20px;
            font-weight: bold;
        }
        .designation-change .new-designation {
            color: #059669;
            font-size: 18px;
            font-weight: 700;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            background: #e0e7ff;
            color: #4338ca;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }
        .badge-success {
            background: #dcfce7;
            color: #166534;
        }
        .footer {
            padding: 25px;
            text-align: center;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 13px;
        }
        .footer a {
            color: #4361ee;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .social-links {
            margin-top: 15px;
        }
        .social-links a {
            display: inline-block;
            margin: 0 8px;
            color: #94a3b8;
            text-decoration: none;
        }
        .social-links a:hover {
            color: #4361ee;
        }
        
        /* Button Styles - IMPORTANT FIXES */
        .btn-primary {
            display: inline-block;
            padding: 12px 30px;
            background: #4361ee !important;
            background-color: #4361ee !important;
            background-image: linear-gradient(135deg, #4361ee, #3a0ca3) !important;
            color: #ffffff !important;
            text-decoration: none !important;
            border-radius: 6px;
            font-weight: 600;
            font-size: 15px;
            margin-top: 10px;
            border: none;
            text-align: center;
        }
        .btn-primary:hover {
            opacity: 0.9;
            background: #3a0ca3 !important;
            color: #ffffff !important;
        }
        .btn-primary:visited {
            color: #ffffff !important;
        }
        .btn-primary:active {
            color: #ffffff !important;
        }
        
        /* Force white text on button */
        .btn-primary span,
        .btn-primary i,
        .btn-primary * {
            color: #ffffff !important;
        }
        
        .promotion-reason {
            background: #fef9e7;
            border-left: 4px solid #f59e0b;
            padding: 12px 16px;
            margin: 15px 0;
            border-radius: 4px;
            font-style: italic;
            color: #78350f;
        }
        
        /* Fix for email clients */
        .button-wrapper {
            text-align: center;
            margin: 25px 0 10px;
        }
        .button-wrapper p {
            margin-bottom: 10px;
            color: #475569;
        }
        
        .btn-link {
            display: inline-block;
            padding: 12px 30px;
            background: #4361ee;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 15px;
        }
        
        .closing-message {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .closing-message p {
            margin-bottom: 5px;
        }
        .closing-message .regards {
            color: #64748b;
            font-size: 14px;
        }
        
        @media (max-width: 480px) {
            .container {
                margin: 10px;
            }
            .content {
                padding: 20px 15px;
            }
            .header h1 {
                font-size: 20px;
            }
            .designation-change {
                flex-direction: column;
                gap: 8px;
            }
            .promotion-details td {
                display: block;
                padding: 6px 8px;
            }
            .promotion-details .label {
                width: 100%;
                font-weight: 600;
            }
            .btn-primary {
                display: block;
                width: 100%;
                padding: 14px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🎉 Congratulations on Your Promotion!</h1>
            <div class="subtitle">{{ $companyName ?? 'Our Institute' }}</div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <div class="greeting">
                Dear <span>{{ $employee->name ?? 'Employee' }}</span>,
            </div>

            <!-- Main Message -->
            <p>We are pleased to inform you that you have been promoted to a new designation. This promotion is a recognition of your dedication, hard work, and contributions to our organization.</p>

            <!-- Designation Change -->
            <div class="designation-change">
                <span class="old-designation">{{ $oldDesignation ?? 'Previous Designation' }}</span>
                <span class="arrow">➜</span>
                <span class="new-designation">{{ $newDesignation ?? 'New Designation' }}</span>
            </div>

            <!-- Promotion Details -->
            <div class="promotion-details">
                <h4 style="margin-top: 0; margin-bottom: 15px; color: #1a1a2e;">
                    📋 Promotion Details
                </h4>
                <table>
                    <tr>
                        <td class="label">Employee Code</td>
                        <td class="value">{{ $employee->employee_code ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Department</td>
                        <td class="value">{{ $departmentName ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">New Designation</td>
                        <td class="value"><span class="badge badge-success">{{ $newDesignation ?? 'N/A' }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Promotion Date</td>
                        <td class="value">{{ $promotionDate ?? date('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Employment Type</td>
                        <td class="value">
                            <span class="badge">
                                {{ $employee->employment_type ?? 'N/A' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Promotion Reason (if provided) -->
            @if(!empty($promotionReason))
                <div class="promotion-reason">
                    <strong>📝 Remark for Promotion:</strong><br>
                    {{ $promotionReason }}
                </div>
            @endif

            <!-- Additional Message -->
            <div class="message-box">
                <p>
                    <strong>💡 What This Means:</strong><br>
                    Your new designation comes with additional responsibilities and opportunities. We are confident that you will excel in your new role and continue to contribute to our organization's success.
                </p>
            </div>

            <!-- Call to Action - FIXED BUTTON -->
            <div class="button-wrapper">
                <p>Please review your updated profile in the system.</p>
                
                <!-- Option 1: Using inline styles directly on the anchor tag -->
                <a href="{{ url('/login') }}" 
                   style="display: inline-block; 
                          padding: 12px 30px; 
                          background: #4361ee !important; 
                          background-color: #4361ee !important;
                          background-image: linear-gradient(135deg, #4361ee, #3a0ca3) !important;
                          color: #ffffff !important; 
                          text-decoration: none !important; 
                          border-radius: 6px; 
                          font-weight: 600; 
                          font-size: 15px; 
                          border: none;
                          text-align: center;">
                    👤 View Profile
                </a>
                
                <!-- Option 2: Using the class approach (commented out, uncomment if you prefer) -->
                <!-- <a href="{{ url('/login') }}" class="btn-primary">👤 View Profile</a> -->
            </div>

            <!-- Closing Message -->
            <div class="closing-message">
                <p>We look forward to your continued growth and success with us!</p>
                <p class="regards">
                    Best Regards,<br>
                    <strong>{{ $companyName ?? 'HR Team' }}</strong>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0;">
                This is an automated notification from <strong>{{ $companyName ?? 'Our Institute' }}</strong>.
            </p>
            <p style="margin: 8px 0 0; font-size: 12px; color: #94a3b8;">
                If you have any questions, please contact the HR department.
            </p>
            <div class="social-links">
                <a href="#">📧</a>
                <a href="#">📱</a>
                <a href="#">💼</a>
            </div>
        </div>
    </div>
</body>
</html>
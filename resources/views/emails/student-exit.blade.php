<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Exit Confirmation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            background: #f1f5f9;
            padding: 20px;
        }
        .container {
            max-width: 750px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #64748b, #475569);
            color: white;
            padding: 30px 40px;
            text-align: center;
        }
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }
        .header p {
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 40px;
            background: #ffffff;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .greeting strong {
            color: #475569;
        }
        .info-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px 25px;
            margin: 25px 0;
            border-left: 5px solid #64748b;
        }
        .info-box h3 {
            color: #475569;
            font-size: 16px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        /* Student Information Table */
        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 14px;
        }
        .student-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .student-table .label {
            font-weight: 600;
            color: #475569;
            width: 40%;
            background: #f8fafc;
        }
        .student-table .value {
            color: #1e293b;
            width: 60%;
        }
        .student-table tr:last-child td {
            border-bottom: none;
        }
        
        .status-section {
            background: #f1f5f9;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
        }
        .status-section h4 {
            color: #475569;
            margin-bottom: 12px;
            font-size: 15px;
        }
        .status-section ul {
            list-style: none;
            padding: 0;
        }
        .status-section ul li {
            padding: 6px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #475569;
        }
        .status-section ul li .icon {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        .status-section ul li .icon.warning {
            color: #ef4444;
        }
        .status-section ul li .icon.info {
            color: #3b82f6;
        }
        
        .exit-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .exit-badge.Course-Completion {
            background: #dcfce7;
            color: #166534;
        }
        .exit-badge.Mid-Session {
            background: #fef3c7;
            color: #92400e;
        }
        .exit-badge.Cancellation {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .footer {
            padding: 25px 40px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            background: #fafbfc;
        }
        .footer p {
            margin: 4px 0;
        }
        .footer .copyright {
            margin-top: 8px;
        }
        
        @media (max-width: 600px) {
            .content {
                padding: 20px;
            }
            .header {
                padding: 20px;
            }
            .header h1 {
                font-size: 22px;
            }
            .student-table td {
                padding: 8px 10px;
                font-size: 13px;
            }
            .student-table .label {
                width: 45%;
            }
            .student-table .value {
                width: 55%;
            }
            .footer {
                padding: 15px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📄 Student Exit Confirmation</h1>
            <p>{{ $instituteName }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <div class="greeting">
                Dear <strong>{{ $studentName }}</strong>,
            </div>

            <p style="margin-bottom: 20px;">
                This is to confirm that your student account at <strong>{{ $instituteName }}</strong> 
                has been <strong style="color: #ef4444;">EXITED</strong> from the institute.
            </p>

            <!-- Exit Information Box -->
            <div class="info-box">
                <h3>📋 Exit Information</h3>
                <table class="student-table">
                    <tr>
                        <td class="label">Exit Type</td>
                        <td class="value">
                            <span class="exit-badge {{ str_replace(' ', '-', $exitType ?? '') }}">
                                {{ $exitType ?? 'N/A' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Exit Date</td>
                        <td class="value"><strong>{{ $exitDate ?? 'N/A' }}</strong></td>
                    </tr>
                    @if(isset($exitReason) && $exitReason && $exitReason != 'Not specified')
                    <tr>
                        <td class="label">Exit Reason</td>
                        <td class="value">{{ $exitReason }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="label">Registration Number</td>
                        <td class="value"><strong>{{ $registrationNumber ?? 'N/A' }}</strong></td>
                    </tr>
                </table>
            </div>

            <!-- Student Details Table -->
            <h3 style="color: #475569; margin: 25px 0 15px 0; font-size: 16px;">
                👤 Student Details
            </h3>
            <table class="student-table" style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
                <tr>
                    <td class="label">Full Name</td>
                    <td class="value"><strong>{{ $studentName ?? 'N/A' }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Registration Number</td>
                    <td class="value">{{ $registrationNumber ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Date of Birth</td>
                    <td class="value">{{ $dob ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Gender</td>
                    <td class="value">{{ $gender ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Email</td>
                    <td class="value">{{ $email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Mobile Number</td>
                    <td class="value">{{ $mobile ?? 'N/A' }}</td>
                </tr>
                
            </table>

            <!-- Academic Details Table -->
            <h3 style="color: #475569; margin: 25px 0 15px 0; font-size: 16px;">
                📚 Academic Details
            </h3>
            <table class="student-table" style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
                <tr>
                    <td class="label">Course</td>
                    <td class="value">{{ $course ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Course Subtype</td>
                    <td class="value">{{ $subtype ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Section</td>
                    <td class="value"><strong>{{ $section ?? 'N/A' }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Batch</td>
                    <td class="value">{{ $batch ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Academic Year</td>
                    <td class="value">{{ $academic_year ?? 'N/A' }}</td>
                </tr>
            </table>

            <!-- What this means -->
            <div class="status-section">
                <h4>⚠️ What this means for you:</h4>
                <ul>
                    <li>
                        <span class="icon warning">❌</span>
                        Your account is now <strong>inactive</strong>
                    </li>
                    <li>
                        <span class="icon warning">❌</span>
                        You cannot access credits or services
                    </li>
                    <li>
                        <span class="icon warning">❌</span>
                        Your student status has been marked as <strong>exited</strong>
                    </li>
                    <li>
                        <span class="icon info">ℹ️</span>
                        For any queries, please contact the institute administration
                    </li>
                </ul>
            </div>

            <p style="margin-top: 25px; font-size: 15px;">
                Thank you for being a part of <strong>{{ $instituteName }}</strong>. 
                We wish you all the best for your future endeavors.
            </p>

            <p style="margin-top: 20px; color: #94a3b8; font-size: 13px;">
                <strong>Date:</strong> {{ $exitDate ?? now()->format('d-m-Y') }}
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is an automated system-generated message from {{ $instituteName }}.</p>
            <p>Please do not reply to this email.</p>
            <p class="copyright">&copy; {{ date('Y') }} {{ $instituteName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
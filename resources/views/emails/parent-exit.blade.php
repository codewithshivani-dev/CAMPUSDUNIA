<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Exit Confirmation - Parent/Guardian</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
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
        .header h1 { font-size: 28px; font-weight: 700; margin-bottom: 5px; }
        .header p { opacity: 0.9; font-size: 16px; }
        .content { padding: 40px; background: #ffffff; }
        .greeting { font-size: 18px; margin-bottom: 20px; }
        .greeting strong { color: #475569; }
        
        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 14px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
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
        .student-table .value { width: 60%; }
        .student-table tr:last-child td { border-bottom: none; }
        
        .exit-badge {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .exit-badge.Course-Completion { background: #dcfce7; color: #166534; }
        .exit-badge.Mid-Session { background: #fef3c7; color: #92400e; }
        .exit-badge.Cancellation { background: #fee2e2; color: #991b1b; }
        
        .info-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        
        .footer {
            padding: 25px 40px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            background: #fafbfc;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📄 Student Exit Confirmation</h1>
            <p>{{ $instituteName }}</p>
        </div>

        <div class="content">
            <div class="greeting">
                Dear <strong>{{ $parentName ?? 'Parent/Guardian' }}</strong>,
            </div>

            <p style="margin-bottom: 20px;">
                This is to inform you that your student <strong>{{ $studentName }}</strong> 
                (Registration No: <strong>{{ $registrationNumber ?? 'N/A' }}</strong>) 
                has been <strong style="color: #ef4444;">EXITED</strong> from <strong>{{ $instituteName }}</strong>.
            </p>

            <!-- Student Details -->
            <h3 style="color: #475569; margin: 25px 0 15px 0; font-size: 16px;">
                👤 Student Details
            </h3>
            <table class="student-table">
                <tr>
                    <td class="label">Student Name</td>
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
                    <td class="label">Course</td>
                    <td class="value">{{ $course ?? 'N/A' }} {{ $subtype ?? '' }}</td>
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
                <tr>
                    <td class="label">Email</td>
                    <td class="value">{{ $email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Mobile Number</td>
                    <td class="value">{{ $mobile ?? 'N/A' }}</td>
                </tr>
            </table>

            <!-- Exit Details -->
            <h3 style="color: #475569; margin: 25px 0 15px 0; font-size: 16px;">
                📋 Exit Details
            </h3>
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
            </table>

            <div class="info-box">
                <strong>ℹ️ Note:</strong> 
                The student account has been deactivated. Please contact the institute administration 
                if you have any questions regarding this exit.
            </div>

            <p style="margin-top: 20px;">
                Thank you for your continued support.
            </p>

            <p style="margin-top: 15px; color: #94a3b8; font-size: 13px;">
                <strong>Date:</strong> {{ $exitDate ?? now()->format('d-m-Y') }}
            </p>
        </div>

        <div class="footer">
            <p>This is an automated message from {{ $instituteName }}. Please do not reply.</p>
            <p>&copy; {{ date('Y') }} {{ $instituteName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
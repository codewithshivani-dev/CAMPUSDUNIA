<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 650px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            margin: -20px -20px 20px -20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .welcome-message {
            background-color: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .info-table td {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            width: 35%;
            background-color: #f8f9fa;
        }
        .info-value {
            color: #333;
            width: 65%;
        }
        .credentials-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .credentials-box h4 {
            margin-top: 0;
            color: #856404;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-active {
            background-color: #d4edda;
            color: #155724;
        }
        .button {
            display: inline-block;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #5a67d8;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
        .next-steps {
            background-color: #e8eefd;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        @media (max-width: 480px) {
            .container {
                margin: 10px;
                padding: 15px;
            }
            .info-label {
                width: 100%;
                display: block;
            }
            .info-value {
                width: 100%;
                display: block;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 Welcome to {{ $instituteName }}!</h1>
            <p>Your journey begins here</p>
        </div>
        
        <div class="content">
            <div class="welcome-message">
                <p>Dear <strong>{{ $studentName }}</strong>,</p>
                <p>Congratulations! We are pleased to inform you that you have been successfully enrolled at <strong>{{ $instituteName }}</strong>. We are excited to have you as a part of our academic community.</p>
            </div>
            
            <h3>📋 Student Information</h3>
            <table class="info-table">
                <tr>
                    <td class="info-label">Registration Number:</td>
                    <td class="info-value"><strong>{{ $registrationNumber }}</strong></td>
                </tr>
                <tr>
                    <td class="info-label">Student Name:</td>
                    <td class="info-value">{{ $studentName }}</td>
                </tr>
                @if($courseName)
                <tr>
                    <td class="info-label">Course:</td>
                    <td class="info-value">{{ $courseName }}</td>
                </tr>
                @endif
                @if($departmentName)
                <tr>
                    <td class="info-label">Department:</td>
                    <td class="info-value">{{ $departmentName }}</td>
                </tr>
                @endif
                @if($batchName)
                <tr>
                    <td class="info-label">Batch:</td>
                    <td class="info-value">{{ $batchName }}</td>
                </tr>
                @endif
                @if($academicYear)
                <tr>
                    <td class="info-label">Academic Year:</td>
                    <td class="info-value">{{ $academicYear }}</td>
                </tr>
                @endif
                <tr>
                    <td class="info-label">Enrollment Date:</td>
                    <td class="info-value">{{ $enrollmentDate }}</td>
                </tr>
                <tr>
                    <td class="info-label">Status:</td>
                    <td class="info-value"><span class="badge badge-active">✓ Active</span></td>
                </tr>
            </table>
            
            <div class="credentials-box">
                <h4>🔐 Login Credentials</h4>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Email:</td>
                        <td class="info-value"><strong>{{ $email }}</strong></td>
                    </tr>
                    <tr>
                        <td class="info-label">Temporary Password:</td>
                        <td class="info-value"><code style="background: #fff; padding: 2px 6px; border-radius: 3px;">{{ $password }}</code></td>
                    </tr>
                    <tr>
                        <td class="info-label">Login URL:</td>
                        <td class="info-value"><a href="{{ url('/login') }}">{{ url('/login') }}</a></td>
                    </tr>
                </table>
                <p style="margin-top: 10px; font-size: 13px; color: #856404;">
                    <strong>⚠️ Important:</strong> Please login and change your password immediately for security reasons.
                </p>
            </div>
            
            <div class="next-steps">
                <h4>📌 Next Steps</h4>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    <li>Login to your student portal using the credentials above</li>
                    <li>Complete your profile with additional information</li>
                    <li>Upload your profile photo and required documents</li>
                    <li>Review your course schedule and academic calendar</li>
                    <li>Check fee structure and payment deadlines</li>
                    <li>Join your class groups for updates</li>
                </ul>
            </div>
            
         
            
            <div style="text-align: center; display:none;">
                <a href="{{ url('/login') }}" class="button" style="color: white; text-decoration: none;">
                    🚀 Go to Student Portal
                </a>
            </div>
        </div>
        
      
    </div>
</body>
</html>
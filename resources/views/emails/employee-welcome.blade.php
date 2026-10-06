<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Team</title>
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
            max-width: 600px;
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
        .employee-details {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .credentials {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .credentials ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .credentials li {
            margin: 5px 0;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 10px;
            margin: 10px 0;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .info-value {
            color: #333;
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
        .warning {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #721c24;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            background-color: #28a745;
            color: white;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        @media (max-width: 480px) {
            .container {
                margin: 10px;
                padding: 15px;
            }
            .info-grid {
                grid-template-columns: 1fr;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to the Team! 🎉</h1>
            <p>Your account has been successfully created</p>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $employee->name }}</strong>,</p>
            
            <p>We are pleased to inform you that your employee account has been created successfully</p>
            
            <!-- Employee Details Section -->
            <div class="employee-details">
                <h3 style="margin-top: 0; color: #667eea;">📋 Your Employee Details</h3>
                <div class="info-grid">
                    <div class="info-label">Employee ID:</div>
                    <div class="info-value"><strong>{{ $employee->employee_code ?? $employee->employee_id }}</strong> <span class="badge">New</span></div>
                    
                    <div class="info-label">Full Name:</div>
                    <div class="info-value">{{ $employee->name }}</div>
                    
                    <div class="info-label">Department:</div>
                    <div class="info-value">{{ $departmentName ?? 'N/A' }}</div>
                    
                    <div class="info-label">Designation:</div>
                    <div class="info-value">{{ $designationName ?? $employee->designation ?? $role ?? 'N/A' }}</div>
                    
                    <div class="info-label">Date of Joining:</div>
                    <div class="info-value">{{ $dateOfJoining ?? ($employee->doj ? \Carbon\Carbon::parse($employee->doj)->format('d-m-Y') : 'N/A') }}</div>
                    
                    <div class="info-label">Employment Type:</div>
                    <div class="info-value">{{ $employee->employment_type ?? 'N/A' }}</div>
                </div>
            </div>
            
            <!-- Login Credentials Section -->
            <div class="credentials">
                <h3 style="margin-top: 0; color: #856404;">🔐 Login Credentials</h3>
                <ul>
                    <li><strong>Email:</strong> {{ $employee->email }}</li>
                    <li><strong>Password:</strong> <code style="background: #fff; padding: 2px 6px; border-radius: 3px;">{{ $password }}</code></li>
                    <li><strong>Role:</strong> {{ ucfirst($role) }}</li>
                    <li><strong>Account Status:</strong> Active ✅</li>
                </ul>
            </div>
            
            <!-- Additional Info -->
            <!-- <div class="info-grid">
                <div class="info-label">Reporting Manager:</div>
                <div class="info-value">{{ $reportingManager ?? 'To be assigned' }}</div>
                
                <div class="info-label">Work Location:</div>
                <div class="info-value">{{ $workLocation ?? $employee->city ?? 'N/A' }}</div>
            </div>
             -->
            <!-- Important Instructions -->
            <div class="warning">
                <strong>⚠️ Important Instructions:</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    <li>Please login and change your password immediately for security reasons.</li>
                    <li>Do not share your login credentials with anyone.</li>
                    <li>For any technical assistance, contact IT support.</li>
                    <li>Keep your profile information up to date.</li>
                </ul>
            </div>
            
            <!-- Login Button -->
            <div style="text-align: center; display:none;">
                <a href="{{ url('/login') }}" class="button" style="color: white; text-decoration: none;">
                    🔑 Click Here to Login
                </a>
            </div>
            
            <!-- Quick Tips -->
            <div style="background-color: #e8eefd; padding: 15px; border-radius: 4px; margin: 20px 0;">
                <h4 style="margin-top: 0;">💡 Quick Tips:</h4>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Complete your profile with additional information</li>
                    <li>Upload your professional photo and signature</li>
                    <li>Review company policies and HR documents</li>
                    <li>Set up two-factor authentication for added security</li>
                </ul>
            </div>
        </div>
        
     
    </div>
</body>
</html>
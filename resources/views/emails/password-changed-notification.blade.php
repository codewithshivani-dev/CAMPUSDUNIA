<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .header {
            background: #3f6fdb;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            margin: -20px -20px 20px -20px;
        }
        .content {
            padding: 20px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
        .alert {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Password Changed</h2>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $name }}</strong>,</p>
            
            <p>Your password for your <strong>{{ $userType }}</strong> account has been changed by an administrator.</p>
            
            @if(!empty($password))
                <div class="alert d-none">
                    <strong>New Password:</strong><br>
                    <span style="font-size:1.1rem; letter-spacing:0.03em;">{{ $password }}</span>
                </div>
            @endif

            <div class="alert">
                <strong>Details:</strong><br>
                Changed by: {{ $changed_by }}<br>
                Changed at: {{ $changed_at }}
            </div>
            
            <p>If you did not request this change, please contact your administrator immediately.</p>
            
            <p>For security reasons, we recommend that you:</p>
            <ul>
                <li>Log in to your account using the new password</li>
                <li>Change your password after logging in (if you prefer)</li>
                <li>Contact support if you notice any unusual activity</li>
            </ul>
            
            <p>Thank you for using our services.</p>
            
            <p>Best regards,<br>
            <strong>Institute Management Team</strong></p>
        </div>
        
        <div class="footer">
            <p>This is an automated notification. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Institute Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

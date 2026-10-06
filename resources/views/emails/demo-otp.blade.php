<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Login Verification Code</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            padding: 40px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 25px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #1a237e;
            font-size: 28px;
            margin: 0;
            font-weight: 700;
        }
        .header p {
            color: #666;
            margin: 10px 0 0;
        }
        .otp-box {
            background: #f8f9ff;
            border: 2px solid #e3edff;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 48px;
            font-weight: 700;
            color: #1a237e;
            letter-spacing: 12px;
            font-family: 'Courier New', monospace;
            padding: 10px 0;
        }
        .info-text {
            color: #666;
            font-size: 14px;
            text-align: center;
            margin: 20px 0;
        }
        .info-text strong {
            color: #1a237e;
        }
        .footer {
            text-align: center;
            color: #999;
            font-size: 13px;
            border-top: 1px solid #f0f0f0;
            padding-top: 25px;
            margin-top: 30px;
        }
        .role-badge {
            display: inline-block;
            background: #e3edff;
            color: #1a237e;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .security-note {
            background: #fff3e0;
            border-left: 4px solid #ff9800;
            padding: 12px 16px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 14px;
            color: #666;
        }
        .security-note i {
            color: #ff9800;
        }
        @media (max-width: 480px) {
            .card {
                padding: 25px 20px;
            }
            .otp-code {
                font-size: 32px;
                letter-spacing: 8px;
            }
            .header h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>🔐 Demo Access Verification</h1>
                <p>Secure login to your dashboard</p>
            </div>

            <div style="text-align: center; margin-bottom: 20px;">
                <span class="role-badge">Role: {{ $role ?? 'User' }}</span>
            </div>

            <p style="font-size: 16px; margin-bottom: 10px;">Hello,</p>
            <p style="font-size: 16px; color: #555;">
                You've requested a secure demo login for the <strong>{{ $role ?? 'User' }}</strong> role.
                Use the verification code below to complete your login:
            </p>

            <div class="otp-box">
                <div style="font-size: 14px; color: #666; margin-bottom: 10px;">
                    Your 6-digit verification code
                </div>
                <div class="otp-code">{{ $otp }}</div>
                <div style="font-size: 13px; color: #888; margin-top: 10px;">
                    Valid for 10 minutes
                </div>
            </div>

            <div class="security-note">
                <i>🔒</i> For security, please do not share this code with anyone. 
                If you didn't request this, please ignore this email.
            </div>

            <div class="info-text">
                <strong>Didn't receive the code?</strong> Check your spam folder or 
                <a href="{{ route('login') }}" style="color: #2196f3; text-decoration: none; font-weight: 500;">
                    request a new one
                </a>.
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('login') }}" style="display: inline-block; background: #2196f3; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                    Return to Login
                </a>
            </div>

            <div class="footer">
                <p style="margin: 0;">
                    This is an automated message from your Demo System.<br>
                    For assistance, contact your system administrator.
                </p>
                <p style="margin: 10px 0 0; font-size: 12px; color: #bbb;">
                    © {{ date('Y') }} Demo System. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
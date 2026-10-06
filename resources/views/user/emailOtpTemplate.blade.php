<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Verification OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #007bff;
            color: #ffffff;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            letter-spacing: 5px;
            margin: 20px 0;
            padding: 15px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }
        .validity {
            color: #666;
            font-size: 14px;
            margin-top: 15px;
        }
        .footer {
            border-top: 1px solid #ddd;
            margin-top: 20px;
            padding-top: 15px;
            font-size: 12px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Email Verification</h2>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>Your One-Time Password (OTP) for email verification is:</p>
            <div class="otp-code">{{ $otp }}</div>
            <p class="validity">This OTP is valid for <strong>2 minutes</strong> only.</p>
            <p>Please do not share this OTP with anyone.</p>
        </div>
        <div class="footer">
            <p>If you didn't request this email, please ignore it.</p>
        </div>
    </div>
</body>
</html>

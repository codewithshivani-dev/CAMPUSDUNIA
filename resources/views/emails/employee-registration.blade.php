<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f4f7fb;
            color: #243044;
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .container {
            max-width: 620px;
            margin: 24px auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(15, 23, 42, .08);
        }

        .header {
            padding: 28px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 28px;
        }

        .credentials,
        .notice {
            margin: 22px 0;
            padding: 16px 18px;
            border-radius: 8px;
        }

        .credentials {
            background: #eff6ff;
            border-left: 4px solid #2563eb;
        }

        .notice {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
        }

        .credentials p {
            margin: 7px 0;
        }

        .button {
            display: inline-block;
            margin: 18px 0;
            padding: 11px 18px;
            color: #fff !important;
            background: #2563eb;
            border-radius: 7px;
            text-decoration: none;
            font-weight: 700;
        }

        .footer {
            padding: 18px 28px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Employee Registration</h1>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $employee->name }}</strong>,</p>
            <p>Your employee account has been registered successfully.</p>

            <div class="credentials">
                <strong>Login Details</strong>
                <p><strong>Employee ID:</strong> {{ $employee->employee_code ?? $employee->employee_id }}</p>
                <p><strong>Email:</strong> {{ $employee->email }}</p>
                <p><strong>Temporary Password:</strong> {{ $password }}</p>
                <a class="button" href="{{ url('/login') }}">Open Login</a>
            </div>

            <div class="notice">
                Your registration has been submitted. Final onboarding and completion status may still require the
                applicable administrative approval or HR process.
            </div>

            <p>Please sign in and change your temporary password after your first login.</p>
            <p>Regards,<br>{{ $institute_name ?? config('app.name') }}</p>
        </div>
        <div class="footer">This is an automated employee registration message.</div>
    </div>
</body>

</html>
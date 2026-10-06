<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Application Received</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f3f6fb;
            font-family: Arial, Helvetica, sans-serif;
            color: #333333;
        }

        .email-wrapper {
            width: 100%;
            padding: 40px 15px;
            box-sizing: border-box;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        }

        .email-header {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            padding: 30px 25px;
            text-align: center;
            color: #ffffff;
        }

        .email-header h1 {
            margin: 0;
            font-size: 25px;
            font-weight: 700;
        }

        .email-header p {
            margin: 10px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .email-body {
            padding: 35px 30px;
        }

        .email-body h2 {
            margin-top: 0;
            color: #1f2937;
            font-size: 21px;
        }

        .email-body p {
            font-size: 15px;
            line-height: 1.7;
            color: #4b5563;
        }

        .lead-box {
            margin: 25px 0;
            padding: 18px;
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            text-align: center;
        }

        .lead-box span {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            color: #6b7280;
        }

        .lead-box strong {
            font-size: 20px;
            color: #1d4ed8;
            letter-spacing: 0.5px;
        }

        .button-wrapper {
            margin: 30px 0;
            text-align: center;
        }

        .journey-button {
            display: inline-block;
            padding: 14px 28px;
            background-color: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 7px;
            font-size: 15px;
            font-weight: 600;
        }

        .journey-button:hover {
            background-color: #1d4ed8;
        }

        .email-footer {
            padding: 20px 25px;
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }

        .email-footer p {
            margin: 0;
            font-size: 12px;
            line-height: 1.6;
            color: #9ca3af;
        }

        @media only screen and (max-width: 600px) {
            .email-wrapper {
                padding: 20px 10px;
            }

            .email-body {
                padding: 25px 20px;
            }

            .email-header h1 {
                font-size: 21px;
            }
        }
    </style>
</head>

<body>

    <div class="email-wrapper">

        <div class="email-container">

            <!-- Header -->
            <div class="email-header">
                <h1>Interview Application Received</h1>
                <p>Thank you for applying with us</p>
            </div>

            <!-- Body -->
            <div class="email-body">

                <h2>
                    Hello {{ $lead->name ?? 'Candidate' }},
                </h2>

                <p>
                    Your interview application has been received successfully.
                    Our team will review your application and contact you with
                    further updates.
                </p>

                <!-- Application Reference ID -->
                <div class="lead-box">
                    <span>Your Application Reference ID</span>
                    <strong>{{ $lead->reference_id ?? $lead->lead_id }}</strong>
                </div>

                <p>
                    You can track your candidate journey by clicking the
                    button below:
                </p>

                <!-- Button -->
                <div class="button-wrapper">
                    <a href="{{ $candidateJourneyUrl }}" class="journey-button">
                        View Candidate Journey
                    </a>
                </div>

                <p>
                    If you have any questions, please contact our support team.
                </p>

                <p>
                    Thank you for your interest.
                </p>

                <p>
                    <strong>Regards,</strong><br>
                    Recruitment Team
                </p>

            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p>
                    This is an automated email. Please do not reply directly
                    to this message.
                </p>

                <p>
                    &copy; {{ date('Y') }} All Rights Reserved.
                </p>
            </div>

        </div>

    </div>

</body>

</html>
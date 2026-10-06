@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Loan Journey | Step by Step Information</title>
    <style>

        .container {
            max-width: 900px;
            margin:auto;
            width: 100%;
            background: white;
            border-radius: 2rem;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .header {
            background: #0f2f44;
            color: white;
            padding: 1.6rem 2rem;
            text-align: center;
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .header p {
            opacity: 0.85;
            font-size: 0.9rem;
            margin-top: 6px;
        }

        .content {
            padding: 2rem;
        }

        /* Simple step cards */
        .step-card {
            background: #fafcff;
            border-radius: 1.2rem;
            padding: 1.2rem 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid #e9edf2;
            transition: all 0.2s;
        }

        .step-card:hover {
            border-color: #cbdde9;
            background: #ffffff;
        }

        .step-number {
            display: inline-block;
            width: 32px;
            height: 32px;
            background: #1f6392;
            color: white;
            border-radius: 60px;
            text-align: center;
            line-height: 32px;
            font-weight: 700;
            font-size: 0.9rem;
            margin-right: 12px;
        }

        .step-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e3a5f;
            display: inline-block;
        }

        .step-desc {
            margin-top: 10px;
            margin-left: 44px;
            color: #4b5e77;
            line-height: 1.5;
            font-size: 0.95rem;
        }

        .step-detail {
            margin-top: 8px;
            margin-left: 44px;
            font-size: 0.85rem;
            color: #2e7d5e;
            background: #eef7ef;
            padding: 8px 12px;
            border-radius: 0.8rem;
            display: inline-block;
        }

        hr {
            margin: 1.2rem 0;
            border: none;
            height: 1px;
            background: #e2edf7;
        }

        .notice-area {
            background: #fff9ef;
            border-left: 5px solid #f3a712;
            padding: 1rem 1.2rem;
            border-radius: 1rem;
            margin: 1.5rem 0;
        }

        .notice-area p {
            font-size: 0.9rem;
            color: #8a6e2e;
        }

        .redirect-btn {
            display: block;
            width: 100%;
            max-width: 300px;
            margin: 1.5rem auto 0;
            background: #1f6e43;
            color: white;
            text-align: center;
            padding: 0.9rem 1.5rem;
            border-radius: 60px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .redirect-btn:hover {
            background: #0f5a38;
            transform: translateY(-2px);
        }

        .footer-note {
            text-align: center;
            font-size: 0.7rem;
            color: #7c8aa0;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #eef2f8;
        }

        @media (max-width: 550px) {
            .content {
                padding: 1.2rem;
            }
            .step-title {
                font-size: 1rem;
            }
            .step-desc, .step-detail {
                margin-left: 0;
            }
            .step-number {
                margin-bottom: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>
                <span>📋</span> Loan Journey
                <span>➡️</span>
            </h1>
            <p>Complete process information • All steps explained</p>
        </div>

        <div class="content">
            <!-- Step 1: Login with Register Number & Verify -->
            <div class="step-card">
                <div>
                    <span class="step-number">1</span>
                    <span class="step-title">🔐 Login with Register Number & Verify</span>
                </div>
                <div class="step-desc">
                    Use your unique register number (provided during loan enquiry) to log in. 
                    An OTP will be sent to your registered mobile number for verification.
                </div>
                <div class="step-detail">
                    ✓ Required: Register Number + OTP verification | Valid for 1 minutes
                </div>
            </div>

            <!-- Step 2: Complete Documents -->
            <div class="step-card">
                <div>
                    <span class="step-number">2</span>
                    <span class="step-title">📄 Complete Documents</span>
                </div>
                <div class="step-desc">
                    PAN and Aadhaar will be verified digitally. Please upload valid income, bank, and employment documents for verification.
                </div>
                <div class="step-detail">
                    ✓ Accepted formats: PDF, JPEG, PNG | Max size: 5MB per file
                </div>
            </div>

            <!-- Step 3: Check Eligibility -->
            <div class="step-card">
                <div>
                    <span class="step-number">3</span>
                    <span class="step-title">✅ Check Eligibility</span>
                </div>
                <div class="step-desc">
                    System evaluates your credit score, monthly income, existing debts, 
                    and employment stability to determine loan eligibility and maximum loan amount.
                </div>
                <div class="step-detail">
                    ✓ Minimum income: ₹25,000/month | Credit score: 650+ required
                </div>
            </div>

            <!-- Step 4: Loan Approval -->
            <div class="step-card">
                <div>
                    <span class="step-number">4</span>
                    <span class="step-title">🏦 Loan Approval</span>
                </div>
                <div class="step-desc">
                    Upon successful eligibility, loan committee reviews your application. 
                    Includes interest rate finalization, loan tenure, and disbursement schedule.
                </div>
                <div class="step-detail">
                    ✓ Processing time: 24-48 hours after verification
                </div>
            </div>

            <!-- Step 5: Final Redirect -->
            <div class="step-card">
                <div>
                    <span class="step-number">5</span>
                    <span class="step-title">🚀 Final Redirect</span>
                </div>
                <div class="step-desc">
                    After full approval, you'll be redirected to the loan agreement portal 
                    to complete e-signature and receive funds directly to Institute bank account.
                </div>
                <div class="step-detail">
                    ✓ Redirect to: Loan confirmation & disbursement portal
                </div>
            </div>

            <!-- Notice message about the process -->
            <div class="notice-area">
                <p>📢 <strong>Process Notice:</strong> Complete all steps in sequence. After login verification, you can proceed with documents, eligibility check, and approval. Final redirect happens only after loan approval.</p>
            </div>

            <!-- Redirect button to continue journey -->
            <button id="redirectBtn" class="redirect-btn">Continue to Loan Portal →</button>
            <div class="footer-note">
                Clicking will redirect you to complete the loan process
            </div>
        </div>
    </div>

    <script>
        const redirectBtn = document.getElementById('redirectBtn');
        const REDIRECT_URL = "https://loan-journey.campusdunia.co.in/authenticate/web/user/48146619a0d2/f3a5e529-e6f2-4d19-86df-56fcb07ba987";
        
        if (redirectBtn) {
            redirectBtn.addEventListener('click', function() {
                window.location.href = REDIRECT_URL;
            });
        }
    </script>
</body>
@endsection
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Loan Application Status</title>
    <style>
        .card {
            width: 90%;
            max-width: 650px;
            margin: 40px auto;
            background: white;
            border-radius: 32px;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.2), 0 2px 5px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            transition: transform 0.2s ease;
        }

        .content {
            padding: 40px 32px 48px;
        }

        .icon-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 24px;
        }

        .clock-circle {
            background: #fef3c7;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.15);
        }

        .success-circle {
            background: #dcfce7;
            box-shadow: 0 8px 20px rgba(34, 197, 94, 0.15);
        }

        .rejected-circle {
            background: #fee2e2;
        }

        .pending-circle {
            background: #fef3c7;
        }

        .clock-icon {
            font-size: 48px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .success-icon {
            font-size: 48px;
            color: #16a34a;
        }

        h1 {
            font-size: 1.9rem;
            font-weight: 700;
            text-align: center;
            color: #1e293b;
            margin-bottom: 12px;
            letter-spacing: -0.3px;
        }

        .subhead {
            text-align: center;
            color: #475569;
            font-size: 1rem;
            line-height: 1.5;
            margin-bottom: 32px;
            border-bottom: 1px solid #e9eef3;
            padding-bottom: 24px;
        }

        .status-card {
            background: #f8fafc;
            border-radius: 24px;
            padding: 20px 24px;
            margin-bottom: 28px;
            border: 1px solid #e2e8f0;
        }

        .status-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 16px;
        }

        .status-label {
            font-weight: 600;
            color: #334155;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-value {
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-pending {
            background: #fef9e3;
            color: #b45309;
        }

        .status-approved {
            background: #dcfce7;
            color: #15803d;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .loan-details {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 12px;
        }

        .detail-item {
            flex: 1;
        }

        .detail-label {
            font-size: 0.75rem;
            color: #5b6e8c;
            text-transform: uppercase;
            font-weight: 500;
            letter-spacing: 0.4px;
        }

        .detail-value {
            font-size: 1.3rem;
            font-weight: 700;
            color: #0f172a;
            margin-top: 4px;
        }

        .progress-section {
            margin: 28px 0 24px;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: #334155;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .progress-bar-bg {
            background-color: #e2e8f0;
            border-radius: 60px;
            height: 8px;
            overflow: hidden;
        }

        .progress-fill {
            width: 65%;
            background: linear-gradient(90deg, #f59e0b, #f97316);
            height: 100%;
            border-radius: 60px;
            animation: pulseWidth 1.8s ease-in-out infinite alternate;
        }

        @keyframes pulseWidth {
            0% { width: 60%; opacity: 0.9; }
            100% { width: 72%; opacity: 1; }
        }

        .info-message {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 16px 20px;
            border-radius: 18px;
            margin: 28px 0 24px;
            font-size: 0.95rem;
            color: #1e40af;
            line-height: 1.45;
        }

        .success-message {
            background: #f0fdf4;
            border-left: 4px solid #22c55e;
            color: #166534;
        }

        .warning-message {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            color: #92400e;
        }

        .error-message {
            background: #fef2f2;
            border-left: 4px solid #dc2626;
            color: #991b1b;
        }

        .proceed-button {
            width: 100%;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            border: none;
            padding: 16px 24px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 48px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            margin-bottom: 20px;
            box-shadow: 0 8px 20px rgba(249, 115, 22, 0.3);
        }

        .proceed-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(249, 115, 22, 0.4);
            background: linear-gradient(135deg, #ea580c, #c2410c);
        }

        .customer-care-button {
            width: 100%;
            background: white;
            color: #1e293b;
            border: 1.5px solid #cbd5e1;
            padding: 14px 24px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 48px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 16px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: #f8fafc;
        }

        .customer-care-button:hover {
            background: #f1f5f9;
            border-color: #f97316;
            color: #ea580c;
            transform: translateY(-1px);
        }

        .support-text {
            font-size: 0.85rem;
            color: #4b5563;
            text-align: center;
            margin-top: 24px;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .support-text a {
            color: #f97316;
            text-decoration: none;
            font-weight: 500;
        }

        .support-text a:hover {
            text-decoration: underline;
        }

        .refresh-note {
            background: #f1f5f9;
            border-radius: 20px;
            text-align: center;
            font-size: 0.8rem;
            padding: 12px;
            color: #3b4252;
            margin-top: 32px;
        }

        button {
            background: none;
            border: none;
            color: #f97316;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.85rem;
        }

        .terms-text {
            font-size: 0.75rem;
            text-align: center;
            color: #6b7280;
            margin-top: 16px;
        }

        .status-message-box {
            background: #f8fafc;
            border-radius: 20px;
            padding: 20px;
            margin: 24px 0;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .status-icon-large {
            font-size: 48px;
            margin-bottom: 12px;
            display: inline-block;
        }

        @media (max-width: 480px) {
            .content {
                padding: 28px 20px 36px;
            }
            h1 {
                font-size: 1.6rem;
            }
            .detail-value {
                font-size: 1.1rem;
            }
            .proceed-button, .customer-care-button {
                padding: 14px 20px;
                font-size: 1rem;
            }
        }

        .animate-check {
            animation: checkmark 0.6s ease-in-out;
        }

        @keyframes checkmark {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); opacity: 1; }
        }

        .toast-notification {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) scale(0.9);
            background: #1e293b;
            color: white;
            padding: 12px 24px;
            border-radius: 60px;
            font-size: 0.9rem;
            font-weight: 500;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.2s, transform 0.2s;
            pointer-events: none;
            white-space: nowrap;
            backdrop-filter: blur(8px);
            background: rgba(30, 41, 59, 0.95);
        }

        .toast-notification.show {
            opacity: 1;
            transform: translateX(-50%) scale(1);
        }

        @media (max-width: 640px) {
            .toast-notification {
                white-space: normal;
                text-align: center;
                max-width: 85%;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
<div class="card">
    <div class="content">
        @php
            // Determine loan status for message display
            $status = isset($loanRequestDetails) ? $loanRequestDetails->loan_status : 'unknown';
        @endphp

        @if($status == 'approved')
            <!-- ========== APPROVED STATUS MESSAGE ========== -->
            <div class="icon-wrapper">
                <div class="clock-circle success-circle">
                    <span class="success-icon animate-check">✓</span>
                </div>
            </div>
            <h1>Loan Approved! 🎉</h1>
            <div class="subhead">
                Great news! Your loan has been successfully approved.
            </div>

            <div class="status-card">
                <div class="status-row">
                    <span class="status-label">Application status</span>
                    <span class="status-value status-approved">✅ Approved</span>
                </div>
                <div class="loan-details">
                    <div class="detail-item">
                        <div class="detail-label">Approved amount</div>
                        <div class="detail-value">₹{{ number_format($loanRequestDetails->loan_amount, 2) }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Loan term</div>
                        <div class="detail-value">{{ $loanRequestDetails->scheme_tenure }} months</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Interest rate</div>
                        <div class="detail-value">{{ $loanRequestDetails->interest_rate ?? '12' }}% p.a.</div>
                    </div>
                </div>
                @if(isset($loanRequestDetails->emi_amount))
                <div class="loan-details" style="margin-top: 16px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                    <div class="detail-item">
                        <div class="detail-label">Monthly EMI</div>
                        <div class="detail-value">₹{{ number_format($loanRequestDetails->emi_amount, 2) }}</div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Status-specific message for APPROVED -->
            <div class="info-message success-message">
                <strong>✅ Status Message:</strong> Congratulations! Your loan application has been reviewed and approved. The approved amount will be disbursed after you complete the loan agreement process. Please click the button below to proceed with the agreement and disbursement.
            </div>

            <button class="proceed-button" id="proceedBtn">
                Continue to Loan Agreement →
            </button>

            <div class="terms-text">
                By proceeding, you agree to the loan terms and conditions.
            </div>

        @elseif($status == 'rejected')
            <!-- ========== REJECTED STATUS MESSAGE ========== -->
            <div class="icon-wrapper">
                <div class="clock-circle rejected-circle">
                    <span style="font-size: 48px;">⚠️</span>
                </div>
            </div>
            <h1>Loan Application Declined</h1>
            <div class="subhead">
                We regret to inform you about the status of your application.
            </div>

            <div class="status-card">
                <div class="status-row">
                    <span class="status-label">Application status</span>
                    <span class="status-value status-rejected">❌ Declined</span>
                </div>
                <div class="loan-details">
                    <div class="detail-item">
                        <div class="detail-label">Application ID</div>
                        <div class="detail-value">{{ $loanRequestDetails->loan_request_id }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Requested amount</div>
                        <div class="detail-value">₹{{ number_format($loanRequestDetails->loan_amount, 2) }}</div>
                    </div>
                </div>
            </div>

            <!-- Status-specific message for REJECTED -->
            <div class="info-message error-message">
                <strong>❌ Status Message:</strong> Your loan application has been declined. Reason: {{ $loanRequestDetails->rejection_reason ?? 'Your application did not meet our current lending criteria.' }} If you believe this is an error or wish to reapply, please contact our customer care team for assistance. They will guide you through the next steps.
            </div>

            <button class="customer-care-button" id="contactSupportBtnRejected">
                📞 Contact Customer Care for Assistance
            </button>

            <div class="support-text">
                <span>✉️ <a href="mailto:support@hifinance.com">support@hifinance.com</a></span>
                <span>📞 <a href="tel:+18885550123">1-888-555-0123</a></span>
                <span>💬 Live chat available 9 AM - 8 PM</span>
            </div>

        @elseif($status == 'pending' || $status == 'under_review')
            <!-- ========== PENDING STATUS MESSAGE ========== -->
            <div class="icon-wrapper">
                <div class="clock-circle pending-circle">
                    <span class="clock-icon">⏳</span>
                </div>
            </div>
            <h1>Loan Under Review</h1>
            <div class="subhead">
                Your application is being processed by our team.
            </div>

            <div class="status-card">
                <div class="status-row">
                    <span class="status-label">Application status</span>
                    <span class="status-value status-pending">⏱️ Pending Review</span>
                </div>
                <div class="loan-details">
                    <div class="detail-item">
                        <div class="detail-label">Requested amount</div>
                        <div class="detail-value">₹{{ number_format($loanRequestDetails->loan_amount, 2) }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Loan term</div>
                        <div class="detail-value">{{ $loanRequestDetails->scheme_tenure }} months</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Application ID</div>
                        <div class="detail-value">{{ $loanRequestDetails->loan_request_id }}</div>
                    </div>
                </div>
            </div>

            <!-- Status-specific message for PENDING -->
            <div class="info-message">
                <strong>⏳ Status Message:</strong> Your loan application is currently under review by our underwriting team. We are verifying your income, credit history, and other details. This process typically takes 30-60 minutes. You will receive an email and SMS notification once a decision is made. No action is required from your side at this moment.
            </div>

            <div class="progress-section">
                <div class="progress-label">
                    <span>Processing status</span>
                    <span>Underwriting review in progress</span>
                </div>
                <div class="progress-bar-bg">
                    <div class="progress-fill"></div>
                </div>
            </div>

            <div class="support-text">
                <span>📞 Need assistance? <a href="tel:+18885550123">Call Support</a></span>
                <span>✉️ <a href="mailto:loans@example.com">Email Us</a></span>
            </div>

            <div class="refresh-note">
                🔄 Page auto-refreshes every 30 seconds to check status &nbsp;|&nbsp;
                <button id="manualRefreshBtn">Refresh now</button>
            </div>

        @else
            <!-- ========== UNKNOWN / ERROR STATUS MESSAGE ========== -->
            <div class="icon-wrapper">
                <div class="clock-circle">
                    <span style="font-size: 48px;">📋</span>
                </div>
            </div>
            <h1>Status Unavailable</h1>
            <div class="subhead">
                We're unable to retrieve your loan application status.
            </div>

            <!-- Status-specific message for UNKNOWN -->
            <div class="info-message warning-message">
                <strong>⚠️ Status Message:</strong> We are currently unable to fetch the latest status of your loan application. This could be due to a technical issue or because the application reference is invalid. Please contact our customer care team immediately to resolve this and get accurate information about your application.
            </div>

            <div class="status-message-box">
                <div class="status-icon-large">🆘</div>
                <p style="margin-top: 8px; color: #475569;"><strong>What you can do:</strong></p>
                <p style="margin-top: 8px; font-size: 0.9rem;">1. Check your application ID and try again later.<br>2. Contact customer care with your application details.<br>3. Visit your nearest branch for assistance.</p>
            </div>

            <button class="customer-care-button" id="contactSupportBtnUnknown">
                📞 Connect with Customer Care
            </button>

            <div class="support-text">
                <span>📞 <a href="tel:+18885550123">Call: 1-888-555-0123</a></span>
                <span>✉️ <a href="mailto:care@hifinance.com">care@hifinance.com</a></span>
                <span>🏢 Visit branch</span>
            </div>
        @endif
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toastMsg" class="toast-notification">Message</div>

<script>
    // Helper function to show toast notifications
    function showToast(message, duration = 4000) {
        const toast = document.getElementById('toastMsg');
        if (!toast) return;
        toast.textContent = message;
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, duration);
    }

    // Function to handle customer care contact (shows message only, no automated reinitiate)
    function handleCustomerCare() {
        showToast("📞 Our customer care team will assist you. Please call 1-888-555-0123 or email support@hifinance.com", 5000);
    }

    // Attach customer care button for rejected status
    const rejectedSupportBtn = document.getElementById('contactSupportBtnRejected');
    if (rejectedSupportBtn) {
        rejectedSupportBtn.addEventListener('click', (e) => {
            e.preventDefault();
            handleCustomerCare();
            showToast("For reinitiating your declined application, please speak to our support executive. They're here to help!", 4500);
        });
    }

    // Attach customer care button for unknown status
    const unknownSupportBtn = document.getElementById('contactSupportBtnUnknown');
    if (unknownSupportBtn) {
        unknownSupportBtn.addEventListener('click', (e) => {
            e.preventDefault();
            handleCustomerCare();
            showToast("Our support team can help recover your application status. Please reach out via call or email.", 4500);
        });
    }

    // For approved status: proceed button action
    @if(isset($loanRequestDetails) && $loanRequestDetails->loan_status == 'approved')
        const proceedBtn = document.getElementById('proceedBtn');
        if (proceedBtn) {
            proceedBtn.addEventListener('click', function() {
                const proceedUrl = "https://uat.customerjourney.flyhifinance.com/welcome";
                this.innerHTML = 'Loading... ⏳';
                this.disabled = true;
                window.location.href = proceedUrl;
            });
        }
    @endif

    // For pending status: auto-refresh and manual refresh
    @if(isset($loanRequestDetails) && $loanRequestDetails->loan_status == 'pending' || isset($loanRequestDetails) && $loanRequestDetails->loan_status == 'under_review')
        // Auto-refresh every 30 seconds
        setInterval(() => {
            console.log("Auto-checking loan status...");
            window.location.reload();
        }, 30000);

        const manualRefreshBtn = document.getElementById('manualRefreshBtn');
        if (manualRefreshBtn) {
            manualRefreshBtn.addEventListener('click', () => {
                window.location.reload();
            });
        }

        // Add last check timestamp
        const refreshNote = document.querySelector('.refresh-note');
        if (refreshNote) {
            let timeSpan = refreshNote.querySelector('.last-check-time');
            if (!timeSpan) {
                timeSpan = document.createElement('span');
                timeSpan.className = 'last-check-time';
                timeSpan.style.marginLeft = '12px';
                timeSpan.style.fontSize = '0.7rem';
                refreshNote.appendChild(timeSpan);
            }
            function updateTimestamp() {
                if (timeSpan) timeSpan.innerText = `⏱️ Last check: ${new Date().toLocaleTimeString()}`;
            }
            updateTimestamp();
            setInterval(updateTimestamp, 10000);
        }
    @endif

    // For mailto and tel links - show supportive message but no auto reinitiate
    const mailLinks = document.querySelectorAll('a[href^="mailto:"]');
    mailLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            setTimeout(() => {
                showToast("Our support team will respond to your query about loan application status.", 3500);
            }, 100);
        });
    });

    const telLinks = document.querySelectorAll('a[href^="tel:"]');
    telLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            showToast("Calling customer care. They will assist you with your loan application status.", 3500);
        });
    });

    // Log the current status for debugging
    @if(isset($loanRequestDetails))
        console.log("Current loan status: {{ $loanRequestDetails->loan_status }}");
        console.log("Status message displayed according to loan status");
    @else
        console.log("No loan request details found - showing unknown status message");
    @endif
</script>

@endsection
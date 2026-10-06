<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>OTP Verification | Student Admission</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f4fc 0%, #e9eefa 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 44px;
            padding: 48px 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 30px 60px -20px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(99, 102, 241, 0.08);
            transition: all 0.2s ease;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #eef2ff;
            padding: 6px 16px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 600;
            color: #1e40af;
            margin-bottom: 28px;
            letter-spacing: 0.2px;
            border: 1px solid #c7d2fe;
        }

        h1 {
            font-size: 34px;
            font-weight: 700;
            color: #0a0c15;
            margin-bottom: 10px;
            letter-spacing: -0.8px;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .sub {
            color: #475569;
            font-size: 15px;
            line-height: 1.5;
            margin-bottom: 38px;
            font-weight: 450;
            border-left: 3px solid #6366f1;
            padding-left: 14px;
        }

        .otp-boxes {
            display: flex;
            gap: 18px;
            justify-content: center;
            margin-bottom: 44px;
            flex-wrap: wrap;
        }

        .otp-input {
            width: 78px;
            height: 92px;
            text-align: center;
            font-size: 40px;
            font-weight: 700;
            font-family: 'Inter', monospace;
            border: 2px solid #e2e8f0;
            border-radius: 28px;
            background: #ffffff;
            transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            color: #0f172a;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        }

        .otp-input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 5px rgba(79, 70, 229, 0.15);
            transform: scale(1.02);
        }

        button {
            width: 100%;
            padding: 16px;
            background: #0f172a;
            color: white;
            border: none;
            border-radius: 40px;
            font-size: 16px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.25s ease;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            letter-spacing: 0.3px;
        }

        button:hover:not(:disabled) {
            background: #1e293b;
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -12px rgba(0, 0, 0, 0.2);
        }

        button:active {
            transform: translateY(1px);
        }

        button:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
            transform: none;
        }

        .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.7s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .resend {
            text-align: center;
        }

        .resend-link {
            color: #4f46e5;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            background: none;
            border: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            display: inline-block;
        }

        .resend-link:hover:not(:disabled) {
            color: #312e81;
            text-decoration: underline;
        }

        .resend-link:disabled {
            color: #94a3b8;
            cursor: default;
            text-decoration: none;
        }

        .timer {
            color: #64748b;
            font-size: 13px;
            margin-top: 10px;
            font-weight: 500;
            background: #f8fafc;
            display: inline-block;
            padding: 4px 12px;
            border-radius: 40px;
        }

        .demo-note {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eef2ff;
            color: #94a3b8;
            font-size: 12px;
            font-weight: 500;
        }

        .message-toast {
            margin-bottom: 24px;
            padding: 12px 16px;
            border-radius: 24px;
            font-size: 13px;
            font-weight: 500;
            display: none;
            transition: 0.2s;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .message-toast.error {
            background: #fee2e2;
            color: #b91c1c;
            border-left: 4px solid #dc2626;
        }

        .message-toast.success {
            background: #dcfce7;
            color: #166534;
            border-left: 4px solid #22c55e;
        }

        .message-toast.info {
            background: #e0f2fe;
            color: #075985;
            border-left: 4px solid #0ea5e9;
        }

        /* Success Card Styles */
        .success-card {
            text-align: center;
            animation: fadeInUp 0.5s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: #22c55e;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            animation: scaleIn 0.3s ease;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }

            to {
                transform: scale(1);
            }
        }

        .success-icon svg {
            width: 48px;
            height: 48px;
            color: white;
        }

        .success-title {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .success-message {
            color: #475569;
            margin-bottom: 32px;
            font-size: 15px;
        }

        .activation-link-container {
            background: #f8fafc;
            border-radius: 24px;
            padding: 24px;
            margin: 24px 0;
            text-align: left;
            border: 1px solid #e2e8f0;
        }

        .link-label {
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .link-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            background: white;
            padding: 12px 16px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            margin-bottom: 12px;
        }

        .activation-link {
            flex: 1;
            font-size: 14px;
            color: #4f46e5;
            word-break: break-all;
            font-family: monospace;
            text-decoration: none;
        }

        .copy-btn {
            background: #eef2ff;
            border: none;
            padding: 8px 16px;
            border-radius: 12px;
            color: #4f46e5;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
            width: auto;
            margin: 0;
        }

        .copy-btn:hover {
            background: #e0e7ff;
            transform: translateY(-1px);
        }

        .expiry-info {
            font-size: 12px;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
        }

        .expiry-badge {
            background: #fef9e3;
            color: #b45309;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .action-buttons button {
            margin: 0;
            flex: 1;
        }

        .secondary-btn {
            background: #f1f5f9;
            color: #334155;
        }

        .secondary-btn:hover {
            background: #e2e8f0;
        }

        @media (max-width: 520px) {
            .card {
                padding: 32px 24px;
            }

            .otp-input {
                width: 62px;
                height: 72px;
                font-size: 32px;
            }

            .otp-boxes {
                gap: 12px;
            }

            h1 {
                font-size: 28px;
            }

            .link-wrapper {
                flex-direction: column;
                align-items: stretch;
            }

            .copy-btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="card" id="app">
        <!-- OTP Verification Form -->
        <div id="otpForm">
            <div class="badge">
                <span>📱</span> VERIFICATION
            </div>

            <h1>Enter OTP</h1>
            <p class="sub" id="mobileDisplayText">We've sent a 4-digit code to your registered mobile number.</p>

            <div id="toastMessage" class="message-toast"></div>

            <div class="otp-boxes">
                <input type="text" class="otp-input" maxlength="1" autofocus inputmode="numeric" pattern="\d*">
                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="\d*">
                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="\d*">
                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="\d*">
            </div>

            <button id="verifyBtn">Verify Code</button>

            <div class="resend">
                <button id="resendBtn" class="resend-link"
                    style="background: none; width: auto; padding: 0; margin: 0; display: inline;">Didn't receive
                    code?</button>
                <div class="timer" id="timerText">Resend available in 02:00</div>
            </div>

            <div class="demo-note">
                ✨ Auto-cursor jump · paste support · Instant verification
            </div>
        </div>

        <!-- Success View (Hidden initially) -->
        <div id="successView" style="display: none;">
            <div class="success-card">
                <div class="success-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="success-title">OTP Verified Successfully!</h2>
                <p class="success-message">Your identity has been confirmed. Click the link below to activate your
                    admission process.</p>

                <div class="activation-link-container">
                    <div class="link-label">🔗 YOUR ACTIVATION LINK</div>
                    <div class="link-wrapper">
                        <a href="#" id="activationLink" class="activation-link" target="_blank"></a>
                        <button id="copyLinkBtn" class="copy-btn">📋 Copy Link</button>
                    </div>
                    <div class="expiry-info">
                        <span>⏰ This link will expire in:</span>
                        <span id="expiryTime" class="expiry-badge"></span>
                    </div>
                </div>

                <div class="action-buttons">
                    <button id="openLinkBtn" class="primary-btn">🔗 Open Link</button>
                    <button id="closeSuccessBtn" class="secondary-btn">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // CSRF Token Handling
        function getCsrfToken() {
            const metaToken = document.querySelector('meta[name="csrf-token"]');
            if (metaToken) {
                return metaToken.getAttribute('content');
            }

            const cookies = document.cookie.split(';');
            for (let i = 0; i < cookies.length; i++) {
                const cookie = cookies[i].trim();
                if (cookie.startsWith('XSRF-TOKEN=')) {
                    return decodeURIComponent(cookie.substring('XSRF-TOKEN='.length));
                }
                if (cookie.startsWith('csrf_token=')) {
                    return decodeURIComponent(cookie.substring('csrf_token='.length));
                }
            }
            return '';
        }

        // DOM elements
        const otpFormDiv = document.getElementById('otpForm');
        const successViewDiv = document.getElementById('successView');
        const inputs = document.querySelectorAll('.otp-input');
        const verifyButton = document.getElementById('verifyBtn');
        const resendButton = document.getElementById('resendBtn');
        const timerSpan = document.getElementById('timerText');
        const toastMessageDiv = document.getElementById('toastMessage');

        let countdownInterval = null;
        let canResend = false;
        let remainingSeconds = 120;
        let isVerifying = false;

        function showToast(message, type = 'error') {
            toastMessageDiv.textContent = message;
            toastMessageDiv.className = `message-toast ${type}`;
            toastMessageDiv.style.display = 'block';
            setTimeout(() => {
                toastMessageDiv.style.display = 'none';
            }, 4000);
        }

        function updateTimerDisplay() {
            const mins = Math.floor(remainingSeconds / 60);
            const secs = remainingSeconds % 60;
            timerSpan.textContent = `Resend available in ${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;

            if (remainingSeconds <= 0) {
                if (countdownInterval) {
                    clearInterval(countdownInterval);
                    countdownInterval = null;
                }
                canResend = true;
                resendButton.disabled = false;
                timerSpan.textContent = "You can request a new code now";
                timerSpan.style.color = "#4f46e5";
            } else {
                canResend = false;
                resendButton.disabled = true;
                timerSpan.style.color = "#94a3b8";
            }
        }

        function startResendCooldown(initialSeconds = 120) {
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
            remainingSeconds = initialSeconds;
            canResend = false;
            resendButton.disabled = true;
            updateTimerDisplay();

            countdownInterval = setInterval(() => {
                if (remainingSeconds > 0) {
                    remainingSeconds--;
                    updateTimerDisplay();
                } else {
                    if (countdownInterval) {
                        clearInterval(countdownInterval);
                        countdownInterval = null;
                    }
                    canResend = true;
                    resendButton.disabled = false;
                    timerSpan.textContent = "Request new OTP";
                    timerSpan.style.color = "#4f46e5";
                }
            }, 1000);
        }

        function clearOtpFields() {
            inputs.forEach(input => {
                input.value = '';
            });
            if (inputs[0]) inputs[0].focus();
        }

        function getOtpValue() {
            let otp = '';
            inputs.forEach(input => {
                otp += input.value;
            });
            return otp;
        }

        function highlightInvalidOtp() {
            inputs.forEach(input => {
                if (input.value === '') {
                    input.style.borderColor = '#f97316';
                    setTimeout(() => {
                        input.style.borderColor = '#e2e8f0';
                    }, 800);
                }
            });
        }

        // Display success view with activation link
        function showActivationLink(activationUrl, expiresAt, linkType, expiryType, expiryValue) {
            // Hide OTP form, show success view
            otpFormDiv.style.display = 'none';
            successViewDiv.style.display = 'block';

            // Set activation link
            const linkElement = document.getElementById('activationLink');
            linkElement.href = activationUrl;
            linkElement.textContent = activationUrl;

            // Set expiry time display
            const expiryElement = document.getElementById('expiryTime');
            const expiryDate = new Date(expiresAt);
            const now = new Date();
            const diffMs = expiryDate - now;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);

            if (diffMins < 60) {
                expiryElement.textContent = `${diffMins} minute${diffMins !== 1 ? 's' : ''}`;
            } else if (diffHours < 24) {
                expiryElement.textContent = `${diffHours} hour${diffHours !== 1 ? 's' : ''}`;
            } else {
                expiryElement.textContent = expiryDate.toLocaleString();
            }

            // Store link for copy functionality
            window.currentActivationUrl = activationUrl;

            showToast('OTP verified successfully!', 'success');
        }

        // Verify OTP
        async function verifyOtpRequest(otpCode) {
            if (isVerifying) return;
            isVerifying = true;
            const originalBtnText = verifyButton.innerHTML;
            verifyButton.innerHTML = '<span class="spinner"></span> Verifying...';
            verifyButton.disabled = true;

            try {
                const csrfToken = getCsrfToken();
                const formData = new FormData();
                formData.append('otp', otpCode);

                const headers = {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                };

                if (csrfToken) {
                    headers['X-CSRF-TOKEN'] = csrfToken;
                }

                const response = await fetch('/applicant-otp-verify', {
                    method: 'POST',
                    headers: headers,
                    credentials: 'include',
                    body: formData
                });

                const contentType = response.headers.get("content-type");

                if (contentType && contentType.includes("application/json")) {
                    const jsonData = await response.json();

                    if (jsonData.redirect_url) {
                        showToast("OTP verified! Redirecting to onboarding...", "success");
                        setTimeout(() => {
                            window.location.href = jsonData.redirect_url;
                        }, 700);
                        return true;
                    }

                    // Check if the response contains activation data (redirect with session flash)
                    if (jsonData.activation_url) {
                        showActivationLink(
                            jsonData.activation_url,
                            jsonData.expires_at,
                            jsonData.link_type,
                            jsonData.expiry_type,
                            jsonData.expiry_value
                        );
                        return true;
                    }

                    if (jsonData.Success === false || jsonData.success === false) {
                        const errorMsg = jsonData.message || "Invalid OTP. Please try again.";
                        showToast(errorMsg, "error");
                        return false;
                    } else if (jsonData.type === 'success') {
                        showToast("OTP verified! Redirecting...", "success");
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                        return true;
                    } else {
                        showToast("Unexpected server response", "error");
                        return false;
                    }
                }
                else {
                    // Handle HTML response - check for redirect or session data
                    const htmlText = await response.text();

                    if (response.ok) {
                        // Check if there's a redirect with activation link in session
                        if (htmlText.includes('activation_url') || htmlText.includes('redirect')) {
                            // Try to extract activation data from HTML or reload
                            showToast("Verification successful!", "success");
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                            return true;
                        }

                        if (htmlText.includes('Invalid OTP') || htmlText.includes('error')) {
                            showToast("Invalid OTP. Please try again.", "error");
                            return false;
                        }

                        showToast("Verification successful!", "success");
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                        return true;
                    } else {
                        showToast("Verification failed. Please try again.", "error");
                        return false;
                    }
                }
            } catch (error) {
                console.error("OTP verification error:", error);
                showToast("Network error. Kindly try after sometime.", "error");
                return false;
            } finally {
                isVerifying = false;
                verifyButton.innerHTML = originalBtnText;
                verifyButton.disabled = false;
            }
        }

        // Resend OTP
        async function requestResendOtp() {
            if (!canResend) {
                showToast(`Please wait ${Math.ceil(remainingSeconds)} seconds before requesting again`, "error");
                return;
            }

            try {
                const csrfToken = getCsrfToken();
                const headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                };

                if (csrfToken) {
                    headers['X-CSRF-TOKEN'] = csrfToken;
                }

                const response = await fetch('/applicant-resend-otp', {
                    method: 'POST',
                    headers: headers,
                    credentials: 'include',
                    body: JSON.stringify({})
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success || data.Success) {
                        showToast("New OTP sent to your mobile number", "success");
                        clearOtpFields();
                        startResendCooldown(120);
                    } else {
                        showToast(data.message || "Unable to resend OTP", "error");
                    }
                } else {
                    showToast("Resend functionality: Please contact your administrator", "info");
                    startResendCooldown(120);
                }
            } catch (err) {
                showToast("Please contact administrator to resend OTP", "info");
                startResendCooldown(120);
            }
        }

        // Copy link to clipboard
        function copyLinkToClipboard() {
            if (window.currentActivationUrl) {
                navigator.clipboard.writeText(window.currentActivationUrl).then(() => {
                    showToast('Link copied to clipboard!', 'success');
                }).catch(() => {
                    showToast('Failed to copy link', 'error');
                });
            }
        }

        // Setup OTP fields
        function setupOtpFields() {
            inputs.forEach((input, idx) => {
                input.addEventListener('input', (e) => {
                    e.target.value = e.target.value.replace(/[^0-9]/g, '');
                    if (e.target.value.length === 1 && idx < inputs.length - 1) {
                        inputs[idx + 1].focus();
                    }
                    const otpCode = getOtpValue();
                    if (otpCode.length === 4) {
                        setTimeout(() => {
                            verifyButton.click();
                        }, 100);
                    }
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && e.target.value.length === 0 && idx > 0) {
                        inputs[idx - 1].focus();
                        e.preventDefault();
                    }
                    if (e.key === 'ArrowLeft' && idx > 0) {
                        inputs[idx - 1].focus();
                        e.preventDefault();
                    }
                    if (e.key === 'ArrowRight' && idx < inputs.length - 1) {
                        inputs[idx + 1].focus();
                        e.preventDefault();
                    }
                });

                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pasteData = e.clipboardData.getData('text');
                    const digits = pasteData.replace(/[^0-9]/g, '').split('').slice(0, 4);
                    digits.forEach((digit, i) => {
                        if (inputs[i]) inputs[i].value = digit;
                    });
                    const nextFocus = digits.length < 4 ? digits.length : 3;
                    if (inputs[nextFocus]) {
                        inputs[nextFocus].focus();
                    }
                });
            });
        }

        // Setup event handlers
        function setupEventHandlers() {
            verifyButton.addEventListener('click', async () => {
                const otpCode = getOtpValue();
                if (otpCode.length !== 4) {
                    showToast("Please enter complete 4-digit OTP", "error");
                    highlightInvalidOtp();
                    return;
                }
                await verifyOtpRequest(otpCode);
            });

            resendButton.addEventListener('click', (e) => {
                e.preventDefault();
                if (canResend) {
                    requestResendOtp();
                } else {
                    showToast(`Please wait ${Math.ceil(remainingSeconds)} seconds`, "error");
                }
            });

            // Success view buttons
            document.getElementById('copyLinkBtn')?.addEventListener('click', copyLinkToClipboard);
            document.getElementById('openLinkBtn')?.addEventListener('click', () => {
                if (window.currentActivationUrl) {
                    window.open(window.currentActivationUrl, '_blank');
                }
            });
            document.getElementById('closeSuccessBtn')?.addEventListener('click', () => {
                successViewDiv.style.display = 'none';
                otpFormDiv.style.display = 'block';
                clearOtpFields();
            });
        }

        // Initialize
        function init() {
            setupOtpFields();
            setupEventHandlers();
            startResendCooldown(120);
            if (inputs[0]) inputs[0].focus();
        }

        init();
    </script>
</body>

</html>
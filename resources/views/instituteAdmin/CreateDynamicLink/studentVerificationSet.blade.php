<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Admission - Send OTP</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(145deg, #eef2ff 0%, #e0e7ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 48px;
            padding: 48px 40px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 30px 50px -20px rgba(0, 0, 0, 0.25);
            animation: floatIn 0.55s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        }

        @keyframes floatIn {
            0% {
                opacity: 0;
                transform: scale(0.96) translateY(28px);
            }

            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .icon-badge {
            width: 70px;
            height: 70px;
            background: linear-gradient(125deg, #4f46e5, #7c3aed);
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 28px;
            box-shadow: 0 12px 20px -10px rgba(79, 70, 229, 0.4);
        }

        .icon-badge span {
            font-size: 36px;
        }

        h1 {
            font-size: 34px;
            font-weight: 800;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            margin-bottom: 12px;
        }

        .subhead {
            color: #475569;
            font-size: 15px;
            line-height: 1.5;
            margin-bottom: 32px;
            border-left: 3px solid #818cf8;
            padding-left: 12px;
            font-weight: 500;
        }

        .input-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .mobile-group {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 8px;
        }

        .country-code {
            background: #f1f5f9;
            padding: 14px 20px;
            border-radius: 28px;
            font-weight: 700;
            color: #0f172a;
            border: 1.5px solid #e2e8f0;
            font-size: 16px;
        }

        .mobile-input {
            flex: 1;
            padding: 14px 18px;
            border: 1.5px solid #e2e8f0;
            border-radius: 28px;
            font-size: 16px;
            font-weight: 500;
            font-family: 'Inter', monospace;
            transition: all 0.2s;
            background: #ffffff;
        }

        .mobile-input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        .btn-primary {
            width: 100%;
            padding: 16px;
            background: linear-gradient(105deg, #4f46e5, #7c3aed);
            color: white;
            border: none;
            border-radius: 48px;
            font-size: 16px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.25s ease;
            margin-top: 24px;
            box-shadow: 0 8px 20px -8px rgba(79, 70, 229, 0.5);
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 14px 26px -10px rgba(79, 70, 229, 0.6);
        }

        .btn-primary:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }

        .loader-spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.7s linear infinite;
            margin-right: 8px;
            vertical-align: middle;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .message-card {
            margin-top: 24px;
            padding: 14px 18px;
            border-radius: 28px;
            font-size: 13px;
            font-weight: 500;
            text-align: center;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .msg-success {
            background: #e6f7e6;
            color: #0a5c2e;
            border-left: 4px solid #22c55e;
        }

        .msg-error {
            background: #ffe8e8;
            color: #b91c1c;
            border-left: 4px solid #ef4444;
        }

        .msg-info {
            background: #eef2ff;
            color: #1e3a8a;
            border-left: 4px solid #818cf8;
        }

        .msg-warning {
            background: #fef3c7;
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }

        .success-icon {
            display: inline-block;
            margin-right: 8px;
            font-size: 16px;
        }

        .redirect-info {
            margin-top: 16px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 20px;
            font-size: 12px;
            color: #64748b;
            text-align: center;
            display: none;
        }

        .redirect-info.show {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @media (max-width: 480px) {
            .card {
                padding: 32px 24px;
            }

            h1 {
                font-size: 28px;
            }

            .icon-badge {
                width: 60px;
                height: 60px;
            }

            .icon-badge span {
                font-size: 30px;
            }
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="icon-badge">
            <span>📱</span>
        </div>
        <h1>Verify Your Number</h1>
        <p class="subhead">We'll send a 4-digit OTP to your mobile number to complete the admission process</p>

        <!-- Mobile Number Input Section -->
        <div class="input-group">
            <label class="input-label">📞 MOBILE NUMBER</label>
            <div class="mobile-group">
                <div class="country-code">+91</div>
                <input type="tel" id="mobileNumber" class="mobile-input" placeholder="98765 43210" maxlength="10"
                    autocomplete="off" value="{{ $verificationMobile ?? '' }}" {{ !empty($verificationMobile) ? 'readonly' : '' }}>
            </div>
        </div>

        <!-- Send OTP Button -->
        <button id="sendOtpBtn" class="btn-primary">Send OTP →</button>

        <!-- Message Display Area -->
        <div id="messageArea" class="message-card msg-info">
            <span>📱</span> Enter your 10-digit mobile number to receive OTP
        </div>

        <!-- Redirect Information (shows on success) -->
        <div id="redirectInfo" class="redirect-info">
            <span>🔄</span> Redirecting to verification page...
        </div>
    </div>

    <script>
        // DOM Elements
        const mobileInput = document.getElementById('mobileNumber');
        const sendBtn = document.getElementById('sendOtpBtn');
        const messageArea = document.getElementById('messageArea');
        const redirectInfo = document.getElementById('redirectInfo');
        const verificationMobile = @json($verificationMobile ?? null);

        // Get CSRF token from meta tag
        function getCsrfToken() {
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            if (tokenMeta) {
                return tokenMeta.getAttribute('content');
            }
            // Fallback: try to get from cookie
            const cookieValue = document.cookie
                .split('; ')
                .find(row => row.startsWith('XSRF-TOKEN='));
            if (cookieValue) {
                return decodeURIComponent(cookieValue.split('=')[1]);
            }
            return '';
        }

        // Show message function
        function showMessage(message, type = 'info', duration = 5000) {
            let icon = '';
            switch (type) {
                case 'success':
                    icon = '✅ ';
                    break;
                case 'error':
                    icon = '❌ ';
                    break;
                case 'warning':
                    icon = '⚠️ ';
                    break;
                default:
                    icon = '📱 ';
            }

            messageArea.innerHTML = `<span>${icon}</span> ${message}`;
            messageArea.className = `message-card msg-${type}`;

            // Auto hide info/warning messages after duration
            if (type !== 'error') {
                setTimeout(() => {
                    if (messageArea.innerHTML.includes(message)) {
                        messageArea.innerHTML = '<span>📱</span> Enter your 10-digit mobile number to receive OTP';
                        messageArea.className = 'message-card msg-info';
                    }
                }, duration);
            }
        }

        // Send OTP API call
        async function sendOtpRequest(mobile) {
            const formData = new URLSearchParams();
            if (mobile) formData.append('mobile_number', mobile);

            const response = await fetch('/applicant-otp-send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json, text/html, */*'
                },
                body: formData.toString(),
                credentials: 'same-origin',
                redirect: 'manual' // Don't auto-follow redirects
            });

            return response;
        }

        // Handle redirect from server
        function handleRedirect(redirectUrl) {
            // Show redirect message
            redirectInfo.classList.add('show');

            // Update message
            showMessage('✅ OTP sent successfully! Redirecting to verification page...', 'success', 3000);

            // Redirect after a short delay
            setTimeout(() => {
                window.location.href = redirectUrl;
            }, 1500);
        }

        // Send OTP button click handler
        sendBtn.addEventListener('click', async () => {
            const mobile = mobileInput.value.trim();

            // Validate mobile number
            if (!mobile) {
                showMessage('Please enter your mobile number', 'error');
                mobileInput.focus();
                return;
            }

            if (!/^\d{10}$/.test(mobile)) {
                showMessage('Please enter a valid 10-digit mobile number', 'error');
                mobileInput.focus();
                return;
            }

            // Disable button and show loading state
            sendBtn.disabled = true;
            const originalBtnText = sendBtn.innerHTML;
            sendBtn.innerHTML = '<span class="loader-spinner"></span> Sending OTP...';

            try {
                // Make API call
                const response = await sendOtpRequest(mobile);

                // Check for redirect (302 status - success case)
                if (response.success === true || response.status === 200) {

                    setTimeout(() => {
                        window.location.href = '/applicant-verify-otp';
                    }, 1500);
                }
                // Handle JSON response
                else {
                    const contentType = response.headers.get('content-type');

                    if (contentType && contentType.includes('application/json')) {
                        const data = await response.json();

                        if (data.type === 'success' || data.success === true) {
                            showMessage(data.message || '✅ OTP sent successfully!', 'success');

                            // Check if there's a redirect URL in response
                            if (data.redirect_url || data.redirect) {
                                setTimeout(() => {
                                    window.location.href = data.redirect_url || data.redirect;
                                }, 1500);
                            } else {
                                // Try to go to verify-otp page after success
                                setTimeout(() => {
                                    window.location.href = '/verify-otp';
                                }, 1500);
                            }
                        } else {
                            showMessage(data.message || 'Failed to send OTP. Please try again.', 'error');
                            sendBtn.disabled = false;
                            sendBtn.innerHTML = originalBtnText;
                        }
                    }
                    // Handle HTML response
                    else {
                        const text = await response.text();

                        if (response.ok) {
                            showMessage('✅ OTP sent successfully! Redirecting...', 'success');
                            setTimeout(() => {
                                window.location.href = '/verify-otp';
                            }, 1500);
                        } else {
                            showMessage('Failed to send OTP. Please try again.', 'error');
                            sendBtn.disabled = false;
                            sendBtn.innerHTML = originalBtnText;
                        }
                    }
                }
            } catch (error) {
                console.error('Send OTP Error:', error);

                // Handle specific error types
                if (error.message === 'SESSION_EXPIRED' || error.toString().includes('419')) {
                    showMessage('Session expired. Please refresh the page and try again.', 'error');
                } else if (error.name === 'TypeError' && error.message.includes('Failed to fetch')) {
                    showMessage('Network error. Please check your internet connection.', 'error');
                } else {
                    showMessage('Unable to send OTP. Please try again later.', 'error');
                }

                // Re-enable button
                sendBtn.disabled = false;
                sendBtn.innerHTML = originalBtnText;
            }
        });

        // Mobile number input validation (only numbers, max 10 digits)
        mobileInput.addEventListener('input', (e) => {
            let value = e.target.value;
            value = value.replace(/[^0-9]/g, '');
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            e.target.value = value;
        });

        // Allow Enter key to trigger send OTP
        mobileInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !sendBtn.disabled) {
                sendBtn.click();
            }
        });

        // Auto-focus on mobile input when page loads
        mobileInput.focus();

        // Clear redirect info when starting new request
        function resetRedirectInfo() {
            redirectInfo.classList.remove('show');
        }

        // Reset redirect info when user starts typing
        mobileInput.addEventListener('focus', resetRedirectInfo);
        mobileInput.addEventListener('input', resetRedirectInfo);

        // Log CSRF token for debugging (remove in production)
        console.log('CSRF Token present:', !!getCsrfToken());
    </script>
</body>

</html>
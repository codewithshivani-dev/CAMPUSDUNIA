<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Candidate Journey</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: #f1f5f9;
        }

        .otp-card {
            width: min(100% - 2rem, 430px);
            border: 0;
            border-radius: 16px;
        }

        .otp-digit {
            width: 48px;
            height: 48px;
            text-align: center;
            font-size: 1.25rem;
        }

        @media (max-width: 420px) {
            .otp-digit {
                width: 42px;
                height: 42px;
            }
        }
    </style>
</head>

<body>
    <div class="card shadow-sm otp-card">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <i class="bi bi-shield-lock text-primary fs-1"></i>
                <h1 class="h4 mt-3 mb-2">Verify Your Journey</h1>
                <p class="text-secondary mb-0">Enter the mobile number used in your application.</p>
            </div>
            <label for="mobile" class="form-label">Mobile number</label>
            <div class="input-group mb-3">
                <input id="mobile" class="form-control" inputmode="numeric" maxlength="10" autocomplete="tel">
                <button id="sendOtp" class="btn btn-primary" type="button">Send OTP</button>
            </div>
            <div id="otpArea" class="d-none">
                <label class="form-label">Verification code</label>
                <div class="d-flex justify-content-center gap-2 mb-3">
                    @for($index = 0; $index < 4; $index++)
                        <input class="form-control otp-digit" maxlength="1" inputmode="numeric"
                            aria-label="OTP digit {{ $index + 1 }}">
                    @endfor
                </div>
                <div class="d-flex gap-2">
                    <button id="verifyOtp" class="btn btn-success flex-grow-1" type="button">Verify OTP</button>
                    <button id="resendOtp" class="btn btn-outline-secondary" type="button">Resend</button>
                </div>
            </div>
            <div id="message" class="small mt-3" role="alert"></div>
        </div>
    </div>
    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const mobile = document.getElementById('mobile');
        const otpArea = document.getElementById('otpArea');
        const message = document.getElementById('message');
        const digits = [...document.querySelectorAll('.otp-digit')];

        function showMessage(text, error = false) {
            message.textContent = text;
            message.className = `small mt-3 ${error ? 'text-danger' : 'text-success'}`;
        }
        function otpValue() { return digits.map(input => input.value).join(''); }
        async function sendOtp() {
            const value = mobile.value.replace(/\D/g, '');
            mobile.value = value;
            if (!/^[6-9]\d{9}$/.test(value)) { showMessage('Enter a valid 10-digit mobile number.', true); return; }
            const response = await fetch('/applicant-otp-send', {
                method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: new URLSearchParams({ mobile_number: value })
            });
            const data = await response.json();
            if (!data.success) { showMessage(data.message || 'Unable to send OTP.', true); return; }
            otpArea.classList.remove('d-none');
            showMessage('OTP sent successfully.');
            digits[0].focus();
        }
        async function verifyOtp() {
            const otp = otpValue();
            if (!/^\d{4}$/.test(otp)) { showMessage('Enter the 4-digit OTP.', true); return; }
            const response = await fetch('/applicant-otp-verify', {
                method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: new URLSearchParams({ otp })
            });
            const data = await response.json();
            if (!data.success || !data.redirect_url) { showMessage(data.message || 'Invalid OTP.', true); return; }
            window.location.href = data.redirect_url;
        }
        digits.forEach((input, index) => input.addEventListener('input', () => {
            input.value = input.value.replace(/\D/g, '').slice(0, 1);
            if (input.value && digits[index + 1]) digits[index + 1].focus();
        }));
        document.getElementById('sendOtp').addEventListener('click', sendOtp);
        document.getElementById('resendOtp').addEventListener('click', sendOtp);
        document.getElementById('verifyOtp').addEventListener('click', verifyOtp);
    </script>
</body>

</html>
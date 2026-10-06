@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Bank Connect | Secure Statement Upload</title>
    <!-- Google Fonts & Font Awesome for icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- jQuery (lightweight) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        /* hide navbar & footer if present (in case of blade integration) */
        /* Main card container */
        .bank-connect-card {
            max-width: 780px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 32px;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.12), 0 8px 24px -6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: all 0.2s ease;
        }

        /* Logo area - improved spacing */
        .logo-strip {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 1.8rem 2rem 0.5rem 2rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .logo-item {
            display: flex;
            align-items: center;
            background: #fafbfe;
            padding: 0.5rem 1rem;
            border-radius: 60px;
            transition: all 0.2s;
        }

        .logo-item img {
            max-height: 56px;
            width: auto;
            object-fit: contain;
        }

        /* Header section with icon */
        .form-header {
            padding: 1.8rem 2rem 0.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-text h2 {
            font-size: 1.9rem;
            font-weight: 700;
            background: linear-gradient(115deg, #1e2a3e, #2c3e4e);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -0.3px;
        }

        .header-text p {
            color: #5a6874;
            font-weight: 500;
            margin-top: 6px;
            font-size: 0.95rem;
        }

        .icon-badge {
            background: #fff6e5;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            box-shadow: 0 8px 18px rgba(255, 160, 0, 0.15);
        }

        .icon-badge svg {
            width: 36px;
            height: 36px;
            fill: #0d6efd;
        }

        /* Alert message */
        .alert-message {
            margin: 1rem 2rem 0 2rem;
            padding: 1rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            background: #eef2ff;
            color: #1e40af;
            border-left: 5px solid #3b82f6;
        }

        .alert-message i {
            font-size: 1.3rem;
        }

        /* Tab / Content area */
        .form-container {
            padding: 1.5rem 2rem 2rem 2rem;
        }

        /* form elements */
        .form-group {
            margin-bottom: 1.8rem;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .form-label i {
            color: #0d6efd;
            font-size: 1rem;
            width: 20px;
        }

        .input-icon-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-wrapper .prefix-icon {
            position: absolute;
            left: 16px;
            background: #f3f4f6;
            padding: 6px 12px;
            border-radius: 40px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #4b5563;
            letter-spacing: 0.5px;
            pointer-events: none;
            z-index: 2;
        }

        .input-icon-wrapper input,
        .input-icon-wrapper select,
        .input-icon-wrapper .file-custom {
            width: 100%;
            padding: 14px 18px 14px 90px;
            border: 1.5px solid #e2e8f0;
            border-radius: 20px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            transition: all 0.25s;
            color: #1e293b;
        }

        .input-icon-wrapper select {
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>');
            background-repeat: no-repeat;
            background-position: right 18px center;
        }

        .input-icon-wrapper input:focus,
        .input-icon-wrapper select:focus {
            outline: none;
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(255, 160, 0, 0.2);
        }

        /* File upload redesign with preview and drag-drop feel */
        .file-upload-area {
            position: relative;
            border: 1.5px dashed #cbd5e1;
            border-radius: 20px;
            background: #fafcff;
            transition: all 0.2s;
            padding: 0.5rem 0;
        }

        .file-upload-area:hover {
            border-color: #0d6efd;
            background: #fffbf0;
        }

        .file-input-hidden {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 3;
        }

        .file-upload-label {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px 14px 90px;
            cursor: pointer;
        }

        .file-upload-icon {
            background: #ffecb3;
            border-radius: 40px;
            padding: 8px 12px;
            font-weight: 600;
            font-size: 0.7rem;
            color: #b45f06;
        }

        .file-name-display {
            font-size: 0.85rem;
            color: #2c3e4e;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 240px;
        }

        .file-hint {
            font-size: 0.7rem;
            color: #6c757d;
            margin-top: 6px;
            margin-left: 90px;
        }

        .note-text {
            font-size: 0.7rem;
            margin-top: 8px;
            background: #f8fafc;
            padding: 8px 12px;
            border-radius: 14px;
            color: #475569;
        }

        .note-text a {
            color: #ff8c00;
            font-weight: 600;
            text-decoration: none;
        }

        .note-text i {
            margin-right: 5px;
            color: #0d6efd;
        }

        .error-message {
            margin-top: 8px;
            padding: 8px 12px;
            background: #fee2e2;
            border-radius: 14px;
            color: #b91c1c;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .confirmation-check {
            background: #f9fafb;
            border-radius: 20px;
            padding: 0.8rem 1.2rem;
            margin: 1rem 0 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid #eef2ff;
        }

        .btn-submit {
            background: #0d6efd ;
            border: none;
            padding: 14px 28px;
            border-radius: 40px;
            font-weight: 700;
            font-size: 1rem;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.25s;
            box-shadow: 0 5px 12px rgba(255, 140, 0, 0.25);
            width: 100%;
            justify-content: center;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            background: linear-gradient(95deg, #ff9100, #ff7b00);
            box-shadow: 0 12px 20px -8px rgba(255, 140, 0, 0.4);
        }

        /* loader overlay */
        #global-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(3px);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .spinner {
            width: 56px;
            height: 56px;
            border: 5px solid #ffe0b3;
            border-top: 5px solid #0d6efd;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* responsive */
        @media (max-width: 640px) {
            .bank-connect-card {
                margin: 0 0.8rem;
            }
            .form-header {
                padding: 1.2rem 1.2rem 0rem 1.2rem;
                flex-direction: column;
                align-items: flex-start;
            }
            .form-container {
                padding: 1rem 1.2rem 1.8rem;
            }
            .logo-strip {
                padding: 1rem 1.2rem;
            }
            .input-icon-wrapper .prefix-icon {
                padding: 4px 8px;
                font-size: 0.65rem;
            }
            .input-icon-wrapper input,
            .input-icon-wrapper select,
            .file-upload-label {
                padding-left: 75px;
            }
        }
    </style>
</head>
<body>

<div id="global-loader">
    <div class="spinner"></div>
    <p style="margin-top: 20px; font-weight: 500; color:#0d6efd;">Processing, please wait...</p>
</div>

<div class="bank-connect-card">

    <!-- header with title & icon -->
    <div class="form-header">
        <div class="header-text">
            <h2><i class="fas fa-university" style="color:#0d6efd; margin-right:8px;"></i> Bank Connect</h2>
            <p>Seamless & secure — upload your e-statement in seconds</p>
        </div>
        <div class="icon-badge">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 346.164 346.164">
                <circle cx="173.082" cy="248.413" r="14.003" fill="#0d6efd"/>
                <path fill="#0d6efd" d="M210.979,193.111c32.374,0,58.62-25.975,58.62-58.02c0-32.039-26.246-58.016-58.62-58.016c-32.379,0-58.622,25.977-58.622,58.016C152.356,167.137,178.6,193.111,210.979,193.111z"/>
                <path fill="#0d6efd" d="M323.395,335.404L284.31,232.093c-0.993-2.621-9.402-19.928-25.013-19.928c-4.938,0-90.583,0.129-93.386,0.129c0,0-0.456,0-3.236,0c-17.474,0-24.017,17.178-25.007,19.799L98.579,335.404c-0.993,2.623-0.417,5.877,5.083,5.877c0,0,21.099,0,28.845,0c5.255,0,6.892-4.6,6.892-4.6l18.636-50.002c0,0,2.343-6.4,2.343,0.543c0,9.545,0.125,35.047,0.186,47.301c0.023,4.133,0.662,6.758,5.542,6.758c25.279,0,71.376,0,95.166,0c6.759,0,5.934-3.518,5.918-8.822c-0.035-12.934-0.099-36.496-0.099-45.613c0-4.689,1.662-1.49,1.662-1.49l17.504,50.545c0,0,1.669,5.381,7.862,5.381c6.585,0,23.901,0,23.901,0C323.705,341.281,324.388,338.027,323.395,335.404z"/>
                <path fill="#0d6efd" d="M81.082,294.305c-11.689,0-46.759,0-46.759,0c-4.058,0-7.378,3.32-7.378,7.379v32.219c0,4.059,3.32,7.379,7.378,7.379c0,0,23.194,0,30.926,0c6.166,0,8.472-7.664,8.472-7.664l12.299-33.734C86.02,299.883,87.582,294.305,81.082,294.305z"/>
                <path fill="#0d6efd" d="M107.207,222.609c-18.008,0-72.03,0-72.03,0c-7.827,0-14.23-6.402-14.23-14.23V42.635c0-7.828,6.403-14.231,14.23-14.231h275.81c7.826,0,14.23,6.402,14.23,14.231v165.744c0,6.365-4.278,11.656-10.026,13.592c-5.858,1.975-4.254,5.875-4.254,5.875l17.537,46.357c0,0,1.441,4.324,6.316,3.658c6.546-0.895,11.374-7.092,11.374-13.94V19.111c0-7.826-6.404-14.23-14.231-14.23H14.23C6.403,4.881,0,11.285,0,19.111v101.953v49v93.857c0,7.826,6.403,14.23,14.23,14.23c0,0,52.993,0,71.019,0c6.666,0,8.727-5.195,8.727-5.195l17.25-45.594C111.226,227.363,113.082,222.609,107.207,222.609z"/>
            </svg>
        </div>
    </div>

    <!-- Session flash message handler (if any) -->
    @if(Session::has('message'))
    <div class="alert-message">
        <i class="fas fa-info-circle"></i> 
        <span>{{ Session::get('message') }}</span>
    </div>
    @endif

    <div class="form-container">
        <form action="{{ route('loan.journey.post.bank.details') }}" method="POST" enctype="multipart/form-data" id="bankConnectForm">
            @csrf
            <input type="hidden" name="loan_request_id" value="{{ $loan_request_id }}">
            <!-- Bank Name -->
            <div class="form-group">
                <div class="form-label">
                    <i class="fas fa-building-columns"></i> <span>Bank Name</span>
                </div>
                <div class="input-icon-wrapper">
                    <span class="prefix-icon">BN</span>
                    <input type="text" name="bank_name" placeholder="e.g., State Bank of India, HDFC" value="{{ old('bank_name') }}" id="bankName">
                </div>
                @if ($errors->has('bank_name'))
                <div class="error-message"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('bank_name') }}</div>
                @endif
            </div>

            <!-- Account Type -->
            <div class="form-group">
                <div class="form-label">
                    <i class="fas fa-wallet"></i> <span>Account Type</span>
                </div>
                <div class="input-icon-wrapper">
                    <span class="prefix-icon">AT</span>
                    <select name="account_type" id="accountType">
                        <option value="">Select Account Type</option>
                        <option value="SAVING" {{ old('account_type') == 'SAVING' ? 'selected' : '' }}>Savings Account</option>
                        <option value="CURRENT" {{ old('account_type') == 'CURRENT' ? 'selected' : '' }}>Current Account</option>
                        <option value="CREDIT_CARD" {{ old('account_type') == 'CREDIT_CARD' ? 'selected' : '' }}>Credit Card</option>
                    </select>
                </div>
                @if ($errors->has('account_type'))
                <div class="error-message"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('account_type') }}</div>
                @endif
            </div>

            <!-- Enhanced Bank Statement Upload with preview and note -->
            <div class="form-group">
                <div class="form-label">
                    <i class="fas fa-file-pdf"></i> <span>Bank Statement (PDF)</span>
                </div>
                <div class="file-upload-area" id="dropZone">
                    <div class="file-upload-label">
                        <span class="file-upload-icon"><i class="fas fa-cloud-upload-alt"></i> Choose file</span>
                        <span class="file-name-display" id="fileNameDisplay">No file chosen</span>
                    </div>
                    <input type="file" name="bank_statement" id="bankStatementFile" accept=".pdf" class="file-input-hidden">
                </div>
                <div class="note-text">
                    <i class="fas fa-lightbulb"></i> <strong>Note:</strong> Upload minimum 3 months & maximum 6 months bank e-statement in <strong>single PDF</strong> where you receive salary/income.
                    <a href="/images/3Mor6M-Bank%20E-STATEMENT-Format.pdf" target="_blank"><i class="fas fa-eye"></i> View Sample Statement</a>
                </div>
                @if ($errors->has('bank_statement'))
                <div class="error-message"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('bank_statement') }}</div>
                @endif
            </div>

            <!-- Password (Optional) -->
            <div class="form-group">
                <div class="form-label">
                    <i class="fas fa-lock"></i> <span>Password (Optional)</span>
                </div>
                <div class="input-icon-wrapper">
                    <span class="prefix-icon">P</span>
                    <input type="password" name="password" placeholder="Enter if statement is password protected" value="{{ old('password') }}" id="pdfPassword">
                </div>
                <div class="note-text" style="margin-top: 4px;">
                    <i class="fas fa-key"></i> In case of password protected PDF, provide password above.
                </div>
                @if ($errors->has('password'))
                <div class="error-message"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('password') }}</div>
                @endif
            </div>

            <!-- Confirmation check -->
            <div class="confirmation-check">
                <i class="fas fa-check-circle" style="color:#0d6efd; font-size: 1.2rem;"></i>
                <span style="font-size: 0.85rem; font-weight:500;">I/we confirm that the submitted details are correct for applying the process.</span>
            </div>

            <!-- Submit button -->
            <div class="term-condition">
                <button type="submit" id="submitBtn" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Submit Application
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Custom file upload display
        const fileInput = $('#bankStatementFile');
        const fileNameSpan = $('#fileNameDisplay');

        fileInput.on('change', function() {
            if (this.files && this.files.length > 0) {
                let fileName = this.files[0].name;
                if (fileName.length > 35) fileName = fileName.substring(0, 30) + '...' + fileName.split('.').pop();
                fileNameSpan.text(fileName).css('color', '#1e293b');
                // Optional: validate extension
                const ext = this.files[0].name.split('.').pop().toLowerCase();
                if (ext !== 'pdf') {
                    alert('Only PDF files are allowed. Please select a valid bank statement PDF.');
                    $(this).val('');
                    fileNameSpan.text('No file chosen');
                }
            } else {
                fileNameSpan.text('No file chosen');
            }
        });

        // Drag & drop enhancement
        const dropArea = document.getElementById('dropZone');
        if (dropArea) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
            });
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, highlight, false);
            });
            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, unhighlight, false);
            });
            function highlight() { dropArea.style.borderColor = '#0d6efd'; dropArea.style.backgroundColor = '#fff9ef'; }
            function unhighlight() { dropArea.style.borderColor = '#cbd5e1'; dropArea.style.backgroundColor = '#fafcff'; }
            dropArea.addEventListener('drop', handleDrop, false);
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files.length) {
                    const file = files[0];
                    if (file.type === 'application/pdf') {
                        fileInput[0].files = files;
                        // trigger change event manually
                        fileInput.trigger('change');
                    } else {
                        alert('Only PDF files are allowed.');
                    }
                }
                unhighlight();
            }
        }

        // Form validation + loader
        $('#bankConnectForm').on('submit', function(e) {
            let bankName = $('#bankName').val().trim();
            let accountType = $('#accountType').val();
            let bankStatementFile = $('#bankStatementFile')[0].files[0];

            let hasError = false;

            // Remove existing dynamic error messages to avoid duplication
            $('.dynamic-error').remove();

            if (!bankName) {
                showFieldError('bankName', 'Bank name is required.');
                hasError = true;
            }
            if (!accountType) {
                showFieldError('accountType', 'Please select account type.');
                hasError = true;
            }
            if (!bankStatementFile) {
                showFieldError('bankStatementFile', 'Bank statement PDF is mandatory.');
                hasError = true;
            } else if (bankStatementFile.type !== 'application/pdf') {
                showFieldError('bankStatementFile', 'Only PDF format allowed.');
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                // scroll to first error
                $('html, body').animate({ scrollTop: $('.error-message').first().offset().top - 100 }, 500);
                return false;
            }

            // Show loader before submitting
            $('#global-loader').css('display', 'flex');
            return true; // submit
        });

        function showFieldError(fieldId, msg) {
            let $field = $('#' + fieldId);
            let $container = $field.closest('.form-group');
            if ($container.find('.dynamic-error').length === 0) {
                $container.append('<div class="error-message dynamic-error"><i class="fas fa-exclamation-triangle"></i> ' + msg + '</div>');
            }
            // highlight border
            if ($field.is('select')) $field.css('border-color', '#f87171');
            else $field.css('border-color', '#f87171');
            setTimeout(() => { if($field) $field.css('border-color', ''); }, 2000);
        }

        // Reset any global session message style after few seconds if needed
        setTimeout(function() {
            $('.alert-message').fadeOut('slow', function() { $(this).remove(); });
        }, 5000);

        // If there are any server-side validation errors, loader stays hidden? we need to hide loader on page load if error from backend?
        // But blade errors are server-side, but on fresh load loader not visible. On submit if server error, loader will be hidden because page reloads.
        // We can also add fallback: if any error container exists, ensure loader hidden.
        if ($('.error-message').length) {
            $('#global-loader').hide();
        }
    });
</script>

<!-- Additional inline to preserve existing blade backend behavior:
     The form action is empty so it will POST to same URL, perfect for Laravel controller.
     Also, the error bag from Laravel will be displayed.
     Additionally we keep file upload enhancements & drag/drop. -->
@endsection
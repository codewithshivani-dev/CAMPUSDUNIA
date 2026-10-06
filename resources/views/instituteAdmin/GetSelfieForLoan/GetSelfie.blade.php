@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Secure Selfie Verification</title>
    <!-- Google Fonts & Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>

        /* Main Card Container */
        .selfie-card {
            max-width: 780px;
            margin: 40px auto;
            width: 100%;
            background: #ffffff;
            border-radius: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        /* Logo Header */
        .logo-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 32px;
            background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            flex-wrap: wrap;
            gap: 16px;
        }

        .logo-header img {
            max-height: 60px;
            width: auto;
            object-fit: contain;
        }

        .logo-group {
            display: flex;
            gap: 24px;
            align-items: center;
            flex-wrap: wrap;
        }

        .secure-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 8px 18px;
            border-radius: 40px;
            color: white;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .secure-badge i {
            margin-right: 6px;
        }

        /* Tab Navigation */
        .tab-navigation {
            display: flex;
            padding: 0 32px;
            background: #ffffff;
            gap: 8px;
            border-bottom: 2px solid #f0f2f5;
        }

        .tab-btn {
            flex: 1;
            background: none;
            border: none;
            padding: 18px 24px;
            font-size: 16px;
            font-weight: 600;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-family: 'Inter', sans-serif;
        }

        .tab-btn i {
            font-size: 18px;
        }

        .tab-btn.active {
            color: #667eea;
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 3px;
        }

        .tab-btn:hover:not(.active) {
            color: #495057;
            background: #f8f9fa;
        }

        /* Tab Content */
        .tab-content {
            display: none;
            padding: 32px;
            animation: fadeIn 0.4s ease;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Media Section (Camera) */
        .media-section {
            position: relative;
            border-radius: 28px;
            overflow: hidden;
            background: #1a1a2e;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.2);
            margin-bottom: 24px;
        }

        #video, #canvas {
            width: 100%;
            height: auto;
            display: block;
            background: #0f0f1a;
        }

        #video {
            transform: scaleX(-1);
        }

        #canvas {
            transform: scaleX(-1);
            min-height: 380px;
        }

        .video-overlay {
            position: absolute;
            bottom: 100px;
            left: 0;
            right: 0;
            text-align: center;
            pointer-events: none;
        }

        .frame-tip {
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            display: inline-block;
            padding: 8px 20px;
            border-radius: 40px;
            color: white;
            font-size: 13px;
            font-weight: 500;
        }

        /* Buttons */
        .btn-group-custom {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .btn {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            padding: 12px 28px;
            border-radius: 60px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-warning:hover {
            background: #d97706;
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid #e2e8f0;
            color: #4b5563;
        }

        .btn-outline:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        }

        /* OTP Modern Card */
        .otp-modern-card {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            border-radius: 28px;
            padding: 24px 20px;
            margin: 24px 0 20px;
            text-align: center;
            box-shadow: 0 15px 30px -12px rgba(0, 0, 0, 0.3);
        }

        .otp-digits {
            font-size: 48px;
            font-weight: 800;
            letter-spacing: 10px;
            color: #fbbf24;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            background: rgba(0, 0, 0, 0.4);
            display: inline-block;
            padding: 12px 28px;
            border-radius: 60px;
            font-family: 'Courier New', monospace;
            margin-bottom: 12px;
        }

        .otp-hint {
            color: #94a3b8;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* Voice & Save Row */
        .voice-save-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 8px;
            flex-wrap: wrap;
        }

        .voice-input {
            flex: 2;
            position: relative;
        }

        .voice-input textarea {
            width: 100%;
            padding: 14px 20px;
            border-radius: 60px;
            border: 2px solid #e2e8f0;
            background: #f8fafc;
            font-size: 15px;
            resize: none;
            font-family: 'Inter', monospace;
            transition: all 0.2s;
            height: 52px;
            line-height: 24px;
        }

        .voice-input textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .mic-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            width: 52px;
            height: 52px;
            border-radius: 60px;
            color: white;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .mic-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .mic-btn.listening {
            background: #ef4444;
            animation: pulseMic 1.2s infinite;
        }

        @keyframes pulseMic {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.6); }
            70% { box-shadow: 0 0 0 12px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        .save-retry-group {
            display: flex;
            gap: 10px;
        }

        .note-modern {
            background: #fef9e6;
            border-left: 4px solid #f59e0b;
            padding: 12px 18px;
            border-radius: 16px;
            font-size: 12px;
            margin-top: 20px;
            color: #92400e;
        }

        .note-success {
            background: #e8f5e9;
            border-left: 4px solid #10b981;
            color: #065f46;
        }

        /* Manual Upload Section */
        .upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 28px;
            padding: 48px 32px;
            text-align: center;
            background: #fafcff;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 24px;
        }

        .upload-area:hover {
            border-color: #667eea;
            background: #f5f3ff;
        }

        .upload-area.drag-over {
            border-color: #667eea;
            background: #ede9fe;
        }

        .upload-icon {
            font-size: 64px;
            color: #667eea;
            margin-bottom: 16px;
        }

        .upload-area h4 {
            color: #1f2937;
            margin-bottom: 8px;
            font-size: 18px;
        }

        .upload-area p {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 16px;
        }

        .file-info {
            margin-top: 12px;
            font-size: 13px;
            color: #10b981;
            font-weight: 500;
        }

        .preview-image {
            max-width: 100%;
            max-height: 300px;
            margin-top: 20px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            display: none;
        }

        /* Loader */
        #loader {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(8px);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .loader-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 255, 255, 0.2);
            border-top: 4px solid #f59e0b;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loader-text {
            color: white;
            margin-top: 20px;
            font-weight: 500;
        }

        .error-message {
            color: #ef4444;
            font-size: 13px;
            margin-top: 8px;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 600px) {
            body {
                padding: 12px;
            }
            .tab-navigation {
                padding: 0 16px;
            }
            .tab-btn {
                padding: 14px 12px;
                font-size: 13px;
            }
            .tab-content {
                padding: 20px;
            }
            .otp-digits {
                font-size: 32px;
                letter-spacing: 6px;
            }
            .voice-save-row {
                flex-direction: column;
            }
            .save-retry-group {
                width: 100%;
                justify-content: space-between;
            }
            .mic-btn {
                width: 100%;
            }
        }

        .hidden-section {
            display: none;
        }

        .simple-submit {
            margin-top: 24px;
            text-align: center;
        }
    </style>
</head>
<div class="selfie-card">
    <!-- Logo Header -->
    <div class="logo-header">
        <div class="secure-badge">
            <i class="fas fa-shield-alt"></i> Secure Verification
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="tab-navigation">
        <button class="tab-btn active" data-tab="live">
            <i class="fas fa-camera"></i> Live Selfie
        </button>
        <button class="tab-btn" data-tab="manual">
            <i class="fas fa-upload"></i> Manual Upload
        </button>
    </div>

    <!-- Tab 1: Live Selfie (with OTP Verification) -->
    <div id="live-tab" class="tab-content active">
        <div id="errorMsgLive" class="error-message"></div>
        
        <!-- Video Capture Section -->
        <div id="liveVideoSection">
            <div class="media-section">
                <video id="video" playsinline autoplay muted style="width:100%;"></video>
                <div class="video-overlay">
                    <div class="frame-tip"><i class="fas fa-camera"></i> Center your face & click capture</div>
                </div>
                <div class="btn-group-custom">
                    <button class="btn btn-primary" id="btnCapture"><i class="fas fa-camera"></i> Capture Selfie</button>
                    <button class="btn btn-outline" id="refreshCameraBtn"><i class="fas fa-sync-alt"></i> Refresh Camera</button>
                </div>
            </div>
        </div>

        <!-- Captured Preview Section (hidden initially) -->
        <div id="livePreviewSection" style="display:none;">
            <div class="media-section">
                <canvas id="canvas" width="580" height="572" style="width:100%; background:#000;"></canvas>
            </div>

            <!-- OTP Display for Live Selfie -->
            <div class="otp-modern-card">
                <div class="otp-digits" id="otpDisplay">----</div>
                <div class="otp-hint">
                    <i class="fas fa-microphone-alt"></i> Speak this OTP after clicking microphone
                </div>
            </div>

            <!-- Voice Recognition & Save -->
            <div class="voice-save-row">
                <div class="voice-input">
                    <textarea id="voiceResult" rows="1" placeholder="🎤 Recognized OTP will appear here..." readonly></textarea>
                </div>
                <button id="startMicBtn" class="mic-btn"><i class="fas fa-microphone"></i></button>
                <div class="save-retry-group">
                    <button id="btnSave" class="btn btn-primary"><i class="fas fa-check-circle"></i> Verify & Save</button>
                    <button id="retryCaptureBtn" class="btn btn-outline"><i class="fas fa-redo-alt"></i> Retry</button>
                </div>
            </div>

            <div class="note-modern">
                <i class="fas fa-info-circle"></i> <strong>How it works:</strong> Click the mic icon, speak the OTP shown above. Voice will be auto-matched. Then click "Verify & Save" to complete verification.
            </div>
        </div>
    </div>

    <!-- Tab 2: Manual Upload (NO OTP Verification - Direct Upload) -->
    <div id="manual-tab" class="tab-content">
        <div id="errorMsgManual" class="error-message"></div>
        
        <!-- Upload Area -->
        <div class="upload-area" id="uploadArea">
            <div class="upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <h4>Upload Your Selfie</h4>
            <p>Click or drag & drop your photo here (JPEG, PNG)</p>
            <input type="file" id="fileInput" accept="image/jpeg,image/png,image/jpg" style="display:none;">
            <button class="btn btn-outline" id="selectFileBtn" style="margin-top: 8px;">
                <i class="fas fa-folder-open"></i> Choose File
            </button>
            <div class="file-info" id="fileInfo"></div>
            <img id="uploadPreview" class="preview-image" alt="Preview">
        </div>

        <!-- Direct Submit Button (No OTP) -->
        <div class="simple-submit">
            <button id="manualDirectSaveBtn" class="btn btn-success" style="width: 100%; padding: 16px; font-size: 16px;">
                <i class="fas fa-check-circle"></i> Submit Selfie for Verification
            </button>
        </div>

        <div class="note-modern note-success">
            <i class="fas fa-info-circle"></i> <strong>Note:</strong> Upload a clear front-facing photo. No OTP verification required for manual upload. Click the button above to submit your selfie.
        </div>
    </div>
</div>

<!-- Loader -->
<div id="loader">
    <div class="loader-spinner"></div>
    <div class="loader-text">Processing verification...</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
    // Global Variables
    let currentStream = null;
    let capturedImageBase64 = null;
    let liveGeneratedOTP = Math.floor(Math.random() * (9999 - 1111 + 1) + 1111);
    let manualUploadedImageBase64 = null;
    
    let liveRecognition = null;
    
    // DOM Elements
    const liveTab = document.getElementById('live-tab');
    const manualTab = document.getElementById('manual-tab');
    const tabBtns = document.querySelectorAll('.tab-btn');
    const videoSection = document.getElementById('liveVideoSection');
    const previewSection = document.getElementById('livePreviewSection');
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const btnCapture = document.getElementById('btnCapture');
    const retryCaptureBtn = document.getElementById('retryCaptureBtn');
    const refreshCameraBtn = document.getElementById('refreshCameraBtn');
    const btnSave = document.getElementById('btnSave');
    const otpDisplay = document.getElementById('otpDisplay');
    const voiceResult = document.getElementById('voiceResult');
    const startMicBtn = document.getElementById('startMicBtn');
    
    // Manual elements
    const manualDirectSaveBtn = document.getElementById('manualDirectSaveBtn');
    const fileInput = document.getElementById('fileInput');
    const selectFileBtn = document.getElementById('selectFileBtn');
    const uploadArea = document.getElementById('uploadArea');
    const fileInfo = document.getElementById('fileInfo');
    const uploadPreview = document.getElementById('uploadPreview');
    
    // Helper: Toast
    function showToast(message, isError = false) {
        Toastify({
            text: message,
            duration: 3500,
            close: true,
            gravity: "top",
            position: "center",
            style: {
                background: isError ? "linear-gradient(135deg, #ef4444, #dc2626)" : "linear-gradient(135deg, #667eea, #764ba2)",
                borderRadius: "40px",
                fontWeight: "500"
            }
        }).showToast();
    }
    
    // Initialize Camera
    function initCamera() {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }
        const constraints = {
            audio: false,
            video: { width: { ideal: 740 }, height: { ideal: 572 }, facingMode: "user" }
        };
        navigator.mediaDevices.getUserMedia(constraints)
            .then(stream => {
                currentStream = stream;
                video.srcObject = stream;
                video.play().catch(e => console.warn);
                document.getElementById('errorMsgLive').innerText = "";
            })
            .catch(err => {
                console.error("Camera error:", err);
                document.getElementById('errorMsgLive').innerText = "⚠️ Camera access denied. Please allow permissions or use Manual Upload tab.";
                showToast("Camera access required. Please enable camera or switch to Manual Upload.", true);
            });
    }
    
    // Setup Live OTP
    otpDisplay.innerText = liveGeneratedOTP;
    
    // Capture Selfie
    btnCapture.addEventListener('click', function() {
        if (!video.srcObject || !video.videoWidth) {
            showToast("Camera not ready. Please refresh or allow permissions.", true);
            return;
        }
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        videoSection.style.display = 'none';
        previewSection.style.display = 'block';
    });
    
    // Retry Capture (go back to video)
    retryCaptureBtn.addEventListener('click', function() {
        videoSection.style.display = 'block';
        previewSection.style.display = 'none';
        capturedImageBase64 = null;
        voiceResult.value = '';
    });
    
    // Refresh Camera
    refreshCameraBtn.addEventListener('click', function() {
        initCamera();
        showToast("Camera refreshed", false);
    });
    
    // Voice Recognition for Live
    function setupLiveVoice() {
        if ('webkitSpeechRecognition' in window) {
            liveRecognition = new webkitSpeechRecognition();
            liveRecognition.lang = 'en-US';
            liveRecognition.interimResults = false;
            liveRecognition.maxAlternatives = 1;
            
            liveRecognition.onresult = (event) => {
                const transcript = event.results[0][0].transcript.trim();
                const digits = transcript.replace(/\D/g, '');
                voiceResult.value = digits || transcript;
                startMicBtn.classList.remove('listening');
                startMicBtn.innerHTML = '<i class="fas fa-microphone"></i>';
            };
            liveRecognition.onerror = () => {
                showToast("Voice error. Please speak clearly.", true);
                startMicBtn.classList.remove('listening');
                startMicBtn.innerHTML = '<i class="fas fa-microphone"></i>';
            };
            liveRecognition.onend = () => {
                startMicBtn.classList.remove('listening');
                startMicBtn.innerHTML = '<i class="fas fa-microphone"></i>';
            };
        } else {
            startMicBtn.disabled = true;
            startMicBtn.style.opacity = "0.5";
            showToast("Speech recognition not supported in this browser", true);
        }
    }
    
    let isLiveListening = false;
    startMicBtn.addEventListener('click', () => {
        if (!liveRecognition) {
            showToast("Voice recognition not available", true);
            return;
        }
        if (isLiveListening) {
            liveRecognition.stop();
            return;
        }
        voiceResult.value = "";
        liveRecognition.start();
        startMicBtn.classList.add('listening');
        startMicBtn.innerHTML = '<i class="fas fa-microphone-slash"></i>';
        isLiveListening = true;
        liveRecognition.onend = () => {
            isLiveListening = false;
            startMicBtn.classList.remove('listening');
            startMicBtn.innerHTML = '<i class="fas fa-microphone"></i>';
        };
    });
    
    // Save Live Selfie with OTP verification
    btnSave.addEventListener('click', function() {
        const spokenOtp = voiceResult.value.replace(/\D/g, '');
        if (!spokenOtp) {
            showToast("Please click microphone and speak the OTP", true);
            return;
        }
        if (spokenOtp !== liveGeneratedOTP.toString()) {
            showToast(`OTP mismatch! Expected ${liveGeneratedOTP}, got ${spokenOtp}`, true);
            return;
        }
        
        // Flip canvas for natural orientation
        const sourceCanvas = canvas;
        const finalCanvas = document.createElement('canvas');
        const w = sourceCanvas.width;
        const h = sourceCanvas.height;
        finalCanvas.width = w;
        finalCanvas.height = h;
        const ctx = finalCanvas.getContext('2d');
        ctx.translate(w, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(sourceCanvas, 0, 0, w, h);
        let imageBase64 = finalCanvas.toDataURL("image/png").replace('data:image/png;base64,', '');
        
        if (!imageBase64 || imageBase64.length < 100) {
            showToast("Selfie image missing. Please capture again.", true);
            return;
        }
        
        $("#loader").css("display", "flex");
        $.ajax({
            url: "{{route('loan.journey.post.selfie')}}",
            type: "POST",
            data: {
                loan_request_id: "{{ $loan_request_id }}",
                selfie_image: imageBase64,
                "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
                console.log(response);
                $("#loader").hide();
                if (response.status == true) {
                    showToast("Verification successful! Redirecting...");
                    if (response.link) {
                        window.location.href = response.link;
                    } else {
                        window.location.href = "/dashboard";
                    }
                } else {
                    showToast(response.message || "Verification failed", true);
                }
            },
            error: function() {
                $("#loader").hide();
                showToast("Server error. Please try again.", true);
            }
        });
    });
    
    // ==================== MANUAL UPLOAD SECTION (NO OTP) ====================
    
    // Handle file selection
    selectFileBtn.addEventListener('click', () => fileInput.click());
    uploadArea.addEventListener('click', (e) => {
        if (e.target === uploadArea || e.target.closest('.upload-area')) {
            fileInput.click();
        }
    });
    
    // Drag & Drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('drag-over');
    });
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('drag-over');
    });
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('drag-over');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFile(files[0]);
        }
    });
    
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFile(e.target.files[0]);
        }
    });
    
    function handleFile(file) {
        if (!file.type.match('image.*')) {
            showToast("Please select a valid image file (JPEG/PNG)", true);
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            showToast("File size should be less than 5MB", true);
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                // Create canvas to resize and get base64
                const tempCanvas = document.createElement('canvas');
                tempCanvas.width = 580;
                tempCanvas.height = 572;
                const ctx = tempCanvas.getContext('2d');
                ctx.drawImage(img, 0, 0, 580, 572);
                manualUploadedImageBase64 = tempCanvas.toDataURL("image/png").replace('data:image/png;base64,', '');
                
                uploadPreview.src = e.target.result;
                uploadPreview.style.display = 'block';
                fileInfo.innerHTML = `<i class="fas fa-check-circle"></i> File loaded: ${file.name}`;
                showToast("Image loaded successfully! Click 'Submit Selfie' to verify.", false);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
    
    // Direct Save for Manual Upload (NO OTP)
    manualDirectSaveBtn.addEventListener('click', function() {
        if (!manualUploadedImageBase64) {
            showToast("Please upload a selfie image first", true);
            return;
        }
        
        $("#loader").css("display", "flex");
        $.ajax({
            url: "{{url('/authenticate-selfie')}}",
            type: "POST",
            data: {
                selfie_image: manualUploadedImageBase64,
                "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
                console.log(response);
                $("#loader").hide();
                if (response.status == true) {
                    showToast("Verification successful! Redirecting...");
                    if (response.link) {
                        window.location.href = response.link;
                    } else {
                        window.location.href = "/dashboard";
                    }
                } else {
                    showToast(response.message || "Verification failed", true);
                }
            },
            error: function() {
                $("#loader").hide();
                showToast("Server error. Please try again.", true);
            }
        });
    });
    
    // Tab Switching
    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const tabId = btn.getAttribute('data-tab');
            tabBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            if (tabId === 'live') {
                document.getElementById('live-tab').classList.add('active');
                // Ensure camera is running if not already
                if (!currentStream || !currentStream.active) {
                    initCamera();
                }
                // Reset to video view if needed
                if (previewSection.style.display === 'block') {
                    videoSection.style.display = 'block';
                    previewSection.style.display = 'none';
                    voiceResult.value = '';
                }
            } else {
                document.getElementById('manual-tab').classList.add('active');
                // Stop camera to save resources
                if (currentStream) {
                    currentStream.getTracks().forEach(track => track.stop());
                    currentStream = null;
                }
            }
        });
    });
    
    // Initialize
    initCamera();
    setupLiveVoice();
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }
    });
</script>
@endsection
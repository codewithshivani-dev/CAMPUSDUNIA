@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary-blue: #2C5AA0;
            --secondary-blue: #3B82F6;
            --light-blue: #EFF6FF;
            --dark-blue: #1E3A8A;
            --accent-blue: #60A5FA;
            --success-green: #10B981;
            --warning-orange: #F59E0B;
            --text-dark: #1F2937;
            --text-light: #6B7280;
            --border-color: #D1D5DB;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .logo-icon {
            width: 60px;
            height: 60px;
            background: var(--primary-blue);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
        }
        
        .logo-text h1 {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 5px;
        }
        
        .logo-text p {
            color: var(--text-light);
            font-size: 14px;
        }
        
        .welcome-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--shadow-lg);
            margin-bottom: 40px;
        }
        
        .welcome-content {
            max-width: 700px;
            margin: 0 auto;
            text-align: center;
        }
        
        .welcome-content h2 {
            font-size: 28px;
            color: var(--primary-blue);
            margin-bottom: 15px;
            font-weight: 700;
        }
        
        .welcome-content p {
            color: var(--text-light);
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        
        .registration-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .option-card {
            background: white;
            border-radius: 16px;
            padding: 35px 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none !important;
            color: inherit;
            display: block;
            border: 2px solid var(--border-color);
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
        }
        
        .option-card:hover {
            transform: translateY(-10px) scale(1.04);
            border-color: var(--primary-blue);
        }
        
        .option-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
        }
        
        .option-card.admission::before {
            background: linear-gradient(90deg, var(--success-green), #34D399);
        }
        
        .option-card.interview::before {
            background: linear-gradient(90deg, var(--warning-orange), #FBBF24);
        }
        
        .card-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 36px;
            color: white;
        }
        
        .admission .card-icon {
            background: linear-gradient(135deg, var(--success-green), #34D399);
        }
        
        .interview .card-icon {
            background: linear-gradient(135deg, var(--warning-orange), #FBBF24);
        }
        
        .option-card h3 {
            font-size: 22px;
            margin-bottom: 12px;
            color: var(--text-dark);
            font-weight: 700;
        }
        
        .option-card p {
            color: var(--text-light);
            line-height: 1.6;
            margin-bottom: 20px;
            font-size: 15px;
        }
        
        .card-features {
            text-align: left;
            margin-top: 20px;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 14px;
            color: var(--text-light);
        }
        
        .feature-item i {
            color: var(--success-green);
            font-size: 12px;
        }
        
        /* UPDATED MODE SECTION - Matching Lead Source UI */
        .mode-section {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
        }
        
        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
        }
        
        .section-title i {
            color: var(--primary-blue);
            font-size: 20px;
        }
        
        .section-title h3 {
            font-size: 18px;
            color: var(--text-dark);
            font-weight: 600;
        }
        
        /* Lead Source Style Options Grid */
        .option-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 10px;
        }
        
        .option-card-mode {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 130px;
        }
        
        .option-card-mode:hover {
            border-color: var(--primary-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .option-card-mode.selected {
            border-color: var(--primary-blue);
            background: rgba(59, 130, 246, 0.05);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .option-card-mode i {
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: var(--primary-blue);
        }
        
        .option-card-mode h4 {
            margin: 5px 0 4px;
            color: #1e293b;
            font-weight: 600;
            font-size: 15px;
        }
        
        .option-card-mode p {
            color: #64748b;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
        }
        
        /* SOURCE SECTION */
        .source-section {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: var(--shadow);
            margin-bottom: 40px;
            border: 1px solid #e2e8f0;
        }
        
        .select-wrapper {
            position: relative;
        }
        
        .select-wrapper i {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            pointer-events: none;
        }
        
        select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            background: white;
            appearance: none;
            color: var(--text-dark);
            transition: border-color 0.2s ease;
        }
        
        select:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .card-action {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 25px;
            font-weight: 600;
            color: var(--primary-blue);
            opacity: 0.8;
            transition: gap 0.3s ease, opacity 0.3s ease;
        }

        .option-card:hover .card-action {
            gap: 16px;
            opacity: 1;
        }

        .card-action i {
            transition: transform 0.3s ease;
        }

        .fincap-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
            margin-left: 10px;
        }
        
        /* Required field indicator */
        .required {
            color: #ef4444;
            margin-left: 4px;
        }
        
        @media (max-width: 768px) {
            .registration-options {
                grid-template-columns: 1fr;
            }
            
            .option-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .welcome-card {
                padding: 25px 20px;
            }
            
            .logo {
                flex-direction: column;
                text-align: center;
            }
            
            .option-card-mode {
                padding: 16px;
                min-height: 120px;
            }
            
            .mode-section, .source-section {
                padding: 20px;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 10px;
            }
            
            .welcome-card {
                padding: 20px 15px;
            }
            
            .mode-section, .source-section {
                padding: 16px;
            }
            
            .option-card-mode i {
                font-size: 1.5rem;
            }
        }
        
    </style>

    <div class="container-fluid">
        <div class="welcome-card">
            <div class="welcome-content">
                <h2>Welcome to Our Registration Portal</h2>
                <p>Begin your journey with our Institute by selecting the appropriate registration type below. Our streamlined process ensures a smooth experience whether you're applying for admission or scheduling an interview.</p>
                <div style="background: var(--light-blue); padding: 15px; border-radius: 10px; border-left: 4px solid var(--primary-blue);">
                    <p style="margin: 0; color: var(--primary-blue); font-weight: 500;">
                        <i class="fas fa-info-circle"></i> Please select your registration mode and source before proceeding
                    </p>
                </div>
            </div>
        </div>
        
        <!-- UPDATED MODE SECTION - Matching Lead Source UI -->
        <div class="mode-section">
            <div class="section-title">
                <i class="fas fa-laptop-house"></i>
                <h3>1. Select Lead Source <span class="required">*</span></h3>
            </div>
            <div class="option-grid">
                <div class="option-card-mode selected" onclick="selectMode('online')" data-mode="online">
                    <i class="fas fa-globe"></i>
                    <h4>Online Registration</h4>
                    <p>Complete the process digitally</p>
                </div>
                <div class="option-card-mode" onclick="selectMode('offline')" data-mode="offline">
                    <i class="fas fa-walking"></i>
                    <h4>Walk-in/Offline</h4>
                    <p>Direct institute visit</p>
                </div>
                <div class="option-card-mode" onclick="selectMode('phone')" data-mode="phone">
                    <i class="fas fa-phone-alt"></i>
                    <h4>Phone Registration</h4>
                    <p>Telephonic registration</p>
                </div>
                <!-- <div class="option-card-mode" onclick="selectMode('referral')" data-mode="referral">
                    <i class="fas fa-users"></i>
                    <h4>Referral</h4>
                    <p>Existing student referral</p>
                </div> -->
            </div>
            <div style="margin-top: 15px; font-size: 12px; color: #64748b; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-info-circle"></i>
                <span>Selected mode: <strong id="selectedModeText">Online Registration</strong></span>
            </div>
        </div>
        
        <!-- SOURCE SECTION -->
        <div class="source-section">
            <div class="section-title">
                <i class="fas fa-bullhorn"></i>
                <h3>2. How did you hear about us? <span class="required">*</span></h3>
            </div>
            <div class="select-wrapper">
                <select id="source" onchange="updateSource()">
                    <option value="">Select referral source</option>
                    <option value="friend_family">Friend or Family Referral</option>
                    <option value="social_media">Social Media (Facebook, Instagram, etc.)</option>
                    <option value="website">Institute Website</option>
                    <option value="newspaper">Newspaper Advertisement</option>
                    <option value="seminar">Educational Seminar/Workshop</option>
                    <option value="alumni">Alumni Reference</option>
                    <option value="school">School/College Referral</option>
                    <option value="other">Other Source</option>
                </select>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        
        <!-- REGISTRATION OPTIONS -->
        <div class="registration-options">
            <a href="javascript:void(0)" class="option-card admission" onclick="proceedToAdmission()">
                <div class="card-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h3>Admission Registration</h3>
                <p>Register for admission to Institute. Complete the application with personal, educational, and guardian details.</p>
                
                <div class="card-features">
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Personal & Educational Details</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Guardian/Parent Information</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Document Upload Section</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Real-time Form Validation</span>
                    </div>
                </div>
                <div class="card-action">
                    <span>Proceed</span>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>
            
            <a href="javascript:void(0)" class="option-card interview" onclick="proceedToInterview()">
                <div class="card-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3>Interview Registration</h3>
                <p>Schedule an interview session with our admission committee. Provide basic details and select preferred time slots.</p>
                
                <div class="card-features">
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Basic Personal Information</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Interview Time Selection</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Purpose & Experience Details</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Instant Confirmation</span>
                    </div>
                </div>
                <div class="card-action">
                    <span>Proceed</span>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        // Application state
        let selectedMode = 'online';
        let selectedSource = '';
        
        // Mode labels for display
        const modeLabels = {
            'online': 'Online Registration',
            'offline': 'Walk-in/Offline',
            'phone': 'Phone Registration',
            // 'referral': 'Referral'
        };
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Load saved selections from localStorage
            const savedMode = localStorage.getItem('registration_mode') || 'online';
            const savedSource = localStorage.getItem('registration_source') || '';
            
            // Set mode
            selectMode(savedMode);
            
            // Set source
            if (savedSource) {
                document.getElementById('source').value = savedSource;
                selectedSource = savedSource;
            }
        });
        
        function selectMode(mode) {
            selectedMode = mode;
            
            // Update UI
            document.querySelectorAll('.option-card-mode').forEach(card => {
                card.classList.remove('selected');
            });
            
            const selectedCard = document.querySelector(`[data-mode="${mode}"]`);
            if (selectedCard) {
                selectedCard.classList.add('selected');
            }
            
            // Update display text
            document.getElementById('selectedModeText').textContent = modeLabels[mode] || mode;
            
            // Save to localStorage
            localStorage.setItem('registration_mode', mode);
            
            // Save to server via AJAX (using your existing endpoint)
            savePreferences();
        }
        
        function updateSource() {
            const sourceSelect = document.getElementById('source');
            selectedSource = sourceSelect.value;
            
            // Save to localStorage
            localStorage.setItem('registration_source', selectedSource);
            
            // Save to server via AJAX (using your existing endpoint)
            savePreferences();
        }
        
        function savePreferences() {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Prepare data
            const data = {
                mode: selectedMode,
                source: selectedSource,
                _token: csrfToken
            };
            
            // Send to your existing endpoint
            fetch('/save-preferences', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Preferences saved successfully:', data);
            })
            .catch(error => {
                console.error('Error saving preferences:', error);
            });
        }
        
        function validateSelections() {
            const source = document.getElementById('source').value;
        
            if (!selectedMode) {
                showToast('Please select a registration mode', 'error');
                return false;
            }
        
            if (source === "" || source === null) {
                showToast('Please select how you heard about us', 'error');
                return false;
            }
        
            return true;
        }
                
        function proceedToAdmission() {
        
            if (!validateSelections()) return;
        
            const source = document.getElementById('source').value;
        
            savePreferences();
        
            window.location.href = `/admission-form?mode=${selectedMode}&source=${source}`;
        }
                
        function proceedToInterview() {
        
            if (!validateSelections()) return;
        
            const source = document.getElementById('source').value;
        
            savePreferences();
        
            window.location.href = `/interview-form?mode=${selectedMode}&source=${source}`;
        }
        
        function showToast(message, type = 'error') {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                close: true,
                style: {
                    background: type === 'error'
                        ? "linear-gradient(to right, #ef4444, #dc2626)"
                        : "linear-gradient(to right, #10b981, #059669)"
                }
            }).showToast();
        }
    </script>
@endsection
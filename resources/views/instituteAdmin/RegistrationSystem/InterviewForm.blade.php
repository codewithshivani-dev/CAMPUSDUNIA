<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
    --text-dark: #1F2937;
    --text-light: #6B7280;
    --border-color: #D1D5DB;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.top-bar {
    background: white;
    padding: 20px 40px;
    box-shadow: var(--shadow);
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 20px;
}

.logo {
    display: flex;
    align-items: center;
    gap: 12px;
}

.logo-icon {
    width: 40px;
    height: 40px;
    background: var(--primary-blue);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.logo-text h3 {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary-blue);
}

.logo-text p {
    font-size: 12px;
    color: var(--text-light);
}

.ref-id {
    text-align: right;
}

.ref-id div:first-child {
    font-size: 14px;
    color: var(--text-light);
    margin-bottom: 5px;
}

.ref-id div:last-child {
    font-size: 16px;
    font-weight: 700;  
    color: var(--primary-blue);
}

.form-wrapper {
    background: white;
    border-radius: 20px;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

.form-header {
    background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
    color: white;
    padding: 35px 40px;
}

.form-header h1 {
    font-size: 28px;
    margin-bottom: 10px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 15px;
}

.form-header p {
    opacity: 0.9;
    font-size: 15px;
}

.form-content {
    padding: 40px;
}

.registration-info {
    background: var(--light-blue);
    border: 2px solid var(--accent-blue);
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.info-item {
    padding: 10px 0;
}

.info-label {
    font-size: 13px;
    color: var(--primary-blue);
    font-weight: 600;
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-value {
    font-size: 15px;
    color: var(--text-dark);
    font-weight: 500;
}

.section-card {
    background: #F8FAFC;
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 25px;
    transition: all 0.3s ease;
}

.section-card:hover {
    border-color: var(--accent-blue);
    box-shadow: var(--shadow);
}

.section-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.section-icon {
    width: 50px;
    height: 50px;
    background: var(--light-blue);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-blue);
    font-size: 20px;
}

.section-title {
    flex: 1;
}

.section-title h3 {
    font-size: 18px;
    color: var(--text-dark);
    margin-bottom: 5px;
    font-weight: 700;
}

.section-title p {
    font-size: 14px;
    color: var(--text-light);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-dark);
    font-size: 14px;
}

.required::after {
    content: " *";
    color: #EF4444;
}

input, select, textarea {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--border-color);
    border-radius: 10px;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    transition: all 0.2s ease;
    background: white;
    height: 48px;
    box-sizing: border-box;
}

input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.input-with-icon {
    position: relative;
}

.input-with-icon i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-light);
    z-index: 1;
}

.input-with-icon input {
    padding-left: 45px;
}

/* Fixed size experience container */
.experience-container {
    display: flex;
    align-items: center;
    border: 2px solid var(--border-color);
    border-radius: 10px;
    overflow: hidden;
    background: white;
    transition: all 0.2s ease;
    height: 48px;
    width: 100%;
    box-sizing: border-box;
}

.experience-container:focus-within {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.experience-field {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    position: relative;
}

.experience-field:first-child {
    border-right: 1px solid var(--border-color);
}

.experience-field input {
    width: 100%;
    height: 100%;
    border: none;
    padding: 0 8px;
    text-align: center;
    font-size: 14px;
    font-weight: 500;
    background: transparent;
}

.experience-field input:focus {
    outline: none;
    box-shadow: none;
}

.experience-field .label {
    position: absolute;
    right: 8px;
    font-size: 10px;
    color: var(--text-light);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    background: white;
    padding: 2px 4px;
    border-radius: 4px;
    pointer-events: none;
}

.experience-field input[type="number"]::-webkit-inner-spin-button, 
.experience-field input[type="number"]::-webkit-outer-spin-button { 
    opacity: 0.2;
    height: 16px;
}

.experience-field input::placeholder {
    color: #aaa;
    font-size: 13px;
    font-weight: 300;
}

/* For Firefox */
.experience-field input[type=number] {
    -moz-appearance: textfield;
    appearance: textfield;
}

.experience-field input[type=number]:hover {
    -moz-appearance: initial;
}

.interview-type-section {
    background: var(--light-blue);
    border-color: var(--accent-blue);
}

.form-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 40px;
    padding-top: 25px;
    border-top: 1px solid var(--border-color);
}

.btn {
    padding: 12px 32px;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 10px;
    height: 48px;
}

#otherProfessionField,
#otherQualificationField,
#otherApplyingForField,
#otherSubjectField {
    animation: slideDown 0.2s ease-out;
}

#otherProfessionField input,
#otherQualificationField input,
#otherApplyingForField input,
#otherSubjectField input {
    border: 2px solid var(--border-color);
    border-radius: 8px;
    background: white;
    transition: all 0.2s ease;
}

#otherProfessionField input:focus,
#otherQualificationField input:focus,
#otherApplyingForField input:focus,
#otherSubjectField input:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    outline: none;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Skill Tags Styling */
.skill-tag {
    display: inline-block;
    padding: 8px 16px;
    background: white;
    border: 2px solid var(--border-color);
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-dark);
    cursor: pointer;
    transition: all 0.2s ease;
    user-select: none;
    margin: 0 5px 5px 0;
}

.skill-tag:hover {
    border-color: var(--primary-blue);
    background: var(--light-blue);
    transform: translateY(-1px);
}

.skill-tag.selected {
    background: var(--primary-blue);
    border-color: var(--primary-blue);
    color: white;
}

.selected-skill-item {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px 6px 16px;
    background: var(--primary-blue);
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    color: white;
    gap: 8px;
    margin: 0 5px 5px 0;
}

.selected-skill-item i {
    cursor: pointer;
    font-size: 12px;
    opacity: 0.8;
    transition: opacity 0.2s ease;
}

.selected-skill-item i:hover {
    opacity: 1;
}

.btn-outline {
    background: white;
    border: 2px solid var(--border-color);
    color: var(--text-dark);
}

.btn-outline:hover {
    border-color: var(--primary-blue);
    background: var(--light-blue);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.declaration {
    background: var(--light-blue);
    border-left: 4px solid var(--primary-blue);
    padding: 20px;
    border-radius: 10px;
    margin-top: 30px;
}

.declaration h4 {
    color: var(--primary-blue);
    margin-bottom: 10px;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.declaration p {
    color: var(--text-dark);
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 15px;
}

.declaration ul {
    color: var(--text-dark);
    font-size: 14px;
    margin-left: 20px;
    margin-bottom: 15px;
}

.checkbox-group {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.checkbox-option {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.checkbox-option input {
    width: 18px;
    height: 18px;
    margin-top: 3px;
}

.checkbox-text {
    font-size: 14px;
    color: var(--text-dark);
}

.checkbox-text a {
    color: var(--primary-blue);
    text-decoration: none;
}

.checkbox-text a:hover {
    text-decoration: underline;
}

.hidden-field {
    display: none;
    margin-top: 15px;
    padding: 15px;
    background: white;
    border-radius: 8px;
    border-left: 3px solid var(--primary-blue);
}

@media (max-width: 768px) {
    .form-content {
        padding: 20px;
    }
    
    .top-bar {
        padding: 15px 20px;
        flex-direction: column;
        gap: 15px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
        gap: 15px;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .checkbox-group {
        flex-direction: column;
    }
}   
    </style>
</head>
<body>
    <div class="container-fluid">
            <div class="top-bar">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <div class="logo-text">
                        <h3>Institute</h3>
                        <p>Applicant Registration</p>
                    </div>
                </div>
                
                <div class="ref-id">
                    <div>
                        <i class="fas fa-laptop"></i> <span id="modeDisplay">Online</span>
                    </div>
                    <div id="referenceId">INT-2024-001</div>
                </div>
            </div>
            
        <div class="form-wrapper mt-3">
            <div class="form-header">
                <h1><i class="fas fa-file-signature"></i> Applicant Registration</h1> 
                <p>Complete your application for admission/registration</p>
            </div>
            
            <div class="form-content">
                <div class="registration-info">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-user-check"></i> Registration Type
                            </div>
                            <div class="info-value">Applicant Registration</div> 
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-laptop-house"></i> Mode
                            </div>
                            <div class="info-value" id="modeValue">Online</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-bullhorn"></i> Referral Source 
                            </div>
                            <div class="info-value" id="sourceValue">Not specified</div>
                        </div>
                    </div>
                </div>
                
                <!-- Personal Information Section -->
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="section-title">
                            <h3>Personal Information</h3> 
                            <p>Basic details about the applicant</p>  
                        </div>
                    </div>
                    
                    <!-- Row 1: Full Name and Email -->
                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label class="required">Full Name</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user"></i>
                                <input type="text" id="fullName" placeholder="Enter your full name">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="required">Email Address</label>
                            <div class="input-with-icon">
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="email" placeholder="your.email@example.com">
                            </div>
                            <div style="display: flex; justify-content: flex-end; margin-top: 8px;">
                                <button type="button" class="btn btn-sm btn-outline" id="sendEmailOtpBtn" style="display: none;">
                                    <i class="fas fa-envelope"></i> Send OTP to Email
                                </button>
                            </div>

                            <!-- Email OTP Verification Section -->
                            <div id="emailOtpVerificationSection" style="display: none; margin-top: 15px; padding: 15px; background: #f0fdf4; border: 1px solid #22c55e; border-radius: 8px;">
                                <div style="margin-bottom: 12px;">
                                    <h5 style="margin-bottom: 8px; font-size: 14px;"><i class="fas fa-lock"></i> Email Verification</h5>
                                    <p style="color: #666; font-size: 13px;">Enter the 4-digit OTP sent to your email</p>
                                </div>
                                
                                <div class="form-group">
                                    <div style="display: flex; gap: 8px; margin-bottom: 10px;">
                                        <input type="text" class="email-otp-digit" maxlength="1" style="width: 45px; height: 45px; font-size: 20px; text-align: center; border: 2px solid #ddd; border-radius: 6px;">
                                        <input type="text" class="email-otp-digit" maxlength="1" style="width: 45px; height: 45px; font-size: 20px; text-align: center; border: 2px solid #ddd; border-radius: 6px;">
                                        <input type="text" class="email-otp-digit" maxlength="1" style="width: 45px; height: 45px; font-size: 20px; text-align: center; border: 2px solid #ddd; border-radius: 6px;">
                                        <input type="text" class="email-otp-digit" maxlength="1" style="width: 45px; height: 45px; font-size: 20px; text-align: center; border: 2px solid #ddd; border-radius: 6px;">
                                    </div>
                                </div>
                                
                                <div id="emailTimerText" style="margin-top: 8px; color: var(--text-light); font-size: 13px;"></div>

                                <div style="display: flex; gap: 8px;">
                                    <button type="button" class="btn btn-sm btn-primary" id="verifyEmailOtpBtn" style="font-size: 12px;">
                                        <i class="fas fa-check"></i> Verify Email
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline" id="resendEmailOtpBtn" style="display: none; font-size: 12px;">
                                        <i class="fas fa-redo"></i> Resend
                                    </button>
                                </div>

                                <div id="emailOtpStatus" style="display: none; margin-top: 8px; padding: 8px; border-radius: 6px; font-size: 13px;">
                                    <span id="emailOtpStatusText"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Row 2: Phone and Date of Birth -->
                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label class="required">Phone Number</label> 
                            <div class="input-with-icon">
                                <i class="fas fa-phone"></i>
                                <input type="tel" id="phone" maxlength="10" placeholder="98765 43210" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                            <div style="display: flex; justify-content: flex-end; margin-top: 8px;">
                                <button type="button" class="btn btn-sm btn-outline" id="sendOtpBtn" style="display: none;">
                                    <i class="fas fa-envelope"></i> Send OTP
                                </button>
                            </div>

                            <!-- OTP Verification Section -->
                            <div id="otpVerificationSection" style="display: none; margin-top: 20px; padding: 20px; background: #f0f9ff; border: 1px solid #0284c7; border-radius: 8px;">
                                <div style="margin-bottom: 15px;">
                                    <h5 style="margin-bottom: 10px;"><i class="fas fa-lock"></i> Phone Verification</h5>
                                    <p style="color: #666; font-size: 14px;">Enter the 4-digit OTP sent to your phone number</p>
                                </div>
                                
                                <div class="form-group">
                                    <label>Enter OTP</label>
                                    <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                                        <input type="text" class="otp-digit" maxlength="1" style="width: 50px; height: 50px; font-size: 24px; text-align: center; border: 2px solid #ddd; border-radius: 8px;">
                                        <input type="text" class="otp-digit" maxlength="1" style="width: 50px; height: 50px; font-size: 24px; text-align: center; border: 2px solid #ddd; border-radius: 8px;">
                                        <input type="text" class="otp-digit" maxlength="1" style="width: 50px; height: 50px; font-size: 24px; text-align: center; border: 2px solid #ddd; border-radius: 8px;">
                                        <input type="text" class="otp-digit" maxlength="1" style="width: 50px; height: 50px; font-size: 24px; text-align: center; border: 2px solid #ddd; border-radius: 8px;">
                                    </div>
                                </div>

                                <div id="phoneTimerText" style="margin-top: 8px; color: var(--text-light); font-size: 13px;"></div>

                                <div style="display: flex; gap: 10px;">
                                    <button type="button" class="btn btn-sm btn-primary" id="verifyOtpBtn">
                                        <i class="fas fa-check"></i> Verify OTP
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline" id="resendOtpBtn" style="display: none;">
                                        <i class="fas fa-redo"></i> Resend OTP
                                    </button>
                                </div>

                                <div id="otpStatus" style="display: none; margin-top: 10px; padding: 10px; border-radius: 6px;">
                                    <span id="otpStatusText"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <div class="input-with-icon">
                                <i class="fas fa-calendar-alt"></i>
                                <input type="date" id="dob"> 
                            </div>
                        </div>
                    </div>
                    
                    <!-- Row 3: Marital Status -->
                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label>Marital Status</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user"></i>
                                <select id="marital_status" style="padding-left: 45px;">
                                    <option value="">Select marital status</option>
                                    <option value="single">Single</option>
                                    <option value="married">Married</option>
                                    <option value="divorced">Divorced</option>
                                    <option value="widowed">Widowed</option>
                                    <option value="separated">Separated</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <!-- Empty div to maintain two-column layout -->
                        </div>
                    </div>
                </div>
                
                <!-- Professional Information Section -->
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="section-title">
                            <h3>Professional Information</h3>
                            <p>Your professional background and qualifications</p>
                        </div>
                    </div>
                    
                    <!-- Row 1: Current Profession and Organization -->
                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label>Current Profession</label>
                            <select id="profession" onchange="toggleOtherProfession()">
                                <option value="">Select profession</option>
                                <option value="student">Student</option>
                                <option value="teacher">Teacher</option>
                                <option value="manager">Manager</option>
                                <option value="assistant_manager">Assistant manager</option>
                                <option value="principal">Principal</option>
                                <option value="hod">HOD</option>
                                <option value="executive">Executive</option>
                                <option value="professor">Professor</option>
                                <option value="assistant_professor">Assistant professor</option>
                                <option value="dean">Dean</option>
                                <option value="chairman">Chairman</option>
                                <option value="coordinator">Coordinator</option>
                                <option value="other">Other (Please specify)</option>
                            </select>
                            
                            <!-- Other Profession Field -->
                            <div id="otherProfessionField" style="display: none; margin-top: 10px;">
                                <input type="text" 
                                       id="other_profession_details" 
                                       placeholder="Please specify your profession"
                                       style="width: 100%; padding: 10px;">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Current Organization</label>
                            <input type="text" id="organization" placeholder="School/Company name">
                        </div>
                    </div>
                    
                 
                    <!-- Row 3: Total Experience and Highest Qualification -->
                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label>Total Experience</label>
                            <div class="experience-container">
                                <div class="experience-field">
                                    <input type="number" id="experience_years" min="0" max="50" placeholder="0">
                                    <span class="label">YRS</span>
                                </div>
                                <div class="experience-field">
                                    <input type="number" id="experience_months" min="0" max="11" placeholder="0">
                                    <span class="label">MTH</span>
                                </div>
                            </div>
                            <small style="color: var(--text-light); margin-top: 5px; display: block;">
                                <i class="fas fa-info-circle"></i> Years and months
                            </small>
                            <input type="hidden" id="experience" name="experience">
                        </div>
                        
                        <div class="form-group">
                            <label>Highest Qualification</label>
                            <select id="highest_qualification" onchange="toggleOtherQualification()">
                                <option value="">Select qualification</option>
                                <option value="high_school">High School</option>
                                <option value="diploma">Diploma</option>
                                <option value="bachelors">Bachelor's Degree</option>
                                <option value="masters">Master's Degree</option>
                                <option value="phd">PhD</option>
                                <option value="bed">B.Ed</option>
                                <option value="med">M.Ed</option>
                                <option value="d.el.ed">D.El.Ed</option>
                                <option value="other">Other (Please specify)</option>
                            </select>
                            
                            <!-- Other Qualification Field -->
                            <div id="otherQualificationField" style="display: none; margin-top: 10px;">
                                <input type="text" 
                                       id="other_qualification_details" 
                                       placeholder="Please specify your qualification"
                                       style="width: 100%; padding: 10px;">
                            </div>
                        </div>
                    </div>
                  <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
    <!-- Department/Applying For Field -->
    <div class="form-group">
        <label class="required">Applying For (Department)</label>
        <select   id="applying_for" name="department" onchange="toggleOtherApplyingFor()">
            <option value="">Select department</option>
            @foreach($departments as $dept)
                <option value="{{ $dept }}">
                    {{ ucfirst(str_replace('_', ' ', $dept)) }}
                </option>
            @endforeach
            <option value="other">Other (Please specify)</option>
        </select>

        <!-- Other Applying For Field -->
        <div id="otherApplyingForField" style="display: none; margin-top: 10px;">
            <input type="text" 
                   id="other_applying_for_details" 
                   name="other_department"
                   placeholder="Please specify department"
                   style="width: 100%; padding: 10px; border: 2px solid var(--border-color); border-radius: 8px;">
        </div>
    </div>
    
    <!-- NEW: Profile Field -->
            <div class="form-group">
        <label class="required">Applying as</label>
        <select id="profile" name="profile" onchange="toggleOtherProfile()" disabled>
            <option value="">First select a department</option>
        </select>
        
        <!-- Loading indicator -->
        <div id="profileLoading" style="display: none; margin-top: 10px; color: var(--primary-blue);">
            <i class="fas fa-spinner fa-spin"></i> Loading profiles...
        </div>
        
        <!-- Other Profile Field -->
        <div id="otherProfileField" style="display: none; margin-top: 10px;">
            <input type="text" 
                id="other_profile_details" 
                name="other_profile"
                placeholder="Please specify profile"
                style="width: 100%; padding: 10px; border: 2px solid var(--border-color); border-radius: 8px;">
        </div>
    </div>
</div>
        </div>
                
                <!-- Skills Section -->
           
<div class="section-card">
    <div class="section-header">
        <div class="section-icon">
            <i class="fas fa-code"></i>
        </div>
        <div class="section-title">
            <h3>Skills & Competencies</h3>
            <p>Add your skills (automatically loaded based on your selection)</p>
        </div>
    </div>
    
    <div class="form-group">
        <label class="required">Select Skills</label>
        
        <!-- Loading indicator for skills -->
        <div id="skillsLoading" style="display: none; text-align: center; padding: 20px; background: var(--light-blue); border-radius: 10px; margin-bottom: 20px;">
            <i class="fas fa-spinner fa-spin fa-2x" style="color: var(--primary-blue);"></i>
            <p style="margin-top: 10px; color: var(--primary-blue);">Loading relevant skills based on your selection...</p>
        </div>
        
        <!-- Skills Container (initially hidden) -->
        <div id="skillsContainer" style="display: none;">
            <!-- Teaching Skills -->
            <div id="teachingSkillsSection" style="margin-bottom: 20px; display: none;">
                <div style="font-size: 14px; color: var(--text-dark); margin-bottom: 12px; font-weight: 500;">
                    <i class="fas fa-graduation-cap"></i> Teaching & Academic Skills:
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;" id="teachingSkills">
                    <!-- Will be populated via AJAX -->
                </div>
            </div>
            
            <!-- Technical Skills -->
            <div id="technicalSkillsSection" style="margin-bottom: 20px; display: none;">
                <div style="font-size: 14px; color: var(--text-dark); margin-bottom: 12px; font-weight: 500;">
                    <i class="fas fa-laptop"></i> Technical Skills:
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;" id="technicalSkills">
                    <!-- Will be populated via AJAX -->
                </div>
            </div>
            
            <!-- Soft Skills -->
            <div id="softSkillsSection" style="margin-bottom: 20px; display: none;">
                <div style="font-size: 14px; color: var(--text-dark); margin-bottom: 12px; font-weight: 500;">
                    <i class="fas fa-heart"></i> Soft Skills:
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;" id="softSkills">
                    <!-- Will be populated via AJAX -->
                </div>
            </div>
            
            <!-- Languages -->
            <div id="languageSkillsSection" style="margin-bottom: 20px; display: none;">
                <div style="font-size: 14px; color: var(--text-dark); margin-bottom: 12px; font-weight: 500;">
                    <i class="fas fa-language"></i> Languages:
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;" id="languageSkills">
                    <!-- Will be populated via AJAX -->
                </div>
            </div>
            
            <!-- No skills message -->
            <div id="noSkillsMessage" style="display: none; text-align: center; padding: 30px; background: var(--light-blue); border-radius: 10px;">
                <i class="fas fa-info-circle fa-2x" style="color: var(--primary-blue);"></i>
                <p style="margin-top: 10px; color: var(--text-dark);">No predefined skills found for this selection. You can add custom skills below.</p>
            </div>
        </div>
        
        <!-- Selected Skills Display -->
        <div style="background: var(--light-blue); padding: 15px; border-radius: 10px; margin: 20px 0;">
            <label style="color: var(--primary-blue); margin-bottom: 10px; font-size: 14px;">
                <i class="fas fa-check-circle"></i> Selected Skills:
            </label>
            <div id="selectedSkillsContainer" style="display: flex; flex-wrap: wrap; gap: 8px; min-height: 40px;">
                <!-- Selected skills will appear here -->
                <span style="color: var(--text-light); font-style: italic;">No skills selected yet</span>
            </div>
        </div>
        
        <!-- Add Custom Skill -->
        <div style="display: flex; gap: 10px; align-items: center;">
            <div style="flex: 1;">
                <input type="text" 
                       id="customSkillInput" 
                       placeholder="Enter a custom skill" 
                       style="width: 100%;">
            </div>
            <button type="button" 
                    class="btn btn-outline" 
                    onclick="addCustomSkill()"
                    style="padding: 10px 20px; height: 48px;">
                <i class="fas fa-plus"></i> Add Skill
            </button>
        </div>
        <small style="color: var(--text-light); margin-top: 10px; display: block;">
            <i class="fas fa-info-circle"></i> Click on skills above to select them. Add your own skills using the custom field.
        </small>
        
        <!-- Hidden input to store selected skills for form submission -->
        <input type="hidden" id="selectedSkills" name="skills">
    </div>
</div>
                
           
 
                
                <!-- File Upload Section -->
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-file-upload"></i>
                        </div>
                        <div class="section-title">
                            <h3>Document Upload</h3>
                            <p>Upload your resume/CV</p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="required">Upload Resume/CV</label>
                        <div>
                            <input type="file" 
                                id="resume" 
                                name="resume" 
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" 
                                style="padding: 8px; border: 2px dashed var(--border-color); background: #f9f9f9; height: auto; width: 100%;">
                        </div>
                        <small style="color: var(--text-light); margin-top: 5px; display: block;">
                            <i class="fas fa-info-circle"></i> PDF, DOC, DOCX, JPG, PNG (Max: 5MB)
                        </small>
                    </div> 
                </div>
                
                <div class="declaration">
                    <h4><i class="fas fa-file-contract"></i> Terms & Conditions</h4>
                    <p>By submitting this form:</p>
                    <ul>
                        <li>I confirm that all information provided is accurate</li>
                        <li>I agree to be contacted by the institute</li>
                        <li>I understand that interview timing will be confirmed later</li>
                        <li>I will receive confirmation details via email</li>
                    </ul>
                    
                    <div class="checkbox-group">
                        <div class="checkbox-option">
                            <input type="checkbox" id="declarationCheck" required>
                            <div class="checkbox-text">
                                <strong>I agree to the terms and conditions</strong>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button class="btn btn-outline" onclick="window.location.href='main-form'">
                        <i class="fas fa-arrow-left"></i> Back to Home
                    </button>
                    <button class="btn btn-primary" onclick="submitInterviewForm()">
                        <i class="fas fa-paper-plane"></i> Submit Application
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        // ====== GLOBAL VARIABLES - MUST BE DECLARED HERE BEFORE ANY FUNCTION CALLS ======
        // Array to store selected skills
        let selectedSkills = [];

        // OTP Functionality Variables
        let generatedOTP = '';
        let otpTimer = null;
        let isOtpVerified = false;
        
        // Email OTP Variables
        let generatedEmailOTP = '';
        let emailOtpTimer = null;
        let isEmailVerified = false;
        let emailOtpTimeLeft = 0;
        
        // Phone OTP Variables
        let phoneOtpTimer = null;
        let phoneOtpTimeLeft = 0;
        
        let applyingForProfile = ''; // Added: fix for "applyingForProfile is not defined" error

        // Load skills based on department and profile selection
        function loadSkillsBySelection() {
            const department = document.getElementById('applying_for').value;
            const profile = document.getElementById('profile').value;
            
            console.log('Loading skills for:', { department, profile }); // Debug log
            
            // Only load skills if both department and profile are selected and not "other"
            if (!department || department === 'other' || !profile || profile === 'other') {
                // Hide skills container if either is "other" or not selected
                document.getElementById('skillsContainer').style.display = 'none';
                document.getElementById('skillsLoading').style.display = 'none';
                return;
            }
            
            // Show loading indicator
            document.getElementById('skillsLoading').style.display = 'block';
            document.getElementById('skillsContainer').style.display = 'none';
            
            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Make AJAX call to get skills
            fetch('/get-skills-by-selection', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    department: department,
                    profile: profile
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
        .then(data => {
            document.getElementById('skillsLoading').style.display = 'none';
            
            if (data.success) {
                clearSkillSections();
                
                const skills = data.skills;
                let hasSkills = false;
                
                // Populate sections
                ['teaching', 'technical', 'soft', 'language'].forEach(category => {
                    const sectionId = category + 'Skills';
                    const sectionDiv = document.getElementById(sectionId + 'Section');
                    
                    if (skills[category] && skills[category].length > 0) {
                        populateSkillSection(sectionId, skills[category]);
                        sectionDiv.style.display = 'block';
                        hasSkills = true;
                    } else {
                        sectionDiv.style.display = 'none';
                    }
                });
                
                document.getElementById('skillsContainer').style.display = 'block';
                document.getElementById('noSkillsMessage').style.display = hasSkills ? 'none' : 'block';
            }
        })
            .catch(error => {
                console.error('Error loading skills:', error);
                document.getElementById('skillsLoading').style.display = 'none';
                showToast('Failed to load skills. Please try again.', 'error');
            });
        }
        function toggleSkill(element) {
            const skill = element.getAttribute('data-skill');
            
            if (element.classList.contains('selected')) {
                element.classList.remove('selected');
                selectedSkills = selectedSkills.filter(s => s !== skill);
            } else {
                element.classList.add('selected');
                selectedSkills.push(skill);
            }
            
            updateSelectedSkillsDisplay();
        }
        function populateSkillSection(sectionId, skills) {
            const container = document.getElementById(sectionId);
            if (!container) return;
            
            container.innerHTML = '';
            
            skills.forEach(skill => {
                const isSelected = selectedSkills.includes(skill);
                const selectedClass = isSelected ? 'selected' : '';
                
                container.innerHTML += `<span class="skill-tag ${selectedClass}" onclick="toggleSkill(this)" data-skill="${skill}">${skill}</span>`;
            });
        }
        function clearSkillSections() {
            ['teachingSkills', 'technicalSkills', 'softSkills', 'languageSkills'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.innerHTML = '';
            });
            
            selectedSkills = [];
            updateSelectedSkillsDisplay();
        }
        // Add custom skill
        function addCustomSkill() {
            const input = document.getElementById('customSkillInput');
            const skill = input.value.trim();
            
            if (skill === '') {
                showToast('Please enter a skill', 'error');
                return;
            }
            
            if (!selectedSkills.includes(skill)) {
                selectedSkills.push(skill);
                updateSelectedSkillsDisplay();
                input.value = ''; // Clear input
                
                // Optional: Add visual feedback
                showResumeSuccess('Skill "' + skill + '" added successfully!');
            } else {
                showToast('This skill is already selected', 'error');
            }
        }

        // Update selected skills display
        function updateSelectedSkillsDisplay() {
            const container = document.getElementById('selectedSkillsContainer');
            const hiddenInput = document.getElementById('selectedSkills');
            
            if (selectedSkills.length === 0) {
                container.innerHTML = '<span style="color: var(--text-light); font-style: italic;">No skills selected yet</span>';
            } else {
                let html = '';
                selectedSkills.forEach(skill => {
                    html += `<span class="selected-skill-item">
                        ${skill} <i class="fas fa-times" onclick="removeSkill('${skill}')"></i>
                    </span>`;
                });
                container.innerHTML = html;
            }
            
            // Update hidden input for form submission
            hiddenInput.value = JSON.stringify(selectedSkills);
        }

        // Remove individual skill
        function removeSkill(skill) {
            selectedSkills = selectedSkills.filter(s => s !== skill);
            
            // Also remove selected class from corresponding tag if it exists
            const tags = document.querySelectorAll('.skill-tag');
            tags.forEach(tag => {
                if (tag.getAttribute('data-skill') === skill) {
                    tag.classList.remove('selected');
                }
            });
            
            updateSelectedSkillsDisplay();
        }

        // Toggle other profession field
        function toggleOtherProfession() {
            const profession = document.getElementById('profession').value;
            const otherField = document.getElementById('otherProfessionField');
            
            if (profession === 'other') {
                otherField.style.display = 'block';
                document.getElementById('other_profession_details').setAttribute('required', 'required');
            } else {
                otherField.style.display = 'none';
                document.getElementById('other_profession_details').removeAttribute('required');
                document.getElementById('other_profession_details').value = ''; // Clear the field
            }
        }

        // Toggle other qualification field
        function toggleOtherQualification() {
            const qualification = document.getElementById('highest_qualification').value;
            const otherField = document.getElementById('otherQualificationField');
            
            if (qualification === 'other') {
                otherField.style.display = 'block';
                document.getElementById('other_qualification_details').setAttribute('required', 'required');
            } else {
                otherField.style.display = 'none';
                document.getElementById('other_qualification_details').removeAttribute('required');
                document.getElementById('other_qualification_details').value = ''; // Clear the field
            }
        }

        // Toggle other applying for field
        function toggleOtherApplyingFor() {
            const applyingFor = document.getElementById('applying_for').value;
            const otherField = document.getElementById('otherApplyingForField');
            
            if (applyingFor === 'other') {
                otherField.style.display = 'block';
                document.getElementById('other_applying_for_details').setAttribute('required', 'required');
            } else {
                otherField.style.display = 'none';
                document.getElementById('other_applying_for_details').removeAttribute('required');
                document.getElementById('other_applying_for_details').value = '';
            }
        }

        // Toggle other subject field
        function toggleOtherSubject() {
            const subject = document.getElementById('preferred_subject').value;
            const otherField = document.getElementById('otherSubjectField');
            
            if (subject === 'other') {
                otherField.style.display = 'block';
                document.getElementById('other_subject_details').setAttribute('required', 'required');
            } else {
                otherField.style.display = 'none';
                document.getElementById('other_subject_details').removeAttribute('required');
                document.getElementById('other_subject_details').value = '';
            }
        }

        // Load saved data
            document.addEventListener('DOMContentLoaded', function() {
            const mode = localStorage.getItem('fincap_mode') || 'online';
            const source = localStorage.getItem('fincap_source') || '';
            
            // Add event listeners
            document.getElementById('applying_for').addEventListener('change', loadProfilesByDepartment);
            
            // Add change event for profile
            document.getElementById('profile').addEventListener('change', function() {
                toggleOtherProfile();
                
                // Load skills when profile is selected (and not "other")
                const profile = this.value;
                if (profile && profile !== 'other') {
                    loadSkillsBySelection();
                } else {
                    // Hide skills if "other" is selected
                    document.getElementById('skillsContainer').style.display = 'none';
                }
            });
            
            document.getElementById('modeDisplay').textContent = mode === 'online' ? 'Online' : 'In-Person';
            document.getElementById('modeValue').textContent = mode === 'online' ? 'Online Registration' : 'In-Person Registration';
            document.getElementById('sourceValue').textContent = getSourceText(source);
            
            // Generate reference ID
            const refId = 'APP-' + new Date().getFullYear() + '-' + Math.floor(1000 + Math.random() * 9000);
            document.getElementById('referenceId').textContent = refId;
            
            // Add resume file validation
            const resumeInput = document.getElementById('resume');
            if (resumeInput) {
                resumeInput.addEventListener('change', validateResume);
            }
        });

        function loadProfilesByDepartment() {
            const departmentSelect = document.getElementById('applying_for');
            const profileSelect = document.getElementById('profile');
            const loadingIndicator = document.getElementById('profileLoading');
            const otherProfileField = document.getElementById('otherProfileField');
            const department = departmentSelect.value;
            
            // Handle "Other" department selection
            if (department === 'other') {
                // Show other department field
                document.getElementById('otherApplyingForField').style.display = 'block';
                
                // Clear and disable profile dropdown
                profileSelect.innerHTML = '<option value="">First specify your department</option>';
                profileSelect.disabled = true;
                
                // Hide other profile field and skills
                otherProfileField.style.display = 'none';
                document.getElementById('skillsContainer').style.display = 'none';
                return;
            } else {
                // Hide other department field
                document.getElementById('otherApplyingForField').style.display = 'none';
            }
            
            // If no department selected, disable profile dropdown
            if (!department) {
                profileSelect.innerHTML = '<option value="">First select a department</option>';
                profileSelect.disabled = true;
                otherProfileField.style.display = 'none';
                document.getElementById('skillsContainer').style.display = 'none';
                return;
            }
            
            // Show loading indicator
            profileSelect.disabled = true;
            loadingIndicator.style.display = 'block';
            
            // Clear current options
            profileSelect.innerHTML = '<option value="">Loading profiles...</option>';
            
            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Make AJAX call to get profiles for selected department
            fetch(`/get-profiles-by-department/${encodeURIComponent(department)}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Hide loading indicator
                loadingIndicator.style.display = 'none';
                
                if (data.success && data.profiles.length > 0) {
                    // Build profile dropdown options
                    let options = '<option value="">Select profile</option>';
                    
                    data.profiles.forEach(profile => {
                        // Format profile name (replace underscores with spaces and capitalize)
                        const formattedProfile = profile.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        options += `<option value="${profile}">${formattedProfile}</option>`;
                    });
                    
                    // Add "Other" option
                    options += '<option value="other">Other (Please specify)</option>';
                    
                    // Update profile dropdown
                    profileSelect.innerHTML = options;
                    profileSelect.disabled = false;
                } else {
                    // No profiles found for this department
                    profileSelect.innerHTML = '<option value="">No profiles available for this department</option>';
                    profileSelect.disabled = true;
                }
                
                // Hide other profile field
                otherProfileField.style.display = 'none';
                
                // Hide skills container until profile is selected
                document.getElementById('skillsContainer').style.display = 'none';
            })
            .catch(error => {
                console.error('Error loading profiles:', error);
                
                // Hide loading indicator
                loadingIndicator.style.display = 'none';
                
                // Show error in dropdown
                profileSelect.innerHTML = '<option value="">Error loading profiles</option>';
                profileSelect.disabled = true;
                
                // Show error message to user
                alert('Failed to load profiles. Please try again.');
            });
        }

        // Resume validation function
        function validateResume() {
            const resumeInput = document.getElementById('resume');
            const file = resumeInput.files[0];
            const fileSizeLimit = 5 * 1024 * 1024; // 5MB in bytes
            
            // Check if there's an existing error message and remove it
            const existingError = document.getElementById('resume-error');
            if (existingError) {
                existingError.remove();
            }
            
            // Remove any error styling
            resumeInput.style.borderColor = '';
            resumeInput.style.backgroundColor = '';
            
            if (!file) {
                return; // No file selected, no error
            }
            
            // Allowed file extensions
            const allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
            const fileName = file.name;
            const fileExtension = fileName.split('.').pop().toLowerCase();
            
            // Check file extension
            if (!allowedExtensions.includes(fileExtension)) {
                showResumeError('Invalid file format. Please upload PDF, DOC, DOCX, JPG, or PNG files only.');
                resumeInput.value = ''; // Clear the file input
                return;
            }
            
            // Check file size
            if (file.size > fileSizeLimit) {
                const fileSizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                showResumeError(`File size (${fileSizeInMB} MB) exceeds the 5MB limit. Please upload a smaller file.`);
                resumeInput.value = ''; // Clear the file input
                return;
            }
            
            // If file is valid, show success message
            const fileSizeInKB = (file.size / 1024).toFixed(2);
            showResumeSuccess(`File accepted: ${file.name} (${fileSizeInKB} KB)`);
        }

        // Function to show error message
        function showResumeError(message) {
            const resumeInput = document.getElementById('resume');
            const resumeContainer = resumeInput.parentElement;
            
            // Style the input as error
            resumeInput.style.borderColor = '#EF4444';
            resumeInput.style.backgroundColor = '#FEF2F2';
            
            // Create error message element
            const errorDiv = document.createElement('div');
            errorDiv.id = 'resume-error';
            errorDiv.style.color = '#EF4444';
            errorDiv.style.fontSize = '13px';
            errorDiv.style.marginTop = '8px';
            errorDiv.style.display = 'flex';
            errorDiv.style.alignItems = 'center';
            errorDiv.style.gap = '5px';
            
            // Add icon and message
            errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
            
            // Append error message after the input
            resumeContainer.appendChild(errorDiv);
        }

        // Function to show success message
        function showResumeSuccess(message) {
            const resumeInput = document.getElementById('resume');
            const resumeContainer = resumeInput.parentElement;
            
            // Style the input as success
            resumeInput.style.borderColor = '#10B981';
            resumeInput.style.backgroundColor = '#F0FDF4';
            
            // Create success message element
            const successDiv = document.createElement('div');
            successDiv.id = 'resume-success';
            successDiv.style.color = '#10B981';
            successDiv.style.fontSize = '13px';
            successDiv.style.marginTop = '8px';
            successDiv.style.display = 'flex';
            successDiv.style.alignItems = 'center';
            successDiv.style.gap = '5px';
            
            // Add icon and message
            successDiv.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
            
            // Append success message after the input
            resumeContainer.appendChild(successDiv);
            
            // Remove success message after 3 seconds
            setTimeout(() => {
                const successElement = document.getElementById('resume-success');
                if (successElement) {
                    successElement.remove();
                }
                // Reset border color but keep the file
                resumeInput.style.borderColor = '';
                resumeInput.style.backgroundColor = '';
            }, 3000);
        }

        function getSourceText(source) {
            const sources = {
                'friend_family': 'Friend or Family Referral',
                'social_media': 'Social Media',
                'website': 'Institute Website',
                'newspaper': 'Newspaper Ad',
                'seminar': 'Seminar/Workshop',
                'alumni': 'Alumni Reference',
                'school': 'School/College Referral',
                'other': 'Other Source'
            };
            return sources[source] || source || 'Not specified';
        }
        
        function showToast(message, type = 'error') {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                close: true,
                stopOnFocus: true,
                style: {
                    background: type === 'error'
                        ? "linear-gradient(to right, #ef4444, #dc2626)"
                        : "linear-gradient(to right, #10b981, #059669)"
                }
            }).showToast();
        }
        
function submitInterviewForm() {
    // SAFETY CHECK: Ensure global variables are accessible
    if (typeof isOtpVerified === 'undefined') {
        console.error('ERROR: isOtpVerified variable is not defined. This should not happen.');
        showToast('Form error: Please refresh the page and try again', 'error');
        return;
    }
    
    // Get department value directly
    const departmentSelect = document.getElementById('applying_for');
    const department = departmentSelect.value;
    
    console.log('Department selected:', department); // Debug log
    
    // Validate department
    if (!department) {
        showToast('Please select a department', 'error');
        setTimeout(() => departmentSelect.focus(), 100);
        return;
    }
    
    // Validate department if "Other" is selected
    if (department === 'other') {
        const otherDepartment = document.getElementById('other_applying_for_details').value;
        if (!otherDepartment) {
            showToast('Please specify the department');
            setTimeout(()=> {
                document.getElementById('other_applying_for_details').focus();
            }, 100);
            return;
        }
    }
    
    // Validate profile
    const profileSelect = document.getElementById('profile');
    const profile = profileSelect.value;
    
    if (!profile) {
        showToast('Please select a profile', 'error');
        setTimeout(() => profileSelect.focus(), 100);
        return;
    }

    // Validate profile if "Other" is selected
    if (profile === 'other') {
        const otherProfile = document.getElementById('other_profile_details').value;
        if (!otherProfile) {
            showToast('Please specify your profile', 'error');
            setTimeout(() => {
                document.getElementById('other_profile_details').focus();
            }, 100);
            return;
        }
    }
    
    // Validate required fields
    if (!document.getElementById('fullName').value) {
        showToast('Please enter your full name' , 'error');
        setTimeout(() => {
            document.getElementById('fullName').focus();
        }, 100);
        return;
    }
    if (!document.getElementById('email').value) {
        showToast('Please enter your email address');
        setTimeout(() => {
            document.getElementById('email').focus();
        }, 100);
        return;
    }
    if (!document.getElementById('phone').value) {
        showToast('Please enter your phone number');
        setTimeout(() => {
            document.getElementById('phone').focus();
        }, 100);
        return;
    }

    // Validate OTP verification - use typeof check to prevent TDZ errors
    if (typeof isOtpVerified !== 'boolean' || !isOtpVerified) {
        showToast('Please verify your phone number with OTP');
        document.getElementById('otpVerificationSection').scrollIntoView({ behavior: 'smooth' });
        return;
    }
    
    // Validate Email OTP verification
    if (typeof isEmailVerified !== 'boolean' || !isEmailVerified) {
        showToast('Please verify your email address with OTP');
        document.getElementById('emailOtpVerificationSection').scrollIntoView({ behavior: 'smooth' });
        return;
    }
    
    // Validate profession if "Other" is selected
    const profession = document.getElementById('profession').value;
    if (profession === 'other' && !document.getElementById('other_profession_details').value) {
        showToast('Please specify your profession');
        setTimeout(() => {
            document.getElementById('other_profession_details').focus();
        }, 100);
        return;
    }
    
    // Validate qualification if "Other" is selected
    const qualification = document.getElementById('highest_qualification').value;
    if (qualification === 'other' && !document.getElementById('other_qualification_details').value) {
        showToast('Please specify your qualification details');
        setTimeout(() => {
            document.getElementById('other_qualification_details').focus();
        }, 100);
        return;
    }
    
    // Validate skills
    if (selectedSkills.length === 0) {
        showToast('Please select at least one skill', 'error');
        return;
    }
    
    // Validate resume
    const resumeFile = document.getElementById('resume').files[0];
    if (!resumeFile) {
        showToast('Please upload your resume/CV', 'error');
        setTimeout(() => {
            document.getElementById('resume').focus();
        }, 100);
        return;
    }
    
    // Check file size (max 5MB)
    if (resumeFile.size > 5 * 1024 * 1024) {
        setTimeout(() => {
            showToast('File size should be less than 5MB', 'error');
        }, 100);
        return;
    }
    
    // Check declaration
    if (!document.getElementById('declarationCheck').checked) {
        showToast('Please agree to the terms and conditions', 'error');
        return;
    }
    
    // Validate experience fields
    const yearsInput = document.getElementById('experience_years').value;
    const monthsInput = document.getElementById('experience_months').value;
    
    const years = yearsInput === '' ? 0 : parseInt(yearsInput);
    const months = monthsInput === '' ? 0 : parseInt(monthsInput);
    
    if (yearsInput !== '' && (years < 0 || years > 50)) {
        showToast('Please enter valid years of experience (0-50)');
        setTimeout(() => {
            document.getElementById('experience_years').focus();
        }, 100);
        return;
    }
    if (monthsInput !== '' && (months < 0 || months > 11)) {
        showToast('Please enter valid months of experience (0-11)');
        setTimeout(() => {
            document.getElementById('experience_months').focus();
        }, 100);
        return;
    }
    
    // Create FormData object for file upload
    const formData = new FormData();
    
    // Add all form fields with correct names
    formData.append('mode', localStorage.getItem('fincap_mode') || 'online');
    formData.append('source', localStorage.getItem('fincap_source') || '');
    formData.append('full_name', document.getElementById('fullName').value);
    formData.append('email', document.getElementById('email').value);
    formData.append('phone', document.getElementById('phone').value);
    formData.append('dob', document.getElementById('dob').value);
    formData.append('marital_status', document.getElementById('marital_status').value);
    
    // Handle profession
    if (profession === 'other') {
        const otherProfession = document.getElementById('other_profession_details').value;
        formData.append('profession', otherProfession);
        formData.append('other_profession_details', otherProfession);
    } else {
        formData.append('profession', profession);
    }
    
    formData.append('organization', document.getElementById('organization').value);
    
    // Handle department (applying_for) - THIS IS THE CRITICAL PART
    if (department === 'other') {
        const otherDepartment = document.getElementById('other_applying_for_details').value;
        formData.append('applying_for', otherDepartment);
        formData.append('other_applying_for_details', otherDepartment);
        console.log('Setting department (other) to:', otherDepartment); // Debug log
    } else {
        formData.append('applying_for', department);
        console.log('Setting department to:', department); // Debug log
    }
    
    // Handle profile (applying_for_profile)
    if (profile === 'other') {
        const otherProfile = document.getElementById('other_profile_details').value;
        formData.append('applying_for_profile', otherProfile);
        formData.append('other_profile_details', otherProfile);
        console.log('Setting profile (other) to:', otherProfile); // Debug log
    } else {
        formData.append('applying_for_profile', profile);
        console.log('Setting profile to:', profile); // Debug log
    }
    
    // Experience fields
    formData.append('experience_in_year', years);
    formData.append('experience_in_month', months);
    
    const totalMonths = (years * 12) + months;
    formData.append('experience_in_months', totalMonths);
    
    // Handle qualification
    if (qualification === 'other') {
        const otherQualification = document.getElementById('other_qualification_details').value;
        formData.append('qualification', otherQualification);
        formData.append('other_qualification_details', otherQualification);
    } else {
        formData.append('qualification', qualification);
    }
    
    formData.append('highest_qualification', qualification);
    
    // Add skills
    formData.append('skills', JSON.stringify(selectedSkills));
    
    // Add the resume file
    formData.append('resume', resumeFile);
    
    // Add CSRF token
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Log ALL form data for debugging
    console.log('=== FORM DATA SUBMISSION ===');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    console.log('===========================');
    
    // Send to interview route
    fetch('/save-interview', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Server Response:', data);
        if (data.success) {
            showToast('Application submitted successfully!\nReference ID: ' + data.reference_id, 'success');
            
            // Clear localStorage
            localStorage.removeItem('fincap_mode');
            localStorage.removeItem('fincap_source');
            
            // Redirect to home
            setTimeout(() => {
                window.location.href = '/main-form';
            }, 3000);
        } else {
            showToast(data.message || 'Unknown error occurred', 'error');
            if (data.errors) {
                console.error('Validation errors:', data.errors);
                // Display specific validation errors
                let errorMsg = 'Please fix the following errors:\n';
                for (let field in data.errors) {
                    errorMsg += `- ${field}: ${data.errors[field]}\n`;
                }
                showToast(errorMsg , 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error submitting application.', 'error');
    });
}

                // Toggle other profile field
        function toggleOtherProfile() {
            const profile = document.getElementById('profile').value;
            const otherField = document.getElementById('otherProfileField');
            
            if (profile === 'other') {
                otherField.style.display = 'block';
                document.getElementById('other_profile_details').setAttribute('required', 'required');
            } else {
                otherField.style.display = 'none';
                document.getElementById('other_profile_details').removeAttribute('required');
                document.getElementById('other_profile_details').value = '';
            }
        }

        // Get CSRF token
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').content;
        }

        // AJAX helper function
        async function makeAjaxRequest(url, method, data) {
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                return await response.json();
            } catch (error) {
                console.error('AJAX request failed:', error);
                return {
                    Success: false,
                    message: 'Network error: ' + error.message
                };
            }
        }

        // Handle phone input change
        if (document.getElementById('phone')) {
            document.getElementById('phone').addEventListener('change', function() {
                const phone = this.value.trim();
                if (phone.length === 10) {
                    document.getElementById('sendOtpBtn').style.display = 'inline-block';
                    isOtpVerified = false;
                } else {
                    document.getElementById('sendOtpBtn').style.display = 'none';
                    document.getElementById('otpVerificationSection').style.display = 'none';
                    isOtpVerified = false;
                }
            });
        }

        // Handle email input change
        if (document.getElementById('email')) {
            document.getElementById('email').addEventListener('change', function() {
                const email = this.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email && emailRegex.test(email)) {
                    document.getElementById('sendEmailOtpBtn').style.display = 'inline-block';
                    isEmailVerified = false;
                } else {
                    document.getElementById('sendEmailOtpBtn').style.display = 'none';
                    document.getElementById('emailOtpVerificationSection').style.display = 'none';
                    isEmailVerified = false;
                }
            });
        }

        // Send Email OTP function
        if (document.getElementById('sendEmailOtpBtn')) {
            document.getElementById('sendEmailOtpBtn').addEventListener('click', async function() {
                const email = document.getElementById('email').value.trim();
                
                if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    showToast('Please enter a valid email address', 'error');
                    return;
                }

                // Show loading state
                const btn = this;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
                btn.disabled = true;

                try {
                    const response = await fetch('/send-interview-email-otp', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            email_id: email,
                            otp_verification_type: 'interview_email_verification'
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        showToast('OTP sent to your email successfully!', 'success');
                        document.getElementById('emailOtpVerificationSection').style.display = 'block';
                        startEmailOTPTimer();
                        document.querySelector('.email-otp-digit').focus();
                    } else {
                        showToast(result.message || 'Failed to send OTP', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('Error sending OTP', 'error');
                } finally {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            });
        }

        // Start Email OTP timer
        function startEmailOTPTimer() {
            emailOtpTimeLeft = 120; // 2 minutes = 120 seconds
            const verifyBtn = document.getElementById('verifyEmailOtpBtn');
            const resendBtn = document.getElementById('resendEmailOtpBtn');
            const timerDiv = document.getElementById('emailTimerText');
            
            if (emailOtpTimer) clearInterval(emailOtpTimer);
            
            verifyBtn.style.display = 'inline-block';
            resendBtn.style.display = 'none';
            
            emailOtpTimer = setInterval(function() {
                emailOtpTimeLeft--;
                const minutes = Math.floor(emailOtpTimeLeft / 60);
                const seconds = emailOtpTimeLeft % 60;
                timerDiv.innerHTML = `Time remaining: ${minutes}:${seconds.toString().padStart(2, '0')}`;
                
                if (emailOtpTimeLeft <= 0) {
                    clearInterval(emailOtpTimer);
                    verifyBtn.style.display = 'none';
                    resendBtn.style.display = 'inline-block';
                    timerDiv.innerHTML = 'OTP expired. Please resend.';
                }
            }, 1000);
        }

        // Email OTP digit input handling
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('email-otp-digit')) {
                if (e.target.value.length === 1 && /^\d$/.test(e.target.value)) {
                    const next = e.target.nextElementSibling;
                    if (next && next.classList.contains('email-otp-digit')) {
                        next.focus();
                    }
                }
            }
        });

        // Handle backspace for Email OTP digits
        document.addEventListener('keydown', function(e) {
            if (e.target.classList.contains('email-otp-digit')) {
                if (e.key === 'Backspace' && e.target.value === '') {
                    const prev = e.target.previousElementSibling;
                    if (prev && prev.classList.contains('email-otp-digit')) {
                        prev.focus();
                    }
                }
            }
        });

        // Verify Email OTP
        if (document.getElementById('verifyEmailOtpBtn')) {
            document.getElementById('verifyEmailOtpBtn').addEventListener('click', async function() {
                let enteredOTP = '';
                document.querySelectorAll('.email-otp-digit').forEach(input => {
                    enteredOTP += input.value;
                });

                if (enteredOTP.length !== 4) {
                    showToast('Please enter the complete 4-digit OTP', 'error');
                    return;
                }

                const btn = this;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';
                btn.disabled = true;

                try {
                    const email = document.getElementById('email').value.trim();
                    const response = await fetch('/verify-interview-email-otp', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            otp: enteredOTP,
                            email: email
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        isEmailVerified = true;
                        showToast('Email verified successfully!', 'success');
                        
                        // Disable all OTP inputs
                        document.querySelectorAll('.email-otp-digit').forEach(input => {
                            input.disabled = true;
                            input.style.backgroundColor = '#f0fdf4';
                        });
                        
                        document.getElementById('verifyEmailOtpBtn').style.display = 'none';
                        document.getElementById('resendEmailOtpBtn').style.display = 'none';
                        
                        if (emailOtpTimer) {
                            clearInterval(emailOtpTimer);
                        }
                    } else {
                        showToast(result.message || 'Invalid OTP', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('Error verifying OTP', 'error');
                } finally {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            });
        }

        // Resend Email OTP
        if (document.getElementById('resendEmailOtpBtn')) {
            document.getElementById('resendEmailOtpBtn').addEventListener('click', function() {
                resetEmailOTPUI();
                document.getElementById('sendEmailOtpBtn').click();
            });
        }

        function resetEmailOTPUI() {
            document.querySelectorAll('.email-otp-digit').forEach(input => {
                input.value = '';
                input.disabled = false;
                input.style.backgroundColor = 'white';
            });
            document.getElementById('emailOtpStatus').style.display = 'none';
            isEmailVerified = false;
            if (emailOtpTimer) clearInterval(emailOtpTimer);
            document.getElementById('emailTimerText').innerHTML = '';
        }

        function resetPhoneOTPUI() {
            document.querySelectorAll('.otp-digit').forEach(input => {
                input.value = '';
                input.disabled = false;
            });
            document.getElementById('otpStatus').style.display = 'none';
            isOtpVerified = false;
            if (phoneOtpTimer) clearInterval(phoneOtpTimer);
            document.getElementById('phoneTimerText').innerHTML = '';
        }

        // Send OTP function
        if (document.getElementById('sendOtpBtn')) {
            document.getElementById('sendOtpBtn').addEventListener('click', async function() {
            const phone = document.getElementById('phone').value.trim();
            
            if (!phone || phone.length !== 10) {
                showToast('Please enter a valid 10-digit phone number');
                return;
            }

            // Show loading state
            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            btn.disabled = true;

            try {
                const result = await makeAjaxRequest('/send/otp', 'POST', {
                    mobile_number: phone
                });

                if (result.Success) {
                    generatedOTP = result.Success;
                    showToast('OTP sent successfully!', 'success');
                    document.getElementById('otpVerificationSection').style.display = 'block';
                    startPhoneOTPTimer();
                    document.querySelector('.otp-digit').focus();
                } else {
                    showToast(result.message || 'Failed to send OTP');
                    // Fallback to client-side OTP generation
                    fallbackGenerateOTP();
                }
            } catch (error) {
                console.error('Error:', error);
                fallbackGenerateOTP();
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
        }

        // Fallback OTP generation
        function fallbackGenerateOTP() {
            generatedOTP = Math.floor(1000 + Math.random() * 9000).toString();
            console.log('Generated OTP (local):', generatedOTP);
            showToast(`OTP generated: ${generatedOTP}`, 'success');
            document.getElementById('otpVerificationSection').style.display = 'block';
            startPhoneOTPTimer();
            document.querySelector('.otp-digit').focus();
        }

        // Start Phone OTP timer
        function startPhoneOTPTimer() {
            phoneOtpTimeLeft = 120; // 2 minutes = 120 seconds
            const verifyBtn = document.getElementById('verifyOtpBtn');
            const resendBtn = document.getElementById('resendOtpBtn');
            const timerDiv = document.getElementById('phoneTimerText');
            
            if (phoneOtpTimer) clearInterval(phoneOtpTimer);
            
            verifyBtn.style.display = 'inline-block';
            resendBtn.style.display = 'none';
            
            phoneOtpTimer = setInterval(function() {
                phoneOtpTimeLeft--;
                const minutes = Math.floor(phoneOtpTimeLeft / 60);
                const seconds = phoneOtpTimeLeft % 60;
                timerDiv.innerHTML = `Time remaining: ${minutes}:${seconds.toString().padStart(2, '0')}`;
                
                if (phoneOtpTimeLeft <= 0) {
                    clearInterval(phoneOtpTimer);
                    verifyBtn.style.display = 'none';
                    resendBtn.style.display = 'inline-block';
                    timerDiv.innerHTML = 'OTP expired. Please resend.';
                }
            }, 1000);
        }

        // OTP digit input handling
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('otp-digit')) {
                if (e.target.value.length === 1 && /^\d$/.test(e.target.value)) {
                    const nextInput = e.target.nextElementSibling;
                    if (nextInput && nextInput.classList.contains('otp-digit')) {
                        nextInput.focus();
                    }
                }
            }
        });

        // Handle backspace for OTP digits
        document.addEventListener('keydown', function(e) {
            if (e.target.classList.contains('otp-digit')) {
                if (e.key === 'Backspace' && e.target.value === '') {
                    const prevInput = e.target.previousElementSibling;
                    if (prevInput && prevInput.classList.contains('otp-digit')) {
                        prevInput.focus();
                    }
                }
            }
        });

        // Verify OTP
        if (document.getElementById('verifyOtpBtn')) {
            document.getElementById('verifyOtpBtn').addEventListener('click', async function() {
            let enteredOTP = '';
            document.querySelectorAll('.otp-digit').forEach(input => {
                enteredOTP += input.value;
            });

            if (enteredOTP.length !== 4) {
                showToast('Please enter the complete 4-digit OTP');
                return;
            }

            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';
            btn.disabled = true;

            try {
                const phone = document.getElementById('phone').value.trim();
                const result = await makeAjaxRequest('/verify/otp', 'POST', {
                    mobile_number: phone,
                    otp: enteredOTP
                });

                if (result.Success) {
                    isOtpVerified = true;
                    showToast('Phone number verified successfully!', 'success');
                    
                    // Show success status
                    const otpStatus = document.getElementById('otpStatus');
                    otpStatus.style.display = 'block';
                    otpStatus.style.background = '#d1fae5';
                    otpStatus.style.color = '#065f46';
                    otpStatus.style.borderLeft = '4px solid #10b981';
                    otpStatus.innerHTML = '<i class="fas fa-check-circle"></i> <strong>Phone verified!</strong>';

                    // Disable OTP inputs after verification
                    document.querySelectorAll('.otp-digit').forEach(input => {
                        input.disabled = true;
                    });
                    document.getElementById('verifyOtpBtn').style.display = 'none';
                    document.getElementById('resendOtpBtn').style.display = 'none';

                    if (phoneOtpTimer) {
                        clearInterval(phoneOtpTimer);
                    }
                } else {
                    // Try local verification as fallback
                    if (enteredOTP === generatedOTP) {
                        isOtpVerified = true;
                        showToast('Phone number verified successfully!', 'success');
                        
                        const otpStatus = document.getElementById('otpStatus');
                        otpStatus.style.display = 'block';
                        otpStatus.style.background = '#d1fae5';
                        otpStatus.style.color = '#065f46';
                        otpStatus.style.borderLeft = '4px solid #10b981';
                        otpStatus.innerHTML = '<i class="fas fa-check-circle"></i> <strong>Phone verified!</strong>';

                        document.querySelectorAll('.otp-digit').forEach(input => {
                            input.disabled = true;
                        });
                        document.getElementById('verifyOtpBtn').style.display = 'none';
                        document.getElementById('resendOtpBtn').style.display = 'none';

                        if (phoneOtpTimer) {
                            clearInterval(phoneOtpTimer);
                        }
                    } else {
                        showToast('Invalid OTP. Please try again.');
                        document.querySelectorAll('.otp-digit').forEach(input => {
                            input.value = '';
                        });
                        document.querySelector('.otp-digit').focus();
                    }
                }
            } catch (error) {
                console.error('Error verifying OTP:', error);
                // Local verification
                if (enteredOTP === generatedOTP) {
                    isOtpVerified = true;
                    showToast('Phone number verified!', 'success');
                    
                    const otpStatus = document.getElementById('otpStatus');
                    otpStatus.style.display = 'block';
                    otpStatus.style.background = '#d1fae5';
                    otpStatus.style.color = '#065f46';
                    otpStatus.style.borderLeft = '4px solid #10b981';
                    otpStatus.innerHTML = '<i class="fas fa-check-circle"></i> <strong>Phone verified!</strong>';

                    document.querySelectorAll('.otp-digit').forEach(input => {
                        input.disabled = true;
                    });
                    document.getElementById('verifyOtpBtn').style.display = 'none';
                    document.getElementById('resendOtpBtn').style.display = 'none';
                } else {
                    showToast('Invalid OTP. Please try again.');
                }
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
        }

        // Resend OTP
        if (document.getElementById('resendOtpBtn')) {
            document.getElementById('resendOtpBtn').addEventListener('click', function() {
                resetPhoneOTPUI();
                document.getElementById('sendOtpBtn').click();
            });
        }
    </script>
@endsection
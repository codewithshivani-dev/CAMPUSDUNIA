<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Candidate Application Form</title>
    <!-- Font Awesome 6 (free CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Toastify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #eef2f7 0%, #dce5ef 100%);
            font-family: 'Inter', sans-serif;
            padding: 2rem 1.5rem;
            color: #1e2f3f;
        }

        .form-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 2rem;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .form-header {
            background: #0f2b3d;
            padding: 1.8rem 2rem;
            color: white;
        }

        .form-header h1 {
            font-weight: 700;
            font-size: 1.9rem;
            letter-spacing: -0.3px;
            margin-bottom: 0.3rem;
        }

        .form-header h1 i {
            margin-right: 10px;
            color: #6abf9e;
        }

        .form-header p {
            opacity: 0.8;
            font-size: 0.95rem;
            margin-top: 0.4rem;
        }

        form {
            padding: 2rem 2rem 2rem 2rem;
        }

        .two-cols {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .full-width {
            grid-column: span 2;
        }

        .form-section {
            margin-bottom: 2rem;
            border-bottom: 1px solid #e4edf2;
            padding-bottom: 1.2rem;
        }

        .section-title {
            font-size: 1.35rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            color: #0f2b3d;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            border-left: 4px solid #2b7a62;
            padding-left: 0.8rem;
        }

        .section-title i {
            color: #2b7a62;
            font-size: 1.3rem;
            width: 1.6rem;
        }

        .input-group {
            margin-bottom: 1.2rem;
            display: flex;
            flex-direction: column;
        }

        .input-group label {
            font-weight: 500;
            font-size: 0.85rem;
            margin-bottom: 0.4rem;
            color: #2c4b66;
            letter-spacing: -0.2px;
        }

        .input-group label i {
            margin-right: 6px;
            color: #2b7a62;
            font-size: 0.8rem;
            width: 1.2rem;
        }

        .input-group input,
        .input-group select,
        .input-group textarea {
            padding: 0.75rem 1rem;
            border: 1.5px solid #dde6ed;
            border-radius: 1rem;
            font-family: 'Inter', monospace;
            font-size: 0.9rem;
            background-color: #fff;
            transition: 0.2s;
            outline: none;
        }

        .input-group input:focus,
        .input-group select:focus,
        .input-group textarea:focus {
            border-color: #2b7a62;
            box-shadow: 0 0 0 3px rgba(43, 122, 98, 0.2);
        }

        .exp-row {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .exp-box {
            flex: 1;
        }

        .exp-box input {
            width: 100%;
        }

        .skills-area {
            background: #fafdff;
            border-radius: 1.2rem;
            border: 1px solid #e2edf5;
            padding: 1rem 1.2rem;
        }

        .skill-tag {
            display: inline-block;
            padding: 8px 16px;
            background: white;
            border: 2px solid #cde0ea;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 500;
            color: #1f5068;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
            margin: 0 5px 5px 0;
        }

        .skill-tag:hover {
            border-color: #2b7a62;
            background: #eef2f7;
            transform: translateY(-1px);
        }

        .skill-tag.selected {
            background: #2b7a62;
            border-color: #1f5e4b;
            color: white;
        }

        .selected-skill-item {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px 6px 16px;
            background: #2b7a62;
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
            border: 2px solid #cde0ea;
            color: #1e2f3f;
            padding: 10px 20px;
            height: 48px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-outline:hover {
            border-color: #2b7a62;
            background: #eef2f7;
        }

        .file-upload {
            border: 1.5px dashed #bdd4e2;
            border-radius: 1.2rem;
            padding: 0.8rem;
            text-align: center;
            background: #fafeff;
        }

        .file-upload i {
            font-size: 1.8rem;
            color: #6f8eaa;
            margin-bottom: 6px;
        }

        .submit-btn {
            background: #0f2b3d;
            color: white;
            font-weight: 700;
            padding: 0.9rem 1.8rem;
            border: none;
            border-radius: 3rem;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            font-family: 'Inter', sans-serif;
            letter-spacing: 0.3px;
            transition: 0.2s;
        }

        .submit-btn:hover {
            background: #1b4a62;
            transform: scale(0.99);
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .inline-note {
            font-size: 0.7rem;
            color: #6f8eaa;
            margin-top: 0.3rem;
        }

        @media (max-width: 720px) {
            .two-cols {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .full-width {
                grid-column: span 1;
            }

            form {
                padding: 1.5rem;
            }
        }

        footer {
            text-align: center;
            font-size: 0.7rem;
            padding: 1rem;
            color: #6f8eaa;
            background: #fafdff;
            border-top: 1px solid #e4edf2;
        }

        footer i {
            margin: 0 3px;
        }

        /* OTP Verification Styles */
        .otp-verification-section {
            background: #f0f7ff;
            border: 1.5px solid #dbeafe;
            border-radius: 1.2rem;
            padding: 1rem 1.2rem;
            margin-top: 0.75rem;
            margin-bottom: 0.75rem;
            display: none;
            width: 100%;
        }

        .otp-verification-section h3 {
            font-size: 0.95rem;
            color: #0f2b3d;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-weight: 600;
        }

        .otp-verification-section h3 i {
            color: #2b7a62;
            font-size: 1rem;
        }

        .otp-inputs {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            margin-bottom: 0.6rem;
            flex-wrap: wrap;
        }

        .otp-digit {
            width: 42px;
            height: 42px;
            font-size: 1.35rem;
            text-align: center;
            border: 2px solid #dbeafe;
            border-radius: 0.6rem;
            font-weight: bold;
            color: #0f2b3d;
            transition: all 0.3s ease;
        }

        .otp-digit:focus {
            border-color: #2b7a62;
            box-shadow: 0 0 0 3px rgba(43, 122, 98, 0.1);
            outline: none;
        }

        .otp-digit:disabled {
            background-color: #f0f7ff;
            cursor: not-allowed;
        }

        .otp-status {
            display: none;
            padding: 0.45rem 0.7rem;
            border-radius: 0.6rem;
            margin: 0.45rem 0;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .otp-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .otp-btn {
            padding: 0.45rem 0.8rem;
            border: none;
            border-radius: 0.6rem;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .otp-btn-verify {
            background: #2b7a62;
            color: white;
            display: none;
        }

        .otp-btn-verify:hover {
            background: #1f5e4b;
        }

        .otp-btn-resend {
            background: #f59e0b;
            color: white;
            display: none;
        }

        .otp-btn-resend:hover {
            background: #d97706;
        }

        .otp-send-btn {
            background: #2b7a62;
            color: white;
            padding: 0.6rem 1rem;
            border: none;
            border-radius: 0.6rem;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            margin-left: 0.5rem;
            display: none;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .otp-send-btn:hover {
            background: #1f5e4b;
        }

        .otp-send-btn:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
        }

        /* Success Message Styles */
        .success-message {
            display: none;
            background: #d1fae5;
            border: 2px solid #10b981;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-top: 1.5rem;
            text-align: center;
            animation: slideUp 0.5s ease;
        }

        .success-message i {
            font-size: 3rem;
            color: #10b981;
            margin-bottom: 0.5rem;
        }

        .success-message h2 {
            color: #065f46;
            margin-bottom: 0.5rem;
        }

        .success-message p {
            color: #065f46;
            font-size: 1rem;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="form-container">
        <div class="form-header">
            <h1><i class="fas fa-file-alt"></i> Candidate Application</h1>
            <p><i class="fas fa-info-circle"></i> Please fill in all required fields (*)</p>
        </div>

        <form id="jobApplicationForm" method="POST" enctype="multipart/form-data">
            <!-- Personal Information Section -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-user-circle"></i> Personal Information
                </div>
                <div class="two-cols">
                    <div class="input-group">
                        <label><i class="fas fa-user"></i> Full Name *</label>
                        <input type="text" id="fullName" placeholder="e.g. Ananya Sharma" required>
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-envelope"></i> Email Address *</label>
                        <div style="display: flex; gap: 0.8rem; align-items: flex-end;">
                            <input type="email" id="email" placeholder="your.email@example.com" required
                                style="flex: 1;">
                            <button type="button" id="sendEmailOtpBtn" class="otp-send-btn">
                                <i class="fas fa-envelope"></i> Email OTP
                            </button>
                        </div>
                    </div>

                    <!-- Email OTP Verification Section -->
                    <div id="emailOtpVerificationSection" class="otp-verification-section"
                        style="grid-column: 1 / -1; display: none; background: #f0fdf4; border-color: #22c55e;">
                        <h3 style="color: #2b7a62;">
                            <i class="fas fa-envelope"></i> Verify Email Address
                        </h3>
                        <p style="font-size: 0.85rem; color: #475569; margin-bottom: 1rem;">
                            Enter the 4-digit OTP sent to your email address
                        </p>
                        <div class="otp-inputs">
                            <input type="text" class="email-otp-digit" maxlength="1" inputmode="numeric" />
                            <input type="text" class="email-otp-digit" maxlength="1" inputmode="numeric" />
                            <input type="text" class="email-otp-digit" maxlength="1" inputmode="numeric" />
                            <input type="text" class="email-otp-digit" maxlength="1" inputmode="numeric" />
                        </div>
                        <div id="emailOtpStatus" class="otp-status"></div>
                        <div class="otp-buttons">
                            <button type="button" id="verifyEmailOtpBtn" class="otp-btn otp-btn-verify">
                                <i class="fas fa-check"></i> Verify Email
                            </button>
                            <button type="button" id="resendEmailOtpBtn" class="otp-btn otp-btn-resend"
                                style="display: none;">
                                <i class="fas fa-redo"></i> Resend OTP
                            </button>
                        </div>
                    </div>

                    <div class="input-group">
                        <label><i class="fas fa-phone-alt"></i> Phone Number *</label>
                        <div style="display: flex; gap: 0.8rem; align-items: flex-end;">
                            <input type="tel" id="phone" placeholder="9876543210" required style="flex: 1;">
                            <button type="button" id="sendOtpBtn" class="otp-send-btn">
                                <i class="fas fa-sms"></i> Send OTP
                            </button>
                        </div>
                    </div>

                    <!-- OTP Verification Section -->
                    <div id="otpVerificationSection" class="otp-verification-section" style="grid-column: 1 / -1;">
                        <h3>
                            <i class="fas fa-shield-alt"></i> Verify Phone Number
                        </h3>
                        <p style="font-size: 0.85rem; color: #475569; margin-bottom: 1rem;">
                            Enter the 4-digit OTP sent to your phone number
                        </p>
                        <div class="otp-inputs">
                            <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" />
                            <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" />
                            <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" />
                            <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" />
                        </div>
                        <div id="otpStatus" class="otp-status"></div>
                        <div class="otp-buttons">
                            <button type="button" id="verifyOtpBtn" class="otp-btn otp-btn-verify">
                                <i class="fas fa-check"></i> Verify OTP
                            </button>
                            <button type="button" id="resendOtpBtn" class="otp-btn otp-btn-resend">
                                <i class="fas fa-redo"></i> Resend OTP
                            </button>
                        </div>
                    </div>

                    <div class="input-group">
                        <label><i class="fas fa-calendar-alt"></i> Date of Birth *</label>
                        <input type="date" id="dob" required>
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-heart"></i> Marital Status *</label>
                        <select id="maritalStatus" required>
                            <option value="" disabled selected>Select marital status</option>
                            <option>Single</option>
                            <option>Married</option>
                            <option>Divorced</option>
                            <option>Widowed</option>
                            <option>Prefer not to say</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-briefcase"></i> Professional Information
                </div>
                <div class="two-cols">
                    <div class="input-group">
                        <label><i class="fas fa-chalkboard-user"></i> Current Profession *</label>
                        <select id="profession" required>
                            <option value="" disabled selected>Select profession</option>
                            <option>Teacher / Educator</option>
                            <option>Administrator</option>
                            <option>IT Professional</option>
                            <option>Marketing Specialist</option>
                            <option>HR Professional</option>
                            <option>Student / Fresher</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-building"></i> Current Organization</label>
                        <input type="text" id="organization" placeholder="School / Company name">
                    </div>
                    <div class="input-group full-width">
                        <label><i class="fas fa-clock"></i> Total Experience *</label>
                        <div class="exp-row">
                            <div class="exp-box">
                                <input type="number" id="expYears" placeholder="Years" min="0" max="50" value="0"
                                    step="1">
                                <div class="inline-note">Years</div>
                            </div>
                            <div class="exp-box">
                                <input type="number" id="expMonths" placeholder="Months" min="0" max="11" value="0"
                                    step="1">
                                <div class="inline-note">Months</div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-graduation-cap"></i> Highest Qualification *</label>
                        <select id="qualification" required>
                            <option value="" disabled selected>Select qualification</option>
                            <option>High School</option>
                            <option>Bachelor's Degree</option>
                            <option>Master's Degree</option>
                            <option>Doctorate (PhD)</option>
                            <option>Diploma / Certification</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-building-columns"></i> Applying For (Department) *</label>
                        <select id="applying_for" name="applying_for" required onchange="toggleOtherDepartment()">
                            <option value="" disabled selected>Select department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department }}">{{ $department }}</option>
                            @endforeach
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <!-- Other Department Field -->
                    <div id="otherDepartmentField" style="display: none; margin-top: 10px;" class="input-group">
                        <label><i class="fas fa-building"></i> Specify Other Department *</label>
                        <input type="text" id="other_department_details" name="other_department_details"
                            placeholder="Enter department name">
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-id-card"></i> Applying as (Profile) *</label>
                        <select id="applying_for_profile" name="applying_for_profile" required disabled
                            onchange="toggleOtherProfile()">
                            <option value="" selected>First select a department</option>
                        </select>
                    </div>
                    <!-- Other Profile Field -->
                    <div id="otherProfileField" style="display: none; margin-top: 10px;" class="input-group">
                        <label><i class="fas fa-user-tag"></i> Specify Other Profile *</label>
                        <input type="text" id="other_profile_details" name="other_profile_details"
                            placeholder="Enter profile/role name">
                    </div>
                </div>
            </div>

            <!-- Skills & Competencies -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-brain"></i> Skills & Competencies
                </div>
                <div class="skills-area">
                    <label style="font-weight:500;"><i class="fas fa-hand-pointer"></i> Select Skills </label>

                    <!-- Loading indicator for skills -->
                    <div id="skillsLoading"
                        style="display: none; text-align: center; padding: 20px; background: #eef2f7; border-radius: 10px; margin-bottom: 20px;">
                        <i class="fas fa-spinner fa-spin fa-2x" style="color: #2b7a62;"></i>
                        <p style="margin-top: 10px; color: #2b7a62;">Loading relevant skills based on your selection...
                        </p>
                    </div>

                    <!-- Skills Container -->
                    <div id="skillsContainer" style="display: none;">
                        <div id="teachingSkillsSection" style="margin-bottom: 20px; display: none;">
                            <div style="font-size: 14px; color: #1e2f3f; margin-bottom: 12px; font-weight: 500;">
                                <i class="fas fa-graduation-cap"></i> Teaching & Academic Skills:
                            </div>
                            <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;"
                                id="teachingSkills"></div>
                        </div>

                        <div id="technicalSkillsSection" style="margin-bottom: 20px; display: none;">
                            <div style="font-size: 14px; color: #1e2f3f; margin-bottom: 12px; font-weight: 500;">
                                <i class="fas fa-laptop"></i> Technical Skills:
                            </div>
                            <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;"
                                id="technicalSkills"></div>
                        </div>

                        <div id="softSkillsSection" style="margin-bottom: 20px; display: none;">
                            <div style="font-size: 14px; color: #1e2f3f; margin-bottom: 12px; font-weight: 500;">
                                <i class="fas fa-heart"></i> Soft Skills:
                            </div>
                            <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;"
                                id="softSkills"></div>
                        </div>

                        <div id="languageSkillsSection" style="margin-bottom: 20px; display: none;">
                            <div style="font-size: 14px; color: #1e2f3f; margin-bottom: 12px; font-weight: 500;">
                                <i class="fas fa-language"></i> Languages:
                            </div>
                            <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;"
                                id="languageSkills"></div>
                        </div>

                        <div id="noSkillsMessage"
                            style="display: none; text-align: center; padding: 30px; background: #eef2f7; border-radius: 10px;">
                            <i class="fas fa-info-circle fa-2x" style="color: #2b7a62;"></i>
                            <p style="margin-top: 10px; color: #1e2f3f;">No predefined skills found. Add custom skills
                                below.</p>
                        </div>
                    </div>

                    <!-- Selected Skills Display -->
                    <div style="background: #eef2f7; padding: 15px; border-radius: 10px; margin: 20px 0;">
                        <label style="color: #2b7a62; margin-bottom: 10px; font-size: 14px;">
                            <i class="fas fa-check-circle"></i> Selected Skills:
                        </label>
                        <div id="selectedSkillsContainer"
                            style="display: flex; flex-wrap: wrap; gap: 8px; min-height: 40px;">
                            <span style="color: #6f8eaa; font-style: italic;">No skills selected yet</span>
                        </div>
                    </div>

                    <!-- Add Custom Skill -->
                    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <input type="text" id="customSkillInput" placeholder="Enter a custom skill"
                                style="width: 100%; padding: 12px 16px; border: 1.5px solid #dde6ed; border-radius: 1rem; font-size: 0.9rem;">
                        </div>
                        <button type="button" class="btn-outline" onclick="addCustomSkill()"
                            style="padding: 10px 20px; height: 48px;">
                            <i class="fas fa-plus"></i> Add Skill
                        </button>
                    </div>
                    <small style="color: #6f8eaa; margin-top: 10px; display: block;">
                        <i class="fas fa-info-circle"></i> Click on skills above to select them.
                    </small>

                    <input type="hidden" id="selectedSkills" name="skills">
                </div>
            </div>

            <!-- Document Upload -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-paperclip"></i> Document Upload
                </div>
                <div class="file-upload">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <label style="font-weight:500; display:block;">Upload Resume/CV *</label>
                    <input type="file" id="resumeFile" accept=".pdf,.doc,.docx,.jpg,.png" style="margin-top: 8px;">
                    <div class="inline-note"><i class="fas fa-file-pdf"></i> PDF, DOC, DOCX, JPG, PNG (Max: 5MB)</div>
                </div>
            </div>

            <!-- Success Message (initially hidden) -->
            <div id="successMessage" class="success-message">
                <i class="fas fa-check-circle"></i>
                <h2>✅ Application Submitted Successfully!</h2>
                <p>Thank you for submitting your application. Our team will review it and contact you soon.</p>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn"><i class="fas fa-paper-plane"></i> Submit
                Application</button>
        </form>

        <footer>
            <i class="fas fa-envelope"></i> After submission, you'll receive a confirmation email. <i
                class="fas fa-lock"></i> Your data is secure.
        </footer>
    </div>

    <!-- Toastify JS -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
        // ========================
        // GLOBAL VARIABLES
        // ========================

        let selectedSkills = [];
        let generatedOTP = '';
        let otpTimer = null;
        let isOtpVerified = false;
        let generatedEmailOTP = '';
        let emailOtpTimer = null;
        let isEmailVerified = false;

        // ========================
        // CSRF TOKEN HELPER
        // ========================

        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').content;
        }

        // ========================
        // TOAST NOTIFICATION
        // ========================

        function showToast(message, type = 'info') {
            const colors = {
                success: '#10b981',
                error: '#ef4444',
                info: '#3b82f6',
                warning: '#f59e0b'
            };

            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: colors[type] || colors.info,
                style: {
                    borderRadius: '12px',
                    padding: '12px 20px',
                    fontSize: '14px',
                    fontWeight: '500'
                }
            }).showToast();
        }

        // ========================
        // SKILLS FUNCTIONALITY
        // ========================

        document.getElementById('applying_for').addEventListener('change', function () {
            loadProfilesByDepartment();
            loadSkillsBySelection();
            toggleOtherDepartment();
        });

        document.getElementById('applying_for_profile').addEventListener('change', function () {
            loadSkillsBySelection();
            toggleOtherProfile();
        });

        function loadProfilesByDepartment() {
            const department = document.getElementById('applying_for').value;

            if (!department || department === 'other') {
                document.getElementById('applying_for_profile').innerHTML = '<option value="">First select a department</option>';
                document.getElementById('applying_for_profile').disabled = true;
                return;
            }

            const token = getCsrfToken();

            fetch(`/get-profiles-by-department/${encodeURIComponent(department)}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    const profileSelect = document.getElementById('applying_for_profile');
                    profileSelect.innerHTML = '<option value="" disabled selected>Select profile</option>';

                    const profiles = data.profiles || [];

                    if (profiles.length > 0) {
                        profiles.forEach(profile => {
                            const option = document.createElement('option');
                            option.value = profile;
                            option.textContent = profile;
                            profileSelect.appendChild(option);
                        });
                        profileSelect.innerHTML += '<option value="other">Other</option>';
                    } else {
                        profileSelect.innerHTML += '<option value="" disabled>No profiles available</option>';
                    }

                    profileSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error loading profiles:', error);
                    showToast('Failed to load profiles', 'error');
                });
        }

        function loadSkillsBySelection() {
            const department = document.getElementById('applying_for').value;
            const profile = document.getElementById('applying_for_profile').value;

            if (!department || department === 'other' || !profile || profile === 'other') {
                document.getElementById('skillsContainer').style.display = 'none';
                document.getElementById('skillsLoading').style.display = 'none';
                return;
            }

            document.getElementById('skillsLoading').style.display = 'block';
            document.getElementById('skillsContainer').style.display = 'none';

            const token = getCsrfToken();

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
                .then(response => response.json())
                .then(data => {
                    document.getElementById('skillsLoading').style.display = 'none';

                    if (data.success) {
                        clearSkillSections();

                        const skills = data.skills;
                        let hasSkills = false;

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
                    showToast('Failed to load skills', 'error');
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
        }

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
                input.value = '';
                showToast('Skill added!', 'success');
            } else {
                showToast('Skill already selected', 'error');
            }
        }

        function updateSelectedSkillsDisplay() {
            const container = document.getElementById('selectedSkillsContainer');
            const hiddenInput = document.getElementById('selectedSkills');

            if (selectedSkills.length === 0) {
                container.innerHTML = '<span style="color: #6f8eaa; font-style: italic;">No skills selected yet</span>';
            } else {
                let html = '';
                selectedSkills.forEach(skill => {
                    html += `<span class="selected-skill-item">
                    ${skill} <i class="fas fa-times" onclick="removeSkill('${skill}')"></i>
                </span>`;
                });
                container.innerHTML = html;
            }

            hiddenInput.value = JSON.stringify(selectedSkills);
        }

        function removeSkill(skill) {
            selectedSkills = selectedSkills.filter(s => s !== skill);

            const tags = document.querySelectorAll('.skill-tag');
            tags.forEach(tag => {
                if (tag.getAttribute('data-skill') === skill) {
                    tag.classList.remove('selected');
                }
            });

            updateSelectedSkillsDisplay();
        }

        function toggleOtherDepartment() {
            const department = document.getElementById('applying_for').value;
            const otherField = document.getElementById('otherDepartmentField');

            if (department === 'other') {
                otherField.style.display = 'block';
                document.getElementById('other_department_details').setAttribute('required', 'required');
            } else {
                otherField.style.display = 'none';
                document.getElementById('other_department_details').removeAttribute('required');
                document.getElementById('other_department_details').value = '';
            }
        }

        function toggleOtherProfile() {
            const profile = document.getElementById('applying_for_profile').value;
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

        // ========================
        // OTP FUNCTIONALITY
        // ========================

        document.getElementById('phone').addEventListener('input', function () {
            const phone = this.value.trim();
            if (phone.length === 10 && /^\d{10}$/.test(phone)) {
                document.getElementById('sendOtpBtn').style.display = 'inline-block';
                isOtpVerified = false;
            } else {
                document.getElementById('sendOtpBtn').style.display = 'none';
                document.getElementById('otpVerificationSection').style.display = 'none';
                isOtpVerified = false;
            }
        });

        document.getElementById('email').addEventListener('input', function () {
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

        document.getElementById('sendEmailOtpBtn').addEventListener('click', async function () {
            const email = document.getElementById('email').value.trim();

            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showToast('Please enter a valid email address', 'error');
                return;
            }

            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            btn.disabled = true;

            try {
                const response = await fetch('/send-interview-email-otp', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        email_id: email,
                        otp_verification_type: 'interview_email_verification'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showToast('OTP sent to your email!', 'success');
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

        function startEmailOTPTimer() {
            let timeLeft = 300;
            document.getElementById('verifyEmailOtpBtn').style.display = 'inline-flex';
            document.getElementById('resendEmailOtpBtn').style.display = 'none';

            if (emailOtpTimer) {
                clearInterval(emailOtpTimer);
            }

            emailOtpTimer = setInterval(function () {
                timeLeft--;
                if (timeLeft <= 0) {
                    clearInterval(emailOtpTimer);
                    document.getElementById('verifyEmailOtpBtn').style.display = 'none';
                    document.getElementById('resendEmailOtpBtn').style.display = 'inline-flex';
                    showToast('OTP expired. Request a new one.', 'error');
                }
            }, 1000);
        }

        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('email-otp-digit')) {
                if (e.target.value.length === 1 && /^\d$/.test(e.target.value)) {
                    const next = e.target.nextElementSibling;
                    if (next && next.classList.contains('email-otp-digit')) {
                        next.focus();
                    }
                }
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.target.classList.contains('email-otp-digit')) {
                if (e.key === 'Backspace' && e.target.value === '') {
                    const prev = e.target.previousElementSibling;
                    if (prev && prev.classList.contains('email-otp-digit')) {
                        prev.focus();
                    }
                }
            }
        });

        document.getElementById('verifyEmailOtpBtn').addEventListener('click', async function () {
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
                        'X-CSRF-TOKEN': getCsrfToken(),
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
                    showToast('Email verified!', 'success');

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

        document.getElementById('resendEmailOtpBtn').addEventListener('click', function () {
            document.getElementById('sendEmailOtpBtn').click();
            document.querySelectorAll('.email-otp-digit').forEach(input => {
                input.value = '';
                input.disabled = false;
                input.style.backgroundColor = 'white';
            });
            document.getElementById('emailOtpStatus').style.display = 'none';
            isEmailVerified = false;
        });

        document.getElementById('sendOtpBtn').addEventListener('click', async function () {
            const phone = document.getElementById('phone').value.trim();

            if (!phone || phone.length !== 10 || !/^\d{10}$/.test(phone)) {
                showToast('Please enter a valid 10-digit phone number', 'error');
                return;
            }

            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            btn.disabled = true;

            try {
                const response = await fetch('/send/otp', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        mobile_number: phone
                    })
                });

                const result = await response.json();

                if (result.Success) {
                    generatedOTP = result.Success;
                    showToast('OTP sent successfully!', 'success');
                    document.getElementById('otpVerificationSection').style.display = 'block';
                    startOTPTimer();
                    document.querySelector('.otp-digit').focus();
                } else {
                    showToast(result.message || 'Failed to send OTP', 'error');
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

        function fallbackGenerateOTP() {
            generatedOTP = Math.floor(1000 + Math.random() * 9000).toString();
            console.log('Generated OTP (local):', generatedOTP);
            showToast(`OTP generated: ${generatedOTP}`, 'success');
            document.getElementById('otpVerificationSection').style.display = 'block';
            startOTPTimer();
            document.querySelector('.otp-digit').focus();
        }

        function startOTPTimer() {
            let timeLeft = 300;
            document.getElementById('verifyOtpBtn').style.display = 'inline-flex';
            document.getElementById('resendOtpBtn').style.display = 'none';

            if (otpTimer) {
                clearInterval(otpTimer);
            }

            otpTimer = setInterval(function () {
                timeLeft--;
                if (timeLeft <= 0) {
                    clearInterval(otpTimer);
                    document.getElementById('verifyOtpBtn').style.display = 'none';
                    document.getElementById('resendOtpBtn').style.display = 'inline-flex';
                    showToast('OTP expired. Request a new one.', 'error');
                }
            }, 1000);
        }

        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('otp-digit')) {
                if (e.target.value.length === 1 && /^\d$/.test(e.target.value)) {
                    const nextInput = e.target.nextElementSibling;
                    if (nextInput && nextInput.classList.contains('otp-digit')) {
                        nextInput.focus();
                    }
                }
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.target.classList.contains('otp-digit')) {
                if (e.key === 'Backspace' && e.target.value === '') {
                    const prevInput = e.target.previousElementSibling;
                    if (prevInput && prevInput.classList.contains('otp-digit')) {
                        prevInput.focus();
                    }
                }
            }
        });

        document.getElementById('verifyOtpBtn').addEventListener('click', async function () {
            let enteredOTP = '';
            document.querySelectorAll('.otp-digit').forEach(input => {
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
                const phone = document.getElementById('phone').value.trim();
                const response = await fetch('/verify/otp', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        mobile_number: phone,
                        otp: enteredOTP
                    })
                });

                const result = await response.json();

                if (result.Success || enteredOTP === generatedOTP) {
                    isOtpVerified = true;
                    showToast('Phone verified!', 'success');

                    const otpStatus = document.getElementById('otpStatus');
                    otpStatus.style.display = 'flex';
                    otpStatus.style.background = '#d1fae5';
                    otpStatus.style.color = '#065f46';
                    otpStatus.style.borderLeft = '4px solid #10b981';
                    otpStatus.innerHTML = '<i class="fas fa-check-circle"></i> <strong>Phone verified!</strong>';

                    document.querySelectorAll('.otp-digit').forEach(input => {
                        input.disabled = true;
                    });
                    document.getElementById('verifyOtpBtn').style.display = 'none';
                    document.getElementById('resendOtpBtn').style.display = 'none';

                    if (otpTimer) {
                        clearInterval(otpTimer);
                    }
                } else {
                    showToast('Invalid OTP. Try again.', 'error');
                    document.querySelectorAll('.otp-digit').forEach(input => {
                        input.value = '';
                    });
                    document.querySelector('.otp-digit').focus();
                }
            } catch (error) {
                console.error('Error verifying OTP:', error);
                if (enteredOTP === generatedOTP) {
                    isOtpVerified = true;
                    showToast('Phone verified!', 'success');

                    const otpStatus = document.getElementById('otpStatus');
                    otpStatus.style.display = 'flex';
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
                    showToast('Invalid OTP. Try again.', 'error');
                }
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });

        document.getElementById('resendOtpBtn').addEventListener('click', function () {
            document.getElementById('sendOtpBtn').click();
            document.querySelectorAll('.otp-digit').forEach(input => {
                input.value = '';
                input.disabled = false;
            });
            document.getElementById('otpStatus').style.display = 'none';
            isOtpVerified = false;
        });

        // ========================
        // FORM SUBMISSION
        // ========================

        document.getElementById('jobApplicationForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Validate required fields
            const requiredFields = ['fullName', 'email', 'phone', 'dob', 'maritalStatus', 'profession', 'qualification', 'applying_for'];
            let isValid = true;

            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!field || !field.value || field.value === '') {
                    isValid = false;
                    if (field) {
                        field.style.borderColor = '#ef4444';
                        field.style.borderWidth = '2px';
                        setTimeout(() => {
                            field.style.borderColor = '';
                            field.style.borderWidth = '';
                        }, 3000);
                    }
                }
            });

            // Check other department field
            const otherDeptField = document.getElementById('otherDepartmentField');
            if (otherDeptField && otherDeptField.style.display !== 'none') {
                const otherDeptInput = document.getElementById('other_department_details');
                if (!otherDeptInput || !otherDeptInput.value.trim()) {
                    isValid = false;
                    if (otherDeptInput) {
                        otherDeptInput.style.borderColor = '#ef4444';
                        setTimeout(() => {
                            otherDeptInput.style.borderColor = '';
                        }, 3000);
                    }
                }
            }

            // Check other profile field
            const otherProfileField = document.getElementById('otherProfileField');
            if (otherProfileField && otherProfileField.style.display !== 'none') {
                const otherProfileInput = document.getElementById('other_profile_details');
                if (!otherProfileInput || !otherProfileInput.value.trim()) {
                    isValid = false;
                    if (otherProfileInput) {
                        otherProfileInput.style.borderColor = '#ef4444';
                        setTimeout(() => {
                            otherProfileInput.style.borderColor = '';
                        }, 3000);
                    }
                }
            }

            // Check skills
            if (selectedSkills.length === 0) {
                isValid = false;
                showToast('Please select at least one skill', 'error');
            }

            // Check phone verification
            if (!isOtpVerified) {
                isValid = false;
                showToast('Please verify your phone number with OTP', 'error');
            }

            // Check email verification
            if (!isEmailVerified) {
                isValid = false;
                showToast('Please verify your email address with OTP', 'error');
            }

            // Check resume
            const resumeFile = document.getElementById('resumeFile');
            if (!resumeFile || !resumeFile.files || resumeFile.files.length === 0) {
                isValid = false;
                showToast('Please upload your resume/CV', 'error');
            }

            if (!isValid) {
                return;
            }

            // Get form data
            const formData = new FormData();
            formData.append('full_name', document.getElementById('fullName').value.trim());
            formData.append('email', document.getElementById('email').value.trim());
            formData.append('phone', document.getElementById('phone').value.trim());
            formData.append('dob', document.getElementById('dob').value);
            formData.append('marital_status', document.getElementById('maritalStatus').value);
            formData.append('profession', document.getElementById('profession').value);
            formData.append('organization', document.getElementById('organization').value.trim());
            formData.append('qualification', document.getElementById('qualification').value);
            formData.append('experience_in_year', document.getElementById('expYears').value || 0);
            formData.append('experience_in_month', document.getElementById('expMonths').value || 0);
            formData.append('applying_for', document.getElementById('applying_for').value);
            formData.append('applying_for_profile', document.getElementById('applying_for_profile').value);
            formData.append('skills', document.getElementById('selectedSkills').value);

            if (document.getElementById('applying_for').value === 'other') {
                formData.append('other_department_details', document.getElementById('other_department_details').value.trim());
            }
            if (document.getElementById('applying_for_profile').value === 'other') {
                formData.append('other_profile_details', document.getElementById('other_profile_details').value.trim());
            }

            if (resumeFile && resumeFile.files[0]) {
                formData.append('resume', resumeFile.files[0]);
            }

            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            submitBtn.disabled = true;

            const token = getCsrfToken();

            fetch('/save-interview', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Server error');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const successMsg = document.getElementById('successMessage');
                        successMsg.style.display = 'block';

                        // Hide the submit button
                        submitBtn.style.display = 'none';

                        // Show toast
                        showToast('🎉 Application submitted successfully!', 'success');

                        // Scroll to success message
                        successMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    } else {
                        showToast(data.message || 'Error submitting application', 'error');
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error: ' + error.message, 'error');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        });

        console.log('🚀 Application form loaded!');
    </script>

</body>

</html>
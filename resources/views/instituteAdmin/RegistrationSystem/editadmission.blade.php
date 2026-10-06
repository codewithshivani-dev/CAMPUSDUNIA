<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .form-wrapper {
            margin: 0 auto;
            background: white;
            border-radius: 28px;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.12);
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(135deg, #1e3a5f, #2c5aa0);
            padding: 28px 32px;
            color: white;
        }

        .form-header h1 {
            font-size: 26px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }
 
        .form-header p {
            opacity: 0.85;
            margin-top: 8px;
            font-size: 14px;
        }

        .form-body {
            padding: 32px 36px;
        }

        /* sections */
        .form-section {
            background: #ffffff;
            border: 1px solid #e9edf2;
            border-radius: 24px;
            margin-bottom: 28px;
            padding: 24px 28px;
            transition: all 0.2s;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 2px solid #eef2f8;
        }

        .section-title i {
            font-size: 22px;
            color: #2c5aa0;
            background: #eef3fc;
            padding: 8px;
            border-radius: 14px;
        }

        .section-title h2 {
            font-size: 20px;
            font-weight: 600;
            color: #1f2a3e;
        }

        .form-grid {
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        label {
            font-weight: 600;
            font-size: 14px;
            color: #1e2a3e;
        }

        .required:after {
            content: " *";
            color: #e03a3a;
        }

        input, select, textarea {
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            font-size: 13.5px;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            background: #ffffff;
            width: 100%;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #2c5aa0;
            box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.08);
        }

        .radio-group {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            align-items: center;
            margin-top: 6px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            background: #f8fafc; 
            padding: 8px 20px;
            border-radius: 40px;
            border: 1px solid #e2e8f0;
            transition: 0.2s;
        }

        .radio-option.active {
            background: #eef3fc;
            border-color: #2c5aa0;
        }

        .radio-option input {
            width: 18px;
            height: 18px;
            accent-color: #2c5aa0;
            margin: 0;
        }

        .sibling-box {
            background: #f9fbfe;
            border-radius: 20px;
            padding: 20px;
            margin-top: 20px;
            border: 1px solid #e9edf2;
        }

        hr {
            margin: 16px 0;
            border: 0;
            height: 1px;
            background: #e9edf2;
        }

        .auto-save-note {
            background: #eef3fc;
            border-radius: 16px;
            padding: 12px 20px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            color: #1e3a5f;
        }

        .btn {
            padding: 12px 28px;
            border: none;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: 'Inter', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2c5aa0, #1e3a5f);
            color: white;
            box-shadow: 0 4px 12px rgba(44, 90, 160, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1e3a5f, #0f1f35);
            box-shadow: 0 6px 16px rgba(44, 90, 160, 0.4);
            transform: translateY(-2px);
        }

        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(44, 90, 160, 0.2);
        }

        @media (max-width: 680px) {
            .form-body { padding: 20px; }
            .form-section { padding: 18px; }
            .btn { padding: 10px 20px; font-size: 14px; }
        }
        .otp-digit{
            padding: 0px;
        }
    </style>
<div class="container-fluid">
    <div class="form-wrapper">
        <div class="form-header">
            <h1><i class="fas fa-graduation-cap"></i> Admission Application</h1>
            <p>Edit student admission details below and click Update when ready.</p>
            <div style="margin-top: 10px; font-size: 12px; opacity: 0.8;">
                <i class="fas fa-edit"></i> Edit Count: {{ $admission->edit_count ?? 0 }}
            </div>
        </div>
        @if(session('success'))
            <div class="form-body" style="padding: 16px 36px 0;">
                <div style="background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 14px; padding: 16px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="form-body" style="padding: 16px 36px 0;">
                <div style="background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; border-radius: 14px; padding: 16px; margin-bottom: 20px;">
                    {{ session('error') }}
                </div>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.admission.update', $admission->id) }}"> 
            @csrf
            <div class="form-body">
                <div class="section-title">
                    <i class="fas fa-user-graduate"></i>
                    <h2>Student Basic Details</h2>
                </div>
                <div class="form-grid"> 
                    <div class="form-group">
                        <label class="required">Student Full Name</label>
                        <input type="text" name="student_full_name" id="fullName" placeholder="Full name" autocomplete="off" value="{{ old('student_full_name', $admission->student_full_name ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="required">Date of Birth</label>
                        <input type="date" name="student_dob" id="dob" value="{{ old('student_dob', optional($admission)->student_dob) }}">
                    </div>
                    <div class="form-group">
                        <label class="required">Gender</label>
                        <input type="text" name="student_gender" id="gender" placeholder="Male / Female / Other" value="{{ old('student_gender', $admission->student_gender ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Nationality</label>
                        <input type="text" name="student_nationality" id="nationality" placeholder="e.g., Indian" value="{{ old('student_nationality', $admission->student_nationality ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="required">Applying for Grade</label>
                        <input type="text" id="gradeDisplay" placeholder="e.g., Nursery, LKG, 1, 2, etc." readonly style="background-color: #f8fafc; cursor: not-allowed;">
                        <input type="hidden" name="applying_for_grade" id="grade" value="{{ old('applying_for_grade', $admission->applying_for_grade ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="required">Email Address</label>
                        <div style="position:relative;">
                            <input type="email" name="email" id="email" placeholder="student@example.com" value="{{ old('email', $admission->email ?? '') }}" style="padding-right:102px;">
                            <button type="button" id="sendEmailOtpBtn" style="position:absolute; right:0; top:0; height:100%; display:none; padding:0 16px; border:none; background:#2c5aa0; color:#fff; border-radius:0 14px 14px 0; cursor:pointer;">Send OTP</button>
                        </div>
                        <div id="emailOtpVerificationSection" style="display:none; margin-top:12px; padding:16px; background:#f0f9ff; border:1px solid #0284c7; border-radius:8px;">
                            <div style="margin-bottom:10px;">
                                <strong><i class="fas fa-envelope"></i> Email Verification</strong>
                                <div style="font-size:13px; color:#555;">Enter the 4-digit OTP sent to <span id="emailDisplay"></span></div>
                            </div>
                            <div style="display:flex; gap:8px; margin-bottom:10px;">
                                <input type="text" class="email-otp-digit" maxlength="1" style="width:48px; height:40px; font-size:20px; text-align:center; border:1.5px solid #ddd; border-radius:8px;">
                                <input type="text" class="email-otp-digit" maxlength="1" style="width:48px; height:40px; font-size:20px; text-align:center; border:1.5px solid #ddd; border-radius:8px;">
                                <input type="text" class="email-otp-digit" maxlength="1" style="width:48px; height:40px; font-size:20px; text-align:center; border:1.5px solid #ddd; border-radius:8px;">
                                <input type="text" class="email-otp-digit" maxlength="1" style="width:48px; height:40px; font-size:20px; text-align:center; border:1.5px solid #ddd; border-radius:8px;">
                            </div>
                            <div style="display:flex; gap:10px;">
                                <button type="button" id="verifyEmailOtpBtn" style="background:#2c5aa0; color:#fff; border:none; border-radius:8px; padding:6px 18px;">Verify OTP</button>
                                <button type="button" id="resendEmailOtpBtn" style="display:none; background:#e2e8f0; color:#2c5aa0; border:none; border-radius:8px; padding:6px 18px;">Resend OTP</button> 
                            </div>
                            <div id="emailOtpStatus" style="display:none; margin-top:8px; padding:8px; border-radius:6px;"><span id="emailOtpStatusText"></span></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="required">Phone Number</label>
                        <div style="position:relative;">
                            <input type="tel" name="phone" id="phone" maxlength="10" placeholder="9876543210" value="{{ old('phone', $admission->phone ?? '') }}" autocomplete="off" style="padding-right:120px;">
                            <button type="button" id="sendOtpBtn" style="position:absolute; right:0; top:0; height:100%; display:none; padding:0 16px; border:none; background:#2c5aa0; color:#fff; border-radius:0 14px 14px 0; cursor:pointer;">Send OTP</button>
                        </div>
                        <div id="otpVerificationSection" style="display:none; margin-top:12px; padding:16px; background:#f0f9ff; border:1px solid #0284c7; border-radius:8px;">
                            <div style="margin-bottom:10px;">
                                <strong><i class="fas fa-lock"></i> Phone Verification</strong>
                                <div style="font-size:13px; color:#555;">Enter the 4-digit OTP sent to your phone</div>
                            </div>
                            <div style="display:flex; gap:8px; margin-bottom:10px;">
                                <input type="text" class="otp-digit" maxlength="1" style="width:44px; height:40px; font-size:20px; text-align:center; border:1.5px solid #ddd; border-radius:8px;">
                                <input type="text" class="otp-digit" maxlength="1" style="width:44px; height:40px; font-size:20px; text-align:center; border:1.5px solid #ddd; border-radius:8px;">
                                <input type="text" class="otp-digit" maxlength="1" style="width:44px; height:40px; font-size:20px; text-align:center; border:1.5px solid #ddd; border-radius:8px;">
                                <input type="text" class="otp-digit" maxlength="1" style="width:44px; height:40px; font-size:20px; text-align:center; border:1.5px solid #ddd; border-radius:8px;">
                            </div>
                            <div style="display:flex; gap:10px;">
                                <button type="button" id="verifyOtpBtn" style="background:#2c5aa0; color:#fff; border:none; border-radius:8px; padding:6px 18px;">Verify OTP</button>
                                <button type="button" id="resendOtpBtn" style="display:none; background:#e2e8f0; color:#2c5aa0; border:none; border-radius:8px; padding:6px 18px;">Resend OTP</button>
                            </div>
                            <div id="otpStatus" style="display:none; margin-top:8px; padding:8px; border-radius:6px;"><span id="otpStatusText"></span></div>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- ========= SECTION 2: ADDRESS & ADDITIONAL CONTACTS ========= -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-map-marker-alt"></i>
                    <h2>Address & Additional Contacts</h2>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Alternate Phone</label>
                        <input type="tel" name="alternate_phone" id="altPhone" placeholder="Alternate number" value="{{ old('alternate_phone', $admission->alternate_phone ?? '') }}">
                    </div>
                    <div class="form-group full-width">
                        <label class="required">Address Line 1</label>
                        <input type="text" name="address_line_1" id="address_line_1" placeholder="House no, Street, Area" value="{{ old('address_line_1', $admission->address_line_1 ?? '') }}">
                    </div>
                    <div class="form-group full-width">
                        <label>Address Line 2</label>
                        <input type="text" name="address_line_2" id="address_line_2" placeholder="Apartment, Suite, Landmark (optional)" value="{{ old('address_line_2', $admission->address_line_2 ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" id="city" placeholder="City" value="{{ old('city', $admission->city ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>State</label>
                        <input type="text" name="state" id="state" placeholder="State" value="{{ old('state', $admission->state ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>PIN Code</label>
                        <input type="text" name="pincode" id="pincode" placeholder="PIN code" value="{{ old('pincode', $admission->pincode ?? '') }}">
                    </div>
                </div>
            </div>
    
            <!-- ========= SECTION 3: EDUCATION & FAMILY BACKGROUND ========= -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-graduation-cap"></i>
                    <h2>Education & Family Background</h2>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="required">Previous School</label>
                        <input type="text" name="previous_school" id="prevSchool" placeholder="Last school attended" value="{{ old('previous_school', $admission->previous_school ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="required">Previous Class/Grade</label>
                        <input type="text" name="previous_class" id="prevClass" placeholder="e.g., Class 5" value="{{ old('previous_class', $admission->previous_class ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>School Location</label>
                        <input type="text" name="school_location" id="schoolLocation" placeholder="City/State" value="{{ old('school_location', $admission->school_location ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Percentage / CGPA</label>
                        <input type="text" name="percentage_cgpa" id="marks" placeholder="85% or 8.5 CGPA" value="{{ old('percentage_cgpa', $admission->percentage_cgpa ?? '') }}">
                    </div>
                </div>
    
                <!-- Marks Format Radio -->
                <div style="margin: 20px 0 10px 0;">
                    <label>Marks Format</label>
                    <div class="radio-group" id="marksFormatGroup">
                        <label class="radio-option" data-format="percentage">
                            <input type="radio" name="marks_format" value="percentage" {{ old('marks_format', $admission->marks_format) === 'percentage' ? 'checked' : '' }}> Percentage
                        </label>
                        <label class="radio-option" data-format="cgpa">
                            <input type="radio" name="marks_format" value="cgpa" {{ old('marks_format', $admission->marks_format) === 'cgpa' ? 'checked' : '' }}> CGPA
                        </label>
                    </div>
                </div>
    
                <!-- Sibling Question -->
                <div style="margin: 20px 0 10px 0;">
                    <label>Sibling already studying in this institute?</label>
                    <div class="radio-group" id="siblingGroup">
                        <label class="radio-option" data-sibling="yes">
                            <input type="radio" name="sibling_option" value="yes" {{ old('sibling_option', $admission->sibling_option ?? '') === 'yes' ? 'checked' : '' }}> Yes
                        </label>
                        <label class="radio-option" data-sibling="no">
                            <input type="radio" name="sibling_option" value="no" {{ old('sibling_option', $admission->sibling_option ?? '') === 'no' ? 'checked' : '' }}> No
                        </label>
                    </div>
                </div>
    
                <!-- Sibling Details (hidden by default) -->
                <div id="siblingDetailsBlock" style="display: none;">
                    <div class="sibling-box">
                        <div class="form-grid">
                            <div class="form-group"><label>Sibling's Name</label><input type="text" name="sibling_name" id="siblingName" placeholder="Sibling's full name" value="{{ old('sibling_name', $admission->sibling_name ?? '') }}"></div>
                            <div class="form-group"><label>Sibling's Class</label><input type="text" name="sibling_class" id="siblingClass" placeholder="Current class" value="{{ old('sibling_class', $admission->sibling_class ?? '') }}"></div>
                            <div class="form-group"><label>Sibling's Section</label><input type="text" name="sibling_section" id="siblingSection" placeholder="Section (if any)" value="{{ old('sibling_section', $admission->sibling_section ?? '') }}"></div>
                            <div class="form-group"><label>Admission Number</label><input type="text" name="sibling_admission_no" id="siblingAdmNo" placeholder="Admission number" value="{{ old('sibling_admission_no', $admission->sibling_admission_no ?? '') }}"></div>
                        </div>
                    </div> 
                </div>
            </div>
    
            <!-- ========= SECTION 4: FATHER / GUARDIAN ========= -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-user-tie"></i>
                    <h2>Father / Guardian</h2>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="required">Father's/Guardian's Name</label>
                        <input type="text" name="father_name" id="fatherName" placeholder="Full name" value="{{ old('father_name', $admission->father_name ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Occupation</label>
                        <input type="text" name="father_occupation" id="fatherOcc" placeholder="Occupation" value="{{ old('father_occupation', $admission->father_occupation ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="required">Phone Number</label>
                        <input type="tel" name="father_phone" id="fatherPhone" placeholder="Contact" value="{{ old('father_phone', $admission->father_phone ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="father_email" id="fatherEmail" placeholder="Email" value="{{ old('father_email', $admission->father_email ?? '') }}">
                    </div>
                </div>
            </div>
    
            <!-- ========= SECTION 5: MOTHER INFORMATION ========= -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-female"></i>
                    <h2>Mother Information</h2>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Mother's Name</label>
                        <input type="text" name="mother_name" id="motherName" placeholder="Full name" value="{{ old('mother_name', $admission->mother_name ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Occupation</label>
                        <input type="text" name="mother_occupation" id="motherOcc" placeholder="Occupation" value="{{ old('mother_occupation', $admission->mother_occupation ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" name="mother_phone" id="motherPhone" placeholder="Contact" value="{{ old('mother_phone', $admission->mother_phone ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="mother_email" id="motherEmail" placeholder="Email" value="{{ old('mother_email', $admission->mother_email ?? '') }}">
                    </div>
                </div>
            </div>
    
            <!-- Update button -->
            <div style="padding: 0 28px 32px; display: flex; justify-content: flex-end; gap: 12px;">
                <button type="submit" class="btn btn-primary" style="margin-top: 10px;">
                    <i class="fas fa-save"></i> Update Details
                </button>
            </div>
        </form>
    </div>
</div>

<script>
        // --- OTP Verification Logic (API only, no demo/fallback) ---
        let otpTimer = null;
        let isOtpVerified = true; // Set true initially if phone is unchanged, else false
        const phoneInput = document.getElementById('phone');
        const sendOtpBtn = document.getElementById('sendOtpBtn');
        const otpSection = document.getElementById('otpVerificationSection');
        const verifyOtpBtn = document.getElementById('verifyOtpBtn');
        const resendOtpBtn = document.getElementById('resendOtpBtn');
        const otpStatus = document.getElementById('otpStatus');
        const otpStatusText = document.getElementById('otpStatusText');

        // Email OTP variables
        let emailOtpTimer = null;
        let isEmailVerified = true; // Set true initially if email is unchanged, else false
        const emailInput = document.getElementById('email');
        const sendEmailOtpBtn = document.getElementById('sendEmailOtpBtn');
        const emailOtpSection = document.getElementById('emailOtpVerificationSection');
        const verifyEmailOtpBtn = document.getElementById('verifyEmailOtpBtn');
        const resendEmailOtpBtn = document.getElementById('resendEmailOtpBtn');
        const emailOtpStatus = document.getElementById('emailOtpStatus');
        const emailOtpStatusText = document.getElementById('emailOtpStatusText');
        const emailDisplay = document.getElementById('emailDisplay');

        function getCsrfToken() {
            const meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.content : '';
        }

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
                return { Success: false, message: 'Network error: ' + error.message };
            }
        }

        let originalPhone = phoneInput.value;
        isOtpVerified = true;
        phoneInput.addEventListener('input', function() {
            if (this.value.trim().length === 10 && this.value !== originalPhone) {
                sendOtpBtn.style.display = 'inline-block';
                isOtpVerified = false;
            } else {
                sendOtpBtn.style.display = 'none';
                otpSection.style.display = 'none';
                isOtpVerified = (this.value === originalPhone);
            }
        });

        // Email input handling
        let originalEmail = emailInput.value;
        isEmailVerified = true;
        emailInput.addEventListener('input', function() {
            if (this.value.trim() && this.value !== originalEmail) {
                sendEmailOtpBtn.style.display = 'inline-block';
                isEmailVerified = false;
            } else {
                sendEmailOtpBtn.style.display = 'none';
                emailOtpSection.style.display = 'none';
                isEmailVerified = (this.value === originalEmail);
            }
        });

        sendOtpBtn.addEventListener('click', async function() {
            const phone = phoneInput.value.trim();
            if (!phone || phone.length !== 10) {
                alert('Please enter a valid 10-digit phone number');
                return;
            }
            sendOtpBtn.innerHTML = 'Sending...';
            sendOtpBtn.disabled = true;
            try {
                const result = await makeAjaxRequest('/send/otp', 'POST', { mobile_number: phone });
                if (result.Success) {
                    otpSection.style.display = 'block';
                    startOTPTimer();
                    document.querySelector('.otp-digit').focus();
                } else {
                    alert(result.message || 'Failed to send OTP. Please try again.');
                }
            } catch (e) {
                alert('Failed to send OTP. Please try again.');
            } finally {
                sendOtpBtn.innerHTML = 'Send OTP';
                sendOtpBtn.disabled = false;
            }
        });

        function startOTPTimer() {
            let timeLeft = 300;
            verifyOtpBtn.style.display = 'inline-block';
            resendOtpBtn.style.display = 'none';
            if (otpTimer) clearInterval(otpTimer);
            otpTimer = setInterval(function() {
                timeLeft--;
                if (timeLeft <= 0) {
                    clearInterval(otpTimer);
                    verifyOtpBtn.style.display = 'none';
                    resendOtpBtn.style.display = 'inline-block';
                    alert('OTP expired. Please resend.');
                }
            }, 1000);
        }

        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('otp-digit')) {
                if (e.target.value.length === 1 && /^\d$/.test(e.target.value)) {
                    const next = e.target.nextElementSibling;
                    if (next && next.classList.contains('otp-digit')) next.focus();
                }
            }
        });
        document.addEventListener('keydown', function(e) {
            if (e.target.classList.contains('otp-digit')) {
                if (e.key === 'Backspace' && e.target.value === '') {
                    const prev = e.target.previousElementSibling;
                    if (prev && prev.classList.contains('otp-digit')) prev.focus();
                }
            }
        });

        verifyOtpBtn.addEventListener('click', async function() {
            let enteredOTP = '';
            document.querySelectorAll('.otp-digit').forEach(input => { enteredOTP += input.value; });
            if (enteredOTP.length !== 4) {
                alert('Please enter the complete 4-digit OTP');
                return;
            }
            verifyOtpBtn.innerHTML = 'Verifying...';
            verifyOtpBtn.disabled = true;
            try {
                const phone = phoneInput.value.trim();
                const result = await makeAjaxRequest('/verify/otp', 'POST', { mobile_number: phone, otp: enteredOTP });
                if (result.Success) {
                    isOtpVerified = true;
                    otpStatus.style.display = 'block';
                    // otpDigit.style.display = 'none';
                    otpStatus.style.background = '#d1fae5';
                    otpStatus.style.color = '#065f46';
                    otpStatus.innerHTML = '<i class="fas fa-check-circle"></i> <strong>Phone verified!</strong>';
                    document.querySelectorAll('.otp-digit').forEach(input => { input.disabled = true; });
                    verifyOtpBtn.style.display = 'none';
                    resendOtpBtn.style.display = 'none';
                    if (otpTimer) clearInterval(otpTimer);
                } else {
                    isOtpVerified = false;
                    alert('Invalid OTP. Please try again.');
                    document.querySelectorAll('.otp-digit').forEach(input => { input.value = ''; input.disabled = false; });
                    document.querySelector('.otp-digit').focus();
                }
            } finally {
                verifyOtpBtn.innerHTML = 'Verify OTP';
                verifyOtpBtn.disabled = false;
            }
        });

        resendOtpBtn.addEventListener('click', function() {
            sendOtpBtn.click();
            document.querySelectorAll('.otp-digit').forEach(input => { input.value = ''; input.disabled = false; });
            otpStatus.style.display = 'none';
            isOtpVerified = false;
        });

        // Email OTP functions
        sendEmailOtpBtn.addEventListener('click', async function() {
            const email = emailInput.value.trim();
            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                alert('Please enter a valid email address');
                return;
            }
            sendEmailOtpBtn.innerHTML = 'Sending...';
            sendEmailOtpBtn.disabled = true;
            try {
                const result = await makeAjaxRequest('/send-email-otp', 'POST', { email_id: email });
                if (result.success) {
                    emailOtpSection.style.display = 'block';
                    emailDisplay.textContent = email;
                    startEmailOTPTimer();
                    document.querySelector('.email-otp-digit').focus();
                } else {
                    alert(result.message || 'Failed to send OTP. Please try again.');
                }
            } catch (e) {
                alert('Failed to send OTP. Please try again.');
            } finally {
                sendEmailOtpBtn.innerHTML = 'Send OTP';
                sendEmailOtpBtn.disabled = false;
            }
        });

        function startEmailOTPTimer() {
            let timeLeft = 300;
            verifyEmailOtpBtn.style.display = 'inline-block';
            resendEmailOtpBtn.style.display = 'none';
            if (emailOtpTimer) clearInterval(emailOtpTimer);
            emailOtpTimer = setInterval(function() {
                timeLeft--;
                if (timeLeft <= 0) {
                    clearInterval(emailOtpTimer);
                    verifyEmailOtpBtn.style.display = 'none';
                    resendEmailOtpBtn.style.display = 'inline-block';
                    alert('OTP expired. Please resend.');
                }
            }, 1000);
        }

        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('email-otp-digit')) {
                if (e.target.value.length === 1 && /^\d$/.test(e.target.value)) {
                    const next = e.target.nextElementSibling;
                    if (next && next.classList.contains('email-otp-digit')) next.focus();
                }
            }
        });
        document.addEventListener('keydown', function(e) {
            if (e.target.classList.contains('email-otp-digit')) {
                if (e.key === 'Backspace' && e.target.value === '') {
                    const prev = e.target.previousElementSibling;
                    if (prev && prev.classList.contains('email-otp-digit')) prev.focus();
                }
            }
        });

        verifyEmailOtpBtn.addEventListener('click', async function() {
            let enteredOTP = '';
            document.querySelectorAll('.email-otp-digit').forEach(input => { enteredOTP += input.value; });
            if (enteredOTP.length !== 4) {
                alert('Please enter the complete 4-digit OTP');
                return;
            }
            verifyEmailOtpBtn.innerHTML = 'Verifying...';
            verifyEmailOtpBtn.disabled = true;
            try {
                const email = emailInput.value.trim();
                const result = await makeAjaxRequest('/verify-email-otp', 'POST', { email: email, otp: enteredOTP });
                if (result.success) {
                    isEmailVerified = true;
                    emailOtpStatus.style.display = 'block';
                    emailOtpStatus.style.background = '#d1fae5';
                    emailOtpStatus.style.color = '#065f46';
                    emailOtpStatus.innerHTML = '<i class="fas fa-check-circle"></i> <strong>Email verified!</strong>';
                    document.querySelectorAll('.email-otp-digit').forEach(input => { input.disabled = true; });
                    verifyEmailOtpBtn.style.display = 'none';
                    resendEmailOtpBtn.style.display = 'none';
                    if (emailOtpTimer) clearInterval(emailOtpTimer);
                } else {
                    isEmailVerified = false;
                    alert('Invalid OTP. Please try again.');
                    document.querySelectorAll('.email-otp-digit').forEach(input => { input.value = ''; input.disabled = false; });
                    document.querySelector('.email-otp-digit').focus();
                }
            } finally {
                verifyEmailOtpBtn.innerHTML = 'Verify OTP';
                verifyEmailOtpBtn.disabled = false;
            }
        });

        resendEmailOtpBtn.addEventListener('click', function() {
            sendEmailOtpBtn.click();
            document.querySelectorAll('.email-otp-digit').forEach(input => { input.value = ''; input.disabled = false; });
            emailOtpStatus.style.display = 'none';
            isEmailVerified = false;
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            if (!isOtpVerified) {
                alert('Please verify your phone number with OTP before submitting.');
                e.preventDefault();
                return;
            }
            if (!isEmailVerified) {
                alert('Please verify your email address with OTP before submitting.');
                e.preventDefault();
                return;
            }
        });
    // Get classes data from controller (if passed)
    const classes = @json($classes ?? []);
    
    // Create class name mapping
    const classMap = {};
    classes.forEach(cls => {
        classMap[cls.finacp_merchant_sub_category_id] = cls.finacp_merchant_sub_category_type;
    });
    
    // Helper function to get class name from ID
    function getClassName(classId) {
        if (!classId) return '';
        return classMap[classId] || classId || '';
    }
    
    // DOM elements - Student Basic
    const fullName = document.getElementById('fullName');
    const dob = document.getElementById('dob');
    const gender = document.getElementById('gender');
    const nationality = document.getElementById('nationality');
    const grade = document.getElementById('grade');
    const gradeDisplay = document.getElementById('gradeDisplay');
    const email = document.getElementById('email');
    const phone = document.getElementById('phone');
    // Address & additional
    const altPhone = document.getElementById('altPhone');
    const addressLine1 = document.getElementById('address_line_1');
    const addressLine2 = document.getElementById('address_line_2');
    const city = document.getElementById('city');
    const state = document.getElementById('state');
    const pincode = document.getElementById('pincode');
    // Education
    const prevSchool = document.getElementById('prevSchool');
    const prevClass = document.getElementById('prevClass');
    const schoolLocation = document.getElementById('schoolLocation');
    const marks = document.getElementById('marks');
    // Father
    const fatherName = document.getElementById('fatherName');
    const fatherOcc = document.getElementById('fatherOcc');
    const fatherPhone = document.getElementById('fatherPhone');
    const fatherEmail = document.getElementById('fatherEmail');
    // Mother
    const motherName = document.getElementById('motherName');
    const motherOcc = document.getElementById('motherOcc');
    const motherPhone = document.getElementById('motherPhone');
    const motherEmail = document.getElementById('motherEmail');
    // Sibling
    const siblingBlock = document.getElementById('siblingDetailsBlock');
    const siblingName = document.getElementById('siblingName');
    const siblingClass = document.getElementById('siblingClass');
    const siblingSection = document.getElementById('siblingSection');
    const siblingAdmNo = document.getElementById('siblingAdmNo');

    let marksFormatValue = 'percentage';
    let siblingStatus = 'no';

    // Helper: update active styling for radio groups
    function updateRadioStyles() {
        document.querySelectorAll('#marksFormatGroup .radio-option').forEach(opt => {
            const radio = opt.querySelector('input');
            if (radio && radio.checked) opt.classList.add('active');
            else opt.classList.remove('active');
        });
        document.querySelectorAll('#siblingGroup .radio-option').forEach(opt => {
            const radio = opt.querySelector('input');
            if (radio && radio.checked) opt.classList.add('active');
            else opt.classList.remove('active');
        });
    }

    // Attach behavior for radio inputs and sibling details
    const marksRadios = document.querySelectorAll('input[name="marks_format"]');
    const siblingRadios = document.querySelectorAll('input[name="sibling_option"]');

    marksRadios.forEach(radio => {
        radio.addEventListener('change', () => updateRadioStyles());
    });

    siblingRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
            if (e.target.checked) {
                siblingBlock.style.display = e.target.value === 'yes' ? 'block' : 'none';
            }
            updateRadioStyles();
        });
    });

    document.querySelectorAll('.radio-option').forEach(opt => {
        opt.addEventListener('click', () => {
            const radio = opt.querySelector('input');
            if (radio) {
                radio.checked = true;
                radio.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    });

    // Initialize state on load
    const selectedSibling = document.querySelector('input[name="sibling_option"]:checked');
    if (selectedSibling && selectedSibling.value === 'yes') {
        siblingBlock.style.display = 'block';
    } else {
        siblingBlock.style.display = 'none';
    }

    updateRadioStyles();
    
    // Initialize grade field with mapped class name on page load
    if (grade.value) {
        const gradeId = grade.value;
        const gradeName = getClassName(gradeId);
        // Display the class name, keep ID in hidden field for submission
        if (gradeName && gradeName !== gradeId) {
            gradeDisplay.value = gradeName;
        } else {
            gradeDisplay.value = gradeId; // Fallback to ID if no mapping found
        }
    }
</script>
@endsection
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Enrollment Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #ffffff;
            font-family: "Segoe UI", "Poppins", system-ui, sans-serif;
            padding: 40px 24px;
        } 

        .form-container {
            max-width: 999px; 
            margin: 0 auto;
            background: #ffffff;
            border-radius: 28px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            border: 1px solid #f0f2f5;
        }

        .form-header {
            background: #fafbfc;
            padding: 28px 32px;
            border-bottom: 1px solid #eef2f6;
        }

        .form-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1a2634;
        }

        .form-header h1 i {
            color: #2c6e9e;
            margin-right: 12px;
        }

        .form-header p {
            font-size: 14px;
            color: #5e6f8d;
            margin-left: 40px;
        }

        .form-body {
            padding: 32px 36px;
        }

        .section {
            margin-bottom: 36px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e2f41;
            margin-bottom: 24px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eef2f8;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title i {
            font-size: 22px;
            color: #2c6e9e;
            background: #f0f4fa;
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            margin-bottom: 24px;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-group-full {
            width: 100%;
        }

        label {
            display: block;
            font-weight: 600;
            font-size: 13px;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .required-label::after {
            content: " *";
            color: #e74c3c;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            font-size: 14px;
            font-family: inherit;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #2c6e9e;
        }

        .radio-group {
            display: flex;
            gap: 28px;
            align-items: center;
            flex-wrap: wrap;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .radio-option input {
            width: 18px;
            height: 18px;
        }

        /* OTP Verification Styles */
        .otp-verification-section {
            background: #f0f7ff;
            border: 1.5px solid #dbeafe;
            border-radius: 14px;
            padding: 24px;
            margin-top: 20px;
            display: none;
        }

        .otp-verification-section h3 {
            font-size: 16px;
            color: #1e40af;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .otp-inputs {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .otp-digit {
            width: 50px;
            height: 50px;
            font-size: 24px;
            text-align: center;
            border: 2px solid #dbeafe;
            border-radius: 10px;
            font-weight: bold;
            color: #1e40af;
            transition: all 0.3s ease;
        }

        .otp-digit:focus {
            border-color: #2c6e9e;
            box-shadow: 0 0 0 3px rgba(44, 110, 158, 0.1);
        }

        .otp-digit:disabled {
            background-color: #f0f7ff;
            cursor: not-allowed;
        }

        .otp-timer {
            font-size: 13px;
            color: #0369a1;
            text-align: center;
            margin: 12px 0;
        }

        .otp-status {
            display: none;
            padding: 12px 16px;
            border-radius: 10px;
            margin: 12px 0;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .otp-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .otp-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .otp-btn-verify {
            background: #2c6e9e;
            color: white;
            display: none;
        }

        .otp-btn-verify:hover {
            background: #1f547c;
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
            background: #2c6e9e;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            margin-left: 8px;
            display: none;
        }

        .otp-send-btn:hover {
            background: #1f547c;
        }

        .otp-send-btn:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
        }

        .button-group {
            display: flex;
            gap: 18px;
            justify-content: flex-end;
            margin-top: 28px;
            padding-top: 24px;
            border-top: 1px solid #eef2f8;
        }

        .btn {
            padding: 12px 32px;
            border: none;
            border-radius: 40px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary {
            background: #2c6e9e;
            color: white;
        }

        .btn-primary:hover {
            background: #1f547c;
        }

        .btn-secondary {
            background: #f8fafd;
            color: #2c3e66;
            border: 1.5px solid #e2e8f0;
        }

        .alert {
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 14px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .info-note {
            background: #fafcff;
            border-radius: 16px;
            padding: 14px 18px;
            margin-top: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid #eef2fa;
            font-size: 12px;
        }

        .loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loading-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        @media (max-width: 780px) {
            .form-body {
                padding: 24px;
            }
            .form-row {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1>
                <i class="fas fa-graduation-cap"></i>
                Student Admission Form  
            </h1>
            <p>
                <i class="fas fa-edit"></i> Please fill in all required details
            </p>
        </div>

        <div class="form-body">
            <div id="alert-message"></div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="admissionForm" method="POST">
                @csrf
                
                <!-- STUDENT INFORMATION SECTION -->
                <div class="section">
                    <div class="section-title">
                        <i class="fas fa-user-circle"></i>
                        <span>Student Information</span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required-label">Student's Full Name</label>
                            <input type="text" name="student_full_name" placeholder="e.g., Priya Sharma" value="{{ old('student_full_name') }}" required />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required-label">Date of Birth</label>
                            <input type="date" name="student_dob" value="{{ old('student_dob') }}" required />
                        </div>

                        <div class="form-group">
                            <label class="required-label">Gender</label>
                            <div class="radio-group">
                                <div class="radio-option">
                                    <input type="radio" name="student_gender" value="Male" id="male" {{ old('student_gender') == 'Male' ? 'checked' : '' }} required />
                                    <label for="male">Male</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" name="student_gender" value="Female" id="female" {{ old('student_gender') == 'Female' ? 'checked' : '' }} />
                                    <label for="female">Female</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" name="student_gender" value="Other" id="other_gender" {{ old('student_gender') == 'Other' ? 'checked' : '' }} />
                                    <label for="other_gender">Other</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required-label">Nationality</label>
                            <input type="text" name="student_nationality" placeholder="e.g., Indian" value="{{ old('student_nationality', 'Indian') }}" required />
                        </div>

                        <div class="form-group">
                            <label class="required-label">Applying for Grade/Class</label>
                            @if(count($admissionclass) > 0)
                                <select name="applying_for_grade" required>
                                    <option value="" disabled {{ old('applying_for_grade') ? '' : 'selected' }}>— Select Grade —</option>
                                    @foreach($admissionclass as $class)
                                        <option value="{{ $class['id'] ?? $class->id }}" {{ old('applying_for_grade') == ($class['id'] ?? $class->id) ? 'selected' : '' }}>
                                            {{ $class['name'] ?? $class->name ?? 'Unknown' }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <div style="padding: 12px; background: #FEF3C7; border: 1px solid #FCD34D; border-radius: 8px; color: #92400E;">
                                    <i class="fas fa-info-circle"></i> No classes configured for admission. Please configure classes in Admission Process Settings.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- PARENT/GUARDIAN DETAILS SECTION -->
                <div class="section">
                    <div class="section-title">
                        <i class="fas fa-people-arrows"></i> 
                        <span>Parent / Guardian Details</span> 
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="">Parent/Guardian Full Name</label>
                            <input type="text" name="parent_full_name" placeholder="Full name" value="{{ old('parent_full_name') }}"  />
                        </div>

                        <div class="form-group">
                            <label class="">Relationship to Student</label>
                            <input type="text" name="relationship" placeholder="e.g., Father / Mother / Guardian" value="{{ old('relationship') }}"  />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="">Occupation</label>
                            <input type="text" name="parent_occupation" placeholder="e.g., Engineer, Business, Homemaker" value="{{ old('parent_occupation') }}"  />
                        </div>
                    </div>
                </div>

                <!-- CONTACT DETAILS SECTION -->
                <div class="section">
                    <div class="section-title">
                        <i class="fas fa-address-card"></i>
                        <span>Contact Details</span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="">WhatsApp Number</label>
                            <input type="tel" name="whatsapp_number" placeholder="WhatsApp contact number" value="{{ old('whatsapp_number') }}"  />
                        </div>

                        <div class="form-group">
                            <label class="required-label">Email Address</label>
                            <div style="display: flex; gap: 8px; align-items: flex-end;">
                                <input type="email" id="email" name="email" placeholder="Email address" value="{{ old('email') }}" required style="flex: 1;" />
                                <button type="button" id="sendEmailOtpBtn" class="otp-send-btn" style="display: none;">
                                    <i class="fas fa-paper-plane"></i> Send OTP
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="emailOtpVerificationSection" class="otp-verification-section" style="display: none; margin-top: 16px; padding: 16px; border: 1px solid #c7d2fe; border-radius: 8px; background: #eff6ff;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                            <i class="fas fa-envelope-open-text" style="color: #2563eb; font-size: 18px;"></i>
                            <h4 style="margin: 0; color: #1e3a8a;">Email OTP Verification</h4>
                        </div>
                        <p style="color: #1e40af; font-size: 14px; margin-bottom: 15px;">
                            Enter the 4-digit OTP sent to <strong id="emailDisplay"></strong>
                        </p>
                        <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                            <input type="text" class="email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #2563eb; border-radius: 8px;" inputmode="numeric" />
                            <input type="text" class="email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #2563eb; border-radius: 8px;" inputmode="numeric" />
                            <input type="text" class="email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #2563eb; border-radius: 8px;" inputmode="numeric" />
                            <input type="text" class="email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #2563eb; border-radius: 8px;" inputmode="numeric" />
                        </div>
                        <div style="display: flex; gap: 10px; margin-top: 12px;">
                            <button type="button" id="verifyEmailOtpBtn" class="otp-btn otp-btn-verify">
                                <i class="fas fa-check"></i> Verify Email OTP
                            </button>
                            <button type="button" id="resendEmailOtpBtn" class="otp-btn otp-btn-resend" style="display: none;">
                                <i class="fas fa-redo"></i> Resend OTP
                            </button>
                        </div>
                        <div id="emailOtpStatus" class="otp-status" style="display: none; margin-top: 12px;"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required-label">Phone Number</label>
                            <div style="display: flex; gap: 8px; align-items: flex-end;">
                                <input type="tel" id="phone" name="phone" placeholder="Contact number" value="{{ old('phone') }}" required style="flex: 1;" />
                                <button type="button" id="sendOtpBtn" class="otp-send-btn" style="display: none;">
                                    <i class="fas fa-sms"></i> Send OTP
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Alternate Phone</label>
                            <input type="tel" name="alternate_phone" placeholder="Alternate contact number" value="{{ old('alternate_phone') }}" />
                        </div>
                    </div>

                    <!-- OTP Verification Section -->
                    <div id="otpVerificationSection" class="otp-verification-section">
                        <h3>
                            <i class="fas fa-shield-alt"></i> Verify Phone Number
                        </h3>
                        <p style="font-size: 13px; color: #475569; margin-bottom: 16px;">
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
                </div>

                <!-- ADDRESS SECTION -->
                <div class="section">
                    <div class="section-title">
                        <i class="fas fa-location-dot"></i>
                        <span>Address</span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="">Address Line 1</label>
                            <input type="text" name="address_line_1" placeholder="House no, Street, Area" value="{{ old('address_line_1') }}"  />
                        </div>
                        <div class="form-group">
                            <label>Address Line 2</label>
                            <input type="text" name="address_line_2" placeholder="Apartment, Suite, Landmark (optional)" value="{{ old('address_line_2') }}" />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="">City</label>
                            <input type="text" name="city" placeholder="City name" value="{{ old('city') }}" />
                        </div>

                        <div class="form-group">
                            <label class="">State</label>
                            <input type="text" name="state" placeholder="State name" value="{{ old('state') }}"  />
                        </div>

                        <div class="form-group">
                            <label class="">PIN Code</label>
                            <input type="text" name="pincode" placeholder="6-digit PIN" maxlength="6" value="{{ old('pincode') }}"  />
                        </div>
                    </div>
                </div>

                <!-- Hidden fields for RegistrationController -->
                <input type="hidden" name="is_initial_lead" value="1" />
                <input type="hidden" name="is_complete_application" value="0" />
                <input type="hidden" name="email_verified" id="email_verified" value="0" />
                <input type="hidden" name="applicant_type" value="self" />
                <input type="hidden" name="status" value="pending" />
                <input type="hidden" name="registration_mode" value="online" />
                <input type="hidden" name="source" value="website" />

                <div class="button-group">
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-eraser"></i> Clear Form
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Submit Enrollment
                    </button>
                </div>

                <div class="info-note">
                    <i class="fas fa-shield-alt"></i>
                    <span>
                        <strong>Note:</strong> Fields marked with <span style="color: #e74c3c">*</span> are mandatory.
                    </span>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading">
        <div class="loading-content">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p>Submitting your application...</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('admissionForm');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const alertDiv = document.getElementById('alert-message');
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Show loading overlay
                loadingOverlay.style.display = 'flex';
                
                // Clear previous alerts
                alertDiv.innerHTML = '';
                
                // Get form data
                const formData = new FormData(form);
                const emailVerifiedField = document.getElementById('email_verified');
                const addressLine1 = form.querySelector('input[name="address_line_1"]')?.value.trim();
                const addressLine2 = form.querySelector('input[name="address_line_2"]')?.value.trim();

                if (!emailVerifiedField || emailVerifiedField.value !== '1') {
                    alertDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            Please verify your email address before submitting the application.
                        </div>
                    `;
                    loadingOverlay.style.display = 'none';
                    return;
                }

                if (addressLine1) {
                    formData.set('address', addressLine2 ? `${addressLine1}, ${addressLine2}` : addressLine1);
                }

                console.log('Form data being sent:', [...formData.entries()]);
                
                try {
                    // Use the direct URL path (since route has no name)
                    const response = await fetch('/save-admission', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Show success message
                        alertDiv.innerHTML = `
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> 
                                ${data.message}
                                <br>
                                <strong>Reference ID:</strong> ${data.reference_id || 'N/A'}
                                ${data.lead_id ? `<br><strong>Lead ID:</strong> ${data.lead_id}` : ''}
                            </div>
                        `;
                        
                        // Reset form
                        form.reset();
                        
                        // Scroll to top to see success message
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else {
                        // Show error message
                        alertDiv.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                ${data.message || 'Failed to submit application. Please try again.'}
                            </div>
                        `;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alertDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            Network error. Please check your connection and try again.
                        </div>
                    `;
                } finally {
                    // Hide loading overlay
                    loadingOverlay.style.display = 'none';
                }
            });

            form.addEventListener('reset', function() {
                document.getElementById('sendEmailOtpBtn').style.display = 'none';
                document.getElementById('emailOtpVerificationSection').style.display = 'none';
                document.getElementById('otpVerificationSection').style.display = 'none';
                document.getElementById('otpStatus').style.display = 'none';
                const emailStatus = document.getElementById('emailOtpStatus');
                if (emailStatus) {
                    emailStatus.style.display = 'none';
                }
                isOtpVerified = false;
                isEmailVerified = false;
                const emailVerifiedField = document.getElementById('email_verified');
                if (emailVerifiedField) {
                    emailVerifiedField.value = '0';
                }
            });
            
            // Add validation for pincode
            const pincodeInput = document.querySelector('input[name="pincode"]');
            if (pincodeInput) {
                pincodeInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
                });
            }
            
            // Add validation for phone numbers
            const phoneInputs = document.querySelectorAll('input[type="tel"]');
            phoneInputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9+]/g, '');
                });
            });
        });

        // ========================
        // OTP Functionality
        // ========================
        let generatedOTP = '';
        let otpTimer = null;
        let isOtpVerified = false; 

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
        document.getElementById('phone').addEventListener('input', function() {
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

        // Send OTP function
        document.getElementById('sendOtpBtn').addEventListener('click', async function() {
            const phone = document.getElementById('phone').value.trim();
            
            if (!phone || phone.length !== 10) {
                showToast('Please enter a valid 10-digit phone number', 'error');
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
                    startOTPTimer();
                    document.querySelector('.otp-digit').focus();
                } else {
                    showToast(result.message || 'Failed to send OTP', 'error');
                    // Fallback: Generate OTP locally
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

        // Fallback OTP generation
        function fallbackGenerateOTP() {
            generatedOTP = Math.floor(1000 + Math.random() * 9000).toString();
            console.log('Generated OTP (local):', generatedOTP);
            showToast(`OTP generated: ${generatedOTP}`, 'success');
            document.getElementById('otpVerificationSection').style.display = 'block';
            startOTPTimer();
            document.querySelector('.otp-digit').focus();
        }

        // Start OTP timer
        function startOTPTimer() {
            let timeLeft = 120; // 2 minutes
            document.getElementById('verifyOtpBtn').style.display = 'inline-flex';
            document.getElementById('resendOtpBtn').style.display = 'none';

            // Create timer display if not exists
            let timerDisplay = document.getElementById('otpTimerDisplay');
            if (!timerDisplay) {
                timerDisplay = document.createElement('div');
                timerDisplay.id = 'otpTimerDisplay';
                timerDisplay.style.cssText = 'margin-top: 12px; padding: 10px; background: #EFF6FF; border-left: 4px solid #2563EB; border-radius: 6px; color: #1E40AF; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px;';
                document.getElementById('otpVerificationSection').appendChild(timerDisplay);
            }

            if (otpTimer) {
                clearInterval(otpTimer);
            }

            otpTimer = setInterval(function() {
                timeLeft--;
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerDisplay.innerHTML = `<i class="fas fa-clock" style="color: #2563EB;"></i> OTP expires in: <strong>${minutes}:${seconds.toString().padStart(2, '0')}</strong>`;

                if (timeLeft <= 0) {
                    clearInterval(otpTimer);
                    document.getElementById('verifyOtpBtn').style.display = 'none';
                    document.getElementById('resendOtpBtn').style.display = 'inline-flex';
                    timerDisplay.innerHTML = '<i class="fas fa-hourglass-end" style="color: #DC2626;"></i> <strong>OTP Expired!</strong> Click Resend to get a new OTP.';
                    timerDisplay.style.background = '#FEE2E2';
                    timerDisplay.style.borderLeftColor = '#DC2626';
                    timerDisplay.style.color = '#991B1B';
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
        document.getElementById('verifyOtpBtn').addEventListener('click', async function() {
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
                const result = await makeAjaxRequest('/verify/otp', 'POST', {
                    mobile_number: phone,
                    otp: enteredOTP
                });

                if (result.Success) {
                    isOtpVerified = true;
                    showToast('Phone number verified successfully!', 'success');
                    
                    // Show success status
                    const otpStatus = document.getElementById('otpStatus');
                    otpStatus.style.display = 'flex';
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

                    if (otpTimer) {
                        clearInterval(otpTimer);
                    }
                } else {
                    // Try local verification as fallback
                    if (enteredOTP === generatedOTP) {
                        isOtpVerified = true;
                        showToast('Phone number verified successfully!', 'success');
                        
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
                        showToast('Invalid OTP. Please try again.', 'error');
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
                    showToast('Invalid OTP. Please try again.', 'error');
                }
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });

        // Resend OTP
        document.getElementById('resendOtpBtn').addEventListener('click', function() {
            // Reset OTP inputs
            document.querySelectorAll('.otp-digit').forEach(input => {
                input.value = '';
                input.disabled = false;
            });
            document.getElementById('otpStatus').style.display = 'none';
            isOtpVerified = false;
            // Clear timer display
            const timerDisplay = document.getElementById('otpTimerDisplay');
            if (timerDisplay) {
                timerDisplay.remove();
            }
            // Resend OTP
            document.getElementById('sendOtpBtn').click();
        });

        // Email OTP state
        let isEmailVerified = false;
        let emailOtpTimer = null;

        const emailInput = document.getElementById('email');
        const emailDisplay = document.getElementById('emailDisplay');

        if (emailInput && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
            document.getElementById('sendEmailOtpBtn').style.display = 'inline-block';
        }

        emailInput.addEventListener('input', function() {
            const email = this.value.trim();
            const sendButton = document.getElementById('sendEmailOtpBtn');
            const emailSection = document.getElementById('emailOtpVerificationSection');
            const emailVerifiedField = document.getElementById('email_verified');
            const emailStatus = document.getElementById('emailOtpStatus');

            if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                sendButton.style.display = 'inline-block';
            } else {
                sendButton.style.display = 'none';
            }

            emailSection.style.display = 'none';

            isEmailVerified = false;
            if (emailVerifiedField) {
                emailVerifiedField.value = '0';
            }
            if (emailStatus) {
                emailStatus.style.display = 'none';
            }
        });

        document.getElementById('sendEmailOtpBtn').addEventListener('click', async function() {
            const email = document.getElementById('email').value.trim();
            const btn = this;

            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showToast('Please enter a valid email address', 'error');
                return;
            }

            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            btn.disabled = true;

            try {
                const response = await fetch('/send-email-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email_id: email,
                        otp_verification_type: 'admission_email_verification'
                    })
                });
                const data = await response.json();

                if (data.success) {
                    showToast('Email OTP sent successfully!', 'success');
                    document.getElementById('emailOtpVerificationSection').style.display = 'block';
                    document.getElementById('emailOtpStatus').style.display = 'none';
                    emailDisplay.textContent = email;
                    startEmailOtpTimer();
                    document.querySelector('.email-otp-digit').focus();
                } else {
                    showToast(data.message || 'Failed to send email OTP', 'error');
                }
            } catch (error) {
                console.error('Error sending email OTP:', error);
                showToast('Failed to send email OTP. Please try again.', 'error');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });

        function startEmailOtpTimer() {
            let timeLeft = 120; // 2 minutes
            document.getElementById('verifyEmailOtpBtn').style.display = 'inline-flex';
            document.getElementById('resendEmailOtpBtn').style.display = 'none';

            // Create timer display if not exists
            let timerDisplay = document.getElementById('emailOtpTimerDisplay');
            if (!timerDisplay) {
                timerDisplay = document.createElement('div');
                timerDisplay.id = 'emailOtpTimerDisplay';
                timerDisplay.style.cssText = 'margin-top: 12px; padding: 10px; background: #EFF6FF; border-left: 4px solid #2563EB; border-radius: 6px; color: #1E40AF; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px;';
                document.getElementById('emailOtpVerificationSection').appendChild(timerDisplay);
            }

            if (emailOtpTimer) {
                clearInterval(emailOtpTimer);
            }

            emailOtpTimer = setInterval(function() {
                timeLeft--;
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerDisplay.innerHTML = `<i class="fas fa-clock" style="color: #2563EB;"></i> OTP expires in: <strong>${minutes}:${seconds.toString().padStart(2, '0')}</strong>`;

                if (timeLeft <= 0) {
                    clearInterval(emailOtpTimer);
                    document.getElementById('verifyEmailOtpBtn').style.display = 'none';
                    document.getElementById('resendEmailOtpBtn').style.display = 'inline-flex';
                    timerDisplay.innerHTML = '<i class="fas fa-hourglass-end" style="color: #DC2626;"></i> <strong>OTP Expired!</strong> Click Resend to get a new OTP.';
                    timerDisplay.style.background = '#FEE2E2';
                    timerDisplay.style.borderLeftColor = '#DC2626';
                    timerDisplay.style.color = '#991B1B';
                }
            }, 1000);
        }

        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('email-otp-digit')) {
                if (e.target.value.length === 1 && /^\d$/.test(e.target.value)) {
                    const nextInput = e.target.nextElementSibling;
                    if (nextInput && nextInput.classList.contains('email-otp-digit')) {
                        nextInput.focus();
                    }
                }
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.target.classList.contains('email-otp-digit')) {
                if (e.key === 'Backspace' && e.target.value === '') {
                    const prevInput = e.target.previousElementSibling;
                    if (prevInput && prevInput.classList.contains('email-otp-digit')) {
                        prevInput.focus();
                    }
                }
            }
        });

        document.getElementById('verifyEmailOtpBtn').addEventListener('click', async function() {
            let enteredOtp = '';
            document.querySelectorAll('.email-otp-digit').forEach(input => {
                enteredOtp += input.value;
            });

            if (enteredOtp.length !== 4) {
                showToast('Please enter the complete 4-digit OTP', 'error');
                return;
            }

            const email = document.getElementById('email').value.trim();
            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showToast('Email address is required for verification', 'error');
                return;
            }

            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';
            btn.disabled = true;

            try {
                const response = await fetch('/verify-email-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: email,
                        otp: enteredOtp,
                        otp_verification_type: 'admission_email_verification'
                    })
                });
                const data = await response.json();

                if (data.success) {
                    isEmailVerified = true;
                    document.getElementById('email_verified').value = '1';
                    showToast('Email verified successfully!', 'success');

                    const emailOtpStatus = document.getElementById('emailOtpStatus');
                    emailOtpStatus.style.display = 'flex';
                    emailOtpStatus.style.background = '#d1fae5';
                    emailOtpStatus.style.color = '#065f46';
                    emailOtpStatus.style.borderLeft = '4px solid #10b981';
                    emailOtpStatus.innerHTML = '<i class="fas fa-check-circle"></i> <strong>Email verified!</strong>';

                    document.querySelectorAll('.email-otp-digit').forEach(input => {
                        input.disabled = true;
                    });
                    document.getElementById('verifyEmailOtpBtn').style.display = 'none';
                    document.getElementById('resendEmailOtpBtn').style.display = 'none';

                    if (emailOtpTimer) {
                        clearInterval(emailOtpTimer);
                    }
                } else {
                    showToast(data.message || 'Invalid OTP. Please try again.', 'error');
                }
            } catch (error) {
                console.error('Error verifying email OTP:', error);
                showToast('Failed to verify email OTP. Please try again.', 'error');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });

        document.getElementById('resendEmailOtpBtn').addEventListener('click', function() {
            document.getElementById('sendEmailOtpBtn').click();
            document.querySelectorAll('.email-otp-digit').forEach(input => {
                input.value = '';
                input.disabled = false;
            });
            document.getElementById('emailOtpStatus').style.display = 'none';
            isEmailVerified = false;
            document.getElementById('email_verified').value = '0';
        });

        // Toast notification function
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
    </script>
</body>
</html>
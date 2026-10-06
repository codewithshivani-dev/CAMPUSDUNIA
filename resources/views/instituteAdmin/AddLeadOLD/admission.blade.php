<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Enrollment Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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
                            <select name="applying_for_grade" required>
                                <option value="" disabled {{ old('applying_for_grade') ? '' : 'selected' }}>— Select Grade —</option>
                                @foreach($admissionclass as $class)
                                    <option value="{{ $class->finacp_merchant_sub_category_id }}" {{ old('applying_for_grade') == $class->finacp_merchant_sub_category_id ? 'selected' : '' }}>
                                        {{ $class->finacp_merchant_sub_category_type ?? $class->course_type ?? $class->name ?? 'Unknown' }}
                                    </option>
                                @endforeach
                            </select>
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
                            <label class="required-label">Parent/Guardian Full Name</label>
                            <input type="text" name="parent_full_name" placeholder="Full name" value="{{ old('parent_full_name') }}" required />
                        </div>

                        <div class="form-group">
                            <label class="required-label">Relationship to Student</label>
                            <input type="text" name="relationship" placeholder="e.g., Father / Mother / Guardian" value="{{ old('relationship') }}" required />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required-label">Occupation</label>
                            <input type="text" name="parent_occupation" placeholder="e.g., Engineer, Business, Homemaker" value="{{ old('parent_occupation') }}" required />
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
                            <label class="required-label">WhatsApp Number</label>
                            <input type="tel" name="whatsapp_number" placeholder="WhatsApp contact number" value="{{ old('whatsapp_number') }}" required />
                        </div>

                        <div class="form-group">
                            <label class="required-label">Email Address</label>
                            <input type="email" name="email" placeholder="Email address" value="{{ old('email') }}" required />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required-label">Phone Number</label>
                            <input type="tel" name="phone" placeholder="Contact number" value="{{ old('phone') }}" required />
                        </div>

                        <div class="form-group">
                            <label>Alternate Phone</label>
                            <input type="tel" name="alternate_phone" placeholder="Alternate contact number" value="{{ old('alternate_phone') }}" />
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
                        <div class="form-group-full">
                            <label class="required-label">Complete Address</label>
                            <textarea name="address" rows="2" placeholder="House no, Street, Area, Landmark" required>{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required-label">City</label>
                            <input type="text" name="city" placeholder="City name" value="{{ old('city') }}" required />
                        </div>

                        <div class="form-group">
                            <label class="required-label">State</label>
                            <input type="text" name="state" placeholder="State name" value="{{ old('state') }}" required />
                        </div>

                        <div class="form-group">
                            <label class="required-label">PIN Code</label>
                            <input type="text" name="pincode" placeholder="6-digit PIN" maxlength="6" value="{{ old('pincode') }}" required />
                        </div>
                    </div>
                </div>

                <!-- Hidden fields for RegistrationController -->
                <input type="hidden" name="is_initial_lead" value="1" />
                <input type="hidden" name="is_complete_application" value="0" />
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
    </script>
</body>
</html>
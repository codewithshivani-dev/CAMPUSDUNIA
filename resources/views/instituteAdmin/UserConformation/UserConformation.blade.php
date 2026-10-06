@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Application - FlyHI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>

        .form-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .form-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1a2a3a, #2c3e50);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -0.3px;
        }

        .form-header p {
            color: #5a6874;
            font-size: 1rem;
            margin-top: 0.5rem;
        }

        .form-card {
            background: white;
            border-radius: 28px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .form-card:hover {
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.12);
        }

        .card-header-custom {
            background: linear-gradient(98deg, #ffffff 0%, #f8fafc 100%);
            padding: 1.25rem 1.8rem;
            border-bottom: 2px solid #eef2f6;
        }

        .card-header-custom h3 {
            font-size: 1.35rem;
            font-weight: 700;
            margin: 0;
            color: #1e2f3f;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .card-header-custom h3 i {
            color: #3b82f6;
            font-size: 1.5rem;
        }

        .form-body {
            padding: 1.8rem 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label i {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .required-star {
            color: #dc3545;
            margin-left: 4px;
        }

        .form-control, .form-select {
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            padding: 0.7rem 1rem;
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59,130,246,0.1);
            outline: none;
        }

        .input-group-text {
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            font-weight: 500;
        }

        .editable-indicator {
            background: #fef9e6;
            border-left: 4px solid #f59e0b;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            margin-bottom: 1.2rem;
            font-size: 0.85rem;
            color: #b45309;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .detail-row {
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #555;
        }
        .detail-value {
            color: #333;
            word-break: break-word;
        }

        .badge-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .badge-editable {
            background-color: #fef3c7;
            color: #d97706;
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 30px;
            font-weight: 600;
            margin-left: 10px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            padding: 14px 40px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s;
            box-shadow: 0 6px 14px rgba(16,185,129,0.3);
        }

        .btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(16,185,129,0.4);
        }

        .btn-edit, .btn-back {
            background: white;
            border: 1.5px solid #cbd5e1;
            padding: 12px 32px;
            border-radius: 50px;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-edit:hover, .btn-back:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
            color: #1e2f3f;
        }

        .declaration-box {
            background-color: #f8f9fa;
            border-left: 4px solid #ffc107;
            padding: 1rem;
            margin: 1rem 0;
        }

        .section-icon {
            margin-right: 10px;
        }

        .row-custom {
            margin-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            .form-body {
                padding: 1.5rem;
            }
            .form-header h1 {
                font-size: 1.8rem;
            }
            .d-flex {
                flex-direction: column;
                gap: 1rem;
            }
            .btn-edit, .btn-submit, .btn-back {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
<div class="form-container">
    <div class="form-header">
        <h1><i class="bi bi-journal-bookmark-fill me-2"></i>Complete Application & Employment Details</h1>
        <p>Please review all information. Fields marked with <span class="text-danger">*</span> are required.</p>
    </div>

    @php
        // Dummy Data for all sections
        
        $signupDetails = $data['signup_details'];
        $instituteDetails = $data['institute_details'];
        $studentDetails = $data['student_details'];
        
        $consentDate = date('d M Y, h:i A', strtotime($signupDetails['consent_timestamp']));
        $studentDob = date('d M Y', strtotime($studentDetails['student_dob']));
        $dob = new DateTime($studentDetails['student_dob']);
        $today = new DateTime();
        $age = $today->diff($dob)->y;
        
        // Employment default data
        $employment_details = $data['personal_employment_details'];
    @endphp

    <form action="{{ route('loan.journey.pre.loan.sanction.request.details') }}" method="POST" id="fullApplicationForm">
        @csrf
        
        {{-- Hidden fields for all data --}}
        @foreach($signupDetails as $key => $value)
            <input type="hidden" name="signup_details[{{ $key }}]" value="{{ $value }}">
        @endforeach
        @foreach($instituteDetails as $key => $value)
            <input type="hidden" name="institute_details[{{ $key }}]" value="{{ $value }}">
        @endforeach
        @foreach($studentDetails as $key => $value)
            <input type="hidden" name="student_details[{{ $key }}]" value="{{ $value }}">
        @endforeach
        @foreach($employment_details as $key => $value)
            <input type="hidden" name="employment_details[{{ $key }}]" id="hidden_{{ $key }}" value="{{ $value }}">
        @endforeach

        {{-- ==================== SECTION 1: APPLICATION SUMMARY (Read-only confirmation) ==================== --}}
        <div class="form-card">
            <div class="card-header-custom">
                <h3><i class="bi bi-check-circle-fill text-success"></i> Application Summary</h3>
            </div>
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-row">
                            <div class="detail-label">Applicant Name</div>
                            <div class="detail-value"><strong>{{ $signupDetails['applicant_name'] }}</strong></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Mobile Number</div>
                            <div class="detail-value"><strong>{{ $signupDetails['mobile_number'] }}</strong></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Email ID</div>
                            <div class="detail-value"><strong>{{ $signupDetails['email_id'] }}</strong></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">PAN Number</div>
                            <div class="detail-value"><strong>{{ $signupDetails['pan_number'] }}</strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-row">
                            <div class="detail-label">Institute Name</div>
                            <div class="detail-value"><strong>{{ $instituteDetails['institute_name'] }}</strong></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Course</div>
                            <div class="detail-value"><strong>{{ $instituteDetails['institute_course_id'] }}</strong></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Loan Amount</div>
                            <div class="detail-value"><strong>₹ {{ number_format($instituteDetails['loan_amount']) }}</strong></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Tenure</div>
                            <div class="detail-value"><strong>{{ $instituteDetails['scheme_tenure'] }}</strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== SECTION 2: STUDENT DETAILS (Read-only) ==================== --}}
        <div class="form-card">
            <div class="card-header-custom">
                <h3><i class="bi bi-person-badge"></i> Student Details</h3>
            </div>
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-row">
                            <div class="detail-label">Student Name</div>
                            <div class="detail-value"><strong>{{ $studentDetails['student_name'] }}</strong></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Date of Birth</div>
                            <div class="detail-value"><strong>{{ $studentDob }}</strong> <span class="text-muted">(Age: {{ $age }} years)</span></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-row">
                            <div class="detail-label">Enrollment Type</div>
                            <div class="detail-value"><span class="badge bg-info">{{ $studentDetails['enrollment_type'] }}</span></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Relationship with Applicant</div>
                            <div class="detail-value"><strong>{{ $studentDetails['relationship_with_applicant'] }}</strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== SECTION 3: ADDRESS DETAILS (Read-only) ==================== --}}
        <div class="form-card">
            <div class="card-header-custom">
                <h3><i class="bi bi-geo-alt-fill"></i> Residential Address</h3>
            </div>
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="detail-row">
                            <div class="detail-label">Current Residential Address</div>
                            <div class="detail-value"><strong>{{ $signupDetails['current_residential_address'] }}</strong></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">City</div>
                            <div class="detail-value"><strong>{{ $signupDetails['city'] }}</strong></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">State</div>
                            <div class="detail-value"><strong>{{ $signupDetails['state'] }}</strong></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Pincode</div>
                            <div class="detail-value"><strong>{{ $signupDetails['pincode'] }}</strong></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Consent Timestamp</div>
                            <div class="detail-value"><strong>{{ $consentDate }}</strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== SECTION 4: PERSONAL & EMPLOYMENT DETAILS (Editable Form) ==================== --}}
        <div class="form-card">
            <div class="card-header-custom">
                <h3><i class="bi bi-person-badge"></i> Personal Information <span class="badge-editable"><i class="bi bi-pencil-square"></i> Editable</span></h3>
            </div>
            <div class="form-body">
                <div class="editable-indicator">
                    <i class="bi bi-info-circle-fill"></i> Marital Status, Number of Dependents & Monthly Family Income can be edited
                </div>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-heart"></i> Marital Status <span class="required-star">*</span></label>
                            <select name="marital_status" id="marital_status" class="form-select" data-hidden-field="marital_status">
                                <option value="Single" {{ $employment_details['marital_status'] == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ $employment_details['marital_status'] == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Divorced" {{ $employment_details['marital_status'] == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="Widowed" {{ $employment_details['marital_status'] == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-people"></i> Number of Dependents <span class="required-star">*</span></label>
                            <input type="number" name="no_of_dependents" id="no_of_dependents" class="form-control" value="{{ $employment_details['no_of_dependents'] }}" min="0" max="20" data-hidden-field="no_of_dependents">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-briefcase"></i> Earning Family Members</label>
                            <input type="number" name="no_of_earning_family_members" id="no_of_earning_family_members" class="form-control" value="{{ $employment_details['no_of_earning_family_members'] }}" min="0" data-hidden-field="no_of_earning_family_members">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-currency-rupee"></i> Monthly Family Income (₹) <span class="required-star">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="monthly_family_income" id="monthly_family_income" class="form-control" value="{{ $employment_details['monthly_family_income'] }}" min="0" step="1000" data-hidden-field="monthly_family_income">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- EMI & Loan Obligations --}}
        <div class="form-card">
            <div class="card-header-custom">
                <h3><i class="bi bi-credit-card-2-front"></i> EMI & Loan Obligations</h3>
            </div>
            <div class="form-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-receipt"></i> Number of EMIs Currently <span class="required-star">*</span></label>
                            <input type="number" name="no_of_emis_currently" id="no_of_emis_currently" class="form-control" value="{{ $employment_details['no_of_emis_currently'] }}" min="0" data-hidden-field="no_of_emis_currently">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-cash-stack"></i> Total Monthly EMI Amount (₹) <span class="required-star">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="total_monthly_emi_amount" id="total_monthly_emi_amount" class="form-control" value="{{ $employment_details['total_monthly_emi_amount'] }}" min="0" step="500" data-hidden-field="total_monthly_emi_amount">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Employment Details --}}
        <div class="form-card">
            <div class="card-header-custom">
                <h3><i class="bi bi-building"></i> Employment & Work History</h3>
            </div>
            <div class="form-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-briefcase-fill"></i> Employment Type <span class="required-star">*</span></label>
                            <select name="employment_type" id="employment_type" class="form-select" data-hidden-field="employment_type">
                                <option value="Salaried" {{ $employment_details['employment_type'] == 'Salaried' ? 'selected' : '' }}>Salaried</option>
                                <option value="Self-Employed" {{ $employment_details['employment_type'] == 'Self-Employed' ? 'selected' : '' }}>Self-Employed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-building"></i> Employer / Business Name <span class="required-star">*</span></label>
                            <input type="text" name="employer_business_name" id="employer_business_name" class="form-control" value="{{ $employment_details['employer_business_name'] }}" data-hidden-field="employer_business_name">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-geo-alt"></i> Employer / Business Address</label>
                            <input type="text" name="employer_business_address" id="employer_business_address" class="form-control" value="{{ $employment_details['employer_business_address'] }}" data-hidden-field="employer_business_address">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-calendar-check"></i> Current Experience (Years)</label>
                            <input type="number" name="current_work_experience_years" id="current_work_experience_years" class="form-control" value="{{ $employment_details['current_work_experience_years'] }}" min="0" data-hidden-field="current_work_experience_years">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-clock-history"></i> Total Experience (Years)</label>
                            <input type="number" name="total_work_experience_years" id="total_work_experience_years" class="form-control" value="{{ $employment_details['total_work_experience_years'] }}" min="0" data-hidden-field="total_work_experience_years">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Confirmation & Declaration --}}
        <div class="form-card">
            <div class="card-header-custom">
                <h3><i class="bi bi-check-circle"></i> Declaration & Submission</h3>
            </div>
            <div class="form-body">
                <div class="declaration-box">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="confirmCheck" required>
                        <label class="form-check-label fw-semibold" for="confirmCheck">
                            I confirm that all the above information is correct and complete.
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="declarationCheck" required>
                        <label class="form-check-label fw-semibold" for="declarationCheck">
                            I declare that the information provided is true to the best of my knowledge.
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="termsCheck" required>
                        <label class="form-check-label fw-semibold" for="termsCheck">
                            I agree to the terms and conditions and authorize FlyHI to verify the information provided.
                        </label>
                    </div>
                </div>
                <div class="d-flex justify-content-between gap-3">
                    <a href="#" class="btn-edit"><i class="bi bi-arrow-left me-2"></i>Back</a>
                    <button type="submit" class="btn-submit text-white" id="submitBtn" disabled>
                        <i class="bi bi-send-check me-2"></i>Confirm & Submit Application
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Update hidden fields when form inputs change
    function updateHiddenFields() {
        document.querySelectorAll('[data-hidden-field]').forEach(input => {
            const hiddenFieldName = input.getAttribute('data-hidden-field');
            const hiddenInput = document.getElementById(`hidden_${hiddenFieldName}`);
            if (hiddenInput) {
                hiddenInput.value = input.value;
            }
        });
    }
    
    // Add event listeners to all editable fields
    const editableFields = ['marital_status', 'no_of_dependents', 'no_of_earning_family_members', 'monthly_family_income', 
                            'no_of_emis_currently', 'total_monthly_emi_amount', 'employment_type', 'employer_business_name',
                            'employer_business_address', 'current_work_experience_years', 'current_work_experience_months',
                            'total_work_experience_years', 'total_work_experience_months'];
    
    editableFields.forEach(field => {
        const element = document.getElementById(field);
        if (element) {
            element.addEventListener('input', function() {
                updateHiddenFields();
            });
            element.addEventListener('change', function() {
                updateHiddenFields();
            });
        }
    });
    
    // Checkbox validation for submit button
    const confirmCheck = document.getElementById('confirmCheck');
    const declarationCheck = document.getElementById('declarationCheck');
    const termsCheck = document.getElementById('termsCheck');
    const submitBtn = document.getElementById('submitBtn');
    
    function toggleSubmitButton() {
        if (confirmCheck.checked && declarationCheck.checked && termsCheck.checked) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }
    
    confirmCheck.addEventListener('change', toggleSubmitButton);
    declarationCheck.addEventListener('change', toggleSubmitButton);
    termsCheck.addEventListener('change', toggleSubmitButton);
    
    // Form submission handler
    const form = document.getElementById('fullApplicationForm');
    form.addEventListener('submit', function(e) {

        if (!confirmCheck.checked || !declarationCheck.checked || !termsCheck.checked) {
            e.preventDefault(); // ❗ only block if invalid
            alert('Please check all confirmation boxes before submitting.');
            return false;
        }

        updateHiddenFields();

        // Optional: show loader
        submitBtn.disabled = true;
        submitBtn.innerText = "Submitting...";
    });
    
    // Validate months fields
    function validateMonths() {
        const currentMonths = document.getElementById('current_work_experience_months');
        if (currentMonths && parseInt(currentMonths.value) > 11) currentMonths.value = 11;
        if (currentMonths && parseInt(currentMonths.value) < 0) currentMonths.value = 0;
    }
    
    document.getElementById('current_work_experience_months')?.addEventListener('change', validateMonths);
    
    // Initialize
    updateHiddenFields();
    
    // Animation on load
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.form-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.4s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 80);
        });
    });
</script>
@endsection
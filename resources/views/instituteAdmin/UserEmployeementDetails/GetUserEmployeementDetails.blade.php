@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Employment Details Form - FlyHI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f0f2f5 0%, #e3e8ee 100%);
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            padding: 2rem 0 3rem 0;
        }

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

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(16,185,129,0.4);
        }

        .btn-reset {
            background: white;
            border: 1.5px solid #cbd5e1;
            padding: 12px 32px;
            border-radius: 50px;
            font-weight: 600;
            color: #475569;
        }

        .btn-reset:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
        }

        .section-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #cbd5e1, transparent);
            margin: 0.5rem 0 1.5rem;
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

        @media (max-width: 768px) {
            .form-body {
                padding: 1.5rem;
            }
            .form-header h1 {
                font-size: 1.8rem;
            }
        }

        .info-note {
            background: #eff6ff;
            border-radius: 16px;
            padding: 1rem 1.2rem;
            margin-top: 1rem;
            font-size: 0.85rem;
            border-left: 4px solid #3b82f6;
        }
    </style>
</head>
<body>
<div class="form-container">
    <div class="form-header">
        <h1><i class="bi bi-journal-bookmark-fill me-2"></i>Employment & Personal Information</h1>
        <p>Please fill in all details accurately. Fields marked with <span class="text-danger">*</span> are required.</p>
    </div>

    <form action="#" method="POST" id="fullDetailsForm">
        @csrf

        {{-- ======================== SECTION 1: PERSONAL INFORMATION (Editable fields) ======================== --}}
        <div class="form-card">
            <div class="card-header-custom">
                <h3><i class="bi bi-person-badge"></i> Personal Information <span class="badge-editable"><i class="bi bi-pencil-square"></i> Editable</span></h3>
            </div>
            <div class="form-body">
                <div class="editable-indicator">
                    <i class="bi bi-info-circle-fill"></i> Marital Status, Number of Dependents & Monthly Family Income can be edited later
                </div>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-heart"></i> Marital Status <span class="required-star">*</span></label>
                            <select name="marital_status" id="marital_status" class="form-select" required>
                                <option value="Single">Single</option>
                                <option value="Married" selected>Married</option>
                                <option value="Divorced">Divorced</option>
                                <option value="Widowed">Widowed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-people"></i> Number of Dependents <span class="required-star">*</span></label>
                            <input type="number" name="no_of_dependents" id="no_of_dependents" class="form-control" value="2" min="0" max="20" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-briefcase"></i> Earning Family Members</label>
                            <input type="number" name="no_of_earning_family_members" id="no_of_earning_family_members" class="form-control" value="1" min="0" step="1">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-currency-rupee"></i> Monthly Family Income (₹) <span class="required-star">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="monthly_family_income" id="monthly_family_income" class="form-control" value="85000" min="0" step="1000" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ======================== SECTION 2: EMI & LIABILITIES ======================== --}}
        <div class="form-card">
            <div class="card-header-custom">
                <h3><i class="bi bi-credit-card-2-front"></i> EMI & Loan Obligations</h3>
            </div>
            <div class="form-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-receipt"></i> Number of EMIs Currently <span class="required-star">*</span></label>
                            <input type="number" name="no_of_emis_currently" id="no_of_emis_currently" class="form-control" value="2" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-cash-stack"></i> Total Monthly EMI Amount (₹) <span class="required-star">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="total_monthly_emi_amount" id="total_monthly_emi_amount" class="form-control" value="12500" min="0" step="500" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="info-note">
                    <i class="bi bi-graph-up me-2"></i> <strong>Debt-to-Income Ratio Preview:</strong> Based on current inputs, your DTI will be calculated automatically.
                </div>
            </div>
        </div>

        {{-- ======================== SECTION 3: EMPLOYMENT DETAILS (Complete fields) ======================== --}}
        <div class="form-card">
            <div class="card-header-custom">
                <h3><i class="bi bi-building"></i> Employment & Work History</h3>
            </div>
            <div class="form-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-briefcase-fill"></i> Employment Type <span class="required-star">*</span></label>
                            <select name="employment_type" id="employment_type" class="form-select" required>
                                <option value="SALARIED">Salaried</option>
                                <option value="SELF EMPLOYED">Self-Employed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-building"></i> Employer / Business Name <span class="required-star">*</span></label>
                            <input type="text" name="employer_business_name" id="employer_business_name" class="form-control" value="Tech Solutions Ltd." placeholder="e.g., Google, Self-employed" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-geo-alt"></i> Employer / Business Address</label>
                            <input type="text" name="employer_business_address" id="employer_business_address" class="form-control" value="MG Road, Bengaluru - 560001" placeholder="Full office address">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-calendar-check"></i> Current Work Experience (Years)</label>
                            <input type="number" name="current_work_experience_years" id="current_work_experience_years" class="form-control" value="4" min="0" step="1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-clock-history"></i> Total Work Experience (Years)</label>
                            <input type="number" name="total_work_experience_years" id="total_work_experience_years" class="form-control" value="5" min="0" step="1">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- {{-- ======================== SECTION 4: FINANCIAL SUMMARY & DTI CALCULATOR ======================== --}}
        <div class="form-card">
            <div class="card-header-custom">
                <h3><i class="bi bi-pie-chart"></i> Financial Health Summary</h3>
            </div>
            <div class="form-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="bg-light rounded-4 p-3 text-center">
                            <small class="text-muted">Monthly Income</small>
                            <h4 class="mb-0 text-success" id="liveIncomeDisplay">₹ 85,000</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light rounded-4 p-3 text-center">
                            <small class="text-muted">Monthly EMI</small>
                            <h4 class="mb-0 text-danger" id="liveEmiDisplay">₹ 12,500</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light rounded-4 p-3 text-center">
                            <small class="text-muted">Debt-to-Income Ratio</small>
                            <h4 class="mb-0" id="liveRatioDisplay">14.7%</h4>
                            <span id="ratioBadge" class="badge bg-success mt-1">Excellent</span>
                        </div>
                    </div>
                </div>
                <div class="section-divider"></div>
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="bi bi-shield-check fs-4 me-3"></i>
                    <div id="recommendationText">
                        Your debt-to-income ratio is very healthy. You have strong eligibility for loans.
                    </div>
                </div>
            </div>
        </div> -->

        {{-- ======================== SECTION 5: CONFIRMATION & DECLARATION ======================== --}}
        <div class="form-card">
            <div class="card-header-custom">
                <h3><i class="bi bi-check-circle"></i> Declaration & Submission</h3>
            </div>
            <div class="form-body">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="confirmCheck" required>
                    <label class="form-check-label fw-semibold" for="confirmCheck">
                        I confirm that all the above information is correct and complete.
                    </label>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="declarationCheck" required>
                    <label class="form-check-label fw-semibold" for="declarationCheck">
                        I declare that the information provided is true to the best of my knowledge.
                    </label>
                </div>
                <div class="d-flex justify-content-between gap-3">
                    <button type="button" class="btn btn-reset" id="resetBtn"><i class="bi bi-arrow-repeat me-2"></i>Reset Form</button>
                    <button type="submit" class="btn btn-submit text-white" id="submitBtn" disabled>
                        <i class="bi bi-send-check me-2"></i>Submit Application
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Helper: format INR
    function formatIndianCurrency(amount) {
        return new Intl.NumberFormat('en-IN').format(amount);
    }
    
    // Checkbox validations for submit button
    const confirmCheck = document.getElementById('confirmCheck');
    const declarationCheck = document.getElementById('declarationCheck');
    const submitBtn = document.getElementById('submitBtn');
    
    function toggleSubmitButton() {
        if (confirmCheck.checked && declarationCheck.checked) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }
    
    confirmCheck.addEventListener('change', toggleSubmitButton);
    declarationCheck.addEventListener('change', toggleSubmitButton);
    
    // Reset all form fields to default values (original dummy data)
    const resetBtn = document.getElementById('resetBtn');
    resetBtn.addEventListener('click', function() {
        // Reset personal info
        document.getElementById('marital_status').value = 'Married';
        document.getElementById('no_of_dependents').value = '2';
        document.getElementById('no_of_earning_family_members').value = '1';
        document.getElementById('monthly_family_income').value = '85000';
        // EMI
        document.getElementById('no_of_emis_currently').value = '2';
        document.getElementById('total_monthly_emi_amount').value = '12500';
        // Employment
        document.getElementById('employment_type').value = 'Salaried';
        document.getElementById('employer_business_name').value = 'Tech Solutions Ltd.';
        document.getElementById('employer_business_address').value = 'MG Road, Bengaluru - 560001';
        document.getElementById('current_work_experience_years').value = '4';
        document.getElementById('current_work_experience_months').value = '6';
        document.getElementById('total_work_experience_years').value = '5';
        document.getElementById('total_work_experience_months').value = '2';
        
        // Uncheck checkboxes and disable submit
        confirmCheck.checked = false;
        declarationCheck.checked = false;
        toggleSubmitButton();
        
        
        // Optional small visual feedback
        const cards = document.querySelectorAll('.form-card');
        cards[0].style.transform = 'scale(0.99)';
        setTimeout(() => { cards[0].style.transform = ''; }, 150);
    });
    
    // Form submission handler
    const form = document.getElementById('fullDetailsForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!confirmCheck.checked || !declarationCheck.checked) {
            alert('Please accept both confirmation and declaration checkboxes to proceed.');
            return false;
        }
        
        // Collect all form data
        const formData = new FormData(form);
        const dataObject = {};
        for (let [key, value] of formData.entries()) {
            dataObject[key] = value;
        }
        
        console.log('Submitted Employment Data:', dataObject);
        
        // Show success modal style alert
        alert('✅ Application submitted successfully!\n\nYour employment & personal details have been recorded.\nReference: FLYHI-EMP-' + Math.floor(Math.random() * 10000));
        
        // Optionally you can redirect or do an AJAX post here
        // For demo, we just log and show success.
        
        // If you want to enable actual redirect, uncomment below
        // window.location.href = '/thank-you';
    });


</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
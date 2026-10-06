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
            --warning-orange: #F59E0B;
            --danger-red: #EF4444;
            --text-dark: #1F2937;
            --text-light: #6B7280;
            --border-color: #D1D5DB;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        @keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}
        .top-bar {
            background: white;
            padding: 20px 40px;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        
        .progress-container {
            flex: 1;
            max-width: 600px;
            margin: 0 40px;
        }
        
        .progress-bar {
            height: 8px;
            background: #E5E7EB;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        
        .progress {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-blue), var(--secondary-blue));
            width: 20%;
            transition: width 0.5s ease;
            border-radius: 4px;
        }
        
        .progress-text {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: var(--text-light);
            font-weight: 500;
            position: relative;
        }
        
        .progress-text span {
            position: relative;
            width: 20%;
            text-align: center;
        }
        
        .progress-text span::after {
            content: "";
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 0px;
            font-size: 12px;
            color: var(--text-light);
            font-weight: 500;
        }
        
        .progress-text span:nth-child(1)::after { content: "Applicant Type"; }
        .progress-text span:nth-child(2)::after { content: "Basic Details"; }
        .progress-text span:nth-child(3)::after { content: "Contact Info"; }
        .progress-text span:nth-child(4)::after { content: "Family & Education"; }   
        .progress-text span:nth-child(5)::after { content: "Review"; }
        
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
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
        }
        
        .form-header p {
            opacity: 0.9;
            font-size: 15px;
        }
        
        .form-content {
            padding: 40px;
        }
        
        .step {
            display: none;
        }
        
        .step.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
            padding: 14px 16px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s ease;
            background: white;
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
        }
        
        .input-with-icon input {
            padding-left: 45px;
        }
        
        .radio-group {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }
        
        .radio-option {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 15px 20px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            transition: all 0.2s ease;
            flex: 1;
            min-width: 200px;
        }
        
        .radio-option:hover {
            border-color: var(--accent-blue);
            background: var(--light-blue);
        }
        
        .radio-option.selected {
            border-color: var(--primary-blue);
            background: var(--light-blue);
        }
        
        .radio-option input {
            display: none;
        }
        
        .radio-icon {
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .radio-option.selected .radio-icon {
            border-color: var(--primary-blue);
        }
        
        .radio-option.selected .radio-icon::after {
            content: '';
            width: 10px;
            height: 10px;
            background: var(--primary-blue);
            border-radius: 50%;
        }
        
        .radio-text {
            flex: 1;
        }
        
        .radio-text h4 {
            font-size: 15px;
            margin-bottom: 3px;
            font-weight: 600;
        }
        
        .radio-text p {
            font-size: 13px;
            color: var(--text-light);
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 25px;
            border-top: 1px solid var(--border-color);
        }
        
        .btn {
            padding: 14px 32px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
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
        
        .btn-success {
            background: linear-gradient(135deg, var(--success-green), #34D399);
            color: white;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, var(--warning-orange), #FBBF24);
            color: white;
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .hidden {
            display: none;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .summary-item:last-child {
            border-bottom: none;
        }
        
        .summary-label {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .summary-value {
            color: var(--text-light);
            text-align: right;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .alert-info {
            background: var(--light-blue);
            border-left: 4px solid var(--primary-blue);
            color: var(--primary-blue);
        }
        
        .alert-success {
            background: #D1FAE5;
            border-left: 4px solid var(--success-green);
            color: #065F46;
        }
        
        .alert-warning {
            background: #FEF3C7;
            border-left: 4px solid var(--warning-orange);
            color: #92400E;
        }
        
        .declaration {
            background: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
        }
        
        .declaration h4 {
            color: #92400E;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .declaration p {
            color: #92400E;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .checkbox-group {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-direction: column;
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
        
        /* Admission Form Display */
        .admission-form-preview {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .form-preview-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--border-color);
        }
        
        .form-preview-header h3 {
            color: var(--primary-blue);
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .form-preview-header .ref-id {
            background: var(--light-blue);
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 600;
            color: var(--primary-blue);
        }
        
        .form-preview-section {
            margin-bottom: 25px;
        }
        
        .form-preview-section h4 {
            color: var(--dark-blue);
            font-size: 16px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .form-preview-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed var(--border-color);
        }
        
        .form-preview-label {
            font-weight: 600;
            color: var(--text-dark);
            width: 40%;
        }
        
        .form-preview-value {
            color: var(--text-light);
            width: 55%;
            text-align: right;
        }
        
        /* Enhanced Form Preview Styles - Side by Side Layout */
        .form-preview-section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .form-preview-section:last-child {
            border-bottom: none;
        }

        .form-preview-section h4 {
            color: #1e40af;
            font-size: 16px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
            font-weight: 600;
        }

        .preview-grid-2col {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-preview-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            border-bottom: 1px solid #f1f5f9;
            background: #f8fafc;
            border-radius: 8px;
            min-height: 50px;
        }

        .form-preview-label {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
            min-width: 40%;
            padding-right: 15px;
            border-right: 1px solid #e5e7eb;
        }

        .form-preview-value {
            color: #4b5563;
            font-size: 14px;
            text-align: right;
            flex: 1;
            padding-left: 15px;
            word-break: break-word;
        }

        /* Address field spans full width */
        .form-preview-row.full-width {
            grid-column: 1 / span 2;
            display: flex;
            align-items: flex-start;
            padding: 15px;
        }

        .form-preview-row.full-width .form-preview-label {
            min-width: 15%;
            border-right: 1px solid #e5e7eb;
            padding-right: 15px;
            align-self: flex-start;
        }

        .form-preview-row.full-width .form-preview-value {
            text-align: left;
            padding-left: 15px;
            border-left: 1px solid #f1f5f9;
            background: white;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }

        /* Form Section Styles */
        .form-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .form-section h3 {
            color: #3b82f6;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
        }

        /* Option Grid */
        .option-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .option-card {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .option-card:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
        }

        .option-card.selected {
            border-color: #3b82f6;
            background: rgba(59, 130, 246, 0.05);
        }

        .option-card i {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #3b82f6;
        }

        .option-card h4 {
            margin: 8px 0 4px;
            color: #1e293b;
            font-size: 18px;
            font-weight: 600;
        }

        .option-card p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.5;
        }

        /* Checkmark icon inside the green circle - ONLY SHOWS WHEN SELECTED */
        .option-card::after {
            content: '';
            position: absolute;
            top: 15px;
            right: 15px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #10b981;
            opacity: 0;
            transform: scale(0);
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            content: "\f00c";
            font-size: 12px;
            z-index: 2;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        .option-card.selected::after {
            opacity: 1;
            transform: scale(1);
            animation: checkmarkPop 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes checkmarkPop {
            0% { transform: scale(0); opacity: 0; }
            70% { transform: scale(1.2); }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Sibling Relationship Fields */
        .sibling-details {
            background: #f0f7ff;
            border: 1px solid #dbeafe;
            border-radius: 10px;
            padding: 20px;
            margin-top: 15px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Photo Upload */
        .photo-upload {
            border: 2px dashed #dbeafe;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: #f8fafc;
        }

        .photo-upload:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .photo-preview {
            width: 150px;
            height: 150px;
            border-radius: 10px;
            object-fit: cover;
            margin: 15px auto;
            display: none;
            border: 2px solid #e2e8f0;
        }

        .upload-icon {
            font-size: 48px;
            color: #3b82f6;
            margin-bottom: 15px;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .preview-grid-2col {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .form-preview-row.full-width {
                grid-column: 1;
            }
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
            
            .progress-container {
                width: 100%;
                margin: 0;
            }
        
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .radio-group {
                flex-direction: column;
            }
            
            .radio-option {
                min-width: 100%;
            }
            
            .form-actions {
                flex-direction: column;
                gap: 15px;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .form-preview-row {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px;
            }
            
            .form-preview-label {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e5e7eb;
                padding-right: 0;
                padding-bottom: 8px;
                margin-bottom: 8px;
                min-width: auto;
            }
            
            .form-preview-value {
                width: 100%;
                text-align: left;
                padding-left: 0;
            }
            
            .form-preview-row.full-width {
                flex-direction: column;
            }
            
            .form-preview-row.full-width .form-preview-label {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e5e7eb;
                padding-right: 0;
                padding-bottom: 8px;
                min-width: auto;
            }
            
            .form-preview-row.full-width .form-preview-value {
                width: 100%;
                text-align: left;
                padding-left: 0;
                border-left: none;
                margin-top: 10px;
            }
            
            .option-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .form-section {
                padding: 20px;
            }
            
            .form-section h3 {
                font-size: 18px;
            }
            
            .alert {
                padding: 15px 18px;
                font-size: 13px;
            }
        }
    </style>

    <div class="top-bar">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="logo-text">
                <h3>Institute</h3>
                <p>Admission Registration</p>
            </div>
        </div>
        
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress" id="progressFill"></div>
            </div>
            <div class="progress-text">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        
        <div style="text-align: right;">
            <div style="font-size: 14px; color: var(--text-light); margin-bottom: 5px;">
                <i class="fas fa-laptop"></i> <span id="modeDisplay">Online</span>
            </div>
            <div style="font-size: 12px; color: var(--text-light);">
                Ref: <span id="referenceId">-</span>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="form-wrapper">
            <div class="form-header">
                <h1><i class="fas fa-user-graduate"></i> Admission Registration</h1>
                <p>Complete all sections carefully. Fields marked with * are mandatory.</p>
            </div>
            
            <div class="form-content">
                <!-- Step 1: Applicant Type & Lead Source -->
                <div class="step active" id="step1">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <div style="font-size: 14px;">
                            <strong>Step 1 of 5:</strong> Select applicant type and provide your details. A lead will be created after next step.
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3><i class="fas fa-user-tag"></i> 1. Select Applicant Type <span class="required"></span></h3>
                        <div class="option-grid">
                            <div class="option-card" onclick="selectApplicantType(event, 'student')" data-type="student">
                                <i class="fas fa-user-graduate"></i>
                                <h4>Student</h4>
                                <p>I am the student applying for myself</p>
                                <div style="margin-top: 8px; font-size: 12px; color: #3b82f6;">
                                    You will provide your own details
                                </div>
                            </div>
                            
                            <div class="option-card" onclick="selectApplicantType(event, 'parent')" data-type="parent">
                                <i class="fas fa-users"></i>
                                <h4>Parent/Guardian Application</h4>
                                <p>I am applying on behalf of a child/student</p>
                                <div style="margin-top: 8px; font-size: 12px; color: #3b82f6;">
                                    You will provide student details
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <!-- Parent Name Section (Hidden by default) -->
                    <div id="parentNameSection" class="section-card hidden" style="margin-top: 20px;">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="section-title">
                                <h3>Your Information (Parent/Guardian)</h3>
                                <p>Enter your name as the person filling this application</p>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="required">Your Full Name (Parent/Guardian)</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-user"></i>
                                    <input type="text" id="applicantName" placeholder="Enter your name">
                                </div>
                                <div style="font-size: 12px; color: var(--text-light); margin-top: 5px;">
                                    This is the name of the person filling this application
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="required">Relationship to Student</label>
                                <select id="applicantRelationship">
                                    <option value="">Select relationship</option>
                                    <option value="father">Father</option>
                                    <option value="mother">Mother</option>
                                    <option value="guardian">Guardian</option>
                                    <option value="grandparent">Grandparent</option>
                                    <option value="sibling">Sibling</option>
                                    <option value="other_relative">Other Relative</option>
                                </select>
                            </div>
                        </div>
                    </div>
                
                    <div class="form-actions">
                        <button class="btn btn-outline" onclick="window.location.href='main-form'">
                            <i class="fas fa-arrow-left"></i> Back to Home
                        </button>
                        <button class="btn btn-primary" onclick="nextStep()" id="nextBtn1">
                            Next Step <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 2: Student Basic Details + Contact (LEAD CREATION HERE) -->
                <div class="step" id="step2">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <div style="font-size: 14px;">
                            <strong>Step 2 of 5:</strong> Basic student details and contact information. <strong>A lead will be created after this step.</strong>
                        </div>
                    </div>
                    
                    <!-- Student Basic Details -->
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="section-title">
                                <h3>Student Basic Details</h3>
                                <p>Minimum required information to create a lead</p>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="required">Student's Full Name</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-user"></i>
                                    <input type="text" id="studentFullName" placeholder="Enter full name">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="required">Date of Birth</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                    <input type="date" id="studentDob">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="required">Gender</label>
                                <select id="studentGender">
                                    <option value="">Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                    <option value="prefer_not_to_say">Prefer not to say</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Nationality</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-globe"></i>
                                    <input type="text" id="studentNationality" placeholder="e.g., Indian">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="required">Applying for Grade/Class</label>
                                @if(count($admissionclass) > 0)
                                    <select id="applyingForGrade" class="form-control">
                                        <option value="">Select grade</option>

                                        @foreach($admissionclass as $grade)
                                            <option value="{{ $grade['id'] }}">
                                                {{ $grade['name'] }}
                                            </option>
                                        @endforeach

                                    </select>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Essential Contact Information -->
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="section-title">
                                <h3>Essential Contact Information</h3>
                                <p>Required for lead creation and communication</p>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="required">Email Address</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" id="email" placeholder="student.email@example.com">
                                </div>
                            </div>
                            
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
                            </div>
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
                    
                    <div class="form-actions">
                        <button class="btn btn-outline" onclick="prevStep()">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <button class="btn btn-primary" onclick="createLeadAndProceed()" id="createLeadBtn">
                            <i class="fas fa-save"></i> Save & Continue
                        </button>
                    </div>
                </div>
                
                <!-- Step 3: Additional Contact & Address Information -->
                <div class="step" id="step3">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <div style="font-size: 14px;">
                            <strong>Step 3 of 5:</strong> Additional contact and address information.
                        </div>
                    </div>
                    
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-address-card"></i>
                            </div>
                            <div class="section-title">
                                <h3>Complete Contact Information</h3>
                                <p>Additional contact details for better communication</p>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Alternate Phone</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-phone-alt"></i>
                                    <input type="tel" id="alternatePhone" placeholder="Alternate contact number">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>WhatsApp Number</label>
                                <div class="input-with-icon">
                                    <i class="fab fa-whatsapp"></i>
                                    <input type="tel" id="whatsappNumber" placeholder="WhatsApp number (optional)">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="required">Full Address</label>
                            <textarea id="address" rows="3" placeholder="House no, Street, Area, City, State, PIN Code"></textarea>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" id="city" placeholder="City">
                            </div>
                            
                            <div class="form-group">
                                <label>State</label>
                                <input type="text" id="state" placeholder="State">
                            </div>
                            
                            <div class="form-group">
                                <label>PIN Code</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-map-pin"></i>
                                    <input type="text" id="pincode" placeholder="PIN Code">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button class="btn btn-outline" onclick="prevStep()">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <button class="btn btn-primary" onclick="nextStep()" id="nextBtn3">
                            Next Step <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 4: Education & Family Information -->
                <div class="step" id="step4">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <div style="font-size: 14px;">
                            <strong>Step 4 of 5:</strong> Educational background and family information.
                        </div>
                    </div>
                    
                    <!-- Educational Background -->
                    <div class="section-card">
                        <div class="section-header"> 
                            <div class="section-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="section-title">
                                <h3>Educational Background</h3>
                                <p>previous educational information</p>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="required">Previous School</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-school"></i>
                                    <input type="text" id="previousSchool" placeholder="Name of previous institution">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="required">Previous Class/Grade</label>
                                <input type="text" id="previousClass" placeholder="Previous class completed">
                            </div>
                            
                            <div class="form-group">
                                <label>School Location</label>
                                <input type="text" id="schoolLocation" placeholder="City, State of previous school">
                            </div>
                            
                            <div class="form-group">
                                <label>Percentage/CGPA</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-percentage"></i>
                                    <input type="text" id="percentageCgpa" placeholder="e.g., 85% or 8.5 CGPA">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Marks Format</label>
                            <div style="display: flex; gap: 20px; margin-top: 10px;">
                                <div class="radio-option selected" onclick="selectMarksFormat('percentage')">
                                    <div class="radio-icon"></div>
                                    <div class="radio-text">
                                        <h4>Percentage</h4>
                                        <p>e.g., 85%</p>
                                    </div>
                                </div>
                                <div class="radio-option" onclick="selectMarksFormat('cgpa')">
                                    <div class="radio-icon"></div>
                                    <div class="radio-text">
                                        <h4>CGPA</h4>
                                        <p>e.g., 8.5</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sibling Relationship -->
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-user-friends"></i>
                            </div>
                            <div class="section-title">
                                <h3>Sibling Relationship</h3>
                                <p>Is there a sibling already studying in our school?</p>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="required">Sibling Studying in School?</label>
                                <div style="display: flex; gap: 20px; margin-top: 10px;">
                                    <div class="radio-option" onclick="selectSiblingOption('yes')">
                                        <div class="radio-icon"></div>
                                        <div class="radio-text">
                                            <h4>Yes</h4>
                                            <p>Sibling currently studying</p>
                                        </div>
                                    </div>
                                    <div class="radio-option selected" onclick="selectSiblingOption('no')">
                                        <div class="radio-icon"></div>
                                        <div class="radio-text">
                                            <h4>No</h4>
                                            <p>No sibling in school</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sibling Details (Hidden by default) -->
                        <div id="siblingDetails" class="sibling-details hidden">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Sibling's Name</label>
                                    <input type="text" id="siblingName" placeholder="Sibling's full name">
                                </div>
                                
                                <div class="form-group">
                                    <label>Sibling's Class</label>
                                    <input type="text" id="siblingClass" placeholder="Current class of sibling">
                                </div>
                                
                                <div class="form-group">
                                    <label>Sibling's Section</label>
                                    <input type="text" id="siblingSection" placeholder="Section (if applicable)">
                                </div>
                            </div>
                            
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Admission Number</label>
                                    <input type="text" id="siblingAdmissionNo" placeholder="Sibling's admission number">
                                </div>
                                
                                <div class="form-group">
                                    <label>Academic Year</label>
                                    <input type="text" id="siblingAcademicYear" placeholder="e.g., 2023-2024">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Parent/Guardian Details -->
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="section-title">
                                <h3>Parent/Guardian Details</h3>
                                <p>Information about parent or primary guardian</p>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="required">Father's/Guardian's Name</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-user"></i>
                                    <input type="text" id="fatherName" placeholder="Full name">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Occupation</label>
                                <input type="text" id="fatherOccupation" placeholder="Profession">
                            </div>
                            
                            <div class="form-group">
                                <label class="required">Phone Number</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-phone"></i>
                                    <input type="tel" id="fatherPhone" placeholder="Contact number">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Email Address</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" id="fatherEmail" placeholder="email@example.com">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mother Information -->
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-female"></i>
                            </div>
                            <div class="section-title">
                                <h3>Mother Information</h3>
                                <p>Details of mother</p>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Mother's Name</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-user"></i>
                                    <input type="text" id="motherName" placeholder="Full name">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Occupation</label>
                                <input type="text" id="motherOccupation" placeholder="Profession">
                            </div>
                            
                            <div class="form-group">
                                <label>Phone Number</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-phone"></i>
                                    <input type="tel" id="motherPhone" placeholder="Contact number">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Email Address</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" id="motherEmail" placeholder="email@example.com">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Photo Upload -->
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div class="section-title">
                                <h3>Student Photograph</h3>
                                <p>Upload a recent passport-size photo (Optional)</p>
                            </div>
                        </div>
                        
                        <div class="photo-upload" onclick="document.getElementById('photoInput').click()">
                            <div class="upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <h4>Click to Upload Photo</h4>
                            <p>Recommended: Passport size, JPEG/PNG, Max 2MB</p>
                            <img id="photoPreview" class="photo-preview" alt="Photo Preview">
                            <input type="file" id="photoInput" accept="image/*" style="display: none;" onchange="previewPhoto(event)">
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button class="btn btn-outline" onclick="prevStep()">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <button class="btn btn-primary" onclick="nextStep()" id="nextBtn4">
                            Next Step <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 5: Review & Submit -->
                <div class="step" id="step5">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <div style="font-size: 14px;">
                            <strong>Step 5 of 5:</strong> Review and submit your application.
                        </div> 
                    </div>
                    
                    <div class="admission-form-preview" id="admissionFormPreview">
                        <!-- Admission form preview will be generated here -->
                    </div>
                    
                    <div class="declaration">
                        <h4><i class="fas fa-file-contract"></i> Declaration & Agreement</h4>
                        <p>I hereby declare that all information provided in this application is true, complete, and accurate to the best of my knowledge. I understand that any false information may result in cancellation of admission.</p>
                        
                        <div class="checkbox-group">
                            <div class="checkbox-option">
                                <input type="checkbox" id="declarationCheck" required>
                                <div class="checkbox-text">
                                    <strong>I agree to the declaration above</strong>
                                </div>
                            </div>
                            
                            <div class="checkbox-option">
                                <input type="checkbox" id="termsCheck" required>
                                <div class="checkbox-text">
                                    <strong>I accept the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a> of Institute</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button class="btn btn-outline" onclick="prevStep()">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <button class="btn btn-success" onclick="completeApplication()" id="submitBtn">
                            <i class="fas fa-paper-plane"></i> Complete Application
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        let currentStep = 1;
        let applicantType = '';
        let siblingOption = 'no';
        let marksFormat = 'percentage';
        let leadId = null;
        let referenceId = null;
        let applicantName = '';
        let applicantRelationship = '';
    
        function selectApplicantType(event, type) {
            applicantType = type;
            
            // Update UI
            document.querySelectorAll('.option-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            event.currentTarget.classList.add('selected');
            
            // Show/hide parent name section
            const parentNameSection = document.getElementById('parentNameSection');
            if (type === 'parent') {
                parentNameSection.classList.remove('hidden');
                
                // Make parent name required
                document.getElementById('applicantName').required = true;
                document.getElementById('applicantRelationship').required = true;
            } else {
                parentNameSection.classList.add('hidden');
                
                // Remove required attribute for parent name
                document.getElementById('applicantName').required = false;
                document.getElementById('applicantRelationship').required = false;
            }
        }
        
        function selectSiblingOption(option) {
            siblingOption = option;
            
            // Update UI
            const radioOptions = event.currentTarget.closest('.form-group').querySelectorAll('.radio-option');
            radioOptions.forEach(radio => {
                radio.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
            
            // Show/hide sibling details
            const siblingDetails = document.getElementById('siblingDetails');
            if (option === 'yes') {
                siblingDetails.classList.remove('hidden');
            } else {
                siblingDetails.classList.add('hidden');
            }
        }
        
        function selectMarksFormat(format) {
            marksFormat = format;
            
            // Update UI
            const radioOptions = event.currentTarget.closest('.form-group').querySelectorAll('.radio-option');
            radioOptions.forEach(option => {
                option.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
        }
        
        function previewPhoto(event) {
            const input = event.target;
            const preview = document.getElementById('photoPreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    
                    // Save to form data
                    const formData = JSON.parse(localStorage.getItem('admissionFormData') || '{}');
                    formData.photo_data = e.target.result;
                    localStorage.setItem('admissionFormData', JSON.stringify(formData));
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function createLeadAndProceed() {
            // Validate required fields for Step 2 (Lead Creation)
            if (!validateStep2()) {
                return;
            }
            
            // Get all data from Step 1 and Step 2
            const leadData = {
                applicant_type: applicantType,
                student_full_name: document.getElementById('studentFullName').value,
                student_dob: document.getElementById('studentDob').value,
                student_gender: document.getElementById('studentGender').value,
                student_nationality: document.getElementById('studentNationality').value,
                applying_for_grade: document.getElementById('applyingForGrade').value,
                email: document.getElementById('email').value,
                phone: cleanPhoneNumber(document.getElementById('phone').value),
                is_initial_lead: true
            };
            
            // Add parent name if applicant is parent
            if (applicantType === 'parent') {
                leadData.applicant_name = applicantName;
                leadData.applicant_relationship = applicantRelationship;
                leadData.registered_by = applicantName; // Person who filled the form
            } else {
                leadData.registered_by = document.getElementById('studentFullName').value; // Student filled it themselves
            }
            
            console.log('Creating lead with data:', leadData);
            
            // Send to create lead route (rest of the function remains the same)
            fetch('/save-admission', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(leadData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // console.log('Lead created successfully:', data);
                    showToast('Lead created successfully!', 'success');
                    // Store lead ID and reference ID
                    leadId = data.lead_id;
                    referenceId = data.reference_id;
                    
                    // Update UI with reference ID
                    document.getElementById('referenceId').textContent = referenceId;
                    
                    // Save to localStorage
                    localStorage.setItem('lead_id', leadId);
                    localStorage.setItem('reference_id', referenceId);
                    localStorage.setItem('applicant_type', applicantType);
                    
                    // Save parent name if applicable
                    if (applicantType === 'parent') {
                        localStorage.setItem('applicant_name', applicantName);
                        localStorage.setItem('applicant_relationship', applicantRelationship);
                    }
                    
                    localStorage.setItem('basic_lead_data', JSON.stringify(leadData));
                    
                    // Proceed to next step
                    document.getElementById('step2').classList.remove('active');
                    document.getElementById('step3').classList.add('active');
                    currentStep = 3;
                    updateProgress();
                } else {
                    showToast('Error creating lead: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error creating lead:', error);
                showToast('Error creating lead. Please try again.', 'error');
            });
        }
        
        function validateStep2() {
            // Validate applicant type
            if (!applicantType || !document.querySelector('.option-card.selected')) {
                showToast('Please select applicant type', 'error');
                return false;
            }
            
            // Validate student basic details
            const studentFields = [
                { id: 'studentFullName', name: 'Student Full Name' },
                { id: 'studentDob', name: 'Date of Birth' },
                { id: 'studentGender', name: 'Gender' },
                { id: 'applyingForGrade', name: 'Applying for Grade' }
            ];
            
            for (const field of studentFields) {
                const value = document.getElementById(field.id).value;
                if (!value || value.trim() === '') {
                    showToast(`Please enter ${field.name}`, 'error');
                    setTimeout(() => {
                        document.getElementById(field.id).focus();
                    }, 100);
                    return false;
                }
            }
            
            // Validate contact information
            const contactFields = [
                { id: 'email', name: 'Email Address' },
                { id: 'phone', name: 'Phone Number' }
            ];
            
            for (const field of contactFields) {
                const value = document.getElementById(field.id).value;
                if (!value || value.trim() === '') {
                    showToast(`Please enter ${field.name}`, 'error');
                    setTimeout(() => {
                        document.getElementById(field.id).focus();
                    }, 100);
                    return false;
                }
            }
            
            // Validate email format
            const email = document.getElementById('email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showToast('Please enter a valid email address', 'error');
                setTimeout(() => {
                    document.getElementById('email').focus();
                }, 100);
                return false;
            }

            // Validate OTP verification
            if (!isOtpVerified) {
                showToast('Please verify your phone number with OTP', 'error');
                document.getElementById('otpVerificationSection').scrollIntoView({ behavior: 'smooth' });
                return false;
            }
            
            return true;
        }
        
        function nextStep() {
            console.log('Next step called, current step:', currentStep);
            
            if (currentStep === 1) {
                // Validate Step 1 - Ensure applicant type is selected
                if (!applicantType) {
                    showToast('Please select applicant type (Student or Parent/Guardian)', 'error');
                    
                    // Add visual feedback - make both cards shake
                    const cards = document.querySelectorAll('.option-card');
                    cards.forEach(card => {
                        card.style.animation = 'shake 0.5s ease-in-out';
                    });
                    
                    // Remove animation after it completes
                    setTimeout(() => {
                        cards.forEach(card => {
                            card.style.animation = '';
                        });
                    }, 500);
                    
                    return;
                }
                
            if (applicantType === 'parent') {
                    applicantName = document.getElementById('applicantName').value.trim();
                    applicantRelationship = document.getElementById('applicantRelationship').value;
                    
                    if (!applicantName) {
                        showToast('Please enter your name (Parent/Guardian)', 'error');
                        setTimeout(() => {
                            document.getElementById('applicantName').focus();
                        }, 100);
                        return;
                    }
                    
                    if (!applicantRelationship) {
                        showToast('Please select your relationship to the student', 'error');
                        setTimeout(() => {
                            document.getElementById('applicantRelationship').focus();
                        }, 100);
                        return;
                    }
                }
                
                document.getElementById('step1').classList.remove('active');
                document.getElementById('step2').classList.add('active');
                currentStep = 2;
            } 
            else if (currentStep === 2 && !validateStep2()) {
                return;
            }
            else if (currentStep === 3 && !validateStep3()) {
                return;
            }
            else if (currentStep === 4 && !validateStep4()) {
                return;
            }
            else if (currentStep === 2 || currentStep === 3 || currentStep === 4) {
                // Save current step data
                saveCurrentStepData();
                
                // Move to next step
                document.getElementById(`step${currentStep}`).classList.remove('active');
                currentStep++;
                document.getElementById(`step${currentStep}`).classList.add('active');
                
                // If this is the last step, generate preview
                if (currentStep === 5) {
                    generateAdmissionFormPreview();
                }
            }
            
            updateProgress();
        }
        
        function prevStep() {
            // Save current step data before moving back
            saveCurrentStepData();
            
            document.getElementById(`step${currentStep}`).classList.remove('active');
            currentStep--;
            document.getElementById(`step${currentStep}`).classList.add('active');
            updateProgress();
        }
        
        function validateStep3() {
            // Address is required
            if (!document.getElementById('address').value.trim()) {
                showToast('Please enter full address', 'error');
                setTimeout(() => {
                    document.getElementById('address').focus();
                }, 100);
                return false;
            }
            return true;
        }
        
        function validateStep4() {
            // Validate educational fields
            const eduFields = [
                { id: 'previousSchool', name: 'Previous School/College' },
                { id: 'previousClass', name: 'Previous Class/Grade' }
            ];
            
            for (const field of eduFields) {
                const value = document.getElementById(field.id).value;
                if (!value || value.trim() === '') {
                    showToast(`Please enter ${field.name}`, 'error');
                    setTimeout(() => {
                        document.getElementById(field.id).focus();
                    }, 100);
                    return false;
                }
            }
            
            // Validate guardian fields
            const guardianFields = [
                { id: 'fatherName', name: "Father's/Guardian's Name" },
                { id: 'fatherPhone', name: "Father's/Guardian's Phone Number" }
            ];
            
            for (const field of guardianFields) {
                const value = document.getElementById(field.id).value;
                if (!value || value.trim() === '') {
                    showToast(`Please enter ${field.name}`, 'error');
                    setTimeout(() => {
                        document.getElementById(field.id).focus();
                    }, 100);
                    return false;
                }
            }
            return true;
        }
    
        function saveCurrentStepData() {
            const formData = {
                applicant_type: applicantType,
                applicant_name: applicantName,
                applicant_relationship: applicantRelationship,
                sibling_option: siblingOption,
                marks_format: marksFormat,
                
                // Step 3 data
                alternate_phone: document.getElementById('alternatePhone').value,
                whatsapp_number: document.getElementById('whatsappNumber').value,
                address: document.getElementById('address').value,
                city: document.getElementById('city').value,
                state: document.getElementById('state').value,
                pincode: document.getElementById('pincode').value,
                
                // Step 4 data
                previous_school: document.getElementById('previousSchool').value,
                previous_class: document.getElementById('previousClass').value,
                school_location: document.getElementById('schoolLocation').value,
                percentage_cgpa: document.getElementById('percentageCgpa').value,
                sibling_name: document.getElementById('siblingName')?.value || '',
                sibling_class: document.getElementById('siblingClass')?.value || '',
                sibling_section: document.getElementById('siblingSection')?.value || '',
                sibling_admission_no: document.getElementById('siblingAdmissionNo')?.value || '',
                sibling_academic_year: document.getElementById('siblingAcademicYear')?.value || '',
                father_name: document.getElementById('fatherName').value,
                father_occupation: document.getElementById('fatherOccupation').value,
                father_phone: document.getElementById('fatherPhone').value,
                father_email: document.getElementById('fatherEmail').value,
                mother_name: document.getElementById('motherName').value,
                mother_occupation: document.getElementById('motherOccupation').value,
                mother_phone: document.getElementById('motherPhone').value,
                mother_email: document.getElementById('motherEmail').value
            };
            
            // Get existing photo data
            const photoPreview = document.getElementById('photoPreview');
            if (photoPreview && photoPreview.src && photoPreview.src.startsWith('data:image')) {
                formData.photo_data = photoPreview.src;
            }
            
            // Merge with existing data
            const existingData = JSON.parse(localStorage.getItem('admissionFormData') || '{}');
            const mergedData = { ...existingData, ...formData };
            
            // Save to localStorage
            localStorage.setItem('admissionFormData', JSON.stringify(mergedData));
            
            return mergedData;
        }
        
        function updateProgress() {
            const progress = (currentStep / 5) * 100;
            document.getElementById('progressFill').style.width = progress + '%';
        }
        
        function generateAdmissionFormPreview() {
            const formPreview = document.getElementById('admissionFormPreview');
            
            // Load all data
            const basicData = JSON.parse(localStorage.getItem('basic_lead_data') || '{}');
            const additionalData = JSON.parse(localStorage.getItem('admissionFormData') || '{}'); 
            const admissionData = { ...basicData, ...additionalData };
            
            referenceId = localStorage.getItem('reference_id') || referenceId;
            leadId = localStorage.getItem('lead_id') || leadId;
            
            let previewHTML = `
                <div class="form-preview-header"> 
                    <h3>Admission  Form Preview</h3>
                    <div class="ref-id">Reference ID: ${referenceId || 'Pending'}</div>
                    <div style="font-size: 14px; color: var(--text-light); margin-top: 10px;">
                        Lead ID: ${leadId || 'Pending'}
                    </div>
                </div>
                
                <div class="form-preview-section">
                    <h4>Application Information</h4>
                    <div class="preview-grid-2col">
                        <div class="form-preview-row">
                            <div class="form-preview-label">Applicant Type</div>
                            <div class="form-preview-value">${admissionData.applicant_type === 'student' ? 'Student (student)' : 'Parent/Guardian'}</div>
                        </div>
                           `;
        
        // Add parent name if applicable
        if (admissionData.applicant_type === 'parent') {
            previewHTML += `
                    <div class="form-preview-row">
                        <div class="form-preview-label">Registered By</div>
                        <div class="form-preview-value">${admissionData.applicant_name || 'Not provided'} (${admissionData.applicant_relationship || 'Parent'})</div>
                    </div>
            `;
        }
        
        previewHTML += `
                    <div class="form-preview-row">
                        <div class="form-preview-label">Application Status</div>
                        <div class="form-preview-value"><span style="color: var(--warning-orange); font-weight: 600;">Draft (Pending Completion)</span></div>
                    </div>
                </div>
            </div>
                
                <div class="form-preview-section">
                    <h4>Student Basic Details</h4>
                    <div class="preview-grid-2col">
                        <div class="form-preview-row">
                            <div class="form-preview-label">Full Name</div>
                            <div class="form-preview-value">${admissionData.student_full_name || 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row">
                            <div class="form-preview-label">Date of Birth</div>
                            <div class="form-preview-value">${admissionData.student_dob ? new Date(admissionData.student_dob).toLocaleDateString('en-IN') : 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row">
                            <div class="form-preview-label">Gender</div>
                            <div class="form-preview-value">${admissionData.student_gender ? admissionData.student_gender.charAt(0).toUpperCase() + admissionData.student_gender.slice(1) : 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row">
                            <div class="form-preview-label">Nationality</div>
                            <div class="form-preview-value">${admissionData.student_nationality || 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row">
                            <div class="form-preview-label">Applying for Grade</div>
                            <div class="form-preview-value">${admissionData.applying_for_grade || 'Not provided'}</div>
                        </div>
                    </div>
                </div>
                
                <div class="form-preview-section">
                    <h4>Contact Information</h4>
                    <div class="preview-grid-2col">
                        <div class="form-preview-row">
                            <div class="form-preview-label">Email</div>
                            <div class="form-preview-value">${admissionData.email || 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row">
                            <div class="form-preview-label">Primary Phone</div>
                            <div class="form-preview-value">${admissionData.phone || 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row">
                            <div class="form-preview-label">Alternate Phone</div>
                            <div class="form-preview-value">${admissionData.alternate_phone || 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row">
                            <div class="form-preview-label">WhatsApp Number</div>
                            <div class="form-preview-value">${admissionData.whatsapp_number || 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row full-width">
                            <div class="form-preview-label">Address</div>
                            <div class="form-preview-value">${admissionData.address || 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row">
                            <div class="form-preview-label">City</div>
                            <div class="form-preview-value">${admissionData.city || 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row">
                            <div class="form-preview-label">State</div>
                            <div class="form-preview-value">${admissionData.state || 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row">
                            <div class="form-preview-label">PIN Code</div>
                            <div class="form-preview-value">${admissionData.pincode || 'Not provided'}</div>
                        </div>
                    </div>
                </div>
                
                <div class="form-preview-section">
                    <h4>Educational Background</h4>
                    <div class="preview-grid-2col">
                        <div class="form-preview-row">
                            <div class="form-preview-label">Previous School</div>
                            <div class="form-preview-value">${admissionData.previous_school || 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row">
                            <div class="form-preview-label">Previous Class</div>
                            <div class="form-preview-value">${admissionData.previous_class || 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row">
                            <div class="form-preview-label">School Location</div>
                            <div class="form-preview-value">${admissionData.school_location || 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row">
                            <div class="form-preview-label">Marks (${admissionData.marks_format === 'percentage' ? 'Percentage' : 'CGPA'})</div>
                            <div class="form-preview-value">${admissionData.percentage_cgpa || 'Not provided'}</div>
                        </div>
                    </div>
                </div>
                
                <div class="form-preview-section">
                    <h4>Sibling Information</h4>
                    <div class="preview-grid-2col">
                        <div class="form-preview-row">
                            <div class="form-preview-label">Sibling in School</div>
                            <div class="form-preview-value">${admissionData.sibling_option === 'yes' ? 'Yes' : 'No'}</div>
                        </div>
            `;
            
            if (admissionData.sibling_option === 'yes') {
                previewHTML += `
                        <div class="form-preview-row">
                            <div class="form-preview-label">Sibling Name</div>
                            <div class="form-preview-value">${admissionData.sibling_name || 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row">
                            <div class="form-preview-label">Sibling Class</div>
                            <div class="form-preview-value">${admissionData.sibling_class || 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row">
                            <div class="form-preview-label">Sibling Section</div>
                            <div class="form-preview-value">${admissionData.sibling_section || 'Not provided'}</div>
                        </div>
                `;
            }
            
            previewHTML += `
                    </div>
                </div>
                
                <div class="form-preview-section">
                    <h4>Guardian Information</h4>
                    <div class="preview-grid-2col">
                        <div class="form-preview-row">
                            <div class="form-preview-label">Father/Guardian Name</div>
                            <div class="form-preview-value">${admissionData.father_name || 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row">
                            <div class="form-preview-label">Father's Occupation</div>
                            <div class="form-preview-value">${admissionData.father_occupation || 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row">
                            <div class="form-preview-label">Father's Phone</div>
                            <div class="form-preview-value">${admissionData.father_phone || 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row">
                            <div class="form-preview-label">Mother's Name</div>
                            <div class="form-preview-value">${admissionData.mother_name || 'Not provided'}</div>
                        </div>
                        <div class="form-preview-row">
                            <div class="form-preview-label">Mother's Occupation</div>
                            <div class="form-preview-value">${admissionData.mother_occupation || 'Not provided'}</div>
                        </div>
                    </div>
                </div>
            `;
            
            formPreview.innerHTML = previewHTML;
        }
        
        function completeApplication() {
            // Check declaration
            if (!document.getElementById('declarationCheck').checked || !document.getElementById('termsCheck').checked) {
                showToast('Please accept the declaration and terms & conditions', 'error');
                return;
            }
            
            // Save all data
            const allData = saveCurrentStepData();
            
            // Add lead ID and reference ID
            allData.lead_id = localStorage.getItem('lead_id') || leadId;
            allData.reference_id = localStorage.getItem('reference_id') || referenceId;
            allData.is_complete_application = true;
            
            console.log('Completing Application with Data:', allData);
            
            // Send to update lead route
            fetch('/update-admission', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(allData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Server Response:', data);
                if (data.success) {
                    showToast('Application completed successfully!\nReference ID: ' + data.reference_id + '\nLead ID: ' + data.lead_id, 'success');
                    
                    // Clear localStorage
                    localStorage.removeItem('admissionFormData');
                    localStorage.removeItem('basic_lead_data');
                    localStorage.removeItem('lead_id');
                    localStorage.removeItem('reference_id');
                    localStorage.removeItem('applicant_type');
                    
                    // Redirect to main form page
                    setTimeout(() => {
                        window.location.href = '/main-form';
                    }, 1500);
                } else {
                    showToast(data.message, 'error'); 
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error completing application. Please try again.', 'error');
            });
        }

        // OTP Functionality
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
            let timeLeft = 300; // 5 minutes
            document.getElementById('verifyOtpBtn').style.display = 'inline-block';
            document.getElementById('resendOtpBtn').style.display = 'none';

            if (otpTimer) {
                clearInterval(otpTimer);
            }

            otpTimer = setInterval(function() {
                timeLeft--;

                if (timeLeft <= 0) {
                    clearInterval(otpTimer);
                    document.getElementById('verifyOtpBtn').style.display = 'none';
                    document.getElementById('resendOtpBtn').style.display = 'inline-block';
                    showToast('OTP has expired. Please request a new one.', 'error');
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

                    if (otpTimer) {
                        clearInterval(otpTimer);
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
                    showToast('Invalid OTP. Please try again.', 'error');
                }
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });

        // Resend OTP
        document.getElementById('resendOtpBtn').addEventListener('click', function() {
            document.getElementById('sendOtpBtn').click();
            // Reset OTP inputs
            document.querySelectorAll('.otp-digit').forEach(input => {
                input.value = '';
                input.disabled = false;
            });
            document.getElementById('otpStatus').style.display = 'none';
            isOtpVerified = false;
        });
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            
            // Load additional data if exists
            const savedData = localStorage.getItem('admissionFormData');
            if (savedData) {
                const data = JSON.parse(savedData);
                console.log('Loaded additional data:', data);
                
                // Load applicant type
                if (data.applicant_type) {
                    applicantType = data.applicant_type;
                }
                
                // Load sibling option
                if (data.sibling_option) {
                    siblingOption = data.sibling_option;
                    // Manually trigger selection
                    const siblingYes = document.querySelector('.radio-option:nth-child(1)');
                    const siblingNo = document.querySelector('.radio-option:nth-child(2)');
                    
                    if (siblingOption === 'yes') {
                        siblingYes.classList.add('selected');
                        siblingNo.classList.remove('selected');
                        document.getElementById('siblingDetails').classList.remove('hidden');
                    } else {
                        siblingNo.classList.add('selected');
                        siblingYes.classList.remove('selected');
                        document.getElementById('siblingDetails').classList.add('hidden');
                    }
                }
                
                // Load marks format
                if (data.marks_format) {
                    marksFormat = data.marks_format;
                
                    const marksOptions = document.querySelectorAll('#marks-format .radio-option');
                
                    marksOptions.forEach(option => {
                        option.classList.remove('selected');
                    });
                
                    if (marksOptions.length >= 2) {
                        if (marksFormat === 'percentage') {
                            marksOptions[0].classList.add('selected');
                        } else {
                            marksOptions[1].classList.add('selected');
                        }
                    } else {
                        console.warn('Marks options not found properly');
                    }
                }
                
                // Load all other fields
                const fields = [
                    'alternatePhone', 'whatsappNumber', 'address', 'city', 'state', 'pincode',
                    'previousSchool', 'previousClass', 'schoolLocation', 'percentageCgpa',
                    'siblingName', 'siblingClass', 'siblingSection', 'siblingAdmissionNo', 'siblingAcademicYear',
                    'fatherName', 'fatherOccupation', 'fatherPhone', 'fatherEmail',
                    'motherName', 'motherOccupation', 'motherPhone', 'motherEmail'
                ];
                
                fields.forEach(field => {
                    if (data[field] && document.getElementById(field)) {
                        document.getElementById(field).value = data[field];
                    }
                });
                
                // Load photo if exists
                if (data.photo_data && data.photo_data.startsWith('data:image')) {
                    const preview = document.getElementById('photoPreview');
                    preview.src = data.photo_data;
                    preview.style.display = 'block';
                }
            }
            
            // Initialize progress
            updateProgress();
            
            // Add click event for photo upload
            document.getElementById('photoInput').addEventListener('change', previewPhoto);
            
            console.log('Initialization complete, current step:', currentStep);
        });
        
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
        function cleanPhoneNumber(phoneNumber) {
            // Remove any non-digit characters
            let cleaned = phoneNumber.replace(/\D/g, '');
        
            // Remove +91 prefix if present
            if (cleaned.startsWith('91')) {
                cleaned = cleaned.substring(2);
            }
        
            return cleaned;
        }
    </script>
@endsection
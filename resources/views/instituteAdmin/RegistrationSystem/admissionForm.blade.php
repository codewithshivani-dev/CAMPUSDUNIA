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
            flex-wrap: wrap;
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
            margin: 0;
        }
        
        .logo-text p {
            font-size: 12px;
            color: var(--text-light);
            margin: 0;
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
            /*position: absolute;*/
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
        
        .btn-sm {
            padding: 10px 20px;
            font-size: 13px;
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
        
        /* Enhanced Form Preview Styles */
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
        
        /* OTP Styles */
        .row-two-col {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }
        
        .col-md-6 {
            flex: 1;
            min-width: 280px;
        }
        
        .otp-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            height: 100%;
        }
        
        .otp-box-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .otp-box-header i {
            font-size: 24px;
            color: var(--primary-blue);
        }
        
        .otp-box-header h4 {
            margin: 0;
            font-size: 16px;
            color: var(--text-dark);
        }
        
        .otp-digits {
            display: flex;
            gap: 12px;
            margin: 15px 0;
            justify-content: center;
        }
        
        .otp-digit {
            width: 55px;
            height: 55px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border: 2px solid #cbd5e1;
            border-radius: 12px;
            background: white;
        }
        
        .otp-digit:focus {
            border-color: var(--primary-blue);
            outline: none;
        }
        
        .timer-text {
            font-size: 13px;
            background: #eef2ff;
            display: inline-block;
            padding: 5px 12px;
            border-radius: 30px;
            color: #1e3a8a;
            margin-top: 8px;
        }
        
        .otp-status {
            margin-top: 12px;
            padding: 10px;
            border-radius: 8px;
            display: none;
        }
        
        .otp-status.success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
            display: block;
        }
        
        .otp-status.error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
            display: block;
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
            
            .row-two-col {
                flex-direction: column;
            }
            
            .otp-digits {
                gap: 8px;
            }
            
            .otp-digit {
                width: 45px;
                height: 45px;
                font-size: 20px;
            }
        }
    </style>
    
    <div class="container-fluid">
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
            
        <div class="form-wrapper mt-3">
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
                    
                    <!-- Essential Contact Information - SIDE BY SIDE LAYOUT -->
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="section-title">
                                <h3>Essential Contact Information</h3>
                                <p>Required for lead creation and communication - Please verify both</p>
                            </div>
                        </div>
                        
                        <!-- Two Column Layout for Email and Phone -->
                        <div class="row-two-col">
                            <!-- Email Column -->
                            <div class="col-md-6">
                                <div class="otp-box">
                                    <div class="otp-box-header">
                                        <i class="fas fa-envelope"></i>
                                        <h4>Email Verification</h4>
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Email Address</label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-envelope"></i>
                                            <input type="email" id="email" name="email" placeholder="student.email@example.com">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary" id="sendEmailOtpBtn" style="width: 100%; display: none;">
                                        <i class="fas fa-paper-plane"></i> Send OTP to Email
                                    </button>
                                    
                                    <!-- Email OTP Verification Section -->
                                    <div id="emailOtpVerificationSection" style="display: none; margin-top: 20px;">
                                        <p style="color: #0c4a6e; font-size: 13px; margin-bottom: 12px;">
                                            Enter 4-digit OTP sent to <strong id="emailDisplay"></strong>
                                        </p>
                                        <div class="otp-digits">
                                            <input type="text" class="email-otp-digit" maxlength="1" inputmode="numeric">
                                            <input type="text" class="email-otp-digit" maxlength="1" inputmode="numeric">
                                            <input type="text" class="email-otp-digit" maxlength="1" inputmode="numeric">
                                            <input type="text" class="email-otp-digit" maxlength="1" inputmode="numeric">
                                        </div>
                                        <div style="display: flex; gap: 10px; margin-top: 12px; flex-wrap: wrap;">
                                            <button type="button" class="btn btn-sm btn-primary" id="verifyEmailOtpBtn" style="flex: 1;">
                                                <i class="fas fa-check"></i> Verify
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline" id="resendEmailOtpBtn" style="display: none; flex: 1;">
                                                <i class="fas fa-redo"></i> Resend OTP
                                            </button>
                                        </div>
                                        <div id="emailTimerText" style="font-size: 12px; margin-top: 10px; text-align: center;"></div>
                                        <div id="emailOtpStatus" class="otp-status"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Phone Column -->
                            <div class="col-md-6">
                                <div class="otp-box">
                                    <div class="otp-box-header">
                                        <i class="fas fa-phone-alt"></i>
                                        <h4>Phone Verification</h4>
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Phone Number</label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-phone"></i>
                                            <input type="tel" id="phone" maxlength="10" placeholder="98765 43210" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary" id="sendOtpBtn" style="width: 100%; display: none;">
                                        <i class="fas fa-paper-plane"></i> Send OTP to Phone
                                    </button>
                                    
                                    <!-- Phone OTP Verification Section -->
                                    <div id="otpVerificationSection" style="display: none; margin-top: 20px;">
                                        <p style="color: #0c4a6e; font-size: 13px; margin-bottom: 12px;">
                                            Enter 4-digit OTP sent to <strong id="phoneDisplay"></strong>
                                        </p>
                                        <div class="otp-digits">
                                            <input type="text" class="phone-otp-digit" maxlength="1" inputmode="numeric">
                                            <input type="text" class="phone-otp-digit" maxlength="1" inputmode="numeric">
                                            <input type="text" class="phone-otp-digit" maxlength="1" inputmode="numeric">
                                            <input type="text" class="phone-otp-digit" maxlength="1" inputmode="numeric">
                                        </div>
                                        <div style="display: flex; gap: 10px; margin-top: 12px; flex-wrap: wrap;">
                                            <button type="button" class="btn btn-sm btn-primary" id="verifyPhoneOtpBtn" style="flex: 1;">
                                                <i class="fas fa-check"></i> Verify
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline" id="resendPhoneOtpBtn" style="display: none; flex: 1;">
                                                <i class="fas fa-redo"></i> Resend OTP
                                            </button>
                                        </div>
                                        <div id="phoneTimerText" style="font-size: 12px; margin-top: 10px; text-align: center;"></div>
                                        <div id="phoneOtpStatus" class="otp-status"></div>
                                    </div>
                                </div>
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
                            <label class="required">Address Line 1</label>
                            <input type="text" id="address_line_1" placeholder="House no, Street, Area" required>
                        </div>
                        <div class="form-group">
                            <label>Address Line 2</label>
                            <input type="text" id="address_line_2" placeholder="Apartment, Suite, Landmark (optional)">
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
                        
                        <div class="form-group" id="marks-format">
                            <label>Marks Format</label>
                            <div style="display: flex; gap: 20px; margin-top: 10px; flex-wrap: wrap;">
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
                                <div style="display: flex; gap: 20px; margin-top: 10px; flex-wrap: wrap;">
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
    
    // OTP variables
    let generatedEmailOTP = '';
    let generatedPhoneOTP = '';
    let isEmailVerified = false;
    let isPhoneVerified = false;
    let emailOtpTimer = null;
    let phoneOtpTimer = null;
    let emailOtpTimeLeft = 0;
    let phoneOtpTimeLeft = 0;

    function selectApplicantType(event, type) {
        applicantType = type;
        
        document.querySelectorAll('.option-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        event.currentTarget.classList.add('selected');
        
        const parentNameSection = document.getElementById('parentNameSection');
        if (type === 'parent') {
            parentNameSection.classList.remove('hidden');
            document.getElementById('applicantName').required = true;
            document.getElementById('applicantRelationship').required = true;
        } else {
            parentNameSection.classList.add('hidden');
            document.getElementById('applicantName').required = false;
            document.getElementById('applicantRelationship').required = false;
        }
    }
    
    function selectSiblingOption(option) {
        siblingOption = option;
        
        const radioOptions = document.querySelectorAll('#step4 .radio-option');
        radioOptions.forEach(radio => {
            radio.classList.remove('selected');
        });
        event.currentTarget.classList.add('selected');
        
        const siblingDetails = document.getElementById('siblingDetails');
        if (option === 'yes') {
            siblingDetails.classList.remove('hidden');
        } else {
            siblingDetails.classList.add('hidden');
        }
    }
    
    function selectMarksFormat(format) {
        marksFormat = format;
        const radioOptions = document.querySelectorAll('#marks-format .radio-option');
        radioOptions.forEach(radio => {
            radio.classList.remove('selected');
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
                const formData = JSON.parse(localStorage.getItem('admissionFormData') || '{}');
                formData.photo_data = e.target.result;
                localStorage.setItem('admissionFormData', JSON.stringify(formData));
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // EMAIL OTP FUNCTIONS (2 MINUTE TIMER)
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
            
            if (emailOtpTimeLeft <= 0) {
                clearInterval(emailOtpTimer);
                verifyBtn.style.display = 'none';
                resendBtn.style.display = 'inline-block';
                timerDiv.innerHTML = '<span class="timer-text"><i class="fas fa-hourglass-end"></i> OTP expired. Click Resend.</span>';
            } else {
                const minutes = Math.floor(emailOtpTimeLeft / 60);
                const seconds = emailOtpTimeLeft % 60;
                timerDiv.innerHTML = `<span class="timer-text"><i class="fas fa-clock"></i> Resend available in: ${minutes}:${seconds.toString().padStart(2, '0')}</span>`;
            }
        }, 1000);
    }
    
    function resetEmailOTPUI() {
        document.querySelectorAll('.email-otp-digit').forEach(input => {
            input.value = '';
            input.disabled = false;
        });
        document.getElementById('emailOtpStatus').className = 'otp-status';
        document.getElementById('emailOtpStatus').style.display = 'none';
        isEmailVerified = false;
        if (emailOtpTimer) clearInterval(emailOtpTimer);
        document.getElementById('emailTimerText').innerHTML = '';
    }
    
    // PHONE OTP FUNCTIONS (2 MINUTE TIMER)
    function startPhoneOTPTimer() {
        phoneOtpTimeLeft = 120; // 2 minutes = 120 seconds
        const verifyBtn = document.getElementById('verifyPhoneOtpBtn');
        const resendBtn = document.getElementById('resendPhoneOtpBtn');
        const timerDiv = document.getElementById('phoneTimerText');
        
        if (phoneOtpTimer) clearInterval(phoneOtpTimer);
        
        verifyBtn.style.display = 'inline-block';
        resendBtn.style.display = 'none';
        
        phoneOtpTimer = setInterval(function() {
            phoneOtpTimeLeft--;
            
            if (phoneOtpTimeLeft <= 0) {
                clearInterval(phoneOtpTimer);
                verifyBtn.style.display = 'none';
                resendBtn.style.display = 'inline-block';
                timerDiv.innerHTML = '<span class="timer-text"><i class="fas fa-hourglass-end"></i> OTP expired. Click Resend.</span>';
            } else {
                const minutes = Math.floor(phoneOtpTimeLeft / 60);
                const seconds = phoneOtpTimeLeft % 60;
                timerDiv.innerHTML = `<span class="timer-text"><i class="fas fa-clock"></i> Resend available in: ${minutes}:${seconds.toString().padStart(2, '0')}</span>`;
            }
        }, 1000);
    }
    
    function resetPhoneOTPUI() {
        document.querySelectorAll('.phone-otp-digit').forEach(input => {
            input.value = '';
            input.disabled = false;
        });
        document.getElementById('phoneOtpStatus').className = 'otp-status';
        document.getElementById('phoneOtpStatus').style.display = 'none';
        isPhoneVerified = false;
        if (phoneOtpTimer) clearInterval(phoneOtpTimer);
        document.getElementById('phoneTimerText').innerHTML = '';
    }
    
    function createLeadAndProceed() {
        if (!validateStep2()) {
            return;
        }
        
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
        
        if (applicantType === 'parent') {
            leadData.applicant_name = document.getElementById('applicantName').value;
            leadData.applicant_relationship = document.getElementById('applicantRelationship').value;
            leadData.registered_by = document.getElementById('applicantName').value;
        } else {
            leadData.registered_by = document.getElementById('studentFullName').value;
        }
        
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
                showToast('Lead created successfully!', 'success');
                leadId = data.lead_id;
                referenceId = data.reference_id;
                document.getElementById('referenceId').textContent = referenceId;
                localStorage.setItem('lead_id', leadId);
                localStorage.setItem('reference_id', referenceId);
                localStorage.setItem('applicant_type', applicantType);
                localStorage.setItem('basic_lead_data', JSON.stringify(leadData));
                document.getElementById('step2').classList.remove('active');
                document.getElementById('step3').classList.add('active');
                currentStep = 3;
                updateProgress();
            } else {
                showToast('Error creating lead: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error creating lead. Please try again.', 'error');
        });
    }
    
    function validateStep2() {
        if (!applicantType || !document.querySelector('.option-card.selected')) {
            showToast('Please select applicant type', 'error');
            return false;
        }
        
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
                setTimeout(() => document.getElementById(field.id).focus(), 100);
                return false;
            }
        }
        
        const email = document.getElementById('email').value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showToast('Please enter a valid email address', 'error');
            setTimeout(() => document.getElementById('email').focus(), 100);
            return false;
        }
        
        const phone = document.getElementById('phone').value;
        if (!phone || phone.length !== 10) {
            showToast('Please enter a valid 10-digit phone number', 'error');
            setTimeout(() => document.getElementById('phone').focus(), 100);
            return false;
        }
        
        if (!isEmailVerified) {
            showToast('Please verify your email address with OTP', 'error');
            document.getElementById('emailOtpVerificationSection').scrollIntoView({ behavior: 'smooth' });
            return false;
        }
        
        if (!isPhoneVerified) {
            showToast('Please verify your phone number with OTP', 'error');
            document.getElementById('otpVerificationSection').scrollIntoView({ behavior: 'smooth' });
            return false;
        }
        
        return true;
    }
    
    function validateStep3() {
        const address1 = document.getElementById('address_line_1');
        if (!address1 || !address1.value.trim()) {
            showToast('Please enter Address Line 1', 'error');
            setTimeout(() => address1.focus(), 100);
            return false;
        }
        return true;
    }
    
    function validateStep4() {
        const eduFields = [
            { id: 'previousSchool', name: 'Previous School/College' },
            { id: 'previousClass', name: 'Previous Class/Grade' }
        ];
        
        for (const field of eduFields) {
            const value = document.getElementById(field.id).value;
            if (!value || value.trim() === '') {
                showToast(`Please enter ${field.name}`, 'error');
                setTimeout(() => document.getElementById(field.id).focus(), 100);
                return false;
            }
        }
        
        const guardianFields = [
            { id: 'fatherName', name: "Father's/Guardian's Name" },
            { id: 'fatherPhone', name: "Father's/Guardian's Phone Number" }
        ];
        
        for (const field of guardianFields) {
            const value = document.getElementById(field.id).value;
            if (!value || value.trim() === '') {
                showToast(`Please enter ${field.name}`, 'error');
                setTimeout(() => document.getElementById(field.id).focus(), 100);
                return false;
            }
        }
        return true;
    }
    
    function saveCurrentStepData() {
        const formData = {
            applicant_type: applicantType,
            sibling_option: siblingOption,
            marks_format: marksFormat,
            alternate_phone: document.getElementById('alternatePhone')?.value || '',
            whatsapp_number: document.getElementById('whatsappNumber')?.value || '',
            address_line_1: document.getElementById('address_line_1')?.value || '',
            address_line_2: document.getElementById('address_line_2')?.value || '',
            city: document.getElementById('city')?.value || '',
            state: document.getElementById('state')?.value || '',
            pincode: document.getElementById('pincode')?.value || '',
            previous_school: document.getElementById('previousSchool')?.value || '',
            previous_class: document.getElementById('previousClass')?.value || '',
            school_location: document.getElementById('schoolLocation')?.value || '',
            percentage_cgpa: document.getElementById('percentageCgpa')?.value || '',
            sibling_name: document.getElementById('siblingName')?.value || '',
            sibling_class: document.getElementById('siblingClass')?.value || '',
            sibling_section: document.getElementById('siblingSection')?.value || '',
            sibling_admission_no: document.getElementById('siblingAdmissionNo')?.value || '',
            sibling_academic_year: document.getElementById('siblingAcademicYear')?.value || '',
            father_name: document.getElementById('fatherName')?.value || '',
            father_occupation: document.getElementById('fatherOccupation')?.value || '',
            father_phone: document.getElementById('fatherPhone')?.value || '',
            father_email: document.getElementById('fatherEmail')?.value || '',
            mother_name: document.getElementById('motherName')?.value || '',
            mother_occupation: document.getElementById('motherOccupation')?.value || '',
            mother_phone: document.getElementById('motherPhone')?.value || '',
            mother_email: document.getElementById('motherEmail')?.value || ''
        };
        
        const photoPreview = document.getElementById('photoPreview');
        if (photoPreview && photoPreview.src && photoPreview.src.startsWith('data:image')) {
            formData.photo_data = photoPreview.src;
        }
        
        const existingData = JSON.parse(localStorage.getItem('admissionFormData') || '{}');
        const mergedData = { ...existingData, ...formData };
        localStorage.setItem('admissionFormData', JSON.stringify(mergedData));
        return mergedData;
    }
    
    function nextStep() {
        if (currentStep === 1) {
            if (!applicantType) {
                showToast('Please select applicant type (Student or Parent/Guardian)', 'error');
                const cards = document.querySelectorAll('.option-card');
                cards.forEach(card => card.style.animation = 'shake 0.5s ease-in-out');
                setTimeout(() => cards.forEach(card => card.style.animation = ''), 500);
                return;
            }
            
            if (applicantType === 'parent') {
                applicantName = document.getElementById('applicantName').value.trim();
                applicantRelationship = document.getElementById('applicantRelationship').value;
                if (!applicantName) {
                    showToast('Please enter your name (Parent/Guardian)', 'error');
                    document.getElementById('applicantName').focus();
                    return;
                }
                if (!applicantRelationship) {
                    showToast('Please select your relationship to the student', 'error');
                    document.getElementById('applicantRelationship').focus();
                    return;
                }
            }
            
            document.getElementById('step1').classList.remove('active');
            document.getElementById('step2').classList.add('active');
            currentStep = 2;
        } 
        else if (currentStep === 2) {
            if (!validateStep2()) return;
            saveCurrentStepData();
            document.getElementById('step2').classList.remove('active');
            currentStep++;
            document.getElementById('step3').classList.add('active');
        }
        else if (currentStep === 3) {
            if (!validateStep3()) return;
            saveCurrentStepData();
            document.getElementById('step3').classList.remove('active');
            currentStep++;
            document.getElementById('step4').classList.add('active');
        }
        else if (currentStep === 4) {
            if (!validateStep4()) return;
            saveCurrentStepData();
            document.getElementById('step4').classList.remove('active');
            currentStep++;
            document.getElementById('step5').classList.add('active');
            generateAdmissionFormPreview();
        }
        updateProgress();
    }
    
    function prevStep() {
        saveCurrentStepData();
        document.getElementById(`step${currentStep}`).classList.remove('active');
        currentStep--;
        document.getElementById(`step${currentStep}`).classList.add('active');
        updateProgress();
    }
    
    function updateProgress() {
        const progress = (currentStep / 5) * 100;
        document.getElementById('progressFill').style.width = progress + '%';
    }
    
    function generateAdmissionFormPreview() {
        const formPreview = document.getElementById('admissionFormPreview');
        const basicData = JSON.parse(localStorage.getItem('basic_lead_data') || '{}');
        const additionalData = JSON.parse(localStorage.getItem('admissionFormData') || '{}'); 
        const admissionData = { ...basicData, ...additionalData };
        
        referenceId = localStorage.getItem('reference_id') || referenceId;
        leadId = localStorage.getItem('lead_id') || leadId;
        
        let previewHTML = `
            <div class="form-preview-header"> 
                <h3>Admission Form Preview</h3>
                <div class="ref-id">Reference ID: ${referenceId || 'Pending'}</div>
                <div style="font-size: 14px; margin-top: 10px;">Lead ID: ${leadId || 'Pending'}</div>
            </div>
            <div class="form-preview-section">
                <h4>Application Information</h4>
                <div class="preview-grid-2col">
                    <div class="form-preview-row">
                        <div class="form-preview-label">Applicant Type</div>
                        <div class="form-preview-value">${admissionData.applicant_type === 'student' ? 'Student (Self)' : 'Parent/Guardian'}</div>
                    </div>
                    <div class="form-preview-row">
                        <div class="form-preview-label">Application Status</div>
                        <div class="form-preview-value"><span style="color: var(--warning-orange);">Pending Completion</span></div>
                    </div>
                </div>
            </div>
            <div class="form-preview-section">
                <h4>Student Basic Details</h4>
                <div class="preview-grid-2col">
                    <div class="form-preview-row"><div class="form-preview-label">Full Name</div><div class="form-preview-value">${admissionData.student_full_name || 'Not provided'}</div></div>
                    <div class="form-preview-row"><div class="form-preview-label">Date of Birth</div><div class="form-preview-value">${admissionData.student_dob ? new Date(admissionData.student_dob).toLocaleDateString('en-IN') : 'Not provided'}</div></div>
                    <div class="form-preview-row"><div class="form-preview-label">Gender</div><div class="form-preview-value">${admissionData.student_gender || 'Not provided'}</div></div>
                    <div class="form-preview-row"><div class="form-preview-label">Applying for Grade</div><div class="form-preview-value">${admissionData.applying_for_grade || 'Not provided'}</div></div>
                </div>
            </div>
            <div class="form-preview-section">
                <h4>Contact Information</h4>
                <div class="preview-grid-2col">
                    <div class="form-preview-row"><div class="form-preview-label">Email</div><div class="form-preview-value">${admissionData.email || 'Not provided'}</div></div>
                    <div class="form-preview-row"><div class="form-preview-label">Phone</div><div class="form-preview-value">${admissionData.phone || 'Not provided'}</div></div>
                    <div class="form-preview-row full-width"><div class="form-preview-label">Address</div><div class="form-preview-value">${admissionData.address_line_1 || ''} ${admissionData.address_line_2 ? ', ' + admissionData.address_line_2 : ''}${admissionData.city ? ', ' + admissionData.city : ''}${admissionData.state ? ', ' + admissionData.state : ''}${admissionData.pincode ? ' - ' + admissionData.pincode : ''}</div></div>
                </div>
            </div>
            <div class="form-preview-section">
                <h4>Educational Background</h4>
                <div class="preview-grid-2col">
                    <div class="form-preview-row"><div class="form-preview-label">Previous School</div><div class="form-preview-value">${admissionData.previous_school || 'Not provided'}</div></div>
                    <div class="form-preview-row"><div class="form-preview-label">Previous Class</div><div class="form-preview-value">${admissionData.previous_class || 'Not provided'}</div></div>
                </div>
            </div>
            <div class="form-preview-section">
                <h4>Parent/Guardian Details</h4>
                <div class="preview-grid-2col">
                    <div class="form-preview-row"><div class="form-preview-label">Father's Name</div><div class="form-preview-value">${admissionData.father_name || 'Not provided'}</div></div>
                    <div class="form-preview-row"><div class="form-preview-label">Father's Phone</div><div class="form-preview-value">${admissionData.father_phone || 'Not provided'}</div></div>
                </div>
            </div>
        `;
        formPreview.innerHTML = previewHTML;
    }
    
    function completeApplication() {
        if (!document.getElementById('declarationCheck').checked || !document.getElementById('termsCheck').checked) {
            showToast('Please accept the declaration and terms & conditions', 'error');
            return;
        }
        
        const allData = saveCurrentStepData();
        const leadId = localStorage.getItem('lead_id');
        const referenceId = localStorage.getItem('reference_id');
        
        if (!leadId) {
            showToast('Lead ID not found. Please start over.', 'error');
            return;
        }
        
        allData.lead_id = leadId;
        allData.reference_id = referenceId;
        allData.is_complete_application = true;
        
        fetch(`/update-admission/${leadId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(allData)
        })
        .then(async response => {
            const text = await response.text();
            try {
                return JSON.parse(text);
            } catch (e) {
                throw new Error('Server returned invalid response');
            }
        })
        .then(data => {
            if (data.success) {
                showToast('Application completed successfully!', 'success');
                localStorage.clear();
                setTimeout(() => window.location.href = '/main-form', 1500);
            } else {
                showToast(data.message || 'Error saving application', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error completing application. Please try again.', 'error');
        });
    }
    
    function cleanPhoneNumber(phoneNumber) {
        let cleaned = phoneNumber.replace(/\D/g, '');
        if (cleaned.startsWith('91')) cleaned = cleaned.substring(2);
        return cleaned;
    }
    
    function showToast(message, type = 'error') { 
        Toastify({
            text: message,
            duration: 3000,
            gravity: "top",
            position: "right",
            close: true,
            style: { background: type === 'error' ? "#ef4444" : "#10b981" }
        }).showToast();
    }
    
    // DOM Content Loaded
    document.addEventListener('DOMContentLoaded', function() {
        updateProgress();
        document.getElementById('photoInput').addEventListener('change', previewPhoto);
        
        // Email input change
        document.getElementById('email').addEventListener('input', function() {
            const email = this.value.trim();
            if (email && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                document.getElementById('sendEmailOtpBtn').style.display = 'block';
            } else {
                document.getElementById('sendEmailOtpBtn').style.display = 'none';
                document.getElementById('emailOtpVerificationSection').style.display = 'none';
                isEmailVerified = false;
            }
        });
        
        // Phone input change
        document.getElementById('phone').addEventListener('input', function() {
            const phone = this.value.trim();
            if (phone.length === 10) {
                document.getElementById('sendOtpBtn').style.display = 'block';
                document.getElementById('phoneDisplay').textContent = phone;
            } else {
                document.getElementById('sendOtpBtn').style.display = 'none';
                document.getElementById('otpVerificationSection').style.display = 'none';
                isPhoneVerified = false;
            }
        });
        
        // SEND EMAIL OTP
        document.getElementById('sendEmailOtpBtn').addEventListener('click', async function() {
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
                const response = await fetch('/send-email-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ email_id: email, otp_verification_type: 'admission_email_verification' })
                });
                const result = await response.json();
                
                if (result.success || result.Success) {
                    showToast('OTP sent to your email!', 'success');
                    document.getElementById('emailOtpVerificationSection').style.display = 'block';
                    document.getElementById('emailDisplay').textContent = email;
                    resetEmailOTPUI();
                    startEmailOTPTimer();
                    document.querySelector('.email-otp-digit').focus();
                    generatedEmailOTP = result.otp || '';
                } else {
                    showToast(result.message || 'Failed to send OTP', 'error');
                }
            } catch (error) {
                showToast('Error sending OTP. Please try again.', 'error');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
        
        // RESEND EMAIL OTP
        document.getElementById('resendEmailOtpBtn').addEventListener('click', function() {
            document.getElementById('sendEmailOtpBtn').click();
        });
        
        // VERIFY EMAIL OTP
        document.getElementById('verifyEmailOtpBtn').addEventListener('click', async function() {
            let enteredOTP = '';
            document.querySelectorAll('.email-otp-digit').forEach(input => { enteredOTP += input.value; });
            
            if (enteredOTP.length !== 4) {
                showToast('Please enter complete 4-digit OTP', 'error');
                return;
            }
            
            const email = document.getElementById('email').value.trim();
            const btn = this;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;
            
            try {
                const response = await fetch('/verify-email-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ otp: enteredOTP, email: email, otp_verification_type: 'admission_email_verification' })
                });
                const result = await response.json();
                
                if (result.success || result.Success) {
                    isEmailVerified = true;
                    const statusDiv = document.getElementById('emailOtpStatus');
                    statusDiv.className = 'otp-status success';
                    statusDiv.innerHTML = '<i class="fas fa-check-circle"></i> <strong>Email verified successfully!</strong>';
                    statusDiv.style.display = 'block';
                    document.querySelectorAll('.email-otp-digit').forEach(input => input.disabled = true);
                    document.getElementById('verifyEmailOtpBtn').style.display = 'none';
                    document.getElementById('resendEmailOtpBtn').style.display = 'none';
                    if (emailOtpTimer) clearInterval(emailOtpTimer);
                    document.getElementById('emailTimerText').innerHTML = '<span class="timer-text"><i class="fas fa-check"></i> Verified</span>';
                    showToast('Email verified!', 'success');
                } else {
                    showToast(result.message || 'Invalid OTP', 'error');
                }
            } catch (error) {
                showToast('Error verifying OTP', 'error');
            } finally {
                btn.innerHTML = '<i class="fas fa-check"></i> Verify';
                btn.disabled = false;
            }
        });
        
        // SEND PHONE OTP
        document.getElementById('sendOtpBtn').addEventListener('click', async function() {
            const phone = document.getElementById('phone').value.trim();
            if (!phone || phone.length !== 10) {
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
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ mobile_number: phone })
                });
                const result = await response.json();
                
                if (result.Success) {
                    showToast('OTP sent to your phone!', 'success');
                    document.getElementById('otpVerificationSection').style.display = 'block';
                    document.getElementById('phoneDisplay').textContent = phone;
                    resetPhoneOTPUI();
                    startPhoneOTPTimer();
                    document.querySelector('.phone-otp-digit').focus();
                    generatedPhoneOTP = result.Success;
                } else {
                    showToast(result.message || 'Failed to send OTP', 'error');
                }
            } catch (error) {
                showToast('Error sending OTP. Please try again.', 'error');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
        
        // RESEND PHONE OTP
        document.getElementById('resendPhoneOtpBtn').addEventListener('click', function() {
            document.getElementById('sendOtpBtn').click();
        });
        
        // VERIFY PHONE OTP
        document.getElementById('verifyPhoneOtpBtn').addEventListener('click', async function() {
            let enteredOTP = '';
            document.querySelectorAll('.phone-otp-digit').forEach(input => { enteredOTP += input.value; });
            
            if (enteredOTP.length !== 4) {
                showToast('Please enter complete 4-digit OTP', 'error');
                return;
            }
            
            const phone = document.getElementById('phone').value.trim();
            const btn = this;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;
            
            try {
                const response = await fetch('/verify/otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ mobile_number: phone, otp: enteredOTP })
                });
                const result = await response.json();
                
                if (result.Success || enteredOTP === generatedPhoneOTP) {
                    isPhoneVerified = true;
                    const statusDiv = document.getElementById('phoneOtpStatus');
                    statusDiv.className = 'otp-status success';
                    statusDiv.innerHTML = '<i class="fas fa-check-circle"></i> <strong>Phone verified successfully!</strong>';
                    statusDiv.style.display = 'block';
                    document.querySelectorAll('.phone-otp-digit').forEach(input => input.disabled = true);
                    document.getElementById('verifyPhoneOtpBtn').style.display = 'none';
                    document.getElementById('resendPhoneOtpBtn').style.display = 'none';
                    if (phoneOtpTimer) clearInterval(phoneOtpTimer);
                    document.getElementById('phoneTimerText').innerHTML = '<span class="timer-text"><i class="fas fa-check"></i> Verified</span>';
                    showToast('Phone verified!', 'success');
                } else {
                    showToast('Invalid OTP. Please try again.', 'error');
                }
            } catch (error) {
                showToast('Error verifying OTP', 'error');
            } finally {
                btn.innerHTML = '<i class="fas fa-check"></i> Verify';
                btn.disabled = false;
            }
        });
        
        // OTP digit navigation
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('email-otp-digit') || e.target.classList.contains('phone-otp-digit')) {
                if (e.target.value.length === 1 && /^\d$/.test(e.target.value)) {
                    const next = e.target.nextElementSibling;
                    if (next && (next.classList.contains('email-otp-digit') || next.classList.contains('phone-otp-digit'))) {
                        next.focus();
                    }
                }
            }
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.target.classList.contains('email-otp-digit') || e.target.classList.contains('phone-otp-digit')) {
                if (e.key === 'Backspace' && e.target.value === '') {
                    const prev = e.target.previousElementSibling;
                    if (prev && (prev.classList.contains('email-otp-digit') || prev.classList.contains('phone-otp-digit'))) {
                        prev.focus();
                    }
                }
            }
        });
    });
</script>
@endsection
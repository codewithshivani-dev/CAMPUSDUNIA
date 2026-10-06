
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>OTP Verification | Student Admission</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f4fc 0%, #e9eefa 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* All your existing styles remain the same */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .lead-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }
        
        .lead-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .lead-info h2 {
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: 1.8rem;
        }
        
        .lead-id {
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        .lead-status {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            background: #e8f6f3;
            color: #27ae60;
        }
        
        .lead-status.cold {
            background: #e3f2fd;
            color: #1976d2;
        }
        
        .lead-status.warm {
            background: #fff3e0;
            color: #e65100;
        }
        
        .lead-status.hot {
            background: #ffebee;
            color: #c62828;
        }
        
        .lead-status.pending {
            background: #fef9e7;
            color: #f39c12;
        }
        
        .lead-status.converted {
            background: #e8f5e9;
            color: #2e7d32;
        }
        
        /* Tab System Styles */
        .tab-container {
            margin: 30px 0;
        }
        
        .tab-header {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 3px solid #e0e0e0;
            padding-bottom: 10px;
        }
        
        .tab-item {
            flex: 1;
            text-align: center;
            padding: 15px 10px;
            border-radius: 10px 10px 0 0;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            background: #f8f9fa;
            border: 2px solid transparent;
            border-bottom: none;
        }
        
        .tab-item.completed {
            background: #d5f4e6;
            color: #27ae60;
        }
        
        .tab-item.in-progress {
            background: #e1f0fa;
            color: #3498db;
            border-top: 2px solid #3498db;
            border-left: 2px solid #3498db;
            border-right: 2px solid #3498db;
        }
        
        .tab-item.pending {
            background: #fef9e7;
            color: #f39c12;
        }
        
        .tab-item.disabled {
            background: #ecf0f1;
            color: #95a5a6;
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .tab-item.active {
            background: white;
            border-top: 3px solid #3498db;
            border-left: 2px solid #e0e0e0;
            border-right: 2px solid #e0e0e0;
            transform: translateY(-5px);
            box-shadow: 0 -5px 10px rgba(0,0,0,0.05);
            font-weight: bold;
        }
        
        .tab-number {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            border-radius: 50%;
            margin-right: 10px;
            font-weight: bold;
        }
        
        .tab-item.completed .tab-number {
            background: #27ae60;
            color: white;
        }
        
        .tab-item.in-progress .tab-number {
            background: #3498db;
            color: white;
            animation: pulse 2s infinite;
        }
        
        .tab-item.pending .tab-number {
            background: #f39c12;
            color: white;
        }
        
        .tab-item.disabled .tab-number {
            background: #bdc3c7;
            color: white;
        }
        
        .tab-status-icon {
            margin-left: 8px;
            font-size: 0.9rem;
        }
        
        .tab-content {
            background: white;
            border-radius: 0 0 12px 12px;
            padding: 30px;
            border: 2px solid #e0e0e0;
            border-top: none;
            min-height: 400px;
        }
        
        .tab-pane {
            display: none;
        }
        
        .tab-pane.active {
            display: block;
        }
        
        .lock-overlay {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            color: #7f8c8d;
        }
        
        /* Progress Section */
        .progress-section {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 25px;
            margin: 30px 0;
        }
        
        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .progress-title {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .progress-percentage {
            font-weight: 600;
            color: #3498db;
            font-size: 1.1rem;
        }
        
        .progress-bar {
            height: 10px;
            background: #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3498db, #2ecc71);
            border-radius: 5px;
            transition: width 0.5s ease;
        }
        
        /* Payment Status */
        .payment-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .payment-paid {
            background: #d5f4e6;
            color: #27ae60;
        }
        
        .payment-pending {
            background: #fef9e7;
            color: #f39c12;
        }
        
        .payment-overdue {
            background: #fdeded;
            color: #e74c3c;
        }
        
        /* Pay Button */
        .pay-button {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .pay-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }
        
        .pay-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        /* Test Attempt Badge */
        .attempt-badge {
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .attempt-first {
            background: #e1f0fa;
            color: #3498db;
        }
        
        .attempt-improvement {
            background: #fef9e7;
            color: #f39c12;
        }
        
        .attempt-final {
            background: #d5f4e6;
            color: #27ae60;
        }
        
        /* Test Mode Badge */
        .test-mode-badge {
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .mode-online {
            background: #e3f2fd;
            color: #1976d2;
            border: 1px solid #90caf9;
        }
        
        .mode-offline {
            background: #fff3e0;
            color: #e65100;
            border: 1px solid #ffb74d;
        }
        
        /* Info Item */
        .info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
            color: #2c3e50;
        }
        
        .info-item i {
            color: #3498db;
            width: 16px;
        }
        
        .step-info {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 15px;
        }
        
        /* Counselor Card */
        .counselor-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            border: 2px solid #e8f6f3;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .counselor-avatar {
            width: 60px;
            height: 60px;
            background: #3498db;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.4rem;
        }
        
        .counselor-info h4 {
            margin: 0 0 5px 0;
            color: #2c3e50;
        }
        
        .counselor-info p {
            margin: 5px 0;
            color: #7f8c8d;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        /* Fee Structure Card */
        .fee-structure-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .fee-structure-card::before {
            content: '💰';
            position: absolute;
            right: 20px;
            bottom: 20px;
            font-size: 80px;
            opacity: 0.1;
        }
        
        .fee-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .fee-title {
            font-size: 1.4rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .fee-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .fee-item {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 15px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .fee-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        
        .fee-amount {
            font-size: 1.4rem;
            font-weight: 700;
        }
        
        .fee-total {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid rgba(255, 255, 255, 0.2);
            display: flex;
            justify-content: flex-end;
        }
        
        .total-amount {
            font-size: 1.8rem;
            font-weight: 700;
        }
        
        /* Details Grid */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .detail-item {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
        }
        
        .detail-label {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-size: 1rem;
            color: #2c3e50;
            font-weight: 500;
        }
        
        /* Next Steps */
        .next-steps {
            background: #f0f9ff;
            border-radius: 10px;
            padding: 25px;
            margin-top: 30px;
        }
        
        .next-steps h4 {
            color: #3498db;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .next-steps-list {
            list-style: none;
            padding: 0;
        }
        
        .next-steps-list li {
            padding: 12px 0;
            border-bottom: 1px solid #e0f0ff;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
        }
        
        .next-steps-list li:last-child {
            border-bottom: none;
        }
        
        .next-steps-list i {
            color: #3498db;
        }
        
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .modal-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            max-width: 400px;
            width: 90%;
            position: relative;
        }
        
        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 1.2rem;
            cursor: pointer;
            color: #7f8c8d;
        }
        
        .payment-methods {
            margin: 20px 0;
        }
        
        .payment-method {
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .payment-method:hover {
            border-color: #3498db;
            background: #f0f9ff;
        }
        
        .payment-method.selected {
            border-color: #3498db;
            background: #f0f9ff;
        }
        
        .payment-method i {
            margin-right: 10px;
            color: #3498db;
        }
        
        .process-payment {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #bdc3c7;
        }
        
        /* Animations */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(52, 152, 219, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(52, 152, 219, 0); }
            100% { box-shadow: 0 0 0 0 rgba(52, 152, 219, 0); }
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fee-structure-card {
            animation: slideIn 0.6s ease-out;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .lead-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .tab-header {
                flex-direction: column;
            }
            
            .details-grid {
                grid-template-columns: 1fr;
            }
            
            .fee-grid {
                grid-template-columns: 1fr;
            }
            
            .counselor-card {
                flex-direction: column;
                text-align: center;
            }
            
            .step-info {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    @php
        // Extract data from the data array
        $lead = $data['lead'] ?? null;
        $studentlead = $data['studentlead'] ?? null;
        $admissionConfig = $studentlead->admissionConfig ?? null;
        $entranceTestSlot = $studentlead->entranceTestSlot ?? null;
        $counsellingSlot = $studentlead->counsellingSlot ?? null;
        
        // Set default values if data is missing
        $leadName = $lead->name ?? 'Not Provided';
        $leadId = $lead->lead_id ?? 'N/A';
        $leadStatus = $lead->lead_status ?? 'pending';
        $leadEmail = $lead->email ?? 'Not Provided';
        $leadPhone = $lead->phone_no ?? 'Not Provided';
        $leadCreatedAt = $lead->created_at ?? now();
        $leadFollowUp = $lead->follow_up ?? null;
        $defaultAssign = $lead->default_assign ?? 'Not Assigned';
        
        // Student Admission Process Data
        $registrationDate = $studentlead->registration_date ?? null;
        $registrationPayment = $studentlead->registration_payment ?? '0.00';
        $submissionDate = $studentlead->submission_date ?? $leadCreatedAt;
        $paymentDate = $studentlead->payment_date ?? null;
        
        // Entrance Test Data
        $entranceTestDate = $studentlead->entrance_test_date ?? null;
        $entranceTestTime = $studentlead->entrance_test_time ?? null;
        $testName = $studentlead->test_name ?? ($entranceTestSlot->test_name ?? 'Entrance Test');
        $testStatus = $studentlead->test_status ?? 'pending';
        $testPayment = $studentlead->test_payment ?? '0.00';
        $testReattemptDate = $studentlead->test_reattempt_date ?? null;
        
        // Entrance Test Slot Data
        $slotTestDate = $entranceTestSlot->test_date ?? null;
        $slotStartTime = $entranceTestSlot->start_time ?? null;
        $slotEndTime = $entranceTestSlot->end_time ?? null;
        $slotVenue = $entranceTestSlot->venue ?? 'To be announced';
        $slotOnlinePlatform = $entranceTestSlot->online_platform ?? 'https://exam.institute.edu';
        $slotInstructions = $entranceTestSlot->instructions ?? 'Read all instructions carefully before starting the test';
        $slotDuration = $entranceTestSlot->duration_minutes ?? 120;
        
        // Admission Config Data
        $admissionFormEnabled = $admissionConfig->admission_form_enabled ?? 1;
        $admissionFormMode = $admissionConfig->admission_form_mode ?? 'online';
        $admissionFormFeeAmount = $admissionConfig->admission_form_fee_amount ?? 0;
        $entranceTestsEnabled = $admissionConfig->entrance_tests_enabled ?? 1;
        $entranceTestsMode = $admissionConfig->entrance_tests_mode ?? 'online';
        $counsellingEnabled = $admissionConfig->counselling_enabled ?? 1;
        $counsellingMode = $admissionConfig->counselling_mode ?? 'online';
        $onboardingEnabled = $admissionConfig->onboarding_enabled ?? 1;
        $onboardingAdmissionFee = $admissionConfig->onboarding_admission_fee ?? 10000;
        $onboardingSecurityDeposit = $admissionConfig->onboarding_security_deposit ?? 5000;
        $onboardingOtherCharges = $admissionConfig->onboarding_other_charges ?? 2000;
        $onboardingStartDate = $admissionConfig->onboarding_start_date ?? null;
        $classesStartDate = $admissionConfig->onboarding_classes_start_date ?? null;
        $academicYear = $admissionConfig->academic_year ?? date('Y') . '-' . (date('Y') + 1);
        
        // Counselling Data
        $counsellingDate = $studentlead->counselling_date ?? ($counsellingSlot->slot_date ?? null);
        $counsellingTime = $studentlead->counselling_time ?? ($counsellingSlot->start_time ?? null);
        $counsellingStatus = $studentlead->counselling_status ?? 'pending';
        $counsellingSlotDate = $counsellingSlot->slot_date ?? null;
        $counsellingSlotTime = $counsellingSlot->start_time ?? null;
        
        // Onboarding Data
        $onboardingDate = $studentlead->onboarding_date ?? $onboardingStartDate;
        
        // Step Status from database
        $stepFirst = $studentlead->step_first ?? 'pending';
        $stepSecond = $studentlead->step_second ?? 'pending';
        $stepThird = $studentlead->step_third ?? 'pending';
        $stepFourth = $studentlead->step_fourth ?? 'pending';
        
        // Determine if steps are completed
        $admissionCompleted = $stepFirst == 'completed';
        $entranceTestCompleted = $stepSecond == 'completed';
        $counsellingCompleted = $stepThird == 'completed';
        $onboardingCompleted = $stepFourth == 'completed';
        
        // Step enabling logic
        $step1Enabled = true;
        $step2Enabled = $step1Enabled && $admissionCompleted && $entranceTestsEnabled;
        $step3Enabled = $step2Enabled && $entranceTestCompleted && $counsellingEnabled;
        $step4Enabled = $step3Enabled && $counsellingCompleted && $onboardingEnabled;
        
        // Determine step classes for tabs
        $step1Class = $admissionCompleted ? 'completed' : 'pending';
        $step2Class = !$step2Enabled ? 'disabled' : ($entranceTestCompleted ? 'completed' : ($testStatus == 'in_progress' ? 'in-progress' : 'pending'));
        $step3Class = !$step3Enabled ? 'disabled' : ($counsellingCompleted ? 'completed' : ($counsellingStatus == 'in_progress' ? 'in-progress' : 'pending'));
        $step4Class = !$step4Enabled ? 'disabled' : ($onboardingCompleted ? 'completed' : 'pending');
        
        // Fee amounts
        $admissionFeeAmount = floatval($admissionFormFeeAmount) > 0 ? floatval($admissionFormFeeAmount) : 500;
        $entranceTestFeeAmount = floatval($testPayment) > 0 ? floatval($testPayment) : 1200;
        $registrationPaymentStatus = floatval($registrationPayment) > 0 ? 'paid' : 'pending';
        $testPaymentStatus = floatval($testPayment) > 0 ? 'paid' : 'pending';
        
        // Course details
        $productId = $admissionConfig->product_id ?? null;
        $courseName = 'BCA';
        $courseDuration = '3 Years';
        
        // Fee structure
        $semesterFees = floatval($onboardingAdmissionFee) ?: 45000;
        $labFees = 5000;
        $libraryFees = 3000;
        $sportsFees = 2000;
        $examFees = 2500;
        $hostelFees = floatval($onboardingSecurityDeposit) ?: 60000;
        $transportFees = 15000;
        $otherCharges = floatval($onboardingOtherCharges) ?: 2000;
        $totalFees = $semesterFees + $labFees + $libraryFees + $sportsFees + $examFees + $otherCharges;
        
        // Test attempt type
        $testAttempt = $testReattemptDate ? 'improvement' : 'first';
        
        // Format test time
        $formattedTestTime = '';
        if ($slotStartTime && $slotEndTime) {
            $formattedTestTime = \Carbon\Carbon::parse($slotStartTime)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($slotEndTime)->format('h:i A');
        } elseif ($entranceTestTime) {
            $formattedTestTime = \Carbon\Carbon::parse($entranceTestTime)->format('h:i A');
        } else {
            $formattedTestTime = '10:00 AM - 12:00 PM';
        }
        
        // Test date
        $displayTestDate = $slotTestDate ?? $entranceTestDate ?? now()->addDays(15);
        
        // Counselling date and time
        $displayCounsellingDate = $counsellingSlotDate ?? $counsellingDate ?? now()->addDays(25);
        $displayCounsellingTime = '';
        if ($counsellingSlotTime) {
            $displayCounsellingTime = \Carbon\Carbon::parse($counsellingSlotTime)->format('h:i A');
        } elseif ($counsellingTime) {
            $displayCounsellingTime = \Carbon\Carbon::parse($counsellingTime)->format('h:i A');
        } else {
            $displayCounsellingTime = '11:00 AM';
        }
        
        // Onboarding date
        $displayOnboardingDate = $onboardingDate ?? $onboardingStartDate ?? now()->addDays(40);
        
        // Counsellor details
        $counsellorName = $defaultAssign ?? 'Admission Counselor';
        $counsellorDesignation = 'Admission Counselor';
        $counsellorEmail = $leadEmail;
        $counsellorPhone = $leadPhone;
        
        // Online test link
        $testOnlineLink = $slotOnlinePlatform;
        if ($testOnlineLink == 'https://exam.institute.edu' && $lead->lead_id) {
            $testOnlineLink = 'https://exam.institute.edu/entrance-test/' . $lead->id;
        }
        
        // Counselling link
        $counsellingLink = 'https://zoom.us/j/' . ($studentlead->id ?? '123456789');
        
        // Registration mode
        $registrationMode = $lead->registration_mode ?? 'offline';
        
        // Determine if we can show any content
        $canShowStep2 = $step2Enabled && $entranceTestsEnabled;
        $canShowStep3 = $step3Enabled && $counsellingEnabled;
        $canShowStep4 = $step4Enabled && $onboardingEnabled;
    @endphp

    <div class="container">
        <!-- Lead Card -->
        <div class="lead-card">
            <div class="lead-header">
                <div class="lead-info">
                    <h2>{{ $leadName }}</h2>
                    <div class="lead-id">
                        <i class="fas fa-id-card"></i> Application ID: {{ $studentlead->reference_id ?? $leadId }}
                        @if($studentlead->reference_id && $leadId != $studentlead->reference_id)
                            <span style="margin-left: 10px; color: #7f8c8d;">| Lead ID: {{ $leadId }}</span>
                        @endif
                    </div>
                </div>
                <div class="lead-status {{ $leadStatus }}">
                    <i class="fas fa-{{ $leadStatus == 'converted' ? 'check-circle' : ($leadStatus == 'cold' ? 'snowflake' : ($leadStatus == 'warm' ? 'sun' : ($leadStatus == 'hot' ? 'fire' : 'clock'))) }}"></i>
                    {{ ucfirst($leadStatus) }}
                </div>
            </div>

            <!-- Lead Details -->
            <div class="journey-section">
                <h3 class="section-title">
                    <i class="fas fa-info-circle"></i> Your Application Details
                </h3>
                
                <div class="details-grid">
                    <div class="detail-item">
                        <div class="detail-label">Full Name</div>
                        <div class="detail-value">{{ $leadName }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Email Address</div>
                        <div class="detail-value">{{ $leadEmail }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Phone Number</div>
                        <div class="detail-value">{{ $leadPhone }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Course Applied</div>
                        <div class="detail-value">{{ $courseName }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Program Duration</div>
                        <div class="detail-value">{{ $courseDuration }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Academic Year</div>
                        <div class="detail-value">{{ $academicYear }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Registration Date</div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($registrationDate ?? $leadCreatedAt)->format('d M Y') }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Application ID</div>
                        <div class="detail-value">{{ $studentlead->reference_id ?? 'N/A' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Reference ID</div>
                        <div class="detail-value">{{ $leadId }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Next Follow-up</div>
                        <div class="detail-value">{{ $leadFollowUp ? \Carbon\Carbon::parse($leadFollowUp)->format('d M Y') : 'Not scheduled' }}</div>
                    </div>
                </div>
            </div>

            <!-- Progress Section -->
            <div class="progress-section">
                <div class="progress-header">
                    <div class="progress-title">
                        <i class="fas fa-chart-line"></i> Overall Admission Progress
                    </div>
                    <div class="progress-percentage" id="progressPercentage">0%</div> 
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill" style="width: 0%"></div>
                </div>
            </div>

            <!-- Tab System -->
            <div class="tab-container">
                <div class="tab-header">
                    <!-- Tab 1: Admission Form -->
                    <div class="tab-item {{ $step1Class }} {{ $step1Enabled ? 'active' : '' }}" onclick="switchTab(1, {{ $step1Enabled ? 'true' : 'false' }})" id="tab-1">
                        <span class="tab-number">1</span>
                        Admission Form
                        @if($admissionCompleted)
                            <span class="tab-status-icon"><i class="fas fa-check-circle"></i></span>
                        @elseif($step1Enabled)
                            <span class="tab-status-icon"><i class="fas fa-hourglass-half"></i></span>
                        @else
                            <span class="tab-status-icon"><i class="fas fa-lock"></i></span>
                        @endif
                    </div>

                    <!-- Tab 2: Entrance Test -->
                    <div class="tab-item {{ $step2Class }}" onclick="switchTab(2, {{ $step2Enabled ? 'true' : 'false' }})" id="tab-2">
                        <span class="tab-number">2</span>
                        Entrance Test
                        @if($entranceTestCompleted)
                            <span class="tab-status-icon"><i class="fas fa-check-circle"></i></span>
                        @elseif($step2Enabled)
                            <span class="tab-status-icon"><i class="fas fa-hourglass-half"></i></span>
                        @else
                            <span class="tab-status-icon"><i class="fas fa-lock"></i></span>
                        @endif
                    </div>

                    <!-- Tab 3: Counselling -->
                    <div class="tab-item {{ $step3Class }}" onclick="switchTab(3, {{ $step3Enabled ? 'true' : 'false' }})" id="tab-3">
                        <span class="tab-number">3</span>
                        Counselling
                        @if($counsellingCompleted)
                            <span class="tab-status-icon"><i class="fas fa-check-circle"></i></span>
                        @elseif($step3Enabled)
                            <span class="tab-status-icon"><i class="fas fa-hourglass-half"></i></span>
                        @else
                            <span class="tab-status-icon"><i class="fas fa-lock"></i></span>
                        @endif
                    </div>

                    <!-- Tab 4: Onboarding -->
                    <div class="tab-item {{ $step4Class }}" onclick="switchTab(4, {{ $step4Enabled ? 'true' : 'false' }})" id="tab-4">
                        <span class="tab-number">4</span>
                        Onboarding
                        @if($onboardingCompleted)
                            <span class="tab-status-icon"><i class="fas fa-check-circle"></i></span>
                        @elseif($step4Enabled)
                            <span class="tab-status-icon"><i class="fas fa-hourglass-half"></i></span>
                        @else
                            <span class="tab-status-icon"><i class="fas fa-lock"></i></span>
                        @endif
                    </div>
                </div>

                <div class="tab-content">
                    <!-- Tab Pane 1: Admission Form -->
                    <div class="tab-pane active" id="pane-1">
                        <h3 style="color: #2c3e50; margin-bottom: 20px;">
                            <i class="fas fa-file-alt" style="color: #3498db;"></i> 
                            Admission Form Submission
                            @if($admissionCompleted)
                                <span style="font-size: 0.9rem; background: #d5f4e6; color: #27ae60; padding: 5px 12px; border-radius: 20px; margin-left: 15px;">
                                    <i class="fas fa-check-circle"></i> Completed
                                </span>
                            @endif
                        </h3>
                        
                        <p>Your admission form has been {{ $admissionCompleted ? 'submitted successfully' : 'initiated' }}.</p>
                        
                        <!-- Admission Period Dates -->
                        <div style="background: #e8f4fd; border-radius: 10px; padding: 15px; margin-bottom: 20px; border-left: 4px solid #3498db;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                <i class="fas fa-calendar-alt" style="color: #3498db;"></i>
                                <strong style="color: #2c3e50;">Admission Period</strong>
                            </div>
                            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-play-circle" style="color: #27ae60;"></i>
                                    <span><strong>Start Date:</strong> {{ $admissionConfig->admission_form_start_date ? \Carbon\Carbon::parse($admissionConfig->admission_form_start_date)->format('d M Y') : 'Not set' }}</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-stop-circle" style="color: #e74c3c;"></i>
                                    <span><strong>End Date:</strong> {{ $admissionConfig->admission_form_end_date ? \Carbon\Carbon::parse($admissionConfig->admission_form_end_date)->format('d M Y') : 'Not set' }}</span>
                                </div>
                                @if($admissionConfig->admission_form_end_date && \Carbon\Carbon::parse($admissionConfig->admission_form_end_date)->isPast() && !$admissionCompleted)
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <span style="background: #fdeded; color: #e74c3c; padding: 4px 12px; border-radius: 15px; font-size: 0.85rem;">
                                            <i class="fas fa-exclamation-triangle"></i> Deadline Passed
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="step-info">
                            <div class="info-item">
                                <i class="fas fa-calendar-check"></i>
                                <strong>Submission Date:</strong> 
                                @if($studentlead->submission_date)
                                    {{ \Carbon\Carbon::parse($studentlead->submission_date)->format('d M Y, h:i A') }}
                                @else
                                    <span style="color: #f39c12;">Not submitted yet</span>
                                @endif
                            </div>
                            <div class="info-item">
                                <i class="fas fa-money-bill-wave"></i>
                                <strong>Application Fee:</strong> ₹{{ number_format($admissionFeeAmount, 2) }}
                            </div>
                            <div class="info-item">
                                <span class="payment-status payment-{{ $registrationPaymentStatus }}">
                                    <i class="fas fa-{{ $registrationPaymentStatus == 'paid' ? 'check-circle' : 'clock' }}"></i>
                                    Fee Status: {{ ucfirst($registrationPaymentStatus) }}
                                </span>
                            </div>
                            @if($registrationMode)
                            <div class="info-item">
                                <i class="fas fa-{{ $registrationMode == 'online' ? 'wifi' : 'building' }}"></i>
                                <strong>Registration Mode:</strong> {{ ucfirst($registrationMode) }}
                            </div>
                            @endif
                        </div>
                        
                        @if($registrationPaymentStatus != 'paid')
                            <div style="margin-top: 20px;">
                                <button class="pay-button d-none" onclick="openPaymentModal('Admission Application Fee', {{ $admissionFeeAmount }})">
                                    <i class="fas fa-credit-card"></i>
                                    Pay Application Fee Now
                                </button>
                            </div>
                        @endif
                        
                        @if($studentlead->payment_date)
                        <div style="margin-top: 15px; font-size: 0.9rem; color: #7f8c8d;">
                            <i class="fas fa-check-circle" style="color: #27ae60;"></i> 
                            Payment completed on: {{ \Carbon\Carbon::parse($studentlead->payment_date)->format('d M Y, h:i A') }}
                        </div>
                        @endif
                    </div>

                    <!-- Tab Pane 2: Entrance Test -->
                    <div class="tab-pane" id="pane-2">
                        @if(!$entranceTestsEnabled)
                            <div class="lock-overlay">
                                <i class="fas fa-ban" style="font-size: 3rem; margin-bottom: 15px; color: #bdc3c7;"></i>
                                <h4 style="color: #2c3e50;">Entrance Test Not Required</h4>
                                <p>Entrance test is not required for your admission program.</p>
                            </div>
                        @elseif(!$step2Enabled)
                            <div class="lock-overlay">
                                <i class="fas fa-lock" style="font-size: 3rem; margin-bottom: 15px; color: #bdc3c7;"></i>
                                <h4 style="color: #2c3e50;">Step Locked</h4>
                                <p>This step will be enabled after you complete the Admission Form.</p>
                                <p style="font-size: 0.9rem; margin-top: 10px;">Current Status: {{ $admissionCompleted ? 'Admission Form Completed' : 'Admission Form Pending' }}</p>
                            </div>
                        @else
                            <h3 style="color: #2c3e50; margin-bottom: 20px;">
                                <i class="fas fa-pencil-alt" style="color: #3498db;"></i> 
                                Entrance Test Details
                                @if($entranceTestCompleted)
                                    <span style="font-size: 0.9rem; background: #d5f4e6; color: #27ae60; padding: 5px 12px; border-radius: 20px; margin-left: 15px;">
                                        <i class="fas fa-check-circle"></i> Completed
                                    </span>
                                @elseif($testStatus == 'in_progress')
                                    <span style="font-size: 0.9rem; background: #e1f0fa; color: #3498db; padding: 5px 12px; border-radius: 20px; margin-left: 15px;">
                                        <i class="fas fa-spinner fa-spin"></i> In Progress
                                    </span>
                                @endif
                            </h3>
                            
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                                <span class="attempt-badge attempt-{{ $testAttempt }}">
                                    <i class="fas fa-redo-alt"></i>
                                    {{ ucfirst($testAttempt) }} Attempt
                                </span>
                                
                                <span class="test-mode-badge mode-{{ $entranceTestsMode }}">
                                    <i class="fas fa-{{ $entranceTestsMode == 'online' ? 'wifi' : 'building' }}"></i>
                                    {{ ucfirst($entranceTestsMode) }} Mode
                                </span>
                            </div>
                            
                            <div class="step-info">
                                <div class="info-item">
                                    <i class="fas fa-flask"></i>
                                    <strong>Test Name:</strong> {{ $testName }}
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-calendar-check"></i>
                                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($displayTestDate)->format('d M Y') }}
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-clock"></i>
                                    <strong>Time:</strong> {{ $formattedTestTime }}
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-hourglass-half"></i>
                                    <strong>Duration:</strong> {{ $slotDuration }} minutes
                                </div>
                                
                                @if($entranceTestsMode == 'online')
                                    <div class="info-item" style="flex-wrap: wrap; width: 100%;">
                                        <i class="fas fa-link"></i>
                                        <strong>Test Link:</strong> 
                                        <a href="{{ $testOnlineLink }}" target="_blank" 
                                        style="color: #1976d2; text-decoration: underline; margin-left: 5px; word-break: break-all;">
                                            {{ $testOnlineLink }}
                                        </a>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Instructions:</strong> {{ $slotInstructions }}
                                    </div>
                                @else
                                    <div class="info-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <strong>Venue:</strong> {{ $slotVenue }}
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Reporting Time:</strong> 30 minutes before test
                                    </div>
                                @endif
                                
                                <div class="info-item">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <strong>Entrance Fee:</strong> ₹{{ number_format($entranceTestFeeAmount, 2) }}
                                </div>
                                <div class="info-item">
                                    <span class="payment-status payment-{{ $testPaymentStatus }}">
                                        <i class="fas fa-{{ $testPaymentStatus == 'paid' ? 'check-circle' : 'clock' }}"></i>
                                        Fee Status: {{ ucfirst($testPaymentStatus) }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Test Results Section (Only show if test is completed) -->
                            @if(isset($studentlead->test_status) &&  $studentlead->test_status == 'completed')
                                @php

                                    $totalMarks = $studentlead->test_marks ?? 100;
                                    $obtainedMarks = $studentlead->test_obtained_marks ?? 20;
                                    $passingMarks = $studentlead->test_passing_marks ?? 35;
                                    $testStatus = $studentlead->test_result_status ?? ($obtainedMarks >= $passingMarks ? 'passed' : 'failed');
                                    $percentage = $totalMarks > 0 ? round(($obtainedMarks / $totalMarks) * 100, 2) : 0;
                                @endphp
                                
                                <div style="margin-top: 25px; background: linear-gradient(135deg, #f5f7fa, #e9edf5); border-radius: 12px; padding: 20px;">
                                    <h4 style="color: #2c3e50; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-chart-bar" style="color: #3498db;"></i>
                                        Test Results
                                    </h4>
                                    
                                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                                        <!-- Total Marks Card -->
                                        <div style="background: white; border-radius: 10px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                                <div style="width: 35px; height: 35px; background: #3498db20; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-trophy" style="color: #3498db;"></i>
                                                </div>
                                                <span style="color: #7f8c8d; font-size: 0.9rem;">Total Marks</span>
                                            </div>
                                            <div style="font-size: 1.8rem; font-weight: 700; color: #2c3e50;">{{ $totalMarks }}</div>
                                        </div>
                                        
                                        <!-- Obtained Marks Card -->
                                        <div style="background: white; border-radius: 10px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                                <div style="width: 35px; height: 35px; background: #27ae6020; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-star" style="color: #27ae60;"></i>
                                                </div>
                                                <span style="color: #7f8c8d; font-size: 0.9rem;">Obtained Marks</span>
                                            </div>
                                            <div style="font-size: 1.8rem; font-weight: 700; color: #2c3e50;">{{ $obtainedMarks }}</div>
                                        </div>
                                        
                                        <!-- Percentage Card -->
                                        <div style="background: white; border-radius: 10px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                                <div style="width: 35px; height: 35px; background: #f39c1220; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-percent" style="color: #f39c12;"></i>
                                                </div>
                                                <span style="color: #7f8c8d; font-size: 0.9rem;">Percentage</span>
                                            </div>
                                            <div style="font-size: 1.8rem; font-weight: 700; color: #2c3e50;">{{ $percentage }}%</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Passing Marks Info -->
                                    <div style="margin-top: 15px; background: white; border-radius: 8px; padding: 12px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <i class="fas fa-arrow-up" style="color: #e67e22;"></i>
                                            <span><strong>Passing Marks:</strong> {{ $passingMarks }} ({{ $totalMarks > 0 ? round(($passingMarks / $totalMarks) * 100, 2) : 0 }}%)</span>
                                        </div>
                                        
                                        <!-- Pass/Fail Status Badge -->
                                        @if($testStatus == 'passed')
                                            <div style="background: #d5f4e6; color: #27ae60; padding: 8px 20px; border-radius: 30px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                                                <i class="fas fa-check-circle"></i>
                                                PASSED
                                            </div>
                                        @else
                                            <div style="background: #fdeded; color: #e74c3c; padding: 8px 20px; border-radius: 30px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                                                <i class="fas fa-times-circle"></i>
                                                FAILED
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Performance Meter -->
                                    <div style="margin-top: 15px;">
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                            <span style="color: #7f8c8d; font-size: 0.9rem;">Performance</span>
                                            <span style="color: #2c3e50; font-weight: 600;">{{ $percentage }}%</span>
                                        </div>
                                        <div style="height: 10px; background: #e0e0e0; border-radius: 5px; overflow: hidden;">
                                            <div style="height: 100%; width: {{ $percentage }}%; background: {{ $testStatus == 'passed' ? 'linear-gradient(90deg, #27ae60, #2ecc71)' : 'linear-gradient(90deg, #e74c3c, #c0392b)' }}; border-radius: 5px;"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Rank/Achievement (Optional) -->
                                    @if($studentlead->test_rank)
                                    <div style="margin-top: 15px; background: linear-gradient(135deg, #f1c40f20, #f39c1220); border-radius: 8px; padding: 10px 15px; display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-crown" style="color: #f39c12;"></i>
                                        <span><strong>Rank:</strong> #{{ $studentlead->test_rank }}</span>
                                    </div>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Test Preparation Card for Online Mode -->
                            @if($entranceTestsMode == 'online' && $testPaymentStatus == 'paid' && !$entranceTestCompleted)
                            <div style="margin-top: 25px; background: linear-gradient(135deg, #667eea15, #764ba215); border-radius: 12px; padding: 20px; border: 1px solid #667eea30;">
                                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                        <i class="fas fa-video"></i>
                                    </div>
                                    <div>
                                        <h4 style="margin: 0; color: #2c3e50; font-size: 1rem;">Online Test Portal Ready</h4>
                                        <p style="margin: 5px 0 0 0; color: #7f8c8d; font-size: 0.85rem;">Click below to start your entrance test</p>
                                    </div>
                                </div>
                                
                                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                    <a href="{{ $testOnlineLink }}" target="_blank" class="pay-button" style="background: linear-gradient(135deg, #667eea, #764ba2); text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-external-link-alt"></i>
                                        Launch Test Portal
                                    </a>
                                    <button class="pay-button" style="background: #95a5a6;" onclick="viewTestInstructions('{{ addslashes($slotInstructions) }}', '{{ $formattedTestTime }}', '{{ $slotDuration }}')">
                                        <i class="fas fa-file-alt"></i>
                                        View Instructions
                                    </button>
                                </div>
                                
                                <!-- System Requirements -->
                                <div style="margin-top: 15px; display: flex; flex-wrap: wrap; gap: 10px; font-size: 0.85rem; color: #7f8c8d;">
                                    <span style="display: flex; align-items: center; gap: 5px;"><i class="fas fa-check-circle" style="color: #27ae60;"></i> Stable Internet</span>
                                    <span style="display: flex; align-items: center; gap: 5px;"><i class="fas fa-check-circle" style="color: #27ae60;"></i> Webcam Required</span>
                                    <span style="display: flex; align-items: center; gap: 5px;"><i class="fas fa-check-circle" style="color: #27ae60;"></i> Chrome/Firefox</span>
                                    <span style="display: flex; align-items: center; gap: 5px;"><i class="fas fa-check-circle" style="color: #27ae60;"></i> Microphone</span>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Offline Mode Instructions -->
                            @if($entranceTestsMode == 'offline' && $testPaymentStatus == 'paid' && !$entranceTestCompleted)
                            <div style="margin-top: 25px; background: #fff3e0; border-radius: 12px; padding: 20px; border: 1px solid #ffb74d;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <div style="width: 40px; height: 40px; background: #e65100; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div>
                                        <h4 style="margin: 0; color: #e65100; font-size: 1rem;">Offline Test Instructions</h4>
                                        <p style="margin: 5px 0 0 0; color: #7f8c8d; font-size: 0.85rem;">Please visit the venue with necessary documents</p>
                                    </div>
                                </div>
                                
                                <div style="margin-top: 15px; display: flex; flex-wrap: wrap; gap: 15px;">
                                    <div style="background: white; padding: 10px 15px; border-radius: 8px; display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-id-card" style="color: #e65100;"></i>
                                        <span>Hall Ticket</span>
                                    </div>
                                    <div style="background: white; padding: 10px 15px; border-radius: 8px; display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-pencil" style="color: #e65100;"></i>
                                        <span>Stationery</span>
                                    </div>
                                    <div style="background: white; padding: 10px 15px; border-radius: 8px; display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-id-badge" style="color: #e65100;"></i>
                                        <span>Photo ID Proof</span>
                                    </div>
                                </div>
                                
                                <div style="margin-top: 15px;">
                                    <a href="#" class="pay-button" style="background: #e65100; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;" onclick="downloadHallTicket('{{ $testName }}', '{{ \Carbon\Carbon::parse($displayTestDate)->format('d M Y') }}', '{{ $formattedTestTime }}', '{{ $slotVenue }}')">
                                        <i class="fas fa-download"></i>
                                        Download Hall Ticket
                                    </a>
                                </div>
                            </div>
                            @endif
                            
                            @if($testPaymentStatus != 'paid')
                                <div style="margin-top: 20px;">
                                    <button class="pay-button d-none" onclick="openPaymentModal('Entrance Test Fee', {{ $entranceTestFeeAmount }})">
                                        <i class="fas fa-credit-card"></i>
                                        Pay Entrance Test Fee
                                    </button>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Tab Pane 3: Counselling -->
                    <div class="tab-pane" id="pane-3">
                        @if(!$counsellingEnabled)
                            <div class="lock-overlay">
                                <i class="fas fa-ban" style="font-size: 3rem; margin-bottom: 15px; color: #bdc3c7;"></i>
                                <h4 style="color: #2c3e50;">Counselling Not Required</h4>
                                <p>Counselling session is not required for your admission program.</p>
                            </div>
                        @elseif(!$step3Enabled)
                            <div class="lock-overlay">
                                <i class="fas fa-lock" style="font-size: 3rem; margin-bottom: 15px; color: #bdc3c7;"></i>
                                <h4 style="color: #2c3e50;">Step Locked</h4>
                                <p>This step will be enabled after you complete the Entrance Test.</p>
                                <p style="font-size: 0.9rem; margin-top: 10px;">Current Status: {{ $entranceTestCompleted ? 'Entrance Test Completed' : 'Entrance Test Pending' }}</p>
                            </div>
                        @else
                            <h3 style="color: #2c3e50; margin-bottom: 20px;">
                                <i class="fas fa-comments" style="color: #3498db;"></i> 
                                Counselling Session Details
                                @if($counsellingCompleted)
                                    <span style="font-size: 0.9rem; background: #d5f4e6; color: #27ae60; padding: 5px 12px; border-radius: 20px; margin-left: 15px;">
                                        <i class="fas fa-check-circle"></i> Completed
                                    </span>
                                @elseif($counsellingStatus == 'in_progress')
                                    <span style="font-size: 0.9rem; background: #e1f0fa; color: #3498db; padding: 5px 12px; border-radius: 20px; margin-left: 15px;">
                                        <i class="fas fa-spinner fa-spin"></i> In Progress
                                    </span>
                                @endif
                            </h3>
                            
                            @if($displayCounsellingDate)
                                <div class="step-info">
                                    <div class="info-item">
                                        <i class="fas fa-user-tie"></i>
                                        <strong>Counselor:</strong> {{ $counsellorName }}
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        <strong>Date:</strong> {{ \Carbon\Carbon::parse($displayCounsellingDate)->format('d M Y') }}
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-clock"></i>
                                        <strong>Time:</strong> {{ $displayCounsellingTime }}
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-video"></i>
                                        <strong>Mode:</strong> {{ ucfirst($counsellingMode) }}
                                    </div>
                                    @if($counsellingMode == 'online')
                                    <div class="info-item">
                                        <i class="fas fa-link"></i>
                                        <strong>Meeting Link:</strong> 
                                        <a href="{{ $counsellingLink }}" target="_blank" style="color: #3498db;">Join Session</a>
                                    </div>
                                    @endif
                                </div>
                                
                                @if(!$counsellingCompleted && $counsellingStatus != 'in_progress')
                                    <div style="margin-top: 20px; display: flex; gap: 10px;">
                                        <button class="pay-button" onclick="scheduleCounselling()">
                                            <i class="fas fa-calendar-plus"></i>
                                            Schedule Session
                                        </button>
                                        <button class="pay-button" style="background: linear-gradient(135deg, #27ae60, #229954);" onclick="window.location.href='mailto:{{ $counsellorEmail }}'">
                                            <i class="fas fa-envelope"></i>
                                            Contact Counselor
                                        </button>
                                    </div>
                                @elseif($counsellingStatus == 'in_progress')
                                    <div style="margin-top: 20px;">
                                        <button class="pay-button" onclick="window.open('{{ $counsellingLink }}', '_blank')">
                                            <i class="fas fa-video"></i>
                                            Join Counselling Session
                                        </button>
                                    </div>
                                @endif
                            @else
                                <p>Counselling session details will be available soon.</p>
                            @endif
                        @endif
                    </div>

                    <!-- Tab Pane 4: Onboarding -->
                    <div class="tab-pane" id="pane-4">
                        @if(!$onboardingEnabled)
                            <div class="lock-overlay">
                                <i class="fas fa-ban" style="font-size: 3rem; margin-bottom: 15px; color: #bdc3c7;"></i>
                                <h4 style="color: #2c3e50;">Onboarding Not Required</h4>
                                <p>Onboarding process is not required for your admission program.</p>
                            </div>
                        @elseif(!$step4Enabled)
                            <div class="lock-overlay">
                                <i class="fas fa-lock" style="font-size: 3rem; margin-bottom: 15px; color: #bdc3c7;"></i>
                                <h4 style="color: #2c3e50;">Step Locked</h4>
                                <p>This step will be enabled after you complete the Counselling Session.</p>
                                <p style="font-size: 0.9rem; margin-top: 10px;">Current Status: {{ $counsellingCompleted ? 'Counselling Completed' : 'Counselling Pending' }}</p>
                            </div>
                        @else
                            <h3 style="color: #2c3e50; margin-bottom: 20px;">
                                <i class="fas fa-user-graduate" style="color: #3498db;"></i> 
                                Final Onboarding
                                @if($onboardingCompleted)
                                    <span style="font-size: 0.9rem; background: #d5f4e6; color: #27ae60; padding: 5px 12px; border-radius: 20px; margin-left: 15px;">
                                        <i class="fas fa-check-circle"></i> Completed
                                    </span>
                                @endif
                            </h3>
                            
                            <div class="step-info">
                                <div class="info-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <strong>Onboarding Date:</strong> 
                                    {{ $displayOnboardingDate ? \Carbon\Carbon::parse($displayOnboardingDate)->format('d M Y') : 'To be announced' }}
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-calendar-check"></i>
                                    <strong>Classes Start:</strong> 
                                    {{ $classesStartDate ? \Carbon\Carbon::parse($classesStartDate)->format('d M Y') : 'To be announced' }}
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-chart-line"></i>
                                    <strong>Onboarding Status:</strong> 
                                    <span class="payment-status payment-{{ $onboardingCompleted ? 'paid' : 'pending' }}">
                                        <i class="fas fa-{{ $onboardingCompleted ? 'check-circle' : 'clock' }}"></i>
                                        {{ $onboardingCompleted ? 'Completed' : 'Pending' }}
                                    </span>
                                </div>
                            </div>
                            
                            @if(!$onboardingCompleted)
                                <div style="margin-top: 20px;">
                                    <div style="background: #f0f9ff; border-left: 4px solid #3498db; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                                        <strong style="color: #2c3e50;">Required Documents:</strong>
                                        <ul style="margin-top: 10px; list-style: none; padding-left: 0;">
                                            <li style="margin-bottom: 8px;"><i class="fas fa-check-circle" style="color: #27ae60;"></i> 10th Marksheet</li>
                                            <li style="margin-bottom: 8px;"><i class="fas fa-check-circle" style="color: #27ae60;"></i> 12th Marksheet</li>
                                            <li style="margin-bottom: 8px;"><i class="fas fa-check-circle" style="color: #27ae60;"></i> Transfer Certificate</li>
                                            <li style="margin-bottom: 8px;"><i class="fas fa-check-circle" style="color: #27ae60;"></i> Migration Certificate</li>
                                            <li style="margin-bottom: 8px;"><i class="fas fa-check-circle" style="color: #27ae60;"></i> Passport Size Photographs (4)</li>
                                        </ul>
                                    </div>
                                    <button class="pay-button" onclick="confirmOnboarding('{{ $displayOnboardingDate ? \Carbon\Carbon::parse($displayOnboardingDate)->format('d M Y') : 'TBA' }}')">
                                        <i class="fas fa-check-double"></i>
                                        Confirm Attendance
                                    </button>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Fee Structure for Class -->
            <div class="fee-structure-card" style="display:none;">
                <div class="fee-header">
                    <div class="fee-title">
                        <i class="fas fa-rupee-sign"></i>
                        Fee Structure - {{ $courseName }}
                    </div>
                    <span style="background: rgba(255,255,255,0.2); padding: 8px 16px; border-radius: 20px; font-size: 0.9rem;">
                        <i class="fas fa-calendar"></i> Academic Year {{ $academicYear }}
                    </span>
                </div>
                
                <div class="fee-grid">
                    <div class="fee-item">
                        <div class="fee-label">Tuition Fee (Per Semester)</div>
                        <div class="fee-amount">₹{{ number_format($semesterFees, 2) }}</div>
                    </div>
                    <div class="fee-item">
                        <div class="fee-label">Laboratory Fee</div>
                        <div class="fee-amount">₹{{ number_format($labFees, 2) }}</div>
                    </div>
                    <div class="fee-item">
                        <div class="fee-label">Library Fee</div>
                        <div class="fee-amount">₹{{ number_format($libraryFees, 2) }}</div>
                    </div>
                    <div class="fee-item">
                        <div class="fee-label">Sports & Cultural</div>
                        <div class="fee-amount">₹{{ number_format($sportsFees, 2) }}</div>
                    </div>
                    <div class="fee-item">
                        <div class="fee-label">Examination Fee</div>
                        <div class="fee-amount">₹{{ number_format($examFees, 2) }}</div>
                    </div>
                    <div class="fee-item">
                        <div class="fee-label">Other Charges</div>
                        <div class="fee-amount">₹{{ number_format($otherCharges, 2) }}</div>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                    <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 10px;">
                        <div style="font-size: 0.9rem; opacity: 0.9;">Optional Fees (Hostel)</div>
                        <div style="font-size: 1.3rem; font-weight: 700;">₹{{ number_format($hostelFees, 2) }}</div>
                        <div style="font-size: 0.8rem; margin-top: 5px;">Per Semester</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 10px;">
                        <div style="font-size: 0.9rem; opacity: 0.9;">Optional Fees (Transport)</div>
                        <div style="font-size: 1.3rem; font-weight: 700;">₹{{ number_format($transportFees, 2) }}</div>
                        <div style="font-size: 0.8rem; margin-top: 5px;">Per Semester</div>
                    </div>
                </div>
                
                <div class="fee-total">
                    <div>
                        <div style="font-size: 1rem; opacity: 0.9;">Total (Per Semester)</div>
                        <div class="total-amount">₹{{ number_format($totalFees, 2) }}</div>
                        <div style="font-size: 0.85rem; margin-top: 5px;">*Hostel & Transport optional</div>
                    </div>
                </div>
                
                <div style="margin-top: 25px; display: flex; gap: 15px; justify-content: flex-end;">
                    <button class="pay-button" onclick="downloadFeeStructure('{{ $courseName }}', '{{ $academicYear }}')" style="background: rgba(255,255,255,0.2);">
                        <i class="fas fa-download"></i>
                        Download Fee Structure
                    </button>
                    <button class="pay-button d-none" onclick="openPaymentModal('Semester Tuition Fee', {{ $totalFees }})">
                        <i class="fas fa-credit-card"></i>
                        Pay Semester Fees
                    </button>
                </div>
            </div>

            <!-- Counselor Information -->
            @if($counsellingEnabled && $counsellorName != 'Not Assigned' && $counsellorName != 'Admission Counselor')
            <div class="journey-section">
                <h3 class="section-title">
                    <i class="fas fa-user-tie"></i> Your Assigned Counselor
                </h3>
                
                <div class="counselor-card">
                    <div class="counselor-avatar">
                        @php
                            $nameParts = explode(' ', $counsellorName);
                            $initials = '';
                            if (count($nameParts) >= 2) {
                                $initials = substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1);
                            } else {
                                $initials = substr($counsellorName, 0, 2);
                            }
                        @endphp
                        {{ strtoupper($initials) }}
                    </div>
                    <div class="counselor-info">
                        <h4>{{ $counsellorName }}</h4>
                        <p><i class="fas fa-briefcase"></i> {{ $counsellorDesignation }}</p>
                        @if($counsellorEmail && $counsellorEmail != 'Not Provided')
                        <p><i class="fas fa-envelope"></i> {{ $counsellorEmail }}</p>
                        @endif
                        @if($counsellorPhone && $counsellorPhone != 'Not Provided')
                        <p><i class="fas fa-phone-alt"></i> {{ $counsellorPhone }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Next Steps -->
            <div class="next-steps">
                <h4><i class="fas fa-forward"></i> Your Action Items</h4>
                <ul class="next-steps-list" id="nextStepsList">
                    <!-- Will be populated by JavaScript -->
                </ul>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closePaymentModal()">&times;</span>
            <h2 style="color: #2c3e50; margin-bottom: 20px;">
                <i class="fas fa-credit-card" style="color: #3498db;"></i>
                Make Payment
            </h2>
            <div id="paymentDetails" style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                <div style="font-size: 1.1rem; color: #2c3e50;">Payment for: <span id="paymentTitle"></span></div>
                <div style="font-size: 1.8rem; font-weight: 700; color: #2c3e50; margin-top: 10px;">₹<span id="paymentAmount"></span></div>
            </div>
            
            <h3 style="color: #2c3e50; margin-bottom: 15px; font-size: 1.1rem;">Select Payment Method</h3>
            <div class="payment-methods">
                <div class="payment-method" onclick="selectPaymentMethod(this, 'card')">
                    <i class="fas fa-credit-card"></i>
                    Credit/Debit Card
                </div>
                <div class="payment-method" onclick="selectPaymentMethod(this, 'netbanking')">
                    <i class="fas fa-university"></i>
                    Net Banking
                </div>
                <div class="payment-method" onclick="selectPaymentMethod(this, 'upi')">
                    <i class="fas fa-mobile-alt"></i>
                    UPI (Google Pay/PhonePe/Paytm)
                </div>
                <div class="payment-method" onclick="selectPaymentMethod(this, 'wallet')">
                    <i class="fas fa-wallet"></i>
                    Digital Wallet
                </div>
            </div>
            
            <button class="process-payment" onclick="processPayment()">
                <i class="fas fa-lock"></i>
                Pay Securely
            </button>
            <p style="text-align: center; margin-top: 15px; color: #7f8c8d; font-size: 0.85rem;">
                <i class="fas fa-shield-alt"></i>
                Your payment is secured with 256-bit SSL encryption
            </p>
        </div>
    </div>

    <script>
        let selectedPaymentMethod = null;
        let currentPaymentTitle = '';
        let currentPaymentAmount = 0;

        document.addEventListener('DOMContentLoaded', function() {
            // Calculate and update progress
            updateProgress();
            
            // Show next steps
            showNextSteps();
            
            // Set initial active tab
            setActiveTab(1);
        });
        
        function switchTab(tabNumber, isEnabled) {
            if (!isEnabled) {
                // Show alert if trying to access disabled tab
                const stepNames = ['Admission Form', 'Entrance Test', 'Counselling', 'Onboarding'];
                alert(`The ${stepNames[tabNumber-1]} step is not yet available. Please complete the previous step first.`);
                return;
            }
            
            setActiveTab(tabNumber);
        }
        
        function setActiveTab(tabNumber) {
            // Remove active class from all tabs and panes
            document.querySelectorAll('.tab-item').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('active');
            });
            
            // Add active class to selected tab and pane
            document.getElementById(`tab-${tabNumber}`).classList.add('active');
            document.getElementById(`pane-${tabNumber}`).classList.add('active');
        }
        
        function updateProgress() {
            // Count enabled steps
            let totalSteps = 1; // Admission form
            let completedSteps = {{ $admissionCompleted ? 1 : 0 }};
            
            // Check Entrance Test
            @if($entranceTestsEnabled)
                totalSteps++;
                @if($entranceTestCompleted)
                    completedSteps++;
                @endif
            @endif
            
            // Check Counselling
            @if($counsellingEnabled)
                totalSteps++;
                @if($counsellingCompleted)
                    completedSteps++;
                @endif
            @endif
            
            // Check Onboarding
            @if($onboardingEnabled)
                totalSteps++;
                @if($onboardingCompleted)
                    completedSteps++;
                @endif
            @endif
            
            // Calculate percentage
            const percentage = totalSteps > 0 ? Math.round((completedSteps / totalSteps) * 100) : 0;
            
            // Update UI
            document.getElementById('progressPercentage').textContent = percentage + '%';
            document.getElementById('progressFill').style.width = percentage + '%';
        }
        
        function showNextSteps() {
            const nextStepsList = document.getElementById('nextStepsList');
            nextStepsList.innerHTML = '';
            
            // Step 1: Admission Form Fee (if pending)
            @if($registrationPaymentStatus != 'paid')
                addNextStep('Pay admission application fee of ₹{{ number_format($admissionFeeAmount, 0) }}');
            @endif
            
            // Step 2: Entrance Test
            @if($entranceTestsEnabled && !$entranceTestCompleted)
                @if($testPaymentStatus != 'paid')
                    addNextStep('Pay entrance test fee of ₹{{ number_format($entranceTestFeeAmount, 0) }}');
                @else
                    addNextStep('Take entrance test on {{ \Carbon\Carbon::parse($displayTestDate)->format("d M Y") }}');
                @endif
            @endif
            
            // Step 3: Counselling
            @if($counsellingEnabled && !$counsellingCompleted)
                @if($counsellingStatus == 'pending')
                    addNextStep('Schedule counselling session with {{ $counsellorName }}');
                @elseif($counsellingStatus == 'in_progress')
                    addNextStep('Join counselling session on {{ \Carbon\Carbon::parse($displayCounsellingDate)->format("d M Y") }}');
                @endif
            @endif
            
            // Step 4: Onboarding
            @if($onboardingEnabled && !$onboardingCompleted)
                addNextStep('Complete onboarding process on {{ $displayOnboardingDate ? \Carbon\Carbon::parse($displayOnboardingDate)->format("d M Y") : "TBA" }}');
            @endif
            
            // Semester Fee Payment
            addNextStep('Pay semester fee of ₹{{ number_format($totalFees, 0) }}');
            
            // If no next steps
            if (nextStepsList.children.length === 0) {
                addNextStep('🎉 All steps completed! Welcome to the institute!');
                addNextStep('📚 Check your email for orientation details');
                addNextStep('🆔 Your student ID will be generated soon');
            }
        }
        
        function addNextStep(text) {
            const list = document.getElementById('nextStepsList');
            const li = document.createElement('li');
            li.innerHTML = `<i class="fas fa-arrow-right" style="color: #3498db;"></i> ${text}`;
            list.appendChild(li);
        }
        
        function openPaymentModal(title, amount) {
            currentPaymentTitle = title;
            currentPaymentAmount = amount;
            
            document.getElementById('paymentTitle').textContent = title;
            document.getElementById('paymentAmount').textContent = amount.toLocaleString('en-IN');
            
            const modal = document.getElementById('paymentModal');
            modal.style.display = 'flex';
            
            // Reset selected payment method
            selectedPaymentMethod = null;
            document.querySelectorAll('.payment-method').forEach(el => {
                el.classList.remove('selected');
            });
        }
        
        function closePaymentModal() {
            const modal = document.getElementById('paymentModal');
            modal.style.display = 'none';
        }
        
        function selectPaymentMethod(element, method) {
            // Remove selected class from all payment methods
            document.querySelectorAll('.payment-method').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Add selected class to clicked method
            element.classList.add('selected');
            selectedPaymentMethod = method;
        }
        
        function processPayment() {
            if (!selectedPaymentMethod) {
                alert('Please select a payment method');
                return;
            }
            
            // Show loading state
            const payButton = document.querySelector('.process-payment');
            const originalText = payButton.innerHTML;
            payButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            payButton.disabled = true;
            
            // Simulate payment processing
            setTimeout(() => {
                alert(`✅ Payment of ₹${currentPaymentAmount.toLocaleString('en-IN')} for ${currentPaymentTitle} completed successfully!\n\nPayment Method: ${selectedPaymentMethod}\nTransaction ID: TXN${Math.random().toString(36).substr(2, 9).toUpperCase()}\n\nThank you for your payment!`);
                
                closePaymentModal();
                
                // Reset button
                payButton.innerHTML = originalText;
                payButton.disabled = false;
                
                // Reload page to update status
                location.reload();
            }, 2000);
        }
        
        function viewTestInstructions(instructions, testTime, duration) {
            const defaultInstructions = instructions || 'Read all instructions carefully before starting the test';
            const testDuration = duration || '120';
            
            const instructionText = `📋 ENTRANCE TEST INSTRUCTIONS - ONLINE MODE\n\n` +
                `1. Technical Requirements:\n` +
                `   • Stable internet connection (minimum 2 Mbps)\n` +
                `   • Working webcam and microphone\n` +
                `   • Google Chrome or Mozilla Firefox browser\n` +
                `   • No other applications running in background\n\n` +
                `2. Test Details:\n` +
                `   • Test Time: ${testTime}\n` +
                `   • Duration: ${testDuration} minutes\n` +
                `   • Total Questions: 100\n` +
                `   • Maximum Marks: 400\n\n` +
                `3. Important Rules:\n` +
                `   • Login 15 minutes before test time\n` +
                `   • Proctoring enabled - camera must be ON\n` +
                `   • No tab switching allowed\n` +
                `   • No external help or materials\n\n` +
                `4. Marking Scheme:\n` +
                `   • +4 for correct answers\n` +
                `   • -1 for incorrect answers\n` +
                `   • No negative marking for unattempted\n\n` +
                `Additional Instructions:\n` +
                `   ${defaultInstructions}\n\n` +
                `Click OK to start the test when ready.`;
            
            if (confirm(instructionText)) {
                window.open('{{ $testOnlineLink }}', '_blank');
            }
        }
        
        function downloadHallTicket(testName, testDate, testTime, venue) {
            const venueText = venue || 'To be announced';
            alert(`📄 Hall Ticket is being generated...\n\n` +
                  `Test Details:\n` +
                  `• Test Name: ${testName}\n` +
                  `• Date: ${testDate}\n` +
                  `• Time: ${testTime}\n` +
                  `• Venue: ${venueText}\n\n` +
                  `Please check your email for the downloadable hall ticket.`);
            
            // Simulate download
            setTimeout(() => {
                alert('✅ Hall Ticket downloaded successfully!\n📧 Also sent to your registered email.');
            }, 1500);
        }
        
        function scheduleCounselling() {
            alert('📅 Counselling session scheduling portal will open shortly.\n\nPlease check your email for available time slots and choose a convenient time.\n\nYou will receive a confirmation email once scheduled.');
            window.location.href = '{{ $counsellingLink }}';
        }
        
        function confirmOnboarding(date) {
            const onboardingDate = date || 'TBA';
            if (confirm(`✅ Confirm your attendance for onboarding on ${onboardingDate}?\n\nPlease ensure you have all required documents ready.`)) {
                alert('🎉 Your attendance has been confirmed!\n\nYou will receive a confirmation email shortly with further instructions and the onboarding schedule.\n\nThank you for choosing our institute!');
                
                // In real implementation, this would send an AJAX request
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
        }
        
        function downloadFeeStructure(courseName, academicYear) {
            alert(`📊 Fee structure is being generated...\n\n` +
                  `Course: ${courseName}\n` +
                  `Academic Year: ${academicYear}\n\n` +
                  `The PDF will be downloaded automatically.`);
            
            // Simulate download
            setTimeout(() => {
                alert('✅ Fee structure downloaded successfully!\n📧 Also sent to your registered email.');
            }, 1500);
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('paymentModal');
            if (event.target == modal) {
                closePaymentModal();
            }
        }
    </script>
</body>
</html>

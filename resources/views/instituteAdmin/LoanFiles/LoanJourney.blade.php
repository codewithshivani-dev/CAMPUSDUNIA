@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Journey · Vertical Status</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .journey-card {
            max-width: 640px;
            margin:auto;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(4px);
            border-radius: 32px;
            box-shadow: 0 25px 45px -12px rgba(0, 20, 40, 0.25), 0 8px 18px rgba(0, 0, 0, 0.05);
            padding: 2rem 1.8rem 2.2rem;
            border: 1px solid rgba(255, 255, 255, 0.6);
            transition: all 0.2s;
            position: relative;
        }

        h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 1.7rem;
            letter-spacing: -0.01em;
            color: #0b1e32;
            margin-bottom: 0.2rem;
        }

        h2 i {
            color: #2a6f97;
            font-size: 1.8rem;
        }

        .subhead {
            font-size: 0.9rem;
            color: #54738f;
            margin-bottom: 2rem;
            border-left: 3px solid #2a6f97;
            padding-left: 12px;
            font-weight: 400;
        }

        .loan-timeline {
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .loan-timeline::before {
            content: '';
            position: absolute;
            left: 22px;
            top: 12px;
            bottom: 12px;
            width: 2.5px;
            background: linear-gradient(to bottom, #cbd6e4 0%, #a6bbd0 100%);
            border-radius: 2px;
            z-index: 0;
        }

        .journey-step {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            position: relative;
            z-index: 2;
            padding: 10px 0 10px 0;
        }

        .step-icon {
            flex-shrink: 0;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            box-shadow: 0 6px 12px rgba(0, 20, 30, 0.08), 0 2px 4px rgba(0,0,0,0.02);
            border: 2px solid white;
            transition: all 0.15s;
            z-index: 5;
        }

        .status-completed .step-icon {
            background: #1e7e34;
            color: white;
            border-color: #d9f0de;
            box-shadow: 0 8px 14px rgba(30, 126, 52, 0.2);
        }

        .status-pending .step-icon {
            background: #f1b24a;
            color: #1e2f3e;
            border-color: #ffeac2;
        }

        .status-not-received .step-icon {
            background: #94a3b8;
            color: white;
            border-color: #e2e8f0;
            box-shadow: 0 6px 12px rgba(148, 163, 184, 0.18);
        }

        .status-rejected .step-icon {
            background: #cf3e3e;
            color: white;
            border-color: #ffcece;
            box-shadow: 0 6px 12px rgba(207, 62, 62, 0.18);
        }

        .step-content {
            flex: 1;
            padding-top: 4px;
        }

        .step-header {
            display: flex;
            align-items: baseline;
            flex-wrap: wrap;
            gap: 8px 12px;
            margin-bottom: 4px;
        }

        .step-title {
            font-weight: 650;
            font-size: 1.15rem;
            letter-spacing: -0.2px;
            color: #1f3a4e;
        }

        .status-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 40px;
            background: #eef3f9;
            color: #1f3a4e;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: 1px solid rgba(0,0,0,0.04);
        }

        .status-completed .status-badge {
            background: #e1f3e5;
            color: #0b5e2e;
            border-color: #a3d8b1;
        }

        .status-pending .status-badge {
            background: #fff1d6;
            color: #8a6100;
            border-color: #fddfa5;
        }

        .status-not-received .status-badge {
            background: #f1f5f9;
            color: #475569;
            border-color: #cbd5e1;
        }

        .status-rejected .status-badge {
            background: #ffe6e6;
            color: #b11f1f;
            border-color: #fbbbbb;
        }

        .step-meta {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 4px;
            font-size: 0.8rem;
            color: #5c738b;
            flex-wrap: wrap;
        }

        .step-meta i {
            font-size: 0.7rem;
            color: #7b95af;
        }

        .journey-step:not(:last-child) {
            border-bottom: 1px dashed rgba(139, 167, 194, 0.2);
            margin-bottom: 2px;
        }

        .legend-note {
            margin-top: 28px;
            background: #eef3f9c9;
            border-radius: 24px;
            padding: 14px 18px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,0.7);
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .dot {
            width: 14px;
            height: 14px;
            border-radius: 14px;
        }
        .dot.green { background: #1e7e34; }
        .dot.amber { background: #f1b24a; }
        .dot.gray { background: #94a3b8; }
        .dot.red { background: #cf3e3e; }

        .footer-note {
            margin-top: 16px;
            color: #3b5e7e;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .webhook-ref {
            font-family: 'SF Mono', 'Fira Code', monospace;
            background: #dee5ed;
            padding: 4px 10px;
            border-radius: 40px;
            font-size: 0.7rem;
            color: #17344f;
        }

        .step-icon i {
            display: none;
        }
        .status-completed .step-icon .fa-circle-check { display: inline-block; }
        .status-pending .step-icon .fa-circle { display: inline-block; }
        .status-not-received .step-icon .fa-clock { display: inline-block; }
        .status-rejected .step-icon .fa-circle-xmark { display: inline-block; }

        .progress-container {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: #e2e8f0;
            border-radius: 0 0 32px 32px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #1e7e34, #2a9d8f);
            transition: width 0.5s ease;
        }

        /* Action Button Styles */
        .action-button-container {
            margin-top: 24px;
            text-align: center;
        }

        .next-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #2a6f97 0%, #1e4d6f 100%);
            color: white;
            padding: 12px 28px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(42, 111, 151, 0.3);
            border: none;
            cursor: pointer;
        }

        .next-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(42, 111, 151, 0.4);
            background: linear-gradient(135deg, #1e5a7a 0%, #163d57 100%);
            color: white;
        }

        .next-action-btn i {
            font-size: 1.1rem;
        }

        .rejected-action-btn {
            background: linear-gradient(135deg, #cf3e3e 0%, #a02c2c 100%);
            box-shadow: 0 4px 12px rgba(207, 62, 62, 0.3);
        }

        .rejected-action-btn:hover {
            background: linear-gradient(135deg, #b33535 0%, #8a2525 100%);
            box-shadow: 0 6px 20px rgba(207, 62, 62, 0.4);
        }

        .completed-action-btn {
            background: linear-gradient(135deg, #1e7e34 0%, #155d27 100%);
            box-shadow: 0 4px 12px rgba(30, 126, 52, 0.3);
        }

        .completed-action-btn:hover {
            background: linear-gradient(135deg, #186a2d 0%, #0f4a1f 100%);
            box-shadow: 0 6px 20px rgba(30, 126, 52, 0.4);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-15px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .journey-step {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .status-pending .step-icon {
            animation: pulse 2s ease-in-out infinite;
        }

        .journey-step:hover {
            background: rgba(0, 0, 0, 0.02);
            border-radius: 16px;
            transition: all 0.2s ease;
        }

        /* Fade in animation for button */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .action-button-container {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
</head>

<div class="journey-card">
    <h2>
        <i class="fas fa-route"></i> 
        Loan Journey
    </h2>
    <div class="subhead">
        vertical timeline · track loan application progress
    </div>

    <div class="loan-timeline" id="journeyTimeline"></div>

    <!-- Action Button Container -->
    <div id="actionButtonContainer" style="display: none;"></div>

    <div class="legend-note">
        <div class="legend-item"><span class="dot green"></span> Completed</div>
        <div class="legend-item"><span class="dot amber"></span> Pending</div>
        <div class="legend-item"><span class="dot gray"></span> Not Received</div>
        <div class="legend-item"><span class="dot red"></span> Rejected</div>
        <span class="webhook-ref" id="stepCount"><i class="far fa-bell"></i> loading...</span>
    </div>
    <div class="footer-note">
        <i class="fas fa-code-branch"></i>
        <span>Real-time loan journey tracking</span>
    </div>
    <div class="progress-container">
        <div class="progress-bar" id="progressBar" style="width: 0%"></div>
    </div>
</div>

<script>
    (function() {
        // Define the journey steps in order
        const JOURNEY_STEPS = [
            { key: "LOAN_REQUESTED", display: "Loan Requested", description: "Application submitted to lender" },
            { key: "LOAN_PROGRESS", display: "Loan Progress", description: "Underwriting & credit review in progress" },
            { key: "LOAN_APPROVED", display: "Loan Approved", description: "Loan application approved" },
            { key: "LOAN_REJECTED", display: "Loan Rejected", description: "Application declined by lender" },
            { key: "KYC_INITIATED", display: "KYC Initiated", description: "Identity verification process started" },
            { key: "KYC_COMPLETED", display: "KYC Completed", description: "KYC verification successful" },
            { key: "AGREEMENT_INITIATED", display: "Agreement Initiated", description: "Loan agreement document generated" },
            { key: "AGREEMENT_SIGNED", display: "Agreement Signed", description: "E-signature completed" },
            { key: "NACH_INITIATED", display: "NACH Initiated", description: "Auto-debit mandate setup initiated" },
            { key: "NACH_COMPLETED", display: "NACH Completed", description: "NACH mandate registered successfully" },
            { key: "PAYMENT_INITIATED", display: "Payment Initiated", description: "Processing fee payment initiated" },
            { key: "PAYMENT_COMPLETED", display: "Payment Completed", description: "Payment received successfully" },
            { key: "READY_FOR_DISBURSEMENT", display: "Ready For Disbursement", description: "Final verification completed" },
            { key: "DISBURSEMENT_COMPLETED", display: "Disbursement Completed", description: "Loan amount disbursed" }
        ];

        // Get webhook data from Laravel
        const webhookData = @json($loan_journey ?? []);
        const loanRequestId = @json($loan_request_id ?? null);
        
        // Process webhook responses
        let breStatus = null; // 'approved', 'rejected', or null
        let hasLoanProgress = false;
        let receivedEvents = new Map();
        let lastCompletedStep = null;
        let lastCompletedStepKey = null;
        
        webhookData.forEach(item => {
            const eventType = item.webhook_flyhi_response_type;
            const status = item.webhook_flyhi_response_status;
            const timestamp = item.created_at;
            
            // Track BRE status
            if (eventType === 'BRE_APPROVED') {
                breStatus = 'approved';
                receivedEvents.set('BRE_APPROVED', { status, timestamp, display: 'BRE Approved' });
                lastCompletedStep = { key: eventType, display: 'BRE Approved', timestamp };
                lastCompletedStepKey = eventType;
            } else if (eventType === 'BRE_REJECTED') {
                breStatus = 'rejected';
                receivedEvents.set('BRE_REJECTED', { status, timestamp, display: 'BRE Rejected' });
                lastCompletedStep = { key: eventType, display: 'BRE Rejected', timestamp };
                lastCompletedStepKey = eventType;
            } else if (eventType === 'BRE_IN_PROGRESS') {
                hasLoanProgress = true;
                receivedEvents.set('BRE_IN_PROGRESS', { status, timestamp, display: 'BRE In Progress' });
            } else {
                receivedEvents.set(eventType, { status, timestamp, display: eventType.replace(/_/g, ' ') });
                if (status == '1') {
                    lastCompletedStep = { key: eventType, display: eventType.replace(/_/g, ' '), timestamp };
                    lastCompletedStepKey = eventType;
                }
            }
        });
        
        // Function to determine step status based on business logic
        function getStepStatus(stepKey) {
            // LOAN REQUESTED - Always completed (default)
            if (stepKey === 'LOAN_REQUESTED') {
                return 'completed';
            }
            
            // LOAN PROGRESS - Pending until BRE_APPROVED or BRE_REJECTED received
            if (stepKey === 'LOAN_PROGRESS') {
                if (breStatus === 'approved' || breStatus === 'rejected') {
                    return 'completed';
                }
                if (hasLoanProgress) {
                    return 'pending';
                }
                return 'not-received';
            }
            
            // LOAN APPROVED & LOAN REJECTED - Show only one based on BRE status
            if (stepKey === 'LOAN_APPROVED') {
                if (breStatus === 'approved') {
                    return 'completed';
                }
                if (breStatus === 'rejected') {
                    return null; // Don't show this step
                }
                return 'pending';
            }
            
            if (stepKey === 'LOAN_REJECTED') {
                if (breStatus === 'rejected') {
                    return 'completed';
                }
                return null; // Don't show this step if not rejected
            }
            
            // For all other steps, check if received in webhook
            if (receivedEvents.has(stepKey)) {
                const event = receivedEvents.get(stepKey);
                if (event.status == '1') {
                    return 'completed';
                } else if (event.status == '0') {
                    return 'rejected';
                }
                return 'completed';
            }
            
            return 'not-received';
        }
        
        // Get timestamp for a step
        function getStepTimestamp(stepKey) {
            if (stepKey === 'LOAN_REQUESTED') {
                const timestamps = webhookData.filter(w => w.created_at).map(w => w.created_at);
                if (timestamps.length > 0) {
                    return timestamps[0];
                }
                return null;
            }
            
            if (stepKey === 'LOAN_PROGRESS' && (breStatus === 'approved' || breStatus === 'rejected')) {
                const breEvent = receivedEvents.get(breStatus === 'approved' ? 'BRE_APPROVED' : 'BRE_REJECTED');
                return breEvent ? breEvent.timestamp : null;
            }
            
            if (stepKey === 'LOAN_APPROVED' && breStatus === 'approved') {
                const breApproved = receivedEvents.get('BRE_APPROVED');
                return breApproved ? breApproved.timestamp : null;
            }
            
            if (stepKey === 'LOAN_REJECTED' && breStatus === 'rejected') {
                const breRejected = receivedEvents.get('BRE_REJECTED');
                return breRejected ? breRejected.timestamp : null;
            }
            
            if (receivedEvents.has(stepKey)) {
                return receivedEvents.get(stepKey).timestamp;
            }
            
            return null;
        }
        
        // Get meta text based on status and step
        function getMetaText(stepKey, status) {
            if (status === 'completed') {
                if (stepKey === 'LOAN_REQUESTED') return '✓ Application submitted';
                if (stepKey === 'LOAN_PROGRESS') return '✓ BRE evaluation completed';
                if (stepKey === 'LOAN_APPROVED') return '✓ Loan approved by lender';
                if (stepKey === 'LOAN_REJECTED') return '✕ Loan rejected by lender';
                return '✓ Completed';
            }
            if (status === 'pending') {
                if (stepKey === 'LOAN_PROGRESS') return '⏳ BRE evaluation in progress';
                if (stepKey === 'LOAN_APPROVED') return '⏳ Awaiting BRE approval';
                return '⏳ In progress';
            }
            if (status === 'not-received') return '⭘ Awaiting webhook response';
            if (status === 'rejected') return '✕ Rejected';
            return '';
        }
        
        // Get badge text
        function getBadgeText(status) {
            if (status === 'completed') return 'COMPLETED';
            if (status === 'pending') return 'PENDING';
            if (status === 'not-received') return 'NOT RECEIVED';
            if (status === 'rejected') return 'REJECTED';
            return '';
        }
        
        // Get icon HTML
        function getIconHtml(status) {
            if (status === 'not-received') {
                return `<i class="fas fa-clock"></i>`;
            }
            return `
                <i class="fas fa-circle-check"></i>
                <i class="fas fa-circle"></i>
                <i class="fas fa-circle-xmark"></i>
            `;
        }
        
        // Get next action based on last completed step
        function getNextAction(lastStepKey, overallStatus) {
            const actions = {
                'LOAN_REQUESTED': {
                    text: 'Continue Application',
                    icon: 'fa-arrow-right',
                    url: `https://uat.customerjourney.flyhifinance.com/welcome`,
                    color: 'primary'
                },
                'BRE_APPROVED': {
                    text: 'Proceed to Agreement',
                    icon: 'fa-file-signature',
                    url: `https://uat.customerjourney.flyhifinance.com/welcome`,
                    color: 'primary'
                },
                'BRE_REJECTED': {
                    text: 'View Rejection Details',
                    icon: 'fa-info-circle',
                    url: `https://uat.customerjourney.flyhifinance.com/welcome`,
                    color: 'rejected'
                },
                'KYC_INITIATED': {
                    text: 'Upload KYC Documents',
                    icon: 'fa-upload',
                    url: `https://uat.customerjourney.flyhifinance.com/welcome`,
                    color: 'primary'
                },
                'KYC_COMPLETED': {
                    text: 'Setup NACH Mandate',
                    icon: 'fa-hand-holding-usd',
                    url: `https://uat.customerjourney.flyhifinance.com/welcome`,
                    color: 'primary'
                },
                'AGREEMENT_INITIATED': {
                    text: 'Sign Agreement',
                    icon: 'fa-pen-signature',
                    url: `https://uat.customerjourney.flyhifinance.com/welcome`,
                    color: 'primary'
                },
                'AGREEMENT_SIGNED': {
                    text: 'Complete KYC',
                    icon: 'fa-id-card',
                    url: `https://uat.customerjourney.flyhifinance.com/welcome`,
                    color: 'primary'
                },
                'NACH_INITIATED': {
                    text: 'Complete NACH Registration',
                    icon: 'fa-check-double',
                    url: `https://uat.customerjourney.flyhifinance.com/welcome`,
                    color: 'primary'
                },
                'NACH_COMPLETED': {
                    text: 'Make Payment',
                    icon: 'fa-credit-card',
                    url: `https://uat.customerjourney.flyhifinance.com/welcome`,
                    color: 'primary'
                },
                'PAYMENT_INITIATED': {
                    text: 'Complete Payment',
                    icon: 'fa-money-bill-wave',
                    url: `https://uat.customerjourney.flyhifinance.com/welcome`,
                    color: 'primary'
                },
                'PAYMENT_COMPLETED': {
                    text: 'Ready for Disbursement',
                    icon: 'fa-clock',
                    url: `https://uat.customerjourney.flyhifinance.com/welcome`,
                    color: 'primary'
                },
                'READY_FOR_DISBURSEMENT': {
                    text: 'Track Disbursement',
                    icon: 'fa-truck',
                    url: `https://uat.customerjourney.flyhifinance.com/welcome`,
                    color: 'primary'
                },
                'DISBURSEMENT_COMPLETED': {
                    text: 'View Loan Details',
                    icon: 'fa-file-invoice-dollar',
                    url: `https://uat.customerjourney.flyhifinance.com/welcome`,
                    color: 'completed'
                }
            };
            
            // If all steps completed (disbursement done)
            if (overallStatus === 'all_completed') {
                return {
                    text: 'Download Loan Certificate',
                    icon: 'fa-download',
                    url: `https://uat.customerjourney.flyhifinance.com/welcome`,
                    color: 'completed'
                };
            }
            
            // If rejected
            if (overallStatus === 'rejected') {
                return {
                    text: 'Contact Support',
                    icon: 'fa-headset',
                    url: `https://uat.customerjourney.flyhifinance.com/welcome`,
                    color: 'rejected'
                };
            }
            
            return actions[lastStepKey] || {
                text: 'View Application Status',
                icon: 'fa-chart-line',
                url: `/loan/status/${loanRequestId}`,
                color: 'primary'
            };
        }
        
        // Create action button HTML
        function createActionButton(action) {
            const buttonClass = action.color === 'rejected' ? 'rejected-action-btn' : 
                               action.color === 'completed' ? 'completed-action-btn' : 'next-action-btn';
            
            return `
                <div class="action-button-container">
                    <a href="${action.url}" class="${buttonClass}" target="_blank">
                        <i class="fas ${action.icon}"></i>
                        <span>${action.text}</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            `;
        }
        
        // Build the timeline
        const container = document.getElementById('journeyTimeline');
        const actionContainer = document.getElementById('actionButtonContainer');
        if (!container) return;
        
        let stepsHtml = '';
        let completedCount = 0;
        let pendingCount = 0;
        let notReceivedCount = 0;
        let rejectedCount = 0;
        let hasAnyRejected = false;
        
        const visibleSteps = [];
        
        JOURNEY_STEPS.forEach(step => {
            const status = getStepStatus(step.key);
            
            // Skip steps that should not be shown
            if (status === null) return;
            
            visibleSteps.push({ ...step, status });
            
            // Update counters
            if (status === 'completed') {
                completedCount++;
                lastCompletedStepKey = step.key;
            }
            else if (status === 'pending') pendingCount++;
            else if (status === 'not-received') notReceivedCount++;
            else if (status === 'rejected') {
                rejectedCount++;
                hasAnyRejected = true;
            }
            
            const statusClass = 
                status === 'completed' ? 'status-completed' :
                status === 'pending' ? 'status-pending' :
                status === 'not-received' ? 'status-not-received' : 'status-rejected';
            
            const badgeText = getBadgeText(status);
            const metaInfo = getMetaText(step.key, status);
            const timestamp = getStepTimestamp(step.key);
            
            const metaIcon = status === 'completed' ? 'fa-check-circle' : 
                             status === 'pending' ? 'fa-hourglass-half' : 
                             status === 'not-received' ? 'fa-clock' : 'fa-times-circle';
            
            // Format timestamp
            let formattedTimestamp = null;
            if (timestamp) {
                const date = new Date(timestamp);
                formattedTimestamp = date.toLocaleString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
            
            stepsHtml += `
                <div class="journey-step ${statusClass}" data-step-key="${step.key}" data-status="${status}">
                    <div class="step-icon">
                        ${getIconHtml(status)}
                    </div>
                    <div class="step-content">
                        <div class="step-header">
                            <span class="step-title">${step.display}</span>
                            <span class="status-badge">${badgeText}</span>
                        </div>
                        <div style="font-size:0.9rem; color:#2f4b6a; margin-bottom:2px;">${step.description}</div>
                        <div class="step-meta">
                            <i class="fas ${metaIcon}"></i>
                            <span>${metaInfo}</span>
                            ${formattedTimestamp ? `<span><i class="far fa-calendar-alt"></i> ${formattedTimestamp}</span>` : ''}
                            ${status === 'pending' && step.key === 'LOAN_PROGRESS' ? '<span style="background:#fff1d6; padding:2px 8px; border-radius:20px;"><i class="fas fa-chart-line"></i> BRE evaluation running</span>' : ''}
                            ${status === 'pending' && step.key === 'LOAN_APPROVED' ? '<span style="background:#fff1d6; padding:2px 8px; border-radius:20px;"><i class="fas fa-clock"></i> Waiting for BRE response</span>' : ''}
                        </div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = stepsHtml;
        
        // Determine overall status and show appropriate button
        let overallStatus = '';
        let action = null;
        
        if (hasAnyRejected) {
            overallStatus = 'rejected';
            action = getNextAction(null, 'rejected');
            actionContainer.style.display = 'block';
            actionContainer.innerHTML = createActionButton(action);
        } else if (completedCount === visibleSteps.length) {
            overallStatus = 'all_completed';
            action = getNextAction(null, 'all_completed');
            actionContainer.style.display = 'block';
            actionContainer.innerHTML = createActionButton(action);
        } else if (lastCompletedStepKey) {
            // Find the last completed step from visible steps
            let lastCompleted = null;
            for (let i = visibleSteps.length - 1; i >= 0; i--) {
                if (visibleSteps[i].status === 'completed') {
                    lastCompleted = visibleSteps[i].key;
                    break;
                }
            }
            
            if (lastCompleted) {
                action = getNextAction(lastCompleted, 'in_progress');
                actionContainer.style.display = 'block';
                actionContainer.innerHTML = createActionButton(action);
            }
        }
        
        // Update step count
        const stepCountSpan = document.getElementById('stepCount');
        if (stepCountSpan) {
            stepCountSpan.innerHTML = `<i class="far fa-bell"></i> ${completedCount} completed · ${pendingCount} pending · ${notReceivedCount} not received`;
        }
        
        // Update progress bar
        const progressBar = document.getElementById('progressBar');
        if (progressBar) {
            const totalSteps = visibleSteps.length;
            const progressPercentage = (completedCount / totalSteps) * 100;
            progressBar.style.width = `${progressPercentage}%`;
            
            if (rejectedCount > 0) {
                progressBar.style.background = "linear-gradient(90deg, #cf3e3e, #e76d6d)";
            }
        }
        
        // Scroll to the last completed step
        if (lastCompletedStepKey) {
            setTimeout(() => {
                const lastCompletedElement = document.querySelector(`.journey-step[data-step-key="${lastCompletedStepKey}"]`);
                if (lastCompletedElement) {
                    lastCompletedElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    // Add a highlight effect
                    lastCompletedElement.style.transition = 'all 0.3s ease';
                    lastCompletedElement.style.backgroundColor = 'rgba(30, 126, 52, 0.1)';
                    setTimeout(() => {
                        lastCompletedElement.style.backgroundColor = '';
                    }, 2000);
                }
            }, 100);
        }
    })();
</script>
@endsection
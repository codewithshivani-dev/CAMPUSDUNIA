@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EMI Dashboard | Ajivika Loan Payments | Bulk & Single Pay</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* MAIN BOX: central white card that holds everything - perfectly visible as a main container */
        .main-dashboard-box {
            max-width: auto;
            width: 100%;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 2rem;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.25), 0 2px 6px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .dashboard-content {
            padding: 1.5rem 1.8rem 1.8rem 1.8rem;
        }

        .emi-header {
            background: #ffffff;
            border-radius: 24px;
            padding: 0.2rem 0 1rem 0;
            margin-bottom: 1.2rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid #eef2f5;
        }

        .title-section h1 {
            font-size: 1.55rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1a4b6e, #0f2f48);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            letter-spacing: -0.3px;
        }

        .title-section p {
            color: #5f82a0;
            font-size: 0.7rem;
            margin-top: 0.25rem;
            font-weight: 500;
        }

        .loan-badge {
            background: #ecf6f0;
            padding: 0.45rem 1.2rem;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 700;
            color: #1c6b4a;
            border: 1px solid #cfe3d8;
            white-space: nowrap;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }

        .loan-badge i {
            margin-right: 6px;
            color: #2c7a4d;
        }

        /* Sequential Payment Warning */
        .sequential-warning {
            background: #fff9e6;
            border-left: 4px solid #f5a623;
            padding: 0.6rem 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            font-size: 0.7rem;
            color: #8a6e2b;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sequential-warning i {
            font-size: 1rem;
            color: #f5a623;
        }

        /* Bulk Action Bar */
        .bulk-action-bar {
            background: #fafefc;
            border-radius: 1rem;
            padding: 0.8rem 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e2edf0;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .bulk-controls {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .select-all-btn, .pay-bulk-btn {
            border: none;
            background: white;
            padding: 8px 16px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .select-all-btn {
            border: 1px solid #bdd8e4;
            color: #1c6b4a;
            background: #ffffff;
        }

        .select-all-btn:hover {
            background: #eef7f2;
            border-color: #8fbc9b;
        }

        .pay-bulk-btn {
            background: linear-gradient(95deg, #1b6b4a, #117f58);
            color: white;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .pay-bulk-btn:hover {
            background: linear-gradient(95deg, #0f5a40, #0c6b48);
            transform: scale(1.02);
        }

        .selected-info {
            font-size: 0.7rem;
            background: #eef2f5;
            padding: 5px 12px;
            border-radius: 40px;
            font-weight: 600;
            color: #1f5e73;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.8rem;
        }

        .card {
            background: #fefefe;
            border-radius: 20px;
            padding: 1rem 1rem;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.02);
            border: 1px solid #e9f0f3;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.06);
            border-color: #cde0e8;
        }

        .card .label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 800;
            color: #5f7f9c;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .card .value {
            font-size: 1.7rem;
            font-weight: 800;
            color: #173e54;
            line-height: 1.2;
        }

        .card .sub {
            font-size: 0.6rem;
            color: #6c8eae;
            margin-top: 0.35rem;
            font-weight: 500;
        }

        /* Charges Summary Card */
        .charges-summary {
            background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
            border: 1px solid #e2edf0;
            border-radius: 20px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .charges-title {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            color: #5f7f9c;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .charges-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 0.75rem;
        }

        .charge-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0.75rem;
            background: #fafefc;
            border-radius: 12px;
            border: 1px solid #eef3f5;
        }

        .charge-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: #5f7f9c;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .charge-amount {
            font-size: 0.8rem;
            font-weight: 800;
            color: #c26b1a;
        }

        .charge-sub {
            font-size: 0.6rem;
            color: #8a9eb0;
            margin-left: 4px;
            font-weight: normal;
        }

        .total-payable {
            background: linear-gradient(95deg, #1b6b4a, #117f58);
            border-radius: 12px;
            padding: 0.5rem 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .total-payable .label {
            font-size: 0.7rem;
            font-weight: 600;
        }

        .total-payable .amount {
            font-size: 1rem;
            font-weight: 800;
        }

        .emi-table-wrapper {
            background: white;
            border-radius: 24px;
            overflow-x: auto;
            margin-top: 0.25rem;
            border: 1px solid #edf3f8;
        }

        .emi-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.75rem;
            min-width: 900px;
        }

        .emi-table th {
            text-align: left;
            padding: 0.9rem 0.9rem;
            background-color: #fbfefd;
            font-weight: 800;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #1c5a7a;
            border-bottom: 1.5px solid #e1f0f0;
        }

        .emi-table td {
            padding: 0.85rem 0.9rem;
            border-bottom: 1px solid #ecf3f8;
            vertical-align: middle;
            color: #1f3c4e;
        }

        .emi-table tr:last-child td {
            border-bottom: none;
        }

        .emi-table tr:hover td {
            background-color: #f9fdfb;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 40px;
            font-size: 0.68rem;
            font-weight: 700;
            width: fit-content;
        }

        .status-unpaid { background: #fff2e6; color: #bf6f20; }
        .status-paid { background: #e2f5e9; color: #1a6e3f; }
        .status-overdue { background: #ffe6e5; color: #bc3f2e; }
        .status-blocked { background: #f0eef0; color: #8a7b9c; }
        .status-partial { background: #fff3e0; color: #d97a2b; }

        .pay-btn {
            background: linear-gradient(95deg, #1b6b4a, #117f58);
            border: none;
            color: white;
            padding: 6px 14px;
            border-radius: 32px;
            font-weight: 700;
            font-size: 0.7rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .pay-btn:hover { background: linear-gradient(95deg, #0f5a40, #0c6b48); transform: scale(1.02); }
        .pay-btn:active { transform: scale(0.96); }
        .paid-label { background: #e9f4ea; color: #2b6e3c; padding: 4px 12px; border-radius: 32px; font-size: 0.7rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
        .blocked-label { background: #f0eef0; color: #7c6b8e; padding: 4px 12px; border-radius: 32px; font-size: 0.7rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
        .partial-label { background: #fff3e0; color: #d97a2b; padding: 4px 12px; border-radius: 32px; font-size: 0.7rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }

        .emi-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #1b6b4a;
        }
        .emi-checkbox:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }

        .emi-id-monospace {
            font-family: 'SF Mono', 'Fira Code', monospace;
            font-size: 0.7rem;
            background: #f2f8fc;
            padding: 3px 8px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 600;
        }

        .amount-highlight { font-weight: 800; color: #1a6b4a; font-size: 0.85rem; }
        .charge-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.65rem;
            background: #fef5e8;
            padding: 2px 6px;
            border-radius: 12px;
            margin: 2px;
            flex-wrap: wrap;
        }

        .footer-note {
            margin-top: 1.4rem;
            text-align: center;
            font-size: 0.65rem;
            color: #6c8dab;
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
            border-top: 1px solid #ecf3f0;
            padding-top: 1.1rem;
        }

        #toast-container {
            position: fixed;
            bottom: 25px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            pointer-events: none;
            width: auto;
            max-width: 90%;
        }
        .toast-msg {
            background: #1f7840;
            color: white;
            padding: 10px 20px;
            border-radius: 48px;
            font-size: 0.75rem;
            font-weight: 500;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 8px;
            animation: slideUp 0.2s ease;
            pointer-events: none;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .summary-cards {
                grid-template-columns: repeat(2, 1fr);
            }
            .charges-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 550px) {
            .dashboard-content { padding: 1.2rem; }
            .card .value { font-size: 1.4rem; }
            .emi-table th, .emi-table td { padding: 0.7rem 0.6rem; }
            .bulk-action-bar { flex-direction: column; align-items: stretch; }
        }
    </style>
</head>
<body>
<div class="main-dashboard-box">
    <div class="dashboard-content">
        <div class="emi-header">
            <div class="title-section">
                <h1><i class="fas fa-calendar-check" style="color: #2c6e9e; margin-right: 8px;"></i> EMI Schedule</h1>
                <p>Track & pay monthly installments | Sequential payment only</p>
            </div>
            <div class="loan-badge" id="loanIdBadge">
                <i class="fas fa-file-invoice"></i> Loan ID: <span id="loanIdDisplay">Loading...</span>
            </div>
        </div>

        <!-- Sequential Payment Warning -->
        <div class="sequential-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <span><strong>Sequential Payment Rule:</strong> You can only pay the next unpaid EMI. Earlier EMIs must be paid before later ones.</span>
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards" id="summaryCards">
            <div class="card"><div class="label"><i class="fas fa-coins"></i> Total EMI</div><div class="value" id="totalEmiValue">₹ 0</div><div class="sub">sum of installments</div></div>
            <div class="card"><div class="label"><i class="fas fa-check-circle"></i> Paid EMIs</div><div class="value" id="paidCount">0</div><div class="sub">successful payments</div></div>
            <div class="card"><div class="label"><i class="fas fa-hourglass-half"></i> Unpaid</div><div class="value" id="unpaidCount">0</div><div class="sub">pending dues</div></div>
            <div class="card"><div class="label"><i class="fas fa-calendar-alt"></i> Next Due</div><div class="value" id="nextDueDate">—</div><div class="sub">upcoming date</div></div>
        </div>

        <!-- Charges Summary Section -->
        <div class="charges-summary" id="chargesSummary" style="display: block;">
            <div class="charges-title">
                <i class="fas fa-receipt"></i> Additional Charges Breakdown
            </div>
            <div class="charges-grid" id="chargesGrid"></div>
        </div>

        <!-- Bulk action panel -->
        <div class="bulk-action-bar">
            <div class="bulk-controls">
                <button id="selectAllBtn" class="select-all-btn"><i class="fas fa-check-double"></i> Select All Unpaid</button>
                <button id="payBulkBtn" class="pay-bulk-btn"><i class="fas fa-credit-card"></i> Pay Selected (<span id="selectedCount">0</span>)</button>
            </div>
            <div class="selected-info" id="bulkInfoTip">
                <i class="fas fa-info-circle"></i> Select all unpaid EMIs - they will be paid in sequence
            </div>
        </div>
        
        <!-- EMI Table -->
        <div class="emi-table-wrapper">
            <table class="emi-table">
                <thead>
                    <tr>
                        <th style="width: 35px;"><i class="fas fa-check-square"></i></th>
                        <th>EMI ID</th>
                        <th>No.</th>
                        <th>Principal + Interest</th>
                        <th>Charges</th>
                        <th>Total Payable</th>
                        <th>Paid Amount</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="emiTableBody"></tbody>
            </table>
        </div>
        <div class="footer-note">
            <span><i class="fas fa-shield-alt"></i> Secured gateway</span>
            <span><i class="fas fa-credit-card"></i> Sequential payment only</span>
            <span><i class="fas fa-history"></i> Real-time overdue detection</span>
            <span><i class="fas fa-receipt"></i> Charges include processing fee, late fee, penalty, gateway charges</span>
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="https://ebz-static.s3.ap-south-1.amazonaws.com/easecheckout/easebuzz-checkout.js"></script>
<script>
    // ------------------------------------------------------------------
    // LOAN DATA with charges
    const LOAN_ID = @json($loan_emi_structure['data'][0]['user_loan_id']);
    let emiList = @json($loan_emi_structure['data']);
    
    // Charge configuration from backend (passed from controller)
    const CHARGE_CONFIG = {
        processing_fee_rate: @json($loan_emi_structure['data'][0]['processing_fee_rate'] ?? 0),
        processing_gst_fee: @json($loan_emi_structure['data'][0]['processing_gst_fee'] ?? 18),
        processing_gst_type: @json($loan_emi_structure['data'][0]['processing_gst_type'] ?? 'excluding'),
        late_fee: @json($loan_emi_structure['data'][0]['late_fee'] ?? 0),
        late_gst_fee: @json($loan_emi_structure['data'][0]['late_gst_fee'] ?? 18),
        late_gst_type: @json($loan_emi_structure['data'][0]['late_gst_type'] ?? 'excluding'),
        pently_fee: @json($loan_emi_structure['data'][0]['pently_fee'] ?? 0)
    };
    
    console.log("Charge Configuration:", CHARGE_CONFIG);
    
    // Helper function to safely get numeric values
    function getNumericValue(value, defaultValue = 0) {
        const num = parseFloat(value);
        return isNaN(num) ? defaultValue : num;
    }
    
    /**
     * Calculate processing fee with GST based on GST type
     * For EXCLUDING: fee = processing_fee_amount, then GST = fee * (gst% / 100)
     * Example: processing_fee_amount=200, gst=18%
     *   base fee = 200
     *   gst = 200 * 18% = 36
     *   total = 236
     * 
     * For INCLUDING: total amount already includes GST, so base fee = total / (1 + gst%/100)
     */
    function calculateProcessingFeeWithGST(processingFeeAmount, returnBreakdown = false) {
        const gstPercent = CHARGE_CONFIG.processing_gst_fee;
        const gstType = CHARGE_CONFIG.processing_gst_type;
        
        // Convert to number and ensure it's valid
        const feeAmount = getNumericValue(processingFeeAmount);
        
        if (feeAmount === 0) {
            if (returnBreakdown) {
                return {
                    total: 0,
                    base: 0,
                    gst: 0,
                    gstPercent: gstPercent,
                    gstType: gstType
                };
            }
            return 0;
        }
        
        if (gstType === 'excluding') {
            // GST EXCLUDING: GST is added on top of the fee amount
            const gstAmount = (feeAmount * gstPercent) / 100;
            const totalAmount = feeAmount + gstAmount;
            
            if (returnBreakdown) {
                return {
                    total: totalAmount,
                    base: feeAmount,
                    gst: gstAmount,
                    gstPercent: gstPercent,
                    gstType: 'excluding'
                };
            }
            return totalAmount;
        } else {
            // GST INCLUDING: The fee amount already includes GST
            // So base fee = feeAmount / (1 + gst%/100)
            const baseFee = feeAmount / (1 + (gstPercent / 100));
            const gstAmount = feeAmount - baseFee;
            
            if (returnBreakdown) {
                return {
                    total: feeAmount,
                    base: baseFee,
                    gst: gstAmount,
                    gstPercent: gstPercent,
                    gstType: 'including'
                };
            }
            return feeAmount;
        }
    }
    
    /**
     * Calculate late fee with GST based on GST type
     * For EXCLUDING: fee = late_fee_amount, then GST = fee * (gst% / 100)
     * Example: late_fee_amount=200, gst=18%
     *   base fee = 200
     *   gst = 200 * 18% = 36
     *   total = 236
     * 
     * For INCLUDING: total amount already includes GST
     */
    function calculateLateFeeWithGST(lateFeeAmount, returnBreakdown = false) {
        const gstPercent = CHARGE_CONFIG.late_gst_fee;
        const gstType = CHARGE_CONFIG.late_gst_type;
        
        // Convert to number and ensure it's valid
        const feeAmount = getNumericValue(lateFeeAmount);
        
        if (feeAmount === 0) {
            if (returnBreakdown) {
                return {
                    total: 0,
                    base: 0,
                    gst: 0,
                    gstPercent: gstPercent,
                    gstType: gstType
                };
            }
            return 0;
        }
        
        if (gstType === 'excluding') {
            // GST EXCLUDING: GST is added on top of the fee amount
            const gstAmount = (feeAmount * gstPercent) / 100;
            const totalAmount = feeAmount + gstAmount;
            
            if (returnBreakdown) {
                return {
                    total: totalAmount,
                    base: feeAmount,
                    gst: gstAmount,
                    gstPercent: gstPercent,
                    gstType: 'excluding'
                };
            }
            return totalAmount;
        } else {
            // GST INCLUDING: The fee amount already includes GST
            const baseFee = feeAmount / (1 + (gstPercent / 100));
            const gstAmount = feeAmount - baseFee;
            
            if (returnBreakdown) {
                return {
                    total: feeAmount,
                    base: baseFee,
                    gst: gstAmount,
                    gstPercent: gstPercent,
                    gstType: 'including'
                };
            }
            return feeAmount;
        }
    }
    
    // Calculate penalty charges (fixed amount from config)
    function calculatePenaltyCharges() {
        return getNumericValue(CHARGE_CONFIG.pently_fee);
    }
    
    // Calculate total payable amount for an EMI (including all charges)
    function calculateTotalPayable(emi) {
        const principalInterest = getNumericValue(emi.emi_amount);
        
        let processingFee = getNumericValue(emi.processing_fee_amount);
        let lateFee = getNumericValue(emi.late_fee_amount);
        let penaltyCharges = getNumericValue(emi.pently_charges_amount);
        let gatewayCharges = getNumericValue(emi.payment_gateway_charges);
        
        if (emi.emi_status !== 'paid') {
            // Calculate processing fee with GST
            if (processingFee > 0) {
                processingFee = calculateProcessingFeeWithGST(processingFee, false);
            } else if (CHARGE_CONFIG.processing_fee_rate > 0) {
                const calculatedFeeAmount = (principalInterest * CHARGE_CONFIG.processing_fee_rate) / 100;
                if (calculatedFeeAmount > 0) {
                    processingFee = calculateProcessingFeeWithGST(calculatedFeeAmount, false);
                }
            }
            
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const dueDate = new Date(emi.emi_due_date);
            const isOverdue = dueDate < today;
            
            // Calculate late fee with GST if overdue
            if (isOverdue) {
                if (lateFee > 0) {
                    lateFee = calculateLateFeeWithGST(lateFee, false);
                } else if (CHARGE_CONFIG.late_fee > 0) {
                    const calculatedFeeAmount = (principalInterest * CHARGE_CONFIG.late_fee) / 100;
                    if (calculatedFeeAmount > 0) {
                        lateFee = calculateLateFeeWithGST(calculatedFeeAmount, false);
                    }
                }
                
                if (penaltyCharges === 0 && CHARGE_CONFIG.pently_fee > 0) {
                    penaltyCharges = calculatePenaltyCharges();
                }
            }
        }
        
        let totalDue = principalInterest + processingFee + lateFee + penaltyCharges + gatewayCharges;
        
        if (emi.emi_status === 'partial_paid') {
            const paidAmount = getNumericValue(emi.paid_amount);
            totalDue = Math.max(0, totalDue - paidAmount);
        }
        
        return totalDue;
    }
    
    // Get detailed charges breakdown for an EMI with GST details
    function getChargesBreakdown(emi) {
        const principalInterest = getNumericValue(emi.emi_amount);
        let processingFee = getNumericValue(emi.processing_fee_amount);
        let lateFee = getNumericValue(emi.late_fee_amount);
        let penaltyCharges = getNumericValue(emi.pently_charges_amount);
        let gatewayCharges = getNumericValue(emi.payment_gateway_charges);
        
        let processingBreakdown = null;
        let lateBreakdown = null;
        
        if (emi.emi_status !== 'paid') {
            // Calculate processing fee breakdown
            if (processingFee > 0) {
                processingBreakdown = calculateProcessingFeeWithGST(processingFee, true);
                processingFee = processingBreakdown.total;
            } else if (CHARGE_CONFIG.processing_fee_rate > 0) {
                const calculatedFeeAmount = (principalInterest * CHARGE_CONFIG.processing_fee_rate) / 100;
                if (calculatedFeeAmount > 0) {
                    processingBreakdown = calculateProcessingFeeWithGST(calculatedFeeAmount, true);
                    processingFee = processingBreakdown.total;
                }
            }
            
            // Check if overdue for late fee and penalty
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const dueDate = new Date(emi.emi_due_date);
            const isOverdue = dueDate < today;
            
            // Calculate late fee breakdown if overdue
            if (isOverdue) {
                if (lateFee > 0) {
                    lateBreakdown = calculateLateFeeWithGST(lateFee, true);
                    lateFee = lateBreakdown.total;
                } else if (CHARGE_CONFIG.late_fee > 0) {
                    const calculatedFeeAmount = (principalInterest * CHARGE_CONFIG.late_fee) / 100;
                    if (calculatedFeeAmount > 0) {
                        lateBreakdown = calculateLateFeeWithGST(calculatedFeeAmount, true);
                        lateFee = lateBreakdown.total;
                    }
                }
                
                if (penaltyCharges === 0 && CHARGE_CONFIG.pently_fee > 0) {
                    penaltyCharges = calculatePenaltyCharges();
                }
            }
        }
        
        return {
            processingFee,
            processingBreakdown,
            lateFee,
            lateBreakdown,
            penaltyCharges,
            gatewayCharges
        };
    }
    
    // Get total charges for an EMI
    function getTotalCharges(emi) {
        const breakdown = getChargesBreakdown(emi);
        return breakdown.processingFee + breakdown.lateFee + breakdown.penaltyCharges + breakdown.gatewayCharges;
    }
    
    // Format currency
    function formatCurrency(amount) {
        return new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', minimumFractionDigits: 2 }).format(amount);
    }

    /**
     * Format charge HTML with proper GST display based on GST type
     * For EXCLUDING: Show as "₹236 (₹200 + 18% GST ₹36)"
     * For INCLUDING: Show as "₹236 (incl. 18% GST ₹36, base ₹200)"
     */
    function formatChargeHTML(label, icon, amount, breakdown) {
        if (!amount || amount === 0) return '';

        if (breakdown && breakdown.gstType && breakdown.gst > 0) {
            if (breakdown.gstType === 'excluding') {
                // GST EXCLUDING: Show total amount with breakdown of base + GST
                return `<span class="charge-badge" title="${label} - Base: ${formatCurrency(breakdown.base)} + ${breakdown.gstPercent}% GST: ${formatCurrency(breakdown.gst)} = Total: ${formatCurrency(amount)}">
                    <i class="${icon}"></i> ${label}: ${formatCurrency(amount)} 
                    <span class="charge-sub">(${formatCurrency(breakdown.base)} + ${breakdown.gstPercent}% GST ${formatCurrency(breakdown.gst)})</span>
                </span>`;
            } else {
                // GST INCLUDING: Amount already includes GST
                return `<span class="charge-badge" title="${label} - Total: ${formatCurrency(amount)} (Base: ${formatCurrency(breakdown.base)} + GST: ${formatCurrency(breakdown.gst)})">
                    <i class="${icon}"></i> ${label}: ${formatCurrency(amount)} 
                    <span class="charge-sub">(incl. ${breakdown.gstPercent}% GST ${formatCurrency(breakdown.gst)})</span>
                </span>`;
            }
        }
        
        // Fallback: simple display if no breakdown available
        return `<span class="charge-badge"><i class="${icon}"></i> ${label}: ${formatCurrency(amount)}</span>`;
    }

    function showToast(message, type = 'success') {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            document.body.appendChild(container);
        }
        const toastDiv = document.createElement('div');
        toastDiv.className = 'toast-msg';
        let icon = type === 'success' ? 'fas fa-circle-check' : (type === 'info' ? 'fas fa-circle-info' : 'fas fa-exclamation-triangle');
        let bgColor = '#1f7840';
        if (type === 'info') bgColor = '#2c6e9e';
        if (type === 'error') bgColor = '#c26b1a';
        toastDiv.style.backgroundColor = bgColor;
        toastDiv.innerHTML = `<i class="${icon}"></i> ${message}`;
        container.appendChild(toastDiv);
        setTimeout(() => {
            toastDiv.style.opacity = '0';
            toastDiv.style.transform = 'translateY(10px)';
            toastDiv.style.transition = 'opacity 0.25s, transform 0.2s';
            setTimeout(() => { if (toastDiv.parentNode) toastDiv.remove(); }, 250);
        }, 2800);
    }

    function getNextPayableEmi() {
        const unpaidSorted = emiList
            .filter(emi => emi.emi_status === 'unpaid' || emi.emi_status === 'partial_paid')
            .sort((a, b) => a.emi_no - b.emi_no);
        return unpaidSorted.length > 0 ? unpaidSorted[0] : null;
    }

    function isEmiPayable(emi) {
        if (emi.emi_status === 'paid') return false;
        const lowerEmis = emiList.filter(e => e.emi_no < emi.emi_no);
        const allLowerFullyPaid = lowerEmis.every(e => e.emi_status === 'paid');
        return allLowerFullyPaid;
    }

    function getAllUnpaidEmis() {
        return emiList.filter(emi => emi.emi_status === 'unpaid' || emi.emi_status === 'partial_paid').sort((a, b) => a.emi_no - b.emi_no);
    }

    // Update charges summary section with proper GST display
    function updateChargesSummary() {
        const unpaidEmis = getAllUnpaidEmis();
        let totalProcessingFee = 0;
        let totalLateFee = 0;
        let totalPenaltyCharges = 0;
        let totalGatewayCharges = 0;
        let totalPrincipalInterest = 0;
        
        // Track breakdown for summary display
        let processingTotalBase = 0;
        let processingTotalGst = 0;
        let lateTotalBase = 0;
        let lateTotalGst = 0;
        
        unpaidEmis.forEach(emi => {
            const breakdown = getChargesBreakdown(emi);
            totalProcessingFee += breakdown.processingFee;
            totalLateFee += breakdown.lateFee;
            totalPenaltyCharges += breakdown.penaltyCharges;
            totalGatewayCharges += breakdown.gatewayCharges;
            totalPrincipalInterest += getNumericValue(emi.emi_amount);
            
            // Accumulate breakdown details
            if (breakdown.processingBreakdown) {
                processingTotalBase += breakdown.processingBreakdown.base;
                processingTotalGst += breakdown.processingBreakdown.gst;
            }
            if (breakdown.lateBreakdown) {
                lateTotalBase += breakdown.lateBreakdown.base;
                lateTotalGst += breakdown.lateBreakdown.gst;
            }
        });
        
        const totalCharges = totalProcessingFee + totalLateFee + totalPenaltyCharges + totalGatewayCharges;
        const chargesGrid = document.getElementById('chargesGrid');
        
        if (totalCharges > 0) {
            let processingDisplay = '';
            let lateDisplay = '';
            
            // Processing Fee display based on GST type
            if (totalProcessingFee > 0) {
                if (CHARGE_CONFIG.processing_gst_type === 'excluding') {
                    processingDisplay = `${formatCurrency(totalProcessingFee)} <span class="charge-sub">(${formatCurrency(processingTotalBase)} + ${CHARGE_CONFIG.processing_gst_fee}% GST ${formatCurrency(processingTotalGst)})</span>`;
                } else {
                    processingDisplay = `${formatCurrency(totalProcessingFee)} <span class="charge-sub">(incl. ${CHARGE_CONFIG.processing_gst_fee}% GST ${formatCurrency(processingTotalGst)})</span>`;
                }
            } else {
                processingDisplay = formatCurrency(0);
            }
            
            // Late Fee display based on GST type
            if (totalLateFee > 0) {
                if (CHARGE_CONFIG.late_gst_type === 'excluding') {
                    lateDisplay = `${formatCurrency(totalLateFee)} <span class="charge-sub">(${formatCurrency(lateTotalBase)} + ${CHARGE_CONFIG.late_gst_fee}% GST ${formatCurrency(lateTotalGst)})</span>`;
                } else {
                    lateDisplay = `${formatCurrency(totalLateFee)} <span class="charge-sub">(incl. ${CHARGE_CONFIG.late_gst_fee}% GST ${formatCurrency(lateTotalGst)})</span>`;
                }
            } else {
                lateDisplay = formatCurrency(0);
            }
            
            chargesGrid.innerHTML = `
                <div class="charge-item">
                    <div class="charge-label"><i class="fas fa-percent"></i> Processing Fee</div>
                    <div class="charge-amount">${processingDisplay}</div>
                </div>
                <div class="charge-item">
                    <div class="charge-label"><i class="fas fa-clock"></i> Late Fee</div>
                    <div class="charge-amount">${lateDisplay}</div>
                </div>
                <div class="charge-item">
                    <div class="charge-label"><i class="fas fa-gavel"></i> Penalty Charges</div>
                    <div class="charge-amount">${formatCurrency(totalPenaltyCharges)}</div>
                </div>
                <div class="charge-item">
                    <div class="charge-label"><i class="fas fa-credit-card"></i> Gateway Charges</div>
                    <div class="charge-amount">${formatCurrency(totalGatewayCharges)}</div>
                </div>
                <div class="charge-item total-payable">
                    <div class="label"><i class="fas fa-rupee-sign"></i> Total Payable (Principal + All Charges)</div>
                    <div class="amount">${formatCurrency(totalPrincipalInterest + totalCharges)}</div>
                </div>
            `;
        } else {
            chargesGrid.innerHTML = `<div class="charge-item"><div class="charge-label"><i class="fas fa-check-circle"></i> No additional charges on pending EMIs</div><div class="charge-amount">₹ 0.00</div></div>`;
        }
    }

    // Payment processing function
    async function processPaymentAjax(payload) {
        try {
            console.log("📡 EMI Payment Payload =>", payload);
    
            if (!payload.loan_id || !payload.type || !payload.emi_ids || payload.emi_ids.length === 0) {
                throw new Error("Invalid payment request");
            }
    
            const result = await fetch("/loan/pay/emi", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify(payload)
            });
    
            const response = await result.json();
            console.log("✅ EMI Payment Response =>", response);
    
            if (response.Success == true) {
                var easebuzzCheckout = new EasebuzzCheckout('0F50NJTNV', 'test');
                var options = {
                    access_key: response.code,
                    onResponse: async function(paymentResponse) {
                        console.log("💳 Payment Response =>", paymentResponse);
    
                        if (paymentResponse.status === "success") {
                            try {
                                const updateResult = await fetch("/loan/pay/update-emi", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "Accept": "application/json",
                                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                                    },
                                    body: JSON.stringify({
                                        ...payload,
                                        transaction_id: paymentResponse.txnid,
                                        payment_id: paymentResponse.easepayid,
                                        status: paymentResponse.status
                                    })
                                });
    
                                const updateResponse = await updateResult.json();
                                console.log("✅ EMI Update Response =>", updateResponse);
    
                                if (updateResponse.Success == true) {
                                    Toastify({
                                        text: "Payment successful",
                                        className: "success",
                                        duration: 3000,
                                        gravity: "top",
                                        position: "right",
                                        style: { background: "linear-gradient(to right, #00b09b, #96c93d)" }
                                    }).showToast();
    
                                    setTimeout(function () { window.location.reload(); }, 3000);
                                } else {
                                    Toastify({
                                        text: updateResponse.message || "Payment update failed",
                                        className: "error",
                                        duration: 3000,
                                        gravity: "top",
                                        position: "right",
                                        style: { background: "linear-gradient(to right, #ff5f6d, #ffc371)" }
                                    }).showToast();
                                }
                            } catch (updateError) {
                                console.error("❌ Update EMI Error =>", updateError);
                                Toastify({
                                    text: "Payment completed but update failed",
                                    className: "error",
                                    duration: 3000,
                                    gravity: "top",
                                    position: "right",
                                    style: { background: "linear-gradient(to right, #ff5f6d, #ffc371)" }
                                }).showToast();
                            }
                        } else if (paymentResponse.status === "failure") {
                            Toastify({
                                text: "Payment failed",
                                className: "error",
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                style: { background: "linear-gradient(to right, #ff5f6d, #ffc371)" }
                            }).showToast();
                            setTimeout(function () { window.location.href = "/payment-failed"; }, 2000);
                        } else if (paymentResponse.status === "userCancelled") {
                            Toastify({
                                text: "Transaction cancelled by user",
                                className: "info",
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                style: { background: "linear-gradient(to right, #ff9966, #ff5e62)" }
                            }).showToast();
                        } else {
                            Toastify({
                                text: paymentResponse.status,
                                className: "info",
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                style: { background: "linear-gradient(to right, #00b09b, #96c93d)" }
                            }).showToast();
                        }
                    },
                    theme: "#123456"
                };
                easebuzzCheckout.initiatePayment(options);
            } else {
                Toastify({
                    text: response.message || "Unable to initiate payment",
                    className: "error",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: { background: "linear-gradient(to right, #ff5f6d, #ffc371)" }
                }).showToast();
    
                if (response.message === 'session expired') {
                    setTimeout(function () { window.location.href = "address-proof"; }, 2000);
                }
            }
        } catch (error) {
            console.error("❌ EMI Payment Error =>", error);
            Toastify({
                text: error.message || "Something went wrong",
                className: "error",
                duration: 3000,
                gravity: "top",
                position: "right",
                style: { background: "linear-gradient(to right, #ff5f6d, #ffc371)" }
            }).showToast();
        }
    }

    async function handlePayments(emiIdsArray, singleAmount = null) {
        if (!emiIdsArray.length) {
            showToast("❌ No EMI selected for payment", "info");
            return false;
        }
    
        const selectedEmiObjects = emiIdsArray.map(id => emiList.find(e => e.user_emi_id === id)).filter(e => e);
        selectedEmiObjects.sort((a, b) => a.emi_no - b.emi_no);
        
        const nextPayable = getNextPayableEmi();
        
        if (!nextPayable) {
            showToast("🎉 All EMIs are already paid!", "info");
            return false;
        }
        
        if (selectedEmiObjects[0].user_emi_id !== nextPayable.user_emi_id) {
            showToast(`❌ Sequential payment required! Please pay EMI #${nextPayable.emi_no} first.`, "error");
            return false;
        }
        
        let expectedNumber = selectedEmiObjects[0].emi_no;
        for (let i = 0; i < selectedEmiObjects.length; i++) {
            if (selectedEmiObjects[i].emi_no !== expectedNumber + i) {
                showToast(`❌ Cannot skip EMI #${expectedNumber + i}. Please pay EMIs in sequence.`, "error");
                return false;
            }
            if (selectedEmiObjects[i].emi_status === 'paid') {
                showToast(`❌ EMI #${selectedEmiObjects[i].emi_no} is already fully paid.`, "error");
                return false;
            }
        }
    
        const paymentType = emiIdsArray.length === 1 ? "single" : "multiple";
        let payAmount = 0;
        
        emiIdsArray.forEach(emiId => {
            const emi = emiList.find(e => String(e.user_emi_id) === String(emiId));
            if (emi) {
                payAmount += calculateTotalPayable(emi);
            }
        });
        
        const payload = {
            loan_id: LOAN_ID,
            type: paymentType,
            emi_ids: emiIdsArray,
            pay_amount: payAmount,
            payment_date: new Date().toISOString().split('T')[0]
        };
        
        try {
            showToast(`⏳ Processing ${emiIdsArray.length} EMI(s) totaling ${formatCurrency(payAmount)}...`, "info");
            await processPaymentAjax(payload);
            return true;
        } catch (err) {
            console.error(err);
            showToast(`⚠️ Network error: ${err.message}`, "error");
            return false;
        }
    }

    let selectedEmiIds = new Set();

    function renderFullDashboard() {
        renderEMITableWithCharges();
        updateSummaryAndNextDue();
        updateSelectedCounter();
        updateChargesSummary();
        document.getElementById('loanIdDisplay').innerText = LOAN_ID;
    }

    function renderEMITableWithCharges() {
        const tbody = document.getElementById('emiTableBody');
        if (!tbody) return;
        tbody.innerHTML = '';
        const sorted = [...emiList].sort((a, b) => a.emi_no - b.emi_no);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        sorted.forEach(emi => {
            const isOverdue = ((emi.emi_status === 'unpaid' || emi.emi_status === 'partial_paid') && new Date(emi.emi_due_date) < today);
            const isPayable = isEmiPayable(emi);
            const totalPayable = calculateTotalPayable(emi);
            const breakdown = getChargesBreakdown(emi);
            const hasCharges = (breakdown.processingFee > 0 || breakdown.lateFee > 0 || breakdown.penaltyCharges > 0 || breakdown.gatewayCharges > 0);
            const isPartial = emi.emi_status === 'partial_paid';
            const paidAmount = getNumericValue(emi.paid_amount);
            
            const row = document.createElement('tr');
            
            const tdCheck = document.createElement('td');
            const chk = document.createElement('input');
            chk.type = 'checkbox';
            chk.className = 'emi-checkbox';
            chk.disabled = (emi.emi_status === 'paid');
            
            if (selectedEmiIds.has(emi.user_emi_id)) {
                chk.checked = true;
            }
            
            chk.addEventListener('change', (e) => {
                if (emi.emi_status === 'paid') {
                    e.preventDefault();
                    chk.checked = false;
                    showToast(`EMI already fully paid, cannot select`, 'info');
                    return;
                }
                
                if (chk.checked) {
                    selectedEmiIds.add(emi.user_emi_id);
                    if (!isPayable) {
                        const next = getNextPayableEmi();
                        if (next) {
                            showToast(`⚠️ EMI #${emi.emi_no} requires EMI #${next.emi_no} to be paid first.`, 'info');
                        }
                    }
                } else {
                    selectedEmiIds.delete(emi.user_emi_id);
                }
                updateSelectedCounter();
            });
            tdCheck.appendChild(chk);
            
            const tdId = document.createElement('td');
            tdId.innerHTML = `<span class="emi-id-monospace"><i class="fas fa-fingerprint"></i> ${emi.user_emi_id}</span>`;
            
            const tdNo = document.createElement('td');
            tdNo.innerHTML = `<strong>${emi.emi_no}</strong>`;
            
            const tdPrincipal = document.createElement('td');
            let principalAmount = getNumericValue(emi.emi_amount);
            tdPrincipal.innerHTML = `<span class="amount-highlight">${formatCurrency(principalAmount)}</span>`;
            
            const tdCharges = document.createElement('td');
            if (hasCharges && emi.emi_status !== 'paid') {
                let chargesHtml = '<div style="display: flex; flex-direction: column; gap: 3px;">';
                
                if (breakdown.processingFee > 0) {
                    chargesHtml += formatChargeHTML('Processing', 'fas fa-percent', breakdown.processingFee, breakdown.processingBreakdown);
                }
                if (breakdown.lateFee > 0) {
                    chargesHtml += formatChargeHTML('Late Fee', 'fas fa-clock', breakdown.lateFee, breakdown.lateBreakdown);
                }
                if (breakdown.penaltyCharges > 0) {
                    chargesHtml += `<span class="charge-badge" title="Penalty Charges ₹${CHARGE_CONFIG.pently_fee}"><i class="fas fa-gavel"></i> Penalty: ${formatCurrency(breakdown.penaltyCharges)}</span>`;
                }
                if (breakdown.gatewayCharges > 0) {
                    chargesHtml += `<span class="charge-badge" title="Payment Gateway Charges"><i class="fas fa-credit-card"></i> Gateway: ${formatCurrency(breakdown.gatewayCharges)}</span>`;
                }
                chargesHtml += '</div>';
                tdCharges.innerHTML = chargesHtml;
            } else {
                tdCharges.innerHTML = '<span style="color: #a0c0d0;">—</span>';
            }
            
            const tdTotal = document.createElement('td');
            tdTotal.innerHTML = `<span style="font-weight: 800; color: #c26b1a;">${formatCurrency(totalPayable)}</span>`;
            if ((hasCharges || isPartial) && emi.emi_status !== 'paid') {
                tdTotal.style.backgroundColor = '#fff9f0';
                tdTotal.style.borderRadius = '8px';
            }
            
            const tdPaid = document.createElement('td');
            tdPaid.innerHTML = `<span class="amount-highlight">${formatCurrency(paidAmount)}</span>`;
            
            const tdDate = document.createElement('td');
            tdDate.innerHTML = `<i class="far fa-calendar-alt" style="margin-right:5px;"></i> ${emi.emi_due_date}`;
            
            const tdStatus = document.createElement('td');
            let statusHtml = '';
            if (emi.emi_status === 'paid') {
                statusHtml = `<span class="status-badge status-paid"><i class="fas fa-check-circle"></i> Paid</span>`;
            } else if (emi.emi_status === 'partial_paid') {
                statusHtml = `<span class="status-badge status-partial"><i class="fas fa-clock"></i> Partial Paid</span>`;
            } else if (emi.emi_status === 'unpaid' && !isPayable) {
                statusHtml = `<span class="status-badge status-blocked"><i class="fas fa-lock"></i> Locked</span>`;
            } else if (emi.emi_status === 'unpaid' && isOverdue) {
                statusHtml = `<span class="status-badge status-overdue"><i class="fas fa-exclamation-triangle"></i> Overdue</span>`;
            } else if (emi.emi_status === 'unpaid') {
                statusHtml = `<span class="status-badge status-unpaid"><i class="fas fa-clock"></i> Unpaid</span>`;
            } else {
                statusHtml = `<span class="status-badge">${emi.emi_status}</span>`;
            }
            tdStatus.innerHTML = statusHtml;
            
            const tdAction = document.createElement('td');
            if (emi.emi_status === 'paid') {
                tdAction.innerHTML = `<span class="paid-label"><i class="fas fa-check-circle"></i> Paid</span>`;
            } else if (!isPayable) {
                const next = getNextPayableEmi();
                tdAction.innerHTML = `<span class="blocked-label"><i class="fas fa-lock"></i> Pay #${next?.emi_no || 'Earlier'} First</span>`;
            } else {
                const singlePayBtn = document.createElement('button');
                singlePayBtn.className = 'pay-btn';
                let btnText = '<i class="fas fa-credit-card"></i> Pay Now';
                if (hasCharges) {
                    let chargesTotal = breakdown.processingFee + breakdown.lateFee + breakdown.penaltyCharges + breakdown.gatewayCharges;
                    btnText += ` <span style="font-size: 0.65rem;">(+${formatCurrency(chargesTotal)} fees)</span>`;
                }
                if (isPartial) {
                    btnText = `<i class="fas fa-credit-card"></i> Pay Remaining (${formatCurrency(totalPayable)})`;
                }
                singlePayBtn.innerHTML = btnText;
                singlePayBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const currentNextPayable = getNextPayableEmi();
                    if (currentNextPayable && currentNextPayable.user_emi_id !== emi.user_emi_id) {
                        showToast(`❌ Cannot pay EMI #${emi.emi_no} yet. Please pay EMI #${currentNextPayable.emi_no} first.`, 'error');
                        return;
                    }
                    handlePayments([emi.user_emi_id]);
                });
                tdAction.appendChild(singlePayBtn);
            }
            
            row.appendChild(tdCheck);
            row.appendChild(tdId);
            row.appendChild(tdNo);
            row.appendChild(tdPrincipal);
            row.appendChild(tdCharges);
            row.appendChild(tdTotal);
            row.appendChild(tdPaid);
            row.appendChild(tdDate);
            row.appendChild(tdStatus);
            row.appendChild(tdAction);
            tbody.appendChild(row);
        });
    }
    
    function updateSummaryAndNextDue() {
        let totalSum = 0, paidCounter = 0, unpaidCounter = 0;
        let earliestDue = null;
        emiList.forEach(emi => {
            let emiTotal = getNumericValue(emi.emi_amount) + getTotalCharges(emi);
            totalSum += emiTotal;
            if (emi.emi_status === 'paid') paidCounter++;
            else if (emi.emi_status === 'unpaid' || emi.emi_status === 'partial_paid') {
                unpaidCounter++;
                if (!earliestDue || new Date(emi.emi_due_date) < new Date(earliestDue)) earliestDue = emi.emi_due_date;
            }
        });
        
        let adjustedTotalSum = totalSum;
        emiList.forEach(emi => {
            if (emi.emi_status === 'partial_paid') {
                adjustedTotalSum -= getNumericValue(emi.paid_amount);
            }
        });
        
        document.getElementById('totalEmiValue').innerHTML = formatCurrency(adjustedTotalSum);
        document.getElementById('paidCount').innerHTML = paidCounter;
        document.getElementById('unpaidCount').innerHTML = unpaidCounter;
        if (earliestDue) document.getElementById('nextDueDate').innerHTML = earliestDue;
        else document.getElementById('nextDueDate').innerHTML = '🎉 All Settled';
    }
    
    function updateSelectedCounter() {
        document.getElementById('selectedCount').innerHTML = selectedEmiIds.size;
    }
    
    function selectAllUnpaid() {
        const allUnpaid = getAllUnpaidEmis();
        selectedEmiIds.clear();
        allUnpaid.forEach(emi => {
            selectedEmiIds.add(emi.user_emi_id);
        });
        renderFullDashboard();
        
        if (allUnpaid.length === 0) {
            showToast(`📭 No unpaid EMIs available`, 'info');
        } else {
            let totalPayable = 0;
            allUnpaid.forEach(emi => {
                totalPayable += calculateTotalPayable(emi);
            });
            showToast(`✅ ${allUnpaid.length} unpaid EMI(s) selected. Total payable: ${formatCurrency(totalPayable)}`, 'info');
        }
    }
    
    async function bulkPayment() {
        const selectedArray = Array.from(selectedEmiIds);
        if (selectedArray.length === 0) {
            showToast("📭 No EMI selected for bulk payment", "info");
            return;
        }
        
        const selectedEmiObjects = selectedArray
            .map(id => emiList.find(e => e.user_emi_id === id))
            .filter(e => e && (e.emi_status === 'unpaid' || e.emi_status === 'partial_paid'))
            .sort((a, b) => a.emi_no - b.emi_no);
        
        if (selectedEmiObjects.length === 0) {
            showToast("❌ No valid unpaid EMIs selected", "error");
            return;
        }
        
        const nextPayable = getNextPayableEmi();
        if (!nextPayable) {
            showToast("🎉 All EMIs are already paid!", "info");
            return;
        }
        
        let consecutiveEmis = [];
        let expectedNo = nextPayable.emi_no;
        
        for (let i = 0; i < selectedEmiObjects.length; i++) {
            if (selectedEmiObjects[i].emi_no === expectedNo) {
                consecutiveEmis.push(selectedEmiObjects[i]);
                expectedNo++;
            } else {
                break;
            }
        }
        
        if (consecutiveEmis.length === 0) {
            showToast(`❌ Cannot pay selected EMIs. Please start from EMI #${nextPayable.emi_no}.`, "error");
            return;
        }
        
        if (consecutiveEmis.length < selectedEmiObjects.length) {
            const totalSelected = selectedEmiObjects.length;
            const totalConsecutive = consecutiveEmis.length;
            showToast(`⚠️ Only ${totalConsecutive} consecutive EMI(s) from EMI #${nextPayable.emi_no} will be paid. ${totalSelected - totalConsecutive} EMI(s) skipped.`, "info");
        }
        
        const consecutiveIds = consecutiveEmis.map(e => e.user_emi_id);
        await handlePayments(consecutiveIds);
        selectedEmiIds.clear();
        renderFullDashboard();
    }
    
    window.addEventListener('DOMContentLoaded', () => {
        renderFullDashboard();
        document.getElementById('selectAllBtn')?.addEventListener('click', selectAllUnpaid);
        document.getElementById('payBulkBtn')?.addEventListener('click', bulkPayment);
        document.getElementById('loanIdDisplay').innerText = LOAN_ID;
        console.log("✅ EMI Dashboard with CORRECT GST CALCULATION");
        console.log("Processing Fee with GST: If fee = ₹200, GST 18% = ₹36, Total = ₹236");
        console.log("Charge Config Used:", CHARGE_CONFIG);
        console.log("Processing GST Type:", CHARGE_CONFIG.processing_gst_type);
        console.log("Late GST Type:", CHARGE_CONFIG.late_gst_type);
        
        // Test calculation for excluding GST example
        const testProcessingAmount = 200;
        const testProcessingResult = calculateProcessingFeeWithGST(testProcessingAmount, true);
        console.log(`Test: Processing Fee Amount ₹${testProcessingAmount}, GST ${CHARGE_CONFIG.processing_gst_fee}% (${CHARGE_CONFIG.processing_gst_type})`);
        if (testProcessingResult.gst > 0) {
            console.log(`Result: Base Fee = ₹${testProcessingResult.base.toFixed(2)}, GST = ₹${testProcessingResult.gst.toFixed(2)}, Total = ₹${testProcessingResult.total.toFixed(2)}`);
        }
    });
</script>
</body>
@endsection
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pay Later</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
      rel="stylesheet"
    />
    <link rel="shortcut icon" href="/images/rr-logo.png" type="image/x-icon" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
      integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <style>
      :root {
        --primary-color: #4e73df;
        --primary-light: #7a9cf0;
        --primary-dark: #3a56b0;
        --accent-color: #ffa000;
        --light-bg: #f8f9fc;
        --dark-text: #2e384d;
        --light-text: #8898aa;
        --success-color: #1cc88a;
        --warning-color: #f6c23e;
        --danger-color: #e74a3b;
      }
      
      body {
        font-family: "Comfortaa", sans-serif;
        background-color: var(--light-bg);
        color: var(--dark-text);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      
      .card-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(78, 115, 223, 0.15);
        overflow: hidden;
        width: 100%;
      }
      
      .card-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 25px 30px;
        position: relative;
      }
      
      .card-header::after {
        content: '';
        position: absolute;
        bottom: -20px;
        left: 0;
        width: 100%;
        height: 40px;
        background: white;
        border-radius: 50% 50% 0 0;
      }
      
      .page-title {
        text-align: center;
        font-weight: 700;
        font-size: 24px;
        margin: 10px 0;
      }
      
      .card-body {
        padding: 40px 30px 30px;
      }
      
      .transaction-summary {
        background: linear-gradient(135deg, #f8f9fc, #e9ecef);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        text-align: center;
        position: relative;
        overflow: hidden;
      }

      .transaction-summary::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
      }

      .transaction-icon {
        font-size: 48px;
        color: var(--primary-color);
        margin-bottom: 15px;
        display: inline-block;
        padding: 15px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      }

      .transaction-amount {
        font-size: 32px;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 5px;
      }

      .transaction-label {
        color: var(--light-text);
        font-size: 16px;
        margin-bottom: 20px;
      }

      .beneficiary-selection {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        margin-bottom: 25px;
      }

      .beneficiary-title {
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--dark-text);
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
      }

      .beneficiary-title i {
        color: var(--primary-color);
      }

      .beneficiary-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
        max-height: 300px;
        overflow-y: auto;
        padding: 10px 5px;
      }

      .beneficiary-item {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
      }

      .beneficiary-item:hover {
        border-color: var(--primary-light);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.1);
      }

      .beneficiary-item.selected {
        border-color: var(--primary-color);
        background: rgba(78, 115, 223, 0.05);
      }

      .beneficiary-item.selected::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: var(--primary-color);
      }

      .beneficiary-radio {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
      }

      .beneficiary-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .beneficiary-info {
        flex: 1;
      }

      .beneficiary-name {
        font-weight: 600;
        font-size: 16px;
        color: var(--dark-text);
        margin-bottom: 5px;
      }

      .beneficiary-details {
        font-size: 14px;
        color: var(--light-text);
      }

      .beneficiary-bank {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 5px;
      }

      .bank-icon {
        color: var(--primary-color);
        font-size: 16px;
      }

      .beneficiary-check {
        width: 24px;
        height: 24px;
        border: 2px solid #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        flex-shrink: 0;
      }

      .beneficiary-item.selected .beneficiary-check {
        background: var(--primary-color);
        border-color: var(--primary-color);
      }

      .beneficiary-item.selected .beneficiary-check::after {
        content: '✓';
        color: white;
        font-size: 14px;
        font-weight: bold;
      }

      .add-beneficiary-btn {
        background: transparent;
        border: 2px dashed #e9ecef;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: var(--light-text);
        margin-top: 10px;
      }

      .add-beneficiary-btn:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        background: rgba(78, 115, 223, 0.05);
      }

      .add-beneficiary-btn i {
        font-size: 24px;
        margin-bottom: 8px;
        display: block;
      }

      .amount-details {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        margin-bottom: 25px;
      }

      .amount-title {
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--dark-text);
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
      }

      .amount-title i {
        color: var(--primary-color);
      }

      .amount-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
      }

      .amount-item:last-child {
        border-bottom: none;
      }

      .amount-label {
        color: var(--light-text);
      }

      .amount-value {
        font-weight: 600;
        color: var(--dark-text);
      }

      .settlement-amount {
        background: linear-gradient(135deg, var(--success-color), #17a673);
        color: white;
        border-radius: 12px;
        padding: 20px;
        margin: 20px 0;
        text-align: center;
        box-shadow: 0 5px 15px rgba(28, 200, 138, 0.3);
      }

      .settlement-label {
        font-size: 16px;
        margin-bottom: 8px;
      }

      .settlement-value {
        font-size: 28px;
        font-weight: 700;
      }

      .terms-section {
        background: #f8f9fc;
        border-radius: 15px;
        padding: 25px;
        margin-top: 25px;
        border: 1px solid #e9ecef;
      }

      .terms-checkbox {
        display: flex;
        align-items: flex-start;
        gap: 15px;
      }

      .terms-checkbox input {
        margin-top: 3px;
        transform: scale(1.2);
      }

      .terms-label {
        font-size: 14px;
        color: var(--dark-text);
        line-height: 1.5;
      }

      .terms-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        border-bottom: 1px dotted var(--primary-color);
      }

      .terms-link:hover {
        color: var(--primary-dark);
        text-decoration: none;
      }

      .action-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
      }

      .action-buttons .btn {
        min-width: 150px;
        border-radius: 10px;
        padding: 12px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
      }

      .btn-secondary {
        background: white;
        border: 2px solid #6c757d;
        color: #6c757d;
      }

      .btn-secondary:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
      }

      .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border: none;
        box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
      }

      .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(78, 115, 223, 0.4);
      }

      .btn-primary:disabled {
        background: #b0b0b0;
        transform: none;
        box-shadow: none;
        cursor: not-allowed;
      }

      /* Success Modal Styles */
      .success-modal .modal-content {
        border-radius: 20px;
        overflow: hidden;
        border: none;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        animation: modalSlideIn 0.5s ease-out;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
      }

      @keyframes modalSlideIn {
        from {
          opacity: 0;
          transform: translateY(-30px) scale(0.95);
        }
        to {
          opacity: 1;
          transform: translateY(0) scale(1);
        }
      }

      .success-modal .modal-header {
        background: linear-gradient(135deg, var(--success-color), #17a673);
        color: white;
        border-bottom: none;
        padding: 25px 30px;
        position: relative;
        overflow: hidden;
        text-align: center;
        flex-shrink: 0;
      }

      .success-modal .modal-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(45deg);
        pointer-events: none; /* allow clicks to pass through to close button */
      }

      .success-modal .modal-title {
        font-weight: 700;
        font-size: 24px;
        position: relative;
        z-index: 1;
      }

      .success-modal .close {
        color: white;
        opacity: 0.8;
        text-shadow: none;
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
      }

      .success-modal .close:hover {
        opacity: 1;
        transform: scale(1.1);
      }

      .success-modal .modal-body {
        padding: 0;
        overflow-y: auto;
        flex: 1;
      }

      .success-container {
        padding: 30px;
        text-align: center;
      }

      .success-animation {
        margin-bottom: 25px;
      }

      .success-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--success-color), #17a673);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: white;
        font-size: 48px;
        animation: successPulse 2s infinite;
        box-shadow: 0 10px 30px rgba(28, 200, 138, 0.3);
      }

      @keyframes successPulse {
        0% {
          box-shadow: 0 0 0 0 rgba(28, 200, 138, 0.7);
        }
        70% {
          box-shadow: 0 0 0 20px rgba(28, 200, 138, 0);
        }
        100% {
          box-shadow: 0 0 0 0 rgba(28, 200, 138, 0);
        }
      }

      .success-title {
        font-size: 28px;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 10px;
      }

      .success-subtitle {
        color: var(--light-text);
        font-size: 16px;
        margin-bottom: 30px;
      }

      .success-details {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        margin-bottom: 25px;
        text-align: left;
      }

      .success-detail-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
      }

      .success-detail-item:last-child {
        border-bottom: none;
      }

      .detail-label {
        color: var(--light-text);
      }

      .detail-value {
        font-weight: 600;
        color: var(--dark-text);
      }

      .success-timeline {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        margin-bottom: 25px;
        text-align: left;
      }

      .timeline-title {
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--dark-text);
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
      }

      .timeline-title i {
        color: var(--primary-color);
      }

      .timeline-steps {
        display: flex;
        flex-direction: column;
        gap: 20px;
      }

      .timeline-step {
        display: flex;
        gap: 15px;
        align-items: flex-start;
      }

      .timeline-icon {
        width: 40px;
        height: 40px;
        background: var(--light-bg);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 18px;
        flex-shrink: 0;
      }

      .timeline-content {
        flex: 1;
      }

      .timeline-step-title {
        font-weight: 600;
        margin-bottom: 5px;
        color: var(--dark-text);
      }

      .timeline-step-desc {
        color: var(--light-text);
        font-size: 14px;
      }

      .success-modal .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 20px 30px;
        display: flex;
        justify-content: center;
        background: #f8f9fc;
        gap: 15px;
        flex-shrink: 0;
      }

      .success-modal .modal-footer .btn {
        min-width: 150px;
        border-radius: 10px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
      }

      .btn-outline-primary {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        background: white;
      }

      .btn-outline-primary:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
      }

      #loader {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: rgba(255, 255, 255, 0.8) url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIHZpZXdCb3g9IjAgMCA0OCA0OCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxjaXJjbGUgY3g9IjI0IiBjeT0iMjQiIHI9IjIyIiBzdHJva2U9IiM0ZTczZGYiIHN0cm9rZS13aWR0aD0iNCIvPjxwYXRoIGQ9Ik0yNCA0NkExOCAxOCAwIDAgMSA2IDI0IiBzdHJva2U9IiNmZmEwMDAiIHN0cm9rZS13aWR0aD0iNCI+PGFuaW1hdGVUcmFuc2Zvcm0gYXR0cmlidXRlTmFtZT0idHJhbnNmb3JtIiB0eXBlPSJyb3RhdGUiIGZyb209IjAgMjQgMjQiIHRvPSIzNjAgMjQgMjQiIGR1cj0iMC45cyIgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiLz48L3BhdGg+PC9nPjwvc3ZnPg==') 50% 50% no-repeat;
        display: none;
      }

      @media (max-width: 768px) {
        .card-body {
          padding: 30px 20px 20px;
        }
        
        .beneficiary-content {
          flex-direction: column;
          align-items: flex-start;
          gap: 10px;
        }
        
        .beneficiary-check {
          align-self: flex-end;
        }
        
        .action-buttons {
          flex-direction: column;
          gap: 15px;
        }
        
        .action-buttons .btn {
          width: 100%;
        }
        
        .success-modal .modal-footer {
          flex-direction: column;
        }
        
        .success-modal .modal-footer .btn {
          min-width: 100%;
        }
      }
    </style>

    <div id="loader"></div>
    
    <div class="card-container">
        <div class="card-header">
            <div class="page-title">Pay Later - Select Beneficiary</div>
        </div>
        
        <div class="card-body">
            <div class="transaction-summary">
                <div class="transaction-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="transaction-amount" id="transactionAmountDisplay">₹0.00</div>
                <div class="transaction-label">Pay Later Amount</div>
            </div>
            
            <div class="beneficiary-selection">
                <h5 class="beneficiary-title">
                    <i class="fas fa-users"></i>
                    Select Beneficiary
                </h5>
                <div class="beneficiary-list" id="beneficiaryList">
                    <!-- Beneficiary items will be dynamically populated here -->
                </div>
                <div class="add-beneficiary-btn" id="addBeneficiaryBtn">
                    <i class="fas fa-plus-circle"></i>
                    Add New Beneficiary
                </div>
            </div>
            
            <div class="amount-details">
                <h5 class="amount-title">
                    <i class="fas fa-calculator"></i>
                    Amount Breakdown
                </h5>
                <div class="amount-item">
                    <span class="amount-label">Pay Later Amount</span>
                    <span class="amount-value" id="breakdownTransactionAmount">₹0.00</span>
                </div>
                <div class="amount-item">
                    <span class="amount-label">Processing Fee (2%)</span>
                    <span class="amount-value" id="processingFeeAmount">₹0.00</span>
                </div>
                <div class="amount-item">
                    <span class="amount-label">GST (18%)</span>
                    <span class="amount-value" id="gstAmount">₹0.00</span>
                </div>
                
                <div class="settlement-amount">
                    <div class="settlement-label">Total Payable Amount</div>
                    <div class="settlement-value" id="totalPayableAmount">₹0.00</div>
                </div>
            </div>

            <div class="paylater-terms mt-4 p-4 bg-light rounded">
                <h6 class="font-weight-bold mb-3"><i class="fas fa-info-circle text-primary mr-2"></i>Pay Later Terms</h6>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i> Credit period: 30 days from transaction date</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i> Interest-free if paid within due date</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i> Late payment fee: 2% per month after due date</li>
                    <li><i class="fas fa-check-circle text-success mr-2"></i> Auto-debit from registered account on due date</li>
                </ul>
            </div>
            
            <div class="terms-section">
                <div class="terms-checkbox">
                    <input type="checkbox" id="beneficiaryTermsCheckbox" />
                    <label for="beneficiaryTermsCheckbox" class="terms-label">
                        I/We understand that by clicking the "Confirm Pay Later" button that
                        I have read, understood and agree to all the 
                        <a href="#" class="terms-link" id="termsLink">Pay Later Terms & Conditions</a> 
                        related to this transaction.
                    </label>
                </div>
            </div>
            
            <div class="action-buttons">
                <button type="button" class="btn btn-secondary" id="beneficiaryCancelBtn">Cancel</button>
                <button type="button" class="btn btn-primary" id="beneficiaryConfirmBtn" disabled>Confirm Pay Later</button>
            </div>
        </div>
    </div>
    
    <!-- Success Modal -->
    <div class="modal fade success-modal" id="successModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pay Later Successful!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="success-container">
                        <div class="success-animation">
                            <div class="success-icon">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                        <h2 class="success-title">Pay Later Approved!</h2>
                        <p class="success-subtitle">Your Pay Later transaction has been successfully processed</p>
                        
                        <div class="success-details">
                            <div class="success-detail-item">
                                <span class="detail-label">Transaction ID</span>
                                <span class="detail-value" id="transactionIdDisplay">PL-{{ date('YmdHis') }}</span>
                            </div>
                            <div class="success-detail-item">
                                <span class="detail-label">Pay Later Amount</span>
                                <span class="detail-value" id="successPayLaterAmount">₹0.00</span>
                            </div>
                            <div class="success-detail-item">
                                <span class="detail-label">Total Payable</span>
                                <span class="detail-value" id="successTotalPayable">₹0.00</span>
                            </div>
                            <div class="success-detail-item">
                                <span class="detail-label">Due Date</span>
                                <span class="detail-value" id="dueDateDisplay">{{ date('d-m-Y', strtotime('+30 days')) }}</span>
                            </div>
                            <div class="success-detail-item">
                                <span class="detail-label">Expected Disbursal</span>
                                <span class="detail-value">Within 2-4 hours</span>
                            </div>
                        </div>
                        
                        <div class="success-timeline">
                            <h5 class="timeline-title">
                                <i class="fas fa-road"></i>
                                What's Next?
                            </h5>
                            <div class="timeline-steps">
                                <div class="timeline-step">
                                    <div class="timeline-icon">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="timeline-step-title">Immediate Disbursement</div>
                                        <div class="timeline-step-desc">Amount will be transferred to beneficiary within hours</div>
                                    </div>
                                </div>
                                <div class="timeline-step">
                                    <div class="timeline-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="timeline-step-title">Credit Period</div>
                                        <div class="timeline-step-desc">Enjoy 30 days interest-free credit period</div>
                                    </div>
                                </div>
                                <div class="timeline-step">
                                    <div class="timeline-icon">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="timeline-step-title">Payment Reminder</div>
                                        <div class="timeline-step-desc">We'll remind you 3 days before due date</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">View Transaction</button>
                    <button type="button" class="btn btn-primary" id="downloadDetailsBtn">Download Details</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get amount from URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            const payLaterAmount = urlParams.get('amount') || 0;
            
            // Initialize transaction data
            const transactionData = {
                payLaterAmount: parseInt(payLaterAmount),
                processingFeeRate: 2, // 2% processing fee
                gstRate: 18 // 18% GST
            };
            
            // Calculate amounts for Pay Later
            function calculatePayLaterAmounts() {
                const processingFee = Math.round(transactionData.payLaterAmount * (transactionData.processingFeeRate / 100));
                const gstAmount = Math.round(processingFee * (transactionData.gstRate / 100));
                const totalPayable = transactionData.payLaterAmount + processingFee + gstAmount;
                
                return {
                    payLaterAmount: transactionData.payLaterAmount,
                    processingFee: processingFee,
                    gstAmount: gstAmount,
                    totalPayable: totalPayable
                };
            }
            
            // Update UI with calculated amounts
            function updateAmountsUI() {
                const amounts = calculatePayLaterAmounts();
                
                // Update transaction summary
                document.getElementById('transactionAmountDisplay').textContent = 
                    '₹' + amounts.payLaterAmount.toLocaleString('en-IN');
                
                // Update amount breakdown
                document.getElementById('breakdownTransactionAmount').textContent = 
                    '₹' + amounts.payLaterAmount.toLocaleString('en-IN');
                document.getElementById('processingFeeAmount').textContent = 
                    '₹' + amounts.processingFee.toLocaleString('en-IN');
                document.getElementById('gstAmount').textContent = 
                    '₹' + amounts.gstAmount.toLocaleString('en-IN');
                document.getElementById('totalPayableAmount').textContent = 
                    '₹' + amounts.totalPayable.toLocaleString('en-IN');
                
                // Update success modal amounts
                document.getElementById('successPayLaterAmount').textContent = 
                    '₹' + amounts.payLaterAmount.toLocaleString('en-IN');
                document.getElementById('successTotalPayable').textContent = 
                    '₹' + amounts.totalPayable.toLocaleString('en-IN');
            }
            
            // Sample beneficiary data
            const beneficiaries = [
                {
                    id: 1,
                    name: "Rajesh Kumar",
                    accountNumber: "1234567890123456",
                    accountType: "Savings",
                    ifscCode: "ICIC0000058",
                    bankName: "ICICI Bank"
                },
                {
                    id: 2,
                    name: "Priya Sharma",
                    accountNumber: "9876543210987654",
                    accountType: "Current",
                    ifscCode: "HDFC0000123",
                    bankName: "HDFC Bank"
                },
                {
                    id: 3,
                    name: "Amit Patel",
                    accountNumber: "4567890123456789",
                    accountType: "Savings",
                    ifscCode: "SBIN0000456",
                    bankName: "State Bank of India"
                }
            ];
            
            let selectedBeneficiary = null;
            
            // Generate beneficiary list dynamically
            function generateBeneficiaryList() {
                const beneficiaryList = document.getElementById('beneficiaryList');
                beneficiaryList.innerHTML = '';
                
                beneficiaries.forEach(beneficiary => {
                    const maskedAccountNumber = maskAccountNumber(beneficiary.accountNumber);
                    
                    const beneficiaryItem = document.createElement('div');
                    beneficiaryItem.className = `beneficiary-item ${selectedBeneficiary && selectedBeneficiary.id === beneficiary.id ? 'selected' : ''}`;
                    beneficiaryItem.innerHTML = `
                        <input type="radio" name="beneficiary" value="${beneficiary.id}" class="beneficiary-radio" ${selectedBeneficiary && selectedBeneficiary.id === beneficiary.id ? 'checked' : ''} />
                        <div class="beneficiary-content">
                            <div class="beneficiary-info">
                                <div class="beneficiary-name">${beneficiary.name}</div>
                                <div class="beneficiary-details">
                                    Account: ${maskedAccountNumber} | ${beneficiary.accountType}
                                </div>
                                <div class="beneficiary-bank">
                                    <i class="fas fa-university bank-icon"></i>
                                    ${beneficiary.bankName} • ${beneficiary.ifscCode}
                                </div>
                            </div>
                            <div class="beneficiary-check"></div>
                        </div>
                    `;
                    
                    beneficiaryItem.addEventListener('click', function() {
                        selectBeneficiary(beneficiary);
                    });
                    
                    beneficiaryList.appendChild(beneficiaryItem);
                });
                
                updateBeneficiaryConfirmButton();
            }
            
            // Mask account number
            function maskAccountNumber(accountNumber) {
                if (accountNumber && accountNumber.length > 7) {
                    const lastFourDigits = accountNumber.slice(-4);
                    const maskedPart = accountNumber.slice(0, -4).replace(/./g, "X");
                    return maskedPart + lastFourDigits;
                } else {
                    return accountNumber || "XXXXXXXXXXXXXXXX";
                }
            }
            
            // Select beneficiary
            function selectBeneficiary(beneficiary) {
                selectedBeneficiary = beneficiary;
                generateBeneficiaryList();
            }
            
            // Update beneficiary confirm button state
            function updateBeneficiaryConfirmButton() {
                const beneficiaryConfirmBtn = document.getElementById('beneficiaryConfirmBtn');
                const termsCheckbox = document.getElementById('beneficiaryTermsCheckbox');
                
                beneficiaryConfirmBtn.disabled = !(selectedBeneficiary && termsCheckbox.checked);
            }
            
            // Create Pay Later transaction for transactions page
            function createPayLaterTransaction(selectedBeneficiary, payLaterAmount) {
                const amounts = calculatePayLaterAmounts();
                
                const newTransaction = {
                    id: `TXN${Date.now()}`,
                    reference: `PL${Math.random().toString(36).substr(2, 9).toUpperCase()}`,
                    amount: parseInt(payLaterAmount),
                    type: "Pay Later",
                    beneficiary: {
                        name: selectedBeneficiary.name,
                        bank: selectedBeneficiary.bankName
                    },
                    status: "Success",
                    date: new Date().toISOString(),
                    payLaterDetails: {
                        dueDate: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString(),
                        processingFee: amounts.processingFee,
                        gstAmount: amounts.gstAmount,
                        totalPayable: amounts.totalPayable
                    }
                };
                
                // Store in sessionStorage for transactions page
                sessionStorage.setItem('newPayLaterTransaction', JSON.stringify(newTransaction));
                console.log('Pay Later Transaction created:', newTransaction);
                
                // Also update localStorage directly for immediate access
                updateLocalStorageTransactions(newTransaction);
                
                return newTransaction;
            }
            
            // Update localStorage transactions
            function updateLocalStorageTransactions(newTransaction) {
                let existingTransactions = JSON.parse(localStorage.getItem('creditLimitTransactions') || '[]');
                existingTransactions.unshift(newTransaction); // Add to beginning
                localStorage.setItem('creditLimitTransactions', JSON.stringify(existingTransactions));
                console.log('Transaction saved to localStorage. Total transactions:', existingTransactions.length);
            }
            
            // Initialize page
            function initializePage() {
                updateAmountsUI();
                generateBeneficiaryList();
                
                // Log transaction data for debugging
                console.log('Pay Later Transaction Data:', transactionData);
                console.log('Pay Later Amount:', transactionData.payLaterAmount);
                
                // Show alert if amount is 0
                if (transactionData.payLaterAmount === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Amount Selected',
                        text: 'Please go back and select a credit amount for Pay Later.',
                        confirmButtonText: 'Go Back'
                    }).then(() => {
                        window.history.back();
                    });
                }
            }
            
            // Initialize beneficiary list and amounts
            initializePage();
            
            // Beneficiary Details Modal Logic
            const beneficiaryConfirmBtn = document.getElementById('beneficiaryConfirmBtn');
            const beneficiaryCancelBtn = document.getElementById('beneficiaryCancelBtn');
            const beneficiaryTermsCheckbox = document.getElementById('beneficiaryTermsCheckbox');
            const addBeneficiaryBtn = document.getElementById('addBeneficiaryBtn');
            const termsLink = document.getElementById('termsLink');
            
            // Enable/disable confirm button based on checkbox state and beneficiary selection
            beneficiaryTermsCheckbox.addEventListener('change', function() {
                updateBeneficiaryConfirmButton();
            });
            
            // Add new beneficiary button
            addBeneficiaryBtn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Add New Beneficiary',
                    html: `
                        <input type="text" id="beneficiaryName" class="swal2-input" placeholder="Beneficiary Name">
                        <input type="text" id="beneficiaryAccount" class="swal2-input" placeholder="Account Number">
                        <select id="beneficiaryType" class="swal2-input">
                            <option value="">Select Account Type</option>
                            <option value="Savings">Savings</option>
                            <option value="Current">Current</option>
                        </select>
                        <input type="text" id="beneficiaryIfsc" class="swal2-input" placeholder="IFSC Code">
                        <input type="text" id="beneficiaryBank" class="swal2-input" placeholder="Bank Name">
                    `,
                    focusConfirm: false,
                    preConfirm: () => {
                        const name = document.getElementById('beneficiaryName').value;
                        const account = document.getElementById('beneficiaryAccount').value;
                        const type = document.getElementById('beneficiaryType').value;
                        const ifsc = document.getElementById('beneficiaryIfsc').value;
                        const bank = document.getElementById('beneficiaryBank').value;
                        
                        if (!name || !account || !type || !ifsc || !bank) {
                            Swal.showValidationMessage('Please fill all fields');
                            return false;
                        }
                        
                        return { name, account, type, ifsc, bank };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const newBeneficiary = {
                            id: beneficiaries.length + 1,
                            name: result.value.name,
                            accountNumber: result.value.account,
                            accountType: result.value.type,
                            ifscCode: result.value.ifsc,
                            bankName: result.value.bank
                        };
                        
                        beneficiaries.push(newBeneficiary);
                        selectBeneficiary(newBeneficiary);
                         
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Beneficiary added successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            });
            
            // Beneficiary cancel button
            beneficiaryCancelBtn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Your Pay Later transaction will be cancelled",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4e73df',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, cancel it!',
                    cancelButtonText: 'Continue'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to credit limit page
                        window.location.href = "{{ url('/institute/admin/available-credit-limit') }}";
                    }
                });
            });
            
            // Beneficiary confirm button
            beneficiaryConfirmBtn.addEventListener('click', function() {
                // Check if beneficiary is selected
                if (!selectedBeneficiary) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Please select a beneficiary!"
                    });
                    return false;
                }
                
                // Check if terms and conditions are accepted
                if (!beneficiaryTermsCheckbox.checked) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Please accept the terms and conditions!"
                    });
                    return false;
                }
                
                // Check if amount is valid
                if (transactionData.payLaterAmount === 0) {
                    Swal.fire({
                        icon: "error",
                        title: "Invalid Amount",
                        text: "Please select a valid credit amount for Pay Later."
                    });
                    return false;
                }
                
                document.getElementById('loader').style.display = 'block';
                
                setTimeout(function() {
                    document.getElementById('loader').style.display = 'none';
                    
                    // Create transaction record before showing success modal
                    const newTransaction = createPayLaterTransaction(selectedBeneficiary, transactionData.payLaterAmount);
                    
                    // Update success modal with actual transaction ID
                    document.getElementById('transactionIdDisplay').textContent = newTransaction.id;
                    
                    // Show success modal after beneficiary confirmation
                    const successModalEl = document.getElementById('successModal');
                    const successModalInstance = bootstrap.Modal.getOrCreateInstance(successModalEl);
                    successModalInstance.show();
                }, 2000);
            });
            
            // Terms link handler
            termsLink.addEventListener('click', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Pay Later Terms & Conditions',
                    html: `
                        <div style="text-align: left; max-height: 400px; overflow-y: auto;">
                            <h5>1. PAY LATER AUTHORIZATION</h5>
                            <p>By confirming this Pay Later transaction, you authorize us to process the payment and agree to repay within the credit period.</p>
                            
                            <h5>2. CREDIT PERIOD</h5>
                            <p>Enjoy 30 days interest-free credit from the transaction date.</p>
                            
                            <h5>3. PAYMENT TERMS</h5>
                            <p>Full payment must be made by the due date to avoid late fees.</p>
                            
                            <h5>4. LATE PAYMENT FEES</h5>
                            <p>2% monthly interest will be charged on overdue amounts after the due date.</p>
                            
                            <h5>5. AUTO-DEBIT AUTHORIZATION</h5>
                            <p>You authorize auto-debit from your registered account on the due date.</p>
                            
                            <h5>6. TRANSACTION PROCESSING</h5>
                            <p>Pay Later transactions are processed immediately to the beneficiary.</p>
                            
                            <h5>7. CANCELLATION POLICY</h5>
                            <p>Pay Later transactions cannot be cancelled once processed.</p>
                            
                            <h5>8. GOVERNING LAW</h5>
                            <p>This agreement shall be governed by the laws of India.</p>
                        </div>
                    `,
                    width: 700,
                    confirmButtonText: 'I Understand'
                });
            });
            
            // ensure close button always hides modal (workaround for dismissal issue)
            const successClose = document.querySelector('#successModal .btn-close');
            if (successClose) {
                successClose.addEventListener('click', function() {
                    console.log('success modal close button clicked');
                    const mod = bootstrap.Modal.getOrCreateInstance(document.getElementById('successModal'));
                    mod.hide();
                });
            }

            // Download Details Button Functionality
            document.getElementById('downloadDetailsBtn').addEventListener('click', function() {
                const amounts = calculatePayLaterAmounts();
                
                // Create a PDF-like content for download
                const transactionDetails = `
                    PAY LATER TRANSACTION DETAILS
                    =============================
                    
                    Transaction ID: ${document.getElementById('transactionIdDisplay').textContent}
                    Pay Later Amount: ₹${amounts.payLaterAmount.toLocaleString('en-IN')}
                    Processing Fee (2%): ₹${amounts.processingFee.toLocaleString('en-IN')}
                    GST (18%): ₹${amounts.gstAmount.toLocaleString('en-IN')}
                    Total Payable: ₹${amounts.totalPayable.toLocaleString('en-IN')}
                    Due Date: ${document.getElementById('dueDateDisplay').textContent}
                    
                    Beneficiary Details:
                    -------------------
                    Name: ${selectedBeneficiary ? selectedBeneficiary.name : 'N/A'}
                    Account Number: ${selectedBeneficiary ? maskAccountNumber(selectedBeneficiary.accountNumber) : 'N/A'}
                    Account Type: ${selectedBeneficiary ? selectedBeneficiary.accountType : 'N/A'}
                    Bank: ${selectedBeneficiary ? selectedBeneficiary.bankName : 'N/A'}
                    IFSC Code: ${selectedBeneficiary ? selectedBeneficiary.ifscCode : 'N/A'}
                    
                    Transaction Status: Completed Successfully
                    Credit Period: 30 days interest-free
                    
                    Thank you for using our Pay Later services!
                `;
                
                // Create a Blob with the content
                const blob = new Blob([transactionDetails], { type: 'text/plain' });
                
                // Create a download link
                const downloadLink = document.createElement('a');
                downloadLink.href = URL.createObjectURL(blob);
                downloadLink.download = 'paylater_transaction_details.txt';
                
                // Trigger the download
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Download Started',
                    text: 'Your Pay Later transaction details have been downloaded',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Close the modal after download
                setTimeout(function() {
                    const successModalEl = document.getElementById('successModal');
                    const successModalInstance = bootstrap.Modal.getOrCreateInstance(successModalEl);
                    successModalInstance.hide();
                    
                    // Redirect to dashboard or home page
                    setTimeout(function() {
                        Swal.fire({
                            title: 'Pay Later Complete',
                            text: 'You will be redirected to your dashboard',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '/institute/dashboard';
                        });
                    }, 500);
                }, 500);
            });
        });
    </script>
@endsection
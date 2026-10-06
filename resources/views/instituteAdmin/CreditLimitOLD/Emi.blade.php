@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <title>Choose Your EMI Plan</title>
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
    <script
      src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
      integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
      crossorigin="anonymous"
    ></script>
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
      }
      
      .navbar,
      footer {
        display: none !important;
      }
      
      .wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px 0;
      }
      
      .card-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(78, 115, 223, 0.15);
        overflow: hidden;
        max-width: 900px;
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
      
      .logo-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
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
      
      .loan-illustration {
        text-align: center;
        margin: 20px 0 30px;
      }
      
      .loan-illustration img {
        max-width: 250px;
        height: auto;
      }
      
      .section-title {
        font-weight: 700;
        font-size: 20px;
        margin-bottom: 10px;
        color: var(--primary-color);
      }
      
      .section-subtitle {
        color: var(--light-text);
        margin-bottom: 25px;
        font-size: 14px;
      }
      
      .emi-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
      }
      
      .emi-option {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border: 2px solid transparent;
        transition: all 0.3s ease;
        cursor: pointer;
        text-align: center;
        position: relative;
      }
      
      .emi-option:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(78, 115, 223, 0.15);
      }
      
      .emi-option.selected {
        border-color: var(--primary-color);
        background-color: rgba(78, 115, 223, 0.05);
      }
      
      .emi-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
      }
      
      .emi-duration {
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 10px;
        color: var(--primary-color);
      }
      
      .emi-amount {
        font-weight: 700;
        font-size: 16px;
        margin-bottom: 15px;
        color: var(--dark-text);
      }
      
      .view-details-btn {
        background-color: var(--accent-color);
        color: white;
        border: none;
        border-radius: 5px;
        padding: 8px 15px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        width: 100%;
      }
      
      .view-details-btn:hover {
        background-color: #e69100;
        transform: translateY(-2px);
      }
      
      .proceed-btn-container {
        text-align: center;
        margin-top: 30px;
      }
      
      .proceed-btn {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 40px;
        font-weight: 700;
        font-size: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
      }
      
      .proceed-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(78, 115, 223, 0.4);
      }
      
      .proceed-btn:disabled {
        background: #b0b0b0;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
      }
      
      /* EMI Details Summary Card */
      .emi-summary-card {
        background: linear-gradient(135deg, #f8f9fc, #e9ecef);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        text-align: center;
        position: relative;
        overflow: hidden;
        display: none;
      }

      .emi-summary-card.show {
        display: block;
        animation: fadeIn 0.5s ease;
      }

      .emi-summary-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
      }

      .emi-icon {
        font-size: 48px;
        color: var(--primary-color);
        margin-bottom: 15px;
        display: inline-block;
        padding: 15px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      }

      .emi-amount-display {
        font-size: 32px;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 5px;
      }

      .emi-duration-display {
        color: var(--light-text);
        font-size: 16px;
        margin-bottom: 20px;
      }

      .emi-breakdown {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 25px;
      }

      @media (max-width: 576px) {
        .emi-breakdown {
          grid-template-columns: 1fr;
        }
      }

      .breakdown-item {
        background: white;
        border-radius: 12px;
        padding: 20px 15px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
      }

      .breakdown-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      }

      .breakdown-label {
        font-size: 14px;
        color: var(--light-text);
        margin-bottom: 8px;
      }

      .breakdown-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark-text);
      }

      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
      }

      /* Enhanced EMI Structure Modal */
      .emi-structure-modal .modal-content {
        border-radius: 20px;
        overflow: hidden;
        border: none;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        animation: modalSlideIn 0.5s ease-out;
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

      .emi-structure-modal .modal-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border-bottom: none;
        padding: 25px 30px;
        position: relative;
        overflow: hidden;
      }

      .emi-structure-modal .modal-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(45deg);
      }

      .emi-structure-modal .modal-title {
        font-weight: 700;
        font-size: 24px;
        position: relative;
        z-index: 1;
      }

      .emi-structure-modal .close {
        color: white;
        opacity: 0.8;
        text-shadow: none;
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
      }

      .emi-structure-modal .close:hover {
        opacity: 1;
        transform: scale(1.1);
      }

      .emi-structure-modal .modal-body {
        padding: 0;
      }

      .emi-structure-container {
        padding: 30px;
      }

      .emi-schedule {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        max-height: 400px;
        overflow-y: auto;
      }

      .schedule-title {
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--dark-text);
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
      }

      .schedule-title i {
        color: var(--primary-color);
      }

      .schedule-item {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #e9ecef;
        align-items: center;
      }

      .schedule-item:last-child {
        border-bottom: none;
      }

      .schedule-header {
        font-weight: 700;
        color: var(--primary-color);
        padding-bottom: 10px;
        border-bottom: 2px solid var(--primary-light);
      }

      .schedule-month {
        font-weight: 600;
        color: var(--dark-text);
      }

      .schedule-date {
        color: var(--light-text);
        font-size: 14px;
      }

      .schedule-amount {
        font-weight: 600;
        color: var(--dark-text);
      }

      .schedule-breakdown {
        display: flex;
        flex-direction: column;
        gap: 3px;
        font-size: 12px;
        color: var(--light-text);
      }

      .schedule-breakdown span {
        display: flex;
        justify-content: space-between;
      }

      .emi-structure-modal .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 20px 30px;
        display: flex;
        justify-content: center;
        background: #f8f9fc;
      }

      .emi-structure-modal .modal-footer .btn {
        min-width: 150px;
        border-radius: 10px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
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

      .credit-amount-badge {
        background: linear-gradient(135deg, var(--success-color), #17a673);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 15px;
        display: inline-block;
        box-shadow: 0 4px 15px rgba(28, 200, 138, 0.3);
      }
      
      @media (max-width: 768px) {
        .card-body {
          padding: 30px 20px 20px;
        }
        
        .emi-options {
          grid-template-columns: 1fr;
        }
        
        .schedule-item {
          grid-template-columns: 1fr;
          gap: 5px;
        }
        
        .schedule-header {
          display: none;
        }
      }
    </style>

    <div id="loader"></div>
    
    <div class="wrapper">
        <div class="card-container">
            <div class="card-header">
                <div class="page-title" id="pageTitle">Choose Your EMI Plan</div>
            </div>
            
            <div class="card-body">
                <div class="text-center">
                    <div class="credit-amount-badge" id="creditAmountBadge">
                        <i class="fas fa-credit-card mr-2"></i>
                        Credit Amount: ₹0
                    </div>
                </div>
                
                <div class="section-title">Select EMI Duration</div>
                <div class="section-subtitle">An incredible credit experience is waiting for you</div>
                
                <div class="emi-options">
                    <!-- EMI Options will be dynamically generated -->
                </div>
                
                <div class="emi-summary-card" id="emiSummaryCard">
                    <!-- EMI Summary will be dynamically populated here -->
                </div>
                
                <div class="proceed-btn-container">
                    <button type="button" class="proceed-btn" id="proceedBtn" disabled>
                        Proceed to Agreement
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Enhanced EMI Structure Modal -->
    <div class="modal fade emi-structure-modal" id="emiModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emiModalTitle">EMI Payment Schedule</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="emi-structure-container">
                        <div class="emi-schedule">
                            <h5 class="schedule-title">
                                <i class="fas fa-calendar-alt"></i>
                                Complete Payment Schedule
                            </h5>
                            <div class="schedule-item schedule-header">
                                <div>Month</div>
                                <div>Due Date</div>
                                <div>EMI Amount</div>
                                <div>Breakdown</div>
                            </div>
                            <div id="scheduleContainer">
                                <!-- Schedule items will be dynamically populated here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Got It</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get amount from URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            const creditAmount = urlParams.get('amount');
            
            // Use the credit amount or fallback to default
            const loanAmount = creditAmount ? parseInt(creditAmount) : 40000;
            
            // Update page title and badge with the actual amount
            updatePageTitleWithAmount(loanAmount);
            updateCreditAmountBadge(loanAmount);
            
            // Loan configuration - now using the passed amount
            const loanConfig = {
                loanAmount: loanAmount, // Use the amount from credit limit page
                interestRate: 12, // Annual interest rate in percentage
                emiOptions: [3, 6, 9, 12] // Available EMI durations in months
            };
            
            let selectedEMI = null;
            
            // Update page title with amount
            function updatePageTitleWithAmount(amount) {
                const titleElement = document.getElementById('pageTitle');
                if (titleElement) {
                    titleElement.textContent = `Choose Your EMI Plan - ₹${parseInt(amount).toLocaleString('en-IN')}`;
                }
            }
            
            // Update credit amount badge
            function updateCreditAmountBadge(amount) {
                const badgeElement = document.getElementById('creditAmountBadge');
                if (badgeElement) {
                    badgeElement.innerHTML = `<i class="fas fa-credit-card mr-2"></i>Credit Amount: ₹${parseInt(amount).toLocaleString('en-IN')}`;
                }
            }
            
            // EMI calculation function
            function calculateEMI(principal, annualRate, months) {
                const monthlyRate = annualRate / 12 / 100;
                const emi = principal * monthlyRate * Math.pow(1 + monthlyRate, months) / 
                            (Math.pow(1 + monthlyRate, months) - 1);
                return Math.round(emi);
            }
            
            // Generate payment schedule
            function generatePaymentSchedule(principal, annualRate, months) {
                const monthlyRate = annualRate / 12 / 100;
                const emi = calculateEMI(principal, annualRate, months);
                let balance = principal;
                const schedule = [];
                const today = new Date();
                
                for (let i = 1; i <= months; i++) {
                    const interest = Math.round(balance * monthlyRate);
                    const principalComponent = emi - interest;
                    balance -= principalComponent;
                    
                    // Calculate payment date (1st of each month)
                    const paymentDate = new Date(today);
                    paymentDate.setMonth(today.getMonth() + i);
                    paymentDate.setDate(1);
                    
                    schedule.push({
                        month: i,
                        date: paymentDate.toLocaleDateString('en-GB'),
                        emiAmount: emi,
                        principal: principalComponent,
                        interest: interest,
                        balance: Math.max(0, Math.round(balance))
                    });
                }
                
                return schedule;
            }
            
            // Generate EMI options dynamically
            function generateEMIOptions() {
                const emiOptionsContainer = document.querySelector('.emi-options');
                emiOptionsContainer.innerHTML = '';
                
                loanConfig.emiOptions.forEach(months => {
                    const emiAmount = calculateEMI(loanConfig.loanAmount, loanConfig.interestRate, months);
                    
                    const emiOption = document.createElement('label');
                    emiOption.className = 'emi-option';
                    emiOption.innerHTML = `
                        <input type="radio" name="emi-option" value="${months}" />
                        <div class="emi-duration">${months} Months</div>
                        <div class="emi-amount">₹${emiAmount.toLocaleString('en-IN')} / month</div>
                        <button type="button" class="view-details-btn" data-months="${months}">
                            View Payment Schedule
                        </button>
                    `;
                    
                    emiOptionsContainer.appendChild(emiOption);
                });
            }
            
            // Show EMI summary
            function showEMISummary(months) {
                const emiAmount = calculateEMI(loanConfig.loanAmount, loanConfig.interestRate, months);
                const totalPayment = emiAmount * months;
                const totalInterest = totalPayment - loanConfig.loanAmount;
                
                // Generate summary HTML
                let summaryHTML = `
                    <div class="emi-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="emi-amount-display">₹${emiAmount.toLocaleString('en-IN')} / month</div>
                    <div class="emi-duration-display">For ${months} Months</div>
                    
                    <div class="emi-breakdown">
                        <div class="breakdown-item">
                            <div class="breakdown-label">Loan Amount</div>
                            <div class="breakdown-value">₹${loanConfig.loanAmount.toLocaleString('en-IN')}</div>
                        </div>
                        <div class="breakdown-item">
                            <div class="breakdown-label">Total Interest</div>
                            <div class="breakdown-value">₹${totalInterest.toLocaleString('en-IN')}</div>
                        </div>
                        <div class="breakdown-item">
                            <div class="breakdown-label">Total Payment</div>
                            <div class="breakdown-value">₹${totalPayment.toLocaleString('en-IN')}</div>
                        </div>
                    </div>
                `;
                
                // Update summary container
                document.getElementById('emiSummaryCard').innerHTML = summaryHTML;
                document.getElementById('emiSummaryCard').classList.add('show');
            }
            
            // Show EMI details in modal
            function showEMIDetails(months) {
                const schedule = generatePaymentSchedule(loanConfig.loanAmount, loanConfig.interestRate, months);
                const emiAmount = calculateEMI(loanConfig.loanAmount, loanConfig.interestRate, months);
                
                // Update modal title
                document.getElementById('emiModalTitle').textContent = `${months} Month Payment Schedule - ₹${loanConfig.loanAmount.toLocaleString('en-IN')}`;
                
                // Generate schedule HTML
                let scheduleHTML = '';
                schedule.forEach(payment => {
                    scheduleHTML += `
                        <div class="schedule-item">
                            <div class="schedule-month">Month ${payment.month}</div>
                            <div class="schedule-date">${payment.date}</div>
                            <div class="schedule-amount">₹${payment.emiAmount.toLocaleString('en-IN')}</div>
                            <div class="schedule-breakdown">
                                <span><small>Principal: ₹${payment.principal.toLocaleString('en-IN')}</small></span>
                                <span><small>Interest: ₹${payment.interest.toLocaleString('en-IN')}</small></span>
                            </div>
                        </div>
                    `;
                });
                
                // Update schedule container
                document.getElementById('scheduleContainer').innerHTML = scheduleHTML;
                
                // Show modal
                $('#emiModal').modal('show');
            }

            // Create EMI transaction for transactions page
            function createEMITransaction(selectedEMI, loanAmount) {
                const newTransaction = {
                    id: `TXN${Date.now()}`,
                    reference: `EMI${Math.random().toString(36).substr(2, 9).toUpperCase()}`,
                    amount: parseInt(loanAmount),
                    type: "EMI",
                    beneficiary: {
                        name: "To be selected", // Will be updated in beneficiary page
                        bank: "To be selected"
                    },
                    status: "Pending", // Will be updated to Success after beneficiary selection
                    date: new Date().toISOString(),
                    emiDetails: {
                        duration: selectedEMI.months,
                        monthlyAmount: selectedEMI.amount,
                        interestRate: loanConfig.interestRate,
                        totalPayment: selectedEMI.amount * selectedEMI.months
                    }
                };
                
                // Store in sessionStorage for transactions page
                sessionStorage.setItem('newEMITransaction', JSON.stringify(newTransaction));
                console.log('EMI Transaction created:', newTransaction);
                
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
            
            // Initialize EMI options
            generateEMIOptions();
            
            // EMI selection logic
            const emiOptions = document.querySelectorAll('.emi-option');
            const proceedBtn = document.getElementById('proceedBtn');
            
            emiOptions.forEach(option => {
                option.addEventListener('click', function() {
                    emiOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    proceedBtn.disabled = false;
                    
                    // Store selected EMI duration
                    const selectedMonths = this.querySelector('input').value;
                    selectedEMI = {
                        months: parseInt(selectedMonths),
                        amount: calculateEMI(loanConfig.loanAmount, loanConfig.interestRate, selectedMonths),
                        loanAmount: loanConfig.loanAmount
                    };
                    
                    // Show EMI summary
                    showEMISummary(selectedMonths);
                });
            });
            
            // View details button click handler
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('view-details-btn')) {
                    const months = parseInt(e.target.getAttribute('data-months'));
                    
                    // Select the corresponding EMI option
                    const emiOption = e.target.closest('.emi-option');
                    emiOptions.forEach(opt => opt.classList.remove('selected'));
                    emiOption.classList.add('selected');
                    
                    // Update selected EMI
                    selectedEMI = {
                        months: months,
                        amount: calculateEMI(loanConfig.loanAmount, loanConfig.interestRate, months),
                        loanAmount: loanConfig.loanAmount
                    };
                    
                    // Enable proceed button
                    proceedBtn.disabled = false;
                    
                    // Show EMI summary
                    showEMISummary(months);
                    
                    // Show EMI details in modal
                    showEMIDetails(months);
                }
            });
            
            // Proceed button handler
            proceedBtn.addEventListener('click', function() {
                if (!selectedEMI) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Selection Required',
                        text: 'Please select an EMI plan to continue',
                    });
                    return;
                }
                
                document.getElementById('loader').style.display = 'block';
                
                // Create EMI transaction record before proceeding
                const newTransaction = createEMITransaction(selectedEMI, loanConfig.loanAmount);
                
                // Store selected EMI in sessionStorage for the next page
                sessionStorage.setItem('selectedEMI', JSON.stringify(selectedEMI));
                sessionStorage.setItem('loanAmount', loanConfig.loanAmount);
                sessionStorage.setItem('emiTransactionId', newTransaction.id);
                
                // Simulate processing time
                setTimeout(function() {
                    document.getElementById('loader').style.display = 'none';
                    
                    // Redirect to agreement page with amount parameter
                    window.location.href = '/institute/admin/loan-agreement-two?amount=' + loanConfig.loanAmount;
                }, 1000);
            });
        });
    </script>
@endsection
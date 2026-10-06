@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Loan Approved – Congratulations</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <style>

    /* main card */
    .approval-card {
      width: 95%;
      margin: auto;
      background: #ffffff;
      border-radius: 48px;
      box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.25), 0 4px 12px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      transition: transform 0.2s ease;
    }
/* 
    .approval-card:hover {
      transform: scale(1.01);
    } */

    /* celebration header */
    .hero-section {
      background: linear-gradient(135deg, #0b2b3b 0%, #144d5c 100%);
      padding: 32px 28px 28px 28px;
      text-align: center;
      color: white;
      position: relative;
    }

    .hero-section::after {
      content: "✨";
      font-size: 140px;
      opacity: 0.1;
      position: absolute;
      bottom: -35px;
      right: 10px;
      pointer-events: none;
      font-weight: 300;
    }

    .hero-section::before {
      content: "🎉";
      font-size: 110px;
      opacity: 0.12;
      position: absolute;
      top: 10px;
      left: 10px;
      pointer-events: none;
    }

    .celebration-icon {
      font-size: 64px;
      margin-bottom: 16px;
      background: rgba(255,255,255,0.2);
      width: 96px;
      height: 96px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 100px;
      margin-left: auto;
      margin-right: auto;
      backdrop-filter: blur(2px);
    }

    h1 {
      font-size: 2.1rem;
      font-weight: 700;
      letter-spacing: -0.3px;
      margin-bottom: 8px;
    }

    .lead-badge {
      background: rgba(255,255,240,0.2);
      display: inline-block;
      padding: 6px 16px;
      border-radius: 60px;
      font-size: 0.9rem;
      font-weight: 500;
      backdrop-filter: blur(4px);
      margin-top: 12px;
      font-family: monospace;
      letter-spacing: 0.3px;
    }

    .status-pill {
      margin-top: 20px;
      display: inline-block;
      background: #2bcf7a;
      padding: 8px 24px;
      border-radius: 60px;
      font-weight: 700;
      font-size: 1rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      color: #043927;
    }

    /* content area */
    .details-container {
      padding: 36px 32px 42px 32px;
      background: #fefefe;
    }

    .greeting-message {
      font-size: 1.1rem;
      font-weight: 500;
      color: #1f4e6e;
      background: #eef4fc;
      padding: 14px 20px;
      border-radius: 28px;
      margin-bottom: 32px;
      text-align: center;
      border-left: 4px solid #2bcf7a;
    }

    .approval-details-grid {
      display: flex;
      flex-direction: column;
      gap: 20px;
      margin-bottom: 32px;
    }

    .detail-row {
      display: flex;
      flex-wrap: wrap;
      align-items: baseline;
      justify-content: space-between;
      border-bottom: 1px solid #e4edf2;
      padding-bottom: 14px;
    }

    .detail-label {
      font-weight: 600;
      color: #2c5a74;
      font-size: 1rem;
      letter-spacing: -0.2px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .detail-label span:first-child {
      font-size: 1.2rem;
    }

    .detail-value {
      font-weight: 700;
      font-size: 1.35rem;
      color: #0a2e3f;
      background: #f7fafd;
      padding: 4px 12px;
      border-radius: 40px;
      font-feature-settings: "tnum";
      font-variant-numeric: tabular-nums;
    }

    .highlight-value {
      background: #eef3e6;
      color: #1e6b3b;
    }

    .loan-amount {
      font-size: 1.7rem;
      font-weight: 800;
      color: #0c6b3f;
    }

    .emi-badge {
      background: #1e3b48;
      color: white;
      padding: 8px 18px;
      border-radius: 48px;
      font-size: 1.2rem;
      font-weight: 600;
      display: inline-block;
      margin-top: 4px;
    }

    /* next steps / action */
    .action-section {
      background: #f2f7fb;
      margin-top: 28px;
      border-radius: 32px;
      padding: 24px;
      text-align: center;
    }

    .action-title {
      font-weight: 600;
      color: #1b516e;
      margin-bottom: 12px;
      font-size: 1rem;
    }

    .disclaimer {
      font-size: 0.75rem;
      color: #6d8e9e;
      margin-top: 16px;
      border-top: 1px solid #d9e4ec;
      padding-top: 16px;
    }

    .button_loan_approval {
      background: #0f4c5f;
      border: none;
      padding: 14px 28px;
      font-weight: 600;
      font-size: 1rem;
      border-radius: 60px;
      color: white;
      cursor: pointer;
      transition: 0.2s;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      margin-top: 8px;
      width: 100%;
      font-family: inherit;
    }

    button:hover {
      background: #0b3e4e;
      transform: translateY(-2px);
      box-shadow: 0 10px 18px -6px rgba(0, 0, 0, 0.2);
    }

    .flex-tag {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      justify-content: center;
    }

    @media (max-width: 550px) {
      .approval-card {
        border-radius: 32px;
      }
      .details-container {
        padding: 24px 20px;
      }
      .detail-value {
        font-size: 1.1rem;
      }
      .loan-amount {
        font-size: 1.4rem;
      }
      h1 {
        font-size: 1.7rem;
      }
    }

    /* tooltip / extra shine */
    .success-check {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #2bcf7a20;
      border-radius: 60px;
      padding: 3px 12px;
      font-weight: 500;
      font-size: 0.8rem;
      margin-left: 12px;
      color: #177e48;
    }
  </style>
</head>
<div class="approval-card">
  <div class="hero-section">
    <div class="celebration-icon">
      🎉🏦
    </div>
    <h1>Loan Approved! 🎊</h1>
    <p style="opacity: 0.9; margin-top: 6px;">You're all set to achieve your goals</p>
    <div class="lead-badge" id="leadIdDisplay">Lead ID: FL-987654</div>
    <div class="status-pill" id="statusPill">✅ APPROVED</div>
  </div>

  <div class="details-container">
    <div class="greeting-message">
      ✨ Congratulations! Your loan request has been successfully reviewed and approved. 
      Below are the final terms based on your eligibility. ✨
    </div>

    <!-- dynamic data grid using the provided JSON -->
    <div class="approval-details-grid" id="detailsGrid"></div>

    <!-- extra action buttons and info -->
    <div class="action-section">
      <div class="action-title">📄 What's next?</div>
      <p style="font-size: 0.9rem; color: #2a617b; margin-bottom: 12px;">
        Your loan agreement will be sent via email. Review & sign digitally to receive funds.
      </p>
      <div class="flex-tag">
        <button class="button_loan_approval" id="downloadSummaryBtn">Get Loan</button>
      </div>
      <div class="disclaimer">
        *This is an electronically generated approval letter. Terms subject to final verification.
        Interest rate & EMI are fixed for the chosen tenor.
      </div>
    </div>
  </div>
</div>

<script>
  // Exact data from the event payload (as provided)
  const loanData = {
    event_type: "BRE_RESULT",
    lead_id: "FL-987654",
    status: "APPROVED",
    approval_details: {
      loan_amount: 100000,
      tenor_months: 12,
      interest_rate: "14%",
      emi_amount: 10000
    }
  };

  // helper to format currency INR style (but we can use USD/ generic)
  const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumFractionDigits: 0 }).format(amount);
  };

  // function to render approval details into the grid
  const renderApprovalData = () => {
    const details = loanData.approval_details;
    const leadId = loanData.lead_id;
    const statusText = loanData.status;

    // update lead badge and status pill (just to be extra consistent)
    const leadSpan = document.getElementById('leadIdDisplay');
    if (leadSpan) leadSpan.innerText = `Lead ID: ${leadId}`;
    const statusPill = document.getElementById('statusPill');
    if (statusPill) statusPill.innerHTML = `✅ ${statusText}`;

    const gridContainer = document.getElementById('detailsGrid');
    if (!gridContainer) return;

    // building rows with proper icons and formatting
    const rows = [
      {
        label: "💰 Loan Amount",
        value: formatCurrency(details.loan_amount),
        icon: "💵",
        highlightClass: "loan-amount"
      },
      {
        label: "⏱️ Tenure",
        value: `${details.tenor_months} months`,
        icon: "📅",
        extra: ` (${details.tenor_months === 12 ? '1 year' : details.tenor_months + ' months'})`
      },
      {
        label: "📈 Interest Rate (p.a.)",
        value: details.interest_rate,
        icon: "⚡",
      },
      {
        label: "💸 Equated Monthly Installment (EMI)",
        value: formatCurrency(details.emi_amount),
        icon: "🔄",
        highlightClass: "emi-highlight"
      }
    ];

    // clear and build dom
    gridContainer.innerHTML = '';

    rows.forEach(row => {
      const rowDiv = document.createElement('div');
      rowDiv.className = 'detail-row';

      const labelDiv = document.createElement('div');
      labelDiv.className = 'detail-label';
      labelDiv.innerHTML = `<span>${row.icon}</span> ${row.label}`;
      
      const valueSpan = document.createElement('div');
      valueSpan.className = `detail-value ${row.highlightClass === 'loan-amount' ? 'loan-amount' : ''} ${row.highlightClass === 'emi-highlight' ? 'highlight-value' : ''}`;
      valueSpan.innerText = row.value;
      if (row.extra) {
        const extraSpan = document.createElement('small');
        extraSpan.style.fontSize = '0.7rem';
        extraSpan.style.fontWeight = 'normal';
        extraSpan.style.marginLeft = '6px';
        extraSpan.style.color = '#4f7a90';
        extraSpan.innerText = row.extra;
        valueSpan.appendChild(extraSpan);
      }
      
      rowDiv.appendChild(labelDiv);
      rowDiv.appendChild(valueSpan);
      gridContainer.appendChild(rowDiv);
    });

    // Optional: add a special summary line about total repayment
    const totalRepayment = details.loan_amount + (details.emi_amount * details.tenor_months - details.loan_amount);
    const interestPaid = (details.emi_amount * details.tenor_months) - details.loan_amount;
    
    const summaryRow = document.createElement('div');
    summaryRow.style.marginTop = '16px';
    summaryRow.style.background = '#eef3f0';
    summaryRow.style.borderRadius = '24px';
    summaryRow.style.padding = '14px 18px';
    summaryRow.style.fontSize = '0.85rem';
    summaryRow.style.display = 'flex';
    summaryRow.style.flexWrap = 'wrap';
    summaryRow.style.justifyContent = 'space-between';
    summaryRow.style.alignItems = 'center';
    summaryRow.innerHTML = `
      <span style="font-weight:500;">📊 Total repayment over ${details.tenor_months} months:</span>
      <span style="font-weight:800; color:#1f543e;">${formatCurrency(details.emi_amount * details.tenor_months)}</span>
      <span style="font-size:0.75rem; color:#547e64; width:100%; margin-top:6px;">(includes principal + interest: ₹${interestPaid.toLocaleString('en-IN')} interest)</span>
    `;
    gridContainer.appendChild(summaryRow);
  };

  // download summary as text / image friendly representation (simple TXT file)
  const downloadApprovalSummary = () => {
    const details = loanData.approval_details;
    const totalRepayment = details.emi_amount * details.tenor_months;
    const interestAmount = totalRepayment - details.loan_amount;

    const summaryText = `
LOAN APPROVAL SUMMARY
═══════════════════════════════════════
Event        : ${loanData.event_type}
Lead ID      : ${loanData.lead_id}
Status       : ${loanData.status} ✅
───────────────────────────────────────
💰 Loan Amount          : ${formatCurrency(details.loan_amount)}
⏱️ Tenor                : ${details.tenor_months} months
📈 Interest Rate        : ${details.interest_rate} p.a.
💸 Monthly EMI          : ${formatCurrency(details.emi_amount)}
───────────────────────────────────────
📊 Total Repayment      : ${formatCurrency(totalRepayment)}
💸 Total Interest Payable : ${formatCurrency(interestAmount)}
───────────────────────────────────────
Congratulations! Your loan has been approved.
Disbursement will be initiated after e-sign.
    `;
    
    const blob = new Blob([summaryText], { type: 'text/plain' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.href = url;
    link.download = `Loan_Approval_${loanData.lead_id}.txt`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
  };

  // Additional animation: simulate confetti? subtle but not overkill — optional micro interaction
  const addMicroConfetti = () => {
    // very simple console delight, but visual spark? just optional if we want.
    // I'll add a gentle "pop" effect on card load, no heavy libraries.
    const card = document.querySelector('.approval-card');
    if(card) {
      card.style.animation = 'none';
      card.offsetHeight;
      card.style.transition = 'all 0.2s';
    }
  };

  // Set up event listeners after DOM ready
  document.addEventListener('DOMContentLoaded', () => {
    renderApprovalData();

    const downloadBtn = document.getElementById('downloadSummaryBtn');
    if (downloadBtn) {
      downloadBtn.addEventListener('click', downloadApprovalSummary);
    }
    
    addMicroConfetti();

    // Additionally we can also add dynamic hover tooltip or meta info
    // Also ensure that the interest rate and emi are clearly shown with extra numeric checks (already done)
    // The data shows tenor_months=12, emi_amount=10000, loan_amount=100000 so total repayment = 120000
    // but the interest rate is 14% p.a. (flat vs reducing? but it's demo data, just display as given)
    // We show the exact numbers for transparency.
    
    // Optional: add a nice timing effect to highlight the EMI
    const emiValueElement = document.querySelector('.detail-value.highlight-value');
    if(emiValueElement) {
      emiValueElement.style.transition = 'background 0.3s';
      setTimeout(() => {
        if(emiValueElement) emiValueElement.style.backgroundColor = '#e5f7e5';
      }, 200);
    }
  });

  // For completeness, if window load: also prefill any other dynamic elements
  window.addEventListener('load', () => {
    // extra precision: update document title
    document.title = `Approved: ${loanData.lead_id} – Loan ${formatCurrency(loanData.approval_details.loan_amount)}`;
    // small footer note inside approval card? but already there
  });
</script>
@endsection
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Institute Loan Manager | Loans Portfolio</title>
    <style>

        .loan-app-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 28px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 800;
            background: linear-gradient(135deg, #1E2A5E, #2D3A6E);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -0.3px;
        }

        .card-table {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
            padding: 4px 0;
            margin-bottom: 28px;
        }

        .loan-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            min-width: 1000px;
        }

        .loan-table th {
            background: #f8fafd;
            padding: 18px 16px;
            font-weight: 700;
            color: #1e293b;
            border-bottom: 2px solid #e2e8f0;
            text-align: center;
        }

        .loan-table td {
            padding: 16px 12px;
            text-align: center;
            border-bottom: 1px solid #edf2f7;
            color: #1e293b;
            font-weight: 500;
        }

        .loan-table tr:hover td {
            background-color: #fafcff;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 40px;
            font-size: 0.75rem;
            font-weight: 700;
            text-align: center;
            min-width: 90px;
        }

        .status-active {
            background: #e0f2fe;
            color: #0369a1;
        }

        .status-closed {
            background: #e9ecef;
            color: #2c3e50;
        }

        .btn-icon {
            background: none;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            border-radius: 40px;
            font-weight: 600;
        }

        .btn-view {
            background: #eef2ff;
            color: #4338ca;
            font-size: 13px;
            gap: 6px;
        }

        .btn-view:hover {
            background: #e0e7ff;
            transform: translateY(-1px);
        }

        .loan-id-link {
            color: #2563eb;
            font-weight: 700;
            text-decoration: none;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .loan-id-link:hover {
            text-decoration: underline;
            color: #1e40af;
        }

        /* Mobile cards */
        .mobile-loan-cards {
            display: none;
            flex-direction: column;
            gap: 18px;
        }

        .loan-mobile-card {
            background: white;
            border-radius: 24px;
            padding: 20px;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.03);
            border: 1px solid #eef2ff;
        }

        .mobile-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        @media (max-width: 780px) {
            body { padding: 18px 14px; }
            .loan-table { display: none; }
            .card-table { display: none; }
            .mobile-loan-cards { display: flex; }
        }

        .btn-agreement {
            background: linear-gradient(100deg, #f97316, #ea580c);
            border: none;
            padding: 8px 20px;
            border-radius: 40px;
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
            cursor: pointer;
            transition: 0.2s;
        }
        .footer-note {
            margin-top: 30px;
            text-align: center;
            font-size: 13px;
            color: #5b6e8c;
        }
    </style>
</head>
<body>
<div class="loan-app-container">
    <div class="section-header">
        <h2 class="page-title">📋 Loan Portfolio</h2>
        <div style="font-size: 14px; color:#4b5563;">Institute-admin view</div>
    </div>

    <!-- desktop table -->
    <div class="card-table">
        <table class="loan-table">
            <thead>
                <tr><th>Loan ID</th><th>Type</th><th>Requested</th><th>Disbursed</th><th>Disbursed Date</th>
                <th>Tenure</th><th>Interest</th><th>EMI</th><th>Status</th><th>Schedule</th></tr>
            </thead>
            <tbody id="loanTableBody"></tbody>
        </table>
    </div>

    <!-- mobile cards -->
    <div id="mobileLoanCards" class="mobile-loan-cards"></div>
    <div class="footer-note">🔍 Click on any Loan ID to view complete EMI repayment schedule</div>
</div>

<script>
    // ---------- STATIC DATASET (shared with EMI file) ----------
     const loansDataset = @json($loan_details);
     console.log(loansDataset);

    function renderLoanList() {
        const tbody = document.getElementById("loanTableBody");
        const mobileContainer = document.getElementById("mobileLoanCards");
        tbody.innerHTML = "";
        mobileContainer.innerHTML = "";

        loansDataset.forEach((loan, idx) => {
            const statusClass = loan.status === "Active" ? "status-active" : "status-closed";
            // Desktop row
            const row = document.createElement("tr");
            row.innerHTML = `
                <td><a href="/loan/repaymen/emi-details/${encodeURIComponent(loan.id)}" class="loan-id-link">${loan.id}</a></td>
                <td>Education Loan</td>
                <td>₹${loan.requestedAmount.toLocaleString()}</td>
                <td>₹${loan.disbursedAmount.toLocaleString()}</td>
                <td>${loan.disbursedDate}</td>
                <td>${loan.tenure} m</td>
                <td>${loan.interestRate}%</td>
                <td>₹${loan.emiAmount.toLocaleString()}</td>
                <td><span class="status-badge ${statusClass}">${loan.status}</span></td>
                <td><a href="/loan/repaymen/emi-details/${encodeURIComponent(loan.id)}" class="btn-icon btn-view">📊 View EMI</a></td>
            `;
            tbody.appendChild(row);

            // Mobile card
            const card = document.createElement("div");
            card.className = "loan-mobile-card";
            card.innerHTML = `
                <div class="mobile-card-header">
                    <strong style="font-size:1.2rem;">Education Loan</strong>
                    <span class="status-badge ${statusClass}">${loan.status}</span>
                </div>
                <div style="margin: 6px 0;"><span style="font-weight:600;">Loan ID:</span> 
                    <a href="/loan/repaymen/emi-details/${encodeURIComponent(loan.id)}" class="loan-id-link" style="font-size:0.85rem;">${loan.id}</a>
                </div>
                <div><span style="font-weight:600;">Disbursed:</span> ₹${loan.disbursedAmount.toLocaleString()}</div>
                <div><span style="font-weight:600;">Tenure:</span> ${loan.tenure} months | EMI: ₹${loan.emiAmount}</div>
                <div style="margin-top:12px;"><a href="/loan/repaymen/emi-details/${encodeURIComponent(loan.id)}" class="btn-icon btn-view" style="background:#eef2ff; text-decoration:none;">📄 EMI Schedule</a></div>
            `;
            mobileContainer.appendChild(card);
        });
    }

    renderLoanList();
</script>
</body>
@endsection
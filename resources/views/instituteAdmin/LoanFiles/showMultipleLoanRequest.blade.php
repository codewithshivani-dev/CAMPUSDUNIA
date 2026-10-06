@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Loan Journey Keeper · Resume Loan Requests</title>
    <style>
        /* Your existing styles remain the same */
        .loan-container {
            max-width: 1500px;
            margin: 0 auto;
        }

        .hero {
            text-align: center;
            margin-bottom: 2rem;
        }

        .hero h1 {
            font-size: 2.6rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1A3A3F, #224abe);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            letter-spacing: -0.02em;
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .hero h1::before {
            content: "🏦";
            font-size: 2.2rem;
            background: none;
            color: #224abe;
        }

        .hero p {
            color: #000;
            font-weight: 500;
            background: rgba(255,255,245,0.7);
            backdrop-filter: blur(4px);
            display: inline-block;
            padding: 0.4rem 1.5rem;
            border-radius: 60px;
            font-size: 1rem;
        }

        .action-bar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            background: rgba(255,255,248,0.85);
            backdrop-filter: blur(8px);
            padding: 0.8rem 1.8rem;
            border-radius: 56px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05), inset 0 1px 0 rgba(255,255,255,0.8);
        }

        .new-loan-btn {
            background: #224abe;
            border: none;
            padding: 0.75rem 1.8rem;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.95rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .new-loan-btn:hover {
            background: #1a3a8a;
            transform: scale(1.02);
        }

        .filter-group {
            display: flex;
            gap: 10px;
            background: white;
            padding: 0.25rem 0.8rem;
            border-radius: 48px;
            box-shadow: inset 0 1px 2px #0001, 0 1px 3px #fff9;
        }

        .filter-btn {
            background: transparent;
            border: none;
            padding: 0.45rem 1.2rem;
            border-radius: 40px;
            font-weight: 500;
            font-size: 0.85rem;
            cursor: pointer;
            color: #224abe;
            transition: 0.2s;
        }

        .filter-btn.active {
            background: #224abe;
            color: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .stats-badge {
            background: #e4ede8;
            padding: 0.4rem 1.2rem;
            border-radius: 40px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #000;
        }

        .loans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 1.8rem;
            margin-top: 0.5rem;
        }

        .loan-card {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 15px 30px -12px rgba(0, 0, 0, 0.15);
            transition: all 0.25s ease;
            border: 1px solid rgba(210, 230, 220, 0.7);
            display: flex;
            flex-direction: column;
        }

        .loan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 38px -14px rgba(0, 0, 0, 0.25);
            background: white;
        }

        .card-header {
            padding: 1.2rem 1.5rem 0.8rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            border-bottom: 2px solid #e6f0ec;
            flex-wrap: wrap;
            gap: 8px;
        }

        .loan-id-badge {
            font-family: monospace;
            font-weight: 700;
            background: #eef3f0;
            padding: 0.25rem 0.8rem;
            border-radius: 40px;
            font-size: 0.75rem;
            color: #224abe;
            letter-spacing: 0.3px;
        }

        .status-chip {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.25rem 0.9rem;
            border-radius: 30px;
            text-transform: uppercase;
            background: #eef2f0;
        }

        .status-approved { background: #c8f0e4; color: #0a5c48; }
        .status-pending { background: #fff0cf; color: #b46f0b; }
        .status-rejected { background: #ffe3de; color: #bc4e2c; }
        .status-disbursed { background: #d4f0fc; color: #0f6b8c; }

        .card-body {
            padding: 1.2rem 1.5rem;
            flex: 1;
        }

        .amount-tenure {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            flex-wrap: wrap;
            margin-bottom: 1rem;
            background: #F9FCFA;
            padding: 0.6rem 1rem;
            border-radius: 28px;
        }

        .loan-amount {
            font-size: 1.6rem;
            font-weight: 800;
            color: #224abe;
        }

        .loan-amount small {
            font-size: 0.8rem;
            font-weight: 500;
            color: #5e8576;
        }

        .tenure-badge {
            background: #e2ebe6;
            padding: 0.3rem 0.9rem;
            border-radius: 32px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #224abe;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 0.7rem 0;
            font-size: 0.85rem;
            border-bottom: 1px dashed #e0ede6;
            padding-bottom: 0.5rem;
        }

        .info-label {
            font-weight: 600;
            color: #000;
        }

        .info-value {
            font-weight: 500;
            color: #000;
            text-align: right;
            word-break: break-word;
            max-width: 60%;
        }

        .course-highlight {
            background: #eef3f0;
            border-radius: 20px;
            padding: 0.5rem 0.9rem;
            margin-top: 0.8rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: #224abe;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-footer {
            padding: 1rem 1.5rem 1.3rem;
            display: flex;
            gap: 12px;
            border-top: 1px solid #eef3ef;
            background: #fefefc;
        }

        .resume-btn {
            flex: 2;
            background: #224abe;
            color: white;
            border: none;
            padding: 0.6rem 0;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .resume-btn:hover {
            background: #2b49a3;
        }

        .delete-btn {
            flex: 1;
            background: #fff0ee;
            border: 1px solid #ffcfc7;
            color: #bc4e2c;
            border-radius: 40px;
            font-weight: 600;
            cursor: pointer;
        }

        .empty-state {
            text-align: center;
            grid-column: 1 / -1;
            padding: 3rem;
            background: rgba(255,255,245,0.7);
            border-radius: 64px;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            visibility: hidden;
            opacity: 0;
            transition: 0.2s;
        }

        .modal-overlay.active {
            visibility: visible;
            opacity: 1;
        }

        .modal-card {
            background: white;
            max-width: 550px;
            width: 90%;
            border-radius: 48px;
            padding: 1.8rem 2rem;
            box-shadow: 0 30px 40px rgba(0,0,0,0.3);
        }

        .modal-card h3 {
            font-size: 1.6rem;
            margin-bottom: 1rem;
            color: #1a5e4e;
        }

        .modal-card input, .modal-card select, .modal-card textarea {
            width: 100%;
            padding: 0.8rem 1rem;
            margin: 0.6rem 0;
            border: 1px solid #cbdcd2;
            border-radius: 32px;
            font-family: inherit;
            font-size: 0.9rem;
            background: #fefefe;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            margin-top: 1.2rem;
        }

        .save-modal-btn {
            background: #224abe;
            color: white;
        }
        .cancel-modal-btn {
            background: #eef2f0;
            color: #2c5a4e;
        }
        .modal-actions button {
            flex: 1;
            padding: 0.7rem;
            border-radius: 40px;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }

        .toast-msg {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #1f2e2a;
            color: white;
            padding: 0.6rem 1.4rem;
            border-radius: 60px;
            font-size: 0.85rem;
            z-index: 1100;
            opacity: 0;
            transition: 0.2s;
            pointer-events: none;
        }

        @media (max-width: 680px) {
            body { padding: 1rem; }
            .hero h1 { font-size: 1.9rem; }
            .action-bar { flex-direction: column; align-items: stretch; border-radius: 32px; }
            .loan-amount { font-size: 1.3rem; }
        }
    </style>
</head>

<div class="loan-container">
    <div class="hero">
        <h1>Loan Journey Keeper</h1>
        <p>📌 resume any loan request — track amount, tenure, status & course</p>
    </div>

    <div class="action-bar">
        <button class="new-loan-btn" id="openModalBtn">➕ New Loan Request</button>
        <div class="filter-group">
            <button data-filter="all" class="filter-btn active">All Loans</button>
            <button data-filter="pending" class="filter-btn">⏳ Pending</button>
            <button data-filter="approved" class="filter-btn">✅ Approved</button>
            <button data-filter="rejected" class="filter-btn">❌ Rejected</button>
            <button data-filter="disbursed" class="filter-btn">💰 Disbursed</button>
        </div>
        <div class="stats-badge" id="statsCounter">🏦 0 requests</div>
    </div>

    <div class="loans-grid" id="loansGrid">
        <!-- Loans will be rendered here -->
    </div>
</div>

<!-- Modal -->
<div id="modalOverlay" class="modal-overlay">
    <div class="modal-card">
        <h3>✍️ Register Loan Request</h3>
        <input type="text" id="loanIdInput" placeholder="Loan Request ID (e.g., L2024-0012)" autocomplete="off">
        <input type="number" id="loanAmountInput" placeholder="Loan Amount (₹ / USD)" step="any">
        <input type="text" id="tenureInput" placeholder="Tenure (e.g., 12 months / 3 years)">
        <select id="loanStatusSelect">
            <option value="pending">⏳ Pending</option>
            <option value="approved">✅ Approved</option>
            <option value="rejected">❌ Rejected</option>
            <option value="disbursed">💰 Disbursed</option>
        </select>
        <input type="date" id="requestDateInput" placeholder="Request Date (YYYY-MM-DD)">
        <input type="text" id="courseInput" placeholder="Course / Purpose (e.g., MBA, Engineering, Business Loan)">
        <div class="modal-actions">
            <button class="cancel-modal-btn" id="closeModalBtn">Cancel</button>
            <button class="save-modal-btn" id="confirmSaveBtn">Save Loan Journey</button>
        </div>
    </div>
</div>

<div id="toastMsg" class="toast-msg">✨ Loan journey updated</div>

<script>
    // ============================================================
    // IMPROVED LOAN REQUEST RENDERER WITH WORKING FILTERS
    // UPDATED: Date format changed to "DD MMM YYYY"
    // ============================================================

    let loans = [];
    let currentFilter = "all";

    // Helper: Format date from YYYY-MM-DD to DD MMM YYYY (e.g., 15 Apr 2025)
    function formatDate(dateString) {
        if (!dateString) return '—';
        
        // Handle different date formats
        let date;
        if (dateString.includes('-')) {
            date = new Date(dateString);
        } else if (dateString.includes('/')) {
            let parts = dateString.split('/');
            date = new Date(parts[2], parts[1]-1, parts[0]);
        } else {
            date = new Date(dateString);
        }
        
        // Check if date is valid
        if (isNaN(date.getTime())) return dateString;
        
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const day = date.getDate().toString().padStart(2, '0');
        const month = months[date.getMonth()];
        const year = date.getFullYear();
        
        return `${day} ${month} ${year}`;
    }

    // Helper: Format datetime for resume message
    function formatDateTimeForMessage(dateString) {
        if (!dateString) return '—';
        
        let date;
        if (dateString.includes('-')) {
            date = new Date(dateString);
        } else if (dateString.includes('/')) {
            let parts = dateString.split('/');
            date = new Date(parts[2], parts[1]-1, parts[0]);
        } else {
            date = new Date(dateString);
        }
        
        if (isNaN(date.getTime())) return dateString;
        
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const day = date.getDate().toString().padStart(2, '0');
        const month = months[date.getMonth()];
        const year = date.getFullYear();
        
        return `${day} ${month} ${year}`;
    }

    // Function to map Laravel Collection data to UI format
    function mapLaravelToUILoan(laravelLoan) {
        const data = laravelLoan.attributes || laravelLoan;
        
        // Parse date to store as YYYY-MM-DD internally
        let requestDate = null;
        const rawDate = data.created_at || laravelLoan.created_at;
        if (rawDate) {
            const d = new Date(rawDate);
            if (!isNaN(d.getTime())) {
                requestDate = d.toISOString().split('T')[0];
            }
        }
        
        return {
            id: data.id || laravelLoan.id,
            loanRequestId: data.loan_request_id || laravelLoan.loan_request_id || null,
            loanAmount: data.loan_amount ? parseFloat(data.loan_amount) : (laravelLoan.loan_amount ? parseFloat(laravelLoan.loan_amount) : 0),
            tenure: data.scheme_tenure ? data.scheme_tenure + ' months' : (laravelLoan.scheme_tenure ? laravelLoan.scheme_tenure + ' months' : null),
            loanStatus: (data.loan_status || laravelLoan.loan_status || 'pending').toLowerCase(),
            requestDate: requestDate,
            course: data.institute_course || laravelLoan.institute_course || null,
            timestamp: data.created_at || laravelLoan.created_at || new Date().toISOString()
        };
    }

    // Load data from Laravel backend
    function loadFromBackend() {
        try {
            const backendData = @json($loanRequests ?? []);
            
            if (backendData && backendData.length > 0) {
                loans = backendData.map(item => mapLaravelToUILoan(item));
                saveToStorage();
            } else {
                // Fallback to localStorage
                const stored = localStorage.getItem('loan_journey_requests');
                if (stored) {
                    try {
                        loans = JSON.parse(stored);
                        if (!Array.isArray(loans)) loans = [];
                    } catch(e) { 
                        loans = []; 
                    }
                }
            }
            
            // Demo data if still empty
            if (!loans.length) {
                loans = [
                    {
                        id: 1,
                        loanRequestId: "LR69D3807E54863",
                        loanAmount: 75000,
                        tenure: "12 months",
                        loanStatus: "pending",
                        requestDate: "2026-04-05",
                        course: "Computer Science",
                        timestamp: "2026-04-05T10:30:00"
                    },
                    {
                        id: 2,
                        loanRequestId: "LR69D3807E54864",
                        loanAmount: 250000,
                        tenure: "24 months",
                        loanStatus: "approved",
                        requestDate: "2026-04-04",
                        course: "MBA Finance",
                        timestamp: "2026-04-04T14:20:00"
                    },
                    {
                        id: 3,
                        loanRequestId: "LR69D3807E54865",
                        loanAmount: 500000,
                        tenure: "36 months",
                        loanStatus: "disbursed",
                        requestDate: "2026-04-03",
                        course: "Data Science",
                        timestamp: "2026-04-03T09:15:00"
                    },
                    {
                        id: 4,
                        loanRequestId: "LR69D3807E54866",
                        loanAmount: 125000,
                        tenure: "18 months",
                        loanStatus: "rejected",
                        requestDate: "2026-04-02",
                        course: "Engineering",
                        timestamp: "2026-04-02T16:45:00"
                    },
                    {
                        id: 5,
                        loanRequestId: "LR69D3807E54867",
                        loanAmount: 100000,
                        tenure: "12 months",
                        loanStatus: "pending",
                        requestDate: "2026-04-06",
                        course: "CSE",
                        timestamp: "2026-04-06T15:14:30"
                    }
                ];
                saveToStorage();
            }
        } catch (error) {
            console.error("Error loading data:", error);
            loans = [];
        }
    }

    function saveToStorage() {
        localStorage.setItem('loan_journey_requests', JSON.stringify(loans));
    }

    function getFilteredLoans() {
        if (currentFilter === "all") return [...loans];
        return loans.filter(l => l.loanStatus === currentFilter);
    }

    function formatCurrency(amount) {
        if (amount === undefined || amount === null) return "—";
        return new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumFractionDigits: 0 }).format(amount);
    }

    function getStatusClass(status) {
        switch(status) {
            case 'approved': return 'status-approved';
            case 'pending': return 'status-pending';
            case 'rejected': return 'status-rejected';
            case 'disbursed': return 'status-disbursed';
            default: return '';
        }
    }

    function getStatusLabel(status) {
        const map = { 
            approved: '✅ Approved', 
            pending: '⏳ Pending', 
            rejected: '❌ Rejected', 
            disbursed: '💰 Disbursed' 
        };
        return map[status] || status;
    }

    function renderGrid() {
        const grid = document.getElementById('loansGrid');
        const filtered = getFilteredLoans();
        const statsSpan = document.getElementById('statsCounter');
        
        // Update stats counter
        statsSpan.innerText = `🏦 ${filtered.length} ${filtered.length === 1 ? 'loan' : 'loans'}`;

        // Clear grid
        grid.innerHTML = '';
        
        if (filtered.length === 0) {
            // Show empty state
            const emptyDiv = document.createElement('div');
            emptyDiv.className = 'empty-state';
            emptyDiv.innerHTML = `
                <div style="font-size: 3rem;">📋</div>
                <h3>No loan requests yet</h3>
                <p>Click "New Loan Request" to add a loan journey.</p>
            `;
            grid.appendChild(emptyDiv);
            return;
        }
        
        // Render each loan card
        filtered.forEach(loan => {
            const card = document.createElement('div');
            card.className = 'loan-card';
            card.dataset.id = loan.id;

            const statusClass = getStatusClass(loan.loanStatus);
            const formattedAmount = formatCurrency(loan.loanAmount);
            // Format date for display: DD MMM YYYY
            const displayDate = formatDate(loan.requestDate);
            const displayTenure = loan.tenure || '—';
            const displayCourse = loan.course || 'General Education';
            const displayLoanId = loan.loanRequestId || 'N/A';

            // Determine action button based on status
            let actionButton = '';
            if (loan.loanStatus === 'approved') {
                actionButton = `<a href="/loan/journey/waiting-for-approval/${displayLoanId}" class="resume-btn" data-id="${displayLoanId}">▶ Resume Loan Journey</a>`;
            } else if (loan.loanStatus === 'under_review') {
                actionButton = `<a href="/loan/journey/waiting-for-approval/${displayLoanId}" class="resume-btn" data-id="${displayLoanId}">▶ View Loan Details</a>`;
            } else if (loan.loanStatus === 'pending') {
                actionButton = `<a href="/loan/journey/get-bank-details/${displayLoanId}" class="resume-btn" data-id="${displayLoanId}">▶ Resume Loan Journey</a>`;
            } else if (loan.loanStatus === 'rejected') {
                actionButton = `<a href="/loan/journey/waiting-for-approval/${displayLoanId}" class="resume-btn" data-id="${displayLoanId}">▶ Resume Loan Journey</a>`;
            } else {
                actionButton = `<a href="/loan/journey/show-multiple-loan-requests" class="resume-btn" data-id="${displayLoanId}">▶ Resume Loan Journey</a>`;
            }

            card.innerHTML = `
                <div class="card-header">
                    <span class="loan-id-badge">📄 ${escapeHtml(displayLoanId)}</span>
                    <span class="status-chip ${statusClass}">${getStatusLabel(loan.loanStatus)}</span>
                </div>

                <div class="card-body">
                    <div class="amount-tenure">
                        <span class="loan-amount">${formattedAmount}</span>
                        <span class="tenure-badge">📆 ${escapeHtml(displayTenure)}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Request Date</span>
                        <span class="info-value">${escapeHtml(displayDate)}</span>
                    </div>

                    <div class="course-highlight">
                        <span>🎓 Course / Purpose:</span>
                        <strong>${escapeHtml(displayCourse)}</strong>
                    </div>
                </div>

                <div class="card-footer">
                    ${actionButton}
                    <button class="delete-btn" data-id="${loan.id}" data-loan-id="${displayLoanId}">🗑 Delete</button>
                </div>
            `;

            grid.appendChild(card);
        });

        // Attach event listeners for delete buttons
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.removeEventListener('click', handleDelete);
            btn.addEventListener('click', handleDelete);
        });
        
        // Attach event listeners for resume buttons
        document.querySelectorAll('.resume-btn').forEach(btn => {
            btn.removeEventListener('click', handleResume);
            btn.addEventListener('click', handleResume);
        });
    }
    
    function handleDelete(e) {
        e.stopPropagation();
        const loanId = parseInt(this.getAttribute('data-id'));
        const loanRequestId = this.getAttribute('data-loan-id');
        deleteLoanById(loanId, loanRequestId);
    }
    
    function handleResume(e) {
        e.stopPropagation();
        const loanId = this.getAttribute('data-id');
        if (loanId) {
            resumeLoanById(loanId);
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    function resumeLoanById(id) {
        const loan = loans.find(l => l.loanRequestId == id || l.id == id);
        if (!loan) {
            showToast("⚠️ Loan record not found", 1500);
            return;
        }
        
        // Format date for message in DD MMM YYYY format
        const formattedDate = formatDateTimeForMessage(loan.requestDate);
        
        const resumeMsg = `📌 RESUMING LOAN JOURNEY\n━━━━━━━━━━━━━━━━━━━━━\n🆔 Loan ID: ${loan.loanRequestId}\n💰 Amount: ${formatCurrency(loan.loanAmount)}\n📆 Tenure: ${loan.tenure}\n📊 Status: ${loan.loanStatus?.toUpperCase()}\n📅 Request Date: ${formattedDate}\n🎓 Course: ${loan.course}\n━━━━━━━━━━━━━━━━━━━━━`;
        
        console.log("[RESUME_LOAN]", resumeMsg);
        showToast(`🚀 Resumed: ${loan.loanRequestId}`, 2000);
        
        if (navigator.clipboard) {
            navigator.clipboard.writeText(resumeMsg).catch(()=>{});
            showToast(`✂️ Loan details copied to clipboard`, 1600);
        }
        
        const resumeEvent = new CustomEvent('loanResumed', { detail: { loan } });
        window.dispatchEvent(resumeEvent);
    }

    function deleteLoanById(id, loanRequestId) {
        if (confirm(`Are you sure you want to delete loan request ${loanRequestId || id}?`)) {
            loans = loans.filter(l => l.id != id);
            saveToStorage();
            renderGrid();
            showToast("🗑 Loan request removed", 1200);
        }
    }

    function addNewLoan(loanRequestId, loanAmount, tenure, loanStatus, requestDate, course) {
        if (!loanRequestId.trim()) loanRequestId = "LOAN-" + Math.floor(Math.random()*10000);
        const amountNum = parseFloat(loanAmount);
        const finalAmount = isNaN(amountNum) ? 0 : amountNum;
        if (!tenure.trim()) tenure = "Not specified";
        if (!requestDate.trim()) {
            const today = new Date().toISOString().slice(0,10);
            requestDate = today;
        }
        if (!course.trim()) course = "General Education Loan";
        
        const newId = Date.now();
        const newLoan = {
            id: newId,
            loanRequestId: loanRequestId.trim(),
            loanAmount: finalAmount,
            tenure: tenure.trim(),
            loanStatus: loanStatus,
            requestDate: requestDate.trim(),
            course: course.trim(),
            timestamp: new Date().toISOString()
        };
        loans.unshift(newLoan);
        saveToStorage();
        renderGrid();
        showToast(`✅ New loan request saved: ${newLoan.loanRequestId}`, 1800);
    }

    function showToast(msg, duration = 2000) {
        const toast = document.getElementById('toastMsg');
        toast.innerText = msg;
        toast.style.opacity = '1';
        setTimeout(() => {
            toast.style.opacity = '0';
        }, duration);
    }

    // Filter functionality
    function initFilters() {
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                // Update active state
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Update current filter
                currentFilter = this.getAttribute('data-filter');
                
                // Re-render grid with new filter
                renderGrid();
                
                // Show feedback
                showToast(`Showing ${currentFilter === 'all' ? 'all loans' : currentFilter + ' loans'}`, 1000);
            });
        });
    }

    // Modal functionality
    function initModal() {
        const modal = document.getElementById('modalOverlay');
        const openBtn = document.getElementById('openModalBtn');
        const closeBtn = document.getElementById('closeModalBtn');
        const saveBtn = document.getElementById('confirmSaveBtn');

        const loanIdInput = document.getElementById('loanIdInput');
        const loanAmountInput = document.getElementById('loanAmountInput');
        const tenureInput = document.getElementById('tenureInput');
        const loanStatusSelect = document.getElementById('loanStatusSelect');
        const requestDateInput = document.getElementById('requestDateInput');
        const courseInput = document.getElementById('courseInput');

        function openModalReset() {
            loanIdInput.value = '';
            loanAmountInput.value = '';
            tenureInput.value = '';
            loanStatusSelect.value = 'pending';
            // Set today's date in YYYY-MM-DD format for date input
            const today = new Date().toISOString().slice(0,10);
            requestDateInput.value = today;
            courseInput.value = '';
            modal.classList.add('active');
        }

        function closeModal() {
            modal.classList.remove('active');
        }

        function saveFromModal() {
            let loanId = loanIdInput.value.trim();
            if (!loanId) {
                showToast("Please enter Loan Request ID", 1200);
                return;
            }
            const amountRaw = loanAmountInput.value.trim();
            if (!amountRaw) {
                showToast("Please enter Loan Amount", 1200);
                return;
            }
            const tenure = tenureInput.value.trim();
            if (!tenure) {
                showToast("Please enter Tenure (e.g., 12 months)", 1200);
                return;
            }
            const status = loanStatusSelect.value;
            let requestDate = requestDateInput.value.trim();
            if (!requestDate) {
                requestDate = new Date().toISOString().slice(0,10);
            }
            const course = courseInput.value.trim();
            if (!course) {
                showToast("Please specify Course / Purpose", 1200);
                return;
            }
            addNewLoan(loanId, amountRaw, tenure, status, requestDate, course);
            closeModal();
        }

        openBtn.addEventListener('click', openModalReset);
        closeBtn.addEventListener('click', closeModal);
        saveBtn.addEventListener('click', saveFromModal);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('active')) closeModal();
        });
    }

    // Initialize everything
    function init() {
        loadFromBackend();
        renderGrid();
        initFilters();
        initModal();
    }

    // Start the application
    init();
</script>

@endsection
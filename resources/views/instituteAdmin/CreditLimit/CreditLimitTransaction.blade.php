@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Credit Limit Transactions</title>
  
  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>

  <link rel="shortcut icon" href="/images/rr-logo.png" type="image/x-icon" />

  <style>
    #loader {
      position: fixed;
      inset: 0;
      z-index: 9999;
      background: rgba(255,255,255,0.9) url(/images/preloader.gif) center no-repeat;
      display: none;
    }

    .page-wrapper {
      padding: 0 20px;
      overflow-x: hidden;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: linear-gradient(135deg, #4e73df, #224abe);
      color: #fff;
      padding: 20px 25px;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .header img { max-height: 60px; }

    .header i {
      font-size: 26px;
      cursor: pointer;
      transition: 0.3s;
    }
    .header i:hover { transform: scale(1.1); color: #cbd6ff; }

    .loan-card {
      background: #fff;
      border-radius: 14px;
      padding: 20px 25px;
      margin-top: 30px;
      box-shadow: 0 8px 20px rgba(78,115,223,0.15);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .loan-title {
      font-size: 22px;
      font-weight: 700;
      color: #4e73df;
    }

    .filter-btn {
      background: #4e73df;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 10px 18px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .filter-btn:hover {
      background: #2e59d9;
      transform: translateY(-2px);
    }

    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-top: 25px;
    }

    .stat-card {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      text-align: center;
      transition: transform 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-5px);
    }

    .stat-icon {
      font-size: 32px;
      margin-bottom: 10px;
    }

    .stat-amount {
      font-size: 24px;
      font-weight: 700;
      color: #4e73df;
      margin-bottom: 5px;
    }

    .stat-label {
      font-size: 14px;
      color: #666;
      font-weight: 500;
    }

    .table-container {
      background: #fff;
      border-radius: 14px;
      margin-top: 25px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      overflow-y: auto;
      max-height: 500px;
    }

    .table-container::-webkit-scrollbar { width: 8px; }
    .table-container::-webkit-scrollbar-thumb { background: #4e73df; border-radius: 10px; }

    .table thead {
      background: #4e73df;
      color: #fff;
      font-weight: 600;
      position: sticky;
      top: 0;
      z-index: 2;
    }

    .table tbody tr { transition: all 0.3s ease; }
    .table tbody tr:hover { background: #f1f5ff; transform: scale(1.01); }

    .table td, .table th {
      vertical-align: middle !important;
      font-size: 14px;
      padding: 15px;
    }

    .btn-status {
      font-size: 13px;
      font-weight: 700;
      border-radius: 25px;
      padding: 4px 12px;
    }

    .btn-success { background: #1cc88a; border: none; }
    .btn-warning { background: #f6c23e; border: none; color: #000; }
    .btn-danger { background: #e74a3b; border: none; }
    .btn-info { background: #36b9cc; border: none; }

    .transaction-type {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
    }

    .type-emi { background: #e8f4fd; color: #2e59d9; }
    .type-paylater { background: #fff3cd; color: #856404; }

    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-top: 25px;
      font-weight: 600;
      color: #4e73df;
      background: #fff;
      border: 2px solid #4e73df;
      border-radius: 8px;
      padding: 10px 16px;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .back-btn:hover {
      background: #4e73df;
      color: #fff;
      transform: translateY(-2px);
    }

    .modal-header {
      background: #4e73df;
      color: #fff;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }
    .modal-content {
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(78,115,223,0.2);
    }

    .no-transactions {
      text-align: center;
      padding: 40px;
      color: #666;
    }

    .no-transactions i {
      font-size: 48px;
      color: #ddd;
      margin-bottom: 15px;
    }

    .new-transaction-badge {
      background: #ff4444;
      color: white;
      border-radius: 50%;
      width: 8px;
      height: 8px;
      display: inline-block;
      margin-left: 5px;
      animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.2); opacity: 0.7; }
      100% { transform: scale(1); opacity: 1; }
    }

    @media (max-width: 768px) {
      .header { flex-direction: column; text-align: center; gap: 10px; }
      .loan-title { font-size: 18px; }
      .table td, .table th { font-size: 12px; }
      .stats-container { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

  <div id="loader"></div>

  <div class="page-wrapper">
    <!-- Header -->
    <div class="header">
      <img src="/images/entritt-logo.png" alt="Entritt Logo">
      <i id="backBtn" class="fa-solid fa-right-from-bracket fa-rotate-180"></i>
    </div>

    <!-- Title -->
    <div class="loan-card">
      <div class="loan-title">
        Credit Limit Transactions 
        <span id="newTransactionsBadge" class="new-transaction-badge" style="display: none;"></span>
      </div>
      <div>
        <button class="filter-btn" data-toggle="modal" data-target="#filterModal">
          <i class="fa-solid fa-filter"></i> Filter
        </button>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-container">
      <div class="stat-card">
        <div class="stat-icon text-primary">
          <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-amount" id="totalTransactions">₹0</div>
        <div class="stat-label">Total Amount</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon text-success">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-amount" id="successCount">0</div>
        <div class="stat-label">Successful</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon text-warning">
          <i class="fas fa-clock"></i>
        </div>
        <div class="stat-amount" id="pendingCount">0</div>
        <div class="stat-label">Pending</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon text-danger">
          <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-amount" id="failedCount">0</div>
        <div class="stat-label">Failed</div>
      </div>
    </div>

    <!-- Table -->
    <div class="table-container mt-4">
      <table class="table table-hover mb-0" id="transactionTable">
        <thead>
          <tr>
            <th>Transaction ID</th>
            <th>Amount</th>
            <th>Type</th>
            <th>Beneficiary</th>
            <th>Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody id="transactionsBody">
          <!-- Transactions will be dynamically populated -->
        </tbody>
      </table>
      <div id="noTransactions" class="no-transactions" style="display: none;">
        <i class="fas fa-receipt"></i>
        <h4>No Transactions Found</h4>
        <p>Your credit limit transactions will appear here.</p>
      </div>
    </div>

    <!-- Back Button -->
    <div class="text-center">
      <button class="back-btn" id="goBack"><i class="fa fa-arrow-left"></i> Back to Credit Limit</button>
    </div>
  </div>

  <!-- Filter Modal -->
  <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="filterModalLabel"><i class="fa-solid fa-filter"></i> Filter Transactions</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="filterForm">
            <div class="form-group">
              <label for="transactionType">Transaction Type</label>
              <select class="form-control" id="transactionType">
                <option value="">All Types</option>
                <option value="EMI">EMI Plan</option>
                <option value="Pay Later">Pay Later</option>
              </select>
            </div>
            <div class="form-group">
              <label for="status">Status</label>
              <select class="form-control" id="status">
                <option value="">All Status</option>
                <option value="Success">Success</option>
                <option value="Pending">Pending</option>
                <option value="Failed">Failed</option>
              </select>
            </div>
            <div class="form-group">
              <label for="dateRange">Date Range</label>
              <select class="form-control" id="dateRange">
                <option value="">All Time</option>
                <option value="today">Today</option>
                <option value="week">Last 7 Days</option>
                <option value="month">Last 30 Days</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" id="resetFilter" class="btn btn-outline-secondary">Reset</button>
          <button type="button" id="applyFilter" class="btn btn-primary" style="background:#4e73df;border:none;">Apply Filter</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const back = document.getElementById("backBtn");
      const goBack = document.getElementById("goBack");
      const loader = document.getElementById("loader");
      const applyFilter = document.getElementById("applyFilter");
      const resetFilter = document.getElementById("resetFilter");
      const transactionsBody = document.getElementById("transactionsBody");
      const noTransactions = document.getElementById("noTransactions");
      const newTransactionsBadge = document.getElementById('newTransactionsBadge');

      let transactions = [];
      let lastTransactionCount = 0;

      // Initialize the page
      function initializePage() {
        loadTransactions();
        displayTransactions(transactions);
        updateStatistics(transactions);
        lastTransactionCount = transactions.length;
        
        // Check for new transactions immediately
        checkForNewTransactions();
      }

      // Load transactions from localStorage
      function loadTransactions() {
        transactions = JSON.parse(localStorage.getItem('creditLimitTransactions') || '[]');
        console.log('Loaded transactions:', transactions.length);
        
        // Sort transactions by date (newest first)
        transactions.sort((a, b) => new Date(b.date) - new Date(a.date));
      }

      // Display transactions in the table
      function displayTransactions(transactionsToShow) {
        transactionsBody.innerHTML = '';
        
        if (transactionsToShow.length === 0) {
          noTransactions.style.display = 'block';
          return;
        }

        noTransactions.style.display = 'none';

        transactionsToShow.forEach((transaction, index) => {
          const row = document.createElement('tr');
          row.setAttribute('data-type', transaction.type);
          row.setAttribute('data-status', transaction.status);
          row.setAttribute('data-date', transaction.date);

          const statusClass = getStatusClass(transaction.status);
          const typeClass = transaction.type === 'EMI' ? 'type-emi' : 'type-paylater';
          
          // Highlight new transactions (first 2 for demo)
          const isNew = index < Math.min(2, transactionsToShow.length - lastTransactionCount);
          const rowClass = isNew ? 'new-transaction' : '';

          row.innerHTML = `
            <td>
              <strong>${transaction.id}</strong><br>
              <small class="text-muted">Ref: ${transaction.reference}</small>
              ${isNew ? '<span class="new-transaction-badge"></span>' : ''}
            </td>
            <td><i class="fa fa-inr"></i> <strong>${formatAmount(transaction.amount)}</strong></td>
            <td><span class="transaction-type ${typeClass}">${transaction.type}</span></td>
            <td>
              <strong>${transaction.beneficiary.name}</strong><br>
              <small class="text-muted">${transaction.beneficiary.bank}</small>
            </td>
            <td>${formatDate(transaction.date)}</td>
            <td><button class="btn btn-status ${statusClass}">${transaction.status}</button></td>
          `;

          if (isNew) {
            row.style.animation = 'highlightRow 2s ease-in-out';
          }

          transactionsBody.appendChild(row);
        });
      }

      // Update statistics
      function updateStatistics(transactions) {
        const totalAmount = transactions.reduce((sum, t) => sum + t.amount, 0);
        const successCount = transactions.filter(t => t.status === 'Success').length;
        const pendingCount = transactions.filter(t => t.status === 'Pending').length;
        const failedCount = transactions.filter(t => t.status === 'Failed').length;

        document.getElementById('totalTransactions').textContent = `₹${formatAmount(totalAmount)}`;
        document.getElementById('successCount').textContent = successCount;
        document.getElementById('pendingCount').textContent = pendingCount;
        document.getElementById('failedCount').textContent = failedCount;
      }

      // Get status class for styling
      function getStatusClass(status) {
        switch(status) {
          case 'Success': return 'btn-success';
          case 'Pending': return 'btn-warning';
          case 'Failed': return 'btn-danger';
          default: return 'btn-info';
        }
      }

      // Format amount with commas
      function formatAmount(amount) {
        return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      }

      // Format date
      function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-IN', {
          day: '2-digit',
          month: 'short',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        });
      }

      // Check for new transactions
      function checkForNewTransactions() {
        const newEMITransaction = sessionStorage.getItem('newEMITransaction');
        const newPayLaterTransaction = sessionStorage.getItem('newPayLaterTransaction');
        let hasNewTransactions = false;

        if (newEMITransaction) {
          console.log('Found new EMI transaction');
          const transaction = JSON.parse(newEMITransaction);
          addNewTransaction(transaction);
          sessionStorage.removeItem('newEMITransaction');
          hasNewTransactions = true;
        }

        if (newPayLaterTransaction) {
          console.log('Found new Pay Later transaction');
          const transaction = JSON.parse(newPayLaterTransaction);
          addNewTransaction(transaction);
          sessionStorage.removeItem('newPayLaterTransaction');
          hasNewTransactions = true;
        }

        if (hasNewTransactions) {
          showNewTransactionNotification();
        }

        // Also check localStorage for any updates
        const currentTransactions = JSON.parse(localStorage.getItem('creditLimitTransactions') || '[]');
        if (currentTransactions.length > transactions.length) {
          console.log('Found new transactions in localStorage');
          loadTransactions();
          displayTransactions(transactions);
          updateStatistics(transactions);
          showNewTransactionNotification();
        }
      }

      // Add new transaction
      function addNewTransaction(transaction) {
        // Check if transaction already exists
        const exists = transactions.some(t => t.id === transaction.id);
        if (!exists) {
          transactions.unshift(transaction); // Add to beginning
          localStorage.setItem('creditLimitTransactions', JSON.stringify(transactions));
          displayTransactions(transactions);
          updateStatistics(transactions);
          console.log('New transaction added:', transaction);
        }
      }

      // Show new transaction notification
      function showNewTransactionNotification() {
        newTransactionsBadge.style.display = 'inline-block';
        
        // Show toast notification
        if (typeof showToast === 'function') {
          showToast('New transaction added!', 'success');
        }
        
        // Hide badge after 5 seconds
        setTimeout(() => {
          newTransactionsBadge.style.display = 'none';
        }, 5000);
      }

      // Filter transactions
      function filterTransactions() {
        const selectedType = document.getElementById('transactionType').value;
        const selectedStatus = document.getElementById('status').value;
        const selectedDateRange = document.getElementById('dateRange').value;

        const filtered = transactions.filter(transaction => {
          const typeMatch = !selectedType || transaction.type === selectedType;
          const statusMatch = !selectedStatus || transaction.status === selectedStatus;
          
          let dateMatch = true;
          if (selectedDateRange) {
            const transactionDate = new Date(transaction.date);
            const today = new Date();
            
            switch(selectedDateRange) {
              case 'today':
                dateMatch = transactionDate.toDateString() === today.toDateString();
                break;
              case 'week':
                const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                dateMatch = transactionDate >= weekAgo;
                break;
              case 'month':
                const monthAgo = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);
                dateMatch = transactionDate >= monthAgo;
                break;
            }
          }

          return typeMatch && statusMatch && dateMatch;
        });

        displayTransactions(filtered);
        updateStatistics(filtered);
      }

      // Reset filters
      function resetFilters() {
        document.getElementById('transactionType').value = '';
        document.getElementById('status').value = '';
        document.getElementById('dateRange').value = '';
        displayTransactions(transactions);
        updateStatistics(transactions);
      }

      // Redirect function
      function redirect() {
        loader.style.display = "block";
        setTimeout(() => { 
          window.location.href = "{{ url('/institute/admin/credit-limit') }}"; 
        }, 1000);
      }

      // Event listeners
      back.addEventListener("click", redirect);
      goBack.addEventListener("click", redirect);
      applyFilter.addEventListener("click", filterTransactions);
      resetFilter.addEventListener("click", resetFilters);

      // Initialize the page
      initializePage();
      
      // Check for new transactions every 2 seconds
      setInterval(checkForNewTransactions, 2000);

      // Add CSS for highlighting new transactions
      const style = document.createElement('style');
      style.textContent = `
        @keyframes highlightRow {
          0% { background-color: rgba(76, 175, 80, 0.3); }
          100% { background-color: transparent; }
        }
        .new-transaction {
          animation: highlightRow 2s ease-in-out;
        }
      `;
      document.head.appendChild(style);
    });
  </script>
</body>
</html>
@endsection
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Advance Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4e73df;
            --primary-dark: #2e59d9;
            --primary-light: #7a93e8;
            --secondary: #858796;
            --success: #1cc88a;
            --danger: #e74a3b;
            --warning: #f6c23e;
            --info: #36b9cc;
            --light: #f8f9fc;
            --dark: #5a5c69;
            --white: #ffffff;
            --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f8f9fc 0%, #e3e6f0 100%);
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;

        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        header {
            text-align: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: var(--white);
            border-radius: 15px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }
        
        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--info) 100%);
        }
        
        h1 {
            color: var(--primary);
            margin-bottom: 0.5rem;
            font-size: 2.5rem;
            font-weight: 700;
        }
        
        .subtitle {
            color: var(--secondary);
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .dashboard {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .card {
            background: var(--white);
            border-radius: 15px;
            box-shadow: var(--shadow);
            padding: 1.5rem;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
        }
        
        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #eaecf4;
        }
        
        .card-header i {
            background: var(--primary);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 1.2rem;
        }
        
        .card-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .stats-card {
            text-align: center;
            padding: 1.5rem;
        }
        
        .stats-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary);
            margin: 10px 0;
        }
        
        .stats-label {
            color: var(--secondary);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .stats-icon {
            font-size: 2rem;
            color: var(--primary-light);
            margin-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 1.2rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark);
        }
        
        input, select, textarea {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #d1d3e2;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        input:focus, select:focus, textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -0.5rem;
        }
        
        .form-col {
            flex: 1;
            padding: 0 0.5rem;
            min-width: 200px;
        }
        
        .radio-group {
            display: flex;
            gap: 1.5rem;
            margin-top: 0.5rem;
        }
        
        .radio-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .radio-option input {
            width: auto;
        }
        
        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(78, 115, 223, 0.3);
        }
        
        .btn-success {
            background: var(--success);
            color: white;
        }
        
        .btn-success:hover {
            background: #17a673;
        }
        
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background: #d52a1a;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .transaction-history {
            margin-top: 2rem;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eaecf4;
        }
        
        th {
            background-color: var(--light);
            font-weight: 700;
            color: var(--primary);
        }
        
        tr:hover {
            background-color: #f8f9fc;
        }
        
        .credit {
            color: var(--success);
            font-weight: 700;
        }
        
        .debit {
            color: var(--danger);
            font-weight: 700;
        }
        
        .status-pending {
            background: rgba(246, 194, 62, 0.2);
            color: var(--warning);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .status-approved {
            background: rgba(28, 200, 138, 0.2);
            color: var(--success);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .status-rejected {
            background: rgba(231, 74, 59, 0.2);
            color: var(--danger);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--secondary);
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--primary-light);
        }
        
        .rupee-icon {
            font-family: Arial, sans-serif;
            font-weight: bold;
        }
        
        @media (max-width: 992px) {
            .dashboard {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            .form-col {
                flex: 100%;
                margin-bottom: 1rem;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
            
            .radio-group {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Salary Advance Management</h1>
            <p class="subtitle">Track and manage employee salary advances with our comprehensive system</p>
        </header>
        
        <div class="dashboard">
            <div class="card stats-card">
                <div class="stats-icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div class="stats-value"><span class="rupee-icon">₹</span>1,24,500</div>
                <div class="stats-label">Total Advances Given</div>
            </div>
            
            <div class="card stats-card">
                <div class="stats-icon">
                    <i class="fas fa-money-check-alt"></i>
                </div>
                <div class="stats-value"><span class="rupee-icon">₹</span>82,750</div>
                <div class="stats-label">Total Recovered</div>
            </div>
            
            <div class="card stats-card">
                <div class="stats-icon">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <div class="stats-value"><span class="rupee-icon">₹</span>41,750</div>
                <div class="stats-label">Pending Recovery</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <i class="fas fa-plus-circle"></i>
                <h2 class="card-title">New Salary Advance Transaction</h2>
            </div>
            <form id="salaryAdvanceForm">
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="employeeId"><i class="fas fa-id-card"></i> Employee ID</label>
                            <input type="text" id="employeeId" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="employeeName"><i class="fas fa-user"></i> Employee Name</label>
                            <input type="text" id="employeeName" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="department"><i class="fas fa-building"></i> Department</label>
                            <select id="department" required>
                                <option value="">Select Department</option>
                                <option value="hr">Human Resources</option>
                                <option value="finance">Finance</option>
                                <option value="it">IT</option>
                                <option value="marketing">Marketing</option>
                                <option value="operations">Operations</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="transactionDate"><i class="fas fa-calendar-alt"></i> Transaction Date</label>
                            <input type="date" id="transactionDate" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-exchange-alt"></i> Transaction Type</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="credit" name="transactionType" value="credit" checked>
                            <label for="credit">Credit (Advance Given)</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="debit" name="transactionType" value="debit">
                            <label for="debit">Debit (Advance Recovery)</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="amount"><i class="fas fa-rupee-sign"></i> Amount</label>
                            <input type="number" id="amount" min="0" step="0.01" placeholder="Enter amount in rupees" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="installments"><i class="fas fa-calendar-check"></i> Installments (for recovery)</label>
                            <input type="number" id="installments" min="1" max="12" value="1">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description"><i class="fas fa-sticky-note"></i> Description</label>
                    <textarea id="description" rows="3" placeholder="Add any additional notes..."></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="reset" class="btn btn-danger"><i class="fas fa-times"></i> Clear</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Submit Transaction</button>
                </div>
            </form>
        </div>
        
        <div class="card transaction-history">
            <div class="card-header">
                <i class="fas fa-history"></i>
                <h2 class="card-title">Transaction History</h2>
            </div>
            <table id="transactionTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Employee</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="transactionBody">
                    <!-- Transactions will be added here dynamically -->
                </tbody>
            </table>
            <div id="emptyState" class="empty-state">
                <i class="fas fa-file-invoice-dollar"></i>
                <h3>No transactions recorded yet</h3>
                <p>Submit your first transaction using the form above</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('salaryAdvanceForm');
            const transactionBody = document.getElementById('transactionBody');
            const emptyState = document.getElementById('emptyState');
            const transactionTable = document.getElementById('transactionTable');
            
            // Set default date to today
            document.getElementById('transactionDate').valueAsDate = new Date();
            
            // Load transactions from localStorage
            loadTransactions();
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form values
                const employeeId = document.getElementById('employeeId').value;
                const employeeName = document.getElementById('employeeName').value;
                const department = document.getElementById('department').value;
                const transactionDate = document.getElementById('transactionDate').value;
                const transactionType = document.querySelector('input[name="transactionType"]:checked').value;
                const amount = document.getElementById('amount').value;
                const installments = document.getElementById('installments').value;
                const description = document.getElementById('description').value;
                
                // Create transaction object
                const transaction = {
                    id: Date.now(), // Simple ID based on timestamp
                    employeeId,
                    employeeName,
                    department,
                    transactionDate,
                    transactionType,
                    amount: parseFloat(amount),
                    installments: parseInt(installments),
                    description,
                    status: 'pending'
                };
                
                // Save transaction
                saveTransaction(transaction);
                
                // Reset form
                form.reset();
                document.g0etElementById('transactionDate').valueAsDate = new Date();
                
                // Reload transactions
                loadTransactions();
                
                // Show success message
                alert('Transaction submitted successfully!');
            });
            
            function saveTransaction(transaction) {
                let transactions = JSON.parse(localStorage.getItem('salaryAdvanceTransactions')) || [];
                transactions.push(transaction);
                localStorage.setItem('salaryAdvanceTransactions', JSON.stringify(transactions));
            }
            
            function loadTransactions() {
                const transactions = JSON.parse(localStorage.getItem('salaryAdvanceTransactions')) || [];
                
                if (transactions.length === 0) {
                    emptyState.style.display = 'block';
                    transactionTable.style.display = 'none';
                    return;
                }
                
                emptyState.style.display = 'none';
                transactionTable.style.display = 'table';
                
                // Clear existing rows
                transactionBody.innerHTML = '';
                
                // Add transactions to table
                transactions.forEach(transaction => {
                    const row = document.createElement('tr');
                    
                    // Format date
                    const date = new Date(transaction.transactionDate);
                    const formattedDate = date.toLocaleDateString();
                    
                    // Determine status class
                    let statusClass = '';
                    if (transaction.status === 'pending') statusClass = 'status-pending';
                    if (transaction.status === 'approved') statusClass = 'status-approved';
                    if (transaction.status === 'rejected') statusClass = 'status-rejected';
                    
                    // Format amount with rupee symbol
                    const formattedAmount = `₹${transaction.amount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                    
                    row.innerHTML = `
                        <td>${formattedDate}</td>
                        <td>${transaction.employeeName} (${transaction.employeeId})</td>
                        <td class="${transaction.transactionType}">${transaction.transactionType === 'credit' ? 'Credit' : 'Debit'}</td>
                        <td>${formattedAmount}</td>
                        <td>${transaction.description}</td>
                        <td><span class="${statusClass}">${transaction.status}</span></td>
                    `;
                    
                    transactionBody.appendChild(row);
                });
            }
        });
    </script>
</body>
</html>
@endsection
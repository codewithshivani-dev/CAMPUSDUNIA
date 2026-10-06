@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Advance Requests</title>
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
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
        
        .requests-table {
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
        
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
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
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
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
        
        .filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        
        .filter-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .filter-group label {
            font-weight: 600;
            color: var(--dark);
        }
        
        .filter-group select {
            padding: 0.5rem;
            border: 1px solid #d1d3e2;
            border-radius: 8px;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .filters {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <header>
            <h1>Salary Advance Requests</h1>
            <p class="subtitle">Review and manage employee salary advance requests</p>
        </header>
        
        <div class="dashboard">
            <div class="card stats-card">
                <div class="stats-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-value">12</div>
                <div class="stats-label">Pending Requests</div>
            </div>
            
            <div class="card stats-card">
                <div class="stats-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-value">24</div>
                <div class="stats-label">Approved This Month</div>
            </div>
            
            <div class="card stats-card">
                <div class="stats-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stats-value">3</div>
                <div class="stats-label">Rejected This Month</div>
            </div>
            
            <div class="card stats-card">
                <div class="stats-icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div class="stats-value"><span class="rupee-icon">₹</span>1,85,000</div>
                <div class="stats-label">Total Requested Amount</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list-alt"></i>
                <h2 class="card-title">All Requests</h2>
            </div>
            
            <div class="filters">
                <div class="filter-group">
                    <label for="statusFilter">Status:</label>
                    <select id="statusFilter">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="departmentFilter">Department:</label>
                    <select id="departmentFilter">
                        <option value="all">All Departments</option>
                        <option value="hr">Human Resources</option>
                        <option value="finance">Finance</option>
                        <option value="it">IT</option>
                        <option value="marketing">Marketing</option>
                        <option value="operations">Operations</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="dateFilter">Date Range:</label>
                    <select id="dateFilter">
                        <option value="all">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                </div>
            </div>
            
            <div class="requests-table">
                <table id="requestsTable">
                    <thead>
                        <tr>
                            <th>Request Date</th>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Amount</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="requestsBody">
                        <!-- Requests will be added here dynamically -->
                    </tbody>
                </table>
                <div id="emptyState" class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No requests found</h3>
                    <p>There are no salary advance requests matching your criteria</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const requestsBody = document.getElementById('requestsBody');
            const emptyState = document.getElementById('emptyState');
            const requestsTable = document.getElementById('requestsTable');
            const statusFilter = document.getElementById('statusFilter');
            const departmentFilter = document.getElementById('departmentFilter');
            const dateFilter = document.getElementById('dateFilter');
            
            // Load requests from localStorage
            loadRequests();
            
            // Add event listeners to filters
            statusFilter.addEventListener('change', loadRequests);
            departmentFilter.addEventListener('change', loadRequests);
            dateFilter.addEventListener('change', loadRequests);
            
            function loadRequests() {
                // In a real application, you would fetch this data from an API
                // For demo purposes, we'll use sample data
                const requests = getSampleRequests();
                
                // Apply filters
                const filteredRequests = requests.filter(request => {
                    // Status filter
                    if (statusFilter.value !== 'all' && request.status !== statusFilter.value) {
                        return false;
                    }
                    
                    // Department filter
                    if (departmentFilter.value !== 'all' && request.department !== departmentFilter.value) {
                        return false;
                    }
                    
                    // Date filter (simplified implementation)
                    if (dateFilter.value !== 'all') {
                        const requestDate = new Date(request.requestDate);
                        const today = new Date();
                        
                        if (dateFilter.value === 'today') {
                            if (requestDate.toDateString() !== today.toDateString()) {
                                return false;
                            }
                        } else if (dateFilter.value === 'week') {
                            const weekAgo = new Date(today);
                            weekAgo.setDate(today.getDate() - 7);
                            if (requestDate < weekAgo) {
                                return false;
                            }
                        } else if (dateFilter.value === 'month') {
                            const monthAgo = new Date(today);
                            monthAgo.setMonth(today.getMonth() - 1);
                            if (requestDate < monthAgo) {
                                return false;
                            }
                        }
                    }
                    
                    return true;
                });
                
                if (filteredRequests.length === 0) {
                    emptyState.style.display = 'block';
                    requestsTable.style.display = 'none';
                    return;
                }
                
                emptyState.style.display = 'none';
                requestsTable.style.display = 'table';
                
                // Clear existing rows
                requestsBody.innerHTML = '';
                
                // Add requests to table
                filteredRequests.forEach(request => {
                    const row = document.createElement('tr');
                    
                    // Format date
                    const date = new Date(request.requestDate);
                    const formattedDate = date.toLocaleDateString();
                    
                    // Determine status class
                    let statusClass = '';
                    if (request.status === 'pending') statusClass = 'status-pending';
                    if (request.status === 'approved') statusClass = 'status-approved';
                    if (request.status === 'rejected') statusClass = 'status-rejected';
                    
                    // Format amount with rupee symbol
                    const formattedAmount = `₹${request.amount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                    
                    // Get department name
                    const departmentNames = {
                        'hr': 'Human Resources',
                        'finance': 'Finance',
                        'it': 'IT',
                        'marketing': 'Marketing',
                        'operations': 'Operations'
                    };
                    
                    row.innerHTML = `
                        <td>${formattedDate}</td>
                        <td>${request.employeeName} (${request.employeeId})</td>
                        <td>${departmentNames[request.department]}</td>
                        <td>${formattedAmount}</td>
                        <td>${request.reason}</td>
                        <td><span class="${statusClass}">${request.status.charAt(0).toUpperCase() + request.status.slice(1)}</span></td>
                        <td>
                            <div class="action-buttons">
                                ${request.status === 'pending' ? `
                                    <button class="btn btn-success btn-sm" onclick="approveRequest(${request.id})">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="rejectRequest(${request.id})">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                ` : ''}
                                <button class="btn btn-primary btn-sm" onclick="viewDetails(${request.id})">
                                    <i class="fas fa-eye"></i> Details
                                </button>
                            </div>
                        </td>
                    `;
                    
                    requestsBody.appendChild(row);
                });
            }
            
            function getSampleRequests() {
                // In a real application, this would come from your backend
                return [
                    {
                        id: 1,
                        employeeId: 'EMP001',
                        employeeName: 'Rajesh Kumar',
                        department: 'finance',
                        requestDate: '2023-06-15',
                        amount: 25000,
                        reason: 'Medical emergency for family member',
                        status: 'pending'
                    },
                    {
                        id: 2,
                        employeeId: 'EMP045',
                        employeeName: 'Priya Sharma',
                        department: 'hr',
                        requestDate: '2023-06-10',
                        amount: 15000,
                        reason: 'Home renovation',
                        status: 'approved'
                    },
                    {
                        id: 3,
                        employeeId: 'EMP023',
                        employeeName: 'Amit Patel',
                        department: 'it',
                        requestDate: '2023-06-05',
                        amount: 30000,
                        reason: 'Child education fees',
                        status: 'rejected'
                    },
                    {
                        id: 4,
                        employeeId: 'EMP067',
                        employeeName: 'Sneha Reddy',
                        department: 'marketing',
                        requestDate: '2023-06-12',
                        amount: 20000,
                        reason: 'Vehicle repair',
                        status: 'pending'
                    },
                    {
                        id: 5,
                        employeeId: 'EMP034',
                        employeeName: 'Vikram Singh',
                        department: 'operations',
                        requestDate: '2023-06-08',
                        amount: 18000,
                        reason: 'Wedding expenses',
                        status: 'approved'
                    }
                ];
            }
            
            window.approveRequest = function(id) {
                if (confirm('Are you sure you want to approve this salary advance request?')) {
                    // In a real application, you would send this to your backend
                    alert(`Request #${id} approved successfully!`);
                    loadRequests(); // Reload to reflect the change
                }
            };
            
            window.rejectRequest = function(id) {
                if (confirm('Are you sure you want to reject this salary advance request?')) {
                    // In a real application, you would send this to your backend
                    alert(`Request #${id} rejected.`);
                    loadRequests(); // Reload to reflect the change
                }
            };
            
            window.viewDetails = function(id) {
                // In a real application, you would navigate to a details page or show a modal
                alert(`Showing details for request #${id}`);
            };
        });
    </script>
</body>
</html>
@endsection
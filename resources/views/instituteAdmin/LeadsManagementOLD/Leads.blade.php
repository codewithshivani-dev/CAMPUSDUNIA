@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@section('content')
    <style>
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif; 
            background: #f8fafc;
            color: #334155;
            line-height: 1.5;
        }

        .leads-container {
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Tabs Navigation */
        .tabs-nav {
            display: flex;
            gap: 4px;
            background: white;
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 12px 24px;
            background: none;
            border: none;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            font-size: 14px;
            white-space: nowrap;
        }

        .tab-btn:hover {
            background: #f1f5f9;
            color: #475569;
        }

        .tab-btn.active {
            background: #3b82f6;
            color: white;
        }

        .tab-btn i {
            font-size: 16px;
        }

        /* Tab Content */
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #1e293b;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 14px;
            margin-top: 4px;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            font-size: 14px;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: white;
            color: #3b82f6;
            border: 1px solid #3b82f6;
        }

        .btn-secondary:hover {
            background: #f8fafc;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .stat-icon.total { background: #3b82f6; }
        .stat-icon.hot { background: #ef4444; }
        .stat-icon.converted { background: #10b981; }
        .stat-icon.followup { background: #f59e0b; }

        .stat-value {
            font-size: 24px;
            font-weight: 600;
            color: #1e293b;
        }

        .stat-label {
            color: #64748b;
            font-size: 14px;
        }

        /* Search and Filters */
        .search-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            align-items: center;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 300px;
        }

        .search-box input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .filter-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 10px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            background: white;
            color: #475569;
            min-width: 150px;
        }

        /* Table */
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 32px;
        }

        .table-header {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .table-title {
            font-weight: 600;
            color: #1e293b;
            font-size: 16px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead {
            background: #f8fafc;
        }

        .table th {
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
            white-space: nowrap;
        }

        .table td {
            padding: 16px 20px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }

        .table tbody tr {
            transition: background 0.2s;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .status-hot { background: #fee2e2; color: #dc2626; }
        .status-warm { background: #fef3c7; color: #d97706; }
        .status-cold { background: #dbeafe; color: #2563eb; }
        .status-converted { background: #dcfce7; color: #16a34a; }

        /* Action Buttons in Table */
        .action-buttons-small {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: none;
            background: #f1f5f9;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .action-btn:hover {
            background: #3b82f6;
            color: white;
        }

        /* Form Sections */
        .form-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .form-section h3 {
            color: #3b82f6;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #475569;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-control {
            width: 100%;
            padding: 7px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        /* Option Selectors */
        .option-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .option-card {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
        }

        .option-card:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
        }

        .option-card.selected {
            border-color: #3b82f6;
            background: rgba(59, 130, 246, 0.05);
        }

        .option-card i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #3b82f6;
        }

        .option-card h4 {
            margin: 8px 0 4px;
            color: #1e293b;
        }

        .option-card p {
            color: #64748b;
            font-size: 12px;
        }

        /* Entrance Test Options */
        .test-option-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .test-option {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .test-option:hover {
            border-color: #3b82f6;
        }

        .test-option.selected {
            border-color: #3b82f6;
            background: rgba(59, 130, 246, 0.05);
        }

        /* Counselor Cards */
        .counselor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .counselor-card {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
        }

        .counselor-card:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
        }

        .counselor-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #3b82f6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 15px;
            font-weight: bold;
        }

        /* Follow-up Cards */
        .followup-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .followup-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            border-left: 5px solid #3b82f6;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .followup-card.hot {
            border-left-color: #ef4444;
        }

        .followup-card.warm {
            border-left-color: #f59e0b;
        }

        .followup-card.cold {
            border-left-color: #64748b;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: #64748b;
        }

        .empty-state i {
            font-size: 48px;
            color: #e2e8f0;
            margin-bottom: 16px;
        }

        /* Fee Calculator */
        .fee-calculator {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .fee-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .fee-total {
            font-size: 1.2rem;
            font-weight: bold;
            color: #3b82f6;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
        }

        /* Charts */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 24px;
            margin-top: 32px;
        }

        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .chart-title {
            font-weight: 600;
            font-size: 16px;
            color: #1e293b;
            margin-bottom: 20px;
        }

        /* Pagination */
        .table-footer {
            padding: 20px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pagination-btn {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            background: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .pagination-btn:hover:not(:disabled) {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Required Field */
        .required {
            color: #ef4444;
            margin-left: 4px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .tabs-nav {
                overflow-x: auto;
                padding: 8px;
            }
            
            .tab-btn {
                padding: 10px 16px;
                font-size: 13px;
            }
            
            .search-box {
                min-width: 100%;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .option-grid {
                grid-template-columns: 1fr;
            }
            
            .table-container {
                overflow-x: auto;
            }
            
            .action-buttons {
                width: 100%;
            }
            
            .btn {
                flex: 1;
                justify-content: center;
            }
            
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .counselor-grid {
                grid-template-columns: 1fr;
            }
            
            .followup-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-group {
                width: 100%;
            }
            
            .filter-select {
                flex: 1;
            }
        }

    /* Base Notification Styles */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    color: #1e293b;
    padding: 16px 20px;
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    transform: translateX(400px);
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    z-index: 9999;
    display: flex;
    align-items: flex-start;
    gap: 14px;
    max-width: 380px;
    min-width: 300px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
}

.notification.show {
    transform: translateX(0);
    opacity: 1;
}

/* Success Notification */
.notification.success {
    border-left: 5px solid #10b981;
    background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
}

.notification.success .notification-icon {
    background: #10b981;
    color: white;
}

/* Error Notification */
.notification.error {
    border-left: 5px solid #ef4444;
    background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
}

.notification.error .notification-icon {
    background: #ef4444;
    color: white;
}

/* Notification Icon */
.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 18px;
}

/* Notification Content */
.notification-content {
    flex: 1;
    min-width: 0;
}

#notification-text {
    font-size: 14px;
    line-height: 1.5;
    color: #334155;
    margin-bottom: 8px;
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
}

/* Notification Header */
.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}

.notification-title {
    font-weight: 600;
    font-size: 15px;
    color: #1e293b;
}

.notification-success .notification-title {
    color: #059669;
}

.notification-error .notification-title {
    color: #dc2626;
}

/* Notification Time */
.notification-time {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 4px;
}

/* Close Button */
.notification-close {
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    font-size: 14px;
    padding: 4px;
    margin-left: 8px;
    border-radius: 4px;
    transition: all 0.2s;
    align-self: flex-start;
    flex-shrink: 0;
}

.notification-close:hover {
    background: #f1f5f9;
    color: #64748b;
}

/* Responsive Design */
@media (max-width: 768px) {
    .notification {
        top: 15px;
        right: 15px;
        left: 15px;
        max-width: none;
        min-width: auto;
        width: calc(100% - 30px);
        padding: 14px 16px;
    }
}

@media (max-width: 480px) {
    .notification {
        padding: 12px 14px;
        gap: 12px;
    }
    
    .notification-icon {
        width: 36px;
        height: 36px;
        font-size: 16px;
    }
    
    #notification-text {
        font-size: 13px;
    }
    
    .notification-title {
        font-size: 14px;
    }
}

/* Animation for hiding */
@keyframes notificationSlideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(400px);
        opacity: 0;
    }
}

.notification.hiding {
    animation: notificationSlideOut 0.3s ease forwards;
}
    </style> 

    <div class="leads-container">
        <!-- Tabs Navigation -->
        <div class="tabs-nav">
            <button class="tab-btn active" onclick="showTab('dashboard')">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </button>
            <button class="tab-btn" onclick="showTab('registration')">
                <i class="fas fa-user-plus"></i> New Lead
            </button>
            <button class="tab-btn" onclick="showTab('leads')">
                <i class="fas fa-users"></i> All Leads
            </button>
            <button class="tab-btn" onclick="showTab('followup')">
                <i class="fas fa-calendar-check"></i> Follow-ups
            </button>
           
            <button class="tab-btn" onclick="showTab('admission')">
                <i class="fas fa-file-contract"></i> Admission
            </button>
        </div>

        <!-- Dashboard Tab -->
        <div class="tab-content active" id="dashboard-tab">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Leads Dashboard</h1>
                    <p class="page-subtitle">Track and manage all prospective students</p>
                </div>
                <div class="action-buttons">
                    <button class="btn btn-secondary" onclick="exportData()">
                        <i class="fas fa-download"></i> Export Data
                    </button>
                    <button class="btn btn-primary" onclick="showTab('registration')">
                        <i class="fas fa-plus"></i> Add New Lead
                    </button>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon total">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <div class="stat-value" id="totalLeads">0</div>
                            <div class="stat-label">Total Leads</div>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon hot">
                            <i class="fas fa-fire"></i>
                        </div>
                        <div>
                            <div class="stat-value" id="hotLeads">0</div>
                            <div class="stat-label">Hot Leads</div>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon converted">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div>
                            <div class="stat-value" id="convertedLeads">0</div>
                            <div class="stat-label">Converted</div>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon followup">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <div class="stat-value" id="todayFollowups">0</div>
                            <div class="stat-label">Today's Follow-ups</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Leads Table -->
            <div class="table-container">
                <div class="table-header">
                    <div class="table-title">Recent Leads</div>
                    <button class="btn btn-secondary" onclick="showTab('leads')">
                        View All Leads
                    </button>
                </div>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>Lead ID</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Follow-up</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="recentLeadsTable">
                        <!-- Recent leads will load here -->
                    </tbody>
                </table>
            </div>

            <!-- Charts -->
            <div class="charts-grid">
                <div class="chart-card">
                    <div class="chart-title">Lead Sources</div>
                    <canvas id="sourceChart" height="200"></canvas>
                </div>
                
                <div class="chart-card">
                    <div class="chart-title">Lead Status Distribution</div>
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Registration Tab - THIS IS WHERE YOU CREATE NEW LEADS -->
        <div class="tab-content" id="registration-tab">
            <div class="page-header">
                <div>
                    <h1 class="page-title">New Lead Registration</h1>
                    <p class="page-subtitle">Fill this form to create a new lead</p>
                </div>
                <div class="action-buttons">
                    <button class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-redo"></i> Reset Form
                    </button>
                    <button class="btn btn-primary" onclick="saveLead()">
                        <i class="fas fa-save"></i> Save Lead
                    </button>
                </div>
            </div>

            <!-- LEAD SOURCE SECTION -->
            <div class="form-section">
                <h3><i class="fas fa-signal"></i> 1. Select Lead Source <span class="required">*</span></h3>
                <div class="option-grid">
                    <div class="option-card selected" onclick="selectSource('online')" data-source="online">
                        <i class="fas fa-globe"></i>
                        <h4>Online Enquiry</h4>
                        <p>Website/Portal enquiry</p>
                    </div>
                    <div class="option-card" onclick="selectSource('walkin')" data-source="walkin">
                        <i class="fas fa-walking"></i>
                        <h4>Walk-in/Offline</h4>
                        <p>Direct institute visit</p>
                    </div>
                    <div class="option-card" onclick="selectSource('phone')" data-source="phone">
                        <i class="fas fa-phone-alt"></i>
                        <h4>Phone Enquiry</h4>
                        <p>Telephonic enquiry</p>
                    </div>
                    <div class="option-card" onclick="selectSource('referral')" data-source="referral">
                        <i class="fas fa-users"></i>
                        <h4>Referral</h4>
                        <p>Existing student referral</p>
                    </div>
                </div>
            </div>

            <!-- VISITOR TYPE SECTION -->
            <div class="form-section">
                <h3><i class="fas fa-user-tag"></i> 2. Select Visitor Type <span class="required">*</span></h3>
                <div class="option-grid">
                    <div class="option-card selected" onclick="selectVisitorType('parent')" data-type="parent">
                        <i class="fas fa-users"></i>
                        <h4>Parent/Guardian</h4>
                        <p>Registering for child</p>
                    </div>
                    <div class="option-card" onclick="selectVisitorType('student')" data-type="student">
                        <i class="fas fa-user-graduate"></i>
                        <h4>Student</h4>
                        <p>Self registration</p>
                    </div>
                    <div class="option-card" onclick="selectVisitorType('interviewee')" data-type="interviewee">
                        <i class="fas fa-file-signature"></i>
                        <h4>Interviewee</h4>
                        <p>For interview/job</p>
                    </div>
                    <div class="option-card" onclick="selectVisitorType('other')" data-type="other">
                        <i class="fas fa-user"></i>
                        <h4>Other</h4>
                        <p>General enquiry</p>
                    </div>
                </div>
            </div>

            <!-- PERSONAL INFORMATION SECTION -->
            <div class="form-section">
                <h3><i class="fas fa-id-card"></i> 3. Personal Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Full Name <span class="required">*</span></label>
                        <input type="text" class="form-control" id="fullName" placeholder="Enter visitor's full name" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> Phone Number <span class="required">*</span></label>
                        <input type="tel" class="form-control" id="phone" placeholder="Enter phone number" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email Address</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter email address">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-map-marker-alt"></i> Address</label>
                        <textarea class="form-control" id="address" rows="2" placeholder="Enter full address"></textarea>
                    </div>
                </div>
            </div>

            <!-- CHILD DETAILS SECTION (Shows only for Parent type) -->
            <div class="form-section" id="childDetailsSection">
                <h3><i class="fas fa-child"></i> 4. Child Details (For Parents)</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Child's Full Name <span class="required">*</span></label>
                        <input type="text" class="form-control" id="childName" placeholder="Enter child's full name">
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" class="form-control" id="childDOB">
                    </div>
                    <div class="form-group">
                        <label>Current School</label>
                        <input type="text" class="form-control" id="currentSchool" placeholder="Current school name">
                    </div>
                    <div class="form-group">
                        <label>Grade/Class Applying For <span class="required">*</span></label>
                        <select class="form-control" id="childGrade">
                            <option value="">Select Grade</option>
                            <option value="pre_kg">Pre-KG</option>
                            <option value="lkg">LKG</option>
                            <option value="ukg">UKG</option>
                            <option value="1">Grade 1</option>
                            <option value="2">Grade 2</option>
                            <option value="3">Grade 3</option>
                            <option value="4">Grade 4</option>
                            <option value="5">Grade 5</option>
                            <option value="6">Grade 6</option>
                            <option value="7">Grade 7</option>
                            <option value="8">Grade 8</option>
                            <option value="9">Grade 9</option>
                            <option value="10">Grade 10</option>
                            <option value="11">Grade 11</option>
                            <option value="12">Grade 12</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- GUARDIAN DETAILS SECTION (Shows only for Student type) -->
            <div class="form-section" id="guardianDetailsSection" style="display:none;">
                <h3><i class="fas fa-user-shield"></i> 4. Guardian Details (For Students)</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Guardian Name <span class="required">*</span></label>
                        <input type="text" class="form-control" id="guardianName" placeholder="Enter guardian's name">
                    </div>
                    <div class="form-group">
                        <label>Guardian Phone <span class="required">*</span></label>
                        <input type="tel" class="form-control" id="guardianPhone" placeholder="Enter guardian's phone">
                    </div>
                    <div class="form-group">
                        <label>Relationship</label>
                        <select class="form-control" id="guardianRelation">
                            <option value="father">Father</option>
                            <option value="mother">Mother</option>
                            <option value="brother">Brother</option>
                            <option value="sister">Sister</option>
                            <option value="uncle">Uncle</option>
                            <option value="aunt">Aunt</option>
                            <option value="grandparent">Grandparent</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Occupation</label>
                        <input type="text" class="form-control" id="guardianOccupation" placeholder="Enter occupation">
                    </div>
                </div>
            </div>

            <!-- STATUS AND FOLLOW-UP SECTION -->
            <div class="form-section">
                <h3><i class="fas fa-thermometer-half"></i> 5. Lead Status & Follow-up</h3>
                <div class="option-grid">
                    <div class="option-card selected" onclick="selectStatus('hot')" data-status="hot">
                        <i class="fas fa-fire"></i>
                        <h4>Hot Lead</h4>
                        <p>High priority, immediate follow-up</p>
                    </div>
                    <div class="option-card" onclick="selectStatus('warm')" data-status="warm">
                        <i class="fas fa-sun"></i>
                        <h4>Warm Lead</h4>
                        <p>Medium priority, follow-up in 2-3 days</p>
                    </div>
                    <div class="option-card" onclick="selectStatus('cold')" data-status="cold">
                        <i class="fas fa-snowflake"></i>
                        <h4>Cold Lead</h4>
                        <p>Low priority, follow-up in 1 week</p>
                    </div>
                </div>
                
                <div class="form-grid" style="margin-top: 20px;">
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Follow-up Date & Time <span class="required">*</span></label>
                        <input type="datetime-local" class="form-control" id="followupDate" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-clock"></i> Follow-up Type</label>
                        <select class="form-control" id="followupType">
                            <option value="phone">Phone Call</option>
                            <option value="meeting">In-person Meeting</option>
                            <option value="email">Email Follow-up</option>
                            <option value="whatsapp">WhatsApp Message</option>
                            <option value="sms">SMS</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- ENTRANCE TEST SECTION -->
            <div class="form-section">
                <h3><i class="fas fa-file-alt"></i> 6. Entrance Test Assessment</h3>
                <div class="test-option-grid">
                    <div class="test-option" onclick="selectTest('required')" data-test="required">
                        <i class="fas fa-clipboard-check"></i>
                        <h4>Test Required</h4>
                        <p>Schedule entrance test</p>
                    </div>
                    <div class="test-option selected" onclick="selectTest("not_applicable")" data-test="not_applicable">
                        <i class="fas fa-ban"></i>
                        <h4>Not Applicable</h4>
                        <p>For counseling only</p>
                    </div>
                    <div class="test-option" onclick="selectTest('exempted')" data-test="exempted">
                        <i class="fas fa-user-check"></i>
                        <h4>Test Exempted</h4>
                        <p>Based on previous marks</p>
                    </div>
                </div>
                
                <div id="testDetailsSection" style="display:none; margin-top:20px;">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Test Date & Time</label>
                            <input type="datetime-local" class="form-control" id="testDateTime">
                        </div>
                        <div class="form-group">
                            <label>Test Subjects</label>
                            <input type="text" class="form-control" id="testSubjects" placeholder="e.g., Math, English, Science">
                        </div>
                    </div>
                </div>
            </div>

            <!-- COUNSELING ASSIGNMENT -->
            <div class="form-section">
                <h3><i class="fas fa-user-tie"></i> 7. Counseling Assignment</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label><i class="fas fa-user-tie"></i> Assign to Counselor <span class="required">*</span></label>
                        <select class="form-control" id="counselorSelect" required>
                            <option value="">Select Counselor</option>
                            <option value="john">John Smith (Senior Counselor)</option>
                            <option value="sarah">Sarah Johnson (Admissions)</option>
                            <option value="mike">Mike Wilson (Academic)</option>
                            <option value="priya">Priya Patel (Career Guidance)</option>
                            <option value="rahul">Rahul Verma (Placement)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-exclamation-triangle"></i> Counseling Priority</label>
                        <select class="form-control" id="counselingPriority">
                            <option value="high">High Priority</option>
                            <option value="medium" selected>Medium Priority</option>
                            <option value="low">Low Priority</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- REMARKS AND NOTES -->
            <div class="form-section">
                <h3><i class="fas fa-comment-dots"></i> 8. Remarks & Notes</h3>
                <div class="form-group">
                    <label><i class="fas fa-sticky-note"></i> Initial Remarks</label>
                    <textarea class="form-control" id="remarks" rows="4" placeholder="Enter any initial remarks or observations..."></textarea>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-star"></i> Special Requirements</label>
                    <input type="text" class="form-control" id="specialRequirements" placeholder="Any special requirements or notes...">
                </div>
            </div>

            <!-- SUBMIT BUTTON -->
            <div style="text-align: center; margin-top: 30px; padding: 20px; background: #f8fafc; border-radius: 12px;">
                <h3 style="margin-bottom: 20px; color: #3b82f6;"><i class="fas fa-paper-plane"></i> Ready to Create Lead</h3>
                <p style="color: #64748b; margin-bottom: 20px;">Fill all required fields and click below to generate a new lead</p>
                <button class="btn btn-primary" style="padding: 15px 40px; font-size: 1.1rem;" onclick="saveLead()">
                    <i class="fas fa-save"></i> Generate Lead & Save
                </button>
                <button class="btn btn-secondary" style="padding: 15px 40px; font-size: 1.1rem; margin-left: 15px;" onclick="resetForm()">
                    <i class="fas fa-redo"></i> Clear Form
                </button>
            </div>
        </div>

        <!-- All Leads Tab -->
        <div class="tab-content" id="leads-tab">
            <div class="page-header">
                <div>
                    <h1 class="page-title">All Leads</h1>
                    <p class="page-subtitle">Manage and track all prospective students</p>
                </div>
                <div class="action-buttons">
                    <button class="btn btn-success" onclick="exportData()">
                        <i class="fas fa-download"></i> Export Data
                    </button>
                    <button class="btn btn-primary" onclick="showTab('registration')">
                        <i class="fas fa-plus"></i> Add New Lead
                    </button>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="search-section">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchLeadsInput" placeholder="Search leads by name, phone, or ID..." oninput="searchLeads()">
                </div>
                <div class="filter-group">
                    <select class="filter-select" id="statusFilter" onchange="filterLeads()">
                        <option value="">All Status</option>
                        <option value="hot">Hot</option>
                        <option value="warm">Warm</option>
                        <option value="cold">Cold</option>
                        <option value="converted">Converted</option>
                    </select>
                    <select class="filter-select" id="sourceFilter" onchange="filterLeads()">
                        <option value="">All Sources</option>
                        <option value="online">Online</option>
                        <option value="walkin">Walk-in</option>
                        <option value="phone">Phone</option>
                        <option value="referral">Referral</option>
                    </select>
                    <select class="filter-select" id="typeFilter" onchange="filterLeads()">
                        <option value="">All Types</option>
                        <option value="parent">Parent</option>
                        <option value="student">Student</option>
                        <option value="interviewee">Interviewee</option>
                        <option value="other">Other</option>
                    </select>
                    <button class="btn btn-secondary" onclick="clearFilters()">
                        <i class="fas fa-filter"></i> Clear Filters
                    </button>
                </div>
            </div>

            <!-- Leads Table -->
            <div class="table-container">
                <div class="table-header">
                    <div class="table-title">All Generated Leads</div>
                    <div class="entries-info">
                        Showing <span id="startEntry">1</span> to <span id="endEntry">10</span> of <span id="totalEntries">0</span> leads
                    </div>
                </div>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>Lead ID</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Type</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Follow-up</th>
                            <th>Counselor</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="allLeadsTable">
                        <!-- All leads will load here -->
                    </tbody>
                </table>
                
                <div class="table-footer">
                    <div class="pagination">
                        <button class="pagination-btn" id="prevBtn" onclick="prevPage()">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span id="pageInfo">Page 1 of 1</span>
                        <button class="pagination-btn" id="nextBtn" onclick="nextPage()">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Follow-up Tab -->
        <div class="tab-content" id="followup-tab">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Follow-up Management</h1>
                    <p class="page-subtitle">Schedule and manage follow-ups</p>
                </div>
                <div class="action-buttons">
                    <button class="btn btn-primary" onclick="showTab('registration')">
                        <i class="fas fa-plus"></i> New Lead
                    </button>
                </div>
            </div>

            <div class="followup-grid" id="followupList">
                <!-- Follow-ups will load here -->
            </div>
        </div>

        <!-- Counseling Tab -->


        <!-- Admission Tab -->
        <div class="tab-content" id="admission-tab">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Admission Process</h1>
                    <p class="page-subtitle">Fee calculation and admission processing</p>
                </div>
            </div>

            <div class="form-section">
                <h3><i class="fas fa-calculator"></i> Fee Calculator</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label><i class="fas fa-graduation-cap"></i> Select Course/Program</label>
                        <select class="form-control" id="courseSelect" onchange="updateFeeBreakdown()">
                            <option value="">Select Course</option>
                            <option value="pre_kg">Pre-KG (₹25,000)</option>
                            <option value="primary">Primary School (₹35,000)</option>
                            <option value="middle">Middle School (₹45,000)</option>
                            <option value="high">High School (₹55,000)</option>
                            <option value="senior">Senior Secondary (₹65,000)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-percentage"></i> Discount (%)</label>
                        <input type="number" class="form-control" id="discount" min="0" max="100" value="0" onchange="updateFeeBreakdown()">
                    </div>
                </div>

                <div class="fee-calculator">
                    <div id="feeBreakdown"></div>
                </div>
            </div>

            <div class="form-section">
                <h3><i class="fas fa-file-invoice-dollar"></i> Payment Processing</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label><i class="fas fa-money-check-alt"></i> Payment Method</label>
                        <select class="form-control" id="paymentMethod">
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                            <option value="online">Online Transfer</option>
                            <option value="card">Credit/Debit Card</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar"></i> Payment Date</label>
                        <input type="date" class="form-control" id="paymentDate" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <button class="btn btn-primary" style="margin-top:20px;" onclick="processAdmission()">
                    <i class="fas fa-check-circle"></i> Complete Admission
                </button>
            </div>
        </div>
    </div>

    <!-- Notification -->
   <div class="notification" id="notification">
    <div class="notification-icon">
        <i class="fas fa-check-circle"></i>
    </div>
    <div class="notification-content">
        <div class="notification-header">
            <div class="notification-title" id="notification-title">Success</div>
            <button class="notification-close" onclick="hideNotification()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="notification-text"></div>
        <div class="notification-time" id="notification-time"></div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ============================================
        // LEADS MANAGEMENT SYSTEM
        // ============================================

        // Application State
        let leads = [];
        let currentPage = 1;
        const leadsPerPage = 10;
        let filteredLeads = [];
        let currentFilter = '';
        let currentSource = '';
        let currentType = '';
        let searchTerm = '';

        // Current lead being created
        let currentLead = {
            source: 'online',
            type: 'parent',
            status: 'hot',
            test: 'not_applicable'
        };

        // Initialize Application
        document.addEventListener('DOMContentLoaded', function() {
            initApp();
        });

       function initApp() {
    // Load existing leads from localStorage
            loadLeadsFromStorage();
            
            // ======= ADD THIS CODE HERE =======
            // Add dummy leads if no leads exist
            if (leads.length === 0) {
                const dummyLeads = [
                    {
                        id: 'LEAD-001',
                        name: 'Rajesh Kumar',
                        phone: '9876543210',
                        email: 'rajesh.kumar@example.com',
                        address: '123 Main Street, Mumbai',
                        source: 'online',
                        type: 'parent',
                        status: 'hot',
                        followupDate: new Date(Date.now() + 24 * 60 * 60 * 1000).toISOString().slice(0,16),
                        followupType: 'phone',
                        counselor: 'john',
                        counselingPriority: 'high',
                        remarks: 'Interested in admission for his daughter.',
                        specialRequirements: 'Needs scholarship information',
                        testRequired: 'required',
                        testDateTime: new Date(Date.now() + 3 * 24 * 60 * 60 * 1000).toISOString().slice(0,16),
                        testSubjects: 'Math, English, Science',
                        childName: 'Priya Kumar',
                        childDOB: '2018-05-15',
                        childGrade: '1',
                        currentSchool: 'Little Angels School',
                        createdAt: new Date(Date.now() - 2 * 24 * 60 * 60 * 1000).toISOString(),
                        updatedAt: new Date().toISOString()
                    },
                    {
                        id: 'LEAD-002',
                        name: 'Sunita Sharma',
                        phone: '8765432109',
                        email: 'sunita.sharma@example.com',
                        address: '456 Park Avenue, Delhi',
                        source: 'walkin',
                        type: 'student',
                        status: 'warm',
                        followupDate: new Date(Date.now() + 48 * 60 * 60 * 1000).toISOString().slice(0,16),
                        followupType: 'meeting',
                        counselor: 'sarah',
                        counselingPriority: 'medium',
                        remarks: 'Wants to pursue Science stream.',
                        specialRequirements: 'Looking for hostel facility',
                        testRequired: 'exempted',
                        testDateTime: null,
                        testSubjects: null,
                        guardianName: 'Ravi Sharma',
                        guardianPhone: '7654321098',
                        guardianRelation: 'father',
                        guardianOccupation: 'Doctor',
                        createdAt: new Date(Date.now() - 1 * 24 * 60 * 60 * 1000).toISOString(),
                        updatedAt: new Date().toISOString()
                    }
                ];
                
                leads = dummyLeads;
                filteredLeads = [...leads];
                saveLeadsToStorage();
            }
            // ======= END OF DUMMY DATA CODE =======
            
            // Set default follow-up date (tomorrow at 10 AM)
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            tomorrow.setHours(10, 0, 0, 0);
            document.getElementById('followupDate').value = tomorrow.toISOString().slice(0,16);
            
            // Set default test date (3 days from now)
            const testDate = new Date();
            testDate.setDate(testDate.getDate() + 3);
            testDate.setHours(11, 0, 0, 0);
            document.getElementById('testDateTime').value = testDate.toISOString().slice(0,16);
            
            // Initial render
            updateDashboard();
            renderRecentLeads();
            renderAllLeads();
            renderFollowups();
            updateCounselorStats();
        }

        // Load leads from localStorage
        function loadLeadsFromStorage() {
            const savedLeads = localStorage.getItem('instituteLeads');
            if (savedLeads) {
                leads = JSON.parse(savedLeads);
            } else {
                leads = [];
            }
            filteredLeads = [...leads];
        }

        // Save leads to localStorage
        function saveLeadsToStorage() {
            localStorage.setItem('instituteLeads', JSON.stringify(leads));
        }

        // Tab Navigation
        function showTab(tabName) {
            // Update active tab button
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Update active tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Show selected tab
            const activeBtn = document.querySelector(`.tab-btn[onclick*="${tabName}"]`);
            if (activeBtn) {
                activeBtn.classList.add('active');
            }
            
            const activeContent = document.getElementById(`${tabName}-tab`);
            if (activeContent) {
                activeContent.classList.add('active');
                
                // Refresh data for the tab
                switch(tabName) {
                    case 'dashboard':
                        updateDashboard();
                        renderRecentLeads();
                        break;
                    case 'leads':
                        renderAllLeads();
                        break;
                    case 'followup':
                        renderFollowups();
                        break;
                    case 'counseling':
                        updateCounselorStats();
                        break;
                }
            }
        }

        // ============================================
        // FORM SELECTION FUNCTIONS
        // ============================================

        function selectSource(source) {
            // Update UI
            document.querySelectorAll('[data-source]').forEach(card => {
                card.classList.remove('selected');
            });
            event.target.closest('.option-card').classList.add('selected');
            
            // Update current lead
            currentLead.source = source;
        }

        function selectVisitorType(type) {
            // Update UI
            document.querySelectorAll('[data-type]').forEach(card => {
                card.classList.remove('selected');
            });
            event.target.closest('.option-card').classList.add('selected');
            
            // Update current lead
            currentLead.type = type;
            
            // Show/hide appropriate sections
            const childSection = document.getElementById('childDetailsSection');
            const guardianSection = document.getElementById('guardianDetailsSection');
            
            if (type === 'parent') {
                childSection.style.display = 'block';
                guardianSection.style.display = 'none';
            } else if (type === 'student') {
                childSection.style.display = 'none';
                guardianSection.style.display = 'block';
            } else {
                childSection.style.display = 'none';
                guardianSection.style.display = 'none';
            }
        }

        function selectStatus(status) {
            // Update UI
            document.querySelectorAll('[data-status]').forEach(card => {
                card.classList.remove('selected');
            });
            event.target.closest('.option-card').classList.add('selected');
            
            // Update current lead
            currentLead.status = status;
        }

        function selectTest(test) {
            // Update UI
            document.querySelectorAll('[data-test]').forEach(card => {
                card.classList.remove('selected');
            });
            event.target.closest('.test-option').classList.add('selected');
            
            // Update current lead
            currentLead.test = test;
            
            // Show/hide test details
            const testDetails = document.getElementById('testDetailsSection');
            testDetails.style.display = test === 'required' ? 'block' : 'none';
        }

        // ============================================
        // SAVE LEAD FUNCTION - THIS CREATES NEW LEADS
        // ============================================

        function saveLead() {
            // Validate required fields
            const fullName = document.getElementById('fullName').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const followupDate = document.getElementById('followupDate').value;
            const counselor = document.getElementById('counselorSelect').value;
            
            if (!fullName || !phone || !followupDate || !counselor) {
                showNotification('Please fill in all required fields (marked with *)', 'error');
                return;
            }

            // Validate specific fields based on type
            if (currentLead.type === 'parent') {
                const childName = document.getElementById('childName').value.trim();
                const childGrade = document.getElementById('childGrade').value;
                if (!childName || !childGrade) {
                    showNotification('Please fill in child details for parent type', 'error');
                    return;
                }
            } else if (currentLead.type === 'student') {
                const guardianName = document.getElementById('guardianName').value.trim();
                const guardianPhone = document.getElementById('guardianPhone').value.trim();
                if (!guardianName || !guardianPhone) {
                    showNotification('Please fill in guardian details for student type', 'error');
                    return;
                }
            }

            // Generate unique lead ID
            const leadId = 'LEAD-' + String(leads.length + 1).padStart(3, '0');
            
            // Create new lead object
            const newLead = {
                id: leadId,
                name: fullName,
                phone: phone,
                email: document.getElementById('email').value.trim(),
                address: document.getElementById('address').value.trim(),
                source: currentLead.source,
                type: currentLead.type,
                status: currentLead.status,
                followupDate: followupDate,
                followupType: document.getElementById('followupType').value,
                counselor: counselor,
                counselingPriority: document.getElementById('counselingPriority').value,
                remarks: document.getElementById('remarks').value.trim(),
                specialRequirements: document.getElementById('specialRequirements').value.trim(),
                testRequired: currentLead.test,
                testDateTime: currentLead.test === 'required' ? document.getElementById('testDateTime').value : null,
                testSubjects: currentLead.test === 'required' ? document.getElementById('testSubjects').value : null,
                createdAt: new Date().toISOString(),
                updatedAt: new Date().toISOString()
            };

            // Add child/guardian details based on type
            if (currentLead.type === 'parent') {
                newLead.childName = document.getElementById('childName').value.trim();
                newLead.childDOB = document.getElementById('childDOB').value;
                newLead.childGrade = document.getElementById('childGrade').value;
                newLead.currentSchool = document.getElementById('currentSchool').value.trim();
            } else if (currentLead.type === 'student') {
                newLead.guardianName = document.getElementById('guardianName').value.trim();
                newLead.guardianPhone = document.getElementById('guardianPhone').value.trim();
                newLead.guardianRelation = document.getElementById('guardianRelation').value;
                newLead.guardianOccupation = document.getElementById('guardianOccupation').value.trim();
            }

            // Add lead to array
            leads.unshift(newLead);
            
            // Update filtered leads
            filteredLeads.unshift(newLead);
            
            // Save to localStorage
            saveLeadsToStorage();
            
            // Reset form
            resetForm();
            
            // Update UI
            updateDashboard();
            renderRecentLeads();
            renderAllLeads();
            renderFollowups();
            updateCounselorStats();
            
            // Show success message
            showNotification(`Lead created successfully! Lead ID: ${leadId}`);
            
            // Switch to dashboard tab
            showTab('dashboard');
        }

        function resetForm() {
            // Reset form fields
            document.getElementById('fullName').value = '';
            document.getElementById('phone').value = '';
            document.getElementById('email').value = '';
            document.getElementById('address').value = '';
            document.getElementById('remarks').value = '';
            document.getElementById('specialRequirements').value = '';
            
            // Reset to default selections
            currentLead = {
                source: 'online',
                type: 'parent',
                status: 'hot',
                test: 'not_applicable'
            };
            
            // Reset UI selections
            document.querySelectorAll('.option-card.selected').forEach(card => card.classList.remove('selected'));
            document.querySelectorAll('.test-option.selected').forEach(card => card.classList.remove('selected'));
            
            document.querySelector('[data-source="online"]').classList.add('selected');
            document.querySelector('[data-type="parent"]').classList.add('selected');
            document.querySelector('[data-status="hot"]').classList.add('selected');
            document.querySelector('[data-test="not_applicable"]').classList.add('selected');
            
            // Reset child/guardian fields
            document.getElementById('childName').value = '';
            document.getElementById('childDOB').value = '';
            document.getElementById('childGrade').value = '';
            document.getElementById('currentSchool').value = '';
            document.getElementById('guardianName').value = '';
            document.getElementById('guardianPhone').value = '';
            document.getElementById('guardianRelation').value = 'father';
            document.getElementById('guardianOccupation').value = '';
            
            // Show/hide appropriate sections
            document.getElementById('childDetailsSection').style.display = 'block';
            document.getElementById('guardianDetailsSection').style.display = 'none';
            document.getElementById('testDetailsSection').style.display = 'none';
            
            // Reset counselor selection
            document.getElementById('counselorSelect').value = '';
            document.getElementById('counselingPriority').value = 'medium';
            document.getElementById('followupType').value = 'phone';
            
            // Set default dates
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            tomorrow.setHours(10, 0, 0, 0);
            document.getElementById('followupDate').value = tomorrow.toISOString().slice(0,16);
            
            const testDate = new Date();
            testDate.setDate(testDate.getDate() + 3);
            testDate.setHours(11, 0, 0, 0);
            document.getElementById('testDateTime').value = testDate.toISOString().slice(0,16);
            
            document.getElementById('testSubjects').value = '';
        }

        // ============================================
        // DASHBOARD FUNCTIONS
        // ============================================

        function updateDashboard() {
            const total = leads.length;
            const hotLeads = leads.filter(lead => lead.status === 'hot').length;
            const convertedLeads = leads.filter(lead => lead.status === 'converted').length;
            
            // Count today's follow-ups
            const today = new Date().toISOString().split('T')[0];
            const todayFollowups = leads.filter(lead => 
                lead.followupDate && lead.followupDate.startsWith(today)
            ).length;
            
            // Update stats
            document.getElementById('totalLeads').textContent = total;
            document.getElementById('hotLeads').textContent = hotLeads;
            document.getElementById('convertedLeads').textContent = convertedLeads;
            document.getElementById('todayFollowups').textContent = todayFollowups;
        }

        function renderRecentLeads() {
            const tbody = document.getElementById('recentLeadsTable');
            if (!tbody) return;
            
            tbody.innerHTML = '';
            
            // Get 5 most recent leads
            const recentLeads = filteredLeads.slice(0, 5);
            
            if (recentLeads.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>No leads created yet. Create your first lead!</p>
                        </td>
                    </tr>
                `;
                return;
            }
            
            recentLeads.forEach(lead => {
                const statusClass = `status-${lead.status}`;
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${lead.id}</strong></td>
                    <td>
                        <div><strong>${lead.name}</strong></div>
                        <small style="color: #64748b;">${lead.type === 'parent' ? 'Parent of ' + (lead.childName || 'Child') : lead.type}</small>
                    </td>
                    <td>
                        <div><i class="fas fa-phone"></i> ${lead.phone}</div>
                        <small style="color: #64748b;">${lead.email || 'No email'}</small>
                    </td>
                    <td>${lead.source}</td>
                    <td><span class="status-badge ${statusClass}">${lead.status}</span></td>
                    <td>${lead.followupDate ? new Date(lead.followupDate).toLocaleDateString() : 'Not set'}</td>
                    <td>
                        <div class="action-buttons-small">
                            <button class="action-btn" onclick="viewLead('${lead.id}')" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn" onclick="editLead('${lead.id}')" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // ============================================
        // ALL LEADS MANAGEMENT
        // ============================================

        function renderAllLeads() {
            const tbody = document.getElementById('allLeadsTable');
            if (!tbody) return;
            
            tbody.innerHTML = '';
            
            // Calculate pagination
            const startIndex = (currentPage - 1) * leadsPerPage;
            const endIndex = startIndex + leadsPerPage;
            const pageLeads = filteredLeads.slice(startIndex, endIndex);
            
            if (pageLeads.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>No leads found. ${leads.length === 0 ? 'Create your first lead!' : 'Try different filters.'}</p>
                        </td>
                    </tr>
                `;
                return;
            }
            
            pageLeads.forEach(lead => {
                const statusClass = `status-${lead.status}`;
                
                // Format follow-up date
                let followupDisplay = lead.followupDate || '-';
                if (lead.followupDate) {
                    const today = new Date();
                    const followup = new Date(lead.followupDate);
                    const diffTime = followup - today;
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    
                    if (diffDays === 0) {
                        followupDisplay = `<span style="color: #ef4444; font-weight: 500;">Today</span>`;
                    } else if (diffDays === 1) {
                        followupDisplay = `<span style="color: #f59e0b; font-weight: 500;">Tomorrow</span>`;
                    } else if (diffDays < 0) {
                        followupDisplay = `<span style="color: #64748b; font-style: italic;">Overdue</span>`;
                    } else {
                        followupDisplay = new Date(lead.followupDate).toLocaleDateString();
                    }
                }
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${lead.id}</strong></td>
                    <td>
                        <div><strong>${lead.name}</strong></div>
                        <small style="color: #64748b;">${lead.type === 'parent' ? 'Parent of ' + (lead.childName || 'Child') : lead.type}</small>
                    </td>
                    <td>
                        <div><i class="fas fa-phone"></i> ${lead.phone}</div>
                        <small style="color: #64748b;">${lead.email || 'No email'}</small>
                    </td>
                    <td>${lead.type}</td>
                    <td>${lead.source}</td>
                    <td><span class="status-badge ${statusClass}">${lead.status}</span></td>
                    <td>${followupDisplay}</td>
                    <td>${lead.counselor ? getCounselorName(lead.counselor) : 'Not assigned'}</td>
                    <td>
                        <div class="action-buttons-small">
                            <button class="action-btn" onclick="viewLead('${lead.id}')" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn" onclick="editLead('${lead.id}')" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn" onclick="deleteLead('${lead.id}')" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
            
            updatePagination();
        }

        function updatePagination() {
            const totalPages = Math.ceil(filteredLeads.length / leadsPerPage);
            const startEntry = filteredLeads.length > 0 ? (currentPage - 1) * leadsPerPage + 1 : 0;
            const endEntry = Math.min(currentPage * leadsPerPage, filteredLeads.length);
            
            document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages}`;
            document.getElementById('startEntry').textContent = startEntry;
            document.getElementById('endEntry').textContent = endEntry;
            document.getElementById('totalEntries').textContent = filteredLeads.length;
            
            document.getElementById('prevBtn').disabled = currentPage === 1;
            document.getElementById('nextBtn').disabled = currentPage === totalPages || totalPages === 0;
        }

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                renderAllLeads();
            }
        }

        function nextPage() {
            const totalPages = Math.ceil(filteredLeads.length / leadsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderAllLeads();
            }
        }

        // ============================================
        // SEARCH AND FILTER FUNCTIONS
        // ============================================

        function searchLeads() {
            searchTerm = document.getElementById('searchLeadsInput').value.toLowerCase();
            applyFilters();
        }

        function filterLeads() {
            currentFilter = document.getElementById('statusFilter').value;
            currentSource = document.getElementById('sourceFilter').value;
            currentType = document.getElementById('typeFilter').value;
            applyFilters();
        }

        function applyFilters() {
            filteredLeads = leads.filter(lead => {
                // Search term filter
                const matchesSearch = !searchTerm || 
                    lead.name.toLowerCase().includes(searchTerm) ||
                    lead.phone.includes(searchTerm) ||
                    lead.email?.toLowerCase().includes(searchTerm) ||
                    lead.id.toLowerCase().includes(searchTerm) ||
                    (lead.childName && lead.childName.toLowerCase().includes(searchTerm)) ||
                    (lead.guardianName && lead.guardianName.toLowerCase().includes(searchTerm));
                
                // Status filter
                const matchesStatus = !currentFilter || lead.status === currentFilter;
                
                // Source filter
                const matchesSource = !currentSource || lead.source === currentSource;
                
                // Type filter
                const matchesType = !currentType || lead.type === currentType;
                
                return matchesSearch && matchesStatus && matchesSource && matchesType;
            });
            
            currentPage = 1;
            renderAllLeads();
            renderRecentLeads();
        }

        function clearFilters() {
            document.getElementById('searchLeadsInput').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('sourceFilter').value = '';
            document.getElementById('typeFilter').value = '';
            
            searchTerm = '';
            currentFilter = '';
            currentSource = '';
            currentType = '';
            
            filteredLeads = [...leads];
            currentPage = 1;
            
            renderAllLeads();
            renderRecentLeads();
        }

        // ============================================
        // LEAD ACTIONS (View, Edit, Delete)
        // ============================================

        function viewLead(leadId) {
            const lead = leads.find(l => l.id === leadId);
            if (!lead) return;
            
            let details = `
                <div style="background: white; padding: 24px; border-radius: 12px; max-width: 800px;">
                    <h3 style="color: #3b82f6; margin-bottom: 20px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                        <i class="fas fa-user"></i> Lead Details: ${lead.name}
                    </h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                        <div>
                            <h4 style="color: #475569; margin-bottom: 12px; font-size: 14px;">PERSONAL INFORMATION</h4>
                            <div style="display: grid; gap: 8px; font-size: 14px;">
                                <div><strong>Name:</strong> ${lead.name}</div>
                                <div><strong>Phone:</strong> ${lead.phone}</div>
                                <div><strong>Email:</strong> ${lead.email || 'Not provided'}</div>
                                <div><strong>Address:</strong> ${lead.address || 'Not provided'}</div>
                                <div><strong>Visitor Type:</strong> ${lead.type}</div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 style="color: #475569; margin-bottom: 12px; font-size: 14px;">LEAD INFORMATION</h4>
                            <div style="display: grid; gap: 8px; font-size: 14px;">
                                <div><strong>Lead ID:</strong> ${lead.id}</div>
                                <div><strong>Status:</strong> <span class="status-badge status-${lead.status}">${lead.status}</span></div>
                                <div><strong>Source:</strong> ${lead.source}</div>
                                <div><strong>Follow-up:</strong> ${lead.followupDate ? new Date(lead.followupDate).toLocaleString() : 'Not set'}</div>
                                <div><strong>Counselor:</strong> ${getCounselorName(lead.counselor)}</div>
                            </div>
                        </div>
                    </div>
                    
                    ${lead.type === 'parent' ? `
                    <div style="margin-bottom: 24px;">
                        <h4 style="color: #475569; margin-bottom: 12px; font-size: 14px;">CHILD INFORMATION</h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 14px;">
                            <div><strong>Child Name:</strong> ${lead.childName || 'Not provided'}</div>
                            <div><strong>Date of Birth:</strong> ${lead.childDOB || 'Not provided'}</div>
                            <div><strong>Grade Applying:</strong> ${lead.childGrade || 'Not specified'}</div>
                            <div><strong>Current School:</strong> ${lead.currentSchool || 'Not provided'}</div>
                        </div>
                    </div>
                    ` : ''}
                    
                    ${lead.type === 'student' ? `
                    <div style="margin-bottom: 24px;">
                        <h4 style="color: #475569; margin-bottom: 12px; font-size: 14px;">GUARDIAN INFORMATION</h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 14px;">
                            <div><strong>Guardian Name:</strong> ${lead.guardianName || 'Not provided'}</div>
                            <div><strong>Guardian Phone:</strong> ${lead.guardianPhone || 'Not provided'}</div>
                            <div><strong>Relationship:</strong> ${lead.guardianRelation || 'Not provided'}</div>
                            <div><strong>Occupation:</strong> ${lead.guardianOccupation || 'Not provided'}</div>
                        </div>
                    </div>
                    ` : ''}
                    
                    <div style="margin-bottom: 24px;">
                        <h4 style="color: #475569; margin-bottom: 12px; font-size: 14px;">ADDITIONAL INFORMATION</h4>
                        <div style="font-size: 14px;">
                            <div><strong>Remarks:</strong> ${lead.remarks || 'No remarks'}</div>
                            ${lead.specialRequirements ? `<div><strong>Special Requirements:</strong> ${lead.specialRequirements}</div>` : ''}
                            ${lead.testRequired === 'required' ? `
                            <div style="margin-top: 12px;">
                                <strong>Entrance Test:</strong> Scheduled for ${lead.testDateTime ? new Date(lead.testDateTime).toLocaleString() : 'Not set'}
                                ${lead.testSubjects ? `<br><strong>Subjects:</strong> ${lead.testSubjects}` : ''}
                            </div>
                            ` : ''}
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                        <div style="color: #64748b; font-size: 12px;">
                            <div>Created: ${new Date(lead.createdAt).toLocaleString()}</div>
                            <div>Last Updated: ${new Date(lead.updatedAt).toLocaleString()}</div>
                        </div>
                        <div>
                            <button onclick="editLead('${lead.id}')" style="padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-edit"></i> Edit Lead
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            // Create modal
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                padding: 20px;
            `;
            modal.innerHTML = details;
            
            // Close modal on background click
            modal.onclick = function(e) {
                if (e.target === modal) {
                    document.body.removeChild(modal);
                }
            };
            
            document.body.appendChild(modal);
        }

        function editLead(leadId) {
            const lead = leads.find(l => l.id === leadId);
            if (!lead) return;
            
            // Populate form with lead data
            document.getElementById('fullName').value = lead.name;
            document.getElementById('phone').value = lead.phone;
            document.getElementById('email').value = lead.email || '';
            document.getElementById('address').value = lead.address || '';
            document.getElementById('followupDate').value = lead.followupDate || '';
            document.getElementById('remarks').value = lead.remarks || '';
            document.getElementById('specialRequirements').value = lead.specialRequirements || '';
            document.getElementById('counselorSelect').value = lead.counselor || '';
            document.getElementById('counselingPriority').value = lead.counselingPriority || 'medium';
            document.getElementById('followupType').value = lead.followupType || 'phone';
            
            // Set selections
            currentLead.source = lead.source;
            currentLead.type = lead.type;
            currentLead.status = lead.status;
            currentLead.test = lead.testRequired || 'not_applicable';
            
            // Update UI selections
            document.querySelectorAll('.option-card.selected').forEach(card => card.classList.remove('selected'));
            document.querySelectorAll('.test-option.selected').forEach(card => card.classList.remove('selected'));
            
            document.querySelector(`[data-source="${lead.source}"]`).classList.add('selected');
            document.querySelector(`[data-type="${lead.type}"]`).classList.add('selected');
            document.querySelector(`[data-status="${lead.status}"]`).classList.add('selected');
            document.querySelector(`[data-test="${lead.testRequired || 'not_applicable'}"]`).classList.add('selected');
            
            // Show/hide appropriate sections
            const childSection = document.getElementById('childDetailsSection');
            const guardianSection = document.getElementById('guardianDetailsSection');
            const testDetails = document.getElementById('testDetailsSection');
            
            if (lead.type === 'parent') {
                childSection.style.display = 'block';
                guardianSection.style.display = 'none';
                document.getElementById('childName').value = lead.childName || '';
                document.getElementById('childDOB').value = lead.childDOB || '';
                document.getElementById('childGrade').value = lead.childGrade || '';
                document.getElementById('currentSchool').value = lead.currentSchool || '';
            } else if (lead.type === 'student') {
                childSection.style.display = 'none';
                guardianSection.style.display = 'block';
                document.getElementById('guardianName').value = lead.guardianName || '';
                document.getElementById('guardianPhone').value = lead.guardianPhone || '';
                document.getElementById('guardianRelation').value = lead.guardianRelation || 'father';
                document.getElementById('guardianOccupation').value = lead.guardianOccupation || '';
            } else {
                childSection.style.display = 'none';
                guardianSection.style.display = 'none';
            }
            
            // Show test details if applicable
            if (lead.testRequired === 'required') {
                testDetails.style.display = 'block';
                document.getElementById('testDateTime').value = lead.testDateTime || '';
                document.getElementById('testSubjects').value = lead.testSubjects || '';
            } else {
                testDetails.style.display = 'none';
            }
            
            // Switch to registration tab
            showTab('registration');
            
            // Change save button to update
            const saveBtn = document.querySelector('.btn-primary[onclick="saveLead()"]');
            saveBtn.innerHTML = '<i class="fas fa-save"></i> Update Lead';
            saveBtn.onclick = function() { updateLead(leadId); };
            
            // Add cancel edit button
            const cancelBtn = document.querySelector('.btn-secondary[onclick="resetForm()"]');
            cancelBtn.innerHTML = '<i class="fas fa-times"></i> Cancel Edit';
            cancelBtn.onclick = function() { 
                resetForm(); 
                const saveBtn = document.querySelector('.btn-primary[onclick="updateLead(\'' + leadId + '\')"]');
                saveBtn.innerHTML = '<i class="fas fa-save"></i> Generate Lead & Save';
                saveBtn.onclick = saveLead;
                cancelBtn.innerHTML = '<i class="fas fa-redo"></i> Clear Form';
                cancelBtn.onclick = resetForm;
            };
            
            showNotification('Edit mode: Updating lead ' + leadId);
        }

        function updateLead(leadId) {
            const index = leads.findIndex(l => l.id === leadId);
            if (index === -1) return;
            
            // Validate required fields
            const fullName = document.getElementById('fullName').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const followupDate = document.getElementById('followupDate').value;
            const counselor = document.getElementById('counselorSelect').value;
            
            if (!fullName || !phone || !followupDate || !counselor) {
                showNotification('Please fill in all required fields', 'error');
                return;
            }
            
            // Update lead
            leads[index].name = fullName;
            leads[index].phone = phone;
            leads[index].email = document.getElementById('email').value.trim();
            leads[index].address = document.getElementById('address').value.trim();
            leads[index].source = currentLead.source;
            leads[index].type = currentLead.type;
            leads[index].status = currentLead.status;
            leads[index].followupDate = followupDate;
            leads[index].followupType = document.getElementById('followupType').value;
            leads[index].counselor = counselor;
            leads[index].counselingPriority = document.getElementById('counselingPriority').value;
            leads[index].remarks = document.getElementById('remarks').value.trim();
            leads[index].specialRequirements = document.getElementById('specialRequirements').value.trim();
            leads[index].testRequired = currentLead.test;
            leads[index].testDateTime = currentLead.test === 'required' ? document.getElementById('testDateTime').value : null;
            leads[index].testSubjects = currentLead.test === 'required' ? document.getElementById('testSubjects').value : null;
            leads[index].updatedAt = new Date().toISOString();
            
            // Update child/guardian details
            if (currentLead.type === 'parent') {
                leads[index].childName = document.getElementById('childName').value.trim();
                leads[index].childDOB = document.getElementById('childDOB').value;
                leads[index].childGrade = document.getElementById('childGrade').value;
                leads[index].currentSchool = document.getElementById('currentSchool').value.trim();
            } else if (currentLead.type === 'student') {
                leads[index].guardianName = document.getElementById('guardianName').value.trim();
                leads[index].guardianPhone = document.getElementById('guardianPhone').value.trim();
                leads[index].guardianRelation = document.getElementById('guardianRelation').value;
                leads[index].guardianOccupation = document.getElementById('guardianOccupation').value.trim();
            }
            
            // Save and update UI
            saveLeadsToStorage();
            updateDashboard();
            renderRecentLeads();
            renderAllLeads();
            renderFollowups();
            
            // Reset form and buttons
            resetForm();
            const saveBtn = document.querySelector('.btn-primary[onclick="updateLead(\'' + leadId + '\')"]');
            saveBtn.innerHTML = '<i class="fas fa-save"></i> Generate Lead & Save';
            saveBtn.onclick = saveLead;
            const cancelBtn = document.querySelector('.btn-secondary[onclick="resetForm()"]');
            cancelBtn.innerHTML = '<i class="fas fa-redo"></i> Clear Form';
            cancelBtn.onclick = resetForm;
            
            showNotification('Lead updated successfully!');
            showTab('dashboard');
        }

        function deleteLead(leadId) {
            if (confirm(`Are you sure you want to delete lead ${leadId}? This action cannot be undone.`)) {
                leads = leads.filter(lead => lead.id !== leadId);
                saveLeadsToStorage();
                filteredLeads = filteredLeads.filter(lead => lead.id !== leadId);
                
                updateDashboard();
                renderRecentLeads();
                renderAllLeads();
                renderFollowups();
                updateCounselorStats();
                
                showNotification('Lead deleted successfully!');
            }
        }

        // ============================================
        // FOLLOW-UP MANAGEMENT
        // ============================================

        function renderFollowups() {
            const container = document.getElementById('followupList');
            if (!container) return;
            
            container.innerHTML = '';
            
            const today = new Date().toISOString().split('T')[0];
            const todayFollowups = leads.filter(lead => 
                lead.followupDate && lead.followupDate.startsWith(today)
            );
            
            // Get upcoming follow-ups (next 7 days)
            const nextWeek = new Date();
            nextWeek.setDate(nextWeek.getDate() + 7);
            const upcomingFollowups = leads.filter(lead => 
                lead.followupDate && 
                new Date(lead.followupDate) > new Date() && 
                new Date(lead.followupDate) <= nextWeek
            );
            
            let html = '';
            
            if (todayFollowups.length > 0) {
                html += `<h3 style="grid-column:1/-1; margin-bottom:15px;"><i class="fas fa-calendar-day"></i> Today's Follow-ups</h3>`;
                todayFollowups.forEach(lead => {
                    html += createFollowupCard(lead, 'today');
                });
            }
            
            if (upcomingFollowups.length > 0) {
                html += `<h3 style="grid-column:1/-1; margin-top:30px; margin-bottom:15px;"><i class="fas fa-calendar-week"></i> Upcoming Follow-ups (Next 7 Days)</h3>`;
                upcomingFollowups.forEach(lead => {
                    if (!todayFollowups.some(t => t.id === lead.id)) {
                        html += createFollowupCard(lead, 'upcoming');
                    }
                });
            }
            
            if (html === '') {
                html = '<div class="empty-state"><i class="fas fa-calendar"></i><p>No follow-ups scheduled</p></div>';
            }
            
            container.innerHTML = html;
        }

        function createFollowupCard(lead, type) {
            const statusClass = `status-${lead.status}`;
            const time = new Date(lead.followupDate).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            return `
                <div class="followup-card ${lead.status}">
                    <div style="display:flex; justify-content:space-between; align-items:start;">
                        <div>
                            <h4>${lead.name}</h4>
                            <p><i class="fas fa-phone"></i> ${lead.phone}</p>
                            ${lead.childName ? `<p><i class="fas fa-child"></i> ${lead.childName} (Grade ${lead.childGrade})</p>` : ''}
                        </div>
                        <div>
                            <span class="status-badge ${statusClass}" style="padding:5px 15px;">
                                ${lead.status}
                            </span>
                        </div>
                    </div>
                    <div style="margin-top:15px; padding-top:15px; border-top:1px solid #eee;">
                        <p><i class="fas fa-clock"></i> ${time} | ${lead.followupType || 'Phone Call'}</p>
                        <p><i class="fas fa-user-tie"></i> ${getCounselorName(lead.counselor)}</p>
                        <p><i class="fas fa-sticky-note"></i> ${lead.remarks || 'No remarks'}</p>
                    </div>
                    <div style="margin-top:15px; display:flex; gap:10px;">
                        <button class="btn btn-primary" style="flex:1;padding:8px 16px;" onclick="completeFollowup('${lead.id}')">
                            <i class="fas fa-check"></i> Complete
                        </button>
                        <button class="btn btn-secondary" style="flex:1;padding:8px 16px;" onclick="rescheduleFollowup('${lead.id}')">
                            <i class="fas fa-calendar-plus"></i> Reschedule
                        </button>
                    </div>
                </div>
            `;
        }

        function completeFollowup(leadId) {
            const lead = leads.find(l => l.id === leadId);
            if (lead) {
                const remark = prompt('Enter follow-up remarks:');
                if (remark !== null) {
                    lead.remarks = (lead.remarks || '') + '\n[Follow-up completed: ' + new Date().toLocaleString() + '] ' + remark;
                    lead.updatedAt = new Date().toISOString();
                    saveLeadsToStorage();
                    renderFollowups();
                    showNotification('Follow-up completed successfully!');
                }
            }
        }

        function rescheduleFollowup(leadId) {
            const lead = leads.find(l => l.id === leadId);
            if (lead) {
                const newDate = prompt('Enter new follow-up date and time (YYYY-MM-DDTHH:MM):', lead.followupDate);
                if (newDate) {
                    lead.followupDate = newDate;
                    lead.updatedAt = new Date().toISOString();
                    saveLeadsToStorage();
                    renderFollowups();
                    showNotification('Follow-up rescheduled successfully!');
                }
            }
        }

        // ============================================
        // COUNSELING MANAGEMENT
        // ============================================

        function updateCounselorStats() {
            const johnCount = leads.filter(lead => lead.counselor === 'john').length;
            const sarahCount = leads.filter(lead => lead.counselor === 'sarah').length;
            const mikeCount = leads.filter(lead => lead.counselor === 'mike').length;
            
            document.getElementById('counselorJohn').textContent = johnCount;
            document.getElementById('counselorSarah').textContent = sarahCount;
            document.getElementById('counselorMike').textContent = mikeCount;
        }

        function getCounselorName(counselorId) {
            const counselors = {
                'john': 'John Smith',
                'sarah': 'Sarah Johnson',
                'mike': 'Mike Wilson',
                'priya': 'Priya Patel',
                'rahul': 'Rahul Verma'
            };
            return counselors[counselorId] || counselorId || 'Not assigned';
        }

        function assignToCounselor(counselorId) {
            const unassignedLeads = leads.filter(l => !l.counselor);
            
            if (unassignedLeads.length === 0) {
                showNotification('No unassigned leads available.', 'error');
                return;
            }
            
            let options = 'Select a lead to assign:\n\n';
            unassignedLeads.forEach(lead => {
                options += `${lead.id} - ${lead.name} (${lead.type})\n`;
            });
            
            const leadId = prompt(options);
            
            if (leadId) {
                const lead = leads.find(l => l.id === leadId.trim());
                if (lead) {
                    lead.counselor = counselorId;
                    lead.updatedAt = new Date().toISOString();
                    saveLeadsToStorage();
                    updateDashboard();
                    renderAllLeads();
                    renderFollowups();
                    updateCounselorStats();
                    showNotification(`Lead ${leadId} assigned to ${getCounselorName(counselorId)} successfully!`);
                } else {
                    showNotification('Invalid lead ID.', 'error');
                }
            }
        }

        // ============================================
        // ADMISSION PROCESSING
        // ============================================

        function updateFeeBreakdown() {
            const courseSelect = document.getElementById('courseSelect');
            const discountInput = document.getElementById('discount');
            const course = courseSelect.value;
            const discount = parseFloat(discountInput.value) || 0;
            
            if (!course) {
                document.getElementById('feeBreakdown').innerHTML = '<p style="color: #64748b; text-align: center;">Please select a course to see fee details.</p>';
                return;
            }
            
            const fees = {
                'pre_kg': 25000,
                'primary': 35000,
                'middle': 45000,
                'high': 55000,
                'senior': 65000
            };
            
            const courseNames = {
                'pre_kg': 'Pre-KG Program',
                'primary': 'Primary School',
                'middle': 'Middle School',
                'high': 'High School',
                'senior': 'Senior Secondary'
            };
            
            const baseFee = fees[course];
            const discountAmount = (baseFee * discount) / 100;
            const discountedFee = baseFee - discountAmount;
            const gst = discountedFee * 0.18;
            const developmentFee = discountedFee * 0.1;
            const admissionFee = 5000;
            const examinationFee = 2000;
            const libraryFee = 1000;
            const total = discountedFee + gst + developmentFee + admissionFee + examinationFee + libraryFee;
            
            const breakdown = `
                <div class="fee-item">
                    <span>${courseNames[course]} Tuition Fee</span>
                    <span>₹${baseFee.toLocaleString()}</span>
                </div>
                ${discount > 0 ? `
                <div class="fee-item">
                    <span>Discount (${discount}%)</span>
                    <span>-₹${discountAmount.toLocaleString()}</span>
                </div>
                ` : ''}
                <div class="fee-item">
                    <span>Discounted Tuition Fee</span>
                    <span>₹${discountedFee.toLocaleString()}</span>
                </div>
                <div class="fee-item">
                    <span>GST (18%)</span>
                    <span>₹${gst.toLocaleString()}</span>
                </div>
                <div class="fee-item">
                    <span>Development Fee</span>
                    <span>₹${developmentFee.toLocaleString()}</span>
                </div>
                <div class="fee-item">
                    <span>Admission Fee</span>
                    <span>₹${admissionFee.toLocaleString()}</span>
                </div>
                <div class="fee-item">
                    <span>Examination Fee</span>
                    <span>₹${examinationFee.toLocaleString()}</span>
                </div>
                <div class="fee-item">
                    <span>Library Fee</span>
                    <span>₹${libraryFee.toLocaleString()}</span>
                </div>
                <div class="fee-total">
                    <span>Total Payable Amount</span>
                    <span>₹${total.toLocaleString()}</span>
                </div>
                ${discount > 0 ? `
                <div style="margin-top:10px; padding:10px; background:#dcfce7; border-radius:5px;">
                    <p style="margin:0; color:#166534;"><i class="fas fa-tag"></i> You save ₹${discountAmount.toLocaleString()} with ${discount}% discount!</p>
                </div>
                ` : ''}
            `;
            
            document.getElementById('feeBreakdown').innerHTML = breakdown;
        }

        function processAdmission() {
            const course = document.getElementById('courseSelect').value;
            if (!course) {
                showNotification('Please select a course first.', 'error');
                return;
            }
            
            const paymentMethod = document.getElementById('paymentMethod').value;
            const paymentDate = document.getElementById('paymentDate').value;
            
            showNotification(`Admission processed successfully! Course: ${course}, Payment Method: ${paymentMethod}`);
        }

        // ============================================
        // UTILITY FUNCTIONS
        // ============================================

        function exportData() {
            const dataStr = JSON.stringify(leads, null, 2);
            const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
            const exportFileDefaultName = `leads_export_${new Date().toISOString().split('T')[0]}.json`;
            
            const linkElement = document.createElement('a');
            linkElement.setAttribute('href', dataUri);
            linkElement.setAttribute('download', exportFileDefaultName);
            linkElement.click();
            
            showNotification('Data exported successfully!');
        }

    function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    const notificationTitle = document.getElementById('notification-title');
    const notificationText = document.getElementById('notification-text');
    const notificationTime = document.getElementById('notification-time');
    const notificationIcon = notification.querySelector('.notification-icon i');
    
    // Reset classes
    notification.className = 'notification';
    notification.classList.add(type === 'error' ? 'error' : 'success');
    
    // Set title
    notificationTitle.textContent = type === 'error' ? 'Error' : 'Success';
    
    // Set icon
    notificationIcon.className = type === 'error' ? 'fas fa-exclamation-circle' : 'fas fa-check-circle';
    
    // Set message
    notificationText.textContent = message;
    
    // Set time
    const now = new Date();
    notificationTime.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    
    // Remove any existing timeouts
    if (window.notificationTimeout) {
        clearTimeout(window.notificationTimeout);
    }
    
    // Show notification
    notification.classList.remove('hiding');
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    // Auto-hide after 4 seconds for success, 6 seconds for error
    const hideTime = type === 'error' ? 6000 : 4000;
    window.notificationTimeout = setTimeout(() => {
        hideNotification();
    }, hideTime);
}

function hideNotification() {
    const notification = document.getElementById('notification');
    notification.classList.add('hiding');
    
    // Remove show class after animation
    setTimeout(() => {
        notification.classList.remove('show', 'hiding');
    }, 300);
}

// Add click outside to close
document.addEventListener('click', function(event) {
    const notification = document.getElementById('notification');
    if (notification.classList.contains('show') && 
        !notification.contains(event.target)) {
        hideNotification();
    }
});
    </script>
@endsection
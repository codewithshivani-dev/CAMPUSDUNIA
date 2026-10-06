@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Building Infrastructure Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
            --primary-color: #4361ee;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #0ea5e9;
            --border-color: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --bg-light: #f8fafc;
        }

        * { box-sizing: border-box; }

        /* Dashboard Header */
        .dashboard-header {
            background: var(--primary-gradient);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .dashboard-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: white;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .dashboard-header h1 i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 12px;
        }

        .dashboard-header .subtitle {
            opacity: 0.9;
            font-size: 1rem;
            margin: 0;
        }

        .dashboard-header .header-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .header-btn {
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            background: rgba(255,255,255,0.15);
            color: white;
        }

        .header-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
            color: white;
        }

        .header-btn-primary {
            background: white;
            color: var(--primary-color);
        }

        .header-btn-primary:hover {
            background: #f0f4ff;
            color: var(--primary-color);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.25rem;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border-color: var(--primary-color);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .stat-icon.blue { background: #eff6ff; color: #2563eb; }
        .stat-icon.green { background: #ecfdf5; color: #059669; }
        .stat-icon.orange { background: #fffbeb; color: #d97706; }
        .stat-icon.purple { background: #f5f3ff; color: #7c3aed; }
        .stat-icon.red { background: #fef2f2; color: #dc2626; }
        .stat-icon.teal { background: #ecfdf5; color: #0d9488; }
        .stat-icon.pink { background: #fdf2f8; color: #db2777; }
        .stat-icon.indigo { background: #e0e7ff; color: #4f46e5; }

        .stat-info h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
            line-height: 1.2;
        }

        .stat-info p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin: 0;
            font-weight: 500;
        }

        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 992px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .card-header h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-header h3 i {
            color: var(--primary-color);
        }

        .card-header .badge-count {
            background: var(--primary-gradient);
            color: white;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.25rem 1.5rem;
            max-height: 450px;
            overflow-y: auto;
        }

        .card-body::-webkit-scrollbar { width: 4px; }
        .card-body::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 2px; }
        .card-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 2px; }

        .card-body .empty-state {
            text-align: center;
            padding: 2rem 0;
            color: var(--text-muted);
        }

        .card-body .empty-state i {
            font-size: 2.5rem;
            display: block;
            margin-bottom: 0.5rem;
            color: var(--border-color);
        }

        .card-body .empty-state a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .card-body .empty-state a:hover {
            text-decoration: underline;
        }

        /* Block Items */
        .block-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            margin-bottom: 0.5rem;
            transition: all 0.3s;
            border-left: 4px solid var(--border-color);
            background: var(--bg-light);
        }

        .block-item:last-child {
            margin-bottom: 0;
        }

        .block-item:hover {
            transform: translateX(4px);
        }

        .block-item.complete {
            border-left-color: var(--success-color);
        }

        .block-item.incomplete {
            border-left-color: var(--warning-color);
        }

        .block-item .block-info {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .block-item .block-info .block-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-gradient);
            color: white;
            font-size: 0.9rem;
        }

        .block-item .block-info .block-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .block-item .block-info .block-meta {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .block-item .block-status {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .block-item .block-status .status-badge {
            font-size: 0.7rem;
            padding: 3px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .block-item .block-status .status-badge.success {
            background: #dcfce7;
            color: #166534;
        }

        .block-item .block-status .status-badge.warning {
            background: #fef3c7;
            color: #92400e;
        }

        .block-item .block-status .status-badge.danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .block-item .block-status .status-badge.info {
            background: #dbeafe;
            color: #1e40af;
        }

        .block-item .block-stats {
            display: flex;
            gap: 8px;
            font-size: 0.7rem;
            color: var(--text-muted);
            white-space: nowrap;
        }

        .block-item .block-stats span {
            display: flex;
            align-items: center;
            gap: 3px;
            background: white;
            padding: 2px 8px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        /* Floor Items */
        .floor-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 1rem;
            border-radius: 10px;
            margin-bottom: 0.4rem;
            transition: all 0.3s;
            border-left: 4px solid var(--border-color);
            background: var(--bg-light);
        }

        .floor-item:last-child {
            margin-bottom: 0;
        }

        .floor-item:hover {
            transform: translateX(4px);
        }

        .floor-item.complete {
            border-left-color: var(--success-color);
        }

        .floor-item.incomplete {
            border-left-color: var(--warning-color);
        }

        .floor-item .floor-info {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }

        .floor-item .floor-info .floor-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-gradient);
            color: white;
            font-size: 0.8rem;
        }

        .floor-item .floor-info .floor-number {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.85rem;
        }

        .floor-item .floor-info .floor-block {
            font-size: 0.7rem;
            color: var(--text-muted);
            background: white;
            padding: 2px 10px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .floor-item .floor-status .status-badge {
            font-size: 0.65rem;
            padding: 2px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .floor-item .floor-status .status-badge.success {
            background: #dcfce7;
            color: #166534;
        }

        .floor-item .floor-status .status-badge.warning {
            background: #fef3c7;
            color: #92400e;
        }

        .floor-item .floor-stats {
            font-size: 0.7rem;
            color: var(--text-muted);
            white-space: nowrap;
        }

        .floor-item .floor-stats span {
            background: white;
            padding: 2px 8px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        /* Room Items */
        .room-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            margin-bottom: 0.3rem;
            transition: all 0.3s;
            background: var(--bg-light);
        }

        .room-item:last-child {
            margin-bottom: 0;
        }

        .room-item:hover {
            background: #f1f5f9;
        }

        .room-item .room-info {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            flex: 1;
        }

        .room-item .room-info .room-number {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.85rem;
        }

        .room-item .room-info .room-type {
            font-size: 0.65rem;
            padding: 2px 10px;
            border-radius: 12px;
            font-weight: 500;
        }

        .room-type.classroom { background: #dbeafe; color: #1e40af; }
        .room-type.lab { background: #d1fae5; color: #065f46; }
        .room-type.office { background: #fef3c7; color: #92400e; }
        .room-type.conference { background: #e0e7ff; color: #3730a3; }
        .room-type.library { background: #fce7f3; color: #831843; }
        .room-type.hotel { background: #fef3c7; color: #92400e; }
        .room-type.residential { background: #fce7f3; color: #9d174d; }
        .room-type.commercial { background: #e0e7ff; color: #3730a3; }
        .room-type.store_room { background: #f1f5f9; color: #475569; }
        .room-type.other { background: #f1f5f9; color: #475569; }

        .room-item .room-status {
            font-size: 0.65rem;
            padding: 2px 10px;
            border-radius: 12px;
            font-weight: 500;
        }

        .room-status.vacant { background: #dcfce7; color: #166534; }
        .room-status.occupied { background: #fee2e2; color: #991b1b; }
        .room-status.partially_occupied { background: #fef3c7; color: #92400e; }
        .room-status.maintenance { background: #fef3c7; color: #92400e; }
        .room-status.reserved { background: #dbeafe; color: #1e40af; }

        .room-item .room-actions {
            display: flex;
            gap: 4px;
        }

        .room-item .room-actions .action-btn {
            width: 28px;
            height: 28px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            transition: all 0.3s;
            text-decoration: none;
        }

        .room-item .room-actions .action-btn.view {
            background: #dbeafe;
            color: #1e40af;
        }

        .room-item .room-actions .action-btn.view:hover {
            background: #2563eb;
            color: white;
        }

        /* Progress Section */
        .progress-section {
            margin-top: 2rem;
        }

        .progress-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
        }

        .progress-card h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .progress-card h3 i {
            color: var(--primary-color);
        }

        .progress-bar {
            height: 12px;
            background: #f1f5f9;
            border-radius: 6px;
            overflow: hidden;
        }

        .progress-bar .progress-fill {
            height: 100%;
            background: var(--primary-gradient);
            border-radius: 6px;
            transition: width 0.5s ease;
        }

        .progress-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: var(--text-muted);
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .progress-stats strong {
            color: var(--text-dark);
        }

        /* Quick Links */
        .quick-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .quick-link {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: 2px solid var(--border-color);
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            background: white;
        }

        .quick-link:hover {
            border-color: var(--primary-color);
            background: #f8faff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.1);
            color: var(--text-dark);
            text-decoration: none;
        }

        .quick-link i {
            color: var(--primary-color);
            width: 20px;
            text-align: center;
        }

        .quick-link.success i { color: #10b981; }
        .quick-link.warning i { color: #f59e0b; }
        .quick-link.info i { color: #0ea5e9; }
        .quick-link.purple i { color: #7c3aed; }
        .quick-link.red i { color: #ef4444; }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                text-align: center;
            }

            .dashboard-header h1 {
                justify-content: center;
                font-size: 22px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stat-card {
                padding: 0.75rem;
            }

            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .stat-info h3 {
                font-size: 1.2rem;
            }

            .card-body {
                padding: 1rem;
                max-height: 300px;
            }

            .quick-links {
                grid-template-columns: repeat(2, 1fr);
            }

            .progress-card {
                padding: 1rem;
            }

            .block-item {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .block-item .block-status {
                width: 100%;
                justify-content: flex-start;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .quick-links {
                grid-template-columns: 1fr;
            }

            .room-info {
                flex-wrap: wrap;
            }
        }

        /* Animations */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.08); }
            100% { transform: scale(1); }
        }

        .stat-updated {
            animation: pulse 0.4s ease-in-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .block-item, .floor-item, .room-item {
            animation: slideIn 0.3s ease;
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--success-color);
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1001;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            max-width: 90%;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast.error {
            background: var(--danger-color);
        }

        .toast.info {
            background: var(--info-color);
        }

        /* Loading Spinner */
        .loading-text {
            color: var(--text-muted);
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .loading-text .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid var(--border-color);
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .completion-badge {
            font-size: 0.65rem;
            padding: 2px 10px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .completion-badge.complete { background: #dcfce7; color: #166534; }
        .completion-badge.partial { background: #fef3c7; color: #92400e; }
        .completion-badge.empty { background: #fee2e2; color: #991b1b; }

        .view-all-link {
            color: var(--primary-color);
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
        }

        .view-all-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div>
                <h1><i class="fas fa-chart-pie"></i> Infrastructure Dashboard</h1>
                <p class="subtitle"><i class="fas fa-university"></i> Complete overview of your campus building infrastructure</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('infrastructure.main') }}" class="header-btn header-btn-primary">
                    <i class="fas fa-cogs"></i> Setup
                </a>
                <button class="header-btn" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-university"></i></div>
                <div class="stat-info">
                    <h3 id="totalBuildings">0</h3>
                    <p>Buildings / Campuses</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-building"></i></div>
                <div class="stat-info">
                    <h3 id="totalBlocks">0</h3>
                    <p>Building Blocks</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-layer-group"></i></div>
                <div class="stat-info">
                    <h3 id="totalFloors">0</h3>
                    <p>Total Floors</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-door-open"></i></div>
                <div class="stat-info">
                    <h3 id="totalRooms">0</h3>
                    <p>Total Rooms</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon teal"><i class="fas fa-toilet"></i></div>
                <div class="stat-info">
                    <h3 id="totalWashrooms">0</h3>
                    <p>Washrooms</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon pink"><i class="fas fa-elevator"></i></div>
                <div class="stat-info">
                    <h3 id="totalLifts">0</h3>
                    <p>Lifts / Elevators</p>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Left Column: Blocks & Floors -->
            <div>
                <!-- Blocks Section -->
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header">
                        <h3><i class="fas fa-building"></i> Blocks with Floor Status</h3>
                        <span class="badge-count" id="blockBadge">0 blocks</span>
                    </div>
                    <div class="card-body" id="blocksList">
                        <div class="loading-text">
                            <span class="spinner"></span> Loading blocks...
                        </div>
                    </div>
                </div>

                <!-- Floors Section -->
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-layer-group"></i> Floors with Room Status</h3>
                        <span class="badge-count" id="floorBadge">0 floors</span>
                    </div>
                    <div class="card-body" id="floorsList">
                        <div class="loading-text">
                            <span class="spinner"></span> Loading floors...
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Rooms & Quick Links -->
            <div>
                <!-- Rooms Section -->
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header">
                        <h3><i class="fas fa-door-open"></i> Recent Rooms</h3>
                        <span class="badge-count" id="roomBadge">0 rooms</span>
                    </div>
                    <div class="card-body" id="roomsList">
                        <div class="loading-text">
                            <span class="spinner"></span> Loading rooms...
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-rocket"></i> Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="quick-links">
                            <a href="{{ route('buildings.page') }}" class="quick-link">
                                <i class="fas fa-university"></i> Buildings
                            </a>
                            <a href="{{ route('blocks.page') }}" class="quick-link success">
                                <i class="fas fa-building"></i> Blocks
                            </a>
                            <a href="{{ route('floors.page') }}" class="quick-link warning">
                                <i class="fas fa-layer-group"></i> Floors
                            </a>
                            <a href="{{ route('rooms.page') }}" class="quick-link info">
                                <i class="fas fa-door-open"></i> Rooms
                            </a>
                            <!--<a href="{{ route('rooms.list') }}" class="quick-link purple">-->
                            <!--    <i class="fas fa-list"></i> View All-->
                            <!--</a>-->
                            <!--<a href="#" class="quick-link red" onclick="showIncompleteItems()">-->
                            <!--    <i class="fas fa-exclamation-triangle"></i> Fix Incomplete-->
                            <!--</a>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Section -->
        <div class="progress-section">
            <div class="progress-card">
                <h3><i class="fas fa-tasks"></i> Setup Progress</h3>
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill" style="width: 0%;"></div>
                </div>
                <div class="progress-stats">
                    <span>Completion: <strong id="completionPercent">0%</strong></span>
                    <span>Steps Completed: <strong id="completedSteps">0 / 6</strong></span>
                    <span id="progressMessage" style="font-weight: 600;">Loading data...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toastMessage">Data refreshed!</span>
    </div>

    <script>
        const API_BASE_URL = '{{ url('/') }}';
        let allBlocks = [];
        let allFloors = [];
        let allRooms = [];

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            toastMessage.textContent = message;
            toast.className = 'toast' + (type === 'error' ? ' error' : (type === 'info' ? ' info' : ''));
            toast.querySelector('i').className = type === 'error' ? 'fas fa-exclamation-circle' : 
                                                type === 'info' ? 'fas fa-info-circle' : 'fas fa-check-circle';
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        function escapeHtml(text) {
            if (!text) return '';
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return String(text).replace(/[&<>"']/g, m => map[m]);
        }

        function updateStatWithAnimation(elementId, newValue) {
            const element = document.getElementById(elementId);
            if (element) {
                element.textContent = newValue;
                element.classList.remove('stat-updated');
                void element.offsetWidth;
                element.classList.add('stat-updated');
            }
        }

        // ===== FETCH DATA =====
        async function fetchAllData() {
            try {
                const [buildingsRes, blocksRes, floorsRes, roomsRes] = await Promise.all([
                    fetch(`${API_BASE_URL}/buildings?per_page=1000`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    }),
                    fetch(`${API_BASE_URL}/blocks?per_page=1000`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    }),
                    fetch(`${API_BASE_URL}/floors?per_page=1000`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    }),
                    fetch(`${API_BASE_URL}/rooms?per_page=1000`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    })
                ]);

                const buildingsResult = await buildingsRes.json();
                const blocksResult = await blocksRes.json();
                const floorsResult = await floorsRes.json();
                const roomsResult = await roomsRes.json();

                const buildings = buildingsResult.success ? (buildingsResult.data?.data || buildingsResult.data || []) : [];
                allBlocks = blocksResult.success ? (blocksResult.data?.data || blocksResult.data || []) : [];
                allFloors = floorsResult.success ? (floorsResult.data?.data || floorsResult.data || []) : [];
                allRooms = roomsResult.success ? (roomsResult.data?.data || roomsResult.data || []) : [];

                // Calculate washrooms and lifts from rooms
                let washroomCount = 0;
                let liftCount = 0;
                allRooms.forEach(room => {
                    const specs = room.room_specifications || {};
                    if (specs.has_washroom || room.has_washroom) washroomCount++;
                    if (specs.has_elevator || room.has_elevator || room.has_lift || room.has_lift === 'yes') liftCount++;
                });

                // Update dashboard
                updateDashboard(buildings, allBlocks, allFloors, allRooms, washroomCount, liftCount);
                showToast('Dashboard data refreshed!', 'info');
            } catch (error) {
                console.error('Error fetching data:', error);
                showToast('Error loading dashboard data. Check console for details.', 'error');
                document.getElementById('blocksList').innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Error loading data. Please refresh.</p>
                    </div>
                `;
                document.getElementById('floorsList').innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Error loading data. Please refresh.</p>
                    </div>
                `;
                document.getElementById('roomsList').innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Error loading data. Please refresh.</p>
                    </div>
                `;
            }
        }

        // ===== UPDATE DASHBOARD =====
        function updateDashboard(buildings, blocks, floors, rooms, washroomCount, liftCount) {
            updateStatWithAnimation('totalBuildings', buildings.length);
            updateStatWithAnimation('totalBlocks', blocks.length);
            updateStatWithAnimation('totalFloors', floors.length);
            updateStatWithAnimation('totalRooms', rooms.length);
            updateStatWithAnimation('totalWashrooms', washroomCount || 0);
            updateStatWithAnimation('totalLifts', liftCount || 0);

            document.getElementById('blockBadge').textContent = blocks.length + ' blocks';
            document.getElementById('floorBadge').textContent = floors.length + ' floors';
            document.getElementById('roomBadge').textContent = rooms.length + ' rooms';

            renderBlocksWithStatus(blocks, floors);
            renderFloorsWithStatus(floors, rooms);
            renderRooms(rooms);
            updateProgress(buildings, blocks, floors, rooms, washroomCount, liftCount);
        }

        // ===== RENDER BLOCKS WITH FLOOR STATUS =====
        function renderBlocksWithStatus(blocks, floors) {
            const container = document.getElementById('blocksList');
            if (!blocks || blocks.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-building"></i>
                        <p>No blocks added yet. <a href="{{ route('blocks.page') }}">Add your first block</a></p>
                    </div>
                `;
                return;
            }

            // Group floors by block_id
            const floorsByBlock = {};
            floors.forEach(floor => {
                const blockId = floor.block_id || floor.block?.id;
                if (blockId) {
                    if (!floorsByBlock[blockId]) floorsByBlock[blockId] = [];
                    floorsByBlock[blockId].push(floor);
                }
            });

            const colors = ['#4361ee', '#10b981', '#f59e0b', '#ef4444', '#7c3aed', '#0ea5e9', '#db2777', '#0d9488', '#f97316', '#8b5cf6'];
            const displayBlocks = blocks.slice(0, 8);

            let html = '';
            displayBlocks.forEach((block, index) => {
                const blockId = block.id;
                const blockFloors = floorsByBlock[blockId] || [];
                const floorCount = blockFloors.length;
                const expectedFloors = block.total_floors || block.floors || 1;
                const isComplete = floorCount >= expectedFloors;
                const hasSomeFloors = floorCount > 0;
                const color = colors[index % colors.length];
                const blockName = block.name || `Block ${index + 1}`;
                const buildingName = block.building ? block.building.name : (block.building_name || 'N/A');

                let statusClass = 'incomplete';
                let statusText = 'No Floors';
                let statusBadgeClass = 'danger';
                let statusIcon = 'fa-times-circle';

                if (isComplete) {
                    statusClass = 'complete';
                    statusText = '✅ Complete';
                    statusBadgeClass = 'success';
                    statusIcon = 'fa-check-circle';
                } else if (hasSomeFloors) {
                    statusClass = 'incomplete';
                    statusText = `⚠️ ${floorCount}/${expectedFloors} Floors`;
                    statusBadgeClass = 'warning';
                    statusIcon = 'fa-exclamation-triangle';
                }

                html += `
                    <div class="block-item ${statusClass}">
                        <div class="block-info">
                            <div class="block-icon" style="background: ${color};">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <div class="block-name">${escapeHtml(blockName)}</div>
                                <div class="block-meta">${escapeHtml(buildingName)} • ${floorCount} floors</div>
                            </div>
                        </div>
                        <div class="block-status">
                            <span class="status-badge ${statusBadgeClass}">
                                <i class="fas ${statusIcon}"></i> ${statusText}
                            </span>
                            <div class="block-stats">
                                <span><i class="fas fa-layer-group"></i> ${floorCount}/${expectedFloors}</span>
                            </div>
                        </div>
                    </div>
                `;
            });

            if (blocks.length > 8) {
                html += `
                    <div style="text-align:center;padding-top:0.75rem;color:var(--text-muted);font-size:0.85rem;">
                        <i class="fas fa-ellipsis-h"></i> +${blocks.length - 8} more blocks
                        <a href="{{ route('blocks.page') }}" class="view-all-link" style="margin-left:8px;">View All</a>
                    </div>
                `;
            }

            container.innerHTML = html;
        }

        // ===== RENDER FLOORS WITH ROOM STATUS =====
        function renderFloorsWithStatus(floors, rooms) {
            const container = document.getElementById('floorsList');
            if (!floors || floors.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-layer-group"></i>
                        <p>No floors added yet. <a href="{{ route('floors.page') }}">Add your first floor</a></p>
                    </div>
                `;
                return;
            }

            // Group rooms by floor_id
            const roomsByFloor = {};
            rooms.forEach(room => {
                const floorId = room.floor_id || room.floor?.id;
                if (floorId) {
                    if (!roomsByFloor[floorId]) roomsByFloor[floorId] = [];
                    roomsByFloor[floorId].push(room);
                }
            });

            const displayFloors = floors.slice(0, 8);

            let html = '';
            displayFloors.forEach((floor) => {
                const floorId = floor.id;
                const floorRooms = roomsByFloor[floorId] || [];
                const roomCount = floorRooms.length;
                const expectedRooms = floor.total_rooms || floor.rooms || 1;
                const isComplete = roomCount >= expectedRooms;
                const hasSomeRooms = roomCount > 0;

                const floorNumber = floor.floor_number || floor.name || `Floor ${floor.id}`;
                const blockName = floor.block ? floor.block.name : (floor.block_name || 'Unknown Block');

                let statusClass = 'incomplete';
                let statusText = 'No Rooms';
                let statusBadgeClass = 'danger';
                let statusIcon = 'fa-times-circle';

                if (isComplete) {
                    statusClass = 'complete';
                    statusText = '✅ Complete';
                    statusBadgeClass = 'success';
                    statusIcon = 'fa-check-circle';
                } else if (hasSomeRooms) {
                    statusClass = 'incomplete';
                    statusText = `⚠️ ${roomCount}/${expectedRooms} Rooms`;
                    statusBadgeClass = 'warning';
                    statusIcon = 'fa-exclamation-triangle';
                }

                html += `
                    <div class="floor-item ${statusClass}">
                        <div class="floor-info">
                            <div class="floor-icon">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div>
                                <span class="floor-number">${escapeHtml(floorNumber)}</span>
                                <span class="floor-block">${escapeHtml(blockName)}</span>
                            </div>
                        </div>
                        <div class="floor-status">
                            <span class="status-badge ${statusBadgeClass}">
                                <i class="fas ${statusIcon}"></i> ${statusText}
                            </span>
                            <div class="floor-stats">
                                <span><i class="fas fa-door-open"></i> ${roomCount}/${expectedRooms}</span>
                            </div>
                        </div>
                    </div>
                `;
            });

            if (floors.length > 8) {
                html += `
                    <div style="text-align:center;padding-top:0.75rem;color:var(--text-muted);font-size:0.85rem;">
                        <i class="fas fa-ellipsis-h"></i> +${floors.length - 8} more floors
                        <a href="{{ route('floors.page') }}" class="view-all-link" style="margin-left:8px;">View All</a>
                    </div>
                `;
            }

            container.innerHTML = html;
        }

        // ===== RENDER ROOMS =====
        function renderRooms(rooms) {
            const container = document.getElementById('roomsList');
            if (!rooms || rooms.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-door-open"></i>
                        <p>No rooms added yet. <a href="{{ route('rooms.page') }}">Add your first room</a></p>
                    </div>
                `;
                return;
            }

            const displayRooms = rooms.slice(0, 8);

            let html = '';
            displayRooms.forEach((room) => {
                const roomNumber = room.room_number || `Room ${room.id}`;
                const roomName = room.room_name || '';
                const roomType = room.room_type || 'other';
                const customType = room.custom_room_type || '';
                const displayType = roomType === 'other' && customType ? customType : roomType;
                const status = room.occupancy_status || 'vacant';

                const typeClass = ['classroom', 'lab', 'office', 'conference', 'library', 'hotel', 'residential', 'commercial', 'store_room', 'other'].includes(roomType) ? roomType : 'other';
                const typeLabel = displayType.charAt(0).toUpperCase() + displayType.slice(1);
                const statusDisplay = status === 'partially_occupied' ? 'partially_occupied' : status;

                const floorName = room.floor ? room.floor.floor_number : (room.floor_name || '');
                const blockName = room.block ? room.block.name : (room.block_name || '');

                html += `
                    <div class="room-item">
                        <div class="room-info">
                            <i class="fas fa-door-open" style="color:var(--primary-color);"></i>
                            <div>
                                <span class="room-number">${escapeHtml(roomNumber)}</span>
                                ${roomName ? `<span style="font-size:0.8rem;color:var(--text-muted);"> - ${escapeHtml(roomName)}</span>` : ''}
                                <span class="room-type ${typeClass}">${escapeHtml(typeLabel)}</span>
                                <span class="room-status ${statusDisplay}">${escapeHtml(statusDisplay)}</span>
                                ${floorName ? `<span style="font-size:0.65rem;color:var(--text-muted);">📍 ${escapeHtml(floorName)}</span>` : ''}
                            </div>
                        </div>
                        <div class="room-actions">
                            <a href="/rooms/${room.id}/edit" class="action-btn view" title="Edit Room">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                `;
            });

            if (rooms.length > 8) {
                html += `
                    <div style="text-align:center;padding-top:0.75rem;color:var(--text-muted);font-size:0.85rem;">
                        <i class="fas fa-ellipsis-h"></i> +${rooms.length - 8} more rooms
                        <a href="{{ route('rooms.list') }}" class="view-all-link" style="margin-left:8px;">View All</a>
                    </div>
                `;
            }

            container.innerHTML = html;
        }

        // ===== SHOW INCOMPLETE ITEMS =====
        function showIncompleteItems() {
            let incompleteBlocks = [];
            let incompleteFloors = [];

            // Find blocks without enough floors
            const floorsByBlock = {};
            allFloors.forEach(floor => {
                const blockId = floor.block_id || floor.block?.id;
                if (blockId) {
                    if (!floorsByBlock[blockId]) floorsByBlock[blockId] = [];
                    floorsByBlock[blockId].push(floor);
                }
            });

            allBlocks.forEach(block => {
                const blockId = block.id;
                const blockFloors = floorsByBlock[blockId] || [];
                const expectedFloors = block.total_floors || block.floors || 1;
                if (blockFloors.length < expectedFloors) {
                    incompleteBlocks.push({
                        name: block.name || `Block ${blockId}`,
                        current: blockFloors.length,
                        expected: expectedFloors,
                        building: block.building ? block.building.name : 'N/A'
                    });
                }
            });

            // Find floors without enough rooms
            const roomsByFloor = {};
            allRooms.forEach(room => {
                const floorId = room.floor_id || room.floor?.id;
                if (floorId) {
                    if (!roomsByFloor[floorId]) roomsByFloor[floorId] = [];
                    roomsByFloor[floorId].push(room);
                }
            });

            allFloors.forEach(floor => {
                const floorId = floor.id;
                const floorRooms = roomsByFloor[floorId] || [];
                const expectedRooms = floor.total_rooms || floor.rooms || 1;
                if (floorRooms.length < expectedRooms) {
                    incompleteFloors.push({
                        name: floor.floor_number || `Floor ${floorId}`,
                        current: floorRooms.length,
                        expected: expectedRooms,
                        block: floor.block ? floor.block.name : 'N/A'
                    });
                }
            });

            let message = '';
            if (incompleteBlocks.length === 0 && incompleteFloors.length === 0) {
                message = '🎉 All blocks and floors are complete!';
                showToast(message, 'success');
                return;
            }

            if (incompleteBlocks.length > 0) {
                message += `\n📌 Blocks needing floors:\n`;
                incompleteBlocks.forEach(b => {
                    message += `   • ${b.name} (${b.building}) - ${b.current}/${b.expected} floors\n`;
                });
            }

            if (incompleteFloors.length > 0) {
                message += `\n📌 Floors needing rooms:\n`;
                incompleteFloors.forEach(f => {
                    message += `   • ${f.name} (${f.block}) - ${f.current}/${f.expected} rooms\n`;
                });
            }

            alert(message);
        }

        // ===== UPDATE PROGRESS =====
        function updateProgress(buildings, blocks, floors, rooms, washroomCount, liftCount) {
            const totalSteps = 6;
            let completedSteps = 0;

            if (buildings && buildings.length > 0) completedSteps++;
            if (blocks && blocks.length > 0) completedSteps++;
            if (floors && floors.length > 0) completedSteps++;
            if (rooms && rooms.length > 0) completedSteps++;
            if (washroomCount && washroomCount > 0) completedSteps++;
            if (liftCount && liftCount > 0) completedSteps++;

            const percentage = Math.round((completedSteps / totalSteps) * 100);

            const progressFill = document.getElementById('progressFill');
            progressFill.style.width = percentage + '%';

            document.getElementById('completionPercent').textContent = percentage + '%';
            document.getElementById('completedSteps').textContent = completedSteps + ' / ' + totalSteps;

            const message = document.getElementById('progressMessage');
            if (percentage === 0) {
                message.textContent = '🚀 Start building your infrastructure!';
                message.style.color = 'var(--text-muted)';
            } else if (percentage < 33) {
                message.textContent = '📝 Getting started... Keep adding!';
                message.style.color = 'var(--text-muted)';
            } else if (percentage < 66) {
                message.textContent = '🔄 You\'re making good progress!';
                message.style.color = '#f59e0b';
            } else if (percentage < 100) {
                message.textContent = '🎯 Almost there! Complete all steps.';
                message.style.color = '#0ea5e9';
            } else {
                message.textContent = '🎉 Congratulations! Infrastructure setup complete!';
                message.style.color = '#10b981';
            }
        }

        // ===== REFRESH DATA =====
        function refreshData() {
            const btn = document.querySelector('.header-btn .fa-sync-alt');
            if (btn) {
                btn.classList.add('fa-spin');
            }
            fetchAllData().finally(() => {
                if (btn) {
                    btn.classList.remove('fa-spin');
                }
            });
        }

        // ===== INITIALIZATION =====
        document.addEventListener('DOMContentLoaded', function() {
            fetchAllData();

            setInterval(() => {
                fetchAllData();
            }, 30000);

            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    fetchAllData();
                }
            });
        });
    </script>
</body>
</html>
@endsection
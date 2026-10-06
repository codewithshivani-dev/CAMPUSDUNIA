@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Configuration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-gradient: linear-gradient(135deg, #10b981, #059669);
            --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
            --border-color: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        /* Header */
        .header {
            background: var(--primary-gradient);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            width: 100%
        }

        .header-content h1 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.5rem;
        }

        .header-content h1 i {
            background: rgba(255, 255, 255, 0.2);
            padding: 10px;
            border-radius: 12px;
            font-size: 1.3rem;
        }

        .header-content p {
            opacity: 0.9;
            font-size: 1rem;
            margin: 0;
        }

        /* Form Card */
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: var(--card-shadow);
            border: 2px solid var(--border-color);
        }

        .form-card h3 {
            color: var(--text-dark);
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
            font-weight: 700;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .form-card h3 i {
            color: var(--primary-color);
            font-size: 1.4rem;
        }

        /* Section */
        .config-section {
            margin-bottom: 2rem;
        }

        .config-section-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .config-section-title i {
            color: var(--primary-color);
            font-size: 0.9rem;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-label .required {
            color: #dc3545;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s;
            background: #f8fafc;
            color: var(--text-dark);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
            background: white;
        }

        .form-hint {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.5rem;
        }

        /* Toggle Switches Grid */
        .toggle-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1rem;
        }

        .toggle-card {
            background: #f8fafc;
            border: 2px solid var(--border-color);
            border-radius: 14px;
            padding: 1.25rem;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .toggle-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.1);
            transform: translateY(-2px);
            background: white;
        }

        .toggle-card.selected {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, #f0f4ff, #e8edff);
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.15);
        }

        .toggle-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .toggle-icon.default {
            background: #f1f5f9;
            color: #64748b;
        }

        .toggle-icon.active {
            background: var(--primary-gradient);
            color: white;
        }

        .toggle-info {
            flex: 1;
        }

        .toggle-info h4 {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .toggle-info p {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.4;
        }

        .toggle-checkbox {
            display: none;
        }

        .toggle-indicator {
            width: 24px;
            height: 24px;
            border-radius: 8px;
            border: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.3s ease;
            background: white;
        }

        .toggle-card.selected .toggle-indicator {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .toggle-card.selected .toggle-indicator::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: white;
            font-size: 0.7rem;
        }

        /* Costing Method Selector */
        .costing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.75rem;
        }

        .costing-option {
            padding: 1rem 1.25rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .costing-option i {
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .costing-option:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.1);
        }

        .costing-option.selected {
            background: var(--primary-gradient);
            color: white;
            border-color: var(--primary-color);
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        .costing-option.selected i {
            color: white;
        }

        .costing-radio {
            display: none;
        }

        /* ============================================
           COSTING INFO ACCORDION - WITH HIGHLIGHT ANIMATION
           ============================================ */
        .costing-info-wrapper {
            margin-top: 1.5rem;
            position: relative;
        }

        /* Floating emoji indicator */
        .costing-info-wrapper::before {
            content: '💡';
            position: absolute;
            top: -12px;
            left: -8px;
            font-size: 1.2rem;
            z-index: 2;
            animation: float-emoji 3s ease-in-out infinite;
        }

        @keyframes float-emoji {
            0%, 100% { transform: translateY(0) rotate(-5deg); }
            50% { transform: translateY(-5px) rotate(5deg); }
        }

        /* Main toggle button - HIGHLY VISIBLE */
        .costing-info-toggle {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 5px 5px;
            background: linear-gradient(135deg, #fff5f5, #fff0f0);
            border-radius: 14px;
            color: #dc3545;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            user-select: none;
            position: relative;
            animation: important-pulse 2s ease-in-out infinite;
            box-shadow: 
                0 0 0 0 rgba(220, 53, 69, 0.3),
                0 8px 25px rgba(220, 53, 69, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            width: 100%;
            justify-content: center;
            letter-spacing: 0.3px;
            border: 3px solid #dc3545;
        }

        @keyframes important-pulse {
            0% {
                box-shadow: 
                    0 0 0 0 rgba(220, 53, 69, 0.3),
                    0 8px 25px rgba(220, 53, 69, 0.15);
                transform: scale(1);
                border-color: #dc3545;
            }
            50% {
                box-shadow: 
                    0 0 0 8px rgba(220, 53, 69, 0.08),
                    0 8px 30px rgba(220, 53, 69, 0.25);
                transform: scale(1.01);
                border-color: #ff4757;
            }
            100% {
                box-shadow: 
                    0 0 0 0 rgba(220, 53, 69, 0.3),
                    0 8px 25px rgba(220, 53, 69, 0.15);
                transform: scale(1);
                border-color: #dc3545;
            }
        }

        /* Book emoji with bounce */
        .costing-info-toggle::before {
            content: '📖';
            font-size: 1.3rem;
            animation: bounce-book 2s ease-in-out infinite;
        }

        @keyframes bounce-book {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-3px) rotate(-5deg); }
        }

        /* "KNOW MORE" tag */
        .costing-info-toggle::after {
            content: '⚠️ Know More';
            position: absolute;
            top: -12px;
            right: -10px;
            background: linear-gradient(135deg, #dc3545, #ff4757);
            color: white;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 0.6rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
            animation: blink-tag 2s ease-in-out infinite;
            text-transform: uppercase;
        }

        @keyframes blink-tag {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.05); }
        }

        .costing-info-toggle:hover {
            transform: scale(1.03) translateY(-3px);
            background: linear-gradient(135deg, #fff0f0, #ffe8e8);
            border-color: #ff4757;
            box-shadow: 
                0 0 0 6px rgba(220, 53, 69, 0.12),
                0 12px 40px rgba(220, 53, 69, 0.3);
            animation-play-state: paused;
        }

        /* Active state (when expanded) */
        .costing-info-toggle.active {
            background: linear-gradient(135deg, #dc3545, #ff4757);
            color: white;
            border-color: #dc3545;
            animation: none;
            box-shadow: 0 8px 30px rgba(220, 53, 69, 0.3);
        }

        .costing-info-toggle.active::before {
            content: '✅';
            animation: none;
        }

        .costing-info-toggle.active::after {
            content: 'CLOSE';
            background: rgba(255, 255, 255, 0.2);
            box-shadow: none;
            animation: none;
        }

        .costing-info-toggle.active:hover {
            transform: scale(1.02) translateY(-2px);
            background: linear-gradient(135deg, #ff4757, #dc3545);
        }

        .costing-info-toggle i {
            font-size: 1rem;
            transition: transform 0.3s ease;
            color: #dc3545;
        }

        .costing-info-toggle.active i {
            color: white;
            transform: rotate(180deg);
        }

        .costing-info-toggle span {
            font-weight: 700;
            color: #dc3545;
            font-size: 0.95rem;
        }

        .costing-info-toggle.active span {
            color: white;
        }

        /* Content styling */
        .costing-info-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.4s ease, padding 0.4s ease;
            opacity: 0;
            padding: 0 0.5rem;
        }

        .costing-info-content.open {
            max-height: 1200px;
            opacity: 1;
            padding: 1.5rem 0.5rem 0.2rem 0.5rem;
        }

        .costing-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
        }

        .costing-info-card {
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.2rem 1.5rem;
            transition: all 0.3s ease;
        }

        .costing-info-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.08);
            transform: translateY(-2px);
        }

        .costing-info-card h5 {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .costing-info-card h5 i {
            color: var(--primary-color);
            font-size: 0.9rem;
        }

        .costing-info-card .method-badge {
            display: inline-block;
            background: var(--primary-gradient);
            color: white;
            padding: 0.15rem 0.7rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            margin-left: 4px;
        }

        .costing-info-card p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin: 0.3rem 0;
            line-height: 1.5;
        }

        .costing-info-card .example {
            font-size: 0.78rem;
            color: var(--text-dark);
            background: white;
            padding: 0.5rem 0.8rem;
            border-radius: 6px;
            margin-top: 0.5rem;
            border-left: 3px solid var(--primary-color);
        }

        /* Buttons */
        .form-actions {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--border-color);
        }

        .btn {
            padding: 0.85rem 2rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: var(--text-dark);
            border: 2px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        .loading-spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--success-gradient);
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1001;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast.error {
            background: var(--danger-gradient);
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
            }

            .header-content h1 {
                justify-content: center;
            }

            .form-card {
                padding: 1.5rem;
            }

            .toggle-grid {
                grid-template-columns: 1fr;
            }

            .costing-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .costing-info-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .costing-info-toggle {
                padding: 0.8rem 1.2rem;
                font-size: 0.85rem;
                flex-wrap: wrap;
                gap: 8px;
            }

            .costing-info-toggle::after {
                position: static;
                font-size: 0.5rem;
                padding: 2px 8px;
                margin-left: 4px;
            }

            .costing-info-toggle::before {
                font-size: 1rem;
            }
        }
    </style>

    <div class="container-fluid page-container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <div>
                    <h1><i class="fas fa-cogs"></i> Create Configuration</h1>
                    <p>Configure your inventory management settings</p>
                </div>
                <div>
                    <a href="{{ route('inventory.dashboard') }}" class="btn btn-light" style="background: rgba(255,255,255,0.2); color: white; padding: 0.5rem 1.5rem; border-radius: 12px; font-weight: 600; text-decoration: none; border: none;">
                        <i class="fas fa-arrow-left"></i>
                        Back to Dashboard
                    </a>
                    <a href="{{ route('inventory.configuration') }}" class="btn btn-light" style="background: rgba(255,255,255,0.2); color: white; padding: 0.5rem 1.5rem; border-radius: 12px; font-weight: 600; text-decoration: none; border: none;">
                        <i class="fas fa-list"></i>
                        View Configurations
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <h3><i class="fas fa-sliders-h"></i> Configuration Settings</h3>

            <form method="POST" action="{{ route('inventory.configuration.store') }}" id="configForm">
                @csrf

                <!-- Configuration Name -->
                <div class="config-section">
                    <div class="form-group">
                        <label class="form-label">
                            Configuration Name <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            name="configuration_name"
                            class="form-control"
                            placeholder="e.g., School Inventory, IT Assets, Library Inventory"
                            required
                        >
                        <div class="form-hint">
                            Give your inventory configuration a meaningful name.
                        </div>
                        @error('configuration_name')
                            <div class="text-danger" style="font-size: 0.85rem; margin-top: 0.5rem;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="config-section-title">
                        <i class="fas fa-calculator"></i> Costing Method
                    </div>
                    <div class="costing-grid">
                        <label class="costing-option selected" data-value="FIFO" onclick="selectCostingMethod('FIFO', this)">
                            <i class="fas fa-layer-group"></i> FIFO
                        </label>
                        <label class="costing-option" data-value="LIFO" onclick="selectCostingMethod('LIFO', this)">
                            <i class="fas fa-layer-group fa-flip-vertical"></i> LIFO
                        </label>
                        <label class="costing-option" data-value="WEIGHTED_AVERAGE" onclick="selectCostingMethod('WEIGHTED_AVERAGE', this)">
                            <i class="fas fa-balance-scale"></i> Weighted Average
                        </label>
                    </div>
                    <input type="hidden" name="costing_method" id="costingMethodInput" value="FIFO">
                    @error('costing_method')
                        <div class="text-danger" style="font-size: 0.85rem; margin-top: 0.5rem;">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror

                    <!-- Costing Info Toggle - WITH HIGHLIGHT ANIMATION -->
                    <div class="costing-info-wrapper">
                        <div class="costing-info-toggle" onclick="toggleCostingInfo()">
                            <i class="fas fa-chevron-down"></i>
                            <span>📖 Learn about costing methods</span>
                        </div>
                        <div class="costing-info-content" id="costingInfoContent">
                            <div class="costing-info-grid">
                                <!-- FIFO -->
                                <div class="costing-info-card">
                                    <h5><i class="fas fa-layer-group"></i> FIFO <span class="method-badge">Recommended</span></h5>
                                    <p><strong>First In, First Out</strong> — The oldest items in your stock are the first ones to be sold or used.</p>
                                    <div class="example">📦 Example: You buy 100 pens at ₹10 and later 100 pens at ₹15. When you sell, the first 100 pens cost ₹10 each.</div>
                                </div>

                                <!-- LIFO -->
                                <div class="costing-info-card">
                                    <h5><i class="fas fa-layer-group fa-flip-vertical"></i> LIFO</h5>
                                    <p><strong>Last In, First Out</strong> — The newest items in your stock are the first ones to be sold or used.</p>
                                    <div class="example">📦 Example: You buy 100 pens at ₹10 and later 100 pens at ₹15. When you sell, the first 100 pens cost ₹15 each.</div>
                                </div>

                                <!-- Weighted Average -->
                                <div class="costing-info-card">
                                    <h5><i class="fas fa-balance-scale"></i> Weighted Average</h5>
                                    <p><strong>Average Cost</strong> — All items are valued at a single average cost.</p>
                                    <div class="example">📦 Example: You buy 100 pens at ₹10 and 100 pens at ₹15. The average cost is ₹12.50. All pens sold cost ₹12.50 each.</div>
                                </div>
                            </div>
                            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 1rem; text-align: center;">
                                <i class="fas fa-lightbulb" style="color: #f59e0b;"></i>
                                <strong>Tip:</strong> FIFO is most commonly used as it matches how inventory typically flows.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feature Toggles Section -->
                <div class="config-section">
                    <div class="config-section-title">
                        <i class="fas fa-toggle-on"></i> Features
                    </div>
                    <div class="toggle-grid">
                        <!-- Multi Warehouse -->
                        <label class="toggle-card" onclick="toggleCard(this)">
                            <div class="toggle-icon default" id="icon-multi_warehouse">
                                <i class="fas fa-warehouse"></i>
                            </div>
                            <div class="toggle-info">
                                <h4>Multi Warehouse</h4>
                                <p>Manage inventory across multiple warehouse locations</p>
                            </div>
                            <div class="toggle-indicator"></div>
                            <input type="checkbox" name="multi_warehouse" value="1" class="toggle-checkbox">
                        </label>

                        <!-- Barcode Enabled -->
                        <label class="toggle-card" onclick="toggleCard(this)">
                            <div class="toggle-icon default" id="icon-barcode_enabled">
                                <i class="fas fa-barcode"></i>
                            </div>
                            <div class="toggle-info">
                                <h4>Barcode Enabled</h4>
                                <p>Use barcode scanning for inventory operations</p>
                            </div>
                            <div class="toggle-indicator"></div>
                            <input type="checkbox" name="barcode_enabled" value="1" class="toggle-checkbox">
                        </label>

                        <!-- QR Enabled -->
                        <label class="toggle-card" onclick="toggleCard(this)">
                            <div class="toggle-icon default" id="icon-qr_enabled">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <div class="toggle-info">
                                <h4>QR Enabled</h4>
                                <p>Use QR codes for quick inventory identification</p>
                            </div>
                            <div class="toggle-indicator"></div>
                            <input type="checkbox" name="qr_enabled" value="1" class="toggle-checkbox">
                        </label>

                        <!-- Batch Tracking -->
                        <label class="toggle-card" onclick="toggleCard(this)">
                            <div class="toggle-icon default" id="icon-batch_tracking">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div class="toggle-info">
                                <h4>Batch Tracking</h4>
                                <p>Track inventory by batch or lot numbers</p>
                            </div>
                            <div class="toggle-indicator"></div>
                            <input type="checkbox" name="batch_tracking" value="1" class="toggle-checkbox">
                        </label>

                        <!-- Serial Tracking -->
                        <label class="toggle-card" onclick="toggleCard(this)">
                            <div class="toggle-icon default" id="icon-serial_tracking">
                                <i class="fas fa-hashtag"></i>
                            </div>
                            <div class="toggle-info">
                                <h4>Serial Tracking</h4>
                                <p>Track individual items by serial numbers</p>
                            </div>
                            <div class="toggle-indicator"></div>
                            <input type="checkbox" name="serial_tracking" value="1" class="toggle-checkbox">
                        </label>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <div>
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('inventory.configuration') }}'">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i> Save Configuration
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message"></span>
    </div>

    <script>
        // Costing method selection
        function selectCostingMethod(value, element) {
            document.querySelectorAll('.costing-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            element.classList.add('selected');
            document.getElementById('costingMethodInput').value = value;
        }

        // Toggle costing info
        function toggleCostingInfo() {
            const content = document.getElementById('costingInfoContent');
            const toggle = document.querySelector('.costing-info-toggle');
            content.classList.toggle('open');
            toggle.classList.toggle('active');
        }

        // Toggle card selection
        function toggleCard(card) {
            const checkbox = card.querySelector('.toggle-checkbox');
            const iconDiv = card.querySelector('.toggle-icon');

            checkbox.checked = !checkbox.checked;

            if (checkbox.checked) {
                card.classList.add('selected');
                iconDiv.classList.remove('default');
                iconDiv.classList.add('active');
            } else {
                card.classList.remove('selected');
                iconDiv.classList.remove('active');
                iconDiv.classList.add('default');
            }
        }

        // Reset form to default
        function resetForm() {
            // Reset costing method
            document.querySelectorAll('.costing-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            const defaultCosting = document.querySelector('.costing-option[data-value="FIFO"]');
            if (defaultCosting) {
                defaultCosting.classList.add('selected');
            }
            document.getElementById('costingMethodInput').value = 'FIFO';

            // Reset all toggles
            document.querySelectorAll('.toggle-card').forEach(card => {
                const checkbox = card.querySelector('.toggle-checkbox');
                const iconDiv = card.querySelector('.toggle-icon');

                checkbox.checked = false;
                card.classList.remove('selected');
                iconDiv.classList.remove('active');
                iconDiv.classList.add('default');
            });

            // Reset configuration name
            document.querySelector('input[name="configuration_name"]').value = '';

            showToast('Form reset to default values');
        }

        // Form submission with loading state
        document.getElementById('configForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const originalHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="loading-spinner"></span> Saving...';
            submitBtn.disabled = true;

            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.innerHTML = originalHTML;
                    submitBtn.disabled = false;
                }
            }, 3000);
        });

        // Toast notification
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');

            toastMessage.textContent = message;
            toast.classList.remove('error');

            if (type === 'error') {
                toast.classList.add('error');
                toast.querySelector('i').className = 'fas fa-exclamation-circle';
            } else {
                toast.querySelector('i').className = 'fas fa-check-circle';
            }

            toast.classList.add('show');

            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        @if(session('success'))
            showToast('{{ session('success') }}');
        @endif

        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
    </script>
@endsection
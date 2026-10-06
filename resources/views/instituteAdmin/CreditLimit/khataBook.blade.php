@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khata Book - Digital Ledger & Wallet</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Your existing CSS styles remain the same */
        :root {
            --primary: #6C63FF;
            --primary-light: #8B85FF;
            --primary-dark: #5651D2;
            --secondary: #858796;
            --success: #4CAF50;
            --warning: #FF9800;
            --danger: #F44336;
            --info: #00BCD4;
            --light: #f8f9fc;
            --dark: #2e3a59;
            --gradient: linear-gradient(135deg, #6C63FF 0%, #4A44C6 100%);
            --gradient-success: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%);
            --gradient-danger: linear-gradient(135deg, #F44336 0%, #D32F2F 100%);
            --gradient-warning: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            --gradient-info: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%);
            --gradient-purple: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%);
            --gradient-orange: linear-gradient(135deg, #FF5722 0%, #E64A19 100%);
        }
        
        .app-container {
            margin: 0 auto;
            background-color: white;
            min-height: 100vh; 
            box-shadow: 0 0 35px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow-x: hidden;
            animation: slideInUp 0.6s ease-out;
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
               transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .header {
            background: var(--gradient);
            color: white;
            padding: 25px 25px;
            border-radius: 0 0 30px 30px;
            box-shadow: 0 10px 30px rgba(108, 99, 255, 0.3);
            position: relative;
            z-index: 10;
            animation: headerSlideDown 0.8s ease-out;
            overflow: hidden;
        }
        
        @keyframes headerSlideDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }
        
        .header::after {
            content: '';
            position: absolute;
            bottom: -60%;
            left: -20%;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            animation: float 10s ease-in-out infinite reverse;
        }
        
        /* Wallet Card Styles */
        .wallet-card {
            background: var(--gradient);
            color: white;
            border-radius: 25px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 15px 35px rgba(108, 99, 255, 0.3);
            position: relative;
            overflow: hidden;
            animation: cardAppear 0.6s ease-out 0.1s both;
            transition: all 0.4s ease;
        }
        
        .wallet-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(108, 99, 255, 0.4);
        }
        
        .wallet-card.low-balance {
            background: var(--gradient-warning);
        }
        
        .wallet-card.zero-balance {
            background: var(--gradient-danger);
        }
        
        .wallet-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        .wallet-card::after {
            content: '';
            position: absolute;
            bottom: -60%;
            left: -20%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
        }
        
        .wallet-actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }
        
        .wallet-btn {
            flex: 1;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 15px;
            padding: 14px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 70px;
        }
        
        .wallet-btn:hover:not(:disabled) {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .wallet-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        
        .add-money-btn {
            background: rgba(255, 255, 255, 0.3);
            border: 2px dashed rgba(255, 255, 255, 0.5);
        }
        
        .add-money-btn:hover {
            background: rgba(255, 255, 255, 0.4);
            border-color: rgba(255, 255, 255, 0.7);
        }
        
        /* Category Cards */
        .category-card {
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            color: white;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            animation: cardAppear 0.5s ease-out;
            animation-fill-mode: both;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .category-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .category-card:hover::before {
            opacity: 1;
        }
        
        .category-spent {
            background: var(--gradient-danger);
        }
        
        .category-received {
            background: var(--gradient-success);
        }
        
        .category-lend {
            background: var(--gradient-info);
        }
        
        .category-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            opacity: 0.9;
        }
        
        /* Balance Alert */
        .balance-alert {
            background: rgba(244, 67, 54, 0.1);
            border: 1px solid rgba(244, 67, 54, 0.2);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            animation: fadeIn 0.5s ease-out;
            backdrop-filter: blur(5px);
        }
        
        .balance-alert i {
            color: var(--danger);
            font-size: 2rem;
            margin-bottom: 15px;
        }
        
        /* Transaction Items */
        .transaction-item {
            border-left: 5px solid;
            padding: 18px 20px;
            margin-bottom: 15px;
            background: white;
            border-radius: 0 15px 15px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: slideInRight 0.5s ease-out;
            animation-fill-mode: both;
            position: relative;
            overflow: hidden;
        }
        
        .transaction-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(108, 99, 255, 0.03) 0%, rgba(108, 99, 255, 0) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .transaction-item.spent {
            border-left-color: var(--danger);
        }
        
        .transaction-item.received {
            border-left-color: var(--success);
        }
        
        .transaction-item.lend {
            border-left-color: var(--info);
        }
        
        .transaction-item:hover {
            transform: translateX(8px) translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .transaction-item:hover::before {
            opacity: 1;
        }
        
        .transaction-type-badge {
            font-size: 0.75rem;
            padding: 5px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        
        .type-spent {
            background-color: rgba(244, 67, 54, 0.15);
            color: var(--danger);
            border: 1px solid rgba(244, 67, 54, 0.3);
        }
        
        .type-received {
            background-color: rgba(76, 175, 80, 0.15);
            color: var(--success);
            border: 1px solid rgba(76, 175, 80, 0.3);
        }
        
        .type-lend {
            background-color: rgba(0, 188, 212, 0.15);
            color: var(--info);
            border: 1px solid rgba(0, 188, 212, 0.3);
        }
        
        /* Customer Card Styles */
        .customer-card {
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            margin-bottom: 20px;
            border: none;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            animation: cardAppear 0.5s ease-out;
            animation-fill-mode: both;
            position: relative;
        }
        
        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .customer-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .customer-card-header {
            background: var(--gradient);
            color: white;
            padding: 18px 20px;
            font-weight: 600;
            position: relative;
            overflow: hidden;
        }
        
        .customer-card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.6s ease;
            opacity: 0;
        }
        
        .customer-card:hover .customer-card-header::before {
            animation: shimmer 1.5s ease;
        }
        
        @keyframes shimmer {
            0% {
                transform: translateX(-100%) translateY(-100%) rotate(45deg);
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
            100% {
                transform: translateX(100%) translateY(100%) rotate(45deg);
                opacity: 0;
            }
        }
        
        .amount-positive {
            color: var(--success);
            font-weight: bold;
        }
        
        .amount-negative {
            color: var(--danger);
            font-weight: bold;
        }
        
        .amount-neutral {
            color: var(--info);
            font-weight: bold;
        }
        
        .nav-tabs {
            border-bottom: 2px solid #e3e6f0;
            padding: 0 20px;
            animation: fadeIn 0.8s ease-out 0.2s both;
            background: white;
            border-radius: 15px 15px 0 0;
            margin: 0 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: var(--secondary);
            font-weight: 600;
            padding: 18px 25px;
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: center;
            border-radius: 10px 10px 0 0;
        }
        
        a:hover {
            text-decoration: none !important;
        }
        
        .nav-tabs .nav-link:hover {
            color: var(--primary);
            text-decoration: none !important;
            transform: translateY(-2px);
            background: rgba(108, 99, 255, 0.05);
        }
        
        .nav-tabs .nav-link.active {
            background: transparent;
            color: var(--primary);
            text-decoration: none;
        }
        
        .nav-tabs .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--primary);
            border-radius: 3px 3px 0 0;
            animation: tabUnderline 0.3s ease-out;
            text-decoration: none;
        }
        
        @keyframes tabUnderline {
            from {
                transform: scaleX(0);
            }
            to {
                transform: scaleX(1);
            }
        }
        
        .btn-primary {
            background: var(--gradient);
            border: none;
            border-radius: 15px;
            padding: 14px 28px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(108, 99, 255, 0.3);
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.7s ease;
        }
        
        .btn-primary:hover::before {
            left: 100%;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 25px rgba(108, 99, 255, 0.4);
        }
        
        .btn-danger {
            background: var(--gradient-danger);
            border: none;
            border-radius: 15px;
            padding: 14px 28px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(244, 67, 54, 0.3);
        }
        
        .btn-danger::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.7s ease;
        }
        
        .btn-danger:hover::before {
            left: 100%;
        }
        
        .btn-danger:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 25px rgba(244, 67, 54, 0.4);
        }
        
        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 75px;
            height: 75px;
            border-radius: 50%;
            background: var(--gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            box-shadow: 0 8px 25px rgba(108, 99, 255, 0.5);
            z-index: 100;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: pulse 2s infinite, bounceIn 1s ease-out;
            border: none;
        }
        
        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .floating-btn:hover {
            transform: scale(1.15) rotate(90deg);
            box-shadow: 0 12px 35px rgba(108, 99, 255, 0.7);
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 8px 25px rgba(108, 99, 255, 0.5);
            }
            50% {
                box-shadow: 0 8px 30px rgba(108, 99, 255, 0.8);
            }
            100% {
                box-shadow: 0 8px 25px rgba(108, 99, 255, 0.5);
            }
        }
        
        /* Customer Specific Styles */
        .customer-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 20px;
            background: var(--gradient);
            border-radius: 20px;
            color: white;
            box-shadow: 0 10px 25px rgba(108, 99, 255, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .customer-stats::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }
        
        .customer-stat-item {
            text-align: center;
            flex: 1;
            position: relative;
            z-index: 2;
        }
        
        .customer-stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .customer-stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .quick-actions {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
        }
        
        .quick-action-btn {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 15px;
            background: var(--light);
            color: var(--dark);
            font-weight: 600;
            transition: all 0.3s ease;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 80px;
        }
        
        .quick-action-btn:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(108, 99, 255, 0.3);
        }
        
        .customer-transaction-item {
            border-left: 5px solid var(--info);
            padding: 15px 20px;
            margin-bottom: 12px;
            background: white;
            border-radius: 0 15px 15px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .customer-transaction-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 188, 212, 0.03) 0%, rgba(0, 188, 212, 0) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .customer-transaction-item:hover {
            transform: translateX(5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .customer-transaction-item:hover::before {
            opacity: 1;
        }
        
        .transaction-amount.positive {
            color: var(--success);
            font-weight: bold;
        }
        
        .transaction-amount.negative {
            color: var(--danger);
            font-weight: bold;
        }
        
        .customer-detail-section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--dark);
            border-bottom: 2px solid var(--primary-light);
            padding-bottom: 8px;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 50px;
            height: 2px;
            background: var(--primary);
        }
        
        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            text-align: center;
            margin-bottom: 20px;
            border-left: 5px solid var(--primary);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: cardAppear 0.5s ease-out;
            animation-fill-mode: both;
            position: relative;
            overflow: hidden;
        }
        
        .stats-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .customer-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 28px;
            box-shadow: 0 8px 25px rgba(108, 99, 255, 0.4);
            transition: all 0.4s ease;
            animation: bounceIn 1s ease-out;
            margin: 0 auto 20px;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: var(--secondary);
            animation: fadeIn 1s ease-out;
        }
        
        .empty-state i {
            font-size: 5rem;
            margin-bottom: 25px;
            color: #d1d3e2;
            animation: bounce 2s infinite;
        }
        
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .success-animation {
            animation: successPulse 1s;
        }
        
        .error-animation {
            animation: errorShake 0.5s;
        }
        
        @keyframes successPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(76, 175, 80, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(76, 175, 80, 0);
            }
        }
        
        @keyframes errorShake {
            0%, 100% {
                transform: translateX(0);
            }
            10%, 30%, 50%, 70%, 90% {
                transform: translateX(-5px);
            }
            20%, 40%, 60%, 80% {
                transform: translateX(5px);
            }
        }
        
        /* Modal Enhancements */
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .modal-header {
            background: var(--gradient);
            color: white;
            border-bottom: none;
            padding: 25px;
        }
        
        .modal-body {
            padding: 25px;
        }
        
        .modal-footer {
            border-top: 1px solid #eaeaea;
            padding: 20px 25px;
        }
        
        .form-control {
            border-radius: 12px;
            padding: 6px 15px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(108, 99, 255, 0.25);
        }
        
        .btn-group .btn {
            border-radius: 10px;
            margin: 0 5px;
        }
        
        /* Search Box */
        .search-box {
            position: relative;
        }
        
        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary);
        }
        
        .search-box input {
            padding-left: 45px;
            border-radius: 12px;
        }
        
        /* Badge Custom */
        .badge-custom {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 0.8rem;
        }
        
        /* Settings Button */
        .settings-btn {
            position: absolute;
            top: 10px;
            right: 25px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 11;
        }
        
        .settings-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }
        
        /* Enhanced scrollbar */
        /*::-webkit-scrollbar {*/
        /*    width: 8px;*/
        /*}*/
        
        /*::-webkit-scrollbar-track {*/
        /*    background: #f1f1f1;*/
        /*    border-radius: 10px;*/
        /*}*/
        
        /*::-webkit-scrollbar-thumb {*/
        /*    background: var(--primary);*/
        /*    border-radius: 10px;*/
        /*}*/
        
        /*::-webkit-scrollbar-thumb:hover {*/
        /*    background: var(--primary-dark);*/
        /*}*/
        
        /* Animation for floating elements */
        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-10px) rotate(5deg);
            }
            100% {
                transform: translateY(0) rotate(0deg);
            }
        }
        
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .wallet-actions {
                flex-direction: column;
            }
            
            .quick-actions {
                flex-direction: column;
            }
            
            .customer-stats {
                flex-direction: column;
                gap: 15px;
            }
            
            .nav-tabs .nav-link {
                padding: 15px;
            }
        }
        
        /* FIXED: Modal Backdrop and Loading State */
        .modal-backdrop {
            opacity: 0.5 !important;
            background-color: #000 !important;
        }
        
        .modal-backdrop.show {
            opacity: 0.5 !important;
        }
        
        .modal-backdrop.fade {
            opacity: 0 !important;
        }
        
        .modal-backdrop.fade.show {
            opacity: 0.5 !important;
        }
        
        /* Loading State Fix */
        .btn {
            position: relative;
        }
        
        .btn .btn-text,
        .btn .loading-spinner {
            transition: opacity 0.2s ease;
        }
        
        .btn.loading .btn-text {
            opacity: 0;
            visibility: hidden;
        }
        
        .btn.loading .loading-spinner {
            display: inline-block !important;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            opacity: 1;
            visibility: visible;
        }
        
        /* Ensure modal close button is visible */
        .modal-header .close {
            color: white !important;
            opacity: 0.8;
            text-shadow: none;
        }
        
        .modal-header .close:hover {
            opacity: 1;
            color: white !important;
        }
        
        /* Fix for disabled buttons */
        .btn:disabled {
            opacity: 0.65 !important;
        }
        
        /* Ensure body doesn't scroll when modal is open */
        body.modal-open {
            overflow: hidden;
            padding-right: 0 !important;
        }
        
        /* Fix for modal stacking */
        .modal {
            z-index: 1060;
        }
        
        .modal-backdrop {
            z-index: 1050;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <div class="header position-relative">
            <button class="settings-btn" data-bs-toggle="modal" data-target="#settingsModal">
                <i class="fas fa-cog"></i>
            </button>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1 font-weight-bold" data-i18n="app_title">Khata Book</h3>
                    <p class="mb-0 opacity-75" data-i18n="app_subtitle">Digital Ledger & Wallet Manager</p>
                </div>
                <div class="language-selector" style="margin-top:40px;">
                    <select class="form-control form-control-sm" id="languageSelect" style="border-radius: 10px; background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);padding:2px 15px !important;">
                        <option value="en">English</option>
                        <option value="hi">हिन्दी</option>
                        <option value="gu">ગુજરાતી</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Wallet Section -->
        <div class="container mt-4">
            <div class="row">
                <div class="col-12">
                    <div class="wallet-card position-relative" id="walletCard">
                        <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 2;">
                            <div>
                                <p class="mb-1 opacity-75" data-i18n="wallet_balance">Wallet Balance</p>
                                <h2 class="mb-0 font-weight-bold" id="walletBalance">₹0</h2>
                                <small class="opacity-75" id="walletLastUpdated">Last updated: Just now</small>
                            </div>
                            <div class="text-right">
                                <i class="fas fa-wallet" style="font-size: 3.5rem; opacity: 0.8;"></i>
                            </div>
                        </div>
                        
                        <!-- Balance Alert for Zero Balance -->
                        <div class="balance-alert" id="zeroBalanceAlert" style="display: none;">
                            <i class="fas fa-exclamation-circle"></i>
                            <h6 data-i18n="zero_balance_alert">Add money to your wallet to start transactions</h6>
                            <p class="mb-0" data-i18n="zero_balance_message">You need to add balance first to use Spent and Received features</p>
                        </div>
                        
                        <div class="wallet-actions">
                            <button class="wallet-btn add-money-btn" data-bs-toggle="modal" data-target="#addMoneyModal">
                                <i class="fas fa-plus-circle mb-2" style="font-size: 1.5rem;"></i>
                                <span data-i18n="add_money">Add Money</span>
                            </button>
                            <button class="wallet-btn" id="spentBtn" data-bs-toggle="modal" data-target="#addTransactionModal" data-type="spent" disabled>
                                <i class="fas fa-money-bill-wave mb-2" style="font-size: 1.5rem;"></i>
                                <span data-i18n="spent">Spent</span>
                            </button>
                            <button class="wallet-btn" id="receivedBtn" data-bs-toggle="modal" data-target="#addTransactionModal" data-type="received" disabled>
                                <i class="fas fa-download mb-2" style="font-size: 1.5rem;"></i>
                                <span data-i18n="received">Received</span>
                            </button>
                            <button class="wallet-btn" id="lendBtn" data-bs-toggle="modal" data-target="#addCustomerModal">
                                <i class="fas fa-hand-holding-usd mb-2" style="font-size: 1.5rem;"></i>
                                <span data-i18n="lend">Lend</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Categories Overview -->
            <div class="row mb-3">
                <div class="col-4">
                    <div class="category-card category-spent" data-category="spent">
                        <div class="category-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <h5 class="mb-1" id="totalSpent">₹0</h5>
                        <small class="opacity-90" data-i18n="total_spent">Total Spent</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="category-card category-received" data-category="received">
                        <div class="category-icon">
                            <i class="fas fa-download"></i>
                        </div>
                        <h5 class="mb-1" id="totalReceived">₹0</h5>
                        <small class="opacity-90" data-i18n="total_received">Total Received</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="category-card category-lend" data-category="lend">
                        <div class="category-icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h5 class="mb-1" id="totalLend">₹0</h5>
                        <small class="opacity-90" data-i18n="total_lend">Credit Given</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="container mt-3">
            <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="transactions-tab" data-bs-toggle="tab" href="#transactions" role="tab" aria-controls="transactions" aria-selected="true">
                        <i class="fas fa-exchange-alt mr-2"></i> <span data-i18n="transactions">Transactions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="customers-tab" data-bs-toggle="tab" href="#customers" role="tab" aria-controls="customers" aria-selected="false">
                        <i class="fas fa-users mr-2"></i> <span data-i18n="customers">Customers</span>
                        <span class="badge badge-light ml-1" id="customersCount">0</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="reports-tab" data-bs-toggle="tab" href="#reports" role="tab" aria-controls="reports" aria-selected="false">
                        <i class="fas fa-chart-bar mr-2"></i> <span data-i18n="reports">Reports</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div class="tab-content p-3" id="myTabContent">
            <!-- Transactions Tab -->
            <div class="tab-pane fade show active" id="transactions" role="tabpanel" aria-labelledby="transactions-tab">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="section-title" data-i18n="recent_transactions">Recent Transactions</h5>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" class="form-control" placeholder="Search transactions..." id="searchTransactions" data-i18n-placeholder="search_transactions">
                    </div>
                </div>
                
                <div id="transactionsList">
                    <!-- Transactions will be dynamically added here -->
                </div>
                
                <div class="empty-state" id="emptyTransactionState">
                    <i class="fas fa-exchange-alt"></i>
                    <h5 data-i18n="no_transactions_found">No Transactions Found</h5>
                    <p data-i18n="add_first_transaction">Add your first transaction to get started</p>
                </div>
            </div>

            <!-- Customers Tab -->
            <div class="tab-pane fade" id="customers" role="tabpanel" aria-labelledby="customers-tab">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="section-title" data-i18n="customer_list">Customer List</h5>
                    <div class="d-flex align-items-center">
                        <div class="search-box w-100 me-3">
                            <i class="fas fa-search"></i>
                            <input type="text" class="form-control" placeholder="Search customer..." id="searchCustomer" data-i18n-placeholder="search_customer">
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-target="#addCustomerModal" style="width:270px;">
                            <i class="fas fa-plus mr-2"></i> <span data-i18n="add_customer">Add Customer</span>
                        </button>
                    </div>
                </div>
                
                <!-- Customer Stats -->
                <div class="customer-stats">
                    <div class="customer-stat-item">
                        <div class="customer-stat-value" id="totalCustomersCount">0</div>
                        <div class="customer-stat-label" data-i18n="total_customers">Total Customers</div>
                    </div>
                    <div class="customer-stat-item">
                        <div class="customer-stat-value" id="activeCustomersCount">0</div>
                        <div class="customer-stat-label" data-i18n="active_customers">Active</div>
                    </div>
                    <div class="customer-stat-item">
                        <div class="customer-stat-value" id="totalCreditGiven">₹0</div>
                        <div class="customer-stat-label" data-i18n="total_credit_given">Credit Given</div>
                    </div>
                </div>
                
                <div id="customerList">
                    <!-- Customer cards will be dynamically added here -->
                </div>
                
                <div class="empty-state" id="emptyCustomerState">
                    <i class="fas fa-users"></i>
                    <h5 data-i18n="no_customers_found">No Customers Found</h5>
                    <p data-i18n="add_first_customer">Add your first customer to get started</p>
                </div>
            </div>

            <!-- Reports Tab -->
            <div class="tab-pane fade" id="reports" role="tabpanel" aria-labelledby="reports-tab">
                <h5 class="section-title" data-i18n="business_reports">Business Reports</h5>
                
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title font-weight-bold" data-i18n="monthly_summary">Monthly Summary</h6>
                        <canvas id="monthlyChart" height="150"></canvas>
                    </div>
                </div>
                
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title font-weight-bold" data-i18n="category_breakdown">Category Breakdown</h6>
                        <canvas id="categoryChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Action Button -->
        <button class="floating-btn" data-bs-toggle="modal" data-target="#addMoneyModal">
            <i class="fas fa-plus"></i>
        </button>
    </div>

    <!-- Settings Modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="settingsModalLabel"><i class="fas fa-cog mr-2"></i> Settings</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="btn-close-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-database mb-3" style="font-size: 3rem; color: var(--primary);"></i>
                        <h5>Data Management</h5>
                        <p class="text-muted">Manage your application data and settings</p>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Warning:</strong> Clearing data will permanently delete all your transactions, customers, and wallet balance. This action cannot be undone.
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Clear All Data</label>
                        <p class="text-muted small">This will reset the application to its initial state</p>
                        <button class="btn btn-danger w-100" id="clearDataBtn">
                            <i class="fas fa-trash-alt mr-2"></i> Clear All Data
                        </button>
                    </div>
                    
                    <div class="form-group mt-4">
                        <label class="form-label">Export Data</label>
                        <p class="text-muted small">Download a backup of your data</p>
                        <button class="btn btn-outline-primary w-100" id="exportDataBtn">
                            <i class="fas fa-download mr-2"></i> Export Data
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Money Modal -->
    <div class="modal fade" id="addMoneyModal" tabindex="-1" aria-labelledby="addMoneyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMoneyModalLabel" data-i18n="add_money_to_wallet">Add Money to Wallet</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="btn-close-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addMoneyForm">
                        <div class="form-group">
                            <label for="addAmount" class="form-label" data-i18n="amount_to_add">Amount to Add (₹)</label>
                            <input type="number" class="form-control" id="addAmount" required min="1" data-i18n-placeholder="enter_amount">
                        </div>
                        <div class="form-group">
                            <label for="addMoneyDescription" class="form-label" data-i18n="description">Description</label>
                            <textarea class="form-control" id="addMoneyDescription" rows="2" data-i18n-placeholder="enter_description">Initial wallet deposit</textarea>
                        </div>
                        <div class="form-group">
                            <label for="addMoneySource" class="form-label" data-i18n="source">Source</label>
                            <select class="form-control" id="addMoneySource">
                                <option value="cash" data-i18n="cash">Cash</option>
                                <option value="bank" data-i18n="bank">Bank Transfer</option>
                                <option value="upi" data-i18n="upi">UPI</option>
                                <option value="debit_card" data-i18n="debit_card">Debit Card</option>
                                <option value="credit_card" data-i18n="credit_card">Credit Card</option>
                                <option value="other" data-i18n="other">Other</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-i18n="cancel">Cancel</button>
                    <button type="button" class="btn btn-success" id="saveAddMoneyBtn">
                        <span class="btn-text" data-i18n="add_money">Add Money</span>
                        <span class="loading-spinner"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Transaction Modal -->
    <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTransactionModalLabel" data-i18n="add_transaction">Add Transaction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="btn-close-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addTransactionForm">
                        <div class="form-group">
                            <label class="form-label" data-i18n="transaction_type">Transaction Type</label>
                            <div class="btn-group btn-group-toggle w-100" data-bs-toggle="buttons">
                                <label class="btn btn-outline-danger active">
                                    <input type="radio" name="transactionType" value="spent" checked> 
                                    <i class="fas fa-money-bill-wave mr-2"></i> <span data-i18n="spent">Spent</span>
                                </label>
                                <label class="btn btn-outline-success">
                                    <input type="radio" name="transactionType" value="received"> 
                                    <i class="fas fa-download mr-2"></i> <span data-i18n="received">Received</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transactionAmount" class="form-label" data-i18n="amount">Amount (₹)</label>
                            <input type="number" class="form-control" id="transactionAmount" required min="1" data-i18n-placeholder="enter_amount">
                            <small class="form-text text-muted" id="balanceCheckMessage"></small>
                        </div>
                        <div class="form-group">
                            <label for="transactionDate" class="form-label" data-i18n="date">Date</label>
                            <input type="date" class="form-control" id="transactionDate" required>
                        </div>
                        <div class="form-group">
                            <label for="transactionDescription" class="form-label" data-i18n="description">Description</label>
                            <textarea class="form-control" id="transactionDescription" rows="2" data-i18n-placeholder="enter_description"></textarea>
                        </div>
                        <!-- Updated Category Field with Payment Methods -->
                        <div class="form-group">
                            <label for="transactionCategory" class="form-label" data-i18n="category">Category / Payment Method</label>
                            <select class="form-control" id="transactionCategory">
                                <!-- Common categories for both spent and received -->
                                <optgroup label="Payment Methods" id="paymentMethodsGroup">
                                    <option value="cash" data-i18n="cash">Cash</option>
                                    <option value="upi" data-i18n="upi">UPI</option>
                                    <option value="debit_card" data-i18n="debit_card">Debit Card</option>
                                    <option value="credit_card" data-i18n="credit_card">Credit Card</option>
                                    <option value="bank_transfer" data-i18n="bank_transfer">Bank Transfer</option>
                                    <option value="online_payment" data-i18n="online_payment">Online Payment</option>
                                </optgroup>
                                <!-- Expense specific categories -->
                                <optgroup label="Expense Categories" id="expenseCategoriesGroup">
                                    <option value="food" data-i18n="food">Food</option>
                                    <option value="shopping" data-i18n="shopping">Shopping</option>
                                    <option value="transport" data-i18n="transport">Transport</option>
                                    <option value="entertainment" data-i18n="entertainment">Entertainment</option>
                                    <option value="bills" data-i18n="bills">Bills</option>
                                    <option value="medical" data-i18n="medical">Medical</option>
                                    <option value="education" data-i18n="education">Education</option>
                                    <option value="personal_care" data-i18n="personal_care">Personal Care</option>
                                </optgroup>
                                <!-- Income specific categories -->
                                <optgroup label="Income Categories" id="incomeCategoriesGroup">
                                    <option value="salary" data-i18n="salary">Salary</option>
                                    <option value="business" data-i18n="business">Business</option>
                                    <option value="freelance" data-i18n="freelance">Freelance</option>
                                    <option value="investment" data-i18n="investment">Investment</option>
                                    <option value="gift" data-i18n="gift">Gift</option>
                                    <option value="refund" data-i18n="refund">Refund</option>
                                </optgroup>
                                <option value="other" data-i18n="other">Other</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-i18n="cancel">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveTransactionBtn">
                        <span class="btn-text" data-i18n="save_transaction">Save Transaction</span>
                        <span class="loading-spinner"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Customer Modal -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerModalLabel" data-i18n="add_new_customer">Add New Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="btn-close-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addCustomerForm">
                        <div class="form-group">
                            <label for="customerName" class="form-label" data-i18n="customer_name">Customer Name</label>
                            <input type="text" class="form-control" id="customerName" required data-i18n-placeholder="enter_customer_name">
                        </div>
                        <div class="form-group">
                            <label for="customerPhone" class="form-label" data-i18n="phone_number">Phone Number</label>
                            <input type="tel" class="form-control" id="customerPhone" data-i18n-placeholder="enter_phone_number">
                        </div>
                        <div class="form-group">
                            <label for="customerAddress" class="form-label" data-i18n="address">Address</label>
                            <textarea class="form-control" id="customerAddress" rows="2" data-i18n-placeholder="enter_address"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="initialAmount" class="form-label" data-i18n="initial_amount">Initial Amount (₹)</label>
                            <input type="number" class="form-control" id="initialAmount" value="0" min="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label" data-i18n="transaction_type">Transaction Type</label>
                            <div class="btn-group btn-group-toggle w-100" data-bs-toggle="buttons">
                                <label class="btn btn-outline-info active">
                                    <input type="radio" name="customerTransactionType" value="give" checked> 
                                    <i class="fas fa-hand-holding-usd mr-2"></i> <span data-i18n="you_will_give">You will give</span>
                                </label>
                                <label class="btn btn-outline-success">
                                    <input type="radio" name="customerTransactionType" value="take"> 
                                    <i class="fas fa-hand-holding mr-2"></i> <span data-i18n="you_will_take">You will take</span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-i18n="cancel">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveCustomerBtn">
                        <span class="btn-text" data-i18n="save_customer">Save Customer</span>
                        <span class="loading-spinner"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Details Modal -->
    <div class="modal fade" id="customerDetailModal" tabindex="-1" aria-labelledby="customerDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerDetailModalLabel" data-i18n="customer_details">Customer Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="btn-close-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="customer-avatar mx-auto mb-3" id="customerAvatar">R</div>
                        <h4 id="detailCustomerName">Ramesh Kirana</h4>
                        <p class="text-muted mb-1" id="detailCustomerPhone">+91 98765 43210</p>
                        <p class="text-muted mb-3" id="detailCustomerAddress">Address not provided</p>
                        <h3 id="detailCustomerBalance" class="amount-positive">₹1,500</h3>
                        <p class="text-muted" id="balanceStatus">You will take</p>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="quick-actions">
                        <button class="quick-action-btn" id="addLendTransactionBtn">
                            <i class="fas fa-hand-holding-usd mb-2" style="font-size: 1.5rem;"></i>
                            <span data-i18n="add_lend">Add Lend</span>
                        </button>
                        <button class="quick-action-btn" id="addReceiveBtn">
                            <i class="fas fa-download mb-2" style="font-size: 1.5rem;"></i>
                            <span data-i18n="receive_payment">Receive</span>
                        </button>
                        <button class="quick-action-btn" id="sendReminderBtn">
                            <i class="fas fa-bell mb-2" style="font-size: 1.5rem;"></i>
                            <span data-i18n="send_reminder">Reminder</span>
                        </button>
                    </div>
                    
                    <!-- Customer Statistics -->
                    <div class="customer-detail-section">
                        <h6 class="section-title" data-i18n="customer_stats">Customer Statistics</h6>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stats-card">
                                    <i class="fas fa-exchange-alt mb-2" style="font-size: 1.8rem; color: var(--primary);"></i>
                                    <h6 class="mb-0 font-weight-bold" id="customerTotalTransactions">0</h6>
                                    <small class="text-muted" data-i18n="transactions">Transactions</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stats-card">
                                    <i class="fas fa-clock mb-2" style="font-size: 1.8rem; color: var(--warning);"></i>
                                    <h6 class="mb-0 font-weight-bold" id="customerPendingAmount">₹0</h6>
                                    <small class="text-muted" data-i18n="pending">Pending</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stats-card">
                                    <i class="fas fa-calendar mb-2" style="font-size: 1.8rem; color: var(--info);"></i>
                                    <h6 class="mb-0 font-weight-bold" id="customerLastActivity">-</h6>
                                    <small class="text-muted" data-i18n="last_activity">Last Activity</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Transaction History -->
                    <div class="customer-detail-section">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="section-title mb-0" data-i18n="transaction_history">Transaction History</h6>
                            <button class="btn btn-sm btn-outline-primary" id="viewAllTransactions">
                                <span data-i18n="view_all">View All</span>
                            </button>
                        </div>
                        <div id="customerTransactionHistory">
                            <!-- Customer transactions will be added here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Lend Transaction Modal -->
    <div class="modal fade" id="addLendModal" tabindex="-1" aria-labelledby="addLendModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLendModalLabel" data-i18n="add_lend_transaction">Add Lend Transaction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="btn-close-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addLendForm">
                        <div class="form-group">
                            <label for="lendAmount" class="form-label" data-i18n="amount">Amount (₹)</label>
                            <input type="number" class="form-control" id="lendAmount" required min="1" data-i18n-placeholder="enter_amount">
                        </div>
                        <div class="form-group">
                            <label for="lendDate" class="form-label" data-i18n="date">Date</label>
                            <input type="date" class="form-control" id="lendDate" required>
                        </div>
                        <div class="form-group">
                            <label for="lendDescription" class="form-label" data-i18n="description">Description</label>
                            <textarea class="form-control" id="lendDescription" rows="2" data-i18n-placeholder="enter_description"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label" data-i18n="transaction_type">Transaction Type</label>
                            <div class="btn-group btn-group-toggle w-100" data-bs-toggle="buttons">
                                <label class="btn btn-outline-info active">
                                    <input type="radio" name="lendType" value="give" checked> 
                                    <i class="fas fa-hand-holding-usd mr-2"></i> <span data-i18n="you_will_give">You will give</span>
                                </label>
                                <label class="btn btn-outline-success">
                                    <input type="radio" name="lendType" value="take"> 
                                    <i class="fas fa-hand-holding mr-2"></i> <span data-i18n="you_will_take">You will take</span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-i18n="cancel">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveLendBtn">
                        <span class="btn-text" data-i18n="save_transaction">Save Transaction</span>
                        <span class="loading-spinner"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Receive Payment Modal -->
    <div class="modal fade" id="receivePaymentModal" tabindex="-1" aria-labelledby="receivePaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receivePaymentModalLabel" data-i18n="receive_payment">Receive Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="btn-close-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="receivePaymentForm">
                        <div class="form-group">
                            <label for="receiveAmount" class="form-label" data-i18n="amount">Amount (₹)</label>
                            <input type="number" class="form-control" id="receiveAmount" required min="1" data-i18n-placeholder="enter_amount">
                            <small class="form-text text-muted" data-i18n="max_receive_amount">Maximum: ₹<span id="maxReceiveAmount">0</span></small>
                        </div>
                        <div class="form-group">
                            <label for="receiveDate" class="form-label" data-i18n="date">Date</label>
                            <input type="date" class="form-control" id="receiveDate" required>
                        </div>
                        <div class="form-group">
                            <label for="receiveDescription" class="form-label" data-i18n="description">Description</label>
                            <textarea class="form-control" id="receiveDescription" rows="2" data-i18n-placeholder="enter_description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="receiveMethod" class="form-label" data-i18n="payment_method">Payment Method</label>
                            <select class="form-control" id="receiveMethod">
                                <option value="cash" data-i18n="cash">Cash</option>
                                <option value="bank" data-i18n="bank">Bank Transfer</option>
                                <option value="upi" data-i18n="upi">UPI</option>
                                <option value="debit_card" data-i18n="debit_card">Debit Card</option>
                                <option value="credit_card" data-i18n="credit_card">Credit Card</option>
                                <option value="other" data-i18n="other">Other</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-i18n="cancel">Cancel</button>
                    <button type="button" class="btn btn-success" id="saveReceiveBtn">
                        <span class="btn-text" data-i18n="receive_payment">Receive Payment</span>
                        <span class="loading-spinner"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions by Category Modal -->
    <div class="modal fade" id="transactionsModal" tabindex="-1" aria-labelledby="transactionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionsModalLabel" data-i18n="transactions_by_category">Transactions by Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="btn-close-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="categoryTransactionsList">
                        <!-- Category transactions will be added here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Data storage with enhanced customer tracking
    let wallet = {
        balance: parseFloat(localStorage.getItem('khataWalletBalance')) || 0,
        transactions: JSON.parse(localStorage.getItem('khataWalletTransactions')) || [],
        lastUpdated: localStorage.getItem('khataWalletLastUpdated') || new Date().toISOString(),
        initialDeposit: localStorage.getItem('khataWalletInitialDeposit') || false
    };

    let customers = JSON.parse(localStorage.getItem('khataCustomers')) || [];
    let currentCustomerId = null;
    let currentLanguage = localStorage.getItem('khataLanguage') || 'en';
    let currentTransactionType = 'spent';

    // Enhanced language translations with new payment methods
    const translations = {
        en: {
            // App
            "app_title": "Khata Book",
            "app_subtitle": "Digital Ledger & Wallet Manager",
            
            // Wallet
            "wallet_balance": "Wallet Balance",
            "add_money": "Add Money",
            "add_money_to_wallet": "Add Money to Wallet",
            "amount_to_add": "Amount to Add",
            "source": "Source",
            "cash": "Cash",
            "bank": "Bank Transfer",
            "bank_transfer": "Bank Transfer",
            "upi": "UPI",
            "debit_card": "Debit Card",
            "credit_card": "Credit Card",
            "online_payment": "Online Payment",
            "spent": "Spent",
            "received": "Received",
            "lend": "Credit Given",
            "total_spent": "Total Spent",
            "total_received": "Total Received",
            "total_lend": "Credit Given",
            "zero_balance_alert": "Add money to your wallet to start transactions",
            "zero_balance_message": "You need to add balance first to use Spent and Received features",
            "insufficient_balance": "Insufficient wallet balance",
            "available_balance": "Available balance",
            "money_added_success": "Money added to wallet successfully!",
            
            // Navigation
            "transactions": "Transactions",
            "customers": "Customers",
            "reports": "Reports",
            
            // Transaction Types
            "transaction_type": "Transaction Type",
            "select_customer": "Select Customer",
            "category": "Category / Payment Method",
            "general": "General",
            "food": "Food",
            "shopping": "Shopping",
            "transport": "Transport",
            "entertainment": "Entertainment",
            "bills": "Bills",
            "medical": "Medical",
            "education": "Education",
            "personal_care": "Personal Care",
            "salary": "Salary",
            "business": "Business",
            "freelance": "Freelance",
            "investment": "Investment",
            "gift": "Gift",
            "refund": "Refund",
            "other": "Other",
            
            // Search
            "search_transactions": "Search transactions...",
            "search_customer": "Search customer...",
            
            // Empty States
            "no_transactions_found": "No Transactions Found",
            "add_first_transaction": "Add your first transaction to get started",
            "no_customers_found": "No Customers Found",
            "add_first_customer": "Add your first customer to get started",
            
            // Customer specific
            "add_customer": "Add Customer",
            "total_customers": "Total Customers",
            "active_customers": "Active",
            "total_credit_given": "Credit Given",
            "address": "Address",
            "enter_address": "Enter address",
            "customer_stats": "Customer Statistics",
            "last_activity": "Last Activity",
            "add_lend": "Add Lend",
            "receive_payment": "Receive Payment",
            "add_lend_transaction": "Add Lend Transaction",
            "you_will_give": "You will give",
            "you_will_take": "You will take",
            "view_all": "View All",
            "payment_method": "Payment Method",
            "max_receive_amount": "Maximum",
            "pending_amount": "Pending Amount",
            "total_transactions": "Total Transactions",
            "settle_balance": "Settle Balance",
            "customer_since": "Customer Since",
            "recent_activity": "Recent Activity",
            
            // Existing translations
            "customer_list": "Customer List",
            "recent_transactions": "Recent Transactions",
            "business_reports": "Business Reports",
            "monthly_summary": "Monthly Summary",
            "category_breakdown": "Category Breakdown",
            "transactions_by_category": "Transactions by Category",
            
            // Modals
            "add_transaction": "Add Transaction",
            "add_new_customer": "Add New Customer",
            "customer_name": "Customer Name",
            "enter_customer_name": "Enter customer name",
            "phone_number": "Phone Number",
            "enter_phone_number": "Enter phone number",
            "initial_amount": "Initial Amount",
            "amount": "Amount",
            "enter_amount": "Enter amount",
            "date": "Date",
            "description": "Description",
            "enter_description": "Enter description",
            "cancel": "Cancel",
            "save_transaction": "Save Transaction",
            "save_customer": "Save Customer",
            
            "customer_details": "Customer Details",
            "send_reminder": "Send Reminder",
            "transaction_history": "Transaction History",
            
            // Alerts
            "reminder_sent": "Reminder sent successfully!",
            "fill_required_fields": "Please fill all required fields",
            "enter_customer_name_alert": "Please enter customer name",
            "no_transactions": "No transactions yet"
        },
        hi: {
            // Hindi translations
            "app_subtitle": "डिजिटल लेजर और वॉलेट मैनेजर",
            "wallet_balance": "वॉलेट बैलेंस",
            "add_money": "पैसे जोड़ें",
            "add_money_to_wallet": "वॉलेट में पैसे जोड़ें",
            "amount_to_add": "जोड़ने के लिए राशि",
            "source": "स्रोत",
            "cash": "नकद",
            "bank": "बैंक ट्रांसफर",
            "bank_transfer": "बैंक ट्रांसफर",
            "upi": "यूपीआई",
            "debit_card": "डेबिट कार्ड",
            "credit_card": "क्रेडिट कार्ड",
            "online_payment": "ऑनलाइन भुगतान",
            "spent": "खर्च",
            "received": "प्राप्त",
            "lend": "उधार",
            "total_spent": "कुल खर्च",
            "total_received": "कुल प्राप्त",
            "total_lend": "कुल उधार",
            "zero_balance_alert": "लेनदेन शुरू करने के लिए अपने वॉलेट में पैसे जोड़ें",
            "zero_balance_message": "खर्च और प्राप्त सुविधाओं का उपयोग करने के लिए आपको पहले बैलेंस जोड़ना होगा",
            "insufficient_balance": "अपर्याप्त वॉलेट बैलेंस",
            "available_balance": "उपलब्ध बैलेंस",
            "money_added_success": "वॉलेट में पैसे सफलतापूर्वक जोड़े गए!",
            "transactions": "लेनदेन",
            "transaction_type": "लेनदेन प्रकार",
            "select_customer": "ग्राहक चुनें",
            "category": "श्रेणी / भुगतान विधि",
            "general": "सामान्य",
            "food": "भोजन",
            "shopping": "खरीदारी",
            "transport": "यातायात",
            "entertainment": "मनोरंजन",
            "bills": "बिल",
            "medical": "चिकित्सा",
            "education": "शिक्षा",
            "personal_care": "व्यक्तिगत देखभाल",
            "salary": "वेतन",
            "business": "व्यवसाय",
            "freelance": "फ्रीलांस",
            "investment": "निवेश",
            "gift": "उपहार",
            "refund": "वापसी",
            "other": "अन्य",
            "search_transactions": "लेनदेन खोजें...",
            "no_transactions_found": "कोई लेनदेन नहीं मिला",
            "add_first_transaction": "आरंभ करने के लिए अपना पहला लेनदेन जोड़ें",
            "transactions_by_category": "श्रेणी के अनुसार लेनदेन",
            "category_breakdown": "श्रेणी विवरण",
            "add_customer": "ग्राहक जोड़ें",
            "total_customers": "कुल ग्राहक",
            "active_customers": "सक्रिय",
            "total_credit_given": "क्रेडिट दिया",
            "address": "पता",
            "enter_address": "पता दर्ज करें",
            "customer_stats": "ग्राहक आंकड़े",
            "last_activity": "अंतिम गतिविधि",
            "add_lend": "उधार जोड़ें",
            "receive_payment": "भुगतान प्राप्त करें",
            "add_lend_transaction": "उधार लेनदेन जोड़ें",
            "you_will_give": "आप देंगे",
            "you_will_take": "आप लेंगे",
            "view_all": "सभी देखें",
            "payment_method": "भुगतान विधि",
            "max_receive_amount": "अधिकतम",
            "pending_amount": "लंबित राशि",
            "total_transactions": "कुल लेनदेन",
            "settle_balance": "बैलेंस सेटल करें",
            "customer_since": "ग्राहक से",
            "recent_activity": "हाल की गतिविधि"
        },
        gu: {
            // Gujarati translations
            "app_subtitle": "ડિજિટલ લેજર અને વૉલેટ મેનેજર",
            "wallet_balance": "વૉલેટ બેલેન્સ",
            "add_money": "પૈસા ઉમેરો",
            "add_money_to_wallet": "વૉલેટમાં પૈસા ઉમેરો",
            "amount_to_add": "ઉમેરવાની રકમ",
            "source": "સ્ત્રોત",
            "cash": "કેશ",
            "bank": "બેંક ટ્રાન્સફર",
            "bank_transfer": "બેંક ટ્રાન્સફર",
            "upi": "યુપીઆઈ",
            "debit_card": "ડેબિટ કાર્ડ",
            "credit_card": "ક્રેડિટ કાર્ડ",
            "online_payment": "ઓનલાઈન પેમેન્ટ",
            "spent": "ખર્ચ",
            "received": "મળ્યું",
            "lend": "ઉધાર",
            "total_spent": "કુલ ખર્ચ",
            "total_received": "કુલ મળ્યું",
            "total_lend": "કુલ ઉધાર",
            "zero_balance_alert": "લેનદેન શરૂ કરવા માટે તમારા વૉલેટમાં પૈસા ઉમેરો",
            "zero_balance_message": "ખર્ચ અને મળ્યું સુવિધાઓનો ઉપયોગ કરવા માટે તમારે પહેલા બેલેન્સ ઉમેરવું જરૂરી છે",
            "insufficient_balance": "અપર્યાપ્ત વૉલેટ બેલેન્સ",
            "available_balance": "ઉપલબ્ધ બેલેન્સ",
            "money_added_success": "વૉલેટમાં પૈસા સફળતાપૂર્વક ઉમેરાયા!",
            "transactions": "લેનદેન",
            "transaction_type": "લેનદેન પ્રકાર",
            "select_customer": "ગ્રાહક પસંદ કરો",
            "category": "શ્રેણી / ભુગતાન પદ્ધતિ",
            "general": "સામાન્ય",
            "food": "ખોરાક",
            "shopping": "શોપિંગ",
            "transport": "ટ્રાન્સપોર્ટ",
            "entertainment": "મનોરંજન",
            "bills": "બિલ",
            "medical": "મેડિકલ",
            "education": "શિક્ષણ",
            "personal_care": "વ્યક્તિગત સંભાળ",
            "salary": "સેલરી",
            "business": "વ્યવસાય",
            "freelance": "ફ્રીલાન્સ",
            "investment": "ઇન્વેસ્ટમેન્ટ",
            "gift": "ભેટ",
            "refund": "રિફંડ",
            "other": "અન્ય",
            "search_transactions": "લેનદેન શોધો...",
            "no_transactions_found": "કોઈ લેનદેન મળ્યા નથી",
            "add_first_transaction": "શરૂ કરવા માટે તમારું પહેલું લેનદેન ઉમેરો",
            "transactions_by_category": "શ્રેણી મુજબ લેનદેન",
            "category_breakdown": "શ્રેણી વિગતો",
            "add_customer": "ગ્રાહક ઉમેરો",
            "total_customers": "કુલ ગ્રાહકો",
            "active_customers": "સક્રિય",
            "total_credit_given": "ક્રેડિટ આપ્યો",
            "address": "સરનામું",
            "enter_address": "સરનામું દાખલ કરો",
            "customer_stats": "ગ્રાહક આંકડા",
            "last_activity": "છેલ્લી પ્રવૃત્તિ",
            "add_lend": "ઉધાર ઉમેરો",
            "receive_payment": "ભુગતાન મેળવો",
            "add_lend_transaction": "ઉધાર લેનદેન ઉમેરો",
            "you_will_give": "તમે આપશો",
            "you_will_take": "તમે લેશો",
            "view_all": "બધા જુઓ",
            "payment_method": "ભુગતાન પદ્ધતિ",
            "max_receive_amount": "મહત્તમ",
            "pending_amount": "બાકી રકમ",
            "total_transactions": "કુલ લેનદેન",
            "settle_balance": "બેલેન્સ સેટલ કરો",
            "customer_since": "ગ્રાહક ત્યારથી",
            "recent_activity": "તાજેતરની પ્રવૃત્તિ"
        }
    };

    // Initialize the app
    $(document).ready(function() {
        // Set initial language
        $('#languageSelect').val(currentLanguage);
        updateLanguage(currentLanguage);
        
        // Initialize data
        updateWalletDisplay();
        updateTransactionButtons();
        updateCustomerStats();
        renderTransactionsList();
        renderCustomerList();
        initializeCharts();
        setupEventListeners();
        
        // Set today's date as default for transaction date
        const today = new Date().toISOString().split('T')[0];
        $('#transactionDate').val(today);
        $('#lendDate').val(today);
        $('#receiveDate').val(today);
        
        // Initialize category dropdown based on transaction type
        updateCategoryDropdown();
        
        // Fix for any existing backdrop issues
        cleanupModalBackdrops();
    });

    // Clean up any leftover modal backdrops
    function cleanupModalBackdrops() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('padding-right', '');
        $('.modal.show').removeClass('show');
        $('.modal').css('display', 'none');
    }

    // Update category dropdown based on transaction type
    function updateCategoryDropdown() {
        const transactionType = currentTransactionType;
        const categorySelect = $('#transactionCategory');
        
        // Show/hide optgroups based on transaction type
        if (transactionType === 'spent') {
            $('#expenseCategoriesGroup').show();
            $('#incomeCategoriesGroup').hide();
            // Select first expense category as default
            categorySelect.val('food');
        } else if (transactionType === 'received') {
            $('#expenseCategoriesGroup').hide();
            $('#incomeCategoriesGroup').show();
            // Select first income category as default
            categorySelect.val('salary');
        }
    }

    // Update language function
    function updateLanguage(language) {
        currentLanguage = language;
        localStorage.setItem('khataLanguage', language);
        
        // Update all elements with data-i18n attribute
        $('[data-i18n]').each(function() {
            const key = $(this).data('i18n');
            if (translations[language] && translations[language][key]) {
                $(this).text(translations[language][key]);
            }
        });
        
        // Update placeholder texts
        $('[data-i18n-placeholder]').each(function() {
            const key = $(this).data('i18n-placeholder');
            if (translations[language] && translations[language][key]) {
                $(this).attr('placeholder', translations[language][key]);
            }
        });
        
        // Update select options
        $('option[data-i18n]').each(function() {
            const key = $(this).data('i18n');
            if (translations[language] && translations[language][key]) {
                $(this).text(translations[language][key]);
            }
        });
    }

    // Wallet Functions
    function updateWalletDisplay() {
        $('#walletBalance').text(`₹${wallet.balance.toFixed(2)}`);
        $('#walletLastUpdated').text(`Last updated: ${formatRelativeTime(new Date(wallet.lastUpdated))}`);
        
        // Update wallet card appearance based on balance
        const walletCard = $('#walletCard');
        walletCard.removeClass('low-balance zero-balance');
        
        if (wallet.balance === 0) {
            walletCard.addClass('zero-balance');
            $('#zeroBalanceAlert').show();
        } else if (wallet.balance < 100) {
            walletCard.addClass('low-balance');
            $('#zeroBalanceAlert').hide();
        } else {
            $('#zeroBalanceAlert').hide();
        }
        
        // Calculate category totals
        const totals = calculateCategoryTotals();
        $('#totalSpent').text(`₹${totals.spent.toFixed(2)}`);
        $('#totalReceived').text(`₹${totals.received.toFixed(2)}`);
        $('#totalLend').text(`₹${totals.lend.toFixed(2)}`);
    }

    function updateTransactionButtons() {
        const hasBalance = wallet.balance > 0;
        
        // Enable/disable spent and received buttons based on wallet balance
        $('#spentBtn').prop('disabled', !hasBalance);
        $('#receivedBtn').prop('disabled', !hasBalance);
        
        // Lend button is always enabled as it doesn't affect wallet balance
        $('#lendBtn').prop('disabled', false);
        
        // Update tooltips for disabled buttons
        if (!hasBalance) {
            $('#spentBtn').attr('title', 'Add money to wallet first');
            $('#receivedBtn').attr('title', 'Add money to wallet first');
        } else {
            $('#spentBtn').removeAttr('title');
            $('#receivedBtn').removeAttr('title');
        }
    }

    function addMoneyToWallet(amount, description, source) {
        const newTransaction = {
            id: wallet.transactions.length > 0 ? Math.max(...wallet.transactions.map(t => t.id)) + 1 : 1,
            amount: parseFloat(amount),
            date: new Date().toISOString().split('T')[0],
            description: description || 'Wallet deposit',
            type: 'received',
            category: source || 'deposit',
            source: source,
            createdAt: new Date().toISOString()
        };
        
        // Update wallet balance
        wallet.balance += newTransaction.amount;
        wallet.transactions.unshift(newTransaction);
        wallet.lastUpdated = new Date().toISOString();
        wallet.initialDeposit = true;
        
        saveToLocalStorage();
        updateWalletDisplay();
        updateTransactionButtons();
        renderTransactionsList();
        
        return newTransaction;
    }

    function calculateCategoryTotals() {
        const totals = { spent: 0, received: 0, lend: 0 };
        
        wallet.transactions.forEach(transaction => {
            if (transaction.type === 'spent') {
                totals.spent += transaction.amount;
            } else if (transaction.type === 'received') {
                totals.received += transaction.amount;
            } else if (transaction.type === 'lend') {
                totals.lend += transaction.amount;
            }
        });
        
        return totals;
    }

    function addTransaction(amount, date, description, type, category, customerId = null) {
        // Check if wallet has sufficient balance for spent transactions
        if (type === 'spent' && amount > wallet.balance) {
            alert(`${translations[currentLanguage]['insufficient_balance']}! ${translations[currentLanguage]['available_balance']}: ₹${wallet.balance.toFixed(2)}`);
            return null;
        }
        
        const newTransaction = {
            id: wallet.transactions.length > 0 ? Math.max(...wallet.transactions.map(t => t.id)) + 1 : 1,
            amount: parseFloat(amount),
            date: date,
            description: description || 'Transaction',
            type: type,
            category: category,
            customerId: customerId,
            createdAt: new Date().toISOString()
        };
        
        // Update wallet balance based on transaction type
        if (type === 'spent') {
            wallet.balance -= newTransaction.amount;
        } else if (type === 'received') {
            wallet.balance += newTransaction.amount;
        } else if (type === 'lend' && customerId) {
            // For lend transactions, update customer balance instead of wallet
            updateCustomerBalance(customerId, newTransaction.amount, 'give');
        }
        
        wallet.transactions.unshift(newTransaction);
        wallet.lastUpdated = new Date().toISOString();
        
        saveToLocalStorage();
        updateWalletDisplay();
        updateTransactionButtons();
        renderTransactionsList();
        
        return newTransaction;
    }

    // Enhanced Customer Functions
    function updateCustomerStats() {
        const totalCustomers = customers.length;
        const activeCustomers = customers.filter(c => c.transactions && c.transactions.length > 0).length;
        const totalCreditGiven = customers.reduce((sum, customer) => {
            return sum + (customer.balance > 0 ? customer.balance : 0);
        }, 0);

        $('#totalCustomersCount').text(totalCustomers);
        $('#activeCustomersCount').text(activeCustomers);
        $('#totalCreditGiven').text(`₹${totalCreditGiven.toFixed(2)}`);
        $('#customersCount').text(totalCustomers);
    }

    function addCustomer(name, phone, address, initialAmount, transactionType) {
        const newCustomer = {
            id: customers.length > 0 ? Math.max(...customers.map(c => c.id)) + 1 : 1,
            name: name,
            phone: phone,
            address: address,
            balance: transactionType === 'take' ? parseFloat(initialAmount) : -parseFloat(initialAmount),
            transactions: [],
            createdAt: new Date().toISOString(),
            updatedAt: new Date().toISOString()
        };
        
        // Add initial transaction if amount > 0
        if (initialAmount > 0) {
            const transaction = {
                id: 1,
                amount: parseFloat(initialAmount),
                date: new Date().toISOString().split('T')[0],
                description: 'Initial transaction',
                type: transactionType,
                category: 'initial',
                createdAt: new Date().toISOString()
            };
            newCustomer.transactions.push(transaction);
            
            // Also add to wallet transactions if it's a lend transaction
            if (transactionType === 'give') {
                const walletTransaction = {
                    id: wallet.transactions.length > 0 ? Math.max(...wallet.transactions.map(t => t.id)) + 1 : 1,
                    amount: parseFloat(initialAmount),
                    date: new Date().toISOString().split('T')[0],
                    description: `Lend to ${name}`,
                    type: 'lend',
                    category: 'lend',
                    customerId: newCustomer.id,
                    createdAt: new Date().toISOString()
                };
                wallet.transactions.unshift(walletTransaction);
            }
        }
        
        customers.push(newCustomer);
        saveToLocalStorage();
        updateCustomerStats();
        renderCustomerList();
        renderTransactionsList();
        
        return newCustomer;
    }

    function addCustomerTransaction(customerId, amount, date, description, type) {
        const customer = customers.find(c => c.id === customerId);
        if (!customer) return null;

        const newTransaction = {
            id: customer.transactions.length > 0 ? Math.max(...customer.transactions.map(t => t.id)) + 1 : 1,
            amount: parseFloat(amount),
            date: date,
            description: description,
            type: type,
            category: 'customer',
            createdAt: new Date().toISOString()
        };

        // Update customer balance
        if (type === 'take') {
            customer.balance += newTransaction.amount;
        } else {
            customer.balance -= newTransaction.amount;
        }

        customer.transactions.unshift(newTransaction);
        customer.updatedAt = new Date().toISOString();

        // Also add to wallet transactions for tracking
        const walletTransaction = {
            id: wallet.transactions.length > 0 ? Math.max(...wallet.transactions.map(t => t.id)) + 1 : 1,
            amount: parseFloat(amount),
            date: date,
            description: `${type === 'take' ? 'Received from' : 'Lend to'} ${customer.name}: ${description}`,
            type: type === 'take' ? 'received' : 'lend',
            category: 'customer',
            customerId: customerId,
            createdAt: new Date().toISOString()
        };

        // Update wallet balance for received payments
        if (type === 'take') {
            wallet.balance += parseFloat(amount);
        }

        wallet.transactions.unshift(walletTransaction);
        wallet.lastUpdated = new Date().toISOString();

        saveToLocalStorage();
        updateWalletDisplay();
        updateTransactionButtons();
        updateCustomerStats();
        renderCustomerList();
        renderTransactionsList();

        if (currentCustomerId === customerId) {
            refreshCustomerDetails(customerId);
        }

        return newTransaction;
    }

    function updateCustomerBalance(customerId, amount, type) {
        const customer = customers.find(c => c.id === customerId);
        if (!customer) return;
        
        if (type === 'give') {
            customer.balance += parseFloat(amount);
        } else if (type === 'take') {
            customer.balance -= parseFloat(amount);
        }
        
        customer.updatedAt = new Date().toISOString();
        saveToLocalStorage();
        renderCustomerList();
    }

    // Render transactions list
    function renderTransactionsList() {
        const transactionsList = $('#transactionsList');
        transactionsList.empty();
        
        if (wallet.transactions.length === 0) {
            $('#emptyTransactionState').removeClass('d-none');
            return;
        }
        
        $('#emptyTransactionState').addClass('d-none');
        
        wallet.transactions.forEach((transaction, index) => {
            const amountClass = transaction.type === 'received' ? 'amount-positive' : 
                             transaction.type === 'lend' ? 'amount-neutral' : 'amount-negative';
            const typeText = translations[currentLanguage][transaction.type] || transaction.type;
            const typeBadgeClass = `type-${transaction.type}`;
            
            // Get customer name if it's a customer transaction
            let customerInfo = '';
            if (transaction.customerId) {
                const customer = customers.find(c => c.id === transaction.customerId);
                if (customer) {
                    customerInfo = `<small class="text-muted me-2">${customer.name}</small>`;
                }
            }
            
            // Get category display text
            const categoryText = translations[currentLanguage][transaction.category] || transaction.category;
            
            const transactionItem = `
                <div class="transaction-item ${transaction.type}" style="animation-delay: ${index * 0.05}s">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${transaction.description}</h6>
                            <div class="d-flex align-items-center">
                                <span class="transaction-type-badge ${typeBadgeClass} me-2">${typeText}</span>
                                ${customerInfo}
                                <small class="text-muted me-2">${categoryText}</small>
                                <small class="text-muted">${new Date(transaction.date).toLocaleDateString()}</small>
                            </div>
                        </div>
                        <div class="text-right">
                            <h5 class="${amountClass} mb-1">${transaction.type === 'spent' ? '-' : '+'}₹${transaction.amount.toFixed(2)}</h5>
                            <small class="text-muted">${formatRelativeTime(new Date(transaction.createdAt))}</small>
                        </div>
                    </div>
                </div>
            `;
            
            transactionsList.append(transactionItem);
        });
    }

    function renderCustomerList() {
        const customerList = $('#customerList');
        customerList.empty();
        
        if (customers.length === 0) {
            $('#emptyCustomerState').removeClass('d-none');
            return;
        }
        
        $('#emptyCustomerState').addClass('d-none');
        
        // Sort customers by recent activity
        const sortedCustomers = [...customers].sort((a, b) => new Date(b.updatedAt) - new Date(a.updatedAt));
        
        sortedCustomers.forEach((customer, index) => {
            const balanceClass = customer.balance >= 0 ? 'amount-positive' : 'amount-negative';
            const balanceText = customer.balance >= 0 ? 
                `${translations[currentLanguage]['you_will_take']} ₹${Math.abs(customer.balance).toFixed(2)}` : 
                `${translations[currentLanguage]['you_will_give']} ₹${Math.abs(customer.balance).toFixed(2)}`;
            
            const lastTransaction = customer.transactions && customer.transactions.length > 0 
                ? customer.transactions[0] 
                : null;
            
            const customerCard = `
                <div class="card customer-card" style="animation-delay: ${index * 0.1}s">
                    <div class="customer-card-header d-flex justify-content-between align-items-center">
                        <span>${customer.name}</span>
                        <div>
                            <span class="badge-custom me-2">${customer.phone}</span>
                            <span class="badge ${customer.balance !== 0 ? 'badge-warning' : 'badge-success'}">
                                ${customer.transactions ? customer.transactions.length : 0} trans
                            </span>
                        </div>
                    </div>
                    <div class="card-body" data-customer-id="${customer.id}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="customer-avatar mr-3">${customer.name.charAt(0)}</div>
                                <div>
                                    <h6 class="mb-0">${balanceText}</h6>
                                    <small class="text-muted">
                                        ${lastTransaction ? 
                                            `Last: ${new Date(lastTransaction.date).toLocaleDateString()}` : 
                                            translations[currentLanguage]['no_transactions']}
                                    </small>
                                </div>
                            </div>
                            <div class="text-right">
                                <h4 class="${balanceClass}">₹${Math.abs(customer.balance).toFixed(2)}</h4>
                                <small class="text-muted">
                                    ${formatRelativeTime(new Date(customer.updatedAt))}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            customerList.append(customerCard);
        });
        
        // Re-attach click event to customer cards
        attachCustomerCardClickEvents();
    }

    function attachCustomerCardClickEvents() {
        // Remove existing handlers first
        $('.customer-card').off('click');
        
        // Add new handler
        $('.customer-card').on('click', function() {
            const customerId = parseInt($(this).find('.card-body').data('customer-id'));
            openCustomerDetails(customerId);
        });
    }

    function openCustomerDetails(customerId) {
        const customer = customers.find(c => c.id === customerId);
        if (!customer) return;
        
        currentCustomerId = customerId;
        
        // Update modal content
        $('#customerAvatar').text(customer.name.charAt(0));
        $('#detailCustomerName').text(customer.name);
        $('#detailCustomerPhone').text(customer.phone || 'Not provided');
        $('#detailCustomerAddress').text(customer.address || 'Address not provided');
        
        const balanceElement = $('#detailCustomerBalance');
        balanceElement.text(`₹${Math.abs(customer.balance).toFixed(2)}`);
        balanceElement.removeClass('amount-positive amount-negative')
                     .addClass(customer.balance >= 0 ? 'amount-positive' : 'amount-negative');
        
        $('#balanceStatus').text(customer.balance >= 0 ? 
            translations[currentLanguage]['you_will_take'] : 
            translations[currentLanguage]['you_will_give']);
        
        // Update customer statistics
        updateCustomerDetailStats(customer);
        
        // Render transaction history
        renderCustomerTransactionHistory(customer);
        
        // Show the modal
        $('#customerDetailModal').modal('show');
    }

    function updateCustomerDetailStats(customer) {
        const totalTransactions = customer.transactions ? customer.transactions.length : 0;
        const pendingAmount = Math.abs(customer.balance);
        const lastActivity = customer.transactions && customer.transactions.length > 0 
            ? formatRelativeTime(new Date(customer.transactions[0].createdAt))
            : '-';
        
        $('#customerTotalTransactions').text(totalTransactions);
        $('#customerPendingAmount').text(`₹${pendingAmount.toFixed(2)}`);
        $('#customerLastActivity').text(lastActivity);
        
        // Update max receive amount
        $('#maxReceiveAmount').text(pendingAmount.toFixed(2));
    }

    function renderCustomerTransactionHistory(customer) {
        const transactionHistory = $('#customerTransactionHistory');
        transactionHistory.empty();
        
        if (!customer.transactions || customer.transactions.length === 0) {
            transactionHistory.html(`
                <div class="empty-state" style="padding: 20px;">
                    <i class="fas fa-exchange-alt"></i>
                    <p>${translations[currentLanguage]['no_transactions']}</p>
                </div>
            `);
            return;
        }
        
        // Show only last 5 transactions
        const recentTransactions = customer.transactions.slice(0, 5);
        
        recentTransactions.forEach((transaction, index) => {
            const amountClass = transaction.type === 'take' ? 'positive' : 'negative';
            const amountSign = transaction.type === 'take' ? '+' : '-';
            const typeText = transaction.type === 'take' ? 
                translations[currentLanguage]['you_will_take'] : 
                translations[currentLanguage]['you_will_give'];
            
            const transactionItem = `
                <div class="customer-transaction-item" style="animation-delay: ${index * 0.05}s">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${transaction.description}</h6>
                            <small class="text-muted">${new Date(transaction.date).toLocaleDateString()} • ${typeText}</small>
                        </div>
                        <div class="text-right">
                            <h5 class="transaction-amount ${amountClass} mb-1">${amountSign}₹${transaction.amount.toFixed(2)}</h5>
                            <small class="text-muted">${formatRelativeTime(new Date(transaction.createdAt))}</small>
                        </div>
                    </div>
                </div>
            `;
            
            transactionHistory.append(transactionItem);
        });
    }

    function refreshCustomerDetails(customerId) {
        const customer = customers.find(c => c.id === customerId);
        if (!customer) return;
        
        if ($('#customerDetailModal').is(':visible')) {
            updateCustomerDetailStats(customer);
            renderCustomerTransactionHistory(customer);
            
            // Update balance display
            const balanceElement = $('#detailCustomerBalance');
            balanceElement.text(`₹${Math.abs(customer.balance).toFixed(2)}`);
            balanceElement.removeClass('amount-positive amount-negative')
                         .addClass(customer.balance >= 0 ? 'amount-positive' : 'amount-negative');
            
            $('#balanceStatus').text(customer.balance >= 0 ? 
                translations[currentLanguage]['you_will_take'] : 
                translations[currentLanguage]['you_will_give']);
        }
    }

    // Initialize charts
    function initializeCharts() {
        // Monthly chart - Updated to show all 12 months
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Spent',
                    data: [12000, 19000, 15000, 25000, 22000, 30000, 28000, 32000, 27000, 29000, 31000, 35000],
                    backgroundColor: '#e74a3b',
                    borderRadius: 5,
                }, {
                    label: 'Received',
                    data: [10000, 15000, 13000, 20000, 18000, 25000, 23000, 27000, 24000, 26000, 28000, 32000],
                    backgroundColor: '#1cc88a',
                    borderRadius: 5,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += '₹' + context.parsed.y.toLocaleString();
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // Category chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const totals = calculateCategoryTotals();
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Spent', 'Received', 'Lend'],
                datasets: [{
                    data: [totals.spent, totals.received, totals.lend],
                    backgroundColor: ['#e74a3b', '#1cc88a', '#36b9cc'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ₹${value.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Utility functions
    function formatRelativeTime(date) {
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);
        
        if (diffInSeconds < 60) return 'Just now';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`;
        if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)} days ago`;
        return date.toLocaleDateString();
    }

    function saveToLocalStorage() {
        localStorage.setItem('khataWalletBalance', wallet.balance.toString());
        localStorage.setItem('khataWalletTransactions', JSON.stringify(wallet.transactions));
        localStorage.setItem('khataWalletLastUpdated', wallet.lastUpdated);
        localStorage.setItem('khataWalletInitialDeposit', wallet.initialDeposit);
        localStorage.setItem('khataCustomers', JSON.stringify(customers));
    }

    function clearAllData() {
        // Clear all data from localStorage
        localStorage.removeItem('khataWalletBalance');
        localStorage.removeItem('khataWalletTransactions');
        localStorage.removeItem('khataWalletLastUpdated');
        localStorage.removeItem('khataWalletInitialDeposit');
        localStorage.removeItem('khataCustomers');
        localStorage.removeItem('khataLanguage');
        
        // Reset application state
        wallet = {
            balance: 0,
            transactions: [],
            lastUpdated: new Date().toISOString(),
            initialDeposit: false
        };
        
        customers = [];
        currentCustomerId = null;
        currentLanguage = 'en';
        
        // Update UI
        updateWalletDisplay();
        updateTransactionButtons();
        updateCustomerStats();
        renderTransactionsList();
        renderCustomerList();
        
        // Show success message
        alert('All data has been cleared successfully!');
    }

    // Fixed loading functions
    function showLoading(button) {
        const $button = $(button);
        $button.prop('disabled', true);
        $button.addClass('loading');
        $button.find('.loading-spinner').show();
    }
    
    function hideLoading(button) {
        const $button = $(button);
        $button.prop('disabled', false);
        $button.removeClass('loading');
        $button.find('.loading-spinner').hide();
        
        // Force cleanup of any leftover backdrop
        setTimeout(cleanupModalBackdrops, 100);
    }

    // Setup event listeners
    function setupEventListeners() {
        // Language selector
        $('#languageSelect').on('change', function() {
            const selectedLanguage = $(this).val();
            updateLanguage(selectedLanguage);
            renderTransactionsList();
            renderCustomerList();
        });
        
        // Clear data button
        $('#clearDataBtn').on('click', function() {
            if (confirm('Are you sure you want to clear all data? This action cannot be undone.')) {
                clearAllData();
                $('#settingsModal').modal('hide');
                setTimeout(cleanupModalBackdrops, 100);
            }
        });
        
        // Export data button
        $('#exportDataBtn').on('click', function() {
            const data = {
                wallet: wallet,
                customers: customers,
                exportDate: new Date().toISOString()
            };
            
            const dataStr = JSON.stringify(data, null, 2);
            const dataBlob = new Blob([dataStr], {type: 'application/json'});
            
            const url = URL.createObjectURL(dataBlob);
            const link = document.createElement('a');
            link.href = url;
            link.download = `khata-book-backup-${new Date().toISOString().split('T')[0]}.json`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            alert('Data exported successfully!');
        });
        
        // Add money button
        $('#saveAddMoneyBtn').on('click', function() {
            const amount = parseFloat($('#addAmount').val());
            const description = $('#addMoneyDescription').val() || 'Initial wallet deposit';
            const source = $('#addMoneySource').val();
            
            if (amount && amount > 0) {
                showLoading(this);
                
                setTimeout(() => {
                    addMoneyToWallet(amount, description, source);
                    hideLoading(this);
                    
                    $('#addMoneyModal').modal('hide');
                    $('#addMoneyForm')[0].reset();
                    
                    // Show success message
                    alert(translations[currentLanguage]['money_added_success']);
                    
                    // Show success animation
                    $('.wallet-card').addClass('success-animation')
                        .on('animationend', function() {
                            $(this).removeClass('success-animation');
                        });
                }, 1000);
            } else {
                alert(translations[currentLanguage]['fill_required_fields']);
            }
        });
        
        // Wallet action buttons - FIXED: Proper event delegation
        $(document).on('click', '.wallet-btn[data-type]', function() {
            if (!$(this).prop('disabled')) {
                currentTransactionType = $(this).data('type');
                updateCategoryDropdown();
                $('#addTransactionModal').modal('show');
            }
        });
        
        // Category cards click
        $('.category-card').on('click', function() {
            const category = $(this).data('category');
            if (category === 'spent' || category === 'received') {
                currentTransactionType = category;
                updateCategoryDropdown();
                $('#addTransactionModal').modal('show');
                
                // Set the correct radio button as active
                $(`input[name="transactionType"][value="${category}"]`).prop('checked', true).trigger('change');
                $(`label.btn-outline-${category === 'spent' ? 'danger' : 'success'}`).addClass('active');
                $(`label.btn-outline-${category === 'spent' ? 'success' : 'danger'}`).removeClass('active');
            } else if (category === 'lend') {
                $('#addCustomerModal').modal('show');
            }
        });
        
        // Transaction type change
        $('input[name="transactionType"]').on('change', function() {
            currentTransactionType = $(this).val();
            updateCategoryDropdown();
            updateBalanceCheckMessage();
        });
        
        // Amount input change for balance check
        $('#transactionAmount').on('input', function() {
            updateBalanceCheckMessage();
        });
        
        // Save transaction - Fixed with proper loading handling
        $('#saveTransactionBtn').on('click', function() {
            const amount = parseFloat($('#transactionAmount').val());
            const date = $('#transactionDate').val();
            const description = $('#transactionDescription').val();
            const category = $('#transactionCategory').val();
            
            if (amount && amount > 0 && date) {
                showLoading(this);
                
                setTimeout(() => {
                    const transaction = addTransaction(amount, date, description, currentTransactionType, category);
                    
                    // Always hide loading regardless of transaction success
                    hideLoading(this);
                    
                    if (transaction) {
                        $('#addTransactionModal').modal('hide');
                        
                        // Reset form with a small delay
                        setTimeout(() => {
                            $('#addTransactionForm')[0].reset();
                            $('input[name="transactionType"][value="spent"]').prop('checked', true);
                            $('.btn-group label').removeClass('active');
                            $('label.btn-outline-danger').addClass('active');
                            currentTransactionType = 'spent';
                            updateCategoryDropdown();
                            $('#balanceCheckMessage').text('');
                            $('#transactionDate').val(new Date().toISOString().split('T')[0]);
                            
                            // Force modal to be ready for next open
                            $('#addTransactionModal').removeClass('show');
                            $('#addTransactionModal').css('display', 'none');
                        }, 300);
                        
                        // Show success animation
                        $('.wallet-card').addClass('success-animation')
                            .on('animationend', function() {
                                $(this).removeClass('success-animation');
                            });
                    }
                }, 1000);
            } else {
                alert(translations[currentLanguage]['fill_required_fields']);
            }
        });
        
        // Save customer
        $('#saveCustomerBtn').on('click', function() {
            const name = $('#customerName').val();
            const phone = $('#customerPhone').val();
            const address = $('#customerAddress').val();
            const amount = parseFloat($('#initialAmount').val()) || 0;
            const type = $('input[name="customerTransactionType"]:checked').val();
            
            if (name) {
                showLoading(this);
                
                setTimeout(() => {
                    addCustomer(name, phone, address, amount, type);
                    hideLoading(this);
                    
                    $('#addCustomerModal').modal('hide');
                    $('#addCustomerForm')[0].reset();
                    
                    // Show success animation
                    $('.customer-stats').addClass('success-animation')
                        .on('animationend', function() {
                            $(this).removeClass('success-animation');
                        });
                }, 1000);
            } else {
                alert(translations[currentLanguage]['enter_customer_name_alert']);
            }
        });
        
        // Add lend transaction
        $('#addLendTransactionBtn').on('click', function() {
            $('#addLendModal').modal('show');
        });
        
        // Save lend transaction
        $('#saveLendBtn').on('click', function() {
            const amount = parseFloat($('#lendAmount').val());
            const date = $('#lendDate').val();
            const description = $('#lendDescription').val();
            const type = $('input[name="lendType"]:checked').val();
            
            if (amount && amount > 0 && date && currentCustomerId) {
                showLoading(this);
                
                setTimeout(() => {
                    addCustomerTransaction(currentCustomerId, amount, date, description, type);
                    hideLoading(this);
                    
                    $('#addLendModal').modal('hide');
                    $('#addLendForm')[0].reset();
                }, 1000);
            } else {
                alert(translations[currentLanguage]['fill_required_fields']);
            }
        });
        
        // Receive payment
        $('#addReceiveBtn').on('click', function() {
            const customer = customers.find(c => c.id === currentCustomerId);
            if (customer && customer.balance < 0) {
                $('#receivePaymentModal').modal('show');
            } else {
                alert('No pending amount to receive from this customer');
            }
        });
        
        // Save receive payment
        $('#saveReceiveBtn').on('click', function() {
            const amount = parseFloat($('#receiveAmount').val());
            const date = $('#receiveDate').val();
            const description = $('#receiveDescription').val();
            const method = $('#receiveMethod').val();
            
            if (amount && amount > 0 && date && currentCustomerId) {
                const customer = customers.find(c => c.id === currentCustomerId);
                const maxAmount = Math.abs(customer.balance);
                
                if (amount > maxAmount) {
                    alert(`Amount cannot exceed pending balance of ₹${maxAmount.toFixed(2)}`);
                    return;
                }
                
                showLoading(this);
                
                setTimeout(() => {
                    addCustomerTransaction(currentCustomerId, amount, date, `${description} (${method})`, 'take');
                    hideLoading(this);
                    
                    $('#receivePaymentModal').modal('hide');
                    $('#receivePaymentForm')[0].reset();
                }, 1000);
            } else {
                alert(translations[currentLanguage]['fill_required_fields']);
            }
        });
        
        // Send reminder
        $('#sendReminderBtn').on('click', function() {
            const customer = customers.find(c => c.id === currentCustomerId);
            if (customer) {
                const amount = Math.abs(customer.balance);
                const message = `Reminder: You have pending amount of ₹${amount.toFixed(2)} with ${customer.name}.`;
                alert(message);
                // Here you can integrate with SMS/email services
            }
        });
        
        // Search functionality
        $('#searchTransactions').on('input', function( ) {
            const searchTerm = $(this).val().toLowerCase();
            filterTransactions(searchTerm);
        });
        
        $('#searchCustomer').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            filterCustomers(searchTerm);
        });
        
        // Modal hidden events to ensure loading states are reset - FIXED
        $('#addTransactionModal').on('hidden.bs.modal', function() {
            hideLoading($('#saveTransactionBtn'));
            $('#addTransactionForm')[0].reset();
            $('input[name="transactionType"][value="spent"]').prop('checked', true);
            $('.btn-group label').removeClass('active');
            $('label.btn-outline-danger').addClass('active');
            currentTransactionType = 'spent';
            updateCategoryDropdown();
            $('#balanceCheckMessage').text('');
            $('#transactionDate').val(new Date().toISOString().split('T')[0]);
            
            // Ensure modal is properly hidden
            $(this).removeClass('show');
            $(this).css('display', 'none');
            setTimeout(cleanupModalBackdrops, 100);
        });
        
        $('#addMoneyModal').on('hidden.bs.modal', function() {
            hideLoading($('#saveAddMoneyBtn'));
            $('#addMoneyForm')[0].reset();
            setTimeout(cleanupModalBackdrops, 100);
        });
        
        $('#addCustomerModal').on('hidden.bs.modal', function() {
            hideLoading($('#saveCustomerBtn'));
            $('#addCustomerForm')[0].reset();
            setTimeout(cleanupModalBackdrops, 100);
        });
        
        $('#addLendModal').on('hidden.bs.modal', function() {
            hideLoading($('#saveLendBtn'));
            $('#addLendForm')[0].reset();
            setTimeout(cleanupModalBackdrops, 100);
        });
        
        $('#receivePaymentModal').on('hidden.bs.modal', function() {
            hideLoading($('#saveReceiveBtn'));
            $('#receivePaymentForm')[0].reset();
            setTimeout(cleanupModalBackdrops, 100);
        });
        
        // Clean up backdrops when any modal is hidden
        $(document).on('hidden.bs.modal', '.modal', function () {
            setTimeout(cleanupModalBackdrops, 100);
        });
        
        // Fix for modal opening issue - reinitialize modal data attributes
        $(document).on('show.bs.modal', '.modal', function() {
            // Ensure all other modals are properly hidden
            $('.modal').not($(this)).removeClass('show').css('display', 'none');
        });
    }

    function updateBalanceCheckMessage() {
        const amount = parseFloat($('#transactionAmount').val()) || 0;
        const messageElement = $('#balanceCheckMessage');
        
        if (currentTransactionType === 'spent' && amount > 0) {
            if (amount > wallet.balance) {
                messageElement.text(`${translations[currentLanguage]['insufficient_balance']}! ${translations[currentLanguage]['available_balance']}: ₹${wallet.balance.toFixed(2)}`);
                messageElement.removeClass('text-muted').addClass('text-danger');
                $('#saveTransactionBtn').prop('disabled', true);
            } else {
                messageElement.text(`${translations[currentLanguage]['available_balance']}: ₹${wallet.balance.toFixed(2)}`);
                messageElement.removeClass('text-danger').addClass('text-muted');
                $('#saveTransactionBtn').prop('disabled', false);
            }
        } else {
            messageElement.text('');
            $('#saveTransactionBtn').prop('disabled', false);
        }
    }

    function filterTransactions(searchTerm) {
        const transactionItems = $('.transaction-item');
        let hasResults = false;
        
        transactionItems.each(function() {
            const text = $(this).text().toLowerCase();
            if (text.includes(searchTerm)) {
                $(this).show();
                hasResults = true;
            } else {
                $(this).hide();
            }
        });
        
        $('#emptyTransactionState').toggleClass('d-none', hasResults || wallet.transactions.length === 0);
    }

    function filterCustomers(searchTerm) {
        const customerCards = $('.customer-card');
        let hasResults = false;
        
        customerCards.each(function() {
            const customerName = $(this).find('.customer-card-header span').text().toLowerCase();
            if (customerName.includes(searchTerm)) {
                $(this).show();
                hasResults = true;
            } else {
                $(this).hide();
            }
        });
        
        $('#emptyCustomerState').toggleClass('d-none', hasResults || customers.length === 0);
    }
</script>
</body>
</html>
@endsection
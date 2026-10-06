@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khata Book - Digital Ledger & Wallet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
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
        
        body {
            font-family: 'Nunito', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            color: #4a4a4a;
            line-height: 1.6;
            min-height: 100vh;
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
        
        .nav-tabs .nav-link:hover {
            color: var(--primary);
            transform: translateY(-2px);
            background: rgba(108, 99, 255, 0.05);
        }
        
        .nav-tabs .nav-link.active {
            background: transparent;
            color: var(--primary);
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
        
        .badge-custom {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 0.8rem;
        }
        
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
        
        .modal-header .close {
            color: white !important;
            opacity: 0.8;
            text-shadow: none;
        }
        
        .modal-header .close:hover {
            opacity: 1;
            color: white !important;
        }
        
        .btn:disabled {
            opacity: 0.65 !important;
        }
        
        body.modal-open {
            overflow: hidden;
            padding-right: 0 !important;
        }
        
        .modal {
            z-index: 1060;
        }
        
        .modal-backdrop {
            z-index: 1050;
        }

        .filter-preset-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .stats-card.small {
            padding: 15px;
            margin-bottom: 10px;
        }

        .stats-card.small h6 {
            font-size: 1rem;
        }

        .stats-card.small i {
            font-size: 1.2rem;
        }

        .summary-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--primary);
            margin-bottom: 15px;
        }

        .summary-card .summary-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--dark);
        }

        .summary-card .summary-label {
            font-size: 0.8rem;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-active {
            border: 2px solid var(--primary) !important;
        }

        .date-range-inputs .form-control {
            height: 38px;
            font-size: 0.9rem;
        }

        .filter-group-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

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
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <div class="header position-relative">
            <button class="settings-btn" data-toggle="modal" data-target="#settingsModal">
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
                            <button class="wallet-btn add-money-btn" data-toggle="modal" data-target="#addMoneyModal">
                                <i class="fas fa-plus-circle mb-2" style="font-size: 1.5rem;"></i>
                                <span data-i18n="add_money">Add Money</span>
                            </button>
                            <button class="wallet-btn" id="spentBtn" data-toggle="modal" data-target="#addTransactionModal" data-type="spent" disabled>
                                <i class="fas fa-money-bill-wave mb-2" style="font-size: 1.5rem;"></i>
                                <span data-i18n="spent">Spent</span>
                            </button>
                            <button class="wallet-btn" id="receivedBtn" data-toggle="modal" data-target="#addTransactionModal" data-type="received" disabled>
                                <i class="fas fa-download mb-2" style="font-size: 1.5rem;"></i>
                                <span data-i18n="received">Received</span>
                            </button>
                            <button class="wallet-btn" id="lendBtn" data-toggle="modal" data-target="#addCustomerModal">
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
                    <a class="nav-link active" id="transactions-tab" data-toggle="tab" href="#transactions" role="tab" aria-controls="transactions" aria-selected="true">
                        <i class="fas fa-exchange-alt mr-2"></i> <span data-i18n="transactions">Transactions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="customers-tab" data-toggle="tab" href="#customers" role="tab" aria-controls="customers" aria-selected="false">
                        <i class="fas fa-users mr-2"></i> <span data-i18n="customers">Customers</span>
                        <span class="badge badge-light ml-1" id="customersCount">0</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="reports-tab" data-toggle="tab" href="#reports" role="tab" aria-controls="reports" aria-selected="false">
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
                
                <!-- Filter Section -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="card-title font-weight-bold mb-3"><i class="fas fa-filter mr-2"></i> Filter Transactions</h6>
                        
                        <div class="row">
                            <!-- Date Range Filter -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label" data-i18n="date_range">Date Range</label>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="date" class="form-control" id="filterFromDate" data-i18n-placeholder="from_date">
                                    </div>
                                    <div class="col-6">
                                        <input type="date" class="form-control" id="filterToDate" data-i18n-placeholder="to_date">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Transaction Type Filter -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label" data-i18n="transaction_type">Transaction Type</label>
                                <select class="form-control" id="filterType">
                                    <option value="">All Types</option>
                                    <option value="spent" data-i18n="spent">Spent</option>
                                    <option value="received" data-i18n="received">Received</option>
                                    <option value="lend" data-i18n="lend">Credit Given</option>
                                </select>
                            </div>
                            
                            <!-- Category Filter -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label" data-i18n="category">Category</label>
                                <select class="form-control" id="filterCategory">
                                    <option value="">All Categories</option>
                                    <optgroup label="Payment Methods">
                                        <option value="cash" data-i18n="cash">Cash</option>
                                        <option value="upi" data-i18n="upi">UPI</option>
                                        <option value="debit_card" data-i18n="debit_card">Debit Card</option>
                                        <option value="credit_card" data-i18n="credit_card">Credit Card</option>
                                        <option value="bank_transfer" data-i18n="bank_transfer">Bank Transfer</option>
                                    </optgroup>
                                    <optgroup label="Expense Categories">
                                        <option value="food" data-i18n="food">Food</option>
                                        <option value="shopping" data-i18n="shopping">Shopping</option>
                                        <option value="transport" data-i18n="transport">Transport</option>
                                        <option value="bills" data-i18n="bills">Bills</option>
                                    </optgroup>
                                    <optgroup label="Income Categories">
                                        <option value="salary" data-i18n="salary">Salary</option>
                                        <option value="business" data-i18n="business">Business</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button class="btn btn-primary btn-sm" id="applyFiltersBtn">
                                    <i class="fas fa-filter mr-1"></i> <span data-i18n="apply_filters">Apply Filters</span>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm ml-2" id="clearFiltersBtn">
                                    <i class="fas fa-times mr-1"></i> <span data-i18n="clear_filters">Clear Filters</span>
                                </button>
                            </div>
                            
                            <!-- Quick Date Presets -->
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" data-preset="today">Today</button>
                                <button class="btn btn-outline-primary" data-preset="yesterday">Yesterday</button>
                                <button class="btn btn-outline-primary" data-preset="thisWeek">This Week</button>
                                <button class="btn btn-outline-primary" data-preset="thisMonth">This Month</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Enhanced Summary Cards -->
                <div class="row mb-4" id="filterSummary" style="display: none;">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <i class="fas fa-money-bill-wave mb-2" style="font-size: 1.8rem; color: var(--danger);"></i>
                            <h6 class="mb-0 font-weight-bold" id="filterTotalSpent">₹0</h6>
                            <small class="text-muted" data-i18n="total_spent">Total Spent</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <i class="fas fa-download mb-2" style="font-size: 1.8rem; color: var(--success);"></i>
                            <h6 class="mb-0 font-weight-bold" id="filterTotalReceived">₹0</h6>
                            <small class="text-muted" data-i18n="total_received">Total Received</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <i class="fas fa-hand-holding-usd mb-2" style="font-size: 1.8rem; color: var(--info);"></i>
                            <h6 class="mb-0 font-weight-bold" id="filterTotalLend">₹0</h6>
                            <small class="text-muted" data-i18n="total_lend">Credit Given</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <i class="fas fa-calculator mb-2" style="font-size: 1.8rem; color: var(--primary);"></i>
                            <h6 class="mb-0 font-weight-bold" id="filterNetBalance">₹0</h6>
                            <small class="text-muted" data-i18n="net_balance">Net Balance</small>
                        </div>
                    </div>
                </div>

                <!-- Detailed Summary -->
                <div class="card border-0 shadow-sm mb-4" id="detailedSummary" style="display: none;">
                    <div class="card-body">
                        <h6 class="card-title font-weight-bold mb-3"><i class="fas fa-chart-line mr-2"></i> Detailed Summary</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4 class="mb-1" id="filterProfitLoss">₹0</h4>
                                    <small class="text-muted" data-i18n="profit_loss">Profit/Loss</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4 class="mb-1" id="filterAvgPerDay">₹0</h4>
                                    <small class="text-muted" data-i18n="average_per_day">Avg per Day</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4 class="mb-1" id="filterDaysCount">0</h4>
                                    <small class="text-muted" data-i18n="days_count">Days</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4 class="mb-1" id="filterTransactionsCount">0</h4>
                                    <small class="text-muted" data-i18n="transactions_count">Transactions</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Transactions List -->
                <div id="transactionsList">
                    <!-- Transactions will be dynamically added here -->
                </div>
                
                <!-- Pagination -->
                <nav aria-label="Transaction pagination" id="transactionsPagination" style="display: none;">
                    <ul class="pagination justify-content-center"></ul>
                </nav>
                
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
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addCustomerModal" style="width:270px;">
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
                
                <!-- <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title font-weight-bold" data-i18n="monthly_summary">Monthly Summary</h6>
                        <canvas id="monthlyChart" height="150"></canvas>
                    </div>
                </div> -->
                
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title font-weight-bold" data-i18n="category_breakdown">Category Breakdown</h6>
                        <canvas id="categoryChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Action Button -->
        <button class="floating-btn" data-toggle="modal" data-target="#addMoneyModal">
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
                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
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
                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
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
                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
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
    // API Base URL
    const API_BASE_URL = '/institute-admin/khata/api';
    
    // Global variables
    let currentCustomerId = null;
    let currentLanguage = localStorage.getItem('khataLanguage') || 'en';
    let currentTransactionType = 'spent';
    let currentPage = {
        transactions: 1,
        customers: 1
    };
    let perPage = 10;
    let currentFilters = {
        search: '',
        fromDate: '',
        toDate: '',
        type: '',
        category: ''
    };
    let isFilterActive = false;

    // Language translations
    const translations = {
        en: {
            "app_title": "Khata Book",
            "app_subtitle": "Digital Ledger & Khata Book Manager",
            "wallet_balance": "Khata Book Balance",
            "add_money": "Add Money",
            "add_money_to_wallet": "Add Money to Khata Book",
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
            "zero_balance_alert": "Add money to your Khata Book to start transactions",
            "zero_balance_message": "You need to add balance first to use Spent and Received features",
            "insufficient_balance": "Insufficient Khata Book balance",
            "available_balance": "Available balance",
            "money_added_success": "Money added to Khata Book successfully!",
            "transactions": "Transactions",
            "customers": "Customers",
            "reports": "Reports",
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
            "search_transactions": "Search transactions...",
            "search_customer": "Search customer...",
            "no_transactions_found": "No Transactions Found",
            "add_first_transaction": "Add your first transaction to get started",
            "no_customers_found": "No Customers Found",
            "add_first_customer": "Add your first customer to get started",
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
            "customer_list": "Customer List",
            "recent_transactions": "Recent Transactions",
            "business_reports": "Business Reports",
            "monthly_summary": "Monthly Summary",
            "category_breakdown": "Category Breakdown",
            "transactions_by_category": "Transactions by Category",
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
            "reminder_sent": "Reminder sent successfully!",
            "fill_required_fields": "Please fill all required fields",
            "enter_customer_name_alert": "Please enter customer name",
            "no_transactions": "No transactions yet",
            "date_range": "Date Range",
            "from_date": "From Date",
            "to_date": "To Date",
            "apply_filters": "Apply Filters",
            "clear_filters": "Clear Filters",
            "filtered_results": "Filtered Results",
            "today": "Today",
            "yesterday": "Yesterday",
            "thisWeek": "This Week",
            "thisMonth": "This Month",
            "total_filtered_spent": "Total Spent",
            "total_filtered_received": "Total Received",
            "total_filtered_lend": "Total Credit Given",
            "net_balance": "Net Balance",
            "profit_loss": "Profit/Loss",
            "average_per_day": "Average per Day",
            "days_count": "Days Count",
            "transactions_count": "Transactions Count"
        },
        hi: {
            // Hindi translations (simplified for brevity)
            "app_subtitle": "डिजिटल लेजर और वॉलेट मैनेजर",
            "wallet_balance": "वॉलेट बैलेंस",
            "add_money": "पैसे जोड़ें",
            "spent": "खर्च",
            "received": "प्राप्त",
            "lend": "उधार",
            "transactions": "लेनदेन",
            "customers": "ग्राहक"
        },
        gu: {
            // Gujarati translations (simplified for brevity)
            "app_subtitle": "ડિજિટલ લેજર અને વૉલેટ મેનેજર",
            "wallet_balance": "વૉલેટ બેલેન્સ",
            "add_money": "પૈસા ઉમેરો",
            "spent": "ખર્ચ",
            "received": "મળ્યું",
            "lend": "ઉધાર",
            "transactions": "લેનદેન",
            "customers": "ગ્રાહકો"
        }
    };

    // Initialize the app
    $(document).ready(function() {
        // Set CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        console.log('API Base URL:', API_BASE_URL);
        console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));

        // Set initial language
        $('#languageSelect').val(currentLanguage);
        updateLanguage(currentLanguage);
        
        // Load initial data
        loadInitialData();
        
        // Set today's date as default for transaction date
        const today = new Date().toISOString().split('T')[0];
        $('#transactionDate').val(today);
        $('#lendDate').val(today);
        $('#receiveDate').val(today);
        
        // Initialize category dropdown based on transaction type
        updateCategoryDropdown();
        
        // Setup event listeners
        setupEventListeners();
        
        // Load customers for dropdown
        loadCustomersForDropdown();
        
        // Test API connection
        testApiConnection();
    });

    // Test API connection
    function testApiConnection() {
        console.log('Testing API connection...');
        $.ajax({
            url: API_BASE_URL + '/initial-data',
            type: 'GET',
            success: function(response) {
                console.log('✅ API Connection Successful:', response);
            },
            error: function(xhr, status, error) {
                console.error('❌ API Connection Failed:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    response: xhr.responseText,
                    error: error
                });
            }
        });
    }

    // Load initial data from API
    function loadInitialData() {
        showLoading();
        $.ajax({
            url: API_BASE_URL + '/initial-data',
            type: 'GET',
            success: function(response) {
                console.log('Initial Data Response:', response);
                if (response.success) {
                    updateWalletDisplay(response.wallet);
                    updateCategoryTotals(response.category_totals);
                    renderTransactionsList(response.recent_transactions);
                    renderCustomerList(response.recent_customers);
                    updateCustomerStats(response.customer_stats);
                    updateTransactionButtons(response.wallet.balance);
                    
                    // Set language from settings
                    if (response.language) {
                        currentLanguage = response.language;
                        $('#languageSelect').val(currentLanguage);
                        updateLanguage(currentLanguage);
                    }
                    
                    // Initialize charts with real data
                    initializeCharts(response.category_totals);
                } else {
                    console.error('API Error:', response.message);
                }
                hideLoading();
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    response: xhr.responseText,
                    error: error
                });
                hideLoading();
            }
        });
    }

    // Load customers for dropdown in transaction modal
    function loadCustomersForDropdown() {
        $.ajax({
            url: API_BASE_URL + '/customers',
            type: 'GET',
            data: { per_page: 100 },
            success: function(response) {
                if (response.success) {
                    const customerSelect = $('#transactionCustomer');
                    customerSelect.empty();
                    customerSelect.append('<option value="" data-i18n="select_customer">Select Customer</option>');
                    
                    response.customers.forEach(customer => {
                        customerSelect.append(`<option value="${customer.id}">${customer.name}</option>`);
                    });
                }
            },
            error: function(xhr) {
                console.error('Error loading customers:', xhr.responseText);
            }
        });
    }

    // Update wallet display
    function updateWalletDisplay(wallet) {
        if (!wallet) return;
        
        $('#walletBalance').text(`₹${parseFloat(wallet.balance).toFixed(2)}`);
        $('#walletLastUpdated').text(`Last updated: ${formatRelativeTime(new Date(wallet.last_updated))}`);
        
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
    }

    // Update category totals
    function updateCategoryTotals(totals) {
        if (!totals) return;
        
        $('#totalSpent').text(`₹${parseFloat(totals.spent).toFixed(2)}`);
        $('#totalReceived').text(`₹${parseFloat(totals.received).toFixed(2)}`);
        $('#totalLend').text(`₹${parseFloat(totals.lend).toFixed(2)}`);
    }

    // Update transaction buttons based on wallet balance
    function updateTransactionButtons(balance) {
        const hasBalance = parseFloat(balance) > 0;
        
        $('#spentBtn').prop('disabled', !hasBalance);
        $('#receivedBtn').prop('disabled', !hasBalance);
        $('#lendBtn').prop('disabled', false);
        
        if (!hasBalance) {
            $('#spentBtn').attr('title', 'Add money to wallet first');
            $('#receivedBtn').attr('title', 'Add money to wallet first');
        } else {
            $('#spentBtn').removeAttr('title');
            $('#receivedBtn').removeAttr('title');
        }
    }

    // Render customer list
    function renderCustomerList(customers) {
        const customerList = $('#customerList');
        customerList.empty();
        
        if (!customers || customers.length === 0) {
            $('#emptyCustomerState').removeClass('d-none');
            $('#customersPagination').hide();
            return;
        }
        
        $('#emptyCustomerState').addClass('d-none');
        $('#customersPagination').show();
        
        customers.forEach((customer, index) => {
            const balanceClass = customer.balance >= 0 ? 'amount-positive' : 'amount-negative';
            const balanceText = customer.balance >= 0 ? 
                `${translations[currentLanguage]['you_will_take']} ₹${Math.abs(customer.balance).toFixed(2)}` : 
                `${translations[currentLanguage]['you_will_give']} ₹${Math.abs(customer.balance).toFixed(2)}`;
            
            const customerCard = `
                <div class="card customer-card" style="animation-delay: ${index * 0.1}s" data-customer-id="${customer.id}">
                    <div class="customer-card-header d-flex justify-content-between align-items-center">
                        <span>${customer.name}</span>
                        <div>
                            <span class="badge-custom me-2">${customer.phone || 'No phone'}</span>
                            <span class="badge ${customer.balance !== 0 ? 'badge-warning' : 'badge-success'}">
                                ${customer.total_transactions || 0} trans
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="customer-avatar mr-3">${customer.initial || customer.name.charAt(0)}</div>
                                <div>
                                    <h6 class="mb-0">${balanceText}</h6>
                                    <small class="text-muted">
                                        ${customer.last_activity ? 
                                            `Last: ${formatRelativeTime(new Date(customer.last_activity))}` : 
                                            translations[currentLanguage]['no_transactions']}
                                    </small>
                                </div>
                            </div>
                            <div class="text-right">
                                <h4 class="${balanceClass}">₹${Math.abs(customer.balance).toFixed(2)}</h4>
                                <small class="text-muted">
                                    ${formatRelativeTime(new Date(customer.updated_at))}
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

    // Render transactions list
    function renderTransactionsList(transactions) {
        const transactionsList = $('#transactionsList');
        transactionsList.empty();
        
        if (!transactions || transactions.length === 0) {
            let message = translations[currentLanguage]['no_transactions_found'];
            if (isFilterActive) {
                message = translations[currentLanguage]['no_transactions_found'] + ' with current filters';
            }
            
            $('#emptyTransactionState h5').text(message);
            $('#emptyTransactionState').removeClass('d-none');
            $('#transactionsPagination').hide();
            return;
        }
        
        $('#emptyTransactionState').addClass('d-none');
        $('#transactionsPagination').show();
        
        // Add filter status header
        if (isFilterActive) {
            const filterStatus = createFilterStatusText();
            transactionsList.append(`
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle mr-2"></i>
                    ${filterStatus}
                    <button class="btn btn-sm btn-outline-info ml-2" id="clearFiltersInline">
                        <i class="fas fa-times mr-1"></i> Clear Filters
                    </button>
                </div>
            `);
            
            // Add event listener for inline clear button
            $('#clearFiltersInline').on('click', clearFilters);
        }
        
        transactions.forEach((transaction, index) => {
            const amountClass = transaction.type === 'received' ? 'amount-positive' : 
                            transaction.type === 'lend' ? 'amount-neutral' : 'amount-negative';
            const typeText = translations[currentLanguage][transaction.type] || transaction.type;
            const typeBadgeClass = `type-${transaction.type}`;
            
            // Get customer name if it's a customer transaction
            let customerInfo = '';
            if (transaction.customer) {
                customerInfo = `<small class="text-muted me-2">${transaction.customer.name}</small>`;
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
                            <h5 class="${amountClass} mb-1">${transaction.type === 'spent' ? '-' : '+'}₹${parseFloat(transaction.amount).toFixed(2)}</h5>
                            <small class="text-muted">${formatRelativeTime(new Date(transaction.created_at))}</small>
                        </div>
                    </div>
                </div>
            `;
            
            transactionsList.append(transactionItem);
        });
    }

    // Create filter status text
    function createFilterStatusText() {
        let status = translations[currentLanguage]['filtered_results'] + ': ';
        const parts = [];
        
        if (currentFilters.search) {
            parts.push(`Search: "${currentFilters.search}"`);
        }
        
        if (currentFilters.fromDate || currentFilters.toDate) {
            let dateText = '';
            if (currentFilters.fromDate && currentFilters.toDate) {
                dateText = `${currentFilters.fromDate} to ${currentFilters.toDate}`;
            } else if (currentFilters.fromDate) {
                dateText = `From ${currentFilters.fromDate}`;
            } else if (currentFilters.toDate) {
                dateText = `Until ${currentFilters.toDate}`;
            }
            parts.push(`Date: ${dateText}`);
        }
        
        if (currentFilters.type) {
            const typeText = translations[currentLanguage][currentFilters.type] || currentFilters.type;
            parts.push(`Type: ${typeText}`);
        }
        
        if (currentFilters.category) {
            const categoryText = translations[currentLanguage][currentFilters.category] || currentFilters.category;
            parts.push(`Category: ${categoryText}`);
        }
        
        return status + parts.join(', ');
    }

    // Update filter summary
    function updateFilterSummary(summary) {
        // Update basic summary cards
        $('#filterTotalSpent').text(`₹${parseFloat(summary.total_spent || 0).toFixed(2)}`);
        $('#filterTotalReceived').text(`₹${parseFloat(summary.total_received || 0).toFixed(2)}`);
        $('#filterTotalLend').text(`₹${parseFloat(summary.total_lend || 0).toFixed(2)}`);
        
        // Calculate and update net balance
        const netBalance = (summary.total_received || 0) - (summary.total_spent || 0);
        const netBalanceElement = $('#filterNetBalance');
        netBalanceElement.text(`₹${netBalance.toFixed(2)}`);
        netBalanceElement.removeClass('amount-positive amount-negative')
                        .addClass(netBalance >= 0 ? 'amount-positive' : 'amount-negative');
        
        // Update detailed summary if available
        if (summary.net_balance !== undefined) {
            $('#filterProfitLoss').text(`₹${parseFloat(summary.net_balance || 0).toFixed(2)}`);
            $('#filterAvgPerDay').text(`₹${parseFloat(summary.average_per_day || 0).toFixed(2)}`);
            $('#filterDaysCount').text(summary.days_count || '-');
            $('#filterTransactionsCount').text(summary.total_count || 0);
            
            $('#detailedSummary').show();
        } else {
            $('#detailedSummary').hide();
        }
        
        // Show summary sections
        $('#filterSummary').show();
    }

    // Load transactions with filters
    function loadTransactions(page = 1) {
        showLoading();
        
        // Build query parameters
        const params = {
            page: page,
            per_page: perPage
        };
        
        // Add filters if they exist
        if (currentFilters.search) params.search = currentFilters.search;
        if (currentFilters.fromDate) params.from_date = currentFilters.fromDate;
        if (currentFilters.toDate) params.to_date = currentFilters.toDate;
        if (currentFilters.type) params.type = currentFilters.type;
        if (currentFilters.category) params.category = currentFilters.category;
        
        $.ajax({
            url: API_BASE_URL + '/transactions',
            type: 'GET',
            data: params,
            success: function(response) {
                if (response.success) {
                    renderTransactionsList(response.transactions.data);
                    renderPagination('transactions', response.transactions);
                    currentPage.transactions = page;
                    
                    // Show/hide filter summary
                    if (isFilterActive && response.summary) {
                        updateFilterSummary(response.summary);
                        $('#filterSummary').show();
                    } else {
                        $('#filterSummary').hide();
                    }
                }
                hideLoading();
            },
            error: function(xhr) {
                console.error('Error loading transactions:', xhr.responseText);
                hideLoading();
            }
        });
    }

    // Load more customers with pagination
    function loadCustomers(page = 1) {
        showLoading();
        $.ajax({
            url: API_BASE_URL + '/customers',
            type: 'GET',
            data: {
                page: page,
                per_page: perPage
            },
            success: function(response) {
                if (response.success) {
                    renderCustomerList(response.customers.data);
                    renderPagination('customers', response.customers);
                    currentPage.customers = page;
                }
                hideLoading();
            },
            error: function(xhr) {
                console.error('Error loading customers:', xhr.responseText);
                hideLoading();
            }
        });
    }

    // Render pagination
    function renderPagination(type, paginationData) {
        const paginationContainer = $(`#${type}Pagination .pagination`);
        paginationContainer.empty();
        
        if (paginationData.last_page <= 1) {
            $(`#${type}Pagination`).hide();
            return;
        }
        
        $(`#${type}Pagination`).show();
        
        // Previous button
        const prevDisabled = paginationData.current_page === 1 ? 'disabled' : '';
        paginationContainer.append(`
            <li class="page-item ${prevDisabled}">
                <a class="page-link" href="#" data-page="${paginationData.current_page - 1}">Previous</a>
            </li>
        `);
        
        // Page numbers
        for (let i = 1; i <= paginationData.last_page; i++) {
            const active = i === paginationData.current_page ? 'active' : '';
            paginationContainer.append(`
                <li class="page-item ${active}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `);
        }
        
        // Next button
        const nextDisabled = paginationData.current_page === paginationData.last_page ? 'disabled' : '';
        paginationContainer.append(`
            <li class="page-item ${nextDisabled}">
                <a class="page-link" href="#" data-page="${paginationData.current_page + 1}">Next</a>
            </li>
        `);
        
        // Attach click events
        paginationContainer.find('.page-link').on('click', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            if (type === 'transactions') {
                loadTransactions(page);
            } else if (type === 'customers') {
                loadCustomers(page);
            }
        });
    }

    // Update customer stats
    function updateCustomerStats(stats) {
        if (!stats) return;
        
        $('#totalCustomersCount').text(stats.total_customers || 0);
        $('#activeCustomersCount').text(stats.active_customers || 0);
        $('#totalCreditGiven').text(`₹${parseFloat(stats.total_credit_given || 0).toFixed(2)}`);
        $('#customersCount').text(stats.total_customers || 0);
    }

    // Initialize charts with real data
    function initializeCharts(categoryTotals) {
        // Monthly chart
        // const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        
        // Category chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        if (categoryTotals) {
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Spent', 'Received', 'Lend'],
                    datasets: [{
                        data: [
                            parseFloat(categoryTotals.spent || 0),
                            parseFloat(categoryTotals.received || 0),
                            parseFloat(categoryTotals.lend || 0)
                        ],
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
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${label}: ₹${value.toLocaleString()} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Open customer details
    function openCustomerDetails(customerId) {
        showLoading();
        $.ajax({
            url: API_BASE_URL + '/customers/' + customerId,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    currentCustomerId = customerId;
                    
                    // Update modal content
                    const customer = response.customer;
                    $('#customerAvatar').text(customer.initial || customer.name.charAt(0));
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
                    updateCustomerDetailStats(response.stats);
                    
                    // Render transaction history
                    renderCustomerTransactionHistory(response.transactions);
                    
                    // Set customer ID for lend modal
                    $('#lendCustomerId').val(customerId);
                    $('#receiveCustomerId').val(customerId);
                    
                    // Show the modal
                    $('#customerDetailModal').modal('show');
                }
                hideLoading();
            },
            error: function(xhr) {
                console.error('Error loading customer details:', xhr.responseText);
                hideLoading();
            }
        });
    }

    // Update customer detail stats
    function updateCustomerDetailStats(stats) {
        if (!stats) return;
        
        $('#customerTotalTransactions').text(stats.total_transactions || 0);
        $('#customerPendingAmount').text(`₹${parseFloat(stats.pending_amount || 0).toFixed(2)}`);
        $('#customerLastActivity').text(stats.last_activity || '-');
        
        // Update max receive amount
        $('#maxReceiveAmount').text(parseFloat(stats.pending_amount || 0).toFixed(2));
    }

    // Render customer transaction history
    function renderCustomerTransactionHistory(transactions) {
        const transactionHistory = $('#customerTransactionHistory');
        transactionHistory.empty();
        
        if (!transactions || transactions.length === 0) {
            transactionHistory.html(`
                <div class="empty-state" style="padding: 20px;">
                    <i class="fas fa-exchange-alt"></i>
                    <p>${translations[currentLanguage]['no_transactions']}</p>
                </div>
            `);
            return;
        }
        
        transactions.forEach((transaction, index) => {
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
                            <h5 class="transaction-amount ${amountClass} mb-1">${amountSign}₹${parseFloat(transaction.amount).toFixed(2)}</h5>
                            <small class="text-muted">${formatRelativeTime(new Date(transaction.created_at))}</small>
                        </div>
                    </div>
                </div>
            `;
            
            transactionHistory.append(transactionItem);
        });
    }

    // Utility functions
    function formatRelativeTime(date) {
        if (!date) return 'Never';
        const now = new Date();
        const diffInSeconds = Math.floor((now - new Date(date)) / 1000);
        
        if (diffInSeconds < 60) return 'Just now';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`;
        if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)} days ago`;
        return new Date(date).toLocaleDateString();
    }

    function showLoading() {
        $('.ajax-loading').show();
    }

    function hideLoading() {
        $('.ajax-loading').hide();
    }

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

    // Setup event listeners
    function setupEventListeners() {
        // Language selector
        $('#languageSelect').on('change', function() {
            const selectedLanguage = $(this).val();
            updateLanguage(selectedLanguage);
        });
        
        // Clear data button
        $('#clearDataBtn').on('click', function() {
            if (confirm('Are you sure you want to clear all data? This action cannot be undone.')) {
                showLoading();
                $.ajax({
                    url: API_BASE_URL + '/clear-all-data',
                    type: 'POST',
                    success: function(response) {
                        if (response.success) {
                            alert('All data cleared successfully!');
                            loadInitialData();
                            $('#settingsModal').modal('hide');
                        } else {
                            alert(response.message || 'Error clearing data');
                        }
                        hideLoading();
                    },
                    error: function(xhr) {
                        alert('Error clearing data. Please try again.');
                        hideLoading();
                    }
                });
            }
        });
        
        // Export data button
        $('#exportDataBtn').on('click', function() {
            alert('Export feature will be implemented soon!');
        });
        
        // Add money button
        $('#saveAddMoneyBtn').on('click', function() {
            const amount = parseFloat($('#addAmount').val());
            const description = $('#addMoneyDescription').val() || 'Wallet deposit';
            const source = $('#addMoneySource').val();
            
            if (amount && amount > 0) {
                showLoading();
                
                $.ajax({
                    url: API_BASE_URL + '/add-money',
                    type: 'POST',
                    data: {
                        amount: amount,
                        description: description,
                        source: source
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Money added successfully!');
                            $('#addMoneyModal').modal('hide');
                            $('#addMoneyForm')[0].reset();
                            loadInitialData();
                            
                            // Show success animation
                            $('.wallet-card').addClass('success-animation')
                                .on('animationend', function() {
                                    $(this).removeClass('success-animation');
                                });
                            window.location.reload();
                        } else {
                            alert(response.message || 'Error adding money');
                        }
                        hideLoading();
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON && xhr.responseJSON.message 
                            ? xhr.responseJSON.message 
                            : 'Error adding money. Please try again.';
                        alert(errorMsg);
                        hideLoading();
                    }
                });
            } else {
                alert('Please enter a valid amount');
            }
        });
        
        // Wallet action buttons
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
        
        // Save transaction
        $('#saveTransactionBtn').on('click', function() {
            const amount = parseFloat($('#transactionAmount').val());
            const date = $('#transactionDate').val();
            const description = $('#transactionDescription').val();
            const category = $('#transactionCategory').val();
            const customerId = $('#transactionCustomer').val() || null;
            
            if (amount && amount > 0 && date) {
                showLoading();
                
                $.ajax({
                    url: API_BASE_URL + '/add-transaction',
                    type: 'POST',
                    data: {
                        amount: amount,
                        date: date,
                        description: description,
                        type: currentTransactionType,
                        category: category,
                        customer_id: customerId
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Transaction added successfully!');
                            $('#addTransactionModal').modal('hide');
                            $('#addTransactionForm')[0].reset();
                            loadInitialData();
                            
                            // Show success animation
                            $('.wallet-card').addClass('success-animation')
                                .on('animationend', function() {
                                    $(this).removeClass('success-animation');
                                });
                            window.location.reload();
                        } else {
                            alert(response.message || 'Error adding transaction');
                        }
                        hideLoading();
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON && xhr.responseJSON.message 
                            ? xhr.responseJSON.message 
                            : 'Error adding transaction. Please try again.';
                        alert(errorMsg);
                        hideLoading();
                    }
                });
            } else {
                alert('Please fill all required fields');
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
                showLoading();
                
                $.ajax({
                    url: API_BASE_URL + '/add-customer',
                    type: 'POST',
                    data: {
                        name: name,
                        phone: phone,
                        address: address,
                        initial_amount: amount,
                        transaction_type: type
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Customer added successfully!');
                            $('#addCustomerModal').modal('hide');
                            $('#addCustomerForm')[0].reset();
                            loadInitialData();
                            loadCustomersForDropdown();
                            
                            // Show success animation
                            $('.customer-stats').addClass('success-animation')
                                .on('animationend', function() {
                                    $(this).removeClass('success-animation');
                                });
                            window.location.reload();
                        } else {
                            alert(response.message || 'Error adding customer');
                        }
                        hideLoading();
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON && xhr.responseJSON.message 
                            ? xhr.responseJSON.message 
                            : 'Error adding customer. Please try again.';
                        alert(errorMsg);
                        hideLoading();
                    }
                });
            } else {
                alert('Please enter customer name');
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
            const customerId = localStorage.getItem('customer_id');
            
            if (amount && amount > 0 && date && customerId) {
                showLoading();
                
                $.ajax({
                    url: API_BASE_URL + '/customers/' + customerId + '/transactions',
                    type: 'POST',
                    data: {
                        amount: amount,
                        date: date,
                        description: description,
                        type: type,
                        payment_method: 'cash'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Transaction added successfully!');
                            $('#addLendModal').modal('hide');
                            $('#addLendForm')[0].reset();
                            openCustomerDetails(customerId);
                            loadInitialData();
                        } else {
                            alert(response.message || 'Error adding transaction');
                        }
                        hideLoading();
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON && xhr.responseJSON.message 
                            ? xhr.responseJSON.message 
                            : 'Error adding transaction. Please try again.';
                        alert(errorMsg);
                        hideLoading();
                    }
                });
            } else {
                alert('Please fill all required fields');
            }
        });
        
        // Receive payment
        $('#addReceiveBtn').on('click', function() {
            if (currentCustomerId) {
                $.ajax({
                    url: API_BASE_URL + '/customers/' + currentCustomerId,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const customer = response.customer;
                            if (customer.balance < 0) {
                                $('#receivePaymentModal').modal('show');
                            } else {
                                alert('No pending amount to receive from this customer');
                            }
                        }
                    }
                });
            }
        });
        
        // Save receive payment
        $('#saveReceiveBtn').on('click', function() {
            const amount = parseFloat($('#receiveAmount').val());
            const date = $('#receiveDate').val();
            const description = $('#receiveDescription').val();
            const method = $('#receiveMethod').val();
            const customerId = localStorage.getItem('customer_id');
            
            if (amount && amount > 0 && date && customerId) {
                showLoading();
                
                $.ajax({
                    url: API_BASE_URL + '/customers/' + customerId + '/transactions',
                    type: 'POST',
                    data: {
                        amount: amount,
                        date: date,
                        description: description,
                        type: 'take',
                        payment_method: method
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Payment received successfully!');
                            $('#receivePaymentModal').modal('hide');
                            $('#receivePaymentForm')[0].reset();
                            openCustomerDetails(customerId);
                            loadInitialData();
                            window.location.reload();
                        } else {
                            alert(response.message || 'Error receiving payment');
                        }
                        hideLoading();
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON && xhr.responseJSON.message 
                            ? xhr.responseJSON.message 
                            : 'Error receiving payment. Please try again.';
                        alert(errorMsg);
                        hideLoading();
                    }
                });
            } else {
                alert('Please fill all required fields');
            }
        });
        
        // Send reminder
        $('#sendReminderBtn').on('click', function() {
            if (currentCustomerId) {
                $.ajax({
                    url: API_BASE_URL + '/customers/' + currentCustomerId,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const customer = response.customer;
                            const amount = Math.abs(customer.balance);
                            const message = `Reminder: You have pending amount of ₹${amount.toFixed(2)} with ${customer.name}.`;
                            alert(message);
                        }
                    }
                });
            }
        });
        
        // Search functionality
        $('#searchTransactions').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            currentFilters.search = searchTerm;
            applyFilters();
        });
        
        $('#searchCustomer').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            filterCustomers(searchTerm);
        });
        
        // Tab click events
        $('#transactions-tab').on('click', function() {
            loadTransactions();
        });
        
        $('#customers-tab').on('click', function() {
            loadCustomers();
        });
        
        $('#reports-tab').on('click', function() {
            loadReportsData();
        });
        
        // View all transactions for customer
        $('#viewAllTransactions').on('click', function() {
            if (currentCustomerId) {
                // Implement view all transactions for customer
                alert('View all transactions feature will be implemented soon!');
            }
        });
        
        // Filter event listeners
        $('#applyFiltersBtn').on('click', function() {
            applyFilters();
        });
        
        $('#clearFiltersBtn').on('click', function() {
            clearFilters();
        });
        
        $('#filterFromDate, #filterToDate, #filterType, #filterCategory').on('change', function() {
            // Apply filters automatically when any filter changes
            setTimeout(() => applyFilters(), 300);
        });
        
        // Quick date preset buttons
        $('[data-preset]').on('click', function() {
            const preset = $(this).data('preset');
            setDatePreset(preset);
            applyFilters();
        });
    }

    function updateBalanceCheckMessage() {
        const amount = parseFloat($('#transactionAmount').val()) || 0;
        const messageElement = $('#balanceCheckMessage');
        
        if (currentTransactionType === 'spent' && amount > 0) {
            // Get current wallet balance
            const walletBalance = parseFloat($('#walletBalance').text().replace('₹', '')) || 0;
            
            if (amount > walletBalance) {
                messageElement.text(`Insufficient balance! Available: ₹${walletBalance.toFixed(2)}`);
                messageElement.removeClass('text-muted').addClass('text-danger');
                $('#saveTransactionBtn').prop('disabled', true);
            } else {
                messageElement.text(`Available balance: ₹${walletBalance.toFixed(2)}`);
                messageElement.removeClass('text-danger').addClass('text-muted');
                $('#saveTransactionBtn').prop('disabled', false);
            }
        } else {
            messageElement.text('');
            $('#saveTransactionBtn').prop('disabled', false);
        }
    }

    // Apply filters
    function applyFilters() {
        // Update current filters
        currentFilters = {
            search: $('#searchTransactions').val().toLowerCase(),
            fromDate: $('#filterFromDate').val(),
            toDate: $('#filterToDate').val(),
            type: $('#filterType').val(),
            category: $('#filterCategory').val()
        };
        
        isFilterActive = Object.values(currentFilters).some(value => value !== '');
        
        // Load transactions with filters
        loadTransactions(1);
    }

    // Clear filters
    function clearFilters() {
        // Clear all filter inputs
        $('#searchTransactions').val('');
        $('#filterFromDate').val('');
        $('#filterToDate').val('');
        $('#filterType').val('');
        $('#filterCategory').val('');
        
        // Reset current filters
        currentFilters = {
            search: '',
            fromDate: '',
            toDate: '',
            type: '',
            category: ''
        };
        
        isFilterActive = false;
        $('#filterSummary').hide();
        
        // Reload transactions without filters
        loadTransactions(1);
    }

    // Set date presets
    function setDatePreset(preset) {
        const today = new Date();
        let fromDate = new Date();
        let toDate = new Date();
        
        switch(preset) {
            case 'today':
                fromDate = today;
                toDate = today;
                break;
            case 'yesterday':
                fromDate = new Date(today);
                fromDate.setDate(today.getDate() - 1);
                toDate = fromDate;
                break;
            case 'thisWeek':
                const day = today.getDay();
                const diff = today.getDate() - day + (day === 0 ? -6 : 1);
                fromDate = new Date(today.setDate(diff));
                toDate = new Date();
                break;
            case 'thisMonth':
                fromDate = new Date(today.getFullYear(), today.getMonth(), 1);
                toDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                break;
        }
        
        // Format dates as YYYY-MM-DD
        $('#filterFromDate').val(formatDateForInput(fromDate));
        $('#filterToDate').val(formatDateForInput(toDate));
    }

    // Helper function to format date for input field
    function formatDateForInput(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
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
        
        $('#emptyCustomerState').toggleClass('d-none', hasResults || customerCards.length > 0);
    }

    function attachCustomerCardClickEvents() {
        $('.customer-card').off('click').on('click', function() {
            const customerId = $(this).data('customer-id');
            localStorage.setItem('customer_id', customerId);
            openCustomerDetails(customerId);
        });
    }

    function loadReportsData() {
        // Implement reports data loading
        // This would make API calls to get monthly and category data
        alert('Reports feature will be implemented soon!');
    }

    // Modal cleanup functions
    function cleanupModalBackdrops() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('padding-right', '');
        $('.modal.show').removeClass('show');
        $('.modal').css('display', 'none');
    }
    
    // Initialize on page load
    $(window).on('load', function() {
        cleanupModalBackdrops();
    });
</script>
</body>
</html>
@endsection
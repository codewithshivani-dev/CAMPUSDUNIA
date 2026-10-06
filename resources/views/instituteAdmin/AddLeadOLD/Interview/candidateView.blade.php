@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@section('content')
<style>
    /* Copy all your CSS styles here - keeping them exactly as you had them */
    :root {
        --primary: #3b82f6;
        --primary-dark: #2563eb;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --purple: #8b5cf6;
        --dark: #1e293b;
        --light: #f8fafc;
        --gray: #64748b;
        --border: #e2e8f0;
    }

    /* Desktop Steps Grid */
    .desktop-steps {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }

    /* Mobile Steps Container - Hidden on Desktop */
    .mobile-steps-container {
        display: none;
        position: relative;
        width: 100%;
        margin-top: 15px;
        overflow: hidden;
    }

    .mobile-steps-wrapper {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        gap: 12px;
        padding: 5px 5px 15px 5px;
        scrollbar-width: thin;
        scrollbar-color: var(--primary) var(--border);
        cursor: grab;
        scroll-behavior: smooth;
    }

    .mobile-steps-wrapper::-webkit-scrollbar {
        height: 4px;
        display: block;
    }

    .mobile-steps-wrapper::-webkit-scrollbar-track {
        background: var(--border);
        border-radius: 10px;
    }

    .mobile-steps-wrapper::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 10px;
    }

    .mobile-step-item {
        flex: 0 0 80%;
        scroll-snap-align: start;
        background: var(--light);
        border-radius: 16px;
        padding: 15px;
        border: 2px solid var(--border);
        transition: all 0.3s;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        min-width: 260px;
        max-width: 300px;
    }

    .mobile-steps-container::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        height: 100%;
        width: 40px;
        background: linear-gradient(to right, transparent, rgba(255,255,255,0.8));
        pointer-events: none;
        opacity: 0.8;
        border-radius: 0 16px 16px 0;
        z-index: 2;
    }

    .mobile-steps-container.scrolled-end::after {
        opacity: 0;
    }

    .mobile-step-item.completed {
        background: #f0fdf4;
        border-color: var(--success);
    }

    .mobile-step-item.active {
        border-color: var(--primary);
        background: #eff6ff;
        animation: pulse 2s infinite;
        position: relative;
    }

    .mobile-step-item.active::after {
        content: '✓';
        position: absolute;
        top: -5px;
        right: -5px;
        width: 20px;
        height: 20px;
        background: var(--success);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        border: 2px solid white;
    }

    .mobile-step-item.locked {
        opacity: 0.6;
        cursor: not-allowed;
        background: #f1f5f9;
    }

    .mobile-step-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .mobile-step-icon.step1 { background: #dbeafe; color: var(--primary); }
    .mobile-step-icon.step2 { background: #f3e8ff; color: var(--purple); }
    .mobile-step-icon.step3 { background: #d1fae5; color: var(--success); }
    .mobile-step-icon.step4 { background: #fef3c7; color: var(--warning); }

    .mobile-step-content {
        flex: 1;
    }

    .mobile-step-name {
        font-weight: 600;
        font-size: 16px;
        color: var(--dark);
        margin-bottom: 4px;
    }

    .mobile-step-status {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        display: inline-block;
    }

    .mobile-step-status.completed {
        background: var(--success);
        color: white;
    }

    .mobile-step-status.active {
        background: var(--primary);
        color: white;
    }

    .mobile-step-status.pending {
        background: var(--warning);
        color: white;
    }

    .mobile-step-status.locked {
        background: var(--gray);
        color: white;
    }

    .mobile-step-date {
        font-size: 11px;
        color: var(--success);
        margin-left: auto;
        white-space: nowrap;
        background: rgba(16, 185, 129, 0.1);
        padding: 4px 8px;
        border-radius: 20px;
    }

    .mobile-step-progress {
        font-size: 11px;
        color: var(--primary);
        margin-left: auto;
        white-space: nowrap;
        background: rgba(59, 130, 246, 0.1);
        padding: 4px 8px;
        border-radius: 20px;
    }

    .scroll-indicators {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 10px;
        padding: 5px 0;
    }

    .scroll-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--border);
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        padding: 0;
    }

    .scroll-dot:hover {
        background: var(--gray);
    }

    .scroll-dot.active {
        width: 24px;
        border-radius: 12px;
        background: var(--primary);
    }

    .mobile-steps-wrapper::after {
        content: '';
        flex: 0 0 5px;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: #f1f5f9;
    }

    .candidate-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .welcome-card {
        background: white;
        border-radius: 24px;
        padding: 30px 40px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border-left: 6px solid var(--primary);
        position: relative;
        overflow: hidden;
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: linear-gradient(135deg, var(--primary)10, var(--purple)10);
        border-radius: 50%;
        opacity: 0.1;
    }

    .welcome-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .welcome-title h1 {
        font-size: 32px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 8px;
    }

    .welcome-title p {
        color: var(--gray);
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .app-badge {
        background: linear-gradient(135deg, var(--primary), var(--purple));
        color: white;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 16px;
        box-shadow: 0 8px 20px rgba(59,130,246,0.3);
    }

    .journey-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid var(--border);
    }

    .journey-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .journey-title {
        font-size: 20px;
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .journey-progress {
        font-size: 28px;
        font-weight: 700;
        color: var(--primary);
    }

    .progress-bar {
        width: 100%;
        height: 10px;
        background: #e2e8f0;
        border-radius: 5px;
        margin-bottom: 30px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary), var(--purple));
        border-radius: 5px;
        transition: width 0.5s ease;
    }

    .steps-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }

    .step-item {
        background: var(--light);
        border-radius: 16px;
        padding: 20px;
        border: 2px solid var(--border);
        transition: all 0.3s;
        cursor: pointer;
        position: relative;
    }

    .step-item:hover {
        border-color: var(--primary);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(59,130,246,0.1);
    }

    .step-item.completed {
        background: #f0fdf4;
        border-color: var(--success);
    }

    .step-item.active {
        border-color: var(--primary);
        background: #eff6ff;
        animation: pulse 2s infinite;
        position: relative;
    }

    .step-item.active::after {
        content: '✓';
        position: absolute;
        top: -5px;
        right: -5px;
        width: 24px;
        height: 24px;
        background: var(--success);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .step-item.locked {
        opacity: 0.6;
        cursor: not-allowed;
        background: #f1f5f9;
    }

    .step-item.locked:hover {
        transform: none;
        border-color: var(--border);
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(59,130,246,0.4); }
        70% { box-shadow: 0 0 0 10px rgba(59,130,246,0); }
        100% { box-shadow: 0 0 0 0 rgba(59,130,246,0); }
    }

    .step-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 15px;
    }

    .step-icon.step1 { background: #dbeafe; color: var(--primary); }
    .step-icon.step2 { background: #f3e8ff; color: var(--purple); }
    .step-icon.step3 { background: #d1fae5; color: var(--success); }
    .step-icon.step4 { background: #fef3c7; color: var(--warning); }

    .step-name {
        font-weight: 600;
        font-size: 16px;
        color: var(--dark);
        margin-bottom: 8px;
    }

    .step-status {
        font-size: 12px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-block;
    }

    .step-status.completed {
        background: var(--success);
        color: white;
    }

    .step-status.active {
        background: var(--primary);
        color: white;
    }

    .step-status.pending {
        background: var(--warning);
        color: white;
    }

    .step-status.locked {
        background: var(--gray);
        color: white;
    }

    .content-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid var(--border);
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: var(--primary);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .info-item {
        background: var(--light);
        padding: 20px;
        border-radius: 12px;
        border-left: 4px solid var(--primary);
    }

    .info-label {
        font-size: 12px;
        color: var(--gray);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .info-value {
        font-size: 16px;
        font-weight: 600;
        color: var(--dark);
    }

    .rounds-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 20px;
        margin: 20px 0;
    }

    .round-card {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border-radius: 16px;
        padding: 20px;
        border: 2px solid var(--border);
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .round-card:hover {
        border-color: var(--purple);
        transform: translateY(-3px);
    }

    .round-card.passed {
        border-color: var(--success);
        background: #f0fdf4;
    }

    .round-card.failed {
        border-color: var(--danger);
        background: #fee2e2;
    }

    .round-card.inprogress {
        border-color: var(--primary);
        background: #eff6ff;
    }

    .round-card.pending {
        border-color: var(--warning);
        background: #fffbeb;
    }

    .round-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .round-name {
        font-weight: 600;
        color: var(--dark);
        font-size: 16px;
    }

    .round-status {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .round-status.pending { background: #fef3c7; color: var(--warning); }
    .round-status.passed { background: #d1fae5; color: var(--success); }
    .round-status.failed { background: #fee2e2; color: var(--danger); }
    .round-status.inprogress { background: #dbeafe; color: var(--primary); }

    .round-details {
        margin-top: 10px;
        font-size: 13px;
        color: var(--gray);
    }

    .round-details-item {
        padding: 8px 0;
        border-bottom: 1px dashed var(--border);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .round-details-item:last-child {
        border-bottom: none;
    }

    .round-details i {
        width: 20px;
        color: var(--purple);
    }

    .round-details strong {
        color: var(--dark);
        font-weight: 600;
        min-width: 80px;
    }

    .round-timing-details {
        background: white;
        border-radius: 12px;
        padding: 15px;
        margin-top: 15px;
        border: 1px dashed var(--border);
    }

    .timing-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        border-bottom: 1px solid var(--border);
    }

    .timing-row:last-child {
        border-bottom: none;
    }

    .timing-icon {
        width: 30px;
        height: 30px;
        background: var(--light);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
    }

    .timing-label {
        font-size: 12px;
        color: var(--gray);
        min-width: 80px;
    }

    .timing-value {
        font-weight: 600;
        color: var(--dark);
        font-size: 13px;
    }

    .interview-card {
        background: linear-gradient(135deg, var(--primary), var(--purple));
        border-radius: 16px;
        padding: 25px;
        color: white;
        margin: 20px 0;
    }

    .interview-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .interview-badge {
        background: rgba(255,255,255,0.2);
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 500;
    }

    .interview-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .detail-block {
        background: rgba(255,255,255,0.1);
        padding: 15px;
        border-radius: 12px;
        backdrop-filter: blur(10px);
    }

    .detail-block i {
        font-size: 20px;
        margin-bottom: 8px;
        opacity: 0.9;
    }

    .detail-block-label {
        font-size: 11px;
        opacity: 0.8;
        margin-bottom: 4px;
    }

    .detail-block-value {
        font-size: 16px;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 25px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(59,130,246,0.3);
    }

    .btn-success {
        background: var(--success);
        color: white;
    }

    .btn-success:hover {
        background: #059669;
    }

    .btn-outline {
        background: transparent;
        border: 2px solid var(--primary);
        color: var(--primary);
    }

    .btn-outline:hover {
        background: var(--primary);
        color: white;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        justify-content: center;
        align-items: center;
        z-index: 10000;
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        padding: 30px;
        max-width: 500px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border);
    }

    .modal-header h2 {
        font-size: 20px;
        color: var(--dark);
    }

    .modal-close {
        font-size: 24px;
        cursor: pointer;
        color: var(--gray);
    }

    .modal-close:hover {
        color: var(--danger);
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid var(--border);
        border-radius: 8px;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
    }

    .notification {
        position: fixed;
        top: 24px;
        right: 24px;
        background: white;
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        z-index: 9999;
        display: none;
        align-items: center;
        gap: 12px;
        border-left: 4px solid var(--success);
        max-width: 400px;
    }

    .notification.error {
        border-left-color: var(--danger);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--gray);
    }

    .empty-state i {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 15px;
    }

    .document-item {
        background: var(--light);
        border: 2px dashed var(--border);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .document-item:hover {
        border-color: var(--primary);
        background: #eff6ff;
    }
    
    .document-item i {
        font-size: 30px;
        color: var(--warning);
        margin-bottom: 10px;
    }

    .congrats-message {
        background: linear-gradient(135deg, #10b98120, #10b98110);
        border-left: 4px solid var(--success);
        padding: 12px 15px;
        margin-bottom: 15px;
        border-radius: 8px;
    }

    .failed-message {
        background: #fee2e2;
        border-left: 4px solid var(--danger);
        padding: 12px 15px;
        margin-bottom: 15px;
        border-radius: 8px;
    }

    .all-cleared-banner {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border-radius: 16px;
        padding: 25px;
        margin: 20px 0;
        text-align: center;
        border: 2px solid var(--success);
    }

    /* Mobile Responsive Breakpoints */
    @media (max-width: 768px) {
        .candidate-container {
            padding: 15px 10px;
        }
        
        .welcome-card {
            padding: 20px 15px;
        }
        
        .welcome-title h1 {
            font-size: 24px;
        }
        
        .app-badge {
            padding: 8px 20px;
            font-size: 14px;
        }
        
        .journey-card, .content-card {
            padding: 20px 15px;
        }
        
        .section-title {
            font-size: 18px;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .rounds-grid {
            grid-template-columns: 1fr;
        }
        
        .interview-details {
            grid-template-columns: 1fr 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 10px;
        }
        
        .btn {
            width: 100%;
            padding: 10px 20px;
            font-size: 13px;
        }
        
        .modal-content {
            padding: 20px;
            width: 95%;
        }
        
        .documents-grid {
            grid-template-columns: 1fr;
        }

        .desktop-steps {
            display: none;
        }
        
        .mobile-steps-container {
            display: block;
        }
        
        .journey-header {
            flex-direction: row;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .journey-title {
            font-size: 18px;
        }
        
        .journey-progress {
            font-size: 24px;
        }
        
        .progress-bar {
            margin-bottom: 15px;
        }
        
        .mobile-steps-wrapper {
            padding-right: 10px;
        }
    }

    @media (max-width: 480px) {
        .welcome-title h1 {
            font-size: 20px;
        }
        
        .welcome-title p {
            font-size: 14px;
        }
        
        .app-badge {
            width: 100%;
            text-align: center;
        }
        
        .journey-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .journey-progress {
            font-size: 24px;
        }
        
        .interview-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .interview-details {
            grid-template-columns: 1fr;
        }
        
        .detail-block {
            padding: 12px;
        }
        
        .round-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        
        .round-status {
            align-self: flex-start;
        }
        
        .modal-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .modal-close {
            align-self: flex-end;
        }

        .mobile-step-item {
            flex: 0 0 85%;
            min-width: 240px;
            max-width: 280px;
            padding: 12px;
        }
        
        .mobile-step-icon {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }
        
        .mobile-step-name {
            font-size: 15px;
        }
        
        .journey-title {
            font-size: 16px;
        }
        
        .journey-progress {
            font-size: 22px;
        }
        
        .mobile-steps-container::after {
            width: 30px;
        }
    }

    @media (max-width: 360px) {
        .mobile-step-item {
            flex: 0 0 90%;
            min-width: 200px;
            padding: 10px;
        }
        
        .mobile-step-icon {
            width: 35px;
            height: 35px;
            font-size: 16px;
        }
        
        .mobile-step-name {
            font-size: 14px;
        }
    }

    html {
        scroll-behavior: smooth;
    }

    @media (max-width: 768px) {
        .step-item, 
        .mobile-step-item,
        .btn,
        .document-item,
        .round-card {
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }
        
        .step-item:active,
        .mobile-step-item:active,
        .btn:active,
        .document-item:active {
            transform: scale(0.98);
            transition: transform 0.1s;
        }
    }
</style>

@php
    // Safely extract data with proper null checking
    $lead = $data['lead'] ?? null;
    $interviewRegistration = $data['interviewRegistration'] ?? null;
    $interviewConfig = $data['interviewConfig'] ?? null;
    $interviewRounds = $data['interviewRounds'] ?? collect([]);
    $department = $data['department'] ?? null;
    
    // Get statuses from interviewRegistration (real data from database)
    $applicationStatus = $interviewRegistration->application_status ?? 'pending';
    $interviewStatus = $interviewRegistration->interview_status ?? 'pending';
    $selectionStatus = $interviewRegistration->selection_status ?? 'pending';
    $onboardingStatus = $interviewRegistration->onboarding_status ?? 'pending';
    
    $stepStatuses = [
        'application' => $applicationStatus,
        'interview' => $interviewStatus,
        'selection' => $selectionStatus,
        'onboarding' => $onboardingStatus
    ];
    
    $stepIcons = [
        'application' => 'fa-file-signature',
        'interview' => 'fa-users',
        'selection' => 'fa-check-double',
        'onboarding' => 'fa-rocket'
    ];
    
    $stepTitles = [
        'application' => 'Application',
        'interview' => 'Interview',
        'selection' => 'Selection',
        'onboarding' => 'Onboarding'
    ];
    
    // Get round statuses from round_status table
    $roundStatuses = \App\Models\RoundStatus::where('lead_id', $lead->id ?? 0)
        ->orderBy('id')
        ->get();
    
    // Calculate passed/failed counts from round_statuses
    $passedRounds = $roundStatuses->where('round_status', 'passed')->count();
    $failedRounds = $roundStatuses->where('round_status', 'failed')->count();
    $inProgressRounds = $roundStatuses->where('round_status', 'inprogress')->count();
    $pendingRounds = $roundStatuses->where('round_status', 'pending')->count();
    $totalRounds = $roundStatuses->count();
    
    $nextRound = null;
    // Find next round (first pending round)
    if ($totalRounds > 0) {
        $nextRoundStatus = $roundStatuses->where('round_status', 'pending')->first();
        if ($nextRoundStatus) {
            $nextRound = $interviewRounds->where('id', $nextRoundStatus->round_id)->first();
        }
    }
    
    // Calculate progress
    $progressSteps = [
        $stepStatuses['application'] === 'completed' ? 1 : 0,
        $stepStatuses['interview'] === 'completed' ? 1 : 0,
        $stepStatuses['selection'] === 'completed' ? 1 : 0,
        $stepStatuses['onboarding'] === 'completed' ? 1 : 0
    ];
    $progress = round((array_sum($progressSteps) / 4) * 100);
    
    // Helper function
    function getStepClass($status, $stepNumber, $currentStep) {
        if ($status === 'completed') return 'completed';
        if ($status === 'in_progress' || $stepNumber === $currentStep) return 'active';
        if ($status === 'pending') return 'pending';
        if ($status === 'rejected') return 'locked';
        return 'locked';
    }
    
    // Determine current active step based on statuses
    $currentStep = 1;
    if ($stepStatuses['application'] === 'completed' && $stepStatuses['interview'] !== 'completed' && $stepStatuses['interview'] !== 'in_progress') {
        $currentStep = 2;
    } elseif ($stepStatuses['interview'] === 'completed' && $stepStatuses['selection'] !== 'completed' && $stepStatuses['selection'] !== 'in_progress') {
        $currentStep = 3;
    } elseif ($stepStatuses['selection'] === 'completed' || $stepStatuses['selection'] === 'in_progress') {
        $currentStep = 4;
    }
    
    // If interview is in progress, set current step to 2
    if ($stepStatuses['interview'] === 'in_progress') {
        $currentStep = 2;
    }
    
    // If selection is in progress, set current step to 3
    if ($stepStatuses['selection'] === 'in_progress') {
        $currentStep = 3;
    }
@endphp

<div class="candidate-container">
    @if(!$lead)
        <div class="empty-state">
            <i class="fas fa-exclamation-circle"></i>
            <h3>Candidate Not Found</h3>
            <p>The requested candidate information could not be found.</p>
        </div>
    @else
        <!-- Welcome Card -->
        <div class="welcome-card">
            <div class="welcome-content">
                <div class="welcome-title">
                    <h1>Welcome, {{ $lead->name ?? 'Candidate' }}! 👋</h1>
                    <p>
                        <i class="fas fa-briefcase" style="color: var(--primary);"></i>
                        {{ $department->department ?? 'Interview' }} Department
                    </p>
                </div>
                <div class="app-badge">
                    <i class="fas fa-id-card"></i>
                    {{ $lead->lead_id ?? 'LEAD-'.str_pad($lead->id ?? 0, 4, '0', STR_PAD_LEFT) }}
                </div>
            </div>
        </div>

        <!-- Journey Steps -->
        <div class="journey-card">
            <div class="journey-header">
                <div class="journey-title">
                    <i class="fas fa-road" style="color: var(--primary);"></i>
                    Your Recruitment Journey
                </div>
                <div class="journey-progress">{{ $progress }}%</div>
            </div>

            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $progress }}%"></div>
            </div>

            <!-- Desktop Steps Grid with Green Tick on Active Tab -->
            <div class="steps-grid desktop-steps">
                @foreach(['application', 'interview', 'selection', 'onboarding'] as $index => $step)
                @php
                    $status = $stepStatuses[$step];
                    $icon = $stepIcons[$step];
                    $title = $stepTitles[$step];
                    $isActive = ($index + 1) == $currentStep;
                @endphp
                <div class="step-item {{ getStepClass($status, $index + 1, $currentStep) }} {{ $isActive ? 'active' : '' }}" onclick="showStep({{ $index + 1 }})">
                    <div class="step-icon step{{ $index + 1 }}">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    <div class="step-name">{{ $title }}</div>
                    <span class="step-status {{ $status }}">
                        @if($status == 'completed')
                            <i class="fas fa-check-circle"></i> Completed
                        @elseif($status == 'in_progress')
                            <i class="fas fa-spinner"></i> In Progress
                        @elseif($status == 'rejected')
                            <i class="fas fa-times-circle"></i> Rejected
                        @else
                            <i class="fas fa-clock"></i> Pending
                        @endif
                    </span>
                    @if($step == 'application' && $lead->created_at)
                        <div style="font-size: 11px; color: var(--success); margin-top: 8px;">
                            <i class="fas fa-check-circle"></i> {{ $lead->created_at->format('d M') }}
                        </div>
                    @endif
                    @if($step == 'interview' && $totalRounds > 0)
                        <div style="font-size: 11px; color: var(--primary); margin-top: 8px;">
                            {{ $passedRounds }} Passed / {{ $failedRounds }} Failed
                        </div>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Mobile Steps Carousel with Green Tick on Active Tab -->
            <div class="mobile-steps-container">
                <div class="mobile-steps-wrapper">
                    @foreach(['application', 'interview', 'selection', 'onboarding'] as $index => $step)
                    @php
                        $status = $stepStatuses[$step];
                        $icon = $stepIcons[$step];
                        $title = $stepTitles[$step];
                        $isActive = ($index + 1) == $currentStep;
                    @endphp
                    <div class="mobile-step-item {{ getStepClass($status, $index + 1, $currentStep) }} {{ $isActive ? 'active' : '' }}" onclick="showStep({{ $index + 1 }})">
                        <div class="mobile-step-icon step{{ $index + 1 }}">
                            <i class="fas {{ $icon }}"></i>
                        </div>
                        <div class="mobile-step-content">
                            <div class="mobile-step-name">{{ $title }}</div>
                            <span class="mobile-step-status {{ $status }}">
                                @if($status == 'completed')
                                    Completed
                                @elseif($status == 'in_progress')
                                    In Progress
                                @elseif($status == 'rejected')
                                    Rejected
                                @else
                                    Pending
                                @endif
                            </span> 
                        </div>
                        @if($step == 'application' && $lead->created_at)
                            <div class="mobile-step-date">
                                {{ $lead->created_at->format('d M') }}
                            </div>
                        @endif
                        @if($step == 'interview' && $totalRounds > 0)
                            <div class="mobile-step-progress">
                                {{ $passedRounds }}P/{{ $failedRounds }}F
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                
                <div class="scroll-indicators">
                    <span class="scroll-dot {{ $currentStep == 1 ? 'active' : '' }}" data-step="1"></span>
                    <span class="scroll-dot {{ $currentStep == 2 ? 'active' : '' }}" data-step="2"></span>
                    <span class="scroll-dot {{ $currentStep == 3 ? 'active' : '' }}" data-step="3"></span>
                    <span class="scroll-dot {{ $currentStep == 4 ? 'active' : '' }}" data-step="4"></span>
                </div>
            </div>
        </div>

        <!-- Dynamic Content Based on Active Step -->
        <div class="content-card" id="step-content">
            <!-- Application Step Content -->
            <div id="step-1-content" style="display: {{ $currentStep == 1 ? 'block' : 'none' }};">
                <div class="section-title">
                    <i class="fas fa-file-signature"></i>
                    Application Details
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Application ID</div>
                        <div class="info-value">{{ $lead->lead_id ?? 'LEAD-'.str_pad($lead->id ?? 0, 4, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">{{ $lead->name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div> 
                        <div class="info-value">{{ $lead->email ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Phone</div> 
                        <div class="info-value">{{ $lead->phone_no ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Applied Date</div>
                        <div class="info-value">{{ $lead->created_at ? $lead->created_at->format('d M Y') : 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Application Status</div>
                        <div class="info-value">
                            <span class="step-status {{ $applicationStatus }}">
                                {{ ucfirst($applicationStatus) }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($interviewRegistration)
                    @if($interviewRegistration->dob || $interviewRegistration->marital_status)
                    <div style="margin-top: 20px;">
                        <h4 style="color: var(--dark); margin-bottom: 15px;">Personal Information</h4>
                        <div class="info-grid">
                            @if($interviewRegistration->dob)
                            <div class="info-item">
                                <div class="info-label">Date of Birth</div>
                                <div class="info-value">{{ \Carbon\Carbon::parse($interviewRegistration->dob)->format('d M Y') }}</div>
                            </div>
                            @endif
                            @if($interviewRegistration->marital_status)
                            <div class="info-item">
                                <div class="info-label">Marital Status</div>
                                <div class="info-value">{{ ucfirst($interviewRegistration->marital_status) }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($interviewRegistration->profession || $interviewRegistration->qualification)
                    <div style="margin-top: 20px;">
                        <h4 style="color: var(--dark); margin-bottom: 15px;">Professional Information</h4>
                        <div class="info-grid">
                            @if($interviewRegistration->profession)
                            <div class="info-item">
                                <div class="info-label">Profession</div>
                                <div class="info-value">{{ ucfirst($interviewRegistration->profession) }}</div>
                            </div>
                            @endif
                            @if($interviewRegistration->organization)
                            <div class="info-item">
                                <div class="info-label">Organization</div>
                                <div class="info-value">{{ $interviewRegistration->organization }}</div>
                            </div>
                            @endif
                            @if($interviewRegistration->qualification)
                            <div class="info-item">
                                <div class="info-label">Qualification</div>
                                <div class="info-value">{{ strtoupper($interviewRegistration->qualification) }}</div>
                            </div>
                            @endif
                            @if($interviewRegistration->experience_in_year || $interviewRegistration->experience_in_month)
                            <div class="info-item">
                                <div class="info-label">Experience</div>
                                <div class="info-value">{{ $interviewRegistration->experience_in_year ?? 0 }} years {{ $interviewRegistration->experience_in_month ?? 0 }} months</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if(!empty($interviewRegistration->skills))
                    @php
                        $skills = is_string($interviewRegistration->skills) ? json_decode($interviewRegistration->skills, true) : $interviewRegistration->skills;
                    @endphp
                    @if(!empty($skills) && is_array($skills))
                    <div style="margin-top: 20px;">
                        <h4 style="color: var(--dark); margin-bottom: 15px;">Skills</h4>
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            @foreach($skills as $skill)
                                @if(is_array($skill) && isset($skill['name']))
                                    <span style="background: var(--primary-soft); color: var(--primary); padding: 8px 16px; border-radius: 30px; font-size: 13px;">
                                        {{ $skill['name'] }} @if(isset($skill['level'])) ({{ ucfirst($skill['level']) }}) @endif
                                    </span>
                                @elseif(is_string($skill))
                                    <span style="background: var(--primary-soft); color: var(--primary); padding: 8px 16px; border-radius: 30px; font-size: 13px;">
                                        {{ $skill }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @endif

                    @if($interviewRegistration->resume_path)
                    <div style="margin-top: 20px;">
                        <h4 style="color: var(--dark); margin-bottom: 15px;">Resume</h4>
                        <a href="{{ asset($interviewRegistration->resume_path) }}" target="_blank" style="text-decoration: none;">
                            <div class="document-item" style="display: inline-flex; padding: 12px 24px;">
                                <i class="fas fa-file-pdf" style="font-size: 20px; margin-right: 10px;"></i>
                                <span>Download Resume</span>
                            </div>
                        </a>
                    </div>
                    @endif
                @endif

                @if($interviewConfig && !empty($interviewConfig->application_documents))
                    @php
                        $docs = is_string($interviewConfig->application_documents) 
                            ? json_decode($interviewConfig->application_documents, true) 
                            : $interviewConfig->application_documents;
                    @endphp
                    @if(!empty($docs) && is_array($docs))
                        <div style="margin-top: 20px;">
                            <h4 style="color: var(--dark); margin-bottom: 15px;">Required Documents</h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                                @foreach($docs as $doc)
                                    @if(is_array($doc) && isset($doc['name']))
                                        <div style="background: var(--light); padding: 15px; border-radius: 8px; display: flex; align-items: center; gap: 10px;">
                                            <i class="fas fa-check-circle" style="color: var(--success);"></i>
                                            <span>{{ $doc['name'] }}</span>
                                        </div>
                                    @elseif(is_string($doc))
                                        <div style="background: var(--light); padding: 15px; border-radius: 8px; display: flex; align-items: center; gap: 10px;">
                                            <i class="fas fa-check-circle" style="color: var(--success);"></i>
                                            <span>{{ $doc }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Interview Step Content - SHOWING ALL ROUND DETAILS -->

<div id="step-2-content" style="display: {{ $currentStep == 2 ? 'block' : 'none' }};">
    <div class="section-title">
        <i class="fas fa-users"></i>
        Interview Process
    </div>

    @if($interviewRounds && $interviewRounds->count() > 0)
        @php
            // Get round statuses from the lead object
            $roundStatuses = $lead->roundStatuses ?? collect([]);
            
            // Calculate passed/failed counts from round_statuses
            $passedRounds = $roundStatuses->where('round_status', 'passed')->count();
            $failedRounds = $roundStatuses->where('round_status', 'failed')->count();
            $inProgressRounds = $roundStatuses->where('round_status', 'inprogress')->count();
            $pendingRounds = $roundStatuses->where('round_status', 'pending')->count();
            $totalRounds = $roundStatuses->count();
        @endphp

        <!-- Progress Summary -->
        <div style="margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; flex-wrap: wrap; gap: 10px;">
                <span style="color: var(--dark); font-weight: 500;">Interview Progress</span>
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <span style="color: var(--success); font-weight: 600;">
                        <i class="fas fa-check-circle"></i> Passed: {{ $passedRounds }}
                    </span>
                    <span style="color: var(--danger); font-weight: 600;">
                        <i class="fas fa-times-circle"></i> Failed: {{ $failedRounds }}
                    </span>
                    @if($inProgressRounds > 0)
                    <span style="color: var(--primary); font-weight: 600;">
                        <i class="fas fa-spinner"></i> In Progress: {{ $inProgressRounds }}
                    </span>
                    @endif
                    @if($pendingRounds > 0)
                    <span style="color: var(--warning); font-weight: 600;">
                        <i class="fas fa-clock"></i> Pending: {{ $pendingRounds }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="progress-bar" style="height: 8px;">
                <div class="progress-fill" style="width: {{ $interviewRounds->count() > 0 ? (($passedRounds + $failedRounds) / $interviewRounds->count()) * 100 : 0 }}%; background: var(--purple);"></div>
            </div>
        </div>

        <!-- Round Cards - SHOWING ALL DETAILS WITH DYNAMIC STATUS -->
        <div class="rounds-grid">
            @foreach($interviewRounds as $index => $round)
                @php
                    // Get status from lead's roundStatuses by matching name
                    $roundStatus = $roundStatuses->first(function($status) use ($round) {
                        return strtolower(trim($status->name)) === strtolower(trim($round->name));
                    });
                    
                    $status = $roundStatus ? strtolower($roundStatus->round_status) : 'pending';
                    
                    // Get type to determine if it's a test or interview
                    $roundType = $roundStatus->type ?? $round->type ?? '';
                    $isTest = strtolower($roundType) == 'test';
                    
                    // Get marks and total marks
                    $marks = $roundStatus->marks ?? null;
                    $totalMarks = $round->total_marks ?? 100; // Default to 100 if not set
                    $passingMarks = $round->passing_marks ?? 40; // Default to 40 if not set
                    
                    // Convert marks to rating stars (1-5)
                    $rating = 0;
                    if ($marks && !$isTest) {
                        // Assuming marks is between 1-5 for ratings
                        $rating = intval($marks);
                        if ($rating < 1) $rating = 1;
                        if ($rating > 5) $rating = 5;
                    }
                    
                    $roundDate = null;
                    $roundTime = null;
                    
                    // Use round status date if available, otherwise use round data
                    $displayDate = $roundStatus->round_date ?? $round->round_date ?? null;
                    $displayStartTime = $roundStatus->start_time ?? $round->start_time ?? null;
                    $displayEndTime = $roundStatus->end_time ?? $round->end_time ?? null;
                    $displayDuration = $roundStatus->duration ?? $round->duration ?? null;
                    $displayType = $roundStatus->type_label ?? $round->type_label ?? $round->type ?? null;
                    $displayPanel = $roundStatus->panel ?? $round->panel ?? null;
                    
                    if ($displayDate) {
                        try {
                            $roundDate = \Carbon\Carbon::parse($displayDate);
                        } catch (\Exception $e) {
                            $roundDate = null;
                        }
                    }
                    
                    if ($displayStartTime && $displayStartTime != '00:00:00') {
                        try {
                            $roundTime = \Carbon\Carbon::parse($displayStartTime);
                        } catch (\Exception $e) {
                            $roundTime = null;
                        }
                    }
                @endphp
                
                <div class="round-card {{ $status }}" style="padding: 15px;">
                    <div class="round-header" style="margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                        <span class="round-name" style="font-size: 15px; font-weight: 600;">Round {{ $index + 1 }}: {{ $round->name ?? 'Interview Round' }}</span>
                        <span class="round-status {{ $status }}" style="padding: 4px 10px; font-size: 11px; border-radius: 20px;">
                            @if($status == 'passed')
                                <i class="fas fa-check-circle"></i> Passed
                            @elseif($status == 'failed')
                                <i class="fas fa-times-circle"></i> Failed
                            @elseif($status == 'inprogress')
                                <i class="fas fa-spinner"></i> In Progress
                            @else
                                <i class="fas fa-clock"></i> Pending
                            @endif
                        </span>
                    </div>
                    
                    <!-- Status Message with compact design -->
                    @if($status == 'passed')
                        <div class="congrats-message" style="padding: 10px; margin-bottom: 10px; background: #f0fdf4; border-radius: 8px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 30px; height: 30px; background: var(--success); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0;">
                                    <i class="fas fa-check" style="font-size: 14px;"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 600; color: var(--success); font-size: 13px;">Congratulations! 🎉</div>
                                    <p style="color: var(--dark); font-size: 11px; margin: 2px 0 0 0;">You cleared Round {{ $index + 1 }}!</p>
                                    
                                    @if($marks)
                                        @if($isTest)
                                            <div style="margin-top: 5px; background: rgba(16, 185, 129, 0.1); padding: 3px 8px; border-radius: 12px; display: inline-block; font-size: 11px;">
                                                <span style="font-weight: 600;">Score:</span> {{ $marks }}/{{ $totalMarks }} 
                                                <span style="color: var(--gray);">| Passing marks: {{ $passingMarks }}</span>
                                            </div>
                                        @else
                                            <div style="margin-top: 5px; background: rgba(16, 185, 129, 0.1); padding: 3px 8px; border-radius: 12px; display: inline-block;">
                                                @for($i = 1; $i <= $rating; $i++)
                                                    <i class="fas fa-star" style="color: #FFD700; font-size: 10px;"></i>
                                                @endfor
                                                <span style="margin-left: 4px; font-size: 10px;">({{ $rating }}/5)</span>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($status == 'failed')
                        <div class="failed-message" style="padding: 10px; margin-bottom: 10px; background: #fee2e2; border-radius: 8px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 30px; height: 30px; background: var(--danger); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0;">
                                    <i class="fas fa-info" style="font-size: 14px;"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 600; color: var(--danger); font-size: 13px;">Round Not Cleared</div>
                                    <p style="color: var(--dark); font-size: 11px; margin: 2px 0 0 0;">Best wishes for future opportunities</p>
                                    
                                    @if($marks)
                                        @if($isTest)
                                            <div style="margin-top: 5px; background: rgba(239, 68, 68, 0.1); padding: 3px 8px; border-radius: 12px; display: inline-block; font-size: 11px;">
                                                <span style="font-weight: 600;">Score:</span> {{ $marks }}/{{ $totalMarks }}
                                                <span style="color: var(--gray);">| Paasing marks: {{ $passingMarks }}</span>
                                            </div>
                                        @else
                                            <div style="margin-top: 5px; background: rgba(239, 68, 68, 0.1); padding: 3px 8px; border-radius: 12px; display: inline-block;">
                                                @for($i = 1; $i <= $rating; $i++)
                                                    <i class="fas fa-star" style="color: #FFD700; font-size: 10px;"></i>
                                                @endfor
                                                <span style="margin-left: 4px; font-size: 10px;">({{ $rating }}/5)</span>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Round Details - ONLY SHOW FOR PENDING OR INPROGRESS -->
                    @if($status == 'pending' || $status == 'inprogress')
                        <div class="round-details" style="margin-top: 5px;">
                            @if($roundDate)
                                <div class="round-details-item" style="padding: 5px 0; display: flex; align-items: center; gap: 8px; font-size: 12px;">
                                    <i class="fas fa-calendar-alt" style="width: 16px; color: var(--purple); font-size: 11px;"></i>
                                    <strong style="min-width: 35px; font-size: 11px;">Date:</strong> 
                                    <span>{{ $roundDate->format('d M Y') }}</span>
                                </div>
                            @endif
                            
                            @if($roundTime)
                                <div class="round-details-item" style="padding: 5px 0; display: flex; align-items: center; gap: 8px; font-size: 12px;">
                                    <i class="fas fa-clock" style="width: 16px; color: var(--purple); font-size: 11px;"></i>
                                    <strong style="min-width: 35px; font-size: 11px;">Time:</strong> 
                                    <span>{{ $roundTime->format('h:i A') }}</span>
                                </div>
                            @endif
                            
                            @if($displayType)
                                <div class="round-details-item" style="padding: 5px 0; display: flex; align-items: center; gap: 8px; font-size: 12px;">
                                    <i class="fas fa-tag" style="width: 16px; color: var(--purple); font-size: 11px;"></i>
                                    <strong style="min-width: 35px; font-size: 11px;">Type:</strong> 
                                    <span>{{ $displayType }}</span>
                                </div>
                            @endif

                            @if($displayPanel)
                                <div class="round-details-item" style="padding: 5px 0; display: flex; align-items: center; gap: 8px; font-size: 12px;">
                                    <i class="fas fa-users" style="width: 16px; color: var(--purple); font-size: 11px;"></i>
                                    <strong style="min-width: 35px; font-size: 11px;">Panel:</strong> 
                                    <span>{{ $displayPanel }}</span>
                                </div>
                            @endif
                            
                            @if($isTest)
                                <div class="round-details-item" style="padding: 5px 0; display: flex; align-items: center; gap: 8px; font-size: 12px;">
                                    <i class="fas fa-star" style="width: 16px; color: var(--purple); font-size: 11px;"></i>
                                    <strong style="min-width: 35px; font-size: 11px;">Max Marks:</strong> 
                                    <span>{{ $totalMarks }} (Pass: {{ $passingMarks }})</span>
                                </div>
                            @endif
                        </div>

                        @if($round->desc)
                        <div style="margin-top: 8px; padding: 8px 10px; background: #f8f9fa; border-radius: 6px; font-size: 11px; border-left: 2px solid var(--purple);">
                            <i class="fas fa-info-circle" style="color: var(--purple); margin-right: 5px; font-size: 10px;"></i> 
                            {{ $round->desc }}
                        </div>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
        <!-- If all rounds passed but selection pending -->
        @if($passedRounds == $interviewRounds->count() && $interviewRounds->count() > 0 && $selectionStatus == 'pending')
        <div class="all-cleared-banner">
            <i class="fas fa-trophy" style="font-size: 48px; color: var(--success); margin-bottom: 15px;"></i>
            <h3 style="color: var(--dark); margin-bottom: 10px;">Congratulations! 🎉</h3>
            <p style="color: var(--gray); margin-bottom: 20px;">You have successfully cleared all {{ $interviewRounds->count() }} interview rounds! Your selection is now under review. We'll notify you soon.</p>
            <div style="display: inline-block; background: var(--success); color: white; padding: 8px 20px; border-radius: 30px; font-weight: 600;">
                <i class="fas fa-check-circle"></i> All Rounds Cleared
            </div>
        </div>
        @endif

        <!-- Next Round Card -->
        @if($nextRound)
            @php
                $nextRoundDate = null;
                $nextRoundTime = null;
                
                if ($nextRound->round_date) {
                    try {
                        $nextRoundDate = \Carbon\Carbon::parse($nextRound->round_date);
                    } catch (\Exception $e) {
                        $nextRoundDate = null;
                    }
                }
                
                if ($nextRound->start_time && $nextRound->start_time != '00:00:00') {
                    try {
                        $nextRoundTime = \Carbon\Carbon::parse($nextRound->start_time);
                    } catch (\Exception $e) {
                        $nextRoundTime = null;
                    }
                }
            @endphp
            
            <div class="interview-card">
                <div class="interview-header">
                    <span class="interview-badge">
                        <i class="fas fa-hourglass-half"></i> Up Next
                    </span>
                    <span>{{ $nextRound->name ?? 'Interview' }}</span>
                </div>

                <!-- Show previous round cleared message -->
                @if($passedRounds > 0)
                <div style="background: rgba(255,255,255,0.15); border-radius: 10px; padding: 10px 15px; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-check-circle" style="color: white;"></i>
                    <span style="color: white; font-size: 13px;">Great job clearing {{ $passedRounds }} round{{ $passedRounds > 1 ? 's' : '' }}! Ready for the next challenge?</span>
                </div>
                @endif

                <div class="interview-details">
                    @if($nextRoundDate)
                        <div class="detail-block">
                            <i class="fas fa-calendar-alt"></i>
                            <div class="detail-block-label">Date</div>
                            <div class="detail-block-value">
                                {{ $nextRoundDate->format('l, d M') }}
                            </div>
                        </div>
                    @endif
                    
                    @if($nextRoundTime)
                        <div class="detail-block">
                            <i class="fas fa-clock"></i>
                            <div class="detail-block-label">Time</div>
                            <div class="detail-block-value">
                                {{ $nextRoundTime->format('h:i A') }}
                            </div>
                        </div>
                    @endif
                    
                    @if($nextRound->duration)
                        <div class="detail-block">
                            <i class="fas fa-hourglass-half"></i>
                            <div class="detail-block-label">Duration</div>
                            <div class="detail-block-value">{{ $nextRound->duration }} mins</div>
                        </div>
                    @endif
                    
                    @if($nextRound->type)
                        <div class="detail-block">
                            <i class="fas fa-tag"></i>
                            <div class="detail-block-label">Type</div>
                            <div class="detail-block-value">{{ $nextRound->type_label ?? $nextRound->type }}</div>
                        </div>
                    @endif

                    @if($nextRound->panel)
                        <div class="detail-block">
                            <i class="fas fa-users"></i>
                            <div class="detail-block-label">Panel</div>
                            <div class="detail-block-value">Panel {{ $nextRound->panel }}</div>
                        </div>
                    @endif

                    @if($nextRound->venue)
                        <div class="detail-block">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="detail-block-label">Venue</div>
                            <div class="detail-block-value">{{ $nextRound->venue }}</div>
                        </div>
                    @endif
                    
                    @if($interviewConfig)
                        <div class="detail-block">
                            <i class="fas fa-{{ $interviewConfig->interview_mode === 'online' ? 'video' : 'building' }}"></i>
                            <div class="detail-block-label">Mode</div>
                            <div class="detail-block-value">{{ ucfirst($interviewConfig->interview_mode ?? 'online') }}</div>
                        </div>
                    @endif
                </div>

                @if($nextRound->desc)
                <div style="margin-top: 15px; padding: 12px; background: rgba(255,255,255,0.1); border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-info-circle"></i> {{ $nextRound->desc }}
                </div>
                @endif

                <div class="action-buttons" style="margin-top: 20px;">
                    <button class="btn btn-outline" onclick="openRescheduleModal()">
                        <i class="fas fa-calendar-alt"></i> Request Reschedule
                    </button>
                </div>
            </div>
        @endif

        <!-- Interview Guidelines -->
        @if($interviewConfig)
            <div style="margin-top: 30px;">
                <h4 style="color: var(--dark); margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-info-circle" style="color: var(--purple);"></i>
                    Interview Guidelines
                </h4>

                <div style="background: var(--light); border-radius: 12px; padding: 20px;">
                    @if(!empty($interviewConfig->dress_code))
                        <div style="margin-bottom: 12px;">
                            <strong>Dress Code:</strong> 
                            @if($interviewConfig->dress_code === 'custom' && !empty($interviewConfig->custom_dress_code))
                                {{ $interviewConfig->custom_dress_code }}
                            @else
                                {{ ucfirst($interviewConfig->dress_code) }}
                            @endif
                        </div>
                    @endif

                    @if(!empty($interviewConfig->items_to_bring))
                        <div style="margin-bottom: 12px;">
                            <strong>Items to Bring:</strong> {{ $interviewConfig->items_to_bring }}
                        </div>
                    @endif

                    @if($interviewConfig->interview_mode === 'offline' && !empty($interviewConfig->venue_address))
                        <div>
                            <strong>Venue Address:</strong><br>
                            {{ $interviewConfig->venue_address }}
                        </div>
                    @endif

                    @if($interviewConfig->interview_mode === 'online' && !empty($interviewConfig->online_meet_link))
                        <div>
                            <strong>Meeting Link:</strong><br>
                            <a href="{{ $interviewConfig->online_meet_link }}" target="_blank" style="color: var(--primary);">{{ $interviewConfig->online_meet_link }}</a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @else
        <div class="empty-state">
            <i class="fas fa-calendar-times"></i>
            <h3>No Interviews Scheduled</h3>
            <p>Your interview schedule is being finalized. Please check back later.</p>
        </div>
    @endif
</div>

            <!-- Selection Step Content -->
            <div id="step-3-content" style="display: {{ $currentStep == 3 ? 'block' : 'none' }};">
                <div class="section-title">
                    <i class="fas fa-check-double"></i>
                    Selection Status
                </div>

                @if($selectionStatus === 'completed')
                    <div style="background: #f0fdf4; border-radius: 16px; padding: 30px; text-align: center;">
                        <i class="fas fa-trophy" style="font-size: 60px; color: var(--success); margin-bottom: 20px;"></i>
                        <h2 style="color: var(--dark); margin-bottom: 10px;">Congratulations! 🎉</h2>
                        <p style="color: var(--gray); margin-bottom: 20px;">You have been selected for the position.</p>
                        
                        @if($interviewConfig && $interviewConfig->offer_letter_generation)
                            <div class="action-buttons" style="justify-content: center; margin-top: 25px;">
                                <button class="btn btn-success" onclick="acceptOffer()">
                                    <i class="fas fa-check-circle"></i> Accept Offer
                                </button>
                            </div>
                        @endif
                    </div>

                    @if($totalRounds > 0)
                        <div style="margin-top: 30px;">
                            <h4 style="color: var(--dark); margin-bottom: 15px;">Performance Summary</h4>
                            @php
                                $totalMarksObtained = 0;
                                $totalMaxMarks = 0;
                                foreach($interviewRounds as $round) {
                                    if(!empty($round->marks_obtained) && !empty($round->total_marks)) {
                                        $totalMarksObtained += $round->marks_obtained;
                                        $totalMaxMarks += $round->total_marks;
                                    }
                                }
                                $overallPercentage = $totalMaxMarks > 0 ? round(($totalMarksObtained / $totalMaxMarks) * 100, 2) : 0;
                            @endphp
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Total Marks</div>
                                    <div class="info-value">{{ $totalMarksObtained }}/{{ $totalMaxMarks }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Overall Percentage</div>
                                    <div class="info-value">{{ $overallPercentage }}%</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Rounds Passed</div>
                                    <div class="info-value">{{ $passedRounds }}/{{ $totalRounds }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                @elseif($selectionStatus === 'rejected')
                    <div style="text-align: center; padding: 40px;">
                        <i class="fas fa-times-circle" style="font-size: 60px; color: var(--danger); margin-bottom: 20px;"></i>
                   
                        <p style="color: var(--gray);">We are sorry to inform you that your application has not been selected for this position.</p>
                    </div>
                @else
                    <div style="text-align: center; padding: 40px;">
                        <i class="fas fa-hourglass-half" style="font-size: 60px; color: var(--gray); margin-bottom: 20px;"></i>
                        <h3 style="color: var(--dark); margin-bottom: 10px;">Selection Pending</h3>
                        <p style="color: var(--gray);">Your application is under review. The selection committee will evaluate all interview rounds and get back to you.</p>
                    </div>
                @endif
            </div>

            <!-- Onboarding Step Content -->
<!-- Onboarding Step Content -->
<div id="step-4-content" style="display: {{ $currentStep == 4 ? 'block' : 'none' }};">
    <div class="section-title">
        <i class="fas fa-rocket"></i>
        Onboarding Process
    </div>

    @if($selectionStatus === 'completed' && $onboardingStatus === 'in_progress')
        <div class="info-grid">
            @if(!empty($interviewConfig->joining_date))
                <div class="info-item">
                    <div class="info-label">Joining Date</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($interviewConfig->joining_date)->format('l, d M Y') }}</div>
                </div>
            @endif
            
            @if(!empty($interviewConfig->reporting_time))
                <div class="info-item">
                    <div class="info-label">Reporting Time</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($interviewConfig->reporting_time)->format('h:i A') }}</div>
                </div>
            @endif
            
            @if(!empty($interviewConfig->reporting_venue))
                <div class="info-item">
                    <div class="info-label">Reporting Venue</div> 
                    <div class="info-value">{{ $interviewConfig->reporting_venue }}</div>
                </div>
            @endif
            
            @if(!empty($interviewConfig->induction_program))
                <div class="info-item">
                    <div class="info-label">Induction Program</div>
                    <div class="info-value">{{ $interviewConfig->induction_program }} ({{ $interviewConfig->induction_days ?? 3 }} days)</div>
                </div>
            @endif

            @if(!empty($interviewConfig->training_months))
                <div class="info-item">
                    <div class="info-label">Training Period</div>
                    <div class="info-value">{{ $interviewConfig->training_months }} months</div>
                </div>
            @endif
        </div>

        @if(!empty($interviewConfig->onboarding_documents))
            @php
                $docs = is_string($interviewConfig->onboarding_documents) 
                    ? json_decode($interviewConfig->onboarding_documents, true) 
                    : $interviewConfig->onboarding_documents;
            @endphp
            @if(!empty($docs) && is_array($docs))
                <div style="margin-top: 25px;">
                    <h4 style="color: var(--dark); margin-bottom: 15px;">Required Onboarding Documents</h4>
                    <div class="documents-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        @foreach($docs as $doc)
                            @if(is_array($doc) && isset($doc['name']))
                                <div class="document-item" onclick="uploadDocument('{{ $doc['name'] }}')">
                                    <i class="fas fa-file-alt"></i>
                                    <div style="font-weight: 500;">{{ $doc['name'] }}</div>
                                    <div style="font-size: 11px; color: var(--gray); margin-top: 8px;">Click to upload</div>
                                </div>
                            @elseif(is_string($doc))
                                <div class="document-item" onclick="uploadDocument('{{ $doc }}')">
                                    <i class="fas fa-file-alt"></i>
                                    <div style="font-weight: 500;">{{ $doc }}</div>
                                    <div style="font-size: 11px; color: var(--gray); margin-top: 8px;">Click to upload</div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        <div style="margin-top: 30px; background: var(--light); border-radius: 16px; padding: 20px;">
            <h4 style="color: var(--dark); margin-bottom: 15px;">Onboarding Checklist</h4>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="width: 20px; height: 20px; background: var(--success); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">✓</span>
                    <span>Complete documentation</span>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="width: 20px; height: 20px; background: var(--border); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">2</span>
                    <span>Attend induction program</span>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="width: 20px; height: 20px; background: var(--border); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">3</span>
                    <span>Complete HR formalities</span>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="width: 20px; height: 20px; background: var(--border); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">4</span>
                    <span>Meet team members</span>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <button class="btn btn-primary" onclick="contactHR()">
                <i class="fas fa-headset"></i> Contact HR
            </button>
        </div>
    
    @elseif($onboardingStatus === 'rejected')
        <!-- Onboarding Rejected Message -->
        <div style="text-align: center; padding: 60px 20px; background: #fff5f5; border-radius: 16px; border: 1px solid #fecaca;">
            <i class="fas fa-times-circle" style="font-size: 60px; color: var(--danger); margin-bottom: 20px;"></i>
            <h3 style="color: var(--danger); font-size: 24px; margin-bottom: 15px;">Onboarding Declined</h3>
            <p style="color: var(--dark); font-size: 16px; max-width: 500px; margin: 0 auto 20px;">
                We regret to inform you that the onboarding process has been declined. 
              
            </p>
     
        </div>
    
    @elseif($selectionStatus === 'completed' && $onboardingStatus === 'pending')
        <!-- Onboarding Pending Message -->
        <div style="text-align: center; padding: 40px; background: #fffbeb; border-radius: 16px;">
            <i class="fas fa-hourglass-half" style="font-size: 60px; color: var(--warning); margin-bottom: 20px;"></i>
            <h3 style="color: var(--dark); margin-bottom: 10px;">Onboarding Pending</h3>
            <p style="color: var(--gray);">Your onboarding process will begin soon. Please check back later.</p>
        </div>
    
    @else
        <!-- Default message when not selected -->
        <div class="empty-state">
            <i class="fas fa-rocket"></i>
            <h3>Onboarding Not Started</h3>
            <p>Once you're selected, your onboarding details will appear here.</p>
        </div>
    @endif
</div>
        </div>
    @endif

    <!-- Reschedule Modal -->
    <div class="modal" id="rescheduleModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Request Interview Reschedule</h2>
                <span class="modal-close" onclick="closeModal('rescheduleModal')">&times;</span>
            </div>
            
            <div class="form-group">
                <label class="form-label">Preferred Date</label>
                <input type="date" id="rescheduleDate" class="form-control" min="{{ now()->addDay()->format('Y-m-d') }}">
            </div>
            
            <div class="form-group">
                <label class="form-label">Preferred Time</label>
                <input type="time" id="rescheduleTime" class="form-control" value="10:00">
            </div>
            
            <div class="form-group">
                <label class="form-label">Reason for Reschedule</label>
                <textarea id="rescheduleReason" class="form-control" rows="4" placeholder="Please provide a reason..."></textarea>
            </div>

            <div class="action-buttons" style="justify-content: flex-end;">
                <button class="btn btn-outline" onclick="closeModal('rescheduleModal')">Cancel</button>
                <button class="btn btn-primary" onclick="submitReschedule({{ $lead->id ?? 0 }})">Submit Request</button>
            </div>
        </div>
    </div>

    <!-- Document Upload Modal -->
    <div class="modal" id="uploadModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="uploadModalTitle">Upload Document</h2>
                <span class="modal-close" onclick="closeModal('uploadModal')">&times;</span>
            </div>
            
            <div style="text-align: center; padding: 30px; border: 2px dashed var(--border); border-radius: 12px; margin-bottom: 20px;">
                <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: var(--primary); margin-bottom: 15px;"></i>
                <p style="color: var(--gray); margin-bottom: 10px;">Click to browse or drag and drop</p>
                <p style="font-size: 12px; color: var(--gray);">PDF, DOC, DOCX, JPG (Max 5MB)</p>
                <input type="file" id="fileInput" style="display: none;" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <button class="btn btn-outline" style="margin-top: 15px;" onclick="document.getElementById('fileInput').click()">Browse Files</button>
            </div>

            <div class="action-buttons" style="justify-content: flex-end;">
                <button class="btn btn-outline" onclick="closeModal('uploadModal')">Cancel</button>
                <button class="btn btn-primary" onclick="uploadFile()">Upload</button>
            </div>
        </div>
    </div>

    <!-- Success Notification -->
    <div class="notification" id="notification">
        <div class="notification-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="notification-content" id="notification-text"></div>
    </div>
</div>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    let currentDocument = '';
    let selectedFile = null;
    let currentLeadId = {{ $lead->id ?? 0 }};

    function showStep(stepNumber) {
        for (let i = 1; i <= 4; i++) {
            const content = document.getElementById(`step-${i}-content`);
            if (content) content.style.display = 'none';
        }
        
        const selectedContent = document.getElementById(`step-${stepNumber}-content`);
        if (selectedContent) selectedContent.style.display = 'block';
        window.location.hash = `step-${stepNumber}`;
        
        // Update active class on step items
        document.querySelectorAll('.step-item').forEach((item, index) => {
            if (index + 1 === stepNumber) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
        
        document.querySelectorAll('.mobile-step-item').forEach((item, index) => {
            if (index + 1 === stepNumber) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
        
        // Update scroll dots
        document.querySelectorAll('.scroll-dot').forEach((dot, index) => {
            if (index + 1 === stepNumber) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
        
        if (window.innerWidth <= 768) {
            selectedContent.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    function openRescheduleModal() {
        openModal('rescheduleModal');
    }

    function uploadDocument(docName) {
        currentDocument = docName;
        document.getElementById('uploadModalTitle').textContent = `Upload ${docName}`;
        openModal('uploadModal');
    }

    document.getElementById('fileInput')?.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            selectedFile = e.target.files[0];
            
            if (selectedFile.size > 5 * 1024 * 1024) {
                showNotification('File size exceeds 5MB limit', 'error');
                this.value = '';
                selectedFile = null;
                return;
            }
            
            showNotification(`File selected: ${selectedFile.name}`);
        }
    });

    function uploadFile() {
        if (!selectedFile) {
            showNotification('Please select a file', 'error');
            return;
        }

        closeModal('uploadModal');
        showNotification(`${currentDocument} uploaded successfully!`);
        
        selectedFile = null;
        document.getElementById('fileInput').value = '';
    }

    function submitReschedule(leadId) {
        const date = document.getElementById('rescheduleDate').value;
        const time = document.getElementById('rescheduleTime').value;
        const reason = document.getElementById('rescheduleReason').value;

        if (!date || !time) {
            showNotification('Please select date and time', 'error');
            return;
        }

        if (!reason.trim()) {
            showNotification('Please provide a reason', 'error');
            return;
        }

        showNotification('Reschedule request submitted successfully!');
        closeModal('rescheduleModal');
        
        document.getElementById('rescheduleDate').value = '';
        document.getElementById('rescheduleTime').value = '10:00';
        document.getElementById('rescheduleReason').value = '';
    }

    function acceptOffer() {
        showNotification('Offer accepted successfully!');
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    }

    function contactHR() {
        window.location.href = 'mailto:hr@institute.com';
    }

    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        const text = document.getElementById('notification-text');
        
        text.textContent = message;
        notification.className = 'notification';
        
        if (type === 'error') {
            notification.classList.add('error');
            notification.querySelector('i').className = 'fas fa-exclamation-circle';
        } else {
            notification.querySelector('i').className = 'fas fa-check-circle';
        }
        
        notification.style.display = 'flex';
        
        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }

    // Mobile Steps Scroll Indicators
    function initMobileSteps() {
        const wrapper = document.querySelector('.mobile-steps-wrapper');
        const container = document.querySelector('.mobile-steps-container');
        const dots = document.querySelectorAll('.scroll-dot');
        const items = document.querySelectorAll('.mobile-step-item');
        
        if (!wrapper || !dots.length || !items.length) return;
        
        function updateScrollState() {
            const scrollPosition = wrapper.scrollLeft;
            const maxScroll = wrapper.scrollWidth - wrapper.clientWidth;
            
            if (scrollPosition >= maxScroll - 10) {
                container.classList.add('scrolled-end');
            } else {
                container.classList.remove('scrolled-end');
            }
            
            const itemWidth = items[0]?.offsetWidth || 300;
            const gap = 12;
            const totalWidth = itemWidth + gap;
            const activeIndex = Math.round(scrollPosition / totalWidth);
            
            dots.forEach((dot, index) => {
                if (index === activeIndex) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }
        
        wrapper.addEventListener('scroll', updateScrollState);
        window.addEventListener('resize', updateScrollState);
        
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                const itemWidth = items[0]?.offsetWidth || 300;
                const gap = 12;
                const scrollTo = index * (itemWidth + gap);
                
                wrapper.scrollTo({
                    left: scrollTo,
                    behavior: 'smooth'
                });
                
                // Show the corresponding step
                showStep(index + 1);
            });
        });
        
        updateScrollState();
    }

    document.addEventListener('DOMContentLoaded', function() {
        initMobileSteps();
        
        if (window.location.hash) {
            const step = window.location.hash.replace('#step-', '');
            if (step >= 1 && step <= 4) {
                showStep(parseInt(step));
            }
        }
        
        if ('ontouchstart' in window) {
            document.querySelectorAll('.step-item, .btn, .document-item, .mobile-step-item').forEach(el => {
                el.addEventListener('touchstart', function() {
                    this.style.transform = 'scale(0.98)';
                });
                el.addEventListener('touchend', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        }
    });

    window.addEventListener('orientationchange', function() {
        setTimeout(() => {
            initMobileSteps();
            window.scrollTo(0, 0);
        }, 100);
    });
</script>
@endsection
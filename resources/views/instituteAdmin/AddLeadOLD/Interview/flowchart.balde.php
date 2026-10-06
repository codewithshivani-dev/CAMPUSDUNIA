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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        border: 1px solid var(--border);
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border-color: var(--primary);
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }

    .stat-icon.application { background: var(--primary); }
    .stat-icon.interview { background: var(--purple); }
    .stat-icon.selection { background: var(--success); }
    .stat-icon.onboarding { background: var(--warning); }

    .stat-value {
        font-size: 26px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 4px;
    }

    .stat-label {
        color: var(--gray);
        font-size: 13px;
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
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin: 20px 0;
    }

    .round-card {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border-radius: 16px;
        padding: 20px;
        border: 2px solid var(--border);
        transition: all 0.3s;
    }

    .round-card:hover {
        border-color: var(--purple);
        transform: translateY(-3px);
    }

    .round-card.completed {
        border-color: var(--success);
        background: #f0fdf4;
    }

    .round-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
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
    .round-status.completed { background: #d1fae5; color: var(--success); }
    .round-status.scheduled { background: #dbeafe; color: var(--primary); }

    .round-details {
        margin-top: 10px;
        font-size: 13px;
        color: var(--gray);
    }

    .round-details i {
        width: 16px;
        color: var(--purple);
        margin-right: 6px;
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

    .timeline-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        border: 1px solid var(--border);
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 7px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary), var(--purple));
    }

    .timeline-item {
        position: relative;
        padding-bottom: 25px;
        padding-left: 25px;
    }

    .timeline-marker {
        position: absolute;
        left: -36px;
        top: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: white;
        border: 2px solid var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 12px;
        z-index: 1;
    }

    .timeline-item.completed .timeline-marker {
        background: var(--success);
        border-color: var(--success);
        color: white;
    }

    .timeline-content {
        background: var(--light);
        padding: 15px 20px;
        border-radius: 12px;
    }

    .timeline-date {
        font-size: 11px;
        color: var(--gray);
        margin-bottom: 5px;
    }

    .timeline-title {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 5px;
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
        
        .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        
        .stat-card {
            padding: 15px;
        }
        
        .stat-header {
            gap: 8px;
        }
        
        .stat-icon {
            width: 35px;
            height: 35px;
            font-size: 16px;
        }
        
        .stat-value {
            font-size: 20px;
        }
        
        .stat-label {
            font-size: 11px;
        }
        
        .journey-card, .content-card, .timeline-card {
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
        
        .timeline {
            padding-left: 15px;
        }
        
        .timeline::before {
            left: 2px;
        }
        
        .timeline-marker {
            left: -26px;
            width: 25px;
            height: 25px;
            font-size: 10px;
        }
        
        .timeline-item {
            padding-left: 15px;
        }
        
        .timeline-content {
            padding: 12px 15px;
        }
        
        .timeline-date {
            font-size: 10px;
        }
        
        .timeline-title {
            font-size: 14px;
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
        
        .panel-grid {
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
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
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
        
        .timeline-progress div:last-child {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
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
        .round-card,
        .timeline-item {
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
    
    // Calculate step statuses
    $stepStatuses = [
        'application' => 'completed',
        'interview' => 'pending',
        'selection' => 'pending',
        'onboarding' => 'pending'
    ];
    
    $totalRounds = $interviewRounds->count();
    $completedRounds = $interviewRounds->where('status', 'completed')->count();
    $nextRound = $interviewRounds->where('status', 'scheduled')->first();
    $currentStep = 1;
    
    // Set interview step status
    if ($totalRounds > 0) {
        if ($completedRounds == $totalRounds) {
            $stepStatuses['interview'] = 'completed';
            $currentStep = 3;
        } elseif ($completedRounds > 0 || $nextRound) {
            $stepStatuses['interview'] = 'active';
            $currentStep = 2;
        }
    }
    
    // Check selection status
    $leadStatus = $lead->lead_status ?? '';
    if (in_array($leadStatus, ['selected', 'converted'])) {
        $stepStatuses['selection'] = 'completed';
        $stepStatuses['onboarding'] = 'active';
        $currentStep = 4;
    } elseif (in_array($leadStatus, ['rejected', 'lost'])) {
        $stepStatuses['selection'] = 'locked';
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
        if ($status === 'active' || $stepNumber === $currentStep) return 'active';
        if ($status === 'pending') return 'pending';
        return 'locked';
    }
    
    // Build timeline
    $timeline = [];
    
    if ($lead && $lead->created_at) {
        $timeline[] = [
            'date' => $lead->created_at->format('Y-m-d H:i:s'),
            'step' => 'Application',
            'title' => 'Application Submitted',
            'description' => 'Your application was submitted successfully.',
            'icon' => 'fa-file-alt',
            'status' => 'completed',
            'order' => 1
        ];
    }
    
    if ($totalRounds > 0) {
        if ($completedRounds == $totalRounds) {
            $timeline[] = [
                'date' => $interviewRounds->last()->round_date ?? now()->format('Y-m-d'),
                'step' => 'Interview',
                'title' => 'All Interviews Completed',
                'description' => "All $totalRounds interview rounds have been completed.",
                'icon' => 'fa-users',
                'status' => 'completed',
                'order' => 2
            ];
        } elseif ($completedRounds > 0) {
            $timeline[] = [
                'date' => $interviewRounds->first()->round_date ?? now()->format('Y-m-d'),
                'step' => 'Interview',
                'title' => 'Interviews in Progress',
                'description' => "$completedRounds of $totalRounds interview rounds completed.",
                'icon' => 'fa-users',
                'status' => 'active',
                'order' => 2
            ];
            
            if ($nextRound) {
                $dateStr = $nextRound->round_date;
                if (!empty($nextRound->start_time) && $nextRound->start_time != '00:00:00') {
                    $dateStr = $nextRound->round_date . ' ' . $nextRound->start_time;
                }
                $timeline[] = [
                    'date' => $dateStr,
                    'step' => 'Interview',
                    'title' => 'Next: ' . ($nextRound->name ?? 'Interview Round'),
                    'description' => $nextRound->desc ?? 'Scheduled interview round',
                    'icon' => 'fa-calendar-check',
                    'status' => 'pending',
                    'order' => 3
                ];
            }
        } else {
            if ($nextRound) {
                $dateStr = $nextRound->round_date;
                if (!empty($nextRound->start_time) && $nextRound->start_time != '00:00:00') {
                    $dateStr = $nextRound->round_date . ' ' . $nextRound->start_time;
                }
                $timeline[] = [
                    'date' => $dateStr,
                    'step' => 'Interview',
                    'title' => 'Interviews Scheduled',
                    'description' => ($nextRound->name ?? 'First interview round') . ' scheduled',
                    'icon' => 'fa-calendar-check',
                    'status' => 'pending',
                    'order' => 2
                ];
            } else {
                $timeline[] = [
                    'date' => $lead->created_at->addDays(7)->format('Y-m-d'),
                    'step' => 'Interview',
                    'title' => 'Interviews Pending',
                    'description' => 'Interview schedule will be updated soon.',
                    'icon' => 'fa-clock',
                    'status' => 'pending',
                    'order' => 2
                ];
            }
        }
    } else {
        $timeline[] = [
            'date' => $lead->created_at->addDays(3)->format('Y-m-d'),
            'step' => 'Interview',
            'title' => 'Interview Process',
            'description' => 'Interview details will be updated soon.',
            'icon' => 'fa-clock',
            'status' => 'pending',
            'order' => 2
        ];
    }
    
    if (in_array($leadStatus, ['selected', 'converted'])) {
        $timeline[] = [
            'date' => ($lead->updated_at ?? now())->format('Y-m-d H:i:s'),
            'step' => 'Selection',
            'title' => 'Selected!',
            'description' => 'Congratulations! You have been selected for the position.',
            'icon' => 'fa-trophy',
            'status' => 'completed',
            'order' => 4
        ];
    } elseif (in_array($leadStatus, ['rejected', 'lost'])) {
        $timeline[] = [
            'date' => ($lead->updated_at ?? now())->format('Y-m-d H:i:s'),
            'step' => 'Selection',
            'title' => 'Application Not Selected',
            'description' => 'Thank you for your interest in this position.',
            'icon' => 'fa-times-circle',
            'status' => 'locked',
            'order' => 4
        ];
    } else {
        $estimatedDate = $totalRounds > 0 ? 
            \Carbon\Carbon::parse($interviewRounds->last()->round_date ?? now())->addDays(7) : 
            now()->addDays(14);
        
        $timeline[] = [
            'date' => $estimatedDate->format('Y-m-d'),
            'step' => 'Selection',
            'title' => 'Selection Decision Pending',
            'description' => 'pending selection decision. We will update you soon.',
            'icon' => 'fa-hourglass-half',
            'status' => 'pending',
            'order' => 5
        ];
    }
    
    if ($stepStatuses['selection'] === 'completed' && $interviewConfig) {
        if (!empty($interviewConfig->joining_date)) {
            $timeline[] = [
                'date' => $interviewConfig->joining_date,
                'step' => 'Onboarding',
                'title' => 'Joining Date',
                'description' => 'Your expected joining date: ' . \Carbon\Carbon::parse($interviewConfig->joining_date)->format('d M Y'),
                'icon' => 'fa-briefcase',
                'status' => 'pending',
                'order' => 6
            ];
        }
        
        if (!empty($interviewConfig->induction_program)) {
            $inductionDate = !empty($interviewConfig->joining_date) 
                ? \Carbon\Carbon::parse($interviewConfig->joining_date)->subDays(1)
                : now()->addDays(6);
            
            $timeline[] = [
                'date' => $inductionDate->format('Y-m-d'),
                'step' => 'Onboarding',
                'title' => 'Induction Program',
                'description' => $interviewConfig->induction_program . ' program',
                'icon' => 'fa-users',
                'status' => 'pending',
                'order' => 7
            ];
        }
    } elseif ($stepStatuses['selection'] === 'completed') {
        $timeline[] = [
            'date' => now()->addDays(10)->format('Y-m-d'),
            'step' => 'Onboarding',
            'title' => 'Onboarding Process',
            'description' => 'Onboarding details will be shared soon.',
            'icon' => 'fa-rocket',
            'status' => 'pending',
            'order' => 6
        ];
    }
    
    // Filter and sort timeline
    $timeline = array_filter($timeline, function($event) {
        try {
            \Carbon\Carbon::parse($event['date']);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    });
    
    usort($timeline, function($a, $b) {
        return strtotime($a['date']) - strtotime($b['date']); 
    });
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

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon application">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $lead->created_at ? $lead->created_at->format('d M') : 'N/A' }}</div>
                        <div class="stat-label">Applied On</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon interview">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="stat-value">
                            @if($stepStatuses['interview'] === 'completed')
                                Completed
                            @elseif($stepStatuses['interview'] === 'active')
                                {{ $completedRounds }}/{{ $totalRounds }} Rounds
                            @else
                                Pending
                            @endif
                        </div>
                        <div class="stat-label">Interview Progress</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon selection">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-value">
                            {{ $stepStatuses['selection'] === 'completed' ? 'Selected' : 'Pending' }}
                        </div>
                        <div class="stat-label">Selection Status</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon onboarding">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $progress }}%</div>
                        <div class="stat-label">Overall Progress</div>
                    </div>
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

            <!-- Desktop Steps Grid -->
            <div class="steps-grid desktop-steps">
                <div class="step-item {{ getStepClass($stepStatuses['application'], 1, $currentStep) }}" onclick="showStep(1)">
                    <div class="step-icon step1">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <div class="step-name">Application</div>
                    <span class="step-status {{ $stepStatuses['application'] }}">
                        {{ ucfirst($stepStatuses['application']) }}
                    </span>
                    @if($stepStatuses['application'] === 'completed' && $lead->created_at)
                        <div style="font-size: 11px; color: var(--success); margin-top: 8px;">
                            <i class="fas fa-check-circle"></i> {{ $lead->created_at->format('d M') }}
                        </div>
                    @endif
                </div>

                <div class="step-item {{ getStepClass($stepStatuses['interview'], 2, $currentStep) }}" onclick="showStep(2)">
                    <div class="step-icon step2">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="step-name">Interview</div>
                    <span class="step-status {{ $stepStatuses['interview'] }}">
                        {{ ucfirst($stepStatuses['interview']) }}
                    </span>
                    @if($stepStatuses['interview'] === 'active' && $totalRounds > 0)
                        <div style="font-size: 11px; color: var(--primary); margin-top: 8px;">
                            {{ $completedRounds }}/{{ $totalRounds }} Rounds
                        </div>
                    @endif
                </div>

                <div class="step-item {{ getStepClass($stepStatuses['selection'], 3, $currentStep) }}" onclick="showStep(3)">
                    <div class="step-icon step3">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div class="step-name">Selection</div>
                    <span class="step-status {{ $stepStatuses['selection'] }}">
                        {{ ucfirst($stepStatuses['selection']) }}
                    </span>
                </div>

                <div class="step-item {{ getStepClass($stepStatuses['onboarding'], 4, $currentStep) }}" onclick="showStep(4)">
                    <div class="step-icon step4">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <div class="step-name">Onboarding</div>
                    <span class="step-status {{ $stepStatuses['onboarding'] }}">
                        {{ ucfirst($stepStatuses['onboarding']) }}
                    </span>
                </div>
            </div>

            <!-- Mobile Steps Carousel -->
            <div class="mobile-steps-container">
                <div class="mobile-steps-wrapper">
                    <div class="mobile-step-item {{ getStepClass($stepStatuses['application'], 1, $currentStep) }}" onclick="showStep(1)">
                        <div class="mobile-step-icon step1">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <div class="mobile-step-content">
                            <div class="mobile-step-name">Application</div>
                            <span class="mobile-step-status {{ $stepStatuses ['application'] }}">
                                {{ ucfirst($stepStatuses['application']) }}
                            </span> 
                        </div>
                        @if($stepStatuses['application'] === 'completed' && $lead->created_at)
                            <div class="mobile-step-date">
                                {{ $lead->created_at->format('d M') }}
                            </div>
                        @endif
                    </div>

                    <div class="mobile-step-item {{ getStepClass($stepStatuses['interview'], 2, $currentStep) }}" onclick="showStep(2)">
                        <div class="mobile-step-icon step2">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="mobile-step-content">
                            <div class="mobile-step-name">Interview</div>
                            <span class="mobile-step-status {{ $stepStatuses['interview'] }}">
                                {{ ucfirst($stepStatuses['interview']) }}
                            </span>
                        </div>
                        @if($stepStatuses['interview'] === 'active' && $totalRounds > 0)
                            <div class="mobile-step-progress">
                                {{ $completedRounds }}/{{ $totalRounds }} 
                            </div>
                        @endif
                    </div>

                    <div class="mobile-step-item {{ getStepClass($stepStatuses['selection'], 3, $currentStep) }}" onclick="showStep(3)">
                        <div class="mobile-step-icon step3">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <div class="mobile-step-content">
                            <div class="mobile-step-name">Selection</div>
                            <span class="mobile-step-status {{ $stepStatuses['selection'] }}">
                                {{ ucfirst($stepStatuses['selection']) }}
                            </span>
                        </div>
                    </div>

                    <div class="mobile-step-item {{ getStepClass($stepStatuses['onboarding'], 4, $currentStep) }}" onclick="showStep(4)">
                        <div class="mobile-step-icon step4">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <div class="mobile-step-content">
                            <div class="mobile-step-name">Onboarding</div>
                            <span class="mobile-step-status {{ $stepStatuses['onboarding'] }}">
                                {{ ucfirst($stepStatuses['onboarding']) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="scroll-indicators">
                    <span class="scroll-dot active" data-step="1"></span>
                    <span class="scroll-dot" data-step="2"></span>
                    <span class="scroll-dot" data-step="3"></span>
                    <span class="scroll-dot" data-step="4"></span>
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
                </div>

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
                                    @if(is_string($doc))
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

            <!-- Interview Step Content -->
            <div id="step-2-content" style="display: {{ $currentStep == 2 ? 'block' : 'none' }};">
                <div class="section-title">
                    <i class="fas fa-users"></i>
                    Interview Process
                </div>

                @if($totalRounds > 0)
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px; flex-wrap: wrap; gap: 10px;">
                            <span style="color: var(--dark); font-weight: 500;">Interview Progress</span>
                            <span style="color: var(--primary); font-weight: 600;">
                                Round {{ $completedRounds }}/{{ $totalRounds }} Completed
                            </span>
                        </div>
                        <div class="progress-bar" style="height: 8px;">
                            <div class="progress-fill" style="width: {{ $totalRounds > 0 ? ($completedRounds / $totalRounds) * 100 : 0 }}%; background: var(--purple);"></div>
                        </div>
                    </div>

                    <div class="rounds-grid">
                        @foreach($interviewRounds as $round)
                            @php
                                $roundDate = null;
                                $roundTime = null;
                                
                                if ($round->round_date) {
                                    try {
                                        $roundDate = \Carbon\Carbon::parse($round->round_date);
                                    } catch (\Exception $e) {
                                        $roundDate = null;
                                    }
                                }
                                
                                if ($round->start_time && $round->start_time != '00:00:00') {
                                    try {
                                        $roundTime = \Carbon\Carbon::parse($round->start_time);
                                    } catch (\Exception $e) {
                                        $roundTime = null;
                                    }
                                }
                            @endphp
                            
                            <div class="round-card {{ $round->status === 'completed' ? 'completed' : '' }}">
                                <div class="round-header">
                                    <span class="round-name">{{ $round->name ?? 'Interview Round' }}</span>
                                    <span class="round-status {{ $round->status ?? 'pending' }}">
                                        {{ ucfirst($round->status ?? 'pending') }}
                                    </span>
                                </div>
                                
                                <div class="round-details">
                                    @if($roundDate)
                                        <div>
                                            <i class="fas fa-calendar"></i> 
                                            {{ $roundDate->format('d M Y') }}
                                        </div>
                                    @endif
                                    
                                    @if($roundTime)
                                        <div>
                                            <i class="fas fa-clock"></i> 
                                            {{ $roundTime->format('h:i A') }} 
                                            @if($round->duration)
                                                ({{ $round->duration }} mins)
                                            @endif
                                        </div>
                                    @endif
                                    
                                    @if($round->type)
                                        <div>
                                            <i class="fas fa-tag"></i> 
                                            {{ $round->type_label ?? $round->type }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

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
                                
                                @if($interviewConfig)
                                    <div class="detail-block">
                                        <i class="fas fa-{{ $interviewConfig->interview_mode === 'online' ? 'video' : 'building' }}"></i>
                                        <div class="detail-block-label">Mode</div>
                                        <div class="detail-block-value">{{ ucfirst($interviewConfig->interview_mode ?? 'online') }}</div>
                                    </div>
                                @endif
                            </div>

                            <div class="action-buttons" style="margin-top: 20px;">
                                <button class="btn btn-outline" onclick="openRescheduleModal()">
                                    <i class="fas fa-calendar-alt"></i> Request Reschedule
                                </button>
                            </div>
                        </div>
                    @endif

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

                                @if($interviewConfig->interview_mode === 'offline' && !empty($interviewConfig->venue_address))
                                    <div>
                                        <strong>Venue Address:</strong><br>
                                        {{ $interviewConfig->venue_address }}
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

                @if(in_array($lead->lead_status ?? '', ['selected', 'converted']))
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
                @elseif(in_array($lead->lead_status ?? '', ['rejected', 'lost']))
                    <div style="text-align: center; padding: 40px;">
                        <i class="fas fa-times-circle" style="font-size: 60px; color: var(--danger); margin-bottom: 20px;"></i>
                        <h3 style="color: var(--dark); margin-bottom: 10px;">Application Status Update</h3>
                        <p style="color: var(--gray);">We regret to inform you that your application has not been selected for this position.</p>
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
            <div id="step-4-content" style="display: {{ $currentStep == 4 ? 'block' : 'none' }};">
                <div class="section-title">
                    <i class="fas fa-rocket"></i>
                    Onboarding Process
                </div>

                @if($stepStatuses['selection'] === 'completed' && $interviewConfig)
                    <div class="info-grid">
                        @if(!empty($interviewConfig->joining_date))
                            <div class="info-item">
                                <div class="info-label">Start Date</div>
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
                    </div>

                    @if(!empty($interviewConfig->onboarding_documents))
                        @php
                            $docs = is_string($interviewConfig->onboarding_documents) 
                                ? json_decode($interviewConfig->onboarding_documents, true) 
                                : $interviewConfig->onboarding_documents;
                        @endphp
                        @if(!empty($docs) && is_array($docs))
                            <div style="margin-top: 25px;">
                                <h4 style="color: var(--dark); margin-bottom: 15px;">Required Documents</h4>
                                <div class="documents-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                                    @foreach($docs as $doc)
                                        @if(is_string($doc))
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
                @else
                    <div class="empty-state">
                        <i class="fas fa-rocket"></i>
                        <h3>Onboarding Not Started</h3>
                        <p>Once you're selected, your onboarding details will appear here.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Timeline Card -->
        @if(!empty($timeline))
            <div class="timeline-card">
                <div class="section-title" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                    <div>
                        <i class="fas fa-history"></i>
                        Application Journey Timeline
                    </div>
                    <div style="display: flex; gap: 10px; font-size: 12px; flex-wrap: wrap;">
                        <span><span style="display: inline-block; width: 10px; height: 10px; background: var(--success); border-radius: 50%; margin-right: 5px;"></span>Completed</span>
                        <span><span style="display: inline-block; width: 10px; height: 10px; background: var(--primary); border-radius: 50%; margin-right: 5px;"></span>Active</span>
                        <span><span style="display: inline-block; width: 10px; height: 10px; background: var(--warning); border-radius: 50%; margin-right: 5px;"></span>Pending</span>
                        <span><span style="display: inline-block; width: 10px; height: 10px; background: var(--gray); border-radius: 50%; margin-right: 5px;"></span>Locked</span>
                    </div>
                </div>

                <div class="timeline-progress" style="margin-bottom: 25px; padding: 15px; background: var(--light); border-radius: 12px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; flex-wrap: wrap; gap: 10px;">
                        <span style="font-weight: 600; color: var(--dark);">Journey Progress</span>
                        <span style="color: var(--primary); font-weight: 600;">{{ $progress }}% Complete</span>
                    </div>
                    <div class="progress-bar" style="height: 8px;">
                        <div class="progress-fill" style="width: {{ $progress }}%;"></div>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 10px; font-size: 11px; flex-wrap: wrap; gap: 8px;">
                        <span style="color: {{ $stepStatuses['application'] === 'completed' ? 'var(--success)' : 'var(--gray)' }};">
                            <i class="fas fa-{{ $stepStatuses['application'] === 'completed' ? 'check-circle' : 'circle' }}"></i> Application
                        </span>
                        <span style="color: {{ $stepStatuses['interview'] === 'completed' ? 'var(--success)' : ($stepStatuses['interview'] === 'active' ? 'var(--primary)' : 'var(--gray)') }};">
                            <i class="fas fa-{{ $stepStatuses['interview'] === 'completed' ? 'check-circle' : ($stepStatuses['interview'] === 'active' ? 'spinner' : 'circle') }}"></i> Interview
                        </span>
                        <span style="color: {{ $stepStatuses['selection'] === 'completed' ? 'var(--success)' : ($stepStatuses['selection'] === 'active' ? 'var(--primary)' : 'var(--gray)') }};">
                            <i class="fas fa-{{ $stepStatuses['selection'] === 'completed' ? 'check-circle' : ($stepStatuses['selection'] === 'active' ? 'spinner' : 'circle') }}"></i> Selection
                        </span>
                        <span style="color: {{ $stepStatuses['onboarding'] === 'completed' ? 'var(--success)' : ($stepStatuses['onboarding'] === 'active' ? 'var(--primary)' : 'var(--gray)') }};">
                            <i class="fas fa-{{ $stepStatuses['onboarding'] === 'completed' ? 'check-circle' : ($stepStatuses['onboarding'] === 'active' ? 'spinner' : 'circle') }}"></i> Onboarding
                        </span>
                    </div>
                </div>

                <div class="timeline">
                    @php
                        $lastStep = '';
                    @endphp
                    
                    @foreach($timeline as $event)
                        @php
                            try {
                                $eventDate = \Carbon\Carbon::parse($event['date']);
                                $isToday = $eventDate->isToday();
                                $showStepHeader = ($event['step'] ?? '') !== $lastStep;
                                $lastStep = $event['step'] ?? '';
                            } catch (\Exception $e) {
                                continue;
                            }
                        @endphp
                        
                        @if($showStepHeader && isset($event['step']))
                            <div style="margin: 20px 0 10px 0; padding-left: 15px;">
                                <h4 style="color: var(--dark); font-size: 16px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                    @if($event['step'] == 'Application')
                                        <span style="background: var(--primary); width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;"><i class="fas fa-file-alt"></i></span>
                                    @elseif($event['step'] == 'Interview')
                                        <span style="background: var(--purple); width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;"><i class="fas fa-users"></i></span>
                                    @elseif($event['step'] == 'Selection')
                                        <span style="background: var(--success); width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;"><i class="fas fa-check-double"></i></span>
                                    @elseif($event['step'] == 'Onboarding')
                                        <span style="background: var(--warning); width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;"><i class="fas fa-rocket"></i></span>
                                    @endif
                                    {{ $event['step'] }} Stage
                                </h4>
                            </div>
                        @endif
                        
                        <div class="timeline-item {{ $event['status'] ?? 'pending' }}" style="margin-left: {{ isset($event['step']) ? '0' : '15px' }};">
                            <div class="timeline-marker">
                                <i class="fas {{ $event['icon'] ?? 'fa-calendar' }}"></i>
                            </div>
                            <div class="timeline-content" style="background: {{ $isToday ? '#fffbeb' : ($event['status'] === 'completed' ? '#f0fdf4' : 'var(--light)') }};">
                                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                    <div class="timeline-date">
                                        <i class="fas fa-calendar-alt" style="margin-right: 5px;"></i>
                                        {{ $eventDate->format('d M Y') }}
                                        @if(!$eventDate->format('H:i') == '00:00')
                                            <span style="margin-left: 5px;">
                                                <i class="fas fa-clock"></i> {{ $eventDate->format('h:i A') }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                                        @if($isToday)
                                            <span style="background: var(--warning); color: white; padding: 3px 8px; border-radius: 20px; font-size: 10px; font-weight: 600;">
                                                <i class="fas fa-bell"></i> Today
                                            </span>
                                        @endif
                                        
                                        @if($event['status'] === 'completed')
                                            <span style="background: var(--success); color: white; padding: 3px 8px; border-radius: 20px; font-size: 10px; font-weight: 600;">
                                                <i class="fas fa-check-circle"></i> Completed
                                            </span>
                                        @elseif($event['status'] === 'active')
                                            <span style="background: var(--primary); color: white; padding: 3px 8px; border-radius: 20px; font-size: 10px; font-weight: 600;">
                                                <i class="fas fa-spinner fa-pulse"></i> In Progress
                                            </span>
                                        @elseif($event['status'] === 'pending')
                                            <span style="background: var(--warning); color: white; padding: 3px 8px; border-radius: 20px; font-size: 10px; font-weight: 600;">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @elseif($event['status'] === 'locked')
                                            <span style="background: var(--gray); color: white; padding: 3px 8px; border-radius: 20px; font-size: 10px; font-weight: 600;">
                                                <i class="fas fa-lock"></i> Locked
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="timeline-title" style="font-size: 14px; margin-top: 5px;">{{ $event['title'] }}</div>
                                
                                @if(!empty($event['description']))
                                    <div style="font-size: 12px; color: var(--gray); margin-top: 5px; padding-top: 5px; border-top: 1px dashed var(--border);">
                                        {{ $event['description'] }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div style="margin-top: 30px; padding: 20px; background: linear-gradient(135deg, var(--primary), var(--purple)); border-radius: 12px; color: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                        <div>
                            <h4 style="margin: 0 0 5px 0; color: white; font-size: 16px;">Current Stage: 
                                @if($currentStep == 1)
                                    Application
                                @elseif($currentStep == 2)
                                    Interview Process
                                @elseif($currentStep == 3)
                                    Selection Review
                                @elseif($currentStep == 4)
                                    Onboarding
                                @endif
                            </h4>
                            <p style="margin: 0; opacity: 0.9; font-size: 13px;">
                                @if($currentStep == 1)
                                    Your application has been submitted successfully.
                                @elseif($currentStep == 2)
                                    You have completed {{ $completedRounds }} of {{ $totalRounds }} interview rounds.
                                @elseif($currentStep == 3)
                                    Your application is under final review.
                                @elseif($currentStep == 4)
                                    Complete your onboarding formalities.
                                @endif
                            </p>
                        </div>
                        <div style="background: rgba(255,255,255,0.2); padding: 8px 15px; border-radius: 30px; font-size: 13px;">
                            <i class="fas fa-arrow-right"></i> Next: 
                            @if($nextRound)
                                {{ $nextRound->name ?? 'Interview' }} on {{ \Carbon\Carbon::parse($nextRound->round_date)->format('d M') }}
                            @elseif($currentStep == 2 && $completedRounds == $totalRounds)
                                Selection Decision
                            @elseif($currentStep == 3 && $stepStatuses['selection'] === 'completed')
                                Onboarding
                            @else
                                Update Pending
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
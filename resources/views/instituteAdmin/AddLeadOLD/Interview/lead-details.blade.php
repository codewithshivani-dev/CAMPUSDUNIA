@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@section('content')
<style>
    :root {
        --primary: #2563eb;
        --primary-light: #3b82f6;
        --primary-dark: #1d4ed8;
        --primary-soft: #dbeafe;
        --secondary: #0ea5e9;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f8fafc;
        --border: #e2e8f0;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .container {
        margin: 0 auto;
        padding: 24px;
    }

    /* Header Styles */
    .page-header {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        color: white;
        box-shadow: 0 10px 30px rgba(37, 99, 235, 0.2);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .header-title h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .header-title p {
        font-size: 14px;
        opacity: 0.9;
    }

    .header-actions {
        display: flex;
        gap: 15px;
    }

    .btn-back {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 2px solid rgba(255,255,255,0.3);
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .btn-back:hover {
        background: white;
        color: var(--primary);
        border-color: white;
    }

    /* Main Card */
    .lead-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .lead-card-header {
        padding: 30px;
        border-bottom: 1px solid var(--border);
        background: linear-gradient(135deg, #f8fafc, white);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 20px;
    }

    .lead-info h2 {
        font-size: 28px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 10px;
    }

    .lead-meta {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .lead-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--gray);
        font-size: 14px;
        background: white;
        padding: 6px 14px;
        border-radius: 30px;
        border: 1px solid var(--border);
    }

    .lead-meta-item i {
        color: var(--primary);
    }

    /* Status Section */
    .status-container {
        position: relative;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 20px;
        border-radius: 40px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        background: linear-gradient(135deg, #f8fafc, white);
        border: 2px solid var(--border);
        transition: all 0.3s ease;
    }

    .status-badge:hover {
        border-color: var(--primary);
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.1);
    }

    .status-badge.hot { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; border-color: #fecaca; }
    .status-badge.warm { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; border-color: #fde68a; }
    .status-badge.cold { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #2563eb; border-color: #bfdbfe; }
    .status-badge.selected { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #059669; border-color: #a7f3d0; }
    .status-badge.rejected { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; border-color: #fecaca; }
    .status-badge.new { background: linear-gradient(135deg, #f1f5f9, #e2e8f0); color: var(--gray); border-color: #e2e8f0; }

    .status-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 10px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        border: 1px solid var(--border);
        min-width: 200px;
        z-index: 1000;
        display: none;
        overflow: hidden;
    }

    .status-dropdown.show {
        display: block;
    }

    .status-option {
        padding: 12px 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: background 0.2s;
        font-weight: 500;
    }

    .status-option:hover {
        background: var(--primary-soft);
    }

    /* Timeline Journey - 4 Steps */
    .timeline-section {
        padding: 30px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: var(--primary);
    }

    .timeline {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        position: relative;
        margin: 20px 0;
    }

    /* Connecting line */
    .timeline::before {
        content: '';
        position: absolute;
        top: 10px;
        left: 30px;
        right: 30px;
        height: 3px;
        background: linear-gradient(90deg, 
            var(--success) 0%, 
            var(--success) 25%, 
            var(--primary) 25%, 
            var(--primary) 50%, 
            var(--warning) 50%, 
            var(--warning) 75%, 
            var(--gray) 75%, 
            var(--gray) 100%);
        border-radius: 2px;
        z-index: 1;
    }

    .timeline-item {
        flex: 1;
        min-width: 0;
        position: relative;
        z-index: 2;
        cursor: pointer;
    }

    .timeline-item:hover .timeline-content {
        transform: translateY(-4px);
        border-color: var(--primary);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.15);
    }

    .timeline-dot {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: white;
        border: 3px solid;
        margin: 0 auto 15px;
        position: relative;
        z-index: 3;
    }

    .timeline-dot.completed {
        border-color: #10b981;
        background: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }

    .timeline-dot.in_progress {
        border-color: var(--primary);
        background: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        animation: pulse 2s infinite;
    }

    .timeline-dot.pending {
        border-color: #f59e0b;
        background: white;
    }

    .timeline-dot.rejected {
        border-color: #ef4444;
        background: #ef4444;
    }

    .timeline-content {
        background: white;
        border-radius: 16px;
        border: 2px solid var(--border);
        padding: 16px;
        transition: all 0.3s ease;
        position: relative;
    }

    .timeline-content.completed { border-color: #10b981; background: #f0fdf4; }
    .timeline-content.in_progress { border-color: var(--primary); background: #f0f9ff; }
    .timeline-content.pending { border-color: #f59e0b; background: #fffbeb; }
    .timeline-content.rejected { border-color: #ef4444; background: #fee2e2; }

    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .timeline-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: white;
    }

    .timeline-icon.completed { background: #10b981; }
    .timeline-icon.in_progress { background: var(--primary); }
    .timeline-icon.pending { background: #f59e0b; }
    .timeline-icon.rejected { background: #ef4444; }

    .timeline-step-number {
        font-size: 11px;
        font-weight: 600;
        color: var(--gray);
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 20px;
    }

    .timeline-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 6px;
    }

    .timeline-badge {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        text-align: left;
    }

    .timeline-badge:hover {
        filter: brightness(0.95);
        transform: translateY(-1px);
    }

    .timeline-badge.completed { background: #d1fae5; color: #059669; }
    .timeline-badge.in_progress { background: #dbeafe; color: #2563eb; }
    .timeline-badge.pending { background: #fef3c7; color: #d97706; }
    .timeline-badge.rejected { background: #fee2e2; color: #dc2626; }

    .timeline-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-top: 8px;
        font-size: 11px;
    }

    .timeline-info-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--gray);
    }

    .timeline-info-item i {
        color: var(--primary);
        width: 12px;
        font-size: 10px;
    }

    /* Student Information */
    .info-section {
        padding: 30px;
        border-top: 1px solid var(--border);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 16px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid var(--border);
    }

    .info-icon {
        width: 40px;
        height: 40px;
        background: var(--primary-soft);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 16px;
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        font-size: 12px;
        color: var(--gray);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 16px;
        font-weight: 600;
        color: var(--dark);
    }

    /* Follow-up Section */
    .followup-section {
        padding: 0 30px 30px;
    }

    .followup-card {
        background: #f8fafc;
        border-radius: 16px;
        padding: 25px;
        border: 1px solid var(--border);
    }

    .followup-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .followup-header i {
        color: var(--primary);
        font-size: 24px;
    }

    .followup-header h4 {
        font-size: 18px;
        font-weight: 600;
        color: var(--dark);
    }

    .followup-current {
        background: white;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        border: 2px solid var(--border);
    }

    .followup-current i {
        font-size: 24px;
        color: var(--primary);
    }

    .followup-current-info {
        flex: 1;
    }

    .followup-date-display {
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 5px;
    }

    .followup-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .followup-badge.today {
        background: #fee2e2;
        color: #dc2626;
    }

    .followup-badge.overdue {
        background: #fee2e2;
        color: #dc2626;
    }

    .followup-badge.upcoming {
        background: #d1fae5;
        color: #059669;
    }

    .followup-input-group {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .followup-input {
        flex: 1;
        min-width: 250px;
        padding: 12px 16px;
        border: 2px solid var(--border);
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .followup-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-soft);
    }

    .btn-update {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
    }

    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 10000;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-content {
        background: white;
        border-radius: 24px;
        width: 100%;
        max-width: 700px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: modalSlideUp 0.3s ease;
        border: 1px solid rgba(37, 99, 235, 0.1);
    }

    @keyframes modalSlideUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        padding: 24px 30px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #f8fafc, white);
        border-radius: 24px 24px 0 0;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .modal-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .modal-title i {
        color: var(--primary);
        background: var(--primary-soft);
        padding: 10px;
        border-radius: 14px;
        font-size: 20px;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 28px;
        color: var(--gray);
        cursor: pointer;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        background: white;
        border: 1px solid var(--border);
    }

    .modal-close:hover {
        background: #fee2e2;
        color: var(--danger);
        border-color: var(--danger);
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 30px;
    }

    .modal-section {
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 1px solid var(--border);
    }

    .modal-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .modal-section-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-section-title i {
        color: var(--primary);
        background: var(--primary-soft);
        padding: 6px;
        border-radius: 8px;
        font-size: 14px;
    }

    .modal-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .modal-info-item {
        background: #f8fafc;
        padding: 16px;
        border-radius: 14px;
        border: 1px solid var(--border);
    }

    .modal-info-label {
        font-size: 12px;
        color: var(--gray);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modal-info-label i {
        color: var(--primary);
        font-size: 12px;
    }

    .modal-info-value {
        font-weight: 600;
        color: var(--dark);
        font-size: 15px;
    }

    .modal-info-value.highlight {
        color: var(--primary);
        font-size: 18px;
    }

    .status-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
    }
    /* Rating warning animation */
@keyframes pulseWarning {
    0% { transform: scale(1); color: #dc2626; }
    50% { transform: scale(1.1); color: #b91c1c; }
    100% { transform: scale(1); color: #dc2626; }
}

.rating-warning {
    margin-top: 8px;
    font-size: 12px;
    color: #dc2626;
    background: #fee2e2;
    padding: 6px 10px;
    border-radius: 6px;
    border-left: 3px solid #dc2626;
    animation: slideDown 0.3s ease;
}

/* Success animation for passed ratings */
.rating-success {
    margin-top: 8px;
    font-size: 12px;
    color: #10b981;
    background: #f0fdf4;
    padding: 6px 10px;
    border-radius: 6px;
    border-left: 3px solid #10b981;
    animation: slideDown 0.3s ease;
}

    .status-chip.completed {
        background: #d1fae5;
        color: #059669;
    }

    .status-chip.in_progress {
        background: #dbeafe;
        color: #2563eb;
    }

    .status-chip.pending {
        background: #fef3c7;
        color: #d97706;
    }

    .status-chip.rejected {
        background: #fee2e2;
        color: #dc2626;
    }

    .status-chip.scheduled {
        background: #dbeafe;
        color: #2563eb;
    }

    .skills-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .skill-tag {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        background: var(--primary-soft);
        color: var(--primary);
    }

    .skill-tag.advanced { background: #fee2e2; color: #dc2626; }
    .skill-tag.intermediate { background: #fef3c7; color: #d97706; }
    .skill-tag.beginner { background: #dbeafe; color: #2563eb; }

    .documents-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-top: 10px;
    }

    .document-item {
        background: #f8fafc;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.2s ease;
    }

    .document-item:hover {
        border-color: var(--primary);
        background: var(--primary-soft);
    }

    .document-item i {
        color: var(--primary);
        font-size: 16px;
    }

    .rounds-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 15px;
    }

    .round-item {
        background: #f8fafc;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 15px;
    }

    .round-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .round-name {
        font-weight: 600;
        color: var(--dark);
    }

    .round-status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .round-status-badge.completed { background: #d1fae5; color: #059669; }
    .round-status-badge.pending { background: #fef3c7; color: #d97706; }
    .round-status-badge.scheduled { background: #dbeafe; color: #2563eb; }
    .round-status-badge.rejected { background: #fee2e2; color: #dc2626; }

    .round-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-top: 10px;
        font-size: 12px;
    }

    .round-detail-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--gray);
    }

    .round-detail-item i {
        color: var(--primary);
        width: 14px;
    }

    .modal-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid var(--border);
    }

    .btn-modal {
        flex: 1;
        padding: 14px 20px;
        border-radius: 14px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .btn-modal-primary {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
    }

    .btn-modal-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
    }

    .btn-modal-secondary {
        background: #f1f5f9;
        color: var(--dark);
        border: 1px solid var(--border);
    }

    .btn-modal-secondary:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }

    .notification {
        position: fixed;
        top: 24px;
        right: 24px;
        background: white;
        border-radius: 12px;
        padding: 16px 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        display: none;
        align-items: center;
        gap: 12px;
        border-left: 4px solid var(--success);
        z-index: 99999;
        max-width: 400px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .notification.error {
        border-left-color: var(--danger);
    }

    .notification-icon {
        font-size: 20px;
        color: var(--success);
    }

    .notification.error .notification-icon {
        color: var(--danger);
    }

    .notification-content {
        font-size: 14px;
        color: var(--dark);
        flex: 1;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); color: #3b82f6; }
        100% { transform: scale(1); }
    }
    /* Star hover effect */
    .fa-star {
        transition: all 0.2s ease;
    }

    .fa-star:hover {
        transform: scale(1.2) !important;
        filter: drop-shadow(0 2px 4px rgba(245, 158, 11, 0.3));
    }
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .btn-save-marks i.fa-spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    /* Success message animation */
    .marks-display, .rating-display {
        animation: slideDown 0.3s ease;
    }
    /* Marks input styles */
    .marks-input {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid var(--border);
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .marks-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-soft);
    }

    .btn-save-marks {
        padding: 10px 20px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s ease;
        white-space: nowrap;
    }
/* Confirmation Popup Styles */
.confirmation-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    z-index: 1000000;
}

.confirmation-popup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 24px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    border: 1px solid var(--border);
    z-index: 1000001;
    width: 420px;
    max-width: 90%;
    overflow: hidden;
}

#confirmFailBtn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(220, 38, 38, 0.3);
}

#cancelFailBtn:hover {
    background: #f1f5f9;
    border-color: #94a3b8;
}

/* Marks input warning styling */
.marks-input.warning {
    border-color: #f59e0b !important;
    border-width: 2px !important;
    background-color: #fffbeb;
}

.marks-input.success {
    border-color: #10b981 !important;
    border-width: 2px !important;
    background-color: #f0fdf4;
}

.marks-input.error {
    border-color: #dc2626 !important;
    border-width: 2px !important;
    background-color: #fee2e2;
}

.marks-error {
    color: #dc2626;
    font-size: 12px;
    margin-top: 5px;
    font-weight: 500;
    animation: slideDown 0.3s ease;
}

.marks-warning {
    color: #f59e0b;
    font-size: 12px;
    margin-top: 5px;
    font-weight: 500;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
    .btn-save-marks:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-save-marks:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .marks-display {
        margin-top: 10px;
        font-size: 13px;
        color: #10b981;
        animation: fadeIn 0.3s ease;
    }
    .marks-error {
        animation: fadeIn 0.3s ease;
    }
    .marks-input.error {
        border-color: #dc2626 !important;
        border-width: 2px !important;
        background-color: #fef2f2;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 992px) {
        .timeline {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .timeline::before {
            display: none;
        }
        
        .timeline-item {
            flex: 0 0 calc(50% - 10px);
            min-width: 200px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .modal-info-grid {
            grid-template-columns: 1fr;
        }

        .round-details {
            grid-template-columns: 1fr;
        }

        .documents-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .container {
            padding: 16px;
        }

        .lead-card-header {
            flex-direction: column;
        }

        .timeline-item {
            flex: 0 0 100%;
        }

        .followup-input-group {
            flex-direction: column;
        }

        .btn-update {
            width: 100%;
            justify-content: center;
        }

        .modal-actions {
            flex-direction: column;
        }
    }
</style>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container-fluid">
        <!-- Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-title">
                    <h1>Applicant Journey</h1>
                    <p><i class="fas fa-eye"></i> Complete view of {{ $lead->name ?? 'candidate' }}'s application journey</p>
                </div>
                <div class="header-actions">
                    <button class="btn-back" onclick="window.location.href='{{ url()->previous() }}'">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </button>
                </div>
            </div>
        </div>

        @if(isset($lead) && $lead)
        <!-- Main Card -->
        <div class="lead-card">
            <!-- Lead Header -->
            <div class="lead-card-header">
                <div class="lead-info">
                    <h2>{{ $lead->name }}</h2>
                    <div class="lead-meta">
                        <div class="lead-meta-item">
                            <i class="fas fa-id-card"></i>
                            {{ $lead->lead_id }}
                        </div>
                        <div class="lead-meta-item">
                            <i class="fas fa-calendar"></i>
                            Applied: {{ \Carbon\Carbon::parse($lead->created_at)->format('d M Y') }}
                        </div>
                        <div class="lead-meta-item">
                            <i class="fas fa-phone"></i>
                            {{ $lead->phone_no }}
                        </div>
                        <div class="lead-meta-item">
                            <i class="fas fa-envelope"></i>
                            {{ $lead->email }}
                        </div>
                    </div>
                </div>
                
                <div class="status-container">
                    <div class="status-badge {{ $lead->lead_status ?? 'new' }}" onclick="toggleStatusDropdown()">
                        <i class="fas {{ $lead->lead_status == 'hot' ? 'fa-fire' : ($lead->lead_status == 'warm' ? 'fa-sun' : ($lead->lead_status == 'cold' ? 'fa-snowflake' : ($lead->lead_status == 'selected' ? 'fa-check-circle' : ($lead->lead_status == 'rejected' ? 'fa-times-circle' : 'fa-circle')))) }}"></i>
                        {{ ucfirst($lead->lead_status ?? 'New') }}
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    
                    <div class="status-dropdown" id="statusDropdown">
                        <div class="status-option" onclick="updateLeadStatus('hot')">
                            <i class="fas fa-fire" style="color: #dc2626;"></i> Hot
                        </div>
                        <div class="status-option" onclick="updateLeadStatus('warm')">
                            <i class="fas fa-sun" style="color: #d97706;"></i> Warm
                        </div>
                        <div class="status-option" onclick="updateLeadStatus('cold')">
                            <i class="fas fa-snowflake" style="color: #2563eb;"></i> Cold
                        </div>
                        <div class="status-option" onclick="updateLeadStatus('selected')">
                            <i class="fas fa-check-circle" style="color: #059669;"></i> Selected
                        </div>
                        <div class="status-option" onclick="updateLeadStatus('rejected')">
                            <i class="fas fa-times-circle" style="color: #dc2626;"></i> Rejected
                        </div>
                    </div>
                </div>
            </div>

            @php
                // Get statuses from interviewRegistration with default values
                $applicationStatus = $interviewRegistration->application_status ?? 'pending';
                $interviewStatus = $interviewRegistration->interview_status ?? 'pending';
                
                // IMPORTANT: Get selection status DIRECTLY from database - don't override with lead status
                // This ensures it shows whatever is actually in the database
                $selectionStatus = $interviewRegistration->selection_status ?? 'pending';
                
                // Get onboarding status
                $onboardingStatus = $interviewRegistration->onboarding_status ?? 'pending';
                
                // For lead status badge display only - NOT for overriding selection status
                $leadStatus = $lead->lead_status ?? 'new';
                
                $stepStatuses = [
                    'application' => $applicationStatus,
                    'interview' => $interviewStatus,
                    'selection' => $selectionStatus, // This now comes ONLY from database
                    'onboarding' => $onboardingStatus
                ];
                
                $stepIcons = [
                    'application' => 'fa-file-alt',
                    'interview' => 'fa-users',
                    'selection' => 'fa-check-double',
                    'onboarding' => 'fa-user-graduate'
                ];
                
                $stepTitles = [
                    'application' => 'Application',
                    'interview' => 'Interview',
                    'selection' => 'Selection',
                    'onboarding' => 'Onboarding'
                ];

                // Interview rounds data
                $totalRounds = $interviewRounds->count();
                $completedRounds = $interviewRounds->where('status', 'completed')->count();
            @endphp

            <!-- Journey Timeline - 4 Main Steps with Status Update -->

<!-- Journey Timeline - 4 Main Steps with Status Update -->
<div class="timeline-section">
    <h3 class="section-title">
        <i class="fas fa-road"></i>
        Application Journey
        <span style="font-size: 14px; font-weight: 400; color: var(--gray); margin-left: 10px;">
            Click <i class="fas fa-pencil-alt" style="color: var(--primary);"></i> to update status
        </span>
    </h3>

    <div class="timeline">
        @foreach(['application', 'interview', 'selection', 'onboarding'] as $index => $step)
        @php
            $status = $stepStatuses[$step];
            $icon = $stepIcons[$step];
            $title = $stepTitles[$step];
            $fieldName = $step . '_status';
        @endphp
        <div class="timeline-item">
            <div class="timeline-dot {{ $status }}"></div>
            <div class="timeline-content {{ $status }}">
                <div class="timeline-header">
                    <div class="timeline-icon {{ $status }}">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    <span class="timeline-step-number">Step {{ $index + 1 }}</span>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <div class="timeline-title">{{ $title }}</div>
                    
                    <!-- View Detail Button with Eye Icon and Text Side by Side -->
                    <button onclick="openStepModal('{{ $step }}')" 
                            style="display: flex; flex-direction:column-reverse;align-items: center; gap: 2px; cursor: pointer; padding: 5px 19px; border: none; border-radius: 8px; background: var(--primary-soft); transition: all 0.2s ease; font-family: inherit;"
                            onmouseover="this.style.background='var(--primary)'; this.querySelector('i').style.color='white'; this.querySelector('span').style.color='white';"
                            onmouseout="this.style.background='var(--primary-soft)'; this.querySelector('i').style.color='var(--primary)'; this.querySelector('span').style.color='var(--primary)';">
                             <span style="font-size: 11px; font-weight: 600; color: var(--primary); transition: all 0.2s ease;">view </span>
                        <i class="fas fa-eye" style="font-size: 12px; color: var(--primary); transition: all 0.2s ease;"></i>
                       
                    </button>
                </div>
                
                <!-- Status Badge with Pencil Icon - Click Pencil to Open Dropdown -->
                <div class="status-container" style="position: relative; width: 100%;">
                    <!-- Status Badge -->
                    <div class="timeline-badge {{ $status }}" 
                         style="display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 8px 15px;">
                        <span>
                            @if($status == 'completed') 
                                <i class="fas fa-check-circle"></i> Completed
                            @elseif($status == 'in_progress') 
                                <i class="fas fa-spinner fa-spin"></i> In Progress
                            @elseif($status == 'pending') 
                                <i class="fas fa-clock"></i> Pending
                            @elseif($status == 'rejected') 
                                <i class="fas fa-times-circle"></i> Rejected
                            @else
                                {{ ucfirst($status) }}
                            @endif
                        </span>
                        
                        <!-- Pencil Icon - Click to Open Dropdown -->
                        <div style="position: relative;">
                            <i class="fas fa-pencil-alt" 
                               style="cursor: pointer; font-size: 12px; padding: 6px; border-radius: 50%; background: rgba(255,255,255,0.3); transition: all 0.2s ease;"
                               onmouseover="this.style.background='rgba(255,255,255,0.6)'; this.style.transform='scale(1.1)';"
                               onmouseout="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='scale(1)';"
                               onclick="event.stopPropagation(); toggleStepStatusDropdown('{{ $step }}', event)"
                               title="Update Status"></i>
                            
                            <!-- Status Dropdown for this step -->
                            <div class="status-dropdown" id="dropdown-{{ $step }}" 
                                 style="position: absolute; top: 100%; right: 0; margin-top: 8px; background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); border: 1px solid var(--border); min-width: 180px; z-index: 1000; display: none; overflow: hidden;">
                                <div class="status-option" onclick="updateJourneyStatus('{{ $fieldName }}', 'pending', event)">
                                    <i class="fas fa-clock" style="color: #94a3b8; width: 20px;"></i> Pending
                                </div>
                                <div class="status-option" onclick="updateJourneyStatus('{{ $fieldName }}', 'in_progress', event)">
                                    <i class="fas fa-spinner" style="color: #3b82f6; width: 20px;"></i> In Progress
                                </div>
                                <div class="status-option" onclick="updateJourneyStatus('{{ $fieldName }}', 'completed', event)">
                                    <i class="fas fa-check-circle" style="color: #10b981; width: 20px;"></i> Completed
                                </div>
                                <div class="status-option" onclick="updateJourneyStatus('{{ $fieldName }}', 'rejected', event)">
                                    <i class="fas fa-times-circle" style="color: #ef4444; width: 20px;"></i> Rejected
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step-specific info -->
                @if($step == 'application' && isset($lead->created_at))
                <div class="timeline-info" style="margin-top: 12px;">
                    <div class="timeline-info-item">
                        <i class="fas fa-calendar"></i>
                        <span>Applied: {{ \Carbon\Carbon::parse($lead->created_at)->format('d M Y') }}</span>
                    </div>
                </div>
                @endif
                
                @if($step == 'interview' && isset($totalRounds) && $totalRounds > 0)
                <div class="timeline-info" style="margin-top: 12px;">
                    <div class="timeline-info-item">
                        <i class="fas fa-layer-group"></i>
                        <span>{{ $completedRounds }}/{{ $totalRounds }} Rounds Completed</span>
                    </div>
                </div>
                @endif
                
                @if($step == 'selection' && isset($lead->lead_status))
                <div class="timeline-info" style="margin-top: 12px;">
                    <div class="timeline-info-item">
                        <i class="fas fa-tag"></i>
                        <span>Lead Status: {{ ucfirst($lead->lead_status) }}</span>
                    </div>
                </div>
                @endif
                
                @if($step == 'onboarding' && isset($interviewConfig) && $interviewConfig && isset($interviewConfig->joining_date))
                <div class="timeline-info" style="margin-top: 12px;">
                    <div class="timeline-info-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>Joining: {{ \Carbon\Carbon::parse($interviewConfig->joining_date)->format('d M Y') }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

        <div class="info-section">
            <h3 class="section-title" style="margin-bottom: 20px;">
                <i class="fas fa-user-circle"></i>
                Candidate Information
            </h3>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Application ID</div>
                        <div class="info-value">{{ $lead->lead_id }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">{{ $lead->name }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $lead->email }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Phone</div>
                        <div class="info-value">{{ $lead->phone_no }}</div>
                    </div>
                </div>

                @if($interviewRegistration)
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Applying For</div>
                        <div class="info-value">{{ $interviewRegistration->applying_for ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Profile</div>
                        <div class="info-value">{{ ucfirst($interviewRegistration->applying_for_profile ?? 'N/A') }}</div>
                    </div>
                </div>

                <!-- Current Organization -->
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Current Organization</div>
                        <div class="info-value">{{ $interviewRegistration->organization ?? 'N/A' }}</div>
                    </div>
                </div>

                <!-- Current Profession -->
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Current Profession</div>
                        <div class="info-value">{{ ucfirst($interviewRegistration->profession ?? 'N/A') }}</div>
                    </div>
                </div>

                <!-- Experience -->
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Total Experience</div>
                        <div class="info-value">
                            @if($interviewRegistration->experience_in_year || $interviewRegistration->experience_in_month)
                                {{ $interviewRegistration->experience_in_year ?? 0 }} years 
                                {{ $interviewRegistration->experience_in_month ?? 0 }} months
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Qualification -->
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Qualification</div>
                        <div class="info-value">{{ strtoupper($interviewRegistration->qualification ?? 'N/A') }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        </div>

        <!-- APPLICATION MODAL -->
        <div class="modal-overlay" id="applicationModal">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <i class="fas fa-file-alt"></i>
                        Application Details
                    </div>
                    <button class="modal-close" onclick="closeModal('application')">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Application Status -->
                    <div class="modal-section">
                        <div class="modal-section-title">
                            <i class="fas fa-flag"></i>
                            Application Status
                        </div>
                        <div class="modal-info-grid">
                            <div class="modal-info-item">
                                <div class="modal-info-label"><i class="fas fa-check-circle"></i> Current Status</div>
                                <div class="modal-info-value">
                                    <span class="status-chip {{ $applicationStatus }}">
                                        @if($applicationStatus == 'completed')
                                            <i class="fas fa-check-circle"></i> Completed
                                        @elseif($applicationStatus == 'in_progress')
                                            <i class="fas fa-spinner"></i> In Progress
                                        @elseif($applicationStatus == 'rejected')
                                            <i class="fas fa-times-circle"></i> Rejected
                                        @else
                                            <i class="fas fa-clock"></i> Pending
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="modal-section">
                        <div class="modal-section-title">
                            <i class="fas fa-user-circle"></i>
                            Personal Information
                        </div>
                        <div class="modal-info-grid">
                            <div class="modal-info-item">
                                <div class="modal-info-label"><i class="fas fa-user"></i> Full Name</div>
                                <div class="modal-info-value">{{ $lead->name }}</div>
                            </div>
                            <div class="modal-info-item">
                                <div class="modal-info-label"><i class="fas fa-envelope"></i> Email</div>
                                <div class="modal-info-value">{{ $lead->email }}</div>
                            </div>
                            <div class="modal-info-item">
                                <div class="modal-info-label"><i class="fas fa-phone"></i> Phone</div>
                                <div class="modal-info-value">{{ $lead->phone_no }}</div>
                            </div>
                            @if($interviewRegistration && $interviewRegistration->dob) 
                            <div class="modal-info-item">
                                <div class="modal-info-label"><i class="fas fa-calendar-alt"></i> Date of Birth</div>
                                <div class="modal-info-value">{{ \Carbon\Carbon::parse($interviewRegistration->dob)->format('d M Y') }}</div>
                            </div>
                            @endif
                            @if($interviewRegistration && $interviewRegistration->marital_status)
                            <div class="modal-info-item">
                                <div class="modal-info-label"><i class="fas fa-heart"></i> Marital Status</div>
                                <div class="modal-info-value">{{ ucfirst($interviewRegistration->marital_status) }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Professional Information -->
                    <div class="modal-section">
                        <div class="modal-section-title">
                            <i class="fas fa-briefcase"></i>
                            Professional Information
                        </div>
                        <div class="modal-info-grid">
                            @if($interviewRegistration)
                            <div class="modal-info-item">
                                <div class="modal-info-label"><i class="fas fa-briefcase"></i> Profession</div>
                                <div class="modal-info-value">{{ ucfirst($interviewRegistration->profession ?? 'N/A') }}</div>
                            </div>
                            <div class="modal-info-item">
                                <div class="modal-info-label"><i class="fas fa-building"></i> Organization</div>
                                <div class="modal-info-value">{{ $interviewRegistration->organization ?? 'N/A' }}</div>
                            </div>
                            <div class="modal-info-item">
                                <div class="modal-info-label"><i class="fas fa-graduation-cap"></i> Qualification</div>
                                <div class="modal-info-value">{{ strtoupper($interviewRegistration->qualification ?? 'N/A') }}</div>
                            </div>
                            <div class="modal-info-item">
                                <div class="modal-info-label"><i class="fas fa-clock"></i> Experience</div>
                                <div class="modal-info-value">
                                    @if($interviewRegistration->experience_in_year || $interviewRegistration->experience_in_month)
                                        {{ $interviewRegistration->experience_in_year ?? 0 }} years {{ $interviewRegistration->experience_in_month ?? 0 }} months
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            @endif
                            <div class="modal-info-item">
                                <div class="modal-info-label"><i class="fas fa-tag"></i> Applicant Type</div>
                                <div class="modal-info-value">{{ ucfirst($lead->applicant_type) }}</div>
                            </div>
                            <div class="modal-info-item">
                                <div class="modal-info-label"><i class="fas fa-edit"></i> Registration Mode</div>
                                <div class="modal-info-value">{{ ucfirst($lead->registration_mode) }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Skills -->
                    @if($interviewRegistration && !empty($interviewRegistration->skills))
                    @php
                        $skills = json_decode($interviewRegistration->skills, true);
                    @endphp
                    @if(!empty($skills) && is_array($skills))
                    <div class="modal-section">
                        <div class="modal-section-title">
                            <i class="fas fa-code"></i>
                            Skills
                        </div>
                        <div class="skills-container">
                            @foreach($skills as $skill)
                                @if(is_array($skill) && isset($skill['name']))
                                    <span class="skill-tag {{ $skill['level'] ?? 'beginner' }}">
                                        {{ $skill['name'] }}
                                        @if(isset($skill['level']))
                                            ({{ ucfirst($skill['level']) }})
                                        @endif
                                    </span>
                                @elseif(is_string($skill))
                                    <span class="skill-tag">{{ $skill }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @endif

                    <!-- Application Documents -->
                    @if($interviewConfig && !empty($interviewConfig->application_documents))
                    @php
                        $appDocs = json_decode($interviewConfig->application_documents, true);
                    @endphp
                    @if(!empty($appDocs) && is_array($appDocs))
                    <div class="modal-section">
                        <div class="modal-section-title">
                            <i class="fas fa-file-alt"></i>
                            Required Documents
                        </div>
                        <div class="documents-grid">
                            @foreach($appDocs as $doc)
                                @if(is_array($doc) && isset($doc['name']))
                                <div class="document-item">
                                    <i class="fas fa-file-pdf"></i>
                                    <span>{{ $doc['name'] }}</span>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @endif

                    <!-- Resume -->
                    @if($interviewRegistration && $interviewRegistration->resume_path)
                    <div class="modal-section">
                        <div class="modal-section-title">
                            <i class="fas fa-file-pdf"></i>
                            Uploaded Resume
                        </div>
                        <a href="{{ asset($interviewRegistration->resume_path) }}" target="_blank" style="text-decoration: none;">
                            <div class="document-item" style="justify-content: center;">
                                <i class="fas fa-download"></i>
                                <span>Download Resume</span>
                            </div>
                        </a>
                    </div>
                    @endif

                    <div class="modal-actions">
                        <button class="btn-modal btn-modal-secondary" onclick="closeModal('application')">
                            <i class="fas fa-times"></i> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

    <!-- INTERVIEW MODAL -->
    <div class="modal-overlay" id="interviewModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-users"></i>
                    Interview Details
                </div>
                <button class="modal-close" onclick="closeModal('interview')">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Interview Status -->
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-flag"></i>
                        Interview Status
                    </div>
                    <div class="modal-info-grid">
                        @if($interviewRegistration)
                        <div class="modal-info-item">
                            <div class="modal-info-label"><i class="fas fa-check-circle"></i> Overall Interview Status</div>
                            <div class="modal-info-value">
                                <span class="status-chip {{ $interviewRegistration->interview_status ?? 'pending' }}">
                                    @if(($interviewRegistration->interview_status ?? 'pending') == 'in_progress')
                                        <i class="fas fa-spinner fa-spin"></i> In Progress
                                    @elseif(($interviewRegistration->interview_status ?? 'pending') == 'completed')
                                        <i class="fas fa-check-circle"></i> Completed
                                    @elseif(($interviewRegistration->interview_status ?? 'pending') == 'rejected')
                                        <i class="fas fa-times-circle"></i> Rejected
                                    @else
                                        <i class="fas fa-clock"></i> Pending
                                    @endif
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Interview Configuration -->
                @if($interviewConfig)
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-cog"></i>
                        Interview Configuration
                    </div>
                    <div class="modal-info-grid">
                        <div class="modal-info-item">
                            <div class="modal-info-label"><i class="fas fa-laptop"></i> Interview Mode</div>
                            <div class="modal-info-value">{{ ucfirst($interviewConfig->interview_mode ?? 'online') }}</div>
                        </div>
                        <div class="modal-info-item">
                            <div class="modal-info-label"><i class="fas fa-calendar"></i> Application Period</div>
                            <div class="modal-info-value">
                                @if($interviewConfig->application_start_date && $interviewConfig->application_end_date)
                                    {{ \Carbon\Carbon::parse($interviewConfig->application_start_date)->format('d M') }} - 
                                    {{ \Carbon\Carbon::parse($interviewConfig->application_end_date)->format('d M Y') }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Interview Rounds with Status Update and Marks/Stars -->
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-comments"></i>
                        Interview Rounds
                    </div>
                    
                    @php
                        // Use lead_id (the formatted string) to query round_status
                        $roundStatuses = \App\Models\RoundStatus::where('lead_id', $lead->lead_id)
                            ->orderBy('id')
                            ->get();
                    @endphp
                    
                    @if($roundStatuses->count() > 0)
                        <div class="rounds-list">
                            @foreach($roundStatuses as $index => $round)
                                @php
                                    $currentStatus = strtolower($round->round_status ?? 'pending');
                                    
                                    $statusColor = '#94a3b8';
                                    $statusIcon = 'fa-clock';
                                    $statusDisplay = 'Pending';
                                    
                                    if ($currentStatus == 'inprogress') {
                                        $statusColor = '#3b82f6';
                                        $statusIcon = 'fa-play-circle';
                                        $statusDisplay = 'In Progress';
                                    } elseif ($currentStatus == 'passed') {
                                        $statusColor = '#10b981';
                                        $statusIcon = 'fa-check-circle';
                                        $statusDisplay = 'Passed';
                                    } elseif ($currentStatus == 'failed') {
                                        $statusColor = '#ef4444';
                                        $statusIcon = 'fa-times-circle';
                                        $statusDisplay = 'Failed';
                                    }
                                    
                                    // Get interview round details from configuration if available
                                    $interviewRound = null;
                                    if ($interviewConfig && $interviewConfig->interviewRounds) {
                                        $interviewRound = $interviewConfig->interviewRounds->where('name', $round->name)->first();
                                    }
                                    
                                    $isTestRound = $round->type == 'test' || ($interviewRound && $interviewRound->type == 'test');
                                @endphp
                                
                                <div class="round-item" id="round-{{ $round->id }}" data-round-type="{{ $round->type }}">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <span style="font-size: 16px; font-weight: 600;">Round {{ $index + 1 }}</span>
                                            @if($round->name)
                                                <span style="color: #64748b; font-size: 13px;">({{ $round->name }})</span>
                                            @endif
                                            @if($round->type)
                                                <span style="background: #e2e8f0; padding: 4px 8px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                                    {{ ucfirst($round->type) }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <select class="round-status-select" id="status-{{ $round->id }}" 
                                                    style="padding: 8px 16px; border-radius: 30px; border: 2px solid {{ $statusColor }}; background: white; font-weight: 600; color: {{ $statusColor }}; cursor: pointer;"
                                                    onchange="updateRoundStatus({{ $round->id }}, this.value)">
                                                <option value="pending" {{ $currentStatus == 'pending' ? 'selected' : '' }} style="color: #94a3b8;">⏳ Pending</option>
                                                <option value="inprogress" {{ $currentStatus == 'inprogress' ? 'selected' : '' }} style="color: #3b82f6;">▶️ In Progress</option>
                                                <option value="passed" {{ $currentStatus == 'passed' ? 'selected' : '' }} style="color: #10b981;">✅ Passed</option>
                                                <option value="failed" {{ $currentStatus == 'failed' ? 'selected' : '' }} style="color: #ef4444;">❌ Failed</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px; background: #f8fafc; padding: 12px; border-radius: 8px;">
                                        @if($round->round_date)
                                        <div>
                                            <span style="font-size: 12px; color: #64748b; display: block;">Date</span>
                                            <span style="font-weight: 500;">{{ \Carbon\Carbon::parse($round->round_date)->format('d M Y') }}</span>
                                        </div>
                                        @endif
                                        
                                        @if($round->start_time)
                                        <div>
                                            <span style="font-size: 12px; color: #64748b; display: block;">Start Time</span>
                                            <span style="font-weight: 500;">{{ \Carbon\Carbon::parse($round->start_time)->format('h:i A') }}</span>
                                        </div>
                                        @endif
                                        
                                        @if($round->end_time)
                                        <div>
                                            <span style="font-size: 12px; color: #64748b; display: block;">End Time</span>
                                            <span style="font-weight: 500;">{{ \Carbon\Carbon::parse($round->end_time)->format('h:i A') }}</span>
                                        </div>
                                        @endif
                                        
                                        @if($round->duration)
                                        <div>
                                            <span style="font-size: 12px; color: #64748b; display: block;">Duration</span>
                                            <span style="font-weight: 500;">{{ $round->duration }} min</span>
                                        </div>
                                        @endif
                                        
                                        @if($round->type)
                                        <div>
                                            <span style="font-size: 12px; color: #64748b; display: block;">Type</span>
                                            <span style="font-weight: 500;">{{ ucfirst($round->type) }}</span>
                                        </div>
                                        @endif
                                        
                                        @if($round->panel)
                                        <div>
                                            <span style="font-size: 12px; color: #64748b; display: block;">Panel</span>
                                            <span style="font-weight: 500;">Panel {{ $round->panel }}</span>
                                        </div>
                                        @endif

                                        <!-- Show Total Marks and Passing Marks for Test Rounds -->
                                        @if($isTestRound)
                                            @php
                                                $totalMarks = $round->total_marks ?? ($interviewRound->total_marks ?? 100);
                                                $passingMarks = $round->passing_marks ?? ($interviewRound->passing_marks ?? 40);
                                            @endphp
                                            <div>
                                                <span style="font-size: 12px; color: #64748b; display: block;">Total Marks</span>
                                                <span style="font-weight: 500;">{{ $totalMarks }}</span>
                                            </div>
                                            <div>
                                                <span style="font-size: 12px; color: #64748b; display: block;">Passing Marks</span>
                                                <span style="font-weight: 500;">{{ $passingMarks }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Marks/Rating Input Section - Different UI based on round type -->
                                    <div style="margin-top: 15px; padding: 15px; background: #f1f5f9; border-radius: 8px; border-left: 4px solid {{ $isTestRound ? '#f59e0b' : '#3b82f6' }};">
                                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                            @if($isTestRound)
                                                <i class="fas fa-star" style="color: #f59e0b;"></i>
                                                <span style="font-weight: 600;">Marks Obtained</span>
                                                <span style="background: #f59e0b; color: white; padding: 2px 8px; border-radius: 20px; font-size: 11px;">Test Round</span>
                                            @else
                                                <i class="fas fa-star" style="color: #3b82f6;"></i>
                                                <span style="font-weight: 600;">Rating (Stars)</span>
                                                <span style="background: #3b82f6; color: white; padding: 2px 8px; border-radius: 20px; font-size: 11px;">Interview Round</span>
                                            @endif
                                        </div>
                                        
                                        @if($isTestRound)
                                            <!-- Test Round - Marks Input -->
                                            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                                                <div style="flex: 1; min-width: 200px;">
                                                    <input type="number" 
                                                        id="marks-{{ $round->id }}" 
                                                        class="marks-input" 
                                                        value="{{ $round->marks ?? '' }}" 
                                                        placeholder="Enter marks obtained"
                                                        min="0"
                                                        max="{{ $round->total_marks ?? ($interviewRound->total_marks ?? 100) }}"
                                                        step="0.01"
                                                        style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                                                </div>
                                                <button onclick="saveRoundMarks({{ $round->id }}, 'marks')" 
                                                        class="btn-save-marks"
                                                        style="padding: 10px 20px; background: #f59e0b; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                                                    <i class="fas fa-save"></i> Save Marks
                                                </button>
                                            </div>
                                        @else
                                            <!-- Non-Test Round - Star Rating -->
                                            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                                                <div class="star-rating" id="star-rating-{{ $round->id }}" style="display: flex; gap: 5px; font-size: 24px;">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star" 
                                                        style="color: {{ $round->marks && $i <= $round->marks ? '#f59e0b' : '#cbd5e1' }}; cursor: pointer; transition: color 0.2s;"
                                                        onmouseover="hoverStar({{ $round->id }}, {{ $i }})"
                                                        onmouseout="resetStar({{ $round->id }})"
                                                        onclick="setStar({{ $round->id }}, {{ $i }})"></i>
                                                    @endfor
                                                </div>
                                                <span id="rating-value-{{ $round->id }}" style="font-weight: 600; color: #3b82f6;">
                                                    {{ $round->marks ? $round->marks . '/5' : 'Not rated' }}
                                                </span>
                                                <button onclick="saveRoundRating({{ $round->id }})" 
                                                        class="btn-save-marks"
                                                        style="padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                                                    <i class="fas fa-save"></i> Save Rating
                                                </button>
                                            </div>
                                            
                                            <!-- Hidden input to store selected rating -->
                                            <input type="hidden" id="rating-{{ $round->id }}" value="{{ $round->marks ?? 0 }}">
                                        @endif
                                        
                                        @if($round->marks)
                                            @if($isTestRound)
                                                <div style="margin-top: 10px; font-size: 13px; color: #10b981;">
                                                    <i class="fas fa-check-circle"></i> Current marks: {{ $round->marks }}/{{ $round->total_marks ?? ($interviewRound->total_marks ?? 100) }}
                                                </div>
                                            @else
                                                <div style="margin-top: 10px; font-size: 13px; color: #10b981;">
                                                    <i class="fas fa-check-circle"></i> Current rating: {{ $round->marks }}/5 stars
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    
                                    @if($round->desc)
                                    <div style="padding: 12px; background: #f8fafc; border-radius: 8px; border-left: 4px solid #3b82f6; margin-top: 15px;">
                                        <span style="font-size: 12px; color: #64748b; display: block; margin-bottom: 4px;">Description</span>
                                        <p style="margin: 0;">{{ $round->desc }}</p>
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Check if there are interview rounds configured but not initialized --}}
                        @php
                            $hasInterviewRounds = $interviewConfig && 
                                                $interviewConfig->interviewRounds && 
                                                $interviewConfig->interviewRounds->count() > 0;
                        @endphp
                        
                        @if($hasInterviewRounds)
                            <div style="text-align: center; padding: 40px 20px; background: #f8fafc; border-radius: 12px;">
                                <i class="fas fa-sync-alt fa-spin" style="font-size: 3rem; color: #3b82f6; margin-bottom: 15px;"></i>
                                <h3 style="color: #1e293b; margin-bottom: 10px;">Initializing Interview Rounds</h3>
                                <p style="color: #64748b;">Interview rounds are being set up. Please refresh the page in a moment.</p>
                                <button onclick="location.reload()" style="margin-top: 15px; padding: 8px 20px; background: #3b82f6; color: white; border: none; border-radius: 8px; cursor: pointer;">
                                    <i class="fas fa-sync-alt"></i> Refresh Now
                                </button>
                            </div>
                        @else
                            <div style="text-align: center; padding: 40px 20px; background: #f8fafc; border-radius: 12px;">
                                <i class="fas fa-comments" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 15px;"></i>
                                <h3 style="color: #1e293b; margin-bottom: 10px;">No Interview Rounds</h3>
                                <p style="color: #64748b;">No interview rounds have been configured for this position yet.</p>
                            </div>
                        @endif
                    @endif
                </div>

                <div class="modal-actions">
                    <button class="btn-modal btn-modal-secondary" onclick="closeModal('interview')">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SELECTION MODAL -->
    <div class="modal-overlay" id="selectionModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-check-double"></i>
                    Selection Details
                </div>
                <button class="modal-close" onclick="closeModal('selection')">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Selection Status -->
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-flag"></i>
                        Selection Status
                    </div>
                    <div class="modal-info-grid">
                        <div class="modal-info-item">
                            <div class="modal-info-label"><i class="fas fa-tag"></i> Selection Status</div>
                            <div class="modal-info-value">
                                <span class="status-chip {{ $selectionStatus }}">
                                    @if($selectionStatus == 'completed')
                                        <i class="fas fa-check-circle"></i> Selected
                                    @elseif($selectionStatus == 'in_progress')
                                        <i class="fas fa-spinner"></i> In Progress
                                    @elseif($selectionStatus == 'rejected')
                                        <i class="fas fa-times-circle"></i> Rejected
                                    @else
                                        <i class="fas fa-clock"></i> Pending
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="modal-info-item">
                            <div class="modal-info-label"><i class="fas fa-tag"></i> Lead Status</div>
                            <div class="modal-info-value">
                                <span class="status-chip {{ $lead->lead_status }}">
                                    {{ ucfirst($lead->lead_status ?? 'pending') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

              

                <div class="modal-actions">
                    <button class="btn-modal btn-modal-secondary" onclick="closeModal('selection')">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ONBOARDING MODAL -->
    <div class="modal-overlay" id="onboardingModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-user-graduate"></i>
                    Onboarding Details
                </div>
                <button class="modal-close" onclick="closeModal('onboarding')">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Onboarding Status -->
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-flag"></i>
                        Onboarding Status
                    </div>
                    <div class="modal-info-grid">
                        <div class="modal-info-item">
                            <div class="modal-info-label"><i class="fas fa-tag"></i> Onboarding Status</div>
                            <div class="modal-info-value">
                                <span class="status-chip {{ $onboardingStatus }}">
                                    @if($onboardingStatus == 'completed')
                                        <i class="fas fa-check-circle"></i> Completed
                                    @elseif($onboardingStatus == 'in_progress')
                                        <i class="fas fa-spinner"></i> In Progress
                                    @elseif($onboardingStatus == 'rejected')
                                        <i class="fas fa-times-circle"></i> Rejected
                                    @else
                                        <i class="fas fa-clock"></i> Pending
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($interviewConfig)
                <!-- Joining Information -->
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-calendar-alt"></i>
                        Joining Information
                    </div>
                    <div class="modal-info-grid">
                        @if($interviewConfig->joining_date)
                        <div class="modal-info-item">
                            <div class="modal-info-label"><i class="fas fa-calendar-check"></i> Joining Date</div>
                            <div class="modal-info-value highlight">{{ \Carbon\Carbon::parse($interviewConfig->joining_date)->format('l, d M Y') }}</div>
                        </div>
                        @endif
                        
                        @if($interviewConfig->reporting_time)
                        <div class="modal-info-item">
                            <div class="modal-info-label"><i class="fas fa-clock"></i> Reporting Time</div>
                            <div class="modal-info-value">{{ \Carbon\Carbon::parse($interviewConfig->reporting_time)->format('h:i A') }}</div>
                        </div>
                        @endif
                        
                        @if($interviewConfig->reporting_venue)
                        <div class="modal-info-item">
                            <div class="modal-info-label"><i class="fas fa-map-marker-alt"></i> Reporting Venue</div>
                            <div class="modal-info-value">{{ $interviewConfig->reporting_venue }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Induction Program -->
                @if($interviewConfig->induction_program)
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-chalkboard-teacher"></i>
                        Induction Program
                    </div>
                    <div class="modal-info-grid">
                        <div class="modal-info-item">
                            <div class="modal-info-label"><i class="fas fa-tag"></i> Program</div>
                            <div class="modal-info-value">{{ $interviewConfig->induction_program }}</div>
                        </div>
                        @if($interviewConfig->induction_days)
                        <div class="modal-info-item">
                            <div class="modal-info-label"><i class="fas fa-calendar-alt"></i> Duration</div>
                            <div class="modal-info-value">{{ $interviewConfig->induction_days }} days</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Training Period -->
                @if($interviewConfig->training_months)
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-clock"></i>
                        Training Period
                    </div>
                    <div class="modal-info-item">
                        <div class="modal-info-label"><i class="fas fa-hourglass-half"></i> Duration</div>
                        <div class="modal-info-value">{{ $interviewConfig->training_months }} months</div>
                    </div>
                </div>
                @endif

                <!-- Onboarding Documents -->
                @if(!empty($interviewConfig->onboarding_documents))
                @php
                    $onboardDocs = json_decode($interviewConfig->onboarding_documents, true);
                @endphp
                @if(!empty($onboardDocs) && is_array($onboardDocs))
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-file-contract"></i>
                        Required Onboarding Documents
                    </div>
                    <div class="documents-grid">
                        @foreach($onboardDocs as $doc)
                            @if(is_array($doc) && isset($doc['name']))
                            <div class="document-item">
                                <i class="fas fa-file-pdf"></i>
                                <span>{{ $doc['name'] }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
                @endif

                <!-- Offer Letter -->
                @if($interviewConfig->offer_letter_generation)
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-file-signature"></i>
                        Offer Letter
                    </div>
                    <div class="modal-info-grid">
                        <div class="modal-info-item">
                            <div class="modal-info-label"><i class="fas fa-cog"></i> Generation</div>
                            <div class="modal-info-value">{{ ucfirst($interviewConfig->offer_letter_generation) }}</div>
                        </div>
                        @if($interviewConfig->acceptance_deadline_days)
                        <div class="modal-info-item">
                            <div class="modal-info-label"><i class="fas fa-hourglass-end"></i> Acceptance Deadline</div>
                            <div class="modal-info-value">{{ $interviewConfig->acceptance_deadline_days }} days</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                @else
                <div style="text-align: center; padding: 40px 20px;">
                    <i class="fas fa-user-graduate" style="font-size: 3rem; color: var(--border); margin-bottom: 15px;"></i>
                    <h3 style="color: var(--dark); margin-bottom: 10px;">No Onboarding Data</h3>
                    <p style="color: var(--gray);">Onboarding details will appear here once the candidate is selected.</p>
                </div>
                @endif

                <div class="modal-actions">
                    <button class="btn-modal btn-modal-secondary" onclick="closeModal('onboarding')">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    @else
    <!-- Empty State -->
    <div class="lead-card" style="text-align: center; padding: 80px 20px;">
        <i class="fas fa-user-slash" style="font-size: 64px; color: var(--border); margin-bottom: 20px;"></i>
        <h2 style="color: var(--dark); margin-bottom: 10px;">Lead Not Found</h2>
        <p style="color: var(--gray);">The requested lead could not be found.</p>
        <button class="btn-back" onclick="window.location.href='{{ url()->previous() }}'" style="margin-top: 20px; display: inline-flex;">
            <i class="fas fa-arrow-left"></i>
            Back
        </button>
    </div>
    @endif
</div>

        <!-- Notification -->
        <div class="notification" id="notification">
            <div class="notification-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="notification-content" id="notificationText"></div>
        </div>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

<script>
        let currentLeadId = "{{ $lead->id ?? '' }}";

   
        function toggleStatusDropdown() {
            const dropdown = document.getElementById('statusDropdown');
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
        }

        async function updateLeadStatus(status) {
            if (!currentLeadId) return;
            
            try {
                const response = await fetch(`/leads/${currentLeadId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ lead_status: status })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification(`Lead status updated to ${status}`, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(result.message || 'Failed to update status', 'error');
                }
            } catch (error) {
                showNotification('Error updating status', 'error');
            }
            
            document.getElementById('statusDropdown').classList.remove('show');
        }

        async function updateJourneyStatus(field, status) {
            if (!currentLeadId) return;
            
            // Show loading state
            const button = event?.target;
            if (button) {
                button.style.opacity = '0.7';
                button.disabled = true;
            }
            
            try {
                console.log(`Updating ${field} to ${status} for lead ${currentLeadId}`);
                
                const response = await fetch(`/interview-leads/${currentLeadId}/journey-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        field: field,
                        status: status
                    })
                });
                
                // Check if response is OK
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Server response:', errorText);
                    throw new Error(`Server error: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Server response:', data);
                
                if (data.success) {
                    const fieldNames = {
                        'application_status': 'Application',
                        'interview_status': 'Interview',
                        'selection_status': 'Selection',
                        'onboarding_status': 'Onboarding'
                    };
                    
                    showNotification(`${fieldNames[field] || field} status updated to ${status}`, 'success');
                    
                    // Update the UI immediately without page reload
                    updateStatusInUI(field, status);
                    
                    // Close dropdown
                    document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                        dropdown.style.display = 'none';
                    });
                } else {
                    throw new Error(data.message || 'Update failed');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error: ' + error.message, 'error');
            } finally {
                if (button) {
                    button.style.opacity = '1';
                    button.disabled = false;
                }
            }
        }

    function updateStatusInUI(field, status) {
        // Map field to step
        const stepMap = {
            'application_status': 'application',
            'interview_status': 'interview',
            'selection_status': 'selection',
            'onboarding_status': 'onboarding'
        };
        
        const step = stepMap[field];
        if (!step) return;
        
        // Find the timeline item for this step
        const timelineItems = document.querySelectorAll('.timeline-item');
        let targetItem = null;
        
        timelineItems.forEach(item => {
            const title = item.querySelector('.timeline-title')?.textContent.toLowerCase();
            if (title && title.includes(step)) {
                targetItem = item;
            }
        });
        
        if (!targetItem) return;
        
        // Update timeline dot
        const dot = targetItem.querySelector('.timeline-dot');
        if (dot) {
            dot.className = `timeline-dot ${status}`;
        }
        
        // Update timeline content
        const content = targetItem.querySelector('.timeline-content');
        if (content) {
            content.className = `timeline-content ${status}`;
        }
        
        // Update timeline icon
        const icon = targetItem.querySelector('.timeline-icon');
        if (icon) {
            icon.className = `timeline-icon ${status}`;
        }
        
        // Update status badge
        const badge = targetItem.querySelector('.timeline-badge');
        if (badge) {
            let statusText = '';
            let statusIcon = '';
            
            if (status === 'completed') {
                statusText = 'Completed';
                statusIcon = 'fa-check-circle';
            } else if (status === 'in_progress') {
                statusText = 'In Progress';
                statusIcon = 'fa-spinner';
            } else if (status === 'pending') {
                statusText = 'Pending';
                statusIcon = 'fa-clock';
            } else if (status === 'rejected') {
                statusText = 'Rejected';
                statusIcon = 'fa-times-circle';
            }
            
            badge.className = `timeline-badge ${status}`;
            badge.innerHTML = `<i class="fas ${statusIcon}"></i> ${statusText} <i class="fas fa-chevron-down" style="font-size: 10px; margin-left: 5px;"></i>`;
        }
        
        // Update the onclick attribute for the status dropdown trigger
        const badgeContainer = targetItem.querySelector('.status-container .timeline-badge');
        if (badgeContainer) {
            badgeContainer.setAttribute('onclick', `toggleStepStatusDropdown('${step}')`);
        }
        
        // Update the field in the hidden dropdown
        const dropdown = document.getElementById(`dropdown-${step}`);
        if (dropdown) {
            dropdown.setAttribute('data-current-status', status);
        }
        
        // Also update the modal if it's open
        if (step === 'selection' && document.getElementById('selectionModal').style.display === 'flex') {
            updateSelectionModalStatus(status);
        }
    }

        function updateSelectionModalStatus(status) {
            const modal = document.getElementById('selectionModal');
            if (!modal) return;
            
            const statusChip = modal.querySelector('.status-chip');
            if (statusChip) {
                statusChip.className = `status-chip ${status}`;
                
                let icon = '';
                let text = '';
                
                if (status === 'completed') {
                    icon = 'fa-check-circle';
                    text = 'Selected';
                } else if (status === 'in_progress') {
                    icon = 'fa-spinner';
                    text = 'In Progress';
                } else if (status === 'rejected') {
                    icon = 'fa-times-circle';
                    text = 'Rejected';
                } else {
                    icon = 'fa-clock';
                    text = 'Pending';
                }
                
                statusChip.innerHTML = `<i class="fas ${icon}"></i> ${text}`;
            }
        }

        function toggleStepStatusDropdown(step) {
            event.stopPropagation();
            // Close all other dropdowns first
            document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                if (dropdown.id !== `dropdown-${step}`) {
                    dropdown.style.display = 'none';
                }
            });
            
            // Toggle current dropdown
            const dropdown = document.getElementById(`dropdown-${step}`);
            if (dropdown) {
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            }
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.status-container')) {
                document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                    dropdown.style.display = 'none';
                });
            }
        });

  
        function openStepModal(step) {
            const modal = document.getElementById(step + 'Modal');
            if (modal) {
                modal.style.display = 'flex';
            }
        }


        function closeModal(step) {
            const modal = document.getElementById(step + 'Modal');
            if (modal) {
                modal.style.display = 'none';
            }
        }


        async function updateFollowupDate() {
            const dateInput = document.getElementById('followupDate');
            const selectedDate = dateInput.value;
            
            if (!selectedDate) {
                showNotification('Please select a date', 'error');
                return;
            }
            
            try {
                const response = await fetch(`/leads/${currentLeadId}/followup`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ follow_up: selectedDate })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('Follow-up date updated successfully', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(result.message || 'Failed to update date', 'error');
                }
            } catch (error) {
                showNotification('Error updating date', 'error');
            }
        }

        async function updateRoundStatus(roundId, status) {
            const select = document.getElementById(`status-${roundId}`);
            const originalValue = select.value;
            
            const allowedStatuses = ['pending', 'inprogress', 'passed', 'failed'];
            if (!allowedStatuses.includes(status)) {
                showNotification('Invalid status value', 'error');
                return;
            }
            
            select.style.opacity = '0.7';
            select.disabled = true;
            
            try {
                console.log('Updating round', roundId, 'to status:', status);
                
                const response = await fetch(`/round-status/${roundId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: status })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const statusMessages = {
                        'pending': 'Round marked as Pending',
                        'inprogress': 'Round marked as In Progress',
                        'passed': 'Round marked as Passed',
                        'failed': 'Round marked as Failed'
                    };
                    showNotification(statusMessages[status] || 'Status updated successfully', 'success');
                    
                    let color = '#94a3b8';
                    if (status === 'inprogress') color = '#3b82f6';
                    else if (status === 'passed') color = '#10b981';
                    else if (status === 'failed') color = '#ef4444';
                    
                    select.style.borderColor = color;
                    select.style.color = color;
                    
                    // Update the UI to reflect changes
                    setTimeout(() => location.reload(), 1500);
                } else {
                    throw new Error(data.message || 'Update failed');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error: ' + error.message, 'error');
                select.value = originalValue;
            } finally {
                select.style.opacity = '1';
                select.disabled = false;
            }
        }


        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            const text = document.getElementById('notificationText');
            const icon = notification.querySelector('.notification-icon i');
            
            text.textContent = message;
            notification.className = 'notification';
            
            if (type === 'error') {
                notification.classList.add('error');
                icon.className = 'fas fa-exclamation-circle';
            } else {
                icon.className = 'fas fa-check-circle';
            }
            
            notification.style.display = 'flex';
            
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }

            window.onclick = function(event) {
                if (event.target.classList.contains('modal-overlay')) {
                    event.target.style.display = 'none';
                }
            }

        let tempRating = {};

        function hoverStar(roundId, rating) {
            const stars = document.querySelectorAll(`#star-rating-${roundId} .fa-star`);
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.style.color = '#f59e0b';
                    star.style.transform = 'scale(1.1)';
                } else {
                    star.style.color = '#cbd5e1';
                    star.style.transform = 'scale(1)';
                }
            });
        }

        function resetStar(roundId) {
            const currentRating = parseInt(document.getElementById(`rating-${roundId}`).value) || 0;
            const stars = document.querySelectorAll(`#star-rating-${roundId} .fa-star`);
            stars.forEach((star, index) => {
                if (index < currentRating) {
                    star.style.color = '#f59e0b';
                } else {
                    star.style.color = '#cbd5e1';
                }
                star.style.transform = 'scale(1)';
            });
        }
        function setStar(roundId, rating) {
            document.getElementById(`rating-${roundId}`).value = rating;
            const ratingValue = document.getElementById(`rating-value-${roundId}`);
            
            if (ratingValue) {
                ratingValue.textContent = rating + '/5';
                
                // Find the marks section
                const roundDiv = document.getElementById(`round-${roundId}`);
                const marksSection = roundDiv.querySelector('div[style*="background: #f1f5f9"]');
                
                // Remove any existing warning messages first
                const existingWarnings = marksSection?.querySelectorAll('.rating-warning');
                if (existingWarnings) {
                    existingWarnings.forEach(w => w.remove());
                }
                
                // Show warning for low ratings (1 or 2 stars)
                if (rating < 3) {
                    ratingValue.style.color = '#dc2626';
                    ratingValue.style.fontWeight = '700';
                    
                    // Add subtle warning animation
                    ratingValue.style.animation = 'pulseWarning 0.5s ease';
                    setTimeout(() => {
                        ratingValue.style.animation = '';
                    }, 500);
                    
                    // Show warning message
                    if (marksSection) {
                        // Check if warning already exists
                        let warningMsg = marksSection.querySelector('.rating-warning');
                        
                        if (!warningMsg) {
                            warningMsg = document.createElement('div');
                            warningMsg.className = 'rating-warning';
                            warningMsg.style.cssText = 'margin-top: 8px; font-size: 12px; color: #dc2626; background: #fee2e2; padding: 6px 10px; border-radius: 6px; border-left: 3px solid #dc2626; animation: slideDown 0.3s ease;';
                            marksSection.appendChild(warningMsg);
                        }
                        
                        warningMsg.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ⚠️ Rating below 3 stars will mark this round as <strong>FAILED</strong>';
                    }
                } else {
                    ratingValue.style.color = '#10b981';
                    ratingValue.style.fontWeight = 'normal';
                    
                    // Remove warning message if exists
                    if (marksSection) {
                        const warningMsg = marksSection.querySelector('.rating-warning');
                        if (warningMsg) warningMsg.remove();
                    }
                }
                
                ratingValue.style.animation = 'pulse 0.3s ease';
                setTimeout(() => {
                    ratingValue.style.animation = '';
                }, 300);
            }
            
            hoverStar(roundId, rating);
        }


        async function saveRoundRating(roundId) {
            const rating = document.getElementById(`rating-${roundId}`).value;
            
            if (!rating || rating === '0') {
                showNotification('Please select a rating', 'error');
                return;
            }
            
            const ratingValue = parseInt(rating);
            const passingStars = 3; // 3+ stars = pass, 1-2 stars = fail
            
            // Determine round status based on rating
            let roundStatus;
            let isFailing = false;
            
            if (ratingValue < passingStars) {
                roundStatus = 'failed';
                isFailing = true;
                console.log(`❌ FAILED: Rating (${ratingValue}) < Passing (${passingStars}) → FAILED`);
            } else {
                roundStatus = 'passed';
                console.log(`✅ PASSED: Rating (${ratingValue}) >= Passing (${passingStars}) → PASSED`);
            }
            
            // SHOW CONFIRMATION IF RATING IS BELOW PASSING (1 or 2 stars)
            if (isFailing) {
                const confirmed = await showRatingFailConfirmation(ratingValue, passingStars);
                if (!confirmed) {
                    return; // User cancelled
                }
            }
            
            // Show loading state
            const saveButton = event.target.closest('button');
            if (saveButton) {
                saveButton.style.opacity = '0.7';
                saveButton.disabled = true;
                saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            }
            
            try {
                console.log(`Saving rating ${ratingValue} for round ${roundId}, Passing stars: ${passingStars}, Status: ${roundStatus}`);
                
                const response = await fetch(`/round-status/${roundId}/marks`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        marks: ratingValue
                    })
                });
                
                // Check if response is OK
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Server error response:', errorText);
                    throw new Error(`Server error: ${response.status}`);
                }
                
                const marksData = await response.json();
                console.log('Rating saved response:', marksData);
                
                // Check for success in the marks response
                if (marksData && (marksData.success === true || marksData.status === 'success')) {
                    
                    // Now update the round status based on rating
                    const statusResponse = await fetch(`/round-status/${roundId}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status: roundStatus })
                    });
                    
                    const statusData = await statusResponse.json();
                    console.log('Status update response:', statusData);
                    
                    if (statusData.success) {
                        // Update the UI - THIS WILL NOW CLEAN PROPERLY
                        refreshRatingUI(roundId, roundStatus, ratingValue);
                        updateMainInterviewStatus();
                        
                        // If round failed, also update interview and selection status to rejected
                        if (roundStatus === 'failed') {
                            const leadId = findLeadIdFromRound(roundId);
                            if (leadId) {
                                // Update interview and selection status to rejected
                                updateJourneyStatus('interview_status', 'rejected');
                                updateJourneyStatus('selection_status', 'rejected');
                            }
                        }
                        
                        // Show appropriate message
                        if (roundStatus === 'failed') {
                            showNotification(`❌ Student FAILED with ${ratingValue}/5 stars`, 'error');
                        } else {
                            showNotification(`✅ Student PASSED with ${ratingValue}/5 stars`, 'success');
                        }
                    } else {
                        throw new Error(statusData.message || 'Failed to update status');
                    }
                    
                } else {
                    throw new Error(marksData.message || 'Failed to save rating');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error: ' + error.message, 'error');
            } finally {
                // Remove loading state
                if (saveButton) {
                    saveButton.style.opacity = '1';
                    saveButton.disabled = false;
                    saveButton.innerHTML = '<i class="fas fa-save"></i> Save Rating';
                }
            }
        }

        async function saveRoundMarks(roundId) {
            const marksInput = document.getElementById(`marks-${roundId}`);
            const marks = marksInput.value;
            
            if (!marks || marks === '') {
                showNotification('Please enter marks', 'error');
                return;
            }
            
            // Validate if marks is a number
            if (isNaN(marks) || parseFloat(marks) < 0) {
                showNotification('Please enter a valid number', 'error');
                return;
            }
            
            // Get the maximum allowed marks
            const maxMarks = parseFloat(marksInput.max);
            const marksValue = parseFloat(marks);
            
            // Validate that marks do not exceed maximum
            if (marksValue > maxMarks) {
                showNotification(`Marks cannot exceed ${maxMarks}`, 'error');
                marksInput.style.borderColor = '#dc2626';
                marksInput.style.borderWidth = '2px';
                
                // Add a visual error indicator
                let errorMsg = marksInput.parentNode.querySelector('.marks-error');
                if (!errorMsg) {
                    errorMsg = document.createElement('div');
                    errorMsg.className = 'marks-error';
                    errorMsg.style.color = '#dc2626';
                    errorMsg.style.fontSize = '12px';
                    errorMsg.style.marginTop = '5px';
                    errorMsg.style.fontWeight = '500';
                    marksInput.parentNode.appendChild(errorMsg);
                }
                errorMsg.textContent = `Maximum allowed marks is ${maxMarks}`;
                
                // Remove error after 3 seconds
                setTimeout(() => {
                    marksInput.style.borderColor = '#e2e8f0';
                    marksInput.style.borderWidth = '2px';
                    if (errorMsg) errorMsg.remove();
                }, 3000);
                
                return;
            }
            
            // Get passing marks for this round
            let passingMarks = 40; // Default passing marks
            
            // Try to get passing marks from the round details in the UI
            const roundDiv = document.getElementById(`round-${roundId}`);
            if (roundDiv) {
                const roundDetails = roundDiv.querySelectorAll('div[style*="grid-template-columns"] div');
                roundDetails.forEach(detail => {
                    const label = detail.querySelector('span[style*="font-size: 12px"]');
                    if (label && label.textContent.includes('Passing Marks')) {
                        const valueSpan = detail.querySelector('span[style*="font-weight: 500"]');
                        if (valueSpan) {
                            passingMarks = parseFloat(valueSpan.textContent.trim()) || 40;
                        }
                    }
                });
            }
            
            // Determine round status based on marks vs passing marks
            let roundStatus;
            let isFailing = false;
            
            if (marksValue < passingMarks) {
                roundStatus = 'failed';
                isFailing = true;
                console.log(`❌ FAILED: Marks (${marksValue}) < Passing (${passingMarks}) → FAILED`);
            } else {
                roundStatus = 'passed';
                console.log(`✅ PASSED: Marks (${marksValue}) >= Passing (${passingMarks}) → PASSED`);
            }
            
            // Clear any previous error styling
            marksInput.style.borderColor = '#e2e8f0';
            const oldError = marksInput.parentNode.querySelector('.marks-error');
            if (oldError) oldError.remove();
            
            // SHOW CONFIRMATION IF MARKS ARE BELOW PASSING
            if (isFailing) {
                const confirmed = await showFailConfirmation(marksValue, maxMarks, passingMarks);
                if (!confirmed) {
                    return; // User cancelled
                }
            }
            
            // Show loading state
            marksInput.style.opacity = '0.7';
            marksInput.disabled = true;
            const saveButton = event.target.closest('button');
            if (saveButton) {
                saveButton.style.opacity = '0.7';
                saveButton.disabled = true;
                saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            }
            
            try {
                console.log(`Saving marks ${marks} for round ${roundId}, Passing marks: ${passingMarks}, Status: ${roundStatus}`);
                
                // First, save the marks
                const marksResponse = await fetch(`/round-status/${roundId}/marks`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        marks: marksValue
                    })
                });
                
                // Check if response is OK
                if (!marksResponse.ok) {
                    const errorText = await marksResponse.text();
                    console.error('Server error response:', errorText);
                    throw new Error(`Server error: ${marksResponse.status}`);
                }
                
                const marksData = await marksResponse.json();
                console.log('Marks saved response:', marksData);
                
                // Check for success in the marks response
                if (marksData && (marksData.success === true || marksData.status === 'success')) {
                    
                    // Now update the round status based on marks comparison
                    const statusResponse = await fetch(`/round-status/${roundId}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status: roundStatus })
                    });
                    
                    const statusData = await statusResponse.json();
                    console.log('Status update response:', statusData);
                    
                    if (statusData.success) {
                        // COMPLETE UI REFRESH
                        refreshRoundUI(roundId, roundStatus, marksValue, maxMarks, passingMarks);
                        updateMainInterviewStatus();
                        
                        // If round failed, also update interview and selection status to rejected
                        if (roundStatus === 'failed') {
                            const leadId = findLeadIdFromRound(roundId);
                            if (leadId) {
                                // Update interview and selection status to rejected
                                updateJourneyStatus('interview_status', 'rejected');
                                updateJourneyStatus('selection_status', 'rejected');
                            }
                        }
                        
                        // Update marks input with saved value
                        marksInput.value = marks;
                        
                        // Add success styling briefly
                        marksInput.style.borderColor = '#10b981';
                        marksInput.style.borderWidth = '2px';
                        setTimeout(() => {
                            marksInput.style.borderColor = '#e2e8f0';
                        }, 2000);
                        
                        // Show appropriate message
                        if (roundStatus === 'failed') {
                            showNotification(`❌ Student FAILED with ${marksValue}/${maxMarks} marks (Passing: ${passingMarks}) - Interview and Selection status set to Rejected`, 'error');
                        } else {
                            showNotification(`✅ Student PASSED with ${marksValue}/${maxMarks} marks`, 'success');
                        }
                    } else {
                        throw new Error(statusData.message || 'Failed to update status');
                    }
                    
                } else {
                    throw new Error(marksData.message || 'Failed to save marks');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error: ' + error.message, 'error');
            } finally {
                // Remove loading state
                marksInput.style.opacity = '1';
                marksInput.disabled = false;
                if (saveButton) {
                    saveButton.style.opacity = '1';
                    saveButton.disabled = false;
                    saveButton.innerHTML = '<i class="fas fa-save"></i> Save Marks';
                }
            }
        }

        function refreshRoundUI(roundId, status, marks, maxMarks, passingMarks) {
            const roundDiv = document.getElementById(`round-${roundId}`);
            if (!roundDiv) return;
            
            console.log(`🔄 Refreshing UI for round ${roundId} to status: ${status}`);
            
            // ===== STEP 1: FIND THE MARKS SECTION =====
            const marksSection = roundDiv.querySelector('div[style*="background: #f1f5f9"]');
            if (!marksSection) return;
            
            // ===== STEP 2: COMPLETELY CLEAR THE MARKS SECTION =====
            // Remove everything inside marks section except the header
            const header = marksSection.querySelector('div[style*="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;"]');
            marksSection.innerHTML = '';
            
            // Restore header if it existed
            if (header) {
                marksSection.appendChild(header);
            } else {
                // Recreate header if needed
                const newHeader = document.createElement('div');
                newHeader.style.cssText = 'display: flex; align-items: center; gap: 10px; margin-bottom: 10px;';
                
                if (status === 'passed' || status === 'failed') {
                    const icon = document.createElement('i');
                    icon.className = status === 'passed' ? 'fas fa-check-circle' : 'fas fa-times-circle';
                    icon.style.color = status === 'passed' ? '#10b981' : '#ef4444';
                    newHeader.appendChild(icon);
                    
                    const span = document.createElement('span');
                    span.style.fontWeight = '600';
                    span.textContent = status === 'passed' ? 'Marks Obtained' : 'Marks Obtained';
                    newHeader.appendChild(span);
                    
                    const badge = document.createElement('span');
                    badge.style.cssText = `background: ${status === 'passed' ? '#10b981' : '#ef4444'}; color: white; padding: 2px 8px; border-radius: 20px; font-size: 11px;`;
                    badge.textContent = status === 'passed' ? 'Test Round' : 'Test Round';
                    newHeader.appendChild(badge);
                }
                
                marksSection.appendChild(newHeader);
            }
            
            // ===== STEP 3: CREATE THE INPUT SECTION =====
            const inputDiv = document.createElement('div');
            inputDiv.style.cssText = 'display: flex; gap: 10px; align-items: center; flex-wrap: wrap;';
            
            // Marks input
            const inputWrapper = document.createElement('div');
            inputWrapper.style.cssText = 'flex: 1; min-width: 200px;';
            
            const input = document.createElement('input');
            input.type = 'number';
            input.id = `marks-${roundId}`;
            input.className = 'marks-input';
            input.value = marks;
            input.placeholder = 'Enter marks obtained';
            input.min = '0';
            input.max = maxMarks;
            input.step = '0.01';
            input.style.cssText = 'width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;';
            
            inputWrapper.appendChild(input);
            inputDiv.appendChild(inputWrapper);
            
            // Save button
            const saveBtn = document.createElement('button');
            saveBtn.onclick = function() { saveRoundMarks(roundId); };
            saveBtn.className = 'btn-save-marks';
            saveBtn.style.cssText = 'padding: 10px 20px; background: #f59e0b; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 5px;';
            saveBtn.innerHTML = '<i class="fas fa-save"></i> Save Marks';
            
            inputDiv.appendChild(saveBtn);
            marksSection.appendChild(inputDiv);
            
            // ===== STEP 4: ADD SINGLE MARKS DISPLAY =====
            const marksDisplay = document.createElement('div');
            marksDisplay.className = 'marks-display';
            marksDisplay.style.cssText = 'margin-top: 10px; font-size: 13px; animation: fadeIn 0.3s ease;';
            
            // Set color and text based on status
            if (status === 'passed') {
                marksDisplay.style.color = '#10b981';
                marksDisplay.innerHTML = `<i class="fas fa-check-circle"></i> Current marks: ${marks}/${maxMarks}`;
            } else if (status === 'failed') {
                marksDisplay.style.color = '#ef4444';
                marksDisplay.innerHTML = `<i class="fas fa-times-circle"></i> Current marks: ${marks}/${maxMarks} (Failed - below ${passingMarks})`;
            } else {
                marksDisplay.style.color = '#94a3b8';
                marksDisplay.innerHTML = `<i class="fas fa-clock"></i> Current marks: ${marks}/${maxMarks}`;
            }
            
            marksSection.appendChild(marksDisplay);
            
            // ===== STEP 5: UPDATE STATUS DROPDOWN =====
            const statusSelect = document.getElementById(`status-${roundId}`);
            if (statusSelect) {
                statusSelect.value = status;
                
                let color, bgColor;
                if (status === 'passed') {
                    color = '#10b981';
                    bgColor = '#f0fdf4';
                } else if (status === 'failed') {
                    color = '#ef4444';
                    bgColor = '#fee2e2';
                } else {
                    color = '#94a3b8';
                    bgColor = 'white';
                }
                
                statusSelect.style.borderColor = color;
                statusSelect.style.color = color;
                statusSelect.style.backgroundColor = bgColor;
            }
            
            // ===== STEP 6: UPDATE STATUS BADGE =====
            const statusBadge = roundDiv.querySelector('.round-status-badge');
            if (statusBadge) {
                statusBadge.className = `round-status-badge ${status}`;
                
                let icon, text;
                if (status === 'passed') {
                    icon = 'fa-check-circle';
                    text = 'Passed';
                } else if (status === 'failed') {
                    icon = 'fa-times-circle';
                    text = 'Failed';
                } else {
                    icon = 'fa-clock';
                    text = 'Pending';
                }
                
                statusBadge.innerHTML = `<i class="fas ${icon}"></i> ${text}`;
            }
            
            // ===== STEP 7: UPDATE TIMELINE =====
            setTimeout(updateTimelineOnce, 300);
        }


        function updateTimelineOnce() {
            const roundItems = document.querySelectorAll('.round-item');
            if (roundItems.length === 0) return;
            
            let allPassed = true;
            let anyFailed = false;
            
            roundItems.forEach(round => {
                const select = round.querySelector('select[id^="status-"]');
                if (select) {
                    const status = select.value;
                    if (status === 'failed') {
                        allPassed = false;
                        anyFailed = true;
                    } else if (status !== 'passed') {
                        allPassed = false;
                    }
                }
            });
            
            // Find selection step
            const timelineItems = document.querySelectorAll('.timeline-item');
            timelineItems.forEach(item => {
                const title = item.querySelector('.timeline-title');
                if (title && title.textContent.includes('Selection')) {
                    
                    let newStatus;
                    if (allPassed && !anyFailed) {
                        newStatus = 'completed';
                    } else if (anyFailed) {
                        newStatus = 'rejected';
                    } else {
                        newStatus = 'pending';
                    }
                    
                    // Update dot
                    const dot = item.querySelector('.timeline-dot');
                    if (dot) dot.className = `timeline-dot ${newStatus}`;
                    
                    // Update content
                    const content = item.querySelector('.timeline-content');
                    if (content) content.className = `timeline-content ${newStatus}`;
                    
                    // Update icon
                    const icon = item.querySelector('.timeline-icon');
                    if (icon) icon.className = `timeline-icon ${newStatus}`;
                    
                    // Update badge
                    const badge = item.querySelector('.timeline-badge');
                    if (badge) {
                        badge.className = `timeline-badge ${newStatus}`;
                        
                        let iconHtml, text;
                        if (newStatus === 'completed') {
                            iconHtml = 'fa-check-circle';
                            text = 'Completed';
                        } else if (newStatus === 'rejected') {
                            iconHtml = 'fa-times-circle';
                            text = 'Rejected';
                        } else {
                            iconHtml = 'fa-clock';
                            text = 'Pending';
                        }
                        
                        badge.innerHTML = `<i class="fas ${iconHtml}"></i> ${text} <i class="fas fa-chevron-down" style="font-size: 10px;"></i>`;
                    }
                }
            });
        }
        function updateMainInterviewStatus() {
        
            const interviewStep = Array.from(document.querySelectorAll('.timeline-item')).find(item => 
                item.querySelector('.timeline-title')?.textContent.includes('Interview')
            );
            
            if (!interviewStep) return;
            
            // Get all round statuses
            const rounds = document.querySelectorAll('.round-item select[id^="status-"]');
            let allPassed = true;
            let anyFailed = false;
            
            rounds.forEach(select => {
                if (select.value === 'failed') anyFailed = true;
                if (select.value !== 'passed') allPassed = false;
            });
            
            // Determine status
            let status = 'pending';
            if (anyFailed) status = 'rejected';
            else if (allPassed) status = 'completed';
            else if (rounds.length > 0) status = 'in_progress';
            
            // Update UI elements
            const dot = interviewStep.querySelector('.timeline-dot');
            const content = interviewStep.querySelector('.timeline-content');
            const icon = interviewStep.querySelector('.timeline-icon');
            const badge = interviewStep.querySelector('.timeline-badge');
            
            if (dot) dot.className = `timeline-dot ${status}`;
            if (content) content.className = `timeline-content ${status}`;
            if (icon) icon.className = `timeline-icon ${status}`;
            
            if (badge) {
                badge.className = `timeline-badge ${status}`;
                const icons = {
                    'completed': 'fa-check-circle',
                    'in_progress': 'fa-spinner',
                    'rejected': 'fa-times-circle',
                    'pending': 'fa-clock'
                };
                const texts = {
                    'completed': 'Completed',
                    'in_progress': 'In Progress', 
                    'rejected': 'Rejected',
                    'pending': 'Pending'
                };
                badge.innerHTML = `<i class="fas ${icons[status]}"></i> ${texts[status]} <i class="fas fa-chevron-down" style="font-size: 10px;"></i>`;
            }
        }
        function updateMarksInUI(roundId, marks, roundData) {
            const roundDiv = document.getElementById(`round-${roundId}`);
            if (!roundDiv) return;
            
            // Check if this is a test round
            const isTestRound = roundDiv.dataset.roundType === 'test';
            
            // Find the marks section
            const marksSection = roundDiv.querySelector('div[style*="background: #f1f5f9"]');
            if (!marksSection) return;
            
            // Remove ALL existing displays with 'marks-display', 'rating-display', or containing 'Current'
            const existingDisplays = marksSection.querySelectorAll('.marks-display, .rating-display, div[class*="display"]');
            existingDisplays.forEach(display => display.remove());
            
            // Also remove any div with text containing 'Current' (fallback)
            const allChildren = marksSection.children;
            for (let i = allChildren.length - 1; i >= 0; i--) {
                if (allChildren[i].textContent.includes('Current marks') || 
                    allChildren[i].textContent.includes('Current rating')) {
                    allChildren[i].remove();
                }
            }
            
            // Get the correct total marks from roundData
            // First try roundData.total_marks, then check the input's max attribute, then default to 100
            let totalMarks = roundData.total_marks;
            
            // If not in roundData, try to get from the input field's max attribute
            if (!totalMarks) {
                const marksInput = document.getElementById(`marks-${roundId}`);
                if (marksInput && marksInput.max) {
                    totalMarks = marksInput.max;
                }
            }
            
            // If still not found, try to get from the interview round data in the UI
            if (!totalMarks) {
                // Look for the total marks display in the round details
                const roundDetails = roundDiv.querySelectorAll('div[style*="grid-template-columns"] div');
                roundDetails.forEach(detail => {
                    const label = detail.querySelector('span[style*="font-size: 12px"]');
                    if (label && label.textContent.includes('Total Marks')) {
                        const valueSpan = detail.querySelector('span[style*="font-weight: 500"]');
                        if (valueSpan) {
                            totalMarks = valueSpan.textContent.trim();
                        }
                    }
                });
            }
            
            // Final fallback
            if (!totalMarks) {
                totalMarks = 100;
            }
            
            // Add new marks display with correct total marks
            const display = document.createElement('div');
            display.className = 'marks-display';
            display.style.marginTop = '10px';
            display.style.fontSize = '13px';
            display.style.color = '#10b981';
            display.style.animation = 'fadeIn 0.3s ease';
            
            display.innerHTML = `<i class="fas fa-check-circle"></i> Current marks: ${marks}/${totalMarks}`;
            
            marksSection.appendChild(display);
            
            // Update the marks input value
            const marksInput = document.getElementById(`marks-${roundId}`);
            if (marksInput) {
                marksInput.value = marks;
            }
            
            // Update round status if it changed
            if (roundData && roundData.round_status && typeof updateRoundStatusInUI === 'function') {
                updateRoundStatusInUI(roundId, roundData.round_status);
            }
        }

        function updateRatingInUI(roundId, rating, roundData) {
            const roundDiv = document.getElementById(`round-${roundId}`);
            if (!roundDiv) return;
            
            // Update star colors
            const stars = document.querySelectorAll(`#star-rating-${roundId} .fa-star`);
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.style.color = '#f59e0b';
                } else {
                    star.style.color = '#cbd5e1';
                }
            });
 
            const ratingValue = document.getElementById(`rating-value-${roundId}`);
            if (ratingValue) {
                ratingValue.textContent = rating + '/5';
            }
      
            const marksSection = roundDiv.querySelector('div[style*="background: #f1f5f9"]');
            if (!marksSection) return;

            const existingDisplays = marksSection.querySelectorAll('.marks-display, .rating-display, div[class*="display"]');
            existingDisplays.forEach(display => display.remove());
        
            const oldTextElements = marksSection.querySelectorAll('div[style*="margin-top: 10px"]');
            oldTextElements.forEach(el => {
                if (el.textContent.includes('Current')) {
                    el.remove();
                }
            });
            
            // Add new rating display
            const display = document.createElement('div');
            display.className = 'rating-display';
            display.style.marginTop = '10px';
            display.style.fontSize = '13px';
            display.style.color = '#10b981';
            display.style.animation = 'fadeIn 0.3s ease';
            display.setAttribute('data-rating-id', `rating-${roundId}`);
            
            let starsDisplay = '';
            for (let i = 1; i <= 5; i++) {
                starsDisplay += i <= rating ? '★' : '☆';
            }
            
            display.innerHTML = `<i class="fas fa-check-circle"></i> Current rating: ${starsDisplay} (${rating}/5)`;
            marksSection.appendChild(display);

        if (roundData && roundData.round_status && typeof updateRoundStatusInUI === 'function') {
            updateRoundStatusInUI(roundId, roundData.round_status);
        }
        }
        async function updateRoundStatus(roundId, status) {
            const select = document.getElementById(`status-${roundId}`);
            const originalValue = select.value;
            
            const allowedStatuses = ['pending', 'inprogress', 'passed', 'failed'];
            if (!allowedStatuses.includes(status)) {
                showNotification('Invalid status value', 'error');
                return;
            }
            
            select.style.opacity = '0.7';
            select.disabled = true;
            
            try {
                console.log('Updating round', roundId, 'to status:', status);
                
                const response = await fetch(`/round-status/${roundId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: status })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const statusMessages = {
                        'pending': 'Round marked as Pending',
                        'inprogress': 'Round marked as In Progress',
                        'passed': 'Round marked as Passed',
                        'failed': 'Round marked as Failed'
                    };
                    showNotification(statusMessages[status] || 'Status updated successfully', 'success');
                    
                    // Update UI without reload
                    let color = '#94a3b8';
                    if (status === 'inprogress') color = '#3b82f6';
                    else if (status === 'passed') color = '#10b981';
                    else if (status === 'failed') color = '#ef4444';
                    
                    select.style.borderColor = color;
                    select.style.color = color;
                    
                    // Update status badges in the round
                    updateRoundStatusInUI(roundId, status);
                } else {
                    throw new Error(data.message || 'Update failed');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error: ' + error.message, 'error');
                select.value = originalValue;
            } finally {
                select.style.opacity = '1';
                select.disabled = false;
            }
        }

        function updateRoundStatusInUI(roundId, status) {
            const select = document.getElementById(`status-${roundId}`);
            if (select) {
                // Update the select value
                select.value = status;
    
                let color = '#94a3b8';
                let bgColor = 'white';
                
                if (status === 'inprogress') {
                    color = '#3b82f6';
                    bgColor = '#f0f9ff';
                } else if (status === 'passed') {
                    color = '#10b981';
                    bgColor = '#f0fdf4';
                } else if (status === 'failed') {
                    color = '#ef4444';
                    bgColor = '#fee2e2';
                } else if (status === 'pending') {
                    color = '#94a3b8';
                    bgColor = '#f8fafc';
                }
                
                select.style.borderColor = color;
                select.style.color = color;
                select.style.backgroundColor = bgColor;
                
                // Also update any status badge if present
                const roundItem = document.getElementById(`round-${roundId}`);
                if (roundItem) {
                    const statusBadge = roundItem.querySelector('.round-status-badge');
                    if (statusBadge) {
                        statusBadge.className = `round-status-badge ${status}`;
                        
                        let icon = '';
                        let text = '';
                        
                        if (status === 'passed') {
                            icon = 'fa-check-circle';
                            text = 'Passed';
                        } else if (status === 'failed') {
                            icon = 'fa-times-circle';
                            text = 'Failed';
                        } else if (status === 'inprogress') {
                            icon = 'fa-spinner';
                            text = 'In Progress';
                        } else {
                            icon = 'fa-clock';
                            text = 'Pending';
                        }
                        
                        statusBadge.innerHTML = `<i class="fas ${icon}"></i> ${text}`;
                    }
                }
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('input[id^="marks-"]').forEach(input => {
                input.addEventListener('input', function() {
                    const maxMarks = parseFloat(this.max);
                    const currentValue = parseFloat(this.value) || 0;
                    
                    if (currentValue > maxMarks) {
                        this.style.borderColor = '#dc2626';
                        this.style.borderWidth = '2px';
                        this.style.backgroundColor = '#fef2f2';
                        
                        // Show error message
                        let errorMsg = this.parentNode.querySelector('.marks-error');
                        if (!errorMsg) {
                            errorMsg = document.createElement('div');
                            errorMsg.className = 'marks-error';
                            errorMsg.style.color = '#dc2626';
                            errorMsg.style.fontSize = '12px';
                            errorMsg.style.marginTop = '5px';
                            this.parentNode.appendChild(errorMsg);
                        }
                        errorMsg.textContent = `Maximum allowed marks is ${maxMarks}`;
                    } else {
                        this.style.borderColor = '#e2e8f0';
                        this.style.borderWidth = '2px';
                        this.style.backgroundColor = 'white';
                        
                        const errorMsg = this.parentNode.querySelector('.marks-error');
                        if (errorMsg) errorMsg.remove();
                    }
                });
            });
        });
        function findLeadIdFromRound(roundId) {
    const roundElement = document.getElementById(`round-${roundId}`);
    if (!roundElement) return null;

    const modal = roundElement.closest('.modal-content');
    if (modal) {
        const leadIdElement = modal.querySelector('[data-lead-id]');
        if (leadIdElement) {
            return leadIdElement.getAttribute('data-lead-id');
        }
    }

    const leadIdInput = document.querySelector('input[name="lead_id"]');
    if (leadIdInput) {
        return leadIdInput.value;
    }

    if (typeof currentLeadId !== 'undefined' && currentLeadId) {
        return currentLeadId;
    }
    
    console.warn('Could not find lead ID for round:', roundId);
    return null;
}

    function showRatingFailConfirmation(rating, passingStars) {
        return new Promise((resolve) => {
            // Remove any existing modals first
            const existingBackdrop = document.querySelector('.modal-fixed-backdrop');
            const existingModal = document.querySelector('.modal-fixed-content');
            if (existingBackdrop) existingBackdrop.remove();
            if (existingModal) existingModal.remove();
            
            // Create backdrop - using fixed positioning with no animation initially
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-fixed-backdrop';
            backdrop.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
                z-index: 999999;
                opacity: 0;
                transition: opacity 0.2s ease;
            `;
            
            // Create modal container
            const modalContainer = document.createElement('div');
            modalContainer.className = 'modal-fixed-content';
            modalContainer.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 1000000;
                opacity: 0;
                transition: opacity 0.2s ease;
                max-width: 90%;
                max-height: 90vh;
                overflow-y: auto;
            `;
            
            // Create star display
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    starsHtml += '<i class="fas fa-star" style="color: #f59e0b; font-size: 24px;"></i>';
                } else {
                    starsHtml += '<i class="far fa-star" style="color: #cbd5e1; font-size: 24px;"></i>';
                }
            }
            
            // Modal content
            const modalContent = document.createElement('div');
            modalContent.style.cssText = `
                background: white;
                border-radius: 24px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                border: 1px solid #e2e8f0;
                width: 480px;
                max-width: 100%;
            `;
            
            modalContent.innerHTML = `
                <div style="padding: 32px;">
                    <!-- Header with icon -->
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                        <div style="width: 56px; height: 56px; border-radius: 16px; background: linear-gradient(135deg, #fee2e2, #fecaca); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 28px; color: #dc2626;"></i>
                        </div>
                        <div>
                            <h3 style="margin: 0 0 4px 0; color: #1e293b; font-size: 20px; font-weight: 700;">Low Rating Warning</h3>
                            <p style="margin: 0; color: #64748b; font-size: 14px;">This will mark the round as failed</p>
                        </div>
                    </div>
                    
                    <!-- Rating Display -->
                    <div style="background: #f8fafc; border-radius: 20px; padding: 24px; margin-bottom: 24px; border: 2px solid #fee2e2;">
                        <div style="display: flex; justify-content: center; gap: 8px; margin-bottom: 16px; flex-wrap: wrap;">
                            ${starsHtml}
                        </div>
                        
                        <div style="display: flex; justify-content: center; gap: 24px; align-items: center;">
                            <div style="text-align: center;">
                                <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;">Your Rating</div>
                                <div style="font-size: 28px; font-weight: 700; color: #f59e0b;">${rating}</div>
                                <div style="font-size: 12px; color: #94a3b8;">/5 stars</div>
                            </div>
                            <div style="width: 2px; height: 40px; background: #e2e8f0;"></div>
                            <div style="text-align: center;">
                                <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;">Required</div>
                                <div style="font-size: 28px; font-weight: 700; color: #10b981;">${passingStars}+</div>
                                <div style="font-size: 12px; color: #94a3b8;">stars to pass</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Consequences -->
                    <div style="background: #fef2f2; border-radius: 16px; padding: 20px; margin-bottom: 28px; border: 1px solid #fecaca;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                            <i class="fas fa-info-circle" style="color: #dc2626; font-size: 16px;"></i>
                            <span style="font-weight: 600; color: #991b1b;">This will also:</span>
                        </div>
                        
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <i class="fas fa-users" style="color: #dc2626; width: 20px; font-size: 14px;"></i>
                                <span style="color: #475569; font-size: 14px;">Set Interview status to <strong style="color: #dc2626;">Rejected</strong></span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <i class="fas fa-check-double" style="color: #dc2626; width: 20px; font-size: 14px;"></i>
                                <span style="color: #475569; font-size: 14px;">Set Selection status to <strong style="color: #dc2626;">Rejected</strong></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Buttons -->
                    <div style="display: flex; gap: 12px;">
                        <button class="cancel-rating-btn" style="flex: 1; padding: 14px; border-radius: 12px; border: 2px solid #e2e8f0; background: white; color: #64748b; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button class="confirm-rating-fail-btn" style="flex: 1; padding: 14px; border-radius: 12px; border: none; background: linear-gradient(135deg, #dc2626, #b91c1c); color: white; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);">
                            <i class="fas fa-check"></i> Yes, Fail Round
                        </button>
                    </div>
                </div>
            `;
            
            modalContainer.appendChild(modalContent);
            
            // Add to DOM
            document.body.appendChild(backdrop);
            document.body.appendChild(modalContainer);
            
            // Force a reflow then animate in
            setTimeout(() => {
                backdrop.style.opacity = '1';
                modalContainer.style.opacity = '1';
            }, 10);
            
            // Handle button clicks
            const confirmBtn = modalContainer.querySelector('.confirm-rating-fail-btn');
            const cancelBtn = modalContainer.querySelector('.cancel-rating-btn');
            
            confirmBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                backdrop.remove();
                modalContainer.remove();
                resolve(true);
            });
            
            cancelBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                backdrop.remove();
                modalContainer.remove();
                resolve(false);
            });
            
            backdrop.addEventListener('click', (e) => {
                e.stopPropagation();
                backdrop.remove();
                modalContainer.remove();
                resolve(false);
            });
            
            // Prevent clicks inside modal from closing
            modalContainer.addEventListener('click', (e) => {
                e.stopPropagation();
            });
            
            // Escape key
            const handleEscape = (e) => {
                if (e.key === 'Escape') {
                    backdrop.remove();
                    modalContainer.remove();
                    document.removeEventListener('keydown', handleEscape);
                    resolve(false);
                }
            };
            document.addEventListener('keydown', handleEscape);
        });
    }


    function showFailConfirmation(marks, maxMarks, passingMarks) {
        return new Promise((resolve) => {
            // Remove any existing modals first
            const existingBackdrop = document.querySelector('.modal-fixed-backdrop');
            const existingModal = document.querySelector('.modal-fixed-content');
            if (existingBackdrop) existingBackdrop.remove();
            if (existingModal) existingModal.remove();
            
            // Create backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-fixed-backdrop';
            backdrop.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
                z-index: 999999;
                opacity: 0;
                transition: opacity 0.2s ease;
            `;
            
            // Create modal container
            const modalContainer = document.createElement('div');
            modalContainer.className = 'modal-fixed-content';
            modalContainer.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 1000000;
                opacity: 0;
                transition: opacity 0.2s ease;
                max-width: 90%;
                max-height: 90vh;
                overflow-y: auto;
            `;
            
            // Modal content
            const modalContent = document.createElement('div');
            modalContent.style.cssText = `
                background: white;
                border-radius: 24px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                border: 1px solid #e2e8f0;
                width: 480px;
                max-width: 100%;
            `;
            
            modalContent.innerHTML = `
                <div style="padding: 32px;">
                    <!-- Header with icon -->
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                        <div style="width: 56px; height: 56px; border-radius: 16px; background: linear-gradient(135deg, #fee2e2, #fecaca); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 28px; color: #dc2626;"></i>
                        </div>
                        <div>
                            <h3 style="margin: 0 0 4px 0; color: #1e293b; font-size: 20px; font-weight: 700;">Below Passing Marks</h3>
                            <p style="margin: 0; color: #64748b; font-size: 14px;">This will mark the round as failed</p>
                        </div>
                    </div>
                    
                    <!-- Marks Display -->
                    <div style="background: #f8fafc; border-radius: 20px; padding: 24px; margin-bottom: 24px; border: 2px solid #fee2e2;">
                        <div style="display: flex; justify-content: center; gap: 24px; align-items: center;">
                            <div style="text-align: center;">
                                <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;">Marks Obtained</div>
                                <div style="font-size: 32px; font-weight: 700; color: #f59e0b;">${marks}</div>
                                <div style="font-size: 12px; color: #94a3b8;">/ ${maxMarks}</div>
                            </div>
                            <div style="width: 2px; height: 40px; background: #e2e8f0;"></div>
                            <div style="text-align: center;">
                                <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;">Passing Marks</div>
                                <div style="font-size: 32px; font-weight: 700; color: #10b981;">${passingMarks}</div>
                                <div style="font-size: 12px; color: #94a3b8;">required</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Consequences -->
                    <div style="background: #fef2f2; border-radius: 16px; padding: 20px; margin-bottom: 28px; border: 1px solid #fecaca;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                            <i class="fas fa-info-circle" style="color: #dc2626; font-size: 16px;"></i>
                            <span style="font-weight: 600; color: #991b1b;">This will also:</span>
                        </div>
                        
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <i class="fas fa-users" style="color: #dc2626; width: 20px; font-size: 14px;"></i>
                                <span style="color: #475569; font-size: 14px;">Set Interview status to <strong style="color: #dc2626;">Rejected</strong></span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <i class="fas fa-check-double" style="color: #dc2626; width: 20px; font-size: 14px;"></i>
                                <span style="color: #475569; font-size: 14px;">Set Selection status to <strong style="color: #dc2626;">Rejected</strong></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Buttons -->
                    <div style="display: flex; gap: 12px;">
                        <button class="cancel-marks-btn" style="flex: 1; padding: 14px; border-radius: 12px; border: 2px solid #e2e8f0; background: white; color: #64748b; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button class="confirm-marks-fail-btn" style="flex: 1; padding: 14px; border-radius: 12px; border: none; background: linear-gradient(135deg, #dc2626, #b91c1c); color: white; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);">
                            <i class="fas fa-check"></i> Yes, Fail Round
                        </button>
                    </div>
                </div>
            `;
            
            modalContainer.appendChild(modalContent);
            
            // Add to DOM
            document.body.appendChild(backdrop);
            document.body.appendChild(modalContainer);
            
            // Force a reflow then animate in smoothly
            setTimeout(() => {
                backdrop.style.opacity = '1';
                modalContainer.style.opacity = '1';
            }, 10);
            
            // Handle button clicks
            const confirmBtn = modalContainer.querySelector('.confirm-marks-fail-btn');
            const cancelBtn = modalContainer.querySelector('.cancel-marks-btn');
            
            confirmBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                backdrop.remove();
                modalContainer.remove();
                resolve(true);
            });
            
            cancelBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                backdrop.remove();
                modalContainer.remove();
                resolve(false);
            });
            
            backdrop.addEventListener('click', (e) => {
                e.stopPropagation();
                backdrop.remove();
                modalContainer.remove();
                resolve(false);
            });
            
            modalContainer.addEventListener('click', (e) => {
                e.stopPropagation();
            });
            
            // Escape key
            const handleEscape = (e) => {
                if (e.key === 'Escape') {
                    backdrop.remove();
                    modalContainer.remove();
                    document.removeEventListener('keydown', handleEscape);
                    resolve(false);
                }
            };
            document.addEventListener('keydown', handleEscape);
        });
    }

        function showRatingFailConfirmation(rating, passingStars) {
            return new Promise((resolve) => {
                // Remove any existing modals first
                const existingBackdrop = document.querySelector('.modal-fixed-backdrop');
                const existingModal = document.querySelector('.modal-fixed-content');
                if (existingBackdrop) existingBackdrop.remove();
                if (existingModal) existingModal.remove();
                
                // Create backdrop - using fixed positioning with no animation initially
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-fixed-backdrop';
                backdrop.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.5);
                    backdrop-filter: blur(4px);
                    z-index: 999999;
                    opacity: 0;
                    transition: opacity 0.2s ease;
                `;
                
                // Create modal container
                const modalContainer = document.createElement('div');
                modalContainer.className = 'modal-fixed-content';
                modalContainer.style.cssText = `
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    z-index: 1000000;
                    opacity: 0;
                    transition: opacity 0.2s ease;
                    max-width: 90%;
                    max-height: 90vh;
                    overflow-y: auto;
                `;
                
                // Create star display
                let starsHtml = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= rating) {
                        starsHtml += '<i class="fas fa-star" style="color: #f59e0b; font-size: 24px;"></i>';
                    } else {
                        starsHtml += '<i class="far fa-star" style="color: #cbd5e1; font-size: 24px;"></i>';
                    }
                }
                
                // Modal content
                const modalContent = document.createElement('div');
                modalContent.style.cssText = `
                    background: white;
                    border-radius: 24px;
                    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                    border: 1px solid #e2e8f0;
                    width: 480px;
                    max-width: 100%;
                `;
                
                modalContent.innerHTML = `
                    <div style="padding: 32px;">
                        <!-- Header with icon -->
                        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                            <div style="width: 56px; height: 56px; border-radius: 16px; background: linear-gradient(135deg, #fee2e2, #fecaca); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-exclamation-triangle" style="font-size: 28px; color: #dc2626;"></i>
                            </div>
                            <div>
                                <h3 style="margin: 0 0 4px 0; color: #1e293b; font-size: 20px; font-weight: 700;">Low Rating Warning</h3>
                                <p style="margin: 0; color: #64748b; font-size: 14px;">This will mark the round as failed</p>
                            </div>
                        </div>
                        
                        <!-- Rating Display -->
                        <div style="background: #f8fafc; border-radius: 20px; padding: 24px; margin-bottom: 24px; border: 2px solid #fee2e2;">
                            <div style="display: flex; justify-content: center; gap: 8px; margin-bottom: 16px; flex-wrap: wrap;">
                                ${starsHtml}
                            </div>
                            
                            <div style="display: flex; justify-content: center; gap: 24px; align-items: center;">
                                <div style="text-align: center;">
                                    <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;">Your Rating</div>
                                    <div style="font-size: 28px; font-weight: 700; color: #f59e0b;">${rating}</div>
                                    <div style="font-size: 12px; color: #94a3b8;">/5 stars</div>
                                </div>
                                <div style="width: 2px; height: 40px; background: #e2e8f0;"></div>
                                <div style="text-align: center;">
                                    <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;">Required</div>
                                    <div style="font-size: 28px; font-weight: 700; color: #10b981;">${passingStars}+</div>
                                    <div style="font-size: 12px; color: #94a3b8;">stars to pass</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Consequences -->
                        <div style="background: #fef2f2; border-radius: 16px; padding: 20px; margin-bottom: 28px; border: 1px solid #fecaca;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                                <i class="fas fa-info-circle" style="color: #dc2626; font-size: 16px;"></i>
                                <span style="font-weight: 600; color: #991b1b;">This will also:</span>
                            </div>
                            
                            <div style="display: flex; flex-direction: column; gap: 12px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <i class="fas fa-users" style="color: #dc2626; width: 20px; font-size: 14px;"></i>
                                    <span style="color: #475569; font-size: 14px;">Set Interview status to <strong style="color: #dc2626;">Rejected</strong></span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <i class="fas fa-check-double" style="color: #dc2626; width: 20px; font-size: 14px;"></i>
                                    <span style="color: #475569; font-size: 14px;">Set Selection status to <strong style="color: #dc2626;">Rejected</strong></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Buttons -->
                        <div style="display: flex; gap: 12px;">
                            <button class="cancel-rating-btn" style="flex: 1; padding: 14px; border-radius: 12px; border: 2px solid #e2e8f0; background: white; color: #64748b; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button class="confirm-rating-fail-btn" style="flex: 1; padding: 14px; border-radius: 12px; border: none; background: linear-gradient(135deg, #dc2626, #b91c1c); color: white; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);">
                                <i class="fas fa-check"></i> Yes, Fail Round
                            </button>
                        </div>
                    </div>
                `;
                
                modalContainer.appendChild(modalContent);
                
                // Add to DOM
                document.body.appendChild(backdrop);
                document.body.appendChild(modalContainer);
                
                // Force a reflow then animate in
                setTimeout(() => {
                    backdrop.style.opacity = '1';
                    modalContainer.style.opacity = '1';
                }, 10);
                
                // Handle button clicks
                const confirmBtn = modalContainer.querySelector('.confirm-rating-fail-btn');
                const cancelBtn = modalContainer.querySelector('.cancel-rating-btn');
                
                confirmBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    backdrop.remove();
                    modalContainer.remove();
                    resolve(true);
                });
                
                cancelBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    backdrop.remove();
                    modalContainer.remove();
                    resolve(false);
                });
                
                backdrop.addEventListener('click', (e) => {
                    e.stopPropagation();
                    backdrop.remove();
                    modalContainer.remove();
                    resolve(false);
                });
                
                // Prevent clicks inside modal from closing
                modalContainer.addEventListener('click', (e) => {
                    e.stopPropagation();
                });
                
                // Escape key
                const handleEscape = (e) => {
                    if (e.key === 'Escape') {
                        backdrop.remove();
                        modalContainer.remove();
                        document.removeEventListener('keydown', handleEscape);
                        resolve(false);
                    }
                };
                document.addEventListener('keydown', handleEscape);
            });
        }

        function showRatingFailNotification(rating) {
            // Create a toast-style notification
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: white;
                border-radius: 16px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.15);
                border-left: 5px solid #dc2626;
                padding: 16px 20px;
                z-index: 1000002;
                max-width: 400px;
                animation: slideInRight 0.3s ease;
                display: flex;
                align-items: center;
                gap: 15px;
            `;
            
            notification.innerHTML = `
                <div style="width: 40px; height: 40px; border-radius: 50%; background: #fee2e2; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-star" style="color: #dc2626; font-size: 20px;"></i>
                </div>
                <div style="flex: 1;">
                    <div style="font-weight: 700; color: #1e293b; margin-bottom: 4px;">Round Failed</div>
                    <div style="font-size: 13px; color: #64748b;">
                        Rating of <strong style="color: #dc2626;">${rating} stars</strong> is below passing threshold.<br>
                        <span style="color: #b45309;">Interview & Selection status set to Rejected</span>
                    </div>
                </div>
                <button onclick="this.parentElement.remove()" style="background: none; border: none; color: #94a3b8; cursor: pointer; font-size: 18px;">&times;</button>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.animation = 'slideOutRight 0.3s ease';
                    setTimeout(() => notification.remove(), 300);
                }
            }, 5000);
            
            // Add animation styles
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideInRight {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                @keyframes slideOutRight {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }

    function refreshRatingUI(roundId, status, rating) {
        const roundDiv = document.getElementById(`round-${roundId}`);
        if (!roundDiv) return;
        
        console.log(`🔄 Refreshing rating UI for round ${roundId} to status: ${status}`);
        
        // ===== STEP 1: Update star colors =====
        const stars = document.querySelectorAll(`#star-rating-${roundId} .fa-star`);
        stars.forEach((star, index) => {
            if (index < rating) {
                star.style.color = '#f59e0b';
            } else {
                star.style.color = '#cbd5e1';
            }
        });
        
        // ===== STEP 2: Update rating text =====
        const ratingValue = document.getElementById(`rating-value-${roundId}`);
        if (ratingValue) {
            ratingValue.textContent = rating + '/5';
            
            // Color code based on status
            if (status === 'passed') {
                ratingValue.style.color = '#10b981';
            } else if (status === 'failed') {
                ratingValue.style.color = '#dc2626';
            } else {
                ratingValue.style.color = '#3b82f6';
            }
        }
        
        // ===== STEP 3: Update hidden input =====
        const ratingInput = document.getElementById(`rating-${roundId}`);
        if (ratingInput) {
            ratingInput.value = rating;
        }
        
        // ===== STEP 4: Find the marks section and CLEAN it =====
        const marksSection = roundDiv.querySelector('div[style*="background: #f1f5f9"]');
        if (marksSection) {
            // Remove ALL existing displays - target by class and also any div that might contain rating text
            const existingDisplays = marksSection.querySelectorAll('.rating-display, .marks-display, [class*="display"]');
            existingDisplays.forEach(display => display.remove());
            
            // Also remove any div that contains "Current rating" or "Current marks" text
            const allDivs = marksSection.querySelectorAll('div');
            allDivs.forEach(div => {
                if (div.textContent.includes('Current rating') || div.textContent.includes('Current marks')) {
                    div.remove();
                }
            });
            
            // Remove any warning messages
            const warnings = marksSection.querySelectorAll('.rating-warning, .marks-warning');
            warnings.forEach(warning => warning.remove());
            
            // ===== STEP 5: Add SINGLE new rating display =====
            const display = document.createElement('div');
            display.className = 'rating-display';
            display.style.cssText = 'margin-top: 10px; font-size: 13px; animation: fadeIn 0.3s ease;';
            
            // Set color based on status
            if (status === 'passed') {
                display.style.color = '#10b981';
            } else if (status === 'failed') {
                display.style.color = '#dc2626';
            } else {
                display.style.color = '#94a3b8';
            }
            
            // Create star display
            let starsDisplay = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    starsDisplay += '★';
                } else {
                    starsDisplay += '☆';
                }
            }
            
            // Set content based on status
            if (status === 'failed') {
                display.innerHTML = `<i class="fas fa-times-circle"></i> Current rating: ${starsDisplay} (${rating}/5) - Failed (below 3 stars)`;
            } else if (status === 'passed') {
                display.innerHTML = `<i class="fas fa-check-circle"></i> Current rating: ${starsDisplay} (${rating}/5) - Passed`;
            } else {
                display.innerHTML = `<i class="fas fa-clock"></i> Current rating: ${starsDisplay} (${rating}/5)`;
            }
            
            marksSection.appendChild(display);
        }
        
        // ===== STEP 6: Update status dropdown =====
        const statusSelect = document.getElementById(`status-${roundId}`);
        if (statusSelect) {
            statusSelect.value = status;
            
            let color, bgColor;
            if (status === 'passed') {
                color = '#10b981';
                bgColor = '#f0fdf4';
            } else if (status === 'failed') {
                color = '#ef4444';
                bgColor = '#fee2e2';
            } else {
                color = '#94a3b8';
                bgColor = 'white';
            }
            
            statusSelect.style.borderColor = color;
            statusSelect.style.color = color;
            statusSelect.style.backgroundColor = bgColor;
        }
    }
</script>
@endsection
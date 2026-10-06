{{-- admin_all_claims.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #eff6ff;
            --success: #10b981;
            --success-light: #f0fdf4;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            --warning: #f59e0b;
            --warning-light: #fffbeb;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
        }

        .rc-page-header {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            border-radius: 24px;
            padding: 2rem 2.5rem;
            margin-bottom: 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(15, 23, 42, 0.15);
        }

        .rc-page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .rc-page-header::after {
            content: '';
            position: absolute;
            bottom: -60%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .rc-header-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .rc-header-left {
            display: flex;
            align-items: center;
            gap: 1.2rem;
        }

        .rc-header-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: white;
            box-shadow: 0 4px 16px rgba(37, 99, 235, 0.3);
        }

        .rc-header-title {
            color: white;
            margin: 0;
        }

        .rc-header-title h1 {
            font-size: 1.8rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .rc-header-title p {
            margin: 4px 0 0 0;
            font-size: 0.95rem;
            font-weight: 400;
        }

        .rc-header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .rc-btn-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: white;
            padding: 0.6rem 1.4rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .rc-btn-header:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            color: white;
        }

        .rc-stats-badge {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            color: #94a3b8;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .rc-stats-badge strong {
            color: white;
            font-weight: 700;
        }

        .rc-glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 24px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04);
            padding: 1.5rem;
            transition: all 0.3s;
        }

        .rc-kpi-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #64748b;
        }

        .rc-kpi-val {
            font-size: 1.8rem;
            font-weight: 850;
            color: #0f172a;
            margin-top: 2px;
        }

        .rc-kpi-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .rc-badge-pill {
            padding: 0.25rem 0.8rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .rc-badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .rc-badge-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .rc-badge-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .rc-badge-settled {
            background: #e0e7ff;
            color: #3730a3;
        }

        .rc-badge-step1 {
            background: #dbeafe;
            color: #1e40af;
        }

        .rc-table-wrapper {
            background: white;
            border-radius: 28px;
            border: 1px solid #e2e8f0;
            overflow: scroll;
            box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.02);
        }

        .rc-modern-table {
            width: 100%;
            border-collapse: collapse;
        }

        .rc-modern-table th {
            background: #f8fafc;
            padding: 1rem 1.25rem;
            color: #475569;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 1.5px solid #edf2f7;
            text-align: left;
        }

        .rc-modern-table td {
            padding: 1.1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 0.9rem;
            vertical-align: middle;
        }

        .rc-modern-table tr:last-child td {
            border-bottom: none;
        }

        .rc-filter-section {
            padding: 0.75rem 1.25rem;
            display: flex;
            gap: 1rem;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
        }

        .rc-filter-section select,
        .rc-filter-section input {
            padding: 0.5rem 1.2rem;
            border-radius: 30px;
            border: 1px solid #e2e8f0;
            outline: none;
            font-size: 0.85rem;
            background: white;
            cursor: pointer;
        }

        .rc-filter-section select:focus,
        .rc-filter-section input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
        }

        .rc-filter-left {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .rc-filter-left label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .rc-results-count {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
        }

        .rc-btn-outline {
            background: transparent;
            border: 1.5px solid #e2e8f0;
            color: #475569;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .rc-btn-outline:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        .rc-btn-view {
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            color: white !important;
            border: none;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            text-decoration: none !important;
            transition: all 0.2s;
        }

        .rc-btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
            color: white !important;
        }

        .rc-empty-state {
            padding: 4rem 2rem;
            text-align: center;
            color: #94a3b8;
        }

        .rc-empty-state i {
            font-size: 3rem;
            color: #e2e8f0;
            margin-bottom: 10px;
            display: block;
        }

        .rc-empty-state h3 {
            margin: 0;
            color: #475569;
            font-size: 1.1rem;
        }

        .rc-empty-state p {
            margin: 6px 0 0 0;
            font-size: 0.9rem;
        }

        .policy-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .policy-tag {
            background: #eff6ff;
            color: #2563eb;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .master-id-link {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
            font-family: monospace;
            font-size: 0.85rem;
            cursor: pointer;
        }

        .master-id-link:hover {
            text-decoration: underline;
            color: #7c3aed;
        }

        /* Modal Styles - Enhanced */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(8px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-container {
            background: white;
            border-radius: 32px;
            width: 95%;
            max-width: 1200px;
            max-height: 92vh;
            overflow-y: auto;
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.35);
            animation: slideUp 0.35s cubic-bezier(0.21, 1.02, 0.73, 1);
            position: relative;
        }

        .modal-container::-webkit-scrollbar {
            width: 6px;
        }

        .modal-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .modal-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .modal-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px) scale(0.98);
                opacity: 0;
            }

            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, #f8fafc, #ffffff);
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 10;
            border-radius: 32px 32px 0 0;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-header h2 .batch-id {
            background: #e2e8f0;
            padding: 0.2rem 0.8rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #475569;
            letter-spacing: 0.5px;
        }

        .modal-close {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: #f1f5f9;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.25s ease;
            flex-shrink: 0;
        }

        .modal-close:hover {
            background: #e2e8f0;
            color: #0f172a;
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 2rem 2.5rem;
        }

        /* Enhanced Detail Sections */
        .detail-section {
            margin-bottom: 2rem;
            background: white;
            border-radius: 20px;
            border: 1px solid #e8edf4;
            overflow: hidden;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        .detail-section:hover {
            border-color: #d0d9e6;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        .detail-section-header {
            padding: 1rem 1.5rem;
            background: linear-gradient(135deg, #fafbfc, #f1f5f9);
            border-bottom: 1px solid #e8edf4;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .detail-section-header .icon-wrapper {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .detail-section-header .icon-wrapper.blue {
            background: #eff6ff;
            color: #2563eb;
        }

        .detail-section-header .icon-wrapper.purple {
            background: #f5f3ff;
            color: #7c3aed;
        }

        .detail-section-header .icon-wrapper.amber {
            background: #fffbeb;
            color: #f59e0b;
        }

        .detail-section-header h3 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.2px;
        }

        .detail-section-header .section-badge {
            margin-left: auto;
            font-size: 0.7rem;
            padding: 0.2rem 0.8rem;
            border-radius: 30px;
            background: #e2e8f0;
            color: #475569;
            font-weight: 600;
        }

        .detail-section-body {
            padding: 1.5rem;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 0.5rem 0.75rem;
            border-radius: 10px;
            background: #fafbfc;
            border: 1px solid #f1f5f9;
            transition: all 0.15s ease;
        }

        .detail-item:hover {
            background: #f8fafc;
            border-color: #e2e8f0;
        }

        .detail-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 0.6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .detail-label i {
            font-size: 0.6rem;
        }

        .detail-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: #0f172a;
        }

        .detail-value.highlight {
            color: #2563eb;
        }

        .detail-value.green {
            color: #059669;
        }

        .detail-value.red {
            color: #dc2626;
        }

        /* Enhanced Claims List */
        .claims-list {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .claim-card {
            background: white;
            border: 1px solid #e8edf4;
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.25s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        }

        .claim-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
            transform: translateY(-2px);
        }

        .claim-card .claim-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .claim-card .claim-policy {
            font-weight: 700;
            color: #2563eb;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .claim-card .claim-policy .claim-index {
            background: #e8edf4;
            color: #475569;
            font-size: 0.6rem;
            padding: 0.1rem 0.5rem;
            border-radius: 30px;
            font-weight: 700;
        }

        .claim-card .claim-amount {
            font-weight: 800;
            font-size: 1.15rem;
            color: #059669;
            padding: 0.2rem 0.8rem;
            background: #f0fdf4;
            border-radius: 30px;
        }

        .claim-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .claim-details-grid {
                grid-template-columns: 1fr;
            }
        }

        .claim-detail-item {
            padding: 0.4rem 0.6rem;
            border-radius: 8px;
            background: #fafbfc;
            border: 1px solid #f1f5f9;
        }

        .claim-detail-item strong {
            display: block;
            font-size: 0.6rem;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 0.5px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .claim-detail-item .value {
            font-size: 0.85rem;
            font-weight: 600;
            color: #0f172a;
        }

        .claim-card .claim-remarks {
            margin-top: 0.75rem;
            padding: 0.75rem 1rem;
            background: #fffbeb;
            border-radius: 10px;
            font-size: 0.85rem;
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }

        /* Enhanced Sub-Entries Table */
        .sub-entries-wrapper {
            margin-top: 1rem;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e8edf4;
        }

        .sub-entries-wrapper .sub-header {
            padding: 0.6rem 1rem;
            background: #f8fafc;
            font-weight: 700;
            font-size: 0.75rem;
            color: #475569;
            border-bottom: 1px solid #e8edf4;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sub-entries-wrapper .table {
            margin-bottom: 0;
        }

        .sub-entries-wrapper .table th {
            background: #fafbfc;
            font-size: 0.6rem;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
            padding: 0.5rem 0.75rem;
            border-bottom: 2px solid #e8edf4;
        }

        .sub-entries-wrapper .table td {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .sub-entries-wrapper .table tr:last-child td {
            border-bottom: none;
        }

        /* Attachment styling */
        .attachments-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .attachment-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.3rem 0.8rem;
            background: #eff6ff;
            color: #2563eb;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .attachment-link:hover {
            background: #dbeafe;
            color: #1e40af;
            border-color: #bfdbfe;
            transform: translateY(-1px);
        }

        .attachment-link i {
            font-size: 0.7rem;
        }

        /* Approval details styling */
        .approval-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 0.75rem;
        }

        @media (max-width: 768px) {
            .approval-grid {
                grid-template-columns: 1fr;
            }
        }

        .approval-card {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #e8edf4;
        }

        .approval-card .approval-label {
            font-size: 0.6rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 0.5px;
        }

        .approval-card .approval-value {
            font-weight: 600;
            font-size: 0.85rem;
            color: #0f172a;
        }

        .modal-footer {
            padding: 1.25rem 2rem;
            border-top: 2px solid #e8edf4;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            position: sticky;
            bottom: 0;
            background: white;
            border-radius: 0 0 32px 32px;
            background: linear-gradient(135deg, #fafbfc, #ffffff);
        }

        .btn-close-modal {
            padding: 0.65rem 2rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            border: 2px solid #e2e8f0;
            background: white;
            color: #475569;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-close-modal:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .btn-close-modal i {
            font-size: 0.9rem;
        }

        /* Policy tags in modal */
        .policy-tags-large {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .policy-tag-large {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            color: #1e40af;
            padding: 0.3rem 1rem;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid #bfdbfe;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .policy-tag-large i {
            font-size: 0.7rem;
            opacity: 0.7;
        }

        @media (max-width: 768px) {
            .rc-page-header {
                padding: 1.5rem;
            }

            .rc-header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .rc-filter-section {
                flex-direction: column;
                align-items: stretch;
            }

            .rc-filter-left {
                flex-direction: column;
                align-items: stretch;
            }

            .rc-filter-left input,
            .rc-filter-left select {
                width: 100%;
            }

            .modal-container {
                width: 98%;
                max-height: 98vh;
                border-radius: 24px;
            }

            .modal-header {
                padding: 1rem 1.25rem;
                border-radius: 24px 24px 0 0;
            }

            .modal-body {
                padding: 1rem 1.25rem;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .detail-section-body {
                padding: 1rem;
            }

            .modal-footer {
                padding: 1rem 1.25rem;
                border-radius: 0 0 24px 24px;
            }

            .modal-header h2 {
                font-size: 1.1rem;
            }

            .claim-card .claim-header {
                flex-direction: column;
                align-items: stretch;
            }
        }

        /* Status badge variations */
        .rc-badge-pill .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 4px;
        }

        .rc-badge-pending .status-dot {
            background: #f59e0b;
        }

        .rc-badge-approved .status-dot {
            background: #10b981;
        }

        .rc-badge-rejected .status-dot {
            background: #ef4444;
        }

        .rc-badge-settled .status-dot {
            background: #6366f1;
        }

        .rc-badge-step1 .status-dot {
            background: #3b82f6;
        }
    </style>

    <div class="p-2">
        <!-- Header -->
        <div class="rc-page-header">
            <div class="rc-header-content">
                <div class="rc-header-left">
                    <div class="rc-header-icon"><i class="fas fa-list-check"></i></div>
                    <div class="rc-header-title">
                        <h1>All Reimbursement Claims</h1>
                        <p>View and manage all submitted reimbursement batches</p>
                    </div>
                </div>
                <div class="rc-header-actions">
                    <a href="/institute-admin/create-reimbursement-claim" class="rc-btn-header">
                        <i class="fas fa-plus-circle"></i> Create New Claim
                    </a>
                    <span class="rc-stats-badge">
                        <i class="fas fa-clock" style="color: #f59e0b;"></i>
                        <strong id="pending-count">0</strong> Pending
                    </span>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
            <div class="rc-glass-card" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="rc-kpi-title">Total Batches</div>
                    <div class="rc-kpi-val" id="kpi-total">0</div>
                </div>
                <div class="rc-kpi-icon" style="background: #eff6ff; color: #2563eb;"><i class="fas fa-layer-group"></i>
                </div>
            </div>
            <div class="rc-glass-card" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="rc-kpi-title">Pending</div>
                    <div class="rc-kpi-val" style="color: #f59e0b;" id="kpi-pending">0</div>
                </div>
                <div class="rc-kpi-icon" style="background: #fffbeb; color: #f59e0b;"><i class="fas fa-clock"></i></div>
            </div>
            <div class="rc-glass-card" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="rc-kpi-title">Approved</div>
                    <div class="rc-kpi-val" style="color: #22c55e;" id="kpi-approved">0</div>
                </div>
                <div class="rc-kpi-icon" style="background: #f0fdf4; color: #22c55e;"><i class="fas fa-check-double"></i>
                </div>
            </div>
            <div class="rc-glass-card" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="rc-kpi-title">Declined</div>
                    <div class="rc-kpi-val" style="color: #ef4444;" id="kpi-rejected">0</div>
                </div>
                <div class="rc-kpi-icon" style="background: #fef2f2; color: #ef4444;"><i class="fas fa-ban"></i></div>
            </div>
        </div>

        <!-- Filters -->
        <div class="rc-filter-section">
            <div class="rc-filter-left">
                <label><i class="fas fa-filter"></i> Filter</label>
                <input type="text" id="search-input" placeholder="Search employee, batch ID or policy..."
                    style="width: 250px;">
                <select id="filter-status">
                    <option value="all">All Statuses</option>
                    <option value="pending">Pending Review</option>
                    <option value="step1_approved">Step 1 Approved</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Declined</option>
                    <option value="settled">Settled</option>
                    <option value="mixed">Mixed Status</option>
                </select>
                <select id="filter-policy">
                    <option value="all">All Policies</option>
                </select>
                <button class="rc-btn-outline" onclick="loadClaimBatches()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
            <div class="rc-results-count" id="results-count">Loading batches...</div>
        </div>

        <!-- Table -->
        <div class="rc-table-wrapper">
            <table class="rc-modern-table">
                <thead>
                    <tr>
                        <th>Batch ID</th>
                        <th>Employee</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Policies</th>
                        <th>Claims</th>
                        <th>Total Amount</th>
                        <th>Submission Date</th>
                        <th>Status</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="claims-table-body">
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #2563eb;"></i>
                            <div style="margin-top: 10px; color: #94a3b8;">Loading batches...</div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div id="table-empty-state" class="rc-empty-state" style="display: none;">
                <i class="fas fa-inbox"></i>
                <h3>No Claim Batches Found</h3>
                <p>No reimbursement claim batches have been submitted yet.</p>
            </div>
        </div>
    </div>

    <!-- Modal Overlay (Hidden by default) -->
    <div id="claims-detail-modal" class="modal-overlay" style="display: none;">
        <div class="modal-container">
            <div class="modal-header">
                <h2 id="modal-title">
                    <i class="fas fa-file-invoice" style="color: #2563eb;"></i>
                    Batch Details
                    <span class="batch-id" id="modal-batch-id">BATCH-001</span>
                </h2>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="modal-body-content">
                <!-- Content will be populated dynamically -->
            </div>
            <div class="modal-footer">
                <button class="btn-close-modal" onclick="closeModal()">
                    <i class="fas fa-times-circle"></i> Close
                </button>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value;
        let allClaims = [];
        let batches = [];

        function showToast(message, type = 'success') {
            const colors = { success: '#22c55e', error: '#ef4444' };
            const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle' };
            const toast = document.createElement('div');
            toast.style.cssText = `position:fixed;top:20px;right:20px;z-index:9999;background:white;border-radius:12px;padding:16px 24px;box-shadow:0 10px 40px rgba(0,0,0,0.15);display:flex;align-items:center;gap:12px;min-width:300px;border-left:4px solid ${colors[type]};`;
            toast.innerHTML = `<i class="fas ${icons[type]}" style="color:${colors[type]};font-size:1.2rem;"></i><span>${message}</span>`;
            document.body.appendChild(toast);
            setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'all 0.3s ease'; setTimeout(() => toast.remove(), 300); }, 4000);
        }

        async function loadClaimBatches() {
            try {
                const response = await fetch('/institute-admin/reimbursement-claims-admin', {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const result = await response.json();

                if (result.success) {
                    allClaims = result.data || [];
                    groupIntoBatches();
                    populatePolicyFilter();
                    updateStats();
                    applyFilters();
                } else {
                    allClaims = [];
                    batches = [];
                    updateStats();
                    renderTable([]);
                }
            } catch (err) {
                console.error('Error loading claims:', err);
                document.getElementById("claims-table-body").innerHTML = '<tr><td colspan="11" style="text-align: center; padding: 2rem; color: #ef4444;">Failed to load claims. Please try again.</td></tr>';
            }
        }

        function groupIntoBatches() {
            const batchMap = new Map();

            allClaims.forEach(claim => {
                const masterId = claim.master_request_id || 'BATCH-UNKNOWN';

                if (!batchMap.has(masterId)) {
                    batchMap.set(masterId, {
                        master_request_id: masterId,
                        claims: [],
                        employee_name: claim.name || 'N/A',
                        employee_id: claim.employee_id || 'N/A',
                        designation: claim.designation || 'N/A',
                        department: claim.department || 'N/A',
                        submission_date: claim.submission_date || 'N/A',
                        policies: new Set(),
                        statuses: new Set(),
                        total_amount: 0,
                        total_claims: 0
                    });
                }

                const batch = batchMap.get(masterId);
                batch.claims.push(claim);
                batch.total_amount += parseFloat(claim.claim_amount || 0);
                batch.total_claims++;

                if (claim.policy_name) batch.policies.add(claim.policy_name);

                if (claim.status === 'pending' || claim.status === 'step1_approved') {
                    batch.statuses.add('pending');
                } else if (claim.status === 'approved') {
                    batch.statuses.add('approved');
                } else if (claim.status === 'rejected') {
                    batch.statuses.add('rejected');
                } else if (claim.status === 'settled') {
                    batch.statuses.add('settled');
                }
            });

            batches = Array.from(batchMap.values()).map(batch => {
                let batchStatus = 'mixed';
                const uniqueStatuses = Array.from(batch.statuses);

                if (uniqueStatuses.length === 1) {
                    batchStatus = uniqueStatuses[0];
                } else if (uniqueStatuses.every(s => s === 'approved' || s === 'settled')) {
                    batchStatus = 'approved';
                } else if (uniqueStatuses.every(s => s === 'rejected')) {
                    batchStatus = 'rejected';
                } else if (uniqueStatuses.includes('pending')) {
                    batchStatus = 'pending';
                }

                return {
                    ...batch,
                    policies: Array.from(batch.policies),
                    batch_status: batchStatus
                };
            });

            batches.sort((a, b) => new Date(b.submission_date) - new Date(a.submission_date));
        }

        function populatePolicyFilter() {
            const select = document.getElementById('filter-policy');
            const policiesSet = new Set();
            batches.forEach(b => b.policies.forEach(p => policiesSet.add(p)));
            select.innerHTML = '<option value="all">All Policies</option>';
            Array.from(policiesSet).sort().forEach(p => {
                select.innerHTML += `<option value="${p}">${p}</option>`;
            });
        }

        function updateStats() {
            document.getElementById("kpi-total").textContent = batches.length;
            document.getElementById("kpi-pending").textContent = batches.filter(b => b.batch_status === 'pending').length;
            document.getElementById("kpi-approved").textContent = batches.filter(b => b.batch_status === 'approved' || b.batch_status === 'settled').length;
            document.getElementById("kpi-rejected").textContent = batches.filter(b => b.batch_status === 'rejected').length;
            document.getElementById("pending-count").textContent = batches.filter(b => b.batch_status === 'pending').length;
        }

        function getStatusBadge(status) {
            const map = {
                approved: '<span class="rc-badge-pill rc-badge-approved"><span class="status-dot"></span><i class="fas fa-check-circle"></i> Approved</span>',
                settled: '<span class="rc-badge-pill rc-badge-approved"><span class="status-dot"></span><i class="fas fa-check-circle"></i> Settled</span>',
                rejected: '<span class="rc-badge-pill rc-badge-rejected"><span class="status-dot"></span><i class="fas fa-times-circle"></i> Declined</span>',
                pending: '<span class="rc-badge-pill rc-badge-pending"><span class="status-dot"></span><i class="fas fa-clock"></i> Pending</span>',
                step1_approved: '<span class="rc-badge-pill rc-badge-step1"><span class="status-dot"></span><i class="fas fa-user-check"></i> Step 1 OK</span>',
                mixed: '<span class="rc-badge-pill" style="background:#f1f5f9;color:#475569;"><i class="fas fa-layer-group"></i> Mixed</span>'
            };
            return map[status] || `<span class="rc-badge-pill" style="background:#f1f5f9;color:#475569;">${status}</span>`;
        }

        function renderTable(data) {
            const body = document.getElementById("claims-table-body");
            const emptyState = document.getElementById("table-empty-state");
            body.innerHTML = "";

            if (data.length === 0) {
                emptyState.style.display = "block";
                document.getElementById("results-count").textContent = "Showing 0 batches";
                return;
            }

            emptyState.style.display = "none";
            document.getElementById("results-count").textContent = `Showing ${data.length} batch(es)`;

            data.forEach(batch => {
                const policyTags = batch.policies.slice(0, 2).map(p => `<span class="policy-tag">${p.substring(0, 20)}${p.length > 20 ? '...' : ''}</span>`).join('');
                const morePolicies = batch.policies.length > 2 ? `<span class="policy-tag">+${batch.policies.length - 2}</span>` : '';

                const tr = document.createElement('tr');
                tr.innerHTML = `
                                                    <td>
                                                        <span class="master-id-link" onclick="viewBatchDetails('${batch.master_request_id}')" title="Click to view details">
                                                            ${batch.master_request_id}
                                                        </span>
                                                    </td>
                                                    <td><div style="font-weight:600;">${batch.employee_name}</div><div style="font-size:0.75rem;color:#94a3b8;">${batch.employee_id}</div></td>
                                                    <td>${batch.designation}</td>
                                                    <td>${batch.department}</td>
                                                    <td><div class="policy-tags">${policyTags}${morePolicies}</div></td>
                                                    <td><strong>${batch.total_claims}</strong></td>
                                                    <td>₹${batch.total_amount.toLocaleString('en-IN', { minimumFractionDigits: 2 })}</td>
                                                    <td style="font-size:0.85rem;">${batch.submission_date}</td>
                                                    <td>${getStatusBadge(batch.batch_status)}</td>
                                                                <td class="d-flex justify-content-end gap-2">
                                                                    <button class="rc-btn-view" onclick="viewBatchDetails('${batch.master_request_id}')">
                                                                        <i class="fas fa-eye"></i> View
                                                                    </button>
                                                                    <a href="/institute-admin/reimbursement-claims/settlement/${batch.master_request_id}"
                                                                    class="btn rc-btn-view"><i class="fas fa-calculator"></i> View Settlement</a> 

                                                                </td>
                                                `;
                body.appendChild(tr);
            });
        }

        function applyFilters() {
            const query = document.getElementById("search-input").value.toLowerCase();
            const statusFilter = document.getElementById("filter-status").value;
            const policyFilter = document.getElementById("filter-policy").value;

            const filtered = batches.filter(batch => {
                const nameMatch = (batch.employee_name || '').toLowerCase().includes(query);
                const idMatch = (batch.master_request_id || '').toLowerCase().includes(query);
                const policyMatch = batch.policies.some(p => p.toLowerCase().includes(query));
                const matchesQuery = nameMatch || idMatch || policyMatch;

                const matchesStatus = statusFilter === 'all' || batch.batch_status === statusFilter;
                const matchesPolicy = policyFilter === 'all' || batch.policies.includes(policyFilter);

                return matchesQuery && matchesStatus && matchesPolicy;
            });

            renderTable(filtered);
        }

        function viewBatchDetails(masterId) {
            const batch = batches.find(b => b.master_request_id === masterId);
            if (!batch) {
                showToast('Batch not found', 'error');
                return;
            }

            const modal = document.getElementById('claims-detail-modal');
            const modalTitle = document.getElementById('modal-title');
            const modalBatchId = document.getElementById('modal-batch-id');
            const modalBody = document.getElementById('modal-body-content');

            // Update modal title with batch ID
            if (modalTitle) {
                modalTitle.innerHTML = `<i class="fas fa-file-invoice" style="color: #2563eb;"></i> Batch Details`;
            }
            if (modalBatchId) {
                modalBatchId.textContent = batch.master_request_id;
            }

            // Helper to format JSON arrays as links
            // Helper to format JSON arrays as links - UPDATED with correct route
            function renderAttachments(attachmentJson, originalNamesJson, label) {
                if (!attachmentJson) return `<span class="text-muted">None</span>`;
                let paths = [];
                let originals = [];
                try {
                    paths = JSON.parse(attachmentJson);
                    if (originalNamesJson) originals = JSON.parse(originalNamesJson);
                } catch (e) {
                    return `<span class="text-muted">Invalid data</span>`;
                }
                if (!Array.isArray(paths) || paths.length === 0) return `<span class="text-muted">None</span>`;
                return `<div class="attachments-wrapper">${paths.map((p, i) => {
                    const name = originals[i] || `file_${i + 1}`;
                    // Updated to use the named route for reimbursement files
                    const fileUrl = `/reimbursement-file/${p}`;
                    return `<a href="${fileUrl}" target="_blank" class="attachment-link"><i class="fas fa-paperclip"></i> ${name}</a>`;
                }).join('')}</div>`;
            }

            // Build HTML for each claim
            function buildClaimCard(claim, index) {
                const statusBadge = getStatusBadge(claim.status);
                const subEntries = claim.sub_entries_data || [];

                let subEntriesHtml = '';
                if (subEntries.length > 0) {
                    subEntriesHtml = `
                                    <div class="sub-entries-wrapper">
                                        <div class="sub-header">
                                            <i class="fas fa-list-ul" style="color: #7c3aed;"></i> Sub-Entries (${subEntries.length})
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Range</th>
                                                        <th>Amount</th>
                                                        <th>Date</th>
                                                        <th>Max Limit</th>
                                                        <th>Existing Claimed</th>
                                                        <th>Remaining</th>
                                                        <th>Exceeded?</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${subEntries.map((se, idx) => `
                                                        <tr>
                                                            <td><span class="badge bg-secondary rounded-pill">${idx + 1}</span></td>
                                                            <td><strong>${se.range_name || 'N/A'}</strong></td>
                                                            <td>₹${parseFloat(se.amount || 0).toFixed(2)}</td>
                                                            <td>${se.meal_date || se.travel_date || se.checkin_date || se.expense_date || se.date || 'N/A'}</td>
                                                            <td>${se.max_limit ? '₹' + parseFloat(se.max_limit).toFixed(2) : 'N/A'}</td>
                                                            <td>${se.range_existing_claimed ? '₹' + parseFloat(se.range_existing_claimed).toFixed(2) : '0.00'}</td>
                                                            <td>${se.range_remaining_limit !== null ? '₹' + parseFloat(se.range_remaining_limit).toFixed(2) : 'N/A'}</td>
                                                            <td>${se.is_exceeded ? '<span class="badge bg-danger">Yes</span>' : '<span class="badge bg-success">No</span>'}</td>
                                                        </tr>
                                                    `).join('')}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                `;
                }

                const billsHtml = renderAttachments(claim.bill_attachment, claim.bill_attachment_original, 'Bill');
                const photosHtml = renderAttachments(claim.photo_attachment, claim.photo_attachment_original, 'Photo');

                // Approval details
                let approvalHtml = '';
                if (claim.step1_approver_name || claim.step2_approver_name) {
                    approvalHtml = `
                                    <div class="approval-grid">
                                        <div class="approval-card">
                                            <div class="approval-label"><i class="fas fa-user-check"></i> Step 1 Approver</div>
                                            <div class="approval-value">${claim.step1_approver_name || 'N/A'}</div>
                                            <div style="font-size:0.75rem;color:#64748b;margin-top:4px;">
                                                Status: <strong>${claim.step1_status || 'N/A'}</strong>
                                                ${claim.step1_remarks ? `<br>Remarks: ${claim.step1_remarks}` : ''}
                                            </div>
                                        </div>
                                        <div class="approval-card">
                                            <div class="approval-label"><i class="fas fa-user-shield"></i> Step 2 Approver</div>
                                            <div class="approval-value">${claim.step2_approver_name || 'N/A'}</div>
                                            <div style="font-size:0.75rem;color:#64748b;margin-top:4px;">
                                                Status: <strong>${claim.step2_status || 'N/A'}</strong>
                                                ${claim.step2_remarks ? `<br>Remarks: ${claim.step2_remarks}` : ''}
                                            </div>
                                        </div>
                                    </div>
                                `;
                }

                return `
                                <div class="claim-card">
                                    <div class="claim-header">
                                        <div class="claim-policy">
                                            <i class="fas fa-file-alt" style="color: #2563eb;"></i>
                                            ${claim.policy_name || 'N/A'}
                                            <span class="claim-index">#${index + 1}</span>
                                            ${claim.policy_category ? `<span class="badge bg-info text-dark">${claim.policy_category}</span>` : ''}
                                        </div>
                                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                            ${statusBadge}
                                            <span class="claim-amount">₹${parseFloat(claim.claim_amount || 0).toFixed(2)}</span>
                                        </div>
                                    </div>

                                    <div class="claim-details-grid">
                                        <div>
                                            <div class="claim-detail-item">
                                                <strong><i class="fas fa-hashtag"></i> Claim ID</strong>
                                                <div class="value">${claim.reimbursement_request_id || 'N/A'}</div>
                                            </div>
                                            <div class="claim-detail-item">
                                                <strong><i class="fas fa-user"></i> Employee</strong>
                                                <div class="value">${claim.name || 'N/A'} (${claim.employee_id || 'N/A'})</div>
                                            </div>
                                            <div class="claim-detail-item">
                                                <strong><i class="fas fa-briefcase"></i> Designation</strong>
                                                <div class="value">${claim.designation || 'N/A'}</div>
                                            </div>
                                            <div class="claim-detail-item">
                                                <strong><i class="fas fa-building"></i> Department</strong>
                                                <div class="value">${claim.department || 'N/A'}</div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="claim-detail-item">
                                                <strong><i class="fas fa-tag"></i> Policy Category</strong>
                                                <div class="value">${claim.policy_category || 'N/A'}</div>
                                            </div>
                                            <div class="claim-detail-item">
                                                <strong><i class="fas fa-calculator"></i> Calculation Type</strong>
                                                <div class="value">${claim.calculation_type || 'N/A'}</div>
                                            </div>
                                            <div class="claim-detail-item">
                                                <strong><i class="fas fa-money-bill-wave"></i> Calculated Amount</strong>
                                                <div class="value" style="color:#2563eb;">₹${parseFloat(claim.calculated_amount || 0).toFixed(2)}</div>
                                            </div>
                                            ${claim.approved_amount ? `<div class="claim-detail-item">
                                                <strong><i class="fas fa-check-circle" style="color:#10b981;"></i> Approved Amount</strong>
                                                <div class="value" style="color:#10b981;">₹${parseFloat(claim.approved_amount).toFixed(2)}</div>
                                            </div>` : ''}
                                            ${claim.decline_reason ? `<div class="claim-detail-item">
                                                <strong><i class="fas fa-exclamation-triangle" style="color:#ef4444;"></i> Decline Reason</strong>
                                                <div class="value" style="color:#dc2626;">${claim.decline_reason}</div>
                                            </div>` : ''}
                                            ${claim.settlement_mode ? `<div class="claim-detail-item">
                                                <strong><i class="fas fa-handshake"></i> Settlement Mode</strong>
                                                <div class="value">${claim.settlement_mode}</div>
                                            </div>` : ''}
                                            ${claim.payout_amount ? `<div class="claim-detail-item">
                                                <strong><i class="fas fa-credit-card"></i> Payout Amount</strong>
                                                <div class="value" style="color:#059669;">₹${parseFloat(claim.payout_amount).toFixed(2)}</div>
                                            </div>` : ''}
                                        </div>
                                    </div>

                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-top:0.75rem;">
                                        <div class="claim-detail-item">
                                            <strong><i class="fas fa-calendar-day"></i> Expense Date</strong>
                                            <div class="value">${claim.expense_date || 'N/A'}</div>
                                        </div>
                                        <div class="claim-detail-item">
                                            <strong><i class="fas fa-calendar-check"></i> To Date</strong>
                                            <div class="value">${claim.to_date || 'N/A'}</div>
                                        </div>
                                        <div class="claim-detail-item">
                                            <strong><i class="fas fa-calendar-plus"></i> Submission Date</strong>
                                            <div class="value">${claim.submission_date || 'N/A'}</div>
                                        </div>
                                        <div class="claim-detail-item">
                                            <strong><i class="fas fa-comment"></i> Remarks</strong>
                                            <div class="value">${claim.remarks || 'N/A'}</div>
                                        </div>
                                        <div class="claim-detail-item">
                                            <strong><i class="fas fa-file-invoice"></i> Bills</strong>
                                            <div class="value">${billsHtml}</div>
                                        </div>
                                        <div class="claim-detail-item">
                                            <strong><i class="fas fa-image"></i> Photos</strong>
                                            <div class="value">${photosHtml}</div>
                                        </div>
                                    </div>

                                    ${approvalHtml}
                                    ${subEntriesHtml}
                                </div>
                            `;
            }

            // Assemble modal body
            modalBody.innerHTML = `
                            <!-- Employee & Batch Info -->
                            <div class="detail-section">
                                <div class="detail-section-header">
                                    <div class="icon-wrapper blue"><i class="fas fa-user-circle"></i></div>
                                    <h3>Employee & Batch Information</h3>
                                    <span class="section-badge"><i class="fas fa-layer-group"></i> ${batch.total_claims} Claims</span>
                                </div>
                                <div class="detail-section-body">
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <span class="detail-label"><i class="fas fa-user"></i> Employee Name</span>
                                            <span class="detail-value highlight">${batch.employee_name}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label"><i class="fas fa-id-badge"></i> Employee ID</span>
                                            <span class="detail-value">${batch.employee_id}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label"><i class="fas fa-briefcase"></i> Designation</span>
                                            <span class="detail-value">${batch.designation}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label"><i class="fas fa-building"></i> Department</span>
                                            <span class="detail-value">${batch.department}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label"><i class="fas fa-calendar-alt"></i> Submission Date</span>
                                            <span class="detail-value">${batch.submission_date}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label"><i class="fas fa-tasks"></i> Batch Status</span>
                                            <span class="detail-value">${getStatusBadge(batch.batch_status)}</span>
                                        </div>
                                        <div class="detail-item" style="grid-column: span 2;">
                                            <span class="detail-label"><i class="fas fa-coins"></i> Total Amount</span>
                                            <span class="detail-value green" style="font-size:1.2rem;">₹${batch.total_amount.toLocaleString('en-IN', { minimumFractionDigits: 2 })}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Policies Summary -->
                            <div class="detail-section">
                                <div class="detail-section-header">
                                    <div class="icon-wrapper purple"><i class="fas fa-file-alt"></i></div>
                                    <h3>Policies in Batch</h3>
                                    <span class="section-badge"><i class="fas fa-tags"></i> ${batch.policies.length}</span>
                                </div>
                                <div class="detail-section-body">
                                    <div class="policy-tags-large">
                                        ${batch.policies.map(p => `<span class="policy-tag-large"><i class="fas fa-tag"></i> ${p}</span>`).join('')}
                                    </div>
                                </div>
                            </div>

                            <!-- Individual Claims -->
                            <div class="detail-section">
                                <div class="detail-section-header">
                                    <div class="icon-wrapper amber"><i class="fas fa-list-ul"></i></div>
                                    <h3>Individual Claims</h3>
                                    <span class="section-badge"><i class="fas fa-copy"></i> ${batch.claims.length} Items</span>
                                </div>
                                <div class="detail-section-body">
                                    <div class="claims-list">
                                        ${batch.claims.map((claim, index) => buildClaimCard(claim, index)).join('')}
                                    </div>
                                </div>
                            </div>
                        `;

            // Show modal
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('claims-detail-modal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal on overlay click
        document.getElementById('claims-detail-modal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Event Listeners
        document.getElementById("search-input").addEventListener("input", applyFilters);
        document.getElementById("filter-status").addEventListener("change", applyFilters);
        document.getElementById("filter-policy").addEventListener("change", applyFilters);

        // Load on page load and auto-refresh
        document.addEventListener('DOMContentLoaded', () => {
            loadClaimBatches();
            setInterval(loadClaimBatches, 30000);
        });
    </script>
@endsection
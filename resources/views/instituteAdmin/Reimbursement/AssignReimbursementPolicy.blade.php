@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

    <style>
        /* Modern Header */
        .page-header {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            border-radius: 24px;
            padding: 2rem 2.5rem;
            margin-bottom: 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(15, 23, 42, 0.15);
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: -60%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .header-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1.2rem;
        }

        .header-icon {
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

        .header-title {
            color: white;
            margin: 0;
        }

        .header-title h1 {
            font-size: 1.8rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .header-title p {
            /* color: #94a3b8; */
            margin: 4px 0 0 0;
            font-size: 0.95rem;
            font-weight: 400;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-header {
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

        .btn-header:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            color: white;
        }

        .btn-header i {
            font-size: 0.9rem;
        }

        .stats-badge {
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

        .stats-badge strong {
            color: white;
            font-weight: 700;
        }

        /* .split-layout {
                                                                                                                                                                                                                                            display: grid;
                                                                                                                                                                                                                                            grid-template-columns: 1.2fr 2fr;
                                                                                                                                                                                                                                            gap: 1.8rem;
                                                                                                                                                                                                                                        } */

        @media (max-width: 900px) {
            .split-layout {
                grid-template-columns: 1fr;
            }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 24px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04);
            padding: 1.8rem;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .glass-card:hover {
            border-color: rgba(37, 99, 235, 0.2);
            box-shadow: 0 12px 40px -12px rgba(37, 99, 235, 0.08);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1.5px solid #edf2f7;
        }

        .card-header h3 {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header h3 i {
            color: #2563eb;
            background: #eff6ff;
            padding: 8px;
            border-radius: 12px;
            font-size: 1rem;
        }

        .card-badge {
            background: #f1f5f9;
            padding: 0.25rem 1rem;
            border-radius: 30px;
            font-size: 0.7rem;
            color: #64748b;
            font-weight: 600;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 1.25rem;
        }

        .form-group label {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-group label .step-num {
            background: #2563eb;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6rem;
            font-weight: 700;
        }

        .form-group label .required {
            color: #ef4444;
        }

        .form-control {
            width: 100%;
            padding: 0.7rem 1.1rem;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-size: 0.9rem;
            background: white;
            outline: none;
            transition: all 0.2s;
            font-family: inherit;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
            cursor: pointer;
        }

        select.form-control:disabled {
            background-color: #f8fafc;
            cursor: not-allowed;
            opacity: 0.7;
        }

        textarea.form-control {
            border-radius: 12px;
            min-height: 80px;
            resize: vertical;
        }

        .form-hint {
            color: #94a3b8;
            font-size: 0.7rem;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .form-hint i {
            font-size: 0.65rem;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            color: #334155;
            padding: 6px 0;
            transition: all 0.2s;
            cursor: pointer;
        }

        .checkbox-item:hover {
            color: #0f172a;
        }

        .checkbox-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 2px solid #cbd5e1;
            cursor: pointer;
            accent-color: #2563eb;
            transition: all 0.2s;
        }

        .checkbox-item input[type="checkbox"]:checked {
            border-color: #2563eb;
        }

        .radio-group {
            display: flex;
            gap: 1.5rem;
            padding: 0.5rem 0;
        }

        .radio-item {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            transition: all 0.2s;
            flex: 1;
            justify-content: center;
        }

        .radio-item:hover {
            border-color: #94a3b8;
            background: #f8fafc;
        }

        .radio-item input[type="radio"] {
            accent-color: #2563eb;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .radio-item.active {
            border-color: #2563eb;
            background: #eff6ff;
        }

        .radio-item label {
            margin: 0;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            color: #475569;
            text-transform: none;
            letter-spacing: 0;
        }

        .radio-item.active label {
            color: #2563eb;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white !important;
            border: none;
            padding: 0.75rem 1.6rem;
            border-radius: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(37, 99, 235, 0.25);
            font-size: 0.9rem;
            text-decoration: none !important;
            justify-content: center;
            width: 100%;
            margin-top: 8px;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            box-shadow: 0 6px 24px rgba(37, 99, 235, 0.35);
            transform: translateY(-2px);
        }

        .btn-gradient i {
            font-size: 1rem;
        }

        .badge-pill {
            padding: 0.25rem 0.8rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .table-wrapper {
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
        }

        .modern-table th {
            background: #f8fafc;
            padding: 0.9rem 1rem;
            color: #475569;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1.5px solid #edf2f7;
            text-align: left;
        }

        .modern-table td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 0.85rem;
            vertical-align: middle;
        }

        .modern-table tr:last-child td {
            border-bottom: none;
        }

        .modern-table tr:hover td {
            background: #fafcfd;
        }

        .btn-action-delete {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: 1px solid #fee2e2;
            color: #ef4444;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-action-delete:hover {
            background: #fef2f2;
            transform: scale(1.05);
            border-color: #fecaca;
        }

        .empty-state {
            padding: 3rem 2rem;
            text-align: center;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 3rem;
            color: #e2e8f0;
            margin-bottom: 10px;
            display: block;
        }

        .empty-state p {
            margin: 0;
            font-size: 0.9rem;
        }

        .range-container {
            background: #fafcfd;
            border: 1px solid #edf2f7;
            padding: 12px 16px;
            border-radius: 12px;
            max-height: 350px;
            overflow-y: auto;
        }

        .range-container::-webkit-scrollbar {
            width: 4px;
        }

        .range-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .range-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .approval-step {
            background: #fafcfd;
            border: 1px solid #edf2f7;
            border-radius: 12px;
            padding: 12px 16px;
            margin-top: 8px;
            transition: all 0.3s ease;
        }

        .approval-step.active {
            border-color: #2563eb;
            background: #eff6ff;
        }

        .approval-step label {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 6px;
            display: block;
        }

        .approval-step select {
            width: 100%;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            border: 1.5px solid #e2e8f0;
            font-size: 0.85rem;
            background: white;
            outline: none;
            transition: all 0.2s;
            font-family: inherit;
        }

        .approval-step select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
        }

        .assignment-type-toggle {
            display: flex;
            gap: 0;
            border-radius: 12px;
            overflow: hidden;
            border: 1.5px solid #e2e8f0;
            margin-bottom: 4px;
        }

        .toggle-option {
            flex: 1;
            padding: 0.6rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            font-weight: 600;
            font-size: 0.8rem;
            color: #64748b;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .toggle-option:hover {
            background: #f8fafc;
        }

        .toggle-option.active {
            background: #2563eb;
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .toggle-option i {
            font-size: 0.9rem;
        }

        .toggle-option:not(:last-child) {
            border-right: 1.5px solid #e2e8f0;
        }

        .range-override-box {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            margin-top: 8px;
            transition: all 0.2s ease;
        }

        .range-override-box:hover {
            border-color: #bfdbfe;
        }

        .range-override-box .range-label {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.85rem;
            margin-bottom: 8px;
        }

        .range-override-box .range-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .range-override-box .range-inputs .form-group {
            margin-bottom: 0;
        }

        .range-override-box .range-inputs .form-group label {
            font-size: 0.65rem;
            text-transform: uppercase;
            font-weight: 600;
            color: #64748b;
        }

        .range-override-box .range-inputs .form-control {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }

        .range-override-box .policy-limit-hint {
            font-size: 0.7rem;
            color: #94a3b8;
            margin-top: 6px;
            padding: 6px 10px;
            background: #f8fafc;
            border-radius: 6px;
            border-left: 3px solid #2563eb;
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast {
            background: white;
            border-radius: 12px;
            padding: 16px 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 300px;
            animation: slideIn 0.3s ease;
            border-left: 4px solid #2563eb;
        }

        .toast-success {
            border-left-color: #22c55e;
        }

        .toast-error {
            border-left-color: #ef4444;
        }

        .toast-warning {
            border-left-color: #f59e0b;
        }

        .toast-info {
            border-left-color: #3b82f6;
        }

        .toast i {
            font-size: 1.2rem;
        }

        .toast-success i {
            color: #22c55e;
        }

        .toast-error i {
            color: #ef4444;
        }

        .toast-warning i {
            color: #f59e0b;
        }

        .toast-info i {
            color: #3b82f6;
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

        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
            }

            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-left {
                width: 100%;
            }

            .header-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .glass-card {
                padding: 1.2rem;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .modern-table {
                font-size: 0.75rem;
            }

            .modern-table th,
            .modern-table td {
                padding: 0.6rem;
            }

            .btn-gradient {
                padding: 0.6rem 1.2rem;
                font-size: 0.85rem;
            }

            .radio-group {
                flex-direction: column;
                gap: 0.75rem;
            }

            .assignment-type-toggle {
                flex-direction: column;
                border-radius: 12px;
            }

            .toggle-option:not(:last-child) {
                border-right: none;
                border-bottom: 1.5px solid #e2e8f0;
            }

            .range-override-box .range-inputs {
                grid-template-columns: 1fr;
            }

            .split-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .header-title h1 {
                font-size: 1.3rem;
            }

            .header-icon {
                width: 44px;
                height: 44px;
                font-size: 1.2rem;
            }

            .stats-badge {
                font-size: 0.7rem;
                padding: 0.2rem 0.8rem;
            }
        }

        .policy-info-card {
            background: linear-gradient(135deg, #f0f7ff 0%, #e8f0fe 100%);
            border: 1px solid #bfdbfe;
            border-radius: 16px;
            padding: 1.2rem;
            margin-bottom: 1rem;
        }

        .policy-info-card .policy-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.8rem;
        }

        .policy-info-card .policy-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 1rem;
        }

        .policy-info-card .policy-meta {
            font-size: 0.75rem;
            color: #64748b;
        }

        .editable-range-box {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.2rem;
            margin-top: 0.8rem;
            transition: all 0.3s ease;
        }

        .editable-range-box:hover {
            border-color: #2563eb;
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.08);
        }

        .editable-range-box.editing {
            border-color: #2563eb;
            background: #f8faff;
        }

        .range-slider-group {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 0.8rem;
        }

        .range-slider-group input[type="range"] {
            flex: 1;
            height: 6px;
            -webkit-appearance: none;
            background: linear-gradient(to right, #2563eb 0%, #2563eb 50%, #e2e8f0 50%, #e2e8f0 100%);
            border-radius: 3px;
            outline: none;
        }

        .range-slider-group input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            background: #2563eb;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
        }

        .view-only-badge {
            background: #fef3c7;
            color: #d97706;
            padding: 0.2rem 0.8rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .editable-badge {
            background: #d1fae5;
            color: #059669;
            padding: 0.2rem 0.8rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .detail-modal-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -45%) scale(0.95);
            width: 90%;
            max-width: 800px;
            background: white;
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            z-index: 1001;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            max-height: 85vh;
            overflow-y: auto;
            padding: 2rem;
        }

        .detail-modal-container.active {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
            pointer-events: auto;
        }

        .detail-row {
            display: flex;
            padding: 0.6rem 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.88rem;
        }

        .detail-row .detail-label {
            font-weight: 600;
            color: #64748b;
            width: 35%;
            flex-shrink: 0;
        }

        .detail-row .detail-value {
            color: #1e293b;
            font-weight: 500;
            flex: 1;
        }

        .range-badge {
            display: inline-block;
            background: #eff6ff;
            color: #2563eb;
            padding: 0.3rem 0.8rem;
            border-radius: 8px;
            font-size: 0.8rem;
            margin: 2px 4px;
            border: 1px solid #bfdbfe;
        }

        /* Policy select dropdown styling */
        #assign-policy {
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        #assign-policy option {
            padding: 8px 12px;
            font-size: 0.85rem;
        }

        /* Style options based on status */
        #assign-policy option[data-status="active"] {
            /* background: #f0fdf4; */
            color: #065f46;
        }

        #assign-policy option[data-status="upcoming"] {
            /* background: #eff6ff; */
            color: #1e40af;
        }

        #assign-policy option[data-status="expired"] {
            background: #fef2f2;
            color: #991b1b;
        }

        #assign-policy option[data-status="inactive"] {
            background: #f8fafc;
            color: #64748b;
        }

        /* Alternative: Add colored dots before policy names using pseudo-elements */
        /* Note: This may not work in all browsers for <option> elements */
        #assign-policy option::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 8px;
        }

        #assign-policy option[data-status="active"]::before {
            background: #22c55e;
        }

        #assign-policy option[data-status="upcoming"]::before {
            background: #3b82f6;
        }

        #assign-policy option[data-status="expired"]::before {
            background: #ef4444;
        }

        #assign-policy option[data-status="inactive"]::before {
            background: #6b7280;
        }

        /* Optgroup styling */
        #assign-policy optgroup {
            font-weight: 700;
            font-size: 0.85rem;
            color: #0f172a;
            background: #f8fafc;
            padding: 8px 0;
            font-style: normal;
        }

        #assign-policy option {
            padding: 6px 12px;
            font-size: 0.82rem;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        #assign-policy option:hover {
            background: #eff6ff !important;
        }
    </style>

    <div class="p-2">
        <!-- Toast Container -->
        <div class="toast-container" id="toastContainer"></div>

        <!-- Modern Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fas fa-user-tag"></i>
                    </div>
                    <div class="header-title">
                        <h1>Policy Assignment Configuration</h1>
                        <p>Map policies to departments/designations with customizable limits</p>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="/instiute-admin/reimbursement-policies" class="btn-header">
                        <i class="fas fa-arrow-left"></i> Back to Policies
                    </a>
                    <span class="stats-badge">
                        <i class="fas fa-check-circle" style="color: #22c55e;"></i>
                        <strong id="assignment-count">0</strong> Active Assignments
                    </span>
                </div>
            </div>
        </div>

        <div class="split-layout">
            <!-- Assign Policies Form -->
            <div class="glass-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-plus-circle"></i>
                        New Policy Map
                    </h3>
                    <span class="card-badge"><i class="far fa-clock"></i> Quick Setup</span>
                </div>

                <form id="assignment-form" onsubmit="return createAssignment(event)">
                    <!-- Step 1: Assignment Type -->
                    <div class="form-group">
                        <label>
                            <span class="step-num">1</span>
                            Assignment Type <span class="required">*</span>
                        </label>
                        <div class="assignment-type-toggle" style="display: flex; flex-wrap: wrap;">
                            <button type="button" class="toggle-option active" data-type="department"
                                onclick="selectAssignmentType('department')">
                                <i class="fas fa-building"></i> Department Level
                            </button>
                            <button type="button" class="toggle-option" data-type="designation"
                                onclick="selectAssignmentType('designation')">
                                <i class="fas fa-user-tie"></i> Designation Level
                            </button>
                            <button type="button" class="toggle-option" data-type="employee"
                                onclick="selectAssignmentType('employee')">
                                <i class="fas fa-user"></i> Employee Level
                            </button>
                        </div>
                        <span class="form-hint" id="assignment-type-hint">
                            <i class="fas fa-info-circle"></i>
                            Department Level: Policy applies to ALL members of the selected department
                        </span>
                    </div>

                    <!-- Step 2a: Department -->
                    <div class="form-group" id="department-group">
                        <label>
                            <span class="step-num">2</span>
                            Department <span class="required">*</span>
                        </label>
                        <select id="assign-dept" class="form-control" onchange="handleDepartmentChange(this.value)">
                            <option value="">Select Department</option>
                            <option value="all">🏢 All Departments</option>
                        </select>
                    </div>

                    <!-- Step 2b: Category & Designation -->
                    <div class="form-group" id="designation-group" style="display: none;">
                        <label>
                            <span class="step-num">2</span>
                            Department Category <span class="required">*</span>
                        </label>
                        <select id="assign-category" class="form-control" onchange="handleCategoryChange(this.value)">
                            <option value="">Select Department Category</option>
                        </select>
                    </div>

                    <!-- Step 2c: Employee Selection -->
                    <div class="form-group" id="employee-group" style="display: none;">
                        <label>
                            <span class="step-num">2</span>
                            Department <span class="required">*</span>
                        </label>
                        <select id="assign-emp-dept" class="form-control"
                            onchange="handleEmployeeDepartmentChange(this.value)">
                            <option value="">Select Department</option>
                        </select>
                    </div>

                    <div class="form-group" id="employee-select-group" style="display: none;">
                        <label>
                            <span class="step-num">3</span>
                            Employee <span class="required">*</span>
                        </label>
                        <select id="assign-employee" class="form-control">
                            <option value="">Select Employee</option>
                        </select>
                    </div>

                    <div class="form-group" id="designation-select-group" style="display: none;">
                        <label>
                            <span class="step-num">3</span>
                            Designation <span class="required">*</span>
                        </label>
                        <select id="assign-desig" class="form-control">
                            <option value="">Select Designation</option>
                        </select>
                    </div>

                    <!-- Step 3: Policy Selection -->
                    <div class="form-group">
                        <label>
                            <span class="step-num" id="policy-step-num">3</span>
                            Select Policy <span class="required">*</span>
                        </label>
                        <select id="assign-policy" class="form-control" onchange="loadPolicyDetails(this.value)">
                            <option value="">Select active policy...</option>
                        </select>
                    </div>

                    <!-- Step 4: Policy Details & Ranges -->
                    <div class="form-group" id="range-picker-group" style="display: none;">
                        <label>
                            <span class="step-num" id="range-step-num">4</span>
                            Policy Ranges Configuration
                        </label>
                        <div class="range-container" id="ranges-container"></div>
                    </div>

                    <!-- Step 5: Approval -->
                    <div class="form-group">
                        <label>
                            <span class="step-num" id="approval-step-num">5</span>
                            Approval Process <span class="required">*</span>
                        </label>
                        <div class="radio-group" id="approval-radio-group">
                            <div class="radio-item active" onclick="selectApprovalType('1step')">
                                <input type="radio" name="approval-type" value="1step" checked id="approval-1step">
                                <label for="approval-1step">1 Step Approval</label>
                            </div>
                            <div class="radio-item" onclick="selectApprovalType('2step')">
                                <input type="radio" name="approval-type" value="2step" id="approval-2step">
                                <label for="approval-2step">2 Step Approval</label>
                            </div>
                        </div>
                    </div>

                    <!-- Approval Steps -->
                    <div id="approval-steps">
                        <div class="approval-step active" id="step1-approval">
                            <label>Step 1 Approver <span class="required">*</span></label>
                            <select id="approver-step1" class="form-control">
                                <option value="">Select Approver...</option>
                            </select>
                        </div>
                        <div class="approval-step" id="step2-approval" style="display: none;">
                            <div style="margin-bottom: 12px;">
                                <label>Step 1 Approver <span class="required">*</span></label>
                                <select id="approver-step2-1" class="form-control">
                                    <option value="">Select First Approver...</option>
                                </select>
                            </div>
                            <div>
                                <label>Step 2 Approver <span class="required">*</span></label>
                                <select id="approver-step2-2" class="form-control">
                                    <option value="">Select Second Approver...</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="form-group">
                        <label>
                            <i class="far fa-sticky-note" style="color: #94a3b8;"></i>
                            Assignment Notes <span
                                style="font-weight: 400; color: #94a3b8; font-size: 0.65rem;">(Optional)</span>
                        </label>
                        <textarea id="assign-notes" class="form-control" rows="2"
                            placeholder="Add any notes about this assignment..."></textarea>
                    </div>

                    <button type="submit" class="btn-gradient">
                        <i class="fas fa-check-double"></i> Map Policy
                    </button>
                </form>
            </div>

            <!-- Assignments List -->
            <div class="glass-card" style="display: flex; flex-direction: column; gap: 1rem;">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-network-wired"></i>
                        Policy Assignments Matrix
                    </h3>
                    <span class="card-badge" id="assignment-badge">
                        <i class="fas fa-list"></i> <span id="assignment-count-table">0</span> Records
                    </span>
                </div>

                <div class="table-wrapper">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Department / Category</th>
                                <th>Designation</th>
                                <th>Policy</th>
                                <th>Allowed Ranges</th>
                                <th>Approval</th>
                                <th style="text-align: center; width: 80px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="assignments-table-body"></tbody>
                    </table>
                    <div id="assignments-empty-state" class="empty-state" style="display: none;">
                        <i class="fas fa-clipboard-list"></i>
                        <p>No assignments configured yet.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail View Modal -->
    <div class="modal-overlay" id="detail-overlay" onclick="closeDetailModal()"></div>
    <div class="detail-modal-container" id="detail-modal">
        <div
            style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1.5px solid #edf2f7; padding-bottom: 1rem; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin: 0;">
                <i class="fas fa-search" style="color: #2563eb;"></i>
                Assignment Details
            </h3>
            <button onclick="closeDetailModal()"
                style="border: none; background: transparent; font-size: 1.2rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="detail-content"></div>
    </div>

    <script>
        (function () {
            'use strict';

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                document.querySelector('input[name="_token"]')?.value;

            let departments = [];
            let categories = [];
            let designations = [];
            let policies = [];
            let assignments = [];
            let allDesignations = [];
            let currentPolicyData = null;

            function showToast(message, type = 'info') {
                const container = document.getElementById('toastContainer');
                const toast = document.createElement('div');
                const iconMap = {
                    'success': 'fa-check-circle',
                    'error': 'fa-exclamation-circle',
                    'warning': 'fa-exclamation-triangle',
                    'info': 'fa-info-circle'
                };
                toast.className = `toast toast-${type}`;
                toast.innerHTML = `<i class="fas ${iconMap[type] || 'fa-info-circle'}"></i><span>${message}</span>`;
                container.appendChild(toast);
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    toast.style.transition = 'all 0.3s ease';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            async function fetchJson(url, options = {}) {
                const response = await fetch(url, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        ...(options.headers || {}),
                    },
                    ...options,
                });
                const result = await response.json().catch(() => ({}));
                if (!response.ok) throw new Error(result.message || 'Request failed');
                return result;
            }

            // ============ LOAD DATA ============
            async function loadDepartments() {
                try {
                    const data = await fetchJson('/institute-admin/reimbursement-assignments/departments');
                    departments = data || [];
                    const select = document.getElementById('assign-dept');
                    select.innerHTML = '<option value="">Select Department</option><option value="all">🏢 All Departments</option>';
                    departments.forEach(dept => { select.innerHTML += `<option value="${dept.id}">${dept.name}</option>`; });
                } catch (error) { console.error('Error loading departments:', error); }
            }

            async function loadDepartmentCategories() {
                try {
                    const data = await fetchJson('/institute-admin/reimbursement-assignments/department-categories');
                    categories = data || [];
                    const select = document.getElementById('assign-category');
                    select.innerHTML = '<option value="">Select Department Category</option>';
                    categories.forEach(cat => { select.innerHTML += `<option value="${cat.id}">${cat.name}</option>`; });
                    select.disabled = false;
                } catch (error) { console.error('Error loading categories:', error); }
            }

            async function loadDesignationsByCategory(categoryId) {
                try {
                    const data = await fetchJson(`/institute-admin/reimbursement-assignments/designations-by-category?department_category_id=${categoryId}`);
                    designations = data || [];
                    const select = document.getElementById('assign-desig');
                    select.innerHTML = '<option value="">Select Designation</option>';
                    designations.forEach(desig => { select.innerHTML += `<option value="${desig.id}">${desig.name}</option>`; });
                    select.disabled = false;
                } catch (error) { console.error('Error loading designations:', error); }
            }

            async function loadAllDesignations() {
                try {
                    const data = await fetchJson('/institute-admin/reimbursement-assignments/all-designations');
                    allDesignations = data || [];
                    const selects = ['approver-step1', 'approver-step2-1', 'approver-step2-2'];
                    selects.forEach(selectId => {
                        const select = document.getElementById(selectId);
                        if (select) {
                            const currentValue = select.value;
                            select.innerHTML = '<option value="">Select Approver...</option>';
                            allDesignations.forEach(desig => { select.innerHTML += `<option value="${desig.id}">${desig.name}</option>`; });
                            if (currentValue) select.value = currentValue;
                        }
                    });
                } catch (error) { console.error('Error loading designations:', error); }
            }

            async function loadPolicies() {
                try {
                    const result = await fetchJson('/institute-admin/reimbursement-policies');
                    policies = Array.isArray(result) ? result : (result.data || []);
                    // Only show active and upcoming policies (not expired or inactive)
                    policies = policies.filter(p => ['active', 'upcoming'].includes(p.status || 'active'));
                    const policySelect = document.getElementById('assign-policy');
                    policySelect.innerHTML = '<option value="">Select active policy...</option>';
                    const categoryLabels = {
                        'travel': '🚗 Travel', 'accommodation': '🏨 Accommodation', 'food': '🍽️ Food',
                        'mobile': '📱 Mobile', 'internet': '🌐 Internet', 'entertainment': '🎉 Entertainment',
                        'miscellaneous': '📦 Miscellaneous', 'custom': '✨ Custom'
                    };

                    // const statusLabels = {
                    //     'active': '🟢 Active',
                    //     'upcoming': '🔵 Upcoming',
                    //     'expired': '🔴 Expired',
                    //     'inactive': '⚫ Inactive'
                    // };

                    policies.forEach(policy => {
                        const category = policy.policy_category || 'other';
                        const label = categoryLabels[category] || category.toUpperCase();
                        const status = policy.status || 'active';
                        // const statusLabel = statusLabels[status] || status;
                        const policyName = policy.policy_name || 'Unnamed';

                        // Truncate long names
                        const displayName = policyName.length > 40 ? policyName.substring(0, 37) + '...' : policyName;

                        // Add date info for upcoming policies
                        let dateInfo = '';
                        if (status === 'upcoming' && policy.effective_from) {
                            const effDate = new Date(policy.effective_from);
                            dateInfo = ` (Starts: ${effDate.toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' })})`;
                        }

                        policySelect.innerHTML += `<option value="${policy.reimbursement_policy_id}" data-category="${category}" data-status="${status}">
                                                    ${displayName} | ${label} | ${status} ${dateInfo}
                                                </option>`;
                    });
                } catch (error) { console.error('Error loading policies:', error); }
            }

            async function loadAssignments() {
                try {
                    const result = await fetchJson('/institute-admin/reimbursement-assignments/list');
                    assignments = Array.isArray(result) ? result : (result.data || []);
                    renderAssignments();
                } catch (error) { console.error('Error loading assignments:', error); }
            }

            async function loadEmployeeDepartments() {
                try {
                    const data = await fetchJson('/institute-admin/reimbursement-assignments/departments');
                    const empDeptSelect = document.getElementById('assign-emp-dept');
                    empDeptSelect.innerHTML = '<option value="">Select Department</option>';
                    (data || []).forEach(dept => {
                        empDeptSelect.innerHTML += `<option value="${dept.id}">${dept.name}</option>`;
                    });
                } catch (error) { console.error('Error loading employee departments:', error); }
            }

            // ============ UI FUNCTIONS ============
            window.selectAssignmentType = function (type) {
                document.querySelectorAll('.toggle-option').forEach(btn => btn.classList.remove('active'));
                const deptGroup = document.getElementById('department-group');
                const desigGroup = document.getElementById('designation-group');
                const desigSelectGroup = document.getElementById('designation-select-group');
                const employeeGroup = document.getElementById('employee-group');
                const employeeSelectGroup = document.getElementById('employee-select-group');
                const hint = document.getElementById('assignment-type-hint');
                const deptSelect = document.getElementById('assign-dept');
                const categorySelect = document.getElementById('assign-category');
                const desigSelect = document.getElementById('assign-desig');
                const empDeptSelect = document.getElementById('assign-emp-dept');
                const empSelect = document.getElementById('assign-employee');

                deptGroup.style.display = 'none';
                desigGroup.style.display = 'none';
                desigSelectGroup.style.display = 'none';
                employeeGroup.style.display = 'none';
                employeeSelectGroup.style.display = 'none';

                deptSelect.required = false;
                categorySelect.required = false;
                desigSelect.required = false;
                empDeptSelect.required = false;
                empSelect.required = false;
                deptSelect.value = '';
                categorySelect.value = '';
                desigSelect.value = '';
                empDeptSelect.value = '';
                empSelect.value = '';

                if (type === 'department') {
                    document.querySelector('[data-type="department"]').classList.add('active');
                    deptGroup.style.display = 'block';
                    deptSelect.required = true;
                    hint.innerHTML = '<i class="fas fa-info-circle"></i> Department Level: Policy applies to ALL members of the selected department';
                    document.getElementById('policy-step-num').textContent = '3';
                    document.getElementById('range-step-num').textContent = '4';
                    document.getElementById('approval-step-num').textContent = '5';
                } else if (type === 'designation') {
                    document.querySelector('[data-type="designation"]').classList.add('active');
                    desigGroup.style.display = 'block';
                    desigSelectGroup.style.display = 'block';
                    categorySelect.required = true;
                    desigSelect.required = true;
                    hint.innerHTML = '<i class="fas fa-info-circle"></i> Designation Level: Policy applies to specific designation';
                    document.getElementById('policy-step-num').textContent = '4';
                    document.getElementById('range-step-num').textContent = '5';
                    document.getElementById('approval-step-num').textContent = '6';
                    if (categories.length === 0) loadDepartmentCategories();
                } else if (type === 'employee') {
                    document.querySelector('[data-type="employee"]').classList.add('active');
                    employeeGroup.style.display = 'block';
                    employeeSelectGroup.style.display = 'block';
                    empDeptSelect.required = true;
                    empSelect.required = true;
                    hint.innerHTML = '<i class="fas fa-info-circle"></i> Employee Level: Policy applies to a specific employee';
                    document.getElementById('policy-step-num').textContent = '4';
                    document.getElementById('range-step-num').textContent = '5';
                    document.getElementById('approval-step-num').textContent = '6';
                    loadEmployeeDepartments();
                }
                resetPolicySelection();
            };

            window.handleDepartmentChange = function () { resetPolicySelection(); };

            window.handleCategoryChange = function (categoryId) {
                if (categoryId) { loadDesignationsByCategory(categoryId); }
                else { document.getElementById('assign-desig').innerHTML = '<option value="">Select Designation</option>'; document.getElementById('assign-desig').disabled = true; }
                resetPolicySelection();
            };

            window.handleEmployeeDepartmentChange = async function (departmentId) {
                const empSelect = document.getElementById('assign-employee');
                empSelect.innerHTML = '<option value="">Select Employee</option>';
                empSelect.disabled = true;
                if (!departmentId) return;
                try {
                    const data = await fetchJson(`/institute-admin/reimbursement-assignments/employees-by-department?department_id=${departmentId}`);
                    (data || []).forEach(emp => {
                        empSelect.innerHTML += `<option value="${emp.employee_id}">${emp.name} (${emp.employee_id})</option>`;
                    });
                    empSelect.disabled = false;
                } catch (error) { console.error('Error loading employees:', error); }
            };

            function resetPolicySelection() {
                document.getElementById('assign-policy').value = '';
                document.getElementById('range-picker-group').style.display = 'none';
                document.getElementById('ranges-container').innerHTML = '';
                currentPolicyData = null;
            }

            window.selectApprovalType = function (type) {
                document.querySelectorAll('.radio-item').forEach(item => item.classList.remove('active'));
                if (type === '1step') {
                    document.getElementById('approval-1step').checked = true;
                    document.getElementById('approval-1step').closest('.radio-item').classList.add('active');
                    document.getElementById('step1-approval').style.display = 'block';
                    document.getElementById('step2-approval').style.display = 'none';
                } else {
                    document.getElementById('approval-2step').checked = true;
                    document.getElementById('approval-2step').closest('.radio-item').classList.add('active');
                    document.getElementById('step1-approval').style.display = 'none';
                    document.getElementById('step2-approval').style.display = 'block';
                }
            };

            // ============ BUILD POLICY META HTML ============
            function buildPolicyMetaHtml(policy) {
                var fy = policy.financial_year || 'N/A';
                var calcType = (policy.calculation_type || 'N/A').replace(/_/g, ' ').replace(/\b\w/g, function (c) { return c.toUpperCase(); });

                var effFrom = policy.effective_from
                    ? new Date(policy.effective_from).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' })
                    : 'N/A';
                var effTo = policy.effective_to
                    ? new Date(policy.effective_to).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' })
                    : 'Ongoing';

                var statusColor = '#10b981';
                var statusText = 'Active';
                var now = new Date();
                now.setHours(0, 0, 0, 0);

                if (policy.effective_from) {
                    var fromDate = new Date(policy.effective_from);
                    fromDate.setHours(0, 0, 0, 0);
                    if (fromDate > now) { statusColor = '#6366f1'; statusText = 'Upcoming'; }
                }
                if (policy.effective_to) {
                    var toDate = new Date(policy.effective_to);
                    toDate.setHours(0, 0, 0, 0);
                    if (toDate < now) { statusColor = '#f59e0b'; statusText = 'Expired'; }
                }
                if (policy.status === 'inactive') { statusColor = '#ef4444'; statusText = 'Inactive'; }

                return `
                                                        <div style="margin-top:10px; padding-top:10px; border-top:1px dashed #e2e8f0; display:grid; grid-template-columns:1fr 1fr; gap:4px 12px; font-size:0.75rem; color:#64748b;">
                                                            <div><strong>📅 FY:</strong> ${fy}</div>
                                                            <div><strong>⚙️ Calc:</strong> ${calcType}</div>
                                                            <div><strong>📆 From:</strong> ${effFrom}</div>
                                                            <div><strong>📆 To:</strong> ${effTo}</div>
                                                            <div style="grid-column:1/-1; margin-top:4px;">
                                                                <strong>Status:</strong> 
                                                                <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:${statusColor}; margin-right:4px;"></span>
                                                                <span style="color:${statusColor}; font-weight:600;">${statusText}</span>
                                                            </div>
                                                        </div>`;
            }

            // ============ HELPER FUNCTIONS FOR TRAVEL RANGES ============
            function getPrivateVehicleRanges(policy) {
                var items = [];
                var vehicles = [
                    { key: 'two_wheeler', name: 'Two Wheeler', rate: 'two_wheeler_rate_km', bill: 'two_wheeler_bill_required', photo: 'two_wheeler_photo_required' },
                    { key: 'car', name: 'Car', rate: 'car_rate_km', bill: 'car_bill_required', photo: 'car_photo_required' },
                    { key: 'auto', name: 'Auto', rate: 'auto_rate_km', bill: 'auto_bill_required', photo: 'auto_photo_required' },
                ];
                vehicles.forEach(function (v) {
                    if (policy[v.key + '_min'] || policy[v.key + '_max']) {
                        items.push({
                            name: v.name,
                            min: parseFloat(policy[v.key + '_min']) || 0,
                            max: parseFloat(policy[v.key + '_max']) || 0,
                            rate: policy[v.rate] || null,
                            bill: policy[v.bill] || 'Yes',
                            photo: policy[v.photo] || 'No',
                            type: 'vehicle'
                        });
                    }
                });
                return items;
            }

            function getBusRanges(policy) {
                var items = [];
                if (policy.bus_categories) {
                    var busCat = typeof policy.bus_categories === 'string' ? JSON.parse(policy.bus_categories) : policy.bus_categories;
                    var names = { 'general': 'General Bus', 'seater_ac': 'Seater AC', 'seater_nonac': 'Seater Non-AC', 'sleeper_ac': 'Sleeper AC', 'sleeper_nonac': 'Sleeper Non-AC' };
                    Object.keys(busCat).forEach(function (key) {
                        if (busCat[key] && (busCat[key].min || busCat[key].max)) {
                            items.push({
                                name: names[key] || key,
                                min: parseFloat(busCat[key].min) || 0,
                                max: parseFloat(busCat[key].max) || 0,
                                bill: busCat[key].bill_required || 'Yes',
                                photo: busCat[key].photo_required || 'No',
                                type: 'bus'
                            });
                        }
                    });
                }
                return items;
            }

            function getTrainRanges(policy) {
                var items = [];
                if (policy.train_categories) {
                    var trainCat = typeof policy.train_categories === 'string' ? JSON.parse(policy.train_categories) : policy.train_categories;
                    var names = { 'general': 'General', 'sleeper': 'Sleeper Class', 'ac3': '3AC', 'ac2': '2AC', 'ac1': '1AC' };
                    Object.keys(trainCat).forEach(function (key) {
                        if (trainCat[key] && (trainCat[key].min || trainCat[key].max)) {
                            items.push({
                                name: names[key] || key,
                                min: parseFloat(trainCat[key].min) || 0,
                                max: parseFloat(trainCat[key].max) || 0,
                                bill: trainCat[key].bill_required || 'Yes',
                                photo: trainCat[key].photo_required || 'No',
                                type: 'train'
                            });
                        }
                    });
                }
                return items;
            }

            function getFlightRanges(policy) {
                var items = [];
                if (policy.flight_categories) {
                    var flightCat = typeof policy.flight_categories === 'string' ? JSON.parse(policy.flight_categories) : policy.flight_categories;
                    var names = { 'economy': 'Economy', 'business': 'Business Class' };
                    Object.keys(flightCat).forEach(function (key) {
                        if (flightCat[key] && (flightCat[key].min || flightCat[key].max)) {
                            items.push({
                                name: names[key] || key,
                                min: parseFloat(flightCat[key].min) || 0,
                                max: parseFloat(flightCat[key].max) || 0,
                                bill: flightCat[key].bill_required || 'Yes',
                                photo: flightCat[key].photo_required || 'No',
                                type: 'flight'
                            });
                        }
                    });
                }
                return items;
            }

            window.switchTransportTab = function (tab, btn) {
                document.querySelectorAll('.transport-tab').forEach(function (t) {
                    t.style.background = 'transparent';
                    t.style.color = '#64748b';
                });
                btn.style.background = 'white';
                btn.style.color = '#2563eb';
                document.querySelectorAll('.transport-tab-content').forEach(function (panel) {
                    panel.style.display = 'none';
                });
                var targetPanel = document.getElementById('transport-tab-' + tab);
                if (targetPanel) {
                    targetPanel.style.display = 'block';
                }
            };

            // ============ LOAD POLICY DETAILS ============
            window.loadPolicyDetails = async function (policyId) {
                const container = document.getElementById('ranges-container');
                const group = document.getElementById('range-picker-group');
                container.innerHTML = '';
                group.style.display = 'none';
                currentPolicyData = null;
                if (!policyId) return;

                const selectedOption = document.querySelector(`#assign-policy option[value="${policyId}"]`);
                const category = selectedOption?.dataset?.category || '';
                const status = selectedOption?.dataset?.status || 'active';
                const policy = policies.find(p => p.reimbursement_policy_id == policyId);

                if (!policy) {
                    container.innerHTML = '<div style="color: #94a3b8; padding: 1rem;">Policy not found</div>';
                    group.style.display = 'block';
                    return;
                }

                // Show warning for upcoming policies
                if (status === 'upcoming') {
                    const warningHtml = `
                                    <div class="policy-info-card" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-color: #93c5fd;">
                                        <div style="display: flex; align-items: center; gap: 10px; color: #1e40af;">
                                            <i class="fas fa-clock" style="font-size: 1.2rem;"></i>
                                            <div>
                                                <strong>Upcoming Policy</strong>
                                                <div style="font-size: 0.8rem; margin-top: 2px;">
                                                    This policy will become active on ${policy.effective_from ? new Date(policy.effective_from).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' }) : 'a future date'}.
                                                    You can assign it now, but claims won't be allowed until activation.
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                    container.innerHTML = warningHtml;
                }

                const fixedCategories = ['mobile', 'internet', 'entertainment', 'miscellaneous'];
                const readonlyCategories = ['travel', 'accommodation', 'food'];
                const isFixedPolicy = fixedCategories.includes(category);
                const isReadonlyPolicy = readonlyCategories.includes(category);
                let html = '';

                if (isFixedPolicy) {
                    const minAmt = parseFloat(policy.min_amount) || 0;
                    const maxAmt = parseFloat(policy.max_amount) || 5000;
                    html = `
                                                            <div class="policy-info-card">
                                                                <div class="policy-header">
                                                                    <div><span class="policy-name">${policy.policy_name || 'Policy'}</span><span class="policy-meta" style="margin-left:10px;">(${category})</span></div>
                                                                    <span class="editable-badge"><i class="fas fa-edit"></i> Editable Limits</span>
                                                                </div>
                                                                <div style="font-size:0.8rem;color:#64748b;">Default Policy Range: <strong>₹${minAmt} - ₹${maxAmt}</strong></div>
                                                                ${policy.remarks ? '<div style="font-size:0.75rem;color:#64748b;margin-top:4px;">Remarks: ' + policy.remarks + '</div>' : ''}
                                                                ${buildPolicyMetaHtml(policy)}
                                                            </div>
                                                            <div class="editable-range-box editing">
                                                                <div style="font-weight:700;color:#1e293b;margin-bottom:1rem;"><i class="fas fa-sliders-h" style="color:#2563eb;"></i> Set Assignment-Specific Limits</div>
                                                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                                                                    <div class="form-group"><label>Minimum Amount (₹)</label><input type="number" id="range_min" class="form-control" value="${minAmt}" min="0" max="${maxAmt}"><span style="font-size:0.7rem;color:#94a3b8;">Policy min: ₹${minAmt}</span></div>
                                                                    <div class="form-group"><label>Maximum Amount (₹) *</label><input type="number" id="range_max" class="form-control" value="${maxAmt}" min="${minAmt}" max="${maxAmt}"><span style="font-size:0.7rem;color:#94a3b8;">Policy max: ₹${maxAmt}</span></div>
                                                                </div>
                                                                <div style="display:flex;gap:1rem;margin-top:10px;font-size:0.78rem;color:#475569;">
                                                                    <span>📄 Bill Required: <strong>${policy.bill_required || 'Yes'}</strong></span>
                                                                    <span>📷 Photo Required: <strong>${policy.photo_required || 'No'}</strong></span>
                                                                    <span>💰 Actual Amount: <strong style="color:${policy.allow_actual_amount && policy.allow_actual_amount !== 'No' ? '#059669' : '#dc2626'};">${policy.allow_actual_amount && policy.allow_actual_amount !== 'No' ? 'Allowed' : 'Not Allowed'}</strong></span>
                                                                </div>
                                                                <div style="background:#fef3c7;padding:10px 14px;border-radius:10px;margin-top:1rem;font-size:0.8rem;color:#92400e;"><i class="fas fa-lightbulb"></i> <strong>Tip:</strong> Set lower limits for specific departments.</div>
                                                            </div>
                                                            <input type="hidden" name="allowed-ranges" id="allowed-ranges-input" value='${JSON.stringify({ name: policy.policy_name, min: minAmt, max: maxAmt, type: 'fixed', is_editable: true })}'>`;
                    currentPolicyData = { policy, isEditable: true, minAmt, maxAmt };
                } else if (isReadonlyPolicy) {
                    html = `<div class="policy-info-card"><div class="policy-header"><div><span class="policy-name">${policy.policy_name || 'Policy'}</span><span class="policy-meta" style="margin-left:10px;">(${category})</span></div><span class="view-only-badge"><i class="fas fa-lock"></i> View Only</span></div>${buildPolicyMetaHtml(policy)}</div>`;

                    if (category === 'travel') {
                        currentPolicyData = {
                            policy: policy, isReadonly: true,
                            transportRanges: {
                                private: getPrivateVehicleRanges(policy),
                                bus: getBusRanges(policy),
                                train: getTrainRanges(policy),
                                flight: getFlightRanges(policy)
                            }
                        };

                        var transportTabs = [
                            { key: 'private', label: '🚗 Private', ranges: currentPolicyData.transportRanges.private },
                            { key: 'bus', label: '🚌 Bus', ranges: currentPolicyData.transportRanges.bus },
                            { key: 'train', label: '🚂 Train', ranges: currentPolicyData.transportRanges.train },
                            { key: 'flight', label: '✈️ Flight', ranges: currentPolicyData.transportRanges.flight }
                        ];

                        html += `<div style="display:flex;gap:4px;background:#f1f5f9;padding:4px;border-radius:10px;margin-bottom:12px;overflow-x:auto;">`;
                        transportTabs.forEach(function (tab, idx) {
                            var isActive = idx === 0;
                            html += `<button type="button" class="transport-tab ${isActive ? 'active' : ''}" 
                                                                        onclick="switchTransportTab('${tab.key}',this)" 
                                                                        style="flex:1;padding:6px 10px;border:none;border-radius:8px;font-size:0.75rem;font-weight:600;cursor:pointer;background:${isActive ? 'white' : 'transparent'};color:${isActive ? '#2563eb' : '#64748b'};white-space:nowrap;">
                                                                        ${tab.label}</button>`;
                        });
                        html += `</div>`;

                        transportTabs.forEach(function (tab, idx) {
                            var isActive = idx === 0;
                            var tabHtml = '';
                            if (tab.ranges.length > 0) {
                                tab.ranges.forEach(function (range) {
                                    tabHtml += `
                                                                            <div class="range-override-box" style="background: #f8fafc; padding: 10px 14px; margin-bottom: 6px;">
                                                                                <div class="range-label">
                                                                                    <input type="checkbox" name="allowed-ranges" value='${JSON.stringify(range).replace(/'/g, "&#39;")}' checked>
                                                                                    <span style="margin-left: 8px; font-weight: 600; font-size: 0.82rem;">${range.name}</span>
                                                                                </div>
                                                                                <div style="display: flex; gap: 1rem; margin-top: 4px; font-size: 0.78rem; color: #475569; flex-wrap: wrap;">
                                                                                    <span>Min: <strong style="color:#1e293b;">₹${range.min || 0}</strong></span>
                                                                                    <span>Max: <strong style="color:#1e293b;">₹${range.max || 0}</strong></span>
                                                                                    ${range.rate ? '<span>Rate: <strong style="color:#7c3aed;">₹' + range.rate + '/KM</strong></span>' : ''}
                                                                                    ${range.bill ? '<span style="font-size:0.7rem;">📄 Bill: ' + range.bill + '</span>' : ''}
                                                                                    ${range.photo ? '<span style="font-size:0.7rem;">📷 Photo: ' + range.photo + '</span>' : ''}
                                                                                    <span>💰 Actual Amount: <strong style="color:${policy.allow_actual_amount && policy.allow_actual_amount !== 'No' ? '#059669' : '#dc2626'};">${policy.allow_actual_amount && policy.allow_actual_amount !== 'No' ? 'Allowed' : 'Not Allowed'}</strong></span>
                                                                                </div>
                                                                            </div>`;
                                });
                            } else {
                                tabHtml = '<div style="text-align:center;color:#94a3b8;padding:1rem;">No ranges configured</div>';
                            }
                            html += `<div id="transport-tab-${tab.key}" class="transport-tab-content" style="display:${isActive ? 'block' : 'none'};">${tabHtml}</div>`;
                        });
                    } else {
                        var ranges = getPolicyRanges(policy, category);
                        currentPolicyData = { policy, isReadonly: true, ranges };
                        ranges.forEach(function (range) {
                            html += `
                                                                    <div class="range-override-box" style="background:#f8fafc;">
                                                                        <div class="range-label"><input type="checkbox" name="allowed-ranges" value='${JSON.stringify(range)}' checked><span style="margin-left:8px;font-weight:600;">${range.name}</span></div>
                                                                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;margin-top:0.3rem;font-size:0.78rem;">
                                                                            <div>Min: <strong>₹${range.min || 0}</strong></div><div>Max: <strong>₹${range.max || 0}</strong></div>
                                                                        </div>
                                                                        <div style="font-size:0.78rem;color:#475569;margin-top:4px;">
                                                                            <span>💰 Actual Amount: <strong style="color:${policy.allow_actual_amount && policy.allow_actual_amount !== 'No' ? '#059669' : '#dc2626'};">${policy.allow_actual_amount && policy.allow_actual_amount !== 'No' ? 'Allowed' : 'Not Allowed'}</strong></span>
                                                                        </div>
                                                                        ${range.details ? '<div style="font-size:0.7rem;color:#64748b;margin-top:2px;">' + range.details + '</div>' : ''}
                                                                    </div>`;
                        });
                    }
                } else if (category === 'custom') {
                    let policyData = policy.policy_data;
                    if (typeof policyData === 'string') { try { policyData = JSON.parse(policyData); } catch (e) { policyData = []; } }
                    html = `<div class="policy-info-card"><div class="policy-header"><div><span class="policy-name">${policy.policy_name || 'Policy'}</span><span class="policy-meta" style="margin-left:10px;">(custom)</span></div><span class="editable-badge"><i class="fas fa-edit"></i> Custom Rules</span></div>${buildPolicyMetaHtml(policy)}</div>`;

                    if (Array.isArray(policyData) && policyData.length > 0) {
                        policyData.forEach(function (rule) {
                            var ruleName = rule.name || 'Rule';
                            var ruleType = rule.type || 'actual';
                            var fields = rule.fields || {};
                            var fieldDetails = '';
                            var typeFieldMap = {
                                'actual': ['min', 'max'],
                                'fixed': ['amount'],
                                'per_km': ['rate_km', 'min_km', 'max_km'],
                                'per_day': ['rate_day', 'max_days'],
                                'per_night': ['rate_night', 'max_nights'],
                                'per_hour': ['rate_hour', 'max_hours'],
                                'per_unit': ['rate_unit', 'unit', 'max_units'],
                                'percentage': ['rate_percentage', 'max_amount']
                            };
                            var fieldsToShow = typeFieldMap[ruleType] || Object.keys(fields);
                            var fieldParts = [];
                            fieldsToShow.forEach(function (key) {
                                if (fields[key] && fields[key] !== '' && fields[key] !== '0') {
                                    var label = key.replace(/_/g, ' ');
                                    fieldParts.push('<strong>' + label + ':</strong> ' + fields[key]);
                                }
                            });
                            fieldDetails = fieldParts.join(' | ');

                            html += `
                                                                    <div class="editable-range-box">
                                                                        <div style="font-weight:600;color:#1e293b;margin-bottom:0.5rem;">${ruleName} <span class="badge-pill" style="background:#e2e8f0;color:#64748b;font-size:0.7rem;">${ruleType.replace(/_/g, ' ')}</span></div>
                                                                        ${fieldDetails ? '<div style="font-size:0.78rem;color:#475569;margin-bottom:6px;">' + fieldDetails + '</div>' : '<div style="font-size:0.75rem;color:#94a3b8;margin-bottom:6px;">No values configured</div>'}
                                                                        <div style="display:flex;gap:1rem;font-size:0.7rem;color:#64748b;margin-bottom:6px;">
                                                                            <span>📄 Bill: <strong>${rule.bill_required || 'Yes'}</strong></span>
                                                                            <span>📷 Photo: <strong>${rule.photo_required || 'No'}</strong></span>
                                                                            <span>💰 Actual Amount: <strong style="color:${policy.allow_actual_amount && policy.allow_actual_amount !== 'No' ? '#059669' : '#dc2626'};">${policy.allow_actual_amount && policy.allow_actual_amount !== 'No' ? 'Allowed' : 'Not Allowed'}</strong></span>
                                                                        </div>
                                                                        <div style="display:flex;">
                                                                            <input type="checkbox" name="allowed-rules" value='${JSON.stringify(rule).replace(/'/g, "&#39;")}' checked>
                                                                            <label style="font-size:0.8rem;margin-left:4px;">Include this rule in assignment</label>
                                                                        </div>
                                                                    </div>`;
                        });
                    } else {
                        html += '<div style="color:#94a3b8;padding:1rem;text-align:center;">No custom rules defined</div>';
                    }
                    currentPolicyData = { policy, isCustom: true, policyData };
                }

                container.innerHTML = html;
                group.style.display = 'block';

                if (category === 'travel') {
                    setTimeout(function () {
                        var firstTab = document.querySelector('.transport-tab');
                        if (firstTab) firstTab.click();
                    }, 50);
                }
            };

            function getPolicyRanges(policy, category) {
                var ranges = [];
                if (category === 'accommodation') {
                    ['basic', 'deluxe', 'premium'].forEach(function (type) {
                        var names = { basic: '1-2 Star (Budget)', deluxe: '3-4 Star (Business)', premium: '5 Star (Premium)' };
                        ranges.push({
                            name: names[type], min: parseFloat(policy[type + '_min']) || 0, max: parseFloat(policy[type + '_max']) || 0,
                            type: 'room', details: policy[type + '_includes_food'] ? '🍽️ Includes Food' : ''
                        });
                    });
                } else if (category === 'food') {
                    ['breakfast', 'lunch', 'dinner', 'two_meals', 'three_meals'].forEach(function (key) {
                        if (policy[key + '_min'] || policy[key + '_max']) {
                            var names = { breakfast: 'Breakfast', lunch: 'Lunch', dinner: 'Dinner', two_meals: 'Two Meals', three_meals: 'Three Meals' };
                            ranges.push({ name: names[key], min: parseFloat(policy[key + '_min']) || 0, max: parseFloat(policy[key + '_max']) || 0, type: 'meal' });
                        }
                    });
                }
                return ranges;
            }

            // ============ CREATE ASSIGNMENT ============
            window.createAssignment = async function (e) {
                e.preventDefault();
                var assignmentType = document.querySelector('.toggle-option.active')?.dataset.type || 'department';
                var policyId = document.getElementById('assign-policy').value;
                var notes = document.getElementById('assign-notes').value.trim();
                var selectedOption = document.querySelector(`#assign-policy option[value="${policyId}"]`);
                var category = selectedOption?.dataset?.category || '';
                if (!policyId) { showToast('Please select a policy', 'error'); return false; }

                var approvalType = document.querySelector('input[name="approval-type"]:checked')?.value || '1step';
                var approvers = [];
                if (approvalType === '1step') {
                    var approver = document.getElementById('approver-step1').value;
                    if (!approver) { showToast('Please select an approver', 'error'); return false; }
                    approvers = [approver];
                } else {
                    var a1 = document.getElementById('approver-step2-1').value;
                    var a2 = document.getElementById('approver-step2-2').value;
                    if (!a1 || !a2) { showToast('Please select both approvers', 'error'); return false; }
                    if (a1 === a2) { showToast('Approvers cannot be same', 'error'); return false; }
                    approvers = [a1, a2];
                }

                var fixedCategories = ['mobile', 'internet', 'entertainment', 'miscellaneous'];
                var allowedRanges = [];
                if (fixedCategories.includes(category)) {
                    var minInput = document.getElementById('range_min');
                    var maxInput = document.getElementById('range_max');
                    if (!minInput || !maxInput) { showToast('Range inputs not found', 'error'); return false; }
                    var min = parseFloat(minInput.value) || 0;
                    var max = parseFloat(maxInput.value) || 0;
                    var policyMax = parseFloat(maxInput.max) || 5000;
                    if (max > policyMax) { showToast('Max exceeds policy limit', 'error'); return false; }
                    if (min > max) { showToast('Min cannot exceed max', 'error'); return false; }
                    allowedRanges = [{ name: 'General Limit', min: min, max: max, type: 'fixed', is_overridden: true }];
                } else {
                    document.querySelectorAll('input[name="allowed-ranges"]:checked, input[name="allowed-rules"]:checked').forEach(function (cb) {
                        try { allowedRanges.push(JSON.parse(cb.value)); } catch { allowedRanges.push(cb.value); }
                    });
                    if (!allowedRanges.length) { showToast('Select at least one range', 'error'); return false; }
                }

                var policy = policies.find(p => p.reimbursement_policy_id == policyId);
                var payload = {
                    assignment_type: assignmentType, reimbursement_policy_id: policyId,
                    policy_name: policy?.policy_name || '', policy_category: category,
                    allowed_ranges: allowedRanges, approval_type: approvalType, approvers: approvers, notes: notes
                };

                if (assignmentType === 'department') {
                    var dept = document.getElementById('assign-dept').value;
                    if (!dept) { showToast('Select a department', 'error'); return false; }
                    payload.department_id = dept;
                } else if (assignmentType === 'designation') {
                    var cat = document.getElementById('assign-category').value;
                    var desig = document.getElementById('assign-desig').value;
                    if (!cat || !desig) { showToast('Select category and designation', 'error'); return false; }
                    payload.department_category_id = cat;
                    payload.designation_id = desig;
                } else if (assignmentType === 'employee') {
                    var empDept = document.getElementById('assign-emp-dept').value;
                    var empId = document.getElementById('assign-employee').value;
                    if (!empDept || !empId) { showToast('Select department and employee', 'error'); return false; }
                    payload.department_id = empDept;
                    payload.employee_id = empId;
                }

                try {
                    var result = await fetchJson('/institute-admin/reimbursement-assignments/store', { method: 'POST', body: JSON.stringify(payload) });
                    showToast(result.message || 'Created!', 'success');
                    document.getElementById('assign-policy').value = '';
                    document.getElementById('range-picker-group').style.display = 'none';
                    document.getElementById('ranges-container').innerHTML = '';
                    document.getElementById('assign-notes').value = '';
                    currentPolicyData = null;
                    await loadAssignments();
                } catch (error) { showToast(error.message || 'Failed', 'error'); }
                return false;
            };

            // ============ RENDER ASSIGNMENTS TABLE ============
            function renderAssignments() {
                var body = document.getElementById('assignments-table-body');
                var emptyState = document.getElementById('assignments-empty-state');
                body.innerHTML = '';
                document.getElementById('assignment-count').textContent = assignments.length;
                document.getElementById('assignment-count-table').textContent = assignments.length;
                if (!assignments.length) { emptyState.style.display = 'block'; return; }
                emptyState.style.display = 'none';

                assignments.forEach(function (a, index) {
                    var tr = document.createElement('tr');
                    var typeBadge;
                    if (a.assignment_type === 'designation') {
                        typeBadge = '<span class="badge-pill" style="background:#fae8ff;color:#7c3aed;">👤 Designation</span>';
                    } else if (a.assignment_type === 'employee') {
                        typeBadge = '<span class="badge-pill" style="background:#fef3c7;color:#d97706;">👤 Employee</span>';
                    } else {
                        typeBadge = '<span class="badge-pill" style="background:#dbeafe;color:#2563eb;">🏢 Department</span>';
                    }

                    var departmentDisplay;
                    if (a.assignment_type === 'designation') {
                        departmentDisplay = categories.find(c => c.id == a.department_category_id)?.name || a.department_category_id || 'N/A';
                    } else if (a.assignment_type === 'employee') {
                        departmentDisplay = a.department_name || a.department_id || 'N/A';
                    } else {
                        departmentDisplay = a.department_name || a.department_id || 'All Departments';
                    }

                    var designationDisplay;
                    if (a.assignment_type === 'department') {
                        designationDisplay = '<span class="badge-pill" style="background:#e0e7ff;color:#3730a3;">👥 All</span>';
                    } else if (a.assignment_type === 'employee') {
                        designationDisplay = `<span class="badge-pill" style="background:#fef3c7;color:#d97706;">👤 ${a.employee_name || a.employee_id || 'N/A'}</span>`;
                    } else {
                        designationDisplay = `<span class="badge-pill" style="background:#f1f5f9;color:#475569;">${a.designation_name || 'N/A'}</span>`;
                    }

                    var rangesDisplay = '';
                    if (a.allowed_ranges) {
                        var ranges = typeof a.allowed_ranges === 'string' ? JSON.parse(a.allowed_ranges) : a.allowed_ranges;
                        if (Array.isArray(ranges) && ranges.length > 0) {
                            var rangeHtmlParts = [];
                            ranges.forEach(function (r) {
                                if (typeof r === 'object') {
                                    var name = r.name || '';
                                    var ruleType = r.type || '';
                                    var min = r.min || r.min_amount || '';
                                    var max = r.max || r.max_amount || '';
                                    var icon = '📌';
                                    switch (ruleType) {
                                        case 'vehicle': icon = '🚗'; break;
                                        case 'bus': icon = '🚌'; break;
                                        case 'train': icon = '🚂'; break;
                                        case 'flight': icon = '✈️'; break;
                                        case 'fixed': icon = '📋'; break;
                                        case 'meal': icon = '🍽️'; break;
                                        case 'room': icon = '🏨'; break;
                                        case 'actual': icon = '💵'; break;
                                        case 'per_km': icon = '🛣️'; break;
                                        case 'per_night': icon = '🌙'; break;
                                        case 'per_day': icon = '📅'; break;
                                        case 'per_hour': icon = '⏰'; break;
                                        case 'per_unit': icon = '📦'; break;
                                        case 'percentage': icon = '💯'; break;
                                    }
                                    var displayText = icon + ' ';
                                    if (name) {
                                        var label = name.length > 15 ? name.substring(0, 15) + '...' : name;
                                        displayText += label;
                                    }
                                    if (min && max) displayText += ' ₹' + min + '-' + max;
                                    var rateValue = r.rate || r.rate_km || r.rate_night || r.rate_day || r.rate_hour || r.rate_unit || r.rate_percentage || '';
                                    if (rateValue) displayText += ' @₹' + rateValue;
                                    var titleText = name;
                                    if (min && max) titleText += ': ₹' + min + ' - ₹' + max;
                                    if (rateValue) titleText += ' (Rate: ₹' + rateValue + ')';
                                    rangeHtmlParts.push(
                                        `<span class="range-badge" style="${r.is_overridden ? 'background:#fef3c7;color:#d97706;border-color:#fcd34d;' : ''}" title="${titleText}">${displayText}${r.is_overridden ? ' ⚡' : ''}</span>`
                                    );
                                } else {
                                    rangeHtmlParts.push('<span class="range-badge">📌 ' + r + '</span>');
                                }
                            });
                            var maxShow = 4;
                            var shown = rangeHtmlParts.slice(0, maxShow);
                            var remaining = rangeHtmlParts.length - maxShow;
                            rangesDisplay = shown.join(' ');
                            if (remaining > 0) rangesDisplay += ` <span class="range-badge" style="background:#e2e8f0;color:#64748b;">+${remaining} more</span>`;
                        }
                    }

                    var approversDisplay = '';
                    if (a.approvers) {
                        var approvers = typeof a.approvers === 'string' ? JSON.parse(a.approvers) : a.approvers;
                        if (Array.isArray(approvers)) {
                            approversDisplay = approvers.map(function (app) {
                                return '<span class="badge-pill" style="background:#d1fae5;color:#059669;margin:2px;">' + app + '</span>';
                            }).join(' → ');
                        }
                    }

                    tr.innerHTML = `
                                                            <td>${typeBadge}</td>
                                                            <td style="font-weight:700;color:#1e293b;">${departmentDisplay}</td>
                                                            <td>${designationDisplay}</td>
                                                            <td>
                                                                <div style="font-weight:600;color:#2563eb;">${a.policy_name || 'Policy'}</div>
                                                                <span style="font-size:0.7rem;color:#64748b;">${a.policy_category || 'other'}</span>
                                                                ${a.financial_year ? '<div style="font-size:0.65rem;color:#94a3b8;margin-top:2px;">📅 ' + a.financial_year + '</div>' : ''}
                                                            </td>
                                                            <td><div style="display:flex;gap:4px;flex-wrap:wrap;">${rangesDisplay || '-'}</div></td>
                                                            <td><div style="display:flex;flex-direction:column;gap:2px;">${approversDisplay || '<span class="badge-pill" style="background:#fef3c7;color:#d97706;">Pending</span>'}<span style="font-size:0.65rem;color:#94a3b8;">${a.approval_type || '1step'}</span></div></td>
                                                            <td style="text-align:center;"><div style="display:flex;gap:4px;justify-content:center;">
                                                                <button class="btn-action-delete" onclick="viewAssignmentDetail('${index}')" title="View" style="border-color:#bfdbfe;color:#2563eb;"><i class="fas fa-eye"></i></button>
                                                                <button class="btn-action-delete" onclick="deleteAssignment('${a.id}')" title="Remove"><i class="fas fa-trash-alt"></i></button>
                                                            </div></td>`;
                    body.appendChild(tr);
                });
            }

            // ============ VIEW ASSIGNMENT DETAIL ============
            window.viewAssignmentDetail = function (index) {
                var a = assignments[index]; if (!a) return;

                var rangesHtml = '';
                if (a.allowed_ranges) {
                    var ranges = typeof a.allowed_ranges === 'string' ? JSON.parse(a.allowed_ranges) : a.allowed_ranges;
                    if (Array.isArray(ranges) && ranges.length > 0) {
                        rangesHtml = '<div style="display:flex;flex-direction:column;gap:8px;">';
                        ranges.forEach(function (r, i) {
                            if (typeof r === 'object') {
                                var name = r.name || ('Range ' + (i + 1));
                                var type = r.type || '';
                                var isOverridden = r.is_overridden;
                                var bill = r.bill || r.bill_required || '-';
                                var photo = r.photo || r.photo_required || '-';
                                var fields = r.fields || {};
                                var min = r.min || fields.min || fields.min_km || '0';
                                var max = r.max || fields.max || fields.max_km || fields.max_nights || fields.max_days || fields.max_hours || fields.max_units || fields.max_amount || fields.amount || '0';
                                var rate = r.rate || fields.rate_km || fields.rate_night || fields.rate_day || fields.rate_hour || fields.rate_unit || fields.rate_percentage || '';
                                var unit = fields.unit || '';
                                var fieldDetailsHtml = '';
                                if (type && ['actual', 'fixed', 'per_km', 'per_night', 'per_day', 'per_hour', 'per_unit', 'percentage'].includes(type)) {
                                    var detailParts = [];
                                    if (type === 'fixed' && fields.amount) detailParts.push('<span>💰 Fixed Amount: <strong>₹' + fields.amount + '</strong></span>');
                                    else if (type === 'actual') {
                                        if (fields.min) detailParts.push('<span>Min: <strong>₹' + fields.min + '</strong></span>');
                                        if (fields.max) detailParts.push('<span>Max: <strong>₹' + fields.max + '</strong></span>');
                                    } else if (type === 'per_night') {
                                        if (fields.rate_night) detailParts.push('<span>Rate: <strong>₹' + fields.rate_night + '/Night</strong></span>');
                                        if (fields.max_nights) detailParts.push('<span>Max Nights: <strong>' + fields.max_nights + '</strong></span>');
                                    } else if (type === 'per_km') {
                                        if (fields.rate_km) detailParts.push('<span>Rate: <strong>₹' + fields.rate_km + '/KM</strong></span>');
                                        if (fields.min_km) detailParts.push('<span>Min KM: <strong>' + fields.min_km + '</strong></span>');
                                        if (fields.max_km) detailParts.push('<span>Max KM: <strong>' + fields.max_km + '</strong></span>');
                                    } else if (type === 'per_day') {
                                        if (fields.rate_day) detailParts.push('<span>Rate: <strong>₹' + fields.rate_day + '/Day</strong></span>');
                                        if (fields.max_days) detailParts.push('<span>Max Days: <strong>' + fields.max_days + '</strong></span>');
                                    } else if (type === 'per_hour') {
                                        if (fields.rate_hour) detailParts.push('<span>Rate: <strong>₹' + fields.rate_hour + '/Hour</strong></span>');
                                        if (fields.max_hours) detailParts.push('<span>Max Hours: <strong>' + fields.max_hours + '</strong></span>');
                                    } else if (type === 'per_unit') {
                                        if (fields.rate_unit) detailParts.push('<span>Rate: <strong>₹' + fields.rate_unit + '/' + (unit || 'Unit') + '</strong></span>');
                                        if (fields.unit) detailParts.push('<span>Unit: <strong>' + fields.unit + '</strong></span>');
                                        if (fields.max_units) detailParts.push('<span>Max Units: <strong>' + fields.max_units + '</strong></span>');
                                    } else if (type === 'percentage') {
                                        if (fields.rate_percentage) detailParts.push('<span>Rate: <strong>' + fields.rate_percentage + '%</strong></span>');
                                        if (fields.max_amount) detailParts.push('<span>Max Amount: <strong>₹' + fields.max_amount + '</strong></span>');
                                    }
                                    fieldDetailsHtml = detailParts.length > 0 ?
                                        '<div style="display:flex;gap:1rem;flex-wrap:wrap;margin-top:4px;font-size:0.78rem;color:#475569;">' + detailParts.join('') + '</div>' : '';
                                }
                                rangesHtml += `
                                                                        <div class="range-override-box" style="${isOverridden ? 'border-color:#f59e0b;background:#fffbeb;' : 'background:#f8fafc;'} padding:12px 16px;">
                                                                            <div class="range-label" style="display:flex;justify-content:space-between;align-items:center;">
                                                                                <strong style="font-size:0.9rem;">${name}</strong>
                                                                                <div style="display:flex;gap:8px;">
                                                                                    ${type ? '<span class="badge-pill" style="background:#e2e8f0;color:#64748b;font-size:0.65rem;">' + type.replace(/_/g, ' ') + '</span>' : ''}
                                                                                    ${isOverridden ? '<span class="editable-badge" style="font-size:0.65rem;">⚡ Customized</span>' : ''}
                                                                                </div>
                                                                            </div>
                                                                            ${fieldDetailsHtml}
                                                                            ${!fieldDetailsHtml ? `
                                                                            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-top:8px;font-size:0.82rem;">
                                                                                <div><span style="color:#64748b;">Min:</span> <strong>₹${min}</strong></div>
                                                                                <div><span style="color:#64748b;">Max:</span> <strong>₹${max}</strong></div>
                                                                                ${rate ? '<div><span style="color:#64748b;">Rate:</span> <strong style="color:#7c3aed;">₹' + rate + '</strong></div>' : '<div></div>'}
                                                                            </div>` : ''}
                                                                            <div style="display:flex;gap:1.5rem;margin-top:6px;font-size:0.72rem;color:#64748b;border-top:1px dashed #e2e8f0;padding-top:8px;">
                                                                                <span>📄 Bill: <strong>${bill}</strong></span>
                                                                                <span>📷 Photo: <strong>${photo}</strong></span>
                                                                            </div>
                                                                        </div>`;
                            } else {
                                rangesHtml += '<div class="range-override-box" style="background:#f8fafc;padding:12px 16px;"><strong>' + r + '</strong></div>';
                            }
                        });
                        rangesHtml += '</div>';
                    }
                }

                var approversHtml = '';
                if (a.approvers) {
                    var approvers = typeof a.approvers === 'string' ? JSON.parse(a.approvers) : a.approvers;
                    if (Array.isArray(approvers)) {
                        approversHtml = approvers.map(function (app, i) {
                            return '<div class="detail-row"><span class="detail-label">Approver ' + (i + 1) + '</span><span class="detail-value">' + app + '</span></div>';
                        }).join('');
                    }
                }

                var typeText = a.assignment_type === 'designation' ? '👤 Designation Level' :
                    a.assignment_type === 'employee' ? '👤 Employee Level' : '🏢 Department Level';

                document.getElementById('detail-content').innerHTML = `
                                                        <div class="detail-row"><span class="detail-label">Type</span><span class="detail-value">${typeText}</span></div>
                                                        <div class="detail-row"><span class="detail-label">Department</span><span class="detail-value">${a.department_name || a.department_id || 'All Departments'}</span></div>
                                                        ${a.designation_name ? '<div class="detail-row"><span class="detail-label">Designation</span><span class="detail-value">' + a.designation_name + '</span></div>' : ''}
                                                        ${a.employee_name ? '<div class="detail-row"><span class="detail-label">Employee</span><span class="detail-value">' + a.employee_name + ' (' + (a.employee_id || '') + ')</span></div>' : ''}
                                                        <div class="detail-row"><span class="detail-label">Policy</span><span class="detail-value"><strong>${a.policy_name || 'Policy'}</strong> <span class="badge-pill" style="background:#e0e7ff;color:#3730a3;font-size:0.7rem;">${a.policy_category || 'other'}</span>${a.financial_year ? '<div style="font-size:0.7rem;color:#64748b;margin-top:2px;">📅 FY: ' + a.financial_year + ' | ⚙️ ' + (a.calculation_type || 'N/A').replace(/_/g, ' ') + '</div>' : ''}</span></div>
                                                        <div class="detail-row"><span class="detail-label">Approval Type</span><span class="detail-value">${a.approval_type || '1step'} Step Approval</span></div>
                                                        ${approversHtml}
                                                        <div style="margin-top:1.5rem;">
                                                            <h4 style="margin:0 0 1rem 0;color:#0f172a;font-size:0.95rem;"><i class="fas fa-sliders-h" style="color:#2563eb;"></i> Allowed Ranges (${a.allowed_ranges ? (typeof a.allowed_ranges === 'string' ? JSON.parse(a.allowed_ranges).length : a.allowed_ranges.length) : 0})</h4>
                                                            ${rangesHtml || '<div style="color:#94a3b8;padding:1rem;text-align:center;background:#f8fafc;border-radius:12px;">No ranges configured</div>'}
                                                        </div>
                                                        ${a.notes ? '<div style="margin-top:1.5rem;"><h4 style="margin:0 0 0.5rem 0;color:#0f172a;font-size:0.95rem;"><i class="far fa-sticky-note" style="color:#2563eb;"></i> Notes</h4><div style="background:#f8fafc;padding:1rem;border-radius:12px;font-size:0.85rem;color:#475569;">' + a.notes + '</div></div>' : ''}
                                                        ${a.created_at ? '<div style="margin-top:1.5rem;font-size:0.75rem;color:#94a3b8;text-align:right;">Created: ' + new Date(a.created_at).toLocaleString() + '</div>' : ''}`;
                document.getElementById('detail-overlay').classList.add('active');
                document.getElementById('detail-modal').classList.add('active');
            };

            window.closeDetailModal = function () {
                document.getElementById('detail-overlay').classList.remove('active');
                document.getElementById('detail-modal').classList.remove('active');
            };

            window.deleteAssignment = async function (id) {
                if (!confirm('Remove this assignment?')) return;
                try { await fetchJson(`/institute-admin/reimbursement-assignments/${id}`, { method: 'DELETE' }); showToast('Removed!', 'success'); await loadAssignments(); }
                catch (error) { showToast(error.message || 'Failed', 'error'); }
            };

            async function initialize() {
                await loadDepartments();
                await loadDepartmentCategories();
                await loadAllDesignations();
                await loadPolicies();
                await loadAssignments();
                selectAssignmentType('department');
            }

            document.addEventListener('DOMContentLoaded', initialize);
        })();
    </script>

@endsection
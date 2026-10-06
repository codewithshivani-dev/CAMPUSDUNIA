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

        /* Form Container */
        .form-container {
            background: white;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
            padding: 2.5rem;
            max-width: 1024px;
            margin: 0 auto;
        }

        .form-section {
            background: #fafcfd;
            border: 1px solid #edf2f7;
            border-radius: 20px;
            padding: 1.8rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .form-section:hover {
            border-color: #bfdbfe;
            box-shadow: 0 2px 12px rgba(37, 99, 235, 0.05);
        }

        .section-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1.5px solid #edf2f7;
            padding-bottom: 10px;
        }

        .section-title .step-badge {
            background: #2563eb;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
            margin-right: 4px;
        }

        .section-title i {
            color: #2563eb;
            font-size: 1.1rem;
        }

        .form-grid-3 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 15px;
            align-items: center;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-group label {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #475569;
        }

        .form-group label .required {
            color: #ef4444;
            margin-left: 2px;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1.2rem;
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

        textarea.form-control {
            border-radius: 12px;
            min-height: 80px;
            resize: vertical;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
        }

        .btn-outline-sm {
            background: white;
            border: 1.5px solid #e2e8f0;
            padding: 0.5rem 1.2rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-outline-sm:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            transform: translateY(-1px);
        }

        .range-box {
            border: 1px solid #e2e8f0;
            background: white;
            border-radius: 16px;
            padding: 1.2rem 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.2s ease;
        }

        .range-box:hover {
            border-color: #bfdbfe;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.04);
        }

        .range-box-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 700;
            font-size: 0.9rem;
            color: #1e293b;
            margin-bottom: 0.8rem;
            padding-bottom: 6px;
        }

        .range-box-title .transport-icon {
            width: 32px;
            height: 32px;
            background: #eff6ff;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            font-size: 0.9rem;
            margin-right: 8px;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white !important;
            border: none;
            padding: 0.85rem 2.5rem;
            border-radius: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(37, 99, 235, 0.25);
            font-size: 0.95rem;
            text-decoration: none !important;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            box-shadow: 0 6px 24px rgba(37, 99, 235, 0.35);
            transform: translateY(-2px);
        }

        .btn-outline-lg {
            background: white;
            border: 1.5px solid #e2e8f0;
            color: #475569;
            padding: 0.85rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-outline-lg:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            transform: translateY(-1px);
        }

        .info-badge {
            background: #eff6ff;
            color: #2563eb;
            padding: 0.2rem 0.8rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
        }

        .sub-type-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 1.5rem 0 1rem 0;
            padding: 0.5rem 0;
        }

        .sub-type-header h4 {
            margin: 0;
            color: #1e293b;
            font-weight: 700;
            font-size: 1rem;
        }

        .sub-type-header .line {
            flex: 1;
            height: 1.5px;
            background: linear-gradient(to right, #e2e8f0, transparent);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 14px;
            border-top: 1.5px solid #edf2f7;
            padding-top: 1.8rem;
            margin-top: 0.5rem;
        }

        .custom-field-row {
            display: grid;
            grid-template-columns: 1.5fr 1fr 0.8fr 0.8fr 0.8fr 1fr 1.2fr auto;
            gap: 8px;
            align-items: center;
            background: white;
            padding: 8px 12px;
            border: 1px solid #edf2f7;
            border-radius: 10px;
            margin-bottom: 6px;
            transition: all 0.2s;
        }

        .custom-field-row:hover {
            border-color: #bfdbfe;
            background: #fafcfd;
        }

        .custom-field-row .field-input {
            padding: 0.4rem 0.6rem;
            font-size: 0.85rem;
            border-radius: 8px;
            border: 1.5px solid #e2e8f0;
            background: white;
            outline: none;
            transition: all 0.2s;
            width: 100%;
        }

        .custom-field-row .field-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
        }

        .custom-field-row .field-select {
            padding: 0.4rem 0.6rem;
            font-size: 0.85rem;
            border-radius: 8px;
            border: 1.5px solid #e2e8f0;
            background: white;
            outline: none;
            transition: all 0.2s;
            width: 100%;
        }

        .custom-field-row .field-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
        }

        .remove-field-btn {
            color: #ef4444;
            border-color: #fee2e2;
            padding: 0.2rem 0.6rem;
            font-size: 0.75rem;
            background: white;
            border: 1.5px solid #fee2e2;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .remove-field-btn:hover {
            background: #fef2f2;
            border-color: #ef4444;
        }

        .custom-preview-box {
            background: #f8fafc;
            border: 1px dashed #94a3b8;
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .custom-preview-box .preview-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .custom-preview-box .preview-item {
            display: inline-block;
            background: white;
            padding: 0.3rem 0.8rem;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            font-size: 0.8rem;
            margin: 0.2rem;
        }

        .checkbox-with-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .checkbox-with-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
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

        .toast i {
            font-size: 1.2rem;
        }

        .toast-success i {
            color: #22c55e;
        }

        .toast-error i {
            color: #ef4444;
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

            .form-container {
                padding: 1.5rem;
            }

            .form-section {
                padding: 1.2rem;
            }

            .range-box {
                padding: 1rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions .btn-gradient,
            .form-actions .btn-outline-lg {
                width: 100%;
                justify-content: center;
            }

            .custom-field-row {
                grid-template-columns: 1fr;
                gap: 6px;
            }
        }

        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.6s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .btn-loading {
            opacity: 0.7;
            pointer-events: none;
        }
    </style>

    <div class="p-2">
        <!-- Modern Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="header-title">
                        <h1>Edit Reimbursement Policy</h1>
                        <p>Modifying: <span id="policy-title-id" style="font-weight: 700;">Loading...</span></p>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="/institute-admin/reimbursement-policies" class="btn-header">
                        <i class="fas fa-arrow-left"></i> Back to Master
                    </a>
                </div>
            </div>
        </div>

        <!-- Toast Container -->
        <div class="toast-container" id="toastContainer"></div>

        <div class="form-container">
            <form id="policy-form" onsubmit="updatePolicy(event)" novalidate>
                @csrf
                <input type="hidden" id="policy-id" name="policy_id">

                <!-- SECTION 1: Basic Information -->
                <div class="form-section">
                    <div class="section-title">
                        <span class="step-badge">1</span>
                        <i class="fas fa-info-circle"></i>
                        Basic Information
                    </div>
                    <div class="form-grid-3" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
                        <div class="form-group">
                            <label>Policy Name <span class="required">*</span></label>
                            <input type="text" id="policy_name" name="policy_name" class="form-control"
                                placeholder="e.g. Travel & Tours Policy" required>
                        </div>
                        <div class="form-group">
                            <label>Policy Category <span class="required">*</span></label>
                            <select id="policy_category" name="policy_category" class="form-control"
                                onchange="switchCategoryLayout(this.value)" required>
                                <option value="">-- Select Category --</option>
                                <option value="travel">🚗 Travel Policy</option>
                                <option value="accommodation">🏨 Accommodation Policy</option>
                                <option value="food">🍽️ Food Policy</option>
                                <option value="mobile">📱 Mobile Policy</option>
                                <option value="internet">🌐 Internet Policy</option>
                                <option value="entertainment">🎉 Entertainment Policy</option>
                                <option value="miscellaneous">📦 Miscellaneous Policy</option>
                                <option value="custom">✨ Custom Policy</option>
                            </select>
                        </div>
                        <!-- Financial Year & Effective Dates -->
                        <div class="form-group">
                            <label>Financial Year <span class="required">*</span></label>
                            <select id="financial_year" name="financial_year" class="form-control" required>
                                <option value="">-- Select Financial Year --</option>
                                <option value="2024-25">2024-25</option>
                                <option value="2025-26">2025-26</option>
                                <option value="2026-27">2026-27</option>
                                <option value="2027-28">2027-28</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Reimbursement Calculation Type <span class="required">*</span></label>
                            <select id="calculation_type" name="calculation_type" class="form-control" required>
                                <option value="">-- Select --</option>
                                <option value="per_claim">Per Claim</option>
                                <option value="per_day">Per Day</option>
                                <option value="per_month">Per Month</option>
                                <option value="per_quarter">Per Quarter</option>
                                <option value="per_year">Per Year</option>
                                <option value="lumpsum">Lumpsum (One-time)</option>
                            </select>
                        </div>

                        <!-- Effective Dates -->
                        <div class="form-group">
                            <label>Effective From <span class="required">*</span></label>
                            <input type="date" name="effective_from" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Effective To</label>
                            <input type="date" name="effective_to" class="form-control"
                                placeholder="Leave empty for ongoing">
                            <span style="font-size: 0.7rem; color: #94a3b8; margin-top: 2px;">Leave blank if this policy has
                                no end date</span>
                        </div>

                        <div class="form-group" style="grid-column: span 2;">
                            <label>Description</label>
                            <textarea id="description" name="description" class="form-control"
                                placeholder="Describe the reimbursement scope, criteria and parameters..."
                                rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: Reimbursement Values -->
                <div class="form-section">
                    <div class="section-title">
                        <span class="step-badge">2</span>
                        <i class="fas fa-coins"></i>
                        Reimbursement Values
                        <span
                            style="margin-left: auto; font-size: 0.7rem; color: #94a3b8; font-weight: 400; text-transform: none;">Define
                            amounts per sub-type</span>
                    </div>

                    <!-- Travel Category layout -->
                    <div id="val-travel" class="val-layout" style="display: none;">
                        <div class="sub-type-header">
                            <h4><i class="fas fa-car-side" style="color: #2563eb; margin-right: 8px;"></i>Private Vehicle
                                Rates & Limits</h4>
                            <span class="line"></span>
                        </div>

                        <div class="range-box">
                            <div class="range-box-title">
                                <div>
                                    <span class="transport-icon"><i class="fas fa-motorcycle"></i></span>
                                    Two Wheeler
                                </div>
                            </div>
                            <div class="form-grid-3">
                                <div class="form-group">
                                    <label>Minimum Amount (₹)</label>
                                    <input type="number" name="two_wheeler_min" class="form-control" placeholder="e.g. 50">
                                </div>
                                <div class="form-group">
                                    <label>Maximum Amount (₹)</label>
                                    <input type="number" name="two_wheeler_max" class="form-control" placeholder="e.g. 500">
                                </div>
                                <div class="form-group">
                                    <label>Rate Per KM (₹)</label>
                                    <input type="number" step="0.1" name="two_wheeler_rate_km" class="form-control"
                                        placeholder="e.g. 8">
                                </div>
                            </div>
                            <div class="form-grid-3" style="margin-top: 10px;">
                                <div class="form-group">
                                    <label>Bill Required</label>
                                    <select name="two_wheeler_bill_required" class="form-control">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Photo Required</label>
                                    <select name="two_wheeler_photo_required" class="form-control">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="range-box">
                            <div class="range-box-title">
                                <div>
                                    <span class="transport-icon"><i class="fas fa-car"></i></span>
                                    Car
                                </div>
                            </div>
                            <div class="form-grid-3">
                                <div class="form-group">
                                    <label>Minimum Amount (₹)</label>
                                    <input type="number" name="car_min" class="form-control" placeholder="e.g. 100">
                                </div>
                                <div class="form-group">
                                    <label>Maximum Amount (₹)</label>
                                    <input type="number" name="car_max" class="form-control" placeholder="e.g. 2000">
                                </div>
                                <div class="form-group">
                                    <label>Rate Per KM (₹)</label>
                                    <input type="number" step="0.1" name="car_rate_km" class="form-control"
                                        placeholder="e.g. 15">
                                </div>
                            </div>
                            <div class="form-grid-3" style="margin-top: 10px;">
                                <div class="form-group">
                                    <label>Bill Required</label>
                                    <select name="car_bill_required" class="form-control">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Photo Required</label>
                                    <select name="car_photo_required" class="form-control">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="range-box">
                            <div class="range-box-title">
                                <div>
                                    <span class="transport-icon"><i class="fas fa-truck"></i></span>
                                    Three Wheeler (Auto)
                                </div>
                            </div>
                            <div class="form-grid-3">
                                <div class="form-group">
                                    <label>Minimum Amount (₹)</label>
                                    <input type="number" name="auto_min" class="form-control" placeholder="e.g. 30">
                                </div>
                                <div class="form-group">
                                    <label>Maximum Amount (₹)</label>
                                    <input type="number" name="auto_max" class="form-control" placeholder="e.g. 300">
                                </div>
                                <div class="form-group">
                                    <label>Rate Per KM (₹)</label>
                                    <input type="number" step="0.1" name="auto_rate_km" class="form-control"
                                        placeholder="e.g. 10">
                                </div>
                            </div>
                            <div class="form-grid-3" style="margin-top: 10px;">
                                <div class="form-group">
                                    <label>Bill Required</label>
                                    <select name="auto_bill_required" class="form-control">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Photo Required</label>
                                    <select name="auto_photo_required" class="form-control">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="sub-type-header">
                            <h4><i class="fas fa-train" style="color: #2563eb; margin-right: 8px;"></i>Public Transport
                                Allowances</h4>
                            <span class="line"></span>
                        </div>

                        <!-- Bus Categories -->
                        <div class="range-box">
                            <div class="range-box-title">🚌 Bus Categories</div>
                            <div style="display: grid; gap: 1rem;">

                                <!-- General Bus -->
                                <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px;">
                                    <label
                                        style="font-weight: 700; font-size: 0.85rem; display: block; margin-bottom: 8px;">General
                                        Bus</label>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                        <div class="form-group"><label>Min (₹)</label><input type="number"
                                                name="bus_categories[general][min]" class="form-control" value="20"></div>
                                        <div class="form-group"><label>Max (₹)</label><input type="number"
                                                name="bus_categories[general][max]" class="form-control" value="300"></div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px;">
                                        <div class="form-group">
                                            <label>Bill Required</label>
                                            <select name="bus_categories[general][bill_required]" class="form-control">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Photo Required</label>
                                            <select name="bus_categories[general][photo_required]" class="form-control">
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Seater AC -->
                                <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px;">
                                    <label
                                        style="font-weight: 700; font-size: 0.85rem; display: block; margin-bottom: 8px;">Seater
                                        (AC)</label>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                        <div class="form-group"><label>Min (₹)</label><input type="number"
                                                name="bus_categories[seater_ac][min]" class="form-control" value="100">
                                        </div>
                                        <div class="form-group"><label>Max (₹)</label><input type="number"
                                                name="bus_categories[seater_ac][max]" class="form-control" value="800">
                                        </div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px;">
                                        <div class="form-group">
                                            <label>Bill Required</label>
                                            <select name="bus_categories[seater_ac][bill_required]" class="form-control">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Photo Required</label>
                                            <select name="bus_categories[seater_ac][photo_required]" class="form-control">
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Seater Non-AC -->
                                <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px;">
                                    <label
                                        style="font-weight: 700; font-size: 0.85rem; display: block; margin-bottom: 8px;">Seater
                                        (Non-AC)</label>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                        <div class="form-group"><label>Min (₹)</label><input type="number"
                                                name="bus_categories[seater_nonac][min]" class="form-control" value="50">
                                        </div>
                                        <div class="form-group"><label>Max (₹)</label><input type="number"
                                                name="bus_categories[seater_nonac][max]" class="form-control" value="500">
                                        </div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px;">
                                        <div class="form-group">
                                            <label>Bill Required</label>
                                            <select name="bus_categories[seater_nonac][bill_required]" class="form-control">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Photo Required</label>
                                            <select name="bus_categories[seater_nonac][photo_required]"
                                                class="form-control">
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sleeper AC -->
                                <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px;">
                                    <label
                                        style="font-weight: 700; font-size: 0.85rem; display: block; margin-bottom: 8px;">Sleeper
                                        (AC)</label>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                        <div class="form-group"><label>Min (₹)</label><input type="number"
                                                name="bus_categories[sleeper_ac][min]" class="form-control" value="300">
                                        </div>
                                        <div class="form-group"><label>Max (₹)</label><input type="number"
                                                name="bus_categories[sleeper_ac][max]" class="form-control" value="2000">
                                        </div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px;">
                                        <div class="form-group">
                                            <label>Bill Required</label>
                                            <select name="bus_categories[sleeper_ac][bill_required]" class="form-control">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Photo Required</label>
                                            <select name="bus_categories[sleeper_ac][photo_required]" class="form-control">
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sleeper Non-AC -->
                                <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px;">
                                    <label
                                        style="font-weight: 700; font-size: 0.85rem; display: block; margin-bottom: 8px;">Sleeper
                                        (Non-AC)</label>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                        <div class="form-group"><label>Min (₹)</label><input type="number"
                                                name="bus_categories[sleeper_nonac][min]" class="form-control" value="200">
                                        </div>
                                        <div class="form-group"><label>Max (₹)</label><input type="number"
                                                name="bus_categories[sleeper_nonac][max]" class="form-control" value="1200">
                                        </div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px;">
                                        <div class="form-group">
                                            <label>Bill Required</label>
                                            <select name="bus_categories[sleeper_nonac][bill_required]"
                                                class="form-control">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Photo Required</label>
                                            <select name="bus_categories[sleeper_nonac][photo_required]"
                                                class="form-control">
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Train Categories -->
                        <div class="range-box" style="margin-top: 1rem;">
                            <div class="range-box-title">🚂 Train Categories</div>
                            <div style="display: grid; gap: 1rem;">

                                <!-- General -->
                                <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px;">
                                    <label
                                        style="font-weight: 700; font-size: 0.85rem; display: block; margin-bottom: 8px;">General</label>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                        <div class="form-group"><label>Min (₹)</label><input type="number"
                                                name="train_categories[general][min]" class="form-control" value="50"></div>
                                        <div class="form-group"><label>Max (₹)</label><input type="number"
                                                name="train_categories[general][max]" class="form-control" value="300">
                                        </div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px;">
                                        <div class="form-group">
                                            <label>Bill Required</label>
                                            <select name="train_categories[general][bill_required]" class="form-control">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Photo Required</label>
                                            <select name="train_categories[general][photo_required]" class="form-control">
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sleeper -->
                                <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px;">
                                    <label
                                        style="font-weight: 700; font-size: 0.85rem; display: block; margin-bottom: 8px;">Sleeper
                                        Class</label>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                        <div class="form-group"><label>Min (₹)</label><input type="number"
                                                name="train_categories[sleeper][min]" class="form-control" value="200">
                                        </div>
                                        <div class="form-group"><label>Max (₹)</label><input type="number"
                                                name="train_categories[sleeper][max]" class="form-control" value="800">
                                        </div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px;">
                                        <div class="form-group">
                                            <label>Bill Required</label>
                                            <select name="train_categories[sleeper][bill_required]" class="form-control">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Photo Required</label>
                                            <select name="train_categories[sleeper][photo_required]" class="form-control">
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- 3AC -->
                                <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px;">
                                    <label
                                        style="font-weight: 700; font-size: 0.85rem; display: block; margin-bottom: 8px;">3AC</label>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                        <div class="form-group"><label>Min (₹)</label><input type="number"
                                                name="train_categories[ac3][min]" class="form-control" value="500"></div>
                                        <div class="form-group"><label>Max (₹)</label><input type="number"
                                                name="train_categories[ac3][max]" class="form-control" value="2000"></div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px;">
                                        <div class="form-group">
                                            <label>Bill Required</label>
                                            <select name="train_categories[ac3][bill_required]" class="form-control">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Photo Required</label>
                                            <select name="train_categories[ac3][photo_required]" class="form-control">
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2AC -->
                                <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px;">
                                    <label
                                        style="font-weight: 700; font-size: 0.85rem; display: block; margin-bottom: 8px;">2AC</label>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                        <div class="form-group"><label>Min (₹)</label><input type="number"
                                                name="train_categories[ac2][min]" class="form-control" value="1000"></div>
                                        <div class="form-group"><label>Max (₹)</label><input type="number"
                                                name="train_categories[ac2][max]" class="form-control" value="3500"></div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px;">
                                        <div class="form-group">
                                            <label>Bill Required</label>
                                            <select name="train_categories[ac2][bill_required]" class="form-control">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Photo Required</label>
                                            <select name="train_categories[ac2][photo_required]" class="form-control">
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- 1AC -->
                                <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px;">
                                    <label
                                        style="font-weight: 700; font-size: 0.85rem; display: block; margin-bottom: 8px;">1AC</label>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                        <div class="form-group"><label>Min (₹)</label><input type="number"
                                                name="train_categories[ac1][min]" class="form-control" value="1500"></div>
                                        <div class="form-group"><label>Max (₹)</label><input type="number"
                                                name="train_categories[ac1][max]" class="form-control" value="5000"></div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px;">
                                        <div class="form-group">
                                            <label>Bill Required</label>
                                            <select name="train_categories[ac1][bill_required]" class="form-control">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Photo Required</label>
                                            <select name="train_categories[ac1][photo_required]" class="form-control">
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Flight Categories -->
                        <div class="range-box" style="margin-top: 1rem;">
                            <div class="range-box-title">✈️ Flight Categories</div>
                            <div style="display: grid; gap: 1rem;">

                                <!-- Economy -->
                                <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px;">
                                    <label
                                        style="font-weight: 700; font-size: 0.85rem; display: block; margin-bottom: 8px;">Economy</label>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                        <div class="form-group"><label>Min (₹)</label><input type="number"
                                                name="flight_categories[economy][min]" class="form-control" value="2000">
                                        </div>
                                        <div class="form-group"><label>Max (₹)</label><input type="number"
                                                name="flight_categories[economy][max]" class="form-control" value="10000">
                                        </div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px;">
                                        <div class="form-group">
                                            <label>Bill Required</label>
                                            <select name="flight_categories[economy][bill_required]" class="form-control">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Photo Required</label>
                                            <select name="flight_categories[economy][photo_required]" class="form-control">
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Business -->
                                <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px;">
                                    <label
                                        style="font-weight: 700; font-size: 0.85rem; display: block; margin-bottom: 8px;">Business
                                        Class</label>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                        <div class="form-group"><label>Min (₹)</label><input type="number"
                                                name="flight_categories[business][min]" class="form-control" value="5000">
                                        </div>
                                        <div class="form-group"><label>Max (₹)</label><input type="number"
                                                name="flight_categories[business][max]" class="form-control" value="25000">
                                        </div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px;">
                                        <div class="form-group">
                                            <label>Bill Required</label>
                                            <select name="flight_categories[business][bill_required]" class="form-control">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Photo Required</label>
                                            <select name="flight_categories[business][photo_required]" class="form-control">
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Accommodation layout -->
                    <div id="val-accommodation" class="val-layout" style="display: none;">
                        <div class="sub-type-header">
                            <h4>🏨 Room Categories</h4>
                            <span class="line"></span>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <div class="range-box">
                                <div class="range-box-title">
                                    <span><strong>1-2 Star</strong> <span
                                            style="font-weight: 400; color: #64748b; font-size: 0.8rem;">(Basic)</span></span>
                                </div>
                                <div class="form-grid-3">
                                    <div class="form-group">
                                        <label>Minimum Amount (₹)</label>
                                        <input type="number" name="basic_min" class="form-control" value="500">
                                    </div>
                                    <div class="form-group">
                                        <label>Maximum Amount (₹)</label>
                                        <input type="number" name="basic_max" class="form-control" value="2000">
                                    </div>
                                    <div class="form-group">
                                        <div class="checkbox-with-label" style="margin-top: 20px;">
                                            <input type="checkbox" name="basic_includes_food" value="1" checked>
                                            <label
                                                style="text-transform: none; font-weight: 600; color: #1e293b; font-size: 0.85rem;">Includes
                                                Food</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-grid-3" style="margin-top: 10px;">
                                    <div class="form-group">
                                        <label>Bill Required</label>
                                        <select name="basic_bill_required" class="form-control">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Photo Required</label>
                                        <select name="basic_photo_required" class="form-control">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="range-box">
                                <div class="range-box-title">
                                    <span><strong>3-4 Star</strong> <span
                                            style="font-weight: 400; color: #64748b; font-size: 0.8rem;">(Business)</span></span>
                                </div>
                                <div class="form-grid-3">
                                    <div class="form-group">
                                        <label>Minimum Amount (₹)</label>
                                        <input type="number" name="deluxe_min" class="form-control" value="1000">
                                    </div>
                                    <div class="form-group">
                                        <label>Maximum Amount (₹)</label>
                                        <input type="number" name="deluxe_max" class="form-control" value="4000">
                                    </div>
                                    <div class="form-group">
                                        <div class="checkbox-with-label" style="margin-top: 20px;">
                                            <input type="checkbox" name="deluxe_includes_food" value="1" checked>
                                            <label
                                                style="text-transform: none; font-weight: 600; color: #1e293b; font-size: 0.85rem;">Includes
                                                Food</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-grid-3" style="margin-top: 10px;">
                                    <div class="form-group">
                                        <label>Bill Required</label>
                                        <select name="deluxe_bill_required" class="form-control">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Photo Required</label>
                                        <select name="deluxe_photo_required" class="form-control">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="range-box">
                                <div class="range-box-title">
                                    <span><strong>5 Star</strong> <span
                                            style="font-weight: 400; color: #64748b; font-size: 0.8rem;">(Premium)</span></span>
                                </div>
                                <div class="form-grid-3">
                                    <div class="form-group">
                                        <label>Minimum Amount (₹)</label>
                                        <input type="number" name="premium_min" class="form-control" value="2000">
                                    </div>
                                    <div class="form-group">
                                        <label>Maximum Amount (₹)</label>
                                        <input type="number" name="premium_max" class="form-control" value="8000">
                                    </div>
                                    <div class="form-group">
                                        <div class="checkbox-with-label" style="margin-top: 20px;">
                                            <input type="checkbox" name="premium_includes_food" value="1" checked>
                                            <label
                                                style="text-transform: none; font-weight: 600; color: #1e293b; font-size: 0.85rem;">Includes
                                                Food</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-grid-3" style="margin-top: 10px;">
                                    <div class="form-group">
                                        <label>Bill Required</label>
                                        <select name="premium_bill_required" class="form-control">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Photo Required</label>
                                        <select name="premium_photo_required" class="form-control">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Food layout -->
                    <div id="val-food" class="val-layout" style="display: none;">
                        <div class="sub-type-header">
                            <h4>🍽️ Meals per Person</h4>
                            <span class="line"></span>
                        </div>

                        <div class="range-box">
                            <div class="range-box-title">
                                <span><strong>Individual Meals</strong> <span
                                        style="font-weight: 400; color: #64748b; font-size: 0.8rem;">(Single meal
                                        claim)</span></span>
                            </div>
                            <div class="form-grid-3">
                                <div class="form-group">
                                    <label>Breakfast</label>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                        <input type="number" name="breakfast_min" class="form-control" placeholder="Min"
                                            value="50">
                                        <input type="number" name="breakfast_max" class="form-control" placeholder="Max"
                                            value="150">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Lunch</label>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                        <input type="number" name="lunch_min" class="form-control" placeholder="Min"
                                            value="100">
                                        <input type="number" name="lunch_max" class="form-control" placeholder="Max"
                                            value="300">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Dinner</label>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                        <input type="number" name="dinner_min" class="form-control" placeholder="Min"
                                            value="100">
                                        <input type="number" name="dinner_max" class="form-control" placeholder="Max"
                                            value="400">
                                    </div>
                                </div>
                            </div>
                            <div class="form-grid-3" style="margin-top: 10px;">
                                <div class="form-group">
                                    <label>Bill Required</label>
                                    <select name="individual_bill_required" class="form-control">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Photo Required</label>
                                    <select name="individual_photo_required" class="form-control">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="range-box">
                            <div class="range-box-title">
                                <span><strong>Two Meals (Combined)</strong> <span
                                        style="font-weight: 400; color: #64748b; font-size: 0.8rem;">(Breakfast+Lunch or
                                        Lunch+Dinner)</span></span>
                            </div>
                            <div class="form-grid-3">
                                <div class="form-group">
                                    <label>Minimum Amount (₹)</label>
                                    <input type="number" name="two_meals_min" class="form-control" value="150">
                                </div>
                                <div class="form-group">
                                    <label>Maximum Amount (₹)</label>
                                    <input type="number" name="two_meals_max" class="form-control" value="650">
                                </div>
                            </div>
                            <div class="form-grid-3" style="margin-top: 10px;">
                                <div class="form-group">
                                    <label>Bill Required</label>
                                    <select name="two_meals_bill_required" class="form-control">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Photo Required</label>
                                    <select name="two_meals_photo_required" class="form-control">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="range-box">
                            <div class="range-box-title">
                                <span><strong>Three Meals (Full Day)</strong> <span
                                        style="font-weight: 400; color: #64748b; font-size: 0.8rem;">(Breakfast+Lunch+Dinner)</span></span>
                            </div>
                            <div class="form-grid-3">
                                <div class="form-group">
                                    <label>Minimum Amount (₹)</label>
                                    <input type="number" name="three_meals_min" class="form-control" value="200">
                                </div>
                                <div class="form-group">
                                    <label>Maximum Amount (₹)</label>
                                    <input type="number" name="three_meals_max" class="form-control" value="850">
                                </div>
                            </div>
                            <div class="form-grid-3" style="margin-top: 10px;">
                                <div class="form-group">
                                    <label>Bill Required</label>
                                    <select name="three_meals_bill_required" class="form-control">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Photo Required</label>
                                    <select name="three_meals_photo_required" class="form-control">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fixed categories layout -->
                    <div id="val-fixed" class="val-layout" style="display: none;">
                        <div style="background: #f0f7ff; padding: 8px 16px; border-radius: 12px; margin-bottom: 16px;">
                            <span class="info-badge"><i class="fas fa-info-circle"></i> Configure individual sub-type rules
                                below</span>
                        </div>
                        <h4 style="margin: 0 0 10px 0; color: #1e293b;"><i class="fas fa-tag"
                                style="color: #2563eb; margin-right: 6px;"></i> General Limit Settings</h4>
                        <div class="form-grid-3">
                            <div class="form-group">
                                <label>Minimum Amount (₹)</label>
                                <input type="number" name="min_amount" class="form-control" value="50">
                            </div>
                            <div class="form-group">
                                <label>Maximum Amount (₹)</label>
                                <input type="number" name="max_amount" class="form-control" value="1500">
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label>Remarks / Special Terms</label>
                                <input type="text" name="remarks" class="form-control"
                                    placeholder="e.g. Allowed once a billing cycle">
                            </div>
                        </div>
                        <div class="form-grid-3" style="margin-top: 10px;">
                            <div class="form-group">
                                <label>Bill Required</label>
                                <select name="bill_required" class="form-control">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Photo Required</label>
                                <select name="photo_required" class="form-control">
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Custom Policy layout -->
                    <div id="val-custom" class="val-layout" style="display: none;">
                        <div style="background: #f0f7ff; padding: 12px 16px; border-radius: 12px; margin-bottom: 16px;">
                            <span class="info-badge"><i class="fas fa-info-circle"></i> Define claim items and rules for
                                this policy. These will be saved in policy_data field.</span>
                        </div>

                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 10px;">
                            <h4 style="margin: 0; color: #1e293b;">
                                <i class="fas fa-list-check" style="color: #2563eb; margin-right: 6px;"></i>
                                Claim Values
                                <span style="font-weight: 400; color: #64748b; font-size: 0.8rem;">(Add claim items with
                                    their Values)</span>
                            </h4>
                            <button type="button" class="btn-gradient" style="padding: 0.5rem 1.5rem; font-size: 0.85rem;"
                                onclick="addClaimRule()">
                                <i class="fas fa-plus"></i> Add Claim Value
                            </button>
                        </div>

                        <input type="hidden" name="policy_data" id="policy_data" value="">

                        <div id="claim-rules-container" style="display: flex; flex-direction: column; gap: 12px;"></div>

                        <div class="custom-preview-box" style="margin-top: 1.5rem;">
                            <div class="preview-title"><i class="fas fa-eye"></i> Claim Values Summary</div>
                            <div id="claim-rules-preview" style="font-size: 0.85rem; color: #475569;">
                                <span style="color: #94a3b8;">No claim values added yet. Click "Add Claim Value" to get
                                    started.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: Claim Rules -->
                <div class="form-section">
                    <div class="section-title">
                        <span class="step-badge">3</span>
                        <i class="fas fa-gavel"></i>
                        Claim Rules
                        <span
                            style="margin-left: auto; font-size: 0.7rem; color: #94a3b8; font-weight: 400; text-transform: none;">Global
                            rules applicable to all sub-types</span>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">

                        <div class="range-box" style="margin-bottom: 0;">
                            <div class="range-box-title">Maximum Claims Allowed</div>
                            <div style="display: flex; flex-direction: column; gap: 8px; font-size: 0.85rem;">
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="radio" name="frequency_type" value="unlimited" checked
                                        onclick="toggleFreqInput(false)"> Unlimited Claims
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="radio" name="frequency_type" value="day" onclick="toggleFreqInput(true)">
                                    Per Day
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="radio" name="frequency_type" value="week" onclick="toggleFreqInput(true)">
                                    Per Week
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="radio" name="frequency_type" value="month" onclick="toggleFreqInput(true)">
                                    Per Month
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="radio" name="frequency_type" value="quarter"
                                        onclick="toggleFreqInput(true)"> Per Quarter
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="radio" name="frequency_type" value="year" onclick="toggleFreqInput(true)">
                                    Per Year
                                </label>

                                <div class="form-group" id="freq-val-group" style="display: none; margin-top: 5px;">
                                    <label>Value (No. of Claims)</label>
                                    <input type="number" name="frequency_value" class="form-control" value="1" min="1">
                                </div>
                            </div>
                        </div>

                        <div class="range-box" style="margin-bottom: 0;">
                            <div class="range-box-title">Bills constraints</div>
                            <div class="form-group" style="margin-bottom: 8px;">
                                <label>Allow Multiple Bills</label>
                                <select name="allow_multi_bills" class="form-control" onchange="toggleMaxBills(this.value)">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="form-group" id="max-bills-group" style="margin-bottom: 8px;">
                                <label>Maximum Bills</label>
                                <input type="number" name="max_bills" class="form-control" value="5" min="1">
                            </div>
                            <div class="form-group" style="margin-bottom: 8px;">
                                <label>Allow Same Bill Number</label>
                                <select name="allow_same_bill" class="form-control">
                                    <option value="No">No (Must be unique)</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div
                        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 1.25rem;">

                        <div class="range-box" style="margin-bottom: 0;">
                            <div class="range-box-title">Actual Amount Claims</div>
                            <div class="form-group">
                                <label>Allow Actual Amount Claims</label>
                                <select name="allow_actual_amount" class="form-control">
                                    <option value="No">No (Use defined ranges only)</option>
                                    <option value="Yes">Yes (Employee can claim exact bill amount)</option>
                                </select>
                                <span class="form-hint" style="font-size: 0.7rem; color: #94a3b8;">
                                    <i class="fas fa-info-circle"></i> If Yes, Approvers can approve exact amount with
                                    mandatory bill upload. The range max limit still applies.
                                </span>
                            </div>
                        </div>

                        <div class="range-box" style="margin-bottom: 0;">
                            <div class="range-box-title">Claim Submission Window</div>
                            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 0.85rem;">
                                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    Submit within
                                    <input type="number" name="submission_within" class="form-control"
                                        style="width: 70px; padding: 0.4rem 0.6rem; border-radius: 12px; margin: 0;"
                                        value="7">
                                    Days
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 6px;">
                                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                        <input type="radio" name="submission_type" value="expense_date" checked> After
                                        Expense Date
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                        <input type="radio" name="submission_type" value="bill_date"> Of Bill Date
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="range-box" style="margin-top: 1.25rem; margin-bottom: 0;">
                        <div class="range-box-title">Mandatory Fields on claim form</div>
                        <div style="display: flex; gap: 24px; flex-wrap: wrap; font-size: 0.85rem;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" name="expense_date_mandatory" checked disabled> Expense Date <span
                                    style="color: #ef4444;">*</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" name="remarks_mandatory" value="1" checked> Remarks
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" name="bill_mandatory" value="1" checked> Bill Number
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" name="vendor_mandatory" value="1"> Vendor Name
                            </label>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: Settlement Rules -->
                <div class="form-section">
                    <div class="section-title">
                        <span class="step-badge">4</span>
                        <i class="fas fa-check-double"></i>
                        Settlement Rules
                    </div>
                    <div class="form-grid-3">
                        <div class="form-group">
                            <label>Settlement Timeline (Days)</label>
                            <input type="number" name="settlement_timeline" class="form-control" value="7">
                        </div>
                        <div class="form-group">
                            <label>Settlement Mode <span class="required">*</span></label>
                            <select name="settlement_mode" class="form-control" required>
                                <option value="">-- Select Mode --</option>
                                <option value="payroll">Payroll Adjustment</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cash">Cash payment</option>
                                <option value="wallet">Corporate Wallet</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Auto Settlement</label>
                            <select name="auto_settlement" class="form-control">
                                <option value="No">No</option>
                                <option value="Yes">Yes (Process instantly after approval)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Allow Partial Settlement</label>
                            <select name="allow_partial_settlement" class="form-control">
                                <option value="Yes">Yes (Settler can adjust approved amount)</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="reset" class="btn-outline-lg">
                        <i class="fas fa-undo"></i> Reset Form
                    </button>
                    <button type="submit" class="btn-gradient" id="submitBtn">
                        <i class="fas fa-save"></i>
                        <span id="submitText">Update Policy</span>
                        <span id="submitSpinner" style="display: none;" class="spinner"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Get policy ID from URL
        const pathParts = window.location.pathname.split('/');
        const policyId = pathParts[pathParts.length - 1];

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            document.querySelector('input[name="_token"]')?.value;

        let allPolicies = [];

        // --- Toast Helper ---
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i><span>${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        // --- Toggle helpers ---
        function toggleFreqInput(show) {
            document.getElementById("freq-val-group").style.display = show ? "flex" : "none";
        }

        function toggleMaxBills(status) {
            document.getElementById("max-bills-group").style.display = status === "Yes" ? "flex" : "none";
        }

        // --- Category layout switcher ---
        function switchCategoryLayout(category) {
            document.querySelectorAll(".val-layout").forEach(el => el.style.display = "none");

            switch (category) {
                case 'travel':
                    document.getElementById("val-travel").style.display = "block";
                    break;
                case 'accommodation':
                    document.getElementById("val-accommodation").style.display = "block";
                    break;
                case 'food':
                    document.getElementById("val-food").style.display = "block";
                    break;
                case 'custom':
                    document.getElementById("val-custom").style.display = "block";
                    break;
                case 'mobile':
                case 'internet':
                case 'entertainment':
                case 'miscellaneous':
                    document.getElementById("val-fixed").style.display = "block";
                    break;
                default:
                    break;
            }
        }

        // --- Load policy data ---
        async function loadPolicy() {
            try {
                const response = await fetch('/institute-admin/reimbursement-policies', {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await response.json();
                allPolicies = Array.isArray(data) ? data : (data.data || []);

                const policy = allPolicies.find(p => p.id == policyId);
                if (!policy) throw new Error('Policy not found');

                document.getElementById("policy-title-id").textContent = policy.policy_name || 'Unnamed Policy';
                prefillForm(policy);
            } catch (err) {
                showToast('Error loading policy: ' + err.message, 'error');
            }
        }

        // --- Pre-fill form with policy data ---
        function prefillForm(p) {
            // Basic info
            document.getElementById("policy-id").value = p.id;
            document.getElementById("policy_name").value = p.policy_name || '';
            document.getElementById("policy_category").value = p.policy_category || '';
            document.getElementById("description").value = p.description || '';

            // Financial & effective dates
            setVal('financial_year', p.financial_year);
            setVal('calculation_type', p.calculation_type);
            setVal('effective_from', p.effective_from);
            setVal('effective_to', p.effective_to);

            const category = p.policy_category || '';
            switchCategoryLayout(category);

            // --- Category-specific pre-fill ---
            if (category === 'travel') {
                // Private vehicles
                setVal('two_wheeler_min', p.two_wheeler_min);
                setVal('two_wheeler_max', p.two_wheeler_max);
                setVal('two_wheeler_rate_km', p.two_wheeler_rate_km);
                setSelect('two_wheeler_bill_required', p.two_wheeler_bill_required);
                setSelect('two_wheeler_photo_required', p.two_wheeler_photo_required);

                setVal('car_min', p.car_min);
                setVal('car_max', p.car_max);
                setVal('car_rate_km', p.car_rate_km);
                setSelect('car_bill_required', p.car_bill_required);
                setSelect('car_photo_required', p.car_photo_required);

                setVal('auto_min', p.auto_min);
                setVal('auto_max', p.auto_max);
                setVal('auto_rate_km', p.auto_rate_km);
                setSelect('auto_bill_required', p.auto_bill_required);
                setSelect('auto_photo_required', p.auto_photo_required);

                // Bus categories (JSON)
                if (p.bus_categories) {
                    let busData = p.bus_categories;
                    if (typeof busData === 'string') {
                        try { busData = JSON.parse(busData); } catch (e) { busData = {}; }
                    }
                    if (typeof busData === 'object' && busData !== null) {
                        Object.keys(busData).forEach(key => {
                            const cat = busData[key];
                            if (cat) {
                                setVal(`bus_categories[${key}][min]`, cat.min);
                                setVal(`bus_categories[${key}][max]`, cat.max);
                                setSelect(`bus_categories[${key}][bill_required]`, cat.bill_required);
                                setSelect(`bus_categories[${key}][photo_required]`, cat.photo_required);
                            }
                        });
                    }
                }

                // Train categories (JSON)
                if (p.train_categories) {
                    let trainData = p.train_categories;
                    if (typeof trainData === 'string') {
                        try { trainData = JSON.parse(trainData); } catch (e) { trainData = {}; }
                    }
                    if (typeof trainData === 'object' && trainData !== null) {
                        Object.keys(trainData).forEach(key => {
                            const cat = trainData[key];
                            if (cat) {
                                setVal(`train_categories[${key}][min]`, cat.min);
                                setVal(`train_categories[${key}][max]`, cat.max);
                                setSelect(`train_categories[${key}][bill_required]`, cat.bill_required);
                                setSelect(`train_categories[${key}][photo_required]`, cat.photo_required);
                            }
                        });
                    }
                }

                // Flight categories (JSON)
                if (p.flight_categories) {
                    let flightData = p.flight_categories;
                    if (typeof flightData === 'string') {
                        try { flightData = JSON.parse(flightData); } catch (e) { flightData = {}; }
                    }
                    if (typeof flightData === 'object' && flightData !== null) {
                        Object.keys(flightData).forEach(key => {
                            const cat = flightData[key];
                            if (cat) {
                                setVal(`flight_categories[${key}][min]`, cat.min);
                                setVal(`flight_categories[${key}][max]`, cat.max);
                                setSelect(`flight_categories[${key}][bill_required]`, cat.bill_required);
                                setSelect(`flight_categories[${key}][photo_required]`, cat.photo_required);
                            }
                        });
                    }
                }
            } else if (category === 'accommodation') {
                setVal('basic_min', p.basic_min);
                setVal('basic_max', p.basic_max);
                if (p.basic_includes_food) document.querySelector('[name="basic_includes_food"]').checked = true;
                setSelect('basic_bill_required', p.basic_bill_required);
                setSelect('basic_photo_required', p.basic_photo_required);

                setVal('deluxe_min', p.deluxe_min);
                setVal('deluxe_max', p.deluxe_max);
                if (p.deluxe_includes_food) document.querySelector('[name="deluxe_includes_food"]').checked = true;
                setSelect('deluxe_bill_required', p.deluxe_bill_required);
                setSelect('deluxe_photo_required', p.deluxe_photo_required);

                setVal('premium_min', p.premium_min);
                setVal('premium_max', p.premium_max);
                if (p.premium_includes_food) document.querySelector('[name="premium_includes_food"]').checked = true;
                setSelect('premium_bill_required', p.premium_bill_required);
                setSelect('premium_photo_required', p.premium_photo_required);
            } else if (category === 'food') {
                setVal('breakfast_min', p.breakfast_min);
                setVal('breakfast_max', p.breakfast_max);
                setVal('lunch_min', p.lunch_min);
                setVal('lunch_max', p.lunch_max);
                setVal('dinner_min', p.dinner_min);
                setVal('dinner_max', p.dinner_max);
                setSelect('individual_bill_required', p.individual_bill_required);
                setSelect('individual_photo_required', p.individual_photo_required);

                setVal('two_meals_min', p.two_meals_min);
                setVal('two_meals_max', p.two_meals_max);
                setSelect('two_meals_bill_required', p.two_meals_bill_required);
                setSelect('two_meals_photo_required', p.two_meals_photo_required);

                setVal('three_meals_min', p.three_meals_min);
                setVal('three_meals_max', p.three_meals_max);
                setSelect('three_meals_bill_required', p.three_meals_bill_required);
                setSelect('three_meals_photo_required', p.three_meals_photo_required);
            } else if (category === 'custom') {
                // Load custom rules
                let policyData = p.policy_data;
                if (typeof policyData === 'string') {
                    try { policyData = JSON.parse(policyData); } catch (e) { policyData = []; }
                }
                if (Array.isArray(policyData) && policyData.length > 0) {
                    const container = document.getElementById('claim-rules-container');
                    container.innerHTML = '';
                    policyData.forEach((rule, idx) => {
                        addClaimRuleWithData(rule, idx);
                    });
                    updateClaimRulesPreview();
                }
            } else {
                // Fixed categories (mobile, internet, entertainment, miscellaneous)
                setVal('min_amount', p.min_amount);
                setVal('max_amount', p.max_amount);
                setSelect('bill_required', p.bill_required);
                setSelect('photo_required', p.photo_required);
                setVal('remarks', p.remarks);
            }

            // --- Claim Rules Section ---
            const freqType = p.frequency_type || 'unlimited';
            const freqRadio = document.querySelector(`input[name="frequency_type"][value="${freqType}"]`);
            if (freqRadio) {
                freqRadio.checked = true;
                toggleFreqInput(freqType !== 'unlimited');
            }
            setVal('frequency_value', p.frequency_value);
            setVal('submission_within', p.submission_within);
            const subRadio = document.querySelector(`input[name="submission_type"][value="${p.submission_type || 'expense_date'}"]`);
            if (subRadio) subRadio.checked = true;

            setSelect('allow_multi_bills', p.allow_multi_bills);
            toggleMaxBills(p.allow_multi_bills || 'Yes');
            setVal('max_bills', p.max_bills);
            setSelect('allow_same_bill', p.allow_same_bill);

            // Actual amount
            setSelect('allow_actual_amount', p.allow_actual_amount);

            // Mandatory fields
            document.querySelector('[name="remarks_mandatory"]').checked = p.remarks_mandatory ? true : false;
            document.querySelector('[name="bill_mandatory"]').checked = p.bill_mandatory ? true : false;
            document.querySelector('[name="vendor_mandatory"]').checked = p.vendor_mandatory ? true : false;

            // --- Settlement Rules ---
            setVal('settlement_timeline', p.settlement_timeline);
            setSelect('settlement_mode', p.settlement_mode);
            setSelect('auto_settlement', p.auto_settlement);
            setSelect('allow_partial_settlement', p.allow_partial_settlement);
        }

        // --- Helpers for setting values ---
        function setVal(name, value) {
            const el = document.querySelector(`[name="${name}"]`);
            if (el && value !== undefined && value !== null) el.value = value;
        }

        function setSelect(name, value) {
            const el = document.querySelector(`[name="${name}"]`);
            if (el && value) {
                const option = Array.from(el.options).find(o => o.value === value);
                if (option) el.value = value;
            }
        }

        // --- Custom policy rule builder ---
        function addClaimRule() {
            const container = document.getElementById("claim-rules-container");
            const ruleIndex = container.children.length;
            const card = createClaimRuleCard(ruleIndex, {}, false);
            container.appendChild(card);
            updateClaimRulesPreview();
        }

        function addClaimRuleWithData(rule, index) {
            const container = document.getElementById("claim-rules-container");
            const card = createClaimRuleCard(index, rule, true);
            container.appendChild(card);
        }

        function createClaimRuleCard(ruleIndex, ruleData, isPrefill) {
            const card = document.createElement("div");
            card.className = "range-box";
            card.style.marginBottom = "12px";
            card.style.position = "relative";
            card.setAttribute('data-rule-index', ruleIndex);

            const name = ruleData.name || '';
            const type = ruleData.type || 'actual';
            const fields = ruleData.fields || {};
            const billReq = ruleData.bill_required || 'Yes';
            const photoReq = ruleData.photo_required || 'No';

            // Determine which field group is visible based on type
            const fieldVisibility = {
                actual: type === 'actual' ? 'grid' : 'none',
                fixed: type === 'fixed' ? 'grid' : 'none',
                per_km: type === 'per_km' ? 'grid' : 'none',
                per_day: type === 'per_day' ? 'grid' : 'none',
                per_night: type === 'per_night' ? 'grid' : 'none',
                per_hour: type === 'per_hour' ? 'grid' : 'none',
                per_unit: type === 'per_unit' ? 'grid' : 'none',
                percentage: type === 'percentage' ? 'grid' : 'none'
            };

            card.innerHTML = `
                <div style="position: absolute; top: 12px; right: 12px;">
                    <button type="button" class="remove-field-btn" onclick="removeClaimRule(this)" style="padding: 0.3rem 0.8rem;">
                        <i class="fas fa-times"></i> Remove
                    </button>
                </div>
                <div class="form-grid-3" style="margin-bottom: 12px;">
                    <div class="form-group">
                        <label>Claim Name <span class="required">*</span></label>
                        <input type="text" class="form-control rule-name" 
                            name="custom_rules[${ruleIndex}][name]" 
                            placeholder="e.g. Courier Charges" 
                            value="${name}"
                            oninput="updateClaimRulesPreview()">
                    </div>
                    <div class="form-group">
                        <label>Claim Type <span class="required">*</span></label>
                        <select class="form-control rule-type" 
                            name="custom_rules[${ruleIndex}][type]" 
                            onchange="toggleRuleFields(this)" required>
                            <option value="actual" ${type === 'actual' ? 'selected' : ''}>Actual Amount</option>
                            <option value="fixed" ${type === 'fixed' ? 'selected' : ''}>Fixed Amount</option>
                            <option value="per_km" ${type === 'per_km' ? 'selected' : ''}>Per KM</option>
                            <option value="per_day" ${type === 'per_day' ? 'selected' : ''}>Per Day</option>
                            <option value="per_night" ${type === 'per_night' ? 'selected' : ''}>Per Night</option>
                            <option value="per_hour" ${type === 'per_hour' ? 'selected' : ''}>Per Hour</option>
                            <option value="per_unit" ${type === 'per_unit' ? 'selected' : ''}>Per Unit</option>
                            <option value="percentage" ${type === 'percentage' ? 'selected' : ''}>Percentage</option>
                        </select>
                    </div>
                </div>

                <div class="rule-fields" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                    <!-- Actual Amount Fields -->
                    <div class="actual-fields" style="display: ${fieldVisibility.actual}; grid-template-columns: 1fr 1fr; gap: 10px; width: 100%;">
                        <div class="form-group">
                            <label>Minimum Amount (₹)</label>
                            <input type="number" class="form-control" name="custom_rules[${ruleIndex}][fields][min]" value="${fields.min || ''}">
                        </div>
                        <div class="form-group">
                            <label>Maximum Amount (₹)</label>
                            <input type="number" class="form-control" name="custom_rules[${ruleIndex}][fields][max]" value="${fields.max || ''}">
                        </div>
                    </div>

                    <!-- Fixed Amount Fields -->
                    <div class="fixed-fields" style="display: ${fieldVisibility.fixed}; grid-template-columns: 1fr; gap: 10px; width: 100%;">
                        <div class="form-group">
                            <label>Fixed Amount (₹)</label>
                            <input type="number" class="form-control" name="custom_rules[${ruleIndex}][fields][amount]" value="${fields.amount || ''}">
                        </div>
                    </div>

                    <!-- Per KM Fields -->
                    <div class="perkm-fields" style="display: ${fieldVisibility.per_km}; grid-template-columns: 1fr 1fr 1fr; gap: 10px; width: 100%;">
                        <div class="form-group">
                            <label>Rate Per KM (₹)</label>
                            <input type="number" step="0.1" class="form-control" name="custom_rules[${ruleIndex}][fields][rate_km]" value="${fields.rate_km || ''}">
                        </div>
                        <div class="form-group">
                            <label>Minimum KM</label>
                            <input type="number" class="form-control" name="custom_rules[${ruleIndex}][fields][min_km]" value="${fields.min_km || ''}">
                        </div>
                        <div class="form-group">
                            <label>Maximum KM</label>
                            <input type="number" class="form-control" name="custom_rules[${ruleIndex}][fields][max_km]" value="${fields.max_km || ''}">
                        </div>
                    </div>

                    <!-- Per Day Fields -->
                    <div class="perday-fields" style="display: ${fieldVisibility.per_day}; grid-template-columns: 1fr 1fr; gap: 10px; width: 100%;">
                        <div class="form-group">
                            <label>Rate Per Day (₹)</label>
                            <input type="number" class="form-control" name="custom_rules[${ruleIndex}][fields][rate_day]" value="${fields.rate_day || ''}">
                        </div>
                        <div class="form-group">
                            <label>Maximum Days</label>
                            <input type="number" class="form-control" name="custom_rules[${ruleIndex}][fields][max_days]" value="${fields.max_days || ''}">
                        </div>
                    </div>

                    <!-- Per Night Fields -->
                    <div class="pernight-fields" style="display: ${fieldVisibility.per_night}; grid-template-columns: 1fr 1fr; gap: 10px; width: 100%;">
                        <div class="form-group">
                            <label>Rate Per Night (₹)</label>
                            <input type="number" class="form-control" name="custom_rules[${ruleIndex}][fields][rate_night]" value="${fields.rate_night || ''}">
                        </div>
                        <div class="form-group">
                            <label>Maximum Nights</label>
                            <input type="number" class="form-control" name="custom_rules[${ruleIndex}][fields][max_nights]" value="${fields.max_nights || ''}">
                        </div>
                    </div>

                    <!-- Per Hour Fields -->
                    <div class="perhour-fields" style="display: ${fieldVisibility.per_hour}; grid-template-columns: 1fr 1fr; gap: 10px; width: 100%;">
                        <div class="form-group">
                            <label>Rate Per Hour (₹)</label>
                            <input type="number" class="form-control" name="custom_rules[${ruleIndex}][fields][rate_hour]" value="${fields.rate_hour || ''}">
                        </div>
                        <div class="form-group">
                            <label>Maximum Hours</label>
                            <input type="number" class="form-control" name="custom_rules[${ruleIndex}][fields][max_hours]" value="${fields.max_hours || ''}">
                        </div>
                    </div>

                    <!-- Per Unit Fields -->
                    <div class="perunit-fields" style="display: ${fieldVisibility.per_unit}; grid-template-columns: 1fr 1fr 1fr; gap: 10px; width: 100%;">
                        <div class="form-group">
                            <label>Rate Per Unit (₹)</label>
                            <input type="number" class="form-control" name="custom_rules[${ruleIndex}][fields][rate_unit]" value="${fields.rate_unit || ''}">
                        </div>
                        <div class="form-group">
                            <label>Unit Name</label>
                            <select class="form-control" name="custom_rules[${ruleIndex}][fields][unit]">
                                <option value="KG" ${fields.unit === 'KG' ? 'selected' : ''}>KG</option>
                                <option value="Box" ${fields.unit === 'Box' ? 'selected' : ''}>Box</option>
                                <option value="Litre" ${fields.unit === 'Litre' ? 'selected' : ''}>Litre</option>
                                <option value="Packet" ${fields.unit === 'Packet' ? 'selected' : ''}>Packet</option>
                                <option value="Hour" ${fields.unit === 'Hour' ? 'selected' : ''}>Hour</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Maximum Units</label>
                            <input type="number" class="form-control" name="custom_rules[${ruleIndex}][fields][max_units]" value="${fields.max_units || ''}">
                        </div>
                    </div>

                    <!-- Percentage Fields -->
                    <div class="percentage-fields" style="display: ${fieldVisibility.percentage}; grid-template-columns: 1fr 1fr; gap: 10px; width: 100%;">
                        <div class="form-group">
                            <label>Percentage (%)</label>
                            <input type="number" class="form-control" name="custom_rules[${ruleIndex}][fields][rate_percentage]" value="${fields.rate_percentage || ''}">
                        </div>
                        <div class="form-group">
                            <label>Maximum Amount (₹)</label>
                            <input type="number" class="form-control" name="custom_rules[${ruleIndex}][fields][max_amount]" value="${fields.max_amount || ''}">
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 12px; padding-top: 12px; border-top: 1px solid #edf2f7;">
                    <div class="form-group">
                        <label>Bill Required</label>
                        <select class="form-control" name="custom_rules[${ruleIndex}][bill_required]">
                            <option value="Yes" ${billReq === 'Yes' ? 'selected' : ''}>Yes</option>
                            <option value="No" ${billReq === 'No' ? 'selected' : ''}>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Photo Required</label>
                        <select class="form-control" name="custom_rules[${ruleIndex}][photo_required]">
                            <option value="No" ${photoReq === 'No' ? 'selected' : ''}>No</option>
                            <option value="Yes" ${photoReq === 'Yes' ? 'selected' : ''}>Yes</option>
                        </select>
                    </div>
                </div>
            `;
            return card;
        }

        function toggleRuleFields(select) {
            const card = select.closest('.range-box');
            const type = select.value;

            // Hide all field containers
            card.querySelectorAll('.actual-fields, .fixed-fields, .perkm-fields, .perday-fields, .pernight-fields, .perhour-fields, .perunit-fields, .percentage-fields')
                .forEach(el => el.style.display = 'none');

            // Show relevant fields based on type
            const fieldMap = {
                'actual': '.actual-fields',
                'fixed': '.fixed-fields',
                'per_km': '.perkm-fields',
                'per_day': '.perday-fields',
                'per_night': '.pernight-fields',
                'per_hour': '.perhour-fields',
                'per_unit': '.perunit-fields',
                'percentage': '.percentage-fields'
            };

            const target = card.querySelector(fieldMap[type]);
            if (target) {
                target.style.display = 'grid';
            }

            updateClaimRulesPreview();
        }

        function removeClaimRule(button) {
            const card = button.closest('.range-box');
            card.remove();
            updateClaimRulesPreview();

            // Reindex remaining cards
            const container = document.getElementById('claim-rules-container');
            const cards = container.querySelectorAll('.range-box');
            cards.forEach((card, index) => {
                card.setAttribute('data-rule-index', index);
                card.querySelectorAll('[name^="custom_rules["]').forEach(input => {
                    const name = input.getAttribute('name');
                    const newName = name.replace(/custom_rules\[\d+\]/, `custom_rules[${index}]`);
                    input.setAttribute('name', newName);
                });
            });
        }

        function updateClaimRulesPreview() {
            const container = document.getElementById('claim-rules-container');
            const preview = document.getElementById('claim-rules-preview');
            const policyDataInput = document.getElementById('policy_data');
            const cards = container.querySelectorAll('.range-box');

            if (cards.length === 0) {
                preview.innerHTML = '<span style="color: #94a3b8;">No claim rules added yet.</span>';
                policyDataInput.value = '';
                return;
            }

            const policyData = [];
            let html = '<div style="display: flex; flex-wrap: wrap; gap: 8px;">';

            cards.forEach(card => {
                const nameInput = card.querySelector('.rule-name');
                const typeInput = card.querySelector('.rule-type');
                const name = nameInput?.value?.trim() || 'Unnamed Rule';
                const type = typeInput?.value || 'actual';

                const typeLabels = {
                    'actual': 'Actual Amount',
                    'fixed': 'Fixed Amount',
                    'per_km': 'Per KM',
                    'per_day': 'Per Day',
                    'per_night': 'Per Night',
                    'per_hour': 'Per Hour',
                    'per_unit': 'Per Unit',
                    'percentage': 'Percentage'
                };

                html += `<span class="preview-item"><strong>${name}</strong> (${typeLabels[type] || type})</span>`;

                // Build rule data with all fields (including nulls)
                const fields = {};
                card.querySelectorAll('[name*="[fields]"]').forEach(input => {
                    const match = input.getAttribute('name').match(/\[fields\]\[(\w+)\]/);
                    if (match) {
                        fields[match[1]] = input.value !== '' ? input.value : null;
                    }
                });

                const ruleData = {
                    name: name,
                    type: type,
                    fields: fields,
                    bill_required: card.querySelector('[name$="[bill_required]"]')?.value || 'Yes',
                    photo_required: card.querySelector('[name$="[photo_required]"]')?.value || 'No'
                };
                policyData.push(ruleData);
            });

            html += '</div>';
            preview.innerHTML = html;
            policyDataInput.value = JSON.stringify(policyData);
        }

        // Initialize with one default rule for custom policy
        function initializeCustomRules() {
            const container = document.getElementById('claim-rules-container');
            if (container && container.children.length === 0) {
                addClaimRule();
            }
        }

        // --- Update Policy (submit) ---
        function updatePolicy(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');

            // Validate required fields
            const policyName = document.getElementById('policy_name').value.trim();
            const category = document.getElementById('policy_category').value;
            const financialYear = document.getElementById('financial_year').value;
            const calculationType = document.getElementById('calculation_type').value;
            const effectiveFrom = document.querySelector('[name="effective_from"]').value;

            if (!financialYear) { showToast('Please select a financial year.', 'error'); return false; }
            if (!calculationType) { showToast('Please select a calculation type.', 'error'); return false; }
            if (!effectiveFrom) { showToast('Please select an effective from date.', 'error'); return false; }
            if (!policyName) { showToast('Please enter a policy name.', 'error'); return false; }
            if (!category) { showToast('Please select a policy category.', 'error'); return false; }

            // For custom category, ensure rules are valid
            if (category === 'custom') {
                updateClaimRulesPreview();
                const container = document.getElementById('claim-rules-container');
                const cards = container.querySelectorAll('.range-box');
                if (cards.length === 0) {
                    showToast('Please add at least one claim rule.', 'error');
                    return false;
                }
                let hasEmptyNames = false;
                cards.forEach(card => {
                    const nameInput = card.querySelector('.rule-name');
                    if (!nameInput || !nameInput.value.trim()) {
                        hasEmptyNames = true;
                    }
                });
                if (hasEmptyNames) {
                    showToast('Please provide names for all claim rules.', 'error');
                    return false;
                }
            }

            submitBtn.classList.add('btn-loading');
            submitText.textContent = 'Updating...';
            submitSpinner.style.display = 'inline-block';

            const form = document.getElementById('policy-form');
            const formData = new FormData(form);

            // No _method spoofing – we use POST directly (matches route)
            // The policy ID is already in the hidden field

            fetch(`/institute-admin/reimbursement-policies/${policyId}`, {
                method: 'POST',  // Route is POST, so we use POST
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(async response => {
                    const result = await response.json();
                    if (!response.ok) {
                        throw new Error(result.message || 'Failed to update policy');
                    }
                    return result;
                })
                .then(data => {
                    showToast('✅ Reimbursement Policy updated successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = "/institute-admin/reimbursement-policies";
                    }, 2000);
                })
                .catch(err => {
                    showToast('❌ Error updating policy: ' + err.message, 'error');
                    submitBtn.classList.remove('btn-loading');
                    submitText.textContent = 'Update Policy';
                    submitSpinner.style.display = 'none';
                });

            return false;
        }

        // --- Initialize on load ---
        document.addEventListener('DOMContentLoaded', function () {
            loadPolicy();
            // If category is custom, initialize rules after prefill (but prefill already does it)
        });
    </script>

@endsection
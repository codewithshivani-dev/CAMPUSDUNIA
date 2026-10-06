@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<!-- Include SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Include QR Scanner Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<style>
    :root {
        --primary-color: #4361ee;
        --primary-dark: #3a0ca3;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * {
        box-sizing: border-box;
    }

    .pos-container {
        max-width: 100%;
        padding: 0 15px;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
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

    .page-header h1 {
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.5rem;
    }

    .page-header h1 i {
        background: rgba(255, 255, 255, 0.2);
        padding: 10px;
        border-radius: 12px;
        font-size: 1.3rem;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.7rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        color: white;
    }

    .btn-change-type {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.7rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .btn-change-type:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .configuration-badge {
        background: rgba(255, 255, 255, 0.15);
        padding: 0.1rem 0.6rem;
        border-radius: 20px;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: white;
    }

    .configuration-badge .costing-method {
        font-weight: 700;
        text-transform: uppercase;
        padding: 0.1rem 0.6rem;
        border-radius: 12px;
        font-size: 0.65rem;
        background: rgba(255, 255, 255, 0.2);
    }

    .stock-type-selection {
        background: white;
        border-radius: 20px;
        /* padding: 2.5rem; */
        padding: 2em 2em 2em 1.5em;
        box-shadow: var(--card-shadow);
        border: 2px solid var(--border-color);
        text-align: center;
    }

    .stock-type-selection h4 {
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .stock-type-selection .sub-text {
        color: var(--text-muted);
        margin-bottom: 2rem;
    }

    .type-options {
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        gap: 12px;
    }

    .type-option {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1.5rem 2rem;
        border: 3px solid var(--border-color);
        border-radius: 16px;
        cursor: pointer;
        transition: var(--transition);
        background: #f8fafc;
        position: relative;
        text-align: left;
    }

    .type-option:hover {
        border-color: var(--primary-color);
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    }

    .type-option.selected {
        border-color: var(--primary-color);
        background: #eff6ff;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
    }

    .type-option .type-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        flex-shrink: 0;
    }

    .type-option .type-info {
        flex: 1;
    }

    .type-option .type-info h5 {
        font-weight: 700;
        color: var(--text-dark);
        margin: 0 0 0.2rem 0;
    }

    .type-option .type-info p {
        margin: 0;
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .type-option .type-badge {
        align-self: flex-start;
    }

    .pos-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 1.5rem;
        min-height: 600px;
    }

    .pos-left {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        border: 2px solid var(--border-color);
    }

    .pos-right {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        border: 2px solid var(--border-color);
        display: flex;
        flex-direction: column;
        max-height: 800px;
    }

    .search-section {
        margin-bottom: 1.5rem;
    }

    .search-wrapper {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .search-wrapper .search-input {
        flex: 1;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 0.7rem 1.2rem;
        font-size: 1rem;
        transition: var(--transition);
        background: #f8fafc;
    }

    .search-wrapper .search-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        background: white;
        outline: none;
    }

    .search-wrapper .btn-scan {
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.7rem 1.2rem;
        font-weight: 600;
        transition: var(--transition);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .search-wrapper .btn-scan:hover:not(:disabled) {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }

    .search-wrapper .btn-scan:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .search-filters {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
        flex-wrap: wrap;
    }

    .search-filters .filter-chip {
        padding: 0.3rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        background: white;
        cursor: pointer;
        transition: var(--transition);
        color: var(--text-muted);
    }

    .search-filters .filter-chip:hover {
        border-color: var(--primary-color);
        color: var(--text-dark);
    }

    .search-filters .filter-chip.active {
        border-color: var(--primary-color);
        background: #eff6ff;
        color: var(--primary-color);
    }

    .items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 0.75rem;
        max-height: 450px;
        overflow-y: auto;
        padding-right: 5px;
    }

    .items-grid::-webkit-scrollbar {
        width: 6px;
    }

    .items-grid::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .items-grid::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 10px;
    }

    .item-card {
        background: #f8fafc;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 0.75rem;
        cursor: pointer;
        transition: var(--transition);
        position: relative;
    }

    .item-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .item-card .item-code {
        font-size: 0.7rem;
        color: var(--text-muted);
        font-weight: 600;
        background: #e2e8f0;
        padding: 0.1rem 0.5rem;
        border-radius: 4px;
        display: inline-block;
    }

    .item-card .item-name {
        font-weight: 700;
        color: var(--text-dark);
        font-size: 0.9rem;
        margin: 0.3rem 0 0.2rem;
        line-height: 1.2;
    }

    .item-card .item-price {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--success-color);
    }

    .item-card .item-stock {
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    .item-card .item-stock.low {
        color: var(--danger-color);
    }

    .item-card .add-quick {
        position: absolute;
        bottom: 0.5rem;
        right: 0.5rem;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        cursor: pointer;
        opacity: 1;
    }

    .item-card .add-quick:hover {
        background: var(--primary-dark);
        transform: scale(1.1);
    }

    .item-card .stock-badge {
        width: max-content;
        top: 0.5rem;
        right: 0.5rem;
        font-size: 0.6rem;
        background: #d1fae5;
        color: #065f46;
        padding: 0.1rem 0.5rem;
        border-radius: 10px;
        font-weight: 600;
    }

    .item-card .stock-badge.low {
        background: #fee2e2;
        color: #991b1b;
    }

    .cart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--border-color);
        margin-bottom: 0.75rem;
    }

    .cart-header h5 {
        margin: 0;
        font-weight: 700;
        color: var(--text-dark);
    }

    .cart-header .cart-count {
        background: var(--primary-color);
        color: white;
        padding: 0.4rem 0.7rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .cart-items {
        flex: 1;
        overflow-y: auto;
        max-height: 350px;
    }

    .cart-items::-webkit-scrollbar {
        width: 6px;
    }

    .cart-items::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .cart-items::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 10px;
    }

    .cart-item-pos {
        align-items: center;
        gap: 0.75rem;
        padding: 0.6rem 0.75rem;
        background: #f8fafc;
        border-radius: 10px;
        margin-bottom: 0.4rem;
        border: 2px solid transparent;
        transition: var(--transition);
    }

    .cart-item-pos:hover {
        border-color: var(--border-color);
    }

    .cart-item-pos .ci-info {
        flex: 1;
        min-width: 0;
    }

    .cart-item-pos .ci-info .ci-name {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--text-dark);
    }

    .cart-item-pos .ci-info .ci-detail {
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    .cart-item-pos .ci-qty {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        background: white;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        padding: 0.1rem;
    }

    .cart-item-pos .ci-qty .qty-btn {
        width: 24px;
        height: 24px;
        border: none;
        background: transparent;
        border-radius: 6px;
        font-weight: 700;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .cart-item-pos .ci-qty .qty-btn:hover {
        background: #e2e8f0;
    }

    .cart-item-pos .ci-qty .qty-val {
        min-width: 25px;
        text-align: center;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .cart-item-pos .ci-price {
        font-weight: 700;
        color: var(--success-color);
        font-size: 0.9rem;
        min-width: 60px;
        text-align: right;
    }

    .cart-item-pos .ci-remove {
        background: none;
        border: none;
        color: var(--danger-color);
        cursor: pointer;
        font-size: 0.8rem;
        padding: 0.2rem 0.4rem;
        border-radius: 6px;
        transition: var(--transition);
    }

    .cart-item-pos .ci-remove:hover {
        background: #fee2e2;
    }

    .empty-cart-pos {
        text-align: center;
        padding: 2rem 0;
        color: var(--text-muted);
    }

    .empty-cart-pos i {
        font-size: 3rem;
        color: #d1d5db;
        display: block;
        margin-bottom: 0.5rem;
    }

    .cart-summary {
        border-top: 2px solid var(--border-color);
        padding-top: 0.75rem;
        margin-top: 0.5rem;
    }

    .summary-row-pos {
        display: flex;
        justify-content: space-between;
        padding: 0.2rem 0;
        font-size: 0.85rem;
    }

    .summary-row-pos.total {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-dark);
        border-top: 2px dashed var(--border-color);
        padding-top: 0.5rem;
        margin-top: 0.3rem;
    }

    .summary-row-pos .label {
        color: var(--text-muted);
    }

    .summary-row-pos .value {
        font-weight: 600;
    }

    .summary-row-pos.total .value {
        color: var(--success-color);
        font-size: 1.3rem;
    }

    .cart-actions-pos {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
        flex-wrap: wrap;
    }

    .cart-actions-pos .btn-action {
        flex: 1;
        min-width: 80px;
        padding: 0.6rem;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.85rem;
        transition: var(--transition);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-action.btn-checkout-pos {
        background: var(--success-color);
        color: white;
        flex: 2;
    }

    .btn-action.btn-checkout-pos:hover:not(:disabled) {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.35);
    }

    .btn-action.btn-checkout-pos:disabled {
        background: #94a3b8;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .btn-action.btn-clear-pos {
        background: #fee2e2;
        color: var(--danger-color);
    }

    .btn-action.btn-clear-pos:hover {
        background: #fecaca;
    }

    .location-selector-pos {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .location-selector-pos .loc-group {
        flex: 1;
        min-width: 150px;
    }

    .location-selector-pos .loc-group label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        display: block;
        margin-bottom: 0.2rem;
    }

    .location-selector-pos .loc-group label .required {
        color: var(--danger-color);
    }

    .location-selector-pos .loc-group select {
        width: 100%;
        padding: 0.4rem 0.8rem;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        font-size: 0.85rem;
        transition: var(--transition);
        background: white;
    }

    .location-selector-pos .loc-group select:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .location-selector-pos .loc-group select.error {
        border-color: var(--danger-color);
    }

    .location-notice {
        font-size: 0.8rem;
        color: var(--danger-color);
        margin-top: 0.3rem;
        display: none;
    }

    .location-notice.show {
        display: block;
    }

    .customer-section {
        background: #f8fafc;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        border: 2px solid var(--border-color);
    }

    .customer-section .customer-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 0.5rem;
        align-items: end;
    }

    .customer-section .customer-row .form-group {
        margin-bottom: 0;
    }

    .customer-section .customer-row label {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-muted);
        display: block;
        margin-bottom: 0.1rem;
    }

    .customer-section .customer-row label .required {
        color: var(--danger-color);
    }

    .customer-section .customer-row input,
    .customer-section .customer-row select {
        width: 100%;
        padding: 0.3rem 0.6rem;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        font-size: 0.8rem;
        transition: var(--transition);
    }

    .customer-section .customer-row input:focus,
    .customer-section .customer-row select:focus {
        border-color: var(--primary-color);
        outline: none;
    }

    .customer-section .customer-row input.error {
        border-color: var(--danger-color);
    }

    .customer-section .customer-type-btns {
        display: flex;
        gap: 0.3rem;
        margin-bottom: 0.5rem;
    }

    .customer-section .customer-type-btns .ct-btn {
        padding: 0.2rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        background: white;
        font-weight: 600;
        font-size: 0.8rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .customer-section .customer-type-btns .ct-btn.active {
        border-color: var(--primary-color);
        background: #eff6ff;
        color: var(--primary-color);
    }

    .customer-section .customer-type-btns .ct-btn:hover {
        border-color: var(--primary-color);
    }

    .transfer-config {
        background: #f8fafc;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        border: 2px solid var(--border-color);
    }

    .transfer-config .transfer-row {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 0.75rem;
        align-items: end;
    }

    .transfer-config .transfer-row .form-group {
        margin-bottom: 0;
    }

    .transfer-config .transfer-row label {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-muted);
        display: block;
        margin-bottom: 0.1rem;
    }

    .transfer-config .transfer-row select {
        width: 100%;
        padding: 0.4rem 0.8rem;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        font-size: 0.85rem;
        transition: var(--transition);
        background: white;
    }

    .transfer-config .transfer-row select:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .transfer-config .transfer-row .arrow-icon {
        font-size: 1.5rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        padding-bottom: 0.3rem;
    }

    .transfer-config .transfer-sub-types {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.5rem;
        flex-wrap: wrap;
    }

    .transfer-config .transfer-sub-types .ts-btn {
        padding: 0.2rem 1.2rem;
        border: 2px solid var(--border-color);
        border-radius: 20px;
        background: white;
        font-weight: 600;
        font-size: 0.75rem;
        cursor: pointer;
        transition: var(--transition);
        color: var(--text-muted);
    }

    .transfer-config .transfer-sub-types .ts-btn.active {
        border-color: var(--primary-color);
        background: #eff6ff;
        color: var(--primary-color);
    }

    .transfer-config .transfer-sub-types .ts-btn:hover {
        border-color: var(--primary-color);
    }

    .tracking-status-badge {
        font-size: 0.6rem;
        padding: 0.1rem 0.5rem;
        border-radius: 10px;
        background: #dbeafe;
        color: #1e40af;
        margin-left: 0.3rem;
        font-weight: 600;
    }

    .tracking-status-badge.auto {
        background: #dcfce7;
        color: #166534;
    }

    .tracking-status-badge.manual {
        background: #fef3c7;
        color: #92400e;
    }

    /* Logistics Modal */
    .logistics-modal .modal-dialog {
        max-width: 600px;
    }

    .logistics-modal .modal-content {
        border-radius: 16px;
        border: none;
    }

    .logistics-modal .modal-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 1.25rem 1.5rem;
    }

    .logistics-modal .modal-body {
        padding: 1.5rem;
    }

    .logistics-modal .form-group {
        margin-bottom: 1rem;
    }

    .logistics-modal .form-group label {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--text-dark);
        display: block;
        margin-bottom: 0.25rem;
    }

    .logistics-modal .form-group input,
    .logistics-modal .form-group select,
    .logistics-modal .form-group textarea {
        width: 100%;
        padding: 0.5rem 0.8rem;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .logistics-modal .form-group input:focus,
    .logistics-modal .form-group select:focus,
    .logistics-modal .form-group textarea:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .logistics-modal .modal-footer {
        padding: 1rem 1.5rem 1.5rem;
        border-top: 2px solid var(--border-color);
    }

    .batch-badge {
        font-size: 0.6rem;
        padding: 0.1rem 0.5rem;
        border-radius: 10px;
        background: #dbeafe;
        color: #1e40af;
        margin-left: 0.3rem;
    }

    .serial-badge {
        font-size: 0.6rem;
        padding: 0.1rem 0.5rem;
        border-radius: 10px;
        background: #fef3c7;
        color: #92400e;
        margin-left: 0.3rem;
    }

    .tracking-indicator {
        font-size: 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .tracking-indicator .icon {
        font-size: 0.6rem;
    }

    .tracking-indicator.auto-complete .icon {
        color: var(--success-color);
    }

    .tracking-indicator.auto-complete {
        color: var(--success-color);
    }

    .tracking-indicator.auto-processing .icon {
        color: var(--warning-color);
    }

    .tracking-indicator.auto-processing {
        color: var(--warning-color);
    }

    .fifo-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: rgba(16, 185, 129, 0.2);
        color: #065f46;
        padding: 0.1rem 0.6rem;
        border-radius: 12px;
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-left: 0.3rem;
    }

    .fifo-badge i {
        font-size: 0.5rem;
    }

    .processing-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    .processing-overlay.show {
        display: flex;
    }

    .processing-overlay .spinner {
        width: 50px;
        height: 50px;
        border: 4px solid #e2e8f0;
        border-top: 4px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    .processing-overlay p {
        color: white;
        margin-top: 1rem;
        font-weight: 600;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Hidden Additional Discount Field */
    .additional-discount-field {
        display: none !important;
    }

    @media (max-width: 992px) {
        .pos-layout {
            grid-template-columns: 1fr;
        }
        .pos-right {
            max-height: none;
        }
        .cart-items {
            max-height: 250px;
        }
        .customer-section .customer-row {
            grid-template-columns: 1fr 1fr;
        }
        .transfer-config .transfer-row {
            grid-template-columns: 1fr;
        }
        .transfer-config .transfer-row .arrow-icon {
            transform: rotate(90deg);
            justify-content: center;
            padding: 0.3rem 0;
        }
        .type-options {
            flex-direction: column;
        }
    }

    @media (max-width: 576px) {
        .page-header {
            flex-direction: column;
            text-align: center;
            padding: 1rem;
        }
        .stock-type-selection {
            padding: 1.5rem;
        }
        .type-option {
            flex-direction: column;
            text-align: center;
            padding: 1.5rem;
        }
        .pos-left,
        .pos-right {
            padding: 1rem;
        }
        .items-grid {
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        }
        .customer-section .customer-row {
            grid-template-columns: 1fr;
        }
        .search-wrapper {
            flex-wrap: wrap;
        }
        .cart-actions-pos {
            flex-direction: column;
        }
        .scanner-overlay .scanner-container {
            padding: 1rem;
        }
        .checkout-modal .modal-body {
            padding: 1rem;
        }
        .checkout-modal .modal-footer {
            flex-direction: column;
            gap: 0.5rem;
        }
        .checkout-modal .modal-footer .btn {
            width: 100%;
        }
    }

    .ci-qty .qty-input {
        width: 45px;
        text-align: center;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        padding: 2px 4px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: var(--transition);
        background: white;
        -moz-appearance: textfield;
    }

    .ci-qty .qty-input::-webkit-outer-spin-button,
    .ci-qty .qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .ci-qty .qty-input:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .ci-qty .qty-input.error {
        border-color: var(--danger-color);
        background: #fee2e2;
    }

    .ci-qty .qty-input:disabled {
        background: #f1f5f9;
        cursor: not-allowed;
    }

    .capacityStatus {
        display: flex;
        width: 100%;
        justify-content: space-between;
    }

    .scanner-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 20px;
    }

    .scanner-overlay.show {
        display: flex;
    }

    .scanner-overlay .scanner-container {
        background: white;
        border-radius: 24px;
        padding: 1.5rem;
        max-width: 500px;
        width: 100%;
        position: relative;
    }

    .scanner-overlay .scanner-container .scanner-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .scanner-overlay .scanner-container .scanner-header h4 {
        margin: 0;
        font-weight: 700;
        color: var(--text-dark);
    }

    .scanner-overlay .scanner-container .scanner-header .btn-close-scanner {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--text-muted);
        cursor: pointer;
        transition: var(--transition);
    }

    .scanner-overlay .scanner-container .scanner-header .btn-close-scanner:hover {
        color: var(--danger-color);
    }

    .scanner-overlay .scanner-container #qr-reader {
        width: 100%;
        border-radius: 12px;
        overflow: hidden;
    }

    .scanner-overlay .scanner-container .scanner-footer {
        text-align: center;
        margin-top: 1rem;
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .scanner-overlay .scanner-container .manual-input {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }

    .scanner-overlay .scanner-container .manual-input input {
        flex: 1;
        padding: 0.5rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        font-size: 0.9rem;
    }

    .scanner-overlay .scanner-container .manual-input input:focus {
        border-color: var(--primary-color);
        outline: none;
    }

    .scanner-overlay .scanner-container .manual-input button {
        padding: 0.5rem 1.5rem;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .scanner-overlay .scanner-container .manual-input button:hover {
        background: var(--primary-dark);
    }

    .checkout-modal .modal-dialog {
        max-width: 600px;
    }

    .checkout-modal .modal-content {
        border-radius: 20px;
        border: none;
    }

    .checkout-modal .modal-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border-radius: 20px 20px 0 0;
        padding: 1.5rem 2rem;
    }

    .checkout-modal .modal-header .btn-close {
        color: white;
        filter: brightness(0) invert(1);
    }

    .checkout-modal .modal-body {
        padding: 1.5rem 2rem;
        max-height: 70vh;
        overflow-y: auto;
    }

    .checkout-modal .checkout-summary {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .checkout-modal .checkout-summary .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 0.3rem 0;
        font-size: 0.9rem;
        border-bottom: 1px solid var(--border-color);
    }

    .checkout-modal .checkout-summary .summary-item:last-child {
        border-bottom: none;
    }

    .checkout-modal .checkout-summary .summary-item.total {
        font-weight: 700;
        font-size: 1.2rem;
        color: var(--success-color);
        border-top: 2px solid var(--border-color);
        padding-top: 0.5rem;
        margin-top: 0.3rem;
    }

    .checkout-modal .checkout-items {
        max-height: 200px;
        overflow-y: auto;
        margin-bottom: 1rem;
    }

    .checkout-modal .checkout-items .item-row {
        display: flex;
        justify-content: space-between;
        padding: 0.3rem 0;
        font-size: 0.85rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .checkout-modal .form-group {
        margin-bottom: 0.75rem;
    }

    .checkout-modal .form-group label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-muted);
        display: block;
        margin-bottom: 0.2rem;
    }

    .checkout-modal .form-group label .required {
        color: var(--danger-color);
    }

    .checkout-modal .form-group input,
    .checkout-modal .form-group select {
        width: 100%;
        padding: 0.5rem 0.8rem;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .checkout-modal .form-group input:focus,
    .checkout-modal .form-group select:focus {
        border-color: var(--primary-color);
        outline: none;
    }

    .checkout-modal .form-group input.error {
        border-color: var(--danger-color);
    }

    .checkout-modal .form-group .error-text {
        font-size: 0.75rem;
        color: var(--danger-color);
        display: none;
        margin-top: 0.2rem;
    }

    .checkout-modal .form-group .error-text.show {
        display: block;
    }

    .checkout-modal .modal-footer {
        padding: 1rem 2rem 1.5rem;
        border-top: 2px solid var(--border-color);
    }

    .btn-confirm-checkout {
        background: var(--success-color);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.7rem 2rem;
        font-weight: 700;
        font-size: 1rem;
        transition: var(--transition);
        cursor: pointer;
    }

    .btn-confirm-checkout:hover:not(:disabled) {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.35);
    }

    .btn-confirm-checkout:disabled {
        background: #94a3b8;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .btn-cancel-checkout {
        background: #f1f5f9;
        color: var(--text-dark);
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 0.7rem 2rem;
        font-weight: 600;
        transition: var(--transition);
        cursor: pointer;
    }

    .btn-cancel-checkout:hover {
        background: #e2e8f0;
    }

    .cash-fields {
        display: none;
    }

    .cash-fields.show {
        display: flex;
    }

    .mode-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.3rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        background: rgba(255, 255, 255, 0.15);
        color: white;
    }

    .mode-badge i {
        font-size: 0.9rem;
    }

    /* Hidden Additional Discount Field */
    .additional-discount-field {
        display: none !important;
    }
</style>

<!-- ============================================ -->
<!-- PAGE HEADER -->
<!-- ============================================ -->
<div class="pos-container">
    <div class="page-header">
        <div>
            <div class="d-flex gap-2" style="align-items: center;">
                <h1><i class="fas fa-arrow-right"></i> Stock Out</h1>
                <div style="height: max-content;">
                    @if($configuration)
                    <!-- <span class="configuration-badge"> -->
                        <!-- <i class="fas fa-cog"></i>
                        {{ $configuration->configuration_name }} -->
                        <span class="configuration-badge costing-method">{{ $configuration->costing_method }}
                            @if($configuration->costing_method == 'FIFO')
                                <i class="fas fa-check-circle" style="color: #ffffff;"></i>
                            @endif
                        </span>
                    <!-- </span> -->
                    @endif
                    
                    <span class="mode-badge" id="modeBadge" style="display:none;">
                        <i class="fas fa-check-circle"></i>
                        <span id="modeBadgeText">Sell</span>
                    </span>
                </div>
            </div>
            <p>POS-style checkout and inventory movement</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="button" class="btn-change-type" id="changeTypeBtn" style="display:none;">
                <i class="fas fa-undo"></i> Change Type
            </button>
            <a href="{{ route('inventory.stock-out.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Stock Out
            </a>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- STOCK TYPE SELECTION - STEP 1 -->
    <!-- ============================================ -->
    <div id="stockTypeSelection" class="stock-type-selection">
        <h4>Select Stock Out Type</h4>
        <p class="sub-text">Choose the type of stock movement you want to perform</p>
        
        <div class="type-options">
            <div class="type-option col-md-6" data-type="sell" onclick="selectStockType('sell')">
                <div class="type-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="type-info">
                    <h5>Sell</h5>
                    <p>Record a sales transaction with customer details and payment</p>
                </div>
                <div class="type-badge">
                    <span class="badge bg-success">Sales</span>
                </div>
            </div>
            
            <div class="type-option col-md-6" data-type="transfer" onclick="selectStockType('transfer')">
                <div class="type-icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="type-info">
                    <h5>Transfer</h5>
                    <p>Move items between warehouses or stores</p>
                </div>
                <div class="type-badge">
                    <span class="badge bg-primary">Inventory Move</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- FORM START -->
    <!-- ============================================ -->
    <div id="stockOutFormWrapper" style="display:none;">
        <form method="POST" action="{{ route('inventory.stock-out.store') }}" id="stockOutForm">
            @csrf

            <input type="hidden" name="type" id="selectedType" value="">
            <input type="hidden" name="sub_type" id="selectedSubType" value="">
            <input type="hidden" name="items_data" id="itemsDataInput" value="">
            <input type="hidden" name="batch_data" id="batchDataInput" value="">
            <input type="hidden" name="serial_data" id="serialDataInput" value="">
            <input type="hidden" name="subtotal" id="subtotalInput" value="0">
            <input type="hidden" name="total_amount" id="totalAmountInput" value="0">
            <input type="hidden" name="discount_amount" id="discountAmountInput" value="0">
            <input type="hidden" name="tax_amount" id="taxAmountInput" value="0">
            <input type="hidden" name="amount_received" id="amountReceivedInput" value="0">
            <input type="hidden" name="change_amount" id="changeAmountInput" value="0">
            <input type="hidden" name="payment_method" id="paymentMethodInput" value="cash">
            <!-- Hidden additional discount field - maps to discount_percentage from inventory_items table -->
            <input type="hidden" name="additional_discount" id="additionalDiscountInput" value="0">

            <!-- ============================================ -->
            <!-- SELL MODE -->
            <!-- ============================================ -->
            <div id="sellMode" class="pos-layout">
                <!-- LEFT PANEL -->
                <div class="pos-left">
                    <!-- Location Selector -->
                    <div class="location-selector-pos">
                        <div class="loc-group">
                            <label><i class="fas fa-warehouse"></i> Warehouse <span class="required">*</span></label>
                            <select name="sell_from_warehouse_id" id="sellFromWarehouse" class="form-select">
                                <option value="">-- Select Warehouse --</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name }}</option>
                                @endforeach
                            </select>
                            <div class="location-notice show" id="warehouseNotice">Please select a warehouse to see items</div>
                        </div>
                        <div class="loc-group">
                            <label><i class="fas fa-store"></i> Store (Optional)</label>
                            <select name="sell_from_store_id" id="sellFromStore" class="form-select">
                                <option value="">-- All Stores --</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" data-warehouse-id="{{ $store->warehouse_id }}">
                                        {{ $store->store_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="search-section">
                        <div class="search-wrapper">
                            <input type="text" id="itemSearch" class="search-input" placeholder="Search by name, code, barcode..." disabled>
                            <button type="button" class="btn-scan" id="scanBtn" disabled>
                                <i class="fas fa-qrcode"></i> Scan
                            </button>
                        </div>
                        <div class="search-filters" id="categoryFilters">
                            <button type="button" class="filter-chip active" data-category="all">All</button>
                            @foreach($categories as $category)
                                <button type="button" class="filter-chip" data-category="{{ $category->id }}">{{ $category->category_name }}</button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Items Grid -->
                    <div id="itemsGrid" class="items-grid">
                        <div style="grid-column:1/-1; text-align:center; padding:2rem; color:var(--text-muted);">
                            <i class="fas fa-warehouse" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i>
                            <p>Please select a warehouse to see available items</p>
                        </div>
                    </div>
                </div>

                <!-- RIGHT PANEL - CART -->
                <div class="pos-right">
                    <div class="cart-header">
                        <h5><i class="fas fa-shopping-bag"></i> Cart <span id="cartTrackingInfo" style="font-size:0.65rem; font-weight:400; color:var(--text-muted);"></span></h5>
                        <span class="cart-count" id="cartCount">0</span>
                    </div>

                    <!-- Customer Section -->
                    <div class="customer-section">
                        <div class="customer-type-btns">
                            <button type="button" class="ct-btn active" data-type="individual">Individual</button>
                            <button type="button" class="ct-btn" data-type="business">Business</button>
                        </div>
                        <input type="hidden" name="customer_type" id="customerType" value="individual">
                        <div class="customer-row">
                            <div class="form-group">
                                <label>Customer Name <span class="required">*</span></label>
                                <input type="text" name="customer_name" id="customerName" placeholder="Full name">
                            </div>
                            <div class="form-group">
                                <label>Phone <span class="required">*</span></label>
                                <input type="text" name="customer_phone" id="customerPhone" placeholder="Phone number">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="customer_email" id="customerEmail" placeholder="Email">
                            </div>
                        </div>
                        <div id="businessFields" style="display:none; margin-top:0.5rem;">
                            <div class="customer-row">
                                <div class="form-group">
                                    <label>GST Number</label>
                                    <input type="text" name="gst_number" id="gstNumber" placeholder="GST Number">
                                </div>
                                <div class="form-group">
                                    <label>PAN Number</label>
                                    <input type="text" name="pan_number" id="panNumber" placeholder="PAN Number">
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" name="customer_address" id="customerAddress" placeholder="Address">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Items -->
                    <div class="cart-items" id="cartItems">
                        <div class="empty-cart-pos">
                            <i class="fas fa-shopping-bag"></i>
                            <p>No items in cart</p>
                            <small>Click on items to add</small>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="cart-summary" id="cartSummary">
                        <div class="summary-row-pos">
                            <span class="label">Subtotal</span>
                            <span class="value" id="summarySubtotal">₹ 0.00</span>
                        </div>
                        <div class="summary-row-pos">
                            <span class="label">Item Discount</span>
                            <span class="value" id="summaryItemDiscount">- ₹ 0.00</span>
                        </div>
                        <div class="summary-row-pos additional-discount-field">
                            <span class="label">Additional Discount</span>
                            <span class="value" id="summaryAdditionalDiscount">- ₹ 0.00</span>
                        </div>
                        <div class="summary-row-pos">
                            <span class="label">Tax</span>
                            <span class="value" id="summaryTax">+ ₹ 0.00</span>
                        </div>
                        <div class="summary-row-pos total">
                            <span class="label">Total</span>
                            <span class="value" id="summaryTotal">₹ 0.00</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="cart-actions-pos">
                        <button type="button" class="btn-action btn-clear-pos" id="clearCartBtn">
                            <i class="fas fa-trash"></i> Clear
                        </button>
                        <button type="button" class="btn-action btn-checkout-pos" id="checkoutBtn" disabled>
                            <i class="fas fa-credit-card"></i> Checkout
                        </button>
                    </div>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- TRANSFER MODE -->
            <!-- ============================================ -->
            <div id="transferMode" style="display:none;">
                <div class="pos-layout">
                    <!-- LEFT PANEL -->
                    <div class="pos-left">
                        <!-- Transfer Config -->
                        <div class="transfer-config">
                            <div class="transfer-sub-types">
                                <button type="button" class="ts-btn active" data-subtype="warehouse_to_warehouse">
                                    <i class="fas fa-warehouse"></i> WH → WH
                                </button>
                                <button type="button" class="ts-btn" data-subtype="store_to_store">
                                    <i class="fas fa-store"></i> Store → Store
                                </button>
                                <button type="button" class="ts-btn" data-subtype="warehouse_to_store">
                                    <i class="fas fa-warehouse"></i> → <i class="fas fa-store"></i> WH → Store
                                </button>
                            </div>
                            <p class="small text-muted my-1">WH - Warehouse</p>

                            <div class="transfer-row" style="margin-top:0.75rem;">
                                <div class="form-group">
                                    <label>From Location <span class="required">*</span></label>
                                    <select id="transferFromLocation">
                                        <option value="">Select Source</option>
                                    </select>
                                </div>
                                <div class="arrow-icon"><i class="fas fa-arrow-right"></i></div>
                                <div class="form-group">
                                    <label>To Location <span class="required">*</span></label>
                                    <select id="transferToLocation">
                                        <option value="">Select Destination</option>
                                    </select>
                                </div>
                            </div>
                            <div class="location-notice" id="transferLocationNotice" style="display:block; margin-top:0.5rem;">
                                Please select both source and destination locations
                            </div>
                        </div>

                        <!-- Search -->
                        <div class="search-section">
                            <div class="search-wrapper">
                                <input type="text" id="transferSearch" class="search-input" placeholder="Search items to transfer..." disabled>
                                <button type="button" class="btn-scan" id="transferScanBtn" disabled>
                                    <i class="fas fa-qrcode"></i> Scan
                                </button>
                            </div>
                        </div>

                        <!-- Items Grid -->
                        <div id="transferItemsGrid" class="items-grid">
                            <div style="grid-column:1/-1; text-align:center; padding:2rem; color:var(--text-muted);">
                                <i class="fas fa-warehouse" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i>
                                <p>Please select source and destination locations to see items</p>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT PANEL -->
                    <div class="pos-right">
                        <div class="cart-header">
                            <h5><i class="fas fa-exchange-alt"></i> Transfer Cart</h5>
                            <span class="cart-count" id="transferCartCount">0</span>
                        </div>

                        <!-- Cart Items -->
                        <div class="cart-items" id="transferCartItems">
                            <div class="empty-cart-pos">
                                <i class="fas fa-box-open"></i>
                                <p>No items in transfer cart</p>
                                <small>Click on items to add</small>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="cart-summary">
                            <div class="summary-row-pos total">
                                <span class="label">Total Items</span>
                                <span class="value" id="transferTotalItems">0</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="cart-actions-pos">
                            <button type="button" class="btn-action btn-clear-pos" id="clearTransferBtn">
                                <i class="fas fa-trash"></i> Clear
                            </button>
                            <button type="button" class="btn-action btn-checkout-pos" id="transferCheckoutBtn" disabled>
                                <i class="fas fa-exchange-alt"></i> Transfer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <!-- ============================================ -->
    <!-- QR SCANNER OVERLAY -->
    <!-- ============================================ -->
    <div class="scanner-overlay" id="scannerOverlay">
        <div class="scanner-container">
            <div class="scanner-header">
                <h4><i class="fas fa-qrcode"></i> Scan Barcode / QR Code</h4>
                <button type="button" class="btn-close-scanner" id="scannerCloseBtn">&times;</button>
            </div>
            <div id="qr-reader"></div>
            <div class="scanner-footer">
                <div class="scanning-indicator">
                    <span class="dot"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                    <span style="margin-left:0.5rem;">Scanning...</span>
                </div>
                <p style="margin-top:0.5rem; font-size:0.75rem;">Position the barcode within the frame</p>
                <div class="manual-input">
                    <input type="text" id="manualBarcodeInput" placeholder="Or enter barcode manually...">
                    <button type="button" id="manualBarcodeBtn">Find</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- LOGISTICS MODAL -->
    <!-- ============================================ -->
    <div class="modal fade logistics-modal" id="logisticsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-truck"></i> Logistics Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: brightness(0) invert(1);"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Transporter Name</label>
                        <input type="text" id="logisticsTransporter" class="form-control" placeholder="Enter transporter name">
                    </div>
                    <div class="form-group">
                        <label>Vehicle Number</label>
                        <input type="text" id="logisticsVehicle" class="form-control" placeholder="Enter vehicle number">
                    </div>
                    <div class="form-group">
                        <label>Driver Name</label>
                        <input type="text" id="logisticsDriver" class="form-control" placeholder="Enter driver name">
                    </div>
                    <div class="form-group">
                        <label>Driver Phone</label>
                        <input type="text" id="logisticsDriverPhone" class="form-control" placeholder="Enter driver phone number">
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea id="logisticsNotes" class="form-control" rows="2" placeholder="Any additional logistics notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Skip</button>
                    <button type="button" class="btn btn-primary" id="confirmLogisticsBtn">
                        <i class="fas fa-check"></i> Confirm Logistics
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- CHECKOUT MODAL -->
    <!-- ============================================ -->
    <div class="modal fade checkout-modal" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fas fa-credit-card"></i> Checkout</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Cart Items -->
                    <div class="checkout-items" id="checkoutItems">
                        <!-- Will be populated by JS -->
                    </div>

                    <!-- Summary -->
                    <div class="checkout-summary" id="checkoutSummary">
                        <div class="summary-item">
                            <span>Subtotal</span>
                            <span id="checkoutSubtotal">₹ 0.00</span>
                        </div>
                        <div class="summary-item">
                            <span>Item Discount</span>
                            <span id="checkoutItemDiscount">- ₹ 0.00</span>
                        </div>
                        <div class="summary-item additional-discount-field">
                            <span>Additional Discount</span>
                            <span id="checkoutAdditionalDiscount">- ₹ 0.00</span>
                        </div>
                        <div class="summary-item">
                            <span>Tax</span>
                            <span id="checkoutTax">+ ₹ 0.00</span>
                        </div>
                        <div class="summary-item total">
                            <span>Total Amount</span>
                            <span id="checkoutTotal">₹ 0.00</span>
                        </div>
                    </div>

                    <!-- Customer Info (Read-only) -->
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Customer Name</label>
                                <input type="text" id="checkoutCustomerName" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" id="checkoutCustomerPhone" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="form-group">
                        <label>Payment Method <span class="required">*</span></label>
                        <select id="checkoutPaymentMethod" class="form-control">
                            <option value="cash">Cash</option>
                            <option value="cod">Cash on Delivery (COD)</option>
                            <option value="card">Card</option>
                            <option value="upi">UPI</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="pg">Payment Gateway</option>
                        </select>
                    </div>

                    <!-- Cash Fields -->
                    <div class="cash-fields" id="cashFields">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Amount Received <span class="required">*</span></label>
                                    <input type="number" id="checkoutAmountReceived" class="form-control" placeholder="Enter amount received" step="0.01" min="0">
                                    <div class="error-text" id="amountReceivedError">Amount received must be greater than or equal to total amount</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="changeDisplay">
                                    <label>Change to Return</label>
                                    <input type="text" id="checkoutChange" class="form-control" readonly style="color: var(--success-color); font-weight: 700;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel-checkout" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-confirm-checkout" id="confirmCheckoutBtn">
                        <i class="fas fa-check"></i> Confirm Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Processing Overlay -->
<div class="processing-overlay" id="processingOverlay">
    <div class="spinner"></div>
    <p id="processingMessage">Processing tracking data...</p>
</div>

<!-- ============================================ -->
<!-- SCRIPTS -->
<!-- ============================================ -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    // ============================================
    // GLOBAL STATE
    // ============================================
    let sellCart = [];
    let transferCart = [];
    let currentMode = 'sell';
    let allItems = [];
    let warehouses = [];
    let stores = [];
    let categories = [];
    let selectedStockType = null;
    let qrScanner = null;
    let isScannerOpen = false;
    let scannerInitialized = false;
    let currentCategory = 'all';
    let logisticsData = null;
    
    // Global tracking data storage
    let batchSelectionData = {};
    let serialSelectionData = {};

    // Configuration data from server
    let inventoryConfig = @json($configuration);
    let isFIFOEnabled = inventoryConfig && inventoryConfig.costing_method === 'FIFO';

    // ============================================
    // STOCK TYPE SELECTION
    // ============================================
    function selectStockType(type) {
        const event = new CustomEvent('stockTypeSelected', { detail: { type: type } });
        document.dispatchEvent(event);
    }

    $(document).ready(function() {
        // ============================================
        // DATA
        // ============================================
        allItems = @json($items);
        warehouses = @json($warehouses);
        stores = @json($stores);
        categories = @json($categories);

        // ============================================
        // DOM REFS
        // ============================================
        const $itemsGrid = $('#itemsGrid');
        const $transferItemsGrid = $('#transferItemsGrid');
        const $cartItems = $('#cartItems');
        const $transferCartItems = $('#transferCartItems');
        const $cartCount = $('#cartCount');
        const $transferCartCount = $('#transferCartCount');
        const $summarySubtotal = $('#summarySubtotal');
        const $summaryItemDiscount = $('#summaryItemDiscount');
        const $summaryAdditionalDiscount = $('#summaryAdditionalDiscount');
        const $summaryTax = $('#summaryTax');
        const $summaryTotal = $('#summaryTotal');
        const $transferTotalItems = $('#transferTotalItems');
        const $checkoutBtn = $('#checkoutBtn');
        const $transferCheckoutBtn = $('#transferCheckoutBtn');
        const $itemSearch = $('#itemSearch');
        const $transferSearch = $('#transferSearch');
        const $scannerOverlay = $('#scannerOverlay');
        const $manualBarcodeInput = $('#manualBarcodeInput');
        const $qrReader = $('#qr-reader');
        const $sellFromWarehouse = $('#sellFromWarehouse');
        const $sellFromStore = $('#sellFromStore');
        const $warehouseNotice = $('#warehouseNotice');
        const $transferFromLocation = $('#transferFromLocation');
        const $transferToLocation = $('#transferToLocation');
        const $transferLocationNotice = $('#transferLocationNotice');
        const $modeBadge = $('#modeBadge');
        const $modeBadgeText = $('#modeBadgeText');
        const $processingOverlay = $('#processingOverlay');
        const $processingMessage = $('#processingMessage');

        let previousSellWarehouse = '';
        let previousSellStore = '';
        let previousTransferFrom = '';
        let previousTransferTo = '';

        // ============================================
        // UTILITY FUNCTIONS
        // ============================================
        function formatCurrency(amount) {
            return '₹ ' + parseFloat(amount || 0).toFixed(2);
        }

        function showToast(message, type = 'success') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }

        function showAlert(title, message, type = 'warning') {
            return Swal.fire({
                title: title,
                text: message,
                icon: type,
                confirmButtonColor: type === 'error' ? '#ef4444' : '#4361ee'
            });
        }

        function findItemByCode(code) {
            if (!code) return null;
            code = code.trim().toLowerCase();
            return allItems.find(item => 
                (item.barcode && item.barcode.toLowerCase() === code) ||
                item.item_code.toLowerCase() === code
            );
        }

        function highlightItemCard(itemId) {
            const card = document.querySelector(`.item-card[data-id="${itemId}"]`);
            if (card) {
                card.style.borderColor = '#10b981';
                card.style.backgroundColor = '#f0fdf4';
                card.style.transition = 'all 0.3s ease';
                setTimeout(() => {
                    card.style.borderColor = '';
                    card.style.backgroundColor = '';
                }, 1500);
            }
        }

        function enableSellSearch(enabled) {
            $itemSearch.prop('disabled', !enabled);
            $('#scanBtn').prop('disabled', !enabled);
            if (!enabled) {
                $itemsGrid.html(`
                    <div style="grid-column:1/-1; text-align:center; padding:2rem; color:var(--text-muted);">
                        <i class="fas fa-warehouse" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i>
                        <p>Please select a warehouse to see available items</p>
                    </div>
                `);
            }
        }

        function enableTransferSearch(enabled) {
            $transferSearch.prop('disabled', !enabled);
            $('#transferScanBtn').prop('disabled', !enabled);
            if (!enabled) {
                $transferItemsGrid.html(`
                    <div style="grid-column:1/-1; text-align:center; padding:2rem; color:var(--text-muted);">
                        <i class="fas fa-warehouse" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i>
                        <p>Please select source and destination locations to see items</p>
                    </div>
                `);
            }
        }

        function showProcessingOverlay(message = 'Processing tracking data...') {
            $processingMessage.text(message);
            $processingOverlay.addClass('show');
        }

        function hideProcessingOverlay() {
            $processingOverlay.removeClass('show');
        }

        // ============================================
        // TRACKING HELPER FUNCTIONS (AUTO-FIFO)
        // ============================================
        function hasTracking(item) {
            return item.track_batch || item.track_serial;
        }

        function getTrackingBadge(item) {
            let badges = '';
            if (item.track_batch) {
                badges += `<span class="batch-badge"><i class="fas fa-boxes"></i> Batch</span>`;
            }
            if (item.track_serial) {
                badges += `<span class="serial-badge"><i class="fas fa-hashtag"></i> Serial</span>`;
            }
            return badges;
        }

        /**
         * Auto-select batches using FIFO (First Expiry First Out) - Optimized
         */
        function autoSelectFIFOBatches(itemId, quantity) {
            return new Promise((resolve, reject) => {
                // Use a more efficient approach - fetch only needed data
                $.ajax({
                    url: '/inventory/items/' + itemId + '/batches',
                    method: 'GET',
                    data: { 
                        limit: quantity + 10, // Fetch slightly more than needed for efficiency
                        status: 'active',
                        sort_by: 'expiry_date',
                        sort_order: 'asc'
                    },
                    success: function(response) {
                        if (response.success && response.batches) {
                            const batches = response.batches;
                            let remaining = parseInt(quantity);
                            const selected = [];
                            
                            // Early exit if not enough stock
                            let totalAvailable = 0;
                            for (const batch of batches) {
                                totalAvailable += batch.remaining_quantity;
                            }
                            
                            if (totalAvailable < remaining) {
                                reject(new Error('Insufficient batch stock. Available: ' + totalAvailable + ', Required: ' + quantity));
                                return;
                            }
                            
                            // Efficient selection using FIFO (oldest expiry first)
                            for (const batch of batches) {
                                if (remaining <= 0) break;
                                const available = batch.remaining_quantity || 0;
                                if (available <= 0) continue;
                                
                                const takeQty = Math.min(available, remaining);
                                if (takeQty > 0) {
                                    selected.push({
                                        batch_id: batch.id,
                                        batch_number: batch.batch_number || 'N/A',
                                        quantity: takeQty,
                                        expiry_date: batch.expiry_date || null
                                    });
                                    remaining -= takeQty;
                                }
                            }
                            
                            if (remaining > 0) {
                                reject(new Error('Insufficient batch stock. Need ' + remaining + ' more units.'));
                                return;
                            }
                            
                            resolve(selected);
                        } else {
                            reject(new Error(response.message || 'Failed to fetch batches'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Batch fetch error:', error);
                        reject(new Error('Failed to fetch batches. Please try again.'));
                    }
                });
            });
        }

        /**
         * Auto-select serials using FIFO (oldest created first) - Optimized
         */
        function autoSelectFIFOSerials(itemId, quantity) {
            return new Promise((resolve, reject) => {
                // Use a more efficient approach - fetch only needed data
                $.ajax({
                    url: '/inventory/items/' + itemId + '/assets',
                    method: 'GET',
                    data: {
                        limit: quantity + 10, // Fetch slightly more than needed
                        status: 'ACTIVE',
                        sort_by: 'created_at',
                        sort_order: 'asc'
                    },
                    success: function(response) {
                        if (response.success && response.assets) {
                            const assets = response.assets;
                            const activeAssets = assets.filter(a => a.status === 'ACTIVE');
                            
                            if (activeAssets.length < quantity) {
                                reject(new Error('Insufficient active serial numbers. Need ' + quantity + ', available: ' + activeAssets.length));
                                return;
                            }
                            
                            // Efficient selection - take oldest first
                            const selected = activeAssets.slice(0, quantity).map(asset => ({
                                asset_instance_id: asset.id,
                                serial_number: asset.serial_number || 'N/A',
                                asset_code: asset.asset_code || 'N/A'
                            }));
                            
                            resolve(selected);
                        } else {
                            reject(new Error(response.message || 'Failed to fetch serial numbers'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Serial fetch error:', error);
                        reject(new Error('Failed to fetch serial numbers. Please try again.'));
                    }
                });
            });
        }

        /**
         * Auto-process tracking for an item in the cart - Optimized with parallelism
         */
        async function autoProcessTracking(itemId) {
            const cartItem = sellCart.find(i => i.id === itemId);
            if (!cartItem) return;
            
            // Skip if already processed or no tracking needed
            if (cartItem.tracking_complete || !hasTracking(cartItem)) return;
            
            try {
                showProcessingOverlay('Auto-selecting ' + (cartItem.track_batch ? 'batches' : '') + 
                    (cartItem.track_batch && cartItem.track_serial ? ' & ' : '') + 
                    (cartItem.track_serial ? 'serial numbers' : '') + ' for ' + cartItem.name + '...');
                
                let batchData = null;
                let serialData = null;
                
                // Use Promise.all for parallel execution when both batch and serial are needed
                const promises = [];
                
                if (cartItem.track_batch) {
                    promises.push(
                        autoSelectFIFOBatches(itemId, cartItem.quantity)
                            .then(result => { batchData = result; })
                            .catch(err => { throw new Error('Batch selection failed: ' + err.message); })
                    );
                }
                
                if (cartItem.track_serial) {
                    promises.push(
                        autoSelectFIFOSerials(itemId, cartItem.quantity)
                            .then(result => { serialData = result; })
                            .catch(err => { throw new Error('Serial selection failed: ' + err.message); })
                    );
                }
                
                // Execute all selections in parallel
                await Promise.all(promises);
                
                // Update cart item with tracking data
                if (batchData) {
                    cartItem.batches = batchData;
                    batchSelectionData[itemId] = batchData;
                }
                
                if (serialData) {
                    cartItem.serials = serialData;
                    serialSelectionData[itemId] = serialData;
                }
                
                cartItem.tracking_complete = true;
                cartItem.tracking_auto = true;
                
                hideProcessingOverlay();
                renderSellCart();
                
                // Show concise success message
                const trackingTypes = [];
                if (batchData) trackingTypes.push('batches');
                if (serialData) trackingTypes.push('serial numbers');
                showToast('Auto-selected ' + trackingTypes.join(' & ') + ' for ' + cartItem.name + ' ✓', 'success');
                
            } catch (error) {
                hideProcessingOverlay();
                console.error('Auto-processing failed:', error);
                showAlert('Tracking Error', error.message || 'Failed to auto-select tracking data. Please try again.', 'error');
                cartItem.tracking_complete = false;
                renderSellCart();
            }
        }

        // ============================================
        // STOCK TYPE SELECTION - EVENT HANDLER
        // ============================================
        document.addEventListener('stockTypeSelected', function(e) {
            const type = e.detail.type;
            selectedStockType = type;
            currentMode = type;
            
            $('.type-option').removeClass('selected');
            $(`.type-option[data-type="${type}"]`).addClass('selected');
            
            $modeBadge.show();
            $modeBadgeText.text(type === 'sell' ? 'Sell' : 'Transfer');
            $modeBadge.find('i').removeClass('fa-shopping-cart fa-exchange-alt').addClass(type === 'sell' ? 'fa-shopping-cart' : 'fa-exchange-alt');
            
            $('#stockTypeSelection').slideUp(300, function() {
                $('#stockOutFormWrapper').slideDown(300);
                $('#changeTypeBtn').show();
            });
            
            $('#selectedType').val(type);
            
            if (type === 'sell') {
                $('#sellMode').show();
                $('#transferMode').hide();
                enableSellSearch(false);
                renderSellCart();
                if ($sellFromWarehouse.val()) {
                    renderItems();
                }
            } else {
                $('#sellMode').hide();
                $('#transferMode').show();
                populateTransferLocations();
                enableTransferSearch(false);
                renderTransferCart();
            }
            
            showToast(`Switched to ${type === 'sell' ? 'Sell' : 'Transfer'} mode`, 'info');
        });

        // ============================================
        // CHANGE TYPE
        // ============================================
        $('#changeTypeBtn').on('click', function() {
            const hasItems = (currentMode === 'sell' ? sellCart.length : transferCart.length) > 0;
            
            Swal.fire({
                title: 'Change Stock Type?',
                text: hasItems ? 'This will clear your current cart and selections. Continue?' : 'This will reset your selections. Continue?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, change',
                cancelButtonText: 'Cancel'
            }).then(result => {
                if (result.isConfirmed) {
                    resetAndShowSelection();
                }
            });
        });

        function resetAndShowSelection() {
            sellCart = [];
            transferCart = [];
            batchSelectionData = {};
            serialSelectionData = {};
            renderSellCart();
            renderTransferCart();
            
            $('#itemsDataInput').val('');
            $('#batchDataInput').val('');
            $('#serialDataInput').val('');
            $('#selectedType').val('');
            $('#subtotalInput').val('0');
            $('#totalAmountInput').val('0');
            $('#discountAmountInput').val('0');
            $('#taxAmountInput').val('0');
            $('#additionalDiscountInput').val('0');
            
            $sellFromWarehouse.val('');
            $sellFromStore.val('');
            $transferFromLocation.val('');
            $transferToLocation.val('');
            
            $modeBadge.hide();
            
            $('#stockOutFormWrapper').slideUp(300, function() {
                $('#stockTypeSelection').slideDown(300);
                $('#changeTypeBtn').hide();
                $('.type-option').removeClass('selected');
            });
            
            enableSellSearch(false);
            enableTransferSearch(false);
            
            showToast('Stock type selection reset', 'info');
        }

        // ============================================
        // QR SCANNER FUNCTIONS
        // ============================================
        function openScanner() {
            if (isScannerOpen) return;
            
            if (currentMode === 'sell' && !$sellFromWarehouse.val()) {
                showAlert('Warehouse Required', 'Please select a warehouse first before scanning items.', 'warning');
                return;
            }
            
            if (currentMode === 'transfer' && (!$transferFromLocation.val() || !$transferToLocation.val())) {
                showAlert('Locations Required', 'Please select both source and destination locations first.', 'warning');
                return;
            }
            
            isScannerOpen = true;
            $scannerOverlay.addClass('show');
            
            if (qrScanner) {
                try {
                    qrScanner.stop().then(() => {
                        qrScanner.clear();
                    }).catch(err => console.log('Scanner stop error:', err));
                } catch(e) {}
                qrScanner = null;
            }
            
            setTimeout(() => {
                try {
                    if (typeof Html5Qrcode === 'undefined') {
                        showAlert('Library Error', 'QR scanner library not loaded. Please refresh the page.', 'error');
                        closeScanner();
                        return;
                    }
                    
                    const readerElement = document.getElementById('qr-reader');
                    if (!readerElement) {
                        showAlert('Error', 'Scanner element not found.', 'error');
                        closeScanner();
                        return;
                    }
                    
                    qrScanner = new Html5Qrcode("qr-reader");
                    
                    const config = {
                        fps: 15,
                        qrbox: { width: 250, height: 250 },
                        aspectRatio: 1.0
                    };
                    
                    qrScanner.start(
                        { facingMode: "environment" },
                        config,
                        onScanSuccess,
                        onScanError
                    ).then(() => {
                        scannerInitialized = true;
                        console.log('Scanner started successfully');
                    }).catch(err => {
                        console.error('Scanner start error:', err);
                        showAlert('Camera Error', 'Could not access camera. Please ensure camera permissions are granted.', 'error');
                        closeScanner();
                    });
                } catch (err) {
                    console.error('Scanner init error:', err);
                    showAlert('Scanner Error', 'Could not start camera: ' + err.message, 'error');
                    closeScanner();
                }
            }, 300);
        }

        function closeScanner() {
            isScannerOpen = false;
            scannerInitialized = false;
            $scannerOverlay.removeClass('show');
            
            if (qrScanner) {
                try {
                    qrScanner.stop().then(() => {
                        qrScanner.clear();
                        qrScanner = null;
                    }).catch(err => {
                        console.log('Scanner stop error:', err);
                        qrScanner = null;
                    });
                } catch (err) {
                    console.log('Scanner cleanup error:', err);
                    qrScanner = null;
                }
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            console.log('Scan successful:', decodedText);
            
            if (navigator.vibrate) {
                navigator.vibrate(200);
            }
            
            closeScanner();
            
            if (currentMode === 'sell' && !$sellFromWarehouse.val()) {
                showAlert('Warehouse Required', 'Please select a warehouse first.', 'warning');
                return;
            }
            
            if (currentMode === 'transfer' && (!$transferFromLocation.val() || !$transferToLocation.val())) {
                showAlert('Locations Required', 'Please select both source and destination locations first.', 'warning');
                return;
            }
            
            const item = findItemByCode(decodedText);
            if (!item) {
                showAlert('Item Not Found', 'No item found with barcode: ' + decodedText, 'error');
                return;
            }
            
            if (currentMode === 'sell') {
                const warehouseId = $sellFromWarehouse.val();
                const storeId = $sellFromStore.val();
                
                if (item.warehouse_id != warehouseId) {
                    showAlert('Invalid Warehouse', `Item "${item.item_name}" does not belong to the selected warehouse.`, 'error');
                    return;
                }
                
                if (storeId && item.store_id != storeId) {
                    showAlert('Invalid Store', `Item "${item.item_name}" does not belong to the selected store.`, 'error');
                    return;
                }
            } else {
                const fromId = $transferFromLocation.val();
                const subType = $('.ts-btn.active').data('subtype');
                
                let fromType = '';
                if (subType === 'warehouse_to_warehouse' || subType === 'warehouse_to_store') {
                    fromType = 'warehouse';
                } else if (subType === 'store_to_store') {
                    fromType = 'store';
                }
                
                if (fromType === 'warehouse' && item.warehouse_id != fromId) {
                    showAlert('Invalid Source', `Item "${item.item_name}" does not belong to the selected source warehouse.`, 'error');
                    return;
                }
                if (fromType === 'store' && item.store_id != fromId) {
                    showAlert('Invalid Source', `Item "${item.item_name}" does not belong to the selected source store.`, 'error');
                    return;
                }
            }
            
            addItemToCart(item);
            showToast(`${item.item_name} added via scan!`, 'success');
        }

        function onScanError(err) {
            if (err && typeof err === 'string' && err.includes('error')) {
                console.warn('Scan error:', err);
            }
        }

        // ============================================
        // ADD ITEM TO CART (With Auto-Tracking)
        // ============================================
        function addItemToCart(item) {
            if (currentMode === 'sell') {
                // Prevent ASSET items from being sold
                if (item.item_type === 'ASSET') {
                    showAlert('Cannot Sell Asset', 'Asset items cannot be sold. They can only be transferred.', 'warning');
                    return false;
                }
                
                const warehouseId = $sellFromWarehouse.val();
                const storeId = $sellFromStore.val();
                
                if (!warehouseId) {
                    showAlert('Warehouse Required', 'Please select a warehouse first.', 'warning');
                    return false;
                }
                
                if (item.warehouse_id != warehouseId) {
                    showAlert('Invalid Warehouse', `Item "${item.item_name}" does not belong to the selected warehouse.`, 'error');
                    return false;
                }
                
                if (storeId && item.store_id != storeId) {
                    showAlert('Invalid Store', `Item "${item.item_name}" does not belong to the selected store.`, 'error');
                    return false;
                }
                
                const price = parseFloat(item.selling_price || 0);
                if (price <= 0) {
                    showAlert('No Price', 'This item does not have a selling price set.', 'error');
                    return false;
                }
                
                const existing = sellCart.find(i => i.id === item.id);
                if (existing) {
                    if (existing.quantity >= item.current_stock) {
                        showAlert('Insufficient Stock', 'Maximum stock available: ' + item.current_stock, 'error');
                        return false;
                    }
                    existing.quantity++;
                    existing.tracking_complete = false;
                    existing.batches = null;
                    existing.serials = null;
                    existing.tracking_auto = false;
                } else {
                    if (item.current_stock < 1) {
                        showAlert('Out of Stock', 'This item is out of stock.', 'error');
                        return false;
                    }
                    sellCart.push({ 
                        id: item.id, 
                        name: item.item_name, 
                        code: item.item_code, 
                        price: price, 
                        quantity: 1, 
                        maxStock: item.current_stock,
                        track_batch: item.track_batch || false,
                        track_serial: item.track_serial || false,
                        tracking_complete: !(item.track_batch || item.track_serial),
                        tracking_auto: false,
                        batches: null,
                        serials: null,
                        tax_percentage: item.tax_percentage || 0,
                        discount_percentage: item.discount_percent || 0
                    });
                }
                renderSellCart();
                
                if (hasTracking(item)) {
                    autoProcessTracking(item.id);
                }
                
                highlightItemCard(item.id);
                showToast(`${item.item_name} added to cart`, 'success');
                return true;
            } else {
                // Transfer mode - ASSET items CAN be transferred
                const fromId = $transferFromLocation.val();
                const toId = $transferToLocation.val();
                const subType = $('.ts-btn.active').data('subtype');
                
                if (!fromId || !toId) {
                    showAlert('Locations Required', 'Please select both source and destination locations first.', 'warning');
                    return false;
                }
                
                // Get the types of selected locations
                const fromType = $transferFromLocation.find('option:selected').data('type');
                const toType = $transferToLocation.find('option:selected').data('type');
                
                // Only check for same location if both are the same type
                if (fromType === toType && fromId === toId) {
                    showAlert('Invalid Transfer', 'Cannot transfer items to the same location.', 'error');
                    return false;
                }
                
                let fromTypeCheck = '';
                if (subType === 'warehouse_to_warehouse' || subType === 'warehouse_to_store') {
                    fromTypeCheck = 'warehouse';
                } else if (subType === 'store_to_store') {
                    fromTypeCheck = 'store';
                }
                
                if (fromTypeCheck === 'warehouse' && item.warehouse_id != fromId) {
                    showAlert('Invalid Source', `Item "${item.item_name}" does not belong to the selected source warehouse.`, 'error');
                    return false;
                }
                if (fromTypeCheck === 'store' && item.store_id != fromId) {
                    showAlert('Invalid Source', `Item "${item.item_name}" does not belong to the selected source store.`, 'error');
                    return false;
                }
                
                addToTransferCart(item.id, item.item_name, item.item_code, item.current_stock);
                highlightItemCard(item.id);
                return true;
            }
        }

        // ============================================
        // ITEM CLICK HANDLER WITH TRACKING
        // ============================================
        $(document).on('click', '.item-card .add-quick', function(e) {
            e.stopPropagation();
            const $card = $(this).closest('.item-card');
            const itemId = parseInt($card.data('id'));
            
            const item = allItems.find(i => i.id === itemId);
            if (!item) {
                showAlert('Error', 'Item data not found.', 'error');
                return;
            }
            
            if (currentMode === 'sell') {
                if (sellCart.length > 0) {
                    const firstItemId = sellCart[0].id;
                    const firstItem = allItems.find(i => i.id === firstItemId);
                    if (firstItem && firstItem.warehouse_id != item.warehouse_id) {
                        showAlert('Warehouse Mismatch', 'You can only add items from the same warehouse. Please clear the cart first.', 'warning');
                        return;
                    }
                }
                addItemToCart(item);
            } else {
                addItemToCart(item);
            }
        });

        $(document).on('click', '.item-card', function(e) {
            if ($(e.target).closest('.add-quick').length) return;
            $(this).find('.add-quick').click();
        });

        // ============================================
        // SELL CART FUNCTIONS
        // ============================================
        function renderSellCart() {
            if (sellCart.length === 0) {
                $cartItems.html(`
                    <div class="empty-cart-pos">
                        <i class="fas fa-shopping-bag"></i>
                        <p>No items in cart</p>
                        <small>Click on items to add</small>
                    </div>
                `);
                $cartCount.text('0');
                $('#cartTrackingInfo').text('');
                updateSellSummary();
                updateCheckoutButton();
                return;
            }

            let html = '';
            let hasTrackingItems = false;
            let allTrackingComplete = true;
            
            sellCart.forEach((item, index) => {
                const trackingStatus = item.track_batch || item.track_serial;
                const trackingComplete = item.tracking_complete;
                
                if (trackingStatus) {
                    hasTrackingItems = true;
                    if (!trackingComplete) allTrackingComplete = false;
                }
                
                const trackingBadge = trackingStatus ? 
                    `<span class="tracking-indicator ${trackingComplete ? 'auto-complete' : 'auto-processing'}">
                        <span class="icon">${trackingComplete ? '✅' : '⏳'}</span>
                    </span>` : '';
                
                html += `
                    <div class="cart-item-pos" data-index="${index}">
                        <div class="d-flex">
                            <div class="ci-info">
                                <div class="ci-name">
                                    ${item.name}
                                    ${trackingBadge}
                                    ${isFIFOEnabled ? '<span class="fifo-badge"><i class="fas fa-arrow-up"></i> FIFO</span>' : ''}
                                </div>
                                <div class="ci-detail d-none">${item.code}</div>
                                <div class="d-none">
                                    ${item.track_batch ? `<div class="ci-detail">Batch: ${item.batches ? item.batches.length + ' selected' : 'Auto-selecting...'}</div>` : ''}
                                    ${item.track_serial ? `<div class="ci-detail">Serial: ${item.serials ? item.serials.length + ' selected' : 'Auto-selecting...'}</div>` : ''}
                                </div>
                            </div>
                            <div class="ci-qty">
                                <button type="button" class="qty-btn" onclick="updateSellQty(${index}, -1)">−</button>
                                <input type="number" class="qty-input" value="${item.quantity}" min="1" max="${item.maxStock}" data-index="${index}" style="width:45px; text-align:center; border:1px solid #e2e8f0; border-radius:4px; padding:2px 4px; font-weight:600; font-size:0.85rem;">
                                <button type="button" class="qty-btn" onclick="updateSellQty(${index}, 1)">+</button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="ci-price">${formatCurrency(item.price * item.quantity)}</div>
                            <button type="button" class="ci-remove" onclick="removeSellItem(${index})"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                `;
            });
            $cartItems.html(html);
            $cartCount.text(sellCart.reduce((sum, i) => sum + i.quantity, 0));
            
            if (hasTrackingItems && allTrackingComplete) {
                $('#cartTrackingInfo').html('✅ All tracking complete');
            } else if (hasTrackingItems) {
                $('#cartTrackingInfo').html('⏳ Auto-selecting tracking...');
            } else {
                $('#cartTrackingInfo').text('');
            }
            
            updateSellSummary();
            updateCheckoutButton();

            $('.ci-qty .qty-input').on('change', function() {
                const index = parseInt($(this).data('index'));
                const newValue = parseInt($(this).val());
                const item = sellCart[index];
                
                if (!item) return;
                
                if (isNaN(newValue) || newValue < 1) {
                    $(this).val(item.quantity);
                    showAlert('Invalid Quantity', 'Quantity must be at least 1.', 'warning');
                    return;
                }
                
                if (newValue > item.maxStock) {
                    $(this).val(item.quantity);
                    showAlert('Insufficient Stock', 'Maximum stock available: ' + item.maxStock, 'error');
                    return;
                }
                
                item.quantity = newValue;
                item.tracking_complete = false;
                item.batches = null;
                item.serials = null;
                item.tracking_auto = false;
                renderSellCart();
                
                const fullItem = allItems.find(i => i.id === item.id);
                if (fullItem && hasTracking(fullItem)) {
                    autoProcessTracking(item.id);
                }
            });
        }

        // Make functions globally accessible
        window.updateSellQty = function(index, delta) {
            const item = sellCart[index];
            if (!item) return;
            const newQty = item.quantity + delta;
            if (newQty < 1) return;
            if (newQty > item.maxStock) {
                showAlert('Insufficient Stock', 'Maximum stock: ' + item.maxStock, 'error');
                return;
            }
            item.quantity = newQty;
            item.tracking_complete = false;
            item.batches = null;
            item.serials = null;
            item.tracking_auto = false;
            renderSellCart();
            
            const fullItem = allItems.find(i => i.id === item.id);
            if (fullItem && hasTracking(fullItem)) {
                autoProcessTracking(item.id);
            }
        };

        window.removeSellItem = function(index) {
            sellCart.splice(index, 1);
            renderSellCart();
        };

        // ============================================
        // UPDATE SELL SUMMARY (With Discount & Tax Support)
        // ============================================
        function updateSellSummary() {
            let subtotal = sellCart.reduce((sum, i) => sum + (i.price * i.quantity), 0);
            
            // Calculate item-level discounts from inventory_items table (discount_percent)
            let itemDiscount = 0;
            sellCart.forEach(item => {
                if (item.discount_percentage && item.discount_percentage > 0) {
                    const discountAmount = (item.price * item.discount_percentage) / 100;
                    itemDiscount += discountAmount * item.quantity;
                }
            });
            
            // Additional discount (hidden field - maps to discount_percentage from inventory_items table)
            // This is the "additional discount" field that is hidden but functional
            let additionalDiscount = 0;
            sellCart.forEach(item => {
                // Additional discount is stored in the same discount_percentage field
                // but we track it separately for UI purposes
                if (item.additional_discount_percentage) {
                    const additionalAmount = (item.price * item.additional_discount_percentage) / 100;
                    additionalDiscount += additionalAmount * item.quantity;
                }
            });
            
            // Tax calculation from inventory_items table (tax_percentage)
            let taxAmount = 0;
            let totalAfterDiscount = subtotal - itemDiscount - additionalDiscount;
            sellCart.forEach(item => {
                if (item.tax_percentage && item.tax_percentage > 0) {
                    const itemTotal = item.price * item.quantity;
                    const itemDiscountAmount = (item.discount_percentage || 0) > 0 ? (item.price * item.discount_percentage / 100) * item.quantity : 0;
                    const itemAdditionalDiscount = (item.additional_discount_percentage || 0) > 0 ? (item.price * item.additional_discount_percentage / 100) * item.quantity : 0;
                    const taxableAmount = itemTotal - itemDiscountAmount - itemAdditionalDiscount;
                    taxAmount += (taxableAmount * item.tax_percentage) / 100;
                }
            });
            
            // Total = Subtotal - Item Discount - Additional Discount + Tax
            let total = subtotal - itemDiscount - additionalDiscount + taxAmount;
            
            $summarySubtotal.text(formatCurrency(subtotal));
            $summaryItemDiscount.text('- ' + formatCurrency(itemDiscount));
            $summaryAdditionalDiscount.text('- ' + formatCurrency(additionalDiscount));
            $summaryTax.text('+ ' + formatCurrency(taxAmount));
            $summaryTotal.text(formatCurrency(total));
            
            $('#subtotalInput').val(subtotal);
            $('#discountAmountInput').val(itemDiscount + additionalDiscount);
            $('#taxAmountInput').val(taxAmount);
            $('#totalAmountInput').val(total);
            $('#additionalDiscountInput').val(additionalDiscount);
        }

        // ============================================
        // UPDATE CHECKOUT BUTTON
        // ============================================
        function updateCheckoutButton() {
            const warehouseId = $sellFromWarehouse.val();
            const hasItems = sellCart.length > 0;
            const hasWarehouse = warehouseId && warehouseId !== '';
            const trackingComplete = sellCart.every(item => item.tracking_complete || !(item.track_batch || item.track_serial));
            
            $checkoutBtn.prop('disabled', !(hasItems && hasWarehouse && trackingComplete));
            
            if (hasItems && !trackingComplete) {
                $checkoutBtn.html('<i class="fas fa-hourglass-half"></i> Auto-selecting tracking...');
            } else {
                $checkoutBtn.html('<i class="fas fa-credit-card"></i> Checkout');
            }
        }

        function updateTransferCheckoutBtn() {
            const fromId = $transferFromLocation.val();
            const toId = $transferToLocation.val();
            const hasItems = transferCart.length > 0;
            const hasLocations = fromId && toId && fromId !== toId;
            
            $transferCheckoutBtn.prop('disabled', !(hasItems && hasLocations));
        }

        // ============================================
        // TRANSFER CART FUNCTIONS
        // ============================================
        function addToTransferCart(id, name, code, stock) {
            const existing = transferCart.find(item => item.id === id);
            if (existing) {
                if (existing.quantity >= stock) {
                    showAlert('Insufficient Stock', 'Maximum stock available: ' + stock, 'error');
                    return false;
                }
                existing.quantity++;
            } else {
                if (stock < 1) {
                    showAlert('Out of Stock', 'This item is out of stock.', 'error');
                    return false;
                }
                transferCart.push({ id, name, code, quantity: 1, maxStock: stock });
            }
            renderTransferCart();
            showToast(`${name} added to transfer`, 'success');
            return true;
        }

        function renderTransferCart() {
            if (transferCart.length === 0) {
                $transferCartItems.html(`
                    <div class="empty-cart-pos">
                        <i class="fas fa-box-open"></i>
                        <p>No items in transfer cart</p>
                        <small>Click on items to add</small>
                    </div>
                `);
                $transferCartCount.text('0');
                $transferTotalItems.text('0');
                updateTransferCheckoutBtn();
                return;
            }

            let html = '';
            transferCart.forEach((item, index) => {
                html += `
                    <div class="cart-item-pos" data-index="${index}">
                    <div class="d-flex">
                        <div class="ci-info">
                            <div class="ci-name">${item.name}</div>
                            <div class="ci-detail">${item.code}</div>
                        </div>
                        <div class="ci-qty">
                            <button type="button" class="qty-btn" onclick="updateTransferQty(${index}, -1)">−</button>
                            <input type="number" class="qty-input" value="${item.quantity}" min="1" max="${item.maxStock}" data-index="${index}" style="width:45px; text-align:center; border:1px solid #e2e8f0; border-radius:4px; padding:2px 4px; font-weight:600; font-size:0.85rem;">
                            <button type="button" class="qty-btn" onclick="updateTransferQty(${index}, 1)">+</button>
                        </div>
                        <button type="button" class="ci-remove" onclick="removeTransferItem(${index})"><i class="fas fa-times"></i></button>
                    </div>
                    </div>
                `;
            });
            $transferCartItems.html(html);
            const totalQty = transferCart.reduce((sum, i) => sum + i.quantity, 0);
            $transferCartCount.text(totalQty);
            $transferTotalItems.text(totalQty);
            updateTransferCheckoutBtn();

            $('.ci-qty .qty-input').on('change', function() {
                const index = parseInt($(this).data('index'));
                const newValue = parseInt($(this).val());
                const item = transferCart[index];
                
                if (!item) return;
                
                if (isNaN(newValue) || newValue < 1) {
                    $(this).val(item.quantity);
                    showAlert('Invalid Quantity', 'Quantity must be at least 1.', 'warning');
                    return;
                }
                
                if (newValue > item.maxStock) {
                    $(this).val(item.quantity);
                    showAlert('Insufficient Stock', 'Maximum stock available: ' + item.maxStock, 'error');
                    return;
                }
                
                item.quantity = newValue;
                renderTransferCart();
            });
        }

        window.updateTransferQty = function(index, delta) {
            const item = transferCart[index];
            if (!item) return;
            const newQty = item.quantity + delta;
            if (newQty < 1) return;
            if (newQty > item.maxStock) {
                showAlert('Insufficient Stock', 'Maximum stock: ' + item.maxStock, 'error');
                return;
            }
            item.quantity = newQty;
            renderTransferCart();
        };

        window.removeTransferItem = function(index) {
            transferCart.splice(index, 1);
            renderTransferCart();
        };

        // ============================================
        // CATEGORY FILTERS
        // ============================================
        $(document).on('click', '.filter-chip', function() {
            $('.filter-chip').removeClass('active');
            $(this).addClass('active');
            currentCategory = $(this).data('category');
            if (currentMode === 'sell') {
                renderItems();
            } else {
                renderTransferItems();
            }
        });

        // ============================================
        // RENDER ITEMS (Sell Mode)
        // ============================================
        function renderItems() {
            const search = $itemSearch.val().toLowerCase();
            const warehouseId = $sellFromWarehouse.val();
            const storeId = $sellFromStore.val();

            if (!warehouseId) {
                enableSellSearch(false);
                return;
            }

            enableSellSearch(true);

            let filtered = allItems.filter(item => {
                // Exclude ASSET type items from sell mode
                if (item.item_type === 'ASSET') return false;
                if (item.current_stock <= 0) return false;
                if (item.warehouse_id != warehouseId) return false;
                if (storeId && item.store_id != storeId) return false;
                return true;
            });

            if (search) {
                filtered = filtered.filter(item => {
                    const matchName = item.item_name.toLowerCase().includes(search);
                    const matchCode = item.item_code.toLowerCase().includes(search);
                    const matchBarcode = (item.barcode || '').toLowerCase().includes(search);
                    return matchName || matchCode || matchBarcode;
                });
            }

            if (currentCategory !== 'all') {
                filtered = filtered.filter(item => item.category_id == currentCategory);
            }

            if (filtered.length === 0) {
                $itemsGrid.html(`
                    <div style="grid-column:1/-1; text-align:center; padding:2rem; color:var(--text-muted);">
                        <i class="fas fa-box-open" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i>
                        <p>No items available at this location</p>
                        <small>Try selecting a different location</small>
                    </div>
                `);
                return;
            }

            let html = '';
            filtered.forEach(item => {
                const price = parseFloat(item.selling_price || 0);
                const stock = item.current_stock || 0;
                const lowStock = stock < 10;
                const hasTracking = item.track_batch || item.track_serial;
                const trackingBadge = hasTracking ? 
                    `<span class="tracking-indicator auto-processing">${isFIFOEnabled ? '<span class="fifo-badge"><i class="fas fa-arrow-up"></i> FIFO</span>' : ''}</span>` : '';
                
                // Show discount and tax badges if present
                const discountBadge = (item.discount_percent && item.discount_percent > 0) ? 
                    `<span class="badge bg-warning text-dark" style="font-size:0.6rem; margin-left:0.3rem;">${item.discount_percent}% off</span>` : '';
                const taxBadge = (item.tax_percentage && item.tax_percentage > 0) ? 
                    `<span class="badge bg-info" style="font-size:0.6rem; margin-left:0.3rem;">Tax ${item.tax_percentage}%</span>` : '';
                
                html += `
                    <div class="item-card" data-id="${item.id}" data-name="${item.item_name}" data-code="${item.item_code}" data-price="${price}" data-stock="${stock}">
                        <span class="item-code">${item.item_code}</span>
                        <div class="item-name">${item.item_name} ${trackingBadge}</div>
                        <div class="item-price">${formatCurrency(price)} ${discountBadge}</div>
                        <div class="item-stock stock-badge ${lowStock ? 'low' : ''}">Stock: ${stock} ${lowStock ? '⚠️' : ''}</div>
                        ${getTrackingBadge(item)}
                        ${taxBadge}
                        
                        <button type="button" class="add-quick" data-id="${item.id}">
                            <i class="fas fa-plus"></i>
                        </button>
                        <span class="stock-badge d-none ${lowStock ? 'low' : ''}">${stock} units</span>
                    </div>
                `;
            });
            $itemsGrid.html(html);
            
            updateCheckoutButton();
        }

        // ============================================
        // RENDER TRANSFER ITEMS
        // ============================================
        function renderTransferItems() {
            const search = $transferSearch.val().toLowerCase();
            const fromId = $transferFromLocation.val();
            const toId = $transferToLocation.val();
            const subType = $('.ts-btn.active').data('subtype');

            if (!fromId || !toId) {
                enableTransferSearch(false);
                return;
            }
            
            // Get the types of selected locations
            const fromType = $transferFromLocation.find('option:selected').data('type');
            const toType = $transferToLocation.find('option:selected').data('type');
            
            // Only check for same location if both are the same type
            // For warehouse_to_store, from is warehouse and to is store - they can never be the same
            if (fromType === toType && fromId === toId) {
                $transferItemsGrid.html(`
                    <div style="grid-column:1/-1; text-align:center; padding:2rem; color:var(--danger-color);">
                        <i class="fas fa-exclamation-triangle" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i>
                        <p>Source and destination cannot be the same</p>
                        <small>Please select different locations</small>
                    </div>
                `);
                enableTransferSearch(false);
                return;
            }

            enableTransferSearch(true);

            let locationType = null;
            if (subType === 'warehouse_to_warehouse' || subType === 'warehouse_to_store') {
                locationType = 'warehouse';
            } else if (subType === 'store_to_store') {
                locationType = 'store';
            }

            let filtered = allItems.filter(item => {
                if (item.current_stock <= 0) return false;
                if (locationType === 'warehouse' && item.warehouse_id != fromId) return false;
                if (locationType === 'store' && item.store_id != fromId) return false;
                return true;
            });

            if (search) {
                filtered = filtered.filter(item => {
                    const matchName = item.item_name.toLowerCase().includes(search);
                    const matchCode = item.item_code.toLowerCase().includes(search);
                    const matchBarcode = (item.barcode || '').toLowerCase().includes(search);
                    return matchName || matchCode || matchBarcode;
                });
            }

            if (filtered.length === 0) {
                $transferItemsGrid.html(`
                    <div style="grid-column:1/-1; text-align:center; padding:2rem; color:var(--text-muted);">
                        <i class="fas fa-box-open" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i>
                        <p>No items available at this location</p>
                    </div>
                `);
                return;
            }

            let html = '';
            filtered.forEach(item => {
                const stock = item.current_stock || 0;
                const lowStock = stock < 10;
                const hasTracking = item.track_batch || item.track_serial;
                const trackingBadge = hasTracking ? 
                    `<span class="tracking-indicator auto-processing"><span class="icon">🤖</span> FIFO Auto</span>` : '';
                
                // Show item type badge
                const itemTypeBadge = item.item_type === 'ASSET' ? 
                    `<span class="badge bg-primary" style="font-size:0.6rem; margin-left:0.3rem;"><i class="fas fa-cube"></i> ASSET</span>` : '';
                
                html += `
                    <div class="item-card" data-id="${item.id}" data-name="${item.item_name}" data-code="${item.item_code}" data-stock="${stock}">
                        <span class="item-code">${item.item_code}</span>
                        <div class="item-name">${item.item_name} ${trackingBadge}</div>
                        <div class="item-stock ${lowStock ? 'low' : ''}">Stock: ${stock} ${lowStock ? '⚠️' : ''}</div>
                        ${getTrackingBadge(item)}
                        ${isFIFOEnabled ? '<span class="fifo-badge"><i class="fas fa-arrow-up"></i> FIFO</span>' : ''}
                        ${itemTypeBadge}
                        <button type="button" class="add-quick transfer-add" data-id="${item.id}">
                            <i class="fas fa-plus"></i>
                        </button>
                        <span class="stock-badge ${lowStock ? 'low' : ''}">${stock} units</span>
                    </div>
                `;
            });
            $transferItemsGrid.html(html);
        }

        // ============================================
        // POPULATE TRANSFER LOCATIONS
        // ============================================
        function populateTransferLocations() {
            const subType = $('.ts-btn.active').data('subtype');
            const $from = $transferFromLocation;
            const $to = $transferToLocation;
            
            $from.empty().append('<option value="">Select Source</option>');
            $to.empty().append('<option value="">Select Destination</option>');

            let fromLocations = [];
            let toLocations = [];

            if (subType === 'warehouse_to_warehouse') {
                fromLocations = warehouses.map(w => ({ id: w.id, name: w.warehouse_name, type: 'warehouse' }));
                toLocations = warehouses.map(w => ({ id: w.id, name: w.warehouse_name, type: 'warehouse' }));
            } else if (subType === 'store_to_store') {
                fromLocations = stores.map(s => ({ id: s.id, name: s.store_name, type: 'store', warehouse_id: s.warehouse_id }));
                toLocations = stores.map(s => ({ id: s.id, name: s.store_name, type: 'store', warehouse_id: s.warehouse_id }));
            } else if (subType === 'warehouse_to_store') {
                fromLocations = warehouses.map(w => ({ id: w.id, name: w.warehouse_name, type: 'warehouse' }));
                toLocations = stores.map(s => ({ id: s.id, name: s.store_name, type: 'store', warehouse_id: s.warehouse_id }));
            }

            fromLocations.forEach(loc => {
                $from.append(`<option value="${loc.id}" data-type="${loc.type}">${loc.name}</option>`);
            });

            previousTransferFrom = '';
            previousTransferTo = '';

            if (subType === 'warehouse_to_store') {
                $from.off('change').on('change', function() {
                    const fromVal = $(this).val();
                    const previousFrom = previousTransferFrom;
                    
                    if (previousFrom !== fromVal && previousFrom !== '' && transferCart.length > 0) {
                        clearCartWithConfirmation(function() {
                            $to.empty().append('<option value="">Select Destination Store</option>');
                            if (fromVal) {
                                stores.filter(s => s.warehouse_id == fromVal).forEach(store => {
                                    $to.append(`<option value="${store.id}" data-type="store">${store.store_name}</option>`);
                                });
                            }
                            $transferLocationNotice.hide();
                            renderTransferItems();
                            updateTransferCheckoutBtn();
                            previousTransferFrom = fromVal;
                        }, 'source location');
                    } else {
                        $to.empty().append('<option value="">Select Destination Store</option>');
                        if (fromVal) {
                            stores.filter(s => s.warehouse_id == fromVal).forEach(store => {
                                $to.append(`<option value="${store.id}" data-type="store">${store.store_name}</option>`);
                            });
                        }
                        $transferLocationNotice.hide();
                        renderTransferItems();
                        updateTransferCheckoutBtn();
                        previousTransferFrom = fromVal;
                    }
                });
            } else {
                toLocations.forEach(loc => {
                    $to.append(`<option value="${loc.id}" data-type="${loc.type}">${loc.name}</option>`);
                });
                
                $from.off('change').on('change', function() {
                    const fromVal = $(this).val();
                    const previousFrom = previousTransferFrom;
                    
                    if (previousFrom !== fromVal && previousFrom !== '' && transferCart.length > 0) {
                        clearCartWithConfirmation(function() {
                            $to.find('option').each(function() {
                                if ($(this).val() && $(this).val() === fromVal) {
                                    $(this).prop('disabled', true);
                                } else {
                                    $(this).prop('disabled', false);
                                }
                            });
                            if ($to.val() === fromVal) {
                                $to.val('');
                            }
                            $transferLocationNotice.hide();
                            renderTransferItems();
                            updateTransferCheckoutBtn();
                            previousTransferFrom = fromVal;
                        }, 'source location');
                    } else {
                        $to.find('option').each(function() {
                            if ($(this).val() && $(this).val() === fromVal) {
                                $(this).prop('disabled', true);
                            } else {
                                $(this).prop('disabled', false);
                            }
                        });
                        if ($to.val() === fromVal) {
                            $to.val('');
                        }
                        $transferLocationNotice.hide();
                        renderTransferItems();
                        updateTransferCheckoutBtn();
                        previousTransferFrom = fromVal;
                    }
                });
            }
            
            $to.off('change').on('change', function() {
                const fromVal = $from.val();
                const toVal = $(this).val();
                const previousTo = previousTransferTo;
                const fromType = $from.find('option:selected').data('type');
                const toType = $(this).find('option:selected').data('type');
                
                // Only check for same location if both are the same type
                if (fromType === toType && fromVal === toVal) {
                    showAlert('Invalid Transfer', 'Source and destination cannot be the same. Please select different locations.', 'error');
                    $(this).val('');
                    $transferLocationNotice.show();
                    enableTransferSearch(false);
                    previousTransferTo = toVal;
                    return;
                }
                
                if (previousTo !== toVal && previousTo !== '' && transferCart.length > 0) {
                    clearCartWithConfirmation(function() {
                        if (fromVal && toVal) {
                            $transferLocationNotice.hide();
                            renderTransferItems();
                            updateTransferCheckoutBtn();
                        } else {
                            $transferLocationNotice.show();
                            enableTransferSearch(false);
                        }
                        previousTransferTo = toVal;
                    }, 'destination location');
                } else {
                    if (fromVal && toVal) {
                        $transferLocationNotice.hide();
                        renderTransferItems();
                        updateTransferCheckoutBtn();
                    } else {
                        $transferLocationNotice.show();
                        enableTransferSearch(false);
                    }
                    previousTransferTo = toVal;
                }
            });
            
            $('#selectedSubType').val(subType);
            
            if (transferCart.length > 0) {
                transferCart = [];
                renderTransferCart();
                showToast('Transfer cart cleared due to type change', 'info');
            }
        }

        function clearCartWithConfirmation(callback, locationName = 'location') {
            if (currentMode === 'sell' && sellCart.length === 0) {
                callback();
                return;
            }
            if (currentMode === 'transfer' && transferCart.length === 0) {
                callback();
                return;
            }
            
            Swal.fire({
                title: 'Change ' + locationName + '?',
                text: 'Changing the ' + locationName + ' will remove all items from your cart. Do you want to continue?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, change ' + locationName,
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then(result => {
                if (result.isConfirmed) {
                    if (currentMode === 'sell') {
                        sellCart = [];
                        batchSelectionData = {};
                        serialSelectionData = {};
                        renderSellCart();
                    } else {
                        transferCart = [];
                        renderTransferCart();
                    }
                    callback();
                    showToast('Cart cleared due to ' + locationName + ' change', 'info');
                } else {
                    showToast(locationName + ' change cancelled', 'info');
                }
            });
        }

        // ============================================
        // LOCATION CHANGE EVENTS (Sell)
        // ============================================
        $sellFromWarehouse.on('focus', function() {
            previousSellWarehouse = $(this).val();
        });

        $sellFromWarehouse.on('change', function() {
            const newWarehouseId = $(this).val();
            const $storeSelect = $sellFromStore;
            
            $storeSelect.find('option').each(function() {
                const $option = $(this);
                if ($option.val() === '') return;
                const warehouseIdAttr = $option.data('warehouse-id');
                if (newWarehouseId && warehouseIdAttr && warehouseIdAttr != newWarehouseId) {
                    $option.hide();
                } else {
                    $option.show();
                }
            });
            
            if ($storeSelect.val() && $storeSelect.find('option:selected').css('display') === 'none') {
                $storeSelect.val('');
            }
            
            if (newWarehouseId) {
                if (previousSellWarehouse !== newWarehouseId && previousSellWarehouse !== '') {
                    clearCartWithConfirmation(function() {
                        $warehouseNotice.removeClass('show').text('');
                        $sellFromWarehouse.removeClass('error');
                        renderItems();
                        updateCheckoutButton();
                        previousSellWarehouse = newWarehouseId;
                    }, 'warehouse');
                } else {
                    $warehouseNotice.removeClass('show').text('');
                    $sellFromWarehouse.removeClass('error');
                    renderItems();
                    updateCheckoutButton();
                    previousSellWarehouse = newWarehouseId;
                }
            } else {
                if (sellCart.length > 0) {
                    Swal.fire({
                        title: 'Remove Warehouse?',
                        text: 'Deselecting the warehouse will clear the cart. Continue?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'Yes, clear cart',
                        cancelButtonText: 'Cancel'
                    }).then(result => {
                        if (result.isConfirmed) {
                            sellCart = [];
                            batchSelectionData = {};
                            serialSelectionData = {};
                            renderSellCart();
                            $warehouseNotice.addClass('show').text('Please select a warehouse to see items');
                            $sellFromWarehouse.addClass('error');
                            enableSellSearch(false);
                            updateCheckoutButton();
                            previousSellWarehouse = '';
                            showToast('Cart cleared', 'info');
                        } else {
                            $sellFromWarehouse.val(previousSellWarehouse);
                            renderItems();
                            updateCheckoutButton();
                        }
                    });
                } else {
                    $warehouseNotice.addClass('show').text('Please select a warehouse to see items');
                    $sellFromWarehouse.addClass('error');
                    enableSellSearch(false);
                    updateCheckoutButton();
                    previousSellWarehouse = '';
                }
            }
        });

        $sellFromStore.on('focus', function() {
            previousSellStore = $(this).val();
        });

        $sellFromStore.on('change', function() {
            const newStoreId = $(this).val();
            const warehouseId = $sellFromWarehouse.val();
            
            if (!warehouseId) {
                $(this).val('');
                showAlert('Warehouse Required', 'Please select a warehouse first.', 'warning');
                return;
            }
            
            if (previousSellStore !== newStoreId && previousSellStore !== '') {
                clearCartWithConfirmation(function() {
                    renderItems();
                    updateCheckoutButton();
                    previousSellStore = newStoreId;
                }, 'store');
            } else {
                renderItems();
                updateCheckoutButton();
                previousSellStore = newStoreId;
            }
        });

        // ============================================
        // TRANSFER SUB-TYPE CHANGE
        // ============================================
        $(document).on('click', '.ts-btn', function() {
            const hasItems = transferCart.length > 0;
            
            if (hasItems) {
                Swal.fire({
                    title: 'Change Transfer Type?',
                    text: 'Changing transfer type will clear the current transfer cart. Continue?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Yes, change',
                    cancelButtonText: 'Cancel'
                }).then(result => {
                    if (result.isConfirmed) {
                        transferCart = [];
                        renderTransferCart();
                        $('.ts-btn').removeClass('active');
                        $(this).addClass('active');
                        populateTransferLocations();
                        renderTransferItems();
                        previousTransferFrom = '';
                        previousTransferTo = '';
                        showToast('Transfer type changed, cart cleared', 'info');
                    }
                });
            } else {
                $('.ts-btn').removeClass('active');
                $(this).addClass('active');
                populateTransferLocations();
                renderTransferItems();
                previousTransferFrom = '';
                previousTransferTo = '';
            }
        });

        // ============================================
        // QR SCANNER EVENTS
        // ============================================
        $('#scanBtn, #transferScanBtn').on('click', function(e) {
            e.preventDefault();
            openScanner();
        });

        $('#scannerCloseBtn').on('click', function() {
            closeScanner();
        });

        $scannerOverlay.on('click', function(e) {
            if ($(e.target).is($scannerOverlay)) {
                closeScanner();
            }
        });

        $('#manualBarcodeBtn').on('click', function() {
            const code = $manualBarcodeInput.val().trim();
            if (!code) {
                showAlert('No Input', 'Please enter a barcode or item code.', 'warning');
                return;
            }
            
            if (currentMode === 'sell' && !$sellFromWarehouse.val()) {
                showAlert('Warehouse Required', 'Please select a warehouse first before adding items.', 'warning');
                return;
            }
            if (currentMode === 'transfer' && (!$transferFromLocation.val() || !$transferToLocation.val())) {
                showAlert('Locations Required', 'Please select both source and destination locations first.', 'warning');
                return;
            }
            
            const item = findItemByCode(code);
            if (!item) {
                showAlert('Not Found', 'No item found with barcode or code: ' + code, 'error');
                $manualBarcodeInput.val('').focus();
                return;
            }
            
            if (currentMode === 'sell') {
                const warehouseId = $sellFromWarehouse.val();
                const storeId = $sellFromStore.val();
                if (item.warehouse_id != warehouseId) {
                    showAlert('Invalid Warehouse', `Item "${item.item_name}" does not belong to the selected warehouse.`, 'error');
                    return;
                }
                if (storeId && item.store_id != storeId) {
                    showAlert('Invalid Store', `Item "${item.item_name}" does not belong to the selected store.`, 'error');
                    return;
                }
            } else {
                const fromId = $transferFromLocation.val();
                const subType = $('.ts-btn.active').data('subtype');
                let fromType = '';
                if (subType === 'warehouse_to_warehouse' || subType === 'warehouse_to_store') {
                    fromType = 'warehouse';
                } else if (subType === 'store_to_store') {
                    fromType = 'store';
                }
                if (fromType === 'warehouse' && item.warehouse_id != fromId) {
                    showAlert('Invalid Source', `Item "${item.item_name}" does not belong to the selected source warehouse.`, 'error');
                    return;
                }
                if (fromType === 'store' && item.store_id != fromId) {
                    showAlert('Invalid Source', `Item "${item.item_name}" does not belong to the selected source store.`, 'error');
                    return;
                }
            }
            
            closeScanner();
            addItemToCart(item);
            showToast(`${item.item_name} added!`, 'success');
            $manualBarcodeInput.val('');
        });

        $manualBarcodeInput.on('keypress', function(e) {
            if (e.key === 'Enter') {
                $('#manualBarcodeBtn').click();
            }
        });

        // ============================================
        // CUSTOMER TYPE TOGGLE
        // ============================================
        $(document).on('click', '.ct-btn', function() {
            $('.ct-btn').removeClass('active');
            $(this).addClass('active');
            const type = $(this).data('type');
            $('#customerType').val(type);
            $('#businessFields').toggle(type === 'business');
        });

        // ============================================
        // CLEAR CART
        // ============================================
        $('#clearCartBtn').on('click', function() {
            if (sellCart.length === 0) return;
            Swal.fire({
                title: 'Clear Cart?',
                text: 'All items will be removed from the cart.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, clear all'
            }).then(result => {
                if (result.isConfirmed) {
                    sellCart = [];
                    batchSelectionData = {};
                    serialSelectionData = {};
                    renderSellCart();
                    showToast('Cart cleared', 'info');
                }
            });
        });

        $('#clearTransferBtn').on('click', function() {
            if (transferCart.length === 0) return;
            Swal.fire({
                title: 'Clear Transfer?',
                text: 'All items will be removed from the transfer cart.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, clear all'
            }).then(result => {
                if (result.isConfirmed) {
                    transferCart = [];
                    renderTransferCart();
                    showToast('Transfer cart cleared', 'info');
                }
            });
        });

        // ============================================
        // CHECKOUT MODAL
        // ============================================
        $('#checkoutBtn').on('click', function() {
            if (sellCart.length === 0) {
                showAlert('Empty Cart', 'Please add items to the cart before checkout.', 'warning');
                return;
            }
            
            const incompleteTracking = sellCart.filter(item => 
                (item.track_batch || item.track_serial) && !item.tracking_complete
            );
            
            if (incompleteTracking.length > 0) {
                const names = incompleteTracking.map(i => i.name).join(', ');
                showAlert('Tracking in Progress', `Auto-selecting tracking for: ${names}. Please wait.`, 'warning');
                return;
            }
            
            const name = $('#customerName').val().trim();
            const phone = $('#customerPhone').val().trim();
            
            if (!name) {
                showAlert('Missing Info', 'Please enter customer name.', 'warning');
                $('#customerName').focus();
                return;
            }
            if (!phone) {
                showAlert('Missing Info', 'Please enter customer phone number.', 'warning');
                $('#customerPhone').focus();
                return;
            }
            
            const warehouseId = $sellFromWarehouse.val();
            if (!warehouseId) {
                showAlert('Missing Location', 'Please select a warehouse to sell from.', 'warning');
                return;
            }
            
            populateCheckoutModal();
            $('#checkoutModal').modal('show');
        });

        function populateCheckoutModal() {
            let itemsHtml = '';
            sellCart.forEach(item => {
                itemsHtml += `
                    <div class="item-row">
                        <span>${item.name} × ${item.quantity}</span>
                        <span>${formatCurrency(item.price * item.quantity)}</span>
                    </div>
                `;
            });
            $('#checkoutItems').html(itemsHtml);
            
            const subtotal = sellCart.reduce((sum, i) => sum + (i.price * i.quantity), 0);
            
            // Calculate discounts and tax for checkout modal
            let itemDiscount = 0;
            let additionalDiscount = 0;
            let taxAmount = 0;
            
            sellCart.forEach(item => {
                if (item.discount_percentage && item.discount_percentage > 0) {
                    const discountAmount = (item.price * item.discount_percentage) / 100;
                    itemDiscount += discountAmount * item.quantity;
                }
                if (item.additional_discount_percentage) {
                    const additionalAmount = (item.price * item.additional_discount_percentage) / 100;
                    additionalDiscount += additionalAmount * item.quantity;
                }
                if (item.tax_percentage && item.tax_percentage > 0) {
                    const itemTotal = item.price * item.quantity;
                    const itemDiscountAmount = (item.discount_percentage || 0) > 0 ? (item.price * item.discount_percentage / 100) * item.quantity : 0;
                    const itemAdditionalDiscount = (item.additional_discount_percentage || 0) > 0 ? (item.price * item.additional_discount_percentage / 100) * item.quantity : 0;
                    const taxableAmount = itemTotal - itemDiscountAmount - itemAdditionalDiscount;
                    taxAmount += (taxableAmount * item.tax_percentage) / 100;
                }
            });
            
            const total = subtotal - itemDiscount - additionalDiscount + taxAmount;
            
            $('#checkoutSubtotal').text(formatCurrency(subtotal));
            $('#checkoutItemDiscount').text('- ' + formatCurrency(itemDiscount));
            $('#checkoutAdditionalDiscount').text('- ' + formatCurrency(additionalDiscount));
            $('#checkoutTax').text('+ ' + formatCurrency(taxAmount));
            $('#checkoutTotal').text(formatCurrency(total));
            
            $('#checkoutCustomerName').val($('#customerName').val());
            $('#checkoutCustomerPhone').val($('#customerPhone').val());
            
            $('#checkoutPaymentMethod').val('cash');
            $('#checkoutAmountReceived').val('');
            $('#checkoutChange').val('');
            $('#cashFields').addClass('show');
            $('#amountReceivedError').removeClass('show');
            $('#checkoutAmountReceived').removeClass('error');
            $('#changeDisplay').hide();
            
            $('#confirmCheckoutBtn').prop('disabled', false);
        }

        // ============================================
        // CHECKOUT PAYMENT HANDLING
        // ============================================
        $('#checkoutPaymentMethod').on('change', function() {
            const method = $(this).val();
            if (method === 'cash' || method === 'cod') {
                $('#cashFields').addClass('show');
                $('#checkoutAmountReceived').prop('required', true);
            } else {
                $('#cashFields').removeClass('show');
                $('#checkoutAmountReceived').prop('required', false);
                $('#changeDisplay').hide();
                $('#amountReceivedError').removeClass('show');
                $('#checkoutAmountReceived').removeClass('error');
            }
        });

        $('#checkoutAmountReceived').on('input', function() {
            const total = parseFloat($('#checkoutTotal').text().replace('₹ ', '').replace(/,/g, '')) || 0;
            const received = parseFloat($(this).val()) || 0;
            
            if (received > 0) {
                if (received >= total) {
                    const change = received - total;
                    $('#checkoutChange').val('₹ ' + change.toFixed(2));
                    $('#changeDisplay').show();
                    $('#amountReceivedError').removeClass('show');
                    $('#checkoutAmountReceived').removeClass('error');
                } else {
                    $('#changeDisplay').hide();
                    $('#amountReceivedError').addClass('show');
                    $('#checkoutAmountReceived').addClass('error');
                }
            } else {
                $('#changeDisplay').hide();
                $('#amountReceivedError').removeClass('show');
                $('#checkoutAmountReceived').removeClass('error');
            }
        });

        // ============================================
        // CONFIRM CHECKOUT - Opens Logistics Modal
        // ============================================
        $('#confirmCheckoutBtn').on('click', function() {
            const paymentMethod = $('#checkoutPaymentMethod').val();
            
            if (!paymentMethod) {
                showAlert('Missing Payment', 'Please select a payment method.', 'warning');
                return;
            }
            
            if (paymentMethod === 'cash' || paymentMethod === 'cod') {
                const received = parseFloat($('#checkoutAmountReceived').val()) || 0;
                const total = parseFloat($('#checkoutTotal').text().replace('₹ ', '').replace(/,/g, '')) || 0;
                
                if (received < total) {
                    showAlert('Insufficient Amount', 'Amount received must be greater than or equal to the total amount.', 'warning');
                    $('#checkoutAmountReceived').focus();
                    return;
                }
            }
            
            $('#checkoutModal').modal('hide');
            
            $('#logisticsTransporter').val('');
            $('#logisticsVehicle').val('');
            $('#logisticsDriver').val('');
            $('#logisticsDriverPhone').val('');
            $('#logisticsNotes').val('');
            
            $('#logisticsModal').modal('show');
        });

        // ============================================
        // CONFIRM LOGISTICS - Final Submit
        // ============================================
        $('#confirmLogisticsBtn').on('click', function() {
            const logisticsData = {
                transporter: $('#logisticsTransporter').val() || null,
                vehicle: $('#logisticsVehicle').val() || null,
                driver: $('#logisticsDriver').val() || null,
                driver_phone: $('#logisticsDriverPhone').val() || null,
                notes: $('#logisticsNotes').val() || null
            };
            
            $('#logisticsModal').modal('hide');
            
            const itemsData = sellCart.map(item => ({
                id: item.id,
                name: item.name,
                code: item.code,
                quantity: item.quantity,
                price: item.price,
                batches: item.batches || [],
                serials: item.serials || [],
                tax_percentage: item.tax_percentage || 0,
                discount_percentage: item.discount_percentage || 0,
                additional_discount_percentage: item.additional_discount_percentage || 0
            }));
            
            $('#itemsDataInput').val(JSON.stringify(itemsData));
            
            const batchData = {};
            Object.keys(batchSelectionData).forEach(key => {
                batchData[key] = batchSelectionData[key];
            });
            $('#batchDataInput').val(JSON.stringify(batchData));
            
            const serialData = {};
            Object.keys(serialSelectionData).forEach(key => {
                serialData[key] = serialSelectionData[key];
            });
            $('#serialDataInput').val(JSON.stringify(serialData));
            
            const paymentMethod = $('#checkoutPaymentMethod').val();
            const received = parseFloat($('#checkoutAmountReceived').val()) || 0;
            const total = parseFloat($('#checkoutTotal').text().replace('₹ ', '').replace(/,/g, '')) || 0;
            const change = received - total;
            
            $('#paymentMethodInput').val(paymentMethod);
            $('#amountReceivedInput').val(received);
            $('#changeAmountInput').val(change > 0 ? change : 0);
            
            if (logisticsData.transporter || logisticsData.vehicle || logisticsData.driver) {
                $('<input>').attr({ 
                    type: 'hidden', 
                    name: 'logistics_data', 
                    value: JSON.stringify(logisticsData) 
                }).appendTo('#stockOutForm');
            }
            
            $('#confirmCheckoutBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
            $('#stockOutForm').submit();
        });

        // ============================================
        // TRANSFER CHECKOUT
        // ============================================
        $('#transferCheckoutBtn').on('click', function() {
            if (transferCart.length === 0) {
                showAlert('Empty Transfer', 'Please add items to transfer.', 'warning');
                return;
            }
            
            const fromId = $transferFromLocation.val();
            const toId = $transferToLocation.val();
            
            if (!fromId || !toId) {
                showAlert('Missing Locations', 'Please select both source and destination locations.', 'warning');
                return;
            }
            
            if (fromId === toId) {
                showAlert('Invalid Transfer', 'Source and destination cannot be the same.', 'error');
                return;
            }
            
            const subType = $('.ts-btn.active').data('subtype');
            
            $('#logisticsTransporter').val('');
            $('#logisticsVehicle').val('');
            $('#logisticsDriver').val('');
            $('#logisticsDriverPhone').val('');
            $('#logisticsNotes').val('');
            
            window._pendingTransferData = {
                fromId: fromId,
                toId: toId,
                subType: subType,
                items: transferCart.map(item => ({
                    id: item.id,
                    name: item.name,
                    code: item.code,
                    quantity: item.quantity
                }))
            };
            
            $('#logisticsModal').modal('show');
        });

        // Override logistics confirm for transfer mode
        $(document).on('click', '#confirmLogisticsBtn', function() {
            if (currentMode === 'transfer' && window._pendingTransferData) {
                const data = window._pendingTransferData;
                
                const logisticsData = {
                    transporter: $('#logisticsTransporter').val() || null,
                    vehicle: $('#logisticsVehicle').val() || null,
                    driver: $('#logisticsDriver').val() || null,
                    driver_phone: $('#logisticsDriverPhone').val() || null,
                    notes: $('#logisticsNotes').val() || null
                };
                
                $('#logisticsModal').modal('hide');
                
                $('#itemsDataInput').val(JSON.stringify(data.items));
                $('#selectedSubType').val(data.subType);
                
                $('#stockOutForm input[name="from_warehouse_id"], #stockOutForm input[name="to_warehouse_id"], #stockOutForm input[name="from_store_id"], #stockOutForm input[name="to_store_id"]').remove();
                
                if (data.subType === 'warehouse_to_warehouse') {
                    $('<input>').attr({ type: 'hidden', name: 'from_warehouse_id', value: data.fromId }).appendTo('#stockOutForm');
                    $('<input>').attr({ type: 'hidden', name: 'to_warehouse_id', value: data.toId }).appendTo('#stockOutForm');
                } else if (data.subType === 'store_to_store') {
                    $('<input>').attr({ type: 'hidden', name: 'from_store_id', value: data.fromId }).appendTo('#stockOutForm');
                    $('<input>').attr({ type: 'hidden', name: 'to_store_id', value: data.toId }).appendTo('#stockOutForm');
                } else if (data.subType === 'warehouse_to_store') {
                    $('<input>').attr({ type: 'hidden', name: 'from_warehouse_id', value: data.fromId }).appendTo('#stockOutForm');
                    $('<input>').attr({ type: 'hidden', name: 'to_store_id', value: data.toId }).appendTo('#stockOutForm');
                }
                
                if (logisticsData.transporter || logisticsData.vehicle || logisticsData.driver) {
                    $('<input>').attr({ 
                        type: 'hidden', 
                        name: 'logistics_data', 
                        value: JSON.stringify(logisticsData) 
                    }).appendTo('#stockOutForm');
                }
                
                $transferCheckoutBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
                $('#stockOutForm').submit();
                
                window._pendingTransferData = null;
            }
        });

        // ============================================
        // KEYBOARD SHORTCUTS
        // ============================================
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && isScannerOpen) {
                closeScanner();
            }
            
            if (e.ctrlKey && e.shiftKey && (e.key === 's' || e.key === 'S')) {
                e.preventDefault();
                openScanner();
            }
            
            if (e.key === 'F5' || (e.ctrlKey && e.key === 'f')) {
                e.preventDefault();
                if (currentMode === 'sell') {
                    if ($sellFromWarehouse.val()) {
                        $('#itemSearch').focus();
                    }
                } else {
                    if ($transferFromLocation.val() && $transferToLocation.val()) {
                        $('#transferSearch').focus();
                    }
                }
            }
            
            if (e.ctrlKey && e.key === 'Enter') {
                e.preventDefault();
                if (currentMode === 'sell') {
                    if (!$checkoutBtn.prop('disabled')) {
                        $('#checkoutBtn').click();
                    }
                } else {
                    if (!$transferCheckoutBtn.prop('disabled')) {
                        $('#transferCheckoutBtn').click();
                    }
                }
            }
        });

        // ============================================
        // SEARCH EVENTS
        // ============================================
        $itemSearch.on('input', function() {
            renderItems();
        });

        $transferSearch.on('input', function() {
            renderTransferItems();
        });

        // ============================================
        // INITIALIZATION
        // ============================================
        $sellFromWarehouse.val('');
        $sellFromStore.val('');
        $warehouseNotice.addClass('show').text('Please select a warehouse to see items');
        $sellFromWarehouse.addClass('error');
        $transferLocationNotice.show().text('Please select both source and destination locations');
        
        enableSellSearch(false);
        enableTransferSearch(false);
        
        renderSellCart();
        renderTransferCart();
        populateTransferLocations();
        $('#customerType').val('individual');
    });
</script>
@endsection
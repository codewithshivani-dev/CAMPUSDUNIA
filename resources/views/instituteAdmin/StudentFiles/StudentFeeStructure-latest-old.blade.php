@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
/* Main Cards & Layout */
.main-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    border: none;
    margin-bottom: 20px;
}

.card-header {
    background: linear-gradient(135deg, #2951c3 0%, #2850c3 100%);
    color: white;
    border-radius: 12px 12px 0 0 !important;
    padding: 15px 20px;
    border: none;
}

.student-info-card {
    background: #f8f9fa;
    border-left: 4px solid #007bff;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 0 8px 8px 0;
}

/* Fee Category Cards */
.fee-category-compact {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
    position: relative;
}

.fee-category-compact:hover {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.fee-category-compact.has-data {
    border-left: 4px solid #28a745;
}

.fee-category-compact.no-data {
    border-left: 4px solid #6c757d;
    opacity: 0.7;
}

.category-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.category-title {
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
    font-size: 0.95rem;
}

.fee-amount {
    font-size: 1rem;
    font-weight: bold;
    color: #28a745;
}

.fee-detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 0;
    border-bottom: 1px solid #f8f9fa;
    font-size: 0.85rem;
}

.fee-detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    color: #6c757d;
}

.detail-value {
    font-weight: 500;
    color: #2c3e50;
}

/* Summary Cards */
.fee-summary-card {
    background: linear-gradient(135deg, #2951c3 0%, #2850c3 100%);
    color: white;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
}

.fee-summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.fee-summary-item:last-child {
    margin-bottom: 0;
}

.total-card-small {
    background: #28a745;
    color: white;
    border-radius: 8px;
    padding: 12px 15px;
    text-align: center;
    margin-bottom: 0;
}

.multi-category-summary {
    background: linear-gradient(135deg, #2951c3 0%, #2850c3 100%);
    color: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}

.multi-category-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.multi-category-item:last-child {
    border-bottom: none;
}

.multi-category-total {
    font-size: 1.3rem;
    font-weight: 700;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 2px solid rgba(255, 255, 255, 0.3);
}

/* Gateway Charges */
.gateway-charge-summary {
    background: linear-gradient(135deg, #2951c3 0%, #2850c3 100%);
    border-radius: 8px;
    padding: 15px;
    margin: 15px 0;
    color: #fff;
}

.gateway-charge-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.gateway-charge-item:last-child {
    margin-bottom: 0;
}

.gateway-charge-breakdown {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 12px;
    margin: 10px 0;
    font-size: 0.85rem;
}

.charge-breakdown-item {
    display: flex;
    justify-content: space-between;
    padding: 4px 0;
    color: #3c3d3e;
}

.charge-total {
    font-weight: 600;
    border-top: 1px solid #dee2e6;
    padding-top: 6px;
    margin-top: 6px;
}

.gateway-selection {
    margin-top: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.gateway-options {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 10px;
}

.gateway-option {
    flex: 1;
    min-width: 150px;
}

.gateway-radio {
    display: none;
}

.gateway-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 12px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
}

.gateway-label:hover {
    border-color: #007bff;
    background: #f8f9fa;
}

.gateway-radio:checked+.gateway-label {
    border-color: #28a745;
    background: #d4edda;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2);
}

.gateway-icon {
    font-size: 1.5rem;
    margin-bottom: 8px;
    color: #495057;
}

.gateway-name {
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 4px;
    color: #000;
}

.gateway-charge {
    font-size: 0.8rem;
    color: #6c757d;
}

.final-amount-display {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border-radius: 8px;
    padding: 20px;
    margin: 15px 0;
    text-align: center;
}

.final-amount-label {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-bottom: 5px;
}

.final-amount-value {
    font-size: 2rem;
    font-weight: 700;
}

/* Icons & Badges */
.icon-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    font-size: 0.9rem;
}

.stats-badge {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    padding: 4px 10px;
    margin: 2px;
    font-size: 0.8rem;
}

.category-badge {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 0.8rem;
    font-weight: 500;
    margin-left: 8px;
}

.late-fee-indicator {
    background: #ffc107;
    color: #856404;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    margin-left: 8px;
}

.selected-count {
    background: #007bff;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

/* Status Indicators */
.late-fee-item,
.late-fee-applied {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    border-radius: 6px;
    padding: 8px 12px;
    margin-top: 8px;
}

.late-fee-info,
.course-dates {
    background: #e7f3ff;
    border: 1px solid #b3d9ff;
    border-radius: 6px;
    padding: 8px 12px;
    margin-top: 8px;
    font-size: 0.8rem;
}

.late-fee-pending {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 6px;
    padding: 8px 12px;
    margin-top: 8px;
}

.date-overdue {
    color: #dc3545;
    font-weight: 600;
}

.date-valid {
    color: #28a745;
    font-weight: 600;
}

.date-upcoming {
    color: #ffc107;
    font-weight: 600;
}

/* Buttons */
.loan-btn,
.pay-btn {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    color: white;
    border: none;
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 0.75rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 10px;
    width: 100%;
    justify-content: center;
}

.loan-btn:hover,
.pay-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(238, 90, 36, 0.3);
}

.payment-gateway-btn {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
    margin: 5px;
    flex: 1;
    justify-content: center;
}

.payment-gateway-btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.payment-gateway-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.loan-apply-btn {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
    margin: 5px;
    flex: 1;
    justify-content: center;
}

.loan-apply-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
}

.pay-all-fees-btn {
    background: linear-gradient(135deg, #2951c3 0%, #2850c3 100%);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 12px 30px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 20px auto;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.pay-all-fees-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.category-pay-btn {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
    border-radius: 6px;
    padding: 12px 24px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
    justify-content: center;
}

.category-pay-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.category-pay-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.bulk-select-btn {
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.bulk-select-btn:hover {
    background: #5a6268;
}

.quick-action-btn {
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.3s ease;
    flex: 1;
}

.quick-action-btn:hover {
    background: #5a6268;
}

.quick-action-btn.primary {
    background: #28a745;
}

.quick-action-btn.primary:hover {
    background: #218838;
}

.quick-action-btn.danger {
    background: #dc3545;
}

.quick-action-btn.danger:hover {
    background: #c82333;
}

.payment-receipt-btn {
    background: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 6px 12px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.payment-receipt-btn:hover {
    background: #0056b3;
}

.view-receipt-btn {
    background: #17a2b8;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 4px 8px;
    font-size: 0.7rem;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-left: 5px;
}

.view-receipt-btn:hover {
    background: #138496;
}

.close-btn {
    background: none;
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background 0.3s ease;
}

.close-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

.remove-item-btn {
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 4px 8px;
    font-size: 0.7rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.remove-item-btn:hover {
    background: #c82333;
}

/* Tables */
.fee-installment-table,
.category-installment-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.fee-installment-table th,
.category-installment-table th {
    background: #f8f9fa;
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    color: #2c3e50;
    border-bottom: 2px solid #dee2e6;
    font-size: 0.85rem;
}

.fee-installment-table td,
.category-installment-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e9ecef;
    font-size: 0.85rem;
}

.fee-installment-table tr,
.category-installment-table tr {
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.fee-installment-table tr:hover,
.category-installment-table tr:hover {
    background-color: #f8f9fa;
}

.fee-installment-table tr.selected,
.category-installment-table tr.selected {
    background-color: #e3f2fd;
    border-left: 3px solid #2196f3;
}

.installment-checkbox {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.installment-amount-cell {
    font-weight: 600;
    color: #28a745;
    text-align: right;
}

/* Payment History Table */
.payment-history-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.payment-history-table th {
    background: #f8f9fa;
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    color: #2c3e50;
    border-bottom: 2px solid #dee2e6;
}

.payment-history-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e9ecef;
}

.payment-history-table tr:hover {
    background-color: #f8f9fa;
}

/* Status Badges & Indicators */
.installment-status {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-badge-overdue,
.status-badge-unpaid {
    background: #f8d7da;
    color: #721c24;
}

.status-badge-upcoming {
    background: #d1edff;
    color: #004085;
}

.status-badge-current,
.status-badge-paid {
    background: #d4edda;
    color: #155724;
}

.status-badge-partially-paid {
    background: #fff3cd;
    color: #856404;
}

.status-paid {
    background: #d4edda !important;
    color: #155724 !important;
    border-left: 4px solid #28a745 !important;
}

.status-unpaid {
    background: #ffffff !important;
    color: #2c3e50 !important;
}

.status-partially-paid {
    background: #fff3cd !important;
    color: #856404 !important;
    border-left: 4px solid #ffc107 !important;
}

.status-paid-row,
.disabled-row {
    background-color: #f8f9fa !important;
    opacity: 0.7;
    cursor: not-allowed !important;
}

.status-paid-row:hover,
.disabled-row:hover {
    background-color: #f8f9fa !important;
    cursor: not-allowed;
}

.status-paid-checkbox {
    opacity: 0.5;
    cursor: not-allowed;
}

.payment-status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.8rem;
}

.paid-amount {
    color: #28a745;
    font-weight: 600;
}

.pending-amount {
    color: #dc3545;
    font-weight: 600;
}

.installment-status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

/* Tab System */
.fee-tabs-container {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    margin-bottom: 25px;
    overflow: hidden;
}

.fee-tabs-header {
    background: linear-gradient(135deg, #2951c3 0%, #2850c3 100%);
    color: white;
    padding: 20px 25px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.fee-tabs-header h4 {
    margin: 0;
    font-weight: 600;
}

.fee-tabs-header .subtitle {
    opacity: 0.9;
    font-size: 0.9rem;
    margin-top: 5px;
}

.category-tabs-container {
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
}

.category-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.category-tab-item {
    flex: 1;
    min-width: 150px;
    max-width: 200px;
}

.category-tab-link {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 15px 10px;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    color: #495057;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    text-align: center;
}

.category-tab-link:hover {
    background: #e9ecef;
    border-color: #dee2e6;
    transform: translateY(-2px);
}

.category-tab-link.active {
    background: #fff;
    border-color: #385ece;
    color: #385ece;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

.category-tab-link .tab-icon {
    font-size: 1.5rem;
    margin-bottom: 8px;
    opacity: 0.8;
}

.category-tab-link .tab-title {
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 4px;
}

.category-tab-link .tab-badge {
    background: rgba(102, 126, 234, 0.1);
    color: #385ece;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.category-tab-link.active .tab-badge {
    background: #385ece;
    color: white;
}

.tab-content-container {
    padding: 25px;
}

.category-tab-pane,
.category-tab-content {
    display: none;
    animation: fadeIn 0.3s ease;
}

.category-tab-pane.active,
.category-tab-content.active {
    display: block;
}

/* Modals */
.fee-detail-modal,
.multi-category-modal,
.payment-history-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1050;
    backdrop-filter: blur(5px);
}

.multi-category-modal {
    z-index: 1060;
}

.payment-history-modal {
    z-index: 1070;
}

.fee-detail-content,
.multi-category-content,
.payment-history-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 12px;
    width: 95%;
    max-width: 800px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}

.multi-category-content {
    max-width: 900px;
}

.payment-history-content {
    max-width: 800px;
}

.fee-detail-header,
.multi-category-header,
.payment-history-header {
    background: linear-gradient(135deg, #2951c3 0%, #2850c3 100%);
    color: white;
    padding: 20px 25px;
    border-radius: 12px 12px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.payment-history-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.fee-detail-body,
.multi-category-body,
.payment-history-body {
    padding: 25px;
}

/* Selection & Summary Areas */
.selection-summary,
.category-selection-summary {
    background: #e7f3ff;
    border: 1px solid #b3d9ff;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
    display: none;
}

.selection-summary.show,
.category-selection-summary {
    display: block;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.summary-item:last-child {
    margin-bottom: 0;
}

.summary-total {
    font-weight: bold;
    font-size: 1.1rem;
    color: #28a745;
    border-top: 1px solid #b3d9ff;
    padding-top: 8px;
    margin-top: 8px;
}

.selected-items-list {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 20px;
}

.selected-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    border-bottom: 1px solid #f8f9fa;
    background: #f8f9fa;
}

.selected-item:last-child {
    border-bottom: none;
}

.empty-selection {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.empty-selection i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.5;
}

.category-fee-actions {
    margin-top: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    display: none;
}

.category-fee-actions.show {
    display: block;
}

.select-all-category {
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    color: #495057;
}

.bulk-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.table-header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.quick-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

/* Category Specific Styles */
.hostel-fee-structure {
    border-left: 4px solid #28a745;
}

.transportation-fee-structure {
    border-left: 4px solid #ffc107;
}

.registration-fee-structure {
    border-left: 4px solid #dc3545;
}

.miscellaneous-fee-structure {
    border-left: 4px solid #6f42c1;
}

.custom-fee-structure {
    border-left: 4px solid #fd7e14;
}

.course-fee-structure {
    border-left: 4px solid #007bff;
}

/* Category Stats */
.category-stats-header {
    background: linear-gradient(135deg, #2951c3 0%, #2850c3 100%);
    color: white;
    border-radius: 8px;
    padding: 15px 20px;
    margin-bottom: 20px;
}

.category-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.category-stat-item {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 6px;
    padding: 10px 15px;
}

.category-stat-label {
    font-size: 0.8rem;
    opacity: 0.9;
    margin-bottom: 5px;
}

.category-stat-value {
    font-size: 1.2rem;
    font-weight: 600;
}

/* Payment Options */
.payment-options {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.payment-options-buttons {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.fee-installment-card {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.fee-installment-card:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.2);
}

.fee-installment-card.selected {
    border-color: #28a745;
    background: #d4edda;
}

.installment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.installment-title {
    font-weight: 600;
    color: #2c3e50;
}

.installment-amount {
    font-weight: bold;
    color: #28a745;
    font-size: 1.1rem;
}

.installment-dates {
    font-size: 0.8rem;
    color: #6c757d;
}

.installment-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px;
}

/* Payment Date Column */
.payment-date-cell {
    min-width: 120px;
}

.last-payment-date {
    font-size: 0.85rem;
    color: #28a745;
    font-weight: 500;
}

.payment-date-pending {
    color: #dc3545;
}

.payment-date-upcoming {
    color: #ffc107;
}

.no-payment-record {
    color: #6c757d;
    font-style: italic;
    font-size: 0.8rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 50px 20px;
    background: #f8f9fa;
    border-radius: 10px;
    margin: 20px 0;
}

.empty-state i {
    font-size: 3rem;
    color: #adb5bd;
    margin-bottom: 15px;
}

.empty-state h5 {
    color: #6c757d;
    margin-bottom: 10px;
}

.empty-state p {
    color: #adb5bd;
    font-size: 0.9rem;
}

/* View Toggle */
.view-toggle {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.view-toggle-btn {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 0.85rem;
    color: #495057;
    cursor: pointer;
    transition: all 0.3s ease;
}

.view-toggle-btn.active {
    background: #385ece;
    color: white;
    border-color: #385ece;
}

.view-toggle-btn:hover:not(.active) {
    background: #e9ecef;
}

/* Grid Layout */
.fee-categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

/* Utility Classes */
.clearfix::after {
    content: "";
    clear: both;
    display: table;
}

.mb-20 {
    margin-bottom: 20px;
}

.mt-20 {
    margin-top: 20px;
}

.select-all-checkbox {
    margin-right: 10px;
}

/* Receipt Modal Styles */
.receipt-modal {
    max-width: 800px;
}

.receipt-container {
    padding: 0;
}

.receipt-paper {
    background: white;
    padding: 40px;
    font-family: 'Courier New', monospace;
    max-width: 210mm;
    margin: 0 auto;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

.receipt-header {
    text-align: center;
    margin-bottom: 30px;
    border-bottom: 3px double #333;
    padding-bottom: 20px;
}

.institute-name {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 5px;
    color: #2c3e50;
}

.receipt-title {
    font-size: 22px;
    font-weight: bold;
    color: #2c3e50;
    margin: 15px 0;
    text-transform: uppercase;
}

.receipt-body {
    margin: 30px 0;
}

.receipt-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px dashed #ddd;
}

.receipt-row.header {
    font-weight: bold;
    background: #f8f9fa;
    padding: 10px 0;
    border-bottom: 2px solid #333;
}

.receipt-label {
    flex: 1;
    font-weight: 500;
}

.receipt-value {
    flex: 2;
    text-align: right;
}

.amount-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin: 30px 0;
    border: 1px solid #dee2e6;
}

.amount-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
}

.amount-total {
    font-size: 20px;
    font-weight: bold;
    border-top: 2px solid #333;
    padding-top: 10px;
    margin-top: 10px;
}

.receipt-footer {
    margin-top: 40px;
    text-align: center;
    border-top: 3px double #333;
    padding-top: 20px;
}

.signature-section {
    display: flex;
    justify-content: space-between;
    margin: 40px 0;
}

.signature-box {
    text-align: center;
    flex: 1;
    padding: 0 20px;
}

.signature-line {
    width: 200px;
    height: 1px;
    background: #333;
    margin: 30px auto 10px;
}

.terms {
    font-size: 12px;
    color: #7f8c8d;
    margin-top: 20px;
    text-align: left;
}

.watermark {
    position: absolute;
    opacity: 0.1;
    font-size: 120px;
    transform: rotate(-45deg);
    top: 30%;
    left: 10%;
    color: #333;
    pointer-events: none;
}

.receipt-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-top: 30px;
    padding: 20px;
    border-top: 1px solid #dee2e6;
    background: #f8f9fa;
}
.pay-all-fees-btn{
        position: fixed;
    right: 20px;
    bottom: 20px;
}
@media print {
    body * {
        visibility: hidden;
    }

    .receipt-paper,
    .receipt-paper * {
        visibility: visible;
    }

    .receipt-paper {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none;
    }

    .receipt-actions {
        display: none;
    }
}

.receipt-preview {
    max-height: 600px;
    overflow-y: auto;
    margin-bottom: 20px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .category-tab-item {
        min-width: 120px;
    }

    .gateway-options {
        flex-direction: column;
    }

    .gateway-option {
        min-width: 100%;
    }

    .category-stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .category-tabs-container {
        padding: 15px;
    }

    .tab-content-container {
        padding: 15px;
    }

    .payment-options-buttons {
        flex-direction: column;
    }
}

@media (max-width: 576px) {
    .category-tabs {
        gap: 5px;
    }

    .category-tab-item {
        min-width: 100px;
    }

    .category-tab-link .tab-icon {
        font-size: 1.2rem;
    }

    .category-stats-grid {
        grid-template-columns: 1fr;
    }

    .bulk-actions {
        flex-direction: column;
    }

    .quick-actions {
        flex-direction: column;
    }
}
</style>
<div class="container-fluid py-3">
    <div class="main-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-money-bill-wave me-2"></i>My Fee Structure
                </h5>
                <div class="text-white">
                    <small><i class="fas fa-info-circle me-1"></i>Applicable Fee Structure for Your Course</small>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Student Information -->
            <div class="student-info-card">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-circle bg-primary text-white">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $studentDetail->first_name ?? 'Student Name' }}
                                    {{ $studentDetail->last_name ?? '' }}</h5>
                                <p class="mb-0 text-muted">Reg No. : {{ $studentDetail->registration_number ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap">
                            <span class="stats-badge">
                                <i class="fas fa-building me-1"></i>Department:
                                <b>{{ $studentDetail->academicTransportDetails->department ?? 'N/A' }}</b>
                            </span>
                            <span class="stats-badge">
                                <i class="fas fa-book me-1"></i>Course:
                                <b>{{ $studentDetail->academicTransportDetails->course_type ?? 'N/A' }}</b>
                            </span>
                            <span class="stats-badge">
                                <i class="fas fa-code-branch me-1"></i>Branch:
                                <b>{{ $studentDetail->academicTransportDetails->course_subtype ?? 'N/A' }}</b>
                            </span>
                            <span class="stats-badge">
                                <i class="fas fa-layer-group me-1"></i>Batch:
                                <b>{{ $studentDetail->academicTransportDetails->batch ?? 'N/A' }}</b>
                            </span>
                            <span class="stats-badge">
                                <i class="fas fa-graduation-cap me-1"></i>Academic Year:
                                <b>{{ $studentDetail->academicTransportDetails->academic_year ?? 'N/A' }}</b>
                            </span>
                            <span class="stats-badge">
                                <i class="fas fa-graduation-cap me-1"></i>Mode:
                                <b>{{ $studentDetail->academicTransportDetails->mode_of_course ?? 'N/A' }}</b>
                            </span>
                            <span class="stats-badge">
                                <i class="fas fa-graduation-cap me-1"></i>Section:
                                <b>{{ $studentDetail->academicTransportDetails->section_id ?? 'N/A' }}</b>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="total-card-small">
                            <div class="small">Total Fee (After Discounts)</div>
                            @php
                            $totalAllFees = 0;
                            $feeCategories = [
                            'StudentCourseFeeStructure' => 'course_fee',
                            'StudentHostelFeeStructure' => 'hostel_fee',
                            'StudentTransportFeeStructure' => 'transport_fee',
                            'StudentRegistrationFeeStructure' => 'registration_fee',
                            'StudentMiscellaneousFeeStructure' => 'miscellaneous_fee'
                            ];

                            foreach($feeCategories as $relation => $feeField) {
                            if($studentDetail->$relation) {
                            foreach($studentDetail->$relation as $feeItem) {
                            $baseAmount = floatval($feeItem->$feeField ?? 0);
                            $totalAllFees += $baseAmount - floatval($feeItem->discount_amount ?? 0);
                            }
                            }
                            }
                            if($studentDetail->StudentCustomFeestructure) {
                            foreach($studentDetail->StudentCustomFeestructure as $customFee) {
                            $totalAllFees += floatval($customFee->custom_fee_value ?? 0) - floatval($customFee->discount_amount ?? 0);
                            }
                            }
                            @endphp
                            <div class="h5 mb-0">₹{{ number_format($totalAllFees, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pay All Fees Button -->
            <div class="text-center mb-4">
                <button class="pay-all-fees-btn" onclick="openMultiCategoryModal()">
                    <i class="fas fa-cart-plus"></i>
                    Pay Selected Fees
                    <span class="category-badge" id="totalSelectedItems">0 Items</span>
                </button>
            </div>

            <!-- Category Tabs -->
            <div class="fee-tabs-container">
                <div class="fee-tabs-header">
                    <h4>Fee Categories</h4>
                    <div class="subtitle">Click on a category to view fee details</div>
                </div>

                <div class="category-tabs-container">
                    <ul class="category-tabs">
                        @php
                        $allCategories = [
                        'course_fee' => [
                        'title' => 'Course Fee',
                        'icon' => 'fas fa-book',
                        'relation' => 'StudentCourseFeeStructure',
                        'count' => $studentDetail->StudentCourseFeeStructure ? $studentDetail->StudentCourseFeeStructure->count() : 0
                        ],
                        'hostel_fee' => [
                        'title' => 'Hostel Fee',
                        'icon' => 'fas fa-bed',
                        'relation' => 'StudentHostelFeeStructure',
                        'count' => $studentDetail->StudentHostelFeeStructure ? $studentDetail->StudentHostelFeeStructure->count() : 0
                        ],
                        'transportation_fee' => [
                        'title' => 'Transport Fee',
                        'icon' => 'fas fa-bus',
                        'relation' => 'StudentTransportFeeStructure',
                        'count' => $studentDetail->StudentTransportFeeStructure ? $studentDetail->StudentTransportFeeStructure->count() : 0
                        ],
                        'registration_fee' => [
                        'title' => 'Registration Fee',
                        'icon' => 'fas fa-file-signature',
                        'relation' => 'StudentRegistrationFeeStructure',
                        'count' => $studentDetail->StudentRegistrationFeeStructure ? $studentDetail->StudentRegistrationFeeStructure->count() : 0
                        ],
                        'miscellaneous_fee' => [
                        'title' => 'Miscellaneous',
                        'icon' => 'fas fa-receipt',
                        'relation' => 'StudentMiscellaneousFeeStructure',
                        'count' => $studentDetail->StudentMiscellaneousFeeStructure ? $studentDetail->StudentMiscellaneousFeeStructure->count() : 0
                        ]
                        ];
                        @endphp

                        @foreach($allCategories as $feeKey => $feeCategory)
                        @if($feeCategory['count'] > 0)
                        <li class="category-tab-item">
                            <a href="#" class="category-tab-link {{ $loop->first ? 'active' : '' }}"
                                onclick="switchCategoryTab('{{ $feeKey }}')">
                                <div class="tab-icon"><i class="{{ $feeCategory['icon'] }}"></i></div>
                                <div class="tab-title">{{ $feeCategory['title'] }}</div>
                                <div class="tab-badge">{{ $feeCategory['count'] }} Installments</div>
                            </a>
                        </li>
                        @endif
                        @endforeach

                        @php $customFeesCount = $studentDetail->StudentCustomFeestructure ? $studentDetail->StudentCustomFeestructure->count() : 0; @endphp
                        @if($customFeesCount > 0)
                        <li class="category-tab-item">
                            <a href="#" class="category-tab-link" onclick="switchCategoryTab('custom_fees')">
                                <div class="tab-icon"><i class="fas fa-list-alt"></i></div>
                                <div class="tab-title">Custom Fees</div>
                                <div class="tab-badge">{{ $customFeesCount }} Items</div>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="tab-content-container">
                    @php
                    $categoryConfigs = [
                        'course_fee' => ['class' => 'course-fee-structure', 'fee_field' => 'course_fee'],
                        'hostel_fee' => ['class' => 'hostel-fee-structure', 'fee_field' => 'hostel_fee'],
                        'transportation_fee' => ['class' => 'transportation-fee-structure', 'fee_field' => 'transport_fee'],
                        'registration_fee' => ['class' => 'registration-fee-structure', 'fee_field' => 'registration_fee'],
                        'miscellaneous_fee' => ['class' => 'miscellaneous-fee-structure', 'fee_field' => 'miscellaneous_fee']
                    ];
                    @endphp

                    @foreach($allCategories as $feeKey => $feeCategory)
                    @if($feeCategory['count'] > 0)
                    <div class="category-tab-pane {{ $loop->first ? 'active' : '' }}" id="{{ $feeKey }}TabContent">
                        <div class="category-stats-header">
                            <div class="category-stats-grid">
                                @php
                                $collection = $studentDetail->{$feeCategory['relation']};
                                $config = $categoryConfigs[$feeKey];
                                $paidCount = 0;
                                $pendingCount = 0;
                                $totalFee = 0;
                                foreach($collection as $item) {
                                    $totalFee += floatval($item->{$config['fee_field']} ?? 0);
                                    if(($item->payment_status ?? 'pending') === 'paid') $paidCount++;
                                    else $pendingCount++;
                                }
                                @endphp
                                <div class="category-stat-item">
                                    <div class="category-stat-label">Total {{ $feeCategory['title'] }}</div>
                                    <div class="category-stat-value">₹{{ number_format($totalFee, 2) }}</div>
                                </div>
                                <div class="category-stat-item">
                                    <div class="category-stat-label">Paid Installments</div>
                                    <div class="category-stat-value">{{ $paidCount }} of {{ $collection->count() }}</div>
                                </div>
                                <div class="category-stat-item">
                                    <div class="category-stat-label">Pending</div>
                                    <div class="category-stat-value">{{ $pendingCount }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="fee-category-structure {{ $config['class'] }}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0"><i class="{{ $feeCategory['icon'] }} me-2"></i>{{ $feeCategory['title'] }} Structure</h6>
                                <div class="select-all-category">
                                    <input type="checkbox" id="selectAll{{ ucfirst($feeKey) }}" onchange="toggleSelectAllCategory('{{ $feeKey }}')">
                                    <label for="selectAll{{ ucfirst($feeKey) }}">Select All Payable</label>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="category-installment-table">
                                    <thead>
                                        <tr>
                                            <th width="40"><input type="checkbox" disabled style="opacity:0.5"></th>
                                            <th>Installment</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th>Late Fee</th>
                                            <th>Discount</th>
                                            <th>Total</th>
                                            <th>Receipt</th>
                                        </tr>
                                    </thead>
                                    <tbody id="{{ $feeKey }}TableBody">
                                        @foreach($collection as $index => $feeItem)
                                        @php
                                        $baseAmount = floatval($feeItem->{$config['fee_field']} ?? 0);
                                        $lateFeeAmount = floatval($feeItem->late_fee_amount ?? 0);
                                        $discountAmount = floatval($feeItem->discount_amount ?? 0);
                                        $totalAmount = $baseAmount + $lateFeeAmount - $discountAmount;
                                        $paymentStatus = $feeItem->payment_status ?? 'pending';
                                        $isFullyPaid = $paymentStatus === 'paid';
                                        $dueDate = $feeItem->due_date ? date('d M Y', strtotime($feeItem->due_date)) : 'Not set';
                                        $installmentName = $feeItem->fee_duration_type ?? 'Installment ' . ($index + 1);
                                        @endphp
                                        <tr id="{{ $feeKey }}-installment-{{ $index }}" data-fee-id="{{ $feeItem->id }}"
                                            class="{{ $isFullyPaid ? 'status-paid-row' : '' }}"
                                            @if(!$isFullyPaid) onclick="toggleCategoryInstallmentSelection('{{ $feeKey }}', {{ $index }}, {{ $totalAmount }}, {{ $baseAmount }}, {{ $lateFeeAmount }}, false, '{{ $feeItem->id }}')" @endif>
                                            <td>
                                                <input type="checkbox"
                                                    class="installment-checkbox category-installment-checkbox"
                                                    data-category="{{ $feeKey }}" data-index="{{ $index }}"
                                                    data-fee-id="{{ $feeItem->id }}" data-total="{{ $totalAmount }}"
                                                    data-base="{{ $baseAmount }}" data-late="{{ $lateFeeAmount }}"
                                                    @if($isFullyPaid) disabled class="status-paid-checkbox" @endif
                                                    onclick="event.stopPropagation()"
                                                    onchange="toggleCategoryInstallmentSelection('{{ $feeKey }}', {{ $index }}, {{ $totalAmount }}, {{ $baseAmount }}, {{ $lateFeeAmount }}, false, '{{ $feeItem->id }}', this.checked)">
                                            </td>
                                            <td><strong>{{ $installmentName }}</strong></td>
                                            <td>{{ $dueDate }}</td>
                                            <td>
                                                @if($isFullyPaid)
                                                <span class="status-badge-paid">PAID</span>
                                                @else
                                                <span class="status-badge-unpaid">PENDING</span>
                                                @endif
                                            </td>
                                            <td class="text-end">₹{{ number_format($baseAmount, 2) }}</td>
                                            <td class="text-end">{{ $lateFeeAmount > 0 ? '₹'.number_format($lateFeeAmount, 2) : '-' }}</td>
                                            <td class="text-end">{{ $discountAmount > 0 ? '-₹'.number_format($discountAmount, 2) : '-' }}</td>
                                            <td class="text-end"><strong>₹{{ number_format($totalAmount, 2) }}</strong></td>
                                            <td class="text-center">
                                                @if($isFullyPaid)
                                                <button class="view-receipt-btn" onclick="viewReceipt('{{ $feeItem->id }}', '{{ $feeKey }}', '{{ $feeItem->fee_reference_id ?? '' }}')">
                                                    <i class="fas fa-receipt"></i> Receipt
                                                </button>
                                                @else
                                                <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div id="{{ $feeKey }}Actions" class="category-fee-actions">
                                <div class="category-selection-summary">
                                    <div class="summary-item"><span>Selected:</span><span id="{{ $feeKey }}SelectedCount">0</span></div>
                                    <div class="summary-item"><span>Amount:</span><span id="{{ $feeKey }}BaseAmountTotal">₹0.00</span></div>
                                    <div class="summary-item"><span>Late Fees:</span><span id="{{ $feeKey }}LateFeeTotal">₹0.00</span></div>
                                    <div class="summary-item"><span>Discounts:</span><span id="{{ $feeKey }}DiscountTotal">₹0.00</span></div>
                                    <div class="summary-item summary-total"><span>Total Payable:</span><span id="{{ $feeKey }}TotalPayable">₹0.00</span></div>
                                </div>
                                <button class="category-pay-btn" onclick="proceedToCategoryPayment('{{ $feeKey }}', '{{ $feeCategory['title'] }}')">
                                    <i class="fas fa-credit-card"></i> Pay {{ $feeCategory['title'] }} (<span id="{{ $feeKey }}PayButtonAmount">0.00</span>)
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach

                    <!-- Custom Fees Tab -->
                    @if($customFeesCount > 0)
                    <div class="category-tab-pane" id="custom_feesTabContent">
                        <div class="category-stats-header">
                            <div class="category-stats-grid">
                                @php
                                $customFees = $studentDetail->StudentCustomFeestructure;
                                $customPaidCount = 0;
                                $customTotal = 0;
                                foreach($customFees as $item) {
                                    $customTotal += floatval($item->custom_fee_value ?? 0);
                                    if(($item->payment_status ?? 'pending') === 'paid') $customPaidCount++;
                                }
                                @endphp
                                <div class="category-stat-item">
                                    <div class="category-stat-label">Total Custom Fees</div>
                                    <div class="category-stat-value">₹{{ number_format($customTotal, 2) }}</div>
                                </div>
                                <div class="category-stat-item">
                                    <div class="category-stat-label">Paid Items</div>
                                    <div class="category-stat-value">{{ $customPaidCount }} of {{ $customFeesCount }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="fee-category-structure custom-fee-structure">
                            <div class="table-responsive">
                                <table class="category-installment-table">
                                    <thead>
                                        <tr>
                                            <th width="40"><input type="checkbox" disabled style="opacity:0.5"></th>
                                            <th>Fee Item</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th>Late Fee</th>
                                            <th>Discount</th>
                                            <th>Total</th>
                                            <th>Receipt</th>
                                        </tr>
                                    </thead>
                                    <tbody id="customFeesTableBody">
                                        @foreach($customFees as $index => $customFee)
                                        @php
                                        $baseAmount = floatval($customFee->custom_fee_value ?? 0);
                                        $lateFeeAmount = floatval($customFee->late_fee_amount ?? 0);
                                        $discountAmount = floatval($customFee->discount_amount ?? 0);
                                        $totalAmount = $baseAmount + $lateFeeAmount - $discountAmount;
                                        $paymentStatus = $customFee->payment_status ?? 'pending';
                                        $isFullyPaid = $paymentStatus === 'paid';
                                        $dueDate = $customFee->due_date ? date('d M Y', strtotime($customFee->due_date)) : 'Not set';
                                        @endphp
                                        <tr id="custom-fee-{{ $index }}" class="{{ $isFullyPaid ? 'status-paid-row' : '' }}"
                                            @if(!$isFullyPaid) onclick="toggleCustomFeeSelection({{ $index }}, {{ $totalAmount }}, {{ $baseAmount }}, {{ $lateFeeAmount }}, false, '{{ $customFee->id }}')" @endif>
                                            <td>
                                                <input type="checkbox" class="installment-checkbox custom-fee-checkbox"
                                                    data-index="{{ $index }}" data-fee-id="{{ $customFee->id }}"
                                                    data-total="{{ $totalAmount }}" data-base="{{ $baseAmount }}"
                                                    data-late="{{ $lateFeeAmount }}"
                                                    @if($isFullyPaid) disabled class="status-paid-checkbox" @endif
                                                    onclick="event.stopPropagation()"
                                                    onchange="toggleCustomFeeSelection({{ $index }}, {{ $totalAmount }}, {{ $baseAmount }}, {{ $lateFeeAmount }}, false, '{{ $customFee->id }}', this.checked)">
                                            </td>
                                            <td><strong>{{ $customFee->custom_fee_key ?? 'Custom Fee' }}</strong><br>
                                                <small class="text-muted">{{ $customFee->description ?? '' }}</small>
                                            </td>
                                            <td>{{ $dueDate }}</td>
                                            <td>
                                                @if($isFullyPaid)
                                                <span class="status-badge-paid">PAID</span>
                                                @else
                                                <span class="status-badge-unpaid">PENDING</span>
                                                @endif
                                            </td>
                                            <td class="text-end">₹{{ number_format($baseAmount, 2) }}</td>
                                            <td class="text-end">{{ $lateFeeAmount > 0 ? '₹'.number_format($lateFeeAmount, 2) : '-' }}</td>
                                            <td class="text-end">{{ $discountAmount > 0 ? '-₹'.number_format($discountAmount, 2) : '-' }}</td>
                                            <td class="text-end"><strong>₹{{ number_format($totalAmount, 2) }}</strong></td>
                                            <td class="text-center">
                                                @if($isFullyPaid)
                                                <button class="view-receipt-btn" onclick="viewCustomFeeReceipt('{{ $customFee->id }}', '{{ $customFee->fee_reference_id ?? '' }}')">
                                                    <i class="fas fa-receipt"></i> Receipt
                                                </button>
                                                @else
                                                <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div id="customFeesActions" class="category-fee-actions">
                                <div class="category-selection-summary">
                                    <div class="summary-item"><span>Selected:</span><span id="customFeesSelectedCount">0</span></div>
                                    <div class="summary-item"><span>Amount:</span><span id="customFeesBaseAmountTotal">₹0.00</span></div>
                                    <div class="summary-item"><span>Late Fees:</span><span id="customFeesLateFeeTotal">₹0.00</span></div>
                                    <div class="summary-item"><span>Discounts:</span><span id="customFeesDiscountTotal">₹0.00</span></div>
                                    <div class="summary-item summary-total"><span>Total Payable:</span><span id="customFeesTotalPayable">₹0.00</span></div>
                                </div>
                                <button class="category-pay-btn" onclick="proceedToCustomFeesPayment()">
                                    <i class="fas fa-credit-card"></i> Pay Custom Fees (<span id="customFeesPayButtonAmount">0.00</span>)
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<!-- Payment Modal with Gateway Charges -->
<div class="fee-detail-modal" id="paymentModal">
    <div class="fee-detail-content">
        <div class="fee-detail-header">
            <h5 class="mb-0" id="paymentModalTitle">Payment Confirmation</h5>
            <button class="close-btn" onclick="closePaymentModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="fee-detail-body">
            <div class="text-center mb-4">
                <i class="fas fa-credit-card fa-3x text-primary mb-3"></i>
                <h6>Confirm Your Payment</h6>
            </div>

            <!-- Selection Summary -->
            <div class="selection-summary show">
                <div class="summary-item">
                    <span>Selected Items:</span>
                    <span id="modalSelectedCount">0</span>
                </div>
                <div class="summary-item">
                    <span>Amount:</span>
                    <span id="modalBaseAmountTotal">₹0.00</span>
                </div>
                <div class="summary-item">
                    <span>Late Fees:</span>
                    <span id="modalLateFeeTotal">₹0.00</span>
                </div>
                <div class="summary-item">
                    <span>Discounts:</span>
                    <span id="modalDiscountTotal">₹0.00</span>
                </div>
                <div class="summary-item summary-total">
                    <span>Total Amount:</span>
                    <span id="modalTotalPayable">₹0.00</span>
                </div>
            </div>

            <!-- Gateway Charges Section -->
            <div class="gateway-charge-summary" id="gatewayChargeSummary">
                <h6 class="mb-3"><i class="fas fa-credit-card me-2"></i>Payment Gateway Charges (Select this tab to continue the payment)</h6>

                <!-- Gateway Selection -->
                <div class="gateway-selection" id="gatewaySelection">
                    <h6 class="mb-2">Select Payment Gateway</h6>
                    <div class="gateway-options">
                        <!-- Razorpay Gateway -->
                        <div class="gateway-option">
                            <input type="radio" name="paymentGateway" id="gatewayRazorpay" value="1"
                                class="gateway-radio" 
                                data-gateway-name="{{ $charges->gateway_name ?? 'Razorpay' }}"
                                data-charge-type="{{ $charges->charge_type ?? 'percentage' }}"
                                data-charge-value="{{ $charges->charge_value ?? 0 }}"
                                data-gst-applicable="{{ $charges->gst_applicable ?? 0 }}"
                                data-gst-percentage="{{ $charges->gst_percentage ?? 0 }}"
                                data-charges-minon="{{ $charges->min_transaction_amount ?? 0 }}"
                                data-charges-maxon="{{ $charges->max_transaction_amount ?? 0 }}"
                                onchange="calculateGatewayCharges(this)">
                            <label for="gatewayRazorpay" class="gateway-label">
                                <div class="gateway-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="gateway-name">Razorpay</div>
                                <div class="gateway-charge">
                                    @if($charges)
                                        @php
                                        $text = '';
                                        if ($charges->charge_type === 'percentage') {
                                            $text = $charges->charge_value . '%';
                                        } else {
                                            $text = '₹' . $charges->charge_value;
                                        }
                                        if ($charges->gst_applicable == 1) {
                                            $text .= ' + GST (' . $charges->gst_percentage . '%)';
                                        }
                                        @endphp
                                        {{ $text }}
                                    @else
                                        No additional charges
                                    @endif
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

            <!-- Charge Breakdown -->
            <div class="gateway-charge-breakdown" id="chargeBreakdown" style="display: none;">
                    <div class="charge-breakdown-item">
                        <span>Amount:</span>
                        <span id="breakdownBaseAmount">₹0.00</span>
                    </div>
                    <div class="charge-breakdown-item">
                        <span>Gateway Charge:</span>
                        <span id="breakdownGatewayCharge">₹0.00</span>
                    </div>
                    <div class="charge-breakdown-item">
                        <span>GST:</span>
                        <span id="breakdownGST">₹0.00</span>
                    </div>
                    <div class="charge-breakdown-item charge-total">
                        <span>Total to Pay:</span>
                        <span id="breakdownTotalAmount">₹0.00</span>
                    </div>
                </div>
            </div>

            <!-- Final Amount Display -->
            <div class="final-amount-display" id="finalAmountDisplay" style="display: none;">
                <div class="final-amount-label">Total Amount to Pay</div>
                <div class="final-amount-value" id="finalAmountValue">₹0.00</div>
                <small id="gatewaySelectedText" class="mt-2 d-block"></small>
            </div>

            <!-- Payment Options -->
            <div class="payment-options">
                <h6 class="mb-3">Choose Payment Method</h6>
                <div class="payment-options-buttons">
                    <button class="payment-gateway-btn" onclick="processPayment()" id="paymentGatewayBtn" disabled>
                        <i class="fas fa-credit-card"></i>
                        Select a Gateway First
                    </button>
                </div>
            </div>

            <div class="mt-3 text-center">
                <small class="text-muted">
                    <i class="fas fa-lock me-1"></i>Your payment information is secure and encrypted
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Multi Category Selection Modal -->
<div class="multi-category-modal" id="multiCategoryModal">
    <div class="multi-category-content">
        <div class="multi-category-header">
            <h5 class="mb-0">
                <i class="fas fa-shopping-cart me-2"></i>Pay Multiple Fees
            </h5>
            <button class="close-btn" onclick="closeMultiCategoryModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="multi-category-body">
            <!-- Multi Category Summary -->
            <div class="multi-category-summary">
                <div class="multi-category-item">
                    <span>Total Selected Items:</span>
                    <span id="multiCategorySelectedCount">0 Items</span>
                </div>
                <div class="multi-category-item">
                    <span>Amount:</span>
                    <span id="multiCategoryBaseAmount">₹0.00</span>
                </div>
                <div class="multi-category-item">
                    <span>Late Fees:</span>
                    <span id="multiCategoryLateFee">₹0.00</span>
                </div>
                <div class="multi-category-item">
                    <span>Discounts:</span>
                    <span id="multiCategoryDiscount">₹0.00</span>
                </div>
                <div class="multi-category-item multi-category-total">
                    <span>Total Payable:</span>
                    <span id="multiCategoryTotalAmount">₹0.00</span>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <button class="quick-action-btn primary" onclick="selectAllFees()">
                    <i class="fas fa-check-double"></i> Select All
                </button>
                <button class="quick-action-btn danger" onclick="clearAllSelections()">
                    <i class="fas fa-times"></i> Clear All
                </button>
            </div>

            <!-- Selected Items List -->
            <div class="selected-items-list" id="selectedItemsList">
                <div class="empty-selection" id="emptySelection">
                    <i class="fas fa-cart-plus"></i>
                    <h6>No Items Selected</h6>
                    <p>Select fees from different categories to pay them together</p>
                </div>
            </div>

            <!-- Payment Options -->
            <div class="payment-options">
                <h6 class="mb-3">Proceed to Payment</h6>
                <div class="payment-options-buttons">
                    <button class="payment-gateway-btn" onclick="processMultiCategoryPayment()"
                        id="multiCategoryPayButton" disabled>
                        <i class="fas fa-credit-card"></i>
                        Pay All Selected (₹<span id="multiCategoryPayAmount">0.00</span>)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div class="fee-detail-modal" id="receiptModal" style="z-index: 9999;">
    <div class="fee-detail-content" style="max-width: 900px;">
        <div class="fee-detail-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
            <h5 class="mb-0">
                <i class="fas fa-receipt me-2"></i>Fee Payment Receipt
            </h5>
            <button class="close-btn" onclick="closeReceiptModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="fee-detail-body receipt-container">
            <div class="receipt-preview" id="receiptPreview">
                <!-- Receipt will be generated here -->
            </div>
            <div class="receipt-actions">
                <button type="button" class="btn btn-outline-primary" onclick="printReceipt()">
                    <i class="bi bi-printer me-2"></i>Print Receipt
                </button>
                <button type="button" class="btn btn-primary" onclick="downloadReceiptAsPDF()">
                    <i class="bi bi-download me-2"></i>Download PDF
                </button>
                <button type="button" class="btn btn-success" onclick="downloadReceiptAsImage()">
                    <i class="bi bi-image me-2"></i>Download Image
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Include required libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
// Global Variables
let selectedInstallments = {
    course_fee: new Map(),
    hostel_fee: new Map(),
    transportation_fee: new Map(),
    registration_fee: new Map(),
    miscellaneous_fee: new Map(),
    custom_fees: new Map()
};

let currentPaymentData = null;
let selectedGateway = null;
let gatewayChargeAmount = 0;
let gstAmount = 0;
let finalPayableAmount = 0;
let currentReceiptData = null;

// Institute details from PHP
const instituteDetails = {
    name: "{{ $serviceInstitutedetails->name ?? 'Institute Name' }}",
    address: "{{ $serviceInstitutedetails->address_line_1 ?? '' }} {{ $serviceInstitutedetails->address_line_2 ?? '' }} {{ $serviceInstitutedetails->state ?? '' }} {{ $serviceInstitutedetails->city ?? '' }} {{ $serviceInstitutedetails->pincode ?? '' }}",
    phone: "{{ $serviceInstitutedetails->contact_number ?? '' }}",
    email: "{{ $serviceInstitutedetails->email ?? '' }}",
    website: "{{ $serviceInstitutedetails->website ?? '' }}"
};

// Student details from PHP
const studentDetails = {
    name: "{{ $studentDetail->first_name ?? '' }} {{ $studentDetail->last_name ?? '' }}",
    reg_no: "{{ $studentDetail->registration_number ?? '' }}",
    department: "{{ $studentDetail->academicTransportDetails->department ?? '' }}",
    course: "{{ $studentDetail->academicTransportDetails->course_type ?? '' }}",
    batch: "{{ $studentDetail->academicTransportDetails->batch ?? '' }}",
    academic_year: "{{ $studentDetail->academicTransportDetails->academic_year ?? '' }}",
    semester: "{{ $studentDetail->academicTransportDetails->semester ?? '' }}",
    section: "{{ $studentDetail->academicTransportDetails->section_id ?? '' }}"
};

// ==================== RECEIPT FUNCTIONS ====================

function viewReceipt(installmentId, category, referenceId) {
    // Fetch receipt data from backend
    $.ajax({
        url: "{{ route('student.receipt.fetch') }}",
        type: 'POST',
        data: {
            installment_id: installmentId,
            category: category,
            reference_id: referenceId,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            if (response.status) {
                generateReceiptHTML(response.data);
                $('#receiptModal').show();
            } else {
                alert('Receipt not found: ' + response.message);
            }
        },
        error: function(xhr) {
            console.error('Error fetching receipt:', xhr);
            alert('Error loading receipt. Please try again.');
        }
    });
}

function viewCustomFeeReceipt(customFeeId, referenceId) {
    $.ajax({
        url: "{{ route('student.custom.receipt.fetch') }}",
        type: 'POST',
        data: {
            custom_fee_id: customFeeId,
            reference_id: referenceId,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            if (response.status) {
                generateReceiptHTML(response.data);
                $('#receiptModal').show();
            } else {
                alert('Receipt not found: ' + response.message);
            }
        },
        error: function(xhr) {
            console.error('Error fetching receipt:', xhr);
            alert('Error loading receipt. Please try again.');
        }
    });
}

function generateReceiptNumber() {
    const date = new Date();
    const year = date.getFullYear().toString().substr(-2);
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
    return `RCPT${year}${month}${day}${random}`;
}

function generateReceiptHTML(payment) {
    const receiptPreview = document.getElementById('receiptPreview');
    currentReceiptData = payment;

    const formatCurrency = (amount) => {
        return '₹' + parseFloat(amount || 0).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    };

    const payDate = payment.pay_date ? new Date(payment.pay_date).toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    }) : 'N/A';

    let payTime = '';
    if (payment.pay_date && payment.pay_date.includes(' ')) {
        const dateObj = new Date(payment.pay_date);
        payTime = dateObj.toLocaleTimeString('en-IN', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    }

    const dueDate = payment.due_date ? new Date(payment.due_date).toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    }) : 'N/A';

    const feeAmount = parseFloat(payment.fee_amount || 0);
    const lateFee = parseFloat(payment.late_fee_amount || 0);
    const discount = parseFloat(payment.discount_amount || 0);
    const payable = parseFloat(payment.payable_amount || (feeAmount + lateFee - discount));

    const paymentStatusHTML = payment.payment_status === 'paid' ?
        '<span style="color: #28a745; font-weight: bold;"><i class="fas fa-check-circle"></i> PAID</span>' :
        '<span style="color: #dc3545; font-weight: bold;"><i class="fas fa-times-circle"></i> NOT PAID</span>';

    const paymentMethodHTML = {
        online: '<span style="color: #28a745;"><i class="fas fa-credit-card"></i> Online Payment</span>',
        cash: '<span style="color: #007bff;"><i class="fas fa-money-bill-wave"></i> Cash Payment</span>',
        emi: '<span style="color: #ffc107;"><i class="fas fa-calendar-check"></i> EMI Payment</span>',
        bank: '<span style="color: #6f42c1;"><i class="fas fa-university"></i> Bank Transfer</span>'
    } [payment.payment_type] || '<span>N/A</span>';

    const receiptHTML = `
        <div class="receipt-paper" id="receiptContent">
            <div class="watermark">PAID</div>
            <div class="receipt-header">
                <div class="institute-name">${instituteDetails.name}</div>
                <div class="receipt-title">FEE PAYMENT RECEIPT</div>
                <div class="institute-address">${instituteDetails.address}</div>
                <div class="institute-contact">Phone: ${instituteDetails.phone} | Email: ${instituteDetails.email}</div>
            </div>
            
            <div class="student-details-section" style="display: flex; gap: 20px; margin-bottom: 20px;">
                <div style="flex:1; border:1px solid #ddd; padding:15px; background:#f8f9fa;">
                    <div style="font-weight:bold; margin-bottom:10px;"><i class="fas fa-user-graduate"></i> STUDENT INFORMATION</div>
                    <div><span style="font-weight:500;">Name:</span> ${studentDetails.name}</div>
                    <div><span style="font-weight:500;">Reg No:</span> ${studentDetails.reg_no}</div>
                </div>
                <div style="flex:1; border:1px solid #ddd; padding:15px; background:#f8f9fa;">
                    <div style="font-weight:bold; margin-bottom:10px;"><i class="fas fa-receipt"></i> RECEIPT INFORMATION</div>
                    <div><span style="font-weight:500;">Receipt No:</span> ${generateReceiptNumber()}</div>
                    <div><span style="font-weight:500;">Date:</span> ${payDate} ${payTime ? `at ${payTime}` : ''}</div>
                </div>
            </div>
            
            <div style="border:1px solid #ddd; padding:15px; background:#f8f9fa; margin-bottom:20px;">
                <div style="font-weight:bold; margin-bottom:10px;"><i class="fas fa-graduation-cap"></i> ACADEMIC INFORMATION</div>
                <div style="display: grid; grid-template-columns: repeat(3,1fr); gap:10px;">
                    <div><span style="font-weight:500;">Department:</span> ${studentDetails.department}</div>
                    <div><span style="font-weight:500;">Course:</span> ${studentDetails.course}</div>
                    <div><span style="font-weight:500;">Batch:</span> ${studentDetails.batch}</div>
                    <div><span style="font-weight:500;">Academic Year:</span> ${studentDetails.academic_year}</div>
                    
                    <div><span style="font-weight:500;">Section:</span> ${studentDetails.section}</div>
                </div>
            </div>
            
            <div style="display: flex; gap: 20px; margin-bottom:20px;">
                <div style="flex:1; border:1px solid #ddd; padding:15px; background:#f8f9fa;">
                    <div style="font-weight:bold; margin-bottom:10px;"><i class="fas fa-calculator"></i> FEE DETAILS</div>
                    <div><span style="font-weight:500;">Fee Type:</span> ${payment.fee_type || 'Course Fee'}</div>
                    <div><span style="font-weight:500;">Due Date:</span> ${dueDate}</div>
                    <div><span style="font-weight:500;">Payment Date:</span> ${payDate} ${payTime ? `<br><small>at ${payTime}</small>` : ''}</div>
                    <div style="margin-top:15px;">
                        <div style="display:flex; justify-content:space-between;"><span>Fee Amount:</span> ${formatCurrency(feeAmount)}</div>
                        ${lateFee > 0 ? `<div style="display:flex; justify-content:space-between; color:#dc3545;"><span>Late Fee:</span> +${formatCurrency(lateFee)}</div>` : ''}
                        ${discount > 0 ? `<div style="display:flex; justify-content:space-between; color:#28a745;"><span>Discount:</span> -${formatCurrency(discount)}</div>` : ''}
                        <div style="display:flex; justify-content:space-between; font-weight:bold; border-top:2px solid #333; margin-top:8px; padding-top:8px;">
                            <span>TOTAL PAYABLE:</span> <span>${formatCurrency(payable)}</span>
                        </div>
                    </div>
                </div>
                <div style="flex:1; border:1px solid #ddd; padding:15px; background:#f8f9fa;">
                    <div style="font-weight:bold; margin-bottom:10px;"><i class="fas fa-credit-card"></i> PAYMENT INFORMATION</div>
                    <div><span style="font-weight:500;">Status:</span> ${paymentStatusHTML}</div>
                    <div><span style="font-weight:500;">Method:</span> ${paymentMethodHTML}</div>
                    ${payment.transaction_id ? `
                        <div><span style="font-weight:500;">Transaction ID:</span> <code>${payment.transaction_id}</code></div>
                    ` : ''}
                    ${payment.reference_id ? `
                        <div><span style="font-weight:500;">Reference No:</span> <code>${payment.reference_id}</code></div>
                    ` : ''}
                </div>
            </div>
            
            <div class="receipt-footer">
                <div style="background:#f1f8ff; border:1px solid #d1e7ff; padding:12px; margin-bottom:15px;">
                    <div style="font-weight:bold;">Terms & Conditions:</div>
                    <ul style="font-size:11px; margin-bottom:0;">
                        <li>This is a computer generated receipt and does not require signature.</li>
                        <li>Payment once made is non-refundable.</li>
                        <li>Please keep this receipt for future reference.</li>
                        <li>For any queries, contact accounts department within 7 days.</li>
                    </ul>
                </div>
              
            </div>
        </div>
    `;

    receiptPreview.innerHTML = receiptHTML;
}

function closeReceiptModal() {
    $('#receiptModal').hide();
    currentReceiptData = null;
}

function printReceipt() {
    window.print();
}

async function downloadReceiptAsPDF() {
    if (!currentReceiptData) return;
    try {
        const downloadBtn = document.querySelector('#receiptModal .btn-primary');
        const originalText = downloadBtn.innerHTML;
        downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating PDF...';
        downloadBtn.disabled = true;

        const receiptElement = document.getElementById('receiptContent');
        const canvas = await html2canvas(receiptElement, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff'
        });
        const imgData = canvas.toDataURL('image/png');

        const {
            jsPDF
        } = window.jspdf;
        const pdf = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'a4'
        });
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const imgHeight = (canvas.height * pdfWidth) / canvas.width;

        pdf.addImage(imgData, 'PNG', 10, 10, pdfWidth - 20, imgHeight);
        const fileName = `Fee_Receipt_${currentReceiptData.transaction_id || 'receipt'}.pdf`;
        pdf.save(fileName);

        showDownloadSuccess('PDF');
    } catch (error) {
        console.error(error);
        alert('Error generating PDF. Please try again.');
    } finally {
        const downloadBtn = document.querySelector('#receiptModal .btn-primary');
        downloadBtn.innerHTML = originalText;
        downloadBtn.disabled = false;
    }
}

async function downloadReceiptAsImage() {
    if (!currentReceiptData) return;
    try {
        const downloadBtn = document.querySelector('#receiptModal .btn-success');
        const originalText = downloadBtn.innerHTML;
        downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating Image...';
        downloadBtn.disabled = true;

        const receiptElement = document.getElementById('receiptContent');
        const canvas = await html2canvas(receiptElement, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff'
        });
        const imgData = canvas.toDataURL('image/png');

        const link = document.createElement('a');
        link.download = `Fee_Receipt_${currentReceiptData.transaction_id || 'receipt'}.png`;
        link.href = imgData;
        link.click();

        showDownloadSuccess('Image');
    } catch (error) {
        console.error(error);
        alert('Error generating image. Please try again.');
    } finally {
        const downloadBtn = document.querySelector('#receiptModal .btn-success');
        downloadBtn.innerHTML = originalText;
        downloadBtn.disabled = false;
    }
}

function showDownloadSuccess(fileType) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            <div>
                <strong>${fileType} Downloaded Successfully!</strong>
                <div class="small">Receipt for ${studentDetails.name}</div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.body.appendChild(alertDiv);
    setTimeout(() => alertDiv.remove(), 5000);
}

// ==================== TAB MANAGEMENT ====================

function switchCategoryTab(category) {
    $('.category-tab-link').removeClass('active');
    $(`.category-tab-link[onclick*="${category}"]`).addClass('active');
    $('.category-tab-pane').removeClass('active');
    $(`#${category}TabContent`).addClass('active');
}

// ==================== CATEGORY FEE SELECTION FUNCTIONS ====================

function toggleCategoryInstallmentSelection(category, index, totalAmount, baseAmount, lateFeeAmount, isOverdue, itemId,
    isSelected = null) {
    const row = $(`#${category}-installment-${index}`);
    const checkbox = row.find('.category-installment-checkbox');

    if (checkbox.prop('disabled')) return;

    let shouldBeSelected;
    if (isSelected === null) {
        shouldBeSelected = !checkbox.prop('checked');
    } else {
        shouldBeSelected = isSelected;
    }

    checkbox.prop('checked', shouldBeSelected);

    if (shouldBeSelected) {
        let installmentName = '';
        const nameElement = row.find('td:nth-child(2) .installment-info strong');
        if (nameElement.length) {
            installmentName = nameElement.text();
        } else {
            installmentName = `${category} Installment ${index + 1}`;
        }

        let paymentStatus = '';
        const statusElement = row.find('.installment-status-badge');
        if (statusElement.length) {
            paymentStatus = statusElement.text().trim();
        }

        selectedInstallments[category].set(index, {
            totalAmount: totalAmount,
            baseAmount: baseAmount,
            lateFeeAmount: lateFeeAmount,
            isOverdue: isOverdue,
            index: index,
            itemId: itemId,
            installmentName: installmentName,
            paymentStatus: paymentStatus
        });
        row.addClass('selected');
    } else {
        selectedInstallments[category].delete(index);
        row.removeClass('selected');
    }

    updateCategorySelectionSummary(category);
    updateSelectAllCategoryCheckbox(category);
    updateMultiCategorySummary();
}

function toggleCustomFeeSelection(index, totalAmount, baseAmount, lateFeeAmount, isOverdue, itemId, isSelected = null) {
    const row = $(`#custom-fee-${index}`);
    const checkbox = row.find('.custom-fee-checkbox');

    if (checkbox.prop('disabled')) return;

    let shouldBeSelected;
    if (isSelected === null) {
        shouldBeSelected = !checkbox.prop('checked');
    } else {
        shouldBeSelected = isSelected;
    }

    checkbox.prop('checked', shouldBeSelected);

    if (shouldBeSelected) {
        let installmentName = '';
        const nameElement = row.find('td:nth-child(2) strong');
        if (nameElement.length) {
            installmentName = nameElement.text();
        } else {
            installmentName = `Custom Fee ${index + 1}`;
        }

        let paymentStatus = '';
        const statusElement = row.find('.installment-status-badge');
        if (statusElement.length) {
            paymentStatus = statusElement.text().trim();
        }

        selectedInstallments.custom_fees.set(index, {
            totalAmount: totalAmount,
            baseAmount: baseAmount,
            lateFeeAmount: lateFeeAmount,
            isOverdue: isOverdue,
            index: index,
            itemId: itemId,
            installmentName: installmentName,
            paymentStatus: paymentStatus
        });
        row.addClass('selected');
    } else {
        selectedInstallments.custom_fees.delete(index);
        row.removeClass('selected');
    }

    updateCustomFeesSelectionSummary();
    updateMultiCategorySummary();
}

function updateCustomFeesSelectionSummary() {
    let baseTotal = 0;
    let lateFeeTotal = 0;
    let discountTotal = 0;
    let totalPayable = 0;

    selectedInstallments.custom_fees.forEach((installment) => {
        baseTotal += installment.baseAmount;
        lateFeeTotal += installment.lateFeeAmount;

        const discount = (installment.baseAmount + installment.lateFeeAmount) - installment.totalAmount;
        discountTotal += discount > 0 ? discount : 0;

        totalPayable += installment.totalAmount;
    });

    $('#customFeesSelectedCount').text(selectedInstallments.custom_fees.size);
    $('#customFeesBaseAmountTotal').text('₹' + baseTotal.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#customFeesLateFeeTotal').text('₹' + lateFeeTotal.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#customFeesDiscountTotal').text('₹' + discountTotal.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#customFeesTotalPayable').text('₹' + totalPayable.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#customFeesPayButtonAmount').text(totalPayable.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));

    const actionsDiv = $('#customFeesActions');
    if (selectedInstallments.custom_fees.size > 0) {
        actionsDiv.addClass('show');
        $('#customFeesPayButton').prop('disabled', false);
    } else {
        actionsDiv.removeClass('show');
        $('#customFeesPayButton').prop('disabled', true);
    }
}

function toggleSelectAllCategory(category) {
    const isChecked = $(`#selectAll${category.charAt(0).toUpperCase() + category.slice(1)}`).prop('checked');
    const checkboxes = $(`.category-installment-checkbox[data-category="${category}"]:not(:disabled)`);

    checkboxes.each(function() {
        const checkbox = $(this);
        const index = parseInt(checkbox.data('index'));
        const itemId = checkbox.data('fee-id');
        const row = $(`#${category}-installment-${index}`);

        const baseAmount = parseFloat(checkbox.data('base'));
        const lateFeeAmount = parseFloat(checkbox.data('late'));
        const totalAmount = parseFloat(checkbox.data('total'));
        const isOverdue = checkbox.data('overdue') === 'true';

        toggleCategoryInstallmentSelection(category, index, totalAmount, baseAmount, lateFeeAmount, isOverdue,
            itemId, isChecked);
    });
}

function updateSelectAllCategoryCheckbox(category) {
    const totalCheckboxes = $(`.category-installment-checkbox[data-category="${category}"]:not(:disabled)`).length;
    const checkedCheckboxes = $(`.category-installment-checkbox[data-category="${category}"]:checked`).length;
    const checkboxId = `selectAll${category.charAt(0).toUpperCase() + category.slice(1)}`;

    $(`#${checkboxId}`).prop('checked', totalCheckboxes > 0 && checkedCheckboxes === totalCheckboxes);
    $(`#${checkboxId}`).prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
}

function updateCategorySelectionSummary(category) {
    if (category === 'custom_fees') {
        updateCustomFeesSelectionSummary();
        return;
    }

    let baseTotal = 0;
    let lateFeeTotal = 0;
    let discountTotal = 0;
    let totalPayable = 0;

    selectedInstallments[category].forEach((installment) => {
        baseTotal += installment.baseAmount;
        lateFeeTotal += installment.lateFeeAmount;

        const discount = (installment.baseAmount + installment.lateFeeAmount) - installment.totalAmount;
        discountTotal += discount > 0 ? discount : 0;

        totalPayable += installment.totalAmount;
    });

    $(`#${category}SelectedCount`).text(selectedInstallments[category].size);
    $(`#${category}BaseAmountTotal`).text('₹' + baseTotal.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $(`#${category}LateFeeTotal`).text('₹' + lateFeeTotal.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $(`#${category}DiscountTotal`).text('₹' + discountTotal.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $(`#${category}TotalPayable`).text('₹' + totalPayable.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $(`#${category}PayButtonAmount`).text(totalPayable.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));

    const actionsDiv = $(`#${category}Actions`);
    if (selectedInstallments[category].size > 0) {
        actionsDiv.addClass('show');
        $(`#${category}PayButton`).prop('disabled', false);
    } else {
        actionsDiv.removeClass('show');
        $(`#${category}PayButton`).prop('disabled', true);
    }
}

// ==================== MULTI CATEGORY FUNCTIONS ====================

function openMultiCategoryModal() {
    updateMultiCategorySummary();
    $('#multiCategoryModal').show();
}

function closeMultiCategoryModal() {
    $('#multiCategoryModal').hide();
}

function updateMultiCategorySummary() {
    let totalItems = 0;
    let baseTotal = 0;
    let lateFeeTotal = 0;
    let discountTotal = 0;
    let totalPayable = 0;

    Object.keys(selectedInstallments).forEach(category => {
        const count = selectedInstallments[category].size;
        totalItems += count;

        selectedInstallments[category].forEach(installment => {
            baseTotal += installment.baseAmount;
            lateFeeTotal += installment.lateFeeAmount;

            const discount = (installment.baseAmount + installment.lateFeeAmount) - installment
                .totalAmount;
            discountTotal += discount > 0 ? discount : 0;

            totalPayable += installment.totalAmount;
        });
    });

    $('#totalSelectedItems').text(`${totalItems} Item${totalItems !== 1 ? 's' : ''}`);
    $('#multiCategorySelectedCount').text(`${totalItems} Item${totalItems !== 1 ? 's' : ''}`);
    $('#multiCategoryBaseAmount').text('₹' + baseTotal.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#multiCategoryLateFee').text('₹' + lateFeeTotal.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#multiCategoryDiscount').text('₹' + discountTotal.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#multiCategoryTotalAmount').text('₹' + totalPayable.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#multiCategoryPayAmount').text(totalPayable.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));

    $('#multiCategoryPayButton').prop('disabled', totalItems === 0);
    updateSelectedItemsList();
}

function updateSelectedItemsList() {
    const container = $('#selectedItemsList');
    const emptySelection = $('#emptySelection');

    let totalItems = 0;
    Object.keys(selectedInstallments).forEach(category => {
        totalItems += selectedInstallments[category].size;
    });

    if (totalItems === 0) {
        emptySelection.show();
        container.find('.selected-item').remove();
        return;
    }

    emptySelection.hide();
    container.find('.selected-item').remove();

    Object.keys(selectedInstallments).forEach(category => {
        selectedInstallments[category].forEach((installment, index) => {
            const categoryName = getCategoryName(category);
            const item = `
                <div class="selected-item" data-category="${category}" data-index="${index}">
                    <div>
                        <strong>${installment.installmentName}</strong>
                        <br>
                        <small class="text-muted">${categoryName} - ₹${installment.totalAmount.toLocaleString('en-IN', {minimumFractionDigits: 2})}</small>
                    </div>
                    <button class="remove-item-btn" onclick="removeSelectedItem('${category}', ${index})">
                        <i class="fas fa-times"></i> Remove
                    </button>
                </div>
            `;
            container.append(item);
        });
    });
}

function getCategoryName(categoryKey) {
    const names = {
        'course_fee': 'Course Fee',
        'hostel_fee': 'Hostel Fee',
        'transportation_fee': 'Transport Fee',
        'registration_fee': 'Registration Fee',
        'miscellaneous_fee': 'Miscellaneous Fee',
        'custom_fees': 'Custom Fees'
    };
    return names[categoryKey] || categoryKey;
}

function removeSelectedItem(category, index) {
    if (category === 'custom_fees') {
        $(`#custom-fee-${index} .custom-fee-checkbox`).prop('checked', false);
        $(`#custom-fee-${index}`).removeClass('selected');
        selectedInstallments.custom_fees.delete(index);
        updateCustomFeesSelectionSummary();
    } else {
        $(`#${category}-installment-${index} .category-installment-checkbox`).prop('checked', false);
        $(`#${category}-installment-${index}`).removeClass('selected');
        selectedInstallments[category].delete(index);
        updateCategorySelectionSummary(category);
    }
    updateMultiCategorySummary();
}

function selectAllFees() {
    Object.keys(selectedInstallments).forEach(category => {
        if (category === 'custom_fees') {
            $('.custom-fee-checkbox:not(:disabled)').each(function() {
                const checkbox = $(this);
                const index = parseInt(checkbox.data('index'));
                const itemId = checkbox.data('fee-id');
                const totalAmount = parseFloat(checkbox.data('total'));
                const baseAmount = parseFloat(checkbox.data('base'));
                const lateFeeAmount = parseFloat(checkbox.data('late'));
                const isOverdue = checkbox.data('overdue') === 'true';

                toggleCustomFeeSelection(index, totalAmount, baseAmount, lateFeeAmount, isOverdue,
                    itemId, true);
            });
        } else {
            $(`#selectAll${category.charAt(0).toUpperCase() + category.slice(1)}`).prop('checked', true);
            toggleSelectAllCategory(category);
        }
    });
}

function clearAllSelections() {
    Object.keys(selectedInstallments).forEach(category => {
        selectedInstallments[category].clear();
        $(`[id^="${category}-installment-"]`).removeClass('selected').find('.category-installment-checkbox')
            .prop('checked', false);
        $(`#selectAll${category.charAt(0).toUpperCase() + category.slice(1)}`).prop('checked', false);
    });

    $('[id^="custom-fee-"]').removeClass('selected').find('.custom-fee-checkbox').prop('checked', false);
    $('.category-fee-actions').removeClass('show');

    updateMultiCategorySummary();
}

// ==================== PAYMENT FUNCTIONS ====================

function proceedToCategoryPayment(category, categoryTitle) {
    if (selectedInstallments[category].size === 0) {
        alert('Please select at least one installment to pay.');
        return;
    }

    const installmentData = Array.from(selectedInstallments[category].values());
    const totalAmount = installmentData.reduce((sum, inst) => sum + inst.totalAmount, 0);
    const baseTotal = installmentData.reduce((sum, inst) => sum + inst.baseAmount, 0);
    const lateFeeTotal = installmentData.reduce((sum, inst) => sum + inst.lateFeeAmount, 0);
    const discountTotal = installmentData.reduce((sum, inst) => {
        const discount = (inst.baseAmount + inst.lateFeeAmount) - inst.totalAmount;
        return sum + (discount > 0 ? discount : 0);
    }, 0);

    currentPaymentData = {
        category: categoryTitle,
        categoryKey: category,
        totalAmount: totalAmount,
        installmentCount: selectedInstallments[category].size,
        installments: installmentData,
        baseTotal: baseTotal,
        lateFeeTotal: lateFeeTotal,
        discountTotal: discountTotal
    };

    $('#modalSelectedCount').text(currentPaymentData.installmentCount);
    $('#modalBaseAmountTotal').text('₹' + currentPaymentData.baseTotal.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#modalLateFeeTotal').text('₹' + currentPaymentData.lateFeeTotal.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#modalDiscountTotal').text('₹' + currentPaymentData.discountTotal.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#modalTotalPayable').text('₹' + currentPaymentData.totalAmount.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#paymentModalTitle').text(`Payment Confirmation - ${currentPaymentData.category}`);

    $('#gatewayChargeSummary').show();
    $('#breakdownBaseAmount').text('₹' + currentPaymentData.totalAmount.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));

    $('.gateway-radio').prop('checked', false);
    selectedGateway = null;
    $('#chargeBreakdown').hide();
    $('#finalAmountDisplay').hide();
    $('#paymentGatewayBtn').prop('disabled', true).html('<i class="fas fa-credit-card"></i> Select a Gateway First');

    $('#paymentModal').show();
}

function proceedToCustomFeesPayment() {
    if (selectedInstallments.custom_fees.size === 0) {
        alert('Please select at least one custom fee item to pay.');
        return;
    }

    const installmentData = Array.from(selectedInstallments.custom_fees.values());
    const totalAmount = installmentData.reduce((sum, inst) => sum + inst.totalAmount, 0);
    const baseTotal = installmentData.reduce((sum, inst) => sum + inst.baseAmount, 0);
    const lateFeeTotal = installmentData.reduce((sum, inst) => sum + inst.lateFeeAmount, 0);
    const discountTotal = installmentData.reduce((sum, inst) => {
        const discount = (inst.baseAmount + inst.lateFeeAmount) - inst.totalAmount;
        return sum + (discount > 0 ? discount : 0);
    }, 0);

    currentPaymentData = {
        category: 'Custom Fees',
        categoryKey: 'custom_fees',
        totalAmount: totalAmount,
        installmentCount: selectedInstallments.custom_fees.size,
        installments: installmentData,
        baseTotal: baseTotal,
        lateFeeTotal: lateFeeTotal,
        discountTotal: discountTotal
    };

    $('#modalSelectedCount').text(currentPaymentData.installmentCount);
    $('#modalBaseAmountTotal').text('₹' + currentPaymentData.baseTotal.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#modalLateFeeTotal').text('₹' + currentPaymentData.lateFeeTotal.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#modalDiscountTotal').text('₹' + currentPaymentData.discountTotal.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#modalTotalPayable').text('₹' + currentPaymentData.totalAmount.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#paymentModalTitle').text(`Payment Confirmation - ${currentPaymentData.category}`);

    $('#gatewayChargeSummary').show();
    $('#breakdownBaseAmount').text('₹' + currentPaymentData.totalAmount.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));

    $('.gateway-radio').prop('checked', false);
    selectedGateway = null;
    $('#chargeBreakdown').hide();
    $('#finalAmountDisplay').hide();
    $('#paymentGatewayBtn').prop('disabled', true).html('<i class="fas fa-credit-card"></i> Select a Gateway First');

    $('#paymentModal').show();
}

function processMultiCategoryPayment() {
    let totalItems = 0;
    Object.keys(selectedInstallments).forEach(category => {
        totalItems += selectedInstallments[category].size;
    });

    if (totalItems === 0) {
        alert('Please select at least one fee item to pay.');
        return;
    }

    const allInstallments = [];
    let totalAmount = 0;
    let baseTotal = 0;
    let lateFeeTotal = 0;
    let discountTotal = 0;

    Object.keys(selectedInstallments).forEach(category => {
        selectedInstallments[category].forEach(installment => {
            allInstallments.push({
                ...installment,
                category: category,
                categoryTitle: getCategoryName(category)
            });
            baseTotal += installment.baseAmount;
            lateFeeTotal += installment.lateFeeAmount;

            const discount = (installment.baseAmount + installment.lateFeeAmount) - installment
                .totalAmount;
            discountTotal += discount > 0 ? discount : 0;

            totalAmount += installment.totalAmount;
        });
    });

    currentPaymentData = {
        category: 'Multiple Fees',
        categoryKey: 'multiple',
        totalAmount: totalAmount,
        installmentCount: totalItems,
        installments: allInstallments,
        baseTotal: baseTotal,
        lateFeeTotal: lateFeeTotal,
        discountTotal: discountTotal
    };

    $('#modalSelectedCount').text(currentPaymentData.installmentCount);
    $('#modalBaseAmountTotal').text('₹' + currentPaymentData.baseTotal.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#modalLateFeeTotal').text('₹' + currentPaymentData.lateFeeTotal.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#modalDiscountTotal').text('₹' + currentPaymentData.discountTotal.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#modalTotalPayable').text('₹' + currentPaymentData.totalAmount.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#paymentModalTitle').text('Payment Confirmation - Multiple Fees');

    $('#gatewayChargeSummary').show();
    $('#breakdownBaseAmount').text('₹' + currentPaymentData.totalAmount.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));

    $('.gateway-radio').prop('checked', false);
    selectedGateway = null;
    $('#chargeBreakdown').hide();
    $('#finalAmountDisplay').hide();
    $('#paymentGatewayBtn').prop('disabled', true).html('<i class="fas fa-credit-card"></i> Select a Gateway First');

    closeMultiCategoryModal();
    $('#paymentModal').show();
}

function calculateGatewayCharges(selectedElement) {
    const gatewayId = selectedElement.value;
    const totalAmount = currentPaymentData.totalAmount;

    selectedGateway = {
        id: gatewayId,
        gateway_name: selectedElement.dataset.gatewayName,
        charge_type: selectedElement.dataset.chargeType,
        charge_value: parseFloat(selectedElement.dataset.chargeValue),
        gst_applicable: parseInt(selectedElement.dataset.gstApplicable),
        gst_percentage: parseFloat(selectedElement.dataset.gstPercentage),
        min_charges: parseFloat(selectedElement.dataset.chargesMinon),
        max_charges: parseFloat(selectedElement.dataset.chargesMaxon),
    };

    if (selectedGateway.charge_type === 'percentage') {
        gatewayChargeAmount = (totalAmount * selectedGateway.charge_value) / 100;
    } else {
        gatewayChargeAmount = selectedGateway.charge_value;
    }

    if (selectedGateway.gst_applicable === 1) {
        gstAmount = (gatewayChargeAmount * selectedGateway.gst_percentage) / 100;
    } else {
        gstAmount = 0;
    }

    if (totalAmount < selectedGateway.min_charges) {
        gatewayChargeAmount = 0.00;
        gstAmount = 0.00;
    }

    finalPayableAmount = totalAmount + gatewayChargeAmount + gstAmount;

    $('#breakdownGatewayCharge').text('₹' + gatewayChargeAmount.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#breakdownGST').text('₹' + gstAmount.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#breakdownTotalAmount').text('₹' + finalPayableAmount.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#finalAmountValue').text('₹' + finalPayableAmount.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    }));
    $('#gatewaySelectedText').html(
        `Using <strong>${selectedGateway.gateway_name}</strong> ` +
        `(${selectedGateway.charge_type === 'percentage' ? selectedGateway.charge_value + '%' : '₹' + selectedGateway.charge_value} charge)`
    );

    $('#chargeBreakdown').show();
    $('#finalAmountDisplay').show();
    $('#paymentGatewayBtn').prop('disabled', false).html(
        `<i class="fas fa-credit-card"></i> ` +
        `Pay ₹${finalPayableAmount.toLocaleString('en-IN', {minimumFractionDigits: 2})} ` +
        `via ${selectedGateway.gateway_name}`
    );
}

function processPayment() {
    if (!selectedGateway) {
        alert('Please select a payment gateway.');
        return;
    }

    const payButton = $('#paymentGatewayBtn');
    const originalText = payButton.html();
    payButton.html('<i class="fas fa-spinner fa-spin"></i> Processing...');
    payButton.prop('disabled', true);

    const installmentDataForBackend = currentPaymentData.installments.map(installment => ({
        installment_name: installment.installmentName,
        base_amount: installment.baseAmount,
        late_fee_amount: installment.lateFeeAmount,
        total_amount: installment.totalAmount,
        is_overdue: installment.isOverdue,
        category: installment.category || currentPaymentData.categoryKey,
        item_id: installment.itemId,
        payment_status: installment.paymentStatus
    }));

    const studentHashId = "{{ $studentDetail->student_hash_id ?? '' }}";

    fetch("{{ route('payment.Courselink.create') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                amount: finalPayableAmount,
                student_hash_id: studentHashId,
                payment_type: currentPaymentData.categoryKey === 'multiple' ? 'multiple-fees' :
                    'category-fee',
                installment_data: installmentDataForBackend,
                category: currentPaymentData.category,
                fee_key: currentPaymentData.categoryKey,
                installment_count: currentPaymentData.installmentCount,
                gateway_id: selectedGateway.id,
                gateway_name: selectedGateway.gateway_name,
                gateway_charge: gatewayChargeAmount,
                gst_amount: gstAmount,
                base_amount: currentPaymentData.totalAmount,
                late_fee_amount: currentPaymentData.lateFeeTotal,
                discount_amount: currentPaymentData.discountTotal
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.payment_link;
            } else {
                alert('Payment link creation failed: ' + data.message);
                payButton.html(originalText);
                payButton.prop('disabled', false);
            }
        })
        .catch(err => {
            alert('Error creating payment link: ' + err.message);
            payButton.html(originalText);
            payButton.prop('disabled', false);
        });
}

function closePaymentModal() {
    $('#paymentModal').hide();
    $('#gatewayChargeSummary').hide();
    $('#chargeBreakdown').hide();
    $('#finalAmountDisplay').hide();
    currentPaymentData = null;
    selectedGateway = null;
    gatewayChargeAmount = 0;
    gstAmount = 0;
    finalPayableAmount = 0;
}

// Close modals when clicking outside
window.onclick = function(event) {
    const multiModal = document.getElementById('multiCategoryModal');
    const paymentModal = document.getElementById('paymentModal');
    const receiptModal = document.getElementById('receiptModal');

    if (event.target === multiModal) {
        closeMultiCategoryModal();
    }
    if (event.target === paymentModal) {
        closePaymentModal();
    }
    if (event.target === receiptModal) {
        closeReceiptModal();
    }
}

// Initialize on page load
$(document).ready(function() {
    console.log('Student fee structure loaded');
    Object.keys(selectedInstallments).forEach(category => {
        if (category === 'custom_fees') {
            updateCustomFeesSelectionSummary();
        } else {
            updateCategorySelectionSummary(category);
        }
    });
    updateMultiCategorySummary();
});
</script>
@endsection
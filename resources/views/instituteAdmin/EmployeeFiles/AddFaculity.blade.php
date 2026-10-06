@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Employee Management</title>
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --success-gradient: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
    --shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.error {
    color: red;
    font-size: 12px;
    margin-top: 4px;
}

.mainDiv {
    position: relative;
}

.childContent {
    position: absolute;
    color: #000;
    top: 65%;
    left: 7%;
}

.child1 {
    background: linear-gradient(90deg, #4B3F72, #F6C667);
    padding: 40px 20px;
    border-radius: 8px 8px 0 0;
    text-align: center;
    color: white;
}

.profile-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-top: -50px;
    border: 5px solid white;
}

.add-btn {
    padding: 10px 20px;
    background: var(--primary-gradient);
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 12px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

.add-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
}

/* Overlay */
.overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.3);
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease;
    z-index: 100;
}

.overlay.show {
    opacity: 1;
    visibility: visible;
}

/* Side Panel */
.side-panel {
    position: fixed;
    top: 0;
    right: -100%;
    width: 100%;
    max-width: 800px;
    height: 100vh;
    background-color: #F8FAFF;
    box-shadow: -2px 0 8px rgba(0, 0, 0, 0.2);
    transition: right 0.4s ease;
    z-index: 200;
    display: flex;
    flex-direction: column;
}

.side-panel.open {
    right: 0;
}

.panel-header {
    background: var(--primary-gradient);
    padding: 15px;
    font-size: 18px;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
    color: white;
}

.close-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.close-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

/* Panel Content */
.panel-content {
    flex: 1;
    overflow-y: auto;
    padding-bottom: 100px;
    max-height: calc(100vh - 60px);
    box-sizing: border-box;
}

/* Accordion */
#sidePanel .accordion {
    background-color: #fff;
    cursor: pointer;
    padding: 15px;
    width: 100%;
    border: none;
    border-bottom: 1px solid #ccc;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.accordion.active {
    background-color: #E8F4FF;
}

.accordion:after {
    content: '\25BC';
    transition: transform 0.3s ease;
}

.accordion.active:after {
    transform: rotate(180deg);
}

.panel {
    max-height: 0;
    overflow: hidden;
    background-color: #fff;
    transition: max-height 0.3s ease;
    padding: 0 15px;
}

.panel.open {
    max-height: none;
    padding: 15px;
}

/* Form Styling */
form {
    margin: 10px 0;
}

label {
    display: block;
    margin-top: 8px;
    font-size: 14px;
}

input,
select,
textarea {
    width: 100%;
    padding: 8px;
    margin-top: 4px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    box-sizing: border-box;
}

.gender-options {
    display: flex;
    gap: 15px;
    margin-top: 5px;
}

.gender-options label {
    display: flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    font-size: 14px;
}

.gender-options input[type="radio"] {
    appearance: none;
    width: 16px;
    height: 16px;
    border: 2px solid #007BFF;
    border-radius: 50%;
    outline: none;
    cursor: pointer;
    position: relative;
}

.gender-options input[type="radio"]:checked::before {
    content: '';
    position: absolute;
    top: 3px;
    left: 3px;
    width: 8px;
    height: 8px;
    background-color: #007BFF;
    border-radius: 50%;
}

.phone-input {
    display: flex;
    align-items: center;
}

.phone-input span {
    background: #eee;
    padding: 8px;
    border: 1px solid #ccc;
    border-right: none;
    border-radius: 4px 0 0 4px;
}

.phone-input input {
    border-radius: 0 4px 4px 0;
    border-left: none;
    flex: 1;
}

/* Footer buttons */
.save-close {
    padding: 15px;
    background-color: #E8F4FF;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    position: absolute;
    bottom: 0;
    width: 100%;
    box-sizing: border-box;
    z-index: 11;
}

.save-close button {
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.save-btn {
    background: var(--primary-gradient);
    color: white;
}

.close-btn-footer {
    background-color: #ccc;
}

.form-group {
    margin-bottom: 15px;
}

/* Ensure main content scrollable */
.container.mt-5 {
    overflow: visible;
}

.table-responsive {
    overflow-x: hidden;
}

/* View Panel Styles */
#viewEmployeePanel {
    position: fixed;
    top: 0;
    right: -100%;
    width: 100%;
    max-width: 800px;
    height: 100vh;
    background: #fff;
    box-shadow: -2px 0 8px rgba(0, 0, 0, .2);
    z-index: 300;
    overflow-y: auto;
    transition: right 0.4s ease;
}

#viewEmployeePanel.open {
    right: 0;
}

.view-panel-header {
    background: var(--primary-gradient);
    padding: 15px;
    font-size: 18px;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #ccc;
    color: white;
}

.view-panel-header .d-flex i {
    background: rgba(255, 255, 255, 0.2);
    padding: 8px;
    border-radius: 10px;
    margin-right: 10px;
}

.view-panel-content {
    padding: 15px;
}

.view-section {
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
}

.view-section-header {
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    padding: 10px 15px;
    font-weight: bold;
    border-bottom: 1px solid #ddd;
    color: #2c3e50;
}

.view-section-body {
    padding: 15px;
}

.view-row {
    display: flex;
    margin-bottom: 10px;
}

.view-label {
    font-weight: bold;
    width: 40%;
    color: #555;
}

.view-value {
    width: 60%;
}

/* Document viewer */
.doc-viewer {
    max-width: 100%;
    max-height: 400px;
    margin: 0 auto;
    display: block;
}

.doc-iframe {
    width: 100%;
    height: 500px;
    border: none;
}

/* Tabs styling */
.view-tabs {
    display: flex;
    border-bottom: 1px solid #ddd;
    margin-bottom: 15px;
    flex-wrap: wrap;
    background: linear-gradient(135deg, #f8faff 0%, #f0f4ff 100%);
    padding: 10px 10px 0;
}

.view-tab {
    padding: 10px 20px;
    cursor: pointer;
    border: 1px solid transparent;
    border-bottom: none;
    margin-right: 5px;
    border-radius: 8px 8px 0 0;
    background-color: rgba(255, 255, 255, 0.5);
    margin-bottom: 5px;
    transition: all 0.3s ease;
    color: #555;
}

.view-tab:hover {
    background-color: rgba(255, 255, 255, 0.9);
    color: #4361ee;
}

.view-tab.active {
    background-color: #fff;
    border-color: #ddd;
    border-bottom: 1px solid #fff;
    margin-bottom: -1px;
    color: #4361ee;
    font-weight: 600;
}

.view-tab-content {
    display: none;
    animation: fadeIn 0.3s ease;
}

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

.view-tab-content.active {
    display: block;
}

/* Reference section styling */
.reference-section {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px dashed #ccc;
}

.reference-header {
    font-weight: bold;
    margin-bottom: 10px;
    color: #555;
}

#signupFormContainer label {
    margin-top: 0;
    margin-bottom: 0;
}

#signupFormContainer input {
    width: 100%;
    margin-top: 0;
}

#signupFormContainer #terms {
    width: auto;
    margin-top: 0;
    margin-right: 8px;
}

/* ERP Table Styles */
.erp-table {
    width: 100%;
    background: #fff;
    border-collapse: collapse;
}

.erp-table thead {
    background: var(--primary-gradient);
    border-bottom: 2px solid #e2e8f0;
    position: sticky;
    top: 0;
    z-index: 10;
}

.erp-table th {
    padding: 15px 8px;
    font-weight: 600;
    color: white;
    text-align: left;
    font-size: 14px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    cursor: pointer;
    user-select: none;
    transition: background-color 0.2s;
    position: relative;
}

.erp-table th h6 {
    color: rgba(255, 255, 255, 0.9);
    font-size: 11px;
    margin: 2px 0 0;
}

.erp-table th:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.erp-table th.sortable {
    padding-right: 30px;
}

.sort-icons {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.sort-icon {
    color: rgba(255, 255, 255, 0.5);
    font-size: 12px;
    line-height: 1;
}

.sort-icon.active {
    color: white;
}

.erp-table td {
    padding: 15px 8px;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    font-size: 14px;
    vertical-align: middle;
}

.erp-table tbody tr {
    transition: background-color 0.2s, transform 0.2s;
}

.erp-table tbody tr:hover {
    background-color: #f8fafc;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.erp-table tbody tr:last-child td {
    border-bottom: none;
}

/* Status Badges */
.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
    transition: transform 0.2s;
}

.status-badge:hover {
    transform: scale(1.05);
}

.status-active {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.status-inactive {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

/* Bulk Actions */
.bulk-actions-container {
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    animation: slideDown 0.3s ease;
    background: #fff;
    padding: 15px 20px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: var(--shadow-sm);
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

.bulk-actions-container.active {
    display: flex;
}

.selected-count {
    font-weight: 500;
    color: #4361ee;
    margin-right: auto;
    font-size: 14px;
    background: #e0e7ff;
    padding: 5px 15px;
    border-radius: 20px;
}

.bulk-action-btn {
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    color: #475569;
    transition: all 0.2s;
    font-size: 14px;
    display: flex;
    align-items: center;
    border: 1px solid transparent;
    margin-right: 5px;
}

.bulk-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.bulk-action-btn:active {
    transform: translateY(0);
}

.bulk-action-btn.download {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.bulk-action-btn.download:hover {
    background: #bbf7d0;
}

.bulk-action-btn.notice {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

.bulk-action-btn.notice:hover {
    background: #fde68a;
}

.bulk-action-btn.exit {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.bulk-action-btn.exit:hover {
    background: #fecaca;
}

.bulk-action-btn.delete {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.bulk-action-btn.delete:hover {
    background: #fecaca;
}

.bulk-action-btn.clear {
    background: transparent;
    color: #64748b;
    border: 1px solid #cbd5e1;
}

.bulk-action-btn.clear:hover {
    background: #f1f5f9;
}

.bulk-action-btn i {
    margin-right: 5px;
}

/* Checkbox styling */
.select-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
    border-radius: 4px;
    border: 2px solid #cbd5e1;
    transition: all 0.2s;
}

.select-checkbox:hover {
    border-color: #3b82f6;
}

.select-checkbox:checked {
    background-color: #3b82f6;
    border-color: #3b82f6;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
}

/* Filter container */
.filter-container {
    background: #fff;
    position: relative;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #e2e8f0;
    animation: slideUp 0.3s ease;
    box-shadow: var(--shadow-sm);
}

.filter-form {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.filter-grid {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    flex: 1;
}

.filter-group {
    flex: 1;
    min-width: 150px;
}

.filter-group h6 {
    margin-bottom: 5px;
    color: #4361ee;
    font-size: 13px;
}

.filter-input {
    padding: 10px 12px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    background: #fff;
    transition: all 0.2s;
    width: 100%;
}

.filter-input:focus {
    outline: none;
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    transform: translateY(-1px);
}

.filter-input:hover {
    border-color: #cbd5e1;
}

.filter-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.btn-filter {
    padding: 5px 10px;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.btn-filter-secondary:hover {
    text-decoration: none;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.btn-filter:active {
    transform: translateY(0);
}

.btn-filter-primary {
    background: var(--primary-gradient);
    color: white;
}

.btn-filter-primary:hover {
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

.btn-filter-secondary {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}

.btn-filter-secondary:hover {
    background: #e2e8f0;
}

/* Header */
.page-header {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding: 20px;
    background: linear-gradient(135deg, #4361ee, #3a0ca3);
    border-radius: 15px;
    animation: fadeIn 0.5s ease;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

.page-title {
    /*font-size: 24px;*/
    font-weight: 600;
    color: white;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.page-title i {
    background: rgba(255, 255, 255, 0.2);
    padding: 10px;
    border-radius: 12px;
}

/* Banner */
.banner {
    height: 260px;
    border-radius: 18px;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #2154BE, #3E70B3);
    display: flex;
    align-items: center;
    animation: fadeIn 0.8s ease;
    box-shadow: var(--shadow-lg);
}

.banner img.bg {
    width: 100%;
    height: 260px;
    object-fit: cover;
    filter: brightness(0.8);
}

.banner .meta {
    position: absolute;
    left: 28px;
    bottom: 22px;
    background: rgba(255, 255, 255, 0.95);
    padding: 14px 20px;
    border-radius: 12px;
    backdrop-filter: blur(10px);
    animation: slideInLeft 0.5s ease;
    box-shadow: var(--shadow-md);
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Action Buttons - Original Alignment */
.table-actions {
    display: flex;
    gap: 5px;
    justify-content: center;
    /*flex-wrap: wrap;*/
}

.table-actions a {
    display: block;
}

.action-btn {
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 5px;
    justify-content: center;
    min-width: 75px;
    text-decoration: none;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-decoration: none;
}

.action-btn:active {
    transform: translateY(0);
}

.action-btn-view {
    background: #e0f2fe;
    color: #0369a1;
    border-color: #bae6fd;
}

.action-btn-assign {
    background: #ffa0a0;
    color: #291515;
    border-color: #ffa0a0;
}

.action-btn-edit {
    background: #fef2c8;
    color: #92400e;
    border-color: #fef2c8;
}

.action-btn-delete {
    background: #fee2e2;
    color: #dc2626;
    border-color: #fecaca;
}

.action-btn-assign:hover {
    color: #291515;
    text-decoration: none;
}

.action-btn-view:hover {
    background: #bae6fd;
    text-decoration: none;
    color: #0369a1;
}

.action-btn-edit:hover {
    background: #fef2c8;
    text-decoration: none;
    color: #92400e;
}

.action-btn-delete:hover {
    background: #fecaca;
}

.action-btn-card {
    background: #dcfce7;
    color: #166534;
    border-color: #bbf7d0;
}

.action-btn-card:hover {
    background: #bbf7d0;
    text-decoration: none;
    color: #166534;
}

/* Loading Animation */
.loading-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #64748b;
}

.empty-state-icon {
    font-size: 48px;
    color: #cbd5e1;
    margin-bottom: 16px;
}

/* Tooltips */
.tooltip {
    position: relative;
    display: inline-block;
}

.tooltip .tooltip-text {
    visibility: hidden;
    width: 120px;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -60px;
    opacity: 0;
    transition: opacity 0.3s;
    font-size: 12px;
}

.tooltip .tooltip-text::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #333 transparent transparent transparent;
}

.tooltip:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #ccc;
    border-top-color: #4361ee;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .page-header {
        /*flex-direction: column;*/
        gap: 15px;
        /*text-align: center;*/
    }

    .filter-grid {
        width: 100%;
    }

    .filter-actions {
        width: 100%;

        /*justify-content: center;*/
    }

    .bulk-actions-container {
        flex-direction: column;
        align-items: stretch;
    }

    .bulk-actions-container .d-flex {
        flex-wrap: wrap;
        gap: 8px;
        /*justify-content: center;*/
    }

    .table-actions {
        /*flex-direction: column;*/
        gap: 4px;
    }

    .action-btn {
        width: 100%;
    }
}

a {
    text-decoration: none;
}

.probation-status {
    min-width: 180px;
}

.probation-status.overdue {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        opacity: 1;
    }

    50% {
        opacity: 0.7;
    }

    100% {
        opacity: 1;
    }
}

.probation-status .btn-sm {
    font-size: 11px;
    padding: 2px 8px;
}

.progress {
    background-color: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar {
    transition: width 0.6s ease;
}

.action-btn-exit {
    background: #ff5d5d;
    color: #ffffff;
    border-color: #fde68a;
}

.action-btn-exit:hover {
    background: #ff5d5d;
    text-decoration: none;
    color: #ffffff;
}

/* Suspend button - Temporary block */
.action-btn-suspend {
    background: linear-gradient(135deg, #fa8993, #fa1b5d);
    color: #ffffff;
}

.action-btn-suspend:hover {
    background: linear-gradient(135deg, #fa8993, #fa1b5d);
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
}

/* Unsuspend button - Restore access */
.action-btn-unsuspend {
    background: linear-gradient(135deg, #002998, #42a6e2);
    color: #fcfdfc;
}

.action-btn-unsuspend:hover {
    background: linear-gradient(135deg, #002998, #42a6e2);
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(34, 197, 94, 0.3);
}

.suspend-badge {
    background: linear-gradient(135deg, #f5690b, #d95306);
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
    margin-left: 8px;
}

/* Visual indicator for suspended rows */
tr.suspended-row {
    background-color: rgba(254, 242, 200, 0.1);
    border-left: 3px solid #f59e0b;
}

/* Exit Status Badges */
.status-draft {
    background: #e5e7eb;
    color: #4b5563;
}

.status-pending_approval {
    background: #fef3c7;
    color: #92400e;
}

.status-notice_period {
    background: #dbeafe;
    color: #1e40af;
}

.status-exited {
    background: #fee2e2;
    color: #991b1b;
}

.status-cancelled {
    background: #e5e7eb;
    color: #4b5563;
}

/* Action button for exit with active process */
.action-btn-exit-active {
    background: #fef3c7;
    color: #92400e;
    border-color: #fde68a;
}

.action-btn-exit-active:hover {
    background: #fde68a;
    color: #92400e;
    text-decoration: none;
}

.action-btn-exit-completed {
    background: #e5e7eb;
    color: #6b7280;
    border-color: #d1d5db;
    cursor: not-allowed;
}

.action-btn-exit-completed:hover {
    background: #e5e7eb;
    color: #6b7280;
    transform: none;
}
</style>

<!-- Header with merchant name -->
<div class="mainDiv1 d-none">
    <div class="mainDiv" style="height: 270px;">
        <div class="banner mb-4">
            @if(!empty($bannerPath))
            <img src="{{ asset('/image/' . $fincapMerchants->documents->first()->institute_image_path) }}"
                alt="institute image" class="bg">
            @else
            <img src="{{ asset('/image/' . $fincapMerchants->documents->first()->institute_image_path) }}"
                alt="institute image" class="bg">
            @endif
            <div class="meta">
                <h3 style="margin:0;">
                    {{ $fincapMerchants->name ?? $fincapMerchants->fincap_merchant_name ?? 'Institute Name' }}
                </h3>
                <p style="margin:0;color:#444;">
                    {{ $fincapMerchants->state ?? $fincapMerchants->fincap_merchant_state ?? '' }},
                    {{ $fincapMerchants->city ?? $fincapMerchants->fincap_merchant_city ?? '' }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="page-header">
        <h4 class="page-title">
            <i class="fas fa-users"></i>
            View Employees
        </h4>
        <button class="add-btn" onclick="window.location.href='/institute/admin/addemployeesdetails'">
            <i class="fas fa-plus-circle"></i>
            Add Employee
        </button>
    </div>

    {{-- Messages --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Filters --}}
    <div class="filter-container">
        <h6 class="mb-3"><i class="fas fa-filter me-2"></i>Search Employees</h6>
        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <input list="employeeNamesList" name="name" class="filter-input" value="{{ request(key: 'name') }}"
                        placeholder="Employee Name">
                    <datalist id="employeeNamesList">
                        @foreach($employeeNames as $emp)
                        <option value="{{ $emp->name }}">
                            @endforeach
                    </datalist>
                </div>

                <div class="filter-group">
                    <input list="employeeCodeList" name="employee_code" class="filter-input"
                        value="{{ request('employee_code') }}" placeholder="Employee Code">
                    <datalist id="employeeCodeList">
                        @foreach($employeeCodes as $code)
                        <option value="{{ $code->employee_code }}">
                            @endforeach
                    </datalist>
                </div>

                <div class="filter-group">
                    <select name="employment_filter" class="filter-input">
                        <option value="">All Employees</option>

                        <option value="full_time" {{ request('employment_filter')=='full_time' ? 'selected' : '' }}>
                            Full Time
                        </option>

                        <option value="part_time" {{ request('employment_filter')=='part_time' ? 'selected' : '' }}>
                            Part Time
                        </option>

                        <option value="contract" {{ request('employment_filter')=='contract' ? 'selected' : '' }}>
                            Contract Based
                        </option>

                        <option value="probation_active"
                            {{ request('employment_filter')=='probation_active' ? 'selected' : '' }}>
                            Active Probation
                        </option>

                        <option class="d-none" value="probation_today"
                            {{ request('employment_filter')=='probation_today' ? 'selected' : '' }}>
                            Completed Today
                        </option>

                        <option value="probation_overdue"
                            {{ request('employment_filter')=='probation_overdue' ? 'selected' : '' }}>
                            Probation Overdue
                        </option>
                    </select>
                </div>

                <div class="filter-group">
                    <!-- Hidden input that will hold the department ID -->
                    <input type="hidden" name="department_id" id="department_id" value="{{ request('department_id') }}">

                    <!-- Visible input with datalist -->
                    <input list="employeeDepartmentList" id="department_name_input" name="department_name_input"
                        class="filter-input" value="{{ $selectedDeptName ?? '' }}" placeholder="All Departments"
                        autocomplete="off">

                    <datalist id="employeeDepartmentList">
                        @foreach($departments as $dept)
                        <option value="{{ $dept->department }}" data-id="{{ $dept->department_id }}">
                            <span class="small">({{ $dept->category->category_name }})</span>
                            @endforeach
                    </datalist>
                </div>

                <div class="filter-group">
                    <input list="employeeDesignationList" name="designation" class="filter-input"
                        value="{{ request('designation') }}" placeholder="All Designations">
                    <datalist id="employeeDesignationList">
                        @foreach($designations as $ds)
                        <option value="{{ $ds->designations }}">
                            @endforeach
                    </datalist>
                </div>
                <div class="filter-group">
                    <select name="status" class="filter-input">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                            ✅ Active
                        </option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                            ⭕ Inactive
                        </option>
                    </select>
                </div>
            </div>

            <div style="position: absolute;top: 7px;right: 13px;">

                <div class="filter-actions" style="font-size:small;">
                    <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary">
                        <i class="fas fa-undo-alt"></i>
                        Reset
                    </a>
                </div>
            </div>

            <!-- Hidden sort inputs -->
            <input type="hidden" name="sort_by" id="sortBy" value="{{ request('sort_by', 'employee_code') }}">
            <input type="hidden" name="sort_order" id="sortOrder" value="{{ request('sort_order', 'asc') }}">
        </form>
    </div>

    {{-- Bulk Actions Container --}}
    <div class="bulk-actions-container" id="bulkActionsContainer">
        <div class="selected-count mb-2" id="selectedCount">0 employees selected</div>
        <div class="d-flex flex-wrap">
            <span style="margin-right:5px;">
                <select class="bulk-action-btn download" onchange="bulkAction('download', this.value)"
                    style="margin-right: 0px;margin-top:0px;">
                    <option value="">Download</option>
                    <option value="excel">Excel</option>
                    <option value="csv">CSV</option>
                </select>
            </span>

            <button class="bulk-action-btn notice d-none" onclick="bulkAction('send_notice')">
                <i class="fas fa-envelope"></i>
                Send Notice
            </button>

            <button class="bulk-action-btn delete" onclick="bulkAction('bulk_delete')">
                <i class="fas fa-trash-alt"></i>
                Bulk Delete
            </button>
            <button class="bulk-action-btn clear mr-0" onclick="clearSelection()">
                <i class="fas fa-times"></i>
                Clear
            </button>
        </div>
    </div>
    <div>
        {{-- Employee Table --}}
        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
            <input type="hidden" id="filteredTotal" value="{{ $employees->total() }}">
            <table class="erp-table">
                <thead>
                    <tr>
                        <th class="sticky-checkbox" width="40">
                            <input type="checkbox" id="selectAll" class="select-checkbox">
                        </th>
                        <th class="sortable d-none" onclick="sortTable('employee_code')">
                            Employee Code
                            <div class="sort-icons">
                                <i
                                    class="sort-icon fas fa-caret-up {{ request('sort_by') == 'employee_code' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon fas fa-caret-down {{ request('sort_by') == 'employee_code' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sticky-main sortable" onclick="sortTable('name')">
                            Name
                            <h6>Employee ID</h6>

                            <div class="sort-icons">
                                <i
                                    class="sort-icon fas fa-caret-up {{ request('sort_by') == 'name' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon fas fa-caret-down {{ request('sort_by') == 'name' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('employment_type')">
                            Employment Type
                            <div class="sort-icons">
                                <i class="sort-icon fas fa-caret-up"></i>
                                <i class="sort-icon fas fa-caret-down"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('email')">
                            Email
                            <div class="sort-icons">
                                <i
                                    class="sort-icon fas fa-caret-up {{ request('sort_by') == 'email' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon fas fa-caret-down {{ request('sort_by') == 'email' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('mobile_number')">
                            Phone
                            <div class="sort-icons">
                                <i
                                    class="sort-icon fas fa-caret-up {{ request('sort_by') == 'mobile_number' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon fas fa-caret-down {{ request('sort_by') == 'mobile_number' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('department_name')">
                            Department
                            <div class="sort-icons">
                                <i
                                    class="sort-icon fas fa-caret-up {{ request('sort_by') == 'department_name' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon fas fa-caret-down {{ request('sort_by') == 'department_name' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('designation')">
                            Designation
                            <div class="sort-icons">
                                <i
                                    class="sort-icon fas fa-caret-up {{ request('sort_by') == 'designation' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon fas fa-caret-down {{ request('sort_by') == 'designation' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('status')">
                            Status
                            <div class="sort-icons">
                                <i
                                    class="sort-icon fas fa-caret-up {{ request('sort_by') == 'status' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon fas fa-caret-down {{ request('sort_by') == 'status' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('exit_status')">
                            Exit Status
                            <div class="sort-icons">
                                <i class="sort-icon fas fa-caret-up"></i>
                                <i class="sort-icon fas fa-caret-down"></i>
                            </div>
                        </th>
                        <th class="sortable text-center">ID Card</th>
                        <th class="sortable text-center">Actions</th>
                        <th class="sortable text-center">Journey</th>
                        <th class="sortable text-center d-none">Suspension History</th>
                        <th class="sortable text-center">Assign Duties</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $hasFilter = request('name') || request('employee_code') || request('department_id') ||
                    request('designation') || request('department_name_input');
                    @endphp
                    @foreach($employees as $emp)
                    <tr>
                        <td class="sticky-checkbox">
                            <input type="checkbox" class="employee-checkbox select-checkbox" value="{{ $emp->id }}"
                                data-dept-id="{{ $emp->department_id ?? '' }}" @if($hasFilter) checked @endif>
                        </td>

                        <td class="d-none">{{ $emp->employee_code }}</td>

                        <td class="sticky-main">
                            {{ $emp->name }} <br>

                            <span class="small text-primary">
                                {{ $emp->employee_code }}
                            </span>

                        </td>
                        <td>
                                                    @php
                                                    $badgeClass = match(strtolower($emp->employment_type)) {
                                                    'full-time' => 'bg-success',
                                                    'probation-period' => 'bg-warning text-dark',
                                                    'contract-based' => 'bg-info',
                                                    'part-time' => 'bg-secondary',
                                                    default => 'bg-light text-dark'
                                                    };
                        
                                                    $probationInfo = $emp->getProbationStatusAttribute();
                                                    $daysDelta = $emp->getProbationDaysDeltaAttribute();
                                                    $endDate = $emp->getProbationEndDateAttribute();
                                                    @endphp
                        
                                                    <!-- Employment Type Badge -->
                                                    <span class="badge {{ $badgeClass }}">
                                                        @if($emp->employment_type === 'Probation-Period')
                                                        Probation
                                                        @else
                                                       {{ ucfirst($emp->employment_type) }}
                        
                                                        @endif
                                                        @if($emp->employment_type === 'Probation-Period')
                                                        <span class="badge bg-warning text-dark ms-1">
                                                            <i class="bi bi-calendar-week"></i> {{ $emp->probation_days }} days
                                                        </span>
                                                        @endif
                                                    </span>
                        
                                                    <!-- Probation Details (only for Probation-Period) -->
                                                    @if($emp->employment_type === 'Probation-Period')
                                                    <div class="mt-1">
                                                        @if($probationInfo === 'overdue')
                                                         <div class="small text-muted mt-1">
                                                            <i class="fas fa-calendar-alt"></i> DOJ:
                                                            {{ $emp->doj ? \Carbon\Carbon::parse($emp->doj)->format('d-m-Y') : 'N/A' }}
                                                        </div>
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-exclamation-triangle"></i>
                                                            Overdue by {{ abs($daysDelta) }} day(s)
                                                        </span>
                        
                                                        <div class="small text-danger mt-1">
                                                            <i class="fas fa-calendar-times"></i> Ended:
                                                            {{ $endDate ? $endDate->format('d-m-Y') : 'N/A' }}
                                                        </div>
                                                        <button class="btn btn-sm btn-warning mt-1" onclick="promoteToFullTime({{ $emp->id }})"
                                                            style="font-size: 10px; padding: 2px 8px;">
                                                            <i class="fas fa-arrow-up"></i> Promote
                                                        </button>
                                                        @elseif($probationInfo === 'completes_today')
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="fas fa-calendar-day"></i> Completed Today
                                                        </span>
                                                        <button class="btn btn-sm btn-success mt-1" onclick="promoteToFullTime({{ $emp->id }})"
                                                            style="font-size: 10px; padding: 2px 8px;">
                                                            <i class="fas fa-check"></i> Complete
                                                        </button>
                                                        @else
                                                         <div class="small text-muted mt-1">
                                                            <i class="fas fa-calendar-alt"></i> DOJ:
                                                            {{ $emp->doj ? \Carbon\Carbon::parse($emp->doj)->format('d-m-Y') : 'N/A' }}
                                                        </div>
                                                        <span class="badge bg-info">
                                                            <i class="fas fa-hourglass-half"></i>
                                                            {{ $daysDelta }} day(s) remaining
                                                        </span>
                        
                                                        <div class="small text-muted">
                                                            <i class="fas fa-calendar-check"></i> Ends:
                                                            {{ $endDate ? $endDate->format('d-m-Y') : 'N/A' }}
                                                        </div>
                        
                                                        @endif
                                                    </div>
                                                    @else
                                                    <br>
                        
                                                         <div class="small text-muted mt-1">
                                                            <i class="fas fa-calendar-alt"></i> DOJ:
                                                            {{ $emp->doj ? \Carbon\Carbon::parse($emp->doj)->format('d-m-Y') : 'N/A' }}
                                                        </div>
                                                    @endif
                        </td>
                        <td>{{ $emp->email }}</td>
                        <td>{{ $emp->mobile_number ?? 'N/A' }}</td>
                        <td>{{ $emp->department_name ?? 'N/A' }}</td>
                        <td>{{ $emp->designation }}</td>
                        <td>
                            @php
                            $statusClass = 'status-active';
                            $statusText = 'Active';

                            if (isset($emp->status)) {
                            switch (strtolower($emp->status)) {
                            case 'inactive':
                            $statusClass = 'status-inactive';
                            $statusText = 'Inactive';
                            break;
                            case 'pending':
                            $statusClass = 'status-pending';
                            $statusText = 'Pending';
                            break;
                            default:
                            $statusClass = 'status-active';
                            $statusText = 'Active';
                            }
                            }
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                        </td>
                        <td>
                            @php
                            $exitData = $emp->exit_status_display ?? null;
                            @endphp

                            @if($exitData && $exitData['has_exit'])
                            <!-- Status Badge -->
                            <span class="exit-status-badge exit-status-{{ $exitData['status'] }}">
                                {{ $exitData['label'] }}
                            </span>

                            @if($exitData['is_overdue'])
                            <span class="badge bg-danger ms-1" style="font-size:8px;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>
                            @endif

                            <!-- Date Display -->
                            <div class="small text-muted" style="font-size:9px;margin-top:2px;">
                                @if($exitData['is_exited'] && $exitData['actual_exit_date'])
                                {{-- ✅ Show actual exit date when exited --}}
                                <i class="fas fa-calendar-check text-success me-1"></i>
                                Exited:
                                @if($exitData['actual_exit_date'] instanceof \Carbon\Carbon)
                                {{ $exitData['actual_exit_date']->format('d-m-Y') }}
                                @else
                                {{ \Carbon\Carbon::parse($exitData['actual_exit_date'])->format('d-m-Y') }}
                                @endif
                                @elseif($exitData['notice_end_date'])
                                {{-- Show notice end date for active exits --}}
                                <i class="fas fa-calendar-alt me-1"></i>
                                @if($exitData['notice_end_date'] instanceof \Carbon\Carbon)
                                {{ $exitData['notice_end_date']->format('d-m-Y') }}
                                @else
                                {{ \Carbon\Carbon::parse($exitData['notice_end_date'])->format('d-m-Y') }}
                                @endif
                                @else
                                <span class="text-muted">No date</span>
                                @endif
                            </div>
                            @else
                            <span class="text-muted" style="font-size:11px;">--</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="table-actions">
                                @php
                                    $hasCard = \App\Models\GeneratedIdCard::where('employee_id', $emp->employee_id)
                                                ->where('is_active', true)
                                                ->exists();
                                    $card = \App\Models\GeneratedIdCard::where('employee_id', $emp->employee_id)
                                                ->where('is_active', true)
                                                ->first();
                                @endphp
                               @if($hasCard && $card)
                                <a href="{{ route('generated-cards.view', $card->id) }}"
                                class="action-btn action-btn-card d-flex" title="View ID Card">
                                    <div>
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <div>
                                        <span class="small">View Card</span>
                                    </div>
                                </a>
                            @else
                                <a href="{{ route('id-card.generate', ['employee' => $emp->employee_id]) }}"
                                class="action-btn action-btn-card d-flex" title="Generate ID Card">
                                    <div>
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <div>
                                        <span class="small">Generate</span>
                                    </div>
                                </a>
                            @endif
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="table-actions d-flex">
                                <!-- View Button -->
                                <a href="{{ url('/employee-details/' . $emp->id) }}"
                                    class="action-btn action-btn-view d-flex" title="View Details">
                                    <div>
                                        <i class="fa-regular fa-eye"></i>
                                    </div>
                                    <div>
                                        <span class="small">View</span>
                                    </div>
                                </a>

                                <!-- Edit Button -->
                                <a href="{{ route('employees.edit', $emp->employee_id) }}"
                                    class="action-btn action-btn-edit d-flex" title="Edit Employee">
                                    <div>
                                        <i class="fas fa-edit"></i>
                                    </div>
                                    <div>
                                        <span class="small">Edit</span>
                                    </div>
                                </a>

                                <!-- SUSPEND/UNSUSPEND BUTTON -->
                                @if($emp->suspend_status === 'suspended')
                                <button class="action-btn action-btn-unsuspend"
                                    onclick="unsuspendEmployee('{{ $emp->id }}', '{{ $emp->name }}')"
                                    title="Unsuspend Employee - Restore Access">
                                    <div>
                                        <i class="bi bi-unlock-fill"></i>
                                    </div>
                                    <div>
                                        <span class="small">Unsuspend</span>
                                    </div>
                                </button>
                                @else
                                <button class="action-btn action-btn-suspend"
                                    onclick="suspendEmployee('{{ $emp->id }}', '{{ $emp->name }}')"
                                    title="Suspend Employee - Block Access Temporarily">
                                    <div>
                                        <i class="bi bi-lock-fill"></i>
                                    </div>
                                    <div>
                                        <span class="small">Suspend</span>
                                    </div>
                                </button>
                                @endif

                                <!-- ✅ EXIT BUTTON - UPDATED WITH PROPER STATUS CHECK -->
                                @php
                                $exitData = $emp->exit_status_display ?? null;
                                @endphp

                                @if($exitData && $exitData['is_exited'])
                                <button class="action-btn action-btn-exit-completed d-flex" disabled
                                    title="Employee already exited">
                                    <div>
                                        <i class="fas fa-door-closed"></i>
                                    </div>
                                    <div>
                                        <span class="small">Exited</span>
                                    </div>
                                </button>
                                @elseif($exitData && $exitData['is_active'])
                                <a href="{{ route('employee.exit.initiate', $emp->employee_id) }}"
                                    class="action-btn action-btn-exit-active d-flex" title="View/Manage Exit Process">
                                    <div>
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div>
                                        <span class="small">
                                            @if($exitData['is_pending'])
                                            Pending
                                            @elseif($exitData['is_notice_period'])
                                            Notice-Period
                                            @else
                                            In Exit
                                            @endif
                                        </span>
                                    </div>
                                </a>
                                @else
                                <a href="{{ route('employee.exit.initiate', $emp->employee_id) }}"
                                    class="action-btn action-btn-exit d-flex" title="Initiate Exit Process">
                                    <div>
                                        <i class="fas fa-door-open"></i>
                                    </div>
                                    <div>
                                        <span class="small">Exit</span>
                                    </div>
                                </a>
                                @endif
                            </div>
                        </td>
                      
                        <td class="text-center d-none">
                            <div class="table-actions">
                                <button class="action-btn action-btn-info"
                                    onclick="employeeSuspensionHistoryModal('{{ $emp->id }}', '{{ $emp->name }}')"
                                    title="View Suspension History">
                                    <div><i class="bi bi-clock-history"></i></div>

                                    <div><span class="small">History</span></div>
                                </button>
                            </div>

                        </td>
                        <td class="text-center">  <div class="table-actions">
                                            <a href="{{ route('employees.journey', $emp->id) }}" class="dropdown-item" title="View Employee Journey">
                                                <i class="fas fa-road me-2 d-none"></i> View
                                            </a>
                                         </div></td>
                        <td class="text-center">
                            <div class="table-actions">
                                <a href="{{ route('employees.assign-responsibilities.form', $emp->employee_id) }}"
                                    class="action-btn action-btn-assign d-flex" title="Assign Duties">
                                    <div>
                                        <i class="fas fa-tasks"></i>
                                    </div>
                                    <div>
                                        <span class="small">Assign Duties</span>
                                    </div>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if($employees->count() == 0)
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h4>No employees found</h4>
                                <p>Try adjusting your filters or add a new employee</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
                <!-- Floating Horizontal Scrollbar -->
        <div class="table-scroll-top" id="tableScrollTop">
            <div class="table-scroll-inner"></div>
        </div>

        {{-- Pagination --}}
        @if($employees->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-sm text-gray-600">
                Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} of
                {{ $employees->total() }}
                results
            </div>
            <div>
                {{ $employees->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>
</div>



<div id="pageLoader" style="
        display:none;
        position:fixed;
        inset:0;
        background:rgba(255,255,255,0.7);
        z-index:9999;
        align-items:center;
        justify-content:center;
    ">
    <div class="spinner"></div>
</div>

<!-- Suspension History Modal -->
<div class="modal fade" id="employeeSuspensionHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <h5 class="modal-title" style="color: white;">
                    <i class="bi bi-clock-history me-2"></i>
                    Employee Suspension History
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    style="background-color: white;"></button>
            </div>
            <div class="modal-body" id="employeeSuspensionHistoryContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-warning" role="status"></div>
                    <p class="mt-2">Loading suspension history...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Bootstrap Icons (keeping for compatibility) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Add this after jQuery and before your custom scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ============================================
// GLOBAL VARIABLES (Removed selectAllGlobal)
// ============================================

// Bulk Selection Management
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const bulkActionsContainer = document.getElementById('bulkActionsContainer');
    const selectedCountElement = document.getElementById('selectedCount');

    // Track whether header "Select All" represents "all filtered" (across pages)
    let selectAllFiltersMode = false;
    // Timer used to batch rapid checkbox change events so we compute final counts
    let selectionUpdateTimer = null;

    function scheduleUpdateSelectionUI() {
        if (selectionUpdateTimer) clearTimeout(selectionUpdateTimer);
        selectionUpdateTimer = setTimeout(() => {
            updateSelectionUI();
            selectionUpdateTimer = null;
        }, 30);
    }

    // Delegate change events for all present and future checkboxes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('employee-checkbox')) {
            // If we were in 'all filtered' mode and the user manually unchecked a row,
            // cancel that mode so counts reflect actual per-page selection.
            if (selectAllFiltersMode && e.target.checked === false) {
                selectAllFiltersMode = false;
                // ensure header checkbox state reflects page selection
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }

            scheduleUpdateSelectionUI();
        }
    });

    // Select All functionality
    selectAllCheckbox.addEventListener('change', function() {
        const isChecked = this.checked;
        const filteredTotalInput = document.getElementById('filteredTotal');
        const filteredTotal = filteredTotalInput ? parseInt(filteredTotalInput.value, 10) : null;

        // If user checked header and there are filtered results, assume they want to select all filtered
        // If unchecked, clear the mode so UI shows 0
        selectAllFiltersMode = isChecked && filteredTotal && filteredTotal > 0;

        document.querySelectorAll('.employee-checkbox').forEach(cb => {
            // When selecting all filtered, check visible checkboxes visually too
            cb.checked = isChecked;
            // Dispatch change event so individual listeners (including delegated) run
            cb.dispatchEvent(new Event('change', {
                bubbles: true
            }));
        });

        // schedule a single UI update after all checkbox change events settle
        scheduleUpdateSelectionUI();
    });

    function updateSelectionUI() {
        const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
        const localSelectedCount = document.querySelectorAll('.employee-checkbox:checked').length;
        const filteredTotalInput = document.getElementById('filteredTotal');
        const filteredTotal = filteredTotalInput ? parseInt(filteredTotalInput.value, 10) : null;

        // If header selectAll is checked, prefer showing the total filtered count (across pages)
        if (selectAllCheckbox.checked && filteredTotal && filteredTotal > 0) {
            bulkActionsContainer.classList.add('active');
            selectedCountElement.textContent = filteredTotal + ' employee(s) selected';

            // When selectAll is used to mean "all filtered", it's not indeterminate
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else if (localSelectedCount > 0) {
            bulkActionsContainer.classList.add('active');
            selectedCountElement.textContent = localSelectedCount + ' employee(s) selected';

            // Update select all checkbox state based on current page checkboxes
            selectAllCheckbox.checked = localSelectedCount === employeeCheckboxes.length && employeeCheckboxes
                .length > 0;
            selectAllCheckbox.indeterminate = localSelectedCount > 0 && localSelectedCount < employeeCheckboxes
                .length;
        } else {
            bulkActionsContainer.classList.remove('active');
            selectedCountElement.textContent = '0 employees selected';
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
    }

    // Initial call in case of pre‑checked boxes (e.g., after form submit)
    updateSelectionUI();

    // If all visible checkboxes are pre-checked (due to filter auto-selection),
    // and total filtered > visible count, enable "all filtered" mode
    const allCheckboxesChecked = Array.from(document.querySelectorAll('.employee-checkbox')).every(cb => cb
        .checked);
    const filteredTotalInput = document.getElementById('filteredTotal');
    const filteredTotal = filteredTotalInput ? parseInt(filteredTotalInput.value, 10) : null;
    const visibleCheckboxCount = document.querySelectorAll('.employee-checkbox').length;

    if (allCheckboxesChecked && filteredTotal && filteredTotal > visibleCheckboxCount && visibleCheckboxCount >
        0) {
        selectAllFiltersMode = true;
        selectAllCheckbox.checked = true;
        updateSelectionUI();
    }
});

// Bulk Action Functions
function clearSelection() {
    document.querySelectorAll('.employee-checkbox:checked').forEach(checkbox => {
        checkbox.checked = false;
        // Dispatch change to update UI
        checkbox.dispatchEvent(new Event('change', {
            bubbles: true
        }));
    });
    // No need to manually update container; change events will trigger updateSelectionUI
}

function bulkAction(action, format = null) {
    const selectAllCheckbox = document.getElementById('selectAll');
    const selectAllChecked = selectAllCheckbox ? selectAllCheckbox.checked : false;

    let ids = [];
    if (!selectAllChecked) {
        ids = Array.from(document.querySelectorAll('.employee-checkbox:checked'))
            .map(cb => cb.value);
    }

    // Validate selection – if no checkboxes and no filters, show alert
    const filterForm = document.getElementById('filterForm');
    let hasFilter = false;
    if (filterForm) {
        const filterInputs = filterForm.querySelectorAll('input, select');
        filterInputs.forEach(input => {
            if (input.value && input.value.trim() !== '') {
                hasFilter = true;
            }
        });
    }

    // For download we handle specially; for other actions we require selection
    if (action !== 'download') {
        if (!selectAllChecked && ids.length === 0) {
            alert('Please select at least one employee.');
            return;
        }
    }

    const selectedCount = selectAllChecked ?
        'all filtered' :
        ids.length;

    switch (action) {

        // ================= DOWNLOAD =================
        case 'download':
            if (!format) {
                alert("Please select format");
                return;
            }

            const params = new URLSearchParams();
            params.append('type', format);

            // Priority 1: explicit selection
            if (selectAllChecked) {
                const formData = new FormData(filterForm);
                formData.forEach((value, key) => {
                    if (value) params.append(key, value);
                });
                params.append('select_all', 1);
            } else if (ids.length > 0) {
                params.append('ids', ids.join(','));
            }
            // Priority 3: no selection but filters present → use select_all with filters
            else if (hasFilter) {
                const formData = new FormData(filterForm);
                formData.forEach((value, key) => {
                    if (value) params.append(key, value);
                });
                params.append('select_all', 1);
            } else {
                alert('Please select at least one employee or apply filters to download all.');
                return;
            }

            window.location.href = `/employees/bulk-download?${params.toString()}`;
            break;

            // ================= DELETE =================
        case 'bulk_exit':
            const selectedIds = getSelectedIds();
            if (selectedIds.length === 0) {
                alert('Please select at least one employee.');
                return;
            }

            const reason = prompt('Enter exit reason (optional):');
            // User clicked cancel
            if (reason === null) return;

            if (confirm(`Are you sure you want to exit ${selectedIds.length} employee(s)?`)) {
                showLoader();

                fetch('/employees/bulk-exit', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            employee_ids: selectedIds,
                            exit_reason: reason || 'Bulk exit action'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoader();
                        if (data.success) {
                            alert(data.message);
                            clearSelection();
                            setTimeout(() => location.reload(true), 1500);
                        } else {
                            alert(data.message || 'Failed to exit employees');
                        }
                    })
                    .catch(error => {
                        hideLoader();
                        alert('An error occurred while exiting employees');
                    });
            }
            break;

        default:
            alert(`${action} action triggered for ${selectedCount} employees`);
    }
}

function getSelectedIds() {
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox && selectAllCheckbox.checked) {
        // Return all visible checkbox values
        return Array.from(document.querySelectorAll('.employee-checkbox'))
            .map(cb => cb.value)
            .filter(id => id);
    }
    return Array.from(document.querySelectorAll('.employee-checkbox:checked'))
        .map(cb => cb.value);
}


// Sorting Functionality
function sortTable(column) {
    const currentSortBy = document.getElementById('sortBy').value;
    const currentSortOrder = document.getElementById('sortOrder').value;

    let newSortOrder = 'asc';

    if (currentSortBy === column) {
        newSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
    }

    document.getElementById('sortBy').value = column;
    document.getElementById('sortOrder').value = newSortOrder;

    // Submit the form
    document.getElementById('filterForm').submit();
}

// Tab switching for view panel
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.view-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.view-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.view-tab-content').forEach(c => c.classList.remove(
                'active'));
            this.classList.add('active');
            const tabId = this.getAttribute('data-tab');
            document.getElementById(`${tabId}-tab`).classList.add('active');
        });
    });
});

// Document Viewer
$(document).on('click', '.view-doc', function() {
    let filePath = $(this).data('file');
    let previewHtml = '';
    let cacheBuster = '?t=' + new Date().getTime();

    if (filePath.match(/\.(jpg|jpeg|png|gif|webp|bmp)$/i)) {
        previewHtml = `<img src="${filePath}${cacheBuster}" class="doc-viewer" alt="Document">`;
    } else if (filePath.match(/\.(pdf)$/i)) {
        previewHtml =
            `<iframe src="${filePath}${cacheBuster}#view=fitH" class="doc-iframe" frameborder="0"></iframe>`;
    } else {
        previewHtml =
            `<div class="p-3"><a href="${filePath}" target="_blank" class="btn btn-primary">Open Document</a></div>`;
    }

    $('#docPreview').html(previewHtml);
    $('#docModal').modal('show');
});


// Filter form submission with loading state
document.getElementById('filterForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('.btn-filter-primary');
    if (submitBtn) {
        const originalHTML = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Applying...';
        submitBtn.disabled = true;

        // Re-enable button after 2 seconds in case of error
        setTimeout(() => {
            submitBtn.innerHTML = originalHTML;
            submitBtn.disabled = false;
        }, 2000);
    }
});


// Build a map of department names to IDs
document.addEventListener('DOMContentLoaded', function() {
    // Build department map
    const deptMap = {};
    document.querySelectorAll('#employeeDepartmentList option').forEach(option => {
        deptMap[option.value] = option.getAttribute('data-id');
    });

    const deptNameInput = document.getElementById('department_name_input');
    const deptIdHidden = document.getElementById('department_id');

    // Sync visible input from hidden ID on page load
    const initialDeptId = deptIdHidden.value;
    if (initialDeptId) {
        const matchingOption = document.querySelector(
            `#employeeDepartmentList option[data-id="${initialDeptId}"]`);
        if (matchingOption) {
            deptNameInput.value = matchingOption.value;
        }
    }

    // Update hidden ID when user types or selects
    function updateDepartmentId() {
        const name = deptNameInput.value.trim();
        const id = deptMap[name] || '';
        deptIdHidden.value = id;
    }

    deptNameInput.addEventListener('input', updateDepartmentId);
    deptNameInput.addEventListener('change', updateDepartmentId);

    // Initial sync (also runs if hidden ID was empty)
    updateDepartmentId();
});


function promoteToFullTime(employeeId) {
    if (confirm('Are you sure you want to promote this employee from probation to full-time?')) {
        // Show loading
        const loader = document.getElementById('pageLoader');
        if (loader) loader.style.display = 'flex';

        // Make AJAX request to promote employee
        fetch(`/employees/${employeeId}/promote-to-fulltime`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (loader) loader.style.display = 'none';

                if (data.success) {
                    alert('Employee promoted to full-time successfully!');
                    // Force reload from server (bypass cache)
                    setTimeout(() => {
                        location.reload(true);
                    }, 1500);
                } else {
                    alert(data.message || 'Failed to promote employee');
                }
            })
            .catch(error => {
                if (loader) loader.style.display = 'none';
                alert('An error occurred while promoting employee');
                console.error('Error:', error);
            });
    }
}

function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}

// ============================================
// EMPLOYEE SUSPENSION FUNCTIONS
// ============================================

// SUSPEND EMPLOYEE FUNCTION (Temporary block)
function suspendEmployee(employeeId, employeeName) {
    Swal.fire({
        title: 'Suspend Employee (Temporary)',
        html: `
            <p>Are you sure you want to <strong class="text-warning">TEMPORARILY SUSPEND</strong> <strong>${employeeName}</strong>?</p>
            <p>This will:</p>
            <ul class="text-left">
                <li><i class="bi bi-lock-fill text-warning"></i> <strong>BLOCK ACCESS</strong> temporarily</li>
                <li><i class="bi bi-clock-history"></i> Employee can be unsuspended later</li>
                <li><i class="bi bi-person-check"></i> Employee remains in the system</li>
            </ul>
            <p class="text-muted small">Note: This is different from Exit (which is permanent).</p>
            <div class="mt-3">
                <label for="employeeSuspensionReason" class="form-label">Suspension Reason <span class="text-danger">*</span>:</label>
                <textarea id="employeeSuspensionReason" class="form-control" rows="3"
                    placeholder="Please provide a reason for temporary suspension..."></textarea>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Suspend Temporarily',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const suspensionReason = document.getElementById('employeeSuspensionReason').value;
            if (!suspensionReason || suspensionReason.trim().length < 3) {
                Swal.showValidationMessage(
                    'Please provide a valid suspension reason (minimum 3 characters)');
                return false;
            }
            return {
                suspensionReason: suspensionReason
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processing...',
                text: 'Suspending employee and blocking access',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: `/employees/${employeeId}/suspend`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    suspension_reason: result.value.suspensionReason
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Employee Suspended',
                            text: response.message,
                            confirmButtonColor: '#f59e0b'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            confirmButtonColor: '#f59e0b'
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred while suspending the employee.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        confirmButtonColor: '#f59e0b'
                    });
                }
            });
        }
    });
}

// UNSUSPEND EMPLOYEE FUNCTION (Restore access)
function unsuspendEmployee(employeeId, employeeName) {
    Swal.fire({
        title: 'Unsuspend Employee',
        html: `
            <p>Are you sure you want to <strong class="text-success">UNSUSPEND</strong> <strong>${employeeName}</strong>?</p>
            <p>This will:</p>
            <ul class="text-left">
                <li><i class="bi bi-unlock-fill text-success"></i> <strong>RESTORE ACCESS</strong></li>
                <li><i class="bi bi-check-circle"></i> Employee can work normally again</li>
                <li><i class="bi bi-arrow-repeat"></i> Employee remains in the system</li>
            </ul>
            <div class="mt-3">
                <label for="employeeUnsuspensionReason" class="form-label">Unsuspension Reason (Optional):</label>
                <textarea id="employeeUnsuspensionReason" class="form-control" rows="3"
                    placeholder="Please provide a reason for unsuspending the employee..."></textarea>
                <small class="text-muted">This will be logged for audit purposes</small>
            </div>
            <div class="mt-3 alert alert-info">
                <i class="bi bi-info-circle"></i>
                The employee will be able to access the system immediately after unsuspension.
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Unsuspend Employee',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const unsuspensionReason = document.getElementById('employeeUnsuspensionReason').value;
            return {
                unsuspensionReason: unsuspensionReason || null
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processing...',
                text: 'Restoring employee access',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: `/employees/${employeeId}/unsuspend`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    unsuspension_reason: result.value.unsuspensionReason
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Employee Unsuspended',
                            text: response.message,
                            confirmButtonColor: '#10b981'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            confirmButtonColor: '#10b981'
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred while unsuspending the employee.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        confirmButtonColor: '#10b981'
                    });
                }
            });
        }
    });
}

// VIEW EMPLOYEE SUSPENSION HISTORY
function employeeSuspensionHistoryModal(employeeId, employeeName) {
    $('#employeeSuspensionHistoryModal').modal('show');
    $('#employeeSuspensionHistoryContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-warning" role="status"></div>
            <p class="mt-2">Loading suspension history for ${employeeName}...</p>
        </div>
    `);

    $.ajax({
        url: `/employees/${employeeId}/suspension-history`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                let html = `
                    <div class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="text-warning mb-0">${response.statistics.total_suspensions}</h5>
                                        <small class="text-muted">Total Suspensions</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="text-success mb-0">${response.statistics.total_unsuspensions}</h5>
                                        <small class="text-muted">Total Unsuspensions</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="${response.statistics.currently_suspended ? 'text-danger' : 'text-success'} mb-0">
                                            ${response.statistics.currently_suspended ? 'Suspended' : 'Active'}
                                        </h5>
                                        <small class="text-muted">Current Status</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6><i class="bi bi-list-ul me-2"></i>Suspension Logs</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Action</th>
                                    <th>Reason</th>
                                    <th>Performed By</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                if (response.logs.length === 0) {
                    html +=
                        `<tr><td colspan="4" class="text-center text-muted">No suspension records found</td></tr>`;
                } else {
                    response.logs.forEach(log => {
                        const actionClass = log.action === 'suspend' ? 'text-warning' :
                            'text-success';
                        const actionIcon = log.action === 'suspend' ? '🔒' : '🔓';
                        html += `
                            <tr>
                                <td><small>${new Date(log.created_at).toLocaleString()}</small></td>
                                <td><span class="${actionClass}">${actionIcon} ${log.action.toUpperCase()}</span></td>
                                <td><small>${log.reason || 'N/A'}</small></td>
                                <td><small>${log.performed_by ? log.performed_by.name : 'System'}</small></td>
                            </tr>
                        `;
                    });
                }

                html += `
                            </tbody>
                        </table>
                    </div>
                `;

                $('#employeeSuspensionHistoryContent').html(html);
            } else {
                $('#employeeSuspensionHistoryContent').html(`
                    <div class="alert alert-danger">${response.message}</div>
                `);
            }
        },
        error: function() {
            $('#employeeSuspensionHistoryContent').html(`
                <div class="alert alert-danger">Error loading suspension history</div>
            `);
        }
    });
}
// Auto‑submit filter form on input (with debounce)
// Auto-submit on status filter change
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filterForm');
    let typingTimer;
    const delay = 1000;

    const autoInputs = [
        'name',
        'employee_code',
        'department_name_input',
        'designation'
    ];

    // Auto-submit for text inputs
    autoInputs.forEach(name => {
        const input = document.querySelector(`input[name="${name}"]`);
        if (!input) return;

        input.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                showLoader();
                form.submit();
            }, delay);
        });
    });

    // Auto-submit for select dropdowns
    const selectInputs = [
        'employment_filter',
        'status'
    ];

    selectInputs.forEach(name => {
        const select = document.querySelector(`select[name="${name}"]`);
        if (!select) return;

        select.addEventListener('change', function() {
            showLoader();
            form.submit();
        });
    });
});
</script>
@endsection
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@section('content')

<style>
    .history-tab-content {
        display: none;
    }

    .history-tab-content.active {
        display: block;
    }

    .history-tab {
        padding: 10px 20px;
        background: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
        color: #666;
        border-radius: 6px 6px 0 0;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .history-tab.active {
        background: #3498db;
        color: white;
    }

    .history-tab:hover:not(.active) {
        background: #f8f9fa;
        color: #3498db;
    }
    .btn-primary {
        background: #3498db;
        color: white;
    }
    /* Add these styles to your CSS */
    .changes-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        vertical-align: top;
    }

    .changes-table .change-field {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 4px;
    }

    .changes-table .change-from {
        color: #e74c3c;
        font-size: 0.85rem;
        display: block;
    }

    .changes-table .change-to {
        color: #2ecc71;
        font-size: 0.85rem;
        display: block;
    }

    .changes-table .change-arrow {
        color: #999;
        margin: 0 5px;
    }

    .dashboard {
        display: block;
    }

    .main-content {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        width: 100%;
    }

    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .content-header h2 {
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .header-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: #3498db;
        color: white;
    }

    .btn-primary:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }

    .btn-success {
        background: #2ecc71;
        color: white;
    }

    .btn-success:hover {
        background: #27ae60;
    }

    .btn-danger {
        background: #e74c3c;
        color: white;
    }

    .btn-danger:hover {
        background: #c0392b;
    }

    .btn-warning {
        background: #f39c12;
        color: white;
    }

    .btn-warning:hover {
        background: #d68910;
    }

    .btn-info {
        background: #17a2b8;
        color: white;
    }

    .btn-info:hover {
        background: #138496;
    }

    /* Table Styles */
    .inventory-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .inventory-table th {
        background: #f8f9fa;
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: #2c3e50;
        border-bottom: 2px solid #eee;
    }

    .inventory-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }

    .inventory-table tr:hover {
        background: #f8f9fa;
    }

    .table-category {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-actions {
        display: flex;
        gap: 5px;
        justify-content: center;
    }
    
    .action-btn {
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 5px;
        justify-content: center;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .action-btn:active {
        transform: translateY(0);
    }
    
    .action-btn-view {
        background: #e0f2fe;
        color: #0369a1;
        border-color: #bae6fd;
    }
    
    .action-btn-edit {
        background: #fef3c7;
        color: #92400e;
        border-color: #fde68a;
    }
    
    .action-btn-history {
        background: #e9d5ff;
        color: #6b21a8;
        border-color: #d8b4fe;
    }
    
    .action-btn-stock {
        background: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }
    
    .action-btn-delete {
        background: #fee2e2;
        color: #dc2626;
        border-color: #fecaca;
    }
    
    .action-btn-view:hover {
        background: #bae6fd;
    }
    
    .action-btn-edit:hover {
        background: #fde68a;
    }
    
    .action-btn-history:hover {
        background: #d8b4fe;
    }
    
    .action-btn-stock:hover {
        background: #bbf7d0;
    }
    
    .action-btn-delete:hover {
        background: #fecaca;
    }
    .quantity-control {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .quantity-btn {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 1px solid #ddd;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .quantity-btn:hover {
        background: #f8f9fa;
        border-color: #3498db;
    }

    .quantity-value {
        min-width: 40px;
        text-align: center;
        font-weight: 600;
    }

    /* Tabs */
    .tabs {
        display: flex;
        gap: 5px;
        margin-bottom: 20px;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
    }

    .tab-btn {
        padding: 10px 20px;
        background: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
        color: #666;
        border-radius: 6px 6px 0 0;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .tab-btn.active {
        background: #3498db;
        color: white;
    }

    .tab-btn:hover:not(.active) {
        background: #f8f9fa;
        color: #3498db;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Transaction Log Styles */
    .transaction-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .transaction-table th {
        background: #f8f9fa;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #2c3e50;
        border-bottom: 2px solid #eee;
    }

    .transaction-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }

    .transaction-table tr:hover {
        background: #f8f9fa;
    }

    .transaction-type {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .type-in {
        background: #d5f4e6;
        color: #2ecc71;
    }

    .type-out {
        background: #fde8e8;
        color: #e74c3c;
    }

    .type-adjust {
        background: #fef5e7;
        color: #f39c12;
    }

    .log-time {
        color: #666;
        font-size: 0.9rem;
    }

    .log-user {
        color: #3498db;
        font-weight: 600;
    }

    .filter-controls {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-controls select,
    .filter-controls input {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.95rem;
    }

    .badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-primary {
        background: #e3f2fd;
        color: #3498db;
    }

    .badge-success {
        background: #d5f4e6;
        color: #2ecc71;
    }

    .badge-warning {
        background: #fef5e7;
        color: #f39c12;
    }

    .badge-danger {
        background: #fde8e8;
        color: #e74c3c;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        width: 90%;
        max-width: 800px;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .modal-header h3 {
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #999;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .close-modal:hover {
        color: #e74c3c;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #555;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-control {
        width: 100%;
        padding: 8px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
        transition: border 0.3s ease;
    }
    .category-card {
        display: flex;
        justify-content: space-between;
        padding: 5px;
        border-bottom: 1px solid #f2f2f2;
    }

    .form-control:focus {
        border-color: #3498db;
        outline: none;
        box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .color-input-group {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .color-input-group input[type="color"] {
        width: 50px;
        height: 50px;
        padding: 0;
        border: 2px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
    }

    .category-list-container {
        max-height: 300px;
        overflow-y: auto;
        margin-top: 10px;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 10px;
    }

    .category-item-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 15px;
        margin-bottom: 8px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid;
        transition: all 0.3s ease;
    }

    .category-item-card:hover {
        background: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .action-icon {
        padding: 6px 12px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .content-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .header-buttons {
            width: 100%;
            justify-content: flex-start;
        }

        .inventory-table,
        .transaction-table {
            display: block;
            overflow-x: auto;
        }

        .tabs {
            flex-wrap: wrap;
        }

        .filter-controls {
            flex-direction: column;
            align-items: flex-start;
        }

        .modal-content {
            padding: 20px;
        }
    }
    [data-bs-dismiss="modal"] {
        display: none !important;
    }
    .close {
        display: none;
    }

    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        z-index: 99999 !important; /* Increased z-index */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        animation: slideIn 0.3s ease;
        min-width: 250px;
        max-width: 350px;
        font-size: 14px;
    }

    .notification.success {
        background: #2ecc71;
        border-left: 4px solid #27ae60;
    }

    .notification.error {
        background: #e74c3c;
        border-left: 4px solid #c0392b;
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

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    .action-buttons{
        display: flex;
        gap: 5px;
    }
</style>

<div class="container-fluid">
    <div class="dashboard">
        <div class="main-content">
            <div class="content-header">
                <h2>
                    <i class="fas fa-boxes"></i> Inventory Management System
                </h2>
                <div class="header-buttons">
                    <button class="btn btn-warning" id="manageCategoriesBtn">
                        <i class="fas fa-folder"></i> Manage Categories
                    </button>
                    <button class="btn btn-primary" id="addItemBtn">
                        <i class="fas fa-plus-circle"></i> Add New Item
                    </button>
                </div>
            </div>
            <div class="tabs">
                <button
                    class="tab-btn active"
                    data-tab="inventory-tab"
                    id="viewInventoryBtn"
                >
                    <i class="fas fa-boxes"></i> Inventory Items
                </button>
                <button class="tab-btn" data-tab="logs-tab" id="viewLogsBtn">
                    <i class="fas fa-history"></i> Stock Transactions
                </button>
                <button
                    class="tab-btn"
                    data-tab="changes-tab"
                    id="viewChangesBtn"
                >
                    <i class="fas fa-edit"></i> Activity Logs
                </button>
            </div>

            <!-- Inventory Items Tab -->
            <div class="tab-content active" id="inventory-tab">
                <div class="table-responsive">
                    <table class="table inventory-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Item Name</th>
                            <th>Code</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="inventoryTableBody">
                        <!-- Items will be dynamically added here --> 
                    </tbody>
                </table>
                </div>
            </div>

            <!-- Transaction Logs Tab -->
            <div class="tab-content" id="logs-tab">
                <div class="filter-controls">
                    <select
                        id="logTypeFilter"
                        class="form-control"
                        style="width: 150px"
                    >
                        <option value="all">All Types</option>
                        <option value="in">Stock In</option>
                        <option value="out">Stock Out</option>

                    </select>

                    <select
                        id="logCategoryFilter"
                        class="form-control"
                        style="width: 200px"
                    >
                        <option value="all">All Categories</option>
                    </select>

                    <input
                        type="date"
                        id="logDateFilter"
                        class="form-control"
                        style="width: 150px"
                    />
                    <button class="btn btn-primary" id="clearLogFilters">
                        <i class="fas fa-times"></i> Clear Filters
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table transaction-table">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Transaction Type</th>
                                <th>Item Details</th>
                                <th>Quantity Change</th>
                                <th>New Quantity</th>
                                <th>Performed By</th>
                                <th>Reason/Notes</th>
                            </tr>
                        </thead>
                        <tbody id="transactionLogsBody">
                            <!-- Transaction logs will be dynamically added here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Change Logs Tab -->
            <div class="tab-content" id="changes-tab">
                <div class="filter-controls">
                    <select
                        id="changeTypeFilter"
                        class="form-control"
                        style="width: 200px"
                    >
                        <option value="all">All Changes</option>
                        <option value="name">Name Changes</option>
                        <option value="price">Price Changes</option>
                        <option value="category">Category Changes</option>
                        <option value="description">Description Changes</option>
                    </select>

                    <select
                        id="changeCategoryFilter"
                        class="form-control"
                        style="width: 200px"
                    >
                        <option value="all">All Categories</option>
                    </select>

                    <input
                        type="date"
                        id="changeDateFilter"
                        class="form-control"
                        style="width: 150px"
                    />

                    <button class="btn btn-primary" id="clearChangeFilters">
                        <i class="fas fa-times"></i> Clear Filters
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table transaction-table">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Changes Made</th>
    
                                <th>Before</th>
                                <th>After</th>
                                <th>Changed By</th>
                            </tr>
                        </thead>
                        <tbody id="changeLogsBody">
                            <!-- Change logs will be dynamically added here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Item Modal -->
<div class="modal" id="itemModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <i class="fas fa-box"></i>
                <span id="modalTitle">Add New Item</span>
            </h3>
            <button class="close-modal" id="closeItemModal">&times;</button>
        </div>
        <form id="itemForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="itemCategory"
                        ><i class="fas fa-list"></i> Category</label
                    >
                    <select id="itemCategory" class="form-control" required>
                        <option value="">Select a category</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="itemSubCategory"
                        ><i class="fas fa-list-ol"></i> Sub Category</label
                    >
                    <select id="itemSubCategory" class="form-control">
                        <option value="">
                            Select a sub-category (optional)
                        </option>
                    </select>
                </div>
            </div>

            <!-- UNIFORM EXTRA FIELDS -->
            <div class="form-row" id="uniformFields" style="display: none">
                <div class="form-group">
                    <label><i class="fas fa-user-graduate"></i> For</label>
                    <select id="uniformFor" class="form-control">
                        <option value="">Select</option>
                        <option value="Student">Student</option>
                        <option value="Staff">Staff</option>
                    </select>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-venus-mars"></i> Gender</label>
                    <select id="uniformGender" class="form-control">
                        <option value="">Select</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Unisex">Unisex</option>
                    </select>
                </div>
            </div>

            <div class="form-group" id="uniformSizeField" style="display: none">
                <label><i class="fas fa-ruler"></i> Size</label>
                <input
                    type="text"
                    id="uniformSize"
                    class="form-control"
                    placeholder="Example: S, M, L, XL or 28, 30"
                />
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="itemName"
                        ><i class="fas fa-tag"></i> Item Name</label
                    >
                    <input
                        type="text"
                        id="itemName"
                        class="form-control"
                        placeholder="Enter item name"
                        required
                    />
                </div>

                <div class="form-group">
                    <label for="itemCode"
                        ><i class="fas fa-barcode"></i> Item Code</label
                    >
                    <input
                        type="text"
                        id="itemCode"
                        class="form-control"
                        placeholder="Enter item code"
                    />
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="itemQuantity">
                        <i class="fas fa-cubes"></i> Quantity
                    </label>
                    <input
                        type="number"
                        id="itemQuantity"
                        class="form-control"
                        placeholder="Enter quantity"
                        min="0"
                        required
                    />
                </div>

                <div class="form-group">
                    <label for="itemPrice">
                        <i class="fas fa-rupee-sign"></i> Price
                    </label>
                    <input
                        type="number"
                        id="itemPrice"
                        class="form-control"
                        placeholder="Enter price"
                        min="0"
                        step="0.01"
                    />
                </div>
            </div>

            <div class="form-group">
                <label for="itemDescription"
                    ><i class="fas fa-align-left"></i> Description</label
                >
                <textarea
                    id="itemDescription"
                    class="form-control"
                    placeholder="Enter item description"
                    rows="3"
                ></textarea>
            </div>

            <div class="form-group">
                <label for="itemThreshold"
                    ><i class="fas fa-exclamation-triangle"></i> Low Stock
                    Threshold</label
                >
                <input
                    type="number"
                    id="itemThreshold"
                    class="form-control"
                    placeholder="Alert when stock is below this number"
                    min="1"
                    value="10"
                />
            </div>

            <div
                class="form-group"
                style="display: flex; gap: 10px; margin-top: 30px"
            >
                <button type="submit" class="btn btn-success" style="flex: 1">
                    <i class="fas fa-save"></i> Save
                </button>
                <button
                    type="button"
                    class="btn btn-danger"
                    id="cancelItemBtn"
                    style="flex: 1"
                >
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Stock In/Out Modal -->
<div class="modal" id="stockModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <i class="fas fa-exchange-alt"></i>
                <span id="stockModalTitle">Stock Adjustment</span>
            </h3>
            <button class="close-modal" id="closeStockModal">&times;</button>
        </div>
        <form id="stockForm">
            <input type="hidden" id="stockItemId" />

            <div class="form-group">
                <label><i class="fas fa-box"></i> Item</label>
                <input
                    type="text"
                    id="stockItemName"
                    class="form-control"
                    readonly
                />
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label
                        ><i class="fas fa-arrows-alt-v"></i> Current
                        Quantity</label
                    >
                    <input
                        type="number"
                        id="stockCurrentQuantity"
                        class="form-control"
                        readonly
                    />
                </div>

                <div class="form-group">
                    <label
                        ><i class="fas fa-balance-scale"></i> Transaction
                        Type</label
                    >
                    <select id="stockType" class="form-control" required>
                        <option value="in">Stock In (Add)</option>
                        <option value="out">Stock Out (Remove)</option>
                        <!-- <option value="adjust">Adjustment</option> -->
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-calculator"></i> Quantity Change</label>
                <input
                    type="number"
                    id="stockQuantityChange"
                    class="form-control"
                    placeholder="Enter quantity"
                    min="1"
                    required
                />
            </div>

            <div class="form-group">
                <label><i class="fas fa-sticky-note"></i> Reason / Notes</label>
                <textarea
                    id="stockNotes"
                    class="form-control"
                    placeholder="Enter reason for this transaction (optional)"
                    rows="3"
                ></textarea>
            </div>

            <div class="form-group">
                <label><i class="fas fa-user"></i> Performed By</label>
                <input
                    type="text"
                    id="stockPerformedBy"
                    class="form-control"
                    placeholder="Enter your name"
                    required
                />
            </div>

            <div
                class="form-group"
                style="display: flex; gap: 10px; margin-top: 30px"
            >
                <button type="submit" class="btn btn-success" style="flex: 1">
                    <i class="fas fa-check"></i> Confirm
                </button>
                <button
                    type="button"
                    class="btn btn-danger"
                    id="cancelStockBtn"
                    style="flex: 1"
                >
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Category Management Modal -->
<div class="modal" id="categoryModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-folder"></i> Manage Categories</h3>
            <button class="close-modal" id="closeCategoryModal">&times;</button>
        </div>

        <div style="margin-bottom: 30px">
            <h4><i class="fas fa-list"></i> Existing Categories</h4>
            <div id="categoryList" class="category-list-container">
                <!-- Categories will be listed here -->
            </div>
        </div>

        <hr />

        <h4 id="categoryFormHeading"><i class="fas fa-plus-circle"></i> Add New Category</h4>
        <form id="categoryForm">
            <div class="form-group">
                <label for="categoryName"
                    ><i class="fas fa-tag"></i> Category Name</label
                >
                <input
                    type="text"
                    name="name"
                    id="categoryName"
                    class="form-control"
                    placeholder="Enter category name"
                    required
                />
            </div>

            <div class="form-group">
                <input
                    type="hidden"
                    name="edit_id"
                    id="editCategoryId"
                    class="form-control"
                    placeholder="Enter category name"
                />
            </div>

            <div class="form-group">
                <label for="categoryIcon"
                    ><i class="fas fa-icons"></i> Icon</label
                >
                <select id="categoryIcon" name="icon" class="form-control">
                    <option value="fa-box">Default (Box)</option>
                    <option value="fa-tshirt">Clothing</option>
                    <option value="fa-file-alt">Documents</option>
                    <option value="fa-pencil-alt">Stationery</option>
                    <option value="fa-futbol">Sports</option>
                    <option value="fa-flask">Lab</option>
                    <option value="fa-laptop">Electronics</option>
                    <option value="fa-book">Books</option>
                    <option value="fa-tools">Tools</option>
                    <option value="fa-medkit">Medical</option>
                    <option value="fa-utensils">Food</option>
                    <option value="fa-chair">Furniture</option>
                </select>
            </div>

            <div class="form-group">
                <label for="categoryColor"
                    ><i class="fas fa-palette"></i> Color</label
                >
                <div class="color-input-group">
                    <input
                        type="color"
                        id="categoryColor"
                        name="color"
                        value="#3498db"
                    />
                    <span id="colorHex">#3498db</span>
                </div>
            </div>

            <div class="form-group" id="subcategorySection">
                <label
                    ><i class="fas fa-list-ol"></i> Sub Categories
                    (Optional)</label
                >
                <div id="subcategoriesContainer">
                    <!-- Subcategories will be added here -->
                </div>
                <button
                    type="button"
                    class="btn btn-primary"
                    id="addSubcategoryBtn"
                    style="margin-top: 10px; padding: 8px 12px"
                >
                    <i class="fas fa-plus"></i> Add Sub Category
                </button>
            </div>

            <div
                class="form-group"
                style="display: flex; gap: 10px; margin-top: 30px"
            >
                <button type="submit" class="btn btn-success" style="flex: 1">
                    <i class="fas fa-save"></i> Save
                </button>
                <button
                    type="button"
                    class="btn btn-danger"
                    id="cancelCategoryBtn"
                    style="flex: 1"
                >
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal" id="editCategoryModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Edit Category</h3>
            <button class="close-modal" id="closeEditCategoryModal">
                &times;
            </button>
        </div>
        <form id="editCategoryForm">
            <input type="hidden" id="editCategoryId" />
            <div class="form-group">
                <label for="editCategoryName"
                    ><i class="fas fa-tag"></i> Category Name</label
                >
                <input
                    type="text"
                    id="editCategoryName"
                    class="form-control"
                    placeholder="Enter category name"
                    required
                />
            </div>

            <div class="form-group">
                <label for="editCategoryIcon"
                    ><i class="fas fa-icons"></i> Icon</label
                >
                <select id="editCategoryIcon" class="form-control">
                    <option value="fa-box">Default (Box)</option>
                    <option value="fa-tshirt">Clothing</option>
                    <option value="fa-file-alt">Documents</option>
                    <option value="fa-pencil-alt">Stationery</option>
                    <option value="fa-futbol">Sports</option>
                    <option value="fa-flask">Lab</option>
                    <option value="fa-laptop">Electronics</option>
                    <option value="fa-book">Books</option>
                    <option value="fa-tools">Tools</option>
                    <option value="fa-medkit">Medical</option>
                    <option value="fa-utensils">Food</option>
                    <option value="fa-chair">Furniture</option>
                </select>
            </div>

            <div class="form-group">
                <label for="editCategoryColor"
                    ><i class="fas fa-palette"></i> Color</label
                >
                <div class="color-input-group">
                    <input
                        type="color"
                        id="editCategoryColor"
                        value="#3498db"
                    />
                    <span id="editColorHex">#3498db</span>
                </div>
            </div>

            <div class="form-group" id="editSubcategorySection">
                <label><i class="fas fa-list-ol"></i> Sub Categories</label>
                <div id="editSubcategoriesContainer">
                    <!-- Subcategories will be added here -->
                </div>
                <button
                    type="button"
                    class="btn btn-primary"
                    id="addEditSubcategoryBtn"
                    style="margin-top: 10px; padding: 8px 12px"
                >
                    <i class="fas fa-plus"></i> Add Sub Category
                </button>
            </div>

            <div
                class="form-group"
                style="display: flex; gap: 10px; margin-top: 30px"
            >
                <button type="submit" class="btn btn-success" style="flex: 1">
                    <i class="fas fa-save"></i> Update Category
                </button>
                <button
                    type="button"
                    class="btn btn-danger"
                    id="cancelEditCategoryBtn"
                    style="flex: 1"
                >
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Item History Modal - Updated with Tabs -->
<div class="modal" id="historyModal">
    <div class="modal-content" style="max-width: 928px">
        <div class="modal-header">
            <h3>
                <i class="fas fa-history"></i>
                <span id="historyModalTitle">Item History</span>
            </h3>
            <button class="close-modal" id="closeHistoryModal">&times;</button>
        </div>

        <div
            class="item-info-summary"
            style="
                margin-bottom: 20px;
                padding: 15px;
                background: #f8f9fa;
                border-radius: 8px;
            "
        >
            <div
                style="
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                "
            >
                <div>
                    <h4 id="historyItemName" style="margin: 0 0 5px 0"></h4>
                    <div
                        id="historyItemDetails"
                        style="color: #666; font-size: 0.9rem"
                    ></div>
                </div>
                <div style="text-align: right">
                    <div style="font-size: 0.9rem; color: #666">
                        Current Stock
                    </div>
                    <div
                        id="historyCurrentQuantity"
                        style="
                            font-size: 1.5rem;
                            font-weight: 600;
                            color: #2c3e50;
                        "
                    ></div>
                </div>
            </div>
        </div>

        <!-- Tabs for History Modal -->
        <div class="tabs" style="margin-bottom: 20px">
            <button
                class="tab-btn history-tab active"
                data-tab="stock-history-tab"
            >
                <i class="fas fa-exchange-alt"></i> Stock Transactions
            </button>
            <button class="tab-btn history-tab" data-tab="change-history-tabs">
                <i class="fas fa-edit"></i> Change Logs
            </button>
        </div>

        <!-- Stock History Tab -->
        <div
            class="tab-content history-tab-content active"
            id="stock-history-tab"
        >
            <div class="filter-controls" style="margin-bottom: 15px">
                <select
                    id="modal-stock-history-type-filter"
                    class="form-control"
                    style="width: 150px"
                >
                    <option value="all">All Types</option>
                    <option value="in">Stock In</option>
                    <option value="out">Stock Out</option>
                </select>
                <input
                    type="date"
                    id="modal-stock-history-date-filter"
                    class="form-control"
                    style="width: 150px"
                />
                <button
                    class="btn btn-primary"
                    id="modal-clear-stock-history-filters"
                    style="padding: 8px 12px"
                >
                    <i class="fas fa-times"></i> Clear Filters
                </button>
            </div>

            <div style="max-height: 300px; overflow-y: auto">
                <div class="table-responsive">
                    <table class="table transaction-table">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Type</th>
                                <th>Quantity Change</th>
                                <th>Stock Before</th>
                                <th>Stock After</th>
                                <th>Performed By</th>
                                <!-- <th>Notes</th> -->
                            </tr>
                        </thead>
                        <tbody id="stockHistoryBody">
                            <!-- Stock history will be loaded here -->
                        </tbody>
                    </table>
                </div>

                <div
                    id="noStockHistoryMessage"
                    style="
                        display: none;
                        text-align: center;
                        padding: 40px;
                        color: #999;
                    "
                >
                    <i
                        class="fas fa-exchange-alt"
                        style="
                            font-size: 2.5rem;
                            margin-bottom: 15px;
                            display: block;
                        "
                    ></i>
                    <h4>No stock transactions found</h4>
                    <p>This item has no stock transactions yet.</p>
                </div>
            </div>
        </div>

        <!-- Change History Tab -->
        <div class="tab-content history-tab-content" id="change-history-tabs">
            <div class="filter-controls" style="margin-bottom: 15px">
                <select
                    id="modal-change-history-type-filter"
                    class="form-control"
                    style="width: 200px"
                >
                    <option value="all">All Changes</option>
                    <option value="name">Name Changes</option>
                    <option value="code">Code Changes</option>
                    <option value="price">Price Changes</option>
                    <option value="category">Category Changes</option>
                    <option value="description">Description Changes</option>
                    <option value="threshold">Threshold Changes</option>
                </select>
                <input
                    type="date"
                    id="modal-change-history-date-filter"
                    class="form-control"
                    style="width: 150px"
                />
                <button
                    class="btn btn-primary"
                    id="modal-clear-change-history-filters"
                    style="padding: 8px 12px"
                >
                    <i class="fas fa-times"></i> Clear Filters
                </button>
            </div>

            <div style="max-height: 300px; overflow-y: auto">
                <div class="table-responsive">
                    <table class="table transaction-table">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Changed By</th>
                                <th>Changes Made</th>
                                <th>Before</th>
                                <th>After</th>
                            </tr>
                        </thead>
                        <tbody id="changeHistoryBody">
                            <!-- Change history will be loaded here -->
                        </tbody>
                    </table>
                </div>

                <div
                    id="noChangeHistoryMessage"
                    style="
                        display: none;
                        text-align: center;
                        padding: 40px;
                        color: #999;
                    "
                >
                    <i
                        class="fas fa-edit"
                        style="
                            font-size: 2.5rem;
                            margin-bottom: 15px;
                            display: block;
                        "
                    ></i>
                    <h4>No change logs found</h4>
                    <p>This item has no change logs yet.</p>
                </div>
            </div>
        </div>

        <!-- <div style="margin-top: 20px; text-align: center;">
            <button class="btn btn-primary" id="closeHistoryBtn">
                <i class="fas fa-times"></i> Close
            </button>
        </div> -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    /* =========================================================
    GLOBAL STATE
    ========================================================= */
    let inventory = [];
    let categories = [];
    let categorySubcategories = {};
    let transactionLogs = [];
    let transactions = [];
    let activities = [];
    let editingItemId = null;

    /* =========================================================
    CSRF SETUP
    ========================================================= */
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
    });

    /* =========================================================
    INIT
    ========================================================= */
    $(document).ready(function () {
        fetchCategories();
        fetchInventory();
        // fetchLogs();
        fetchActivities();
        fetchTransactions();
        setupTabs();

        const savedTab = localStorage.getItem("activeTab");
        if (savedTab) {
            $(".tab-btn").removeClass("active");
            $(".tab-content").removeClass("active");
            $(`.tab-btn[data-tab="${savedTab}"]`).addClass("active");
            $(`#${savedTab}`).addClass("active");
        }
    });

    /* =========================================================
    MODAL HELPERS
    ========================================================= */
    function openModal(id) {
        $("#" + id).css("display", "flex");
        $("body").addClass("modal-open");
    }

    function closeModal(id) {
        $("#" + id).hide();
        $("body").removeClass("modal-open");
    }

    $(document).on("click", ".close-modal", function () {
        closeModal($(this).closest(".modal").attr("id"));
    });

    $(".modal").on("click", function (e) {
        if (e.target === this) closeModal(this.id);
    });

    /* =========================================================
    VALIDATION HELPERS
    ========================================================= */
    function isDuplicateItem(name, code, ignoreId = null) {
        name = name.trim().toLowerCase();
        code = code?.trim().toLowerCase();

        return inventory.find((item) => {
            if (ignoreId && item.id == ignoreId) return false;

            const sameName = item.name.toLowerCase() === name;
            const sameCode =
                code && item.code && item.code.toLowerCase() === code;

            return sameName || sameCode;
        });
    }

    /* =========================================================
CATEGORY VALIDATION
========================================================= */
    function isDuplicateCategory(name, ignoreId = null) {
        name = name.trim().toLowerCase();

        return categories.some((cat) => {
            if (ignoreId && cat.id == ignoreId) return false;
            return cat.name.toLowerCase() === name;
        });
    }

    function showError(msg) {
        showNotification(msg, "error");
    }

    /* =========================================================
    BUTTONS
    ========================================================= */
    $("#addItemBtn").on("click", () => {
        editingItemId = null;
        $("#itemForm")[0].reset();
        $("#itemSubCategory").html(
            '<option value="">Select sub-category</option>'
        );
        openModal("itemModal");
    });

    $("#manageCategoriesBtn").on("click", () => {
        $("#categoryForm")[0].reset();
        $("#subcategoriesContainer").empty();
        $("#editCategoryId").val('');
        openModal("categoryModal");
    });

    /* =========================================================
    FETCH CATEGORIES
    ========================================================= */
    function fetchCategories(callback = null) {
        $.get("/institute/admin/inventory/categories", (res) => {
            categories = res.categories || [];
            categorySubcategories = {};
    
            $("#itemCategory").html(
                '<option value="">Select category</option>'
            );
    
            categories.forEach((cat) => {
                $("#itemCategory").append(
                    `<option value="${cat.id}">${cat.name}</option>`
                );
    
                categorySubcategories[cat.id] = cat.subcategories || [];
            });
    
            renderCategoryList();
    
            // ✅ Run callback after categories load
            if (callback) callback();
        });
    }

    /* =========================================================
    CATEGORY → SUBCATEGORY
    ========================================================= */
    $("#itemCategory").on("change", function () {
        const categoryId = this.value;
        const sub = $("#itemSubCategory");

        sub.empty().append(
            '<option value="">Select sub-category (optional)</option>'
        );

        if (!categorySubcategories[categoryId]) return;

        categorySubcategories[categoryId].forEach((s) => {
            sub.append(`<option value="${s}">${s}</option>`);
        });
    });

    /* =========================================================
    SUBCATEGORY ADD / REMOVE
    ========================================================= */
    $("#addSubcategoryBtn").on("click", function () {
        $("#subcategoriesContainer").append(`
            <div class="subcategory-row" style="display:flex;gap:8px;margin-bottom:6px">
                <input type="text" name="subcategories[]" class="form-control" placeholder="Subcategory" required>
                <button type="button" class="btn btn-danger remove-subcategory">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `);
    });

    $(document).on("click", ".remove-subcategory", function () {
        $(this).closest(".subcategory-row").remove();
    });

    $("#categoryForm").on("submit", function (e) {
        e.preventDefault();
    
        const categoryName = $("#categoryName").val().trim();
        const editId = $("#editCategoryId").val(); // ✅ Get from hidden input field
    
        if (!categoryName) {
            showError("Category name is required!");
            return;
        }
    
        // Validate duplicate category name
        if (isDuplicateCategory(categoryName, editId)) {
            Swal.fire({
                icon: 'error',
                title: 'Duplicate Category!',
                html: `
                    <div style="text-align: left; padding: 10px;">
                        <p>A category with name <strong>"${categoryName}"</strong> already exists!</p>
                        <p style="color: #e74c3c;">
                            <i class="fas fa-exclamation-triangle"></i> 
                            Please use a different category name.
                        </p>
                    </div>
                `,
                confirmButtonText: 'Okay',
                confirmButtonColor: '#3498db'
            });
            return;
        }
    
        const data = $(this).serialize();
    
        // Note: The edit_id is already in the form data from the hidden input
        // So you don't need to dynamically add it again
        
        // However, if you want to keep the dynamic approach, make sure not to duplicate
        if (editId && !data.includes('edit_id=')) {
            $("<input>")
                .attr({
                    type: "hidden",
                    name: "edit_id",
                    value: editId,
                })
                .appendTo(this);
        }
    
        $.post("/institute/admin/inventory/category/store", data)
            .done((res) => {
                showNotification(res.message || "Category saved successfully!");
                closeModal("categoryModal");
                $("#editCategoryId").val('');
                // window.location.reload();
                // Refresh UI dynamically
                fetchCategories(() => {
                    fetchInventory();
                });
                fetchTransactions();
                fetchActivities();
            })
            .fail((xhr) => {
                showError(
                    xhr.responseJSON?.message || "Failed to save category"
                );
            });
    });

    /* =========================================================
    FETCH INVENTORY
    ========================================================= */
    function fetchInventory() {
        $.get("/institute/admin/inventory/items", (res) => {
            inventory = res.items || [];
            renderItems();
        });
    }

    /* =========================================================
    FETCH LOGS
    ========================================================= */
    function fetchTransactions() {
        $.get("/institute/admin/inventory/transactions", (res) => {
            transactions = Array.isArray(res) ? res : [];
            renderTransactions();
        });
    }

    function fetchActivities() {
        $.get("/institute/admin/inventory/logs", (res) => {
            activities = res.data; // ✅ CORRECT
            renderActivities();
        });
    }

    /* =========================================================
    TABS
    ========================================================= */
    function setupTabs() {
        $(document).on("click", ".tab-btn:not(.history-tab)", function (e) {
            e.preventDefault();
            e.stopPropagation();

            const tabId = $(this).data("tab");

            // ADD THIS LINE - Save active tab
            localStorage.setItem("activeTab", tabId);

            window.location.href = window.location.pathname + "?tab=" + tabId;
        });
    }

    /* =========================================================
    RENDER INVENTORY
    ========================================================= */
    function renderItems() {
        const tbody = $("#inventoryTableBody").empty();

        if (!inventory.length) {
            tbody.append(
                '<tr><td colspan="7" style="text-align:center">No items found</td></tr>'
            );
            return;
        }

        inventory.forEach((item) => {
            const cat = categories.find(
                (c) => Number(c.id) === Number(item.category_id)
            );
            const low = item.quantity <= item.low_stock_threshold;

            tbody.append(`
                <tr>
                    <td>
                        <div class="table-category">
                            <i class="fas ${cat?.icon || "fa-box"}" style="color: ${
                        cat?.color || "#3498db"
                                 }"></i>

                            <div>
                                <strong>${cat?.name || "Uncategorized"}</strong>
                                ${
                                    item?.subcategory
                                        ? `<div><span class="badge badge-primary">${item.subcategory}</span></div>`
                                        : ""
                                }
                            </div>
                        </div>
                   </td>
                    <td><strong>${item.name}</strong></td>
                    <td>${item.code || "-"}</td>
                    <td>${item.quantity}</td>
                    <td>${item.price ? "₹" + item.price : "-"}</td>
                    <td>
                        <span class="badge ${
                            low ? "badge-warning" : "badge-success"
                        }">
                            ${low ? "Low Stock" : "In Stock"}
                        </span>
                    </td>
                    <td class="action-button">
                        <div class="table-actions">
                            <button class="action-btn action-btn-view d-block" onclick="openHistoryModal(${
                                item.id
                            }, event); return false;" title="View History">
                                <div>
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div>
                                    <span class="d-none d-md-inline">View</span>
                                </div>
                            </button>
                            <button class="action-btn action-btn-stock d-block" onclick="openStockModal(${
                                item.id
                            })" title="Stock Adjustment">
                               <div>
                                    <i class="fas fa-exchange-alt"></i>
                                </div>
                                <div>
                                    <span class="d-none d-md-inline">Stock</span>
                                </div>

                            </button>
                            <button class="action-btn action-btn-edit d-block" onclick="editItem(${
                                item.id
                            })" title="Edit Item">
                                
                                
                               <div>
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div>
                                    <span class="d-none d-md-inline">Edit</span>
                                </div>                                
                            </button>
                            <button class="action-btn action-btn-delete d-block" onclick="deleteItem(${
                                item.id
                            })" title="Delete Item">
                                
                                
                               <div>
                                    <i class="fas fa-trash"></i>
                                </div>
                                <div>
                                    <span class="d-none d-md-inline">Delete</span>
                                </div>                                
                            </button>
                        </div>
                    </td>
                </tr>
            `);
        });
    }

    function openStockModal(itemId) {
        const item = inventory.find((i) => i.id === itemId);
        if (!item) return;

        // Fill modal fields
        $("#stockItemName").val(item.name); // <-- .val() for input
        $("#stockItemId").val(item.id);
        $("#stockCurrentQuantity").val(item.quantity);

        // Optional: clear previous inputs
        $("#stockQuantityChange").val("");
        $("#stockNotes").val("");
        // $('#stockPerformedBy').val(authUserName || '');

        // Open modal
        openModal("stockModal");
    }

    $("#stockForm").on("submit", function (e) {
        e.preventDefault();

        const itemId = $("#stockItemId").val();
        const type = $("#stockType").val();
        const quantity = parseInt($("#stockQuantityChange").val());
        const notes = $("#stockNotes").val();
        const performedBy = $("#stockPerformedBy").val();

        $.post(`/institute/admin/inventory/item/${itemId}/stock`, {
            // ✅ correct route
            type: type,
            quantity: quantity,
            notes: notes,
            performed_by: performedBy,
        })
            .done((res) => {
                showNotification("Stock updated");
                closeModal("stockModal");
                fetchInventory();
            })
            .fail((xhr) => {
                showError(xhr.responseJSON?.message || "Stock update failed");
            });
    });

    /* =========================================================
    ITEM CRUD WITH VALIDATION
    ========================================================= */
    $("#itemForm").on("submit", function (e) {
        e.preventDefault();

        const name = $("#itemName").val().trim();
        const code = $("#itemCode").val().trim();

        if (!name) {
            showError("Item name is required!");
            return;
        }

        // Validate duplicate item name
        const duplicateFound = isDuplicateItem(name, code, editingItemId);
        if (duplicateFound) {
            if (name.toLowerCase() === duplicateFound.name.toLowerCase()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Item Name!',
                    html: `
                        <div style="text-align: left; padding: 10px;">
                            <p>An item with name <strong>"${duplicateFound.name}"</strong> already exists!</p>
                            <p style="color: #e74c3c;">
                                <i class="fas fa-exclamation-triangle"></i> 
                                Please use a different item name.
                            </p>
                        </div>
                    `,
                    confirmButtonText: 'Okay',
                    confirmButtonColor: '#3498db'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Item Code!',
                    html: `
                        <div style="text-align: left; padding: 10px;">
                            <p>An item with code <strong>"${duplicateFound.code}"</strong> already exists!</p>
                            <p style="color: #e74c3c;">
                                <i class="fas fa-exclamation-triangle"></i> 
                                Please use a different item code.
                            </p>
                        </div>
                    `,
                    confirmButtonText: 'Okay',
                    confirmButtonColor: '#3498db'
                });
            }
            return;
        }
        const data = {
            category_id: $("#itemCategory").val(),
            subcategory: $("#itemSubCategory").val(),
            name,
            code: code || null,
            quantity: $("#itemQuantity").val(),
            price: $("#itemPrice").val(),
            description: $("#itemDescription").val(),
            low_stock_threshold: $("#itemThreshold").val(),
        };

        const url = editingItemId
            ? `/institute/admin/inventory/item/${editingItemId}`
            : `/institute/admin/inventory/item`;

        $.post(url, data)
            .done(() => {
                closeModal("itemModal");
                fetchInventory();
                showNotification(
                    editingItemId
                        ? "Item updated successfully!"
                        : "Item added successfully!"
                );
            })
            .fail((xhr) => {
                showError(xhr.responseJSON?.message || "Failed to save item");
            });
    });

    function editItem(id) {
        $.get(`/institute/admin/inventory/item/${id}`, (res) => {
            const i = res.item;
            editingItemId = id;

            // Wait for categories to be fully loaded
            if (Object.keys(categorySubcategories).length === 0) {
                // If categories aren't loaded yet, load them first
                $.get("/institute/admin/inventory/categories", (catRes) => {
                    categories = catRes.categories || [];
                    categorySubcategories = {};

                    categories.forEach((cat) => {
                        categorySubcategories[cat.id] = cat.subcategories || [];
                    });

                    // Now populate the form
                    populateEditForm(i);
                });
            } else {
                // Categories already loaded
                populateEditForm(i);
            }
        });
    }

    function populateEditForm(item) {
        // Set category
        $("#itemCategory").val(item.category_id);

        // Populate subcategories for this category
        const subSelect = $("#itemSubCategory");
        subSelect
            .empty()
            .append('<option value="">Select sub-category (optional)</option>');

        if (categorySubcategories[item.category_id]) {
            categorySubcategories[item.category_id].forEach((s) => {
                const isSelected = item.subcategory === s ? "selected" : "";
                subSelect.append(
                    `<option value="${s}" ${isSelected}>${s}</option>`
                );
            });
        }

        // Set other values
        $("#itemName").val(item.name);
        $("#itemCode").val(item.code);
        $("#itemQuantity").val(item.quantity);
        $("#itemPrice").val(item.price);
        $("#itemDescription").val(item.description);
        $("#itemThreshold").val(item.low_stock_threshold);

        openModal("itemModal");
    }

    function deleteItem(id) {
        if (!confirm("Delete item?")) return;

        $.post(`/institute/admin/inventory/item/${id}/delete`, () => {
            fetchInventory();
            showNotification("Item deleted");
        });
    }

    function renderCategoryList() {
        const c = $("#categoryList").empty();

        if (!categories.length) {
            c.append('<p class="text-muted">No categories found</p>');
            return;
        }

        categories.forEach((cat) => {
            c.append(`
                <div class="category-card">
                    <div>
                        <i class="fas ${cat.icon}" style="color:${
                            cat.color
                        }"></i>
                        <strong>${cat.name}</strong>
                    </div>

                    <div class="category-actions action-buttons">
                        <button class="btn btn-sm btn-primary action-icon"
                            onclick='openEditCategoryModal(${JSON.stringify(
                                cat
                            )})'>
                            <i class="fas fa-edit"></i>
                        </button>

                        <button class="btn btn-sm btn-danger action-icon"
                            onclick="deleteCategory(${cat.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `);
        });
    }

    function editCategory(id) {
        const cat = categories.find((c) => c.id === id);
        if (!cat) return;

        $("#categoryName").val(cat.name);
        $("#categoryIcon").val(cat.icon);
        $("#categoryColor").val(cat.color);

        $("#subcategoriesContainer").empty();
        (cat.subcategories || []).forEach((sub) => {
            $("#subcategoriesContainer").append(`
                <div class="subcategory-row" style="display:flex;gap:8px;margin-bottom:6px">
                    <input type="text" name="subcategories[]" class="form-control" value="${sub}">
                    <button type="button" class="btn btn-danger remove-subcategory">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `);
        });

        $("#categoryForm").data("edit-id", id);
        openModal("categoryModal");
    }

    function deleteCategory(id) {
        if (!confirm("Delete this category?")) return;

        $.post(
            `/institute/admin/inventory/category/${id}/delete`,
            function (res) {
                showNotification(res.message);
                fetchCategories();
            }
        ).fail((xhr) => {
            alert(xhr.responseJSON?.message || "Delete failed");
        });
    }

    function renderTransactions() {
        const tbody = $("#transactionLogsBody").empty();
        if (!transactions.length) {
            tbody.append(
                '<tr><td colspan="7" class="text-center">No transactions</td></tr>'
            );
            return;
        }
        transactions.forEach((i) => {
            tbody.append(`
                <tr>
                    <td>${i.created_at}</td>
                  <td>
                    <span style="
                        display: inline-block;
                        padding: 4px 12px;
                        font-size: 13px;
                        font-weight: 600;
                        border-radius: 999px;
                        background-color: ${
                            i.type === "in" ? "#eafaf1" : "#fdecea"
                        };
                        color: ${i.type === "in" ? "#2ecc71" : "#e74c3c"};
                    ">
                        ${i.type === "in" ? "Stock In" : "Stock Out"}
                    </span>
                </td>

                    <td>${i.item_name ?? "N/A"}</td>
                   <td>
                    <span style="font-weight: 600; color: ${
                        i.type === "in" ? "#2ecc71" : "#e74c3c"
                    }">
                        ${i.type === "in" ? "+" : "-"}${i.quantity_change}
                    </span>
                </td>

                    <td>
                        <strong>${i.new_quantity}</strong>
                        <div class="log-time">was ${i.previous_quantity}</div>
                    </td>
                    <td>${i.performed_by}</td>
                    <td>${i.notes ?? "-"}</td>
                </tr>
            `);
        });
    }

    function renderActivities() {
        const tbody = $("#changeLogsBody").empty();

        if (!activities || !activities.length) {
            tbody.append(`
            <tr>
                <td colspan="7" class="text-center text-muted">
                    No activity logs
                </td>
            </tr>
        `);
            return;
        }

        activities.forEach((a) => {
            // 🔹 Parse changes (string → object)
            let changesObj = null;
            if (a.changes) {
                // try {
                    changesObj =
                        typeof a.changes === "string" 
                            ? JSON.parse(a.changes)
                            : a.changes;
                // } catch (e) {
                //     console.error("Invalidata changes JSON", a.changes);
                // }
            }

            // 🔹 Changed fields
            const changeKeys = changesObj
                ? Object.keys(changesObj).filter(
                      (key) =>
                          key !== "category_id" &&
                          changesObj[key]?.from !== changesObj[key]?.to
                  )
                : [];

            const changedFields = changeKeys.length
                ? changeKeys.join("<br>")
                : "-";

            // 🔹 From / To columns
            let fromHtml = "-";
            let toHtml = "-";

            if (changeKeys.length) {
                fromHtml = changeKeys
                    .map(
                        (key) => `
                    <div>
                        <span style="color:#e74c3c">
                            ${changesObj[key].from ?? "-"}
                        </span>
                    </div>
                `
                    )
                    .join("");

                toHtml = changeKeys
                    .map(
                        (key) => `
                    <div>
                        <span style="color:#2ecc71">
                            ${changesObj[key].to ?? "-"}
                        </span>
                    </div>
                `
                    )
                    .join("");
            }

            tbody.append(`
            <tr>
                <td>${new Date(a.created_at).toLocaleString()}</td>
                <td><strong>${a.item_name ?? "-"}</strong></td>
                <td>${a.category_name ?? "-"}</td>
                <td> <strong>${changedFields}</strong></td>
                <td style="font-size:0.9rem">${fromHtml}</td>
                <td style="font-size:0.9rem">${toHtml}</td>
                <td>${a.performed_by ?? "System"}</td>
            </tr>
        `);
        });
    }

    let activeHistoryItemIds = null;

    $(document).on("click", ".history-tab", function () {
        $(".history-tab").removeClass("active");
        $(this).addClass("active");

        const tab = $(this).data("tab");

        $(".history-tab-content").removeClass("active");
        $("#" + tab).addClass("active");

        if (tab === "stock-history-tab") {
            loadStockHistory(activeHistoryItemIds);
        }

        if (tab === "change-history-tabs") {
            loadChangeLogs(activeHistoryItemIds);
        }
    });

    /* =========================================================
    HISTORY MODAL (FIX)
    ========================================================= */
    // function openHistoryModal(itemId) {
    //     event.stopPropagation();
    //     activeHistoryItemId = itemId; // ✅ REQUIRED

    //     const item = inventory.find(i => i.id === itemId);
    //     if (!item) return;

    //     $('#historyItemName').text(item.name);
    //     $('#historyItemDetails').text(item.code || '-');
    //     $('#historyCurrentQuantity').text(item.quantity);

    //     $('.history-tab[data-tab="stock-history-tab"]').click(); // default tab
    //     openModal('historyModal');
    // }

    function openHistoryModal(itemId) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        activeHistoryItemIds = itemId;
        const item = inventory.find((i) => i.id === itemId);
        if (!item) return;

        $("#historyItemName").text(item.name);
        $("#historyItemDetails").text(item.code || "-");
        $("#historyCurrentQuantity").text(item.quantity);

        // Don't trigger click on history tabs - just set active class directly
        $(".history-tab").removeClass("active");
        $(".history-tab-content").removeClass("active");

        $('.history-tab[data-tab="stock-history-tab"]').addClass("active");
        $("#stock-history-tab").addClass("active");

        openModal("historyModal");

        // ✅ Load BOTH tabs data immediately
        loadStockHistory(itemId);
        loadChangeLogs(itemId);
    }

    function loadStockHistory(itemId) {
        $("#stockHistoryBody").html(`<tr><td colspan="6">Loading...</td></tr>`);

        $.get(
            `/institute/admin/inventory/item/${itemId}/stock-history`,
            (res) => {
                const tbody = $("#stockHistoryBody").empty();

                if (!res.logs.length) {
                    $("#noStockHistoryMessage").show();
                    return;
                }

                $("#noStockHistoryMessage").hide();

                res.logs.forEach((log) => {
                    tbody.append(`
                    <tr>
                        <td>${new Date(log.created_at).toLocaleString()}</td>
                       <td>
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            font-size: 13px;
                            font-weight: 600;
                            border-radius: 999px;
                            background-color: ${
                                log.type === "in" ? "#eafaf1" : "#fdecea"
                            };
                            color: ${log.type === "in" ? "#2ecc71" : "#e74c3c"};
                        ">
                            ${log.type === "in" ? "Stock In" : "Stock Out"}
                        </span>
                    </td>

                        <td>
                        <span style="font-weight: 600; color: ${
                            log.type === "in" ? "#2ecc71" : "#e74c3c"
                        }">
                            ${log.type === "in" ? "+" : "-"}${
                        log.quantity_change
                    }
                        </span>
                    </td>

                        <td>${log.previous_quantity}</td>
                        <td><strong>${log.new_quantity}</strong></td>
                        <td>${log.performed_by}</td>
                    </tr>
                `);
                });
            }
        );
    }

    function loadChangeLogs(itemId) {
        $("#changeLogsBody").html(`<tr><td colspan="7">Loading...</td></tr>`);
        // ✅ Target the modal-specific container
        const container = $("#changeHistoryBody");
        container.html(`<tr><td colspan="5">Loading change logs...</td></tr>`);
        $.get(`/institute/admin/inventory/item/${itemId}/activity`, (res) => {
            const tbody = $("#changeLogsBody").empty();
            const logs = res.logs || [];

            if (!logs.length) {
                tbody.append(
                    `<tr><td colspan="7" class="text-center">No activity logs</td></tr>`
                );
                return;
            }

            logs.forEach((a) => {
                // Get changed keys excluding 'category_id'
                const changeKeys = Object.keys(a.changes || {}).filter(
                    (key) =>
                        key !== "category_id" &&
                        a.changes[key].from !== a.changes[key].to
                );

                let fromHtml = "-";
                let toHtml = "-";
                let changedFields = "-";

                if (changeKeys.length) {
                    changedFields = changeKeys.join("<br>");

                    fromHtml = changeKeys
                        .map((key) => {
                            const val = a.changes[key];
                            return `<div><span style="color:red">${
                                val.from ?? "-"
                            }</span></div>`;
                        })
                        .join("");

                    toHtml = changeKeys
                        .map((key) => {
                            const val = a.changes[key];
                            return `<div><span style="color:green">${
                                val.to ?? "-"
                            }</span></div>`;
                        })
                        .join("");
                }

                tbody.append(`
                    <tr>
                        <td>${new Date(a.created_at).toLocaleString()}</td> 
                        <td>${a.item_name ?? a.item?.name ?? "-"}</td>
                        <td>${a.category_name ?? a.category?.name ?? "-"}</td>
                        <td>${changedFields}</td>
                        <td>${fromHtml}</td>
                        <td>${toHtml}</td>
                        <td>${a.user?.name ?? "System"}</td>
                    </tr>
                `);
            });
        });
    }

    function openEditCategoryModal(category) {
        $("#categoryForm")[0].reset();
        $("#subcategoriesContainer").empty();
    
        // ✅ Change heading
        $("#categoryFormHeading").html(`
            <i class="fas fa-edit"></i> Edit Category
        `);
    
        $("#categoryName").val(category.name);
        $("#categoryIcon").val(category.icon || "fa-box");
        $("#categoryColor").val(category.color || "#3498db");
        $("#colorHex").text(category.color || "#3498db");
    
        // ✅ Set hidden edit_id
        $("#editCategoryId").val(category.id);
    
        // Restore subcategories
        if (category.subcategories && category.subcategories.length) {
            category.subcategories.forEach((sub) => {
                $("#subcategoriesContainer").append(`
                    <div class="subcategory-row" style="display:flex;gap:8px;margin-bottom:6px">
                        <input type="text" name="subcategories[]" class="form-control" value="${sub}" required>
                        <button type="button" class="btn btn-danger remove-subcategory">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `);
            });
        }
    
        openModal("categoryModal");
        
        document.getElementById("categoryFormHeading").scrollIntoView({
            behavior: "smooth",
            block: "start"
        });
    }
    
    function loadChangeLogs(itemId) {
        $("#changeHistoryBody").html(
            `<tr><td colspan="5">Loading change logs...</td></tr>` 
        );

        $.get(`/institute/admin/inventory/item/${itemId}/activity`, (res) => {
            const tbody = $("#changeHistoryBody").empty();
            const logs = res.logs || [];

            if (!logs.length) {
                tbody.append(`
                <tr>
                    <td colspan="5" class="text-center">No change logs found</td>
                </tr>
            `);
                return;
            }

            // Loop through the logs for this specific item
            logs.forEach((log) => {
                // Parse the changes
                let changes = {};
                // try {
                    changes =
                        typeof log.changes === "string"
                            ? JSON.parse(log.changes)
                            : log.changes || {};
                // } catch (e) {
                //     console.error("Error parsing changes:", e);
                // }

                // Create rows for each changed field
                Object.keys(changes).forEach((field) => {
                    const change = changes[field];

                    // Skip if no actual change or if it's category_id
                    if (field === "category_id" || change.from === change.to)
                        return;

                    tbody.append(`
                    <tr>
                        <td>${new Date(log.created_at).toLocaleString()}</td>
                        <td class="log-user">${log.user?.name || "System"}</td>
                        <td>${field
                            .replace("_", " ")
                            .replace(/^./, (c) => c.toUpperCase())}</td>
                        <td style="color: #e74c3c; max-width: 150px; word-wrap: break-word;">${
                            change.from || "-"
                        }</td>
                        <td style="color: #2ecc71; max-width: 150px; word-wrap: break-word;">${
                            change.to || "-"
                        }</td>
                    </tr>
                `);
                });
            });

            if (tbody.find("tr").length === 0) {
                tbody.append(`
                <tr>
                    <td colspan="5" class="text-center">No change logs found</td>
                </tr>
            `);
            }
        }).fail(function () {
            $("#changeHistoryBody").html(`
            <tr>
                <td colspan="5" class="text-center text-danger">
                    Failed to load change logs
                </td>
            </tr>
        `);
        });
    }
    /* =========================================================
    NOTIFICATION
    ========================================================= */
    function showNotification(msg, type = "success") {
        $(".notification").remove();

        const icon =
            type === "success"
                ? "fas fa-check-circle"
                : "fas fa-exclamation-circle";

        const notification = $(`
        <div class="notification ${type}">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="${icon}" style="font-size: 1.2rem;"></i>
                <span>${msg}</span>
            </div>
        </div>
    `);

        $("body").append(notification);

        setTimeout(() => {
            notification.css("animation", "slideOut 0.3s ease");
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }

    function showError(msg) {
        showNotification(msg, "error");
    }
</script>
@endsection

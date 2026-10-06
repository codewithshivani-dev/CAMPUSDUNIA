@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    .stock-info-box {
        background: #f0fdf4;
        border: 2px solid #bbf7d0;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-top: 0.5rem;
        display: none;
    }
    .stock-info-box.visible {
        display: block;
    }
    .stock-info-box .label {
        color: #166534;
        font-weight: 600;
    }
    .stock-info-box .value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #166534;
    }
    .stock-info-box .value.low {
        color: #dc2626;
    }
    .stock-info-box .value.medium {
        color: #f59e0b;
    }
    .stock-warning {
        background: #fef3c7;
        border: 2px solid #fcd34d;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        margin-top: 0.5rem;
        display: none;
    }
    .stock-warning.visible {
        display: block;
    }
    .stock-warning i {
        color: #92400e;
    }
    .required-star {
        color: #dc3545;
    }
    .form-hint {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 0.25rem;
    }
    .section-divider {
        background: #f8fafc;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        margin: 1rem 0 1rem 0;
        border-left: 4px solid #4361ee;
        font-weight: 600;
        color: #1e293b;
    }
    .section-divider i {
        color: #4361ee;
        margin-right: 8px;
    }
    .asset-instance-select {
        background: #f0f4ff;
        border: 2px solid #c7d2fe;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        margin-top: 0.5rem;
        display: none;
    }
    .asset-instance-select.visible {
        display: block;
    }
    .asset-instance-select .label {
        color: #4338ca;
        font-weight: 600;
    }
    .asset-badge {
        display: inline-block;
        padding: 0.15rem 0.6rem;
        border-radius: 12px;
        font-size: 0.65rem;
        font-weight: 600;
    }
    .asset-badge.active {
        background: #d1fae5;
        color: #065f46;
    }
    .asset-badge.damaged {
        background: #fee2e2;
        color: #991b1b;
    }
    .asset-badge.assigned {
        background: #fef3c7;
        color: #92400e;
    }
    .asset-badge.under_repair {
        background: #fef3c7;
        color: #92400e;
    }
    .asset-badge.disposed {
        background: #e2e3e5;
        color: #383d41;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-plus"></i> Create Out Receipt</h4>
        <a href="{{ route('inventory.receipts.out.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('inventory.receipts.out.store') }}">
                @csrf

                <div class="row">
                    <!-- Item -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Item <span class="required-star">*</span></label>
                        <select name="item_id" id="itemSelect" class="form-control @error('item_id') is-invalid @enderror" required>
                            <option value="">Select Item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" 
                                    {{ old('item_id') == $item->id ? 'selected' : '' }}
                                    data-warehouse="{{ $item->warehouse_id }}"
                                    data-stock="{{ $item->available_stock }}"
                                    data-item-name="{{ $item->item_name }}"
                                    data-item-code="{{ $item->item_code }}"
                                    data-warehouse-name="{{ $item->warehouse->warehouse_name ?? '' }}"
                                    data-item-type="{{ $item->item_type }}"
                                    data-has-assets="{{ $item->assetInstances()->exists() ? 'true' : 'false' }}">
                                    {{ $item->item_name }} ({{ $item->item_code }}) - Stock: {{ $item->available_stock }}
                                    @if($item->item_type == 'ASSET')
                                        <span class="badge bg-warning text-dark">Asset</span>
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Warehouse -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Warehouse <span class="required-star">*</span></label>
                        <select name="warehouse_id" id="warehouseSelect" class="form-control @error('warehouse_id') is-invalid @enderror" required>
                            <option value="">Select Warehouse</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->warehouse_name }} ({{ $warehouse->warehouse_code }})
                                    @if($warehouse->is_default) - Default @endif
                                </option>
                            @endforeach
                        </select>
                        @error('warehouse_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Select the warehouse from which items will be issued.</div>
                    </div>

                    <!-- Quantity -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Quantity <span class="required-star">*</span></label>
                        <input type="number" name="quantity" id="quantityInput" 
                               class="form-control @error('quantity') is-invalid @enderror" 
                               value="{{ old('quantity') }}" step="0.01" min="0.01" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Receipt Type -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Receipt Type <span class="required-star">*</span></label>
                        <select name="receipt_type" id="receiptType" class="form-control @error('receipt_type') is-invalid @enderror" required>
                            <option value="">Select Type</option>
                            <option value="SALE" {{ old('receipt_type') == 'SALE' ? 'selected' : '' }}>Sale</option>
                            <option value="TRANSFER" {{ old('receipt_type') == 'TRANSFER' ? 'selected' : '' }}>Transfer</option>
                            <option value="RETURN" {{ old('receipt_type') == 'RETURN' ? 'selected' : '' }}>Return</option>
                            <option value="DAMAGE" {{ old('receipt_type') == 'DAMAGE' ? 'selected' : '' }}>Damage</option>
                            <option value="WASTE" {{ old('receipt_type') == 'WASTE' ? 'selected' : '' }}>Waste</option>
                            <option value="ISSUE" {{ old('receipt_type') == 'ISSUE' ? 'selected' : '' }}>Department Issue</option>
                            <option value="CONSUMPTION" {{ old('receipt_type') == 'CONSUMPTION' ? 'selected' : '' }}>Consumption</option>
                        </select>
                        @error('receipt_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Issued To -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Issued To</label>
                        <input type="text" name="issued_to" class="form-control" value="{{ old('issued_to') }}" 
                               placeholder="Person/Department receiving">
                        <div class="form-hint">For ISSUE type, specify the person or department.</div>
                    </div>

                    <!-- ============================================ -->
                    <!-- DEPARTMENT SELECTION (For ISSUE type) -->
                    <!-- ============================================ -->
                    <div class="col-md-3 mb-3" id="departmentSection" style="display: none;">
                        <label class="form-label">Department <span class="required-star">*</span></label>
                        <select name="department_id" id="departmentSelect" class="form-control">
                            <option value="">Select Department</option>
                            @php
                                // This will be populated from the departments table
                                $departments = \App\Models\Inventory\InventoryDepartment::where('institute_id', auth()->user()->institute_id)->get();
                            @endphp
                            @foreach($departments ?? [] as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }} ({{ $department->code }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-hint">Select the department receiving these items.</div>
                    </div>

                    <!-- ============================================ -->
                    <!-- CONSUMPTION TYPE (For CONSUMPTION type) -->
                    <!-- ============================================ -->
                    <div class="col-md-3 mb-3" id="consumptionSection" style="display: none;">
                        <label class="form-label">Consumption Type <span class="required-star">*</span></label>
                        <select name="consumption_type" id="consumptionType" class="form-control">
                            <option value="">Select Consumption Type</option>
                            <option value="KITCHEN" {{ old('consumption_type') == 'KITCHEN' ? 'selected' : '' }}>🍳 Kitchen / Food Preparation</option>
                            <option value="PRODUCTION" {{ old('consumption_type') == 'PRODUCTION' ? 'selected' : '' }}>🏭 Production / Manufacturing</option>
                            <option value="PROJECT" {{ old('consumption_type') == 'PROJECT' ? 'selected' : '' }}>📋 Project Work</option>
                            <option value="OTHER" {{ old('consumption_type') == 'OTHER' ? 'selected' : '' }}>Other</option>
                        </select>
                        <div class="form-hint">Select how the items are being consumed.</div>
                    </div>

                    <!-- ============================================ -->
                    <!-- WASTE REASON (For WASTE/DAMAGE type) -->
                    <!-- ============================================ -->
                    <div class="col-md-3 mb-3" id="wasteSection" style="display: none;">
                        <label class="form-label">Waste Reason <span class="required-star">*</span></label>
                        <select name="waste_reason" id="wasteReason" class="form-control">
                            <option value="">Select Waste Reason</option>
                            <option value="EXPIRED" {{ old('waste_reason') == 'EXPIRED' ? 'selected' : '' }}>⏰ Expired</option>
                            <option value="DAMAGED" {{ old('waste_reason') == 'DAMAGED' ? 'selected' : '' }}>🔨 Damaged</option>
                            <option value="BROKEN" {{ old('waste_reason') == 'BROKEN' ? 'selected' : '' }}>💔 Broken</option>
                            <option value="SPOILED" {{ old('waste_reason') == 'SPOILED' ? 'selected' : '' }}>🥀 Spoiled / Rotten</option>
                            <option value="OTHER" {{ old('waste_reason') == 'OTHER' ? 'selected' : '' }}>Other</option>
                        </select>
                        <div class="form-hint">Select the reason for waste or damage.</div>
                    </div>

                    <!-- ============================================ -->
                    <!-- ASSET INSTANCE SELECTION (For ASSET items) -->
                    <!-- ============================================ -->
                    <div class="col-md-12 mb-3" id="assetInstanceSection" style="display: none;">
                        <div class="asset-instance-select">
                            <div class="label">
                                <i class="fas fa-hashtag"></i> Select Asset Instance
                                <span class="text-muted small fw-normal">(Choose specific asset to remove)</span>
                            </div>
                            <div id="assetInstanceList" class="mt-2">
                                <p class="text-muted small">Select an item above to see available asset instances.</p>
                            </div>
                            <div class="form-hint mt-2">For asset items, you can select specific asset instances to remove.</div>
                        </div>
                    </div>

                    <!-- Purpose -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Purpose</label>
                        <input type="text" name="purpose" class="form-control" value="{{ old('purpose') }}" 
                               placeholder="e.g., Sold to customer, Transferred to branch">
                        <div class="form-hint">Brief description of why this item is being issued.</div>
                    </div>

                    <!-- Notes -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Stock Info Box -->
                <div id="stockInfoBox" class="stock-info-box">
                    <div class="row">
                        <div class="col-md-4">
                            <span class="label">📦 Available Stock:</span>
                            <span id="displayStock" class="value">0</span>
                        </div>
                        <div class="col-md-4">
                            <span class="label">🏢 Warehouse:</span>
                            <span id="displayWarehouse" class="value">-</span>
                        </div>
                        <div class="col-md-4">
                            <span class="label">📋 Item:</span>
                            <span id="displayItem" class="value">-</span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <span class="label">🔢 Code:</span>
                            <span id="displayCode" class="value">-</span>
                        </div>
                        <div class="col-md-6">
                            <span class="label">📊 Item Type:</span>
                            <span id="displayItemType" class="value">-</span>
                        </div>
                    </div>
                </div>

                <!-- Stock Warning -->
                <div id="stockWarning" class="stock-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span id="warningMessage">⚠️ Quantity exceeds available stock!</span>
                </div>

                <hr>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success" id="submitBtn">
                        <i class="fas fa-save"></i> Create Receipt (Draft)
                    </button>
                    <a href="{{ route('inventory.receipts.out.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {

        // =============================================
        // RECEIPT TYPE HANDLING
        // =============================================
        $('#receiptType').on('change', function() {
            const type = $(this).val();
            
            // Department Section (for ISSUE)
            if (type === 'ISSUE') {
                $('#departmentSection').slideDown(300);
                $('#consumptionSection').slideUp(300);
                $('#wasteSection').slideUp(300);
            } 
            // Consumption Section
            else if (type === 'CONSUMPTION') {
                $('#departmentSection').slideUp(300);
                $('#consumptionSection').slideDown(300);
                $('#wasteSection').slideUp(300);
            } 
            // Waste Section (for WASTE/DAMAGE)
            else if (type === 'WASTE' || type === 'DAMAGE') {
                $('#departmentSection').slideUp(300);
                $('#consumptionSection').slideUp(300);
                $('#wasteSection').slideDown(300);
            } 
            else {
                $('#departmentSection').slideUp(300);
                $('#consumptionSection').slideUp(300);
                $('#wasteSection').slideUp(300);
            }

            // Show/hide asset instance section based on item type
            updateAssetInstanceSection();
        });

        // =============================================
        // UPDATE STOCK INFO WHEN ITEM SELECTED
        // =============================================
        function updateStockInfo() {
            const selected = $('#itemSelect').find('option:selected');
            const stock = parseFloat(selected.data('stock')) || 0;
            const warehouseId = selected.data('warehouse');
            const itemName = selected.data('item-name') || '';
            const itemCode = selected.data('item-code') || '';
            const warehouseName = selected.data('warehouse-name') || '';
            const itemType = selected.data('item-type') || '';

            // Update stock info box
            $('#displayStock').text(stock).removeClass('low medium');
            if (stock <= 0) {
                $('#displayStock').addClass('low');
            } else if (stock <= 10) {
                $('#displayStock').addClass('medium');
            }
            $('#displayWarehouse').text(warehouseName || 'No warehouse assigned');
            $('#displayItem').text(itemName || '-');
            $('#displayCode').text(itemCode || '-');
            $('#displayItemType').text(itemType || '-');

            // Show the info box
            if (selected.val()) {
                $('#stockInfoBox').addClass('visible');
            } else {
                $('#stockInfoBox').removeClass('visible');
            }

            // Auto-select warehouse
            if (warehouseId) {
                $('#warehouseSelect').val(warehouseId);
            }

            // Update asset instance section
            updateAssetInstanceSection();

            // Check quantity against stock
            checkQuantity();
        }

        // =============================================
        // ASSET INSTANCE SECTION
        // =============================================
        function updateAssetInstanceSection() {
            const selected = $('#itemSelect').find('option:selected');
            const itemType = selected.data('item-type') || '';
            const itemId = selected.val();
            const receiptType = $('#receiptType').val();

            // Only show for ASSET items and when receipt type is not 'ISSUE'
            if (itemType === 'ASSET' && receiptType !== 'ISSUE') {
                $('#assetInstanceSection').show();
                loadAssetInstances(itemId);
            } else {
                $('#assetInstanceSection').hide();
            }
        }

        function loadAssetInstances(itemId) {
            if (!itemId) {
                $('#assetInstanceList').html('<p class="text-muted small">Select an item to see available asset instances.</p>');
                return;
            }

            $.ajax({
                url: '/inventory/items/' + itemId + '/asset-instances',
                method: 'GET',
                success: function(response) {
                    if (response.length > 0) {
                        let html = '<div class="row">';
                        response.forEach(function(asset) {
                            const statusBadge = 'asset-badge ' + asset.status.toLowerCase();
                            html += `
                                <div class="col-md-4 col-6 mb-2">
                                    <div class="form-check">
                                        <input type="checkbox" name="asset_instance_ids[]" 
                                               value="${asset.id}" 
                                               class="form-check-input asset-checkbox"
                                               id="asset_${asset.id}">
                                        <label class="form-check-label" for="asset_${asset.id}" style="font-size: 0.85rem;">
                                            <strong>${asset.asset_code || asset.serial_number || 'N/A'}</strong>
                                            <br>
                                            <span class="asset-badge ${asset.status.toLowerCase()}">${asset.status}</span>
                                            ${asset.serial_number ? ' | SN: ' + asset.serial_number : ''}
                                        </label>
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        html += '<div class="mt-2 text-muted small">Select the asset instances to remove. Total: ' + response.length + ' instances.</div>';
                        $('#assetInstanceList').html(html);
                    } else {
                        $('#assetInstanceList').html('<p class="text-muted small">No asset instances available for this item.</p>');
                    }
                },
                error: function() {
                    $('#assetInstanceList').html('<p class="text-muted small">Error loading asset instances.</p>');
                }
            });
        }

        // =============================================
        // CHECK QUANTITY AGAINST STOCK
        // =============================================
        function checkQuantity() {
            const selected = $('#itemSelect').find('option:selected');
            const stock = parseFloat(selected.data('stock')) || 0;
            const quantity = parseFloat($('#quantityInput').val()) || 0;
            const warning = $('#stockWarning');
            const warningMsg = $('#warningMessage');
            const itemType = selected.data('item-type') || '';
            const isAsset = itemType === 'ASSET';

            // For assets, we check if enough asset instances are selected
            if (isAsset && quantity > 0) {
                const selectedAssets = $('.asset-checkbox:checked').length;
                if (selectedAssets > 0 && selectedAssets < quantity) {
                    warning.addClass('visible');
                    warningMsg.text('⚠️ Selected ' + selectedAssets + ' asset(s) but quantity is ' + quantity + '. Please select ' + quantity + ' asset(s).');
                    $('#submitBtn').prop('disabled', true);
                    return;
                } else if (selectedAssets >= quantity) {
                    warning.removeClass('visible');
                    $('#submitBtn').prop('disabled', false);
                    return;
                }
            }

            if (quantity > 0 && selected.val()) {
                if (quantity > stock) {
                    warning.addClass('visible');
                    warningMsg.text('⚠️ Quantity (' + quantity + ') exceeds available stock (' + stock + ')!');
                    $('#submitBtn').prop('disabled', true);
                } else if (quantity > stock * 0.5) {
                    warning.addClass('visible');
                    warningMsg.text('⚠️ Quantity is more than 50% of available stock (' + stock + '). Please review.');
                    $('#submitBtn').prop('disabled', false);
                } else {
                    warning.removeClass('visible');
                    $('#submitBtn').prop('disabled', false);
                }
            } else {
                warning.removeClass('visible');
                $('#submitBtn').prop('disabled', false);
            }
        }

        // =============================================
        // EVENT LISTENERS
        // =============================================
        $('#itemSelect').on('change', function() {
            updateStockInfo();
        });

        $('#quantityInput').on('input', function() {
            checkQuantity();
        });

        $(document).on('change', '.asset-checkbox', function() {
            checkQuantity();
        });

        // =============================================
        // TRIGGER ON PAGE LOAD
        // =============================================
        if ($('#itemSelect').val()) {
            updateStockInfo();
        }

        // Trigger receipt type change on load
        if ($('#receiptType').val()) {
            $('#receiptType').trigger('change');
        }

        // =============================================
        // VALIDATE ASSET SELECTION BEFORE SUBMIT
        // =============================================
        $('form').on('submit', function(e) {
            const selected = $('#itemSelect').find('option:selected');
            const itemType = selected.data('item-type') || '';
            const receiptType = $('#receiptType').val();

            if (itemType === 'ASSET' && receiptType !== 'ISSUE') {
                const quantity = parseFloat($('#quantityInput').val()) || 0;
                const selectedAssets = $('.asset-checkbox:checked').length;

                if (selectedAssets > 0 && selectedAssets < quantity) {
                    e.preventDefault();
                    showToast('Please select ' + quantity + ' asset instance(s) for this quantity.', 'error');
                    return false;
                } else if (selectedAssets === 0 && quantity > 0) {
                    e.preventDefault();
                    showToast('Please select asset instance(s) for this quantity.', 'error');
                    return false;
                }
            }
            return true;
        });

        // Toast notification
        function showToast(message, type = 'success') {
            const toast = $('#toast');
            if (toast.length === 0) {
                // Create toast if not exists
                $('body').append(`
                    <div id="toast" class="toast" style="position: fixed; bottom: 20px; right: 20px; padding: 16px 24px; border-radius: 12px; color: white; display: none; z-index: 1001; background: ${type === 'error' ? '#ef4444' : '#10b981'};">
                        <span id="toastMessage"></span>
                    </div>
                `);
            }
            const toastEl = $('#toast');
            const toastMessage = $('#toastMessage');
            toastMessage.text(message);
            toastEl.css('background', type === 'error' ? '#ef4444' : '#10b981');
            toastEl.show();
            setTimeout(function() {
                toastEl.hide();
            }, 3000);
        }
    });
</script>

@endsection
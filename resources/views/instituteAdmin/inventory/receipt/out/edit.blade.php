@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    .receipt-header {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        margin-bottom: 1.5rem;
    }
    .status-badge {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
    .form-hint {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 0.25rem;
    }
    .required-star {
        color: #dc3545;
    }
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
        font-size: 1.1rem;
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
    .other-warehouses {
        background: #f0f4ff;
        border: 2px solid #c7d2fe;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-top: 0.5rem;
        display: none;
    }
    .other-warehouses.visible {
        display: block;
    }
    .other-warehouses .label {
        color: #4338ca;
        font-weight: 600;
    }
    .other-warehouses .warehouse-item {
        display: inline-block;
        background: white;
        padding: 0.2rem 0.8rem;
        border-radius: 20px;
        margin: 0.2rem 0.4rem 0.2rem 0;
        border: 1px solid #c7d2fe;
        font-size: 0.85rem;
    }
    .other-warehouses .warehouse-item .stock-count {
        font-weight: 700;
        color: #4338ca;
    }
    .other-warehouses .suggestion {
        margin-top: 0.5rem;
        padding: 0.5rem 1rem;
        background: #e0e7ff;
        border-radius: 8px;
        color: #4338ca;
        font-size: 0.85rem;
    }
    .other-warehouses .suggestion i {
        margin-right: 0.5rem;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-edit"></i> Edit Out Receipt</h4>
        <div>
            <a href="{{ route('inventory.receipts.out.show', $receipt->id) }}" class="btn btn-info btn-sm">
                <i class="fas fa-eye"></i> View
            </a>
            <a href="{{ route('inventory.receipts.out.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Receipt Header -->
    <div class="receipt-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-1">{{ $receipt->receipt_number }}</h5>
                <small class="text-muted">Created: {{ $receipt->created_at->format('d M Y, h:i A') }}</small>
                <br>
                <small class="text-muted">Created By: {{ $receipt->creator->name ?? 'System' }}</small>
            </div>
            <div class="col-md-6 text-end">
                <span class="badge {{ $receipt->status_badge }} status-badge">
                    {{ $receipt->status_text }}
                </span>
                <span class="badge bg-info ms-2">{{ $receipt->receipt_type_text }}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('inventory.receipts.out.update', $receipt->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Item <span class="required-star">*</span></label>
                        <select name="item_id" id="itemSelect" class="form-control @error('item_id') is-invalid @enderror" required>
                            <option value="">Select Item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" 
                                    {{ old('item_id', $receipt->item_id) == $item->id ? 'selected' : '' }}
                                    data-selling-price="{{ $item->selling_price }}"
                                    data-item-name="{{ $item->item_name }}"
                                    data-item-code="{{ $item->item_code }}"
                                    data-available-stock="{{ $item->available_stock }}"
                                    data-warehouse="{{ $item->warehouse_id }}"
                                    data-warehouse-name="{{ $item->warehouse->warehouse_name ?? '' }}">
                                    {{ $item->item_name }} ({{ $item->item_code }}) - Stock: {{ $item->available_stock }}
                                </option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Warehouse <span class="required-star">*</span></label>
                        <select name="warehouse_id" id="warehouseSelect" class="form-control @error('warehouse_id') is-invalid @enderror" required>
                            <option value="">Select Warehouse</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" 
                                    {{ old('warehouse_id', $receipt->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->warehouse_name }} ({{ $warehouse->warehouse_code }})
                                </option>
                            @endforeach
                        </select>
                        @error('warehouse_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Select the warehouse from which items will be issued.</div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Quantity <span class="required-star">*</span></label>
                        <input type="number" name="quantity" id="quantityInput" 
                               class="form-control @error('quantity') is-invalid @enderror" 
                               value="{{ old('quantity', $receipt->quantity) }}" step="0.01" min="0.01" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Unit Price</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="text" id="unitPriceDisplay" class="form-control" readonly 
                                   value="{{ number_format($receipt->unit_price, 2) }}">
                        </div>
                        <div class="form-hint">Auto-calculated from item's selling price.</div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Total Price</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="text" id="totalPriceDisplay" class="form-control" readonly 
                                   value="{{ number_format($receipt->total_price, 2) }}">
                        </div>
                        <div class="form-hint">Auto-calculated: Quantity × Unit Price</div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Receipt Type <span class="required-star">*</span></label>
                        <select name="receipt_type" class="form-control @error('receipt_type') is-invalid @enderror" required>
                            <option value="">Select Type</option>
                            <option value="SALE" {{ old('receipt_type', $receipt->receipt_type) == 'SALE' ? 'selected' : '' }}>Sale</option>
                            <option value="TRANSFER" {{ old('receipt_type', $receipt->receipt_type) == 'TRANSFER' ? 'selected' : '' }}>Transfer</option>
                            <option value="RETURN" {{ old('receipt_type', $receipt->receipt_type) == 'RETURN' ? 'selected' : '' }}>Return</option>
                            <option value="DAMAGE" {{ old('receipt_type', $receipt->receipt_type) == 'DAMAGE' ? 'selected' : '' }}>Damage</option>
                            <option value="WASTE" {{ old('receipt_type', $receipt->receipt_type) == 'WASTE' ? 'selected' : '' }}>Waste</option>
                        </select>
                        @error('receipt_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Issued To</label>
                        <input type="text" name="issued_to" class="form-control" 
                               value="{{ old('issued_to', $receipt->issued_to) }}" 
                               placeholder="Person/Department receiving">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Purpose</label>
                        <input type="text" name="purpose" class="form-control" 
                               value="{{ old('purpose', $receipt->purpose) }}" 
                               placeholder="e.g., Sold to customer, Transferred to branch">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $receipt->notes) }}</textarea>
                    </div>
                </div>

                <!-- Stock Info Box -->
                <div id="stockInfoBox" class="stock-info-box">
                    <div class="row">
                        <div class="col-md-4">
                            <span class="label">📦 Item:</span>
                            <span id="displayItem" class="value">{{ $receipt->item->item_name ?? '-' }}</span>
                        </div>
                        <div class="col-md-3">
                            <span class="label">🔢 Code:</span>
                            <span id="displayCode" class="value">{{ $receipt->item->item_code ?? '-' }}</span>
                        </div>
                        <div class="col-md-3">
                            <span class="label">📊 Available Stock:</span>
                            <span id="displayStock" class="value">{{ $receipt->item->available_stock ?? 0 }}</span>
                        </div>
                        <div class="col-md-2">
                            <span class="label">🏢 Warehouse:</span>
                            <span id="displayWarehouse" class="value">{{ $receipt->warehouse->warehouse_name ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Stock Warning -->
                <div id="stockWarning" class="stock-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span id="warningMessage">⚠️ Quantity exceeds available stock!</span>
                </div>

                <!-- Other Warehouses -->
                <div id="otherWarehouses" class="other-warehouses">
                    <div class="label">📦 Available in other warehouses:</div>
                    <div id="otherWarehouseList" class="mt-2">
                        <!-- Dynamically populated -->
                    </div>
                    <div id="suggestionBox" class="suggestion">
                        <i class="fas fa-lightbulb"></i>
                        <span id="suggestionText">Consider transferring stock from another warehouse first.</span>
                    </div>
                </div>

                <hr>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Receipt
                    </button>
                    <a href="{{ route('inventory.receipts.out.show', $receipt->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    @if($receipt->status == 'DRAFT')
                        <button type="button" class="btn btn-danger float-end" onclick="deleteReceipt({{ $receipt->id }})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {

        // =============================================
        // UPDATE PRICES AND STOCK INFO
        // =============================================
        function updatePrices() {
            const selected = $('#itemSelect').find('option:selected');
            const sellingPrice = parseFloat(selected.data('selling-price')) || 0;
            const quantity = parseFloat($('#quantityInput').val()) || 0;
            const availableStock = parseFloat(selected.data('available-stock')) || 0;
            const warehouseName = selected.data('warehouse-name') || '';
            
            $('#unitPriceDisplay').val(sellingPrice.toFixed(2));
            $('#totalPriceDisplay').val((sellingPrice * quantity).toFixed(2));

            // Update item info
            const itemName = selected.data('item-name') || '';
            const itemCode = selected.data('item-code') || '';
            $('#displayItem').text(itemName || '-');
            $('#displayCode').text(itemCode || '-');
            $('#displayStock').text(availableStock).removeClass('low medium');
            
            if (availableStock <= 0) {
                $('#displayStock').addClass('low');
            } else if (availableStock <= 10) {
                $('#displayStock').addClass('medium');
            }
            
            $('#displayWarehouse').text(warehouseName || '-');

            // Show/hide stock info
            if (selected.val()) {
                $('#stockInfoBox').addClass('visible');
            } else {
                $('#stockInfoBox').removeClass('visible');
            }

            // Check quantity against stock
            checkQuantity();
        }

        // =============================================
        // CHECK QUANTITY AGAINST STOCK
        // =============================================
        function checkQuantity() {
            const selected = $('#itemSelect').find('option:selected');
            const stock = parseFloat(selected.data('available-stock')) || 0;
            const quantity = parseFloat($('#quantityInput').val()) || 0;
            const warning = $('#stockWarning');
            const warningMsg = $('#warningMessage');
            const otherWH = $('#otherWarehouses');

            if (quantity > 0 && selected.val()) {
                if (quantity > stock) {
                    warning.addClass('visible');
                    warningMsg.text('⚠️ Quantity (' + quantity + ') exceeds available stock (' + stock + ')!');
                    $('#submitBtn').prop('disabled', true);
                    
                    // Check other warehouses
                    checkOtherWarehouses(selected.val(), quantity);
                } else if (quantity > stock * 0.5) {
                    warning.addClass('visible');
                    warningMsg.text('⚠️ Quantity is more than 50% of available stock (' + stock + '). Please review.');
                    $('#submitBtn').prop('disabled', false);
                    otherWH.removeClass('visible');
                } else {
                    warning.removeClass('visible');
                    $('#submitBtn').prop('disabled', false);
                    otherWH.removeClass('visible');
                }
            } else {
                warning.removeClass('visible');
                $('#submitBtn').prop('disabled', false);
                otherWH.removeClass('visible');
            }
        }

        // =============================================
        // CHECK OTHER WAREHOUSES
        // =============================================
        function checkOtherWarehouses(itemId, requiredQuantity) {
            const otherWH = $('#otherWarehouses');
            const list = $('#otherWarehouseList');
            const suggestion = $('#suggestionText');

            $.ajax({
                url: '/inventory/receipts/out/check-stock/' + itemId,
                method: 'GET',
                success: function(response) {
                    const others = response.other_warehouses || [];
                    
                    if (others.length > 0) {
                        let html = '';
                        others.forEach(function(wh) {
                            html += '<span class="warehouse-item">' + 
                                    wh.warehouse_name + ': <span class="stock-count">' + 
                                    wh.available_stock + '</span> units</span>';
                        });
                        list.html(html);
                        
                        // Check if total available across all warehouses meets requirement
                        const totalAvailable = response.total_available || 0;
                        if (totalAvailable >= requiredQuantity) {
                            suggestion.text('✅ Stock is available across other warehouses. Consider transferring ' + 
                                           requiredQuantity + ' units to this warehouse.');
                            suggestion.css('color', '#065f46');
                        } else {
                            suggestion.text('⚠️ Total available across all warehouses: ' + totalAvailable + 
                                           '. Required: ' + requiredQuantity + '. Please procure more stock.');
                            suggestion.css('color', '#92400e');
                        }
                        
                        otherWH.addClass('visible');
                    } else {
                        otherWH.removeClass('visible');
                    }
                },
                error: function() {
                    // Silent fail
                }
            });
        }

        // =============================================
        // EVENT LISTENERS
        // =============================================
        $('#itemSelect').on('change', function() {
            // Auto-select warehouse if item has one
            const warehouseId = $(this).find('option:selected').data('warehouse');
            if (warehouseId) {
                $('#warehouseSelect').val(warehouseId);
            }
            updatePrices();
        });

        $('#quantityInput').on('input', updatePrices);
        $('#warehouseSelect').on('change', function() {
            // Re-check stock when warehouse changes
            updatePrices();
        });

        // =============================================
        // DELETE RECEIPT
        // =============================================
        window.deleteReceipt = function(id) {
            if (confirm('⚠️ Are you sure you want to delete this receipt?\n\nThis action cannot be undone.')) {
                const form = document.getElementById('deleteForm');
                form.action = '{{ route("inventory.receipts.out.delete", "") }}/' + id;
                form.submit();
            }
        };

        // =============================================
        // TRIGGER ON PAGE LOAD
        // =============================================
        updatePrices();

    });
</script>

@endsection
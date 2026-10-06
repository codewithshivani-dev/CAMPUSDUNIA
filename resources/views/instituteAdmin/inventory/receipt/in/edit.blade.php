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
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-edit"></i> Edit In Receipt</h4>
        <div>
            <a href="{{ route('inventory.receipts.in.show', $receipt->id) }}" class="btn btn-info btn-sm">
                <i class="fas fa-eye"></i> View
            </a>
            <a href="{{ route('inventory.receipts.in.index') }}" class="btn btn-secondary btn-sm">
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
            <form method="POST" action="{{ route('inventory.receipts.in.update', $receipt->id) }}">
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
                                    data-buying-price="{{ $item->buying_price }}"
                                    data-item-name="{{ $item->item_name }}"
                                    data-item-code="{{ $item->item_code }}">
                                    {{ $item->item_name }} ({{ $item->item_code }}) - ₹{{ number_format($item->buying_price, 2) }}
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
                        <div class="form-hint">Select the warehouse where items will be received.</div>
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
                        <div class="form-hint">Auto-calculated from item's buying price.</div>
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
                            <option value="PURCHASE" {{ old('receipt_type', $receipt->receipt_type) == 'PURCHASE' ? 'selected' : '' }}>Purchase</option>
                            <option value="TRANSFER" {{ old('receipt_type', $receipt->receipt_type) == 'TRANSFER' ? 'selected' : '' }}>Transfer</option>
                            <option value="RETURN" {{ old('receipt_type', $receipt->receipt_type) == 'RETURN' ? 'selected' : '' }}>Return</option>
                        </select>
                        @error('receipt_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Supplier Name</label>
                        <input type="text" name="supplier_name" class="form-control" 
                               value="{{ old('supplier_name', $receipt->supplier_name) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Supplier Invoice Number</label>
                        <input type="text" name="supplier_invoice_number" class="form-control" 
                               value="{{ old('supplier_invoice_number', $receipt->supplier_invoice_number) }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $receipt->notes) }}</textarea>
                    </div>
                </div>

                <!-- Stock Info Box -->
                <div id="stockInfoBox" class="stock-info-box">
                    <div class="row">
                        <div class="col-md-6">
                            <span class="label">📦 Item:</span>
                            <span id="displayItem" class="value">{{ $receipt->item->item_name ?? '-' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="label">🔢 Code:</span>
                            <span id="displayCode" class="value">{{ $receipt->item->item_code ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Receipt
                    </button>
                    <a href="{{ route('inventory.receipts.in.show', $receipt->id) }}" class="btn btn-secondary">
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
        // UPDATE PRICES WHEN ITEM OR QUANTITY CHANGES
        // =============================================
        function updatePrices() {
            const selected = $('#itemSelect').find('option:selected');
            const buyingPrice = parseFloat(selected.data('buying-price')) || 0;
            const quantity = parseFloat($('#quantityInput').val()) || 0;
            
            $('#unitPriceDisplay').val(buyingPrice.toFixed(2));
            $('#totalPriceDisplay').val((buyingPrice * quantity).toFixed(2));

            // Update item info
            const itemName = selected.data('item-name') || '';
            const itemCode = selected.data('item-code') || '';
            $('#displayItem').text(itemName || '-');
            $('#displayCode').text(itemCode || '-');

            // Show/hide stock info
            if (selected.val()) {
                $('#stockInfoBox').addClass('visible');
            } else {
                $('#stockInfoBox').removeClass('visible');
            }
        }

        $('#itemSelect').on('change', updatePrices);
        $('#quantityInput').on('input', updatePrices);

        // =============================================
        // DELETE RECEIPT
        // =============================================
        window.deleteReceipt = function(id) {
            if (confirm('⚠️ Are you sure you want to delete this receipt?\n\nThis action cannot be undone.')) {
                const form = document.getElementById('deleteForm');
                form.action = '{{ route("inventory.receipts.in.delete", "") }}/' + id;
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
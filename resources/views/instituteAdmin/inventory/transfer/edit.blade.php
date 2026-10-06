@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    .transfer-header {
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
    .warehouse-flow {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        background: #f8fafc;
        border-radius: 12px;
        margin: 1rem 0;
    }
    .warehouse-flow .warehouse-box {
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-align: center;
        min-width: 150px;
    }
    .warehouse-flow .warehouse-box.source {
        background: #fee2e2;
        color: #991b1b;
        border: 2px solid #fecaca;
    }
    .warehouse-flow .warehouse-box.destination {
        background: #d1fae5;
        color: #065f46;
        border: 2px solid #a7f3d0;
    }
    .warehouse-flow .arrow {
        font-size: 2rem;
        color: #94a3b8;
        margin: 0 1.5rem;
    }
    .warehouse-flow .quantity-badge {
        background: #e0e7ff;
        color: #4338ca;
        padding: 0.25rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .capacity-warning {
        background: #fef3c7;
        border: 2px solid #fcd34d;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        margin-top: 0.5rem;
        display: none;
    }
    .capacity-warning.visible {
        display: block;
    }
    .capacity-warning i {
        color: #92400e;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-edit"></i> Edit Warehouse Transfer</h4>
        <div>
            <a href="{{ route('inventory.transfers.show', $transfer->id) }}" class="btn btn-info btn-sm">
                <i class="fas fa-eye"></i> View
            </a>
            <a href="{{ route('inventory.transfers.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Transfer Header -->
    <div class="transfer-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-1">{{ $transfer->transfer_code }}</h5>
                <small class="text-muted">Created: {{ $transfer->created_at->format('d M Y, h:i A') }}</small>
                <br>
                <small class="text-muted">Created By: {{ $transfer->creator->name ?? 'System' }}</small>
            </div>
            <div class="col-md-6 text-end">
                <span class="badge {{ $transfer->status_badge }} status-badge">
                    {{ $transfer->status_text }}
                </span>
                @if($transfer->is_overdue)
                    <span class="badge bg-danger ms-2">Overdue</span>
                @endif
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('inventory.transfers.update', $transfer->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Item <span class="required-star">*</span></label>
                        <select name="item_id" id="itemSelect" class="form-control @error('item_id') is-invalid @enderror" required>
                            <option value="">Select Item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" 
                                    {{ old('item_id', $transfer->item_id) == $item->id ? 'selected' : '' }}
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
                        <label class="form-label">Quantity <span class="required-star">*</span></label>
                        <input type="number" name="quantity" id="quantityInput" 
                               class="form-control @error('quantity') is-invalid @enderror" 
                               value="{{ old('quantity', $transfer->quantity) }}" step="0.01" min="0.01" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Quantity to transfer from source to destination warehouse.</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">From Warehouse <span class="required-star">*</span></label>
                        <select name="from_warehouse_id" id="fromWarehouseSelect" 
                                class="form-control @error('from_warehouse_id') is-invalid @enderror" required>
                            <option value="">Select Source Warehouse</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" 
                                    {{ old('from_warehouse_id', $transfer->from_warehouse_id) == $warehouse->id ? 'selected' : '' }}
                                    data-capacity="{{ $warehouse->capacity }}"
                                    data-utilization="{{ $warehouse->current_utilization }}">
                                    {{ $warehouse->warehouse_name }} ({{ $warehouse->warehouse_code }})
                                    @if($warehouse->is_default) - Default @endif
                                </option>
                            @endforeach
                        </select>
                        @error('from_warehouse_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Source warehouse from which items will be transferred.</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">To Warehouse <span class="required-star">*</span></label>
                        <select name="to_warehouse_id" id="toWarehouseSelect" 
                                class="form-control @error('to_warehouse_id') is-invalid @enderror" required>
                            <option value="">Select Destination Warehouse</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" 
                                    {{ old('to_warehouse_id', $transfer->to_warehouse_id) == $warehouse->id ? 'selected' : '' }}
                                    data-capacity="{{ $warehouse->capacity }}"
                                    data-utilization="{{ $warehouse->current_utilization }}">
                                    {{ $warehouse->warehouse_name }} ({{ $warehouse->warehouse_code }})
                                    @if($warehouse->is_default) - Default @endif
                                </option>
                            @endforeach
                        </select>
                        @error('to_warehouse_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Destination warehouse where items will be received.</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Expected Arrival Date</label>
                        <input type="date" name="expected_arrival_date" class="form-control" 
                               value="{{ old('expected_arrival_date', $transfer->expected_arrival_date ? $transfer->expected_arrival_date->format('Y-m-d') : '') }}">
                        <div class="form-hint">When is this transfer expected to be completed?</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Reason</label>
                        <input type="text" name="reason" class="form-control" 
                               value="{{ old('reason', $transfer->reason) }}" 
                               placeholder="Why is this transfer needed?">
                        <div class="form-hint">e.g., Stock shortage in destination warehouse</div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $transfer->notes) }}</textarea>
                    </div>
                </div>

                <!-- Warehouse Flow Visualization -->
                <div id="warehouseFlow" class="warehouse-flow" style="display: none;">
                    <div class="warehouse-box source" id="sourceDisplay">
                        <i class="fas fa-arrow-right"></i> Source
                        <br>
                        <span id="sourceName">-</span>
                    </div>
                    <div class="arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div class="warehouse-box destination" id="destinationDisplay">
                        <i class="fas fa-arrow-left"></i> Destination
                        <br>
                        <span id="destinationName">-</span>
                    </div>
                    <div class="ms-3">
                        <span class="quantity-badge" id="flowQuantity">0 units</span>
                    </div>
                </div>

                <!-- Stock Info Box -->
                <div id="stockInfoBox" class="stock-info-box">
                    <div class="row">
                        <div class="col-md-4">
                            <span class="label">📦 Item:</span>
                            <span id="displayItem" class="value">{{ $transfer->item->item_name ?? '-' }}</span>
                        </div>
                        <div class="col-md-3">
                            <span class="label">🔢 Code:</span>
                            <span id="displayCode" class="value">{{ $transfer->item->item_code ?? '-' }}</span>
                        </div>
                        <div class="col-md-3">
                            <span class="label">📊 Available Stock:</span>
                            <span id="displayStock" class="value">{{ $transfer->item->available_stock ?? 0 }}</span>
                        </div>
                        <div class="col-md-2">
                            <span class="label">🏢 Source:</span>
                            <span id="displaySource" class="value">{{ $transfer->fromWarehouse->warehouse_name ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Capacity Warning -->
                <div id="capacityWarning" class="capacity-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span id="capacityMessage">⚠️ Destination warehouse does not have enough capacity!</span>
                </div>

                <hr>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Update Transfer
                    </button>
                    <a href="{{ route('inventory.transfers.show', $transfer->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    @if($transfer->status == 'PENDING')
                        <button type="button" class="btn btn-danger float-end" onclick="deleteTransfer({{ $transfer->id }})">
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
        // UPDATE TRANSFER INFO
        // =============================================
        function updateTransferInfo() {
            const selected = $('#itemSelect').find('option:selected');
            const quantity = parseFloat($('#quantityInput').val()) || 0;
            const availableStock = parseFloat(selected.data('available-stock')) || 0;
            const sourceWarehouseId = $('#fromWarehouseSelect').val();
            const destWarehouseId = $('#toWarehouseSelect').val();
            const sourceName = selected.data('warehouse-name') || '';
            
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
            
            // Update source warehouse display
            const sourceText = $('#fromWarehouseSelect').find('option:selected').text() || '-';
            $('#displaySource').text(sourceText);

            // Show/hide stock info
            if (selected.val()) {
                $('#stockInfoBox').addClass('visible');
            } else {
                $('#stockInfoBox').removeClass('visible');
            }

            // Update warehouse flow
            updateWarehouseFlow();
            checkCapacity();
            validateTransfer();
        }

        // =============================================
        // UPDATE WAREHOUSE FLOW
        // =============================================
        function updateWarehouseFlow() {
            const sourceText = $('#fromWarehouseSelect').find('option:selected').text() || '-';
            const destText = $('#toWarehouseSelect').find('option:selected').text() || '-';
            const quantity = parseFloat($('#quantityInput').val()) || 0;

            if (sourceText !== '-' && destText !== '-' && sourceText !== destText) {
                $('#sourceName').text(sourceText);
                $('#destinationName').text(destText);
                $('#flowQuantity').text(quantity + ' units');
                $('#warehouseFlow').show();
            } else {
                $('#warehouseFlow').hide();
            }
        }

        // =============================================
        // CHECK DESTINATION CAPACITY
        // =============================================
        function checkCapacity() {
            const destSelect = $('#toWarehouseSelect').find('option:selected');
            const quantity = parseFloat($('#quantityInput').val()) || 0;
            const capacity = parseFloat(destSelect.data('capacity')) || 0;
            const utilization = parseFloat(destSelect.data('utilization')) || 0;
            const warning = $('#capacityWarning');
            const message = $('#capacityMessage');

            if (destSelect.val() && quantity > 0) {
                if (capacity > 0) {
                    const available = capacity - utilization;
                    if (quantity > available) {
                        warning.addClass('visible');
                        message.text('⚠️ Destination warehouse capacity: ' + available + ' units available. Required: ' + quantity + ' units.');
                        $('#submitBtn').prop('disabled', true);
                    } else if (quantity > available * 0.8) {
                        warning.addClass('visible');
                        message.text('⚠️ This transfer will use ' + Math.round((quantity/available)*100) + '% of available space. Please review.');
                        $('#submitBtn').prop('disabled', false);
                    } else {
                        warning.removeClass('visible');
                        $('#submitBtn').prop('disabled', false);
                    }
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
        // VALIDATE TRANSFER
        // =============================================
        function validateTransfer() {
            const source = $('#fromWarehouseSelect').val();
            const dest = $('#toWarehouseSelect').val();
            const selected = $('#itemSelect').find('option:selected');
            const quantity = parseFloat($('#quantityInput').val()) || 0;
            const availableStock = parseFloat(selected.data('available-stock')) || 0;

            // Check if source and destination are the same
            if (source && dest && source === dest) {
                alert('Source and destination warehouses cannot be the same.');
                $('#toWarehouseSelect').val('');
                return false;
            }

            // Check if item is in source warehouse
            const itemWarehouse = selected.data('warehouse');
            if (source && itemWarehouse && source != itemWarehouse) {
                alert('This item is not available in the selected source warehouse.');
                $('#fromWarehouseSelect').val(itemWarehouse);
                return false;
            }

            // Check stock availability
            if (quantity > availableStock) {
                // Don't show alert here, handled by stock warning
                return false;
            }

            return true;
        }

        // =============================================
        // EVENT LISTENERS
        // =============================================
        $('#itemSelect').on('change', function() {
            // Auto-select source warehouse if item has one
            const warehouseId = $(this).find('option:selected').data('warehouse');
            if (warehouseId) {
                $('#fromWarehouseSelect').val(warehouseId);
            }
            updateTransferInfo();
        });

        $('#fromWarehouseSelect, #toWarehouseSelect').on('change', function() {
            updateTransferInfo();
            validateTransfer();
        });

        $('#quantityInput').on('input', function() {
            updateTransferInfo();
        });

        // =============================================
        // DELETE TRANSFER
        // =============================================
        window.deleteTransfer = function(id) {
            if (confirm('⚠️ Are you sure you want to delete this transfer?\n\nThis action cannot be undone.')) {
                const form = document.getElementById('deleteForm');
                form.action = '{{ route("inventory.transfers.delete", "") }}/' + id;
                form.submit();
            }
        };

        // =============================================
        // TRIGGER ON PAGE LOAD
        // =============================================
        updateTransferInfo();

    });
</script>

@endsection
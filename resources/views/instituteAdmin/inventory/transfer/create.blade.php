@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .page-header {
        background: var(--primary-gradient);
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

    .form-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        box-shadow: var(--card-shadow);
        border: 2px solid var(--border-color);
    }

    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.85rem;
        margin-bottom: 0.4rem;
    }

    .form-control, .form-select {
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .form-control:focus, .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .required-star {
        color: #dc3545;
    }

    .form-hint {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
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

    .warehouse-flow {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        background: #f8fafc;
        border-radius: 12px;
        margin: 1rem 0;
        border: 2px solid var(--border-color);
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

    .btn-success {
        background: #10b981;
        border: none;
        color: white;
        border-radius: 12px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-success:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.35);
        color: white;
    }

    .btn-secondary {
        background: #f1f5f9;
        border: 2px solid var(--border-color);
        color: var(--text-dark);
        border-radius: 12px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .form-card {
            padding: 1.25rem;
        }

        .warehouse-flow {
            flex-direction: column;
            gap: 1rem;
        }

        .warehouse-flow .arrow {
            transform: rotate(90deg);
            margin: 0;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-exchange-alt"></i> Create Warehouse Transfer</h1>
            <p>Transfer inventory between warehouses</p>
        </div>
        <a href="{{ route('inventory.transfers.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Transfers
        </a>
    </div>

    <!-- Form -->
    <div class="form-card">
        <form method="POST" action="{{ route('inventory.transfers.store') }}">
            @csrf

            <div class="row">
                <!-- Item Selection -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Item <span class="required-star">*</span></label>
                    <select name="item_id" id="itemSelect" class="form-select @error('item_id') is-invalid @enderror" required>
                        <option value="">Select Item</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}
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

                <!-- Quantity -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Quantity <span class="required-star">*</span></label>
                    <input type="number" name="quantity" id="quantityInput" 
                           class="form-control @error('quantity') is-invalid @enderror" 
                           value="{{ old('quantity') }}" step="0.01" min="0.01" required>
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Quantity to transfer from source to destination warehouse.</div>
                </div>

                <!-- From Warehouse -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">From Warehouse <span class="required-star">*</span></label>
                    <select name="from_warehouse_id" id="fromWarehouseSelect" 
                            class="form-select @error('from_warehouse_id') is-invalid @enderror" required>
                        <option value="">Select Source Warehouse</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ old('from_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->warehouse_name }} ({{ $warehouse->warehouse_code }})
                            </option>
                        @endforeach
                    </select>
                    @error('from_warehouse_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Source warehouse from which items will be transferred.</div>
                </div>

                <!-- To Warehouse -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">To Warehouse <span class="required-star">*</span></label>
                    <select name="to_warehouse_id" id="toWarehouseSelect" 
                            class="form-select @error('to_warehouse_id') is-invalid @enderror" required>
                        <option value="">Select Destination Warehouse</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ old('to_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->warehouse_name }} ({{ $warehouse->warehouse_code }})
                            </option>
                        @endforeach
                    </select>
                    @error('to_warehouse_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Destination warehouse where items will be received.</div>
                </div>

                <!-- Expected Arrival Date -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Expected Arrival Date</label>
                    <input type="date" name="expected_arrival_date" class="form-control" 
                           value="{{ old('expected_arrival_date') }}">
                    <div class="form-hint">When is this transfer expected to be completed?</div>
                </div>

                <!-- Reason -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Reason</label>
                    <input type="text" name="reason" class="form-control" value="{{ old('reason') }}" 
                           placeholder="Why is this transfer needed?">
                    <div class="form-hint">e.g., Stock shortage in destination warehouse</div>
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
                        <span class="label">📦 Item:</span>
                        <span id="displayItem" class="value">-</span>
                    </div>
                    <div class="col-md-3">
                        <span class="label">🔢 Code:</span>
                        <span id="displayCode" class="value">-</span>
                    </div>
                    <div class="col-md-3">
                        <span class="label">📊 Available Stock:</span>
                        <span id="displayStock" class="value">0</span>
                    </div>
                    <div class="col-md-2">
                        <span class="label">🏢 Warehouse:</span>
                        <span id="displayWarehouse" class="value">-</span>
                    </div>
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

            <hr>

            <!-- Submit -->
            <div class="mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Create Transfer
                </button>
                <a href="{{ route('inventory.transfers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {

        function updateTransferInfo() {
            const selected = $('#itemSelect').find('option:selected');
            const quantity = parseFloat($('#quantityInput').val()) || 0;
            const availableStock = parseFloat(selected.data('available-stock')) || 0;
            const sourceWarehouseId = $('#fromWarehouseSelect').val();
            const destWarehouseId = $('#toWarehouseSelect').val();
            
            // Update item info
            const itemName = selected.data('item-name') || '';
            const itemCode = selected.data('item-code') || '';
            const warehouseName = selected.data('warehouse-name') || '';
            
            $('#displayItem').text(itemName || '-');
            $('#displayCode').text(itemCode || '-');
            $('#displayStock').text(availableStock).removeClass('low');
            $('#displayWarehouse').text(warehouseName || '-');
            
            if (availableStock <= 0) {
                $('#displayStock').addClass('low');
            }

            // Show/hide stock info
            if (selected.val()) {
                $('#stockInfoBox').addClass('visible');
            } else {
                $('#stockInfoBox').removeClass('visible');
            }

            // Update warehouse flow
            const sourceText = $('#fromWarehouseSelect').find('option:selected').text() || '-';
            const destText = $('#toWarehouseSelect').find('option:selected').text() || '-';

            if (sourceText !== '-' && destText !== '-' && sourceText !== destText && selected.val()) {
                $('#sourceName').text(sourceText);
                $('#destinationName').text(destText);
                $('#flowQuantity').text(quantity + ' units');
                $('#warehouseFlow').show();
            } else {
                $('#warehouseFlow').hide();
            }
        }

        // Event Listeners
        $('#itemSelect').on('change', function() {
            const warehouseId = $(this).find('option:selected').data('warehouse');
            if (warehouseId) {
                $('#fromWarehouseSelect').val(warehouseId);
            }
            updateTransferInfo();
        });

        $('#fromWarehouseSelect, #toWarehouseSelect, #quantityInput').on('change input', function() {
            updateTransferInfo();
        });

        // Trigger on page load
        updateTransferInfo();

    });
</script>

@endsection
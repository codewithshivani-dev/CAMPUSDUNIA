@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    .info-box {
        background: #f8fafc;
        padding: 1rem;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        text-align: center;
    }
    .info-box .number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #4361ee;
    }
    .info-box .label {
        font-size: 0.8rem;
        color: #64748b;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-box"></i> Item Details</h4>
        <div>
            <a href="{{ route('inventory.items.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('inventory.items.edit', $item->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="info-box">
                <div class="number">{{ number_format($item->current_stock, 2) }}</div>
                <div class="label">Current Stock</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <div class="number">{{ number_format($item->available_stock, 2) }}</div>
                <div class="label">Available Stock</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <div class="number">₹{{ number_format($item->stock_value, 2) }}</div>
                <div class="label">Stock Value</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <div class="number">{{ $item->margin }}%</div>
                <div class="label">Margin</div>
            </div>
        </div>
    </div>

    <!-- Item Details -->
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Basic Information</h6>
                    <hr>
                    <dl class="row">
                        <dt class="col-sm-4">Item Name</dt>
                        <dd class="col-sm-8">{{ $item->item_name }}</dd>

                        <dt class="col-sm-4">Item Code</dt>
                        <dd class="col-sm-8"><span class="badge bg-secondary">{{ $item->item_code }}</span></dd>

                        <dt class="col-sm-4">SKU</dt>
                        <dd class="col-sm-8">{{ $item->sku ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Item Type</dt>
                        <dd class="col-sm-8">{{ $item->item_type ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Category</dt>
                        <dd class="col-sm-8">{{ $item->category->category_name ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Sub Category</dt>
                        <dd class="col-sm-8">{{ $item->subcategory->subcategory_name ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Unit</dt>
                        <dd class="col-sm-8">{{ $item->unit->unit_name ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Warehouse</dt>
                        <dd class="col-sm-8">{{ $item->warehouse->warehouse_name ?? 'N/A' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <h6>Pricing & Stock</h6>
                    <hr>
                    <dl class="row">
                        <dt class="col-sm-4">Buying Price</dt>
                        <dd class="col-sm-8">₹{{ number_format($item->buying_price, 2) }}</dd>

                        <dt class="col-sm-4">Selling Price</dt>
                        <dd class="col-sm-8">₹{{ number_format($item->selling_price, 2) }}</dd>

                        <dt class="col-sm-4">Profit/Unit</dt>
                        <dd class="col-sm-8">
                            <span class="text-{{ $item->profit_per_unit > 0 ? 'success' : 'danger' }}">
                                ₹{{ number_format($item->profit_per_unit, 2) }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Margin</dt>
                        <dd class="col-sm-8">{{ $item->margin }}%</dd>

                        <dt class="col-sm-4">Current Stock</dt>
                        <dd class="col-sm-8">{{ number_format($item->current_stock, 2) }}</dd>

                        <dt class="col-sm-4">Available Stock</dt>
                        <dd class="col-sm-8">{{ number_format($item->available_stock, 2) }}</dd>

                        <dt class="col-sm-4">Reserved Stock</dt>
                        <dd class="col-sm-8">{{ number_format($item->reserved_stock, 2) }}</dd>

                        <dt class="col-sm-4">Reorder Level</dt>
                        <dd class="col-sm-8">{{ number_format($item->reorder_level, 2) }}</dd>
                    </dl>
                </div>
            </div>

            <!-- Shelf Life Information -->
            @if($item->expiry_date || $item->manufacturing_date || $item->shelf_life_days)
            <div class="row mt-3">
                <div class="col-md-12">
                    <h6>Shelf Life Information</h6>
                    <hr>
                    <dl class="row">
                        @if($item->manufacturing_date)
                        <dt class="col-sm-2">Manufacturing Date</dt>
                        <dd class="col-sm-4">{{ $item->manufacturing_date->format('d M Y') }}</dd>
                        @endif

                        @if($item->expiry_date)
                        <dt class="col-sm-2">Expiry Date</dt>
                        <dd class="col-sm-4">
                            {{ $item->expiry_date->format('d M Y') }}
                            <span class="badge {{ $item->shelf_life_badge }} ms-2">
                                {{ $item->shelf_life_status }}
                            </span>
                            @if($item->days_until_expiry)
                                <br><small>{{ $item->days_until_expiry }} days remaining</small>
                            @endif
                        </dd>
                        @endif

                        @if($item->shelf_life_days)
                        <dt class="col-sm-2">Shelf Life</dt>
                        <dd class="col-sm-4">{{ $item->shelf_life_days }} days</dd>
                        @endif

                        @if($item->batch_number)
                        <dt class="col-sm-2">Batch Number</dt>
                        <dd class="col-sm-4"><span class="badge bg-secondary">{{ $item->batch_number }}</span></dd>
                        @endif

                        @if($item->serial_number)
                        <dt class="col-sm-2">Serial Number</dt>
                        <dd class="col-sm-4"><span class="badge bg-secondary">{{ $item->serial_number }}</span></dd>
                        @endif
                    </dl>
                </div>
            </div>
            @endif

            <!-- Description -->
            @if($item->description)
            <div class="row mt-3">
                <div class="col-md-12">
                    <h6>Description</h6>
                    <hr>
                    <p>{{ $item->description }}</p>
                </div>
            </div>
            @endif

            <!-- Stock in Other Warehouses -->
            @if($otherWarehouses->count() > 0)
            <div class="row mt-3">
                <div class="col-md-12">
                    <h6>Stock in Other Warehouses</h6>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Warehouse</th>
                                    <th>Current Stock</th>
                                    <th>Available Stock</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($otherWarehouses as $warehouseItem)
                                <tr>
                                    <td>{{ $warehouseItem->warehouse->warehouse_name ?? 'N/A' }}</td>
                                    <td>{{ number_format($warehouseItem->current_stock, 2) }}</td>
                                    <td>{{ number_format($warehouseItem->available_stock, 2) }}</td>
                                    <td>
                                        @if($warehouseItem->available_stock > 0)
                                            <span class="badge bg-success">Available</span>
                                        @else
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
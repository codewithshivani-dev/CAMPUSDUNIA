@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-plus"></i> Create In Receipt</h4>
        <a href="{{ route('inventory.receipts.in.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('inventory.receipts.in.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Item <span class="text-danger">*</span></label>
                        <select name="item_id" class="form-control @error('item_id') is-invalid @enderror" required>
                            <option value="">Select Item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->item_name }} ({{ $item->item_code }})
                                </option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Warehouse <span class="text-danger">*</span></label>
                        <select name="warehouse_id" class="form-control @error('warehouse_id') is-invalid @enderror" required>
                            <option value="">Select Warehouse</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->warehouse_name }} ({{ $warehouse->warehouse_code }})
                                </option>
                            @endforeach
                        </select>
                        @error('warehouse_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" 
                               value="{{ old('quantity') }}" step="0.01" min="0.01" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Receipt Type <span class="text-danger">*</span></label>
                        <select name="receipt_type" class="form-control @error('receipt_type') is-invalid @enderror" required>
                            <option value="">Select Type</option>
                            <option value="PURCHASE" {{ old('receipt_type') == 'PURCHASE' ? 'selected' : '' }}>Purchase</option>
                            <option value="TRANSFER" {{ old('receipt_type') == 'TRANSFER' ? 'selected' : '' }}>Transfer</option>
                            <option value="RETURN" {{ old('receipt_type') == 'RETURN' ? 'selected' : '' }}>Return</option>
                        </select>
                        @error('receipt_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Supplier Name</label>
                        <input type="text" name="supplier_name" class="form-control" value="{{ old('supplier_name') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Supplier Invoice Number</label>
                        <input type="text" name="supplier_invoice_number" class="form-control" value="{{ old('supplier_invoice_number') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Notes</label>
                        <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" 
                               placeholder="Additional notes about this receipt">
                    </div>
                </div>

                <hr>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Create Receipt (Draft)
                    </button>
                    <a href="{{ route('inventory.receipts.in.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
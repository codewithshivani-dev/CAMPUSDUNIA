{{-- resources/views/instituteAdmin/Inventory/stock-out/edit.blade.php --}}

@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<!-- Include SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --primary-color: #4361ee;
        --primary-dark: #3a0ca3;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
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

    .form-label .required {
        color: var(--danger-color);
    }

    .form-control, .form-select {
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    .form-control:disabled, .form-select:disabled {
        background-color: #f1f5f9;
        cursor: not-allowed;
    }

    .form-control.error {
        border-color: var(--danger-color);
    }

    .btn-update {
        background: var(--primary-color);
        border: none;
        color: white;
        border-radius: 12px;
        padding: 0.75rem 3rem;
        font-weight: 700;
        font-size: 1.1rem;
        transition: var(--transition);
        min-width: 200px;
    }

    .btn-update:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.35);
        color: white;
    }

    .btn-cancel {
        background: #f1f5f9;
        border: 2px solid var(--border-color);
        color: var(--text-dark);
        border-radius: 12px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
        color: var(--text-dark);
    }

    .status-badge {
        padding: 0.3rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-block;
    }

    .status-badge.pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-badge.approved {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-badge.completed {
        background: #d1fae5;
        color: #065f46;
    }

    .status-badge.cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .info-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        border: 2px solid var(--border-color);
        margin-bottom: 1.5rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.75rem;
    }

    .info-item .label {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
    }

    .info-item .value {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-dark);
    }

    .items-list {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        border: 2px solid var(--border-color);
        margin-top: 1rem;
    }

    .items-list .item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .items-list .item-row:last-child {
        border-bottom: none;
    }

    .items-list .item-row .item-info {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .items-list .item-row .item-info .name {
        font-weight: 600;
    }

    .items-list .item-row .item-info .code {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .items-list .item-row .item-qty {
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .form-card {
            padding: 1.25rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .items-list .item-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.3rem;
        }
    }
</style>

<!-- ============================================ -->
<!-- PAGE HEADER -->
<!-- ============================================ -->
<div class="page-header">
    <div>
        <h1><i class="fas fa-edit"></i> Edit Stock Out</h1>
        <p>Editing stock out #{{ $stockOut->stock_out_code }}</p>
    </div>
    <a href="{{ route('inventory.stock-out.show', $stockOut->id) }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Details
    </a>
</div>

<!-- ============================================ -->
<!-- FORM -->
<!-- ============================================ -->
<div class="form-card">
    <form method="POST" action="{{ route('inventory.stock-out.update', $stockOut->id) }}" id="editForm">
        @csrf
        @method('PUT')

        <!-- Status Info -->
        <div class="info-box">
            <div class="info-grid">
                <div class="info-item">
                    <div class="label">Stock Out Code</div>
                    <div class="value">{{ $stockOut->stock_out_code }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Type</div>
                    <div class="value">
                        @if($stockOut->type === 'sell')
                            <i class="fas fa-shopping-cart text-success"></i> Sale
                        @else
                            <i class="fas fa-exchange-alt text-primary"></i> Transfer
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="label">Status</div>
                    <div class="value">
                        <span class="status-badge {{ $stockOut->status }}">
                            {{ ucfirst($stockOut->status) }}
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="label">Created At</div>
                    <div class="value">{{ $stockOut->created_at->format('d M Y, h:i A') }}</div>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- ITEM SELECTION -->
        <!-- ============================================ -->
        <div class="row mb-3">
            <div class="col-md-12">
                <h5 class="mb-3"><i class="fas fa-boxes"></i> Items</h5>
                
                @php
                    $itemsData = $stockOut->items ?? [];
                @endphp

                <div class="items-list">
                    @forelse($itemsData as $index => $item)
                        <div class="item-row">
                            <div class="item-info">
                                <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                <span class="code">{{ $item['code'] ?? 'N/A' }}</span>
                                <span class="name">{{ $item['name'] ?? 'Unknown Item' }}</span>
                            </div>
                            <div class="item-qty">
                                <i class="fas fa-times"></i> {{ $item['quantity'] ?? 0 }}
                                @if(isset($item['price']))
                                    <span class="text-muted ms-2">@ ₹ {{ number_format($item['price'], 2) }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center mb-0">No items found in this stock out.</p>
                    @endforelse
                </div>

                @if($stockOut->type === 'sell' && count($itemsData) > 0)
                    @php
                        $subtotal = 0;
                        foreach($itemsData as $item) {
                            $subtotal += ($item['quantity'] ?? 0) * ($item['price'] ?? 0);
                        }
                    @endphp
                    <div class="mt-2 text-end">
                        <strong>Subtotal: </strong> ₹ {{ number_format($subtotal, 2) }}
                        @if($stockOut->discount_amount)
                            <br><strong>Discount: </strong> - ₹ {{ number_format($stockOut->discount_amount, 2) }}
                        @endif
                        @if($stockOut->tax_amount)
                            <br><strong>Tax: </strong> + ₹ {{ number_format($stockOut->tax_amount, 2) }}
                        @endif
                        <br><strong style="font-size: 1.2rem; color: var(--success-color);">Total: ₹ {{ number_format($stockOut->total_amount ?? $subtotal, 2) }}</strong>
                    </div>
                @endif
            </div>
        </div>

        <!-- ============================================ -->
        <!-- CUSTOMER INFORMATION (for Sell) -->
        <!-- ============================================ -->
        @if($stockOut->type === 'sell')
        <div class="row mb-3">
            <div class="col-md-12">
                <h5 class="mb-3"><i class="fas fa-user"></i> Customer Information</h5>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Customer Name <span class="required">*</span></label>
                <input type="text" name="customer_name" class="form-control @error('customer_name') error @enderror" 
                       value="{{ old('customer_name', $stockOut->customer_name) }}" required>
                @error('customer_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Phone <span class="required">*</span></label>
                <input type="text" name="customer_phone" class="form-control @error('customer_phone') error @enderror" 
                       value="{{ old('customer_phone', $stockOut->customer_phone) }}" required>
                @error('customer_phone')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Email</label>
                <input type="email" name="customer_email" class="form-control @error('customer_email') error @enderror" 
                       value="{{ old('customer_email', $stockOut->customer_email) }}">
                @error('customer_email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Customer Type</label>
                <select name="customer_type" class="form-select">
                    <option value="individual" {{ $stockOut->customer_type == 'individual' ? 'selected' : '' }}>Individual</option>
                    <option value="business" {{ $stockOut->customer_type == 'business' ? 'selected' : '' }}>Business</option>
                </select>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">GST Number</label>
                <input type="text" name="gst_number" class="form-control" 
                       value="{{ old('gst_number', $stockOut->gst_number) }}">
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">PAN Number</label>
                <input type="text" name="pan_number" class="form-control" 
                       value="{{ old('pan_number', $stockOut->pan_number) }}">
            </div>
            <div class="col-md-12 mb-2">
                <label class="form-label">Address</label>
                <textarea name="customer_address" class="form-control" rows="2">{{ old('customer_address', $stockOut->customer_address) }}</textarea>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- PAYMENT INFORMATION (for Sell) -->
        <!-- ============================================ -->
        <div class="row mb-3">
            <div class="col-md-12">
                <h5 class="mb-3"><i class="fas fa-credit-card"></i> Payment Information</h5>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" class="form-select">
                    <option value="cash" {{ $stockOut->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="cod" {{ $stockOut->payment_method == 'cod' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
                    <option value="card" {{ $stockOut->payment_method == 'card' ? 'selected' : '' }}>Card</option>
                    <option value="upi" {{ $stockOut->payment_method == 'upi' ? 'selected' : '' }}>UPI</option>
                    <option value="bank_transfer" {{ $stockOut->payment_method == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="pg" {{ $stockOut->payment_method == 'pg' ? 'selected' : '' }}>Payment Gateway</option>
                </select>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Amount Received</label>
                <input type="number" name="amount_received" class="form-control" 
                       value="{{ old('amount_received', $stockOut->amount_received) }}" step="0.01" min="0">
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Total Amount</label>
                <input type="text" class="form-control" value="₹ {{ number_format($stockOut->total_amount ?? 0, 2) }}" disabled>
            </div>
            @if(($stockOut->amount_received ?? 0) > ($stockOut->total_amount ?? 0))
            <div class="col-md-12">
                <div class="alert alert-success">
                    <i class="fas fa-arrow-left"></i> Change to return: ₹ {{ number_format(($stockOut->amount_received ?? 0) - ($stockOut->total_amount ?? 0), 2) }}
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- ============================================ -->
        <!-- TRANSFER PAYMENT (for WH to WH) -->
        <!-- ============================================ -->
        @if($stockOut->type === 'transfer' && $stockOut->sub_type === 'warehouse_to_warehouse')
        <div class="row mb-3">
            <div class="col-md-12">
                <h5 class="mb-3"><i class="fas fa-money-bill-wave"></i> Transfer Payment</h5>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Payment Method</label>
                <select name="transfer_payment_method" class="form-select">
                    <option value="cash" {{ $stockOut->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="bank_transfer" {{ $stockOut->payment_method == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="cheque" {{ $stockOut->payment_method == 'cheque' ? 'selected' : '' }}>Cheque</option>
                    <option value="online" {{ $stockOut->payment_method == 'online' ? 'selected' : '' }}>Online Transfer</option>
                </select>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Total Amount</label>
                <input type="number" name="transfer_total_amount" class="form-control" 
                       value="{{ old('transfer_total_amount', $stockOut->total_amount) }}" step="0.01" min="0">
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Amount Received</label>
                <input type="number" name="transfer_amount_received" class="form-control" 
                       value="{{ old('transfer_amount_received', $stockOut->amount_received) }}" step="0.01" min="0">
            </div>
            @if(($stockOut->amount_received ?? 0) > ($stockOut->total_amount ?? 0))
            <div class="col-md-12">
                <div class="alert alert-success">
                    <i class="fas fa-arrow-left"></i> Change to return: ₹ {{ number_format(($stockOut->amount_received ?? 0) - ($stockOut->total_amount ?? 0), 2) }}
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- ============================================ -->
        <!-- ADDITIONAL INFO -->
        <!-- ============================================ -->
        <div class="row mb-3">
            <div class="col-md-12">
                <h5 class="mb-3"><i class="fas fa-info-circle"></i> Additional Information</h5>
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label">Expected Arrival Date</label>
                <input type="date" name="expected_arrival_date" class="form-control" 
                       value="{{ old('expected_arrival_date', $stockOut->expected_arrival_date ? \Carbon\Carbon::parse($stockOut->expected_arrival_date)->format('Y-m-d') : '') }}">
            </div>
            <div class="col-md-12 mb-2">
                <label class="form-label">Reason</label>
                <input type="text" name="reason" class="form-control" 
                       value="{{ old('reason', $stockOut->reason) }}" placeholder="Reason for stock out">
            </div>
            <div class="col-md-12 mb-2">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $stockOut->notes) }}</textarea>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- LOGISTICS (if exists) -->
        <!-- ============================================ -->
        @if($stockOut->logistics)
        @php
            $logistics = is_string($stockOut->logistics) ? json_decode($stockOut->logistics, true) : $stockOut->logistics;
        @endphp
        <div class="row mb-3">
            <div class="col-md-12">
                <h5 class="mb-3"><i class="fas fa-truck"></i> Logistics Details</h5>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Transporter</label>
                <input type="text" name="logistics_transporter" class="form-control" 
                       value="{{ old('logistics_transporter', $logistics['transporter'] ?? $logistics['transporter_name'] ?? '') }}">
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Vehicle Number</label>
                <input type="text" name="logistics_vehicle" class="form-control" 
                       value="{{ old('logistics_vehicle', $logistics['vehicle'] ?? $logistics['vehicle_number'] ?? '') }}">
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Driver Name</label>
                <input type="text" name="logistics_driver" class="form-control" 
                       value="{{ old('logistics_driver', $logistics['driver'] ?? $logistics['driver_name'] ?? '') }}">
            </div>
            @if(isset($logistics['driver_phone']))
            <div class="col-md-4 mb-2">
                <label class="form-label">Driver Phone</label>
                <input type="text" name="logistics_driver_phone" class="form-control" 
                       value="{{ old('logistics_driver_phone', $logistics['driver_phone'] ?? '') }}">
            </div>
            @endif
            @if(isset($logistics['notes']))
            <div class="col-md-12 mb-2">
                <label class="form-label">Logistics Notes</label>
                <input type="text" name="logistics_notes" class="form-control" 
                       value="{{ old('logistics_notes', $logistics['notes'] ?? '') }}">
            </div>
            @endif
        </div>
        @endif

        <!-- ============================================ -->
        <!-- SUBMIT BUTTONS -->
        <!-- ============================================ -->
        <hr>
        <div class="text-center">
            <button type="submit" class="btn-update">
                <i class="fas fa-save"></i> Update Stock Out
            </button>
            <a href="{{ route('inventory.stock-out.show', $stockOut->id) }}" class="btn-cancel ms-2">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // Customer type toggle
        $('select[name="customer_type"]').on('change', function() {
            const val = $(this).val();
            // Show/hide business fields if needed
            if (val === 'business') {
                $('input[name="gst_number"]').closest('.col-md-4').show();
                $('input[name="pan_number"]').closest('.col-md-4').show();
            } else {
                $('input[name="gst_number"]').closest('.col-md-4').hide();
                $('input[name="pan_number"]').closest('.col-md-4').hide();
            }
        });

        // Initialize visibility
        const customerType = $('select[name="customer_type"]').val();
        if (customerType === 'business') {
            $('input[name="gst_number"]').closest('.col-md-4').show();
            $('input[name="pan_number"]').closest('.col-md-4').show();
        } else {
            $('input[name="gst_number"]').closest('.col-md-4').hide();
            $('input[name="pan_number"]').closest('.col-md-4').hide();
        }

        // Form validation
        $('#editForm').on('submit', function(e) {
            const name = $('input[name="customer_name"]').val().trim();
            const phone = $('input[name="customer_phone"]').val().trim();
            
            // Only validate for sell type
            @if($stockOut->type === 'sell')
                if (!name) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Validation Error',
                        text: 'Please enter customer name.',
                        icon: 'warning',
                        confirmButtonColor: '#ef4444'
                    });
                    $('input[name="customer_name"]').focus().addClass('error');
                    return false;
                }
                if (!phone) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Validation Error',
                        text: 'Please enter customer phone number.',
                        icon: 'warning',
                        confirmButtonColor: '#ef4444'
                    });
                    $('input[name="customer_phone"]').focus().addClass('error');
                    return false;
                }
            @endif
            
            // Remove error class on focus
            $('input').on('focus', function() {
                $(this).removeClass('error');
            });
        });
    });
</script>

@endsection
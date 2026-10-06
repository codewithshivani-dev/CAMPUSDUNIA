<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Warehouse Transfer #{{ $transfer->transfer_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            padding: 40px; 
            background: #fff;
            color: #333;
        }
        .header { 
            text-align: center; 
            border-bottom: 3px solid #000; 
            padding-bottom: 20px; 
            margin-bottom: 25px; 
        }
        .header h1 { 
            margin: 0; 
            font-size: 28px; 
            color: #1a1a2e;
            letter-spacing: 2px;
        }
        .header .sub-title { 
            font-size: 14px; 
            color: #666; 
            margin-top: 5px;
        }
        .header .transfer-code {
            font-size: 18px;
            font-weight: bold;
            background: #f0f0f0;
            padding: 5px 20px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 8px;
        }
        .status { 
            display: inline-block; 
            padding: 4px 16px; 
            border-radius: 20px; 
            font-weight: bold; 
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 8px;
        }
        .status-completed { background: #d4edda; color: #155724; }
        .status-approved { background: #cce5ff; color: #004085; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-in-transit { background: #cce5ff; color: #004085; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .transfer-flow {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            margin: 25px 0;
            border: 2px solid #e0e0e0;
        }
        .transfer-flow .warehouse-box {
            padding: 15px 25px;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            min-width: 180px;
        }
        .transfer-flow .warehouse-box.source {
            background: #fee2e2;
            color: #991b1b;
            border: 2px solid #fecaca;
        }
        .transfer-flow .warehouse-box.destination {
            background: #d1fae5;
            color: #065f46;
            border: 2px solid #a7f3d0;
        }
        .transfer-flow .arrow {
            font-size: 2.5rem;
            color: #94a3b8;
            margin: 0 20px;
        }
        .transfer-flow .quantity-badge {
            background: #e0e7ff;
            color: #4338ca;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 1rem;
            font-weight: 700;
            margin-left: 20px;
        }
        .receipt-info { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 25px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .receipt-info .left, .receipt-info .right { 
            width: 48%; 
        }
        .receipt-info .label { 
            font-weight: bold; 
            color: #555;
            display: inline-block;
            width: 140px;
        }
        .receipt-info p {
            margin: 4px 0;
            font-size: 13px;
        }
        .receipt-info .value {
            font-weight: 600;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            font-size: 14px;
        }
        table th { 
            background: #1a1a2e; 
            color: white; 
            padding: 12px 15px; 
            text-align: left; 
            font-weight: 600;
        }
        table td { 
            padding: 10px 15px; 
            border-bottom: 1px solid #e0e0e0; 
        }
        table tr:hover {
            background: #f8f9fa;
        }
        .total-row { 
            font-weight: bold; 
            background: #f5f5f5 !important;
        }
        .total-row td {
            border-top: 2px solid #1a1a2e;
        }
        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-info { background: #cce5ff; color: #004085; }
        .badge-secondary { background: #e2e3e5; color: #383d41; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .footer { 
            margin-top: 40px; 
            text-align: center; 
            border-top: 2px solid #000; 
            padding-top: 20px; 
            color: #666; 
            font-size: 13px;
        }
        .footer .signature-area {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
            padding-top: 20px;
        }
        .footer .signature-area div {
            text-align: center;
        }
        .footer .signature-area .line {
            width: 150px;
            border-bottom: 1px solid #333;
            margin: 30px auto 5px auto;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0,0,0,0.03);
            font-weight: bold;
            pointer-events: none;
            z-index: 0;
        }
        .content { position: relative; z-index: 1; }
        .barcode {
            text-align: center;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
            font-size: 18px;
            letter-spacing: 2px;
            color: #333;
        }
        .notes-section {
            margin-top: 25px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #1a1a2e;
        }
        .notes-section strong {
            font-size: 13px;
        }
        .notes-section p {
            margin: 5px 0 0 0;
            font-size: 13px;
            color: #555;
        }
        .receipt-refs {
            margin-top: 20px;
            padding: 15px;
            background: #e8edf2;
            border-radius: 8px;
            display: flex;
            justify-content: space-around;
        }
        .receipt-refs .ref-item {
            text-align: center;
        }
        .receipt-refs .ref-item .ref-label {
            font-size: 11px;
            color: #666;
        }
        .receipt-refs .ref-item .ref-value {
            font-weight: 600;
            font-size: 14px;
        }
        @media print {
            .no-print { display: none !important; }
            body { padding: 20px; }
            .transfer-flow { background: #f8f9fa !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .transfer-flow .warehouse-box { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            table th { background: #1a1a2e !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .status { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .badge { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .receipt-info { background: #f8f9fa !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="watermark">{{ $transfer->status_text }}</div>
    
    <div class="content">
        <div class="header">
            <h1>📦 WAREHOUSE TRANSFER</h1>
            <div class="sub-title">Inventory Transfer Voucher</div>
            <div class="transfer-code">{{ $transfer->transfer_code }}</div>
            <div>
                <span class="status status-{{ strtolower($transfer->status) }}">
                    {{ $transfer->status_text }}
                </span>
                @if($transfer->is_overdue)
                    <span class="status" style="background: #f8d7da; color: #721c24;">OVERDUE</span>
                @endif
            </div>
        </div>

        <!-- Transfer Flow -->
        <div class="transfer-flow">
            <div class="warehouse-box source">
                <i class="fas fa-arrow-right"></i> SOURCE
                <br>
                <strong>{{ $transfer->fromWarehouse->warehouse_name ?? 'N/A' }}</strong>
                <br>
                <small style="font-weight: normal; color: #666;">{{ $transfer->fromWarehouse->warehouse_code ?? '' }}</small>
            </div>
            <div class="arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
            <div class="warehouse-box destination">
                <i class="fas fa-arrow-left"></i> DESTINATION
                <br>
                <strong>{{ $transfer->toWarehouse->warehouse_name ?? 'N/A' }}</strong>
                <br>
                <small style="font-weight: normal; color: #666;">{{ $transfer->toWarehouse->warehouse_code ?? '' }}</small>
            </div>
            <div class="quantity-badge">
                {{ number_format($transfer->quantity, 2) }} units
            </div>
        </div>

        <div class="receipt-info">
            <div class="left">
                <p><span class="label">Item:</span> <span class="value">{{ $transfer->item->item_name ?? 'N/A' }}</span></p>
                <p><span class="label">Item Code:</span> <span class="value">{{ $transfer->item->item_code ?? 'N/A' }}</span></p>
                <p><span class="label">SKU:</span> <span class="value">{{ $transfer->item->sku ?? 'N/A' }}</span></p>
                <p><span class="label">Category:</span> <span class="value">{{ $transfer->item->category->category_name ?? 'N/A' }}</span></p>
                <p><span class="label">Unit:</span> <span class="value">{{ $transfer->item->unit->unit_name ?? 'N/A' }}</span></p>
                @if($transfer->item->batch_number)
                <p><span class="label">Batch:</span> <span class="badge badge-secondary">{{ $transfer->item->batch_number }}</span></p>
                @endif
                @if($transfer->item->serial_number)
                <p><span class="label">Serial:</span> <span class="badge badge-secondary">{{ $transfer->item->serial_number }}</span></p>
                @endif
            </div>
            <div class="right">
                <p><span class="label">Transfer Date:</span> <span class="value">{{ $transfer->transfer_date ? $transfer->transfer_date->format('d M Y') : 'N/A' }}</span></p>
                @if($transfer->expected_arrival_date)
                <p><span class="label">Expected Arrival:</span> <span class="value">{{ $transfer->expected_arrival_date->format('d M Y') }}</span></p>
                @endif
                @if($transfer->received_date)
                <p><span class="label">Received Date:</span> <span class="value">{{ $transfer->received_date->format('d M Y, h:i A') }}</span></p>
                @endif
                <p><span class="label">Created By:</span> <span class="value">{{ $transfer->creator->name ?? 'System' }}</span></p>
                @if($transfer->approved_by)
                <p><span class="label">Approved By:</span> <span class="value">{{ $transfer->approver->name ?? 'N/A' }}</span></p>
                @endif
                @if($transfer->received_by)
                <p><span class="label">Received By:</span> <span class="value">{{ $transfer->receiver->name ?? 'N/A' }}</span></p>
                @endif
                @if($transfer->reason)
                <p><span class="label">Reason:</span> <span class="value">{{ $transfer->reason }}</span></p>
                @endif
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 40%;">Description</th>
                    <th style="width: 20%;">From</th>
                    <th style="width: 20%;">To</th>
                    <th style="width: 20%;">Quantity</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $transfer->item->item_name ?? 'N/A' }}</strong>
                        <br>
                        <small style="color: #888;">{{ $transfer->item->item_code ?? '' }}</small>
                        @if($transfer->item->brand)
                        <br>
                        <small style="color: #888;">Brand: {{ $transfer->item->brand }}</small>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $transfer->fromWarehouse->warehouse_name ?? 'N/A' }}</strong>
                        <br>
                        <small style="color: #888;">{{ $transfer->fromWarehouse->warehouse_code ?? '' }}</small>
                    </td>
                    <td>
                        <strong>{{ $transfer->toWarehouse->warehouse_name ?? 'N/A' }}</strong>
                        <br>
                        <small style="color: #888;">{{ $transfer->toWarehouse->warehouse_code ?? '' }}</small>
                    </td>
                    <td><strong>{{ number_format($transfer->quantity, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Associated Receipts -->
        @if($transfer->receiptOut || $transfer->receiptIn)
        <div class="receipt-refs">
            @if($transfer->receiptOut)
            <div class="ref-item">
                <div class="ref-label">OUT RECEIPT</div>
                <div class="ref-value">{{ $transfer->receiptOut->receipt_number ?? 'N/A' }}</div>
                <small style="color: #888;">{{ $transfer->receiptOut->status_text ?? '' }}</small>
            </div>
            @endif
            @if($transfer->receiptIn)
            <div class="ref-item">
                <div class="ref-label">IN RECEIPT</div>
                <div class="ref-value">{{ $transfer->receiptIn->receipt_number ?? 'N/A' }}</div>
                <small style="color: #888;">{{ $transfer->receiptIn->status_text ?? '' }}</small>
            </div>
            @endif
            <div class="ref-item">
                <div class="ref-label">STATUS</div>
                <div class="ref-value">
                    <span class="badge badge-{{ strtolower($transfer->status) == 'completed' ? 'success' : (strtolower($transfer->status) == 'cancelled' ? 'danger' : 'warning') }}">
                        {{ $transfer->status_text }}
                    </span>
                </div>
            </div>
        </div>
        @endif

        @if($transfer->notes)
        <div class="notes-section">
            <strong>📝 Notes:</strong>
            <p>{{ $transfer->notes }}</p>
        </div>
        @endif

        <!-- Barcode -->
        <div class="barcode">
            {{ $transfer->transfer_code }}
        </div>

        <div class="footer">
            <p style="font-size: 12px; color: #888;">This is a system generated transfer document. Valid without signature.</p>
            
            <div class="signature-area">
                <div>
                    <div class="line"></div>
                    <span style="font-size: 12px;">Created By</span>
                    <br>
                    <span style="font-size: 11px; color: #888;">{{ $transfer->creator->name ?? 'System' }}</span>
                </div>
                <div>
                    <div class="line"></div>
                    <span style="font-size: 12px;">Approved By</span>
                    <br>
                    <span style="font-size: 11px; color: #888;">{{ $transfer->approver->name ?? 'N/A' }}</span>
                </div>
                <div>
                    <div class="line"></div>
                    <span style="font-size: 12px;">Received By</span>
                    <br>
                    <span style="font-size: 11px; color: #888;">{{ $transfer->receiver->name ?? 'N/A' }}</span>
                </div>
            </div>

            <p style="margin-top: 20px; font-size: 11px; color: #aaa;">
                Transfer #{{ $transfer->transfer_code }} | Generated: {{ now()->format('d M Y, h:i A') }}
            </p>
            <p style="font-size: 11px; color: #aaa;">
                <i class="fas fa-check-circle"></i> This document is authorized for inventory transfer
            </p>
        </div>

        <div class="no-print" style="text-align: center; margin-top: 30px; padding: 20px; border-top: 2px solid #e0e0e0;">
            <button onclick="window.print()" style="padding: 12px 40px; font-size: 16px; cursor: pointer; background: #1a1a2e; color: white; border: none; border-radius: 8px; margin-right: 10px;">
                🖨️ Print Transfer
            </button>
            <button onclick="window.close()" style="padding: 12px 40px; font-size: 16px; cursor: pointer; background: #6c757d; color: white; border: none; border-radius: 8px;">
                ✖ Close
            </button>
        </div>
    </div>
</body>
</html>
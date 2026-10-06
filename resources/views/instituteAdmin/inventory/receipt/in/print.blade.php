<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>In Receipt #{{ $receipt->receipt_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
        .header .receipt-number {
            font-size: 18px;
            font-weight: bold;
            background: #f0f0f0;
            padding: 5px 20px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 8px;
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
            width: 120px;
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
        .status { 
            display: inline-block; 
            padding: 4px 16px; 
            border-radius: 20px; 
            font-weight: bold; 
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-completed { background: #d4edda; color: #155724; }
        .status-approved { background: #cce5ff; color: #004085; }
        .status-draft { background: #e2e3e5; color: #383d41; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
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
        @media print {
            .no-print { display: none !important; }
            body { padding: 20px; }
            .receipt-info { background: #f8f9fa !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            table th { background: #1a1a2e !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .status { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .badge { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="watermark">{{ $receipt->status_text }}</div>
    
    <div class="content">
        <div class="header">
            <h1>📄 IN RECEIPT</h1>
            <div class="sub-title">Inventory Receiving Voucher</div>
            <div class="receipt-number">{{ $receipt->receipt_number }}</div>
            <div style="margin-top: 8px;">
                <span class="status status-{{ strtolower($receipt->status) }}">
                    {{ $receipt->status_text }}
                </span>
                <span class="badge badge-info ms-2">{{ $receipt->receipt_type_text }}</span>
            </div>
        </div>

        <div class="receipt-info">
            <div class="left">
                <p><span class="label">Item Name:</span> <span class="value">{{ $receipt->item->item_name ?? 'N/A' }}</span></p>
                <p><span class="label">Item Code:</span> <span class="value">{{ $receipt->item->item_code ?? 'N/A' }}</span></p>
                <p><span class="label">SKU:</span> <span class="value">{{ $receipt->item->sku ?? 'N/A' }}</span></p>
                <p><span class="label">Category:</span> <span class="value">{{ $receipt->item->category->category_name ?? 'N/A' }}</span></p>
                <p><span class="label">Unit:</span> <span class="value">{{ $receipt->item->unit->unit_name ?? 'N/A' }}</span></p>
                @if(optional($receipt->item)->batch_number)
                    <p>
                        <span class="label">Batch:</span>
                        <span class="badge badge-secondary">
                            {{ optional($receipt->item)->batch_number }}
                        </span>
                    </p>
                @endif

                @if(optional($receipt->item)->serial_number)
                    <p>
                        <span class="label">Serial:</span>
                        <span class="badge badge-secondary">
                            {{ optional($receipt->item)->serial_number }}
                        </span>
                    </p>
                @endif
            </div>
            <div class="right">
                <p><span class="label">Receipt Type:</span> <span class="badge badge-info">{{ $receipt->receipt_type_text }}</span></p>
                <p><span class="label">Warehouse:</span> <span class="value">{{ $receipt->warehouse->warehouse_name ?? 'N/A' }}</span></p>
                <p><span class="label">Warehouse Code:</span> <span class="value">{{ $receipt->warehouse->warehouse_code ?? 'N/A' }}</span></p>
                @if($receipt->supplier_name)
                <p><span class="label">Supplier:</span> <span class="value">{{ $receipt->supplier_name }}</span></p>
                @endif
                @if($receipt->supplier_invoice_number)
                <p><span class="label">Invoice #:</span> <span class="value">{{ $receipt->supplier_invoice_number }}</span></p>
                @endif
                <p><span class="label">Date:</span> <span class="value">{{ $receipt->created_at->format('d M Y, h:i A') }}</span></p>
                @if($receipt->approved_at)
                <p><span class="label">Approved:</span> <span class="value">{{ $receipt->approved_at->format('d M Y, h:i A') }}</span></p>
                @endif
                @if($receipt->received_by)
                <p><span class="label">Received By:</span> <span class="value">{{ $receipt->receiver->name ?? 'N/A' }}</span></p>
                @endif
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 40%;">Description</th>
                    <th style="width: 20%;">Quantity</th>
                    <th style="width: 20%;">Unit Price</th>
                    <th style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $receipt->item->item_name ?? 'N/A' }}</strong>
                        <br>
                        <small style="color: #888;">{{ $receipt->item->item_code ?? '' }}</small>
                        @if(optional($receipt->item)->brand)
                            <br>
                            <small style="color: #888;">
                                Brand: {{ optional($receipt->item)->brand }}
                            </small>
                        @endif
                    </td>
                    <td>{{ number_format($receipt->quantity, 2) }}</td>
                    <td>₹{{ number_format($receipt->unit_price, 2) }}</td>
                    <td>₹{{ number_format($receipt->total_price, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right; font-size: 16px;">Grand Total</td>
                    <td style="font-size: 16px; color: #1a1a2e;">₹{{ number_format($receipt->total_price, 2) }}</td>
                </tr>
            </tbody>
        </table>

        @if($receipt->notes)
        <div style="margin-top: 25px; padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #1a1a2e;">
            <strong style="font-size: 13px;">📝 Notes:</strong>
            <p style="margin: 5px 0 0 0; font-size: 13px; color: #555;">{{ $receipt->notes }}</p>
        </div>
        @endif

        <!-- Barcode -->
        <div class="barcode">
            {{ $receipt->receipt_number }}
        </div>

        <div class="footer">
            <p style="font-size: 12px; color: #888;">This is a system generated receipt. Valid without signature.</p>
            
            <div class="signature-area">
                <div>
                    <div class="line"></div>
                    <span style="font-size: 12px;">Received By</span>
                    <br>
                    <span style="font-size: 11px; color: #888;">{{ $receipt->receiver->name ?? $receipt->creator->name ?? 'System' }}</span>
                </div>
                <div>
                    <div class="line"></div>
                    <span style="font-size: 12px;">Approved By</span>
                    <br>
                    <span style="font-size: 11px; color: #888;">{{ $receipt->approver->name ?? 'N/A' }}</span>
                </div>
                <div>
                    <div class="line"></div>
                    <span style="font-size: 12px;">Created By</span>
                    <br>
                    <span style="font-size: 11px; color: #888;">{{ $receipt->creator->name ?? 'System' }}</span>
                </div>
            </div>

            <p style="margin-top: 20px; font-size: 11px; color: #aaa;">
                Receipt #{{ $receipt->receipt_number }} | Generated: {{ now()->format('d M Y, h:i A') }}
            </p>
            <p style="font-size: 11px; color: #aaa;">
                <i class="fas fa-check-circle"></i> This receipt is valid and authorized
            </p>
        </div>

        <div class="no-print" style="text-align: center; margin-top: 30px; padding: 20px; border-top: 2px solid #e0e0e0;">
            <button onclick="window.print()" style="padding: 12px 40px; font-size: 16px; cursor: pointer; background: #1a1a2e; color: white; border: none; border-radius: 8px; margin-right: 10px;">
                🖨️ Print Receipt
            </button>
            <button onclick="window.close()" style="padding: 12px 40px; font-size: 16px; cursor: pointer; background: #6c757d; color: white; border: none; border-radius: 8px;">
                ✖ Close
            </button>
        </div>
    </div>
</body>
</html>
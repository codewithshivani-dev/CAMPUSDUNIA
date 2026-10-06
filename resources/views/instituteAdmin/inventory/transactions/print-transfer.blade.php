<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Transfer Receipt #{{ $transaction->stock_out_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            padding: 40px; 
            background: #fff;
            color: #333;
        }
        .receipt-wrapper {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #1a1a2e;
            border-radius: 12px;
            padding: 40px;
            position: relative;
        }
        .header { 
            display: flex;
            justify-content: space-between;
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
            background: #1a1a2e;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 8px;
            letter-spacing: 2px;
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
        .status-in-transit { background: #f6ff7e; color: #80780f; }
        .status-approved { background: #cce5ff; color: #004085; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-cancelled { background: #f8d7da; color: #721c24; }

        .receipt-info { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 25px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .receipt-info .left, .receipt-info .right { width: 48%; }
        .receipt-info .label { 
            font-weight: bold; 
            color: #555;
            display: inline-block;
            width: 110px;
        }
        .receipt-info p { margin: 4px 0; font-size: 13px; }
        .receipt-info .value { font-weight: 600; }

        .transfer-path {
            margin: 0 0 15px 0;
            padding: 10px;
            background: #e3f2fd;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #1565c0;
        }
        .transfer-path .arrow { font-size: 24px; color: #1565c0; margin: 0 15px; }
        .transfer-path .location { font-weight: bold; font-size: 16px; }
        .transfer-path .location small { font-weight: normal; color: #666; font-size: 12px; }

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
        .total-row { 
            font-weight: bold; 
            background: #f5f5f5 !important;
        }
        .total-row td {
            border-top: 2px solid #1a1a2e;
        }

        /* .barcode-section {
            margin: 25px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            text-align: center;
            border: 2px dashed #1a1a2e;
        } */

        .barcode-section .barcode-label {
            font-size: 10px;
            color: #666;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .barcode-section .barcode {
            font-family: 'Courier New', monospace;
            font-size: 28px;
            letter-spacing: 3px;
            color: #1a1a2e;
            padding: 10px;
            background: white;
            display: inline-block;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .footer { 
            margin-top: 30px; 
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
        .footer .signature-area div { text-align: center; }
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

        @media print {
            .no-print { display: none !important; }
            body { padding: 20px; }
            .receipt-wrapper { border: none; padding: 0; }
            .receipt-info { background: #f8f9fa !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            table th { background: #1a1a2e !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .status { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .transfer-path { background: #e3f2fd !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .barcode-section { background: #f8f9fa !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    @php
        $items = $transaction->items ?? [];
        $totalQuantity = array_sum(array_column($items, 'quantity'));
        $fromLocation = $transaction->fromWarehouse->warehouse_name ?? $transaction->fromStore->store_name ?? 'N/A';
        $toLocation = $transaction->toWarehouse->warehouse_name ?? $transaction->toStore->store_name ?? 'N/A';
        $logistics = $transaction->logistics ?? null;
        
        // Generate barcode content
        $barcodeContent = $transaction->stock_out_code . '|' . $transaction->id . '|' . 
            ($transaction->from_store_id ?? $transaction->from_warehouse_id ?? '') . '|' . 
            ($transaction->to_store_id ?? $transaction->to_warehouse_id ?? '');
        
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($barcodeContent);
    @endphp

    <div class="watermark">TRANSFER</div>
    
    <div class="receipt-wrapper">
        <div class="content">
            <!-- Header -->
            <div class="header">
                <div>
                    <h1>🚚 TRANSFER RECEIPT</h1>
                    <!-- <div class="sub-title">Stock Movement / Transfer Voucher</div> -->
                    <div class="receipt-number">{{ $transaction->stock_out_code }}
                        <span class="status status-{{ $transaction->status }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                        <span class="badge" style="display: inline-block; padding: 2px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; background: #e0e7ff; color: #3730a3; margin-left: 8px;">
                            {{ str_replace('_', ' ', ucfirst($transaction->sub_type ?? 'Transfer')) }}
                        </span>
                    </div>
                </div>
                <!-- QR Code & Barcode Section -->
                <div class="barcode-section">
                    
                    <div style="margin: 10px 0;">
                        <img src="{{ $qrUrl }}" alt="QR Code" width="50" height="50">
                    </div>
                    <small class="barcode-label">📱 Scan to Track Shipment</small>
                    
                    <!-- <div style="margin-top: 15px;">
                        <div class="barcode">
                            {{ $transaction->stock_out_code }}
                        </div>
                    </div> -->
                    
                    <!-- <div class="tracking-info" style="margin-top: 10px; font-size: 12px; color: #666;">
                        <strong>Tracking ID:</strong> {{ $transaction->stock_out_code }}
                        <br>
                        <span style="font-size: 11px; color: #999;">
                            Scan QR code or enter tracking ID at receiving location to verify shipment
                        </span>
                    </div> -->
                </div>
            </div>

            <!-- Transfer Path -->
            <div class="transfer-path">
                <span class="location">
                    🏢 {{ $fromLocation }}
                    <small>(Source)</small>
                </span>
                <span class="arrow">➜</span>
                <span class="location">
                    🏢 {{ $toLocation }}
                    <small>(Destination)</small>
                </span>
            </div>

            <!-- Receipt Info -->
            <div class="receipt-info">
                <div class="left">
                    <p><span class="label">Transfer Type:</span> <span class="value">{{ str_replace('_', ' ', ucfirst($transaction->sub_type ?? 'N/A')) }}</span></p>
                    <p><span class="label">Created:</span> <span class="value">{{ $transaction->created_at->format('d M Y, h:i A') }}</span></p>
                    <p><span class="label">Created By:</span> <span class="value">{{ $transaction->creator->name ?? 'N/A' }}</span></p>
                    @if($transaction->approved_at)
                        <p><span class="label">Approved:</span> <span class="value">{{ \Carbon\Carbon::parse($transaction->approved_at)->format('d M Y, h:i A') }}</span></p>
                    @endif
                </div>
                <div class="right">
                    <p><span class="label">From:</span> <span class="value">{{ $fromLocation }}</span></p>
                    <p><span class="label">To:</span> <span class="value">{{ $toLocation }}</span></p>
                    @if($transaction->expected_arrival_date)
                        <p><span class="label">Expected:</span> <span class="value">{{ \Carbon\Carbon::parse($transaction->expected_arrival_date)->format('d M Y') }}</span></p>
                    @endif
                    @if($transaction->reason)
                        <p><span class="label">Reason:</span> <span class="value">{{ $transaction->reason }}</span></p>
                    @endif
                </div>
            </div>

            <!-- Items Table -->
            <table>
                <thead>
                    <tr>
                        <th style="width: 50%;">Item</th>
                        <th style="width: 25%;">Code</th>
                        <th style="width: 25%;">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td><strong>{{ $item['name'] ?? 'N/A' }}</strong></td>
                            <td><small style="color: #888;">{{ $item['code'] ?? 'N/A' }}</small></td>
                            <td>{{ $item['quantity'] ?? 0 }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="2" style="text-align: right; font-size: 16px;">Total Quantity</td>
                        <td style="font-size: 16px; font-weight: bold;">{{ $totalQuantity }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Logistics -->
            @if($logistics && is_array($logistics))
                <div style="margin-top: 15px; padding: 10px; background: #e8f5e9; border-radius: 8px; border-left: 4px solid #2e7d32; font-size: 13px;">
                    <strong><i class="fas fa-truck"></i> Logistics Details:</strong>
                    @if(isset($logistics['transporter']))
                        <span style="margin-left: 10px;">Transporter: {{ $logistics['transporter'] }}</span>
                    @endif
                    @if(isset($logistics['vehicle_number']) || isset($logistics['vehicle']))
                        <span style="margin-left: 10px;">Vehicle: {{ $logistics['vehicle_number'] ?? $logistics['vehicle'] ?? 'N/A' }}</span>
                    @endif
                    @if(isset($logistics['driver_name']) || isset($logistics['driver']))
                        <span style="margin-left: 10px;">Driver: {{ $logistics['driver_name'] ?? $logistics['driver'] ?? 'N/A' }}</span>
                    @endif
                    @if(isset($logistics['tracking_number']))
                        <span style="margin-left: 10px;">Tracking: {{ $logistics['tracking_number'] }}</span>
                    @endif
                </div>
            @endif

            <!-- Footer -->
            <div class="footer">
                <p style="font-size: 12px; color: #888;">This is a system generated transfer receipt. Valid without signature.</p>
                
                <div class="signature-area">
                    <div>
                        <div class="line"></div>
                        <span style="font-size: 12px;">Dispatched By</span>
                        <br>
                        <span style="font-size: 11px; color: #888;">{{ $transaction->creator->name ?? 'System' }}</span>
                    </div>
                    <div>
                        <div class="line"></div>
                        <span style="font-size: 12px;">Transport</span>
                        <br>
                        <span style="font-size: 11px; color: #888;">{{ $logistics['transporter'] ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <div class="line"></div>
                        <span style="font-size: 12px;">Received By</span>
                        <br>
                        <span style="font-size: 11px; color: #888;">
                            {{ $transaction->receiver->name ?? $toLocation ?? 'N/A' }}
                        </span>
                    </div>
                </div>

                <p style="margin-top: 20px; font-size: 11px; color: #aaa;">
                    Transfer #{{ $transaction->stock_out_code }} | Generated: {{ now()->format('d M Y, h:i A') }}
                    @if($transaction->print_count > 0)
                        | Print Count: {{ $transaction->print_count }}
                    @endif
                </p>
            </div>

            <!-- Print Actions -->
            <div class="no-print" style="text-align: center; margin-top: 30px; padding: 20px; border-top: 2px solid #e0e0e0;">
                <button onclick="window.print()" style="padding: 12px 40px; font-size: 16px; cursor: pointer; background: #1a1a2e; color: white; border: none; border-radius: 8px; margin-right: 10px;">
                    🖨️ Print Receipt
                </button>
                <button onclick="window.close()" style="padding: 12px 40px; font-size: 16px; cursor: pointer; background: #6c757d; color: white; border: none; border-radius: 8px;">
                    ✖ Close
                </button>
            </div>
        </div>
    </div>
</body>
</html>
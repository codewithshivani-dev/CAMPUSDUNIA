<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Transfer Receipt #{{ $stockOut->stock_out_code }}</title>
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
 
        .receipt-wrapper {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #1a1a2e;
            border-radius: 12px;
            padding: 40px;
            position: relative;
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
            background: #1a1a2e;
            color: white;
            padding: 8px 30px;
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

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-approved {
            background: #cce5ff;
            color: #004085;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
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
            width: 110px;
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

        .badge-transfer {
            background: #cce5ff;
            color: #004085;
        }
        
        /* QR Code & Barcode Styles */
        .barcode-section {
            margin: 25px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            text-align: center;
            border: 2px dashed #1a1a2e;
        }

        .barcode-section .barcode-label {
            font-size: 12px;
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

        .barcode-section .qr-code {
            display: inline-block;
            padding: 15px;
            background: white;
            border: 2px solid #1a1a2e;
            border-radius: 8px;
            margin-top: 10px;
        }

        .barcode-section .qr-code canvas {
            display: block;
            margin: 0 auto;
        }

        .barcode-section .tracking-info {
            margin-top: 10px;
            font-size: 12px;
            color: #666;
        }

        .barcode-section .tracking-info strong {
            color: #1a1a2e;
        }
        
        .transfer-path {
            margin: 15px 0;
            padding: 15px;
            background: #e3f2fd;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #1565c0;
        }

        .transfer-path .arrow {
            font-size: 24px;
            color: #1565c0;
            margin: 0 15px;
        }

        .transfer-path .location {
            font-weight: bold;
            font-size: 16px;
        }

        .transfer-path .location small {
            font-weight: normal;
            color: #666;
            font-size: 12px;
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

        .content {
            position: relative;
            z-index: 1;
        }

        .shipment-details {
            background: #fff8e1;
            padding: 12px 15px;
            border-radius: 8px;
            border-left: 4px solid #f57f17;
            margin: 10px 0;
        }

        .shipment-details .label {
            font-weight: bold;
            color: #555;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                padding: 20px;
            }

            .receipt-wrapper {
                border: none;
                padding: 0;
            }

            .receipt-info {
                background: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            table th {
                background: #1a1a2e !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .status {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .badge {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .barcode-section {
                background: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .transfer-path {
                background: #e3f2fd !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .shipment-details {
                background: #fff8e1 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    @php
        $items = is_array($stockOut->items) ? $stockOut->items : json_decode($stockOut->items, true);
        $totalQuantity = array_sum(array_column($items, 'quantity'));
        
        // Generate barcode content
        $barcodeContent = $stockOut->stock_out_code . '|' . $stockOut->id . '|' . ($stockOut->from_store_id ?? $stockOut->from_warehouse_id ?? '') . '|' . ($stockOut->to_store_id ?? $stockOut->to_warehouse_id ?? '');
        
        // QR Code URL (using Google Charts API for demo - you can use any QR library)
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($barcodeContent);
    @endphp

    <div class="watermark">TRANSFER</div>
    
    <div class="receipt-wrapper">
        <div class="content">
            <!-- Header -->
            <div class="header">
                <h1>🚚 TRANSFER RECEIPT</h1>
                <div class="sub-title">Stock Movement / Transfer Voucher</div>
                <div class="receipt-number">{{ $stockOut->stock_out_code }}</div>
                <div>
                    <span class="status status-{{ $stockOut->status }}">
                        {{ ucfirst($stockOut->status) }}
                    </span>
                    <span class="badge badge-transfer" style="margin-left: 8px;">
                        {{ str_replace('_', ' ', ucfirst($stockOut->sub_type ?? 'Transfer')) }}
                    </span>
                </div>
            </div>

            <!-- Transfer Path -->
            <div class="transfer-path">
                <span class="location">
                    @if($stockOut->fromWarehouse)
                        🏢 {{ $stockOut->fromWarehouse->warehouse_name }}
                        <small>({{ $stockOut->fromWarehouse->warehouse_code }})</small>
                    @elseif($stockOut->fromStore)
                        🏪 {{ $stockOut->fromStore->store_name }}
                        <small>({{ $stockOut->fromStore->store_code }})</small>
                    @else
                        N/A
                    @endif
                </span>
                <span class="arrow">➜</span>
                <span class="location">
                    @if($stockOut->toWarehouse)
                        🏢 {{ $stockOut->toWarehouse->warehouse_name }}
                        <small>({{ $stockOut->toWarehouse->warehouse_code }})</small>
                    @elseif($stockOut->toStore)
                        🏪 {{ $stockOut->toStore->store_name }}
                        <small>({{ $stockOut->toStore->store_code }})</small>
                    @else
                        N/A
                    @endif
                </span>
            </div>

            <!-- Receipt Info -->
            <div class="receipt-info">
                <div class="left">
                    <p><span class="label">Transfer Type:</span> <span class="value">{{ str_replace('_', ' ', ucfirst($stockOut->sub_type ?? 'N/A')) }}</span></p>
                    <p><span class="label">Created:</span> <span class="value">{{ $stockOut->created_at->format('d M Y, h:i A') }}</span></p>
                    <p><span class="label">Created By:</span> <span class="value">{{ $stockOut->creator->name ?? 'N/A' }}</span></p>
                    @if($stockOut->approved_at)
                    <p><span class="label">Approved:</span> <span class="value">{{ \Carbon\Carbon::parse($stockOut->approved_at)->format('d M Y, h:i A') }}</span></p>
                    @endif
                </div>
                <div class="right">
                    <p><span class="label">From:</span> <span class="value">
                        @if($stockOut->fromWarehouse)
                            {{ $stockOut->fromWarehouse->warehouse_name }}
                        @elseif($stockOut->fromStore)
                            {{ $stockOut->fromStore->store_name }}
                        @else
                            N/A
                        @endif
                    </span></p>
                    <p><span class="label">To:</span> <span class="value">
                        @if($stockOut->toWarehouse)
                            {{ $stockOut->toWarehouse->warehouse_name }}
                        @elseif($stockOut->toStore)
                            {{ $stockOut->toStore->store_name }}
                        @else
                            N/A
                        @endif
                    </span></p>
                    @if($stockOut->expected_arrival_date)
                    <p><span class="label">Expected:</span> <span class="value">{{ \Carbon\Carbon::parse($stockOut->expected_arrival_date)->format('d M Y') }}</span></p>
                    @endif
                    @if($stockOut->reason)
                    <p><span class="label">Reason:</span> <span class="value">{{ $stockOut->reason }}</span></p>
                    @endif
                    @if($stockOut->notes)
                    <p><span class="label">Notes:</span> <span class="value">{{ $stockOut->notes }}</span></p>
                    @endif
                </div>
            </div>

            <!-- Shipment Details -->
            <div class="shipment-details">
                <p><span class="label">📦 Shipment Details:</span></p>
                <p style="font-size: 13px; margin-top: 4px;">
                    <strong>Total Items:</strong> {{ count($items) }} | 
                    <strong>Total Quantity:</strong> {{ $totalQuantity }} units |
                    <strong>Status:</strong> {{ ucfirst($stockOut->status) }}
                </p>
                @if($stockOut->logistics)
                <p style="font-size: 13px; margin-top: 4px; color: #666;">
                    <strong>Logistics:</strong> {{ is_array($stockOut->logistics) ? json_encode($stockOut->logistics) : $stockOut->logistics }}
                </p>
                @endif
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

            <!-- QR Code & Barcode Section -->
            <div class="barcode-section">
                <div class="barcode-label">📱 Scan to Track Shipment</div>
                
                <!-- QR Code -->
                <div class="qr-code">
                    <img src="{{ $qrUrl }}" alt="QR Code" width="150" height="150">
                </div>
                
                <!-- Barcode -->
                <div style="margin-top: 15px;">
                    <div class="barcode">
                        {{ $stockOut->stock_out_code }}
                    </div>
                </div>
                
                <div class="tracking-info">
                    <strong>Tracking ID:</strong> {{ $stockOut->stock_out_code }}
                    <br>
                    <span style="font-size: 11px; color: #999;">
                        Scan QR code or enter tracking ID at receiving location to verify shipment
                    </span>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p style="font-size: 12px; color: #888;">This is a system generated transfer receipt. Valid without signature.</p>
                
                <div class="signature-area">
                    <div>
                        <div class="line"></div>
                        <span style="font-size: 12px;">Dispatched By</span>
                        <br>
                        <span style="font-size: 11px; color: #888;">{{ $stockOut->creator->name ?? 'System' }}</span>
                    </div>
                    <div>
                        <div class="line"></div>
                        <span style="font-size: 12px;">Transport</span>
                        <br>
                        <span style="font-size: 11px; color: #888;">{{ $stockOut->logistics['transport'] ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <div class="line"></div>
                        <span style="font-size: 12px;">Received By</span>
                        <br>
                        <span style="font-size: 11px; color: #888;">
                            {{ $stockOut->receiver->name ?? $stockOut->toStore->store_name ?? $stockOut->toWarehouse->warehouse_name ?? 'N/A' }}
                        </span>
                    </div>
                </div>

                <p style="margin-top: 20px; font-size: 11px; color: #aaa;">
                    Transfer #{{ $stockOut->stock_out_code }} | Generated: {{ now()->format('d M Y, h:i A') }}
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

    <!-- Optional: JavaScript to generate QR code using a library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var qrData = "{{ $barcodeContent }}";
            var qrContainer = document.getElementById('qrCodeContainer');
            if (qrContainer) {
                new QRCode(qrContainer, {
                    text: qrData,
                    width: 150,
                    height: 150,
                    colorDark: "#1a1a2e",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
            }
        });
    </script>
</body>
</html>
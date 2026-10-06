<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>INVOICE #{{ $transaction->stock_out_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            padding: 40px; 
            background: #fff;
            color: #333;
        }
        .invoice-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            border: 2px solid #1a1a2e;
            border-radius: 12px;
            padding: 40px;
            position: relative;
        }
        .header { 
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #1a1a2e;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .header .left h1 { 
            font-size: 32px; 
            color: #1a1a2e;
            letter-spacing: 2px;
        }
        .header .left .sub-title { 
            font-size: 14px; 
            color: #666; 
            margin-top: 5px;
        }
        .header .right {
            text-align: right;
        }
        .header .right .invoice-number {
            font-size: 18px;
            font-weight: bold;
            background: #1a1a2e;
            color: white;
            padding: 8px 20px;
            border-radius: 4px;
            display: inline-block;
        }
        .header .right .date {
            margin-top: 8px;
            color: #666;
            font-size: 13px;
        }
        .company-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .company-info .left, .company-info .right { width: 48%; }
        .company-info .label { 
            font-weight: bold; 
            color: #555;
            display: inline-block;
            width: 100px;
        }
        .company-info p { margin: 4px 0; font-size: 13px; }
        .company-info .value { font-weight: 600; }

        .bill-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            padding: 15px;
            background: #fff3e0;
            border-radius: 8px;
            border-left: 4px solid #e65100;
        }
        .bill-info .left, .bill-info .right { width: 48%; }
        .bill-info .label { 
            font-weight: bold; 
            color: #555;
            display: inline-block;
            width: 100px;
        }
        .bill-info p { margin: 4px 0; font-size: 13px; }

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
        table th:last-child, table td:last-child {
            text-align: right;
        }
        table td { 
            padding: 10px 15px; 
            border-bottom: 1px solid #e0e0e0; 
        }
        table tr:last-child td { border-bottom: none; }
        
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
        .status-pending { background: #fff3cd; color: #856404; }
        .status-cancelled { background: #f8d7da; color: #721c24; }

        .payment-summary {
            margin-top: 20px;
            padding: 15px;
            background: #e8f5e9;
            border-radius: 8px;
            border-left: 4px solid #2e7d32;
        }
        .payment-summary table { width: 100%; margin: 0; border: none; }
        .payment-summary table td { border: none; padding: 5px 10px; }
        .payment-summary .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #1a1a2e;
        }

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
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
        }
        .footer .signature-area div { text-align: center; }
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
        .amount-words {
            margin-top: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
            font-size: 13px;
            color: #555;
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
            .invoice-wrapper { border: none; padding: 0; }
            .company-info { background: #f8f9fa !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .bill-info { background: #fff3e0 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            table th { background: #1a1a2e !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .status { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .badge { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .payment-summary { background: #e8f5e9 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    @php
        $items = $transaction->items ?? [];
        $totalQuantity = array_sum(array_column($items, 'quantity'));
        $totalAmount = $transaction->total_amount ?? 0;
        $logistics = $transaction->logistics ?? null;
    @endphp

    <div class="watermark">INVOICE</div>
    
    <div class="invoice-wrapper">
        <div class="content">
            <!-- Header -->
            <div class="header">
                <div class="left">
                    <h1>🧾 INVOICE</h1>
                    <div class="sub-title">Tax Invoice / Bill of Supply</div>
                </div>
                <div class="right">
                    <div class="invoice-number">{{ $transaction->stock_out_code }}</div>
                    <div class="date">Date: {{ $transaction->created_at->format('d M Y') }}</div>
                    <div style="margin-top: 8px;">
                        <span class="status status-{{ $transaction->status }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                        <span class="badge badge-{{ $transaction->type }}" style="background: {{ $transaction->type === 'sell' ? '#fce7f3' : '#e0e7ff' }}; color: {{ $transaction->type === 'sell' ? '#9d174d' : '#3730a3' }}; margin-left: 5px;">
                            {{ ucfirst($transaction->type) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Company & Customer Info -->
            <div class="company-info">
                <div class="left">
                    <p><strong style="font-size: 14px;">🏢 {{ auth()->user()->institute->name ?? 'Institute' }}</strong></p>
                    <p><span class="label">Address:</span> <span class="value">{{ auth()->user()->institute->address ?? 'N/A' }}</span></p>
                    <p><span class="label">Phone:</span> <span class="value">{{ auth()->user()->institute->phone ?? 'N/A' }}</span></p>
                    <p><span class="label">Email:</span> <span class="value">{{ auth()->user()->institute->email ?? 'N/A' }}</span></p>
                    @if(auth()->user()->institute->gst_number ?? false)
                        <p><span class="label">GSTIN:</span> <span class="value">{{ auth()->user()->institute->gst_number }}</span></p>
                    @endif
                </div>
                <div class="right">
                    <p><strong style="font-size: 14px;">👤 Customer</strong></p>
                    <p><span class="label">Name:</span> <span class="value">{{ $transaction->customer_name ?? 'Walk-in Customer' }}</span></p>
                    <p><span class="label">Phone:</span> <span class="value">{{ $transaction->customer_phone ?? 'N/A' }}</span></p>
                    <p><span class="label">Email:</span> <span class="value">{{ $transaction->customer_email ?? 'N/A' }}</span></p>
                    <p><span class="label">Address:</span> <span class="value">{{ $transaction->customer_address ?? 'N/A' }}</span></p>
                    @if($transaction->gst_number)
                        <p><span class="label">GSTIN:</span> <span class="value">{{ $transaction->gst_number }}</span></p>
                    @endif
                </div>
            </div>

            <!-- Items Table -->
            <table>
                <thead>
                    <tr>
                        <th style="width: 40%;">Item Description</th>
                        <th style="width: 20%;">HSN/SKU</th>
                        <th style="width: 15%;">Quantity</th>
                        <th style="width: 12%;">Price</th>
                        <th style="width: 13%;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                        <tr>
                            <td>
                                <strong>{{ $item['name'] ?? 'N/A' }}</strong>
                                @if(isset($item['description']))
                                    <br><small style="color: #888;">{{ $item['description'] }}</small>
                                @endif
                            </td>
                            <td><small style="color: #888;">{{ $item['code'] ?? 'N/A' }}</small></td>
                            <td>{{ $item['quantity'] ?? 0 }}</td>
                            <td>₹{{ number_format($item['price'] ?? 0, 2) }}</td>
                            <td>₹{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #888; padding: 20px;">
                                No items found in this invoice.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Payment Summary -->
            <div class="payment-summary">
                <table>
                    <tr style="background: transparent !important;">
                        <td style="width: 70%; text-align: right; font-weight: bold;">Subtotal:</td>
                        <td style="width: 30%; text-align: right;">₹{{ number_format($transaction->subtotal ?? 0, 2) }}</td>
                    </tr>
                    @if(($transaction->discount_amount ?? 0) > 0)
                        <tr style="background: transparent !important;">
                            <td style="text-align: right; font-weight: bold; color: #d32f2f;">Discount:</td>
                            <td style="text-align: right; color: #d32f2f;">- ₹{{ number_format($transaction->discount_amount, 2) }}</td>
                        </tr>
                    @endif
                    @if(($transaction->tax_amount ?? 0) > 0)
                        <tr style="background: transparent !important;">
                            <td style="text-align: right; font-weight: bold;">Tax:</td>
                            <td style="text-align: right;">+ ₹{{ number_format($transaction->tax_amount, 2) }}</td>
                        </tr>
                    @endif
                    @if(($transaction->service_charges ?? 0) > 0)
                        <tr style="background: transparent !important;">
                            <td style="text-align: right; font-weight: bold;">Service Charges:</td>
                            <td style="text-align: right;">+ ₹{{ number_format($transaction->service_charges, 2) }}</td>
                        </tr>
                    @endif
                    @if(($transaction->gst_charges ?? 0) > 0)
                        <tr style="background: transparent !important;">
                            <td style="text-align: right; font-weight: bold;">GST Charges:</td>
                            <td style="text-align: right;">+ ₹{{ number_format($transaction->gst_charges, 2) }}</td>
                        </tr>
                    @endif
                    <tr style="background: #c8e6c9 !important; font-weight: bold; font-size: 18px;">
                        <td style="text-align: right; padding: 10px 0;">Grand Total:</td>
                        <td style="text-align: right; padding: 10px 0; color: #1a1a2e;">₹{{ number_format($transaction->total_amount ?? 0, 2) }}</td>
                    </tr>
                </table>

                <div class="amount-words">
                    <strong>Amount in Words:</strong> 
                    {{-- {{ $this->numberToWords($transaction->total_amount ?? 0) }} --}}
                </div>
                
                @if($transaction->payment_method)
                    <div style="margin-top: 10px; padding: 8px 12px; background: #fff3cd; border-radius: 4px; font-size: 13px;">
                        <strong>Payment Method:</strong> {{ ucfirst($transaction->payment_method) }}
                        @if($transaction->payment_status)
                            | <strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $transaction->payment_status)) }}
                        @endif
                        @if($transaction->transaction_id)
                            | <strong>Transaction:</strong> {{ $transaction->transaction_id }}
                        @endif
                    </div>
                @endif
            </div>

            <!-- Logistics -->
            @if($logistics && is_array($logistics))
                <div style="margin-top: 15px; padding: 10px; background: #e3f2fd; border-radius: 8px; border-left: 4px solid #1565c0; font-size: 13px;">
                    <strong><i class="fas fa-truck"></i> Logistics Details:</strong>
                    @if(isset($logistics['transporter']))
                        <span style="margin-left: 10px;">Transporter: {{ $logistics['transporter'] }}</span>
                    @endif
                    @if(isset($logistics['tracking_number']))
                        <span style="margin-left: 10px;">Tracking: {{ $logistics['tracking_number'] }}</span>
                    @endif
                </div>
            @endif

            <!-- Footer -->
            <div class="footer">
                <p style="font-size: 12px; color: #888;">This is a system generated invoice. Valid without signature.</p>
                
                <div class="signature-area">
                    <div>
                        <div class="line"></div>
                        <span style="font-size: 12px;">Customer Signature</span>
                        <br>
                        <span style="font-size: 11px; color: #888;">{{ $transaction->customer_name ?? 'N/A' }}</span>
                    </div>
                </div>

                <p style="margin-top: 20px; font-size: 11px; color: #aaa;">
                    Invoice #{{ $transaction->stock_out_code }} | Generated: {{ now()->format('d M Y, h:i A') }}
                    @if($transaction->print_count > 0)
                        | Print Count: {{ $transaction->print_count }}
                    @endif
                </p>
            </div>

            <!-- Print Actions -->
            <div class="no-print" style="text-align: center; margin-top: 30px; padding: 20px; border-top: 2px solid #e0e0e0;">
                <button onclick="window.print()" style="padding: 12px 40px; font-size: 16px; cursor: pointer; background: #1a1a2e; color: white; border: none; border-radius: 8px; margin-right: 10px;">
                    🖨️ Print Invoice
                </button>
                <button onclick="window.close()" style="padding: 12px 40px; font-size: 16px; cursor: pointer; background: #6c757d; color: white; border: none; border-radius: 8px;">
                    ✖ Close
                </button>
            </div>
        </div>
    </div>

    <script>
        function numberToWords(num) {
            if (!num || num <= 0) return 'Zero Rupees Only';
            
            var ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 
                       'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 
                       'Seventeen', 'Eighteen', 'Nineteen'];
            var tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
            
            function convert(n) {
                if (n < 20) return ones[n];
                if (n < 100) return tens[Math.floor(n / 10)] + (n % 10 > 0 ? ' ' + ones[n % 10] : '');
                if (n < 1000) return ones[Math.floor(n / 100)] + ' Hundred' + (n % 100 > 0 ? ' ' + convert(n % 100) : '');
                if (n < 100000) return convert(Math.floor(n / 1000)) + ' Thousand' + (n % 1000 > 0 ? ' ' + convert(n % 1000) : '');
                if (n < 10000000) return convert(Math.floor(n / 100000)) + ' Lakh' + (n % 100000 > 0 ? ' ' + convert(n % 100000) : '');
                return convert(Math.floor(n / 10000000)) + ' Crore' + (n % 10000000 > 0 ? ' ' + convert(n % 10000000) : '');
            }
            
            return convert(Math.round(num)) + ' Rupees Only';
        }
    </script>
</body>
</html>
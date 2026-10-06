<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>INVOICE #{{ $stockOut->stock_out_code }}</title>
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
            /* background: #f8f9fa; */
            border-radius: 8px;
        }
        .company-info .left, .company-info .right { 
            width: 48%; 
        }
        .company-info .label { 
            font-weight: bold; 
            color: #555;
            display: inline-block;
            width: 100px;
        }
        .company-info p {
            margin: 4px 0;
            font-size: 13px;
        }
        .company-info .value {
            font-weight: 600;
        }
        .bill-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            padding: 15px;
            background: #fff3e0;
            border-radius: 8px;
            border-left: 4px solid #e65100;
        }
        .bill-info .left, .bill-info .right { 
            width: 48%; 
        }
        .bill-info .label { 
            font-weight: bold; 
            color: #555;
            display: inline-block;
            width: 100px;
        }
        .bill-info p {
            margin: 4px 0;
            font-size: 13px;
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
        table th:last-child, table td:last-child {
            text-align: right;
        }
        table td { 
            padding: 10px 15px; 
            border-bottom: 1px solid #e0e0e0; 
        }
        table tr:last-child td {
            border-bottom: none;
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
        .status-pending { background: #fff3cd; color: #856404; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .payment-summary {
            margin-top: 20px;
            padding: 15px;
            background: #e8f5e9;
            border-radius: 8px;
            border-left: 4px solid #2e7d32;
        }
        .payment-summary table {
            width: 100%;
            margin: 0;
            border: none;
        }
        .payment-summary table td {
            border: none;
            padding: 5px 10px;
        }
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
            justify-content: end;
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
        .badge-sell { background: #fce4ec; color: #721c24; }
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
        .amount-words {
            margin-top: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
            font-size: 13px;
            color: #555;
        }
        .amount-words strong {
            color: #1a1a2e;
        }
        .terms {
            margin-top: 15px;
            font-size: 12px;
            color: #888;
            padding: 10px;
            border: 1px dashed #ddd;
            border-radius: 4px;
        }
        .terms li {
            list-style: none;
            padding: 2px 0;
        }
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
        $items = is_array($stockOut->items) ? $stockOut->items : json_decode($stockOut->items, true);
        $totalQuantity = array_sum(array_column($items, 'quantity'));
        $totalAmount = $stockOut->total_amount ?? 0;
        
        /**
         * FIXED: Safe number to words conversion for Indian Rupees
         */
        function numberToWords($num) {
            // Return empty if no valid number
            if (!is_numeric($num) || $num <= 0) {
                return 'Zero Rupees Only';
            }
            
            $num = round($num, 2);
            $numParts = explode('.', number_format($num, 2, '.', ''));
            $whole = (int)$numParts[0];
            $decimal = isset($numParts[1]) ? (int)$numParts[1] : 0;
            
            $words = '';
            
            // Handle Crore
            if ($whole >= 10000000) {
                $crore = floor($whole / 10000000);
                $words .= numberToWordsPart($crore) . ' Crore ';
                $whole %= 10000000;
            }
            
            // Handle Lakh
            if ($whole >= 100000) {
                $lakh = floor($whole / 100000);
                $words .= numberToWordsPart($lakh) . ' Lakh ';
                $whole %= 100000;
            }
            
            // Handle Thousand
            if ($whole >= 1000) {
                $thousand = floor($whole / 1000);
                $words .= numberToWordsPart($thousand) . ' Thousand ';
                $whole %= 1000;
            }
            
            // Handle Hundred
            if ($whole >= 100) {
                $hundred = floor($whole / 100);
                $words .= numberToWordsPart($hundred) . ' Hundred ';
                $whole %= 100;
            }
            
            // Handle remaining (1-99)
            if ($whole > 0) {
                $words .= numberToWordsPart($whole) . ' ';
            }
            
            // Handle decimal (paise)
            if ($decimal > 0) {
                $words .= 'and ' . str_pad($decimal, 2, '0', STR_PAD_LEFT) . '/100 ';
            }
            
            return trim($words) . ' Rupees Only';
        }
        
        function numberToWordsPart($num) {
            $ones = array('', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 
                          'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 
                          'Seventeen', 'Eighteen', 'Nineteen');
            $tens = array('', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety');
            
            if ($num < 20) {
                return $ones[$num] ?? '';
            }
            
            if ($num < 100) {
                $t = floor($num / 10);
                $o = $num % 10;
                return ($tens[$t] ?? '') . ($o > 0 ? ' ' . ($ones[$o] ?? '') : '');
            }
            
            return (string)$num;
        }
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
                    <div class="invoice-number">{{ $stockOut->stock_out_code }}</div>
                    <div class="date">Date: {{ $stockOut->created_at->format('d M Y') }}</div>
                    <div style="margin-top: 8px;">
                        <span class="status status-{{ $stockOut->status }}">
                            {{ ucfirst($stockOut->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Company & Customer Info -->
            <div class="company-info">
                <div class="left">
                    @php
                        // Get source location details (warehouse or store)
                        $sourceLocation = null;
                        if ($stockOut->fromWarehouse) {
                            $sourceLocation = $stockOut->fromWarehouse;
                            $locationType = 'Warehouse';
                        } elseif ($stockOut->fromStore) {
                            $sourceLocation = $stockOut->fromStore;
                            $locationType = 'Store';
                        }
                    @endphp

                    @if($sourceLocation)
                        <p><strong style="font-size: 14px;">🏢 {{ $sourceLocation->warehouse_name ?? $sourceLocation->store_name ?? 'Location' }}</strong></p>
                        <p><span class="label">Location:</span> <span class="value">{{ $locationType }}</span></p>
                        <p><span class="label">Code:</span> <span class="value">{{ $sourceLocation->warehouse_code ?? $sourceLocation->store_code ?? 'N/A' }}</span></p>
                        <p><span class="label">Address:</span> <span class="value">{{ $sourceLocation->address ?? 'N/A' }}</span></p>
                        <p><span class="label">Phone:</span> <span class="value">{{ $sourceLocation->phone ?? 'N/A' }}</span></p>
                        <p><span class="label">Email:</span> <span class="value">{{ $sourceLocation->email ?? 'N/A' }}</span></p>
                        @if($sourceLocation->gst_number ?? false)
                        <p><span class="label">GSTIN:</span> <span class="value">{{ $sourceLocation->gst_number }}</span></p>
                        @endif
                    @else
                        <p><strong style="font-size: 14px;">🏢 {{ auth()->user()->institute->name ?? 'Institute' }}</strong></p>
                        <p><span class="label">Address:</span> <span class="value">{{ auth()->user()->institute->address ?? 'N/A' }}</span></p>
                        <p><span class="label">Phone:</span> <span class="value">{{ auth()->user()->institute->phone ?? 'N/A' }}</span></p>
                        <p><span class="label">Email:</span> <span class="value">{{ auth()->user()->institute->email ?? 'N/A' }}</span></p>
                        <p><span class="label">GSTIN:</span> <span class="value">{{ auth()->user()->institute->gst_number ?? 'N/A' }}</span></p>
                    @endif
                </div>
                <div class="right">
                    <p><strong style="font-size: 14px;">👤 Customer</strong></p>
                    <p><span class="label">Name:</span> <span class="value">{{ $stockOut->customer_name ?? 'Walk-in Customer' }}</span></p>
                    <p><span class="label">Phone:</span> <span class="value">{{ $stockOut->customer_phone ?? 'N/A' }}</span></p>
                    <p><span class="label">Email:</span> <span class="value">{{ $stockOut->customer_email ?? 'N/A' }}</span></p>
                    <p><span class="label">Address:</span> <span class="value">{{ $stockOut->customer_address ?? 'N/A' }}</span></p>
                    @if($stockOut->gst_number)
                    <p><span class="label">GSTIN:</span> <span class="value">{{ $stockOut->gst_number }}</span></p>
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
            @if($stockOut->type === 'sell')
            <div class="payment-summary">
                <table>
                    <tr style="background: transparent !important;">
                        <td style="width: 70%; text-align: right; font-weight: bold;">Subtotal:</td>
                        <td style="width: 30%; text-align: right;">₹{{ number_format($stockOut->subtotal ?? 0, 2) }}</td>
                    </tr>
                    @if(($stockOut->discount_amount ?? 0) > 0)
                    <tr style="background: transparent !important;">
                        <td style="text-align: right; font-weight: bold; color: #d32f2f;">Discount:</td>
                        <td style="text-align: right; color: #d32f2f;">- ₹{{ number_format($stockOut->discount_amount ?? 0, 2) }}</td>
                    </tr>
                    @endif
                    @if(($stockOut->tax_amount ?? 0) > 0)
                    <tr style="background: transparent !important;">
                        <td style="text-align: right; font-weight: bold;">Tax:</td>
                        <td style="text-align: right;">+ ₹{{ number_format($stockOut->tax_amount ?? 0, 2) }}</td>
                    </tr>
                    @endif
                    <tr style="background: #c8e6c9 !important; font-weight: bold; font-size: 18px;">
                        <td style="text-align: right; padding: 10px 0;">Grand Total:</td>
                        <td style="text-align: right; padding: 10px 0; color: #1a1a2e;">₹{{ number_format($stockOut->total_amount ?? 0, 2) }}</td>
                    </tr>
                </table>
                <div class="amount-words">
                    <strong>Amount in Words:</strong> {{ numberToWords($stockOut->total_amount ?? 0) }}
                </div>
                @if($stockOut->payment_method)
                <div style="margin-top: 10px; padding: 8px 12px; background: #fff3cd; border-radius: 4px; font-size: 13px;">
                    <strong>Payment Method:</strong> {{ ucfirst($stockOut->payment_method) }}
                    @if($stockOut->payment_status)
                    | <strong>Status:</strong> {{ ucfirst($stockOut->payment_status) }}
                    @endif
                    @if($stockOut->transaction_id)
                    | <strong>Transaction:</strong> {{ $stockOut->transaction_id }}
                    @endif
                </div>
                @endif
            </div>
            @endif

            <!-- Terms & Conditions -->
            <div class="terms">
                <strong style="font-size: 13px;">Terms & Conditions:</strong>
                <ul>
                    <li>1. Goods once sold will not be taken back.</li>
                    <li>2. This is a system generated invoice.</li>
                    <li>3. Please check the goods at the time of delivery.</li>
                    <li>4. Subject to jurisdiction, courts at {{ auth()->user()->institute->city ?? 'N/A' }}.</li>
                </ul>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p style="font-size: 12px; color: #888;">This is a system generated invoice. Valid without signature.</p>
                
                <div class="signature-area">
                    <div>
                        <div class="line"></div>
                        <span style="font-size: 12px;">Customer Signature</span>
                        <br>
                        <span style="font-size: 11px; color: #888;">{{ $stockOut->customer_name ?? 'N/A' }}</span>
                    </div>
                </div>

                <p style="margin-top: 20px; font-size: 11px; color: #aaa;">
                    Invoice #{{ $stockOut->stock_out_code }} | Generated: {{ now()->format('d M Y, h:i A') }}
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
</body>
</html>
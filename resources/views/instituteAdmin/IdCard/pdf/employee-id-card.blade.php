{{-- resources/views/instituteAdmin/IdCard/pdf/employee-id-card.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ID Card</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f0f2f5;
        }
        .id-card {
            width: 340px;
            margin: 10px auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .card-header {
            background: {{ $settings['header_bg_color'] ?? '#4361ee' }};
            color: {{ $settings['header_font_color'] ?? '#ffffff' }};
            padding: 15px 20px 10px;
            text-align: center;
        }
        .card-header img {
            max-height: 40px;
            max-width: 120px;
            margin-bottom: 5px;
        }
        .card-header .institute-name {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .card-header .card-title {
            font-size: 10px;
            font-weight: 600;
            opacity: 0.9;
            margin-top: 2px;
        }
        .card-body {
            padding: 15px 20px;
            background: white;
        }
        .employee-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid {{ $settings['header_bg_color'] ?? '#4361ee' }};
            margin: 0 auto 10px;
            display: block;
            object-fit: cover;
        }
        .employee-name {
            text-align: center;
            font-size: 14px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 2px;
        }
        .employee-code {
            text-align: center;
            font-size: 11px;
            color: #64748b;
            margin-bottom: 10px;
        }
        .fields-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px 10px;
            font-size: 9px;
            margin-top: 8px;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
        .field-item {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            border-bottom: 1px dashed #f1f5f9;
        }
        .field-item .label {
            color: #64748b;
            font-weight: 500;
        }
        .field-item .value {
            color: #1a1a2e;
            font-weight: 600;
        }
        .field-item.full-width {
            grid-column: 1 / -1;
        }
        .card-footer {
            background: {{ $settings['footer_bg_color'] ?? '#4361ee' }};
            color: {{ $settings['footer_font_color'] ?? '#ffffff' }};
            padding: 8px 15px;
            text-align: center;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-footer .signature {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .card-footer .signature img {
            max-height: 20px;
        }
        .card-footer .qr-code svg {
            width: 30px;
            height: 30px;
        }
        .card-footer .qr-code {
            display: flex;
            align-items: center;
        }
        .address-text {
            font-size: 7px;
            color: #94a3b8;
            text-align: center;
            margin-top: 5px;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }
        /* Fallback for missing images */
        .no-image {
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="id-card">
        <!-- Header -->
        <div class="card-header">
            @if(!empty($settings['header_banner_base64']))
                <img src="{{ $settings['header_banner_base64'] }}" alt="Header Banner">
            @elseif(!empty($employeeData['insitute_logo']))
                <img src="{{ $employeeData['insitute_logo'] }}" alt="Institute Logo">
            @endif
            <div class="institute-name">{{ $settings['institute_name'] ?? $employeeData['insitute_name'] ?? 'Institute' }}</div>
            <div class="card-title">{{ $settings['card_title'] ?? 'EMPLOYEE IDENTIFICATION CARD' }}</div>
        </div>

        <!-- Body -->
        <div class="card-body">
            @if(!empty($employeeData['photo']))
                <img src="{{ $employeeData['photo'] }}" alt="Employee Photo" class="employee-photo">
            @else
                <div class="employee-photo no-image" style="font-size: 30px;">👤</div>
            @endif

            <div class="employee-name">{{ $employeeData['full_name'] ?? 'Employee Name' }}</div>
            <div class="employee-code">Employee Code {{ $employeeData['employee_code'] ?? 'N/A' }}</div>

            <div class="fields-grid">
                @foreach($visibleFields as $field)
                    @if($field['name'] == 'full_name' || $field['name'] == 'employee_code')
                        @continue
                    @endif
                    <div class="field-item {{ in_array($field['name'], ['address', 'city', 'state', 'pincode', 'emergency_contact']) ? 'full-width' : '' }}">
                        <span class="label">{{ $field['label'] }}:</span>
                        <span class="value">{{ $field['value'] ?? 'N/A' }}</span>
                    </div>
                @endforeach
            </div>

            @if(!empty($settings['institute_address']))
                <div class="address-text">{{ $settings['institute_address'] }}</div>
            @endif
        </div>

        <!-- Footer -->
        <div class="card-footer">
            <div class="signature">
                @if(!empty($settings['signature_image_base64']))
                    <img src="{{ $settings['signature_image_base64'] }}" alt="Signature">
                @endif
                <span>{{ $settings['signature_text'] ?? 'Authorized Signature' }}</span>
            </div>
            <div class="qr-code">
                {!! $qrSvg !!}
            </div>
        </div>
    </div>
</body>
</html>
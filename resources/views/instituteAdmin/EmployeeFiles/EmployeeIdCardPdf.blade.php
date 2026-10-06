<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee ID Card - {{ $employeeData['employee_code'] }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: #fff;
        }

        .id-card {
            width: 340px;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin: 0 auto;
            border: 3px solid {{ $settings['header_bg_color'] ?? '#4361ee' }};
            position: relative;
            page-break-inside: avoid;
        }

        /* Header */
        .card-header-custom {
            background-color: {{ $settings['header_bg_color'] ?? '#4361ee' }};
            color: {{ $settings['header_font_color'] ?? '#ffffff' }};
            text-align: center;
            padding: 15px 12px;
            position: relative;
            overflow: hidden;
        }

        @if(isset($settings['header_banner_base64']) && $settings['header_banner_base64'])
        .card-header-custom {
            background-image: url('{{ $settings['header_banner_base64'] }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .header-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 1;
        }
        @endif

        .header-content {
            position: relative;
            z-index: 2;
        }

        .header-logo {
            margin-bottom: 5px;
        }

        .header-logo img {
            width: 55px;
            height: 55px;
            object-fit: contain;
            background: #fff;
            padding: 4px;
            border-radius: 6px;
        }

        .company-name {
            font-weight: 700;
            font-size: 16px;
            text-transform: uppercase;
        }

        .company-address {
            font-size: 12px;
            opacity: .9;
        }

        .header-divider {
            height: 1px;
            background: rgba(255, 255, 255, .4);
            margin: 8px 0;
        }

        .card-title {
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .5px;
        }

        .card-id {
            font-size: 12px;
            margin-top: 6px;
            background: rgba(255, 255, 255, .2);
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
        }

        /* Body */
        .card-body-custom {
            padding: 20px;
            text-align: center;
        }

        .card-photo {
            width: 110px;
            height: 110px;
            border: 2px solid #ddd;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 6px;
            background: #f8f9fa;
        }

        .card-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-fields {
            text-align: left;
        }

        /* FIXED: Added proper flex properties for field layout */
        .card-field {
            display: table;
            width: 100%;
            margin-bottom: 8px;
            font-size: 13px;
            border-bottom: 1px dashed #eee;
            padding-bottom: 4px;
        }

      .card-field span {
        display: table-cell;
        width: 40%;
        color: #555;
        font-weight: 500;
    }

.card-field strong {
    display: table-cell;
    width: 60%;
    text-align: right;
    font-weight: 600;
    color: #222;
    word-break: break-word;
}

        /* Footer */
        .card-footer-custom {
            background-color: {{ $settings['footer_bg_color'] ?? '#4361ee' }};
            color: {{ $settings['footer_font_color'] ?? '#ffffff' }};
            padding: 12px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            align-items: center;
        }

        .footer-signature {
            display: flex;
            flex-direction: column;
        }

        .signature-wrapper {
            height: 40px;
            margin-bottom: 4px;
        }

        .signature-img {
            max-height: 40px;
            max-width: 120px;
            object-fit: contain;
        }

        .qr-code {
            text-align: right;
        }

        .qr-code svg {
            width: 60px;
            height: 60px;
        }

        .qr-code svg path {
            fill: #ffffff;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .id-card {
                box-shadow: none;
                border: 2px solid #000;
            }
        }
    </style>
</head>
<body>
    <div class="id-card">
        <!-- Header -->
        <div class="card-header-custom">
            @if(isset($settings['header_banner_base64']) && $settings['header_banner_base64'])
            <div class="header-overlay"></div>
            @endif

            <div class="header-content">
                @if(!empty($employeeData['insitute_logo']))
                <div class="header-logo">
                    <img src="{{ $employeeData['insitute_logo'] }}" alt="Institute Logo">
                </div>
                @else
                <div class="company-name">
                    {{ $employeeData['insitute_name'] ?? '' }}
                </div>
                @endif
                
                <div class="company-address">
                    {{ $employeeData['insitute_address'] ?? '' }}
                </div>

                <div class="header-divider"></div>

                <div class="card-title">
                    {{ $settings['card_title'] ?? 'EMPLOYEE IDENTIFICATION CARD' }}
                </div>
                
                <div style="display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
                    <div class="card-id">
                        <span>ID: {{ $employeeData['employee_code'] ?? '' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body-custom">
            <!-- Photo -->
            <div class="card-photo">
                @if(!empty($employeeData['photo']))
                <img src="{{ $employeeData['photo'] }}" alt="Employee Photo">
                @else
                <div style="font-size: 40px; color: #999;">👤</div>
                @endif
            </div>

            <!-- Fields -->
            <div class="card-fields">
                @if($hasFieldSettings)
                    @foreach($visibleFields as $field)
                    <div class="card-field">
                        <span>{{ $field['label'] }}</span>
                        <strong>{{ $field['value'] }}</strong>
                    </div>
                    @endforeach
                @else
                    <div class="card-field">
                        <span>Name</span>
                        <strong>{{ $employeeData['full_name'] ?? 'N/A' }}</strong>
                    </div>

                    <div class="card-field">
                        <span>Employee ID</span>
                        <strong>{{ $employeeData['employee_code'] ?? 'N/A' }}</strong>
                    </div>

                    @if(!empty($employeeData['designation']))
                    <div class="card-field">
                        <span>Designation</span>
                        <strong>{{ $employeeData['designation'] }}</strong>
                    </div>
                    @endif

                    @if(!empty($employeeData['department']))
                    <div class="card-field">
                        <span>Department</span>
                        <strong>{{ $employeeData['department'] }}</strong>
                    </div>
                    @endif

                    @if(!empty($employeeData['dob']))
                    <div class="card-field">
                        <span>Date of Birth</span>
                        <strong>{{ $employeeData['dob'] }}</strong>
                    </div>
                    @endif

                    @if(!empty($employeeData['doj']))
                    <div class="card-field">
                        <span>Joining Date</span>
                        <strong>{{ $employeeData['doj'] }}</strong>
                    </div>
                    @endif

                    <div class="card-field">
                        <span>Phone</span>
                        <strong>{{ $employeeData['phone'] ?? 'N/A' }}</strong>
                    </div>

                    <div class="card-field">
                        <span>Email</span>
                        <strong>{{ $employeeData['email'] ?? 'N/A' }}</strong>
                    </div>

                    @if(!empty($employeeData['blood_group']))
                    <div class="card-field">
                        <span>Blood Group</span>
                        <strong>{{ $employeeData['blood_group'] }}</strong>
                    </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer-custom">
            <div class="footer-signature">
                @if(!empty($settings['signature_image_base64']))
                <div class="signature-wrapper">
                    <img src="{{ $settings['signature_image_base64'] }}" alt="Signature" class="signature-img">
                </div>
                @endif
                <div>
                    <span>{{ $settings['signature_text'] ?? "HR Manager's Signature" }}</span>
                </div>
            </div>
            <div class="qr-code">
                {!! $qrSvg !!}
            </div>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Student ID Card</title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'DejaVu Sans', sans-serif;
        }
        
        body {
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 10px;
        }

        .id-card {
            width: 380px;
            height: 100%;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            margin: auto;
            position: relative;
            border: 1px solid {{ $settings['header_bg_color'] ?? '#4361ee' }};
        }

        /* Header */
        .card-header-custom {
            color: #fff;
            text-align: center;
            padding: 15px 12px;
            position: relative;
            overflow: hidden;
            background-color: {{ $settings['header_bg_color'] ?? '#4361ee' }};
            color: {{ $settings['header_font_color'] ?? '#ffffff' }};
        }

        @if(isset($settings['header_banner_base64']) && $settings['header_banner_base64'])
        .card-header-custom {
            background-image: url('{{ $settings['header_banner_base64'] }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        @endif

        .header-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 1;
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        .header-logo img {
            width: 55px;
            height: 55px;
            object-fit: contain;
            background: #fff;
            padding: 4px;
            border-radius: 6px;
        }

        .school-name {
            font-weight: 700;
            font-size: 16px;
            text-transform: uppercase;
        }

        .school-address {
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
            margin-bottom: 8px;
        }

        .header-info-row {
            display: flex;
            justify-content: flex-start;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 6px;
        }

        .card-id {
            font-size: 12px;
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

        .card-photo i {
            font-size: 48px;
            color: #999;
        }

        .card-fields {
            text-align: left;
        }

        .card-field {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
            border-bottom: 1px dashed #eee;
            padding-bottom: 4px;
        }

        .card-field span {
            color: #555;
            font-weight: 500;
            flex: 0 0 40%;
        }

        .card-field strong {
            font-weight: 600;
            color: #222;
            text-align: right;
            flex: 0 0 58%;
            word-break: break-word;
        }

        /* Footer */
        .card-footer-custom {
            color: #fff;
            padding: 12px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            align-items: center;
            bottom: 0;
            width: 100%;
            background-color: {{ $settings['footer_bg_color'] ?? '#3a0ca3' }};
            color: {{ $settings['footer_font_color'] ?? '#ffffff' }};
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

        .signature-text {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .signature-text i {
            font-size: 14px;
        }

        .barcode {
            width: 100px;
            height: 30px;
            background: repeating-linear-gradient(90deg,
                    #fff,
                    #fff 2px,
                    transparent 2px,
                    transparent 4px);
            border-radius: 4px;
        }

        /* Font Awesome icons for PDF */
        .fas, .fa-user-graduate {
            display: inline-block;
            font-family: 'DejaVu Sans', sans-serif;
        }

        /* Print optimization */
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .id-card {
                box-shadow: none;
                border: 1px solid #000;
            }
        }

        /* Utility classes */
        .text-muted {
            color: #999;
        }

        .me-1, .me-2 {
            margin-right: 4px;
        }

        .me-2 {
            margin-right: 8px;
        }

        .fa-signature:before {
            content: "✍";
        }

        .fa-user-graduate:before {
            content: "👨‍🎓";
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
                @if(!empty($studentData['insitute_logo']))
                <div class="header-logo">
                    <img src="{{ $studentData['insitute_logo'] }}" alt="Institute Logo">
                </div>
                @else
                <div class="school-name">
                    {{ $studentData['insitute_name'] ?? '' }}
                </div>
                @endif

                <div class="school-address">
                    {{ $studentData['insitute_address'] ?? '' }}
                </div>

                <div class="header-divider"></div>

                <div class="card-title">
                    {{ $settings['card_title'] ?? 'STUDENT IDENTIFICATION CARD' }}
                </div>

                <div class="header-info-row">
                    <div class="card-id">
                        Reg No.: {{ $studentData['student_id'] ?? '' }}
                    </div>
                    <div class="card-id">
                        Session: {{ $studentData['academic_year'] ?? '' }}
                    </div>
                    <div class="card-id">
                        DOB: {{ isset($studentData['dob']) ? \Carbon\Carbon::parse($studentData['dob'])->format('d M Y') : 'N/A' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body-custom">

            <!-- Photo -->
            <div class="card-photo">
                @if(!empty($studentData['photo']))
                <img src="{{ $studentData['photo'] }}" alt="Student Photo">
                @else
                <i class="fas fa-user-graduate" style="font-size: 48px; color: #999;"></i>
                @endif
            </div>

            <!-- Fields - Show based on settings or defaults -->
            <div class="card-fields">
                @php
                $hasSettings = isset($hasFieldSettings) ? $hasFieldSettings : false;
                @endphp

                @if($hasSettings && !empty($visibleFields))
                    {{-- Show only enabled fields from settings --}}
                    @foreach($visibleFields as $field)
                        @if(!empty($field['value']) && $field['value'] !== 'N/A')
                        <div class="card-field">
                            <span>{{ $field['label'] }}</span>
                            <strong>{{ $field['value'] }}</strong>
                        </div>
                        @endif
                    @endforeach
                @else
                    {{-- Show default fields when no settings exist --}}
                    <!-- Student Name -->
                    <div class="card-field">
                        <span>Student Name</span>
                        <strong>{{ $studentData['full_name'] ?? 'N/A' }}</strong>
                    </div>

                    <!-- Father's Name -->
                    @if(!empty($studentData['father_name']))
                    <div class="card-field">
                        <span>Father's Name</span>
                        <strong>{{ $studentData['father_name'] }}</strong>
                    </div>
                    @endif

                    <!-- Mother's Name -->
                    @if(!empty($studentData['mother_name']))
                    <div class="card-field">
                        <span>Mother's Name</span>
                        <strong>{{ $studentData['mother_name'] }}</strong>
                    </div>
                    @endif

                    <!-- Class -->
                    <div class="card-field">
                        <span>Class</span>
                        <strong>{{ $studentData['class'] ?? 'N/A' }}</strong>
                    </div>

                    <!-- Section -->
                    <div class="card-field">
                        <span>Section</span>
                        <strong>{{ $studentData['section'] ?? 'N/A' }}</strong>
                    </div>

                    <!-- Blood Group (if available) -->
                    @if(!empty($studentData['blood_group']))
                    <div class="card-field">
                        <span>Blood Group</span>
                        <strong>{{ $studentData['blood_group'] }}</strong>
                    </div>
                    @endif

                    <!-- Contact No. -->
                    <div class="card-field">
                        <span>Contact No.</span>
                        <strong>{{ $studentData['phone'] ?? 'N/A' }}</strong>
                    </div>

                    <!-- Address (if available) -->
                    @if(!empty($studentData['address']))
                    <div class="card-field">
                        <span>Address</span>
                        <strong>{{ $studentData['address'] }}</strong>
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
                <div class="signature-text">
                    <i class="fas fa-signature"></i>
                    <span>{{ $settings['signature_text'] ?? "Principal's Signature" }}</span>
                </div>
            </div>
            <div class="barcode">
                <!-- QR Code can be placed here if you want it as barcode -->
                @if(isset($qrSvg))
                    <div style="width: 100px; height: 30px;">
                        {!! $qrSvg !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
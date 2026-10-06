@extends('emails.layouts.app')

@section('title', 'Visitor Registration Successful')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <!-- Header Section -->
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="color: #2c3e50; margin-bottom: 5px;">✅ Visit Registration Confirmed</h2>
        <p style="color: #7f8c8d; font-size: 14px;">Keep this code handy for your visit</p>
    </div>

    <!-- Visitor Code - MAIN FOCUS -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 30px; text-align: center; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
        <div style="margin-bottom: 15px;">
            <div style="background: rgba(255, 255, 255, 0.2); width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                <span style="font-size: 24px; color: white;">🔐</span>
            </div>
            <p style="color: rgba(255, 255, 255, 0.9); font-size: 14px; margin: 0 0 10px 0; letter-spacing: 1px;">
                YOUR VISITOR CODE
            </p>
        </div>
        
        <div style="background: white; padding: 20px; border-radius: 8px; display: inline-block; margin: 0 auto;">
            <div style="font-family: 'Courier New', monospace; font-size: 32px; font-weight: bold; color: #2c3e50; letter-spacing: 3px; padding: 10px 20px; background: #f8f9fa; border-radius: 6px; border: 2px dashed #e0e0e0;">
                {{ $visitor_code }}
            </div>
        </div>
        
        <p style="color: rgba(255, 255, 255, 0.9); font-size: 14px; margin: 20px 0 0 0; font-style: italic;">
            Present this code at the reception
        </p>
    </div>

    <!-- Important Notice -->
    <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 15px; margin-bottom: 25px;">
        <div style="display: flex; align-items: flex-start;">
            <div style="margin-right: 12px; color: #856404; font-size: 18px;">⚠️</div>
            <div>
                <strong style="color: #856404; display: block; margin-bottom: 5px;">Important Instructions</strong>
                <p style="color: #856404; margin: 0; font-size: 14px;">
                    1. Save this Visitor Code - you'll need it for check-in<br>
                    2. Carry a valid government-issued ID<br>
                    3. Arrive 10 minutes before your scheduled time
                </p>
            </div>
        </div>
    </div>

    <!-- Greeting -->
    <p style="color: #2c3e50; font-size: 16px; margin-bottom: 25px;">
        Hello <strong>{{ $visitor_name }}</strong>,
    </p>

    <!-- Main Message -->
    <p style="color: #2c3e50; line-height: 1.6; margin-bottom: 25px;">
        Your visit to <strong>{{ config('app.name') }}</strong> has been successfully registered. 
        Please keep your <strong>Visitor Code</strong> above readily available for a smooth check-in process.
    </p>

    <!-- Visit Details Table -->
    <div style="background-color: #ffffff; border: 1px solid #e0e0e0; border-radius: 8px; padding: 0; margin-bottom: 30px; overflow: hidden;">
        <div style="background-color: #f8f9fa; color: #2c3e50; padding: 15px; border-bottom: 1px solid #e0e0e0;">
            <h3 style="margin: 0; font-size: 16px;">📅 Visit Details</h3>
        </div>
        
        <table width="100%" cellpadding="15" cellspacing="0" style="border-collapse: collapse;">
            <tr style="border-bottom: 1px solid #f0f0f0;">
                <td width="35%" style="padding: 12px 15px; font-weight: bold; color: #2c3e50;">Visitor Name:</td>
                <td style="padding: 12px 15px; color: #34495e;">{{ $visitor_name }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f0f0f0;">
                <td style="padding: 12px 15px; font-weight: bold; color: #2c3e50;">Visitor Code:</td>
                <td style="padding: 12px 15px;">
                    <span style="font-family: 'Courier New', monospace; font-weight: bold; color: #e74c3c; background: #fff5f5; padding: 4px 10px; border-radius: 4px; border: 1px solid #ffebee;">
                        {{ $visitor_code }}
                    </span>
                </td>
            </tr>
            <tr style="border-bottom: 1px solid #f0f0f0;">
                <td style="padding: 12px 15px; font-weight: bold; color: #2c3e50;">Purpose of Visit:</td>
                <td style="padding: 12px 15px; color: #34495e;">{{ $purpose }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f0f0f0;">
                <td style="padding: 12px 15px; font-weight: bold; color: #2c3e50;">Visit Date:</td>
                <td style="padding: 12px 15px; color: #34495e;">
                    <span style="display: inline-flex; align-items: center; gap: 8px;">
                        📅 {{ $visit_date }}
                    </span>
                </td>
            </tr>
            <tr>
                <td style="padding: 12px 15px; font-weight: bold; color: #2c3e50;">Visit Time:</td>
                <td style="padding: 12px 15px; color: #34495e;">
                    <span style="display: inline-flex; align-items: center; gap: 8px;">
                        ⏰ {{ $visit_time }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <!-- QR Code Section (Optional - if you generate QR codes) -->
    @if(isset($qr_code_url))
    <div style="text-align: center; margin-bottom: 30px; padding: 20px; border: 2px dashed #3498db; border-radius: 8px; background-color: #f8fbfe;">
        <p style="color: #2c3e50; font-weight: bold; margin-bottom: 15px; font-size: 14px;">
            📱 Quick Check-in with QR Code
        </p>
        <img src="{{ $qr_code_url }}" alt="Visitor QR Code" style="max-width: 180px; height: auto; margin-bottom: 10px;">
        <p style="color: #7f8c8d; font-size: 12px; margin: 10px 0 0 0;">
            Scan this QR code at the reception for instant check-in
        </p>
    </div>
    @endif

    <!-- Additional Instructions -->
    <div style="background-color: #e8f4fd; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 30px; border-radius: 0 8px 8px 0;">
        <p style="color: #2c3e50; margin: 0 0 10px 0; font-weight: bold;">
            📋 Check-in Process:
        </p>
        <ol style="color: #2c3e50; margin: 0; padding-left: 20px; font-size: 14px;">
            <li>Go to the reception desk upon arrival</li>
            <li>Provide your <strong>Visitor Code: {{ $visitor_code }}</strong></li>
            <li>Show your government-issued ID for verification</li>
            <li>Receive your visitor badge</li>
        </ol>
    </div>

    <!-- Footer -->
    <div style="text-align: center; padding-top: 20px; border-top: 1px solid #e0e0e0; color: #7f8c8d; font-size: 13px;">
        <p style="margin: 0 0 20px 0;">
            Need to make changes? Contact us at 
            <a href="mailto:support@{{ config('app.domain') }}" style="color: #3498db; text-decoration: none;">
                support@campusdunia.co.in
            </a>
        </p>
        
        <p style="margin: 0;">
            Thank you,<br>
            <strong style="color: #2c3e50;">{{ config('app.name') }} Security Team</strong>
        </p>
    </div>
</div>
@endsection
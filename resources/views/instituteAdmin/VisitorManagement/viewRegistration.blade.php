@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <title>Visitor Details - {{ $visitor->name }}</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #4bb543;
            --warning-color: #ff9e00;
            --danger-color: #e63946;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        .main-container {
            margin: 0 auto;
        }
        
        /* Header Section */
        .header-section {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            border-radius: 15px;
            padding: 25px 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .header-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
        }
        
        .system-logo {
            font-size: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .system-logo i {
            margin-right: 15px;
            font-size: 28px;
        }
        
        /* Card Styles */
        .card {
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: none;
            margin-bottom: 25px;
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            padding: 20px 25px;
            border-bottom: none;
        }
        
        .card-header h4 {
            margin: 0;
            font-weight: 600;
        }
        
        /* Visitor Code Container */
        .visitor-code-container {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin: 25px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .visitor-code-container::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
        }
        
        .visitor-code-display {
            font-size: 42px;
            font-weight: 700;
            letter-spacing: 2px;
            margin: 20px 0;
            background: rgba(255, 255, 255, 0.15);
            padding: 20px 40px;
            border-radius: 12px;
            display: inline-block;
            position: relative;
            z-index: 2;
            font-family: 'Courier New', monospace;
            border: 2px dashed rgba(255, 255, 255, 0.3);
        }
        
        /* Photo Container */
        .photo-container {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .visitor-photo {
            width: 220px;
            height: 220px;
            border-radius: 50%;
            object-fit: cover;
            border: 8px solid white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        
        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
        }
        
        .status-registered {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        
        .status-checked-in {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }
        
        .status-checked-out {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }
        
        .status-expired {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
        }
        
        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            border-left: 5px solid #4361ee;
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 0 12px 12px 0;
        }
        
        .info-box i {
            color: #4361ee;
            font-size: 24px;
            margin-right: 15px;
        }
        
        /* Detail Cards */
        .detail-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .detail-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .detail-label {
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .detail-value {
            color: #111827;
            font-size: 18px;
            font-weight: 500;
        }
        
        /* Vehicle Photos Grid */
        .vehicle-photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }
        
        .vehicle-photo {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            transition: transform 0.3s;
        }
        
        .vehicle-photo:hover {
            transform: scale(1.05);
        }
        
        /* Timeline */
        .timeline-container {
            position: relative;
            padding-left: 40px;
            margin-top: 20px;
        }
        
        .timeline-container::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(to bottom, #4361ee, #3a0ca3);
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 25px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -44px;
            top: 25px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #4361ee;
            border: 4px solid white;
            box-shadow: 0 0 0 4px #4361ee;
        }
        
        .timeline-time {
            font-size: 14px;
            color: #6b7280;
            display: flex;
            align-items: center;
        }
        
        .timeline-time i {
            margin-right: 8px;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-warning:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-danger:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
        }
        
        .btn-info {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-info:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(6, 182, 212, 0.3);
        }
        
        /* QR Code */
        .qr-code-container {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .qr-code {
            width: 180px;
            height: 180px;
            margin: 0 auto 15px;
            padding: 10px;
            background: white;
            border-radius: 10px;
            border: 2px dashed #e5e7eb;
        }
        
        /* Vehicle Details Section */
        .vehicle-details-section {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 12px;
            padding: 25px;
            margin-top: 20px;
            border-left: 5px solid #3b82f6;
        }
        
        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }
            
            .card {
                box-shadow: none !important;
                border: 1px solid #dee2e6 !important;
            }
            
            .visitor-code-display {
                background: #f8f9fa !important;
                color: #000 !important;
                border: 2px solid #000 !important;
            }
        }
        
        @media (max-width: 768px) {
            .visitor-photo {
                width: 180px;
                height: 180px;
            }
            
            .visitor-code-display {
                font-size: 32px;
                padding: 15px 25px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                width: 100%;
            }
            
            .vehicle-photo-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }


        /* Timeline Status Colors */
        .timeline-item.registration { border-left-color: #4361ee; }
        .timeline-item.registration::before { background: #4361ee; }
        .timeline-item.otp_verification { border-left-color: #10b981; }
        .timeline-item.otp_verification::before { background: #10b981; }
        .timeline-item.check_in { border-left-color: #3b82f6; }
        .timeline-item.check_in::before { background: #3b82f6; }
        .timeline-item.assigned { border-left-color: #f59e0b; }
        .timeline-item.assigned::before { background: #f59e0b; }
        .timeline-item.attended { border-left-color: #8b5cf6; }
        .timeline-item.attended::before { background: #8b5cf6; }
        .timeline-item.pass_generated { border-left-color: #06b6d4; }
        .timeline-item.pass_generated::before { background: #06b6d4; }
        .timeline-item.check_out { border-left-color: #f97316; }
        .timeline-item.check_out::before { background: #f97316; }
        
        /* Status color variables */
        :root {
            --primary-color: #4361ee;
            --secondary-color: #6c757d;
            --success-color: #10b981;
            --info-color: #3b82f6;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
    </style>

    <div class="container-fluid main-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <div class="system-logo">
                        <i class="fas fa-id-card"></i>
                        <div>
                            Visitor Details
                            <div class="fs-6 fw-normal">{{ $visitor->visitor_code }}</div>
                        </div>
                    </div>
                    <p class="mb-0 mt-2 opacity-75">Complete visitor information and tracking</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('visitors.index') }}" class="btn btn-light me-2">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                    <button onclick="window.print()" class="btn btn-light">
                        <i class="fas fa-print me-2"></i>Print
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Photo and Basic Info -->
            <div class="col-lg-4">
                <!-- Photo Card -->
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-camera me-2"></i>Visitor Photo</h4>
                    </div>
                    <div class="card-body text-center">
                        <div class="photo-container">
                            @if($visitor->visitor_photo)
                                <img src="{{ Storage::disk('public')->exists($visitor->visitor_photo) ? asset('image/' . $visitor->visitor_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($visitor->name) . '&background=4361ee&color=fff&size=220' }}" 
                                     alt="Visitor Photo" 
                                     class="visitor-photo"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($visitor->name) }}&background=4361ee&color=fff&size=220'">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($visitor->name) }}&background=4361ee&color=fff&size=220" 
                                     alt="Visitor Photo" 
                                     class="visitor-photo">
                            @endif
                        </div>
                        
                        <div class="mt-4">
                            <span class="status-badge status-{{ strtolower($visitor->status) }}">
                                {{ $visitor->status }}
                            </span>
                        </div>
                        
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                Registered on {{ \Carbon\Carbon::parse($visitor->created_at)->format('d M Y, h:i A') }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Quick Info Card -->
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-info-circle me-2"></i>Quick Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="detail-card">
                            <div class="detail-label">Registration Type</div>
                            <div class="detail-value">
                                <i class="fas fa-{{ $visitor->registration_type == 'online' ? 'globe' : 'user-circle' }} me-2"></i>
                                {{ ucfirst($visitor->registration_type) }}
                            </div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-label">OTP Verification</div>
                            <div class="detail-value">
                                @if($visitor->otp_verified)
                                    <span class="text-success">
                                        <i class="fas fa-check-circle me-2"></i>Verified
                                    </span>
                                    @if($visitor->otp_expires_at)
                                        <br>
                                        <small class="text-muted">
                                            Expired: {{ \Carbon\Carbon::parse($visitor->otp_expires_at)->format('d M, h:i A') }}
                                        </small>
                                    @endif
                                @else
                                    <span class="text-danger">
                                        <i class="fas fa-times-circle me-2"></i>Not Verified
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-label">Validity</div>
                            <div class="detail-value">
                                @php
                                    $registrationTime = \Carbon\Carbon::parse($visitor->registration_time);
                                    $expiryTime = $registrationTime->copy()->addHours(24);
                                    $isExpired = $expiryTime->isPast();
                                @endphp
                                @if($isExpired)
                                    <span class="text-danger">
                                        <i class="fas fa-exclamation-triangle me-2"></i>Expired
                                    </span>
                                    <br>
                                    <small class="text-muted">
                                        Expired on {{ $expiryTime->format('d M Y, h:i A') }}
                                    </small>
                                @else
                                    <span class="text-success">
                                        <i class="fas fa-check-circle me-2"></i>Valid
                                    </span>
                                    <br>
                                    <small class="text-muted">
                                        Expires on {{ $expiryTime->format('d M Y, h:i A') }}
                                        ({{ $expiryTime->diffForHumans() }})
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Code Card -->
                <div class="card no-print d-none">
                    <div class="card-header">
                        <h4><i class="fas fa-qrcode me-2"></i>Quick Scan Code</h4>
                    </div>
                    <div class="card-body">
                        <div class="qr-code-container">
                            <div class="qr-code" id="qrcode"></div>
                            <h6 class="mb-2">Scan for Quick Access</h6>
                            <small class="text-muted">Use QR scanner to view visitor details</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Detailed Information -->
            <div class="col-lg-8">
                <!-- Visitor Code Display -->
                <div class="visitor-code-container">
                    <h3><i class="fas fa-id-card me-2"></i>Visitor Code</h3>
                    <div class="visitor-code-display">{{ $visitor->visitor_code }}</div>
                    <div class="mt-3">
                        <small class="opacity-75">
                            <i class="fas fa-clock me-1"></i>
                            Valid for 24 hours from registration
                        </small>
                    </div>
                </div>

                <!-- Personal Details Card -->
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-user-circle me-2"></i>Personal Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <div class="detail-label">Full Name</div>
                                    <div class="detail-value">{{ $visitor->name }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <div class="detail-label">Contact Number</div>
                                    <div class="detail-value">
                                        <i class="fas fa-phone me-2"></i>
                                        {{ $visitor->contact_number }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <div class="detail-label">Email Address</div>
                                    <div class="detail-value">
                                        <i class="fas fa-envelope me-2"></i>
                                        {{ $visitor->email }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <div class="detail-label">Purpose of Visit</div>
                                    <div class="detail-value">
                                        <i class="fas fa-bullseye me-2"></i>
                                        {{ $visitor->purpose }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Notes -->
                        @if($visitor->additional_notes)
                        <div class="info-box mt-3">
                            <i class="fas fa-sticky-note"></i>
                            <div>
                                <strong>Additional Notes:</strong>
                                <p class="mb-0 mt-2">{{ $visitor->additional_notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Vehicle Details (if available) -->
                @if($visitor->vehicle_type || $visitor->vehicle_number || $visitor->vehicle_color || $visitor->vehicle_photos)
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-car me-2"></i>Vehicle Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="vehicle-details-section">
                            @if($visitor->vehicle_type)
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="detail-card">
                                        <div class="detail-label">Vehicle Type</div>
                                        <div class="detail-value">{{ $visitor->vehicle_type }}</div>
                                    </div>
                                </div>
                                
                                @if($visitor->vehicle_number)
                                <div class="col-md-4">
                                    <div class="detail-card">
                                        <div class="detail-label">Vehicle Number</div>
                                        <div class="detail-value">{{ $visitor->vehicle_number }}</div>
                                    </div>
                                </div>
                                @endif
                                
                                @if($visitor->vehicle_color)
                                <div class="col-md-4">
                                    <div class="detail-card">
                                        <div class="detail-label">Vehicle Color</div>
                                        <div class="detail-value">{{ $visitor->vehicle_color }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif
                            
                            <!-- Vehicle Photos -->
                            @if($visitor->vehicle_photos && is_array(json_decode($visitor->vehicle_photos, true)))
                            <div class="mt-4">
                                <h6><i class="fas fa-images me-2"></i>Vehicle Photos</h6>
                                <div class="vehicle-photo-grid">
                                    @foreach(json_decode($visitor->vehicle_photos, true) as $index => $photo)
                                        @if(Storage::disk('public')->exists($photo))
                                        <div>
                                            <img src="{{ asset('image/' . $photo) }}" 
                                                 alt="Vehicle Photo {{ $index + 1 }}" 
                                                 class="vehicle-photo"
                                                 data-bs-toggle="modal" 
                                                 data-bs-target="#imageModal"
                                                 data-image="{{ asset('image/' . $photo) }}"
                                                 data-title="Vehicle Photo {{ $index + 1 }}">
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Timeline Card -->
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-history me-2"></i>Visitor Journey Timeline</h4>
                    </div>
                    <div class="card-body">
                        <div class="timeline-container">
                            @php
                                // Define all possible journey steps in order
                                $journeySteps = [
                                    'registered' => [
                                        'title' => 'Registration',
                                        'description' => 'Visitor registration created',
                                        'icon' => 'user-plus',
                                        'color' => 'primary',
                                        'completed' => true, // Always completed
                                        'time' => $visitor->created_at
                                    ],
                                    'check-in' => [
                                        'title' => 'Check In',
                                        'description' => 'Visitor checked in at reception',
                                        'icon' => 'sign-in-alt',
                                        'color' => 'info',
                                        'completed' => false,
                                        'time' => null
                                    ],
                                    'assign-to-employee' => [
                                        'title' => 'Assigned to Employee',
                                        'description' => 'Visitor assigned for meeting',
                                        'icon' => 'user-tie',
                                        'color' => 'warning',
                                        'completed' => false,
                                        'time' => null
                                    ],
                                    'attended-by' => [
                                        'title' => 'Meeting Attended',
                                        'description' => 'Visitor meeting completed',
                                        'icon' => 'handshake',
                                        'color' => 'success',
                                        'completed' => false,
                                        'time' => null
                                    ],
                                    'generate-pass' => [
                                        'title' => 'Pass Generated',
                                        'description' => 'Out pass generated for visitor',
                                        'icon' => 'file-contract',
                                        'color' => 'info',
                                        'completed' => false,
                                        'time' => null
                                    ],
                                    'check-out' => [
                                        'title' => 'Check Out',
                                        'description' => 'Visitor checked out',
                                        'icon' => 'sign-out-alt',
                                        'color' => 'warning',
                                        'completed' => false,
                                        'time' => null
                                    ]
                                ];
                                
                                // Map current status to the journey
                                $currentStatus = $visitor->status;
                                $statusFound = false;
                                
                                // Mark completed steps based on current status
                                foreach ($journeySteps as $step => $stepData) {
                                    if ($step === $currentStatus) {
                                        $statusFound = true;
                                        $journeySteps[$step]['completed'] = true;
                                        $journeySteps[$step]['current'] = true;
                                        break;
                                    }
                                    $journeySteps[$step]['completed'] = true;
                                }
                                
                                // If current status not in journey steps (like 'Checked Out'), mark all as completed
                                if (!$statusFound && $currentStatus === 'Checked Out') {
                                    foreach ($journeySteps as $step => $stepData) {
                                        $journeySteps[$step]['completed'] = true;
                                    }
                                    $journeySteps['check-out']['current'] = true;
                                }
                                
                                // Fetch actual times from database
                                
                                // 1. Check-in time
                                $checkin = \App\Models\VisitorCheckin::where('visitor_code', $visitor->visitor_code)->first();
                                if ($checkin) {
                                    $journeySteps['check-in']['time'] = $checkin->check_in_time;
                                    $journeySteps['check-in']['description'] = 'Visitor checked in at ' . 
                                        ($checkin->gate_id ? 'Gate ' . $checkin->gate_id : 'reception');
                                }
                                
                                // 2. Assigned time (from meeting)
                                if (in_array($currentStatus, ['assign-to-employee', 'attended-by', 'generate-pass', 'check-out', 'Checked Out'])) {
                                    $meeting = \App\Models\VisitorMeeting::where('visitor_code', $visitor->visitor_code)
                                        ->orderBy('created_at', 'desc')
                                        ->first();
                                    if ($meeting) {
                                        $journeySteps['assign-to-employee']['time'] = $meeting->created_at;
                                        $journeySteps['assign-to-employee']['description'] = 'Assigned to ' . 
                                            ($meeting->employee_name ?? 'employee') . ' for meeting';
                                    }
                                }
                                
                                // 3. Attended time (from front desk logs)
                                if (in_array($currentStatus, ['attended-by', 'generate-pass', 'check-out', 'Checked Out'])) {
                                    $attendentLog = \App\Models\VisitorFrontdeskLogs::where('visitor_code', $visitor->visitor_code)
                                        ->where('visitors_log_status', 'completed')
                                        ->first();
                                    if ($attendentLog) {
                                        $journeySteps['attended-by']['time'] = $attendentLog->updated_at;
                                        $journeySteps['attended-by']['description'] = 'Meeting completed with ' . 
                                            ($attendentLog->meeting_attendent_type == 'self' ? 'self attendance' : 'employee');
                                    }
                                }
                                
                                // 4. Pass generated time
                                if (in_array($currentStatus, ['generate-pass', 'check-out', 'Checked Out']) || $visitor->out_pass_id) {
                                    $outPass = \App\Models\VisitorOutPass::where('visitor_code', $visitor->visitor_code)
                                        ->orderBy('created_at', 'desc')
                                        ->first();
                                    if ($outPass) {
                                        $journeySteps['generate-pass']['time'] = $outPass->created_at;
                                        $journeySteps['generate-pass']['description'] = 'Out pass generated: ' . $outPass->out_pass_id;
                                    } elseif ($visitor->status == 'generate-pass') {
                                        $journeySteps['generate-pass']['time'] = $visitor->updated_at;
                                    }
                                }
                                
                                // 5. Check-out time
                                if (in_array($currentStatus, ['check-out', 'Checked Out'])) {
                                    $checkout = \App\Models\Visitorcheckout::where('visitor_code', $visitor->visitor_code)->first();
                                    if ($checkout) {
                                        $journeySteps['check-out']['time'] = $checkout->check_out_time;
                                        $journeySteps['check-out']['description'] = 'Visitor checked out from premises';
                                    } elseif ($currentStatus == 'check-out') {
                                        $journeySteps['check-out']['time'] = $visitor->updated_at;
                                    }
                                }
                            @endphp
                            
                            @foreach($journeySteps as $step => $stepData)
                                <div class="timeline-item {{ isset($stepData['current']) ? 'current-step' : '' }}" 
                                    style="{{ !$stepData['completed'] ? 'opacity: 0.6;' : '' }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="d-flex align-items-center mb-2">
                                                <!-- Step indicator -->
                                                <div class="step-indicator me-3">
                                                    @if($stepData['completed'])
                                                        @if(isset($stepData['current']))
                                                            <div class="step-circle current">
                                                                <i class="fas fa-{{ $stepData['icon'] }}"></i>
                                                            </div>
                                                        @else
                                                            <div class="step-circle completed">
                                                                <i class="fas fa-check"></i>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="step-circle pending">
                                                            <span>{{ array_search($step, array_keys($journeySteps)) + 1 }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <!-- Step title and status -->
                                                <div>
                                                    <h6 class="mb-0 {{ isset($stepData['current']) ? 'text-' . $stepData['color'] : '' }}">
                                                        {{ $stepData['title'] }}
                                                        @if(isset($stepData['current']))
                                                            <span class="badge bg-{{ $stepData['color'] }} ms-2">Current</span>
                                                        @endif
                                                    </h6>
                                                    @if($stepData['completed'] && $stepData['time'])
                                                        <small class="text-muted">
                                                            Completed: {{ \Carbon\Carbon::parse($stepData['time'])->format('d M, h:i A') }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Step description -->
                                            <p class="mb-1 ms-5">{{ $stepData['description'] }}</p>
                                            
                                            <!-- Additional info for specific steps -->
                                            @if($step == 'generate-pass' && $visitor->out_pass_id)
                                                @php
                                                    $outPass = \App\Models\VisitorOutPass::where('out_pass_id', $visitor->out_pass_id)->first();
                                                @endphp
                                                @if($outPass)
                                                    <div class="small text-muted ms-5">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Valid: {{ \Carbon\Carbon::parse($outPass->valid_from)->format('h:i A') }} - 
                                                        {{ \Carbon\Carbon::parse($outPass->valid_to)->format('h:i A') }}
                                                    </div>
                                                @endif
                                            @endif
                                            
                                            @if($step == 'check-in' && $checkin && $checkin->check_in_method)
                                                <div class="small text-muted ms-5">
                                                    <i class="fas fa-{{ $checkin->check_in_method == 'qr_code' ? 'qrcode' : 'mobile-alt' }} me-1"></i>
                                                    Method: {{ ucfirst(str_replace('_', ' ', $checkin->check_in_method)) }}
                                                </div>
                                            @endif
                                            
                                            @if($step == 'assign-to-employee' && isset($meeting) && $meeting)
                                                <div class="small text-muted ms-5">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    Scheduled: {{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y') }}
                                                    @if($meeting->meeting_time)
                                                        at {{ $meeting->meeting_time }}
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Time display -->
                                        <div class="timeline-time">
                                            @if($stepData['completed'] && $stepData['time'])
                                                <i class="fas fa-{{ $stepData['icon'] }} me-2"></i>
                                                {{ \Carbon\Carbon::parse($stepData['time'])->format('d M Y, h:i A') }}
                                            @elseif($stepData['completed'])
                                                <span class="text-success">
                                                    <i class="fas fa-check me-2"></i>
                                                    Completed
                                                </span>
                                            @else
                                                <span class="text-muted">
                                                    <i class="fas fa-clock me-2"></i>
                                                    Pending
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Journey Progress Bar -->
                        <div class="mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small text-muted">Journey Progress</span>
                                <span class="small text-muted">
                                    @php
                                        $completedCount = count(array_filter($journeySteps, function($step) {
                                            return $step['completed'];
                                        }));
                                        $totalCount = count($journeySteps);
                                        $percentage = ($completedCount / $totalCount) * 100;
                                    @endphp
                                    {{ round($percentage) }}% Complete
                                </span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-primary" 
                                    role="progressbar" 
                                    style="width: {{ $percentage }}%;"
                                    aria-valuenow="{{ $percentage }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                @foreach($journeySteps as $step => $stepData)
                                    <div class="text-center" style="width: {{ 100 / count($journeySteps) }}%">
                                        <small class="{{ $stepData['completed'] ? 'text-primary fw-bold' : 'text-muted' }}">
                                            {{ ucfirst(str_replace('-', ' ', $step)) }}
                                        </small>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                /* Step Indicator Styles */
                .step-indicator {
                    position: relative;
                }

                .step-circle {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: 600;
                    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                }

                .step-circle.completed {
                    background: linear-gradient(135deg, #10b981, #059669);
                    color: white;
                    border: 3px solid white;
                }

                .step-circle.current {
                    background: linear-gradient(135deg, #4361ee, #3a0ca3);
                    color: white;
                    border: 3px solid white;
                    animation: pulse 2s infinite;
                }

                .step-circle.pending {
                    background: #f1f5f9;
                    color: #64748b;
                    border: 3px solid white;
                }

                /* Timeline item current step styling */
                .timeline-item.current-step {
                    background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(67, 97, 238, 0.02));
                    border-left: 4px solid #4361ee !important;
                    border-radius: 0 10px 10px 0;
                }

                .timeline-item.current-step::before {
                    background: #4361ee;
                    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.2);
                }

                /* Progress bar animation */
                .progress-bar {
                    transition: width 0.6s ease;
                }

                /* Pulse animation for current step */
                @keyframes pulse {
                    0% {
                        box-shadow: 0 0 0 0 rgba(67, 97, 238, 0.4);
                    }
                    70% {
                        box-shadow: 0 0 0 10px rgba(67, 97, 238, 0);
                    }
                    100% {
                        box-shadow: 0 0 0 0 rgba(67, 97, 238, 0);
                    }
                }

                /* Responsive adjustments */
                @media (max-width: 768px) {
                    .step-circle {
                        width: 32px;
                        height: 32px;
                        font-size: 14px;
                    }
                    
                    .ms-5 {
                        margin-left: 2.5rem !important;
                    }
                }
                </style>

                <!-- Action Buttons -->
                <!-- <div class="card no-print">
                    <div class="card-body">
                        <div class="action-buttons">
                            <a href="{{ route('visitors.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                            
                            <a href="{{ route('visitors.edit', $visitor->id) }}" class="btn btn-info">
                                <i class="fas fa-edit me-2"></i>Edit Visitor
                            </a>
                            
                            @if($visitor->status == 'Registered')
                            <form action="{{ route('visitors.check-in', $visitor->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-sign-in-alt me-2"></i>Check In
                                </button>
                            </form>
                            @elseif($visitor->status == 'Checked In')
                            <form action="{{ route('visitors.check-out', $visitor->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-sign-out-alt me-2"></i>Check Out
                                </button>
                            </form>
                            @endif
                            
                            <form action="{{ route('visitors.destroy', $visitor->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this visitor?')">
                                    <i class="fas fa-trash me-2"></i>Delete
                                </button>
                            </form>
                            
                            <button onclick="downloadVisitorPass()" class="btn btn-primary">
                                <i class="fas fa-file-pdf me-2"></i>Download Pass
                            </button>
                            
                            <button onclick="window.print()" class="btn btn-outline-primary">
                                <i class="fas fa-print me-2"></i>Print Details
                            </button>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>

        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Vehicle Photo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="modalImage" src="" alt="" class="img-fluid" style="max-height: 70vh;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Generator -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Generate QR Code
            const qrElement = document.getElementById('qrcode');
            if (qrElement) {
                const visitorData = JSON.stringify({
                    code: "{{ $visitor->visitor_code }}",
                    name: "{{ $visitor->name }}",
                    contact: "{{ $visitor->contact_number }}",
                    purpose: "{{ $visitor->purpose }}",
                    url: "{{ url()->current() }}"
                });
                
                QRCode.toCanvas(qrElement, visitorData, {
                    width: 160,
                    margin: 1,
                    color: {
                        dark: '#4361ee',
                        light: '#ffffff'
                    }
                }, function (error) {
                    if (error) console.error(error);
                });
            }
            
            // Image Modal
            const imageModal = document.getElementById('imageModal');
            if (imageModal) {
                imageModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const imageSrc = button.getAttribute('data-image');
                    const imageTitle = button.getAttribute('data-title');
                    
                    const modalTitle = imageModal.querySelector('.modal-title');
                    const modalImage = imageModal.querySelector('#modalImage');
                    
                    modalTitle.textContent = imageTitle;
                    modalImage.src = imageSrc;
                    modalImage.alt = imageTitle;
                });
            }
            
            // Download Visitor Pass
            window.downloadVisitorPass = function() {
                alert('PDF generation would be implemented here.\nVisitor Code: {{ $visitor->visitor_code }}\nName: {{ $visitor->name }}');
                // Implementation for PDF download would go here
            };
            
            // Copy Visitor Code to Clipboard
            const visitorCode = "{{ $visitor->visitor_code }}";
            const codeDisplay = document.querySelector('.visitor-code-display');
            if (codeDisplay) {
                codeDisplay.style.cursor = 'pointer';
                codeDisplay.title = 'Click to copy visitor code';
                codeDisplay.addEventListener('click', function() {
                    navigator.clipboard.writeText(visitorCode).then(function() {
                        const originalText = codeDisplay.textContent;
                        codeDisplay.textContent = 'Copied!';
                        codeDisplay.style.background = 'rgba(16, 185, 129, 0.2)';
                        
                        setTimeout(function() {
                            codeDisplay.textContent = originalText;
                            codeDisplay.style.background = 'rgba(255, 255, 255, 0.15)';
                        }, 2000);
                    }).catch(function(err) {
                        console.error('Failed to copy: ', err);
                    });
                });
            }
        });
    </script>
@endsection
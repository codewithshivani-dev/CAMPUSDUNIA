@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <title>Employee ID Card Templates</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-1: #4cc9f0;
            --accent-2: #7209b7;
            --accent-3: #2a9d8f;
            --accent-4: #e63946;
            --accent-5: #ff9e00;
            --accent-6: #8338ec;
            --dark-color: #1a1a2e;
            --light-color: #f8f9fa;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
        }
        
        .header {
            background: linear-gradient(135deg, var(--dark-color), var(--secondary-color));
            color: white;
            padding: 2.5rem 0;
            border-radius: 0 0 25px 25px;
            margin-bottom: 3rem;
            box-shadow: 0 10px 30px rgba(26, 26, 46, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(76, 201, 240, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(114, 9, 183, 0.1) 0%, transparent 50%),
                linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.05) 30%, rgba(255, 255, 255, 0.05) 70%, transparent 70%);
        }
        
        .header-content {
            position: relative;
            z-index: 2;
        }
        
        .company-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            border: 3px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        
        .template-options {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            border: none;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .template-options:hover {
            transform: translateY(-5px);
        }
        
        .template-options::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(to right, var(--primary-color), var(--accent-1), var(--accent-3));
            border-radius: 20px 20px 0 0;
        }
        
        .template-preview-section {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: none;
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        
        .template-preview-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(to right, var(--accent-3), var(--secondary-color));
            border-radius: 20px 20px 0 0;
        }
        
        .template-card {
            border: 2px solid var(--gray-200);
            border-radius: 16px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            background: white;
            position: relative;
            overflow: hidden;
        }
        
        .template-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .template-card.active {
            border-color: var(--primary-color);
            box-shadow: 0 20px 40px rgba(67, 97, 238, 0.25);
            animation: pulse-border 2s infinite;
        }
        
        @keyframes pulse-border {
            0% { border-color: var(--primary-color); }
            50% { border-color: var(--accent-1); }
            100% { border-color: var(--primary-color); }
        }
        
        .template-card.active::after {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-1), var(--accent-3), var(--primary-color));
            z-index: -1;
            animation: rotate-gradient 3s linear infinite;
            background-size: 300% 300%;
        }
        
        @keyframes rotate-gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .template-thumbnail {
            height: 160px;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            font-weight: 700;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .template-thumbnail::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.3), transparent);
        }
        
        .template-1 { 
            background: linear-gradient(135deg, var(--dark-color), var(--primary-color), #3a86ff); 
            color: white; 
        }
        
        .template-2 { 
            background: linear-gradient(135deg, #003049, #d62828, #f77f00); 
            color: white; 
        }
        
        .template-3 { 
            background: linear-gradient(135deg, #2a9d8f, #264653, #e9c46a); 
            color: white; 
        }
        
        .template-4 { 
            background: linear-gradient(135deg, #7209b7, #3a0ca3, #4361ee); 
            color: white; 
        }
        
        .template-5 { 
            background: linear-gradient(135deg, #e63946, #ff006e, #8338ec); 
            color: white; 
        }
        
        .template-6 { 
            background: linear-gradient(135deg, #ff9e00, #ff5400, #ff006e); 
            color: white; 
        }
        
        .id-card-container {
            perspective: 1500px;
            margin-bottom: 2.5rem;
        }
        
        .id-card {
            width: 100%;
            max-width: 500px;
            height: 750px !important;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.9s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin: 0 auto;
            cursor: pointer;
        }
        
        .id-card.flipped {
            transform: rotateY(180deg);
        }
        
        .id-card-front, .id-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            border: 3px solid rgba(255, 255, 255, 0.4);
        }
        
        .id-card-back {
            transform: rotateY(180deg);
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
        }
        
        .card-header {
            padding: 1rem 1.5rem;
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.15);
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        .card-body {
            padding: 1.5rem;
            height: 400px !important;
        }
        
        .employee-photo-container {
            position: relative;
            width: 130px;
            height: 150px;
            margin: 0 auto 1.5rem;
        }
        
        .employee-photo {
            width: 100%;
            height: 100%;
            border-radius: 12px;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 2;
        }
        
        .photo-frame {
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border: 3px solid rgba(255, 255, 255, 0.6);
            border-radius: 18px;
            z-index: 1;
        }
        
        .employee-badge {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(5px);
            border-radius: 10px;
            padding: 0.6rem 1.2rem;
            font-size: 0.9rem;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 0.75rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }
        
        .field-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0.25rem;
            display: block;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .field-value {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: white;
            display: block;
            padding-bottom: 0.5rem;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.3);
        }
        
        .back-field-label {
            font-size: 0.8rem;
            color: var(--gray-600);
            margin-bottom: 0.25rem;
            display: block;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .back-field-value {
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 0.75rem;
            color: var(--gray-800);
            display: block;
            padding: 0.5rem;
            background: rgba(0, 0, 0, 0.03);
            border-radius: 8px;
            border-left: 4px solid var(--accent-3);
        }
        
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 2.5rem;
            flex-wrap: wrap;
        }
        
        .btn-custom {
            padding: 0.85rem 2.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 200px;
            border: none;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
        }
        
        .btn-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: 0.5s;
        }
        
        .btn-custom:hover::before {
            left: 100%;
        }
        
        .btn-download {
            background: linear-gradient(135deg, var(--dark-color), var(--secondary-color));
            color: white;
            box-shadow: 0 10px 25px rgba(26, 26, 46, 0.3);
        }
        
        .btn-download:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(26, 26, 46, 0.4);
            color: white;
        }
        
        .btn-flip {
            background: linear-gradient(135deg, #ffffff, var(--gray-200));
            color: var(--gray-800);
            border: 2px solid var(--gray-300);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .btn-flip:hover {
            background: linear-gradient(135deg, var(--gray-100), var(--gray-300));
            color: var(--gray-900);
            border-color: var(--gray-400);
            transform: translateY(-5px);
        }
        
        .instructions {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border-radius: 20px;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .instructions::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: linear-gradient(to bottom, var(--accent-1), var(--accent-3));
        }
        
        .instructions ul {
            padding-left: 1.5rem;
            margin-bottom: 0;
        }
        
        .instructions li {
            margin-bottom: 0.75rem;
            color: var(--gray-700);
            font-size: 0.95rem;
            position: relative;
        }
        
        .instructions li::before {
            content: '▶';
            position: absolute;
            left: -1.5rem;
            color: var(--primary-color);
            font-weight: bold;
        }
        
        .employee-info-panel {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: none;
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .employee-info-panel::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle at top right, rgba(76, 201, 240, 0.1), transparent);
        }
        
        .footer {
            text-align: center;
            padding: 2.5rem 0;
            margin-top: 4rem;
            color: var(--gray-600);
            border-top: 1px solid var(--gray-300);
            font-size: 0.95rem;
            background: white;
            border-radius: 20px 20px 0 0;
            box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.05);
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        
        .info-item {
            padding: 0.75rem 0;
            border-bottom: 2px dashed var(--gray-300);
            position: relative;
        }
        
        .info-item::before {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(to right, var(--primary-color), var(--accent-1));
            transition: width 0.3s ease;
        }
        
        .info-item:hover::before {
            width: 100%;
        }
        
        .flip-indicator {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.2);
            color: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .flip-indicator:hover {
            background: rgba(0, 0, 0, 0.3);
            transform: rotate(180deg);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .back-flip-indicator {
            position: absolute;
            bottom: 20px;
            left: 20px;
            background: rgba(0, 0, 0, 0.1);
            color: var(--gray-700);
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            border: 2px solid rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .back-flip-indicator:hover {
            background: rgba(0, 0, 0, 0.2);
            transform: rotate(180deg);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .qr-code-container {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #333, #555);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.8rem;
            margin: 1rem auto;
            border: 3px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .company-logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .logo-circle {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
            margin: 0 auto 0.5rem;
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
            border: 3px solid rgba(255, 255, 255, 0.3);
        }
        
        .template-name {
            position: absolute;
            bottom: 15px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 1rem;
            font-weight: 800;
            color: white;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            z-index: 2;
            letter-spacing: 1px;
        }
        
        .employee-status {
            display: inline-block;
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-active {
            background: linear-gradient(to right, #2ecc71, #27ae60);
            color: white;
        }
        
        .status-inactive {
            background: linear-gradient(to right, #e74c3c, #c0392b);
            color: white;
        }
        
        .status-on-leave {
            background: linear-gradient(to right, #f39c12, #e67e22);
            color: white;
        }
        
        .department-badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-top: 0.25rem;
        }
        
        @media (max-width: 1200px) {
            .id-card {
                max-width: 450px;
                height: 300px;
            }
        }
        
        @media (max-width: 992px) {
            .id-card {
                max-width: 400px;
                height: 280px;
            }
            
            .btn-custom {
                min-width: 180px;
                padding: 0.75rem 2rem;
            }
            
            .employee-photo-container {
                width: 110px;
                height: 130px;
            }
        }
        
        @media (max-width: 768px) {
            .id-card {
                max-width: 350px;
                height: 260px;
            }
            
            .employee-photo-container {
                width: 100px;
                height: 120px;
            }
            
            .field-label, .back-field-label {
                font-size: 0.8rem;
            }
            
            .field-value, .back-field-value {
                font-size: 0.9rem;
            }
            
            .template-thumbnail {
                height: 140px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 576px) {
            .id-card {
                max-width: 320px;
                height: 240px;
            }
            
            .btn-custom {
                min-width: 100%;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .header {
                padding: 2rem 0;
            }
        }
        
        .floating-element {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .template-tag {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            border-radius: 4px;
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 700;
            color: white;
            z-index: 3;
        }
        
        .security-strip {
            height: 40px;
            background: linear-gradient(90deg, rgba(0,0,0,0.8), rgba(255,255,255,0.2), rgba(0,0,0,0.8));
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            opacity: 0.1;
        }
        
        .magnetic-strip {
            height: 30px;
            background: linear-gradient(90deg, #000, #333, #666, #333, #000);
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            opacity: 0.8;
        }
        
        .signature-area {
            width: 150px;
            height: 50px;
            border-bottom: 2px solid rgba(0, 0, 0, 0.3);
            margin: 0 auto;
            position: relative;
        }
        
        .signature-text {
            position: absolute;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 0.7rem;
            color: var(--gray-600);
            font-style: italic;
        }
        
        .expiry-date {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--accent-4);
            background: rgba(230, 57, 70, 0.1);
            padding: 0.3rem 0.8rem;
            border-radius: 6px;
            display: inline-block;
        }
    </style>

    <div class="header floating-element">
        <div class="container">
            <div class="header-content">
                <div class="company-badge">
                    <i class="fas fa-building fa-3x"></i>
                </div>
                <h1 class="text-center mb-2"><i class="fas fa-id-badge me-3"></i>Employee ID Card Designer</h1>
                <p class="text-center mb-0 fs-5">Professional ID templates for your workforce</p>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="template-options">
                    <h3 class="mb-4"><i class="fas fa-layer-group me-2" style="color: var(--primary-color);"></i>Select Template Design</h3>
                    <div class="row g-3">
                        <div class="col-sm-6 col-lg-12">
                            <div class="template-card active" data-template="1">
                                <div class="template-thumbnail template-1">
                                    Corporate Executive
                                    <div class="template-tag">PRO</div>
                                    <div class="template-name">EXECUTIVE PRO</div>
                                </div>
                                <h5 class="mb-1">Executive Pro</h5>
                                <p class="small text-muted mb-0">Professional design for executives</p>
                                <div class="mt-2 d-flex align-items-center">
                                    <div class="badge bg-dark me-2">Premium</div>
                                    <span class="text-primary small fw-bold"><i class="fas fa-check-circle me-1"></i> Selected</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-12">
                            <div class="template-card" data-template="2">
                                <div class="template-thumbnail template-2">
                                    Modern Tech
                                    <div class="template-tag">TECH</div>
                                    <div class="template-name">TECH CORP</div>
                                </div>
                                <h5 class="mb-1">Tech Corporation</h5>
                                <p class="small text-muted mb-0">Modern design for tech companies</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-12">
                            <div class="template-card" data-template="3">
                                <div class="template-thumbnail template-3">
                                    Business Green
                                    <div class="template-tag">ECO</div>
                                    <div class="template-name">GREEN BUSINESS</div>
                                </div>
                                <h5 class="mb-1">Green Business</h5>
                                <p class="small text-muted mb-0">Eco-friendly corporate design</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-12">
                            <div class="template-card" data-template="4">
                                <div class="template-thumbnail template-4">
                                    Royal Purple
                                    <div class="template-tag">VIP</div>
                                    <div class="template-name">ROYAL VIP</div>
                                </div>
                                <h5 class="mb-1">Royal VIP</h5>
                                <p class="small text-muted mb-0">Premium design for VIP employees</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-12">
                            <div class="template-card" data-template="5">
                                <div class="template-thumbnail template-5">
                                    Creative Agency
                                    <div class="template-tag">CREATIVE</div>
                                    <div class="template-name">CREATIVE AGENCY</div>
                                </div>
                                <h5 class="mb-1">Creative Agency</h5>
                                <p class="small text-muted mb-0">Vibrant design for creative teams</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-12">
                            <div class="template-card" data-template="6">
                                <div class="template-thumbnail template-6">
                                    Industrial Orange
                                    <div class="template-tag">INDUSTRY</div>
                                    <div class="template-name">INDUSTRIAL PRO</div>
                                </div>
                                <h5 class="mb-1">Industrial Pro</h5>
                                <p class="small text-muted mb-0">Robust design for industrial sectors</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="instructions">
                    <h5><i class="fas fa-question-circle me-2" style="color: var(--primary-color);"></i>How to Use</h5>
                    <ul class="mt-3">
                        <li>Select a template from the options</li>
                        <li>Preview the ID card with employee data</li>
                        <li>Flip card to view back side details</li>
                        <li>Download high-quality PDF for printing</li>
                        <li>All templates are print-ready</li>
                        <li>Customize data as per your requirements</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="template-preview-section">
                    <h3 class="mb-4 text-center"><i class="fas fa-eye me-2" style="color: var(--accent-3);"></i>Live ID Card Preview</h3>
                    
                    <div class="id-card-container">
                        <div class="id-card floating-element" id="idCard">
                            <!-- Front side of ID card -->
                            <div class="id-card-front" id="idCardFront">
                                <!-- Content will be generated by JavaScript -->
                            </div>
                            
                            <!-- Back side of ID card -->
                            <div class="id-card-back" id="idCardBack">
                                <!-- Content will be generated by JavaScript -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4 d-none">
                        <p class="text-muted fw-medium"><i class="fas fa-hand-pointer me-2"></i> Click on the ID card to flip sides</p>
                    </div>
                    
                    <div class="action-buttons d-none">
                        <button class="btn btn-custom btn-flip" id="flipBtn">
                            <i class="fas fa-exchange-alt me-2"></i> Flip Card
                        </button>
                        <button class="btn btn-custom btn-download" id="downloadBtn">
                            <i class="fas fa-print me-2"></i> Download & Print
                        </button>
                    </div>
                </div>
                
                <div class="employee-info-panel d-none">
                    <h5 class="mb-4"><i class="fas fa-user-tie me-2" style="color: var(--primary-color);"></i>Employee Information</h5>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Full Name:</span>
                            <span class="field-value text-dark fw-bold">Michael Rodriguez</span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Employee ID:</span>
                            <span class="field-value text-dark fw-bold">EMP-2023-0452</span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Designation:</span>
                            <span class="field-value text-dark">Senior Software Engineer</span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Department:</span>
                            <span class="field-value text-dark">Technology & Development</span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Joining Date:</span>
                            <span class="field-value text-dark">March 15, 2020</span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Status:</span>
                            <span class="field-value text-success fw-bold">Active <i class="fas fa-user-check ms-1"></i></span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Employee Type:</span>
                            <span class="field-value text-dark">Full-time Permanent</span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Date of Birth:</span>
                            <span class="field-value text-dark">July 22, 1988</span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Blood Group:</span>
                            <span class="field-value text-danger fw-bold">B+</span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Email:</span>
                            <span class="field-value text-dark">m.rodriguez@company.com</span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Phone:</span>
                            <span class="field-value text-dark">+1 (555) 789-0123</span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Emergency Contact:</span>
                            <span class="field-value text-dark">Maria Rodriguez - +1 (555) 456-7890</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Employee data
            const employeeData = {
                name: "Michael Rodriguez",
                id: "EMP-2023-0452",
                designation: "Senior Software Engineer",
                department: "Technology & Development",
                joiningDate: "March 15, 2020",
                status: "Active",
                employeeType: "Full-time Permanent",
                dob: "July 22, 1988",
                bloodGroup: "B+",
                email: "m.rodriguez@company.com",
                phone: "+1 (555) 789-0123",
                emergencyContact: "Maria Rodriguez - +1 (555) 456-7890",
                address: "123 Corporate Tower, Suite 1200\nBusiness District, BD 54321\nUnited States",
                supervisor: "Sarah Johnson - Director of Engineering",
                workLocation: "Headquarters - Floor 12",
                employeeLevel: "L3 - Senior Professional",
                accessLevel: "Level 4 - Full Access",
                expiryDate: "December 31, 2025",
                companyName: "TECHNOVA CORPORATION",
                companyAddress: "456 Innovation Drive, Tech Park, CA 90210"
            };
            
            // Template configurations for employee ID cards
            const templates = {
                1: {
                    name: "Executive Pro",
                    frontBg: "linear-gradient(135deg, var(--dark-color), var(--primary-color), #3a86ff)",
                    backBg: "linear-gradient(145deg, #ffffff, #f0f7ff)",
                    textColor: "white",
                    backTextColor: "var(--gray-800)",
                    accentColor: "var(--accent-1)",
                    headerColor: "rgba(255, 255, 255, 0.2)",
                    secondaryColor: "#3a86ff",
                    pattern: "url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100\" height=\"100\" viewBox=\"0 0 100 100\"><rect x=\"0\" y=\"0\" width=\"100\" height=\"100\" fill=\"none\" stroke=\"white\" stroke-width=\"2\" stroke-dasharray=\"10,5\" opacity=\"0.1\"/></svg>')"
                },
                2: {
                    name: "Tech Corporation",
                    frontBg: "linear-gradient(135deg, #003049, #d62828, #f77f00)",
                    backBg: "linear-gradient(145deg, #ffffff, #fff5f5)",
                    textColor: "white",
                    backTextColor: "var(--gray-800)",
                    accentColor: "#f77f00",
                    headerColor: "rgba(255, 255, 255, 0.2)",
                    secondaryColor: "#d62828",
                    pattern: "url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100\" height=\"100\" viewBox=\"0 0 100 100\"><circle cx=\"20\" cy=\"20\" r=\"2\" fill=\"white\" opacity=\"0.2\"/><circle cx=\"50\" cy=\"50\" r=\"2\" fill=\"white\" opacity=\"0.2\"/><circle cx=\"80\" cy=\"80\" r=\"2\" fill=\"white\" opacity=\"0.2\"/></svg>')"
                },
                3: {
                    name: "Green Business",
                    frontBg: "linear-gradient(135deg, #2a9d8f, #264653, #e9c46a)",
                    backBg: "linear-gradient(145deg, #ffffff, #f0fff9)",
                    textColor: "white",
                    backTextColor: "var(--gray-800)",
                    accentColor: "#2a9d8f",
                    headerColor: "rgba(255, 255, 255, 0.2)",
                    secondaryColor: "#e9c46a",
                    pattern: "url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100\" height=\"100\" viewBox=\"0 0 100 100\"><path d=\"M20,80 Q50,20 80,80\" fill=\"none\" stroke=\"white\" stroke-width=\"2\" opacity=\"0.1\"/></svg>')"
                },
                4: {
                    name: "Royal VIP",
                    frontBg: "linear-gradient(135deg, #7209b7, #3a0ca3, #4361ee)",
                    backBg: "linear-gradient(145deg, #ffffff, #f9f0ff)",
                    textColor: "white",
                    backTextColor: "var(--gray-800)",
                    accentColor: "#7209b7",
                    headerColor: "rgba(255, 255, 255, 0.2)",
                    secondaryColor: "#4361ee",
                    pattern: "url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100\" height=\"100\" viewBox=\"0 0 100 100\"><path d=\"M50,10 L60,40 L90,40 L65,60 L75,90 L50,70 L25,90 L35,60 L10,40 L40,40 Z\" fill=\"none\" stroke=\"white\" stroke-width=\"2\" opacity=\"0.1\"/></svg>')"
                },
                5: {
                    name: "Creative Agency",
                    frontBg: "linear-gradient(135deg, #e63946, #ff006e, #8338ec)",
                    backBg: "linear-gradient(145deg, #ffffff, #fff0f5)",
                    textColor: "white",
                    backTextColor: "var(--gray-800)",
                    accentColor: "#ff006e",
                    headerColor: "rgba(255, 255, 255, 0.2)",
                    secondaryColor: "#8338ec",
                    pattern: "url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100\" height=\"100\" viewBox=\"0 0 100 100\"><circle cx=\"50\" cy=\"50\" r=\"40\" fill=\"none\" stroke=\"white\" stroke-width=\"1\" stroke-dasharray=\"1,4\" opacity=\"0.1\"/></svg>')"
                },
                6: {
                    name: "Industrial Pro",
                    frontBg: "linear-gradient(135deg, #ff9e00, #ff5400, #ff006e)",
                    backBg: "linear-gradient(145deg, #ffffff, #fff5e6)",
                    textColor: "white",
                    backTextColor: "var(--gray-800)",
                    accentColor: "#ff5400",
                    headerColor: "rgba(255, 255, 255, 0.2)",
                    secondaryColor: "#ff9e00",
                    pattern: "url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100\" height=\"100\" viewBox=\"0 0 100 100\"><rect x=\"30\" y=\"30\" width=\"40\" height=\"40\" fill=\"none\" stroke=\"white\" stroke-width=\"2\" opacity=\"0.1\"/></svg>')"
                }
            };
            
            let currentTemplate = 1;
            
            // Template selection
            const templateCards = document.querySelectorAll('.template-card');
            templateCards.forEach(card => {
                card.addEventListener('click', function() {
                    // Remove active class from all cards
                    templateCards.forEach(c => {
                        c.classList.remove('active');
                        const checkSpan = c.querySelector('.text-primary');
                        if (checkSpan) {
                            checkSpan.innerHTML = '';
                        }
                    });
                    
                    // Add active class to clicked card
                    this.classList.add('active');
                    
                    // Add checkmark to selected card
                    const selectedText = this.querySelector('.text-primary');
                    if (selectedText) {
                        selectedText.innerHTML = '<i class="fas fa-check-circle me-1"></i> Selected';
                    }
                    
                    // Update current template
                    currentTemplate = parseInt(this.getAttribute('data-template'));
                    
                    // Update ID card preview
                    updateIdCard();
                });
            });
            
            // Flip ID card
            const idCard = document.getElementById('idCard');
            const flipBtn = document.getElementById('flipBtn');
            
            idCard.addEventListener('click', function() {
                this.classList.toggle('flipped');
            });
            
            flipBtn.addEventListener('click', function() {
                idCard.classList.toggle('flipped');
                // Add animation effect
                flipBtn.innerHTML = '<i class="fas fa-sync-alt me-2 fa-spin"></i> Flipping...';
                setTimeout(() => {
                    flipBtn.innerHTML = '<i class="fas fa-exchange-alt me-2"></i> Flip Card';
                }, 300);
            });
            
            // Download ID card as PDF
            const downloadBtn = document.getElementById('downloadBtn');
            downloadBtn.addEventListener('click', function() {
                // Create a container for the PDF
                const pdfContainer = document.createElement('div');
                pdfContainer.style.width = '500px';
                pdfContainer.style.padding = '30px';
                pdfContainer.style.backgroundColor = 'white';
                pdfContainer.style.fontFamily = "'Open Sans', sans-serif";
                pdfContainer.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.1)';
                pdfContainer.style.borderRadius = '10px';
                pdfContainer.style.margin = '0 auto';
                
                // Clone the front and back of the ID card
                const frontClone = document.getElementById('idCardFront').cloneNode(true);
                const backClone = document.getElementById('idCardBack').cloneNode(true);
                
                // Remove flip indicators for PDF
                const flipIndicators = frontClone.querySelectorAll('.flip-indicator, .back-flip-indicator');
                flipIndicators.forEach(indicator => indicator.remove());
                
                // Reset transformations for PDF
                frontClone.style.transform = 'none';
                frontClone.style.position = 'relative';
                frontClone.style.backfaceVisibility = 'visible';
                frontClone.style.marginBottom = '40px';
                frontClone.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.15)';
                frontClone.style.borderRadius = '20px';
                
                backClone.style.transform = 'none';
                backClone.style.position = 'relative';
                backClone.style.backfaceVisibility = 'visible';
                backClone.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.15)';
                backClone.style.borderRadius = '20px';
                
                // Append to container
                pdfContainer.appendChild(frontClone);
                pdfContainer.appendChild(backClone);
                
                // Add a footer note
                const footer = document.createElement('div');
                footer.style.textAlign = 'center';
                footer.style.marginTop = '25px';
                footer.style.paddingTop = '20px';
                footer.style.borderTop = '2px dashed var(--gray-300)';
                
                const footerText = document.createElement('p');
                footerText.textContent = `Official ${employeeData.companyName} Employee ID Card`;
                footerText.style.fontSize = '12px';
                footerText.style.color = 'var(--gray-600)';
                footerText.style.fontWeight = '700';
                footerText.style.marginBottom = '10px';
                
                const instructions = document.createElement('p');
                instructions.textContent = 'For security purposes, this card must be worn visibly at all times within company premises';
                instructions.style.fontSize = '11px';
                instructions.style.color = 'var(--gray-500)';
                instructions.style.fontStyle = 'italic';
                
                footer.appendChild(footerText);
                footer.appendChild(instructions);
                pdfContainer.appendChild(footer);
                
                // Add corporate watermark
                const watermark = document.createElement('div');
                watermark.style.position = 'absolute';
                watermark.style.top = '50%';
                watermark.style.left = '50%';
                watermark.style.transform = 'translate(-50%, -50%) rotate(-45deg)';
                watermark.style.opacity = '0.03';
                watermark.style.fontSize = '70px';
                watermark.style.fontWeight = 'bold';
                watermark.style.color = 'var(--dark-color)';
                watermark.style.whiteSpace = 'nowrap';
                watermark.textContent = employeeData.companyName.toUpperCase();
                watermark.style.zIndex = '-1';
                pdfContainer.style.position = 'relative';
                pdfContainer.appendChild(watermark);
                
                // PDF options
                const options = {
                    margin: 0.5,
                    filename: `Employee_ID_${employeeData.name.replace(/\s+/g, '_')}_${templates[currentTemplate].name.replace(/\s+/g, '_')}.pdf`,
                    image: { type: 'jpeg', quality: 1.0 },
                    html2canvas: { 
                        scale: 3,
                        useCORS: true,
                        logging: false,
                        backgroundColor: '#ffffff',
                        letterRendering: true
                    },
                    jsPDF: { 
                        unit: 'in', 
                        format: 'letter', 
                        orientation: 'portrait' 
                    }
                };
                
                // Show loading state
                const originalText = downloadBtn.innerHTML;
                const originalClass = downloadBtn.className;
                downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Generating PDF...';
                downloadBtn.className = 'btn btn-custom btn-secondary';
                downloadBtn.disabled = true;
                
                // Generate and download PDF
                setTimeout(() => {
                    html2pdf().from(pdfContainer).set(options).save().then(() => {
                        // Show success message
                        downloadBtn.innerHTML = '<i class="fas fa-check me-2"></i> Download Complete!';
                        downloadBtn.className = 'btn btn-custom btn-success';
                        
                        setTimeout(() => {
                            downloadBtn.innerHTML = originalText;
                            downloadBtn.className = originalClass;
                            downloadBtn.disabled = false;
                        }, 2000);
                    });
                }, 500);
            });
            
            // Function to update ID card with selected template
            function updateIdCard() {
                const template = templates[currentTemplate];
                const idCardFront = document.getElementById('idCardFront');
                const idCardBack = document.getElementById('idCardBack');
                
                // Update front side
                idCardFront.style.background = `${template.frontBg}, ${template.pattern}`;
                idCardFront.style.color = template.textColor;
                
                // Update back side
                idCardBack.style.background = template.backBg;
                idCardBack.style.color = template.backTextColor;
                
                // Generate front side HTML
                idCardFront.innerHTML = `
                    <div class="security-strip"></div>
                    <div class="card-header" style="background: ${template.headerColor};">
                        <div class="company-logo">
                            <div class="logo-circle" style="background: linear-gradient(135deg, ${template.accentColor}, ${template.secondaryColor});">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div style="font-size: 0.9rem; font-weight: 800; color: ${template.textColor};">${employeeData.companyName}</div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 0.85rem; font-weight: 700;">EMPLOYEE ID CARD</div>
                            <div style="font-size: 0.75rem; opacity: 0.9;">ID: ${employeeData.id}</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="employee-photo-container">
                                    <div class="employee-photo">
                                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(255, 255, 255, 0.1);">
                                            <i class="fas fa-user-tie fa-3x" style="color: rgba(255, 255, 255, 0.8);"></i>
                                        </div>
                                    </div>
                                    <div class="photo-frame"></div>
                                </div>
                                
                                <div class="text-center">
                                    <div class="employee-badge" style="background: ${template.accentColor};">${employeeData.bloodGroup}</div>
                                    <div style="font-size: 0.75rem; opacity: 0.9;">Blood Group</div>
                                </div>
                                
                                <div class="text-center mt-3">
                                    <div class="employee-status status-active">${employeeData.status}</div>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="mb-3">
                                    <div class="field-label">FULL NAME</div>
                                    <div style="font-size: 1.2rem; font-weight: 800; margin-bottom: 0.5rem;">${employeeData.name}</div>
                                    <div class="field-label">DESIGNATION</div>
                                    <div class="field-value" style="font-size: 1rem;">${employeeData.designation}</div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-2">
                                            <div class="field-label">EMPLOYEE ID</div>
                                            <div class="field-value">${employeeData.id}</div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="field-label">DEPARTMENT</div>
                                            <div class="field-value">
                                                ${employeeData.department}
                                                <div class="department-badge">${employeeData.employeeLevel}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="field-label">JOINING DATE</div>
                                            <div class="field-value">${employeeData.joiningDate}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-2">
                                            <div class="field-label">EMPLOYEE TYPE</div>
                                            <div class="field-value">${employeeData.employeeType}</div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="field-label">ACCESS LEVEL</div>
                                            <div class="field-value" style="color: ${template.accentColor}; font-weight: 700;">${employeeData.accessLevel}</div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="field-label">WORK LOCATION</div>
                                            <div class="field-value">${employeeData.workLocation}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 pt-3" style="border-top: 1px dashed rgba(255, 255, 255, 0.3);">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="font-size: 0.75rem; opacity: 0.9;">
                                    <div>${template.name} Template</div>
                                    <div>Issued: ${new Date().toLocaleDateString()}</div>
                                </div>
                                <div class="expiry-date">Valid Until: ${employeeData.expiryDate}</div>
                            </div>
                        </div>
                    </div>
                    <div class="flip-indicator" onclick="event.stopPropagation();">
                        <i class="fas fa-redo-alt"></i>
                    </div>
                `;
                
                // Generate back side HTML
                idCardBack.innerHTML = `
                    <div class="magnetic-strip"></div>
                    <div class="card-header" style="border-bottom: 3px solid ${template.accentColor}; color: ${template.backTextColor}; background: ${template.backTextColor === 'var(--gray-200)' ? 'rgba(0,0,0,0.1)' : 'rgba(0,0,0,0.05)'};">
                        <h5 class="mb-0 text-center" style="font-size: 1.1rem; font-weight: 800;">EMPLOYEE INFORMATION</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="qr-code-container">
                                <div style="text-align: center;">
                                    <div style="font-size: 2rem; margin-bottom: 5px;">
                                        <i class="fas fa-qrcode"></i>
                                    </div>
                                    <div style="font-size: 0.7rem;">EMPLOYEE QR</div>
                                </div>
                            </div>
                            <div style="font-size: 0.85rem; color: ${template.backTextColor === 'var(--gray-200)' ? 'rgba(255,255,255,0.8)' : 'var(--gray-600)'}; font-weight: 600;">Scan for verification</div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="mb-3">
                                    <h6 style="font-size: 0.9rem; color: ${template.accentColor}; border-bottom: 2px solid ${template.accentColor}; padding-bottom: 0.4rem; font-weight: 700;">CONTACT INFORMATION</h6>
                                    <div>
                                        <div class="mb-2">
                                            <div class="back-field-label">Company Email</div>
                                            <div class="back-field-value">${employeeData.email}</div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="back-field-label">Office Phone</div>
                                            <div class="back-field-value">${employeeData.phone}</div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="back-field-label">Work Location</div>
                                            <div class="back-field-value">${employeeData.workLocation}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <h6 style="font-size: 0.9rem; color: ${template.accentColor}; border-bottom: 2px solid ${template.accentColor}; padding-bottom: 0.4rem; font-weight: 700;">SUPERVISOR</h6>
                                    <div class="back-field-value" style="font-size: 0.85rem;">${employeeData.supervisor}</div>
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="mb-3">
                                    <h6 style="font-size: 0.9rem; color: ${template.accentColor}; border-bottom: 2px solid ${template.accentColor}; padding-bottom: 0.4rem; font-weight: 700;">EMERGENCY CONTACT</h6>
                                    <div class="back-field-value" style="font-size: 0.85rem;">${employeeData.emergencyContact}</div>
                                </div>
                                
                                <div class="mb-3">
                                    <h6 style="font-size: 0.9rem; color: ${template.accentColor}; border-bottom: 2px solid ${template.accentColor}; padding-bottom: 0.4rem; font-weight: 700;">ADDITIONAL DETAILS</h6>
                                    <div>
                                        <div class="mb-2">
                                            <div class="back-field-label">Date of Birth</div>
                                            <div class="back-field-value">${employeeData.dob}</div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="back-field-label">Blood Group</div>
                                            <div class="back-field-value" style="color: var(--accent-4); font-weight: 700;">${employeeData.bloodGroup}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4 d-none" style="border-top: 2px dashed ${template.backTextColor === 'var(--gray-200)' ? 'rgba(255,255,255,0.3)' : 'var(--gray-400)'};">
                            <div class="text-center mb-4">
                                <div style="font-size: 0.75rem; color: ${template.backTextColor === 'var(--gray-200)' ? 'rgba(255,255,255,0.7)' : 'var(--gray-600)'}; margin-bottom: 0.5rem;">
                                    <div>This card is property of ${employeeData.companyName}</div>
                                    <div>Must be returned upon termination of employment</div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-end">
                                <div>
                                    <div style="margin-bottom: 0.5rem; font-size: 0.8rem; color: ${template.backTextColor === 'var(--gray-200)' ? 'rgba(255,255,255,0.8)' : 'var(--gray-700)'}; font-weight: 600;">Authorized Signature</div>
                                    <div class="signature-area">
                                        <div class="signature-text">HR Manager Signature</div>
                                    </div>
                                </div>
                                
                                <div style="text-align: right;">
                                    <div style="margin-bottom: 0.5rem; font-size: 0.8rem; color: ${template.backTextColor === 'var(--gray-200)' ? 'rgba(255,255,255,0.8)' : 'var(--gray-700)'}; font-weight: 600;">Card Valid Until</div>
                                    <div style="font-size: 1rem; font-weight: 800; color: ${template.accentColor};">${employeeData.expiryDate}</div>
                                    <div style="font-size: 0.7rem; color: ${template.backTextColor === 'var(--gray-200)' ? 'rgba(255,255,255,0.6)' : 'var(--gray-500)'}; margin-top: 0.25rem;">Annual renewal required</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="back-flip-indicator" onclick="event.stopPropagation();">
                        <i class="fas fa-redo-alt"></i>
                    </div>
                `;
            }
            
            // Initialize with template 1
            updateIdCard();
            
            // Add floating animation to ID card periodically
            setInterval(() => {
                if (!idCard.classList.contains('flipped')) {
                    idCard.classList.add('floating-element');
                    setTimeout(() => {
                        idCard.classList.remove('floating-element');
                    }, 6000);
                }
            }, 8000);
        });
    </script>
@endsection
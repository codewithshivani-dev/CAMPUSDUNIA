@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <title>Student ID Card Templates</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-1: #4cc9f0;
            --accent-2: #7209b7;
            --accent-3: #2a9d8f;
            --accent-4: #e63946;
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
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
        }
        
        .header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2.5rem 0;
            border-radius: 0 0 25px 25px;
            margin-bottom: 3rem;
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.25);
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
                radial-gradient(circle at 80% 20%, rgba(114, 9, 183, 0.1) 0%, transparent 50%);
        }
        
        .header-content {
            position: relative;
            z-index: 2;
        }
        
        .university-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            border: 3px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        
        .template-options {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .template-options::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(to right, var(--primary-color), var(--accent-1));
            border-radius: 20px 20px 0 0;
        }
        
        .template-preview {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: none;
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        
        .template-preview::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(to right, var(--accent-3), var(--secondary-color));
            border-radius: 20px 20px 0 0;
        }
        
        .template-card {
            border: 2px solid transparent;
            border-radius: 15px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            background: white;
            position: relative;
            overflow: hidden;
        }
        
        .template-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .template-card.active {
            border-color: var(--primary-color);
            box-shadow: 0 15px 35px rgba(67, 97, 238, 0.2);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 15px 35px rgba(67, 97, 238, 0.2); }
            50% { box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3); }
            100% { box-shadow: 0 15px 35px rgba(67, 97, 238, 0.2); }
        }
        
        .template-card.active::after {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border-radius: 17px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-1), var(--primary-color));
            z-index: -1;
            animation: rotate 3s linear infinite;
            background-size: 200% 200%;
        }
        
        @keyframes rotate {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .template-thumbnail {
            height: 150px;
            border-radius: 12px;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 600;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        
        .template-thumbnail::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.2), transparent);
        }
        
        .template-1 { 
            background: linear-gradient(135deg, var(--primary-color), var(--accent-1), #3a86ff); 
            color: white; 
        }
        
        .template-2 { 
            background: linear-gradient(135deg, var(--accent-2), var(--accent-4), #ff006e); 
            color: white; 
        }
        
        .template-3 { 
            background: linear-gradient(135deg, var(--accent-3), var(--secondary-color), #06d6a0); 
            color: white; 
        }
        
        .template-4 { 
            background: linear-gradient(135deg, var(--gray-800), var(--dark-color), #2d00f7); 
            color: white; 
        } 
        
        .id-card-container {
            perspective: 1500px;
            margin-bottom: 2.5rem;
        }
        
        .id-card {
            width: 100%;
            max-width: 450px;
            height: 900px;
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
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .id-card-back {
            transform: rotateY(180deg);
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
        }
        
        .card-header {
            padding: 1.25rem 1.5rem;
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
            height: calc(100% - 70px);
            overflow-y: auto;
        }
        
        .card-body::-webkit-scrollbar {
            width: 5px;
        }
        
        .card-body::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }
        
        .card-body::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }
        
        .student-photo-container {
            position: relative;
            width: 120px;
            height: 140px;
            margin: 0 auto 1.5rem;
        }
        
        .student-photo {
            width: 100%;
            height: 100%;
            border-radius: 12px;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 2;
        }
        
        .photo-frame {
            position: absolute;
            top: -8px;
            left: -8px;
            right: -8px;
            bottom: -8px;
            border: 3px solid rgba(255, 255, 255, 0.6);
            border-radius: 16px;
            z-index: 1;
        }
        
        .id-badge {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(5px);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 0.75rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .field-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0.25rem;
            display: block;
            font-weight: 600;`
            letter-spacing: 0.5px;
        }
        
        .field-value {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: white !important;
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
        }
        
        .back-field-value {
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 0.75rem;
            color: var(--gray-800);
            display: block;
            padding: 0.5rem;
            background: rgba(0, 0, 0, 0.03);
            border-radius: 6px;
            border-left: 3px solid var(--accent-3);
        
        
        }
        
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 2.5rem;
            flex-wrap: wrap;
        }
        
        .btn-custom {
            padding: 0.75rem 2.25rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 180px;
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .btn-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        
        .btn-custom:hover::before {
            left: 100%;
        }
        
        .btn-download {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-2));
            color: white;
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        }
        
        .btn-download:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(67, 97, 238, 0.4);
            color: white;
        }
        
        .btn-flip {
            background: linear-gradient(135deg, #ffffff, var(--gray-200));
            color: var(--gray-800);
            border: 2px solid var(--gray-300);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .instructions::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
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
            content: '✓';
            position: absolute;
            left: -1.5rem;
            color: var(--primary-color);
            font-weight: bold;
        }
        
        .student-info-panel {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: none;
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .student-info-panel::before {
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
            box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.05);
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }
        
        .info-item {
            padding: 0.75rem 0;
            border-bottom: 1px dashed var(--gray-300);
            position: relative;
        }
        
        .info-item::before {
            content: '';
            position: absolute;
            bottom: 0;
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
            background: rgba(0, 0, 0, 0.15);
            color: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .flip-indicator:hover {
            background: rgba(0, 0, 0, 0.25);
            transform: rotate(180deg);
        }
        
        .back-flip-indicator {
            position: absolute;
            bottom: 20px;
            left: 20px;
            background: rgba(0, 0, 0, 0.1);
            color: var(--gray-700);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            border: 2px solid rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .back-flip-indicator:hover {
            background: rgba(0, 0, 0, 0.2);
            transform: rotate(180deg);
        }
        
        .qr-code {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #333, #555);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.7rem;
            margin: 1rem auto;
            border: 3px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .university-logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .logo-circle {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin: 0 auto 0.5rem;
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        @media (max-width: 1200px) {
            .id-card {
                max-width: 420px;
                height: 480px;
            }
        }
        
        @media (max-width: 992px) {
            .id-card {
                max-width: 400px;
                height: 460px;
            }
            
            .btn-custom {
                min-width: 160px;
                padding: 0.7rem 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .id-card {
                max-width: 380px;
                height: 440px;
            }
            
            .student-photo-container {
                width: 110px;
                height: 130px;
            }
            
            .field-label, .back-field-label {
                font-size: 0.8rem;
            }
            
            .field-value, .back-field-value {
                font-size: 0.9rem;
            }
            
            .template-thumbnail {
                height: 130px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 576px) {
            .id-card {
                max-width: 350px;
                height: 420px;
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
        
        .glow-effect {
            position: relative;
        }
        
        .glow-effect::after {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, var(--primary-color), var(--accent-1), var(--primary-color));
            border-radius: 22px;
            z-index: -1;
            filter: blur(10px);
            opacity: 0.5;
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes glow {
            from { opacity: 0.5; }
            to { opacity: 0.8; }
        }
        
        .floating-element {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .template-name {
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 0.9rem;
            font-weight: 700;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            z-index: 2;
        }
    </style>
</head>
<body>
    <div class="header floating-element">
        <div class="container-fluid">
            <div class="header-content">
                <div class="university-badge">
                    <i class="fas fa-university fa-3x"></i>
                </div>
                <h1 class="text-center mb-2"><i class="fas fa-id-card me-3"></i>Student ID Card Designer</h1>
                <p class="text-center mb-0 fs-5">Choose from stunning templates, customize, and download professional ID cards</p>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="template-options">
                    <h3 class="mb-4"><i class="fas fa-palette me-2" style="color: var(--primary-color);"></i>Select Template Design</h3>
                    <div class="row g-3">
                        <div class="col-sm-6 col-lg-12">
                            <div class="template-card active glow-effect" data-template="1">
                                <div class="template-thumbnail template-1">
                                    Modern Blue Design
                                    <div class="template-name">OCEAN BREEZE</div>
                                </div>
                                <h5 class="mb-1">Ocean Breeze</h5>
                                <p class="small text-muted mb-0">Clean blue gradient with modern accents</p>
                                <div class="mt-2 d-flex align-items-center">
                                    <div class="badge bg-primary me-2">Most Popular</div>
                                    <span class="text-primary small fw-bold"><i class="fas fa-check-circle me-1"></i> Selected</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-12">
                            <div class="template-card" data-template="2">
                                <div class="template-thumbnail template-2">
                                    Elegant Purple Design
                                    <div class="template-name">ROYAL PURPLE</div>
                                </div>
                                <h5 class="mb-1">Royal Purple</h5>
                                <p class="small text-muted mb-0">Regal purple with vibrant red accents</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-12">
                            <div class="template-card" data-template="3">
                                <div class="template-thumbnail template-3">
                                    Green Professional
                                    <div class="template-name">EMERALD PRO</div>
                                </div>
                                <h5 class="mb-1">Emerald Pro</h5>
                                <p class="small text-muted mb-0">Professional teal and purple design</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-12">
                            <div class="template-card" data-template="4">
                                <div class="template-thumbnail template-4">
                                    Dark Minimalist
                                    <div class="template-name">MIDNIGHT DARK</div>
                                </div>
                                <h5 class="mb-1">Midnight Dark</h5>
                                <p class="small text-muted mb-0">Dark theme with elegant simplicity</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="instructions">
                    <h5><i class="fas fa-info-circle me-2" style="color: var(--primary-color);"></i>How to Use</h5>
                    <ul class="mt-3">
                        <li>Click on any template to preview it instantly</li>
                        <li>Click the ID card to flip between front and back sides</li>
                        <li>Use the "Flip Card" button for manual control</li>
                        <li>Download high-quality PDF for printing</li>
                        <li>All templates use official university colors</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="template-preview">
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
                    
                    <div class="text-center mt-4">
                        <p class="text-muted fw-medium"><i class="fas fa-mouse-pointer me-2"></i> Click on the ID card or use buttons below to interact</p>
                    </div>
                    
                    <div class="action-buttons">
                        <button class="btn btn-custom btn-flip" id="flipBtn">
                            <i class="fas fa-sync-alt me-2"></i> Flip Card
                        </button>
                        <button class="btn btn-custom btn-download" id="downloadBtn">
                            <i class="fas fa-download me-2"></i> Download ID Card (PDF)
                        </button>
                    </div>
                </div>
                
                <div class="student-info-panel">
                    <h5 class="mb-4"><i class="fas fa-user-graduate me-2" style="color: var(--primary-color);"></i>Student Information</h5>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Full Name:</span>
                            <span class="field-value text-dark fw-bold">Alexandra Johnson</span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Student ID:</span>
                            <span class="field-value text-dark fw-bold">STU-2023-7894</span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Program:</span>
                            <span class="field-value text-dark">Bachelor of Computer Science</span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Faculty:</span>
                            <span class="field-value text-dark">Faculty of Technology</span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Enrollment Date:</span>
                            <span class="field-value text-dark">September 15, 2023</span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Status:</span>
                            <span class="field-value text-success fw-bold" style="color:#fff!important;">Active <i class="fas fa-check-circle ms-1"></i></span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Year/Semester:</span>
                            <span class="field-value text-dark">Year 2 / Semester 3</span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Date of Birth:</span>
                            <span class="field-value text-dark">May 18, 2002</span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Blood Group:</span>
                            <span class="field-value text-danger fw-bold">O+</span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Email:</span>
                            <span class="field-value text-dark">alexandra.j@university.edu</span>
                        </div>
                        <div class="info-item">
                            <span class="field-label text-dark fw-semibold">Phone:</span>
                            <span class="field-value text-dark">+1 (555) 123-4567</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <div class="container">
                <p class="mb-2 fw-medium">© 2023 University ID System | Professional ID Card Designer</p>
                <p class="mb-0 small">Designed for optimal printing on PVC cards | All templates are fully customizable</p>
                <div class="mt-3">
                    <span class="badge bg-primary me-2">Secure</span>
                    <span class="badge bg-success me-2">High-Quality</span>
                    <span class="badge bg-warning">Print-Ready</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Student data
            const studentData = {
                name: "Alexandra Johnson",
                id: "STU-2023-7894",
                status: "Active",
                faculty: "Faculty of Technology",
                yearSemester: "Year 2 / Semester 3",
                dob: "May 18, 2002",
                bloodGroup: "O+",
                email: "alexandra.j@university.edu",
                phone: "+1 (555) 123-4567",
                program: "Bachelor of Computer Science",
                enrollmentDate: "September 15, 2023",
                parentName: "Michael Johnson",
                parentPhone: "+1 (555) 987-6543",
                address: "123 University Avenue, Suite 45\nSpringfield, ST 12345\nUnited States",
                emergencyContact: "Sarah Johnson - +1 (555) 456-7890"
            };
            
            // Template configurations with enhanced designs
            const templates = {
                1: {
                    name: "Ocean Breeze",
                    frontBg: "linear-gradient(135deg, var(--primary-color), var(--accent-1), #3a86ff)",
                    backBg: "linear-gradient(145deg, #ffffff, #f0f7ff)",
                    textColor: "white",
                    backTextColor: "var(--gray-800)",
                    accentColor: "var(--accent-1)",
                    headerColor: "rgba(255, 255, 255, 0.2)",
                    pattern: "url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100\" height=\"100\" viewBox=\"0 0 100 100\"><circle cx=\"50\" cy=\"50\" r=\"40\" fill=\"none\" stroke=\"white\" stroke-width=\"2\" stroke-dasharray=\"5,5\" opacity=\"0.1\"/></svg>')"
                },
                2: {
                    name: "Royal Purple",
                    frontBg: "linear-gradient(135deg, var(--accent-2), var(--accent-4), #ff006e)",
                    backBg: "linear-gradient(145deg, #ffffff, #f9f0ff)",
                    textColor: "white",
                    backTextColor: "var(--gray-800)",
                    accentColor: "var(--accent-4)",
                    headerColor: "rgba(255, 255, 255, 0.2)",
                    pattern: "url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100\" height=\"100\" viewBox=\"0 0 100 100\"><rect x=\"10\" y=\"10\" width=\"80\" height=\"80\" fill=\"none\" stroke=\"white\" stroke-width=\"2\" stroke-dasharray=\"10,5\" opacity=\"0.1\"/></svg>')"
                },
                3: {
                    name: "Emerald Pro",
                    frontBg: "linear-gradient(135deg, var(--accent-3), var(--secondary-color), #06d6a0)",
                    backBg: "linear-gradient(145deg, #ffffff, #f0fff9)",
                    textColor: "white",
                    backTextColor: "var(--gray-800)",
                    accentColor: "var(--accent-3)",
                    headerColor: "rgba(255, 255, 255, 0.2)",
                    pattern: "url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100\" height=\"100\" viewBox=\"0 0 100 100\"><polygon points=\"50,10 90,90 10,90\" fill=\"none\" stroke=\"white\" stroke-width=\"2\" stroke-dasharray=\"8,8\" opacity=\"0.1\"/></svg>')"
                },
                4: {
                    name: "Midnight Dark",
                    frontBg: "linear-gradient(135deg, var(--gray-800), var(--dark-color), #2d00f7)",
                    backBg: "linear-gradient(145deg, #2a2a3e, #1a1a2e)",
                    textColor: "white",
                    backTextColor: "var(--gray-200)",
                    accentColor: "var(--gray-500)",
                    headerColor: "rgba(255, 255, 255, 0.1)",
                    pattern: "url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100\" height=\"100\" viewBox=\"0 0 100 100\"><line x1=\"10\" y1=\"10\" x2=\"90\" y2=\"90\" stroke=\"white\" stroke-width=\"1\" opacity=\"0.05\"/><line x1=\"90\" y1=\"10\" x2=\"10\" y2=\"90\" stroke=\"white\" stroke-width=\"1\" opacity=\"0.05\"/></svg>')"
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
                        c.classList.remove('glow-effect');
                        const checkSpan = c.querySelector('.text-primary');
                        if (checkSpan) {
                            checkSpan.innerHTML = '';
                        }
                    });
                    
                    // Add active class to clicked card
                    this.classList.add('active');
                    this.classList.add('glow-effect');
                    
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
                    flipBtn.innerHTML = '<i class="fas fa-sync-alt me-2"></i> Flip Card';
                }, 300);
            });
            
            // Download ID card as PDF
            const downloadBtn = document.getElementById('downloadBtn');
            downloadBtn.addEventListener('click', function() {
                // Create a container for the PDF
                const pdfContainer = document.createElement('div');
                pdfContainer.style.width = '450px';
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
                footerText.textContent = 'Official University ID Card - Valid with university seal and signature';
                footerText.style.fontSize = '12px';
                footerText.style.color = 'var(--gray-600)';
                footerText.style.fontWeight = '600';
                footerText.style.marginBottom = '10px';
                
                const instructions = document.createElement('p');
                instructions.textContent = 'For best results, print on PVC card stock at 100% scale';
                instructions.style.fontSize = '11px';
                instructions.style.color = 'var(--gray-500)';
                instructions.style.fontStyle = 'italic';
                
                footer.appendChild(footerText);
                footer.appendChild(instructions);
                pdfContainer.appendChild(footer);
                
                // Add watermark
                const watermark = document.createElement('div');
                watermark.style.position = 'absolute';
                watermark.style.top = '50%';
                watermark.style.left = '50%';
                watermark.style.transform = 'translate(-50%, -50%) rotate(-45deg)';
                watermark.style.opacity = '0.05';
                watermark.style.fontSize = '60px';
                watermark.style.fontWeight = 'bold';
                watermark.style.color = 'var(--primary-color)';
                watermark.style.whiteSpace = 'nowrap';
                watermark.textContent = 'UNIVERSITY OFFICIAL';
                watermark.style.zIndex = '-1';
                pdfContainer.style.position = 'relative';
                pdfContainer.appendChild(watermark);
                
                // PDF options
                const options = {
                    margin: 0.5,
                    filename: `Student_ID_${studentData.name.replace(/\s+/g, '_')}_${templates[currentTemplate].name.replace(/\s+/g, '_')}.pdf`,
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
                    <div class="card-header" style="background: ${template.headerColor};">
                        <div class="university-logo">
                            <div class="logo-circle">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div style="font-size: 0.8rem; font-weight: 700; color: ${template.textColor};">UNIVERSITY ID</div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 0.8rem; font-weight: 600;">VALID: 2023-2024</div>
                            <div style="font-size: 0.7rem; opacity: 0.9;">ID: ${studentData.id}</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="student-photo-container">
                            <div class="student-photo">
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(255, 255, 255, 0.1);">
                                    <i class="fas fa-user-graduate fa-3x" style="color: rgba(255, 255, 255, 0.8);"></i>
                                </div>
                            </div>
                            <div class="photo-frame"></div>
                        </div>
                        
                        <div class="text-center mb-3">
                            <div class="id-badge" style="background: ${template.accentColor};">BLOOD GROUP: ${studentData.bloodGroup}</div>
                        </div>
                        
                        <div class="text-center mb-4">
                            <div style="font-size: 1.2rem; font-weight: 700; margin-bottom: 0.25rem;">${studentData.name}</div>
                            <div style="font-size: 0.9rem; opacity: 0.9;">${studentData.program}</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-2">
                                    <div class="field-label">STUDENT ID</div>
                                    <div class="field-value">${studentData.id}</div>
                                </div>
                                <div class="mb-2">
                                    <div class="field-label">FACULTY</div>
                                    <div class="field-value">${studentData.faculty}</div>
                                </div>
                                <div class="mb-2">
                                    <div class="field-label">YEAR/SEMESTER</div>
                                    <div class="field-value">${studentData.yearSemester}</div>
                                </div>
                                <div class="mb-2">
                                    <div class="field-label">DATE OF BIRTH</div>
                                    <div class="field-value">${studentData.dob}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <div class="field-label">STATUS</div>
                                    <div class="field-value" style="color: ${template.accentColor}; font-weight: 700;">${studentData.status} <i class="fas fa-circle-check"></i></div>
                                </div>
                                <div class="mb-2">
                                    <div class="field-label">ENROLLMENT DATE</div>
                                    <div class="field-value">${studentData.enrollmentDate}</div>
                                </div>
                                <div class="mb-2">
                                    <div class="field-label">EMAIL</div>
                                    <div class="field-value" style="font-size: 0.85rem;">${studentData.email}</div>
                                </div>
                                <div class="mb-2">
                                    <div class="field-label">PHONE</div>
                                    <div class="field-value">${studentData.phone}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-3" style="border-top: 1px dashed rgba(255, 255, 255, 0.3);">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="font-size: 0.75rem; opacity: 0.9;">
                                    <div>${template.name} Template</div>
                                    <div>Issued: ${new Date().toLocaleDateString()}</div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="flip-indicator" onclick="event.stopPropagation();">
                        <i class="fas fa-redo-alt"></i>
                    </div>
                `;
                
                // Generate back side HTML
                idCardBack.innerHTML = `
                    <div class="card-header" style="border-bottom: 3px solid ${template.accentColor}; color: ${template.backTextColor}; background: ${template.backTextColor === 'var(--gray-200)' ? 'rgba(0,0,0,0.1)' : 'rgba(0,0,0,0.05)'};">
                        <h5 class="mb-0 text-center" style="font-size: 1.1rem; font-weight: 700;">UNIVERSITY ID CARD - BACK SIDE</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div style="font-size: 1rem; font-weight: 700; color: ${template.accentColor}; margin-bottom: 0.5rem;">ADDITIONAL INFORMATION</div>
                            <div style="font-size: 0.85rem; color: ${template.backTextColor === 'var(--gray-200)' ? 'rgba(255,255,255,0.8)' : 'var(--gray-600)'};">Keep this card with you at all times</div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="mb-3">
                                    <h6 style="font-size: 0.9rem; color: ${template.accentColor}; border-bottom: 2px solid ${template.accentColor}; padding-bottom: 0.4rem; font-weight: 700;">PARENT/GUARDIAN</h6>
                                    <div>
                                        <div class="mb-2">
                                            <div class="back-field-label">Full Name</div>
                                            <div class="back-field-value">${studentData.parentName}</div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="back-field-label">Contact Number</div>
                                            <div class="back-field-value">${studentData.parentPhone}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="mb-3">
                                    <h6 style="font-size: 0.9rem; color: ${template.accentColor}; border-bottom: 2px solid ${template.accentColor}; padding-bottom: 0.4rem; font-weight: 700;">EMERGENCY CONTACT</h6>
                                    <div class="back-field-value" style="font-size: 0.85rem;">${studentData.emergencyContact}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6 style="font-size: 0.9rem; color: ${template.accentColor}; border-bottom: 2px solid ${template.accentColor}; padding-bottom: 0.4rem; font-weight: 700;">RESIDENTIAL ADDRESS</h6>
                            <div class="back-field-value" style="white-space: pre-line; font-size: 0.85rem; line-height: 1.6;">
                                ${studentData.address}
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4" style="border-top: 2px dashed ${template.backTextColor === 'var(--gray-200)' ? 'rgba(255,255,255,0.3)' : 'var(--gray-400)'};">
                            <div style="text-align: center; margin-bottom: 1.5rem;">
                                <div style="font-size: 0.75rem; color: ${template.backTextColor === 'var(--gray-200)' ? 'rgba(255,255,255,0.7)' : 'var(--gray-600)'}; margin-bottom: 0.5rem;">
                                    <div>This card is property of University</div>
                                    <div>If found, please return to Administration Office</div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-end">
                                <div>
                                    <div style="margin-bottom: 0.5rem; font-size: 0.8rem; color: ${template.backTextColor === 'var(--gray-200)' ? 'rgba(255,255,255,0.8)' : 'var(--gray-700)'}; font-weight: 600;">Authorized Signature</div>
                                    <div style="width: 140px; height: 50px; background: ${template.backTextColor === 'var(--gray-200)' ? 'rgba(255,255,255,0.1)' : 'var(--gray-100)'}; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 2px dashed ${template.backTextColor === 'var(--gray-200)' ? 'rgba(255,255,255,0.3)' : 'var(--gray-300)'};">
                                        <div style="font-size: 0.75rem; color: ${template.backTextColor === 'var(--gray-200)' ? 'rgba(255,255,255,0.7)' : 'var(--gray-600)'}; font-style: italic;">Signature Here</div>
                                    </div>
                                </div>
                                
                                <div style="text-align: right;">
                                    <div style="margin-bottom: 0.5rem; font-size: 0.8rem; color: ${template.backTextColor === 'var(--gray-200)' ? 'rgba(255,255,255,0.8)' : 'var(--gray-700)'}; font-weight: 600;">Card Valid Until</div>
                                    <div style="font-size: 1rem; font-weight: 700; color: ${template.accentColor};">June 30, 2024</div>
                                    <div style="font-size: 0.7rem; color: ${template.backTextColor === 'var(--gray-200)' ? 'rgba(255,255,255,0.6)' : 'var(--gray-500)'}; margin-top: 0.25rem;">Renewal Required</div>
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
            
            // Add floating animation to ID card
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
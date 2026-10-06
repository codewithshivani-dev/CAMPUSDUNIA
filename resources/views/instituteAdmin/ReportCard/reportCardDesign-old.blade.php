@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ANEE'S SCHOOL - Dynamic Achievement Record System</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- html2canvas -->
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #4bb543;
            --warning-color: #ff9e00;
            --danger-color: #e63946;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gradient-primary: linear-gradient(135deg, #4361ee, #3a0ca3);
            --gradient-success: linear-gradient(135deg, #4bb543, #2a9d40);
            --gradient-warning: linear-gradient(135deg, #ff9e00, #ff7b00);
            --gradient-danger: linear-gradient(135deg, #e63946, #d00000);
            --gradient-gold: linear-gradient(135deg, #FFD700, #FFA500);
            --gradient-purple: linear-gradient(135deg, #8A2BE2, #4B0082);
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 15px 40px rgba(0, 0, 0, 0.15);
            --border-radius: 16px;
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        /* Header */
        .main-header {
            background: var(--gradient-primary);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2.5rem;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }
        
        .main-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 30px 30px;
            opacity: 0.3;
            z-index: 0;
        }
        
        .school-logo {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: 1px;
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 0.5rem;
            text-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .school-subtitle {
            font-size: 1.2rem;
            opacity: 0.95;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
            font-weight: 400;
        }
        
        .campus-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin-top: 15px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            transition: var(--transition);
            position: relative;
            z-index: 1;
        }
        
        .campus-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
        }
        
        /* Student Search Section */`
        .student-search-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            border-top: 8px solid var(--primary-color);
            position: relative;
            overflow: hidden;
        }
        .student-search-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: var(--gradient-primary);
        }
        
        .search-title {
            color: var(--secondary-color);
            font-weight: 800;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 1.5rem;
        }
        
        .search-box {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .search-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 1.4rem;
            z-index: 2;
        }
        
        .search-input {
            padding-left: 60px;
            border: 3px solid #e0e0e0;
            border-radius: 15px;
            height: 65px;
            font-size: 1.2rem;
            transition: var(--transition);
            font-weight: 500;
        }
        
        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.3rem rgba(67, 97, 238, 0.25);
            transform: translateY(-2px);
        }
        
        .search-btn {
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 15px;
            height: 65px;
            font-weight: 700;
            font-size: 1.2rem;
            padding: 0 35px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        }
        
        .search-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(67, 97, 238, 0.4);
            color: white;
        }
        
        /* Student Info Preview */
        .student-info-preview {
            background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-top: 2rem;
            border: 2px solid var(--primary-color);
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.15);
            display: none;
            animation: slideInUp 0.5s ease;
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .student-info-preview.show {
            display: block;
        }
        
        .student-avatar {
            width: 100px;
            height: 100px;
            border-radius: 20px;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-right: 25px;
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
            transition: var(--transition);
        }
        
        .student-avatar:hover {
            transform: rotate(5deg) scale(1.05);
        }
        
        /* Cards */
        .dashboard-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            border: none;
            margin-bottom: 2.5rem;
            overflow: hidden;
            transition: var(--transition);
            display: none;
            animation: fadeIn 0.6s ease;
        }
        
        .dashboard-card.show {
            display: block;
        }
        
        .card-header-custom {
            background: var(--gradient-primary);
            color: white;
            font-weight: 800;
            padding: 1.5rem 2rem;
            border-bottom: none;
            font-size: 1.3rem;
            font-family: 'Montserrat', sans-serif;
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
            overflow: hidden;
        }
        
        .card-header-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        .card-header-custom i {
            font-size: 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }
        
        .card-body-custom {
            padding: 2rem;
        }
        
        /* Student Profile Form - Parallel Layout */
        .parallel-form-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .form-group-enhanced {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .form-group-enhanced label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--secondary-color);
            font-size: 0.95rem;
        }
        
        .form-control-enhanced {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 500;
            transition: var(--transition);
            background: #f8f9ff;
        }
        
        .form-control-enhanced:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
            background: white;
            transform: translateY(-2px);
        }
        
        .form-control-enhanced:read-only {
            background: #f0f2ff;
            cursor: not-allowed;
        }
        
        .form-control-enhanced[type="date"] {
            padding-right: 20px;
        }
        
        /* Input with icon */
        .input-with-icon {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 1.2rem;
        }
        
        /* Health Status Cards */
        .health-card {
            background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
            border-radius: 15px;
            padding: 1.5rem;
            border: 2px solid #e0e0e0;
            transition: var(--transition);
        }
        
        .health-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-5px);
            box-shadow: var(--shadow);
        }
        
        .health-card h5 {
            color: var(--secondary-color);
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Attendance Card */
        .attendance-card {
            background: linear-gradient(135deg, #fff8f0 0%, #ffffff 100%);
            border: 2px solid #ffd700;
            border-radius: 15px;
            padding: 1.5rem;
            transition: var(--transition);
        }
        
        .attendance-card:hover {
            border-color: #ff9800;
            transform: translateY(-5px);
            box-shadow: var(--shadow);
        }
        
        .attendance-card h5 {
            color: #ff6b00;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Tab System */
        .academic-tabs {
            margin-bottom: 2rem;
        }
        
        .nav-tabs-custom {
            border: none;
            background: linear-gradient(135deg, #f8f9ff 0%, #eef1ff 100%);
            border-radius: 15px;
            padding: 10px;
            display: flex;
            flex-wrap: wrap;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .nav-tabs-custom .nav-item {
            flex: 1;
            min-width: 180px;
        }
        
        .nav-tabs-custom .nav-link {
            border: none;
            border-radius: 12px;
            padding: 15px 25px;
            font-weight: 700;
            color: var(--secondary-color);
            text-align: center;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin: 0 5px;
            position: relative;
            overflow: hidden;
        }
        
        .nav-tabs-custom .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-primary);
            opacity: 0;
            transition: var(--transition);
            z-index: 1;
        }
        
        .nav-tabs-custom .nav-link:hover::before {
            opacity: 0.1;
        }
        
        .nav-tabs-custom .nav-link:hover {
            color: var(--primary-color);
            transform: translateY(-3px);
        }
        
        .nav-tabs-custom .nav-link.active {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
            transform: translateY(-3px);
        }
        
        .nav-tabs-custom .nav-link.active::before {
            opacity: 0;
        }
        
        .nav-tabs-custom .nav-link .tab-badge {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 700;
            margin-left: 8px;
            min-width: 50px;
            transition: var(--transition);
        }
        
        .nav-tabs-custom .nav-link:not(.active) .tab-badge {
            background: rgba(67, 97, 238, 0.15);
            color: var(--primary-color);
        }
        
        .tab-content-custom {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            min-height: 400px;
        }
        
        .tab-pane-custom {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        
        .tab-pane-custom.active {
            display: block;
        }
        
        /* Tables */
        .table-responsive {
            border-radius: 15px;
            overflow: scroll;
            border: 2px solid #e0e0e0;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        }
        
        .table-custom {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .table-custom thead th {
            background: var(--gradient-primary);
            color: white;
            font-weight: 700;
            border: none;
            padding: 1.2rem 1.5rem;
            text-align: center;
            vertical-align: middle;
            font-size: 1rem;
            position: relative;
        }
        
        .table-custom thead th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 10%;
            right: 10%;
            height: 3px;
            background: rgba(255, 255, 255, 0.3);
        }
        
        .table-custom tbody td {
            padding: 1.2rem 1.5rem;
            vertical-align: middle;
            border-bottom: 2px solid #f8f9ff;
            font-weight: 500;
        }
        
        .table-custom tbody tr {
            transition: var(--transition);
        }
        
        .table-custom tbody tr:hover {
            background: linear-gradient(90deg, rgba(67, 97, 238, 0.05) 0%, rgba(67, 97, 238, 0.02) 100%);
            transform: scale(1.01);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        /* Grade Badges */
        .grade-badge {
            font-weight: 800;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.95rem;
            display: inline-block;
            text-align: center;
            min-width: 70px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            transition: var(--transition);
        }
        
        .grade-badge:hover {
            transform: scale(1.1) rotate(-2deg);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        
        .grade-a1 { 
            background: linear-gradient(135deg, #28a745, #1e7e34);
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .grade-a2 { 
            background: linear-gradient(135deg, #20c997, #13855c);
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .grade-b1 { 
            background: linear-gradient(135deg, #17a2b8, #0c5460);
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .grade-b2 { 
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .grade-c1 { 
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .grade-c2 { 
            background: linear-gradient(135deg, #ff9e00, #cc7a00);
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .grade-d { 
            background: linear-gradient(135deg, #e63946, #b71c1c);
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .grade-e { 
            background: linear-gradient(135deg, #6f42c1, #4e2882);
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        /* Mark Input Styling */
        .mark-input {
            width: 100px !important;
            margin: 0 auto;
            text-align: center;
            font-weight: 700;
            border: 3px solid #e0e0e0;
            border-radius: 12px;
            padding: 12px;
            transition: var(--transition);
            font-size: 1.1rem;
            background: #f8f9ff;
        }
        
        .mark-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.3rem rgba(67, 97, 238, 0.25);
            transform: scale(1.08);
            background: white;
        }
        
        /* Total Marks Display */
        .total-marks {
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--secondary-color);
            min-width: 60px;
            display: inline-block;
            text-align: center;
            background: linear-gradient(135deg, #f8f9ff 0%, #eef1ff 100%);
            padding: 12px 16px;
            border-radius: 12px;
            border: 2px solid var(--primary-color);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.1);
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 3rem;
        }
        
        .btn-custom {
            border: none;
            border-radius: 15px;
            padding: 15px 30px;
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: var(--transition);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            min-width: 180px;
        }
        
        .btn-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: var(--transition);
        }
        
        .btn-custom:hover::before {
            left: 100%;
        }
        
        .btn-primary-custom {
            background: var(--gradient-primary);
            color: white;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(67, 97, 238, 0.3);
        }
        
        .btn-success-custom {
            background: var(--gradient-success);
            color: white;
        }
        
        .btn-success-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(75, 181, 67, 0.3);
        }
        
        .btn-warning-custom {
            background: var(--gradient-warning);
            color: white;
        }
        
        .btn-warning-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(255, 158, 0, 0.3);
        }
        
        .btn-danger-custom {
            background: var(--gradient-danger);
            color: white;
        }
        
        .btn-info-custom {
            background: var(--gradient-gold);
            color: white;
        }
        
        .btn-info-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(255, 215, 0, 0.3);
        }
        
        .btn-purple-custom {
            background: var(--gradient-purple);
            color: white;
        }
        
        .btn-purple-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(138, 43, 226, 0.3);
        }
        
        /* Performance Cards */
        .term-performance-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 2px solid #e0e0e0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
        }
        
        .term-performance-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 15px 40px rgba(67, 97, 238, 0.1);
            transform: translateY(-5px);
        }
        
        .term-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid rgba(67, 97, 238, 0.15);
        }
        
        .term-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .term-average {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary-color);
            background: linear-gradient(135deg, #f8f9ff 0%, #eef1ff 100%);
            padding: 10px 25px;
            border-radius: 50px;
            border: 2px solid var(--primary-color);
        }
        
        /* Performance Summary */
        .performance-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .summary-card {
            background: white;
            border-radius: 15px;
            padding: 1.8rem;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            border-top: 5px solid var(--primary-color);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        
        .summary-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }
        
        .summary-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(67,97,238,0.05) 1px, transparent 1px);
            background-size: 20px 20px;
            opacity: 0.5;
            z-index: 0;
        }
        
        .summary-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--secondary-color);
            margin-bottom: 0.8rem;
            position: relative;
            z-index: 1;
        }
        
        .summary-label {
            font-size: 1rem;
            color: #6c757d;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }
        
        /* Report Card Preview */
        .report-card-preview {
            background: white;
            border: 3px solid #333;
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            padding: 20mm;
            font-family: 'Times New Roman', Times, serif;
            box-shadow: 0 0 40px rgba(0,0,0,0.15);
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            overflow-y: auto;
            max-height: 90vh;
        }
        
        .report-card-preview.show {
            display: block;
            animation: scaleIn 0.3s ease;
        }
        
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }
        
        .pdf-close-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--gradient-danger);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            cursor: pointer;
            z-index: 10000;
            font-weight: 700;
            box-shadow: 0 8px 20px rgba(230, 57, 70, 0.3);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .pdf-close-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(230, 57, 70, 0.4);
        }
        
        /* Co-Scholastic Areas */
        .co-scholastic-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .co-scholastic-card {
            background: linear-gradient(135deg, #f8fff8 0%, #ffffff 100%);
            border-radius: 15px;
            padding: 1.5rem;
            border: 2px solid #e0f7e0;
            transition: var(--transition);
        }
        
        .co-scholastic-card:hover {
            border-color: #4caf50;
            transform: translateY(-5px);
        }
        
        /* Notification System */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 20px 25px;
            border-radius: 15px;
            color: white;
            font-weight: 600;
            z-index: 9999;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 15px;
            transform: translateX(150%);
            transition: transform 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification-success {
            background: var(--gradient-success);
            border-left: 5px solid #2a9d40;
        }
        
        .notification-error {
            background: var(--gradient-danger);
            border-left: 5px solid #b71c1c;
        }
        
        .notification-info {
            background: var(--gradient-primary);
            border-left: 5px solid #3a0ca3;
        }
        
        .notification-warning {
            background: var(--gradient-warning);
            border-left: 5px solid #cc7a00;
        }
        
        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9998;
        }
        
        .loading-overlay.show {
            display: flex;
            animation: fadeIn 0.3s ease;
        }
        
        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .parallel-form-group {
                grid-template-columns: 1fr;
            }
            
            .nav-tabs-custom .nav-item {
                min-width: 140px;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: stretch;
            }
            
            .btn-custom {
                width: 100%;
            }
            
            .performance-summary {
                grid-template-columns: 1fr;
            }
            
            .search-input, .search-btn {
                height: 55px;
            }
            
            .student-avatar {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-12 text-center text-lg-start">
                    <h1 class="school-logo">ANEE'S SCHOOL</h1>
                    <p class="school-subtitle">A Senior Secondary SMART School Affiliated to C.B.S.E. New Delhi</p>
                    
                    <div class="row mt-3 g-3">
                        <div class="col-md-6">
                            <div class="campus-card">
                                <h6 class="mb-2"><strong><i class="fas fa-school me-2"></i>KHARAR CAMPUS</strong></h6>
                                <p class="mb-1 small">Aff. No. – 1630565</p>
                                <p class="mb-1 small">Shivjot Enclave, Kharar</p>
                                <p class="mb-1 small">Email: anees.school@yahoo.com</p>
                                <p class="mb-0 small">Phone: 7527066620, 7527066621</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="campus-card">
                                <h6 class="mb-2"><strong><i class="fas fa-school me-2"></i>MOHAU CAMPUS</strong></h6>
                                <p class="mb-1 small">Aff. No. – 1630868</p>
                                <p class="mb-1 small">Email: aneeschool.69@gmail.com</p>
                                <p class="mb-0 small">Phone: 9464644444, 7527066607</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 text-center text-lg-end mt-4 mt-lg-0">
                    <div class="bg-white rounded-4 p-4 d-inline-block shadow-lg">
                        <h5 class="text-primary mb-2 fw-bold">ACHIEVEMENT RECORD SYSTEM</h5>
                        <div class="d-flex justify-content-center">
                            <div class="text-center mx-4">
                                <h6 class="text-secondary mb-1">Session</h6>
                                <h4 class="text-primary fw-bold" id="sessionDisplay">2024-2025</h4>
                            </div>
                            <div class="text-center mx-4">
                                <h6 class="text-secondary mb-1">Status</h6>
                                <span class="badge bg-success px-3 py-2 fw-bold" id="systemStatus">Online</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="container">
        <!-- Student Search Section -->
        <div class="student-search-card no-print">
            <h3 class="search-title">
                <i class="fas fa-search"></i> Search Student Record
            </h3>
            <div class="row">
                <div class="col-md-8">
                    <div class="search-box">
                        <i class="fas fa-user-graduate search-icon"></i>
                        <input type="text" class="form-control search-input" 
                               id="studentSearch" 
                               placeholder="Enter Admission Number or Roll Number">
                    </div>
                </div>
                <div class="col-md-4">
                    <button class="btn search-btn w-100" id="searchBtn">
                        <i class="fas fa-search"></i> Search Student
                    </button>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="searchType" id="searchAdmission" value="admission" checked>
                        <label class="form-check-label fw-bold" for="searchAdmission">
                            <i class="fas fa-id-card me-2"></i> Admission Number
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="searchType" id="searchRoll" value="roll">
                        <label class="form-check-label fw-bold" for="searchRoll">
                            <i class="fas fa-hashtag me-2"></i> Roll Number
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Student Info Preview -->
            <div class="student-info-preview" id="studentPreview">
                <div class="d-flex align-items-center">
                    <div class="student-avatar" id="studentAvatar">
                        RS
                    </div>
                    <div>
                        <h3 class="fw-bold" id="previewName">Rahul Sharma</h3>
                        <div class="d-flex flex-wrap gap-3 mt-2">
                            <span class="badge bg-primary px-3 py-2">
                                <i class="fas fa-id-card me-2"></i>
                                <strong id="previewAdmission">2024-1015</strong>
                            </span>
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-graduation-cap me-2"></i>
                                Class: <strong id="previewClass">10-B</strong>
                            </span>
                            <span class="badge bg-warning px-3 py-2">
                                <i class="fas fa-circle me-2"></i>
                                Status: <strong id="previewStatus">Active</strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button class="btn btn-primary-custom btn-custom" id="loadFullRecord">
                        <i class="fas fa-file-alt me-2"></i> Load Full Achievement Record
                    </button>
                    <button class="btn btn-outline-secondary ms-3 px-4 py-3 rounded-pill" id="clearSearch">
                        <i class="fas fa-times me-2"></i> Clear Search
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loadingOverlay">
            <div class="loading-spinner"></div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons no-print" id="actionButtons" style="display: none;">
            <button class="btn btn-primary-custom btn-custom" id="fillSampleBtn">
                <i class="fas fa-magic me-2"></i> Generate Sample Marks
            </button>
            <button class="btn btn-success-custom btn-custom" id="calculateBtn">
                <i class="fas fa-calculator me-2"></i> Calculate All Grades
            </button>
            <button class="btn btn-warning-custom btn-custom" onclick="window.print()">
                <i class="fas fa-print me-2"></i> Print Record
            </button>
            <button class="btn btn-info-custom btn-custom" id="generatePDFBtn">
                <i class="fas fa-file-pdf me-2"></i> Generate Report Card PDF
            </button>
            <button class="btn btn-purple-custom btn-custom" id="saveRecordBtn">
                <i class="fas fa-save me-2"></i> Save Record
            </button>
            <button class="btn btn-danger-custom btn-custom" id="resetBtn">
                <i class="fas fa-redo me-2"></i> Clear Form
            </button>
        </div>

        <!-- Report Card Preview -->
        <div class="report-card-preview" id="reportCardPreview">
            <!-- This will be populated by JavaScript for PDF generation -->
        </div>

        <!-- Student Profile Card -->
        <div class="dashboard-card" id="studentProfileCard">
            <div class="card-header-custom">
                <i class="fas fa-user-graduate"></i> STUDENT PROFILE
            </div>
            <div class="card-body-custom">
                <!-- Personal Information Section -->
                <h5 class="section-title mb-4">
                    <i class="fas fa-id-card text-primary me-2"></i>
                    Personal Information
                </h5>
                
                <div class="parallel-form-group">
                    <div class="form-group-enhanced">
                        <label for="studentName"><i class="fas fa-user me-2"></i>Full Name of Student</label>
                        <div class="input-with-icon">
                            <input type="text" class="form-control-enhanced" id="studentName" readonly>
                            <span class="input-icon"><i class="fas fa-user-check"></i></span>
                        </div>
                    </div>
                    
                    <div class="form-group-enhanced">
                        <label for="session"><i class="fas fa-calendar-alt me-2"></i>Academic Session</label>
                        <div class="input-with-icon">
                            <input type="text" class="form-control-enhanced" id="session" value="2024-2025" readonly>
                            <span class="input-icon"><i class="fas fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
                
                <div class="parallel-form-group">
                    <div class="form-group-enhanced">
                        <label for="studentClass"><i class="fas fa-graduation-cap me-2"></i>Class</label>
                        <div class="input-with-icon">
                            <input type="text" class="form-control-enhanced" id="studentClass" readonly>
                            <span class="input-icon"><i class="fas fa-school"></i></span>
                        </div>
                    </div>
                    
                    <div class="form-group-enhanced">
                        <label for="studentSection"><i class="fas fa-chalkboard me-2"></i>Section</label>
                        <div class="input-with-icon">
                            <input type="text" class="form-control-enhanced" id="studentSection" readonly>
                            <span class="input-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                        </div>
                    </div>
                </div>
                
                <div class="parallel-form-group">
                    <div class="form-group-enhanced">
                        <label for="dob"><i class="fas fa-birthday-cake me-2"></i>Date of Birth</label>
                        <div class="input-with-icon">
                            <input type="date" class="form-control-enhanced" id="dob" readonly>
                            <span class="input-icon"><i class="fas fa-calendar-day"></i></span>
                        </div>
                    </div>
                    
                    <div class="form-group-enhanced">
                        <label for="gender"><i class="fas fa-venus-mars me-2"></i>Gender</label>
                        <div class="input-with-icon">
                            <input type="text" class="form-control-enhanced" id="gender" readonly>
                            <span class="input-icon"><i class="fas fa-user-tag"></i></span>
                        </div>
                    </div>
                </div>
                
                <div class="parallel-form-group">
                    <div class="form-group-enhanced">
                        <label for="adminNo"><i class="fas fa-id-card-alt me-2"></i>Admission Number</label>
                        <div class="input-with-icon">
                            <input type="text" class="form-control-enhanced" id="adminNo" readonly>
                            <span class="input-icon"><i class="fas fa-fingerprint"></i></span>
                        </div>
                    </div>
                    
                    <div class="form-group-enhanced">
                        <label for="rollNo"><i class="fas fa-hashtag me-2"></i>Roll Number</label>
                        <div class="input-with-icon">
                            <input type="text" class="form-control-enhanced" id="rollNo" readonly>
                            <span class="input-icon"><i class="fas fa-list-ol"></i></span>
                        </div>
                    </div>
                </div>
                
                <!-- Parent Information -->
                <h5 class="section-title mt-5 mb-4">
                    <i class="fas fa-users text-success me-2"></i>
                    Parent Information
                </h5>
                
                <div class="parallel-form-group">
                    <div class="form-group-enhanced">
                        <label for="fatherName"><i class="fas fa-user-father me-2"></i>Father's Name</label>
                        <div class="input-with-icon">
                            <input type="text" class="form-control-enhanced" id="fatherName" readonly>
                            <span class="input-icon"><i class="fas fa-male"></i></span>
                        </div>
                    </div>
                    
                    <div class="form-group-enhanced">
                        <label for="motherName"><i class="fas fa-user-mother me-2"></i>Mother's Name</label>
                        <div class="input-with-icon">
                            <input type="text" class="form-control-enhanced" id="motherName" readonly>
                            <span class="input-icon"><i class="fas fa-female"></i></span>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <h5 class="section-title mt-5 mb-4">
                    <i class="fas fa-address-book text-warning me-2"></i>
                    Contact Information
                </h5>
                
                <div class="parallel-form-group">
                    <div class="form-group-enhanced">
                        <label for="phone"><i class="fas fa-phone me-2"></i>Contact Number</label>
                        <div class="input-with-icon">
                            <input type="text" class="form-control-enhanced" id="phone" readonly>
                            <span class="input-icon"><i class="fas fa-mobile-alt"></i></span>
                        </div>
                    </div>
                    
                    <div class="form-group-enhanced">
                        <label for="email"><i class="fas fa-envelope me-2"></i>Email Address</label>
                        <div class="input-with-icon">
                            <input type="email" class="form-control-enhanced" id="email" readonly>
                            <span class="input-icon"><i class="fas fa-at"></i></span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group-enhanced mt-3">
                    <label for="address"><i class="fas fa-home me-2"></i>Residential Address</label>
                    <textarea class="form-control-enhanced" id="address" rows="3" readonly style="resize: none;"></textarea>
                </div>
                
                <!-- Additional Information -->
                <h5 class="section-title mt-5 mb-4">
                    <i class="fas fa-info-circle text-danger me-2"></i>
                    Additional Information
                </h5>
                
                <div class="parallel-form-group">
                    <div class="form-group-enhanced">
                        <label for="boardRegNo"><i class="fas fa-file-certificate me-2"></i>Board Registration No.</label>
                        <div class="input-with-icon">
                            <input type="text" class="form-control-enhanced" id="boardRegNo" readonly>
                            <span class="input-icon"><i class="fas fa-certificate"></i></span>
                        </div>
                    </div>
                    
                    <div class="form-group-enhanced">
                        <label for="bloodGroup"><i class="fas fa-tint me-2"></i>Blood Group</label>
                        <div class="input-with-icon">
                            <input type="text" class="form-control-enhanced" id="bloodGroup" readonly>
                            <span class="input-icon"><i class="fas fa-heartbeat"></i></span>
                        </div>
                    </div>
                </div>
                
                <!-- Health and Attendance -->
                <div class="row mt-5">
                    <div class="col-md-6">
                        <div class="health-card">
                            <h5><i class="fas fa-heartbeat text-danger me-2"></i>Health Status</h5>
                            <div class="parallel-form-group">
                                <div class="form-group-enhanced">
                                    <label for="heightTerm1"><i class="fas fa-arrows-alt-v me-2"></i>Height - TERM I (cm)</label>
                                    <input type="number" class="form-control-enhanced" id="heightTerm1" min="100" max="200" placeholder="Enter height">
                                </div>
                                
                                <div class="form-group-enhanced">
                                    <label for="heightTerm2"><i class="fas fa-arrows-alt-v me-2"></i>Height - TERM II (cm)</label>
                                    <input type="number" class="form-control-enhanced" id="heightTerm2" min="100" max="200" placeholder="Enter height">
                                </div>
                            </div>
                            
                            <div class="parallel-form-group">
                                <div class="form-group-enhanced">
                                    <label for="weightTerm1"><i class="fas fa-weight me-2"></i>Weight - TERM I (kg)</label>
                                    <input type="number" class="form-control-enhanced" id="weightTerm1" min="20" max="100" placeholder="Enter weight">
                                </div>
                                
                                <div class="form-group-enhanced">
                                    <label for="weightTerm2"><i class="fas fa-weight me-2"></i>Weight - TERM II (kg)</label>
                                    <input type="number" class="form-control-enhanced" id="weightTerm2" min="20" max="100" placeholder="Enter weight">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="attendance-card">
                            <h5><i class="fas fa-calendar-check text-warning me-2"></i>Attendance Record</h5>
                            <div class="parallel-form-group">
                                <div class="form-group-enhanced">
                                    <label for="attendanceTerm1"><i class="fas fa-user-check me-2"></i>Attendance - TERM I</label>
                                    <input type="number" class="form-control-enhanced" id="attendanceTerm1" min="0" max="200" placeholder="Days present">
                                </div>
                                
                                <div class="form-group-enhanced">
                                    <label for="workingDaysTerm1"><i class="fas fa-calendar-day me-2"></i>Working Days - TERM I</label>
                                    <input type="number" class="form-control-enhanced" id="workingDaysTerm1" min="0" max="200" placeholder="Total days">
                                </div>
                            </div>
                            
                            <div class="parallel-form-group">
                                <div class="form-group-enhanced">
                                    <label for="attendanceTerm2"><i class="fas fa-user-check me-2"></i>Attendance - TERM II</label>
                                    <input type="number" class="form-control-enhanced" id="attendanceTerm2" min="0" max="200" placeholder="Days present">
                                </div>
                                
                                <div class="form-group-enhanced">
                                    <label for="workingDaysTerm2"><i class="fas fa-calendar-day me-2"></i>Working Days - TERM II</label>
                                    <input type="number" class="form-control-enhanced" id="workingDaysTerm2" min="0" max="200" placeholder="Total days">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Performance Card -->
        <div class="dashboard-card" id="academicPerformanceCard">
            <div class="card-header-custom">
                <i class="fas fa-book-open"></i> PART I: ACADEMIC PERFORMANCE
            </div>
            <div class="card-body-custom">
                <!-- Tab Navigation -->
                <div class="academic-tabs no-print">
                    <ul class="nav nav-tabs-custom" id="academicTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="term1-tab" data-bs-toggle="tab" data-bs-target="#term1" type="button" role="tab">
                                <i class="fas fa-calendar-alt me-2"></i> TERM I
                                <span class="tab-badge" id="term1-badge">0%</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="term2-tab" data-bs-toggle="tab" data-bs-target="#term2" type="button" role="tab">
                                <i class="fas fa-calendar-check me-2"></i> TERM II
                                <span class="tab-badge" id="term2-badge">0%</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="overall-tab" data-bs-toggle="tab" data-bs-target="#overall" type="button" role="tab">
                                <i class="fas fa-chart-line me-2"></i> OVERALL
                                <span class="tab-badge" id="overall-badge">0%</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="performance-tab" data-bs-toggle="tab" data-bs-target="#performance" type="button" role="tab">
                                <i class="fas fa-chart-bar me-2"></i> PERFORMANCE
                                <span class="tab-badge" id="performance-badge">A2</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="tab-content-custom" id="academicTabContent">
                    <!-- Term I Tab -->
                    <div class="tab-pane-custom fade show active" id="term1" role="tabpanel">
                        <div class="term-performance-card">
                            <div class="term-header">
                                <h3 class="term-title">
                                    <i class="fas fa-calendar-alt text-primary"></i>
                                    TERM I MARKS
                                </h3>
                                <div class="term-average">
                                    Average: <span id="term1-average">0%</span>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-custom">
                                    <thead>
                                        <tr>
                                            <th>SUBJECT</th>
                                            <th>FA 1<br><small>(0-20)</small></th>
                                            <th>FA 2<br><small>(0-20)</small></th>
                                            <th>SA 1<br><small>(0-60)</small></th>
                                            <th>TOTAL<br><small>(0-100)</small></th>
                                            <th>PERCENTAGE</th>
                                            <th>GRADE</th>
                                        </tr>
                                    </thead>
                                    <tbody id="term1Table">
                                        <!-- Term I rows will be populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="performance-summary">
                                <div class="summary-card">
                                    <div class="summary-value" id="term1-total">0</div>
                                    <div class="summary-label">Total Marks</div>
                                </div>
                                <div class="summary-card">
                                    <div class="summary-value" id="term1-percentage">0%</div>
                                    <div class="summary-label">Average %</div>
                                </div>
                                <div class="summary-card">
                                    <div class="summary-value" id="term1-grade">-</div>
                                    <div class="summary-label">Average Grade</div>
                                </div>
                                <div class="summary-card">
                                    <div class="summary-value" id="term1-subjects">0</div>
                                    <div class="summary-label">Subjects</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Term II Tab -->
                    <div class="tab-pane-custom fade" id="term2" role="tabpanel">
                        <div class="term-performance-card">
                            <div class="term-header">
                                <h3 class="term-title">
                                    <i class="fas fa-calendar-check text-success"></i>
                                    TERM II MARKS
                                </h3>
                                <div class="term-average">
                                    Average: <span id="term2-average">0%</span>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-custom">
                                    <thead>
                                        <tr>
                                            <th>SUBJECT</th>
                                            <th>FA 3<br><small>(0-20)</small></th>
                                            <th>FA 4<br><small>(0-20)</small></th>
                                            <th>SA 2<br><small>(0-60)</small></th>
                                            <th>TOTAL<br><small>(0-100)</small></th>
                                            <th>PERCENTAGE</th>
                                            <th>GRADE</th>
                                        </tr>
                                    </thead>
                                    <tbody id="term2Table">
                                        <!-- Term II rows will be populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="performance-summary">
                                <div class="summary-card">
                                    <div class="summary-value" id="term2-total">0</div>
                                    <div class="summary-label">Total Marks</div>
                                </div>
                                <div class="summary-card">
                                    <div class="summary-value" id="term2-percentage">0%</div>
                                    <div class="summary-label">Average %</div>
                                </div>
                                <div class="summary-card">
                                    <div class="summary-value" id="term2-grade">-</div>
                                    <div class="summary-label">Average Grade</div>
                                </div>
                                <div class="summary-card">
                                    <div class="summary-value" id="term2-subjects">0</div>
                                    <div class="summary-label">Subjects</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Overall Tab -->
                    <div class="tab-pane-custom fade" id="overall" role="tabpanel">
                        <div class="term-performance-card">
                            <div class="term-header">
                                <h3 class="term-title">
                                    <i class="fas fa-chart-line text-warning"></i>
                                    OVERALL PERFORMANCE
                                </h3>
                                <div class="term-average">
                                    Overall: <span id="overall-average">0%</span>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-custom">
                                    <thead>
                                        <tr>
                                            <th>SUBJECT</th>
                                            <th>TERM I</th>
                                            <th>TERM II</th>
                                            <th>TOTAL<br><small>(0-200)</small></th>
                                            <th>PERCENTAGE</th>
                                            <th>GRADE</th>
                                            <th>STATUS</th>
                                        </tr>
                                    </thead>
                                    <tbody id="overallTable">
                                        <!-- Overall rows will be populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="performance-summary">
                                <div class="summary-card">
                                    <div class="summary-value" id="overall-total">0</div>
                                    <div class="summary-label">Total Marks</div>
                                </div>
                                <div class="summary-card">
                                    <div class="summary-value" id="overall-percentage">0%</div>
                                    <div class="summary-label">Average %</div>
                                </div>
                                <div class="summary-card">
                                    <div class="summary-value" id="overall-grade">-</div>
                                    <div class="summary-label">Overall Grade</div>
                                </div>
                                <div class="summary-card">
                                    <div class="summary-value" id="overall-passed">0/0</div>
                                    <div class="summary-label">Subjects Passed</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Analysis Tab -->
                    <div class="tab-pane-custom fade" id="performance" role="tabpanel">
                        <div class="term-performance-card">
                            <div class="term-header">
                                <h3 class="term-title">
                                    <i class="fas fa-chart-bar text-danger"></i>
                                    PERFORMANCE ANALYSIS
                                </h3>
                                <div class="term-average">
                                    Performance: <span id="performance-rating">Good</span>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Subject-wise Performance Comparison</h5>
                                    <div class="table-responsive">
                                        <table class="performance-table">
                                            <thead>
                                                <tr>
                                                    <th>Subject</th>
                                                    <th>Term I %</th>
                                                    <th>Term II %</th>
                                                    <th>Improvement</th>
                                                </tr>
                                            </thead>
                                            <tbody id="performanceAnalysis">
                                                <!-- Performance analysis will be populated by JavaScript -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h5 class="mb-3">Grade Distribution</h5>
                                    <div class="table-responsive">
                                        <table class="performance-table">
                                            <thead>
                                                <tr>
                                                    <th>Grade</th>
                                                    <th>Count</th>
                                                    <th>Percentage</th>
                                                </tr>
                                            </thead>
                                            <tbody id="gradeDistribution">
                                                <!-- Grade distribution will be populated by JavaScript -->
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-4 p-3 bg-light rounded">
                                        <h6><i class="fas fa-lightbulb me-2 text-warning"></i>Recommendations</h6>
                                        <ul class="mb-0" id="recommendations">
                                            <li>No data available yet. Please enter marks to see recommendations.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="performance-summary mt-4">
                                <div class="summary-card">
                                    <div class="summary-value" id="best-subject">-</div>
                                    <div class="summary-label">Best Subject</div>
                                </div>
                                <div class="summary-card">
                                    <div class="summary-value" id="weak-subject">-</div>
                                    <div class="summary-label">Weak Subject</div>
                                </div>
                                <div class="summary-card">
                                    <div class="summary-value" id="improvement-rate">0%</div>
                                    <div class="summary-label">Improvement Rate</div>
                                </div>
                                <div class="summary-card">
                                    <div class="summary-value" id="attendance-rate">0%</div>
                                    <div class="summary-label">Attendance</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Co-Scholastic Areas -->
                <div class="mt-5">
                    <h5 class="section-title mb-4">
                        <i class="fas fa-star text-warning me-2"></i>
                        Co-Scholastic Areas
                    </h5>
                    
                    <div class="co-scholastic-grid">
                        <div class="co-scholastic-card">
                            <label class="form-label fw-bold">
                                <i class="fas fa-brain text-primary me-2"></i>General Knowledge
                            </label>
                            <div class="parallel-form-group">
                                <div class="form-group-enhanced">
                                    <label for="gkTerm1" class="small">Term I</label>
                                    <input type="text" class="form-control-enhanced" id="gkTerm1" placeholder="Descriptive indicator">
                                </div>
                                <div class="form-group-enhanced">
                                    <label for="gkTerm2" class="small">Term II</label>
                                    <input type="text" class="form-control-enhanced" id="gkTerm2" placeholder="Descriptive indicator">
                                </div>
                            </div>
                        </div>
                        
                        <div class="co-scholastic-card">
                            <label class="form-label fw-bold">
                                <i class="fas fa-palette text-success me-2"></i>Art Education
                            </label>
                            <div class="parallel-form-group">
                                <div class="form-group-enhanced">
                                    <label for="artTerm1" class="small">Term I</label>
                                    <input type="text" class="form-control-enhanced" id="artTerm1" placeholder="Descriptive indicator">
                                </div>
                                <div class="form-group-enhanced">
                                    <label for="artTerm2" class="small">Term II</label>
                                    <input type="text" class="form-control-enhanced" id="artTerm2" placeholder="Descriptive indicator">
                                </div>
                            </div>
                        </div>
                        
                        <div class="co-scholastic-card">
                            <label class="form-label fw-bold">
                                <i class="fas fa-tools text-warning me-2"></i>Work Experience
                            </label>
                            <div class="parallel-form-group">
                                <div class="form-group-enhanced">
                                    <label for="workTerm1" class="small">Term I</label>
                                    <input type="text" class="form-control-enhanced" id="workTerm1" placeholder="Descriptive indicator">
                                </div>
                                <div class="form-group-enhanced">
                                    <label for="workTerm2" class="small">Term II</label>
                                    <input type="text" class="form-control-enhanced" id="workTerm2" placeholder="Descriptive indicator">
                                </div>
                            </div>
                        </div>
                        
                        <div class="co-scholastic-card">
                            <label class="form-label fw-bold">
                                <i class="fas fa-running text-danger me-2"></i>Physical & Health Education
                            </label>
                            <div class="parallel-form-group">
                                <div class="form-group-enhanced">
                                    <label for="peTerm1" class="small">Term I</label>
                                    <input type="text" class="form-control-enhanced" id="peTerm1" placeholder="Descriptive indicator">
                                </div>
                                <div class="form-group-enhanced">
                                    <label for="peTerm2" class="small">Term II</label>
                                    <input type="text" class="form-control-enhanced" id="peTerm2" placeholder="Descriptive indicator">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="text-center mt-5 mb-4 text-muted">
            <p class="fw-bold">Official Achievement Record System - ANEE'S SCHOOL</p>
            <p>Generated on: <span id="generatedDate" class="fw-bold"></span></p>
        </footer>
    </main>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Simulated Student Database
        const studentDatabase = [
            {
                id: 1,
                admissionNo: "2024-1015",
                rollNo: "1015",
                name: "Rahul Sharma",
                class: "10",
                section: "B",
                dob: "2010-05-15",
                gender: "Male",
                motherName: "Priya Sharma",
                fatherName: "Amit Sharma",
                address: "123 Green Avenue, Kharar, Punjab",
                phone: "9876543210",
                email: "rahul.sharma@example.com",
                boardRegNo: "CBSE/2024/1015",
                bloodGroup: "O+",
                status: "Active",
                session: "2024-2025",
                avatarInitials: "RS"
            },
            {
                id: 2,
                admissionNo: "2024-1016",
                rollNo: "1016",
                name: "Priya Patel",
                class: "10",
                section: "A",
                dob: "2010-08-22",
                gender: "Female",
                motherName: "Sunita Patel",
                fatherName: "Rajesh Patel",
                address: "456 Rose Garden, Mohali",
                phone: "9876543211",
                email: "priya.patel@example.com",
                boardRegNo: "CBSE/2024/1016",
                bloodGroup: "A+",
                status: "Active",
                session: "2024-2025",
                avatarInitials: "PP"
            },
            {
                id: 3,
                admissionNo: "2024-1017",
                rollNo: "1017",
                name: "Amit Kumar",
                class: "9",
                section: "C",
                dob: "2011-03-10",
                gender: "Male",
                motherName: "Rita Kumar",
                fatherName: "Sanjay Kumar",
                address: "789 Sunshine Road, Chandigarh",
                phone: "9876543212",
                email: "amit.kumar@example.com",
                boardRegNo: "CBSE/2024/1017",
                bloodGroup: "B+",
                status: "Active",
                session: "2024-2025",
                avatarInitials: "AK"
            }
        ];

        // Academic subjects data
        const subjects = [
            { name: 'English', code: 'eng', color: '#4361ee' },
            { name: 'Hindi', code: 'hin', color: '#3a0ca3' },
            { name: 'Mathematics', code: 'math', color: '#4bb543' },
            { name: 'Science', code: 'sci', color: '#ff9e00' },
            { name: 'Social Studies', code: 'sst', color: '#e63946' },
            { name: 'Computer Science', code: 'comp', color: '#6f42c1' }
        ];

        // Current selected student
        let currentStudent = null;

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const formattedDate = today.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            document.getElementById('generatedDate').textContent = formattedDate;
            
            // Initialize all tables
            initializeTermTables();
            initializePerformanceTab();
            
            // Set up event listeners
            document.getElementById('searchBtn').addEventListener('click', searchStudent);
            document.getElementById('studentSearch').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchStudent();
                }
            });
            document.getElementById('loadFullRecord').addEventListener('click', loadFullRecord);
            document.getElementById('clearSearch').addEventListener('click', clearSearch);
            document.getElementById('fillSampleBtn').addEventListener('click', fillSampleMarks);
            document.getElementById('calculateBtn').addEventListener('click', calculateAllGrades);
            document.getElementById('resetBtn').addEventListener('click', resetForm);
            document.getElementById('saveRecordBtn').addEventListener('click', saveRecord);
            document.getElementById('generatePDFBtn').addEventListener('click', generateReportCardPDF);
            
            // Initialize Bootstrap tabs
            const tabTriggerList = [].slice.call(document.querySelectorAll('#academicTab button[data-bs-toggle="tab"]'));
            tabTriggerList.forEach(function (tabTriggerEl) {
                tabTriggerEl.addEventListener('click', function (event) {
                    event.preventDefault();
                    const tab = new bootstrap.Tab(tabTriggerEl);
                    tab.show();
                    if (tabTriggerEl.id === 'performance-tab') {
                        updatePerformanceAnalysis();
                    }
                });
            });
            
            // Show sample student for demo
            setTimeout(() => {
                document.getElementById('studentSearch').value = '2024-1015';
                searchStudent();
            }, 1000);
        });
        
        // Initialize Term Tables
        function initializeTermTables() {
            // Term I Table
            const term1Body = document.getElementById('term1Table');
            term1Body.innerHTML = '';
            
            // Term II Table
            const term2Body = document.getElementById('term2Table');
            term2Body.innerHTML = '';
            
            // Overall Table
            const overallBody = document.getElementById('overallTable');
            overallBody.innerHTML = '';
            
            subjects.forEach(subject => {
                // Term I Row
                const term1Row = document.createElement('tr');
                term1Row.id = `term1-${subject.code}`;
                term1Row.innerHTML = `
                    <td><strong style="color:${subject.color}">${subject.name}</strong></td>
                    <td>
                        <input type="number" class="form-control mark-input term1-fa1" 
                               min="0" max="20" value="0" 
                               data-subject="${subject.code}" data-type="fa1" data-term="1">
                    </td>
                    <td>
                        <input type="number" class="form-control mark-input term1-fa2" 
                               min="0" max="20" value="0" 
                               data-subject="${subject.code}" data-type="fa2" data-term="1">
                    </td>
                    <td>
                        <input type="number" class="form-control mark-input term1-sa1" 
                               min="0" max="60" value="0" 
                               data-subject="${subject.code}" data-type="sa1" data-term="1">
                    </td>
                    <td>
                        <span class="term1-total total-marks">0</span>
                    </td>
                    <td>
                        <span class="term1-percentage">0%</span>
                    </td>
                    <td>
                        <span class="term1-grade">-</span>
                    </td>
                `;
                term1Body.appendChild(term1Row);
                
                // Term II Row
                const term2Row = document.createElement('tr');
                term2Row.id = `term2-${subject.code}`;
                term2Row.innerHTML = `
                    <td><strong style="color:${subject.color}">${subject.name}</strong></td>
                    <td>
                        <input type="number" class="form-control mark-input term2-fa3" 
                               min="0" max="20" value="0" 
                               data-subject="${subject.code}" data-type="fa3" data-term="2">
                    </td>
                    <td>
                        <input type="number" class="form-control mark-input term2-fa4" 
                               min="0" max="20" value="0" 
                               data-subject="${subject.code}" data-type="fa4" data-term="2">
                    </td>
                    <td>
                        <input type="number" class="form-control mark-input term2-sa2" 
                               min="0" max="60" value="0" 
                               data-subject="${subject.code}" data-type="sa2" data-term="2">
                    </td>
                    <td>
                        <span class="term2-total total-marks">0</span>
                    </td>
                    <td>
                        <span class="term2-percentage">0%</span>
                    </td>
                    <td>
                        <span class="term2-grade">-</span>
                    </td>
                `;
                term2Body.appendChild(term2Row);
                
                // Overall Row
                const overallRow = document.createElement('tr');
                overallRow.id = `overall-${subject.code}`;
                overallRow.innerHTML = `
                    <td><strong style="color:${subject.color}">${subject.name}</strong></td>
                    <td><span class="overall-term1">0</span></td>
                    <td><span class="overall-term2">0</span></td>
                    <td><span class="overall-total-marks total-marks">0</span></td>
                    <td><span class="overall-percentage">0%</span></td>
                    <td><span class="overall-grade">-</span></td>
                    <td><span class="overall-status badge bg-success">Pass</span></td>
                `;
                overallBody.appendChild(overallRow);
                
                // Add event listeners for Term I inputs
                const term1Inputs = term1Row.querySelectorAll('.mark-input');
                term1Inputs.forEach(input => {
                    input.addEventListener('input', () => {
                        calculateTerm1SubjectGrades(subject.code);
                        updateTerm1Summary();
                        updateOverallPerformance();
                        updateTabBadges();
                        updatePerformanceAnalysis();
                    });
                });
                
                // Add event listeners for Term II inputs
                const term2Inputs = term2Row.querySelectorAll('.mark-input');
                term2Inputs.forEach(input => {
                    input.addEventListener('input', () => {
                        calculateTerm2SubjectGrades(subject.code);
                        updateTerm2Summary();
                        updateOverallPerformance();
                        updateTabBadges();
                        updatePerformanceAnalysis();
                    });
                });
            });
        }
        
        // Initialize Performance Tab
        function initializePerformanceTab() {
            // Initialize performance analysis table
            const performanceBody = document.getElementById('performanceAnalysis');
            performanceBody.innerHTML = '';
            
            subjects.forEach(subject => {
                const performanceRow = document.createElement('tr');
                performanceRow.innerHTML = `
                    <td>${subject.name}</td>
                    <td class="performance-term1">0%</td>
                    <td class="performance-term2">0%</td>
                    <td class="performance-improvement">0%</td>
                `;
                performanceBody.appendChild(performanceRow);
            });
            
            // Initialize grade distribution
            updateGradeDistribution();
        }
        
        // Calculate Term I grades for a subject
        function calculateTerm1SubjectGrades(subjectCode) {
            const row = document.getElementById(`term1-${subjectCode}`);
            if (!row) return;
            
            // Get values
            const fa1 = parseFloat(row.querySelector('.term1-fa1').value) || 0;
            const fa2 = parseFloat(row.querySelector('.term1-fa2').value) || 0;
            const sa1 = parseFloat(row.querySelector('.term1-sa1').value) || 0;
            
            // Validate
            const validatedFa1 = Math.min(Math.max(fa1, 0), 20);
            const validatedFa2 = Math.min(Math.max(fa2, 0), 20);
            const validatedSa1 = Math.min(Math.max(sa1, 0), 60);
            
            // Update inputs if validation changed values
            if (fa1 !== validatedFa1) row.querySelector('.term1-fa1').value = validatedFa1;
            if (fa2 !== validatedFa2) row.querySelector('.term1-fa2').value = validatedFa2;
            if (sa1 !== validatedSa1) row.querySelector('.term1-sa1').value = validatedSa1;
            
            // Calculate
            const total = validatedFa1 + validatedFa2 + validatedSa1;
            const percentage = Math.round((total / 100) * 100);
            const grade = calculateGrade(total);
            
            // Update display
            row.querySelector('.term1-total').textContent = total;
            row.querySelector('.term1-percentage').textContent = `${percentage}%`;
            row.querySelector('.term1-grade').textContent = grade;
            row.querySelector('.term1-grade').className = 'term1-grade ' + getGradeClass(grade);
            
            // Update overall table
            const overallRow = document.getElementById(`overall-${subjectCode}`);
            if (overallRow) {
                overallRow.querySelector('.overall-term1').textContent = total;
            }
        }
        
        // Calculate Term II grades for a subject
        function calculateTerm2SubjectGrades(subjectCode) {
            const row = document.getElementById(`term2-${subjectCode}`);
            if (!row) return;
            
            // Get values
            const fa3 = parseFloat(row.querySelector('.term2-fa3').value) || 0;
            const fa4 = parseFloat(row.querySelector('.term2-fa4').value) || 0;
            const sa2 = parseFloat(row.querySelector('.term2-sa2').value) || 0;
            
            // Validate
            const validatedFa3 = Math.min(Math.max(fa3, 0), 20);
            const validatedFa4 = Math.min(Math.max(fa4, 0), 20);
            const validatedSa2 = Math.min(Math.max(sa2, 0), 60);
            
            // Update inputs if validation changed values
            if (fa3 !== validatedFa3) row.querySelector('.term2-fa3').value = validatedFa3;
            if (fa4 !== validatedFa4) row.querySelector('.term2-fa4').value = validatedFa4;
            if (sa2 !== validatedSa2) row.querySelector('.term2-sa2').value = validatedSa2;
            
            // Calculate
            const total = validatedFa3 + validatedFa4 + validatedSa2;
            const percentage = Math.round((total / 100) * 100);
            const grade = calculateGrade(total);
            
            // Update display
            row.querySelector('.term2-total').textContent = total;
            row.querySelector('.term2-percentage').textContent = `${percentage}%`;
            row.querySelector('.term2-grade').textContent = grade;
            row.querySelector('.term2-grade').className = 'term2-grade ' + getGradeClass(grade);
            
            // Update overall table
            const overallRow = document.getElementById(`overall-${subjectCode}`);
            if (overallRow) {
                overallRow.querySelector('.overall-term2').textContent = total;
            }
        }
        
        // Update overall performance
        function updateOverallPerformance() {
            let totalTerm1 = 0;
            let totalTerm2 = 0;
            let overallTotal = 0;
            let passedSubjects = 0;
            
            subjects.forEach(subject => {
                const term1Row = document.getElementById(`term1-${subject.code}`);
                const term2Row = document.getElementById(`term2-${subject.code}`);
                const overallRow = document.getElementById(`overall-${subject.code}`);
                
                if (!term1Row || !term2Row || !overallRow) return;
                
                const term1Total = parseInt(term1Row.querySelector('.term1-total').textContent) || 0;
                const term2Total = parseInt(term2Row.querySelector('.term2-total').textContent) || 0;
                const subjectTotal = term1Total + term2Total;
                const percentage = Math.round((subjectTotal / 200) * 100);
                const grade = calculateGrade(subjectTotal);
                
                // Check if passed (33% minimum)
                const isPassed = percentage >= 33;
                
                // Update overall row
                overallRow.querySelector('.overall-total-marks').textContent = subjectTotal;
                overallRow.querySelector('.overall-percentage').textContent = `${percentage}%`;
                overallRow.querySelector('.overall-grade').textContent = grade;
                overallRow.querySelector('.overall-grade').className = 'overall-grade ' + getGradeClass(grade);
                
                // Update status
                const statusElement = overallRow.querySelector('.overall-status');
                statusElement.textContent = isPassed ? 'Pass' : 'Fail';
                statusElement.className = `overall-status badge ${isPassed ? 'bg-success' : 'bg-danger'}`;
                
                // Update totals
                totalTerm1 += term1Total;
                totalTerm2 += term2Total;
                overallTotal += subjectTotal;
                if (isPassed) passedSubjects++;
            });
            
            // Update overall summary
            const subjectCount = subjects.length;
            const averagePercentage = Math.round((overallTotal / (subjectCount * 200)) * 100);
            const averageGrade = calculateGrade(overallTotal / subjectCount);
            
            document.getElementById('overall-total').textContent = overallTotal;
            document.getElementById('overall-percentage').textContent = `${averagePercentage}%`;
            document.getElementById('overall-grade').textContent = averageGrade;
            document.getElementById('overall-passed').textContent = `${passedSubjects}/${subjectCount}`;
            document.getElementById('overall-average').textContent = `${averagePercentage}%`;
        }
        
        // Update Term I summary
        function updateTerm1Summary() {
            let totalMarks = 0;
            let subjectCount = 0;
            
            subjects.forEach(subject => {
                const row = document.getElementById(`term1-${subject.code}`);
                if (!row) return;
                
                const marks = parseInt(row.querySelector('.term1-total').textContent) || 0;
                if (marks > 0) {
                    totalMarks += marks;
                    subjectCount++;
                }
            });
            
            const averageMarks = subjectCount > 0 ? Math.round(totalMarks / subjectCount) : 0;
            const averagePercentage = Math.round((averageMarks / 100) * 100);
            const averageGrade = calculateGrade(averageMarks);
            
            document.getElementById('term1-total').textContent = totalMarks;
            document.getElementById('term1-percentage').textContent = `${averagePercentage}%`;
            document.getElementById('term1-grade').textContent = averageGrade;
            document.getElementById('term1-subjects').textContent = subjectCount;
            document.getElementById('term1-average').textContent = `${averagePercentage}%`;
        }
        
        // Update Term II summary
        function updateTerm2Summary() {
            let totalMarks = 0;
            let subjectCount = 0;
            
            subjects.forEach(subject => {
                const row = document.getElementById(`term2-${subject.code}`);
                if (!row) return;
                
                const marks = parseInt(row.querySelector('.term2-total').textContent) || 0;
                if (marks > 0) {
                    totalMarks += marks;
                    subjectCount++;
                }
            });
            
            const averageMarks = subjectCount > 0 ? Math.round(totalMarks / subjectCount) : 0;
            const averagePercentage = Math.round((averageMarks / 100) * 100);
            const averageGrade = calculateGrade(averageMarks);
            
            document.getElementById('term2-total').textContent = totalMarks;
            document.getElementById('term2-percentage').textContent = `${averagePercentage}%`;
            document.getElementById('term2-grade').textContent = averageGrade;
            document.getElementById('term2-subjects').textContent = subjectCount;
            document.getElementById('term2-average').textContent = `${averagePercentage}%`;
        }
        
        // Update performance analysis
        function updatePerformanceAnalysis() {
            let bestSubject = { name: '-', percentage: 0 };
            let weakSubject = { name: '-', percentage: 100 };
            let totalImprovement = 0;
            let subjectsWithData = 0;
            
            // Update performance analysis table
            const performanceRows = document.querySelectorAll('#performanceAnalysis tr');
            subjects.forEach((subject, index) => {
                const term1Row = document.getElementById(`term1-${subject.code}`);
                const term2Row = document.getElementById(`term2-${subject.code}`);
                
                if (!term1Row || !term2Row || !performanceRows[index]) return;
                
                const term1Total = parseInt(term1Row.querySelector('.term1-total').textContent) || 0;
                const term2Total = parseInt(term2Row.querySelector('.term2-total').textContent) || 0;
                const term1Percentage = Math.round((term1Total / 100) * 100);
                const term2Percentage = Math.round((term2Total / 100) * 100);
                const improvement = term2Percentage - term1Percentage;
                
                // Update performance row
                const performanceRow = performanceRows[index];
                performanceRow.querySelector('.performance-term1').textContent = `${term1Percentage}%`;
                performanceRow.querySelector('.performance-term2').textContent = `${term2Percentage}%`;
                
                const improvementElement = performanceRow.querySelector('.performance-improvement');
                improvementElement.textContent = `${improvement > 0 ? '+' : ''}${improvement}%`;
                
                // Color code improvement
                if (improvement > 0) {
                    improvementElement.className = 'performance-improvement improvement-positive';
                } else if (improvement < 0) {
                    improvementElement.className = 'performance-improvement improvement-negative';
                } else {
                    improvementElement.className = 'performance-improvement improvement-neutral';
                }
                
                // Find best and weak subjects
                const overallRow = document.getElementById(`overall-${subject.code}`);
                if (overallRow) {
                    const percentageText = overallRow.querySelector('.overall-percentage').textContent;
                    const percentage = parseInt(percentageText) || 0;
                    
                    if (percentage > bestSubject.percentage && percentage > 0) {
                        bestSubject = { name: subject.name, percentage: percentage };
                    }
                    if (percentage < weakSubject.percentage && percentage > 0) {
                        weakSubject = { name: subject.name, percentage: percentage };
                    }
                }
                
                if (term1Total > 0 || term2Total > 0) {
                    totalImprovement += improvement;
                    subjectsWithData++;
                }
            });
            
            // Update performance summary
            const avgImprovement = subjectsWithData > 0 ? Math.round(totalImprovement / subjectsWithData) : 0;
            
            document.getElementById('best-subject').textContent = bestSubject.name;
            document.getElementById('weak-subject').textContent = weakSubject.percentage < 100 ? weakSubject.name : '-';
            document.getElementById('improvement-rate').textContent = `${avgImprovement > 0 ? '+' : ''}${avgImprovement}%`;
            
            // Calculate attendance rate
            const attendance1 = parseInt(document.getElementById('attendanceTerm1').value) || 0;
            const workingDays1 = parseInt(document.getElementById('workingDaysTerm1').value) || 1;
            const attendance2 = parseInt(document.getElementById('attendanceTerm2').value) || 0;
            const workingDays2 = parseInt(document.getElementById('workingDaysTerm2').value) || 1;
            
            const attendanceRate1 = Math.min(100, Math.round((attendance1 / workingDays1) * 100));
            const attendanceRate2 = Math.min(100, Math.round((attendance2 / workingDays2) * 100));
            const avgAttendance = Math.round((attendanceRate1 + attendanceRate2) / 2);
            
            document.getElementById('attendance-rate').textContent = `${avgAttendance}%`;
            
            // Update performance rating
            const overallPercentage = parseInt(document.getElementById('overall-percentage').textContent) || 0;
            let performanceRating = 'Poor';
            if (overallPercentage >= 75) performanceRating = 'Excellent';
            else if (overallPercentage >= 60) performanceRating = 'Good';
            else if (overallPercentage >= 40) performanceRating = 'Average';
            
            document.getElementById('performance-rating').textContent = performanceRating;
            document.getElementById('performance-badge').textContent = calculateGrade(overallPercentage);
            
            // Update grade distribution
            updateGradeDistribution();
            
            // Update recommendations
            updateRecommendations(bestSubject, weakSubject, avgImprovement, avgAttendance);
        }
        
        // Update grade distribution
        function updateGradeDistribution() {
            const gradeDistribution = {
                'A1': 0, 'A2': 0, 'B1': 0, 'B2': 0,
                'C1': 0, 'C2': 0, 'D': 0, 'E': 0
            };
            
            subjects.forEach(subject => {
                const overallRow = document.getElementById(`overall-${subject.code}`);
                if (!overallRow) return;
                
                const grade = overallRow.querySelector('.overall-grade').textContent;
                if (grade && grade !== '-') {
                    gradeDistribution[grade] = (gradeDistribution[grade] || 0) + 1;
                }
            });
            
            const gradeBody = document.getElementById('gradeDistribution');
            gradeBody.innerHTML = '';
            
            let totalSubjects = 0;
            Object.keys(gradeDistribution).forEach(grade => {
                if (gradeDistribution[grade] > 0) {
                    totalSubjects += gradeDistribution[grade];
                }
            });
            
            Object.keys(gradeDistribution).forEach(grade => {
                const count = gradeDistribution[grade];
                if (count > 0) {
                    const percentage = Math.round((count / totalSubjects) * 100);
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td><span class="${getGradeClass(grade)}">${grade}</span></td>
                        <td>${count}</td>
                        <td>${percentage}%</td>
                    `;
                    gradeBody.appendChild(row);
                }
            });
            
            if (totalSubjects === 0) {
                gradeBody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">No grades available</td></tr>';
            }
        }
        
        // Update recommendations
        function updateRecommendations(bestSubject, weakSubject, improvementRate, attendanceRate) {
            const recommendationsList = document.getElementById('recommendations');
            recommendationsList.innerHTML = '';
            
            if (weakSubject.percentage < 40 && weakSubject.name !== '-') {
                const li = document.createElement('li');
                li.textContent = `Focus on improving ${weakSubject.name} as it's the weakest subject (${weakSubject.percentage}%).`;
                recommendationsList.appendChild(li);
            }
            
            if (improvementRate < 0) {
                const li = document.createElement('li');
                li.textContent = `Performance declined by ${Math.abs(improvementRate)}% from Term I to Term II. Need to work harder.`;
                recommendationsList.appendChild(li);
            } else if (improvementRate > 10) {
                const li = document.createElement('li');
                li.textContent = `Great improvement of ${improvementRate}% from Term I to Term II! Keep up the good work.`;
                recommendationsList.appendChild(li);
            }
            
            if (attendanceRate < 75) {
                const li = document.createElement('li');
                li.textContent = `Attendance rate is low (${attendanceRate}%). Regular attendance is important for academic success.`;
                recommendationsList.appendChild(li);
            }
            
            if (bestSubject.percentage >= 90) {
                const li = document.createElement('li');
                li.textContent = `Excellent performance in ${bestSubject.name} (${bestSubject.percentage}%). Consider participating in related competitions.`;
                recommendationsList.appendChild(li);
            }
            
            if (recommendationsList.children.length === 0) {
                const li = document.createElement('li');
                li.textContent = 'Performance is satisfactory. Continue with current study habits.';
                recommendationsList.appendChild(li);
            }
        }
        
        // Update tab badges
        function updateTabBadges() {
            // Term I badge
            const term1Avg = document.getElementById('term1-average').textContent;
            document.getElementById('term1-badge').textContent = term1Avg;
            
            // Term II badge
            const term2Avg = document.getElementById('term2-average').textContent;
            document.getElementById('term2-badge').textContent = term2Avg;
            
            // Overall badge
            const overallAvg = document.getElementById('overall-average').textContent;
            document.getElementById('overall-badge').textContent = overallAvg;
            
            // Performance badge
            const overallPercentage = parseInt(document.getElementById('overall-percentage').textContent) || 0;
            document.getElementById('performance-badge').textContent = calculateGrade(overallPercentage);
        }
        
        // Calculate grade based on marks
        function calculateGrade(marks) {
            if (marks >= 91) return 'A1';
            if (marks >= 81) return 'A2';
            if (marks >= 71) return 'B1';
            if (marks >= 61) return 'B2';
            if (marks >= 51) return 'C1';
            if (marks >= 41) return 'C2';
            if (marks >= 33) return 'D';
            if (marks >= 21) return 'E1';
            return 'E2';
        }
        
        // Get CSS class for grade badge
        function getGradeClass(grade) {
            switch(grade) {
                case 'A1': return 'grade-badge grade-a1';
                case 'A2': return 'grade-badge grade-a2';
                case 'B1': return 'grade-badge grade-b1';
                case 'B2': return 'grade-badge grade-b2';
                case 'C1': return 'grade-badge grade-c1';
                case 'C2': return 'grade-badge grade-c2';
                case 'D': return 'grade-badge grade-d';
                case 'E1':
                case 'E2': return 'grade-badge grade-e';
                default: return '';
            }
        }
        
        // Calculate all grades
        function calculateAllGrades() {
            subjects.forEach(subject => {
                calculateTerm1SubjectGrades(subject.code);
                calculateTerm2SubjectGrades(subject.code);
            });
            updateTerm1Summary();
            updateTerm2Summary();
            updateOverallPerformance();
            updatePerformanceAnalysis();
            updateTabBadges();
            showNotification('All grades have been calculated successfully!', 'success');
        }
        
        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
                <span>${message}</span>
            `;
            
            document.body.appendChild(notification);
            
            // Show notification
            setTimeout(() => notification.classList.add('show'), 10);
            
            // Remove notification after 5 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 500);
            }, 5000);
        }
        
        // Fill sample marks based on student
        function fillSampleMarks() {
            if (!currentStudent) {
                showNotification('Please load a student record first', 'warning');
                return;
            }
            
            // Generate marks based on student performance
            const studentPerformance = {
                'eng': {fa1: 18, fa2: 17, sa1: 52, fa3: 19, fa4: 18, sa2: 55},
                'hin': {fa1: 16, fa2: 15, sa1: 48, fa3: 17, fa4: 16, sa2: 50},
                'math': {fa1: 19, fa2: 20, sa1: 58, fa3: 20, fa4: 19, sa2: 59},
                'sci': {fa1: 17, fa2: 16, sa1: 50, fa3: 18, fa4: 17, sa2: 52},
                'sst': {fa1: 18, fa2: 17, sa1: 53, fa3: 19, fa4: 18, sa2: 54},
                'comp': {fa1: 20, fa2: 20, sa1: 59, fa3: 20, fa4: 20, sa2: 60}
            };
            
            // Adjust based on student's class
            const classFactor = parseInt(currentStudent.class);
            
            subjects.forEach(subject => {
                const data = studentPerformance[subject.code];
                const term1Row = document.getElementById(`term1-${subject.code}`);
                const term2Row = document.getElementById(`term2-${subject.code}`);
                
                if (!term1Row || !term2Row) return;
                
                // Slightly adjust marks based on class
                const adjustment = classFactor >= 10 ? 0 : 5;
                
                // Set Term I values
                term1Row.querySelector('.term1-fa1').value = Math.min(20, data.fa1 + adjustment);
                term1Row.querySelector('.term1-fa2').value = Math.min(20, data.fa2 + adjustment);
                term1Row.querySelector('.term1-sa1').value = Math.min(60, data.sa1 + (adjustment * 3));
                
                // Set Term II values
                term2Row.querySelector('.term2-fa3').value = Math.min(20, data.fa3 + adjustment);
                term2Row.querySelector('.term2-fa4').value = Math.min(20, data.fa4 + adjustment);
                term2Row.querySelector('.term2-sa2').value = Math.min(60, data.sa2 + (adjustment * 3));
                
                // Trigger calculations
                calculateTerm1SubjectGrades(subject.code);
                calculateTerm2SubjectGrades(subject.code);
            });
            
            // Fill other data
            fillOtherData();
            
            // Calculate all grades
            calculateAllGrades();
            
            showNotification(`Sample marks generated for ${currentStudent.name}`, 'info');
        }
        
        // Fill other data (co-scholastic, etc.)
        function fillOtherData() {
            // Co-scholastic descriptive indicators
            document.getElementById('gkTerm1').value = 'Shows keen interest in current affairs';
            document.getElementById('artTerm1').value = 'Excellent creative expression';
            document.getElementById('workTerm1').value = 'Diligent and completes tasks on time';
            document.getElementById('peTerm1').value = 'Active participation in sports';
            
            document.getElementById('gkTerm2').value = 'Good general awareness';
            document.getElementById('artTerm2').value = 'Creative and innovative ideas';
            document.getElementById('workTerm2').value = 'Shows good practical skills';
            document.getElementById('peTerm2').value = 'Regular participation in physical activities';
            
            // Health and attendance
            document.getElementById('heightTerm1').value = getRandomHeight(currentStudent.class);
            document.getElementById('heightTerm2').value = getRandomHeight(currentStudent.class) + 2;
            document.getElementById('weightTerm1').value = getRandomWeight(currentStudent.class);
            document.getElementById('weightTerm2').value = getRandomWeight(currentStudent.class) + 2;
            
            const attendance1 = Math.floor(Math.random() * 20) + 160;
            const attendance2 = Math.floor(Math.random() * 20) + 155;
            document.getElementById('attendanceTerm1').value = attendance1;
            document.getElementById('workingDaysTerm1').value = 180;
            document.getElementById('attendanceTerm2').value = attendance2;
            document.getElementById('workingDaysTerm2').value = 175;
        }
        
        // Search for student
        function searchStudent() {
            const searchInput = document.getElementById('studentSearch').value.trim();
            const searchType = document.querySelector('input[name="searchType"]:checked').value;
            
            if (!searchInput) {
                showNotification('Please enter admission number or roll number', 'warning');
                return;
            }
            
            document.getElementById('loadingOverlay').classList.add('show');
            
            setTimeout(() => {
                let foundStudent = null;
                
                if (searchType === 'admission') {
                    foundStudent = studentDatabase.find(student => 
                        student.admissionNo.toLowerCase() === searchInput.toLowerCase()
                    );
                } else {
                    foundStudent = studentDatabase.find(student => 
                        student.rollNo.toLowerCase() === searchInput.toLowerCase()
                    );
                }
                
                document.getElementById('loadingOverlay').classList.remove('show');
                
                if (foundStudent) {
                    currentStudent = foundStudent;
                    displayStudentPreview(foundStudent);
                    showNotification('Student record found!', 'success');
                } else {
                    showNotification('Student not found. Please check the number and try again.', 'error');
                    clearStudentPreview();
                }
            }, 800);
        }
        
        // Display student preview
        function displayStudentPreview(student) {
            document.getElementById('previewName').textContent = student.name;
            document.getElementById('previewAdmission').textContent = student.admissionNo;
            document.getElementById('previewClass').textContent = `${student.class}-${student.section}`;
            document.getElementById('previewStatus').textContent = student.status;
            document.getElementById('studentAvatar').textContent = student.avatarInitials;
            document.getElementById('studentPreview').classList.add('show');
        }
        
        // Clear student preview
        function clearStudentPreview() {
            document.getElementById('studentPreview').classList.remove('show');
            currentStudent = null;
        }
        
        // Clear search
        function clearSearch() {
            document.getElementById('studentSearch').value = '';
            clearStudentPreview();
            hideAllCards();
            document.getElementById('actionButtons').style.display = 'none';
            showNotification('Search cleared', 'info');
        }
        
        // Load full student record
        function loadFullRecord() {
            if (!currentStudent) {
                showNotification('No student selected', 'warning');
                return;
            }
            
            // Fill student details
            document.getElementById('studentName').value = currentStudent.name;
            document.getElementById('studentClass').value = currentStudent.class;
            document.getElementById('studentSection').value = currentStudent.section;
            document.getElementById('dob').value = currentStudent.dob;
            document.getElementById('adminNo').value = currentStudent.admissionNo;
            document.getElementById('rollNo').value = currentStudent.rollNo;
            document.getElementById('gender').value = currentStudent.gender;
            document.getElementById('motherName').value = currentStudent.motherName;
            document.getElementById('fatherName').value = currentStudent.fatherName;
            document.getElementById('address').value = currentStudent.address;
            document.getElementById('phone').value = currentStudent.phone;
            document.getElementById('email').value = currentStudent.email;
            document.getElementById('boardRegNo').value = currentStudent.boardRegNo;
            document.getElementById('bloodGroup').value = currentStudent.bloodGroup;
            
            // Show all cards
            showAllCards();
            
            // Show action buttons
            document.getElementById('actionButtons').style.display = 'flex';
            
            // Auto-generate sample marks for this student
            fillSampleMarks();
            
            showNotification(`Loaded record for ${currentStudent.name}`, 'success');
        }
        
        // Show all cards
        function showAllCards() {
            const cards = [
                'studentProfileCard',
                'academicPerformanceCard'
            ];
            
            cards.forEach(cardId => {
                document.getElementById(cardId).classList.add('show');
            });
        }
        
        // Hide all cards
        function hideAllCards() {
            const cards = [
                'studentProfileCard',
                'academicPerformanceCard'
            ];
            
            cards.forEach(cardId => {
                document.getElementById(cardId).classList.remove('show');
            });
        }
        
        // Reset form
        function resetForm() {
            if (confirm('Are you sure you want to clear all form data? This cannot be undone.')) {
                // Reset academic inputs
                subjects.forEach(subject => {
                    const term1Row = document.getElementById(`term1-${subject.code}`);
                    const term2Row = document.getElementById(`term2-${subject.code}`);
                    
                    if (term1Row) {
                        term1Row.querySelector('.term1-fa1').value = '';
                        term1Row.querySelector('.term1-fa2').value = '';
                        term1Row.querySelector('.term1-sa1').value = '';
                        calculateTerm1SubjectGrades(subject.code);
                    }
                    
                    if (term2Row) {
                        term2Row.querySelector('.term2-fa3').value = '';
                        term2Row.querySelector('.term2-fa4').value = '';
                        term2Row.querySelector('.term2-sa2').value = '';
                        calculateTerm2SubjectGrades(subject.code);
                    }
                });
                
                // Reset other inputs
                document.querySelectorAll('#academicPerformanceCard input[type="text"]').forEach(input => {
                    input.value = '';
                });
                
                // Reset health and attendance
                document.getElementById('heightTerm1').value = '';
                document.getElementById('heightTerm2').value = '';
                document.getElementById('weightTerm1').value = '';
                document.getElementById('weightTerm2').value = '';
                document.getElementById('attendanceTerm1').value = '';
                document.getElementById('workingDaysTerm1').value = '';
                document.getElementById('attendanceTerm2').value = '';
                document.getElementById('workingDaysTerm2').value = '';
                
                // Update summaries
                updateTerm1Summary();
                updateTerm2Summary();
                updateOverallPerformance();
                updatePerformanceAnalysis();
                updateTabBadges();
                
                showNotification('Form data cleared', 'warning');
            }
        }
        
        // Save record
        function saveRecord() {
            if (!currentStudent) {
                showNotification('No student record loaded', 'warning');
                return;
            }
            
            const recordData = {
                studentId: currentStudent.id,
                studentName: currentStudent.name,
                admissionNo: currentStudent.admissionNo,
                savedAt: new Date().toISOString()
            };
            
            localStorage.setItem(`achievementRecord_${currentStudent.admissionNo}`, JSON.stringify(recordData));
            
            showNotification(`Record saved for ${currentStudent.name} (Admission: ${currentStudent.admissionNo})`, 'success');
        }
        
        // Generate Report Card PDF
        async function generateReportCardPDF() {
            if (!currentStudent) {
                showNotification('Please load a student record first', 'warning');
                return;
            }
            
            document.getElementById('loadingOverlay').classList.add('show');
            
            try {
                showNotification('Generating report card PDF...', 'info');
                
                // Create report card HTML
                createReportCardHTML();
                
                // Show preview
                const previewElement = document.getElementById('reportCardPreview');
                previewElement.classList.add('show');
                
                // Add close button
                const closeBtn = document.createElement('button');
                closeBtn.className = 'pdf-close-btn';
                closeBtn.innerHTML = '<i class="fas fa-times"></i> Close Preview';
                closeBtn.onclick = function() {
                    previewElement.classList.remove('show');
                    this.remove();
                };
                document.body.appendChild(closeBtn);
                
                // Wait for HTML to render
                await new Promise(resolve => setTimeout(resolve, 500));
                
                // Generate PDF
                await generatePDFFromHTML();
                
            } catch (error) {
                console.error('Error generating PDF:', error);
                showNotification('Error generating PDF. Please try again.', 'error');
            } finally {
                document.getElementById('loadingOverlay').classList.remove('show');
            }
        }
        
        // Create Report Card HTML
        function createReportCardHTML() {
            const today = new Date();
            const formattedDate = today.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            
            // Calculate attendance percentages
            const attendanceTerm1 = parseInt(document.getElementById('attendanceTerm1').value) || 0;
            const workingDaysTerm1 = parseInt(document.getElementById('workingDaysTerm1').value) || 180;
            const attendanceTerm2 = parseInt(document.getElementById('attendanceTerm2').value) || 0;
            const workingDaysTerm2 = parseInt(document.getElementById('workingDaysTerm2').value) || 175;
            
            const attendancePercentage1 = Math.min(100, Math.round((attendanceTerm1 / workingDaysTerm1) * 100));
            const attendancePercentage2 = Math.min(100, Math.round((attendanceTerm2 / workingDaysTerm2) * 100));
            const overallAttendance = Math.round((attendancePercentage1 + attendancePercentage2) / 2);
            
            // Collect subject data
            let subjectsHTML = '';
            let overallTotal = 0;
            let passedSubjects = 0;
            const subjectCount = subjects.length;
            
            subjects.forEach(subject => {
                const term1Row = document.getElementById(`term1-${subject.code}`);
                const term2Row = document.getElementById(`term2-${subject.code}`);
                
                if (!term1Row || !term2Row) return;
                
                const term1Total = parseInt(term1Row.querySelector('.term1-total').textContent) || 0;
                const term1Percentage = Math.round((term1Total / 100) * 100);
                const term1Grade = term1Row.querySelector('.term1-grade').textContent || '-';
                const term2Total = parseInt(term2Row.querySelector('.term2-total').textContent) || 0;
                const term2Percentage = Math.round((term2Total / 100) * 100);
                const term2Grade = term2Row.querySelector('.term2-grade').textContent || '-';
                const subjectTotal = term1Total + term2Total;
                const subjectPercentage = Math.round((subjectTotal / 200) * 100);
                const subjectGrade = calculateGrade(subjectTotal);
                
                overallTotal += subjectTotal;
                if (subjectPercentage >= 33) passedSubjects++;
                
                subjectsHTML += `
                    <tr>
                        <td style="text-align: left; padding-left: 10px;">${subject.name}</td>
                        <td>${term1Total} (${term1Percentage}%)</td>
                        <td>${term1Grade}</td>
                        <td>${term2Total} (${term2Percentage}%)</td>
                        <td>${term2Grade}</td>
                        <td>${subjectTotal}</td>
                        <td>${subjectPercentage}%</td>
                        <td><strong>${subjectGrade}</strong></td>
                        <td style="color: ${subjectPercentage >= 33 ? '#27ae60' : '#e74c3c'};">
                            <strong>${subjectPercentage >= 33 ? 'PASS' : 'FAIL'}</strong>
                        </td>
                    </tr>
                `;
            });
            
            const overallPercentage = Math.round((overallTotal / (subjectCount * 200)) * 100);
            const overallGrade = calculateGrade(overallTotal / subjectCount);
            
            // Promotion status
            let promotionStatus = 'NOT PROMOTED';
            let promotionColor = '#e74c3c';
            let promotionBgColor = '#fadbd8';
            let promotionMessage = 'Student needs to improve performance';
            
            if (passedSubjects === subjectCount && overallPercentage >= 33) {
                promotionStatus = 'PROMOTED';
                promotionColor = '#27ae60';
                promotionBgColor = '#d5f4e6';
                promotionMessage = 'Congratulations! Student is promoted to next class';
            } else if (passedSubjects >= subjectCount * 0.75) {
                promotionStatus = 'CONDITIONALLY PROMOTED';
                promotionColor = '#f39c12';
                promotionBgColor = '#fef5e7';
                promotionMessage = 'Student needs to improve in some subjects';
            }
            
            // Get term averages
            const term1Avg = document.getElementById('term1-percentage').textContent;
            const term1Grade = document.getElementById('term1-grade').textContent;
            const term2Avg = document.getElementById('term2-percentage').textContent;
            const term2Grade = document.getElementById('term2-grade').textContent;
            
            const reportCardHTML = `
                <div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                    <!-- Header -->
                    <div style="text-align: center; border-bottom: 4px double #4361ee; padding-bottom: 20px; margin-bottom: 30px;">
                        <div style="position: relative;">
                            <div style="position: absolute; top: 0; left: 0; right: 0; font-size: 120px; opacity: 0.03; color: #4361ee; z-index: -1;">ANEE'S</div>
                            <h1 style="color: #2c3e50; font-size: 36px; font-weight: 800; margin-bottom: 10px;">ANEE'S SCHOOL</h1>
                            <h2 style="color: #34495e; font-size: 20px; font-weight: 600; margin-bottom: 8px;">
                                A Senior Secondary SMART School Affiliated to C.B.S.E. New Delhi
                            </h2>
                            <h3 style="color: #7f8c8d; font-size: 16px; font-weight: 400; margin-bottom: 5px;">
                                Kharar Campus, Shivjot Enclave, Kharar | Aff. No. – 1630565
                            </h3>
                        </div>
                        
                        <div style="background: linear-gradient(135deg, #4361ee, #3a0ca3); color: white; padding: 15px; border-radius: 10px; margin-top: 20px; display: inline-block;">
                            <div style="font-size: 22px; font-weight: 700;">ACADEMIC SESSION: 2024-2025</div>
                            <div style="font-size: 18px; font-weight: 600;">ANNUAL REPORT CARD</div>
                        </div>
                    </div>
                    
                    <!-- Student Information -->
                    <div style="background: linear-gradient(135deg, #f8f9ff, #ffffff); border-radius: 15px; padding: 25px; margin-bottom: 30px; border: 2px solid #e0e0e0;">
                        <h3 style="color: #2c3e50; border-bottom: 3px solid #4361ee; padding-bottom: 10px; margin-bottom: 20px; font-weight: 700;">
                            <i class="fas fa-user-graduate" style="margin-right: 10px;"></i>STUDENT INFORMATION
                        </h3>
                        
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #4361ee, #3a0ca3); border-radius: 15px; display: flex; align-items: center; justify-content: center; color: white; font-size: 36px; font-weight: 700;">
                                    ${currentStudent.avatarInitials}
                                </div>
                                <div>
                                    <div style="font-size: 24px; font-weight: 800; color: #2c3e50;">${currentStudent.name}</div>
                                    <div style="display: flex; gap: 15px; margin-top: 10px;">
                                        <span style="background: #4361ee; color: white; padding: 5px 15px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                                            Class: ${currentStudent.class}-${currentStudent.section}
                                        </span>
                                        <span style="background: #28a745; color: white; padding: 5px 15px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                                            Roll No: ${currentStudent.rollNo}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                    <div>
                                        <div style="font-weight: 600; color: #7f8c8d; font-size: 14px;">Admission No</div>
                                        <div style="font-weight: 700; color: #2c3e50; font-size: 16px;">${currentStudent.admissionNo}</div>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #7f8c8d; font-size: 14px;">Date of Birth</div>
                                        <div style="font-weight: 700; color: #2c3e50; font-size: 16px;">${new Date(currentStudent.dob).toLocaleDateString('en-GB')}</div>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #7f8c8d; font-size: 14px;">Father's Name</div>
                                        <div style="font-weight: 700; color: #2c3e50; font-size: 16px;">${currentStudent.fatherName}</div>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #7f8c8d; font-size: 14px;">Mother's Name</div>
                                        <div style="font-weight: 700; color: #2c3e50; font-size: 16px;">${currentStudent.motherName}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Academic Performance Summary -->
                    <div style="background: linear-gradient(135deg, #fff8f0, #ffffff); border-radius: 15px; padding: 25px; margin-bottom: 30px; border: 2px solid #ffd700;">
                        <h3 style="color: #ff6b00; border-bottom: 3px solid #ffd700; padding-bottom: 10px; margin-bottom: 20px; font-weight: 700;">
                            <i class="fas fa-chart-line" style="margin-right: 10px;"></i>ACADEMIC PERFORMANCE SUMMARY
                        </h3>
                        
                        <div style="text-align: center; color: #2c3e50;">
                            <div style="font-size: 18px; font-weight: 600; margin-bottom: 20px;">Overall Performance Analysis</div>
                            <div style="display: flex; justify-content: center; gap: 40px; flex-wrap: wrap;">
                                <div style="text-align: center;">
                                    <div style="font-size: 14px; color: #7f8c8d; margin-bottom: 5px;">Term I Average</div>
                                    <div style="font-size: 36px; font-weight: 800; color: #4361ee;">${term1Avg}</div>
                                    <div style="font-size: 18px; font-weight: 600; color: #4361ee;">${term1Grade}</div>
                                </div>
                                <div style="text-align: center;">
                                    <div style="font-size: 14px; color: #7f8c8d; margin-bottom: 5px;">Term II Average</div>
                                    <div style="font-size: 36px; font-weight: 800; color: #28a745;">${term2Avg}</div>
                                    <div style="font-size: 18px; font-weight: 600; color: #28a745;">${term2Grade}</div>
                                </div>
                                <div style="text-align: center;">
                                    <div style="font-size: 14px; color: #7f8c8d; margin-bottom: 5px;">Overall Average</div>
                                    <div style="font-size: 36px; font-weight: 800; color: #e63946;">${overallPercentage}%</div>
                                    <div style="font-size: 18px; font-weight: 600; color: #e63946;">${overallGrade}</div>
                                </div>
                                <div style="text-align: center;">
                                    <div style="font-size: 14px; color: #7f8c8d; margin-bottom: 5px;">Attendance</div>
                                    <div style="font-size: 36px; font-weight: 800; color: #ff9e00;">${overallAttendance}%</div>
                                    <div style="font-size: 18px; font-weight: 600; color: #ff9e00;">Good</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Subject-wise Performance -->
                    <div style="background: linear-gradient(135deg, #f8fff8, #ffffff); border-radius: 15px; padding: 25px; margin-bottom: 30px; border: 2px solid #28a745;">
                        <h3 style="color: #1e7e34; border-bottom: 3px solid #28a745; padding-bottom: 10px; margin-bottom: 20px; font-weight: 700;">
                            <i class="fas fa-book-open" style="margin-right: 10px;"></i>SUBJECT-WISE PERFORMANCE
                        </h3>
                        
                        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                            <thead>
                                <tr style="background: linear-gradient(135deg, #28a745, #1e7e34); color: white;">
                                    <th style="padding: 12px; text-align: left; border-radius: 8px 0 0 0;">Subject</th>
                                    <th style="padding: 12px; text-align: center;">Term I</th>
                                    <th style="padding: 12px; text-align: center;">Term II</th>
                                    <th style="padding: 12px; text-align: center;">Total</th>
                                    <th style="padding: 12px; text-align: center;">Percentage</th>
                                    <th style="padding: 12px; text-align: center; border-radius: 0 8px 0 0;">Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${subjectsHTML}
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Co-Scholastic Areas -->
                    <div style="background: linear-gradient(135deg, #f0f8ff, #ffffff); border-radius: 15px; padding: 25px; margin-bottom: 30px; border: 2px solid #17a2b8;">
                        <h3 style="color: #0c5460; border-bottom: 3px solid #17a2b8; padding-bottom: 10px; margin-bottom: 20px; font-weight: 700;">
                            <i class="fas fa-star" style="margin-right: 10px;"></i>CO-SCHOLASTIC AREAS
                        </h3>
                        
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                            <div>
                                <div style="font-weight: 700; color: #0c5460; margin-bottom: 10px;">General Knowledge</div>
                                <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e0e0e0;">
                                    ${document.getElementById('gkTerm1').value || 'Not assessed'} / ${document.getElementById('gkTerm2').value || 'Not assessed'}
                                </div>
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #0c5460; margin-bottom: 10px;">Art Education</div>
                                <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e0e0e0;">
                                    ${document.getElementById('artTerm1').value || 'Not assessed'} / ${document.getElementById('artTerm2').value || 'Not assessed'}
                                </div>
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #0c5460; margin-bottom: 10px;">Work Experience</div>
                                <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e0e0e0;">
                                    ${document.getElementById('workTerm1').value || 'Not assessed'} / ${document.getElementById('workTerm2').value || 'Not assessed'}
                                </div>
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #0c5460; margin-bottom: 10px;">Physical Education</div>
                                <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e0e0e0;">
                                    ${document.getElementById('peTerm1').value || 'Not assessed'} / ${document.getElementById('peTerm2').value || 'Not assessed'}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Teacher's Remarks & Promotion Status -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-bottom: 30px;">
                        <div style="background: linear-gradient(135deg, #fff0f5, #ffffff); border-radius: 15px; padding: 25px; border: 2px solid #e63946;">
                            <h3 style="color: #b71c1c; border-bottom: 3px solid #e63946; padding-bottom: 10px; margin-bottom: 20px; font-weight: 700;">
                                <i class="fas fa-comment-dots" style="margin-right: 10px;"></i>TEACHER'S REMARKS
                            </h3>
                            <div style="line-height: 1.6; color: #2c3e50; font-size: 14px;">
                                ${getTeacherRemarks(overallPercentage)}
                            </div>
                        </div>
                        
                        <div style="background: linear-gradient(135deg, #f0fff0, #ffffff); border-radius: 15px; padding: 25px; border: 2px solid #28a745;">
                            <h3 style="color: #1e7e34; border-bottom: 3px solid #28a745; padding-bottom: 10px; margin-bottom: 20px; font-weight: 700;">
                                <i class="fas fa-award" style="margin-right: 10px;"></i>PROMOTION STATUS
                            </h3>
                            <div style="text-align: center; padding: 20px;">
                                <div style="font-size: 36px; font-weight: 800; color: ${promotionColor}; margin-bottom: 15px;">${promotionStatus}</div>
                                <div style="font-size: 18px; color: #2c3e50; margin-bottom: 20px;">
                                    ${promotionMessage}
                                </div>
                                <div style="display: flex; justify-content: center; gap: 20px; margin-top: 20px;">
                                    <div style="text-align: center;">
                                        <div style="font-size: 14px; color: #7f8c8d;">Overall Percentage</div>
                                        <div style="font-size: 24px; font-weight: 700; color: #2c3e50;">${overallPercentage}%</div>
                                    </div>
                                    <div style="text-align: center;">
                                        <div style="font-size: 14px; color: #7f8c8d;">Overall Grade</div>
                                        <div style="font-size: 24px; font-weight: 700; color: #2c3e50;">${overallGrade}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Signatures -->
                    <div style="border-top: 2px solid #dee2e6; padding-top: 30px; margin-top: 30px;">
                        <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
                            <div style="text-align: center; flex: 1; min-width: 200px;">
                                <div style="border-bottom: 1px solid #2c3e50; width: 150px; margin: 0 auto 10px; padding-bottom: 40px;"></div>
                                <div style="font-weight: 700; color: #2c3e50;">Class Teacher</div>
                                <div style="font-size: 12px; color: #7f8c8d; margin-top: 5px;">Name & Signature</div>
                            </div>
                            <div style="text-align: center; flex: 1; min-width: 200px;">
                                <div style="border-bottom: 1px solid #2c3e50; width: 150px; margin: 0 auto 10px; padding-bottom: 40px;"></div>
                                <div style="font-weight: 700; color: #2c3e50;">Principal</div>
                                <div style="font-size: 12px; color: #7f8c8d; margin-top: 5px;">Name & Signature</div>
                            </div>
                            <div style="text-align: center; flex: 1; min-width: 200px;">
                                <div style="border-bottom: 1px solid #2c3e50; width: 150px; margin: 0 auto 10px; padding-bottom: 40px;"></div>
                                <div style="font-weight: 700; color: #2c3e50;">Parent/Guardian</div>
                                <div style="font-size: 12px; color: #7f8c8d; margin-top: 5px;">Name & Signature</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #dee2e6;">
                        <div style="color: #7f8c8d; font-size: 12px;">
                            <p style="margin-bottom: 5px;">Report Card Generated on: <strong>${formattedDate}</strong></p>
                            <p style="margin-bottom: 5px;">ANEE'S SCHOOL | www.aneesschool.edu.in | Phone: 7527066620, 7527066621</p>
                            <p><em>This is a computer generated report card. No signature required for digital copy.</em></p>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('reportCardPreview').innerHTML = reportCardHTML;
        }
        
        // Get teacher remarks based on percentage
        function getTeacherRemarks(percentage) {
            if (percentage >= 90) {
                return "Excellent performance! Student has shown outstanding academic abilities and consistent hard work throughout the year. Maintains excellent discipline and participates actively in all school activities. Shows remarkable understanding of concepts and applies knowledge creatively. Keep up the excellent work!";
            } else if (percentage >= 75) {
                return "Very good performance. Student is diligent, attentive in class and completes assignments on time. Shows good understanding of concepts and has consistent study habits. Participates well in classroom discussions and shows improvement throughout the year. Continue with the same dedication.";
            } else if (percentage >= 60) {
                return "Good performance. Student shows satisfactory understanding of subjects. Regular attendance and participation observed. Can improve with more focused effort and regular revision. Shows potential for better performance with consistent practice.";
            } else if (percentage >= 33) {
                return "Satisfactory performance. Student needs to put more effort in studies. Regular revision and practice recommended. Shows potential for improvement with better time management and study habits. Parental guidance and support would be beneficial.";
            } else {
                return "Needs improvement. Student requires special attention and remedial classes. Regular attendance and parental guidance necessary for better performance. Shows difficulty in understanding concepts - recommend additional support and regular practice sessions.";
            }
        }
        
        // Generate PDF from HTML
        async function generatePDFFromHTML() {
            try {
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('p', 'mm', 'a4');
                const element = document.getElementById('reportCardPreview');
                
                // Use html2canvas to capture the report card
                const canvas = await html2canvas(element, {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                    backgroundColor: '#ffffff',
                    width: element.offsetWidth,
                    height: element.scrollHeight
                });
                
                const imgData = canvas.toDataURL('image/png');
                const imgWidth = 210; // A4 width in mm
                const pageHeight = 297; // A4 height in mm
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                
                let heightLeft = imgHeight;
                let position = 0;
                
                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
                
                // Add additional pages if needed
                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }
                
                // Generate filename
                const fileName = `Report_Card_${currentStudent.admissionNo}_${currentStudent.name.replace(/\s+/g, '_')}.pdf`;
                
                // Save the PDF
                pdf.save(fileName);
                
                showNotification('Report card PDF generated successfully!', 'success');
                
            } catch (error) {
                console.error('Error generating PDF:', error);
                showNotification('Error generating PDF. Please try again.', 'error');
            }
        }
        
        // Helper functions
        function getRandomHeight(studentClass) {
            const baseHeight = studentClass >= 10 ? 150 : 140;
            return baseHeight + Math.floor(Math.random() * 15);
        }
        
        function getRandomWeight(studentClass) {
            const baseWeight = studentClass >= 10 ? 40 : 35;
            return baseWeight + Math.floor(Math.random() * 15);
        }
    </script>
</body>
</html>
@endsection
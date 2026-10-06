@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --success-color: #10b981;
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
        --light-bg: #f8fafc;
        --border-color: #e2e8f0;
    }

    /*body {*/
    /*    background-color: var(--light-bg);*/
    /*    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;*/
    /*}*/

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding: 20px 30px;
        background: var(--primary-gradient);
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        animation: fadeInDown 0.5s ease;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .page-title i {
        font-size: 32px;
        filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
    }

    .back-link {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(5px);
        transition: all 0.3s;
        text-decoration: none;
        font-size: 14px;
    }

    .back-link:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
    }

    /* Main Form Card */
    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        padding: 40px;
        position: relative;
        overflow: hidden;
        border: none;
        animation: fadeInUp 0.5s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: var(--primary-gradient);
    }

    /* Form Header */
    .form-header {
        text-align: center;
        margin-bottom: 40px;
        position: relative;
    }

    .form-header-icon {
        width: 90px;
        height: 90px;
        background: var(--primary-gradient);
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        color: white;
        font-size: 2.5rem;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        position: relative;
        overflow: hidden;
    }

    /* Fixed animation for the icon */
    .form-header-icon::after {
        content: '';
        position: absolute;
        top: -25%;
        left: -25%;
        width: 150%;
        height: 150%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0) 70%);
        animation: rotate 8s linear infinite;
        pointer-events: none;
    }

    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    .form-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }

    .form-subtitle {
        color: #64748b;
        font-size: 1rem;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 30px;
        position: relative;
    }

    /* Form Labels */
    .form-label {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .form-label i {
        color: var(--primary-color);
        font-size: 16px;
        width: 20px;
    }

    .required::after {
        content: ' *';
        color: #ef4444;
        font-weight: 600;
    }

    /* Form Controls */
    .form-control-custom {
        border: 2px solid var(--border-color);
        border-radius: 16px;
        padding: 14px 18px;
        font-size: 1rem;
        transition: all 0.3s;
        background: #fff;
        width: 100%;
    }

    .form-control-custom:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
        background: #fff;
    }

    .form-control-custom:hover {
        border-color: var(--secondary-color);
    }

    /* Info Alert */
    .info-alert {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border: 2px solid #93c5fd;
        border-radius: 16px;
        padding: 20px 25px;
        margin: 30px 0;
        display: flex;
        align-items: center;
        gap: 15px;
        color: #1e40af;
    }

    .info-alert i {
        font-size: 28px;
        color: #2563eb;
    }

    .info-alert-content {
        flex: 1;
    }

    .info-alert-title {
        font-weight: 700;
        font-size: 16px;
        margin-bottom: 5px;
    }

    .info-alert-text {
        font-size: 14px;
        opacity: 0.9;
    }

    /* Action Buttons */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid var(--border-color);
    }

    .btn-submit {
        background: var(--primary-gradient);
        border: none;
        border-radius: 14px;
        padding: 14px 45px;
        font-weight: 600;
        color: white;
        transition: all 0.3s;
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .btn-submit::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-submit:hover::before {
        left: 100%;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.4);
    }

    .btn-submit:active {
        transform: translateY(-1px);
    }

    .btn-submit i {
        font-size: 18px;
    }

    /* Alert Messages */
    .alert {
        border: none;
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .alert-success {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        border: 1px solid #86efac;
        color: #166534;
    }

    .alert-danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border: 1px solid #fca5a5;
        color: #991b1b;
    }

    .alert ul {
        margin-top: 8px;
        padding-left: 20px;
    }

    .alert .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
        opacity: 0.8;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
        }

        .back-link {
            width: 100%;
            justify-content: center;
        }

        .form-card {
            padding: 30px 20px;
        }

        .form-header-icon {
            width: 70px;
            height: 70px;
            font-size: 2rem;
        }

        .form-title {
            font-size: 1.8rem;
        }

        .info-alert {
            flex-direction: column;
            text-align: center;
        }

        .form-actions {
            justify-content: center;
        }

        .btn-submit {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .form-card {
            padding: 25px 15px;
        }

        .form-title {
            font-size: 1.5rem;
        }

        .form-subtitle {
            font-size: 0.9rem;
        }
    }
</style>

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-bus"></i>
            Transport Management
        </h1>
        <a href="{{ route('admin.transport.routes.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Routes
        </a>
    </div>

    <!-- Main Form Card -->
    <div class="form-card">
        <div class="form-header">
            <div class="form-header-icon">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <h1 class="form-title">Create New Transport Route</h1>
            <p class="form-subtitle">Fill in the details below to add a new route to the system</p>
        </div>
        
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.transport.routes.store') }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-route"></i>
                    <span class="required">Route Name</span>
                </label>
                <input type="text" 
                       name="route_name" 
                       class="form-control form-control-custom" 
                       placeholder="e.g. Chandigarh-Mohali-Kharar" 
                       value="{{ old('route_name') }}" 
                       required>
                <small class="text-muted" style="margin-top: 8px; display: block;">
                    <i class="fas fa-info-circle me-1"></i>Enter a unique name for this route
                </small>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-align-left"></i>
                    Description
                </label>
                <input type="text" 
                       name="description" 
                       class="form-control form-control-custom" 
                       placeholder="Optional: A brief description of the route" 
                       value="{{ old('description') }}">
                <small class="text-muted" style="margin-top: 8px; display: block;">
                    <i class="fas fa-info-circle me-1"></i>Enter a description for this route (optional)
                </small>
            </div>

            <div class="info-alert">
                <i class="fas fa-info-circle"></i>
                <div class="info-alert-content">
                    <div class="info-alert-title">What happens next?</div>
                    <div class="info-alert-text">
                        After creating the route, you can add buses, stops, timings, and fees when adding a new bus.
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i>
                    Create Route
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
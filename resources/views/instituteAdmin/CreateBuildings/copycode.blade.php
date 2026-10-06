@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Block, Floor & Room Setup</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-gradient: linear-gradient(135deg, #10b981, #059669);
            --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
            --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
            --info-gradient: linear-gradient(135deg, #0ea5e9, #0284c7);
            --border-color: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }
        *{box-sizing:border-box}
        .header{background:var(--primary-gradient);color:white;padding:1.5rem 2rem;border-radius:16px;margin-bottom:2rem;box-shadow:0 15px 35px rgba(67,97,238,0.3);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
        .header-content h1{font-size:28px;font-weight:700;color:white;margin:0;display:flex;align-items:center;gap:12px}
        .header-content h1 i{background:rgba(255,255,255,0.2);padding:10px;border-radius:12px}
        .header-content p{opacity:0.9;font-size:1rem}
        .timeline-stepper{display:flex;justify-content:center;align-items:center;margin-bottom:2rem;padding:1.5rem 2rem;background:white;border-radius:16px;box-shadow:0 4px 15px rgba(0,0,0,0.05);border:2px solid var(--border-color);flex-wrap:wrap}
        .step-item{display:flex;align-items:center;gap:10px;cursor:pointer;position:relative;transition:all 0.3s}
        .step-circle{width:48px;height:48px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.1rem;border:3px solid var(--border-color);background:white;color:var(--text-muted);transition:all 0.3s;flex-shrink:0}
        .step-circle.completed{background:var(--success-gradient);border-color:#10b981;color:white}
        .step-circle.active{background:var(--primary-gradient);border-color:var(--primary-color);color:white;box-shadow:0 0 0 8px rgba(67,97,238,0.15)}
        .step-label{font-weight:600;color:var(--text-muted);font-size:0.85rem;transition:all 0.3s;white-space:nowrap}
        .step-label.active-label{color:var(--primary-color)}.step-label.completed-label{color:#059669}
        .step-connector{flex:1;height:3px;background:var(--border-color);margin:0 15px;min-width:40px;transition:all 0.3s}
        .step-connector.completed{background:linear-gradient(90deg,#10b981,#4361ee)}
        .form-card{background:white;border-radius:20px;padding:2.5rem;box-shadow:0 8px 25px rgba(0,0,0,0.05);border:2px solid var(--border-color);max-width:1400px;margin:0 auto}
        .form-card h2{color:var(--text-dark);margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:2px solid var(--border-color);font-weight:700;display:flex;align-items:center;gap:10px}
        .form-card h2 i{color:var(--primary-color)}
        .form-card h3{color:var(--text-dark);margin-bottom:1rem;display:flex;align-items:center;gap:10px;font-size:1.1rem;font-weight:700}
        .form-card h3 i{color:var(--primary-color)}
        .form-group{margin-bottom:1.5rem}
        .form-label{display:block;margin-bottom:0.5rem;font-weight:600;color:var(--text-dark);font-size:0.9rem}
        .form-group label{display:block;margin-bottom:0.5rem;color:var(--text-dark);font-weight:600;font-size:0.95rem}
        .form-control,.form-group input,.form-group textarea,.form-group select{width:100%;padding:12px 16px;border:2px solid var(--border-color);border-radius:12px;font-size:1rem;transition:all 0.3s;font-family:inherit;background:#f8fafc}
        .form-control:focus,.form-group input:focus,.form-group textarea:focus,.form-group select:focus{outline:none;border-color:var(--primary-color);box-shadow:0 0 0 4px rgba(67,97,238,0.1);background:white}
        select.form-control,select{cursor:pointer;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%234361ee' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 16px center;padding-right:40px}
        textarea.form-control,textarea{resize:vertical;min-height:80px}
        .is-invalid{border-color:#dc3545!important}.invalid-feedback{display:block;width:100%;margin-top:4px;font-size:0.8rem;color:#dc3545;font-weight:500}
        .alert-error{background:linear-gradient(135deg,#fef2f2,#fecaca);color:#991b1b;border:1px solid #fca5a5;border-radius:12px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px}
        .alert-error i{font-size:1.2rem;margin-top:2px}
        .alert-error ul{margin:0;padding-left:20px}
        .alert-error ul li{margin-bottom:4px;font-size:0.85rem}
        .alert-info{background:linear-gradient(135deg,#f0f9ff,#e0f2fe);color:#075985;border:1px solid #7dd3fc;border-radius:12px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px}
        .alert-info i{font-size:1.2rem;margin-top:2px;color:#0ea5e9}
        .campus-info-header{background:linear-gradient(135deg,#f0f4ff,#e8edff);border:2px solid rgba(67,97,238,0.2);border-radius:16px;padding:1.25rem 1.5rem;margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
        .campus-info-header .campus-name-section{display:flex;align-items:center;gap:12px}
        .campus-info-header .campus-name{font-size:1.2rem;font-weight:700;color:var(--text-dark)}
        .campus-info-header .campus-code{background:var(--primary-gradient);color:white;padding:5px 14px;border-radius:20px;font-size:0.8rem;font-weight:600}
        .campus-info-header .area-display{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
        .campus-info-header .area-badge{background:white;padding:8px 16px;border-radius:10px;font-weight:700;color:var(--primary-color);border:2px solid rgba(67,97,238,0.2);font-size:0.9rem;display:flex;align-items:center;gap:6px}
        .campus-info-header .remaining-badge{background:var(--success-gradient);color:white;padding:8px 16px;border-radius:10px;font-weight:700;font-size:0.85rem;display:flex;align-items:center;gap:6px}
        .campus-info-header .exceeded-badge{background:var(--danger-gradient);color:white;padding:8px 16px;border-radius:10px;font-weight:700;font-size:0.85rem;display:flex;align-items:center;gap:6px}
        .block-accordion{background:white;border:2px solid var(--border-color);border-radius:16px;margin-bottom:1rem;transition:all 0.3s ease;overflow:hidden}
        .block-accordion.active{border-color:var(--primary-color);box-shadow:0 4px 15px rgba(67,97,238,0.1)}
        .block-accordion .accordion-header{display:flex;justify-content:space-between;align-items:center;padding:1.25rem 1.5rem;cursor:pointer;transition:all 0.3s ease;background:white;user-select:none}
        .block-accordion .accordion-header:hover{background:#f8fafc}
        .block-accordion .accordion-header .header-left{display:flex;align-items:center;gap:12px;flex:1;min-width:0}
        .block-accordion .accordion-header .block-icon{width:44px;height:44px;background:var(--primary-gradient);border-radius:12px;display:flex;align-items:center;justify-content:center;color:white;font-size:1.1rem;flex-shrink:0}
        .block-accordion .accordion-header .block-info{flex:1;min-width:0}
        .block-accordion .accordion-header .block-title{font-weight:700;color:var(--text-dark);font-size:0.95rem}
        .block-accordion .accordion-header .block-subtitle{font-size:0.75rem;color:var(--text-muted);display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-top:2px}
        .block-accordion .accordion-header .header-actions{display:flex;align-items:center;gap:10px;flex-shrink:0}
        .block-accordion .accordion-header .toggle-icon{width:32px;height:32px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;transition:all 0.3s ease;color:var(--text-muted)}
        .block-accordion.active .accordion-header .toggle-icon{background:var(--primary-gradient);color:white;transform:rotate(180deg)}
        .block-accordion .accordion-body{max-height:0;overflow:hidden;transition:max-height 0.4s cubic-bezier(0.4,0,0.2,1)}
        .block-accordion.active .accordion-body{max-height:8000px}
        .block-accordion .accordion-content{padding:0 1.5rem 1.5rem 1.5rem;margin-top:30px}
        .block-accordion .block-area-summary{background:#f8fafc;border:1px solid var(--border-color);border-radius:8px;padding:8px 14px;margin-top:8px;font-size:0.8rem;color:var(--text-dark);display:flex;align-items:center;gap:8px;flex-wrap:wrap}
        .block-accordion .block-area-summary strong{color:var(--primary-color)}
        .block-accordion .block-area-summary .remaining-block{color:#059669;font-weight:600;margin-left:auto}
        .block-accordion .block-area-summary .exceeded-block{color:#dc2626;font-weight:600;margin-left:auto}
        .section-divider{border-top:2px solid var(--border-color);margin:1.25rem 0;padding-top:1rem}
        .section-divider h3{color:var(--text-dark);margin-bottom:0.5rem;display:flex;align-items:center;gap:10px;font-size:1.1rem;font-weight:700}
        .section-divider h3 i{color:var(--primary-color)}
        .section-divider h4{font-size:0.9rem;font-weight:700;color:var(--text-dark);display:flex;align-items:center;gap:8px}
        .section-divider h4 i{color:var(--primary-color)}
        .dynamic-card{background:white;border:2px solid var(--border-color);border-radius:14px;padding:1.25rem;margin-bottom:1rem;transition:all 0.3s ease;position:relative}
        .dynamic-card:hover{border-color:var(--primary-color);box-shadow:0 4px 15px rgba(67,97,238,0.1)}
        .dynamic-card .card-badge,.dynamic-card .card-badge-sm{position:absolute;top:-12px;left:16px;background:var(--primary-gradient);color:white;padding:3px 12px;border-radius:20px;font-size:0.7rem;font-weight:700}
        .dynamic-card .card-row{display:grid;gap:0.75rem}
        .dynamic-card .form-group{margin-bottom:0}
        .dynamic-card .form-group label{font-size:0.85rem;margin-bottom:0.3rem}
        .dynamic-card .form-group input,.dynamic-card .form-group select{padding:0.6rem 0.9rem;font-size:0.9rem}
        .building-area-selector{background:#f8fafc;border:2px dashed var(--border-color);border-radius:12px;padding:1rem;margin-bottom:0.75rem}
        .custom-amenity-card{background:linear-gradient(135deg,#f0fdf4,#ecfdf5);border:2px dashed #6ee7b7;border-radius:14px;padding:1.25rem;margin-bottom:1rem;transition:all 0.3s ease;position:relative}
        .custom-amenity-card:hover{border-color:#10b981;box-shadow:0 4px 15px rgba(16,185,129,0.1)}
        .custom-amenity-card .card-badge-sm,.custom-amenity-card .card-badge{position:absolute;top:-12px;left:16px;background:var(--success-gradient);color:white;padding:3px 12px;border-radius:20px;font-size:0.7rem;font-weight:700}
        .amenities-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1rem}
        .amenity-group{background:#f8fafc;padding:1.25rem;border-radius:12px;border:1px solid var(--border-color);transition:all 0.3s}
        .amenity-group.has-checked{border-color:var(--primary-color);background:#f0f4ff}
        .amenity-group h4,.amenity-group h5{color:var(--text-dark);margin-bottom:0.75rem;font-size:0.9rem;display:flex;align-items:center;gap:8px;font-weight:700}
        .amenity-group h4 i,.amenity-group h5 i{color:var(--primary-color)}
        .checkbox-group{display:flex;flex-direction:column;gap:0.6rem}
        .checkbox-item{display:block;align-items:center;justify-content:space-between;gap:0.5rem}
        .checkbox-item .checkbox-label-wrap{display:flex;align-items:center;gap:0.5rem;flex:1;margin-top:10px;}
        .checkbox-item input[type="checkbox"]{width:18px;height:18px;cursor:pointer;accent-color:var(--primary-color);flex-shrink:0}
        .checkbox-item label{font-size:0.85rem;color:#555;cursor:pointer;font-weight:500}
        .amenity-count-input{width:70px;padding:5px 8px;border:2px solid var(--border-color);border-radius:8px;font-size:0.8rem;text-align:center;transition:all 0.3s;display:none}
        .amenity-count-input.show{display:block}
        .amenity-count-label{font-size:0.7rem;color:var(--text-muted);display:none;white-space:nowrap}
        .amenity-count-label.show{display:inline}
        .facility-card{background:#f8fafc;border:1px solid var(--border-color);border-radius:14px;padding:1.5rem;margin-bottom:1rem;transition:all 0.3s}
        .facility-card:hover{border-color:var(--primary-color);box-shadow:0 4px 12px rgba(67,97,238,0.08)}
        .facility-card h5{color:var(--text-dark);font-size:0.95rem;font-weight:700;margin-bottom:1rem;display:flex;align-items:center;gap:8px}
        .facility-card h5 i{color:var(--primary-color)}
        .facility-toggle{display:flex;align-items:center;gap:10px;margin-bottom:1rem}
        .facility-toggle label{font-weight:500;cursor:pointer}
        .facility-toggle input[type="checkbox"]{width:18px;height:18px;cursor:pointer;accent-color:var(--primary-color)}
        .facility-details{display:none;padding-top:0.75rem;border-top:1px dashed var(--border-color)}
        .facility-details.show{display:block}
        .type-selector{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:0.75rem}
        .type-option{padding:6px 16px;border:2px solid var(--border-color);border-radius:20px;cursor:pointer;font-size:0.85rem;font-weight:500;transition:all 0.3s;background:white}
        .type-option:hover{border-color:var(--primary-color)}
        .type-option.selected{background:var(--primary-gradient);color:white;border-color:var(--primary-color)}
        .multi-entry-card{background:white;border:1px dashed var(--primary-color);border-radius:10px;padding:1rem;margin-bottom:0.75rem;position:relative}
        .multi-entry-card .entry-badge{position:absolute;top:-10px;left:12px;background:var(--primary-gradient);color:white;padding:2px 10px;border-radius:12px;font-size:0.65rem;font-weight:600}
        .floor-card{background:white;border:2px solid var(--border-color);border-radius:16px;padding:1.75rem;margin-bottom:1.5rem;transition:all 0.3s ease;position:relative}
        .floor-card:hover{border-color:var(--primary-color);box-shadow:0 4px 15px rgba(67,97,238,0.1)}
        .floor-card .card-badge{position:absolute;top:-14px;left:20px;background:var(--primary-gradient);color:white;padding:5px 16px;border-radius:20px;font-size:0.8rem;font-weight:700}
        .existing-floor-card{background:linear-gradient(135deg,#f0fdf4,#ecfdf5);border:2px solid #6ee7b7;border-radius:14px;padding:1.25rem;margin-bottom:0.75rem}
        .existing-floor-card .existing-badge{background:var(--success-gradient);color:white;padding:3px 12px;border-radius:20px;font-size:0.7rem;font-weight:700;display:inline-block;margin-bottom:0.5rem}
        .existing-floor-card .floor-info{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem}
        .existing-floor-card .floor-name{font-weight:700;color:var(--text-dark);font-size:0.9rem}
        .existing-floor-card .floor-meta{font-size:0.75rem;color:var(--text-muted)}
        .existing-floor-card .floor-stats{display:flex;gap:10px;flex-wrap:wrap;margin-top:0.5rem}
        .existing-floor-card .stat-tag{background:white;padding:2px 8px;border-radius:12px;font-size:0.7rem;font-weight:600;color:#065f46;border:1px solid #6ee7b7}
        .existing-count-badge{background:var(--success-gradient);color:white;padding:4px 12px;border-radius:20px;font-size:0.75rem;font-weight:600}
        .no-floors-message{text-align:center;padding:1.5rem;color:var(--text-muted);background:#f8fafc;border-radius:10px;border:1px dashed var(--border-color)}
        .room-type-selector{display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:0.75rem}
        .room-type-option{padding:0.75rem;border:2px solid var(--border-color);border-radius:12px;text-align:center;cursor:pointer;transition:all 0.3s;background:white;font-weight:500;color:var(--text-muted)}
        .room-type-option i{display:block;font-size:1.5rem;margin-bottom:6px;color:var(--primary-color)}
        .room-type-option:hover{border-color:var(--primary-color);transform:translateY(-2px)}
        .room-type-option.selected{background:var(--primary-gradient);color:white;border-color:var(--primary-color)}
        .room-type-option.selected i{color:white}
        .room-specs-section{margin-top:1.5rem}
        .specs-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.25rem}
        .spec-group{background:#f8fafc;padding:1.25rem;border-radius:14px;border:1px solid var(--border-color)}
        .spec-group h4{color:var(--text-dark);margin-bottom:0.75rem;font-size:0.9rem;display:flex;align-items:center;gap:8px;font-weight:700}
        .spec-group h4 i{color:var(--primary-color)}
        .count-inputs{display:grid;grid-template-columns:repeat(2,1fr);gap:0.75rem}
        .count-input{display:flex;align-items:center;gap:0.5rem}
        .count-input input{width:60px;padding:6px 8px;border:2px solid var(--border-color);border-radius:8px;font-size:0.85rem;text-align:center}
        .count-input label{font-size:0.8rem;color:var(--text-muted)}
        .inline-input{display:flex;align-items:center;gap:0.75rem}
        .inline-input input{flex:1;padding:8px 12px;border:2px solid var(--border-color);border-radius:10px;font-size:0.875rem}
        .inline-input span{font-size:0.85rem;color:var(--text-muted);white-space:nowrap}
        .btn{padding:0.75rem 1.5rem;border:none;border-radius:12px;font-size:1rem;font-weight:600;cursor:pointer;transition:all 0.3s;display:inline-flex;align-items:center;gap:8px;text-decoration:none}
        .btn-primary{background:var(--primary-gradient);color:white;box-shadow:0 4px 15px rgba(67,97,238,0.3)}
        .btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(67,97,238,0.4)}
        .btn-secondary{background:#f1f5f9;color:var(--text-dark);border:2px solid var(--border-color)}
        .btn-secondary:hover{background:#e2e8f0;transform:translateY(-2px)}
        .btn-success{background:var(--success-gradient);color:white}
        .btn-success:hover{transform:translateY(-2px)}
        .btn-outline-primary{background:white;color:var(--primary-color);border:2px solid var(--primary-color);padding:0.5rem 1rem;border-radius:10px;font-weight:600;font-size:0.85rem;cursor:pointer;transition:all 0.3s;display:inline-flex;align-items:center;gap:6px}
        .btn-outline-primary:hover{background:var(--primary-gradient);color:white;transform:translateY(-2px)}
        .btn-outline-success{background:white;color:#10b981;border:2px solid #10b981;padding:0.5rem 1rem;border-radius:10px;font-weight:600;font-size:0.85rem;cursor:pointer;transition:all 0.3s;display:inline-flex;align-items:center;gap:6px}
        .btn-outline-success:hover{background:var(--success-gradient);color:white;transform:translateY(-2px)}
        .btn-outline-danger{background:white;color:#dc3545;border:2px solid #dc3545;padding:0.5rem 1rem;border-radius:10px;font-weight:600;font-size:0.85rem;cursor:pointer;transition:all 0.3s;display:inline-flex;align-items:center;gap:6px}
        .btn-outline-danger:hover{background:var(--danger-gradient);color:white;transform:translateY(-2px)}
        .btn-sm{padding:5px 10px;font-size:0.7rem;border-radius:6px}
        .add-btn-row{display:flex;justify-content:flex-end;margin-bottom:1rem}
        .form-actions{display:flex;gap:1rem;margin-top:1.5rem;padding-top:1.5rem;border-top:2px solid var(--border-color)}
        .hidden{display:none!important}
        .step-content{display:none}
        .step-content.active{display:block}
        .block-type-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem;margin-bottom:2rem}
        .block-type-card{background:white;border:3px solid var(--border-color);border-radius:16px;padding:2rem 1.5rem;text-align:center;cursor:pointer;transition:all 0.3s ease;position:relative}
        .block-type-card:hover{border-color:var(--primary-color);transform:translateY(-4px);box-shadow:0 12px 30px rgba(67,97,238,0.15)}
        .block-type-card.selected{border-color:var(--primary-color);background:linear-gradient(135deg,#f0f4ff,#e8edff)}
        .block-type-card.selected::after{content:'\f00c';font-family:'Font Awesome 6 Free';font-weight:900;position:absolute;top:12px;right:16px;font-size:1.5rem;color:var(--primary-color)}
        .block-type-card .card-icon{width:72px;height:72px;border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:2rem;color:white}
        .block-type-card .card-icon.single{background:var(--success-gradient)}
        .block-type-card .card-icon.multi{background:var(--primary-gradient)}
        .block-type-card h4{font-size:1.1rem;font-weight:700;color:var(--text-dark);margin-bottom:0.5rem}
        .block-type-card p{font-size:0.85rem;color:var(--text-muted);margin:0}
        .block-type-card .recommended-badge{display:inline-block;background:var(--success-gradient);color:white;padding:3px 12px;border-radius:20px;font-size:0.7rem;font-weight:700;margin-top:0.75rem}
        .accordion-controls{display:flex;gap:10px;margin-bottom:1rem;justify-content:flex-end}
        .loading-spinner{display:inline-block;width:20px;height:20px;border:3px solid rgba(255,255,255,0.3);border-top:3px solid white;border-radius:50%;animation:spin 1s linear infinite}
        @keyframes spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}
        .toast{position:fixed;bottom:20px;right:20px;background:var(--success-gradient);color:white;padding:16px 24px;border-radius:12px;box-shadow:0 8px 25px rgba(0,0,0,0.15);display:flex;align-items:center;gap:10px;z-index:1001;transform:translateY(100px);opacity:0;transition:all 0.3s ease}
        .toast.show{transform:translateY(0);opacity:1}
        .toast.error{background:var(--danger-gradient)}
        .toast.info{background:var(--info-gradient)}
        .amenity-detail{margin-top:0.5rem;padding-top:0.5rem;border-top:1px dashed var(--border-color)}
        .amenity-detail-row{display:flex;align-items:center;gap:0.5rem;margin-bottom:0.4rem}
        .amenity-detail-row label{font-size:0.7rem!important;color:var(--text-muted)!important;min-width:80px;font-weight:500!important}
        .amenity-detail-input{width:80px!important;padding:4px 8px!important;border:1px solid var(--border-color)!important;border-radius:6px!important;font-size:0.75rem!important;text-align:center}
        .amenity-detail-input:focus{outline:none;border-color:var(--primary-color)!important;box-shadow:0 0 0 2px rgba(67,97,238,0.1)}
        .amenity-detail-select{padding:4px 8px!important;border:1px solid var(--border-color)!important;border-radius:6px!important;font-size:0.75rem!important;background:white;cursor:pointer}
        .allocation-tracker{background:#f8fafc;border:1px solid var(--border-color);border-radius:12px;padding:1rem;margin-bottom:1rem}
        .allocation-tracker .allocation-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem;padding-bottom:0.5rem;border-bottom:1px solid var(--border-color)}
        .allocation-tracker .allocation-header h4{font-size:0.9rem;font-weight:700;color:var(--text-dark);margin:0;display:flex;align-items:center;gap:8px}
        .allocation-tracker .allocation-header h4 i{color:var(--primary-color)}
        .allocation-tracker .allocation-header .remaining-tag{font-size:0.7rem;padding:3px 10px;border-radius:12px;font-weight:600}
        .allocation-tracker .allocation-header .remaining-tag.available{background:#dcfce7;color:#166534}
        .allocation-tracker .allocation-header .remaining-tag.exhausted{background:#fef2f2;color:#991b1b}
        .allocation-item{display:flex;align-items:center;gap:12px;padding:8px 12px;background:white;border-radius:8px;margin-bottom:6px;border:1px solid var(--border-color);transition:all 0.2s}
        .allocation-item:hover{border-color:var(--primary-color)}
        .allocation-item .alloc-check{width:18px;height:18px;accent-color:var(--primary-color);flex-shrink:0;cursor:pointer}
        .allocation-item .alloc-name{flex:1;font-size:0.85rem;font-weight:500;color:var(--text-dark)}
        .allocation-item .alloc-stats{display:flex;align-items:center;gap:12px;font-size:0.7rem;color:var(--text-muted)}
        .allocation-item .alloc-stats .used{color:var(--primary-color);font-weight:600}
        .allocation-item .alloc-stats .remaining{font-weight:600}
        .allocation-item .alloc-stats .remaining.positive{color:#059669}
        .allocation-item .alloc-stats .remaining.zero{color:#dc2626}
        .allocation-item .alloc-qty{width:60px;padding:4px 6px;border:2px solid var(--border-color);border-radius:6px;font-size:0.8rem;text-align:center;transition:all 0.2s}
        .allocation-item .alloc-qty:focus{outline:none;border-color:var(--primary-color);box-shadow:0 0 0 3px rgba(67,97,238,0.1)}
        .allocation-item .alloc-qty:disabled{opacity:0.5;background:#f1f5f9;cursor:not-allowed}
        .allocation-item .alloc-qty.show{display:block}
        .allocation-item .alloc-badge{font-size:0.6rem;padding:2px 8px;border-radius:10px;font-weight:600;white-space:nowrap}
        .allocation-item .alloc-badge.included{background:#dbeafe;color:#1e40af}
        .allocation-item .alloc-badge.excluded{background:#f1f5f9;color:#64748b}
        .allocation-item .alloc-badge.exhausted{background:#fef2f2;color:#991b1b}
        .allocation-summary{display:flex;gap:20px;flex-wrap:wrap;margin-top:8px;padding-top:8px;border-top:1px dashed var(--border-color)}
        .allocation-summary .stat-item{font-size:0.7rem;color:var(--text-muted);display:flex;align-items:center;gap:4px}
        .allocation-summary .stat-item strong{color:var(--text-dark);font-weight:700}
        .block-alloc-summary{background:linear-gradient(135deg,#f0f4ff,#e8edff);border-radius:8px;padding:6px 14px;font-size:0.7rem;display:inline-flex;align-items:center;gap:8px;border:1px solid rgba(67,97,238,0.15)}
        .block-alloc-summary i{color:var(--primary-color)}
        .facility-group-card{background:white;border:1px solid var(--border-color);border-radius:10px;margin-bottom:8px;overflow:hidden;transition:all 0.3s}
        .facility-group-card .group-header{display:flex;justify-content:space-between;align-items:center;padding:8px 14px;cursor:pointer;background:#f8fafc;transition:background 0.2s;user-select:none}
        .facility-group-card .group-header:hover{background:#f0f4ff}
        .facility-group-card .group-header .group-title{font-weight:600;font-size:0.85rem;color:var(--text-dark);display:flex;align-items:center;gap:8px}
        .facility-group-card .group-header .group-title i{color:var(--primary-color);width:18px}
        .facility-group-card .group-header .group-badge{font-size:0.7rem;padding:2px 10px;border-radius:12px;background:#e2e8f0;color:var(--text-muted)}
        .facility-group-card .group-header .group-toggle{transition:transform 0.3s}
        .facility-group-card .group-header .group-toggle.open{transform:rotate(180deg)}
        .facility-group-card .group-body{max-height:0;overflow:hidden;transition:max-height 0.3s ease}
        .facility-group-card .group-body.open{max-height:500px}
        .facility-group-card .group-body-inner{padding:8px 14px 14px}
        .facility-group-card .group-body-inner .allocation-item{margin-bottom:4px;border:none;border-bottom:1px solid var(--border-color);border-radius:0;padding:6px 0}
        .facility-group-card .group-body-inner .allocation-item:last-child{border-bottom:none}
        .facility-group-card .group-body-inner .allocation-item .alloc-name{font-size:0.8rem}
        .facility-group-card .group-body-inner .allocation-item .alloc-stats{font-size:0.65rem}
        .facility-group-card .group-body-inner .allocation-item .alloc-qty{width:50px;font-size:0.7rem;padding:2px 4px}
        .floor-amenity-badge{display:inline-block;background:#f0f4ff;border:1px solid rgba(67,97,238,0.2);border-radius:20px;padding:2px 12px;font-size:0.7rem;font-weight:600;color:var(--primary-color);margin:2px 4px 2px 0}
        .floor-amenity-badge i{margin-right:4px}
        .floor-facility-badge{display:inline-block;background:#fef3c7;border:1px solid #fde68a;border-radius:20px;padding:2px 12px;font-size:0.7rem;font-weight:600;color:#92400e;margin:2px 4px 2px 0}
        .floor-facility-badge i{margin-right:4px}
        .block-selector-card{background:#f8fafc;border:2px solid var(--border-color);border-radius:14px;padding:1.25rem;margin-bottom:1.5rem;transition:all 0.3s}
        .block-selector-card:hover{border-color:var(--primary-color)}
        .block-selector-card .selector-header{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:0.75rem}
        .block-selector-card .selector-header h4{margin:0;font-size:0.95rem;font-weight:700;color:var(--text-dark);display:flex;align-items:center;gap:8px}
        .block-selector-card .selector-header h4 i{color:var(--primary-color)}
        .block-selector-card .block-count-badge{background:var(--primary-gradient);color:white;padding:4px 14px;border-radius:20px;font-size:0.75rem;font-weight:600}
        .block-selector-card .form-group{margin-bottom:0}
        .block-selector-card .form-group select{background:white}
        .block-detail-panel{display:none;animation:fadeIn 0.3s ease}
        .block-detail-panel.active{display:block}
        @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        .floor-selector-card{background:#f8fafc;border:2px solid var(--border-color);border-radius:14px;padding:1.25rem;margin-bottom:1.5rem;transition:all 0.3s}
        .floor-selector-card:hover{border-color:var(--primary-color)}
        .floor-selector-card .selector-header{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:0.75rem}
        .floor-selector-card .selector-header h4{margin:0;font-size:0.95rem;font-weight:700;color:var(--text-dark);display:flex;align-items:center;gap:8px}
        .floor-selector-card .selector-header h4 i{color:var(--primary-color)}
        .floor-selector-card .floor-count-badge{background:var(--primary-gradient);color:white;padding:4px 14px;border-radius:20px;font-size:0.75rem;font-weight:600}
        .room-selector-card{background:#f8fafc;border:2px solid var(--border-color);border-radius:14px;padding:1.25rem;margin-bottom:1.5rem;transition:all 0.3s}
        .room-selector-card:hover{border-color:var(--primary-color)}
        .room-selector-card .selector-header{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:0.75rem}
        .room-selector-card .selector-header h4{margin:0;font-size:0.95rem;font-weight:700;color:var(--text-dark);display:flex;align-items:center;gap:8px}
        .room-selector-card .selector-header h4 i{color:var(--primary-color)}
        .floor-detail-panel{display:none;animation:fadeIn 0.3s ease}
        .floor-detail-panel.active{display:block}
        .room-detail-panel{display:none;animation:fadeIn 0.3s ease}
        .room-detail-panel.active{display:block}
        .selected-item-badge{display:inline-flex;align-items:center;gap:6px;background:var(--success-gradient);color:white;padding:4px 14px;border-radius:20px;font-size:0.7rem;font-weight:600}
        .selected-item-badge i{font-size:0.6rem}
        .empty-state-msg{text-align:center;padding:2rem;color:var(--text-muted);background:#f8fafc;border-radius:12px;border:1px dashed var(--border-color)}
        .empty-state-msg i{font-size:2rem;display:block;margin-bottom:0.5rem;color:var(--primary-color);opacity:0.5}
        .room-amenities-selection{background:#f8fafc;border:1px solid var(--border-color);border-radius:12px;padding:1rem;margin-top:1rem}
        .room-amenities-selection .amenities-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:0.5rem;margin-top:0.5rem}
        .room-amenities-selection .amenity-item{display:flex;align-items:center;gap:0.5rem;padding:0.5rem;background:white;border:1px solid var(--border-color);border-radius:8px;transition:all 0.2s}
        .room-amenities-selection .amenity-item:hover{border-color:var(--primary-color)}
        .room-amenities-selection .amenity-item.selected{border-color:var(--primary-color);background:#f0f4ff}
        .room-amenities-selection .amenity-item input[type="checkbox"]{width:16px;height:16px;accent-color:var(--primary-color);cursor:pointer;flex-shrink:0}
        .room-amenities-selection .amenity-item label{font-size:0.85rem;font-weight:500;color:var(--text-dark);cursor:pointer;flex:1}
        .room-amenities-selection .amenity-item .amenity-qty{width:50px;padding:2px 6px;border:1px solid var(--border-color);border-radius:4px;font-size:0.75rem;text-align:center}
        .room-amenities-selection .amenity-item .amenity-qty:focus{outline:none;border-color:var(--primary-color)}
        .room-amenities-selection .amenity-item .amenity-qty:disabled{opacity:0.5;background:#f1f5f9}
        .room-amenities-selection .amenity-category{margin-bottom:0.75rem}
        .room-amenities-selection .amenity-category-title{font-weight:600;font-size:0.8rem;color:var(--text-muted);margin-bottom:0.25rem;display:flex;align-items:center;gap:6px}
        .room-amenities-selection .amenity-category-title i{color:var(--primary-color)}
        .room-specs-tabs{display:flex;gap:8px;margin-bottom:1.5rem;flex-wrap:wrap;border-bottom:2px solid var(--border-color);padding-bottom:0.5rem}
        .room-specs-tabs .tab-btn{padding:8px 20px;border:2px solid var(--border-color);border-radius:8px;background:white;cursor:pointer;font-weight:600;font-size:0.85rem;color:var(--text-muted);transition:all 0.3s}
        .room-specs-tabs .tab-btn:hover{border-color:var(--primary-color);color:var(--primary-color)}
        .room-specs-tabs .tab-btn.active{background:var(--primary-gradient);color:white;border-color:var(--primary-color)}
        .room-specs-tab-content{display:none;animation:fadeIn 0.3s ease}
        .room-specs-tab-content.active{display:block}
        .spec-group-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.25rem}
        .spec-group-card{background:#f8fafc;padding:1.25rem;border-radius:14px;border:1px solid var(--border-color);transition:all 0.3s}
        .spec-group-card:hover{border-color:var(--primary-color);box-shadow:0 2px 10px rgba(67,97,238,0.08)}
        .spec-group-card h4{color:var(--text-dark);margin-bottom:0.75rem;font-size:0.9rem;display:flex;align-items:center;gap:8px;font-weight:700}
        .spec-group-card h4 i{color:var(--primary-color)}
        .spec-row{display:grid;grid-template-columns:1fr 1fr;gap:0.75rem}
        .spec-row .form-group{margin-bottom:0}
        .spec-row .form-group label{font-size:0.8rem;font-weight:500;color:var(--text-muted)}
        .spec-row .form-group input,.spec-row .form-group select{padding:8px 12px;font-size:0.85rem}
        .room-type-badge{display:inline-block;padding:4px 12px;border-radius:20px;font-size:0.7rem;font-weight:600}
        .room-type-badge.hotel{background:#dbeafe;color:#1e40af}
        .room-type-badge.office{background:#dcfce7;color:#166534}
        .room-type-badge.classroom{background:#fef3c7;color:#92400e}
        .room-type-badge.residential{background:#fce7f3;color:#9d174d}
        .room-type-badge.commercial{background:#e0e7ff;color:#3730a3}
        .room-type-badge.other{background:#f1f5f9;color:#475569}
        .room-type-badge.cafeteria{background:#fef3c7;color:#92400e}
        .room-type-badge.computer_lab{background:#dbeafe;color:#1e40af}
        .room-type-badge.seminar_hall{background:#e0e7ff;color:#3730a3}
        .room-type-badge.conference_room{background:#c7d2fe;color:#3730a3}
        .room-type-badge.stationery_shop{background:#fce7f3;color:#9d174d}
        .room-type-badge.prayer_room{background:#d1fae5;color:#065f46}
        .room-type-badge.staff_room{background:#fef3c7;color:#92400e}
        .room-type-badge.admin_office{background:#dbeafe;color:#1e40af}
        .room-type-badge.store_room{background:#f1f5f9;color:#475569}
        .room-type-selector-detailed{display:grid;grid-template-columns:repeat(3,1fr);gap:0.5rem}
        @media(max-width:768px){.room-type-selector-detailed{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:480px){.room-type-selector-detailed{grid-template-columns:1fr 1fr}}
        .room-type-selector-detailed .room-type-option{padding:0.6rem 0.5rem;border:2px solid var(--border-color);border-radius:10px;text-align:center;cursor:pointer;transition:all 0.3s;background:white;font-weight:500;color:var(--text-muted);font-size:0.8rem}
        .room-type-selector-detailed .room-type-option i{display:block;font-size:1.2rem;margin-bottom:4px;color:var(--primary-color)}
        .room-type-selector-detailed .room-type-option:hover{border-color:var(--primary-color);transform:translateY(-2px)}
        .room-type-selector-detailed .room-type-option.selected{background:var(--primary-gradient);color:white;border-color:var(--primary-color)}
        .room-type-selector-detailed .room-type-option.selected i{color:white}
        .room-type-selector-detailed .room-type-option .room-type-badge{display:block;margin-top:3px;font-size:0.55rem}
        .washroom-detail-card{background:#f8fafc;border:2px solid var(--border-color);border-radius:12px;padding:1rem;margin-bottom:0.75rem;position:relative;transition:all 0.3s}
        .washroom-detail-card:hover{border-color:var(--primary-color)}
        .washroom-detail-card .washroom-badge{position:absolute;top:-10px;left:12px;background:var(--primary-gradient);color:white;padding:2px 12px;border-radius:12px;font-size:0.65rem;font-weight:600}
        .washroom-detail-card .washroom-type-badge{display:inline-block;padding:2px 10px;border-radius:12px;font-size:0.65rem;font-weight:600;margin-bottom:0.5rem}
        .washroom-detail-card .washroom-type-badge.male{background:#dbeafe;color:#1e40af}
        .washroom-detail-card .washroom-type-badge.female{background:#fce7f3;color:#9d174d}
        .washroom-detail-card .washroom-type-badge.unisex{background:#dcfce7;color:#166534}
        .washroom-detail-card .washroom-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:0.5rem;margin-top:0.5rem}
        .washroom-detail-card .washroom-row .form-group{margin-bottom:0}
        .washroom-detail-card .washroom-row .form-group label{font-size:0.75rem;font-weight:500;color:var(--text-muted);margin-bottom:0.2rem}
        .washroom-detail-card .washroom-row .form-group input,.washroom-detail-card .washroom-row .form-group select{padding:4px 8px;font-size:0.8rem;border:1px solid var(--border-color);border-radius:6px;width:100%}
        .washroom-detail-card .washroom-row .form-group input:focus,.washroom-detail-card .washroom-row .form-group select:focus{outline:none;border-color:var(--primary-color);box-shadow:0 0 0 3px rgba(67,97,238,0.1)}
        .washroom-detail-card .washroom-remove-btn{margin-top:0.5rem;text-align:right}
        .washroom-detail-card .washroom-remove-btn button{padding:3px 10px;font-size:0.7rem}
        .washroom-summary-badge{display:inline-block;background:#f0f4ff;border:1px solid rgba(67,97,238,0.2);border-radius:20px;padding:2px 12px;font-size:0.65rem;font-weight:600;color:var(--primary-color);margin:2px 4px 2px 0}
        .washroom-summary-badge i{margin-right:4px}
        .washroom-summary-badge.male{background:#dbeafe;color:#1e40af;border-color:#93c5fd}
        .washroom-summary-badge.female{background:#fce7f3;color:#9d174d;border-color:#f9a8d4}
        .washroom-summary-badge.unisex{background:#dcfce7;color:#166534;border-color:#86efac}
        .area-deduction-summary{background:#f8fafc;border:1px solid var(--border-color);border-radius:8px;padding:10px 14px;margin:8px 0;font-size:0.85rem}
        .area-deduction-summary .deduction-item{display:flex;justify-content:space-between;padding:3px 0;border-bottom:1px dashed #e2e8f0}
        .area-deduction-summary .deduction-item:last-child{border-bottom:none}
        .area-deduction-summary .total-row{font-weight:700;color:var(--text-dark);margin-top:5px;padding-top:5px;border-top:2px solid var(--border-color)}
        .area-deduction-summary .positive{color:#059669}
        .area-deduction-summary .negative{color:#dc2626}
        .balcony-card{background:#f0fdf4;border:2px solid #86efac;border-radius:12px;padding:1rem;margin-bottom:0.75rem;position:relative}
        .balcony-card .balcony-badge{position:absolute;top:-10px;left:12px;background:var(--success-gradient);color:white;padding:2px 12px;border-radius:12px;font-size:0.65rem;font-weight:600}
        .floor-card.basement{border-color:#f59e0b;background:#fffbeb}
        .floor-card.basement .card-badge{background:var(--warning-gradient)}
        .store-room-selector{background:#f8fafc;border:1px solid var(--border-color);border-radius:12px;padding:1rem;margin-top:0.5rem}
        .store-room-selector .store-item{display:flex;align-items:center;gap:0.5rem;padding:0.4rem 0.5rem;background:white;border:1px solid var(--border-color);border-radius:6px;margin-bottom:4px}
        .store-room-selector .store-item input[type="checkbox"]{width:16px;height:16px;accent-color:var(--primary-color);cursor:pointer}
        .store-room-selector .store-item label{font-size:0.8rem;font-weight:500;cursor:pointer;flex:1}
        .store-room-selector .store-item .store-area{font-size:0.7rem;color:var(--text-muted)}
        .room-detail-card{background:white;border:2px solid var(--border-color);border-radius:16px;padding:1.5rem;margin-top:1rem}
        .room-detail-card .card-row{display:grid;gap:1rem;margin-bottom:1rem}
        .room-detail-card .form-group{margin-bottom:0}
        .room-detail-card .form-group label{font-size:0.85rem;font-weight:600;color:var(--text-dark);margin-bottom:0.3rem}
        .room-detail-card .form-control{padding:8px 12px;font-size:0.9rem}
        .room-type-selector-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:0.5rem;margin-top:0.5rem}
        .room-type-selector-grid .room-type-option{padding:0.5rem;border:2px solid var(--border-color);border-radius:8px;text-align:center;cursor:pointer;transition:all 0.3s;background:white;font-weight:500;color:var(--text-muted);font-size:0.8rem}
        .room-type-selector-grid .room-type-option:hover{border-color:var(--primary-color)}
        .room-type-selector-grid .room-type-option.selected{background:var(--primary-gradient);color:white;border-color:var(--primary-color)}
        .room-type-selector-grid .room-type-option .room-type-badge{display:block;margin-top:3px;font-size:0.55rem}
        @media(max-width:768px){.header{flex-direction:column;gap:1rem;text-align:center}.form-card{padding:1.5rem}.form-actions{flex-direction:column}.block-type-grid{grid-template-columns:1fr}.amenities-grid{grid-template-columns:repeat(2,1fr)}.dynamic-card .card-row{grid-template-columns:1fr}.campus-info-header{flex-direction:column;align-items:flex-start}.timeline-stepper{flex-direction:column;gap:1rem}.step-connector{display:none}}
        @media(max-width:480px){.amenities-grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-layer-group"></i> Block, Floor & Room Setup</h1>
                <p>Configure building blocks, floors, and rooms for your campus</p>
            </div>
            <div>
                <a href="{{ route('campus.infrastructure', ['campus' => $campus->fincap_merchant_id ?? $campus->id ?? '']) }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Back to Campus Setup
</a>
                <a href="{{ route('buildings.list') }}" class="btn btn-secondary">
                    <i class="fas fa-university"></i> View Campuses
                </a>
            </div>
        </div>

        <!-- Timeline Stepper -->
        <div class="timeline-stepper">
            <div class="step-item" onclick="goToStep(2)" id="stepItem2">
                <div class="step-circle active" id="stepCircle2">1</div>
                <span class="step-label active-label" id="stepLabel2">Building Blocks</span>
            </div>
            <div class="step-connector" id="stepConnector2"></div>
            <div class="step-item" onclick="goToStep(3)" id="stepItem3">
                <div class="step-circle" id="stepCircle3">2</div>
                <span class="step-label" id="stepLabel3">Add Floors</span>
            </div>
            <div class="step-connector" id="stepConnector3"></div>
            <div class="step-item" onclick="goToStep(4)" id="stepItem4">
                <div class="step-circle" id="stepCircle4">3</div>
                <span class="step-label" id="stepLabel4">Add Rooms</span>
            </div>
        </div>

        <!-- ==================== STEP 2: BUILDING BLOCKS ==================== -->
        <div class="step-content active" id="step2Content">
            <div class="form-card">
                <h2><i class="fas fa-cubes"></i> Step 1: Building Blocks</h2>
                <div class="form-group">
                    <label for="buildingId" class="form-label">Select Building <span style="color:#dc3545;">*</span></label>
                    <select id="buildingId" class="form-control" onchange="onBuildingSelect()">
                        <option value="">Select Building</option>
                        @if(isset($buildings) && count($buildings) > 0)
                            @foreach($buildings as $building)
                                <option value="{{ $building->id }}">{{ $building->name }} ({{ $building->code }})</option>
                            @endforeach
                        @endif
                    </select>
                    @if(!isset($buildings) || count($buildings) == 0)
                        <div class="alert-error" style="margin-top:10px;">
                            <i class="fas fa-exclamation-circle"></i>
                            <div><strong>No buildings found!</strong><p style="margin:5px 0 0 0;font-size:0.85rem;">Please save Campus Infrastructure in Step 1 first.</p></div>
                        </div>
                    @endif
                </div>

                <div id="campusInfoDisplay" style="display:none;">
                    <div class="campus-info-header">
                        <div class="campus-name-section">
                            <div>
                                <div class="campus-name"><i class="fas fa-university"></i> <span id="displayCampusName">-</span></div>
                                <span class="campus-code" id="displayCampusCode">-</span>
                            </div>
                        </div>
                        <div class="area-display">
                            <span class="area-badge"><i class="fas fa-vector-square"></i> Total: <strong id="displayTotalArea">-</strong></span>
                            <span class="remaining-badge" id="remainingBadge"><i class="fas fa-chart-pie"></i> Remaining: <strong id="displayRemainingArea">-</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Block Type Selection -->
                <div id="blockTypeSection" style="display:none;">
                    <h3 style="margin-top:1.5rem;"><i class="fas fa-question-circle"></i> Block Configuration Type</h3>
                    <p style="color:var(--text-muted);font-size:0.85rem;">Number of blocks from campus: <strong id="configuredBlocksCount">-</strong></p>
                    <div class="block-type-grid">
                        <div class="block-type-card" id="singleBlockCard" onclick="selectBlockType('single')">
                            <div class="card-icon single"><i class="fas fa-building"></i></div>
                            <h4>Single Block</h4>
                            <p>Entire building as one block</p>
                            <span class="recommended-badge"><i class="fas fa-star"></i> Recommended</span>
                        </div>
                        <div class="block-type-card" id="multiBlockCard" onclick="selectBlockType('multi')">
                            <div class="card-icon multi"><i class="fas fa-cubes"></i></div>
                            <h4>Multiple Blocks</h4>
                            <p>Divide into multiple blocks</p>
                            <span class="recommended-badge" style="background:var(--primary-gradient);"><i class="fas fa-th-large"></i> Large buildings</span>
                        </div>
                    </div>
                </div>

                <!-- Block Detail Panel -->
                <div id="step2Section" class="hidden">
                    <div class="section-divider" style="margin-top:0.5rem;">
                        <h3 id="step2Title"><i class="fas fa-cubes"></i> Blocks</h3>
                    </div>

                    <div id="singleBlockMessage" class="hidden" style="background:#f0fdf4;border:1px solid #6ee7b7;border-radius:12px;padding:1rem;margin-bottom:1rem;color:#065f46;font-size:0.85rem;">
                        <i class="fas fa-info-circle"></i> Block area deducted from campus. Original campus values preserved.
                    </div>
                    <div id="multiBlockMessage" class="hidden" style="background:#f0f4ff;border:1px solid rgba(67,97,238,0.2);border-radius:12px;padding:1rem;margin-bottom:1rem;color:var(--primary-color);font-size:0.85rem;">
                        <i class="fas fa-info-circle"></i> Changing block area unit converts displayed values only.
                    </div>

                    <!-- Global Allocation Summary -->
                    <div id="globalAllocSummary" class="allocation-tracker" style="display:none;margin-bottom:1.5rem;">
                        <div class="allocation-header">
                            <h4><i class="fas fa-chart-bar"></i> Campus Resource Allocation</h4>
                            <span class="remaining-tag available" id="globalAllocTag"><i class="fas fa-check-circle"></i> Resources Available</span>
                        </div>
                        <div id="globalAllocItems" style="display:flex;flex-wrap:wrap;gap:8px;"></div>
                        <div class="allocation-summary">
                            <span class="stat-item"><i class="fas fa-cubes"></i> Total Blocks: <strong id="globalBlockCount">0</strong></span>
                            <span class="stat-item"><i class="fas fa-check-circle" style="color:#10b981;"></i> Allocated: <strong id="globalAllocatedCount">0</strong></span>
                            <span class="stat-item"><i class="fas fa-clock" style="color:#f59e0b;"></i> Remaining: <strong id="globalRemainingCount">0</strong></span>
                        </div>
                    </div>

                    <!-- Block Selector Dropdown -->
                    <div class="block-selector-card" id="blockSelectorCard" style="display:none;">
                        <div class="selector-header">
                            <h4><i class="fas fa-list"></i> Select Block to Configure</h4>
                            <span class="block-count-badge" id="blockCountBadge">0 blocks</span>
                        </div>
                        <div class="form-group">
                            <label for="blockSelectorDropdown" class="form-label">Choose a Block <span style="color:#dc3545;">*</span></label>
                            <select id="blockSelectorDropdown" class="form-control" onchange="onBlockSelectorChange()">
                                <option value="">-- Select a block --</option>
                            </select>
                        </div>
                        <div style="margin-top:0.75rem;display:flex;gap:10px;flex-wrap:wrap;">
                            <span class="selected-item-badge" id="selectedBlockBadge" style="display:none;">
                                <i class="fas fa-check-circle"></i> <span id="selectedBlockName">-</span>
                            </span>
                            <button type="button" class="btn-outline-primary btn-sm" onclick="addBlockFromDropdown()" id="addBlockFromDropdownBtn">
                                <i class="fas fa-plus"></i> Add New Block
                            </button>
                            <button type="button" class="btn-outline-danger btn-sm" onclick="removeSelectedBlock()" id="removeSelectedBlockBtn" style="display:none;">
                                <i class="fas fa-trash"></i> Remove Block
                            </button>
                        </div>
                    </div>

                    <!-- Block Detail Panel -->
                    <div id="blockDetailPanel" class="block-detail-panel">
                        <div id="blocks-container"></div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="goToStep(1)"><i class="fas fa-arrow-left"></i> Back</button>
                        <button type="button" class="btn btn-success" onclick="saveAllBlocksAndContinue()"><i class="fas fa-save"></i> Save & Continue to Floors</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== STEP 3: FLOORS ==================== -->
        <div class="step-content" id="step3Content">
            <div class="form-card">
                <h2><i class="fas fa-layer-group"></i> Step 2: Add Floors</h2>

                <div class="form-group">
                    <label for="floorBuildingId" class="form-label">Building <span style="color:#dc3545;">*</span></label>
                    <select id="floorBuildingId" class="form-control" onchange="loadBlocksForFloors()">
                        <option value="">Select Building</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}">{{ $building->name }} ({{ $building->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="blockSelect" class="form-label">Select Block <span style="color:#dc3545;">*</span></label>
                    <select id="blockSelect" class="form-control" onchange="loadBlockForFloors()">
                        <option value="">Select a building block</option>
                    </select>
                </div>

                <div id="blockAllocSummaryForFloors" style="display:none; background:#f8fafc; padding:1rem; border-radius:12px; border:1px solid var(--border-color); margin-bottom:1rem;">
                    <h4 style="font-size:0.9rem; font-weight:700; color:var(--text-dark);"><i class="fas fa-cubes" style="color:var(--primary-color);"></i> Block Resources Available for Floors</h4>
                    <div id="blockAllocSummaryDetails" style="display:flex; flex-wrap:wrap; gap:8px; margin-top:8px;"></div>
                </div>

                <div id="existingFloorsSection" style="display:none;">
                    <div class="section-divider" style="margin-top:0.5rem;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <h3 style="margin:0;"><i class="fas fa-check-circle" style="color:#10b981;"></i> Existing Floors</h3>
                            <span class="existing-count-badge" id="existingFloorsCount">0 floors</span>
                        </div>
                    </div>
                    <div id="existingFloorsContainer"></div>
                    <div id="noExistingFloors" class="no-floors-message" style="display:none;"><i class="fas fa-info-circle"></i> No existing floors found.</div>
                </div>

                <!-- Floor Selector Dropdown -->
                <div class="floor-selector-card" id="floorSelectorCard" style="display:none;">
                    <div class="selector-header">
                        <h4><i class="fas fa-list"></i> Select Floor to Configure</h4>
                        <span class="floor-count-badge" id="floorCountBadge">0 floors</span>
                    </div>
                    <div class="form-group">
                        <label for="floorSelectorDropdown" class="form-label">Choose a Floor <span style="color:#dc3545;">*</span></label>
                        <select id="floorSelectorDropdown" class="form-control" onchange="onFloorSelectorChange()">
                            <option value="">-- Select a floor --</option>
                        </select>
                    </div>
                    <div style="margin-top:0.75rem;display:flex;gap:10px;flex-wrap:wrap;">
                        <span class="selected-item-badge" id="selectedFloorBadge" style="display:none;">
                            <i class="fas fa-check-circle"></i> <span id="selectedFloorName">-</span>
                        </span>
                        <button type="button" class="btn-outline-primary btn-sm" onclick="addFloorFromDropdown()" id="addFloorFromDropdownBtn">
                            <i class="fas fa-plus"></i> Add New Floor
                        </button>
                        <button type="button" class="btn-outline-danger btn-sm" onclick="removeSelectedFloor()" id="removeSelectedFloorBtn" style="display:none;">
                            <i class="fas fa-trash"></i> Remove Floor
                        </button>
                    </div>
                </div>

                <!-- Floor Detail Panel -->
                <div id="floorDetailPanel" class="floor-detail-panel">
                    <div id="floors-container"></div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="goToStep(2)"><i class="fas fa-arrow-left"></i> Back to Blocks</button>
                    <button type="button" class="btn btn-success" onclick="saveAllFloorsAndContinue()" id="saveFloorsBtn"><i class="fas fa-save"></i> Save & Continue to Rooms</button>
                </div>
            </div>
        </div>

        <!-- ==================== STEP 4: ROOMS ==================== -->
        <div class="step-content" id="step4Content">
            <div class="form-card">
                <h2><i class="fas fa-door-open"></i> Step 3: Add Rooms</h2>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="roomBuildingId" class="form-label">Building <span style="color:#dc3545;">*</span></label>
                            <select id="roomBuildingId" class="form-control" onchange="loadBlocksForRooms()">
                                <option value="">Select Building</option>
                                @foreach($buildings as $building)
                                    <option value="{{ $building->id }}">{{ $building->name }} ({{ $building->code }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="roomBlockSelect" class="form-label">Select Block <span style="color:#dc3545;">*</span></label>
                            <select id="roomBlockSelect" class="form-control" onchange="loadFloorsForRooms()">
                                <option value="">Select a building block</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="roomFloorSelect" class="form-label">Select Floor <span style="color:#dc3545;">*</span></label>
                            <select id="roomFloorSelect" class="form-control" onchange="loadExistingRoomsAndFloorAmenities()">
                                <option value="">Select a floor</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="floorAmenitiesDisplay" style="display:none; background:#f8fafc; border:1px solid var(--border-color); border-radius:12px; padding:1rem; margin-bottom:1rem;">
                    <h4 style="font-size:0.9rem; font-weight:700; color:var(--text-dark);"><i class="fas fa-concierge-bell" style="color:var(--primary-color);"></i> Floor Amenities & Facilities</h4>
                    <div id="floorAmenitiesList" style="margin-top:8px; display:flex; flex-wrap:wrap; gap:4px;"></div>
                </div>

                <div id="existingRoomsSection" style="display:none;">
                    <div class="section-divider" style="margin-top:0.5rem;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <h3 style="margin:0;"><i class="fas fa-check-circle" style="color:#10b981;"></i> Existing Rooms</h3>
                            <span class="existing-count-badge" id="existingRoomsCount">0 rooms</span>
                        </div>
                    </div>
                    <div id="existingRoomsContainer"></div>
                    <div id="noExistingRooms" class="no-floors-message" style="display:none;"><i class="fas fa-info-circle"></i> No existing rooms found.</div>
                </div>

                <!-- Room Selector Dropdown -->
                <div class="room-selector-card" id="roomSelectorCard" style="display:none;">
                    <div class="selector-header">
                        <h4><i class="fas fa-list"></i> Select Room to Configure</h4>
                        <span class="existing-count-badge" id="roomCountBadge">0 rooms</span>
                    </div>
                    <div class="form-group">
                        <label for="roomSelectorDropdown" class="form-label">Choose a Room <span style="color:#dc3545;">*</span></label>
                        <select id="roomSelectorDropdown" class="form-control" onchange="onRoomSelectorChange()">
                            <option value="">-- Select a room --</option>
                        </select>
                    </div>
                    <div style="margin-top:0.75rem;display:flex;gap:10px;flex-wrap:wrap;">
                        <span class="selected-item-badge" id="selectedRoomBadge" style="display:none;">
                            <i class="fas fa-check-circle"></i> <span id="selectedRoomName">-</span>
                        </span>
                        <button type="button" class="btn-outline-primary btn-sm" onclick="addRoomFromDropdown()" id="addRoomFromDropdownBtn">
                            <i class="fas fa-plus"></i> Add New Room
                        </button>
                        <button type="button" class="btn-outline-danger btn-sm" onclick="removeSelectedRoom()" id="removeSelectedRoomBtn" style="display:none;">
                            <i class="fas fa-trash"></i> Remove Room
                        </button>
                    </div>
                </div>

                <!-- Room Detail Panel -->
                <div id="roomDetailPanel" class="room-detail-panel">
                    <div id="roomDetailContainer"></div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="goToStep(3)"><i class="fas fa-arrow-left"></i> Back to Floors</button>
                    <button type="button" class="btn btn-success" onclick="saveAllRoomsAndContinue()" id="saveRoomsBtn"><i class="fas fa-save"></i> Save All Rooms</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"><i class="fas fa-check-circle"></i><span id="toast-message"></span></div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"><i class="fas fa-check-circle"></i><span id="toast-message"></span></div>

    <script>
    // ===== COMPLETE JAVASCRIPT (Steps 2-4) =====


    @php
        $campusData = null;
        if (isset($campus)) {
            // The data is already decoded in the controller, so we just pass it directly
            $campusData = [
                'id' => $campus->fincap_merchant_id,
                'name' => $campus->name,
                'code' => $campus->fincap_merchant_id,
                'area_value' => $campus->area_value ?? 0,
                'area_unit' => $campus->area_unit ?? 'sq_ft',
                'number_of_blocks' => $campus->number_of_blocks ?? 1,
                'additional_areas' => $campus->additional_areas ?? [],
                'gates' => $campus->gates ?? [],
                'amenities' => $campus->amenities ?? [],
                'custom_amenities' => $campus->custom_amenities ?? [],
                'facilities' => $campus->facilities ?? [],
            ];
        }
    @endphp
    const CAMPUS_DATA = @json($campusData);

    const API_BASE_URL = '{{ url('/') }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    let currentStep = 2;

    // Multi‑entry facility counters
    let areaCounter = 1, gateCounter = 1, customAmenityCounter = 1;
    let campusAreaUnit = 'sq_ft', configuredNumberOfBlocks = 1;
    let campusFacilities = { hasWarehouse: false, hasStore: false, hasHostel: false };
    let blockCounter = 0, blockAreaCounters = {}, blockGateCounters = {}, blockCustomAmenityCounters = {};
    let buildingData = null, selectedBlockType = null;
    let originalCampusAreaValue = 0, originalCampusAreaUnit = 'sq_ft', blockDisplayUnits = {};
    let floorCounter = 1, floorAreaCounters = {1:1}, floorGateCounters = {1:1}, floorCustomAmenityCounters = {1:1};
    let roomSelectedType = 'classroom';
    let currentBlockForFloors = null;
    let floorAmenityDataForRooms = {};

    // Block allocation tracking
    let blockAllocations = {};
    let campusAmenityTotals = {};
    let campusCustomAmenityTotals = {};
    let campusFacilityEntries = {};
    let blockIdCounter = 0;

    // Dropdown tracking
    let blockDataMap = {};
    let floorDataMap = {};
    let roomDataMap = {};
    let selectedBlockId = null;
    let selectedFloorId = null;
    let selectedRoomId = null;
    let existingBlockIds = [];
    let existingFloorIds = [];
    let existingRoomIds = [];

    // Floor amenities storage
    window.currentFloorAmenities = { amenities: {}, facilities: [] };

    // Unique ID map
    let facilityIdMap = {};

    // Room types (fetched from backend)
    let roomTypeOptions = [];
    let customAmenityTypes = {};

    // ----- UUID GENERATION -----
    function generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0,
                v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
    function generateUniqueId() {
        return Date.now().toString(36) + Math.random().toString(36).substr(2, 9);
    }

    // ----- AREA CONVERSION -----
    const areaConversionToSqFt = {'sq_ft':1,'sq_m':10.7639,'sq_yd':9,'gaj':9,'marla':272.25,'kanal':5445,'acre':43560,'hectare':107639,'bigha':27000,'biswa':1350};
    function convertArea(value, fromUnit, toUnit) { if(!value||isNaN(value)) return 0; return (parseFloat(value)*(areaConversionToSqFt[fromUnit]||1))/(areaConversionToSqFt[toUnit]||1); }
    function getUnitDisplayName(unit) { const n={'sq_ft':'Sq. Ft.','sq_m':'Sq. M.','sq_yd':'Sq. Yd.','gaj':'Gaj','marla':'Marla','kanal':'Kanal','acre':'Acre','hectare':'Hectare','bigha':'Bigha','biswa':'Biswa'}; return n[unit]||unit; }
    function generateUnitOptions(selected) { const u=['sq_ft','sq_m','sq_yd','gaj','marla','kanal','acre','hectare','bigha','biswa']; const l={sq_ft:'Sq. Ft.',sq_m:'Sq. M.',sq_yd:'Sq. Yd.',gaj:'Gaj',marla:'Marla',kanal:'Kanal',acre:'Acre',hectare:'Hectare',bigha:'Bigha',biswa:'Biswa'}; return u.map(x=>`<option value="${x}" ${x===selected?'selected':''}>${l[x]}</option>`).join(''); }
    function formatUnit(u) { return getUnitDisplayName(u); }
    function escapeHtml(t) { if(!t) return ''; const m={'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}; return String(t).replace(/[&<>"']/g,c=>m[c]); }
    function parseJsonSafe(s) { if(!s) return null; if(typeof s==='object') return s; try{return JSON.parse(s);}catch(e){return null;} }
    function removeCard(cardId) { const card=document.getElementById(cardId); if(card){card.style.opacity='0';card.style.transform='scale(0.95)';setTimeout(()=>card.remove(),300);} }
    function showToast(msg,type='success') { const t=document.getElementById('toast');document.getElementById('toast-message').textContent=msg;t.className='toast'+(type==='error'?' error':(type==='info'?' info':''));t.querySelector('i').className=type==='error'?'fas fa-exclamation-circle':(type==='info'?'fas fa-info-circle':'fas fa-check-circle');t.classList.add('show');setTimeout(()=>t.classList.remove('show'),3000); }
    function toggleFacilityDetails(detailsId) { const d=document.getElementById(detailsId); if(d) d.classList.toggle('show'); }
    function onCampusAreaUnitChange() { campusAreaUnit=document.getElementById('area-unit').value; document.querySelectorAll('#additional-areas-container .area-unit-select').forEach(s=>s.value=campusAreaUnit); updateAllCustomAmenityAreaUnits(); }

    // ----- AMENITY FUNCTIONS -----
    function toggleAmenityDetail(checkbox, key) {
        const detail = document.getElementById(`amenity-detail-${key}`);
        if (detail) { detail.style.display = checkbox.checked ? 'block' : 'none'; }
        updateAmenityHighlight();
    }
    function toggleAmenityCountSimple(checkbox, key) {
        const ci = document.getElementById(`amenity-count-${key}`);
        const cl = document.getElementById(`count-label-${key}`);
        if (checkbox.checked) { if (ci) ci.classList.add('show'); if (cl) cl.classList.add('show'); }
        else { if (ci) { ci.classList.remove('show'); ci.value = 1; } if (cl) cl.classList.remove('show'); }
        updateAmenityHighlight();
    }
    function toggleAmenityCount(cb, key) {
        const ci = document.getElementById(`amenity-count-${key}`);
        const cl = document.getElementById(`count-label-${key}`);
        if (cb.checked) { ci.classList.add('show'); cl.classList.add('show'); }
        else { ci.classList.remove('show'); cl.classList.remove('show'); ci.value = 1; }
        updateAmenityHighlight();
    }
    function toggleAcTypeDetails() {
        const acType = document.getElementById('amenity-ac-type')?.value;
        const centralDetails = document.getElementById('ac-central-details');
        const splitDetails = document.getElementById('ac-split-details');
        if (centralDetails) { centralDetails.style.display = (acType === 'central' || acType === 'both') ? 'block' : 'none'; }
        if (splitDetails) { splitDetails.style.display = (acType === 'split' || acType === 'both') ? 'block' : 'none'; }
    }
    function updateAmenityHighlight() {
        document.querySelectorAll('#amenitiesGrid .amenity-group').forEach(g => {
            g.classList.toggle('has-checked', Array.from(g.querySelectorAll('input[type="checkbox"]')).some(cb => cb.checked));
        });
    }

    // ===== STEP 2: BLOCKS =====
    function onBuildingSelect() {
        const bi = document.getElementById('buildingId').value;
        if (!bi) {
            document.getElementById('campusInfoDisplay').style.display = 'none';
            document.getElementById('blockTypeSection').style.display = 'none';
            document.getElementById('step2Section').classList.add('hidden');
            buildingData = null;
            return;
        }
        loadBuildingData();
    }

    async function loadBuildingData() {
        const bi = document.getElementById('buildingId').value;
        if (!bi) { showToast('Please select a building', 'error'); return; }
        document.getElementById('campusInfoDisplay').style.display = 'block';
        document.getElementById('displayCampusName').textContent = 'Loading...';
        document.getElementById('displayCampusCode').textContent = 'Loading...';
        try {
            const r = await fetch(`${API_BASE_URL}/buildings/${bi}`, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF_TOKEN } });
            if (!r.ok) throw new Error(`HTTP error! status: ${r.status}`);
            const result = await r.json();
            if (result.success && result.data) {
                buildingData = result.data;
                configuredNumberOfBlocks = buildingData.number_of_blocks || 1;
                originalCampusAreaValue = parseFloat(buildingData.area_value) || 0;
                originalCampusAreaUnit = buildingData.area_unit || 'sq_ft';
                campusAreaUnit = originalCampusAreaUnit;
                let facilities = {};
                if (buildingData.facilities) {
                    if (typeof buildingData.facilities === 'string') { try { facilities = JSON.parse(buildingData.facilities); } catch(e) { facilities = {}; } } else { facilities = buildingData.facilities || {}; }
                }
                campusFacilities.hasWarehouse = facilities.warehouse?.enabled || false;
                campusFacilities.hasStore = facilities.store_room?.enabled || false;
                let amenities = {};
                if (buildingData.amenities) {
                    if (typeof buildingData.amenities === 'string') { try { amenities = JSON.parse(buildingData.amenities); } catch(e) { amenities = {}; } } else { amenities = buildingData.amenities || {}; }
                }
                campusFacilities.hasHostel = amenities.hostel?.enabled || false;
                document.getElementById('configuredBlocksCount').textContent = configuredNumberOfBlocks;
                displayCampusInfo(buildingData);
                document.getElementById('campusInfoDisplay').style.display = 'block';
                document.getElementById('blockTypeSection').style.display = 'block';
                document.getElementById('step2Section').classList.add('hidden');
                if (buildingData.amenities) {
                    if (typeof buildingData.amenities === 'string') { try { amenities = JSON.parse(buildingData.amenities); } catch(e) { amenities = {}; } } else { amenities = buildingData.amenities || {}; }
                }
                if (facilities) {
                    campusFacilityEntries = {};
                    const facilityTypes = ['parking', 'playground', 'swimming_pool', 'clubhouse', 'warehouse', 'store_room', 'auditorium', 'washrooms'];
                    facilityTypes.forEach(type => {
                        if (facilities[type] && facilities[type].enabled) {
                            let entries = [];
                            if (type === 'clubhouse') { if (facilities[type].id) entries = [facilities[type]]; }
                            else if (type === 'washrooms') { entries = facilities[type].detailed_washrooms || facilities[type] || []; if (!Array.isArray(entries)) entries = []; }
                            else { entries = facilities[type].entries || facilities[type].basement || facilities[type].open || facilities[type].indoor || facilities[type].outdoor || []; if (!Array.isArray(entries)) entries = []; }
                            if (entries.length > 0) {
                                campusFacilityEntries[type] = entries.map(entry => { if (!entry.id) entry.id = generateUUID(); return entry; });
                            }
                        }
                    });
                }
                initCampusTotals(buildingData);
                autoSelectBlockType();
                showToast('Building data loaded successfully!', 'success');
            } else {
                showToast(result.message || 'Failed to load building data', 'error');
                document.getElementById('campusInfoDisplay').style.display = 'none';
                document.getElementById('blockTypeSection').style.display = 'none';
            }
        } catch (e) {
            console.error('Error loading building data:', e);
            showToast('Error loading building data: ' + (e.message || 'Unknown error'), 'error');
            document.getElementById('campusInfoDisplay').style.display = 'none';
            document.getElementById('blockTypeSection').style.display = 'none';
        }
    }

    function initCampusTotals(data) {
        let amenities = {};
        if (data.amenities) {
            if (typeof data.amenities === 'string') { try { amenities = JSON.parse(data.amenities); } catch(e) { amenities = {}; } } else { amenities = data.amenities || {}; }
        }
        if (Object.keys(amenities).length === 0) {
            const formAmenities = {};
            document.querySelectorAll('#amenitiesGrid .amenity-group input[type="checkbox"]').forEach(cb => {
                const key = cb.id.replace('amenity-', '');
                if (cb.checked) {
                    let count = 1;
                    const countInput = document.getElementById(`amenity-count-${key}`);
                    if (countInput) count = parseInt(countInput.value) || 1;
                    const detail = document.getElementById(`amenity-detail-${key}`);
                    if (detail && detail.style.display !== 'none') {
                        if (key === 'cctv') { const cams = document.getElementById('amenity-cctv-count'); if (cams) count = parseInt(cams.value) || 1; }
                        else if (key === 'elevator') { const elev = document.getElementById('amenity-elevator-count'); if (elev) count = parseInt(elev.value) || 1; }
                        else if (key === 'generator') { const gen = document.getElementById('amenity-generator-count'); if (gen) count = parseInt(gen.value) || 1; }
                        else if (key === 'ac') { const central = parseInt(document.getElementById('amenity-central-ac-count')?.value) || 0; const split = parseInt(document.getElementById('amenity-split-ac-count')?.value) || 0; count = central + split; }
                        else if (key === 'washroom') { const male = parseInt(document.getElementById('male-washroom-count')?.value) || 0; const female = parseInt(document.getElementById('female-washroom-count')?.value) || 0; const unisex = parseInt(document.getElementById('unisex-washroom-count')?.value) || 0; count = male + female + unisex; }
                        else if (key === 'wifi') { const ap = document.getElementById('amenity-wifi-count'); if (ap) count = parseInt(ap.value) || 1; }
                    }
                    if (count > 0) formAmenities[key] = count;
                }
            });
            ['stationery_shop', 'photocopy', 'laundry', 'tuck_shop'].forEach(key => {
                const cb = document.getElementById(`amenity-${key}`);
                if (cb && cb.checked) {
                    const ci = document.getElementById(`amenity-count-${key}`);
                    const count = ci ? parseInt(ci.value) || 1 : 1;
                    if (count > 0) formAmenities[key] = count;
                }
            });
            amenities = formAmenities;
        }
        campusAmenityTotals = {};
        const countKeys = ['wifi','cctv','elevator','generator','ac','washroom','cafeteria','tuck_shop','library','computer_lab','seminar_hall','conference_room','gym','medical_room','ambulance','atm','stationery_shop','photocopy','laundry','prayer_room','daycare','guest_room','staff_room','admin_office','hostel','drinking_water','heater','exhaust_fan','ups','solar_panel','fire_alarm','security_guard','biometric','escalator','ramp','lan','internet_lab'];
        countKeys.forEach(key => {
            let count = 0;
            if (typeof amenities === 'object' && amenities[key] !== undefined) {
                const val = amenities[key];
                if (typeof val === 'number') { count = val; }
                else if (typeof val === 'object' && val !== null) {
                    if (val.enabled) {
                        if (key === 'washroom') { count = (val.male_washrooms || 0) + (val.female_washrooms || 0) + (val.unisex_washrooms || 0); }
                        else { count = val.count || val.units || val.devices || val.panels || val.stations || val.rooms || val.offices || val.sensors || val.guards || val.access_points || val.cameras || 1; if (key === 'ac') count = (val.central_units || 0) + (val.split_units || 0); if (key === 'generator' && val.units) count = val.units; if (key === 'elevator' && val.count) count = val.count; if (key === 'cctv' && val.cameras) count = val.cameras; if (key === 'wifi' && val.access_points) count = val.access_points; }
                    }
                }
            }
            if (count > 0) campusAmenityTotals[key] = count;
        });
        campusCustomAmenityTotals = {};
        let customAmenities = [];
        if (data.custom_amenities) {
            if (typeof data.custom_amenities === 'string') { try { customAmenities = JSON.parse(data.custom_amenities); } catch(e) { customAmenities = []; } } else { customAmenities = data.custom_amenities || []; }
        }
        if (!Array.isArray(customAmenities) || customAmenities.length === 0) {
            const formCustom = [];
            document.querySelectorAll('#custom-amenities-container .custom-amenity-card').forEach(card => {
                const id = card.id.replace('custom-amenity-', '');
                const name = document.getElementById(`custom-amenity-name-${id}`)?.value?.trim();
                const qty = parseInt(document.getElementById(`custom-amenity-qty-${id}`)?.value) || 0;
                if (name && qty > 0) formCustom.push({ name, quantity: qty });
            });
            customAmenities = formCustom;
        }
        if (Array.isArray(customAmenities)) {
            customAmenities.forEach((item, idx) => {
                if (item && item.name && item.quantity) {
                    const key = 'custom_' + idx;
                    campusCustomAmenityTotals[key] = { id: item.id || generateUUID(), name: item.name, total: item.quantity };
                }
            });
        }
        let facilities = {};
        if (data.facilities) {
            if (typeof data.facilities === 'string') { try { facilities = JSON.parse(data.facilities); } catch(e) { facilities = {}; } } else { facilities = data.facilities || {}; }
        }
        const facilityTypes = ['parking','playground','swimming_pool','clubhouse','warehouse','store_room','auditorium','washrooms'];
        facilityTypes.forEach(type => {
            if (facilities[type] && facilities[type].enabled) {
                let entries = [];
                if (type === 'clubhouse') { if (facilities[type].id) entries = [facilities[type]]; }
                else if (type === 'washrooms') { entries = facilities[type].detailed_washrooms || facilities[type] || []; if (!Array.isArray(entries)) entries = []; }
                else { entries = facilities[type].entries || facilities[type].basement || facilities[type].open || facilities[type].indoor || facilities[type].outdoor || []; if (!Array.isArray(entries)) entries = []; }
                if (entries.length > 0) {
                    campusFacilityEntries[type] = entries.map(entry => { if (!entry.id) entry.id = generateUUID(); return entry; });
                }
            }
        });
        blockIdCounter = 0;
        blockAllocations = {};
        blockDataMap = {};
        existingBlockIds = [];
        if (data.blocks && Array.isArray(data.blocks)) {
            data.blocks.forEach(block => {
                const bid = blockIdCounter + 1;
                blockIdCounter = bid;
                blockDataMap[bid] = block;
                existingBlockIds.push(bid);
                blockDisplayUnits[bid] = block.area_unit || originalCampusAreaUnit;
                if (!blockAllocations[bid]) blockAllocations[bid] = {};
                if (block.allocated_amenities) {
                    Object.keys(block.allocated_amenities).forEach(key => {
                        blockAllocations[bid][key] = { allocated: true, quantity: block.allocated_amenities[key] };
                    });
                }
                if (block.allocated_facility_entries && Array.isArray(block.allocated_facility_entries)) {
                    block.allocated_facility_entries.forEach(entryId => {
                        blockAllocations[bid][entryId] = { allocated: true, quantity: 1 };
                    });
                }
            });
        }
        updateGlobalAllocationSummary();
    }

    function autoSelectBlockType() {
        if (configuredNumberOfBlocks > 1) selectBlockType('multi');
        else selectBlockType('single');
    }

    function displayCampusInfo(b) {
        document.getElementById('displayCampusName').textContent = b.name || '-';
        document.getElementById('displayCampusCode').textContent = b.code || '-';
        document.getElementById('displayTotalArea').textContent = `${originalCampusAreaValue} ${formatUnit(originalCampusAreaUnit)}`;
        updateRemainingArea();
    }

    function getTotalBlockAreaInOriginalUnit() {
        let t = 0;
        Object.keys(blockDataMap).forEach(bid => {
            const block = blockDataMap[bid];
            if (block && block.area_value !== undefined && block.area_value !== null && block.area_value !== '') {
                t += convertArea(parseFloat(block.area_value) || 0, block.area_unit || originalCampusAreaUnit, originalCampusAreaUnit);
            }
        });
        document.querySelectorAll('.block-accordion').forEach(c => {
            const bid = c.getAttribute('data-block-id');
            if (bid && !blockDataMap[bid]) {
                const dv = parseFloat(c.querySelector('.block-area-value')?.value) || 0;
                const du = blockDisplayUnits[bid] || originalCampusAreaUnit;
                t += convertArea(dv, du, originalCampusAreaUnit);
            }
        });
        return t;
    }

    function updateRemainingArea() {
        if (!buildingData) return;
        const a = getTotalBlockAreaInOriginalUnit();
        const r = originalCampusAreaValue - a;
        document.getElementById('displayRemainingArea').textContent = `${r.toFixed(2)} ${formatUnit(originalCampusAreaUnit)}`;
        const b = document.getElementById('remainingBadge');
        if (r < -0.01) { b.className = 'exceeded-badge'; b.querySelector('i').className = 'fas fa-exclamation-triangle'; }
        else { b.className = 'remaining-badge'; b.querySelector('i').className = 'fas fa-chart-pie'; }
    }

    function selectBlockType(type) {
        selectedBlockType = type;
        document.getElementById('singleBlockCard').classList.toggle('selected', type === 'single');
        document.getElementById('multiBlockCard').classList.toggle('selected', type === 'multi');
        document.getElementById('step2Section').classList.remove('hidden');
        if (selectedBlockId) saveCurrentBlockData();
        blockDataMap = {};
        existingBlockIds = [];
        blockAllocations = {};
        blockIdCounter = 0;
        const hasHostel = campusFacilities.hasHostel;
        let totalBlocks = type === 'single' ? 1 : configuredNumberOfBlocks;
        if (hasHostel && type === 'multi') totalBlocks = configuredNumberOfBlocks + 1;
        if (type === 'single') {
            document.getElementById('step2Title').innerHTML = '<i class="fas fa-building"></i> Single Block';
            document.getElementById('singleBlockMessage').classList.remove('hidden');
            document.getElementById('multiBlockMessage').classList.add('hidden');
            const bid = ++blockIdCounter;
            const blockName = hasHostel ? 'Hostel Block' : (buildingData?.name || 'Main Block');
            const blockData = { id: bid, name: blockName, code: hasHostel ? 'HOSTEL' : (buildingData?.code || ''), description: hasHostel ? 'Hostel block automatically created' : (buildingData?.description || ''), status: 'active', floors: 1, area_value: parseFloat(originalCampusAreaValue) || 0, area_unit: originalCampusAreaUnit, additional_areas: [], gates: [], custom_amenities: [], allocated_amenities: {}, allocated_facility_entries: [] };
            blockDataMap[bid] = blockData;
            existingBlockIds.push(bid);
            blockDisplayUnits[bid] = originalCampusAreaUnit;
            if (!blockAllocations[bid]) blockAllocations[bid] = {};
            Object.keys(campusAmenityTotals).forEach(key => { blockAllocations[bid][key] = { allocated: true, quantity: campusAmenityTotals[key] }; });
            Object.keys(campusCustomAmenityTotals).forEach(key => { blockAllocations[bid][key] = { allocated: true, quantity: campusCustomAmenityTotals[key].total }; });
            Object.keys(campusFacilityEntries).forEach(type => { campusFacilityEntries[type].forEach(entry => { blockAllocations[bid][entry.id] = { allocated: true, quantity: 1 }; }); });
            document.getElementById('blockSelectorCard').style.display = 'none';
            document.getElementById('blockDetailPanel').classList.add('active');
            document.getElementById('blocks-container').innerHTML = '';
            renderSingleBlock(bid);
        } else {
            document.getElementById('step2Title').innerHTML = '<i class="fas fa-cubes"></i> Multiple Blocks';
            document.getElementById('singleBlockMessage').classList.add('hidden');
            document.getElementById('multiBlockMessage').classList.remove('hidden');
            for (let i = 1; i <= configuredNumberOfBlocks; i++) {
                const bid = ++blockIdCounter;
                const blockData = { id: bid, name: `Block ${i}`, code: buildingData?.code ? `${buildingData.code}-BLK${i}` : '', description: '', status: 'active', floors: 1, area_value: 0, area_unit: originalCampusAreaUnit, additional_areas: [], gates: [], custom_amenities: [], allocated_amenities: {}, allocated_facility_entries: [] };
                blockDataMap[bid] = blockData;
                existingBlockIds.push(bid);
                blockDisplayUnits[bid] = originalCampusAreaUnit;
                if (!blockAllocations[bid]) blockAllocations[bid] = {};
            }
            if (hasHostel) {
                const bid = ++blockIdCounter;
                const blockData = { id: bid, name: 'Hostel Block', code: buildingData?.code ? `${buildingData.code}-HOSTEL` : 'HOSTEL', description: 'Hostel block automatically created', status: 'active', floors: 1, area_value: 0, area_unit: originalCampusAreaUnit, additional_areas: [], gates: [], custom_amenities: [], allocated_amenities: {}, allocated_facility_entries: [] };
                blockDataMap[bid] = blockData;
                existingBlockIds.push(bid);
                blockDisplayUnits[bid] = originalCampusAreaUnit;
                if (!blockAllocations[bid]) blockAllocations[bid] = {};
                showToast('Hostel block automatically created!', 'info');
            }
            document.getElementById('blockSelectorCard').style.display = 'block';
            document.getElementById('blockDetailPanel').classList.remove('active');
            document.getElementById('blocks-container').innerHTML = '';
            populateBlockDropdown();
            if (existingBlockIds.length > 0) { document.getElementById('blockSelectorDropdown').value = existingBlockIds[0]; onBlockSelectorChange(); }
        }
        updateRemainingArea();
        updateGlobalAllocationSummary();
        document.getElementById('step2Section').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function renderSingleBlock(bid) {
        const container = document.getElementById('blocks-container');
        const block = blockDataMap[bid];
        if (!block) return;
        const html = buildBlockHTML(bid, block, true);
        container.innerHTML = html;
        const blockCard = document.getElementById(`block-${bid}`);
        if (blockCard) blockCard.classList.add('active');
        updateBlockAreaSummary(blockCard);
        updateBlockAllocationSummary(bid);
    }

    function populateBlockDropdown() {
        const dropdown = document.getElementById('blockSelectorDropdown');
        const badge = document.getElementById('blockCountBadge');
        dropdown.innerHTML = '<option value="">-- Select a block --</option>';
        existingBlockIds.forEach(bid => {
            const block = blockDataMap[bid];
            if (block) {
                const option = document.createElement('option');
                option.value = bid;
                option.textContent = block.name || `Block ${bid}`;
                dropdown.appendChild(option);
            }
        });
        badge.textContent = `${existingBlockIds.length} blocks`;
        if (existingBlockIds.length === 0) {
            document.getElementById('selectedBlockBadge').style.display = 'none';
            document.getElementById('removeSelectedBlockBtn').style.display = 'none';
            document.getElementById('addBlockFromDropdownBtn').style.display = 'inline-flex';
        } else {
            document.getElementById('addBlockFromDropdownBtn').style.display = 'inline-flex';
        }
    }

    function saveCurrentBlockData() {
        if (!selectedBlockId || !blockDataMap[selectedBlockId]) return;
        const data = getBlockDataFromDOM(selectedBlockId);
        if (data && data.name) {
            blockDataMap[selectedBlockId] = { ...blockDataMap[selectedBlockId], ...data };
            const blockCard = document.getElementById(`block-${selectedBlockId}`);
            if (blockCard) {
                const areaValueInput = blockCard.querySelector('.block-area-value');
                const areaUnitSelect = blockCard.querySelector('.block-area-unit');
                if (areaValueInput) {
                    const dv = parseFloat(areaValueInput.value) || 0;
                    const du = areaUnitSelect ? areaUnitSelect.value : (blockDisplayUnits[selectedBlockId] || originalCampusAreaUnit);
                    blockDataMap[selectedBlockId].area_value = parseFloat(convertArea(dv, du, originalCampusAreaUnit)) || 0;
                    blockDataMap[selectedBlockId].area_unit = originalCampusAreaUnit;
                }
            }
        }
    }

    function onBlockSelectorChange() {
        saveCurrentBlockData();
        const bid = parseInt(document.getElementById('blockSelectorDropdown').value);
        if (!bid || !blockDataMap[bid]) {
            document.getElementById('blockDetailPanel').classList.remove('active');
            document.getElementById('blocks-container').innerHTML = '';
            document.getElementById('selectedBlockBadge').style.display = 'none';
            document.getElementById('removeSelectedBlockBtn').style.display = 'none';
            selectedBlockId = null;
            return;
        }
        selectedBlockId = bid;
        const block = blockDataMap[bid];
        document.getElementById('selectedBlockBadge').style.display = 'inline-flex';
        document.getElementById('selectedBlockName').textContent = block.name || `Block ${bid}`;
        document.getElementById('removeSelectedBlockBtn').style.display = 'inline-flex';
        document.getElementById('blockDetailPanel').classList.add('active');
        const container = document.getElementById('blocks-container');
        const html = buildBlockHTML(bid, block, false);
        container.innerHTML = html;
        const blockCard = document.getElementById(`block-${bid}`);
        if (blockCard) blockCard.classList.add('active');
        updateBlockAreaSummary(blockCard);
        updateBlockAllocationSummary(bid);
    }

    function buildBlockHTML(bid, block, isSingle) {
        const du = blockDisplayUnits[bid] || originalCampusAreaUnit;
        const dv = (typeof block.area_value === 'number' && !isNaN(block.area_value)) ? block.area_value : (parseFloat(block.area_value) || 0);
        const bad = generateBuildingAreasDropdown(bid);
        const allocHtml = buildBlockAllocationHTML(bid);
        let areasHtml = '';
        const areas = block.additional_areas || [];
        if (areas.length === 0) { areasHtml = generateDefaultArea(bid); } else {
            areas.forEach((area, idx) => {
                const id = idx + 1;
                const areaId = area.id || generateUUID();
                areasHtml += `
                    <div class="dynamic-card area-card" data-area-id="${id}" data-area-uuid="${areaId}">
                        <div class="card-badge-sm">Area #${id}</div>
                        <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;">
                            <div class="form-group"><label>Area Name</label><input type="text" class="form-control area-name" value="${escapeHtml(area.name || '')}" placeholder="e.g., Playground"></div>
                            <div class="form-group"><label>Area Unit</label><select class="form-control area-unit" onchange="handleBlockAreaUnitChange(this,${bid})">${generateUnitOptions(area.unit || du)}</select></div>
                            <div class="form-group"><label>Area Value</label><input type="number" class="form-control area-value" value="${area.area || ''}" placeholder="Enter area" min="0" step="0.01" onchange="updateBlockAreaSummary(document.getElementById('block-${bid}'))"></div>
                        </div>
                        <div style="text-align:right;margin-top:0.5rem;">
                            <button type="button" class="btn-outline-danger" onclick="this.closest('.dynamic-card').remove();updateBlockAreaSummary(document.getElementById('block-${bid}'));"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>`;
            });
        }
        let gatesHtml = '';
        const gates = block.gates || [];
        if (gates.length === 0) { gatesHtml = generateDefaultGate(); } else {
            gates.forEach((gate, idx) => {
                const id = idx + 1;
                const gateId = gate.id || generateUUID();
                gatesHtml += `
                    <div class="dynamic-card gate-card" data-gate-id="${id}" data-gate-uuid="${gateId}">
                        <div class="card-badge-sm">Gate #${id}</div>
                        <div class="card-row" style="grid-template-columns:1fr 1fr;">
                            <div class="form-group"><label>Gate Name</label><input type="text" class="form-control gate-name" value="${escapeHtml(gate.name || '')}" placeholder="e.g., Main Entrance"></div>
                            <div class="form-group"><label>Gate Number</label><input type="text" class="form-control gate-number" value="${escapeHtml(gate.number || '')}" placeholder="e.g., G-01"></div>
                        </div>
                        <div style="text-align:right;margin-top:0.5rem;">
                            <button type="button" class="btn-outline-danger" onclick="this.closest('.dynamic-card').remove();updateBlockHeaderSummary(document.getElementById('block-${bid}'));"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>`;
            });
        }
        let customHtml = '';
        const customAmenities = block.custom_amenities || [];
        if (customAmenities.length === 0) { customHtml = generateDefaultCustomAmenity(); } else {
            customAmenities.forEach((item, idx) => {
                const id = idx + 1;
                const amenityId = item.id || generateUUID();
                customHtml += `
                    <div class="custom-amenity-card" data-custom-id="${id}" data-amenity-uuid="${amenityId}">
                        <div class="card-badge-sm">Custom #${id}</div>
                        <div class="card-row" style="grid-template-columns:2fr 1fr;">
                            <div class="form-group"><label>Amenity Name</label><input type="text" class="form-control custom-amenity-name" value="${escapeHtml(item.name || '')}" placeholder="e.g., Projector"></div>
                            <div class="form-group"><label>Quantity</label><input type="number" class="form-control custom-amenity-qty" value="${item.quantity || 1}" min="1"></div>
                        </div>
                        <div style="text-align:right;margin-top:0.5rem;">
                            <button type="button" class="btn-outline-danger" onclick="this.closest('.custom-amenity-card').remove()"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>`;
            });
        }
        const removeBtn = isSingle ? '' : `
            <div style="text-align:right;margin-top:1rem;">
                <button type="button" class="btn-outline-danger" onclick="removeBlockFromDropdown(${bid})"><i class="fas fa-trash"></i> Remove Block</button>
            </div>`;
        const isHostel = block.name && block.name.toLowerCase().includes('hostel');
        const hostelBadge = isHostel ? '<span style="background:var(--warning-gradient);padding:2px 10px;border-radius:12px;font-size:0.6rem;color:white;margin-left:8px;"><i class="fas fa-hotel"></i> Hostel</span>' : '';
        return `
            <div class="block-accordion active" id="block-${bid}" data-block-id="${bid}">
                <div class="accordion-header" onclick="toggleBlock(${bid})">
                    <div class="header-left">
                        <div class="block-icon"><i class="fas ${isHostel ? 'fa-hotel' : 'fa-building'}"></i></div>
                        <div class="block-info">
                            <div class="block-title">${block.name || 'Block'} ${hostelBadge}</div>
                            <div class="block-subtitle"><span><i class="fas fa-vector-square"></i> ${dv.toFixed(2)} ${getUnitDisplayName(du)}</span></div>
                        </div>
                    </div>
                    <div class="header-actions">
                        <span class="block-alloc-summary" id="blockAllocSummary-${bid}"><i class="fas fa-cubes"></i> 0 allocated</span>
                        <div class="toggle-icon"><i class="fas fa-chevron-down"></i></div>
                    </div>
                </div>
                <div class="accordion-body">
                    <div class="accordion-content">
                        <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:0.5rem;">
                            <div class="form-group"><label class="form-label">Block Name <span style="color:#dc3545;">*</span></label><input type="text" class="form-control block-name" value="${escapeHtml(block.name || '')}" placeholder="e.g., Main Block" onchange="updateBlockHeaderSummary(this.closest('.block-accordion'));updateBlockDropdownLabel(${bid}, this.value);"></div>
                            <div class="form-group"><label class="form-label">Block Code</label><input type="text" class="form-control block-code" value="${escapeHtml(block.code || '')}" placeholder="e.g., MB" maxlength="10"></div>
                        </div>
                        <div class="form-group" style="margin-top:0.75rem;"><label class="form-label">Description</label><textarea class="form-control block-description" rows="2" placeholder="Brief description...">${escapeHtml(block.description || '')}</textarea></div>
                        <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:0.75rem;">
                            <div class="form-group"><label class="form-label">Status</label><select class="form-control block-status"><option value="active" ${block.status === 'active' ? 'selected' : ''}>Active</option><option value="inactive" ${block.status === 'inactive' ? 'selected' : ''}>Inactive</option></select></div>
                            <div class="form-group"><label class="form-label">Number of Floors</label><input type="number" class="form-control block-floors" placeholder="e.g., 4" min="0" value="${block.floors || 1}"></div>
                        </div>
                        <div class="section-divider"><h4><i class="fas fa-cubes" style="color:var(--primary-color);"></i> Campus Resource Allocation</h4></div>
                        <div class="allocation-tracker" id="blockAllocTracker-${bid}">${allocHtml}</div>
                        <div class="section-divider"><h4><i class="fas fa-vector-square"></i> Total Area of Block</h4></div>
                        <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                            <div class="form-group"><label>Area Unit</label><select class="form-control block-area-unit" onchange="handleBlockAreaUnitChange(this,${bid})">${generateUnitOptions(du)}</select></div>
                            <div class="form-group"><label>Area Value</label><input type="number" class="form-control block-area-value" value="${dv}" min="0" step="0.01" onchange="updateRemainingArea();updateBlockAreaSummary(this.closest('.block-accordion'));"></div>
                        </div>
                        <div class="alert-info" style="font-size:0.8rem;margin-top:8px;"><i class="fas fa-info-circle"></i> Original campus area: <strong>${originalCampusAreaValue} ${getUnitDisplayName(originalCampusAreaUnit)}</strong>. Changing unit only affects display.</div>
                        <div class="block-area-summary"><i class="fas fa-calculator"></i> Block Area: <strong>${dv.toFixed(2)} ${getUnitDisplayName(du)}</strong> | Additional: <strong>0.00 ${getUnitDisplayName(du)}</strong> | Facilities: <strong>0.00 ${getUnitDisplayName(du)}</strong> | <span class="remaining-block">Remaining: <strong>${dv.toFixed(2)} ${getUnitDisplayName(du)}</span></div>
                        <div class="section-divider"><h4><i class="fas fa-map"></i> Additional Areas</h4></div>
                        ${bad}
                        <div class="block-areas-container" data-block-id="${bid}">${areasHtml}</div>
                        <div class="add-btn-row"><button type="button" class="btn-outline-primary" onclick="addAreaToBlock(${bid})"><i class="fas fa-plus"></i> Add Area</button></div>
                        <div class="section-divider"><h4><i class="fas fa-door-open"></i> Gates</h4></div>
                        <div class="block-gates-container" data-block-id="${bid}">${gatesHtml}</div>
                        <div class="add-btn-row"><button type="button" class="btn-outline-primary" onclick="addGateToBlock(${bid})"><i class="fas fa-plus"></i> Add Gate</button></div>
                        <div class="section-divider"><h4><i class="fas fa-plus-circle" style="color:#10b981;"></i> Custom Amenities (Block Specific)</h4></div>
                        <div class="block-custom-amenities-container" data-block-id="${bid}">${customHtml}</div>
                        <div class="add-btn-row"><button type="button" class="btn-outline-success" onclick="addCustomAmenityToBlock(${bid})"><i class="fas fa-plus"></i> Add Custom</button></div>
                        ${removeBtn}
                    </div>
                </div>
            </div>`;
    }


function updateBlockDropdownLabel(bid, name) {
    const dropdown = document.getElementById('blockSelectorDropdown');
    if (dropdown) {
        const option = dropdown.querySelector(`option[value="${bid}"]`);
        if (option) option.textContent = name || `Block ${bid}`;
    }
    const badge = document.getElementById('selectedBlockName');
    if (badge) badge.textContent = name || `Block ${bid}`;
}
function addBlockFromDropdown() {
    saveCurrentBlockData();
    const bid = ++blockIdCounter;
    const totalBlocks = existingBlockIds.length + 1;
    const blockData = { id: bid, name: `Block ${totalBlocks}`, code: buildingData?.code ? `${buildingData.code}-BLK${totalBlocks}` : '', description: '', status: 'active', floors: 1, area_value: 0, area_unit: originalCampusAreaUnit, additional_areas: [], gates: [], custom_amenities: [], allocated_amenities: {}, allocated_facility_entries: [] };
    blockDataMap[bid] = blockData;
    existingBlockIds.push(bid);
    blockDisplayUnits[bid] = originalCampusAreaUnit;
    if (!blockAllocations[bid]) blockAllocations[bid] = {};
    populateBlockDropdown();
    document.getElementById('blockSelectorDropdown').value = bid;
    onBlockSelectorChange();
    updateRemainingArea();
    updateGlobalAllocationSummary();
    showToast('New block added!', 'success');
}
function removeBlockFromDropdown(bid) {
    saveCurrentBlockData();
    if (existingBlockIds.length <= 1) { showToast('At least one block required', 'error'); return; }
    if (!confirm(`Are you sure you want to remove this block?`)) return;
    const index = existingBlockIds.indexOf(bid);
    if (index > -1) existingBlockIds.splice(index, 1);
    delete blockDataMap[bid];
    delete blockAllocations[bid];
    delete blockDisplayUnits[bid];
    const container = document.getElementById('blocks-container');
    const blockCard = document.getElementById(`block-${bid}`);
    if (blockCard) {
        blockCard.style.opacity = '0';
        blockCard.style.transform = 'scale(0.95)';
        setTimeout(() => {
            blockCard.remove();
            populateBlockDropdown();
            if (existingBlockIds.length > 0) {
                document.getElementById('blockSelectorDropdown').value = existingBlockIds[0];
                onBlockSelectorChange();
            } else {
                document.getElementById('blockDetailPanel').classList.remove('active');
                document.getElementById('selectedBlockBadge').style.display = 'none';
                document.getElementById('removeSelectedBlockBtn').style.display = 'none';
                selectedBlockId = null;
            }
            updateRemainingArea();
            updateGlobalAllocationSummary();
        }, 300);
    } else {
        populateBlockDropdown();
        if (existingBlockIds.length > 0) {
            document.getElementById('blockSelectorDropdown').value = existingBlockIds[0];
            onBlockSelectorChange();
        }
        updateRemainingArea();
        updateGlobalAllocationSummary();
    }
    showToast('Block removed', 'info');
}
function removeSelectedBlock() { if (selectedBlockId) removeBlockFromDropdown(selectedBlockId); }
function buildBlockAllocationHTML(bid) {
    let html = `<div class="allocation-header"><h4><i class="fas fa-arrow-right"></i> Select Campus Resources for this Block</h4><span class="remaining-tag available" id="blockAllocTag-${bid}"><i class="fas fa-check-circle"></i> Available</span></div>`;
    const amenityKeys = Object.keys(campusAmenityTotals);
    if (amenityKeys.length > 0) {
        html += `<div style="margin-bottom:8px;font-weight:600;font-size:0.8rem;color:var(--text-dark);"><i class="fas fa-concierge-bell"></i> Campus Amenities</div>`;
        amenityKeys.forEach(key => {
            const total = campusAmenityTotals[key] || 0;
            const usedGlobal = getUsedCountForAmenity(key);
            const remaining = total - usedGlobal;
            const inThisBlock = blockAllocations[bid] && blockAllocations[bid][key] ? blockAllocations[bid][key].quantity || 0 : 0;
            const displayName = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            const isChecked = blockAllocations[bid] && blockAllocations[bid][key] && blockAllocations[bid][key].allocated;
            const qty = isChecked ? inThisBlock : 0;
            const disabled = remaining <= 0 && !isChecked;
            html += `<div class="allocation-item" data-amenity="${key}">
                <input type="checkbox" class="alloc-check" data-block="${bid}" data-key="${key}" data-total="${total}" ${isChecked ? 'checked' : ''} ${disabled ? 'disabled' : ''} onchange="onBlockAllocationChange(${bid},'${key}',this)">
                <span class="alloc-name">${displayName}</span>
                <div class="alloc-stats">
                    <span class="used">In this block: ${inThisBlock}</span>
                    <span class="remaining ${remaining > 0 ? 'positive' : 'zero'}">Remaining globally: ${remaining}</span>
                </div>
                <input type="number" class="alloc-qty ${isChecked ? 'show' : ''}" data-block="${bid}" data-key="${key}" min="1" max="${remaining + (isChecked ? qty : 0)}" value="${isChecked ? qty : 1}" ${isChecked ? '' : 'disabled'} onchange="onBlockAllocationQtyChange(${bid},'${key}',this)">
                <span class="alloc-badge ${isChecked ? 'included' : 'excluded'}">${isChecked ? 'Included' : 'Excluded'}</span>
            </div>`;
        });
    }
    const customKeys = Object.keys(campusCustomAmenityTotals);
    if (customKeys.length > 0) {
        html += `<div style="margin-top:12px;margin-bottom:8px;font-weight:600;font-size:0.8rem;color:var(--text-dark);"><i class="fas fa-plus-circle" style="color:#10b981;"></i> Custom Campus Amenities</div>`;
        customKeys.forEach(key => {
            const item = campusCustomAmenityTotals[key];
            if (!item) return;
            const total = item.total || 0;
            const usedGlobal = getUsedCountForCustomAmenity(key);
            const remaining = total - usedGlobal;
            const inThisBlock = blockAllocations[bid] && blockAllocations[bid][key] ? blockAllocations[bid][key].quantity || 0 : 0;
            const displayName = item.name || key;
            const isChecked = blockAllocations[bid] && blockAllocations[bid][key] && blockAllocations[bid][key].allocated;
            const qty = isChecked ? inThisBlock : 0;
            const disabled = remaining <= 0 && !isChecked;
            html += `<div class="allocation-item" data-custom="${key}">
                <input type="checkbox" class="alloc-check" data-block="${bid}" data-key="${key}" data-total="${total}" data-custom="true" ${isChecked ? 'checked' : ''} ${disabled ? 'disabled' : ''} onchange="onBlockAllocationChange(${bid},'${key}',this)">
                <span class="alloc-name">${escapeHtml(displayName)}</span>
                <div class="alloc-stats">
                    <span class="used">In this block: ${inThisBlock}</span>
                    <span class="remaining ${remaining > 0 ? 'positive' : 'zero'}">Remaining globally: ${remaining}</span>
                </div>
                <input type="number" class="alloc-qty ${isChecked ? 'show' : ''}" data-block="${bid}" data-key="${key}" min="1" max="${remaining + (isChecked ? qty : 0)}" value="${isChecked ? qty : 1}" ${isChecked ? '' : 'disabled'} onchange="onBlockAllocationQtyChange(${bid},'${key}',this)">
                <span class="alloc-badge ${isChecked ? 'included' : 'excluded'}">${isChecked ? 'Included' : 'Excluded'}</span>
            </div>`;
        });
    }
    const facilityTypes = Object.keys(campusFacilityEntries);
    if (facilityTypes.length > 0) {
        html += `<div style="margin-top:12px;margin-bottom:8px;font-weight:600;font-size:0.8rem;color:var(--text-dark);"><i class="fas fa-building"></i> Campus Facilities</div>`;
        facilityTypes.forEach(type => {
            const entries = campusFacilityEntries[type];
            const typeDisplayName = type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            const iconMap = { 'parking': 'fa-parking', 'playground': 'fa-futbol', 'swimming_pool': 'fa-swimming-pool', 'clubhouse': 'fa-home', 'warehouse': 'fa-warehouse', 'store_room': 'fa-boxes', 'auditorium': 'fa-theater-masks', 'washrooms': 'fa-restroom' };
            const icon = iconMap[type] || 'fa-building';
            let allocatedCount = 0;
            entries.forEach(entry => { if (blockAllocations[bid] && blockAllocations[bid][entry.id] && blockAllocations[bid][entry.id].allocated) allocatedCount++; });
            const totalCount = entries.length;
            const badgeText = allocatedCount > 0 ? `${allocatedCount}/${totalCount} selected` : `${totalCount} available`;
            let detailHtml = '';
            if (type === 'washrooms') {
                detailHtml = `<div style="font-size:0.65rem;color:var(--text-muted);margin-top:4px;display:flex;flex-wrap:wrap;gap:4px;">`;
                entries.forEach(entry => {
                    const typeLabel = entry.type === 'male' ? 'Male' : (entry.type === 'female' ? 'Female' : 'Unisex');
                    const isSelected = blockAllocations[bid] && blockAllocations[bid][entry.id] && blockAllocations[bid][entry.id].allocated;
                    detailHtml += `<span class="washroom-summary-badge ${entry.type} ${isSelected ? 'included' : ''}">
                        <i class="fas fa-${entry.type === 'male' ? 'mars' : (entry.type === 'female' ? 'venus' : 'venus-mars')}"></i>
                        ${typeLabel}: ${entry.toilets || 0} toilets, ${entry.urinals || 0} urinals, ${entry.washbasins || 0} basins
                        ${entry.area ? ` (${parseFloat(entry.area).toFixed(2)} sq.ft.)` : ''}
                    </span>`;
                });
                detailHtml += `</div>`;
            }
            html += `
                <div class="facility-group-card">
                    <div class="group-header" onclick="toggleFacilityGroup(this)">
                        <span class="group-title"><i class="fas ${icon}"></i> ${typeDisplayName}</span>
                        <span style="display:flex;align-items:center;gap:10px;">
                            <span class="group-badge">${badgeText}</span>
                            <i class="fas fa-chevron-down group-toggle"></i>
                        </span>
                    </div>
                    <div class="group-body">
                        <div class="group-body-inner">
                            ${detailHtml}
                            ${entries.map(entry => {
                                const isChecked = blockAllocations[bid] && blockAllocations[bid][entry.id] && blockAllocations[bid][entry.id].allocated;
                                const usedGlobal = getUsedCountForFacilityEntry(entry.id);
                                const total = 1;
                                const remaining = total - usedGlobal;
                                const disabled = remaining <= 0 && !isChecked;
                                const areaDisplay = entry.area ? `${parseFloat(entry.area).toFixed(2)} ${getUnitDisplayName(campusAreaUnit || 'sq_ft')}` : 'Area not set';
                                let extraInfo = '';
                                if (entry.toilets !== undefined) extraInfo = `🚽${entry.toilets || 0} 🚹${entry.urinals || 0} 🚰${entry.washbasins || 0}`;
                                return `
                                    <div class="allocation-item" data-facility-entry="${entry.id}">
                                        <input type="checkbox" class="alloc-check" data-block="${bid}" data-key="${entry.id}" data-total="1" data-facility-entry="true" ${isChecked ? 'checked' : ''} ${disabled ? 'disabled' : ''} onchange="onBlockAllocationChange(${bid},'${entry.id}',this)">
                                        <span class="alloc-name">${escapeHtml(entry.name)} <span style="font-size:0.65rem;color:var(--text-muted);font-weight:400;">${extraInfo}</span></span>
                                        <div class="alloc-stats">
                                            <span class="used">In this block: ${isChecked ? '1' : '0'}</span>
                                            <span class="remaining ${remaining > 0 ? 'positive' : 'zero'}">Remaining globally: ${remaining}</span>
                                        </div>
                                        <span class="alloc-badge ${isChecked ? 'included' : 'excluded'}">${isChecked ? 'Included' : 'Excluded'}</span>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    </div>
                </div>`;
        });
    }
    if (amenityKeys.length === 0 && customKeys.length === 0 && facilityTypes.length === 0) {
        html += `<div style="text-align:center;padding:0.5rem;color:var(--text-muted);font-size:0.85rem;"><i class="fas fa-info-circle"></i> No campus resources found. Add amenities/facilities in Step 1.</div>`;
    }
    html += `<div class="allocation-summary" style="margin-top:0.5rem;padding-top:0.5rem;">
        <span class="stat-item"><i class="fas fa-check-circle" style="color:#10b981;"></i> Allocated: <strong id="blockAllocCount-${bid}">0</strong></span>
        <span class="stat-item"><i class="fas fa-clock" style="color:#f59e0b;"></i> Pending: <strong id="blockAllocPending-${bid}">0</strong></span>
    </div>`;
    return html;
}
function getUsedCountForAmenity(key) {
    let total = 0;
    Object.keys(blockAllocations).forEach(bid => {
        if (blockAllocations[bid] && blockAllocations[bid][key] && blockAllocations[bid][key].allocated) {
            total += blockAllocations[bid][key].quantity || 0;
        }
    });
    return total;
}
function getUsedCountForCustomAmenity(key) {
    let total = 0;
    Object.keys(blockAllocations).forEach(bid => {
        if (blockAllocations[bid] && blockAllocations[bid][key] && blockAllocations[bid][key].allocated) {
            total += blockAllocations[bid][key].quantity || 0;
        }
    });
    return total;
}
function getUsedCountForFacilityEntry(entryId) {
    let count = 0;
    Object.keys(blockAllocations).forEach(bid => {
        if (blockAllocations[bid] && blockAllocations[bid][entryId] && blockAllocations[bid][entryId].allocated) {
            count++;
        }
    });
    return count;
}
function toggleFacilityGroup(header) {
    const body = header.nextElementSibling;
    const toggleIcon = header.querySelector('.group-toggle');
    if (body) { body.classList.toggle('open'); if (toggleIcon) toggleIcon.classList.toggle('open'); }
}
function onBlockAllocationChange(bid, key, checkbox) {
    const checked = checkbox.checked;
    const isFacilityEntry = checkbox.getAttribute('data-facility-entry') === 'true';
    const qtyInput = checkbox.closest('.allocation-item')?.querySelector('.alloc-qty');
    const badge = checkbox.closest('.allocation-item')?.querySelector('.alloc-badge');
    if (!blockAllocations[bid]) blockAllocations[bid] = {};
    if (checked) {
        if (isFacilityEntry) {
            blockAllocations[bid][key] = { allocated: true, quantity: 1 };
            if (badge) { badge.textContent = 'Included'; badge.className = 'alloc-badge included'; }
            if (qtyInput) { qtyInput.disabled = false; qtyInput.classList.add('show'); qtyInput.value = 1; }
        } else {
            const total = parseInt(checkbox.getAttribute('data-total')) || 0;
            const used = getUsedCountForAmenity(key);
            const remaining = total - used;
            if (remaining <= 0) { showToast('No more units available for this amenity', 'error'); checkbox.checked = false; return; }
            blockAllocations[bid][key] = { allocated: true, quantity: 1 };
            if (qtyInput) { qtyInput.disabled = false; qtyInput.classList.add('show'); qtyInput.value = 1; qtyInput.max = remaining + (blockAllocations[bid] && blockAllocations[bid][key] ? blockAllocations[bid][key].quantity || 0 : 0); }
            if (badge) { badge.textContent = 'Included'; badge.className = 'alloc-badge included'; }
            updateAllocationCheckboxes(key, total);
        }
    } else {
        delete blockAllocations[bid][key];
        if (qtyInput) { qtyInput.disabled = true; qtyInput.classList.remove('show'); qtyInput.value = 1; }
        if (badge) { badge.textContent = 'Excluded'; badge.className = 'alloc-badge excluded'; }
        if (!isFacilityEntry) { const total = parseInt(checkbox.getAttribute('data-total')) || 0; updateAllocationCheckboxes(key, total); }
    }
    updateBlockAllocationSummary(bid);
    updateGlobalAllocationSummary();
    if (!isFacilityEntry) { updateAllocationStats(key); }
    const blockCard = document.getElementById(`block-${bid}`);
    if (blockCard) updateBlockAreaSummary(blockCard);
}
function onBlockAllocationQtyChange(bid, key, input) {
    const isFacilityEntry = input.closest('.allocation-item').querySelector('.alloc-check').getAttribute('data-facility-entry') === 'true';
    if (isFacilityEntry) return;
    const val = parseInt(input.value) || 1;
    const total = parseInt(input.getAttribute('max')) || 1;
    const used = getUsedCountForAmenity(key);
    const remaining = (parseInt(input.closest('.allocation-item').querySelector('.alloc-check').getAttribute('data-total')) || 0) - used + (blockAllocations[bid] && blockAllocations[bid][key] ? blockAllocations[bid][key].quantity || 0 : 0);
    if (val < 1) { input.value = 1; return; }
    if (val > remaining) { input.value = remaining; showToast(`Max ${remaining} units available`, 'info'); }
    if (blockAllocations[bid] && blockAllocations[bid][key]) { blockAllocations[bid][key].quantity = parseInt(input.value) || 1; }
    updateBlockAllocationSummary(bid);
    updateGlobalAllocationSummary();
    updateAllocationStats(key);
}
function updateAllocationCheckboxes(key, total) {
    const used = getUsedCountForAmenity(key);
    const remaining = total - used;
    document.querySelectorAll(`.alloc-check[data-key="${key}"]`).forEach(cb => {
        const bid = parseInt(cb.getAttribute('data-block'));
        const isAllocated = blockAllocations[bid] && blockAllocations[bid][key] && blockAllocations[bid][key].allocated;
        if (!isAllocated && remaining <= 0) {
            cb.disabled = true;
            const qtyInput = cb.closest('.allocation-item')?.querySelector('.alloc-qty');
            if (qtyInput) qtyInput.disabled = true;
            const badge = cb.closest('.allocation-item')?.querySelector('.alloc-badge');
            if (badge) { badge.textContent = 'Exhausted'; badge.className = 'alloc-badge exhausted'; }
        } else if (!isAllocated) {
            cb.disabled = false;
            const qtyInput = cb.closest('.allocation-item')?.querySelector('.alloc-qty');
            if (qtyInput) qtyInput.disabled = true;
            const badge = cb.closest('.allocation-item')?.querySelector('.alloc-badge');
            if (badge) { badge.textContent = 'Excluded'; badge.className = 'alloc-badge excluded'; }
        } else {
            cb.disabled = false;
            const qtyInput = cb.closest('.allocation-item')?.querySelector('.alloc-qty');
            if (qtyInput) qtyInput.disabled = false;
            const badge = cb.closest('.allocation-item')?.querySelector('.alloc-badge');
            if (badge) { badge.textContent = 'Included'; badge.className = 'alloc-badge included'; }
        }
    });
}
function updateAllocationStats(key) {
    const used = getUsedCountForAmenity(key);
    document.querySelectorAll(`.allocation-item[data-amenity="${key}"]`).forEach(item => {
        const usedSpan = item.querySelector('.used');
        const remainingSpan = item.querySelector('.remaining');
        const total = parseInt(item.querySelector('.alloc-check').getAttribute('data-total')) || 0;
        const remaining = total - used;
        if (usedSpan) usedSpan.textContent = `In this block: ${item.querySelector('.alloc-qty')?.value || 0}`;
        if (remainingSpan) { remainingSpan.textContent = `Remaining globally: ${remaining}`; remainingSpan.className = `remaining ${remaining > 0 ? 'positive' : 'zero'}`; }
    });
}
function updateBlockAllocationSummary(bid) {
    const allocs = blockAllocations[bid] || {};
    let count = 0;
    Object.keys(allocs).forEach(key => { if (allocs[key] && allocs[key].allocated) count += allocs[key].quantity || 1; });
    const summary = document.getElementById(`blockAllocSummary-${bid}`);
    if (summary) summary.innerHTML = `<i class="fas fa-cubes"></i> ${count} allocated`;
    const countEl = document.getElementById(`blockAllocCount-${bid}`);
    if (countEl) countEl.textContent = count;
    const tag = document.getElementById(`blockAllocTag-${bid}`);
    if (tag) {
        const totalPossible = Object.keys(campusAmenityTotals).length + Object.keys(campusCustomAmenityTotals).length + Object.keys(campusFacilityEntries).reduce((acc, type) => acc + campusFacilityEntries[type].length, 0);
        if (count >= totalPossible && totalPossible > 0) { tag.className = 'remaining-tag exhausted'; tag.innerHTML = '<i class="fas fa-check-circle"></i> Fully Allocated'; }
        else if (totalPossible === 0) { tag.className = 'remaining-tag'; tag.innerHTML = '<i class="fas fa-info-circle"></i> No Resources'; }
        else { tag.className = 'remaining-tag available'; tag.innerHTML = '<i class="fas fa-check-circle"></i> Available'; }
    }
    const groupHeaders = document.querySelectorAll(`#block-${bid} .facility-group-card .group-header`);
    groupHeaders.forEach(header => {
        const groupBody = header.nextElementSibling;
        if (groupBody) {
            const entries = groupBody.querySelectorAll('.allocation-item');
            let selected = 0;
            entries.forEach(item => { const cb = item.querySelector('.alloc-check'); if (cb && cb.checked) selected++; });
            const total = entries.length;
            const badge = header.querySelector('.group-badge');
            if (badge) badge.textContent = selected > 0 ? `${selected}/${total} selected` : `${total} available`;
        }
    });
}
function updateGlobalAllocationSummary() {
    const gas = document.getElementById('globalAllocSummary');
    if (!gas) return;
    const totalBlocks = Object.keys(blockDataMap).length;
    let totalAllocated = 0;
    Object.keys(blockAllocations).forEach(bid => {
        const allocs = blockAllocations[bid] || {};
        Object.keys(allocs).forEach(key => { if (allocs[key] && allocs[key].allocated) totalAllocated += allocs[key].quantity || 1; });
    });
    let totalAvailable = 0;
    Object.keys(campusAmenityTotals).forEach(key => { totalAvailable += campusAmenityTotals[key] || 0; });
    Object.keys(campusCustomAmenityTotals).forEach(key => { totalAvailable += campusCustomAmenityTotals[key]?.total || 0; });
    Object.keys(campusFacilityEntries).forEach(type => { totalAvailable += campusFacilityEntries[type].length; });
    const remaining = totalAvailable - totalAllocated;
    document.getElementById('globalBlockCount').textContent = totalBlocks;
    document.getElementById('globalAllocatedCount').textContent = totalAllocated;
    document.getElementById('globalRemainingCount').textContent = remaining > 0 ? remaining : 0;
    const tag = document.getElementById('globalAllocTag');
    if (tag) {
        if (remaining <= 0 && totalAvailable > 0) { tag.className = 'remaining-tag exhausted'; tag.innerHTML = '<i class="fas fa-exclamation-circle"></i> All Resources Allocated'; }
        else if (totalAvailable === 0) { tag.className = 'remaining-tag'; tag.innerHTML = '<i class="fas fa-info-circle"></i> No Resources Added'; }
        else { tag.className = 'remaining-tag available'; tag.innerHTML = '<i class="fas fa-check-circle"></i> Resources Available'; }
    }
    const container = document.getElementById('globalAllocItems');
    if (container) {
        let itemsHtml = '';
        Object.keys(campusAmenityTotals).forEach(key => {
            const total = campusAmenityTotals[key] || 0;
            const used = getUsedCountForAmenity(key);
            const displayName = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            itemsHtml += `<span class="block-alloc-summary" style="font-size:0.65rem;padding:3px 10px;"><i class="fas fa-cube"></i> ${displayName}: ${used}/${total}</span>`;
        });
        Object.keys(campusCustomAmenityTotals).forEach(key => {
            const item = campusCustomAmenityTotals[key];
            const total = item?.total || 0;
            const used = getUsedCountForCustomAmenity(key);
            itemsHtml += `<span class="block-alloc-summary" style="font-size:0.65rem;padding:3px 10px;background:linear-gradient(135deg,#ecfdf5,#d1fae5);"><i class="fas fa-plus-circle"></i> ${escapeHtml(item?.name || key)}: ${used}/${total}</span>`;
        });
        Object.keys(campusFacilityEntries).forEach(type => {
            campusFacilityEntries[type].forEach(entry => {
                const used = getUsedCountForFacilityEntry(entry.id);
                const total = 1;
                const displayName = entry.name || type;
                let extra = '';
                if (entry.toilets !== undefined) extra = ` 🚽${entry.toilets} 🚹${entry.urinals||0} 🚰${entry.washbasins}`;
                itemsHtml += `<span class="block-alloc-summary" style="font-size:0.65rem;padding:3px 10px;background:linear-gradient(135deg,#fef3c7,#fde68a);"><i class="fas fa-building"></i> ${escapeHtml(displayName)}${extra}: ${used}/${total}</span>`;
            });
        });
        if (itemsHtml) container.innerHTML = itemsHtml;
        else container.innerHTML = '<span style="color:var(--text-muted);font-size:0.75rem;">No resources to allocate</span>';
        gas.style.display = 'block';
    }
}
function handleBlockAreaUnitChange(sel, bid) {
    const ou = blockDisplayUnits[bid] || originalCampusAreaUnit;
    const nu = sel.value;
    if (ou === nu) return;
    const b = document.getElementById(`block-${bid}`);
    if (!b) return;
    const ai = b.querySelector('.block-area-value');
    const cdv = parseFloat(ai?.value) || 0;
    const ndv = convertArea(cdv, ou, nu);
    if (ai) ai.value = ndv.toFixed(4);
    blockDisplayUnits[bid] = nu;
    b.querySelectorAll('.area-card').forEach(ac => {
        const av = ac.querySelector('.area-value'), au = ac.querySelector('.area-unit');
        if (av && av.value && au) { av.value = convertArea(parseFloat(av.value), ou, nu).toFixed(4); au.value = nu; }
    });
    if (blockDataMap[bid]) { blockDataMap[bid].area_unit = nu; blockDataMap[bid].area_value = parseFloat(ndv) || 0; }
    showToast(`Block: Unit changed. Original value preserved.`, 'info');
    updateBlockAreaSummary(b);
    updateRemainingArea();
}
function updateBlockAreaSummary(card) {
    if (!card) return;
    const bid = card.getAttribute('data-block-id');
    const du = blockDisplayUnits[bid] || originalCampusAreaUnit;
    const ba = parseFloat(card.querySelector('.block-area-value')?.value) || 0;
    let as = 0;
    card.querySelectorAll('.area-card .area-value').forEach(i => { const val = parseFloat(i.value); if (!isNaN(val)) as += val; });
    let facilityAreaDeduction = 0;
    const allocs = blockAllocations[bid] || {};
    Object.keys(allocs).forEach(key => {
        if (allocs[key] && allocs[key].allocated) {
            let entry = null;
            for (const type of Object.keys(campusFacilityEntries)) {
                const found = campusFacilityEntries[type].find(e => e.id === key);
                if (found) { entry = found; break; }
            }
            if (entry && entry.area) {
                const areaValue = parseFloat(entry.area);
                if (!isNaN(areaValue) && areaValue > 0) {
                    const areaInOriginal = convertArea(areaValue, campusAreaUnit || 'sq_ft', originalCampusAreaUnit);
                    facilityAreaDeduction += areaInOriginal;
                }
            }
        }
    });
    const facilityAreaInBlockUnit = convertArea(facilityAreaDeduction, originalCampusAreaUnit, du);
    const rb = ba - as - facilityAreaInBlockUnit;
    const se = card.querySelector('.block-area-summary');
    if (se) {
        let rc = 'remaining-block';
        let warning = '';
        if (rb < 0) { rc = 'exceeded-block'; warning = ' ⚠️ Block area exceeds available space!'; }
        se.innerHTML = `<i class="fas fa-calculator"></i> Block Area: <strong>${ba.toFixed(2)} ${getUnitDisplayName(du)}</strong> | Additional: <strong>${as.toFixed(2)} ${getUnitDisplayName(du)}</strong> | Facilities: <strong>${facilityAreaInBlockUnit.toFixed(2)} ${getUnitDisplayName(du)}</strong> | <span class="${rc}">Remaining: <strong>${rb.toFixed(2)} ${getUnitDisplayName(du)}</strong>${warning}</span>`;
    }
    updateBlockHeaderSummary(card);
}
function updateBlockHeaderSummary(card) {
    if (!card) return;
    const bid = card.getAttribute('data-block-id');
    const du = blockDisplayUnits[bid] || originalCampusAreaUnit;
    const ba = parseFloat(card.querySelector('.block-area-value')?.value) || 0;
    const ac = card.querySelectorAll('.area-card').length, gc = card.querySelectorAll('.gate-card').length;
    const allocs = blockAllocations[bid] || {};
    let facilityCount = 0;
    Object.keys(allocs).forEach(key => {
        if (allocs[key] && allocs[key].allocated) {
            let isFacility = false;
            for (const type of Object.keys(campusFacilityEntries)) {
                if (campusFacilityEntries[type].find(e => e.id === key)) { isFacility = true; break; }
            }
            if (isFacility) facilityCount++;
        }
    });
    const st = card.querySelector('.block-subtitle');
    if (st) st.innerHTML = `<span><i class="fas fa-vector-square"></i> ${ba.toFixed(2)} ${getUnitDisplayName(du)}</span>${ac > 0 ? `<span><i class="fas fa-map"></i> ${ac} areas</span>` : ''}${gc > 0 ? `<span><i class="fas fa-door-open"></i> ${gc} gates</span>` : ''}${facilityCount > 0 ? `<span><i class="fas fa-building"></i> ${facilityCount} facilities</span>` : ''}`;
}
function toggleBlock(bid) { const b = document.getElementById(`block-${bid}`); if (b) b.classList.toggle('active'); }
function getBuildingAdditionalAreas() { if (!buildingData) return []; const a = parseJsonSafe(buildingData.additional_areas); return Array.isArray(a) ? a.filter(x => x && x.name) : []; }
function getBuildingCustomAmenities() { if (!buildingData) return []; const a = parseJsonSafe(buildingData.custom_amenities); return Array.isArray(a) ? a.filter(x => x && x.name) : []; }
function getBuildingAmenities() { if (!buildingData) return {}; return parseJsonSafe(buildingData.amenities) || {}; }
function generateDefaultArea(bid) {
    const u = blockDisplayUnits[bid] || originalCampusAreaUnit;
    const areaId = generateUUID();
    return `<div class="dynamic-card area-card" data-area-uuid="${areaId}"><div class="card-badge-sm">Area #1</div><div class="card-row" style="grid-template-columns:2fr 1fr 1fr;"><div class="form-group"><label>Area Name</label><input type="text" class="form-control area-name" placeholder="e.g., Playground"></div><div class="form-group"><label>Area Unit</label><select class="form-control area-unit" onchange="handleBlockAreaUnitChange(this,${bid})">${generateUnitOptions(u)}</select></div><div class="form-group"><label>Area Value</label><input type="number" class="form-control area-value" placeholder="Enter area" min="0" step="0.01" onchange="updateBlockAreaSummary(this.closest('.block-accordion'))"></div></div></div>`;
}
function generateDefaultGate() {
    const gateId = generateUUID();
    return `<div class="dynamic-card gate-card" data-gate-uuid="${gateId}"><div class="card-badge-sm">Gate #1</div><div class="card-row" style="grid-template-columns:1fr 1fr;"><div class="form-group"><label>Gate Name</label><input type="text" class="form-control gate-name" placeholder="e.g., Main Entrance"></div><div class="form-group"><label>Gate Number</label><input type="text" class="form-control gate-number" placeholder="e.g., G-01"></div></div></div>`;
}
function generateDefaultCustomAmenity() {
    const amenityId = generateUUID();
    return `<div class="custom-amenity-card" data-amenity-uuid="${amenityId}"><div class="card-badge-sm">Custom #1</div><div class="card-row" style="grid-template-columns:2fr 1fr;"><div class="form-group"><label>Amenity Name</label><input type="text" class="form-control custom-amenity-name" placeholder="e.g., Projector"></div><div class="form-group"><label>Quantity</label><input type="number" class="form-control custom-amenity-qty" value="1" min="1"></div></div></div>`;
}
function generateBuildingAreasDropdown(bid) {
    const a = getBuildingAdditionalAreas();
    if (a.length === 0) return '';
    let o = '<option value="">-- Select from Building Areas --</option>';
    a.forEach(x => { o += `<option value="${escapeHtml(x.name)}" data-area="${x.area || ''}" data-unit="${x.unit || ''}">${escapeHtml(x.name)} (${x.area || 'N/A'} ${formatUnit(x.unit)})</option>`; });
    return `<div class="building-area-selector"><label style="font-size:0.8rem;font-weight:600;color:var(--primary-color);margin-bottom:0.5rem;display:block;"><i class="fas fa-building"></i> Select from Building Areas</label><select class="form-control" onchange="applyBuildingAreaToBlock(this,${bid})">${o}</select></div>`;
}
function applyBuildingAreaToBlock(sel, bid) {
    if (!sel.value) return;
    const opt = sel.options[sel.selectedIndex], c = document.querySelector(`.block-areas-container[data-block-id="${bid}"]`);
    let t = null;
    c.querySelectorAll('.area-card').forEach(x => { if (x.querySelector('.area-name') && !x.querySelector('.area-name').value.trim() && !t) t = x; });
    if (!t) { addAreaToBlock(bid); t = c.querySelectorAll('.area-card')[c.querySelectorAll('.area-card').length - 1]; }
    if (t) { t.querySelector('.area-name').value = opt.value; t.querySelector('.area-value').value = opt.getAttribute('data-area') || ''; const us = t.querySelector('.area-unit'); if (us) { us.value = opt.getAttribute('data-unit') || blockDisplayUnits[bid] || originalCampusAreaUnit; } }
    sel.value = '';
    const card = document.getElementById(`block-${bid}`);
    if (card) updateBlockAreaSummary(card);
}
function addAreaToBlock(bid) {
    if (!blockAreaCounters[bid]) blockAreaCounters[bid] = 0;
    blockAreaCounters[bid]++;
    const c = blockAreaCounters[bid], u = blockDisplayUnits[bid] || originalCampusAreaUnit, container = document.querySelector(`.block-areas-container[data-block-id="${bid}"]`), card = document.createElement('div');
    card.className = 'dynamic-card area-card';
    const areaId = generateUUID();
    card.setAttribute('data-area-uuid', areaId);
    card.innerHTML = `<div class="card-badge-sm">Area #${c}</div><div class="card-row" style="grid-template-columns:2fr 1fr 1fr;"><div class="form-group"><label>Area Name</label><input type="text" class="form-control area-name" placeholder="e.g., Playground"></div><div class="form-group"><label>Area Unit</label><select class="form-control area-unit" onchange="handleBlockAreaUnitChange(this,${bid})">${generateUnitOptions(u)}</select></div><div class="form-group"><label>Area Value</label><input type="number" class="form-control area-value" placeholder="Enter area" min="0" step="0.01" onchange="updateBlockAreaSummary(document.getElementById('block-${bid}'))"></div></div><div style="text-align:right;margin-top:0.5rem;"><button type="button" class="btn-outline-danger" onclick="this.closest('.dynamic-card').remove();updateBlockAreaSummary(document.getElementById('block-${bid}'));"><i class="fas fa-trash"></i></button></div>`;
    container.appendChild(card);
    const bc = document.getElementById(`block-${bid}`);
    if (bc) updateBlockAreaSummary(bc);
}
function addGateToBlock(bid) {
    if (!blockGateCounters[bid]) blockGateCounters[bid] = 0;
    blockGateCounters[bid]++;
    const c = blockGateCounters[bid], container = document.querySelector(`.block-gates-container[data-block-id="${bid}"]`), card = document.createElement('div');
    card.className = 'dynamic-card gate-card';
    const gateId = generateUUID();
    card.setAttribute('data-gate-uuid', gateId);
    card.innerHTML = `<div class="card-badge-sm">Gate #${c}</div><div class="card-row" style="grid-template-columns:1fr 1fr;"><div class="form-group"><label>Gate Name</label><input type="text" class="form-control gate-name" placeholder="e.g., Main Entrance"></div><div class="form-group"><label>Gate Number</label><input type="text" class="form-control gate-number" placeholder="e.g., G-01"></div></div><div style="text-align:right;margin-top:0.5rem;"><button type="button" class="btn-outline-danger" onclick="this.closest('.dynamic-card').remove();updateBlockHeaderSummary(document.getElementById('block-${bid}'));"><i class="fas fa-trash"></i></button></div>`;
    container.appendChild(card);
}
function addCustomAmenityToBlock(bid) {
    if (!blockCustomAmenityCounters[bid]) blockCustomAmenityCounters[bid] = 0;
    blockCustomAmenityCounters[bid]++;
    const c = blockCustomAmenityCounters[bid], container = document.querySelector(`.block-custom-amenities-container[data-block-id="${bid}"]`), card = document.createElement('div');
    card.className = 'custom-amenity-card';
    const amenityId = generateUUID();
    card.setAttribute('data-amenity-uuid', amenityId);
    card.innerHTML = `<div class="card-badge-sm">Custom #${c}</div><div class="card-row" style="grid-template-columns:2fr 1fr;"><div class="form-group"><label>Amenity Name</label><input type="text" class="form-control custom-amenity-name" placeholder="e.g., Projector"></div><div class="form-group"><label>Quantity</label><input type="number" class="form-control custom-amenity-qty" value="1" min="1"></div></div><div style="text-align:right;margin-top:0.5rem;"><button type="button" class="btn-outline-danger" onclick="this.closest('.custom-amenity-card').remove()"><i class="fas fa-trash"></i></button></div>`;
    container.appendChild(card);
}
function getBlockDataFromDOM(bid) {
    const b = document.getElementById(`block-${bid}`);
    if (!b) return null;
    const nameInput = b.querySelector('.block-name');
    const n = nameInput?.value?.trim() || '';
    if (!n) return null;
    const ar = [];
    b.querySelectorAll('.area-card').forEach(c => {
        const nameEl = c.querySelector('.area-name');
        const valueEl = c.querySelector('.area-value');
        const unitEl = c.querySelector('.area-unit');
        const n = nameEl?.value?.trim() || '';
        const v = parseFloat(valueEl?.value) || 0;
        const u = unitEl?.value || '';
        let areaId = c.getAttribute('data-area-uuid');
        if (!areaId) { areaId = generateUUID(); c.setAttribute('data-area-uuid', areaId); }
        if (n) ar.push({ id: areaId, name: n, area: v, unit: u });
    });
    const ga = [];
    b.querySelectorAll('.gate-card').forEach(c => {
        const nameEl = c.querySelector('.gate-name');
        const numEl = c.querySelector('.gate-number');
        const n = nameEl?.value?.trim() || '';
        const nu = numEl?.value?.trim() || '';
        let gateId = c.getAttribute('data-gate-uuid');
        if (!gateId) { gateId = generateUUID(); c.setAttribute('data-gate-uuid', gateId); }
        if (n || nu) ga.push({ id: gateId, name: n, number: nu });
    });
    const cu = [];
    b.querySelectorAll('.custom-amenity-card').forEach(c => {
        const nameEl = c.querySelector('.custom-amenity-name');
        const qtyEl = c.querySelector('.custom-amenity-qty');
        const n = nameEl?.value?.trim() || '';
        const q = parseInt(qtyEl?.value) || 1;
        let amenityId = c.getAttribute('data-amenity-uuid');
        if (!amenityId) { amenityId = generateUUID(); c.setAttribute('data-amenity-uuid', amenityId); }
        if (n) cu.push({ id: amenityId, name: n, quantity: q });
    });
    const areaValueInput = b.querySelector('.block-area-value');
    const areaUnitSelect = b.querySelector('.block-area-unit');
    const dv = parseFloat(areaValueInput?.value) || 0;
    const du = areaUnitSelect?.value || blockDisplayUnits[bid] || originalCampusAreaUnit;
    const ov = convertArea(dv, du, originalCampusAreaUnit);
    const allocs = blockAllocations[bid] || {};
    const allocatedAmenities = {};
    const allocatedFacilityEntries = [];
    Object.keys(allocs).forEach(key => {
        if (allocs[key] && allocs[key].allocated) {
            let isFacility = false;
            for (const type of Object.keys(campusFacilityEntries)) {
                if (campusFacilityEntries[type].find(e => e.id === key)) { isFacility = true; break; }
            }
            if (isFacility) { allocatedFacilityEntries.push(key); } else { allocatedAmenities[key] = allocs[key].quantity || 1; }
        }
    });
    return { id: bid, name: n, code: b.querySelector('.block-code')?.value?.trim() || '', description: b.querySelector('.block-description')?.value?.trim() || '', status: b.querySelector('.block-status')?.value || 'active', floors: parseInt(b.querySelector('.block-floors')?.value) || 1, area_value: parseFloat(ov) || 0, area_unit: originalCampusAreaUnit, additional_areas: ar, gates: ga, custom_amenities: cu, allocated_amenities: allocatedAmenities, allocated_facility_entries: allocatedFacilityEntries };
}
async function saveAllBlocksAndContinue() {
    saveCurrentBlockData();
    const bi = document.getElementById('buildingId').value;
    if (!bi) { showToast('Please select a building', 'error'); return; }
    const newBlocks = [], existingBlocks = [], allBlocks = [];
    const existingBlockIds = buildingData && buildingData.blocks ? buildingData.blocks.map(b => parseInt(b.id)) : [];
    Object.keys(blockDataMap).forEach(bid => {
        const domData = getBlockDataFromDOM(parseInt(bid));
        if (domData && domData.name) {
            domData.area_value = parseFloat(domData.area_value) || 0;
            const isNumericId = !isNaN(parseInt(bid));
            const isExisting = isNumericId && existingBlockIds.includes(parseInt(bid));
            if (isExisting) { domData.id = parseInt(bid); existingBlocks.push(domData); } else { if (domData.id) delete domData.id; newBlocks.push(domData); }
            allBlocks.push(domData);
        } else {
            const stored = blockDataMap[bid];
            if (stored && stored.name) {
                const b = document.getElementById(`block-${bid}`);
                if (b) {
                    const dv = parseFloat(b.querySelector('.block-area-value')?.value) || 0;
                    const du = blockDisplayUnits[bid] || originalCampusAreaUnit;
                    stored.area_value = parseFloat(convertArea(dv, du, originalCampusAreaUnit)) || 0;
                    stored.area_unit = originalCampusAreaUnit;
                }
                const isNumericId = !isNaN(parseInt(bid));
                const isExisting = isNumericId && existingBlockIds.includes(parseInt(bid));
                if (isExisting) { stored.id = parseInt(bid); existingBlocks.push(stored); } else { if (stored.id) delete stored.id; newBlocks.push(stored); }
                allBlocks.push(stored);
            }
        }
    });
    if (allBlocks.length === 0) { showToast('Please fill at least one block name', 'error'); return; }
    const tba = getTotalBlockAreaInOriginalUnit();
    const r = originalCampusAreaValue - tba;
    if (r < -0.01) { if (!confirm(`Total block area exceeds campus area. Continue?`)) return; }
    const fd = new FormData();
    fd.append('building_id', bi);
    fd.append('block_type', selectedBlockType);
    fd.append('blocks', JSON.stringify(allBlocks));
    fd.append('new_blocks', JSON.stringify(newBlocks));
    fd.append('existing_blocks', JSON.stringify(existingBlocks));
    fd.append('remaining_area', r);
    fd.append('_token', CSRF_TOKEN);
    try {
        const resp = await fetch(`${API_BASE_URL}/blocks`, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const result = await resp.json();
        if (result.success) {
            showToast('Blocks saved! Moving to Floors...');
            await loadBuildingData();
            const floorBuildingSelect = document.getElementById('floorBuildingId');
            if (floorBuildingSelect) { floorBuildingSelect.value = bi; await loadBlocksForFloors(); }
            setTimeout(() => goToStep(3), 1000);
        } else { showToast(result.message || 'Failed to save blocks', 'error'); }
    } catch (e) { console.error('Error saving blocks:', e); showToast('An error occurred while saving blocks', 'error'); }
}

// ===== STEP 3: FLOORS =====
function generateFloorAmenitiesHTML(id) {
    return `<div class="amenity-group"><h5><i class="fas fa-snowflake"></i> AC</h5><div class="checkbox-group"><div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="has_ac" onchange="toggleFloorAmenityCount(this,${id},'has_ac')"><label>AC</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="has_ac" min="1" value="1"></div></div></div><div class="amenity-group"><h5><i class="fas fa-tint"></i> Water</h5><div class="checkbox-group"><div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="has_water_facility" onchange="toggleFloorAmenityCount(this,${id},'has_water_facility')"><label>Water</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="has_water_facility" min="1" value="1"></div></div></div><div class="amenity-group"><h5><i class="fas fa-fire-extinguisher"></i> Fire Safety</h5><div class="checkbox-group"><div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="has_fire_extinguisher" onchange="toggleFloorAmenityCount(this,${id},'has_fire_extinguisher')"><label>Fire Ext.</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="has_fire_extinguisher" min="1" value="1"></div></div></div><div class="amenity-group"><h5><i class="fas fa-elevator"></i> Lift</h5><div class="checkbox-group"><div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="has_lift" onchange="toggleFloorAmenityCount(this,${id},'has_lift')"><label>Lift</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="has_lift" min="1" value="1"></div></div></div><div class="amenity-group"><h5><i class="fas fa-toilet"></i> Washroom</h5><div class="checkbox-group"><div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="has_washroom" onchange="toggleFloorAmenityCount(this,${id},'has_washroom')"><label>Washroom</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="has_washroom" min="1" value="1"></div></div></div><div class="amenity-group"><h5><i class="fas fa-star"></i> Other</h5><div class="checkbox-group"><div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="has_wifi" onchange="toggleFloorAmenityCount(this,${id},'has_wifi')"><label>Wi-Fi</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="has_wifi" min="1" value="1"></div></div></div>`;
}
async function loadBlocksForFloors() {
    const bi = document.getElementById('floorBuildingId').value;
    const bs = document.getElementById('blockSelect');
    document.getElementById('existingFloorsSection').style.display = 'none';
    document.getElementById('blockAllocSummaryForFloors').style.display = 'none';
    document.getElementById('floors-container').innerHTML = '';
    document.getElementById('floorSelectorCard').style.display = 'none';
    document.getElementById('floorDetailPanel').classList.remove('active');
    floorDataMap = {};
    existingFloorIds = [];
    if (!bi) { bs.innerHTML = '<option value="">Select a building block</option>'; return; }
    bs.innerHTML = '<option value="">Loading...</option>';
    try {
        const r = await fetch(`${API_BASE_URL}/rooms/blocks/${bi}`, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
        const result = await r.json();
        if (result.success) {
            let o = '<option value="">Select a building block</option>';
            result.data.forEach(b => { o += `<option value="${b.id}">${escapeHtml(b.name)}</option>`; });
            bs.innerHTML = o;
        } else { bs.innerHTML = '<option value="">No blocks found</option>'; }
    } catch (e) { bs.innerHTML = '<option value="">Error</option>'; }
}
async function loadBlockForFloors() {
    const blockId = document.getElementById('blockSelect').value;
    const container = document.getElementById('floors-container');
    const existingSection = document.getElementById('existingFloorsSection');
    const allocSummary = document.getElementById('blockAllocSummaryForFloors');
    const floorSelectorCard = document.getElementById('floorSelectorCard');
    const floorDetailPanel = document.getElementById('floorDetailPanel');
    container.innerHTML = '';
    existingSection.style.display = 'none';
    allocSummary.style.display = 'none';
    floorSelectorCard.style.display = 'none';
    floorDetailPanel.classList.remove('active');
    floorDataMap = {};
    existingFloorIds = [];
    currentBlockForFloors = null;
    if (!blockId) return;
    try {
        const r = await fetch(`${API_BASE_URL}/blocks/${blockId}/details`, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
        const result = await r.json();
        if (result.success && result.data) {
            currentBlockForFloors = result.data;
            const block = result.data;
            const allocs = block.allocated_amenities || {};
            const allocFacilities = block.allocated_facility_entries || [];
            let summaryHtml = '';
            Object.keys(allocs).forEach(key => {
                const qty = allocs[key] || 0;
                const displayName = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                summaryHtml += `<span class="block-alloc-summary"><i class="fas fa-cube"></i> ${displayName}: ${qty}</span>`;
            });
            allocFacilities.forEach(entryId => {
                let name = entryId;
                for (const type of Object.keys(campusFacilityEntries)) {
                    const found = campusFacilityEntries[type].find(e => e.id === entryId);
                    if (found) { name = found.name; break; }
                }
                summaryHtml += `<span class="block-alloc-summary" style="background:linear-gradient(135deg,#fef3c7,#fde68a);"><i class="fas fa-building"></i> ${escapeHtml(name)}</span>`;
            });
            if (summaryHtml) { document.getElementById('blockAllocSummaryDetails').innerHTML = summaryHtml; allocSummary.style.display = 'block'; } else { allocSummary.style.display = 'none'; }
            await loadExistingFloorsForBlock(blockId);
            const floorDropdown = document.getElementById('floorSelectorDropdown');
            floorDropdown.innerHTML = '<option value="">-- Select a floor --</option>';
            try {
                const floorsResp = await fetch(`${API_BASE_URL}/floors/by-block/${blockId}`, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                const floorsResult = await floorsResp.json();
                if (floorsResult.success && floorsResult.data) {
                    floorsResult.data.forEach(floor => {
                        const fid = floor.id;
                        floorDataMap[fid] = floor;
                        existingFloorIds.push(fid);
                        const option = document.createElement('option');
                        option.value = fid;
                        option.textContent = floor.floor_number || `Floor ${fid}`;
                        floorDropdown.appendChild(option);
                    });
                    const floorsCount = parseInt(block.floors) || 1;
                    const existingCount = existingFloorIds.length;
                    for (let i = existingCount + 1; i <= floorsCount; i++) {
                        const fid = `new_${i}`;
                        if (!floorDataMap[fid]) {
                            floorDataMap[fid] = { id: fid, floor_number: `Floor ${i}`, rooms: '', description: '', area_value: '', area_unit: 'sq_ft', additional_areas: [], gates: [], custom_amenities: [], allocated_amenities: {}, allocated_facility_entries: [], is_basement: false };
                            existingFloorIds.push(fid);
                            const option = document.createElement('option');
                            option.value = fid;
                            option.textContent = `Floor ${i} (new)`;
                            floorDropdown.appendChild(option);
                        }
                    }
                }
            } catch (e) { console.error('Error loading floors:', e); }
            document.getElementById('floorCountBadge').textContent = `${existingFloorIds.length} floors`;
            floorSelectorCard.style.display = 'block';
            if (existingFloorIds.length > 0) { floorDropdown.value = existingFloorIds[0]; onFloorSelectorChange(); } else { addFloorFromDropdown(); }
        } else { showToast('Failed to load block data', 'error'); }
    } catch (e) { console.error('Error loading block:', e); showToast('Error loading block', 'error'); }
}
async function loadExistingFloorsForBlock(blockId) {
    const existingSection = document.getElementById('existingFloorsSection');
    const countSpan = document.getElementById('existingFloorsCount');
    const noFloorsMsg = document.getElementById('noExistingFloors');
    const existingContainer = document.getElementById('existingFloorsContainer');
    try {
        const r = await fetch(`${API_BASE_URL}/floors/by-block/${blockId}`, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
        const result = await r.json();
        if (result.success && result.data && result.data.length > 0) {
            countSpan.textContent = `${result.data.length} floors`;
            existingSection.style.display = 'block';
            let h = '';
            result.data.forEach(f => {
                const isBasement = f.is_basement ? '🏚️ Basement' : '';
                h += `<div class="existing-floor-card"><span class="existing-badge"><i class="fas fa-check-circle"></i> Existing</span><div class="floor-info"><div><div class="floor-name"><i class="fas fa-layer-group"></i> ${escapeHtml(f.floor_number || f.name)} ${isBasement}</div><div class="floor-meta">Rooms: ${f.total_rooms || f.rooms || 'N/A'}</div></div></div></div>`;
            });
            existingContainer.innerHTML = h;
            noFloorsMsg.style.display = 'none';
            return result.data.length;
        } else { existingSection.style.display = 'none'; noFloorsMsg.style.display = 'block'; countSpan.textContent = '0 floors'; existingContainer.innerHTML = ''; return 0; }
    } catch (e) { existingSection.style.display = 'none'; noFloorsMsg.style.display = 'block'; countSpan.textContent = '0 floors'; return 0; }
}
function saveCurrentFloorData() {
    if (!selectedFloorId || !floorDataMap[selectedFloorId]) return;
    const data = getFloorDataFromDOM(selectedFloorId);
    if (data && data.floor_number) {
        floorDataMap[selectedFloorId] = { ...floorDataMap[selectedFloorId], ...data };
        const floorCard = document.getElementById(`floor-${selectedFloorId}`);
        if (floorCard) {
            const areaValueInput = floorCard.querySelector('.floor-area-value');
            const areaUnitSelect = floorCard.querySelector('.floor-area-unit');
            if (areaValueInput) {
                const dv = parseFloat(areaValueInput.value) || 0;
                const du = areaUnitSelect ? areaUnitSelect.value : 'sq_ft';
                floorDataMap[selectedFloorId].area_value = dv;
                floorDataMap[selectedFloorId].area_unit = du;
            }
        }
    }
}
function onFloorSelectorChange() {
    saveCurrentFloorData();
    const fid = document.getElementById('floorSelectorDropdown').value;
    if (!fid || !floorDataMap[fid]) {
        document.getElementById('floorDetailPanel').classList.remove('active');
        document.getElementById('floors-container').innerHTML = '';
        document.getElementById('selectedFloorBadge').style.display = 'none';
        document.getElementById('removeSelectedFloorBtn').style.display = 'none';
        selectedFloorId = null;
        return;
    }
    selectedFloorId = fid;
    const floor = floorDataMap[fid];
    document.getElementById('selectedFloorBadge').style.display = 'inline-flex';
    document.getElementById('selectedFloorName').textContent = floor.floor_number || `Floor ${fid}`;
    document.getElementById('removeSelectedFloorBtn').style.display = 'inline-flex';
    document.getElementById('floorDetailPanel').classList.add('active');
    const container = document.getElementById('floors-container');
    const html = buildFloorHTML(fid, floor);
    container.innerHTML = html;
}

function buildFloorHTML(fid, floor) {
    let allocHtml = '';
    let amenityDataForRoom = {};
    if (currentBlockForFloors) {
        const block = currentBlockForFloors;
        const allocs = block.allocated_amenities || {};
        const allocFacilities = block.allocated_facility_entries || [];
        const floorAllocs = floor.allocated_amenities || {};
        const floorFacilities = floor.allocated_facility_entries || [];
        const amenityKeys = Object.keys(allocs);
        if (amenityKeys.length > 0) {
            allocHtml += `<div style="margin-bottom:6px;font-weight:600;font-size:0.8rem;color:var(--text-dark);"><i class="fas fa-concierge-bell"></i> Block Amenities</div>`;
            amenityKeys.forEach(key => {
                const total = allocs[key] || 0;
                const floorQty = floorAllocs[key] || 0;
                const displayName = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                const isChecked = floorQty > 0;
                allocHtml += `<div class="allocation-item">
                    <input type="checkbox" class="floor-alloc-check" data-floor="${fid}" data-key="${key}" data-total="${total}" ${isChecked ? 'checked' : ''}>
                    <span class="alloc-name">${displayName}</span>
                    <div class="alloc-stats">
                        <span class="used">Allocated: ${total}</span>
                    </div>
                    <input type="number" class="floor-alloc-qty ${isChecked ? 'show' : ''}" data-floor="${fid}" data-key="${key}" min="1" max="${total}" value="${isChecked ? floorQty : 1}" ${isChecked ? '' : 'disabled'}>
                    <span class="alloc-badge ${isChecked ? 'included' : 'excluded'}">${isChecked ? 'Included' : 'Excluded'}</span>
                </div>`;
                amenityDataForRoom[key] = floorQty || total;
            });
        }
        if (allocFacilities.length > 0) {
            allocHtml += `<div style="margin-top:8px;margin-bottom:6px;font-weight:600;font-size:0.8rem;color:var(--text-dark);"><i class="fas fa-building"></i> Block Facilities</div>`;
            allocFacilities.forEach(entryId => {
                let name = entryId;
                let facilityType = 'facility';
                let facilityEntry = null;
                let areaValue = 0;
                for (const type of Object.keys(campusFacilityEntries)) {
                    const found = campusFacilityEntries[type].find(e => e.id === entryId);
                    if (found) { name = found.name; facilityType = type; facilityEntry = found; areaValue = found.area || 0; break; }
                }
                const isChecked = floorFacilities.includes(entryId);
                let extraInfo = '';
                if (facilityType === 'washrooms' && facilityEntry) { extraInfo = ` 🚽${facilityEntry.toilets||0} 🚹${facilityEntry.urinals||0} 🚰${facilityEntry.washbasins||0}`; }
                if (areaValue > 0) extraInfo += ` 📐${areaValue.toFixed(1)} sqft`;
                allocHtml += `<div class="allocation-item">
                    <input type="checkbox" class="floor-alloc-check" data-floor="${fid}" data-key="${entryId}" data-total="1" data-facility="true" ${isChecked ? 'checked' : ''} onchange="updateFloorAreaSummary('${fid}')">
                    <span class="alloc-name">${escapeHtml(name)}${extraInfo}</span>
                    <div class="alloc-stats">
                        <span class="used">Allocated: 1</span>
                    </div>
                    <span class="alloc-badge ${isChecked ? 'included' : 'excluded'}">${isChecked ? 'Included' : 'Excluded'}</span>
                </div>`;
                if (!amenityDataForRoom['facilities']) amenityDataForRoom['facilities'] = [];
                amenityDataForRoom['facilities'].push({ id: entryId, name: name, type: facilityType, entry: facilityEntry, area: areaValue });
            });
        }
    }
    floorAmenityDataForRooms[fid] = amenityDataForRoom;
    let areasHtml = '';
    const areas = floor.additional_areas || [];
    if (areas.length === 0) {
        const areaId = generateUUID();
        areasHtml = `<div class="dynamic-card area-card" data-area-uuid="${areaId}"><div class="card-badge-sm">Area #1</div><div class="card-row" style="grid-template-columns:2fr 1fr 1fr;"><div class="form-group"><label>Area Name</label><input type="text" class="form-control area-name" placeholder="e.g., Office"></div><div class="form-group"><label>Area Unit</label><select class="form-control area-unit"><option value="sq_ft">Sq. Ft.</option><option value="sq_m">Sq. M.</option></select></div><div class="form-group"><label>Area Value</label><input type="number" class="form-control area-value" placeholder="Enter area" min="0" step="0.01" onchange="updateFloorAreaSummary('${fid}')"></div></div></div>`;
    } else {
        areas.forEach((area, idx) => {
            const id = idx + 1;
            const areaId = area.id || generateUUID();
            areasHtml += `
                <div class="dynamic-card area-card" data-area-id="${id}" data-area-uuid="${areaId}">
                    <div class="card-badge-sm">Area #${id}</div>
                    <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;">
                        <div class="form-group"><label>Area Name</label><input type="text" class="form-control area-name" value="${escapeHtml(area.name || '')}" placeholder="e.g., Office"></div>
                        <div class="form-group"><label>Area Unit</label><select class="form-control area-unit"><option value="sq_ft" ${area.unit === 'sq_ft' ? 'selected' : ''}>Sq. Ft.</option><option value="sq_m" ${area.unit === 'sq_m' ? 'selected' : ''}>Sq. M.</option></select></div>
                        <div class="form-group"><label>Area Value</label><input type="number" class="form-control area-value" value="${area.area || ''}" placeholder="Enter area" min="0" step="0.01" onchange="updateFloorAreaSummary('${fid}')"></div>
                    </div>
                    <div style="text-align:right;margin-top:0.5rem;">
                        <button type="button" class="btn-outline-danger" onclick="this.closest('.dynamic-card').remove();updateFloorAreaSummary('${fid}')"><i class="fas fa-trash"></i></button>
                    </div>
                </div>`;
        });
    }
    let gatesHtml = '';
    const gates = floor.gates || [];
    if (gates.length === 0) {
        const gateId = generateUUID();
        gatesHtml = `<div class="dynamic-card gate-card" data-gate-uuid="${gateId}"><div class="card-badge-sm">Entry #1</div><div class="card-row" style="grid-template-columns:1fr 1fr;"><div class="form-group"><label>Entry Name</label><input type="text" class="form-control gate-name" placeholder="e.g., Main Door"></div><div class="form-group"><label>Entry Number</label><input type="text" class="form-control gate-number" placeholder="e.g., E-01"></div></div></div>`;
    } else {
        gates.forEach((gate, idx) => {
            const id = idx + 1;
            const gateId = gate.id || generateUUID();
            gatesHtml += `
                <div class="dynamic-card gate-card" data-gate-id="${id}" data-gate-uuid="${gateId}">
                    <div class="card-badge-sm">Entry #${id}</div>
                    <div class="card-row" style="grid-template-columns:1fr 1fr;">
                        <div class="form-group"><label>Entry Name</label><input type="text" class="form-control gate-name" value="${escapeHtml(gate.name || '')}" placeholder="e.g., Main Door"></div>
                        <div class="form-group"><label>Entry Number</label><input type="text" class="form-control gate-number" value="${escapeHtml(gate.number || '')}" placeholder="e.g., E-01"></div>
                    </div>
                    <div style="text-align:right;margin-top:0.5rem;">
                        <button type="button" class="btn-outline-danger" onclick="this.closest('.dynamic-card').remove()"><i class="fas fa-trash"></i></button>
                    </div>
                </div>`;
        });
    }
    let customHtml = '';
    const customAmenities = floor.custom_amenities || [];
    if (customAmenities.length === 0) {
        const amenityId = generateUUID();
        customHtml = `<div class="custom-amenity-card" data-amenity-uuid="${amenityId}"><div class="card-badge-sm">Custom #1</div><div class="card-row" style="grid-template-columns:2fr 1fr;"><div class="form-group"><label>Amenity Name</label><input type="text" class="form-control custom-amenity-name" placeholder="e.g., Projector"></div><div class="form-group"><label>Quantity</label><input type="number" class="form-control custom-amenity-qty" min="1" value="1"></div></div></div>`;
    } else {
        customAmenities.forEach((item, idx) => {
            const id = idx + 1;
            const amenityId = item.id || generateUUID();
            customHtml += `
                <div class="custom-amenity-card" data-custom-id="${id}" data-amenity-uuid="${amenityId}">
                    <div class="card-badge-sm">Custom #${id}</div>
                    <div class="card-row" style="grid-template-columns:2fr 1fr;">
                        <div class="form-group"><label>Amenity Name</label><input type="text" class="form-control custom-amenity-name" value="${escapeHtml(item.name || '')}" placeholder="e.g., Projector"></div>
                        <div class="form-group"><label>Quantity</label><input type="number" class="form-control custom-amenity-qty" value="${item.quantity || 1}" min="1"></div>
                    </div>
                    <div style="text-align:right;margin-top:0.5rem;">
                        <button type="button" class="btn-outline-danger" onclick="this.closest('.custom-amenity-card').remove()"><i class="fas fa-trash"></i></button>
                    </div>
                </div>`;
        });
    }
    const floorAreaDisplay = buildFloorAreaDeductionHTML(fid);
    const isBasement = floor.is_basement || false;
    const basementChecked = isBasement ? 'checked' : '';
    return `<div class="floor-card ${isBasement ? 'basement' : ''}" id="floor-${fid}" data-floor-id="${fid}">
        <div class="card-badge">${floor.id && !floor.id.toString().startsWith('new_') ? 'Existing Floor' : 'New Floor'} ${isBasement ? '🏚️ Basement' : ''}</div>
        <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
            <div class="form-group"><label class="form-label">Floor Number/Name <span style="color:#dc3545;">*</span></label><input type="text" class="form-control floor-number" value="${escapeHtml(floor.floor_number || '')}" placeholder="e.g., Ground Floor" maxlength="50" onchange="updateFloorDropdownLabel('${fid}', this.value)"></div>
            <div class="form-group"><label class="form-label">Number of Rooms</label><input type="number" class="form-control floor-rooms" value="${floor.rooms || ''}" placeholder="e.g., 10" min="0"></div>
            <div class="form-group" style="display:flex;align-items:center;gap:10px;padding-top:8px;">
                <input type="checkbox" id="floorIsBasement-${fid}" ${basementChecked} onchange="toggleFloorBasement('${fid}')">
                <label for="floorIsBasement-${fid}" style="margin:0;font-weight:600;color:var(--text-dark);">Is Basement</label>
            </div>
        </div>
        <div class="form-group" style="margin-top:0.75rem;"><label class="form-label">Description</label><textarea class="form-control floor-description" rows="2" placeholder="Brief description...">${escapeHtml(floor.description || '')}</textarea></div>
        <div class="section-divider"><h4><i class="fas fa-vector-square"></i> Total Area</h4></div>
        <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div class="form-group"><label>Area Unit</label><select class="form-control floor-area-unit"><option value="sq_ft" ${floor.area_unit === 'sq_ft' ? 'selected' : ''}>Sq. Ft.</option><option value="sq_m" ${floor.area_unit === 'sq_m' ? 'selected' : ''}>Sq. M.</option></select></div>
            <div class="form-group"><label>Area Value</label><input type="number" class="form-control floor-area-value" value="${floor.area_value || ''}" placeholder="Enter area" min="0" step="0.01" onchange="updateFloorAreaSummary('${fid}')"></div>
        </div>
        ${floorAreaDisplay}
        <div class="section-divider"><h4><i class="fas fa-map"></i> Additional Areas</h4></div>
        <div class="floor-areas-container" data-floor-id="${fid}">${areasHtml}</div>
        <div class="add-btn-row"><button type="button" class="btn-outline-primary" onclick="addAreaToFloor('${fid}')"><i class="fas fa-plus"></i> Add Area</button></div>
        <div class="section-divider"><h4><i class="fas fa-door-open"></i> Entry/Exit</h4></div>
        <div class="floor-gates-container" data-floor-id="${fid}">${gatesHtml}</div>
        <div class="add-btn-row"><button type="button" class="btn-outline-primary" onclick="addGateToFloor('${fid}')"><i class="fas fa-plus"></i> Add Entry</button></div>
        <div class="section-divider"><h4><i class="fas fa-concierge-bell"></i> Floor Amenities (from Block)</h4></div>
        <div class="allocation-tracker" id="floorAllocTracker-${fid}" style="padding:0.5rem;">${allocHtml || '<div style="color:var(--text-muted);font-size:0.85rem;">No block resources allocated.</div>'}</div>
        <div class="section-divider"><h4><i class="fas fa-plus-circle" style="color:#10b981;"></i> Custom Amenities (Floor Specific)</h4></div>
        <div class="floor-custom-amenities-container" data-floor-id="${fid}">${customHtml}</div>
        <div class="add-btn-row"><button type="button" class="btn-outline-success" onclick="addCustomAmenityToFloor('${fid}')"><i class="fas fa-plus"></i> Add Custom</button></div>
        <div style="text-align:right;margin-top:1rem;">
            <button type="button" class="btn-outline-danger" onclick="removeFloorFromDropdown('${fid}')"><i class="fas fa-trash"></i> Remove Floor</button>
        </div>
    </div>`;
}

function toggleFloorBasement(fid) {
    const cb = document.getElementById(`floorIsBasement-${fid}`);
    const floor = floorDataMap[fid];
    if (floor) {
        floor.is_basement = cb.checked;
        const floorCard = document.getElementById(`floor-${fid}`);
        if (floorCard) {
            floorCard.classList.toggle('basement', cb.checked);
            const badge = floorCard.querySelector('.card-badge');
            if (badge) badge.textContent = (floor.id && !floor.id.toString().startsWith('new_') ? 'Existing Floor' : 'New Floor') + (cb.checked ? ' 🏚️ Basement' : '');
        }
        if (cb.checked) {
            const numInput = document.getElementById(`floor-${fid}`).querySelector('.floor-number');
            if (numInput && !numInput.value.toLowerCase().includes('basement')) {
                const existingBasements = Object.keys(floorDataMap).filter(id => floorDataMap[id] && floorDataMap[id].is_basement && id !== fid).length;
                numInput.value = `Basement ${existingBasements + 1}`;
                updateFloorDropdownLabel(fid, numInput.value);
            }
        }
    }
}

function buildFloorAreaDeductionHTML(fid) {
    return `<div class="area-deduction-summary" id="floorAreaSummary-${fid}">
        <div class="deduction-item"><span>Total Floor Area:</span><span id="floorTotalArea-${fid}">0.00</span></div>
        <div class="deduction-item"><span>Facilities Area Deduction:</span><span id="floorFacilityDeduction-${fid}">0.00</span></div>
        <div class="deduction-item"><span>Additional Areas:</span><span id="floorAdditionalArea-${fid}">0.00</span></div>
        <div class="deduction-item total-row"><span>Remaining Area:</span><span id="floorRemainingArea-${fid}" class="positive">0.00</span></div>
    </div>`;
}

function updateFloorAreaSummary(fid) {
    const floorCard = document.getElementById(`floor-${fid}`);
    if (!floorCard) return;
    const areaValue = parseFloat(floorCard.querySelector('.floor-area-value')?.value) || 0;
    const areaUnit = floorCard.querySelector('.floor-area-unit')?.value || 'sq_ft';
    let additionalArea = 0;
    floorCard.querySelectorAll('.floor-areas-container .area-value').forEach(el => { const val = parseFloat(el.value); if (!isNaN(val)) additionalArea += val; });
    let facilityDeduction = 0;
    const floorData = floorDataMap[fid] || {};
    const selectedFacilities = floorData.allocated_facility_entries || [];
    selectedFacilities.forEach(entryId => {
        for (const type of Object.keys(campusFacilityEntries)) {
            const found = campusFacilityEntries[type].find(e => e.id === entryId);
            if (found && found.area) {
                const areaVal = parseFloat(found.area);
                if (!isNaN(areaVal) && areaVal > 0) {
                    const areaInFloorUnit = convertArea(areaVal, campusAreaUnit || 'sq_ft', areaUnit);
                    facilityDeduction += areaInFloorUnit;
                }
                break;
            }
        }
    });
    if (campusFacilityEntries.warehouse) {
        campusFacilityEntries.warehouse.forEach(entry => {
            if (selectedFacilities.includes(entry.id) && entry.area) {
                const areaVal = parseFloat(entry.area);
                if (!isNaN(areaVal) && areaVal > 0) {
                    const areaInFloorUnit = convertArea(areaVal, campusAreaUnit || 'sq_ft', areaUnit);
                    facilityDeduction += areaInFloorUnit;
                }
            }
        });
    }
    const remaining = areaValue - additionalArea - facilityDeduction;
    const totalEl = document.getElementById(`floorTotalArea-${fid}`);
    const facilityEl = document.getElementById(`floorFacilityDeduction-${fid}`);
    const additionalEl = document.getElementById(`floorAdditionalArea-${fid}`);
    const remainingEl = document.getElementById(`floorRemainingArea-${fid}`);
    if (totalEl) totalEl.textContent = areaValue.toFixed(2);
    if (facilityEl) facilityEl.textContent = facilityDeduction.toFixed(2);
    if (additionalEl) additionalEl.textContent = additionalArea.toFixed(2);
    if (remainingEl) { remainingEl.textContent = remaining.toFixed(2); remainingEl.className = remaining >= 0 ? 'positive' : 'negative'; }
}

function updateFloorDropdownLabel(fid, name) {
    const dropdown = document.getElementById('floorSelectorDropdown');
    if (dropdown) {
        const option = dropdown.querySelector(`option[value="${fid}"]`);
        if (option) option.textContent = name || `Floor ${fid}`;
    }
    const badge = document.getElementById('selectedFloorName');
    if (badge) badge.textContent = name || `Floor ${fid}`;
}

function addFloorFromDropdown() {
    saveCurrentFloorData();
    const fid = `new_${Date.now()}`;
    const floorData = { id: fid, floor_number: `Floor ${existingFloorIds.length + 1}`, rooms: '', description: '', area_value: '', area_unit: 'sq_ft', additional_areas: [], gates: [], custom_amenities: [], allocated_amenities: {}, allocated_facility_entries: [], is_basement: false };
    floorDataMap[fid] = floorData;
    existingFloorIds.push(fid);
    const dropdown = document.getElementById('floorSelectorDropdown');
    const option = document.createElement('option');
    option.value = fid;
    option.textContent = floorData.floor_number;
    dropdown.appendChild(option);
    document.getElementById('floorCountBadge').textContent = `${existingFloorIds.length} floors`;
    dropdown.value = fid;
    onFloorSelectorChange();
    showToast('New floor added!', 'success');
}

function removeFloorFromDropdown(fid) {
    saveCurrentFloorData();
    if (existingFloorIds.length <= 1) { showToast('At least one floor required', 'error'); return; }
    if (!confirm('Are you sure you want to remove this floor?')) return;
    const index = existingFloorIds.indexOf(fid);
    if (index > -1) existingFloorIds.splice(index, 1);
    delete floorDataMap[fid];
    const container = document.getElementById('floors-container');
    const floorCard = document.getElementById(`floor-${fid}`);
    if (floorCard) {
        floorCard.style.opacity = '0';
        floorCard.style.transform = 'scale(0.95)';
        setTimeout(() => {
            floorCard.remove();
            const dropdown = document.getElementById('floorSelectorDropdown');
            const option = dropdown.querySelector(`option[value="${fid}"]`);
            if (option) option.remove();
            document.getElementById('floorCountBadge').textContent = `${existingFloorIds.length} floors`;
            if (existingFloorIds.length > 0) { dropdown.value = existingFloorIds[0]; onFloorSelectorChange(); } else { document.getElementById('floorDetailPanel').classList.remove('active'); document.getElementById('selectedFloorBadge').style.display = 'none'; document.getElementById('removeSelectedFloorBtn').style.display = 'none'; selectedFloorId = null; }
        }, 300);
    } else {
        const dropdown = document.getElementById('floorSelectorDropdown');
        const option = dropdown.querySelector(`option[value="${fid}"]`);
        if (option) option.remove();
        document.getElementById('floorCountBadge').textContent = `${existingFloorIds.length} floors`;
        if (existingFloorIds.length > 0) { dropdown.value = existingFloorIds[0]; onFloorSelectorChange(); }
    }
    showToast('Floor removed', 'info');
}

function removeSelectedFloor() { if (selectedFloorId) removeFloorFromDropdown(selectedFloorId); }

function addAreaToFloor(fid) {
    if (!floorAreaCounters[fid]) floorAreaCounters[fid] = 0;
    floorAreaCounters[fid]++;
    const c = floorAreaCounters[fid];
    const container = document.querySelector(`.floor-areas-container[data-floor-id="${fid}"]`);
    const card = document.createElement('div');
    card.className = 'dynamic-card area-card';
    const areaId = generateUUID();
    card.setAttribute('data-area-uuid', areaId);
    card.innerHTML = `<div class="card-badge-sm">Area #${c}</div><div class="card-row" style="grid-template-columns:2fr 1fr 1fr;"><div class="form-group"><label>Area Name</label><input type="text" class="form-control area-name" placeholder="e.g., Office"></div><div class="form-group"><label>Area Unit</label><select class="form-control area-unit"><option value="sq_ft">Sq. Ft.</option><option value="sq_m">Sq. M.</option></select></div><div class="form-group"><label>Area Value</label><input type="number" class="form-control area-value" placeholder="Enter area" min="0" step="0.01" onchange="updateFloorAreaSummary('${fid}')"></div></div><div style="text-align:right;margin-top:0.5rem;"><button type="button" class="btn-outline-danger" onclick="this.closest('.dynamic-card').remove();updateFloorAreaSummary('${fid}')"><i class="fas fa-trash"></i></button></div>`;
    container.appendChild(card);
    updateFloorAreaSummary(fid);
}

function addGateToFloor(fid) {
    if (!floorGateCounters[fid]) floorGateCounters[fid] = 0;
    floorGateCounters[fid]++;
    const c = floorGateCounters[fid];
    const container = document.querySelector(`.floor-gates-container[data-floor-id="${fid}"]`);
    const card = document.createElement('div');
    card.className = 'dynamic-card gate-card';
    const gateId = generateUUID();
    card.setAttribute('data-gate-uuid', gateId);
    card.innerHTML = `<div class="card-badge-sm">Entry #${c}</div><div class="card-row" style="grid-template-columns:1fr 1fr;"><div class="form-group"><label>Entry Name</label><input type="text" class="form-control gate-name" placeholder="e.g., Main Door"></div><div class="form-group"><label>Entry Number</label><input type="text" class="form-control gate-number" placeholder="e.g., E-01"></div></div><div style="text-align:right;margin-top:0.5rem;"><button type="button" class="btn-outline-danger" onclick="this.closest('.dynamic-card').remove()"><i class="fas fa-trash"></i></button></div>`;
    container.appendChild(card);
}

function addCustomAmenityToFloor(fid) {
    if (!floorCustomAmenityCounters[fid]) floorCustomAmenityCounters[fid] = 0;
    floorCustomAmenityCounters[fid]++;
    const c = floorCustomAmenityCounters[fid];
    const container = document.querySelector(`.floor-custom-amenities-container[data-floor-id="${fid}"]`);
    const card = document.createElement('div');
    card.className = 'custom-amenity-card';
    const amenityId = generateUUID();
    card.setAttribute('data-amenity-uuid', amenityId);
    card.innerHTML = `<div class="card-badge-sm">Custom #${c}</div><div class="card-row" style="grid-template-columns:2fr 1fr;"><div class="form-group"><label>Amenity Name</label><input type="text" class="form-control custom-amenity-name" placeholder="e.g., Projector"></div><div class="form-group"><label>Quantity</label><input type="number" class="form-control custom-amenity-qty" min="1" value="1"></div></div><div style="text-align:right;margin-top:0.5rem;"><button type="button" class="btn-outline-danger" onclick="this.closest('.custom-amenity-card').remove()"><i class="fas fa-trash"></i></button></div>`;
    container.appendChild(card);
}

function toggleFloorAmenityCount(cb, fid, key) {
    const f = document.getElementById(`floor-${fid}`);
    if (!f) return;
    const ci = f.querySelector(`.amenity-count-input[data-key="${key}"]`);
    if (!ci) return;
    const cl = ci.previousElementSibling;
    if (cb.checked) { ci.classList.add('show'); if (cl) cl.classList.add('show'); } else { ci.classList.remove('show'); if (cl) cl.classList.remove('show'); ci.value = 1; }
}

function getFloorDataFromDOM(fid) {
    const f = document.getElementById(`floor-${fid}`);
    if (!f) return null;
    const floorNumberInput = f.querySelector('.floor-number');
    const n = floorNumberInput?.value?.trim() || '';
    if (!n) return null;
    const isBasement = document.getElementById(`floorIsBasement-${fid}`)?.checked || false;
    const allocs = {};
    const facilityEntries = [];
    f.querySelectorAll('.floor-alloc-check').forEach(cb => {
        const key = cb.getAttribute('data-key');
        const isFacility = cb.getAttribute('data-facility') === 'true';
        if (cb.checked) {
            const qtyInput = cb.closest('.allocation-item')?.querySelector('.floor-alloc-qty');
            const qty = qtyInput ? parseInt(qtyInput.value) || 1 : 1;
            if (isFacility) { if (!facilityEntries.includes(key)) facilityEntries.push(key); } else { allocs[key] = qty; }
        }
    });
    const custom = [];
    f.querySelectorAll('.floor-custom-amenities-container .custom-amenity-card').forEach(card => {
        const nameEl = card.querySelector('.custom-amenity-name');
        const qtyEl = card.querySelector('.custom-amenity-qty');
        const name = nameEl?.value?.trim() || '';
        const qty = parseInt(qtyEl?.value) || 1;
        let amenityId = card.getAttribute('data-amenity-uuid');
        if (!amenityId) { amenityId = generateUUID(); card.setAttribute('data-amenity-uuid', amenityId); }
        if (name) custom.push({ id: amenityId, name, quantity: qty });
    });
    const areas = [];
    f.querySelectorAll('.floor-areas-container .area-card').forEach(c => {
        const nameEl = c.querySelector('.area-name');
        const valueEl = c.querySelector('.area-value');
        const unitEl = c.querySelector('.area-unit');
        const an = nameEl?.value?.trim() || '';
        const av = parseFloat(valueEl?.value) || 0;
        const au = unitEl?.value || 'sq_ft';
        let areaId = c.getAttribute('data-area-uuid');
        if (!areaId) { areaId = generateUUID(); c.setAttribute('data-area-uuid', areaId); }
        if (an) areas.push({ id: areaId, name: an, area: av, unit: au });
    });
    const gates = [];
    f.querySelectorAll('.floor-gates-container .gate-card').forEach(c => {
        const nameEl = c.querySelector('.gate-name');
        const numEl = c.querySelector('.gate-number');
        const gn = nameEl?.value?.trim() || '';
        const gnum = numEl?.value?.trim() || '';
        let gateId = c.getAttribute('data-gate-uuid');
        if (!gateId) { gateId = generateUUID(); c.setAttribute('data-gate-uuid', gateId); }
        if (gn || gnum) gates.push({ id: gateId, name: gn, number: gnum });
    });
    const areaValueInput = f.querySelector('.floor-area-value');
    const areaUnitSelect = f.querySelector('.floor-area-unit');
    return { id: fid, floor_number: n, rooms: f.querySelector('.floor-rooms')?.value || '', description: f.querySelector('.floor-description')?.value?.trim() || '', area_value: parseFloat(areaValueInput?.value) || 0, area_unit: areaUnitSelect?.value || 'sq_ft', additional_areas: areas, gates: gates, custom_amenities: custom, allocated_amenities: allocs, allocated_facility_entries: facilityEntries, is_basement: isBasement };
}

async function saveAllFloorsAndContinue() {
    saveCurrentFloorData();
    const bi = document.getElementById('floorBuildingId').value;
    const bli = document.getElementById('blockSelect').value;
    if (!bi) { showToast('Please select a building', 'error'); return; }
    if (!bli) { showToast('Please select a block', 'error'); return; }
    const fl = [];
    existingFloorIds.forEach(fid => {
        const domData = getFloorDataFromDOM(fid);
        if (domData && domData.floor_number) { fl.push(domData); if (floorDataMap[fid]) floorDataMap[fid] = { ...floorDataMap[fid], ...domData }; } else { const stored = floorDataMap[fid]; if (stored && stored.floor_number) fl.push(stored); }
    });
    if (fl.length === 0) { showToast('Please fill at least one floor name', 'error'); return; }
    const btn = document.getElementById('saveFloorsBtn'), orig = btn.innerHTML;
    btn.innerHTML = '<span class="loading-spinner"></span> Saving...';
    btn.disabled = true;
    const fd = new FormData();
    fd.append('building_id', bi);
    fd.append('block_id', bli);
    fd.append('floors', JSON.stringify(fl));
    fd.append('_token', CSRF_TOKEN);
    try {
        const r = await fetch(`${API_BASE_URL}/floors/bulk`, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const result = await r.json();
        if (result.success) {
            showToast('Floors saved! Moving to Rooms...');
            await loadExistingFloorsForBlock(bli);
            const roomBuildingSelect = document.getElementById('roomBuildingId');
            if (roomBuildingSelect) { roomBuildingSelect.value = bi; await loadBlocksForRooms(); }
            setTimeout(() => goToStep(4), 1000);
        } else { showToast(result.message || 'Failed', 'error'); }
    } catch (e) { showToast('An error occurred', 'error'); } finally { btn.innerHTML = orig; btn.disabled = false; }
}

// ===== STEP 4: ROOMS (with dynamic room types) =====
async function loadBlocksForRooms() {
    const bi = document.getElementById('roomBuildingId').value;
    const bs = document.getElementById('roomBlockSelect');
    const fs = document.getElementById('roomFloorSelect');
    fs.innerHTML = '<option value="">Select a floor</option>';
    document.getElementById('existingRoomsSection').style.display = 'none';
    document.getElementById('floorAmenitiesDisplay').style.display = 'none';
    document.getElementById('roomSelectorCard').style.display = 'none';
    document.getElementById('roomDetailPanel').classList.remove('active');
    roomDataMap = {};
    existingRoomIds = [];
    if (!bi) { bs.innerHTML = '<option value="">Select a building block</option>'; return; }
    bs.innerHTML = '<option value="">Loading...</option>';
    try {
        const r = await fetch(`${API_BASE_URL}/rooms/blocks/${bi}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const result = await r.json();
        if (result.success) {
            let o = '<option value="">Select a building block</option>';
            result.data.forEach(b => { o += `<option value="${b.id}">${escapeHtml(b.name)}</option>`; });
            bs.innerHTML = o;
        } else {
            bs.innerHTML = '<option value="">No blocks found</option>';
        }
    } catch (e) {
        bs.innerHTML = '<option value="">Error</option>';
    }
}

async function loadFloorsForRooms() {
    const bi = document.getElementById('roomBlockSelect').value;
    const fs = document.getElementById('roomFloorSelect');
    document.getElementById('existingRoomsSection').style.display = 'none';
    document.getElementById('floorAmenitiesDisplay').style.display = 'none';
    document.getElementById('roomSelectorCard').style.display = 'none';
    document.getElementById('roomDetailPanel').classList.remove('active');
    roomDataMap = {};
    existingRoomIds = [];
    if (!bi) { fs.innerHTML = '<option value="">Select a floor</option>'; return; }
    fs.innerHTML = '<option value="">Loading...</option>';
    try {
        const r = await fetch(`${API_BASE_URL}/rooms/floors-by-block/${bi}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const result = await r.json();
        if (result.success) {
            let o = '<option value="">Select a floor</option>';
            result.data.forEach(f => { 
                const displayName = f.floor_number || f.name || `Floor ${f.id}`;
                o += `<option value="${f.id}">${escapeHtml(displayName)}</option>`;
            });
            fs.innerHTML = o;
        } else {
            fs.innerHTML = '<option value="">No floors found</option>';
        }
    } catch (e) {
        fs.innerHTML = '<option value="">Error</option>';
    }
}

async function loadExistingRoomsAndFloorAmenities() {
    const fi = document.getElementById('roomFloorSelect').value;
    const s = document.getElementById('existingRoomsSection');
    const c = document.getElementById('existingRoomsContainer');
    const nr = document.getElementById('noExistingRooms');
    const cb = document.getElementById('existingRoomsCount');
    const floorAmenitiesDisplay = document.getElementById('floorAmenitiesDisplay');
    const floorAmenitiesList = document.getElementById('floorAmenitiesList');
    const roomSelectorCard = document.getElementById('roomSelectorCard');
    const roomDetailPanel = document.getElementById('roomDetailPanel');

    roomDataMap = {};
    existingRoomIds = [];
    document.getElementById('roomDetailContainer').innerHTML = '';
    roomDetailPanel.classList.remove('active');
    roomSelectorCard.style.display = 'none';

    if (fi) {
        try {
            const r = await fetch(`${API_BASE_URL}/floors/${fi}/details`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const result = await r.json();
            if (result.success && result.data) {
                const floorData = result.data;
                window.currentFloorAmenities = {
                    amenities: floorData.allocated_amenities || {},
                    facilities: floorData.allocated_facilities || []
                };
                let amenitiesHtml = '';
                const allocs = floorData.allocated_amenities || {};
                const allocFacilities = floorData.allocated_facilities || [];
                Object.keys(allocs).forEach(key => {
                    const qty = allocs[key] || 0;
                    const displayName = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    amenitiesHtml += `<span class="floor-amenity-badge"><i class="fas fa-concierge-bell"></i> ${displayName} (${qty})</span>`;
                });
                allocFacilities.forEach(entryId => {
                    let name = entryId;
                    let entryData = null;
                    for (const type of Object.keys(campusFacilityEntries)) {
                        const found = campusFacilityEntries[type].find(e => e.id === entryId);
                        if (found) { name = found.name; entryData = found; break; }
                    }
                    let extraInfo = '';
                    if (entryData && entryData.toilets !== undefined) {
                        extraInfo = ` 🚽${entryData.toilets} 🚹${entryData.urinals||0} 🚰${entryData.washbasins}`;
                    }
                    if (entryData && entryData.area) extraInfo += ` 📐${parseFloat(entryData.area).toFixed(1)} sqft`;
                    amenitiesHtml += `<span class="floor-facility-badge"><i class="fas fa-building"></i> ${escapeHtml(name)}${extraInfo}</span>`;
                });
                if (amenitiesHtml) {
                    floorAmenitiesList.innerHTML = amenitiesHtml;
                    floorAmenitiesDisplay.style.display = 'block';
                } else {
                    floorAmenitiesList.innerHTML = '<span style="color:var(--text-muted);font-size:0.85rem;">No amenities allocated to this floor.</span>';
                    floorAmenitiesDisplay.style.display = 'block';
                }
                const roomStats = floorData.room_stats || {};
                let totalRooms = roomStats.total_rooms || 0;
                if (totalRooms === 0 && floorData.total_rooms) totalRooms = parseInt(floorData.total_rooms) || 0;
                const roomDropdown = document.getElementById('roomSelectorDropdown');
                roomDropdown.innerHTML = '<option value="">-- Select a room --</option>';
                let existingRoomNumbers = new Set();
                let existingRoomData = {};
                try {
                    const roomsResp = await fetch(`${API_BASE_URL}/rooms/by-floor/${fi}`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const roomsResult = await roomsResp.json();
                    if (roomsResult.success && roomsResult.data && roomsResult.data.length > 0) {
                        roomsResult.data.forEach(room => {
                            const roomId = room.id;
                            existingRoomData[roomId] = room;
                            existingRoomNumbers.add(room.room_number);
                            roomDataMap[roomId] = room;
                            existingRoomIds.push(roomId);
                            const option = document.createElement('option');
                            option.value = roomId;
                            option.textContent = `${room.room_number}${room.room_name ? ' - ' + room.room_name : ''}`;
                            roomDropdown.appendChild(option);
                        });
                    }
                } catch (e) {}
                let roomCounter = 1;
                const roomsToCreate = Math.max(0, totalRooms - existingRoomIds.length);
                for (let i = 1; i <= roomsToCreate; i++) {
                    let roomNumber = `Room ${i}`;
                    let counter = 1;
                    while (existingRoomNumbers.has(roomNumber)) { counter++; roomNumber = `Room ${counter}`; }
                    existingRoomNumbers.add(roomNumber);
                    const roomId = `room_${Date.now()}_${i}`;
                    roomDataMap[roomId] = {
                        id: roomId,
                        room_number: roomNumber,
                        room_name: '',
                        room_type: 'classroom',
                        custom_room_type: null,
                        status: 'active',
                        occupancy_status: 'vacant',
                        floor_level: '',
                        room_specifications: {
                            capacity: null, min_capacity: null, occupancy_type: 'single',
                            category: 'standard', usage_type: 'permanent',
                            available_from: '09:00', available_to: '18:00', booking_required: 'no',
                            area: null, length: null, width: null, height: null,
                            doors: 0, windows: 0, door_type: 'single', window_type: 'casement',
                            lights_count: 0, fans_count: 0, ac_count: 0, sockets_count: 0,
                            lighting_type: 'led', ac_type: 'none',
                            has_wifi: false, has_lan: false, has_telephone: false, has_cctv: false,
                            internet_speed: null, network_type: 'both',
                            has_projector: false, has_whiteboard: false, has_smart_board: false,
                            has_desk: false, has_chair: false, has_cabinets: false,
                            has_bed: false, has_tv: false, has_kitchenette: false,
                            has_fridge: false, has_microwave: false, has_water_cooler: false,
                            has_fire_extinguisher: false, has_fire_alarm: false,
                            has_smoke_detector: false, has_sprinkler: false,
                            has_emergency_exit: false, has_emergency_light: false,
                            has_door_lock: false, has_smart_lock: false, has_intercom: false,
                            security_level: 'medium',
                            has_washroom: false, washroom_capacity: null, washroom_type: 'attached',
                            washroom_area: 0, has_hot_water: false, has_shower: false,
                            has_bathtub: false,
                            has_wheelchair_access: false, has_grab_bars: false,
                            has_visual_alerts: false, accessibility_level: 'none'
                        },
                        selected_floor_amenities: {},
                        selected_floor_facilities: [],
                        has_balcony: false,
                        balcony_area: null,
                        balcony_units: []
                    };
                    existingRoomIds.push(roomId);
                    const option = document.createElement('option');
                    option.value = roomId;
                    option.textContent = roomNumber;
                    roomDropdown.appendChild(option);
                }
                document.getElementById('roomCountBadge').textContent = `${existingRoomIds.length} rooms`;
                roomSelectorCard.style.display = 'block';
                roomDetailPanel.classList.add('active');
                if (existingRoomIds.length > 0) {
                    roomDropdown.value = existingRoomIds[0];
                    onRoomSelectorChange();
                } else {
                    document.getElementById('roomDetailContainer').innerHTML = `
                        <div class="empty-state-msg">
                            <i class="fas fa-door-open"></i>
                            <p>No rooms configured for this floor. The total number of rooms is <strong>${totalRooms}</strong>.</p>
                        </div>`;
                }
                if (totalRooms > 0) {
                    cb.textContent = `${totalRooms} rooms`;
                    s.style.display = 'block';
                    let h = `<div class="existing-floor-card">
                        <span class="existing-badge"><i class="fas fa-chart-bar"></i> Room Statistics</span>
                        <div class="floor-info">
                            <div>
                                <div class="floor-name"><i class="fas fa-door-open"></i> Total Rooms: ${roomStats.total_rooms || totalRooms}</div>
                                <div class="floor-meta">Available: ${roomStats.available_rooms || 0} | Occupied: ${roomStats.occupied_rooms || 0}</div>
                            </div>
                        </div>
                    </div>`;
                    c.innerHTML = h;
                    nr.style.display = 'none';
                } else { s.style.display = 'none'; nr.style.display = 'block'; cb.textContent = '0 rooms'; }
            } else {
                floorAmenitiesList.innerHTML = '<span style="color:var(--text-muted);font-size:0.85rem;">No amenities data available.</span>';
                floorAmenitiesDisplay.style.display = 'block';
            }
        } catch (e) {
            floorAmenitiesList.innerHTML = '<span style="color:var(--text-muted);font-size:0.85rem;">Could not load floor amenities.</span>';
            floorAmenitiesDisplay.style.display = 'block';
        }
    } else {
        s.style.display = 'none';
        floorAmenitiesDisplay.style.display = 'none';
        roomSelectorCard.style.display = 'none';
        roomDetailPanel.classList.remove('active');
    }
    setTimeout(initRoomSpecTabs, 100);
}

async function fetchRoomTypes() {
    try {
        const response = await fetch(`${API_BASE_URL}/room-types`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF_TOKEN }
        });
        const result = await response.json();
        if (result.success && Array.isArray(result.data)) {
            roomTypeOptions = result.data.map(item => ({ id: item.name, name: item.name }));
            roomTypeOptions.push({ id: 'other', name: 'Other' });
        } else {
            setFallbackRoomTypes();
        }
    } catch (e) {
        console.warn('Failed to fetch room types, using fallback', e);
        setFallbackRoomTypes();
    }
    if (selectedRoomId && roomDataMap[selectedRoomId]) {
        onRoomSelectorChange();
    }
}

function setFallbackRoomTypes() {
    roomTypeOptions = [
        { id: 'classroom', name: 'Classroom' },
        { id: 'office', name: 'Office' },
        { id: 'conference_room', name: 'Conference Room' },
        { id: 'seminar_hall', name: 'Seminar Hall' },
        { id: 'computer_lab', name: 'Computer Lab' },
        { id: 'science_lab', name: 'Science Lab' },
        { id: 'library', name: 'Library' },
        { id: 'cafeteria', name: 'Cafeteria' },
        { id: 'auditorium', name: 'Auditorium' },
        { id: 'gym', name: 'Gym' },
        { id: 'hostel', name: 'Hostel Room' },
        { id: 'guest_room', name: 'Guest Room' },
        { id: 'staff_room', name: 'Staff Room' },
        { id: 'admin_office', name: 'Admin Office' },
        { id: 'store_room', name: 'Store Room' },
        { id: 'prayer_room', name: 'Prayer Room' },
        { id: 'waiting_room', name: 'Waiting Room' },
        { id: 'medical_room', name: 'Medical Room' },
        { id: 'daycare', name: 'Daycare' },
        { id: 'residential', name: 'Residential' },
        { id: 'commercial', name: 'Commercial' },
        { id: 'other', name: 'Other' }
    ];
}

// Override goToStep to fetch room types on step 4
const originalGoToStep = goToStep;
goToStep = function(step) {
    originalGoToStep(step);
    if (step === 4 && roomTypeOptions.length === 0) {
        fetchRoomTypes().then(() => {
            if (selectedRoomId && roomDataMap[selectedRoomId]) onRoomSelectorChange();
        });
    }
};

// ===== ROOM TYPE AUTO-NAMING =====
function onRoomTypeSelect(roomId) {
    const select = document.getElementById(`roomTypeSelect-${roomId}`);
    const selectedTypeId = select.value;
    const room = roomDataMap[roomId];
    if (!room) return;
    
    // Get the actual name
    let typeName = selectedTypeId;
    if (selectedTypeId !== 'other') {
        const foundType = roomTypeOptions.find(t => t.id === selectedTypeId);
        if (foundType) {
            typeName = foundType.name;
        }
    }
    
    // Store the name
    room.room_type = typeName;
    console.log(room.room_type);
    room.room_type_id = selectedTypeId; // Keep ID separately if needed
    
    if (selectedTypeId !== 'other') {
        room.custom_room_type = null;
        // Auto-populate room name...
        const typeNameForAuto = roomTypeOptions.find(t => t.id === selectedTypeId)?.name || '';
        const numberInput = document.getElementById(`roomDetailNumber-${roomId}`);
        if (numberInput && typeNameForAuto) {
            const currentNumber = numberInput.value.trim();
            if (!currentNumber || currentNumber.startsWith('Room ')) {
                let typeCount = 0;
                Object.keys(roomDataMap).forEach(rid => {
                    const r = roomDataMap[rid];
                    if (r && r.room_type === typeNameForAuto && r.id !== roomId) {
                        typeCount++;
                    }
                });
                const newName = `${typeNameForAuto} ${typeCount + 1}`;
                numberInput.value = newName;
                updateRoomDropdownLabel(roomId, newName);
            }
        }
    }
    
    // Show/hide custom type input
    const customContainer = document.getElementById(`customRoomTypeContainer-${roomId}`);
    if (customContainer) {
        customContainer.style.display = selectedTypeId === 'other' ? 'block' : 'none';
        if (selectedTypeId !== 'other') {
            const customInput = document.getElementById(`customRoomType-${roomId}`);
            if (customInput) customInput.value = '';
        }
    }
    
    // Update hidden input with name
    const hiddenInput = document.getElementById(`roomDetailType-${roomId}`);
    if (hiddenInput) hiddenInput.value = typeName;
}

function onCustomRoomTypeInput(roomId) {
    const input = document.getElementById(`customRoomType-${roomId}`);
    const room = roomDataMap[roomId];
    if (room) {
        const customTypeName = input.value.trim();
        room.custom_room_type = customTypeName;
        room.room_type = customTypeName; // Set room_type to custom name
        
        // Auto-populate room name from custom type
        const numberInput = document.getElementById(`roomDetailNumber-${roomId}`);
        if (numberInput && customTypeName) {
            const currentNumber = numberInput.value.trim();
            if (!currentNumber || currentNumber.startsWith('Room ') || currentNumber.startsWith('Custom ')) {
                let typeCount = 0;
                Object.keys(roomDataMap).forEach(rid => {
                    const r = roomDataMap[rid];
                    if (r && r.custom_room_type === customTypeName && r.id !== roomId) {
                        typeCount++;
                    }
                });
                const newName = `${customTypeName} ${typeCount + 1}`;
                numberInput.value = newName;
                updateRoomDropdownLabel(roomId, newName);
            }
        }
    }
}

function updateRoomDropdownLabel(roomId, name) {
    const dropdown = document.getElementById('roomSelectorDropdown');
    if (dropdown) {
        const option = dropdown.querySelector(`option[value="${roomId}"]`);
        if (option) option.textContent = name;
    }
    const badge = document.getElementById('selectedRoomName');
    if (badge) badge.textContent = name;
}

function buildRoomDetailHTML(room) {
    const roomTypeId = room.room_type_id || room.room_type || 'classroom';
    // If room.room_type is already a name, find its ID
    let selectedTypeId = roomTypeId;
    if (!roomTypeOptions.find(t => t.id === selectedTypeId) && selectedTypeId !== 'other') {
        // If it's a name, try to find the matching ID
        const found = roomTypeOptions.find(t => t.name === selectedTypeId);
        if (found) selectedTypeId = found.id;
    }
    const isOther = selectedTypeId === 'other' || !roomTypeOptions.find(t => t.id === selectedTypeId);
    const customType = room.custom_room_type || '';
    
    // Get the actual name for display and storage
    let typeName = selectedTypeId;
    if (selectedTypeId !== 'other') {
        const foundType = roomTypeOptions.find(t => t.id === selectedTypeId);
        if (foundType) {
            typeName = foundType.name;
        }
    } else if (customType) {
        typeName = customType;
    }

    const specs = room.room_specifications || {};

    // Build select options
    let typeOptionsHtml = '<select id="roomTypeSelect-' + room.id + '" class="form-control" onchange="onRoomTypeSelect(\'' + room.id + '\')">';
    roomTypeOptions.forEach(type => {
        const selected = type.id === selectedTypeId ? 'selected' : '';
        typeOptionsHtml += `<option value="${type.id}" ${selected}>${escapeHtml(type.name)}</option>`;
    });
    typeOptionsHtml += '</select>';

    // Custom type input (shown only when "other" is selected)
    const customTypeHtml = `
        <div id="customRoomTypeContainer-${room.id}" style="${isOther ? 'display:block;' : 'display:none;'}margin-top:12px;">
            <input type="text" id="customRoomType-${room.id}" class="form-control" value="${escapeHtml(customType)}" placeholder="Enter custom room type" maxlength="50" oninput="onCustomRoomTypeInput('${room.id}')">
        </div>
    `;

    // Floor amenities and facilities
    const floorAmenities = window.currentFloorAmenities || { amenities: {}, facilities: [] };
    const amenitiesList = floorAmenities.amenities || {};
    const facilitiesList = floorAmenities.facilities || [];
    const selectedAmenities = room.selected_floor_amenities || {};
    const selectedFacilities = room.selected_floor_facilities || [];

    let amenitiesSelectionHtml = '';
    const amenityKeys = Object.keys(amenitiesList);
    if (amenityKeys.length > 0) {
        amenitiesSelectionHtml += `<div class="amenity-category"><div class="amenity-category-title"><i class="fas fa-concierge-bell"></i> Floor Amenities</div><div class="amenities-grid">`;
        amenityKeys.forEach(key => {
            const total = amenitiesList[key] || 0;
            const selected = selectedAmenities[key] || 0;
            const isChecked = selected > 0;
            amenitiesSelectionHtml += `
                <div class="amenity-item ${isChecked ? 'selected' : ''}">
                    <input type="checkbox" class="room-amenity-check" data-amenity-key="${key}" data-room-id="${room.id}" ${isChecked ? 'checked' : ''} onchange="toggleRoomAmenity('${room.id}', '${key}', this)">
                    <label>${escapeHtml(key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()))}</label>
                    <input type="number" class="amenity-qty" data-amenity-key="${key}" data-room-id="${room.id}" min="1" max="${total}" value="${selected || 1}" ${isChecked ? '' : 'disabled'} onchange="updateRoomAmenityQty('${room.id}', '${key}', this)">
                    <span style="font-size:0.65rem;color:var(--text-muted);">/ ${total}</span>
                </div>`;
        });
        amenitiesSelectionHtml += `</div></div>`;
    }
    if (facilitiesList.length > 0) {
        amenitiesSelectionHtml += `<div class="amenity-category"><div class="amenity-category-title"><i class="fas fa-building"></i> Floor Facilities</div><div class="amenities-grid">`;
        facilitiesList.forEach(facility => {
            const facilityId = typeof facility === 'string' ? facility : facility.id || facility;
            const facilityName = typeof facility === 'string' ? facility : (facility.name || facilityId);
            const isChecked = selectedFacilities.includes(facilityId);
            let extraInfo = '';
            let facilityEntry = null;
            let areaValue = 0;
            for (const type of Object.keys(campusFacilityEntries)) {
                const found = campusFacilityEntries[type].find(e => e.id === facilityId);
                if (found) { facilityEntry = found; areaValue = found.area || 0; break; }
            }
            if (facilityEntry && facilityEntry.toilets !== undefined) extraInfo = ` 🚽${facilityEntry.toilets} 🚹${facilityEntry.urinals||0} 🚰${facilityEntry.washbasins}`;
            if (areaValue > 0) extraInfo += ` 📐${areaValue.toFixed(1)} sqft`;
            amenitiesSelectionHtml += `
                <div class="amenity-item ${isChecked ? 'selected' : ''}">
                    <input type="checkbox" class="room-facility-check" data-facility-id="${facilityId}" data-room-id="${room.id}" ${isChecked ? 'checked' : ''} onchange="toggleRoomFacility('${room.id}', '${facilityId}', this)">
                    <label>${escapeHtml(facilityName)}${extraInfo}</label>
                </div>`;
        });
        amenitiesSelectionHtml += `</div></div>`;
    }
    if (!amenitiesSelectionHtml) amenitiesSelectionHtml = `<div style="color:var(--text-muted);font-size:0.85rem;padding:0.5rem 0;">No floor amenities or facilities available to assign.</div>`;

    // Balcony
    const hasBalcony = room.has_balcony || false;
    const balconyArea = room.balcony_area || '';
    const balconyUnits = room.balcony_units || [];
    let balconyHtml = '';
    if (hasBalcony) {
        const unitCount = balconyUnits.length || 1;
        balconyHtml = `
            <div class="balcony-card" id="balcony-card-${room.id}">
                <div class="balcony-badge"><i class="fas fa-balcony"></i> Balcony</div>
                <div class="card-row" style="grid-template-columns:1fr 1fr;gap:0.75rem;margin-top:0.5rem;">
                    <div class="form-group">
                        <label>Number of Balcony Units</label>
                        <input type="number" id="roomBalconyCount-${room.id}" class="form-control" min="1" value="${unitCount}" onchange="updateBalconyUnits('${room.id}')">
                    </div>
                    <div class="form-group">
                        <label>Total Balcony Area (sq. ft.)</label>
                        <input type="number" id="roomBalconyArea-${room.id}" class="form-control" min="0" step="0.01" value="${balconyArea}" placeholder="Area" onchange="updateRoomAreaSummary('${room.id}')">
                    </div>
                </div>
                <div id="balcony-units-container-${room.id}" style="margin-top:0.5rem;">
                    ${balconyUnits.map((unit, idx) => `
                        <div class="dynamic-card" style="padding:0.75rem;margin-bottom:0.5rem;">
                            <div class="card-badge-sm">Unit #${idx + 1}</div>
                            <div class="card-row" style="grid-template-columns:1fr 1fr;gap:0.75rem;">
                                <div class="form-group">
                                    <label>Area (sq. ft.)</label>
                                    <input type="number" class="form-control balcony-unit-area" min="0" step="0.01" value="${unit.area || ''}" placeholder="Area" onchange="updateBalconyUnitArea('${room.id}', ${idx}, this)">
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" class="form-control balcony-unit-desc" value="${escapeHtml(unit.description || '')}" placeholder="e.g., Front balcony" onchange="updateBalconyUnitDesc('${room.id}', ${idx}, this)">
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }

    const roomAreaDeductionHtml = buildRoomAreaDeductionHTML(room.id);

    const occupancyOptions = [
        { value: 'vacant', label: 'Vacant' },
        { value: 'occupied', label: 'Occupied' },
        { value: 'maintenance', label: 'Under Maintenance' },
        { value: 'reserved', label: 'Reserved' }
    ];
    let occupancyHtml = '';
    occupancyOptions.forEach(opt => {
        const selected = room.occupancy_status === opt.value ? 'selected' : '';
        occupancyHtml += `<option value="${opt.value}" ${selected}>${opt.label}</option>`;
    });

    const statusOptions = [
        { value: 'active', label: 'Active' },
        { value: 'inactive', label: 'Inactive' }
    ];
    let statusHtml = '';
    statusOptions.forEach(opt => {
        const selected = room.status === opt.value ? 'selected' : '';
        statusHtml += `<option value="${opt.value}" ${selected}>${opt.label}</option>`;
    });

    const balconyChecked = hasBalcony ? 'checked' : '';
    const washroomChecked = specs.has_washroom ? 'checked' : '';
    const washroomDetailsStyle = specs.has_washroom ? 'display:block;' : 'display:none;';

    // Washroom area field
    const washroomArea = specs.washroom_area || 0;

    return `
        <div class="room-detail-card" id="roomDetailCard-${room.id}">
            <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div class="form-group">
                    <label class="form-label">Room Number <span style="color:#dc3545;">*</span></label>
                    <input type="text" id="roomDetailNumber-${room.id}" class="form-control" value="${escapeHtml(room.room_number || '')}" placeholder="e.g., 101, Lab-1" maxlength="20" onchange="updateRoomDropdownLabel('${room.id}', this.value)">
                </div>
                <div class="form-group">
                    <label class="form-label">Room Name</label>
                    <input type="text" id="roomDetailName-${room.id}" class="form-control" value="${escapeHtml(room.room_name || '')}" placeholder="e.g., Computer Lab" maxlength="100">
                </div>
            </div>
            <div class="card-row" style="display:grid;grid-template-columns:1fr;gap:1rem;margin-bottom:1rem;">
                <div class="form-group">
                    <label class="form-label">Room Type <span style="color:#dc3545;">*</span></label>
                    ${typeOptionsHtml}
                    ${customTypeHtml}
                    <input type="hidden" id="roomDetailType-${room.id}" value="${escapeHtml(typeName)}">
                </div>
            </div>
            <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select id="roomDetailStatus-${room.id}" class="form-control">${statusHtml}</select>
                </div>
                <div class="form-group">
                    <label class="form-label">Occupancy Status</label>
                    <select id="roomDetailOccupancy-${room.id}" class="form-control">${occupancyHtml}</select>
                </div>
                <div class="form-group">
                    <label class="form-label">Floor Level</label>
                    <input type="text" id="roomDetailFloorLevel-${room.id}" class="form-control" value="${escapeHtml(room.floor_level || '')}" placeholder="e.g., Ground, 1st">
                </div>
            </div>
            
            <!-- Total Area Section -->
            <div class="section-divider"><h4><i class="fas fa-vector-square"></i> Room Area</h4></div>
            <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Area Unit</label>
                    <select id="roomDetailAreaUnit-${room.id}" class="form-control">
                        <option value="sq_ft">Sq. Ft.</option>
                        <option value="sq_m">Sq. M.</option>
                        <option value="sq_yd">Sq. Yd.</option>
                        <option value="gaj">Gaj</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Area Value</label>
                    <input type="number" id="roomDetailAreaValue-${room.id}" class="form-control" value="${specs.area || ''}" placeholder="Enter room area" min="0" step="0.01" onchange="updateRoomAreaSummary('${room.id}')">
                </div>
            </div>
            
            <!-- Area Summary -->
            ${roomAreaDeductionHtml}
            
            <!-- Balcony Section -->
            <div class="section-divider"><h4><i class="fas fa-balcony"></i> Balcony</h4></div>
            <div class="facility-toggle">
                <input type="checkbox" id="roomHasBalcony-${room.id}" ${balconyChecked} onchange="toggleRoomBalcony('${room.id}')">
                <label for="roomHasBalcony-${room.id}">Has Balcony</label>
            </div>
            <div id="roomBalconyContainer-${room.id}" style="${hasBalcony ? 'display:block;' : 'display:none;'}">${balconyHtml}</div>
            
            <!-- Washroom Section -->
            <div class="section-divider"><h4><i class="fas fa-toilet"></i> Washroom</h4></div>
            <div class="facility-toggle">
                <input type="checkbox" id="roomHasWashroom-${room.id}" ${washroomChecked} onchange="toggleRoomWashroom('${room.id}')">
                <label for="roomHasWashroom-${room.id}">Has Attached Washroom</label>
            </div>
            <div id="roomWashroomContainer-${room.id}" style="${washroomDetailsStyle}margin-top:0.75rem;">
                <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="form-group">
                        <label class="form-label">Washroom Area (sq. ft.)</label>
                        <input type="number" id="roomDetailWashroomArea-${room.id}" class="form-control" value="${washroomArea}" placeholder="Area" min="0" step="0.01" onchange="updateRoomAreaSummary('${room.id}')">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Washroom Type</label>
                        <select id="roomDetailWashroomType-${room.id}" class="form-control">
                            <option value="attached" ${specs.washroom_type === 'attached' ? 'selected' : ''}>Attached</option>
                            <option value="shared" ${specs.washroom_type === 'shared' ? 'selected' : ''}>Shared</option>
                            <option value="common" ${specs.washroom_type === 'common' ? 'selected' : ''}>Common</option>
                        </select>
                    </div>
                </div>
                <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:0.5rem;">
                    <div class="form-group">
                        <label class="form-label">Washroom Capacity (Persons)</label>
                        <input type="number" id="roomDetailWashroomCap-${room.id}" class="form-control" value="${specs.washroom_capacity || ''}" placeholder="Capacity" min="1">
                    </div>
                    <div class="form-group" style="display:flex;align-items:center;gap:10px;padding-top:8px;">
                        <input type="checkbox" id="roomDetailHotWater-${room.id}" ${specs.has_hot_water ? 'checked' : ''}>
                        <label for="roomDetailHotWater-${room.id}" style="margin:0;font-weight:500;">Hot Water</label>
                        <input type="checkbox" id="roomDetailShower-${room.id}" ${specs.has_shower ? 'checked' : ''}>
                        <label for="roomDetailShower-${room.id}" style="margin:0;font-weight:500;">Shower</label>
                    </div>
                </div>
            </div>
            
            <!-- Floor Amenities & Facilities -->
            <div class="section-divider"><h4><i class="fas fa-concierge-bell"></i> Floor Amenities & Facilities Assignment</h4></div>
            <div class="room-amenities-selection">${amenitiesSelectionHtml}</div>
            
            <!-- Specifications Tabs -->
            <div class="section-divider"><h4><i class="fas fa-cogs"></i> Room Specifications</h4></div>
            <div class="room-specs-tabs">
                <button class="tab-btn active" onclick="switchRoomSpecTab('general', this)"><i class="fas fa-info-circle"></i> General</button>
                <button class="tab-btn" onclick="switchRoomSpecTab('dimensions', this)"><i class="fas fa-ruler-combined"></i> Dimensions</button>
                <button class="tab-btn" onclick="switchRoomSpecTab('utilities', this)"><i class="fas fa-bolt"></i> Utilities</button>
                <button class="tab-btn" onclick="switchRoomSpecTab('furniture', this)"><i class="fas fa-chair"></i> Furniture</button>
                <button class="tab-btn" onclick="switchRoomSpecTab('safety', this)"><i class="fas fa-shield-alt"></i> Safety</button>
                <button class="tab-btn" onclick="switchRoomSpecTab('additional', this)"><i class="fas fa-plus-circle"></i> Additional</button>
            </div>
            
            <!-- Tab 1: General -->
            <div class="room-specs-tab-content active" id="tab-general">
                <div class="spec-group-grid">
                    <div class="spec-group-card">
                        <h4><i class="fas fa-users"></i> Capacity & Occupancy</h4>
                        <div class="spec-row">
                            <div class="form-group">
                                <label>Max Capacity (Persons)</label>
                                <input type="number" id="roomDetailCapacity" class="form-control" value="${specs.capacity || ''}" placeholder="Persons" min="1">
                            </div>
                            <div class="form-group">
                                <label>Min Capacity</label>
                                <input type="number" id="roomDetailMinCapacity" class="form-control" value="${specs.min_capacity || ''}" placeholder="Min persons" min="0">
                            </div>
                        </div>
                        <div class="spec-row" style="margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Occupancy Type</label>
                                <select id="roomDetailOccupancyType" class="form-control">
                                    <option value="single" ${specs.occupancy_type === 'single' ? 'selected' : ''}>Single</option>
                                    <option value="shared" ${specs.occupancy_type === 'shared' ? 'selected' : ''}>Shared</option>
                                    <option value="multiple" ${specs.occupancy_type === 'multiple' ? 'selected' : ''}>Multiple</option>
                                    <option value="flexible" ${specs.occupancy_type === 'flexible' ? 'selected' : ''}>Flexible</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Room Category</label>
                                <select id="roomDetailCategory" class="form-control">
                                    <option value="standard" ${specs.category === 'standard' ? 'selected' : ''}>Standard</option>
                                    <option value="premium" ${specs.category === 'premium' ? 'selected' : ''}>Premium</option>
                                    <option value="deluxe" ${specs.category === 'deluxe' ? 'selected' : ''}>Deluxe</option>
                                    <option value="executive" ${specs.category === 'executive' ? 'selected' : ''}>Executive</option>
                                    <option value="economy" ${specs.category === 'economy' ? 'selected' : ''}>Economy</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="spec-group-card">
                        <h4><i class="fas fa-clock"></i> Usage & Timing</h4>
                        <div class="spec-row">
                            <div class="form-group">
                                <label>Usage Type</label>
                                <select id="roomDetailUsageType" class="form-control">
                                    <option value="permanent" ${specs.usage_type === 'permanent' ? 'selected' : ''}>Permanent</option>
                                    <option value="temporary" ${specs.usage_type === 'temporary' ? 'selected' : ''}>Temporary</option>
                                    <option value="flexible" ${specs.usage_type === 'flexible' ? 'selected' : ''}>Flexible</option>
                                    <option value="event_based" ${specs.usage_type === 'event_based' ? 'selected' : ''}>Event Based</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Available From</label>
                                <input type="time" id="roomDetailAvailableFrom" class="form-control" value="${specs.available_from || '09:00'}">
                            </div>
                        </div>
                        <div class="spec-row" style="margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Available To</label>
                                <input type="time" id="roomDetailAvailableTo" class="form-control" value="${specs.available_to || '18:00'}">
                            </div>
                            <div class="form-group">
                                <label>Booking Required</label>
                                <select id="roomDetailBookingRequired" class="form-control">
                                    <option value="yes" ${specs.booking_required === 'yes' ? 'selected' : ''}>Yes</option>
                                    <option value="no" ${specs.booking_required === 'no' ? 'selected' : ''}>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Dimensions -->
            <div class="room-specs-tab-content" id="tab-dimensions">
                <div class="spec-group-grid">
                    <div class="spec-group-card">
                        <h4><i class="fas fa-vector-square"></i> Dimensions</h4>
                        <div class="spec-row">
                            <div class="form-group">
                                <label>Length (ft)</label>
                                <input type="number" id="roomDetailLength" class="form-control" value="${specs.length || ''}" placeholder="Length" min="0" step="0.01">
                            </div>
                            <div class="form-group">
                                <label>Width (ft)</label>
                                <input type="number" id="roomDetailWidth" class="form-control" value="${specs.width || ''}" placeholder="Width" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="spec-row" style="margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Height (ft)</label>
                                <input type="number" id="roomDetailHeight" class="form-control" value="${specs.height || ''}" placeholder="Height" min="0" step="0.01">
                            </div>
                        </div>
                    </div>
                    <div class="spec-group-card">
                        <h4><i class="fas fa-door-open"></i> Doors & Windows</h4>
                        <div class="spec-row">
                            <div class="form-group">
                                <label>Number of Doors</label>
                                <input type="number" id="roomDetailDoors" class="form-control" value="${specs.doors || 0}" min="0">
                            </div>
                            <div class="form-group">
                                <label>Number of Windows</label>
                                <input type="number" id="roomDetailWindows" class="form-control" value="${specs.windows || 0}" min="0">
                            </div>
                        </div>
                        <div class="spec-row" style="margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Door Type</label>
                                <select id="roomDetailDoorType" class="form-control">
                                    <option value="single" ${specs.door_type === 'single' ? 'selected' : ''}>Single Door</option>
                                    <option value="double" ${specs.door_type === 'double' ? 'selected' : ''}>Double Door</option>
                                    <option value="sliding" ${specs.door_type === 'sliding' ? 'selected' : ''}>Sliding</option>
                                    <option value="french" ${specs.door_type === 'french' ? 'selected' : ''}>French</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Window Type</label>
                                <select id="roomDetailWindowType" class="form-control">
                                    <option value="casement" ${specs.window_type === 'casement' ? 'selected' : ''}>Casement</option>
                                    <option value="sliding" ${specs.window_type === 'sliding' ? 'selected' : ''}>Sliding</option>
                                    <option value="fixed" ${specs.window_type === 'fixed' ? 'selected' : ''}>Fixed</option>
                                    <option value="bay" ${specs.window_type === 'bay' ? 'selected' : ''}>Bay</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Utilities -->
            <div class="room-specs-tab-content" id="tab-utilities">
                <div class="spec-group-grid">
                    <div class="spec-group-card">
                        <h4><i class="fas fa-bolt"></i> Electrical</h4>
                        <div class="spec-row">
                            <div class="form-group">
                                <label>Lights</label>
                                <input type="number" id="roomDetailLights" class="form-control" value="${specs.lights_count || 0}" min="0">
                            </div>
                            <div class="form-group">
                                <label>Fans</label>
                                <input type="number" id="roomDetailFans" class="form-control" value="${specs.fans_count || 0}" min="0">
                            </div>
                        </div>
                        <div class="spec-row" style="margin-top:0.5rem;">
                            <div class="form-group">
                                <label>AC Units</label>
                                <input type="number" id="roomDetailAC" class="form-control" value="${specs.ac_count || 0}" min="0">
                            </div>
                            <div class="form-group">
                                <label>Power Sockets</label>
                                <input type="number" id="roomDetailSockets" class="form-control" value="${specs.sockets_count || 0}" min="0">
                            </div>
                        </div>
                        <div class="spec-row" style="margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Lighting Type</label>
                                <select id="roomDetailLightingType" class="form-control">
                                    <option value="led" ${specs.lighting_type === 'led' ? 'selected' : ''}>LED</option>
                                    <option value="fluorescent" ${specs.lighting_type === 'fluorescent' ? 'selected' : ''}>Fluorescent</option>
                                    <option value="incandescent" ${specs.lighting_type === 'incandescent' ? 'selected' : ''}>Incandescent</option>
                                    <option value="natural" ${specs.lighting_type === 'natural' ? 'selected' : ''}>Natural</option>
                                    <option value="mixed" ${specs.lighting_type === 'mixed' ? 'selected' : ''}>Mixed</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>AC Type</label>
                                <select id="roomDetailACType" class="form-control">
                                    <option value="central" ${specs.ac_type === 'central' ? 'selected' : ''}>Central</option>
                                    <option value="split" ${specs.ac_type === 'split' ? 'selected' : ''}>Split</option>
                                    <option value="window" ${specs.ac_type === 'window' ? 'selected' : ''}>Window</option>
                                    <option value="none" ${specs.ac_type === 'none' ? 'selected' : ''}>None</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="spec-group-card">
                        <h4><i class="fas fa-wifi"></i> Connectivity</h4>
                        <div class="checkbox-group">
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailWifi" ${specs.has_wifi ? 'checked' : ''}><label>Wi-Fi</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailLAN" ${specs.has_lan ? 'checked' : ''}><label>LAN Port</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailTelephone" ${specs.has_telephone ? 'checked' : ''}><label>Telephone</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailCCTV" ${specs.has_cctv ? 'checked' : ''}><label>CCTV Coverage</label></div>
                        </div>
                        <div class="spec-row" style="margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Internet Speed (Mbps)</label>
                                <input type="number" id="roomDetailInternetSpeed" class="form-control" value="${specs.internet_speed || ''}" placeholder="Speed" min="0">
                            </div>
                            <div class="form-group">
                                <label>Network Type</label>
                                <select id="roomDetailNetworkType" class="form-control">
                                    <option value="wired" ${specs.network_type === 'wired' ? 'selected' : ''}>Wired</option>
                                    <option value="wireless" ${specs.network_type === 'wireless' ? 'selected' : ''}>Wireless</option>
                                    <option value="both" ${specs.network_type === 'both' ? 'selected' : ''}>Both</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 4: Furniture -->
            <div class="room-specs-tab-content" id="tab-furniture">
                <div class="spec-group-grid">
                    <div class="spec-group-card">
                        <h4><i class="fas fa-chair"></i> Furniture & Equipment</h4>
                        <div class="checkbox-group">
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailProjector" ${specs.has_projector ? 'checked' : ''}><label>Projector</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailWhiteboard" ${specs.has_whiteboard ? 'checked' : ''}><label>Whiteboard</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailSmartBoard" ${specs.has_smart_board ? 'checked' : ''}><label>Smart Board</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailDesk" ${specs.has_desk ? 'checked' : ''}><label>Desk / Table</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailChair" ${specs.has_chair ? 'checked' : ''}><label>Chairs</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailCabinets" ${specs.has_cabinets ? 'checked' : ''}><label>Cabinets / Storage</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailBed" ${specs.has_bed ? 'checked' : ''}><label>Bed</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailTV" ${specs.has_tv ? 'checked' : ''}><label>TV / Monitor</label></div>
                        </div>
                    </div>
                    <div class="spec-group-card">
                        <h4><i class="fas fa-utensils"></i> Kitchenette / Pantry</h4>
                        <div class="checkbox-group">
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailKitchenette" ${specs.has_kitchenette ? 'checked' : ''}><label>Kitchenette</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailFridge" ${specs.has_fridge ? 'checked' : ''}><label>Refrigerator</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailMicrowave" ${specs.has_microwave ? 'checked' : ''}><label>Microwave</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailWaterCooler" ${specs.has_water_cooler ? 'checked' : ''}><label>Water Cooler</label></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 5: Safety -->
            <div class="room-specs-tab-content" id="tab-safety">
                <div class="spec-group-grid">
                    <div class="spec-group-card">
                        <h4><i class="fas fa-fire-extinguisher"></i> Fire Safety</h4>
                        <div class="checkbox-group">
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailFireExt" ${specs.has_fire_extinguisher ? 'checked' : ''}><label>Fire Extinguisher</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailFireAlarm" ${specs.has_fire_alarm ? 'checked' : ''}><label>Fire Alarm</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailSmokeDet" ${specs.has_smoke_detector ? 'checked' : ''}><label>Smoke Detector</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailSprinkler" ${specs.has_sprinkler ? 'checked' : ''}><label>Sprinkler System</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailEmergExit" ${specs.has_emergency_exit ? 'checked' : ''}><label>Emergency Exit</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailEmergLight" ${specs.has_emergency_light ? 'checked' : ''}><label>Emergency Lighting</label></div>
                        </div>
                    </div>
                    <div class="spec-group-card">
                        <h4><i class="fas fa-shield-alt"></i> Security</h4>
                        <div class="checkbox-group">
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailDoorLock" ${specs.has_door_lock ? 'checked' : ''}><label>Door Lock</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailSmartLock" ${specs.has_smart_lock ? 'checked' : ''}><label>Smart Lock</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailIntercom" ${specs.has_intercom ? 'checked' : ''}><label>Intercom</label></div>
                        </div>
                        <div class="spec-row" style="margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Security Level</label>
                                <select id="roomDetailSecurityLevel" class="form-control">
                                    <option value="low" ${specs.security_level === 'low' ? 'selected' : ''}>Low</option>
                                    <option value="medium" ${specs.security_level === 'medium' ? 'selected' : ''}>Medium</option>
                                    <option value="high" ${specs.security_level === 'high' ? 'selected' : ''}>High</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 6: Additional -->
            <div class="room-specs-tab-content" id="tab-additional">
                <div class="spec-group-grid">
                    <div class="spec-group-card">
                        <h4><i class="fas fa-notes-medical"></i> Accessibility</h4>
                        <div class="checkbox-group">
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailWheelchair" ${specs.has_wheelchair_access ? 'checked' : ''}><label>Wheelchair Accessible</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailGrabBars" ${specs.has_grab_bars ? 'checked' : ''}><label>Grab Bars</label></div>
                            <div class="checkbox-item"><input type="checkbox" id="roomDetailVisualAlerts" ${specs.has_visual_alerts ? 'checked' : ''}><label>Visual Alerts</label></div>
                        </div>
                        <div class="spec-row" style="margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Accessibility Level</label>
                                <select id="roomDetailAccessibilityLevel" class="form-control">
                                    <option value="none" ${specs.accessibility_level === 'none' ? 'selected' : ''}>None</option>
                                    <option value="basic" ${specs.accessibility_level === 'basic' ? 'selected' : ''}>Basic</option>
                                    <option value="full" ${specs.accessibility_level === 'full' ? 'selected' : ''}>Full</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="margin-top:1.5rem;display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" class="btn btn-success" onclick="updateRoomFromDetail('${room.id}')"><i class="fas fa-save"></i> Update Room</button>
                <button type="button" class="btn-outline-danger" onclick="removeRoomFromDropdown('${room.id}')"><i class="fas fa-trash"></i> Delete Room</button>
            </div>
        </div>
    `;
}

// ===== ROOM AREA CALCULATION =====
function buildRoomAreaDeductionHTML(roomId) {
    return `<div class="area-deduction-summary" id="roomAreaSummary-${roomId}">
        <div class="deduction-item"><span>Total Room Area:</span><span id="roomTotalArea-${roomId}">0.00</span></div>
        <div class="deduction-item"><span>Washroom Area Deduction:</span><span id="roomWashroomDeduction-${roomId}">0.00</span></div>
        <div class="deduction-item"><span>Balcony Area Deduction:</span><span id="roomBalconyDeduction-${roomId}">0.00</span></div>
        <div class="deduction-item"><span>Facilities Area Deduction:</span><span id="roomFacilityDeduction-${roomId}">0.00</span></div>
        <div class="deduction-item total-row"><span>Usable Area:</span><span id="roomUsableArea-${roomId}" class="positive">0.00</span></div>
    </div>`;
}

function updateRoomAreaSummary(roomId) {
    const roomCard = document.getElementById(`roomDetailCard-${roomId}`);
    if (!roomCard) return;
    
    // Get room area
    const areaValueInput = document.getElementById(`roomDetailAreaValue-${roomId}`);
    const roomArea = parseFloat(areaValueInput?.value) || 0;
    
    // Get washroom area
    const washroomAreaInput = document.getElementById(`roomDetailWashroomArea-${roomId}`);
    const washroomArea = parseFloat(washroomAreaInput?.value) || 0;
    
    // Get balcony area
    const balconyAreaInput = document.getElementById(`roomBalconyArea-${roomId}`);
    const balconyArea = parseFloat(balconyAreaInput?.value) || 0;
    
    // Get facility deductions from selected floor facilities
    let facilityDeduction = 0;
    const room = roomDataMap[roomId];
    if (room && room.selected_floor_facilities) {
        room.selected_floor_facilities.forEach(facilityId => {
            for (const type of Object.keys(campusFacilityEntries)) {
                const found = campusFacilityEntries[type].find(e => e.id === facilityId);
                if (found && found.area) {
                    const areaVal = parseFloat(found.area);
                    if (!isNaN(areaVal) && areaVal > 0) {
                        const areaInSqFt = convertArea(areaVal, campusAreaUnit || 'sq_ft', 'sq_ft');
                        facilityDeduction += areaInSqFt;
                    }
                    break;
                }
            }
        });
    }
    
    // Calculate usable area
    const usableArea = roomArea - washroomArea - balconyArea - facilityDeduction;
    
    // Update summary elements
    const totalEl = document.getElementById(`roomTotalArea-${roomId}`);
    const washroomEl = document.getElementById(`roomWashroomDeduction-${roomId}`);
    const balconyEl = document.getElementById(`roomBalconyDeduction-${roomId}`);
    const facilityEl = document.getElementById(`roomFacilityDeduction-${roomId}`);
    const usableEl = document.getElementById(`roomUsableArea-${roomId}`);
    
    if (totalEl) totalEl.textContent = roomArea.toFixed(2);
    if (washroomEl) washroomEl.textContent = washroomArea.toFixed(2);
    if (balconyEl) balconyEl.textContent = balconyArea.toFixed(2);
    if (facilityEl) facilityEl.textContent = facilityDeduction.toFixed(2);
    if (usableEl) {
        usableEl.textContent = usableArea.toFixed(2);
        usableEl.className = usableArea >= 0 ? 'positive' : 'negative';
    }
}

// ===== ROOM WASHROOM TOGGLE =====
function toggleRoomWashroom(roomId) {
    const cb = document.getElementById(`roomHasWashroom-${roomId}`);
    const container = document.getElementById(`roomWashroomContainer-${roomId}`);
    if (container) {
        container.style.display = cb.checked ? 'block' : 'none';
    }
    const room = roomDataMap[roomId];
    if (room && room.room_specifications) {
        room.room_specifications.has_washroom = cb.checked;
        if (!cb.checked) {
            room.room_specifications.washroom_area = 0;
            const areaInput = document.getElementById(`roomDetailWashroomArea-${roomId}`);
            if (areaInput) areaInput.value = 0;
        }
    }
    updateRoomAreaSummary(roomId);
}

// ===== ROOM BALCONY TOGGLE =====
function toggleRoomBalcony(roomId) {
    const cb = document.getElementById(`roomHasBalcony-${roomId}`);
    const container = document.getElementById(`roomBalconyContainer-${roomId}`);
    if (container) {
        container.style.display = cb.checked ? 'block' : 'none';
    }
    const room = roomDataMap[roomId];
    if (room) {
        room.has_balcony = cb.checked;
        if (!cb.checked) {
            room.balcony_area = null;
            room.balcony_units = [];
        } else if (!room.balcony_units || room.balcony_units.length === 0) {
            room.balcony_units = [{ id: generateUUID(), area: '', description: '' }];
        }
    }
    updateRoomAreaSummary(roomId);
}

// ===== BALCONY UNIT FUNCTIONS =====
function updateBalconyUnits(roomId) {
    const countInput = document.getElementById(`roomBalconyCount-${roomId}`);
    const count = parseInt(countInput?.value) || 1;
    const container = document.getElementById(`balcony-units-container-${roomId}`);
    const room = roomDataMap[roomId];
    if (!container || !room) return;
    const existingUnits = room.balcony_units || [];
    let newUnits = [];
    for (let i = 0; i < count; i++) {
        if (i < existingUnits.length) newUnits.push(existingUnits[i]);
        else newUnits.push({ id: generateUUID(), area: '', description: '' });
    }
    room.balcony_units = newUnits;
    let html = '';
    newUnits.forEach((unit, idx) => {
        html += `
            <div class="dynamic-card" style="padding:0.75rem;margin-bottom:0.5rem;">
                <div class="card-badge-sm">Unit #${idx + 1}</div>
                <div class="card-row" style="grid-template-columns:1fr 1fr;gap:0.75rem;">
                    <div class="form-group">
                        <label>Area (sq. ft.)</label>
                        <input type="number" class="form-control balcony-unit-area" min="0" step="0.01" value="${unit.area || ''}" placeholder="Area" onchange="updateBalconyUnitArea('${roomId}', ${idx}, this)">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control balcony-unit-desc" value="${escapeHtml(unit.description || '')}" placeholder="e.g., Front balcony" onchange="updateBalconyUnitDesc('${roomId}', ${idx}, this)">
                    </div>
                </div>
            </div>`;
    });
    container.innerHTML = html;
    updateBalconyTotalArea(roomId);
}

function updateBalconyUnitArea(roomId, index, input) {
    const room = roomDataMap[roomId];
    if (room && room.balcony_units && room.balcony_units[index]) {
        const val = parseFloat(input.value);
        room.balcony_units[index].area = isNaN(val) ? 0 : val;
        updateBalconyTotalArea(roomId);
    }
}

function updateBalconyUnitDesc(roomId, index, input) {
    const room = roomDataMap[roomId];
    if (room && room.balcony_units && room.balcony_units[index]) {
        room.balcony_units[index].description = input.value;
    }
}

function updateBalconyTotalArea(roomId) {
    const room = roomDataMap[roomId];
    if (!room || !room.balcony_units) return;
    let totalArea = 0;
    room.balcony_units.forEach(unit => { 
        const val = parseFloat(unit.area); 
        if (!isNaN(val)) totalArea += val; 
    });
    room.balcony_area = totalArea;
    const areaInput = document.getElementById(`roomBalconyArea-${roomId}`);
    if (areaInput) areaInput.value = totalArea.toFixed(2);
    updateRoomAreaSummary(roomId);
}

// ===== ROOM AMENITY FUNCTIONS =====
function toggleRoomAmenity(roomId, amenityKey, checkbox) {
    const room = roomDataMap[roomId];
    if (!room) return;
    if (!room.selected_floor_amenities) room.selected_floor_amenities = {};
    const qtyInput = checkbox.closest('.amenity-item').querySelector('.amenity-qty');
    if (checkbox.checked) {
        room.selected_floor_amenities[amenityKey] = 1;
        if (qtyInput) { qtyInput.disabled = false; qtyInput.value = 1; }
        checkbox.closest('.amenity-item').classList.add('selected');
    } else {
        delete room.selected_floor_amenities[amenityKey];
        if (qtyInput) { qtyInput.disabled = true; qtyInput.value = 1; }
        checkbox.closest('.amenity-item').classList.remove('selected');
    }
}

function updateRoomAmenityQty(roomId, amenityKey, input) {
    const room = roomDataMap[roomId];
    if (!room) return;
    let val = parseInt(input.value) || 1;
    const max = parseInt(input.getAttribute('max')) || 1;
    if (val < 1) val = 1;
    if (val > max) val = max;
    input.value = val;
    if (room.selected_floor_amenities) room.selected_floor_amenities[amenityKey] = val;
}

function toggleRoomFacility(roomId, facilityId, checkbox) {
    const room = roomDataMap[roomId];
    if (!room) return;
    if (!room.selected_floor_facilities) room.selected_floor_facilities = [];
    if (checkbox.checked) {
        if (!room.selected_floor_facilities.includes(facilityId)) room.selected_floor_facilities.push(facilityId);
        checkbox.closest('.amenity-item').classList.add('selected');
    } else {
        room.selected_floor_facilities = room.selected_floor_facilities.filter(id => id !== facilityId);
        checkbox.closest('.amenity-item').classList.remove('selected');
    }
    updateRoomAreaSummary(roomId);
}

// ===== GET ROOM DATA FROM DETAIL =====
function getRoomDataFromDetail(rid) {
    const room = roomDataMap[rid];
    if (!room) return null;
    
    const roomTypeSelect = document.getElementById(`roomDetailType-${rid}`);
    const customTypeInput = document.getElementById(`customRoomType-${rid}`);
    
    // Get the selected type ID from the select element
    const selectedTypeId = roomTypeSelect?.value || 'classroom';
    const customType = customTypeInput?.value?.trim() || '';
    
    // Get the actual name from the selected type
    let roomTypeName = selectedTypeId;
    if (selectedTypeId !== 'other') {
        const foundType = roomTypeOptions.find(t => t.id === selectedTypeId);
        if (foundType) {
            roomTypeName = foundType.name;
        }
    } else if (customType) {
        roomTypeName = customType;
    }
    
    const getVal = (id, defaultValue = null) => { 
        const el = document.getElementById(id); 
        return el ? el.value : defaultValue; 
    };
    const getChecked = (id) => { 
        const el = document.getElementById(id); 
        return el ? el.checked : false; 
    };
    
    const hasBalcony = document.getElementById(`roomHasBalcony-${rid}`)?.checked || false;
    const balconyUnits = [];
    const unitContainers = document.querySelectorAll(`#balcony-units-container-${rid} .dynamic-card`);
    unitContainers.forEach(card => {
        const areaInput = card.querySelector('.balcony-unit-area');
        const descInput = card.querySelector('.balcony-unit-desc');
        let unitId = card.getAttribute('data-unit-id');
        if (!unitId) { unitId = generateUUID(); card.setAttribute('data-unit-id', unitId); }
        balconyUnits.push({ 
            id: unitId, 
            area: parseFloat(areaInput?.value) || 0, 
            description: descInput?.value || '' 
        });
    });
    
    // Get room area from the dedicated area field
    const roomArea = parseFloat(getVal(`roomDetailAreaValue-${rid}`)) || null;
    
    const roomSpecifications = {
        capacity: parseInt(getVal(`roomDetailCapacity-${rid}`)) || null,
        min_capacity: parseInt(getVal(`roomDetailMinCapacity-${rid}`)) || null,
        occupancy_type: getVal(`roomDetailOccupancyType-${rid}`, 'single'),
        category: getVal(`roomDetailCategory-${rid}`, 'standard'),
        usage_type: getVal(`roomDetailUsageType-${rid}`, 'permanent'),
        available_from: getVal(`roomDetailAvailableFrom-${rid}`, null),
        available_to: getVal(`roomDetailAvailableTo-${rid}`, null),
        booking_required: getVal(`roomDetailBookingRequired-${rid}`, 'no'),
        area: roomArea,
        length: parseFloat(getVal(`roomDetailLength-${rid}`)) || null,
        width: parseFloat(getVal(`roomDetailWidth-${rid}`)) || null,
        height: parseFloat(getVal(`roomDetailHeight-${rid}`)) || null,
        doors: parseInt(getVal(`roomDetailDoors-${rid}`)) || 0,
        windows: parseInt(getVal(`roomDetailWindows-${rid}`)) || 0,
        door_type: getVal(`roomDetailDoorType-${rid}`, 'single'),
        window_type: getVal(`roomDetailWindowType-${rid}`, 'casement'),
        lights_count: parseInt(getVal(`roomDetailLights-${rid}`)) || 0,
        fans_count: parseInt(getVal(`roomDetailFans-${rid}`)) || 0,
        ac_count: parseInt(getVal(`roomDetailAC-${rid}`)) || 0,
        sockets_count: parseInt(getVal(`roomDetailSockets-${rid}`)) || 0,
        lighting_type: getVal(`roomDetailLightingType-${rid}`, 'led'),
        ac_type: getVal(`roomDetailACType-${rid}`, 'none'),
        has_wifi: getChecked(`roomDetailWifi-${rid}`),
        has_lan: getChecked(`roomDetailLAN-${rid}`),
        has_telephone: getChecked(`roomDetailTelephone-${rid}`),
        has_cctv: getChecked(`roomDetailCCTV-${rid}`),
        internet_speed: parseInt(getVal(`roomDetailInternetSpeed-${rid}`)) || null,
        network_type: getVal(`roomDetailNetworkType-${rid}`, 'both'),
        has_projector: getChecked(`roomDetailProjector-${rid}`),
        has_whiteboard: getChecked(`roomDetailWhiteboard-${rid}`),
        has_smart_board: getChecked(`roomDetailSmartBoard-${rid}`),
        has_desk: getChecked(`roomDetailDesk-${rid}`),
        has_chair: getChecked(`roomDetailChair-${rid}`),
        has_cabinets: getChecked(`roomDetailCabinets-${rid}`),
        has_bed: getChecked(`roomDetailBed-${rid}`),
        has_tv: getChecked(`roomDetailTV-${rid}`),
        has_kitchenette: getChecked(`roomDetailKitchenette-${rid}`),
        has_fridge: getChecked(`roomDetailFridge-${rid}`),
        has_microwave: getChecked(`roomDetailMicrowave-${rid}`),
        has_water_cooler: getChecked(`roomDetailWaterCooler-${rid}`),
        has_fire_extinguisher: getChecked(`roomDetailFireExt-${rid}`),
        has_fire_alarm: getChecked(`roomDetailFireAlarm-${rid}`),
        has_smoke_detector: getChecked(`roomDetailSmokeDet-${rid}`),
        has_sprinkler: getChecked(`roomDetailSprinkler-${rid}`),
        has_emergency_exit: getChecked(`roomDetailEmergExit-${rid}`),
        has_emergency_light: getChecked(`roomDetailEmergLight-${rid}`),
        has_door_lock: getChecked(`roomDetailDoorLock-${rid}`),
        has_smart_lock: getChecked(`roomDetailSmartLock-${rid}`),
        has_intercom: getChecked(`roomDetailIntercom-${rid}`),
        security_level: getVal(`roomDetailSecurityLevel-${rid}`, 'medium'),
        has_washroom: getChecked(`roomHasWashroom-${rid}`),
        washroom_capacity: getChecked(`roomHasWashroom-${rid}`) ? (parseInt(getVal(`roomDetailWashroomCap-${rid}`)) || 1) : null,
        washroom_type: getVal(`roomDetailWashroomType-${rid}`, 'attached'),
        washroom_area: getChecked(`roomHasWashroom-${rid}`) ? (parseFloat(getVal(`roomDetailWashroomArea-${rid}`)) || 0) : 0,
        has_hot_water: getChecked(`roomDetailHotWater-${rid}`),
        has_shower: getChecked(`roomDetailShower-${rid}`),
        has_bathtub: getChecked(`roomDetailBathtub-${rid}`),
        has_wheelchair_access: getChecked(`roomDetailWheelchair-${rid}`),
        has_grab_bars: getChecked(`roomDetailGrabBars-${rid}`),
        has_visual_alerts: getChecked(`roomDetailVisualAlerts-${rid}`),
        accessibility_level: getVal(`roomDetailAccessibilityLevel-${rid}`, 'none')
    };
    
    const roomNumberInput = document.getElementById(`roomDetailNumber-${rid}`);
    const roomNameInput = document.getElementById(`roomDetailName-${rid}`);
    const statusInput = document.getElementById(`roomDetailStatus-${rid}`);
    const occupancyInput = document.getElementById(`roomDetailOccupancy-${rid}`);
    const floorLevelInput = document.getElementById(`roomDetailFloorLevel-${rid}`);
    
    return {
        id: rid,
        room_number: roomNumberInput?.value?.trim() || '',
        room_name: roomNameInput?.value?.trim() || null,
        room_type: roomTypeName, // Store the name, not the ID
        custom_room_type: roomTypeName === 'other' ? customType : null,
        status: statusInput?.value || 'active',
        occupancy_status: occupancyInput?.value || 'vacant',
        floor_level: floorLevelInput?.value?.trim() || null,
        selected_floor_amenities: room.selected_floor_amenities || {},
        selected_floor_facilities: room.selected_floor_facilities || [],
        has_balcony: hasBalcony,
        balcony_area: balconyUnits.reduce((sum, u) => sum + (u.area || 0), 0),
        balcony_units: balconyUnits,
        room_specifications: roomSpecifications
    };
}

// ===== UPDATE ROOM FROM DETAIL =====
async function updateRoomFromDetail(rid) {
    const data = getRoomDataFromDetail(rid);
    if (!data || !data.room_number) { 
        showToast('Room number is required', 'error'); 
        return; 
    }
    const btn = event?.target;
    const orig = btn?.innerHTML || 'Update Room';
    if (btn) { btn.innerHTML = '<span class="loading-spinner"></span> Updating...'; btn.disabled = true; }
    try {
        const r = await fetch(`${API_BASE_URL}/rooms/${rid}`, {
            method: 'PUT',
            headers: { 
                'Content-Type': 'application/json', 
                'Accept': 'application/json', 
                'X-CSRF-TOKEN': CSRF_TOKEN, 
                'X-Requested-With': 'XMLHttpRequest' 
            },
            body: JSON.stringify(data)
        });
        const result = await r.json();
        if (result.success) {
            showToast('Room updated successfully!');
            roomDataMap[rid] = { ...roomDataMap[rid], ...data };
            const dropdown = document.getElementById('roomSelectorDropdown');
            const option = dropdown?.querySelector(`option[value="${rid}"]`);
            if (option) option.textContent = `${data.room_number}${data.room_name ? ' - ' + data.room_name : ''}`;
            const fi = document.getElementById('roomFloorSelect').value;
            if (fi) await loadExistingRoomsAndFloorAmenities();
            if (dropdown) { dropdown.value = rid; onRoomSelectorChange(); }
        } else { 
            showToast(result.message || 'Failed to update room', 'error'); 
        }
    } catch (e) { 
        showToast('An error occurred', 'error'); 
    } finally { 
        if (btn) { btn.innerHTML = orig; btn.disabled = false; } 
    }
}

// ===== ADD/REMOVE ROOM =====
function addRoomFromDropdown() {
    saveCurrentRoomData();
    const fi = document.getElementById('roomFloorSelect').value;
    if (!fi) { showToast('Please select a floor first', 'error'); return; }
    const existingNumbers = new Set();
    Object.keys(roomDataMap).forEach(rid => { 
        const room = roomDataMap[rid]; 
        if (room && room.room_number) existingNumbers.add(room.room_number); 
    });
    let roomNumber = `Room ${existingRoomIds.length + 1}`;
    let counter = 1;
    while (existingNumbers.has(roomNumber)) { 
        counter++; 
        roomNumber = `Room ${counter}`; 
    }
    const rid = `new_${Date.now()}`;
    const roomData = {
        id: rid,
        room_number: roomNumber,
        room_name: '',
        room_type: 'classroom',
        custom_room_type: null,
        status: 'active',
        occupancy_status: 'vacant',
        floor_level: '',
        room_specifications: {
            capacity: null, min_capacity: null, occupancy_type: 'single',
            category: 'standard', usage_type: 'permanent',
            available_from: '09:00', available_to: '18:00', booking_required: 'no',
            area: null, length: null, width: null, height: null,
            doors: 0, windows: 0, door_type: 'single', window_type: 'casement',
            lights_count: 0, fans_count: 0, ac_count: 0, sockets_count: 0,
            lighting_type: 'led', ac_type: 'none',
            has_wifi: false, has_lan: false, has_telephone: false, has_cctv: false,
            internet_speed: null, network_type: 'both',
            has_projector: false, has_whiteboard: false, has_smart_board: false,
            has_desk: false, has_chair: false, has_cabinets: false,
            has_bed: false, has_tv: false, has_kitchenette: false,
            has_fridge: false, has_microwave: false, has_water_cooler: false,
            has_fire_extinguisher: false, has_fire_alarm: false,
            has_smoke_detector: false, has_sprinkler: false,
            has_emergency_exit: false, has_emergency_light: false,
            has_door_lock: false, has_smart_lock: false, has_intercom: false,
            security_level: 'medium',
            has_washroom: false, washroom_capacity: null, washroom_type: 'attached',
            washroom_area: 0, has_hot_water: false, has_shower: false,
            has_bathtub: false,
            has_wheelchair_access: false, has_grab_bars: false,
            has_visual_alerts: false, accessibility_level: 'none'
        },
        selected_floor_amenities: {},
        selected_floor_facilities: [],
        has_balcony: false,
        balcony_area: null,
        balcony_units: []
    };
    roomDataMap[rid] = roomData;
    existingRoomIds.push(rid);
    const dropdown = document.getElementById('roomSelectorDropdown');
    const option = document.createElement('option');
    option.value = rid;
    option.textContent = roomData.room_number;
    dropdown.appendChild(option);
    document.getElementById('roomCountBadge').textContent = `${existingRoomIds.length} rooms`;
    dropdown.value = rid;
    onRoomSelectorChange();
    showToast('New room added! Please fill in the details and click Update.', 'info');
    setTimeout(initRoomSpecTabs, 100);
}

function removeRoomFromDropdown(rid) {
    saveCurrentRoomData();
    if (existingRoomIds.length <= 1) { showToast('At least one room required', 'error'); return; }
    if (!confirm('Are you sure you want to delete this room?')) return;
    if (!rid.toString().startsWith('new_')) {
        fetch(`${API_BASE_URL}/rooms/${rid}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'X-Requested-With': 'XMLHttpRequest' }
        }).then(r => r.json()).then(result => {
            if (result.success) showToast('Room deleted successfully');
            else showToast(result.message || 'Failed to delete room', 'error');
        }).catch(e => showToast('Error deleting room', 'error'));
    }
    const index = existingRoomIds.indexOf(rid);
    if (index > -1) existingRoomIds.splice(index, 1);
    delete roomDataMap[rid];
    const dropdown = document.getElementById('roomSelectorDropdown');
    const option = dropdown?.querySelector(`option[value="${rid}"]`);
    if (option) option.remove();
    document.getElementById('roomCountBadge').textContent = `${existingRoomIds.length} rooms`;
    if (existingRoomIds.length > 0) { 
        dropdown.value = existingRoomIds[0]; 
        onRoomSelectorChange(); 
    } else {
        document.getElementById('roomDetailPanel').classList.remove('active');
        document.getElementById('roomDetailContainer').innerHTML = `
            <div class="empty-state-msg">
                <i class="fas fa-door-open"></i>
                <p>No rooms. Add a new room using the button below.</p>
            </div>`;
        document.getElementById('roomDetailPanel').classList.add('active');
        document.getElementById('selectedRoomBadge').style.display = 'none';
        document.getElementById('removeSelectedRoomBtn').style.display = 'none';
        selectedRoomId = null;
    }
}

function removeSelectedRoom() { 
    if (selectedRoomId) removeRoomFromDropdown(selectedRoomId); 
}

// ===== ROOM SELECTOR CHANGE =====
function onRoomSelectorChange() {
    saveCurrentRoomData();
    const rid = document.getElementById('roomSelectorDropdown').value;
    if (!rid || !roomDataMap[rid]) {
        document.getElementById('roomDetailPanel').classList.remove('active');
        document.getElementById('roomDetailContainer').innerHTML = '';
        document.getElementById('selectedRoomBadge').style.display = 'none';
        document.getElementById('removeSelectedRoomBtn').style.display = 'none';
        selectedRoomId = null;
        return;
    }
    selectedRoomId = rid;
    const room = roomDataMap[rid];
    document.getElementById('selectedRoomBadge').style.display = 'inline-flex';
    document.getElementById('selectedRoomName').textContent = `${room.room_number}${room.room_name ? ' - ' + room.room_name : ''}`;
    document.getElementById('removeSelectedRoomBtn').style.display = 'inline-flex';
    document.getElementById('roomDetailPanel').classList.add('active');
    const container = document.getElementById('roomDetailContainer');
    container.innerHTML = buildRoomDetailHTML(room);
    setTimeout(initRoomSpecTabs, 100);
    // Update area summary after rendering
    setTimeout(() => updateRoomAreaSummary(rid), 200);
}

// ===== SAVE CURRENT ROOM DATA =====
function saveCurrentRoomData() {
    if (!selectedRoomId || !roomDataMap[selectedRoomId]) return;
    const data = getRoomDataFromDetail(selectedRoomId);
    if (data && data.room_number) {
        roomDataMap[selectedRoomId] = { ...roomDataMap[selectedRoomId], ...data };
    }
}

// ===== SWITCH ROOM SPEC TAB =====
function switchRoomSpecTab(tabId, btn) {
    document.querySelectorAll('.room-specs-tabs .tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.room-specs-tab-content').forEach(c => c.classList.remove('active'));
    if (btn) btn.classList.add('active');
    const content = document.getElementById(`tab-${tabId}`);
    if (content) content.classList.add('active');
}

function initRoomSpecTabs() {
    const firstTab = document.querySelector('.room-specs-tabs .tab-btn');
    if (firstTab) {
        const tabId = firstTab.getAttribute('onclick')?.match(/'([^']+)'/)?.[1];
        if (tabId) {
            document.querySelectorAll('.room-specs-tabs .tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.room-specs-tab-content').forEach(c => c.classList.remove('active'));
            firstTab.classList.add('active');
            const content = document.getElementById(`tab-${tabId}`);
            if (content) content.classList.add('active');
        }
    }
}

// ===== SAVE ALL ROOMS =====
async function saveAllRoomsAndContinue() {
    saveCurrentRoomData();
    const bi = document.getElementById('roomBuildingId').value;
    const fi = document.getElementById('roomFloorSelect').value;
    if (!bi) { showToast('Please select a building', 'error'); return; }
    if (!fi) { showToast('Please select a floor', 'error'); return; }
    
    const rooms = [];
    existingRoomIds.forEach(rid => {
        if (rid.toString().startsWith('new_')) {
            const data = getRoomDataFromDetail(rid);
            if (data && data.room_number) { 
                delete data.id; 
                rooms.push(data); 
            }
        } else {
            const data = getRoomDataFromDetail(rid);
            if (data && data.room_number) rooms.push(data);
        }
    });
    
    if (rooms.length === 0) { showToast('No rooms to save', 'error'); return; }
    
    const btn = document.getElementById('saveRoomsBtn');
    const orig = btn?.innerHTML || 'Save All Rooms';
    if (btn) { btn.innerHTML = '<span class="loading-spinner"></span> Saving...'; btn.disabled = true; }
    try {
        const r = await fetch(`${API_BASE_URL}/rooms/bulk`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'Accept': 'application/json', 
                'X-CSRF-TOKEN': CSRF_TOKEN, 
                'X-Requested-With': 'XMLHttpRequest' 
            },
            body: JSON.stringify({ floor_id: fi, rooms: rooms })
        });
        const result = await r.json();
        if (result.success) {
            showToast('All rooms saved successfully!');
            await loadExistingRoomsAndFloorAmenities();
        } else { 
            showToast(result.message || 'Failed to save rooms', 'error'); 
        }
    } catch (e) { 
        showToast('An error occurred', 'error'); 
    } finally { 
        if (btn) { btn.innerHTML = orig; btn.disabled = false; } 
    }
}

// ===== STEP NAVIGATION =====
function goToStep(step) {
    if (step === 2 && selectedBlockId && blockDataMap[selectedBlockId]) {
        saveCurrentBlockData();
    } else if (step === 3 && selectedFloorId && floorDataMap[selectedFloorId]) {
        saveCurrentFloorData();
    } else if (step === 4 && selectedRoomId && roomDataMap[selectedRoomId]) {
        saveCurrentRoomData();
    }

    currentStep = step;
    document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
    const content = document.getElementById(`step${step}Content`);
    if (content) content.classList.add('active');

    document.querySelectorAll('.step-circle, .step-label, .step-connector').forEach(el => {
        el.classList.remove('active', 'active-label', 'completed', 'completed-label');
    });

    // if (step >= 1) {
    //     const circle1 = document.getElementById('stepCircle1');
    //     const label1 = document.getElementById('stepLabel1');
    //     if (circle1) circle1.classList.add(step === 1 ? 'active' : 'completed');
    //     if (label1) label1.classList.add(step === 1 ? 'active-label' : 'completed-label');
    // }
    if (step >= 2) {
        const circle2 = document.getElementById('stepCircle2');
        const label2 = document.getElementById('stepLabel2');
        const connector1 = document.getElementById('stepConnector1');
        if (circle2) circle2.classList.add(step === 2 ? 'active' : 'completed');
        if (label2) label2.classList.add(step === 2 ? 'active-label' : 'completed-label');
        if (connector1) connector1.classList.add('completed');
    }
    if (step >= 3) {
        const circle3 = document.getElementById('stepCircle3');
        const label3 = document.getElementById('stepLabel3');
        const connector2 = document.getElementById('stepConnector2');
        if (circle3) circle3.classList.add(step === 3 ? 'active' : 'completed');
        if (label3) label3.classList.add(step === 3 ? 'active-label' : 'completed-label');
        if (connector2) connector2.classList.add('completed');
    }
    if (step >= 4) {
        const circle4 = document.getElementById('stepCircle4');
        const label4 = document.getElementById('stepLabel4');
        const connector3 = document.getElementById('stepConnector3');
        if (circle4) circle4.classList.add('active');
        if (label4) label4.classList.add('active-label');
        if (connector3) connector3.classList.add('completed');
    }
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ===== POPULATE CAMPUS FORM =====
function populateCampusForm(data) {
    if (!data || !data.id) return;

    if (data.name) document.getElementById('campus-name').value = data.name;
    if (data.code) document.getElementById('campus-code').value = data.code;
    if (data.area_value) document.getElementById('area-value').value = data.area_value;
    if (data.area_unit) {
        const unitSelect = document.getElementById('area-unit');
        if (unitSelect) unitSelect.value = data.area_unit;
        campusAreaUnit = data.area_unit;
    }
    if (data.number_of_blocks) document.getElementById('num-of-blocks').value = data.number_of_blocks;

    if (data.additional_areas && Array.isArray(data.additional_areas) && data.additional_areas.length > 0) {
        const container = document.getElementById('additional-areas-container');
        container.innerHTML = '';
        data.additional_areas.forEach((area, index) => {
            const id = index + 1;
            const card = document.createElement('div');
            card.className = 'dynamic-card';
            card.id = `additional-area-${id}`;
            const areaId = area.id || generateUUID();
            card.setAttribute('data-area-uuid', areaId);
            card.innerHTML = `
                <div class="card-badge">Area #${id}</div>
                <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;">
                    <div class="form-group"><label>Area Name</label><input type="text" id="area-name-${id}" class="form-control" value="${escapeHtml(area.name || '')}" placeholder="e.g., Garden"></div>
                    <div class="form-group"><label>Area Unit</label><select id="area-unit-${id}" class="form-control area-unit-select">${generateUnitOptions(area.unit || campusAreaUnit)}</select></div>
                    <div class="form-group"><label>Area Value</label><input type="number" id="area-value-${id}" class="form-control" value="${area.area || ''}" placeholder="Enter area" min="0" step="0.01"></div>
                </div>
                <div style="text-align:right;margin-top:0.75rem;"><button type="button" class="btn-outline-danger" onclick="removeCard('additional-area-${id}')"><i class="fas fa-trash"></i></button></div>
            `;
            container.appendChild(card);
        });
        areaCounter = data.additional_areas.length;
    }

    if (data.gates && Array.isArray(data.gates) && data.gates.length > 0) {
        const container = document.getElementById('gates-container');
        container.innerHTML = '';
        data.gates.forEach((gate, index) => {
            const id = index + 1;
            const card = document.createElement('div');
            card.className = 'dynamic-card';
            card.id = `gate-${id}`;
            const gateId = gate.id || generateUUID();
            card.setAttribute('data-gate-uuid', gateId);
            card.innerHTML = `
                <div class="card-badge">Gate #${id}</div>
                <div class="card-row" style="grid-template-columns:1fr 1fr;">
                    <div class="form-group"><label>Gate Name</label><input type="text" id="gate-name-${id}" class="form-control" value="${escapeHtml(gate.name || '')}" placeholder="e.g., Main Gate"></div>
                    <div class="form-group"><label>Gate Number</label><input type="text" id="gate-number-${id}" class="form-control" value="${escapeHtml(gate.number || '')}" placeholder="e.g., G-01"></div>
                </div>
                <div style="text-align:right;margin-top:0.75rem;"><button type="button" class="btn-outline-danger" onclick="removeCard('gate-${id}')"><i class="fas fa-trash"></i></button></div>
            `;
            container.appendChild(card);
        });
        gateCounter = data.gates.length;
    }

    if (data.facilities && data.facilities.washrooms && Array.isArray(data.facilities.washrooms) && data.facilities.washrooms.length > 0) {
        document.getElementById('has-washrooms').checked = true;
        toggleFacilityDetails('washrooms-details');
        populateWashroomData(data.facilities.washrooms);
    }

    if (data.custom_amenities && Array.isArray(data.custom_amenities) && data.custom_amenities.length > 0) {
        const container = document.getElementById('custom-amenities-container');
        container.innerHTML = '';
        data.custom_amenities.forEach((amenity, index) => {
            const id = index + 1;
            const card = document.createElement('div');
            card.className = 'custom-amenity-card';
            card.id = `custom-amenity-${id}`;
            const amenityId = amenity.id || generateUUID();
            card.setAttribute('data-amenity-id', amenityId);
            card.innerHTML = `
                <div class="card-badge">Custom #${id}</div>
                <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;gap:0.75rem;">
                    <div class="form-group"><label>Amenity Name</label><input type="text" id="custom-amenity-name-${id}" class="form-control" value="${escapeHtml(amenity.name || '')}" placeholder="e.g., Projector"></div>
                    <div class="form-group"><label>Amenity Type</label><select id="custom-amenity-type-${id}" class="form-control custom-amenity-type-select" onchange="onCustomAmenityTypeChange(${id})"><option value="count" ${amenity.type === 'count' ? 'selected' : ''}>Quantity Based</option><option value="space" ${amenity.type === 'space' ? 'selected' : ''}>Space/Area Based</option></select></div>
                    <div class="form-group"><label>Quantity</label><input type="number" id="custom-amenity-qty-${id}" class="form-control" value="${amenity.quantity || 1}" min="1"></div>
                </div>
                <div id="custom-amenity-space-${id}" class="custom-amenity-space-details" style="${amenity.type === 'space' ? 'display:block;' : 'display:none;'}margin-top:0.75rem;padding-top:0.75rem;border-top:1px dashed var(--border-color);">
                    <div class="card-row" style="grid-template-columns:1fr 1fr;gap:0.75rem;">
                        <div class="form-group"><label>Area per Unit</label><div class="inline-input"><input type="number" id="custom-amenity-area-${id}" class="form-control" value="${amenity.area_per_unit || ''}" placeholder="Area" min="0" step="0.01"><span id="custom-amenity-area-unit-label-${id}">${getUnitDisplayName(campusAreaUnit)}</span></div></div>
                        <div class="form-group"><label>Total Area</label><div class="inline-input"><input type="number" id="custom-amenity-total-area-${id}" class="form-control" value="${amenity.total_area || ''}" placeholder="Auto-calculated" readonly style="background:#e8edff;"><span>${getUnitDisplayName(campusAreaUnit)}</span></div></div>
                    </div>
                </div>
                <div style="text-align:right;margin-top:0.75rem;">
                    <button type="button" class="btn-outline-danger" onclick="removeCustomAmenity(${id})"><i class="fas fa-trash"></i> Remove</button>
                </div>
            `;
            container.appendChild(card);
            customAmenityTypes[id] = amenity.type || 'count';
            if (amenity.type === 'space') {
                updateCustomAmenityTotalArea(id);
            }
        });
        customAmenityCounter = data.custom_amenities.length;
        updateAllCustomAmenityBadges();
    }

    const facilities = data.facilities || {};
    if (facilities.parking && facilities.parking.enabled) {
        document.getElementById('has-parking').checked = true;
        toggleFacilityDetails('parking-details');
        if (facilities.parking.basement && facilities.parking.basement.length > 0) {
            document.getElementById('basement-parking-count').value = facilities.parking.basement.length;
            generateBasementParkingEntries();
            facilities.parking.basement.forEach((entry, idx) => {
                const cards = document.querySelectorAll('#basement-parking-container .multi-entry-card');
                if (cards[idx]) {
                    if (entry.id) cards[idx].setAttribute('data-entry-id', entry.id);
                    cards[idx].querySelector('.basement-name').value = entry.name || '';
                    cards[idx].querySelector('.basement-area').value = entry.area || '';
                    cards[idx].querySelector('.basement-capacity').value = entry.capacity || '';
                }
            });
        }
        if (facilities.parking.open && facilities.parking.open.length > 0) {
            document.getElementById('open-parking-count').value = facilities.parking.open.length;
            generateOpenParkingEntries();
            facilities.parking.open.forEach((entry, idx) => {
                const cards = document.querySelectorAll('#open-parking-container .multi-entry-card');
                if (cards[idx]) {
                    if (entry.id) cards[idx].setAttribute('data-entry-id', entry.id);
                    cards[idx].querySelector('.open-name').value = entry.name || '';
                    cards[idx].querySelector('.open-area').value = entry.area || '';
                    cards[idx].querySelector('.open-capacity').value = entry.capacity || '';
                }
            });
        }
    }
    if (facilities.playground && facilities.playground.enabled) {
        document.getElementById('has-playground').checked = true;
        toggleFacilityDetails('playground-details');
        if (facilities.playground.indoor && facilities.playground.indoor.length > 0) {
            document.getElementById('indoor-playground-count').value = facilities.playground.indoor.length;
            generateIndoorPlaygroundEntries();
            facilities.playground.indoor.forEach((entry, idx) => {
                const cards = document.querySelectorAll('#indoor-playground-container .multi-entry-card');
                if (cards[idx]) {
                    if (entry.id) cards[idx].setAttribute('data-entry-id', entry.id);
                    cards[idx].querySelector('.indoor-pg-name').value = entry.name || '';
                    cards[idx].querySelector('.indoor-pg-area').value = entry.area || '';
                    cards[idx].querySelector('.indoor-pg-capacity').value = entry.capacity || '';
                }
            });
        }
        if (facilities.playground.outdoor && facilities.playground.outdoor.length > 0) {
            document.getElementById('outdoor-playground-count').value = facilities.playground.outdoor.length;
            generateOutdoorPlaygroundEntries();
            facilities.playground.outdoor.forEach((entry, idx) => {
                const cards = document.querySelectorAll('#outdoor-playground-container .multi-entry-card');
                if (cards[idx]) {
                    if (entry.id) cards[idx].setAttribute('data-entry-id', entry.id);
                    cards[idx].querySelector('.outdoor-pg-name').value = entry.name || '';
                    cards[idx].querySelector('.outdoor-pg-area').value = entry.area || '';
                    cards[idx].querySelector('.outdoor-pg-capacity').value = entry.capacity || '';
                }
            });
        }
    }
    if (facilities.swimming_pool && facilities.swimming_pool.enabled) {
        document.getElementById('has-swimming-pool').checked = true;
        toggleFacilityDetails('pool-details');
        if (facilities.swimming_pool.indoor && facilities.swimming_pool.indoor.length > 0) {
            document.getElementById('indoor-pool-count').value = facilities.swimming_pool.indoor.length;
            generateIndoorPoolEntries();
            facilities.swimming_pool.indoor.forEach((entry, idx) => {
                const cards = document.querySelectorAll('#indoor-pool-container .multi-entry-card');
                if (cards[idx]) {
                    if (entry.id) cards[idx].setAttribute('data-entry-id', entry.id);
                    cards[idx].querySelector('.indoor-pool-name').value = entry.name || '';
                    cards[idx].querySelector('.indoor-pool-area').value = entry.area || '';
                    cards[idx].querySelector('.indoor-pool-capacity').value = entry.capacity || '';
                }
            });
        }
        if (facilities.swimming_pool.outdoor && facilities.swimming_pool.outdoor.length > 0) {
            document.getElementById('outdoor-pool-count').value = facilities.swimming_pool.outdoor.length;
            generateOutdoorPoolEntries();
            facilities.swimming_pool.outdoor.forEach((entry, idx) => {
                const cards = document.querySelectorAll('#outdoor-pool-container .multi-entry-card');
                if (cards[idx]) {
                    if (entry.id) cards[idx].setAttribute('data-entry-id', entry.id);
                    cards[idx].querySelector('.outdoor-pool-name').value = entry.name || '';
                    cards[idx].querySelector('.outdoor-pool-area').value = entry.area || '';
                    cards[idx].querySelector('.outdoor-pool-capacity').value = entry.capacity || '';
                }
            });
        }
    }
    if (facilities.clubhouse && facilities.clubhouse.enabled) {
        document.getElementById('has-clubhouse').checked = true;
        toggleFacilityDetails('clubhouse-details');
        document.getElementById('clubhouse-area').value = facilities.clubhouse.area || '';
        document.getElementById('clubhouse-capacity').value = facilities.clubhouse.capacity || '';
    }
    if (facilities.warehouse && facilities.warehouse.enabled) {
        document.getElementById('has-warehouse').checked = true;
        toggleFacilityDetails('warehouse-details');
        if (facilities.warehouse.entries && facilities.warehouse.entries.length > 0) {
            document.getElementById('warehouse-count').value = facilities.warehouse.entries.length;
            generateWarehouseEntries();
            facilities.warehouse.entries.forEach((entry, idx) => {
                const cards = document.querySelectorAll('#warehouse-entries-container .multi-entry-card');
                if (cards[idx]) {
                    if (entry.id) cards[idx].setAttribute('data-entry-id', entry.id);
                    cards[idx].querySelector('.wh-name').value = entry.name || '';
                    cards[idx].querySelector('.wh-area').value = entry.area || '';
                    cards[idx].querySelector('.wh-capacity').value = entry.capacity || '';
                    const unitSelect = cards[idx].querySelector('.wh-unit');
                    if (unitSelect) unitSelect.value = entry.capacity_unit || 'tons';
                }
            });
        }
    }
    if (facilities.store_room && facilities.store_room.enabled) {
        document.getElementById('has-store').checked = true;
        toggleFacilityDetails('store-details');
        if (facilities.store_room.entries && facilities.store_room.entries.length > 0) {
            document.getElementById('store-room-count').value = facilities.store_room.entries.length;
            generateStoreEntries();
            facilities.store_room.entries.forEach((entry, idx) => {
                const cards = document.querySelectorAll('#store-entries-container .multi-entry-card');
                if (cards[idx]) {
                    if (entry.id) cards[idx].setAttribute('data-entry-id', entry.id);
                    cards[idx].querySelector('.st-name').value = entry.name || '';
                    cards[idx].querySelector('.st-area').value = entry.area || '';
                    cards[idx].querySelector('.st-capacity').value = entry.capacity || '';
                    const unitSelect = cards[idx].querySelector('.st-unit');
                    if (unitSelect) unitSelect.value = entry.capacity_unit || 'units';
                    const typeSelect = cards[idx].querySelector('.st-type');
                    if (typeSelect) typeSelect.value = entry.storage_type || 'general';
                    const tempSelect = cards[idx].querySelector('.st-temp');
                    if (tempSelect) tempSelect.value = entry.temperature_controlled || 'no';
                }
            });
        }
    }
    if (facilities.auditorium && facilities.auditorium.enabled) {
        document.getElementById('has-auditorium').checked = true;
        toggleFacilityDetails('auditorium-details');
        if (facilities.auditorium.entries && facilities.auditorium.entries.length > 0) {
            document.getElementById('auditorium-count').value = facilities.auditorium.entries.length;
            generateAuditoriumEntries();
            facilities.auditorium.entries.forEach((entry, idx) => {
                const cards = document.querySelectorAll('#auditorium-entries-container .multi-entry-card');
                if (cards[idx]) {
                    if (entry.id) cards[idx].setAttribute('data-entry-id', entry.id);
                    cards[idx].querySelector('.aud-name').value = entry.name || '';
                    cards[idx].querySelector('.aud-area').value = entry.area || '';
                    cards[idx].querySelector('.aud-capacity').value = entry.capacity || '';
                    const stageSelect = cards[idx].querySelector('.aud-stage');
                    if (stageSelect) stageSelect.value = entry.stage || 'yes';
                }
            });
        }
    }

    if (data.amenities && typeof data.amenities === 'object') {
        const amenities = data.amenities;
        Object.keys(amenities).forEach(key => {
            const cb = document.getElementById(`amenity-${key}`);
            if (cb) {
                const val = amenities[key];
                if (typeof val === 'object' && val !== null) {
                    if (val.enabled) {
                        cb.checked = true;
                        const detail = document.getElementById(`amenity-detail-${key}`);
                        if (detail) detail.style.display = 'block';
                        Object.keys(val).forEach(subKey => {
                            const input = document.getElementById(`amenity-${key}-${subKey}`);
                            if (input && val[subKey] !== undefined && val[subKey] !== null) {
                                input.value = val[subKey];
                            }
                        });
                    }
                } else if (typeof val === 'number' && val > 0) {
                    cb.checked = true;
                    const ci = document.getElementById(`amenity-count-${key}`);
                    if (ci) {
                        ci.value = val;
                        ci.classList.add('show');
                        const cl = document.getElementById(`count-label-${key}`);
                        if (cl) cl.classList.add('show');
                    }
                } else if (typeof val === 'boolean' && val) {
                    cb.checked = true;
                }
            }
        });
        updateAmenityHighlight();
    }

    if (data.custom_amenities && Array.isArray(data.custom_amenities)) {
        data.custom_amenities.forEach((amenity, idx) => {
            const id = idx + 1;
            if (amenity.type === 'space') {
                const typeSelect = document.getElementById(`custom-amenity-type-${id}`);
                if (typeSelect) typeSelect.value = 'space';
                onCustomAmenityTypeChange(id);
            }
        });
    }
}

// ===== INITIALIZATION =====
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('amenitiesGrid').innerHTML = generateCampusAmenitiesHTML();
    campusAreaUnit = document.getElementById('area-unit').value;
    toggleAcTypeDetails();
    blockAllocations = {};
    blockIdCounter = 0;
    campusAmenityTotals = {};
    campusCustomAmenityTotals = {};
    campusFacilityEntries = {};
    populateCampusForm(CAMPUS_DATA);
    generateBasementParkingEntries();
    generateOpenParkingEntries();
    generateIndoorPlaygroundEntries();
    generateOutdoorPlaygroundEntries();
    generateIndoorPoolEntries();
    generateOutdoorPoolEntries();
    generateWarehouseEntries();
    generateStoreEntries();
    generateAuditoriumEntries();
    if (CAMPUS_DATA && CAMPUS_DATA.facilities && CAMPUS_DATA.facilities.washrooms && CAMPUS_DATA.facilities.washrooms.length > 0) {
        document.getElementById('has-washrooms').checked = true;
        toggleFacilityDetails('washrooms-details');
        generateWashroomEntries();
    }
    
    // Fetch room types when step 4 is loaded
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                const step4Content = document.getElementById('step4Content');
                if (step4Content && step4Content.classList.contains('active') && roomTypeOptions.length === 0) {
                    fetchRoomTypes();
                    observer.disconnect();
                }
            }
        });
    });
    const step4Content = document.getElementById('step4Content');
    if (step4Content) {
        observer.observe(step4Content, { attributes: true });
    }
});
</script>
</body>
</html>
@endsection
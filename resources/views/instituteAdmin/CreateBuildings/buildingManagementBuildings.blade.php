@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Block & Floor Setup</title>
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
        .header-content p{opacity:0.9;font-size:1rem;margin:5px 0 0 0}
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
        .alert-success{background:linear-gradient(135deg,#f0fdf4,#dcfce7);color:#065f46;border:1px solid #86efac;border-radius:12px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px}
        .alert-success i{font-size:1.2rem;margin-top:2px;color:#10b981}
        .campus-info-header{background:linear-gradient(135deg,#f0f4ff,#e8edff);border:2px solid rgba(67,97,238,0.2);border-radius:16px;padding:1.25rem 1.5rem;margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
        .campus-info-header .campus-name-section{display:flex;align-items:center;gap:12px}
        .campus-info-header .campus-name{font-size:1.2rem;font-weight:700;color:var(--text-dark)}
        .campus-info-header .campus-code{background:var(--primary-gradient);color:white;padding:5px 14px;border-radius:20px;font-size:0.8rem;font-weight:600}
        .campus-info-header .area-display{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
        .campus-info-header .area-badge{background:white;padding:8px 16px;border-radius:10px;font-weight:700;color:var(--primary-color);border:2px solid rgba(67,97,238,0.2);font-size:0.9rem;display:flex;align-items:center;gap:6px}
        .campus-info-header .remaining-badge{background:var(--success-gradient);color:white;padding:8px 16px;border-radius:10px;font-weight:700;font-size:0.85rem;display:flex;align-items:center;gap:6px}
        .campus-info-header .exceeded-badge{background:var(--danger-gradient);color:white;padding:8px 16px;border-radius:10px;font-weight:700;font-size:0.85rem;display:flex;align-items:center;gap:6px}
        
        /* Tab System */
        .tabs-container{margin:1.5rem 0}
        .tabs{display:flex;gap:8px;flex-wrap:wrap;border-bottom:3px solid var(--border-color);padding-bottom:0.5rem;margin-bottom:1.5rem}
        .tab{display:flex;align-items:center;gap:8px;padding:12px 24px;border:2px solid var(--border-color);border-radius:12px 12px 0 0;cursor:pointer;transition:all 0.3s;background:white;font-weight:600;color:var(--text-muted);position:relative;font-size:0.9rem;margin-bottom:-3px}
        .tab:hover:not(.locked){background:#f8fafc;border-color:var(--primary-color)}
        .tab.active{background:var(--primary-gradient);color:white;border-color:var(--primary-color);box-shadow:0 -4px 15px rgba(67,97,238,0.15)}
        .tab .tab-status{width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.6rem;flex-shrink:0}
        .tab .tab-status.saved{background:#10b981;color:white}
        .tab .tab-status.pending{background:var(--warning-gradient);color:white}
        .tab .tab-status.error{background:var(--danger-gradient);color:white}
        .tab .tab-status.locked-status{background:#3b82f6;color:white}
        .tab .tab-number{font-weight:700;margin-right:4px}
        .tab .tab-remove-btn{background:transparent;border:none;color:var(--text-muted);cursor:pointer;font-size:0.7rem;padding:2px 6px;border-radius:4px;transition:all 0.3s}
        .tab .tab-remove-btn:hover{background:#fef2f2;color:#dc2626}
        .tab.active .tab-remove-btn{color:rgba(255,255,255,0.7)}
        .tab.active .tab-remove-btn:hover{background:rgba(255,255,255,0.2);color:white}
        .tab.locked{opacity:0.8;background:#f0fdf4;border-color:#86efac;cursor:not-allowed;pointer-events:none}
        .tab.locked .tab-status{background:#3b82f6;color:white}
        .tab.locked .tab-lock{color:#3b82f6;margin-left:4px}
        .tab.locked .tab-remove-btn{display:none}
        .tab.locked .lock-indicator{background:#3b82f6;color:white;padding:2px 10px;border-radius:12px;font-size:0.65rem;font-weight:600;margin-left:6px;display:inline-block}
        .block-locked-message{background:#dcfce7;border:2px solid #86efac;border-radius:12px;padding:12px 16px;margin-top:1rem;text-align:center;color:#065f46;font-weight:600;display:flex;align-items:center;justify-content:center;gap:8px}
        .tab-content{display:none;animation:fadeIn 0.3s ease}
        .tab-content.active{display:block}
        .tab-content.locked-content{opacity:0.7;pointer-events:none}
        .tab-content.locked-content .btn{opacity:0.5;cursor:not-allowed}
        .tab-content.locked-content .accordion-header{cursor:default !important;pointer-events:none}
        .save-status{display:flex;align-items:center;gap:8px;padding:8px 16px;border-radius:10px;margin-bottom:1rem;font-size:0.85rem;font-weight:600}
        .save-status.saved{background:#dcfce7;color:#065f46;border:1px solid #86efac}
        .save-status.pending{background:#fef3c7;color:#92400e;border:1px solid #fde68a}
        .save-status.error{background:#fef2f2;color:#991b1b;border:1px solid #fca5a5}
        .save-status.locked-status{background:#dbeafe;color:#1e40af;border:1px solid #93c5fd}
        
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
        .loading-spinner{display:inline-block;width:20px;height:20px;border:3px solid rgba(255,255,255,0.3);border-top:3px solid white;border-radius:50%;animation:spin 1s linear infinite}
        @keyframes spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}
        @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        .toast{position:fixed;bottom:20px;right:20px;background:var(--success-gradient);color:white;padding:16px 24px;border-radius:12px;box-shadow:0 8px 25px rgba(0,0,0,0.15);display:flex;align-items:center;gap:10px;z-index:1001;transform:translateY(100px);opacity:0;transition:all 0.3s ease}
        .toast.show{transform:translateY(0);opacity:1}
        .toast.error{background:var(--danger-gradient)}
        .toast.info{background:var(--info-gradient)}
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
        .block-alloc-summary{background:linear-gradient(135deg,#f0f4ff,#e8edff);border-radius:8px;padding:6px 14px;font-size:0.7rem;display:inline-flex;align-items:center;gap:8px;border:1px solid rgba(67,97,238,0.15)}
        .block-alloc-summary i{color:var(--primary-color)}
        .add-btn-area{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:1rem}
        .add-btn-area .btn{font-size:0.85rem;padding:0.6rem 1.2rem}
        .existing-count-badge{background:var(--success-gradient);color:white;padding:4px 12px;border-radius:20px;font-size:0.75rem;font-weight:600}
        .existing-floor-card{background:linear-gradient(135deg,#f0fdf4,#ecfdf5);border:2px solid #6ee7b7;border-radius:14px;padding:1.25rem;margin-bottom:0.75rem}
        .existing-floor-card .existing-badge{background:var(--success-gradient);color:white;padding:3px 12px;border-radius:20px;font-size:0.7rem;font-weight:700;display:inline-block;margin-bottom:0.5rem}
        .existing-floor-card .floor-info{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem}
        .existing-floor-card .floor-name{font-weight:700;color:var(--text-dark);font-size:0.9rem}
        .existing-floor-card .floor-meta{font-size:0.75rem;color:var(--text-muted)}
        .no-items-message{text-align:center;padding:1.5rem;color:var(--text-muted);background:#f8fafc;border-radius:10px;border:1px dashed var(--border-color)}
        .floor-card{background:white;border:2px solid var(--border-color);border-radius:16px;padding:1.75rem;margin-bottom:1.5rem;transition:all 0.3s ease;position:relative}
        .floor-card:hover{border-color:var(--primary-color);box-shadow:0 4px 15px rgba(67,97,238,0.1)}
        .floor-card .card-badge{position:absolute;top:-14px;left:20px;background:var(--primary-gradient);color:white;padding:5px 16px;border-radius:20px;font-size:0.8rem;font-weight:700}
        .floor-card.basement{border-color:#f59e0b;background:#fffbeb}
        .floor-card.basement .card-badge{background:var(--warning-gradient)}
        .selected-item-badge{display:inline-flex;align-items:center;gap:6px;background:var(--success-gradient);color:white;padding:4px 14px;border-radius:20px;font-size:0.7rem;font-weight:600}
        .selected-item-badge i{font-size:0.6rem}
        .custom-amenity-card{background:linear-gradient(135deg,#f0fdf4,#ecfdf5);border:2px dashed #6ee7b7;border-radius:14px;padding:1.25rem;margin-bottom:1rem;transition:all 0.3s ease;position:relative}
        .custom-amenity-card:hover{border-color:#10b981;box-shadow:0 4px 15px rgba(16,185,129,0.1)}
        .custom-amenity-card .card-badge-sm,.custom-amenity-card .card-badge{position:absolute;top:-12px;left:16px;background:var(--success-gradient);color:white;padding:3px 12px;border-radius:20px;font-size:0.7rem;font-weight:700}
        .area-deduction-summary{background:#f8fafc;border:1px solid var(--border-color);border-radius:8px;padding:10px 14px;margin:8px 0;font-size:0.85rem}
        .area-deduction-summary .deduction-item{display:flex;justify-content:space-between;padding:3px 0;border-bottom:1px dashed #e2e8f0}
        .area-deduction-summary .deduction-item:last-child{border-bottom:none}
        .area-deduction-summary .total-row{font-weight:700;color:var(--text-dark);margin-top:5px;padding-top:5px;border-top:2px solid var(--border-color)}
        .area-deduction-summary .positive{color:#059669}
        .area-deduction-summary .negative{color:#dc2626}
        .facility-group-card{background:white;border:1px solid var(--border-color);border-radius:10px;margin-bottom:8px;overflow:hidden;transition:all 0.3s}
        .facility-group-card .group-header{display:flex;justify-content:space-between;align-items:center;padding:8px 14px;cursor:pointer;background:#f8fafc;transition:background 0.2s;user-select:none}
        .facility-group-card .group-header:hover{background:#f0f4ff}
        .facility-group-card .group-header .group-title{font-weight:600;font-size:0.85rem;color:var(--text-dark);display:flex;align-items:center;gap:8px}
        .facility-group-card .group-header .group-title i{color:var(--primary-color);width:18px}
        .facility-group-card .group-header .group-badge{font-size:0.7rem;padding:2px 10px;border-radius:12px;background:#e2e8f0;color:var(--text-muted)}
        .facility-group-card .group-header .group-toggle{transition:transform 0.3s}
        .facility-group-card .group-header .group-toggle.open{transform:rotate(180deg)}
        .facility-group-card .group-body{max-height:0;overflow:auto;transition:max-height 0.3s ease}
        .facility-group-card .group-body.open{max-height:500px}
        .facility-group-card .group-body-inner{padding:8px 14px 14px}
        .facility-group-card .group-body-inner .allocation-item{margin-bottom:4px;border:none;border-bottom:1px solid var(--border-color);border-radius:0;padding:6px 0}
        .facility-group-card .group-body-inner .allocation-item:last-child{border-bottom:none}
        .facility-group-card .group-body-inner .allocation-item .alloc-name{font-size:0.8rem}
        .facility-group-card .group-body-inner .allocation-item .alloc-stats{font-size:0.65rem}
        .facility-group-card .group-body-inner .allocation-item .alloc-qty{width:50px;font-size:0.7rem;padding:2px 4px}
        .washroom-summary-badge{display:inline-block;background:#f0f4ff;border:1px solid rgba(67,97,238,0.2);border-radius:20px;padding:2px 12px;font-size:0.65rem;font-weight:600;color:var(--primary-color);margin:2px 4px 2px 0}
        .washroom-summary-badge i{margin-right:4px}
        .washroom-summary-badge.male{background:#dbeafe;color:#1e40af;border-color:#93c5fd}
        .washroom-summary-badge.female{background:#fce7f3;color:#9d174d;border-color:#f9a8d4}
        .washroom-summary-badge.unisex{background:#dcfce7;color:#166534;border-color:#86efac}
        .modal-overlay{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:2000;display:flex;align-items:center;justify-content:center;animation:fadeIn 0.3s ease}
        .modal-box{background:white;border-radius:20px;padding:2rem;max-width:500px;width:90%;box-shadow:0 25px 50px rgba(0,0,0,0.3)}
        .modal-box h3{color:var(--text-dark);margin-bottom:0.5rem;font-size:1.3rem}
        .modal-box p{color:var(--text-muted);margin-bottom:1.5rem;line-height:1.6}
        .modal-box .modal-actions{display:flex;gap:1rem;justify-content:flex-end}
        .modal-box .modal-actions .btn{padding:0.6rem 1.5rem}
        .locked-block-disabled{opacity:0.6;pointer-events:none;cursor:not-allowed}
        .locked-block-disabled input,.locked-block-disabled select,.locked-block-disabled textarea,.locked-block-disabled button{opacity:0.5;cursor:not-allowed}
        @media(max-width:768px){.header{flex-direction:column;gap:1rem;text-align:center}.form-card{padding:1.5rem}.form-actions{flex-direction:column}.tabs{flex-wrap:nowrap;overflow-x:auto;gap:4px}.tab{padding:8px 14px;font-size:0.8rem;white-space:nowrap}.tab .tab-number{display:none}}
        @media(max-width:480px){.tab{padding:6px 10px;font-size:0.7rem}.tab .tab-status{width:16px;height:16px;font-size:0.5rem}}
        
        /* Amenity Category Badge Styles */
        .amenity-category-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 0.6rem;
            font-weight: 600;
            margin-left: 6px;
        }
        .amenity-category-badge.technology { background: #dbeafe; color: #1e40af; }
        .amenity-category-badge.security { background: #fef3c7; color: #92400e; }
        .amenity-category-badge.accessibility { background: #d1fae5; color: #065f46; }
        .amenity-category-badge.utilities { background: #fce7f3; color: #9d174d; }
        .amenity-category-badge.hygiene { background: #e0e7ff; color: #3730a3; }
        .amenity-category-badge.food { background: #fef2f2; color: #991b1b; }
        .amenity-category-badge.education { background: #ede9fe; color: #5b21b6; }
        .amenity-category-badge.recreation { background: #d1fae5; color: #065f46; }
        .amenity-category-badge.medical { background: #fce7f3; color: #9d174d; }
        .amenity-category-badge.services { background: #dbeafe; color: #1e40af; }
        .amenity-category-badge.accommodation { background: #fef3c7; color: #92400e; }
        .amenity-category-badge.general { background: #f1f5f9; color: #475569; }

        /* Amenities Redirect Card */
        .amenities-redirect-card {
            background: linear-gradient(135deg, #f0f4ff, #e8edff);
            border: 2px solid rgba(67,97,238,0.2);
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            margin-top: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .amenities-redirect-card .left-content {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .amenities-redirect-card .left-content .icon {
            width: 50px;
            height: 50px;
            background: var(--primary-gradient);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        .amenities-redirect-card .left-content .text-content h5 {
            color: var(--text-dark);
            font-weight: 700;
            margin: 0;
            font-size: 1rem;
        }
        .amenities-redirect-card .left-content .text-content p {
            color: var(--text-muted);
            margin: 2px 0 0 0;
            font-size: 0.85rem;
        }
        .btn-purple {
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .btn-purple:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(124, 58, 237, 0.4);
        }

        .manage-amenities-link {
            background: rgba(255,255,255,0.15);
            border: 2px solid rgba(255,255,255,0.3);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        .manage-amenities-link:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-2px);
            color: white;
        }

        /* Area Validation Warning Styles */
        .area-validation-warning {
            border-radius: 8px;
            padding: 10px 14px;
            margin-top: 8px;
            font-size: 0.8rem;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .area-validation-warning.alert-error {
            background: linear-gradient(135deg, #fef2f2, #fecaca);
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .area-validation-warning.alert-info {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            color: #075985;
            border: 1px solid #7dd3fc;
        }
        .area-validation-warning i {
            font-size: 1rem;
            margin-top: 2px;
            flex-shrink: 0;
        }
        .floor-area-validation-warning {
            border-radius: 8px;
            padding: 10px 14px;
            margin-top: 8px;
            font-size: 0.8rem;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .floor-area-validation-warning.alert-error {
            background: linear-gradient(135deg, #fef2f2, #fecaca);
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .floor-area-validation-warning.alert-info {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            color: #075985;
            border: 1px solid #7dd3fc;
        }
        .floor-area-validation-warning i {
            font-size: 1rem;
            margin-top: 2px;
            flex-shrink: 0;
        }
        input.area-error {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15) !important;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-layer-group"></i> Block & Floor Setup</h1>
                <p>Configure building blocks and floors for your campus</p>
            </div>
            <div style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">
                <a href="{{ route('institute.admin.amenities.management') }}" class="manage-amenities-link">
                    <i class="fas fa-cogs"></i> Manage Amenities
                </a>
                <a href="{{ route('campus.infrastructure', ['campus' => $campus->fincap_merchant_id ?? $campus->id ?? '']) }}" class="btn btn-secondary" style="background:rgba(255,255,255,0.2);color:white;border:2px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-left"></i> Back to Campus Setup
                </a>
                <a href="{{ route('buildings.list') }}" class="btn btn-secondary" style="background:rgba(255,255,255,0.2);color:white;border:2px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-university"></i> View Campuses
                </a>
                <a href="{{ route('rooms.page') }}" class="btn btn-success" style="background:rgba(255,255,255,0.2);color:white;border:2px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-door-open"></i> Manage Rooms
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
        </div>

        <!-- ==================== STEP 2: BUILDING BLOCKS ==================== -->
        <div class="step-content active" id="step2Content">
            <div class="form-card">
                <h2><i class="fas fa-cubes"></i> Step 1: Building Blocks</h2>
                
                <!-- Building Display (Auto-selected) -->
                <div class="form-group">
                    <label class="form-label">Building</label>
                    <div class="form-control" style="background:#f8fafc; display:flex; align-items:center; gap:10px; font-weight:600; color:var(--text-dark); border:2px solid var(--border-color); padding:12px 16px; border-radius:12px;">
                        <i class="fas fa-building" style="color:var(--primary-color);"></i>
                        <span id="selectedBuildingName">
                            @if(isset($buildings) && count($buildings) > 0)
                                {{ $buildings->first()->name }}
                                @if(!empty($buildings->first()->code))
                                    ({{ $buildings->first()->code }})
                                @endif
                            @else
                                No building found
                            @endif
                        </span>
                    </div>
                    
                    <!-- Hidden input for JavaScript to use -->
                    <input type="hidden" id="buildingId" value="{{ isset($buildings) && count($buildings) > 0 ? $buildings->first()->id : '' }}">
                    
                    @if(!isset($buildings) || count($buildings) == 0)
                        <div class="alert-error" style="margin-top:10px;">
                            <i class="fas fa-exclamation-circle"></i>
                            <div>
                                <strong>No buildings found!</strong>
                                <p style="margin:5px 0 0 0;font-size:0.85rem;">
                                    Please save Campus Infrastructure in Step 1 first.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Campus Info Display -->
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

                <!-- Block Tabs System -->
                <div id="blockTabsSection" style="display:none;">
                    <div class="tabs-container">
                        <!-- Tab Headers -->
                        <div class="tabs" id="blockTabs">
                            <!-- Dynamically generated -->
                        </div>
                        
                        <!-- Tab Contents -->
                        <div id="blockContents">
                            <!-- Dynamically generated -->
                        </div>

                        <!-- Add Block Button -->
                        <div class="add-btn-area" id="addBlockArea">
                            <button type="button" class="btn-outline-primary" onclick="addNewBlockTab()" id="addBlockBtn">
                                <i class="fas fa-plus"></i> Add New Block
                            </button>
                            <span style="font-size:0.8rem;color:var(--text-muted);align-self:center;">
                                <i class="fas fa-info-circle"></i> Total blocks: <strong id="totalBlocksCount">0</strong>
                                <span id="maxBlocksIndicator" style="font-size:0.7rem;color:var(--text-muted);margin-left:5px;"></span>
                            </span>
                        </div>
                    </div>

                    <!-- Global Allocation Summary -->
                    <div id="globalAllocSummary" class="allocation-tracker" style="display:none;margin-top:1.5rem;">
                        <div class="allocation-header">
                            <h4><i class="fas fa-chart-bar"></i> Campus Resource Allocation</h4>
                            <span class="remaining-tag available" id="globalAllocTag"><i class="fas fa-check-circle"></i> Resources Available</span>
                        </div>
                        <div id="globalAllocItems" style="display:flex;flex-wrap:wrap;gap:8px;"></div>
                        <div class="allocation-summary" style="display:flex;flex-wrap:wrap;gap:20px;margin-top:8px;padding-top:8px;border-top:1px dashed var(--border-color);">
                            <span class="stat-item"><i class="fas fa-cubes"></i> Total Blocks: <strong id="globalBlockCount">0</strong></span>
                            <span class="stat-item"><i class="fas fa-check-circle" style="color:#10b981;"></i> Allocated: <strong id="globalAllocatedCount">0</strong></span>
                            <span class="stat-item"><i class="fas fa-clock" style="color:#f59e0b;"></i> Remaining: <strong id="globalRemainingCount">0</strong></span>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- AMENITIES REDIRECT CARD (Step 2)             -->
                    <!-- ============================================ -->
                    <div class="amenities-redirect-card">
                        <div class="left-content">
                            <div class="icon">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <div class="text-content">
                                <h5>Manage Campus Amenities</h5>
                                <p>Configure counts, create units, and manage specifications</p>
                            </div>
                        </div>
                        <a href="{{ route('institute.admin.amenities.management') }}" class="btn-purple">
                            <i class="fas fa-arrow-right me-2"></i>Go to Amenities
                        </a>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions" style="opacity:0.5;pointer-events:none;">
                        <button type="button" class="btn btn-success" id="saveAllBlocksBtn" disabled>
                            <i class="fas fa-save"></i> Save All Blocks (Disabled)
                        </button>
                        <span style="font-size:0.8rem;color:var(--text-muted);align-self:center;">
                            <i class="fas fa-info-circle"></i> Save each block individually using the "Save Block" button
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== STEP 3: FLOORS ==================== -->
        <div class="step-content" id="step3Content">
            <div class="form-card">
                <h2><i class="fas fa-layer-group"></i> Step 2: Add Floors</h2>

                <!-- Building Display (Auto-selected) -->
                <div class="form-group">
                    <label class="form-label">Building</label>
                    <div class="form-control" style="background:#f8fafc; display:flex; align-items:center; gap:10px; font-weight:600; color:var(--text-dark); border:2px solid var(--border-color); padding:12px 16px; border-radius:12px;">
                        <i class="fas fa-building" style="color:var(--primary-color);"></i>
                        <span id="floorBuildingName">
                            @if(isset($buildings) && count($buildings) > 0)
                                {{ $buildings->first()->name }}
                                @if(!empty($buildings->first()->code))
                                    ({{ $buildings->first()->code }})
                                @endif
                            @else
                                No building found
                            @endif
                        </span>
                    </div>
                    
                    <!-- Hidden input for JavaScript -->
                    <input type="hidden" id="floorBuildingId" value="{{ isset($buildings) && count($buildings) > 0 ? $buildings->first()->id : '' }}">
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
                    <div id="noExistingFloors" class="no-items-message" style="display:none;"><i class="fas fa-info-circle"></i> No existing floors found.</div>
                </div>

                <!-- Floor Tabs System -->
                <div id="floorTabsSection" style="display:none;">
                    <div class="tabs-container">
                        <!-- Tab Headers -->
                        <div class="tabs" id="floorTabs">
                            <!-- Dynamically generated -->
                        </div>
                        
                        <!-- Tab Contents -->
                        <div id="floorContents">
                            <!-- Dynamically generated -->
                        </div>

                        <!-- Add Floor Button -->
                        <div class="add-btn-area" id="addFloorArea">
                            <button type="button" class="btn-outline-primary" onclick="addNewFloorTab()">
                                <i class="fas fa-plus"></i> Add New Floor
                            </button>
                            <span style="font-size:0.8rem;color:var(--text-muted);align-self:center;">
                                <i class="fas fa-info-circle"></i> Total floors: <strong id="totalFloorsCount">0</strong>
                            </span>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- AMENITIES REDIRECT CARD (Step 3 - Floor)    -->
                    <!-- ============================================ -->
                    <div class="amenities-redirect-card" style="margin-top:1rem;">
                        <div class="left-content">
                            <div class="icon">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <div class="text-content">
                                <h5>Manage Campus Amenities</h5>
                                <p>Configure counts, create units, and manage specifications for floors</p>
                            </div>
                        </div>
                        <a href="{{ route('institute.admin.amenities.management') }}" class="btn-purple">
                            <i class="fas fa-arrow-right me-2"></i>Go to Amenities
                        </a>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions" style="opacity:0.5;pointer-events:none;">
                        <button type="button" class="btn btn-secondary" onclick="goToStep(2)"><i class="fas fa-arrow-left"></i> Back to Blocks</button>
                        <button type="button" class="btn btn-success" id="saveAllFloorsBtn" disabled>
                            <i class="fas fa-save"></i> Save All Floors (Disabled)
                        </button>
                        <span style="font-size:0.8rem;color:var(--text-muted);align-self:center;">
                            <i class="fas fa-info-circle"></i> Save each floor individually using the "Save Floor" button
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"><i class="fas fa-check-circle"></i><span id="toast-message"></span></div>

    <!-- Modal for navigation confirmation -->
    <div id="navigationModal" class="modal-overlay" style="display:none;">
        <div class="modal-box">
            <h3><i class="fas fa-check-circle" style="color:#10b981;"></i> All Blocks Saved!</h3>
            <p>You have successfully saved all blocks. Would you like to proceed to the "Add Floors" section?</p>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeModal()">Stay Here</button>
                <button class="btn btn-success" onclick="proceedToFloors()"><i class="fas fa-arrow-right"></i> Proceed to Floors</button>
            </div>
        </div>
    </div>

    <!-- Modal for unsaved blocks warning -->
    <div id="unsavedModal" class="modal-overlay" style="display:none;">
        <div class="modal-box">
            <h3><i class="fas fa-exclamation-triangle" style="color:#dc3545;"></i> Unsaved Blocks</h3>
            <p>The following blocks have not been saved yet. Please save them before proceeding:</p>
            <ul id="unsavedBlocksList" style="color:var(--text-dark);margin-bottom:1rem;padding-left:1.5rem;"></ul>
            <div class="modal-actions">
                <button class="btn btn-primary" onclick="closeUnsavedModal()"><i class="fas fa-check"></i> OK, I'll Save</button>
            </div>
        </div>
    </div>

<script>
    // ============================================================
    // ===== COMPLETE JAVASCRIPT =====
    // ============================================================

    @php
        $campusData = null;
        if (isset($campus)) {
            $blocksData = [];
            if (isset($blocks) && count($blocks) > 0) {
                $blocksData = $blocks->toArray();
            }
           
            $amenitiesData = [];
            if (isset($amenities)) {
                foreach ($amenities as $amenity) {
                    $amenitiesData[$amenity['name']] = [
                        'id' => $amenity['id'] ?? null,
                        'amenity_id' => $amenity['amenity_id'] ?? null,
                        'name' => $amenity['name'],
                        'category' => $amenity['category'] ?? 'general',
                        'total_units' => $amenity['unit_count'] ?? 0,
                        'count' => $amenity['count'] ?? 0,
                    ];
                }
            }
           
            $campusData = [
                'id' => $campus->fincap_merchant_id,
                'name' => $campus->name,
                'code' => $campus->fincap_merchant_id,
                'area_value' => $campus->area_value ?? 0,
                'area_unit' => $campus->area_unit ?? 'sq_ft',
                'number_of_blocks' => $number_of_blocks ?? 1,
                'blocks' => $blocksData,
                'additional_areas' => $campus->additional_areas ?? [],
                'gates' => $campus->gates ?? [],
                'amenities' => $amenitiesData,
                'custom_amenities' => $campus->custom_amenities ?? [],
                'facilities' => $campus->facilities ?? [],
                'allocated_amenities' => $campus->allocated_amenities ?? [],
            ];
        }
    @endphp
    const CAMPUS_DATA = @json($campusData);

    const API_BASE_URL = '{{ url('/') }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    const ROOMS_PAGE_URL = '{{ route('rooms.page') }}';
    let currentStep = 2;

    // Multi‑entry facility counters
    let areaCounter = 1, gateCounter = 1, customAmenityCounter = 1;
    let campusAreaUnit = 'sq_ft', configuredNumberOfBlocks = 1;
    let campusFacilities = { hasWarehouse: false, hasStore: false, hasHostel: false };
    let blockAreaCounters = {}, blockGateCounters = {}, blockCustomAmenityCounters = {};
    let buildingData = null;
    let originalCampusAreaValue = 0, originalCampusAreaUnit = 'sq_ft', blockDisplayUnits = {};
    let floorAreaCounters = {}, floorGateCounters = {}, floorCustomAmenityCounters = {};
    let currentBlockForFloors = null;

    // Block allocation tracking
    let blockAllocations = {};
    let campusAmenityTotals = {};
    let campusCustomAmenityTotals = {};
    let campusFacilityEntries = {};
    let blockIdCounter = 0;

    // Block tab tracking
    let blockTabData = {};
    let nextBlockTabId = 1;
    let activeBlockTabId = null;
    let blockDataMap = {};
    let existingBlockIds = [];
    let isSavingBlock = false;

    // Floor tab tracking
    let floorTabData = {};
    let nextFloorTabId = 1;
    let activeFloorTabId = null;
    let floorDataMap = {};
    let existingFloorIds = [];
    let isSavingFloor = false;
    let floorBuildingId = null;
    let floorBlockId = null;

    // ============================================================
    // ===== AMENITY CATEGORY HELPERS =====
    // ============================================================
    function getAmenityCategory(key) {
        if (campusAmenityTotals[key + '_category']) {
            return campusAmenityTotals[key + '_category'];
        }
        
        if (['wifi', 'internet_lab', 'lan', 'projector', 'smart_board'].includes(key)) return 'technology';
        else if (['cctv', 'biometric', 'security_guard', 'fire_alarm', 'fire_extinguisher'].includes(key)) return 'security';
        else if (['elevator', 'escalator', 'ramp', 'disabled_access'].includes(key)) return 'accessibility';
        else if (['ac', 'heater', 'exhaust_fan', 'ups', 'solar_panel', 'generator'].includes(key)) return 'utilities';
        else if (['washroom', 'drinking_water', 'water_cooler'].includes(key)) return 'hygiene';
        else if (['cafeteria', 'tuck_shop', 'canteen'].includes(key)) return 'food';
        else if (['library', 'computer_lab', 'seminar_hall', 'conference_room', 'classroom'].includes(key)) return 'education';
        else if (['gym', 'playground', 'swimming_pool', 'sports'].includes(key)) return 'recreation';
        else if (['medical_room', 'ambulance', 'first_aid'].includes(key)) return 'medical';
        else if (['atm', 'stationery_shop', 'photocopy', 'laundry', 'salon'].includes(key)) return 'services';
        else if (['prayer_room', 'daycare', 'guest_room', 'staff_room', 'admin_office', 'hostel'].includes(key)) return 'accommodation';
        
        return 'general';
    }

    function getCategoryIcon(category) {
        const icons = {
            'technology': 'fa-microchip',
            'security': 'fa-shield-alt',
            'accessibility': 'fa-wheelchair',
            'utilities': 'fa-bolt',
            'hygiene': 'fa-hand-sparkles',
            'food': 'fa-utensils',
            'education': 'fa-graduation-cap',
            'recreation': 'fa-gamepad',
            'medical': 'fa-heartbeat',
            'services': 'fa-concierge-bell',
            'accommodation': 'fa-bed',
            'general': 'fa-folder'
        };
        return icons[category] || 'fa-folder';
    }

    function getCategoryDisplayName(category) {
        const names = {
            'technology': 'Technology & Connectivity',
            'security': 'Security & Safety',
            'accessibility': 'Accessibility',
            'utilities': 'Utilities & Power',
            'hygiene': 'Hygiene & Sanitation',
            'food': 'Food & Dining',
            'education': 'Education & Learning',
            'recreation': 'Recreation & Sports',
            'medical': 'Medical & Health',
            'services': 'Services & Amenities',
            'accommodation': 'Accommodation',
            'general': 'General'
        };
        return names[category] || 'General';
    }

    function getCategoryBadgeClass(category) {
        return 'amenity-category-badge ' + (category || 'general');
    }

    // ============================================================
    // ===== LOCALSTORAGE HELPER FUNCTIONS =====
    // ============================================================

    function getLockedBlocksKey() {
        var buildingId = document.getElementById('buildingId').value;
        if (!buildingId) return null;
        return 'locked_blocks_' + buildingId;
    }

    function saveLockedBlockToStorage(blockId, blockData) {
        var key = getLockedBlocksKey();
        if (!key) return;
        
        try {
            var lockedBlocks = JSON.parse(localStorage.getItem(key) || '{}');
            lockedBlocks[blockId] = {
                locked: true,
                savedAt: new Date().toISOString(),
                blockName: blockData.name || 'Block',
                blockData: blockData
            };
            localStorage.setItem(key, JSON.stringify(lockedBlocks));
        } catch (e) {
            console.warn('Could not save to localStorage:', e);
        }
    }

    function getLockedBlocksFromStorage() {
        var key = getLockedBlocksKey();
        if (!key) return {};
        
        try {
            return JSON.parse(localStorage.getItem(key) || '{}');
        } catch (e) {
            return {};
        }
    }

    function isBlockLockedInStorage(blockId) {
        var lockedBlocks = getLockedBlocksFromStorage();
        return lockedBlocks[blockId] && lockedBlocks[blockId].locked === true;
    }

    function removeBlockFromStorage(blockId) {
        var key = getLockedBlocksKey();
        if (!key) return;
        
        try {
            var lockedBlocks = JSON.parse(localStorage.getItem(key) || '{}');
            delete lockedBlocks[blockId];
            localStorage.setItem(key, JSON.stringify(lockedBlocks));
        } catch (e) {
            console.warn('Could not remove from localStorage:', e);
        }
    }

    function clearLockedBlocksStorage() {
        var key = getLockedBlocksKey();
        if (!key) return;
        
        try {
            localStorage.removeItem(key);
        } catch (e) {
            console.warn('Could not clear localStorage:', e);
        }
    }

    // ============================================================
    // ===== UTILITY FUNCTIONS =====
    // ============================================================

    function generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0;
            var v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    function escapeHtml(t) {
        if (!t) return '';
        var m = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(t).replace(/[&<>"']/g, function(c) { return m[c]; });
    }

    function parseJsonSafe(s) {
        if (!s) return null;
        if (typeof s === 'object') return s;
        try { return JSON.parse(s); } catch(e) { return null; }
    }

    function showToast(msg, type) {
        if (type === undefined) type = 'success';
        var t = document.getElementById('toast');
        document.getElementById('toast-message').textContent = msg;
        t.className = 'toast' + (type === 'error' ? ' error' : (type === 'info' ? ' info' : ''));
        t.querySelector('i').className = type === 'error' ? 'fas fa-exclamation-circle' : (type === 'info' ? 'fas fa-info-circle' : 'fas fa-check-circle');
        t.classList.add('show');
        setTimeout(function() { t.classList.remove('show'); }, 3000);
    }

    function toggleFacilityGroup(header) {
        var body = header.nextElementSibling;
        var toggleIcon = header.querySelector('.group-toggle');
        if (body) {
            body.classList.toggle('open');
            if (toggleIcon) toggleIcon.classList.toggle('open');
        }
    }

    // ============================================================
    // ===== AREA CONVERSION FUNCTIONS =====
    // ============================================================

    var areaConversionToSqFt = {
        'sq_ft': 1,
        'sq_m': 10.7639,
        'sq_yd': 9,
        'gaj': 9,
        'marla': 272.25,
        'kanal': 5445,
        'acre': 43560,
        'hectare': 107639,
        'bigha': 27000,
        'biswa': 1350
    };

    function convertArea(value, fromUnit, toUnit) {
        if (!value || isNaN(value)) return 0;
        return (parseFloat(value) * (areaConversionToSqFt[fromUnit] || 1)) / (areaConversionToSqFt[toUnit] || 1);
    }

    function getUnitDisplayName(unit) {
        var n = {
            'sq_ft': 'Sq. Ft.',
            'sq_m': 'Sq. M.',
            'sq_yd': 'Sq. Yd.',
            'gaj': 'Gaj',
            'marla': 'Marla',
            'kanal': 'Kanal',
            'acre': 'Acre',
            'hectare': 'Hectare',
            'bigha': 'Bigha',
            'biswa': 'Biswa'
        };
        return n[unit] || unit;
    }

    function formatUnit(unit) {
        return getUnitDisplayName(unit);
    }

    function generateUnitOptions(selected) {
        var u = ['sq_ft', 'sq_m', 'sq_yd', 'gaj', 'marla', 'kanal', 'acre', 'hectare', 'bigha', 'biswa'];
        var l = {
            'sq_ft': 'Sq. Ft.',
            'sq_m': 'Sq. M.',
            'sq_yd': 'Sq. Yd.',
            'gaj': 'Gaj',
            'marla': 'Marla',
            'kanal': 'Kanal',
            'acre': 'Acre',
            'hectare': 'Hectare',
            'bigha': 'Bigha',
            'biswa': 'Biswa'
        };
        var html = '';
        for (var i = 0; i < u.length; i++) {
            html += '<option value="' + u[i] + '" ' + (u[i] === selected ? 'selected' : '') + '>' + l[u[i]] + '</option>';
        }
        return html;
    }

    // ============================================================
    // ===== MODAL FUNCTIONS =====
    // ============================================================

    function showModal() {
        document.getElementById('navigationModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('navigationModal').style.display = 'none';
    }

    function showUnsavedModal(unsavedBlocks) {
        var list = document.getElementById('unsavedBlocksList');
        list.innerHTML = '';
        for (var i = 0; i < unsavedBlocks.length; i++) {
            var li = document.createElement('li');
            li.textContent = 'Block ' + (i + 1) + ': "' + unsavedBlocks[i] + '"';
            list.appendChild(li);
        }
        document.getElementById('unsavedModal').style.display = 'flex';
    }

    function closeUnsavedModal() {
        document.getElementById('unsavedModal').style.display = 'none';
    }

    function proceedToFloors() {
        closeModal();
        goToStep(3);
    }

    // ============================================================
    // ===== STEP NAVIGATION =====
    // ============================================================

    function goToStep(step) {
        if (step === 2 && activeBlockTabId) {
            saveBlockTabData(activeBlockTabId);
        } else if (step === 3 && activeFloorTabId) {
            saveFloorTabData(activeFloorTabId);
        }

        currentStep = step;
        var contents = document.querySelectorAll('.step-content');
        for (var i = 0; i < contents.length; i++) {
            contents[i].classList.remove('active');
        }
        var content = document.getElementById('step' + step + 'Content');
        if (content) content.classList.add('active');

        var circles = document.querySelectorAll('.step-circle, .step-label, .step-connector');
        for (var j = 0; j < circles.length; j++) {
            circles[j].classList.remove('active', 'active-label', 'completed', 'completed-label');
        }

        if (step >= 2) {
            var circle2 = document.getElementById('stepCircle2');
            var label2 = document.getElementById('stepLabel2');
            if (circle2) circle2.classList.add(step === 2 ? 'active' : 'completed');
            if (label2) label2.classList.add(step === 2 ? 'active-label' : 'completed-label');
        }
        if (step >= 3) {
            var circle3 = document.getElementById('stepCircle3');
            var label3 = document.getElementById('stepLabel3');
            var connector2 = document.getElementById('stepConnector2');
            if (circle3) circle3.classList.add(step === 3 ? 'active' : 'completed');
            if (label3) label3.classList.add(step === 3 ? 'active-label' : 'completed-label');
            if (connector2) connector2.classList.add('completed');
            
            if (step === 3) {
                var buildingId = document.getElementById('buildingId').value;
                if (buildingId) {
                    document.getElementById('floorBuildingId').value = buildingId;
                    setTimeout(function() {
                        loadBlocksForFloors();
                    }, 100);
                }
            }
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ============================================================
    // ===== BUILDING DATA LOADING =====
    // ============================================================

    async function loadBuildingData() {
        var bi = document.getElementById('buildingId').value;
        if (!bi) {
            return;
        }
        
        document.getElementById('campusInfoDisplay').style.display = 'block';
        document.getElementById('displayCampusName').textContent = 'Loading...';
        document.getElementById('displayCampusCode').textContent = 'Loading...';
        
        try {
            var url = '/buildings/' + bi;
            
            var buildingResponse = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            
            if (!buildingResponse.ok) {
                var errorText = await buildingResponse.text();
                console.error('Error response:', errorText);
                throw new Error('HTTP error! status: ' + buildingResponse.status);
            }
            
            var buildingResult = await buildingResponse.json();
            
            if (!buildingResult.success || !buildingResult.data) {
                showToast(buildingResult.message || 'Failed to load building data', 'error');
                return;
            }
            
            var building = buildingResult.data;
            
            configuredNumberOfBlocks = building.number_of_blocks || 1;
            originalCampusAreaValue = parseFloat(building.area_value) || 0;
            originalCampusAreaUnit = building.area_unit || 'sq_ft';
            campusAreaUnit = originalCampusAreaUnit;

            document.getElementById('displayCampusName').textContent = building.name || '-';
            document.getElementById('displayCampusCode').textContent = building.code || '-';
            document.getElementById('displayTotalArea').textContent = originalCampusAreaValue + ' ' + formatUnit(originalCampusAreaUnit);

            buildingData = building;

            initCampusTotals(building);

            document.getElementById('campusInfoDisplay').style.display = 'block';
            document.getElementById('blockTabsSection').style.display = 'block';

            initBlockTabs();
            updateMaxBlocksIndicator();
            updateRemainingArea();
            updateGlobalAllocationSummary();
            showToast('Building data loaded successfully!', 'success');
        
        } catch (e) {
            console.error('Error loading building data:', e);
            showToast('Error loading building data: ' + (e.message || 'Unknown error'), 'error');
            document.getElementById('campusInfoDisplay').style.display = 'none';
            document.getElementById('blockTabsSection').style.display = 'none';
            
            if (typeof CAMPUS_DATA !== 'undefined' && CAMPUS_DATA && CAMPUS_DATA.id) {
                buildingData = CAMPUS_DATA;
                configuredNumberOfBlocks = buildingData.number_of_blocks || 1;
                originalCampusAreaValue = parseFloat(buildingData.area_value) || 0;
                originalCampusAreaUnit = buildingData.area_unit || 'sq_ft';
                campusAreaUnit = originalCampusAreaUnit;
                document.getElementById('displayCampusName').textContent = buildingData.name || '-';
                document.getElementById('displayCampusCode').textContent = buildingData.code || '-';
                document.getElementById('displayTotalArea').textContent = originalCampusAreaValue + ' ' + formatUnit(originalCampusAreaUnit);
                initCampusTotals(buildingData);
                document.getElementById('campusInfoDisplay').style.display = 'block';
                document.getElementById('blockTabsSection').style.display = 'block';
                initBlockTabs();
                updateMaxBlocksIndicator();
                updateRemainingArea();
                updateGlobalAllocationSummary();
                showToast('Building data loaded from fallback.', 'info');
            }
        }
    }

    // ============================================================
    // ===== INIT CAMPUS TOTALS =====
    // ============================================================

    function initCampusTotals(data) {
        campusAmenityTotals = {};
        campusCustomAmenityTotals = {};
        campusFacilityEntries = {};
        
        if (data.amenities && typeof data.amenities === 'object') {
            var amenityKeys = Object.keys(data.amenities);
            for (var i = 0; i < amenityKeys.length; i++) {
                var key = amenityKeys[i];
                var amenity = data.amenities[key];
                
                if (amenity && typeof amenity === 'object') {
                    campusAmenityTotals[key] = amenity.total_units || 0;
                    campusAmenityTotals[key + '_id'] = amenity.amenity_id || amenity.id;
                    if (amenity.category) {
                        campusAmenityTotals[key + '_category'] = amenity.category;
                    }
                }
            }
        }
        
        if (data.allocated_amenities && typeof data.allocated_amenities === 'object') {
            var allocKeys = Object.keys(data.allocated_amenities);
            for (var j = 0; j < allocKeys.length; j++) {
                var key = allocKeys[j];
                var value = data.allocated_amenities[key];
                if (!campusAmenityTotals[key]) {
                    campusAmenityTotals[key] = parseInt(value) || 0;
                }
            }
        }
        
        var customAmenities = data.custom_amenities || [];
        if (typeof customAmenities === 'string') {
            try { customAmenities = JSON.parse(customAmenities); } catch(e) { customAmenities = []; }
        }
        if (Array.isArray(customAmenities)) {
            for (var ci = 0; ci < customAmenities.length; ci++) {
                var item = customAmenities[ci];
                if (item && item.name) {
                    var key = 'custom_' + (item.id || ci);
                    campusCustomAmenityTotals[key] = {
                        id: item.id || generateUUID(),
                        name: item.name,
                        total: parseInt(item.quantity) || 0
                    };
                }
            }
        }
        
        var facilities = data.facilities || {};
        if (typeof facilities === 'string') {
            try { facilities = JSON.parse(facilities); } catch(e) { facilities = {}; }
        }
        
        var facilityTypes = ['parking', 'playground', 'swimming_pool', 'clubhouse', 'warehouse', 'store_room', 'auditorium', 'washrooms'];
        for (var ft = 0; ft < facilityTypes.length; ft++) {
            var type = facilityTypes[ft];
            var displayName = type.replace(/_/g, ' ').replace(/\b\w/g, function(l) { return l.toUpperCase(); });
            
            if (facilities[type] && facilities[type].enabled !== false) {
                var entries = [];
                
                if (type === 'clubhouse') {
                    if (facilities[type].id) {
                        entries.push({
                            id: facilities[type].id,
                            name: facilities[type].name || displayName,
                            area: facilities[type].area || 0,
                            capacity: facilities[type].capacity || 0
                        });
                    }
                } else if (type === 'washrooms') {
                    if (Array.isArray(facilities[type])) {
                        entries = facilities[type];
                    } else if (facilities[type].detailed_washrooms) {
                        entries = facilities[type].detailed_washrooms;
                    } else if (facilities[type].entries) {
                        entries = facilities[type].entries;
                    }
                } else {
                    var nestedKeys = ['open', 'basement', 'indoor', 'outdoor', 'entries'];
                    for (var nk = 0; nk < nestedKeys.length; nk++) {
                        var nestedKey = nestedKeys[nk];
                        if (facilities[type][nestedKey] && Array.isArray(facilities[type][nestedKey])) {
                            for (var ei = 0; ei < facilities[type][nestedKey].length; ei++) {
                                var entry = facilities[type][nestedKey][ei];
                                if (entry && entry.id) {
                                    entry._facilityType = type;
                                    entry._facilityDisplayName = displayName;
                                    if (!entry.name) {
                                        entry.name = displayName + ' (' + nestedKey + ')';
                                    }
                                    entries.push(entry);
                                }
                            }
                        }
                    }
                    
                    if (entries.length === 0 && facilities[type].entries && Array.isArray(facilities[type].entries)) {
                        for (var ei2 = 0; ei2 < facilities[type].entries.length; ei2++) {
                            var entry2 = facilities[type].entries[ei2];
                            if (entry2 && entry2.id) {
                                entry2._facilityType = type;
                                entry2._facilityDisplayName = displayName;
                                entries.push(entry2);
                            }
                        }
                    }
                }
                
                if (entries.length > 0) {
                    campusFacilityEntries[type] = entries.map(function(entry) {
                        if (!entry.id) entry.id = generateUUID();
                        return entry;
                    });
                }
            }
        }
        
        blockAllocations = {};
        if (data.blocks && Array.isArray(data.blocks)) {
            for (var bi = 0; bi < data.blocks.length; bi++) {
                var block = data.blocks[bi];
                var bid = block.id;
                if (!blockAllocations[bid]) blockAllocations[bid] = {};
                if (block.allocated_amenities) {
                    var keys = Object.keys(block.allocated_amenities);
                    for (var ki = 0; ki < keys.length; ki++) {
                        var k = keys[ki];
                        blockAllocations[bid][k] = { allocated: true, quantity: block.allocated_amenities[k] };
                    }
                }
                if (block.allocated_facility_entries && Array.isArray(block.allocated_facility_entries)) {
                    for (var fi = 0; fi < block.allocated_facility_entries.length; fi++) {
                        var entryId = block.allocated_facility_entries[fi];
                        blockAllocations[bid][entryId] = { allocated: true, quantity: 1 };
                    }
                }
            }
        }
        
        updateGlobalAllocationSummary();
    }

    // ============================================================
    // ===== REMAINING AREA FUNCTIONS (ENHANCED) =====
    // ============================================================

    function getTotalBlockAreaInOriginalUnit() {
        var t = 0;
        var keys = Object.keys(blockDataMap);
        for (var i = 0; i < keys.length; i++) {
            var bid = keys[i];
            var block = blockDataMap[bid];
            if (block && block.area_value !== undefined && block.area_value !== null && block.area_value !== '') {
                t += convertArea(parseFloat(block.area_value) || 0, block.area_unit || originalCampusAreaUnit, originalCampusAreaUnit);
            }
        }
        return t;
    }

    function updateRemainingArea() {
        if (!buildingData) return;
        
        var totalBlockArea = getTotalBlockAreaInOriginalUnit();
        var remaining = originalCampusAreaValue - totalBlockArea;
        
        var remainingEl = document.getElementById('displayRemainingArea');
        if (remainingEl) {
            remainingEl.textContent = remaining.toFixed(2) + ' ' + formatUnit(originalCampusAreaUnit);
        }
        
        var badge = document.getElementById('remainingBadge');
        if (badge) {
            if (remaining < -0.01) {
                badge.className = 'exceeded-badge';
                badge.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Exceeded: <strong>' + Math.abs(remaining).toFixed(2) + ' ' + formatUnit(originalCampusAreaUnit) + '</strong>';
            } else {
                badge.className = 'remaining-badge';
                badge.innerHTML = '<i class="fas fa-chart-pie"></i> Remaining: <strong>' + remaining.toFixed(2) + ' ' + formatUnit(originalCampusAreaUnit) + '</strong>';
            }
        }
        
        return remaining;
    }

    // ============================================================
    // ===== VALIDATE BLOCK AREA AGAINST CAMPUS TOTAL =====
    // ============================================================

    function validateBlockArea(bid, newAreaValue, newAreaUnit) {
        if (!buildingData) return { valid: true, message: '' };
        
        var newAreaInOriginal = convertArea(newAreaValue, newAreaUnit, originalCampusAreaUnit);
        
        var otherBlocksArea = 0;
        var keys = Object.keys(blockDataMap);
        for (var i = 0; i < keys.length; i++) {
            var otherBid = keys[i];
            if (otherBid !== bid) {
                var block = blockDataMap[otherBid];
                if (block && block.area_value !== undefined && block.area_value !== null && block.area_value !== '') {
                    otherBlocksArea += convertArea(parseFloat(block.area_value) || 0, block.area_unit || originalCampusAreaUnit, originalCampusAreaUnit);
                }
            }
        }
        
        var remainingAfter = originalCampusAreaValue - otherBlocksArea - newAreaInOriginal;
        
        if (remainingAfter < -0.01) {
            var maxAllowed = originalCampusAreaValue - otherBlocksArea;
            var maxAllowedInUnit = convertArea(maxAllowed, originalCampusAreaUnit, newAreaUnit);
            
            return {
                valid: false,
                message: 'Block area exceeds campus limit! Maximum allowed: ' + maxAllowedInUnit.toFixed(2) + ' ' + formatUnit(newAreaUnit) + ' (Campus total: ' + originalCampusAreaValue.toFixed(2) + ' ' + formatUnit(originalCampusAreaUnit) + ')',
                maxAllowed: maxAllowedInUnit
            };
        }
        
        return { valid: true, message: '', remaining: remainingAfter };
    }

    // ============================================================
    // ===== INIT BLOCK TABS =====
    // ============================================================

    function initBlockTabs() {
        var tabsContainer = document.getElementById('blockTabs');
        var contentsContainer = document.getElementById('blockContents');
        tabsContainer.innerHTML = '';
        contentsContainer.innerHTML = '';
        blockTabData = {};
        nextBlockTabId = 1;
        activeBlockTabId = null;

        var totalConfiguredBlocks = buildingData && buildingData.number_of_blocks ? parseInt(buildingData.number_of_blocks) : (configuredNumberOfBlocks || 1);
        var backendBlocks = buildingData && buildingData.blocks ? buildingData.blocks : [];

        blockDataMap = {};
        existingBlockIds = [];
        blockAllocations = {};
        blockDisplayUnits = {};

        var backendBlockIds = [];
        if (backendBlocks && backendBlocks.length > 0) {
            for (var b = 0; b < backendBlocks.length; b++) {
                var backendBlock = backendBlocks[b];
                if (backendBlock && backendBlock.id) {
                    var bid = backendBlock.id;
                    backendBlockIds.push(bid);
                    
                    var normalizedBlock = {
                        id: backendBlock.id,
                        name: backendBlock.name || 'Block ' + (b + 1),
                        code: backendBlock.code || '',
                        description: backendBlock.description || '',
                        status: backendBlock.status || 'active',
                        floors: backendBlock.total_floors || backendBlock.floors || 1,
                        area_value: backendBlock.total_area || backendBlock.area_value || 0,
                        area_unit: backendBlock.area_unit || 'sq_ft',
                        additional_areas: backendBlock.additional_areas || [],
                        gates: backendBlock.gates || [],
                        custom_amenities: backendBlock.custom_amenities || [],
                        allocated_amenities: backendBlock.allocated_amenities || {},
                        allocated_facility_entries: backendBlock.allocated_facilities || backendBlock.allocated_facility_entries || []
                    };
                    
                    blockDataMap[bid] = normalizedBlock;
                    if (existingBlockIds.indexOf(bid) === -1) {
                        existingBlockIds.push(bid);
                    }
                    blockDisplayUnits[bid] = normalizedBlock.area_unit || originalCampusAreaUnit;
                    if (!blockAllocations[bid]) blockAllocations[bid] = {};

                    var amenities = normalizedBlock.allocated_amenities;
                    if (typeof amenities === 'string') {
                        try { amenities = JSON.parse(amenities); } catch(e) { amenities = {}; }
                    }
                    if (amenities) {
                        var aKeys = Object.keys(amenities);
                        for (var i = 0; i < aKeys.length; i++) {
                            var key = aKeys[i];
                            blockAllocations[bid][key] = { allocated: true, quantity: amenities[key] };
                        }
                    }

                    var facilities = normalizedBlock.allocated_facility_entries;
                    if (typeof facilities === 'string') {
                        try { facilities = JSON.parse(facilities); } catch(e) { facilities = []; }
                    }
                    if (facilities && Array.isArray(facilities)) {
                        for (var j = 0; j < facilities.length; j++) {
                            var entryId = facilities[j];
                            blockAllocations[bid][entryId] = { allocated: true, quantity: 1 };
                        }
                    }
                }
            }
        }

        for (var c = 0; c < backendBlockIds.length; c++) {
            var bid = backendBlockIds[c];
            var block = blockDataMap[bid];
            
            var isLocked = true;
            var isSaved = true;
            
            var tabId = nextBlockTabId++;
            blockTabData[tabId] = {
                blockId: bid,
                blockData: block,
                isSaved: isSaved,
                isNew: false,
                isLocked: isLocked,
                tabElement: null,
                contentElement: null
            };
            createBlockTab(tabId);
        }

        var existingBlockCount = backendBlockIds.length;
        var numberOfNewBlocks = Math.max(0, totalConfiguredBlocks - existingBlockCount);
        
        if (numberOfNewBlocks > 0) {
            for (var e = 1; e <= numberOfNewBlocks; e++) {
                var tabId = nextBlockTabId++;
                var bid = 'new_' + Date.now() + '_' + e;
                var blockIndex = existingBlockCount + e;
                var blockData = {
                    id: bid,
                    name: 'Block ' + blockIndex,
                    code: buildingData && buildingData.code ? buildingData.code + '-BLK' + blockIndex : '',
                    description: '',
                    status: 'active',
                    floors: 1,
                    area_value: 0,
                    area_unit: originalCampusAreaUnit,
                    additional_areas: [],
                    gates: [],
                    custom_amenities: [],
                    allocated_amenities: {},
                    allocated_facility_entries: []
                };
                blockDataMap[bid] = blockData;
                blockDisplayUnits[bid] = originalCampusAreaUnit;
                if (!blockAllocations[bid]) blockAllocations[bid] = {};

                blockTabData[tabId] = {
                    blockId: bid,
                    blockData: blockData,
                    isSaved: false,
                    isNew: true,
                    isLocked: false,
                    tabElement: null,
                    contentElement: null
                };
                createBlockTab(tabId);
            }
        }

        if (Object.keys(blockTabData).length === 0) {
            var tabId = nextBlockTabId++;
            var bid = 'new_' + Date.now();
            var blockData = {
                id: bid,
                name: 'Block 1',
                code: buildingData && buildingData.code ? buildingData.code + '-BLK1' : '',
                description: '',
                status: 'active',
                floors: 1,
                area_value: 0,
                area_unit: originalCampusAreaUnit,
                additional_areas: [],
                gates: [],
                custom_amenities: [],
                allocated_amenities: {},
                allocated_facility_entries: []
            };
            blockDataMap[bid] = blockData;
            blockDisplayUnits[bid] = originalCampusAreaUnit;
            if (!blockAllocations[bid]) blockAllocations[bid] = {};

            blockTabData[tabId] = {
                blockId: bid,
                blockData: blockData,
                isSaved: false,
                isNew: true,
                isLocked: false,
                tabElement: null,
                contentElement: null
            };
            createBlockTab(tabId);
        }

        document.getElementById('totalBlocksCount').textContent = Object.keys(blockTabData).length;

        var firstUnsavedTab = null;
        var tabIds = Object.keys(blockTabData);
        for (var i = 0; i < tabIds.length; i++) {
            var tid = tabIds[i];
            if (!blockTabData[tid].isSaved) {
                firstUnsavedTab = tid;
                break;
            }
        }
        
        var firstTabId = firstUnsavedTab || (tabIds.length > 0 ? tabIds[0] : null);
        if (firstTabId) {
            setTimeout(function(tabId) {
                activateBlockTab(tabId);
            }, 200, firstTabId);
        }

        updateGlobalAllocationSummary();
        updateMaxBlocksIndicator();
    }

    // ============================================================
    // ===== CREATE BLOCK TAB =====
    // ============================================================

    function createBlockTab(tabId) {
        var tabData = blockTabData[tabId];
        if (!tabData) return;
        
        var block = tabData.blockData;
        var tabsContainer = document.getElementById('blockTabs');
        var contentsContainer = document.getElementById('blockContents');

        var tabEl = document.createElement('div');
        tabEl.className = 'tab';
        tabEl.dataset.tabId = tabId;
        
        var isLocked = tabData.isLocked || tabData.isSaved;
        
        if (isLocked) {
            tabEl.classList.add('locked');
            tabEl.style.cursor = 'not-allowed';
            tabEl.style.opacity = '0.8';
            tabEl.title = 'This block is already saved and locked';
            tabEl.style.pointerEvents = 'none';
        }
        
        var statusClass = isLocked ? 'locked-status' : (tabData.isSaved ? 'saved' : 'pending');
        var statusIcon = isLocked ? 'fa-lock' : (tabData.isSaved ? 'fa-check' : 'fa-clock');
        
        tabEl.innerHTML = `
            <span class="tab-status ${statusClass}">
                <i class="fas ${statusIcon}"></i>
            </span>
            <span class="tab-number">#${tabId}</span>
            <span>
                ${block.name || 'Block'}
                ${isLocked ? '<span class="tab-lock"> 🔒</span>' : ''}
            </span>
            ${!isLocked && tabData.isNew ? `
                <button class="tab-remove-btn" onclick="event.stopPropagation();removeBlockTab(${tabId})">
                    <i class="fas fa-times"></i>
                </button>
            ` : ''}
        `;
        
        if (isLocked) {
            tabEl.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                showToast('This block has already been saved and is locked.', 'info');
                return false;
            };
        } else {
            tabEl.onclick = function() {
                activateBlockTab(tabId);
            };
        }
        
        tabsContainer.appendChild(tabEl);
        tabData.tabElement = tabEl;

        var contentEl = document.createElement('div');
        contentEl.className = 'tab-content';
        contentEl.dataset.tabId = tabId;
        contentEl.id = 'blockContent-' + tabId;
        contentEl.innerHTML = buildBlockTabContent(tabId, block);
        contentsContainer.appendChild(contentEl);
        tabData.contentElement = contentEl;

        if (isLocked) {
            setTimeout(function() {
                lockBlockTab(tabId);
            }, 100);
        }
    }

    // ============================================================
    // ===== LOCK BLOCK TAB =====
    // ============================================================

    function lockBlockTab(tabId) {
        var tabData = blockTabData[tabId];
        if (!tabData) return;

        if (tabData.isLocked) {
            return;
        }

        tabData.isLocked = true;
        tabData.isSaved = true;
        tabData.isNew = false;

        saveLockedBlockToStorage(tabData.blockId, tabData.blockData);

        var tabElement = tabData.tabElement;
        if (tabElement) {
            tabElement.classList.add('locked');
            tabElement.style.cursor = 'not-allowed';
            tabElement.style.opacity = '0.8';
            tabElement.title = 'This block is saved and locked';
            
            var statusEl = tabElement.querySelector('.tab-status');
            if (statusEl) {
                statusEl.className = 'tab-status locked-status';
                statusEl.innerHTML = '<i class="fas fa-lock"></i>';
            }
            
            var nameSpan = tabElement.querySelector('span:last-child');
            if (nameSpan && !nameSpan.querySelector('.tab-lock')) {
                nameSpan.innerHTML = nameSpan.textContent + ' <span class="tab-lock">🔒</span>';
            }
            
            var removeBtn = tabElement.querySelector('.tab-remove-btn');
            if (removeBtn) {
                removeBtn.style.display = 'none';
            }
            
            tabElement.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                showToast('This block has been saved and locked. You cannot edit it.', 'info');
                return false;
            };
        }

        var contentEl = document.getElementById('blockContent-' + tabId);
        if (contentEl) {
            contentEl.classList.add('locked-content');
            
            var inputs = contentEl.querySelectorAll('input, select, textarea, button');
            for (var i = 0; i < inputs.length; i++) {
                var input = inputs[i];
                if (!input.classList.contains('tab-remove-btn')) {
                    input.disabled = true;
                    input.style.cursor = 'not-allowed';
                    input.style.opacity = '0.6';
                }
            }
            
            var accordionHeader = contentEl.querySelector('.accordion-header');
            if (accordionHeader) {
                accordionHeader.style.cursor = 'default';
                accordionHeader.style.pointerEvents = 'none';
                accordionHeader.onclick = null;
            }
            
            var accordion = contentEl.querySelector('.block-accordion');
            if (accordion) {
                accordion.classList.add('active');
            }

            var saveStatus = contentEl.querySelector('.save-status');
            if (saveStatus) {
                saveStatus.className = 'save-status locked-status';
                saveStatus.innerHTML = '<i class="fas fa-lock"></i><span>✓ Saved & Locked</span>';
            }
            
            var existingMsg = contentEl.querySelector('.block-locked-message');
            if (existingMsg) existingMsg.remove();
            
            var lockMessage = document.createElement('div');
            lockMessage.className = 'block-locked-message';
            lockMessage.innerHTML = '<i class="fas fa-check-circle" style="color:#10b981;"></i> This block has been saved and is locked. You cannot edit it.';
            
            var firstChild = contentEl.firstChild;
            if (firstChild) {
                contentEl.insertBefore(lockMessage, firstChild.nextSibling);
            } else {
                contentEl.appendChild(lockMessage);
            }
        }

        updateBlockTabStatus(tabId);
    }

    // ============================================================
    // ===== BUILD BLOCK TAB CONTENT =====
    // ============================================================

    function buildBlockTabContent(tabId, block) {
        var bid = block.id;
        var du = blockDisplayUnits[bid] || originalCampusAreaUnit;
        var dv = (typeof block.area_value === 'number' && !isNaN(block.area_value)) ? block.area_value : (parseFloat(block.area_value) || 0);

        var tabData = blockTabData[tabId];
        var isSaved = tabData.isSaved;
        var isLocked = tabData.isLocked;
        var statusClass = isLocked ? 'locked-status' : (isSaved ? 'saved' : 'pending');
        var statusIcon = isLocked ? 'fa-lock' : (isSaved ? 'fa-check-circle' : 'fa-clock');
        var statusText = isLocked ? '✓ Saved & Locked' : (isSaved ? '✓ Saved' : '⏳ Pending Save');

        var allocHtml = buildBlockAllocationHTML(bid);

        var areasHtml = '';
        var areas = block.additional_areas || [];
        if (areas.length === 0) {
            var areaId = generateUUID();
            areasHtml = '<div class="dynamic-card area-card" data-area-uuid="' + areaId + '">\n                <div class="card-badge-sm">Area #1</div>\n                <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;">\n                    <div class="form-group"><label>Area Name</label><input type="text" class="form-control area-name" placeholder="e.g., Playground"></div>\n                    <div class="form-group"><label>Area Unit</label><select class="form-control area-unit" onchange="handleBlockAreaUnitChange(this,' + bid + ')">' + generateUnitOptions(du) + '</select></div>\n                    <div class="form-group"><label>Area Value</label><input type="number" class="form-control area-value" placeholder="Enter area" min="0" step="0.01" onchange="updateBlockAreaSummary(\'' + tabId + '\')"></div>\n                </div>\n            </div>';
        } else {
            for (var ai = 0; ai < areas.length; ai++) {
                var area = areas[ai]; 
                var id = ai + 1;
                var areaId2 = area.id || generateUUID();
                areasHtml += '\n                    <div class="dynamic-card area-card" data-area-id="' + id + '" data-area-uuid="' + areaId2 + '">\n                        <div class="card-badge-sm">Area #' + id + '</div>\n                        <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;">\n                            <div class="form-group"><label>Area Name</label><input type="text" class="form-control area-name" value="' + escapeHtml(area.name || '') + '" placeholder="e.g., Playground"></div>\n                            <div class="form-group"><label>Area Unit</label><select class="form-control area-unit" onchange="handleBlockAreaUnitChange(this,' + bid + ')">' + generateUnitOptions(area.unit || du) + '</select></div>\n                            <div class="form-group"><label>Area Value</label><input type="number" class="form-control area-value" value="' + (area.area || '') + '" placeholder="Enter area" min="0" step="0.01" onchange="updateBlockAreaSummary(\'' + tabId + '\')"></div>\n                        </div>\n                        <div style="text-align:right;margin-top:0.5rem;">\n                            <button type="button" class="btn-outline-danger" onclick="this.closest(\'.dynamic-card\').remove();updateBlockAreaSummary(\'' + tabId + '\');"><i class="fas fa-trash"></i></button>\n                        </div>\n                    </div>';
            }
        }

        var gatesHtml = '';
        var gates = block.gates || [];
        if (gates.length === 0) {
            var gateId = generateUUID();
            gatesHtml = '<div class="dynamic-card gate-card" data-gate-uuid="' + gateId + '">\n                <div class="card-badge-sm">Gate #1</div>\n                <div class="card-row" style="grid-template-columns:1fr 1fr;">\n                    <div class="form-group"><label>Gate Name</label><input type="text" class="form-control gate-name" placeholder="e.g., Main Entrance"></div>\n                    <div class="form-group"><label>Gate Number</label><input type="text" class="form-control gate-number" placeholder="e.g., G-01"></div>\n                </div>\n            </div>';
        } else {
            for (var gi = 0; gi < gates.length; gi++) {
                var gate = gates[gi];
                var id2 = gi + 1;
                var gateId2 = gate.id || generateUUID();
                gatesHtml += '\n                    <div class="dynamic-card gate-card" data-gate-id="' + id2 + '" data-gate-uuid="' + gateId2 + '">\n                        <div class="card-badge-sm">Gate #' + id2 + '</div>\n                        <div class="card-row" style="grid-template-columns:1fr 1fr;">\n                            <div class="form-group"><label>Gate Name</label><input type="text" class="form-control gate-name" value="' + escapeHtml(gate.name || '') + '" placeholder="e.g., Main Entrance"></div>\n                            <div class="form-group"><label>Gate Number</label><input type="text" class="form-control gate-number" value="' + escapeHtml(gate.number || '') + '" placeholder="e.g., G-01"></div>\n                        </div>\n                        <div style="text-align:right;margin-top:0.5rem;">\n                            <button type="button" class="btn-outline-danger" onclick="this.closest(\'.dynamic-card\').remove();"><i class="fas fa-trash"></i></button>\n                        </div>\n                    </div>';
            }
        }

        var customHtml = '';
        var customAmenities = block.custom_amenities || [];
        if (customAmenities.length === 0) {
            var amenityId = generateUUID();
            customHtml = '<div class="custom-amenity-card" data-amenity-uuid="' + amenityId + '">\n                <div class="card-badge-sm">Custom #1</div>\n                <div class="card-row" style="grid-template-columns:2fr 1fr;">\n                    <div class="form-group"><label>Amenity Name</label><input type="text" class="form-control custom-amenity-name" placeholder="e.g., Projector"></div>\n                    <div class="form-group"><label>Quantity</label><input type="number" class="form-control custom-amenity-qty" value="1" min="1"></div>\n                </div>\n            </div>';
        } else {
            for (var ci = 0; ci < customAmenities.length; ci++) {
                var item = customAmenities[ci];
                var id3 = ci + 1;
                var amenityId2 = item.id || generateUUID();
                customHtml += '\n                    <div class="custom-amenity-card" data-custom-id="' + id3 + '" data-amenity-uuid="' + amenityId2 + '">\n                        <div class="card-badge-sm">Custom #' + id3 + '</div>\n                        <div class="card-row" style="grid-template-columns:2fr 1fr;">\n                            <div class="form-group"><label>Amenity Name</label><input type="text" class="form-control custom-amenity-name" value="' + escapeHtml(item.name || '') + '" placeholder="e.g., Projector"></div>\n                            <div class="form-group"><label>Quantity</label><input type="number" class="form-control custom-amenity-qty" value="' + (item.quantity || 1) + '" min="1"></div>\n                        </div>\n                        <div style="text-align:right;margin-top:0.5rem;">\n                            <button type="button" class="btn-outline-danger" onclick="this.closest(\'.custom-amenity-card\').remove()"><i class="fas fa-trash"></i></button>\n                        </div>\n                    </div>';
            }
        }

        var isHostel = block.name && block.name.toLowerCase().indexOf('hostel') !== -1;
        var hostelBadge = isHostel ? '<span style="background:var(--warning-gradient);padding:2px 10px;border-radius:12px;font-size:0.6rem;color:white;margin-left:8px;"><i class="fas fa-hotel"></i> Hostel</span>' : '';
        var removeBtn = !blockTabData[tabId].isNew ? '' : '<button type="button" class="btn-outline-danger" onclick="removeBlockTab(' + tabId + ')"><i class="fas fa-trash"></i> Remove Block</button>';
        var saveBtn = isLocked ? '' : '<button type="button" class="btn btn-success" onclick="saveBlockTab(' + tabId + ')" id="saveBlockBtn-' + tabId + '">\n                                <i class="fas fa-save"></i> Save Block\n                            </button>';

        return '\n            <div class="save-status ' + statusClass + '" id="blockSaveStatus-' + tabId + '">\n                <i class="fas ' + statusIcon + '"></i>\n                <span>' + statusText + '</span>\n            </div>\n            \n            <div class="block-accordion active" id="block-' + bid + '" data-block-id="' + bid + '" data-tab-id="' + tabId + '">\n                <div class="accordion-header" onclick="' + (isLocked ? '' : 'toggleBlock(' + bid + ')') + '">\n                    <div class="header-left">\n                        <div class="block-icon"><i class="fas fa-building"></i></div>\n                        <div class="block-info">\n                            <div class="block-title">' + (block.name || 'Block') + ' ' + hostelBadge + ' <span style="font-size:0.65rem;color:var(--text-muted);font-weight:400;">ID: ' + bid + '</span></div>\n                            <div class="block-subtitle"><span><i class="fas fa-vector-square"></i> ' + dv.toFixed(2) + ' ' + getUnitDisplayName(du) + '</span></div>\n                        </div>\n                    </div>\n                    <div class="header-actions">\n                        <span class="block-alloc-summary" id="blockAllocSummary-' + bid + '"><i class="fas fa-cubes"></i> 0 allocated</span>\n                        <div class="toggle-icon"><i class="fas fa-chevron-down"></i></div>\n                    </div>\n                </div>\n                <div class="accordion-body">\n                    <div class="accordion-content">\n                        <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:0.5rem;">\n                            <div class="form-group">\n                                <label class="form-label">Block Name <span style="color:#dc3545;">*</span></label>\n                                <input type="text" class="form-control block-name" value="' + escapeHtml(block.name || '') + '" placeholder="e.g., Main Block" onchange="updateBlockTabLabel(' + tabId + ', this.value);updateBlockAllocationSummary(' + bid + ');">\n                            </div>\n                            <div class="form-group">\n                                <label class="form-label">Block Code</label>\n                                <input type="text" class="form-control block-code" value="' + escapeHtml(block.code || '') + '" placeholder="e.g., MB" maxlength="10">\n                            </div>\n                        </div>\n                        <div class="form-group" style="margin-top:0.75rem;">\n                            <label class="form-label">Description</label>\n                            <textarea class="form-control block-description" rows="2" placeholder="Brief description...">' + escapeHtml(block.description || '') + '</textarea>\n                        </div>\n                        <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:0.75rem;">\n                            <div class="form-group">\n                                <label class="form-label">Status</label>\n                                <select class="form-control block-status">\n                                    <option value="active" ' + (block.status === 'active' ? 'selected' : '') + '>Active</option>\n                                    <option value="inactive" ' + (block.status === 'inactive' ? 'selected' : '') + '>Inactive</option>\n                                </select>\n                            </div>\n                            <div class="form-group">\n                                <label class="form-label">Number of Floors</label>\n                                <input type="number" class="form-control block-floors" placeholder="e.g., 4" min="0" value="' + (block.floors || 1) + '">\n                            </div>\n                        </div>\n                        \n                        <div class="section-divider"><h4><i class="fas fa-cubes" style="color:var(--primary-color);"></i> Campus Resource Allocation</h4></div>\n                        <div class="allocation-tracker" id="blockAllocTracker-' + bid + '">' + allocHtml + '</div>\n                        \n                        <div class="section-divider"><h4><i class="fas fa-vector-square"></i> Total Area of Block</h4></div>\n                        <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">\n                            <div class="form-group">\n                                <label>Area Unit</label>\n                                <select class="form-control block-area-unit" onchange="handleBlockAreaUnitChange(this,' + bid + ')">' + generateUnitOptions(du) + '</select>\n                            </div>\n                            <div class="form-group">\n                                <label>Area Value</label>\n                                <input type="number" class="form-control block-area-value" value="' + dv + '" min="0" step="0.01" onchange="onBlockAreaValueChange(this,' + bid + ',\'' + tabId + '\');">\n                            </div>\n                        </div>\n                        <div class="alert-info" style="font-size:0.8rem;margin-top:8px;">\n                            <i class="fas fa-info-circle"></i> Original campus area: <strong>' + originalCampusAreaValue + ' ' + getUnitDisplayName(originalCampusAreaUnit) + '</strong>. Changing unit only affects display.\n                        </div>\n                        <div class="block-area-summary" id="blockAreaSummary-' + tabId + '">\n                            <i class="fas fa-calculator"></i> Block Area: <strong>' + dv.toFixed(2) + ' ' + getUnitDisplayName(du) + '</strong> | Additional: <strong>0.00 ' + getUnitDisplayName(du) + '</strong> | Facilities: <strong>0.00 ' + getUnitDisplayName(du) + '</strong> | <span class="remaining-block">Remaining: <strong>' + dv.toFixed(2) + ' ' + getUnitDisplayName(du) + '</span>\n                        </div>\n                        \n                        <div class="section-divider"><h4><i class="fas fa-map"></i> Additional Areas</h4></div>\n                        <div class="block-areas-container" data-block-id="' + bid + '">' + areasHtml + '</div>\n                        <div class="add-btn-row"><button type="button" class="btn-outline-primary" onclick="addAreaToBlockTab(' + tabId + ')"><i class="fas fa-plus"></i> Add Area</button></div>\n                        \n                        <div class="section-divider"><h4><i class="fas fa-door-open"></i> Gates</h4></div>\n                        <div class="block-gates-container" data-block-id="' + bid + '">' + gatesHtml + '</div>\n                        <div class="add-btn-row"><button type="button" class="btn-outline-primary" onclick="addGateToBlockTab(' + tabId + ')"><i class="fas fa-plus"></i> Add Gate</button></div>\n                        \n                        <div class="section-divider"><h4><i class="fas fa-plus-circle" style="color:#10b981;"></i> Custom Amenities (Block Specific)</h4></div>\n                        <div class="block-custom-amenities-container" data-block-id="' + bid + '">' + customHtml + '</div>\n                        <div class="add-btn-row"><button type="button" class="btn-outline-success" onclick="addCustomAmenityToBlockTab(' + tabId + ')"><i class="fas fa-plus"></i> Add Custom</button></div>\n                        \n                        <div style="text-align:right;margin-top:1rem;">\n                            ' + saveBtn + '\n                            ' + removeBtn + '\n                        </div>\n                    </div>\n                </div>\n            </div>\n        ';
    }

    // ============================================================
    // ===== BUILD BLOCK ALLOCATION HTML =====
    // ============================================================

    function buildBlockAllocationHTML(bid) {
        var html = '<div class="allocation-header"><h4><i class="fas fa-arrow-right"></i> Select Campus Resources for this Block</h4><span class="remaining-tag available" id="blockAllocTag-' + bid + '"><i class="fas fa-check-circle"></i> Available</span></div>';

        var amenityKeys = Object.keys(campusAmenityTotals).filter(function(key) {
            return !key.endsWith('_id') && !key.endsWith('_category');
        });
        
        var categorizedAmenities = {};
        
        for (var i = 0; i < amenityKeys.length; i++) {
            var key = amenityKeys[i];
            var category = getAmenityCategory(key);
            
            if (!categorizedAmenities[category]) {
                categorizedAmenities[category] = [];
            }
            categorizedAmenities[category].push(key);
        }
        
        var categoryNames = {
            'technology': 'Technology & Connectivity',
            'security': 'Security & Safety',
            'accessibility': 'Accessibility',
            'utilities': 'Utilities & Power',
            'hygiene': 'Hygiene & Sanitation',
            'food': 'Food & Dining',
            'education': 'Education & Learning',
            'recreation': 'Recreation & Sports',
            'medical': 'Medical & Health',
            'services': 'Services & Amenities',
            'accommodation': 'Accommodation',
            'general': 'General'
        };
        
        var categoryIcons = {
            'technology': 'fa-microchip',
            'security': 'fa-shield-alt',
            'accessibility': 'fa-wheelchair',
            'utilities': 'fa-bolt',
            'hygiene': 'fa-hand-sparkles',
            'food': 'fa-utensils',
            'education': 'fa-graduation-cap',
            'recreation': 'fa-gamepad',
            'medical': 'fa-heartbeat',
            'services': 'fa-concierge-bell',
            'accommodation': 'fa-bed',
            'general': 'fa-folder'
        };
        
        var categoryKeys = Object.keys(categorizedAmenities).sort();
        
        if (categoryKeys.length > 0) {
            for (var ci = 0; ci < categoryKeys.length; ci++) {
                var category = categoryKeys[ci];
                var keys = categorizedAmenities[category];
                
                html += '<div class="facility-group-card" style="margin-top:8px;">';
                html += '<div class="group-header" onclick="toggleFacilityGroup(this)">';
                html += '<span class="group-title"><i class="fas ' + 
                (categoryIcons[category] || 'fa-folder') + 
                '"></i> Amenities <span style="font-size:0.65rem;color:var(--text-muted);font-weight:400;">(' + 
                keys.length + 
                ')</span></span>';
                html += '<span style="display:flex;align-items:center;gap:10px;">';
                html += '<span class="group-badge">' + keys.length + ' items</span>';
                html += '<i class="fas fa-chevron-down group-toggle"></i>';
                html += '</span></div>';
                html += '<div class="group-body"><div class="group-body-inner">';
                
                for (var ki = 0; ki < keys.length; ki++) {
                    var key = keys[ki];
                    var total = parseInt(campusAmenityTotals[key]) || 0;
                    var displayName = key.replace(/_/g, ' ').replace(/\b\w/g, function(l) { return l.toUpperCase(); });
                    var categoryClass = getAmenityCategory(key);
                    
                    var allocation = blockAllocations[bid] && blockAllocations[bid][key] ? blockAllocations[bid][key] : null;
                    var isChecked = allocation && allocation.allocated === true;
                    var qty = isChecked ? (parseInt(allocation.quantity) || 0) : 0;
                    var usedGlobal = getUsedCountForAmenity(key);
                    var remaining = Math.max(0, total - usedGlobal);
                    
                    html += '<div class="allocation-item" data-amenity="' + key + '">';
                    html += '<input type="checkbox" class="alloc-check" data-block="' + bid + '" data-key="' + key + '" data-total="' + total + '" ' + (isChecked ? 'checked' : '') + ' onchange="onBlockAllocationChange(this)">';
                    html += '<span class="alloc-name">' + displayName + ' <span class="' + getCategoryBadgeClass(categoryClass) + '">' + (categoryNames[categoryClass] || 'General') + '</span></span>';
                    html += '<div class="alloc-stats">';
                    html += '<span class="used">In this block: ' + qty + '</span>';
                    html += '<span class="remaining ' + (remaining > 0 ? 'positive' : 'zero') + '">Remaining: ' + remaining + '</span>';
                    html += '</div>';
                    html += '<input type="number" class="alloc-qty ' + (isChecked ? 'show' : '') + '" data-block="' + bid + '" data-key="' + key + '" min="1" max="' + Math.max(1, remaining + qty) + '" value="' + (isChecked ? qty : 1) + '" ' + (isChecked ? '' : 'disabled') + ' onchange="onBlockAllocationQtyChange(this)">';
                    html += '<span class="alloc-badge ' + (isChecked ? 'included' : 'excluded') + '">' + (isChecked ? 'Included' : 'Excluded') + '</span>';
                    html += '</div>';
                }
                
                html += '</div></div></div>';
            }
        } else {
            html += '<div style="text-align:center;padding:0.5rem;color:var(--text-muted);font-size:0.85rem;"><i class="fas fa-info-circle"></i> No campus amenities found. Go to <a href="' + API_BASE_URL + '/institute/admin/amenities-management" style="color:var(--primary-color);font-weight:600;">Manage Amenities</a> to add them.</div>';
        }

        var customKeys = Object.keys(campusCustomAmenityTotals);
        if (customKeys.length > 0) {
            html += '<div style="margin-top:12px;margin-bottom:8px;font-weight:600;font-size:0.8rem;color:var(--text-dark);"><i class="fas fa-plus-circle" style="color:#10b981;"></i> Custom Campus Amenities</div>';
            for (var j = 0; j < customKeys.length; j++) {
                var key2 = customKeys[j];
                var item = campusCustomAmenityTotals[key2];
                if (!item) continue;
                var total2 = parseInt(item.total) || 0;
                var allocation2 = blockAllocations[bid] && blockAllocations[bid][key2] ? blockAllocations[bid][key2] : null;
                var isChecked2 = allocation2 && allocation2.allocated === true;
                var qty2 = isChecked2 ? (parseInt(allocation2.quantity) || 0) : 0;
                var usedGlobal2 = getUsedCountForCustomAmenity(key2);
                var remaining2 = Math.max(0, total2 - usedGlobal2);
                
                html += '<div class="allocation-item" data-custom="' + key2 + '">';
                html += '<input type="checkbox" class="alloc-check" data-block="' + bid + '" data-key="' + key2 + '" data-total="' + total2 + '" data-custom="true" ' + (isChecked2 ? 'checked' : '') + ' onchange="onBlockAllocationChange(this)">';
                html += '<span class="alloc-name">' + escapeHtml(item.name) + ' <span class="amenity-category-badge general">Custom</span></span>';
                html += '<div class="alloc-stats">';
                html += '<span class="used">In this block: ' + qty2 + '</span>';
                html += '<span class="remaining ' + (remaining2 > 0 ? 'positive' : 'zero') + '">Remaining: ' + remaining2 + '</span>';
                html += '</div>';
                html += '<input type="number" class="alloc-qty ' + (isChecked2 ? 'show' : '') + '" data-block="' + bid + '" data-key="' + key2 + '" min="1" max="' + Math.max(1, remaining2 + qty2) + '" value="' + (isChecked2 ? qty2 : 1) + '" ' + (isChecked2 ? '' : 'disabled') + ' onchange="onBlockAllocationQtyChange(this)">';
                html += '<span class="alloc-badge ' + (isChecked2 ? 'included' : 'excluded') + '">' + (isChecked2 ? 'Included' : 'Excluded') + '</span>';
                html += '</div>';
            }
        }

        var facilityTypes = Object.keys(campusFacilityEntries);
        if (facilityTypes.length > 0) {
            html += '<div style="margin-top:12px;margin-bottom:8px;font-weight:600;font-size:0.8rem;color:var(--text-dark);"><i class="fas fa-building"></i> Campus Facilities</div>';
            for (var ft = 0; ft < facilityTypes.length; ft++) {
                var type = facilityTypes[ft];
                var entries = campusFacilityEntries[type];
                var typeDisplayName = type.replace(/_/g, ' ').replace(/\b\w/g, function(l) { return l.toUpperCase(); });
                var iconMap = { 'parking': 'fa-parking', 'playground': 'fa-futbol', 'swimming_pool': 'fa-swimming-pool', 'clubhouse': 'fa-home', 'warehouse': 'fa-warehouse', 'store_room': 'fa-boxes', 'auditorium': 'fa-theater-masks', 'washrooms': 'fa-restroom' };
                var icon = iconMap[type] || 'fa-building';
                var allocatedCount = 0;
                for (var ei = 0; ei < entries.length; ei++) {
                    if (blockAllocations[bid] && blockAllocations[bid][entries[ei].id] && blockAllocations[bid][entries[ei].id].allocated) allocatedCount++;
                }
                var totalCount = entries.length;
                var badgeText = allocatedCount > 0 ? allocatedCount + '/' + totalCount + ' selected' : totalCount + ' available';
                
                html += '\n                    <div class="facility-group-card">\n' +
                        '    <div class="group-header" onclick="toggleFacilityGroup(this)">\n' +
                        '        <span class="group-title"><i class="fas ' + icon + '"></i> ' + typeDisplayName + '</span>\n' +
                        '        <span style="display:flex;align-items:center;gap:10px;">\n' +
                        '            <span class="group-badge">' + badgeText + '</span>\n' +
                        '            <i class="fas fa-chevron-down group-toggle"></i>\n' +
                        '        </span>\n' +
                        '    </div>\n' +
                        '    <div class="group-body">\n' +
                        '        <div class="group-body-inner">\n';
                
                for (var ei2 = 0; ei2 < entries.length; ei2++) {
                    var entry = entries[ei2];
                    var isChecked3 = blockAllocations[bid] && blockAllocations[bid][entry.id] && blockAllocations[bid][entry.id].allocated;
                    var usedGlobal3 = getUsedCountForFacilityEntry(entry.id);
                    var total3 = 1;
                    var remaining3 = Math.max(0, total3 - usedGlobal3);
                    var extraInfo = '';
                    if (entry.toilets !== undefined) extraInfo = '🚽' + (entry.toilets || 0) + ' 🚹' + (entry.urinals || 0) + ' 🚰' + (entry.washbasins || 0);
                    if (entry.area) extraInfo += ' 📐' + parseFloat(entry.area).toFixed(1) + ' sqft';
                    
                    html += '\n' +
                        '            <div class="allocation-item" data-facility-entry="' + entry.id + '">\n' +
                        '                <input type="checkbox" class="alloc-check" data-block="' + bid + '" data-key="' + entry.id + '" data-total="1" data-facility-entry="true" ' + (isChecked3 ? 'checked' : '') + ' onchange="onBlockAllocationChange(this)">\n' +
                        '                <span class="alloc-name">' + escapeHtml(entry.name) + ' <span style="font-size:0.65rem;color:var(--text-muted);font-weight:400;">' + extraInfo + '</span></span>\n' +
                        '                <div class="alloc-stats">\n' +
                        '                    <span class="used">In this block: ' + (isChecked3 ? '1' : '0') + '</span>\n' +
                        '                    <span class="remaining ' + (remaining3 > 0 ? 'positive' : 'zero') + '">Remaining: ' + remaining3 + '</span>\n' +
                        '                </div>\n' +
                        '                <span class="alloc-badge ' + (isChecked3 ? 'included' : 'excluded') + '">' + (isChecked3 ? 'Included' : 'Excluded') + '</span>\n' +
                        '            </div>\n';
                }
                
                html += '        </div>\n' +
                        '    </div>\n' +
                        '</div>';
            }
        }

        html += '<div class="allocation-summary" style="margin-top:0.5rem;padding-top:0.5rem;">\n' +
                '    <span class="stat-item"><i class="fas fa-check-circle" style="color:#10b981;"></i> Allocated: <strong id="blockAllocCount-' + bid + '">0</strong></span>\n' +
                '    <span class="stat-item"><i class="fas fa-clock" style="color:#f59e0b;"></i> Pending: <strong id="blockAllocPending-' + bid + '">0</strong></span>\n' +
                '</div>';
        return html;
    }

    // ============================================================
    // ===== GET USED COUNT FOR AMENITY =====
    // ============================================================

    function getUsedCountForAmenity(key) {
        var total = 0;
        var bids = Object.keys(blockAllocations);
        for (var i = 0; i < bids.length; i++) {
            var bid = bids[i];
            if (blockAllocations[bid] && blockAllocations[bid][key] && blockAllocations[bid][key].allocated) {
                total += blockAllocations[bid][key].quantity || 0;
            }
        }
        return total;
    }

    function getUsedCountForCustomAmenity(key) {
        var total = 0;
        var bids = Object.keys(blockAllocations);
        for (var i = 0; i < bids.length; i++) {
            var bid = bids[i];
            if (blockAllocations[bid] && blockAllocations[bid][key] && blockAllocations[bid][key].allocated) {
                total += blockAllocations[bid][key].quantity || 0;
            }
        }
        return total;
    }

    function getUsedCountForFacilityEntry(entryId) {
        var count = 0;
        var bids = Object.keys(blockAllocations);
        for (var i = 0; i < bids.length; i++) {
            var bid = bids[i];
            if (blockAllocations[bid] && blockAllocations[bid][entryId] && blockAllocations[bid][entryId].allocated) {
                count++;
            }
        }
        return count;
    }

    // ============================================================
    // ===== ON BLOCK ALLOCATION CHANGE =====
    // ============================================================

    function onBlockAllocationChange(checkbox) {
        var bid = checkbox.getAttribute('data-block');
        var key = checkbox.getAttribute('data-key');
        var total = parseInt(checkbox.getAttribute('data-total')) || 0;
        var isFacilityEntry = checkbox.getAttribute('data-facility-entry') === 'true';
        var isCustom = checkbox.getAttribute('data-custom') === 'true';
        
        if (!bid || !key) {
            console.error('Missing data attributes:', { bid, key });
            return;
        }
        
        if (!blockAllocations[bid]) {
            blockAllocations[bid] = {};
        }

        var item = checkbox.closest('.allocation-item');
        if (!item) return;

        var qtyInput = item.querySelector('.alloc-qty');
        var usedSpan = item.querySelector('.used');
        var remainingSpan = item.querySelector('.remaining');
        var badge = item.querySelector('.alloc-badge');

        if (isFacilityEntry) {
            if (checkbox.checked) {
                blockAllocations[bid][key] = { allocated: true, quantity: 1 };
                if (qtyInput) {
                    qtyInput.disabled = false;
                    qtyInput.classList.add('show');
                    qtyInput.value = 1;
                    qtyInput.min = 1;
                    qtyInput.max = 1;
                }
                if (badge) {
                    badge.textContent = 'Included';
                    badge.className = 'alloc-badge included';
                }
                if (usedSpan) {
                    usedSpan.textContent = 'In this block: 1';
                }
                if (remainingSpan) {
                    remainingSpan.textContent = 'Remaining globally: 0';
                    remainingSpan.className = 'remaining zero';
                }
            } else {
                delete blockAllocations[bid][key];
                if (qtyInput) {
                    qtyInput.disabled = true;
                    qtyInput.classList.remove('show');
                    qtyInput.value = 1;
                }
                if (badge) {
                    badge.textContent = 'Excluded';
                    badge.className = 'alloc-badge excluded';
                }
                if (usedSpan) {
                    usedSpan.textContent = 'In this block: 0';
                }
                if (remainingSpan) {
                    remainingSpan.textContent = 'Remaining globally: 1';
                    remainingSpan.className = 'remaining positive';
                }
            }
            updateBlockAllocationSummary(bid);
            updateGlobalAllocationSummary();
            updateBlockAllocationStates();
            return;
        }

        if (!checkbox.checked) {
            delete blockAllocations[bid][key];
            
            if (qtyInput) {
                qtyInput.disabled = true;
                qtyInput.classList.remove('show');
                qtyInput.value = 1;
            }
            if (badge) {
                badge.textContent = 'Excluded';
                badge.className = 'alloc-badge excluded';
            }
        } else {
            var usedBefore = isCustom ? getUsedCountForCustomAmenity(key) : getUsedCountForAmenity(key);
            var available = Math.max(0, total - usedBefore);
            
            if (available <= 0) {
                checkbox.checked = false;
                showToast('No quantity remaining for this amenity.', 'error');
                return;
            }
            
            blockAllocations[bid][key] = { allocated: true, quantity: 1 };
            
            if (qtyInput) {
                qtyInput.disabled = false;
                qtyInput.classList.add('show');
                qtyInput.value = 1;
                qtyInput.min = 1;
                qtyInput.max = available;
            }
            if (badge) {
                badge.textContent = 'Included';
                badge.className = 'alloc-badge included';
            }
        }

        var used = isCustom ? getUsedCountForCustomAmenity(key) : getUsedCountForAmenity(key);
        var remaining = Math.max(0, total - used);
        var currentQty = blockAllocations[bid] && blockAllocations[bid][key] ? parseInt(blockAllocations[bid][key].quantity) || 0 : 0;

        if (usedSpan) {
            usedSpan.textContent = 'In this block: ' + currentQty;
        }
        if (remainingSpan) {
            remainingSpan.textContent = 'Remaining globally: ' + remaining;
            remainingSpan.className = 'remaining ' + (remaining > 0 ? 'positive' : 'zero');
        }

        updateAllocationCheckboxes(key, total);
        updateAllocationStats(key);
        updateBlockAllocationSummary(bid);
        updateGlobalAllocationSummary();
        updateBlockAllocationStates();

        var tabId = null;
        var tabIds = Object.keys(blockTabData);
        for (var i = 0; i < tabIds.length; i++) {
            if (blockTabData[tabIds[i]].blockId === bid) {
                tabId = tabIds[i];
                break;
            }
        }
        if (tabId && blockTabData[tabId] && !blockTabData[tabId].isLocked) {
            blockTabData[tabId].isSaved = false;
            updateBlockTabStatus(tabId);
        }
    }

    // ============================================================
    // ===== ON BLOCK ALLOCATION QTY CHANGE =====
    // ============================================================

    function onBlockAllocationQtyChange(input) {
        var bid = input.getAttribute('data-block');
        var key = input.getAttribute('data-key');
        
        if (!bid || !key) {
            console.error('Missing data attributes:', { bid, key });
            return;
        }
        
        var item = input.closest('.allocation-item');
        if (!item) return;
        
        var checkbox = item.querySelector('.alloc-check');
        if (!checkbox) return;
        
        var isFacilityEntry = checkbox.getAttribute('data-facility-entry') === 'true';
        var isCustom = checkbox.getAttribute('data-custom') === 'true';
        if (isFacilityEntry) return;
        
        var total = parseInt(checkbox.getAttribute('data-total')) || 0;
        var oldQty = blockAllocations[bid] && blockAllocations[bid][key] ? parseInt(blockAllocations[bid][key].quantity) || 0 : 0;
        var usedGlobal = isCustom ? getUsedCountForCustomAmenity(key) : getUsedCountForAmenity(key);
        var availableForThisBlock = total - usedGlobal + oldQty;
        
        var val = parseInt(input.value) || 1;
        if (val < 1) val = 1;
        if (val > availableForThisBlock) {
            val = availableForThisBlock;
            showToast('Maximum ' + availableForThisBlock + ' units available', 'info');
        }
        if (val < 1) val = 1;
        
        if (!blockAllocations[bid]) {
            blockAllocations[bid] = {};
        }
        blockAllocations[bid][key] = { allocated: true, quantity: val };
        
        input.value = val;
        input.min = 1;
        input.max = availableForThisBlock;
        
        var newUsedGlobal = isCustom ? getUsedCountForCustomAmenity(key) : getUsedCountForAmenity(key);
        var remainingGlobal = Math.max(0, total - newUsedGlobal);
        
        var usedSpan = item.querySelector('.used');
        if (usedSpan) {
            usedSpan.textContent = 'In this block: ' + val;
        }
        
        var remainingSpan = item.querySelector('.remaining');
        if (remainingSpan) {
            remainingSpan.textContent = 'Remaining globally: ' + remainingGlobal;
            remainingSpan.className = 'remaining ' + (remainingGlobal > 0 ? 'positive' : 'zero');
        }
        
        updateAllocationCheckboxes(key, total);
        updateAllocationStats(key);
        updateBlockAllocationSummary(bid);
        updateGlobalAllocationSummary();
        updateBlockAllocationStates();
        
        var tabId = null;
        var tabIds = Object.keys(blockTabData);
        for (var i = 0; i < tabIds.length; i++) {
            if (blockTabData[tabIds[i]].blockId === bid) {
                tabId = tabIds[i];
                break;
            }
        }
        if (tabId && blockTabData[tabId] && !blockTabData[tabId].isLocked) {
            blockTabData[tabId].isSaved = false;
            updateBlockTabStatus(tabId);
        }
    }

    // ============================================================
    // ===== UPDATE ALLOCATION CHECKBOXES =====
    // ============================================================

    function updateAllocationCheckboxes(key, total) {
        total = parseInt(total) || 0;
        var used = getUsedCountForAmenity(key);
        var remaining = Math.max(0, total - used);
        
        var checks = document.querySelectorAll('.alloc-check[data-key="' + key + '"]');
        
        for (var i = 0; i < checks.length; i++) {
            var checkbox = checks[i];
            var bid = checkbox.getAttribute('data-block');
            var item = checkbox.closest('.allocation-item');
            if (!item) continue;
            
            var allocation = blockAllocations[bid] && blockAllocations[bid][key] ? blockAllocations[bid][key] : null;
            var isAllocated = allocation && allocation.allocated === true;
            var qty = isAllocated ? parseInt(allocation.quantity) || 0 : 0;
            
            var qtyInput = item.querySelector('.alloc-qty');
            var usedSpan = item.querySelector('.used');
            var remainingSpan = item.querySelector('.remaining');
            var badge = item.querySelector('.alloc-badge');
            
            if (isAllocated) {
                checkbox.checked = true;
                checkbox.disabled = false;
                
                if (qtyInput) {
                    qtyInput.disabled = false;
                    qtyInput.classList.add('show');
                    qtyInput.value = qty;
                    qtyInput.max = Math.max(1, remaining + qty);
                }
                if (usedSpan) {
                    usedSpan.textContent = 'In this block: ' + qty;
                }
                if (remainingSpan) {
                    remainingSpan.textContent = 'Remaining globally: ' + remaining;
                    remainingSpan.className = 'remaining ' + (remaining > 0 ? 'positive' : 'zero');
                }
                if (badge) {
                    badge.textContent = 'Included';
                    badge.className = 'alloc-badge included';
                }
                continue;
            }
            
            checkbox.checked = false;
            
            if (remaining <= 0) {
                checkbox.disabled = true;
                if (qtyInput) {
                    qtyInput.disabled = true;
                    qtyInput.classList.remove('show');
                    qtyInput.value = 1;
                }
                if (badge) {
                    badge.textContent = 'Exhausted';
                    badge.className = 'alloc-badge exhausted';
                }
            } else {
                checkbox.disabled = false;
                if (qtyInput) {
                    qtyInput.disabled = true;
                    qtyInput.classList.remove('show');
                    qtyInput.value = 1;
                    qtyInput.max = remaining;
                }
                if (badge) {
                    badge.textContent = 'Excluded';
                    badge.className = 'alloc-badge excluded';
                }
            }
            
            if (usedSpan) {
                usedSpan.textContent = 'In this block: 0';
            }
            if (remainingSpan) {
                remainingSpan.textContent = 'Remaining globally: ' + remaining;
                remainingSpan.className = 'remaining ' + (remaining > 0 ? 'positive' : 'zero');
            }
        }
    }

    function updateAllocationStats(key) {
        var used = getUsedCountForAmenity(key);
        var items = document.querySelectorAll('.allocation-item[data-amenity="' + key + '"]');
        for (var i = 0; i < items.length; i++) {
            var item = items[i];
            var usedSpan = item.querySelector('.used');
            var remainingSpan = item.querySelector('.remaining');
            var total = parseInt(item.querySelector('.alloc-check').getAttribute('data-total')) || 0;
            var remaining = Math.max(0, total - used);
            var bid = item.querySelector('.alloc-check').getAttribute('data-block');
            var isAllocated = blockAllocations[bid] && blockAllocations[bid][key] && blockAllocations[bid][key].allocated;
            var qty = isAllocated ? (blockAllocations[bid][key].quantity || 0) : 0;
            
            if (usedSpan) usedSpan.textContent = 'In this block: ' + qty;
            if (remainingSpan) {
                remainingSpan.textContent = 'Remaining globally: ' + remaining;
                remainingSpan.className = 'remaining ' + (remaining > 0 ? 'positive' : 'zero');
            }
        }
    }

    function updateBlockAllocationStates() {
        var amenityKeys = Object.keys(campusAmenityTotals).filter(function(key) {
            return !key.endsWith('_id') && !key.endsWith('_category');
        });
        for (var i = 0; i < amenityKeys.length; i++) {
            var key = amenityKeys[i];
            var total = campusAmenityTotals[key] || 0;
            updateAllocationCheckboxes(key, total);
        }
        
        var customKeys = Object.keys(campusCustomAmenityTotals);
        for (var j = 0; j < customKeys.length; j++) {
            var key2 = customKeys[j];
            var item = campusCustomAmenityTotals[key2];
            if (item) {
                var total2 = item.total || 0;
                updateAllocationCheckboxes(key2, total2);
            }
        }
        
        var facilityTypes = Object.keys(campusFacilityEntries);
        for (var ft = 0; ft < facilityTypes.length; ft++) {
            var type = facilityTypes[ft];
            var entries = campusFacilityEntries[type];
            for (var ei = 0; ei < entries.length; ei++) {
                var entry = entries[ei];
                updateAllocationCheckboxes(entry.id, 1);
            }
        }
    }

    // ============================================================
    // ===== UPDATE BLOCK ALLOCATION SUMMARY =====
    // ============================================================

    function updateBlockAllocationSummary(bid) {
        var allocs = blockAllocations[bid] || {};
        var count = 0;
        var keys = Object.keys(allocs);
        for (var i = 0; i < keys.length; i++) {
            var key = keys[i];
            if (allocs[key] && allocs[key].allocated) count += allocs[key].quantity || 1;
        }
        var summary = document.getElementById('blockAllocSummary-' + bid);
        if (summary) summary.innerHTML = '<i class="fas fa-cubes"></i> ' + count + ' allocated';
        var countEl = document.getElementById('blockAllocCount-' + bid);
        if (countEl) countEl.textContent = count;
        var tag = document.getElementById('blockAllocTag-' + bid);
        if (tag) {
            var totalPossible = Object.keys(campusAmenityTotals).filter(function(key) {
                return !key.endsWith('_id') && !key.endsWith('_category');
            }).length + Object.keys(campusCustomAmenityTotals).length;
            var facilityTypes = Object.keys(campusFacilityEntries);
            for (var j = 0; j < facilityTypes.length; j++) {
                totalPossible += campusFacilityEntries[facilityTypes[j]].length;
            }
            if (count >= totalPossible && totalPossible > 0) { 
                tag.className = 'remaining-tag exhausted';
                tag.innerHTML = '<i class="fas fa-check-circle"></i> Fully Allocated'; 
            } else if (totalPossible === 0) { 
                tag.className = 'remaining-tag';
                tag.innerHTML = '<i class="fas fa-info-circle"></i> No Resources'; 
            } else { 
                tag.className = 'remaining-tag available';
                tag.innerHTML = '<i class="fas fa-check-circle"></i> Available'; 
            }
        }
    }

    // ============================================================
    // ===== UPDATE GLOBAL ALLOCATION SUMMARY =====
    // ============================================================

    function updateGlobalAllocationSummary() {
        var gas = document.getElementById('globalAllocSummary');
        if (!gas) return;
        var totalBlocks = Object.keys(blockDataMap).length;
        var totalAllocated = 0;
        var bids = Object.keys(blockAllocations);
        for (var i = 0; i < bids.length; i++) {
            var bid = bids[i];
            var allocs = blockAllocations[bid] || {};
            var keys = Object.keys(allocs);
            for (var j = 0; j < keys.length; j++) {
                var key = keys[j];
                if (allocs[key] && allocs[key].allocated) {
                    totalAllocated += allocs[key].quantity || 1;
                }
            }
        } 

        var totalAvailable = 0;
        var amenityKeys = Object.keys(campusAmenityTotals).filter(function(key) {
            return !key.endsWith('_id') && !key.endsWith('_category');
        });
        for (var k = 0; k < amenityKeys.length; k++) {
            totalAvailable += campusAmenityTotals[amenityKeys[k]] || 0;
        }
        var customKeys = Object.keys(campusCustomAmenityTotals);
        for (var l = 0; l < customKeys.length; l++) {
            var item = campusCustomAmenityTotals[customKeys[l]];
            totalAvailable += item ? item.total || 0 : 0;
        }
        var facilityTypes = Object.keys(campusFacilityEntries);
        for (var m = 0; m < facilityTypes.length; m++) {
            totalAvailable += campusFacilityEntries[facilityTypes[m]].length;
        }
        var remaining = totalAvailable - totalAllocated;
        document.getElementById('globalBlockCount').textContent = totalBlocks;
        document.getElementById('globalAllocatedCount').textContent = totalAllocated;
        document.getElementById('globalRemainingCount').textContent = remaining > 0 ? remaining : 0;
        var tag = document.getElementById('globalAllocTag');
        if (tag) {
            if (remaining <= 0 && totalAvailable > 0) { 
                tag.className = 'remaining-tag exhausted';
                tag.innerHTML = '<i class="fas fa-exclamation-circle"></i> All Resources Allocated'; 
            } else if (totalAvailable === 0) { 
                tag.className = 'remaining-tag';
                tag.innerHTML = '<i class="fas fa-info-circle"></i> No Resources Added'; 
            } else { 
                tag.className = 'remaining-tag available';
                tag.innerHTML = '<i class="fas fa-check-circle"></i> Resources Available'; 
            }
        }
        var container = document.getElementById('globalAllocItems');
        if (container) {
            var itemsHtml = '';
            for (var n = 0; n < amenityKeys.length; n++) {
                var key2 = amenityKeys[n];
                var total2 = campusAmenityTotals[key2] || 0;
                var used = getUsedCountForAmenity(key2);
                var displayName = key2.replace(/_/g, ' ').replace(/\b\w/g, function(l) { return l.toUpperCase(); });
                var category = getAmenityCategory(key2);
                itemsHtml += '<span class="block-alloc-summary" style="font-size:0.65rem;padding:3px 10px;"><i class="fas fa-cube"></i> ' + displayName + ': ' + used + '/' + total2 + ' <span class="' + getCategoryBadgeClass(category) + '" style="font-size:0.5rem;">' + getCategoryDisplayName(category) + '</span></span>';
            }
            for (var o = 0; o < customKeys.length; o++) {
                var key3 = customKeys[o];
                var item2 = campusCustomAmenityTotals[key3];
                var total3 = item2 ? item2.total || 0 : 0;
                var used2 = getUsedCountForCustomAmenity(key3);
                itemsHtml += '<span class="block-alloc-summary" style="font-size:0.65rem;padding:3px 10px;background:linear-gradient(135deg,#ecfdf5,#d1fae5);"><i class="fas fa-plus-circle"></i> ' + escapeHtml(item2 ? item2.name || key3 : key3) + ': ' + used2 + '/' + total3 + '</span>';
            }
            for (var p = 0; p < facilityTypes.length; p++) {
                var type2 = facilityTypes[p];
                var entries = campusFacilityEntries[type2];
                for (var q = 0; q < entries.length; q++) {
                    var entry2 = entries[q];
                    var used3 = getUsedCountForFacilityEntry(entry2.id);
                    var total4 = 1;
                    var displayName3 = entry2.name || type2;
                    var extra = '';
                    if (entry2.toilets !== undefined) extra = ' 🚽' + entry2.toilets + ' 🚹' + (entry2.urinals || 0) + ' 🚰' + (entry2.washbasins || 0);
                    itemsHtml += '<span class="block-alloc-summary" style="font-size:0.65rem;padding:3px 10px;background:linear-gradient(135deg,#fef3c7,#fde68a);"><i class="fas fa-building"></i> ' + escapeHtml(displayName3) + extra + ': ' + used3 + '/' + total4 + '</span>';
                }
            }
            if (itemsHtml) container.innerHTML = itemsHtml;
            else container.innerHTML = '<span style="color:var(--text-muted);font-size:0.75rem;">No resources to allocate</span>';
            gas.style.display = 'block';
        }
    }

    // ============================================================
    // ===== BLOCK AREA FUNCTIONS (ENHANCED WITH VALIDATION) =====
    // ============================================================

    function updateBlockAreaSummary(tabId) {
        var contentEl = document.getElementById('blockContent-' + tabId);
        if (!contentEl) return;

        var tabData = blockTabData[tabId];
        if (!tabData) return;

        var bid = tabData.blockId;
        var du = blockDisplayUnits[bid] || originalCampusAreaUnit;
        var b = contentEl.querySelector('#block-' + bid);
        if (!b) return;

        var ba = parseFloat(b.querySelector('.block-area-value') ? b.querySelector('.block-area-value').value : 0) || 0;
        var as = 0;
        var areaValues = b.querySelectorAll('.area-card .area-value');
        for (var i = 0; i < areaValues.length; i++) {
            var val = parseFloat(areaValues[i].value);
            if (!isNaN(val)) as += val;
        }

        // Calculate facility area deduction
        var facilityAreaDeduction = 0;
        var allocs = blockAllocations[bid] || {};
        var keys = Object.keys(allocs);
        for (var j = 0; j < keys.length; j++) {
            var key = keys[j];
            if (allocs[key] && allocs[key].allocated) {
                var entry = null;
                var facilityTypes = Object.keys(campusFacilityEntries);
                for (var k = 0; k < facilityTypes.length; k++) {
                    var type = facilityTypes[k];
                    var found = null;
                    var entries = campusFacilityEntries[type];
                    for (var l = 0; l < entries.length; l++) {
                        if (entries[l].id === key) { found = entries[l]; break; }
                    }
                    if (found) { entry = found; break; }
                }
                if (entry && entry.area) {
                    var areaValue = parseFloat(entry.area);
                    if (!isNaN(areaValue) && areaValue > 0) {
                        var areaInOriginal = convertArea(areaValue, campusAreaUnit || 'sq_ft', originalCampusAreaUnit);
                        facilityAreaDeduction += areaInOriginal;
                    }
                }
            }
        }

        var facilityAreaInBlockUnit = convertArea(facilityAreaDeduction, originalCampusAreaUnit, du);
        var rb = ba - as - facilityAreaInBlockUnit;

        // Update block area summary display
        var se = b.querySelector('.block-area-summary');
        if (se) {
            var rc = 'remaining-block';
            var warning = '';
            if (rb < 0) {
                rc = 'exceeded-block';
                warning = ' ⚠️ Block area exceeds available space!';
            }
            se.innerHTML = '<i class="fas fa-calculator"></i> Block Area: <strong>' + ba.toFixed(2) + ' ' + getUnitDisplayName(du) + '</strong> | Additional: <strong>' + as.toFixed(2) + ' ' + getUnitDisplayName(du) + '</strong> | Facilities: <strong>' + facilityAreaInBlockUnit.toFixed(2) + ' ' + getUnitDisplayName(du) + '</strong> | <span class="' + rc + '">Remaining: <strong>' + rb.toFixed(2) + ' ' + getUnitDisplayName(du) + '</strong>' + warning + '</span>';
        }

        // Update block subtitle
        var st = b.querySelector('.block-subtitle');
        if (st) {
            var ac = b.querySelectorAll('.area-card').length;
            var gc = b.querySelectorAll('.gate-card').length;
            st.innerHTML = '<span><i class="fas fa-vector-square"></i> ' + ba.toFixed(2) + ' ' + getUnitDisplayName(du) + '</span>' + (ac > 0 ? '<span><i class="fas fa-map"></i> ' + ac + ' areas</span>' : '') + (gc > 0 ? '<span><i class="fas fa-door-open"></i> ' + gc + ' gates</span>' : '');
        }

        // Update blockDataMap with current area
        if (blockDataMap[bid]) {
            blockDataMap[bid].area_value = convertArea(ba, du, originalCampusAreaUnit);
            blockDataMap[bid].area_unit = originalCampusAreaUnit;
        } else if (tabData.blockData) {
            tabData.blockData.area_value = convertArea(ba, du, originalCampusAreaUnit);
            tabData.blockData.area_unit = originalCampusAreaUnit;
            blockDataMap[bid] = tabData.blockData;
        }

        // Validate against campus total
        var validation = validateBlockArea(bid, ba, du);
        
        // Show/hide validation message
        var existingWarning = b.querySelector('.area-validation-warning');
        if (existingWarning) existingWarning.remove();
        
        if (!validation.valid) {
            var warningEl = document.createElement('div');
            warningEl.className = 'area-validation-warning alert-error';
            warningEl.innerHTML = '<i class="fas fa-exclamation-triangle"></i> <div><strong>Area Exceeded!</strong><br>' + validation.message + '</div>';
            
            var areaSummary = b.querySelector('.block-area-summary');
            if (areaSummary) {
                areaSummary.parentNode.insertBefore(warningEl, areaSummary.nextSibling);
            }
        } else {
            var infoEl = document.createElement('div');
            infoEl.className = 'area-validation-warning alert-info';
            var remainingCampus = validation.remaining || 0;
            infoEl.innerHTML = '<i class="fas fa-info-circle"></i> Campus area remaining after this block: <strong>' + remainingCampus.toFixed(2) + ' ' + formatUnit(originalCampusAreaUnit) + '</strong>';
            
            var areaSummary2 = b.querySelector('.block-area-summary');
            if (areaSummary2) {
                areaSummary2.parentNode.insertBefore(infoEl, areaSummary2.nextSibling);
            }
        }

        updateRemainingArea();
    }

    // ============================================================
    // ===== ON BLOCK AREA VALUE CHANGE (REAL-TIME VALIDATION) =====
    // ============================================================

    function onBlockAreaValueChange(input, bid, tabId) {
        var newValue = parseFloat(input.value) || 0;
        var unitSelect = input.closest('.card-row').querySelector('.block-area-unit');
        var unit = unitSelect ? unitSelect.value : originalCampusAreaUnit;
        
        // Validate
        var validation = validateBlockArea(bid, newValue, unit);
        
        if (!validation.valid) {
            showToast(validation.message, 'error');
            
            if (validation.maxAllowed !== undefined) {
                input.value = validation.maxAllowed.toFixed(4);
                input.classList.add('area-error');
                setTimeout(function() {
                    input.classList.remove('area-error');
                }, 2000);
            }
        } else {
            input.classList.remove('area-error');
        }
        
        // Update the block data map
        if (blockDataMap[bid]) {
            blockDataMap[bid].area_value = convertArea(parseFloat(input.value) || 0, unit, originalCampusAreaUnit);
            blockDataMap[bid].area_unit = originalCampusAreaUnit;
        }
        
        updateBlockAreaSummary(tabId);
        updateRemainingArea();
    }

    // ============================================================
    // ===== ACTIVATE BLOCK TAB =====
    // ============================================================

    function activateBlockTab(tabId) {
        var tabData = blockTabData[tabId];
        if (tabData && tabData.isLocked) {
            showToast('This block has been saved and locked. You cannot edit it.', 'info');
            return;
        }

        if (activeBlockTabId && blockTabData[activeBlockTabId] && !blockTabData[activeBlockTabId].isLocked) {
            saveBlockTabData(activeBlockTabId);
        }

        activeBlockTabId = tabId;

        var tabs = document.querySelectorAll('#blockTabs .tab');
        for (var i = 0; i < tabs.length; i++) {
            tabs[i].classList.remove('active');
        }
        var contents = document.querySelectorAll('#blockContents .tab-content');
        for (var j = 0; j < contents.length; j++) {
            contents[j].classList.remove('active');
        }

        if (tabData && tabData.tabElement) {
            tabData.tabElement.classList.add('active');
        }
        var contentEl = document.getElementById('blockContent-' + tabId);
        if (contentEl) {
            contentEl.classList.add('active');
        }

        updateBlockAreaSummary(tabId);
    }

    // ============================================================
    // ===== BLOCK TAB FUNCTIONS =====
    // ============================================================

    function updateBlockTabLabel(tabId, name) {
        var tabData = blockTabData[tabId];
        if (tabData && tabData.tabElement) {
            var nameSpan = tabData.tabElement.querySelector('span:last-child');
            if (nameSpan) {
                var lockHtml = tabData.isLocked ? ' <span class="tab-lock">🔒</span>' : '';
                nameSpan.innerHTML = name || 'Block' + lockHtml;
            }
        }
        if (tabData && !tabData.isLocked) {
            tabData.isSaved = false;
            updateBlockTabStatus(tabId);
        }
    }

    function updateBlockTabStatus(tabId) {
        var tabData = blockTabData[tabId];
        if (!tabData) return;

        var statusEl = tabData.tabElement ? tabData.tabElement.querySelector('.tab-status') : null;
        var saveStatusEl = document.getElementById('blockSaveStatus-' + tabId);

        if (tabData.isLocked) {
            if (statusEl) {
                statusEl.className = 'tab-status locked-status';
                statusEl.innerHTML = '<i class="fas fa-lock"></i>';
            }
            if (saveStatusEl) {
                saveStatusEl.className = 'save-status locked-status';
                saveStatusEl.innerHTML = '<i class="fas fa-lock"></i><span>✓ Saved & Locked</span>';
            }
        } else if (tabData.isSaved) {
            if (statusEl) {
                statusEl.className = 'tab-status saved';
                statusEl.innerHTML = '<i class="fas fa-check"></i>';
            }
            if (saveStatusEl) {
                saveStatusEl.className = 'save-status saved';
                saveStatusEl.innerHTML = '<i class="fas fa-check-circle"></i><span>✓ Saved</span>';
            }
        } else {
            if (statusEl) {
                statusEl.className = 'tab-status pending';
                statusEl.innerHTML = '<i class="fas fa-clock"></i>';
            }
            if (saveStatusEl) {
                saveStatusEl.className = 'save-status pending';
                saveStatusEl.innerHTML = '<i class="fas fa-clock"></i><span>⏳ Pending Save</span>';
            }
        }
    }

    function getBlockDataFromTab(tabId) {
        var tabData = blockTabData[tabId];
        if (!tabData) return null;

        var contentEl = document.getElementById('blockContent-' + tabId);
        if (!contentEl) return null;

        var bid = tabData.blockId;
        var b = contentEl.querySelector('#block-' + bid);
        if (!b) return null;

        var nameInput = b.querySelector('.block-name');
        var n = nameInput ? nameInput.value.trim() : '';
        if (!n) return null;

        var ar = [];
        var areaCards = b.querySelectorAll('.area-card');
        for (var i = 0; i < areaCards.length; i++) {
            var c = areaCards[i];
            var nameEl = c.querySelector('.area-name');
            var valueEl = c.querySelector('.area-value');
            var unitEl = c.querySelector('.area-unit');
            var n2 = nameEl ? nameEl.value.trim() : '';
            var v = parseFloat(valueEl ? valueEl.value : 0) || 0;
            var u = unitEl ? unitEl.value : '';
            var areaId = c.getAttribute('data-area-uuid');
            if (!areaId) { areaId = generateUUID();
                c.setAttribute('data-area-uuid', areaId); }
            if (n2) ar.push({ id: areaId, name: n2, area: v, unit: u });
        }

        var ga = [];
        var gateCards = b.querySelectorAll('.gate-card');
        for (var j = 0; j < gateCards.length; j++) {
            var c2 = gateCards[j];
            var nameEl2 = c2.querySelector('.gate-name');
            var numEl = c2.querySelector('.gate-number');
            var n3 = nameEl2 ? nameEl2.value.trim() : '';
            var nu = numEl ? numEl.value.trim() : '';
            var gateId = c2.getAttribute('data-gate-uuid');
            if (!gateId) { gateId = generateUUID();
                c2.setAttribute('data-gate-uuid', gateId); }
            if (n3 || nu) ga.push({ id: gateId, name: n3, number: nu });
        }

        var cu = [];
        var customCards = b.querySelectorAll('.custom-amenity-card');
        for (var k = 0; k < customCards.length; k++) {
            var c3 = customCards[k];
            var nameEl3 = c3.querySelector('.custom-amenity-name');
            var qtyEl = c3.querySelector('.custom-amenity-qty');
            var n4 = nameEl3 ? nameEl3.value.trim() : '';
            var q = parseInt(qtyEl ? qtyEl.value : 1) || 1;
            var amenityId = c3.getAttribute('data-amenity-uuid');
            if (!amenityId) { amenityId = generateUUID();
                c3.setAttribute('data-amenity-uuid', amenityId); }
            if (n4) cu.push({ id: amenityId, name: n4, quantity: q });
        }

        var areaValueInput = b.querySelector('.block-area-value');
        var areaUnitSelect = b.querySelector('.block-area-unit');
        var dv = parseFloat(areaValueInput ? areaValueInput.value : 0) || 0;
        var du = areaUnitSelect ? areaUnitSelect.value : (blockDisplayUnits[bid] || originalCampusAreaUnit);
        var ov = convertArea(dv, du, originalCampusAreaUnit);

        var allocs = blockAllocations[bid] || {};
        var allocatedAmenities = {};
        var allocatedFacilityEntries = [];
        var allocKeys = Object.keys(allocs);
        for (var l = 0; l < allocKeys.length; l++) {
            var key = allocKeys[l];
            if (allocs[key] && allocs[key].allocated) {
                var isFacility = false;
                var facilityTypes = Object.keys(campusFacilityEntries);
                for (var m = 0; m < facilityTypes.length; m++) {
                    var type = facilityTypes[m];
                    var entries = campusFacilityEntries[type];
                    for (var n5 = 0; n5 < entries.length; n5++) {
                        if (entries[n5].id === key) { isFacility = true; break; }
                    }
                    if (isFacility) break;
                }
                if (isFacility) {
                    allocatedFacilityEntries.push(key);
                } else {
                    allocatedAmenities[key] = allocs[key].quantity || 1;
                }
            }
        }

        return {
            id: bid,
            name: n,
            code: b.querySelector('.block-code') ? b.querySelector('.block-code').value.trim() : '',
            description: b.querySelector('.block-description') ? b.querySelector('.block-description').value.trim() : '',
            status: b.querySelector('.block-status') ? b.querySelector('.block-status').value : 'active',
            floors: parseInt(b.querySelector('.block-floors') ? b.querySelector('.block-floors').value : 1) || 1,
            area_value: parseFloat(ov) || 0,
            area_unit: originalCampusAreaUnit,
            additional_areas: ar,
            gates: ga,
            custom_amenities: cu,
            allocated_amenities: allocatedAmenities,
            allocated_facility_entries: allocatedFacilityEntries
        };
    }

    function saveBlockTabData(tabId) {
        var data = getBlockDataFromTab(tabId);
        if (data && data.name) {
            var tabData = blockTabData[tabId];
            if (tabData) {
                tabData.blockData = Object.assign({}, tabData.blockData, data);
                blockDataMap[tabData.blockId] = tabData.blockData;
            }
        }
    }

    function toggleBlock(bid) {
        var b = document.getElementById('block-' + bid);
        if (b) b.classList.toggle('active');
    }

    function handleBlockAreaUnitChange(sel, bid) {
        var ou = blockDisplayUnits[bid] || originalCampusAreaUnit;
        var nu = sel.value;
        if (ou === nu) return;

        var tabId = null;
        var tabIds = Object.keys(blockTabData);
        for (var i = 0; i < tabIds.length; i++) {
            if (blockTabData[tabIds[i]].blockId === bid) { tabId = tabIds[i]; break; }
        }
        if (!tabId) return;

        var contentEl = document.getElementById('blockContent-' + tabId);
        if (!contentEl) return;

        var b = contentEl.querySelector('#block-' + bid);
        if (!b) return;

        var ai = b.querySelector('.block-area-value');
        var cdv = parseFloat(ai ? ai.value : 0) || 0;
        var ndv = convertArea(cdv, ou, nu);
        
        // Validate before applying
        var validation = validateBlockArea(bid, ndv, nu);
        if (!validation.valid) {
            showToast(validation.message, 'error');
            sel.value = ou;
            return;
        }
        
        if (ai) ai.value = ndv.toFixed(4);
        blockDisplayUnits[bid] = nu;

        var areaCards = b.querySelectorAll('.area-card');
        for (var j = 0; j < areaCards.length; j++) {
            var ac = areaCards[j];
            var av = ac.querySelector('.area-value');
            var au = ac.querySelector('.area-unit');
            if (av && av.value && au) {
                av.value = convertArea(parseFloat(av.value), ou, nu).toFixed(4);
                au.value = nu;
            }
        }

        if (blockDataMap[bid]) {
            blockDataMap[bid].area_unit = nu;
            blockDataMap[bid].area_value = parseFloat(ndv) || 0;
        }

        showToast('Block: Unit changed. Original value preserved.', 'info');
        updateBlockAreaSummary(tabId);
        updateRemainingArea();
    }

    // ============================================================
    // ===== ADD FUNCTIONS =====
    // ============================================================

    function addAreaToBlockTab(tabId) {
        var tabData = blockTabData[tabId];
        if (!tabData || tabData.isLocked) { showToast('This block is locked', 'info'); return; }
        var bid = tabData.blockId;

        if (!blockAreaCounters[bid]) blockAreaCounters[bid] = 0;
        blockAreaCounters[bid]++;
        var c = blockAreaCounters[bid];
        var du = blockDisplayUnits[bid] || originalCampusAreaUnit;

        var contentEl = document.getElementById('blockContent-' + tabId);
        if (!contentEl) return;

        var container = contentEl.querySelector('.block-areas-container[data-block-id="' + bid + '"]');
        if (!container) return;

        var card = document.createElement('div');
        card.className = 'dynamic-card area-card';
        var areaId = generateUUID();
        card.setAttribute('data-area-uuid', areaId);
        card.innerHTML = '\n            <div class="card-badge-sm">Area #' + c + '</div>\n            <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;">\n                <div class="form-group"><label>Area Name</label><input type="text" class="form-control area-name" placeholder="e.g., Playground"></div>\n                <div class="form-group"><label>Area Unit</label><select class="form-control area-unit" onchange="handleBlockAreaUnitChange(this,' + bid + ')">' + generateUnitOptions(du) + '</select></div>\n                <div class="form-group"><label>Area Value</label><input type="number" class="form-control area-value" placeholder="Enter area" min="0" step="0.01" onchange="updateBlockAreaSummary(\'' + tabId + '\')"></div>\n            </div>\n            <div style="text-align:right;margin-top:0.5rem;">\n                <button type="button" class="btn-outline-danger" onclick="this.closest(\'.dynamic-card\').remove();updateBlockAreaSummary(\'' + tabId + '\');"><i class="fas fa-trash"></i></button>\n            </div>\n        ';
        container.appendChild(card);
        updateBlockAreaSummary(tabId);
        tabData.isSaved = false;
        updateBlockTabStatus(tabId);
    }

    function addGateToBlockTab(tabId) {
        var tabData = blockTabData[tabId];
        if (!tabData || tabData.isLocked) { showToast('This block is locked', 'info'); return; }
        var bid = tabData.blockId;

        if (!blockGateCounters[bid]) blockGateCounters[bid] = 0;
        blockGateCounters[bid]++;
        var c = blockGateCounters[bid];

        var contentEl = document.getElementById('blockContent-' + tabId);
        if (!contentEl) return;

        var container = contentEl.querySelector('.block-gates-container[data-block-id="' + bid + '"]');
        if (!container) return;

        var card = document.createElement('div');
        card.className = 'dynamic-card gate-card';
        var gateId = generateUUID();
        card.setAttribute('data-gate-uuid', gateId);
        card.innerHTML = '\n            <div class="card-badge-sm">Gate #' + c + '</div>\n            <div class="card-row" style="grid-template-columns:1fr 1fr;">\n                <div class="form-group"><label>Gate Name</label><input type="text" class="form-control gate-name" placeholder="e.g., Main Entrance"></div>\n                <div class="form-group"><label>Gate Number</label><input type="text" class="form-control gate-number" placeholder="e.g., G-01"></div>\n            </div>\n            <div style="text-align:right;margin-top:0.5rem;">\n                <button type="button" class="btn-outline-danger" onclick="this.closest(\'.dynamic-card\').remove();"><i class="fas fa-trash"></i></button>\n            </div>\n        ';
        container.appendChild(card);
        tabData.isSaved = false;
        updateBlockTabStatus(tabId);
    }

    function addCustomAmenityToBlockTab(tabId) {
        var tabData = blockTabData[tabId];
        if (!tabData || tabData.isLocked) { showToast('This block is locked', 'info'); return; }
        var bid = tabData.blockId;

        if (!blockCustomAmenityCounters[bid]) blockCustomAmenityCounters[bid] = 0;
        blockCustomAmenityCounters[bid]++;
        var c = blockCustomAmenityCounters[bid];

        var contentEl = document.getElementById('blockContent-' + tabId);
        if (!contentEl) return;

        var container = contentEl.querySelector('.block-custom-amenities-container[data-block-id="' + bid + '"]');
        if (!container) return;

        var card = document.createElement('div');
        card.className = 'custom-amenity-card';
        var amenityId = generateUUID();
        card.setAttribute('data-amenity-uuid', amenityId);
        card.innerHTML = '\n            <div class="card-badge-sm">Custom #' + c + '</div>\n            <div class="card-row" style="grid-template-columns:2fr 1fr;">\n                <div class="form-group"><label>Amenity Name</label><input type="text" class="form-control custom-amenity-name" placeholder="e.g., Projector"></div>\n                <div class="form-group"><label>Quantity</label><input type="number" class="form-control custom-amenity-qty" value="1" min="1"></div>\n            </div>\n            <div style="text-align:right;margin-top:0.5rem;">\n                <button type="button" class="btn-outline-danger" onclick="this.closest(\'.custom-amenity-card\').remove()"><i class="fas fa-trash"></i></button>\n            </div>\n        ';
        container.appendChild(card);
        tabData.isSaved = false;
        updateBlockTabStatus(tabId);
    }

    function addNewBlockTab() {
        var totalConfiguredBlocks = buildingData && buildingData.number_of_blocks ? parseInt(buildingData.number_of_blocks) : (configuredNumberOfBlocks || 1);
        var currentBlockCount = Object.keys(blockTabData).length;
        
        if (currentBlockCount >= totalConfiguredBlocks) {
            showToast('Maximum number of blocks (' + totalConfiguredBlocks + ') already reached.', 'info');
            return;
        }
        
        var tabId = nextBlockTabId++;
        var bid = 'new_' + Date.now();
        var blockData = {
            id: bid,
            name: 'Block ' + (Object.keys(blockTabData).length + 1),
            code: buildingData && buildingData.code ? buildingData.code + '-BLK' + (Object.keys(blockTabData).length + 1) : '',
            description: '',
            status: 'active',
            floors: 1,
            area_value: 0,
            area_unit: originalCampusAreaUnit,
            additional_areas: [],
            gates: [],
            custom_amenities: [],
            allocated_amenities: {},
            allocated_facility_entries: []
        };
        blockDataMap[bid] = blockData;
        existingBlockIds.push(bid);
        blockDisplayUnits[bid] = originalCampusAreaUnit;
        if (!blockAllocations[bid]) blockAllocations[bid] = {};

        blockTabData[tabId] = {
            blockId: bid,
            blockData: blockData,
            isSaved: false,
            isNew: true,
            isLocked: false,
            tabElement: null,
            contentElement: null
        };

        createBlockTab(tabId);
        document.getElementById('totalBlocksCount').textContent = Object.keys(blockTabData).length;
        activateBlockTab(tabId);
        updateGlobalAllocationSummary();
        updateRemainingArea();
        showToast('New block tab created! Fill in the details and click Save.', 'info');
    }

    function removeBlockTab(tabId) {
        var tabData = blockTabData[tabId];
        if (!tabData) return;

        if (Object.keys(blockTabData).length <= 1) {
            showToast('At least one block is required', 'error');
            return;
        }

        if (tabData.isLocked) {
            showToast('Cannot remove a locked block', 'error');
            return;
        }

        if (!confirm('Are you sure you want to remove this block?')) return;

        var bid = tabData.blockId;
        if (!bid.toString().startsWith('new_')) {
            fetch(API_BASE_URL + '/blocks/' + bid, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).catch(function(e) { console.warn('Could not delete block from backend:', e); });
        }

        removeBlockFromStorage(bid);

        delete blockDataMap[bid];
        delete blockAllocations[bid];
        delete blockDisplayUnits[bid];
        var idx = existingBlockIds.indexOf(bid);
        if (idx > -1) existingBlockIds.splice(idx, 1);

        if (tabData.tabElement) tabData.tabElement.remove();
        if (tabData.contentElement) tabData.contentElement.remove();

        delete blockTabData[tabId];
        document.getElementById('totalBlocksCount').textContent = Object.keys(blockTabData).length;

        var remainingIds = Object.keys(blockTabData);
        if (remainingIds.length > 0) {
            activateBlockTab(remainingIds[0]);
        }

        updateGlobalAllocationSummary();
        updateRemainingArea();
        showToast('Block removed', 'info');
    }

    // ============================================================
    // ===== SAVE BLOCK TAB (WITH AREA VALIDATION) =====
    // ============================================================

    async function saveBlockTab(tabId) {
        if (isSavingBlock) return;
        isSavingBlock = true;

        var btn = document.getElementById('saveBlockBtn-' + tabId);
        var orig = btn ? btn.innerHTML : 'Save Block';
        if (btn) { 
            btn.innerHTML = '<span class="loading-spinner"></span> Saving...';
            btn.disabled = true; 
        }

        try {
            var data = getBlockDataFromTab(tabId);
            if (!data || !data.name) {
                showToast('Please fill in the block name', 'error');
                return;
            }

            var tabData = blockTabData[tabId];
            if (tabData.isLocked) {
                showToast('This block is already saved and locked', 'info');
                return;
            }

            var bid = tabData.blockId;
            var buildingId = document.getElementById('buildingId').value;

            if (!buildingId) {
                showToast('Please select a building first', 'error');
                return;
            }

            // Validate block area against campus total
            var blockAreaInOriginal = convertArea(data.area_value, data.area_unit, originalCampusAreaUnit);
            var validation = validateBlockArea(bid, blockAreaInOriginal, originalCampusAreaUnit);
            
            if (!validation.valid) {
                showToast(validation.message, 'error');
                return;
            }

            var isNewBlock = false;
            var existingId = null;

            if (bid && typeof bid === 'number' && !isNaN(bid)) {
                existingId = bid;
                isNewBlock = false;
            } else if (bid && bid.toString().startsWith('new_')) {
                isNewBlock = true;
            } else if (!tabData.isSaved) {
                isNewBlock = true;
            } else if (bid && !isNaN(parseInt(bid))) {
                var parsedId = parseInt(bid);
                if (parsedId > 0) {
                    existingId = parsedId;
                    isNewBlock = false;
                } else {
                    isNewBlock = true;
                }
            } else {
                isNewBlock = true;
            }

            var blockData = {
                name: data.name,
                code: data.code || '',
                description: data.description || '',
                status: data.status || 'active',
                floors: parseInt(data.floors) || 1,
                area_value: parseFloat(data.area_value) || 0,
                area_unit: data.area_unit || 'sq_ft',
                additional_areas: data.additional_areas || [],
                gates: data.gates || [],
                custom_amenities: data.custom_amenities || [],
                allocated_amenities: data.allocated_amenities || {},
                allocated_facility_entries: data.allocated_facility_entries || []
            };

            var url, method, payload;

            if (isNewBlock || !existingId) {
                url = API_BASE_URL + '/blocks';
                method = 'POST';
                payload = {
                    building_id: parseInt(buildingId),
                    blocks: [blockData]
                };
            } else {
                url = API_BASE_URL + '/blocks/' + existingId;
                method = 'PUT';
                payload = {
                    building_id: parseInt(buildingId),
                    name: blockData.name,
                    code: blockData.code,
                    description: blockData.description,
                    status: blockData.status,
                    total_floors: blockData.floors,
                    total_area: blockData.area_value,
                    area_unit: blockData.area_unit,
                    additional_areas: blockData.additional_areas,
                    gates: blockData.gates,
                    custom_amenities: blockData.custom_amenities,
                    allocated_amenities: blockData.allocated_amenities,
                    allocated_facilities: blockData.allocated_facility_entries
                };
            }

            var response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            });

            var result = await response.json();

            if (result.success) {
                var savedBlock = result.data;
                if (Array.isArray(savedBlock) && savedBlock.length > 0) {
                    savedBlock = savedBlock[0];
                }

                if (savedBlock && savedBlock.id) {
                    var newId = savedBlock.id;
                    var oldId = tabData.blockId;
                    
                    if (oldId !== newId) {
                        removeBlockFromStorage(oldId);
                        
                        delete blockDataMap[oldId];
                        delete blockAllocations[oldId];
                        delete blockDisplayUnits[oldId];
                        
                        var idx = existingBlockIds.indexOf(oldId);
                        if (idx > -1) existingBlockIds.splice(idx, 1);
                        
                        tabData.blockId = newId;
                        blockDataMap[newId] = savedBlock;
                        blockDisplayUnits[newId] = savedBlock.area_unit || originalCampusAreaUnit;
                        
                        blockAllocations[newId] = {};
                        if (savedBlock.allocated_amenities) {
                            var amenities = typeof savedBlock.allocated_amenities === 'string' 
                                ? JSON.parse(savedBlock.allocated_amenities) 
                                : savedBlock.allocated_amenities || {};
                            var aKeys = Object.keys(amenities);
                            for (var i = 0; i < aKeys.length; i++) {
                                var key = aKeys[i];
                                blockAllocations[newId][key] = { allocated: true, quantity: amenities[key] };
                            }
                        }
                        if (savedBlock.allocated_facilities) {
                            var facilities = typeof savedBlock.allocated_facilities === 'string' 
                                ? JSON.parse(savedBlock.allocated_facilities) 
                                : savedBlock.allocated_facilities || [];
                            for (var j = 0; j < facilities.length; j++) {
                                var entryId = facilities[j];
                                blockAllocations[newId][entryId] = { allocated: true, quantity: 1 };
                            }
                        }
                        
                        existingBlockIds.push(newId);
                        
                        var contentEl = document.getElementById('blockContent-' + tabId);
                        var oldBlockEl = contentEl ? contentEl.querySelector('#block-' + oldId) : null;
                        if (oldBlockEl) {
                            oldBlockEl.id = 'block-' + newId;
                            oldBlockEl.setAttribute('data-block-id', newId);
                        }
                    } else {
                        blockDataMap[newId] = savedBlock;
                    }

                    tabData.blockData = savedBlock;
                    saveLockedBlockToStorage(tabData.blockId, savedBlock);
                }

                tabData.isSaved = true;
                tabData.isNew = false;
                tabData.isLocked = true;
                
                lockBlockTab(tabId);
                
                updateBlockTabStatus(tabId);
                updateGlobalAllocationSummary();
                updateRemainingArea();
                
                showToast('Block "' + data.name + '" saved and locked successfully!', 'success');

                refreshBlockTab(tabId);
                
                var nextTabId = getNextUnsavedBlockTab(tabId);
                if (nextTabId) {
                    setTimeout(function() {
                        activateBlockTab(nextTabId);
                        showToast('Moving to next unsaved block...', 'info');
                    }, 800);
                } else {
                    setTimeout(function() {
                        checkAllBlocksSaved();
                    }, 800);
                }

            } else {
                showToast(result.message || 'Failed to save block', 'error');
                var statusEl = document.getElementById('blockSaveStatus-' + tabId);
                if (statusEl) {
                    statusEl.className = 'save-status error';
                    statusEl.innerHTML = '<i class="fas fa-exclamation-circle"></i><span>✖ Error: ' + (result.message || 'Failed to save') + '</span>';
                }
            }
        } catch (e) {
            console.error('Error saving block:', e);
            showToast('An error occurred while saving: ' + (e.message || 'Unknown error'), 'error');
        } finally {
            if (btn) { 
                btn.innerHTML = orig;
                btn.disabled = false; 
            }
            isSavingBlock = false;
        }
    }

    function refreshBlockTab(tabId) {
        var tabData = blockTabData[tabId];
        if (!tabData) return;

        var wasSaved = tabData.isSaved;
        var wasLocked = tabData.isLocked;

        var contentEl = document.getElementById('blockContent-' + tabId);
        if (!contentEl) return;

        contentEl.innerHTML = buildBlockTabContent(tabId, tabData.blockData);
        updateBlockAreaSummary(tabId);
        updateBlockAllocationSummary(tabData.blockId);
        updateBlockTabStatus(tabId);

        tabData.isSaved = wasSaved;
        tabData.isLocked = wasLocked;

        if (tabData.isLocked) {
            setTimeout(function() {
                lockBlockTab(tabId);
            }, 50);
        }

        var tabElement = tabData.tabElement;
        if (tabElement) {
            var nameSpan = tabElement.querySelector('span:last-child');
            if (nameSpan) {
                var blockName = tabData.blockData.name || 'Block';
                var lockHtml = tabData.isLocked ? ' <span class="tab-lock">🔒</span>' : '';
                var currentText = nameSpan.textContent.trim();
                if (currentText !== blockName) {
                    nameSpan.innerHTML = blockName + lockHtml;
                }
            }
        }
    }

    function getNextUnsavedBlockTab(currentTabId) {
        var tabIds = Object.keys(blockTabData);
        var currentIndex = tabIds.indexOf(String(currentTabId));

        if (currentIndex === -1) currentIndex = 0;

        for (var i = currentIndex + 1; i < tabIds.length; i++) {
            var tabId = tabIds[i];
            if (!blockTabData[tabId].isSaved && !blockTabData[tabId].isLocked) {
                return tabId;
            }
        }

        for (var j = 0; j < currentIndex; j++) {
            var tabId2 = tabIds[j];
            if (!blockTabData[tabId2].isSaved && !blockTabData[tabId2].isLocked) {
                return tabId2;
            }
        }

        return null;
    }

    function checkAllBlocksSaved() {
        var allSaved = true;
        var tabIds = Object.keys(blockTabData);
        for (var i = 0; i < tabIds.length; i++) {
            if (!blockTabData[tabIds[i]].isSaved) {
                allSaved = false;
                break;
            }
        }

        if (allSaved) {
            document.getElementById('navigationModal').querySelector('p').textContent = 
                'You have successfully saved all blocks. Would you like to proceed to the "Add Floors" section?';
            showModal();
        }
    }

    function updateMaxBlocksIndicator() {
        var totalConfiguredBlocks = buildingData && buildingData.number_of_blocks ? parseInt(buildingData.number_of_blocks) : (configuredNumberOfBlocks || 1);
        var currentBlockCount = Object.keys(blockTabData).length;
        var indicator = document.getElementById('maxBlocksIndicator');
        if (indicator) {
            indicator.textContent = '(Max: ' + totalConfiguredBlocks + ')';
        }
        var addBtn = document.getElementById('addBlockBtn');
        if (addBtn) {
            if (currentBlockCount >= totalConfiguredBlocks) {
                addBtn.disabled = true;
                addBtn.style.opacity = '0.5';
                addBtn.style.cursor = 'not-allowed';
            } else {
                addBtn.disabled = false;
                addBtn.style.opacity = '1';
                addBtn.style.cursor = 'pointer';
            }
        }
    }

    // ============================================================
    // ===== FLOOR FUNCTIONS =====
    // ============================================================

    async function loadBlocksForFloors() {
        return new Promise(async function(resolve, reject) {
            var bi = document.getElementById('floorBuildingId').value;
            var bs = document.getElementById('blockSelect');
            document.getElementById('existingFloorsSection').style.display = 'none';
            document.getElementById('blockAllocSummaryForFloors').style.display = 'none';
            document.getElementById('floorTabsSection').style.display = 'none';
            floorDataMap = {};
            existingFloorIds = [];
            floorTabData = {};
            nextFloorTabId = 1;
            activeFloorTabId = null;

            if (!bi) {
                bs.innerHTML = '<option value="">Select a building block</option>';
                resolve();
                return;
            }
            bs.innerHTML = '<option value="">Loading...</option>';
            try {
                var r = await fetch(API_BASE_URL + '/rooms/blocks/' + bi, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                var result = await r.json();
                if (result.success) {
                    var o = '<option value="">Select a building block</option>';
                    for (var i = 0; i < result.data.length; i++) {
                        var b = result.data[i];
                        o += '<option value="' + b.id + '">' + escapeHtml(b.name) + '</option>';
                    }
                    bs.innerHTML = o;
                    resolve();
                } else { 
                    bs.innerHTML = '<option value="">No blocks found</option>';
                    resolve();
                }
            } catch (e) { 
                bs.innerHTML = '<option value="">Error</option>';
                reject(e);
            }
        });
    }

    async function loadBlockForFloors() {
        var blockId = document.getElementById('blockSelect').value;
        var existingSection = document.getElementById('existingFloorsSection');
        var allocSummary = document.getElementById('blockAllocSummaryForFloors');
        var floorTabsSection = document.getElementById('floorTabsSection');

        existingSection.style.display = 'none';
        allocSummary.style.display = 'none';
        floorTabsSection.style.display = 'none';
        floorDataMap = {};
        existingFloorIds = [];
        floorTabData = {};
        nextFloorTabId = 1;
        activeFloorTabId = null;
        currentBlockForFloors = null;
        floorBlockId = blockId;

        clearLockedFloorsStorage();

        if (!blockId) return;

        try {
            var r = await fetch(API_BASE_URL + '/blocks/' + blockId + '/details', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            var result = await r.json();
            if (result.success && result.data) {
                currentBlockForFloors = result.data;
                var block = result.data;
                var allocs = block.allocated_amenities || {};
                if (typeof allocs === 'string') {
                    try { allocs = JSON.parse(allocs); } catch(e) { allocs = {}; }
                }
                var allocFacilities = block.allocated_facility_entries || [];
                if (typeof allocFacilities === 'string') {
                    try { allocFacilities = JSON.parse(allocFacilities); } catch(e) { allocFacilities = []; }
                }

                var summaryHtml = '';
                var aKeys = Object.keys(allocs);
                for (var i = 0; i < aKeys.length; i++) {
                    var key = aKeys[i];
                    var qty = allocs[key] || 0;
                    var displayName = key.replace(/_/g, ' ').replace(/\b\w/g, function(l) { return l.toUpperCase(); });
                    summaryHtml += '<span class="block-alloc-summary"><i class="fas fa-cube"></i> ' + displayName + ': ' + qty + '</span>';
                }
                for (var j = 0; j < allocFacilities.length; j++) {
                    var entryId = allocFacilities[j];
                    var name = entryId;
                    var fTypes = Object.keys(campusFacilityEntries);
                    for (var k = 0; k < fTypes.length; k++) {
                        var type = fTypes[k];
                        var found = false;
                        var entries = campusFacilityEntries[type];
                        for (var l = 0; l < entries.length; l++) {
                            if (entries[l].id === entryId) { found = true;
                                name = entries[l].name; break; }
                        }
                        if (found) break;
                    }
                    summaryHtml += '<span class="block-alloc-summary" style="background:linear-gradient(135deg,#fef3c7,#fde68a);"><i class="fas fa-building"></i> ' + escapeHtml(name) + '</span>';
                }
                if (summaryHtml) {
                    document.getElementById('blockAllocSummaryDetails').innerHTML = summaryHtml;
                    allocSummary.style.display = 'block';
                } else {
                    allocSummary.style.display = 'none';
                }

                await loadExistingFloorsForBlock(blockId);

                var numberOfFloors = parseInt(block.total_floors) || 1;
                initFloorTabs(numberOfFloors);

                floorTabsSection.style.display = 'block';
                document.getElementById('totalFloorsCount').textContent = Object.keys(floorTabData).length;

                var hasUnsavedFloors = false;
                var tabIds = Object.keys(floorTabData);
                for (var i = 0; i < tabIds.length; i++) {
                    if (!floorTabData[tabIds[i]].isSaved && !floorTabData[tabIds[i]].isLocked) {
                        hasUnsavedFloors = true;
                        break;
                    }
                }
                
                if (!hasUnsavedFloors && tabIds.length > 0) {
                    showToast('🎉 All floors have been saved and locked!', 'success');
                }

            } else {
                showToast('Failed to load block data', 'error');
            }
        } catch (e) {
            console.error('Error loading block:', e);
            showToast('Error loading block', 'error');
        }
    }

    // Add CSS for locked floors
    var lockedFloorStyle = document.createElement('style');
    lockedFloorStyle.textContent = `
        .floor-locked-message {
            background: #dcfce7;
            border: 2px solid #86efac;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 1rem;
            text-align: center;
            color: #065f46;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .tab.locked {
            opacity: 0.8;
            background: #f0fdf4;
            border-color: #86efac;
            cursor: not-allowed;
            pointer-events: none;
        }
        .tab.locked .tab-status {
            background: #3b82f6;
            color: white;
        }
        .tab.locked .tab-lock {
            color: #3b82f6;
            margin-left: 4px;
        }
        .tab.locked .tab-remove-btn {
            display: none;
        }
        .tab-content.locked-content {
            opacity: 0.7;
            pointer-events: none;
        }
        .tab-content.locked-content .btn {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .tab-content.locked-content input,
        .tab-content.locked-content select,
        .tab-content.locked-content textarea {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .save-status.locked-status {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }
    `;
    document.head.appendChild(lockedFloorStyle);

    async function loadExistingFloorsForBlock(blockId) {
        var existingSection = document.getElementById('existingFloorsSection');
        var countSpan = document.getElementById('existingFloorsCount');
        var noFloorsMsg = document.getElementById('noExistingFloors');
        var existingContainer = document.getElementById('existingFloorsContainer');
        try {
            var r = await fetch(API_BASE_URL + '/floors/by-block/' + blockId, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            var result = await r.json();
            if (result.success && result.data && result.data.length > 0) {
                countSpan.textContent = result.data.length + ' floors';
                existingSection.style.display = 'block';
                var h = '';
                for (var i = 0; i < result.data.length; i++) {
                    var f = result.data[i];
                    var isBasement = f.is_basement ? '🏚️ Basement' : '';
                    h += '<div class="existing-floor-card"><span class="existing-badge"><i class="fas fa-check-circle"></i> Existing</span><div class="floor-info"><div><div class="floor-name"><i class="fas fa-layer-group"></i> ' + escapeHtml(f.floor_number || f.name) + ' ' + isBasement + '</div><div class="floor-meta">Rooms: ' + (f.total_rooms || f.rooms || 'N/A') + '</div></div></div></div>';
                }
                existingContainer.innerHTML = h;
                noFloorsMsg.style.display = 'none';

                for (var j = 0; j < result.data.length; j++) {
                    var floor = result.data[j];
                    var fid = floor.id;
                    floorDataMap[fid] = floor;
                    existingFloorIds.push(fid);
                }
                return result.data.length;
            } else {
                existingSection.style.display = 'none';
                noFloorsMsg.style.display = 'block';
                countSpan.textContent = '0 floors';
                existingContainer.innerHTML = '';
                return 0;
            }
        } catch (e) {
            existingSection.style.display = 'none';
            noFloorsMsg.style.display = 'block';
            countSpan.textContent = '0 floors';
            return 0;
        }
    }

    function initFloorTabs(numberOfFloors) {
        var tabsContainer = document.getElementById('floorTabs');
        var contentsContainer = document.getElementById('floorContents');
        tabsContainer.innerHTML = '';
        contentsContainer.innerHTML = '';
        floorTabData = {};
        nextFloorTabId = 1;
        activeFloorTabId = null;

        var lockedFloors = getLockedFloorsFromStorage();

        for (var i = 1; i <= numberOfFloors; i++) {
            var tabId = nextFloorTabId++;
            var fid;
            var floorData;

            if (existingFloorIds.length >= i) {
                fid = existingFloorIds[i - 1];
                floorData = floorDataMap[fid];
            } else {
                fid = 'new_' + Date.now() + '_' + i;
                floorData = {
                    id: fid,
                    floor_number: 'Floor ' + i,
                    rooms: '',
                    description: '',
                    area_value: '',
                    area_unit: 'sq_ft',
                    additional_areas: [],
                    gates: [],
                    custom_amenities: [],
                    allocated_amenities: {},
                    allocated_facility_entries: [],
                    is_basement: false
                };
                floorDataMap[fid] = floorData;
                existingFloorIds.push(fid);
            }

            var isSaved = floorData.id && !floorData.id.toString().startsWith('new_');
            var isLocked = isSaved || isFloorLockedInStorage(floorData.id);

            floorTabData[tabId] = {
                floorId: fid,
                floorData: floorData,
                isSaved: isSaved,
                isNew: !isSaved,
                isLocked: isLocked,
                tabElement: null,
                contentElement: null
            };
            createFloorTab(tabId);
        }

        document.getElementById('totalFloorsCount').textContent = Object.keys(floorTabData).length;

        var firstUnsavedTab = null;
        var tabIds = Object.keys(floorTabData);
        for (var i = 0; i < tabIds.length; i++) {
            var tid = tabIds[i];
            if (!floorTabData[tid].isSaved && !floorTabData[tid].isLocked) {
                firstUnsavedTab = tid;
                break;
            }
        }
        
        var firstTabId = firstUnsavedTab || (tabIds.length > 0 ? tabIds[0] : null);
        if (firstTabId) {
            activateFloorTab(firstTabId);
        }
    }

    function createFloorTab(tabId) {
        var tabData = floorTabData[tabId];
        var floor = tabData.floorData;
        var tabsContainer = document.getElementById('floorTabs');
        var contentsContainer = document.getElementById('floorContents');

        var isLocked = tabData.isLocked || tabData.isSaved;

        var tabEl = document.createElement('div');
        tabEl.className = 'tab';
        tabEl.dataset.tabId = tabId;
        
        if (isLocked) {
            tabEl.classList.add('locked');
            tabEl.style.cursor = 'not-allowed';
            tabEl.style.opacity = '0.8';
            tabEl.title = 'This floor is already saved and locked';
            tabEl.style.pointerEvents = 'none';
        }
        
        var statusClass = isLocked ? 'locked-status' : (tabData.isSaved ? 'saved' : 'pending');
        var statusIcon = isLocked ? 'fa-lock' : (tabData.isSaved ? 'fa-check' : 'fa-clock');
        
        tabEl.innerHTML = '\n        <span class="tab-status ' + statusClass + '">\n            <i class="fas ' + statusIcon + '"></i>\n        </span>\n        <span class="tab-number">#' + tabId + '</span>\n        <span>\n            ' + (floor.floor_number || 'Floor') + '\n            ' + (isLocked ? '<span class="tab-lock"> 🔒</span>' : '') + '\n        </span>\n        ' + (!isLocked && tabData.isNew ? '<button class="tab-remove-btn" onclick="event.stopPropagation();removeFloorTab(' + tabId + ')"><i class="fas fa-times"></i></button>' : '') + '\n    ';
        
        if (isLocked) {
            tabEl.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                showToast('This floor has been saved and locked. You cannot edit it.', 'info');
                return false;
            };
        } else {
            tabEl.onclick = function() { activateFloorTab(tabId); };
        }
        
        tabsContainer.appendChild(tabEl);
        tabData.tabElement = tabEl;

        var contentEl = document.createElement('div');
        contentEl.className = 'tab-content';
        contentEl.dataset.tabId = tabId;
        contentEl.id = 'floorContent-' + tabId;
        contentEl.innerHTML = buildFloorTabContent(tabId, floor);
        contentsContainer.appendChild(contentEl);
        tabData.contentElement = contentEl;

        if (isLocked) {
            setTimeout(function() {
                lockFloorTab(tabId);
            }, 100);
        }
    }

    function activateFloorTab(tabId) {
        if (activeFloorTabId) {
            saveFloorTabData(activeFloorTabId);
        }

        activeFloorTabId = tabId;

        var tabs = document.querySelectorAll('#floorTabs .tab');
        for (var i = 0; i < tabs.length; i++) {
            tabs[i].classList.remove('active');
        }
        var contents = document.querySelectorAll('#floorContents .tab-content');
        for (var j = 0; j < contents.length; j++) {
            contents[j].classList.remove('active');
        }

        var tabData = floorTabData[tabId];
        if (tabData && tabData.tabElement) {
            tabData.tabElement.classList.add('active');
        }
        var contentEl = document.getElementById('floorContent-' + tabId);
        if (contentEl) {
            contentEl.classList.add('active');
        }

        updateFloorAreaSummary(tabId);
    }

    function updateFloorTabLabel(tabId, name) {
        var tabData = floorTabData[tabId];
        if (tabData && tabData.tabElement) {
            var nameSpan = tabData.tabElement.querySelector('span:last-child');
            if (nameSpan) nameSpan.textContent = name || 'Floor';
        }
        if (tabData) {
            tabData.isSaved = false;
            updateFloorTabStatus(tabId);
        }
    }

    function updateFloorTabStatus(tabId) {
        var tabData = floorTabData[tabId];
        if (!tabData) return;

        var statusEl = tabData.tabElement ? tabData.tabElement.querySelector('.tab-status') : null;
        var saveStatusEl = document.getElementById('floorSaveStatus-' + tabId);

        if (statusEl) {
            statusEl.className = 'tab-status ' + (tabData.isSaved ? 'saved' : 'pending');
            statusEl.innerHTML = '<i class="fas ' + (tabData.isSaved ? 'fa-check' : 'fa-clock') + '"></i>';
        }
        if (saveStatusEl) {
            saveStatusEl.className = 'save-status ' + (tabData.isSaved ? 'saved' : 'pending');
            saveStatusEl.innerHTML = '<i class="fas ' + (tabData.isSaved ? 'fa-check-circle' : 'fa-clock') + '"></i><span>' + (tabData.isSaved ? '✓ Saved' : '⏳ Pending Save') + '</span>';
        }
    }

    function toggleFloorBasement(tabId) {
        var cb = document.getElementById('floorIsBasement-' + tabId);
        var tabData = floorTabData[tabId];
        if (!tabData) return;
        var floor = tabData.floorData;
        if (floor) {
            floor.is_basement = cb.checked;
            var floorCard = document.getElementById('floor-' + floor.id);
            if (floorCard) {
                floorCard.classList.toggle('basement', cb.checked);
                var badge = floorCard.querySelector('.card-badge');
                if (badge) badge.textContent = (tabData.isSaved ? 'Existing Floor' : 'New Floor') + (cb.checked ? ' 🏚️ Basement' : '');
            }
            if (cb.checked) {
                var numInput = document.getElementById('floor-' + tabId) ? document.getElementById('floor-' + tabId).querySelector('.floor-number') : null;
                if (numInput && numInput.value.toLowerCase().indexOf('basement') === -1) {
                    var existingBasements = 0;
                    var fKeys = Object.keys(floorTabData);
                    for (var i = 0; i < fKeys.length; i++) {
                        if (floorTabData[fKeys[i]] && floorTabData[fKeys[i]].floorData && floorTabData[fKeys[i]].floorData.is_basement && fKeys[i] !== tabId) {
                            existingBasements++;
                        }
                    }
                    numInput.value = 'Basement ' + (existingBasements + 1);
                    updateFloorTabLabel(tabId, numInput.value);
                }
            }
            tabData.isSaved = false;
            updateFloorTabStatus(tabId);
        }
    }

    function buildFloorAreaDeductionHTML(tabId) {
        return '<div class="area-deduction-summary" id="floorAreaSummary-' + tabId + '">\n            <div class="deduction-item"><span>Total Floor Area:</span><span id="floorTotalArea-' + tabId + '">0.00</span></div>\n            <div class="deduction-item"><span>Facilities Area Deduction:</span><span id="floorFacilityDeduction-' + tabId + '">0.00</span></div>\n            <div class="deduction-item"><span>Additional Areas:</span><span id="floorAdditionalArea-' + tabId + '">0.00</span></div>\n            <div class="deduction-item total-row"><span>Remaining Area:</span><span id="floorRemainingArea-' + tabId + '" class="positive">0.00</span></div>\n        </div>';
    }

    // ============================================================
    // ===== UPDATE FLOOR AREA SUMMARY (WITH BLOCK VALIDATION) =====
    // ============================================================

    function updateFloorAreaSummary(tabId) {
        var tabData = floorTabData[tabId];
        if (!tabData) return;
        var fid = tabData.floorId;
        var floorCard = document.getElementById('floor-' + fid);
        if (!floorCard) return;

        // Get block area for validation
        var blockId = document.getElementById('blockSelect') ? document.getElementById('blockSelect').value : null;
        var blockAreaValue = 0;
        var blockAreaUnit = 'sq_ft';
        
        if (currentBlockForFloors) {
            blockAreaValue = parseFloat(currentBlockForFloors.area_value) || 0;
            blockAreaUnit = currentBlockForFloors.area_unit || 'sq_ft';
        } else if (blockId && blockDataMap[blockId]) {
            blockAreaValue = parseFloat(blockDataMap[blockId].area_value) || 0;
            blockAreaUnit = blockDataMap[blockId].area_unit || 'sq_ft';
        }

        var areaValue = parseFloat(floorCard.querySelector('.floor-area-value') ? floorCard.querySelector('.floor-area-value').value : 0) || 0;
        var areaUnit = floorCard.querySelector('.floor-area-unit') ? floorCard.querySelector('.floor-area-unit').value : 'sq_ft';
        
        // Convert block area to floor's unit for comparison
        var blockAreaInFloorUnit = convertArea(blockAreaValue, blockAreaUnit, areaUnit);
        
        var additionalArea = 0;
        var areaValues = floorCard.querySelectorAll('.floor-areas-container .area-value');
        for (var i = 0; i < areaValues.length; i++) {
            var val = parseFloat(areaValues[i].value);
            if (!isNaN(val)) additionalArea += val;
        }

        var facilityDeduction = 0;
        var floorData = tabData.floorData || {};
        var selectedFacilities = floorData.allocated_facility_entries || [];
        for (var j = 0; j < selectedFacilities.length; j++) {
            var entryId = selectedFacilities[j];
            var fTypes = Object.keys(campusFacilityEntries);
            for (var k = 0; k < fTypes.length; k++) {
                var type = fTypes[k];
                var found = false;
                var entries = campusFacilityEntries[type];
                for (var l = 0; l < entries.length; l++) {
                    if (entries[l].id === entryId && entries[l].area) {
                        found = true;
                        var areaVal = parseFloat(entries[l].area);
                        if (!isNaN(areaVal) && areaVal > 0) {
                            var areaInFloorUnit = convertArea(areaVal, campusAreaUnit || 'sq_ft', areaUnit);
                            facilityDeduction += areaInFloorUnit;
                        }
                        break;
                    }
                }
                if (found) break;
            }
        }

        var remaining = areaValue - additionalArea - facilityDeduction;
        var totalEl = document.getElementById('floorTotalArea-' + tabId);
        var facilityEl = document.getElementById('floorFacilityDeduction-' + tabId);
        var additionalEl = document.getElementById('floorAdditionalArea-' + tabId);
        var remainingEl = document.getElementById('floorRemainingArea-' + tabId);

        if (totalEl) totalEl.textContent = areaValue.toFixed(2) + ' ' + formatUnit(areaUnit);
        if (facilityEl) facilityEl.textContent = facilityDeduction.toFixed(2) + ' ' + formatUnit(areaUnit);
        if (additionalEl) additionalEl.textContent = additionalArea.toFixed(2) + ' ' + formatUnit(areaUnit);
        if (remainingEl) {
            remainingEl.textContent = remaining.toFixed(2) + ' ' + formatUnit(areaUnit);
            remainingEl.className = remaining >= 0 ? 'positive' : 'negative';
        }

        // Validate floor area against block area
        var existingWarning = floorCard.querySelector('.floor-area-validation-warning');
        if (existingWarning) existingWarning.remove();

        if (areaValue > blockAreaInFloorUnit + 0.01 && blockAreaInFloorUnit > 0) {
            var warningEl = document.createElement('div');
            warningEl.className = 'floor-area-validation-warning alert-error';
            warningEl.innerHTML = '<i class="fas fa-exclamation-triangle"></i> <div><strong>Floor Area Exceeded!</strong><br>Floor area (' + areaValue.toFixed(2) + ' ' + formatUnit(areaUnit) + ') exceeds block area (' + blockAreaInFloorUnit.toFixed(2) + ' ' + formatUnit(areaUnit) + '). Please reduce the floor area.</div>';
            
            var floorCardContent = floorCard.querySelector('.area-deduction-summary');
            if (floorCardContent) {
                floorCardContent.parentNode.insertBefore(warningEl, floorCardContent.nextSibling);
            }
        } else if (blockAreaInFloorUnit > 0) {
            var infoEl = document.createElement('div');
            infoEl.className = 'floor-area-validation-warning alert-info';
            var remainingBlockArea = blockAreaInFloorUnit - areaValue;
            infoEl.innerHTML = '<i class="fas fa-info-circle"></i> Block area remaining after this floor: <strong>' + remainingBlockArea.toFixed(2) + ' ' + formatUnit(areaUnit) + '</strong> (Block total: ' + blockAreaInFloorUnit.toFixed(2) + ' ' + formatUnit(areaUnit) + ')';
            
            var floorCardContent2 = floorCard.querySelector('.area-deduction-summary');
            if (floorCardContent2) {
                floorCardContent2.parentNode.insertBefore(infoEl, floorCardContent2.nextSibling);
            }
        }

        return remaining;
    }

    // ============================================================
    // ===== ON FLOOR AREA VALUE CHANGE (REAL-TIME VALIDATION) =====
    // ============================================================

    function onFloorAreaValueChange(input, tabId) {
        var tabData = floorTabData[tabId];
        if (!tabData) return;
        
        var fid = tabData.floorId;
        var floorCard = document.getElementById('floor-' + fid);
        if (!floorCard) return;
        
        var newValue = parseFloat(input.value) || 0;
        var unitSelect = floorCard.querySelector('.floor-area-unit');
        var unit = unitSelect ? unitSelect.value : 'sq_ft';
        
        // Get block area
        var blockId = document.getElementById('blockSelect') ? document.getElementById('blockSelect').value : null;
        var blockAreaValue = 0;
        var blockAreaUnit = 'sq_ft';
        
        if (currentBlockForFloors) {
            blockAreaValue = parseFloat(currentBlockForFloors.area_value) || 0;
            blockAreaUnit = currentBlockForFloors.area_unit || 'sq_ft';
        } else if (blockId && blockDataMap[blockId]) {
            blockAreaValue = parseFloat(blockDataMap[blockId].area_value) || 0;
            blockAreaUnit = blockDataMap[blockId].area_unit || 'sq_ft';
        }
        
        // Convert block area to floor's unit
        var blockAreaInFloorUnit = convertArea(blockAreaValue, blockAreaUnit, unit);
        
        if (newValue > blockAreaInFloorUnit + 0.01 && blockAreaInFloorUnit > 0) {
            showToast('Floor area (' + newValue.toFixed(2) + ' ' + formatUnit(unit) + ') exceeds block area (' + blockAreaInFloorUnit.toFixed(2) + ' ' + formatUnit(unit) + ')!', 'error');
            input.value = blockAreaInFloorUnit.toFixed(4);
            input.classList.add('area-error');
            setTimeout(function() {
                input.classList.remove('area-error');
            }, 2000);
        } else {
            input.classList.remove('area-error');
        }
        
        updateFloorAreaSummary(tabId);
    }

    function getFloorDataFromTab(tabId) {
        var tabData = floorTabData[tabId];
        if (!tabData) return null;

        var fid = tabData.floorId;
        var contentEl = document.getElementById('floorContent-' + tabId);
        if (!contentEl) return null;

        var f = contentEl.querySelector('#floor-' + fid);
        if (!f) return null;

        var floorNumberInput = f.querySelector('.floor-number');
        var n = floorNumberInput ? floorNumberInput.value.trim() : '';
        if (!n) return null;

        var isBasement = document.getElementById('floorIsBasement-' + tabId) ? document.getElementById('floorIsBasement-' + tabId).checked : false;

        var allocs = {};
        var facilityEntries = [];
        var allocChecks = f.querySelectorAll('.floor-alloc-check');
        for (var i = 0; i < allocChecks.length; i++) {
            var cb = allocChecks[i];
            var key = cb.getAttribute('data-key');
            var isFacility = cb.getAttribute('data-facility') === 'true';
            if (cb.checked) {
                var item = cb.closest('.allocation-item');
                var qtyInput = item ? item.querySelector('.floor-alloc-qty') : null;
                var qty = qtyInput ? parseInt(qtyInput.value) || 1 : 1;
                if (isFacility) {
                    if (facilityEntries.indexOf(key) === -1) facilityEntries.push(key);
                } else {
                    allocs[key] = qty;
                }
            }
        }

        var custom = [];
        var customCards = f.querySelectorAll('.floor-custom-amenities-container .custom-amenity-card');
        for (var j = 0; j < customCards.length; j++) {
            var card = customCards[j];
            var nameEl = card.querySelector('.custom-amenity-name');
            var qtyEl = card.querySelector('.custom-amenity-qty');
            var name = nameEl ? nameEl.value.trim() : '';
            var qty = parseInt(qtyEl ? qtyEl.value : 1) || 1;
            var amenityId = card.getAttribute('data-amenity-uuid');
            if (!amenityId) { amenityId = generateUUID();
                card.setAttribute('data-amenity-uuid', amenityId); }
            if (name) custom.push({ id: amenityId, name: name, quantity: qty });
        }

        var areas = [];
        var areaCards = f.querySelectorAll('.floor-areas-container .area-card');
        for (var k = 0; k < areaCards.length; k++) {
            var c2 = areaCards[k];
            var nameEl2 = c2.querySelector('.area-name');
            var valueEl = c2.querySelector('.area-value');
            var unitEl = c2.querySelector('.area-unit');
            var an = nameEl2 ? nameEl2.value.trim() : '';
            var av = parseFloat(valueEl ? valueEl.value : 0) || 0;
            var au = unitEl ? unitEl.value : 'sq_ft';
            var areaId2 = c2.getAttribute('data-area-uuid');
            if (!areaId2) { areaId2 = generateUUID();
                c2.setAttribute('data-area-uuid', areaId2); }
            if (an) areas.push({ id: areaId2, name: an, area: av, unit: au });
        }

        var gates = [];
        var gateCards = f.querySelectorAll('.floor-gates-container .gate-card');
        for (var l = 0; l < gateCards.length; l++) {
            var c3 = gateCards[l];
            var nameEl3 = c3.querySelector('.gate-name');
            var numEl = c3.querySelector('.gate-number');
            var gn = nameEl3 ? nameEl3.value.trim() : '';
            var gnum = numEl ? numEl.value.trim() : '';
            var gateId2 = c3.getAttribute('data-gate-uuid');
            if (!gateId2) { gateId2 = generateUUID();
                c3.setAttribute('data-gate-uuid', gateId2); }
            if (gn || gnum) gates.push({ id: gateId2, name: gn, number: gnum });
        }

        var areaValueInput = f.querySelector('.floor-area-value');
        var areaUnitSelect = f.querySelector('.floor-area-unit');

        return {
            id: fid,
            floor_number: n,
            rooms: f.querySelector('.floor-rooms') ? f.querySelector('.floor-rooms').value : '',
            description: f.querySelector('.floor-description') ? f.querySelector('.floor-description').value.trim() : '',
            area_value: parseFloat(areaValueInput ? areaValueInput.value : 0) || 0,
            area_unit: areaUnitSelect ? areaUnitSelect.value : 'sq_ft',
            additional_areas: areas,
            gates: gates,
            custom_amenities: custom,
            allocated_amenities: allocs,
            allocated_facility_entries: facilityEntries,
            is_basement: isBasement
        };
    }

    function saveFloorTabData(tabId) {
        var data = getFloorDataFromTab(tabId);
        if (data && data.floor_number) {
            var tabData = floorTabData[tabId];
            if (tabData) {
                tabData.floorData = Object.assign({}, tabData.floorData, data);
                floorDataMap[tabData.floorId] = tabData.floorData;
            }
        }
    }

    function getNextUnsavedFloorTab(currentTabId) {
        var tabIds = Object.keys(floorTabData);
        var currentIndex = tabIds.indexOf(String(currentTabId));

        if (currentIndex === -1) currentIndex = 0;

        for (var i = currentIndex + 1; i < tabIds.length; i++) {
            var tabId = tabIds[i];
            if (!floorTabData[tabId].isSaved) {
                return tabId;
            }
        }

        for (var j = 0; j < currentIndex; j++) {
            var tabId2 = tabIds[j];
            if (!floorTabData[tabId2].isSaved) {
                return tabId2;
            }
        }

        return null;
    }

    function checkAllFloorsSaved() {
        var allSaved = true;
        var allLocked = true;
        var tabIds = Object.keys(floorTabData);
        for (var i = 0; i < tabIds.length; i++) {
            if (!floorTabData[tabIds[i]].isSaved) {
                allSaved = false;
            }
            if (!floorTabData[tabIds[i]].isLocked) {
                allLocked = false;
            }
        }

        if (allSaved && allLocked) {
            showToast('🎉 All floors have been saved and locked successfully!', 'success');
            return true;
        } else if (allSaved) {
            showToast('⚠️ Some floors are saved but not locked. This should not happen.', 'info');
            return false;
        }
        return false;
    }

    function addAreaToFloorTab(tabId) {
        var tabData = floorTabData[tabId];
        if (!tabData) return;
        var fid = tabData.floorId;
            
        if (!floorAreaCounters[fid]) floorAreaCounters[fid] = 0;
        floorAreaCounters[fid]++;
        var c = floorAreaCounters[fid];

        var contentEl = document.getElementById('floorContent-' + tabId);
        if (!contentEl) return;

        var container = contentEl.querySelector('.floor-areas-container[data-floor-id="' + fid + '"]');
        if (!container) return;

        var card = document.createElement('div');
        card.className = 'dynamic-card area-card';
        var areaId = generateUUID();
        card.setAttribute('data-area-uuid', areaId);
        card.innerHTML = '<div class="card-badge-sm">Area #' + c + '</div><div class="card-row" style="grid-template-columns:2fr 1fr 1fr;"><div class="form-group"><label>Area Name</label><input type="text" class="form-control area-name" placeholder="e.g., Office"></div><div class="form-group"><label>Area Unit</label><select class="form-control area-unit"><option value="sq_ft">Sq. Ft.</option><option value="sq_m">Sq. M.</option></select></div><div class="form-group"><label>Area Value</label><input type="number" class="form-control area-value" placeholder="Enter area" min="0" step="0.01" onchange="updateFloorAreaSummary(\'' + tabId + '\')"></div></div><div style="text-align:right;margin-top:0.5rem;"><button type="button" class="btn-outline-danger" onclick="this.closest(\'.dynamic-card\').remove();updateFloorAreaSummary(\'' + tabId + '\')"><i class="fas fa-trash"></i></button></div>';
        container.appendChild(card);
        updateFloorAreaSummary(tabId);
        tabData.isSaved = false;
        updateFloorTabStatus(tabId);
    }

    function addGateToFloorTab(tabId) {
        var tabData = floorTabData[tabId];
        if (!tabData) return;
        var fid = tabData.floorId;

        if (!floorGateCounters[fid]) floorGateCounters[fid] = 0;
        floorGateCounters[fid]++;
        var c = floorGateCounters[fid];

        var contentEl = document.getElementById('floorContent-' + tabId);
        if (!contentEl) return;

        var container = contentEl.querySelector('.floor-gates-container[data-floor-id="' + fid + '"]');
        if (!container) return;

        var card = document.createElement('div');
        card.className = 'dynamic-card gate-card';
        var gateId = generateUUID();
        card.setAttribute('data-gate-uuid', gateId);
        card.innerHTML = '<div class="card-badge-sm">Entry #' + c + '</div><div class="card-row" style="grid-template-columns:1fr 1fr;"><div class="form-group"><label>Entry Name</label><input type="text" class="form-control gate-name" placeholder="e.g., Main Door"></div><div class="form-group"><label>Entry Number</label><input type="text" class="form-control gate-number" placeholder="e.g., E-01"></div></div><div style="text-align:right;margin-top:0.5rem;"><button type="button" class="btn-outline-danger" onclick="this.closest(\'.dynamic-card\').remove()"><i class="fas fa-trash"></i></button></div>';
        container.appendChild(card);
        tabData.isSaved = false;
        updateFloorTabStatus(tabId);
    }

    function addCustomAmenityToFloorTab(tabId) {
        var tabData = floorTabData[tabId];
        if (!tabData) return;
        var fid = tabData.floorId;

        if (!floorCustomAmenityCounters[fid]) floorCustomAmenityCounters[fid] = 0;
        floorCustomAmenityCounters[fid]++;
        var c = floorCustomAmenityCounters[fid];

        var contentEl = document.getElementById('floorContent-' + tabId);
        if (!contentEl) return;

        var container = contentEl.querySelector('.floor-custom-amenities-container[data-floor-id="' + fid + '"]');
        if (!container) return;

        var card = document.createElement('div');
        card.className = 'custom-amenity-card';
        var amenityId = generateUUID();
        card.setAttribute('data-amenity-uuid', amenityId);
        card.innerHTML = '<div class="card-badge-sm">Custom #' + c + '</div><div class="card-row" style="grid-template-columns:2fr 1fr;"><div class="form-group"><label>Amenity Name</label><input type="text" class="form-control custom-amenity-name" placeholder="e.g., Projector"></div><div class="form-group"><label>Quantity</label><input type="number" class="form-control custom-amenity-qty" min="1" value="1"></div></div><div style="text-align:right;margin-top:0.5rem;"><button type="button" class="btn-outline-danger" onclick="this.closest(\'.custom-amenity-card\').remove()"><i class="fas fa-trash"></i></button></div>';
        container.appendChild(card);
        tabData.isSaved = false;
        updateFloorTabStatus(tabId);
    }

    function addNewFloorTab() {
        var tabId = nextFloorTabId++;
        var fid = 'new_' + Date.now();
        var floorData = {
            id: fid,
            floor_number: 'Floor ' + (Object.keys(floorTabData).length + 1),
            rooms: '',
            description: '',
            area_value: '',
            area_unit: 'sq_ft',
            additional_areas: [],
            gates: [],
            custom_amenities: [],
            allocated_amenities: {},
            allocated_facility_entries: [],
            is_basement: false
        };
        floorDataMap[fid] = floorData;
        existingFloorIds.push(fid);

        floorTabData[tabId] = {
            floorId: fid,
            floorData: floorData,
            isSaved: false,
            isNew: true,
            tabElement: null,
            contentElement: null
        }; 

        createFloorTab(tabId);
        document.getElementById('totalFloorsCount').textContent = Object.keys(floorTabData).length;
        activateFloorTab(tabId);
        showToast('New floor tab created! Fill in the details and click Save.', 'info');
    }

    function removeFloorTab(tabId) {
        var tabData = floorTabData[tabId];
        if (!tabData) return;

        if (Object.keys(floorTabData).length <= 1) {
            showToast('At least one floor is required', 'error');
            return;
        }

        if (!confirm('Are you sure you want to remove this floor?')) return;

        var fid = tabData.floorId;
        if (!fid.toString().startsWith('new_')) {
            fetch(API_BASE_URL + '/floors/' + fid, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).catch(function(e) { console.warn('Could not delete floor from backend:', e); });
        }

        delete floorDataMap[fid];
        var idx = existingFloorIds.indexOf(fid);
        if (idx > -1) existingFloorIds.splice(idx, 1);

        if (tabData.tabElement) tabData.tabElement.remove();
        if (tabData.contentElement) tabData.contentElement.remove();

        delete floorTabData[tabId];
        document.getElementById('totalFloorsCount').textContent = Object.keys(floorTabData).length;

        var remainingIds = Object.keys(floorTabData);
        if (remainingIds.length > 0) {
            activateFloorTab(remainingIds[0]);
        }

        showToast('Floor removed', 'info');
    }

    // ============================================================
    // ===== SAVE FLOOR TAB (WITH AREA VALIDATION) =====
    // ============================================================

    async function saveFloorTab(tabId) {
        if (isSavingFloor) return;
        isSavingFloor = true;

        var btn = document.getElementById('saveFloorBtn-' + tabId);
        var orig = btn ? btn.innerHTML : 'Save Floor';
        if (btn) { 
            btn.innerHTML = '<span class="loading-spinner"></span> Saving...';
            btn.disabled = true; 
        }

        try {
            var data = getFloorDataFromTab(tabId);
            if (!data || !data.floor_number) {
                showToast('Please fill in the floor name', 'error');
                return;
            }

            var tabData = floorTabData[tabId];
            if (tabData.isLocked) {
                showToast('This floor is already saved and locked', 'info');
                return;
            }

            // Validate floor area against block area
            var blockId = document.getElementById('blockSelect').value;
            var blockAreaValue = 0;
            var blockAreaUnit = 'sq_ft';
            
            if (currentBlockForFloors) {
                blockAreaValue = parseFloat(currentBlockForFloors.area_value) || 0;
                blockAreaUnit = currentBlockForFloors.area_unit || 'sq_ft';
            } else if (blockId && blockDataMap[blockId]) {
                blockAreaValue = parseFloat(blockDataMap[blockId].area_value) || 0;
                blockAreaUnit = blockDataMap[blockId].area_unit || 'sq_ft';
            }
            
            var floorAreaInBlockUnit = convertArea(data.area_value, data.area_unit, blockAreaUnit);
            
            if (floorAreaInBlockUnit > blockAreaValue + 0.01 && blockAreaValue > 0) {
                showToast('Floor area exceeds block area! Block total: ' + blockAreaValue.toFixed(2) + ' ' + formatUnit(blockAreaUnit) + ', Floor: ' + floorAreaInBlockUnit.toFixed(2) + ' ' + formatUnit(blockAreaUnit), 'error');
                return;
            }

            var isNew = tabData.isNew;
            var fid = tabData.floorId;
            var buildingId = document.getElementById('floorBuildingId').value;

            if (!buildingId || !blockId) {
                showToast('Please select a building and block first', 'error');
                return;
            }

            var isExisting = !isNew && !fid.toString().startsWith('new_') && !isNaN(parseInt(fid));

            var payload = {
                building_id: parseInt(buildingId),
                block_id: parseInt(blockId),
                floors: [{
                    floor_number: data.floor_number,
                    rooms: data.rooms || '',
                    description: data.description || '',
                    area_value: parseFloat(data.area_value) || 0,
                    area_unit: data.area_unit || 'sq_ft',
                    additional_areas: data.additional_areas || [],
                    gates: data.gates || [],
                    custom_amenities: data.custom_amenities || [],
                    allocated_amenities: data.allocated_amenities || {},
                    allocated_facility_entries: data.allocated_facility_entries || [],
                    is_basement: data.is_basement || false
                }]
            };

            var url = API_BASE_URL + '/floors/bulk';
            var method = 'POST';

            if (isExisting) {
                url = API_BASE_URL + '/floors/' + fid;
                method = 'PUT';
                var floorData = payload.floors[0];
                delete payload.floors;
                payload.floor_number = floorData.floor_number;
                payload.rooms = floorData.rooms;
                payload.description = floorData.description;
                payload.area_value = floorData.area_value;
                payload.area_unit = floorData.area_unit;
                payload.additional_areas = floorData.additional_areas;
                payload.gates = floorData.gates;
                payload.custom_amenities = floorData.custom_amenities;
                payload.allocated_amenities = floorData.allocated_amenities;
                payload.allocated_facility_entries = floorData.allocated_facility_entries;
                payload.is_basement = floorData.is_basement;
            }

            var response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            });

            var result = await response.json();

            if (result.success) {
                var savedFloor = result.data;
                if (Array.isArray(savedFloor) && savedFloor.length > 0) {
                    savedFloor = savedFloor[0];
                }

                if (savedFloor && savedFloor.id) {
                    var oldFid = tabData.floorId;
                    var newFid = savedFloor.id;
                    if (oldFid !== newFid) {
                        removeFloorFromStorage(oldFid);
                        
                        delete floorDataMap[oldFid];
                        var idx = existingFloorIds.indexOf(oldFid);
                        if (idx > -1) existingFloorIds.splice(idx, 1);

                        tabData.floorId = newFid;
                        floorDataMap[newFid] = savedFloor;
                        existingFloorIds.push(newFid);
                    } else {
                        floorDataMap[newFid] = savedFloor;
                    }
                    
                    tabData.floorData = savedFloor;
                }

                saveLockedFloorToStorage(tabData.floorId, tabData.floorData);
                
                tabData.isSaved = true;
                tabData.isNew = false;
                tabData.isLocked = true;
                
                lockFloorTab(tabId);
                
                updateFloorTabStatus(tabId);
                showToast('Floor "' + data.floor_number + '" saved and locked successfully!', 'success');

                refreshFloorTab(tabId);
                
                var nextTabId = getNextUnsavedFloorTab(tabId);
                if (nextTabId) {
                    setTimeout(function() {
                        activateFloorTab(nextTabId);
                        showToast('Moving to next unsaved floor...', 'info');
                    }, 800);
                } else {
                    setTimeout(function() {
                        checkAllFloorsSaved();
                    }, 800);
                }

            } else {
                showToast(result.message || 'Failed to save floor', 'error');
                var statusEl = document.getElementById('floorSaveStatus-' + tabId);
                if (statusEl) {
                    statusEl.className = 'save-status error';
                    statusEl.innerHTML = '<i class="fas fa-exclamation-circle"></i><span>✖ Error: ' + (result.message || 'Failed to save') + '</span>';
                }
            }
        } catch (e) {
            console.error('Error saving floor:', e);
            showToast('An error occurred while saving: ' + (e.message || 'Unknown error'), 'error');
        } finally {
            if (btn) { 
                btn.innerHTML = orig;
                btn.disabled = false; 
            }
            isSavingFloor = false;
        }
    }

    function refreshFloorTab(tabId) {
        var tabData = floorTabData[tabId];
        if (!tabData) return;

        var contentEl = document.getElementById('floorContent-' + tabId);
        if (!contentEl) return;

        contentEl.innerHTML = buildFloorTabContent(tabId, tabData.floorData);
        updateFloorAreaSummary(tabId);
        updateFloorTabStatus(tabId);

        var tabElement = tabData.tabElement;
        if (tabElement) {
            var nameSpan = tabElement.querySelector('span:last-child');
            if (nameSpan) {
                nameSpan.textContent = tabData.floorData.floor_number || 'Floor';
            }
        }
    }

    // ============================================================
    // ===== BUILD FLOOR TAB CONTENT =====
    // ============================================================

    function buildFloorTabContent(tabId, floor) {
        var fid = floor.id;
        var tabData = floorTabData[tabId];
        var isSaved = tabData.isSaved;
        var statusClass = isSaved ? 'saved' : 'pending';
        var statusIcon = isSaved ? 'fa-check-circle' : 'fa-clock';
        var statusText = isSaved ? '✓ Saved' : '⏳ Pending Save';

        var allocHtml = '';
        if (currentBlockForFloors) {
            var block = currentBlockForFloors;
            var allocs = block.allocated_amenities || {};
            if (typeof allocs === 'string') {
                try { allocs = JSON.parse(allocs); } catch(e) { allocs = {}; }
            }
            var allocFacilities = block.allocated_facility_entries || [];
            if (typeof allocFacilities === 'string') {
                try { allocFacilities = JSON.parse(allocFacilities); } catch(e) { allocFacilities = []; }
            }
            var floorAllocs = floor.allocated_amenities || {};
            var floorFacilities = floor.allocated_facility_entries || [];
            var amenityKeys = Object.keys(allocs);
            if (amenityKeys.length > 0) {
                allocHtml += '<div style="margin-bottom:6px;font-weight:600;font-size:0.8rem;color:var(--text-dark);"><i class="fas fa-concierge-bell"></i> Block Amenities</div>';
                for (var i = 0; i < amenityKeys.length; i++) {
                    var key = amenityKeys[i];
                    var total = allocs[key] || 0;
                    var floorQty = floorAllocs[key] || 0;
                    var displayName = key.replace(/_/g, ' ').replace(/\b\w/g, function(l) { return l.toUpperCase(); });
                    var isChecked = floorQty > 0;
                    allocHtml += '<div class="allocation-item">\n                        <input type="checkbox" class="floor-alloc-check" data-floor="' + fid + '" data-key="' + key + '" data-total="' + total + '" ' + (isChecked ? 'checked' : '') + ' onchange="updateFloorAreaSummary(\'' + tabId + '\')">\n                        <span class="alloc-name">' + displayName + '</span>\n                        <div class="alloc-stats">\n                            <span class="used">Allocated: ' + total + '</span>\n                        </div>\n                        <input type="number" class="floor-alloc-qty ' + (isChecked ? 'show' : '') + '" data-floor="' + fid + '" data-key="' + key + '" min="1" max="' + total + '" value="' + (isChecked ? floorQty : 1) + '" ' + (isChecked ? '' : 'disabled') + ' onchange="updateFloorAreaSummary(\'' + tabId + '\')">\n                        <span class="alloc-badge ' + (isChecked ? 'included' : 'excluded') + '">' + (isChecked ? 'Included' : 'Excluded') + '</span>\n                    </div>';
                }
            }
            if (allocFacilities.length > 0) {
                allocHtml += '<div style="margin-top:8px;margin-bottom:6px;font-weight:600;font-size:0.8rem;color:var(--text-dark);"><i class="fas fa-building"></i> Block Facilities</div>';
                for (var j = 0; j < allocFacilities.length; j++) {
                    var entryId = allocFacilities[j];
                    var name = entryId;
                    var facilityType = 'facility';
                    var facilityEntry = null;
                    var areaValue = 0;
                    var fTypes = Object.keys(campusFacilityEntries);
                    for (var k = 0; k < fTypes.length; k++) {
                        var type = fTypes[k];
                        var found = false;
                        var entries = campusFacilityEntries[type];
                        for (var l = 0; l < entries.length; l++) {
                            if (entries[l].id === entryId) { found = true;
                                name = entries[l].name;
                                facilityType = type;
                                facilityEntry = entries[l];
                                areaValue = entries[l].area || 0; break; }
                        }
                        if (found) break;
                    }
                    var isChecked2 = floorFacilities.indexOf(entryId) !== -1;
                    var extraInfo = '';
                    if (facilityType === 'washrooms' && facilityEntry) { extraInfo = ' 🚽' + (facilityEntry.toilets || 0) + ' 🚹' + (facilityEntry.urinals || 0) + ' 🚰' + (facilityEntry.washbasins || 0); }
                    if (areaValue > 0) extraInfo += ' 📐' + areaValue.toFixed(1) + ' sqft';
                    allocHtml += '<div class="allocation-item">\n                        <input type="checkbox" class="floor-alloc-check" data-floor="' + fid + '" data-key="' + entryId + '" data-total="1" data-facility="true" ' + (isChecked2 ? 'checked' : '') + ' onchange="updateFloorAreaSummary(\'' + tabId + '\')">\n                        <span class="alloc-name">' + escapeHtml(name) + extraInfo + '</span>\n                        <div class="alloc-stats">\n                            <span class="used">Allocated: 1</span>\n                        </div>\n                        <span class="alloc-badge ' + (isChecked2 ? 'included' : 'excluded') + '">' + (isChecked2 ? 'Included' : 'Excluded') + '</span>\n                    </div>';
                }
            }
        }

        var areasHtml = '';
        var areas = floor.additional_areas || [];
        if (areas.length === 0) {
            var areaId = generateUUID();
            areasHtml = '<div class="dynamic-card area-card" data-area-uuid="' + areaId + '"><div class="card-badge-sm">Area #1</div><div class="card-row" style="grid-template-columns:2fr 1fr 1fr;"><div class="form-group"><label>Area Name</label><input type="text" class="form-control area-name" placeholder="e.g., Office"></div><div class="form-group"><label>Area Unit</label><select class="form-control area-unit"><option value="sq_ft">Sq. Ft.</option><option value="sq_m">Sq. M.</option></select></div><div class="form-group"><label>Area Value</label><input type="number" class="form-control area-value" placeholder="Enter area" min="0" step="0.01" onchange="updateFloorAreaSummary(\'' + tabId + '\')"></div></div></div>';
        } else {
            for (var ai = 0; ai < areas.length; ai++) {
                var area = areas[ai];
                var id = ai + 1;
                var areaId2 = area.id || generateUUID();
                areasHtml += '\n                    <div class="dynamic-card area-card" data-area-id="' + id + '" data-area-uuid="' + areaId2 + '">\n                        <div class="card-badge-sm">Area #' + id + '</div>\n                        <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;">\n                            <div class="form-group"><label>Area Name</label><input type="text" class="form-control area-name" value="' + escapeHtml(area.name || '') + '" placeholder="e.g., Office"></div>\n                            <div class="form-group"><label>Area Unit</label><select class="form-control area-unit"><option value="sq_ft" ' + (area.unit === 'sq_ft' ? 'selected' : '') + '>Sq. Ft.</option><option value="sq_m" ' + (area.unit === 'sq_m' ? 'selected' : '') + '>Sq. M.</option></select></div>\n                            <div class="form-group"><label>Area Value</label><input type="number" class="form-control area-value" value="' + (area.area || '') + '" placeholder="Enter area" min="0" step="0.01" onchange="updateFloorAreaSummary(\'' + tabId + '\')"></div>\n                        </div>\n                        <div style="text-align:right;margin-top:0.5rem;">\n                            <button type="button" class="btn-outline-danger" onclick="this.closest(\'.dynamic-card\').remove();updateFloorAreaSummary(\'' + tabId + '\')"><i class="fas fa-trash"></i></button>\n                        </div>\n                    </div>';
            }
        }

        var gatesHtml = '';
        var gates = floor.gates || [];
        if (gates.length === 0) {
            var gateId = generateUUID();
            gatesHtml = '<div class="dynamic-card gate-card" data-gate-uuid="' + gateId + '"><div class="card-badge-sm">Entry #1</div><div class="card-row" style="grid-template-columns:1fr 1fr;"><div class="form-group"><label>Entry Name</label><input type="text" class="form-control gate-name" placeholder="e.g., Main Door"></div><div class="form-group"><label>Entry Number</label><input type="text" class="form-control gate-number" placeholder="e.g., E-01"></div></div></div>';
        } else {
            for (var gi = 0; gi < gates.length; gi++) {
                var gate = gates[gi];
                var id2 = gi + 1;
                var gateId2 = gate.id || generateUUID();
                gatesHtml += '\n                    <div class="dynamic-card gate-card" data-gate-id="' + id2 + '" data-gate-uuid="' + gateId2 + '">\n                        <div class="card-badge-sm">Entry #' + id2 + '</div>\n                        <div class="card-row" style="grid-template-columns:1fr 1fr;">\n                            <div class="form-group"><label>Entry Name</label><input type="text" class="form-control gate-name" value="' + escapeHtml(gate.name || '') + '" placeholder="e.g., Main Door"></div>\n                            <div class="form-group"><label>Entry Number</label><input type="text" class="form-control gate-number" value="' + escapeHtml(gate.number || '') + '" placeholder="e.g., E-01"></div>\n                        </div>\n                        <div style="text-align:right;margin-top:0.5rem;">\n                            <button type="button" class="btn-outline-danger" onclick="this.closest(\'.dynamic-card\').remove()"><i class="fas fa-trash"></i></button>\n                        </div>\n                    </div>';
            }
        }

        var customHtml = '';
        var customAmenities = floor.custom_amenities || [];
        if (customAmenities.length === 0) {
            var amenityId = generateUUID();
            customHtml = '<div class="custom-amenity-card" data-amenity-uuid="' + amenityId + '"><div class="card-badge-sm">Custom #1</div><div class="card-row" style="grid-template-columns:2fr 1fr;"><div class="form-group"><label>Amenity Name</label><input type="text" class="form-control custom-amenity-name" placeholder="e.g., Projector"></div><div class="form-group"><label>Quantity</label><input type="number" class="form-control custom-amenity-qty" min="1" value="1"></div></div></div>';
        } else {
            for (var ci = 0; ci < customAmenities.length; ci++) {
                var item = customAmenities[ci];
                var id3 = ci + 1;
                var amenityId2 = item.id || generateUUID();
                customHtml += '\n                    <div class="custom-amenity-card" data-custom-id="' + id3 + '" data-amenity-uuid="' + amenityId2 + '">\n                        <div class="card-badge-sm">Custom #' + id3 + '</div>\n                        <div class="card-row" style="grid-template-columns:2fr 1fr;">\n                            <div class="form-group"><label>Amenity Name</label><input type="text" class="form-control custom-amenity-name" value="' + escapeHtml(item.name || '') + '" placeholder="e.g., Projector"></div>\n                            <div class="form-group"><label>Quantity</label><input type="number" class="form-control custom-amenity-qty" value="' + (item.quantity || 1) + '" min="1"></div>\n                        </div>\n                        <div style="text-align:right;margin-top:0.5rem;">\n                            <button type="button" class="btn-outline-danger" onclick="this.closest(\'.custom-amenity-card\').remove()"><i class="fas fa-trash"></i></button>\n                        </div>\n                    </div>';
            }
        }

        var floorAreaDisplay = buildFloorAreaDeductionHTML(tabId);
        var isBasement = floor.is_basement || false;
        var basementChecked = isBasement ? 'checked' : '';

        return '\n            <div class="save-status ' + statusClass + '" id="floorSaveStatus-' + tabId + '">\n                <i class="fas ' + statusIcon + '"></i>\n                <span>' + statusText + '</span>\n            </div>\n            \n            <div class="floor-card ' + (isBasement ? 'basement' : '') + '" id="floor-' + fid + '" data-floor-id="' + fid + '" data-tab-id="' + tabId + '">\n                <div class="card-badge">' + (tabData.isSaved ? 'Existing Floor' : 'New Floor') + ' ' + (isBasement ? '🏚️ Basement' : '') + '</div>\n                <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">\n                    <div class="form-group"><label class="form-label">Floor Number/Name <span style="color:#dc3545;">*</span></label><input type="text" class="form-control floor-number" value="' + escapeHtml(floor.floor_number || '') + '" placeholder="e.g., Ground Floor" maxlength="50" onchange="updateFloorTabLabel(\'' + tabId + '\', this.value)"></div>\n                    <div class="form-group"><label class="form-label">Number of Rooms</label><input type="number" class="form-control floor-rooms" value="' + (floor.rooms || '') + '" placeholder="e.g., 10" min="0"></div>\n                    <div class="form-group" style="display:flex;align-items:center;gap:10px;padding-top:8px;">\n                        <input type="checkbox" id="floorIsBasement-' + tabId + '" ' + basementChecked + ' onchange="toggleFloorBasement(\'' + tabId + '\')">\n                        <label for="floorIsBasement-' + tabId + '" style="margin:0;font-weight:600;color:var(--text-dark);;">Is Basement</label>\n                    </div>\n                </div>\n                <div class="form-group" style="margin-top:0.75rem;"><label class="form-label">Description</label><textarea class="form-control floor-description" rows="2" placeholder="Brief description...">' + escapeHtml(floor.description || '') + '</textarea></div>\n                <div class="section-divider"><h4><i class="fas fa-vector-square"></i> Total Area</h4></div>\n                <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">\n                    <div class="form-group"><label>Area Unit</label><select class="form-control floor-area-unit"><option value="sq_ft" ' + (floor.area_unit === 'sq_ft' ? 'selected' : '') + '>Sq. Ft.</option><option value="sq_m" ' + (floor.area_unit === 'sq_m' ? 'selected' : '') + '>Sq. M.</option></select></div>\n                    <div class="form-group"><label>Area Value</label><input type="number" class="form-control floor-area-value" value="' + (floor.area_value || '') + '" placeholder="Enter area" min="0" step="0.01" onchange="onFloorAreaValueChange(this,\'' + tabId + '\')"></div>\n                </div>\n                ' + floorAreaDisplay + '\n                <div class="section-divider"><h4><i class="fas fa-map"></i> Additional Areas</h4></div>\n                <div class="floor-areas-container" data-floor-id="' + fid + '">' + areasHtml + '</div>\n                <div class="add-btn-row"><button type="button" class="btn-outline-primary" onclick="addAreaToFloorTab(' + tabId + ')"><i class="fas fa-plus"></i> Add Area</button></div>\n                <div class="section-divider"><h4><i class="fas fa-door-open"></i> Entry/Exit</h4></div>\n                <div class="floor-gates-container" data-floor-id="' + fid + '">' + gatesHtml + '</div>\n                <div class="add-btn-row"><button type="button" class="btn-outline-primary" onclick="addGateToFloorTab(' + tabId + ')"><i class="fas fa-plus"></i> Add Entry</button></div>\n                <div class="section-divider"><h4><i class="fas fa-concierge-bell"></i> Floor Amenities (from Block)</h4></div>\n                <div class="allocation-tracker" id="floorAllocTracker-' + fid + '" style="padding:0.5rem;">' + (allocHtml || '<div style="color:var(--text-muted);font-size:0.85rem;">No block resources allocated.</div>') + '</div>\n                <div class="section-divider"><h4><i class="fas fa-plus-circle" style="color:#10b981;"></i> Custom Amenities (Floor Specific)</h4></div>\n                <div class="floor-custom-amenities-container" data-floor-id="' + fid + '">' + customHtml + '</div>\n                <div class="add-btn-row"><button type="button" class="btn-outline-success" onclick="addCustomAmenityToFloorTab(' + tabId + ')"><i class="fas fa-plus"></i> Add Custom</button></div>\n                <div style="text-align:right;margin-top:1rem;">\n                    <button type="button" class="btn btn-success" onclick="saveFloorTab(' + tabId + ')" id="saveFloorBtn-' + tabId + '">\n                        <i class="fas fa-save"></i> Save Floor\n                    </button>\n                    ' + (!floorTabData[tabId].isNew ? '' : '<button type="button" class="btn-outline-danger" onclick="removeFloorTab(' + tabId + ')"><i class="fas fa-trash"></i> Remove Floor</button>') + '\n                </div>\n            </div>\n        ';
    }

    // ============================================================
    // ===== FLOOR LOCK FUNCTIONS =====
    // ============================================================

    function getLockedFloorsKey() {
        var blockId = document.getElementById('blockSelect').value;
        if (!blockId) return null;
        return 'locked_floors_' + blockId;
    }

    function saveLockedFloorToStorage(floorId, floorData) {
        var key = getLockedFloorsKey();
        if (!key) return;
        
        try {
            var lockedFloors = JSON.parse(localStorage.getItem(key) || '{}');
            lockedFloors[floorId] = {
                locked: true,
                savedAt: new Date().toISOString(),
                floorName: floorData.floor_number || 'Floor',
                floorData: floorData
            };
            localStorage.setItem(key, JSON.stringify(lockedFloors));
        } catch (e) {
            console.warn('Could not save to localStorage:', e);
        }
    }

    function getLockedFloorsFromStorage() {
        var key = getLockedFloorsKey();
        if (!key) return {};
        
        try {
            return JSON.parse(localStorage.getItem(key) || '{}');
        } catch (e) {
            return {};
        }
    }

    function isFloorLockedInStorage(floorId) {
        var lockedFloors = getLockedFloorsFromStorage();
        return lockedFloors[floorId] && lockedFloors[floorId].locked === true;
    }

    function removeFloorFromStorage(floorId) {
        var key = getLockedFloorsKey();
        if (!key) return;
        
        try {
            var lockedFloors = JSON.parse(localStorage.getItem(key) || '{}');
            delete lockedFloors[floorId];
            localStorage.setItem(key, JSON.stringify(lockedFloors));
        } catch (e) {
            console.warn('Could not remove from localStorage:', e);
        }
    }

    function clearLockedFloorsStorage() {
        var key = getLockedFloorsKey();
        if (!key) return;
        
        try {
            localStorage.removeItem(key);
        } catch (e) {
            console.warn('Could not clear localStorage:', e);
        }
    }

    function lockFloorTab(tabId) {
        var tabData = floorTabData[tabId];
        if (!tabData) return;

        if (tabData.isLocked) {
            return;
        }

        tabData.isLocked = true;
        tabData.isSaved = true;
        tabData.isNew = false;

        saveLockedFloorToStorage(tabData.floorId, tabData.floorData);

        var tabElement = tabData.tabElement;
        if (tabElement) {
            tabElement.classList.add('locked');
            tabElement.style.cursor = 'not-allowed';
            tabElement.style.opacity = '0.8';
            tabElement.title = 'This floor is saved and locked';
            
            var statusEl = tabElement.querySelector('.tab-status');
            if (statusEl) {
                statusEl.className = 'tab-status locked-status';
                statusEl.innerHTML = '<i class="fas fa-lock"></i>';
            }
            
            var nameSpan = tabElement.querySelector('span:last-child');
            if (nameSpan && !nameSpan.querySelector('.tab-lock')) {
                nameSpan.innerHTML = nameSpan.textContent + ' <span class="tab-lock">🔒</span>';
            }
            
            var removeBtn = tabElement.querySelector('.tab-remove-btn');
            if (removeBtn) {
                removeBtn.style.display = 'none';
            }
            
            tabElement.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                showToast('This floor has been saved and locked. You cannot edit it.', 'info');
                return false;
            };
        }

        var contentEl = document.getElementById('floorContent-' + tabId);
        if (contentEl) {
            contentEl.classList.add('locked-content');
            
            var inputs = contentEl.querySelectorAll('input, select, textarea, button');
            for (var i = 0; i < inputs.length; i++) {
                var input = inputs[i];
                if (!input.classList.contains('tab-remove-btn')) {
                    input.disabled = true;
                    input.style.cursor = 'not-allowed';
                    input.style.opacity = '0.6';
                }
            }
            
            var floorCard = contentEl.querySelector('.floor-card');
            if (floorCard) {
                var existingMsg = floorCard.querySelector('.floor-locked-message');
                if (existingMsg) existingMsg.remove();
                
                var lockMessage = document.createElement('div');
                lockMessage.className = 'floor-locked-message';
                lockMessage.innerHTML = '<i class="fas fa-check-circle" style="color:#10b981;"></i> This floor has been saved and is locked. You cannot edit it.';
                
                var firstChild = floorCard.firstChild;
                if (firstChild) {
                    floorCard.insertBefore(lockMessage, firstChild);
                } else {
                    floorCard.appendChild(lockMessage);
                }
            }

            var saveStatus = contentEl.querySelector('.save-status');
            if (saveStatus) {
                saveStatus.className = 'save-status locked-status';
                saveStatus.innerHTML = '<i class="fas fa-lock"></i><span>✓ Saved & Locked</span>';
            }
        }

        updateFloorTabStatus(tabId);
    }

    // ============================================================
    // ===== INITIALIZATION =====
    // ============================================================

    document.addEventListener('DOMContentLoaded', function() {
        campusAreaUnit = document.getElementById('area-unit') ? document.getElementById('area-unit').value : 'sq_ft';
        blockAllocations = {};
        blockIdCounter = 0;
        campusAmenityTotals = {};
        campusCustomAmenityTotals = {};
        campusFacilityEntries = {};

        syncLockedBlocksFromStorage();
        
        var buildingId = document.getElementById('buildingId').value;
        if (buildingId) {
            setTimeout(function() {
                loadBuildingData();
            }, 100);
        }
    });

    function syncLockedBlocksFromStorage() {
        var lockedBlocks = getLockedBlocksFromStorage();
        var keys = Object.keys(lockedBlocks);
        if (keys.length > 0) {
            console.log('🔒 Found ' + keys.length + ' locked blocks in localStorage');
        }
    }
</script>
</body>
</html>
@endsection
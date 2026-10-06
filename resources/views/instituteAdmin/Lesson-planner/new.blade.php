@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('styles')
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        background: #f0f4f9;
        color: #0b1a33;
    }

    .top-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
        padding: 14px 28px;
        border-radius: 60px;
        box-shadow: 0 4px 20px rgba(37, 99, 235, 0.08);
        margin: 20px 32px 16px 32px;
        border: 1px solid rgba(37, 99, 235, 0.06);
    }

    .logo-area {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 0 0 auto;
    }
    .logo-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }
    .logo-text {
        font-weight: 800;
        font-size: 22px;
        letter-spacing: -0.3px;
        color: #0a1e3c;
    }
    .logo-text span { color: #2563eb; }

    .nav-wrapper {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: nowrap;
        justify-content: flex-end;
        flex: 1 1 auto;
        min-width: 0;
    }

    .employee-selector {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border: 1px solid #dce4ed;
        border-radius: 12px;
        background: #f8fafc;
        color: #4b6a8b;
        font-size: 12px;
    }
    .employee-selector i { color: #2563eb; }
    .employee-selector select {
        width: 125px !important;
        padding: 6px 8px;
        border: 0;
        background: transparent;
        color: #0a1e3c;
        font: inherit;
        outline: none;
        cursor: pointer;
    }

    .nav-tabs {
        display: flex;
        background: #f1f5f9;
        padding: 5px;
        border-radius: 60px;
        gap: 2px;
        flex: 0 1 auto;
        min-width: 0;
    }

    .nav-tab {
        border: none;
        background: transparent;
        padding: 8px 10px;
        border-radius: 60px;
        font-weight: 500;
        font-size: 14px;
        color: #4b6a8b;
        cursor: pointer;
        transition: 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .nav-tab i { font-size: 14px; }
    .nav-tab:hover {
        background: rgba(255, 255, 255, 0.8);
        color: #0a1e3c;
    }
    .nav-tab.active {
        background: white;
        color: #2563eb;
        box-shadow: 0 2px 12px rgba(37, 99, 235, 0.12);
    }

    .btn-new {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border: none;
        color: white;
        padding: 10px 18px;
        border-radius: 60px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s;
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex: 0 0 auto;
        white-space: nowrap;
    }
    .btn-new i { font-size: 14px; }
    .btn-new:hover {
        background: linear-gradient(135deg, #1d4ed8, #1a3fb5);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
    }

    .content-wrapper {
        margin: 0 32px 32px 32px;
        border-radius: 28px;
        overflow: hidden;
        background: #f0f4f9;
        min-height: calc(100vh - 160px);
    }

    .create-page {
        background: #f0f4f9;
        width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
    }

    .create-card {
        background: white;
        border-radius: 28px;
        padding: 32px 36px;
        box-shadow: 0 4px 20px rgba(37, 99, 235, 0.06);  
        max-width: 1200px;
        margin: 0 auto;
        border: 1px solid rgba(37, 99, 235, 0.06); 
        width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
    }

    .page-title {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 6px;
        color: #0a1e3c;
    }
    .page-title i { color: #2563eb; margin-right: 12px; }

    .page-subtitle {
        color: #4b6a8b;
        font-size: 15px;
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .page-subtitle i { color: #2563eb; }

    .form-section {
        margin-bottom: 28px;
        padding-bottom: 28px;
        border-bottom: 1px solid #eef2f6;
    }
    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #0a1e3c;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .section-title i { color: #2563eb; }
    .section-title .count {
        font-size: 13px;
        color: #4b6a8b;
        font-weight: 400;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px 24px;
        width: 100%;
        box-sizing: border-box;
    }
    .form-group { 
        margin-bottom: 4px; 
        width: 100%;
        box-sizing: border-box;
    }
    .form-group-full { grid-column: 1 / -1; }
    .form-label {
        display: block;
        font-weight: 500;
        font-size: 14px;
        margin-bottom: 6px;
        color: #0a1e3c;
    }
    .form-label i { color: #2563eb; margin-right: 6px; width: 18px; }
    .form-required { color: #ef4444; }
    .form-hint {
        font-size: 12px;
        color: #8a9bb5;
        margin-top: 4px;
    }

    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 20px;
        margin-top: 4px;
    }
    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
    }
    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #2563eb;
        cursor: pointer;
    }
    textarea {
        min-height: 80px;
        resize: vertical;
        width: 100%;
        box-sizing: border-box;
    }

    select, input[type="text"], input[type="url"], input[type="date"], input[type="number"], textarea {
        padding: 10px 16px;
        border: 1.5px solid #dce4ed;
        border-radius: 40px;
        font-size: 14px;
        background: #fafcff;
        font-family: inherit;
        outline: none;
        transition: 0.2s;
        width: 100%;
        box-sizing: border-box;
    }
    select:focus, input:focus, textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08);
        background: white;
    }
    input[type="date"] { cursor: pointer; }
    input[type="number"] {
        -moz-appearance: textfield;
        width: 120px;
        text-align: center;
        font-weight: 700;
        font-size: 20px;
        padding: 8px 12px;
    }
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .success-message {
        background: #d1fae5;
        color: #0b6e4f;
        padding: 14px 22px;
        border-radius: 60px;
        display: none;
        font-weight: 500;
        margin-bottom: 20px;
    }
    .success-message i { margin-right: 8px; }
    .success-message.show { display: block; }

    .action-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #eef2f6;
    }

    .btn {
        border: none;
        padding: 10px 24px;
        border-radius: 60px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s;
        background: #eef2f6;
        color: #1f334f;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn i { font-size: 14px; }
    .btn-primary {
        background: #2563eb;
        color: white;
    }
    .btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); }
    .btn-success {
        background: #10b981;
        color: white;
    }
    .btn-success:hover { background: #059669; transform: translateY(-1px); }
    .btn-danger {
        background: #ef4444;
        color: white;
    }
    .btn-danger:hover { background: #dc2626; transform: translateY(-1px); }
    .btn-secondary {
        background: #e6ecf3;
        color: #1f334f;
    }
    .btn-secondary:hover { background: #d1d9e6; transform: translateY(-1px); }
    .btn-small { padding: 6px 14px; font-size: 12px; }

    .plan-level-selector {
        display: flex;
        gap: 12px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .plan-level-btn {
        padding: 12px 28px;
        border-radius: 60px;
        border: 2px solid #dce4ed;
        background: white;
        cursor: pointer;
        font-weight: 600;
        font-size: 15px;
        color: #4b6a8b;
        transition: 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        justify-content: center;
        min-width: 150px;
    }
    .plan-level-btn:hover {
        border-color: #2563eb;
        color: #2563eb;
        background: #f0f4ff;
        transform: translateY(-2px);
    }
    .plan-level-btn.active {
        border-color: #2563eb;
        background: #2563eb;
        color: white;
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
        transform: translateY(-2px);
    }
    .plan-level-btn .icon { font-size: 20px; }

    .duration-section {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px;
        border: 1.5px solid #dce4ed;
        width: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }

    .duration-input-group {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .duration-input-group .unit-label {
        font-weight: 600;
        color: #0a1e3c;
        font-size: 16px;
        min-width: 60px;
    }
    .duration-input-group .total-days-info {
        background: #2563eb;
        color: white;
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        margin-left: 8px;
    }

    .preset-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #e6ecf3;
        width: 100%;
        box-sizing: border-box;
        max-width: 100%;
    }
    
    .preset-buttons::-webkit-scrollbar {
        height: 6px;
    }
    .preset-buttons::-webkit-scrollbar-track {
        background: #eef2f6;
        border-radius: 4px;
    }
    .preset-buttons::-webkit-scrollbar-thumb {
        background: #2563eb;
        border-radius: 4px;
    }
    
    .preset-btn {
        padding: 6px 14px;
        border-radius: 20px;
        border: 1.5px solid #dce4ed;
        background: white;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        color: #4b6a8b;
        transition: 0.2s;
        flex-shrink: 0;
        white-space: nowrap;
    }
    .preset-btn:hover {
        border-color: #2563eb;
        color: #2563eb;
        background: #f0f4ff;
        transform: translateY(-2px);
    }
    .preset-btn.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }
    .preset-btn.popular {
        background: #fef3c7;
        border-color: #f59e0b;
        color: #92400e;
    }
    .preset-btn.popular:hover {
        background: #f59e0b;
        color: white;
    }
    .preset-btn.popular.active {
        background: #f59e0b;
        color: white;
        border-color: #f59e0b;
    }

    .date-range-display {
        background: linear-gradient(135deg, #f0f4ff 0%, #e8edf5 100%);
        padding: 16px 24px;
        border-radius: 16px;
        margin-top: 12px;
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        border: 1.5px solid #dbeafe;
    }
    .date-range-display .range-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .date-range-display .range-item .label {
        font-size: 13px;
        color: #4b6a8b;
    }
    .date-range-display .range-item .value {
        font-weight: 700;
        color: #1d4ed8;
        font-size: 15px;
    }
    .date-range-display .range-arrow {
        color: #8a9bb5;
        font-size: 20px;
    }
    .date-range-display .duration-badge {
        background: #2563eb;
        color: white;
        padding: 4px 20px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }
    .date-range-display .days-count {
        color: #4b6a8b;
        font-size: 14px;
        font-weight: 500;
    }
    .date-range-display .weekday-info {
        font-size: 12px;
        color: #8a9bb5;
    }

    .level-badge {
        display: inline-block;
        padding: 2px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-left: 8px;
    }
    .level-badge.day { background: #dbeafe; color: #1d4ed8; }
    .level-badge.week { background: #d1fae5; color: #0b6e4f; }

    .topic-row {
        border: 1.5px solid #dce4ed;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 14px;
        background: #fafcff;
        transition: all 0.2s;
    }
    .topic-row:hover {
        border-color: #2563eb;
        box-shadow: 0 2px 12px rgba(37, 99, 235, 0.06);
    }
    .topic-row .topic-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
        flex-wrap: wrap;
        gap: 8px;
    }
    .topic-row .topic-number {
        font-weight: 700;
        font-size: 16px;
        color: #2563eb;
    }
    .topic-row .topic-number i { margin-right: 8px; }
    .topic-row .topic-date-info {
        font-size: 12px;
        color: #4b6a8b;
        background: #f0f4ff;
        padding: 4px 12px;
        border-radius: 16px;
        border: 1px solid #dbeafe;
    }
    .topic-row .topic-fields {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }
    .topic-row .topic-fields-full { grid-column: 1 / -1; }
    .topic-row .topic-field-label {
        font-size: 13px;
        font-weight: 500;
        color: #4b6a8b;
        display: block;
        margin-bottom: 4px;
    }
    .topic-row .topic-field-label i { color: #2563eb; margin-right: 4px; }

    .video-preview {
        margin-top: 10px;
        padding: 8px 12px;
        background: #eef2f6;
        border-radius: 12px;
        display: none;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: #4b6a8b;
    }
    .video-preview.show { display: flex; }
    .video-preview .video-thumb {
        width: 120px;
        height: 68px;
        border-radius: 6px;
        overflow: hidden;
        flex-shrink: 0;
    }
    .video-preview .video-thumb iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    .video-preview .video-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .video-preview .video-info .label {
        font-size: 11px;
        color: #8a9bb5;
    }
    .video-preview .video-info .link {
        color: #2563eb;
        word-break: break-all;
    }

    .file-list { margin-top: 8px; }
    .file-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        background: white;
        border-radius: 8px;
        margin-bottom: 4px;
        font-size: 13px;
        border: 1px solid #e6ecf3;
    }
    .file-item .file-name { flex: 1; }
    .file-item .file-size {
        color: #8a9bb5;
        font-size: 11px;
    }

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
        margin-top: 12px;
        padding: 12px 16px;
        background: white;
        border-radius: 12px;
        border: 1px solid #dce4ed;
    }
    .quick-stats .stat-item { text-align: center; }
    .quick-stats .stat-item .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: #2563eb;
    }
    .quick-stats .stat-item .stat-label {
        font-size: 11px;
        color: #8a9bb5;
        margin-top: 2px;
    }

    .plan-level-container {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px 24px;
        border: 1.5px solid #eef2f6;
        margin-top: 4px;
    }

    .syllabus-container {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px 24px;
        border: 2px solid #2563eb;
        margin-top: 4px;
        position: relative;
        /*overflow: hidden;*/
    }

    .syllabus-container::before {
        content: "📚 SYLLABUS PLANNER";
        position: absolute;
        top: -12px;
        left: 20px;
        background: #2563eb;
        color: white;
        padding: 2px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .syllabus-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        background: white;
        border-radius: 12px;
        margin-bottom: 8px;
        border: 1px solid #e6ecf3;
        cursor: pointer;
        transition: all 0.2s;
    }
    .syllabus-item:hover {
        border-color: #2563eb;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.08);
        transform: translateX(4px);
    }
    .syllabus-item.selected {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 2px 12px rgba(37, 99, 235, 0.15);
    }
    .syllabus-item .syllabus-check {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 2px solid #dce4ed;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: 0.2s;
        background: white;
    }
    .syllabus-item.selected .syllabus-check {
        background: #2563eb;
        border-color: #2563eb;
        color: white;
    }
    .syllabus-item .syllabus-info {
        flex: 1;
    }
    .syllabus-item .syllabus-info .title {
        font-weight: 600;
        color: #0a1e3c;
        font-size: 14px;
    }
    .syllabus-item .syllabus-info .meta {
        font-size: 12px;
        color: #8a9bb5;
        margin-top: 2px;
    }
    .syllabus-item .syllabus-badge {
        font-size: 10px;
        padding: 2px 10px;
        border-radius: 20px;
        background: #dbeafe;
        color: #1d4ed8;
        font-weight: 600;
    }
    .syllabus-item .syllabus-chapters {
        font-size: 11px;
        color: #4b6a8b;
        padding: 2px 10px;
        background: #fef3c7;
        border-radius: 20px;
    }

    .distribute-btn {
        margin-top: 12px;
        padding: 10px 24px;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 40px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }
    .distribute-btn:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    .distribute-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .topic-distribution {
        margin-top: 16px;
        padding: 16px;
        background: white;
        border-radius: 12px;
        border: 1px solid #dce4ed;
        max-height: 400px;
        overflow-y: auto;
    }
    .topic-distribution .dist-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 6px 12px;
        border-bottom: 1px solid #f0f4f9;
    }
    .topic-distribution .dist-item:last-child {
        border-bottom: none;
    }
    .topic-distribution .dist-day {
        font-weight: 600;
        color: #2563eb;
        min-width: 140px;
        font-size: 13px;
    }
    .topic-distribution .dist-topic {
        flex: 1;
        font-size: 13px;
        color: #0a1e3c;
    }
    .topic-distribution .dist-count {
        font-size: 11px;
        color: #8a9bb5;
        background: #eef2f6;
        padding: 2px 10px;
        border-radius: 20px;
    }

    .syllabus-empty {
        text-align: center;
        padding: 30px;
        color: #8a9bb5;
    }
    .syllabus-empty i {
        font-size: 48px;
        color: #2563eb;
        margin-bottom: 12px;
        display: block;
    }

    .syllabus-detail {
        font-size: 12px;
        color: #4b6a8b;
        padding: 8px 12px;
        background: #f8fafc;
        border-radius: 8px;
        margin-top: 4px;
        border-left: 3px solid #2563eb;
    }

    /* ===== SHIFT TABS STYLES - VERTICAL LAYOUT ===== */
    #teacherShiftInfo {
        margin-top: 8px;
        font-size: 13px;
        color: #4b6a8b;
        width: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }

    .shift-tabs-container {
        background: white;
        border-radius: 12px;
        border: 1.5px solid #dce4ed;
        overflow: hidden;
        margin-top: 4px;
        width: 100%;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        max-height: 500px;
    }

    .shift-tabs-header {
        display: flex;
        flex-direction: column;
        gap: 2px;
        background: #f8fafc;
        border-bottom: 2px solid #eef2f6;
        padding: 8px;
        overflow-y: auto;
        max-height: 200px;
        flex-shrink: 0;
        width: 100%;
        box-sizing: border-box;
    }

    .shift-tabs-header::-webkit-scrollbar {
        width: 4px;
    }
    .shift-tabs-header::-webkit-scrollbar-track {
        background: #eef2f6;
        border-radius: 2px;
    }
    .shift-tabs-header::-webkit-scrollbar-thumb {
        background: #2563eb;
        border-radius: 2px;
    }

    .shift-tab-button {
        padding: 8px 14px;
        border: none;
        background: transparent;
        color: #4b6a8b;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        white-space: nowrap;
        border-radius: 6px;
        transition: all 0.2s;
        text-align: left;
        width: 100%;
        box-sizing: border-box;
    }

    .shift-tab-button:hover {
        color: #2563eb;
        background: #f0f4ff;
    }

    .shift-tab-button.active {
        color: #2563eb;
        background: #eff6ff;
        border-left: 3px solid #2563eb;
        padding-left: 11px;
    }

    .shift-tabs-content {
        padding: 16px 20px;
        width: 100%;
        box-sizing: border-box;
        min-height: 150px;
        max-height: 350px;
        overflow-y: auto;
        background: white;
        flex: 1;
    }

    .shift-tabs-content::-webkit-scrollbar {
        width: 6px;
    }
    .shift-tabs-content::-webkit-scrollbar-track {
        background: #f0f4f9;
        border-radius: 3px;
    }
    .shift-tabs-content::-webkit-scrollbar-thumb {
        background: #2563eb;
        border-radius: 3px;
    }
    .shift-tabs-content::-webkit-scrollbar-thumb:hover {
        background: #1d4ed8;
    }

    .shift-tab-pane {
        display: none;
    }

    .shift-tab-pane.active {
        display: block;
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .shift-weekday-group {
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #eef2f6;
    }

    .shift-weekday-group:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .shift-weekday-header {
        font-weight: 700;
        color: #0a1e3c;
        font-size: 15px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 6px;
        border-bottom: 2px solid #2563eb;
        width: fit-content;
    }

    .shift-weekday-header::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #2563eb;
        flex-shrink: 0;
    }

    .shift-time-entry {
        background: linear-gradient(135deg, #f8fafc 0%, #fafcff 100%);
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 10px;
        border-left: 4px solid #2563eb;
        display: flex;
        flex-direction: column;
        gap: 4px;
        transition: all 0.2s;
        box-shadow: 0 1px 3px rgba(37, 99, 235, 0.05);
    }

    .shift-time-entry:hover {
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.12);
        transform: translateX(2px);
    }

    .shift-time {
        font-size: 14px;
        color: #2563eb;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .shift-dates {
        font-size: 12px;
        color: #4b6a8b;
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .shift-count-badge {
        display: inline-block;
        background: #dbeafe;
        color: #1d4ed8;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }

    .shift-no-data {
        text-align: center;
        padding: 40px 20px;
        color: #8a9bb5;
        background: linear-gradient(135deg, #f8fafc 0%, #fafcff 100%);
        border-radius: 10px;
        border: 1px dashed #dce4ed;
    }

    .shift-no-data i {
        font-size: 36px;
        color: #2563eb;
        display: block;
        margin-bottom: 10px;
        opacity: 0.3;
    }

    .shift-no-data div {
        font-size: 14px;
        font-weight: 500;
    }

    /* Month Selector for Lesson Plan */
    .month-selector {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
        margin-bottom: 12px;
    }
    .month-btn {
        padding: 6px 16px;
        border-radius: 20px;
        border: 1.5px solid #dce4ed;
        background: white;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        color: #4b6a8b;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .month-btn:hover {
        border-color: #2563eb;
        color: #2563eb;
        background: #f0f4ff;
        transform: translateY(-2px);
    }
    .month-btn.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }
    .month-btn .working-days-badge {
        background: #dbeafe;
        color: #1d4ed8;
        padding: 0 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
    }
    .month-btn.active .working-days-badge {
        background: rgba(255,255,255,0.2);
        color: white;
    }

    /* Working Days Info Box */
    .working-days-info {
        background: #f0f7ff;
        border: 1.5px solid #dbeafe;
        border-radius: 12px;
        padding: 12px 16px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .working-days-info .label {
        font-weight: 600;
        color: #0a1e3c;
        font-size: 13px;
    }
    .working-days-info .value {
        background: #2563eb;
        color: white;
        padding: 2px 14px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 700;
    }
    .working-days-info .detail {
        color: #4b6a8b;
        font-size: 12px;
    }

    /* Topic Count Info */
    .topic-count-info {
        background: #fef3c7;
        border: 1.5px solid #f59e0b;
        border-radius: 12px;
        padding: 10px 16px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .topic-count-info .label {
        font-weight: 600;
        color: #92400e;
        font-size: 13px;
    }
    .topic-count-info .value {
        background: #f59e0b;
        color: white;
        padding: 2px 14px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 700;
    }
    .topic-count-info .detail {
        color: #92400e;
        font-size: 12px;
    }

    @media (max-width: 820px) {
        /*.create-page { padding: 16px; }*/
        .create-card { padding: 20px; }
        .form-grid { grid-template-columns: 1fr; }
        .topic-row .topic-fields { grid-template-columns: 1fr; }
        .action-buttons { flex-direction: column; }
        .action-buttons .btn { justify-content: center; }
        .plan-level-selector { flex-direction: column; }
        .plan-level-btn { min-width: auto; }
        .date-range-display {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }
        .date-range-display .range-arrow { transform: rotate(90deg); }
        .duration-input-group {
            flex-direction: column;
            align-items: stretch;
        }
        .duration-input-group input[type="number"] { width: 100%; }
        .preset-buttons { justify-content: flex-start; flex-wrap: nowrap; overflow-x: auto; padding-bottom: 6px; }
        .preset-buttons::-webkit-scrollbar { height: 4px; }
        .quick-stats { grid-template-columns: 1fr 1fr; }
        .plan-level-container { padding: 16px; }
        .syllabus-container { padding: 16px; }
        .syllabus-container::before { left: 10px; font-size: 10px; }
        .shift-tabs-content { padding: 12px 14px; max-height: 280px; }
        .shift-time-entry { padding: 10px 12px; }
        .shift-weekday-header { font-size: 14px; }
        .shift-tab-button { font-size: 12px; padding: 6px 12px; }
        .shift-tabs-header { max-height: 160px; padding: 6px; }
        .working-days-info { flex-direction: column; text-align: center; }
        .month-btn { font-size: 12px; padding: 4px 12px; }
        .topic-distribution .dist-day { min-width: 100px; font-size: 12px; }
        .topic-count-info { flex-direction: column; text-align: center; }
    }

    @media (max-width: 480px) {
        .shift-tabs-content { padding: 10px 12px; max-height: 240px; }
        .shift-time-entry { padding: 8px 10px; }
        .shift-time { font-size: 12px; }
        .shift-dates { font-size: 11px; }
        .shift-tab-button { font-size: 11px; padding: 5px 10px; }
        .shift-tabs-header { max-height: 140px; padding: 4px; }
        .shift-tabs-container { max-height: 400px; }
        .preset-btn { font-size: 10px; padding: 4px 10px; }
        .preset-buttons { gap: 4px; }
        .month-btn { font-size: 11px; padding: 3px 10px; }
        .topic-distribution .dist-day { min-width: 80px; font-size: 11px; }
    }
  .create-page {
        background: #f0f4f9;
        width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
    }

    .create-card {
        background: white;
        border-radius: 28px;
        padding: 32px 36px;
        box-shadow: 0 4px 20px rgba(37, 99, 235, 0.06);
        max-width: 1200px;
        margin: 0 auto;
        border: 1px solid rgba(37, 99, 235, 0.06);
        width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
    }

    .page-title {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 6px;
        color: #0a1e3c;
    }
    .page-title i { color: #2563eb; margin-right: 12px; }

    .page-subtitle {
        color: #4b6a8b;
        font-size: 15px;
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .page-subtitle i { color: #2563eb; }

    .form-section {
        margin-bottom: 28px;
        padding-bottom: 28px;
        border-bottom: 1px solid #eef2f6;
    }
    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #0a1e3c;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .section-title i { color: #2563eb; }
    .section-title .count {
        font-size: 13px;
        color: #4b6a8b;
        font-weight: 400;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px 24px;
        width: 100%;
        box-sizing: border-box;
    }
    .form-group { 
        margin-bottom: 4px; 
        width: 100%;
        box-sizing: border-box;
    }
    .form-group-full { grid-column: 1 / -1; }
    .form-label {
        display: block;
        font-weight: 500;
        font-size: 14px;
        margin-bottom: 6px;
        color: #0a1e3c;
    }
    .form-label i { color: #2563eb; margin-right: 6px; width: 18px; }
    .form-required { color: #ef4444; }
    .form-hint {
        font-size: 12px;
        color: #8a9bb5;
        margin-top: 4px;
    }

    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 20px;
        margin-top: 4px;
    }
    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
    }
    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #2563eb;
        cursor: pointer;
    }
    textarea {
        min-height: 80px;
        resize: vertical;
        width: 100%;
        box-sizing: border-box;
    }

    select, input[type="text"], input[type="url"], input[type="date"], input[type="number"], textarea {
        padding: 10px 16px;
        border: 1.5px solid #dce4ed;
        border-radius: 40px;
        font-size: 14px;
        background: #fafcff;
        font-family: inherit;
        outline: none;
        transition: 0.2s;
        width: 100%;
        box-sizing: border-box;
    }
    select:focus, input:focus, textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08);
        background: white;
    }
    input[type="date"] { cursor: pointer; }
    input[type="number"] {
        -moz-appearance: textfield;
        width: 120px;
        text-align: center;
        font-weight: 700;
        font-size: 20px;
        padding: 8px 12px;
    }
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .success-message {
        background: #d1fae5;
        color: #0b6e4f;
        padding: 14px 22px;
        border-radius: 60px;
        display: none;
        font-weight: 500;
        margin-bottom: 20px;
    }
    .success-message i { margin-right: 8px; }
    .success-message.show { display: block; }

    .action-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #eef2f6;
    }

    .btn {
        border: none;
        padding: 10px 24px;
        border-radius: 60px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s;
        background: #eef2f6;
        color: #1f334f;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn i { font-size: 14px; }
    .btn-primary {
        background: #2563eb;
        color: white;
    }
    .btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); }
    .btn-success {
        background: #10b981;
        color: white;
    }
    .btn-success:hover { background: #059669; transform: translateY(-1px); }
    .btn-danger {
        background: #ef4444;
        color: white;
    }
    .btn-danger:hover { background: #dc2626; transform: translateY(-1px); }
    .btn-secondary {
        background: #e6ecf3;
        color: #1f334f;
    }
    .btn-secondary:hover { background: #d1d9e6; transform: translateY(-1px); }
    .btn-small { padding: 6px 14px; font-size: 12px; }

    .plan-level-selector {
        display: flex;
        gap: 12px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .plan-level-btn {
        padding: 12px 28px;
        border-radius: 60px;
        border: 2px solid #dce4ed;
        background: white;
        cursor: pointer;
        font-weight: 600;
        font-size: 15px;
        color: #4b6a8b;
        transition: 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        justify-content: center;
        min-width: 150px;
    }
    .plan-level-btn:hover {
        border-color: #2563eb;
        color: #2563eb;
        background: #f0f4ff;
        transform: translateY(-2px);
    }
    .plan-level-btn.active {
        border-color: #2563eb;
        background: #2563eb;
        color: white;
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
        transform: translateY(-2px);
    }
    .plan-level-btn .icon { font-size: 20px; }

    .duration-section {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px;
        border: 1.5px solid #dce4ed;
        width: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }

    .duration-input-group {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .duration-input-group .unit-label {
        font-weight: 600;
        color: #0a1e3c;
        font-size: 16px;
        min-width: 60px;
    }
    .duration-input-group .total-days-info {
        background: #2563eb;
        color: white;
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        margin-left: 8px;
    }

    .preset-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #e6ecf3;
        width: 100%;
        box-sizing: border-box;
        max-width: 100%;
    }
    
    .preset-buttons::-webkit-scrollbar {
        height: 6px;
    }
    .preset-buttons::-webkit-scrollbar-track {
        background: #eef2f6;
        border-radius: 4px;
    }
    .preset-buttons::-webkit-scrollbar-thumb {
        background: #2563eb;
        border-radius: 4px;
    }
    
    .preset-btn {
        padding: 6px 14px;
        border-radius: 20px;
        border: 1.5px solid #dce4ed;
        background: white;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        color: #4b6a8b;
        transition: 0.2s;
        flex-shrink: 0;
        white-space: nowrap;
    }
    .preset-btn:hover {
        border-color: #2563eb;
        color: #2563eb;
        background: #f0f4ff;
        transform: translateY(-2px);
    }
    .preset-btn.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }
    .preset-btn.popular {
        background: #fef3c7;
        border-color: #f59e0b;
        color: #92400e;
    }
    .preset-btn.popular:hover {
        background: #f59e0b;
        color: white;
    }
    .preset-btn.popular.active {
        background: #f59e0b;
        color: white;
        border-color: #f59e0b;
    }

    .date-range-display {
        background: linear-gradient(135deg, #f0f4ff 0%, #e8edf5 100%);
        padding: 16px 24px;
        border-radius: 16px;
        margin-top: 12px;
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        border: 1.5px solid #dbeafe;
    }
    .date-range-display .range-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .date-range-display .range-item .label {
        font-size: 13px;
        color: #4b6a8b;
    }
    .date-range-display .range-item .value {
        font-weight: 700;
        color: #1d4ed8;
        font-size: 15px;
    }
    .date-range-display .range-arrow {
        color: #8a9bb5;
        font-size: 20px;
    }
    .date-range-display .duration-badge {
        background: #2563eb;
        color: white;
        padding: 4px 20px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }
    .date-range-display .days-count {
        color: #4b6a8b;
        font-size: 14px;
        font-weight: 500;
    }
    .date-range-display .weekday-info {
        font-size: 12px;
        color: #8a9bb5;
    }

    .level-badge {
        display: inline-block;
        padding: 2px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-left: 8px;
    }
    .level-badge.day { background: #dbeafe; color: #1d4ed8; }
    .level-badge.week { background: #d1fae5; color: #0b6e4f; }

    .topic-row {
        border: 1.5px solid #dce4ed;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 14px;
        background: #fafcff;
        transition: all 0.2s;
    }
    .topic-row:hover {
        border-color: #2563eb;
        box-shadow: 0 2px 12px rgba(37, 99, 235, 0.06);
    }
    .topic-row .topic-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
        flex-wrap: wrap;
        gap: 8px;
    }
    .topic-row .topic-number {
        font-weight: 700;
        font-size: 16px;
        color: #2563eb;
    }
    .topic-row .topic-number i { margin-right: 8px; }
    .topic-row .topic-date-info {
        font-size: 12px;
        color: #4b6a8b;
        background: #f0f4ff;
        padding: 4px 12px;
        border-radius: 16px;
        border: 1px solid #dbeafe;
    }
    .topic-row .topic-fields {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }
    .topic-row .topic-fields-full { grid-column: 1 / -1; }
    .topic-row .topic-field-label {
        font-size: 13px;
        font-weight: 500;
        color: #4b6a8b;
        display: block;
        margin-bottom: 4px;
    }
    .topic-row .topic-field-label i { color: #2563eb; margin-right: 4px; }

    .video-preview {
        margin-top: 10px;
        padding: 8px 12px;
        background: #eef2f6;
        border-radius: 12px;
        display: none;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: #4b6a8b;
    }
    .video-preview.show { display: flex; }
    .video-preview .video-thumb {
        width: 120px;
        height: 68px;
        border-radius: 6px;
        overflow: hidden;
        flex-shrink: 0;
    }
    .video-preview .video-thumb iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    .video-preview .video-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .video-preview .video-info .label {
        font-size: 11px;
        color: #8a9bb5;
    }
    .video-preview .video-info .link {
        color: #2563eb;
        word-break: break-all;
    }

    .file-list { margin-top: 8px; }
    .file-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        background: white;
        border-radius: 8px;
        margin-bottom: 4px;
        font-size: 13px;
        border: 1px solid #e6ecf3;
    }
    .file-item .file-name { flex: 1; }
    .file-item .file-size {
        color: #8a9bb5;
        font-size: 11px;
    }

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); 
        gap: 12px;
        margin-top: 12px;
        padding: 12px 16px;
        background: white;
        border-radius: 12px;
        border: 1px solid #dce4ed;
    }
    .quick-stats .stat-item { text-align: center; } 
    .quick-stats .stat-item .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: #2563eb;
    }
    .quick-stats .stat-item .stat-label {
        font-size: 11px;
        color: #8a9bb5;
        margin-top: 2px;
    }

    .plan-level-container {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px 24px;
        border: 1.5px solid #eef2f6;
        margin-top: 4px;
    }

    .syllabus-container {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px 24px;
        border: 2px solid #2563eb;
        margin-top: 4px;
        position: relative;
        /*overflow: hidden;*/
    }

    .syllabus-container::before {
        content: "📚 SYLLABUS PLANNER";
        position: absolute;
        top: -12px;
        left: 20px;
        background: #2563eb;
        color: white;
        padding: 2px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .syllabus-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        background: white;
        border-radius: 12px;
        margin-bottom: 8px;
        border: 1px solid #e6ecf3;
        cursor: pointer;
        transition: all 0.2s;
    }
    .syllabus-item:hover {
        border-color: #2563eb;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.08);
        transform: translateX(4px);
    }
    .syllabus-item.selected {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 2px 12px rgba(37, 99, 235, 0.15);
    }
    .syllabus-item .syllabus-check {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 2px solid #dce4ed;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: 0.2s;
        background: white;
    }
    .syllabus-item.selected .syllabus-check {
        background: #2563eb;
        border-color: #2563eb;
        color: white;
    }
    .syllabus-item .syllabus-info {
        flex: 1;
    }
    .syllabus-item .syllabus-info .title {
        font-weight: 600;
        color: #0a1e3c;
        font-size: 14px;
    }
    .syllabus-item .syllabus-info .meta {
        font-size: 12px;
        color: #8a9bb5;
        margin-top: 2px;
    }
    .syllabus-item .syllabus-badge {
        font-size: 10px;
        padding: 2px 10px;
        border-radius: 20px;
        background: #dbeafe;
        color: #1d4ed8;
        font-weight: 600;
    }
    .syllabus-item .syllabus-chapters {
        font-size: 11px;
        color: #4b6a8b;
        padding: 2px 10px;
        background: #fef3c7;
        border-radius: 20px;
    }

    .distribute-btn {
        margin-top: 12px;
        padding: 10px 24px;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 40px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }
    .distribute-btn:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    .distribute-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .topic-distribution {
        margin-top: 16px;
        padding: 16px;
        background: white;
        border-radius: 12px;
        border: 1px solid #dce4ed;
        max-height: 400px;
        overflow-y: auto;
    }
    .topic-distribution .dist-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 6px 12px;
        border-bottom: 1px solid #f0f4f9;
    }
    .topic-distribution .dist-item:last-child {
        border-bottom: none;
    }
    .topic-distribution .dist-day {
        font-weight: 600;
        color: #2563eb;
        min-width: 140px;
        font-size: 13px;
    }
    .topic-distribution .dist-topic {
        flex: 1;
        font-size: 13px;
        color: #0a1e3c;
    }
    .topic-distribution .dist-count {
        font-size: 11px;
        color: #8a9bb5;
        background: #eef2f6;
        padding: 2px 10px;
        border-radius: 20px;
    }

    .syllabus-empty {
        text-align: center;
        padding: 30px;
        color: #8a9bb5;
    }
    .syllabus-empty i {
        font-size: 48px;
        color: #2563eb;
        margin-bottom: 12px;
        display: block;
    }

    .syllabus-detail {
        font-size: 12px;
        color: #4b6a8b;
        padding: 8px 12px;
        background: #f8fafc;
        border-radius: 8px;
        margin-top: 4px;
        border-left: 3px solid #2563eb;
    }

    /* ===== SHIFT TABS STYLES - VERTICAL LAYOUT ===== */
    #teacherShiftInfo {
        margin-top: 8px;
        font-size: 13px;
        color: #4b6a8b;
        width: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }

    .shift-tabs-container {
        background: white;
        border-radius: 12px;
        border: 1.5px solid #dce4ed;
        overflow: hidden;
        margin-top: 4px;
        width: 100%;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        max-height: 500px;
    }

    .shift-tabs-header {
        display: flex;
        flex-direction: column;
        gap: 2px;
        background: #f8fafc;
        border-bottom: 2px solid #eef2f6;
        padding: 8px;
        overflow-y: auto;
        max-height: 200px;
        flex-shrink: 0;
        width: 100%;
        box-sizing: border-box;
    }

    .shift-tabs-header::-webkit-scrollbar {
        width: 4px;
    }
    .shift-tabs-header::-webkit-scrollbar-track {
        background: #eef2f6;
        border-radius: 2px;
    }
    .shift-tabs-header::-webkit-scrollbar-thumb {
        background: #2563eb;
        border-radius: 2px;
    }

    .shift-tab-button {
        padding: 8px 14px;
        border: none;
        background: transparent;
        color: #4b6a8b;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        white-space: nowrap;
        border-radius: 6px;
        transition: all 0.2s;
        text-align: left;
        width: 100%;
        box-sizing: border-box;
    }

    .shift-tab-button:hover {
        color: #2563eb;
        background: #f0f4ff;
    }

    .shift-tab-button.active {
        color: #2563eb;
        background: #eff6ff;
        border-left: 3px solid #2563eb;
        padding-left: 11px;
    }

    .shift-tabs-content {
        padding: 16px 20px;
        width: 100%;
        box-sizing: border-box;
        min-height: 150px;
        max-height: 350px;
        overflow-y: auto;
        background: white;
        flex: 1;
    }

    .shift-tabs-content::-webkit-scrollbar {
        width: 6px;
    }
    .shift-tabs-content::-webkit-scrollbar-track {
        background: #f0f4f9;
        border-radius: 3px;
    }
    .shift-tabs-content::-webkit-scrollbar-thumb {
        background: #2563eb;
        border-radius: 3px;
    }
    .shift-tabs-content::-webkit-scrollbar-thumb:hover {
        background: #1d4ed8;
    }

    .shift-tab-pane {
        display: none;
    }

    .shift-tab-pane.active {
        display: block;
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .shift-weekday-group {
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #eef2f6;
    }

    .shift-weekday-group:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .shift-weekday-header {
        font-weight: 700;
        color: #0a1e3c;
        font-size: 15px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 6px;
        border-bottom: 2px solid #2563eb;
        width: fit-content;
    }

    .shift-weekday-header::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #2563eb;
        flex-shrink: 0;
    }

    .shift-time-entry {
        background: linear-gradient(135deg, #f8fafc 0%, #fafcff 100%);
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 10px;
        border-left: 4px solid #2563eb;
        display: flex;
        flex-direction: column;
        gap: 4px;
        transition: all 0.2s;
        box-shadow: 0 1px 3px rgba(37, 99, 235, 0.05);
    }

    .shift-time-entry:hover {
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.12);
        transform: translateX(2px);
    }

    .shift-time {
        font-size: 14px;
        color: #2563eb;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .shift-dates {
        font-size: 12px;
        color: #4b6a8b;
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .shift-count-badge {
        display: inline-block;
        background: #dbeafe;
        color: #1d4ed8;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }

    .shift-no-data {
        text-align: center;
        padding: 40px 20px;
        color: #8a9bb5;
        background: linear-gradient(135deg, #f8fafc 0%, #fafcff 100%);
        border-radius: 10px;
        border: 1px dashed #dce4ed;
    }

    .shift-no-data i {
        font-size: 36px;
        color: #2563eb;
        display: block;
        margin-bottom: 10px;
        opacity: 0.3;
    }

    .shift-no-data div {
        font-size: 14px;
        font-weight: 500;
    }

    /* Month Selector for Lesson Plan */
    .month-selector {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
        margin-bottom: 12px;
    }
    .month-btn {
        padding: 6px 16px;
        border-radius: 20px;
        border: 1.5px solid #dce4ed;
        background: white;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        color: #4b6a8b;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .month-btn:hover {
        border-color: #2563eb;
        color: #2563eb;
        background: #f0f4ff;
        transform: translateY(-2px);
    }
    .month-btn.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }
    .month-btn .working-days-badge {
        background: #dbeafe;
        color: #1d4ed8;
        padding: 0 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
    }
    .month-btn.active .working-days-badge {
        background: rgba(255,255,255,0.2);
        color: white;
    }

    /* Working Days Info Box */
    .working-days-info {
        background: #f0f7ff;
        border: 1.5px solid #dbeafe;
        border-radius: 12px;
        padding: 12px 16px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .working-days-info .label {
        font-weight: 600;
        color: #0a1e3c;
        font-size: 13px;
    }
    .working-days-info .value {
        background: #2563eb;
        color: white;
        padding: 2px 14px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 700;
    }
    .working-days-info .detail {
        color: #4b6a8b;
        font-size: 12px;
    }

    /* Topic Count Info */
    .topic-count-info {
        background: #fef3c7;
        border: 1.5px solid #f59e0b;
        border-radius: 12px;
        padding: 10px 16px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .topic-count-info .label {
        font-weight: 600;
        color: #92400e;
        font-size: 13px;
    }
    .topic-count-info .value {
        background: #f59e0b;
        color: white;
        padding: 2px 14px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 700;
    }
    .topic-count-info .detail {
        color: #92400e;
        font-size: 12px;
    }

    @media (max-width: 820px) {
        /*.create-page { padding: 16px; }*/
        .create-card { padding: 20px; }
        .form-grid { grid-template-columns: 1fr; }
        .topic-row .topic-fields { grid-template-columns: 1fr; }
        .action-buttons { flex-direction: column; }
        .action-buttons .btn { justify-content: center; }
        .plan-level-selector { flex-direction: column; }
        .plan-level-btn { min-width: auto; }
        .date-range-display {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }
        .date-range-display .range-arrow { transform: rotate(90deg); }
        .duration-input-group {
            flex-direction: column;
            align-items: stretch;
        }
        .duration-input-group input[type="number"] { width: 100%; } 
        .preset-buttons { justify-content: flex-start; flex-wrap: nowrap; overflow-x: auto; padding-bottom: 6px; }
        .preset-buttons::-webkit-scrollbar { height: 4px; }
        .quick-stats { grid-template-columns: 1fr 1fr; }
        .plan-level-container { padding: 16px; }
        .syllabus-container { padding: 16px; } 
        .syllabus-container::before { left: 10px; font-size: 10px; } 
        .shift-tabs-content { padding: 12px 14px; max-height: 280px; } 
        .shift-time-entry { padding: 10px 12px; }
        .shift-weekday-header { font-size: 14px; }
        .shift-tab-button { font-size: 12px; padding: 6px 12px; }
        .shift-tabs-header { max-height: 160px; padding: 6px; }
        .working-days-info { flex-direction: column; text-align: center; }
        .month-btn { font-size: 12px; padding: 4px 12px; }
        .topic-distribution .dist-day { min-width: 100px; font-size: 12px; }
        .topic-count-info { flex-direction: column; text-align: center; }
    }

    @media (max-width: 480px) {
        .shift-tabs-content { padding: 10px 12px; max-height: 240px; }
        .shift-time-entry { padding: 8px 10px; }
        .shift-time { font-size: 12px; }
        .shift-dates { font-size: 11px; }
        .shift-tab-button { font-size: 11px; padding: 5px 10px; }
        .shift-tabs-header { max-height: 140px; padding: 4px; }
        .shift-tabs-container { max-height: 400px; }
        .preset-btn { font-size: 10px; padding: 4px 10px; }
        .preset-buttons { gap: 4px; }
        .month-btn { font-size: 11px; padding: 3px 10px; }
        .topic-distribution .dist-day { min-width: 80px; font-size: 11px; }
    }
    .topic-tabs-container {
        border: 1.5px solid #dce4ed;
        border-radius: 16px;
        overflow: hidden;
        background: white;
        margin-top: 8px;
    }

    .topic-tabs-header {
        display: flex;
        gap: 4px;
        background: #f8fafc;
        border-bottom: 2px solid #eef2f6;
        padding: 8px 12px;
        overflow-x: auto;
        flex-wrap: nowrap;
        min-height: 50px;
        align-items: center;
    }

    .topic-tabs-header::-webkit-scrollbar {
        height: 4px;
    }
    .topic-tabs-header::-webkit-scrollbar-track {
        background: #eef2f6;
        border-radius: 2px;
    }
    .topic-tabs-header::-webkit-scrollbar-thumb {
        background: #2563eb;
        border-radius: 2px;
    }

    .topic-tab-btn {
        padding: 6px 16px;
        border: none;
        background: transparent;
        color: #4b6a8b;
        font-weight: 600;
        font-size: 13px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }

    .topic-tab-btn:hover {
        background: #f0f4ff;
        color: #2563eb;
    }

    .topic-tab-btn.active {
        background: #2563eb;
        color: white;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
    }

    .topic-tab-btn .tab-badge {
        background: rgba(255,255,255,0.2);
        padding: 0 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 700;
    }

    .topic-tab-btn.active .tab-badge {
        background: rgba(255,255,255,0.25);
        color: white;
    }

    .topic-tab-btn .tab-date {
        font-size: 10px;
        font-weight: 400;
        color: #8a9bb5;
        margin-left: 4px;
    }

    .topic-tab-btn.active .tab-date {
        color: rgba(255,255,255,0.7);
    }

    .topic-tab-content {
        padding: 20px 24px;
        background: white;
        min-height: 120px;
        display: none;
        animation: fadeIn 0.3s ease-in;
    }

    .topic-tab-content.active {
        display: block;
    }

    .topic-tab-content .topic-form-fields {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .topic-tab-content .topic-form-fields .full-width {
        grid-column: 1 / -1;
    }

    .topic-tab-content .field-label {
        font-size: 13px;
        font-weight: 500;
        color: #4b6a8b;
        display: block;
        margin-bottom: 4px;
    }

    .topic-tab-content .field-label i {
        color: #2563eb;
        margin-right: 4px;
    }

    .repeatable-input-row {
        display: flex;
        gap: 6px;
        align-items: center;
        margin-bottom: 6px;
    }

    .repeatable-input-row .form-control {
        flex: 1;
    }

    .repeatable-input-row .btn {
        flex: 0 0 auto;
    }

    .add-repeatable-row {
        margin-top: 2px;
    }

    .topic-tab-content .topic-date-display {
        font-size: 12px;
        color: #4b6a8b;
        background: #f0f4ff;
        padding: 4px 12px;
        border-radius: 16px;
        border: 1px solid #dbeafe;
        display: inline-block;
        margin-bottom: 12px;
    }

    .topic-tab-actions {
        display: flex;
        gap: 8px;
        margin-left: auto;
        flex-shrink: 0;
    }

    .topic-tab-actions .btn-small {
        padding: 4px 12px;
        font-size: 11px;
    }

    .topic-empty-state {
        text-align: center;
        padding: 30px;
        color: #8a9bb5;
    }

    .topic-empty-state i {
        font-size: 40px;
        color: #2563eb;
        margin-bottom: 12px;
        display: block;
        opacity: 0.5;
    }

    /* Quick add buttons */
    .topic-controls {
        display: flex;
        gap: 10px;
        margin-bottom: 16px;
        flex-wrap: wrap;
        align-items: center;
    }

    .topic-controls .btn {
        font-size: 13px;
        padding: 8px 18px;
    }

    @media (max-width: 820px) {
        .topic-tab-content .topic-form-fields {
            grid-template-columns: 1fr;
        }
        .topic-tabs-header {
            padding: 6px 8px;
            gap: 2px;
        }
        .topic-tab-btn {
            font-size: 12px;
            padding: 4px 12px;
        }
        .topic-tab-content {
            padding: 16px;
        }
        .topic-tab-actions .btn-small {
            padding: 2px 8px;
            font-size: 10px;
        }
    }

    @media (max-width: 480px) {
        .topic-tab-btn {
            font-size: 10px;
            padding: 3px 8px;
        }
        .topic-tab-btn .tab-date {
            font-size: 8px;
        }
        .topic-tab-content {
            padding: 12px;
        }
    }
</style>
@endsection

@section('content')
@php
    $plannerUser = auth()->user();
    $plannerIsAdmin = $plannerUser && $plannerUser->hasAnyRole(['admin', 'superadmin', 'super_admin', 'institute_admin']);
    $plannerEmployees = $plannerIsAdmin
        ? \App\Models\EmployeeDetails::where('institute_id', $plannerUser->institute_id)->orderBy('name')->get()
        : collect();
    $plannerDepartments = $plannerIsAdmin
        ? \App\Models\Departments::where('institute_id', $plannerUser->institute_id)->orderBy('department')->get()
        : collect();
    $plannerSelectedEmployee = $plannerEmployees->firstWhere('employee_id', request('employee_id'));
    $plannerSelectedDepartmentId = request('department_id') ?: optional($plannerSelectedEmployee)->department_id;
    $plannerContextQuery = request()->only(['department_id', 'employee_id']);
@endphp

<div class="top-bar">
    <div class="logo-area">
        <div class="logo-icon"><i class="fas fa-chalkboard-teacher"></i></div>
        <span class="logo-text">Daily<span>Planner</span></span>
    </div>
    <div class="nav-wrapper">
        @if($plannerIsAdmin)
            <label class="employee-selector" for="planner-employee">
                <i class="fas fa-user-tie"></i>
                <span>Department</span>
                <select id="planner-department" onchange="filterPlannerEmployees(this.value)">
                    <option value="">Select department</option>
                    @foreach($plannerDepartments as $plannerDepartment)
                        <option value="{{ $plannerDepartment->department_id }}" @if((string) $plannerSelectedDepartmentId === (string) $plannerDepartment->department_id) selected @endif>
                            {{ $plannerDepartment->department }}
                        </option>
                    @endforeach
                </select>
            </label>
            <label class="employee-selector" for="planner-employee">
                <i class="fas fa-user-tie"></i>
                <span>Employee</span>
                <select id="planner-employee" onchange="changePlannerEmployee(this.value)" disabled>
                    <option value="">Select employee</option>
                    @foreach($plannerEmployees as $plannerEmployee)
                        @php
                            $plannerEmployeeName = $plannerEmployee->name;
                            if (!$plannerEmployeeName) {
                                $plannerEmployeeName = $plannerEmployee->employee_id;
                            }
                        @endphp
                        <option value="{{ $plannerEmployee->employee_id }}" data-department="{{ $plannerEmployee->department_id }}" {{ (string) request('employee_id') === (string) $plannerEmployee->employee_id ? 'selected' : '' }}>
                            {{ $plannerEmployeeName }}
                        </option>
                    @endforeach
                </select>
            </label>
        @endif
        <div class="nav-tabs">
            <a href="{{ route('lesson-planner.dashboard', $plannerContextQuery) }}" class="nav-tab">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="{{ route('lesson-planner.plans', $plannerContextQuery) }}" class="nav-tab active">
                <i class="fas fa-calendar-alt"></i> Calendar
            </a>
            <a href="{{ route('lesson-planner.review', $plannerContextQuery) }}" class="nav-tab">
                <i class="fas fa-check-double"></i> Performance
            </a>
            <a href="{{ route('lesson-planner.reports', $plannerContextQuery) }}" class="nav-tab">
                <i class="fas fa-file-alt"></i> Reports
            </a>
        </div>
        <a href="{{ route('lesson-planner.new', $plannerContextQuery) }}" class="btn-new">
            <i class="fas fa-plus-circle"></i> New Plan
        </a>
    </div>
</div>

<div class="content-wrapper">
    <div class="create-page">
        <div class="create-card">
            <h2 class="page-title"><i class="fas fa-plus-circle"></i> Create Lesson Plan</h2>
            <p class="page-subtitle">
                <i class="fas fa-info-circle"></i> Select a syllabus and month to automatically distribute chapters based on employee shift schedule.
            </p>

        <div class="success-message" id="successMessage"><i class="fas fa-check-circle"></i> Lesson plan saved successfully!</div>

        <form id="lessonForm" onsubmit="saveLessonPlan(event)">
            <!-- ===== COURSE INFORMATION ===== -->
            <div class="form-section">
                <div class="section-title"><i class="fas fa-info-circle"></i> Course Information</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label"><i class="bi bi-building"></i> Category <span class="form-required">*</span></label>
                        <select id="category" name="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->department_category_id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="bi bi-building-fill"></i> Department <span class="form-required">*</span></label>
                        <select id="department" name="department_id" class="form-control" required disabled>
                            <option value="">Select Category first</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-graduation-cap"></i> Course Type <span class="form-required">*</span></label>
                        <select id="courseType" name="course_type" class="form-control" required disabled>
                            <option value="">Select Course Type</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-layer-group"></i> Course Sub Type <span class="form-required">*</span></label>
                        <select id="subType" name="sub_type" class="form-control" required disabled>
                            <option value="">Select Course Sub Type</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-calendar-alt"></i> Academic Year <span class="form-required">*</span></label>
                        <select id="academic_year" name="academic_year" class="form-control" required disabled>
                            <option value="">Select Course Sub Type first</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-book"></i> Subject <span class="form-required">*</span></label>
                        <select id="subject" name="subject_id" class="form-control" required onchange="loadSyllabi()" disabled>
                            <option value="">Select Subject</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-chalkboard-teacher"></i> Teacher <span class="form-required">*</span></label>
                        <select id="formTeacher" name="teacher_id" required disabled>
                            <option value="">Select Teacher</option>
                        </select>
                        <div id="teacherShiftInfo" style="margin-top:8px; font-size:13px; color:#4b6a8b;"></div>
                    </div>

                    <div class="form-group form-group-full">
                        <div class="plan-level-container">
                            <label class="form-label"><i class="fas fa-tag"></i> Plan Type <span class="form-required">*</span></label>
                            <div class="plan-level-selector">
                                <button type="button" class="plan-level-btn active" data-level="day" onclick="selectPlanLevel('day')">
                                    <span class="icon">📅</span>
                                    Day Plan
                                </button>
                                <button type="button" class="plan-level-btn" data-level="week" onclick="selectPlanLevel('week')">
                                    <span class="icon">📋</span>
                                    Week Plan
                                </button>
                            </div>
                            <div style="font-size:13px; color:#4b6a8b; padding:6px 4px 0 4px;">
                                <i class="fas fa-lightbulb" style="color:#2563eb;"></i>
                                <span id="level-description">📌 Plan based on teacher's shift schedule - Monthly</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-full">
                        <label class="form-label"><i class="fas fa-calendar-alt"></i> Select Month <span class="form-required">*</span></label>
                        <div id="monthSelector" class="month-selector">
                            <span style="color:#8a9bb5; font-size:13px;">Select a teacher first to see available months</span>
                        </div>
                        <div class="form-hint"><i class="fas fa-info-circle"></i> Select the month for which you want to create the lesson plan</div>
                    </div>

                    <div class="form-group form-group-full">
                        <div class="working-days-info" id="workingDaysInfo">
                            <span class="label"><i class="fas fa-clock"></i> Working Days:</span>
                            <span class="value" id="workingDaysCount">0</span>
                            <span class="detail" id="workingDaysDetail">Select a teacher and month to see working days</span>
                        </div>
                    </div>

                    <div class="form-group form-group-full">
                        <div class="topic-count-info" id="topicCountInfo">
                            <span class="label"><i class="fas fa-list"></i> Topics to Create:</span>
                            <span class="value" id="topicsToCreate">0</span>
                            <span class="detail" id="topicsDetail">Based on selected plan type and working days</span>
                        </div>
                    </div>

                    <div class="form-group form-group-full">
                        <div class="date-range-display" id="dateRangeDisplay">
                            <div class="range-item">
                                <span class="label"><i class="fas fa-play"></i> Start:</span>
                                <span class="value" id="startDateDisplay">-</span>
                            </div>
                            <span class="range-arrow">➜</span>
                            <div class="range-item">
                                <span class="label"><i class="fas fa-stop"></i> End:</span>
                                <span class="value" id="endDateDisplay">-</span>
                            </div>
                            <span class="range-arrow">|</span>
                            <div class="range-item">
                                <span class="duration-badge" id="durationDisplay"><i class="fas fa-tag"></i> Monthly Plan</span>
                            </div>
                            <span class="days-count" id="daysCountDisplay"></span>
                        </div>
                    </div>

                    <div class="form-group form-group-full">
                        <label class="form-label"><i class="fas fa-heading"></i> Plan Title (optional)</label>
                        <input type="text" id="formTitle" placeholder="e.g. Physics - Semester 1 - January" />
                        <div class="form-hint"><i class="fas fa-info-circle"></i> If left blank, title will be auto-generated</div>
                    </div>
                </div>
            </div>

            <!-- ===== SYLLABUS SELECTION ===== --> 
            <div class="form-section">
                <div class="section-title"><i class="fas fa-book-open"></i> Syllabus Selection &   Distribution</div>  
                <div class="syllabus-container">     
                    <p style="color: #4b6a8b; font-size: 14px; margin-bottom: 16px; margin-top: 8px;">
                        <i class="fas fa-info-circle" style="color: #2563eb;"></i> 
                        Select a syllabus. Topics will be distributed across the teacher's working days for the selected month. 
                        <br><strong>Note:</strong> If Day Plan selected, one topic per working day. If Week Plan selected, one topic per week.  
                    </p> 
                    
                    <div id="subjectInfo" style="display:none; background: #dbeafe; padding: 8px 16px; border-radius: 12px; margin-bottom: 12px;">
                        <span id="subjectDisplay" style="font-weight: 600; color: #1d4ed8;"></span>
                    </div>

                    <div id="syllabusList">
                        <div class="syllabus-empty">
                            <i class="fas fa-book"></i>
                            <div>Please select a subject first to view available syllabi</div>
                        </div>
                    </div>

                    <div id="syllabusPreviewWrapper" style="margin-top:12px;"></div>

                    <div style="margin-top: 16px; display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                        <button type="button" class="btn btn-success btn-small" id="distributeBtn" onclick="distributeSyllabus()" disabled>
                            <i class="fas fa-arrow-right"></i> Distribute Topics
                        </button>
                        <button type="button" class="btn btn-secondary btn-small" onclick="clearSyllabusSelection()" id="clearBtn" style="display:none;">
                            <i class="fas fa-times"></i> Clear Selection
                        </button>
                        <span id="selectedInfo" style="font-size:13px; color:#4b6a8b;"></span>
                    </div>

                    <div id="distributionResult" class="topic-distribution" style="display:none; margin-top:16px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; flex-wrap:wrap;">
                            <div style="font-weight:600; color:#0a1e3c;">
                                <i class="fas fa-list-check" style="color:#2563eb;"></i> Topic Distribution Preview
                            </div>
                            <div>
                                <span id="distTotalTopics" style="font-size:12px; color:#4b6a8b;"></span>
                            </div>
                        </div>
                        <div id="distributedTopics"></div>
                        <div style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap;">
                            <button type="button" class="btn btn-success btn-small" onclick="addDistributedTopics()">
                                <i class="fas fa-plus"></i> Add All to Plan
                            </button>
                            <button type="button" class="btn btn-secondary btn-small" onclick="hideDistribution()">
                                <i class="fas fa-times"></i> Close Preview
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== QUICK STATS ===== -->
            <div class="form-section">
                <div class="section-title"><i class="fas fa-chart-bar"></i> Plan Summary</div>
                <div class="quick-stats">
                    <div class="stat-item">
                        <div class="stat-value" id="statDays">0</div>
                        <div class="stat-label"><i class="fas fa-calendar-day"></i> Working Days</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="statTopics">0</div>
                        <div class="stat-label"><i class="fas fa-list"></i> Topics in Plan</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="statWeeks">0</div>
                        <div class="stat-label"><i class="fas fa-calendar-week"></i> Weeks</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="statSyllabusTopics">0</div>
                        <div class="stat-label"><i class="fas fa-book"></i> Chapters</div>
                    </div>
                </div>
            </div>

            <!-- ===== TOPICS SECTION - TABS VERSION ===== -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-book"></i> Lesson Topics
                    <span class="count" id="topic-count">(0 topics)</span>
                    <span class="level-badge day" id="levelBadge">Day</span>
                </div>
                
                <div class="topic-controls">
                    <button type="button" class="btn btn-primary btn-small" onclick="addTopicTab()"><i class="fas fa-plus"></i> Add Topic</button>
                    <button type="button" class="btn btn-secondary btn-small" onclick="addMultipleTopics()"><i class="fas fa-layer-group"></i> Add 3 Topics</button>
                    <button type="button" class="btn btn-secondary btn-small" onclick="autoGenerateTopics()"><i class="fas fa-magic"></i> Auto-generate</button>
                    <button type="button" class="btn btn-danger btn-small" onclick="clearAllTopics()"><i class="fas fa-trash"></i> Clear All</button>
                    <span style="font-size:13px; color:#4b6a8b; margin-left:auto;" id="topicCountDisplay">0 topics</span>
                </div>

                <div id="topicTabsContainer" class="topic-tabs-container">
                    <div class="topic-tabs-header" id="topicTabsHeader">
                        <!-- Tabs will be added here -->
                    </div>
                    <div id="topicTabsContent">
                        <!-- Tab content will be added here -->
                        <div class="topic-empty-state" id="emptyTopicsMessage">
                            <i class="fas fa-book-open"></i>
                            <div>No topics added yet</div>
                            <div style="font-size:13px; margin-top:4px; color:#4b6a8b;">Add topics manually or use the syllabus distribution above</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== ACTION BUTTONS ===== -->
            <div class="action-buttons">
                <button type="submit" class="btn btn-secondary"><i class="fas fa-save"></i> Save as Draft</button>
                <button type="button" class="btn btn-success" onclick="submitLessonPlan()"><i class="fas fa-paper-plane"></i> Save & Activate</button>
                <a href="{{ route('lesson-planner.plans') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
            </div>
        </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function filterPlannerEmployees(departmentId) {
        const employeeSelect = document.getElementById('planner-employee');
        const selectedEmployeeId = @json(request('employee_id'));
        employeeSelect.disabled = !departmentId;

        Array.from(employeeSelect.options).forEach(option => {
            const isEmployee = option.value !== '';
            const matchesDepartment = option.dataset.department === departmentId;
            option.hidden = isEmployee && !matchesDepartment;
            if (isEmployee && !matchesDepartment) option.selected = false;
        });

        const selectedOption = selectedEmployeeId
            ? employeeSelect.querySelector(`option[value="${selectedEmployeeId}"]`)
            : null;
        employeeSelect.value = selectedOption && !selectedOption.hidden ? selectedEmployeeId : '';
    }

    function changePlannerEmployee(employeeId) {
        const url = new URL(window.location.href);
        if (employeeId) {
            url.searchParams.set('employee_id', employeeId);
            url.searchParams.set('department_id', document.getElementById('planner-department').value);
        } else {
            url.searchParams.delete('employee_id');
        }
        window.location.href = url.toString();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const departmentSelect = document.getElementById('planner-department');
        if (departmentSelect) filterPlannerEmployees(departmentSelect.value);
    });

    // ===== STATIC SYLLABUS DATA WITH CHAPTERS =====
    const allSyllabi = [
        // Physics
        {
            id: 1,
            subject: 'Physics',
            title: 'Physics - Class 11',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Units and Measurements',
                'Chapter 2: Motion in a Straight Line',
                'Chapter 3: Motion in a Plane',
                'Chapter 4: Laws of Motion',
                'Chapter 5: Work, Energy and Power',
                'Chapter 6: System of Particles',
                'Chapter 7: Rotational Motion',
                'Chapter 8: Gravitation'
            ],
            topics: []
        },
        {
            id: 2,
            subject: 'Physics',
            title: 'Physics - Class 12',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Electric Charges and Fields',
                'Chapter 2: Electrostatic Potential and Capacitance',
                'Chapter 3: Current Electricity',
                'Chapter 4: Moving Charges and Magnetism',
                'Chapter 5: Magnetism and Matter',
                'Chapter 6: Electromagnetic Induction',
                'Chapter 7: Alternating Current',
                'Chapter 8: Electromagnetic Waves'
            ],
            topics: []
        },
        // Chemistry
        {
            id: 3,
            subject: 'Chemistry',
            title: 'Chemistry - Class 11',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Some Basic Concepts of Chemistry',
                'Chapter 2: Structure of Atom',
                'Chapter 3: Classification of Elements',
                'Chapter 4: Chemical Bonding',
                'Chapter 5: States of Matter',
                'Chapter 6: Thermodynamics',
                'Chapter 7: Equilibrium',
                'Chapter 8: Redox Reactions'
            ],
            topics: []
        },
        {
            id: 4,
            subject: 'Chemistry',
            title: 'Chemistry - Class 12',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Solutions',
                'Chapter 2: Electrochemistry',
                'Chapter 3: Chemical Kinetics',
                'Chapter 4: Surface Chemistry',
                'Chapter 5: Solid State',
                'Chapter 6: Coordination Compounds',
                'Chapter 7: Aldehydes and Ketones',
                'Chapter 8: Carboxylic Acids'
            ],
            topics: []
        },
        // Biology
        {
            id: 5,
            subject: 'Biology',
            title: 'Biology - Class 11',
            month: 'Full Year',
            chapters: [
                'Chapter 1: The Living World',
                'Chapter 2: Biological Classification',
                'Chapter 3: Plant Kingdom',
                'Chapter 4: Animal Kingdom',
                'Chapter 5: Morphology of Flowering Plants',
                'Chapter 6: Anatomy of Flowering Plants',
                'Chapter 7: Cell Structure',
                'Chapter 8: Cell Division'
            ],
            topics: []
        },
        {
            id: 6,
            subject: 'Biology',
            title: 'Biology - Class 12',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Reproduction',
                'Chapter 2: Genetics',
                'Chapter 3: Evolution',
                'Chapter 4: Human Health and Disease',
                'Chapter 5: Biotechnology',
                'Chapter 6: Ecology',
                'Chapter 7: Environmental Issues',
                'Chapter 8: Biodiversity'
            ],
            topics: []
        },
        // Mathematics
        {
            id: 7,
            subject: 'Mathematics',
            title: 'Mathematics - Class 11',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Sets',
                'Chapter 2: Relations and Functions',
                'Chapter 3: Trigonometric Functions',
                'Chapter 4: Principle of Mathematical Induction',
                'Chapter 5: Complex Numbers',
                'Chapter 6: Quadratic Equations',
                'Chapter 7: Linear Inequalities',
                'Chapter 8: Permutations and Combinations'
            ],
            topics: []
        },
        {
            id: 8,
            subject: 'Mathematics',
            title: 'Mathematics - Class 12',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Relations and Functions',
                'Chapter 2: Inverse Trigonometric Functions',
                'Chapter 3: Matrices',
                'Chapter 4: Determinants',
                'Chapter 5: Continuity and Differentiability',
                'Chapter 6: Application of Derivatives',
                'Chapter 7: Integrals',
                'Chapter 8: Application of Integrals'
            ],
            topics: []
        },
        // English
        {
            id: 9,
            subject: 'English',
            title: 'English - Literature & Grammar',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Reading Comprehension',
                'Chapter 2: Writing Skills',
                'Chapter 3: Grammar',
                'Chapter 4: Literature',
                'Chapter 5: Poetry Analysis',
                'Chapter 6: Prose Analysis',
                'Chapter 7: Drama Analysis',
                'Chapter 8: Essay Writing'
            ],
            topics: []
        },
        // Computer Science
        {
            id: 10,
            subject: 'Computer Science',
            title: 'Computer Science - Fundamentals',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Computer Basics',
                'Chapter 2: Operating Systems',
                'Chapter 3: Programming Concepts',
                'Chapter 4: Data Structures',
                'Chapter 5: Algorithms',
                'Chapter 6: Database Management',
                'Chapter 7: Networking',
                'Chapter 8: Cybersecurity'
            ],
            topics: []
        }
    ];

    // Populate topics from chapters for each syllabus
    allSyllabi.forEach(s => {
        s.topics = s.chapters.map(chapter => {
            return {
                title: chapter,
                description: '',
                full: chapter
            };
        });
    });

    let syllabiData = allSyllabi;
    let selectedSyllabusId = null;
    let distributedTopics = [];
    let currentPlanLevel = 'day';
    let currentTeacherShifts = null;
    let workingDays = [];
    let selectedMonth = '';
    let selectedYear = '';
    let courseDateRange = null;
    let topicCounter = 0;
    let activeTopicTab = 0;

    // ===== WORKING DAYS FUNCTIONS =====
    function getWorkingDaysForMonth(shiftData, month, year) {
        const days = [];
        if (!shiftData) return days;
        
        const allShiftDates = [];
        const months = Object.keys(shiftData);
        
        months.forEach(m => {
            const monthMatch = m.toLowerCase().includes(month.toLowerCase()) || 
                              m.includes(month) ||
                              (new Date(m).getMonth() === new Date(month + ' 1, ' + year).getMonth());
            
            if (!monthMatch && !m.includes(month)) return;
            
            const weekdays = shiftData[m] || {};
            Object.keys(weekdays).forEach(weekday => {
                const entries = weekdays[weekday] || [];
                entries.forEach(entry => {
                    if (entry.dates && Array.isArray(entry.dates)) {
                        entry.dates.forEach(dateStr => {
                            const dateObj = new Date(dateStr + 'T00:00:00');
                            if (dateObj.getMonth() === new Date(month + ' 1, ' + year).getMonth() &&
                                dateObj.getFullYear() === parseInt(year)) {
                                allShiftDates.push({
                                    date: dateStr,
                                    weekday: weekday,
                                    time: `${entry.start || ''} - ${entry.end || ''}`,
                                    month: m
                                });
                            }
                        });
                    }
                });
            });
        });
        
        allShiftDates.sort((a, b) => new Date(a.date) - new Date(b.date));
        
        const uniqueDates = [];
        const seen = new Set();
        allShiftDates.forEach(item => {
            if (!seen.has(item.date)) {
                seen.add(item.date);
                uniqueDates.push(item);
            }
        });
        
        return uniqueDates;
    }

    function getAvailableMonths(shiftData) {
        const months = new Set();
        if (!shiftData) return [];
        
        const monthNames = Object.keys(shiftData);
        monthNames.forEach(m => {
            const weekdays = shiftData[m] || {};
            Object.keys(weekdays).forEach(weekday => {
                const entries = weekdays[weekday] || [];
                entries.forEach(entry => {
                    if (entry.dates && Array.isArray(entry.dates)) {
                        entry.dates.forEach(dateStr => {
                            const dateObj = new Date(dateStr + 'T00:00:00');
                            const monthYear = dateObj.toLocaleString('default', { month: 'long', year: 'numeric' });
                            months.add(monthYear);
                        });
                    }
                });
            });
        });
        
        return Array.from(months).sort((a, b) => {
            return new Date(a) - new Date(b);
        });
    }

    function filterMonthsByCourseDateRange(months) {
        if (!courseDateRange || !courseDateRange.startDate || !courseDateRange.endDate) {
            return months;
        }

        const startDate = new Date(courseDateRange.startDate + 'T00:00:00');
        const endDate = new Date(courseDateRange.endDate + 'T00:00:00');
        if (isNaN(startDate.getTime()) || isNaN(endDate.getTime()) || startDate > endDate) {
            return months;
        }

        return months.filter(monthYear => {
            const [monthName, year] = monthYear.split(' ');
            const monthDate = new Date(`${monthName} 1, ${year}`);
            const monthStart = new Date(monthDate.getFullYear(), monthDate.getMonth(), 1);
            const monthEnd = new Date(monthDate.getFullYear(), monthDate.getMonth() + 1, 0, 23, 59, 59, 999);

            return monthStart <= endDate && monthEnd >= startDate;
        });
    }

    function getWeeksFromWorkingDays(workingDaysList) {
        if (!workingDaysList || workingDaysList.length === 0) return [];
        
        const weeks = [];
        let currentWeek = [];
        let currentWeekStart = null;
        
        workingDaysList.forEach((day, index) => {
            const dateObj = new Date(day.date + 'T00:00:00');
            const weekStart = new Date(dateObj);
            const dayOfWeek = weekStart.getDay();
            weekStart.setDate(weekStart.getDate() - (dayOfWeek === 0 ? 6 : dayOfWeek - 1));
            const weekKey = weekStart.toISOString().split('T')[0];
            
            if (index === 0) {
                currentWeek.push(day);
                currentWeekStart = weekKey;
            } else {
                if (weekKey !== currentWeekStart) {
                    weeks.push(currentWeek);
                    currentWeek = [day];
                    currentWeekStart = weekKey;
                } else {
                    currentWeek.push(day);
                }
            }
        });
        
        if (currentWeek.length > 0) {
            weeks.push(currentWeek);
        }
        
        return weeks;
    }

    function updateMonthSelector() {
        const teacherId = $('#formTeacher').val();
        if (!teacherId || !window._subjectShiftData) {
            $('#monthSelector').html('<span style="color:#8a9bb5; font-size:13px;">Select a teacher first to see available months</span>');
            return;
        }
        
        const shifts = window._subjectShiftData[teacherId]; 
        if (!shifts) {
            $('#monthSelector').html('<span style="color:#8a9bb5; font-size:13px;">No shift data available for this teacher</span>');
            return;
        }
        
        let availableMonths = getAvailableMonths(shifts);
        availableMonths = filterMonthsByCourseDateRange(availableMonths);

        if (availableMonths.length === 0) {
            $('#monthSelector').html('<span style="color:#8a9bb5; font-size:13px;">No months available within the selected course date range.</span>');
            return;
        }
        
        let html = '';
        availableMonths.forEach((monthYear, idx) => {
            const [month, year] = monthYear.split(' ');
            const workingDaysList = getWorkingDaysForMonth(shifts, month, year);
            const isActive = idx === 0 ? 'active' : '';
            if (idx === 0) {
                selectedMonth = month;
                selectedYear = year;
            }
            html += `
                <button type="button" class="month-btn ${isActive}" onclick="selectMonth('${month}', '${year}')" data-month="${month}" data-year="${year}">
                    ${month} ${year}
                    <span class="working-days-badge">${workingDaysList.length} days</span>
                </button>
            `;
        });
        
        $('#monthSelector').html(html);
        if (availableMonths.length > 0) {
            const [firstMonth, firstYear] = availableMonths[0].split(' ');
            selectMonth(firstMonth, firstYear);
        }
    }

    function selectMonth(month, year) {
        selectedMonth = month;
        selectedYear = year;
        
        $('.month-btn').removeClass('active');
        $(`.month-btn[data-month="${month}"][data-year="${year}"]`).addClass('active'); 
        
        updateWorkingDays(month, year);
    }

    function setMonthRangeDates(month, year) {
        const monthIndex = new Date(`${month} 1, ${year}`).getMonth();
        const firstDate = new Date(year, monthIndex, 1);
        const lastDate = new Date(year, monthIndex + 1, 0);

        $('#formStartDate').val(firstDate.toISOString().split('T')[0]);
        $('#formEndDate').val(lastDate.toISOString().split('T')[0]);
    }

    function updateWorkingDays(month, year) {
        const teacherId = $('#formTeacher').val();
        if (!teacherId || !month || !year) {
            $('#workingDaysCount').text('0');
            $('#workingDaysDetail').text('Select a teacher and month to see working days');
            return;
        }
        
        const shifts = window._subjectShiftData[teacherId];
        if (!shifts) {
            $('#workingDaysCount').text('0');
            $('#workingDaysDetail').text('No shift data available for this teacher'); 
            setMonthRangeDates(month, year);
            return;
        }
        
        const workingDaysList = getWorkingDaysForMonth(shifts, month, year);
        workingDays = workingDaysList;
        
        $('#workingDaysCount').text(workingDaysList.length);
        $('#workingDaysDetail').text(
            workingDaysList.length > 0 
                ? `${workingDaysList.length} working days in ${month} ${year}`
                : 'No working days found for this month'
        );
        
        // Calculate topics to create based on plan type
        const planLevel = currentPlanLevel;
        let topicsCount = 0;
        let weeksCount = 0;
        let displayText = '';
        
        if (planLevel === 'day') {
            topicsCount = workingDaysList.length;
            weeksCount = Math.ceil(workingDaysList.length / 5);
            displayText = `1 topic per working day (${topicsCount} topics)`;
        } else {
            const weeks = getWeeksFromWorkingDays(workingDaysList);
            weeksCount = weeks.length;
            topicsCount = weeksCount;
            displayText = `1 topic per week (${topicsCount} topics over ${weeksCount} weeks)`; 
        }
        
        $('#topicsToCreate').text(topicsCount);
        $('#topicsDetail').text(displayText);
        
        // Update date range display
        if (workingDaysList.length > 0) {
            const firstDate = workingDaysList[0].date;
            const lastDate = workingDaysList[workingDaysList.length - 1].date;
            
            const start = new Date(firstDate + 'T00:00:00');
            const end = new Date(lastDate + 'T00:00:00');
            
            const startStr = start.toLocaleDateString('en-US', { 
                weekday: 'short', 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric' 
            });
            const endStr = end.toLocaleDateString('en-US', { 
                weekday: 'short', 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric' 
            });
            
            $('#startDateDisplay').text(startStr); 
            $('#endDateDisplay').text(endStr);
            $('#durationDisplay').html(`<i class="fas fa-tag"></i> ${planLevel === 'day' ? 'Day' : 'Week'} Plan - ${month} ${year}`);
            $('#daysCountDisplay').text(`📊 ${workingDaysList.length} working days`); 
            
            $('#formStartDate').val(firstDate);
            $('#formEndDate').val(lastDate);
        } else {
            setMonthRangeDates(month, year);
            $('#startDateDisplay').text(`${month} 1, ${year}`);
            $('#endDateDisplay').text(`${month} ${new Date(year, new Date(`${month} 1, ${year}`).getMonth() + 1, 0).getDate()}, ${year}`);
            $('#durationDisplay').html(`<i class="fas fa-tag"></i> ${planLevel === 'day' ? 'Day' : 'Week'} Plan - ${month} ${year}`);
            $('#daysCountDisplay').text('📊 course date range month');
        }
        
        updateQuickStats();
        updateAllTopicDates();
        updateTopicTabs(); // Rebuild tabs when working days change
    }

    // ===== SYLLABUS FUNCTIONS =====
    function loadCourseDateRange() {
        const subjectId = $('#subject').val();
        const courseDetailId = $('#subType').val();

        if (!subjectId || !courseDetailId) {
            courseDateRange = null;
            updateMonthSelector();
            return;
        }

        fetch(`/ajax/get-subject-course-info?subject_id=${encodeURIComponent(subjectId)}&course_detail_id=${encodeURIComponent(courseDetailId)}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.status === 'success' && data.course_start_date && data.course_end_date) {
                    courseDateRange = {
                        startDate: data.course_start_date,
                        endDate: data.course_end_date
                    };
                } else {
                    courseDateRange = null;
                }
                updateMonthSelector();
            })
            .catch(() => {
                courseDateRange = null;
                updateMonthSelector();
            });
    }

    function loadSyllabi() {
        const subjectId = $('#subject').val();
        const subjectText = $('#subject option:selected').text() || '';

        if (!subjectId) {
            $('#syllabusList').html(`
                <div class="syllabus-empty">
                    <i class="fas fa-book"></i>
                    <div>Please select a subject first to view available syllabi</div>
                </div>
            `);
            $('#subjectInfo').hide();
            $('#syllabusPreviewWrapper').empty();
            $('#distributeBtn').prop('disabled', true); 
            $('#clearBtn').hide();
            return;
        }

        loadCourseDateRange();

        $('#subjectDisplay').text(`📚 ${subjectText}`); 
        $('#subjectInfo').show();

        fetch(`/instituteAdmin/syllabus/files-by-subject/${encodeURIComponent(subjectId)}`)  
            .then(res => res.json())
            .then(data => {
                const files = (data && data.data) ? data.data : []; 

                if (!files.length) {
                    const subjectSyllabi = syllabiData.filter(s => s.subject === subjectText); 
                    if (subjectSyllabi.length > 0) {
                        displaySyllabiList(subjectSyllabi);
                    } else {
                        $('#syllabusPreviewWrapper').html(` 
                            <div class="syllabus-empty">
                                <i class="fas fa-book"></i>
                                <div>No syllabi found for ${subjectText}</div> 
                            </div>
                        `);
                        $('#syllabusList').html('');
                        $('#distributeBtn').prop('disabled', true);
                        $('#clearBtn').hide();
                    }
                    return;
                }

                function normalizeTerm(term) {
                    const t = String(term || '').trim();
                    if (!t) return { key: 'whole', label: 'Whole Semester', year: null };

                    const now = new Date();
                    const defaultYear = now.getFullYear();

                    const ym = t.match(/^(\d{4})-(\d{2})$/);
                    if (ym) {
                        const year = parseInt(ym[1], 10);
                        const month = parseInt(ym[2], 10) - 1;
                        const d = new Date(year, month, 1);
                        return { key: `${year}-${String(month+1).padStart(2,'0')}`, label: d.toLocaleString('en-US', { month: 'short', year: 'numeric' }), year };
                    }

                    try {
                        const parsed = Date.parse('1 ' + t);
                        if (!isNaN(parsed)) {
                            const dd = new Date(parsed);
                            const hasYear = /\d{4}/.test(t);
                            const year = hasYear ? dd.getFullYear() : defaultYear;
                            const month = dd.getMonth();
                            const label = dd.toLocaleString('en-US', { month: 'short', year: 'numeric' });
                            const key = `${year}-${String(month+1).padStart(2,'0')}`;
                            return { key, label, year };
                        }
                    } catch (e) {}

                    const monthMatch = /(jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)/i.exec(t);
                    if (monthMatch) {
                        const monthNames = { jan:0,feb:1,mar:2,apr:3,may:4,jun:5,jul:6,aug:7,sep:8,oct:9,nov:10,dec:11 };
                        const m = monthMatch[1].toLowerCase();
                        const monthIdx = monthNames[m];
                        const labelDate = new Date(defaultYear, monthIdx, 1);
                        return { key: `${defaultYear}-${String(monthIdx+1).padStart(2,'0')}`, label: labelDate.toLocaleString('en-US', { month: 'short', year: 'numeric' }), year: defaultYear };
                    }

                    return { key: `${defaultYear}-00`, label: t + ' ' + defaultYear, year: defaultYear };
                }

                const groups = {};
                files.forEach(f => {
                    const termRaw = (f.term_value || '').toString().trim();
                    const norm = normalizeTerm(termRaw);
                    const key = norm.key || 'whole';
                    if (!groups[key]) groups[key] = { label: norm.label, items: [] };
                    groups[key].items.push(f);
                });

                const groupEntries = Object.keys(groups).map(k => ({ key: k, label: groups[k].label, items: groups[k].items }));
                groupEntries.sort((a,b) => {
                    const parseKey = (k) => {
                        const m = String(k || '');
                        const ym = m.match(/^(\d{4})-(\d{2})$/);
                        if (ym) return new Date(parseInt(ym[1],10), parseInt(ym[2],10)-1, 1).getTime();
                        return null;
                    };
                    const ak = parseKey(a.key);
                    const bk = parseKey(b.key);
                    if (ak !== null && bk !== null) return ak - bk;
                    if (ak !== null) return -1;
                    if (bk !== null) return 1;
                    return (''+a.key).localeCompare(b.key);
                });

                if (groupEntries.length === 0) {
                    $('#syllabusPreviewWrapper').html(`<div class="syllabus-empty"><i class="fas fa-book"></i><div>No syllabi found for ${subjectText}</div></div>`);
                    $('#syllabusList').html('');
                    $('#distributeBtn').prop('disabled', true);
                    $('#clearBtn').hide();
                    return;
                }

                let previewHtml = '<div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap; margin-bottom:12px;">';
                previewHtml += '<div style="display:flex; gap:8px; flex-wrap:wrap;" id="syllabusMonthSelector">';
                groupEntries.forEach((g, idx) => {
                    const btnId = `sv-btn-${idx}`;
                    const displayLabel = (''+ (g.label || '')).replace(/\s*\d{4}\s*$/, '');
                    previewHtml += `<button type="button" id="${btnId}" class="btn btn-small btn-secondary" onclick="selectSyllabusMonth(${idx})">${displayLabel}</button>`;
                });
                previewHtml += '</div></div>';
                previewHtml += `<div id="pdfViewer" style="border:1px solid #e6ecf3; border-radius:8px; overflow:hidden;"></div>`;

                $('#syllabusPreviewWrapper').html(previewHtml);
                $('#syllabusList').html('');

                window._syllabusGroupEntries = groupEntries;
                const trySelectIndex = groupEntries.findIndex(g => {
                    const now = new Date();
                    const ym = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}`;
                    return g.key === ym || (g.label && g.label.toLowerCase().includes(now.toLocaleString('en-US', { month: 'short' }).toLowerCase()));
                });
                const defaultIndex = trySelectIndex >= 0 ? trySelectIndex : 0;
                selectSyllabusMonth(defaultIndex);
                $('#distributeBtn').prop('disabled', true);
                $('#clearBtn').hide();

                fetch(`/instituteAdmin/subject/${encodeURIComponent(subjectId)}/details`)
                    .then(r => r.json())
                    .then(info => {
                        const teacherSel = $('#formTeacher');
                        teacherSel.prop('disabled', true).html('<option value="">Select Teacher</option>');
                        const employees = info.employees || [];
                        employees.forEach(emp => {
                            teacherSel.append(`<option value="${emp.employee_id}">${emp.name}${emp.email ? ' ('+emp.email+')' : ''}</option>`);
                        });
                        window._subjectShiftData = info.shifts || {};
                        $('#teacherShiftInfo').empty();
                        $('#formTeacher').off('change').on('change', function(){
                            const val = $(this).val();
                            showTeacherShifts(val);
                            updateMonthSelector();
                        });
                        if (employees.length > 0) {
                            teacherSel.prop('disabled', false);
                            const firstId = employees[0].employee_id;
                            $('#formTeacher').val(firstId).trigger('change');
                        } else {
                            teacherSel.html('<option value="">No teachers found</option>');
                        }
                    }).catch(err => {
                        console.error('Error loading subject details:', err);
                    });
            })
            .catch(err => {
                console.error('Error loading syllabus files:', err);
                const subjectText = $('#subject option:selected').text() || '';
                const subjectSyllabi = syllabiData.filter(s => s.subject === subjectText);
                if (subjectSyllabi.length > 0) {
                    displaySyllabiList(subjectSyllabi);
                }
            });
    }

    function displaySyllabiList(syllabi) {
        let html = '';
        syllabi.forEach((s, index) => {
            const isSelected = selectedSyllabusId === s.id;
            html += `
                <div class="syllabus-item ${isSelected ? 'selected' : ''}" id="syllabus-${s.id}" onclick="selectSyllabus(${s.id})">
                    <div class="syllabus-check">
                        <i class="fas fa-check" style="${isSelected ? 'display:block;' : 'display:none;'}"></i>
                    </div>
                    <div class="syllabus-info">
                        <div class="title">${s.title}</div>
                        <div class="meta">${s.month} • ${s.chapters.length} chapters</div>
                    </div>
                    <span class="syllabus-badge">${s.subject}</span>
                    <span class="syllabus-chapters">${s.chapters.length} chapters</span>
                </div>
            `;
        });
        $('#syllabusList').html(html);
        if (selectedSyllabusId) {
            const syllabus = syllabiData.find(s => s.id === selectedSyllabusId);
            if (syllabus) {
                $('#selectedInfo').text(`✅ Selected: ${syllabus.title} (${syllabus.chapters.length} chapters)`);
                $('#statSyllabusTopics').text(syllabus.chapters.length);
                $('#distributeBtn').prop('disabled', false);
                $('#clearBtn').show();
            }
        }
        updateQuickStats();
    }

    function selectSyllabusMonth(index) {
        const groups = window._syllabusGroupEntries || [];
        if (!groups || !groups[index]) return;

        $('#syllabusMonthSelector button').removeClass('active');
        $(`#sv-btn-${index}`).addClass('active');

        const group = groups[index];
        const file = (group.items || []).find(f => f.file_path && f.file_path.trim() !== '') || group.items[0];
        if (file && file.file_path) {
            const url = `/image/${file.file_path}`;
            console.log(url);
            const viewer = $('#pdfViewer');
            if (viewer.length) {
                viewer.html(`<iframe src="${url}#toolbar=0" width="100%" height="600" style="border:0;"></iframe>`);
                viewer[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        } else {
            $('#pdfViewer').html('<div class="syllabus-empty"><i class="fas fa-file"></i><div>No file preview available for this month</div></div>');
        }
    }

    function selectSyllabus(id) {
        selectedSyllabusId = id;
        $('.syllabus-item').removeClass('selected');
        $(`#syllabus-${id}`).addClass('selected');
        $(`#syllabus-${id} .syllabus-check i`).show();
        $('#distributeBtn').prop('disabled', false);
        $('#clearBtn').show();
        const syllabus = syllabiData.find(s => s.id === id);
        $('#selectedInfo').text(`✅ Selected: ${syllabus.title} (${syllabus.chapters ? syllabus.chapters.length : 0} chapters)`);
        
        if (syllabus) {
            $('#statSyllabusTopics').text(syllabus.chapters ? syllabus.chapters.length : 0);
        }
        updateQuickStats();
    }

    function clearSyllabusSelection() {
        selectedSyllabusId = null;
        $('.syllabus-item').removeClass('selected');
        $('.syllabus-check i').hide();
        $('#distributeBtn').prop('disabled', true);
        $('#clearBtn').hide();
        $('#selectedInfo').text('');
        $('#distributionResult').hide();
        $('#statSyllabusTopics').text(0);
        updateQuickStats();
    }

    function distributeSyllabus() {
        if (!selectedSyllabusId) {
            alert('Please select a syllabus first.');
            return;
        }

        const syllabus = syllabiData.find(s => s.id === selectedSyllabusId); 
        if (!syllabus) {
            alert('Syllabus not found.');
            return;
        }

        if (workingDays.length === 0) {
            alert('No working days found for the selected month. Please select a different month or teacher.');
            return;
        }

        const chapters = syllabus.chapters || [];
        const chapterCount = chapters.length;
        
        if (chapterCount === 0) {
            alert('This syllabus has no chapters to distribute.');  
            return;
        }

        const planLevel = currentPlanLevel;
        let topicsCount = 0;
        let distributionData = [];

        if (planLevel === 'day') {
            topicsCount = workingDays.length;
            const chaptersPerDay = Math.ceil(chapterCount / topicsCount);
            
            for (let i = 0; i < topicsCount; i++) {
                const start = i * chaptersPerDay;
                const end = Math.min(start + chaptersPerDay, chapterCount);
                const dayChapters = chapters.slice(start, end);
                const workingDay = workingDays[i];
                
                distributionData.push({
                    day: i + 1,
                    date: workingDay.date,
                    weekday: workingDay.weekday,
                    time: workingDay.time,
                    label: `Day ${i + 1} - ${formatDate(workingDay.date)}`,
                    topics: dayChapters
                });
            }
        } else {
            const weeks = getWeeksFromWorkingDays(workingDays);
            topicsCount = weeks.length;
            const chaptersPerWeek = Math.ceil(chapterCount / topicsCount);
            
            weeks.forEach((week, index) => {
                const start = index * chaptersPerWeek;
                const end = Math.min(start + chaptersPerWeek, chapterCount); 
                const weekChapters = chapters.slice(start, end);
                const firstDay = week[0];
                const lastDay = week[week.length - 1];
                
                distributionData.push({
                    day: index + 1,
                    week: index + 1,
                    date: `${formatDate(firstDay.date)} - ${formatDate(lastDay.date)}`,
                    weekday: `${firstDay.weekday} - ${lastDay.weekday}`,
                    time: firstDay.time,
                    label: `Week ${index + 1} (${week.length} days)`,
                    topics: weekChapters,
                    days: week
                });
            });
        }

        distributedTopics = distributionData;

        let html = '';
        let totalTopics = 0;
        distributedTopics.forEach(item => {
            totalTopics += item.topics.length;
            const dateInfo = item.days ? 
                `${item.date} (${item.days.length} days)` : 
                `${item.date} (${item.weekday})`;
            html += `
                <div class="dist-item">
                    <span class="dist-day">${item.label}</span>
                    <span class="dist-topic">${item.topics.join('; ')}</span>
                    <span class="dist-count">${item.topics.length} chapters</span>
                </div>
            `;
        });

        $('#distributedTopics').html(html);
        $('#distTotalTopics').text(`📊 ${totalTopics} chapters across ${distributedTopics.length} ${planLevel === 'day' ? 'days' : 'weeks'}`);
        $('#distributionResult').show();
        
        document.getElementById('distributionResult').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function formatDate(dateStr) {
        const d = new Date(dateStr + 'T00:00:00');
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    }

    // ===== TAB-BASED TOPIC MANAGEMENT =====
    function updateTopicTabs() {
        const header = $('#topicTabsHeader');
        const content = $('#topicTabsContent');
        const topics = header.find('.topic-tab-btn');
        
        if (topics.length === 0) {
            content.html(`
                <div class="topic-empty-state" id="emptyTopicsMessage">
                    <i class="fas fa-book-open"></i>
                    <div>No topics added yet</div>
                    <div style="font-size:13px; margin-top:4px; color:#4b6a8b;">Add topics manually or use the syllabus distribution above</div>
                </div>
            `);
            $('#topic-count').text('(0 topics)');
            $('#topicCountDisplay').text('0 topics');
            return;
        }

        // Build content panes
        let contentHtml = '';
        topics.each(function(index) {
            const tabId = $(this).data('tab-id');
            const isActive = index === 0 ? 'active' : '';
            const topicData = $(this).data('topic-data');
            
            // Get date info for this topic
            let dateInfo = '';
            let workingDay = null;
            let weekInfo = null;
            
            if (workingDays && workingDays.length > 0) {
                if (currentPlanLevel === 'day') {
                    if (index < workingDays.length) {
                        workingDay = workingDays[index];
                        dateInfo = `📅 ${workingDay.date} | ${workingDay.weekday} | 🕐 ${workingDay.time}`;
                    }
                } else {
                    const weeks = getWeeksFromWorkingDays(workingDays);
                    if (index < weeks.length) {
                        weekInfo = weeks[index];
                        const firstDay = weekInfo[0];
                        const lastDay = weekInfo[weekInfo.length - 1];
                        dateInfo = `📅 ${formatDate(firstDay.date)} - ${formatDate(lastDay.date)} | ${weekInfo.length} days`;
                    }
                }
            }
            
            const titleValue = topicData ? topicData.title || '' : '';
            
            contentHtml += `
                <div class="topic-tab-content ${isActive}" data-tab="${tabId}" id="topic-content-${tabId}">
                    ${dateInfo ? `<div class="topic-date-display"><i class="fas fa-calendar-alt"></i> ${dateInfo}</div>` : ''}
                    <div class="topic-form-fields">
                        <div class="full-width">
                            <label class="field-label"><i class="fas fa-heading"></i> Topic Title <span class="form-required">*</span></label>
                            <input type="text" class="topic-title-input form-control" placeholder="Enter topic title" value="${titleValue}" data-tab-id="${tabId}" onchange="updateTopicData(${tabId}, 'title', this.value)" />
                        </div>
                        <div class="full-width">
                            <label class="field-label"><i class="fas fa-list"></i> Topics (comma separated)</label>
                            <input type="text" class="topic-topics-input form-control" placeholder="e.g. Algebra, Geometry, Trigonometry" data-tab-id="${tabId}" onchange="updateTopicData(${tabId}, 'topics', this.value)" />
                        </div>
                        <div>
                            <label class="field-label"><i class="fab fa-youtube" style="color:#ff0000;"></i> YouTube Video URL(s)</label>
                            <div class="topic-video-rows">
                                <div class="repeatable-input-row">
                                    <input type="url" class="topic-video-input form-control" placeholder="https://youtube.com/watch?v=..." data-tab-id="${tabId}" />
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary btn-small add-repeatable-row" onclick="addVideoRow(${tabId})"><i class="fas fa-plus"></i> Add another link</button>
                        </div>
                        <div>
                            <label class="field-label"><i class="fas fa-upload"></i> Upload PDF/File(s)</label>
                            <div class="topic-file-rows">
                                <div class="repeatable-input-row">
                                    <input type="file" class="topic-file-input form-control" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx" data-tab-id="${tabId}" />
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary btn-small add-repeatable-row" onclick="addFileRow(${tabId})"><i class="fas fa-plus"></i> Add another file</button>
                            <div class="file-list" style="margin-top:6px;"></div>
                        </div>
                        <div>
                            <label class="field-label"><i class="fas fa-cubes"></i> Resources (comma separated)</label>
                            <input type="text" class="topic-resources-input form-control" placeholder="e.g. Textbook, Worksheet" data-tab-id="${tabId}" onchange="updateTopicData(${tabId}, 'resources', this.value)" />
                        </div>
                        <div class="full-width">
                            <button type="button" class="btn btn-danger btn-small" onclick="removeTopicTab(${tabId})" style="margin-top:4px;">
                                <i class="fas fa-trash-alt"></i> Remove Topic
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        content.html(contentHtml);

        // Update topic count
        const count = topics.length;
        $('#topic-count').text(`(${count} topics)`);
        $('#topicCountDisplay').text(`${count} topics`);
        updateQuickStats();
    }

    function addTopicTab() {
        const header = $('#topicTabsHeader');
        const content = $('#topicTabsContent');
        const tabId = ++topicCounter;
        
        // Remove empty state if present
        const emptyMsg = content.find('.topic-empty-state');
        if (emptyMsg.length) emptyMsg.remove();
        
        // Determine label for this topic
        const topicNumber = header.find('.topic-tab-btn').length + 1;
        let label = `Topic ${topicNumber}`;
        let dateLabel = '';
        
        // Use consistent labeling based on plan level
        if (currentPlanLevel === 'day') {
            label = `Day ${topicNumber}`;
            if (workingDays && workingDays.length > 0) {
                const index = topicNumber - 1;
                if (index < workingDays.length) {
                    const day = workingDays[index];
                    dateLabel = formatDate(day.date);
                }
            }
        } else if (currentPlanLevel === 'week') {
            label = `Week ${topicNumber}`;
            if (workingDays && workingDays.length > 0) {
                const weeks = getWeeksFromWorkingDays(workingDays);
                const index = topicNumber - 1;
                if (index < weeks.length) {
                    const week = weeks[index];
                    const firstDay = week[0];
                    dateLabel = `${formatDate(firstDay.date)}...`;
                }
            }
        }
        
        // Create tab button
        const tabBtn = $(`
            <button type="button" class="topic-tab-btn" data-tab-id="${tabId}" onclick="switchTopicTab(${tabId})">
                <span class="tab-icon">📖</span>
                ${label}
                ${dateLabel ? `<span class="tab-date">${dateLabel}</span>` : ''}
                <span class="tab-badge">${topicNumber}</span>
                <span class="topic-tab-actions">
                    <button type="button" class="btn btn-danger btn-small" onclick="event.stopPropagation(); removeTopicTab(${tabId})" style="padding:2px 8px; font-size:10px;">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
            </button>
        `);
        
        // Store topic data on the button
        tabBtn.data('topic-data', { title: '', topics: '', video: '', resources: '' });
        
        // Add tab
        header.append(tabBtn);
        
        // Make this the active tab
        header.find('.topic-tab-btn').removeClass('active');
        tabBtn.addClass('active');
        
        // Rebuild content
        updateTopicTabs();
        
        // Focus on the title input
        setTimeout(() => {
            $(`#topic-content-${tabId} .topic-title-input`).focus();
        }, 100);
        
        $(`#topic-content-${tabId}`).on('change', '.topic-file-input', function() {
            renderTopicFileList(tabId);
        });
        
        updateTopicCount();
        updateQuickStats();
    }

    function switchTopicTab(tabId) {
        // Update active tab
        $('#topicTabsHeader .topic-tab-btn').removeClass('active');
        $(`#topicTabsHeader .topic-tab-btn[data-tab-id="${tabId}"]`).addClass('active');
        
        // Update active content
        $('#topicTabsContent .topic-tab-content').removeClass('active');
        $(`#topicTabsContent .topic-tab-content[data-tab="${tabId}"]`).addClass('active');
        
        activeTopicTab = tabId;
    }

    function removeTopicTab(tabId) {
        if (confirm('Remove this topic from the plan?')) {
            // Remove tab
            $(`#topicTabsHeader .topic-tab-btn[data-tab-id="${tabId}"]`).remove();
            
            // Remove content
            $(`#topicTabsContent .topic-tab-content[data-tab="${tabId}"]`).remove();
            
            // If no topics left, show empty state
            if ($('#topicTabsHeader .topic-tab-btn').length === 0) {
                $('#topicTabsContent').html(`
                    <div class="topic-empty-state" id="emptyTopicsMessage">
                        <i class="fas fa-book-open"></i>
                        <div>No topics added yet</div>
                        <div style="font-size:13px; margin-top:4px; color:#4b6a8b;">Add topics manually or use the syllabus distribution above</div>
                    </div>
                `);
            } else {
                // Activate first tab
                const firstTab = $('#topicTabsHeader .topic-tab-btn').first();
                if (firstTab.length) {
                    switchTopicTab(firstTab.data('tab-id'));
                }
            }
            
            updateTopicCount();
            updateQuickStats();
        }
    }

    function updateTopicData(tabId, field, value) {
        const tabBtn = $(`#topicTabsHeader .topic-tab-btn[data-tab-id="${tabId}"]`);
        const topicData = tabBtn.data('topic-data') || {};
        topicData[field] = value;
        tabBtn.data('topic-data', topicData);
    }

    function addVideoRow(tabId) {
        $(`#topic-content-${tabId} .topic-video-rows`).append(`
            <div class="repeatable-input-row">
                <input type="url" class="topic-video-input form-control" placeholder="https://youtube.com/watch?v=..." data-tab-id="${tabId}" />
                <button type="button" class="btn btn-danger btn-small" onclick="$(this).closest('.repeatable-input-row').remove()" title="Remove link"><i class="fas fa-times"></i></button>
            </div>
        `);
    }

    function addFileRow(tabId) {
        $(`#topic-content-${tabId} .topic-file-rows`).append(`
            <div class="repeatable-input-row">
                <input type="file" class="topic-file-input form-control" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx" data-tab-id="${tabId}" />
                <button type="button" class="btn btn-danger btn-small" onclick="$(this).closest('.repeatable-input-row').remove(); renderTopicFileList(${tabId})" title="Remove file"><i class="fas fa-times"></i></button>
            </div>
        `);
    }

    function renderTopicFileList(tabId) {
        const content = $(`#topic-content-${tabId}`);
        const fileList = content.find('.file-list');
        fileList.empty();
        content.find('.topic-file-input').each(function() {
            Array.from(this.files || []).forEach(file => {
                const fileSize = (file.size / 1024).toFixed(1);
                const icon = file.type.includes('pdf') ? 'fa-file-pdf' :
                    file.type.includes('word') ? 'fa-file-word' :
                    file.type.includes('powerpoint') ? 'fa-file-powerpoint' :
                    file.type.includes('sheet') ? 'fa-file-excel' : 'fa-file';
                const color = file.type.includes('pdf') ? '#dc2626' :
                    file.type.includes('word') ? '#2563eb' :
                    file.type.includes('powerpoint') ? '#f59e0b' :
                    file.type.includes('sheet') ? '#10b981' : '#6b7280';
                fileList.append(`
                    <div class="file-item">
                        <i class="fas ${icon}" style="color:${color};"></i>
                        <span class="file-name">${file.name}</span>
                        <span class="file-size">(${fileSize} KB)</span>
                    </div>
                `);
            });
        });
    }

    function getTopicsFromTabs() {
        const topics = [];
        $('#topicTabsHeader .topic-tab-btn').each(function() {
            const tabId = $(this).data('tab-id');
            const content = $(`#topic-content-${tabId}`);
            const title = content.find('.topic-title-input').val().trim();
            const topicsList = content.find('.topic-topics-input').val().trim();
            const videos = [];
            content.find('.topic-video-input').each(function() {
                const video = $(this).val().trim();
                if (video) videos.push(video);
            });
            const resources = content.find('.topic-resources-input').val().trim();
            const files = [];
            
            // Collect actual file objects from file input
            content.find('.topic-file-input').each(function() {
                if (this.files && this.files.length > 0) {
                    for (let i = 0; i < this.files.length; i++) files.push(this.files[i]);
                }
            });
            
            // Get date info from the content
            let dateInfo = '';
            let topicDate = '';
            let weekNumber = null;
            const dateDisplay = content.find('.topic-date-display');
            if (dateDisplay.length) {
                dateInfo = dateDisplay.text().trim();
            }

            const topicIndex = $('#topicTabsHeader .topic-tab-btn').index(this);
            if (currentPlanLevel === 'week') {
                const weeks = getWeeksFromWorkingDays(workingDays || []);
                const week = weeks[topicIndex];
                if (week && week.length > 0) {
                    topicDate = week[0].date;
                    weekNumber = topicIndex + 1;
                }
            } else if (workingDays && workingDays[topicIndex]) {
                topicDate = workingDays[topicIndex].date;
            }
            
            // Save topic even if title is empty
            topics.push({
                number: topics.length + 1,
                title: title,
                topics: topicsList ? topicsList.split(',').map(item => item.trim()).filter(Boolean) : [],
                video: videos.join(', '),
                files: files,
                resources: resources ? resources.split(',').map(r => r.trim()).filter(r => r) : [],
                dateInfo: dateInfo,
                topic_date: topicDate,
                week_number: weekNumber,
                covered: false,
                tabId: tabId
            });
        });
        return topics;
    }

    function updateTopicCount() {
        const count = $('#topicTabsHeader .topic-tab-btn').length;
        $('#topic-count').text(`(${count} topics)`);
        $('#topicCountDisplay').text(`${count} topics`);
        updateQuickStats();
    }

    function addMultipleTopics() {
        const count = $('#topicTabsHeader .topic-tab-btn').length;
        const maxTopics = Math.max(workingDays.length || 10, 10);
        const toAdd = Math.min(3, maxTopics - count);
        
        if (toAdd <= 0) {
            alert('You already have the maximum number of topics for this plan.');
            return;
        }
        
        for (let i = 0; i < toAdd; i++) {
            addTopicTab();
        }
    }

    function autoGenerateTopics() {
        const topicsToCreate = parseInt($('#topicsToCreate').text()) || 0;
        const currentTopics = $('#topicTabsHeader .topic-tab-btn').length;
        const subject = $('#subject').val() || 'Lesson';
        
        if (topicsToCreate === 0) {
            alert('Please select a teacher and month with working days first.');
            return;
        }
        
        if (currentTopics >= topicsToCreate) {
            alert(`You already have ${currentTopics} topics. The recommended number is ${topicsToCreate} topics.`);
            return;
        }
        
        const toAdd = Math.min(topicsToCreate - currentTopics, 50);
        
        const topicSuggestions = {
            'Physics': [
                'Units and Measurements', 'Motion in a Straight Line', 'Motion in a Plane', 
                'Laws of Motion', 'Work, Energy and Power', 'System of Particles', 
                'Rotational Motion', 'Gravitation', 'Mechanical Properties of Solids',
                'Mechanical Properties of Fluids', 'Thermal Properties of Matter', 'Thermodynamics',
                'Kinetic Theory', 'Oscillations', 'Waves', 'Electric Charges and Fields',
                'Electrostatic Potential and Capacitance', 'Current Electricity', 'Moving Charges and Magnetism',
                'Magnetism and Matter', 'Electromagnetic Induction', 'Alternating Current',
                'Electromagnetic Waves', 'Ray Optics', 'Wave Optics', 'Dual Nature of Radiation',
                'Atoms', 'Nuclei', 'Semiconductor Electronics'
            ],
            'Chemistry': [
                'Some Basic Concepts of Chemistry', 'Structure of Atom', 'Classification of Elements',
                'Chemical Bonding', 'States of Matter', 'Thermodynamics', 'Equilibrium',
                'Redox Reactions', 'Hydrogen', 's-Block Elements', 'p-Block Elements',
                'Organic Chemistry', 'Hydrocarbons', 'Environmental Chemistry', 'Solutions',
                'Electrochemistry', 'Chemical Kinetics', 'Surface Chemistry', 'Solid State',
                'Coordination Compounds', 'Aldehydes and Ketones', 'Carboxylic Acids', 'Amines'
            ],
            'Biology': [
                'The Living World', 'Biological Classification', 'Plant Kingdom', 'Animal Kingdom',
                'Morphology of Flowering Plants', 'Anatomy of Flowering Plants', 'Structural Organisation in Animals',
                'Cell Structure', 'Cell Division', 'Transport in Plants', 'Mineral Nutrition',
                'Photosynthesis', 'Respiration in Plants', 'Plant Growth', 'Digestion and Absorption',
                'Breathing and Exchange', 'Body Fluids', 'Excretory Products', 'Locomotion',
                'Neural Control', 'Chemical Coordination', 'Reproduction', 'Genetics', 'Evolution',
                'Human Health', 'Biotechnology', 'Ecology', 'Environmental Issues'
            ],
            'Mathematics': [
                'Sets', 'Relations and Functions', 'Trigonometric Functions', 'Principle of Mathematical Induction',
                'Complex Numbers', 'Quadratic Equations', 'Linear Inequalities', 'Permutations and Combinations',
                'Binomial Theorem', 'Sequences and Series', 'Straight Lines', 'Conic Sections',
                'Three Dimensional Geometry', 'Limits and Derivatives', 'Statistics', 'Probability',
                'Relations and Functions (Class 12)', 'Inverse Trigonometric Functions', 'Matrices', 'Determinants',
                'Continuity and Differentiability', 'Application of Derivatives', 'Integrals', 'Application of Integrals',
                'Differential Equations', 'Vector Algebra', 'Linear Programming', 'Probability'
            ],
            'English': [
                'Reading Comprehension', 'Writing Skills', 'Grammar', 'Literature', 'Poetry Analysis',
                'Prose Analysis', 'Drama Analysis', 'Essay Writing', 'Letter Writing', 'Article Writing',
                'Report Writing', 'Speech Writing', 'Tenses', 'Modals', 'Voice', 'Narration',
                'Clauses', 'Transformation of Sentences', 'Parts of Speech', 'Sentence Structure'
            ]
        };
        
        let suggestions = topicSuggestions[subject] || [];
        
        if (suggestions.length === 0) {
            suggestions = [];
            for (let i = 1; i <= 100; i++) {
                suggestions.push(`Chapter ${i}: ${subject} Topic ${i}`);
            }
        }
        
        while (suggestions.length < topicsToCreate) {
            suggestions = suggestions.concat(suggestions);           
        }
        
        let addedCount = 0;
        const startIndex = currentTopics;
        
        for (let i = 0; i < toAdd; i++) {
            addTopicTab();
            const tabId = topicCounter;
            
            // Only add title for first topic (topic 1)
            if (startIndex + i < 1) {
                const suggestionIndex = (startIndex + i) % suggestions.length;
                const topicTitle = suggestions[suggestionIndex];
                
                // Update the data on the tab button
                const tabBtn = $(`#topicTabsHeader .topic-tab-btn[data-tab-id="${tabId}"]`);
                const topicData = tabBtn.data('topic-data') || {};
                topicData.title = topicTitle;
                tabBtn.data('topic-data', topicData);
                
                // Update the input value in the DOM
                $(`#topic-content-${tabId} .topic-title-input`).val(topicTitle);
            }
            // For topic 2 onwards, leave title empty
            
            addedCount++;
        }
        
        const totalTopics = $('#topicTabsHeader .topic-tab-btn').length;
        const planTypeLabel = currentPlanLevel === 'day' ? 'days' : 'weeks';
        
        alert(`✅ Generated ${addedCount} topic${addedCount > 1 ? 's' : ''} for ${selectedMonth} ${selectedYear}.\nTotal topics: ${totalTopics}\nPlan type: ${currentPlanLevel} plan`);
        
        updateTopicCount();
        updateQuickStats();
    }

    function clearAllTopics() {
        if ($('#topicTabsHeader .topic-tab-btn').length === 0) return;
        if (confirm('Are you sure you want to clear all topics?')) {
            $('#topicTabsHeader').empty();
            $('#topicTabsContent').html(`
                <div class="topic-empty-state" id="emptyTopicsMessage">
                    <i class="fas fa-book-open"></i>
                    <div>No topics added yet</div>
                    <div style="font-size:13px; margin-top:4px; color:#4b6a8b;">Add topics manually or use the syllabus distribution above</div>
                </div>
            `);
            topicCounter = 0;
            updateTopicCount();
            updateQuickStats();
        }
    }

    function updateAllTopicDates() {
        // Update dates in topic tabs when working days change
        $('#topicTabsHeader .topic-tab-btn').each(function(index) {
            const tabId = $(this).data('tab-id');
            const content = $(`#topic-content-${tabId}`);
            const dateDisplay = content.find('.topic-date-display');
            
            if (workingDays && workingDays.length > 0) {
                let dateInfo = '';
                if (currentPlanLevel === 'day') {
                    if (index < workingDays.length) {
                        const day = workingDays[index];
                        dateInfo = `📅 ${day.date} | ${day.weekday} | 🕐 ${day.time}`;
                    }
                } else {
                    const weeks = getWeeksFromWorkingDays(workingDays);
                    if (index < weeks.length) {
                        const week = weeks[index];
                        const firstDay = week[0];
                        const lastDay = week[week.length - 1];
                        dateInfo = `📅 ${formatDate(firstDay.date)} - ${formatDate(lastDay.date)} | ${week.length} days`;
                    }
                }
                
                if (dateInfo) {
                    if (dateDisplay.length) {
                        dateDisplay.html(`<i class="fas fa-calendar-alt"></i> ${dateInfo}`);
                    } else {
                        content.prepend(`<div class="topic-date-display"><i class="fas fa-calendar-alt"></i> ${dateInfo}</div>`);
                    }
                }
            }
        });
    }

    function addDistributedTopics() {
        if (distributedTopics.length === 0) {
            alert('No topics to add. Please distribute a syllabus first.');
            return;
        }

        // Clear existing topics
        $('#topicTabsHeader').empty();
        $('#topicTabsContent').html('');
        topicCounter = 0;

        let totalAdded = 0;
        distributedTopics.forEach((item, index) => {
            addTopicTab();
            const tabId = topicCounter;
            const combinedTopics = item.topics.join('; ');
            
            // Set the title
            $(`#topic-content-${tabId} .topic-title-input`).val(combinedTopics);
            $(`#topicTabsHeader .topic-tab-btn[data-tab-id="${tabId}"]`).data('topic-data', { title: combinedTopics });
            
            // Update resources
            let resourceInfo = '';
            if (item.time) {
                resourceInfo = `🕐 ${item.time}`;
            }
            if (item.label) {
                resourceInfo = (resourceInfo ? resourceInfo + ' | ' : '') + item.label;
            }
            $(`#topic-content-${tabId} .topic-resources-input`).val(resourceInfo);
            
            totalAdded++;
        });

        // Activate first tab
        const firstTab = $('#topicTabsHeader .topic-tab-btn').first();
        if (firstTab.length) {
            switchTopicTab(firstTab.data('tab-id'));
        }

        updateTopicCount();
        updateQuickStats();
        
        alert(`✅ Added ${totalAdded} topics to your lesson plan for ${selectedMonth} ${selectedYear}!`);
        hideDistribution();
    }

    function hideDistribution() {
        $('#distributionResult').hide();
    }

    // ===== PLAN LEVEL SELECTION =====
    function selectPlanLevel(level) {
        currentPlanLevel = level;
        
        $('.plan-level-btn').removeClass('active');
        $(`.plan-level-btn[data-level="${level}"]`).addClass('active');
        
        const descriptions = {
            day: '📌 1 topic per working day',
            week: '📌 1 topic per week'
        };
        $('#level-description').text(descriptions[level] || '');
        
        const badgeLabels = { day: 'Day', week: 'Week' };
        $('#levelBadge').text(badgeLabels[level] || 'Day');
        $('#levelBadge').attr('class', 'level-badge ' + level);
        
        if (selectedMonth && selectedYear) {
            updateWorkingDays(selectedMonth, selectedYear);
        }
        
        updateQuickStats();
    }

    function updateQuickStats() {
        const workingDaysCount = parseInt($('#workingDaysCount').text()) || 0;
        const topics = $('#topicTabsHeader .topic-tab-btn').length;
        
        let weeks = 0;
        if (currentPlanLevel === 'day') {
            weeks = Math.ceil(workingDaysCount / 5);
        } else {
            weeks = parseInt($('#topicsToCreate').text()) || 0;
        }
        
        $('#statDays').text(workingDaysCount);
        $('#statTopics').text(topics);
        $('#statWeeks').text(weeks);
        
        if (selectedSyllabusId) {
            const syllabus = syllabiData.find(s => s.id === selectedSyllabusId);
            if (syllabus) {
                $('#statSyllabusTopics').text(syllabus.chapters ? syllabus.chapters.length : 0);
            }
        } else {
            $('#statSyllabusTopics').text(0);
        }
    }

    // ===== SHOW TEACHER SHIFTS - VERTICAL LAYOUT =====
    function showTeacherShifts(empId) {
        const container = $('#teacherShiftInfo');
        container.empty();
        if (!empId) return;
        const shifts = (window._subjectShiftData && window._subjectShiftData[empId]) || null;
        if (!shifts) {
            container.html('<div class="form-hint">No shift information available for selected teacher.</div>');
            return;
        }

        function fmtDate(dstr) {
            if (!dstr) return '';
            const d = new Date(dstr + 'T00:00:00');
            const opts = { day: 'numeric', month: 'short' };
            return d.toLocaleDateString(undefined, opts);
        }

        const months = Object.keys(shifts);
        if (months.length === 0) {
            container.html('<div class="form-hint">No shift information available for selected teacher.</div>');
            return;
        }

        let html = '<div class="shift-tabs-container">';
        html += '<div class="shift-tabs-header">';
        
        months.forEach((month, idx) => {
            const isActive = idx === 0 ? 'active' : '';
            let displayName = month;
            if (month.length > 20) {
                displayName = month.substring(0, 18) + '…';
            }
            html += `<button type="button" class="shift-tab-button ${isActive}" onclick="switchShiftTab(this, '${month.replace(/'/g, "\\'")}')" data-month="${month.replace(/'/g, "\\'")}" title="${month}">📅 ${displayName}</button>`;
        });
        
        html += '</div>';
        html += '<div class="shift-tabs-content">';

        months.forEach((month, idx) => {
            const isActive = idx === 0 ? 'active' : '';
            const safeMonth = month.replace(/'/g, "\\'");
            html += `<div class="shift-tab-pane ${isActive}" data-tab="${safeMonth}">`;
            
            const byWeekday = shifts[month] || {};
            const weekdayKeys = Object.keys(byWeekday);
            
            if (weekdayKeys.length === 0) {
                html += '<div class="shift-no-data"><i class="fas fa-calendar-times"></i><div>No shifts scheduled for ' + month + '</div></div>';
            } else {
                weekdayKeys.forEach(weekday => {
                    const entries = byWeekday[weekday] || [];
                    if (entries.length === 0) return;
                    
                    html += `<div class="shift-weekday-group">`;
                    html += `<div class="shift-weekday-header">${weekday}</div>`;
                    
                    entries.forEach((shift) => {
                        const timeLine = `${shift.start || ''} - ${shift.end || ''}`;
                        html += `<div class="shift-time-entry">`;
                        html += `<div class="shift-time"><i class="fas fa-clock" style="margin-right:6px;color:#2563eb;"></i>${timeLine}</div>`;
                        
                        if (shift.dates && Array.isArray(shift.dates) && shift.dates.length > 0) {
                            const displayDates = shift.dates.slice(0, 3).map(d => fmtDate(d)).join(', ');
                            const remaining = shift.dates.length - 3;
                            html += `<div class="shift-dates">`;
                            html += `<span class="shift-count-badge">${shift.count || shift.dates.length} times</span>`;
                            html += `<span style="color:#8a9bb5;"> — ${displayDates}`;
                            if (remaining > 0) {
                                html += ` +${remaining} more`;
                            }
                            html += `</span>`;
                            html += `</div>`;
                        }
                        html += `</div>`;
                    });
                    
                    html += `</div>`;
                });
            }
            
            html += `</div>`;
        });

        html += '</div></div>';
        container.html(html);
        updateMonthSelector();
    }

    function switchShiftTab(button, month) {
        const container = button.closest('.shift-tabs-container');
        container.querySelectorAll('.shift-tab-button').forEach(btn => {
            btn.classList.remove('active');
        });
        button.classList.add('active');
        
        container.querySelectorAll('.shift-tab-pane').forEach(pane => {
            pane.classList.remove('active');
        });
        const targetPane = container.querySelector(`[data-tab="${month}"]`);
        if (targetPane) {
            targetPane.classList.add('active');
        }
    }

    // ===== SAVE FUNCTIONS =====
    function saveLessonPlan(e) {
        e.preventDefault();

        const topics = getTopicsFromTabs();
        let startDate = $('#formStartDate').val();
        let endDate = $('#formEndDate').val();
        
        if (topics.length === 0) {
            alert('⚠️ Please add at least one topic before saving.');
            return;
        }

        if (!selectedMonth || !selectedYear) {
            alert('⚠️ Please select a valid month within the course date range.');
            return;
        }

        if (!startDate || !endDate) {
            const monthIndex = new Date(`${selectedMonth} 1, ${selectedYear}`).getMonth();
            const monthStart = new Date(selectedYear, monthIndex, 1);
            const monthEnd = new Date(selectedYear, monthIndex + 1, 0);
            startDate = monthStart.toISOString().split('T')[0];
            endDate = monthEnd.toISOString().split('T')[0];
            $('#formStartDate').val(startDate);
            $('#formEndDate').val(endDate);
        }

        if (!startDate || !endDate) {
            alert('⚠️ A valid month range could not be generated for this course.');
            return;
        }
        
        const planTitle = $('#formTitle').val().trim() || 
            ($('#subject').val() + ' - ' + $('#courseType').val() + ' - ' + selectedMonth + ' ' + selectedYear);
        
        let syllabusInfo = null;
        if (selectedSyllabusId) {
            const syllabus = syllabiData.find(s => s.id === selectedSyllabusId);
            if (syllabus) {
                syllabusInfo = {
                    id: syllabus.id,
                    title: syllabus.title,
                    month: syllabus.month,
                    chapters: syllabus.chapters
                };
            }
        }

        // Prepare FormData to handle file uploads
        const formData = new FormData();
        formData.append('title', planTitle);
        formData.append('category_id', $('#category').val() || null);
        formData.append('department_id', $('#department').val() || null);
        formData.append('course_type', $('#courseType').val() || null);
        formData.append('course_sub_type', $('#subType').val() || null);
        formData.append('academic_year', $('#academic_year').val() || null);
        formData.append('subject_id', $('#subject').val() || null);
        formData.append('teacher_id', $('#formTeacher').val() || null);
        formData.append('plan_level', currentPlanLevel);
        formData.append('plan_type_label', `${currentPlanLevel === 'day' ? 'Day' : 'Week'} Plan - ${selectedMonth} ${selectedYear}`);
        formData.append('month', selectedMonth);
        formData.append('year', selectedYear);
        formData.append('start_date', startDate);
        formData.append('end_date', endDate);
        formData.append('status', window.pendingSubmit ? 'active' : 'draft');
        
        // Append syllabus_data as array properly for Laravel
        if (syllabusInfo) {
            Object.keys(syllabusInfo).forEach(key => {
                if (Array.isArray(syllabusInfo[key])) {
                    syllabusInfo[key].forEach((item, idx) => {
                        formData.append(`syllabus_data[${key}][${idx}]`, item);
                    });
                } else {
                    formData.append(`syllabus_data[${key}]`, syllabusInfo[key]);
                }
            });
        }
        
        // Append arrays properly for Laravel
        workingDays.forEach((day, idx) => {
            if (typeof day === 'string') {
                formData.append(`working_days[${idx}]`, day);
            } else {
                Object.keys(day).forEach(key => {
                    formData.append(`working_days[${idx}][${key}]`, day[key]);
                });
            }
        });
        
        (distributedTopics || []).forEach((topic, idx) => {
            if (typeof topic === 'string') {
                formData.append(`distributed_topics[${idx}]`, topic);
            } else {
                Object.keys(topic).forEach(key => {
                    formData.append(`distributed_topics[${idx}][${key}]`, topic[key]);
                });
            }
        });
        
        // Append topics with files
        topics.forEach((topic, index) => {
            formData.append(`topics[${index}][title]`, topic.title || `Day ${index + 1}`);
            formData.append(`topics[${index}][topics]`, Array.isArray(topic.topics) ? topic.topics.join(', ') : (topic.topics || ''));
            formData.append(`topics[${index}][description]`, topic.description || '');
            formData.append(`topics[${index}][video_url]`, topic.video || '');
            formData.append(`topics[${index}][video]`, topic.video || '');

            // Append resources array properly
            const resources = topic.resources || [];
            resources.forEach((resource, resIdx) => {
                if (typeof resource === 'string') {
                    formData.append(`topics[${index}][resources][${resIdx}]`, resource);
                } else {
                    Object.keys(resource).forEach(key => {
                        formData.append(`topics[${index}][resources][${resIdx}][${key}]`, resource[key]);
                    });
                }
            });
            
            formData.append(`topics[${index}][date_info]`, topic.dateInfo || '');
            formData.append(`topics[${index}][day_number]`, index + 1);
            // Don't append null values - FormData converts them to string "null" which fails validation
            // Only append if the value exists and is not null
            if (topic.week_number) {
                formData.append(`topics[${index}][week_number]`, topic.week_number);
            }
            if (topic.topic_date) {
                formData.append(`topics[${index}][topic_date]`, topic.topic_date);
            }
            // Convert boolean to 0/1 since FormData converts false to string "false"
            formData.append(`topics[${index}][covered]`, topic.covered ? 1 : 0);
            
            // Append actual file objects
            if (topic.files && topic.files.length > 0) {
                topic.files.forEach((file, fileIndex) => {
                    formData.append(`topics[${index}][files][]`, file);
                });
            }
        });

        // Send to backend
        $.ajax({
            url: "{{ route('lesson-planner.store') }}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                window.pendingSubmit = false;
                $('#successMessage').addClass('show');
                setTimeout(() => {
                    window.location.href = "{{ route('lesson-planner.plans') }}";
                }, 1500);
            },
            error: function(xhr, status, error) {
                window.pendingSubmit = false;
                console.error('Error saving lesson plan:', error);
                console.error('Response:', xhr.responseJSON);
                let errorMsg = '⚠️ Failed to save lesson plan.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = '⚠️ ' + xhr.responseJSON.message;
                }
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    console.error('Validation errors:', xhr.responseJSON.errors);
                    errorMsg += '\n\nErrors:\n' + Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                alert(errorMsg);
            }
        });
    }

    function submitLessonPlan() {
        const topics = getTopicsFromTabs();
        if (topics.length === 0) {
            alert('⚠️ Please add at least one topic before submitting.');
            return;
        }
        if (!$('#courseType').val() || !$('#subject').val() || !$('#formTeacher').val()) {
            alert('⚠️ Please fill in all required fields (Course Type, Subject, and Teacher).');
            return;
        }
        if (workingDays.length === 0) {
            alert('⚠️ No working days found. Please select a teacher and month with working days.');
            return;
        }
        
        if (confirm('Activate this lesson plan?')) {
            window.pendingSubmit = true;
            const form = document.getElementById('lessonForm');
            form.querySelector('button[type="submit"]').click();
        }
    }

    function removeFile(btn) {
        $(btn).closest('.file-item').remove();
    }

    // ===== INIT =====
    $(document).ready(function() {
        const now = new Date();
        const today = now.toISOString().split('T')[0];
        
        $('<input>').attr({
            type: 'hidden',
            id: 'formStartDate',
            name: 'formStartDate'
        }).val(today);
        $('<input>').attr({
            type: 'hidden',
            id: 'formEndDate',
            name: 'formEndDate'
        }).appendTo('#lessonForm');
        
        selectPlanLevel('day');
        loadSyllabi();

        // Dynamic selects
        function loadDepartments(categoryId) {
            const deptSelect = $('#department');
            deptSelect.prop('disabled', true).html('<option value="">Loading departments...</option>');

            $('#courseType').prop('disabled', true).html('<option value="">Select Course Type</option>');
            $('#subType').prop('disabled', true).html('<option value="">Select Course Sub Type</option>');
            $('#academic_year').prop('disabled', true).html('<option value="">Select Course Sub Type first</option>');
            $('#subject').prop('disabled', true).html('<option value="">Select Subject</option>');

            if (!categoryId) {
                deptSelect.prop('disabled', true).html('<option value="">Select Category first</option>');
                return;
            }
            fetch(`/ajax/departments-by-category?category_id=${encodeURIComponent(categoryId)}`)
                .then(res => res.json())
                .then(data => {
                    deptSelect.html('<option value="">Select Department</option>');
                    if (data.success && data.departments) {
                        data.departments.forEach(d => {
                            deptSelect.append(`<option value="${d.department_id}">${d.department}</option>`);
                        });
                        deptSelect.prop('disabled', false);
                    } else {
                        deptSelect.html('<option value="">No departments found</option>');
                    }
                }).catch(err => {
                    console.error('Error loading departments:', err);
                    deptSelect.html('<option value="">Error loading departments</option>');
                });
        }

        $('#category').on('change', function() {
            loadDepartments($(this).val());
        });

        $('#department').on('change', function() {
            const deptId = $(this).val();
            $('#courseType').prop('disabled', true).html('<option value="">Loading...</option>');
            $('#subType').prop('disabled', true).html('<option value="">Select Course Sub Type</option>');
            $('#academic_year').prop('disabled', true).html('<option value="">Select Course Sub Type first</option>');
            $('#subject').prop('disabled', true).html('<option value="">Select Subject</option>');
            if (!deptId) return;
            fetch(`/ajax/course-types-by-department?department_id=${encodeURIComponent(deptId)}`)
                .then(res => res.json())
                .then(data => {
                    const select = $('#courseType');
                    select.html('<option value="">Select Course Type</option>');  
                    if (data.status === 'success' && data.courses) {
                        data.courses.forEach(c => {
                            select.append(`<option value="${c.finacp_merchant_sub_category_type || c.course_type}">${c.finacp_merchant_sub_category_type || c.course_type}</option>`);
                        });
                        select.prop('disabled', false);
                    } else if (data.courseTypes) {
                        data.courseTypes.forEach(c => select.append(`<option value="${c.course_type}">${c.course_type}</option>`));
                        select.prop('disabled', false);
                    } else {
                        select.html('<option value="">No course types found</option>');
                    }
                }).catch(err => {
                    console.error('Error loading course types:', err);
                    $('#courseType').html('<option value="">Error loading course types</option>');
                });
        });

        $('#courseType').on('change', function () {

    const courseType = $(this).val();
    const departmentId = $('#department').val();
    const subtype = $('#subType');

    // Always reset subtype when course type changes
    subtype
        .prop('disabled', true)
        .html('<option value="">Loading...</option>');

    // Course type or department not selected
    if (!courseType || !departmentId) {
        subtype
            .prop('disabled', true)
            .html('<option value="">Select Course Sub Type</option>');

        return;
    }

    fetch(
        `/ajax/get-branches-by-course?department_id=${encodeURIComponent(departmentId)}&course_type=${encodeURIComponent(courseType)}`
    )
    .then(res => {
        if (!res.ok) {
            throw new Error(`HTTP ${res.status}`);
        }

        return res.json();
    })
    .then(data => {

        console.log('Course subtype response:', data);

        subtype.empty();

        subtype.append(
            $('<option>', {
                value: '',
                text: 'Select Course Sub Type'
            })
        );

        if (
            data.status === 'success' &&
            Array.isArray(data.branches) &&
            data.branches.length > 0
        ) {

            data.branches.forEach(function (b) {

                subtype.append(
                    $('<option>', {
                        value: b.product_id,
                        text: b.sub_type
                    })
                );

            });

            // IMPORTANT
            // Enable only after course sub types are loaded
            subtype.prop('disabled', false);

            console.log(
                'Subtype enabled:',
                !subtype.prop('disabled')
            );

            // Automatically select if only one subtype exists
            if (data.branches.length === 1) {

                subtype
                    .val(data.branches[0].product_id)
                    .trigger('change');
            }

        } else {

            subtype
                .html('<option value="">No sub types found</option>')
                .prop('disabled', true);
        }

    })
    .catch(err => {

        console.error('Error loading sub types:', err);

        subtype
            .html('<option value="">Error loading sub types</option>')
            .prop('disabled', true);
    });
});

        function loadAcademicYears(productId) {
            const yearSelect = $('#academic_year');
            yearSelect.prop('disabled', true).html('<option value="">Loading academic years...</option>');
            fetch(`/ajax/get-academic-years-by-product?product_id=${encodeURIComponent(productId)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success' && Array.isArray(data.academic_years) && data.academic_years.length > 0) {
                        yearSelect.html('<option value="">Select Academic Year</option>');
                        data.academic_years.forEach(y => yearSelect.append(`<option value="${y}">${y}</option>`));
                        yearSelect.prop('disabled', false);
                        if (data.academic_years.length === 1) {
                            yearSelect.val(data.academic_years[0]);
                        }
                    } else {
                        yearSelect.html('<option value="">No academic years found</option>');
                        yearSelect.prop('disabled', true);
                    }
                }).catch(err => {
                    console.error('Error loading academic years:', err);
                    yearSelect.html('<option value="">Error loading academic years</option>');
                    yearSelect.prop('disabled', true);
                });
        }

        function loadSubjects(productId) {
            const subjectSelect = $('#subject');
            subjectSelect.prop('disabled', true).html('<option value="">Loading subjects...</option>');
            fetch(`/instituteAdmin/syllabus/get-subjects/${encodeURIComponent(productId)}`)
                .then(res => res.json())
                .then(data => {
                    subjectSelect.html('<option value="">Select Subject</option>');
                    const subjects = Array.isArray(data) ? data : (data?.subjects || []);
                    if (Array.isArray(subjects) && subjects.length > 0) {
                        subjects.forEach(s => {
                            subjectSelect.append(`<option value="${s.subject_id}">${s.subject_name}</option>`);
                        });
                        subjectSelect.prop('disabled', false);
                    } else {
                        subjectSelect.html('<option value="">No subjects found</option>');
                    }
                }).catch(err => {
                    console.error('Error loading subjects:', err);
                    subjectSelect.html('<option value="">Error loading subjects</option>');
                });
        }

        $('#subType').on('change', function() {
            const productId = $(this).val();
            if (productId) {
                loadAcademicYears(productId);
                loadSubjects(productId);
            } else {
                $('#academic_year').html('<option value="">Select Course Sub Type first</option>').prop('disabled', true);
                $('#subject').html('<option value="">Select Subject</option>');
            }
            courseDateRange = null;
            updateMonthSelector();
        });

        $('#subject').on('change', function() {
            loadCourseDateRange();
        });

        // Add initial topic
        addTopicTab();

    });
</script>
@endsection
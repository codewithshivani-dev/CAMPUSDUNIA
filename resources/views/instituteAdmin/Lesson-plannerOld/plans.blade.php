@extends('instituteAdmin.Lesson-planner.index')

@section('styles')
<style>
    .page-content {
        padding: 28px 32px;
        background: #f0f4f9;
    }

    .page-subtitle {
        color: #4b6a8b;
        font-size: 15px;
        margin-bottom: 28px;
        font-weight: 400;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .page-subtitle i { color: #2563eb; }
    #teacher-name-display {
        color: #4b6a8b;
        font-size: 13px;
        margin-left: 10px;
        padding-left: 10px;
        border-left: 1px solid #dbe5f0;
    }

    .filters {
        display: flex;
        flex-wrap: wrap;
        gap: 16px 24px;
        background: white;
        padding: 16px 28px;
        border-radius: 60px;
        border: 1px solid #eaf0f6;
        margin-bottom: 28px;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }
    .filter-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .filter-group label {
        font-size: 13px;
        font-weight: 500;
        color: #4b6a8b;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .filter-group label i { color: #2563eb; }
    .filter-group select {
        padding: 8px 16px;
        border-radius: 30px;
        border: 1.5px solid #eaf0f6;
        background: white;
        font-size: 13px;
        color: #0a1e3c;
        outline: none;
        cursor: pointer;
        transition: all 0.2s;
        min-width: 120px;
    }
    .filter-group select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .btn {
        padding: 8px 20px;
        border-radius: 30px;
        border: none;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-primary {
        background: #2563eb;
        color: white;
    }
    .btn-primary:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    .btn-secondary {
        background: #eef2f6;
        color: #4b6a8b;
    }
    .btn-secondary:hover {
        background: #dde7f1;
        transform: translateY(-2px);
    }
    .btn-success {
        background: #2563eb;
        color: white;
    }
    .btn-success:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    .btn-danger {
        background: #ef4444;
        color: white;
    }
    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    .btn-warning {
        background: #f59e0b;
        color: white;
    }
    .btn-warning:hover {
        background: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    .btn-small { padding: 4px 12px; font-size: 11px; }

    .card {
        background: white;
        border-radius: 28px;
        padding: 24px 26px 20px;
        border: 1px solid #eaf0f6;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.01);
    }

    .subject-tabs {
        display: flex;
        gap: 12px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .subject-tab {
        padding: 10px 24px;
        border-radius: 30px;
        border: 2px solid #eaf0f6;
        background: white;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
        color: #4b6a8b;
    }
    .subject-tab:hover { border-color: #2563eb; }
    .subject-tab.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    .subject-tab i { margin-right: 8px; }

    .month-tabs-wrapper {
        margin-bottom: 20px;
        border-bottom: 2px solid #eaf0f6;
        padding-bottom: 12px;
    }
    .month-tabs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .month-tab {
        padding: 8px 20px;
        border-radius: 20px;
        border: 1.5px solid #eaf0f6;
        background: white;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s;
        color: #4b6a8b;
    }
    .month-tab:hover { 
        border-color: #2563eb; 
        background: #eff6ff;
    }
    .month-tab.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }
    .month-tab .month-count {
        font-weight: 400;
        font-size: 11px;
        opacity: 0.7;
        margin-left: 4px;
    }
    .month-tab.active .month-count {
        opacity: 0.9;
    }
    .month-tab i { margin-right: 6px; }

    .month-section {
        margin-bottom: 28px;
        display: none !important;
    }
    .month-section.active {
        display: block !important;
        animation: fadeIn 0.3s ease;
    }
    .month-section:last-child { margin-bottom: 0; }
    .month-section .month-section-content {
        display: none;
    }
    .month-section.active .month-section-content {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .month-header {
        font-size: 20px;
        font-weight: 700;
        color: #0a1e3c;
        padding: 12px 0 16px;
        border-bottom: 2px solid #e6ecf3;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .month-header i { color: #2563eb; margin-right: 10px; }
    .month-header .count {
        font-size: 14px;
        font-weight: 400;
        color: #4b6a8b;
    }
    .month-header .plan-badge {
        font-size: 12px;
        font-weight: 500;
        padding: 4px 14px;
        border-radius: 20px;
        background: #eef2f6;
        color: #4b6a8b;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .month-header .plan-badge.weekly {
        background: #dbeafe;
        color: #1d4ed8;
    }
    .month-header .plan-badge i { 
        color: inherit;
        margin-right: 4px;
        font-size: 12px;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 6px;
        margin-bottom: 16px;
    }
    .calendar-weekday {
        font-weight: 600;
        font-size: 13px;
        color: #4b6a8b;
        text-align: center;
        padding: 8px 0;
        background: #f8fafc;
        border-radius: 8px;
    }
    .calendar-day {
        background: white;
        border: 1px solid #eaf0f6;
        border-radius: 10px;
        padding: 8px 4px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        min-height: 70px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .calendar-day:hover {
        border-color: #2563eb;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.15);
        transform: translateY(-2px);
        z-index: 10;
    }
    .calendar-day .day-number {
        font-weight: 600;
        font-size: 16px;
        color: #0a1e3c;
        line-height: 1.4;
    }
    
    .calendar-day .day-status-dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-top: 4px;
        border: 2px solid white;
        box-shadow: 0 1px 4px rgba(0,0,0,0.15);
    }
    .calendar-day .day-status-dot.covered { 
        background: #10b981;
    }
    .calendar-day .day-status-dot.partial { 
        background: #f59e0b; 
    }
    .calendar-day .day-status-dot.not-covered { 
        background: #ef4444; 
    }
    .calendar-day .day-status-dot.empty { 
        background: #d1d5db; 
        opacity: 0.5;
    }

    .calendar-day.has-plan .day-number {
        color: #0a1e3c;
        font-weight: 700;
    }
    .calendar-day.no-plan {
        opacity: 0.4;
        cursor: default;
    }
    .calendar-day.no-plan:hover {
        border-color: #eaf0f6;
        box-shadow: none;
        transform: none;
    }
    .calendar-day.active-day {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.3);
        transform: scale(1.02);
    }
    .calendar-day .day-label {
        font-size: 9px;
        color: #8a9bb5;
        margin-top: 2px;
        font-weight: 500;
        text-transform: uppercase;
    }
    .calendar-day .day-label.empty-day {
        color: #d1d5db;
    }

    .weekly-plan-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 10px;
        margin-bottom: 16px;
    }
    .weekly-plan-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
        align-content: start;
        padding: 14px 18px;
        background: #fff;
        border: 1px solid #eaf0f6;
        border-radius: 12px;
        min-height: 150px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .weekly-plan-row:hover {
        border-color: #2563eb;
        background: #f8faff;
        box-shadow: 0 3px 12px rgba(37, 99, 235, 0.1);
        transform: translateY(-1px);
    }
    .weekly-plan-week {
        color: #0a1e3c;
        font-size: 14px;
        font-weight: 700;
    }
    .weekly-plan-date {
        display: block;
        margin-top: 3px;
        color: #8a9bb5;
        font-size: 11px;
        font-weight: 400;
    }
    .weekly-plan-topics {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    .weekly-plan-topic {
        padding: 5px 10px;
        border-radius: 8px;
        background: #f0f4f9;
        color: #4b6a8b;
        font-size: 12px;
    }
    .weekly-plan-status {
        min-width: 92px;
        padding: 5px 10px;
        border-radius: 20px;
        text-align: center;
        font-size: 11px;
        font-weight: 600;
        justify-self: start;
    }
    .weekly-plan-status.covered { background: #d1fae5; color: #065f46; }
    .weekly-plan-status.partial { background: #fef3c7; color: #92400e; }
    .weekly-plan-status.not-covered { background: #fee2e2; color: #991b1b; }

    @media (max-width: 820px) {
        .weekly-plan-list { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 560px) {
        .weekly-plan-list { grid-template-columns: 1fr; }
    }

    /* ======================================== */
    /* DAY DETAIL PANEL */
    /* ======================================== */
    .day-detail-panel {
        background: linear-gradient(145deg, #f8fafc 0%, #ffffff 100%);
        border-radius: 16px;
        padding: 0;
        border: 1px solid #eaf0f6;
        margin-top: 16px;
        display: none;
        scroll-margin-top: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }
    .day-detail-panel.open {
        display: block;
        animation: slideDown 0.4s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .day-detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 18px 24px;
        background: linear-gradient(135deg, #2563eb08 0%, #2563eb04 100%);
        border-bottom: 2px solid #eaf0f6;
    }
    .day-detail-title {
        font-size: 18px;
        font-weight: 700;
        color: #0a1e3c;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .day-detail-title i {
        color: #2563eb;
        font-size: 20px;
        background: #2563eb12;
        padding: 8px;
        border-radius: 10px;
    }
    .day-detail-title .day-meta {
        font-size: 13px;
        font-weight: 400;
        color: #6b8aaa;
        margin-left: 4px;
    }
    .day-detail-title .day-meta i {
        font-size: 12px;
        background: none;
        padding: 0;
        color: #6b8aaa;
    }
    .day-detail-title .placeholder-badge {
        font-size: 11px;
        font-weight: 500;
        color: #f59e0b;
        background: #fef3c7;
        padding: 2px 12px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .day-detail-stats {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }
    .day-detail-stats .stat-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #4b6a8b;
        font-weight: 500;
    }
    .day-detail-stats .stat-item .stat-number {
        font-weight: 700;
        color: #0a1e3c;
    }
    .day-detail-stats .stat-item .stat-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }
    .day-detail-stats .stat-item .stat-dot.covered { background: #10b981; }
    .day-detail-stats .stat-item .stat-dot.partial { background: #f59e0b; }
    .day-detail-stats .stat-item .stat-dot.not-covered { background: #ef4444; }

    .day-detail-status-badge {
        padding: 6px 18px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        letter-spacing: 0.3px;
    }
    .day-detail-status-badge.covered {
        background: #d1fae5;
        color: #065f46;
    }
    .day-detail-status-badge.covered i { color: #10b981; }
    .day-detail-status-badge.partial {
        background: #fef3c7;
        color: #92400e;
    }
    .day-detail-status-badge.partial i { color: #f59e0b; }
    .day-detail-status-badge.not-covered {
        background: #fee2e2;
        color: #991b1b;
    }
    .day-detail-status-badge.not-covered i { color: #ef4444; }
    .day-detail-status-badge.empty {
        background: #f3f4f6;
        color: #6b7280;
    }
    .day-detail-status-badge.empty i { color: #9ca3af; }

    /* Topic Items */
    .day-detail-topics {
        padding: 16px 24px 20px;
    }
    .day-detail-topics .topics-label {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #8a9bb5;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .day-detail-topics .topics-label i { color: #2563eb; }

    .topic-item {
        display: flex;
        align-items: flex-start;
        padding: 14px 18px;
        background: white;
        border-radius: 12px;
        margin-bottom: 10px;
        border: 1px solid #eaf0f6;
        gap: 14px;
        flex-wrap: wrap;
        transition: all 0.25s ease;
        position: relative;
    }
    .topic-item:hover {
        border-color: #2563eb;
        box-shadow: 0 2px 12px rgba(37, 99, 235, 0.06);
        transform: translateY(-1px);
    }
    .topic-item:last-child { margin-bottom: 0; }

    .topic-item .topic-number-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        background: #2563eb0c;
        border-radius: 50%;
        font-weight: 700;
        font-size: 14px;
        color: #2563eb;
        flex-shrink: 0;
        border: 1.5px solid #2563eb18;
    }

    .topic-item .topic-content {
        flex: 1;
        min-width: 180px;
    }
    .topic-item .topic-title {
        font-size: 15px;
        font-weight: 600;
        color: #0a1e3c;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .topic-item .topic-title .topic-status-icon {
        font-size: 14px;
    }
    .topic-item .topic-title .topic-status-icon.covered { color: #10b981; }
    .topic-item .topic-title .topic-status-icon.partial { color: #f59e0b; }
    .topic-item .topic-title .topic-status-icon.not-covered { color: #ef4444; }
    .topic-item .topic-title.covered-text {
        color: #10b981;
        opacity: 0.8;
    }

    .topic-item .topic-description {
        margin-top: 6px;
        color: #4b6a8b;
        font-size: 13px;
        line-height: 1.5;
        padding-left: 0;
        white-space: pre-line;
    }

    .topic-item .topic-subtopics {
        display: grid;
        gap: 6px;
        margin-top: 8px;
    }
    .topic-item .topic-subtopic-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px 14px;
        border: 1px solid #eaf0f6;
        border-radius: 8px;
        background: #fafbfc;
        color: #0a1e3c;
        font-size: 13px;
        transition: all 0.2s;
    }
    .topic-item .topic-subtopic-row:hover {
        border-color: #2563eb;
        background: #f8faff;
    }
    .topic-item .topic-subtopic-row .subtopic-number {
        min-width: 22px;
        color: #2563eb;
        font-weight: 600;
        font-size: 12px;
    }
    .topic-item .topic-subtopic-row i { color: #6b8aaa; }

    /* ======================================== */
    /* COVERAGE STATUS - CLEAR DESIGN */
    /* ======================================== */
    .topic-item .coverage-status-section {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 6px;
        width: 100%;
        padding-top: 10px;
        border-top: 1px solid #f0f4f9;
    }
    .topic-item .coverage-status-section .status-label {
        font-size: 12px;
        font-weight: 600;
        color: #4b6a8b;
        display: flex;
        align-items: center;
        gap: 6px;
        min-width: 80px;
    }
    .topic-item .coverage-status-section .status-label i {
        color: #2563eb;
    }

    /* Current Status Display */
    .topic-item .current-status-display {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
    }
    .topic-item .current-status-display.covered {
        background: #d1fae5;
        color: #065f46;
    }
    .topic-item .current-status-display.covered i { color: #10b981; }
    .topic-item .current-status-display.partial {
        background: #fef3c7;
        color: #92400e;
    }
    .topic-item .current-status-display.partial i { color: #f59e0b; }
    .topic-item .current-status-display.not-covered {
        background: #fee2e2;
        color: #991b1b;
    }
    .topic-item .current-status-display.not-covered i { color: #ef4444; }

    /* Change Status Buttons */
    .topic-item .change-status-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        align-items: center;
        margin-left: auto;
    }
    .topic-item .change-status-buttons .change-label {
        font-size: 11px;
        color: #8a9bb5;
        font-weight: 500;
        margin-right: 4px;
    }
    .topic-item .status-btn {
        padding: 4px 14px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f0f4f9;
        color: #4b6a8b;
    }
    .topic-item .status-btn:hover {
        transform: translateY(-2px);
    }
    .topic-item .status-btn.covered {
        border-color: #10b981;
        color: #065f46;
        background: #f0fdf4;
    }
    .topic-item .status-btn.covered:hover {
        background: #10b981;
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }
    .topic-item .status-btn.covered.active {
        background: #10b981;
        color: white;
        box-shadow: 0 2px 12px rgba(16, 185, 129, 0.35);
        border-color: #10b981;
    }
    .topic-item .status-btn.partial {
        border-color: #f59e0b;
        color: #92400e;
        background: #fffbeb;
    }
    .topic-item .status-btn.partial:hover {
        background: #f59e0b;
        color: white;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    }
    .topic-item .status-btn.partial.active {
        background: #f59e0b;
        color: white;
        box-shadow: 0 2px 12px rgba(245, 158, 11, 0.35);
        border-color: #f59e0b;
    }
    .topic-item .status-btn.not-covered {
        border-color: #ef4444;
        color: #991b1b;
        background: #fef2f2;
    }
    .topic-item .status-btn.not-covered:hover {
        background: #ef4444;
        color: white;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }
    .topic-item .status-btn.not-covered.active {
        background: #ef4444;
        color: white;
        box-shadow: 0 2px 12px rgba(239, 68, 68, 0.35);
        border-color: #ef4444;
    }
    .topic-item .status-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }

    /* ======================================== */
    /* ATTACHMENTS - VIDEOS & FILES ONLY */
    /* ======================================== */
    .topic-item .topic-media-section {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #f0f4f9;
        width: 100%;
    }
    .topic-item .topic-media-toggle {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 6px 18px 6px 14px;
        border-radius: 30px;
        background: #f0f4f9;
        color: #4b6a8b;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        border: none;
        transition: all 0.2s;
    }
    .topic-item .topic-media-toggle:hover {
        background: #2563eb;
        color: white;
    }
    .topic-item .topic-media-toggle i { font-size: 13px; }
    .topic-item .topic-media-toggle .media-count-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: rgba(255,255,255,0.3);
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 10px;
    }
    .topic-item .topic-media-toggle:hover .media-count-badge {
        background: rgba(255,255,255,0.2);
    }
    .topic-item .topic-media-toggle .media-count-badge i {
        font-size: 10px;
    }
    .topic-item .topic-media-toggle .media-count-badge .count-video {
        color: #ff0000;
    }
    .topic-item .topic-media-toggle:hover .media-count-badge .count-video {
        color: white;
    }
    .topic-item .topic-media-toggle .media-count-badge .count-file {
        color: #2563eb;
    }
    .topic-item .topic-media-toggle:hover .media-count-badge .count-file {
        color: white;
    }

    .topic-item .topic-media-content {
        margin-top: 10px;
        display: none;
    }
    .topic-item .topic-media-content.open {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    /* Video Section */
    .topic-item .topic-video-section {
        margin-bottom: 10px;
    }
    .topic-item .topic-video-section .section-label {
        font-size: 11px;
        font-weight: 600;
        color: #4b6a8b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .topic-item .topic-video-section .section-label i {
        color: #ff0000;
    }
    .topic-item .topic-video-section .section-label .count-badge {
        background: #fee2e2;
        color: #991b1b;
        padding: 0 8px;
        border-radius: 10px;
        font-size: 10px;
    }

    .topic-item .topic-video-wrapper {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 12px;
        padding-bottom: 8px;
        max-width: 100%;
    }
    .topic-item .topic-video-card {
        flex: 0 0 160px;
        min-width: 160px;
        max-width: 160px;
    }
    .topic-item .topic-video-thumbnail {
        position: relative;
        display: block;
        border-radius: 10px;
        overflow: hidden;
        width: 100%;
        aspect-ratio: 16 / 9;
        background: #1a1a2e;
        border: 1px solid #eaf0f6;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .topic-item .topic-video-thumbnail:hover {
        transform: scale(1.04);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        z-index: 5;
    }
    .topic-item .topic-video-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .topic-item .topic-video-thumbnail .play-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(255, 0, 0, 0.85);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        transition: all 0.2s ease;
    }
    .topic-item .topic-video-thumbnail:hover .play-overlay {
        background: rgba(255, 0, 0, 1);
        transform: translate(-50%, -50%) scale(1.1);
    }
    .topic-item .topic-video-thumbnail .play-overlay i {
        color: white;
        font-size: 12px;
        margin-left: 2px;
    }

    /* Files Section */
    .topic-item .topic-files-section .section-label {
        font-size: 11px;
        font-weight: 600;
        color: #4b6a8b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .topic-item .topic-files-section .section-label i {
        color: #2563eb;
    }
    .topic-item .topic-files-section .section-label .count-badge {
        background: #dbeafe;
        color: #1d4ed8;
        padding: 0 8px;
        border-radius: 10px;
        font-size: 10px;
    }

    .topic-item .topic-resources {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-top: 6px;
    }
    .topic-item .resource-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 30px;
        font-size: 12px;
        text-decoration: none;
        transition: all 0.2s;
        font-weight: 500;
        background: #eef2f6;
        color: #4b6a8b;
    }
    .topic-item .resource-link:hover {
        background: #2563eb;
        color: white;
        transform: translateY(-1px);
    }
    .topic-item .resource-link i { font-size: 12px; }

    /* ======================================== */
    /* RESOURCES - SEPARATE SECTION */
    /* ======================================== */
    .topic-item .topic-resources-section {
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px dashed #eaf0f6;
        width: 100%;
    }
    .topic-item .topic-resources-section .resources-label {
        font-size: 11px;
        font-weight: 600;
        color: #4b6a8b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .topic-item .topic-resources-section .resources-label i {
        color: #f59e0b;
    }
    .topic-item .topic-resources-section .resources-label .count-badge {
        background: #fef3c7;
        color: #92400e;
        padding: 0 8px;
        border-radius: 10px;
        font-size: 10px;
    }

    .topic-item .topic-resources-tags {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }
    .topic-item .topic-resources-tags .resource-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 30px;
        font-size: 11px;
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    .topic-item .topic-resources-tags .resource-tag i {
        color: #f59e0b;
        font-size: 10px;
    }

    .day-detail-empty {
        text-align: center;
        padding: 30px 20px;
        color: #8a9bb5;
    }
    .day-detail-empty i {
        font-size: 36px;
        color: #dce4ed;
        display: block;
        margin-bottom: 10px;
    }
    .day-detail-empty .empty-title {
        font-size: 16px;
        font-weight: 600;
        color: #4b6a8b;
    }
    .day-detail-empty .empty-sub {
        font-size: 13px;
        margin-top: 4px;
    }

    .coverage-legend {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 16px;
        padding: 10px 20px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #eaf0f6;
        align-items: center;
    }
    .coverage-legend .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #4b6a8b;
        font-weight: 500;
    }
    .coverage-legend .legend-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        display: inline-block;
        border: 2px solid white;
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }
    .coverage-legend .legend-dot.covered { background: #10b981; }
    .coverage-legend .legend-dot.partial { background: #f59e0b; }
    .coverage-legend .legend-dot.not-covered { background: #ef4444; }
    .coverage-legend .legend-dot.empty { background: #d1d5db; opacity: 0.5; }
    .coverage-legend .legend-hint {
        color: #8a9bb5;
        font-size: 12px;
        margin-left: auto;
    }
    .coverage-legend .legend-hint i { margin-right: 4px; }

    .scroll-to-top {
        display: none;
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 50%;
        width: 48px;
        height: 48px;
        font-size: 20px;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.4);
        transition: all 0.3s;
        z-index: 999;
    }
    .scroll-to-top:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 24px rgba(37, 99, 235, 0.5);
        background: #1d4ed8;
    }
    .scroll-to-top.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    .toast-container {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: center;
        pointer-events: none;
    }
    .toast {
        background: white;
        padding: 12px 24px;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        border-left: 4px solid #2563eb;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        font-weight: 500;
        color: #0a1e3c;
        animation: slideUp 0.3s ease;
        pointer-events: auto;
        max-width: 500px;
    }
    .toast.success { border-color: #2563eb; }
    .toast.error { border-color: #ef4444; }
    .toast.info { border-color: #2563eb; }
    .toast.warning { border-color: #f59e0b; }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .loading-spinner {
        display: inline-block;
        width: 14px;
        height: 14px;
        border: 2px solid #eef2f6;
        border-top-color: #2563eb;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #8a9bb5;
    }
    .empty-state i {
        font-size: 48px;
        color: #dce4ed;
        display: block;
        margin-bottom: 12px;
    }
    .empty-state .empty-title {
        font-size: 18px;
        font-weight: 600;
        color: #4b6a8b;
    }

    @media (max-width: 820px) {
        .page-content { padding: 16px; }
        .subject-tabs { gap: 8px; }
        .subject-tab { padding: 8px 16px; font-size: 13px; }
        .month-tab { padding: 6px 14px; font-size: 12px; }
        .calendar-grid { gap: 4px; }
        .calendar-day { min-height: 55px; padding: 6px 2px; }
        .calendar-day .day-number { font-size: 14px; }
        .calendar-day .day-status-dot { width: 10px; height: 10px; }
        .day-detail-panel { padding: 0; }
        .day-detail-header { padding: 14px 16px; }
        .day-detail-topics { padding: 12px 16px 16px; }
        .topic-item { padding: 12px 14px; gap: 10px; }
        .topic-item .topic-number-badge { min-width: 28px; height: 28px; font-size: 12px; }
        .topic-item .topic-title { font-size: 14px; }
        .topic-video-wrapper { gap: 8px; }
        .topic-video-card { flex: 0 0 140px; min-width: 140px; max-width: 140px; }
        .scroll-to-top { width: 40px; height: 40px; font-size: 16px; bottom: 20px; right: 20px; }
        .filters {
            flex-direction: column;
            align-items: stretch;
            border-radius: 32px;
            padding: 16px 20px;
        }
        .filter-group select {
            min-width: auto;
            width: 100%;
        }
        .coverage-legend { 
            justify-content: center;
            gap: 12px;
        }
        .coverage-legend .legend-hint { display: none; }
        .topic-item .coverage-status-section {
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
        }
        .topic-item .change-status-buttons {
            margin-left: 0;
            justify-content: flex-start;
        }
        .topic-item .status-btn {
            font-size: 10px;
            padding: 3px 10px;
        }
        .day-detail-stats .stat-item { font-size: 12px; }
        .day-detail-title { font-size: 16px; }
        .day-detail-title i { font-size: 16px; padding: 6px; }
        .topic-item .current-status-display { font-size: 11px; padding: 3px 12px; }
        .topic-item .topic-media-toggle { font-size: 11px; padding: 4px 14px 4px 10px; }
        .topic-item .topic-media-toggle .media-count-badge { font-size: 9px; padding: 1px 8px; }
        .month-header .plan-badge { font-size: 10px; padding: 2px 10px; }
    }
    @media (max-width: 480px) {
        .calendar-grid { gap: 2px; }
        .calendar-day { min-height: 45px; padding: 4px 1px; }
        .calendar-day .day-number { font-size: 12px; }
        .calendar-day .day-status-dot { width: 8px; height: 8px; margin-top: 2px; }
        .month-tab { padding: 4px 10px; font-size: 11px; }
        .month-tab i { display: none; }
        .card { padding: 12px; }
        .coverage-legend { font-size: 11px; gap: 8px; }
        .coverage-legend .legend-dot { width: 10px; height: 10px; }
        .topic-item .topic-video-card { flex: 0 0 120px; min-width: 120px; max-width: 120px; }
        .topic-item .topic-video-wrapper { flex-direction: row; }
        .day-detail-title .day-meta { display: block; font-size: 12px; }
        .day-detail-stats { gap: 10px; }
        .day-detail-status-badge { font-size: 11px; padding: 4px 12px; }
        .topic-item .status-btn { font-size: 9px; padding: 2px 8px; }
        .topic-item .change-status-buttons .change-label { font-size: 10px; }
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <p class="page-subtitle">
        <i class="fas fa-calendar-alt"></i> Daily Lesson Planner - Coverage Tracking
        <span id="teacher-name-display"></span>
    </p>

    <div class="filters">
        <div class="filter-group">
            <label><i class="fas fa-users"></i> Class</label>
            <select id="filter-class">
                <option value="">All</option>
            </select>
        </div>
        <div class="filter-group">
            <label><i class="fas fa-book"></i> Subject</label>
            <select id="filter-subject">
                <option value="">All</option>
            </select>
        </div>
        <button class="btn btn-primary" onclick="applyFilters()"><i class="fas fa-filter"></i> Apply</button>
        <button class="btn btn-secondary" onclick="clearFilters()"><i class="fas fa-undo"></i> Clear</button>
    </div>

    <div class="subject-tabs" id="plan-view-tabs" style="margin-bottom: 18px;">
        <button class="subject-tab active" onclick="switchPlanView('active', this)"><i class="fas fa-calendar-check"></i> Active Plans</button>
        <button class="subject-tab" onclick="switchPlanView('draft', this)"><i class="fas fa-file-alt"></i> Drafts</button>
    </div>

    <div class="subject-tabs" id="subject-tabs-container"></div>

    <!-- Draft Plan Detail Modal -->
    <div id="draft-plan-detail-modal" style="position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:9999; padding:20px; display:none; flex-direction:column; align-items:center; justify-content:flex-start; overflow-y:auto;" onclick="if(event.target.id === 'draft-plan-detail-modal') closeDraftPlanDetail()">
        <div style="background:white; border-radius:12px; max-width:700px; width:100%; margin-top:40px; box-shadow:0 20px 60px rgba(0,0,0,0.3);">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; padding:24px; border-bottom:1px solid #eaf0f6;">
                <div id="draft-plan-detail-title"></div>
                <button onclick="closeDraftPlanDetail()" style="background:none; border:none; font-size:24px; cursor:pointer; color:#9ca3af; flex-shrink:0; margin-left:20px;">&times;</button>
            </div>
            <div id="draft-plan-detail-content" style="padding:24px; max-height:60vh; overflow-y:auto;"></div>
            <div style="padding:20px; border-top:1px solid #eaf0f6; display:flex; gap:10px; justify-content:flex-end;">
                <button onclick="closeDraftPlanDetail()" class="btn btn-secondary" style="border-radius:6px; padding:10px 20px;">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>

    <style>
        #draft-plan-detail-modal {
            animation: none;
        }
        #draft-plan-detail-modal.show {
            display: flex !important;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>

    <div class="coverage-legend">
        <span class="legend-item">
            <span class="legend-dot covered"></span> Covered
        </span>
        <span class="legend-item">
            <span class="legend-dot partial"></span> Partial
        </span>
        <span class="legend-item">
            <span class="legend-dot not-covered"></span> Not Covered
        </span>
        <span class="legend-hint">
            <i class="fas fa-info-circle"></i> Click a day to view and mark topics
        </span>
    </div>

    <div class="card">
        <div id="month-tabs-container"></div>
        <div id="plans-container"></div>
    </div>

    <button class="scroll-to-top" id="scrollToTopBtn" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </button>
</div>

<div class="toast-container" id="toastContainer"></div>

<script>
    // ----------------------------------------------
    // DYNAMIC DATA
    // ----------------------------------------------
    let plansData = [];
    let currentSubject = '';
    let activeMonthIndex = 0;
    let currentPlanView = 'active';

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const colors = {
            success: '#2563eb',
            error: '#ef4444',
            info: '#2563eb',
            warning: '#f59e0b'
        };
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-times-circle',
            info: 'fa-info-circle',
            warning: 'fa-exclamation-triangle'
        };

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <i class="fas ${icons[type]}" style="color: ${colors[type]}; font-size: 18px;"></i>
            <span>${message}</span>
        `;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-10px)';
            toast.style.transition = 'all 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function getYouTubeVideoId(url) {
        if (!url) return null;
        const patterns = [
            /(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\?]+)/,
            /youtube\.com\/embed\/([^&\?]+)/
        ];
        for (const pattern of patterns) {
            const match = url.match(pattern);
            if (match) return match[1];
        }
        return null;
    }

    function getYouTubeThumbnail(url) {
        const videoId = getYouTubeVideoId(url);
        if (!videoId) return null;
        return `https://img.youtube.com/vi/${videoId}/mqdefault.jpg`;
    }

    function formatDate(dateStr) {
        const d = new Date(dateStr + 'T00:00:00');
        return d.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
    }

    function formatDateShort(dateStr) {
        const d = new Date(dateStr + 'T00:00:00');
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    }

    function getDayCoverageStatus(topics) {
        if (!topics || topics.length === 0) { 
            return { status: 'empty', label: 'No Topics', pct: 0, covered: 0, total: 0 };
        }
        
        const covered = topics.filter(t => t.coverage_status === 'covered' || t.covered === true).length;
        const partial = topics.filter(t => t.coverage_status === 'partial').length;
        const total = topics.length;
        const pct = Math.round(((covered + partial * 0.5) / total) * 100);
        
        let status = 'not-covered';
        let label = 'Not Covered';
        
        if (pct === 100) {
            status = 'covered';
            label = 'Covered';
        } else if (pct > 0) {
            status = 'partial';
            label = 'Partial';
        }
        
        return { status, label, pct, covered, total };
    }

    function processPlansForCalendar(plans) {
        const subjects = {};

        plans.forEach(plan => {
            const subject = plan.subject || 'General';
            if (!subjects[subject]) {
                subjects[subject] = {
                    subject: subject,
                    months: {}
                };
            }
            
            // Determine the month key for this plan
            let monthKey = plan.month || 'Unknown';
            let year = plan.year || 2026;
            
            // For weekly plans, use the start date month
            if (plan.plan_level === 'week' && plan.start_date) {
                const startDate = new Date(plan.start_date + 'T00:00:00');
                monthKey = startDate.toLocaleString('en-US', { month: 'long' });
                year = startDate.getFullYear();
                // Add year to month key
                monthKey = monthKey + ' ' + year;
            }
            
            if (!subjects[subject].months[monthKey]) {
                subjects[subject].months[monthKey] = {
                    monthName: monthKey,
                    days: [],
                    planId: plan.id,
                    planTitle: plan.title,
                    planLevel: plan.plan_level,
                    startDate: plan.start_date,
                    endDate: plan.end_date
                };
            }
            
            // Get all dates from the plan's dailyTopics
            const dates = Object.keys(plan.dailyTopics || {}).sort();
            
            dates.forEach(date => {
                const topics = plan.dailyTopics[date] || [];
                const dateObj = new Date(date + 'T00:00:00');
                const dayOfMonth = dateObj.getDate();
                const dayName = dateObj.toLocaleDateString('en-US', { weekday: 'long' });
                
                const covered = topics.filter(t => t.coverage_status === 'covered' || t.covered === true).length;
                const partial = topics.filter(t => t.coverage_status === 'partial').length;
                const total = topics.length;
                const pct = total > 0 ? Math.round(((covered + partial * 0.5) / total) * 100) : 0;
                
                let status = 'not-covered';
                let label = 'Not Covered';
                
                if (total === 0) {
                    status = 'empty';
                    label = 'No Topics';
                } else if (pct === 100) {
                    status = 'covered';
                    label = 'Covered';
                } else if (pct > 0) {
                    status = 'partial';
                    label = 'Partial';
                }
                
                const monthData = subjects[subject].months[monthKey];
                const existingDay = monthData.days.find(d => d.date === date);
                if (existingDay) {
                    existingDay.topics = [...existingDay.topics, ...topics];
                    const newCovered = existingDay.topics.filter(t => t.coverage_status === 'covered' || t.covered === true).length;
                    const newPartial = existingDay.topics.filter(t => t.coverage_status === 'partial').length;
                    const newTotal = existingDay.topics.length;
                    const newPct = newTotal > 0 ? Math.round(((newCovered + newPartial * 0.5) / newTotal) * 100) : 0;
                    
                    let newStatus = 'not-covered';
                    let newLabel = 'Not Covered';
                    
                    if (newTotal === 0) {
                        newStatus = 'empty';
                        newLabel = 'No Topics';
                    } else if (newPct === 100) {
                        newStatus = 'covered';
                        newLabel = 'Covered';
                    } else if (newPct > 0) {
                        newStatus = 'partial';
                        newLabel = 'Partial';
                    }
                    
                    existingDay.status = newStatus;
                    existingDay.coverageLabel = newLabel;
                    existingDay.coveragePct = newPct;
                    existingDay.coveredCount = newCovered;
                    existingDay.totalCount = newTotal;
                } else {
                    monthData.days.push({
                        date: date,
                        dayOfMonth: dayOfMonth,
                        dayName: dayName,
                        status: status,
                        coverageLabel: label,
                        coveragePct: pct,
                        coveredCount: covered,
                        totalCount: total,
                        topics: topics,
                        planId: plan.id,
                        planTitle: plan.title,
                        class: plan.class,
                        subject: plan.subject,
                        week: plan.week,
                        pdf: topics[0]?.files?.[0]?.url || null,
                        video: topics[0]?.video || null,
                        isPlaceholder: total === 0
                    });
                }
            });
        });
        
        const result = {};
        Object.keys(subjects).forEach(subject => {
            const subjectData = subjects[subject];
            result[subject] = {
                subject: subject,
                months: Object.keys(subjectData.months).map(monthKey => {
                    const monthData = subjectData.months[monthKey];
                    const parts = monthKey.split(' ');
                    const monthName = parts[0];
                    const year = parseInt(parts[1]) || 2026;
                    const monthIndex = new Date(`${monthName} 1, ${year}`).getMonth();
                    
                    // Sort days by date
                    const sortedDays = monthData.days.sort((a, b) => {
                        return new Date(a.date + 'T00:00:00') - new Date(b.date + 'T00:00:00');
                    });
                    
                    return {
                        year: year,
                        month: monthIndex,
                        monthName: monthKey,
                        shortName: monthName.substring(0, 3),
                        days: sortedDays,
                        planId: monthData.planId,
                        planTitle: monthData.planTitle,
                        planLevel: monthData.planLevel,
                        startDate: monthData.startDate,
                        endDate: monthData.endDate
                    };
                })
            };
        });
        
        return result;
    }

    function renderDraftPlans() {
        const container = document.getElementById('plans-container');
        const draftPlans = (plansData || []).filter(plan => (plan.status || 'draft') === 'draft');

        if (!draftPlans.length) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-file-alt"></i>
                    <div class="empty-title">No draft plans found</div>
                    <div style="font-size:13px; margin-top:4px; color:#4b6a8b;">Draft lesson plans will appear here until they are activated.</div>
                </div>
            `;
            document.getElementById('month-tabs-container').innerHTML = '';
            document.getElementById('subject-tabs-container').innerHTML = '';
            return;
        }

        container.innerHTML = `
            <div class="draft-plan-list" style="display:grid; gap:16px;">
                ${draftPlans.map(plan => `
                    <div class="card" style="padding:20px; border:1px solid #eaf0f6;">
                        <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:10px;">
                            <div>
                                <div style="font-size:18px; font-weight:700; color:#0a1e3c; margin-bottom:4px;">${plan.title || 'Untitled Plan'}</div>
                                <div style="font-size:13px; color:#4b6a8b;">
                                    <i class="fas fa-book"></i> ${plan.subject || 'Subject'} • ${plan.class || 'Class'}
                                </div>
                            </div>
                            <span style="display:inline-block; background:#fef3c7; color:#92400e; padding:5px 12px; border-radius:20px; font-size:12px; font-weight:600;">
                                <i class="fas fa-pencil-alt"></i> Draft
                            </span>
                        </div>
                        <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:12px;">
                            <button class="btn btn-success" onclick="activateDraftPlan(${plan.id})">
                                <i class="fas fa-check-circle"></i> Activate
                            </button>
                            <button class="btn btn-secondary" onclick="viewDraftPlan(${plan.id})">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
        document.getElementById('month-tabs-container').innerHTML = '';
        document.getElementById('subject-tabs-container').innerHTML = '';
    }

    function renderSubjectTabs(subjects) {
        const container = document.getElementById('subject-tabs-container'); 
        if (!subjects || Object.keys(subjects).length === 0) {
            container.innerHTML = '';
            return;
        }
        
        let html = '';
        const subjectKeys = Object.keys(subjects);
        subjectKeys.forEach((subject, index) => {
            const isActive = index === 0 ? 'active' : ''; 
            const iconMap = {
                'English': 'fa-language',
                'Hindi': 'fa-font',
                'Python': 'fa-python',
                'Mathematics': 'fa-calculator',
                'Science': 'fa-flask',
                'Art': 'fa-paint-brush',
                'Social Studies': 'fa-globe',
                'Computer Science': 'fa-laptop'
            };
            const icon = iconMap[subject] || 'fa-book';
            html += `
                <button class="subject-tab ${isActive}" onclick="switchSubject('${subject}', this)">
                    <i class="fas ${icon}"></i> ${subject}
                </button>
            `;
        });
        container.innerHTML = html;
        
        if (subjectKeys.length > 0) {
            currentSubject = subjectKeys[0];
        }
    }


    

    function renderCalendar(subjectKey) {
        const container = document.getElementById('plans-container');
        const monthTabsContainer = document.getElementById('month-tabs-container');
        
        const processedData = window.processedData || {};
        const subjectData = processedData[subjectKey];
        
        if (!subjectData || !subjectData.months || subjectData.months.length === 0) {
            container.innerHTML = `<div class="empty-state"><i class="fas fa-calendar-alt"></i><div class="empty-title">No plans available for ${subjectKey}</div></div>`;
            monthTabsContainer.innerHTML = '';
            return;
        }

        let tabsHtml = `<div class="month-tabs-wrapper"><div class="month-tabs">`; 
        subjectData.months.forEach((monthData, idx) => {
            const isActive = idx === activeMonthIndex ? 'active' : '';
            const planLevelIcon = monthData.planLevel === 'week' ? 'fa-calendar-week' : 'fa-calendar-alt';
            tabsHtml += `
                <button class="month-tab ${isActive}" onclick="switchMonth(${idx})">  
                    <i class="fas ${planLevelIcon}"></i> ${monthData.shortName}
                    <span class="month-count">(${monthData.days.length})</span>
                    ${monthData.planLevel === 'week' ? '<span style="font-size:9px; background:#dbeafe; padding:1px 8px; border-radius:10px; margin-left:4px; color:#1d4ed8;">Week</span>' : ''}
                </button>
            `;
        });
        tabsHtml += `</div></div>`;
        monthTabsContainer.innerHTML = tabsHtml;

        let html = '';
        subjectData.months.forEach((monthData, monthIdx) => {
            const { year, month, days, monthName, planLevel, startDate, endDate } = monthData;
            const isActive = monthIdx === activeMonthIndex ? 'active' : '';
            
            const dayMap = {};
            days.forEach(d => { dayMap[d.dayOfMonth] = d; });

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            let gridHtml = `<div class="calendar-grid">`;
            const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            weekdays.forEach(wd => {
                gridHtml += `<div class="calendar-weekday">${wd}</div>`;
            });
            for (let i = 0; i < firstDay; i++) {
                gridHtml += `<div class="calendar-day no-plan"></div>`;
            }
            for (let d = 1; d <= daysInMonth; d++) {
                const dayInfo = dayMap[d] || null;
                const hasPlan = !!dayInfo;
                const isActiveClass = hasPlan ? 'has-plan' : 'no-plan';
                
                let dotColor = '';
                let dotClass = '';
                if (hasPlan) {
                    const status = dayInfo.status || 'not-covered';
                    if (status === 'covered') {
                        dotColor = 'covered';
                        dotClass = 'covered';
                    } else if (status === 'partial') {
                        dotColor = 'partial';
                        dotClass = 'partial';
                    } else if (status === 'empty') {
                        dotColor = 'empty';
                        dotClass = 'empty';
                    } else {
                        dotColor = 'not-covered';
                        dotClass = 'not-covered';
                    }
                }

                const dayLabel = hasPlan ? dayInfo.dayName.slice(0,3) : '';
                const isPlaceholder = hasPlan && dayInfo.isPlaceholder;

                gridHtml += `
                    <div class="calendar-day ${isActiveClass} ${hasPlan ? 'clickable' : ''}" 
                         data-month="${month}" data-day="${d}" data-year="${year}"
                         onclick="${hasPlan ? `openDayDetail('${subjectKey}', ${monthIdx}, ${d})` : ''}">
                        <span class="day-number">${d}</span>
                        ${hasPlan ? `<span class="day-status-dot ${dotClass}"></span>` : ''}
                        ${hasPlan ? `<span class="day-label ${isPlaceholder ? 'empty-day' : ''}">${dayLabel}</span>` : ''}
                    </div>
                `;
            }
            gridHtml += `</div>`;

            let weeklyListHtml = '';
            if (planLevel === 'week') {
                const weekGroups = {};
                days.forEach(day => {
                    (day.topics || []).forEach(topic => {
                        const weekKey = topic.week_info || `Week ${Object.keys(weekGroups).length + 1}`;

                        if (!weekGroups[weekKey]) {
                            weekGroups[weekKey] = { days: [], topics: [] };
                        }
                        weekGroups[weekKey].days.push(day);
                        weekGroups[weekKey].topics.push(topic);
                    });
                });

                const weekRows = Object.values(weekGroups)
                    .filter(week => week.topics.length > 0)
                    .map((week, weekIndex) => {
                        const firstDay = week.days[0];
                        const lastDay = week.days[week.days.length - 1];
                        const coverage = getDayCoverageStatus(week.topics);
                        const firstTopic = week.topics[0] || {};
                        const weekLabel = firstTopic.week_info || `Week ${weekIndex + 1}`;
                        const weekDateLabel = firstTopic.weekday_info || `${formatDateShort(firstDay.date)} - ${formatDateShort(lastDay.date)} | ${week.days.length} days`;
                        const encodedTopics = encodeURIComponent(JSON.stringify(week.topics));
                        const encodedDateLabel = encodeURIComponent(weekDateLabel);
                        const topicLabels = week.topics.map(topic =>
                            `<span class="weekly-plan-topic">${topic.title || 'Untitled topic'}</span>`
                        ).join('');

                        return `
                            <div class="weekly-plan-row" onclick="openDayDetail('${subjectKey}', ${monthIdx}, ${firstDay.dayOfMonth}, JSON.parse(decodeURIComponent('${encodedTopics}')), decodeURIComponent('${encodedDateLabel}'))">
                                <div>
                                    <span class="weekly-plan-week">${weekLabel}</span>
                                    <span class="weekly-plan-date">${weekDateLabel}</span>
                                </div>
                                <div class="weekly-plan-topics">${topicLabels}</div>
                                <span class="weekly-plan-status ${coverage.status}">${coverage.label}</span>
                            </div>
                        `;
                    }).join('');

                weeklyListHtml = `<div class="weekly-plan-list">${weekRows || '<div class="empty-state">No topics assigned to this weekly plan.</div>'}</div>`;
            }

            // Build date range display for weekly plans
            let dateRangeHtml = '';
            if (planLevel === 'week' && startDate && endDate) {
                dateRangeHtml = `
                    <span style="font-size:13px; font-weight:400; color:#6b8aaa; margin-left:12px;">
                        <i class="fas fa-arrow-right"></i> ${formatDateShort(startDate)} - ${formatDateShort(endDate)}
                    </span>
                `;
            }

            let detailPanelHtml = `
                <div id="detail-${subjectKey}-${monthIdx}" class="day-detail-panel">
                    <!-- dynamic content -->
                </div>
            `;

            const planBadgeClass = planLevel === 'week' ? 'weekly' : '';
            const planBadgeIcon = planLevel === 'week' ? 'fa-calendar-week' : 'fa-calendar-alt';
            const planBadgeText = planLevel === 'week' ? 'Weekly Plan' : 'Monthly Plan';

            html += `
                <div id="month-${subjectKey}-${monthIdx}" class="month-section ${isActive}">
                    <div class="month-header">
                        <span>
                            <i class="fas fa-calendar-alt"></i> ${monthName}
                            ${dateRangeHtml}
                            <span class="plan-badge ${planBadgeClass}">
                                <i class="fas ${planBadgeIcon}"></i> ${planBadgeText}
                            </span>
                        </span>
                        <span class="count">${days.length} day${days.length > 1 ? 's' : ''}</span>
                    </div>
                    ${planLevel === 'week' ? weeklyListHtml : gridHtml}
                    ${detailPanelHtml}
                </div>
            `;
        });

        container.innerHTML = html;
        hideScrollToTop();
    }

    function switchMonth(monthIndex) {
        activeMonthIndex = monthIndex;
        
        document.querySelectorAll('.month-tab').forEach((tab, idx) => {
            tab.classList.toggle('active', idx === monthIndex);
        });
        
        document.querySelectorAll('.month-section').forEach((section, idx) => {
            section.classList.toggle('active', idx === monthIndex);
        });
        
        document.querySelectorAll('.day-detail-panel').forEach(panel => {
            panel.classList.remove('open');
        });
        document.querySelectorAll('.calendar-day.active-day').forEach(day => {
            day.classList.remove('active-day');
        });
        
        hideScrollToTop();
        
        const card = document.querySelector('.card');
        if (card) {
            card.scrollIntoView({ behavior: 'smooth', block: 'start' }); 
        }
    }

    async function toggleTopicCoverage(topicId, newStatus, planId, date, topicIndex) {
        const buttonGroup = event?.target?.closest('.change-status-buttons');
        const allButtons = buttonGroup ? buttonGroup.querySelectorAll('.status-btn') : [];
        
        allButtons.forEach(btn => btn.disabled = true);

        try {
            const response = await fetch(`/admin-lesson-planner/topic/${topicId}/coverage`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({
                    coverage_status: newStatus,
                    covered_date: newStatus === 'covered' ? new Date().toISOString().split('T')[0] : null,
                    reason: ''
                })
            });

            if (response.ok) {
                const statusLabels = {
                    'covered': 'Covered',
                    'partial': 'Partial',
                    'not_covered': 'Not Covered'
                };
                showToast(`Status changed to: ${statusLabels[newStatus]}`, 'success');
                
                await loadPlansFromServer();
                
                const activeDay = document.querySelector('.calendar-day.active-day');
                if (activeDay) {
                    const day = parseInt(activeDay.dataset.day);
                    openDayDetail(currentSubject, activeMonthIndex, day);
                }
            } else {
                const errorData = await response.json();
                showToast(errorData.message || 'Failed to update status', 'error');
            }
        } catch (error) {
            console.error('Error updating coverage:', error);
            showToast('Error updating status', 'error');
        } finally {
            allButtons.forEach(btn => btn.disabled = false);
        }
    }

    function getTopicId(topic) {
        return topic.id || topic.topic_id || null;
    }

    function openDayDetail(subjectKey, monthIdx, dayOfMonth, topicsOverride = null, dateLabelOverride = '') { 
        const processedData = window.processedData || {};
        const subjectData = processedData[subjectKey];
        if (!subjectData) return;
        
        const monthData = subjectData.months[monthIdx];
        if (!monthData) return;
        
        const dayData = monthData.days.find(d => d.dayOfMonth === dayOfMonth); 
        if (!dayData) return;

        const panelId = `detail-${subjectKey}-${monthIdx}`;
        const panel = document.getElementById(panelId);
        if (!panel) return;

        const monthSection = panel.closest('.month-section');
        monthSection.querySelectorAll('.day-detail-panel').forEach(p => { 
            if (p.id !== panelId) p.classList.remove('open'); 
        });

        const detailTopics = Array.isArray(topicsOverride) ? topicsOverride : dayData.topics;
        const planId = dayData.planId;
        const date = dayData.date;
        const coverage = getDayCoverageStatus(detailTopics);

        let content = '';

        const statusIcon = coverage.status === 'covered' ? 'fa-check-circle' : 
                          coverage.status === 'partial' ? 'fa-adjust' : 
                          coverage.status === 'empty' ? 'fa-circle' : 'fa-times-circle';
        const statusLabel = coverage.status === 'covered' ? 'Fully Covered' : 
                           coverage.status === 'partial' ? 'Partially Covered' : 
                           coverage.status === 'empty' ? 'No Topics' : 'Not Covered';

        content += `
            <div class="day-detail-header">
                <div class="day-detail-title">
                    <i class="fas fa-calendar-day"></i>
                    <span>${dateLabelOverride || formatDate(dayData.date)}</span>
                    <span class="day-meta">
                        <i class="fas fa-clock"></i> ${dayData.dayName}
                        ${dayData.class ? `• <i class="fas fa-users"></i> ${dayData.class}` : ''}
                        ${dayData.isPlaceholder ? `• <span class="placeholder-badge"><i class="fas fa-info-circle"></i> No topics assigned</span>` : ''}
                    </span>
                </div>
                <div class="day-detail-stats">
                    <span class="day-detail-status-badge ${coverage.status}">
                        <i class="fas ${statusIcon}"></i> ${statusLabel} ${coverage.total > 0 ? `(${coverage.pct}%)` : ''}
                    </span>
                </div>
            </div>
        `;

        content += `<div class="day-detail-topics">`;
        
        if (dayData.isPlaceholder) {
            content += `
                <div class="day-detail-empty">
                    <i class="fas fa-calendar-plus"></i>
                    <div class="empty-title">No Topics for This Day</div>
                    <div class="empty-sub">This day is part of the weekly plan but has no topics assigned yet.</div>
                    <div style="margin-top:12px; font-size:13px; color:#4b6a8b;">
                        <i class="fas fa-lightbulb" style="color:#f59e0b;"></i> 
                        Plan: ${dayData.planTitle || 'Lesson Plan'}
                    </div>
                </div>
            `;
        } else if (detailTopics && detailTopics.length > 0) {
            detailTopics.forEach((topic, index) => {
                const topicStatus = topic.coverage_status || (topic.covered ? 'covered' : 'not_covered');
                const isCovered = topicStatus === 'covered';
                const isPartial = topicStatus === 'partial';
                const isNotCovered = topicStatus === 'not_covered';
                
                let statusClass = 'not-covered';
                let statusIconTopic = 'fa-times-circle';
                let statusLabelTopic = 'Not Covered';
                
                if (isCovered) {
                    statusClass = 'covered';
                    statusIconTopic = 'fa-check-circle';
                    statusLabelTopic = 'Covered';
                } else if (isPartial) {
                    statusClass = 'partial';
                    statusIconTopic = 'fa-adjust';
                    statusLabelTopic = 'Partial';
                }
                
                const topicId = getTopicId(topic);
                const hasTopicId = !!topicId;
                
                const videoSource = Array.isArray(topic.videos)
                    ? topic.videos
                    : String(topic.video || topic.video_url || '').split(/[,;\n]+/);
                const videoUrls = videoSource
                    .flatMap(video => typeof video === 'string' ? video.split(/[,;\n]+/) : [video?.url])
                    .map(video => typeof video === 'string' ? video.trim() : video)
                    .filter(Boolean);
                const videoCount = videoUrls.length;
                
                const topicText = topic.topics || topic.description || '';
                const topicList = Array.isArray(topic.topics)
                    ? topic.topics
                    : String(topic.topics || '').split(',').map(item => item.trim()).filter(Boolean);
                const topicListHtml = topicList.length > 0
                    ? `<div class="topic-subtopics">${topicList.map((item, subIndex) => `<div class="topic-subtopic-row"><span class="subtopic-number">#${subIndex + 1}</span><i class="fas fa-book-open"></i><span>${item}</span></div>`).join('')}</div>`
                    : '';
                
                const fileList = Array.isArray(topic.files) ? topic.files : [];
                const fileCount = fileList.length;
                
                const resourceList = topic.resources || [];
                const resourceCount = resourceList.length;
                
                // Build attachments HTML (Videos & Files only)
                let attachmentsHtml = '';
                if (videoCount > 0 || fileCount > 0) {
                    let videoSectionHtml = '';
                    let fileSectionHtml = '';
                    
                    // Videos section
                    if (videoCount > 0) {
                        videoSectionHtml = `
                            <div class="topic-video-section">
                                <div class="section-label">
                                    <i class="fab fa-youtube"></i> Videos
                                    <span class="count-badge">${videoCount}</span>
                                </div>
                                <div class="topic-video-wrapper">
                                    ${videoUrls.map((videoUrl, videoIndex) => {
                                        const thumbnailUrl = getYouTubeThumbnail(videoUrl);
                                        if (!thumbnailUrl) {
                                            return `<a href="${videoUrl}" target="_blank" class="topic-video-link" style="margin-top:0;">
                                                <i class="fab fa-youtube" style="color:#ff0000;"></i> Video ${videoIndex + 1}
                                            </a>`;
                                        }
                                        return `<div class="topic-video-card">
                                            <a href="${videoUrl}" target="_blank" class="topic-video-thumbnail" title="Click to watch video">
                                                <img src="${thumbnailUrl}" alt="Video ${videoIndex + 1} thumbnail" loading="lazy">
                                                <div class="play-overlay"><i class="fas fa-play"></i></div>
                                            </a>
                                        </div>`;
                                    }).join('')}
                                </div>
                            </div>
                        `;
                    }
                    
                    // Files section
                    if (fileCount > 0) {
                        fileSectionHtml = `
                            <div class="topic-files-section">
                                <div class="section-label">
                                    <i class="fas fa-file"></i> Files
                                    <span class="count-badge">${fileCount}</span>
                                </div>
                                <div class="topic-resources">
                                    ${fileList.map(file => {
                                        const fileUrl = file.url || file.path;
                                        const fileName = file.name || 'File';
                                        const fileIcon = file.type === 'pdf' ? 'fa-file-pdf' : 'fa-file';
                                        return `<a href="${fileUrl}" target="_blank" class="resource-link"><i class="fas ${fileIcon}"></i> ${fileName}</a>`;
                                    }).join('')}
                                </div>
                            </div>
                        `;
                    }
                    
                    attachmentsHtml = `
                        <div class="topic-media-section">
                            <button class="topic-media-toggle" onclick="toggleMedia(this)">
                                <i class="fas fa-paperclip"></i> 
                                Attachments
                                <span class="media-count-badge">
                                    ${videoCount > 0 ? `<i class="fab fa-youtube count-video"></i> ${videoCount}` : ''}
                                    ${fileCount > 0 ? `<i class="fas fa-file count-file"></i> ${fileCount}` : ''}
                                </span>
                                <i class="fas fa-chevron-down" style="font-size:10px; margin-left:4px;"></i>
                            </button>
                            <div class="topic-media-content">
                                ${videoSectionHtml}
                                ${fileSectionHtml}
                            </div>
                        </div>
                    `;
                }
                
                // Build Resources HTML (Separate section)
                let resourcesHtml = '';
                if (resourceCount > 0) {
                    resourcesHtml = `
                        <div class="topic-resources-section">
                            <div class="resources-label">
                                <i class="fas fa-tags"></i> Resources
                                <span class="count-badge">${resourceCount}</span>
                            </div>
                            <div class="topic-resources-tags">
                                ${resourceList.map(r => `<span class="resource-tag"><i class="fas fa-tag"></i> ${r}</span>`).join('')}
                            </div>
                        </div>
                    `;
                }
                
                content += `
                    <div class="topic-item" data-topic-id="${topicId || ''}">
                        <div class="topic-content">
                            <div class="topic-title ${isCovered ? 'covered-text' : ''}">
                                <span class="topic-status-icon ${statusClass}">
                                    <i class="fas ${statusIconTopic}"></i>
                                </span>
                                ${topic.title || `Topic ${index + 1}`}
                            </div>
                            ${topicListHtml || (topicText ? `<div class="topic-description">${topicText}</div>` : '')}
                            ${attachmentsHtml}
                            ${resourcesHtml}
                        </div>
                        
                        ${hasTopicId ? `
                            <div class="coverage-status-section">
                                <span class="status-label">
                                    <i class="fas fa-flag-checkered"></i> Current Status:
                                </span>
                                <span class="current-status-display ${statusClass}">
                                    <i class="fas ${statusIconTopic}"></i> ${statusLabelTopic}
                                </span>
                                <div class="change-status-buttons">
                                    <span class="change-label"><i class="fas fa-edit"></i> Change to:</span>
                                    <button class="status-btn covered ${isCovered ? 'active' : ''}" 
                                            onclick="toggleTopicCoverage(${topicId}, 'covered', ${planId}, '${date}', ${index})"
                                            title="Mark as Covered">
                                        <i class="fas fa-check"></i> Covered
                                    </button>
                                    <button class="status-btn partial ${isPartial ? 'active' : ''}" 
                                            onclick="toggleTopicCoverage(${topicId}, 'partial', ${planId}, '${date}', ${index})"
                                            title="Mark as Partial">
                                        <i class="fas fa-adjust"></i> Partial
                                    </button>
                                    <button class="status-btn not-covered ${isNotCovered ? 'active' : ''}" 
                                            onclick="toggleTopicCoverage(${topicId}, 'not_covered', ${planId}, '${date}', ${index})"
                                            title="Mark as Not Covered">
                                        <i class="fas fa-times"></i> Not Covered
                                    </button>
                                </div>
                            </div>
                        ` : `
                            <div class="coverage-status-section">
                                <span class="status-label">
                                    <i class="fas fa-info-circle"></i> Status:
                                </span>
                                <span style="font-size:12px; color:#8a9bb5; display:flex; align-items:center; gap:4px;">
                                    <i class="fas fa-lock"></i> No ID - Cannot change
                                </span>
                            </div>
                        `}
                    </div>
                `;
            });
        } else {
            content += `
                <div class="day-detail-empty">
                    <i class="fas fa-inbox"></i>
                    <div class="empty-title">No Topics for This Day</div>
                    <div class="empty-sub">This day doesn't have any topics assigned yet.</div>
                </div>
            `;
        }
        
        content += `</div>`;

        panel.innerHTML = content;
        panel.classList.add('open');

        monthSection.querySelectorAll('.calendar-day').forEach(el => el.classList.remove('active-day'));
        const targetDay = monthSection.querySelector(`.calendar-day[data-day="${dayOfMonth}"]`);
        if (targetDay) targetDay.classList.add('active-day');

        setTimeout(() => {
            panel.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start',
                inline: 'nearest'
            });
            showScrollToTop();
        }, 200);
    }

    function toggleMedia(button) {
        const content = button.nextElementSibling;
        content.classList.toggle('open');
        const icon = button.querySelector('.fa-chevron-down');
        if (icon) {
            icon.style.transform = content.classList.contains('open') ? 'rotate(180deg)' : 'rotate(0)';
        }
    }

    function showScrollToTop() {
        document.getElementById('scrollToTopBtn').classList.add('show'); 
    }

    function hideScrollToTop() {
        document.getElementById('scrollToTopBtn').classList.remove('show');
    }

    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
        const card = document.querySelector('.card');
        if (card) {
            setTimeout(() => {
                card.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        }
        hideScrollToTop();
    }

    window.addEventListener('scroll', function() {
        const btn = document.getElementById('scrollToTopBtn');
        if (window.scrollY < 100) {
            btn.classList.remove('show');
        }
    });

    function switchPlanView(view, button) {
        currentPlanView = view;
        const tabs = document.querySelectorAll('#plan-view-tabs .subject-tab');
        tabs.forEach(tab => tab.classList.toggle('active', tab === button));

        if (view === 'draft') {
            loadPlansFromServer('', '', 'draft');
            return;
        }

        loadPlansFromServer('', '', 'active');
    }

    function activateDraftPlan(planId) {
        if (!confirm('Activate this draft lesson plan?')) {
            return;
        }

        closeDraftPlanDetail();
        const url = '{{ route("lesson-planner.status.update", ["id" => ":id"]) }}'.replace(':id', planId);

        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: 'active' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Plan activated successfully', 'success');
                currentPlanView = 'active';
                document.querySelectorAll('#plan-view-tabs .subject-tab').forEach((tab, index) => {
                    tab.classList.toggle('active', index === 0);
                });
                loadPlansFromServer('', '', 'active');
            } else {
                showToast(data.message || 'Unable to activate plan', 'error');
            }
        })
        .catch(error => {
            console.error('Activate draft error:', error);
            showToast('Unable to activate plan', 'error');
        });
    }

    function viewDraftPlan(planId) {
        const plan = (plansData || []).find(item => String(item.id) === String(planId));
        if (!plan) {
            showToast('Plan not found', 'error');
            return;
        }
        showDraftPlanDetail(plan);
    }

    function showDraftPlanDetail(plan) {
        const modal = document.getElementById('draft-plan-detail-modal');
        if (!modal) {
            console.error('Modal not found');
            return;
        }

        const modalTitle = document.getElementById('draft-plan-detail-title');
        const modalContent = document.getElementById('draft-plan-detail-content');

        console.log('Showing plan:', plan);

        modalTitle.innerHTML = `
            <div style="display:flex; align-items:center; gap:12px;">
                <i class="fas fa-file-alt" style="color:#f59e0b;"></i>
                <div>
                    <div style="font-size:20px; font-weight:700; color:#0a1e3c;">${plan.title || 'Untitled Plan'}</div>
                    <div style="font-size:13px; color:#4b6a8b; margin-top:4px;">
                        <i class="fas fa-book"></i> ${plan.subject || 'Subject'} • ${plan.class || 'Class'} • ${plan.month || 'Monthly'} ${plan.year || ''}
                    </div>
                </div>
            </div>
        `;

        let topicsHtml = '';
        if (plan.dailyTopics && Object.keys(plan.dailyTopics).length > 0) {
            let dayIndex = 1;
            Object.entries(plan.dailyTopics).forEach(([date, topicList]) => {
                const topics = Array.isArray(topicList) ? topicList : [];
                topicsHtml += `
                    <div class="card" style="padding:16px; margin-bottom:12px; border:1px solid #eaf0f6;">
                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                            <div style="background:#dbeafe; color:#1e40af; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px;">
                                ${dayIndex}
                            </div>
                            <div>
                                <div style="font-size:14px; font-weight:600; color:#0a1e3c;">Day ${dayIndex}</div>
                                <div style="font-size:12px; color:#4b6a8b;"><i class="fas fa-calendar"></i> ${date}</div>
                            </div>
                        </div>
                        ${topics.length > 0 ? `
                            <div style="margin-top:10px; padding-top:10px; border-top:1px solid #f0f4f9;">
                                <div style="font-size:12px; font-weight:600; color:#4b6a8b; margin-bottom:8px;"><i class="fas fa-list"></i> Topics (${topics.length})</div>
                                <div style="display:flex; flex-direction:column; gap:6px;">
                                    ${topics.map((topic, i) => {
                                        const topicContent = topic.topics || topic.title || '';
                                        const topicList = typeof topicContent === 'string' 
                                            ? topicContent.split(',').map(t => t.trim()).filter(Boolean)
                                            : Array.isArray(topicContent) ? topicContent : [topicContent];
                                        
                                        return topicList.map((t, tIdx) => `
                                            <div style="display:flex; align-items:flex-start; gap:8px;">
                                                <span style="color:#2563eb; font-weight:600; min-width:20px;">#${i + 1}${topicList.length > 1 ? '.' + (tIdx + 1) : ''}</span>
                                                <span style="color:#0a1e3c; font-size:13px;">${t}</span>
                                            </div>
                                        `).join('');
                                    }).join('')}
                                </div>
                            </div>
                        ` : ''}
                    </div>
                `;
                dayIndex++;
            });
        } else {
            topicsHtml = `
                <div class="empty-state" style="text-align:center; padding:40px 20px;">
                    <i class="fas fa-inbox" style="font-size:48px; color:#cbd5e1; margin-bottom:12px;"></i>
                    <div style="font-size:16px; color:#0a1e3c; font-weight:600;">No topics yet</div>
                    <div style="font-size:13px; color:#4b6a8b; margin-top:4px;">This plan doesn't have any topics assigned yet.</div>
                </div>
            `;
        }

        modalContent.innerHTML = topicsHtml;
        modal.classList.add('show');
        console.log('Modal shown');
    }

    function closeDraftPlanDetail() {
        const modal = document.getElementById('draft-plan-detail-modal');
        if (modal) {
            modal.classList.remove('show');
        }
    }

    function switchSubject(subjectKey, button) {    
        currentSubject = subjectKey;   
        activeMonthIndex = 0;   
         
        document.querySelectorAll('.subject-tab').forEach(tab => tab.classList.remove('active'));
        if (button) button.classList.add('active');
        
        renderCalendar(subjectKey);
        hideScrollToTop();
    }

    function applyFilters() {
        const classFilter = document.getElementById('filter-class').value;
        const subjectFilter = document.getElementById('filter-subject').value; 
        
        loadPlansFromServer(classFilter, subjectFilter);
    }

    function clearFilters() {
        document.getElementById('filter-class').value = '';
        document.getElementById('filter-subject').value = '';
        loadPlansFromServer('', '');
    }

    function populatePlanFilters(plans, selectedClass = '', selectedSubject = '') {
        const filterOptions = [
            { id: 'filter-class', key: 'class', label: 'All Classes' },
            { id: 'filter-subject', key: 'subject', label: 'All Subjects' }
        ];

        filterOptions.forEach(({ id, key, label }) => {
            const select = document.getElementById(id);
            const selectedValue = key === 'class' ? selectedClass : selectedSubject;
            const values = [...new Set(plans.map(plan => plan[key]).filter(Boolean))].sort();
            if (selectedValue && !values.includes(selectedValue)) values.push(selectedValue);
            select.innerHTML = `<option value="">${label}</option>` + values
                .map(value => `<option value="${String(value).replace(/"/g, '&quot;')}">${value}</option>`)
                .join('');
            select.value = selectedValue;
        });

        const teacherNames = [...new Set(plans.map(plan => plan.teacher).filter(Boolean))];
        document.getElementById('teacher-name-display').textContent = teacherNames.length
            ? `Teacher: ${teacherNames.join(', ')}`
            : '';
    }

    async function loadPlansFromServer(classFilter = '', subjectFilter = '', view = currentPlanView) {
        try {
            let url = '{{ route('lesson-planner.calendar.data') }}';
            const params = new URLSearchParams();
            if (classFilter) params.append('class', classFilter);
            if (subjectFilter) params.append('subject', subjectFilter);
            params.append('status', view === 'draft' ? 'draft' : 'active');
            if (new URLSearchParams(window.location.search).get('employee_id')) {
                params.append('employee_id', new URLSearchParams(window.location.search).get('employee_id'));
            }
            if (params.toString()) url += '?' + params.toString();
            
            const response = await fetch(url);
            const data = await response.json();
            plansData = Array.isArray(data) ? data : [];
            populatePlanFilters(plansData, classFilter, subjectFilter);
            
            if (window.plansData) {
                window.plansData = plansData;
            }

            if (view === 'draft') {
                renderDraftPlans();
                return;
            }
            
            const processed = processPlansForCalendar(plansData); 
            window.processedData = processed;
            
            renderSubjectTabs(processed);
            
            if (Object.keys(processed).length > 0) {
                const firstSubject = Object.keys(processed)[0];
                currentSubject = firstSubject;
                renderCalendar(firstSubject);
            } else {
                document.getElementById('plans-container').innerHTML = ` 
                    <div class="empty-state">
                        <i class="fas fa-calendar-alt"></i>
                        <div class="empty-title">No active lesson plans found</div>
                        <div style="font-size:13px; margin-top:4px; color:#4b6a8b;"> 
                            <i class="fas fa-plus-circle" style="color:#2563eb;"></i> Create a new plan or activate a draft plan to get started
                        </div>
                    </div>
                `;
                document.getElementById('month-tabs-container').innerHTML = ''; 
                document.getElementById('subject-tabs-container').innerHTML = '';
            }
        } catch (error) {
            console.error('Failed to load lesson plans:', error);
            plansData = [];
            window.processedData = {};
            document.getElementById('plans-container').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-circle"></i>   
                    <div class="empty-title">Failed to load plans</div>
                    <div style="font-size:13px; margin-top:4px; color:#4b6a8b;">Please try again later</div>
                </div>
            `;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadPlansFromServer('', '', 'active');
    });

    window.refreshLessonPlannerViews = function() {
        loadPlansFromServer();
    };
</script>
@endsection
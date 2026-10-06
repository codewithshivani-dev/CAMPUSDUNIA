@extends('instituteAdmin.student.Lesson-planner.index')

@section('student-content')
<style>
    .page-content { padding: 28px 32px; background: #f0f4f9; }
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }
    .page-header h1 {
        font-size: 28px;
        font-weight: 800;
        color: #0a1e3c;
        margin: 0;
    }
    .page-header h1 i { color: #10b981; margin-right: 12px; }
    .page-header .badge {
        background: #10b981;
        color: white;
        padding: 6px 20px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
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

    .card {
        background: white;
        border-radius: 28px;
        padding: 24px 26px 20px;
        border: 1px solid #eaf0f6;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.01);
    }

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
    .month-tab .plan-badge-tab {
        font-size: 9px;
        background: #dbeafe;
        padding: 1px 8px;
        border-radius: 10px;
        margin-left: 4px;
        color: #1d4ed8;
    }

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

    /* Coverage Legend */
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

    /* Calendar Grid */
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
    .calendar-day .day-status-dot.covered { background: #10b981; }
    .calendar-day .day-status-dot.partial { background: #f59e0b; }
    .calendar-day .day-status-dot.not-covered { background: #ef4444; }
    .calendar-day .day-status-dot.empty { background: #d1d5db; opacity: 0.5; }

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

    /* Weekly Plan List */
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

    /* Day Detail Panel */
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
    @keyframes slideDown {
        0% { opacity: 0; transform: translateY(-12px); }
        100% { opacity: 1; transform: translateY(0); }
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
        flex-wrap: wrap;
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

    /* Status display - View Only (Student) */
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

    /* Attachments - Videos & Files */
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

    /* Resources Section */
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
        .weekly-plan-list { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 560px) {
        .weekly-plan-list { grid-template-columns: 1fr; }
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
    }
</style>

<div class="page-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-calendar-alt"></i> Lesson Plans</h1>
        </div>
        <span class="badge"><i class="fas fa-eye"></i> View Only</span>
    </div>

    <p class="page-subtitle">
        <i class="fas fa-calendar-alt"></i> Daily Lesson Planner - Coverage View
    </p>

    <div class="filters">
        <div class="filter-group">
            <label><i class="fas fa-users"></i> Class</label>
            <select id="filter-class">
                <option value="">All</option>
                <option value="KG/A">KG / A</option>
                <option value="Nursery/A">Nursery / A</option>
                <option value="Grade 1/A">Grade 1 / A</option>
                <option value="Grade 2/A">Grade 2 / A</option>
                <option value="Grade 3/A">Grade 3 / A</option>
                <option value="Grade 4/A">Grade 4 / A</option>
                <option value="Grade 5/A">Grade 5 / A</option>
            </select>
        </div>
        <div class="filter-group">
            <label><i class="fas fa-book"></i> Subject</label>
            <select id="filter-subject">
                <option value="">All</option>
                <option value="English">English</option>
                <option value="Mathematics">Mathematics</option>
                <option value="Science">Science</option>
                <option value="Art">Art</option>
                <option value="Social Studies">Social Studies</option>
                <option value="Computer Science">Computer Science</option>
                <option value="Python">Python</option>
                <option value="Hindi">Hindi</option>
            </select>
        </div>
        <button class="btn btn-primary" onclick="applyFilters()"><i class="fas fa-filter"></i> Apply</button>
        <button class="btn btn-secondary" onclick="clearFilters()"><i class="fas fa-undo"></i> Clear</button>
    </div>

    <!-- Subject Tabs -->
    <div class="subject-tabs" id="subject-tabs-container"></div>

    <div class="card">
        <!-- Month Tabs -->
        <div id="month-tabs-container"></div>

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
            <!-- <span class="legend-item">
                <span class="legend-dot empty"></span> No Topics
            </span> -->
            <span class="legend-hint">
                <i class="fas fa-info-circle"></i> Click a day to view details
            </span>
        </div>
        
        <!-- Plans Container -->
        <div id="plans-container">
            <!-- Plans will be rendered here -->
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <button class="scroll-to-top" id="scrollToTopBtn" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </button>
</div>

<script>
    // ----------------------------------------------
    // DYNAMIC DATA - Student View
    // ----------------------------------------------
    let plansData = [];
    let currentSubject = '';
    let activeMonthIndex = 0;

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    function formatDate(dateStr) {
        const d = new Date(dateStr + 'T00:00:00');
        return d.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
    }

    function formatDateShort(dateStr) {
        const d = new Date(dateStr + 'T00:00:00');
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
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
            
            let monthKey = plan.month || 'Unknown';
            let year = plan.year || 2026;
            
            if (plan.plan_level === 'week' && plan.start_date) {
                const startDate = new Date(plan.start_date + 'T00:00:00');
                monthKey = startDate.toLocaleString('en-US', { month: 'long' });
                year = startDate.getFullYear();
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
            
            const dates = Object.keys(plan.dailyTopics || {}).sort();
            
            dates.forEach(date => {
                const topics = plan.dailyTopics[date] || [];
                const dateObj = new Date(date + 'T00:00:00');
                const dayOfMonth = dateObj.getDate();
                const dayName = dateObj.toLocaleDateString('en-US', { weekday: 'long' });
                
                const coverage = getDayCoverageStatus(topics);
                
                const monthData = subjects[subject].months[monthKey];
                const existingDay = monthData.days.find(d => d.date === date);
                if (existingDay) {
                    existingDay.topics = [...existingDay.topics, ...topics];
                    const newCoverage = getDayCoverageStatus(existingDay.topics);
                    existingDay.status = newCoverage.status;
                    existingDay.coverageLabel = newCoverage.label;
                    existingDay.coveragePct = newCoverage.pct;
                    existingDay.coveredCount = newCoverage.covered;
                    existingDay.totalCount = newCoverage.total;
                } else {
                    monthData.days.push({
                        date: date,
                        dayOfMonth: dayOfMonth,
                        dayName: dayName,
                        status: coverage.status,
                        coverageLabel: coverage.label,
                        coveragePct: coverage.pct,
                        coveredCount: coverage.covered,
                        totalCount: coverage.total,
                        topics: topics,
                        planId: plan.id,
                        planTitle: plan.title,
                        class: plan.class,
                        subject: plan.subject,
                        week: plan.week,
                        pdf: topics[0]?.files?.[0]?.url || null,
                        video: topics[0]?.video || null,
                        isPlaceholder: coverage.total === 0
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
                    ${monthData.planLevel === 'week' ? '<span class="plan-badge-tab">Week</span>' : ''}
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

            // Calendar Grid
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
                
                let dotClass = '';
                if (hasPlan) {
                    const status = dayInfo.status || 'not-covered';
                    if (status === 'covered') {
                        dotClass = 'covered';
                    } else if (status === 'partial') {
                        dotClass = 'partial';
                    } else if (status === 'empty') {
                        dotClass = 'empty';
                    } else {
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

            // Weekly Plan List
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
                        const topicLabels = week.topics.map(topic =>
                            `<span class="weekly-plan-topic">${topic.title || 'Untitled topic'}</span>`
                        ).join('');

                        return `
                            <div class="weekly-plan-row" onclick="openDayDetail('${subjectKey}', ${monthIdx}, ${firstDay.dayOfMonth})">
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

            // Date range display for weekly plans
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

    function openDayDetail(subjectKey, monthIdx, dayOfMonth) { 
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

        const detailTopics = dayData.topics;
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
                    <span>${formatDate(dayData.date)}</span>
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
                
                const videoSource = Array.isArray(topic.videos)
                    ? topic.videos
                    : String(topic.video || topic.video_url || '').split(/[,;\n]+/);
                const videoUrls = videoSource
                    .flatMap(video => typeof video === 'string' ? video.split(/[,;\n]+/) : [video?.url])
                    .map(video => typeof video === 'string' ? video.trim() : video)
                    .filter(Boolean);
                const videoCount = videoUrls.length;
                
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
                                            return `<a href="${videoUrl}" target="_blank" class="resource-link" style="margin-top:0;" data-media-track="true" data-topic-id="${topic.id}" data-media-type="video" data-media-url="${encodeURIComponent(videoUrl)}" onclick="recordMediaView(this)">
                                                <i class="fab fa-youtube" style="color:#ff0000;"></i> Video ${videoIndex + 1}
                                            </a>`;
                                        }
                                        return `<div class="topic-video-card">
                                            <a href="${videoUrl}" target="_blank" class="topic-video-thumbnail" title="Click to watch video" data-media-track="true" data-topic-id="${topic.id}" data-media-type="video" data-media-url="${encodeURIComponent(videoUrl)}" onclick="recordMediaView(this)">
                                                <img src="${thumbnailUrl}" alt="Video ${videoIndex + 1} thumbnail" loading="lazy">
                                                <div class="play-overlay"><i class="fas fa-play"></i></div>
                                            </a>
                                        </div>`;
                                    }).join('')}
                                </div>
                            </div>
                        `;
                    }
                    
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
                                        return `<a href="${fileUrl}" target="_blank" class="resource-link" data-media-track="true" data-topic-id="${topic.id}" data-media-type="file" data-media-url="${encodeURIComponent(fileUrl)}" data-media-name="${fileName}" onclick="recordMediaView(this)"><i class="fas ${fileIcon}"></i> ${fileName}</a>`;
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
                
                // Build Resources HTML
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
                    <div class="topic-item">
                        <div class="topic-content">
                            <div class="topic-title ${isCovered ? 'covered-text' : ''}">
                                <span class="topic-status-icon ${statusClass}">
                                    <i class="fas ${statusIconTopic}"></i>
                                </span>
                                ${topic.title || `Topic ${index + 1}`}
                            </div>
                            ${topicListHtml || (topic.description ? `<div class="topic-description">${topic.description}</div>` : '')}
                            ${attachmentsHtml}
                            ${resourcesHtml}
                        </div>
                        
                        <div class="coverage-status-section">
                            <span class="status-label">
                                <i class="fas fa-flag-checkered"></i> Status:
                            </span>
                            <span class="current-status-display ${statusClass}">
                                <i class="fas ${statusIconTopic}"></i> ${statusLabelTopic}
                            </span>
                        </div>
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

    function recordMediaView(link) {
        const topicId = Number(link.dataset.topicId);
        const mediaType = link.dataset.mediaType;
        const mediaUrl = decodeURIComponent(link.dataset.mediaUrl || '');

        if (!topicId || !mediaType || !mediaUrl) return;

        fetch('{{ route("student.lesson-planner.media-view") }}', {
            method: 'POST',
            credentials: 'same-origin',
            keepalive: true,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({
                lesson_topic_id: topicId,
                media_type: mediaType,
                media_url: mediaUrl,
                media_name: link.dataset.mediaName || null
            })
        }).catch(() => {});
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

    async function loadPlansFromServer(classFilter = '', subjectFilter = '') {
        try {
            let url = '{{ route("student.lesson-planner.data") }}';
            const params = new URLSearchParams();
            params.append('status', 'active'); // Only show active plans to students
            if (classFilter) params.append('class', classFilter);
            if (subjectFilter) params.append('subject', subjectFilter);
            if (params.toString()) url += '?' + params.toString();
            
            const response = await fetch(url);
            const data = await response.json();
            plansData = Array.isArray(data) ? data : [];
            
            if (window.plansData) {
                window.plansData = plansData;
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
                        <div class="empty-title">No lesson plans found</div>
                        <div style="font-size:13px; margin-top:4px; color:#4b6a8b;"> 
                            No plans are available for your course at this time.
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
        loadPlansFromServer();
    });
</script>
@endsection
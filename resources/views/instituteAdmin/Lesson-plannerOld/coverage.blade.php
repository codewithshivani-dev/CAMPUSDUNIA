@extends('instituteAdmin.Lesson-planner.index')
@section('styles')
<style>
    .page-content {
        padding: 28px 32px;
        background: #f0f4f9;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .page-title-section {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 800;
        color: #0a1e3c;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .page-title i { color: #2563eb; }

    .page-subtitle {
        color: #4b6a8b;
        font-size: 15px;
        font-weight: 400;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .page-subtitle i { color: #2563eb; }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: white;
        padding: 20px 24px;
        border-radius: 20px;
        border: 1px solid #eaf0f6;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.08);
    }
    .stat-card .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 10px;
    }
    .stat-card .stat-icon.blue { background: #dbeafe; color: #2563eb; }
    .stat-card .stat-icon.green { background: #d1fae5; color: #10b981; }
    .stat-card .stat-icon.yellow { background: #fef3c7; color: #f59e0b; }
    .stat-card .stat-icon.red { background: #fee2e2; color: #ef4444; }

    .stat-card .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: #0a1e3c;
    }
    .stat-card .stat-label {
        font-size: 13px;
        color: #4b6a8b;
        font-weight: 500;
    }
    .stat-card .stat-percent {
        font-size: 13px;
        font-weight: 600;
        margin-top: 4px;
    }
    .stat-card .stat-percent.high { color: #10b981; }
    .stat-card .stat-percent.medium { color: #f59e0b; }
    .stat-card .stat-percent.low { color: #ef4444; }

    /* Filters */
    .filters {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        background: white;
        padding: 16px 24px;
        border-radius: 20px;
        border: 1px solid #eaf0f6;
        margin-bottom: 28px;
        align-items: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
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
        gap: 6px;
    }
    .filter-group label i { color: #2563eb; }
    .filter-group select {
        padding: 8px 16px;
        border: 1.5px solid #dce4ed;
        border-radius: 40px;
        font-size: 13px;
        background: #fafcff;
        outline: none;
        transition: 0.2s;
        min-width: 140px;
    }
    .filter-group select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08);
    }

    /* Main Card */
    .card {
        background: white;
        border-radius: 24px;
        padding: 24px 28px;
        border: 1px solid #eaf0f6;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #eef2f6;
    }
    .card-header .title {
        font-size: 16px;
        font-weight: 600;
        color: #0a1e3c;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .card-header .title i { color: #2563eb; }
    .card-header .badge {
        background: #eef2f6;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        color: #4b6a8b;
        font-weight: 500;
    }

    /* Plan Cards */
    .plan-coverage-card {
        background: #f8fafc;
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 16px;
        border: 1px solid #eaf0f6;
        transition: all 0.3s ease;
    }
    .plan-coverage-card:hover {
        border-color: #2563eb;
        box-shadow: 0 2px 12px rgba(37, 99, 235, 0.06);
    }
    .plan-coverage-card:last-child { margin-bottom: 0; }

    .plan-coverage-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 12px;
    }

    .plan-info {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .plan-info .plan-title {
        font-weight: 600;
        color: #0a1e3c;
        font-size: 15px;
    }
    .plan-info .plan-title i { color: #2563eb; margin-right: 6px; }
    .plan-info .class-badge {
        display: inline-block;
        background: #dbeafe;
        color: #1d4ed8;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    .plan-info .subject-tag {
        display: inline-block;
        background: #eef2f6;
        color: #4b6a8b;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 11px;
    }
    .plan-info .week-tag {
        display: inline-block;
        background: #fef3c7;
        color: #a16207;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 11px;
    }

    .plan-stats {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }
    .plan-stats .stat-item {
        text-align: center;
    }
    .plan-stats .stat-item .number {
        font-size: 18px;
        font-weight: 700;
        color: #0a1e3c;
    }
    .plan-stats .stat-item .label {
        font-size: 10px;
        color: #8a9bb5;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .plan-stats .stat-item .number.covered { color: #10b981; }
    .plan-stats .stat-item .number.pending { color: #f59e0b; }
    .plan-stats .stat-item .number.total { color: #2563eb; }

    /* Coverage Bar */
    .coverage-bar-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 180px;
    }
    .coverage-bar-wrapper .coverage-bar {
        flex: 1;
        height: 8px;
        background: #eef2f6;
        border-radius: 20px;
        overflow: hidden;
        min-width: 100px;
    }
    .coverage-bar-wrapper .coverage-fill {
        height: 100%;
        border-radius: 20px;
        transition: width 0.6s ease;
    }
    .coverage-bar-wrapper .coverage-fill.high { background: linear-gradient(90deg, #10b981, #34d399); }
    .coverage-bar-wrapper .coverage-fill.medium { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .coverage-bar-wrapper .coverage-fill.low { background: linear-gradient(90deg, #ef4444, #f87171); }
    .coverage-bar-wrapper .coverage-percent {
        font-weight: 700;
        font-size: 16px;
        min-width: 48px;
        text-align: right;
    }
    .coverage-bar-wrapper .coverage-percent.high { color: #10b981; }
    .coverage-bar-wrapper .coverage-percent.medium { color: #f59e0b; }
    .coverage-bar-wrapper .coverage-percent.low { color: #ef4444; }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: capitalize;
    }
    .status-badge.approved { background: #d1fae5; color: #0b6e4f; }
    .status-badge.pending { background: #dbeafe; color: #1d4ed8; }
    .status-badge.draft { background: #eef2f6; color: #4b6a8b; }
    .status-badge.covered { background: #d1fae5; color: #0b6e4f; }
    .status-badge.partial { background: #fef3c7; color: #a16207; }
    .status-badge.not-covered { background: #fee2e2; color: #dc2626; }

    /* ===== MODAL STYLES ===== */
    .coverage-modal .modal-content {
        max-width: 700px;
        max-height: 85vh;
        overflow-y: auto;
    }

    .coverage-modal .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 16px;
        border-bottom: 2px solid #eef2f6;
        margin-bottom: 20px;
    }
    .coverage-modal .modal-header .modal-title {
        font-size: 20px;
        font-weight: 700;
        color: #0a1e3c;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .coverage-modal .modal-header .modal-title i { color: #2563eb; }
    .coverage-modal .modal-header .close-btn {
        background: none;
        border: none;
        font-size: 28px;
        cursor: pointer;
        color: #8a9bb5;
        transition: 0.2s;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .coverage-modal .modal-header .close-btn:hover {
        background: #f1f5f9;
        color: #0a1e3c;
    }

    .coverage-modal .plan-summary {
        background: #f8fafc;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: center;
        border: 1px solid #eaf0f6;
    }
    .coverage-modal .plan-summary .summary-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        color: #4b6a8b;
    }
    .coverage-modal .plan-summary .summary-item i { color: #2563eb; }
    .coverage-modal .plan-summary .summary-item strong { color: #0a1e3c; }

    .coverage-modal .topics-list-modal {
        display: flex;
        flex-direction: column;
        gap: 8px;
        max-height: 400px;
        overflow-y: auto;
        padding-right: 4px;
    }
    .coverage-modal .topics-list-modal::-webkit-scrollbar {
        width: 6px;
    }
    .coverage-modal .topics-list-modal::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    .coverage-modal .topics-list-modal::-webkit-scrollbar-thumb {
        background: #2563eb;
        border-radius: 10px;
    }

    .coverage-modal .topic-item-modal {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        background: white;
        border-radius: 10px;
        border: 1.5px solid #eef2f6;
        transition: all 0.2s ease;
    }
    .coverage-modal .topic-item-modal:hover {
        border-color: #2563eb;
        background: #f8faff;
    }
    .coverage-modal .topic-item-modal.covered {
        border-color: #10b981;
        background: #f0fdf4;
    }
    .coverage-modal .topic-item-modal .topic-number {
        font-weight: 600;
        color: #2563eb;
        font-size: 13px;
        min-width: 32px;
    }
    .coverage-modal .topic-item-modal .topic-title {
        flex: 1;
        font-size: 14px;
        color: #0a1e3c;
    }
    .coverage-modal .topic-item-modal .topic-title.covered-text {
        color: #10b981;
        text-decoration: line-through;
    }
    .coverage-modal .topic-item-modal input[type="checkbox"] {
        width: 22px;
        height: 22px;
        accent-color: #2563eb;
        cursor: pointer;
        flex-shrink: 0;
    }
    .coverage-modal .topic-item-modal input[type="checkbox"]:checked {
        accent-color: #10b981;
    }
    .coverage-modal .topic-item-modal .topic-resources {
        display: flex;
        gap: 4px;
        font-size: 13px;
        color: #8a9bb5;
    }
    .coverage-modal .topic-item-modal .topic-resources i { font-size: 14px; }

    .coverage-modal .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid #eef2f6;
    }

    .coverage-modal .progress-summary {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 12px 16px;
        background: #f0f4ff;
        border-radius: 12px;
        margin-bottom: 16px;
        border: 1px solid #dbeafe;
    }
    .coverage-modal .progress-summary .progress-text {
        font-size: 14px;
        font-weight: 600;
        color: #0a1e3c;
    }
    .coverage-modal .progress-summary .progress-text span { color: #2563eb; }
    .coverage-modal .progress-summary .progress-bar-mini {
        flex: 1;
        height: 6px;
        background: #eef2f6;
        border-radius: 20px;
        overflow: hidden;
    }
    .coverage-modal .progress-summary .progress-bar-mini .fill {
        height: 100%;
        border-radius: 20px;
        background: linear-gradient(90deg, #2563eb, #60a5fa);
        transition: width 0.4s ease;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #8a9bb5;
    }
    .empty-state i { 
        font-size: 56px; 
        color: #dce4ed;
        margin-bottom: 16px;
        display: block;
    }
    .empty-state .title {
        font-size: 18px;
        font-weight: 600;
        color: #0a1e3c;
        margin-bottom: 4px;
    }
    .empty-state .sub {
        color: #4b6a8b;
        font-size: 14px;
    }

    /* Mark Button */
    .btn-mark {
        background: #2563eb;
        color: white;
        border: none;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-mark:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    .btn-mark i { font-size: 12px; }

    @media (max-width: 820px) {
        .page-content { padding: 16px; }
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .filters {
            flex-direction: column;
            align-items: stretch;
            border-radius: 16px;
        }
        .filter-group select { min-width: auto; }
        .card { padding: 16px; }
        .plan-coverage-header {
            flex-direction: column;
            align-items: stretch;
        }
        .plan-stats {
            justify-content: space-around;
        }
        .coverage-bar-wrapper { min-width: auto; }
        .coverage-modal .modal-content { padding: 20px; }
        .coverage-modal .plan-summary { flex-direction: column; align-items: stretch; }
    }

    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
        .page-title { font-size: 22px; }
        .plan-stats {
            flex-wrap: wrap;
            justify-content: flex-start;
            gap: 8px;
        }
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title-section">
            <div class="page-title">
                <i class="fas fa-check-double"></i> Coverage Tracker
            </div>
            <div class="page-subtitle">
                <i class="fas fa-info-circle"></i> Click the <strong>"Mark Topics"</strong> button to open a modal and track coverage per topic
            </div>
        </div>
        <button class="btn btn-primary" onclick="applyCoverageFilters()" style="display:flex;align-items:center;gap:8px;padding:10px 24px;">
            <i class="fas fa-sync-alt"></i> Refresh
        </button>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid" id="coverageStats">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-chart-line"></i></div>
            <div class="stat-value" id="totalPlans">0</div>
            <div class="stat-label">Total Plans</div>
            <div class="stat-percent high" id="overallCoverage">0% Overall</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-value" id="coveredCount">0</div>
            <div class="stat-label">Fully Covered</div>
            <div class="stat-percent high" id="coveredPercent">0%</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="fas fa-circle"></i></div>
            <div class="stat-value" id="partialCount">0</div>
            <div class="stat-label">Partially Covered</div>
            <div class="stat-percent medium" id="partialPercent">0%</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
            <div class="stat-value" id="notCoveredCount">0</div>
            <div class="stat-label">Not Covered</div>
            <div class="stat-percent low" id="notCoveredPercent">0%</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters">
        <div class="filter-group">
            <label><i class="fas fa-users"></i> Class</label>
            <select id="coverage-class">
                <option value="">All Classes</option>
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
            <select id="coverage-subject">
                <option value="">All Subjects</option>
                <option value="English">English</option>
                <option value="Mathematics">Mathematics</option>
                <option value="Science">Science</option>
                <option value="Art">Art</option>
                <option value="Social Studies">Social Studies</option>
                <option value="Computer Science">Computer Science</option>
            </select>
        </div>
        <div class="filter-group">
            <label><i class="fas fa-filter"></i> Status</label>
            <select id="coverage-status">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="draft">Draft</option>
            </select>
        </div>
        <button class="btn btn-primary" onclick="applyCoverageFilters()" style="padding:8px 20px;border-radius:40px;">
            <i class="fas fa-filter"></i> Apply
        </button>
        <button class="btn btn-secondary" onclick="clearCoverageFilters()" style="padding:8px 20px;border-radius:40px;">
            <i class="fas fa-undo"></i> Clear
        </button>
    </div>

    <!-- Main Card -->
    <div class="card">
        <div class="card-header">
            <div class="title">
                <i class="fas fa-list-ul"></i> Coverage Details
                <span class="badge" id="planCountBadge">0 plans</span>
            </div>
            <div style="display:flex;gap:12px;align-items:center;font-size:13px;color:#4b6a8b;flex-wrap:wrap;">
                <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#10b981;margin-right:4px;"></span> Covered</span>
                <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#f59e0b;margin-right:4px;"></span> Pending</span>
                <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#eef2f6;margin-right:4px;"></span> Not Started</span>
            </div>
        </div>

        <div id="coverageContainer">
            <!-- Plans will be rendered here -->
        </div>
    </div>
</div>

<!-- ===== COVERAGE MODAL ===== -->
<div class="modal coverage-modal" id="coverageModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-check-double"></i>
                <span id="modalPlanTitle">Mark Topics Coverage</span>
            </div>
            <button class="close-btn" onclick="closeCoverageModal()">×</button>
        </div>

        <!-- Plan Summary -->
        <div class="plan-summary" id="modalPlanSummary">  
            <div class="summary-item"><i class="fas fa-users"></i> Class: <strong id="modalClass">-</strong></div>  
            <div class="summary-item"><i class="fas fa-book"></i> Subject: <strong id="modalSubject">-</strong></div>  
            <div class="summary-item"><i class="fas fa-calendar-week"></i> Week: <strong  id="modalWeek">-</strong></div>  
            <div class="summary-item"><i class="fas fa-tag"></i> Status: <strong id="modalStatus">-</strong></div>  
        </div>  

        <!-- Progress Summary --> 
        <div class="progress-summary" id="modalProgressSummary">  
            <div class="progress-text">
                <i class="fas fa-chart-line"></i> Progress: <span id="modalProgressText">0/0</span> topics covered
            </div>
            <div class="progress-bar-mini">
                <div class="fill" id="modalProgressBar" style="width:0%;"></div>
            </div>
            <span style="font-weight:700;color:#2563eb;font-size:16px;" id="modalProgressPercent">0%</span>
        </div>

        <!-- Topics List -->
        <div class="topics-list-modal" id="modalTopicsList">
            <!-- Topics will be rendered here -->
        </div>

        <!-- Modal Actions -->
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeCoverageModal()">
                <i class="fas fa-times"></i> Close
            </button>
            <button class="btn btn-success" onclick="saveCoverageChanges()">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </div>
</div>

<script>
    let currentFilteredPlans = [];
    let currentModalPlanId = null;
    let modalTopics = [];
    let originalModalTopics = [];

    function renderCoverageTable(plans) {
        currentFilteredPlans = plans || [];
        const container = $('#coverageContainer');
        
        if (!plans || plans.length === 0) {
            container.html(`
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <div class="title">No plans found</div>
                    <div class="sub">Create lesson plans to track coverage</div>
                </div>
            `);
            updateStats([]);
            return;
        }

        // Update stats
        updateStats(plans);

        // Render each plan as a card
        let html = '';
        plans.forEach(p => {
            // Collect all topics from all dates
            let allTopics = [];
            if (p.dailyTopics) {
                Object.keys(p.dailyTopics).forEach(date => {
                    if (p.dailyTopics[date] && Array.isArray(p.dailyTopics[date])) {
                        p.dailyTopics[date].forEach(topic => {
                            allTopics.push({
                                ...topic,
                                date: date
                            });
                        });
                    }
                });
            }

            // If no topics found, use the topics array if it exists
            if (allTopics.length === 0 && p.topics && Array.isArray(p.topics)) {
                allTopics = p.topics.map(t => ({ ...t, date: p.startDate || 'N/A' }));
            }

            const totalTopics = allTopics.length;
            const coveredTopics = allTopics.filter(t => t.covered).length;
            const coveragePct = totalTopics > 0 ? Math.round((coveredTopics / totalTopics) * 100) : 0;
            const level = coveragePct >= 75 ? 'high' : (coveragePct >= 25 ? 'medium' : 'low');
            const statusClass = p.status || 'draft';

            html += `
                <div class="plan-coverage-card" data-plan-id="${p.id}">
                    <div class="plan-coverage-header">
                        <div class="plan-info">
                            <span class="plan-title"><i class="fas fa-file-alt"></i> ${p.title || 'Untitled Plan'}</span>
                            <span class="class-badge"><i class="fas fa-users"></i> ${p.class || 'N/A'}</span>
                            <span class="subject-tag"><i class="fas fa-book"></i> ${p.subject || 'N/A'}</span>
                            <span class="week-tag"><i class="fas fa-calendar-week"></i> ${p.week || 'Week 1'}</span>
                            <span class="status-badge ${statusClass}">${statusClass.replace(/-/g, ' ').replace(/\b\w/g, c => c.toUpperCase())}</span>
                        </div>
                        <div class="plan-stats">
                            <div class="stat-item">
                                <div class="number covered">${coveredTopics}</div>
                                <div class="label">Covered</div>
                            </div>
                            <div class="stat-item">
                                <div class="number pending">${totalTopics - coveredTopics}</div>
                                <div class="label">Pending</div>
                            </div>
                            <div class="stat-item">
                                <div class="number total">${totalTopics}</div>
                                <div class="label">Total</div>
                            </div>
                            <div class="coverage-bar-wrapper">
                                <div class="coverage-bar">
                                    <div class="coverage-fill ${level}" style="width:${coveragePct}%;"></div>
                                </div>
                                <span class="coverage-percent ${level}">${coveragePct}%</span>
                            </div>
                            <a class="btn-mark" href="/admin-lesson-planner/coverage/${p.id}/edit">
                                <i class="fas fa-check-square"></i> Mark Topics
                            </a>
                        </div>
                    </div>
                </div>
            `;
        });

        container.html(html);
    }

    // ===== MODAL FUNCTIONS =====
    function openCoverageModal(planId) {
        const plans = window.getPlans();
        const plan = plans.find(p => p.id === planId);
        
        if (!plan) {
            alert('Plan not found!');
            return;
        }

        currentModalPlanId = planId;
        
        // Collect all topics
        let allTopics = [];
        if (plan.dailyTopics) {
            Object.keys(plan.dailyTopics).forEach(date => {
                if (plan.dailyTopics[date] && Array.isArray(plan.dailyTopics[date])) {
                    plan.dailyTopics[date].forEach(topic => {
                        allTopics.push({
                            ...topic,
                            date: date
                        });
                    });
                }
            });
        }

        if (allTopics.length === 0 && plan.topics && Array.isArray(plan.topics)) {
            allTopics = plan.topics.map(t => ({ ...t, date: plan.startDate || 'N/A' }));
        }

        // Store topics for modal
        modalTopics = allTopics.map(t => ({ ...t }));
        originalModalTopics = allTopics.map(t => ({ ...t, covered: t.covered }));

        // Set plan info
        $('#modalPlanTitle').text(plan.title || 'Untitled Plan');
        $('#modalClass').text(plan.class || 'N/A');
        $('#modalSubject').text(plan.subject || 'N/A');
        $('#modalWeek').text(plan.week || 'Week 1');
        $('#modalStatus').text(plan.status || 'draft');

        // Render topics in modal
        renderModalTopics();

        // Update progress
        updateModalProgress();

        // Show modal
        $('#coverageModal').addClass('active');
    }

    function renderModalTopics() {
        const container = $('#modalTopicsList');
        
        if (!modalTopics || modalTopics.length === 0) {
            container.html(`
                <div style="text-align:center;padding:30px;color:#8a9bb5;">
                    <i class="fas fa-info-circle"></i> No topics found for this plan
                </div>
            `);
            return;
        }

        container.html(modalTopics.map((topic, index) => {
            const isCovered = topic.covered || false;
            const hasVideo = topic.video ? '<i class="fab fa-youtube" style="color:#ff0000;"></i>' : '';
            const hasFiles = topic.files && topic.files.length > 0 ? '<i class="fas fa-file"></i>' : '';
            
            return `
                <div class="topic-item-modal ${isCovered ? 'covered' : ''}">
                    <span class="topic-number">#${topic.number || index + 1}</span>
                    <span class="topic-title ${isCovered ? 'covered-text' : ''}">
                        ${topic.title || 'Untitled Topic'}
                        ${hasVideo} ${hasFiles}
                    </span>
                    <span class="topic-resources">
                        ${topic.resources && topic.resources.length > 0 ? topic.resources.map(r => `<span style="background:#eef2f6;padding:1px 8px;border-radius:10px;font-size:11px;">${r}</span>`).join('') : ''}
                    </span>
                    <input type="checkbox" ${isCovered ? 'checked' : ''} 
                           onchange="toggleModalTopic(${index}, this.checked)" />
                </div>
            `;
        }).join(''));
    }

    function toggleModalTopic(index, checked) {
        if (modalTopics[index]) {
            modalTopics[index].covered = checked;
            updateModalProgress();
            renderModalTopics();
        }
    }

    function updateModalProgress() {
        const total = modalTopics.length;
        const covered = modalTopics.filter(t => t.covered).length;
        const percent = total > 0 ? Math.round((covered / total) * 100) : 0;

        $('#modalProgressText').text(`${covered}/${total}`);
        $('#modalProgressPercent').text(percent + '%');
        $('#modalProgressBar').css('width', percent + '%');
    }

    function saveCoverageChanges() {
        if (!currentModalPlanId) return;

        const plans = window.getPlans();
        const planIndex = plans.findIndex(p => p.id === currentModalPlanId);
        
        if (planIndex === -1) {
            alert('Plan not found!');
            return;
        }

        const plan = plans[planIndex];

        // Update dailyTopics with modal changes
        if (plan.dailyTopics) {
            const dates = Object.keys(plan.dailyTopics);
            dates.forEach(date => {
                if (plan.dailyTopics[date] && Array.isArray(plan.dailyTopics[date])) {
                    plan.dailyTopics[date].forEach((topic, idx) => {
                        // Find matching topic in modalTopics
                        const modalTopic = modalTopics.find(t => 
                            t.title === topic.title && t.number === topic.number
                        );
                        if (modalTopic) {
                            topic.covered = modalTopic.covered;
                        }
                    });
                }
            });
        }

        // Also update topics array if it exists
        if (plan.topics && Array.isArray(plan.topics)) {
            plan.topics.forEach((topic, idx) => {
                const modalTopic = modalTopics.find(t => 
                    t.title === topic.title && t.number === topic.number
                );
                if (modalTopic) {
                    topic.covered = modalTopic.covered;
                }
            });
        }

        // Save updated plans
        savePlans(plans);
        
        // Close modal
        closeCoverageModal();

        // Refresh the coverage view
        applyCoverageFilters();

        // Show success message
        showToast('Coverage updated successfully!', 'success');
    }

    function closeCoverageModal() {
        $('#coverageModal').removeClass('active');
        currentModalPlanId = null;
        modalTopics = [];
        originalModalTopics = [];
    }

    // ===== TOAST NOTIFICATION =====
    function showToast(message, type = 'success') {
        const colors = {
            success: '#10b981',
            error: '#ef4444',
            info: '#2563eb',
            warning: '#f59e0b'
        };

        const toast = $(`
            <div style="
                position: fixed;
                bottom: 30px;
                right: 30px;
                background: white;
                padding: 16px 24px;
                border-radius: 16px;
                box-shadow: 0 8px 30px rgba(0,0,0,0.15);
                border-left: 4px solid ${colors[type] || colors.success};
                z-index: 9999;
                display: flex;
                align-items: center;
                gap: 12px;
                font-size: 14px;
                font-weight: 500;
                color: #0a1e3c;
                animation: slideIn 0.3s ease;
                max-width: 400px;
            ">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-times-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle'}" 
                   style="color: ${colors[type] || colors.success}; font-size: 20px;"></i>
                <span>${message}</span>
            </div>
        `);

        $('body').append(toast);

        setTimeout(() => {
            toast.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }

    // ===== STATS FUNCTIONS =====
    function updateStats(plans) {
        const total = plans.length;
        
        let totalTopics = 0;
        let coveredTopics = 0;
        
        plans.forEach(p => {
            let topics = [];
            if (p.dailyTopics) {
                Object.keys(p.dailyTopics).forEach(date => {
                    if (p.dailyTopics[date] && Array.isArray(p.dailyTopics[date])) {
                        topics = topics.concat(p.dailyTopics[date]);
                    }
                });
            }
            if (topics.length === 0 && p.topics && Array.isArray(p.topics)) {
                topics = p.topics;
            }
            
            totalTopics += topics.length;
            coveredTopics += topics.filter(t => t.covered).length;
        });
        
        const avgCoverage = totalTopics > 0 ? Math.round((coveredTopics / totalTopics) * 100) : 0;
        
        const fullyCovered = plans.filter(p => {
            let topics = [];
            if (p.dailyTopics) {
                Object.keys(p.dailyTopics).forEach(date => {
                    if (p.dailyTopics[date] && Array.isArray(p.dailyTopics[date])) {
                        topics = topics.concat(p.dailyTopics[date]);
                    }
                });
            }
            if (topics.length === 0 && p.topics && Array.isArray(p.topics)) {
                topics = p.topics;
            }
            const covered = topics.filter(t => t.covered).length;
            return topics.length > 0 && covered === topics.length;
        }).length;
        
        const partiallyCovered = plans.filter(p => {
            let topics = [];
            if (p.dailyTopics) {
                Object.keys(p.dailyTopics).forEach(date => {
                    if (p.dailyTopics[date] && Array.isArray(p.dailyTopics[date])) {
                        topics = topics.concat(p.dailyTopics[date]);
                    }
                });
            }
            if (topics.length === 0 && p.topics && Array.isArray(p.topics)) {
                topics = p.topics;
            }
            const covered = topics.filter(t => t.covered).length;
            return topics.length > 0 && covered > 0 && covered < topics.length;
        }).length;
        
        const notCovered = plans.filter(p => {
            let topics = [];
            if (p.dailyTopics) {
                Object.keys(p.dailyTopics).forEach(date => {
                    if (p.dailyTopics[date] && Array.isArray(p.dailyTopics[date])) {
                        topics = topics.concat(p.dailyTopics[date]);
                    }
                });
            }
            if (topics.length === 0 && p.topics && Array.isArray(p.topics)) {
                topics = p.topics;
            }
            const covered = topics.filter(t => t.covered).length;
            return topics.length === 0 || covered === 0;
        }).length;

        $('#totalPlans').text(total);
        $('#coveredCount').text(fullyCovered);
        $('#partialCount').text(partiallyCovered);
        $('#notCoveredCount').text(notCovered);
        $('#overallCoverage').text(avgCoverage + '% Overall');
        $('#coveredPercent').text(total > 0 ? Math.round((fullyCovered / total) * 100) + '%' : '0%');
        $('#partialPercent').text(total > 0 ? Math.round((partiallyCovered / total) * 100) + '%' : '0%');
        $('#notCoveredPercent').text(total > 0 ? Math.round((notCovered / total) * 100) + '%' : '0%');
        $('#planCountBadge').text(total + ' plan' + (total > 1 ? 's' : ''));
    }

    // ===== FILTER FUNCTIONS =====
    function applyCoverageFilters() {
        const cls = $('#coverage-class').val();
        const subject = $('#coverage-subject').val();
        const status = $('#coverage-status').val();

        let filtered = window.getPlans();
        if (cls) filtered = filtered.filter(p => p.class === cls);
        if (subject) filtered = filtered.filter(p => p.subject === subject);
        if (status) filtered = filtered.filter(p => p.status === status);

        renderCoverageTable(filtered);
    }

    function clearCoverageFilters() {
        $('#coverage-class').val('');
        $('#coverage-subject').val('');
        $('#coverage-status').val('');
        renderCoverageTable(window.getPlans());
    }

    // ===== KEYBOARD SHORTCUT =====
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCoverageModal();
        }
    });

    // ===== DATA LOADING =====
    async function loadCoverageFromServer() {
        try {
            const response = await fetch('{{ route('lesson-planner.coverage.data') }}');
            const data = await response.json();
            window.plansData = Array.isArray(data) ? data : [];
            renderCoverageTable(window.plansData);
        } catch (error) {
            console.error('Failed to load coverage data:', error);
            window.plansData = [];
            renderCoverageTable([]);
        }
    }

    // ===== INIT =====
    function initCoverage() {
        if (typeof $ === 'undefined') {
            setTimeout(initCoverage, 50);
            return;
        }
        $(document).ready(function() {
            loadCoverageFromServer();
        });
    }

    initCoverage();

    window.refreshLessonPlannerViews = function() {
        loadCoverageFromServer();
    };
</script>
@endsection
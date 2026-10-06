@extends('instituteAdmin.student.Lesson-planner.index')

@section('student-content')
<style>
    .page-content {
        padding: 28px 32px;
        background: #f0f4f9;
    }

    .welcome-section {
        margin-bottom: 28px;
    }
    .welcome-section h1 {
        font-size: 28px;
        font-weight: 800;
        color: #0a1e3c;
        margin-bottom: 4px;
    }
    .welcome-section h1 i {
        color: #10b981;
        margin-right: 12px;
    }
    .welcome-section p {
        color: #4b6a8b;
        font-size: 15px;
    }
    .welcome-section .highlight {
        color: #10b981;
        font-weight: 600;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: white;
        padding: 20px 22px;
        border-radius: 24px;
        border: 1px solid #eaf0f6;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        cursor: default;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #34d399);
        border-radius: 24px 24px 0 0;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.1);
    }
    .stat-label {
        font-size: 13px;
        font-weight: 500;
        color: #4b6a8b;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
    }
    .stat-label i { color: #10b981; font-size: 16px; }
    .stat-value {
        font-size: 30px;
        font-weight: 800;
        color: #0a1e3c;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    .card {
        background: white;
        border-radius: 24px;
        padding: 22px 24px 18px;
        border: 1px solid #eaf0f6;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.01);
    }
    .card-title {
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 16px;
        color: #0a1e3c;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-title i { color: #10b981; margin-right: 8px; }
    .card-title .badge {
        background: #eef2f6;
        padding: 3px 14px;
        border-radius: 20px;
        font-size: 12px;
        color: #4b6a8b;
        font-weight: 400;
    }

    .plan-list-item {
        padding: 12px 14px;
        border-bottom: 1px solid #eef2f6;
        transition: all 0.2s;
        cursor: default;
    }
    .plan-list-item:last-child { border-bottom: none; }
    .plan-list-item:hover {
        background: #f8fafc;
        border-radius: 8px;
    }
    .plan-list-item .plan-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .plan-list-item .plan-title {
        font-weight: 600;
        color: #0a1e3c;
        font-size: 14px;
    }
    .plan-list-item .plan-title i { color: #10b981; margin-right: 6px; }
    .plan-list-item .plan-meta {
        font-size: 12px;
        color: #4b6a8b;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 3px;
    }
    .plan-list-item .plan-meta span {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .plan-list-item .plan-meta i { color: #10b981; }

    .status-badge {
        display: inline-block;
        padding: 3px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: capitalize;
    }
    .status-badge.approved { background: #d1fae5; color: #0b6e4f; }
    .status-badge.pending { background: #dbeafe; color: #1d4ed8; }
    .status-badge.draft { background: #eef2f6; color: #4b6a8b; }

    .btn-view {
        background: #10b981;
        color: white;
        border: none;
        padding: 5px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .btn-view:hover {
        background: #059669;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .coverage-ring {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: conic-gradient(#10b981 0% 29%, #e6ecf3 29% 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.15);
    }
    .coverage-ring-inner {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 800;
        color: #10b981;
    }
    .coverage-ring-inner .label {
        font-size: 10px;
        font-weight: 400;
        color: #4b6a8b;
        text-transform: uppercase;
    }
    .coverage-legend {
        display: flex;
        justify-content: center;
        gap: 14px;
        font-size: 13px;
        color: #4b6a8b;
        flex-wrap: wrap;
    }
    .coverage-legend span {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .coverage-legend .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }
    .dot.covered { background: #10b981; }
    .dot.partial { background: #f59e0b; }
    .dot.notcovered { background: #ef4444; }

    .empty-state {
        text-align: center;
        padding: 30px 20px;
        color: #8a9bb5;
    }
    .empty-state i {
        font-size: 40px;
        color: #dce4ed;
        margin-bottom: 10px;
        display: block;
    }
    .empty-state .title {
        font-size: 16px;
        font-weight: 600;
        color: #0a1e3c;
    }

    /* Refresh button */
    .refresh-btn {
        background: none;
        border: none;
        color: #10b981;
        cursor: pointer;
        font-size: 14px;
        padding: 4px 8px;
        border-radius: 8px;
        transition: 0.2s;
    }
    .refresh-btn:hover {
        background: #eef2f6;
    }
    .refresh-btn i { margin-right: 4px; }

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

    @media (max-width: 820px) {
        .page-content { padding: 16px; }
        .content-grid { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .filters { padding: 14px 18px; gap: 12px 16px; }
    }

    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
        .welcome-section h1 { font-size: 22px; }
        .filters { flex-direction: column; align-items: stretch; }
        .filter-group select, .btn { width: 100%; }
    }
</style>

<div class="page-content">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <h1><i class="fas fa-graduation-cap"></i> My Lesson Plans</h1>
        <p>
            <i class="fas fa-info-circle" style="color:#10b981;"></i>
            View all lesson plans for your enrolled courses.
            <span class="highlight" id="totalPlansDisplay">0</span> plans available
        </p>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid" id="statsContainer">
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-book"></i> Total Plans</div>
            <div class="stat-value" id="totalPlans">0</div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-check-circle" style="color:#10b981;"></i> Approved</div>
            <div class="stat-value" id="approvedPlans">0</div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-clock" style="color:#f59e0b;"></i> Pending</div>
            <div class="stat-value" id="pendingPlans">0</div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-chart-line"></i> Coverage</div>
            <div class="stat-value" id="coveragePercent">0%</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters">
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
        <div class="filter-group">
            <label><i class="fas fa-calendar-alt"></i> Month</label>
            <select id="filter-month">
                <option value="">All</option>
                <option value="January">January</option>
                <option value="February">February</option>
                <option value="March">March</option>
                <option value="April">April</option>
                <option value="May">May</option>
                <option value="June">June</option>
                <option value="July">July</option>
                <option value="August">August</option>
                <option value="September">September</option>
                <option value="October">October</option>
                <option value="November">November</option>
                <option value="December">December</option>
            </select>
        </div>
        <button class="btn btn-primary" onclick="applyFilters()"><i class="fas fa-filter"></i> Apply</button>
        <button class="btn btn-secondary" onclick="clearFilters()"><i class="fas fa-undo"></i> Clear</button>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <div class="card">
            <div class="card-title">
                <span><i class="fas fa-list-ul"></i> Recent Plans</span>
                <span>
                    <span class="badge" id="planCount">0 plans</span>
                    <button class="refresh-btn" onclick="loadStudentPlans()" title="Refresh">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </span>
            </div>
            <div id="recentPlansContainer">
                <!-- Rendered by JS -->
                <div style="text-align:center; padding:20px; color:#8a9bb5;">
                    <i class="fas fa-spinner fa-spin"></i> Loading...
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title">
                <span><i class="fas fa-chart-pie"></i> Overall Coverage</span>
            </div>
            <div style="text-align:center; padding:10px 0;">
                <div class="coverage-ring" id="coverageRing">
                    <div class="coverage-ring-inner">
                        <span id="coveragePercentage">-</span>
                        <span class="label">Coverage</span>
                    </div>
                </div>
                <div class="coverage-legend">
                    <span><span class="dot covered"></span> <span id="coveredCount">0</span> covered</span>
                    <span><span class="dot partial"></span> <span id="partialCount">0</span> partial</span>
                    <span><span class="dot notcovered"></span> <span id="notCoveredCount">0</span> not covered</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let plansData = [];
    let filteredPlansData = [];
    let selectedSubject = '';
    let selectedMonth = '';

    function applyFilters() {
        selectedSubject = document.getElementById('filter-subject').value;
        selectedMonth = document.getElementById('filter-month').value;
        renderDashboard();
    }

    function clearFilters() {
        document.getElementById('filter-subject').value = '';
        document.getElementById('filter-month').value = '';
        selectedSubject = '';
        selectedMonth = '';
        renderDashboard();
    }

    async function loadStudentPlans() {
        const container = document.getElementById('recentPlansContainer');
        container.innerHTML = `
            <div style="text-align:center; padding:20px; color:#8a9bb5;">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
        `;

        try {
            const response = await fetch('{{ route("student.lesson-planner.data") }}');
            const data = await response.json();
            plansData = Array.isArray(data) ? data : [];
            renderDashboard();
        } catch (error) {
            console.error('Failed to load plans:', error);
            plansData = [];
            renderDashboard();
        }
    }

    function renderDashboard() {
        // Apply filters
        filteredPlansData = plansData.filter(p => {
            if (selectedSubject && p.subject !== selectedSubject) return false;
            if (selectedMonth && p.month !== selectedMonth) return false;
            return true;
        });

        const plans = filteredPlansData;
        
        const total = plans.length;
        const active = plans.filter(p => p.status === 'active').length;
        const draft = plans.filter(p => p.status === 'draft').length;
        
        let totalTopics = 0;
        let coveredTopics = 0;
        let partialTopics = 0;
        
        plans.forEach(p => {
            let topics = [];
            if (p.dailyTopics) {
                Object.values(p.dailyTopics).forEach(dayTopics => {
                    if (Array.isArray(dayTopics)) topics = topics.concat(dayTopics);
                });
            }
            if (topics.length === 0 && p.topics && Array.isArray(p.topics)) topics = p.topics;
            
            topics.forEach(t => {
                totalTopics++;
                const status = t.coverage_status || (t.covered ? 'covered' : 'not_covered');
                if (status === 'covered') coveredTopics++;
                else if (status === 'partial') partialTopics++;
            });
        });
        
        const notCovered = totalTopics - coveredTopics - partialTopics;
        const percentage = totalTopics > 0 ? Math.round(((coveredTopics + (partialTopics * 0.5)) / totalTopics) * 100) : 0;
        
        document.getElementById('totalPlans').textContent = total;
        document.getElementById('approvedPlans').textContent = active;
        document.getElementById('pendingPlans').textContent = draft;
        document.getElementById('coveragePercent').textContent = percentage + '%';
        document.getElementById('totalPlansDisplay').textContent = plansData.length;
        document.getElementById('planCount').textContent = total + ' plans';
        
        document.getElementById('coveragePercentage').textContent = percentage + '%';
        document.getElementById('coveredCount').textContent = coveredTopics;
        document.getElementById('partialCount').textContent = partialTopics;
        document.getElementById('notCoveredCount').textContent = notCovered;
        
        const ring = document.getElementById('coverageRing');
        if (ring) {
            ring.style.background = `conic-gradient(#10b981 0% ${percentage}%, #e6ecf3 ${percentage}% 100%)`;
        }
        
        const container = document.getElementById('recentPlansContainer');
        const recent = plans.slice(0, 5);
        
        if (recent.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <div class="title">No plans available</div>
                    <div style="font-size:13px; margin-top:4px; color:#4b6a8b;">Check back later for new lesson plans</div>
                </div>
            `;
            return;
        }
        
        container.innerHTML = recent.map(p => {
            const statusClass = p.status || 'draft';
            const statusLabel = statusClass.charAt(0).toUpperCase() + statusClass.slice(1);
            
            let topics = [];
            if (p.dailyTopics) {
                Object.values(p.dailyTopics).forEach(dayTopics => {
                    if (Array.isArray(dayTopics)) topics = topics.concat(dayTopics);
                });
            }
            if (topics.length === 0 && p.topics && Array.isArray(p.topics)) topics = p.topics;
            
            return `
                <div class="plan-list-item">
                    <div class="plan-header">
                        <div>
                            <div class="plan-title"><i class="fas fa-file-alt"></i> ${p.title || 'Untitled Plan'}</div>
                            <div class="plan-meta">
                                <span><i class="fas fa-users"></i> ${p.class || 'N/A'}</span>
                                <span><i class="fas fa-book"></i> ${p.subject || 'N/A'}</span>
                                <span><i class="fas fa-calendar-week"></i> ${p.week || 'Week 1'}</span>
                                <span><i class="fas fa-list"></i> ${topics.length} topics</span>
                            </div>
                        </div>
                        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                            <a href="{{ route('student.lesson-planner.plan-detail', '') }}/${p.id}" class="btn-view">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    document.addEventListener('DOMContentLoaded', loadStudentPlans);
    window.refreshStudentPlans = loadStudentPlans;
</script>
@endsection
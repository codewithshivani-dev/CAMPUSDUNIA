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

    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    .card {
        background: white;
        border-radius: 28px;
        padding: 24px 26px 20px;
        border: 1px solid #eaf0f6;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.01);
    }
    .card-title {
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 18px;
        color: #0a1e3c;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-title i { color: #2563eb; margin-right: 8px; }

    .coverage-bar {
        width: 100%;
        height: 8px;
        background: #e6ecf3;
        border-radius: 20px;
        overflow: hidden;
    }
    .coverage-fill {
        height: 100%;
        border-radius: 20px;
        transition: width 0.8s ease;
    }
    .coverage-fill.high { background: linear-gradient(90deg, #10b981, #34d399); }
    .coverage-fill.medium { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .coverage-fill.low { background: linear-gradient(90deg, #ef4444, #f87171); }

    .stat-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 4px;
        font-size: 14px;
    }
    .stat-row .value {
        font-weight: 700;
    }

    .big-number {
        font-size: 48px;
        font-weight: 800;
        color: #0a1e3c;
        line-height: 1;
    }
    .big-number .percent { color: #2563eb; }
    .big-label {
        font-size: 12px;
        color: #4b6a8b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .report-item {
        margin-bottom: 16px;
    }
    .report-item:last-child {
        margin-bottom: 0;
    }
    .report-item .row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 4px;
    }
    .report-item .row .name {
        color: #4b6a8b;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .report-item .row .pct {
        font-weight: 700;
    }

    .print-btn {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        border: none;
        padding: 8px 24px;
        border-radius: 60px;
        font-weight: 500;
        cursor: pointer;
        transition: 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .print-btn:hover {
        background: linear-gradient(135deg, #1d4ed8, #1a3fb5);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
    }

    @media print {
        body * { visibility: hidden; }
        .content-wrapper, .content-wrapper * { visibility: visible; }
        .content-wrapper {
            position: absolute;
            left: 0;
            top: 0;
            margin: 0;
            padding: 20px;
        }
        .filters, .btn, .print-btn, .top-bar { display: none !important; }
        .page-content { padding: 0 !important; }
    }

    @media (max-width: 820px) {
        .page-content { padding: 16px; }
        .filters {
            flex-direction: column;
            align-items: stretch;
            border-radius: 32px;
        }
        .content-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <p class="page-subtitle">
        <i class="fas fa-file-alt"></i> View detailed coverage reports
    </p>

    <div class="filters">
        <div class="filter-group">
            <label><i class="fas fa-users"></i> Class</label>
            <select id="report-class">
                <option value="">All</option>
                @foreach ($reportClasses as $class)
                    <option value="{{ $class }}">{{ $class }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label><i class="fas fa-book"></i> Subject</label>
            <select id="report-subject">
                <option value="">All</option>
                @foreach ($reportSubjects as $subject)
                    <option value="{{ $subject }}">{{ $subject }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label><i class="fas fa-calendar-alt"></i> Month</label>
            <select id="report-month">
                <option value="">All</option>
                @foreach ($reportMonths as $month)
                    <option value="{{ $month }}">{{ $month }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary" onclick="applyReportFilters()"><i class="fas fa-filter"></i> Apply</button>
        <button class="print-btn" onclick="downloadReport('pdf')"><i class="fas fa-file-pdf"></i> Download PDF</button>
        <button class="btn btn-secondary" onclick="downloadReport('csv')"><i class="fas fa-file-csv"></i> Download CSV</button>
    </div>

    <div class="content-grid">
        <div class="card">
            <div style="margin-bottom:20px;">
                <div class="big-label"><i class="fas fa-chart-line"></i> Overall completion</div>
                <div class="big-number"><span id="report-overall">0</span><span class="percent">%</span></div>
            </div>

            <div class="report-item">
                <div class="row">
                    <span class="name"><i class="fas fa-check-circle" style="color:#10b981;"></i> Covered</span>
                    <span class="pct" style="color:#10b981;" id="report-covered">0</span>
                </div>
                <div class="coverage-bar">
                    <div class="coverage-fill high" id="covered-bar" style="width:0%;"></div>
                </div>
            </div>

            <div class="report-item">
                <div class="row">
                    <span class="name"><i class="fas fa-circle" style="color:#f59e0b;"></i> Partial</span>
                    <span class="pct" style="color:#f59e0b;" id="report-partial">0</span>
                </div>
                <div class="coverage-bar">
                    <div class="coverage-fill medium" id="partial-bar" style="width:0%;"></div>
                </div>
            </div>

            <div class="report-item">
                <div class="row">
                    <span class="name"><i class="fas fa-times-circle" style="color:#ef4444;"></i> Not Covered</span>
                    <span class="pct" style="color:#ef4444;" id="report-notcovered">0</span>
                </div>
                <div class="coverage-bar">
                    <div class="coverage-fill low" id="notcovered-bar" style="width:0%;"></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title"><i class="fas fa-users"></i> By Class</div>
            <div id="class-coverage-container"></div>

            <div class="card-title" style="margin-top:20px;"><i class="fas fa-book"></i> By Subject</div>
            <div id="subject-coverage-container"></div>
        </div>
    </div>
</div>

<script>
    const reportData = @json($reportData);

    function renderReports(plans) {
        const total = plans.reduce((sum, plan) => sum + plan.total_topics, 0);
        const covered = plans.reduce((sum, plan) => sum + plan.covered_topics, 0);
        const partial = plans.reduce((sum, plan) => sum + plan.partial_topics, 0);
        const notCovered = plans.reduce((sum, plan) => sum + plan.not_covered_topics, 0);
        const avg = total > 0 ? Math.round(((covered + (partial * 0.5)) / total) * 100) : 0;

        $('#report-overall').text(avg);
        $('#report-covered').text(Math.round((covered / (total || 1)) * 100) + '%');
        $('#report-partial').text(Math.round((partial / (total || 1)) * 100) + '%');
        $('#report-notcovered').text(Math.round((notCovered / (total || 1)) * 100) + '%');

        // Update bars (as percentage of total)
        const totalTopics = total || 1;
        $('#covered-bar').css('width', ((covered / totalTopics) * 100) + '%');
        $('#partial-bar').css('width', ((partial / totalTopics) * 100) + '%');
        $('#notcovered-bar').css('width', ((notCovered / totalTopics) * 100) + '%');

        // Class coverage
        const classData = {};
        plans.forEach(p => {
            if (!classData[p.class]) classData[p.class] = [];
            classData[p.class].push(p);
        });

        const classContainer = $('#class-coverage-container');
        const classKeys = Object.keys(classData);
        if (classKeys.length === 0) {
            classContainer.html('<div style="color:#8a9bb5;text-align:center;padding:10px;"><i class="fas fa-inbox"></i> No data</div>');
        } else {
            classContainer.html(classKeys.map(cls => {
                const classPlans = classData[cls];
                const classTopics = classPlans.reduce((sum, plan) => sum + plan.total_topics, 0);
                const avgClass = classTopics > 0
                    ? Math.round((classPlans.reduce((sum, plan) => sum + plan.covered_topics + (plan.partial_topics * 0.5), 0) / classTopics) * 100)
                    : 0;
                const level = avgClass >= 75 ? 'high' : (avgClass >= 25 ? 'medium' : 'low');
                const icon = avgClass >= 75 ? 'fa-check-circle' : (avgClass >= 25 ? 'fa-circle' : 'fa-times-circle');
                const iconColor = avgClass >= 75 ? '#10b981' : (avgClass >= 25 ? '#f59e0b' : '#ef4444');
                return `
                    <div class="report-item">
                        <div class="row">
                            <span class="name"><i class="fas ${icon}" style="color:${iconColor};"></i> ${cls}</span>
                            <span class="pct">${avgClass}%</span>
                        </div>
                        <div class="coverage-bar">
                            <div class="coverage-fill ${level}" style="width:${avgClass}%;"></div>
                        </div>
                    </div>
                `;
            }).join(''));
        }

        // Subject coverage
        const subjectData = {};
        plans.forEach(p => {
            if (!subjectData[p.subject]) subjectData[p.subject] = [];
            subjectData[p.subject].push(p);
        });

        const subjectContainer = $('#subject-coverage-container');
        const subjectKeys = Object.keys(subjectData);
        if (subjectKeys.length === 0) {
            subjectContainer.html('<div style="color:#8a9bb5;text-align:center;padding:10px;"><i class="fas fa-inbox"></i> No data</div>');
        } else {
            subjectContainer.html(subjectKeys.map(sub => {
                const subjectPlans = subjectData[sub];
                const subjectTopics = subjectPlans.reduce((sum, plan) => sum + plan.total_topics, 0);
                const avgSub = subjectTopics > 0
                    ? Math.round((subjectPlans.reduce((sum, plan) => sum + plan.covered_topics + (plan.partial_topics * 0.5), 0) / subjectTopics) * 100)
                    : 0;
                const level = avgSub >= 75 ? 'high' : (avgSub >= 25 ? 'medium' : 'low');
                const icon = avgSub >= 75 ? 'fa-check-circle' : (avgSub >= 25 ? 'fa-circle' : 'fa-times-circle');
                const iconColor = avgSub >= 75 ? '#10b981' : (avgSub >= 25 ? '#f59e0b' : '#ef4444');
                return `
                    <div class="report-item">
                        <div class="row">
                            <span class="name"><i class="fas ${icon}" style="color:${iconColor};"></i> ${sub}</span>
                            <span class="pct">${avgSub}%</span>
                        </div>
                        <div class="coverage-bar">
                            <div class="coverage-fill ${level}" style="width:${avgSub}%;"></div>
                        </div>
                    </div>
                `;
            }).join(''));
        }
    }

    function applyReportFilters() {
        const cls = $('#report-class').val();
        const subject = $('#report-subject').val();

        let filtered = reportData;
        if (cls) filtered = filtered.filter(p => p.class === cls);
        if (subject) filtered = filtered.filter(p => p.subject === subject);
        const month = $('#report-month').val();
        if (month) filtered = filtered.filter(p => p.month === month);

        renderReports(filtered);
    }

    function downloadReport(format) {
        const params = new URLSearchParams();
        const cls = $('#report-class').val();
        const subject = $('#report-subject').val();
        const month = $('#report-month').val();
        if (cls) params.set('class', cls);
        if (subject) params.set('subject', subject);
        if (month) params.set('month', month);
        window.location.href = `{{ url('/admin-lesson-planner/reports') }}/${format}?${params.toString()}`;
    }

    function initReports() {
        if (typeof $ === 'undefined') {
            setTimeout(initReports, 50);
            return;
        }
        $(document).ready(function() {
            renderReports(reportData);
        });
    }

    initReports();

    window.refreshLessonPlannerViews = function() {
        if (typeof renderReports === 'function') {
            renderReports(reportData);
        }
    };
</script>
@endsection
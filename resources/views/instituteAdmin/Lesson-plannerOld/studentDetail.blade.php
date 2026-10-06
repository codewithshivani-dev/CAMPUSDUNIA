@extends('instituteAdmin.Lesson-planner.index')

@section('styles')
<style>
    .student-detail-page {
        padding: 28px 32px;
        background: #f0f4f9;
        min-height: 100vh;
    }

    .detail-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }
    .back-link {
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: white;
        border-radius: 12px;
        border: 1px solid #e6ecf3;
        transition: all 0.2s;
    }
    .back-link:hover {
        background: #f8fafc;
        border-color: #2563eb;
    }
    .back-link i { font-size: 14px; }

    /* Student Header */
    .detail-heading {
        background: white;
        border: 1px solid #e6ecf3;
        border-radius: 24px;
        padding: 24px 28px;
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .student-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2563eb, #60a5fa);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 24px;
        flex-shrink: 0;
    }
    .detail-heading .info h1 {
        color: #0a1e3c;
        font-size: 22px;
        margin: 0 0 4px;
    }
    .detail-heading .info .meta {
        color: #4b6a8b;
        font-size: 13px;
        margin: 0;
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }
    .detail-heading .info .meta i {
        color: #2563eb;
        margin-right: 4px;
    }
    .detail-heading .context {
        margin-left: auto;
        color: #4b6a8b;
        font-size: 13px;
        text-align: right;
        background: #f8fafc;
        padding: 8px 16px;
        border-radius: 12px;
    }
    .detail-heading .context strong {
        display: block;
        color: #0a1e3c;
        font-size: 15px;
        margin-top: 2px;
    }

    /* Stats Cards */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }
    .stat-card {
        background: white;
        padding: 14px 18px;
        border-radius: 16px;
        border: 1px solid #e6ecf3;
        text-align: center;
    }
    .stat-card .number {
        font-size: 28px;
        font-weight: 700;
        display: block;
    }
    .stat-card .label {
        font-size: 12px;
        color: #8a9bb5;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .stat-card .number.green { color: #10b981; }
    .stat-card .number.red { color: #ef4444; }
    .stat-card .number.blue { color: #2563eb; }

    /* Detail Card */
    .detail-card {
        background: white;
        border: 1px solid #e6ecf3;
        border-radius: 24px;
        overflow: hidden;
    }

    .detail-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 16px 24px;
        border-bottom: 1px solid #e6ecf3;
        flex-wrap: wrap;
        background: #fafcfe;
    }
    .detail-controls label {
        color: #4b6a8b;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .detail-controls select {
        padding: 8px 14px;
        border: 2px solid #e6ecf3;
        border-radius: 10px;
        color: #0a1e3c;
        background: white;
        font-weight: 500;
        cursor: pointer;
    }
    .detail-controls select:focus {
        outline: none;
        border-color: #2563eb;
    }

    /* Table */
    .table-responsive {
        overflow-x: auto;
        padding: 0 4px 4px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        min-width: 750px;
    }
    th {
        background: #f8fafc;
        color: #4b6a8b;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding: 12px 16px;
        text-align: left;
        border-bottom: 2px solid #e6ecf3;
        position: sticky;
        top: 0;
        z-index: 5;
    }
    th i { color: #2563eb; margin-right: 4px; }
    td {
        padding: 12px 16px;
        border-bottom: 1px solid #eff3f8;
        color: #0a1e3c;
        vertical-align: top;
    }
    tr:hover td {
        background: #f8fafc;
    }
    tr:last-child td {
        border-bottom: none;
    }

    /* Date Column */
    .date-cell {
        font-weight: 700;
        min-width: 70px;
        white-space: nowrap;
    }
    .date-cell .day-name {
        display: block;
        color: #8a9bb5;
        font-size: 11px;
        font-weight: 400;
        margin-top: 2px;
    }

    /* Presence Badge */
    .presence-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }
    .presence-badge.present {
        background: #d1fae5;
        color: #047857;
    }
    .presence-badge.absent {
        background: #fee2e2;
        color: #b91c1c;
    }
    .presence-badge.no-class {
        background: #f1f5f9;
        color: #64748b;
    }
    .presence-badge.na {
        background: #fef3c7;
        color: #92400e;
    }

    /* Materials Column */
    .materials-container {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .material-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px 12px;
        background: #f8fafc;
        border-radius: 8px;
        border-left: 3px solid transparent;
        flex-wrap: wrap;
    }
    .material-row.type-video {
        border-left-color: #2563eb;
        background: #f0f7ff;
    }
    .material-row.type-link {
        border-left-color: #10b981;
        background: #f0fdf4;
    }

    .material-row .mat-icon {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
    }
    .material-row .mat-icon.video {
        background: #dbeafe;
        color: #2563eb;
    }
    .material-row .mat-icon.link {
        background: #dcfce7;
        color: #10b981;
    }

    .material-row .mat-name {
        flex: 1;
        font-weight: 500;
        font-size: 13px;
        color: #0a1e3c;
        min-width: 150px;
    }

    .material-row .mat-clicks {
        font-size: 12px;
        font-weight: 600;
        color: #4b6a8b;
        background: #e6ecf3;
        padding: 2px 12px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }
    .material-row .mat-clicks i {
        color: #2563eb;
        font-size: 11px;
    }
    .material-row .mat-clicks.high {
        background: #d1fae5;
        color: #065f46;
    }
    .material-row .mat-clicks.high i {
        color: #10b981;
    }
    .material-row .mat-clicks.low {
        background: #fee2e2;
        color: #991b1b;
    }
    .material-row .mat-clicks.low i {
        color: #ef4444;
    }

    .material-row .mat-status {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 12px;
        border-radius: 12px;
        white-space: nowrap;
    }
    .material-row .mat-status.viewed {
        background: #d1fae5;
        color: #065f46;
    }
    .material-row .mat-status.not-viewed {
        background: #f1f5f9;
        color: #64748b;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #8a9bb5;
    }
    .empty-state .icon {
        font-size: 48px;
        margin-bottom: 12px;
        color: #10b981;
        opacity: 0.5;
    }
    .empty-state .sub {
        color: #b0c0d0;
        font-size: 14px;
    }

    /* Responsive */
    @media (max-width: 700px) {
        .student-detail-page {
            padding: 16px;
        }
        .detail-toolbar {
            flex-direction: column;
            align-items: stretch;
        }
        .detail-heading {
            flex-direction: column;
            align-items: flex-start;
            padding: 18px;
        }
        .detail-heading .context {
            margin-left: 0;
            text-align: left;
            width: 100%;
        }
        .detail-controls {
            flex-direction: column;
            align-items: stretch;
            padding: 14px 16px;
        }
        .stats-row {
            grid-template-columns: 1fr 1fr 1fr;
        }
        td {
            padding: 10px 12px;
        }
        .material-row {
            padding: 4px 8px;
            gap: 6px;
        }
        .material-row .mat-name {
            min-width: 100px;
            font-size: 12px;
        }
        .material-row .mat-clicks {
            font-size: 11px;
            padding: 1px 8px;
        }
    }

    @media (max-width: 480px) {
        .stats-row {
            grid-template-columns: 1fr 1fr;
        }
        .date-cell {
            min-width: 55px;
            font-size: 12px;
        }
        .presence-badge {
            font-size: 11px;
            padding: 3px 10px;
        }
    }
</style>
@endsection

@section('content')
<div class="student-detail-page">
    <!-- Toolbar -->
    <div class="detail-toolbar">
        <a class="back-link" href="{{ route('lesson-planner.review') }}">
            <i class="fas fa-arrow-left"></i> Back to review
        </a>
    </div>

    <!-- Student Header -->
    <section class="detail-heading">
        <div class="student-avatar" id="student-avatar"></div>
        <div class="info">
            <h1 id="student-name">Student Detail</h1>
            <p class="meta">
                <span><i class="fas fa-id-badge"></i> Reg. No.: <span id="student-reg-no"></span></span>
                <span><i class="fas fa-envelope"></i> <span id="student-email"></span></span>
            </p>
        </div>
        <div class="context">
            Department & Course
            <strong id="class-subject"></strong>
        </div>
    </section>

    <!-- Stats Cards -->
    <div class="stats-row">
        <div class="stat-card">
            <span class="number blue" id="total-days">0</span>
            <span class="label">Total Days</span>
        </div>
        <div class="stat-card">
            <span class="number green" id="present-count">0</span>
            <span class="label">Present</span>
        </div>
        <div class="stat-card">
            <span class="number red" id="absent-count">0</span>
            <span class="label">Absent</span>
        </div>
        <div class="stat-card">
            <span class="number blue" id="attendance-percent">0%</span>
            <span class="label">Attendance</span>
        </div>
        <div class="stat-card">
            <span class="number blue" id="total-materials">0</span>
            <span class="label">Total Materials</span>
        </div>
    </div>

    <!-- Detail Card -->
    <section class="detail-card">
        <div class="detail-controls">
            <label>
                <i class="fas fa-calendar-alt"></i> Month
                <select id="month-filter">
                    @for ($month = 1; $month <= 12; $month++)
                        <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}</option>
                    @endfor
                </select>
            </label>
            <div style="font-size:13px;color:#4b6a8b;">
                <i class="fas fa-info-circle"></i> 
                <span id="teaching-days-label">0 teaching days</span>
            </div>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="min-width:75px;"><i class="fas fa-calendar-day"></i> Date</th>
                        <th style="min-width:70px;"><i class="fas fa-clock"></i> Day</th>
                        <th style="min-width:180px;"><i class="fas fa-heading"></i> Topic</th>
                        <th style="min-width:90px;"><i class="fas fa-user-check"></i> Presence</th>
                        <th><i class="fas fa-file-alt"></i> Materials &amp; Clicks</th>
                    </tr>
                </thead>
                <tbody id="detail-body">
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="icon"><i class="fas fa-calendar-times"></i></div>
                                <div>No data available</div>
                                <div class="sub">Select a month to view student details</div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
    // =============================================
    // CONFIGURATION
    // =============================================
    const student = @json($studentData);
    const detailData = {
        department: @json($assignment->department_name),
        course: @json($assignment->course_type ?: $assignment->course_detail_id),
        subject: @json($assignment->subject_name),
        year: @json($year),
        attendance: @json($attendanceStatuses),
        months: @json($months)
    };

    function renderStudentDetail() {
        const month = parseInt(document.getElementById('month-filter').value, 10);
        const days = detailData.months[month] || [];
        let present = 0, absent = 0, totalMaterials = 0, teachingDays = 0;
        let rows = '';

        days.forEach(day => {
            const materials = day.materials || []; 
            const topicTitles = day.topicTitles || [];
            const hasSchedule = day.classScheduled === true;
            const attendanceStatus = detailData.attendance?.[day.date];
            const dayData = {
                status: hasSchedule
                    ? (attendanceStatus === 'present' || attendanceStatus === 'absent' ? attendanceStatus : 'na')
                    : 'no_class',
                materials: materials
            };

            if (hasSchedule) {
                teachingDays++;
                if (dayData.status === 'present') present++;
                else if (dayData.status === 'absent') absent++;
                totalMaterials += materials.length;
            }

            let materialsHtml = '<div class="materials-container">';
            materials.forEach(material => {
                const typeClass = material.type === 'video' ? 'type-video' : 'type-link';
                const icon = material.type === 'video' ? 'fa-video' : 'fa-link';
                const statusClass = material.viewed ? 'viewed' : 'not-viewed';
                const clickClass = material.clicks >= 4 ? 'high' : (material.clicks === 0 ? 'low' : '');
                materialsHtml += `<div class="material-row ${typeClass}">
                    <span class="mat-icon ${material.type === 'video' ? 'video' : 'link'}"><i class="fas ${icon}"></i></span>
                    <a class="mat-name" href="${material.url}" target="_blank" rel="noopener">${material.title}</a>
                    <span class="mat-clicks ${clickClass}"><i class="fas fa-mouse-pointer"></i> ${material.clicks} clicks</span>
                    <span class="mat-status ${statusClass}">${material.viewed ? 'Viewed' : 'Not Viewed'}</span>
                </div>`;
            });
            materialsHtml += materials.length ? '</div>' : '<span style="color:#b0c0d0;font-size:12px;">—</span></div>';

            const statusLabels = { present: ['present', 'fa-check-circle', 'Present'], absent: ['absent', 'fa-times-circle', 'Absent'], na: ['na', 'fa-question-circle', 'N/A'], no_class: ['no-class', 'fa-minus', 'No Class'] };
            const status = statusLabels[dayData.status] || statusLabels.no_class;
            const topicHtml = topicTitles.length
                ? topicTitles.map(title => `<div>${title}</div>`).join('')
                : (hasSchedule ? '<span style="color:#b45309;font-size:12px;">Topic not planned for this day</span>' : '<span style="color:#b0c0d0;font-size:12px;">—</span>');
            rows += `<tr>
                <td class="date-cell">${day.day}<span class="day-name">${day.shortDay}</span></td>
                <td>${day.shortDay}</td>
                <td>${topicHtml}</td>
                <td><span class="presence-badge ${status[0]}"><i class="fas ${status[1]}"></i> ${status[2]}</span></td>
                <td>${materialsHtml}</td>
            </tr>`;
        });

        document.getElementById('detail-body').innerHTML = rows || '<tr><td colspan="5"><div  class="empty-state">No classes for this month</div></td></tr>';
        const totalDays = present + absent;
        document.getElementById('total-days').textContent = totalDays;
        document.getElementById('present-count').textContent = present;
        document.getElementById('absent-count').textContent = absent;
        document.getElementById('attendance-percent').textContent = totalDays ? Math.round((present / totalDays) * 100) + '%' : '0%';
        document.getElementById('total-materials').textContent = totalMaterials;
        document.getElementById('teaching-days-label').textContent = `${teachingDays} teaching days`;
    }

    // =============================================
    // INITIALIZE PAGE
    // =============================================
    document.getElementById('student-name').textContent = student.name;
    document.getElementById('student-avatar').textContent = student.name.split(' ').map(n => n[0]).join('');
    document.getElementById('student-reg-no').textContent = student.regNo;
    document.getElementById('student-email').textContent = student.email;
    document.getElementById('class-subject').textContent = `${detailData.department} - ${detailData.course} - ${detailData.subject}`;

    document.getElementById('month-filter').addEventListener('change', renderStudentDetail);
    renderStudentDetail();
</script>
@endsection
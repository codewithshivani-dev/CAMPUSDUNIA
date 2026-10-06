@extends('instituteAdmin.student.Lesson-planner.index')

@section('student-content')
<style>
    .performance-page { padding: 28px 32px; background: #f0f4f9; min-height: 100vh; }
    .performance-header { margin-bottom: 24px; }
    .performance-header h1 { margin: 0; color: #0a1e3c; font-size: 28px; font-weight: 800; }
    .performance-header h1 i { color: #10b981; margin-right: 10px; }
    .performance-header p { margin: 5px 0 0; color: #4b6a8b; font-size: 15px; }
    .student-details-card { display: flex; align-items: center; justify-content: space-between; gap: 24px; background: white; border: 1px solid #eaf0f6; border-radius: 24px; padding: 24px 28px; margin-bottom: 24px; box-shadow: 0 2px 10px rgba(0,0,0,.01); }
    .student-profile { display: flex; align-items: center; gap: 18px; min-width: 0; }
    .student-avatar { width: 64px; height: 64px; flex: 0 0 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #2563eb, #60a5fa); color: white; font-size: 24px; font-weight: 700; }
    .student-name { color: #0a1e3c; font-size: 22px; font-weight: 700; margin: 0 0 4px; }
    .student-meta { display: flex; flex-wrap: wrap; gap: 14px; color: #4b6a8b; font-size: 13px; }
    .student-meta i, .student-context-label i { color: #2563eb; margin-right: 5px; }
    .student-context { min-width: 250px; padding: 10px 16px; border-radius: 12px; background: #f8fafc; text-align: right; }
    .student-context-label { color: #4b6a8b; font-size: 12px; margin-bottom: 4px; }
    .student-context-value { color: #0a1e3c; font-size: 15px; font-weight: 700; }
    .details-button { display: inline-flex; align-items: center; gap: 7px; margin-top: 10px; padding: 8px 13px; border-radius: 10px; background: #2563eb; color: white; text-decoration: none; font-size: 12px; font-weight: 600; }
    .details-button:hover { background: #1d4ed8; }
    .filter-card { background: white; border: 1px solid #eaf0f6; border-radius: 28px; padding: 24px 26px; margin-bottom: 24px; box-shadow: 0 2px 10px rgba(0,0,0,.01); }
    .filters { display: flex; flex-wrap: wrap; gap: 16px; align-items: end; }
    .filter-group { flex: 1; min-width: 180px; }
    .filter-group label { display: block; color: #4b6a8b; font-size: 13px; font-weight: 600; margin-bottom: 4px; }
    .filter-group label i { color: #10b981; margin-right: 4px; }
    .filter-group select { width: 100%; padding: 10px 14px; border: 2px solid #e6ecf3; border-radius: 12px; color: #0a1e3c; background: white; font-size: 14px; }
    .filter-button, .reset-button { padding: 10px 18px; border-radius: 12px; font-weight: 600; font-size: 14px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 7px; }
    .filter-button { border: 0; background: #10b981; color: white; }
    .reset-button { border: 2px solid #e6ecf3; color: #4b6a8b; background: transparent; }
    .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .summary-card, .performance-card { background: white; border: 1px solid #eaf0f6; border-radius: 24px; box-shadow: 0 2px 10px rgba(0,0,0,.01); }
    .summary-card { padding: 20px 22px; position: relative; overflow: hidden; }
    .summary-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: #10b981; }
    .summary-label { color: #4b6a8b; font-size: 13px; display: flex; gap: 8px; align-items: center; }
    .summary-label i { color: #10b981; }
    .summary-value { color: #0a1e3c; font-size: 30px; font-weight: 800; margin-top: 3px; }
    .performance-card { padding: 22px 24px 18px; overflow: hidden; }
    .card-heading { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 16px; }
    .card-heading h2 { margin: 0; font-size: 17px; color: #0a1e3c; }
    .card-heading h2 i { color: #10b981; margin-right: 8px; }
    .year-badge { background: #d1fae5; color: #0b6e4f; padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .table-wrap { overflow-x: auto; border: 1px solid #eef2f7; border-radius: 16px; }
    table { width: 100%; min-width: 760px; border-collapse: collapse; font-size: 13px; }
    th { padding: 10px 12px; text-align: left; color: #4b6a8b; background: #f8fafc; font-size: 11px; text-transform: uppercase; letter-spacing: .3px; white-space: nowrap; border-bottom: 2px solid #e6ecf3; }
    th i { color: #10b981; margin-right: 4px; }
    td { padding: 13px 14px; border-top: 1px solid #eff3f8; color: #1f334f; vertical-align: middle; }
    td:not(:first-child), th:not(:first-child) { text-align: center; }
    .subject-name { color: #0a1e3c; font-weight: 700; text-align: left !important; }
    .metric { font-weight: 700; color: #0a1e3c; }
    .metric-sub { display: block; color: #8a9bb5; font-size: 11px; margin-top: 3px; }
    .performance-progress { width: 90px; height: 8px; background: #e6ecf3; border-radius: 10px; overflow: hidden; display: inline-block; margin-top: 5px; vertical-align: middle; }
    .performance-progress-fill { display: block; height: 100%; background: #10b981; border-radius: 10px; transition: width .4s ease; }
    .attendance { color: #2563eb; }
    .details-button { display: inline-flex; align-items: center; gap: 6px; padding: 7px 11px; border-radius: 9px; background: #2563eb; color: white; text-decoration: none; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .details-button:hover { background: #1d4ed8; }
    .empty-state { padding: 54px 20px; text-align: center; color: #8a9bb5; }
    .empty-state i { color: #10b981; font-size: 48px; margin-bottom: 12px; }
    .empty-state p { margin: 4px 0 0; color: #b0c0d0; }
    @media (max-width: 820px) { .performance-page { padding: 16px; } .performance-card { padding: 16px 12px; } .card-heading { align-items: flex-start; flex-direction: column; } }
    @media (max-width: 820px) { .student-details-card { align-items: flex-start; flex-direction: column; padding: 18px; } .student-context { width: 100%; text-align: left; min-width: 0; } .student-name { font-size: 19px; } }
</style>

@php
    $totalPlans = collect($performanceData)->sum('plans');
    $totalTopics = collect($performanceData)->sum('topics');
    $coveredTopics = collect($performanceData)->sum('covered_topics');
    $totalMaterials = collect($performanceData)->sum('materials');
    $viewedMaterials = collect($performanceData)->sum('viewed_materials');
    $totalPresent = collect($performanceData)->sum('present');
    $totalAttendance = collect($performanceData)->sum('attendance_total');
    $coveragePercent = $totalTopics ? round(($coveredTopics / $totalTopics) * 100) : 0;
    $materialPercent = $totalMaterials ? round(($viewedMaterials / $totalMaterials) * 100) : 0;
    $attendancePercent = $totalAttendance ? round(($totalPresent / $totalAttendance) * 100) : 0;
@endphp

<div class="performance-page">
    <div class="performance-header">
        <h1><i class="fas fa-chart-line"></i>My Performance</h1>
        <p>Review your attendance, lesson progress, and learning-material activity.</p>
    </div>

    @if ($student)
        <div class="student-details-card">
            @php $studentName = trim($student->first_name . ' ' . ($student->middle_name ?: '') . ' ' . $student->last_name); @endphp
            <div class="student-profile">
                <div class="student-avatar">{{ collect(explode(' ', $studentName))->filter()->map(fn ($name) => strtoupper(substr($name, 0, 1)))->take(2)->implode('') }}</div>
                <div>
                    <h2 class="student-name">{{ $studentName }}</h2>
                    <div class="student-meta">
                        <span><i class="fas fa-id-badge"></i>Reg. No.: {{ $student->registration_number ?: 'Not available' }}</span>
                        @if ($student->email)<span><i class="fas fa-envelope"></i>{{ $student->email }}</span>@endif
                    </div>
                </div>
            </div>
            <div class="student-context">
                <div class="student-context-label">Department &amp; Course</div>
                <div class="student-context-value">{{ $departmentName ?: 'Not available' }} - {{ $className ?: 'Not available' }}</div>
                @if (!empty($teacherNames))<div class="student-context-label" style="margin-top: 5px;"><i class="fas fa-chalkboard-teacher"></i>Teacher: {{ implode(', ', $teacherNames) }}</div>@endif
            </div>
        </div>
    @endif

   

    <div class="summary-grid">
        <div class="summary-card"><div class="summary-label"><i class="fas fa-calendar-check"></i> Attendance</div><div class="summary-value">{{ $attendancePercent }}%</div></div>
        <div class="summary-card"><div class="summary-label"><i class="fas fa-book-open"></i> Topic coverage</div><div class="summary-value">{{ $coveragePercent }}%</div></div>
        <div class="summary-card"><div class="summary-label"><i class="fas fa-file-alt"></i> Materials viewed</div><div class="summary-value">{{ $materialPercent }}%</div></div>
        <div class="summary-card"><div class="summary-label"><i class="fas fa-layer-group"></i> Lesson plans</div><div class="summary-value">{{ $totalPlans }}</div></div>
    </div>
 <div class="filter-card">
    <form class="filters" method="GET" action="{{ route('student.lesson-planner.performance') }}">
        <div class="filter-group">
            <label for="performance-month"><i class="fas fa-calendar-alt"></i>Month</label>
            <select id="performance-month" name="month">
                @for ($filterMonth = 1; $filterMonth <= 12; $filterMonth++)
                    <option value="{{ $filterMonth }}" {{ $filterMonth === $month ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $filterMonth, 1)) }} {{ $year }}</option>
                @endfor
            </select>
        </div>
        <div class="filter-group">
            <label for="performance-subject"><i class="fas fa-book"></i>Subject</label>
            <select id="performance-subject" name="subject">
                <option value="">All subjects</option>
                @foreach ($subjects as $subjectId => $subjectName)
                    <option value="{{ $subjectId }}" {{ (string) $subjectFilter === (string) $subjectId ? 'selected' : '' }}>{{ $subjectName }}</option>
                @endforeach
            </select>
        </div>
        <button class="filter-button" type="submit"><i class="fas fa-filter"></i> Apply filter</button>
        <a class="reset-button" href="{{ route('student.lesson-planner.performance') }}"><i class="fas fa-undo"></i> Reset</a>
    </form>
    </div>
    <div class="performance-card">
        <div class="card-heading">
            <h2><i class="fas fa-chart-bar"></i>Subject performance</h2>
            <span class="year-badge">{{ $year }}</span>
        </div>
        @if (empty($performanceData))
            <div class="empty-state"><i class="fas fa-chart-line"></i><div>No performance data is available yet.</div><p>Your subject results will appear here as lessons and attendance are recorded.</p></div>
        @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th><i class="fas fa-book"></i> Subject</th><th><i class="fas fa-user-check"></i> Attendance</th><th><i class="fas fa-list-check"></i> Topics covered</th><th><i class="fas fa-file-alt"></i> Materials viewed</th><th><i class="fas fa-layer-group"></i> Plans</th><th><i class="fas fa-cog"></i> Action</th></tr></thead>
                    <tbody>
                    @foreach ($performanceData as $subject)
                        @php
                            $subjectAttendance = $subject['attendance_total'] ? round(($subject['present'] / $subject['attendance_total']) * 100) : 0;
                            $subjectCoverage = $subject['topics'] ? round(($subject['covered_topics'] / $subject['topics']) * 100) : 0;
                            $subjectMaterials = $subject['materials'] ? round(($subject['viewed_materials'] / $subject['materials']) * 100) : 0;
                        @endphp
                        <tr>
                            <td class="subject-name">{{ $subject['subject'] }}</td>
                            <td><span class="metric attendance">{{ $subjectAttendance }}%</span><span class="metric-sub">{{ $subject['present'] }}/{{ $subject['attendance_total'] }} present</span></td>
                            <td><span class="metric">{{ $subjectCoverage }}%</span><span class="metric-sub">{{ $subject['covered_topics'] }}/{{ $subject['topics'] }}</span><span class="performance-progress" role="progressbar" aria-valuenow="{{ $subjectCoverage }}" aria-valuemin="0" aria-valuemax="100"><span class="performance-progress-fill" style="width: {{ $subjectCoverage }}%"></span></span></td>
                            <td><span class="metric">{{ $subjectMaterials }}%</span><span class="metric-sub">{{ $subject['viewed_materials'] }}/{{ $subject['materials'] }}</span><span class="performance-progress" role="progressbar" aria-valuenow="{{ $subjectMaterials }}" aria-valuemin="0" aria-valuemax="100"><span class="performance-progress-fill" style="width: {{ $subjectMaterials }}%"></span></span></td>
                            <td><span class="metric">{{ $subject['plans'] }}</span></td>
                            <td><a class="details-button" href="{{ route('student.lesson-planner.student-details', ['subject' => $subject['subject_id']]) }}"><i class="fas fa-eye"></i> View details</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

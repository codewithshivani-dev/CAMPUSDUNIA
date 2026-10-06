<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Report Card</title>
    <style>
    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 11px;
        line-height: 1.4;
        color: #333;
        margin: 20px;
    }

    h1,
    h2,
    h3,
    h4 {
        margin: 0 0 5px 0;
        padding: 0;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #4361ee;
    }

    .header h1 {
        font-size: 24px;
        color: #4361ee;
        margin-bottom: 5px;
    }

    .header h2 {
        font-size: 18px;
        color: #3a0ca3;
        margin-bottom: 5px;
    }

    .header .school-info {
        font-size: 12px;
        color: #666;
        margin-bottom: 3px;
    }

    .header .session {
        font-size: 12px;
        font-weight: bold;
        color: #4361ee;
        margin-top: 5px;
    }

    .student-info-section {
        margin-bottom: 20px;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 5px;
        border-left: 4px solid #4361ee;
    }

    .student-info-grid {
        display: table;
        width: 100%;
    }

    .info-row {
        display: table-row;
    }

    .info-label {
        display: table-cell;
        font-weight: bold;
        width: 140px;
        padding: 3px 5px;
        color: #3a0ca3;
    }

    .info-value {
        display: table-cell;
        padding: 3px 5px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
        font-size: 10px;
    }

    thead {
        display: table-header-group;
    }

    th {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: Black;
        font-weight: 600;
        padding: 8px 5px;
        border: 1px solid #ddd;
        text-align: center;
        font-size: 10px;
    }

    td {
        padding: 6px 5px;
        border: 1px solid #ddd;
        text-align: center;
    }

    .subject-cell {
        font-weight: 600;
        text-align: left !important;
        padding-left: 8px !important;
        background-color: #f0f5ff;
    }

    .grade-box {
        display: inline-block;
        padding: 3px 6px;
        border-radius: 3px;
        font-weight: 600;
        color: white;
        font-size: 9px;
        min-width: 30px;
    }

    .grade-a1 {
        background: linear-gradient(135deg, #FFD700, #FFA500);
    }

    .grade-a2 {
        background: linear-gradient(135deg, #8A2BE2, #4B0082);
    }

    .grade-b1 {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
    }

    .grade-b2 {
        background: linear-gradient(135deg, #5a67d8, #4c51bf);
    }

    .grade-c1 {
        background: linear-gradient(135deg, #4bb543, #2a9d40);
    }

    .grade-c2 {
        background: linear-gradient(135deg, #ff9e00, #ff7b00);
    }

    .grade-d {
        background: #aaa;
    }

    .grade-e {
        background: linear-gradient(135deg, #e63946, #d00000);
    }

    .summary-section {
        margin: 20px 0;
        padding: 15px;
        background-color: #f8f9ff;
        border-radius: 5px;
        border: 1px solid #e0e0e0;
    }

    .summary-row {
        display: table;
        width: 100%;
        margin-bottom: 8px;
    }

    .summary-label {
        display: table-cell;
        font-weight: bold;
        width: 150px;
        color: #3a0ca3;
    }

    .summary-value {
        display: table-cell;
        font-weight: 600;
    }

    .grading-scale-section {
        margin: 15px 0;
        padding: 10px;
        background-color: #f8f9ff;
        border-left: 4px solid #4361ee;
    }

    .grading-scale-title {
        font-weight: bold;
        margin-bottom: 8px;
        color: #3a0ca3;
    }

    .grade-items {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .grade-item {
        margin-right: 8px;
        margin-bottom: 5px;
    }

    .signature-section {
        margin-top: 30px;
        display: table;
        width: 100%;
    }

    .signature-box {
        display: table-cell;
        text-align: center;
        width: 33.33%;
    }

    .signature-line {
        border-top: 1px solid #555;
        width: 130px;
        margin: 0 auto 5px auto;
        padding-top: 5px;
    }

    .footer-note {
        margin-top: 15px;
        text-align: center;
        font-size: 9px;
        color: #666;
        border-top: 1px dashed #ccc;
        padding-top: 10px;
    }

    /* Board-specific colors */
    .cbse-format .header h1 {
        color: #4361ee;
    }

    .icse-format .header h1 {
        color: #e63946;
    }

    .state-format .header h1 {
        color: #2a9d8f;
    }

    .open-format .header h1 {
        color: #7209b7;
    }

    .cbse-format th {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
    }

    .icse-format th {
        background: linear-gradient(135deg, #e63946, #9d0208);
    }

    .state-format th {
        background: linear-gradient(135deg, #2a9d8f, #264653);
    }

    .open-format th {
        background: linear-gradient(135deg, #7209b7, #3a0ca3);
    }

    @media print {
        body {
            margin: 0.5in;
        }

        .no-print {
            display: none;
        }
    }

    .text-center {
        text-align: center;
    }

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

    .font-bold {
        font-weight: bold;
    }

    .mt-20 {
        margin-top: 20px;
    }

    .mb-10 {
        margin-bottom: 10px;
    }

    .p-10 {
        padding: 10px;
    }
    </style>
</head>
<body>
<div class="cbse-format">

    {{-- ================= HEADER ================= --}}
    <div style="text-align:center; border-bottom:2px solid #000; padding-bottom:10px; margin-bottom:15px;">
        <h2 style="margin:0;">{{ strtoupper($institute->name ?? 'SCHOOL NAME') }}</h2>

        <div style="font-size:11px;">
            {{ $institute->address_line_1 ?? '' }}
        </div>

        <div style="font-size:11px;">
            PH: {{ $institute->contact_number ?? '' }}
            @if(!empty($institute->email))
                | Email: {{ $institute->email }}
            @endif
        </div>

        <h3 style="margin-top:8px;">ACHIEVEMENT RECORD - ANNUAL REPORT CARD</h3>
        <div><strong>Session:</strong> {{ $session ?? date('Y') }}</div>
    </div>


    {{-- ================= STUDENT DETAILS ================= --}}
    <table style="margin-bottom:15px; font-size:11px;">
        <tr>
            <td><strong>Name:</strong> {{ $student->full_name ?? '--' }}</td>
            <td><strong>Registration No:</strong> {{ $student->registration_number ?? '--' }}</td>
        </tr>
        <tr>
            <td><strong>Father's Name:</strong> {{ $student->father_name ?? '--' }}</td>
            <td><strong>Class:</strong> {{ $academic->course_type ?? '' }} {{ $academic->section_display ?? '' }}</td>
        </tr>
        <tr>
            <td><strong>Mother's Name:</strong> {{ $student->mother_name ?? '--' }}</td>
            <td><strong>Date of Birth:</strong>
                {{ isset($student->dob) ? date('d/m/Y', strtotime($student->dob)) : '--' }}
            </td>
        </tr>
    </table>


    {{-- ================= SUBJECT TABLE ================= --}}
    <h4 style="margin-bottom:5px;">Scholastic Areas</h4>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
        <tr style="background:#eee; font-weight:bold;">
            <th>Subject</th>

            @foreach($examNames ?? [] as $exam)
                <th>{{ $exam['name'] }} (Max)</th>
                <th>Marks Obt.</th>
                <th>%</th>
                <th>Grade</th>
            @endforeach

            <th>Total</th>
            <th>Final Grade</th>
        </tr>
        </thead>

        <tbody>
        @forelse($subjects ?? [] as $subject)
            @php
                $subjectTotalObtained = 0;
                $subjectTotalMax = 0;
            @endphp

            <tr>
                <td><strong>{{ $subject['subject_name'] ?? 'N/A' }}</strong></td>

                @foreach($examNames ?? [] as $exam)
                    @php
                        $marks = $subject['exam_marks'][$exam['id']] ?? null;
                        $totalMarks = $marks['total_marks'] ?? 0;
                        $obtainedMarks = $marks['obtained_marks'] ?? 0;
                        $percentage = $marks['percentage'] ?? 0;
                        $grade = $marks['grade'] ?? '--';

                        $subjectTotalObtained += $obtainedMarks;
                        $subjectTotalMax += $totalMarks;
                    @endphp

                    <td>{{ $totalMarks ?: '-' }}</td>
                    <td>{{ $obtainedMarks ?: '-' }}</td>
                    <td>{{ $percentage ? $percentage.'%' : '-' }}</td>
                    <td>{{ $grade }}</td>
                @endforeach

                @php
                    $finalPercentage = $subjectTotalMax > 0 
                        ? round(($subjectTotalObtained / $subjectTotalMax) * 100, 2) 
                        : 0;

                    if ($finalPercentage >= 91) $finalGrade = 'A1';
                    elseif ($finalPercentage >= 81) $finalGrade = 'A2';
                    elseif ($finalPercentage >= 71) $finalGrade = 'B1';
                    elseif ($finalPercentage >= 61) $finalGrade = 'B2';
                    elseif ($finalPercentage >= 51) $finalGrade = 'C1';
                    elseif ($finalPercentage >= 41) $finalGrade = 'C2';
                    elseif ($finalPercentage >= 33) $finalGrade = 'D';
                    else $finalGrade = 'E';
                @endphp

                <td>{{ $subjectTotalObtained }} / {{ $subjectTotalMax }}</td>
                <td><strong>{{ $finalGrade }}</strong></td>
            </tr>
        @empty
            <tr>
                <td colspan="100%" align="center">No subject data available</td>
            </tr>
        @endforelse
        </tbody>
    </table>


    {{-- ================= SUMMARY ================= --}}
    @php
        $percentage = $statistics['overall_percentage'] ?? 0;
        $overallGrade = $percentage >= 91 ? 'A1' :
                        ($percentage >= 81 ? 'A2' :
                        ($percentage >= 71 ? 'B1' :
                        ($percentage >= 61 ? 'B2' :
                        ($percentage >= 51 ? 'C1' :
                        ($percentage >= 41 ? 'C2' :
                        ($percentage >= 33 ? 'D' : 'E'))))));
        $result = ($statistics['passed_subjects'] ?? 0) == ($statistics['total_subjects'] ?? 0)
                    ? 'PASS' : 'FAIL';
    @endphp

    <table style="margin-top:15px; font-size:12px;">
        <tr>
            <td><strong>Total Marks:</strong> {{ $statistics['total_marks'] ?? 0 }}</td>
            <td><strong>Marks Obtained:</strong> {{ $statistics['obtained_marks'] ?? 0 }}</td>
        </tr>
        <tr>
            <td><strong>Percentage:</strong> {{ $percentage }}%</td>
            <td><strong>Overall Grade:</strong> {{ $overallGrade }}</td>
        </tr>
        <tr>
            <td><strong>Result:</strong> {{ $result }}</td>
            <td></td>
        </tr>
    </table>


    {{-- ================= SIGNATURES ================= --}}
    <table style="margin-top:40px; width:100%; text-align:center;">
        <tr>
            <td>_____________________<br>Class Teacher</td>
            <td>_____________________<br>Principal</td>
        </tr>
    </table>

    <div style="text-align:center; font-size:9px; margin-top:20px;">
        This is a computer generated report card.
    </div>

</div>
</body>

</html>
<style>

    /*
    |--------------------------------------------------------------------------
    | DOMPDF SAFE ADMIT CARD
    |--------------------------------------------------------------------------
    | A4 page is controlled by pdf.blade.php.
    |
    | Printable area:
    | Width  = 194mm
    | Height = 281mm
    |
    | IMPORTANT:
    | Do not use position:absolute, z-index or overflow:hidden
    | for the main content sections.
    |--------------------------------------------------------------------------
    */

    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        background: #ffffff;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        color: #1a1a2e;
    }


    /*
    |--------------------------------------------------------------------------
    | MAIN PAGE
    |--------------------------------------------------------------------------
    */

    .admit-card-pdf {
        width: 194mm;
        height: 281mm;
        margin: 0;
        padding: 0;
        background: #ffffff;
        page-break-inside: avoid;
    }

    .admit-card-pdf:not(.last-page) {
        page-break-after: always;
    }

    .admit-card-pdf.last-page {
        page-break-after: auto;
    }


    /*
    |--------------------------------------------------------------------------
    | CARD WRAPPER
    |--------------------------------------------------------------------------
    */

    .card-wrapper {
        width: 194mm;
        height: 281mm;

        margin: 0;
        padding: 6mm;

        background: #ffffff;

        border: 1px solid #1a1a2e;

        /*
        IMPORTANT:
        No overflow:hidden.
        No z-index.
        No forced positioning.
        */

        position: relative;

        page-break-inside: avoid;
    }


    /*
    |--------------------------------------------------------------------------
    | INNER BORDER
    |--------------------------------------------------------------------------
    */

    .card-wrapper:before {
        content: "";

        position: absolute;

        top: 2.5mm;
        left: 2.5mm;
        right: 2.5mm;
        bottom: 2.5mm;

        border: 0.4px solid #c7c7d2;

        pointer-events: none;
    }


    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    .header {
        width: 100%;

        text-align: center;

        border-bottom: 1px solid #1a1a2e;

        padding: 0 2mm 3.5mm;

        margin: 0 0 3mm;

        page-break-inside: avoid;
    }

    .institute-name {
        font-size: 17px;
        line-height: 1.2;

        font-weight: bold;

        letter-spacing: 1px;

        text-transform: uppercase;

        color: #1a1a2e;
    }

    .institute-address,
    .institute-phone {
        font-size: 7.5px;
        line-height: 1.25;

        color: #4a4a6a;
    }

    .institute-address {
        margin-top: 1mm;
    }

    .institute-phone {
        margin-top: 0.5mm;
    }

    .doc-title {
        margin-top: 2.5mm;

        font-size: 18px;
        line-height: 1.15;

        font-weight: bold;

        letter-spacing: 3.5px;

        text-transform: uppercase;

        color: #1a1a2e;
    }

    .doc-subtitle {
        margin-top: 1mm;

        font-size: 9px;
        line-height: 1.2;

        font-weight: bold;

        color: #2d2d44;
    }

    .doc-exam-info {
        margin-top: 0.8mm;

        font-size: 8px;
        line-height: 1.2;

        color: #2d2d44;
    }


    /*
    |--------------------------------------------------------------------------
    | STUDENT PANEL
    |--------------------------------------------------------------------------
    */

    .student-panel {
        width: 100%;

        background: #f8f7fc;

        border: 1px solid #d1d0db;

        padding: 3.5mm;

        margin: 0 0 3mm;

        page-break-inside: avoid;
    }

    .student-table,
    .student-details-table {
        width: 100%;

        border-collapse: collapse;

        table-layout: fixed;

        margin: 0;
        padding: 0;
    }

    .student-table > tbody > tr > td {
        padding: 0;

        vertical-align: middle;
    }

    .student-info {
        width: 82%;

        padding-right: 3mm !important;
    }

    .student-photo {
        width: 18%;

        text-align: center;

        vertical-align: middle !important;
    }

    .student-details-table td {
        width: 50%;

        padding: 1mm 1.5mm;

        vertical-align: top;
    }

    .student-details-table td[colspan="2"] {
        width: 100%;
    }

    .info-label {
        display: block;

        margin-bottom: 0.6mm;

        font-size: 8px;
        line-height: 1.1;

        font-weight: bold;

        text-transform: uppercase;

        letter-spacing: 0.35px;

        color: #6b6b8a;
    }

    .info-value,
    .info-value-sm {
        display: block;

        color: #1a1a2e;

        word-wrap: break-word;
    }

    .info-value {
        font-size: 10px;

        line-height: 1.2;

        font-weight: bold;
    }

    .info-value-sm {
        font-size: 9px;

        line-height: 1.2;
    }


    /*
    |--------------------------------------------------------------------------
    | PHOTO
    |--------------------------------------------------------------------------
    */

    .photo-frame {
        width: 25mm;
        height: 30mm;

        margin: 0 auto;

        border: 1px solid #1a1a2e;

        background: #f1f1f6;

        text-align: center;

        overflow: hidden;
    }

    .photo-frame img {
        display: block;

        width: 25mm;
        height: 30mm;
    }

    .photo-frame .placeholder {
        padding-top: 8mm;

        font-size: 8px;

        line-height: 1.2;

        color: #8b8baa;
    }


    /*
    |--------------------------------------------------------------------------
    | SUBJECT-WISE EXAMINATION SCHEDULE
    |--------------------------------------------------------------------------
    |
    | IMPORTANT DOMPDF FIX:
    |
    | The section title is now INSIDE the same table as the schedule.
    |
    | Previously:
    |
    | <div class="schedule-title">...</div>
    | <table>...</table>
    |
    | DomPDF could paint the title over the table body.
    |
    | Now:
    |
    | <table>
    |     <thead>
    |         <tr class="schedule-section-title">...</tr>
    |         <tr class="schedule-header-row">...</tr>
    |     </thead>
    |     <tbody>...</tbody>
    | </table>
    |
    |--------------------------------------------------------------------------
    */

    .schedule-table {
        width: 100%;

        margin: 0;
        padding: 0;

        border-collapse: collapse;

        border-spacing: 0;

        table-layout: fixed;

        font-size: 8px;

        page-break-inside: avoid;
    }


    /*
    |--------------------------------------------------------------------------
    | SCHEDULE SECTION TITLE
    |--------------------------------------------------------------------------
    */

    .schedule-section-title th {
        padding: 2mm 3mm;

        background: #1a1a2e;

        color: #ffffff;

        font-size: 8.5px;

        line-height: 1.2;

        font-weight: bold;

        text-align: left;

        text-transform: uppercase;

        letter-spacing: 0.6px;

        border: 1px solid #1a1a2e;
    }


    /*
    |--------------------------------------------------------------------------
    | SCHEDULE COLUMN HEADER
    |--------------------------------------------------------------------------
    */

    .schedule-header-row th {
        padding: 1.8mm 1.2mm;

        background: #e8e7f0;

        color: #1a1a2e;

        font-size: 7.5px;

        line-height: 1.15;

        font-weight: bold;

        text-align: left;

        text-transform: uppercase;

        border: 1px solid #bdbcc8;
    }


    /*
    |--------------------------------------------------------------------------
    | SCHEDULE CELLS
    |--------------------------------------------------------------------------
    */

    .schedule-table td {
        padding: 1.8mm 1.2mm;

        border: 1px solid #bdbcc8;

        vertical-align: top;

        font-size: 8px;

        line-height: 1.4;

        color: #1a1a2e;

        word-wrap: break-word;

        word-break: normal;
    }


    /*
    |--------------------------------------------------------------------------
    | SCHEDULE ROW
    |--------------------------------------------------------------------------
    */

    .schedule-table tbody tr {
        page-break-inside: avoid;
    }


    /*
    |--------------------------------------------------------------------------
    | ALTERNATE ROW
    |--------------------------------------------------------------------------
    */

    .schedule-table tbody tr:nth-child(even) {
        background: #faf9fe;
    }


    /*
    |--------------------------------------------------------------------------
    | SUBJECT
    |--------------------------------------------------------------------------
    */

    .subject-name {
        font-weight: bold;
    }


    /*
    |--------------------------------------------------------------------------
    | TIME / LOCATION
    |--------------------------------------------------------------------------
    */

    .schedule-table .location-cell div,
    .schedule-table .time-cell div {
        margin: 0 0 0.7mm;

        padding: 0;
    }

    .schedule-table .location-cell div:last-child,
    .schedule-table .time-cell div:last-child {
        margin-bottom: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | INSTRUCTIONS
    |--------------------------------------------------------------------------
    */

    .instructions {
        width: 100%;

        margin-top: 3mm;

        padding: 2.8mm 3.5mm;

        border: 1px solid #d1d0db;

        background: #faf9fe;

        page-break-inside: avoid;
    }

    .instructions-title {
        margin: 0 0 1.5mm;

        font-size: 9px;

        line-height: 1.2;

        font-weight: bold;

        text-transform: uppercase;

        letter-spacing: 0.35px;
    }

    .instructions ol {
        margin: 0;

        padding-left: 5mm;

        font-size: 8px;

        line-height: 1.35;

        color: #2d2d44;
    }

    .instructions li {
        margin: 0 0 1mm;

        padding: 0;

        line-height: 1.35;
    }

    .instructions li:last-child {
        margin-bottom: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | SIGNATURES
    |--------------------------------------------------------------------------
    */

    .signatures {
        width: 100%;

        margin-top: 6mm;

        page-break-inside: avoid;
    }

    .signatures table {
        width: 100%;

        margin: 0;

        border-collapse: collapse;

        table-layout: fixed;
    }

    .signatures td {
        width: 50%;

        padding: 0;

        text-align: center;

        vertical-align: bottom;
    }

    .signature-line {
        display: inline-block;

        width: 42mm;

        padding-top: 1.8mm;

        border-top: 1px solid #1a1a2e;

        font-size: 8px;

        line-height: 1.2;

        color: #4a4a6a;
    }

    .signature-label {
        display: block;

        margin-top: 0.8mm;

        font-size: 7.5px;

        line-height: 1.2;

        color: #6b6b8a;
    }


    /*
    |--------------------------------------------------------------------------
    | FOOTER
    |--------------------------------------------------------------------------
    */

    .card-footer {
        width: 100%;

        margin-top: 4mm;

        padding-top: 2mm;

        border-top: 0.5px solid #d9d8e2;

        text-align: center;

        font-size: 7.5px;

        line-height: 1.2;

        color: #8b8baa;
    }

</style>


<div class="admit-card-pdf {{ !empty($isLastPage) ? 'last-page' : '' }}">

    <div class="card-wrapper">


        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="header">

            <div class="institute-name">
                {{ $institute->name ?? 'INSTITUTE NAME' }}
            </div>


            <div class="institute-address">

                @php
                    $addressParts = array_filter([
                        $institute->address_line_1 ?? null,
                        $institute->address_line_2 ?? null,
                        $institute->city ?? null,
                        $institute->state ?? null,
                        $institute->pincode ?? null,
                    ]);
                @endphp

                {{ implode(', ', $addressParts) }}

            </div>


            @if(!empty($institute->contact_number))

                <div class="institute-phone">
                    {{ $institute->contact_number }}
                </div>

            @endif


            <div class="doc-title">
                Admit Card
            </div>


            <div class="doc-subtitle">
                {{ optional($exams->first())->exam_name ?? 'Examination' }}
            </div>


            <div class="doc-exam-info">

                Academic Year:

                {{ optional($exams->first())->academic_year ?? 'N/A' }}

            </div>

        </div>



        {{-- =====================================================
             STUDENT PANEL
        ====================================================== --}}

        <div class="student-panel">

            <table class="student-table">

                <tbody>

                    <tr>

                        <td class="student-info">

                            <table class="student-details-table">

                                <tbody>


                                    {{-- ROW 1 --}}

                                    <tr>

                                        <td style="width:50%;">

                                            <span class="info-label">
                                                Student Name
                                            </span>

                                            <span class="info-value">
                                                {{ $studentName }}
                                            </span>

                                        </td>


                                        <td style="width:50%;">

                                            <span class="info-label">
                                                Registration No.
                                            </span>

                                            <span class="info-value">
                                                {{ $student->registration_number ?? 'N/A' }}
                                            </span>

                                        </td>

                                    </tr>



                                    {{-- ROW 2 --}}

                                    <tr>

                                        <td>

                                            <span class="info-label">
                                                Roll Number
                                            </span>

                                            <span class="info-value">
                                                {{ optional($student->rollNumber)->roll_number ?? 'Not Assigned' }}
                                            </span>

                                        </td>


                                        <td>

                                            <span class="info-label">
                                                Department
                                            </span>

                                            <span class="info-value-sm">
                                                {{ $academic->department ?? $academic->department_id ?? 'N/A' }}
                                            </span>

                                        </td>

                                    </tr>



                                    {{-- ROW 3 --}}

                                    <tr>

                                        <td>

                                            <span class="info-label">
                                                Course
                                            </span>

                                            <span class="info-value-sm">
                                                {{ $academic->course_subtype ?? $academic->course_type ?? 'N/A' }}
                                            </span>

                                        </td>


                                        <td>

                                            <span class="info-label">
                                                Semester
                                            </span>

                                            <span class="info-value-sm">
                                                {{ $academic->semester_id ?? 'N/A' }}
                                            </span>

                                        </td>

                                    </tr>



                                    {{-- ROW 4 --}}

                                    <tr>

                                        <td colspan="2">

                                            <span class="info-label">
                                                Section
                                            </span>

                                            <span class="info-value-sm">
                                                {{ $sectionName ?? ($academic->section_id ?? 'N/A') }}
                                            </span>

                                        </td>

                                    </tr>


                                </tbody>

                            </table>

                        </td>



                        {{-- =================================================
                             STUDENT PHOTO
                        ================================================== --}}

                        <td class="student-photo">

                            @if($photoData)

                                <div class="photo-frame">

                                    <img
                                        src="{{ $photoData }}"
                                        alt="Student Photo"
                                    >

                                </div>

                            @else

                                <div class="photo-frame">

                                    <div class="placeholder">

                                        <span
                                            style="font-size:32px; display:block;"
                                        >
                                            📷
                                        </span>

                                        Photo

                                    </div>

                                </div>

                            @endif

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>



        {{-- =====================================================
             SUBJECT-WISE EXAMINATION SCHEDULE
             
             IMPORTANT:
             The title and table header are now inside the SAME
             table. This prevents DomPDF from overlapping the
             title with the table body.
        ====================================================== --}}

        <table class="schedule-table">

            <thead>


                {{-- =================================================
                     SCHEDULE TITLE
                ================================================== --}}

                <tr class="schedule-section-title">

                    <th colspan="7">
                        Subject-wise Examination Schedule
                    </th>

                </tr>



                {{-- =================================================
                     TABLE HEADER
                ================================================== --}}

                <tr class="schedule-header-row">

                    <th style="width:5%;">
                        #
                    </th>


                    <th style="width:13%;">
                        Subject
                    </th>


                    <th style="width:14%;">
                        Exam Date
                    </th>


                    <th style="width:17%;">
                        Time
                    </th>


                    <th style="width:10%;">
                        Duration
                    </th>


                    <th style="width:31%;">
                        Exam Location
                    </th>


                    <th style="width:10%;">
                        Max Marks
                    </th>

                </tr>

            </thead>



            <tbody>

                @forelse($exams as $index => $exam)

                    <tr>


                        {{-- =================================================
                             NUMBER
                        ================================================== --}}

                        <td>
                            {{ $index + 1 }}
                        </td>



                        {{-- =================================================
                             SUBJECT
                        ================================================== --}}

                        <td class="subject-name">

                            {{ optional($exam->subject)->subject_name ?? $exam->subject_id }}

                        </td>



                        {{-- =================================================
                             EXAM DATE
                        ================================================== --}}

                        <td>

                            @if($exam->exam_date)

                                {{ \Carbon\Carbon::parse($exam->exam_date)->format('d M Y') }}

                            @else

                                N/A

                            @endif

                        </td>



                        {{-- =================================================
                             TIME
                        ================================================== --}}

                        <td class="time-cell">

                            @if($exam->reporting_time)

                                <div>

                                    <strong>
                                        Reporting:
                                    </strong>

                                    {{ \Carbon\Carbon::parse($exam->reporting_time)->format('h:i A') }}

                                </div>

                            @endif


                            @if($exam->start_time && $exam->end_time)

                                <div>

                                    {{ \Carbon\Carbon::parse($exam->start_time)->format('h:i A') }}

                                    -

                                    {{ \Carbon\Carbon::parse($exam->end_time)->format('h:i A') }}

                                </div>

                            @else

                                @if(!$exam->reporting_time)

                                    N/A

                                @endif

                            @endif

                        </td>



                        {{-- =================================================
                             DURATION
                        ================================================== --}}

                        <td>

                            {{ $exam->duration_minutes
                                ? $exam->duration_minutes . ' min'
                                : 'N/A'
                            }}

                        </td>



                        {{-- =================================================
                             EXAM LOCATION
                        ================================================== --}}

                        <td class="location-cell">

                            @if(($card->location_mode ?? 'inside') === 'outside')


                                @php

                                    $outsideLocation = [

                                        'Building' =>
                                            $card->outside_building,

                                        'Block' =>
                                            $card->outside_block,

                                        'Floor' =>
                                            $card->outside_floor,

                                        'Room' =>
                                            $card->outside_room,

                                        'Address' =>
                                            $card->outside_address,

                                        'City' =>
                                            $card->outside_city,

                                        'State' =>
                                            $card->outside_state,

                                        'Pincode' =>
                                            $card->outside_pincode,

                                    ];

                                @endphp


                                @foreach($outsideLocation as $label => $value)

                                    @if(filled($value))

                                        <div>

                                            <strong>
                                                {{ $label }}:
                                            </strong>

                                            {{ $value }}

                                        </div>

                                    @endif

                                @endforeach


                            @else


                                <div>

                                    <strong>
                                        Building:
                                    </strong>

                                    {{ $exam->building_detail->name
                                        ?? $exam->building_id
                                        ?? 'N/A'
                                    }}

                                </div>


                                <div>

                                    <strong>
                                        Block:
                                    </strong>

                                    {{ $exam->block_detail->name
                                        ?? $exam->block_id
                                        ?? 'N/A'
                                    }}

                                </div>


                                <div>

                                    <strong>
                                        Floor:
                                    </strong>

                                    {{ $exam->floor_detail->floor_name
                                        ?? $exam->floor_id
                                        ?? 'N/A'
                                    }}

                                </div>


                                <div>

                                    <strong>
                                        Room:
                                    </strong>

                                    {{
                                        $exam->room_detail->room_name
                                        ?? $exam->room_detail->room_number
                                        ?? $exam->room_id
                                        ?? 'N/A'
                                    }}

                                </div>


                            @endif

                        </td>



                        {{-- =================================================
                             MAX MARKS
                        ================================================== --}}

                        <td>

                            {{ $exam->total_marks ?? 'N/A' }}

                        </td>


                    </tr>


                @empty


                    <tr>

                        <td
                            colspan="7"
                            style="
                                text-align:center;
                                padding:20px;
                                color:#8b8baa;
                            "
                        >

                            No subjects scheduled

                        </td>

                    </tr>


                @endforelse

            </tbody>

        </table>



        {{-- =====================================================
             DYNAMIC EXAM INSTRUCTIONS
        ====================================================== --}}

        @if(!empty($instructionTexts))

            <div class="instructions">

                <div class="instructions-title">
                    Instructions
                </div>


                <ol>

                    @foreach($instructionTexts as $instructionText)

                        @php
                            $instructionText =
                                trim((string) $instructionText);
                        @endphp


                        @if($instructionText !== '')

                            <li>
                                {{ $instructionText }}
                            </li>

                        @endif

                    @endforeach

                </ol>

            </div>

        @endif



        {{-- =====================================================
             SIGNATURES
        ====================================================== --}}

        <div class="signatures">

            <table>

                <tbody>

                    <tr>


                        {{-- STUDENT SIGNATURE --}}

                        <td>

                            <span class="signature-line">

                                Student's Signature

                                <span class="signature-label">
                                    (Candidate)
                                </span>

                            </span>

                        </td>



                        {{-- AUTHORIZED SIGNATURE --}}

                        <td>

                            <span class="signature-line">

                                Authorized Signature

                                <span class="signature-label">
                                    (Controller of Examinations)
                                </span>

                            </span>

                        </td>


                    </tr>

                </tbody>

            </table>

        </div>



        {{-- =====================================================
             FOOTER
        ====================================================== --}}

        <div class="card-footer">

            This admit card is issued by the institute and is valid only
            for the specified examination.

        </div>


    </div>

</div>
{{-- resources/views/instituteAdmin/RollNumber/AssignRollNumber.blade.php --}}

@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<style>
    /* ================================================================
       ROLL NUMBER ASSIGNMENT - MODERN DESIGN
    ================================================================ */
    
    /* -------- Global Reset -------- */
    #rollNumberApp {
        --primary: #1a3a5c;
        --primary-light: #2a5a8c;
        --primary-soft: #e8edf4;
        --primary-dark: #0e2440;
        --success: #2d7d46;
        --success-soft: #e6f3eb;
        --warning: #b8860b;
        --warning-soft: #fdf6e6;
        --danger: #b33c2e;
        --danger-soft: #fce9e6;
        --gray-50: #f8f9fa;
        --gray-100: #f1f3f5;
        --gray-200: #e9ecef;
        --gray-300: #dee2e6;
        --gray-400: #ced4da;
        --gray-500: #adb5bd;
        --gray-600: #6c757d;
        --gray-700: #495057;
        --gray-800: #343a40;
        --gray-900: #212529;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
        --shadow-lg: 0 8px 30px rgba(0,0,0,0.12);
        --radius: 10px;
        --radius-sm: 6px;
        --font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        
        font-family: var(--font-family);
        background: var(--gray-100);
        margin: -20px -15px;
        padding: 24px 24px 40px;
        min-height: calc(100vh - 60px);
    }

    #rollNumberApp * { box-sizing: border-box; }

    /* -------- Container -------- */
    #rollNumberApp .rn-container {
        max-width: 1600px;
        margin: 0 auto;
    }

    /* -------- Header -------- */
    #rollNumberApp .rn-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        padding: 0 4px 20px;
    }

    #rollNumberApp .rn-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    #rollNumberApp .rn-header-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 20px;
    }

    #rollNumberApp .rn-header-title {
        font-size: 22px;
        font-weight: 600;
        color: var(--gray-900);
        margin: 0;
        letter-spacing: -0.3px;
    }

    #rollNumberApp .rn-header-sub {
        font-size: 14px;
        color: var(--gray-600);
        margin: 2px 0 0;
    }

    #rollNumberApp .rn-header-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    /* -------- Buttons -------- */
    #rollNumberApp .btn {
        font-family: var(--font-family);
        font-weight: 500;
        font-size: 13px;
        padding: 8px 16px;
        border-radius: var(--radius-sm);
        border: 1px solid transparent;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        background: #fff;
        color: var(--gray-700);
        border-color: var(--gray-300);
    }
    #rollNumberApp .btn:hover { transform: translateY(-1px); box-shadow: var(--shadow-sm); }
    #rollNumberApp .btn:active { transform: translateY(0); }

    #rollNumberApp .btn-primary {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }
    #rollNumberApp .btn-primary:hover { background: var(--primary-light); border-color: var(--primary-light); }

    #rollNumberApp .btn-success {
        background: var(--success);
        color: #fff;
        border-color: var(--success);
    }
    #rollNumberApp .btn-success:hover { background: #236a3b; border-color: #236a3b; }

    #rollNumberApp .btn-warning {
        background: var(--warning);
        color: #fff;
        border-color: var(--warning);
    }
    #rollNumberApp .btn-warning:hover { background: #a0750a; border-color: #a0750a; }

    #rollNumberApp .btn-outline {
        background: transparent;
        border-color: var(--gray-300);
        color: var(--gray-700);
    }
    #rollNumberApp .btn-outline:hover { background: var(--gray-100); border-color: var(--gray-400); }

    #rollNumberApp .btn-outline-primary {
        background: transparent;
        border-color: var(--primary);
        color: var(--primary);
    }
    #rollNumberApp .btn-outline-primary:hover { background: var(--primary-soft); }

    #rollNumberApp .btn-sm { padding: 5px 12px; font-size: 12px; }
    #rollNumberApp .btn-xs { padding: 3px 8px; font-size: 11px; border-radius: 4px; }

    /* -------- Filter Panel -------- */
    #rollNumberApp .rn-filter-panel {
        background: #fff;
        border-radius: var(--radius);
        padding: 20px 24px;
        margin-bottom: 20px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
    }

    #rollNumberApp .rn-filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 14px;
        align-items: end;
    }

    #rollNumberApp .rn-filter-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    #rollNumberApp .rn-filter-group label {
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-700);
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    #rollNumberApp .rn-filter-group label .required {
        color: var(--danger);
        margin-left: 2px;
    }

    #rollNumberApp .rn-filter-group select,
    #rollNumberApp .rn-filter-group .select2-container .select2-selection--single {
        height: 40px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--gray-300);
        background: #fff;
        font-size: 14px;
        color: var(--gray-800);
        transition: var(--transition);
        padding: 0 12px;
        width: 100%;
    }

    #rollNumberApp .rn-filter-group select:focus,
    #rollNumberApp .rn-filter-group .select2-container--focus .select2-selection--single {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(26, 58, 92, 0.15);
        outline: none;
    }

    #rollNumberApp .select2-container .select2-selection--single {
        display: flex;
        align-items: center;
    }
    #rollNumberApp .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
        padding-left: 0;
    }
    #rollNumberApp .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px;
    }

    #rollNumberApp .rn-filter-actions {
        display: flex;
        gap: 8px;
        align-items: center;
        padding-top: 20px;
        flex-wrap: wrap;
    }

    /* -------- Progress Card -------- */
    #rollNumberApp .rn-progress-card {
        background: #fff;
        border-radius: var(--radius);
        padding: 18px 24px;
        margin-bottom: 20px;
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    #rollNumberApp .rn-progress-info {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    #rollNumberApp .rn-progress-stat {
        display: flex;
        align-items: baseline;
        gap: 4px;
        font-size: 14px;
        color: var(--gray-700);
    }

    #rollNumberApp .rn-progress-stat .number {
        font-weight: 700;
        font-size: 20px;
        color: var(--gray-900);
    }

    #rollNumberApp .rn-progress-stat .number.green { color: var(--success); }
    #rollNumberApp .rn-progress-stat .number.orange { color: var(--warning); }

    #rollNumberApp .rn-progress-bar-wrap {
        flex: 1;
        min-width: 150px;
    }

    #rollNumberApp .rn-progress-track {
        height: 6px;
        border-radius: 999px;
        background: var(--gray-200);
        overflow: hidden;
        position: relative;
    }

    #rollNumberApp .rn-progress-fill {
        height: 100%;
        border-radius: 999px;
        background: var(--success);
        transition: width 0.6s ease;
    }

    #rollNumberApp .rn-progress-label {
        font-size: 12px;
        color: var(--gray-600);
        margin-top: 4px;
        text-align: right;
    }

    /* -------- Table Wrapper (Scrollable) -------- */
    #rollNumberApp .rn-table-wrapper {
        background: #fff;
        border-radius: var(--radius);
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        position: relative;
    }

    #rollNumberApp .rn-table-scroll {
        overflow-x: auto;
        overflow-y: auto;
        max-height: 520px;
        scroll-behavior: smooth;
    }

    #rollNumberApp .rn-table-scroll::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    #rollNumberApp .rn-table-scroll::-webkit-scrollbar-track {
        background: var(--gray-100);
        border-radius: 999px;
    }

    #rollNumberApp .rn-table-scroll::-webkit-scrollbar-thumb {
        background: var(--gray-400);
        border-radius: 999px;
    }

    #rollNumberApp .rn-table-scroll::-webkit-scrollbar-thumb:hover {
        background: var(--gray-500);
    }

    /* -------- Table -------- */
    #rollNumberApp table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        min-width: 1100px;
    }

    #rollNumberApp table thead {
        position: sticky;
        top: 0;
        z-index: 10;
    }

    #rollNumberApp table thead th {
        background: var(--gray-50);
        color: var(--gray-700);
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding: 10px 14px;
        border-bottom: 2px solid var(--gray-200);
        white-space: nowrap;
        text-align: left;
        position: sticky;
        top: 0;
        z-index: 10;
        background: #f8f9fa;
    }

    #rollNumberApp table thead th:first-child {
        padding-left: 16px;
    }
    #rollNumberApp table thead th:last-child {
        padding-right: 16px;
    }

    #rollNumberApp table tbody td {
        padding: 8px 14px;
        border-bottom: 1px solid var(--gray-100);
        vertical-align: middle;
        color: var(--gray-800);
    }

    #rollNumberApp table tbody td:first-child { padding-left: 16px; }
    #rollNumberApp table tbody td:last-child { padding-right: 16px; }

    #rollNumberApp table tbody tr {
        transition: var(--transition);
    }

    #rollNumberApp table tbody tr:hover {
        background: var(--primary-soft);
    }

    #rollNumberApp table tbody tr.rn-row-pending {
        border-left: 3px solid var(--warning);
    }

    #rollNumberApp table tbody tr.rn-row-assigned {
        border-left: 3px solid var(--success);
    }

    /* -------- Sortable Headers -------- */
    #rollNumberApp .sortable {
        cursor: pointer;
        user-select: none;
        transition: var(--transition);
        padding-right: 20px !important;
        position: relative;
    }

    #rollNumberApp .sortable:hover {
        background: var(--gray-200) !important;
    }

    #rollNumberApp .sortable .sort-icon {
        display: inline-block;
        margin-left: 4px;
        font-size: 10px;
        color: var(--gray-400);
        transition: var(--transition);
    }

    #rollNumberApp .sortable.active {
        color: var(--primary);
    }

    #rollNumberApp .sortable.active .sort-icon {
        color: var(--primary);
    }

    #rollNumberApp .sortable .sort-icon.asc { color: var(--success); }
    #rollNumberApp .sortable .sort-icon.desc { color: var(--danger); }

    /* -------- Checkbox -------- */
    #rollNumberApp .checkbox-custom {
        width: 16px;
        height: 16px;
        accent-color: var(--primary);
        cursor: pointer;
    }

    /* -------- Student Name -------- */
    #rollNumberApp .student-name {
        font-weight: 500;
        color: var(--gray-900);
    }

    #rollNumberApp .student-name .middle {
        font-weight: 400;
        color: var(--gray-600);
    }

    /* -------- Roll Number Input -------- */
    #rollNumberApp .rn-input-group {
        display: flex;
        align-items: center;
        gap: 4px;
        flex-wrap: nowrap;
    }

    #rollNumberApp .roll-number-input {
        font-weight: 500;
        font-size: 12px;
        font-family: 'SF Mono', 'Consolas', monospace;
        padding: 4px 8px;
        border-radius: 4px;
        border: 1px solid var(--gray-300);
        background: #fff;
        width: 130px;
        min-width: 90px;
        transition: var(--transition);
        color: var(--gray-800);
        height: 30px;
    }

    #rollNumberApp .roll-number-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(26, 58, 92, 0.12);
        outline: none;
    }

    #rollNumberApp .roll-number-input:not([readonly]) {
        border-color: var(--warning);
        background: var(--warning-soft);
    }

    #rollNumberApp .roll-number-input:not([readonly])::placeholder {
        color: var(--warning);
        opacity: 0.7;
    }

    #rollNumberApp .roll-number-input[readonly] {
        background: var(--gray-50);
        border-color: var(--gray-200);
        color: var(--success);
        font-weight: 600;
    }

    /* -------- Status Badge -------- */
    #rollNumberApp .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 500;
    }

    #rollNumberApp .status-badge.assigned {
        background: var(--success-soft);
        color: var(--success);
    }

    #rollNumberApp .status-badge.pending {
        background: var(--warning-soft);
        color: var(--warning);
    }

    #rollNumberApp .status-badge .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    #rollNumberApp .status-badge.assigned .dot { background: var(--success); }
    #rollNumberApp .status-badge.pending .dot { background: var(--warning); }

    /* -------- Row number -------- */
    #rollNumberApp .row-number {
        color: var(--gray-500);
        font-size: 12px;
        font-weight: 500;
        min-width: 28px;
        display: inline-block;
    }

    /* -------- Bulk Bar -------- */
    #rollNumberApp .rn-bulk-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 14px 20px;
        background: var(--gray-50);
        border-top: 1px solid var(--gray-200);
    }

    #rollNumberApp .rn-bulk-bar .bulk-info {
        font-size: 13px;
        color: var(--gray-600);
    }

    #rollNumberApp .rn-bulk-bar .bulk-info strong {
        color: var(--gray-800);
    }

    /* -------- Empty State -------- */
    #rollNumberApp .rn-empty {
        padding: 48px 24px;
        text-align: center;
        color: var(--gray-600);
    }

    #rollNumberApp .rn-empty .icon {
        font-size: 40px;
        color: var(--gray-300);
        margin-bottom: 12px;
        display: block;
    }

    #rollNumberApp .rn-empty h4 {
        font-size: 16px;
        color: var(--gray-700);
        margin: 0 0 4px;
    }

    #rollNumberApp .rn-empty p {
        margin: 0;
        font-size: 14px;
    }

    /* -------- Modal -------- */
    #rollNumberModal .modal-content {
        border-radius: var(--radius);
        border: none;
        box-shadow: var(--shadow-lg);
    }

    #rollNumberModal .modal-header {
        border-bottom: 1px solid var(--gray-200);
        padding: 18px 24px;
    }

    #rollNumberModal .modal-title {
        font-weight: 600;
        color: var(--gray-900);
        font-size: 18px;
    }

    #rollNumberModal .modal-body {
        padding: 32px 24px;
        text-align: center;
    }

    #rollNumberModal .modal-body .big-number {
        font-size: 52px;
        font-weight: 700;
        font-family: 'SF Mono', 'Consolas', monospace;
        color: var(--primary);
        letter-spacing: 1px;
    }

    #rollNumberModal .modal-body .label {
        font-size: 14px;
        color: var(--gray-600);
        margin-top: 8px;
    }

    #rollNumberModal .modal-footer {
        border-top: 1px solid var(--gray-200);
        padding: 14px 24px;
    }

    /* -------- Responsive -------- */
    @media (max-width: 992px) {
        #rollNumberApp .rn-filter-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        }
    }

    @media (max-width: 768px) {
        #rollNumberApp {
            padding: 12px 12px 24px;
            margin: -10px -8px;
        }

        #rollNumberApp .rn-header {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }

        #rollNumberApp .rn-header-actions {
            justify-content: flex-start;
        }

        #rollNumberApp .rn-filter-grid {
            grid-template-columns: 1fr 1fr;
        }

        #rollNumberApp .rn-progress-card {
            flex-direction: column;
            align-items: stretch;
        }

        #rollNumberApp .rn-progress-info {
            flex-wrap: wrap;
        }

        #rollNumberApp .rn-table-scroll {
            max-height: 400px;
        }

        #rollNumberApp .rn-bulk-bar {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }

        #rollNumberApp .roll-number-input {
            width: 100px;
            min-width: 70px;
        }
    }

    @media (max-width: 480px) {
        #rollNumberApp .rn-filter-grid {
            grid-template-columns: 1fr;
        }

        #rollNumberApp table {
            font-size: 12px;
            min-width: 800px;
        }

        #rollNumberApp table thead th,
        #rollNumberApp table tbody td {
            padding: 6px 10px;
        }

        #rollNumberApp .roll-number-input {
            width: 80px;
            min-width: 60px;
            font-size: 11px;
            padding: 3px 6px;
        }
    }

  /* -------- Select2 overrides -------- */

.select2-container--default .select2-selection--single {
    background-color: #ffffff !important;
    color: #343a40 !important;
}

.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #adb5bd !important;
}

.select2-dropdown {
    background-color: #ffffff !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    z-index: 9999;
}

.select2-container--default .select2-results__option {
    background-color: #ffffff !important;
    color: #343a40 !important;
    padding: 8px 12px;
}

/* IMPORTANT: hovered option */
.select2-container--default .select2-results__option--highlighted {
    background-color: #1a3a5c !important;
    color: #ffffff !important;
}

/* Selected option */
.select2-container--default .select2-results__option[aria-selected="true"] {
    background-color: #e8edf4 !important;
    color: #1a3a5c !important;
}

/* Selected + hovered */
.select2-container--default
.select2-results__option--highlighted[aria-selected="true"] {
    background-color: #1a3a5c !important;
    color: #ffffff !important;
}

.select2-container--default .select2-search--dropdown {
    background-color: #ffffff !important;
}

.select2-container--default .select2-search--dropdown .select2-search__field {
    background-color: #ffffff !important;
    color: #343a40 !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 4px;
    padding: 6px 10px;
}

.select2-container--default .select2-search--dropdown .select2-search__field:focus {
    outline: none;
    border-color: #1a3a5c !important;
}
</style>

<!-- ================================================================
     ROLL NUMBER ASSIGNMENT APP
     ================================================================ -->
<div id="rollNumberApp">
    <div class="rn-container">

        <!-- ============================================================
             HEADER
             ============================================================ -->
        <div class="rn-header">
            <div class="rn-header-left">
                <div class="rn-header-icon">
                    <i class="fas fa-hashtag"></i>
                </div>
                <div>
                    <h1 class="rn-header-title">
                     Assign  Roll Number  </h1>
                    <p class="rn-header-sub">Assign unique roll numbers to students</p>
                </div>
            </div>
            <div class="rn-header-actions">
                @if($students->isNotEmpty())
                    <button onclick="exportData()" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download"></i> Export
                    </button>
                    <button onclick="reassignAll()" class="btn btn-warning btn-sm">
                        <i class="fas fa-sync-alt"></i> Reassign All
                    </button>
                @endif
            </div>
        </div>

        <!-- ============================================================
             FILTER PANEL
             ============================================================ -->
        <div class="rn-filter-panel">
            <form id="filterForm" method="GET" action="{{ route('roll-numbers.index') }}">
                <div class="rn-filter-grid">
                    <div class="rn-filter-group">
                        <label>Department <span class="required">*</span></label>
                        <select name="department_id" id="department_id" class="form-control select2" onchange="loadCourseTypes()">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->department_id }}"
                                    {{ ($selectedFilters['department_id'] ?? '') == $department->department_id ? 'selected' : '' }}>
                                    {{ $department->department }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="rn-filter-group">
                        <label>Course <span class="required">*</span></label>
                        <select name="course_type" id="course_type" class="form-control select2" onchange="loadSubTypes()">
                            <option value="">All Courses</option>
                            @if(!empty($courseTypes))
                                @foreach($courseTypes as $courseType)
                                    <option value="{{ $courseType->id ?? $courseType['id'] ?? '' }}"
                                        {{ ($selectedFilters['course_type'] ?? '') == ($courseType->id ?? $courseType['id'] ?? '') ? 'selected' : '' }}>
                                        {{ $courseType->name ?? $courseType['name'] ?? '' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="rn-filter-group">
                        <label>Sub Type <span class="required">*</span></label>
                        <select name="course_subtype_id" id="course_subtype_id" class="form-control select2" onchange="loadBatches()">
                            <option value="">All Sub Types</option>
                            @if(!empty($subTypes))
                                @foreach($subTypes as $course)
                                    <option value="{{ $course->id ?? $course['id'] ?? '' }}"
                                        {{ ($selectedFilters['course_subtype_id'] ?? '') == ($course->id ?? $course['id'] ?? '') ? 'selected' : '' }}>
                                        {{ $course->name ?? $course['name'] ?? '' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="rn-filter-group">
                        <label>Batch <span class="required">*</span></label>
                        <select name="batch_id" id="batch_id" class="form-control select2" onchange="loadAcademicYears()">
                            <option value="">All Batches</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->batch_id }}"
                                    {{ ($selectedFilters['batch_id'] ?? '') == $batch->batch_id ? 'selected' : '' }}>
                                    {{ $batch->batch }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="rn-filter-group">
                        <label>Academic Year <span class="required">*</span></label>
                        <select name="academic_year_id" id="academic_year_id" class="form-control select2" onchange="loadSections()">
                            <option value="">All Years</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->academic_year_id }}"
                                    {{ ($selectedFilters['academic_year_id'] ?? '') == $year->academic_year_id ? 'selected' : '' }}>
                                    {{ $year->academic_year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="rn-filter-group">
                        <label>Section <span class="required">*</span></label>
                        <select name="section_id" id="section_id" class="form-control select2">
                            <option value="">All Sections</option>
                            @if(isset($sections) && !empty($sections))
                                @foreach($sections as $section)
                                    <option value="{{ $section['id'] }}"
                                        {{ ($selectedFilters['section_id'] ?? '') == $section['id'] ? 'selected' : '' }}>
                                        {{ $section['name'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="rn-filter-actions">
                    <input type="hidden" name="sort_by" id="sort_by" value="{{ $sortBy ?? 'first_name' }}">
                    <input type="hidden" name="sort_order" id="sort_order" value="{{ $sortOrder ?? 'asc' }}">
                    <button type="submit" name="filter" value="1" class="btn btn-primary">
                        <i class="fas fa-search"></i> Load Students
                    </button>
                    <button type="reset" class="btn btn-outline" onclick="resetFilters()">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                    @if($students->isNotEmpty())
                        <span style="font-size:13px;color:var(--gray-600);margin-left:8px;">
                            <i class="fas fa-users"></i> {{ $students->count() }} students found
                        </span>
                    @endif
                </div>
            </form>
        </div>

        <!-- ============================================================
             PROGRESS CARD
             ============================================================ -->
        @if($students->isNotEmpty())
            @php
                $percentAssigned = $statistics['total'] > 0
                    ? round(($statistics['with_roll_number'] / $statistics['total']) * 100)
                    : 0;
            @endphp
            <div class="rn-progress-card">
                <div class="rn-progress-info">
                    <div class="rn-progress-stat">
                        <span class="number green">{{ $statistics['with_roll_number'] }}</span>
                        <span>assigned</span>
                    </div>
                    <div class="rn-progress-stat">
                        <span class="number orange">{{ $statistics['without_roll_number'] }}</span>
                        <span>pending</span>
                    </div>
                    <div class="rn-progress-stat" style="color:var(--gray-600);">
                        <span>of</span>
                        <span class="number">{{ $statistics['total'] }}</span>
                        <span>total</span>
                    </div>
                </div>

                <div class="rn-progress-bar-wrap">
                    <div class="rn-progress-track">
                        <div class="rn-progress-fill" style="width: {{ $percentAssigned }}%"></div>
                    </div>
                    <div class="rn-progress-label">{{ $percentAssigned }}% complete</div>
                </div>

                @if($statistics['without_roll_number'] > 0)
                    <button onclick="assignAllStudents()" class="btn btn-success btn-sm" style="white-space:nowrap;">
                        <i class="fas fa-magic"></i> Auto-assign All
                    </button>
                @else
                    <span style="color:var(--success);font-weight:500;white-space:nowrap;">
                        <i class="fas fa-check-circle"></i> All assigned
                    </span>
                @endif
            </div>
        @endif

        <!-- ============================================================
             STUDENTS TABLE
             ============================================================ -->
        <div class="rn-table-wrapper">
            <div class="rn-table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th style="width:36px;">
                                <input type="checkbox" id="selectAll" class="checkbox-custom" onclick="toggleSelectAll()">
                            </th>
                            <th style="width:40px;">#</th>
                            <th class="sortable {{ $sortBy === 'registration_number' ? 'active' : '' }}"
                                onclick="sortTable('registration_number')">
                                Registration
                                <span class="sort-icon {{ $sortBy === 'registration_number' ? $sortOrder : '' }}">
                                    <i class="fas fa-{{ $sortBy === 'registration_number' ? ($sortOrder === 'asc' ? 'chevron-up' : 'chevron-down') : 'sort' }}"></i>
                                </span>
                            </th>
                            <th class="sortable {{ $sortBy === 'first_name' ? 'active' : '' }}"
                                onclick="sortTable('first_name')" style="min-width:150px;">
                                Student Name
                                <span class="sort-icon {{ $sortBy === 'first_name' ? $sortOrder : '' }}">
                                    <i class="fas fa-{{ $sortBy === 'first_name' ? ($sortOrder === 'asc' ? 'chevron-up' : 'chevron-down') : 'sort' }}"></i>
                                </span>
                            </th>
                            <th class="sortable {{ $sortBy === 'course_subtype' ? 'active' : '' }}"
                                onclick="sortTable('course_subtype')">
                                Course
                                <span class="sort-icon {{ $sortBy === 'course_subtype' ? $sortOrder : '' }}">
                                    <i class="fas fa-{{ $sortBy === 'course_subtype' ? ($sortOrder === 'asc' ? 'chevron-up' : 'chevron-down') : 'sort' }}"></i>
                                </span>
                            </th>
                            <th class="sortable {{ $sortBy === 'batch' ? 'active' : '' }}"
                                onclick="sortTable('batch')">
                                Batch
                                <span class="sort-icon {{ $sortBy === 'batch' ? $sortOrder : '' }}">
                                    <i class="fas fa-{{ $sortBy === 'batch' ? ($sortOrder === 'asc' ? 'chevron-up' : 'chevron-down') : 'sort' }}"></i>
                                </span>
                            </th>
                            <th class="sortable {{ $sortBy === 'academic_year' ? 'active' : '' }}"
                                onclick="sortTable('academic_year')">
                                A.Y.
                                <span class="sort-icon {{ $sortBy === 'academic_year' ? $sortOrder : '' }}">
                                    <i class="fas fa-{{ $sortBy === 'academic_year' ? ($sortOrder === 'asc' ? 'chevron-up' : 'chevron-down') : 'sort' }}"></i>
                                </span>
                            </th>
                            <th style="min-width:80px;">Section</th>
                            <th class="sortable {{ $sortBy === 'roll_number' ? 'active' : '' }}"
                                onclick="sortTable('roll_number')" style="min-width:160px;">
                                Roll Number
                                <span class="sort-icon {{ $sortBy === 'roll_number' ? $sortOrder : '' }}">
                                    <i class="fas fa-{{ $sortBy === 'roll_number' ? ($sortOrder === 'asc' ? 'chevron-up' : 'chevron-down') : 'sort' }}"></i>
                                </span>
                            </th>
                            <th class="sortable {{ $sortBy === 'status' ? 'active' : '' }}"
                                onclick="sortTable('status')" style="width:100px;">
                                Status
                                <span class="sort-icon {{ $sortBy === 'status' ? $sortOrder : '' }}">
                                    <i class="fas fa-{{ $sortBy === 'status' ? ($sortOrder === 'asc' ? 'chevron-up' : 'chevron-down') : 'sort' }}"></i>
                                </span>
                            </th>
                            <th style="width:80px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $student)
                        @php
                            $academic = $student->academicTransportDetails;
                            $hasRollNumber = $student->rollNumber && $student->rollNumber->status === 'active';
                            $rollValue = $hasRollNumber ? $student->rollNumber->roll_number : '';
                        @endphp
                        <tr class="{{ $hasRollNumber ? 'rn-row-assigned' : 'rn-row-pending' }}">
                            <td>
                                <input type="checkbox" class="student-checkbox checkbox-custom" value="{{ $student->student_hash_id }}">
                            </td>
                            <td>
                                <span class="row-number">{{ $loop->iteration }}</span>
                            </td>
                            <td>{{ $student->registration_number ?? '—' }}</td>
                            <td>
                                <span class="student-name">
                                    {{ $student->first_name }}
                                    @if($student->middle_name)
                                        <span class="middle">{{ $student->middle_name }}</span>
                                    @endif
                                    {{ $student->last_name }}
                                </span>
                            </td>
                            <td>{{ $academic->course_subtype ?? '—' }}</td>
                            <td>{{ $academic->batch ?? '—' }}</td>
                            <td>{{ $academic->academic_year ?? '—' }}</td>
                            <td>
                                @php
                                    $sectionName = $academic->section_id;
                                    if ($academic && $academic->section_id && $academic->course_subtype_id) {
                                        $courseFee = DB::table('course_fee_structures')
                                            ->where('product_id', $academic->course_subtype_id)
                                            ->where('institute_id', $context['institute_id'])
                                            ->first();
                                        if ($courseFee && $courseFee->sections) {
                                            $sections = json_decode($courseFee->sections, true);
                                            if (is_array($sections)) {
                                                foreach ($sections as $section) {
                                                    $id = $section['id'] ?? $section['section_id'] ?? null;
                                                    if ($id == $academic->section_id) {
                                                        $sectionName = $section['name'] ?? $section['section_name'] ?? $academic->section_id;
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                @endphp
                                {{ $sectionName }}
                            </td>
                            <td>
                                <div class="rn-input-group">
                                    <input type="text"
                                           class="roll-number-input"
                                           value="{{ $rollValue }}"
                                           placeholder="Roll No."
                                           data-student-id="{{ $student->student_hash_id }}"
                                           {{ $hasRollNumber ? 'readonly' : '' }}>
                                    @if($hasRollNumber)
                                        <button onclick="editRollNumber(this.closest('.rn-input-group').querySelector('input'))"
                                                class="btn btn-outline btn-xs" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    @endif
                                    <button onclick="generateRandomRollNumber(this.closest('.rn-input-group').querySelector('input'))"
                                            class="btn btn-outline btn-xs" title="Generate">
                                        <i class="fas fa-dice"></i>
                                    </button>
                                    <button onclick="saveSingleRollNumber('{{ $student->student_hash_id }}', this)"
                                            class="btn btn-{{ $hasRollNumber ? 'outline-primary' : 'success' }} btn-xs"
                                            title="Save">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                @if($hasRollNumber)
                                    <span class="status-badge assigned">
                                        <span class="dot"></span> Assigned
                                    </span>
                                @else
                                    <span class="status-badge pending">
                                        <span class="dot"></span> Pending
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($hasRollNumber)
                                    <button onclick="viewRollNumber('{{ $student->rollNumber->roll_number }}')"
                                            class="btn btn-outline-primary btn-xs">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                @else
                                    <span style="color:var(--gray-400);font-size:12px;">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11">
                                <div class="rn-empty">
                                    <span class="icon"><i class="fas fa-users-slash"></i></span>
                                    <h4>No students found</h4>
                                    @if(request()->has('filter'))
                                        <p>Try adjusting your filters above or ensure students have been onboarded.</p>
                                    @else
                                        <p>Select filters above and click <strong>"Load Students"</strong>.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Bulk Actions -->
            @if($students->isNotEmpty())
                <div class="rn-bulk-bar">
                    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                        <button onclick="bulkAssign()" class="btn btn-success btn-sm">
                            <i class="fas fa-check"></i> Assign Selected
                        </button>
                        <button onclick="selectAllStudents()" class="btn btn-outline btn-sm">
                            <i class="fas fa-check-double"></i> Select All
                        </button>
                        <button onclick="deselectAll()" class="btn btn-outline btn-sm">
                            <i class="fas fa-times"></i> Deselect All
                        </button>
                    </div>
                    <span class="bulk-info">
                        <strong>{{ $students->count() }}</strong> students
                        @if($sortBy ?? false)
                            <span style="color:var(--gray-400);margin:0 6px;">|</span>
                            Sorted by <strong>{{ str_replace('_', ' ', ucfirst($sortBy)) }}</strong>
                            <span style="color:var(--gray-500);">
                                <i class="fas fa-arrow-{{ $sortOrder === 'asc' ? 'up' : 'down' }}"></i>
                            </span>
                        @endif
                    </span>
                </div>
            @endif
        </div>

    </div>
</div>

<!-- ================================================================
     ROLL NUMBER PREVIEW MODAL
     ================================================================ -->
<div class="modal fade" id="rollNumberModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-hashtag" style="color:var(--primary);margin-right:8px;"></i>Roll Number</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="big-number" id="rollNumberDisplay">—</div>
                <div class="label">Student's unique roll number</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ================================================================
     SCRIPTS
     ================================================================ -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        width: '100%',
        placeholder: 'Select...',
        allowClear: true
    });

    $('#filterForm').on('submit', function (e) {
        if (!validateFilterSelection()) {
            e.preventDefault();
            return false;
        }
    });

    // Auto-load based on existing selections
    if ($('#department_id').val()) loadCourseTypes();
    if ($('#course_type').val() && $('#department_id').val()) loadSubTypes();
    if ($('#course_subtype_id').val() && $('#batch_id').val()) loadAcademicYears();
    if ($('#batch_id').val() && $('#course_subtype_id').val() && $('#academic_year_id').val()) loadSections();
});

/* ================================================================
   FILTER FUNCTIONS
   ================================================================ */

function clearDependentFields() {
    $('#course_type').html('<option value="">All Courses</option>').val('').trigger('change');
    $('#course_subtype_id').html('<option value="">All Sub Types</option>').val('').trigger('change');
    $('#batch_id').html('<option value="">All Batches</option>').val('').trigger('change');
    $('#academic_year_id').html('<option value="">All Years</option>').val('').trigger('change');
    $('#section_id').html('<option value="">All Sections</option>').val('').trigger('change');
}

function loadCourseTypes() {
    const dept = $('#department_id').val();
    const $target = $('#course_type');
    if (!dept) { clearDependentFields(); return; }

    $target.html('<option value="">Loading...</option>').prop('disabled', true);
    $('#course_subtype_id, #batch_id, #academic_year_id, #section_id')
        .html('<option value="">Select...</option>').prop('disabled', true);

    $.ajax({
        url: '{{ route("roll-numbers.get-courses") }}',
        method: 'GET',
        data: { department_id: dept },
        success: function(r) {
            $target.html('<option value="">All Courses</option>').prop('disabled', false);
            if (r.success && r.courses.length) {
                $.each(r.courses, function(_, c) {
                    $target.append(`<option value="${c.id}">${c.name}</option>`);
                });
            }
        },
        error: function() {
            $target.html('<option value="">Error loading</option>').prop('disabled', true);
        }
    });
}

function loadSubTypes() {
    const dept = $('#department_id').val();
    const course = $('#course_type').val();
    const $target = $('#course_subtype_id');
    if (!dept || !course) { $target.html('<option value="">All Sub Types</option>').prop('disabled', true); return; }

    $target.html('<option value="">Loading...</option>').prop('disabled', true);
    $('#batch_id, #academic_year_id, #section_id').html('<option value="">Select...</option>').prop('disabled', true);

    $.ajax({
        url: '{{ route("roll-numbers.get-courses") }}',
        method: 'GET',
        data: { department_id: dept, course_type: course },
        success: function(r) {
            $target.html('<option value="">All Sub Types</option>').prop('disabled', false);
            if (r.success && r.courses.length) {
                $.each(r.courses, function(_, c) {
                    $target.append(`<option value="${c.id}">${c.name}</option>`);
                });
            }
        },
        error: function() {
            $target.html('<option value="">Error loading</option>').prop('disabled', true);
        }
    });
}

function loadBatches() {
    const subType = $('#course_subtype_id').val();
    const $target = $('#batch_id');
    if (!subType) { $target.html('<option value="">All Batches</option>').prop('disabled', true); return; }

    $target.html('<option value="">Loading...</option>').prop('disabled', true);
    $('#academic_year_id, #section_id').html('<option value="">Select...</option>').prop('disabled', true);

    $.ajax({
        url: '{{ route("roll-numbers.get-batches") }}',
        method: 'GET',
        data: { course_subtype_id: subType },
        success: function(r) {
            $target.html('<option value="">All Batches</option>').prop('disabled', false);
            if (r.success && r.batches.length) {
                $.each(r.batches, function(_, b) {
                    $target.append(`<option value="${b.batch_id}">${b.batch}</option>`);
                });
            }
        },
        error: function() {
            $target.html('<option value="">Error loading</option>').prop('disabled', true);
        }
    });
}

function loadAcademicYears() {
    const batch = $('#batch_id').val();
    const subType = $('#course_subtype_id').val();
    const $target = $('#academic_year_id');
    if (!batch || !subType) { $target.html('<option value="">All Years</option>').prop('disabled', true); return; }

    $target.html('<option value="">Loading...</option>').prop('disabled', true);
    $('#section_id').html('<option value="">Select...</option>').prop('disabled', true);

    $.ajax({
        url: '{{ route("roll-numbers.get-academic-years") }}',
        method: 'GET',
        data: { batch_id: batch, course_subtype_id: subType },
        success: function(r) {
            $target.html('<option value="">All Years</option>').prop('disabled', false);
            if (r.success && r.academicYears.length) {
                $.each(r.academicYears, function(_, y) {
                    $target.append(`<option value="${y.academic_year_id}">${y.academic_year}</option>`);
                });
            }
        },
        error: function() {
            $target.html('<option value="">Error loading</option>').prop('disabled', true);
        }
    });
}

function loadSections() {
    const course = $('#course_subtype_id').val();
    const batch = $('#batch_id').val();
    const year = $('#academic_year_id').val();
    const $target = $('#section_id');
    if (!course || !batch) { $target.html('<option value="">All Sections</option>'); return; }

    $target.html('<option value="">Loading...</option>').prop('disabled', true);

    $.ajax({
        url: '{{ route("roll-numbers.get-sections") }}',
        method: 'GET',
        data: { course_subtype_id: course, batch_id: batch, academic_year_id: year },
        success: function(r) {
            $target.html('<option value="">All Sections</option>').prop('disabled', false);
            if (r.success && r.sections.length) {
                $.each(r.sections, function(_, s) {
                    $target.append(`<option value="${s.id}">${s.name}</option>`);
                });
                @if(($selectedFilters['section_id'] ?? '') != '')
                    $target.val('{{ $selectedFilters["section_id"] }}').trigger('change');
                @endif
            }
        },
        error: function() {
            $target.html('<option value="">Error loading</option>').prop('disabled', true);
        }
    });
}

/* ================================================================
   SORTING
   ================================================================ */

function validateFilterSelection() {
    const values = {
        department_id: $('#department_id').val(),
        course_type: $('#course_type').val(),
        course_subtype_id: $('#course_subtype_id').val(),
        batch_id: $('#batch_id').val(),
        academic_year_id: $('#academic_year_id').val(),
        section_id: $('#section_id').val(),
    };

    const required = [
        ['department_id', 'Department'],
        ['course_type', 'Course'],
        ['course_subtype_id', 'Sub Type'],
        ['batch_id', 'Batch'],
        ['academic_year_id', 'Academic Year'],
        ['section_id', 'Section'],
    ];

    const missing = required.filter(([key]) => !values[key]).map(([, label]) => label);

    if (missing.length) {
        alert('Please complete the full filter chain before loading students. Missing: ' + missing.join(', '));
        return false;
    }

    return true;
}

function sortTable(column) {
    if (!validateFilterSelection()) {
        return;
    }

    const current = $('#sort_by').val();
    const order = $('#sort_order').val();

    let newOrder = 'asc';

    // Same column = toggle ASC/DESC
    if (current === column) {
        newOrder = order === 'asc' ? 'desc' : 'asc';
    }

    $('#sort_by').val(column);
    $('#sort_order').val(newOrder);

    // IMPORTANT:
    // Programmatic form.submit() does not send
    // the submit button's name="filter".
    let $filter = $('#filterForm input[name="filter"]');

    if (!$filter.length) {
        $('<input>', {
            type: 'hidden',
            name: 'filter',
            value: '1'
        }).appendTo('#filterForm');
    } else {
        $filter.val('1');
    }

    $('#filterForm')[0].submit();
}

/* ================================================================
   SELECTION
   ================================================================ */

function toggleSelectAll() {
    const checked = $('#selectAll').prop('checked');
    $('.student-checkbox').prop('checked', checked);
}

function selectAllStudents() {
    $('.student-checkbox').prop('checked', true);
    $('#selectAll').prop('checked', true);
}

function deselectAll() {
    $('.student-checkbox').prop('checked', false);
    $('#selectAll').prop('checked', false);
}

function getSelectedStudents() {
    const selected = [];
    $('.student-checkbox:checked').each(function() {
        selected.push($(this).val());
    });
    return selected;
}

/* ================================================================
   ROLL NUMBER ACTIONS
   ================================================================ */

function editRollNumber(input) {
    if (input.hasAttribute('readonly')) {
        input.removeAttribute('readonly');
        input.focus();
        input.select();
    }
}

function generateRandomRollNumber(input) {
    if (!input) return;
    const num = Math.floor(1000 + Math.random() * 9000);
    input.value = 'RN-' + num;
    input.removeAttribute('readonly');
    input.focus();
}

function saveSingleRollNumber(studentHashId, button) {
    const $container = $(button).closest('.rn-input-group');
    const $input = $container.find('input.roll-number-input');
    const rollNumber = ($input.val() || '').trim();

    if (!rollNumber) {
        alert('Please enter a roll number or click Generate.');
        return;
    }

    const original = $(button).html();
    $(button).html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

    $.ajax({
        url: '{{ route("roll-numbers.assign-single") }}',
        method: 'POST',
        data: {
            student_hash_id: studentHashId,
            roll_number: rollNumber,
            _token: '{{ csrf_token() }}'
        },
        success: function(r) {
            if (r.success) {
                alert('✅ Roll number saved: ' + r.roll_number);
                location.reload();
            } else {
                alert('❌ ' + r.message);
                $(button).html(original).prop('disabled', false);
            }
        },
        error: function(xhr) {
            const msg = xhr.responseJSON?.message || 'Something went wrong';
            alert('❌ ' + msg);
            $(button).html(original).prop('disabled', false);
        }
    });
}

function bulkAssign() {
    const selected = getSelectedStudents();
    if (!selected.length) { alert('Please select at least one student'); return; }
    if (!confirm(`Assign roll numbers to ${selected.length} student(s)?`)) return;

    const btn = document.querySelector('.btn-success');
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Assigning...';

    $.ajax({
        url: '{{ route("roll-numbers.bulk-assign") }}',
        method: 'POST',
        data: { student_ids: selected, _token: '{{ csrf_token() }}' },
        success: function(r) {
            alert(r.success ? '✅ ' + r.message : '❌ ' + r.message);
            if (r.success) location.reload();
        },
        error: function() { alert('❌ Error assigning roll numbers'); },
        complete: function() { btn.disabled = false; btn.innerHTML = orig; }
    });
}

function assignAllStudents() {
    if (!confirm('Assign roll numbers to ALL students without roll numbers?')) return;

    const btn = document.querySelector('.rn-progress-card .btn-success');
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Assigning...';

    const data = $('#filterForm').serialize();

    $.ajax({
        url: '{{ route("roll-numbers.assign-all") }}',
        method: 'POST',
        data: data + '&_token={{ csrf_token() }}',
        success: function(r) {
            alert(r.success ? '✅ ' + r.message : '❌ ' + r.message);
            if (r.success) location.reload();
        },
        error: function() { alert('❌ Error assigning roll numbers'); },
        complete: function() { btn.disabled = false; btn.innerHTML = orig; }
    });
}

function reassignAll() {
    if (!confirm('⚠️ This will REASSIGN roll numbers to ALL students in this section. Continue?')) return;

    const btn = document.querySelector('.btn-warning');
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Reassigning...';

    const data = $('#filterForm').serialize();

    $.ajax({
        url: '{{ route("roll-numbers.reassign-section") }}',
        method: 'POST',
        data: data + '&_token={{ csrf_token() }}',
        success: function(r) {
            alert(r.success ? '✅ ' + r.message : '❌ ' + r.message);
            if (r.success) location.reload();
        },
        error: function() { alert('❌ Error reassigning roll numbers'); },
        complete: function() { btn.disabled = false; btn.innerHTML = orig; }
    });
}

function viewRollNumber(rollNumber) {
    $('#rollNumberDisplay').text(rollNumber);
    $('#rollNumberModal').modal('show');
}

function exportData() {
    const data = $('#filterForm').serialize();
    window.location.href = '{{ route("roll-numbers.export") }}?' + data;
}

function resetFilters() {
    $('#filterForm')[0].reset();
    $('.select2').val('').trigger('change');
    $('#sort_by').val('first_name');
    $('#sort_order').val('asc');
    location.href = '{{ route("roll-numbers.index") }}';
}
</script>
@endsection
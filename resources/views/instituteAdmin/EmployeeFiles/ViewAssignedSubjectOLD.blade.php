@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Assign Subjects To Employee Details</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
/* ===== YOUR ORIGINAL CSS (UNCHANGED) ===== */

body {
    background:#f8fafc;
}
.view-container {
    max-width: 1100px;
    margin: auto;
    background: white;
    border-radius: 12px;
    overflow: hidden;
}
.view-header {
    background:linear-gradient(135deg,#3b82f6,#1d4ed8);
    color:white;
    padding:20px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.view-tabs {
    display:flex;
    background:#f1f5f9;
    border-bottom:1px solid #ddd;
}
.view-tab {
    padding:15px 25px;
    cursor:pointer;
    border:none;
    background:none;
}
.view-tab.active {
    color:#2563eb;
    border-bottom:3px solid #2563eb;
    background:white;
}
.view-content {
    padding:25px;
}
.view-section {
    border:1px solid #ddd;
    border-radius:10px;
    margin-bottom:20px;
}
.section-header {
    background:#f8fafc;
    padding:15px;
    border-bottom:1px solid #ddd;
}
.section-body {
    padding:20px;
}
.info-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:15px;
}
.info-label {
  font-size: 14px;
  color: #5a616f;
}
.info-value {
    background:#f8fafc;
    padding:8px 12px;
    border-left:4px solid #3b82f6;
    border-radius:6px;
}
.document-item {
    background:#f8fafc;
    padding:12px;
    border-radius:8px;
    margin-bottom:10px;
    display:flex;
    justify-content:space-between;
}
</style>
</head>

<body>
<div class="view-container">

    <!-- HEADER -->
    <div class="view-header">
        <h4>
            <i class="bi bi-journal-text"></i>
            <span>Assignment Details</span>
        </h4>

        <button onclick="history.back()" class="btn btn-light btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </button>
    </div>

    <div class="view-content">

        <!-- ================= BASIC DETAILS ================= -->

        <div class="view-section">
            <div class="section-header"><b>Employee & Course Details</b></div>
            <div class="section-body">
                <div class="info-grid">

                    <div>
                        <div class="info-label">Employee Name</div>
                        <div class="info-value">{{ $assignment->employee_name }}</div>
                    </div>

                    <div>
                        <div class="info-label">Department</div>
                        <div class="info-value">{{ $assignment->department_name }}</div>
                    </div>

                    <div>
                        <div class="info-label">Course Type</div>
                        <div class="info-value">{{ $assignment->course_type }}</div>
                    </div>

                    <div>
                        <div class="info-label">Branch</div>
                        <div class="info-value">{{ $assignment->branch_name }}</div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ================= SUBJECT DETAILS ================= -->

        <div class="view-section">
            <div class="section-header"><b>Subject Details</b></div>
            <div class="section-body">
                <div class="info-grid">

                    <div>
                        <div class="info-label">Semester</div>
                        <div class="info-value">{{ $assignment->semester_id }}</div>
                    </div>

                    <div>
                        <div class="info-label">Section</div>
                        <div class="info-value">{{ $assignment->section_name }}</div>
                    </div>

                    <div>
                        <div class="info-label">Subject Type</div>
                        <div class="info-value">{{ $assignment->subject_type }}</div>
                    </div> 

                    <div>
                        <div class="info-label">Subject Display Name</div>
                        <div class="info-value">{{ $assignment->subject_display_name }}</div>
                    </div> 
                    
                    <div>
                        <div class="info-label">Status</div>
                        <div class="info-value">{{ ucfirst($assignment->status) }}</div>
                    </div>

                    <div>
                        <div class="info-label">Remarks</div>
                        <div class="info-value">{{ $assignment->remarks ?? 'Not provided' }}</div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ================= LECTURE SCHEDULE ================= -->

        <div class="view-section">
            <div class="section-header"><b>Lecture Schedule</b></div>
            <div class="section-body">
                <div class="info-grid">

                    <div>
                        <div class="info-label">Frequency</div>
                        <div class="info-value">{{ $assignment->frequency ?? 'N/A' }}</div>
                    </div>

                    <div>
                        <div class="info-label">Assigned Date</div>
                        <div class="info-value">{{ $assignment->assigned_date ?? 'N/A' }}</div>
                    </div>

                    <div>
                        <div class="info-label">Time</div>
                        <div class="info-value">
                            {{ $assignment->start_time }} - {{ $assignment->end_time }}
                        </div>
                    </div>

                    <div>
                        <div class="info-label">Valid From</div>
                        <div class="info-value">{{ $assignment->valid_from }}</div>
                    </div>

                    <div>
                        <div class="info-label">Valid To</div>
                        <div class="info-value">{{ $assignment->valid_to }}</div>
                    </div>

                    <div>
                        <div class="info-label">Days</div>
                        <div class="info-value">
                            {{ !empty($assignment->days_of_week) ? implode(', ', $assignment->days_of_week) : 'N/A' }}
                        </div>
                    </div>

                    <div>
                        <div class="info-label">Location</div>
                        <div class="info-value">{{ $assignment->location ?? 'N/A' }}</div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

@endsection

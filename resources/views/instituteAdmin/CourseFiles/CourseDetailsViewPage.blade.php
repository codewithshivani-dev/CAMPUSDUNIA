@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Course Details</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
/* ===== YOUR ORIGINAL CSS  ===== */

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
        Course Details
    </h4>

    <button onclick="history.back()" class="btn btn-light btn-sm">
        <i class="bi bi-arrow-left"></i> Back
    </button>
</div>

<!-- TABS -->

<div class="view-content">

<div id="basic-tab">

<div class="view-section">
<div class="section-header"><b>Basic Details</b></div>
<div class="section-body">
<div class="info-grid">

<div>
<div class="info-label">Class</div>
<div class="info-value">{{ $c->course_type }}</div>
</div>

<div class="d-none">
<div class="info-label">sub_type</div>
<div class="info-value">{{ $c->sub_type }}</div>
</div>

<div>
<div class="info-label">Mode Of Course</div>
<div class="info-value">{{ $c->mode_of_course }}</div>
</div>

<div>
<div class="info-label">Mode Type</div>
    <div class="info-value">{{ $c->mode_type }}</div>
</div>

<div>
<div class="info-label">Course Duration</div>
<div class="info-value">{{ $c->course_duration }}</div>
</div>

<div>
<div class="info-label">Course Length</div>
<div class="info-value">{{ $c->course_length }}</div>
</div>

<div class="d-none">
<div class="info-label">semesters</div>
<div class="info-value">{{ $c->semesters }}</div>
</div>

<div>
<div class="info-label">Status</div>
<div class="info-value">{{ $c->status }}</div>
</div>

</div>
</div>

</div>

</div>
</div>

</body>
</html>
@endsection
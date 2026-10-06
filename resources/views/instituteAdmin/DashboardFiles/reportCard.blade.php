@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Card System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #4bb543;
            --warning-color: #ff9e00;
            --danger-color: #e63946;
            --info-color: #00b4d8;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gradient-primary: linear-gradient(135deg, #4361ee, #3a0ca3);
            --gradient-success: linear-gradient(135deg, #4bb543, #2a9d40);
            --gradient-info: linear-gradient(135deg, #00b4d8, #0077b6);
            --gradient-warning: linear-gradient(135deg, #ff9e00, #ff7b00);
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --radius: 12px;
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        /*body {*/
        /*    background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);*/
        /*    min-height: 100vh;*/
        /*    padding: 20px 0;*/
        /*}*/
        
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .header-section {
            background: var(--gradient-primary);
            color: white;
            border-radius: var(--radius);
            padding: 25px 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }
        
        .header-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
        }
        
        .system-logo {
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .system-logo i {
            margin-right: 15px;
            font-size: 32px;
        }
        
        .card {
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: none;
            margin-bottom: 25px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background: var(--gradient-primary);
            color: white;
            padding: 20px 25px;
            border-bottom: none;
        }
        
        .card-header h4 {
            margin: 0;
            font-weight: 600;
        }
        
        .section-title {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            color: var(--secondary-color);
        }
        
        .section-title i {
            margin-right: 12px;
            font-size: 24px;
        }
        
        .info-box {
            background: linear-gradient(135deg, #e3f2fd, #f0f4ff);
            border-left: 4px solid var(--primary-color);
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 0 8px 8px 0;
            display: flex;
            align-items: flex-start;
        }
        
        .info-box i {
            color: var(--primary-color);
            font-size: 20px;
            margin-right: 15px;
            margin-top: 2px;
        }
        
        .info-box p {
            margin: 0;
            color: #495057;
        }
        
        .student-profile-card {
            background: white;
            border-radius: var(--radius);
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
            border-top: 5px solid var(--primary-color);
        }
        
        .student-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary-color);
            margin: 0 auto 15px;
            display: block;
        }
        
        .report-card-container {
            background: white;
            border-radius: var(--radius);
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
            border: 2px solid #e9ecef;
        }
        
        .report-card-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .institution-logo {
            font-size: 32px;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .grade-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 600;
            margin: 10px 0;
        }
        
        .grade-a {
            background: linear-gradient(135deg, #4bb543, #2a9d40);
            color: white;
        }
        
        .grade-b {
            background: linear-gradient(135deg, #00b4d8, #0077b6);
            color: white;
        }
        
        .grade-c {
            background: linear-gradient(135deg, #ff9e00, #ff7b00);
            color: white;
        }
        
        .grade-d {
            background: linear-gradient(135deg, #e63946, #d00000);
            color: white;
        }
        
        .grade-f {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
        }
        
        .performance-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 10px;
        }
        
        .performance-excellent {
            background-color: #4bb543;
        }
        
        .performance-good {
            background-color: #00b4d8;
        }
        
        .performance-average {
            background-color: #ff9e00;
        }
        
        .performance-poor {
            background-color: #e63946;
        }
        
        .subject-row {
            padding: 15px;
            margin-bottom: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .subject-row:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }
        
        .score-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            color: white;
        }
        
        .score-high {
            background: var(--gradient-success);
        }
        
        .score-medium {
            background: var(--gradient-warning);
        }
        
        .score-low {
            background: var(--gradient-primary);
        }
        
        .score-circle-large {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 36px;
            color: white;
            margin: 0 auto 20px;
        }
        
        .stat-card {
            background: white;
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
            text-align: center;
            border-top: 4px solid var(--primary-color);
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            margin: 10px 0;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 14px;
        }
        
        .chart-container {
            background: white;
            border-radius: var(--radius);
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
            height: 300px;
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
        }
        
        .btn-success {
            background: var(--gradient-success);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(75, 181, 67, 0.4);
        }
        
        .btn-warning {
            background: var(--gradient-warning);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 158, 0, 0.4);
        }
        
        .btn-pdf {
            background: linear-gradient(135deg, #e63946, #d00000);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
            color: white;
        }
        
        .btn-pdf:hover {
            background: linear-gradient(135deg, #d32f2f, #b71c1c);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(230, 57, 70, 0.4);
            color: white;
        }
        
        .btn-info {
            background: linear-gradient(135deg, #00b4d8, #0077b6);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 180, 216, 0.3);
        }
        
        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 180, 216, 0.4);
            background: linear-gradient(135deg, #0077b6, #005a8c);
            color: white;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 12px 25px;
            border-radius: 8px 8px 0 0;
            margin-right: 5px;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            background-color: white;
            border-bottom: 3px solid var(--primary-color);
        }
        
        .tab-content {
            background: white;
            border-radius: 0 0 var(--radius) var(--radius);
            padding: 25px;
            box-shadow: var(--shadow);
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .comment-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            border-left: 4px solid var(--primary-color);
        }
        
        .signature-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px dashed #dee2e6;
        }
        
        .signature-line {
            width: 200px;
            height: 1px;
            background: #495057;
            margin: 40px auto 10px;
        }
        
        @media (max-width: 768px) {
            .header-section {
                padding: 20px;
            }
            
            .system-logo {
                font-size: 22px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container main-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="system-logo">
                        <i class="fas fa-award"></i>
                        <div>
                            Report Card System
                            <div class="fs-6 fw-normal">Comprehensive Academic Performance Analysis</div>
                        </div>
                    </div>
                    <p class="mb-0 mt-2">View detailed report cards with performance analytics and insights</p>
                </div>
                <div>
                    <button class="btn btn-info" id="backToExamSystemBtn">
                        <i class="fas fa-arrow-left me-2"></i>Back to Exams
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Main Navigation Tabs -->
        <ul class="nav nav-tabs" id="reportTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                    <i class="fas fa-chart-line me-2"></i>Overview
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="report-tab" data-bs-toggle="tab" data-bs-target="#report" type="button" role="tab">
                    <i class="fas fa-file-alt me-2"></i>Report Card
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab">
                    <i class="fas fa-chart-bar me-2"></i>Analytics
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab">
                    <i class="fas fa-users me-2"></i>Students
                </button>
            </li>
        </ul>
        
        <!-- Tab Content -->
        <div class="tab-content" id="reportTabsContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="section-title">
                    <i class="fas fa-chart-line"></i>
                    <h5 class="mb-0">Performance Overview</h5>
                </div>
                
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>View overall academic performance, statistics, and key metrics for all students.</p>
                </div>
                
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-card">
                            <i class="fas fa-users fa-2x text-primary mb-2"></i>
                            <div class="stat-value" id="totalStudents">0</div>
                            <div class="stat-label">Total Students</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-card">
                            <i class="fas fa-chart-bar fa-2x text-success mb-2"></i>
                            <div class="stat-value" id="avgScore">0%</div>
                            <div class="stat-label">Average Score</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-card">
                            <i class="fas fa-medal fa-2x text-warning mb-2"></i>
                            <div class="stat-value" id="topPerformer">-</div>
                            <div class="stat-label">Top Performer</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-card">
                            <i class="fas fa-graduation-cap fa-2x text-info mb-2"></i>
                            <div class="stat-value" id="passRate">0%</div>
                            <div class="stat-label">Pass Rate</div>
                        </div>
                    </div>
                </div>
                
                <!-- Performance Chart -->
                <div class="chart-container">
                    <canvas id="performanceChart"></canvas>
                </div>
                
                <!-- Recent Report Cards -->
                <div class="section-title mt-4">
                    <i class="fas fa-history"></i>
                    <h5 class="mb-0">Recent Report Cards</h5>
                </div>
                
                <div id="recentReports" class="row">
                    <!-- Recent reports will be loaded here -->
                </div>
            </div>
            
            <!-- Report Card Tab -->
            <div class="tab-pane fade" id="report" role="tabpanel">
                <div class="section-title">
                    <i class="fas fa-file-alt"></i>
                    <h5 class="mb-0">Generate Report Card</h5>
                </div>
                
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>Select a student to generate their comprehensive report card with detailed performance analysis.</p>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <!-- Student Selection -->
                        <div class="student-profile-card">
                            <h6 class="mb-3"><i class="fas fa-user-graduate me-2"></i>Select Student</h6>
                            <div class="mb-3">
                                <select class="form-select" id="studentSelect">
                                    <option value="" selected disabled>Select a student</option>
                                    <!-- Students will be populated here -->
                                </select>
                            </div>
                            
                            <!-- Student Info Preview -->
                            <div id="studentPreview" style="display: none;">
                                <img src="https://ui-avatars.com/api/?name=Student&background=4361ee&color=fff" class="student-avatar" id="studentAvatar">
                                <h5 class="text-center" id="previewName">Student Name</h5>
                                <p class="text-center text-muted mb-2" id="previewId">ID: -</p>
                                <div class="text-center">
                                    <button class="btn btn-primary btn-sm" id="generateReportBtn">
                                        <i class="fas fa-file-pdf me-2"></i>Generate Report
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Stats -->
                        <div id="quickStats" class="student-profile-card" style="display: none;">
                            <h6 class="mb-3"><i class="fas fa-chart-pie me-2"></i>Quick Stats</h6>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Overall Score:</span>
                                    <strong id="quickScore">-</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Grade:</span>
                                    <strong id="quickGrade">-</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Rank:</span>
                                    <strong id="quickRank">-</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Attendance:</span>
                                    <strong id="quickAttendance">-</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <!-- Report Card Display -->
                        <div id="reportCardDisplay" style="display: none;">
                            <div class="report-card-container">
                                <div class="report-card-header">
                                    <div class="institution-logo">
                                        <i class="fas fa-university"></i>
                                    </div>
                                    <h3>ACADEMIC REPORT CARD</h3>
                                    <p class="text-muted mb-0">Session 2023-2024 • Term II</p>
                                </div>
                                
                                <!-- Student Info -->
                                <div class="row mb-4">
                                    <div class="col-md-8">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td width="30%"><strong>Student Name:</strong></td>
                                                <td id="reportName">-</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Student ID:</strong></td>
                                                <td id="reportId">-</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Class/Grade:</strong></td>
                                                <td id="reportClass">-</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Roll Number:</strong></td>
                                                <td id="reportRoll">-</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="score-circle-large score-high" id="overallScoreCircle">
                                            85%
                                        </div>
                                        <div class="grade-badge grade-a mt-2" id="overallGrade">
                                            Grade A
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Academic Performance -->
                                <div class="section-title">
                                    <i class="fas fa-book-open"></i>
                                    <h6 class="mb-0">Academic Performance</h6>
                                </div>
                                
                                <div id="subjectPerformance">
                                    <!-- Subjects will be loaded here -->
                                </div>
                                
                                <!-- Overall Summary -->
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="comment-box">
                                            <h6><i class="fas fa-comment me-2"></i>Teacher's Comments</h6>
                                            <p id="teacherComments" class="mb-0">-</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="comment-box">
                                            <h6><i class="fas fa-lightbulb me-2"></i>Recommendations</h6>
                                            <p id="recommendations" class="mb-0">-</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Statistics -->
                                <div class="row mt-4">
                                    <div class="col-md-3 col-6">
                                        <div class="text-center">
                                            <div class="stat-value text-success" id="totalExams">0</div>
                                            <div class="stat-label">Exams Taken</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="text-center">
                                            <div class="stat-value text-primary" id="highestScore">0%</div>
                                            <div class="stat-label">Highest Score</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="text-center">
                                            <div class="stat-value text-warning" id="attendanceRate">0%</div>
                                            <div class="stat-label">Attendance</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="text-center">
                                            <div class="stat-value text-info" id="classRank">-</div>
                                            <div class="stat-label">Class Rank</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Signatures -->
                                <div class="signature-section">
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <div class="signature-line"></div>
                                            <p class="mb-0">Class Teacher</p>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="signature-line"></div>
                                            <p class="mb-0">Principal</p>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="signature-line"></div>
                                            <p class="mb-0">Parent/Guardian</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="action-buttons">
                                <button type="button" class="btn btn-pdf" id="downloadReportBtn">
                                    <i class="fas fa-download me-2"></i>Download PDF
                                </button>
                                <button type="button" class="btn btn-success" id="printReportBtn">
                                    <i class="fas fa-print me-2"></i>Print Report
                                </button>
                                <button type="button" class="btn btn-primary" id="shareReportBtn">
                                    <i class="fas fa-share-alt me-2"></i>Share Report
                                </button>
                            </div>
                        </div>
                        
                        <div id="noReportSelected" class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5>No Student Selected</h5>
                            <p class="text-muted">Select a student from the list to generate their report card</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Analytics Tab -->
            <div class="tab-pane fade" id="analytics" role="tabpanel">
                <div class="section-title">
                    <i class="fas fa-chart-bar"></i>
                    <h5 class="mb-0">Performance Analytics</h5>
                </div>
                
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>Detailed analytics and insights into student performance across different subjects and exams.</p>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="chart-container">
                            <canvas id="subjectComparisonChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="chart-container">
                            <canvas id="gradeDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="chart-container">
                            <canvas id="performanceTrendChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Performance Insights -->
                <div class="section-title mt-4">
                    <i class="fas fa-lightbulb"></i>
                    <h5 class="mb-0">Performance Insights</h5>
                </div>
                
                <div class="row" id="performanceInsights">
                    <!-- Insights will be loaded here -->
                </div>
            </div>
            
            <!-- Students Tab -->
            <div class="tab-pane fade" id="students" role="tabpanel">
                <div class="section-title">
                    <i class="fas fa-users"></i>
                    <h5 class="mb-0">Student Management</h5>
                </div>
                
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>Manage student information, view individual performance, and generate report cards.</p>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <!-- Student Search -->
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" id="studentSearch" placeholder="Search students...">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Student List -->
                        <div id="studentList" style="max-height: 500px; overflow-y: auto;">
                            <!-- Students will be loaded here -->
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <!-- Student Details -->
                        <div id="studentDetails" style="display: none;">
                            <div class="student-profile-card">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <img src="https://ui-avatars.com/api/?name=Student&background=4361ee&color=fff" class="student-avatar" id="detailAvatar">
                                    </div>
                                    <div class="col-md-9">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h4 id="detailName">Student Name</h4>
                                                <p class="text-muted mb-1" id="detailInfo">ID: - | Class: - | Roll: -</p>
                                                <div class="d-flex gap-3">
                                                    <span><i class="fas fa-envelope me-1"></i> <span id="detailEmail">-</span></span>
                                                    <span><i class="fas fa-phone me-1"></i> <span id="detailPhone">-</span></span>
                                                </div>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#" id="editStudentBtn"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                                    <li><a class="dropdown-item" href="#" id="generateReportFromDetailBtn"><i class="fas fa-file-pdf me-2"></i>Generate Report</a></li>
                                                    <li><a class="dropdown-item" href="#" id="viewPerformanceBtn"><i class="fas fa-chart-line me-2"></i>View Performance</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Student Performance -->
                            <div class="section-title mt-4">
                                <i class="fas fa-chart-line"></i>
                                <h6 class="mb-0">Performance Summary</h6>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-3 col-6">
                                    <div class="stat-card">
                                        <div class="stat-value text-success" id="detailTotalScore">0%</div>
                                        <div class="stat-label">Overall Score</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="stat-card">
                                        <div class="stat-value text-primary" id="detailExamsTaken">0</div>
                                        <div class="stat-label">Exams Taken</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="stat-card">
                                        <div class="stat-value text-warning" id="detailAttendance">0%</div>
                                        <div class="stat-label">Attendance</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="stat-card">
                                        <div class="stat-value text-info" id="detailRank">-</div>
                                        <div class="stat-label">Class Rank</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Recent Exams -->
                            <div class="section-title">
                                <i class="fas fa-history"></i>
                                <h6 class="mb-0">Recent Exam Results</h6>
                            </div>
                            
                            <div id="studentExamResults">
                                <!-- Exam results will be loaded here -->
                            </div>
                        </div>
                        
                        <div id="noStudentSelected" class="text-center py-5">
                            <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                            <h5>No Student Selected</h5>
                            <p class="text-muted">Select a student from the list to view details</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Global variables
            let exams = [];
            let results = [];
            let students = [];
            let currentStudent = null;
            let performanceChart = null;
            let subjectChart = null;
            let gradeChart = null;
            let trendChart = null;
            
            // Load data from localStorage
            loadDataFromExamSystem();
            
            // Initialize charts
            initializeCharts();
            
            // Tab switching
            document.querySelectorAll('#reportTabs button').forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-bs-target').replace('#', '');
                    switch(tabId) {
                        case 'overview':
                            updateOverview();
                            break;
                        case 'analytics':
                            updateAnalytics();
                            break;
                        case 'students':
                            loadStudentList();
                            break;
                    }
                });
            });
            
            // Student selection
            document.getElementById('studentSelect').addEventListener('change', function() {
                const studentId = parseInt(this.value);
                if (studentId) {
                    selectStudent(studentId);
                }
            });
            
            // Generate report button
            document.getElementById('generateReportBtn').addEventListener('click', function() {
                generateReportCard();
            });
            
            // Download PDF
            document.getElementById('downloadReportBtn').addEventListener('click', function() {
                downloadReportPDF();
            });
            
            // Print report
            document.getElementById('printReportBtn').addEventListener('click', function() {
                window.print();
            });
            
            // Share report
            document.getElementById('shareReportBtn').addEventListener('click', function() {
                shareReport();
            });
            
            // Student search
            document.getElementById('studentSearch').addEventListener('input', filterStudents);
            
            // Back to Exam System button
            document.getElementById('backToExamSystemBtn').addEventListener('click', function() {
                window.location.href = '/institute/admin/exam-structure';
            });
            
            // Functions
            function loadDataFromExamSystem() {
                // Load data from localStorage (transferred from Exam System)
                exams = JSON.parse(localStorage.getItem('examsData') || '[]');
                results = JSON.parse(localStorage.getItem('examResultsData') || '[]');
                students = JSON.parse(localStorage.getItem('studentsData') || '[]');
                
                // If no data, initialize sample data
                if (students.length === 0) {
                    initializeSampleData();
                }
                
                // Populate student select dropdown
                populateStudentSelect();
                
                // Update overview
                updateOverview();
                
                // Show success message if data was transferred
                const lastSystem = localStorage.getItem('lastSystem');
                if (lastSystem === 'exam') {
                    showSuccessMessage();
                }
            }
            
            function showSuccessMessage() {
                const alertHTML = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Data successfully transferred from Exam Management System!</strong>
                        <p class="mb-0 mt-1">Loaded ${exams.length} exams, ${results.length} results, and ${students.length} students.</p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                
                document.querySelector('.main-container').insertAdjacentHTML('afterbegin', alertHTML);
                
                // Clear the flag
                localStorage.removeItem('lastSystem');
            }
            
            function initializeSampleData() {
                students = [
                    {
                        id: 1,
                        name: "John Smith",
                        studentId: "STU2023001",
                        class: "Grade 10",
                        rollNumber: "25",
                        email: "john.smith@example.com",
                        phone: "+1 234-567-8900",
                        avatar: "https://ui-avatars.com/api/?name=John+Smith&background=4361ee&color=fff",
                        attendance: 92,
                        joinedDate: "2023-09-01"
                    },
                    {
                        id: 2,
                        name: "Emma Johnson",
                        studentId: "STU2023002",
                        class: "Grade 10",
                        rollNumber: "12",
                        email: "emma.j@example.com",
                        phone: "+1 234-567-8901",
                        avatar: "https://ui-avatars.com/api/?name=Emma+Johnson&background=00b4d8&color=fff",
                        attendance: 88,
                        joinedDate: "2023-09-01"
                    },
                    {
                        id: 3,
                        name: "Michael Chen",
                        studentId: "STU2023003",
                        class: "Grade 10",
                        rollNumber: "08",
                        email: "michael.c@example.com",
                        phone: "+1 234-567-8902",
                        avatar: "https://ui-avatars.com/api/?name=Michael+Chen&background=4bb543&color=fff",
                        attendance: 95,
                        joinedDate: "2023-09-01"
                    },
                    {
                        id: 4,
                        name: "Sarah Williams",
                        studentId: "STU2023004",
                        class: "Grade 10",
                        rollNumber: "18",
                        email: "sarah.w@example.com",
                        phone: "+1 234-567-8903",
                        avatar: "https://ui-avatars.com/api/?name=Sarah+Williams&background=e63946&color=fff",
                        attendance: 85,
                        joinedDate: "2023-09-01"
                    }
                ];
                
                // If no exam results, create some sample results
                if (results.length === 0 && exams.length > 0) {
                    results = [
                        {
                            id: 1,
                            examId: 1,
                            examTitle: "Mathematics Basic Test",
                            studentId: 1,
                            studentName: "John Smith",
                            score: 15,
                            totalMarks: 15,
                            percentage: 100,
                            correctAnswers: 3,
                            incorrectAnswers: 0,
                            skippedAnswers: 0,
                            status: "passed",
                            answers: [
                                { questionId: 1, selectedOption: "B", isCorrect: true },
                                { questionId: 2, selectedOption: "A", isCorrect: true },
                                { questionId: 3, selectedOption: "B", isCorrect: true }
                            ],
                            completedAt: new Date().toISOString()
                        },
                        {
                            id: 2,
                            examId: 1,
                            examTitle: "Mathematics Basic Test",
                            studentId: 2,
                            studentName: "Emma Johnson",
                            score: 10,
                            totalMarks: 15,
                            percentage: 67,
                            correctAnswers: 2,
                            incorrectAnswers: 1,
                            skippedAnswers: 0,
                            status: "passed",
                            answers: [
                                { questionId: 1, selectedOption: "B", isCorrect: true },
                                { questionId: 2, selectedOption: "A", isCorrect: true },
                                { questionId: 3, selectedOption: "C", isCorrect: false }
                            ],
                            completedAt: new Date(Date.now() - 86400000).toISOString()
                        }
                    ];
                }
            }
            
            function populateStudentSelect() {
                const select = document.getElementById('studentSelect');
                select.innerHTML = '<option value="" selected disabled>Select a student</option>';
                
                students.forEach(student => {
                    const option = document.createElement('option');
                    option.value = student.id;
                    option.textContent = `${student.name} (${student.studentId})`;
                    select.appendChild(option);
                });
            }
            
            function initializeCharts() {
                // Performance Overview Chart
                const performanceCtx = document.getElementById('performanceChart').getContext('2d');
                performanceChart = new Chart(performanceCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Mathematics', 'Science', 'English', 'History', 'Computer Science'],
                        datasets: [{
                            label: 'Average Score (%)',
                            data: [85, 78, 92, 70, 88],
                            backgroundColor: [
                                'rgba(67, 97, 238, 0.7)',
                                'rgba(75, 181, 67, 0.7)',
                                'rgba(255, 158, 0, 0.7)',
                                'rgba(230, 57, 70, 0.7)',
                                'rgba(0, 180, 216, 0.7)'
                            ],
                            borderColor: [
                                'rgb(67, 97, 238)',
                                'rgb(75, 181, 67)',
                                'rgb(255, 158, 0)',
                                'rgb(230, 57, 70)',
                                'rgb(0, 180, 216)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                title: {
                                    display: true,
                                    text: 'Score (%)'
                                }
                            }
                        }
                    }
                });
                
                // Subject Comparison Chart
                const subjectCtx = document.getElementById('subjectComparisonChart').getContext('2d');
                subjectChart = new Chart(subjectCtx, {
                    type: 'radar',
                    data: {
                        labels: ['Mathematics', 'Science', 'English', 'History', 'Computer Science', 'Physics'],
                        datasets: [{
                            label: 'Class Average',
                            data: [75, 80, 85, 70, 90, 78],
                            backgroundColor: 'rgba(67, 97, 238, 0.2)',
                            borderColor: 'rgb(67, 97, 238)',
                            pointBackgroundColor: 'rgb(67, 97, 238)'
                        }, {
                            label: 'Top Student',
                            data: [95, 88, 92, 85, 96, 90],
                            backgroundColor: 'rgba(75, 181, 67, 0.2)',
                            borderColor: 'rgb(75, 181, 67)',
                            pointBackgroundColor: 'rgb(75, 181, 67)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            r: {
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    }
                });
                
                // Grade Distribution Chart
                const gradeCtx = document.getElementById('gradeDistributionChart').getContext('2d');
                gradeChart = new Chart(gradeCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Grade A', 'Grade B', 'Grade C', 'Grade D', 'Grade F'],
                        datasets: [{
                            data: [25, 35, 20, 15, 5],
                            backgroundColor: [
                                'rgba(75, 181, 67, 0.8)',
                                'rgba(0, 180, 216, 0.8)',
                                'rgba(255, 158, 0, 0.8)',
                                'rgba(230, 57, 70, 0.8)',
                                'rgba(108, 117, 125, 0.8)'
                            ],
                            borderColor: [
                                'rgb(75, 181, 67)',
                                'rgb(0, 180, 216)',
                                'rgb(255, 158, 0)',
                                'rgb(230, 57, 70)',
                                'rgb(108, 117, 125)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right'
                            }
                        }
                    }
                });
                
                // Performance Trend Chart
                const trendCtx = document.getElementById('performanceTrendChart').getContext('2d');
                trendChart = new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: ['Term I', 'Term II', 'Term III', 'Term IV'],
                        datasets: [{
                            label: 'Class Average',
                            data: [72, 75, 78, 82],
                            borderColor: 'rgb(67, 97, 238)',
                            backgroundColor: 'rgba(67, 97, 238, 0.1)',
                            tension: 0.3
                        }, {
                            label: 'Top Student',
                            data: [85, 88, 90, 92],
                            borderColor: 'rgb(75, 181, 67)',
                            backgroundColor: 'rgba(75, 181, 67, 0.1)',
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                title: {
                                    display: true,
                                    text: 'Average Score (%)'
                                }
                            }
                        }
                    }
                });
            }
            
            function updateOverview() {
                // Update statistics
                document.getElementById('totalStudents').textContent = students.length;
                
                // Calculate average score
                let totalScore = 0;
                let scoreCount = 0;
                
                results.forEach(result => {
                    totalScore += result.percentage;
                    scoreCount++;
                });
                
                const avgScore = scoreCount > 0 ? Math.round(totalScore / scoreCount) : 0;
                document.getElementById('avgScore').textContent = `${avgScore}%`;
                
                // Find top performer
                let topPerformer = '-';
                let topScore = 0;
                
                students.forEach(student => {
                    const studentResults = results.filter(r => r.studentId === student.id);
                    
                    if (studentResults.length > 0) {
                        const avg = studentResults.reduce((sum, r) => sum + r.percentage, 0) / studentResults.length;
                        if (avg > topScore) {
                            topScore = avg;
                            topPerformer = student.name.split(' ')[0];
                        }
                    }
                });
                
                document.getElementById('topPerformer').textContent = topPerformer;
                
                // Calculate pass rate (assuming 40% is passing)
                let passed = 0;
                results.forEach(result => {
                    if (result.percentage >= 40) passed++;
                });
                
                const passRate = results.length > 0 ? Math.round((passed / results.length) * 100) : 0;
                document.getElementById('passRate').textContent = `${passRate}%`;
                
                // Update recent reports
                updateRecentReports();
            }
            
            function updateRecentReports() {
                const container = document.getElementById('recentReports');
                container.innerHTML = '';
                
                // Get recent results (last 3)
                const recentResults = results.slice(-3).reverse();
                
                if (recentResults.length === 0) {
                    container.innerHTML = `
                        <div class="col-12">
                            <div class="text-center py-4">
                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                <h5>No Recent Reports</h5>
                                <p class="text-muted">No exam results available to display</p>
                            </div>
                        </div>
                    `;
                    return;
                }
                
                recentResults.forEach(result => {
                    const student = students.find(s => s.id === result.studentId);
                    const col = document.createElement('div');
                    col.className = 'col-md-4 mb-3';
                    
                    col.innerHTML = `
                        <div class="student-profile-card">
                            <div class="d-flex align-items-start">
                                <img src="${student?.avatar || 'https://ui-avatars.com/api/?name=Student&background=4361ee&color=fff'}" 
                                     class="rounded-circle" width="50" height="50">
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="mb-1">${student?.name || 'Student'}</h6>
                                    <p class="text-muted mb-1 small">${result.examTitle}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge ${result.percentage >= 70 ? 'bg-success' : result.percentage >= 40 ? 'bg-warning' : 'bg-danger'}">
                                            ${Math.round(result.percentage)}%
                                        </span>
                                        <small class="text-muted">${new Date(result.completedAt).toLocaleDateString()}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    container.appendChild(col);
                });
            }
            
            function selectStudent(studentId) {
                currentStudent = students.find(s => s.id === studentId);
                if (!currentStudent) return;
                
                // Update preview
                document.getElementById('studentPreview').style.display = 'block';
                document.getElementById('quickStats').style.display = 'block';
                document.getElementById('studentAvatar').src = currentStudent.avatar;
                document.getElementById('previewName').textContent = currentStudent.name;
                document.getElementById('previewId').textContent = `ID: ${currentStudent.studentId}`;
                
                // Calculate quick stats
                const studentResults = getStudentResults(currentStudent.id);
                const overallScore = calculateOverallScore(studentResults);
                const grade = calculateGrade(overallScore);
                const rank = calculateRank(currentStudent.id);
                
                document.getElementById('quickScore').textContent = `${overallScore}%`;
                document.getElementById('quickGrade').textContent = grade;
                document.getElementById('quickRank').textContent = rank;
                document.getElementById('quickAttendance').textContent = `${currentStudent.attendance}%`;
            }
            
            function generateReportCard() {
                if (!currentStudent) {
                    alert('Please select a student first');
                    return;
                }
                
                // Show report card
                document.getElementById('reportCardDisplay').style.display = 'block';
                document.getElementById('noReportSelected').style.display = 'none';
                
                // Get student results
                const studentResults = getStudentResults(currentStudent.id);
                const overallScore = calculateOverallScore(studentResults);
                const grade = calculateGrade(overallScore);
                const rank = calculateRank(currentStudent.id);
                
                // Update report card information
                document.getElementById('reportName').textContent = currentStudent.name;
                document.getElementById('reportId').textContent = currentStudent.studentId;
                document.getElementById('reportClass').textContent = currentStudent.class;
                document.getElementById('reportRoll').textContent = currentStudent.rollNumber;
                
                // Update overall score and grade
                document.getElementById('overallScoreCircle').textContent = `${overallScore}%`;
                document.getElementById('overallScoreCircle').className = `score-circle-large ${overallScore >= 80 ? 'score-high' : overallScore >= 60 ? 'score-medium' : 'score-low'}`;
                
                const gradeElement = document.getElementById('overallGrade');
                gradeElement.textContent = `Grade ${grade}`;
                gradeElement.className = `grade-badge grade-${grade.toLowerCase()}`;
                
                // Update subject performance
                updateSubjectPerformance(studentResults);
                
                // Update teacher comments and recommendations
                document.getElementById('teacherComments').textContent = getTeacherComments(overallScore, grade);
                document.getElementById('recommendations').textContent = getRecommendations(overallScore, studentResults);
                
                // Update statistics
                document.getElementById('totalExams').textContent = studentResults.length;
                document.getElementById('highestScore').textContent = `${getHighestScore(studentResults)}%`;
                document.getElementById('attendanceRate').textContent = `${currentStudent.attendance}%`;
                document.getElementById('classRank').textContent = rank;
            }
            
            function getStudentResults(studentId) {
                return results.filter(r => r.studentId === studentId);
            }
            
            function calculateOverallScore(studentResults) {
                if (studentResults.length === 0) return 0;
                
                const totalScore = studentResults.reduce((sum, result) => sum + result.percentage, 0);
                return Math.round(totalScore / studentResults.length);
            }
            
            function calculateGrade(score) {
                if (score >= 90) return 'A+';
                if (score >= 80) return 'A';
                if (score >= 70) return 'B';
                if (score >= 60) return 'C';
                if (score >= 50) return 'D';
                if (score >= 40) return 'E';
                return 'F';
            }
            
            function calculateRank(studentId) {
                // Calculate average scores for all students
                const studentScores = students.map(student => ({
                    id: student.id,
                    score: calculateOverallScore(getStudentResults(student.id))
                }));
                
                // Sort by score (descending)
                studentScores.sort((a, b) => b.score - a.score);
                
                // Find rank
                const rank = studentScores.findIndex(s => s.id === studentId) + 1;
                return rank <= 0 ? '-' : `${rank}/${students.length}`;
            }
            
            function updateSubjectPerformance(studentResults) {
                const container = document.getElementById('subjectPerformance');
                container.innerHTML = '';
                
                if (studentResults.length === 0) {
                    container.innerHTML = `
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No exam results available for this student.
                        </div>
                    `;
                    return;
                }
                
                // Group results by subject
                const subjectPerformance = {};
                
                studentResults.forEach(result => {
                    const exam = exams.find(e => e.id === result.examId);
                    if (exam) {
                        if (!subjectPerformance[exam.subject]) {
                            subjectPerformance[exam.subject] = {
                                scores: [],
                                exams: []
                            };
                        }
                        subjectPerformance[exam.subject].scores.push(result.percentage);
                        subjectPerformance[exam.subject].exams.push(exam.title);
                    }
                });
                
                // Display subject performance
                Object.entries(subjectPerformance).forEach(([subject, data]) => {
                    const avgScore = Math.round(data.scores.reduce((a, b) => a + b, 0) / data.scores.length);
                    
                    const row = document.createElement('div');
                    row.className = 'subject-row';
                    row.innerHTML = `
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="performance-indicator ${avgScore >= 80 ? 'performance-excellent' : avgScore >= 60 ? 'performance-good' : avgScore >= 40 ? 'performance-average' : 'performance-poor'}"></div>
                                    <span><strong>${subject}</strong></span>
                                </div>
                                <small class="text-muted">${data.exams.join(', ')}</small>
                            </div>
                            <div class="col-md-3">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar ${avgScore >= 80 ? 'bg-success' : avgScore >= 60 ? 'bg-warning' : 'bg-danger'}" 
                                         style="width: ${avgScore}%"></div>
                                </div>
                            </div>
                            <div class="col-md-3 text-end">
                                <strong>${avgScore}%</strong>
                                <span class="badge ${avgScore >= 80 ? 'bg-success' : avgScore >= 60 ? 'bg-warning' : 'bg-danger'} ms-2">
                                    ${calculateGrade(avgScore)}
                                </span>
                            </div>
                        </div>
                    `;
                    container.appendChild(row);
                });
            }
            
            function getTeacherComments(score, grade) {
                if (score >= 90) {
                    return "Outstanding performance! Shows exceptional understanding and application of concepts. Consistently demonstrates excellence in all areas.";
                } else if (score >= 80) {
                    return "Excellent work! Shows strong understanding of concepts and applies them effectively. Consistent performance across all subjects.";
                } else if (score >= 70) {
                    return "Good performance. Shows understanding of core concepts with room for improvement in application. Consistent effort noted.";
                } else if (score >= 60) {
                    return "Satisfactory performance. Understands basic concepts but needs to work on application and deeper understanding.";
                } else if (score >= 40) {
                    return "Needs improvement. Basic understanding present but requires more effort and practice to achieve better results.";
                } else {
                    return "Requires significant improvement. Needs to focus on fundamental concepts and seek additional help when needed.";
                }
            }
            
            function getRecommendations(score, studentResults) {
                if (score >= 80) {
                    return "Consider advanced coursework or enrichment activities. Continue current study habits and explore beyond curriculum.";
                } else if (score >= 60) {
                    return "Focus on consistent practice and review weaker areas. Consider study groups or peer tutoring for challenging topics.";
                } else {
                    return "Develop structured study schedule. Seek help from teachers for difficult concepts. Focus on fundamentals before advanced topics.";
                }
            }
            
            function getHighestScore(studentResults) {
                if (studentResults.length === 0) return 0;
                return Math.max(...studentResults.map(r => r.percentage));
            }
            
            function downloadReportPDF() {
                try {
                    alert('Generating PDF report...');
                    
                    // In a real implementation, you would use jsPDF here
                    // For this example, we'll just show a success message
                    
                    const fileName = `Report_Card_${currentStudent.name.replace(/\s+/g, '_')}_${new Date().getFullYear()}.pdf`;
                    
                    // Create a temporary link to simulate download
                    const link = document.createElement('a');
                    link.href = '#';
                    link.download = fileName;
                    link.click();
                    
                    alert(`✅ Report card downloaded successfully!\n\nFile: ${fileName}`);
                    
                } catch (error) {
                    console.error('Error generating PDF:', error);
                    alert('Error generating PDF. Please try again.');
                }
            }
            
            function shareReport() {
                if (navigator.share) {
                    navigator.share({
                        title: `${currentStudent.name}'s Report Card`,
                        text: `Check out ${currentStudent.name}'s academic performance report for Term II 2023-2024`,
                        url: window.location.href
                    })
                    .then(() => console.log('Report shared successfully'))
                    .catch(error => console.log('Error sharing:', error));
                } else {
                    alert('Share functionality is not available in your browser. You can copy the URL to share.');
                }
            }
            
            function loadStudentList() {
                const container = document.getElementById('studentList');
                container.innerHTML = '';
                
                students.forEach(student => {
                    const studentCard = document.createElement('div');
                    studentCard.className = 'student-profile-card mb-2';
                    studentCard.dataset.id = student.id;
                    studentCard.style.cursor = 'pointer';
                    
                    studentCard.innerHTML = `
                        <div class="d-flex align-items-center">
                            <img src="${student.avatar}" class="rounded-circle" width="45" height="45">
                            <div class="ms-3 flex-grow-1">
                                <h6 class="mb-1">${student.name}</h6>
                                <p class="text-muted mb-0 small">${student.studentId} • ${student.class}</p>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    `;
                    
                    studentCard.addEventListener('click', function() {
                        showStudentDetails(student.id);
                    });
                    
                    container.appendChild(studentCard);
                });
            }
            
            function showStudentDetails(studentId) {
                const student = students.find(s => s.id === studentId);
                if (!student) return;
                
                currentStudent = student;
                
                document.getElementById('studentDetails').style.display = 'block';
                document.getElementById('noStudentSelected').style.display = 'none';
                
                // Update student details
                document.getElementById('detailAvatar').src = student.avatar;
                document.getElementById('detailName').textContent = student.name;
                document.getElementById('detailInfo').textContent = `ID: ${student.studentId} | Class: ${student.class} | Roll: ${student.rollNumber}`;
                document.getElementById('detailEmail').textContent = student.email;
                document.getElementById('detailPhone').textContent = student.phone;
                
                // Calculate and update statistics
                const studentResults = getStudentResults(student.id);
                const overallScore = calculateOverallScore(studentResults);
                
                document.getElementById('detailTotalScore').textContent = `${overallScore}%`;
                document.getElementById('detailExamsTaken').textContent = studentResults.length;
                document.getElementById('detailAttendance').textContent = `${student.attendance}%`;
                document.getElementById('detailRank').textContent = calculateRank(student.id);
                
                // Update exam results
                updateStudentExamResults(studentResults);
            }
            
            function updateStudentExamResults(studentResults) {
                const container = document.getElementById('studentExamResults');
                container.innerHTML = '';
                
                if (studentResults.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No exam results available</p>
                        </div>
                    `;
                    return;
                }
                
                // Show recent results (last 5)
                const recentResults = studentResults.slice(-5).reverse();
                
                recentResults.forEach(result => {
                    const examCard = document.createElement('div');
                    examCard.className = 'student-profile-card mb-2';
                    
                    examCard.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">${result.examTitle}</h6>
                                <p class="text-muted mb-0 small">
                                    ${new Date(result.completedAt).toLocaleDateString()} • 
                                    Score: ${result.score}/${result.totalMarks}
                                </p>
                            </div>
                            <div class="text-end">
                                <div class="grade-badge ${result.percentage >= 70 ? 'grade-a' : result.percentage >= 40 ? 'grade-b' : 'grade-c'}">
                                    ${Math.round(result.percentage)}%
                                </div>
                                <div class="small text-muted mt-1">${result.status}</div>
                            </div>
                        </div>
                    `;
                    
                    container.appendChild(examCard);
                });
            }
            
            function filterStudents() {
                const searchTerm = document.getElementById('studentSearch').value.toLowerCase();
                const studentCards = document.querySelectorAll('#studentList .student-profile-card');
                
                studentCards.forEach(card => {
                    const name = card.querySelector('h6').textContent.toLowerCase();
                    const id = card.querySelector('.text-muted').textContent.toLowerCase();
                    
                    if (name.includes(searchTerm) || id.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
            
            function updateAnalytics() {
                // Update performance insights
                const insightsContainer = document.getElementById('performanceInsights');
                insightsContainer.innerHTML = '';
                
                const insights = [
                    {
                        title: "Mathematics Performance",
                        description: `Average score: ${calculateSubjectAverage('Mathematics')}%`,
                        icon: "fas fa-calculator",
                        color: "primary"
                    },
                    {
                        title: "Science Excellence",
                        description: `Average score: ${calculateSubjectAverage('Science')}%`,
                        icon: "fas fa-flask",
                        color: "success"
                    },
                    {
                        title: "Attendance Impact",
                        description: "Students with >90% attendance score higher on average",
                        icon: "fas fa-calendar-check",
                        color: "warning"
                    },
                    {
                        title: "Study Habits",
                        description: "Regular practice correlates with better performance",
                        icon: "fas fa-book",
                        color: "info"
                    }
                ];
                
                insights.forEach(insight => {
                    const col = document.createElement('div');
                    col.className = 'col-md-6 mb-3';
                    
                    col.innerHTML = `
                        <div class="student-profile-card">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <i class="${insight.icon} fa-2x text-${insight.color}"></i>
                                </div>
                                <div>
                                    <h6 class="mb-2">${insight.title}</h6>
                                    <p class="text-muted mb-0">${insight.description}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    insightsContainer.appendChild(col);
                });
            }
            
            function calculateSubjectAverage(subject) {
                const subjectExams = exams.filter(exam => exam.subject === subject);
                if (subjectExams.length === 0) return 0;
                
                let totalScore = 0;
                let count = 0;
                
                subjectExams.forEach(exam => {
                    const examResults = results.filter(r => r.examId === exam.id);
                    examResults.forEach(result => {
                        totalScore += result.percentage;
                        count++;
                    });
                });
                
                return count > 0 ? Math.round(totalScore / count) : 0;
            }
            
            // Initialize the page
            updateOverview();
            loadStudentList();
            updateAnalytics();
        });
    </script>
</body>
</html>

@endsection
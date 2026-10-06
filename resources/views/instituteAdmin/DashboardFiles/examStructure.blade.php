@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        
        .exam-card {
            background: white;
            border-radius: var(--radius);
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .exam-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .exam-card.active {
            border-left: 4px solid var(--success-color);
            background: #f8fff8;
        }
        
        .exam-status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }
        
        .badge-draft {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .badge-published {
            background-color: #d4edda;
            color: #155724;
        }
        
        .badge-completed {
            background-color: #e2e3e5;
            color: #383d41;
        }
        
        .question-card {
            background: white;
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .question-card:hover {
            border-color: var(--primary-color);
        }
        
        .question-number {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--gradient-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 15px;
        }
        
        .option-container {
            margin: 15px 0;
        }
        
        .option-label {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .option-label:hover {
            background: #dee2e6;
        }
        
        .option-label.selected {
            background: var(--gradient-success);
            color: white;
        }
        
        .option-label.correct {
            background: var(--gradient-success);
            color: white;
        }
        
        .option-label.incorrect {
            background: var(--gradient-warning);
            color: white;
        }
        
        .timer-container {
            background: var(--gradient-warning);
            color: white;
            border-radius: var(--radius);
            padding: 15px 25px;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
        }
        
        .timer-display {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 2px;
            margin: 10px 0;
        }
        
        .progress-container {
            background: white;
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
        }
        
        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .progress-bar {
            height: 10px;
            background: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: var(--gradient-success);
            width: 0%;
            transition: width 0.5s ease;
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
        
        .btn-danger {
            background: linear-gradient(135deg, #e63946, #d00000);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(230, 57, 70, 0.4);
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
        
        .form-control, .form-select, .form-textarea {
            border-radius: 8px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .form-textarea {
            min-height: 100px;
            resize: vertical;
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
        
        .result-card {
            background: white;
            border-radius: var(--radius);
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
            text-align: center;
            border-top: 5px solid var(--success-color);
        }
        
        .score-display {
            font-size: 48px;
            font-weight: 700;
            color: var(--success-color);
            margin: 20px 0;
        }
        
        .score-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: var(--gradient-success);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            font-weight: 700;
            margin: 0 auto 20px;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .question-navigation {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 20px 0;
        }
        
        .nav-btn {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .nav-btn:hover {
            border-color: var(--primary-color);
            background: #f8f9fa;
        }
        
        .nav-btn.active {
            background: var(--gradient-primary);
            color: white;
            border-color: var(--primary-color);
        }
        
        .nav-btn.answered {
            background: var(--gradient-success);
            color: white;
            border-color: var(--success-color);
        }
        
        .nav-btn.marked {
            background: var(--gradient-warning);
            color: white;
            border-color: var(--warning-color);
        }
        
        /* Report Card Button Styles */
        #goToReportCardBtn {
            background: linear-gradient(135deg, #00b4d8, #0077b6);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 180, 216, 0.3);
        }
        
        #goToReportCardBtn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 180, 216, 0.4);
            background: linear-gradient(135deg, #0077b6, #005a8c);
        }
        
        @media (max-width: 768px) {
            .header-section {
                padding: 20px;
            }
            
            .system-logo {
                font-size: 22px;
            }
            
            .timer-display {
                font-size: 24px;
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
                        <i class="fas fa-graduation-cap"></i>
                        <div>
                            Exam Management System
                            <div class="fs-6 fw-normal">Create, Manage, and Take Exams</div>
                        </div>
                    </div>
                    <p class="mb-0 mt-2">Complete exam management system with creation, taking, and result analysis</p>
                </div>
                <div>
                    <button class="btn btn-info" id="goToReportCardBtn">
                        <i class="fas fa-chart-line me-2"></i>View Report Cards
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Main Navigation Tabs -->
        <ul class="nav nav-tabs" id="examTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="create-tab" data-bs-toggle="tab" data-bs-target="#create" type="button" role="tab">
                    <i class="fas fa-plus-circle me-2"></i>Create Exam
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="manage-tab" data-bs-toggle="tab" data-bs-target="#manage" type="button" role="tab">
                    <i class="fas fa-tasks me-2"></i>Manage Exams
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="take-tab" data-bs-toggle="tab" data-bs-target="#take" type="button" role="tab">
                    <i class="fas fa-pencil-alt me-2"></i>Take Exam
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="results-tab" data-bs-toggle="tab" data-bs-target="#results" type="button" role="tab">
                    <i class="fas fa-chart-bar me-2"></i>Results
                </button>
            </li>
        </ul>
        
        <!-- Tab Content -->
        <div class="tab-content" id="examTabsContent">
            <!-- Create Exam Tab -->
            <div class="tab-pane fade show active" id="create" role="tabpanel">
                <div class="section-title">
                    <i class="fas fa-plus-circle"></i>
                    <h5 class="mb-0">Create New Exam</h5>
                </div>
                
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>Create a new exam by providing basic information and adding questions. You can save as draft or publish immediately.</p>
                </div>
                
                <form id="createExamForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="examTitle" class="form-label required">Exam Title</label>
                            <input type="text" class="form-control" id="examTitle" placeholder="Enter exam title" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="examSubject" class="form-label required">Subject</label>
                            <select class="form-select" id="examSubject" required>
                                <option value="" selected disabled>Select Subject</option>
                                <option value="Mathematics">Mathematics</option>
                                <option value="Science">Science</option>
                                <option value="English">English</option>
                                <option value="History">History</option>
                                <option value="Computer Science">Computer Science</option>
                                <option value="Physics">Physics</option>
                                <option value="Chemistry">Chemistry</option>
                                <option value="Biology">Biology</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="examDuration" class="form-label required">Duration (minutes)</label>
                            <input type="number" class="form-control" id="examDuration" min="5" max="300" value="60" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="totalMarks" class="form-label required">Total Marks</label>
                            <input type="number" class="form-control" id="totalMarks" min="10" max="500" value="100" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="passingMarks" class="form-label required">Passing Marks (%)</label>
                            <input type="number" class="form-control" id="passingMarks" min="30" max="100" value="40" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="examDescription" class="form-label">Description</label>
                        <textarea class="form-control form-textarea" id="examDescription" placeholder="Enter exam description and instructions..." rows="3"></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary" id="saveDraftBtn">
                            <i class="fas fa-save me-2"></i>Save as Draft
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle me-2"></i>Create Exam
                        </button>
                    </div>
                </form>
                
                <!-- Questions Section (shown after exam creation) -->
                <div id="questionsSection" style="display: none; margin-top: 40px;">
                    <div class="section-title">
                        <i class="fas fa-question-circle"></i>
                        <h5 class="mb-0">Add Questions to: <span id="currentExamTitle"></span></h5>
                    </div>
                    
                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <p>Add multiple choice questions to your exam. You can add as many questions as needed.</p>
                    </div>
                    
                    <form id="addQuestionForm">
                        <div class="question-card">
                            <div class="mb-3">
                                <label for="questionText" class="form-label required">Question</label>
                                <textarea class="form-control form-textarea" id="questionText" placeholder="Enter your question here..." rows="3" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="questionMarks" class="form-label required">Marks</label>
                                <input type="number" class="form-control" id="questionMarks" min="1" max="20" value="5" required>
                            </div>
                            
                            <div class="option-container">
                                <label class="form-label required">Options</label>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="input-group">
                                            <span class="input-group-text">A</span>
                                            <input type="text" class="form-control" id="optionA" placeholder="Option A" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="input-group">
                                            <span class="input-group-text">B</span>
                                            <input type="text" class="form-control" id="optionB" placeholder="Option B" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="input-group">
                                            <span class="input-group-text">C</span>
                                            <input type="text" class="form-control" id="optionC" placeholder="Option C" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="input-group">
                                            <span class="input-group-text">D</span>
                                            <input type="text" class="form-control" id="optionD" placeholder="Option D" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="correctOption" class="form-label required">Correct Answer</label>
                                <select class="form-select" id="correctOption" required>
                                    <option value="" selected disabled>Select correct option</option>
                                    <option value="A">Option A</option>
                                    <option value="B">Option B</option>
                                    <option value="C">Option C</option>
                                    <option value="D">Option D</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="explanation" class="form-label">Explanation</label>
                                <textarea class="form-control form-textarea" id="explanation" placeholder="Add explanation for the answer (optional)..." rows="2"></textarea>
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Question
                            </button>
                            <button type="button" class="btn btn-success" id="finishExamBtn">
                                <i class="fas fa-check-double me-2"></i>Finish & Publish Exam
                            </button>
                            <button type="button" class="btn btn-secondary" id="cancelQuestionsBtn">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                        </div>
                    </form>
                    
                    <!-- Questions List -->
                    <div id="questionsList" style="display: none; margin-top: 40px;">
                        <div class="section-title">
                            <i class="fas fa-list-ol"></i>
                            <h5 class="mb-0">Added Questions (<span id="questionCount">0</span>)</h5>
                        </div>
                        <div id="questionsListContainer"></div>
                    </div>
                </div>
            </div>
            
            <!-- Manage Exams Tab -->
            <div class="tab-pane fade" id="manage" role="tabpanel">
                <div class="section-title">
                    <i class="fas fa-tasks"></i>
                    <h5 class="mb-0">Manage Exams</h5>
                </div>
                
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>View, edit, delete, or publish your exams. Click on an exam to see details and questions.</p>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="searchExams" placeholder="Search exams...">
                        </div>
                        <div id="examsList" style="max-height: 500px; overflow-y: auto;">
                            <!-- Exams will be loaded here -->
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div id="examDetails" style="display: none;">
                            <div class="exam-card active">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 id="detailExamTitle">Exam Title</h5>
                                        <p class="text-muted mb-2" id="detailExamSubject">Subject</p>
                                        <div class="d-flex gap-3">
                                            <span><i class="fas fa-clock me-1"></i> <span id="detailExamDuration">0</span> min</span>
                                            <span><i class="fas fa-star me-1"></i> <span id="detailExamMarks">0</span> marks</span>
                                            <span><i class="fas fa-percentage me-1"></i> <span id="detailPassingMarks">0</span>% passing</span>
                                        </div>
                                        <p class="mt-2 mb-1" id="detailExamDescription">No description provided</p>
                                        <span class="exam-status-badge" id="detailExamStatus">Draft</span>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" id="editExamBtn"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                            <li><a class="dropdown-item" href="#" id="publishExamBtn"><i class="fas fa-paper-plane me-2"></i>Publish</a></li>
                                            <li><a class="dropdown-item" href="#" id="deleteExamBtn"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                            <li><a class="dropdown-item" href="#" id="viewQuestionsBtn"><i class="fas fa-eye me-2"></i>View Questions</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Questions List for Selected Exam -->
                            <div id="manageQuestionsList" style="display: none; margin-top: 20px;">
                                <div class="section-title">
                                    <i class="fas fa-question-circle"></i>
                                    <h5 class="mb-0">Exam Questions</h5>
                                </div>
                                <div id="manageQuestionsContainer"></div>
                            </div>
                        </div>
                        
                        <div id="noExamSelected" class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5>No Exam Selected</h5>
                            <p class="text-muted">Select an exam from the list to view details</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Take Exam Tab -->
            <div class="tab-pane fade" id="take" role="tabpanel">
                <div class="section-title">
                    <i class="fas fa-pencil-alt"></i>
                    <h5 class="mb-0">Take Exam</h5>
                </div>
                
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>Select an exam to take. You can only take published exams that you haven't completed yet.</p>
                </div>
                
                <!-- Exam Selection -->
                <div id="examSelection" class="row">
                    <!-- Available exams will be loaded here -->
                </div>
                
                <!-- Exam Interface (shown when exam starts) -->
                <div id="examInterface" style="display: none;">
                    <!-- Timer and Progress -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="timer-container">
                                <h6><i class="fas fa-clock me-2"></i>Time Remaining</h6>
                                <div class="timer-display" id="timerDisplay">00:00</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="progress-container">
                                <div class="progress-label">
                                    <span>Progress</span>
                                    <span id="progressText">0/0</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" id="progressFill"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Question Navigation -->
                    <div class="question-navigation" id="questionNav">
                        <!-- Navigation buttons will be added here -->
                    </div>
                    
                    <!-- Current Question -->
                    <div id="currentQuestionContainer">
                        <!-- Question will be loaded here -->
                    </div>
                    
                    <!-- Navigation Buttons -->
                    <div class="action-buttons">
                        <button type="button" class="btn btn-secondary" id="prevQuestionBtn">
                            <i class="fas fa-arrow-left me-2"></i>Previous
                        </button>
                        <button type="button" class="btn btn-warning" id="markReviewBtn">
                            <i class="fas fa-flag me-2"></i>Mark for Review
                        </button>
                        <button type="button" class="btn btn-primary" id="nextQuestionBtn">
                            Next <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                        <button type="button" class="btn btn-success" id="submitExamBtn">
                            <i class="fas fa-paper-plane me-2"></i>Submit Exam
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Results Tab -->
            <div class="tab-pane fade" id="results" role="tabpanel">
                <div class="section-title">
                    <i class="fas fa-chart-bar"></i>
                    <h5 class="mb-0">Exam Results</h5>
                </div>
                
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>View your exam results and performance analysis. Click on a result to see detailed review.</p>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="searchResults" placeholder="Search results...">
                        </div>
                        <div id="resultsList" style="max-height: 500px; overflow-y: auto;">
                            <!-- Results will be loaded here -->
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div id="resultDetails" style="display: none;">
                            <div class="result-card">
                                <h5 id="resultExamTitle">Exam Title</h5>
                                <p class="text-muted mb-3" id="resultExamDate">Date</p>
                                
                                <div class="score-circle" id="scoreCircle">
                                    85%
                                </div>
                                
                                <div class="score-display" id="scoreDisplay">
                                    85/100
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <p class="mb-1"><strong>Correct</strong></p>
                                            <h4 id="correctCount">0</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <p class="mb-1"><strong>Incorrect</strong></p>
                                            <h4 id="incorrectCount">0</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <p class="mb-1"><strong>Skipped</strong></p>
                                            <h4 id="skippedCount">0</h4>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="progress-container mt-3">
                                    <div class="progress-label">
                                        <span>Performance</span>
                                        <span id="performanceText">85%</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" id="performanceFill" style="width: 85%;"></div>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <h6><i class="fas fa-medal me-2"></i>Status: <span id="resultStatus" class="text-success">Passed</span></h6>
                                </div>
                            </div>
                            
                            <!-- Detailed Review -->
                            <div id="detailedReview" style="display: none; margin-top: 30px;">
                                <div class="section-title">
                                    <i class="fas fa-search"></i>
                                    <h5 class="mb-0">Detailed Review</h5>
                                </div>
                                <div id="reviewQuestionsContainer"></div>
                            </div>
                        </div>
                        
                        <div id="noResultSelected" class="text-center py-5">
                            <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                            <h5>No Result Selected</h5>
                            <p class="text-muted">Select a result from the list to view details</p>
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
            let exams = JSON.parse(localStorage.getItem('exams') || '[]');
            let results = JSON.parse(localStorage.getItem('examResults') || '[]');
            let students = JSON.parse(localStorage.getItem('students') || '[]');
            let currentExam = null;
            let currentQuestionIndex = 0;
            let examTimer = null;
            let timeRemaining = 0;
            let examAnswers = [];
            let currentResult = null;
            
            // Initialize sample data
            initializeSampleData();
            
            // Tab switching
            document.querySelectorAll('#examTabs button').forEach(tab => {
                tab.addEventListener('click', function() {
                    // Reset any active exam taking
                    if (examTimer) {
                        clearInterval(examTimer);
                        examTimer = null;
                    }
                    // Load relevant data for each tab
                    const tabId = this.getAttribute('data-bs-target').replace('#', '');
                    switch(tabId) {
                        case 'manage':
                            loadExamsList();
                            break;
                        case 'take':
                            loadAvailableExams();
                            break;
                        case 'results':
                            loadResultsList();
                            break;
                    }
                });
            });
            
            // Create Exam Form Submission
            document.getElementById('createExamForm').addEventListener('submit', function(e) {
                e.preventDefault();
                createNewExam();
            });
            
            // Save as Draft
            document.getElementById('saveDraftBtn').addEventListener('click', function() {
                createNewExam(true);
            });
            
            // Add Question Form Submission
            document.getElementById('addQuestionForm').addEventListener('submit', function(e) {
                e.preventDefault();
                addQuestionToExam();
            });
            
            // Finish Exam
            document.getElementById('finishExamBtn').addEventListener('click', function() {
                finishAndPublishExam();
            });
            
            // Cancel Questions
            document.getElementById('cancelQuestionsBtn').addEventListener('click', function() {
                resetCreateExamForm();
            });
            
            // Exam selection for taking
            document.getElementById('take-tab').addEventListener('click', function() {
                loadAvailableExams();
            });
            
            // Exam taking controls
            document.getElementById('prevQuestionBtn').addEventListener('click', showPreviousQuestion);
            document.getElementById('nextQuestionBtn').addEventListener('click', showNextQuestion);
            document.getElementById('markReviewBtn').addEventListener('click', markForReview);
            document.getElementById('submitExamBtn').addEventListener('click', submitExam);
            
            // Search functionality
            document.getElementById('searchExams').addEventListener('input', filterExams);
            document.getElementById('searchResults').addEventListener('input', filterResults);
            
            // Report Card Button
            document.getElementById('goToReportCardBtn').addEventListener('click', redirectToReportCardSystem);
            
            // Functions
            function initializeSampleData() {
                if (exams.length === 0) {
                    const sampleExams = [
                        {
                            id: 1,
                            title: "Mathematics Basic Test",
                            subject: "Mathematics",
                            duration: 30,
                            totalMarks: 50,
                            passingMarks: 40,
                            description: "Basic mathematics test covering arithmetic and algebra",
                            status: "published",
                            questions: [
                                {
                                    id: 1,
                                    text: "What is 15 + 27?",
                                    options: ["32", "42", "52", "62"],
                                    correctAnswer: "B",
                                    marks: 5,
                                    explanation: "15 + 27 = 42"
                                },
                                {
                                    id: 2,
                                    text: "Solve for x: 2x + 5 = 15",
                                    options: ["x = 5", "x = 10", "x = 7.5", "x = 5.5"],
                                    correctAnswer: "A",
                                    marks: 5,
                                    explanation: "2x = 15 - 5 = 10, so x = 5"
                                },
                                {
                                    id: 3,
                                    text: "What is the square root of 144?",
                                    options: ["11", "12", "13", "14"],
                                    correctAnswer: "B",
                                    marks: 5,
                                    explanation: "12 × 12 = 144"
                                }
                            ],
                            createdAt: new Date().toISOString()
                        },
                        {
                            id: 2,
                            title: "Science Quiz",
                            subject: "Science",
                            duration: 45,
                            totalMarks: 100,
                            passingMarks: 50,
                            description: "General science knowledge quiz",
                            status: "draft",
                            questions: [],
                            createdAt: new Date().toISOString()
                        }
                    ];
                    exams = sampleExams;
                    localStorage.setItem('exams', JSON.stringify(exams));
                }
                
                if (results.length === 0) {
                    const sampleResults = [
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
                    results = sampleResults;
                    localStorage.setItem('examResults', JSON.stringify(results));
                }
                
                if (students.length === 0) {
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
                        }
                    ];
                    localStorage.setItem('students', JSON.stringify(students));
                }
            }
            
            function redirectToReportCardSystem() {
                // Create a modal to confirm redirection
                const modalHTML = `
                    <div class="modal fade" id="redirectModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><i class="fas fa-external-link-alt me-2"></i>Redirect to Report Card System</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>You are about to leave the Exam Management System and go to the Report Card System.</p>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Your exam data will be preserved and available in the Report Card System.
                                    </div>
                                    <div class="mt-3">
                                        <p><strong>Data to be transferred:</strong></p>
                                        <ul>
                                            <li>Exams: ${exams.length}</li>
                                            <li>Results: ${results.length}</li>
                                            <li>Students: ${students.length}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-primary" id="confirmRedirectBtn">
                                        <i class="fas fa-check me-2"></i>Proceed to Report Cards
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Add modal to body
                document.body.insertAdjacentHTML('beforeend', modalHTML);
                
                // Show modal
                const redirectModal = new bootstrap.Modal(document.getElementById('redirectModal'));
                redirectModal.show();
                
                // Handle confirm button click
                document.getElementById('confirmRedirectBtn').addEventListener('click', function() {
                    // Store data for the Report Card System to access
                    localStorage.setItem('examsData', JSON.stringify(exams));
                    localStorage.setItem('examResultsData', JSON.stringify(results));
                    localStorage.setItem('studentsData', JSON.stringify(students));
                    localStorage.setItem('lastSystem', 'exam');
                    
                    // Create a notification
                    const notification = document.createElement('div');
                    notification.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                    notification.style.zIndex = '9999';
                    notification.innerHTML = `
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Redirecting to Report Card System...</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(notification);
                    
                    // Close modal
                    redirectModal.hide();
                    
                    // Redirect after a short delay
                    setTimeout(() => {
                        window.location.href = '/institute/admin/report-card';
                    }, 1000);
                });
                
                // Remove modal from DOM when hidden
                document.getElementById('redirectModal').addEventListener('hidden.bs.modal', function() {
                    this.remove();
                });
            }
            
            // Existing Exam Management System functions
            function createNewExam(isDraft = false) {
                const examData = {
                    id: Date.now(),
                    title: document.getElementById('examTitle').value,
                    subject: document.getElementById('examSubject').value,
                    duration: parseInt(document.getElementById('examDuration').value),
                    totalMarks: parseInt(document.getElementById('totalMarks').value),
                    passingMarks: parseInt(document.getElementById('passingMarks').value),
                    description: document.getElementById('examDescription').value,
                    status: isDraft ? 'draft' : 'published',
                    questions: [],
                    createdAt: new Date().toISOString()
                };
                
                exams.push(examData);
                localStorage.setItem('exams', JSON.stringify(exams));
                
                currentExam = examData;
                
                if (isDraft) {
                    alert('Exam saved as draft successfully!');
                    resetCreateExamForm();
                    document.getElementById('manage-tab').click();
                } else {
                    // Show questions section
                    document.getElementById('questionsSection').style.display = 'block';
                    document.getElementById('currentExamTitle').textContent = examData.title;
                    document.getElementById('createExamForm').reset();
                    
                    // Scroll to questions section
                    document.getElementById('questionsSection').scrollIntoView({ behavior: 'smooth' });
                }
                
                loadExamsList();
            }
            
            function addQuestionToExam() {
                if (!currentExam) {
                    alert('Please create an exam first');
                    return;
                }
                
                const questionData = {
                    id: Date.now(),
                    text: document.getElementById('questionText').value,
                    options: [
                        document.getElementById('optionA').value,
                        document.getElementById('optionB').value,
                        document.getElementById('optionC').value,
                        document.getElementById('optionD').value
                    ],
                    correctAnswer: document.getElementById('correctOption').value,
                    marks: parseInt(document.getElementById('questionMarks').value),
                    explanation: document.getElementById('explanation').value || ''
                };
                
                currentExam.questions.push(questionData);
                
                // Update exam in localStorage
                const examIndex = exams.findIndex(e => e.id === currentExam.id);
                if (examIndex !== -1) {
                    exams[examIndex] = currentExam;
                    localStorage.setItem('exams', JSON.stringify(exams));
                }
                
                // Clear form
                document.getElementById('addQuestionForm').reset();
                
                // Update questions list
                updateQuestionsList();
                
                // Show success message
                alert('Question added successfully!');
            }
            
            function updateQuestionsList() {
                const container = document.getElementById('questionsListContainer');
                container.innerHTML = '';
                
                currentExam.questions.forEach((question, index) => {
                    const questionCard = document.createElement('div');
                    questionCard.className = 'question-card';
                    questionCard.innerHTML = `
                        <div class="d-flex align-items-start">
                            <div class="question-number">${index + 1}</div>
                            <div class="flex-grow-1">
                                <h6>${question.text}</h6>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>A:</strong> ${question.options[0]}</p>
                                        <p class="mb-1"><strong>B:</strong> ${question.options[1]}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>C:</strong> ${question.options[2]}</p>
                                        <p class="mb-1"><strong>D:</strong> ${question.options[3]}</p>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-success">Correct: ${question.correctAnswer}</span>
                                    <span class="badge bg-primary ms-2">Marks: ${question.marks}</span>
                                </div>
                                ${question.explanation ? `<p class="mt-2 text-muted"><small><strong>Explanation:</strong> ${question.explanation}</small></p>` : ''}
                            </div>
                        </div>
                    `;
                    container.appendChild(questionCard);
                });
                
                document.getElementById('questionCount').textContent = currentExam.questions.length;
                document.getElementById('questionsList').style.display = currentExam.questions.length > 0 ? 'block' : 'none';
            }
            
            function finishAndPublishExam() {
                if (!currentExam || currentExam.questions.length === 0) {
                    alert('Please add at least one question before publishing');
                    return;
                }
                
                currentExam.status = 'published';
                
                // Update exam in localStorage
                const examIndex = exams.findIndex(e => e.id === currentExam.id);
                if (examIndex !== -1) {
                    exams[examIndex] = currentExam;
                    localStorage.setItem('exams', JSON.stringify(exams));
                }
                
                alert('Exam published successfully!');
                resetCreateExamForm();
                document.getElementById('manage-tab').click();
            }
            
            function resetCreateExamForm() {
                document.getElementById('createExamForm').reset();
                document.getElementById('questionsSection').style.display = 'none';
                document.getElementById('questionsList').style.display = 'none';
                currentExam = null;
            }
            
            function loadExamsList() {
                const container = document.getElementById('examsList');
                container.innerHTML = '';
                
                exams.forEach(exam => {
                    const examCard = document.createElement('div');
                    examCard.className = 'exam-card';
                    examCard.dataset.id = exam.id;
                    examCard.innerHTML = `
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6>${exam.title}</h6>
                                <p class="text-muted mb-1 small">${exam.subject}</p>
                                <div class="d-flex gap-2 small">
                                    <span><i class="fas fa-clock"></i> ${exam.duration} min</span>
                                    <span><i class="fas fa-star"></i> ${exam.totalMarks} marks</span>
                                    <span><i class="fas fa-question-circle"></i> ${exam.questions.length} questions</span>
                                </div>
                            </div>
                            <span class="exam-status-badge ${exam.status === 'published' ? 'badge-published' : exam.status === 'completed' ? 'badge-completed' : 'badge-draft'}">
                                ${exam.status}
                            </span>
                        </div>
                    `;
                    
                    examCard.addEventListener('click', function() {
                        showExamDetails(exam.id);
                    });
                    
                    container.appendChild(examCard);
                });
            }
            
            function showExamDetails(examId) {
                const exam = exams.find(e => e.id === examId);
                if (!exam) return;
                
                document.getElementById('examDetails').style.display = 'block';
                document.getElementById('noExamSelected').style.display = 'none';
                document.getElementById('manageQuestionsList').style.display = 'none';
                
                document.getElementById('detailExamTitle').textContent = exam.title;
                document.getElementById('detailExamSubject').textContent = exam.subject;
                document.getElementById('detailExamDuration').textContent = exam.duration;
                document.getElementById('detailExamMarks').textContent = exam.totalMarks;
                document.getElementById('detailPassingMarks').textContent = exam.passingMarks;
                document.getElementById('detailExamDescription').textContent = exam.description || 'No description provided';
                document.getElementById('detailExamStatus').textContent = exam.status;
                document.getElementById('detailExamStatus').className = `exam-status-badge ${exam.status === 'published' ? 'badge-published' : exam.status === 'completed' ? 'badge-completed' : 'badge-draft'}`;
                
                // Update button event listeners
                document.getElementById('editExamBtn').onclick = () => editExam(exam.id);
                document.getElementById('publishExamBtn').onclick = () => publishExam(exam.id);
                document.getElementById('deleteExamBtn').onclick = () => deleteExam(exam.id);
                document.getElementById('viewQuestionsBtn').onclick = () => viewExamQuestions(exam.id);
            }
            
            function viewExamQuestions(examId) {
                const exam = exams.find(e => e.id === examId);
                if (!exam) return;
                
                const container = document.getElementById('manageQuestionsContainer');
                container.innerHTML = '';
                
                exam.questions.forEach((question, index) => {
                    const questionCard = document.createElement('div');
                    questionCard.className = 'question-card';
                    questionCard.innerHTML = `
                        <div class="d-flex align-items-start">
                            <div class="question-number">${index + 1}</div>
                            <div class="flex-grow-1">
                                <h6>${question.text}</h6>
                                <div class="row mt-2">
                                    ${question.options.map((option, optIndex) => `
                                        <div class="col-md-6 mb-1">
                                            <div class="d-flex align-items-center">
                                                <div class="option-label ${question.correctAnswer === String.fromCharCode(65 + optIndex) ? 'correct' : ''}">
                                                    ${String.fromCharCode(65 + optIndex)}
                                                </div>
                                                <span>${option}</span>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-success">Correct: ${question.correctAnswer}</span>
                                    <span class="badge bg-primary ms-2">Marks: ${question.marks}</span>
                                </div>
                                ${question.explanation ? `<p class="mt-2 text-muted"><small><strong>Explanation:</strong> ${question.explanation}</small></p>` : ''}
                            </div>
                        </div>
                    `;
                    container.appendChild(questionCard);
                });
                
                document.getElementById('manageQuestionsList').style.display = 'block';
            }
            
            function editExam(examId) {
                alert('Edit functionality would open a form to edit exam details');
            }
            
            function publishExam(examId) {
                const examIndex = exams.findIndex(e => e.id === examId);
                if (examIndex !== -1) {
                    exams[examIndex].status = 'published';
                    localStorage.setItem('exams', JSON.stringify(exams));
                    loadExamsList();
                    showExamDetails(examId);
                    alert('Exam published successfully!');
                }
            }
            
            function deleteExam(examId) {
                if (confirm('Are you sure you want to delete this exam?')) {
                    exams = exams.filter(e => e.id !== examId);
                    localStorage.setItem('exams', JSON.stringify(exams));
                    loadExamsList();
                    document.getElementById('examDetails').style.display = 'none';
                    document.getElementById('noExamSelected').style.display = 'block';
                    alert('Exam deleted successfully!');
                }
            }
            
            function filterExams() {
                const searchTerm = document.getElementById('searchExams').value.toLowerCase();
                const examCards = document.querySelectorAll('#examsList .exam-card');
                
                examCards.forEach(card => {
                    const title = card.querySelector('h6').textContent.toLowerCase();
                    const subject = card.querySelector('.text-muted').textContent.toLowerCase();
                    
                    if (title.includes(searchTerm) || subject.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
            
            function loadAvailableExams() {
                const container = document.getElementById('examSelection');
                container.innerHTML = '';
                
                const publishedExams = exams.filter(exam => exam.status === 'published');
                
                if (publishedExams.length === 0) {
                    container.innerHTML = `
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                <h5>No Exams Available</h5>
                                <p class="text-muted">There are no published exams available to take</p>
                            </div>
                        </div>
                    `;
                    return;
                }
                
                publishedExams.forEach(exam => {
                    // Check if user already completed this exam
                    const userResult = results.find(r => r.examId === exam.id);
                    
                    const col = document.createElement('div');
                    col.className = 'col-md-6 mb-3';
                    col.innerHTML = `
                        <div class="exam-card ${userResult ? 'completed' : ''}">
                            <h5>${exam.title}</h5>
                            <p class="text-muted mb-2">${exam.subject}</p>
                            <div class="d-flex gap-3 mb-2">
                                <span><i class="fas fa-clock me-1"></i> ${exam.duration} min</span>
                                <span><i class="fas fa-star me-1"></i> ${exam.totalMarks} marks</span>
                                <span><i class="fas fa-question-circle me-1"></i> ${exam.questions.length} questions</span>
                            </div>
                            <p class="mb-3">${exam.description || 'No description available'}</p>
                            <button class="btn ${userResult ? 'btn-secondary' : 'btn-primary'} w-100 start-exam-btn" data-id="${exam.id}">
                                ${userResult ? '<i class="fas fa-eye me-2"></i>View Result' : '<i class="fas fa-play me-2"></i>Start Exam'}
                            </button>
                        </div>
                    `;
                    
                    container.appendChild(col);
                });
                
                // Add event listeners to start buttons
                document.querySelectorAll('.start-exam-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const examId = parseInt(this.getAttribute('data-id'));
                        const exam = exams.find(e => e.id === examId);
                        
                        // Check if user already completed this exam
                        const userResult = results.find(r => r.examId === examId);
                        
                        if (userResult) {
                            // Show result
                            showResultDetails(userResult);
                            document.getElementById('results-tab').click();
                        } else {
                            // Start exam
                            startExam(exam);
                        }
                    });
                });
            }
            
            function startExam(exam) {
                currentExam = exam;
                currentQuestionIndex = 0;
                timeRemaining = exam.duration * 60; // Convert to seconds
                examAnswers = new Array(exam.questions.length).fill(null);
                
                // Show exam interface
                document.getElementById('examSelection').style.display = 'none';
                document.getElementById('examInterface').style.display = 'block';
                
                // Initialize timer
                updateTimerDisplay();
                examTimer = setInterval(updateTimer, 1000);
                
                // Initialize question navigation
                initializeQuestionNavigation();
                
                // Show first question
                showQuestion(currentQuestionIndex);
            }
            
            function updateTimer() {
                timeRemaining--;
                updateTimerDisplay();
                
                if (timeRemaining <= 0) {
                    clearInterval(examTimer);
                    submitExam();
                }
            }
            
            function updateTimerDisplay() {
                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;
                document.getElementById('timerDisplay').textContent = 
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    
                // Change color when time is running out
                if (timeRemaining < 300) { // Less than 5 minutes
                    document.getElementById('timerDisplay').style.color = '#e63946';
                }
            }
            
            function initializeQuestionNavigation() {
                const navContainer = document.getElementById('questionNav');
                navContainer.innerHTML = '';
                
                currentExam.questions.forEach((_, index) => {
                    const navBtn = document.createElement('div');
                    navBtn.className = 'nav-btn';
                    navBtn.textContent = index + 1;
                    navBtn.dataset.index = index;
                    
                    navBtn.addEventListener('click', function() {
                        const newIndex = parseInt(this.getAttribute('data-index'));
                        showQuestion(newIndex);
                    });
                    
                    navContainer.appendChild(navBtn);
                });
                
                updateQuestionNavigation();
            }
            
            function updateQuestionNavigation() {
                const navButtons = document.querySelectorAll('#questionNav .nav-btn');
                navButtons.forEach((btn, index) => {
                    btn.classList.remove('active', 'answered', 'marked');
                    
                    if (index === currentQuestionIndex) {
                        btn.classList.add('active');
                    }
                    
                    if (examAnswers[index] !== null) {
                        btn.classList.add('answered');
                    }
                    
                    // You can implement marked for review functionality here
                });
                
                // Update progress
                const answeredCount = examAnswers.filter(answer => answer !== null).length;
                const totalQuestions = currentExam.questions.length;
                const progressPercent = (answeredCount / totalQuestions) * 100;
                
                document.getElementById('progressText').textContent = `${answeredCount}/${totalQuestions}`;
                document.getElementById('progressFill').style.width = `${progressPercent}%`;
            }
            
            function showQuestion(index) {
                if (index < 0 || index >= currentExam.questions.length) return;
                
                currentQuestionIndex = index;
                const question = currentExam.questions[index];
                
                const container = document.getElementById('currentQuestionContainer');
                container.innerHTML = `
                    <div class="question-card">
                        <div class="d-flex align-items-start mb-3">
                            <div class="question-number">${index + 1}</div>
                            <div class="flex-grow-1">
                                <h5>Question ${index + 1}</h5>
                                <p class="text-muted mb-2">Marks: ${question.marks}</p>
                            </div>
                        </div>
                        
                        <p class="mb-4">${question.text}</p>
                        
                        <div class="option-container">
                            ${question.options.map((option, optIndex) => {
                                const optionLetter = String.fromCharCode(65 + optIndex);
                                const isSelected = examAnswers[index] === optionLetter;
                                return `
                                    <div class="d-flex align-items-center mb-3 option-item" data-option="${optionLetter}">
                                        <div class="option-label ${isSelected ? 'selected' : ''}">
                                            ${optionLetter}
                                        </div>
                                        <div class="flex-grow-1">
                                            ${option}
                                        </div>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    </div>
                `;
                
                // Add event listeners to options
                document.querySelectorAll('.option-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const selectedOption = this.getAttribute('data-option');
                        selectAnswer(selectedOption);
                    });
                });
                
                updateQuestionNavigation();
                updateNavigationButtons();
            }
            
            function selectAnswer(option) {
                examAnswers[currentQuestionIndex] = option;
                
                // Update UI
                document.querySelectorAll('.option-item').forEach(item => {
                    const itemOption = item.getAttribute('data-option');
                    const label = item.querySelector('.option-label');
                    label.classList.remove('selected');
                    
                    if (itemOption === option) {
                        label.classList.add('selected');
                    }
                });
                
                updateQuestionNavigation();
            }
            
            function updateNavigationButtons() {
                document.getElementById('prevQuestionBtn').disabled = currentQuestionIndex === 0;
                document.getElementById('nextQuestionBtn').disabled = currentQuestionIndex === currentExam.questions.length - 1;
            }
            
            function showPreviousQuestion() {
                if (currentQuestionIndex > 0) {
                    showQuestion(currentQuestionIndex - 1);
                }
            }
            
            function showNextQuestion() {
                if (currentQuestionIndex < currentExam.questions.length - 1) {
                    showQuestion(currentQuestionIndex + 1);
                }
            }
            
            function markForReview() {
                // Implement mark for review functionality
                const navButton = document.querySelector(`#questionNav .nav-btn[data-index="${currentQuestionIndex}"]`);
                navButton.classList.add('marked');
                alert('Question marked for review');
            }
            
            function submitExam() {
                if (examTimer) {
                    clearInterval(examTimer);
                }
                
                if (!confirm('Are you sure you want to submit the exam?')) {
                    // Restart timer if they cancel
                    examTimer = setInterval(updateTimer, 1000);
                    return;
                }
                
                // Calculate results
                let score = 0;
                let correctAnswers = 0;
                let incorrectAnswers = 0;
                let skippedAnswers = 0;
                
                const answerDetails = [];
                
                currentExam.questions.forEach((question, index) => {
                    const userAnswer = examAnswers[index];
                    const isCorrect = userAnswer === question.correctAnswer;
                    
                    if (userAnswer === null) {
                        skippedAnswers++;
                    } else if (isCorrect) {
                        score += question.marks;
                        correctAnswers++;
                    } else {
                        incorrectAnswers++;
                    }
                    
                    answerDetails.push({
                        questionId: question.id,
                        selectedOption: userAnswer,
                        isCorrect: isCorrect,
                        correctAnswer: question.correctAnswer,
                        explanation: question.explanation
                    });
                });
                
                const percentage = (score / currentExam.totalMarks) * 100;
                const status = percentage >= currentExam.passingMarks ? 'passed' : 'failed';
                
                // Select a random student for demo purposes
                const randomStudent = students[Math.floor(Math.random() * students.length)];
                
                // Create result
                const result = {
                    id: Date.now(),
                    examId: currentExam.id,
                    examTitle: currentExam.title,
                    studentId: randomStudent.id,
                    studentName: randomStudent.name,
                    score: score,
                    totalMarks: currentExam.totalMarks,
                    percentage: percentage,
                    correctAnswers: correctAnswers,
                    incorrectAnswers: incorrectAnswers,
                    skippedAnswers: skippedAnswers,
                    status: status,
                    answers: answerDetails,
                    completedAt: new Date().toISOString()
                };
                
                // Save result
                results.push(result);
                localStorage.setItem('examResults', JSON.stringify(results));
                
                // Show result
                showExamResult(result);
                
                // Reset exam interface
                document.getElementById('examSelection').style.display = 'block';
                document.getElementById('examInterface').style.display = 'none';
                
                // Switch to results tab
                document.getElementById('results-tab').click();
            }
            
            function showExamResult(result) {
                currentResult = result;
                
                document.getElementById('resultDetails').style.display = 'block';
                document.getElementById('noResultSelected').style.display = 'none';
                document.getElementById('detailedReview').style.display = 'none';
                
                document.getElementById('resultExamTitle').textContent = result.examTitle;
                document.getElementById('resultExamDate').textContent = new Date(result.completedAt).toLocaleString();
                document.getElementById('scoreCircle').textContent = `${Math.round(result.percentage)}%`;
                document.getElementById('scoreDisplay').textContent = `${result.score}/${result.totalMarks}`;
                document.getElementById('correctCount').textContent = result.correctAnswers;
                document.getElementById('incorrectCount').textContent = result.incorrectAnswers;
                document.getElementById('skippedCount').textContent = result.skippedAnswers;
                document.getElementById('performanceText').textContent = `${Math.round(result.percentage)}%`;
                document.getElementById('performanceFill').style.width = `${result.percentage}%`;
                document.getElementById('resultStatus').textContent = result.status;
                document.getElementById('resultStatus').className = result.status === 'passed' ? 'text-success' : 'text-danger';
            }
            
            function loadResultsList() {
                const container = document.getElementById('resultsList');
                container.innerHTML = '';
                
                if (results.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-4">
                            <i class="fas fa-trophy fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No results found</p>
                        </div>
                    `;
                    return;
                }
                
                results.forEach(result => {
                    const resultCard = document.createElement('div');
                    resultCard.className = 'exam-card';
                    resultCard.dataset.id = result.id;
                    resultCard.innerHTML = `
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6>${result.examTitle}</h6>
                                <p class="text-muted mb-1 small">${result.studentName}</p>
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="badge ${result.status === 'passed' ? 'bg-success' : 'bg-danger'}">
                                        ${result.status}
                                    </span>
                                    <span class="small">Score: ${result.score}/${result.totalMarks}</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <h5 class="${result.status === 'passed' ? 'text-success' : 'text-danger'}">
                                    ${Math.round(result.percentage)}%
                                </h5>
                                <p class="text-muted small mb-0">${new Date(result.completedAt).toLocaleDateString()}</p>
                            </div>
                        </div>
                    `;
                    
                    resultCard.addEventListener('click', function() {
                        showResultDetails(result.id);
                    });
                    
                    container.appendChild(resultCard);
                });
            }
            
            function showResultDetails(resultId) {
                const result = results.find(r => r.id === resultId);
                if (!result) return;
                
                showExamResult(result);
            }
            
            function filterResults() {
                const searchTerm = document.getElementById('searchResults').value.toLowerCase();
                const resultCards = document.querySelectorAll('#resultsList .exam-card');
                
                resultCards.forEach(card => {
                    const title = card.querySelector('h6').textContent.toLowerCase();
                    const student = card.querySelector('.text-muted').textContent.toLowerCase();
                    
                    if (title.includes(searchTerm) || student.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
            
            // Initialize the page
            loadExamsList();
            loadAvailableExams();
            loadResultsList();
        });
    </script>
</body>
</html>
@endsection
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Meeting Attendance System</title>
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
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --radius: 12px;
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        /* body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            min-height: 100vh;
            padding: 20px 0;
        } */
        
        .main-container {
            max-width: 1200px;
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
        
        .visitor-code-display-large {
            background: var(--gradient-info);
            color: white;
            font-size: 42px;
            font-weight: 700;
            letter-spacing: 3px;
            padding: 20px 40px;
            border-radius: var(--radius);
            text-align: center;
            margin: 20px 0;
            display: inline-block;
            box-shadow: var(--shadow);
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
        
        .visitor-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
        }
        
        .info-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16px;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .meeting-details-card {
            background: white;
            border: 2px solid var(--info-color);
            border-radius: var(--radius);
            padding: 25px;
            margin-top: 20px;
            box-shadow: var(--shadow);
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
            background: linear-gradient(135deg, #ff9e00, #ff7b00);
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
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .meeting-status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-left: 10px;
        }
        
        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .badge-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .badge-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .yes-no-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin: 20px 0;
        }
        
        .yes-no-btn {
            padding: 15px 40px;
            font-size: 18px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .yes-btn {
            background: linear-gradient(135deg, #4bb543, #2a9d40);
            color: white;
        }
        
        .yes-btn:hover {
            background: linear-gradient(135deg, #3fa037, #228b22);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(75, 181, 67, 0.4);
        }
        
        .yes-btn.active {
            background: linear-gradient(135deg, #3fa037, #228b22);
            box-shadow: 0 0 0 3px rgba(75, 181, 67, 0.5);
        }
        
        .no-btn {
            background: linear-gradient(135deg, #e63946, #d00000);
            color: white;
        }
        
        .no-btn:hover {
            background: linear-gradient(135deg, #d32f2f, #b71c1c);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(230, 57, 70, 0.4);
        }
        
        .no-btn.active {
            background: linear-gradient(135deg, #d32f2f, #b71c1c);
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.5);
        }
        
        .employee-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-color);
            margin: 10px auto;
            display: block;
        }
        
        .visitor-photo {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            object-fit: cover;
            border: 3px solid #dee2e6;
        }
        
        .meeting-timeline {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 20px 0;
            position: relative;
        }
        
        .timeline-step {
            text-align: center;
            position: relative;
            z-index: 2;
            flex: 1;
        }
        
        .timeline-step.completed .step-icon {
            background: var(--gradient-success);
            color: white;
        }
        
        .timeline-step.active .step-icon {
            background: var(--gradient-primary);
            color: white;
            transform: scale(1.1);
        }
        
        .step-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 20px;
            transition: all 0.3s ease;
        }
        
        .step-label {
            font-size: 12px;
            color: #6c757d;
            font-weight: 500;
        }
        
        .meeting-timeline::before {
            content: '';
            position: absolute;
            top: 25px;
            left: 0;
            right: 0;
            height: 3px;
            background-color: #e9ecef;
            z-index: 1;
        }
        
        .visitor-code-examples {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        
        .visitor-code-example {
            background: rgba(67, 97, 238, 0.1);
            border: 2px dashed var(--primary-color);
            padding: 8px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .visitor-code-example:hover {
            background: rgba(67, 97, 238, 0.2);
            transform: translateY(-2px);
        }
        
        .no-meeting-alert {
            background: linear-gradient(135deg, #ffeaa7, #fab1a0);
            border-left: 4px solid #e17055;
            padding: 20px;
            border-radius: 0 8px 8px 0;
            margin-top: 20px;
        }
        
        @media (max-width: 768px) {
            .header-section {
                padding: 20px;
            }
            
            .system-logo {
                font-size: 22px;
            }
            
            .visitor-code-display-large {
                font-size: 28px;
                padding: 12px 20px;
            }
            
            .yes-no-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .yes-no-btn {
                width: 100%;
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
            <div class="system-logo">
                <i class="fas fa-handshake"></i>
                <div>
                    Employee Meeting Attendance
                    <div class="fs-6 fw-normal">Confirm meeting completion with visitors</div>
                </div>
            </div>
            <p class="mb-0 mt-2">Enter visitor code to confirm meeting attendance</p>
        </div>
        
        <!-- Main Card -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-user-tie me-2"></i>Meeting Attendance Confirmation</h4>
            </div>
            <div class="card-body">
                <!-- Visitor Code Entry -->
                <div class="section-title">
                    <i class="fas fa-search"></i>
                    <h5 class="mb-0">Enter Visitor Code</h5>
                </div>
                
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>Enter the visitor code provided by the visitor to confirm meeting attendance.</p>
                </div>
                
                <div class="mb-4">
                    <label for="visitorCodeInput" class="form-label required">Visitor Code</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="visitorCodeInput" placeholder="Enter visitor code (e.g., VR-A1B2C3)" required>
                        <button class="btn btn-primary" type="button" id="fetchVisitorMeetingBtn">
                            <i class="fas fa-search me-2"></i>Find Meeting
                        </button>
                    </div>
                    <small class="text-muted">The visitor should provide you with their unique visitor code</small>
                    
                    <!-- Visitor Code Examples -->
                    <div class="visitor-code-examples mt-3">
                        <div class="info-label mb-2">Test Visitor Codes:</div>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="visitor-code-example" onclick="document.getElementById('visitorCodeInput').value='VR-A1B2C3'">VR-A1B2C3</span>
                            <span class="visitor-code-example" onclick="document.getElementById('visitorCodeInput').value='VR-X9Y8Z7'">VR-X9Y8Z7</span>
                            <span class="visitor-code-example" onclick="document.getElementById('visitorCodeInput').value='VR-M5N6O7'">VR-M5N6O7</span>
                        </div>
                    </div>
                </div>
                
                <!-- No Meeting Assignment Alert -->
                <div id="noMeetingAlert" class="no-meeting-alert" style="display: none;">
                    <h5><i class="fas fa-exclamation-triangle me-2"></i>Meeting Assignment Not Found</h5>
                    <p class="mb-2">No meeting assignment found for this visitor code.</p>
                    <p class="mb-0"><strong>Possible reasons:</strong></p>
                    <ul class="mb-0 mt-1">
                        <li>The visitor hasn't been assigned to an employee yet</li>
                        <li>The meeting has already been confirmed or cancelled</li>
                        <li>There's an error in the visitor code</li>
                    </ul>
                    <div class="mt-3">
                        <button class="btn btn-outline-warning" id="createNewMeetingBtn">
                            <i class="fas fa-plus me-2"></i>Create New Meeting Assignment
                        </button>
                    </div>
                </div>
                
                <!-- Meeting Details -->
                <div id="meetingDetailsContainer" style="display: none;">
                    <!-- Large Visitor Code Display -->
                    <div class="text-center my-4">
                        <div class="visitor-code-display-large" id="visitorCodeDisplayLarge">VR-000000</div>
                        <p class="text-muted mt-2">Visitor Code - Please verify with the visitor</p>
                    </div>
                    
                    <!-- Meeting Timeline -->
                    <div class="meeting-timeline">
                        <div class="timeline-step completed">
                            <div class="step-icon">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <div class="step-label">Scheduled</div>
                        </div>
                        <div class="timeline-step active">
                            <div class="step-icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="step-label">Meeting</div>
                        </div>
                        <div class="timeline-step">
                            <div class="step-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="step-label">Completed</div>
                        </div>
                    </div>
                    
                    <!-- Employee & Visitor Info -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="meeting-details-card">
                                <h6><i class="fas fa-user me-2"></i>Visitor Details</h6>
                                <img src="https://ui-avatars.com/api/?name=Visitor&background=00b4d8&color=fff" class="employee-photo" id="visitorPhoto">
                                <div class="text-center mt-2">
                                    <h5 id="visitorName">Visitor Name</h5>
                                    <p class="text-muted mb-1" id="visitorPurpose">Purpose</p>
                                    <p class="mb-1" id="visitorContact">Contact</p>
                                    <p class="mb-0"><small>Code: <strong id="visitorCode">VR-000000</strong></small></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="meeting-details-card">
                                <h6><i class="fas fa-user-tie me-2"></i>Employee Details</h6>
                                <img src="https://ui-avatars.com/api/?name=Employee&background=4361ee&color=fff" class="employee-photo" id="employeePhoto">
                                <div class="text-center mt-2">
                                    <h5 id="employeeName">Employee Name</h5>
                                    <p class="text-muted mb-1" id="employeePosition">Position</p>
                                    <p class="mb-0" id="employeeDepartment">Department</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Meeting Information -->
                    <div class="meeting-details-card mt-4">
                        <h6><i class="fas fa-calendar-alt me-2"></i>Meeting Information</h6>
                        <div class="visitor-info-grid">
                            <div class="info-item">
                                <div class="info-label">Meeting Title</div>
                                <div class="info-value" id="meetingTitle">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Meeting Date</div>
                                <div class="info-value" id="meetingDate">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Meeting Time</div>
                                <div class="info-value" id="meetingTime">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Location</div>
                                <div class="info-value" id="meetingLocation">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Duration</div>
                                <div class="info-value" id="meetingDuration">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Status</div>
                                <div class="info-value">
                                    <span class="meeting-status-badge badge-pending" id="meetingStatus">Pending</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <div class="info-label">Meeting Notes</div>
                            <div class="info-value" id="meetingNotes">No notes provided</div>
                        </div>
                    </div>
                    
                    <!-- Attendance Confirmation -->
                    <div class="meeting-details-card mt-4">
                        <div class="section-title">
                            <i class="fas fa-user-check"></i>
                            <h5 class="mb-0">Attendance Confirmation</h5>
                        </div>
                        
                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            <p>Please confirm if the meeting with the visitor was completed successfully.</p>
                        </div>
                        
                        <div class="text-center mb-4">
                            <h5>Did you meet with the visitor?</h5>
                            <p class="text-muted">Select Yes if the meeting was completed, or No if it was cancelled</p>
                            
                            <div class="yes-no-buttons">
                                <button type="button" class="yes-no-btn yes-btn" id="yesBtn">
                                    <i class="fas fa-check me-2"></i>Yes, Meeting Completed
                                </button>
                                <button type="button" class="yes-no-btn no-btn" id="noBtn">
                                    <i class="fas fa-times me-2"></i>No, Meeting Cancelled
                                </button>
                            </div>
                        </div>
                        
                        <!-- Additional Notes (shown when Yes is selected) -->
                        <div id="additionalNotesSection" style="display: none;">
                            <div class="mb-3">
                                <label for="meetingOutcome" class="form-label">Meeting Outcome / Notes</label>
                                <textarea class="form-control form-textarea" id="meetingOutcome" placeholder="Briefly describe the meeting outcome or any important notes..." rows="3"></textarea>
                                <small class="text-muted">Optional: Add notes about the meeting discussion or outcome</small>
                            </div>
                        </div>
                        
                        <!-- Cancellation Reason (shown when No is selected) -->
                        <div id="cancellationReasonSection" style="display: none;">
                            <div class="mb-3">
                                <label for="cancellationReason" class="form-label required">Cancellation Reason</label>
                                <select class="form-select" id="cancellationReason">
                                    <option value="" selected disabled>Select reason for cancellation</option>
                                    <option value="visitor_no_show">Visitor didn't show up</option>
                                    <option value="employee_unavailable">Employee unavailable</option>
                                    <option value="rescheduled">Meeting rescheduled</option>
                                    <option value="emergency">Emergency situation</option>
                                    <option value="other">Other reason</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="cancellationNotes" class="form-label">Additional Notes</label>
                                <textarea class="form-control form-textarea" id="cancellationNotes" placeholder="Add any additional notes about the cancellation..." rows="2"></textarea>
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <button type="button" class="btn btn-secondary" id="backBtn">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </button>
                            <button type="button" class="btn btn-success" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>Submit
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- New Meeting Form (shown when no meeting found) -->
                <div id="newMeetingFormContainer" class="meeting-details-card mt-4" style="display: none;">
                    <div class="section-title">
                        <i class="fas fa-plus-circle"></i>
                        <h5 class="mb-0">Create New Meeting Assignment</h5>
                    </div>
                    
                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <p>Create a new meeting assignment for this visitor. Fill in the meeting details below.</p>
                    </div>
                    
                    <form id="newMeetingForm">
                        <input type="hidden" id="visitorCodeForNewMeeting" value="">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="newMeetingTitle" class="form-label required">Meeting Title</label>
                                    <input type="text" class="form-control" id="newMeetingTitle" placeholder="Enter meeting title" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="newEmployeeId" class="form-label required">Your Employee ID</label>
                                    <input type="text" class="form-control" id="newEmployeeId" placeholder="Enter your employee ID" required>
                                    <small class="text-muted">e.g., EMP001, EMP002, etc.</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="newMeetingDate" class="form-label required">Meeting Date</label>
                                    <input type="date" class="form-control" id="newMeetingDate" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="newMeetingTime" class="form-label required">Meeting Time</label>
                                    <input type="time" class="form-control" id="newMeetingTime" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="newMeetingDuration" class="form-label required">Duration (minutes)</label>
                                    <input type="number" class="form-control" id="newMeetingDuration" min="15" max="480" value="60" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="newMeetingLocation" class="form-label required">Location</label>
                            <input type="text" class="form-control" id="newMeetingLocation" placeholder="Enter meeting location" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="newMeetingNotes" class="form-label">Meeting Notes</label>
                            <textarea class="form-control form-textarea" id="newMeetingNotes" placeholder="Add any notes about the meeting..." rows="3"></textarea>
                        </div>
                        
                        <div class="action-buttons">
                            <button type="button" class="btn btn-secondary" id="cancelNewMeetingBtn">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                            <button type="button" class="btn btn-success" id="createMeetingBtn">
                                <i class="fas fa-check-circle me-2"></i>Create Meeting
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Success Message -->
                <div id="successMessage" class="alert alert-success mt-4" style="display: none;">
                    <h5><i class="fas fa-check-circle me-2"></i>Attendance Confirmed Successfully!</h5>
                    <p class="mb-0" id="successDetails"></p>
                    <div class="mt-3">
                        <button class="btn btn-outline-success" id="newAttendanceBtn">
                            <i class="fas fa-plus me-2"></i>Confirm Another Meeting
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get visitor data from localStorage (shared with front desk system)
            let allVisitors = JSON.parse(localStorage.getItem('visitorRegistrationData') || '[]');
            let currentEmployee = null;
            let currentVisitor = null;
            let currentMeeting = null;
            let selectedAttendance = null; // 'yes' or 'no'
            
            // Sample employee data (for display only - employee is already assigned in visitor data)
            const employees = {
                'EMP001': { id: 1, name: 'Sarah Johnson', position: 'HR Manager', department: 'Human Resources', photo: 'https://ui-avatars.com/api/?name=Sarah+Johnson&background=4361ee&color=fff' },
                'EMP002': { id: 2, name: 'Michael Chen', position: 'Recruitment Specialist', department: 'Human Resources', photo: 'https://ui-avatars.com/api/?name=Michael+Chen&background=4361ee&color=fff' },
                'EMP003': { id: 3, name: 'Emily Davis', position: 'Training Coordinator', department: 'Human Resources', photo: 'https://ui-avatars.com/api/?name=Emily+Davis&background=4361ee&color=fff' },
                'EMP004': { id: 4, name: 'Robert Wilson', position: 'IT Director', department: 'Information Technology', photo: 'https://ui-avatars.com/api/?name=Robert+Wilson&background=4361ee&color=fff' },
                'EMP005': { id: 5, name: 'David Miller', position: 'Software Developer', department: 'Information Technology', photo: 'https://ui-avatars.com/api/?name=David+Miller&background=4361ee&color=fff' },
                'EMP006': { id: 6, name: 'Lisa Thompson', position: 'System Administrator', department: 'Information Technology', photo: 'https://ui-avatars.com/api/?name=Lisa+Thompson&background=4361ee&color=fff' },
                'EMP007': { id: 7, name: 'James Anderson', position: 'Sales Manager', department: 'Sales & Marketing', photo: 'https://ui-avatars.com/api/?name=James+Anderson&background=4361ee&color=fff' },
                'EMP008': { id: 8, name: 'Maria Garcia', position: 'Account Executive', department: 'Sales & Marketing', photo: 'https://ui-avatars.com/api/?name=Maria+Garcia&background=4361ee&color=fff' },
                'EMP009': { id: 9, name: 'Thomas Lee', position: 'Marketing Specialist', department: 'Sales & Marketing', photo: 'https://ui-avatars.com/api/?name=Thomas+Lee&background=4361ee&color=fff' }
            };
            
            // Initialize if no data exists
            function initializeSampleData() {
                // Check if we have any meetings in visitor data
                let hasMeetings = false;
                for (let visitor of allVisitors) {
                    if (visitor.frontDeskActions && visitor.frontDeskActions.some(action => action.type === 'assigned_to_employee')) {
                        hasMeetings = true;
                        break;
                    }
                }
                
                if (!hasMeetings) {
                    console.log('Creating sample meeting data...');
                    
                    // Add sample meetings to existing visitors
                    const sampleMeetings = [
                        {
                            visitorCode: 'VR-A1B2C3',
                            employeeId: 'EMP001',
                            meetingTitle: 'Job Interview Discussion',
                            meetingDate: new Date().toISOString().split('T')[0],
                            meetingTime: '14:30',
                            location: 'Conference Room A',
                            duration: '60',
                            notes: 'Discussing candidate qualifications for the open position',
                            status: 'pending'
                        },
                        {
                            visitorCode: 'VR-X9Y8Z7',
                            employeeId: 'EMP004',
                            meetingTitle: 'IT Infrastructure Review',
                            meetingDate: new Date().toISOString().split('T')[0],
                            meetingTime: '11:00',
                            location: 'IT Department - Room 205',
                            duration: '90',
                            notes: 'Review current IT infrastructure and future upgrades',
                            status: 'pending'
                        },
                        {
                            visitorCode: 'VR-M5N6O7',
                            employeeId: 'EMP007',
                            meetingTitle: 'Sales Partnership Discussion',
                            meetingDate: new Date().toISOString().split('T')[0],
                            meetingTime: '16:00',
                            location: 'Sales Conference Room',
                            duration: '120',
                            notes: 'Discuss potential partnership opportunities',
                            status: 'pending'
                        }
                    ];
                    
                    // Add meetings to visitors
                    sampleMeetings.forEach(meeting => {
                        const visitor = allVisitors.find(v => v.code === meeting.visitorCode);
                        if (visitor) {
                            if (!visitor.frontDeskActions) {
                                visitor.frontDeskActions = [];
                            }
                            
                            // Find employee details
                            const employee = employees[meeting.employeeId];
                            
                            // Add meeting assignment
                            visitor.frontDeskActions.push({
                                type: 'assigned_to_employee',
                                timestamp: new Date().toISOString(),
                                department: employee.department.toLowerCase(),
                                employee: {
                                    id: employee.id,
                                    name: employee.name,
                                    position: employee.position,
                                    employeeId: meeting.employeeId
                                },
                                meetingTitle: meeting.meetingTitle,
                                meetingDate: meeting.meetingDate,
                                meetingTime: meeting.meetingTime,
                                location: meeting.location,
                                duration: meeting.duration,
                                notes: meeting.notes,
                                status: meeting.status,
                                meetingId: 'MTG-' + Date.now().toString().substr(-6)
                            });
                        }
                    });
                    
                    // Save to localStorage
                    localStorage.setItem('visitorRegistrationData', JSON.stringify(allVisitors));
                    console.log('Sample meeting data created');
                }
            }
            
            // Call initialization
            initializeSampleData();
            
            // Event Listeners
            document.getElementById('fetchVisitorMeetingBtn').addEventListener('click', fetchVisitorMeeting);
            
            // Enter key support for visitor code input
            document.getElementById('visitorCodeInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    fetchVisitorMeeting();
                }
            });
            
            // Yes/No buttons
            document.getElementById('yesBtn').addEventListener('click', function() {
                selectAttendance('yes');
            });
            
            document.getElementById('noBtn').addEventListener('click', function() {
                selectAttendance('no');
            });
            
            // Submit button (renamed from Confirm Attendance)
            document.getElementById('submitBtn').addEventListener('click', submitAttendance);
            
            // Back button
            document.getElementById('backBtn').addEventListener('click', function() {
                resetForm();
            });
            
            // New attendance button
            document.getElementById('newAttendanceBtn').addEventListener('click', function() {
                resetForm();
            });
            
            // Create new meeting button (when no meeting found)
            document.getElementById('createNewMeetingBtn').addEventListener('click', function() {
                showNewMeetingForm();
            });
            
            // Cancel new meeting button
            document.getElementById('cancelNewMeetingBtn').addEventListener('click', function() {
                resetForm();
            });
            
            // Create meeting button
            document.getElementById('createMeetingBtn').addEventListener('click', function() {
                createNewMeeting();
            });
            
            // Functions
            function fetchVisitorMeeting() {
                const visitorCode = document.getElementById('visitorCodeInput').value.trim().toUpperCase();
                
                if (!visitorCode) {
                    alert('Please enter a visitor code');
                    return;
                }
                
                // Hide all sections first
                document.getElementById('meetingDetailsContainer').style.display = 'none';
                document.getElementById('noMeetingAlert').style.display = 'none';
                document.getElementById('newMeetingFormContainer').style.display = 'none';
                document.getElementById('successMessage').style.display = 'none';
                
                // Find visitor by code
                const visitor = allVisitors.find(v => v.code === visitorCode);
                
                if (!visitor) {
                    // Show visitor not found alert
                    document.getElementById('noMeetingAlert').style.display = 'block';
                    document.getElementById('noMeetingAlert').innerHTML = `
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Visitor Not Found</h5>
                        <p class="mb-2">Visitor with code <strong>"${visitorCode}"</strong> not found in the system.</p>
                        <p class="mb-0"><strong>Possible reasons:</strong></p>
                        <ul class="mb-0 mt-1">
                            <li>The visitor code is incorrect</li>
                            <li>The visitor hasn't registered at the front desk yet</li>
                            <li>There's a typo in the visitor code</li>
                        </ul>
                        <div class="mt-3">
                            <button class="btn btn-outline-warning" onclick="resetForm()">
                                <i class="fas fa-redo me-2"></i>Try Again
                            </button>
                        </div>
                    `;
                    return;
                }
                
                currentVisitor = visitor;
                
                // Find meeting assigned to this visitor
                let meetingAssignment = null;
                if (visitor.frontDeskActions) {
                    for (let action of visitor.frontDeskActions) {
                        if (action.type === 'assigned_to_employee' && 
                            (!action.meetingStatus || action.meetingStatus === 'pending')) {
                            meetingAssignment = action;
                            break;
                        }
                    }
                }
                
                if (!meetingAssignment) {
                    // Show "Meeting Assignment Not Found" alert
                    document.getElementById('noMeetingAlert').style.display = 'block';
                    document.getElementById('visitorCodeForNewMeeting').value = visitorCode;
                    return;
                }
                
                // Get employee details
                const employeeId = meetingAssignment.employee.employeeId;
                currentEmployee = employees[employeeId];
                
                if (!currentEmployee) {
                    // Show alert for employee not found
                    document.getElementById('noMeetingAlert').style.display = 'block';
                    document.getElementById('noMeetingAlert').innerHTML = `
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Employee Not Found</h5>
                        <p class="mb-2">The employee assigned to this meeting (${employeeId}) is not found in the system.</p>
                        <div class="mt-3">
                            <button class="btn btn-outline-warning" id="createNewMeetingBtn">
                                <i class="fas fa-plus me-2"></i>Create New Meeting Assignment
                            </button>
                        </div>
                    `;
                    return;
                }
                
                currentMeeting = {
                    visitor: visitor,
                    meeting: meetingAssignment,
                    meetingId: meetingAssignment.meetingId || 'MTG-' + Date.now().toString().substr(-6)
                };
                
                // Display meeting details
                displayMeetingDetails();
            }
            
            function displayMeetingDetails() {
                if (!currentMeeting || !currentEmployee || !currentVisitor) return;
                
                // Update visitor details
                document.getElementById('visitorCodeDisplayLarge').textContent = currentVisitor.code;
                document.getElementById('visitorCode').textContent = currentVisitor.code;
                document.getElementById('visitorName').textContent = currentVisitor.name;
                document.getElementById('visitorPurpose').textContent = currentVisitor.purpose;
                document.getElementById('visitorContact').textContent = currentVisitor.contact;
                document.getElementById('visitorPhoto').src = currentVisitor.visitorPhoto || 
                    `https://ui-avatars.com/api/?name=${encodeURIComponent(currentVisitor.name)}&background=00b4d8&color=fff`;
                
                // Update employee details
                document.getElementById('employeeName').textContent = currentEmployee.name;
                document.getElementById('employeePosition').textContent = currentEmployee.position;
                document.getElementById('employeeDepartment').textContent = currentEmployee.department;
                document.getElementById('employeePhoto').src = currentEmployee.photo;
                
                // Update meeting details
                document.getElementById('meetingTitle').textContent = currentMeeting.meeting.meetingTitle;
                document.getElementById('meetingDate').textContent = currentMeeting.meeting.meetingDate;
                document.getElementById('meetingTime').textContent = currentMeeting.meeting.meetingTime;
                document.getElementById('meetingLocation').textContent = currentMeeting.meeting.location;
                document.getElementById('meetingDuration').textContent = currentMeeting.meeting.duration + ' minutes';
                document.getElementById('meetingNotes').textContent = currentMeeting.meeting.notes || 'No notes provided';
                
                // Reset attendance selection
                resetAttendanceSelection();
                
                // Show meeting details container
                document.getElementById('meetingDetailsContainer').style.display = 'block';
                document.getElementById('noMeetingAlert').style.display = 'none';
                document.getElementById('newMeetingFormContainer').style.display = 'none';
                document.getElementById('successMessage').style.display = 'none';
                
                // Scroll to top
                document.getElementById('meetingDetailsContainer').scrollIntoView({ behavior: 'smooth' });
            }
            
            function showNewMeetingForm() {
                const visitorCode = document.getElementById('visitorCodeInput').value.trim().toUpperCase();
                document.getElementById('visitorCodeForNewMeeting').value = visitorCode;
                
                // Set default date to today
                document.getElementById('newMeetingDate').value = new Date().toISOString().split('T')[0];
                
                // Set default time to next hour
                const nextHour = new Date();
                nextHour.setHours(nextHour.getHours() + 1);
                document.getElementById('newMeetingTime').value = nextHour.toTimeString().substr(0, 5);
                
                // Show new meeting form
                document.getElementById('noMeetingAlert').style.display = 'none';
                document.getElementById('newMeetingFormContainer').style.display = 'block';
            }
            
            function createNewMeeting() {
                const visitorCode = document.getElementById('visitorCodeForNewMeeting').value;
                const employeeId = document.getElementById('newEmployeeId').value.trim().toUpperCase();
                const meetingTitle = document.getElementById('newMeetingTitle').value.trim();
                const meetingDate = document.getElementById('newMeetingDate').value;
                const meetingTime = document.getElementById('newMeetingTime').value;
                const meetingDuration = document.getElementById('newMeetingDuration').value;
                const meetingLocation = document.getElementById('newMeetingLocation').value.trim();
                const meetingNotes = document.getElementById('newMeetingNotes').value.trim();
                
                // Validation
                if (!visitorCode || !employeeId || !meetingTitle || !meetingDate || !meetingTime || !meetingDuration || !meetingLocation) {
                    alert('Please fill in all required fields');
                    return;
                }
                
                // Check if employee exists
                if (!employees[employeeId]) {
                    alert(`Employee with ID "${employeeId}" not found.\n\nTry one of these test IDs:\nEMP001, EMP002, EMP003, etc.`);
                    return;
                }
                
                // Find visitor
                const visitor = allVisitors.find(v => v.code === visitorCode);
                if (!visitor) {
                    alert('Visitor not found in system');
                    return;
                }
                
                // Get employee details
                const employee = employees[employeeId];
                
                // Add meeting assignment to visitor
                if (!visitor.frontDeskActions) {
                    visitor.frontDeskActions = [];
                }
                
                const newMeeting = {
                    type: 'assigned_to_employee',
                    timestamp: new Date().toISOString(),
                    department: employee.department.toLowerCase(),
                    employee: {
                        id: employee.id,
                        name: employee.name,
                        position: employee.position,
                        employeeId: employeeId
                    },
                    meetingTitle: meetingTitle,
                    meetingDate: meetingDate,
                    meetingTime: meetingTime,
                    location: meetingLocation,
                    duration: meetingDuration,
                    notes: meetingNotes,
                    status: 'pending',
                    meetingId: 'MTG-' + Date.now().toString().substr(-6)
                };
                
                visitor.frontDeskActions.push(newMeeting);
                
                // Update localStorage
                localStorage.setItem('visitorRegistrationData', JSON.stringify(allVisitors));
                
                // Set current data
                currentVisitor = visitor;
                currentEmployee = employee;
                currentMeeting = {
                    visitor: visitor,
                    meeting: newMeeting,
                    meetingId: newMeeting.meetingId
                };
                
                // Show success message and meeting details
                document.getElementById('newMeetingFormContainer').style.display = 'none';
                displayMeetingDetails();
                
                // Show confirmation alert
                alert(`✅ New meeting created successfully!\n\nMeeting Title: ${meetingTitle}\nEmployee: ${employee.name}\nDate: ${meetingDate} at ${meetingTime}\nLocation: ${meetingLocation}`);
            }
            
            function selectAttendance(choice) {
                selectedAttendance = choice;
                
                // Update button styles
                document.getElementById('yesBtn').classList.remove('active');
                document.getElementById('noBtn').classList.remove('active');
                
                if (choice === 'yes') {
                    document.getElementById('yesBtn').classList.add('active');
                    document.getElementById('additionalNotesSection').style.display = 'block';
                    document.getElementById('cancellationReasonSection').style.display = 'none';
                } else {
                    document.getElementById('noBtn').classList.add('active');
                    document.getElementById('additionalNotesSection').style.display = 'none';
                    document.getElementById('cancellationReasonSection').style.display = 'block';
                }
            }
            
            function resetAttendanceSelection() {
                selectedAttendance = null;
                document.getElementById('yesBtn').classList.remove('active');
                document.getElementById('noBtn').classList.remove('active');
                document.getElementById('additionalNotesSection').style.display = 'none';
                document.getElementById('cancellationReasonSection').style.display = 'none';
                document.getElementById('meetingOutcome').value = '';
                document.getElementById('cancellationReason').value = '';
                document.getElementById('cancellationNotes').value = '';
            }
            
            function submitAttendance() {
                if (!selectedAttendance) {
                    alert('Please select "Yes" or "No" to confirm if the meeting was completed');
                    return;
                }
                
                if (selectedAttendance === 'no' && !document.getElementById('cancellationReason').value) {
                    alert('Please select a cancellation reason');
                    return;
                }
                
                // Find the visitor in allVisitors
                const visitorIndex = allVisitors.findIndex(v => v.code === currentVisitor.code);
                if (visitorIndex === -1) {
                    alert('Visitor not found in system');
                    return;
                }
                
                const visitor = allVisitors[visitorIndex];
                
                // Find the meeting action
                const meetingActionIndex = visitor.frontDeskActions.findIndex(action => 
                    action.type === 'assigned_to_employee' && 
                    action.meetingId === currentMeeting.meetingId
                );
                
                if (meetingActionIndex === -1) {
                    alert('Meeting assignment not found');
                    return;
                }
                
                // Update meeting status
                const newStatus = selectedAttendance === 'yes' ? 'completed' : 'cancelled';
                visitor.frontDeskActions[meetingActionIndex].meetingStatus = newStatus;
                visitor.frontDeskActions[meetingActionIndex].attendanceConfirmed = selectedAttendance === 'yes';
                visitor.frontDeskActions[meetingActionIndex].confirmationTime = new Date().toISOString();
                
                if (selectedAttendance === 'yes') {
                    visitor.frontDeskActions[meetingActionIndex].meetingOutcome = document.getElementById('meetingOutcome').value;
                    visitor.frontDeskActions[meetingActionIndex].confirmationType = 'meeting_completed';
                } else {
                    visitor.frontDeskActions[meetingActionIndex].cancellationReason = document.getElementById('cancellationReason').value;
                    visitor.frontDeskActions[meetingActionIndex].cancellationNotes = document.getElementById('cancellationNotes').value;
                    visitor.frontDeskActions[meetingActionIndex].confirmationType = 'meeting_cancelled';
                }
                
                // Add a new action for front desk notification
                visitor.frontDeskActions.push({
                    type: 'employee_attendance_confirmed',
                    timestamp: new Date().toISOString(),
                    employee: currentEmployee,
                    attendance: selectedAttendance === 'yes' ? 'completed' : 'cancelled',
                    meetingTitle: currentMeeting.meeting.meetingTitle,
                    notes: selectedAttendance === 'yes' ? 
                        document.getElementById('meetingOutcome').value : 
                        `Cancelled: ${document.getElementById('cancellationReason').options[document.getElementById('cancellationReason').selectedIndex].text}`,
                    status: 'pending_gate_pass', // This will trigger front desk to generate gate pass
                    requiresGatePass: selectedAttendance === 'yes' // Only generate gate pass for completed meetings
                });
                
                // Update localStorage
                localStorage.setItem('visitorRegistrationData', JSON.stringify(allVisitors));
                
                // Show success message
                document.getElementById('meetingDetailsContainer').style.display = 'none';
                document.getElementById('successMessage').style.display = 'block';
                
                const successDetails = selectedAttendance === 'yes' ? 
                    `✅ Meeting with <strong>${currentVisitor.name}</strong> has been marked as completed.<br><br>
                    <strong>Details:</strong><br>
                    👤 Visitor: ${currentVisitor.name}<br>
                    📅 Meeting: ${currentMeeting.meeting.meetingTitle}<br>
                    🕒 Time: ${currentMeeting.meeting.meetingDate} at ${currentMeeting.meeting.meetingTime}<br>
                    📍 Location: ${currentMeeting.meeting.location}<br><br>
                    <span class="badge bg-success">Ready for Gate Pass Generation</span><br><br>
                    <small>The front desk has been notified to generate a gate pass for the visitor.</small>` :
                    
                    `⚠️ Meeting with <strong>${currentVisitor.name}</strong> has been marked as cancelled.<br><br>
                    <strong>Details:</strong><br>
                    👤 Visitor: ${currentVisitor.name}<br>
                    📅 Meeting: ${currentMeeting.meeting.meetingTitle}<br>
                    🕒 Time: ${currentMeeting.meeting.meetingDate} at ${currentMeeting.meeting.meetingTime}<br>
                    📍 Location: ${currentMeeting.meeting.location}<br>
                    ❌ Reason: ${document.getElementById('cancellationReason').options[document.getElementById('cancellationReason').selectedIndex].text}<br><br>
                    <span class="badge bg-warning">No Gate Pass Required</span>`;
                
                document.getElementById('successDetails').innerHTML = successDetails;
                
                console.log(`Attendance confirmed: ${selectedAttendance} for meeting with ${currentVisitor.name}`);
                
                // Clear current data
                currentMeeting = null;
                currentEmployee = null;
                currentVisitor = null;
                selectedAttendance = null;
            }
            
            function resetForm() {
                document.getElementById('visitorCodeInput').value = '';
                document.getElementById('meetingDetailsContainer').style.display = 'none';
                document.getElementById('noMeetingAlert').style.display = 'none';
                document.getElementById('newMeetingFormContainer').style.display = 'none';
                document.getElementById('successMessage').style.display = 'none';
                
                // Reset new meeting form
                document.getElementById('newMeetingForm').reset();
                
                currentEmployee = null;
                currentVisitor = null;
                currentMeeting = null;
                selectedAttendance = null;
                
                // Focus on visitor code input
                document.getElementById('visitorCodeInput').focus();
            }
            
            // Initialize the form
            console.log('Employee Meeting Attendance System initialized');
            console.log('Connected to Front Desk Management System');
            
            // Display test info
            console.log('Test Visitor Codes: VR-A1B2C3, VR-X9Y8Z7, VR-M5N6O7');
        });
    </script>
</body>
</html>
@endsection
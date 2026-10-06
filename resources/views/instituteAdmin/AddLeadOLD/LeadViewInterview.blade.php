@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@section('content')
    <style>
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            color: white;
        }
        
        .header-content h1 {
            font-size: 2rem;
            margin-bottom: 5px;
            color: white;
        }
        
        .header-content p {
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .header-actions {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background-color: white;
            color: #667eea;
        }
        
        .btn-primary:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }
        
        .main-content {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
        }
        
        .interview-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .section-header {
            padding: 20px 25px;
            border-bottom: 1px solid #e9ecef;
            background-color: #f8f9fa;
        }
        
        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.3rem;
            color: #2c3e50;
            margin: 0;
        }
        
        .section-body {
            padding: 25px;
        }
        
        /* Interview Form */
        .interview-form {
            max-width: 800px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        /* Interviewer Selection */
        .interviewer-select {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .interviewer-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .interviewer-card:hover {
            border-color: #667eea;
            transform: translateY(-2px);
        }
        
        .interviewer-card.selected {
            border-color: #10b981;
            background-color: #f0fdf4;
            position: relative;
        }
        
        .interviewer-card.selected::after {
            content: '✓';
            position: absolute;
            top: -10px;
            right: -10px;
            background: #10b981;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .interviewer-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin: 0 auto 15px;
            font-weight: bold;
        }
        
        .interviewer-card h4 {
            font-size: 1rem;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        .interviewer-card p {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 10px;
        }
        
        .interviewer-rating {
            color: #f59e0b;
            font-size: 0.9rem;
        }
        
        /* Calendar Section */
        .calendar-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        
        .calendar-grid {
            padding: 20px;
        }
        
        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            margin-bottom: 10px;
        }
        
        .day-header {
            text-align: center;
            font-weight: 600;
            color: #64748b;
            padding: 10px;
            font-size: 0.9rem;
        }
        
        .calendar-dates {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }
        
        .date-cell {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
        }
        
        .date-cell:hover:not(.empty):not(.selected) {
            background-color: #f1f5f9;
        }
        
        .date-cell.selected {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .date-cell.empty {
            background: transparent;
            cursor: default;
        }
        
        .date-cell.today {
            border: 2px solid #667eea;
        }
        
        /* Time Slots */
        .time-slots {
            margin-top: 25px;
        }
        
        .time-slots-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 15px;
        }
        
        .time-slot {
            padding: 12px;
            text-align: center;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
        }
        
        .time-slot:hover {
            border-color: #667eea;
            background-color: #f8fafc;
        }
        
        .time-slot.selected {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
        }
        
        /* Interview List */
        .interview-list {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .interview-list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        
        .interview-items {
            max-height: 500px;
            overflow-y: auto;
        }
        
        .interview-item {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            transition: all 0.3s;
        }
        
        .interview-item:hover {
            background-color: #f8fafc;
        }
        
        .interview-item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        
        .interview-candidate {
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.1rem;
        }
        
        .interview-time {
            background: #e0f2fe;
            color: #0369a1;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .interview-details {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }
        
        .interview-detail {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #64748b;
        }
        
        .interview-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-scheduled {
            background-color: #f0f9ff;
            color: #0369a1;
        }
        
        .status-completed {
            background-color: #f0fdf4;
            color: #059669;
        }
        
        .status-cancelled {
            background-color: #fef2f2;
            color: #dc2626;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e9ecef;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        
        .btn-outline {
            background: white;
            color: #64748b;
            border: 1px solid #ddd;
        }
        
        .btn-outline:hover {
            border-color: #64748b;
        }
        
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            animation: modalSlide 0.3s ease;
        }
        
        @keyframes modalSlide {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .modal-header {
            padding: 20px 25px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-body {
            padding: 25px;
        }
        
        .modal-footer {
            padding: 20px 25px;
            border-top: 1px solid #e9ecef;
            text-align: right;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #64748b;
            cursor: pointer;
            line-height: 1;
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .main-content {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .interviewer-select {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
            
            .time-slots-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .header-section {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            
            .header-actions {
                width: 100%;
                justify-content: center;
            }
        }
        
        @media (max-width: 480px) {
            .time-slots-grid {
                grid-template-columns: 1fr;
            }
            
            .interviewer-select {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="header-content">
                <h1>Schedule Admission Interview</h1>
                <p>Create and manage admission interviews for prospective students</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-secondary" onclick="viewAllInterviews()">
                    <i class="fas fa-list"></i> View All Interviews
                </button>
                <button class="btn btn-primary" onclick="openBulkScheduleModal()">
                    <i class="fas fa-calendar-plus"></i> Bulk Schedule
                </button>
            </div>
        </div>

        <div class="main-content">
            <!-- Left Column - Interview Creation -->
            <div class="interview-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-calendar-plus"></i> Schedule New Interview
                    </h2>
                </div>
                
                <div class="section-body">
                    <form id="interviewForm" class="interview-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Candidate Name *</label>
                                <select class="form-control" id="candidateSelect" required>
                                    <option value="">Select Candidate</option>
                                    @if(isset($candidates) && count($candidates) > 0)
                                        @foreach($candidates as $candidate)
                                            <option value="{{ $candidate->id }}" 
                                                    data-lead-id="{{ $candidate->lead_id }}"
                                                    data-phone="{{ $candidate->phone_no }}">
                                                {{ $candidate->name }} ({{ $candidate->lead_id }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Interview Type *</label>
                                <select class="form-control" id="interviewType" required>
                                    <option value="">Select Type</option>
                                    <option value="personal">Personal Interview</option>
                                    <option value="technical">Technical Interview</option>
                                    <option value="counseling">Counseling Session</option>
                                    <option value="group">Group Discussion</option>
                                    <option value="final">Final Assessment</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Select Interviewer(s)</label>
                            <div class="interviewer-select">
                                <div class="interviewer-card" onclick="selectInterviewer('Dr. Sharma')" data-id="1">
                                    <div class="interviewer-avatar">DS</div>
                                    <h4>Dr. Sharma</h4>
                                    <p>Head of Admissions</p>
                                    <div class="interviewer-rating">
                                        <i class="fas fa-star"></i> 4.8
                                    </div>
                                </div>
                                
                                <div class="interviewer-card" onclick="selectInterviewer('Prof. Gupta')" data-id="2">
                                    <div class="interviewer-avatar">PG</div>
                                    <h4>Prof. Gupta</h4>
                                    <p>Academic Dean</p>
                                    <div class="interviewer-rating">
                                        <i class="fas fa-star"></i> 4.6
                                    </div>
                                </div>
                                
                                <div class="interviewer-card" onclick="selectInterviewer('Ms. Patel')" data-id="3">
                                    <div class="interviewer-avatar">MP</div>
                                    <h4>Ms. Patel</h4>
                                    <p>Senior Counselor</p>
                                    <div class="interviewer-rating">
                                        <i class="fas fa-star"></i> 4.9
                                    </div>
                                </div>
                                
                                <div class="interviewer-card" onclick="selectInterviewer('Mr. Kumar')" data-id="4">
                                    <div class="interviewer-avatar">MK</div>
                                    <h4>Mr. Kumar</h4>
                                    <p>Technical Lead</p>
                                    <div class="interviewer-rating">
                                        <i class="fas fa-star"></i> 4.7
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="selectedInterviewers" name="interviewers">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Interview Date *</label>
                                <input type="date" class="form-control" id="interviewDate" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Select Time Slot *</label>
                                <div class="time-slots">
                                    <div class="time-slots-grid">
                                        <div class="time-slot" onclick="selectTimeSlot('09:00 AM')">09:00 AM</div>
                                        <div class="time-slot" onclick="selectTimeSlot('10:00 AM')">10:00 AM</div>
                                        <div class="time-slot" onclick="selectTimeSlot('11:00 AM')">11:00 AM</div>
                                        <div class="time-slot" onclick="selectTimeSlot('02:00 PM')">02:00 PM</div>
                                        <div class="time-slot" onclick="selectTimeSlot('03:00 PM')">03:00 PM</div>
                                        <div class="time-slot" onclick="selectTimeSlot('04:00 PM')">04:00 PM</div>
                                    </div>
                                </div>
                                <input type="hidden" id="selectedTimeSlot" name="time_slot">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Interview Mode *</label>
                            <div style="display: flex; gap: 20px; margin-top: 10px;">
                                <label style="display: flex; align-items: center; gap: 8px;">
                                    <input type="radio" name="interviewMode" value="in_person" checked>
                                    <span>In-Person</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px;">
                                    <input type="radio" name="interviewMode" value="online">
                                    <span>Online</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px;">
                                    <input type="radio" name="interviewMode" value="phone">
                                    <span>Phone</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group" id="locationField">
                            <label class="form-label">Location/Venue *</label>
                            <input type="text" class="form-control" id="interviewLocation" 
                                   placeholder="e.g., Main Campus, Room 101" value="Main Campus, Room 101">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Notes/Instructions</label>
                            <textarea class="form-control" id="interviewNotes" 
                                      placeholder="Add any special instructions or notes for the interview..."></textarea>
                        </div>
                        
                        <div class="action-buttons">
                            <button type="button" class="btn btn-success" onclick="scheduleInterview()">
                                <i class="fas fa-calendar-check"></i> Schedule Interview
                            </button>
                            <button type="reset" class="btn btn-outline">
                                <i class="fas fa-redo"></i> Clear Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Right Column - Calendar & Upcoming Interviews -->
            <div style="display: flex; flex-direction: column; gap: 30px;">
                <!-- Calendar Section -->
                <div class="calendar-section">
                    <div class="calendar-header">
                        <h3 style="margin: 0; color: #2c3e50;">Interview Calendar</h3>
                        <div style="display: flex; gap: 10px;">
                            <button onclick="prevMonth()" style="background: none; border: none; cursor: pointer; color: #64748b;">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <span id="currentMonth" style="font-weight: 600;">January 2024</span>
                            <button onclick="nextMonth()" style="background: none; border: none; cursor: pointer; color: #64748b;">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="calendar-grid">
                        <div class="calendar-days">
                            <div class="day-header">Sun</div>
                            <div class="day-header">Mon</div>
                            <div class="day-header">Tue</div>
                            <div class="day-header">Wed</div>
                            <div class="day-header">Thu</div>
                            <div class="day-header">Fri</div>
                            <div class="day-header">Sat</div>
                        </div>
                        <div class="calendar-dates" id="calendarDates">
                            <!-- Calendar dates will be generated by JavaScript -->
                        </div>
                    </div>
                </div>
                
                <!-- Upcoming Interviews -->
                <div class="interview-list">
                    <div class="interview-list-header">
                        <h3 style="margin: 0; color: #2c3e50;">Today's Interviews</h3>
                        <span class="interview-status status-scheduled">3 Scheduled</span>
                    </div>
                    <div class="interview-items" id="upcomingInterviews">
                        <!-- Interview items will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Schedule Modal -->
    <div class="modal" id="bulkScheduleModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style="margin: 0; color: #2c3e50;">Bulk Schedule Interviews</h3>
                <button class="close-modal" onclick="closeBulkScheduleModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Select Candidates</label>
                    <select class="form-control" id="bulkCandidates" multiple style="height: 150px;">
                        @if(isset($candidates) && count($candidates) > 0)
                            @foreach($candidates as $candidate)
                                <option value="{{ $candidate->id }}">{{ $candidate->name }} ({{ $candidate->lead_id }})</option>
                            @endforeach
                        @endif
                    </select>
                    <small style="color: #64748b; display: block; margin-top: 5px;">
                        Hold Ctrl/Cmd to select multiple candidates
                    </small>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="bulkStartDate">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time Slot</label>
                        <select class="form-control" id="bulkTimeSlot">
                            <option value="09:00 AM">09:00 AM</option>
                            <option value="10:00 AM">10:00 AM</option>
                            <option value="11:00 AM">11:00 AM</option>
                            <option value="02:00 PM">02:00 PM</option>
                            <option value="03:00 PM">03:00 PM</option>
                            <option value="04:00 PM">04:00 PM</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Duration (days)</label>
                    <select class="form-control" id="bulkDuration">
                        <option value="1">1 Day</option>
                        <option value="3">3 Days</option>
                        <option value="5">5 Days</option>
                        <option value="7">1 Week</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeBulkScheduleModal()">Cancel</button>
                <button type="button" class="btn btn-success" onclick="confirmBulkSchedule()">Schedule All</button>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmationModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style="margin: 0; color: #2c3e50;">Interview Scheduled</h3>
                <button class="close-modal" onclick="closeConfirmationModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; padding: 20px;">
                    <div style="width: 80px; height: 80px; background: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-check" style="font-size: 2rem; color: #10b981;"></i>
                    </div>
                    <h4 style="margin: 0 0 10px; color: #2c3e50;">Interview Scheduled Successfully!</h4>
                    <p id="confirmationMessage" style="color: #64748b;">The interview has been scheduled and notifications have been sent.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="closeConfirmationModal()">Done</button>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let selectedInterviewers = [];
        let selectedTimeSlot = '';
        let currentDate = new Date();
        let today = new Date();
        
        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            // Set minimum date to today
            const dateInput = document.getElementById('interviewDate');
            const todayStr = today.toISOString().split('T')[0];
            dateInput.min = todayStr;
            dateInput.value = todayStr;
            
            // Generate calendar
            generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
            
            // Load upcoming interviews
            loadUpcomingInterviews();
            
            // Set up interview mode change listener
            document.querySelectorAll('input[name="interviewMode"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const locationField = document.getElementById('locationField');
                    const locationInput = document.getElementById('interviewLocation');
                    
                    if (this.value === 'online') {
                        locationInput.placeholder = 'Enter meeting link (Zoom/Google Meet)';
                        locationInput.value = '';
                    } else if (this.value === 'phone') {
                        locationField.style.display = 'none';
                    } else {
                        locationField.style.display = 'block';
                        locationInput.placeholder = 'e.g., Main Campus, Room 101';
                        locationInput.value = 'Main Campus, Room 101';
                    }
                });
            });
        });
        
        // Interviewer selection
        function selectInterviewer(name) {
            const card = event.currentTarget;
            const index = selectedInterviewers.indexOf(name);
            
            if (index === -1) {
                // Add to selection
                selectedInterviewers.push(name);
                card.classList.add('selected');
            } else {
                // Remove from selection
                selectedInterviewers.splice(index, 1);
                card.classList.remove('selected');
            }
            
            // Update hidden input
            document.getElementById('selectedInterviewers').value = selectedInterviewers.join(', ');
        }
        
        // Time slot selection
        function selectTimeSlot(time) {
            selectedTimeSlot = time;
            
            // Remove selected class from all slots
            document.querySelectorAll('.time-slot').forEach(slot => {
                slot.classList.remove('selected');
            });
            
            // Add selected class to clicked slot
            event.currentTarget.classList.add('selected');
            
            // Update hidden input
            document.getElementById('selectedTimeSlot').value = time;
        }
        
        // Generate calendar
        function generateCalendar(year, month) {
            const monthNames = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
            
            // Update month header
            document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;
            
            // Get first day of month and total days
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            
            const calendarDates = document.getElementById('calendarDates');
            calendarDates.innerHTML = '';
            
            // Add empty cells for days before first day of month
            for (let i = 0; i < firstDay; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'date-cell empty';
                calendarDates.appendChild(emptyCell);
            }
            
            // Add cells for each day of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const dateCell = document.createElement('div');
                dateCell.className = 'date-cell';
                dateCell.textContent = day;
                
                // Check if it's today
                const cellDate = new Date(year, month, day);
                if (cellDate.toDateString() === today.toDateString()) {
                    dateCell.classList.add('today');
                }
                
                // Check if it has interviews (mock data)
                if ([5, 12, 19, 26].includes(day)) {
                    dateCell.style.position = 'relative';
                    dateCell.style.fontWeight = 'bold';
                    dateCell.style.color = '#667eea';
                    
                    const indicator = document.createElement('div');
                    indicator.style.position = 'absolute';
                    indicator.style.bottom = '5px';
                    indicator.style.left = '50%';
                    indicator.style.transform = 'translateX(-50%)';
                    indicator.style.width = '6px';
                    indicator.style.height = '6px';
                    indicator.style.backgroundColor = '#667eea';
                    indicator.style.borderRadius = '50%';
                    dateCell.appendChild(indicator);
                }
                
                dateCell.addEventListener('click', function() {
                    // Remove selected class from all cells
                    document.querySelectorAll('.date-cell').forEach(cell => {
                        cell.classList.remove('selected');
                    });
                    
                    // Add selected class to clicked cell
                    this.classList.add('selected');
                    
                    // Update date input
                    const selectedDate = new Date(year, month, day);
                    const formattedDate = selectedDate.toISOString().split('T')[0];
                    document.getElementById('interviewDate').value = formattedDate;
                });
                
                calendarDates.appendChild(dateCell);
            }
        }
        
        // Navigate calendar
        function prevMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
        }
        
        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
        }
        
        // Load upcoming interviews
        function loadUpcomingInterviews() {
            const interviews = [
                {
                    id: 1,
                    candidate: 'Rajesh Kumar',
                    time: '10:00 AM',
                    type: 'Technical Interview',
                    interviewer: 'Mr. Kumar',
                    status: 'scheduled'
                },
                {
                    id: 2,
                    candidate: 'Priya Sharma',
                    time: '02:00 PM',
                    type: 'Personal Interview',
                    interviewer: 'Dr. Sharma',
                    status: 'scheduled'
                },
                {
                    id: 3,
                    candidate: 'Amit Patel',
                    time: '04:00 PM',
                    type: 'Counseling Session',
                    interviewer: 'Ms. Patel',
                    status: 'completed'
                }
            ];
            
            const container = document.getElementById('upcomingInterviews');
            container.innerHTML = '';
            
            interviews.forEach(interview => {
                const item = document.createElement('div');
                item.className = 'interview-item';
                
                item.innerHTML = `
                    <div class="interview-item-header">
                        <div class="interview-candidate">${interview.candidate}</div>
                        <div class="interview-time">${interview.time}</div>
                    </div>
                    <div class="interview-details">
                        <div class="interview-detail">
                            <i class="fas fa-user-tie"></i>
                            ${interview.interviewer}
                        </div>
                        <div class="interview-detail">
                            <i class="fas fa-calendar-alt"></i>
                            ${interview.type}
                        </div>
                    </div>
                    <div class="interview-status status-${interview.status}">
                        ${interview.status.charAt(0).toUpperCase() + interview.status.slice(1)}
                    </div>
                `;
                
                container.appendChild(item);
            });
        }
        
        // Schedule interview
        async function scheduleInterview() {
            // Validate form
            const candidateSelect = document.getElementById('candidateSelect');
            const interviewType = document.getElementById('interviewType');
            const interviewDate = document.getElementById('interviewDate');
            const interviewMode = document.querySelector('input[name="interviewMode"]:checked');
            const interviewLocation = document.getElementById('interviewLocation');
            
            if (!candidateSelect.value) {
                alert('Please select a candidate');
                return;
            }
            
            if (!interviewType.value) {
                alert('Please select interview type');
                return;
            }
            
            if (!interviewDate.value) {
                alert('Please select interview date');
                return;
            }
            
            if (selectedInterviewers.length === 0) {
                alert('Please select at least one interviewer');
                return;
            }
            
            if (!selectedTimeSlot) {
                alert('Please select a time slot');
                return;
            }
            
            // Prepare data
            const formData = {
                candidate_id: candidateSelect.value,
                candidate_name: candidateSelect.options[candidateSelect.selectedIndex].text.split('(')[0].trim(),
                lead_id: candidateSelect.options[candidateSelect.selectedIndex].getAttribute('data-lead-id'),
                phone: candidateSelect.options[candidateSelect.selectedIndex].getAttribute('data-phone'),
                interview_type: interviewType.value,
                interviewers: selectedInterviewers,
                date: interviewDate.value,
                time: selectedTimeSlot,
                mode: interviewMode.value,
                location: interviewMode.value === 'phone' ? 'Phone Call' : interviewLocation.value,
                notes: document.getElementById('interviewNotes').value,
                status: 'scheduled'
            };
            
            try {
                // Simulate API call
                console.log('Scheduling interview:', formData);
                
                // Show success modal
                document.getElementById('confirmationMessage').textContent = 
                    `${formData.candidate_name} has been scheduled for a ${formData.interview_type} on ${formatDate(formData.date)} at ${formData.time}.`;
                
                document.getElementById('confirmationModal').style.display = 'flex';
                
                // Reset form
                setTimeout(() => {
                    document.getElementById('interviewForm').reset();
                    selectedInterviewers = [];
                    selectedTimeSlot = '';
                    document.querySelectorAll('.interviewer-card').forEach(card => {
                        card.classList.remove('selected');
                    });
                    document.querySelectorAll('.time-slot').forEach(slot => {
                        slot.classList.remove('selected');
                    });
                    document.getElementById('selectedInterviewers').value = '';
                    document.getElementById('selectedTimeSlot').value = '';
                }, 2000);
                
            } catch (error) {
                console.error('Error scheduling interview:', error);
                alert('Failed to schedule interview. Please try again.');
            }
        }
        
        // Format date for display
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
        
        // Bulk scheduling modal
        function openBulkScheduleModal() {
            document.getElementById('bulkScheduleModal').style.display = 'flex';
            
            // Set default start date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowStr = tomorrow.toISOString().split('T')[0];
            document.getElementById('bulkStartDate').value = tomorrowStr;
            document.getElementById('bulkStartDate').min = tomorrowStr;
        }
        
        function closeBulkScheduleModal() {
            document.getElementById('bulkScheduleModal').style.display = 'none';
        }
        
        function confirmBulkSchedule() {
            const candidates = document.getElementById('bulkCandidates');
            const selectedCandidates = Array.from(candidates.selectedOptions).map(opt => opt.text);
            
            if (selectedCandidates.length === 0) {
                alert('Please select at least one candidate');
                return;
            }
            
            const startDate = document.getElementById('bulkStartDate').value;
            const timeSlot = document.getElementById('bulkTimeSlot').value;
            const duration = document.getElementById('bulkDuration').value;
            
            console.log('Bulk scheduling:', {
                candidates: selectedCandidates,
                startDate,
                timeSlot,
                duration
            });
            
            alert(`${selectedCandidates.length} interviews will be scheduled starting from ${startDate}`);
            closeBulkScheduleModal();
        }
        
        // View all interviews
        function viewAllInterviews() {
            // Navigate to interviews list page
            window.location.href = '/interviews'; 
        }
        
        // Close confirmation modal
        function closeConfirmationModal() {
            document.getElementById('confirmationModal').style.display = 'none';
        }
        
        // Export to calendar
        function exportToCalendar() {
            alert('Export functionality would integrate with Google Calendar/Outlook');
        }
    </script>
@endsection
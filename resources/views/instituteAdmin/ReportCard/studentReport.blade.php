@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #4bb543;
            --warning-color: #ff9e00;
            --danger-color: #e63946;
            --gradient-primary: linear-gradient(135deg, #4361ee, #3a0ca3);
            --gradient-success: linear-gradient(135deg, #4bb543, #2a9d40);
            --gradient-warning: linear-gradient(135deg, #ff9e00, #ff7b00);
            --gradient-danger: linear-gradient(135deg, #e63946, #d00000);
            --gradient-gold: linear-gradient(135deg, #FFD700, #FFA500);
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --border-radius: 16px;
            --transition: all 0.3s ease;
        }
        
        .report-card {
            background: white;
            border: 3px solid #333;
            width: 100%;
            min-height: 100vh;
            margin: 20px auto;
            padding: 40px;
            font-family: 'Times New Roman', Times, serif;
            box-shadow: 0 0 40px rgba(0,0,0,0.15);
            position: relative;
        }
        
        @media print {
            .report-card {
                border: none;
                box-shadow: none;
                padding: 20px;
                margin: 0;
            }
            
            .no-print {
                display: none !important;
            }
        }
        
        .grade-badge {
            font-weight: 800;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.95rem;
            display: inline-block;
            text-align: center;
            min-width: 70px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            transition: var(--transition);
        }
        
        .performance-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .summary-card {
            background: white;
            border-radius: 15px;
            padding: 1.8rem;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            border-top: 5px solid var(--primary-color);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        
        .summary-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }
        
        .summary-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--secondary-color);
            margin-bottom: 0.8rem;
        }
        
        .summary-label {
            font-size: 1rem;
            color: #6c757d;
            font-weight: 600;
        }
        
        .table-custom {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .table-custom thead th {
            background: var(--gradient-primary);
            color: white;
            font-weight: 700;
            border: none;
            padding: 1.2rem 1.5rem;
            text-align: center;
            vertical-align: middle;
            font-size: 1rem;
        }
        
        .table-custom tbody td {
            padding: 1.2rem 1.5rem;
            vertical-align: middle;
            border-bottom: 2px solid #f8f9ff;
            font-weight: 500;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 3rem;
        }
        
        .btn-custom {
            border: none;
            border-radius: 15px;
            padding: 15px 30px;
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: var(--transition);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            min-width: 180px;
        }
        
        .btn-primary-custom {
            background: var(--gradient-primary);
            color: white;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(67, 97, 238, 0.3);
        }
        
        .btn-success-custom {
            background: var(--gradient-success);
            color: white;
        }
        
        .btn-success-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(75, 181, 67, 0.3);
        }
        
        .btn-warning-custom {
            background: var(--gradient-warning);
            color: white;
        }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9998;
        }
        
        .loading-overlay.show {
            display: flex;
            animation: fadeIn 0.3s ease;
        }
        
        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
                align-items: stretch;
            }
            
            .btn-custom {
                width: 100%;
            }
            
            .performance-summary {
                grid-template-columns: 1fr;
            }
            
            .report-card {
                padding: 20px;
            }
        }
    </style>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Main Container -->
    <main class="container-fluid">
          @php
            $courseLabel = (isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School')
                ? 'Class'
                : 'Course';
            @endphp
        <!-- Action Buttons -->
        <div class="action-buttons no-print" id="actionButtons">
            <button class="btn btn-primary-custom btn-custom" onclick="window.print()">
                <i class="fas fa-print me-2"></i> Print Report Card
            </button>
            <button class="btn btn-success-custom btn-custom" id="generatePDFBtn">
                <i class="fas fa-file-pdf me-2"></i> Download as PDF
            </button>
            <button class="btn btn-warning-custom btn-custom" onclick="window.history.back()">
                <i class="fas fa-arrow-left me-2"></i> Back to Selection
            </button>
        </div>

        <!-- Report Card Content -->
        <div id="reportCardContainer">
            <div class="text-center my-5">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 fs-5">Loading student report card...</p>
            </div>
        </div>

        <!-- Footer -->
        <footer class="text-center mt-5 mb-4 text-muted no-print">
            <p>Generated on: <span id="generatedDate" class="fw-bold"></span></p>
        </footer>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    
    <script>
        const instituteData = @json($fincapMerchants);
        const instituteName = instituteData?.institute_name || instituteData?.name || 'Institute Name';
        const instituteTagline = instituteData?.affiliation || 'Affiliated Institute';
        const instituteAddress = [
            instituteData?.address_line_1 || instituteData?.registered_address,
            instituteData?.city,
            instituteData?.state,
            instituteData?.pincode
        ].filter(Boolean).join(', ') || 'Institute Address';
        const affiliationNo = instituteData?.contact_number ? `Contact. No. – ${instituteData.contact_number}` : '';

        $(document).ready(function() {
            const studentHashId = '{{ $student_hash_id }}';
            const academicYear = '{{ $academic_year }}';
            
            // Set generated date
            const today = new Date();
            const formattedDate = today.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            $('#generatedDate').text(formattedDate);
            
            // Show loading
            showLoading();
            
            // Load student report
            $.ajax({
                url: '/ajax/get-student-exam-marks',
                type: 'GET',
                data: {
                    student_hash_id: studentHashId,
                    academic_year: academicYear
                },
                success: function(response) {
                    if (response.success) {
                        displayReportCard(response);
                    } else {
                        $('#reportCardContainer').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                ${response.message}
                            </div>
                        `);
                    }
                    hideLoading();
                },
                error: function() {
                    $('#reportCardContainer').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error loading student report. Please try again.
                        </div>
                    `);
                    hideLoading();
                }
            });
            
            // PDF Generation
            $('#generatePDFBtn').click(function() {
                generatePDF();
            });
        });
        
        // Display Report Card with actual grades
        function displayReportCard(data) {
            const student = data.student;
            const academic = data.academic_details;
            const stats = data.statistics;
            const subjects = data.subject_performance;
            
            // Format date of birth
            const dob = student.dob ? new Date(student.dob).toLocaleDateString('en-GB') : 'N/A';
            
            // Use actual overall grade from backend
            const overallGrade = stats.overall_grade || 'N/A';
            
            // Determine promotion status
            const promotionStatus = determinePromotionStatus(stats);
            
            // Create report card HTML
            const reportCardHTML = `
                <div class="report-card" id="reportCardContent">
                    <!-- Report Card Header -->
                    <div style="text-align: center; border-bottom: 4px double #4361ee; padding-bottom: 20px; margin-bottom: 30px;">
                        <h1 style="color: #2c3e50; font-size: 36px; font-weight: 800; margin-bottom: 10px;">
                            ${instituteName}
                        </h1>
                        <h2 style="color: #34495e; font-size: 20px; font-weight: 600; margin-bottom: 8px;">
                            ${instituteTagline}
                        </h2>
                        <h3 style="color: #7f8c8d; font-size: 16px; font-weight: 400; margin-bottom: 5px;">
                            ${instituteAddress} ${affiliationNo}
                        </h3>
                        
                        <div style="background: linear-gradient(135deg, #4361ee, #3a0ca3); color: white; padding: 15px; border-radius: 10px; margin-top: 20px; display: inline-block;">
                            <div style="font-size: 22px; font-weight: 700;">ACADEMIC SESSION: ${student.academic_year || data.academic_year}</div>
                            <div style="font-size: 18px; font-weight: 600;">ANNUAL REPORT CARD</div>
                        </div>
                    </div>
                    
                    <!-- Student Information -->
                    <div style="background: linear-gradient(135deg, #f8f9ff, #ffffff); border-radius: 15px; padding: 25px; margin-bottom: 30px; border: 2px solid #e0e0e0;">
                        <h3 style="color: #2c3e50; border-bottom: 3px solid #4361ee; padding-bottom: 10px; margin-bottom: 20px; font-weight: 700;">
                            <i class="fas fa-user-graduate" style="margin-right: 10px;"></i>STUDENT INFORMATION
                        </h3>
                        
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #4361ee, #3a0ca3); border-radius: 15px; display: flex; align-items: center; justify-content: center; color: white; font-size: 36px; font-weight: 700;">
                                    ${(student.first_name ? student.first_name.charAt(0) : '') + (student.last_name ? student.last_name.charAt(0) : '')}
                                </div>
                                <div>
                                    <div style="font-size: 24px; font-weight: 800; color: #2c3e50;">${student.full_name || `${student.first_name} ${student.last_name}`}</div>
                                    <div style="display: flex; gap: 15px; margin-top: 10px;">
                                        <span style="background: #4361ee; color: white; padding: 5px 15px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                                            Registration: ${student.registration_number || 'N/A'}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                    <div>
                                        <div style="font-weight: 600; color: #7f8c8d; font-size: 14px;">Date of Birth</div>
                                        <div style="font-weight: 700; color: #2c3e50; font-size: 16px;">${dob}</div>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #7f8c8d; font-size: 14px;">Gender</div>
                                        <div style="font-weight: 700; color: #2c3e50; font-size: 16px;">${student.gender || 'N/A'}</div>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #7f8c8d; font-size: 14px;">Father's Name</div>
                                        <div style="font-weight: 700; color: #2c3e50; font-size: 16px;">${student.father_first_name || ''} ${student.father_middle_name || ''} ${student.father_last_name || ''}</div>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #7f8c8d; font-size: 14px;">Mother's Name</div>
                                        <div style="font-weight: 700; color: #2c3e50; font-size: 16px;">${student.mother_first_name || ''} ${student.mother_middle_name || ''} ${student.mother_last_name || ''}</div>
                                    </div>
                                    ${academic ? `
                                    <div>
                                        <div style="font-weight: 600; color: #7f8c8d; font-size: 14px;">Course</div>
                                        <div style="font-weight: 700; color: #2c3e50; font-size: 16px;">${academic.course_type || 'N/A'}/ ${academic.course_subtype || 'N/A'}</div>
                                    </div>
                                   
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Overall Performance Summary -->
                    <div style="background: linear-gradient(135deg, #fff8f0, #ffffff); border-radius: 15px; padding: 25px; margin-bottom: 30px; border: 2px solid #ffd700;">
                        <h3 style="color: #ff6b00; border-bottom: 3px solid #ffd700; padding-bottom: 10px; margin-bottom: 20px; font-weight: 700;">
                            <i class="fas fa-chart-line" style="margin-right: 10px;"></i>OVERALL PERFORMANCE SUMMARY
                        </h3>
                        
                        <div class="performance-summary">
                            <div class="summary-card">
                                <div class="summary-value">${stats.overall_percentage}%</div>
                                <div class="summary-label">Overall Percentage</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-value">${stats.total_exams}</div>
                                <div class="summary-label">Total Exams</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-value">${stats.passed_exams}</div>
                                <div class="summary-label">Exams Passed</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-value">${stats.subjects_count}</div>
                                <div class="summary-label">Subjects</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-value" style="font-size: 2rem;">${overallGrade}</div>
                                <div class="summary-label">Overall Grade</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-value">${stats.pass_percentage}%</div>
                                <div class="summary-label">Pass Rate</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Subject-wise Performance -->
                    <div style="background: linear-gradient(135deg, #f8fff8, #ffffff); border-radius: 15px; padding: 25px; margin-bottom: 30px; border: 2px solid #28a745;">
                        <h3 style="color: #1e7e34; border-bottom: 3px solid #28a745; padding-bottom: 10px; margin-bottom: 20px; font-weight: 700;">
                            <i class="fas fa-book-open" style="margin-right: 10px;"></i>SUBJECT-WISE PERFORMANCE
                        </h3>
                        
                        ${subjects.length > 0 ? `
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Exams</th>
                                        <th>Total Marks</th>
                                        <th>Obtained</th>
                                        <th>Percentage</th>
                                        <th>Grade</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${subjects.map(subject => {
                                        // Use actual subject grade from backend
                                        const grade = subject.grade || 'N/A';
                                        const gradeClass = getGradeClass(grade);
                                        const status = subject.percentage >= 33 ? 'Pass' : 'Fail';
                                        const statusClass = status === 'Pass' ? 'text-success' : 'text-danger';
                                        
                                        return `
                                            <tr>
                                                <td><strong>${subject.subject_name}</strong></td>
                                                <td>${subject.exam_count}</td>
                                                <td>${subject.total_marks}</td>
                                                <td>${subject.obtained_marks}</td>
                                                <td>${subject.percentage}%</td>
                                                <td><span class="grade-badge ${gradeClass}">${grade}</span></td>
                                                <td class="${statusClass}">
                                                    <i class="fas fa-${status === 'Pass' ? 'check' : 'times'} me-1"></i>
                                                    ${status}
                                                </td>
                                            </tr>
                                        `;
                                    }).join('')}
                                </tbody>
                            </table>
                        </div>
                        ` : `
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No exam marks recorded for this student.
                        </div>
                        `}
                    </div>
                    
                    <!-- Detailed Exam Marks -->
                    ${subjects.length > 0 ? `
                    <div style="background: linear-gradient(135deg, #f0f8ff, #ffffff); border-radius: 15px; padding: 25px; margin-bottom: 30px; border: 2px solid #17a2b8;">
                        <h3 style="color: #0c5460; border-bottom: 3px solid #17a2b8; padding-bottom: 10px; margin-bottom: 20px; font-weight: 700;">
                            <i class="fas fa-clipboard-list" style="margin-right: 10px;"></i>DETAILED EXAM MARKS
                        </h3>
                        
                        ${subjects.map(subject => `
                            <div style="margin-bottom: 25px;">
                                <h5 style="color: #0c5460; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #e0e0e0;">
                                    <i class="fas fa-book me-2"></i>${subject.subject_name}
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-sm" style="font-size: 14px;">
                                        <thead>
                                            <tr style="background: #f8f9ff;">
                                                <th>Exam Name</th>
                                                <th>Date</th>
                                                <th>Total Marks</th>
                                                <th>Obtained</th>
                                                <th>Percentage</th>
                                                <th>Grade</th>
                                                <th>Status</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${subject.exams.map(exam => {
                                                // Use actual exam grade from backend
                                                const grade = exam.grade || 'N/A';
                                                const gradeClass = getGradeClass(grade);
                                                const status = exam.status;
                                                const statusClass = status === 'Pass' ? 'text-success' : 'text-danger';
                                                const examDate = exam.exam_date ? new Date(exam.exam_date).toLocaleDateString('en-GB') : 'N/A';
                                                
                                                return `
                                                    <tr>
                                                        <td>${exam.exam_name}</td>
                                                        <td>${examDate}</td>
                                                        <td>${exam.total_marks}</td>
                                                        <td>${exam.obtained_marks}</td>
                                                        <td>${exam.percentage}%</td>
                                                        <td><span class="${gradeClass}" style="padding: 3px 10px; border-radius: 15px; font-size: 12px;">${grade}</span></td>
                                                        <td class="${statusClass}">
                                                            <i class="fas fa-${status === 'Pass' ? 'check' : 'times'} me-1"></i>
                                                            ${status}
                                                        </td>
                                                        <td>${exam.remarks || '-'}</td>
                                                    </tr>
                                                `;
                                            }).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    ` : ''}
                    
                    <!-- Promotion Status & Teacher's Remarks -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-bottom: 30px;">
                        <div style="background: linear-gradient(135deg, #fff0f5, #ffffff); border-radius: 15px; padding: 25px; border: 2px solid #e63946;">
                            <h3 style="color: #b71c1c; border-bottom: 3px solid #e63946; padding-bottom: 10px; margin-bottom: 20px; font-weight: 700;">
                                <i class="fas fa-comment-dots" style="margin-right: 10px;"></i>TEACHER'S REMARKS
                            </h3>
                            <div style="line-height: 1.6; color: #2c3e50; font-size: 14px; min-height: 150px;">
                                ${getTeacherRemarks(stats.overall_percentage)}
                            </div>
                        </div>
                        
                        <div style="background: linear-gradient(135deg, #f0fff0, #ffffff); border-radius: 15px; padding: 25px; border: 2px solid #28a745;">
                            <h3 style="color: #1e7e34; border-bottom: 3px solid #28a745; padding-bottom: 10px; margin-bottom: 20px; font-weight: 700;">
                                <i class="fas fa-award" style="margin-right: 10px;"></i>PROMOTION STATUS
                            </h3>
                            <div style="text-align: center; padding: 20px;">
                                <div style="font-size: 36px; font-weight: 800; color: ${promotionStatus.color}; margin-bottom: 15px;">
                                    ${promotionStatus.status}
                                </div>
                                <div style="font-size: 18px; color: #2c3e50; margin-bottom: 20px;">
                                    ${promotionStatus.message}
                                </div>
                                <div style="display: flex; justify-content: center; gap: 20px; margin-top: 20px;">
                                    <div style="text-align: center;">
                                        <div style="font-size: 14px; color: #7f8c8d;">Overall Percentage</div>
                                        <div style="font-size: 24px; font-weight: 700; color: #2c3e50;">${stats.overall_percentage}%</div>
                                    </div>
                                    <div style="text-align: center;">
                                        <div style="font-size: 14px; color: #7f8c8d;">Overall Grade</div>
                                        <div style="font-size: 24px; font-weight: 700; color: #2c3e50;">${overallGrade}</div>
                                    </div>
                                    <div style="text-align: center;">
                                        <div style="font-size: 14px; color: #7f8c8d;">Pass Rate</div>
                                        <div style="font-size: 24px; font-weight: 700; color: #2c3e50;">${stats.pass_percentage}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Signatures -->
                    <div style="border-top: 2px solid #dee2e6; padding-top: 30px; margin-top: 30px;">
                        <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
                            <div style="text-align: center; flex: 1; min-width: 200px;">
                                <div style="border-bottom: 1px solid #2c3e50; width: 150px; margin: 0 auto 10px; padding-bottom: 40px;"></div>
                                <div style="font-weight: 700; color: #2c3e50;">Class Teacher</div>
                                <div style="font-size: 12px; color: #7f8c8d; margin-top: 5px;">Name & Signature</div>
                            </div>
                            <div style="text-align: center; flex: 1; min-width: 200px;">
                                <div style="border-bottom: 1px solid #2c3e50; width: 150px; margin: 0 auto 10px; padding-bottom: 40px;"></div>
                                <div style="font-weight: 700; color: #2c3e50;">Principal</div>
                                <div style="font-size: 12px; color: #7f8c8d; margin-top: 5px;">Name & Signature</div>
                            </div>
                            <div style="text-align: center; flex: 1; min-width: 200px;">
                                <div style="border-bottom: 1px solid #2c3e50; width: 150px; margin: 0 auto 10px; padding-bottom: 40px;"></div>
                                <div style="font-weight: 700; color: #2c3e50;">Parent/Guardian</div>
                                <div style="font-size: 12px; color: #7f8c8d; margin-top: 5px;">Name & Signature</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#reportCardContainer').html(reportCardHTML);
        }
        
        // Get grade CSS class based on actual grade
        function getGradeClass(grade) {
            if (!grade) return 'grade-badge';
            
            const cleanGrade = grade.toUpperCase().replace('+', '').replace('-', '');
            
            switch(cleanGrade) {
                case 'A1':
                case 'A': return 'grade-badge grade-a1';
                case 'A2': return 'grade-badge grade-a2';
                case 'B1':
                case 'B': return 'grade-badge grade-b1';
                case 'B2': return 'grade-badge grade-b2';
                case 'C1':
                case 'C': return 'grade-badge grade-c1';
                case 'C2': return 'grade-badge grade-c2';
                case 'D': return 'grade-badge grade-d';
                case 'E1':
                case 'E2':
                case 'E': return 'grade-badge grade-e';
                default: return 'grade-badge';
            }
        }
        
        // Determine promotion status
        function determinePromotionStatus(stats) {
            if (stats.overall_percentage >= 75) {
                return {
                    status: 'PROMOTED',
                    color: '#27ae60',
                    message: 'Excellent performance! Student is promoted to next class with distinction.'
                };
            } else if (stats.overall_percentage >= 60) {
                return {
                    status: 'PROMOTED',
                    color: '#27ae60',
                    message: 'Good performance. Student is promoted to next class.'
                };
            } else if (stats.overall_percentage >= 33) {
                return {
                    status: 'CONDITIONALLY PROMOTED',
                    color: '#f39c12',
                    message: 'Student needs improvement. Promoted with conditions.'
                };
            } else {
                return {
                    status: 'NOT PROMOTED',
                    color: '#e74c3c',
                    message: 'Student needs to repeat the class. Requires special attention.'
                };
            }
        }
        
        // Get teacher remarks
        function getTeacherRemarks(percentage) {
            if (percentage >= 90) {
                return "Excellent performance! Student has shown outstanding academic abilities and consistent hard work throughout the year. Maintains excellent discipline and participates actively in all school activities. Shows remarkable understanding of concepts and applies knowledge creatively. Keep up the excellent work!";
            } else if (percentage >= 75) {
                return "Very good performance. Student is diligent, attentive in class and completes assignments on time. Shows good understanding of concepts and has consistent study habits. Participates well in classroom discussions and shows improvement throughout the year. Continue with the same dedication.";
            } else if (percentage >= 60) {
                return "Good performance. Student shows satisfactory understanding of subjects. Regular attendance and participation observed. Can improve with more focused effort and regular revision. Shows potential for better performance with consistent practice.";
            } else if (percentage >= 33) {
                return "Satisfactory performance. Student needs to put more effort in studies. Regular revision and practice recommended. Shows potential for improvement with better time management and study habits. Parental guidance and support would be beneficial.";
            } else {
                return "Needs improvement. Student requires special attention and remedial classes. Regular attendance and parental guidance necessary for better performance. Shows difficulty in understanding concepts - recommend additional support and regular practice sessions.";
            }
        }
        
        // Generate PDF
        async function generatePDF() {
            showLoading();
            
            try {
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('p', 'mm', 'a4');
                const element = document.getElementById('reportCardContent');
                
                const canvas = await html2canvas(element, {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                    backgroundColor: '#ffffff',
                    width: element.offsetWidth,
                    height: element.scrollHeight
                });
                
                const imgData = canvas.toDataURL('image/png');
                const imgWidth = 210;
                const pageHeight = 297;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                
                let heightLeft = imgHeight;
                let position = 0;
                
                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
                
                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }
                
                const studentName = $('div:contains("STUDENT INFORMATION")').next().find('div').first().text().trim().replace(/\s+/g, '_');
                const fileName = `Report_Card_${studentName}_${new Date().getFullYear()}.pdf`;
                
                pdf.save(fileName);
                
                showNotification('Report card PDF downloaded successfully!', 'success');
                
            } catch (error) {
                console.error('Error generating PDF:', error);
                showNotification('Error generating PDF. Please try again.', 'error');
            } finally {
                hideLoading();
            }
        }
        
        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            `;
            notification.innerHTML = `
                <strong>${type.toUpperCase()}:</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 5000);
        }
        
        // Loading functions
        function showLoading() {
            $('#loadingOverlay').addClass('show');
        }
        
        function hideLoading() {
            $('#loadingOverlay').removeClass('show');
        }
    </script>
@endsection
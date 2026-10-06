<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
:root {
    --primary-blue: #2C5AA0;
    --secondary-blue: #3B82F6;
    --light-blue: #EFF6FF;
    --dark-blue: #1E3A8A;
    --accent-blue: #60A5FA;
    --success-green: #10B981;
    --text-dark: #1F2937;
    --text-light: #6B7280;
    --border-color: #D1D5DB;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.top-bar {
    background: white;
    padding: 20px 40px;
    box-shadow: var(--shadow);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    display: flex;
    align-items: center;
    gap: 12px;
}

.logo-icon {
    width: 40px;
    height: 40px;
    background: var(--primary-blue);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.logo-text h3 {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary-blue);
}

.logo-text p {
    font-size: 12px;
    color: var(--text-light);
}

.ref-id {
    text-align: right;
}

.ref-id div:first-child {
    font-size: 14px;
    color: var(--text-light);
    margin-bottom: 5px;
}

.ref-id div:last-child {
    font-size: 16px;
    font-weight: 700;  
    color: var(--primary-blue);
}

.container {
    margin: 30px auto;
    padding: 0 20px;
}

.form-wrapper {
    background: white;
    border-radius: 20px;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

.form-header {
    background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
    color: white;
    padding: 35px 40px;
}

.form-header h1 {
    font-size: 28px;
    margin-bottom: 10px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 15px;
}

.form-header p {
    opacity: 0.9;
    font-size: 15px;
}

.form-content {
    padding: 40px;
}

.registration-info {
    background: var(--light-blue);
    border: 2px solid var(--accent-blue);
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.info-item {
    padding: 10px 0;
}

.info-label {
    font-size: 13px;
    color: var(--primary-blue);
    font-weight: 600;
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-value {
    font-size: 15px;
    color: var(--text-dark);
    font-weight: 500;
}

.section-card {
    background: #F8FAFC;
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 25px;
    transition: all 0.3s ease;
}

.section-card:hover {
    border-color: var(--accent-blue);
    box-shadow: var(--shadow);
}

.section-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.section-icon {
    width: 50px;
    height: 50px;
    background: var(--light-blue);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-blue);
    font-size: 20px;
}

.section-title {
    flex: 1;
}

.section-title h3 {
    font-size: 18px;
    color: var(--text-dark);
    margin-bottom: 5px;
    font-weight: 700;
}

.section-title p {
    font-size: 14px;
    color: var(--text-light);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-dark);
    font-size: 14px;
}

.required::after {
    content: " *";
    color: #EF4444;
}

input, select, textarea {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--border-color);
    border-radius: 10px;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    transition: all 0.2s ease;
    background: white;
    height: 48px;
    box-sizing: border-box;
}

input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.input-with-icon {
    position: relative;
}

.input-with-icon i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-light);
    z-index: 1;
}

.input-with-icon input {
    padding-left: 45px;
}

/* Fixed size experience container */
.experience-container {
    display: flex;
    align-items: center;
    border: 2px solid var(--border-color);
    border-radius: 10px;
    overflow: hidden;
    background: white;
    transition: all 0.2s ease;
    height: 48px;
    width: 100%;
    box-sizing: border-box;
}

.experience-container:focus-within {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.experience-field {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    position: relative;
}

.experience-field:first-child {
    border-right: 1px solid var(--border-color);
}

.experience-field input {
    width: 100%;
    height: 100%;
    border: none;
    padding: 0 8px;
    text-align: center;
    font-size: 14px;
    font-weight: 500;
    background: transparent;
}

.experience-field input:focus {
    outline: none;
    box-shadow: none;
}

.experience-field .label {
    position: absolute;
    right: 8px;
    font-size: 10px;
    color: var(--text-light);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    background: white;
    padding: 2px 4px;
    border-radius: 4px;
    pointer-events: none;
}

.experience-field input[type="number"]::-webkit-inner-spin-button, 
.experience-field input[type="number"]::-webkit-outer-spin-button { 
    opacity: 0.2;
    height: 16px;
}

.experience-field input::placeholder {
    color: #aaa;
    font-size: 13px;
    font-weight: 300;
}

/* For Firefox */
.experience-field input[type=number] {
    -moz-appearance: textfield;
    appearance: textfield;
}

.experience-field input[type=number]:hover {
    -moz-appearance: initial;
}

.interview-type-section {
    background: var(--light-blue);
    border-color: var(--accent-blue);
}

.form-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 40px;
    padding-top: 25px;
    border-top: 1px solid var(--border-color);
}

.btn {
    padding: 12px 32px;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 10px;
    height: 48px;
}

#otherProfessionField,
#otherQualificationField,
#otherApplyingForField,
#otherSubjectField {
    animation: slideDown 0.2s ease-out;
}

#otherProfessionField input,
#otherQualificationField input,
#otherApplyingForField input,
#otherSubjectField input {
    border: 2px solid var(--border-color);
    border-radius: 8px;
    background: white;
    transition: all 0.2s ease;
}

#otherProfessionField input:focus,
#otherQualificationField input:focus,
#otherApplyingForField input:focus,
#otherSubjectField input:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    outline: none;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Skill Tags Styling */
.skill-tag {
    display: inline-block;
    padding: 8px 16px;
    background: white;
    border: 2px solid var(--border-color);
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-dark);
    cursor: pointer;
    transition: all 0.2s ease;
    user-select: none;
    margin: 0 5px 5px 0;
}

.skill-tag:hover {
    border-color: var(--primary-blue);
    background: var(--light-blue);
    transform: translateY(-1px);
}

.skill-tag.selected {
    background: var(--primary-blue);
    border-color: var(--primary-blue);
    color: white;
}

.selected-skill-item {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px 6px 16px;
    background: var(--primary-blue);
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    color: white;
    gap: 8px;
    margin: 0 5px 5px 0;
}

.selected-skill-item i {
    cursor: pointer;
    font-size: 12px;
    opacity: 0.8;
    transition: opacity 0.2s ease;
}

.selected-skill-item i:hover {
    opacity: 1;
}

.btn-outline {
    background: white;
    border: 2px solid var(--border-color);
    color: var(--text-dark);
}

.btn-outline:hover {
    border-color: var(--primary-blue);
    background: var(--light-blue);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.declaration {
    background: var(--light-blue);
    border-left: 4px solid var(--primary-blue);
    padding: 20px;
    border-radius: 10px;
    margin-top: 30px;
}

.declaration h4 {
    color: var(--primary-blue);
    margin-bottom: 10px;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.declaration p {
    color: var(--text-dark);
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 15px;
}

.declaration ul {
    color: var(--text-dark);
    font-size: 14px;
    margin-left: 20px;
    margin-bottom: 15px;
}

.checkbox-group {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.checkbox-option {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.checkbox-option input {
    width: 18px;
    height: 18px;
    margin-top: 3px;
}

.checkbox-text {
    font-size: 14px;
    color: var(--text-dark);
}

.checkbox-text a {
    color: var(--primary-blue);
    text-decoration: none;
}

.checkbox-text a:hover {
    text-decoration: underline;
}

.hidden-field {
    display: none;
    margin-top: 15px;
    padding: 15px;
    background: white;
    border-radius: 8px;
    border-left: 3px solid var(--primary-blue);
}

@media (max-width: 768px) {
    .form-content {
        padding: 20px;
    }
    
    .top-bar {
        padding: 15px 20px;
        flex-direction: column;
        gap: 15px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
        gap: 15px;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .checkbox-group {
        flex-direction: column;
    }
}
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-file-signature"></i>
            </div>
            <div class="logo-text">
                <h3>Institute</h3>
                <p>Applicant Registration</p>
            </div>
        </div>
        
        <div class="ref-id">
            <div>
                <i class="fas fa-laptop"></i> <span id="modeDisplay">Online</span>
            </div>
            <div id="referenceId">INT-2024-001</div>
        </div>
    </div>
    
    <div class="container">
        <div class="form-wrapper">
            <div class="form-header">
                <h1><i class="fas fa-file-signature"></i> Applicant Registration</h1> 
                <p>Complete your application for admission/registration</p>
            </div>
            
            <div class="form-content">
                <div class="registration-info">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-user-check"></i> Registration Type
                            </div>
                            <div class="info-value">Applicant Registration</div> 
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-laptop-house"></i> Mode
                            </div>
                            <div class="info-value" id="modeValue">Online</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-bullhorn"></i> Referral Source 
                            </div>
                            <div class="info-value" id="sourceValue">Not specified</div>
                        </div>
                    </div>
                </div>
                
                <!-- Personal Information Section -->
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="section-title">
                            <h3>Personal Information</h3> 
                            <p>Basic details about the applicant</p>  
                        </div>
                    </div>
                    
                    <!-- Row 1: Full Name and Email -->
                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label class="required">Full Name</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user"></i>
                                <input type="text" id="fullName" placeholder="Enter your full name">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="required">Email Address</label>
                            <div class="input-with-icon">
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="email" placeholder="your.email@example.com">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Row 2: Phone and Date of Birth -->
                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label class="required">Phone Number</label> 
                            <div class="input-with-icon">
                                <i class="fas fa-phone"></i>
                                <input type="tel" id="phone" placeholder="+91 98765 43210">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <div class="input-with-icon">
                                <i class="fas fa-calendar-alt"></i>
                                <input type="date" id="dob"> 
                            </div>
                        </div>
                    </div>
                    
                    <!-- Row 3: Marital Status -->
                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label>Marital Status</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user"></i>
                                <select id="marital_status" style="padding-left: 45px;">
                                    <option value="">Select marital status</option>
                                    <option value="single">Single</option>
                                    <option value="married">Married</option>
                                    <option value="divorced">Divorced</option>
                                    <option value="widowed">Widowed</option>
                                    <option value="separated">Separated</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <!-- Empty div to maintain two-column layout -->
                        </div>
                    </div>
                </div>
                
                <!-- Professional Information Section -->
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="section-title">
                            <h3>Professional Information</h3>
                            <p>Your professional background and qualifications</p>
                        </div>
                    </div>
                    
                    <!-- Row 1: Current Profession and Organization -->
                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label>Current Profession</label>
                            <select id="profession" onchange="toggleOtherProfession()">
                                <option value="">Select profession</option>
                                <option value="student">Student</option>
                                <option value="teacher">Teacher</option>
                                <option value="manager">Manager</option>
                                <option value="assistant_manager">Assistant manager</option>
                                <option value="principal">Principal</option>
                                <option value="hod">HOD</option>
                                <option value="executive">Executive</option>
                                <option value="professor">Professor</option>
                                <option value="assistant_professor">Assistant professor</option>
                                <option value="dean">Dean</option>
                                <option value="chairman">Chairman</option>
                                <option value="coordinator">Coordinator</option>
                                <option value="other">Other (Please specify)</option>
                            </select>
                            
                            <!-- Other Profession Field -->
                            <div id="otherProfessionField" style="display: none; margin-top: 10px;">
                                <input type="text" 
                                       id="other_profession_details" 
                                       placeholder="Please specify your profession"
                                       style="width: 100%; padding: 10px;">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Current Organization</label>
                            <input type="text" id="organization" placeholder="School/Company name">
                        </div>
                    </div>
                    
                 
                    <!-- Row 3: Total Experience and Highest Qualification -->
                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label>Total Experience</label>
                            <div class="experience-container">
                                <div class="experience-field">
                                    <input type="number" id="experience_years" min="0" max="50" placeholder="0">
                                    <span class="label">YRS</span>
                                </div>
                                <div class="experience-field">
                                    <input type="number" id="experience_months" min="0" max="11" placeholder="0">
                                    <span class="label">MTH</span>
                                </div>
                            </div>
                            <small style="color: var(--text-light); margin-top: 5px; display: block;">
                                <i class="fas fa-info-circle"></i> Years and months
                            </small>
                            <input type="hidden" id="experience" name="experience">
                        </div>
                        
                        <div class="form-group">
                            <label>Highest Qualification</label>
                            <select id="highest_qualification" onchange="toggleOtherQualification()">
                                <option value="">Select qualification</option>
                                <option value="high_school">High School</option>
                                <option value="diploma">Diploma</option>
                                <option value="bachelors">Bachelor's Degree</option>
                                <option value="masters">Master's Degree</option>
                                <option value="phd">PhD</option>
                                <option value="bed">B.Ed</option>
                                <option value="med">M.Ed</option>
                                <option value="d.el.ed">D.El.Ed</option>
                                <option value="other">Other (Please specify)</option>
                            </select>
                            
                            <!-- Other Qualification Field -->
                            <div id="otherQualificationField" style="display: none; margin-top: 10px;">
                                <input type="text" 
                                       id="other_qualification_details" 
                                       placeholder="Please specify your qualification"
                                       style="width: 100%; padding: 10px;">
                            </div>
                        </div>
                    </div>
                     <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div class="form-group">
    <label class="required">Applying For</label>

    <select id="applying_for" name="department" onchange="toggleOtherApplyingFor()">
        <option value="">Select department</option>

        @foreach($departments as $dept)
            <option value="{{ $dept }}">
                {{ ucfirst(str_replace('_', ' ', $dept)) }}
            </option>
        @endforeach

        <option value="other">Other (Please specify)</option>
    </select>

    <!-- Other Applying For Field -->
    <div id="otherApplyingForField" style="display: none; margin-top: 10px;">
        <input type="text" 
               id="other_applying_for_details" 
               name="other_department"
               placeholder="Please specify position"
               style="width: 100%; padding: 10px; border: 2px solid var(--border-color); border-radius: 8px;">
    </div>
</div>
                        
                        <!-- <div class="form-group">
                            <label>Preferred Subject/Specialization</label>
                            <select id="preferred_subject" onchange="toggleOtherSubject()">
                                <option value="">Select subject</option>
                                <option value="mathematics">Mathematics</option>
                                <option value="science">Science</option>
                                <option value="physics">Physics</option>
                                <option value="chemistry">Chemistry</option>
                                <option value="biology">Biology</option>
                                <option value="english">English</option>
                                <option value="hindi">Hindi</option>
                                <option value="social_studies">Social Studies</option>
                                <option value="history">History</option>
                                <option value="geography">Geography</option>
                                <option value="computer_science">Computer Science</option>
                                <option value="physical_education">Physical Education</option>
                                <option value="arts">Arts</option>
                                <option value="music">Music</option>
                                <option value="commerce">Commerce</option>
                                <option value="economics">Economics</option>
                                <option value="business_studies">Business Studies</option>
                                <option value="accountancy">Accountancy</option>
                                <option value="other">Other (Please specify)</option>
                            </select>
                            
                       
                            <div id="otherSubjectField" style="display: none; margin-top: 10px;">
                                <input type="text" 
                                       id="other_subject_details" 
                                       placeholder="Please specify subject"
                                       style="width: 100%; padding: 10px;">
                            </div>
                        </div> -->
                    </div>
                </div>
                
                <!-- Skills Section -->
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <div class="section-title">
                            <h3>Skills & Competencies</h3>
                            <p>Add your skills (especially relevant for school roles)</p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="required">Select Skills</label>
                        
                        <!-- Predefined School-Related Skills -->
                        <div style="margin-bottom: 20px;">
                            <div style="font-size: 14px; color: var(--text-dark); margin-bottom: 12px; font-weight: 500;">
                                <i class="fas fa-graduation-cap"></i> Teaching & Academic Skills:
                            </div>
                            <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;" id="teachingSkills">
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Lesson Planning">Lesson Planning</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Classroom Management">Classroom Management</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Curriculum Development">Curriculum Development</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Student Assessment">Student Assessment</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Differentiated Instruction">Differentiated Instruction</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Child Psychology">Child Psychology</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Special Education">Special Education</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Early Childhood Education">Early Childhood Education</span>
                            </div>
                            
                            <div style="font-size: 14px; color: var(--text-dark); margin-bottom: 12px; font-weight: 500;">
                                <i class="fas fa-laptop"></i> Technical Skills:
                            </div>
                            <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;" id="technicalSkills">
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="MS Office">MS Office</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Google Classroom">Google Classroom</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Zoom/Meet">Zoom/Meet</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Learning Management System">Learning Management System</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Educational Technology">Educational Technology</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Online Teaching Tools">Online Teaching Tools</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Data Analysis">Data Analysis</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Programming Basics">Programming Basics</span>
                            </div>
                            
                            <div style="font-size: 14px; color: var(--text-dark); margin-bottom: 12px; font-weight: 500;">
                                <i class="fas fa-heart"></i> Soft Skills:
                            </div>
                            <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;" id="softSkills">
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Communication">Communication</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Leadership">Leadership</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Team Collaboration">Team Collaboration</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Problem Solving">Problem Solving</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Patience">Patience</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Creativity">Creativity</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Empathy">Empathy</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Adaptability">Adaptability</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Conflict Resolution">Conflict Resolution</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Time Management">Time Management</span>
                            </div>
                            
                            <div style="font-size: 14px; color: var(--text-dark); margin-bottom: 12px; font-weight: 500;">
                                <i class="fas fa-language"></i> Languages:
                            </div>
                            <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;" id="languageSkills">
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="English">English</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Hindi">Hindi</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Regional Language">Regional Language</span>
                                <span class="skill-tag" onclick="toggleSkill(this)" data-skill="Foreign Language">Foreign Language</span>
                            </div>
                        </div>
                        
                        <!-- Selected Skills Display -->
                        <div style="background: var(--light-blue); padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                            <label style="color: var(--primary-blue); margin-bottom: 10px; font-size: 14px;">
                                <i class="fas fa-check-circle"></i> Selected Skills:
                            </label>
                            <div id="selectedSkillsContainer" style="display: flex; flex-wrap: wrap; gap: 8px; min-height: 40px;">
                                <!-- Selected skills will appear here -->
                                <span style="color: var(--text-light); font-style: italic;">No skills selected yet</span>
                            </div>
                        </div>
                        
                        <!-- Add Custom Skill -->
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <div style="flex: 1;">
                                <input type="text" 
                                       id="customSkillInput" 
                                       placeholder="Enter a custom skill" 
                                       style="width: 100%;">
                            </div>
                            <button type="button" 
                                    class="btn btn-outline" 
                                    onclick="addCustomSkill()"
                                    style="padding: 10px 20px; height: 48px;">
                                <i class="fas fa-plus"></i> Add Skill
                            </button>
                        </div>
                        <small style="color: var(--text-light); margin-top: 10px; display: block;">
                            <i class="fas fa-info-circle"></i> Click on skills above to select them. Add your own skills using the custom field.
                        </small>
                        
                        <!-- Hidden input to store selected skills for form submission -->
                        <input type="hidden" id="selectedSkills" name="skills">
                    </div>
                </div>
                
                <!-- Interview Purpose Section -->
                <div class="section-card interview-type-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-comment-dots"></i>
                        </div>
                        <div class="section-title">
                            <h3>Application Purpose</h3>
                            <p>Tell us why you're applying</p> 
                        </div>
                    </div>
                    
                    <!-- Full width for textarea -->
                    <div class="form-group">
                        <label class="required">Purpose of Application</label>
                        <textarea id="purpose" rows="4" required placeholder="Please describe why you're applying, what you hope to achieve, and any specific topics you'd like to discuss..."></textarea>
                    </div>
                </div>
                
                <!-- File Upload Section -->
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-file-upload"></i>
                        </div>
                        <div class="section-title">
                            <h3>Document Upload</h3>
                            <p>Upload your resume/CV</p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="required">Upload Resume/CV</label>
                        <div>
                            <input type="file" 
                                id="resume" 
                                name="resume" 
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" 
                                style="padding: 8px; border: 2px dashed var(--border-color); background: #f9f9f9; height: auto; width: 100%;">
                        </div>
                        <small style="color: var(--text-light); margin-top: 5px; display: block;">
                            <i class="fas fa-info-circle"></i> PDF, DOC, DOCX, JPG, PNG (Max: 5MB)
                        </small>
                    </div> 
                </div>
                
                <div class="declaration">
                    <h4><i class="fas fa-file-contract"></i> Terms & Conditions</h4>
                    <p>By submitting this form:</p>
                    <ul>
                        <li>I confirm that all information provided is accurate</li>
                        <li>I agree to be contacted by the institute</li>
                        <li>I understand that interview timing will be confirmed later</li>
                        <li>I will receive confirmation details via email</li>
                    </ul>
                    
                    <div class="checkbox-group">
                        <div class="checkbox-option">
                            <input type="checkbox" id="declarationCheck" required>
                            <div class="checkbox-text">
                                <strong>I agree to the terms and conditions</strong>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button class="btn btn-outline" onclick="window.location.href='main-form'">
                        <i class="fas fa-arrow-left"></i> Back to Home
                    </button>
                    <button class="btn btn-primary" onclick="submitInterviewForm()">
                        <i class="fas fa-paper-plane"></i> Submit Application
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Array to store selected skills
        let selectedSkills = [];

        // Toggle skill selection
        function toggleSkill(element) {
            const skill = element.getAttribute('data-skill');
            
            if (element.classList.contains('selected')) {
                // Remove from selected
                element.classList.remove('selected');
                selectedSkills = selectedSkills.filter(s => s !== skill);
            } else {
                // Add to selected
                element.classList.add('selected');
                selectedSkills.push(skill);
            }
            
            updateSelectedSkillsDisplay();
        }

        // Add custom skill
        function addCustomSkill() {
            const input = document.getElementById('customSkillInput');
            const skill = input.value.trim();
            
            if (skill === '') {
                alert('Please enter a skill');
                return;
            }
            
            if (!selectedSkills.includes(skill)) {
                selectedSkills.push(skill);
                updateSelectedSkillsDisplay();
                input.value = ''; // Clear input
                
                // Optional: Add visual feedback
                showResumeSuccess('Skill "' + skill + '" added successfully!');
            } else {
                alert('This skill is already selected');
            }
        }

        // Update selected skills display
        function updateSelectedSkillsDisplay() {
            const container = document.getElementById('selectedSkillsContainer');
            const hiddenInput = document.getElementById('selectedSkills');
            
            if (selectedSkills.length === 0) {
                container.innerHTML = '<span style="color: var(--text-light); font-style: italic;">No skills selected yet</span>';
            } else {
                let html = '';
                selectedSkills.forEach(skill => {
                    html += `<span class="selected-skill-item">
                        ${skill} <i class="fas fa-times" onclick="removeSkill('${skill}')"></i>
                    </span>`;
                });
                container.innerHTML = html;
            }
            
            // Update hidden input for form submission
            hiddenInput.value = JSON.stringify(selectedSkills);
        }

        // Remove individual skill
        function removeSkill(skill) {
            selectedSkills = selectedSkills.filter(s => s !== skill);
            
            // Also remove selected class from corresponding tag if it exists
            const tags = document.querySelectorAll('.skill-tag');
            tags.forEach(tag => {
                if (tag.getAttribute('data-skill') === skill) {
                    tag.classList.remove('selected');
                }
            });
            
            updateSelectedSkillsDisplay();
        }

        // Toggle other profession field
        function toggleOtherProfession() {
            const profession = document.getElementById('profession').value;
            const otherField = document.getElementById('otherProfessionField');
            
            if (profession === 'other') {
                otherField.style.display = 'block';
                document.getElementById('other_profession_details').setAttribute('required', 'required');
            } else {
                otherField.style.display = 'none';
                document.getElementById('other_profession_details').removeAttribute('required');
                document.getElementById('other_profession_details').value = ''; // Clear the field
            }
        }

        // Toggle other qualification field
        function toggleOtherQualification() {
            const qualification = document.getElementById('highest_qualification').value;
            const otherField = document.getElementById('otherQualificationField');
            
            if (qualification === 'other') {
                otherField.style.display = 'block';
                document.getElementById('other_qualification_details').setAttribute('required', 'required');
            } else {
                otherField.style.display = 'none';
                document.getElementById('other_qualification_details').removeAttribute('required');
                document.getElementById('other_qualification_details').value = ''; // Clear the field
            }
        }

        // Toggle other applying for field
        function toggleOtherApplyingFor() {
            const applyingFor = document.getElementById('applying_for').value;
            const otherField = document.getElementById('otherApplyingForField');
            
            if (applyingFor === 'other') {
                otherField.style.display = 'block';
                document.getElementById('other_applying_for_details').setAttribute('required', 'required');
            } else {
                otherField.style.display = 'none';
                document.getElementById('other_applying_for_details').removeAttribute('required');
                document.getElementById('other_applying_for_details').value = '';
            }
        }

        // Toggle other subject field
        function toggleOtherSubject() {
            const subject = document.getElementById('preferred_subject').value;
            const otherField = document.getElementById('otherSubjectField');
            
            if (subject === 'other') {
                otherField.style.display = 'block';
                document.getElementById('other_subject_details').setAttribute('required', 'required');
            } else {
                otherField.style.display = 'none';
                document.getElementById('other_subject_details').removeAttribute('required');
                document.getElementById('other_subject_details').value = '';
            }
        }

        // Load saved data
        document.addEventListener('DOMContentLoaded', function() {
            const mode = localStorage.getItem('fincap_mode') || 'online';
            const source = localStorage.getItem('fincap_source') || '';
            
            document.getElementById('modeDisplay').textContent = mode === 'online' ? 'Online' : 'In-Person';
            document.getElementById('modeValue').textContent = mode === 'online' ? 'Online Registration' : 'In-Person Registration';
            document.getElementById('sourceValue').textContent = getSourceText(source);
            
            // Generate reference ID
            const refId = 'APP-' + new Date().getFullYear() + '-' + Math.floor(1000 + Math.random() * 9000);
            document.getElementById('referenceId').textContent = refId;
            
            // Add resume file validation
            document.getElementById('resume').addEventListener('change', validateResume);
        });

        // Resume validation function
        function validateResume() {
            const resumeInput = document.getElementById('resume');
            const file = resumeInput.files[0];
            const fileSizeLimit = 5 * 1024 * 1024; // 5MB in bytes
            
            // Check if there's an existing error message and remove it
            const existingError = document.getElementById('resume-error');
            if (existingError) {
                existingError.remove();
            }
            
            // Remove any error styling
            resumeInput.style.borderColor = '';
            resumeInput.style.backgroundColor = '';
            
            if (!file) {
                return; // No file selected, no error
            }
            
            // Allowed file extensions
            const allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
            const fileName = file.name;
            const fileExtension = fileName.split('.').pop().toLowerCase();
            
            // Check file extension
            if (!allowedExtensions.includes(fileExtension)) {
                showResumeError('Invalid file format. Please upload PDF, DOC, DOCX, JPG, or PNG files only.');
                resumeInput.value = ''; // Clear the file input
                return;
            }
            
            // Check file size
            if (file.size > fileSizeLimit) {
                const fileSizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                showResumeError(`File size (${fileSizeInMB} MB) exceeds the 5MB limit. Please upload a smaller file.`);
                resumeInput.value = ''; // Clear the file input
                return;
            }
            
            // If file is valid, show success message
            const fileSizeInKB = (file.size / 1024).toFixed(2);
            showResumeSuccess(`File accepted: ${file.name} (${fileSizeInKB} KB)`);
        }

        // Function to show error message
        function showResumeError(message) {
            const resumeInput = document.getElementById('resume');
            const resumeContainer = resumeInput.parentElement;
            
            // Style the input as error
            resumeInput.style.borderColor = '#EF4444';
            resumeInput.style.backgroundColor = '#FEF2F2';
            
            // Create error message element
            const errorDiv = document.createElement('div');
            errorDiv.id = 'resume-error';
            errorDiv.style.color = '#EF4444';
            errorDiv.style.fontSize = '13px';
            errorDiv.style.marginTop = '8px';
            errorDiv.style.display = 'flex';
            errorDiv.style.alignItems = 'center';
            errorDiv.style.gap = '5px';
            
            // Add icon and message
            errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
            
            // Append error message after the input
            resumeContainer.appendChild(errorDiv);
        }

        // Function to show success message
        function showResumeSuccess(message) {
            const resumeInput = document.getElementById('resume');
            const resumeContainer = resumeInput.parentElement;
            
            // Style the input as success
            resumeInput.style.borderColor = '#10B981';
            resumeInput.style.backgroundColor = '#F0FDF4';
            
            // Create success message element
            const successDiv = document.createElement('div');
            successDiv.id = 'resume-success';
            successDiv.style.color = '#10B981';
            successDiv.style.fontSize = '13px';
            successDiv.style.marginTop = '8px';
            successDiv.style.display = 'flex';
            successDiv.style.alignItems = 'center';
            successDiv.style.gap = '5px';
            
            // Add icon and message
            successDiv.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
            
            // Append success message after the input
            resumeContainer.appendChild(successDiv);
            
            // Remove success message after 3 seconds
            setTimeout(() => {
                const successElement = document.getElementById('resume-success');
                if (successElement) {
                    successElement.remove();
                }
                // Reset border color but keep the file
                resumeInput.style.borderColor = '';
                resumeInput.style.backgroundColor = '';
            }, 3000);
        }

        function getSourceText(source) {
            const sources = {
                'friend_family': 'Friend or Family Referral',
                'social_media': 'Social Media',
                'website': 'Institute Website',
                'newspaper': 'Newspaper Ad',
                'seminar': 'Seminar/Workshop',
                'alumni': 'Alumni Reference',
                'school': 'School/College Referral',
                'other': 'Other Source'
            };
            return sources[source] || source || 'Not specified';
        }
        
        function submitInterviewForm() {
            // Check declaration
            if (!document.getElementById('declarationCheck').checked) {
                alert('Please agree to the terms and conditions');
                return;
            }
            
            // Validate required fields
            if (!document.getElementById('fullName').value) {
                alert('Please enter your full name');
                return;
            }
            if (!document.getElementById('email').value) {
                alert('Please enter your email address');
                return;
            }
            if (!document.getElementById('phone').value) {
                alert('Please enter your phone number');
                return;
            }
            if (!document.getElementById('purpose').value) {
                alert('Please describe the purpose of application');
                return;
            }
            
            // Validate applying for
            if (!document.getElementById('applying_for').value) {
                alert('Please select the position you are applying for');
                return;
            }
            
            // Validate applying for if "Other" is selected
            const applyingFor = document.getElementById('applying_for').value;
            if (applyingFor === 'other' && !document.getElementById('other_applying_for_details').value) {
                alert('Please specify the position you are applying for');
                return;
            }
            
            // Validate subject if "Other" is selected
            const subject = document.getElementById('preferred_subject').value;
            if (subject === 'other' && !document.getElementById('other_subject_details').value) {
                alert('Please specify your preferred subject');
                return;
            }
            
            // Validate profession if "Other" is selected
            const profession = document.getElementById('profession').value;
            if (profession === 'other' && !document.getElementById('other_profession_details').value) {
                alert('Please specify your profession');
                return;
            }
            
            // Validate qualification if "Other" is selected
            const qualification = document.getElementById('highest_qualification').value;
            if (qualification === 'other' && !document.getElementById('other_qualification_details').value) {
                alert('Please specify your qualification details');
                return;
            }
            
            // Validate skills
            if (selectedSkills.length === 0) {
                alert('Please select at least one skill');
                return;
            }
            
            // Validate resume
            const resumeFile = document.getElementById('resume').files[0];
            if (!resumeFile) {
                alert('Please upload your resume/CV');
                return;
            }
            
            // Check file size (max 5MB)
            if (resumeFile.size > 5 * 1024 * 1024) {
                alert('File size should be less than 5MB');
                return;
            }
            
            // Validate experience fields
            const yearsInput = document.getElementById('experience_years').value;
            const monthsInput = document.getElementById('experience_months').value;
            
            const years = yearsInput === '' ? 0 : parseInt(yearsInput);
            const months = monthsInput === '' ? 0 : parseInt(monthsInput);
            
            if (yearsInput !== '' && (years < 0 || years > 50)) {
                alert('Please enter valid years of experience (0-50)');
                return;
            }
            if (monthsInput !== '' && (months < 0 || months > 11)) {
                alert('Please enter valid months of experience (0-11)');
                return;
            }
            
            // Create FormData object for file upload
            const formData = new FormData();
            
            // Add all form fields
            formData.append('mode', localStorage.getItem('fincap_mode') || 'online');
            formData.append('source', localStorage.getItem('fincap_source') || '');
            formData.append('full_name', document.getElementById('fullName').value);
            formData.append('email', document.getElementById('email').value);
            formData.append('phone', document.getElementById('phone').value);
            formData.append('dob', document.getElementById('dob').value);
            formData.append('marital_status', document.getElementById('marital_status').value);
            
            // Handle profession
            let professionValue = profession;
            if (profession === 'other') {
                professionValue = document.getElementById('other_profession_details').value;
                formData.append('profession', professionValue);
                formData.append('other_profession_details', document.getElementById('other_profession_details').value);
            } else {
                formData.append('profession', professionValue);
            }
            
            formData.append('organization', document.getElementById('organization').value);
            
            // Handle applying for
            let applyingForValue = applyingFor;
            if (applyingFor === 'other') {
                applyingForValue = document.getElementById('other_applying_for_details').value;
                formData.append('applying_for', applyingForValue);
                formData.append('other_applying_for_details', document.getElementById('other_applying_for_details').value);
            } else {
                formData.append('applying_for', applyingForValue);
            }
            
            // Handle preferred subject
            let subjectValue = document.getElementById('preferred_subject').value;
            if (subjectValue === 'other') {
                subjectValue = document.getElementById('other_subject_details').value;
                formData.append('preferred_subject', subjectValue);
                formData.append('other_subject_details', document.getElementById('other_subject_details').value);
            } else {
                formData.append('preferred_subject', subjectValue);
            }
            
            // Experience fields
            formData.append('experience_in_year', years);
            formData.append('experience_in_month', months);
            
            const totalMonths = (years * 12) + months;
            formData.append('experience_in_months', totalMonths);
            
            // Handle qualification
            let qualificationValue = qualification;
            if (qualification === 'other') {
                qualificationValue = document.getElementById('other_qualification_details').value;
                formData.append('qualification', qualificationValue);
                formData.append('other_qualification_details', document.getElementById('other_qualification_details').value);
            } else {
                formData.append('qualification', qualificationValue);
            }
            
            formData.append('highest_qualification', qualification);
            
            // Add skills
            formData.append('skills', JSON.stringify(selectedSkills));
            
            formData.append('purpose', document.getElementById('purpose').value);
            
            // Add the resume file
            formData.append('resume', resumeFile);
            
            // Add CSRF token
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            // Log the form data for debugging
            console.log('Submitting form with data:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            // Send to interview route
            fetch('/save-interview', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Server Response:', data);
                if (data.success) {
                    alert('Application submitted successfully!\nReference ID: ' + data.reference_id);
                    
                    // Clear localStorage
                    localStorage.removeItem('fincap_mode');
                    localStorage.removeItem('fincap_source');
                    
                    // Redirect to home
                    setTimeout(() => {
                        window.location.href = '/main-form';
                    }, 3000);
                } else {
                    alert('Error: ' + (data.message || 'Unknown error occurred'));
                    if (data.errors) {
                        console.error('Validation errors:', data.errors);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error submitting application. Please check console for details.');
            });
        }
        
    </script>
@endsection
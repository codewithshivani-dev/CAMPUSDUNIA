
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Font Awesome 6 (free CDN) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- Toastify CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(135deg, #eef2f7 0%, #dce5ef 100%);
      font-family: 'Inter', sans-serif;
      padding: 2rem 1.5rem;
      color: #1e2f3f;
    }

    .form-container {
      max-width: 1000px;
      margin: 0 auto;
      background: white;
      border-radius: 2rem;
      box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.25);
      overflow: hidden;
      transition: all 0.2s ease;
    }

    .form-header {
      background: #0f2b3d;
      padding: 1.8rem 2rem;
      color: white;
    }

    .form-header h1 {
      font-weight: 700;
      font-size: 1.9rem;
      letter-spacing: -0.3px;
      margin-bottom: 0.3rem;
    }

    .form-header h1 i {
      margin-right: 10px;
      color: #6abf9e;
    }

    .form-header p {
      opacity: 0.8;
      font-size: 0.95rem;
      margin-top: 0.4rem;
    }

    .form-header p i {
      margin-right: 6px;
    }

    form {
      padding: 2rem 2rem 2rem 2rem;
    }

    .two-cols {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem;
    }

    .full-width {
      grid-column: span 2;
    }

    .form-section {
      margin-bottom: 2rem;
      border-bottom: 1px solid #e4edf2;
      padding-bottom: 1.2rem;
    }

    .section-title {
      font-size: 1.35rem;
      font-weight: 600;
      margin-bottom: 1.25rem;
      color: #0f2b3d;
      display: flex;
      align-items: center;
      gap: 0.6rem;
      border-left: 4px solid #2b7a62;
      padding-left: 0.8rem;
    }

    .section-title i {
      color: #2b7a62;
      font-size: 1.3rem;
      width: 1.6rem;
    }

    .input-group {
      margin-bottom: 1.2rem;
      display: flex;
      flex-direction: column;
    }

    .input-group label {
      font-weight: 500;
      font-size: 0.85rem;
      margin-bottom: 0.4rem;
      color: #2c4b66;
      letter-spacing: -0.2px;
    }

    .input-group label i {
      margin-right: 6px;
      color: #2b7a62;
      font-size: 0.8rem;
      width: 1.2rem;
    }

    .input-group input, 
    .input-group select, 
    .input-group textarea {
      padding: 0.75rem 1rem;
      border: 1.5px solid #dde6ed;
      border-radius: 1rem;
      font-family: 'Inter', monospace;
      font-size: 0.9rem;
      background-color: #fff;
      transition: 0.2s;
      outline: none;
    }

    .input-group input:focus, 
    .input-group select:focus, 
    .input-group textarea:focus {
      border-color: #2b7a62;
      box-shadow: 0 0 0 3px rgba(43, 122, 98, 0.2);
    }

    .exp-row {
      display: flex;
      gap: 1rem;
      align-items: center;
    }
    .exp-box {
      flex: 1;
    }
    .exp-box input {
      width: 100%;
    }

    /* skills area */
    .skills-area {
      background: #fafdff;
      border-radius: 1.2rem;
      border: 1px solid #e2edf5;
      padding: 1rem 1.2rem;
    }

    .skills-palette {
      display: flex;
      flex-wrap: wrap;
      gap: 0.6rem;
      margin: 1rem 0 0.8rem 0;
      border-bottom: 1px dashed #cbdde8;
      padding-bottom: 1rem;
    }

    .skill-chip {
      background: #eef3fc;
      padding: 0.4rem 1rem;
      border-radius: 40px;
      font-size: 0.8rem;
      font-weight: 500;
      border: 1px solid #cbdde8;
      color: #1f5068;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      cursor: pointer;
      transition: 0.2s;
    }

    .skill-chip i {
      font-size: 0.7rem;
      color: #5f8aaa;
    }

    .skill-chip.selected {
      background: #2b7a62;
      color: white;
      border-color: #1f5e4b;
    }

    .skill-chip.selected i {
      color: white;
    }

    /* Skill Tags Styling */
    .skill-tag {
        display: inline-block;
        padding: 8px 16px;
        background: white;
        border: 2px solid #cde0ea;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 500;
        color: #1f5068;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
        margin: 0 5px 5px 0;
    }

    .skill-tag:hover {
        border-color: #2b7a62;
        background: #eef2f7;
        transform: translateY(-1px);
    }

    .skill-tag.selected {
        background: #2b7a62;
        border-color: #1f5e4b;
        color: white;
    }

    .selected-skill-item {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px 6px 16px;
        background: #2b7a62;
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
        border: 2px solid #cde0ea;
        color: #1e2f3f;
        padding: 10px 20px;
        height: 48px;
    }

    .btn-outline:hover {
        border-color: #2b7a62;
        background: #eef2f7;
    }

    .selected-skills-box {
      background: #f3f9fe;
      border-radius: 1rem;
      padding: 0.8rem;
      margin: 1rem 0 0.8rem;
    }

    .selected-badge {
      display: inline-block;
      background: #e0f0ea;
      padding: 0.3rem 0.8rem;
      border-radius: 30px;
      font-size: 0.8rem;
      margin: 0.25rem 0.35rem;
      font-weight: 500;
      color: #155f48;
    }

    .selected-badge i {
      margin-right: 5px;
      font-size: 0.7rem;
    }

    .custom-skill-add {
      display: flex;
      gap: 0.6rem;
      margin-top: 0.5rem;
      align-items: center;
      flex-wrap: wrap;
    }

    .custom-skill-add input {
      flex: 2;
      padding: 0.6rem 1rem;
      border-radius: 2rem;
      border: 1px solid #cde0ea;
    }

    .btn-small {
      background: #eef2f7;
      border: none;
      padding: 0.6rem 1.2rem;
      border-radius: 2rem;
      font-weight: 600;
      cursor: pointer;
      font-family: 'Inter', sans-serif;
      transition: 0.2s;
    }

    .btn-small i {
      margin-right: 5px;
    }

    .btn-small:hover {
      background: #dde4ec;
    }

    .file-upload {
      border: 1.5px dashed #bdd4e2;
      border-radius: 1.2rem;
      padding: 0.8rem;
      text-align: center;
      background: #fafeff;
    }

    .file-upload i {
      font-size: 1.8rem;
      color: #6f8eaa;
      margin-bottom: 6px;
    }

    .terms-box {
      background: #f8fafc;
      border-radius: 1rem;
      padding: 1rem 1.2rem;
      margin: 1rem 0 1.5rem;
      border: 1px solid #e2edf2;
    }

    .checkbox-line {
      display: flex;
      align-items: center;
      gap: 0.7rem;
      margin: 0.6rem 0;
    }

    .checkbox-line i {
      color: #2b7a62;
      width: 1.2rem;
      font-size: 0.9rem;
    }

    .submit-btn {
      background: #0f2b3d;
      color: white;
      font-weight: 700;
      padding: 0.9rem 1.8rem;
      border: none;
      border-radius: 3rem;
      font-size: 1rem;
      cursor: pointer;
      width: 100%;
      font-family: 'Inter', sans-serif;
      letter-spacing: 0.3px;
      transition: 0.2s;
    }

    .submit-btn i {
      margin-right: 8px;
    }

    .submit-btn:hover {
      background: #1b4a62;
      transform: scale(0.99);
    }

    .inline-note {
      font-size: 0.7rem;
      color: #6f8eaa;
      margin-top: 0.3rem;
    }

    .alert-message {
      background: #eef2ff;
      border-left: 5px solid #2b7a62;
      padding: 0.8rem;
      border-radius: 0.8rem;
      margin-bottom: 1rem;
      font-size: 0.85rem;
    }

    @media (max-width: 720px) {
      .two-cols {
        grid-template-columns: 1fr;
        gap: 0;
      }
      .full-width {
        grid-column: span 1;
      }
      form {
        padding: 1.5rem;
      }
    }

    footer {
      text-align: center;
      font-size: 0.7rem;
      padding: 1rem;
      color: #6f8eaa;
      background: #fafdff;
      border-top: 1px solid #e4edf2;
    }

    footer i {
      margin: 0 3px;
    }
  </style>

<div class="form-container">
  <div class="form-header">
    <h1><i class="fas fa-file-alt"></i> Candidate Application</h1>
   
  </div>

  <form id="jobApplicationForm" method="POST" enctype="multipart/form-data">
    <!-- Personal Information Section -->
    <div class="form-section">
      <div class="section-title">
        <i class="fas fa-user-circle"></i> Personal Information
      </div>
      <div class="two-cols">
        <div class="input-group">
          <label><i class="fas fa-user"></i> Full Name *</label>
          <input type="text" id="fullName" placeholder="e.g. Ananya Sharma" required>
        </div>
        <div class="input-group">
          <label><i class="fas fa-envelope"></i> Email Address *</label>
          <input type="email" id="email" placeholder="your.email@example.com" required>
        </div>
        <div class="input-group">
          <label><i class="fas fa-phone-alt"></i> Phone Number *</label>
          <input type="tel" id="phone" placeholder="+91 98765 43210" required>
        </div>
        <div class="input-group">
          <label><i class="fas fa-calendar-alt"></i> Date of Birth *</label>
          <input type="date" id="dob" required>
        </div>
        <div class="input-group full-width">
          <label><i class="fas fa-heart"></i> Marital Status *</label>
          <select id="maritalStatus" required>
            <option value="" disabled selected>Select marital status</option>
            <option>Single</option>
            <option>Married</option>
            <option>Divorced</option>
            <option>Widowed</option>
            <option>Prefer not to say</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Professional Information -->
    <div class="form-section">
      <div class="section-title">
        <i class="fas fa-briefcase"></i> Professional Information
      </div>
      <div class="two-cols">
        <div class="input-group">
          <label><i class="fas fa-chalkboard-user"></i> Current Profession *</label>
          <select id="profession" required>
            <option value="" disabled selected>Select profession</option>
            <option>Teacher / Educator</option>
            <option>Administrator</option>
            <option>IT Professional</option>
            <option>Marketing Specialist</option>
            <option>HR Professional</option>
            <option>Student / Fresher</option>
            <option>Other</option>
          </select>
        </div>
        <div class="input-group">
          <label><i class="fas fa-building"></i> Current Organization</label>
          <input type="text" id="organization" placeholder="School / Company name">
        </div>
        <div class="input-group full-width">
          <label><i class="fas fa-clock"></i> Total Experience *</label>
          <div class="exp-row">
            <div class="exp-box">
              <input type="number" id="expYears" placeholder="Years" min="0" max="50" value="0" step="1">
              <div class="inline-note">Years</div>
            </div>
            <div class="exp-box">
              <input type="number" id="expMonths" placeholder="Months" min="0" max="11" value="0" step="1">
              <div class="inline-note">Months</div>
            </div>
          </div>
        </div>
        <div class="input-group">
          <label><i class="fas fa-graduation-cap"></i> Highest Qualification *</label>
          <select id="qualification" required>
            <option value="" disabled selected>Select qualification</option>
            <option>High School</option>
            <option>Bachelor's Degree</option>
            <option>Master's Degree</option>
            <option>Doctorate (PhD)</option>
            <option>Diploma / Certification</option>
          </select>
        </div>
        <div class="input-group">
          <label><i class="fas fa-building-columns"></i> Applying For (Department) *</label>
          <select id="applying_for" name="applying_for" required onchange="toggleOtherDepartment()">
            <option value="" disabled selected>Select department</option>
            @foreach($departments as $department)
            <option value="{{ $department }}">{{ $department }}</option>
            @endforeach
            <option value="other">Other</option>
          </select>
        </div>
        <!-- Other Department Field -->
        <div id="otherDepartmentField" style="display: none; margin-top: 10px;" class="input-group">
          <label><i class="fas fa-building"></i> Specify Other Department *</label>
          <input type="text" id="other_department_details" name="other_department_details" placeholder="Enter department name">
        </div>
        <div class="input-group">
          <label><i class="fas fa-id-card"></i> Applying as (Profile) *</label>
          <select id="applying_for_profile" name="applying_for_profile" required disabled onchange="toggleOtherProfile()">
            <option value="" selected>First select a department</option>
          </select>
        </div>
        <!-- Other Profile Field -->
        <div id="otherProfileField" style="display: none; margin-top: 10px;" class="input-group">
          <label><i class="fas fa-user-tag"></i> Specify Other Profile *</label>
          <input type="text" id="other_profile_details" name="other_profile_details" placeholder="Enter profile/role name">
        </div>
      </div>
    </div>

    <!-- Skills & Competencies -->
    <div class="form-section">
      <div class="section-title">
        <i class="fas fa-brain"></i> Skills & Competencies
      </div>
      <div class="skills-area">
        <label style="font-weight:500;"><i class="fas fa-hand-pointer"></i> Select Skills </label>
        
        <!-- Loading indicator for skills -->
        <div id="skillsLoading" style="display: none; text-align: center; padding: 20px; background: #eef2f7; border-radius: 10px; margin-bottom: 20px;">
            <i class="fas fa-spinner fa-spin fa-2x" style="color: #2b7a62;"></i>
            <p style="margin-top: 10px; color: #2b7a62;">Loading relevant skills based on your selection...</p>
        </div>
        
        <!-- Skills Container (initially hidden) -->
        <div id="skillsContainer" style="display: none;">
            <!-- Teaching Skills -->
            <div id="teachingSkillsSection" style="margin-bottom: 20px; display: none;">
                <div style="font-size: 14px; color: #1e2f3f; margin-bottom: 12px; font-weight: 500;">
                    <i class="fas fa-graduation-cap"></i> Teaching & Academic Skills:
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;" id="teachingSkills">
                    <!-- Will be populated via AJAX -->
                </div>
            </div>
            
            <!-- Technical Skills -->
            <div id="technicalSkillsSection" style="margin-bottom: 20px; display: none;">
                <div style="font-size: 14px; color: #1e2f3f; margin-bottom: 12px; font-weight: 500;">
                    <i class="fas fa-laptop"></i> Technical Skills:
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;" id="technicalSkills">
                    <!-- Will be populated via AJAX -->
                </div>
            </div>
            
            <!-- Soft Skills -->
            <div id="softSkillsSection" style="margin-bottom: 20px; display: none;">
                <div style="font-size: 14px; color: #1e2f3f; margin-bottom: 12px; font-weight: 500;">
                    <i class="fas fa-heart"></i> Soft Skills:
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;" id="softSkills">
                    <!-- Will be populated via AJAX -->
                </div>
            </div>
            
            <!-- Languages -->
            <div id="languageSkillsSection" style="margin-bottom: 20px; display: none;">
                <div style="font-size: 14px; color: #1e2f3f; margin-bottom: 12px; font-weight: 500;">
                    <i class="fas fa-language"></i> Languages:
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;" id="languageSkills">
                    <!-- Will be populated via AJAX -->
                </div>
            </div>
            
            <!-- No skills message -->
            <div id="noSkillsMessage" style="display: none; text-align: center; padding: 30px; background: #eef2f7; border-radius: 10px;">
                <i class="fas fa-info-circle fa-2x" style="color: #2b7a62;"></i>
                <p style="margin-top: 10px; color: #1e2f3f;">No predefined skills found for this selection. You can add custom skills below.</p>
            </div>
        </div>
        
        <!-- Selected Skills Display -->
        <div style="background: #eef2f7; padding: 15px; border-radius: 10px; margin: 20px 0;">
            <label style="color: #2b7a62; margin-bottom: 10px; font-size: 14px;">
                <i class="fas fa-check-circle"></i> Selected Skills:
            </label>
            <div id="selectedSkillsContainer" style="display: flex; flex-wrap: wrap; gap: 8px; min-height: 40px;">
                <!-- Selected skills will appear here -->
                <span style="color: #6f8eaa; font-style: italic;">No skills selected yet</span>
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
        <small style="color: #6f8eaa; margin-top: 10px; display: block;">
            <i class="fas fa-info-circle"></i> Click on skills above to select them. Add your own skills using the custom field.
        </small>
        
        <!-- Hidden input to store selected skills for form submission -->
        <input type="hidden" id="selectedSkills" name="skills">
      </div>
    </div>

    <!-- Document Upload -->
    <div class="form-section">
      <div class="section-title">
        <i class="fas fa-paperclip"></i> Document Upload
      </div>
      <div class="file-upload">
        <i class="fas fa-cloud-upload-alt"></i>
        <label style="font-weight:500; display:block;">Upload Resume/CV *</label>
        <input type="file" id="resumeFile" accept=".pdf,.doc,.docx,.jpg,.png" style="margin-top: 8px;">
        <div class="inline-note"><i class="fas fa-file-pdf"></i> PDF, DOC, DOCX, JPG, PNG (Max: 5MB)</div>
      </div>
    </div>



    <button type="submit" class="submit-btn"><i class="fas fa-paper-plane"></i> Submit Application</button>
    <div id="formStatus" class="alert-message" style="display: none; margin-top: 1rem;"></div>
  </form>
  <footer>
    <i class="fas fa-envelope"></i> After submission, you'll receive a confirmation email. <i class="fas fa-lock"></i> Your data is secure.
  </footer>
</div>


<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
    // Array to store selected skills
    let selectedSkills = [];

    // Load profiles based on department selection
    document.getElementById('applying_for').addEventListener('change', function() {
        loadProfilesByDepartment();
        loadSkillsBySelection();
        toggleOtherDepartment();
    });

    // Load skills based on department and profile selection 
    document.getElementById('applying_for_profile').addEventListener('change', function() {
        loadSkillsBySelection();
        toggleOtherProfile(); 
    });

    // Load profiles based on department selection
    function loadProfilesByDepartment() {
        const department = document.getElementById('applying_for').value;
        
        if (!department || department === 'other') {
            document.getElementById('applying_for_profile').innerHTML = '<option value="">First select a department</option>';
            document.getElementById('applying_for_profile').disabled = true;
            return;
        }
        
        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Make AJAX call to get profiles for selected department
        fetch(`/get-profiles-by-department/${encodeURIComponent(department)}`, {
            method: 'GET',
            headers: { 
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json' 
            }
        })
        .then(response => response.json()) 
        .then(data => {
            const profileSelect = document.getElementById('applying_for_profile');
            profileSelect.innerHTML = '<option value="" disabled selected>Select profile</option>';
            
            // Use data.profiles instead of data directly
            const profiles = data.profiles || [];
            
            if (profiles.length > 0) {
                profiles.forEach(profile => {
                    const option = document.createElement('option');
                    option.value = profile;
                    option.textContent = profile;
                    profileSelect.appendChild(option);
                });
                profileSelect.innerHTML += '<option value="other">Other</option>';
            } else {
                profileSelect.innerHTML += '<option value="" disabled>No profiles available</option>';
            }
            
            profileSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error loading profiles:', error);
            showToast('Failed to load profiles. Please try again.', 'error');
        });
    }

    // Load skills based on department and profile selection
    function loadSkillsBySelection() {
        const department = document.getElementById('applying_for').value;
        const profile = document.getElementById('applying_for_profile').value;
        
        console.log('Loading skills for:', { department, profile }); // Debug log
        
        // Only load skills if both department and profile are selected and not "other"
        if (!department || department === 'other' || !profile || profile === 'other') {
            // Hide skills container if either is "other" or not selected
            document.getElementById('skillsContainer').style.display = 'none';
            document.getElementById('skillsLoading').style.display = 'none';
            return;
        }
        
        // Show loading indicator
        document.getElementById('skillsLoading').style.display = 'block';
        document.getElementById('skillsContainer').style.display = 'none';
        
        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Make AJAX call to get skills
        fetch('/get-skills-by-selection', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                department: department,
                profile: profile
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('skillsLoading').style.display = 'none';
            
            if (data.success) {
                clearSkillSections();
                
                const skills = data.skills;
                let hasSkills = false;
                
                // Populate sections
                ['teaching', 'technical', 'soft', 'language'].forEach(category => {
                    const sectionId = category + 'Skills';
                    const sectionDiv = document.getElementById(sectionId + 'Section');
                    
                    if (skills[category] && skills[category].length > 0) {
                        populateSkillSection(sectionId, skills[category]);
                        sectionDiv.style.display = 'block';
                        hasSkills = true;
                    } else {
                        sectionDiv.style.display = 'none';
                    }
                });
                
                document.getElementById('skillsContainer').style.display = 'block';
                document.getElementById('noSkillsMessage').style.display = hasSkills ? 'none' : 'block';
            }
        })
        .catch(error => {
            console.error('Error loading skills:', error);
            document.getElementById('skillsLoading').style.display = 'none';
            showToast('Failed to load skills. Please try again.', 'error');
        });
    }

    function toggleSkill(element) {
        const skill = element.getAttribute('data-skill');
        
        if (element.classList.contains('selected')) {
            element.classList.remove('selected');
            selectedSkills = selectedSkills.filter(s => s !== skill);
        } else {
            element.classList.add('selected');
            selectedSkills.push(skill);
        }
        
        updateSelectedSkillsDisplay();
    }

    function populateSkillSection(sectionId, skills) {
        const container = document.getElementById(sectionId);
        if (!container) return;
        
        container.innerHTML = '';
        
        skills.forEach(skill => {
            const isSelected = selectedSkills.includes(skill);
            const selectedClass = isSelected ? 'selected' : '';
            
            container.innerHTML += `<span class="skill-tag ${selectedClass}" onclick="toggleSkill(this)" data-skill="${skill}">${skill}</span>`;
        });
    }

    function clearSkillSections() {
        ['teachingSkills', 'technicalSkills', 'softSkills', 'languageSkills'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.innerHTML = '';
        });
        
        selectedSkills = [];
        updateSelectedSkillsDisplay();
    }

    // Add custom skill
    function addCustomSkill() {
        const input = document.getElementById('customSkillInput');
        const skill = input.value.trim();
        
        if (skill === '') {
            showToast('Please enter a skill', 'error');
            return;
        }
        
        if (!selectedSkills.includes(skill)) {
            selectedSkills.push(skill);
            updateSelectedSkillsDisplay();
            input.value = ''; 
            
            
            showToast('Skill "' + skill + '" added successfully!', 'success'); 
        } else {
            showToast('This skill is already selected', 'error');
        } 
    }

    // Update selected skills display
    function updateSelectedSkillsDisplay() {
        const container = document.getElementById('selectedSkillsContainer');
        const hiddenInput = document.getElementById('selectedSkills');
        
        if (selectedSkills.length === 0) {
            container.innerHTML = '<span style="color: #6f8eaa; font-style: italic;">No skills selected yet</span>';
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

    // Toggle other department field
    function toggleOtherDepartment() {
        const department = document.getElementById('applying_for').value;
        const otherField = document.getElementById('otherDepartmentField');
        
        if (department === 'other') {
            otherField.style.display = 'block';
            document.getElementById('other_department_details').setAttribute('required', 'required');
        } else {
            otherField.style.display = 'none';
            document.getElementById('other_department_details').removeAttribute('required');
            document.getElementById('other_department_details').value = ''; // Clear the field
        }
    }

    // Toggle other profile field
    function toggleOtherProfile() {
        const profile = document.getElementById('applying_for_profile').value;
        const otherField = document.getElementById('otherProfileField');
        
        if (profile === 'other') {
            otherField.style.display = 'block';
            document.getElementById('other_profile_details').setAttribute('required', 'required');
        } else {
            otherField.style.display = 'none';
            document.getElementById('other_profile_details').removeAttribute('required');
            document.getElementById('other_profile_details').value = ''; // Clear the field
        }
    }

    // Toast notification function
    function showToast(message, type = 'info') {
        Toastify({
            text: message,
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: type === 'error' ? "#ef4444" : type === 'success' ? "#10b981" : "#3b82f6",
        }).showToast();
    }

    // Form submission
    document.getElementById('jobApplicationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData();
        formData.append('full_name', document.getElementById('fullName').value);
        formData.append('email', document.getElementById('email').value);
        formData.append('phone', document.getElementById('phone').value);
        formData.append('dob', document.getElementById('dob').value);
        formData.append('marital_status', document.getElementById('maritalStatus').value);
        formData.append('profession', document.getElementById('profession').value);
        formData.append('organization', document.getElementById('organization').value);
        formData.append('qualification', document.getElementById('qualification').value);
        formData.append('experience_in_year', document.getElementById('expYears').value || 0);
        formData.append('experience_in_month', document.getElementById('expMonths').value || 0);
        formData.append('applying_for', document.getElementById('applying_for').value);
        formData.append('applying_for_profile', document.getElementById('applying_for_profile').value);
        formData.append('skills', document.getElementById('selectedSkills').value);
        
        // Handle other fields
        if (document.getElementById('applying_for').value === 'other') {
            formData.append('other_department_details', document.getElementById('other_department_details').value);
        }
        if (document.getElementById('applying_for_profile').value === 'other') {
            formData.append('other_profile_details', document.getElementById('other_profile_details').value);
        }
        
        // Handle file upload
        const resumeFile = document.getElementById('resumeFile').files[0];
        if (resumeFile) {
            formData.append('resume', resumeFile);
        }
        
        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Submit form
        fetch('/save-interview', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
      
        .catch(error => {
            console.error('Error:', error);
            showToast('Error submitting application', 'error');
        });
    });
</script>

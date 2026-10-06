<meta charset="UTF-8">
<title>Job Application Form</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .form-container {
        background: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .section-title {
        font-weight: 600;
        margin-top: 25px;
        margin-bottom: 15px;
        border-left: 4px solid #0d6efd;
        padding-left: 10px;
    }
</style>

<div class="container">
    <div class="form-container">
        <h3 class="text-center mb-4">Job Application Form</h3>

        <form method="POST" enctype="multipart/form-data">

            <!-- ================= Personal Information ================= -->
            <div class="section-title">Personal Information</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name *</label>
                    <input type="text" class="form-control" name="full_name" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mobile Number *</label>
                    <input type="tel" class="form-control" name="mobile" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Current Location</label>
                    <input type="text" class="form-control" name="location">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" name="dob">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Gender</label>
                    <select class="form-select" name="gender">
                        <option value="">Select</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>
            </div>

            <!-- ================= Position Details ================= -->
            <div class="section-title">Position Details</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Applying For *</label>
                    <select class="form-select" name="position" required>
                        <option value="">Select Position</option>

                        <optgroup label="Academic Roles">
                            <option>Teacher</option>
                            <option>Assistant Professor</option>
                            <option>Lecturer</option>
                            <option>Subject Coordinator</option>
                            <option>Academic Coordinator</option>
                            <option>Principal</option>
                            <option>Vice Principal</option>
                        </optgroup>

                        <optgroup label="Administrative Roles">
                            <option>Admission Counselor</option>
                            <option>Office Administrator</option>
                            <option>HR Executive</option>
                            <option>Accountant</option>
                            <option>Examination Coordinator</option>
                            <option>Front Desk Executive</option>
                        </optgroup>

                        <optgroup label="Support & Operational Roles">
                            <option>IT Support Executive</option>
                            <option>Lab Assistant</option>
                            <option>Librarian</option>
                            <option>Transport Manager</option>
                            <option>Student Relationship Officer</option>
                            <option>Marketing Executive</option>
                        </optgroup>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Total Experience (Years) *</label>
                    <input type="number" class="form-control" name="experience" min="0" step="0.1" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Current Organization</label>
                    <input type="text" class="form-control" name="current_organization">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Current Salary</label>
                    <input type="text" class="form-control" name="current_salary">
                </div>
            </div>

            <!-- ================= Academic / Professional Details ================= -->
            <div class="section-title">Education & Qualification</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Highest Qualification *</label>
                    <input type="text" class="form-control" name="qualification" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">University / Institute *</label>
                    <input type="text" class="form-control" name="institute" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Year of Passing</label>
                    <input type="number" class="form-control" name="passing_year">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Specialization / Subject</label>
                    <input type="text" class="form-control" name="specialization">
                </div>
            </div>

            <!-- ================= Skills ================= -->
            <div class="section-title">Skills & Expertise</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Key Skills</label>
                    <input type="text" class="form-control" name="skills" placeholder="Teaching, Classroom Management, MS Office, Accounting, etc.">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Preferred Work Type</label>
                    <select class="form-select" name="work_type">
                        <option value="">Select</option>
                        <option>Full Time</option>
                        <option>Part Time</option>
                        <option>Contract</option>
                    </select>
                </div>
            </div>

            <!-- ================= Documents ================= -->
            <div class="section-title">Upload Documents</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Upload Resume *</label>
                    <input type="file" class="form-control" name="resume" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Upload Certificates (Optional)</label>
                    <input type="file" class="form-control" name="certificates">
                </div>
            </div>

            <!-- ================= Additional Info ================= -->
            <div class="section-title">Additional Information</div>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Why should we hire you?</label>
                    <textarea class="form-control" name="about" rows="4"></textarea>
                </div>
            </div>

            <!-- ================= Submit ================= -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-5">Submit Application</button>
                <button type="reset" class="btn btn-secondary px-4">Reset</button>
            </div>

        </form>
    </div>
</div>
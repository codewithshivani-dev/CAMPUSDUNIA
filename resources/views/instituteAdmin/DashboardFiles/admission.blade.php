<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Student Enrollment Form | White Background</title>
        <!-- Font Awesome 6 (Free CDN) -->
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        />
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                background: #ffffff;
                font-family:
                    "Segoe UI",
                    "Poppins",
                    system-ui,
                    -apple-system,
                    "Roboto",
                    sans-serif;
                padding: 40px 24px;
                min-height: 100vh;
            }

            /* Form Container - clean white card with subtle shadow */
            .form-container {
                max-width: 1100px;
                margin: 0 auto;
                background: #ffffff;
                border-radius: 28px;
                box-shadow:
                    0 20px 40px rgba(0, 0, 0, 0.05),
                    0 5px 12px rgba(0, 0, 0, 0.03);
                overflow: hidden;
                border: 1px solid #f0f2f5;
            }

            /* Header with light grey background & icons */
            .form-header {
                background: #fafbfc;
                padding: 28px 32px;
                border-bottom: 1px solid #eef2f6;
            }

            .form-header h1 {
                font-size: 28px;
                font-weight: 700;
                color: #1a2634;
                margin-bottom: 8px;
                letter-spacing: -0.2px;
            }

            .form-header h1 i {
                color: #2c6e9e;
                margin-right: 12px;
                font-size: 28px;
            }

            .form-header p {
                font-size: 14px;
                color: #5e6f8d;
                margin-left: 40px;
            }

            /* Form Body */
            .form-body {
                padding: 32px 36px;
            }

            /* Section Styling */
            .section {
                margin-bottom: 36px;
                background: #ffffff;
                border-radius: 20px;
            }

            .section-title {
                font-size: 20px;
                font-weight: 700;
                color: #1e2f41;
                margin-bottom: 24px;
                padding-bottom: 10px;
                border-bottom: 2px solid #eef2f8;
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .section-title i {
                font-size: 22px;
                color: #2c6e9e;
                background: #f0f4fa;
                width: 36px;
                height: 36px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 12px;
            }

            /* Grid layouts */
            .form-row {
                display: flex;
                flex-wrap: wrap;
                gap: 24px;
                margin-bottom: 24px;
            }

            .form-group {
                flex: 1;
                min-width: 200px;
            }

            .form-group-full {
                width: 100%;
            }

            /* Labels with icons */
            label {
                display: block;
                font-weight: 600;
                font-size: 13px;
                color: #2c3e50;
                margin-bottom: 8px;
                letter-spacing: 0.3px;
            }

            label i {
                margin-right: 6px;
                color: #5b7c9c;
                font-size: 12px;
                width: 18px;
            }

            .required-label i {
                color: #e56c4b;
            }

            .required-label::after {
                content: " *";
                color: #e74c3c;
                font-weight: bold;
            }

            /* Input fields */
            input,
            select,
            textarea {
                width: 100%;
                padding: 12px 16px;
                border: 1.5px solid #e2e8f0;
                border-radius: 14px;
                font-size: 14px;
                font-family: inherit;
                background: #ffffff;
                transition: all 0.2s ease;
                color: #1a2c3e;
            }

            input:focus,
            select:focus,
            textarea:focus {
                outline: none;
                border-color: #2c6e9e;
                box-shadow: 0 0 0 3px rgba(44, 110, 158, 0.08);
            }

            /* Radio Group Styling with icons */
            .radio-group {
                display: flex;
                gap: 28px;
                align-items: center;
                flex-wrap: wrap;
                background: #fefefe;
                padding: 6px 0;
            }

            .radio-option {
                display: flex;
                align-items: center;
                gap: 8px;
                cursor: pointer;
            }

            .radio-option input {
                width: 18px;
                height: 18px;
                accent-color: #2c6e9e;
                margin: 0;
                cursor: pointer;
            }

            .radio-option label {
                margin: 0;
                font-weight: 500;
                font-size: 14px;
                cursor: pointer;
                text-transform: none;
                letter-spacing: normal;
            }

            .radio-option i {
                color: #5f7f9e;
                font-size: 14px;
            }

            /* Hint text */
            .hint {
                font-size: 11px;
                color: #8a99b0;
                margin-top: 6px;
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .hint i {
                font-size: 10px;
            }

            /* Parent grid (2 columns) */
            .parent-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 24px;
            }

            /* Button Group */
            .button-group {
                display: flex;
                gap: 18px;
                justify-content: flex-end;
                margin-top: 28px;
                padding-top: 24px;
                border-top: 1px solid #eef2f8;
            }

            .btn {
                padding: 12px 32px;
                border: none;
                border-radius: 40px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.25s ease;
                font-family: inherit;
                display: inline-flex;
                align-items: center;
                gap: 10px;
            }

            .btn-primary {
                background: #2c6e9e;
                color: white;
                box-shadow: 0 2px 6px rgba(44, 110, 158, 0.2);
            }

            .btn-primary i {
                color: white;
                font-size: 14px;
            }

            .btn-primary:hover {
                background: #1f547c;
                transform: translateY(-1px);
                box-shadow: 0 8px 18px rgba(44, 110, 158, 0.2);
            }

            .btn-secondary {
                background: #f8fafd;
                color: #2c3e66;
                border: 1.5px solid #e2e8f0;
            }

            .btn-secondary i {
                color: #7c8ea0;
            }

            .btn-secondary:hover {
                background: #f1f5f9;
                border-color: #cbd5e1;
            }

            /* Note box */
            .info-note {
                background: #fafcff;
                border-radius: 16px;
                padding: 14px 18px;
                margin-top: 24px;
                display: flex;
                align-items: center;
                gap: 12px;
                border: 1px solid #eef2fa;
                font-size: 12px;
                color: #4a5b7a;
            }

            .info-note i {
                font-size: 18px;
                color: #2c6e9e;
            }

            hr {
                margin: 20px 0 12px;
                border: none;
                border-top: 1px solid #eef2f8;
            }

            /* Responsive */
            @media (max-width: 780px) {
                .form-body {
                    padding: 24px;
                }
                .parent-grid {
                    grid-template-columns: 1fr;
                    gap: 18px;
                }
                .form-row {
                    flex-direction: column;
                    gap: 18px;
                }
                .button-group {
                    flex-direction: column;
                }
                .btn {
                    justify-content: center;
                }
                .section-title {
                    font-size: 18px;
                }
            }
        </style>
    </head>
    <body>
        <div class="form-container">
            <div class="form-header">
                <h1>
                    <i class="fas fa-graduation-cap"></i>
                    Student Enrollment Form
                </h1>
                <p>
                    <i class="fas fa-edit"></i> Please fill in all required
                    details to complete enrollment
                </p>
            </div>

            <div class="form-body">
                <form action="#" method="POST">
                    <!-- ========== PERSONAL INFORMATION ========== -->
                    <div class="section">
                        <div class="section-title">
                            <i class="fas fa-user-circle"></i>
                            <span>Personal Information</span>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="required-label"
                                    ><i class="fas fa-user"></i> Student's Full
                                    Name</label
                                >
                                <input
                                    type="text"
                                    name="fullname"
                                    placeholder="e.g., Priya Sharma"
                                    required
                                />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="required-label"
                                    ><i class="fas fa-calendar-alt"></i> Date of
                                    Birth</label
                                >
                                <input
                                    type="text"
                                    name="dob"
                                    placeholder="dd-mm-yyyy"
                                    pattern="\d{2}-\d{2}-\d{4}"
                                    required
                                />
                                <div class="hint">
                                    <i class="fas fa-info-circle"></i> Format:
                                    dd-mm-yyyy (e.g., 15-08-2010)
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="required-label"
                                    ><i class="fas fa-venus-mars"></i>
                                    Gender</label
                                >
                                <div class="radio-group">
                                    <div class="radio-option">
                                        <input
                                            type="radio"
                                            name="gender"
                                            value="Male"
                                            id="male"
                                            checked
                                        />
                                        <i class="fas fa-mars"></i>
                                        <label for="male">Male</label>
                                    </div>
                                    <div class="radio-option">
                                        <input
                                            type="radio"
                                            name="gender"
                                            value="Female"
                                            id="female"
                                        />
                                        <i class="fas fa-venus"></i>
                                        <label for="female">Female</label>
                                    </div>
                                    <div class="radio-option">
                                        <input
                                            type="radio"
                                            name="gender"
                                            value="Other"
                                            id="other_gender"
                                        />
                                        <i class="fas fa-genderless"></i>
                                        <label for="other_gender">Other</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="required-label"
                                    ><i class="fas fa-flag"></i>
                                    Nationality</label
                                >
                                <input
                                    type="text"
                                    name="nationality"
                                    placeholder="e.g., Indian"
                                    value="Indian"
                                    required
                                />
                            </div>

                            <div class="form-group">
                                <label class="required-label"
                                    ><i class="fas fa-chalkboard-user"></i>
                                    Applying for Grade/Class</label
                                >
                                <select name="grade" required>
                                    <option value="" disabled selected>
                                        — Select Grade —
                                    </option>
                                    <option>Nursery</option>
                                    <option>LKG</option>
                                    <option>UKG</option>
                                    <option>1st Grade</option>
                                    <option>2nd Grade</option>
                                    <option>3rd Grade</option>
                                    <option>4th Grade</option>
                                    <option>5th Grade</option>
                                    <option>6th Grade</option>
                                    <option>7th Grade</option>
                                    <option>8th Grade</option>
                                    <option>9th Grade</option>
                                    <option>10th Grade</option>
                                    <option>11th Grade</option>
                                    <option>12th Grade</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="required-label"
                                    ><i class="fas fa-phone-alt"></i> Phone /
                                    Mobile</label
                                >
                                <input
                                    type="tel"
                                    name="phone"
                                    placeholder="+91 9876543210"
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    <!-- ========== FULL ADDRESS SECTION ========== -->
                    <div class="section">
                        <div class="section-title">
                            <i class="fas fa-location-dot"></i>
                            <span>Full Address</span>
                        </div>

                        <div class="form-row">
                            <div class="form-group-full">
                                <label class="required-label"
                                    ><i class="fas fa-house-chimney"></i> House
                                    no, Street, Area, City, State, PIN
                                    Code</label
                                >
                                <textarea
                                    name="address"
                                    rows="3"
                                    placeholder="House no, Street, Area, Landmark, City, State, PIN Code"
                                    required
                                ></textarea>
                                <div class="hint">
                                    <i class="fas fa-map-pin"></i> Example: 42,
                                    Green Valley, MG Road, Bangalore, Karnataka,
                                    560001
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="required-label"
                                    ><i class="fas fa-city"></i> City</label
                                >
                                <input
                                    type="text"
                                    name="city"
                                    placeholder="City name"
                                    required
                                />
                            </div>

                            <div class="form-group">
                                <label class="required-label"
                                    ><i class="fas fa-map"></i> State</label
                                >
                                <input
                                    type="text"
                                    name="state"
                                    placeholder="State name"
                                    required
                                />
                            </div>

                            <div class="form-group">
                                <label class="required-label"
                                    ><i class="fas fa-mail-bulk"></i> PIN
                                    Code</label
                                >
                                <input
                                    type="text"
                                    name="pincode"
                                    placeholder="6-digit PIN"
                                    pattern="[0-9]{6}"
                                    maxlength="6"
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    <!-- ========== PARENT / GUARDIAN DETAILS ========== -->
                    <div class="section">
                        <div class="section-title">
                            <i class="fas fa-people-arrows"></i>
                            <span>Parent / Guardian Details</span>
                        </div>

                        <div class="parent-grid">
                            <div class="form-group">
                                <label class="required-label"
                                    ><i class="fas fa-user-check"></i>
                                    Parent/Guardian Full Name</label
                                >
                                <input
                                    type="text"
                                    name="parent_name"
                                    placeholder="Full name"
                                    required
                                />
                            </div>

                            <div class="form-group">
                                <label class="required-label"
                                    ><i class="fas fa-handshake"></i> Relation
                                    to Student</label
                                >
                                <input
                                    type="text"
                                    name="relation"
                                    placeholder="e.g., Father / Mother / Guardian"
                                    required
                                />
                            </div>

                            <div class="form-group">
                                <label class="required-label"
                                    ><i class="fas fa-briefcase"></i>
                                    Occupation</label
                                >
                                <input
                                    type="text"
                                    name="occupation"
                                    placeholder="e.g., Engineer, Business, Homemaker"
                                    required
                                />
                            </div>

                            <div class="form-group">
                                <label class="required-label"
                                    ><i class="fas fa-mobile-alt"></i> Phone
                                    Number</label
                                >
                                <input
                                    type="tel"
                                    name="parent_phone"
                                    placeholder="Parent contact number"
                                    required
                                />
                            </div>

                            <div class="form-group">
                                <label class="required-label"
                                    ><i class="fas fa-envelope"></i> Email
                                    Address</label
                                >
                                <input
                                    type="email"
                                    name="parent_email"
                                    placeholder="parent@example.com"
                                    required
                                />
                            </div>
                        </div>

                        <hr />
                    </div>

                    <!-- Buttons with icons -->
                    <div class="button-group">
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-eraser"></i> Clear Form
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Submit Enrollment
                        </button>
                    </div>

                    <div class="info-note">
                        <i class="fas fa-shield-alt"></i>
                        <span
                            ><strong>Note:</strong> Fields marked with
                            <span style="color: #e74c3c">*</span> are mandatory.
                            All information is securely stored and used only for
                            admission purposes.</span
                        >
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>

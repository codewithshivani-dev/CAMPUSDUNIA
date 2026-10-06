@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Branch Campus</title>

<style>
    @import url("https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&family=Comfortaa:wght@300..700&family=Funnel+Sans:ital,wght@0,300..800;1,300..800&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap");

    :root {
        --primary: #0d6efd;
        --success: #198754;
        --danger: #dc3545;
        --dark: #212529;
        --light: #f8f9fa;
        --gray: #6c757d;
    }

    .timeline {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 25px;
    }

    .timeline-line {
        position: absolute;
        top: 16px;
        left: 16px;
        height: 4px;
        background: #198754;
        z-index: 0;
        transition: width 0.4s ease;
        width: 0%;
    }

    .timeline::before {
        content: "";
        position: absolute;
        top: 16px;
        left: 127px;
        width: calc(100% - 24%);
        height: 4px;
        background: #dee2e6;
        z-index: 0;
    }

    .timeline-step {
        position: relative;
        z-index: 1;
        text-align: center;
        flex: 1;
    }

    .timeline-bullet {
        width: 32px;
        height: 32px;
        background: #dee2e6;
        color: white;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        font-weight: bold;
    }

    .timeline-step.active .timeline-bullet {
        background: #0d6efd;
    }

    .timeline-step.completed .timeline-bullet {
        background: #198754;
    }

    .form-navigation {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .form-navigation .btn {
        min-width: 100px;
    }

    /*MEDIA QUERY START*/
    @media screen and (max-width: 768px) {
        .timeline-step {
            font-size: 10px;
        }
    }
    @media screen and (max-width: 768px) {
        .register-image {
            display: none;
        }
    }

    /*MEDIA QUERY END*/

    /* Stakeholder form styles */
    .form-row {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-width: 150px;
    }

    .btn-group-custom {
        display: flex;
        gap: 5px;
        align-items: flex-end;
    }

    .action-btn {
        font-size: 20px;
        font-weight: bold;
        width: 36px;
        height: 36px;
        padding: 0;
    }

    /* Checkbox section styles */
    .copy-stakeholder-section {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
    }

    .copy-stakeholder-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .stakeholder-checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }

    .stakeholder-checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(0, 123, 255, 0.1);
        padding: 8px 12px;
        border-radius: 4px;
        border: 1px solid rgba(0, 123, 255, 0.3);
        min-width: 200px;
    }

    .stakeholder-checkbox-item input[type="checkbox"] {
        margin: 0;
        transform: scale(1.2);
        /* Add these styles for better visibility */
        accent-color: #0d6efd;
        /* Blue color for the checkbox */
        cursor: pointer;
    }

    .stakeholder-checkbox-item input[type="checkbox"]:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .stakeholder-checkbox-item input[type="checkbox"]:checked::after {
        content: "✓";
        color: white;
        font-size: 14px;
        font-weight: bold;
        position: absolute;
        top: -2px;
        left: 2px;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stakeholder-checkbox-item input[type="checkbox"]:hover {
        border-color: #0056b3;
    }

    .stakeholder-checkbox-item input[type="checkbox"]:focus {
        outline: 2px solid #0d6efd;
        outline-offset: 2px;
    }

    .stakeholder-checkbox-item label {
        margin: 0;
        cursor: pointer;
        font-size: 14px;
    }

    /* Creative Upload Styles */
    .upload-card {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 25px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .upload-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(13, 110, 253, 0.15);
    }

    .upload-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .upload-title {
        font-size: 18px;
        font-weight: 600;
    }

    .upload-type {
        font-size: 14px;
        font-weight: bold;
        color: var(--primary);
        background: rgba(13, 110, 253, 0.15);
        padding: 5px 12px;
        border-radius: 20px;
    }

    .upload-area {
        border: 2px dashed rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }

    .upload-area:hover,
    .upload-area.dragover {
        border-color: var(--primary);
        background: rgba(13, 110, 253, 0.05);
    }

    .upload-area i {
        font-size: 48px;
        color: var(--primary);
        margin-bottom: 15px;
        display: block;
    }

    .upload-text {
        margin-bottom: 15px;
    }

    .upload-text h4 {
        font-size: 18px;
        margin-bottom: 8px;
    }

    .upload-text p {
        font-size: 14px;
        margin: 0;
    }

    .upload-btn {
        background: var(--primary);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .upload-btn:hover {
        background: #0b5ed7;
        transform: translateY(-2px);
    }

    .file-input {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }

    .preview-area {
        display: none;
        margin-top: 20px;
    }

    .preview-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .preview-title {
        font-size: 16px;
        font-weight: 600;
    }

    .change-file {
        color: var(--primary);
        background: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }

    .preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }

    .preview-item {
        position: relative;
        width: 120px;
        height: 120px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
    }
    .preview-area.active {
        display: flex !important;
        flex-direction: column;
        align-items: center;
    }

    .preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .preview-item .file-icon {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(13, 110, 253, 0.1);
    }

    .preview-item .file-icon i {
        font-size: 36px;
        color: var(--primary);
    }

    .preview-info {
        padding: 10px;
        background: rgba(0, 0, 0, 0.7);
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
    }

    .preview-info .file-name {
        font-size: 12px;
        color: #fff;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 5px;
    }

    .preview-info .file-size {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.7);
    }

    .remove-file {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--danger);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .preview-item:hover .remove-file {
        opacity: 1;
    }

    .upload-status {
        display: flex;
        align-items: center;
        margin-top: 15px;
        font-size: 14px;
    }

    .status-icon {
        margin-right: 8px;
        font-size: 16px;
    }

    .status-pending {
        color: #ffc107;
    }

    .status-success {
        color: var(--success);
    }

    .status-error {
        color: var(--danger);
    }

    .progress-bar {
        height: 6px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
        overflow: hidden;
        margin-top: 10px;
        display: none;
    }

    .progress-fill {
        height: 100%;
        background: var(--primary);
        width: 0%;
        transition: width 0.4s ease;
    }

    .document-section {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 25px;
        border-left: 4px solid #0d6efd;
    }

    .document-section h5 {
        color: #0dcaf0;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .document-person-type {
        font-size: 18px;
        font-weight: bold;
        color: #fff;
        background: rgba(13, 110, 253, 0.2);
        padding: 5px 15px;
        border-radius: 20px;
    }

    .merged-section {
        border-left: 4px solid #6f42c1 !important;
    }

    .merged-section .document-person-type {
        background: #6f42c1 !important;
    }

    .alert-info {
        background-color: rgba(13, 202, 240, 0.1);
        border: 1px solid rgba(13, 202, 240, 0.3);
        color: #0dcaf0;
        border-radius: 6px;
        padding: 12px 15px;
        margin-bottom: 20px;
    }

    .alert-info i {
        margin-right: 8px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .upload-card {
            padding: 20px;
        }

        .upload-area {
            padding: 20px;
        }

        .preview-item {
            width: 100px;
            height: 100px;
        }
    }

    @media (max-width: 576px) {
        .upload-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .upload-type {
            margin-top: 10px;
        }

        .preview-item {
            width: 85px;
            height: 85px;
        }
    }

    .form-disabled {
        pointer-events: none;
        opacity: 0.6;
    }
    .timeline-bullet {
        cursor: pointer;
    }

    form{
        padding: 14px;
    }

    .page-header {
        background: var(--primary-gradient);
        padding: 20px 25px;
        border-radius: 16px;
        margin-bottom: 25px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
    }

    .page-header h2 {
        color: white;
        margin: 0;
        font-weight: 700;
        display: flex;
        align-items: center;
    }
</style>

<div class="page-header">
    <h2>
        Branch Details
    </h2>
</div>

<div class="card shadow-sm border-0" style="border-radius: 12px">
    <!--NEW TIMELINE FORM START-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <!-- Timeline -->
                <div class="timeline position-relative mt-3">
                    <div class="timeline-line" id="timelineProgress"></div>
                    <div class="timeline-step active" id="step1">
                        <div class="timeline-bullet">1</div>
                        <div>Institute Details</div>
                    </div>
                    <div class="timeline-step" id="step2">
                        <div class="timeline-bullet">2</div>
                        <div>Stakeholder/Authorized Details</div>
                    </div>
                    <div class="timeline-step" id="step3">
                        <div class="timeline-bullet">3</div>
                        <div>Documents</div>
                    </div>
                    <div class="timeline-step" id="step4">
                        <div class="timeline-bullet">4</div>
                        <div>Beneficiary Details</div>
                    </div>
                </div>

                <!-- Form 1 -->
                <form
                    id="form1"
                    action="{{ route('branch.store.details') }}"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    <h4>Institution Details</h4>
                    <div class="row mb-3 mt-2">
                        <div class="col-md-6">
                            <label class="mt-3">Institute Name <span style="color: red;">*</span></label>
                            <input
                                type="text"
                                class="form-control mt-2"
                                id="instituteName"
                                name="name"
                                required
                            />
                        </div>
                        <div class="col-md-6">
                            <label class="mt-3">Institute Type <span style="color: red;">*</span></label>
                            <select
                                class="form-select mt-2"
                                id="instituteType"
                                name="type"
                            >
                                <option selected disabled value="">
                                    Choose...
                                </option>
                                <option value="College">College</option>
                                <option value="University">
                                    University
                                </option>
                                <option value="School">School</option>
                                <option value="Coaching">Coaching</option>
                                <option value="Tutor">Tutor</option>
                            </select>
                        </div>
                        <!-- Affiliation field container (initially hidden) -->
                        <div
                            id="affiliationContainer"
                            class="col-md-6"
                            style="display: none"
                        >
                            <label class="mt-3"
                                >Institute Affiliation Type</label
                            >
                            <select
                                class="form-select mt-2"
                                id="affiliationType"
                                name="affiliation"
                            >
                                <option selected disabled value="">
                                    Choose...
                                </option>
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="mt-3"
                                >Institute Registeration Type <span style="color: red;">*</span></label
                            >
                            <select
                                class="form-select mt-2"
                                id="registrationType"
                                name="registration_type"
                                required
                            >
                                <option selected disabled value>
                                    Choose...
                                </option>
                                <option name="registration_type">
                                    Society
                                </option>
                                <option name="registration_type">
                                    Trust
                                </option>
                                <option name="registration_type">
                                    Pvt.Ltd
                                </option>
                                <option name="registration_type">
                                    Ltd
                                </option>
                                <option name="registration_type">
                                    Llp
                                </option>
                                <option name="registration_type">
                                    Other
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="mt-3">Email <span style="color: red;">*</span></label>
                            <input
                                type="email"
                                class="form-control mt-2"
                                id="email"
                                name="email"
                                required
                            />
                        </div>
                        <div class="col-md-6">
                            <label class="mt-3">Contact Number <span style="color: red;">*</span></label>
                            <input
                                type="text"
                                class="form-control mt-2"
                                id="contactNumber"
                                name="contact_number"
                                required
                            />
                        </div>
                        <div class="col-md-6">
                            <label class="mt-3">Address Line 1 <span style="color: red;">*</span></label>
                            <input
                                type="text"
                                class="form-control mt-2"
                                id="addressLine1"
                                name="address_line_1"
                                required
                            />
                        </div>
                        <div class="col-md-6">
                            <label class="mt-3">Address Line 2</label>
                            <input
                                type="text"
                                class="form-control mt-2"
                                id="addressLine2"
                                name="address_line_2"
                            />
                        </div>
                        <div class="col-md-6">
                            <label
                                for="validationCustom04"
                                class="form-label mt-3"
                                >State <span style="color: red;">*</span></label
                            >
                            <select
                                class="form-select"
                                id="state"
                                name="state"
                                required
                            >
                                <option selected disabled value>
                                    choose...
                                </option>
                                <option>...</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label
                                for="validationCustom03"
                                class="form-label mt-3"
                                >City <span style="color: red;">*</span></label
                            >
                            <select
                                class="form-select"
                                id="city"
                                name="city"
                                required
                            >
                                <option selected disabled value>
                                    Choose...
                                </option>
                                <option>...</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="mt-3">Pincode <span style="color: red;">*</span></label>
                            <input
                                type="text"
                                class="form-control mt-2"
                                id="pincode"
                                name="pincode"
                                required
                            />
                        </div>
                        <div class="col-md-6">
                            <label class="mt-3"
                                >Date of Establishment <span style="color: red;">*</span></label
                            >
                            <input
                                type="date"
                                class="form-control mt-2"
                                id="establishmentDate"
                                name="establishment_date"
                                required
                            />
                        </div>
                        <div class="col-md-6">
                            <label class="mt-3">Website URL</label>
                            <input
                                type="text"
                                class="form-control mt-2"
                                id="website"
                                name="website"
                            />
                        </div>
                    </div>
                    <div class="form-navigation">
                        <span></span>
                        <button type="submit" class="btn btn-primary">
                            Next
                        </button>
                    </div>
                </form>

                <!-- Form 2 -->
                <form
                    id="form2"
                    style="display: none"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    <br />
                    <h4>Stakeholder Details</h4>
                    <div id="dynamicForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name"> Full Name <span style="color: red;">*</span></label>
                                <input
                                    type="text"
                                    placeholder="Ex:Tarun Dhiman|Tarun Singh Dhiman"
                                    name="name[]"
                                    class="form-control stakeholder-name"
                                />
                            </div>
                            <div class="form-group">
                                <label for="email">Email <span style="color: red;">*</span></label>
                                <input
                                    type="email"
                                    placeholder="Ex:tarun.dhiman@gmail.com"
                                    name="email[]"
                                    class="form-control stakeholder-email"
                                />
                            </div>
                            <div class="form-group">
                                <label for="number">Phone Number <span style="color: red;">*</span></label>
                                <input
                                    type="text"
                                    placeholder="Enter your Phone Number"
                                    name="phone_number[]"
                                    class="form-control stakeholder-number"
                                />
                            </div>
                            <div class="form-group">
                                <label for="designation"
                                    >Designation <span style="color: red;">*</span></label
                                >
                                <select
                                    id
                                    name="designation[]"
                                    class="form-control stakeholder-designation"
                                >
                                    <option value>Select</option>
                                    <option value="owner">Owner</option>
                                    <option value="director">
                                        Director
                                    </option>
                                    <option value="partner">Partner</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="pannumber">Pan Number <span style="color: red;">*</span></label>
                                <input
                                    type="text"
                                    placeholder="Enter your Pan Number"
                                    name="pan_number[]"
                                    class="form-control stakeholder-pannumber"
                                />
                            </div>
                            <div class="form-group">
                                <label for="aadhaarnumber"
                                    >Aadhaar Number <span style="color: red;">*</span></label
                                >
                                <input
                                    type="text"
                                    placeholder="Enter your Aadhaar Number"
                                    name="aadhaar_number[]"
                                    class="form-control stakeholder-aadhaarnumber"
                                />
                            </div>
                            <div class="btn-group-custom">
                                <button
                                    type="button"
                                    class="btn btn-success action-btn"
                                    onclick="addStakeholderRow()"
                                >
                                    +
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-danger action-btn"
                                    onclick="deleteStakeholderRow(this)"
                                >
                                    −
                                </button>
                            </div>
                        </div>
                    </div>
                    <br /><br />
                    <h4 class="mt-4">Authorized User Details</h4>

                    <!-- Copy from Stakeholder Section -->
                    <div class="copy-stakeholder-section">
                        <div class="copy-stakeholder-title">
                            <i class="fas fa-copy"></i> Copy Details from
                            Stakeholders
                        </div>
                        <div
                            id="stakeholderCheckboxes"
                            class="stakeholder-checkbox-group"
                        >
                            <!-- Checkboxes will be dynamically generated here -->
                        </div>
                    </div>

                    <div id="authorizedForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name"> Full Name</label>
                                <input
                                    type="text"
                                    placeholder="Ex:Tarun Dhiman|Tarun Singh Dhiman"
                                    name="authorized_name[]"
                                    class="form-control authorized-name"
                                />
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input
                                    type="email"
                                    placeholder="Ex:tarun.dhiman@gmail.com"
                                    name="authorized_email[]"
                                    class="form-control authorized-email"
                                />
                            </div>
                            <div class="form-group">
                                <label for="number">Phone Number</label>
                                <input
                                    type="text"
                                    placeholder="Enter your Phone Number"
                                    name="authorized_phone_number[]"
                                    class="form-control authorized-number"
                                />
                            </div>
                            <div class="form-group">
                                <label for="designation">Designation</label>
                                <select
                                    id
                                    name="authorized_designation[]"
                                    class="form-control authorized-designation"
                                >
                                    <option value>Select</option>
                                    <option value="owner">Owner</option>
                                    <option value="director">
                                        Director
                                    </option>
                                    <option value="admin">Admin</option>
                                    <option value="authorized person">
                                        Authorized Person
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="number">Pan Number</label>
                                <input
                                    type="text"
                                    placeholder="Enter your Pan Number"
                                    name="authorized_pan_number[]"
                                    class="form-control authorized-pannumber"
                                />
                            </div>
                            <div class="form-group">
                                <label for="number">Aadhaar Number</label>
                                <input
                                    type="text"
                                    placeholder="Enter your Aadhaar Number"
                                    name="authorized_aadhaar_number[]"
                                    class="form-control authorized-aadhaarnumber"
                                />
                            </div>
                            <div class="btn-group-custom">
                                <button
                                    type="button"
                                    class="btn btn-success action-btn"
                                    onclick="addAuthorizedRow()"
                                >
                                    +
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-danger action-btn"
                                    onclick="deleteAuthorizedRow(this)"
                                >
                                    −
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-navigation">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            id="backToForm1"
                        >
                            Back
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Next
                        </button>
                    </div>
                </form>

                <!-- Form 3 - Updated to include creative file uploads -->
                <form
                    id="form3"
                    style="display: none"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    <h4>Documents</h4>

                    <!-- Institute Documents Section -->
                    <div class="document-section">
                        <h5>Institute Documents</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <!-- FIX: Added form-group wrapper -->
                                <div class="form-group">
                                    <label class="form-label"
                                        >Registration Number</label
                                    >
                                    <input
                                        type="text"
                                        name="registration_number"
                                        class="form-control"
                                        id="registrationNumber"
                                        placeholder="COI / Registration Number"
                                    />
                                </div>

                                <!-- Creative Upload for Registration Document -->
                                <div class="upload-card">
                                    <div class="upload-header">
                                        <div class="upload-title">
                                            Certificate of Incorporation
                                        </div>
                                        <span class="upload-type"
                                            >Required</span
                                        >
                                    </div>

                                    <div
                                        class="upload-area"
                                        id="registrationUploadArea"
                                    >
                                        <i class="fas fa-file-pdf"></i>
                                        <div class="upload-text">
                                            <h4>
                                                Upload Registration Document
                                            </h4>
                                            <p>PDF, JPG or PNG (Max 5MB)</p>
                                        </div>
                                        <button class="upload-btn">
                                            Select File
                                        </button>
                                        <input
                                            type="file"
                                            name="registration_document_path"
                                            class="file-input"
                                            id="registrationDocument"
                                            accept=".jpg,.jpeg,.png,.pdf"
                                        />
                                    </div>

                                    <div class="progress-bar">
                                        <div class="progress-fill"></div>
                                    </div>

                                    <div class="upload-status">
                                        <i
                                            class="fas fa-exclamation-circle status-icon status-pending"
                                        ></i>
                                        <span class="status-text"
                                            >No file uploaded</span
                                        >
                                    </div>

                                    <div class="preview-area">
                                        <div class="preview-header">
                                            <div class="preview-title">
                                                Uploaded File
                                            </div>
                                            <button class="change-file">
                                                Change File
                                            </button>
                                        </div>
                                        <div
                                            class="preview-container"
                                        ></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- FIX: Added form-group wrapper -->
                                <div class="form-group">
                                    <label class="form-label"
                                        >PAN Number</label
                                    >
                                    <input
                                        type="text"
                                        name="pan_number"
                                        class="form-control"
                                        id="institutePanNumber"
                                        placeholder="Enter PAN Number"
                                    />
                                </div>

                                <!-- Creative Upload for PAN Document -->
                                <div class="upload-card">
                                    <div class="upload-header">
                                        <div class="upload-title">
                                            PAN Card Document
                                        </div>
                                        <span class="upload-type"
                                            >Required</span
                                        >
                                    </div>

                                    <div
                                        class="upload-area"
                                        id="panUploadArea"
                                    >
                                        <i class="fas fa-id-card"></i>
                                        <div class="upload-text">
                                            <h4>Upload PAN Document</h4>
                                            <p>PDF, JPG or PNG (Max 5MB)</p>
                                        </div>
                                        <button class="upload-btn">
                                            Select File
                                        </button>
                                        <input
                                            name="pan_document_path"
                                            type="file"
                                            class="file-input"
                                            id="panDocument"
                                            accept=".jpg,.jpeg,.png,.pdf"
                                        />
                                    </div>

                                    <div class="progress-bar">
                                        <div class="progress-fill"></div>
                                    </div>

                                    <div class="upload-status">
                                        <i
                                            class="fas fa-exclamation-circle status-icon status-pending"
                                        ></i>
                                        <span class="status-text"
                                            >No file uploaded</span
                                        >
                                    </div>

                                    <div class="preview-area">
                                        <div class="preview-header">
                                            <div class="preview-title">
                                                Uploaded File
                                            </div>
                                            <button class="change-file">
                                                Change File
                                            </button>
                                        </div>
                                        <div
                                            class="preview-container"
                                        ></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Creative Upload for Institute Logo -->
                                <div class="upload-card">
                                    <div class="upload-header">
                                        <div class="upload-title">
                                            Institute Logo
                                        </div>
                                        <span class="upload-type"
                                            >Required</span
                                        >
                                    </div>

                                    <div
                                        class="upload-area"
                                        id="logoUploadArea"
                                    >
                                        <i class="fas fa-image"></i>
                                        <div class="upload-text">
                                            <h4>Upload Institute Logo</h4>
                                            <p>JPG or PNG (Max 5MB)</p>
                                        </div>
                                        <button class="upload-btn">
                                            Select File
                                        </button>
                                        <input
                                            name="logo_path"
                                            type="file"
                                            class="file-input"
                                            id="instituteLogo"
                                            accept=".jpg,.jpeg,.png"
                                        />
                                    </div>

                                    <div class="progress-bar">
                                        <div class="progress-fill"></div>
                                    </div>

                                    <div class="upload-status">
                                        <i
                                            class="fas fa-exclamation-circle status-icon status-pending"
                                        ></i>
                                        <span class="status-text"
                                            >No file uploaded</span
                                        >
                                    </div>

                                    <div class="preview-area">
                                        <div class="preview-header">
                                            <div class="preview-title">
                                                Uploaded File
                                            </div>
                                            <button class="change-file">
                                                Change File
                                            </button>
                                        </div>
                                        <div
                                            class="preview-container"
                                        ></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Creative Upload for Institute Image -->
                                <div class="upload-card">
                                    <div class="upload-header">
                                        <div class="upload-title">
                                            Institute Image
                                        </div>
                                        <span class="upload-type"
                                            >Required</span
                                        >
                                    </div>

                                    <div
                                        class="upload-area"
                                        id="imageUploadArea"
                                    >
                                        <i class="fas fa-building"></i>
                                        <div class="upload-text">
                                            <h4>Upload Institute Image</h4>
                                            <p>JPG or PNG (Max 5MB)</p>
                                        </div>
                                        <button class="upload-btn">
                                            Select File
                                        </button>
                                        <input
                                            name="institute_image_path"
                                            type="file"
                                            class="file-input"
                                            id="instituteImage"
                                            accept=".jpg,.jpeg,.png"
                                        />
                                    </div>

                                    <div class="progress-bar">
                                        <div class="progress-fill"></div>
                                    </div>

                                    <div class="upload-status">
                                        <i
                                            class="fas fa-exclamation-circle status-icon status-pending"
                                        ></i>
                                        <span class="status-text"
                                            >No file uploaded</span
                                        >
                                    </div>

                                    <div class="preview-area">
                                        <div class="preview-header">
                                            <div class="preview-title">
                                                Uploaded File
                                            </div>
                                            <button class="change-file">
                                                Change File
                                            </button>
                                        </div>
                                        <div
                                            class="preview-container"
                                        ></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <!-- FIX: Added form-group wrapper -->
                                <div class="form-group">
                                    <label class="form-label"
                                        >GST Number</label
                                    >
                                    <input
                                        type="text"
                                        name="gst_number"
                                        class="form-control"
                                        id="gstNumber"
                                        placeholder="Enter GST Number"
                                    />
                                </div>

                                <!-- Creative Upload for GST Document -->
                                <div class="upload-card">
                                    <div class="upload-header">
                                        <div class="upload-title">
                                            GST Certificate
                                        </div>
                                        <span class="upload-type"
                                            >Required</span
                                        >
                                    </div>

                                    <div
                                        class="upload-area"
                                        id="gstUploadArea"
                                    >
                                        <i
                                            class="fas fa-file-invoice-dollar"
                                        ></i>
                                        <div class="upload-text">
                                            <h4>Upload GST Document</h4>
                                            <p>PDF, JPG or PNG (Max 5MB)</p>
                                        </div>
                                        <button class="upload-btn">
                                            Select File
                                        </button>
                                        <input
                                            name="gst_document_path"
                                            type="file"
                                            class="file-input"
                                            id="gstDocument"
                                            accept=".jpg,.jpeg,.png,.pdf"
                                        />
                                    </div>

                                    <div class="progress-bar">
                                        <div class="progress-fill"></div>
                                    </div>

                                    <div class="upload-status">
                                        <i
                                            class="fas fa-exclamation-circle status-icon status-pending"
                                        ></i>
                                        <span class="status-text"
                                            >No file uploaded</span
                                        >
                                    </div>

                                    <div class="preview-area">
                                        <div class="preview-header">
                                            <div class="preview-title">
                                                Uploaded File
                                            </div>
                                            <button class="change-file">
                                                Change File
                                            </button>
                                        </div>
                                        <div
                                            class="preview-container"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stakeholder Documents Section (will be dynamically generated) -->
                    <div id="stakeholderDocumentsContainer"></div>

                    <!-- Authorized User Documents Section (will be dynamically generated) -->
                    <div id="authorizedDocumentsContainer"></div>

                    <!-- Navigation Buttons -->
                    <div class="form-navigation mt-4">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            id="backToForm2"
                        >
                            Back
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Next
                        </button>
                    </div>
                </form>

                <!-- Form 4 -->
                <form
                    id="form4"
                    style="display: none"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    <h4>Beneficiary Details</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="mt-3">Beneficiary Name</label>
                            <input
                                type="text"
                                class="form-control mt-2"
                                id="beneficiaryName"
                                name="beneficiary_name"
                            />
                        </div>
                        <div class="col-md-6">
                            <label class="mt-3">Bank Account Number</label>
                            <input
                                type="text"
                                class="form-control mt-2"
                                id="accountNumber"
                                name="account_number"
                            />
                        </div>
                        <div class="col-md-6">
                            <label class="mt-3">Bank Name</label>
                            <input
                                type="text"
                                class="form-control mt-2"
                                id="bankName"
                                name="bank_name"
                            />
                        </div>
                        <div class="col-md-6">
                            <label class="mt-3">IFSC Code</label>
                            <input
                                type="text"
                                class="form-control mt-2"
                                id="ifscCode"
                                name="ifsc_code"
                            />
                        </div>
                        <div class="col-md-6">
                            <label
                                for="validationCustom03"
                                class="form-label mt-3"
                                >Account Type</label
                            >
                            <select
                                class="form-select"
                                id="accountType"
                                name="account_type"
                            >
                                <option selected disabled value>
                                    Choose...
                                </option>
                                <option>Current</option>
                                <option>Saving</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label
                                for="validationCustom03"
                                class="form-label mt-3"
                                >Cancelled Cheque</label
                            >
                            <!-- Creative Upload for Cancelled Cheque -->
                            <div class="upload-card">
                                <div class="upload-header">
                                    <span class="upload-type"
                                        >Required</span
                                    >
                                </div>

                                <div
                                    class="upload-area"
                                    id="chequeUploadArea"
                                >
                                    <i class="fas fa-money-check"></i>
                                    <div class="upload-text">
                                        <h4>Upload Cancelled Cheque</h4>
                                        <p>JPG or PNG (Max 5MB)</p>
                                    </div>
                                    <button class="upload-btn">
                                        Select File
                                    </button>
                                    <input
                                        type="file"
                                        name="cancelled_cheque_path"
                                        class="file-input"
                                        id="cancelledCheque"
                                        accept=".jpg,.jpeg,.png"
                                    />
                                </div>

                                <div class="progress-bar">
                                    <div class="progress-fill"></div>
                                </div>

                                <div class="upload-status">
                                    <i
                                        class="fas fa-exclamation-circle status-icon status-pending"
                                    ></i>
                                    <span class="status-text"
                                        >No file uploaded</span
                                    >
                                </div>

                                <div class="preview-area">
                                    <div class="preview-h">
                                        <div class="preview-title">
                                            Uploaded File
                                        </div>
                                        <button class="change-file">
                                            Change File
                                        </button>
                                    </div>
                                    <div class="preview-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                value
                                id="termsAgreement"
                            />
                            <label
                                class="form-check-label"
                                for="termsAgreement"
                            >
                                Agree to terms and conditions
                            </label>
                            <div class="invalid-feedback">
                                You must agree before submitting.
                            </div>
                        </div>
                    </div>
                    <div class="form-navigation">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            id="backToForm3"
                        >
                            Back
                        </button>
                        <button type="submit" class="btn btn-success">
                            Submit
                        </button>
                    </div>
                </form>

                <!-- Final Success Message -->
                <div
                    id="successMessage"
                    class="alert alert-success mt-3"
                    style="display: none"
                >
                    ✅ All forms submitted successfully! Data saved to
                    localStorage.
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            $(document).ready(function() {
                const instituteType = document.getElementById('instituteType');
                const affiliationContainer = document.getElementById('affiliationContainer');
                const affiliationSelect = document.getElementById('affiliationType');
                const affiliationOptions = {
                    'School': ['CBSE', 'ICSE', 'IGCSE', 'State Board', 'NIOS (Open Schooling)', 'IB (International Board)', 'Other'],
                    'College': ['UGC', 'AICTE', 'NAAC', 'State University', 'Deemed University', 'Autonomous', 'Private', 'Other'],
                    'University': ['UGC', 'State University', 'Central University', 'Deemed University', 'Private University', 'NAAC Accredited', 'Other'],
                    'Coaching': ['Registered Coaching Center', 'Individual', 'Franchise', 'Online Platform', 'Test Preparation Center', 'Other'],
                    'Tutor': ['Independent Tutor', 'Agency Tutor', 'Online Tutor', 'Home Tutor', 'Other']
                };
                instituteType.addEventListener('change', function() {
                    const selectedType = this.value;
                    // Clear existing options except first one
                    while (affiliationSelect.options.length > 1) {
                        affiliationSelect.remove(1);
                    }
                    if (selectedType && affiliationOptions[selectedType]) {
                        affiliationContainer.style.display = 'block';
                        // Add new options
                        affiliationOptions[selectedType].forEach(option => {
                            const optionElement = document.createElement('option');
                            optionElement.value = option;
                            optionElement.textContent = option;
                            // Select if editing with existing value
                            @if(isset($serviceInstitutedetails->affiliation))
                                if (option === '{{ $serviceInstitutedetails->affiliation }}') {
                                    optionElement.selected = true;
                                }
                            @endif
                            affiliationSelect.appendChild(optionElement);
                        });
                    } else {
                        affiliationContainer.style.display = 'none';
                    }
                });

                // Initialize on page load for edit mode
                @if(isset($serviceInstitutedetails->type))
                    instituteType.value = '{{ $serviceInstitutedetails->type }}';
                    instituteType.dispatchEvent(new Event('change'));
                @endif

                // Load states automatically for India when page loads
                loadStates();

                // Function to fetch states
                function loadStates() {
                    $('#state').html('<option value="">Loading...</option>');
                    $.ajax({
                        url: "{{ route('get.states') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            $('#state').empty().append('<option value="">Choose...</option>');
                            if (response.success && response.data && response.data.states) {
                                $.each(response.data.states, function(index, state) {
                                    $('#state').append('<option value="' + state.name + '">' + state
                                        .name + '</option>');
                                });
                            } else {
                                $('#state').append('<option value="">No states found</option>');
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            $('#state').html('<option value="">Error loading states</option>');
                        }
                    });
                }

                // Fetch cities when a state is selected
                $('#state').on('change', function() {
                    var stateName = $(this).val();
                    $('#city').html('<option value="">Loading...</option>');

                    $.ajax({
                        url: "{{ route('get.cities') }}",
                        type: "POST",
                        data: {
                            state: stateName,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            $('#city').empty().append('<option value="">Choose...</option>');
                            if (response.success && response.data && response.data.city) {
                                $.each(response.data.city, function(index, city) {
                                    $('#city').append('<option value="' + city + '">' +
                                        city + '</option>');
                                });
                            } else {
                                $('#city').append('<option value="">No cities found</option>');
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            $('#city').html('<option value="">Error loading cities</option>');
                        }
                    });
                });
            });

            document.addEventListener("DOMContentLoaded", function () {
                    // ================================
                    // STEP STATE VARIABLES
                    // ================================
                    let currentStep = 1;      // Currently visible step
                    let maxUnlockedStep = 1;  // Highest unlocked step
                    
                    // ================================
                    // INITIAL LOCK
                    // ================================
                    lockFutureForms();

                    // ================================
                    // TIMELINE CLICK HANDLER
                    // ================================
                    const steps = document.querySelectorAll(".timeline-step");
                    steps.forEach(step => {
                        step.addEventListener("click", function () {
                            const stepNumber = parseInt(this.id.replace("step", ""));
                            showStep(stepNumber, false);
                        });
                    });

                    // ================================
                    // FORM SUBMIT HANDLERS
                    // ================================
                    document.querySelector("#form1").addEventListener("submit", function (e) {
                        e.preventDefault();

                        if (!validateForm1()) {
                            return;
                        }

                        unlockForm(2);
                        showStep(2, true);
                    });

                    document.querySelector("#form2").addEventListener("submit", function (e) {
                        e.preventDefault();

                        if (!validateForm2()) {
                            return;
                        }

                        unlockForm(3);
                        showStep(3, true);
                    });

                    document.querySelector("#form3").addEventListener("submit", function (e) {
                        e.preventDefault();

                        if (!validateForm3()) {
                            return;
                        }

                        unlockForm(4);
                        showStep(4, true);
                    });

                    // ================================
                    // BACK BUTTONS
                    // ================================
                    document.getElementById("backToForm1")?.addEventListener("click", () => showStep(1, true));
                    document.getElementById("backToForm2")?.addEventListener("click", () => showStep(2, true));
                    document.getElementById("backToForm3")?.addEventListener("click", () => showStep(3, true));

                    // ================================
                    // FUNCTIONS
                    // ================================

                    function lockFutureForms() {
                        document.querySelectorAll("form[id^='form']").forEach(form => {
                            const step = parseInt(form.id.replace("form", ""));
                            if (step > currentStep) {
                                form.classList.add("form-disabled");
                            }
                        });
                    }

                    function unlockForm(step) {
                        if (step > maxUnlockedStep) {
                            maxUnlockedStep = step;
                        }
                        document.getElementById("form" + step)?.classList.remove("form-disabled");
                    }

                    function showStep(step) {
                        currentStep = step;

                        // Highlight active timeline step
                        document.querySelectorAll(".timeline-step").forEach(s => s.classList.remove("active"));
                        document.getElementById("step" + step)?.classList.add("active");

                        // Hide all forms
                        document.querySelectorAll("form[id^='form']").forEach(form => form.style.display = "none");

                        // Show current form
                        const form = document.getElementById("form" + step);
                        form.style.display = "block";

                        // If step is locked, make inputs readonly/disabled
                        const isLocked = step > maxUnlockedStep;
                        form.querySelectorAll("input, select, textarea, button[type='submit']").forEach(el => {
                            if (isLocked) {
                                el.disabled = true; // disable editing
                            } else {
                                el.disabled = false; // allow editing
                            }
                        });

                        // Add visual lock styling
                        form.classList.toggle("form-disabled", isLocked);
                    }

                    // ================================
                    // VALIDATION FUNCTIONS
                    // ================================
                    function validateForm1() {
                        let isValid = true;
                        const form = document.getElementById('form1');

                        // Check all visible required inputs
                        form.querySelectorAll("input[required], select[required], textarea[required]").forEach(input => {
                            if (input.disabled) return; // skip disabled inputs

                            if (!input.value.trim()) {
                                input.classList.add('is-invalid');
                                isValid = false;
                            } else {
                                input.classList.remove('is-invalid');
                            }
                        });

                        // if (!isValid) {
                        //     console.log("Form 1 failed manual validation");
                        // }

                        return isValid;
                    }

                    function validateForm2() {
                        const form = document.getElementById('form2');
                        if (!form.checkValidity()) {
                            form.reportValidity();
                            return false;
                        }
                        return true;
                    }

                    function validateForm3() {
                        const form = document.getElementById('form3');
                        if (!form.checkValidity()) {
                            form.reportValidity();
                            return false;
                        }
                        return true;
                    }

                });

            // Initialize form data object in memory
            let formData = {
                form1: {},
                form2: {
                    stakeholders: [],
                    authorizedUsers: []
                },
                form3: {},
                form4: {}
            };

            // Form Validation Functions
            const validationRules = {
                form1: {
                    instituteName: {
                        required: true,
                        minLength: 2,
                        maxLength: 255
                    },
                    instituteType: {
                        required: true
                    },
                    registrationType: {
                        required: true
                    },
                    email: {
                        required: true,
                        type: 'email'
                    },
                    contactNumber: {
                        required: true,
                        pattern: /^[6-9]\d{9}$/
                    }, // Indian mobile numbers
                    addressLine1: {
                        required: true,
                        minLength: 5
                    },
                    state: {
                        required: true
                    },
                    city: {
                        required: true
                    },
                    pincode: {
                        required: true,
                        pattern: /^\d{6}$/
                    },
                    establishmentDate: {
                        required: true,
                        type: 'date'
                    },
                    website: {
                        required: false,
                        type: 'url'
                    }
                },
                stakeholder: {
                    name: {
                        required: true,
                        minLength: 2,
                        maxLength: 255
                    },
                    email: {
                        required: true,
                        type: 'email'
                    },
                    phone_number: {
                        required: true,
                        pattern: /^[6-9]\d{9}$/
                    },
                    designation: {
                        required: true
                    },
                    pan_number: {
                        required: true,
                        pattern: /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/
                    },
                    aadhaar_number: {
                        required: true,
                        pattern: /^\d{12}$/
                    }
                },
                authorizedUser: {
                    authorized_name: {
                        required: true,
                        minLength: 2,
                        maxLength: 255
                    },
                    authorized_email: {
                        required: true,
                        type: 'email'
                    },
                    authorized_phone_number: {
                        required: true,
                        pattern: /^[6-9]\d{9}$/
                    },
                    authorized_designation: {
                        required: true
                    },
                    authorized_pan_number: {
                        required: true,
                        pattern: /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/
                    },
                    authorized_aadhaar_number: {
                        required: true,
                        pattern: /^\d{12}$/
                    }
                },
                form3: {
                    registrationNumber: {
                        required: true,
                        minLength: 3
                    },
                    institutePanNumber: {
                        required: true,
                        pattern: /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/
                    },
                    gstNumber: {
                        required: false,
                        pattern: /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/
                    }
                },
                form4: {
                    beneficiaryName: {
                        required: true,
                        minLength: 2,
                        maxLength: 255
                    },
                    accountNumber: {
                        required: true,
                        pattern: /^\d{9,18}$/
                    },
                    bankName: {
                        required: true,
                        minLength: 2
                    },
                    ifscCode: {
                        required: true,
                        pattern: /^[A-Z]{4}0[A-Z0-9]{6}$/
                    },
                    accountType: {
                        required: true
                    },
                }
            };

            // Validation utility functions
            const validationUtils = {
                getFieldLabel: (element) => {
                    // Try to get label from associated label tag
                    const id = element.id;
                    if (id) {
                        const label = document.querySelector(`label[for="${id}"]`);
                        if (label) return label.textContent.replace('*', '').trim();
                    }

                    // Try to get label from parent form-group
                    const formGroup = element.closest('.form-group');
                    if (formGroup) {
                        const label = formGroup.querySelector('label');
                        if (label) return label.textContent.replace('*', '').trim();
                    }

                    // Try to get from placeholder
                    if (element.placeholder) {
                        return element.placeholder;
                    }

                    // Fallback to field name
                    const fieldName = element.getAttribute('name') || element.id;
                    return validationUtils.formatFieldName(fieldName); // FIX: Use validationUtils instead of this
                },

                formatFieldName: (fieldName) => {
                    // Convert field names to readable format
                    const nameMap = {
                        'name': 'Institue/School Name',
                        'email': 'Institue/School Email',
                        'phone_number': 'Phone Number',
                        'designation': 'Designation',
                        'pan_number': 'PAN Number',
                        'aadhaar_number': 'Aadhaar Number',
                        'authorized_name': 'Full Name',
                        'authorized_email': 'Email',
                        'authorized_phone_number': 'Phone Number',
                        'authorized_designation': 'Designation',
                        'authorized_pan_number': 'PAN Number',
                        'authorized_aadhaar_number': 'Aadhaar Number',
                        'instituteName': 'Institue/School Name',
                        'instituteType': 'Institue/School Type',
                        'registrationType': 'Registration Type',
                        'contactNumber': 'Contact Number',
                        'addressLine1': 'Address Line 1',
                        'addressLine2': 'Address Line 2',
                        'state': 'State',
                        'city': 'City',
                        'pincode': 'Pincode',
                        'establishmentDate': 'Date of Establishment',
                        'website': 'Website URL',
                        'registrationNumber': 'Registration Number',
                        'institutePanNumber': 'PAN Number',
                        'gstNumber': 'GST Number',
                        'beneficiaryName': 'Beneficiary Name',
                        'accountNumber': 'Bank Account Number',
                        'bankName': 'Bank Name',
                        'ifscCode': 'IFSC Code',
                        'accountType': 'Account Type',
                        'termsAgreement': 'Terms and Conditions'
                    };

                    return nameMap[fieldName] || fieldName.replace(/([A-Z])/g, ' $1').replace(/_/g, ' ').replace(/^\w/,
                        c => c.toUpperCase());
                },

                showError: (element, message) => {
                    // Remove existing error
                    validationUtils.removeError(element);

                    // Add error class
                    element.classList.add('is-invalid');

                    // Create error message
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = message;
                    errorDiv.id = `${element.id}-error`;

                    // Insert after the element
                    element.parentNode.appendChild(errorDiv);
                },

                removeError: (element) => {
                    element.classList.remove('is-invalid');
                    element.classList.add('is-valid');

                    const existingError = document.getElementById(`${element.id}-error`);
                    if (existingError) {
                        existingError.remove();
                    }
                },

                validateField: (element, rules) => {
                    const value = element.value.trim();
                    const fieldLabel = validationUtils.getFieldLabel(
                        element); // FIX: Use validationUtils instead of this

                    // Required validation
                    if (rules.required && !value) {
                        validationUtils.showError(element, `${fieldLabel} is required`);
                        return false;
                    }

                    // If not required and empty, skip other validations
                    if (!rules.required && !value) {
                        validationUtils.removeError(element);
                        return true;
                    }

                    // Type validation
                    if (rules.type === 'email' && value) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(value)) {
                            validationUtils.showError(element, 'Please enter a valid email address');
                            return false;
                        }
                    }

                    if (rules.type === 'url' && value) {
                        try {
                            new URL(value.startsWith('http') ? value : `https://${value}`);
                        } catch {
                            validationUtils.showError(element, 'Please enter a valid URL');
                            return false;
                        }
                    }

                    if (rules.type === 'date' && value) {
                        const inputDate = new Date(value);
                        const today = new Date();
                        if (inputDate > today) {
                            validationUtils.showError(element, 'Date cannot be in the future');
                            return false;
                        }
                    }

                    // Pattern validation
                    if (rules.pattern && value) {
                        if (!rules.pattern.test(value)) {
                            let message = 'Invalid format';

                            // Custom messages based on field type
                            if (fieldLabel.toLowerCase().includes('phone') || fieldLabel.toLowerCase().includes(
                                    'contact')) {
                                message = 'Please enter a valid 10-digit mobile number';
                            } else if (fieldLabel.toLowerCase().includes('pincode')) {
                                message = 'Please enter a valid 6-digit pincode';
                            } else if (fieldLabel.toLowerCase().includes('pan')) {
                                message = 'Please enter a valid PAN number (e.g., ABCDE1234F)';
                            } else if (fieldLabel.toLowerCase().includes('aadhaar')) {
                                message = 'Please enter a valid 12-digit Aadhaar number';
                            } else if (fieldLabel.toLowerCase().includes('ifsc')) {
                                message = 'Please enter a valid IFSC code (e.g., SBIN0000123)';
                            } else if (fieldLabel.toLowerCase().includes('account')) {
                                message = 'Please enter a valid account number (9-18 digits)';
                            } else if (fieldLabel.toLowerCase().includes('gst')) {
                                message = 'Please enter a valid GST number';
                            } else {
                                message = `Please enter a valid ${fieldLabel.toLowerCase()}`;
                            }

                            validationUtils.showError(element, message);
                            return false;
                        }
                    }

                    // Length validation
                    if (rules.minLength && value.length < rules.minLength) {
                        validationUtils.showError(element,
                            `${fieldLabel} must be at least ${rules.minLength} characters`);
                        return false;
                    }

                    if (rules.maxLength && value.length > rules.maxLength) {
                        validationUtils.showError(element, `${fieldLabel} cannot exceed ${rules.maxLength} characters`);
                        return false;
                    }

                    // If all validations pass
                    validationUtils.removeError(element);
                    return true;
                },

                validateFile: (fileInput, required = false, allowedTypes = [], maxSize = 5 * 1024 * 1024, customLabel =
                    null) => {
                    const fieldLabel = customLabel || validationUtils.getFieldLabel(fileInput);

                    if (required && (!fileInput.files || fileInput.files.length === 0)) {
                        validationUtils.showError(fileInput, `${fieldLabel} is required`);
                        return false;
                    }

                    if (fileInput.files && fileInput.files.length > 0) {
                        const file = fileInput.files[0];

                        // Size validation
                        if (file.size > maxSize) {
                            validationUtils.showError(fileInput,
                                `${fieldLabel} size should be less than ${maxSize / 1024 / 1024}MB`);
                            return false;
                        }

                        // Type validation
                        if (allowedTypes.length > 0 && !allowedTypes.includes(file.type)) {
                            const allowedExtensions = allowedTypes.map(type => {
                                if (type === 'image/jpeg') return 'JPG';
                                if (type === 'image/png') return 'PNG';
                                if (type === 'application/pdf') return 'PDF';
                                return type;
                            }).join(', ');

                            validationUtils.showError(fileInput,
                                `Only ${allowedExtensions} files are allowed for ${fieldLabel}`);
                            return false;
                        }
                    }

                    validationUtils.removeError(fileInput);
                    return true;
                }
            };

            // Specific form validation functions
            const formValidators = {
                validateForm1: () => {
                    let isValid = true;
                    const form = document.getElementById('form1');

                    Object.keys(validationRules.form1).forEach(fieldId => {
                        const element = document.getElementById(fieldId);
                        if (element) {
                            const fieldValid = validationUtils.validateField(element, validationRules.form1[
                                fieldId]);
                            if (!fieldValid) isValid = false;
                        }
                    });

                    return isValid;
                },

                validateForm2: () => {
                    let isValid = true;

                    // Validate stakeholders
                    const stakeholderRows = document.querySelectorAll('#dynamicForm .form-row');

                    if (stakeholderRows.length === 0) {
                        alert('At least one stakeholder is required');
                        return false;
                    }

                    stakeholderRows.forEach((row, index) => {
                        Object.keys(validationRules.stakeholder).forEach(fieldName => {
                            const element = row.querySelector(`[name="${fieldName}"]`);

                            if (element) {
                                const fieldValid = validationUtils.validateField(element,
                                    validationRules.stakeholder[fieldName]);
                                if (!fieldValid) isValid = false;
                            } else {
                                console.error(`Stakeholder field not found: ${fieldName}`);
                                isValid = false;
                            }
                        });
                    });

                    // Validate authorized users
                    const authorizedRows = document.querySelectorAll('#authorizedForm .form-row');

                    if (authorizedRows.length === 0) {
                        alert('At least one authorized user is required');
                        return false;
                    }

                    authorizedRows.forEach((row, index) => {
                        Object.keys(validationRules.authorizedUser).forEach(fieldName => {
                            const element = row.querySelector(`[name="${fieldName}"]`);

                            if (element) {
                                const fieldValid = validationUtils.validateField(element,
                                    validationRules.authorizedUser[fieldName]);
                                if (!fieldValid) isValid = false;
                            } else {
                                console.error(`Authorized field not found: ${fieldName}`);
                                isValid = false;
                            }
                        });
                    });

                    return isValid;
                },

                validateForm3: () => {
                    let isValid = true;

                    // Validate basic text fields
                    Object.keys(validationRules.form3).forEach(fieldId => {
                        const element = document.getElementById(fieldId);
                        if (element) {
                            const fieldValid = validationUtils.validateField(element, validationRules.form3[
                                fieldId]);
                            if (!fieldValid) isValid = false;
                        }
                    });

                    // Validate institute documents
                    const requiredFiles = [{
                            id: 'registrationDocument',
                            types: ['image/jpeg', 'image/png', 'application/pdf'],
                            required: true,
                            label: 'Registration Document'
                        },
                        {
                            id: 'panDocument',
                            types: ['image/jpeg', 'image/png', 'application/pdf'],
                            required: true,
                            label: 'PAN Document'
                        },
                        {
                            id: 'gstDocument',
                            types: ['image/jpeg', 'image/png', 'application/pdf'],
                            required: false,
                            label: 'GST Document'
                        },
                        {
                            id: 'instituteLogo',
                            types: ['image/jpeg', 'image/png'],
                            required: true,
                            label: 'Institute Logo'
                        },
                        {
                            id: 'instituteImage',
                            types: ['image/jpeg', 'image/png'],
                            required: true,
                            label: 'Institute Image'
                        }
                    ];

                    requiredFiles.forEach(fileConfig => {
                        const fileInput = document.getElementById(fileConfig.id);
                        if (fileInput) {
                            const fileValid = validationUtils.validateFile(fileInput, fileConfig.required,
                                fileConfig.types);
                            if (!fileValid) isValid = false;
                        }
                    });
                    // Validate stakeholder documents
                    const stakeholderRows = document.querySelectorAll('#dynamicForm .form-row');
                    stakeholderRows.forEach((row, index) => {
                        const aadhaarFront = document.querySelector(
                            `input[name="stakeholder${index}_aadhaar_front"]`);
                        const aadhaarBack = document.querySelector(
                            `input[name="stakeholder${index}_aadhaar_back"]`);
                        const panDoc = document.querySelector(`input[name="stakeholder${index}_pan"]`);

                        if (aadhaarFront) {
                            const valid = validationUtils.validateFile(aadhaarFront, true, ['image/jpeg',
                                'image/png'
                            ]);
                            if (!valid) isValid = false;
                        }

                        if (aadhaarBack) {
                            const valid = validationUtils.validateFile(aadhaarBack, true, ['image/jpeg',
                                'image/png'
                            ]);
                            if (!valid) isValid = false;
                        }

                        if (panDoc) {
                            const valid = validationUtils.validateFile(panDoc, true, ['image/jpeg', 'image/png',
                                'application/pdf'
                            ]);
                            if (!valid) isValid = false;
                        }
                    });
                    // Validate authorized user documents
                    const authorizedRows = document.querySelectorAll('#authorizedForm .form-row');
                    authorizedRows.forEach((row, index) => {
                        const aadhaarFront = document.querySelector(
                            `input[name="authorized${index}_aadhaar_front"]`);
                        const aadhaarBack = document.querySelector(
                            `input[name="authorized${index}_aadhaar_back"]`);
                        const panDoc = document.querySelector(`input[name="authorized${index}_pan"]`);

                        if (aadhaarFront) {
                            const valid = validationUtils.validateFile(aadhaarFront, true, ['image/jpeg',
                                'image/png'
                            ]);
                            if (!valid) isValid = false;
                        }

                        if (aadhaarBack) {
                            const valid = validationUtils.validateFile(aadhaarBack, true, ['image/jpeg',
                                'image/png'
                            ]);
                            if (!valid) isValid = false;
                        }

                        if (panDoc) {
                            const valid = validationUtils.validateFile(panDoc, true, ['image/jpeg', 'image/png',
                                'application/pdf'
                            ]);
                            if (!valid) isValid = false;
                        }
                    });

                    return isValid;
                },

                validateForm4: () => {
                    let isValid = true;

                    // Validate basic fields
                    Object.keys(validationRules.form4).forEach(fieldId => {
                        const element = document.getElementById(fieldId);
                        if (element) {
                            if (fieldId === 'termsAgreement') {
                                // Special handling for checkbox
                                if (!element.checked) {
                                    validationUtils.showError(element,
                                        'You must agree to the terms and conditions');
                                    isValid = false;
                                } else {
                                    validationUtils.removeError(element);
                                }
                            } else {
                                const fieldValid = validationUtils.validateField(element, validationRules.form4[
                                    fieldId]);
                                if (!fieldValid) isValid = false;
                            }
                        }
                    });

                    // Validate cancelled cheque
                    const cancelledCheque = document.getElementById('cancelledCheque');
                    if (cancelledCheque) {
                        const fileValid = validationUtils.validateFile(cancelledCheque, true, ['image/jpeg',
                            'image/png'
                        ]);
                        if (!fileValid) isValid = false;
                    }

                    return isValid;
                }
            };

            // Real-time validation for better UX
            function setupRealTimeValidation() {
                // Form 1 real-time validation
                Object.keys(validationRules.form1).forEach(fieldId => {
                    const element = document.getElementById(fieldId);
                    if (element) {
                        element.addEventListener('blur', () => {
                            validationUtils.validateField(element, validationRules.form1[fieldId]);
                        });

                        // Clear validation on input
                        element.addEventListener('input', () => {
                            if (element.classList.contains('is-invalid')) {
                                validationUtils.validateField(element, validationRules.form1[fieldId]);
                            }
                        });
                    }
                });

                // Real-time validation for dynamic fields will be handled when they're created
            }

            // Add some CSS for validation styles
            const validationStyles = `
                .is-invalid {
                    border-color: #dc3545 !important;
                    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6.4.4.4-.4'/%3e%3cpath d='M6 7v1'/%3e%3c/svg%3e");
                    background-repeat: no-repeat;
                    background-position: right calc(0.375em + 0.1875rem) center;
                    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
                }
                .form-group .invalid-feedback {
                    display: block;
                    color: #dc3545;
                    font-size: 0.875em;
                    margin-top: 0.25rem;
                }

                .form-control.is-invalid {
                    border-color: #dc3545;
                    padding-right: calc(1.5em + 0.75rem);
                    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6.4.4.4-.4'/%3e%3cpath d='M6 7v1'/%3e%3c/svg%3e");
                    background-repeat: no-repeat;
                    background-position: right calc(0.375em + 0.1875rem) center;
                    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
                }

                .form-control.is-valid {
                    border-color: #198754;
                    padding-right: calc(1.5em + 0.75rem);
                    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
                    background-repeat: no-repeat;
                    background-position: right calc(0.375em + 0.1875rem) center;
                    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
                }
                .is-valid {
                    border-color: #198754 !important;
                    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
                    background-repeat: no-repeat;
                    background-position: right calc(0.375em + 0.1875rem) center;
                    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
                }

                .invalid-feedback {
                    display: block;
                    width: 100%;
                    margin-top: 0.25rem;
                    font-size: 0.875em;
                    color: #dc3545;
                }

                .form-control.is-invalid:focus {
                    border-color: #dc3545;
                    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
                }

                .form-check-input.is-invalid ~ .form-check-label {
                    color: #dc3545;
                }

                .form-check-input.is-invalid {
                    border-color: #dc3545;
                }

                .form-check-input.is-invalid:checked {
                    background-color: #dc3545;
                    border-color: #dc3545;
                }

                .file-input.is-invalid ~ .upload-area {
                    border-color: #dc3545 !important;
                }

                .file-input.is-invalid ~ .upload-area .upload-text h4 {
                    color: #dc3545;
            }`;

            // Inject validation styles
            const styleSheet = document.createElement('style');
            styleSheet.textContent = validationStyles;
            document.head.appendChild(styleSheet);

            function setupStakeholderValidation(row) {
                const inputs = row.querySelectorAll('input, select');
                inputs.forEach(input => {
                    const fieldName = input.getAttribute('name');
                    const rules = validationRules.stakeholder[fieldName];

                    if (rules) {
                        input.addEventListener('blur', () => {
                            validationUtils.validateField(input, rules);
                        });

                        input.addEventListener('input', () => {
                            if (input.classList.contains('is-invalid')) {
                                validationUtils.validateField(input, rules);
                            }
                        });
                    }
                });
            }

            function setupAuthorizedValidation(row) {
                const inputs = row.querySelectorAll('input, select');
                inputs.forEach(input => {
                    const fieldName = input.getAttribute('name');
                    const rules = validationRules.authorizedUser[fieldName];

                    if (rules) {
                        input.addEventListener('blur', () => {
                            validationUtils.validateField(input, rules);
                        });

                        input.addEventListener('input', () => {
                            if (input.classList.contains('is-invalid')) {
                                validationUtils.validateField(input, rules);
                            }
                        });
                    }
                });
            }

            // Save form data to memory
            function saveFormData(formId) {
                const form = document.getElementById(formId);

                if (formId === 'form2') {
                    // Special handling for form2 (stakeholders and authorized users)
                    const stakeholders = [];
                    const authorizedUsers = [];

                    // Save stakeholders data - WITH CORRECT FIELD NAMES
                    const stakeholderRows = document.querySelectorAll('#dynamicForm .form-row');
                    stakeholderRows.forEach(row => {
                        const name = row.querySelector('.stakeholder-name').value;
                        const email = row.querySelector('.stakeholder-email').value;
                        const phone_number = row.querySelector('.stakeholder-number').value;
                        const designation = row.querySelector('.stakeholder-designation').value;
                        const pan_number = row.querySelector('.stakeholder-pannumber').value;
                        const aadhaar_number = row.querySelector('.stakeholder-aadhaarnumber').value;

                        stakeholders.push({
                            name: name,
                            email: email,
                            phone_number: phone_number,
                            designation: designation,
                            pan_number: pan_number,
                            aadhaar_number: aadhaar_number
                        });
                    });


                    // Save authorized users data
                    const authorizedRows = document.querySelectorAll('#authorizedForm .form-row');
                    authorizedRows.forEach(row => {
                        const authorized_name = row.querySelector('.authorized-name').value;
                        const authorized_email = row.querySelector('.authorized-email').value;
                        const authorized_phone_number = row.querySelector('.authorized-number').value;
                        const authorized_designation = row.querySelector('.authorized-designation').value;
                        const authorized_pan_number = row.querySelector('.authorized-pannumber').value;
                        const authorized_aadhaar_number = row.querySelector('.authorized-aadhaarnumber').value;

                        authorizedUsers.push({
                            authorized_name: authorized_name,
                            authorized_email: authorized_email,
                            authorized_phone_number: authorized_phone_number,
                            authorized_designation: authorized_designation,
                            authorized_pan_number: authorized_pan_number,
                            authorized_aadhaar_number: authorized_aadhaar_number
                        });
                    });

                    formData.form2 = {
                        stakeholders: stakeholders,
                        authorizedUsers: authorizedUsers,
                        copiedStakeholders: formData.form2.copiedStakeholders || []
                    };
                } else {
                    // Regular form handling
                    formData[formId] = {};
                    const inputs = form.querySelectorAll('input, select, textarea');

                    inputs.forEach(input => {
                        if (input.type !== 'button' && input.type !== 'submit') {
                            if (input.type === 'file') {
                                // For files, we just store the file name (not the actual file)
                                formData[formId][input.id] = input.files.length > 0 ? input.files[0].name : '';
                            } else {
                                formData[formId][input.id] = input.value;
                            }
                        }
                    });
                }
            }

           
            // Load saved data into form fields when page loads
            function loadSavedData() {
                // Form 1 data
                if (formData.form1) {
                    for (const [key, value] of Object.entries(formData.form1)) {
                        const element = document.getElementById(key);
                        if (element) {
                            element.value = value;
                        }
                    }
                }

                // Form 2 data - stakeholders
                if (formData.form2 && formData.form2.stakeholders && formData.form2.stakeholders.length > 0) {
                    const dynamicForm = document.getElementById('dynamicForm');
                    dynamicForm.innerHTML = ''; // Clear existing rows

                    formData.form2.stakeholders.forEach((stakeholder, index) => {
                        const row = document.createElement('div');
                        row.className = 'form-row';
                        const rowId = `stakeholder-${index}`;

                        row.innerHTML = `
                        <div class="form-group">
                            <label for="${rowId}-name">Full Name</label>
                            <input type="text" id="${rowId}-name" name="name" class="form-control stakeholder-name" placeholder="Enter Your Full Name" value="${stakeholder.name || ''}" />
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-email">Email</label>
                            <input type="email" id="${rowId}-email" name="email" class="form-control stakeholder-email" placeholder="Enter your Email" value="${stakeholder.email || ''}" />
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-phone">Phone Number</label>
                            <input type="text" id="${rowId}-phone" name="phone_number" class="form-control stakeholder-number" placeholder="Enter your Phone Number" value="${stakeholder.phone_number || ''}" />
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-designation">Designation</label>
                            <select name="designation" class="form-control stakeholder-designation" id="${rowId}-designation">
                                <option value="">Select</option>
                                <option value="owner" ${stakeholder.designation === 'owner' ? 'selected' : ''}>Owner</option>
                                <option value="director" ${stakeholder.designation === 'director' ? 'selected' : ''}>Director</option>
                                <option value="partner" ${stakeholder.designation === 'partner' ? 'selected' : ''}>Partner</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-pan">Pan Number</label>
                            <input name="pan_number" type="text" class="form-control stakeholder-pannumber" id="${rowId}-pan" placeholder="Enter your Pan Number" value="${stakeholder.pan_number || ''}" />
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-aadhaar">Aadhaar Number</label>
                            <input type="text" name="aadhaar_number" class="form-control stakeholder-aadhaarnumber" id="${rowId}-aadhaar" placeholder="Enter your Aadhaar Number" value="${stakeholder.aadhaar_number || ''}" />
                        </div>
                        <div class="btn-group-custom">
                            <button type="button" class="btn btn-success action-btn" onclick="addStakeholderRow()">+</button>
                            <button type="button" class="btn btn-danger action-btn" onclick="deleteStakeholderRow(this)" ${index === 0 ? 'style="display: none;"' : ''}>−</button>
                        </div>
                    `;

                        dynamicForm.appendChild(row);
                        setupStakeholderValidation(row);
                    });
                } else {
                    // Initialize with one empty stakeholder row if none exists
                    const dynamicForm = document.getElementById('dynamicForm');
                    const rowId = 'stakeholder-initial';

                    dynamicForm.innerHTML = `
                    <div class="form-row">
                        <div class="form-group">
                            <label for="${rowId}-name">Full Name</label>
                            <input type="text" id="${rowId}-name" name="name" placeholder="Ex: Tarun Dhiman" class="form-control stakeholder-name" />
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-email">Email</label>
                            <input type="email" id="${rowId}-email" name="email" placeholder="Ex: tarun.dhiman@gmail.com" class="form-control stakeholder-email" />
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-phone">Phone Number</label>
                            <input type="text" id="${rowId}-phone" name="phone_number" placeholder="Enter your Phone Number" class="form-control stakeholder-number" />
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-designation">Designation</label>
                            <select name="designation" class="form-control stakeholder-designation" id="${rowId}-designation">
                                <option value="">Select</option>
                                <option value="owner">Owner</option>
                                <option value="director">Director</option>
                                <option value="partner">Partner</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-pan">Pan Number</label>
                            <input type="text" name="pan_number" class="form-control stakeholder-pannumber" id="${rowId}-pan" placeholder="Enter Your Pan Number" />
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-aadhaar">Aadhaar Number</label>
                            <input type="text" name="aadhaar_number" class="form-control stakeholder-aadhaarnumber" id="${rowId}-aadhaar" placeholder="Enter Your Aadhaar Number" />
                        </div>
                        <div class="btn-group-custom">
                            <button type="button" class="btn btn-success action-btn" onclick="addStakeholderRow()">+</button>
                            <button type="button" class="btn btn-danger action-btn" onclick="deleteStakeholderRow(this)" style="display: none;">−</button>
                        </div>
                    </div>
                `;
                    // Setup validation for the initial row
                    const initialRow = dynamicForm.querySelector('.form-row');
                    if (initialRow) {
                        setupStakeholderValidation(initialRow);
                    }
                }

                // Form 2 data - authorized users
                if (formData.form2 && formData.form2.authorizedUsers && formData.form2.authorizedUsers.length > 0) {
                    const authorizedForm = document.getElementById('authorizedForm');
                    authorizedForm.innerHTML = ''; // Clear existing rows

                    formData.form2.authorizedUsers.forEach((user, index) => {
                        const row = document.createElement('div');
                        row.className = 'form-row';
                        const rowId = `authorized-${index}`;

                        // Map designation for authorized user
                        let authorizedDesignation = user.designation;
                        if (user.designation === 'partner') {
                            authorizedDesignation = 'authorized person';
                        }

                        row.innerHTML = `
                        <div class="form-group">
                            <label for="${rowId}-name">Full Name</label>
                            <input type="text" name="authorized_name" class="form-control authorized-name" id="${rowId}-name" placeholder="Ex: Tarun Dhiman" value="${user.name || ''}" />
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-email">Email</label>
                            <input type="email" name="authorized_email" class="form-control authorized-email" id="${rowId}-email" placeholder="Ex: tarun.dhiman@gmail.com" value="${user.email || ''}" />
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-phone">Phone Number</label>
                            <input type="text" name="authorized_phone_number" class="form-control authorized-number" id="${rowId}-phone" placeholder="Enter your Phone Number" value="${user.number || ''}" />
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-designation">Designation</label>
                            <select name="authorized_designation" class="form-control authorized-designation" id="${rowId}-designation">
                                <option value="">Select</option>
                                <option value="owner" ${authorizedDesignation === 'owner' ? 'selected' : ''}>Owner</option>
                                <option value="director" ${authorizedDesignation === 'director' ? 'selected' : ''}>Director</option>
                                <option value="admin" ${authorizedDesignation === 'admin' ? 'selected' : ''}>Admin</option>
                                <option value="authorized person" ${authorizedDesignation === 'authorized person' ? 'selected' : ''}>Authorized Person</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-pan">Pan Number</label>
                            <input type="text" name="authorized_pan_number" class="form-control authorized-pannumber" id="${rowId}-pan" placeholder="Enter Your Pan Number" value="${user.pannumber || ''}" />
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-aadhaar">Aadhaar Number</label>
                            <input type="text" name="authorized_aadhaar_number" class="form-control authorized-aadhaarnumber" id="${rowId}-aadhaar" placeholder="Enter Your Aadhaar Number" value="${user.aadhaarnumber || ''}" />
                        </div>
                        <div class="btn-group-custom">
                            <button type="button" class="btn btn-success action-btn" onclick="addAuthorizedRow()">+</button>
                            <button type="button" class="btn btn-danger action-btn" onclick="deleteAuthorizedRow(this)" ${index === 0 ? 'style="display: none;"' : ''}>−</button>
                        </div>
                    `;

                        authorizedForm.appendChild(row);
                        setupAuthorizedValidation(row);
                    });
                } else {
                    // Initialize with one empty authorized user row if none exists
                    const authorizedForm = document.getElementById('authorizedForm');
                    const rowId = 'authorized-initial';

                    authorizedForm.innerHTML = `
                    <div class="form-row">
                        <div class="form-group">
                            <label for="${rowId}-name">Full Name</label>
                            <input name="authorized_name" type="text" id="${rowId}-name" placeholder="Ex: Tarun Dhiman" class="form-control authorized-name" />
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-email">Email</label>
                            <input name="authorized_email" type="email" id="${rowId}-email" placeholder="Ex: tarun.dhiman@gmail.com" class="form-control authorized-email" />
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-phone">Phone Number</label>
                            <input type="text" name="authorized_phone_number" id="${rowId}-phone" placeholder="Enter your Phone Number" class="form-control authorized-number" />
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-designation">Designation</label>
                            <select name="authorized_designation" class="form-control authorized-designation" id="${rowId}-designation">
                                <option value="">Select</option>
                                <option value="owner">Owner</option>
                                <option value="director">Director</option>
                                <option value="admin">Admin</option>
                                <option value="authorized person">Authorized Person</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-pan">Pan Number</label>
                            <input type="text" name="authorized_pan_number" class="form-control authorized-pannumber" id="${rowId}-pan" placeholder="Enter Your Pan Number" />
                        </div>
                        <div class="form-group">
                            <label for="${rowId}-aadhaar">Aadhaar Number</label>
                            <input type="text" name="authorized_aadhaar_number" class="form-control authorized-aadhaarnumber" id="${rowId}-aadhaar" placeholder="Enter Your Aadhaar Number" />
                        </div>
                        <div class="btn-group-custom">
                            <button type="button" class="btn btn-success action-btn" onclick="addAuthorizedRow()">+</button>
                            <button type="button" class="btn btn-danger action-btn" onclick="deleteAuthorizedRow(this)" style="display: none;">−</button>
                        </div>
                    </div>
                `;
                    // Setup validation for the initial row
                    const initialRow = authorizedForm.querySelector('.form-row');
                    if (initialRow) {
                        setupAuthorizedValidation(initialRow);
                    }
                }

                // Form 3 data
                if (formData.form3) {
                    for (const [key, value] of Object.entries(formData.form3)) {
                        const element = document.getElementById(key);
                        if (element) {
                            element.value = value;
                        }
                    }
                }

                // Form 4 data
                if (formData.form4) {
                    for (const [key, value] of Object.entries(formData.form4)) {
                        const element = document.getElementById(key);
                        if (element) {
                            if (key === 'termsAgreement') {
                                element.checked = value === true || value === 'true' || value === 1;
                            } else {
                                element.value = value;
                            }
                        }
                    }
                }

                // Update stakeholder checkboxes
                updateStakeholderCheckboxes();

                // Update delete button visibility for authorized rows
                updateAuthorizedDeleteButtons();
            }

            // Function to update stakeholder checkboxes
            function updateStakeholderCheckboxes() {
                const checkboxContainer = document.getElementById('stakeholderCheckboxes');
                const stakeholderRows = document.querySelectorAll('#dynamicForm .form-row');

                checkboxContainer.innerHTML = '';

                stakeholderRows.forEach((row, index) => {
                    const nameInput = row.querySelector('.stakeholder-name');
                    const name = nameInput ? nameInput.value.trim() : '';
                    const displayName = name || `Stakeholder ${index + 1}`;

                    // Check if this stakeholder has been copied before
                    const isCopied = formData.form2.copiedStakeholders &&
                        formData.form2.copiedStakeholders.includes(index);

                    const checkboxItem = document.createElement('div');
                    checkboxItem.className = 'stakeholder-checkbox-item';

                    checkboxItem.innerHTML = `
                    <input type="checkbox" id="copyStakeholder${index}"
                        onchange="handleStakeholderCopy(${index}, this.checked)"
                        ${isCopied ? 'disabled data-copied="true"' : ''}>
                    <label for="copyStakeholder${index}" ${isCopied ? 'style="opacity: 0.6; text-decoration: line-through;"' : ''}>${displayName}</label>
                `;

                    // If copied, make sure checkbox is checked
                    if (isCopied) {
                        setTimeout(() => {
                            const checkbox = document.getElementById(`copyStakeholder${index}`);
                            if (checkbox) {
                                checkbox.checked = true;
                            }
                        }, 0);
                    }

                    checkboxContainer.appendChild(checkboxItem);
                });

                // If no stakeholders, show a message
                if (stakeholderRows.length === 0) {
                    checkboxContainer.innerHTML =
                        '<div style="font-style: italic;">No stakeholders available to copy</div>';
                }
            }

            // Function to handle stakeholder copy
            function handleStakeholderCopy(stakeholderIndex, isChecked) {
                if (!isChecked) return;

                const stakeholderRows = document.querySelectorAll('#dynamicForm .form-row');
                const stakeholderRow = stakeholderRows[stakeholderIndex];
                const checkbox = document.getElementById(`copyStakeholder${stakeholderIndex}`);

                if (!stakeholderRow) return;

                // Check if this stakeholder has already been copied
                if (checkbox.getAttribute('data-copied') === 'true') {
                    // If already copied, just keep the checkbox checked but don't copy again
                    checkbox.checked = true; // Ensure it stays checked
                    return;
                }

                // Get stakeholder data
                const stakeholderData = {
                    name: stakeholderRow.querySelector('.stakeholder-name').value,
                    email: stakeholderRow.querySelector('.stakeholder-email').value,
                    number: stakeholderRow.querySelector('.stakeholder-number').value,
                    designation: stakeholderRow.querySelector('.stakeholder-designation').value,
                    pannumber: stakeholderRow.querySelector('.stakeholder-pannumber').value,
                    aadhaarnumber: stakeholderRow.querySelector('.stakeholder-aadhaarnumber').value
                };

                // Remove the first blank authorized row if it exists and is empty
                const authorizedRows = document.querySelectorAll('#authorizedForm .form-row');
                const firstRow = authorizedRows[0];

                if (firstRow && isRowEmpty(firstRow)) {
                    // Remove the first blank row
                    firstRow.remove();
                }

                // Add new authorized user row with copied data
                addAuthorizedRowWithData(stakeholderData);

                // Mark this stakeholder as copied in our formData
                if (!formData.form2.copiedStakeholders) {
                    formData.form2.copiedStakeholders = [];
                }
                formData.form2.copiedStakeholders.push(stakeholderIndex);

                // Update the checkbox appearance
                updateCheckboxAppearance(checkbox, true);

                // Disable the checkbox to prevent multiple copies
                checkbox.disabled = true;

                // Change the label style to indicate it's been used
                const label = checkbox.nextElementSibling;
                if (label) {
                    label.style.opacity = '0.6';
                    label.style.textDecoration = 'line-through';
                }

                // Uncheck the checkbox
                document.getElementById(`copyStakeholder${stakeholderIndex}`).checked = false;
            }

            // Function to update checkbox appearance
            function updateCheckboxAppearance(checkbox, isCopied) {
                if (isCopied) {
                    checkbox.disabled = true;
                    checkbox.setAttribute('data-copied', 'true');

                    const label = checkbox.nextElementSibling;
                    if (label) {
                        label.style.opacity = '0.6';
                        label.style.textDecoration = 'line-through';
                    }
                }
            }

            // Function to check if a row is empty
            function isRowEmpty(row) {
                const name = row.querySelector('.authorized-name').value;
                const email = row.querySelector('.authorized-email').value;
                const number = row.querySelector('.authorized-number').value;
                const designation = row.querySelector('.authorized-designation').value;
                const pannumber = row.querySelector('.authorized-pannumber').value;
                const aadhaarnumber = row.querySelector('.authorized-aadhaarnumber').value;

                return !name && !email && !number && !designation && !pannumber && !aadhaarnumber;
            }

            // Function to add authorized row with data
            function addAuthorizedRowWithData(data = {}) {
                const form = document.getElementById("authorizedForm");
                const rowCount = form.querySelectorAll('.form-row').length;
                const rowId = `authorized-${rowCount + 1}`;

                const row = document.createElement("div");
                row.className = "form-row";
                row.id = rowId;

                // Map stakeholder designation to authorized designation
                let authorizedDesignation = data.designation;
                if (data.designation === 'partner') {
                    authorizedDesignation = 'authorized person';
                }

                row.innerHTML = `
                    <div class="form-group">
                        <label for="${rowId}-name">Full Name</label>
                        <input type="text" id="${rowId}-name" name="authorized_name" placeholder="Ex: Tarun Dhiman" class="form-control authorized-name" value="${data.name || ''}" />
                    </div>
                    <div class="form-group">
                        <label for="${rowId}-email">Email</label>
                        <input type="email" id="${rowId}-email" name="authorized_email" placeholder="Ex: tarun.dhiman@gmail.com" class="form-control authorized-email" value="${data.email || ''}" />
                    </div>
                    <div class="form-group">
                        <label for="${rowId}-phone">Phone Number</label>
                        <input type="text" id="${rowId}-phone" name="authorized_phone_number" placeholder="Enter your Phone Number" class="form-control authorized-number" value="${data.number || ''}" />
                    </div>
                    <div class="form-group">
                        <label for="${rowId}-designation">Designation</label>
                        <select id="${rowId}-designation" name="authorized_designation" class="form-control authorized-designation">
                            <option value="">Select</option>
                            <option value="owner" ${authorizedDesignation === 'owner' ? 'selected' : ''}>Owner</option>
                            <option value="director" ${authorizedDesignation === 'director' ? 'selected' : ''}>Director</option>
                            <option value="admin" ${authorizedDesignation === 'admin' ? 'selected' : ''}>Admin</option>
                            <option value="authorized person" ${authorizedDesignation === 'authorized person' ? 'selected' : ''}>Authorized Person</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="${rowId}-pan">PAN Number</label>
                        <input type="text" id="${rowId}-pan" name="authorized_pan_number" class="form-control authorized-pannumber" placeholder="Enter PAN Number" value="${data.pannumber || ''}" />
                    </div>
                    <div class="form-group">
                        <label for="${rowId}-aadhaar">Aadhaar Number</label>
                        <input type="text" id="${rowId}-aadhaar" name="authorized_aadhaar_number" class="form-control authorized-aadhaarnumber" placeholder="Enter Aadhaar Number" value="${data.aadhaarnumber || ''}" />
                    </div>
                    <div class="btn-group-custom">
                        <button type="button" class="btn btn-success action-btn" onclick="addAuthorizedRow()">+</button>
                        <button type="button" class="btn btn-danger action-btn" onclick="deleteAuthorizedRow(this)">−</button>
                    </div>
                    `;

                form.appendChild(row);
                setupAuthorizedValidation(row);
                updateAuthorizedDeleteButtons();
            }

            // Function to add empty authorized row
            function addAuthorizedRow() {
                const form = document.getElementById("authorizedForm");
                const row = document.createElement("div");
                row.className = "form-row";

                row.innerHTML = `
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="authorized_name" placeholder="Ex:Tarun Dhiman|Tarun Singh Dhiman" class="form-control authorized-name" />
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="authorized_email" placeholder="Ex:tarun.dhiman@gmail.com" class="form-control authorized-email" />
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="authorized_phone_number" placeholder="Enter your Phone Number" class="form-control authorized-number" />
                    </div>
                    <div class="form-group">
                        <label>Designation</label>
                        <select name="authorized_designation" class="form-control authorized-designation">
                            <option value="">Select</option>
                            <option value="owner">Owner</option>
                            <option value="director">Director</option>
                            <option value="admin">Admin</option>
                            <option value="authorized person">Authorized Person</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pan Number</label>
                        <input name="authorized_pan_number"  type="text" class="form-control authorized-pannumber" placeholder="Enter Your Pan Number" />
                    </div>
                    <div class="form-group">
                        <label>Aadhaar Number</label>
                        <input name="authorized_aadhaar_number" type="text" class="form-control authorized-aadhaarnumber" placeholder="Enter Your Aadhaar Number" />
                    </div>
                    <div class="btn-group-custom">
                        <button type="button" class="btn btn-success action-btn" onclick="addAuthorizedRow()">+</button>
                        <button type="button" class="btn btn-danger action-btn" onclick="deleteAuthorizedRow(this)">−</button>
                    </div>
                `;

                form.appendChild(row);

                // Update delete button visibility
                updateAuthorizedDeleteButtons();
            }

            // Function to update delete button visibility for authorized rows
            function updateAuthorizedDeleteButtons() {
                const authorizedRows = document.querySelectorAll('#authorizedForm .form-row');
                authorizedRows.forEach((row, index) => {
                    const deleteButton = row.querySelector('.btn-danger');
                    if (deleteButton) {
                        // Hide delete button for the first row, show for others
                        deleteButton.style.display = index === 0 ? 'none' : 'block';
                    }
                });
            }

            // Call loadSavedData when page loads
            // Initialize real-time validation when page loads
            document.addEventListener('DOMContentLoaded', function() {
                loadSavedData();
                setupRealTimeValidation();

                // Initialize all upload areas
                initUploadArea('registrationUploadArea', 'registrationDocument');
                initUploadArea('panUploadArea', 'panDocument');
                initUploadArea('gstUploadArea', 'gstDocument');
                initUploadArea('logoUploadArea', 'instituteLogo');
                initUploadArea('imageUploadArea', 'instituteImage');
                initUploadArea('chequeUploadArea', 'cancelledCheque');
            });

            // Prepare all form data for submission - FIXED VERSION
            function prepareFormData() {
                const submissionData = new FormData();

                // Form 1 data
                const form1Data = {
                    name: document.getElementById('instituteName')?.value || '',
                    type: document.getElementById('instituteType')?.value || '',
                    affiliation: document.getElementById('affiliationType')?.value || '',
                    registration_type: document.getElementById('registrationType')?.value || '',
                    email: document.getElementById('email')?.value || '',
                    contact_number: document.getElementById('contactNumber')?.value || '',
                    address_line_1: document.getElementById('addressLine1')?.value || '',
                    address_line_2: document.getElementById('addressLine2')?.value || '',
                    state: document.getElementById('state')?.value || '',
                    city: document.getElementById('city')?.value || '',
                    pincode: document.getElementById('pincode')?.value || '',
                    establishment_date: document.getElementById('establishmentDate')?.value || '',
                    website: document.getElementById('website')?.value || '',
                };

                // Form 2 data - stakeholders
                const stakeholders = [];
                document.querySelectorAll('#dynamicForm .form-row').forEach((row, index) => {
                    const stakeholder = {
                        name: row.querySelector('.stakeholder-name')?.value || '',
                        email: row.querySelector('.stakeholder-email')?.value || '',
                        phone_number: row.querySelector('.stakeholder-number')?.value || '',
                        designation: row.querySelector('.stakeholder-designation')?.value || '',
                        pan_number: row.querySelector('.stakeholder-pannumber')?.value || '',
                        aadhaar_number: row.querySelector('.stakeholder-aadhaarnumber')?.value || '',
                    };

                    if (stakeholder.name.trim()) {
                        stakeholders.push(stakeholder);
                    }
                });

                // Form 2 data - authorized users
                const authorizedUsers = [];
                document.querySelectorAll('#authorizedForm .form-row').forEach((row, index) => {
                    const authorizedUser = {
                        authorized_name: row.querySelector('.authorized-name')?.value || '',
                        authorized_email: row.querySelector('.authorized-email')?.value || '',
                        authorized_phone_number: row.querySelector('.authorized-number')?.value || '',
                        authorized_designation: row.querySelector('.authorized-designation')?.value || '',
                        authorized_pan_number: row.querySelector('.authorized-pannumber')?.value || '',
                        authorized_aadhaar_number: row.querySelector('.authorized-aadhaarnumber')?.value || '',
                    };

                    if (authorizedUser.authorized_name.trim()) {
                        authorizedUsers.push(authorizedUser);
                    }
                });

                // Form 3 data
                const form3Data = {
                    registration_number: document.getElementById('registrationNumber')?.value || '',
                    pan_number: document.getElementById('institutePanNumber')?.value || '',
                    gst_number: document.getElementById('gstNumber')?.value || '',
                };

                // Form 4 data
                const form4Data = {
                    beneficiary_name: document.getElementById('beneficiaryName')?.value || '',
                    account_number: document.getElementById('accountNumber')?.value || '',
                    bank_name: document.getElementById('bankName')?.value || '',
                    ifsc_code: document.getElementById('ifscCode')?.value || '',
                    account_type: document.getElementById('accountType')?.value || '',
                    terms_agreed: document.getElementById('termsAgreement')?.checked ? true : false,
                };

                // Add form data to FormData
                Object.keys(form1Data).forEach(key => {
                    submissionData.append(`form1[${key}]`, form1Data[key]);
                });

                // Add stakeholders
                stakeholders.forEach((stakeholder, index) => {
                    Object.keys(stakeholder).forEach(key => {
                        submissionData.append(`form2[stakeholders][${index}][${key}]`, stakeholder[key]);
                    });
                });

                // Add authorized users
                authorizedUsers.forEach((user, index) => {
                    Object.keys(user).forEach(key => {
                        submissionData.append(`form2[authorizedUsers][${index}][${key}]`, user[key]);
                    });
                });

                // Add form3 data
                Object.keys(form3Data).forEach(key => {
                    submissionData.append(`form3[${key}]`, form3Data[key]);
                });

                // Add form4 data
                Object.keys(form4Data).forEach(key => {
                    submissionData.append(`form4[${key}]`, form4Data[key]);
                });

                // Add institute files
                const instituteFiles = [{
                        name: 'registration_document_path',
                        selector: '[name="registration_document_path"]'
                    },
                    {
                        name: 'pan_document_path',
                        selector: '[name="pan_document_path"]'
                    },
                    {
                        name: 'gst_document_path',
                        selector: '[name="gst_document_path"]'
                    },
                    {
                        name: 'logo_path',
                        selector: '[name="logo_path"]'
                    },
                    {
                        name: 'institute_image_path',
                        selector: '[name="institute_image_path"]'
                    }
                ];

                instituteFiles.forEach(fileConfig => {
                    const fileInput = document.querySelector(fileConfig.selector);
                    if (fileInput && fileInput.files[0]) {
                        submissionData.append(fileConfig.name, fileInput.files[0]);
                    }
                });

                // DEBUG: Log all file inputs available
                const allFileInputs = document.querySelectorAll('input[type="file"]');
                allFileInputs.forEach(input => {
                    console.log(`File input: ${input.name} - Files:`, input.files.length > 0 ? input.files[0].name :
                        'No file');
                });

                // Add stakeholder files
                for (let i = 0; i < stakeholders.length; i++) {
                    const aadhaarFrontInput = document.querySelector(`input[name="stakeholder${i}_aadhaar_front"]`);
                    const aadhaarBackInput = document.querySelector(`input[name="stakeholder${i}_aadhaar_back"]`);
                    const panInput = document.querySelector(`input[name="stakeholder${i}_pan"]`);

                    if (aadhaarFrontInput && aadhaarFrontInput.files[0]) {
                        submissionData.append(`stakeholder${i}_aadhaar_front`, aadhaarFrontInput.files[0]);
                    }
                    if (aadhaarBackInput && aadhaarBackInput.files[0]) {
                        submissionData.append(`stakeholder${i}_aadhaar_back`, aadhaarBackInput.files[0]);
                    }
                    if (panInput && panInput.files[0]) {
                        submissionData.append(`stakeholder${i}_pan`, panInput.files[0]);
                    }
                }

                // Add authorized user files
                for (let i = 0; i < authorizedUsers.length; i++) {
                    const aadhaarFrontInput = document.querySelector(`input[name="authorized${i}_aadhaar_front"]`);
                    const aadhaarBackInput = document.querySelector(`input[name="authorized${i}_aadhaar_back"]`);
                    const panInput = document.querySelector(`input[name="authorized${i}_pan"]`);

                    if (aadhaarFrontInput && aadhaarFrontInput.files[0]) {
                        submissionData.append(`authorized${i}_aadhaar_front`, aadhaarFrontInput.files[0]);
                    }
                    if (aadhaarBackInput && aadhaarBackInput.files[0]) {
                        submissionData.append(`authorized${i}_aadhaar_back`, aadhaarBackInput.files[0]);
                    }
                    if (panInput && panInput.files[0]) {
                        submissionData.append(`authorized${i}_pan`, panInput.files[0]);
                    }
                }
                // Add cancelled cheque
                const cancelledChequeInput = document.querySelector('[name="cancelled_cheque_path"]');
                if (cancelledChequeInput && cancelledChequeInput.files[0]) {
                    submissionData.append('cancelled_cheque_path', cancelledChequeInput.files[0]);
                }

                // Debug: Log what's being sent
                for (let pair of submissionData.entries()) {
                    if (pair[1] instanceof File) {
                        console.log(pair[0] + ': FILE - ' + pair[1].name);
                    } else {
                        console.log(pair[0] + ': ', pair[1]);
                    }
                }

                return submissionData;
            }

            // Function to clear all forms and reset the UI
            function clearAllForms() {
                // Clear form1 inputs
                const form1Inputs = document.querySelectorAll('#form1 input, #form1 select');
                form1Inputs.forEach(input => {
                    if (input.type !== 'submit' && input.type !== 'button') {
                        input.value = '';
                    }
                });

                // Clear form2 inputs
                const form2Inputs = document.querySelectorAll('#form2 input, #form2 select');
                form2Inputs.forEach(input => {
                    if (input.type === 'file') {
                        input.value = '';
                    } else if (input.type !== 'submit' && input.type !== 'button' && input.type !== 'checkbox') {
                        input.value = '';
                    }
                });

                // Clear form3 inputs
                const form3Inputs = document.querySelectorAll('#form3 input, #form3 select');
                form3Inputs.forEach(input => {
                    if (input.type === 'file') {
                        input.value = '';
                    } else if (input.type !== 'submit' && input.type !== 'button') {
                        input.value = '';
                    }
                });

                // Clear form4 inputs
                const form4Inputs = document.querySelectorAll('#form4 input, #form4 select');
                form4Inputs.forEach(input => {
                    if (input.type === 'file') {
                        input.value = '';
                    } else if (input.type !== 'submit' && input.type !== 'button') {
                        input.value = '';
                    }
                });


                // Reset stakeholder form to one row
                const dynamicForm = document.getElementById('dynamicForm');
                dynamicForm.innerHTML = `
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name" placeholder="Ex:Tarun Dhiman|Tarun Singh Dhiman" class="form-control stakeholder-name" oninput="updateStakeholderCheckboxes()" />
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="Ex:tarun.dhiman@gmail.com" class="form-control stakeholder-email" />
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone_number" placeholder="Enter your Phone Number" class="form-control stakeholder-number" />
                        </div>
                        <div class="form-group">
                            <label>Designation</label>
                            <select name="designation" class="form-control stakeholder-designation">
                                <option value="">Select</option>
                                <option value="owner">Owner</option>
                                <option value="director">Director</option>
                                <option value="partner">Partner</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Pan Number</label>
                            <input type="text" name="pan_number" class="form-control stakeholder-pannumber" placeholder="Enter Your Pan Number" />
                        </div>
                        <div class="form-group">
                            <label>Aadhaar Number</label>
                            <input type="text" name="aadhaar_number" class="form-control stakeholder-aadhaarnumber" placeholder="Enter Your Aadhaar Number" />
                        </div>
                        <div class="btn-group-custom">
                            <button type="button" class="btn btn-success action-btn" onclick="addStakeholderRow()">+</button>
                            <button type="button" class="btn btn-danger action-btn" onclick="deleteStakeholderRow(this)" style="display: none;">−</button>
                        </div>
                    </div>
                `;

                // Reset authorized user form to one row
                const authorizedForm = document.getElementById('authorizedForm');
                authorizedForm.innerHTML = `
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="authorized_name" placeholder="Ex:Tarun Dhiman|Tarun Singh Dhiman" class="form-control authorized-name" />
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="authorized_email" placeholder="Ex:tarun.dhiman@gmail.com" class="form-control authorized-email" />
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="authorized_phone_number" placeholder="Enter your Phone Number" class="form-control authorized-number" />
                        </div>
                        <div class="form-group">
                            <label>Designation</label>
                            <select name="authorized_designation" class="form-control authorized-designation">
                                <option value="">Select</option>
                                <option value="owner">Owner</option>
                                <option value="director">Director</option>
                                <option value="admin">Admin</option>
                                <option value="authorized person">Authorized Person</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Pan Number</label>
                            <input type="text" name="authorized_pan_number" class="form-control authorized-pannumber" placeholder="Enter Your Pan Number" />
                        </div>
                        <div class="form-group">
                            <label>Aadhaar Number</label>
                            <input type="text" name="authorized_aadhaar_number" class="form-control authorized-aadhaarnumber" placeholder="Enter Your Aadhaar Number" />
                        </div>
                        <div class="btn-group-custom">
                            <button type="button" class="btn btn-success action-btn" onclick="addAuthorizedRow()">+</button>
                            <button type="button" class="btn btn-danger action-btn" onclick="deleteAuthorizedRow(this)" style="display: none;">−</button>
                        </div>
                    </div>
                `;

                // Uncheck terms checkbox
                document.getElementById('termsAgreement').checked = false;

                // Reset timeline
                document.querySelectorAll('.timeline-step').forEach(step => {
                    step.classList.remove('active', 'completed');
                });
                document.getElementById('step1').classList.add('active');
                document.getElementById('timelineProgress').style.width = '0%';

                // Show form1 and hide others
                document.getElementById('form1').style.display = 'block';
                document.getElementById('form2').style.display = 'none';
                document.getElementById('form3').style.display = 'none';
                document.getElementById('form4').style.display = 'none';
                document.getElementById('successMessage').style.display = 'none';

                // Clear form data
                formData = {
                    form1: {},
                    form2: {
                        stakeholders: [],
                        authorizedUsers: []
                    },
                    form3: {},
                    form4: {}
                };

                // Update checkboxes
                updateStakeholderCheckboxes();
            }

            // Timeline and form navigation
            const steps = {
                step1: document.getElementById("step1"),
                step2: document.getElementById("step2"),
                step3: document.getElementById("step3"),
                step4: document.getElementById("step4"),
            };

            const forms = {
                form1: document.getElementById("form1"),
                form2: document.getElementById("form2"),
                form3: document.getElementById("form3"),
                form4: document.getElementById("form4"),
            };

            const progressBar = document.getElementById("timelineProgress");
            const successMessage = document.getElementById("successMessage");

            function updateTimeline(currentStep, nextStep, progressPercent) {
                steps[currentStep].classList.remove("active");
                steps[currentStep].classList.add("completed");
                steps[nextStep].classList.add("active");
                progressBar.style.width = progressPercent;
            }

            function backStep(currentStep, previousStep, progressPercent) {
                steps[currentStep].classList.remove("active");
                steps[previousStep].classList.remove("completed");
                steps[previousStep].classList.add("active");
                progressBar.style.width = progressPercent;
            }

            // Form submission handlers
            forms.form1.addEventListener("submit", function(e) {
                e.preventDefault();

                if (!formValidators.validateForm1()) {
                    // Scroll to first error
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstError.focus();
                    }
                    return;
                }

                saveFormData('form1');
                forms.form1.style.display = "none";
                forms.form2.style.display = "block";
                updateTimeline("step1", "step2", "33.33%");
                updateStakeholderCheckboxes();
            });

            forms.form2.addEventListener("submit", function(e) {
                e.preventDefault();

                

                if (!formValidators.validateForm2()) {

                    // Find and focus on first error
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstError.focus();
                    } else {
                        // Show a generic error message
                        alert('Please fill all required fields correctly in Stakeholder and Authorized User details.');
                    }
                    return;
                }

                saveFormData('form2');
                // Debug the saved data
                forms.form2.style.display = "none";
                forms.form3.style.display = "block";
                updateTimeline("step2", "step3", "66.66%");
                generateDocumentSections();
            });

            forms.form3.addEventListener("submit", function(e) {
                e.preventDefault();

                if (!formValidators.validateForm3()) {
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstError.focus();
                    }
                    return;
                }

                saveFormData('form3');
                forms.form3.style.display = "none";
                forms.form4.style.display = "block";
                updateTimeline("step3", "step4", "100%");
            });

            forms.form4.addEventListener("submit", function(e) {
                e.preventDefault();

                if (!formValidators.validateForm4()) {
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstError.focus();
                    }
                    return;
                }

                saveFormData('form4');
                submitAllForms();
            });

        
            // Function to detect if a person exists in both stakeholders and authorized users
            function findDuplicatePersons() {

                const duplicates = [];

                if (!formData.form2.stakeholders || !formData.form2.authorizedUsers) {
                    return duplicates;
                }


                formData.form2.stakeholders.forEach((stakeholder, sIndex) => {
                    formData.form2.authorizedUsers.forEach((authorized, aIndex) => {
                        // Get the names for comparison
                        const stakeholderName = stakeholder.name ? stakeholder.name.toLowerCase().trim() : '';
                        const authorizedName = authorized.authorized_name ? authorized.authorized_name
                            .toLowerCase().trim() : '';

                        // Check if it's the same person (primarily by name)
                        const isSamePerson = stakeholderName && authorizedName && stakeholderName ===
                            authorizedName;

                        if (isSamePerson) {
                            duplicates.push({
                                stakeholderIndex: sIndex,
                                authorizedIndex: aIndex,
                                person: {
                                    name: stakeholder.name || authorized.authorized_name,
                                    email: stakeholder.email || authorized.authorized_email,
                                    number: stakeholder.phone_number || authorized
                                        .authorized_phone_number,
                                    designation: stakeholder.designation || authorized
                                        .authorized_designation,
                                    pannumber: stakeholder.pan_number || authorized
                                        .authorized_pan_number,
                                    aadhaarnumber: stakeholder.aadhaar_number || authorized
                                        .authorized_aadhaar_number
                                }
                            });
                        } else {
                            console.log(
                                `❌ NOT DUPLICATE: Stakeholder ${sIndex} (${stakeholder.name}) vs Authorized ${aIndex} (${authorized.authorized_name})`
                            );
                        }
                    });
                });

                return duplicates;
            }
            // Function to generate document sections in Form 3
            function generateDocumentSections() {

                // Clear existing dynamic sections
                document.getElementById('stakeholderDocumentsContainer').innerHTML = '';
                document.getElementById('authorizedDocumentsContainer').innerHTML = '';

                // Find all duplicate persons
                const duplicatePersons = findDuplicatePersons();

                // Track which indexes we've processed
                const processedStakeholders = new Set();
                const processedAuthorized = new Set();

                // 1. FIRST: Process all duplicates as merged sections
                duplicatePersons.forEach((duplicate, index) => {
                    const {
                        stakeholderIndex,
                        authorizedIndex,
                        person
                    } = duplicate;

                    createMergedDocumentSection(duplicate, index);

                    // Mark both as processed
                    processedStakeholders.add(stakeholderIndex);
                    processedAuthorized.add(authorizedIndex);
                });

                // 2. SECOND: Process stakeholders that are NOT duplicates
                if (formData.form2.stakeholders) {
                    formData.form2.stakeholders.forEach((stakeholder, index) => {
                        if (!processedStakeholders.has(index)) {
                            createStakeholderDocumentSection(stakeholder, index);
                            processedStakeholders.add(index);
                        } else {
                            console.log(`Skipping stakeholder (duplicate): ${stakeholder.name} (index: ${index})`);
                        }
                    });
                }

                console.log('=== PROCESSING REMAINING AUTHORIZED USERS ===');
                // 3. THIRD: Process authorized users that are NOT duplicates
                if (formData.form2.authorizedUsers) {
                    formData.form2.authorizedUsers.forEach((user, index) => {
                        if (!processedAuthorized.has(index)) {
                            createAuthorizedDocumentSection(user, index);
                            processedAuthorized.add(index);
                        } else {
                            console.log(
                                `Skipping authorized user (duplicate): ${user.authorized_name} (index: ${index})`);
                        }
                    });
                }
            }

            // Function to create a merged document section
            function createMergedDocumentSection(duplicate, index) {
                const {
                    person,
                    stakeholderIndex,
                    authorizedIndex
                } = duplicate;
                const mergedId = `merged${index}`;

                const sectionHtml = `
                    <div class="document-section merged-section" id="${mergedId}Section">
                        <div class="section-header">
                            <h5>${person.name || 'Merged Profile'}</h5>
                            <span class="document-person-type" style="background: #6f42c1;">Merged Profile (Stakeholder & Authorized User)</span>
                        </div>
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle"></i>
                            <strong>${person.name}</strong> appears as both a Stakeholder and Authorized User.
                            Please upload documents once for this merged profile.
                        </div>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="${mergedId}Name"
                                    value="${person.name || ''}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Designation</label>
                                <input type="text" class="form-control" id="${mergedId}Designation"
                                    value="${person.designation || ''}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Aadhaar Number</label>
                                <input type="text" class="form-control" id="${mergedId}AadhaarNumber"
                                    value="${person.aadhaar_number || ''}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">PAN Number</label>
                                <input type="text" class="form-control" id="${mergedId}PanNumber"
                                    value="${person.pan_number || ''}" readonly>
                            </div>
                            <div class="col-md-6">
                                <!-- Creative Upload for Aadhaar Front -->
                                <div class="upload-card">
                                    <div class="upload-header">
                                        <div class="upload-title">Aadhaar Front</div>
                                        <span class="upload-type">Required</span>
                                    </div>

                                    <div class="upload-area" id="${mergedId}AadhaarFrontArea">
                                        <i class="fas fa-id-card"></i>
                                        <div class="upload-text">
                                            <h4>Upload Aadhaar Front</h4>
                                            <p>JPG or PNG (Max 5MB)</p>
                                        </div>
                                        <button class="upload-btn">Select File</button>
                                        <input type="file" name="stakeholder${stakeholderIndex}_aadhaar_front" class="file-input" id="${mergedId}AadhaarFront" accept=".jpg,.jpeg,.png">
                                    </div>

                                    <div class="progress-bar">
                                        <div class="progress-fill"></div>
                                    </div>

                                    <div class="upload-status">
                                        <i class="fas fa-exclamation-circle status-icon status-pending"></i>
                                        <span class="status-text">No file uploaded</span>
                                    </div>

                                    <div class="preview-area">
                                        <div class="preview-header">
                                            <div class="preview-title">Uploaded File</div>
                                            <button class="change-file">Change File</button>
                                        </div>
                                        <div class="preview-container"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Creative Upload for Aadhaar Back -->
                                <div class="upload-card">
                                    <div class="upload-header">
                                        <div class="upload-title">Aadhaar Back</div>
                                        <span class="upload-type">Required</span>
                                    </div>

                                    <div class="upload-area" id="${mergedId}AadhaarBackArea">
                                        <i class="fas fa-id-card"></i>
                                        <div class="upload-text">
                                            <h4>Upload Aadhaar Back</h4>
                                            <p>JPG or PNG (Max 5MB)</p>
                                        </div>
                                        <button class="upload-btn">Select File</button>
                                        <input type="file" name="stakeholder${stakeholderIndex}_aadhaar_back" class="file-input" id="${mergedId}AadhaarBack" accept=".jpg,.jpeg,.png">
                                    </div>

                                    <div class="progress-bar">
                                        <div class="progress-fill"></div>
                                    </div>

                                    <div class="upload-status">
                                        <i class="fas fa-exclamation-circle status-icon status-pending"></i>
                                        <span class="status-text">No file uploaded</span>
                                    </div>

                                    <div class="preview-area">
                                        <div class="preview-header">
                                            <div class="preview-title">Uploaded File</div>
                                            <button class="change-file">Change File</button>
                                        </div>
                                        <div class="preview-container"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Creative Upload for PAN Document -->
                                <div class="upload-card">
                                    <div class="upload-header">
                                        <div class="upload-title">PAN Document</div>
                                        <span class="upload-type">Required</span>
                                    </div>

                                    <div class="upload-area" id="${mergedId}PanArea">
                                        <i class="fas fa-file-pdf"></i>
                                        <div class="upload-text">
                                            <h4>Upload PAN Document</h4>
                                            <p>PDF, JPG or PNG (Max 5MB)</p>
                                        </div>
                                        <button class="upload-btn">Select File</button>
                                        <input type="file" name="stakeholder${stakeholderIndex}_pan" class="file-input" id="${mergedId}Pan" accept=".jpg,.jpeg,.png,.pdf">
                                    </div>

                                    <div class="progress-bar">
                                        <div class="progress-fill"></div>
                                    </div>

                                    <div class="upload-status">
                                        <i class="fas fa-exclamation-circle status-icon status-pending"></i>
                                        <span class="status-text">No file uploaded</span>
                                    </div>

                                    <div class="preview-area">
                                        <div class="preview-header">
                                            <div class="preview-title">Uploaded File</div>
                                            <button class="change-file">Change File</button>
                                        </div>
                                        <div class="preview-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                document.getElementById('stakeholderDocumentsContainer').innerHTML += sectionHtml;

                // Initialize file upload handlers for this section
                initUploadArea(`${mergedId}AadhaarFrontArea`, `${mergedId}AadhaarFront`);
                initUploadArea(`${mergedId}AadhaarBackArea`, `${mergedId}AadhaarBack`);
                initUploadArea(`${mergedId}PanArea`, `${mergedId}Pan`);
            }

            // Function to create regular stakeholder document section
            function createStakeholderDocumentSection(stakeholder, index, isMerged = false) {
                const stakeholderId = `stakeholder${index}`;
                const sectionHtml = `
                    <div class="document-section" id="${stakeholderId}Section">
                        <div class="section-header">
                            <h5>Stakeholder Documents - ${stakeholder.name || 'Stakeholder ' + (index + 1)}</h5>
                            <span class="document-person-type">Stakeholder</span>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="${stakeholderId}Name"
                                    value="${stakeholder.name || ''}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Aadhaar Number</label>
                                <input type="text" class="form-control" id="${stakeholderId}AadhaarNumber"
                                    value="${stakeholder.aadhaar_number || ''}" readonly>
                            </div>
                            <div class="col-md-6">
                                <!-- Creative Upload for Aadhaar Front -->
                                <div class="upload-card">
                                    <div class="upload-header">
                                        <div class="upload-title">Aadhaar Front</div>
                                        <span class="upload-type">Required</span>
                                    </div>

                                    <div class="upload-area" id="${stakeholderId}AadhaarFrontArea">
                                        <i class="fas fa-id-card"></i>
                                        <div class="upload-text">
                                            <h4>Upload Aadhaar Front</h4>
                                            <p>JPG or PNG (Max 5MB)</p>
                                        </div>
                                        <button class="upload-btn">Select File</button>
                                        <input type="file" name="stakeholder${index}_aadhaar_front" class="file-input" id="${stakeholderId}AadhaarFront" accept=".jpg,.jpeg,.png">
                                    </div>

                                    <div class="progress-bar">
                                        <div class="progress-fill"></div>
                                    </div>

                                    <div class="upload-status">
                                        <i class="fas fa-exclamation-circle status-icon status-pending"></i>
                                        <span class="status-text">No file uploaded</span>
                                    </div>

                                    <div class="preview-area">
                                        <div class="preview-header">
                                            <div class="preview-title">Uploaded File</div>
                                            <button class="change-file">Change File</button>
                                        </div>
                                        <div class="preview-container"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Creative Upload for Aadhaar Back -->
                                <div class="upload-card">
                                    <div class="upload-header">
                                        <div class="upload-title">Aadhaar Back</div>
                                        <span class="upload-type">Required</span>
                                    </div>

                                    <div class="upload-area" id="${stakeholderId}AadhaarBackArea">
                                        <i class="fas fa-id-card"></i>
                                        <div class="upload-text">
                                            <h4>Upload Aadhaar Back</h4>
                                            <p>JPG or PNG (Max 5MB)</p>
                                        </div>
                                        <button class="upload-btn">Select File</button>
                                        <input type="file" name="stakeholder${index}_aadhaar_back" class="file-input" id="${stakeholderId}AadhaarBack" accept=".jpg,.jpeg,.png">
                                    </div>

                                    <div class="progress-bar">
                                        <div class="progress-fill"></div>
                                    </div>

                                    <div class="upload-status">
                                        <i class="fas fa-exclamation-circle status-icon status-pending"></i>
                                        <span class="status-text">No file uploaded</span>
                                    </div>

                                    <div class="preview-area">
                                        <div class="preview-header">
                                            <div class="preview-title">Uploaded File</div>
                                            <button class="change-file">Change File</button>
                                        </div>
                                        <div class="preview-container"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">PAN Number</label>
                                <input type="text" class="form-control" id="${stakeholderId}PanNumber"
                                    value="${stakeholder.pan_number || ''}" readonly>
                                <!-- Creative Upload for PAN Document -->
                                <div class="upload-card">
                                    <div class="upload-header">
                                        <div class="upload-title">PAN Document</div>
                                        <span class="upload-type">Required</span>
                                    </div>

                                    <div class="upload-area" id="${stakeholderId}PanArea">
                                        <i class="fas fa-file-pdf"></i>
                                        <div class="upload-text">
                                            <h4>Upload PAN Document</h4>
                                            <p>PDF, JPG or PNG (Max 5MB)</p>
                                        </div>
                                        <button class="upload-btn">Select File</button>
                                        <input type="file" name="stakeholder${index}_pan" class="file-input" id="${stakeholderId}Pan" accept=".jpg,.jpeg,.png,.pdf">
                                    </div>

                                    <div class="progress-bar">
                                        <div class="progress-fill"></div>
                                    </div>

                                    <div class="upload-status">
                                        <i class="fas fa-exclamation-circle status-icon status-pending"></i>
                                        <span class="status-text">No file uploaded</span>
                                    </div>

                                    <div class="preview-area">
                                        <div class="preview-header">
                                            <div class="preview-title">Uploaded File</div>
                                            <button class="change-file">Change File</button>
                                        </div>
                                        <div class="preview-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                    document.getElementById('stakeholderDocumentsContainer').innerHTML += sectionHtml;

                // Initialize file upload handlers for this section
                initUploadArea(`${stakeholderId}AadhaarFrontArea`, `${stakeholderId}AadhaarFront`);
                initUploadArea(`${stakeholderId}AadhaarBackArea`, `${stakeholderId}AadhaarBack`);
                initUploadArea(`${stakeholderId}PanArea`, `${stakeholderId}Pan`);
            }

            // Function to create authorized user document section - FIXED
            // Function to create authorized user document section - UPDATED
            function createAuthorizedDocumentSection(user, index) {
                const authorizedId = `authorized${index}`;
                const sectionHtml = `
                <div class="document-section" id="${authorizedId}Section">
                <div class="section-header">
                    <h5>Authorized User Documents - ${user.authorized_name || 'Authorized User ' + (index + 1)}</h5>
                    <span class="document-person-type">Authorized User</span>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="${authorizedId}Name"
                            value="${user.authorized_name || ''}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Aadhaar Number</label>
                        <input type="text" class="form-control" id="${authorizedId}AadhaarNumber"
                            value="${user.authorized_aadhaar_number || ''}" readonly>
                    </div>
                    <div class="col-md-6">
                        <!-- Creative Upload for Aadhaar Front -->
                        <div class="upload-card">
                            <div class="upload-header">
                                <div class="upload-title">Aadhaar Front</div>
                                <span class="upload-type">Required</span>
                            </div>

                            <div class="upload-area" id="${authorizedId}AadhaarFrontArea">
                                <i class="fas fa-id-card"></i>
                                <div class="upload-text">
                                    <h4>Upload Aadhaar Front</h4>
                                    <p>JPG or PNG (Max 5MB)</p>
                                </div>
                                <button class="upload-btn">Select File</button>
                                <input type="file" name="authorized${index}_aadhaar_front" class="file-input" id="${authorizedId}AadhaarFront" accept=".jpg,.jpeg,.png">
                            </div>

                            <div class="progress-bar">
                                <div class="progress-fill"></div>
                            </div>

                            <div class="upload-status">
                                <i class="fas fa-exclamation-circle status-icon status-pending"></i>
                                <span class="status-text">No file uploaded</span>
                            </div>

                            <div class="preview-area">
                                <div class="preview-header">
                                    <div class="preview-title">Uploaded File</div>
                                    <button class="change-file">Change File</button>
                                </div>
                                <div class="preview-container"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Creative Upload for Aadhaar Back -->
                        <div class="upload-card">
                            <div class="upload-header">
                                <div class="upload-title">Aadhaar Back</div>
                                <span class="upload-type">Required</span>
                            </div>

                            <div class="upload-area" id="${authorizedId}AadhaarBackArea">
                                <i class="fas fa-id-card"></i>
                                <div class="upload-text">
                                    <h4>Upload Aadhaar Back</h4>
                                    <p>JPG or PNG (Max 5MB)</p>
                                </div>
                                <button class="upload-btn">Select File</button>
                                <input type="file" name="authorized${index}_aadhaar_back" class="file-input" id="${authorizedId}AadhaarBack" accept=".jpg,.jpeg,.png">
                            </div>

                            <div class="progress-bar">
                                <div class="progress-fill"></div>
                            </div>

                            <div class="upload-status">
                                <i class="fas fa-exclamation-circle status-icon status-pending"></i>
                                <span class="status-text">No file uploaded</span>
                            </div>

                            <div class="preview-area">
                                <div class="preview-header">
                                    <div class="preview-title">Uploaded File</div>
                                    <button class="change-file">Change File</button>
                                </div>
                                <div class="preview-container"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">PAN Number</label>
                        <input type="text" class="form-control" id="${authorizedId}PanNumber"
                            value="${user.authorized_pan_number || ''}" readonly>
                        <!-- Creative Upload for PAN Document -->
                        <div class="upload-card">
                            <div class="upload-header">
                                <div class="upload-title">PAN Document</div>
                                <span class="upload-type">Required</span>
                            </div>

                            <div class="upload-area" id="${authorizedId}PanArea">
                                <i class="fas fa-file-pdf"></i>
                                <div class="upload-text">
                                    <h4>Upload PAN Document</h4>
                                    <p>PDF, JPG or PNG (Max 5MB)</p>
                                </div>
                                <button class="upload-btn">Select File</button>
                                <input type="file" name="authorized${index}_pan" class="file-input" id="${authorizedId}Pan" accept=".jpg,.jpeg,.png,.pdf">
                            </div>

                            <div class="progress-bar">
                                <div class="progress-fill"></div>
                            </div>

                            <div class="upload-status">
                                <i class="fas fa-exclamation-circle status-icon status-pending"></i>
                                <span class="status-text">No file uploaded</span>
                            </div>

                            <div class="preview-area">
                                <div class="preview-header">
                                    <div class="preview-title">Uploaded File</div>
                                    <button class="change-file">Change File</button>
                                </div>
                                <div class="preview-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                        document.getElementById('authorizedDocumentsContainer').innerHTML += sectionHtml;

                // Initialize file upload handlers for this section
                initUploadArea(`${authorizedId}AadhaarFrontArea`, `${authorizedId}AadhaarFront`);
                initUploadArea(`${authorizedId}AadhaarBackArea`, `${authorizedId}AadhaarBack`);
                initUploadArea(`${authorizedId}PanArea`, `${authorizedId}Pan`);
            }

            // Initialize file upload area with creative design
            function initUploadArea(areaId, inputId) {
                const uploadArea = document.getElementById(areaId);
                if (!uploadArea) return;

                const fileInput = document.getElementById(inputId);

                // ✅ FIX: Make preview area lookup flexible (works for merged and normal)
                const previewArea =
                    uploadArea.closest('.upload-card')?.querySelector('.preview-area') ||
                    uploadArea.parentElement.querySelector('.preview-area');

                const previewContainer = previewArea?.querySelector('.preview-container');
                const changeFileBtn = previewArea?.querySelector('.change-file');

                // ✅ FIX: broader search for status and progress elements
                const wrapper = uploadArea.closest('.upload-card') || uploadArea.parentElement;
                const statusIcon = wrapper.querySelector('.status-icon');
                const statusText = wrapper.querySelector('.status-text');
                const progressBar = wrapper.querySelector('.progress-bar');
                const progressFill = progressBar?.querySelector('.progress-fill');

                if (!previewArea || !previewContainer) return; // ✅ Prevent JS errors

                // Click on upload area
                uploadArea.addEventListener('click', function (e) {
                    if (e.currentTarget !== e.target) return;
                    fileInput.click();
                });

                changeFileBtn?.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    fileInput.click();
                });

                fileInput.addEventListener('change', function () {
                    if (this.files && this.files[0]) {
                        handleFileUpload(this.files[0]);
                    }
                });

                uploadArea.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    uploadArea.classList.add('dragover');
                });

                uploadArea.addEventListener('dragleave', function () {
                    uploadArea.classList.remove('dragover');
                });

                uploadArea.addEventListener('drop', function (e) {
                    e.preventDefault();
                    uploadArea.classList.remove('dragover');

                    if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                        fileInput.files = e.dataTransfer.files;
                        handleFileUpload(e.dataTransfer.files[0]);
                    }
                });

                function handleFileUpload(file) {
                    if (file.size > 5 * 1024 * 1024) {
                        setStatus('error', 'File too large. Max 5MB allowed.');
                        return;
                    }

                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                    if (!validTypes.includes(file.type)) {
                        setStatus('error', 'Invalid file type. Please upload JPG, PNG, or PDF.');
                        return;
                    }

                    setStatus('uploading', 'Uploading...');
                    progressBar.style.display = 'block';

                    let progress = 0;
                    const interval = setInterval(() => {
                        progress += Math.random() * 15;
                        if (progress >= 100) {
                            progress = 100;
                            clearInterval(interval);
                            setTimeout(() => {
                                progressBar.style.display = 'none';
                                setStatus('success', 'File uploaded successfully');
                                showPreview(file);
                            }, 300);
                        }
                        progressFill.style.width = progress + '%';
                    }, 200);
                }

                function setStatus(status, message) {
                    statusIcon.className = 'status-icon ';
                    statusText.textContent = message;

                    switch (status) {
                        case 'pending':
                            statusIcon.classList.add('fas', 'fa-exclamation-circle', 'status-pending');
                            break;
                        case 'uploading':
                            statusIcon.classList.add('fas', 'fa-spinner', 'fa-spin', 'status-pending');
                            break;
                        case 'success':
                            statusIcon.classList.add('fas', 'fa-check-circle', 'status-success');
                            break;
                        case 'error':
                            statusIcon.classList.add('fas', 'fa-times-circle', 'status-error');
                            break;
                    }
                }

                function showPreview(file) {
                // Clear previous preview
                        if (!previewContainer) return;
                        previewContainer.innerHTML = '';

                        const previewItem = document.createElement('div');
                        previewItem.className = 'preview-item';

                        if (file.type.includes('image')) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.style.width = '100%';
                                img.style.borderRadius = '10px';
                                img.style.objectFit = 'cover';
                                img.alt = 'Uploaded Preview';
                                previewItem.appendChild(img);

                                const removeBtn = createRemoveButton();
                                previewItem.appendChild(removeBtn);

                                const info = createFileInfo(file);
                                previewItem.appendChild(info);

                                // ✅ Make sure merged section preview is visible
                                if (previewArea) {
                                    previewArea.style.display = 'flex';
                                    previewArea.style.flexDirection = 'column';
                                    previewArea.style.alignItems = 'center';
                                    previewArea.style.gap = '5px';
                                }

                                previewContainer.appendChild(previewItem);
                            };
                            reader.readAsDataURL(file);
                        } else {
                            // For PDFs or other files
                            const fileIcon = document.createElement('div');
                            fileIcon.className = 'file-icon';
                            fileIcon.innerHTML = '<i class="fas fa-file-pdf fa-3x"></i>';
                            previewItem.appendChild(fileIcon);

                            const removeBtn = createRemoveButton();
                            previewItem.appendChild(removeBtn);

                            const info = createFileInfo(file);
                            previewItem.appendChild(info);

                            if (previewArea) {
                                previewArea.style.display = 'flex';
                                previewArea.style.flexDirection = 'column';
                                previewArea.style.alignItems = 'center';
                                previewArea.style.gap = '5px';
                            }

                            previewContainer.appendChild(previewItem);
                        }
                }

                function createRemoveButton() {
                    const removeBtn = document.createElement('div');
                    removeBtn.className = 'remove-file';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                    removeBtn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        resetUpload();
                    });
                    return removeBtn;
                }

                function createFileInfo(file) {
                    const info = document.createElement('div');
                    info.className = 'preview-info';

                    const fileName = document.createElement('div');
                    fileName.className = 'file-name';
                    fileName.textContent = file.name;

                    const fileSize = document.createElement('div');
                    fileSize.className = 'file-size';
                    fileSize.textContent = formatFileSize(file.size);

                    info.appendChild(fileName);
                    info.appendChild(fileSize);
                    return info;
                }

                function formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                }

                function resetUpload() {
                    fileInput.value = '';
                    previewArea.style.display = 'none';
                    setStatus('pending', 'No file uploaded');
                }
            }

            // Back button handlers
            document.getElementById("backToForm1").addEventListener("click", function() {
                saveFormData('form2');
                forms.form2.style.display = "none";
                forms.form1.style.display = "block";
                backStep("step2", "step1", "0%");
            });

            // Add back button handler for Form 3
            document.getElementById("backToForm2").addEventListener("click", function() {
                saveFormData('form3');
                forms.form3.style.display = "none";
                forms.form2.style.display = "block";
                backStep("step3", "step2", "33.33%");

                // Update checkboxes when going back to form 2
                updateStakeholderCheckboxes();
            });

            document.getElementById("backToForm3").addEventListener("click", function() {
                saveFormData('form4');
                forms.form4.style.display = "none";
                forms.form3.style.display = "block";
                backStep("step4", "step3", "66.66%");
            });

            // Stakeholder form functions
            // Update dynamic field creation functions to include real-time validation
            function addStakeholderRow() {
                const form = document.getElementById("dynamicForm");
                const rowCount = form.querySelectorAll('.form-row').length;
                const rowId = `stakeholder-${rowCount + 1}`;

                const row = document.createElement("div");
                row.className = "form-row";
                row.id = rowId;

                row.innerHTML = `
                <div class="form-group">
                    <label for="${rowId}-name">Full Name</label>
                    <input type="text" id="${rowId}-name" name="name" placeholder="Ex: Tarun Dhiman" class="form-control stakeholder-name" />
                </div>
                <div class="form-group">
                    <label for="${rowId}-email">Email</label>
                    <input type="email" id="${rowId}-email" name="email" placeholder="Ex: tarun.dhiman@gmail.com" class="form-control stakeholder-email" />
                </div>
                <div class="form-group">
                    <label for="${rowId}-phone">Phone Number</label>
                    <input type="text" id="${rowId}-phone" name="phone_number" placeholder="Enter your Phone Number" class="form-control stakeholder-number" />
                </div>
                <div class="form-group">
                    <label for="${rowId}-designation">Designation</label>
                    <select id="${rowId}-designation" name="designation" class="form-control stakeholder-designation">
                        <option value="">Select</option>
                        <option value="owner">Owner</option>
                        <option value="director">Director</option>
                        <option value="partner">Partner</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="${rowId}-pan">PAN Number</label>
                    <input type="text" id="${rowId}-pan" name="pan_number" class="form-control stakeholder-pannumber" placeholder="Enter PAN Number" />
                </div>
                <div class="form-group">
                    <label for="${rowId}-aadhaar">Aadhaar Number</label>
                    <input type="text" id="${rowId}-aadhaar" name="aadhaar_number" class="form-control stakeholder-aadhaarnumber" placeholder="Enter Aadhaar Number" />
                </div>
                <div class="btn-group-custom">
                    <button type="button" class="btn btn-success action-btn" onclick="addStakeholderRow()">+</button>
                    <button type="button" class="btn btn-danger action-btn" onclick="deleteStakeholderRow(this)">−</button>
                </div>
                `;

                form.appendChild(row);
                setupStakeholderValidation(row);
                updateStakeholderCheckboxes();
            }

            function deleteStakeholderRow(button) {
                const row = button.closest(".form-row");
                const form = document.getElementById("dynamicForm");
                if (form.children.length > 1) {
                    row.remove();
                    // Update checkboxes after deleting row
                    updateStakeholderCheckboxes();
                } else {
                    alert("At least one stakeholder must remain.");
                }
            }

            // Authorized user form functions
            function addAuthorizedRow() {
                addAuthorizedRowWithData();
            }

            function deleteAuthorizedRow(button) {
                const row = button.closest(".form-row");
                const form = document.getElementById("authorizedForm");
                if (form.children.length > 1) {
                    row.remove();
                    // Update delete button visibility after removal
                    updateAuthorizedDeleteButtons();
                } else {
                    alert("At least one authorized user must remain.");
                }
            }

            // Generate PDF from form data
            function generatePDF() {
                // Initialize jsPDF
                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF();

                // Add title
                doc.setFontSize(20);
                doc.text('Institute Registration Details', 105, 15, {
                    align: 'center'
                });
                doc.setFontSize(12);

                // Add institution details
                doc.text('1. Institution Information', 14, 25);
                doc.autoTable({
                    startY: 30,
                    head: [
                        ['Field', 'Value']
                    ],
                    body: [
                        ['Institute Name', formData.form1.instituteName || ''],
                        ['Institute Type', formData.form1.instituteType || ''],
                        ['Registration Type', formData.form1.registrationType || ''],
                        ['Email', formData.form1.email || ''],
                        ['Contact Number', formData.form1.contactNumber || ''],
                        ['Address Line 1', formData.form1.addressLine1 || ''],
                        ['Address Line 2', formData.form1.addressLine2 || ''],
                        ['State', formData.form1.state || ''],
                        ['City', formData.form1.city || ''],
                        ['Pincode', formData.form1.pincode || ''],
                        ['Date of Establishment', formData.form1.establishmentDate || ''],
                        ['Website URL', formData.form1.website || '']
                    ],
                    margin: {
                        left: 14
                    },
                    styles: {
                        cellPadding: 5,
                        fontSize: 10
                    }
                });

                // Add stakeholders
                doc.text('2. Stakeholder Information', 14, doc.lastAutoTable.finalY + 15);
                if (formData.form2.stakeholders && formData.form2.stakeholders.length > 0) {
                    const stakeholdersData = formData.form2.stakeholders.map(stakeholder => [
                        stakeholder.name || '',
                        stakeholder.email || '',
                        stakeholder.phone_number || '',
                        stakeholder.designation || '',
                        stakeholder.pan_number || '',
                        stakeholder.aadhaar_number || ''
                    ]);

                    doc.autoTable({
                        startY: doc.lastAutoTable.finalY + 20,
                        head: [
                            ['Name', 'Email', 'Phone', 'Designation', 'PAN', 'Aadhaar']
                        ],
                        body: stakeholdersData,
                        margin: {
                            left: 14
                        },
                        styles: {
                            cellPadding: 5,
                            fontSize: 10
                        }
                    });
                }

                // Add authorized users
                doc.text('3. Authorized User Information', 14, doc.lastAutoTable.finalY + 15);
                if (formData.form2.authorizedUsers && formData.form2.authorizedUsers.length > 0) {
                    const authorizedData = formData.form2.authorizedUsers.map(user => [
                        user.name || '',
                        user.email || '',
                        user.number || '',
                        user.designation || '',
                        user.pannumber || '',
                        user.aadhaarnumber || ''
                    ]);

                    doc.autoTable({
                        startY: doc.lastAutoTable.finalY + 20,
                        head: [
                            ['Name', 'Email', 'Phone', 'Designation', 'PAN', 'Aadhaar']
                        ],
                        body: authorizedData,
                        margin: {
                            left: 14
                        },
                        styles: {
                            cellPadding: 5,
                            fontSize: 10
                        }
                    });
                }

                // Add document details00
                doc.text('4. Document Information', 14, doc.lastAutoTable.finalY + 15);
                doc.autoTable({
                    startY: doc.lastAutoTable.finalY + 20,
                    head: [
                        ['Field', 'Value']
                    ],
                    body: [
                        ['Registration Number', formData.form3.registrationNumber || ''],
                        ['Registration Document', formData.form3.registrationDocument || ''],
                        ['PAN Number', formData.form3.panNumber || ''],
                        ['PAN Document', formData.form3.panDocument || ''],
                        ['GST Number', formData.form3.gstNumber || ''],
                        ['GST Document', formData.form3.gstDocument || ''],
                        ['Institute Logo', formData.form3.instituteLogo || ''],
                        ['Institute Image', formData.form3.instituteImage || '']
                    ],
                    margin: {
                        left: 14
                    },
                    styles: {
                        cellPadding: 5,
                        fontSize: 10
                    }
                });

                // Add bank details
                doc.text('5. Bank Information', 14, doc.lastAutoTable.finalY + 15);
                doc.autoTable({
                    startY: doc.lastAutoTable.finalY + 20,
                    head: [
                        ['Field', 'Value']
                    ],
                    body: [
                        ['Beneficiary Name', formData.form4.beneficiaryName || ''],
                        ['Account Number', formData.form4.accountNumber || ''],
                        ['Bank Name', formData.form4.bankName || ''],
                        ['IFSC Code', formData.form4.ifscCode || ''],
                        ['Account Type', formData.form4.accountType || ''],
                        ['Cancelled Cheque', formData.form4.cancelledCheque || '']
                    ],
                    margin: {
                        left: 14
                    },
                    styles: {
                        cellPading: 5,
                        fontSize: 10
                    }
                });

                // Add timestamp
                doc.text(`Generated on: ${new Date().toLocaleString()}`, 14, doc.lastAutoTable.finalY + 15);

                // Save the PDF
                doc.save('institute_registration_details.pdf');
            }

            // Submit all forms to backend
            async function submitAllForms() {
                const submissionData = prepareFormData();

                try {
                    // Get CSRF token from meta tag
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                        "{{ csrf_token() }}";

                    const response = await fetch("{{ route('branch.store.details') }}", {
                        method: 'POST',
                        body: submissionData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Show success message
                        forms.form4.style.display = "none";
                        steps.step4.classList.add("completed");
                        successMessage.style.display = 'block';
                        successMessage.innerHTML =
                            '✅ All forms submitted successfully! Data saved to database.';

                        // Generate PDF from form data
                        generatePDF();

                        // Clear all forms after 8 seconds
                        setTimeout(clearAllForms, 8000);
                    } else {
                        console.error('Error:', result.error);
                        alert('Error saving data: ' + (result.error || 'Unknown error'));
                    }
                } catch (error) {
                    console.error('Network error:', error);
                    alert('Network error occurred. Please try again.');
                }
            }

            // Make functions globally available
            window.addStakeholderRow = addStakeholderRow;
            window.deleteStakeholderRow = deleteStakeholderRow;
            window.addAuthorizedRow = addAuthorizedRow;
            window.deleteAuthorizedRow = deleteAuthorizedRow;
            window.updateStakeholderCheckboxes = updateStakeholderCheckboxes;
            window.handleStakeholderCopy = handleStakeholderCopy;
        </script>
@endsection

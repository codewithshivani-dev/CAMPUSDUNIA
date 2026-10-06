@extends('layouts.campusdunialayout')
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Register Institute</title>
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
            integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
        />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
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
        
        body {
            background-color: black;
            color: white;
            font-family: 'Open Sans', sans-serif;
        }
        .start-collecting-text {
            font-size: 40px;
            text-align: center;
            margin-top: 60px;
        }
        .start-collecting-main {
            padding: 100px 0px 100px 0px;
        }
        .start-collecting-para {
            font-size: 20px;
            text-align: center;
        }
        .register-image {
        }

        .timeline {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: -60px 0 20px;
    }

        .timeline-line {
        position: absolute;
        top: 16px;
        left: 16px; /* Start from first bullet's center */
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
        left: 16px; /* Start from first bullet's center */
        width: calc(100% - 32px); /* Subtract bullet widths from both ends */
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
            .register-image {
                display: none;
            }
            .start-collecting-text {
                font-size: 30px;
                text-align: center;
                margin-top: 0px;
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
            color: #fff;
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
    accent-color: #0d6efd; /* Blue color for the checkbox */
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
            color: #fff;
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
            color: #fff;
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
        
        .upload-area:hover, .upload-area.dragover {
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
            color: #fff;
        }
        
        .upload-text p {
            color: rgba(255, 255, 255, 0.7);
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
            color: #fff;
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
            .
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
    </style>
    <body>
        <section style="padding: 50px 0px 0px 0px; min-height: 1px"></section>
        <section class="start-collecting-main">
            <div class="container">
                <div class="row mt-4">
                    <div class="col-sm-12">
                        <div class="start-collecting-text">
                            Start Collecting Fees Online, Today!
                        </div>
                        <div class="start-collecting-para">
                            Ready to set smooth fee collection process for your
                            institute?
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!--NEW TIMELINE FORM START-->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <!-- Timeline -->
                        <div class="timeline position-relative">
                            <div
                                class="timeline-line"
                                id="timelineProgress"
                            ></div>
                            <div class="timeline-step active" id="step1">
                                <div class="timeline-bullet">1</div>
                                <div>Step 1</div>
                            </div>
                            <div class="timeline-step" id="step2">
                                <div class="timeline-bullet">2</div>
                                <div>Step 2</div>
                            </div>
                            <div class="timeline-step" id="step3">
                                <div class="timeline-bullet">3</div>
                                <div>Step 3</div>
                            </div>
                            <div class="timeline-step" id="step4">
                                <div class="timeline-bullet">4</div>
                                <div>Step 4</div>
                            </div>
                        </div>
                        <!-- Form 1 -->
                        <form id="form1">
                            <h4>Institution Form</h4>
                            <div class="row mb-3 mt-2">
                                <div class="col-md-6">
                                    <label class="mt-3">Institute Name</label>
                                    <input
                                        type="text"
                                        class="form-control mt-2"
                                        id="instituteName"
                                        
                                    />
                                </div>
                                <div class="col-md-6">
                                    <label class="mt-3">Institute Type</label>
                                    <select
                                        class="form-select mt-2"
                                        id="instituteType"
                                        
                                    >
                                        <option selected disabled value="">
                                            Choose...
                                        </option>
                                        <option>College</option>
                                        <option>University</option>
                                        <option>School</option>
                                        <option>Coaching</option>
                                        <option>Tutor</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="mt-3"
                                        >Institute Registeration Type</label
                                    >
                                    <select
                                        class="form-select mt-2"
                                        id="registrationType"
                                        
                                    >
                                        <option selected disabled value="">
                                            Choose...
                                        </option>
                                        <option>Society</option>
                                        <option>Trust</option>
                                        <option>Pvt.Ltd</option>
                                        <option>Ltd</option>
                                        <option>Llp</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="mt-3">Email</label>
                                    <input
                                        type="email"
                                        class="form-control mt-2"
                                        id="email"
                                        
                                    />
                                </div>
                                <div class="col-md-6">
                                    <label class="mt-3">Contact Number</label>
                                    <input
                                        type="text"
                                        class="form-control mt-2"
                                        id="contactNumber"
                                        
                                    />
                                </div>
                                <div class="col-md-6">
                                    <label class="mt-3">Address Line 1</label>
                                    <input
                                        type="text"
                                        class="form-control mt-2"
                                        id="addressLine1"
                                        
                                    />
                                </div>
                                <div class="col-md-6">
                                    <label class="mt-3">Address Line 2</label>
                                    <input
                                        type="text"
                                        class="form-control mt-2"
                                        id="addressLine2"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <label
                                        for="validationCustom04"
                                        class="form-label mt-3"
                                        >State</label
                                    >
                                    <select
                                        class="form-select"
                                        id="state"
                                        
                                    >
                                        <option selected disabled value="">
                                            choose...
                                        </option>
                                        <option>...</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label
                                        for="validationCustom03"
                                        class="form-label mt-3"
                                        >City</label
                                    >
                                    <select
                                        class="form-select"
                                        id="city"
                                        
                                    >
                                        <option selected disabled value="">
                                            Choose...
                                        </option>
                                        <option>...</option>
                                    </select>
                                </div> 
                                <div class="col-md-6">
                                    <label class="mt-3">Pincode</label>
                                    <input
                                        type="text"
                                        class="form-control mt-2"
                                        id="pincode"
                                        
                                    />
                                </div>
                                <div class="col-md-6">
                                    <label class="mt-3"
                                        >Date of Establishment</label
                                    >
                                    <input
                                        type="date"
                                        class="form-control mt-2"
                                        id="establishmentDate"
                                       
                                    />
                                </div>
                                <div class="col-md-6">
                                    <label class="mt-3"
                                        >Website URL</label
                                    >
                                    <input
                                        type="text"
                                        class="form-control mt-2"
                                        id="website"
                                       
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
                        <form id="form2" style="display: none">
                            <br/>
                            <h4>Add Stakeholder Details</h4>
                            <div id="dynamicForm">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="name"> Full Name</label>
                                        <input
                                            type="text" placeholder="Ex:Tarun Dhiman|Tarun Singh Dhiman"
                                            name="name[]"
                                            class="form-control stakeholder-name"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input
                                            type="email" placeholder="Ex:tarun.dhiman@gmail.com"
                                            name="email[]"
                                            class="form-control stakeholder-email"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label for="number">Phone Number</label>
                                        <input
                                            type="text" placeholder="Enter your Phone Number"
                                            name="number[]"
                                            class="form-control stakeholder-number"
                                        />  
                                    </div>
                                    <div class="form-group">
                                        <label for="designation"
                                            >Designation</label
                                        >
                                        <select id="" name="designation[]" class="form-control stakeholder-designation">
                                        <option value="">Select</option>
                                        <option value="owner">Owner</option>
                                        <option value="director">Director</option>
                                        <option value="partner">Partner</option>
                                       </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="pannumber">Pan Number</label>
                                        <input
                                            type="text" placeholder="Enter your Pan Number"
                                            name="pannumber[]"
                                            class="form-control stakeholder-pannumber"
                                        />  
                                    </div>
                                    <div class="form-group">
                                        <label for="aadhaarnumber">Aadhaar Number</label>
                                        <input
                                            type="text" placeholder="Enter your Aadhaar Number"
                                            name="aadhaarnumber[]"
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
                            <br/><br/>
                            <h4 class="mt-4">Add Authorized User Details</h4>
                            
                            <!-- Copy from Stakeholder Section -->
                            <div class="copy-stakeholder-section">
                                <div class="copy-stakeholder-title">
                                    <i class="fas fa-copy"></i> Copy Details from Stakeholders
                                </div>
                                <div id="stakeholderCheckboxes" class="stakeholder-checkbox-group">
                                    <!-- Checkboxes will be dynamically generated here -->
                                </div>
                            </div>
                            
                            <div id="authorizedForm">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="name"> Full Name</label>
                                        <input
                                            type="text" placeholder="Ex:Tarun Dhiman|Tarun Singh Dhiman"
                                            name="authorized_name[]"
                                            class="form-control authorized-name"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input
                                            type="email" placeholder="Ex:tarun.dhiman@gmail.com"
                                            name="authorized_email[]"
                                            class="form-control authorized-email"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label for="number">Phone Number</label>
                                        <input
                                            type="text" placeholder="Enter your Phone Number"
                                            name="authorized_number[]"
                                            class="form-control authorized-number"
                                        />  
                                    </div>
                                    <div class="form-group">
                                        <label for="designation"
                                            >Designation</label
                                        >
                                       <select id="" name="authorized_designation[]" class="form-control authorized-designation">
                                        <option value="">Select</option>
                                        <option value="owner">Owner</option>
                                        <option value="director">Director</option>
                                        <option value="admin">Admin</option>
                                        <option value="authorized person">Authorized Person</option>
                                       </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="number">Pan Number</label>
                                        <input
                                            type="text" placeholder="Enter your Pan Number"
                                            name="authorized_pannumber[]"
                                            class="form-control authorized-pannumber"
                                        />  
                                    </div>
                                    <div class="form-group">
                                        <label for="number">Aadhaar Number</label>
                                        <input
                                            type="text" placeholder="Enter your Aadhaar Number"
                                            name="authorized_aadhaarnumber[]"
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
                               <button type="button" class="btn btn-secondary" id="backToForm1">Back</button>
                                <button type="submit" class="btn btn-primary">
                                    Next
                                </button>
                            </div>
                        </form>
<!-- Form 3 - Updated to include creative file uploads -->
                <form id="form3" style="display:none;">
                    <h4>Document Details</h4>
                    
                    <!-- Institute Documents Section -->
                    <div class="document-section">
                        <h5>Institute Documents</h5>
                        <div class="row g-4">
                            
                            <div class="col-md-6">
                                <label class="form-label">Registration Number</label>
                                <input type="text" class="form-control" id="registrationNumber" placeholder="COI / Registration Number">
                                <!-- Creative Upload for Registration Document -->
                                <div class="upload-card">
                                    <div class="upload-header">
                                        <div class="upload-title">Certificate of Incorporation</div>
                                        <span class="upload-type">Required</span>
                                    </div>
                                    
                                    <div class="upload-area" id="registrationUploadArea">
                                        <i class="fas fa-file-pdf"></i>
                                        <div class="upload-text">
                                            <h4>Upload Registration Document</h4>
                                            <p>PDF, JPG or PNG (Max 5MB)</p>
                                        </div>
                                        <button class="upload-btn">Select File</button>
                                        <input type="file" class="file-input" id="registrationDocument" accept=".jpg,.jpeg,.png,.pdf">
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
                                <input type="text" class="form-control" id="institutePanNumber" placeholder="Enter PAN Number">
                                <!-- Creative Upload for PAN Document -->
                                <div class="upload-card">
                                    <div class="upload-header">
                                        <div class="upload-title">PAN Card Document</div>
                                        <span class="upload-type">Required</span>
                                    </div>
                                    
                                    <div class="upload-area" id="panUploadArea">
                                        <i class="fas fa-id-card"></i>
                                        <div class="upload-text">
                                            <h4>Upload PAN Document</h4>
                                            <p>PDF, JPG or PNG (Max 5MB)</p>
                                        </div>
                                        <button class="upload-btn">Select File</button>
                                        <input type="file" class="file-input" id="panDocument" accept=".jpg,.jpeg,.png,.pdf">
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
                                <!-- Creative Upload for Institute Logo -->
                                <div class="upload-card">
                                    <div class="upload-header">
                                        <div class="upload-title">Institute Logo</div>
                                        <span class="upload-type">Required</span>
                                    </div>
                                    
                                    <div class="upload-area" id="logoUploadArea">
                                        <i class="fas fa-image"></i>
                                        <div class="upload-text">
                                            <h4>Upload Institute Logo</h4>
                                            <p>JPG or PNG (Max 5MB)</p>
                                        </div>
                                        <button class="upload-btn">Select File</button>
                                        <input type="file" class="file-input" id="instituteLogo" accept=".jpg,.jpeg,.png">
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
                                <!-- Creative Upload for Institute Image -->
                                <div class="upload-card">
                                    <div class="upload-header">
                                        <div class="upload-title">Institute Image</div>
                                        <span class="upload-type">Required</span>
                                    </div>
                                    
                                    <div class="upload-area" id="imageUploadArea">
                                        <i class="fas fa-building"></i>
                                        <div class="upload-text">
                                            <h4>Upload Institute Image</h4>
                                            <p>JPG or PNG (Max 5MB)</p>
                                        </div>
                                        <button class="upload-btn">Select File</button>
                                        <input type="file" class="file-input" id="instituteImage" accept=".jpg,.jpeg,.png">
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
                            <div class="col-md-12">
                                <label class="form-label">GST Number</label>
                                <input type="text" class="form-control" id="gstNumber" placeholder="Enter GST Number">
                                <!-- Creative Upload for GST Document -->
                                <div class="upload-card">
                                    <div class="upload-header">
                                        <div class="upload-title">GST Certificate</div>
                                        <span class="upload-type">Required</span>
                                    </div>
                                    
                                    <div class="upload-area" id="gstUploadArea">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                        <div class="upload-text">
                                            <h4>Upload GST Document</h4>
                                            <p>PDF, JPG or PNG (Max 5MB)</p>
                                        </div>
                                        <button class="upload-btn">Select File</button>
                                        <input type="file" class="file-input" id="gstDocument" accept=".jpg,.jpeg,.png,.pdf">
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
                    
                    <!-- Stakeholder Documents Section (will be dynamically generated) -->
                    <div id="stakeholderDocumentsContainer"></div>
                
                    <!-- Authorized User Documents Section (will be dynamically generated) -->
                    <div id="authorizedDocumentsContainer"></div>
                        
                    <!-- Navigation Buttons -->
                    <div class="form-navigation mt-4">
                        <button type="button" class="btn btn-secondary" id="backToForm2">Back</button>
                        <button type="submit" class="btn btn-primary">Next</button>
                    </div>
                </form>

                       
                        <!-- Form 4 -->
                    <form id="form4" style="display: none">
                            <h4>Add Beneficiary Details</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="mt-3">Beneficiary Name</label>
                                    <input
                                        type="text"
                                        class="form-control mt-2"
                                        id="beneficiaryName"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <label class="mt-3"
                                        >Bank Account Number</label
                                    >
                                    <input
                                        type="text"
                                        class="form-control mt-2"
                                        id="accountNumber"
                                        
                                    />
                                </div>
                                <div class="col-md-6">
                                    <label class="mt-3">Bank Name</label>
                                    <input
                                        type="text"
                                        class="form-control mt-2"
                                        id="bankName"
                                        
                                    />
                                </div>
                                <div class="col-md-6">
                                    <label class="mt-3">IFSC Code</label>
                                    <input
                                        type="text"
                                        class="form-control mt-2"
                                        id="ifscCode"
                                        
                                    />
                                </div>
                                <div class="col-md-6">
                                    <label
                                        for="validationCustom03"
                                        class="form-label mt-3"
                                        >Account Type</label
                                    >
                                    <select class="form-select" id="accountType" >
                                        <option selected disabled value="">
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
                                    <span class="upload-type">Required</span>
                                </div>
                                
                                <div class="upload-area" id="chequeUploadArea">
                                    <i class="fas fa-money-check"></i>
                                    <div class="upload-text">
                                        <h4>Upload Cancelled Cheque</h4>
                                        <p>JPG or PNG (Max 5MB)</p>
                                    </div>
                                    <button class="upload-btn">Select File</button>
                                    <input type="file" class="file-input" id="cancelledCheque" accept=".jpg,.jpeg,.png">
                                </div>
                                
                                <div class="progress-bar">
                                    <div class="progress-fill"></div>
                                </div>
                                
                                <div class="upload-status">
                                    <i class="fas fa-exclamation-circle status-icon status-pending"></i>
                                    <span class="status-text">No file uploaded</span>
                                </div>
                                
                                <div class="preview-area">
                                    <div class="preview-h">
                                        <div class="preview-title">Uploaded File</div>
                                        <button class="change-file">Change File</button>
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
                                                value=""
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
                                        <button type="button" class="btn btn-secondary" id="backToForm3">Back</button>
                                        <button
                                            type="submit"
                                            class="btn btn-success"
                                        >
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
                            ✅ All forms submitted successfully! Data saved to localStorage.
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- jQuery Script -->
         <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

        
       <script>
      // Initialize form data object in memory if it doesn't exist
let formData = {
    form1: {},
    form2: { 
        stakeholders: [],
        authorizedUsers: []
    },
    form3: {},
    form4: {}
};

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
            
            row.innerHTML = `
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" class="form-control stakeholder-name" placeholder="Enter You Full Name" value="${stakeholder.name || ''}" />
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control stakeholder-email" placeholder="Enter your Email" value="${stakeholder.email || ''}" />
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" class="form-control stakeholder-number" placeholder="Enter your Phone Number" value="${stakeholder.number || ''}" />
                </div>
                <div class="form-group">
                    <label>Designation</label>
                    <select class="form-control stakeholder-designation">
                        <option value="">Select</option>
                        <option value="owner" ${stakeholder.designation === 'owner' ? 'selected' : ''}>Owner</option>
                        <option value="director" ${stakeholder.designation === 'director' ? 'selected' : ''}>Director</option>
                        <option value="partner" ${stakeholder.designation === 'partner' ? 'selected' : ''}>Partner</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Pan Number</label>
                    <input type="text" class="form-control stakeholder-pannumber" placeholder="Enter your Pan Number" value="${stakeholder.pannumber || ''}" />
                </div>
                 <div class="form-group">
                    <label>Aadhaar Number</label>
                    <input type="text" class="form-control stakeholder-aadhaarnumber" placeholder="Enter your Aadhaar Number" value="${stakeholder.aadhaarnumber || ''}" />
                </div>
                <div class="btn-group-custom">
                    <button type="button" class="btn btn-success action-btn" onclick="addStakeholderRow()">+</button>
                    <button type="button" class="btn btn-danger action-btn" onclick="deleteStakeholderRow(this)" ${index === 0 ? 'style="display: none;"' : ''}>−</button>
                </div>
            `;
            
            dynamicForm.appendChild(row);
        });
    } else {
        // Initialize with one empty stakeholder row if none exists
        const dynamicForm = document.getElementById('dynamicForm');
        dynamicForm.innerHTML = `
            <div class="form-row">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" placeholder="Ex:Tarun Dhiman|Tarun Singh Dhiman" class="form-control stakeholder-name" />
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" placeholder="Ex:tarun.dhiman@gmail.com" class="form-control stakeholder-email" />
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" placeholder="Enter your Phone Number" class="form-control stakeholder-number" />
                </div>
                <div class="form-group">
                    <label>Designation</label>
                    <select class="form-control stakeholder-designation">
                        <option value="">Select</option>
                        <option value="owner">Owner</option>
                        <option value="director">Director</option>
                        <option value="partner">Partner</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Pan Number</label>
                    <input type="text" class="form-control stakeholder-pannumber" placeholder="Enter Your Pan Number" />
                </div>
                 <div class="form-group">
                    <label>Aadhaar Number</label>
                    <input type="text" class="form-control stakeholder-aadhaarnumber" placeholder="Enter Your Aadhaar Number" />
                </div>
                <div class="btn-group-custom">
                    <button type="button" class="btn btn-success action-btn" onclick="addStakeholderRow()">+</button>
                    <button type="button" class="btn btn-danger action-btn" onclick="deleteStakeholderRow(this)" style="display: none;">−</button>
                </div>
            </div>
        `;
    }

    // Form 2 data - authorized users
    if (formData.form2 && formData.form2.authorizedUsers && formData.form2.authorizedUsers.length > 0) {
        const authorizedForm = document.getElementById('authorizedForm');
        authorizedForm.innerHTML = ''; // Clear existing rows
        
        formData.form2.authorizedUsers.forEach((user, index) => {
            const row = document.createElement('div');
            row.className = 'form-row';
            
            row.innerHTML = `
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" class="form-control authorized-name" placeholder="Ex:Tarun Dhiman|Tarun Singh Dhiman" value="${user.name || ''}" />
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control authorized-email" placeholder="Ex:tarun.dhiman@gmail.com" value="${user.email || ''}" />
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" class="form-control authorized-number" placeholder="Enter your Phone Number" value="${user.number || ''}" />
                </div>
                <div class="form-group">
                    <label>Designation</label>
                    <select class="form-control authorized-designation">
                        <option value="">Select</option>
                        <option value="owner" ${user.designation === 'owner' ? 'selected' : ''}>Owner</option>
                        <option value="director" ${user.designation === 'director' ? 'selected' : ''}>Director</option>
                        <option value="admin" ${user.designation === 'admin' ? 'selected' : ''}>Admin</option>
                        <option value="authorized person" ${user.designation === 'authorized person' ? 'selected' : ''}>Authorized Person</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Pan Number</label>
                    <input type="text" class="form-control authorized-pannumber" placeholder="Enter Your Pan Number" value="${user.pannumber || ''}" />
                </div>
                <div class="form-group">
                    <label>Aadhaar Number</label>
                    <input type="text" class="form-control authorized-aadhaarnumber" placeholder="Enter Your Aadhaar Number" value="${user.aadhaarnumber || ''}" />
                </div>
                <div class="btn-group-custom">
                    <button type="button" class="btn btn-success action-btn" onclick="addAuthorizedRow()">+</button>
                    <button type="button" class="btn btn-danger action-btn" onclick="deleteAuthorizedRow(this)" ${index === 0 ? 'style="display: none;"' : ''}>−</button>
                </div>
            `;
            
            authorizedForm.appendChild(row);
        });
    } else {
        // Initialize with one empty authorized user row if none exists
        const authorizedForm = document.getElementById('authorizedForm');
        authorizedForm.innerHTML = `
            <div class="form-row">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" placeholder="Ex:Tarun Dhiman|Tarun Singh Dhiman" class="form-control authorized-name" />
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" placeholder="Ex:tarun.dhiman@gmail.com" class="form-control authorized-email" />
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" placeholder="Enter your Phone Number" class="form-control authorized-number" />
                </div>
                <div class="form-group">
                    <label>Designation</label>
                    <select class="form-control authorized-designation">
                        <option value="">Select</option>
                        <option value="owner">Owner</option>
                        <option value="director">Director</option>
                        <option value="admin">Admin</option>
                        <option value="authorized person">Authorized Person</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Pan Number</label>
                    <input type="text" class="form-control authorized-pannumber" placeholder="Enter Your Pan Number" />
                </div>
                <div class="form-group">
                    <label>Aadhaar Number</label>
                    <input type="text" class="form-control authorized-aadhaarnumber" placeholder="Enter Your Aadhaar Number" />
                </div>
                <div class="btn-group-custom">
                    <button type="button" class="btn btn-success action-btn" onclick="addAuthorizedRow()">+</button>
                    <button type="button" class="btn btn-danger action-btn" onclick="deleteAuthorizedRow(this)" style="display: none;">−</button>
                </div>
            </div>
        `;
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
                element.value = value;
            }
        }
    }
    
    // Update stakeholder checkboxes
    updateStakeholderCheckboxes();
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
        checkboxContainer.innerHTML = '<div style="color: #ccc; font-style: italic;">No stakeholders available to copy</div>';
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
    const row = document.createElement("div");
    row.className = "form-row";

    // Map stakeholder designation to authorized designation
    let authorizedDesignation = data.designation;
    if (data.designation === 'partner') {
        authorizedDesignation = 'authorized person';
    }

    row.innerHTML = `
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" placeholder="Ex:Tarun Dhiman|Tarun Singh Dhiman" class="form-control authorized-name" value="${data.name || ''}" />
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" placeholder="Ex:tarun.dhiman@gmail.com" class="form-control authorized-email" value="${data.email || ''}" />
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" placeholder="Enter your Phone Number" class="form-control authorized-number" value="${data.number || ''}" />
        </div>
        <div class="form-group">
            <label>Designation</label>
            <select class="form-control authorized-designation">
                <option value="">Select</option>
                <option value="owner" ${authorizedDesignation === 'owner' ? 'selected' : ''}>Owner</option>
                <option value="director" ${authorizedDesignation === 'director' ? 'selected' : ''}>Director</option>
                <option value="admin" ${authorizedDesignation === 'admin' ? 'selected' : ''}>Admin</option>
                <option value="authorized person" ${authorizedDesignation === 'authorized person' ? 'selected' : ''}>Authorized Person</option>
            </select>
        </div>
        <div class="form-group">
            <label>Pan Number</label>
            <input type="text" class="form-control authorized-pannumber" placeholder="Enter Your Pan Number" value="${data.pannumber || ''}" />
        </div>
        <div class="form-group">
            <label>Aadhaar Number</label>
            <input type="text" class="form-control authorized-aadhaarnumber" placeholder="Enter Your Aadhaar Number" value="${data.aadhaarnumber || ''}" />
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

// Function to add empty authorized row
function addAuthorizedRow() {
    const form = document.getElementById("authorizedForm");
    const row = document.createElement("div");
    row.className = "form-row";

    row.innerHTML = `
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" placeholder="Ex:Tarun Dhiman|Tarun Singh Dhiman" class="form-control authorized-name" />
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" placeholder="Ex:tarun.dhiman@gmail.com" class="form-control authorized-email" />
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" placeholder="Enter your Phone Number" class="form-control authorized-number" />
        </div>
        <div class="form-group">
            <label>Designation</label>
            <select class="form-control authorized-designation">
                <option value="">Select</option>
                <option value="owner">Owner</option>
                <option value="director">Director</option>
                <option value="admin">Admin</option>
                <option value="authorized person">Authorized Person</option>
            </select>
        </div>
        <div class="form-group">
            <label>Pan Number</label>
            <input type="text" class="form-control authorized-pannumber" placeholder="Enter Your Pan Number" />
        </div>
        <div class="form-group">
            <label>Aadhaar Number</label>
            <input type="text" class="form-control authorized-aadhaarnumber" placeholder="Enter Your Aadhaar Number" />
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
document.addEventListener('DOMContentLoaded', function() {
    loadSavedData();
    
    // Initialize all upload areas
    initUploadArea('registrationUploadArea', 'registrationDocument');
    initUploadArea('panUploadArea', 'panDocument');
    initUploadArea('gstUploadArea', 'gstDocument');
    initUploadArea('logoUploadArea', 'instituteLogo');
    initUploadArea('imageUploadArea', 'instituteImage');
    initUploadArea('chequeUploadArea', 'cancelledCheque');
});

// Save form data to memory
function saveFormData(formId) {
    const form = document.getElementById(formId);
    
    if (formId === 'form2') {
        // Special handling for form2 (stakeholders and authorized users)
        const stakeholders = [];
        const authorizedUsers = [];
        
        // Save stakeholders data
        const stakeholderRows = document.querySelectorAll('#dynamicForm .form-row');
        stakeholderRows.forEach(row => {
            const name = row.querySelector('.stakeholder-name').value;
            const email = row.querySelector('.stakeholder-email').value;
            const number = row.querySelector('.stakeholder-number').value;
            const designation = row.querySelector('.stakeholder-designation').value;
            const pannumber = row.querySelector('.stakeholder-pannumber').value;
            const aadhaarnumber = row.querySelector('.stakeholder-aadhaarnumber').value;
            
            stakeholders.push({
                name,
                email,
                number,
                designation,
                pannumber,
                aadhaarnumber
            });
        });
        
        // Save authorized users data
        const authorizedRows = document.querySelectorAll('#authorizedForm .form-row');
        authorizedRows.forEach(row => {
            const name = row.querySelector('.authorized-name').value;
            const email = row.querySelector('.authorized-email').value;
            const number = row.querySelector('.authorized-number').value;
            const designation = row.querySelector('.authorized-designation').value;
            const pannumber = row.querySelector('.authorized-pannumber').value;
            const aadhaarnumber = row.querySelector('.authorized-aadhaarnumber').value;
            
            authorizedUsers.push({
                name,
                email,
                number,
                designation,
                pannumber,
                aadhaarnumber
            });
        });
        
        formData.form2 = { 
            stakeholders,
            authorizedUsers,
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
                <input type="text" placeholder="Ex:Tarun Dhiman|Tarun Singh Dhiman" class="form-control stakeholder-name" oninput="updateStakeholderCheckboxes()" />
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" placeholder="Ex:tarun.dhiman@gmail.com" class="form-control stakeholder-email" />
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" placeholder="Enter your Phone Number" class="form-control stakeholder-number" />
            </div>
            <div class="form-group">
                <label>Designation</label>
                <select class="form-control stakeholder-designation">
                    <option value="">Select</option>
                    <option value="owner">Owner</option>
                    <option value="director">Director</option>
                    <option value="partner">Partner</option>
                </select>
            </div>
            <div class="form-group">
                <label>Pan Number</label>
                <input type="text" class="form-control stakeholder-pannumber" placeholder="Enter Your Pan Number" />
            </div>
            <div class="form-group">
                <label>Aadhaar Number</label>
                <input type="text" class="form-control stakeholder-aadhaarnumber" placeholder="Enter Your Aadhaar Number" />
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
                <input type="text" placeholder="Ex:Tarun Dhiman|Tarun Singh Dhiman" class="form-control authorized-name" />
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" placeholder="Ex:tarun.dhiman@gmail.com" class="form-control authorized-email" />
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" placeholder="Enter your Phone Number" class="form-control authorized-number" />
            </div>
            <div class="form-group">
                <label>Designation</label>
                <select class="form-control authorized-designation">
                    <option value="">Select</option>
                    <option value="owner">Owner</option>
                    <option value="director">Director</option>
                    <option value="admin">Admin</option>
                    <option value="authorized person">Authorized Person</option>
                </select>
            </div>
            <div class="form-group">
                <label>Pan Number</label>
                <input type="text" class="form-control authorized-pannumber" placeholder="Enter Your Pan Number" />
            </div>
            <div class="form-group">
                <label>Aadhaar Number</label>
                <input type="text" class="form-control authorized-aadhaarnumber" placeholder="Enter Your Aadhaar Number" />
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
forms.form1.addEventListener("submit", function (e) {
    e.preventDefault();
    
    // Validate form 1
    const form1Inputs = forms.form1.querySelectorAll('input[required], select[required]');
    let isValid = true;
    
    form1Inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        alert('Please fill all required fields in Form 1');
        return;
    }
    
    saveFormData('form1');
    forms.form1.style.display = "none";
    forms.form2.style.display = "block";
    updateTimeline("step1", "step2", "33.33%");
    
    // Update checkboxes when moving to form 2
    updateStakeholderCheckboxes();
});

forms.form2.addEventListener("submit", function (e) {
    e.preventDefault();
    saveFormData('form2');
    forms.form2.style.display = "none";
    forms.form3.style.display = "block";
    updateTimeline("step2", "step3", "66.66%");
    
    // Generate document sections for stakeholders and authorized users
    generateDocumentSections();
});

forms.form3.addEventListener("submit", function (e) {
    e.preventDefault();
    saveFormData('form3');
    forms.form3.style.display = "none";
    forms.form4.style.display = "block";
    updateTimeline("step3", "step4", "100%");
});

forms.form4.addEventListener("submit", function (e) {
    e.preventDefault();
    saveFormData('form4');
    forms.form4.style.display = "none";
    steps.step4.classList.add("completed");
    successMessage.style.display = "block";
    
    // Generate PDF from form data
    generatePDF();
    
    // Clear all forms after 8 seconds
    setTimeout(clearAllForms, 8000);
});

// Function to generate document sections in Form 3
// Function to generate document sections in Form 3
function generateDocumentSections() {
    // Clear existing dynamic sections
    document.getElementById('stakeholderDocumentsContainer').innerHTML = '';
    document.getElementById('authorizedDocumentsContainer').innerHTML = '';
    
    // Find ACTUAL duplicate persons (same person in both stakeholders and authorized users)
    const duplicatePersons = findDuplicatePersons();
    const processedStakeholderIndexes = new Set();
    const processedAuthorizedIndexes = new Set();
    
    console.log('Found duplicates:', duplicatePersons);
    console.log('Stakeholders:', formData.form2.stakeholders);
    console.log('Authorized Users:', formData.form2.authorizedUsers);
    
    // First, handle actual duplicates (same person in both sections)
    duplicatePersons.forEach(duplicate => {
        // Only process if both stakeholder and authorized user haven't been processed yet
        if (!processedStakeholderIndexes.has(duplicate.stakeholderIndex) && 
            !processedAuthorizedIndexes.has(duplicate.authorizedIndex)) {
            
            createMergedDocumentSection(duplicate, duplicate.stakeholderIndex);
            processedStakeholderIndexes.add(duplicate.stakeholderIndex);
            processedAuthorizedIndexes.add(duplicate.authorizedIndex);
        }
    });
    
    // Then, create separate sections for remaining stakeholders (non-duplicates)
    if (formData.form2.stakeholders && formData.form2.stakeholders.length > 0) {
        formData.form2.stakeholders.forEach((stakeholder, index) => {
            if (!processedStakeholderIndexes.has(index)) {
                createStakeholderDocumentSection(stakeholder, index, false);
            }
        });
    }
    
    // Finally, create separate sections for remaining authorized users (non-duplicates)
    if (formData.form2.authorizedUsers && formData.form2.authorizedUsers.length > 0) {
        formData.form2.authorizedUsers.forEach((user, index) => {
            if (!processedAuthorizedIndexes.has(index)) {
                createAuthorizedDocumentSection(user, index);
            }
        });
    }
    
    // Log final processing results
    console.log('Processed stakeholders:', Array.from(processedStakeholderIndexes));
    console.log('Processed authorized users:', Array.from(processedAuthorizedIndexes));
}

// Improved function to detect if a person exists in both stakeholders and authorized users
function findDuplicatePersons() {
    const duplicates = [];
    const processedPairs = new Set(); // Track processed pairs to avoid duplicates
    
    if (!formData.form2.stakeholders || !formData.form2.authorizedUsers) {
        return duplicates;
    }
    
    formData.form2.stakeholders.forEach((stakeholder, sIndex) => {
        formData.form2.authorizedUsers.forEach((authorized, aIndex) => {
            const pairKey = `${sIndex}_${aIndex}`;
            
            if (processedPairs.has(pairKey)) {
                return; // Skip already processed pairs
            }
            
            // STRICT matching: require strong evidence to consider it the same person
            let matchScore = 0;
            let matchReasons = [];
            
            // Check name match (exact, case-insensitive, ignoring extra spaces)
            if (stakeholder.name && authorized.name) {
                const cleanStakeholderName = stakeholder.name.toLowerCase().trim().replace(/\s+/g, ' ');
                const cleanAuthorizedName = authorized.name.toLowerCase().trim().replace(/\s+/g, ' ');
                if (cleanStakeholderName === cleanAuthorizedName) {
                    matchScore += 3; // Name match is strong evidence
                    matchReasons.push('Name match');
                }
            }
            
            // Check email match (exact, case-insensitive)
            if (stakeholder.email && authorized.email) {
                const cleanStakeholderEmail = stakeholder.email.toLowerCase().trim();
                const cleanAuthorizedEmail = authorized.email.toLowerCase().trim();
                if (cleanStakeholderEmail === cleanAuthorizedEmail && cleanStakeholderEmail !== '') {
                    matchScore += 3; // Email match is strong evidence
                    matchReasons.push('Email match');
                }
            }
            
            // Check phone match (exact, after cleaning)
            if (stakeholder.number && authorized.number) {
                const cleanStakeholderPhone = stakeholder.number.replace(/\D/g, '');
                const cleanAuthorizedPhone = authorized.number.replace(/\D/g, '');
                if (cleanStakeholderPhone && cleanAuthorizedPhone && 
                    cleanStakeholderPhone === cleanAuthorizedPhone) {
                    matchScore += 3; // Phone match is strong evidence
                    matchReasons.push('Phone match');
                }
            }
            
            // Check PAN match (exact, case-insensitive)
            if (stakeholder.pannumber && authorized.pannumber) {
                const cleanStakeholderPAN = stakeholder.pannumber.toLowerCase().trim();
                const cleanAuthorizedPAN = authorized.pannumber.toLowerCase().trim();
                if (cleanStakeholderPAN === cleanAuthorizedPAN && cleanStakeholderPAN !== '') {
                    matchScore += 4; // PAN match is very strong evidence (unique identifier)
                    matchReasons.push('PAN match');
                }
            }
            
            // Check Aadhaar match (exact)
            if (stakeholder.aadhaarnumber && authorized.aadhaarnumber) {
                const cleanStakeholderAadhaar = stakeholder.aadhaarnumber.trim();
                const cleanAuthorizedAadhaar = authorized.aadhaarnumber.trim();
                if (cleanStakeholderAadhaar === cleanAuthorizedAadhaar && cleanStakeholderAadhaar !== '') {
                    matchScore += 4; // Aadhaar match is very strong evidence (unique identifier)
                    matchReasons.push('Aadhaar match');
                }
            }
            
            // Only consider it a duplicate if we have strong evidence (score >= 4)
            // This ensures we only merge when we're very confident it's the same person
            if (matchScore >= 4) {
                duplicates.push({
                    stakeholderIndex: sIndex,
                    authorizedIndex: aIndex,
                    person: {...stakeholder, ...authorized}, // Merge properties
                    matchScore: matchScore,
                    matchReasons: matchReasons
                });
                
                processedPairs.add(pairKey);
                
                console.log(`Found duplicate: ${stakeholder.name} & ${authorized.name} (Score: ${matchScore})`, matchReasons);
            }
        });
    });
    
    return duplicates;
}

// Function to create a merged document section
// Function to create a merged document section
function createMergedDocumentSection(duplicate, index) {
    const { person, stakeholderIndex, authorizedIndex, matchScore, matchReasons } = duplicate;
    const mergedId = `merged${stakeholderIndex}_${authorizedIndex}`;
    
    // Get roles from both sections
    const stakeholderRole = formData.form2.stakeholders[stakeholderIndex]?.designation || 'Not specified';
    const authorizedRole = formData.form2.authorizedUsers[authorizedIndex]?.designation || 'Not specified';
    
    const sectionHtml = `
        <div class="document-section merged-section" id="${mergedId}Section">
            <div class="section-header">
                <h5>${person.name || 'Merged Profile'}</h5>
                <span class="document-person-type" style="background: #6f42c1;">
                    <i class="fas fa-link"></i> Merged Profile (Stakeholder & Authorized User)
                </span>
            </div>
            
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle"></i> 
                This person appears in both Stakeholders and Authorized Users sections. 
                Only one set of documents is required.
                ${matchReasons && matchReasons.length > 0 ? 
                    `<br><small><strong>Match reasons:</strong> ${matchReasons.join(', ')}</small>` : ''}
            </div>

            <!-- Personal Information -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="section-subtitle">Personal Information</h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="${mergedId}Name" 
                        value="${person.name || ''}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="text" class="form-control" id="${mergedId}Email" 
                        value="${person.email || ''}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="${mergedId}Phone" 
                        value="${person.number || ''}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Designation</label>
                    <input type="text" class="form-control" id="${mergedId}Designation" 
                        value="${person.designation || ''}" readonly>
                </div>
            </div>

            <!-- Identity Documents -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="section-subtitle">Identity Documents</h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Aadhaar Number</label>
                    <input type="text" class="form-control" id="${mergedId}AadhaarNumber" 
                        value="${person.aadhaarnumber || ''}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">PAN Number</label>
                    <input type="text" class="form-control" id="${mergedId}PanNumber" 
                        value="${person.pannumber || ''}" readonly>
                </div>
            </div>

            <!-- Document Uploads -->
            <div class="row g-4">
                <!-- Aadhaar Front Upload -->
                <div class="col-md-6">
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
                            <input type="file" class="file-input" id="${mergedId}AadhaarFront" accept=".jpg,.jpeg,.png">
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
                
                <!-- Aadhaar Back Upload -->
                <div class="col-md-6">
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
                            <input type="file" class="file-input" id="${mergedId}AadhaarBack" accept=".jpg,.jpeg,.png">
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
                
                <!-- PAN Document Upload -->
                <div class="col-md-6">
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
                            <input type="file" class="file-input" id="${mergedId}Pan" accept=".jpg,.jpeg,.png,.pdf">
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
                
                <!-- Profile Photo Upload (Optional) -->
                <div class="col-md-6">
                    <div class="upload-card">
                        <div class="upload-header">
                            <div class="upload-title">Profile Photo</div>
                            <span class="upload-type">Optional</span>
                        </div>
                        
                        <div class="upload-area" id="${mergedId}ProfilePhotoArea">
                            <i class="fas fa-user-circle"></i>
                            <div class="upload-text">
                                <h4>Upload Profile Photo</h4>
                                <p>JPG or PNG (Max 5MB)</p>
                            </div>
                            <button class="upload-btn">Select File</button>
                            <input type="file" class="file-input" id="${mergedId}ProfilePhoto" accept=".jpg,.jpeg,.png">
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
            
            <!-- Additional Information -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="additional-info">
                        <h6><i class="fas fa-info-circle"></i> Profile Details</h6>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Stakeholder Role:</span>
                                <span class="info-value">${stakeholderRole}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Authorized User Role:</span>
                                <span class="info-value">${authorizedRole}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Match Confidence:</span>
                                <span class="info-value" style="color: ${matchScore >= 6 ? '#28a745' : matchScore >= 4 ? '#ffc107' : '#dc3545'};">
                                    ${matchScore >= 6 ? 'High' : matchScore >= 4 ? 'Medium' : 'Low'} (${matchScore} points)
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Profile Type:</span>
                                <span class="info-value">Combined Stakeholder & Authorized User</span>
                            </div>
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
    initUploadArea(`${mergedId}ProfilePhotoArea`, `${mergedId}ProfilePhoto`);
    
    // Add custom CSS for merged sections if not already added
    if (!document.querySelector('#merged-section-styles')) {
        const style = document.createElement('style');
        style.id = 'merged-section-styles';
        style.textContent = `
            .merged-section {
                border-left: 4px solid #6f42c1;
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border-radius: 8px;
                padding: 20px;
                margin-bottom: 25px;
                box-shadow: 0 2px 10px rgba(111, 66, 193, 0.1);
            }
            .merged-section .section-header {
                background: linear-gradient(135deg, #6f42c1 0%, #5a2d91 100%);
                color: white;
                padding: 15px 20px;
                border-radius: 8px 8px 0 0;
                margin: -20px -20px 20px -20px;
            }
            .merged-section .section-header h5 {
                color: white;
                margin: 0;
                font-weight: 600;
                font-size: 1.25rem;
            }
            .section-subtitle {
                color: #495057;
                font-weight: 600;
                margin-bottom: 15px;
                padding-bottom: 8px;
                border-bottom: 2px solid #6f42c1;
            }
            .additional-info {
                background: #f8f9fa;
                border: 1px solid #e9ecef;
                border-radius: 8px;
                padding: 15px;
                margin-top: 20px;
            }
            .additional-info h6 {
                color: #495057;
                margin-bottom: 15px;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .info-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 12px;
            }
            .info-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 0;
                border-bottom: 1px solid #e9ecef;
            }
            .info-item:last-child {
                border-bottom: none;
            }
            .info-label {
                font-weight: 500;
                color: #6c757d;
                font-size: 0.9rem;
            }
            .info-value {
                color: #495057;
                font-weight: 600;
                font-size: 0.9rem;
                text-align: right;
            }
            .document-person-type {
                background: #6f42c1;
                color: white;
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 0.8em;
                font-weight: 500;
                display: inline-flex;
                align-items: center;
                gap: 5px;
                margin-top: 5px;
            }
            .merged-section .upload-card {
                border: 1px solid #e0d6f5;
                background: white;
            }
            .merged-section .upload-card:hover {
                border-color: #6f42c1;
                box-shadow: 0 2px 8px rgba(111, 66, 193, 0.15);
            }
        `;
        document.head.appendChild(style);
    }
}

// Function to create regular stakeholder document section
function createStakeholderDocumentSection(stakeholder, index, isMerged = false) {
    const stakeholderId = `stakeholder${index + 1}`;
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
                        value="${stakeholder.aadhaarnumber || ''}" readonly>
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
                            <input type="file" class="file-input" id="${stakeholderId}AadhaarFront" accept=".jpg,.jpeg,.png">
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
                            <input type="file" class="file-input" id="${stakeholderId}AadhaarBack" accept=".jpg,.jpeg,.png">
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
                        value="${stakeholder.pannumber || ''}" readonly>
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
                            <input type="file" class="file-input" id="${stakeholderId}Pan" accept=".jpg,.jpeg,.png,.pdf">
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

// Function to create regular authorized user document section
function createAuthorizedDocumentSection(user, index) {
    const authorizedId = `authorized${index + 1}`;
    const sectionHtml = `
        <div class="document-section" id="${authorizedId}Section">
            <div class="section-header">
                <h5>Authorized User Documents - ${user.name || 'Authorized User ' + (index + 1)}</h5>
                <span class="document-person-type">Authorized User</span>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="${authorizedId}Name" 
                        value="${user.name || ''}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Aadhaar Number</label>
                    <input type="text" class="form-control" id="${authorizedId}AadhaarNumber" 
                        value="${user.aadhaarnumber || ''}" readonly>
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
                            <input type="file" class="file-input" id="${authorizedId}AadhaarFront" accept=".jpg,.jpeg,.png">
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
                            <input type="file" class="file-input" id="${authorizedId}AadhaarBack" accept=".jpg,.jpeg,.png">
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
                        value="${user.pannumber || ''}" readonly>
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
                            <input type="file" class="file-input" id="${authorizedId}Pan" accept=".jpg,.jpeg,.png,.pdf">
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
    const previewArea = uploadArea.parentElement.querySelector('.preview-area');
    const previewContainer = previewArea.querySelector('.preview-container');
    const changeFileBtn = previewArea.querySelector('.change-file');
    const statusIcon = uploadArea.parentElement.querySelector('.status-icon');
    const statusText = uploadArea.parentElement.querySelector('.status-text');
    const progressBar = uploadArea.parentElement.querySelector('.progress-bar');
    const progressFill = progressBar.querySelector('.progress-fill');

    // Click on upload area
    uploadArea.addEventListener('click', function(e) {
        // Don't trigger input when clicking on change file button
        if (e.target !== changeFileBtn && !changeFileBtn.contains(e.target)) {
            fileInput.click();
        }
    });

    // Change file button - prevent form submission
    changeFileBtn.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent form submission
        e.stopPropagation(); // Stop event bubbling
        fileInput.click();
    });

    // File input change
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            handleFileUpload(this.files[0]);
        }
    });

    // Drag and drop functionality
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function() {
        uploadArea.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            fileInput.files = e.dataTransfer.files;
            handleFileUpload(e.dataTransfer.files[0]);
        }
    });

    function handleFileUpload(file) {
        // Validate file size
        if (file.size > 5 * 1024 * 1024) {
            setStatus('error', 'File too large. Max 5MB allowed.');
            return;
        }
    
        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        if (!validTypes.includes(file.type)) {
            setStatus('error', 'Invalid file type. Please upload JPG, PNG, or PDF.');
            return;
        }
    
        // Show uploading progress
        setStatus('uploading', 'Uploading...');
        progressBar.style.display = 'block';
    
        // Simulate upload progress
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
            
                // Upload complete
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
        
        switch(status) {
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
        previewContainer.innerHTML = '';
        
        const previewItem = document.createElement('div');
        previewItem.className = 'preview-item';
        
        if (file.type.includes('image')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                previewItem.appendChild(img);
                
                const removeBtn = createRemoveButton();
                previewItem.appendChild(removeBtn);
                
                const info = createFileInfo(file);
                previewItem.appendChild(info);
            };
            reader.readAsDataURL(file);
        } else {
            // For PDFs and other files
            const fileIcon = document.createElement('div');
            fileIcon.className = 'file-icon';
            fileIcon.innerHTML = '<i class="fas fa-file-pdf"></i>';
            previewItem.appendChild(fileIcon);
            
            const removeBtn = createRemoveButton();
            previewItem.appendChild(removeBtn);
            
            const info = createFileInfo(file);
            previewItem.appendChild(info);
        }
        
        previewContainer.appendChild(previewItem);
        previewArea.style.display = 'block';
    }

    function createRemoveButton() {
        const removeBtn = document.createElement('div');
        removeBtn.className = 'remove-file';
        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
        removeBtn.addEventListener('click', function(e) {
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

// Add back button handler for Form 3
document.getElementById("backToForm2").addEventListener("click", function () {
    saveFormData('form3');
    forms.form3.style.display = "none";
    forms.form2.style.display = "block";
    backStep("step3", "step2", "33.33%");
    
    // Update checkboxes when going back to form 2
    updateStakeholderCheckboxes();
});

// Back button handlers
document.getElementById("backToForm1").addEventListener("click", function () {
    saveFormData('form2');
    forms.form2.style.display = "none";
    forms.form1.style.display = "block";
    backStep("step2", "step1", "0%");
});

document.getElementById("backToForm3").addEventListener("click", function () {
    saveFormData('form4');
    forms.form4.style.display = "none";
    forms.form3.style.display = "block";
    backStep("step4", "step3", "66.66%");
});

// Stakeholder form functions
function addStakeholderRow() {
    const form = document.getElementById("dynamicForm");
    const row = document.createElement("div");
    row.className = "form-row";

    row.innerHTML = `
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" placeholder="Ex:Tarun Dhiman|Tarun Singh Dhiman" class="form-control stakeholder-name" oninput="updateStakeholderCheckboxes()" />
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" placeholder="Ex:tarun.dhiman@gmail.com" class="form-control stakeholder-email" />
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" placeholder="Enter your Phone Number" class="form-control stakeholder-number" />
        </div>
        <div class="form-group">
            <label>Designation</label>
            <select class="form-control stakeholder-designation">
                <option value="">Select</option>
                <option value="owner">Owner</option>
                <option value="director">Director</option>
                <option value="partner">Partner</option>
            </select>
        </div>
        <div class="form-group">
            <label>Pan Number</label>
            <input type="text" class="form-control stakeholder-pannumber" placeholder="Enter Your Pan Number" />
        </div>
        <div class="form-group">
            <label>Aadhaar Number</label>
            <input type="text" class="form-control stakeholder-aadhaarnumber" placeholder="Enter Your Aadhaar Number" />
        </div>
        <div class="btn-group-custom">
            <button type="button" class="btn btn-success action-btn" onclick="addStakeholderRow()">+</button>
            <button type="button" class="btn btn-danger action-btn" onclick="deleteStakeholderRow(this)">−</button>
        </div>
    `;

    form.appendChild(row);
    
    // Update checkboxes after adding new row
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
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    // Add title
    doc.setFontSize(20);
    doc.text('Institute Registration Details', 105, 15, { align: 'center' });
    doc.setFontSize(12);
    
    // Add institution details
    doc.text('1. Institution Information', 14, 25);
    doc.autoTable({
        startY: 30,
        head: [['Field', 'Value']],
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
        margin: { left: 14 },
        styles: { cellPadding: 5, fontSize: 10 }
    });
    
    // Add stakeholders
    doc.text('2. Stakeholder Information', 14, doc.lastAutoTable.finalY + 15);
    if (formData.form2.stakeholders && formData.form2.stakeholders.length > 0) {
        const stakeholdersData = formData.form2.stakeholders.map(stakeholder => [
            stakeholder.name || '',
            stakeholder.email || '',
            stakeholder.number || '',
            stakeholder.designation || '',
            stakeholder.pannumber || '',
            stakeholder.aadhaarnumber || ''
        ]);
        
        doc.autoTable({
            startY: doc.lastAutoTable.finalY + 20,
            head: [['Name', 'Email', 'Phone', 'Designation', 'PAN', 'Aadhaar']],
            body: stakeholdersData,
            margin: { left: 14 },
            styles: { cellPadding: 5, fontSize: 10 }
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
            head: [['Name', 'Email', 'Phone', 'Designation', 'PAN', 'Aadhaar']],
            body: authorizedData,
            margin: { left: 14 },
            styles: { cellPadding: 5, fontSize: 10 }
        });
    }
    
    // Add document details00
    doc.text('4. Document Information', 14, doc.lastAutoTable.finalY + 15);
    doc.autoTable({
        startY: doc.lastAutoTable.finalY + 20,
        head: [['Field', 'Value']],
        body: [
            ['Registration Number', formData.form3.registrationNumber || ''],
            ['Registration Document', formData.form3.registrationDocument || ''],
            ['PAN Number', formData.form3.panNumber || ''],
            ['PAN Document', formData.form3.panDocument || ''], 
            ['GST Number',  formData.form3.gstNumber || ''],
            ['GST Document', formData.form3.gstDocument || ''],
            ['Institute Logo', formData.form3.instituteLogo || ''],
            ['Institute Image', formData.form3.instituteImage || '']
        ],
        margin: { left: 14 },
        styles: { cellPadding: 5, fontSize: 10 }
    }); 
    
    // Add bank details
    doc.text('5. Bank Information', 14, doc.lastAutoTable.finalY + 15);
    doc.autoTable({
        startY: doc.lastAutoTable.finalY + 20,
        head: [['Field', 'Value']],
        body: [
            ['Beneficiary Name', formData.form4.beneficiaryName || ''],
            ['Account Number', formData.form4.accountNumber || ''],
            ['Bank Name', formData.form4.bankName || ''],
            ['IFSC Code', formData.form4.ifscCode || ''],
            ['Account Type', formData.form4.accountType || ''],
            ['Cancelled Cheque', formData.form4.cancelledCheque || '']
        ],
        margin: { left: 14 },
        styles: { cellPading: 5, fontSize: 10 }
    });
    
    // Add timestamp
    doc.text(`Generated on: ${new Date().toLocaleString()}`, 14, doc.lastAutoTable.finalY + 15);
    
    // Save the PDF
    doc.save('institute_registration_details.pdf');
}

// Make functions globally available
window.addStakeholderRow = addStakeholderRow;
window.deleteStakeholderRow = deleteStakeholderRow;
window.addAuthorizedRow = addAuthorizedRow;
window.deleteAuthorizedRow = deleteAuthorizedRow;
window.updateStakeholderCheckboxes = updateStakeholderCheckboxes;
window.handleStakeholderCopy = handleStakeholderCopy;
</script>
    </body>
</html>
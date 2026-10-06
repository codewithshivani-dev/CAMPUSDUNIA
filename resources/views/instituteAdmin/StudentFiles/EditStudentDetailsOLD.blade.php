@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
.timeline {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin: 40px 0 20px;
}

.timeline-line {
    position: absolute;
    top: 16px;
    left: 10%;
    height: 4px;
    background: #198754;
    z-index: 0;
    transition: width 0.4s ease;
    width: 0%;
}

.timeline::before {
    content: '';
    position: absolute;
    top: 16px;
    left: 10%;
    width: 80%;
    height: 4px;
    background: #DEE2E6;
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
    background: #DEE2E6;
    color: white;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    font-weight: bold;
    cursor: pointer;
}

.timeline-step.active .timeline-bullet {
    background: #0D6EFD;
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

#successMessage {
    transition: all 0.3s ease;
    position: relative;
    padding-right: 3rem;
}

.is-invalid {
    border-color: #dc3545;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}

.invalid-feedback {
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}

.disabled-field {
    opacity: 0.6;
    pointer-events: none;
}

.timeline-step.locked {
    opacity: 0.5;
    cursor: not-allowed;
}

label {
    margin-top: .5rem;
    margin-bottom: 0px;
}

.guardianSelectContainer label {
    margin-top: 0px;
    margin-bottom: 0px;
}

.form-check-input {
    position: relative;
    margin: 0 !important;
}
</style>
<section>
@php
    $courseLabel = (isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School')
    ? 'Class'
    : 'Course';
@endphp    
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Timeline -->
                <div class="timeline position-relative">
                    <div class="timeline-line" id="timelineProgress"></div>
                    <div class="timeline-step active" id="step1">
                        <div class="timeline-bullet">01</div>
                        <div>Basic Details</div>
                    </div>
                    <div class="timeline-step" id="step2">
                        <div class="timeline-bullet">02</div>
                        <div>Address Details</div>
                    </div>
                    <div class="timeline-step" id="step3">
                        <div class="timeline-bullet">03</div>
                        <div>Academic Details</div>
                    </div>
                    <div class="timeline-step" id="step4">
                        <div class="timeline-bullet">04</div>
                        <div>Documents</div>
                    </div>
                    <div class="timeline-step" id="step5">
                        <div class="timeline-bullet">05</div>
                        <div>Bank Details</div>
                    </div>
                </div>

                <!-- Form 1 -->
                <form id="form1">
                    {{-- ===================== STUDENT DETAILS ===================== --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Student Details</h5>
                        </div>
                        <div class="card-body row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Registration Number</label>
                                <div class="input-group">
                                    <input type="text" name="registration_number" id="registration_number"
                                        class="form-control" placeholder="Enter or Generate"
                                        value="{{old('registration_number', $student->registration_number)}}" readonly>
                                    <button class="btn btn-outline-secondary" type="button"
                                        id="btnGenerateReg" disabled>Generate</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" required
                                    value="{{old('first_name', $student->first_name)}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" class="form-control" name="middle_name"
                                    value="{{old('middle_name', $student->middle_name)}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name"
                                    value="{{old('last_name', $student->last_name)}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="dob" id="student_dob" class="form-control" required
                                    value="{{old('dob', $student->dob)}}">
                            </div>
                            <div class="col-md-4">
                                <label>Gender</label>
                                <select name="gender" class="form-control" required>
                                    <option value="">Select</option>
                                    <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $student->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text">+91</span>
                                    <input type="tel" name="mobile" class="form-control" pattern="[0-9]{10}"
                                        title="Please enter exactly 10 digits"  value="{{old('mobile', $student->mobile)}}">
                                </div>
                                <div class="invalid-feedback">Please enter a valid 10-digit mobile number</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email ID</label>
                                <input type="email" class="form-control" name="email" required value="{{old('email', $student->email)}}" readonly>
                                <div class="invalid-feedback">Please enter a valid email address</div>
                            </div>
                            <!-- NEW: Nationality Field -->
                            <div class="col-md-4">
                                <label>Nationality</label>
                                <select name="nationality" class="form-control">
                                    <option value="">Select Nationality</option>
                                    <option value="Indian" {{ old('nationality', $student->nationality) == 'Indian' ? 'selected' : '' }}>Indian</option>
                                    <option value="American" {{ old('nationality', $student->nationality) == 'American' ? 'selected' : '' }}>American</option>
                                    <option value="British" {{ old('nationality', $student->nationality) == 'British' ? 'selected' : '' }}>British</option>
                                    <option value="Canadian" {{ old('nationality', $student->nationality) == 'Canadian' ? 'selected' : '' }}>Canadian</option>
                                    <option value="Australian" {{ old('nationality', $student->nationality) == 'Australian' ? 'selected' : '' }}>Australian</option>
                                    <option value="Others" {{ old('nationality', $student->nationality) == 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                                @error('nationality')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- NEW: Religion Field -->
                            <div class="col-md-4">
                                <label>Religion</label>
                                <select name="religion" class="form-control">
                                    <option value="">Select Religion</option>
                                    <option value="Hindu" {{ old('religion', $student->religion) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Muslim" {{ old('religion', $student->religion) == 'Muslim' ? 'selected' : '' }}>Muslim</option>
                                    <option value="Christian" {{ old('religion', $student->religion) == 'Christian' ? 'selected' : '' }}>Christian</option>
                                    <option value="Sikh" {{ old('religion', $student->religion) == 'Sikh' ? 'selected' : '' }}>Sikh</option>
                                    <option value="Buddhist" {{ old('religion', $student->religion) == 'Buddhist' ? 'selected' : '' }}>Buddhist</option>
                                    <option value="Jain" {{ old('religion', $student->religion) == 'Jain' ? 'selected' : '' }}>Jain</option>
                                    <option value="Jewish" {{ old('religion', $student->religion) == 'Jewish' ? 'selected' : '' }}>Jewish</option>
                                    <option value="Others" {{ old('religion', $student->religion) == 'Others' ? 'selected' : '' }}>Others</option>
                                    <option value="Not Specified" {{ old('religion', $student->religion) == 'Not Specified' ? 'selected' : '' }}>Not Specified</option>
                                </select>
                                @error('religion')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Blood Group Field -->
                            <div class="col-md-4">
                                <label class="form-label">Blood Group</label>
                                <select name="blood_group" class="form-control">
                                    <option value="">Select Blood Group</option>
                                    <option value="A+" {{ old('blood_group', $student->blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A-" {{ old('blood_group', $student->blood_group) == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ old('blood_group', $student->blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B-" {{ old('blood_group', $student->blood_group) == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="AB+" {{ old('blood_group', $student->blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-" {{ old('blood_group', $student->blood_group) == 'Ab-' ? 'selected' : '' }}>AB-</option>
                                    <option value="O+" {{ old('blood_group', $student->blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="O-" {{ old('blood_group', $student->blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
                                    <option value="Unknown" {{ old('blood_group', $student->blood_group) == 'Unknown' ? 'selected' : '' }}>Unknown</option>
                                    <option value="Not Disclosed" {{ old('blood_group', $student->blood_group) == 'Not Specified' ? 'selected' : '' }}>Not Disclosed</option>
                                </select>
                            </div>
                            <!-- Social Category Field -->
                            <div class="col-md-4">
                                <label class="form-label">Social Category</label>
                                <select name="category" class="form-control">
                                    <option value="">Select Category</option>
                                    <option value="General" {{ old('category', $student->category) == 'General' ? 'selected' : '' }}>General</option>
                                    <option value="OBC" {{ old('category', $student->category) == 'OBC (Other Backward Class)' ? 'selected' : '' }}>OBC (Other Backward Class)</option>
                                    <option value="SC" {{ old('category', $student->category) == 'SC (Scheduled Caste)' ? 'selected' : '' }}>SC (Scheduled Caste)</option>
                                    <option value="ST" {{ old('category', $student->category) == 'ST (Scheduled Tribe)' ? 'selected' : '' }}>ST (Scheduled Tribe)</option>
                                    <option value="Others" {{ old('category', $student->category) == 'Others' ? 'selected' : '' }}>Others</option>
                                    <option value="Not Specified" {{ old('category', $student->category) == 'Not Specified' ? 'selected' : '' }}>Not Specified</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- ===================== PARENT DETAILS ===================== --}}
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Parent's Details</h5>
                        </div>

                        <div class="card-body row g-3">
                            <h4 class="col-md-12">Father's Details</h4>
                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" name="father_first_name" class="form-control" 
                                    value="{{old('father_first_name', $student->father_first_name)}}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="father_middle_name" class="form-control"
                                    value="{{old('father_middle_name', $student->father_middle_name)}}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="father_last_name" class="form-control"
                                    value="{{old('father_last_name', $student->father_last_name)}}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="father_dob" id="father_dob" class="form-control"
                                    value="{{old('father_dob', $student->father_dob)}}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Email ID</label>
                                <input type="email" name="father_email" class="form-control"
                                    value="{{old('father_email', $student->father_email)}}">
                            </div>

                            <div class="col-md-4"> <label class="form-label">Phone No.</label>
                                <div class="input-group"> <span class="input-group-text">
                                        +91</span> <input type="tel" name="father_phone" class="form-control"
                                        placeholder="Enter 10-digit number" pattern="[0-9]{10}"
                                        title="Please enter exactly 10 digits"
                                        value="{{old('father_phone', $student->father_phone)}}">
                                </div>

                                <div class="invalid-feedback">
                                    Please enter a valid 10-digit mobile number
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Occupation</label>
                                <select name="father_occupation" class="form-control">
                                    <option value="">-- Select --</option>
                                    <option value="Government Employee" {{ old('father_occupation', $student->father_occupation) == 'Government Employee' ? 'selected' : '' }}>Government Employee</option>
                                    <option value="Private Sector Employee" {{ old('father_occupation', $student->father_occupation) == 'Private Sector Employee' ? 'selected' : '' }}>Private Sector Employee</option>
                                    <option value="Self-Employed" {{ old('father_occupation', $student->father_occupation) == 'Self-Employed' ? 'selected' : '' }}>Self-Employed</option>
                                    <option value="Business Owner" {{ old('father_occupation', $student->father_occupation) == 'Business Owner' ? 'selected' : '' }}>Business Owner</option>
                                    <option value="Farmer" {{ old('father_occupation', $student->father_occupation) == 'Farmer' ? 'selected' : '' }}>Farmer</option>
                                    <option value="Teacher" {{ old('father_occupation', $student->father_occupation) == 'Teacher' ? 'selected' : '' }}>Teacher</option>
                                    <option value="Doctor" {{ old('father_occupation', $student->father_occupation) == 'Doctor' ? 'selected' : '' }}>Doctor</option>
                                    <option value="Engineer" {{ old('father_occupation', $student->father_occupation) == 'Engineer' ? 'selected' : '' }}>Engineer</option>
                                    <option value="Driver" {{ old('father_occupation', $student->father_occupation) == 'Driver' ? 'selected' : '' }}>Driver</option>
                                    <option value="Housewife" {{ old('father_occupation', $student->father_occupation) == 'Housewife' ? 'selected' : '' }}>Housewife</option>
                                    <option value="Retired" {{ old('father_occupation', $student->father_occupation) == 'Retired' ? 'selected' : '' }}>Retired</option>
                                    <option value="Unemployed" {{ old('father_occupation', $student->father_occupation) == 'Unemployed' ? 'selected' : '' }}>Unemployed</option>
                                    <option value="Other" {{ old('father_occupation', $student->father_occupation) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Annual Income</label>
                                <input type="number" name="father_income" class="form-control" placeholder="In INR"
                                    value="{{old('father_income', $student->father_income)}}">
                            </div>

                            <!-- NEW: Nationality Field -->
                            <div class="col-md-4">
                                <label>Nationality</label>
                                <select name="father_nationality" class="form-control">
                                    <option value="">Select Nationality</option>
                                    <option value="Indian" {{ old('father_nationality', $student->father_nationality) == 'Indian' ? 'selected' : '' }}>Indian</option>
                                    <option value="American" {{ old('father_nationality', $student->father_nationality) == 'American' ? 'selected' : '' }}>American</option>
                                    <option value="British" {{ old('father_nationality', $student->father_nationality) == 'British' ? 'selected' : '' }}>British</option>
                                    <option value="Canadian" {{ old('father_nationality', $student->father_nationality) == 'Canadian' ? 'selected' : '' }}>Canadian</option>
                                    <option value="Australian" {{ old('father_nationality', $student->father_nationality) == 'Australian' ? 'selected' : '' }}>Australian</option>
                                    <option value="Others" {{ old('father_nationality', $student->father_nationality) == 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                            </div>

                            <!-- NEW: Religion Field -->
                            <div class="col-md-4">
                                <label>Religion</label>
                                <select name="father_religion" class="form-control">
                                    <option value="">Select Religion</option>
                                    <option value="Hindu" {{ old('father_religion', $student->father_religion) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Muslim" {{ old('father_religion', $student->father_religion) == 'Muslim' ? 'selected' : '' }}>Muslim</option>
                                    <option value="Christian" {{ old('father_religion', $student->father_religion) == 'Christian' ? 'selected' : '' }}>Christian</option>
                                    <option value="Sikh" {{ old('father_religion', $student->father_religion) == 'Sikh' ? 'selected' : '' }}>Sikh</option>
                                    <option value="Buddhist" {{ old('father_religion', $student->father_religion) == 'Buddhist' ? 'selected' : '' }}>Buddhist</option>
                                    <option value="Jain" {{ old('father_religion', $student->father_religion) == 'Jain' ? 'selected' : '' }}>Jain</option>
                                    <option value="Jewish" {{ old('father_religion', $student->father_religion) == 'Jewish' ? 'selected' : '' }}>Jewish</option>
                                    <option value="Others" {{ old('father_religion', $student->father_religion) == 'Others' ? 'selected' : '' }}>Others</option>
                                    <option value="Not Specified" {{ old('father_religion', $student->father_religion) == 'Not Specified' ? 'selected' : '' }}>Not Specified</option>
                                </select>
                            </div>

                            <!-- Blood Group Field -->
                            <div class="col-md-4">
                                <label class="form-label">Blood Group</label>
                                <select name="father_blood_group" class="form-control">
                                    <option value="">Select Blood Group</option>
                                    <option value="A+" {{ old('father_blood_group', $student->father_blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A-" {{ old('father_blood_group', $student->father_blood_group) == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ old('father_blood_group', $student->father_blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B-" {{ old('father_blood_group', $student->father_blood_group) == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="AB+" {{ old('father_blood_group', $student->father_blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-" {{ old('father_blood_group', $student->father_blood_group) == 'Ab-' ? 'selected' : '' }}>AB-</option>
                                    <option value="O+" {{ old('father_blood_group', $student->father_blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="O-" {{ old('father_blood_group', $student->father_blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
                                    <option value="Unknown" {{ old('father_blood_group', $student->father_blood_group) == 'Unknown' ? 'selected' : '' }}>Unknown</option>
                                    <option value="Not Disclosed" {{ old('father_blood_group', $student->father_blood_group) == 'Not Specified' ? 'selected' : '' }}>Not Disclosed</option>
                                </select>
                            </div>

                            <!-- Social Category Field -->
                            <div class="col-md-4">
                                <label class="form-label">Social Category</label>
                                <select name="father_category" class="form-control">
                                    <option value="">Select Category</option>
                                    <option value="General" {{ old('father_category', $student->father_category) == 'General' ? 'selected' : '' }}>General</option>
                                    <option value="OBC" {{ old('father_category', $student->father_category) == 'OBC (Other Backward Class)' ? 'selected' : '' }}>OBC (Other Backward Class)</option>
                                    <option value="SC" {{ old('father_category', $student->father_category) == 'SC (Scheduled Caste)' ? 'selected' : '' }}>SC (Scheduled Caste)</option>
                                    <option value="ST" {{ old('father_category', $student->father_category) == 'ST (Scheduled Tribe)' ? 'selected' : '' }}>ST (Scheduled Tribe)</option>
                                    <option value="Others" {{ old('father_category', $student->father_category) == 'Others' ? 'selected' : '' }}>Others</option>
                                    <option value="Not Specified" {{ old('father_category', $student->father_category) == 'Not Specified' ? 'selected' : '' }}>Not Specified</option>
                                </select>
                            </div>
                        </div>

                        <div class="card-body row g-3">
                            <h4 class="col-md-12">Mother's Details</h4>
                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" name="mother_first_name" class="form-control" 
                                    value="{{old('mother_first_name', $student->mother_first_name)}}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="mother_middle_name" class="form-control"
                                    value="{{old('mother_middle_name', $student->mother_middle_name)}}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="mother_last_name" class="form-control"
                                    value="{{old('mother_last_name', $student->mother_last_name)}}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="mother_dob" id="mother_dob" class="form-control"
                                    value="{{old('mother_dob', $student->mother_dob)}}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Email ID</label>
                                <input type="email" name="mother_email" class="form-control" 
                                    value="{{old('mother_email', $student->mother_email)}}">
                            </div>

                            <div class="col-md-4"> <label class="form-label">Phone Number</label>
                                <div class="input-group"> <span class="input-group-text">
                                        +91</span> <input type="tel" name="mother_phone" class="form-control"
                                        placeholder="Enter 10-digit number" pattern="[0-9]{10}"
                                        title="Please enter exactly 10 digits" 
                                        value="{{old('mother_phone', $student->mother_phone)}}">
                                </div>

                                <div class="invalid-feedback">
                                    Please enter a valid 10-digit mobile number
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Occupation</label>
                                <select name="mother_occupation" class="form-control">
                                    <option value="">-- Select --</option>
                                    <option value="Government Employee" {{ old('mother_occupation', $student->mother_occupation) == 'Government Employee' ? 'selected' : '' }}>Government Employee</option>
                                    <option value="Private Sector Employee" {{ old('mother_occupation', $student->mother_occupation) == 'Private Sector Employee' ? 'selected' : '' }}>Private Sector Employee</option>
                                    <option value="Self-Employed" {{ old('mother_occupation', $student->mother_occupation) == 'Self-Employed' ? 'selected' : '' }}>Self-Employed</option>
                                    <option value="Business Owner" {{ old('mother_occupation', $student->mother_occupation) == 'Business Owner' ? 'selected' : '' }}>Business Owner</option>
                                    <option value="Farmer" {{ old('mother_occupation', $student->mother_occupation) == 'Farmer' ? 'selected' : '' }}>Farmer</option>
                                    <option value="Teacher" {{ old('mother_occupation', $student->mother_occupation) == 'Teacher' ? 'selected' : '' }}>Teacher</option>
                                    <option value="Doctor" {{ old('mother_occupation', $student->mother_occupation) == 'Doctor' ? 'selected' : '' }}>Doctor</option>
                                    <option value="Engineer" {{ old('mother_occupation', $student->mother_occupation) == 'Engineer' ? 'selected' : '' }}>Engineer</option>
                                    <option value="Driver" {{ old('mother_occupation', $student->mother_occupation) == 'Driver' ? 'selected' : '' }}>Driver</option>
                                    <option value="Housewife" {{ old('mother_occupation', $student->mother_occupation) == 'Housewife' ? 'selected' : '' }}>Housewife</option>
                                    <option value="Retired" {{ old('mother_occupation', $student->mother_occupation) == 'Retired' ? 'selected' : '' }}>Retired</option>
                                    <option value="Unemployed" {{ old('mother_occupation', $student->mother_occupation) == 'Unemployed' ? 'selected' : '' }}>Unemployed</option>
                                    <option value="Other" {{ old('mother_occupation', $student->mother_occupation) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Annual Income</label>
                                <input type="number" name="mother_income" class="form-control"
                                placeholder="In INR" value="{{old('mother_income', $student->mother_income)}}">
                            </div>

                            <!-- NEW: Nationality Field -->
                            <div class="col-md-4">
                                <label>Nationality</label>
                                <select name="mother_nationality" class="form-control">
                                    <option value="">Select Nationality</option>
                                    <option value="Indian" {{ old('mother_nationality', $student->mother_nationality) == 'Indian' ? 'selected' : '' }}>Indian</option>
                                    <option value="American" {{ old('mother_nationality', $student->mother_nationality) == 'American' ? 'selected' : '' }}>American</option>
                                    <option value="British" {{ old('mother_nationality', $student->mother_nationality) == 'British' ? 'selected' : '' }}>British</option>
                                    <option value="Canadian" {{ old('mother_nationality', $student->mother_nationality) == 'Canadian' ? 'selected' : '' }}>Canadian</option>
                                    <option value="Australian" {{ old('mother_nationality', $student->mother_nationality) == 'Australian' ? 'selected' : '' }}>Australian</option>
                                    <option value="Others" {{ old('mother_nationality', $student->mother_nationality) == 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                            </div>

                            <!-- NEW: Religion Field -->
                            <div class="col-md-4">
                                <label>Religion</label>
                                <select name="mother_religion" class="form-control">
                                    <option value="">Select Religion</option>
                                    <option value="Hindu" {{ old('mother_religion', $student->mother_religion) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Muslim" {{ old('mother_religion', $student->mother_religion) == 'Muslim' ? 'selected' : '' }}>Muslim</option>
                                    <option value="Christian" {{ old('mother_religion', $student->mother_religion) == 'Christian' ? 'selected' : '' }}>Christian</option>
                                    <option value="Sikh" {{ old('mother_religion', $student->mother_religion) == 'Sikh' ? 'selected' : '' }}>Sikh</option>
                                    <option value="Buddhist" {{ old('mother_religion', $student->mother_religion) == 'Buddhist' ? 'selected' : '' }}>Buddhist</option>
                                    <option value="Jain" {{ old('mother_religion', $student->mother_religion) == 'Jain' ? 'selected' : '' }}>Jain</option>
                                    <option value="Jewish" {{ old('mother_religion', $student->mother_religion) == 'Jewish' ? 'selected' : '' }}>Jewish</option>
                                    <option value="Others" {{ old('mother_religion', $student->mother_religion) == 'Others' ? 'selected' : '' }}>Others</option>
                                    <option value="Not Specified" {{ old('mother_religion', $student->mother_religion) == 'Not Specified' ? 'selected' : '' }}>Not Specified</option>
                                </select>
                            </div>

                            <!-- Blood Group Field -->
                            <div class="col-md-4">
                                <label class="form-label">Blood Group</label>
                                <select name="mother_blood_group" class="form-control">
                                    <option value="">Select Blood Group</option>
                                    <option value="A+" {{ old('mother_blood_group', $student->mother_blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A-" {{ old('mother_blood_group', $student->mother_blood_group) == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ old('mother_blood_group', $student->mother_blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B-" {{ old('mother_blood_group', $student->mother_blood_group) == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="AB+" {{ old('mother_blood_group', $student->mother_blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-" {{ old('mother_blood_group', $student->mother_blood_group) == 'Ab-' ? 'selected' : '' }}>AB-</option>
                                    <option value="O+" {{ old('mother_blood_group', $student->mother_blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="O-" {{ old('mother_blood_group', $student->mother_blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
                                    <option value="Unknown" {{ old('mother_blood_group', $student->mother_blood_group) == 'Unknown' ? 'selected' : '' }}>Unknown</option>
                                    <option value="Not Disclosed" {{ old('mother_blood_group', $student->mother_blood_group) == 'Not Specified' ? 'selected' : '' }}>Not Disclosed</option>
                                </select>
                            </div>
                            
                            <!-- Social Category Field -->
                            <div class="col-md-4">
                                <label class="form-label">Social Category</label>
                                <select name="mother_category" class="form-control">
                                    <option value="">Select Category</option>
                                    <option value="General" {{ old('mother_category', $student->mother_category) == 'General' ? 'selected' : '' }}>General</option>
                                    <option value="OBC" {{ old('mother_category', $student->mother_category) == 'OBC (Other Backward Class)' ? 'selected' : '' }}>OBC (Other Backward Class)</option>
                                    <option value="SC" {{ old('mother_category', $student->mother_category) == 'SC (Scheduled Caste)' ? 'selected' : '' }}>SC (Scheduled Caste)</option>
                                    <option value="ST" {{ old('mother_category', $student->mother_category) == 'ST (Scheduled Tribe)' ? 'selected' : '' }}>ST (Scheduled Tribe)</option>
                                    <option value="Others" {{ old('mother_category', $student->mother_category) == 'Others' ? 'selected' : '' }}>Others</option>
                                    <option value="Not Specified" {{ old('mother_category', $student->mother_category) == 'Not Specified' ? 'selected' : '' }}>Not Specified</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- ===================== GUARDIAN DETAILS ===================== --}}
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Guardian Details</h5>
                        </div>

                        <div class="card-body guardianSelectContainer">
                            <label class="form-label d-block"><b>Select Guardian</b></label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="guardian_type" id="guardian_father"
                                    value="father" {{ old('guardian_type', $student->guardian_type) == 'Father' ? 'selected' : '' }}>
                                <label class="form-check-label" for="guardian_father">Father</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="guardian_type" id="guardian_mother"
                                    value="mother" {{ old('guardian_type', $student->guardian_type) == 'Mother' ? 'selected' : '' }}>
                                <label class="form-check-label" for="guardian_mother">Mother</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="guardian_type" id="guardian_other"
                                    value="other" {{ old('guardian_type', $student->guardian_type) == 'Different / Other Person' ? 'selected' : '' }}>
                                <label class="form-check-label" for="guardian_other">Different / Other Person</label>
                            </div>
                        </div>

                        <div class="card-body row g-3 guardianContainer d-none">
                            <div class="col-md-4">
                                <label class="form-label">Relation with Student</label>
                                <input type="text" name="guardian_relation" class="form-control"
                                    value="{{old('guardian_relation', $student->guardian_relation)}}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" name="guardian_first_name" class="form-control"
                                    value="{{old('guardian_first_name', $student->guardian_first_name)}}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="guardian_middle_name" class="form-control"
                                    value="{{old('guardian_middle_name', $student->guardian_middle_name)}}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="guardian_last_name" class="form-control"
                                    value="{{old('guardian_last_name', $student->guardian_last_name)}}">
                            </div>

                            <div class="col-md-4">
                                <label>Gender</label>
                                <select name="guardian_gender" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Male" {{ old('guardian_gender', $student->guardian_gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('guardian_gender', $student->guardian_gender) == 'Male' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('guardian_gender', $student->guardian_gender) == 'Male' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="guardian_dob" id="guardian_dob" class="form-control"
                                    value="{{old('guardian_dob', $student->guardian_dob)}}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Email</label>
                                <input type="email" name="guardian_email" class="form-control"
                                    value="{{old('guardian_email', $student->guardian_email)}}">
                            </div>

                            <div class="col-md-4"> <label class="form-label">Phone</label>
                                <div class="input-group"> <span class="input-group-text">
                                        +91</span> <input type="tel" name="guardian_phone" class="form-control"
                                        placeholder="Enter 10-digit number" pattern="[0-9]{10}"
                                        title="Please enter exactly 10 digits"
                                        value="{{old('guardian_phone', $student->guardian_phone)}}">
                                </div>

                                <div class="invalid-feedback">
                                    Please enter a valid 10-digit mobile number
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Alternate Phone</label>
                                <input type="tel" name="guardian_alternate_phone_number" class="form-control"
                                    value="{{old('guardian_alternate_phone_number', $student->guardian_alternate_phone_number)}}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Occupation</label>
                                <select name="guardian_occupation" class="form-control">
                                    <option value="">-- Select --</option>
                                    <option value="Government Employee" {{ old('guardian_occupation', $student->guardian_occupation) == 'Government Employee' ? 'selected' : '' }}>Government Employee</option>
                                    <option value="Private Sector Employee" {{ old('guardian_occupation', $student->guardian_occupation) == 'Private Sector Employee' ? 'selected' : '' }}>Private Sector Employee</option>
                                    <option value="Self-Employed" {{ old('guardian_occupation', $student->guardian_occupation) == 'Self-Employed' ? 'selected' : '' }}>Self-Employed</option>
                                    <option value="Business Owner" {{ old('guardian_occupation', $student->guardian_occupation) == 'Business Owner' ? 'selected' : '' }}>Business Owner</option>
                                    <option value="Farmer" {{ old('guardian_occupation', $student->guardian_occupation) == 'Farmer' ? 'selected' : '' }}>Farmer</option>
                                    <option value="Teacher" {{ old('guardian_occupation', $student->guardian_occupation) == 'Teacher' ? 'selected' : '' }}>Teacher</option>
                                    <option value="Doctor" {{ old('guardian_occupation', $student->guardian_occupation) == 'Doctor' ? 'selected' : '' }}>Doctor</option>
                                    <option value="Engineer" {{ old('guardian_occupation', $student->guardian_occupation) == 'Engineer' ? 'selected' : '' }}>Engineer</option>
                                    <option value="Driver" {{ old('guardian_occupation', $student->guardian_occupation) == 'Driver' ? 'selected' : '' }}>Driver</option>
                                    <option value="Housewife" {{ old('guardian_occupation', $student->guardian_occupation) == 'Housewife' ? 'selected' : '' }}>Housewife</option>
                                    <option value="Retired" {{ old('guardian_occupation', $student->guardian_occupation) == 'Retired' ? 'selected' : '' }}>Retired</option>
                                    <option value="Unemployed" {{ old('guardian_occupation', $student->guardian_occupation) == 'Unemployed' ? 'selected' : '' }}>Unemployed</option>
                                    <option value="Other" {{ old('guardian_occupation', $student->guardian_occupation) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Annual Income</label>
                                <input type="number" name="guardian_income" class="form-control" placeholder="In INR"
                                    value="{{old('guardian_income', $student->guardian_income)}}">
                            </div>

                            <!-- NEW: Nationality Field -->
                            <div class="col-md-4">
                                <label>Nationality</label>
                                <select name="guardian_nationality" class="form-control">
                                    <option value="">Select Nationality</option>
                                    <option value="Indian" {{ old('guardian_nationality', $student->guardian_nationality) == 'Indian' ? 'selected' : '' }}>Indian</option>
                                    <option value="American" {{ old('guardian_nationality', $student->guardian_nationality) == 'American' ? 'selected' : '' }}>American</option>
                                    <option value="British" {{ old('guardian_nationality', $student->guardian_nationality) == 'British' ? 'selected' : '' }}>British</option>
                                    <option value="Canadian" {{ old('guardian_nationality', $student->guardian_nationality) == 'Canadian' ? 'selected' : '' }}>Canadian</option>
                                    <option value="Australian" {{ old('guardian_nationality', $student->guardian_nationality) == 'Australian' ? 'selected' : '' }}>Australian</option>
                                    <option value="Others" {{ old('guardian_nationality', $student->guardian_nationality) == 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                            </div>

                            <!-- NEW: Religion Field -->
                            <div class="col-md-4">
                                <label>Religion</label>
                                <select name="guardian_religion" class="form-control">
                                    <option value="">Select Religion</option>
                                    <option value="Hindu" {{ old('guardian_religion', $student->guardian_religion) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Muslim" {{ old('guardian_religion', $student->guardian_religion) == 'Muslim' ? 'selected' : '' }}>Muslim</option>
                                    <option value="Christian" {{ old('guardian_religion', $student->guardian_religion) == 'Christian' ? 'selected' : '' }}>Christian</option>
                                    <option value="Sikh" {{ old('guardian_religion', $student->guardian_religion) == 'Sikh' ? 'selected' : '' }}>Sikh</option>
                                    <option value="Buddhist" {{ old('guardian_religion', $student->guardian_religion) == 'Buddhist' ? 'selected' : '' }}>Buddhist</option>
                                    <option value="Jain" {{ old('guardian_religion', $student->guardian_religion) == 'Jain' ? 'selected' : '' }}>Jain</option>
                                    <option value="Jewish" {{ old('guardian_religion', $student->guardian_religion) == 'Jewish' ? 'selected' : '' }}>Jewish</option>
                                    <option value="Others" {{ old('guardian_religion', $student->guardian_religion) == 'Others' ? 'selected' : '' }}>Others</option>
                                    <option value="Not Specified" {{ old('guardian_religion', $student->guardian_religion) == 'Not Specified' ? 'selected' : '' }}>Not Specified</option>
                                </select>
                            </div>

                            <!-- Blood Group Field -->
                            <div class="col-md-4">
                                <label class="form-label">Blood Group</label>
                                <select name="guardian_blood_group" class="form-control">
                                    <option value="">Select Blood Group</option>
                                    <option value="A+" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A-" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B-" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="AB+" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'Ab-' ? 'selected' : '' }}>AB-</option>
                                    <option value="O+" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="O-" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
                                    <option value="Unknown" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'Unknown' ? 'selected' : '' }}>Unknown</option>
                                    <option value="Not Disclosed" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'Not Specified' ? 'selected' : '' }}>Not Disclosed</option>
                                </select>
                            </div>
                            
                            <!-- Social Category Field -->
                            <div class="col-md-4">
                                <label class="form-label">Social Category</label>
                                <select name="guardian_category" class="form-control">
                                    <option value="">Select Category</option>
                                    <option value="General" {{ old('guardian_category', $student->guardian_category) == 'General' ? 'selected' : '' }}>General</option>
                                    <option value="OBC" {{ old('guardian_category', $student->guardian_category) == 'OBC (Other Backward Class)' ? 'selected' : '' }}>OBC (Other Backward Class)</option>
                                    <option value="SC" {{ old('guardian_category', $student->guardian_category) == 'SC (Scheduled Caste)' ? 'selected' : '' }}>SC (Scheduled Caste)</option>
                                    <option value="ST" {{ old('guardian_category', $student->guardian_category) == 'ST (Scheduled Tribe)' ? 'selected' : '' }}>ST (Scheduled Tribe)</option>
                                    <option value="Others" {{ old('guardian_category', $student->guardian_category) == 'Others' ? 'selected' : '' }}>Others</option>
                                    <option value="Not Specified" {{ old('guardian_category', $student->guardian_category) == 'Not Specified' ? 'selected' : '' }}>Not Specified</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-navigation">
                        <span></span>
                        <button type="submit" class="btn btn-primary">Next</button>
                    </div>
                </form>

                <!-- Form 2 -->
                <form id="form2" style="display:none;">
                    <!-- <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Step 2: Address Details</h4>
                    </div> -->

                    {{-- ===================== STUDENT ADDRESSES ===================== --}}
                    <div class="card mb-4 shadow-sm">
                        <!-- STUDENT DOCUMENTS -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Student Address</h5>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Permanent Address</h6>
                                    <div class="mb-3">
                                        <input type="text" name="student_perm_address_line1" class="form-control"
                                            placeholder="Address Line 1" 
                                            value="{{old('student_perm_address_line1', $student->address->student_perm_address_line1)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="student_perm_address_line2" class="form-control"
                                            placeholder="Address Line 2"
                                            value="{{old('student_perm_address_line2', $student->address->student_perm_address_line2)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="student_perm_city" class="form-control"
                                            placeholder="City" 
                                            value="{{old('student_perm_city', $student->address->student_perm_city)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="student_perm_state" class="form-control"
                                            placeholder="State" 
                                            value="{{old('student_perm_state', $student->address->student_perm_state)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="student_perm_pincode" class="form-control"
                                            placeholder="Pincode" pattern="[0-9]{6}" 
                                            value="{{old('student_perm_pincode', $student->address->student_perm_pincode)}}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6>Communication Address</h6>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="copyStudentAddress" disabled>
                                            <label class="form-check-label mt-0" for="copyStudentAddress">Same as
                                                Permanent</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="student_comm_address_line1" class="form-control"
                                            placeholder="Address Line 1"
                                            value="{{old('student_comm_address_line1', $student->address->student_comm_address_line1)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="student_comm_address_line2" class="form-control"
                                            placeholder="Address Line 2"
                                            value="{{old('student_comm_address_line2', $student->address->student_comm_address_line2)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="student_comm_city" class="form-control"
                                            placeholder="City"
                                            value="{{old('student_comm_city', $student->address->student_comm_city)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="student_comm_state" class="form-control"
                                            placeholder="State"
                                            value="{{old('student_comm_state', $student->address->student_comm_state)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="student_comm_pincode" class="form-control"
                                            placeholder="Pincode"
                                            value="{{old('student_comm_pincode', $student->address->student_comm_pincode)}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="copyStudentToParent" disabled>
                        <label class="form-check-label fw-semibold mt-0" for="copyStudentToParent">
                            Use Same Address as Student
                        </label>
                    </div>

                    {{-- ===================== FATHERS ADDRESSES ===================== --}}
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5>Father's Address</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Permanent Address</h6>
                                    <div class="mb-3">
                                        <input type="text" name="parent_perm_address_line1" class="form-control"
                                            placeholder="Address Line 1" 
                                            value="{{old('parent_perm_address_line1', $student->address->parent_perm_address_line1)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="parent_perm_address_line2" class="form-control"
                                            placeholder="Address Line 2"
                                            value="{{old('parent_perm_address_line2', $student->address->parent_perm_address_line2)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="parent_perm_city" class="form-control"
                                            placeholder="City"
                                            value="{{old('parent_perm_city', $student->address->parent_perm_city)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="parent_perm_state" class="form-control"
                                            placeholder="State"
                                            value="{{old('parent_perm_state', $student->address->parent_perm_state)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="parent_perm_pincode" class="form-control"
                                            placeholder="Pincode" pattern="[0-9]{6}" 
                                            value="{{old('parent_perm_pincode', $student->address->parent_perm_pincode)}}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6>Communication Address</h6>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="copyParentAddress" disabled>
                                            <label class="form-check-label mt-0" for="copyParentAddress">Same as
                                                Permanent</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="parent_comm_address_line1" class="form-control"
                                            placeholder="Address Line 1"
                                            value="{{old('parent_comm_address_line1', $student->address->parent_comm_address_line1)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="parent_comm_address_line2" class="form-control"
                                            placeholder="Address Line 2"
                                            value="{{old('parent_comm_address_line2', $student->address->parent_comm_address_line2)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="parent_comm_city" class="form-control"
                                            placeholder="City"
                                            value="{{old('parent_comm_city', $student->address->parent_comm_city)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="parent_comm_state" class="form-control"
                                            placeholder="State"
                                            value="{{old('parent_comm_state', $student->address->parent_comm_state)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="parent_comm_pincode" class="form-control"
                                            placeholder="Pincode"
                                            value="{{old('parent_comm_pincode', $student->address->parent_comm_pincode)}}">
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="motherAddressCheckbox" checked>
                                <label class="form-check-label fw-semibold mt-0" for="copyStudentToParent">
                                    Use Same Address as Father
                                </label>
                            </div> -->

                            <!-- <h4>Mother's Address</h4>
                            <div class="row motherAddressContainer d-none">
                                <div class="col-md-6">
                                    <h6>Permanent Address</h6>
                                    <div class="mb-3">
                                        <input type="text" name="parent_perm_address_line1" class="form-control"
                                            placeholder="Address Line 1">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="parent_perm_address_line2" class="form-control"
                                            placeholder="Address Line 2">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="parent_perm_city" class="form-control"
                                            placeholder="City">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="parent_perm_state" class="form-control"
                                            placeholder="State">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="parent_perm_pincode" class="form-control"
                                            placeholder="Pincode" pattern="[0-9]{6}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6>Communication Address</h6>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="copyParentAddress">
                                            <label class="form-check-label mt-0" for="copyParentAddress">Same as
                                                Permanent</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="parent_comm_address_line1" class="form-control"
                                            placeholder="Address Line 1">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="parent_comm_address_line2" class="form-control"
                                            placeholder="Address Line 2">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="parent_comm_city" class="form-control"
                                            placeholder="City">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="parent_comm_state" class="form-control"
                                            placeholder="State">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="parent_comm_pincode" class="form-control"
                                            placeholder="Pincode">
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>

                    {{-- ===================== GUARDIAN ADDRESSES ===================== --}}
                    <div class="card mb-4 shadow-sm guardian-address-container d-none">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Guardian Address</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Permanent Address</h6>
                                    <div class="mb-3">
                                        <input type="text" name="guardian_perm_address_line1" class="form-control"
                                            placeholder="Address Line 1"
                                            value="{{old('guardian_perm_address_line1', $student->address->guardian_perm_address_line1)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="guardian_perm_address_line2" class="form-control"
                                            placeholder="Address Line 2"
                                            value="{{old('guardian_perm_address_line2', $student->address->guardian_perm_address_line2)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="guardian_perm_city" class="form-control"
                                            placeholder="City"
                                            value="{{old('guardian_perm_city', $student->address->guardian_perm_city)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="guardian_perm_state" class="form-control"
                                            placeholder="State"
                                            value="{{old('guardian_perm_state', $student->address->guardian_perm_state)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="guardian_perm_pincode" class="form-control"
                                            placeholder="Pincode" pattern="[0-9]{6}"
                                            value="{{old('guardian_perm_pincode', $student->address->guardian_perm_pincode)}}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6>Communication Address</h6>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="copyParentAddress"
                                                value="{{old('parent_comm_pincode', $student->address->parent_comm_pincode)}}">
                                            <label class="form-check-label mt-0" for="copyParentAddress">Same as
                                                Permanent</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="guardian_comm_address_line1" class="form-control"
                                            placeholder="Address Line 1"
                                            value="{{old('guardian_comm_address_line1', $student->address->guardian_comm_address_line1)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="guardian_comm_address_line2" class="form-control"
                                            placeholder="Address Line 2"
                                            value="{{old('guardian_comm_address_line2', $student->address->guardian_comm_address_line2)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="guardian_comm_city" class="form-control"
                                            placeholder="City"
                                            value="{{old('guardian_comm_city', $student->address->guardian_comm_city)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="guardian_comm_state" class="form-control"
                                            placeholder="State"
                                            value="{{old('guardian_comm_state', $student->address->guardian_comm_state)}}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="guardian_comm_pincode" class="form-control"
                                            placeholder="Pincode"
                                            value="{{old('guardian_comm_pincode', $student->address->guardian_comm_pincode)}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-navigation">
                        <button type="button" class="btn btn-secondary" id="backToForm1">Back</button>
                        <button type="submit" class="btn btn-primary">Next</button>
                    </div>
                </form>

                <!-- Form 3 -->
                <form id="form3" style="display:none;">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Step 3: Institute Details</h5>
                        </div>

                        <!-- Academic Details -->
                        <div class="card-body row g-3">
                            <!-- Department Category Selection -->
                            <div class="col-md-6">
                                <label class="form-label">Department Category</label>
                                <select name="department_category_id" id="department_category_id" class="form-control"
                                     disabled>
                                    <option value="">-- Select Department Category --</option>
                                    @foreach($departmentCategories as $category)
                                    <option value="{{ $category->department_category_id }}"
                                        {{ old('department_category_id', $extra->department_category_id) == $category->department_category_id ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Department Selection -->
                            <div class="col-md-6">
                                <label>Department</label>
                                <input name="department" class="form-control" id="department"
                                    value="{{ old('department', $extra->department)}}" disabled>
                            </div>

                            <!-- Course Type Selection -->
                            <div class="col-md-6">
                                <label>{{$courseLabel}} Type</label>
                                <input type="text" name="course_type" id="course_type"
                                    class="form-control" value="{{ old('course_type', $extra->course_type)}}" disabled>
                            </div>

                            <!-- Sub Type Selection -->
                            <div class="col-md-6">
                                <label>{{$courseLabel}}  Sub Type</label>
                                <input type="text" name="course_subtype" id="course_subtype" 
                                    class="form-control" value="{{ old('course_subtype', $extra->course_subtype)}}" disabled>
                            </div>

                            <!-- Hidden fields -->
                            <input type="hidden" name="course_detail_id" id="course_detail_id">

                            <!-- Batch as Readonly Input -->
                            <div class="col-md-6">
                                <label>Batch</label>
                                <input type="text" name="batch" id="batch_name" class="form-control" disabled
                                    value="{{ old('batch', $extra->batch)}}">
                                <input type="hidden" name="batch_id" id="batch_id">
                            </div>

                            <!-- Academic Year as Select Dropdown -->
                            <div class="col-md-6">
                                <label>Academic Year</label>
                                <input type="text" name="academic_year" id="academic_year_name"
                                    class="form-control" value="{{ old('academic_year', $extra->academic_year)}}" disabled>
                            </div>

                            <!-- Course Mode -->
                            <div class="col-md-6">
                                <label>{{$courseLabel}}  Mode</label>
                                <input type="text" name="mode_of_course" id="mode_of_course"
                                    class="form-control" value="{{ old('mode_of_course', $extra->mode_of_course)}}" disabled>
                            </div>

                            <!-- Mode Type -->
                            <div class="col-md-6">
                                <label>Mode Type</label>
                                <input type="text" name="mode_type" id="mode_type"
                                    class="form-control" value="{{ old('mode_type', $extra->mode_type)}}" disabled>
                            </div>

                            <div class="col-md-6">
                                <label>Semester/Terms</label>
                                <input type="text" name="semester_id" id="semester_id"
                                    class="form-control" value="{{ old('semester_id', $extra->semester_id)}}" disabled>
                            </div>

                            <div class="col-md-6">
                                <label>Section</label>
                                <input type="text" name="section_id" id="section_id"
                                    class="form-control" value="{{ old('section_id', $extra->section_id)}}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="form-navigation">
                        <button type="button" class="btn btn-secondary" id="back2">Back</button>
                        <button type="submit" class="btn btn-primary">Next</button>
                    </div>
                </form>

                <!-- Form 4 -->
                <form id="form4" style="display:none;" enctype="multipart/form-data">
                    <!-- <h4 class="mb-4">Step 4: Upload Documents</h4> -->
                    <!-- STUDENT DOCUMENTS -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Student Documents</h5>
                        </div>
                        <div class="card-body row g-3" id="student-documents-container">

                            <div class="col-md-6">
                                <label class="form-label">Aadhaar Number</label>
                                <input type="text" name="student_aadhaar_number" class="form-control" maxlength="12"
                                    pattern="\d{12}" placeholder="Enter 12-digit Aadhaar number"
                                    value="{{ old('student_aadhaar_number', $documents->student_aadhaar_number)}}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Upload Aadhaar Card</label>
                                <input type="file" name="student_aadhaar_file" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">PAN Number</label>
                                <input type="text" name="student_pan_number" class="form-control" maxlength="10"
                                    pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" title="PAN format: ABCDE1234F"
                                    placeholder="ABCDE1234F"
                                    value="{{ old('student_pan_number', $documents->student_pan_number)}}">
                                <div class="invalid-feedback">PAN must be in format ABCDE1234F</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Upload PAN Card</label>
                                <input type="file" name="student_pan_file" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Student Photo <span class="text-danger">*</span></label>
                                <input type="file" name="student_photo" class="form-control" accept="image/*">
                            </div>

                            <div class="col-md-6">
                                <label>Upload Student ID Card <small class="text-muted">(optional)</small></label>
                                <input type="file" name="student_id_card" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Address Proof (Optional)</label>
                                <input type="file" name="student_address_proof" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Birth Certificate</label>
                                <input type="file" name="student_bonafide" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>

                            <!-- Academic Documents will be injected here by JS -->
                            <div id="academic-documents-container" class="row g-3"></div>

                            <!-- Other Documents Section -->
                            <div class="col-12 d-flex s align-items-center">
                                <label class="form-label mb-0">Other Documents</label>
                                <button type="button" style="margin-left: 20px; margin-top: 20px; margin-bottom: 20px;"
                                    class="btn btn-sm btn-outline-primary" id="addOtherDocBtn">
                                    <i class="bi bi-plus-lg"></i> Add
                                </button>
                            </div>

                            <div id="other-documents-wrapper" class="row g-3">
                                <!-- Initial Other Document Field -->
                                <div class="col-md-6 other-doc-group">
                                    <label class="form-label">Other Document Name</label>
                                    <input type="text" name="student_other_doc_label_1" class="form-control"
                                        placeholder="Enter document name">
                                </div>
                                <div class="col-md-6 other-doc-group d-flex align-items-end">
                                    <div class="w-100">
                                        <label class="form-label">Upload Other Document <small
                                                class="text-muted">(optional)</small></label>
                                        <input type="file" name="student_other_doc_file_1" class="form-control"
                                            accept=".pdf,.jpg,.jpeg,.png">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PARENT DOCUMENTS -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Parent Documents</h5>
                        </div>

                        <div class="card-body row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Aadhaar Number</label>
                                <input type="text" name="parent_aadhaar_number" class="form-control" maxlength="12"
                                    pattern="\d{12}" placeholder="Enter 12-digit Aadhaar number"
                                    value="{{ old('parent_aadhaar_number', $documents->parent_aadhaar_number)}}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Upload Aadhaar Card</label>
                                <input type="file" name="parent_aadhaar_file" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">PAN Number</label>
                                <input type="text" name="parent_pan_number" class="form-control" maxlength="10"
                                    placeholder="ABCDE1234F"
                                    value="{{ old('parent_pan_number', $documents->parent_pan_number)}}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Upload PAN Card</label>
                                <input type="file" name="parent_pan_file" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Income Proof</label>
                                <input type="file" name="parent_income_proof" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Photo</label>
                                <input type="file" name="parent_photo" class="form-control" accept="image/*">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Address Proof (Optional)</label>
                                <input type="file" name="parent_address_proof" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>

                        <!-- GUARDIAN DOCUMENTS -->
                        <div class="otherDocumentsContainer d-none">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Guardian Documents</h5>
                            </div>

                            <div class="card-body row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Aadhaar Number</label>
                                    <input type="text" name="guardian_aadhaar_number" class="form-control"
                                        maxlength="12" pattern="\d{12}" placeholder="Enter 12-digit Aadhaar number"
                                        value="{{ old('guardian_aadhaar_number', $documents->guardian_aadhaar_number)}}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Upload Aadhaar Card</label>
                                    <input type="file" name="guardian_aadhaar_file" class="form-control"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">PAN Number</label>
                                    <input type="text" name="guardian_pan_number" class="form-control" maxlength="10"
                                        placeholder="ABCDE1234F"
                                        value="{{ old('guardian_pan_number', $documents->guardian_pan_number)}}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Upload PAN Card</label>
                                    <input type="file" name="guardian_pan_file" class="form-control"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Income Proof</label>
                                    <input type="file" name="guardian_income_proof" class="form-control"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Photo</label>
                                    <input type="file" name="guardian_photo" class="form-control" accept="image/*">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Address Proof (Optional)</label>
                                    <input type="file" name="guardian_address_proof" class="form-control"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-navigation">
                        <button type="button" class="btn btn-secondary" id="back3">Back</button>
                        <button type="submit" class="btn btn-primary">Next</button>
                    </div>
                </form>

                <!-- Form 5 -->
                <form id="form5" style="display:none;">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Parent Bank Details</h5>
                        </div>

                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Beneficiary Name</label>
                                    <input type="text" name="benificiary_name" class="form-control" 
                                        value="{{ old('benificiary_name', $bank->benificiary_name)}}"/>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Bank Account Number</label>
                                    <input type="text" name="bank_account_number" pattern="[0-9]{9,18}"
                                        class="form-control" 
                                        value="{{ old('bank_account_number', $bank->bank_account_number)}}"/>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control" 
                                        value="{{ old('bank_name', $bank->bank_name)}}"/>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">IFSC Code</label>
                                    <input type="text" name="ifsc_code" class="form-control"
                                        pattern="[A-Z]{4}0[A-Z0-9]{6}" title="IFSC format: ABCD0123456" 
                                        value="{{ old('ifsc_code', $bank->ifsc_code)}}"/>
                                    <div class="invalid-feedback">IFSC must be in format ABCD0123456</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Account Type</label>
                                    <select name="account_type" class="form-control">
                                        <option value="">Select Account Type</option>
                                        <option value="saving" {{ old('account_type', $bank->account_type) == 'saving' ? 'selected' : '' }}>Savings Account</option>
                                        <option value="current" {{ old('account_type', $bank->account_type) == 'current' ? 'selected' : '' }}>Current Account</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Upload Cancelled Cheque</label>
                                    <input name="upload_cancelled_cheque" type="file" class="form-control"/>
                                </div>
                            </div>
                        </div>

                        <div class="card-body add_student_bank">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="add_student_bank"
                                    id="add_student_bank">
                                <label class="form-check-label" for="add_student_bank">
                                    Add Student Bank Details
                                </label>
                            </div>
                        </div>

                        <div class="studentBank d-none">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Student Bank Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Beneficiary Name</label>
                                        <input type="text" name="student_benificiary_name" class="form-control" 
                                        value="{{ old('student_benificiary_name', $bank->student_benificiary_name)}}"/>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Bank Account Number</label>
                                        <input type="text" name="student_bank_account_number" pattern="[0-9]{9,18}"
                                            class="form-control" 
                                            value="{{ old('student_bank_account_number', $bank->student_bank_account_number)}}"/>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Bank Name</label>
                                        <input type="text" name="student_bank_name" class="form-control" 
                                            value="{{ old('student_bank_name', $bank->student_bank_name)}}"/>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">IFSC Code</label>
                                        <input type="text" name="student_ifsc_code" class="form-control"
                                            pattern="[A-Z]{4}0[A-Z0-9]{6}" title="IFSC format: ABCD0123456" 
                                            value="{{ old('student_ifsc_code', $bank->student_ifsc_code)}}"/>
                                        <div class="invalid-feedback">IFSC must be in format ABCD0123456</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Account Type</label>
                                        <select name="student_account_type" class="form-control">
                                            <option value="">Select Account Type</option>
                                            <option value="saving" {{ old('student_account_type', $bank->student_account_type) == 'saving' ? 'selected' : '' }}>Savings Account</option>
                                            <option value="current" {{ old('student_account_type', $bank->student_account_type) == 'current' ? 'selected' : '' }}>Current Account</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Upload Cancelled Cheque</label>
                                        <input name="student_upload_cancelled_cheque" type="file"
                                            class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-navigation">
                        <button type="button" class="btn btn-secondary" id="back4">Back</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    /* ===================== GLOBALS ===================== */
    const steps = ["step1", "step2", "step3", "step4", "step5"];
    const forms = ["form1", "form2", "form3", "form4", "form5"];
    const progressBar = document.getElementById("timelineProgress");
    const successMessage = document.getElementById("successMessage");

    let currentStep = 0;
    let unlockedStepIndex = 0;
    let otherDocCount = 1;
    const maxOtherDocs = 3;

    function renderAcademicDocs(type) {
        const container = document.getElementById("academic-documents-container");
        if (!container) return;

        container.innerHTML = "";
        if (!academicDocs[type]) return;

        academicDocs[type].forEach(doc => {
            const col = document.createElement("div");
            col.className = "col-md-6 academic-field";
            col.innerHTML = `
                        <label class="form-label">${doc.label} ${doc.optional ? "<small class='text-muted'>(optional)</small>" : "<span class='text-danger'>*</span>"}</label>
                        <input type="file" name="${doc.name}" class="form-control" accept=".pdf,.jpg,.jpeg,.png" ${doc.optional ? '' : ''}>
                    `;
            container.appendChild(col);
        });
    }

    /* ===================== DOM READY (single init) ===================== */
    document.addEventListener('DOMContentLoaded', function() {
        showForm(currentStep);
        initFormStates();
        bindForm2Events();
        initGuardianLogic();
        initStudentBank();
        initFormSubmissions();
        initBackAndResetButtons();
        updateGuardianValue();
    });

    // Function to populate course modes with hybrid logic
    function populateCourseModes(courseModes) {
        const modeSelect = document.getElementById('mode_of_course');
        modeSelect.innerHTML = '<option value="">-- Select Course Mode --</option>';

        if (courseModes && courseModes.length > 0) {
            courseModes.forEach(mode => {
                // Use lowercase values that match database CHECK constraint
                let value = mode.toLowerCase();
                let display = mode; // Keep display as is
                
                const option = document.createElement('option');
                option.value = value; // Use lowercase
                option.textContent = display; // Display with proper case
                option.setAttribute('data-display', display); // Store display text
                modeSelect.appendChild(option);
            });
        }
    }

    // Function to populate mode types with hybrid logic
    function populateModeTypes(modeTypes) {
        const modeTypeSelect = document.getElementById('mode_type');
        modeTypeSelect.innerHTML = '<option value="">-- Select Mode Type --</option>';

        if (modeTypes && modeTypes.length > 0) {
            modeTypes.forEach(type => {
                // Use lowercase with underscore format
                let value = type.toLowerCase().replace('-', '_');
                let display = type.replace('_', ' '); // Display with space
                
                const option = document.createElement('option');
                option.value = value;
                option.textContent = display;
                modeTypeSelect.appendChild(option);
            });
        }
    }

    // Helper function for jQuery version
    function populateModeTypeDropdown(modeTypes) {
        const modeTypeSelect = $('#mode_type');
        modeTypeSelect.html('<option value="">-- Select Mode Type --</option>');

        if (modeTypes && modeTypes.length > 0) {
            modeTypes.forEach(type => {
                if (type === 'hybrid') {
                    const allOptions = ['part_time', 'full_time'];
                    allOptions.forEach(optionValue => {
                        modeTypeSelect.append(`<option value="${optionValue}">${optionValue}</option>`);
                    });
                    return false;
                } else {
                    modeTypeSelect.append(`<option value="${type}">${type}</option>`);
                }
            });
        }
    }

    /* ===================== GUARDIAN HANDLING ===================== */
    function updateGuardianValue() {
        const guardianRadios = document.querySelectorAll('input[name="guardian_type"]');
        const guardianContainer = document.querySelector('.guardianContainer');
        if (!guardianRadios.length || !guardianContainer) return;
        const selected = Array.from(guardianRadios).find(radio => radio.checked)?.value;
        if (!selected) return;
        console.log(selected);
        if (selected === 'other') {
            guardianContainer.classList.remove('d-none');
            guardianContainer.querySelectorAll('input, select').forEach(el => el.value = '');
        } else {
            guardianContainer.classList.add('d-none');
            autoFillGuardian(selected);
        }
    }

    function initGuardianLogic() {
        const guardianRadios = document.querySelectorAll('input[name="guardian_type"]');
        const guardianContainer = document.querySelector('.guardianContainer');
        if (!guardianRadios.length || !guardianContainer) return;
        // attach listeners
        guardianRadios.forEach(radio => {
            radio.addEventListener('change', updateGuardianValue);
        });
        // set default to father on page load
        const defaultGuardian = document.querySelector('input[value="father"][name="guardian_type"]');
        if (defaultGuardian) {
            defaultGuardian.checked = true;
            guardianContainer.classList.add('d-none');
            autoFillGuardian('father');
        }
    }

    /* ===================== FORM STEP MANAGEMENT ===================== */
    function showForm(step) {
        forms.forEach((id, i) => {
            const form = document.getElementById(id);
            if (form) form.style.display = i === step ? "block" : "none";
        });

        steps.forEach((id, i) => {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.toggle("active", i === step);
            el.classList.toggle("completed", i < step);
        });

        updateProgressBar(step);
    }

    function updateProgressBar(step) {
        const percentage = (step / (steps.length - 1)) * 80;
        if (progressBar) progressBar.style.width = `${percentage}%`;
    }

    /* ===================== INITIAL FORM STATES ===================== */
    function initFormStates() {
        steps.forEach((stepId, i) => {
            const el = document.getElementById(stepId);
            if (!el) return;

            el.addEventListener("click", () => {
                showForm(i);
            });
        });
    }

    /* ===================== FORM SUBMISSION ===================== */
    function submitFormData(form, step, cb) {

        const fd = new FormData(form);

        const pathParts = window.location.pathname.split('/');
        const studentHashId = pathParts[pathParts.indexOf('admin') + 1];

        if (!studentHashId) {
            Swal.fire('Error', 'Student ID not found', 'error');
            return;
        }

        fd.append('_method', 'PUT');

        const btn = form.querySelector('button[type="submit"]');
        const original = btn.innerHTML;
        btn.innerHTML =
            '<span class="spinner-border spinner-border-sm"></span> Updating...';
        btn.disabled = true;

        fetch(`/institute/admin/${studentHashId}`, {
            method: "POST",
            body: fd,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json"
            }
        })
        .then(async r => {
            const json = await r.json().catch(() => ({}));
            if (!r.ok) throw new Error(json.message || "Update failed");
            return json;
        })
        .then(d => {
            btn.innerHTML = original;
            btn.disabled = false;

            if (step === forms.length) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Student data updated successfully',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = '/institute/admin/students';
                });
            }

            if (typeof cb === "function") cb(d);
        })
        .catch(err => {
            btn.innerHTML = original;
            btn.disabled = false;
            Swal.fire('Error', err.message || 'Something went wrong!', 'error');
        });
    }

    /* ===================== GUARDIAN LOGIC ===================== */
    const guardianRadios = document.querySelectorAll('input[name="guardian_type"]');
    const guardianContainer = document.querySelector('.guardianContainer');

    guardianRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'other') {
                guardianContainer.classList.remove('d-none');
            } else {
                guardianContainer.classList.add('d-none');
                autoFillGuardian(this.value);
            }
        });
    });

    /* ===================== GUARDIAN AUTO-FILL ===================== */
    function autoFillGuardian(type) {
        const prefix = type === 'father' ? 'father' : 'mother';
        document.querySelector('input[name="guardian_first_name"]').value =
            document.querySelector(`input[name="${prefix}_first_name"]`)?.value || '';
        document.querySelector('input[name="guardian_middle_name"]').value =
            document.querySelector(`input[name="${prefix}_middle_name"]`)?.value || '';
        document.querySelector('input[name="guardian_last_name"]').value =
            document.querySelector(`input[name="${prefix}_last_name"]`)?.value || '';
        document.querySelector('input[name="guardian_email"]').value =
            document.querySelector(`input[name="${prefix}_email"]`)?.value || '';
        document.querySelector('input[name="guardian_phone"]').value =
            document.querySelector(`input[name="${prefix}_phone"]`)?.value || ''; 
        document.querySelector('select[name="guardian_occupation"]').value =
            document.querySelector(`select[name="${prefix}_occupation"]`)?.value || '';
        document.querySelector('input[name="guardian_income"]').value =
            document.querySelector(`input[name="${prefix}_income"]`)?.value || '';
        document.querySelector('select[name="guardian_blood_group"]').value =
            document.querySelector(`select[name="${prefix}_blood_group"]`)?.value || '';
        document.querySelector('input[name="guardian_relation"]').value =
            document.querySelector(`input[name="${prefix}_relation"]`)?.value || '';
    }

    // Set Father as default guardian
    const defaultGuardian = document.querySelector('input[value="father"][name="guardian_type"]');
    if (defaultGuardian) {
        defaultGuardian.checked = true;
        guardianContainer.classList.add('d-none');
        autoFillGuardian('father');
    }

    /* ===================== STUDENT BANK TOGGLE ===================== */
    function initStudentBank() {
        const checkbox = document.querySelector('input[name="add_student_bank"]');
        const container = document.querySelector('.studentBank');
        if (!checkbox || !container) return;
        checkbox.addEventListener("change", () => {
            container.classList.toggle("d-none", !checkbox.checked);
            // Clear student bank fields when unchecked
            if (!checkbox.checked) {
                container.querySelectorAll('input, select').forEach(el => {
                    if (el.type !== 'checkbox') {
                        el.value = '';
                    }
                });
            }
        });
    }

    /* ===================== ADDRESS COPY (FORM 2) ===================== */
    function bindForm2Events() {
        const addressPairs = [
            ['copyStudentAddress', 'student_perm', 'student_comm'],
            ['copyStudentToParent', 'student_perm', 'parent_perm'],
            ['copyStudentToParent', 'student_comm', 'parent_comm'],
            ['copyParentAddress', 'parent_perm', 'parent_comm']
        ];
        addressPairs.forEach(([id, from, to]) => {
            const chk = document.getElementById(id);
            if (!chk) return;
            chk.addEventListener('change', () => handleAddressSync(from, to, chk.checked));
            handleAddressSync(from, to, chk.checked);
        });

        document.querySelectorAll(
            'input[name$="_address_line1"], input[name$="_address_line2"], input[name$="_city"], input[name$="_state"], input[name$="_pincode"]'
        ).forEach(el => {
            el.addEventListener('input', () => {
                if (el.dataset.syncTo) {
                    const target = document.getElementsByName(el.dataset.syncTo)[0];
                    if (target) target.value = el.value;
                }
            });
        });
    }

    function handleAddressSync(from, to, sync) {
        ['address_line1', 'address_line2', 'city', 'state', 'pincode'].forEach(f => {
            const src = document.getElementsByName(`${from}_${f}`)[0];
            const dst = document.getElementsByName(`${to}_${f}`)[0];
            if (src && dst) {
                if (sync) {
                    dst.value = src.value;
                    src.dataset.syncTo = `${to}_${f}`;
                } else delete src.dataset.syncTo;
            }
        });
    }

    /* ===================== OTHER DOCUMENTS (FORM 4) ===================== */
    document.getElementById('addOtherDocBtn')?.addEventListener('click', () => {
        if (otherDocCount >= maxOtherDocs) return;
        otherDocCount++;
        const wrapper = document.getElementById('other-documents-wrapper');
        if (!wrapper) return;

        const groupHTML = `
                    <div class="col-md-6 other-doc-group">
                        <label class="form-label">Other Document Name ${otherDocCount}</label>
                        <input type="text" name="student_other_doc_label_${otherDocCount}" class="form-control" placeholder="Enter document name"
                            value="{{ old('student_other_doc_label', $bank->student_other_doc_label)}}">
                    </div>
                    <div class="col-md-6 other-doc-group d-flex align-items-end">
                        <div class="w-100">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                Upload Other Document ${otherDocCount}
                                <button type="button" class="btn btn-sm btn-danger ms-2 removeOtherDocBtn"><i class="fas fa-times"></i></button>
                            </label>
                            <input type="file" name="student_other_doc_file_${otherDocCount}" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>`;
        wrapper.insertAdjacentHTML('beforeend', groupHTML);
    });

    document.addEventListener('click', e => {
        const btn = e.target.closest('.removeOtherDocBtn');
        if (btn) {
            const group = btn.closest('.other-doc-group');
            group?.previousElementSibling?.remove();
            group?.remove();
            otherDocCount = Math.max(1, otherDocCount - 1);
        }
    });

    /* ===================== FORM SUBMISSIONS (ALL FORMS) ===================== */
    function initFormSubmissions() {
        forms.forEach((id, i) => {
            const form = document.getElementById(id);
            if (!form) return;

            form.addEventListener("submit", function(e) {
                e.preventDefault();
                const step = i + 1;

                // clearValidationErrors(id);
                // if (!validateForm(id)) return;

                submitFormData(this, step, (d) => {
                    currentStep = step;
                    showForm(step);

                    // ✅ Control Guardian Sections visibility
                    const guardianType = d.guardian_type || sessionStorage.getItem("guardian_type");
                    console.log("Guardian Type:", guardianType);

                    const addressContainer = document.querySelector(".guardian-address-container");
                    const docsContainer = document.querySelector(".otherDocumentsContainer");

                    if (guardianType && guardianType.toLowerCase() === "other") {
                        addressContainer?.classList.remove("d-none");
                        addressContainer?.classList.add("d-block");

                        docsContainer?.classList.remove("d-none");
                        docsContainer?.classList.add("d-block");
                    } else {
                        addressContainer?.classList.add("d-none");
                        addressContainer?.classList.remove("d-block");

                        docsContainer?.classList.add("d-none");
                        docsContainer?.classList.remove("d-block");
                    }

                    // ✅ Handle final success case
                    if (step === 5) {
                        form.style.display = "none";
                        if (successMessage) successMessage.style.display = "block";

                        setTimeout(() => {
                            currentStep = 0;
                            showForm(currentStep);
                            if (successMessage) successMessage.style.display = "none";

                            sessionStorage.removeItem("current_student_id");
                            sessionStorage.removeItem("current_student_hash_id");
                            sessionStorage.removeItem("guardian_type");
                        }, 3000);
                    }
                });
            });
        });
    }

    /* ===================== BACK / RESET BUTTONS ===================== */
    function initBackAndResetButtons() {
        const backs = [{
                id: 'backToForm1',
                step: 0
            },
            {
                id: 'back2',
                step: 1
            },
            {
                id: 'back3',
                step: 2
            },
            {
                id: 'back4',
                step: 3
            },
        ];
        backs.forEach(b => document.getElementById(b.id)?.addEventListener('click', () => {
            currentStep = b.step;
            showForm(b.step);
        }));
    }
</script>
@endsection

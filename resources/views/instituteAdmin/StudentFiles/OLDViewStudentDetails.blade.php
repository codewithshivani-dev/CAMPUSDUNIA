@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
.banner {
    height: 260px;
    border-radius: 18px;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #2154BE, #3E70B3);
    display: flex;
    align-items: center;
}
.banner img.bg {
    width: 100%;
    height: 260px;
    object-fit: cover;
    filter: brightness(0.8);
}
.banner .meta {
    position: absolute;
    left: 28px;
    bottom: 22px;
    background: rgba(255, 255, 255, 0.95);
    padding: 14px 20px;
    border-radius: 12px;
}
        .mainDiv {
            position: relative;
        }
    
        .childContent {
            position: absolute;
            color: #000;
            top: 65%;
            left: 7%;
        }
    
        .child1 {
            background: linear-gradient(90deg, #4B3F72, #F6C667);
            padding: 40px 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
            color: white;
        }
    
        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-top: -50px;
            border: 5px solid white;
        }
    
        /* Overlay */
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            display: none;
            z-index: 9998;
        }
    
        /* Slide panel */
        #sidePanel {
            position: fixed;
            top: 0;
            right: -100%;
            width: 900px;
            height: 100%;
            background: #fff;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.3);
            overflow-y: auto;
            transition: right 0.4s ease;
            z-index: 9999;
            padding: 20px;
        }
    
        /* Table spacing for better readability */
        .student-details-table th,
        .student-details-table td {
            /* padding: 8px 12px; */
            vertical-align: middle;
        }
    
        /* Add subtle row separation */
        .student-details-table tr {
            border-bottom: 1px solid #e9ecef;
        }
    
        /* Section headers (like Parent Details) */
        .student-details-table .section-header {
            background-color: #f1f1f1;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    
        /* Label column styling */
        .student-details-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            /* width: 20%; */
        }
    
        .searchContainer .row{
            margin-right: 0px !important;
            margin-left: 0px !important;
        }
    
        .searchContainer input{
            width: 19%;
            margin-right: 8px;
            margin-bottom: 8px;
        }
    
        .searchContainer button{
            margin-right: 8px;
            height: fit-content;
        }
    
        #signupForm label{
            margin-bottom: 0px !important;
        }
    </style>
    
    <style>
    .filter-box {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    
        background: #fff;
        padding: 18px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        align-items: center;
    }

    .filter-group {
        position: relative;
        flex: 1 1 200px; /* flexible width but minimum 200px */
    }
    
    .filter-group input {
        width: 100%;
        padding: 10px 14px 10px 38px; /* space for icon */
        border: 1.5px solid #dcdcdc;
        border-radius: 8px;
        font-size: 14px;
        background: #fafafa;
        transition: all 0.25s ease-in-out;
    }
    
    .filter-group input:focus {
        border-color: #4a70dc;
        box-shadow: 0px 0px 6px rgba(74, 112, 220, 0.4);
        background: #ffffff;
        outline: none;
    }
    
    .filter-icon {
        position: absolute;
        top: 50%;
        left: 12px;
        transform: translateY(-50%);
        color: #6c757d;
        font-size: 18px;
    }
    </style>
</head>

<body>
    <!-- Header with merchant name -->
<div class="banner mb-4">
    @if($fincapMerchants && $fincapMerchants->documents && $fincapMerchants->documents->first())
    <img src="{{ asset('/image/'.$fincapMerchants->documents->first()->institute_image_path) }}" alt="institute image"
        class="bg">
    @else
    <div class="bg" style="background: linear-gradient(135deg,#2154BE,#3E70B3);"></div>
    @endif
    <div class="meta">
        <h3 style="margin:0;">{{ $fincapMerchants->name ?? $fincapMerchants->fincap_merchant_name ?? 'Institute Name' }}
        </h3>
        <p style="margin:0;color:#444;">{{ $fincapMerchants->state ?? $fincapMerchants->fincap_merchant_state ?? '' }},
            {{ $fincapMerchants->city ?? $fincapMerchants->fincap_merchant_city ?? '' }}</p>
    </div>
</div>

    <!-- Students Table -->
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center my-2">
            <h2>Students Details</h2>
            <!-- Add Student Button -->
            <a href="{{ route('students.create') }}" class="btn btn-primary">
                + Add Student
            </a>
        </div>

        <form method="GET" action="{{ url()->current() }}" class="filter-box mb-4">
            {{-- Registration Number --}}
            <div class="filter-group">
                <i class="bi bi-upc-scan filter-icon"></i>
                <input list="regList" name="registration_number" placeholder="Registration Number"
                    value="{{ request('registration_number') }}"> 
                <datalist id="regList">
                    @foreach($regNumbers as $r)
                        <option value="{{ $r->registration_number }}">
                    @endforeach
                </datalist>
            </div>

            {{-- Name --}}
            <div class="filter-group">
                <i class="bi bi-person filter-icon"></i>
                <input list="nameList" name="name" placeholder="Enter Name"
                    value="{{ request('name') }}">
                <datalist id="nameList">
                    @foreach($names as $n)
                        <option value="{{ $n->first_name }}"> 
                    @endforeach
                </datalist>
            </div>

            {{-- Email --}}
            <div class="filter-group">
                <i class="bi bi-envelope filter-icon"></i>
                <input list="emailList" name="email" placeholder="Email" 
                    value="{{ request('email') }}"> 
                <datalist id="emailList">
                    @foreach($emails as $e)
                        <option value="{{ $e->email }}">  
                    @endforeach
                </datalist>
            </div> 

            {{-- Phone --}}
            <div class="filter-group">
                <i class="bi bi-telephone filter-icon"></i>
                <input list="mobileList" name="mobile" placeholder="Phone Number"
                    value="{{ request('mobile') }}">
                <datalist id="mobileList">
                    @foreach($mobiles as $m)
                        <option value="{{ $m->mobile }}"> 
                    @endforeach 
                </datalist>
            </div>

            {{-- DOB --}}
            <div class="filter-group" style="display:none;> 
                <i class="bi bi-calendar filter-icon"></i>
                <input type="text" name="dob" placeholder="DOB"
                    onfocus="this.type='date'" onblur="if(!this.value)this.type='text'"
                    value="{{ request('dob') }}">
            </div>


            {{-- Buttons --}}
            <div class="filter-input" style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
            </div>

        </form>

        <div class="table-responsive">
            <table class="table table-striped">
                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <thead>
                    <tr>
                        <th>Hash Id</th>
                        <th>Registration Number</th>
                        <th>Name</th>
                        <th>DOB</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody id="CourseDisplayTable">
                    @foreach ($students as $student)
                    <tr>
                        <td>{{ $student->student_hash_id }}</td>
                        <td>{{ $student->registration_number }}</td>
                        <td>{{ $student->first_name }} {{ $student->middle_name
                            }} {{ $student->last_name }}</td>
                        <td>{{ $student->dob }}</td>
                        <td>{{ $student->mobile }}</td>
                        <td>{{ $student->email }}</td>
                        <td style="text-align: end;">
                            <a class="btn btn-outline-primary"
                                href="{{route('students.edit', $student->student_hash_id) }}">
                                Edit
                            </a>

                            <button type="button"
                                class="btn btn-outline-primary view-button mb-1"
                                data-id="{{ $student->student_hash_id }}">
                                View
                            </button>

                             <a type="button"
                                class="btn btn-outline-primary create-card mb-1"
                                href="/students/{{ $student->student_hash_id }}/card">
                                Student Card
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div>
            {{ $students->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <!-- Overlay -->
    <div id="overlay"></div>

    <!-- Slide Panel -->
    <div id="sidePanel">
        <div class="d-flex justify-content-between align-items-center">
            <h4>Student Details</h4>
            <button id="closePanel"
                class="btn btn-sm btn-outline-secondary">&times;</button>
        </div>
        <hr>

        <!-- Bootstrap Tabs -->
        <ul class="nav nav-tabs" id="studentTabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" id="basic-tab"
                data-toggle="tab" href="#tabBasic" role="tab">Basic</a>
            </li>
            <li class="nav-item"><a class="nav-link" id="address-tab"
                data-toggle="tab" href="#tabAddress" role="tab">Address</a>
            </li>
            <li class="nav-item"><a class="nav-link" id="extra-tab"
                data-toggle="tab" href="#tabExtra" role="tab">Academic</a>
            </li>
            <li class="nav-item"><a class="nav-link" id="docs-tab"
                data-toggle="tab" href="#tabDocs" role="tab">Documents</a>
            </li>
            <li class="nav-item"><a class="nav-link" id="bank-tab"
                data-toggle="tab" href="#tabBank" role="tab">Bank</a>
            </li>
        </ul>

        <!-- Document View Modal -->
        <div class="modal fade" id="docModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Document Viewer</h5>
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">X</button>
                    </div>
                    <div class="modal-body text-center" id="docPreview">
                        <!-- Document will be shown here -->
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Close</button> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Contents -->
        <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="tabBasic"
                role="tabpanel"></div>
            <div class="tab-pane fade" id="tabAddress" role="tabpanel"></div>
            <div class="tab-pane fade" id="tabBank" role="tabpanel"></div>
            <div class="tab-pane fade" id="tabDocs" role="tabpanel"></div>
            <div class="tab-pane fade" id="tabExtra" role="tabpanel"></div>
        </div>
    </div>
    <!-- Signup Modal -->
    <div class="modal fade" id="signupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title">Signup Employee</h5>
                <button type="button" class="btn-close btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="signupFormContainer">
                <!-- Form will be injected here dynamically -->
                <p>Loading...</p>
            </div>
            </div>
        </div>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script
    src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

<script>
function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    });
}
$(document).on('click', '.view-button', function() {
    let hashId = $(this).data('id');

    // Show overlay
    $('#overlay').fadeIn(200);

    // Fetch student details
    $.ajax({
        url: '/student-details/' + hashId,
        method: 'GET',
        success: function(response) {
            // Fill tabs with data
            $('#tabBasic').html(`
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <table class="table table-bordered student-details-table">
                            <tbody>
                                <tr class="mt-2">
                                    <td colspan="2" class="bg-primary text-white fw-bold text-center">
                                        Student Details
                                    </td>
                                </tr>

                                <tr>
                                    <th class="bg-light" style="width: 15%;">Registration Number</th>
                                    <td style="width: 35%;">${response.student.registration}</td>
                                    <th class="bg-light" style="width: 15%;">Name</th>
                                    <td style="width: 35%;">${response.student.first_name} ${response.student.last_name}</td>
                                </tr>

                                <tr>
                                    <th class="bg-light" style="width: 15%;">DOB</th>
                                    <td style="width: 35%;">${response.student.dob}</td>
                                    <th class="bg-light" style="width: 15%;">Age</th>
                                    <td style="width: 35%;">${response.student.age}</td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Mobile</th>
                                    <td>${response.student.mobile}</td>
                                    <th class="bg-light">Blood Group</th>
                                    <td>${response.student.blood_group}</td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Email</th>
                                    <td>${response.student.email}</td>
                                    <th class="bg-light">Gender</th>
                                    <td>${response.student.gender}</td>
                                </tr>

                                <tr class="mt-2">
                                    <td colspan="2" class="bg-primary text-white fw-bold text-center">
                                        Parents Detail
                                    </td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Father's Full Name</th>
                                    <td></td>
                                    <th class="bg-light">Father's D.O.B</th>
                                    <td></td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Father's Age</th>
                                    <td></td>
                                    <th class="bg-light">Father's Email</th>
                                    <td>${response.student.parent_email}</td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Father's Phone</th>
                                    <td>${response.student.parent_phone}</td>
                                    <th class="bg-light">Father's Occupation</th>
                                    <td>${response.student.parent_occupation}</td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Father's Annual Income</th>
                                    <td>${response.student.parent_income}</td>
                                    <th class="bg-light">Father's Blood Group</th>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td colspan="4"><hr class="my-1"></td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Mother's Full Name</th>
                                    <td></td>
                                    <th class="bg-light">Mother's D.O.B</th>
                                    <td></td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Mother's Age</th>
                                    <td></td>
                                    <th class="bg-light">Mother's Email</th>
                                    <td>${response.student.parent_email}</td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Mother's Phone</th>
                                    <td>${response.student.parent_phone}</td>
                                    <th class="bg-light">Mother's Occupation</th>
                                    <td>${response.student.parent_occupation}</td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Mother's Annual Income</th>
                                    <td>${response.student.parent_income}</td>
                                    <th class="bg-light">Mother's Blood Group</th>
                                    <td></td>
                                </tr>

                                <tr class="mt-2">
                                    <td colspan="2" class="bg-primary text-white fw-bold text-center">
                                        Guardian Details
                                    </td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Parent Relation</th>
                                    <td>${response.student.parent_relation}</td>
                                    <th class="bg-light">Parent Name</th>
                                    <td>${response.student.parent_first_name} ${response.student.parent_last_name}</td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Parent Gender</th>
                                    <td>${response.student.parent_gender}</td>
                                    <th class="bg-light">Parent DOB</th>
                                    <td>${response.student.parent_dob}</td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Parent Email</th>
                                    <td>${response.student.parent_email}</td>
                                    <th class="bg-light">Parent Phone</th>
                                    <td>${response.student.parent_phone}</td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Alternate Phone</th>
                                    <td>${response.student.alternate_phone_number}</td>
                                    <th class="bg-light">Parent Occupation</th>
                                    <td>${response.student.parent_occupation}</td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Parent Income</th>
                                    <td>${response.student.parent_income}</td>
                                    <th class="bg-light">Created At</th>
                                    <td>${response.student.created_at}</td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Updated At</th>
                                    <td colspan="3">${response.student.updated_at}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            `);


            $('#tabAddress').html(`
                    <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                        <table class="table table-bordered address-details-table">
                            <tbody>
                                <tr>
                                    <th class="bg-light" style="width: 15%;">Address Line1</th>
                                    <td style="width: 35%;">${response.address.student_perm_address_line1 ?? "null"}</td>
                                    <th class="bg-light" style="width: 15%;">Address Line2</th>
                                    <td style="width: 35%;">${response.address.student_perm_address_line2}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Permanent City</th>
                                    <td>${response.address.student_perm_city}</td>
                                    <th class="bg-light">Permanent State</th>
                                    <td>${response.address.student_perm_state}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Permanent Pincode</th>
                                    <td>${response.address.student_perm_pincode}</td>
                                    <th class="bg-light">Communication Address Line1 </th>
                                    <td>${response.address.student_comm_address_line1}</td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Communication Address Line2</th>
                                    <td>${response.address.student_comm_address_line2}</td>
                                    <th class="bg-light">Communication City</th>
                                    <td>${response.address.student_comm_city} </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Communication State</th>
                                    <td>${response.address.student_comm_state}</td>
                                    <th class="bg-light">Parent Address Line1  </th>
                                    <td>${response.address.parent_perm_address_line1}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Parent Address Line2</th>
                                    <td>${response.address.parent_perm_address_line2}</td>
                                    <th class="bg-light">Parent City</th>
                                    <td>${response.address.parent_perm_city}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Parent State</th>
                                    <td>${response.address.parent_perm_state}</td>
                                    <th class="bg-light">Parent Pincode</th>
                                    <td>${response.address.parent_perm_pincode}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Parent Communication Address Line1</th>
                                    <td>${response.address.parent_comm_address_line1}</td>
                                    <th class="bg-light">Parent Communication Address Line2</th>
                                    <td>${response.address.parent_comm_address_line2}</td>

                                </tr>
                                <tr>
                                    <th class="bg-light">Parent Communication Address City</th>
                                    <td>${response.address.parent_comm_city}</td>
                                    <th class="bg-light">Parent Communication Address State</th>
                                    <td>${response.address.parent_comm_state}</td>

                                </tr>
                                <tr>
                                    <th class="bg-light">Parent Communication Address Pincode</th>
                                    <td>${response.address.parent_comm_pincode}</td>
                                    <th class="bg-light">Created At</th>
                                    <td>${formatDate(response.address.created_at)}</td>

                                </tr>
                                <tr>
                                    <th class="bg-light">Updated At</th>
                                    <td colspan="3">${formatDate(response.address.updated_at)}</td>
                                
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
            `);

            $('#tabBank').html(`
    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <table class="table table-bordered address-details-table">
                <tbody>
                    <tr>
                        <th class="bg-light" style="width: 15%;">Benificiary Name</th>
                        <td style="width: 35%;">${response.bank.benificiary_name ?? 'N/A'}</td>
                        <th class="bg-light" style="width: 15%;">Bank Account Number</th>
                        <td style="width: 35%;">${response.bank.bank_account_number ?? 'N/A'}</td>
                    </tr>

                    <tr>
                        <th class="bg-light">Bank Name</th>
                        <td>${response.bank.bank_name ?? 'N/A'}</td>
                        <th class="bg-light">IFSC Code</th>
                        <td>${response.bank.ifsc_code ?? 'N/A'}</td>
                    </tr>

                    <tr>
                        <th class="bg-light">Account Type</th>
                        <td>${response.bank.account_type ?? 'N/A'}</td>
                        <th class="bg-light">Cancelled Cheque</th>
                        <td>
                            ${response.bank && response.bank.upload_cancelled_cheque 
                                ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${response.bank.upload_cancelled_cheque}">View</button>` 
                                : 'Not Uploaded'}
                        </td>
                    </tr>

                    <tr>
                        <th class="bg-light">Created At</th>
                        <td>${formatDate(response.bank.created_at)}</td>
                        <th class="bg-light">Updated At</th>
                        <td>${formatDate(response.bank.updated_at)}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
`);

            $('#tabDocs').html(`
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <table class="table table-bordered address-details-table">
                            <tbody>
                                <tr>
                                    <th class="bg-light">Aadhaar Number</th>
                                    <td>${response.documents.student_aadhaar_number}</td>
                                    <th class="bg-light">Aadhaar File</th>
                                    <td>
                                        ${response.documents.student_aadhaar_file 
                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${response.documents.student_aadhaar_file}">View</button>` 
                            : 'Not Uploaded'}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Pan Number</th>
                                    <td>${response.documents.student_pan_number}</td>
                                    <th class="bg-light">Pan File</th>
                                    <td>
                                        ${response.documents.student_pan_file 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${response.documents.student_pan_file}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Photo</th>
                                    <td>
                                        ${response.documents.student_photo 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${response.documents.student_photo}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                    <th class="bg-light">Id Card</th>
                                    <td>
                                        ${response.documents.student_id_card 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${response.documents.student_id_card}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Address Proof</th>
                                    <td>
                                        ${response.documents.student_address_proof 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${response.documents.student_address_proof}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                    <th class="bg-light">Student Bonafide</th>
                                    <td>
                                        ${response.documents.student_bonafide 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${response.documents.student_bonafide}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Academic Documents</th>
                                    <td>
                                        ${response.documents.academic_documents 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${response.documents.academic_documents}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                    <th class="bg-light">Other Doc File1</th>
                                    <td>
                                        ${response.documents.student_other_doc_file_1 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${response.documents.student_other_doc_file_1}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Prev Class Certificate</th>
                                    <td>
                                        ${response.documents.prev_class_certificate 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${response.documents.prev_class_certificate}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                    <th class="bg-light">Marksheet 10th</th>
                                    <td>
                                        ${response.documents.marksheet_10 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${response.documents.marksheet_10}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Marksheet 12th</th>
                                    <td>
                                        ${response.documents.marksheet_12 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${response.documents.marksheet_12}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                    <th class="bg-light">Bachelor Marksheet</th>
                                    <td>
                                        ${response.documents.bachelor_marksheet 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${response.documents.bachelor_marksheet}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Parent Aadhaar Number</th>
                                    <td>
                                        ${response.documents.parent_aadhaar_number 
                                        }
                                    </td>
                                    <th class="bg-light">Parent Aadhaar File</th>
                                    <td>
                                        ${response.documents.parent_aadhaar_file 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${response.documents.parent_aadhaar_file}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Parent Pan Number</th>
                                    <td>
                                        ${response.documents.parent_pan_number}
                                    </td>
                                    <th class="bg-light">Parent Pan File</th>
                                    <td>
                                        ${response.documents.parent_pan_file 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${response.documents.parent_pan_file}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Parent Income Proof</th>
                                    <td>
                                        ${response.documents.parent_income_proof 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${response.documents.parent_income_proof}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                    <th class="bg-light">Parent Photo</th>
                                    <td>
                                        ${response.documents.parent_photo 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${response.documents.parent_photo}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Parent Address Proof</th>
                                    <td>
                                        ${response.documents.parent_address_proof 
                                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${response.documents.parent_address_proof}">View</button>` 
                                            : 'Not Uploaded'}
                                    </td>
                                    <th class="bg-light">Created At</th>
                                    <td>
                                        ${formatDate(response.documents.created_at) 
                                        }
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Updated At</th>
                                    <td>
                                        ${formatDate(response.documents.updated_at) 
                                        }
                                    </td>
                                
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            `);


        $('#tabExtra').html(`
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <table class="table table-bordered address-details-table">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width: 15%;">Institute Id</th>
                                <td>${response.extra.institute_id}</td>
                                <th class="bg-light" style="width: 15%;">Course Type</th>
                                <td>${response.extra.course_type}</td>
                            </tr>

                            <tr>
                                <th class="bg-light">Course Subtype</th>
                                <td>${response.extra.course_subtype}</td>
                                <th class="bg-light">Session Id</th>
                                <td>${response.extra.session_id} </td>
                
                            </tr>

                            <tr>
                                <th class="bg-light">Semester Id</th>
                                <td>${response.extra.semester_id}</td>
                                <th class="bg-light">Section Id</th>
                                <td>${response.extra.section_id}</td>
                            </tr>

                            <tr>
                                <th class="bg-light">Uses Transport</th>
                                <td>${response.extra.uses_transport}</td>
                                <th class="bg-light">Morning route Id</th>
                                <td>${response.extra.morning_route_id}</td>
                            </tr>

                            <tr>
                                
                                <th class="bg-light">Morning Route Name</th>
                                <td>${response.extra.morning_route_name}</td>
                                <th class="bg-light">Evening Route Id</th>
                                <td>${response.extra.evening_route_id}</td>
                            </tr>

                            <tr>    
                                <th class="bg-light">Evening Route Name</th>
                                <td>${response.extra.evening_route_name}</td>
                                <th class="bg-light">Morning Stop</th>
                                <td>${response.extra.morning_stop}</td>                           
                            </tr>

                            <tr>    
                                <th class="bg-light">Evening Stop</th>
                                <td>${response.extra.evening_stop}</td>     
                                <th class="bg-light">Created At</th>
                                <td>${formatDate(response.extra.created_at)}</td>                      
                            </tr>

                            <tr>
                                <th class="bg-light">Updated At</th>
                                <td>${formatDate(response.extra.updated_at)}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        `);

            // Slide in panel
            $('#sidePanel').css('right', '0');
        }
    });
});

// Close panel
$('#closePanel, #overlay').click(function() {
    $('#sidePanel').css('right', '-100%');

    $('#overlay').fadeOut(200);
});

// Event Delegation for all "View" buttons
$(document).on('click', '.view-doc', function() {
    let filePath = $(this).data('file');
    let previewHtml = '';

    // Add a timestamp to prevent caching
    let cacheBuster = '?t=' + new Date().getTime();

    if (filePath.match(/\.(jpg|jpeg|png|gif|webp|bmp)$/i)) {
        previewHtml =
            `<img src="${filePath}${cacheBuster}" class="img-fluid" alt="Document" style="max-height: 80vh;">`;
    } else if (filePath.match(/\.(pdf)$/i)) {
        previewHtml =
            `<iframe src="${filePath}${cacheBuster}#view=fitH" width="100%" height="600px" frameborder="0"></iframe>`;
    } else {
        previewHtml =
            `<div class="p-3"><a href="${filePath}" target="_blank" class="btn btn-primary">Open Document</a></div>`;
    }

    $('#docPreview').html(previewHtml);
    $('#docModal').modal('show');
});
</script>

@endsection
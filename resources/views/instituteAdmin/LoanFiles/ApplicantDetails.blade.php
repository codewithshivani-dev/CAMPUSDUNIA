@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<style>
.error {
    color: red;
    font-size: 12px;
    margin-top: 4px;
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

.add-btn {
    margin: 20px;
    padding: 10px 20px;
    background-color: #007bff;
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 5px;
}

</style>
 <div class="mainDiv1">
        <div class="mainDiv" style="height: 270px;">
            <div class="child1" style="height: 200px;">
                <div class="childContent">
                    <img src="/images/allen-logo.webp" alt="Profile Image" class="profile-image">
                    <h3>Vignesh Ramesh</h3>
                </div>
            </div>
        </div>
    </div>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center my-2">
        <h2>Applicant Details</h2>
    </div>

    {{-- Messages (optional) --}}
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Applicant List Table --}}
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Reg. Number</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($applicants as $app)
                <tr>
                    <td>{{ $app->registration_number }}</td>
                    <td>{{ $app->first_name }} {{ $app->last_name }}</td>
                    <td>{{ $app->email }}</td>
                    <td>{{ $app->mobile_number }}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-info" onclick="viewApplicant('{{ $app->id }}')">View</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Overlay --}}
<div class="overlay" id="overlay" onclick="closeApplicantPanel()"></div>

<!-- View Applicant Panel -->
<div id="viewApplicantPanel" style="
    position: fixed; top: 0; right: -100%;
    width: 40%; max-width: 900px;
    height: 100%; background: #fff;
    box-shadow: -2px 0 8px rgba(0,0,0,.2);
    z-index: 11; overflow-y: auto;
    transition: right 0.4s ease;">

    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="m-0">Applicant Details</h5>
        <button onclick="closeApplicantPanel()" style="border:none; background:none; font-size:20px;">×</button>
    </div>

    <!-- Nav Tabs -->
    <ul class="nav nav-tabs px-3 pt-2" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#tabAppBasic" role="tab">Basic</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#tabAppFamily" role="tab">Family</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#tabAppAddress" role="tab">Address</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#tabAppMeta" role="tab">Meta</a>
        </li>
    </ul>

    <!-- Tab Contents -->
    <div class="tab-content p-3">
        <div class="tab-pane fade show active" id="tabAppBasic" role="tabpanel"></div>
        <div class="tab-pane fade" id="tabAppFamily" role="tabpanel"></div>
        <div class="tab-pane fade" id="tabAppAddress" role="tabpanel"></div>
        <div class="tab-pane fade" id="tabAppMeta" role="tabpanel"></div>
    </div>
</div>

<!-- jQuery + Bootstrap -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function viewApplicant(id) {
    let panel = document.getElementById("viewApplicantPanel");
    panel.style.right = "0"; // open panel

    // reset
    $("#tabAppBasic, #tabAppFamily, #tabAppAddress, #tabAppMeta").html("<p>Loading...</p>");

    $.ajax({
        url: '/applicant-details/' + id,
        method: 'GET',
        success: function(a) {
            // BASIC TAB
            $('#tabAppBasic').html(`
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th>Registration No.</th><td>${a.registration_number || '-'}</td></tr>
                                <tr><th>Applicant Type</th><td>${a.applicant_type || '-'}</td></tr>
                                <tr><th>First Name</th><td>${a.first_name || '-'}</td></tr>
                                <tr><th>Middle Name</th><td>${a.middle_name || '-'}</td></tr>
                                <tr><th>Last Name</th><td>${a.last_name || '-'}</td></tr>
                                <tr><th>Date of Birth</th><td>${a.date_of_birth || '-'}</td></tr>
                                <tr><th>Gender</th><td>${a.gender || '-'}</td></tr>
                                <tr><th>Email</th><td>${a.email || '-'}</td></tr>
                                <tr><th>Mobile</th><td>${a.mobile_number || '-'}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            `);

            // FAMILY TAB
            $('#tabAppFamily').html(`
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th>Father Name</th><td>${a.father_name || '-'}</td></tr>
                                <tr><th>Father Occupation</th><td>${a.father_occupation || '-'}</td></tr>
                                <tr><th>Mother Name</th><td>${a.mother_name || '-'}</td></tr>
                                <tr><th>Parents Mobile</th><td>${a.parents_mobile || '-'}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            `);

            // ADDRESS TAB
            $('#tabAppAddress').html(`
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th>Address</th><td>${a.communication_address || '-'}</td></tr>
                                <tr><th>City</th><td>${a.city || '-'}</td></tr>
                                <tr><th>State</th><td>${a.state || '-'}</td></tr>
                                <tr><th>Pincode</th><td>${a.pincode || '-'}</td></tr>
                                <tr><th>Nationality</th><td>${a.nationality || '-'}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            `);

            // META TAB
            $('#tabAppMeta').html(`
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th>Institute ID</th><td>${a.institute_id || '-'}</td></tr>
                                <tr><th>User Hash ID</th><td>${a.user_hash_id || '-'}</td></tr>
                                <tr><th>Applicant ID</th><td>${a.applicant_id || '-'}</td></tr>
                                <tr><th>Created At</th><td>${a.created_at || '-'}</td></tr>
                                <tr><th>Updated At</th><td>${a.updated_at || '-'}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            `);
        },
        error: function() {
            alert("Failed to load applicant details.");
        }
    });
}

function closeApplicantPanel() {
    document.getElementById("viewApplicantPanel").style.right = "-100%";
}
</script>
@endsection
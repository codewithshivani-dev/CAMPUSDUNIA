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
.overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1040;
    display: none;
}
.overlay.show {
    display: block;
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
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Applicant Fee Details</h3>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Institute</th>
                    <th>Course</th>
                    <th>Total Fee</th>
                    <th>Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applicantFees as $fee)
                <tr>
                    <td>{{ $fee->id }}</td>
                    <td>{{ $fee->institute_name }}</td>
                    <td>{{ $fee->course_name }}</td>
                    <td>{{ $fee->total_fee }}</td>
                    <td>
                        <span class="badge {{ $fee->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($fee->status) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-primary" onclick="viewFee('{{ $fee->id }}')">View</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Slide-Over Panel --}}
<div class="overlay" id="overlay" onclick="closeFeePanel()"></div>

<div id="viewFeePanel" style="
    position: fixed; top: 0; right: -100%;
    width: 40%; max-width: 900px;
    height: 100%; background: #fff;
    box-shadow: -2px 0 8px rgba(0,0,0,.2);
    z-index: 1050; overflow-y: auto;
    transition: right 0.4s ease;">
    
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="m-0">Applicant Fee Detail</h5>
        <button onclick="closeFeePanel()" style="border:none; background:none; font-size:20px;">×</button>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs px-3 pt-2" id="feeTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#tabFeeBasic"
                type="button" role="tab" aria-controls="tabFeeBasic" aria-selected="true">
                Basic
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="meta-tab" data-bs-toggle="tab" data-bs-target="#tabFeeMeta"
                type="button" role="tab" aria-controls="tabFeeMeta" aria-selected="false">
                Meta
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content p-3" id="feeTabContent">
        <div class="tab-pane fade show active" id="tabFeeBasic" role="tabpanel" aria-labelledby="basic-tab"></div>
        <div class="tab-pane fade" id="tabFeeMeta" role="tabpanel" aria-labelledby="meta-tab"></div>
    </div>
</div>

<script>
function viewFee(id) {
    let panel = document.getElementById("viewFeePanel");
    let overlay = document.getElementById("overlay");

    // Reset tabs with loading text
    document.getElementById("tabFeeBasic").innerHTML = "<p>Loading...</p>";
    document.getElementById("tabFeeMeta").innerHTML = "<p>Loading...</p>";

    fetch(`/applicant-fee-details/${id}`)
        .then(res => res.json())
        .then(fee => {
            // Basic Tab
            document.getElementById("tabFeeBasic").innerHTML = `
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <table class="table table-bordered">
                            <tbody>
                                 <tr><th>Institute Type</th><td>${fee.institute_type || '-'}</td></tr>
                                <tr><th style="width:40%">Institute</th><td>${fee.institute_name || '-'}</td></tr>
                                <tr><th>Course</th><td>${fee.course_name || '-'}</td></tr>
                                <tr><th>Duration</th><td>${fee.fee_duration || '-'}</td></tr>
                                <tr><th>Total Fee</th><td>${fee.total_fee || '-'}</td></tr>
                                <tr><th>Payable Term</th><td>${fee.select_payablefee_term || '-'}</td></tr>
                                <tr><th>Total Payable Fee</th><td>${fee.total_payable_fee || '-'}</td></tr>
                                <tr><th>Fee Request Status</th><td>${fee.fee_request_status || '-'}</td></tr>
                                <tr><th>Status</th><td>${fee.status || '-'}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            `;

            // Meta Tab
            document.getElementById("tabFeeMeta").innerHTML = `
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th>Institute Id</th><td>${fee.institute_id || '-'}</td></tr>
                                <tr><th>User Hash Id</th><td>${fee.user_hash_id || '-'}</td></tr>
                                <tr><th>Fee Request Id </th><td>${fee.fee_request_id  || '-'}</td></tr>
                                <tr><th>Institute Type Id</th><td>${fee.institute_type_id || '-'}</td></tr>
                                <tr><th>Merchant Id</th><td>${fee.merchant_id || '-'}</td></tr>
                                <tr><th>Updated At</th><td>${fee.updated_at || '-'}</td></tr>
                                <tr><th style="width:40%">Created At</th><td>${fee.created_at || '-'}</td></tr>
                                <tr><th>Updated At</th><td>${fee.updated_at || '-'}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            `;

            // Reinitialize Bootstrap tabs after content is loaded
            initializeTabs();
        })
        .catch(error => {
            console.error('Error fetching fee details:', error);
            document.getElementById("tabFeeBasic").innerHTML = "<p>Error loading data</p>";
            document.getElementById("tabFeeMeta").innerHTML = "<p>Error loading data</p>";
        });

    // Open panel
    panel.style.right = "0";
    overlay.classList.add("show");
    document.body.style.overflow = 'hidden';
}

function initializeTabs() {
    // Get all tab buttons
    const tabButtons = document.querySelectorAll('#feeTabs button[data-bs-toggle="tab"]');
    
    // Add click event listeners to each tab button
    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get the target tab pane
            const targetId = this.getAttribute('data-bs-target');
            const targetPane = document.querySelector(targetId);
            
            // Hide all tab panes
            document.querySelectorAll('#feeTabContent .tab-pane').forEach(pane => {
                pane.classList.remove('show', 'active');
            });
            
            // Remove active class from all tab buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active');
                btn.setAttribute('aria-selected', 'false');
            });
            
            // Show the target tab pane and activate the button
            targetPane.classList.add('show', 'active');
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
        });
    });
}

function closeFeePanel() {
    document.getElementById("viewFeePanel").style.right = "-100%";
    document.getElementById("overlay").classList.remove("show");
    document.body.style.overflow = '';
}

// Initialize tabs when the page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeTabs();
});
</script>
@endsection
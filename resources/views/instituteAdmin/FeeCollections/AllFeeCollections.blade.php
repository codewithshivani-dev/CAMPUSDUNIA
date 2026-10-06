@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
{{-- Custom Styling --}}
<style>
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

#overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1049;
}

#sidePanel {
    position: fixed;
    top: 0;
    right: -650px;
    width: 650px;
    height: 100%;
    background: #fff;
    z-index: 1050;
    box-shadow: -2px 0 10px rgba(0, 0, 0, 0.2);
    padding: 16px;
    transition: right 0.3s ease-in-out;
    overflow-y: auto;
}

#sidePanel.open {
    right: 0;
}
.badge.bg-success
{
    color: white;
    padding: 5px;
}    
.table thead th {
    font-weight: 600;
    color: #495057;
}
.badge {
    font-size: 0.85rem;
    padding: 6px 12px;
    border-radius: 20px;
}
.detail-panel {
    position: fixed;
    top: 0;
    right: -400px;
    width: 350px;
    height: 100%;
    background: #fff;
    border-left: 1px solid #ddd;
    box-shadow: -2px 0 8px rgba(0,0,0,0.15);
    transition: right 0.3s ease;
    z-index: 1050;
    padding: 20px;
}
.detail-panel.open {
    right: 0;
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
<div class="container-fluid mt-5">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center my-2">
            <h3 class="fw-bold">Applicant Fee Collection</h3>
    </div>

    <!-- Search Filters -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Search by ID...">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Search by Name...">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Search by Phone...">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-warning w-100">SEARCH</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Fee Table -->
  
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>course</th>
                    <th>sub type</th>
                    <th>section</th>
                    <th>session</th>
                    <th>semester</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td>#{{ $student->id }}</td>
                   <td>
                        @if (!empty($student->student_photo))
                            <img src="{{ route('image', ['path' => $student->student_photo]) }}" 
                                alt="Student Photo" 
                                class="rounded-circle" 
                                width="40" height="40">
                        @endif
                    </td>

                    <td>{{ $student->first_name }}{{ $student->middle_name }}{{ $student->last_name }}

                    </td>
                    <td>{{ $student->gender }}</td>
                    <td>{{ $student->course_type }}</td>
                    <td>{{ $student->sub_type }}</td>
                    <td>{{ $student->section_id }}</td>
                    <td>{{ $student->session_id }}</td>
                    <td>{{ $student->semester_id }}</td>
                    <td>{{ $student->total_fee }}</td>
                    <td>
                        @if($student->status == 'Paid')
                            <span class="badge bg-success">Paid</span>
                        @elseif($student->status == 'Unpaid')
                            <span class="badge bg-danger">Unpaid</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td> 
                    <td class="text-center">
                            <button 
                                class="btn btn-sm btn-primary view-btn"
                                data-id="{{ $student->id }}"
                                data-name="{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}"
                                data-course="{{ $student->course_type }}"
                                data-subtype="{{ $student->sub_type }}"
                                data-section="{{ $student->section_id }}"
                                data-session="{{ $student->session_id }}"
                                data-semester="{{ $student->semester_id }}"
                                data-amount="{{ $student->total_fee }}"
                                data-phone="{{ $student->mobile }}"
                                data-status="{{ $student->status ?? 'Pending' }}"
                                data-photo="{{ !empty($student->student_photo) ? route('image', ['path' => $student->student_photo]) : 'https://i.pravatar.cc/80?u='.$student->id }}"
                            >
                                View
                            </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<!-- Slide Over Panel -->
<div id="detailPanel" class="detail-panel">
    <div class="panel-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Applicant Details</h5>
        <button class="btn-close" onclick="closePanel()"></button>
    </div>
    <div class="panel-body">
        <div class="text-center mb-3">
            <img id="detailPhoto" src="" class="rounded-circle mb-2" width="80" height="80">
            <h5 id="detailName"></h5>
            <span id="detailStatus" class="badge"></span>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <td id="detailId"></td>
                    </tr>
                    <tr>
                        <th>Course</th>
                        <td id="detailCourse"></td>
                    </tr>
                    <tr>
                        <th>Sub Type</th>
                        <td id="detailSubtype"></td>
                    </tr>
                    <tr>
                        <th>Section</th>
                        <td id="detailSection"></td>
                    </tr>
                    <tr>
                        <th>Session</th>
                        <td id="detailSession"></td>
                    </tr>
                    <tr>
                        <th>Semester</th>
                        <td id="detailSemester"></td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td id="detailAmount"></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td id="detailPhone"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
const detailPanel = document.getElementById('detailPanel');
const detailId = document.getElementById('detailId');
const detailName = document.getElementById('detailName');
const detailCourse = document.getElementById('detailCourse');
const detailSubtype = document.getElementById('detailSubtype');
const detailSection = document.getElementById('detailSection');
const detailSession = document.getElementById('detailSession');
const detailSemester = document.getElementById('detailSemester');
const detailAmount = document.getElementById('detailAmount');
const detailStatus = document.getElementById('detailStatus');
const detailPhone = document.getElementById('detailPhone');
const detailPhoto = document.getElementById('detailPhoto');

document.querySelectorAll('.view-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        detailId.textContent = this.dataset.id;
        detailName.textContent = this.dataset.name;
        detailCourse.textContent = this.dataset.course;
        detailSubtype.textContent = this.dataset.subtype;
        detailSection.textContent = this.dataset.section;
        detailSession.textContent = this.dataset.session;
        detailSemester.textContent = this.dataset.semester;
        detailAmount.textContent = this.dataset.amount;
        detailPhone.textContent = this.dataset.phone;
        detailPhoto.src = this.dataset.photo;

        // status + badge color
        detailStatus.textContent = this.dataset.status;
        detailStatus.className = 'badge';
        if(this.dataset.status === 'Paid') {
            detailStatus.classList.add('bg-success');
        } else if(this.dataset.status === 'Unpaid') {
            detailStatus.classList.add('bg-danger');
        } else {
            detailStatus.classList.add('bg-warning','text-dark');
        }

        detailPanel.classList.add('open');
    });
});

function closePanel() {
    detailPanel.classList.remove('open');
}

</script>
@endsection

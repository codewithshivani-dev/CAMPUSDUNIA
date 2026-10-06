@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    .sibling-row {
        transition: all 0.3s ease;
    }
    .sibling-row:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .is-valid {
        border-color: #198754;
    }
    .is-invalid {
        border-color: #dc3545;
    }
    .badge {
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
    }
    .fw-bold {
        font-weight: 600;
    }
</style>

<div class="container-fluid">
    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ================= STUDENT INFO ================= --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-user-graduate mr-2"></i>Assign Siblings
            </h5>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6 col-lg-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-search mr-1"></i>Search Student
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search text-primary"></i>
                        </span>
                        <input type="text" 
                            class="form-control" 
                            id="search_value"
                            placeholder="Enter Registration Number or Email">
                    </div>
                    <small class="">
                        <i class="fas fa-info-circle mr-1"></i>Enter Registration Number or Email
                    </small>
                </div>
            </div>
            
            {{-- Student Details Card - Only visible when student found --}}
            <div id="studentDetailsCard" class="mt-4" style="display: none;">
                <div class="card border-primary">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-primary">
                            <i class="fas fa-user-circle mr-2"></i>Student Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-labelsmall">Registration Number</label>
                                <div class="fw-bold" id="main_registration_number">-</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-labelsmall">Email</label>
                                <div class="fw-bold" id="main_email">-</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small">Student Name</label>
                                <div class="fw-bold" id="main_student_name">-</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small">Date of Birth</label>
                                <div class="fw-bold" id="main_dob">-</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small">Class</label>
                                <div class="fw-bold" id="main_class">-</div>
                            </div>
                            
                            <div class="col-md-3 d-none">
                                <label class="form-label small">Section</label>
                                <div class="fw-bold" id="main_section">-</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small">Mobile</label>
                                <div class="fw-bold" id="main_mobile">-</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- No Results Message --}}
            <div id="noResultsMessage" class="alert alert-warning mt-4" style="display: none;">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                No student found with the provided registration number or email.
            </div>

            {{-- Loading Spinner --}}
            <div id="searchSpinner" class="text-center mt-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden"></span>
                </div>
                <p class="mt-2 ">Searching for student...</p>
            </div>
        </div>
    </div>


    {{-- ================= SIBLING SECTION ================= --}}
    <form method="POST" action="{{ route('assign.siblings.store') }}" id="siblingForm">
        @csrf
        
        <!-- Main student hidden fields -->
        <input type="hidden" name="student_hash_id" id="student_hash_id" value="">
        <input type="hidden" name="main_class_id" id="main_class_id" value="">
        <input type="hidden" name="main_section_id" id="main_section_id" value="">
        <input type="hidden" name="main_registration_number" id="hidden_main_registration_number" value="">
        <input type="hidden" name="main_email" id="hidden_main_email" value="">
        
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-users mr-2"></i>Sibling Details
                </h5>
                <button type="button" class="btn btn-light btn-sm" id="addSiblingBtn" disabled>
                    <i class="fas fa-plus-circle mr-1"></i>Add Sibling
                </button>
            </div>

            <div class="card-body" id="siblingContainer">
                <div class="text-center  py-4" id="noSiblingsMessage">
                    <i class="fas fa-user-plus fa-3x mb-3"></i>
                    <p>No siblings added yet. Click "Add Sibling" to start adding.</p>
                </div>
                <!-- Dynamic rows will be added here -->
            </div>

            <div class="card-footer text-end" id="formFooter" style="display: none;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>Save Siblings
                </button>
            </div>
        </div>
    </form>
</div>


{{-- TEMPLATE --}}
<template id="siblingTemplate">
    <div class="sibling-row card border-success mb-3" style="display: none;">
        <div class="card-header bg-light py-2">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="mb-0 text-success">
                        <i class="fas fa-user-friends mr-2"></i>New Sibling
                    </h6>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-danger btn-sm removeSibling">
                        <i class="fas fa-trash-alt mr-1"></i>Remove
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- SEARCH INPUT -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        <i class="fas fa-search mr-1"></i>Search Sibling
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search text-success"></i>
                        </span>
                        <input type="text"
                            name="siblings[__INDEX__][search_value]"
                            class="form-control sibling-search-value"
                            placeholder="Enter Registration Number or Email">
                    </div>
                    <small class="">Enter Registration Number or Email</small>
                    
                    <input type="hidden" name="siblings[__INDEX__][student_hash_id]" class="sibling-hash-id">
                    <input type="hidden" name="siblings[__INDEX__][class_id]" class="sibling-class-id">
                    <input type="hidden" name="siblings[__INDEX__][section_id]" class="sibling-section-id">
                    <input type="hidden" name="siblings[__INDEX__][registration_number]" class="sibling-reg-number">
                </div>
            </div>

            {{-- Sibling Details Card - Hidden by default --}}
            <div class="sibling-details-card mt-3" style="display: none;">
                <div class="card border-success">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0 text-success">
                            <i class="fas fa-info-circle mr-2"></i>Sibling Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label small">Registration No.</label>
                                <div class="fw-bold sibling-reg-display">-</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small">Email</label>
                                <div class="fw-bold sibling-email-display">-</div>
                                <input type="hidden" name="siblings[__INDEX__][email]" class="sibling-email">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small">Full Name</label>
                                <div class="fw-bold sibling-name-display">-</div>
                                <input type="hidden" name="siblings[__INDEX__][name]" class="sibling-name">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small">Date of Birth</label>
                                <div class="fw-bold sibling-dob-display">-</div>
                                <input type="hidden" name="siblings[__INDEX__][dob]" class="sibling-dob">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small">Class</label>
                                <div class="fw-bold sibling-class-display">-</div>
                                <input type="hidden" name="siblings[__INDEX__][class]" class="sibling-class">
                            </div>

                            <div class="col-md-3 d-none">
                                <label class="form-label small">Section</label>
                                <div class="fw-bold sibling-section-display">-</div>
                            </div>
                            <input type="hidden" name="siblings[__INDEX__][section]" class="sibling-section">

                            <div class="col-md-3">
                                <label class="form-label small">Mobile</label>
                                <div class="fw-bold sibling-mobile-display">-</div>
                                <input type="hidden" name="siblings[__INDEX__][mobile]" class="sibling-mobile">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sibling Not Found Message --}}
            <div class="sibling-not-found alert alert-warning mt-3" style="display: none;">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                No student found with the provided information.
            </div>

            {{-- Sibling Loading Spinner --}}
            <div class="sibling-spinner text-center mt-3" style="display: none;">
                <div class="spinner-border text-success spinner-border-sm" role="status">
                    <span class="visually-hidden"></span>
                </div>
                <span class="ms-2 small">Searching for sibling...</span>
            </div>
        </div>
    </div>
</template>

<!-- Add necessary CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
let siblingIndex = 0;

/* =============================
   TOAST INITIALIZATION
============================= */

function initializeToastr() {
    if (typeof toastr !== 'undefined') {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: "3000",
            extendedTimeOut: "1000"
        };
    }
}

function showToast(type, message) {
    if (typeof toastr !== 'undefined') {
        toastr[type](message);
    } else {
        console.log(`[${type}] ${message}`);
        if (type === 'error') alert(message);
    }
}

/* =============================
   FETCH STUDENT
============================= */

async function fetchStudentDetails(searchValue, targetRow = null) {
    if (!searchValue || searchValue.length < 3) return;

    // Show appropriate spinner
    if (targetRow) {
        // For sibling search
        const row = targetRow.closest('.sibling-row');
        row.querySelector('.sibling-details-card').style.display = 'none';
        row.querySelector('.sibling-not-found').style.display = 'none';
        row.querySelector('.sibling-spinner').style.display = 'block';
        row.style.display = 'block';
    } else {
        // For main student search
        document.getElementById('studentDetailsCard').style.display = 'none';
        document.getElementById('noResultsMessage').style.display = 'none';
        document.getElementById('searchSpinner').style.display = 'block';
    }

    try {
        const response = await fetch('{{ route("get.student.by.reg.email") }}', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                search_term: searchValue
            })
        });

        const result = await response.json();

        if (targetRow) {
            // Handle sibling search
            const row = targetRow.closest('.sibling-row');
            row.querySelector('.sibling-spinner').style.display = 'none';

            if (!result.success) {
                row.querySelector('.sibling-not-found').style.display = 'block';
                clearSiblingFields(row);
                return;
            }

            const student = result.data;

            /* ===== PREVENT MAIN STUDENT AS SIBLING ===== */
            const mainHash = document.getElementById("student_hash_id")?.value;

            if (student.student_hash_id === mainHash) {
                row.querySelector('.sibling-not-found').style.display = 'block';
                row.querySelector('.sibling-not-found').innerHTML = 
                    '<i class="fas fa-exclamation-triangle mr-2"></i>Main student cannot be added as sibling';
                clearSiblingFields(row);
                return;
            }

            /* ===== PREVENT DUPLICATE SIBLINGS ===== */
            if (isDuplicateSibling(student.student_hash_id)) {
                row.querySelector('.sibling-not-found').style.display = 'block';
                row.querySelector('.sibling-not-found').innerHTML = 
                    '<i class="fas fa-exclamation-triangle mr-2"></i>Sibling already added';
                clearSiblingFields(row);
                return;
            }

            // Populate sibling details
            populateSiblingFields(row, student);
            row.querySelector('.sibling-details-card').style.display = 'block';
            row.querySelector('.sibling-search-value').classList.add('is-valid');

        } else {
            // Handle main student search
            document.getElementById('searchSpinner').style.display = 'none';

            if (!result.success) {
                document.getElementById('noResultsMessage').style.display = 'block';
                clearMainStudentFields();
                document.getElementById('addSiblingBtn').disabled = true;
                return;
            }

            const student = result.data;
            populateMainStudentFields(student);
            document.getElementById('studentDetailsCard').style.display = 'block';
            document.getElementById('search_value').classList.add('is-valid');
            document.getElementById('addSiblingBtn').disabled = false;
        }

    } catch (error) {
        console.error(error);
        showToast("error", "Error fetching student details");
        
        if (targetRow) {
            const row = targetRow.closest('.sibling-row');
            row.querySelector('.sibling-spinner').style.display = 'none';
            row.querySelector('.sibling-not-found').style.display = 'block';
        } else {
            document.getElementById('searchSpinner').style.display = 'none';
            document.getElementById('noResultsMessage').style.display = 'block';
        }
    }
}

/* =============================
   POPULATE FIELDS
============================= */

function populateMainStudentFields(student) {
    document.getElementById("main_registration_number").textContent = student.registration_number || "-";
    document.getElementById("main_email").textContent = student.email || student.father_email || student.mother_email || "-";
    document.getElementById("main_student_name").textContent = student.full_name || "-";
    document.getElementById("main_dob").textContent = student.dob || "-";
    document.getElementById("main_mobile").textContent = student.mobile || "-";
    document.getElementById("main_class").textContent = student.class || "-";
    document.getElementById("main_section").textContent = student.section || "-";
    
    document.getElementById("student_hash_id").value = student.student_hash_id || "";
    document.getElementById("main_class_id").value = student.classID || "";
    document.getElementById("main_section_id").value = student.sectionID || "";
    document.getElementById("hidden_main_registration_number").value = student.registration_number || "";
    document.getElementById("hidden_main_email").value = student.email || student.father_email || student.mother_email || "";
}

function populateSiblingFields(row, student) {
    // Display fields
    row.querySelector(".sibling-reg-display").textContent = student.registration_number || "-";
    row.querySelector(".sibling-email-display").textContent = student.email || student.father_email || student.mother_email || "-";
    row.querySelector(".sibling-name-display").textContent = student.full_name || "-";
    row.querySelector(".sibling-dob-display").textContent = student.dob || "-";
    row.querySelector(".sibling-class-display").textContent = student.class || "-";
    row.querySelector(".sibling-section-display").textContent = student.section || "-";
    row.querySelector(".sibling-mobile-display").textContent = student.mobile || "-";

    // Hidden fields for form submission
    row.querySelector(".sibling-name").value = student.full_name || "";
    row.querySelector(".sibling-email").value = student.email || student.father_email || student.mother_email || "";
    row.querySelector(".sibling-class").value = student.class || "";
    row.querySelector(".sibling-dob").value = student.dob || "";
    row.querySelector(".sibling-mobile").value = student.mobile || "";
    row.querySelector(".sibling-section").value = student.section || "";
    row.querySelector(".sibling-hash-id").value = student.student_hash_id || "";
    row.querySelector(".sibling-class-id").value = student.classID || "";
    row.querySelector(".sibling-section-id").value = student.sectionID || "";
    row.querySelector(".sibling-reg-number").value = student.registration_number || "";

    // Update search input: preserve user's original entry if it's an email
    const searchInput = row.querySelector(".sibling-search-value");
    const currentSearch = (searchInput.value || '').trim();
    if (!/@/.test(currentSearch)) {
        // if user didn't enter an email, replace with registration number
        searchInput.value = student.registration_number || "";
    }
}

function clearMainStudentFields() {
    document.getElementById("main_registration_number").textContent = "-";
    document.getElementById("main_email").textContent = "-";
    document.getElementById("main_student_name").textContent = "-";
    document.getElementById("main_dob").textContent = "-";
    document.getElementById("main_mobile").textContent = "-";
    document.getElementById("main_class").textContent = "-";
    document.getElementById("main_section").textContent = "-";
    
    document.getElementById("student_hash_id").value = "";
    document.getElementById("main_class_id").value = "";
    document.getElementById("main_section_id").value = "";
    document.getElementById("hidden_main_registration_number").value = "";
    document.getElementById("hidden_main_email").value = "";
}

function clearSiblingFields(row) {
    row.querySelector(".sibling-name-display").textContent = "-";
    row.querySelector(".sibling-email-display").textContent = "-";
    row.querySelector(".sibling-class-display").textContent = "-";
    row.querySelector(".sibling-reg-display").textContent = "-";
    row.querySelector(".sibling-dob-display").textContent = "-";
    row.querySelector(".sibling-section-display").textContent = "-";
    row.querySelector(".sibling-mobile-display").textContent = "-";

    row.querySelector(".sibling-name").value = "";
    row.querySelector(".sibling-email").value = "";
    row.querySelector(".sibling-class").value = "";
    row.querySelector(".sibling-dob").value = "";
    row.querySelector(".sibling-mobile").value = "";
    row.querySelector(".sibling-section").value = "";
    row.querySelector(".sibling-hash-id").value = "";
    row.querySelector(".sibling-class-id").value = "";
    row.querySelector(".sibling-section-id").value = "";
    row.querySelector(".sibling-reg-number").value = "";
}

/* =============================
   DUPLICATE CHECK
============================= */

function isDuplicateSibling(hashId) {
    const allHashes = document.querySelectorAll(".sibling-hash-id");
    for (let el of allHashes) {
        if (el.value === hashId) return true;
    }
    return false;
}

/* =============================
   DEBOUNCE
============================= */

function debounce(func, wait) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func(...args), wait);
    };
}

const debouncedFetch = debounce(fetchStudentDetails, 500);

/* =============================
   DOM READY
============================= */

document.addEventListener("DOMContentLoaded", function () {
    initializeToastr();

    /* MAIN AUTO SEARCH */
    const mainSearchInput = document.getElementById("search_value");

    if (mainSearchInput) {
        mainSearchInput.addEventListener("input", function () {
            const value = this.value.trim();
            if (value.length >= 3) {
                debouncedFetch(value);
            } else {
                document.getElementById('studentDetailsCard').style.display = 'none';
                document.getElementById('noResultsMessage').style.display = 'none';
                document.getElementById('searchSpinner').style.display = 'none';
                clearMainStudentFields();
                document.getElementById('addSiblingBtn').disabled = true;
            }
        });
    }

    /* ADD SIBLING */
    const addBtn = document.getElementById("addSiblingBtn");

    if (addBtn) {
        addBtn.addEventListener("click", function () {
            document.getElementById('noSiblingsMessage').style.display = 'none';
            document.getElementById('formFooter').style.display = 'block';

            let template = document
                .getElementById("siblingTemplate")
                .innerHTML
                .replace(/__INDEX__/g, siblingIndex);

            document
                .getElementById("siblingContainer")
                .insertAdjacentHTML("beforeend", template);

            const newRow = document.querySelector(
                "#siblingContainer .sibling-row:last-child"
            );

            newRow.style.display = 'block';

            const input = newRow.querySelector(".sibling-search-value");

            input.addEventListener("input", function () {
                const value = this.value.trim();
                if (value.length >= 3) {
                    debouncedFetch(value, input);
                } else {
                    const row = this.closest('.sibling-row');
                    row.querySelector('.sibling-details-card').style.display = 'none';
                    row.querySelector('.sibling-not-found').style.display = 'none';
                    row.querySelector('.sibling-spinner').style.display = 'none';
                    clearSiblingFields(row);
                }
            });

            siblingIndex++;
        });
    }

    /* REMOVE SIBLING */
    document.addEventListener("click", function (e) {
        if (
            e.target.classList.contains("removeSibling") ||
            e.target.closest(".removeSibling")
        ) {
            const row = e.target.closest(".sibling-row");
            row.remove();
            
            // Check if no siblings left
            if (document.querySelectorAll('.sibling-row').length === 0) {
                document.getElementById('noSiblingsMessage').style.display = 'block';
                document.getElementById('formFooter').style.display = 'none';
            }
        }
    });

    /* FORM VALIDATION */
    const form = document.getElementById('siblingForm');

    if (form) {
        form.addEventListener("submit", function (e) {
            const mainHash = document.getElementById("student_hash_id").value;

            if (!mainHash) {
                e.preventDefault();
                showToast("error", "Please select a valid main student");
                return;
            }

            const siblingRows = document.querySelectorAll(".sibling-row");

            for (let row of siblingRows) {
                const searchVal = row.querySelector(".sibling-search-value").value;
                const hashId = row.querySelector(".sibling-hash-id").value;

                if (searchVal && !hashId) {
                    e.preventDefault();
                    showToast("error", "Please select valid siblings only.");
                    return;
                }
            }

            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = 
                    '<span class="spinner-border spinner-border-sm"></span> Saving...';
            }
        });
    }
});
</script>
@endsection
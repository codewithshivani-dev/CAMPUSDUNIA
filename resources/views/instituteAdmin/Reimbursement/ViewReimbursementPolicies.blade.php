@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    .detail-card {
        background: white;
        border-radius: 32px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.03);
        padding: 2.2rem;
        max-width: 900px;
        margin: 0 auto;
    }

    .info-section {
        background: #fafcfd;
        border: 1px solid #edf2f7;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 1.5px solid #edf2f7;
        padding-bottom: 8px;
    }

    .section-title i {
        color: #2563eb;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    @media (max-width: 600px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }
    }

    .badge-pill {
        padding: 0.25rem 0.8rem;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .badge-active { background: #d1fae5; color: #065f46; }
    .badge-inactive { background: #fee2e2; color: #991b1b; }
    .badge-draft { background: #fef3c7; color: #92400e; }
    .badge-category { background: #e0e7ff; color: #3730a3; }

    .meta-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .meta-table tr {
        border-bottom: 1px solid #edf2f7;
    }

    .meta-table tr:last-child {
        border-bottom: none;
    }

    .meta-table td {
        padding: 10px 0;
        color: #334155;
    }

    .meta-table td.label {
        font-weight: 600;
        color: #64748b;
        width: 35%;
    }

    .meta-table td.val {
        font-weight: 700;
        color: #0f172a;
    }

    .sub-box {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px;
        font-size: 0.85rem;
    }

    .btn-outline-lg {
        background: white;
        border: 1.5px solid #cbd5e1;
        color: #475569;
        padding: 0.8rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: 0.2s;
    }

    .btn-outline-lg:hover {
        background: #f8fafc;
        border-color: #94a3b8;
    }
</style>

<div class="p-2">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px; margin: 0;">
                <i class="fas fa-file-contract" style="color: #2563eb; background: #eff6ff; padding: 10px; border-radius: 16px;"></i>
                Policy Configuration Auditor
            </h1>
            <p style="color: #64748b; margin-top: 4px; font-weight: 500; font-size: 0.95rem;">
                Detailed lookup of reimbursement rates, limits, and claim rules.
            </p>
        </div>
        <a href="{{ route('reimbursement.policies') }}" class="btn-outline-lg" style="padding: 0.5rem 1.2rem; font-size: 0.85rem;">
            <i class="fas fa-arrow-left"></i> Back to Master
        </a>
    </div>

    <div class="detail-card">
        <!-- Section 1: Basic Information -->
        <div class="info-section">
            <div class="section-title">
                <i class="fas fa-info-circle"></i>
                Basic Configuration Details
            </div>
            <div class="grid-2">
                <table class="meta-table">
                    <tr><td class="label">Policy Name</td><td class="val" id="p-name">-</td></tr>
                    <tr><td class="label">Policy Code</td><td class="val" id="p-code">-</td></tr>
                    <tr><td class="label">Category</td><td class="val"><span class="badge-pill badge-category" id="p-category">-</span></td></tr>
                </table>
                <table class="meta-table">
                    <tr><td class="label">Effective Period</td><td class="val" id="p-period">-</td></tr>
                    <tr><td class="label">Status</td><td class="val" id="p-status">-</td></tr>
                    <tr><td class="label">Audited On</td><td class="val">{{ date('Y-m-d H:i') }}</td></tr>
                </table>
            </div>
            <div style="margin-top: 15px; font-size: 0.88rem; line-height: 1.4; color: #475569;">
                <strong>Scope Description:</strong> <span id="p-desc">No description available.</span>
            </div>
        </div>

        <!-- Section 2: Reimbursement Values -->
        <div class="info-section">
            <div class="section-title">
                <i class="fas fa-coins"></i>
                Reimbursement Rates & Allowed Ranges
            </div>
            <div id="p-values-container" style="display: flex; flex-direction: column; gap: 10px;">
                <!-- Dynamically loaded values -->
            </div>
        </div>

        <!-- Section 3 & 4 Grid -->
        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 1.5rem;">
            <!-- Section 3: Claim Validation Rules -->
            <div class="info-section" style="margin-bottom: 0;">
                <div class="section-title">
                    <i class="fas fa-gavel"></i>
                    Claim Validation Constraints
                </div>
                <table class="meta-table" style="font-size: 0.82rem;">
                    <tr><td class="label">Claims Frequency</td><td id="p-claim-freq">-</td></tr>
                    <tr><td class="label">Amount Range</td><td id="p-claim-amt">-</td></tr>
                    <tr><td class="label">Submission Window</td><td id="p-claim-window">-</td></tr>
                    <tr><td class="label">Future Dates</td><td id="p-claim-future">-</td></tr>
                    <tr><td class="label">Backdated Claims</td><td id="p-claim-backdated">-</td></tr>
                    <tr><td class="label">Bill Submission</td><td id="p-claim-bill">-</td></tr>
                    <tr><td class="label">Duplicate Bills</td><td id="p-claim-duplicate">-</td></tr>
                    <tr><td class="label">Weekend/Holiday</td><td id="p-claim-restricted">-</td></tr>
                </table>
            </div>

            <!-- Section 4: Settlement Rules -->
            <div class="info-section" style="margin-bottom: 0;">
                <div class="section-title">
                    <i class="fas fa-check-double"></i>
                    Settlement Parameters
                </div>
                <table class="meta-table" style="font-size: 0.82rem;">
                    <tr><td class="label">timeline window</td><td id="p-settle-time">-</td></tr>
                    <tr><td class="label">Settlement Mode(s)</td><td id="p-settle-modes">-</td></tr>
                    <tr><td class="label">Auto Settlement</td><td id="p-settle-auto">-</td></tr>
                    <tr><td class="label">Partial settlement</td><td id="p-settle-partial">-</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Extract ID parameters from URL path
    const pathParts = window.location.pathname.split('/');
    const targetPolicyId = pathParts[pathParts.length - 1];

    const policies = JSON.parse(localStorage.getItem("reimbursement_policies")) || [];
    const p = policies.find(item => item.id === targetPolicyId);

    if (!p) {
        alert("Requested policy definition does not exist in browser records.");
        window.location.href = "{{ route('reimbursement.policies') }}";
    } else {
        renderPolicyView(p);
    }

    function renderPolicyView(policy) {
        document.getElementById("p-name").textContent = policy.name;
        document.getElementById("p-code").textContent = policy.code || "N/A";
        document.getElementById("p-category").textContent = policy.category;
        document.getElementById("p-period").textContent = `From ${policy.effective_from || 'N/A'} to ${policy.effective_to || 'Ongoing'}`;
        document.getElementById("p-desc").textContent = policy.description || "None";
        
        let statusBadge = `<span class="badge-pill badge-active"><i class="fas fa-check"></i> Active</span>`;
        if (policy.status === 'inactive') statusBadge = `<span class="badge-pill badge-inactive"><i class="fas fa-times"></i> Inactive</span>`;
        else if (policy.status === 'draft') statusBadge = `<span class="badge-pill badge-draft"><i class="fas fa-edit"></i> Draft</span>`;
        document.getElementById("p-status").innerHTML = statusBadge;

        // Render Values
        const valContainer = document.getElementById("p-values-container");
        valContainer.innerHTML = "";

        if (policy.category === 'travel' && policy.values) {
            valContainer.innerHTML = `
                <div class="sub-box">
                    <strong style="display: block; margin-bottom: 6px;"><i class="fas fa-car"></i> Private Vehicles Limits</strong>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px;">
                        <div><strong>Two Wheeler:</strong> Min ₹${policy.values.private_wheeler?.min} - Max ₹${policy.values.private_wheeler?.max} (${policy.values.private_wheeler?.rate}/KM)</div>
                        <div><strong>Car:</strong> Min ₹${policy.values.private_car?.min} - Max ₹${policy.values.private_car?.max} (${policy.values.private_car?.rate}/KM)</div>
                        <div><strong>Auto:</strong> Min ₹${policy.values.private_auto?.min} - Max ₹${policy.values.private_auto?.max} (${policy.values.private_auto?.rate}/KM)</div>
                    </div>
                </div>
                <div class="sub-box">
                    <strong style="display: block; margin-bottom: 6px;"><i class="fas fa-bus"></i> Public Transit Allowances</strong>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px;">
                        <div><strong>Bus:</strong> Min ₹${policy.values.public_bus?.min} - Max ₹${policy.values.public_bus?.max}</div>
                        <div><strong>Train:</strong> Min ₹${policy.values.public_train?.min} - Max ₹${policy.values.public_train?.max}</div>
                        <div><strong>Flight:</strong> Min ₹${policy.values.public_flight?.min} - Max ₹${policy.values.public_flight?.max}</div>
                    </div>
                </div>
            `;
        } else if (policy.category === 'accommodation' && policy.values) {
            valContainer.innerHTML = `
                <div class="sub-box">
                    <strong style="display: block; margin-bottom: 6px;"><i class="fas fa-hotel"></i> Room Categories</strong>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px;">
                        <div><strong>Basic Class:</strong> ₹${policy.values.basic?.min} - ₹${policy.values.basic?.max}</div>
                        <div><strong>Deluxe Class:</strong> ₹${policy.values.deluxe?.min} - ₹${policy.values.deluxe?.max}</div>
                        <div><strong>Premium Class:</strong> ₹${policy.values.premium?.min} - ₹${policy.values.premium?.max}</div>
                    </div>
                </div>
            `;
        } else if (policy.category === 'food' && policy.values) {
            valContainer.innerHTML = `
                <div class="sub-box">
                    <strong style="display: block; margin-bottom: 6px;"><i class="fas fa-utensils"></i> Food Allotment Limits</strong>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                        <div><strong>Breakfast:</strong> Max ₹${policy.values.breakfast?.max}</div>
                        <div><strong>Lunch:</strong> Max ₹${policy.values.lunch?.max}</div>
                        <div><strong>Dinner:</strong> Max ₹${policy.values.dinner?.max}</div>
                        <div><strong>Two Meals:</strong> Max ₹${policy.values.two_meals?.max}</div>
                        <div><strong>Three Meals:</strong> Max ₹${policy.values.three_meals?.max}</div>
                    </div>
                </div>
            `;
        } else if (policy.category === 'custom' && policy.custom_fields) {
            let fieldsHtml = policy.custom_fields.map(f => `
                <div style="padding: 6px 12px; background: white; border: 1px solid #edf2f7; border-radius: 8px; font-size: 0.8rem; display: flex; justify-content: space-between;">
                    <strong>${f.name} (${f.type})</strong>
                    <span>Required: ${f.required} | Use Calc: ${f.use_calc === 'Yes' ? 'YES (Multiplier Rate: ₹'+f.rate+'/unit)' : 'No'}</span>
                </div>
            `).join("");
            valContainer.innerHTML = `
                <div class="sub-box" style="display: flex; flex-direction: column; gap: 8px;">
                    <strong style="display: block;"><i class="fas fa-sliders-h"></i> Dynamic Field Builder Form Definitions</strong>
                    ${fieldsHtml}
                </div>
            `;
        } else if (policy.values) {
            valContainer.innerHTML = `
                <div class="sub-box">
                    <strong>Standard Range Limit settings</strong>
                    <div style="margin-top: 5px;">Limit Thresholds: Min ₹${policy.values.fixed?.min} - Max ₹${policy.values.fixed?.max} | Bill Required: ${policy.values.fixed?.bill}</div>
                </div>
            `;
        }

        // Section 3: Claims Validations
        const c = policy.claims || {};
        document.getElementById("p-claim-freq").textContent = c.frequency_type === 'unlimited' ? 'Unlimited' : `${c.frequency_value} Claims per ${c.frequency_type}`;
        document.getElementById("p-claim-amt").textContent = `₹${c.min_claim_amt} - ₹${c.max_claim_amt}`;
        document.getElementById("p-claim-window").textContent = `${c.submission_within} Days after ${c.submission_type === 'bill_date' ? 'Bill Date' : 'Expense Date'}`;
        document.getElementById("p-claim-future").textContent = c.allow_future === 'Yes' ? 'Allowed' : 'Prohibited';
        document.getElementById("p-claim-backdated").textContent = c.allow_backdated === 'Yes' ? `Allowed (Max backdate: ${c.max_back_date} Days)` : 'Prohibited';
        document.getElementById("p-claim-bill").textContent = c.bill_required === 'Yes' ? `Required (${c.allow_multi_bills === 'Yes' ? 'Multiple allowed, Max ' + c.max_bills : 'Single bill'})` : 'Optional';
        document.getElementById("p-claim-duplicate").textContent = c.allow_same_bill === 'Yes' ? 'Allowed' : 'Must be unique';
        document.getElementById("p-claim-restricted").textContent = `Weekend: ${c.allow_weekend} | Holiday: ${c.allow_holiday}`;

        // Section 4: Settlement
        const s = policy.settlement || {};
        document.getElementById("p-settle-time").textContent = `${s.timeline} Days`;
        document.getElementById("p-settle-modes").textContent = s.modes ? s.modes.map(m => m.replace("_", " ")).join(", ").toUpperCase() : "PAYROLL";
        document.getElementById("p-settle-auto").textContent = s.auto_settlement || "No";
        document.getElementById("p-settle-partial").textContent = s.allow_partial || "No";
    }
</script>

@endsection

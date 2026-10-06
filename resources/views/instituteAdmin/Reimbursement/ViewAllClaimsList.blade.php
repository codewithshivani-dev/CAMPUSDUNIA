@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #eff6ff;
            --success: #10b981;
            --success-light: #f0fdf4;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            --warning: #f59e0b;
            --warning-light: #fffbeb;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
        }

        .page-header {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            border-radius: 24px;
            padding: 2rem 2.5rem;
            margin-bottom: 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(15, 23, 42, 0.15);
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: -60%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .header-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1.2rem;
        }

        .header-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: white;
            box-shadow: 0 4px 16px rgba(37, 99, 235, 0.3);
        }

        .header-title {
            color: white;
            margin: 0;
        }

        .header-title h1 {
            font-size: 1.8rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .header-title p {
            margin: 4px 0 0 0;
            font-size: 0.95rem;
            font-weight: 400;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: white;
            padding: 0.6rem 1.4rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-header:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            color: white;
        }

        .stats-badge {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            color: #94a3b8;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .stats-badge strong {
            color: white;
            font-weight: 700;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 24px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04);
            padding: 1.5rem;
            transition: all 0.3s;
        }

        .glass-card:hover {
            transform: translateY(-2px);
        }

        .kpi-title {
            font-size: 0.8rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #64748b;
        }

        .kpi-val {
            font-size: 2rem;
            font-weight: 850;
            color: #0f172a;
            margin-top: 4px;
        }

        .kpi-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .btn-outline {
            background: transparent;
            border: 1.5px solid #e2e8f0;
            color: #475569;
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-outline:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        .btn-review {
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            color: white !important;
            border: none;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.8rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none !important;
            transition: all 0.2s;
        }

        .btn-review:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }

        .table-wrapper {
            background: white;
            border-radius: 28px;
            border: 1px solid #e2e8f0;
            overflow-x: auto;
            box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.02);
            width: 100%;
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
        }

        .modern-table th {
            background: #f8fafc;
            padding: 1rem 1.25rem;
            color: #475569;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 1.5px solid #edf2f7;
            text-align: left;
            white-space: nowrap;
        }

        .modern-table td {
            padding: 1.1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 0.9rem;
        }

        .modern-table tbody tr:hover td {
            background: #f8fafc;
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

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-settled {
            background: #e0e7ff;
            color: #3730a3;
        }

        .badge-mixed {
            background: #f1f5f9;
            color: #475569;
        }

        .filter-section {
            padding: 0.75rem 1.25rem;
            display: flex;
            gap: 1rem;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
        }

        .filter-section input,
        .filter-section select {
            padding: 0.5rem 1rem;
            border-radius: 30px;
            border: 1px solid #e2e8f0;
            outline: none;
            font-size: 0.85rem;
            font-family: inherit;
            background: white;
            transition: all 0.2s;
        }

        .filter-section input:focus,
        .filter-section select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
        }

        .filter-section .results-count {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
        }

        .filter-section .filter-left {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
            flex: 1;
        }

        .master-id-link {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
            font-family: monospace;
            font-size: 0.85rem;
        }

        .master-id-link:hover {
            text-decoration: underline;
        }

        .policy-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .policy-tag {
            background: #eff6ff;
            color: #2563eb;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
            }

            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .filter-section {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-section .filter-left {
                flex-direction: column;
            }

            .filter-section input,
            .filter-section select {
                width: 100%;
            }

            .modern-table {
                font-size: 0.8rem;
            }

            .modern-table th,
            .modern-table td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>

    <div class="p-2">
        <!-- Modern Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fas fa-list-check"></i>
                    </div>
                    <div class="header-title">
                        <h1>Reimbursement Claims</h1>
                        <p>Review and manage all submitted reimbursement batches</p>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="/institute-admin/create-reimbursement-claim" class="btn-header">
                        <i class="fas fa-plus-circle"></i> Create New Claim
                    </a>
                    <span class="stats-badge">
                        <i class="fas fa-clock" style="color: #f59e0b;"></i>
                        <strong id="pending-count">0</strong> Pending
                    </span>
                </div>
            </div>
        </div>

        <!-- Stats Row -->
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
            <div class="glass-card" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="kpi-title">Total Batches</div>
                    <div class="kpi-val" id="kpi-total">0</div>
                </div>
                <div class="kpi-icon" style="background: #eff6ff; color: #2563eb;"><i class="fas fa-layer-group"></i></div>
            </div>
            <div class="glass-card" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="kpi-title">Pending</div>
                    <div class="kpi-val" style="color: #f59e0b;" id="kpi-pending">0</div>
                </div>
                <div class="kpi-icon" style="background: #fffbeb; color: #f59e0b;"><i class="fas fa-clock"></i></div>
            </div>
            <div class="glass-card" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="kpi-title">Approved</div>
                    <div class="kpi-val" style="color: #22c55e;" id="kpi-approved">0</div>
                </div>
                <div class="kpi-icon" style="background: #f0fdf4; color: #22c55e;"><i class="fas fa-check-double"></i></div>
            </div>
            <div class="glass-card" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="kpi-title">Declined</div>
                    <div class="kpi-val" style="color: #ef4444;" id="kpi-rejected">0</div>
                </div>
                <div class="kpi-icon" style="background: #fef2f2; color: #ef4444;"><i class="fas fa-ban"></i></div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <div class="filter-left">
                <input type="text" id="search-input" placeholder="Search employee, batch ID or policy..."
                    style="width: 250px;">
                <select id="filter-status">
                    <option value="all">All Statuses</option>
                    <option value="pending">Pending Review</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Declined</option>
                    <option value="settled">Settled</option>
                    <option value="mixed">Mixed Status</option>
                </select>
                <select id="filter-policy">
                    <option value="all">All Policies</option>
                </select>
                <button class="btn-outline" onclick="loadClaimBatches()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
            <div class="results-count" id="results-count">Loading batches...</div>
        </div>

        <!-- Table -->
        <div class="table-wrapper">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Batch ID</th>
                        <th>Employee</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Policies</th>
                        <th>Claims</th>
                        <th>Total Amount</th>
                        <th>Submission Date</th>
                        <th>Status</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="claims-table-body">
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #2563eb;"></i>
                            <div style="margin-top: 10px; color: #94a3b8;">Loading batches...</div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div id="table-empty-state" style="display: none; padding: 4rem 2rem; text-align: center; color: #94a3b8;">
                <i class="fas fa-inbox" style="font-size: 3rem; color: #e2e8f0; margin-bottom: 1rem; display: block;"></i>
                <h3 style="margin: 0; color: #475569;">No Claim Batches Found</h3>
                <p style="margin: 6px 0 0 0; font-size: 0.9rem;">No reimbursement claim batches have been submitted yet.</p>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            document.querySelector('input[name="_token"]')?.value;

        let allClaims = [];
        let batches = [];

        function showToast(message, type) {
            type = type || 'success';
            var container = document.createElement('div');
            container.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;';
            var colors = { success: '#22c55e', error: '#ef4444' };
            var toast = document.createElement('div');
            toast.style.cssText = 'background:white;border-radius:12px;padding:16px 24px;box-shadow:0 10px 40px rgba(0,0,0,0.15);margin-bottom:10px;display:flex;align-items:center;gap:12px;min-width:300px;border-left:4px solid ' + (colors[type] || '#2563eb') + ';';
            var iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            toast.innerHTML = '<i class="fas ' + iconClass + '" style="color:' + (colors[type] || '#2563eb') + ';font-size:1.2rem;"></i><span>' + message + '</span>';
            container.appendChild(toast);
            document.body.appendChild(container);
            setTimeout(function () {
                toast.style.opacity = '0';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(function () { container.remove(); }, 300);
            }, 4000);
        }

        async function loadClaimBatches() {
            try {
                var response = await fetch('/institute-admin/reimbursement-claims/approval-list', {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                var result = await response.json();

                console.log('Approval claims response:', result);

                if (result.success) {
                    allClaims = result.data || [];
                    console.log('Loaded claims count:', allClaims.length);
                    groupIntoBatches();
                    populatePolicyFilter();
                    updateStats();
                    applyFilters();
                } else {
                    console.log('Failed to load claims:', result.message);
                    allClaims = [];
                    batches = [];
                    updateStats();
                    renderTable([]);
                }
            } catch (err) {
                console.error('Error loading claims:', err);
                document.getElementById("claims-table-body").innerHTML =
                    '<tr><td colspan="10" style="text-align: center; padding: 2rem; color: #ef4444;">' +
                    '<i class="fas fa-exclamation-triangle" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>' +
                    'Failed to load claims. Please try again.</td></tr>';
            }
        }

        function groupIntoBatches() {
            var batchMap = new Map();

            allClaims.forEach(function (claim) {
                var masterId = claim.master_request_id || 'BATCH-UNKNOWN';

                if (!batchMap.has(masterId)) {
                    batchMap.set(masterId, {
                        master_request_id: masterId,
                        claims: [],
                        employee_name: claim.name || 'N/A',
                        employee_id: claim.employee_id || 'N/A',
                        designation: claim.designation || 'N/A',
                        department: claim.department || 'N/A',
                        submission_date: claim.submission_date || 'N/A',
                        policies: new Set(),
                        statuses: new Set(),
                        total_amount: 0,
                        total_claims: 0
                    });
                }

                var batch = batchMap.get(masterId);
                batch.claims.push(claim);
                batch.total_amount += parseFloat(claim.claim_amount || 0);
                batch.total_claims++;

                if (claim.policy_name) batch.policies.add(claim.policy_name);

                if (claim.status === 'pending' || claim.status === 'step1_approved') {
                    batch.statuses.add('pending');
                } else if (claim.status === 'approved') {
                    batch.statuses.add('approved');
                } else if (claim.status === 'rejected') {
                    batch.statuses.add('rejected');
                } else if (claim.status === 'settled') {
                    batch.statuses.add('settled');
                }
            });

            batches = Array.from(batchMap.values()).map(function (batch) {
                var batchStatus = 'mixed';
                var uniqueStatuses = Array.from(batch.statuses);

                if (uniqueStatuses.length === 1) {
                    batchStatus = uniqueStatuses[0];
                } else if (uniqueStatuses.every(function (s) { return s === 'approved' || s === 'settled'; })) {
                    batchStatus = 'approved';
                } else if (uniqueStatuses.every(function (s) { return s === 'rejected'; })) {
                    batchStatus = 'rejected';
                } else if (uniqueStatuses.includes('pending')) {
                    batchStatus = 'pending';
                }

                return {
                    master_request_id: batch.master_request_id,
                    claims: batch.claims,
                    employee_name: batch.employee_name,
                    employee_id: batch.employee_id,
                    designation: batch.designation,
                    department: batch.department,
                    submission_date: batch.submission_date,
                    policies: Array.from(batch.policies),
                    statuses: batch.statuses,
                    total_amount: batch.total_amount,
                    total_claims: batch.total_claims,
                    batch_status: batchStatus
                };
            });

            batches.sort(function (a, b) {
                var dateA = new Date(a.submission_date);
                var dateB = new Date(b.submission_date);
                return dateB - dateA;
            });
        }

        function populatePolicyFilter() {
            var policyFilter = document.getElementById('filter-policy');
            var allPolicies = new Set();

            batches.forEach(function (batch) {
                batch.policies.forEach(function (p) { allPolicies.add(p); });
            });

            policyFilter.innerHTML = '<option value="all">All Policies</option>';

            Array.from(allPolicies).sort().forEach(function (policy) {
                var option = document.createElement('option');
                option.value = policy;
                option.textContent = policy;
                policyFilter.appendChild(option);
            });
        }

        function updateStats() {
            document.getElementById("kpi-total").textContent = batches.length;
            document.getElementById("kpi-pending").textContent = batches.filter(function (b) { return b.batch_status === 'pending'; }).length;
            document.getElementById("kpi-approved").textContent = batches.filter(function (b) { return b.batch_status === 'approved' || b.batch_status === 'settled'; }).length;
            document.getElementById("kpi-rejected").textContent = batches.filter(function (b) { return b.batch_status === 'rejected'; }).length;
            document.getElementById("pending-count").textContent = batches.filter(function (b) { return b.batch_status === 'pending'; }).length;
        }

        function getStatusBadge(status) {
            switch (status) {
                case 'approved':
                case 'settled':
                    return '<span class="badge-pill badge-approved"><i class="fas fa-check-circle"></i> Approved</span>';
                case 'rejected':
                    return '<span class="badge-pill badge-rejected"><i class="fas fa-times-circle"></i> Declined</span>';
                case 'pending':
                    return '<span class="badge-pill badge-pending"><i class="fas fa-clock"></i> Pending</span>';
                case 'mixed':
                    return '<span class="badge-pill badge-mixed"><i class="fas fa-layer-group"></i> Mixed</span>';
                default:
                    return '<span class="badge-pill" style="background:#f1f5f9;color:#475569;">' + status + '</span>';
            }
        }

        function renderTable(data) {
            var body = document.getElementById("claims-table-body");
            var emptyState = document.getElementById("table-empty-state");
            body.innerHTML = "";

            if (data.length === 0) {
                emptyState.style.display = "block";
                document.getElementById("results-count").textContent = "Showing 0 batches";
                return;
            }

            emptyState.style.display = "none";
            document.getElementById("results-count").textContent = 'Showing ' + data.length + ' batch(es)';

            data.forEach(function (batch) {
                var policyTags = batch.policies.slice(0, 2).map(function (p) {
                    return '<span class="policy-tag">' + p.substring(0, 20) + (p.length > 20 ? '...' : '') + '</span>';
                }).join('');

                var morePolicies = batch.policies.length > 2 ?
                    '<span class="policy-tag">+' + (batch.policies.length - 2) + '</span>' : '';

                var tr = document.createElement("tr");
                tr.innerHTML =
                    '<td>' +
                    '<a href="/institute-admin/reimbursement-claims/review/' + batch.master_request_id + '" class="master-id-link">' +
                    batch.master_request_id +
                    '</a>' +
                    '</td>' +
                    '<td>' +
                    '<div style="font-weight: 600;">' + batch.employee_name + '</div>' +
                    '<div style="font-size: 0.75rem; color: #94a3b8;">' + batch.employee_id + '</div>' +
                    '</td>' +
                    '<td><span style="font-size: 0.85rem;">' + batch.designation + '</span></td>' +
                    '<td><span style="font-size: 0.85rem;">' + batch.department + '</span></td>' +
                    '<td><div class="policy-tags">' + policyTags + morePolicies + '</div></td>' +
                    '<td><span style="font-weight: 700;">' + batch.total_claims + '</span></td>' +
                    '<td><div style="font-weight: 700; font-size: 1rem;">₹' + batch.total_amount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</div></td>' +
                    '<td style="font-size: 0.85rem;">' + batch.submission_date + '</td>' +
                    '<td>' + getStatusBadge(batch.batch_status) + '</td>' +
                    '<td style="text-align: right;">' +
                    '<a href="/institute-admin/reimbursement-claims/review/' + batch.master_request_id + '" class="btn-review">' +
                    '<i class="fas fa-search"></i> Review' +
                    '</a>' +
                    '</td>';
                body.appendChild(tr);
            });
        }

        function applyFilters() {
            var query = document.getElementById("search-input").value.toLowerCase();
            var statusFilter = document.getElementById("filter-status").value;
            var policyFilter = document.getElementById("filter-policy").value;

            var filtered = batches.filter(function (batch) {
                var nameMatch = (batch.employee_name || '').toLowerCase().includes(query);
                var idMatch = (batch.master_request_id || '').toLowerCase().includes(query);
                var policyMatch = batch.policies.some(function (p) { return p.toLowerCase().includes(query); });
                var matchesQuery = nameMatch || idMatch || policyMatch;

                var matchesStatus = true;
                if (statusFilter !== 'all') {
                    matchesStatus = batch.batch_status === statusFilter;
                }

                var matchesPolicy = true;
                if (policyFilter !== 'all') {
                    matchesPolicy = batch.policies.includes(policyFilter);
                }

                return matchesQuery && matchesStatus && matchesPolicy;
            });

            renderTable(filtered);
        }

        // Event Listeners
        document.getElementById("search-input").addEventListener("input", applyFilters);
        document.getElementById("filter-status").addEventListener("change", applyFilters);
        document.getElementById("filter-policy").addEventListener("change", applyFilters);

        // Load on page load
        document.addEventListener('DOMContentLoaded', function () {
            loadClaimBatches();
            setInterval(loadClaimBatches, 30000);
        });
    </script>
@endsection
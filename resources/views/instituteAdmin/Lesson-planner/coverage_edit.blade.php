@extends('instituteAdmin.Lesson-planner.index')

@section('styles')
<style>
.page-content { padding: 28px 32px; background: #f0f4f9; }
.card { background: white; border-radius: 24px; padding: 24px 28px; border: 1px solid #eaf0f6; }
.table-plain { width:100%; border-collapse: collapse; }
.table-plain th, .table-plain td { padding: 12px 10px; border-bottom:1px solid #eef2f6; text-align:left; }
.input-small { padding:8px 10px; border:1px solid #dce4ed; border-radius:8px; }
.btn { padding:8px 14px; border-radius:10px; cursor:pointer; }
.btn-primary { background:#2563eb; color:white; border:none; }
.btn-secondary { background:#eef2f6; color:#0a1e3c; border:none; }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="page-header" style="margin-bottom:16px;display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h2 id="pageTitle" style="margin:0;font-size:22px;">Edit Coverage</h2>
            <div id="pageSubtitle" style="color:#4b6a8b;font-size:13px;margin-top:6px;"></div>
        </div>
        <div style="display:flex;gap:8px;">
            <button class="btn btn-secondary" id="cancelBtn">Cancel</button>
            <button class="btn btn-primary" id="saveBtn">Save Changes</button>
        </div>
    </div>

    <div class="card">
        <table class="table-plain" id="topicsTable">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Topic</th>
                    <th style="width:160px;">Status</th>
                    <th style="width:260px;">Reason</th>
                    <th style="width:160px;">Date</th>
                </tr>
            </thead>
            <tbody>
                <!-- rows populated by JS -->
            </tbody>
        </table>
    </div>
</div>

<script>
async function initCoverageEdit(){
    (function(){
        function getPlanIdFromPath(){
            const parts = window.location.pathname.split('/').filter(Boolean);
            return parts[parts.length - 2] || null;
        }

        function formatDateForInput(d) {
            if(!d) return '';
            if (/^\d{4}-\d{2}-\d{2}$/.test(d)) return d;
            const dt = new Date(d);
            if (isNaN(dt)) return '';
            const yyyy = dt.getFullYear();
            const mm = String(dt.getMonth()+1).padStart(2, '0');
            const dd = String(dt.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        }

        const planId = getPlanIdFromPath();
        if (!planId) {
            alert('Invalid plan id');
            window.location.href = '/admin-lesson-planner/coverage';
            return;
        }

        // Load coverage data from backend
        async function loadPlanFromServer() {
            try {
                const response = await fetch('{{ route("lesson-planner.coverage.data") }}');
                if (!response.ok) throw new Error('Failed to load coverage data');
                const plans = await response.json();
                return plans.find(p => String(p.id) === String(planId)) || null;
            } catch (error) {
                console.error('Error loading plan:', error);
                alert('Failed to load plan data');
                window.location.href = '/admin-lesson-planner/coverage';
                return null;
            }
        }

        loadPlanFromServer().then(plan => {
            if (!plan) return;

            document.getElementById('pageTitle').innerText = 'Edit Coverage — ' + (plan.title || 'Untitled');
            document.getElementById('pageSubtitle').innerText = (plan.class || 'N/A') + ' · ' + (plan.subject || 'N/A') + ' · ' + (plan.week || '');

            const flatTopics = [];
            if (plan.dailyTopics) {
                Object.keys(plan.dailyTopics).sort().forEach(date => {
                    const arr = plan.dailyTopics[date] || [];
                    arr.forEach((t, idx) => {
                        // Use coverage_status from backend, fallback to mapping covered boolean
                        const status = t.coverage_status || (t.covered ? 'covered' : 'not_covered');
                        flatTopics.push({
                            id: t.id,
                            title: t.title || '',
                            status: status,
                            reason: t.reason || '',
                            date: t.date || date || '',
                            covered: t.covered || false,
                            weekday_info: t.weekday_info || ''
                        });
                    });
                });
            }

            if (flatTopics.length === 0) {
                document.querySelector('#topicsTable tbody').innerHTML = '<tr><td colspan="5" style="text-align:center;color:#999;">No topics found</td></tr>';
                return;
            }

            const tbody = document.querySelector('#topicsTable tbody');
            function renderRows(){
                tbody.innerHTML = '';
                flatTopics.forEach((t, i) => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${i+1}</td>
                        <td>${t.title}</td>
                        <td>
                            <select class="input-small" data-index="${i}" data-topic-id="${t.id}" name="status">
                                <option value="not_covered" ${t.status==='not_covered'?'selected':''}>Not covered</option>
                                <option value="covered" ${t.status==='covered'?'selected':''}>Covered</option>
                                <option value="partial" ${t.status==='partial'?'selected':''}>Partial</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="input-small" data-index="${i}" name="reason" value="${(t.reason||'').replace(/"/g,'&quot;')}" placeholder="Optional reason" />
                        </td>
                        <td>
                            <input type="date" class="input-small" data-index="${i}" name="date" value="${formatDateForInput(t.date)}" />
                        </td>
                    `;
                    tbody.appendChild(tr);
 
                    // Add change listener to status select
                    const statusSelect = tr.querySelector('select[name="status"]');
                    statusSelect.addEventListener('change', async function(e) {
                        const idx = parseInt(this.dataset.index, 10);
                        const topicId = this.dataset.topicId;
                        const newStatus = this.value;
                        const dateInput = tr.querySelector('input[name="date"]');
                        const reasonInput = tr.querySelector('input[name="reason"]');

                        const coveredDate = (newStatus === 'covered') ? dateInput.value : null;
                        const reason = reasonInput.value;

                        try {
                            const response = await fetch(`/admin-lesson-planner/topic/${topicId}/coverage`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                                },
                                body: JSON.stringify({
                                    coverage_status: newStatus,
                                    covered_date: coveredDate,
                                    reason: reason
                                })
                            });

                            if (response.ok) {
                                flatTopics[idx].status = newStatus;
                                // Show brief success feedback
                                const originalBg = this.style.backgroundColor;
                                this.style.backgroundColor = '#d4edda';
                                setTimeout(() => {
                                    this.style.backgroundColor = originalBg;
                                }, 500);
                            } else {
                                alert('Failed to update status');
                                this.value = flatTopics[idx].status; // revert
                            }
                        } catch (error) {
                            console.error('Error updating status:', error);
                            alert('Error updating status: ' + error.message);
                            this.value = flatTopics[idx].status; // revert
                        }
                    });
                });
            }

            renderRows();

            document.getElementById('cancelBtn').addEventListener('click', function(){
                window.location.href = '/admin-lesson-planner/coverage';
            });

            document.getElementById('saveBtn').addEventListener('click', async function(){
                const saveBtn = this;
                const originalText = saveBtn.innerText;
                saveBtn.disabled = true;
                saveBtn.innerText = 'Saving...';

                try {
                    const reasons = tbody.querySelectorAll('input[name="reason"]');
                    const dates = tbody.querySelectorAll('input[name="date"]');

                    // Update local data for reason and date
                    reasons.forEach(r => {
                        const idx = parseInt(r.dataset.index,10);
                        flatTopics[idx].reason = r.value;
                    });
                    dates.forEach(d => {
                        const idx = parseInt(d.dataset.index,10);
                        flatTopics[idx].date = d.value;
                    });

                    // Save reason and date to database (status already saved on change)
                    const savePromises = flatTopics.map(ft => {
                        return fetch(`/admin-lesson-planner/topic/${ft.id}/coverage`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: JSON.stringify({
                                coverage_status: ft.status,
                                covered_date: ft.status === 'covered' ? ft.date : null,
                                reason: ft.reason
                            })
                        });
                    });

                    const results = await Promise.all(savePromises);
                    const allSuccess = results.every(r => r.ok);

                    if (allSuccess) {
                        if (typeof showToast === 'function') {
                            showToast('Coverage updated successfully','success');
                        } else {
                            alert('Coverage updated successfully');
                        }
                        setTimeout(() => { window.location.href = '/admin-lesson-planner/coverage'; }, 300);
                    } else {
                        alert('Some updates failed. Please try again.');
                        saveBtn.disabled = false;
                        saveBtn.innerText = originalText;
                    }
                } catch (error) {
                    console.error('Error saving coverage:', error);
                    alert('Error saving coverage: ' + error.message);
                    saveBtn.disabled = false;
                    saveBtn.innerText = originalText;
                }
            });
        });

    })();
}

// Start initialization
initCoverageEdit();
</script>
@endsection

@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Fine Rules - Library Management</title>
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --primary-color: #4361ee;
    --secondary-color: #3a0ca3;
    --success-gradient: linear-gradient(135deg, #10b981, #059669);
    --success-color: #10b981;
    --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
    --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
    --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
    --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-600: #6b7280;
    --gray-700: #374151;
    --gray-900: #111827;
    --border-radius: 16px;
    --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    --hover-shadow: 0 15px 40px rgba(67, 97, 238, 0.12);
}

/* Header Styles - Enhanced */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    background: var(--primary-gradient);
    padding: 1.5rem 2rem;
    border-radius: 20px;
    box-shadow: var(--card-shadow);
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 250px;
    height: 250px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 50%;
}

.page-header::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -5%;
    width: 180px;
    height: 180px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
}

.header-left h1 {
    font-size: 1.75rem;
    font-weight: 700;
    color: white;
    margin: 0 0 0.5rem 0;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
}

.header-left p {
    color: rgba(255, 255, 255, 0.9);
    margin: 0;
    font-size: 0.9rem;
}

.btn-primary {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 0.7rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
}

.btn-primary:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    color: white;
}

/* Stats Cards - Enhanced */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 20px;
    box-shadow: var(--card-shadow);
    border: 1px solid var(--gray-200);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: var(--primary-gradient);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--hover-shadow);
}

.stat-label {
    font-size: 0.8rem;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
}

.stat-label i {
    color: var(--primary-color);
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    line-height: 1.2;
    margin-top: 8px;
}

.stat-sub {
    font-size: 0.85rem;
    color: var(--gray-600);
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

/* Filter Card - Enhanced */
.filter-card {
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--card-shadow);
    transition: all 0.3s ease;
}

.filter-card:hover {
    box-shadow: var(--hover-shadow);
}

.filter-card form {
    display: flex;
    gap: 15px;
    align-items: flex-end;
    flex-wrap: wrap;
}

.filter-card form > div {
    flex: 1;
    min-width: 180px;
}

.filter-card label {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--gray-600);
    margin-bottom: 6px;
    display: block;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-card label i {
    margin-right: 4px;
    color: var(--primary-color);
}

.filter-card select {
    width: 100%;
    padding: 0.7rem 1rem;
    border: 2px solid var(--gray-200);
    border-radius: 12px;
    font-size: 0.9rem;
    background: white;
    transition: all 0.3s;
    cursor: pointer;
}

.filter-card select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: var(--accent-glow);
}

.filter-card select:hover {
    border-color: var(--primary-color);
}

.btn-filter {
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 0.7rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: var(--accent-glow);
}

.btn-clear {
    background: var(--gray-100);
    color: var(--gray-700);
    padding: 0.7rem 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    border: 1px solid var(--gray-200);
}

.btn-clear:hover {
    background: var(--gray-200);
    transform: translateY(-2px);
    text-decoration: none;
    color: var(--gray-900);
}

/* Rules Table - Enhanced */
.rules-table {
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: 20px;
    /*overflow: hidden;*/
    box-shadow: var(--card-shadow);
}

.table-header {
    background: linear-gradient(135deg, #ffffff, #f8fafc);
    padding: 1.25rem 1.5rem;
    border-bottom: 2px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-header span:first-child {
    font-weight: 700;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    gap: 8px;
}

.table-header span:first-child i {
    color: var(--primary-color);
    font-size: 1.2rem;
}

.table-header span:last-child {
    color: var(--gray-600);
    font-size: 0.85rem;
    background: var(--gray-100);
    padding: 0.25rem 0.75rem;
    border-radius: 30px;
}


td {
    padding: 1rem 1.5rem;
    font-size: 0.9rem;
    color: var(--gray-900);
    border-bottom: 1px solid var(--gray-100);
    vertical-align: middle;
}

tr:hover td {
    background: linear-gradient(135deg, #f8fafc, #ffffff);
    transform: translateX(2px);
}

/* Badges - Enhanced */
.badge {
    padding: 0.35rem 0.9rem;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.badge-active {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
}

.badge-inactive {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
}

.rule-type {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 0.35rem 0.9rem;
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    border-radius: 30px;
    font-size: 0.8rem;
    color: var(--gray-700);
    font-weight: 500;
}

.rule-type i {
    color: var(--primary-color);
}

.amount-badge {
    font-weight: 700;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 1rem;
}

.frequency-badge {
    font-size: 0.8rem;
    color: var(--gray-600);
    background: var(--gray-100);
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    display: inline-block;
}

/* Action Buttons - Enhanced */
.btn-icon {
    padding: 0.4rem 0.9rem;
    border-radius: 10px;
    border: 1px solid var(--gray-200);
    background: white;
    color: var(--gray-600);
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    text-decoration: none;
    font-weight: 500;
    cursor: pointer;
}

.btn-icon:hover {
    background: var(--primary-gradient);
    border-color: transparent;
    font-weight: 700;
    /*color: white;*/
    transform: translateY(-2px);
    text-decoration: none;
}

.btn-icon i {
    font-size: 0.9rem;
}

/* Pagination - Enhanced */
.pagination {
    padding: 1.25rem 1.5rem;
    border-top: 1px solid var(--gray-200);
    background: white;
}

.pagination nav {
    display: flex;
    justify-content: center;
}

.pagination .page-link {
    border-radius: 10px;
    border: 1px solid var(--gray-200);
    color: var(--gray-600);
    padding: 0.5rem 1rem;
    margin: 0 3px;
    transition: all 0.3s;
}

.pagination .page-link:hover {
    background: var(--primary-gradient);
    border-color: transparent;
    color: white;
    transform: translateY(-2px);
}

.pagination .page-item.active .page-link {
    background: var(--primary-gradient);
    border-color: transparent;
    color: white;
}

/* Empty State - Enhanced */
.empty-state {
    padding: 3rem;
    text-align: center;
    color: var(--gray-600);
}

.empty-state i {
    font-size: 4rem;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 1rem;
}

.empty-state h3 {
    font-size: 1.25rem;
    color: var(--gray-900);
    margin-top: 1rem;
    font-weight: 600;
}

.empty-state p {
    margin: 0.5rem 0;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
        padding: 1.25rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .filter-card form {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-card form > div {
        min-width: 100%;
    }
    
    .filter-card .d-flex {
        flex-direction: column;
        gap: 10px;
    }
    
    .btn-filter, .btn-clear {
        width: 100%;
        justify-content: center;
    }
    
    th, td {
        padding: 0.75rem 1rem;
    }
    
    .table-header {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
    
    .btn-icon {
        padding: 0.3rem 0.7rem;
        font-size: 0.75rem;
    }
}

/* Form control styling */
.form-control {
    width: 100%;
    padding: 0.7rem 1rem;
    border: 2px solid var(--gray-200);
    border-radius: 12px;
    font-size: 0.9rem;
    transition: all 0.3s;
    background: white;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: var(--accent-glow);
}

    /* ERP Table Styles */
        .erp-table {
            width: 100%;
            background: #fff;
            border-collapse: collapse;
        }

        .erp-table thead {
            background: var(--primary-gradient);
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }
    
    .erp-table th {
        padding: 12px 16px;
        font-weight: 600;
        color: white;
        text-align: left;
        font-size: 14px;
        border-bottom: 1px solid #e2e8f0;
        cursor: pointer;
        user-select: none;
        transition: background-color 0.2s;
        position: relative;
    }
    
    /*.erp-table th:hover {*/
    /*    background-color: #f1f5f9;*/
    /*}*/
    
    .erp-table th.sortable {
        padding-right: 30px;
    }
    
    .sort-icons {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .sort-icon {
        color: #cbd5e1;
        font-size: 12px;
        line-height: 1;
    }
    
    .sort-icon.active {
        color: #3b82f6;
    }
    
    .erp-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 14px;
        vertical-align: middle;
    }
    
    .erp-table tbody tr {
        transition: background-color 0.2s, transform 0.2s;
    }
    
    .erp-table tbody tr:hover {
        background-color: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .erp-table tbody tr:last-child td {
        border-bottom: none;
    }
    .table-responsive{
        overflow-x: hidden;
    }
</style>

<div class="container-fluid">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="header-left">
            <h1><i class="bi bi-shield-check" style="margin-right: 10px;"></i> Library Fine Rules</h1>
            <p><i class="bi bi-info-circle"></i> Configure fine rules for overdue, damaged, and lost books</p>
        </div>
        <a href="{{ route('library.fine-rules.create') }}" class="btn-primary">
            <i class="bi bi-plus-circle"></i>
            Add New Rule
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">
                <i class="bi bi-list-check"></i>
                Total Rules
            </div>
            <div class="stat-value">{{ $fineRules->total() }}</div>
            <div class="stat-sub">
                <i class="bi bi-check-circle-fill" style="color: var(--success-color); font-size: 0.7rem;"></i>
                Active: {{ $fineRules->where('is_active', true)->count() }}
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-label">
                <i class="bi bi-clock-history"></i>
                Overdue Rules
            </div>
            <div class="stat-value">{{ $fineRules->where('fine_type', 'overdue')->count() }}</div>
            <div class="stat-sub">
                <i class="bi bi-calendar"></i> Late return penalties
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-label">
                <i class="bi bi-exclamation-triangle"></i>
                Damage/Loss Rules
            </div>
            <div class="stat-value">{{ $fineRules->whereIn('fine_type', ['damaged', 'lost'])->count() }}</div>
            <div class="stat-sub">
                <i class="bi bi-shield"></i> Physical damage penalties
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-label">
                <i class="bi bi-currency-rupee"></i>
                Default Rate
            </div>
            <div class="stat-value">
                @php
                    $defaultOverdue = $fineRules->where('fine_type', 'overdue')->where('is_active', true)->first();
                @endphp
                {{ $defaultOverdue ? '₹' . $defaultOverdue->amount . '/day' : 'Not set' }}
            </div>
            <div class="stat-sub">
                <i class="bi bi-stopwatch"></i> Standard overdue rate
            </div>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('library.fine-rules.index') }}">
            <div>
                <label><i class="bi bi-tag"></i> Fine Type</label>
                <select name="fine_type" class="form-control">
                    <option value="">All Types</option>
                    <option value="overdue" {{ request('fine_type') == 'overdue' ? 'selected' : '' }}>📖 Overdue</option>
                    <option value="damaged" {{ request('fine_type') == 'damaged' ? 'selected' : '' }}>⚠️ Damaged</option>
                    <option value="lost" {{ request('fine_type') == 'lost' ? 'selected' : '' }}>❌ Lost</option>
                    <option value="misplaced" {{ request('fine_type') == 'misplaced' ? 'selected' : '' }}>🔍 Misplaced</option>
                    <option value="other" {{ request('fine_type') == 'other' ? 'selected' : '' }}>📌 Other</option>
                </select>
            </div>
            <div>
                <label><i class="bi bi-toggle-on"></i> Status</label>
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>✅ Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>⭕ Inactive</option>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn-filter">
                    <i class="bi bi-funnel"></i> Apply Filter
                </button>
                <a href="{{ route('library.fine-rules.index') }}" class="btn-clear">
                    <i class="bi bi-x-circle"></i> Clear
                </a>
            </div>
        </form>
    </div>

    {{-- Rules Table --}}
    <div class="rules-table">
        <div class="table-header">
            <span>
                <i class="bi bi-list-ul"></i> All Fine Rules
            </span>
            <span>
                <i class="bi bi-database"></i> Total {{ $fineRules->total() }} rules
            </span>
        </div>
        
        <div>
            <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th class="sticky-main-2 sortable">Rule Name</th>
                            <th class="sortable">Type</th>
                            <th class="sortable">Amount</th>
                            <th class="sortable">Frequency</th>
                            <th class="sortable">Grace Period</th>
                            <th class="sortable">Status</th>
                            <th class="sortable">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fineRules as $rule)
                        <tr>
                            <td class="sticky-main-2">
                                <div style="font-weight: 700; color: var(--gray-900);">{{ $rule->rule_name }}</div>
                                <div style="font-size: 0.75rem; color: var(--gray-600); margin-top: 4px;">
                                    <i class="bi bi-file-text"></i> {{ Str::limit($rule->description, 40) }}
                                </div>
                            </div>
                            </td>
                            <td>
                                <span class="rule-type">
                                    <i class="bi {{
                                        $rule->fine_type == 'overdue' ? 'bi-clock-history' :
                                        ($rule->fine_type == 'damaged' ? 'bi-exclamation-triangle' :
                                        ($rule->fine_type == 'lost' ? 'bi-x-circle' :
                                        ($rule->fine_type == 'misplaced' ? 'bi-question-circle' : 'bi-gear')))
                                    }}"></i>
                                    {{ ucfirst($rule->fine_type) }}
                                </span>
                            </div>
                            </td>
                            <td>
                                <span class="amount-badge">
                                    @if($rule->amount_type == 'fixed')
                                        <i class="bi bi-currency-rupee"></i> {{ number_format($rule->amount, 2) }}
                                    @else
                                        <i class="bi bi-percent"></i> {{ $rule->amount }}% of book cost
                                    @endif
                                </span>
                            </div>
                            </td>
                            <td>
                                <span class="frequency-badge">
                                    <i class="bi bi-arrow-repeat"></i>
                                    {{ str_replace('_', ' ', ucfirst($rule->frequency)) }}
                                </span>
                            </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <i class="bi bi-calendar-check" style="color: var(--primary-color);"></i>
                                    {{ $rule->grace_period_days }} days
                                </div>
                            </div>
                            <td>
                                <span class="badge {{ $rule->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    <i class="bi {{ $rule->is_active ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                    {{ $rule->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <td>
                                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                    <a href="{{ route('library.fine-rules.edit', $rule->id) }}" class="btn-icon d-none">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('library.fine-rules.toggle-status', $rule->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-icon" style="background: {{ $rule->is_active ? '#fee2e2' : '#d1fae5' }}; border-color: {{ $rule->is_active ? '#fecaca' : '#a7f3d0' }};">
                                            <i class="bi {{ $rule->is_active ? 'bi-pause-circle' : 'bi-play-circle' }}"></i>
                                            {{ $rule->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-shield-x"></i>
                                    <h3>No fine rules found</h3>
                                    <p>Get started by creating your first fine rule to manage library fines effectively.</p>
                                    <a href="{{ route('library.fine-rules.create') }}" class="btn-primary" style="margin-top: 1rem; display: inline-flex;">
                                        <i class="bi bi-plus-circle"></i> Add New Rule
                                    </a>
                                </div>
                            </div>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Floating Horizontal Scrollbar -->
            <div class="table-scroll-top" id="tableScrollTop">
                <div class="table-scroll-inner"></div>
            </div>
    
            @if($fineRules->hasPages())
            <div class="pagination">
                {{ $fineRules->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
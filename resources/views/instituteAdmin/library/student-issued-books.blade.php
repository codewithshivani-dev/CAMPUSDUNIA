@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>My Issued Books</title>

<style>
    /* Compact Dashboard Styling */
    :root {
        --primary: #4361ee;
        --secondary: #3f37c9;
        --success: #4cc9f0;
        --danger: #f72585;
        --warning: #f8961e;
        --info: #4895ef;
        --light: #f8f9fa;
        --dark: #212529;
        --gray: #6c757d;
    }

    /* Smaller Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        color: white;
        box-shadow: 0 5px 10px rgba(67, 97, 238, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-title {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .page-title i {
        font-size: 24px;
    }

    .user-info-card {
        background: rgba(255,255,255,0.15);
        padding: 8px 15px;
        border-radius: 40px;
        display: flex;
        align-items: center;
        gap: 10px;
        backdrop-filter: blur(10px);
        font-size: 13px;
    }

    .user-avatar {
        width: 35px;
        height: 35px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 18px;
    }

    /* Smaller Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 3px 8px rgba(0,0,0,0.03);
        transition: all 0.2s ease;
        border: 1px solid rgba(0,0,0,0.03);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(67, 97, 238, 0.08);
    }

    .stat-info h3 {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
        color: var(--dark);
        line-height: 1.2;
    }

    .stat-info p {
        margin: 3px 0 0;
        color: var(--gray);
        font-size: 12px;
        font-weight: 500;
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: white;
    }

    .stat-icon.primary { background: linear-gradient(135deg, var(--primary), var(--secondary)); }
    .stat-icon.warning { background: linear-gradient(135deg, #ff9e40, #ff6b4a); }
    .stat-icon.danger { background: linear-gradient(135deg, var(--danger), #b5179e); }

    /* Compact Filter Section */
    .filter-section {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.02);
        border: 1px solid rgba(0,0,0,0.03);
    }

    .filter-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-title i {
        color: var(--primary);
        font-size: 16px;
    }

    /* Smaller Books Grid */
    .books-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    /* Compact Book Card */
    .book-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 3px 8px rgba(0,0,0,0.03);
        transition: all 0.2s ease;
        border: 1px solid rgba(0,0,0,0.03);
        position: relative;
    }

    .book-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(67, 97, 238, 0.1);
    }

    .book-card.overdue {
        border-left: 4px solid var(--danger);
    }

    .book-card.due-soon {
        border-left: 4px solid var(--warning);
    }

    .book-header {
        padding: 12px 15px;
        border-bottom: 1px solid rgba(0,0,0,0.03);
        display: flex;
        justify-content: space-between;
        align-items: start;
    }

    .book-icon {
        width: 40px;
        height: 40px;
        background: rgba(67, 97, 238, 0.1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 20px;
    }

    .book-title {
        flex: 1;
        margin-left: 12px;
    }

    .book-title h4 {
        font-size: 14px;
        font-weight: 600;
        margin: 0 0 3px;
        color: var(--dark);
        line-height: 1.3;
    }

    .book-copy {
        display: inline-block;
        background: rgba(67, 97, 238, 0.1);
        padding: 3px 8px;
        border-radius: 40px;
        font-size: 11px;
        color: var(--primary);
        font-weight: 500;
    }

    .status-badge {
        padding: 4px 10px;
        border-radius: 40px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .status-overdue {
        background: #ffe5e5;
        color: var(--danger);
    }

    .status-issued {
        background: #e3f2fd;
        color: var(--primary);
    }

    /* Compact Book Details */
    .book-details {
        padding: 12px 15px;
    }

    .detail-row {
        display: flex;
        margin-bottom: 8px;
        font-size: 12px;
    }

    .detail-label {
        width: 80px;
        color: var(--gray);
        font-weight: 500;
    }

    .detail-value {
        flex: 1;
        color: var(--dark);
        font-weight: 500;
    }

    .due-date {
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 40px;
        display: inline-block;
        font-size: 11px;
    }

    .due-date.normal {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .due-date.warning {
        background: #fff3e0;
        color: #e65100;
    }

    .due-date.overdue {
        background: #ffebee;
        color: #c62828;
    }

    .fine-amount {
        background: #fff3e0;
        padding: 4px 10px;
        border-radius: 6px;
        color: #e65100;
        font-weight: 600;
        font-size: 12px;
        display: inline-block;
    }

    .book-footer {
        padding: 10px 15px;
        background: rgba(0,0,0,0.02);
        border-top: 1px solid rgba(0,0,0,0.03);
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11px;
    }

    .copy-details {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--gray);
    }

    .copy-details span {
        display: flex;
        align-items: center;
        gap: 3px;
    }

    .copy-details i {
        font-size: 11px;
    }

    /* Empty State - Compact */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        background: white;
        border-radius: 10px;
        grid-column: 1 / -1;
    }

    .empty-state i {
        font-size: 45px;
        color: #ddd;
        margin-bottom: 10px;
    }

    .empty-state h4 {
        font-size: 18px;
        color: var(--dark);
        margin-bottom: 8px;
    }

    .empty-state p {
        color: var(--gray);
        font-size: 13px;
        margin-bottom: 15px;
    }

    /* Quick Actions - Compact */
    .quick-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 12px;
    }

    .action-btn {
        padding: 6px 14px;
        border-radius: 40px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: white;
        border: 1px solid #dee2e6;
        color: var(--gray);
        transition: all 0.2s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .action-btn:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .action-btn.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    /* Compact Pagination */
    .pagination {
        margin-top: 20px;
        justify-content: center;
    }

    .pagination .page-link {
        border-radius: 6px;
        margin: 0 2px;
        color: var(--primary);
        border: 1px solid #dee2e6;
        padding: 5px 10px;
        font-size: 12px;
    }

    .pagination .page-item.active .page-link {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .books-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .detail-row {
            flex-direction: column;
        }
        
        .detail-label {
            width: 100%;
            margin-bottom: 3px;
        }
    }

    /* Form Controls - Compact */
    .form-control, .form-select {
        height: 40px;
        font-size: 13px;
        border-radius: 8px;
    }

    .btn {
        padding: 8px 16px;
        font-size: 13px;
        border-radius: 8px;
    }

    /* Guidelines Card - Compact */
    .guidelines-card {
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px;
        margin-top: 20px;
    }

    .guidelines-card h5 {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .guidelines-card ul {
        margin-bottom: 0;
        padding-left: 15px;
        font-size: 12px;
        opacity: 0.9;
    }

    .guidelines-card li {
        margin-bottom: 3px;
    }

    .guidelines-card i {
        font-size: 36px;
        opacity: 0.5;
    }
</style>

<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <i class="bi bi-book-half"></i>
            My Issued Books
        </div>
        <div class="user-info-card">
            <div class="user-avatar">
                <i class="bi bi-person-fill"></i>
            </div>
            <div>
                <div style="font-weight: 600;">{{ $userName ?? 'User' }}</div>
                <div style="font-size: 11px; opacity: 0.9;">{{ $userType ?? '' }} | {{ $userCode ?? '' }}</div>
            </div>
        </div>
    </div>

    <!-- Messages -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2" role="alert" style="font-size: 13px;">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close" style="font-size: 12px;"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show py-2" role="alert" style="font-size: 13px;">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close" style="font-size: 12px;"></button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <h3>{{ $totalIssued }}</h3>
                <p>Total Issued</p>
            </div>
            <div class="stat-icon primary">
                <i class="bi bi-book"></i>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-info">
                <h3>{{ $totalOverdue }}</h3>
                <p>Overdue</p>
            </div>
            <div class="stat-icon warning">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-info">
                <h3>₹{{ number_format($totalFineAmount, 2) }}</h3>
                <p>Pending Fine</p>
            </div>
            <div class="stat-icon danger">
                <i class="bi bi-cash-coin"></i>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-title">
            <i class="bi bi-funnel"></i>
            Filter Books
        </div>
        
        <form method="GET" action="{{ route('library.my.issued') }}" id="filterForm">
            <div class="row g-2">
                <div class="col-md-5">
                    <div class="position-relative">
                        <i class="bi bi-search" style="position: absolute; left: 12px; top: 12px; color: #6c757d; font-size: 13px;"></i>
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Search by title or copy ID..."
                               value="{{ request('search') }}"
                               style="padding-left: 35px;">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="all">All Books</option>
                        <option value="issued" {{ request('status') == 'issued' ? 'selected' : '' }}>Currently Issued</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-funnel me-1"></i>Apply
                        </button>
                        <a href="{{ route('library.my.issued') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
        
        <!-- Quick Filters -->
        <div class="quick-actions">
            <a href="{{ route('library.my.issued') }}" class="action-btn {{ !request('status') ? 'active' : '' }}">
                <i class="bi bi-collection"></i> All
            </a>
            <a href="{{ route('library.my.issued', ['status' => 'issued']) }}" class="action-btn {{ request('status') == 'issued' ? 'active' : '' }}">
                <i class="bi bi-check-circle"></i> Issued
            </a>
            <a href="{{ route('library.my.issued', ['status' => 'overdue']) }}" class="action-btn {{ request('status') == 'overdue' ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle"></i> Overdue
            </a>
        </div>
    </div>

    <!-- Books Grid -->
    <div class="books-grid">
        @forelse($issuedBooks as $issue)
        @php
            // Calculate overdue details in real time
            $dueDate = \Carbon\Carbon::parse($issue->due_date);
            $today = \Carbon\Carbon::today();
            $daysOverdue = $today->gt($dueDate) ? $dueDate->diffInDays($today) : 0;
            $isOverdue = $daysOverdue > 0;
            $daysUntilDue = $today->lt($dueDate) ? $today->diffInDays($dueDate) : 0;
            
            // Determine due date status
            $dueDateClass = 'normal';
            $dueDateText = $dueDate->format('d M Y');
            
            if ($isOverdue) {
                $dueDateClass = 'overdue';
                $dueDateText = $daysOverdue . ' days overdue';
            } elseif ($daysUntilDue <= 3) {
                $dueDateClass = 'warning';
                $dueDateText = 'Due in ' . $daysUntilDue . ' days';
            }
            
            // Get fine amount
            $fineAmount = 0;
            $fineStatus = 'No Fine';
            $fineColor = 'success';
            
            if ($issue->fine) {
                $fineAmount = $issue->fine->amount;
                if ($issue->fine->paid_status == 'paid') {
                    $fineStatus = 'Paid';
                    $fineColor = 'success';
                } elseif ($issue->fine->paid_status == 'waived') {
                    $fineStatus = 'Waived';
                    $fineColor = 'warning';
                } else {
                    $fineStatus = '₹' . number_format($fineAmount, 2);
                    $fineColor = 'danger';
                }
            } elseif ($isOverdue) {
                $rule = \App\Models\LibraryFineRule::where('institute_id', auth()->user()->institute_id)
                    ->where('fine_type', 'overdue')
                    ->where('is_active', true)
                    ->first();
                    
                if ($rule) {
                    $graceDays = $rule->grace_period_days ?? 0;
                    $effectiveDays = max(0, $daysOverdue - $graceDays);
                    
                    if ($effectiveDays > 0) {
                        if ($rule->frequency == 'one_time') {
                            $fineAmount = $rule->amount;
                        } else {
                            $fineAmount = $effectiveDays * $rule->amount;
                        }
                        
                        if ($rule->amount_type == 'percentage_of_book_cost' && $issue->libraryBook) {
                            $bookCost = $issue->libraryBook->price ?? 0;
                            $fineAmount = ($rule->amount / 100) * $bookCost;
                            if ($rule->frequency != 'one_time') {
                                $fineAmount = $fineAmount * $effectiveDays;
                            }
                        }
                    }
                } else {
                    $fineAmount = $daysOverdue * 5;
                }
                
                if ($fineAmount > 0) {
                    $fineStatus = '₹' . number_format($fineAmount, 2);
                    $fineColor = 'danger';
                }
            }
        @endphp
        
        <div class="book-card {{ $isOverdue ? 'overdue' : ($daysUntilDue <= 3 ? 'due-soon' : '') }}">
            <div class="book-header">
                <div class="d-flex align-items-start">
                    <div class="book-icon">
                        <i class="bi bi-journal-richtext"></i>
                    </div>
                    <div class="book-title">
                        <h4>{{ Str::limit($issue->libraryBook->title ?? 'N/A', 30) }}</h4>
                        <span class="book-copy">
                            <i class="bi bi-upc-scan"></i>
                            {{ $issue->copy->copy_id ?? 'N/A' }}
                        </span>
                    </div>
                </div>
                <span class="status-badge status-{{ $isOverdue ? 'overdue' : 'issued' }}">
                    <i class="bi bi-{{ $isOverdue ? 'exclamation-triangle' : 'arrow-up-circle' }}"></i>
                    {{ $isOverdue ? 'Overdue' : 'Issued' }}
                </span>
            </div>
            
            <div class="book-details">
                <div class="detail-row">
                    <span class="detail-label">Author:</span>
                    <span class="detail-value">{{ Str::limit($issue->copy->writer_name ?? $issue->libraryBook->writer_name ?? 'N/A', 20) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Issue Date:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($issue->issue_date)->format('d M Y') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Due Date:</span>
                    <span class="detail-value">
                        <span class="due-date {{ $dueDateClass }}">
                            <i class="bi bi-{{ $isOverdue ? 'exclamation-circle' : 'check-circle' }}"></i>
                            {{ $dueDateText }}
                        </span>
                    </span>
                </div>
                
                @if($issue->copy && $issue->copy->condition)
                <div class="detail-row">
                    <span class="detail-label">Condition:</span>
                    <span class="detail-value">
                        <span class="badge bg-{{ $conditionClass ?? 'secondary' }} text-white" style="font-size: 10px; padding: 3px 6px;">
                            {{ ucfirst($issue->copy->condition) }}
                        </span>
                    </span>
                </div>
                @endif
                
                @if($fineAmount > 0 || $issue->fine)
                <div class="detail-row">
                    <span class="detail-label">Fine:</span>
                    <span class="detail-value">
                        <span class="fine-amount" style="background: rgba(247, 37, 133, 0.1); color: var(--danger);">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $fineStatus }}
                        </span>
                    </span>
                </div>
                @endif
            </div>
            
            <div class="book-footer">
                <div class="copy-details">
                    @if($issue->copy && $issue->copy->rack)
                    <span><i class="bi bi-pin-map"></i> R:{{ $issue->copy->rack }}</span>
                    @endif
                    @if($issue->copy && $issue->copy->shelf)
                    <span><i class="bi bi-box"></i> S:{{ $issue->copy->shelf }}</span>
                    @endif
                </div>
                <small class="text-muted">
                    <i class="bi bi-clock-history"></i>
                    {{ $daysOverdue > 0 ? $daysOverdue . 'd overdue' : ($daysUntilDue > 0 ? $daysUntilDue . 'd left' : 'Due today') }}
                </small>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="bi bi-journal-bookmark-fill"></i>
            <h4>No Books Found</h4>
            <p>You don't have any issued books at the moment.</p>
          
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(method_exists($issuedBooks, 'links'))
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                Showing {{ $issuedBooks->firstItem() ?? 0 }} to {{ $issuedBooks->lastItem() ?? 0 }} of {{ $issuedBooks->total() ?? 0 }}
            </div>
            <div class="pagination-wrapper">
                {{ $issuedBooks->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
    
    <!-- Return Guidelines - Compact -->
    <div class="guidelines-card">
        <div class="row align-items-center">
            <div class="col-9">
                <h5><i class="bi bi-info-circle-fill me-1"></i>Return Guidelines</h5>
                <ul>
                    <li>Return by due date to avoid fines</li>
                    <li>Overdue fine calculated automatically</li>
                    <li>Damaged/lost books incur additional charges</li>
                </ul>
            </div>
            <div class="col-3 text-end">
                <i class="bi bi-journal-bookmark-fill"></i>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() { alert.remove(); }, 500);
        });
    }, 4000);
});
</script>
@endsection
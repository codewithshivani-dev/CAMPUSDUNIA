{{-- 
    Student Lesson Planner Layout
    Extends the teacher layout but overrides navigation and adds student-specific elements
--}}
@extends('instituteAdmin.Lesson-planner.index')

@section('styles')
@parent
<style>
    /* ===== STUDENT OVERRIDES ===== */
    
    /* Change logo color to student green */
    .logo-icon {
        background: linear-gradient(135deg, #10b981, #059669) !important;
    }
    .logo-text span { color: #10b981 !important; }

    /* Student badge in top bar */
    .student-badge-header {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-left: 12px;
    }
    .student-badge-header i { font-size: 12px; }

    /* Hide teacher actions */
    .teacher-only {
        display: none !important;
    }

    /* Student nav active color */
    .nav-tab.active {
        background: white;
        color: #10b981 !important;
        box-shadow: 0 2px 12px rgba(16, 185, 129, 0.15) !important;
    }

    /* Student-specific button */
    .btn-student {
        background: #10b981;
        color: white;
        border: none;
        padding: 8px 24px;
        border-radius: 60px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .btn-student:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
    }

    /* Student info banner */
    .student-info-banner {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        border: 1px solid #10b981;
        border-radius: 16px;
        padding: 16px 24px;
        margin: 0 32px 24px 32px;
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }
    .student-info-banner i {
        font-size: 28px;
        color: #059669;
    }
    .student-info-banner .content {
        flex: 1;
    }
    .student-info-banner .content h4 {
        font-size: 16px;
        font-weight: 600;
        color: #0a1e3c;
        margin: 0;
    }
    .student-info-banner .content p {
        font-size: 14px;
        color: #0b6e4f;
        margin: 2px 0 0 0;
    }
    .student-info-banner .readonly-badge {
        background: #10b981;
        color: white;
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }
    .student-info-banner .readonly-badge i { font-size: 12px; color: white; }

    /* Student stat cards */
    .stat-card.student::before {
        background: linear-gradient(90deg, #10b981, #34d399) !important;
    }
    .stat-card.student .stat-icon.blue { background: #dbeafe; color: #10b981; }
    .stat-card.student .stat-icon.green { background: #d1fae5; color: #10b981; }

    /* View-only badge */
    .view-only-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #d1fae5;
        color: #0b6e4f;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
        margin-left: 8px;
    }
    .view-only-badge i { font-size: 10px; }

    /* Student coverage badge */
    .coverage-badge-student {
        display: inline-block;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        cursor: default;
    }
    .coverage-badge-student.covered { background: #d1fae5; color: #0b6e4f; }
    .coverage-badge-student.partial { background: #fef3c7; color: #a16207; }
    .coverage-badge-student.not-covered { background: #fee2e2; color: #dc2626; }

    /* Disable interactive elements for student */
    .student-disabled {
        pointer-events: none;
        opacity: 0.7;
        cursor: default;
    }

    /* Hide new plan button for students */
    .btn-new {
        display: none !important;
    }
</style>
@endsection

{{-- Override the top bar to add student badge --}}
@section('topbar')
    <div class="top-bar">
        <div class="logo-area">
            <div class="logo-icon"><i class="fas fa-graduation-cap"></i></div>
            <span class="logo-text">View<span>Plans</span></span>
            <span class="student-badge-header"><i class="fas fa-user-graduate"></i> Student View</span>
        </div>
        <div class="nav-wrapper">
            <div class="nav-tabs">
                <a href="{{ route('student.lesson-planner.dashboard') }}" class="nav-tab {{ request()->routeIs('student.lesson-planner.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <a href="{{ route('student.lesson-planner.plans') }}" class="nav-tab {{ request()->routeIs('student.lesson-planner.plans') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> All Plans
                </a>
                <a href="{{ route('student.lesson-planner.coverage') }}" class="nav-tab {{ request()->routeIs('student.lesson-planner.coverage') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i> Coverage
                </a>
                <a href="{{ route('student.lesson-planner.performance') }}" class="nav-tab {{ request()->routeIs('student.lesson-planner.performance') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> Performance
                </a>
            </div>
         
        </div>
    </div>
@endsection

{{-- Override content to add student banner before yielding content --}}
@section('content')
    <!-- Student Info Banner -->
    <div class="student-info-banner">
        <i class="fas fa-info-circle"></i>
        <div class="content">
            <h4><i class="fas fa-graduation-cap"></i> Student View</h4>
            <p>You are viewing lesson plans in <strong>read-only</strong> mode. You can view all plans and track coverage but cannot create or edit plans.</p>
        </div>
        <span class="readonly-badge">
            <i class="fas fa-eye"></i> Read Only
        </span>
    </div>

    @yield('student-content')
@endsection

{{-- Add student-specific scripts --}}
@push('scripts')
<script>
    // Student-specific initialization
    $(document).ready(function() {
        // Disable any edit/create buttons
        $('.btn-edit, .btn-create, .btn-delete, .btn-mark').addClass('student-hidden');
        
        // Add student class to stat cards
        $('.stat-card').addClass('student');
        
        // Show student info
        console.log('📚 Student Lesson Planner - Read Only Mode');
    });

    // Override any teacher functions that might be called
    window.approvePlan = function() {
        showToast('Students cannot approve plans. This is a read-only view.', 'info');
    };
    
    window.rejectPlan = function() {
        showToast('Students cannot reject plans. This is a read-only view.', 'info');
    };
    
    window.editPlan = function() {
        showToast('Students cannot edit plans. This is a read-only view.', 'info');
    };
    
    window.markCoverage = function() {
        showToast('Students cannot mark coverage. This is a read-only view.', 'info');
    };
</script>
@endpush
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')


{{-- =============================================================
    PAGE CSS
============================================================= --}}
<style>

    .admit-card-row {
        transition: all 0.2s ease;
    }

    .admit-card-row:hover {
        background-color: #fafafa;
    }

    .admit-card-icon {

        width: 52px;
        height: 52px;

        border-radius: 12px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: rgba(13, 110, 253, 0.10);

        color: #0d6efd;

        font-size: 25px;
    }

    .empty-admit-icon {

        width: 80px;
        height: 80px;

        border-radius: 50%;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #f5f5f5;

        color: #9b9b9b;

        font-size: 40px;
    }

    .admit-card-row .btn {
        min-width: 105px;
    }

    @media (max-width: 767px) {

        .admit-card-row {
            padding: 20px !important;
        }

        .admit-card-row .btn {
            flex: 1;
        }

    }

</style>

<div class="content">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="page-header d-flex align-items-center justify-content-between mb-4">

        <div>
            <h4 class="mb-1 d-none">
                <i class="ti ti-id-badge me-2"></i>
                My Admit Cards
            </h4>

            <p class="text-muted mb-0">
                View, download and print your generated examination admit cards.
            </p>
        </div>

        <a href="{{ route('student.exam-schedule') }}"
           class="btn btn-light">
            <i class="ti ti-calendar me-1"></i>
            Exam Schedule
        </a>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-circle-check me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- =========================================================
        ADMIT CARDS
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-bottom">

            <div class="d-flex align-items-center justify-content-between">

                <div>
                    <h5 class="mb-1">
                        Examination Admit Cards
                    </h5>

                    <small class="text-muted">
                        Your generated admit cards are listed below.
                    </small>
                </div>

                @if($admitCards->count())
                    <span class="badge bg-primary">
                        {{ $admitCards->count() }}
                        {{ $admitCards->count() == 1 ? 'Card' : 'Cards' }}
                    </span>
                @endif

            </div>

        </div>


        <div class="card-body p-0">

            @forelse($admitCards as $admitCard)

                @php

                    $status = strtolower(
                        $admitCard->status ?? 'generated'
                    );

                    $statusLabel = match($status) {
                        'published' => 'Published',
                        'printed' => 'Printed',
                        'downloaded' => 'Downloaded',
                        'generated' => 'Generated',
                        default => ucfirst($status),
                    };

                    $statusClass = match($status) {
                        'published' => 'bg-success',
                        'generated' => 'bg-primary',
                        'printed' => 'bg-info',
                        'downloaded' => 'bg-warning text-dark',
                        default => 'bg-secondary',
                    };

                @endphp


                {{-- =====================================================
                    SINGLE ADMIT CARD
                ====================================================== --}}
                <div class="admit-card-row p-4
                            {{ !$loop->last ? 'border-bottom' : '' }}">

                    <div class="row align-items-center g-3">

                        {{-- =================================================
                            ICON
                        ================================================== --}}
                        <div class="col-auto">

                            <div class="admit-card-icon">

                                <i class="ti ti-id-badge"></i>

                            </div>

                        </div>


                        {{-- =================================================
                            EXAM INFORMATION
                        ================================================== --}}
                        <div class="col">

                            <div class="d-flex flex-wrap
                                        align-items-center gap-2 mb-1">

                                <h5 class="mb-0">

                                    {{ optional($admitCard->examName)->name
                                        ?? 'Examination' }}

                                </h5>


                                <span class="badge {{ $statusClass }}">

                                    @if($status === 'generated')
                                        <i class="ti ti-file-check me-1"></i>
                                    @elseif($status === 'published')
                                        <i class="ti ti-circle-check me-1"></i>
                                    @elseif($status === 'printed')
                                        <i class="ti ti-printer me-1"></i>
                                    @elseif($status === 'downloaded')
                                        <i class="ti ti-download me-1"></i>
                                    @endif

                                    {{ $statusLabel }}

                                </span>

                            </div>


                            <div class="row g-2 mt-2">

                                <div class="col-md-4">

                                    <small class="text-muted d-block">
                                        Academic Year
                                    </small>

                                    <strong>
                                        {{ $admitCard->academic_year ?? '—' }}
                                    </strong>

                                </div>


                                @if($admitCard->generated_at)

                                    <div class="col-md-4">

                                        <small class="text-muted d-block">
                                            Generated On
                                        </small>

                                        <strong>
                                            {{ \Carbon\Carbon::parse(
                                                $admitCard->generated_at
                                            )->format('d M Y, h:i A') }}
                                        </strong>

                                    </div>

                                @endif


                                @if($admitCard->published_at)

                                    <div class="col-md-4">

                                        <small class="text-muted d-block">
                                            Published On
                                        </small>

                                        <strong class="text-success">
                                            {{ \Carbon\Carbon::parse(
                                                $admitCard->published_at
                                            )->format('d M Y, h:i A') }}
                                        </strong>

                                    </div>

                                @endif

                            </div>


                            {{-- =============================================
                                STATUS MESSAGE
                            ============================================== --}}
                            <div class="mt-3">

                                @if($status === 'generated')

                                    <div class="small text-primary">

                                        <i class="ti ti-info-circle me-1"></i>

                                        Your admit card has been generated.
                                        You can view, download or print it.

                                    </div>

                                @elseif($status === 'published')

                                    <div class="small text-success">

                                        <i class="ti ti-circle-check me-1"></i>

                                        Your admit card has been published.

                                    </div>

                                @endif

                            </div>

                        </div>


                        {{-- =================================================
                            ACTIONS
                        ================================================== --}}
                        <div class="col-12 col-lg-auto">

                            @if(
                                !empty($admitCard->pdf_path)
                                &&
                                in_array(
                                    $status,
                                    [
                                        'generated',
                                        'published',
                                        'printed',
                                        'downloaded'
                                    ],
                                    true
                                )
                            )

                                <div class="d-flex flex-wrap gap-2">

                                    {{-- =====================================
                                        VIEW
                                    ====================================== --}}
                                    <a href="{{ route(
                                            'admit-cards.student.view',
                                            $admitCard->id
                                        ) }}"
                                       target="_blank"
                                       class="btn btn-outline-primary">

                                        <i class="ti ti-eye me-1"></i>
                                        View

                                    </a>


                                    {{-- =====================================
                                        DOWNLOAD
                                    ====================================== --}}
                                    <a href="{{ route(
                                            'admit-card.student.download',
                                            $admitCard->id
                                        ) }}"
                                       class="btn btn-success">

                                        <i class="ti ti-download me-1"></i>
                                        Download

                                    </a>


                                    {{-- =====================================
                                        PRINT
                                    ====================================== --}}
                                    <a href="{{ route(
                                            'admit-cards.student.print',
                                            $admitCard->id
                                        ) }}"
                                       target="_blank"
                                       class="btn btn-outline-dark">

                                        <i class="ti ti-printer me-1"></i>
                                        Print

                                    </a>

                                </div>

                            @else

                                <span class="badge bg-light text-muted">
                                    Admit card PDF not available
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            @empty

                {{-- =====================================================
                    EMPTY STATE
                ====================================================== --}}
                <div class="text-center py-5 px-3">

                    <div class="empty-admit-icon mx-auto mb-3">

                        <i class="ti ti-id-badge"></i>

                    </div>

                    <h5 class="mb-2">
                        No Admit Card Available
                    </h5>

                    <p class="text-muted mb-0">

                        Your admit card will appear here after the
                        administrator generates it.

                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection
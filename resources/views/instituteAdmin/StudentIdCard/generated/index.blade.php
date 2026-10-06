{{-- resources/views/instituteAdmin/StudentIdCard/generated/index.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
.card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}
.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.status-badge.active {
    background: #d1fae5;
    color: #065f46;
}
.status-badge.inactive {
    background: #fee2e2;
    color: #991b1b;
}
.academic-tag {
    background: #e0f2fe;
    color: #0369a1;
    padding: 2px 10px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
}
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-id-card text-primary me-2"></i>Generated Student ID Cards</h4>
        <a href="{{ route('student-id-card-templates.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Templates
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($cards->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            No student ID cards generated yet. 
            <a href="{{ route('student-id-card-templates.index') }}">Create a template</a> and 
            <a href="{{ route('student-id-card.generate', ['student' => '']) }}">generate cards</a> for students.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Card Number</th>
                        <th>Student</th>
                        <th>Academic Year</th>
                        <th>Batch</th>
                        <th>Generated At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cards as $card)
                    <tr>
                        <td><strong>{{ $card->card_number }}</strong></td>
                        <td>
                            <div>{{ $card->student->first_name ?? 'N/A' }} {{ $card->student->last_name ?? '' }}</div>
                            <small class="text-muted">{{ $card->student->registration_number ?? 'N/A' }}</small>
                        </td>
                        <td>
                            <span class="academic-tag">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $card->academic_year ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <span class="academic-tag">
                                <i class="fas fa-users me-1"></i>
                                {{ $card->batch ?? 'N/A' }}
                            </span>
                        </td>
                        <td>{{ $card->generated_at ? Carbon\Carbon::parse($card->generated_at)->format('d M Y H:i') : 'N/A' }}</td>
                        <td>
                            <span class="status-badge {{ $card->is_active ? 'active' : 'inactive' }}">
                                {{ $card->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('generated-student-cards.view', $card->id) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($card->pdf_path && file_exists(storage_path('app/public/' . $card->pdf_path)))
                                <a href="{{ route('generated-student-cards.download', $card->id) }}" class="btn btn-sm btn-primary" title="Download PDF">
                                    <i class="fas fa-download"></i>
                                </a>
                                @endif
                                <button class="btn btn-sm btn-warning regenerate-btn" 
                                        data-student="{{ $card->student_hash_id }}"
                                        title="Regenerate">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $cards->links() }}
        </div>
    @endif
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('.regenerate-btn').click(function() {
        var studentHashId = $(this).data('student');
        var $btn = $(this);
        
        if (!confirm('This will regenerate the ID card. Proceed?')) return;
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: '/institute/student-id-card/regenerate/' + studentHashId,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect_url;
                } else {
                    alert(response.message || 'Error regenerating card');
                    $btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i>');
                }
            },
            error: function() {
                alert('Error regenerating card');
                $btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i>');
            }
        });
    });
});
</script>
@endsection
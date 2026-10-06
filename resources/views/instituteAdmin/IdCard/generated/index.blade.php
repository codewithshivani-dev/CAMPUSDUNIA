{{-- resources/views/instituteAdmin/IdCard/generated/index.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-id-card mr-2"></i> Generated ID Cards</h4>
        <span class="text-muted">Total: {{ $cards->total() }}</span>
    </div>

    <div class="card">
        <div class="card-body">
            @if($cards->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-id-card fa-4x text-muted mb-3"></i>
                    <h5>No ID Cards Generated Yet</h5>
                    <p class="text-muted">Generate ID cards for your employees.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Card Number</th>
                                <th>Template</th>
                                <th>Generated At</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cards as $card)
                            <tr>
                                <td>
                                    <strong>{{ $card->employee->name ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $card->employee->employee_code ?? '' }}</small>
                                </td>
                                <td>{{ $card->card_number }}</td>
                                <td>{{ $card->template->template_name ?? 'N/A' }}</td>
                                <td>{{ $card->generated_at->format('d M Y, h:i A') }}</td>
                                <td>{{ $card->expiry_date ? $card->expiry_date->format('d M Y') : 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $card->is_active ? 'success' : 'danger' }}">
                                        {{ $card->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('generated-cards.view', $card->id) }}" 
                                       class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $cards->links() }}
            @endif
        </div>
    </div>
</div>
@endsection

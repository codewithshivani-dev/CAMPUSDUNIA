@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-sign-out-alt"></i> Out Receipts</h4>
        <a href="{{ route('inventory.receipts.out.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Out Receipt
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Receipt No.</th>
                            <th>Item</th>
                            <th>Warehouse</th>
                            <th>Quantity</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($receipts as $receipt)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge bg-secondary">{{ $receipt->receipt_number }}</span></td>
                            <td>{{ $receipt->item->item_name ?? 'N/A' }}</td>
                            <td>{{ $receipt->warehouse->warehouse_name ?? 'N/A' }}</td>
                            <td>{{ number_format($receipt->quantity, 2) }}</td>
                            <td>
                                <span class="badge bg-info">{{ $receipt->receipt_type_text }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $receipt->status_badge }}">
                                    {{ $receipt->status_text }}
                                </span>
                            </td>
                            <td>
                                {{ $receipt->created_at->format('d M Y') }}
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="{{ route('inventory.receipts.out.show', $receipt->id) }}" 
                                       class="btn btn-info btn-sm" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($receipt->status == 'COMPLETED')
                                        <a href="{{ route('inventory.receipts.out.print', $receipt->id) }}" 
                                           class="btn btn-secondary btn-sm" title="Print" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-receipt" style="font-size: 3rem; color: #d1d5db;"></i>
                                <h5 class="mt-3 text-muted">No Out Receipts Found</h5>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $receipts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h6 class="border-bottom pb-2">Bus Information</h6>
            <table class="table table-sm">
                <tr>
                    <th width="40%">Bus Number:</th>
                    <td>{{ $bus->bus_number }}</td>
                </tr>
                <tr>
                    <th>Vehicle Number:</th>
                    <td>{{ $bus->vehicle_number }}</td>
                </tr>
                <tr>
                    <th>Route Name:</th>
                    <td>{{ $bus->route_name }}</td>
                </tr>
                <tr>
                    <th>Route Type:</th>
                    <td>
                        @if($bus->route_type == 'morning')
                            <span class="badge bg-primary text-white">Morning Pickup</span>
                        @elseif($bus->route_type == 'evening')
                            <span class="badge bg-info text-white">Evening Drop</span>
                        @else
                            <span class="badge bg-success text-white">Both</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Driver:</th>
                    <td>{{ $bus->driver_name }} ({{ $bus->driver_contact }})</td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <h6 class="border-bottom pb-2">Helpers</h6>
            @if(count($helpers) > 0)
                <ul class="list-group">
                    @foreach($helpers as $index => $helper)
                    <li class="list-group-item">
                        <strong>{{ $helper['name'] ?? 'Helper ' . ($index + 1) }}</strong><br>
                        <small>Contact: {{ $helper['contact'] ?? 'N/A' }}</small>
                    </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">No helpers assigned</p>
            @endif
        </div>
    </div>
    
    <h6 class="border-bottom pb-2">Bus Stops & Timings</h6>
    
    @if(count($stops) > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Stop ID</th>
                        <th>Stop Name</th>
                        <th>Pickup Time</th>
                        <th>Drop Time</th>
                        <th>Monthly Fee</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stops as $index => $stop)
                    @php
                        $stopFee = 'N/A';
                        foreach($feeBreakdown as $fee) {
                            if(isset($fee['stop_id']) && $fee['stop_id'] == ($stop['id'] ?? '')) {
                                $stopFee = '₹' . number_format($fee['monthly_fee'] ?? 0, 2);
                                break;
                            }
                        }
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><code>{{ $stop['id'] ?? 'N/A' }}</code></td>
                        <td><strong>{{ $stop['name'] ?? 'Unnamed Stop' }}</strong></td>
                        <td>
                            @if(isset($stop['pickup_time']))
                                <span class="badge bg-success">{{ $stop['pickup_time'] }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if(isset($stop['drop_time']))
                                <span class="badge bg-info">{{ $stop['drop_time'] }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="fw-bold text-success">{{ $stopFee }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Overall Timings -->
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <strong>Estimated Start Time</strong>
                    </div>
                    <div class="card-body">
                        @if($bus->estimated_start_time)
                            <h5>{{ \Carbon\Carbon::parse($bus->estimated_start_time)->format('h:i A') }}</h5>
                        @else
                            <p class="text-muted">Not set</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <strong>Estimated End Time</strong>
                    </div>
                    <div class="card-body">
                        @if($bus->estimated_end_time)
                            <h5>{{ \Carbon\Carbon::parse($bus->estimated_end_time)->format('h:i A') }}</h5>
                        @else
                            <p class="text-muted">Not set</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i>
            No stops have been configured for this bus.
        </div>
    @endif
</div>

<style>
    .table-sm td, .table-sm th {
        padding: 0.5rem;
    }
    .badge {
        font-size: 0.85rem;
    }
    .list-group-item {
        padding: 0.5rem 1rem;
    }
</style>
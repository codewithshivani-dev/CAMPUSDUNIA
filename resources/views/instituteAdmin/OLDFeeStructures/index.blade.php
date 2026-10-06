@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<STYLE>
    
.banner { 
    height:260px;
    border-radius:18px;
    position:relative;
    overflow:hidden;
    background:linear-gradient(135deg,#2154be,#3e70b3);
    display:flex;align-items:center;
}

.banner img.bg { 
    width:100%;
    height:260px;
    object-fit:cover;
    filter:brightness(0.8); 
}

.banner .meta { 
    position:absolute; 
    left:28px; 
    bottom:22px; 
    background:rgba(255,255,255,0.95); 
    padding:14px 20px;
    border-radius:12px; 
}

</STYLE>
<div class="banner mb-4">
    @if(!empty($bannerPath))
        <img src="{{ asset('/image/'.$fincapMerchants->documents->first()->institute_image_path) }}" 
             alt="institute image"
             class="bg">
    @else
        <img src="{{ asset('/image/'.$fincapMerchants->documents->first()->institute_image_path) }}" 
             alt="institute image"
             class="bg">
    @endif

    <div class="meta">
        <h3 style="margin:0;">{{ $fincapMerchants->name ?? $fincapMerchants->fincap_merchant_name ?? 'Institute Name' }}</h3>
        <p style="margin:0;color:#444;">{{ $fincapMerchants->state ?? $fincapMerchants->fincap_merchant_state ?? '' }}, {{ $fincapMerchants->city ?? $fincapMerchants->fincap_merchant_city ?? '' }}</p>
    </div>
</div>
<div class="container mt-5">
   
        <div class="d-flex justify-content-between align-items-center my-2">
        <h3>Hostel Fee Structure</h3>
        <button class="btn btn-primary"><a href="{{ route('hostel.fees.create') }}" class="btn btn-primary mb-3">
                <i class="fas fa-plus"></i> Add New
            </a></button>
       </div>
        <div >
          
          <div class="table-responsive">   
            <table class="table table-striped">
                <thead >
                    <tr>
                        <th>Reference ID</th>
                        <th>Hostel Name</th>
                        <th>Type</th>
                        <th>Academic Year</th>
                        <th>Security Deposit</th>
                        <th>Maintenance Fee</th>
                        <th>UtilitY Charges</th>
                        <th>Total Fee</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="subjectTableBody">
                    @forelse($hostelFees as $fee)
                    <tr>
                        <td>{{ $fee->hostel_fee_reference_id }}</td>
                        <td>{{ $fee->hostel_name }}</td>
                        <td>{{ ucfirst($fee->hostel_type) }}</td>
                        <td>{{ $fee->academic_year }}</td>
                        <td>{{ $fee->security_deposit }}</td>
                        <td>{{ $fee->maintenance_fee }}</td>
                        <td>{{ $fee->utility_charges }}</td>
                        <td>₹{{ number_format($fee->total_fee, 2) }}</td>
                        <td>
                            @if($fee->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No hostel fee structures found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
</div>  
      
    </div>
</div>
@endsection
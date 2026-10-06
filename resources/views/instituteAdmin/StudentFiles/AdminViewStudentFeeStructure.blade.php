@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
/* All existing styles remain unchanged */
.main-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    border: none;
}

.card-header {
    background: linear-gradient(135deg, #2951c3 0%, #2850c3 100%);
    color: white;
    border-radius: 12px 12px 0 0 !important;
    padding: 15px 20px;
}

.student-info-card {
    background: #f8f9fa;
    border-left: 4px solid #007bff;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 0 8px 8px 0;
}

.icon-circle {
    border-radius: 50%;
    margin-right: 8px;
}

.icon-circle i{
    width: 35px;
    height: 35px;
    justify-content: center;
    align-items: center;
    display: flex;
}

.stats-badge {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    padding: 4px 10px;
    margin: 2px;
    font-size: 0.8rem;
}

.total-card-small {
    background: #28a745;
    color: white;
    border-radius: 8px;
    padding: 12px 15px;
    text-align: center;
}

.fee-category-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 20px;
    overflow: hidden;
}

.category-header {
    background: #f1f3f5;
    padding: 12px 15px;
    font-weight: 600;
    border-bottom: 1px solid #dee2e6;
}

.category-header i {
    margin-right: 8px;
    color: #2951c3;
}

.fee-table {
    width: 100%;
    border-collapse: collapse;
}



.fee-table th,
.fee-table td {
    padding: 8px 10px;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
}

.fee-table tr:last-child td {
    border-bottom: none;
}

.amount-cell {
    text-align: right;
    font-weight: 500;
}

.duration-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    background: #e9ecef;
    color: #495057;
}

.duration-badge i {
    margin-right: 4px;
    font-size: 0.7rem;
}

.subtotal-row {
    background: #eef3ff;
    font-weight: 600;
    color: #2951c3;
}

.subtotal-row td {
    padding: 8px 10px;
    border-top: 2px solid #dee2e6;
}

.total-summary {
    background: #e7f3ff;
    border: 1px solid #b3d9ff;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
    font-size: 1.1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.total-summary .total-label {
    font-weight: 600;
}

.total-summary .total-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #28a745;
}

/* PRINT STYLES - Hide everything except #printArea */
@media print {
    @page {
        size: A4;
        margin: 10mm;
    }

    body * {
        visibility: hidden;
    }

    #printArea, #printArea * {
        visibility: visible;
    }

    #printArea {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background: white;
        padding: 0;
    }

    .no-print {
        display: none !important;
    }

    .main-card {
        box-shadow: none;
        border: 1px solid #ddd;
    }

    .fee-table th {
        background: #f0f0f0 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .student-info-card,
    .fee-category-card,
    .total-summary {
        break-inside: avoid;
    }
}
</style>

<div class="container-fluid" id="printArea">
    <div class="main-card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-money-bill-wave me-2"></i>Student Fee Structure
                </h5>
                <div class="no-print">
                    <button class="btn btn-sm btn-light" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-light ms-2">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Student Information -->
            <div class="student-info-card">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-primary text-white">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $studentDetail->first_name ?? 'Student Name' }}
                                    {{ $studentDetail->last_name ?? '' }}</h5>
                                <p class="mb-0 text-muted">
                                    Reg No. : {{ $studentDetail->registration_number ?? 'N/A' }} |
                                    Father: {{ $studentDetail->father_first_name ?? 'Father Name' }}
                                    {{ $studentDetail->father_middle_name ?? '' }}
                                    {{ $studentDetail->father_last_name ?? '' }}
                                </p>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap my-3">
                            @if($studentDetail->academicTransportDetails->department ?? false)
                            <span class="stats-badge">
                                <i class="fas fa-building me-1"></i>Dept:
                                <b>{{ $studentDetail->academicTransportDetails->department }}</b>
                            </span>
                            @endif
                            <span class="stats-badge">
                                <i class="fas fa-book me-1"></i>Class:
                                <b>{{ $studentDetail->academicTransportDetails->course_type ?? 'N/A' }} - {{ $studentDetail->academicTransportDetails->course_subtype ?? 'N/A' }}</b>
                            </span>
                            <span class="stats-badge">
                                <i class="fas fa-layer-group me-1"></i>Batch:
                                <b>{{ $studentDetail->academicTransportDetails->batch ?? 'N/A' }}</b>
                            </span>
                            <span class="stats-badge">
                                <i class="fas fa-graduation-cap me-1"></i>Acad Year:
                                <b>{{ $studentDetail->academicTransportDetails->academic_year ?? 'N/A' }}</b>
                            </span>
                            <span class="stats-badge">
                                <i class="fas fa-graduation-cap me-1"></i>Section:
                                <b>{{ $sectionDisplayName ?? $studentDetail->academicTransportDetails->section_id ?? 'N/A' }}</b>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="total-card-small">
                            <div class="small">Total Fee (after discount)</div>
                            <div class="h5 mb-0" id="grandTotalDisplay">₹0</div>
                        </div>
                    </div>
                </div>
            </div>

            @php
            $grandTotal = 0;
            $presentFees = [];

            // Helper to get duration multiplier and label (case‑insensitive)
            function getDurationInfo($duration) {
                $duration = strtolower(trim($duration ?? ''));
                switch($duration) {
                    case 'monthly': return ['multiplier' => 12, 'label' => 'Monthly', 'icon' => 'fa-calendar-alt'];
                    case 'quarterly': return ['multiplier' => 4, 'label' => 'Quarterly', 'icon' => 'fa-calendar-week'];
                    case 'half_yearly':
                    case 'half-yearly':
                    case 'half yearly': return ['multiplier' => 2, 'label' => 'Half-Yearly', 'icon' => 'fa-calendar-check'];
                    case 'yearly': return ['multiplier' => 1, 'label' => 'Yearly', 'icon' => 'fa-calendar'];
                    default: return ['multiplier' => 1, 'label' => 'One Time', 'icon' => 'fa-clock'];
                }
            }

            $categories = [
                'course_fee' => ['title' => 'Class Fee', 'icon' => 'fas fa-book', 'relation' => 'StudentCourseFeeStructure', 'fee_field' => 'course_fee'],
                'hostel_fee' => ['title' => 'Hostel Fee', 'icon' => 'fas fa-bed', 'relation' => 'StudentHostelFeeStructure', 'fee_field' => 'hostel_fee'],
                'transportation_fee' => ['title' => 'Transport Fee', 'icon' => 'fas fa-bus', 'relation' => 'StudentTransportFeeStructure', 'fee_field' => 'transport_fee'],
                'registration_fee' => ['title' => 'Registration Fee', 'icon' => 'fas fa-file-signature', 'relation' => 'StudentRegistrationFeeStructure', 'fee_field' => 'registration_fee'],
                'miscellaneous_fee' => ['title' => 'Miscellaneous Fee', 'icon' => 'fas fa-receipt', 'relation' => 'StudentMiscellaneousFeeStructure', 'fee_field' => 'miscellaneous_fee'],
                'custom_fees' => ['title' => 'Custom Fees', 'icon' => 'fas fa-list-alt', 'relation' => 'StudentCustomFeestructure', 'fee_field' => 'custom_fee_value']
            ];

            $className = $studentDetail->academicTransportDetails->course_type ?? 'Class';
            @endphp

            @foreach($categories as $key => $cat)
                @if($key == 'custom_fees')
                    {{-- Custom fees: list individually without grouping --}}
                    @php
                    $collection = $studentDetail->{$cat['relation']} ?? collect();
                    @endphp
                    @if(!$collection->isEmpty())
                        @php $presentFees[] = $cat['title']; @endphp
                        <div class="fee-category-card">
                            <div class="category-header">
                                <i class="{{ $cat['icon'] }}"></i> {{ $cat['title'] }}
                            </div>
                            <div class="table-responsive">
                                <table class="fee-table">
                                    <thead>
                                        <tr>
                                            <th class="sortable">Fee Details</th>
                                            <th class="sortable">Duration</th>
                                            <th class="sortable amount-cell">Discount (₹)</th>
                                            <th class="sortable amount-cell">Per Installment (₹)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $categoryTotal = 0; @endphp
                                        @foreach($collection as $item)
                                            @php
                                            $amount = floatval($item->{$cat['fee_field']} ?? 0);
                                            $discount = floatval($item->discount_amount ?? 0);
                                            $net = $amount - $discount;
                                            $categoryTotal += $net;
                                            $grandTotal += $net;
                                            $duration = $item->fee_duration_type ?? 'One Time';
                                            $durationInfo = getDurationInfo($duration);
                                            @endphp
                                            <tr>
                                                <td>{{ $item->custom_fee_name ?? $item->custom_fee_key ?? 'Custom Fee' }}</td>
                                                <td>
                                                    <span class="duration-badge">
                                                        <i class="fas {{ $durationInfo['icon'] }}"></i> {{ $durationInfo['label'] }}
                                                    </span>
                                                </td>
                                                <td class="amount-cell">
                                                    @if($discount > 0)
                                                        -{{ number_format($discount, 2) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="amount-cell">{{ number_format($amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="subtotal-row">
                                            <td colspan="3"><strong>Total {{ $cat['title'] }}</strong></td>
                                            <td class="amount-cell"><strong>₹{{ number_format($categoryTotal, 2) }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @endif
                @else
                    {{-- Other fee categories: group by duration --}}
                    @php
                    $collection = $studentDetail->{$cat['relation']} ?? collect();
                    if ($collection->isEmpty()) continue;
                    $presentFees[] = $cat['title'];

                    // Group by duration
                    $groups = [];
                    foreach ($collection as $item) {
                        $amount = floatval($item->{$cat['fee_field']} ?? 0);
                        $discount = floatval($item->discount_amount ?? 0);
                        $duration = strtolower(trim($item->fee_duration_type ?? 'one_time'));

                        $groupKey = $duration;
                        if (!isset($groups[$groupKey])) {
                            $groups[$groupKey] = [
                                'duration' => $duration,
                                'total_amount' => 0,
                                'total_discount' => 0,
                                'count' => 0,
                                'first_amount' => $amount
                            ];
                        }
                        $groups[$groupKey]['total_amount'] += $amount;
                        $groups[$groupKey]['total_discount'] += $discount;
                        $groups[$groupKey]['count']++;
                    }

                    $categoryTotalNet = 0;
                    $categoryTotalGross = 0; // Track original total before discounts
                    foreach ($groups as &$group) {
                        $durationInfo = getDurationInfo($group['duration']);
                        $multiplier = $durationInfo['multiplier'];

                        $perInstallment = $group['first_amount'];
                        $gross = $perInstallment * $multiplier; // Original total for this group
                        $groupNet = $gross - $group['total_discount'];
                        $group['net_total'] = $groupNet;
                        $categoryTotalNet += $groupNet;
                        $categoryTotalGross += $gross;
                        $grandTotal += $groupNet;
                    }
                    @endphp

                    <div class="fee-category-card">
                        <div class="category-header">
                            <i class="{{ $cat['icon'] }}"></i>
                            @if($key == 'course_fee')
                                {{ $cat['title'] }} ({{ $className }})
                            @else
                                {{ $cat['title'] }}
                            @endif
                        </div>
                        <div class="table-responsive">
                            <table class="fee-table">
                                <thead>
                                    <tr>
                                        <th class="sortable">Fee Details</th>
                                        <th class="sortable">Duration</th>
                                        <th class="sortable amount-cell">Discount (₹)</th>
                                        <th class="sortable amount-cell">Per Installment (₹)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groups as $group)
                                        @php
                                        $durationInfo = getDurationInfo($group['duration']);
                                        $perInstallment = $group['first_amount'];
                                        $multiplier = $durationInfo['multiplier'];
                                        $displayName = $cat['title'];
                                        if ($multiplier > 1) {
                                            $displayName .= ' (' . $durationInfo['label'] . ' x' . $multiplier . ')';
                                        }
                                        @endphp
                                        <tr>
                                            <td>{{ $displayName }}</td>
                                            <td>
                                                <span class="duration-badge">
                                                    <i class="fas {{ $durationInfo['icon'] }}"></i> {{ $durationInfo['label'] }}
                                                </span>
                                            </td>
                                            <td class="amount-cell">
                                                @if($group['total_discount'] > 0)
                                                    @php
                                                        $perInstallmentDiscount = $group['total_discount'] / $multiplier;
                                                    @endphp
                                                    -₹{{ number_format($perInstallmentDiscount, 2) }} per installment
                                                    @if($multiplier > 1)
                                                        <br><small class="text-muted">
                                                            ({{ $multiplier }} installments) = -₹{{ number_format($group['total_discount'], 2) }} total
                                                        </small>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="amount-cell">{{ number_format($perInstallment, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="subtotal-row">
                                        <td colspan="3"><strong>Total {{ $cat['title'] }}</strong></td>
                                        <td class="amount-cell">
                                            <strong> ₹{{ number_format($categoryTotalNet, 2) }}</strong>
                                            @if($categoryTotalGross > $categoryTotalNet)
                                                <br><small class="text-muted">(Before Discounts: ₹{{ number_format($categoryTotalGross, 2) }})</small>
                                            @endif
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Grand Total with present fees listed -->
            <div class="total-summary">
                <span class="total-label">
                    Grand Total (after all discounts)
                    @if(!empty($presentFees))
                    <span style="font-weight:normal; font-size:0.9rem;"> ({{ implode(' + ', $presentFees) }})</span>
                    @endif
                    :
                </span>
                <span class="total-value" id="grandTotal">₹{{ number_format($grandTotal, 2) }}</span>
            </div>

            <div class="row mt-4 no-print">
                <div class="col-12">
                    <!-- Additional notes if needed -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const grandTotal = document.getElementById('grandTotal')?.innerText || '₹0';
    document.getElementById('grandTotalDisplay').innerText = grandTotal;
});
</script>
@endsection
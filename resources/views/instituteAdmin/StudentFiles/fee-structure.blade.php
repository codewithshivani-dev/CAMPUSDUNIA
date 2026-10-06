@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
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
}


/* Main Card */
.main-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
    border: 2px solid rgba(67, 97, 238, 0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.card-header {
    background: var(--primary-gradient) !important;
    color: white;
    border-radius: 20px 20px 0 0 !important;
    padding: 18px 25px;
    border: none;
    position: relative;
    overflow: hidden;
}

.card-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: headerPulse 4s ease-in-out infinite;
}

@keyframes headerPulse {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.1); opacity: 0.3; }
}

.card-header h5 {
    font-weight: 700;
    font-size: 1.1rem;
    position: relative;
    z-index: 1;
}

.card-header .btn-light {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    border-radius: 30px;
    padding: 8px 18px;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s ease;
}

.card-header .btn-light:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.card-body {
    padding: 25px;
}

/* Student Info Card */
.student-info-card {
    background: linear-gradient(135deg, #f8fafc, #eef2ff);
    border-left: 5px solid var(--primary-color);
    padding: 18px 20px;
    margin-bottom: 25px;
    border-radius: 0 15px 15px 0;
    box-shadow: 0 3px 10px rgba(67, 97, 238, 0.08);
}

.icon-circle {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
}

.icon-circle.bg-primary {
    background: var(--primary-gradient) !important;
    box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
}

.student-info-card h5 {
    color: var(--primary-color);
    font-weight: 700;
}

/* Stats Badge */
.stats-badge {
    background: white;
    border: 2px solid rgba(67, 97, 238, 0.15);
    border-radius: 30px;
    padding: 5px 14px;
    margin: 3px;
    font-size: 0.8rem;
    font-weight: 600;
    color: #475569;
    transition: all 0.2s ease;
}

.stats-badge:hover {
    border-color: var(--primary-color);
    background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(58, 12, 163, 0.05));
}

.stats-badge i {
    color: var(--primary-color);
    margin-right: 5px;
}

.stats-badge b {
    color: #334155;
}

/* Total Card Small */
.total-card-small {
    background: var(--success-gradient);
    color: white;
    border-radius: 15px;
    padding: 15px 20px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
}

.total-card-small .small {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
    opacity: 0.9;
}

.total-card-small .h5 {
    font-size: 1.5rem;
    font-weight: 800;
}

/* Fee Category Card */
.fee-category-card {
    border: 2px solid rgba(67, 97, 238, 0.1);
    border-radius: 15px;
    margin-bottom: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.fee-category-card:hover {
    box-shadow: 0 5px 20px rgba(67, 97, 238, 0.1);
    border-color: rgba(67, 97, 238, 0.3);
}

.category-header {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    padding: 14px 20px;
    font-weight: 700;
    border-bottom: 2px solid rgba(67, 97, 238, 0.1);
    color: var(--primary-color);
    font-size: 0.95rem;
}

.category-header i {
    margin-right: 8px;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Fee Table */
.fee-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}
.fee-table thead{
    background:var(--primary-gradient);
}
.fee-table th {
    color: white;
    padding: 12px 15px;
    border: none;
    font-weight: 700;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.fee-table th:first-child {
    border-top-left-radius: 0;
}

.fee-table th:last-child {
    border-top-right-radius: 0;
}

.fee-table td {
    padding: 12px 15px;
    border-bottom: 1px solid rgba(67, 97, 238, 0.08);
    vertical-align: middle;
    font-size: 0.9rem;
}

.fee-table tr:last-child td {
    border-bottom: none;
}

.fee-table tbody tr:hover {
    background: linear-gradient(135deg, rgba(67, 97, 238, 0.03), rgba(58, 12, 163, 0.03));
}

/* Column widths */
.fee-table th:nth-child(1),
.fee-table td:nth-child(1) {
    width: 40%;
}

.fee-table th:nth-child(2),
.fee-table td:nth-child(2) {
    width: 20%;
}

.fee-table th:nth-child(3),
.fee-table td:nth-child(3) {
    width: 20%;
}

.fee-table th:nth-child(4),
.fee-table td:nth-child(4) {
    width: 20%;
}

/* Amount Cell */
.amount-cell {
    text-align: right;
    font-weight: 600;
    color: #334155;
}

/* Duration Badge */
.duration-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    background: linear-gradient(135deg, #eef2ff, #e0e7ff);
    color: var(--primary-color);
    border: 1px solid rgba(67, 97, 238, 0.15);
}

.duration-badge i {
    margin-right: 4px;
    font-size: 0.7rem;
}

/* Subtotal Row */
.subtotal-row {
    background: linear-gradient(135deg, #eef2ff, #e0e7ff) !important;
    font-weight: 700;
}

.subtotal-row td {
    padding: 12px 15px;
    border-top: 2px solid rgba(67, 97, 238, 0.2);
    color: var(--primary-color);
}

/* Total Summary */
.total-summary {
    background: linear-gradient(135deg, #eef2ff, #e0e7ff);
    border: 2px solid rgba(67, 97, 238, 0.2);
    border-radius: 15px;
    padding: 22px 25px;
    margin-top: 25px;
    font-size: 1.1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.total-summary .total-label {
    font-weight: 700;
    color: var(--primary-color);
    font-size: 1rem;
}

.total-summary .total-label span {
    color: #64748b;
}

.total-summary .total-value {
    font-size: 1.8rem;
    font-weight: 800;
    background: var(--success-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Text Styles */
.text-muted {
    color: #94a3b8 !important;
    font-size: 0.8rem;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 8px;
}

::-webkit-scrollbar-thumb {
    background: var(--primary-gradient);
    border-radius: 8px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--secondary-color);
}

/* Table Responsive */
.table-responsive::-webkit-scrollbar {
    height: 4px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: var(--primary-gradient);
    border-radius: 4px;
}

/* PRINT STYLES */
@media print {
    @page {
        size: A4;
        margin: 10mm;
    }

    body * {
        visibility: hidden;
    }

    #printArea,
    #printArea * {
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
        color: #000 !important;
    }

    .student-info-card,
    .fee-category-card,
    .total-summary {
        break-inside: avoid;
    }
    
    .total-summary .total-value {
        -webkit-text-fill-color: #059669 !important;
        color: #059669 !important;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .card-body {
        padding: 15px;
    }
    
    .student-info-card {
        padding: 15px;
    }
    
    .total-summary {
        flex-direction: column;
        gap: 10px;
        text-align: center;
        padding: 18px 20px;
    }
    
    .total-summary .total-value {
        font-size: 1.5rem;
    }
    
    .fee-table th,
    .fee-table td {
        padding: 8px 10px;
        font-size: 0.8rem;
    }
    
    .category-header {
        padding: 12px 15px;
        font-size: 0.85rem;
    }
}
</style>

<div class="container-fluid py-3" id="printArea">
    <div class="main-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-money-bill-wave me-2"></i>My Fee Structure
                </h5>
                <div class="no-print">
                    <button class="btn btn-sm btn-light" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                    <a href="{{ route('student.dashboard') }}" class="btn btn-sm btn-light ms-2">
                        <i class="fas fa-arrow-left me-1"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Student Information -->
            <div class="student-info-card">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-2">
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
                        <div class="d-flex flex-wrap">
                            @if($studentDetail->academicTransportDetails->department ?? false)
                            <span class="stats-badge">
                                <i class="fas fa-building me-1"></i>Dept:
                                <b>{{ $studentDetail->academicTransportDetails->department }}</b>
                            </span>
                            @endif
                            <span class="stats-badge">
                                <i class="fas fa-book me-1"></i>Class:
                                <b>{{ $studentDetail->academicTransportDetails->course_type ?? 'N/A' }} -
                                    {{ $studentDetail->academicTransportDetails->course_subtype ?? 'N/A' }}</b>
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
            'course_fee' => ['title' => 'Class Fee', 'icon' => 'fas fa-book', 'relation' => 'StudentCourseFeeStructure',
            'fee_field' => 'course_fee'],
            'hostel_fee' => ['title' => 'Hostel Fee', 'icon' => 'fas fa-bed', 'relation' => 'StudentHostelFeeStructure',
            'fee_field' => 'hostel_fee'],
            'transportation_fee' => ['title' => 'Transport Fee', 'icon' => 'fas fa-bus', 'relation' =>
            'StudentTransportFeeStructure', 'fee_field' => 'transport_fee'],
            'registration_fee' => ['title' => 'Registration Fee', 'icon' => 'fas fa-file-signature', 'relation' =>
            'StudentRegistrationFeeStructure', 'fee_field' => 'registration_fee'],
            'miscellaneous_fee' => ['title' => 'Miscellaneous Fee', 'icon' => 'fas fa-receipt', 'relation' =>
            'StudentMiscellaneousFeeStructure', 'fee_field' => 'miscellaneous_fee'],
            'custom_fees' => ['title' => 'Custom Fees', 'icon' => 'fas fa-list-alt', 'relation' =>
            'StudentCustomFeestructure', 'fee_field' => 'custom_fee_value']
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
                        <thead style="">
                            <tr>
                                <th>Fee Details</th>
                                <th>Duration</th>
                                <th class="amount-cell">Discount (₹)</th>
                                <th class="amount-cell">Per Installment (₹)</th>
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
            $categoryTotalGross = 0; // NEW: track original total before discounts
            foreach ($groups as &$group) {
            $durationInfo = getDurationInfo($group['duration']);
            $multiplier = $durationInfo['multiplier'];

            $perInstallment = $group['first_amount'];
            $gross = $perInstallment * $multiplier; // original total for this group
            $groupNet = $gross - $group['total_discount'];
            $group['net_total'] = $groupNet;
            $categoryTotalNet += $groupNet;
            $categoryTotalGross += $gross; // add to gross total
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
                                <th>Fee Details</th>
                                <th>Duration</th>
                                <th class="amount-cell">Discount (₹)</th>
                                <th class="amount-cell">Per Installment (₹)</th>
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
                                        ({{ $multiplier }} installments) =
                                        -₹{{ number_format($group['total_discount'], 2) }} total
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
                                <td class="amount-cell"><strong>₹{{ number_format($categoryTotalNet, 2) }}</strong>
                                    @if($categoryTotalGross > $categoryTotalNet)
                                    <br><small class="text-muted">(Before Discounts:
                                        ₹{{ number_format($categoryTotalGross, 2) }})</small>
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif
            @endforeach

            <!-- Grand Total -->
            <div class="total-summary">
                <span class="total-label">
                    <i class="fas fa-calculator me-2"></i>Grand Total
                    @if(!empty($presentFees))
                    <br>
                    <span>({{ implode(' + ', $presentFees) }})</span>
                    @endif
                </span>
                <span class="total-value" id="grandTotal">₹{{ number_format($grandTotal, 2) }}</span>
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
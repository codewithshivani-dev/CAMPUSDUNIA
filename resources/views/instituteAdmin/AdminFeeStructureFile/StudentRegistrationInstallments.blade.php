@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Registration Fee Installments - {{ $studentData->first_name ?? '' }} {{ $studentData->last_name ?? '' }}</title>

<style>
:root {
    --primary: #4361ee;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
    --info: #3b82f6;
}

.dashboard-header {
    background: linear-gradient(135deg, var(--primary) 0%, #3a56d4 100%);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    color: white;
}

.header-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.header-subtitle {
    font-size: 0.9rem;
    opacity: 0.9;
}

/* Summary Cards */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: .5rem;
    margin-bottom: 2rem;
}

.summary-card {
    background: white;
    border-radius: 12px;
    padding: .9rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.summary-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.summary-label {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6b7280;
    margin-bottom: 0.5rem;
}

.summary-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1f2937;
}

.summary-trend {
    font-size: 0.85rem;
    margin-top: 0.5rem;
}

.progress-bar-container {
    margin-top: 1rem;
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar-fill {
    height: 100%;
    background: var(--success);
    transition: width 0.3s ease;
}

/* Installments Table */
.installments-table {
    background: white;
    border-radius: 12px;
    /*overflow: hidden;*/
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.table-header {
    background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
    color: white;
}

.table-header th {
    padding: 1rem 1.25rem;
    font-weight: 500;
    font-size: 0.85rem;
    text-transform: uppercase;
}

.table-body tr {
    border-bottom: 1px solid #e5e7eb;
    transition: background 0.2s;
}

.table-body tr:hover {
    background: #f9fafb;
}

.table-body td {
    padding: 1rem 1.25rem;
    vertical-align: middle;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-paid {
    background: #d1fae5;
    color: #065f46;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-overdue {
    background: #fee2e2;
    color: #991b1b;
}

.amount-cell {
    text-align: right;
    font-weight: 500;
}

.btn-back {
    background: white;
    color: var(--primary);
    border: 1px solid var(--primary);
    padding: 0.5rem 1rem;
    border-radius: 8px;
    transition: all 0.2s;
    text-decoration: none;
}

/*.btn-back:hover {*/
/*    background: var(--primary);*/
/*    color: white;*/
/*    text-decoration: none;*/
/*}*/

/* Academic Year Badge */
.academic-year-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1rem;
    border-radius: 30px;
    font-size: 0.875rem;
}

/* Receipt Modal Styles */
.receipt-modal {
    max-width: 800px;
}

.receipt-container {
    padding: 0;
}

.receipt-paper {
    background: white;
    padding: 40px;
    font-family: 'Courier New', monospace;
    max-width: 210mm;
    margin: 0 auto;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

.receipt-header {
    text-align: center;
    margin-bottom: 30px;
    border-bottom: 3px double #333;
    padding-bottom: 20px;
}

.institute-name {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 5px;
    color: #2c3e50;
}

.receipt-title {
    font-size: 22px;
    font-weight: bold;
    color: #2c3e50;
    margin: 15px 0;
    text-transform: uppercase;
}

.watermark {
    position: absolute;
    opacity: 0.1;
    font-size: 120px;
    transform: rotate(-45deg);
    top: 30%;
    left: 10%;
    color: #333;
    pointer-events: none;
}

.receipt-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-top: 30px;
    padding: 20px;
    border-top: 1px solid #dee2e6;
    background: #f8f9fa;
}

@media print {
    body * {
        visibility: hidden;
    }
    .receipt-paper, .receipt-paper * {
        visibility: visible;
    }
    .receipt-paper {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none;
    }
    .receipt-actions {
        display: none;
    }
}

.receipt-preview {
    max-height: 600px;
    overflow-y: auto;
    margin-bottom: 20px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
}

        /* ERP Table Styles */
        .erp-table {
            width: 100%;
            background: #fff;
            border-collapse: collapse;
        }
        
        .erp-table thead {
            background: var(--primary-gradient);
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .erp-table th {
            padding: 15px 8px;
            font-weight: 600;
            color: white;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            cursor: pointer;
            user-select: none;
            transition: background-color 0.2s;
            position: relative;
        }

        .erp-table th h6 {
            color: rgba(255,255,255,0.9);
            font-size: 11px;
            margin: 2px 0 0;
        }

        .erp-table th:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .erp-table th.sortable {
            padding-right: 30px;
        }

        .sort-icons {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sort-icon {
            color: rgba(255,255,255,0.5);
            font-size: 12px;
            line-height: 1;
        }

        .sort-icon.active {
            color: white;
        }

        .erp-table td {
            padding: 15px 8px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 14px;
            vertical-align: middle;
        }

        .erp-table tbody tr {
            transition: background-color 0.2s, transform 0.2s;
        }

        .erp-table tbody tr:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .erp-table tbody tr:last-child td {
            border-bottom: none;
        }
        .table-responsive{
            overflow-x: hidden;
        }
        
        .erp-table tbody .sticky-main,
        .erp-table thead .sticky-main{
            left: 28px;
        }
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="header-title">
                    <i class="bi bi-calendar-check me-2"></i>Registration Fee Installments
                </h1>
                <div class="header-subtitle">
                    <i class="bi bi-person"></i> {{ $studentData->first_name ?? '' }}
                    {{ $studentData->middle_name ?? '' }} {{ $studentData->last_name ?? '' }}
                    <span class="mx-2">|</span>
                    <i class="bi bi-person-badge"></i> {{ $studentData->registration_number ?? 'N/A' }}
                    <span class="mx-2">|</span>
                    <i class="bi bi-mortarboard"></i> {{ $academic->course_type ?? 'N/A' }}
                </div>
                <div class="academic-year-badge mt-2">
                    <i class="bi bi-calendar"></i>
                    Academic Year: <strong>{{ $academicYearFilter ?? 'Current' }}</strong>
                </div>
            </div>
            <div>
                <button class="btn btn-primary me-2" onclick="printFullSummary()">
                    <i class="bi bi-printer me-2"></i>Print Full Summary
                </button>
                <a href="{{ url()->previous() }}" class="btn-back" style="text-decoration: none;">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-label">Total Installments</div>
            <div class="summary-value">{{ $summary['total_installments'] }}</div>
            <div class="summary-trend text-muted">Registration fee payments</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Total Payable</div>
            <div class="summary-value">₹{{ number_format($summary['total_fee'], 2) }}</div>
            <div class="summary-trend text-muted">
                @if($summary['total_discount'] > 0)
                Discount: -₹{{ number_format($summary['total_discount'], 2) }}
                @endif
                @if($summary['total_late_fee'] > 0)
                <br>Late Fee: +₹{{ number_format($summary['total_late_fee'], 2) }}
                @endif
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Paid Amount</div>
            <div class="summary-value text-success">₹{{ number_format($summary['total_paid'], 2) }}</div>
            <div class="summary-trend">{{ $summary['paid_count'] }} of {{ $summary['total_installments'] }} installments paid</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Pending Amount</div>
            <div class="summary-value text-danger">₹{{ number_format($summary['total_pending'], 2) }}</div>
            <div class="summary-trend">{{ $summary['pending_count'] }} installments pending</div>
            <div class="progress-bar-container">
                <div class="progress-bar-fill" style="width: {{ $summary['completion_percentage'] }}%"></div>
            </div>
            <div class="summary-trend mt-2">
                {{ number_format($summary['completion_percentage'], 1) }}% Complete
            </div>
        </div>
    </div>

    <!-- Installments Table -->
    <div class="installments-table">
        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
            <table class="erp-table table mb-0">
                <thead class="table-header">
                    <tr>
                        <th class="sticky-checkbox">#</th>
                        <th class="sticky-main sortable">Due Date</th>
                        <th class="sortable">Payment Date</th>
                        <th class="sortable">Fee Amount</th>
                        <th class="sortable">Late Fee</th>
                        <th class="sortable">Discount</th>
                        <th class="sortable">Payable</th>
                        <th class="sortable">Status</th>
                        <th class="sortable">Action</th>
                    </tr>
                </thead>
                <tbody class="table-body">
                    @forelse($installments as $index => $inst)
                    @php
                    $feeAmount = $inst->registration_fee ?? 0;
                    $lateFee = $inst->late_fee_amount ?? 0;
                    $discount = $inst->discount_amount ?? 0;
                    $payable = $feeAmount + $lateFee - $discount;

                    $dueDate = \Carbon\Carbon::parse($inst->due_date);
                    $instStatus = strtolower(trim($inst->payment_status ?? ''));
                    $isOverdue = $instStatus !== 'paid' && $dueDate->lt(\Carbon\Carbon::today());
                    $status = $instStatus === 'paid' ? 'paid' : ($isOverdue ? 'overdue' : 'pending');
                    @endphp
                    <tr>
                        <td class="sticky-checkbox fw-bold">{{ $index + 1 }}</td>
                        <td class="sticky-main">
                            {{ $dueDate->format('d M Y') }}
                            @if($lateFee > 0)
                            <br><small class="text-danger">Late fee applies</small>
                            @endif
                        </td>
                        <td>
                            @if(strtolower(trim($inst->payment_status ?? '')) === 'paid' && $inst->pay_date)
                            {{ \Carbon\Carbon::parse($inst->pay_date)->format('d M Y') }}
                            @if($inst->payment_type)
                            <br><small class="text-muted">via {{ ucfirst($inst->payment_type) }}</small>
                            @endif
                            @else
                            —
                            @endif
                        </td>
                        <td class="amount-cell">₹{{ number_format($feeAmount, 2) }}</td>
                        <td class="amount-cell text-danger">
                            @if($lateFee > 0)
                            +₹{{ number_format($lateFee, 2) }}
                            @else
                            —
                            @endif
                        </td>
                        <td class="amount-cell text-success">
                            @if($discount > 0)
                            -₹{{ number_format($discount, 2) }}
                            @else
                            —
                            @endif
                        </td>
                        <td class="amount-cell fw-bold">₹{{ number_format($payable, 2) }}</td>
                        <td>
                            @if($status == 'paid')
                            <span class="status-badge status-paid">
                                <i class="bi bi-check-circle"></i> Paid
                            </span>
                            @elseif($status == 'overdue')
                            <span class="status-badge status-overdue">
                                <i class="bi bi-exclamation-triangle"></i> Overdue
                            </span>
                            @else
                            <span class="status-badge status-pending">
                                <i class="bi bi-clock"></i> Pending
                            </span>
                            @endif
                        </td>
                        <td>
                            @if(strtolower(trim($inst->payment_status ?? '')) === 'paid')
                            <button class="btn btn-sm btn-outline-primary" onclick="viewReceipt({{ $inst->id }})"
                                title="View Receipt">
                                <i class="bi bi-receipt"></i> Receipt
                            </button>
                            @else
                            <button class="btn btn-sm btn-outline-secondary" disabled
                                title="Receipt available after payment">
                                <i class="bi bi-receipt"></i> Receipt
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            No registration fee installment records found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Floating Horizontal Scrollbar -->
        <div class="table-scroll-top" id="tableScrollTop">
            <div class="table-scroll-inner"></div>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true"
    style="z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered receipt-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">Registration Fee Payment Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body receipt-container">
                <div class="receipt-preview" id="receiptPreview">
                    <!-- Receipt will be generated here -->
                </div>
                <div class="receipt-actions">
                    <button type="button" class="btn btn-outline-primary" onclick="printReceipt()">
                        <i class="bi bi-printer me-2"></i>Print Receipt
                    </button>
                    <button type="button" class="btn btn-primary" onclick="downloadReceiptAsPDF()">
                        <i class="bi bi-download me-2"></i>Download PDF
                    </button>
                    <button type="button" class="btn btn-success" onclick="downloadReceiptAsImage()">
                        <i class="bi bi-image me-2"></i>Download Image
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
const installmentsData = @json($installments);
const studentData = {
    student_hash_id: "{{ $studentData->student_hash_id }}",
    first_name: "{{ $studentData->first_name }}",
    middle_name: "{{ $studentData->middle_name }}",
    last_name: "{{ $studentData->last_name }}",
    registration_number: "{{ $studentData->registration_number }}",
    department: "{{ $academic->department ?? '' }}",
    course: "{{ $academic->course_type ?? '' }}",
    batch: "{{ $academic->batch ?? '' }}",
    academic_year: "{{ $academic->academic_year ?? '' }}",
    semester: "{{ $academic->semester_id ?? '' }}",
    section: "{{ $sectionName ?? $academic->section_id }}"
};
const serviceInstitutedetails = @json($serviceInstitutedetails ?? null);

let currentReceiptData = null;

function viewReceipt(installmentId) {
    const installment = installmentsData.find(inst => inst.id == installmentId);
    if (!installment) {
        alert('Installment not found');
        return;
    }

    currentReceiptData = {
        id: installment.id,
        student_name: studentData.first_name + ' ' + (studentData.middle_name || '') + ' ' + studentData.last_name,
        student_reg: studentData.registration_number || 'N/A',
        department: studentData.department || 'N/A',
        course: studentData.course || 'N/A',
        batch: studentData.batch || 'N/A',
        academic_year: studentData.academic_year || 'N/A',
        semester: studentData.semester || 'N/A',
        section: studentData.section || 'N/A',
        fee_amount: parseFloat(installment.registration_fee || 0),
        late_fee: parseFloat(installment.late_fee_amount || 0),
        discount: parseFloat(installment.discount_amount || 0),
        payable_amount: (parseFloat(installment.registration_fee || 0) + parseFloat(installment.late_fee_amount || 0) -
            parseFloat(installment.discount_amount || 0)),
        payment_type: installment.payment_type || 'cash',
        payment_status: installment.payment_status ? installment.payment_status.toLowerCase() : 'pending',
        pay_date: installment.pay_date,
        due_date: installment.due_date,
        fee_reference_id: installment.fee_reference_id,
        transaction_id: installment.transaction_id,
        receiptDate: new Date().toLocaleDateString('en-IN'),
        receiptTime: new Date().toLocaleTimeString('en-IN', { hour12: true, hour: '2-digit', minute: '2-digit' }),
        receiptNumber: generateReceiptNumber(),
        instituteName: serviceInstitutedetails?.name || 'Institute Name',
        instituteAddress: (serviceInstitutedetails?.address_line_1 || '') + ' ' + (serviceInstitutedetails?.address_line_2 || '') + ' ' + (serviceInstitutedetails?.state || '') + ' ' + (serviceInstitutedetails?.city || '') + ' ' + (serviceInstitutedetails?.pincode || ''),
        institutePhone: serviceInstitutedetails?.contact_number || '',
        instituteEmail: serviceInstitutedetails?.email || '',
        website: serviceInstitutedetails?.website || '',
        terms: [
            "This is a computer generated receipt and does not require signature.",
            "For any queries, contact accounts department within 7 days."
        ]
    };

    generateReceiptHTML(currentReceiptData);
    const modal = new bootstrap.Modal(document.getElementById('receiptModal'));
    modal.show();
}

function generateReceiptNumber() {
    const date = new Date();
    const year = date.getFullYear().toString().substr(-2);
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
    return `REGRCPT${year}${month}${day}${random}`;
}

function generateReceiptHTML(payment) {
    const receiptPreview = document.getElementById('receiptPreview');

    const formatCurrency = (amount) => {
        return '₹' + parseFloat(amount || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };

    const payDate = payment.pay_date ? new Date(payment.pay_date).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' }) : 'N/A';
    const dueDate = payment.due_date ? new Date(payment.due_date).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' }) : 'N/A';

    const feeAmount = parseFloat(payment.fee_amount || 0);
    const lateFee = parseFloat(payment.late_fee || 0);
    const discount = parseFloat(payment.discount || 0);
    const payable = parseFloat(payment.payable_amount || 0);

    const paymentStatusHTML = payment.payment_status === 'paid' ?
        '<span style="color: #28a745; font-weight: bold;"><i class="bi bi-check-circle"></i> PAID</span>' :
        '<span style="color: #dc3545; font-weight: bold;"><i class="bi bi-x-circle"></i> NOT PAID</span>';

    const paymentMethodHTML = {
        online: '<span style="color: #28a745;"><i class="bi bi-credit-card"></i> Online Payment</span>',
        cash: '<span style="color: #007bff;"><i class="bi bi-cash"></i> Cash Payment</span>',
        bank: '<span style="color: #6f42c1;"><i class="bi bi-bank"></i> Bank Transfer</span>'
    }[payment.payment_type] || '<span>N/A</span>';

    const receiptHTML = `
        <div class="receipt-paper" id="receiptContent">
            <div class="watermark">PAID</div>
            <div class="receipt-header">
                <div class="institute-name">${payment.instituteName}</div>
                <div class="receipt-title">REGISTRATION FEE PAYMENT RECEIPT</div>
                <div class="institute-address">${payment.instituteAddress}</div>
                <div class="institute-contact">Phone: ${payment.institutePhone} | Email: ${payment.instituteEmail}</div>
            </div>
            <div class="student-details-section" style="display: flex; gap: 20px; margin-bottom: 20px;">
                <div style="flex:1; border:1px solid #ddd; padding:15px; background:#f8f9fa;">
                    <div style="font-weight:600; margin-bottom:5px; font-size:13px;"><i class="bi bi-person-circle"></i> STUDENT INFORMATION</div>
                    <div><span style="font-weight:500;">Name:</span> ${payment.student_name}</div>
                    <div><span style="font-weight:500;">Reg No:</span> ${payment.student_reg}</div>
                </div>
                <div style="flex:1; border:1px solid #ddd; padding:15px; background:#f8f9fa;">
                    <div style="font-weight:600; margin-bottom:5px; font-size:13px;"><i class="bi bi-receipt"></i> RECEIPT INFORMATION</div>
                    <div><span style="font-weight:500;">Receipt No:</span> ${payment.receiptNumber}</div>
                    <div><span style="font-weight:500;">Date:</span> ${payment.receiptDate} ${payment.receiptTime}</div>
                </div>
            </div>
            <div style="border:1px solid #ddd; padding:10px; background:#f8f9fa; margin-bottom:15px;">
                <div style="font-weight:600; margin-bottom:5px; font-size:13px;"><i class="bi bi-mortarboard"></i> ACADEMIC INFORMATION</div>
                <div style="display: grid; grid-template-columns: repeat(2,1fr); gap:4px 10px; font-size:12px;">
                    <div><b>Department:</b> ${payment.department}</div>
                    <div><b>Class:</b> ${payment.course}</div>
                    <div><b>Batch:</b> ${payment.batch}</div>
                    <div><b>Year:</b> ${payment.academic_year}</div>
                    <div><b>Section:</b> ${payment.section}</div>
                </div>
            </div>
            <div style="display: flex; gap: 20px; margin-bottom:10px;">
                <div style="flex:1; border:1px solid #ddd; padding:15px; background:#f8f9fa;">
                    <div style="font-weight:600; margin-bottom:5px; font-size:13px;"><i class="bi bi-cash-stack"></i> FEE DETAILS</div>
                    <div><span style="font-weight:500;">Fee Type:</span> Registration Fee</div>
                    <div><span style="font-weight:500;">Due Date:</span> ${dueDate}</div>
                    <div><span style="font-weight:500;">Payment Date:</span> ${payDate}</div>
                    <div style="margin-top:15px;">
                        <div style="display:flex; justify-content:space-between;"><span>Registration Fee:</span> ${formatCurrency(feeAmount)}</div>
                        ${lateFee > 0 ? `<div style="display:flex; justify-content:space-between; color:#dc3545;"><span>Late Fee:</span> +${formatCurrency(lateFee)}</div>` : ''}
                        ${discount > 0 ? `<div style="display:flex; justify-content:space-between; color:#28a745;"><span>Discount:</span> -${formatCurrency(discount)}</div>` : ''}
                        <div style="display:flex; justify-content:space-between; font-weight:bold; border-top:2px solid #333; margin-top:8px; padding-top:8px;">
                            <span>TOTAL PAYABLE:</span> <span>${formatCurrency(payable)}</span>
                        </div>
                    </div>
                </div>
                <div style="flex:1; border:1px solid #ddd; padding:15px; background:#f8f9fa;">
                    <div style="font-weight:600; margin-bottom:5px; font-size:13px;"><i class="bi bi-credit-card-2-front"></i> PAYMENT INFORMATION</div>
                    <div><span style="font-weight:500;">Status:</span> ${paymentStatusHTML}</div>
                    <div><span style="font-weight:500;">Method:</span> ${paymentMethodHTML}</div>
                    ${payment.fee_reference_id ? `<div><span style="font-weight:500;">Transaction ID:</span> <code>${payment.fee_reference_id}</code></div>` : ''}
                    ${payment.transaction_id ? `<div><span style="font-weight:500;">Reference No:</span> <code>${payment.transaction_id}</code></div>` : ''}
                </div>
            </div>
            <div class="receipt-footer">
                <div style="background:#f1f8ff; border:1px solid #d1e7ff; padding:12px; margin-bottom:15px;">
                    <div style="font-weight:bold;">Terms & Conditions:</div>
                    <ul style="font-size:11px; margin-bottom:0;">
                        ${payment.terms.map(term => `<li>${term}</li>`).join('')}
                    </ul>
                </div>
            </div>
        </div>
    `;

    receiptPreview.innerHTML = receiptHTML;
}

function printReceipt() {
    window.print();
}

async function downloadReceiptAsPDF() {
    if (!currentReceiptData) return;
    try {
        const downloadBtn = document.querySelector('#receiptModal .btn-primary');
        const originalText = downloadBtn.innerHTML;
        downloadBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Generating PDF...';
        downloadBtn.disabled = true;

        const receiptElement = document.getElementById('receiptContent');
        const canvas = await html2canvas(receiptElement, { scale: 2, useCORS: true, backgroundColor: '#ffffff' });
        const imgData = canvas.toDataURL('image/png');

        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const imgHeight = (canvas.height * pdfWidth) / canvas.width;

        pdf.addImage(imgData, 'PNG', 10, 10, pdfWidth - 20, imgHeight);
        pdf.save(`Registration_Fee_Receipt_${currentReceiptData.receiptNumber}.pdf`);

        showDownloadSuccess('PDF');
    } catch (error) {
        console.error(error);
        alert('Error generating PDF. Please try again.');
    } finally {
        const downloadBtn = document.querySelector('#receiptModal .btn-primary');
        downloadBtn.innerHTML = originalText;
        downloadBtn.disabled = false;
    }
}

async function downloadReceiptAsImage() {
    if (!currentReceiptData) return;
    try {
        const downloadBtn = document.querySelector('#receiptModal .btn-success');
        const originalText = downloadBtn.innerHTML;
        downloadBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Generating Image...';
        downloadBtn.disabled = true;

        const receiptElement = document.getElementById('receiptContent');
        const canvas = await html2canvas(receiptElement, { scale: 2, useCORS: true, backgroundColor: '#ffffff' });
        const imgData = canvas.toDataURL('image/png');

        const link = document.createElement('a');
        link.download = `Registration_Fee_Receipt_${currentReceiptData.receiptNumber}.png`;
        link.href = imgData;
        link.click();

        showDownloadSuccess('Image');
    } catch (error) {
        console.error(error);
        alert('Error generating image. Please try again.');
    } finally {
        const downloadBtn = document.querySelector('#receiptModal .btn-success');
        downloadBtn.innerHTML = originalText;
        downloadBtn.disabled = false;
    }
}

function showDownloadSuccess(fileType) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>
                <strong>${fileType} Downloaded Successfully!</strong>
                <div class="small">Receipt for ${currentReceiptData?.student_name}</div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.body.appendChild(alertDiv);
    setTimeout(() => alertDiv.remove(), 5000);
}

function printFullSummary() {
    let studentName = `${studentData.first_name} ${studentData.middle_name || ''} ${studentData.last_name}`;
    let rows = '';

    installmentsData.forEach((inst, index) => {
        let fee = parseFloat(inst.registration_fee || 0);
        let late = parseFloat(inst.late_fee_amount || 0);
        let discount = parseFloat(inst.discount_amount || 0);
        let total = fee + late - discount;

        let status = inst.payment_status;
        let dueDate = new Date(inst.due_date).toLocaleDateString('en-IN');
        let payDate = inst.pay_date ? new Date(inst.pay_date).toLocaleDateString('en-IN') : '-';

        rows += `
            <tr>
                <td>${index + 1}</td>
                <td>${dueDate}</td>
                <td>${payDate}</td>
                <td>₹${fee.toFixed(2)}</td>
                <td>₹${late.toFixed(2)}</td>
                <td>₹${discount.toFixed(2)}</td>
                <td><b>₹${total.toFixed(2)}</b></td>
                <td>${status}</td>
            </tr>
        `;
    });

    let printContent = `
        <html>
        <head>
            <title>Registration Fee Installment Summary</title>
            <style>
                body { font-family: Arial; padding: 20px; }
                h2, h3 { margin: 5px 0; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #000; padding: 8px; text-align: center; }
                th { background: #f2f2f2; }
                .summary { margin-top: 20px; }
            </style>
        </head>
        <body>
            <h2>${serviceInstitutedetails?.name || 'Institute Name'}</h2>
            <h3>Registration Fee Installment Summary</h3>
            <p><b>Student:</b> ${studentName}</p>
            <p><b>Reg No:</b> ${studentData.registration_number}</p>
            <p><b>Course:</b> ${studentData.course}</p>
            <p><b>Batch:</b> ${studentData.batch}</p>
            <table>
                <thead>
                    <tr>
                        <th>#</th><th>Due Date</th><th>Payment Date</th><th>Fee</th><th>Late Fee</th><th>Discount</th><th>Total</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
            <div class="summary">
                <p><b>Total Installments:</b> ${installmentsData.length}</p>
                <p><b>Total Payable:</b> ₹{{ number_format($summary['total_fee'] ?? 0, 2) }}</p>
                <p><b>Paid:</b> ₹{{ number_format($summary['total_paid'] ?? 0, 2) }}</p>
                <p><b>Pending:</b> ₹{{ number_format($summary['total_pending'] ?? 0, 2) }}</p>
            </div>
        </body>
        </html>
    `;

    let win = window.open('', '', 'width=900,height=700');
    win.document.write(printContent);
    win.document.close();
    win.print();
}
</script>
@endsection
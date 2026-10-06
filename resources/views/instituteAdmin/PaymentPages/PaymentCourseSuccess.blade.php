@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
{{-- Bootstrap Icons CDN --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background: #f8fafc;
    }

    .payment-success-container {
        max-width: 600px;
        margin: 80px auto;
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 50px 40px;
        text-align: center;
        animation: fadeInUp 0.8s ease;
    }

    .success-icon {
        position: relative;
        display: inline-block;
        width: 100px;
        height: 100px;
        background: #28a745;
        border-radius: 50%;
        margin-bottom: 25px;
        animation: pop 0.6s ease;
    }

    .success-icon::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 50%;
        height: 25%;
        border-left: 5px solid #fff;
        border-bottom: 5px solid #fff;
        transform: translate(-50%, -60%) rotate(-45deg);
    }

    .payment-success-title {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 12px;
        font-size: 1.8rem;
    }

    .payment-success-text {
        font-size: 1.1rem;
        color: #64748b;
        margin-bottom: 30px;
    }

    .payment-success-id {
        background: #f1f5f9;
        border-radius: 10px;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 600;
        color: #0f172a;
        font-family: monospace;
        margin-bottom: 25px;
    }

    .copy-btn {
        background: transparent;
        border: none;
        color: #0d6efd;
        font-size: 1.3rem;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .copy-btn:hover {
        color: #0b5ed7;
    }

    .payment-success-btn {
        background: #0d6efd;
        border: none;
        padding: 12px 26px;
        border-radius: 10px;
        color: #fff;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .payment-success-btn:hover {
        background: #0b5ed7;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
    }

    @keyframes pop {
        0% { transform: scale(0.5); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="payment-success-container">
    <div class="success-icon"></div>

    <h2 class="payment-success-title">Payment Successful!</h2>
    <p class="payment-success-text">Thank you for your payment. Your transaction has been processed successfully.</p>

    <div class="payment-success-id">
        <span id="paymentId">{{ $paymentId }}</span>
        <button class="copy-btn" onclick="copyPaymentId()" title="Copy Payment ID">
            <i class="bi bi-clipboard"></i>
        </button>
    </div>

    <a href="{{ route('student.fee.structure') }}" class="payment-success-btn">
        <i class="bi bi-arrow-left-circle"></i> Back to Return Page
    </a>
</div>

<script>
function copyPaymentId() {
    const paymentId = document.getElementById('paymentId').textContent;
    navigator.clipboard.writeText(paymentId).then(() => {
        const btn = document.querySelector('.copy-btn i');
        btn.classList.replace('bi-clipboard', 'bi-clipboard-check');
        btn.style.color = '#28a745';
        setTimeout(() => {
            btn.classList.replace('bi-clipboard-check', 'bi-clipboard');
            btn.style.color = '#0d6efd';
        }, 2000);
    });
}
</script>
@endsection

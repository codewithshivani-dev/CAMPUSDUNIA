<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onboarding Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card border-0 shadow-lg rounded-4 p-4 text-center">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <div class="bg-success bg-opacity-10 rounded-circle p-4 d-inline-block">
                                <i class="bi bi-check-circle-fill text-success display-1"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-3">🎉 Onboarding Completed!</h2>
                        <p class="text-muted mb-4">
                            Thank you for completing your onboarding registration. Your details have been submitted
                            successfully.
                        </p>
                        @if(session('success'))
                            <div class="alert alert-success border-0">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="mt-4">
                            <p class="text-muted small">
                                <i class="bi bi-envelope-fill me-1"></i>
                                You will receive further communication from the HR team shortly.
                            </p>
                            <a href="{{ route('external.form') }}" class="btn btn-outline-primary mt-2">
                                <i class="bi bi-arrow-left me-1"></i> Back to Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
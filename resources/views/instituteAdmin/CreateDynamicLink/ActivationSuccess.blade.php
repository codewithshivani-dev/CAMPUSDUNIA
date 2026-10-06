<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activation Successful</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow-lg p-4 text-center" style="max-width: 420px;">
        <div class="mb-3">
            <span class="fs-1 text-success">✔</span>
        </div>

        <h4 class="mb-2">Activation Successful</h4>
        <p class="text-muted">
            Your link has been verified successfully.
            <br>This link is now expired and cannot be reused.
        </p>

        <a href="{{ url('/') }}" class="btn btn-success mt-3">
            Go to Home
        </a>
    </div>

</body>
</html>

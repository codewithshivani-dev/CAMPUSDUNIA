<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Activation Link</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

<div class="card shadow-lg p-4" style="max-width: 520px; width:100%;">
    <h4 class="text-center mb-3">🔗 Generate Activation Link</h4>

    {{-- Form --}}
    <form method="POST" action="{{ route('activate.generate') }}">
        @csrf

        {{-- Link Type --}}
        <div class="mb-3">
            <label class="form-label">Link Type</label>
            <select name="link_type" class="form-select" required>
                <option value="">-- Select Type --</option>
                <option value="admission_registration">Track Student Admission Process</option>
                <option value="interview_process">Track Interview Process</option>
                <option value="invite">Invite Link</option>
            </select>
        </div>

        {{-- Expiry Controls --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Expiry Type</label>
                <select name="expiry_type" class="form-select" required>
                    <option value="minute">Minutes</option>
                    <option value="day">Days</option>
                    <option value="month">Months</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Expiry Value</label>
                <input type="number"
                       name="expiry_value"
                       class="form-control"
                       min="1"
                       placeholder="e.g. 30"
                       required>
            </div>
        </div>

        <button class="btn btn-primary w-100">
            Generate Link
        </button>
    </form>

    {{-- Result --}}
    @if(session('activation_url'))
        <hr>

        <label class="form-label fw-bold">Generated Link</label>

        <div class="input-group">
            <input type="text"
                   class="form-control"
                   id="activationLink"
                   value="{{ session('activation_url') }}"
                   readonly>
            <button class="btn btn-outline-secondary" onclick="copyLink()">Copy</button>
        </div>

        <p class="small text-muted mt-2">
            🔐 Type: <b>{{ session('link_type') }}</b><br>
            ⏳ Expires at: {{ session('expires_at') }}<br>
            ✔ One-time use
        </p>
    @endif
</div>

<script>
function copyLink() {
    const input = document.getElementById('activationLink');
    input.select();
    input.setSelectionRange(0, 99999);
    document.execCommand("copy");
    alert("Link copied!");
}
</script>

</body>
</html>

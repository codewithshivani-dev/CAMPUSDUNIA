<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Certificate Verification | Official Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #eef2f7 0%, #dce5ef 100%);
            font-family: 'Inter', sans-serif;
            padding: 2rem 1.5rem;
            min-height: 100vh;
        }

        .verification-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .verification-header {
            background: linear-gradient(135deg, #0a2b3e 0%, #1a4b6e 100%);
            padding: 2rem 2rem 1.8rem;
            color: white;
            text-align: center;
        }

        .verification-header h1 {
            font-weight: 800;
            font-size: 2rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .verification-header h1 i {
            background: #ffd166;
            padding: 10px;
            border-radius: 60px;
            color: #1e4a6b;
        }

        .badge {
            background: rgba(255,255,255,0.2);
            padding: 0.3rem 1rem;
            border-radius: 40px;
            font-size: 0.75rem;
            display: inline-block;
        }

        .verification-body {
            padding: 2rem;
        }

        .input-group {
            margin-bottom: 1.5rem;
        }

        label {
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #1e4a6b;
            display: block;
            margin-bottom: 0.6rem;
        }

        label i {
            margin-right: 8px;
            color: #2c7da0;
        }

        .input-field {
            width: 100%;
            padding: 1rem 1.3rem;
            font-size: 1rem;
            border: 2px solid #e2edf5;
            border-radius: 1.5rem;
            transition: all 0.2s;
            outline: none;
            font-family: 'Inter', monospace;
        }

        .input-field:focus {
            border-color: #2c7da0;
            box-shadow: 0 0 0 4px rgba(44,125,160,0.15);
        }

        .verify-btn {
            background: linear-gradient(105deg, #1e5a7a, #2c7da0);
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 3rem;
            color: white;
            font-weight: 700;
            font-size: 1.05rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 1.8rem;
            transition: 0.2s;
        }

        .verify-btn:hover {
            transform: scale(0.98);
            background: linear-gradient(105deg, #134c67, #236b8a);
        }

        .result-card {
            background: #f9fbfe;
            border-radius: 1.5rem;
            padding: 1.3rem 1.8rem;
            margin-top: 1rem;
            border-left: 6px solid;
            animation: fadeSlide 0.35s ease-out;
        }

        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .result-card.valid {
            border-left-color: #2b8c5e;
            background: linear-gradient(120deg, #eef9f2, #fff);
        }
        .result-card.invalid {
            border-left-color: #c23b22;
            background: linear-gradient(120deg, #fef3f0, #fff);
        }
        .result-card.warning {
            border-left-color: #e6a017;
            background: linear-gradient(120deg, #fff9e8, #fff);
        }

        .result-title {
            font-weight: 800;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cert-details-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
            background: #fff;
            padding: 1rem;
            border-radius: 1rem;
        }

        .detail-item {
            flex: 1;
            min-width: 170px;
        }

        .detail-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #6f8e9e;
        }

        .detail-value {
            font-weight: 600;
            font-size: 0.9rem;
            color: #1e4a6b;
            margin-top: 4px;
        }

        .verification-seal {
            background: #e9f5ef;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        footer {
            font-size: 0.7rem;
            text-align: center;
            padding-top: 1rem;
            color: #6f8e9e;
            border-top: 1px solid #e2edf5;
            margin-top: 1rem;
        }

        .loading-spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid white;
            border-top: 2px solid #2c7da0;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 550px) {
            body { padding: 1rem; }
            .verification-body { padding: 1.5rem; }
            .cert-details-grid { flex-direction: column; }
        }
    </style>
</head>
<body>

<div class="verification-container">
    <div class="verification-header">
        <h1>
            <i class="fas fa-shield-alt"></i>
            Certificate Verification
        </h1>
        <div class="badge"><i class="fas fa-lock"></i> Official Registry · Real-time Validation</div>
        <p style="margin-top: 12px; opacity:0.85;">Enter certificate ID to verify authenticity</p>
    </div>

    <div class="verification-body">
        <div class="input-group">
            <label><i class="fas fa-qrcode"></i> Certificate ID / Serial Number *</label>
            <input type="text" id="certId" class="input-field" placeholder="Enter certificate number to verify" autocomplete="off">
        </div>

        <div class="input-group">
            <label><i class="fas fa-user-check"></i> Holder Name (Optional)</label>
            <input type="text" id="holderName" class="input-field" placeholder="Enter holder's name for additional verification">
        </div>

        <button id="verifyButton" class="verify-btn">
            <i class="fas fa-search"></i> Verify Certificate
        </button>

        <div id="resultPanel"></div>

        <footer>
            <i class="fas fa-database"></i> Secure digital registry · Blockchain anchored · Tamper-proof records
        </footer>
    </div>
</div>

<script>
    async function verifyCertificate(certId, holderName) {
        try {
            const response = await fetch(`/certificate-verification/data?certificate_number=${encodeURIComponent(certId)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json().catch(() => null);

            if (!response.ok || !data || !data.found) {
                return {
                    found: false,
                    status: data?.status || 'not_found',
                    message: data?.message || 'Certificate not found in official registry. Please verify the ID or contact issuing authority.'
                };
            }

            if (holderName && holderName.trim() !== '' && data.data.holder_name) {
                const inputName = holderName.trim().toLowerCase();
                const storedName = data.data.holder_name.trim().toLowerCase();
                if (inputName !== storedName) {
                    return {
                        found: true,
                        status: 'mismatch',
                        message: `Holder name does not match records. Certificate exists for "${data.data.holder_name}".`,
                        data: data.data
                    };
                }
            }

            return data;
        } catch (error) {
            console.error('Certificate verification error:', error);
            return {
                found: false,
                status: 'error',
                message: 'Unable to connect to verification service. Please try again later.'
            };
        }
    }

    function formatDate(dateStr) {
        if (!dateStr) return "Not specified";
        if (dateStr === "Lifetime") return "Lifetime (No Expiry)";
        if (dateStr === "N/A") return "Not Applicable";
        try {
            const d = new Date(dateStr);
            if (isNaN(d.getTime())) return dateStr;
            return d.toLocaleDateString('en-IN', { day: 'numeric', month: 'long', year: 'numeric' });
        } catch(e) {
            return dateStr;
        }
    }

    function escapeHtml(str) {
        if (!str) return "";
        return str.replace(/[&<>]/g, function(m) { 
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    function renderResult(result, certId, holderNameProvided) {
        const panel = document.getElementById("resultPanel");
        panel.innerHTML = "";

        if (!result.found) {
            const card = document.createElement("div");
            card.className = "result-card invalid";
            card.innerHTML = `
                <div class="result-title">
                    <i class="fas fa-times-circle" style="color:#c23b22;"></i>
                    Certificate Not Found
                </div>
                <div style="margin-top: 8px;">${result.message}</div>
                <div style="margin-top: 14px; background:#fff0ed; padding: 12px; border-radius: 16px; font-size:0.85rem;">
                    <i class="fas fa-lightbulb"></i> <strong>Possible reasons:</strong> Incorrect certificate ID, certificate not yet issued, or data pending sync.
                </div>
            `;
            panel.appendChild(card);
            return;
        }

        const data = result.data;
        let cardClass = "result-card";
        let iconHtml = "";
        let statusTitle = "";

        if (result.status === "valid") {
            cardClass += " valid";
            iconHtml = '<i class="fas fa-check-circle" style="color:#2b8c5e;"></i>';
            statusTitle = "✓ VALID CERTIFICATE";
        } else if (result.status === "expired") {
            cardClass += " warning";
            iconHtml = '<i class="fas fa-hourglass-end" style="color:#e6a017;"></i>';
            statusTitle = "⚠️ EXPIRED CERTIFICATE";
        } else if (result.status === "revoked") {
            cardClass += " invalid";
            iconHtml = '<i class="fas fa-ban" style="color:#c23b22;"></i>';
            statusTitle = "❌ REVOKED CERTIFICATE";
        } else if (result.status === "mismatch") {
            cardClass += " invalid";
            iconHtml = '<i class="fas fa-user-slash" style="color:#c23b22;"></i>';
            statusTitle = "⚠️ HOLDER NAME MISMATCH";
        } else {
            cardClass += " warning";
            iconHtml = '<i class="fas fa-question-circle" style="color:#e6a017;"></i>';
            statusTitle = "⚠️ UNCERTAIN STATUS";
        }

        const card = document.createElement("div");
        card.className = cardClass;
        
        let detailsHtml = `
            <div class="result-title">
                ${iconHtml}
                <span>${statusTitle}</span>
            </div>
            <div style="margin-bottom: 8px;">${result.message}</div>
            <div class="cert-details-grid">
                <div class="detail-item">
                    <div class="detail-label">Certificate ID</div>
                    <div class="detail-value"><code>${escapeHtml(certId)}</code></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Holder Name</div>
                    <div class="detail-value">${escapeHtml(data.holder_name)} ${holderNameProvided ? '<span style="font-size:0.65rem; background:#eef2f5; padding:2px 6px; border-radius:20px;">verified</span>' : ''}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Certificate Type</div>
                    <div class="detail-value">${escapeHtml(data.certificate_type || "Standard Certificate")}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Issuance Date</div>
                    <div class="detail-value">${formatDate(data.issue_date)}</div>
                </div>`;

        if (data.expiry_date && data.expiry_date !== 'N/A') {
            detailsHtml += `<div class="detail-item"><div class="detail-label">Expiry / Validity</div><div class="detail-value">${formatDate(data.expiry_date)}</div></div>`;
        }

        if (data.issued_by && data.issued_by.toLowerCase() !== 'no_due') {
            detailsHtml += `<div class="detail-item"><div class="detail-label">Issued By</div><div class="detail-value">${escapeHtml(data.issued_by)}</div></div>`;
        }

        if (data.grade) {
            detailsHtml += `<div class="detail-item"><div class="detail-label">Grade / Honor</div><div class="detail-value">${escapeHtml(data.grade)}</div></div>`;
        }
        if (data.registration_no) {
            detailsHtml += `<div class="detail-item"><div class="detail-label">Registration No.</div><div class="detail-value">${escapeHtml(data.registration_no)}</div></div>`;
        }
        if (data.blockchain_hash) {
            detailsHtml += `<div class="detail-item"><div class="detail-label">Blockchain Hash</div><div class="detail-value"><code style="font-size:0.7rem;">${escapeHtml(data.blockchain_hash)}</code></div></div>`;
        }
        if (result.status === "revoked" && data.revocation_reason) {
            detailsHtml += `<div class="detail-item" style="flex:100%;"><div class="detail-label">Revocation Reason</div><div class="detail-value" style="color:#c23b22;">${escapeHtml(data.revocation_reason)}</div></div>`;
        }
        
        detailsHtml += `</div>
            <div style="margin-top: 12px; font-size:0.7rem; color:#5c7f8f; background:#f0f4f9; padding:8px 12px; border-radius: 20px;">
                <i class="fas fa-shield-alt"></i> Digitally verified by issuing authority • ${new Date().toLocaleString()}
            </div>
        `;
        card.innerHTML = detailsHtml;
        panel.appendChild(card);
    }

    async function performVerification() {
        const certInput = document.getElementById("certId");
        const holderInput = document.getElementById("holderName");
        const verifyBtn = document.getElementById("verifyButton");
        
        const certId = certInput.value.trim().toUpperCase();
        const holderName = holderInput.value.trim();
        
        if (!certId) {
            const panel = document.getElementById("resultPanel");
            panel.innerHTML = `
                <div class="result-card invalid">
                    <div class="result-title"><i class="fas fa-exclamation-triangle"></i> Input Required</div>
                    <div>Please enter a Certificate ID to verify.</div>
                </div>
            `;
            return;
        }
        
        const originalText = verifyBtn.innerHTML;
        verifyBtn.innerHTML = '<span class="loading-spinner"></span> Verifying...';
        verifyBtn.disabled = true;
        
        try {
            const result = await verifyCertificate(certId, holderName);
            renderResult(result, certId, holderName !== "");
        } catch (error) {
            const panel = document.getElementById("resultPanel");
            panel.innerHTML = `
                <div class="result-card invalid">
                    <div class="result-title"><i class="fas fa-bug"></i> Verification Error</div>
                    <div>Unable to connect to verification server. Please try again.</div>
                </div>
            `;
        } finally {
            verifyBtn.innerHTML = originalText;
            verifyBtn.disabled = false;
        }
    }
    
    document.getElementById("verifyButton").addEventListener("click", performVerification);
    document.getElementById("certId").addEventListener("keypress", (e) => {
        if (e.key === "Enter") performVerification();
    });
    document.getElementById("holderName").addEventListener("keypress", (e) => {
        if (e.key === "Enter") performVerification();
    });
    
    window.addEventListener("DOMContentLoaded", () => {
        document.getElementById("certId").focus();
    });
</script>
</body>
</html>
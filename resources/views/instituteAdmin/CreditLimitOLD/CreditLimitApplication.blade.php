@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
  <title>Credit Limit Application - Royal Card</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <style>
    :root {
      --primary: #4e73df;
      --primary-dark: #3c5dc5;
      --secondary: #ffd700;
      --success: #1a7f37;
      --warning: #ffc107;
      --danger: #dc3545;
      --dark: #2d3748;
      --light: #f8f9fa;
      --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --gradient-gold: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
      --gradient-primary: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    body { 
      background: #ffffff !important;
      font-family: 'Poppins', sans-serif; 
      color: #2d3748; 
      margin: 0;
      min-height: 100vh;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-radius: 20px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
    }

    .glass-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(78, 115, 223, 0.15);
    }

    .page-wrapper { 
      max-width: 1200px; 
      margin: 0 auto; 
      padding: 20px;
    }

    .hero-section {
      text-align: center;
      padding: 60px 20px;
      background: var(--gradient-primary);
      color: white;
      position: relative;
      overflow: hidden;
      border-radius: 20px;
      margin-bottom: 40px;
    }

    .hero-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.1);
      z-index: 1;
    }

    .hero-content {
      position: relative;
      z-index: 2;
    }

    .hero-title {
      font-size: 3rem;
      font-weight: 800;
      margin-bottom: 20px;
      text-shadow: 2px 2px 10px rgba(0,0,0,0.2);
    }

    .hero-subtitle {
      font-size: 1.2rem;
      font-weight: 300;
      margin-bottom: 30px;
      opacity: 0.9;
    }

    .feature-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 25px;
      margin: 40px 0;
    }

    .feature-card {
      padding: 30px;
      text-align: center;
      background: white;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.08);
      transition: all 0.3s ease;
      border: 1px solid rgba(78, 115, 223, 0.1);
    }

    .feature-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 40px rgba(78, 115, 223, 0.15);
    }

    .feature-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 20px;
      background: var(--gradient-primary);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      color: white;
    }

    .feature-title {
      font-size: 1.3rem;
      font-weight: 700;
      margin-bottom: 15px;
      color: var(--primary);
    }

    .documents-section {
      margin: 60px 0;
    }

    .section-title {
      font-size: 2.2rem;
      font-weight: 700;
      text-align: center;
      margin-bottom: 40px;
      color: var(--dark);
    }

    /* Two columns layout for documents */
    .documents-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
      gap: 25px;
      margin-bottom: 40px;
    }

    .document-card {
      background: white;
      border-radius: 15px;
      padding: 25px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.08);
      border-left: 5px solid var(--primary);
      transition: all 0.3s ease;
      height: 100%;
    }

    .document-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 30px rgba(78, 115, 223, 0.15);
    }

    .document-header {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
    }

    .doc-icon {
      width: 50px;
      height: 50px;
      background: var(--gradient-primary);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.3rem;
      margin-right: 15px;
    }

    .doc-status {
      margin-left: auto;
      padding: 6px 15px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
    }

    .status-approved { background: #d4edda; color: var(--success); }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-verified { background: #d1ecf1; color: #0c5460; }
    .status-completed { background: #d4edda; color: var(--success); }

    .progress-tracker {
      display: flex;
      justify-content: space-between;
      position: relative;
      margin: 40px 0;
      padding: 0 20px;
    }

    .progress-tracker::before {
      content: '';
      position: absolute;
      top: 25px;
      left: 50px;
      right: 50px;
      height: 4px;
      background: #e2e8f0;
      z-index: 1;
    }

    .progress-step {
      text-align: center;
      position: relative;
      z-index: 2;
      flex: 1;
    }

    .step-circle {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: white;
      border: 3px solid #e2e8f0;
      margin: 0 auto 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #a0aec0;
      font-weight: 700;
      font-size: 1.2rem;
      transition: all 0.3s ease;
    }

    .step-circle.active {
      background: var(--primary);
      border-color: var(--primary);
      color: white;
      transform: scale(1.1);
    }

    .step-label {
      color: var(--dark);
      font-weight: 600;
      font-size: 0.9rem;
    }

    .cta-section {
      text-align: center;
      padding: 40px 20px;
      background: var(--light);
      border-radius: 20px;
      margin-top: 40px;
    }

    .cta-button {
      background: var(--gradient-primary);
      color: white;
      border: none;
      padding: 18px 45px;
      border-radius: 50px;
      font-size: 1.2rem;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 10px 30px rgba(78, 115, 223, 0.3);
      text-decoration: none;
      display: inline-block;
    }

    .cta-button:hover {
      transform: translateY(-3px) scale(1.05);
      box-shadow: 0 15px 40px rgba(78, 115, 223, 0.4);
      color: white;
      text-decoration: none;
    }

    .benefit-tag {
      display: inline-block;
      background: rgba(255, 255, 255, 0.2);
      padding: 8px 20px;
      border-radius: 25px;
      margin: 5px;
      font-size: 0.9rem;
      backdrop-filter: blur(10px);
    }

    .document-preview {
      background: #f8f9fa;
      border-radius: 10px;
      padding: 15px;
      margin-top: 10px;
      border: 2px dashed #dee2e6;
    }

    .preview-item {
      display: flex;
      align-items: center;
      padding: 8px;
      margin: 5px 0;
      background: white;
      border-radius: 8px;
      border: 1px solid #e9ecef;
    }

    .preview-item i {
      color: var(--primary);
      margin-right: 10px;
    }

    .approval-badge {
      background: var(--gradient-gold);
      color: var(--dark);
      padding: 20px;
      border-radius: 15px;
      text-align: center;
      margin: 20px 0;
      font-weight: 700;
      box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
    }

    .application-summary {
      background: white;
      border-radius: 15px;
      padding: 25px;
      margin: 25px 0;
      box-shadow: 0 8px 25px rgba(0,0,0,0.08);
      border: 2px solid rgba(78, 115, 223, 0.1);
    }

    .summary-header {
      color: var(--primary);
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 20px;
      text-align: center;
    }

    .summary-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-top: 15px;
    }

    .summary-item {
      text-align: center;
      padding: 15px;
      background: rgba(78, 115, 223, 0.05);
      border-radius: 10px;
      border: 1px solid rgba(78, 115, 223, 0.1);
    }

    .summary-label {
      font-size: 0.9rem;
      color: #666;
      margin-bottom: 5px;
    }

    .summary-value {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--primary);
    }

    .status-badge {
      display: inline-block;
      padding: 6px 15px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      margin-top: 5px;
    }

    .status-approved-badge {
      background: #d4edda;
      color: var(--success);
    }

    .floating-shapes {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      z-index: 1;
    }

    .shape {
      position: absolute;
      opacity: 0.1;
      animation: float 6s ease-in-out infinite;
      font-size: 2rem;
    }

    .shape:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
    .shape:nth-child(2) { top: 60%; left: 80%; animation-delay: 2s; }
    .shape:nth-child(3) { top: 80%; left: 20%; animation-delay: 4s; }

    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(180deg); }
    }

    @media (max-width: 768px) {
      .hero-title { font-size: 2.2rem; }
      .feature-grid { grid-template-columns: 1fr; }
      .documents-grid { grid-template-columns: 1fr; }
      .progress-tracker { flex-direction: column; gap: 30px; }
      .progress-tracker::before { display: none; }
      .documents-grid { grid-template-columns: 1fr; }
      .summary-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 576px) {
      .documents-grid { grid-template-columns: 1fr; }
    }
  </style>

  <div class="page-wrapper">
    <!-- Hero Section -->
    <section class="hero-section">
      <div class="floating-shapes">
        <div class="shape">💳</div>
        <div class="shape">💰</div>
        <div class="shape">💰</div>
        <div class="shape">📊</div>
      </div>
      <div class="hero-content">
        <h1 class="hero-title animate__animated animate__fadeInDown">
          Welcome to CampusDunia Working <br/>Capital Limit
        </h1>
        <p class="hero-subtitle animate__animated animate__fadeInUp">
          Unlock financial flexibility with your personalized credit limit
        </p>
        
        <div class="animate__animated animate__zoomIn" style="animation-delay: 0.5s;">
          <span class="benefit-tag">🎯 Up to ₹2,00,000 Limit</span>
          <span class="benefit-tag">⚡ Instant Approval</span>
          <span class="benefit-tag">🔒 Secure & Safe</span>
          <span class="benefit-tag">💳 Flexible EMI Options</span>
        </div>
      </div>
    </section>

    <!-- Approval Badge -->
    <div class="approval-badge animate__animated animate__bounceIn">
      <i class="fas fa-check-circle me-2"></i>
      Congratulations! Your Credit Limit Application Has Been Approved
    </div>

    <!-- Application Summary - Moved to top after congratulations -->
    <div class="application-summary animate__animated animate__fadeInUp">
      <div class="summary-header">
        <i class="fas fa-file-alt me-2"></i>
        Application Summary
      </div>
      <div class="summary-grid">
        <div class="summary-item">
          <div class="summary-label">Applicant Name</div>
          <div class="summary-value">Tarun Dhiman</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Application Status</div>
          <div class="summary-value">
            Approved
            <div class="status-badge status-approved-badge">
              <i class="fas fa-check-circle me-1"></i>
              Completed
            </div>
          </div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Credit Limit</div>
          <div class="summary-value">₹2,00,000</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Card Type</div>
          <div class="summary-value">Platinum</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Approved Date</div>
          <div class="summary-value">02-10-2025</div>
        </div>
        <div class="summary-item">
          <div class="summary-label">Expiry Date</div>
          <div class="summary-value">02-10-2029</div>
        </div>
      </div>
    </div>

    <!-- Progress Tracker -->
    <div class="progress-tracker">
      <div class="progress-step">
        <div class="step-circle active">1</div>
        <div class="step-label">Application</div>
      </div>
      <div class="progress-step">
        <div class="step-circle active">2</div>
        <div class="step-label">Document Upload</div>
      </div>
      <div class="progress-step">
        <div class="step-circle active">3</div>
        <div class="step-label">Verification</div>
      </div>
      <div class="progress-step">
        <div class="step-circle">4</div>
        <div class="step-label">Approval</div>
      </div>
      <div class="progress-step">
        <div class="step-circle">5</div>
        <div class="step-label">Credit Active</div>
      </div>
    </div>

    <!-- Features Grid -->
    <div class="feature-grid">
      <div class="feature-card animate__animated animate__fadeInLeft">
        <div class="feature-icon">
          <i class="fas fa-bolt"></i>
        </div>
        <h3 class="feature-title">Quick Access</h3>
        <p>Get instant access to credit when you need it most with our streamlined approval process</p>
      </div>
      <div class="feature-card animate__animated animate__fadeInUp">
        <div class="feature-icon">
          <i class="fas fa-shield-alt"></i>
        </div>
        <h3 class="feature-title">Secure & Protected</h3>
        <p>Bank-level security with advanced encryption to keep your financial data safe</p>
      </div>

      <div class="feature-card animate__animated animate__fadeInRight">
        <div class="feature-icon">
          <i class="fas fa-chart-line"></i>
        </div>
        <h3 class="feature-title">Flexible Limits</h3>
        <p>Credit limits that grow with you, starting from ₹50,000 up to ₹2,00,000</p>
      </div>
    </div>

    <!-- Documents Section -->
    <section class="documents-section">
      <h2 class="section-title animate__animated animate__fadeIn">Uploaded Documents</h2>
      
      <!-- Two columns grid for documents -->
      <div class="documents-grid">
        <!-- Row 1 -->
        <div class="document-card animate__animated animate__fadeInLeft">
          <div class="document-header">
            <div class="doc-icon">
              <i class="fas fa-id-card"></i>
            </div>
            <div>
              <h4 style="margin: 0; color: var(--primary);">PAN Card</h4>
              <p style="margin: 0; font-size: 0.9rem; color: #666;">Primary Identity Proof</p>
            </div>
            <span class="doc-status status-verified">Verified</span>
          </div>
          <div class="document-preview">
            <div class="preview-item">
              <i class="fas fa-file-pdf"></i>
              <span>pan_card_tarun_dhiman.pdf</span>
            </div>
          </div>
        </div>

        <div class="document-card animate__animated animate__fadeInRight">
          <div class="document-header">
            <div class="doc-icon">
              <i class="fas fa-address-card"></i>
            </div>
            <div>
              <h4 style="margin: 0; color: var(--primary);">Aadhaar Card</h4>
              <p style="margin: 0; font-size: 0.9rem; color: #666;">Address & Identity Proof</p>
            </div>
            <span class="doc-status status-verified">Verified</span>
          </div>
          <div class="document-preview">
            <div class="preview-item">
              <i class="fas fa-file-image"></i>
              <span>aadhaar_front.jpg</span>
            </div>
            <div class="preview-item">
              <i class="fas fa-file-image"></i>
              <span>aadhaar_back.jpg</span>
            </div>
          </div>
        </div>

        <!-- Row 2 -->
        <div class="document-card animate__animated animate__fadeInLeft">
          <div class="document-header">
            <div class="doc-icon">
              <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div>
              <h4 style="margin: 0; color: var(--primary);">Bank Statements</h4>
              <p style="margin: 0; font-size: 0.9rem; color: #666;">Last 6 Months</p>
            </div>
            <span class="doc-status status-approved">Approved</span>
          </div>
          <div class="document-preview">
            <div class="preview-item">
              <i class="fas fa-file-pdf"></i>
              <span>bank_statement_6months.pdf</span>
            </div>
          </div>
        </div>

        <div class="document-card animate__animated animate_fadeInRight">
          <div class="document-header">
            <div class="doc-icon">
              <i class="fas fa-receipt"></i>
            </div>
            <div>
              <h4 style="margin: 0; color: var(--primary);">Income Proof</h4>
              <p style="margin: 0; font-size: 0.9rem; color: #666;">Salary Slips/ITR</p>
            </div>
            <span class="doc-status status-approved">Approved</span>
          </div>
          <div class="document-preview">
            <div class="preview-item">
              <i class="fas fa-file-pdf"></i>
              <span>salary_slips_q2.pdf</span>
            </div>
            <div class="preview-item">
              <i class="fas fa-file-pdf"></i>
              <span>itr_2024.pdf</span>
            </div>
          </div>
        </div>

        <!-- Row 3 -->
        <div class="document-card animate__animated animate__fadeInLeft">
          <div class="document-header">
            <div class="doc-icon">
              <i class="fas fa-university"></i>
            </div>
            <div>
              <h4 style="margin: 0; color: var(--primary);">Institute Registeration</h4>
              <p style="margin: 0; font-size: 0.9rem; color: #666;">Business Proof</p>
            </div>
            <span class="doc-status status-completed">Completed</span>
          </div>
          <div class="document-preview">
            <div class="preview-item">
              <i class="fas fa-file-contract"></i>
              <span>institute_certificate.pdf</span>
            </div>
          </div>
        </div>

        <div class="document-card animate__animated animate__fadeInRight">
          <div class="document-header">
            <div class="doc-icon">
              <i class="fas fa-photo-video"></i>
            </div>
            <div>
              <h4 style="margin: 0; color: var(--primary);">Additional Documents</h4>
              <p style="margin: 0; font-size: 0.9rem; color: #666;">Supporting Files</p>
            </div>
            <span class="doc-status status-completed">Completed</span>
          </div>
          <div class="document-preview">
            <div class="preview-item">
              <i class="fas fa-file-image"></i>
              <span>business_premises.jpg</span>
            </div>
            <div class="preview-item">
              <i class="fas fa-file-pdf"></i>
              <span>additional_proof.pdf</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
      <h2 style="color: var(--primary); margin-bottom: 20px; font-weight: 700;">
        Ready to Access Your Credit Limit?
      </h2>
      <p style="font-size: 1.1rem; margin-bottom: 30px; color: #666;">
        Your documents have been successfully verified. You're approved for a credit limit of <strong>₹2,00,000</strong>.<br>
        Click below to start using your credit facility immediately.
      </p>
      <a href="{{ url('/institute/admin/signAgreement') }}" class="cta-button animate_animated animate__pulse animate__infinite">
        <i class="fas fa-arrow-right me-2"></i>
        Proceed to Sign Agreement
      </a>
    </section>
  </div>

  <script>
    // Add scroll animations
    document.addEventListener('DOMContentLoaded', function() {
      const animatedElements = document.querySelectorAll('.animate__animated');
      
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const animation = entry.target.getAttribute('data-animation');
            if (animation) {
              entry.target.classList.add(animation);
            }
          }
        });
      }, { threshold: 0.1 });

      animatedElements.forEach(element => {
        observer.observe(element);
      });

      // Add floating animation to features on hover
      document.querySelectorAll('.feature-card, .document-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
          this.style.transform = 'translateY(-10px)';
        });
        
        card.addEventListener('mouseleave', function() {
          this.style.transform = 'translateY(0)';
        });
      });
    });
  </script>
@endsection
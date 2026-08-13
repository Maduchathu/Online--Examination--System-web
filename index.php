<?php
$pageTitle = 'Home';
require 'partials/header.php';
?>

<section class="hero text-white">
  <div class="container py-5">
    <div class="row align-items-center py-lg-5">
      <div class="col-lg-7">
        <span class="badge bg-primary-subtle text-primary mb-3">ONLINE EXAMINATION MANAGEMENT SYSTEM</span>
        <h1 class="display-4 fw-bold">Conduct exams anywhere in Sri Lanka.</h1>
        <p class="lead text-white-50 mt-3">Register candidates, schedule examinations, answer questions online and generate results automatically.</p>
        <div class="d-flex gap-2 mt-4 flex-wrap">
          <a href="register.php" class="btn btn-primary btn-lg">Get Started</a>
          <a href="exams.php" class="btn btn-outline-light btn-lg">View Exams</a>
        </div>
      </div>
      <div class="col-lg-5 mt-5 mt-lg-0">
        <div class="hero-card rounded-4 p-4 shadow-lg">
          <div class="d-flex justify-content-between mb-4">
            <span>Exam Dashboard</span><span class="badge bg-success">Live</span>
          </div>
          <div class="bg-white text-dark rounded-3 p-3 mb-3">
            <div class="small text-secondary">Upcoming examination</div>
            <strong>Diploma ICT Mock Examination</strong>
            <div class="small mt-2">20 Questions • 30 Minutes</div>
          </div>
          <div class="row g-2">
            <div class="col-4"><div class="bg-white text-dark rounded-3 p-3 text-center"><strong>24/7</strong><small class="d-block text-secondary">Access</small></div></div>
            <div class="col-4"><div class="bg-white text-dark rounded-3 p-3 text-center"><strong>Auto</strong><small class="d-block text-secondary">Marking</small></div></div>
            <div class="col-4"><div class="bg-white text-dark rounded-3 p-3 text-center"><strong>Fast</strong><small class="d-block text-secondary">Results</small></div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="container py-5">
  <div class="text-center mb-5">
    <h2 class="fw-bold">Why this system?</h2>
    <p class="text-secondary">Designed around the project objectives and expected outcomes.</p>
  </div>
  <div class="row g-4">
    <?php
    $features = [
      ['Secure','Authentication, controlled access and protected candidate records.'],
      ['Auto Evaluation','Objective questions can be marked automatically and results generated instantly.'],
      ['Accessible','Candidates can participate without travelling to a physical campus.'],
      ['Low Paper Usage','Digital examinations reduce paper-based processes and waste.'],
      ['Reliable','Auto-save and reconnection features can reduce progress loss.'],
      ['Scalable','The architecture can be extended for large examination sessions.']
    ];
    foreach ($features as [$title,$desc]): ?>
      <div class="col-md-6 col-lg-4">
        <div class="feature-card bg-white rounded-4 p-4 h-100">
          <div class="fs-2 mb-3">◈</div>
          <h5><?= e($title) ?></h5>
          <p class="text-secondary mb-0"><?= e($desc) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Contact Us Section -->
<section id="contact" class="bg-light py-5 border-top">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="fw-bold">Contact Us</h2>
      <p class="text-secondary">Get in touch with us for more information.</p>
    </div>
    <div class="row justify-content-center">
      <div class="col-md-6 text-center">
        <div class="bg-white p-4 rounded-4 shadow-sm">
          <p class="mb-2"><strong>Name:</strong> H.K.C. Madushani</p>
          <p class="mb-2"><strong>Location:</strong> Embilipitiya</p>
          <p class="mb-2"><strong>Phone:</strong> <a href="tel:0704655268" class="text-decoration-none">0704655268</a></p>
          <p class="mb-0"><strong>Email:</strong> <a href="mailto:hkcmadu@gmail.com" class="text-decoration-none">hkcmadu@gmail.com</a></p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require 'partials/footer.php'; ?>
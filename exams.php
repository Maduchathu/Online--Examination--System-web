<?php
require 'config/auth.php'; requireLogin();
$pageTitle='Examinations';
$exams=$pdo->query("SELECT e.*, o.OrganizationName FROM exams e LEFT JOIN organizations o ON o.OrganizationID=e.OrganizationID ORDER BY e.Date DESC")->fetchAll();
require 'partials/header.php';
?>
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div><h2 class="fw-bold">Available Examinations</h2><p class="text-secondary">Register for an examination and start when it is available.</p></div>
  </div>
  <div class="row g-4">
  <?php foreach($exams as $exam): ?>
    <div class="col-md-6 col-lg-4">
      <div class="card border-0 shadow-sm rounded-4 h-100">
        <div class="card-body p-4">
          <span class="badge text-bg-primary mb-3"><?= e($exam['ExamType']) ?></span>
          <h5 class="fw-bold"><?= e($exam['ExamTitle']) ?></h5>
          <p class="text-secondary small"><?= e($exam['OrganizationName'] ?? 'Organization') ?></p>
          <div class="small mb-2">📅 <?= e($exam['Date']) ?></div>
          <div class="small mb-2">⏱ <?= (int)$exam['Duration'] ?> minutes</div>
          <div class="small mb-3">📝 <?= (int)$exam['TotalMarks'] ?> marks</div>
          <a href="take_exam.php?id=<?= (int)$exam['ExamID'] ?>" class="btn btn-primary w-100">Take / Continue Exam</a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  </div>
</div>
<?php require 'partials/footer.php'; ?>
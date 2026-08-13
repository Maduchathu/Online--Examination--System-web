<?php
require 'config/auth.php'; requireLogin();
$id=(int)($_GET['id']??0);
$stmt=$pdo->prepare("SELECT r.*, e.ExamTitle,e.TotalMarks,c.FullName FROM results r JOIN exams e ON e.ExamID=r.ExamID JOIN candidates c ON c.CandidateID=r.CandidateID WHERE r.ResultID=?");
$stmt->execute([$id]); $result=$stmt->fetch();
if(!$result || ($_SESSION['user']['role']!=='admin' && $result['CandidateID']!=$_SESSION['user']['id'])) exit('Result not found.');
$pageTitle='Result';
require 'partials/header.php';
?>
<div class="container py-5" style="max-width:700px">
  <div class="bg-white rounded-4 shadow-sm p-5 text-center">
    <div class="display-4 mb-3">✓</div>
    <h2 class="fw-bold">Examination Result</h2>
    <p class="text-secondary"><?= e($result['ExamTitle']) ?></p>
    <div class="row g-3 my-4">
      <div class="col-6"><div class="bg-light rounded-3 p-4"><div class="text-secondary">Marks</div><div class="display-6 fw-bold"><?= (int)$result['MarksObtained'] ?>/<?= (int)$result['TotalMarks'] ?></div></div></div>
      <div class="col-6"><div class="bg-light rounded-3 p-4"><div class="text-secondary">Grade</div><div class="display-6 fw-bold"><?= e($result['Grade']) ?></div></div></div>
    </div>
    <span class="badge text-bg-success px-3 py-2"><?= e($result['Status']) ?></span>
    <div class="mt-4"><a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a></div>
  </div>
</div>
<?php require 'partials/footer.php'; ?>
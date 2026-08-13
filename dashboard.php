<?php
require 'config/auth.php'; requireLogin();
$pageTitle='Dashboard';
$u=$_SESSION['user'];
if ($u['role']==='candidate') {
    $stmt=$pdo->prepare("SELECT COUNT(*) FROM applications WHERE CandidateID = ?");
}
require 'partials/header.php';
?>
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div><h2 class="fw-bold mb-1">Welcome, <?= e($u['name']) ?></h2><p class="text-secondary mb-0"><?= ucfirst($u['role']) ?> dashboard</p></div>
    <a href="exams.php" class="btn btn-primary">Browse Exams</a>
  </div>
  <?php if($u['role']==='candidate'): ?>
    <?php
    $stmt=$pdo->prepare("SELECT COUNT(*) FROM applications WHERE CandidateID=?"); $stmt->execute([$u['id']]); $applications=(int)$stmt->fetchColumn();
    $stmt=$pdo->prepare("SELECT COUNT(*) FROM results WHERE CandidateID=?"); $stmt->execute([$u['id']]); $results=(int)$stmt->fetchColumn();
    ?>
    <div class="row g-4 mb-4">
      <div class="col-md-4"><div class="card stat-card rounded-4"><div class="card-body p-4"><div class="text-secondary">Applications</div><div class="display-6 fw-bold"><?= $applications ?></div></div></div></div>
      <div class="col-md-4"><div class="card stat-card rounded-4"><div class="card-body p-4"><div class="text-secondary">Results</div><div class="display-6 fw-bold"><?= $results ?></div></div></div></div>
      <div class="col-md-4"><div class="card stat-card rounded-4"><div class="card-body p-4"><div class="text-secondary">Access</div><div class="display-6 fw-bold">24/7</div></div></div></div>
    </div>
    <div class="bg-white rounded-4 shadow-sm p-4">
      <h5>Candidate workflow</h5>
      <div class="row g-3 mt-1">
        <div class="col-md-3"><div class="p-3 bg-light rounded-3"><strong>1.</strong> Register</div></div>
        <div class="col-md-3"><div class="p-3 bg-light rounded-3"><strong>2.</strong> Apply for exam</div></div>
        <div class="col-md-3"><div class="p-3 bg-light rounded-3"><strong>3.</strong> Take exam</div></div>
        <div class="col-md-3"><div class="p-3 bg-light rounded-3"><strong>4.</strong> View result</div></div>
      </div>
    </div>
  <?php else: ?>
    <?php
    $c=(int)$pdo->query("SELECT COUNT(*) FROM candidates")->fetchColumn();
    $ex=(int)$pdo->query("SELECT COUNT(*) FROM exams")->fetchColumn();
    $re=(int)$pdo->query("SELECT COUNT(*) FROM results")->fetchColumn();
    ?>
    <div class="row g-4">
      <div class="col-md-4"><div class="card stat-card rounded-4"><div class="card-body p-4"><div class="text-secondary">Candidates</div><div class="display-6 fw-bold"><?= $c ?></div></div></div></div>
      <div class="col-md-4"><div class="card stat-card rounded-4"><div class="card-body p-4"><div class="text-secondary">Exams</div><div class="display-6 fw-bold"><?= $ex ?></div></div></div></div>
      <div class="col-md-4"><div class="card stat-card rounded-4"><div class="card-body p-4"><div class="text-secondary">Results</div><div class="display-6 fw-bold"><?= $re ?></div></div></div></div>
    </div>
    <div class="mt-4"><a class="btn btn-dark" href="admin.php">Open Admin Panel</a></div>
  <?php endif; ?>
</div>
<?php require 'partials/footer.php'; ?>
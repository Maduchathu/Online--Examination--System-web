<?php
require 'config/auth.php'; requireAdmin();
$message='';
if(isset($_POST['action']) && $_POST['action']==='add_exam'){
    $title=trim($_POST['title']); $type=trim($_POST['type']); $date=$_POST['date']; $duration=(int)$_POST['duration']; $marks=(int)$_POST['marks'];
    $org=(int)$_POST['organization'];
    $stmt=$pdo->prepare("INSERT INTO exams (ExamTitle,ExamType,Date,Duration,TotalMarks,OrganizationID) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$title,$type,$date,$duration,$marks,$org]); $message='Exam created successfully.';
}
if(isset($_POST['action']) && $_POST['action']==='add_question'){
    $stmt=$pdo->prepare("INSERT INTO questions (ExamID,QuestionText,OptionA,OptionB,OptionC,OptionD,CorrectAnswer,Marks) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->execute([(int)$_POST['exam_id'],trim($_POST['question']),trim($_POST['a']),trim($_POST['b']),trim($_POST['c']),trim($_POST['d']),$_POST['correct'],(int)$_POST['qmarks']]);
    $message='Question added successfully.';
}
$orgs=$pdo->query("SELECT * FROM organizations ORDER BY OrganizationName")->fetchAll();
$exams=$pdo->query("SELECT e.*,o.OrganizationName,(SELECT COUNT(*) FROM questions q WHERE q.ExamID=e.ExamID) QuestionCount FROM exams e LEFT JOIN organizations o ON o.OrganizationID=e.OrganizationID ORDER BY e.ExamID DESC")->fetchAll();
$results=$pdo->query("SELECT r.*,c.FullName,e.ExamTitle FROM results r JOIN candidates c ON c.CandidateID=r.CandidateID JOIN exams e ON e.ExamID=r.ExamID ORDER BY r.ResultID DESC LIMIT 20")->fetchAll();
$pageTitle='Admin Panel';
require 'partials/header.php';
?>
<div class="container py-5">
  <h2 class="fw-bold">Administrator Panel</h2>
  <p class="text-secondary">Manage exams, questions and review candidate results.</p>
  <?php if($message): ?><div class="alert alert-success"><?= e($message) ?></div><?php endif; ?>

  <div class="row g-4">
    <div class="col-lg-5">
      <div class="bg-white rounded-4 shadow-sm p-4">
        <h5>Create Examination</h5>
        <form method="post" class="row g-2">
          <input type="hidden" name="action" value="add_exam">
          <div class="col-12"><input name="title" class="form-control" placeholder="Exam title" required></div>
          <div class="col-md-6"><input name="type" class="form-control" placeholder="Exam type" value="Competitive" required></div>
          <div class="col-md-6"><select name="organization" class="form-select"><?php foreach($orgs as $o): ?><option value="<?= $o['OrganizationID'] ?>"><?= e($o['OrganizationName']) ?></option><?php endforeach; ?></select></div>
          <div class="col-md-6"><input type="datetime-local" name="date" class="form-control" required></div>
          <div class="col-md-3"><input type="number" name="duration" class="form-control" placeholder="Minutes" value="30" required></div>
          <div class="col-md-3"><input type="number" name="marks" class="form-control" placeholder="Marks" value="10" required></div>
          <div class="col-12"><button class="btn btn-primary">Create Exam</button></div>
        </form>
      </div>

      <div class="bg-white rounded-4 shadow-sm p-4 mt-4">
        <h5>Add Question</h5>
        <form method="post" class="row g-2">
          <input type="hidden" name="action" value="add_question">
          <div class="col-12"><select name="exam_id" class="form-select"><?php foreach($exams as $e): ?><option value="<?= $e['ExamID'] ?>"><?= e($e['ExamTitle']) ?></option><?php endforeach; ?></select></div>
          <div class="col-12"><textarea name="question" class="form-control" placeholder="Question" required></textarea></div>
          <div class="col-md-6"><input name="a" class="form-control" placeholder="Option A" required></div>
          <div class="col-md-6"><input name="b" class="form-control" placeholder="Option B" required></div>
          <div class="col-md-6"><input name="c" class="form-control" placeholder="Option C" required></div>
          <div class="col-md-6"><input name="d" class="form-control" placeholder="Option D" required></div>
          <div class="col-md-6"><select name="correct" class="form-select"><option>A</option><option>B</option><option>C</option><option>D</option></select></div>
          <div class="col-md-6"><input type="number" name="qmarks" class="form-control" value="1" min="1"></div>
          <div class="col-12"><button class="btn btn-dark">Add Question</button></div>
        </form>
      </div>
    </div>

    <div class="col-lg-7">
      <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
        <h5>Examinations</h5>
        <div class="table-responsive"><table class="table align-middle">
          <thead><tr><th>Exam</th><th>Date</th><th>Questions</th><th>Marks</th></tr></thead>
          <tbody><?php foreach($exams as $e): ?><tr><td><strong><?= e($e['ExamTitle']) ?></strong><div class="small text-secondary"><?= e($e['OrganizationName']??'') ?></div></td><td><?= e($e['Date']) ?></td><td><?= $e['QuestionCount'] ?></td><td><?= $e['TotalMarks'] ?></td></tr><?php endforeach; ?></tbody>
        </table></div>
      </div>
      <div class="bg-white rounded-4 shadow-sm p-4">
        <h5>Recent Results</h5>
        <div class="table-responsive"><table class="table align-middle">
          <thead><tr><th>Candidate</th><th>Exam</th><th>Marks</th><th>Grade</th></tr></thead>
          <tbody><?php foreach($results as $r): ?><tr><td><?= e($r['FullName']) ?></td><td><?= e($r['ExamTitle']) ?></td><td><?= $r['MarksObtained'] ?></td><td><span class="badge text-bg-primary"><?= e($r['Grade']) ?></span></td></tr><?php endforeach; ?></tbody>
        </table></div>
      </div>
    </div>
  </div>
</div>
<?php require 'partials/footer.php'; ?>
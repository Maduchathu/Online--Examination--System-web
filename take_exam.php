<?php
require 'config/auth.php'; requireLogin();
if ($_SESSION['user']['role']!=='candidate') exit('Only candidates can take exams.');
$examId=(int)($_GET['id']??0);
$stmt=$pdo->prepare("SELECT e.*, o.OrganizationName FROM exams e LEFT JOIN organizations o ON o.OrganizationID=e.OrganizationID WHERE e.ExamID=?");
$stmt->execute([$examId]); $exam=$stmt->fetch();
if(!$exam) exit('Exam not found.');
$q=$pdo->prepare("SELECT * FROM questions WHERE ExamID=? ORDER BY QuestionID"); $q->execute([$examId]); $questions=$q->fetchAll();

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $answers=$_POST['answer']??[];
    $score=0; $count=count($questions);
    foreach($questions as $question){
        $selected=$answers[$question['QuestionID']]??'';
        if($selected===$question['CorrectAnswer']) $score += (int)$question['Marks'];
    }
    $total=(int)$exam['TotalMarks'];
    if($total<=0){ foreach($questions as $question) $total += (int)$question['Marks']; }
    $grade=$total>0 ? (($score/$total)*100) : 0;
    $gradeLabel=$grade>=75?'A':($grade>=65?'B':($grade>=55?'C':($grade>=40?'S':'F')));
    $stmt=$pdo->prepare("INSERT INTO results (CandidateID,ExamID,MarksObtained,Grade,Status) VALUES (?,?,?,?,?)");
    $stmt->execute([$_SESSION['user']['id'],$examId,$score,$gradeLabel,'Completed']);
    header("Location: result.php?id=".$pdo->lastInsertId()); exit;
}
$pageTitle='Take Exam';
require 'partials/header.php';
?>
<div class="container py-4" style="max-width:900px">
  <div class="bg-white rounded-4 shadow-sm p-4 mb-4 sticky-top" style="top:70px;z-index:10">
    <div class="d-flex justify-content-between align-items-center">
      <div><h4 class="fw-bold mb-1"><?= e($exam['ExamTitle']) ?></h4><small class="text-secondary"><?= count($questions) ?> questions • <?= (int)$exam['Duration'] ?> minutes</small></div>
      <div class="text-end"><div class="small text-secondary">Time remaining</div><div id="timer" class="fs-3 fw-bold text-primary exam-timer">--:--</div></div>
    </div>
  </div>
  <form method="post" id="examForm">
    <?php foreach($questions as $i=>$question): ?>
      <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
        <div class="d-flex justify-content-between"><h5 class="fw-semibold">Question <?= $i+1 ?></h5><span class="badge text-bg-light"><?= (int)$question['Marks'] ?> mark(s)</span></div>
        <p class="fs-5 mt-3"><?= e($question['QuestionText']) ?></p>
        <?php foreach(['A','B','C','D'] as $opt): $val=$question['Option'.$opt]; ?>
          <div class="mb-2">
            <input class="d-none option-input" type="radio" id="q<?= $question['QuestionID'].$opt ?>" name="answer[<?= (int)$question['QuestionID'] ?>]" value="<?= $opt ?>">
            <label class="option-label" for="q<?= $question['QuestionID'].$opt ?>"><strong><?= $opt ?>.</strong> <?= e($val) ?></label>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
    <button class="btn btn-success btn-lg w-100" onclick="return confirm('Submit your examination now?')">Submit Examination</button>
  </form>
</div>
<script>
let seconds = <?= (int)$exam['Duration'] * 60 ?>;
const timer=document.getElementById('timer');
const form=document.getElementById('examForm');
function tick(){
  const m=Math.floor(seconds/60), s=seconds%60;
  timer.textContent=String(m).padStart(2,'0')+':'+String(s).padStart(2,'0');
  if(seconds<=0){ form.submit(); return; }
  seconds--;
}
tick(); setInterval(tick,1000);
</script>
<?php require 'partials/footer.php'; ?>
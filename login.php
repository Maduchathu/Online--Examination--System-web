<?php
require 'config/database.php';
if (!empty($_SESSION['user'])) { header('Location: dashboard.php'); exit; }
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $username=trim($_POST['username']??'');
    $password=$_POST['password']??'';
    $role=$_POST['role']??'candidate';
    if ($role==='admin') {
        $stmt=$pdo->prepare("SELECT AdminID id, Name name, Username username, Email email, 'admin' role FROM admins WHERE Username=?");
    } else {
        $stmt=$pdo->prepare("SELECT CandidateID id, FullName name, Username username, Email email, 'candidate' role FROM candidates WHERE Username=?");
    }
    $stmt->execute([$username]);
    $user=$stmt->fetch();
    $table = $role==='admin' ? 'admins' : 'candidates';
    if ($user) {
        $p=$pdo->prepare("SELECT Password FROM {$table} WHERE Username=?");
        $p->execute([$username]);
        $hash=$p->fetchColumn();
    }
    if ($user && password_verify($password,$hash)) {
        $_SESSION['user']=$user;
        header('Location: dashboard.php'); exit;
    }
    $error='Invalid username, password or account type.';
}
$pageTitle='Login';
require 'partials/header.php';
?>
<div class="container py-5" style="max-width:500px">
  <div class="bg-white rounded-4 shadow-sm p-4 p-md-5">
    <h2 class="fw-bold">Sign in</h2>
    <p class="text-secondary">Access your examination dashboard.</p>
    <?php if(isset($_GET['registered'])): ?><div class="alert alert-success">Registration successful. You can now log in.</div><?php endif; ?>
    <?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
      <label class="form-label">Account Type</label>
      <select name="role" class="form-select mb-3"><option value="candidate">Candidate</option><option value="admin">Administrator</option></select>
      <label class="form-label">Username</label><input name="username" class="form-control mb-3" required>
      <label class="form-label">Password</label><input type="password" name="password" class="form-control mb-3" required>
      <button class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</div>
<?php require 'partials/footer.php'; ?>
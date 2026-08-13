<?php
require 'config/database.php';
if (!empty($_SESSION['user'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full_name'] ?? '');
    $nic = trim($_POST['nic'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $dob = $_POST['date_of_birth'] ?: null;
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$name || !$email || !$username || strlen($password) < 6) {
        $error = 'Please complete the required fields. Password must be at least 6 characters.';
    } else {
        $check = $pdo->prepare("SELECT CandidateID FROM candidates WHERE Email=? OR Username=?");
        $check->execute([$email,$username]);
        if ($check->fetch()) {
            $error = 'Email or username already exists.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO candidates (FullName,NIC,Email,Phone,Address,DateOfBirth,Username,Password) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->execute([$name,$nic,$email,$phone,$address,$dob,$username,password_hash($password,PASSWORD_DEFAULT)]);
            header('Location: login.php?registered=1'); exit;
        }
    }
}
$pageTitle='Candidate Registration';
require 'partials/header.php';
?>
<div class="container py-5" style="max-width:850px">
  <div class="bg-white rounded-4 shadow-sm p-4 p-md-5">
    <h2 class="fw-bold">Candidate Registration</h2>
    <p class="text-secondary">Create your account to apply for and take online examinations.</p>
    <?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="row g-3">
      <div class="col-md-6"><label class="form-label">Full Name *</label><input name="full_name" class="form-control" required></div>
      <div class="col-md-6"><label class="form-label">NIC</label><input name="nic" class="form-control"></div>
      <div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required></div>
      <div class="col-md-6"><label class="form-label">Phone</label><input name="phone" class="form-control"></div>
      <div class="col-12"><label class="form-label">Address</label><input name="address" class="form-control"></div>
      <div class="col-md-6"><label class="form-label">Date of Birth</label><input type="date" name="date_of_birth" class="form-control"></div>
      <div class="col-md-6"><label class="form-label">Username *</label><input name="username" class="form-control" required></div>
      <div class="col-md-6"><label class="form-label">Password *</label><input type="password" name="password" class="form-control" minlength="6" required></div>
      <div class="col-12"><button class="btn btn-primary px-4">Create Account</button> <a href="login.php" class="btn btn-light">Already have an account?</a></div>
    </form>
  </div>
</div>
<?php require 'partials/footer.php'; ?>
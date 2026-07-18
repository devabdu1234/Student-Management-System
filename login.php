<?php
/* login.php — User authentication page with CSRF protection and role-based profile mapping */
session_start();
include_once 'includes/config.php';
$message = '';
if (isset($_POST['submit'])) {
  // Verify CSRF token
  if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
    $message = '<div class="alert alert-danger">Invalid request.</div>';
  } else {
    // Input sanitization
    $email = trim($_POST['email'] ?? '');
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
      try {
        // Fetch user by email using prepared statement
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
          // Verify password against bcrypt hash
          if (password_verify($password, $user['password'])) {
            $role = $user['role'];
            $_SESSION['role'] = $role;
            $_SESSION['user_email'] = $email;

            // Map role to profile table for name/display
            $role_table = $role == 'Admin' || $role == 'Lecturer' ? 'teacher' : strtolower($role);
            $stmt2 = $pdo->prepare("SELECT * FROM `$role_table` WHERE `email` = ?");
            $stmt2->execute([$email]);
            $profile = $stmt2->fetch();

            if ($profile) {
              $_SESSION['user'] = ($profile['fname'] ?? '') . ' ' . ($profile['lname'] ?? '');
              if ($role == 'Student') $_SESSION['uid'] = $profile['sid'] ?? '';
              elseif ($role == 'Parent') $_SESSION['uid'] = $profile['pid'] ?? '';
              elseif ($role == 'Admin' || $role == 'Lecturer') $_SESSION['uid'] = $profile['tid'] ?? '';
            } else {
              $_SESSION['user'] = $email;
            }
            // Generate fresh CSRF token and regenerate session for fixation protection
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            session_regenerate_id(true);
            header("Location: dashboard.php");
            exit;
          }
        }
        $message = '<div class="alert alert-danger">Incorrect email or password</div>';
      } catch (Exception $e) {
        $message = '<div class="alert alert-danger">System error. Please try again.</div>';
      }
    } else {
      $message = '<div class="alert alert-danger">Please enter email and password</div>';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Login - Student Management System</title>
  <link rel="icon" href="images/user.png">
  <link href="assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="assets/css/custom.css?t=<?= time() ?>" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="login-page">
  <div class="login-card slide-up">
    <div class="login-header">
      <div class="login-icon">
        <i class="fa fa-institution"></i>
      </div>
      <h2>Welcome Back</h2>
      <p>Sign in to your Student Management portal</p>
    </div>
    <div class="login-body">
      <?= $message ?>
      <form method="post" class="needs-validation" novalidate>
<input type="hidden" name="csrf_token" value="<?=csrf_token()?>">
        <div class="form-group">
          <label for="loginEmail">Email Address <span class="required">*</span></label>
          <input name="email" type="email" id="loginEmail" class="form-control" placeholder="you@studentmanagement.ac.lk" required autocomplete="email">
          <div class="form-error"></div>
        </div>
        <div class="form-group">
          <label for="loginPassword">Password <span class="required">*</span></label>
          <input name="password" type="password" id="loginPassword" class="form-control" placeholder="Enter your password" required autocomplete="current-password">
          <div class="form-error"></div>
        </div>
        <button name="submit" value="submit" type="submit" class="btn btn-primary btn-lg btn-block mt-3">
          <i class="fa fa-sign-in"></i> Sign In
        </button>
      </form>
    </div>
    <div class="login-footer">
      <p>Student Management System &copy; <?= date('Y') ?></p>
      <p style="font-size:11px;margin-top:4px;color:var(--text-muted">
        admin@Student Management.ac.lk / 1234 (Admin) &bull; lecturer@Student Management.ac.lk / 1234 (Lecturer) &bull; kasun@Student Management.ac.lk / 1234 (Student)
      </p>
    </div>
  </div>
  <script src="assets/js/custom.js?t=<?= time() ?>"></script>
</body>
</html>

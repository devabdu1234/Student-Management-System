<?php session_start(); include_once 'config.php'; $message='';
if(isset($_POST['submit'])){
  // Input sanitization
  $fname=sanitize($_POST['fname']??'');$lname=sanitize($_POST['lname']??'');
  $email=filter_var(trim($_POST['email']??''),FILTER_SANITIZE_EMAIL);
  $password=password_hash($_POST['password'],PASSWORD_DEFAULT);$role=sanitize($_POST['role']??'');
  try{
    // Register in both role table and user table
    if($role=='Student'){db_query("INSERT INTO student(sid,fname,lname,email) VALUES(?,?,?,?)",[$email,$fname,$lname,$email]);}
    elseif($role=='Parent'){db_query("INSERT INTO parent(fname,lname,email,nic) VALUES(?,?,?,?)",[$fname,$lname,$email,'']);}
    elseif($role=='Lecturer'){db_query("INSERT INTO teacher(tid,fname,lname,email) VALUES(?,?,?,?)",[$email,$fname,$lname,$email]);}
    db_query("INSERT INTO users(email,password,role) VALUES(?,?,?)",[$email,$password,$role]);
    $message='<div class="alert alert-success"><i class="fa fa-check-circle"></i> Registration successful! <a href="login.php">Login here</a></div>';
  }catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}
}
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Register - ICST Academic Management</title>
<link href="assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="assets/css/custom.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head><body class="login-page">
<div class="login-card slide-up">
<div class="login-header"><div class="login-icon"><i class="fa fa-user-plus"></i></div><h2>Create Account</h2><p>Register for the ICST Academic portal</p></div>
<div class="login-body"><?=$message?>
<form method="post" class="needs-validation" novalidate>
<input type="hidden" name="csrf_token" value="<?=csrf_token()?>">
<div class="form-row"><div class="form-group"><label>First Name <span class="required">*</span></label><input name="fname" type="text" class="form-control" required><div class="form-error"></div></div>
<div class="form-group"><label>Last Name <span class="required">*</span></label><input name="lname" type="text" class="form-control" required><div class="form-error"></div></div></div>
<div class="form-group"><label>Email <span class="required">*</span></label><input name="email" type="email" class="form-control" required><div class="form-error"></div></div>
<div class="form-group"><label>Password <span class="required">*</span></label><input name="password" type="password" class="form-control" required><div class="form-error"></div></div>
<div class="form-group"><label>Role <span class="required">*</span></label><select name="role" class="form-control" required><option value="">Select Role</option><option value="Student">Student</option><option value="Parent">Parent</option><option value="Lecturer">Lecturer</option></select><div class="form-error"></div></div>
<button type="submit" name="submit" value="submit" class="btn btn-primary btn-lg btn-block"><i class="fa fa-user-plus"></i> Register</button>
<p class="text-center mt-3" style="font-size:13px">Already have an account? <a href="login.php">Login</a></p>
</form></div></div>
<script src="assets/js/custom.js"></script>
</body></html>

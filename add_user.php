<?php
/* add_user.php — Create a new system user account */
session_start(); include_once 'includes/config.php';
// Redirect unauthenticated or non-admin users
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: login.php'); exit; }
$message='';
// Fetch available emails from role tables for dropdown
$emails=db_fetch_all("SELECT email FROM (SELECT email FROM parent UNION SELECT email FROM student UNION SELECT email FROM teacher) t");
if(isset($_POST['submit'])){
  // CSRF check
  if(!isset($_POST['csrf_token'])||!verify_csrf($_POST['csrf_token'])){$message='<div class="alert alert-danger">Invalid request.</div>';}else{
  $email=sanitize($_POST['email']??'');$password=password_hash($_POST['password'],PASSWORD_DEFAULT);$role=sanitize($_POST['role']??'');
  // Insert new user into users table
  try{db_query("INSERT INTO users(email,password,role) VALUES(?,?,?)",[$email,$password,$role]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> User created!</div>';}
  catch(Exception $e){$message='<div class="alert alert-danger">'.htmlspecialchars($e->getMessage()).'</div>';}}
}
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Add User - Student Management</title><link rel="icon" href="images/user.png">
<?php include_once 'includes/header.php';?></head><body>
<div class="app-layout"><?php include_once 'includes/sidebar.php';?>
<div class="main-content"><?php include_once 'includes/nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Add User</h1></div>
<?=$message?>
<div class="card" style="max-width:500px"><div class="card-body">
<form method="post" class="needs-validation" novalidate>
<input type="hidden" name="csrf_token" value="<?=csrf_token()?>">
<div class="form-group"><label>User Email <span class="required">*</span></label><select name="email" class="form-control" required><option value="">Select Email</option><?php foreach($emails as $r):?><option value="<?=htmlspecialchars($r['email'])?>"><?=htmlspecialchars($r['email'])?></option><?php endforeach;?></select><div class="form-error"></div></div>
<div class="form-group"><label>Password <span class="required">*</span></label><input name="password" type="text" class="form-control" required><div class="form-error"></div></div>
<div class="form-group"><label>Role <span class="required">*</span></label><select name="role" class="form-control" required><option value="">Select</option><option value="Admin">Admin</option><option value="Lecturer">Lecturer</option><option value="Student">Student</option><option value="Parent">Parent</option></select><div class="form-error"></div></div>
<div class="form-actions"><button type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fa fa-save"></i> Create User</button>
<a href="manage_user.php" class="btn btn-secondary">Cancel</a></div></form></div></div></div>
<footer class="app-footer">Student Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'includes/footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Add User';</script>
</body></html>

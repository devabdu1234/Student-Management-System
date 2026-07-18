<?php
/* edit_user.php — Update user password and role */
session_start(); include_once 'includes/config.php';
// Redirect unauthenticated or non-admin users
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: login.php'); exit; }
$message='';
// Fetch current user data by email
$r=db_fetch("SELECT * FROM users WHERE email=?",[$_GET['email']??'']);
$email=$r['email']??'';$role=$r['role']??'';
if(isset($_POST['submit'])){
  // Verify CSRF token
  if(!isset($_POST['csrf_token'])||!verify_csrf($_POST['csrf_token'])){$message='<div class="alert alert-danger">Invalid request.</div>';}else{
  $password=password_hash($_POST['password'],PASSWORD_DEFAULT);$nrole=sanitize($_POST['role']??'');
  // Update user record using prepared statement
  try{db_query("UPDATE users SET password=?,role=? WHERE email=?",[$password,$nrole,$email]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> User updated!</div>';}
  catch(Exception $e){$message='<div class="alert alert-danger">'.htmlspecialchars($e->getMessage()).'</div>';}}
}
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Edit User - Student Management</title><link rel="icon" href="images/user.png">
<?php include_once 'includes/header.php';?></head><body>
<div class="app-layout"><?php include_once 'includes/sidebar.php';?>
<div class="main-content"><?php include_once 'includes/nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Edit User</h1></div>
<?=$message?>
<div class="card" style="max-width:500px"><div class="card-body">
<form method="post" class="needs-validation" novalidate>
<input type="hidden" name="csrf_token" value="<?=csrf_token()?>">
<div class="form-group"><label>Email</label><input type="text" class="form-control" value="<?=htmlspecialchars($email)?>" disabled></div>
<div class="form-group"><label>New Password <span class="required">*</span></label><input name="password" type="text" class="form-control" required><div class="form-error"></div></div>
<div class="form-group"><label>Role <span class="required">*</span></label><select name="role" class="form-control" required><option value="Admin" <?=$role=='Admin'?'selected':''?>>Admin</option><option value="Lecturer" <?=$role=='Lecturer'?'selected':''?>>Lecturer</option><option value="Student" <?=$role=='Student'?'selected':''?>>Student</option><option value="Parent" <?=$role=='Parent'?'selected':''?>>Parent</option></select><div class="form-error"></div></div>
<div class="form-actions"><button type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update User</button>
<a href="manage_user.php" class="btn btn-secondary">Cancel</a></div></form></div></div></div>
<footer class="app-footer">Student Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'includes/footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Edit User';</script>
</body></html>

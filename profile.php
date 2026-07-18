<?php
/* profile.php — Display current user's profile information from role-specific table */
session_start(); include_once 'includes/config.php';
// Redirect unauthenticated users
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
$role = $_SESSION['role']??'';
$email = $_SESSION['user_email']??'';
$user = $_SESSION['user']??'';
$info = [];

if ($role && $email) {
  // Fetch profile data from role-specific table
  $table = strtolower($role);
  $info = db_fetch("SELECT * FROM `$table` WHERE email=?",[$email]) ?: [];
}
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Profile - Student Management System</title><link rel="icon" href="images/user.png">
<?php include_once 'includes/header.php';?></head><body>
<div class="app-layout"><?php include_once 'includes/sidebar.php';?>
<div class="main-content"><?php include_once 'includes/nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>My Profile</h1></div>
<div class="card" style="max-width:600px">
<?php if(count($info)>0):?>
<div class="card-body">
<div class="text-center mb-4"><div class="avatar" style="width:80px;height:80px;border-radius:50%;background:var(--icst-red);display:inline-flex;align-items:center;justify-content:center;color:white;font-size:32px;font-weight:700;margin:0 auto"><?=strtoupper(substr($user,0,2))?></div>
<h4 class="mt-3"><?=htmlspecialchars($info['fname']??'').' '.htmlspecialchars($info['lname']??'')?></h4>
<p style="color:var(--text-muted)"><?=htmlspecialchars($role)?></p></div>
<hr>
<div class="form-row"><div class="form-group"><label>Email</label><input type="text" class="form-control" value="<?=htmlspecialchars($email)?>" disabled></div>
<?php if(isset($info['contact'])):?><div class="form-group"><label>Contact</label><input type="text" class="form-control" value="<?=htmlspecialchars($info['contact'])?>" disabled></div><?php endif;?></div>
<?php if(isset($info['address']) && $info['address']):?><div class="form-group"><label>Address</label><textarea class="form-control" rows="2" disabled><?=htmlspecialchars($info['address'])?></textarea></div><?php endif;?>
<?php if(isset($info['gender'])):?><div class="form-group"><label>Gender</label><input type="text" class="form-control" value="<?=htmlspecialchars($info['gender'])?>" disabled></div><?php endif;?>
</div>
<?php else:?>
<div class="card-body"><div class="empty-state"><i class="fa fa-user-circle"></i><h3>Profile Info</h3><p><?=htmlspecialchars($user)?></p></div></div>
<?php endif;?></div></div>
<footer class="app-footer">Student Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'includes/footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='My Profile';</script>
</body></html>

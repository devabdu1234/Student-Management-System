<?php
/* student-par.php — Parent-facing view of their linked children's information */
session_start(); include_once 'includes/config.php';
// Redirect non-parent users
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'Parent') { header('Location: logout.php'); exit; }
// Retrieve students linked to this parent via email
$rows=db_fetch_all("SELECT * FROM student WHERE parent=(SELECT pid FROM parent WHERE email=?)",[$_SESSION['user_email']??'']);
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>My Children - Student Management</title><link rel="icon" href="images/user.png"><?php include_once 'includes/header.php';?></head><body>
<div class="app-layout"><?php include_once 'includes/sidebar.php';?>
<div class="main-content"><?php include_once 'includes/nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>My Children</h1><p>View your registered children's information</p></div>
<?php if(count($rows)>0):?><div class="row"><?php foreach($rows as $r):?><div class="col-md-6 mb-4"><div class="card"><div class="card-body">
<h5><i class="fa fa-user-graduate" style="color:var(--icst-red);margin-right:8px"></i> <?=htmlspecialchars($r['fname'].' '.$r['lname'])?></h5>
<hr>
<div class="form-row"><div class="form-group"><label>Student ID</label><input type="text" class="form-control" value="<?=htmlspecialchars($r['sid'])?>" disabled></div>
<div class="form-group"><label>Classroom</label><input type="text" class="form-control" value="<?=htmlspecialchars($r['classroom'])?>" disabled></div></div>
<div class="form-row"><div class="form-group"><label>Email</label><input type="text" class="form-control" value="<?=htmlspecialchars($r['email'])?>" disabled></div>
<div class="form-group"><label>Gender</label><input type="text" class="form-control" value="<?=htmlspecialchars($r['gender'])?>" disabled></div></div>
</div></div></div><?php endforeach;?></div>
<?php else:?><div class="empty-state"><i class="fa fa-users"></i><h3>No Children Linked</h3><p>No student records are linked to your parent account.</p></div><?php endif;?></div>
<footer class="app-footer">Student Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'includes/footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='My Children';</script>
</body></html>

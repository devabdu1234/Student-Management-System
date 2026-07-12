<?php
session_start(); include_once 'database.php';
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: login.php'); exit; }

$total_students = db_fetch("SELECT COUNT(*) as c FROM student")['c']??0;
$total_teachers = db_fetch("SELECT COUNT(*) as c FROM teacher")['c']??0;
$total_subjects = db_fetch("SELECT COUNT(*) as c FROM subject")['c']??0;
$total_users = db_fetch("SELECT COUNT(*) as c FROM users")['c']??0;
$total_exams = db_fetch("SELECT COUNT(*) as c FROM exam")['c']??0;
$total_notices = db_fetch("SELECT COUNT(*) as c FROM notice")['c']??0;
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Dashboard - ICST</title><link rel="icon" href="images/user.png">
<?php include_once 'header.php';?></head><body>
<div class="app-layout"><?php include_once 'sidebar.php';?>
<div class="main-content"><?php include_once 'nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Admin Dashboard</h1><p>Full system overview and management</p></div>
<div class="stat-grid">
<div class="stat-card"><div class="stat-icon"><i class="fa fa-users"></i></div><div class="stat-label">Students</div><div class="stat-value"><?=$total_students?></div></div>
<div class="stat-card"><div class="stat-icon"><i class="fa fa-black-tie"></i></div><div class="stat-label">Lecturers</div><div class="stat-value"><?=$total_teachers?></div></div>
<div class="stat-card gold"><div class="stat-icon"><i class="fa fa-book"></i></div><div class="stat-label">Subjects</div><div class="stat-value"><?=$total_subjects?></div></div>
<div class="stat-card"><div class="stat-icon"><i class="fa fa-user-plus"></i></div><div class="stat-label">System Users</div><div class="stat-value"><?=$total_users?></div></div>
<div class="stat-card gold"><div class="stat-icon"><i class="fa fa-line-chart"></i></div><div class="stat-label">Assessments</div><div class="stat-value"><?=$total_exams?></div></div>
<div class="stat-card"><div class="stat-icon"><i class="fa fa-envelope-o"></i></div><div class="stat-label">Notices</div><div class="stat-value"><?=$total_notices?></div></div>
</div>
<div class="quick-actions">
<a href="manage_features.php" class="quick-action-item"><i class="fa fa-cogs"></i> Manage Features</a>
<a href="manage_user.php" class="quick-action-item"><i class="fa fa-users"></i> Manage Users</a>
<a href="student.php" class="quick-action-item"><i class="fa fa-user-plus"></i> Students</a>
<a href="exam.php" class="quick-action-item"><i class="fa fa-line-chart"></i> Assessments</a>
</div></div>
<footer class="app-footer">ICST Academic Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Admin Dashboard';</script>
</body></html>

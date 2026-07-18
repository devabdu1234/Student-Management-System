<?php
/* examresults-stu.php — Student-facing exam results view with aggregate stats */
session_start(); include_once 'includes/config.php';
// Redirect non-student users
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'Student') { header('Location: logout.php'); exit; }
// Retrieve results for the logged-in student using their uid
$rows=db_fetch_all("SELECT r.*, e.subject, sub.title as sub_title FROM examresult r LEFT JOIN exam e ON r.exam=e.id LEFT JOIN subject sub ON e.subject=sub.sid WHERE r.student=?",[$_SESSION['uid']??'']);
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>My Results - ICST</title><link rel="icon" href="images/user.png"><?php include_once 'includes/header.php';?></head><body>
<div class="app-layout"><?php include_once 'includes/sidebar.php';?>
<div class="main-content"><?php include_once 'includes/nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>My Results</h1><p>View your assessment results</p></div>
<?php if(count($rows)>0):?><div class="stat-grid">
<div class="stat-card gold"><div class="stat-icon"><i class="fa fa-star"></i></div><div class="stat-label">Total Assessments</div><div class="stat-value"><?=count($rows)?></div></div>
<?php $avg=array_sum(array_column($rows,'marks'))/count($rows);?>
<div class="stat-card"><div class="stat-icon"><i class="fa fa-calculator"></i></div><div class="stat-label">Average Marks</div><div class="stat-value"><?=number_format($avg,1)?></div></div>
</div>
<div class="card"><div class="card-body" style="padding:0"><div class="table-container" style="border:none;border-radius:0">
<table><thead><tr><th>Subject</th><th>Marks</th><th>Grade</th></tr></thead>
<tbody><?php foreach($rows as $r):?><tr><td><strong><?=htmlspecialchars($r['sub_title']??$r['subject'])?></strong></td><td><?=$r['marks']?></td><td><span class="badge" style="background:var(--icst-gold);color:#1a1a2e;padding:4px 12px;border-radius:10px"><?=htmlspecialchars($r['grade'])?></span></td></tr><?php endforeach;?></tbody></table></div></div></div>
<?php else:?><div class="empty-state"><i class="fa fa-graduation-cap"></i><h3>No Results Yet</h3><p>Your assessment results will appear here once published.</p></div><?php endif;?></div>
<footer class="app-footer">ICST Academic Management &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'includes/footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='My Results';</script>
</body></html>

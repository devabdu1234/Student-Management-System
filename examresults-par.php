<?php
session_start(); include_once 'database.php';
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'Parent') { header('Location: logout.php'); exit; }
$rows=db_fetch_all("SELECT r.*, e.subject, s.fname, s.lname, sub.title as sub_title FROM examresult r LEFT JOIN exam e ON r.exam=e.id LEFT JOIN student s ON r.student=s.sid LEFT JOIN subject sub ON e.subject=sub.sid WHERE s.parent=(SELECT pid FROM parent WHERE email=?)",[$_SESSION['user_email']??'']);
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Results - ICST Parent Portal</title><link rel="icon" href="images/user.png"><?php include_once 'header.php';?></head><body>
<div class="app-layout"><?php include_once 'sidebar.php';?>
<div class="main-content"><?php include_once 'nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Exam Results</h1><p>View your children's assessment results</p></div>
<?php if(count($rows)>0):?><div class="card"><div class="card-body" style="padding:0">
<div class="table-container" style="border:none;border-radius:0"><table><thead><tr><th>Student</th><th>Subject</th><th>Marks</th><th>Grade</th></tr></thead>
<tbody><?php foreach($rows as $r):?><tr><td><strong><?=htmlspecialchars($r['fname'].' '.$r['lname'])?></strong></td><td><?=htmlspecialchars($r['sub_title']??$r['subject'])?></td><td><?=$r['marks']?></td><td><span class="badge" style="background:var(--icst-gold);color:#1a1a2e;padding:4px 12px;border-radius:10px"><?=htmlspecialchars($r['grade'])?></span></td></tr><?php endforeach;?></tbody></table></div></div></div>
<?php else:?><div class="empty-state"><i class="fa fa-graduation-cap"></i><h3>No Results</h3><p>No exam results available for your children yet.</p></div><?php endif;?></div>
<footer class="app-footer">ICST Academic Management &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Exam Results';</script>
</body></html>

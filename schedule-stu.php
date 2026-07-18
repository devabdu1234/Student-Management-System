<?php
/* schedule-stu.php — Student-facing weekly class timetable view */
session_start(); include_once 'includes/config.php';
// Redirect non-student users
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'Student') { header('Location: login.php'); exit; }
// Retrieve all schedules ordered by day and time with subject/teacher names
$rows=db_fetch_all("SELECT s.*, sub.title as sub_title, t.fname as t_fname, t.lname as t_lname FROM schedule s LEFT JOIN subject sub ON s.subject=sub.sid LEFT JOIN teacher t ON s.teacher=t.tid ORDER BY FIELD(s.day,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), s.stime");
$days=['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>My Schedule - Student Management</title><link rel="icon" href="images/user.png"><?php include_once 'includes/header.php';?></head><body>
<div class="app-layout"><?php include_once 'includes/sidebar.php';?>
<div class="main-content"><?php include_once 'includes/nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>My Schedule</h1><p>Your weekly class timetable</p></div>
<?php if(count($rows)>0):?>
<div class="card"><div class="card-body" style="padding:0">
<div class="table-container" style="border:none;border-radius:0">
<div class="desktop-table"><table><thead><tr><th>Day</th><th>Subject</th><th>Lecturer</th><th>Room</th><th>Time</th></tr></thead>
<tbody><?php foreach($rows as $r):?><tr><td><strong><?=htmlspecialchars($r['day'])?></strong></td><td><?=htmlspecialchars($r['sub_title']??$r['subject'])?></td><td><?=htmlspecialchars(($r['t_fname']??'').' '.($r['t_lname']??''))?></td><td><?=htmlspecialchars($r['class'])?></td><td><?=htmlspecialchars($r['stime'])?> - <?=htmlspecialchars($r['etime'])?></td></tr><?php endforeach;?></tbody></table></div>
<div class="mobile-card-view"><?php foreach($days as $day):$day_items=[];foreach($rows as $r){if($r['day']==$day)$day_items[]=$r;}if(count($day_items)>0):?><h5 style="padding:12px 16px 4px;font-size:13px;color:var(--icst-red);font-weight:600"><?=$day?></h5><?php foreach($day_items as $r):?><div class="mobile-card-item">
<div class="mci-row"><span class="mci-label">Subject</span><span class="mci-value"><strong><?=htmlspecialchars($r['sub_title']??$r['subject'])?></strong></span></div>
<div class="mci-row"><span class="mci-label">Lecturer</span><span class="mci-value"><?=htmlspecialchars(($r['t_fname']??'').' '.($r['t_lname']??''))?></span></div>
<div class="mci-row"><span class="mci-label">Room</span><span class="mci-value"><?=htmlspecialchars($r['class'])?></span></div>
<div class="mci-row"><span class="mci-label">Time</span><span class="mci-value"><?=htmlspecialchars($r['stime'])?></span></div></div><?php endforeach;endif;endforeach;?></div></div></div></div>
<?php else:?><div class="empty-state"><i class="fa fa-calendar"></i><h3>No Schedule</h3><p>Your class schedule has not been published yet.</p></div><?php endif;?></div>
<footer class="app-footer">Student Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'includes/footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='My Schedule';</script>
</body></html>

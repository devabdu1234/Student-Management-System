<?php
/* attendance.php — Create and manage attendance sessions linked to schedules */
session_start(); include_once 'includes/config.php';
// Redirect unauthenticated or non-admin users
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: logout.php'); exit; }
$message='';
// Process form submission to create a new attendance session
if(isset($_POST['submit'])){
  $schedule=$_POST['schedule'];$date=$_POST['date'];
  try{db_query("INSERT INTO attendance(`date`,sid) VALUES(?,?)",[$date,$schedule]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Attendance session created!</div>';}
  catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}
}
// Process delete request
if(isset($_GET['delete'])){try{db_query("DELETE FROM attendance WHERE aid=?",[$_GET['delete']]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Attendance session deleted!</div>';}catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}}
// Retrieve schedules and attendance sessions for display
$schedules=db_fetch_all("SELECT s.*, sub.title as sub_title FROM schedule s LEFT JOIN subject sub ON s.subject=sub.sid ORDER BY s.id DESC");
$attendances=db_fetch_all("SELECT a.*, s.subject, s.class, s.stime, sub.title as sub_title FROM attendance a LEFT JOIN schedule s ON a.sid=s.id LEFT JOIN subject sub ON s.subject=sub.sid ORDER BY a.aid DESC");
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Attendance - ICST Academic Management</title><link rel="icon" href="images/user.png">
<?php include_once 'includes/header.php';?></head><body>
<div class="app-layout"><?php include_once 'includes/sidebar.php';?>
<div class="main-content"><?php include_once 'includes/nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Attendance Management</h1><p>Create attendance sessions and mark student attendance</p></div>
<?=$message?>
<div class="two-column">
<div class="card"><div class="card-header"><h3><i class="fa fa-plus-circle" style="color:var(--icst-red);margin-right:8px"></i> New Attendance Session</h3></div>
<div class="card-body"><form method="post" class="needs-validation" novalidate>
<div class="form-group"><label>Schedule <span class="required">*</span></label><select name="schedule" class="form-control" required><option value="">Select Schedule</option><?php foreach($schedules as $r):?><option value="<?=$r['id']?>"><?=htmlspecialchars(($r['sub_title']??$r['subject']).' - '.$r['class'].' - '.$r['day'])?></option><?php endforeach;?></select><div class="form-error"></div></div>
<div class="form-group"><label>Date <span class="required">*</span></label><input name="date" type="date" class="form-control" required><div class="form-error"></div></div>
<div class="form-actions"><button type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fa fa-save"></i> Create Session</button></div></form></div></div>
<div class="card"><div class="card-header"><h3><i class="fa fa-list" style="color:var(--icst-red);margin-right:8px"></i> Attendance Sessions</h3><span class="badge" style="background:var(--icst-red);color:white;padding:4px 12px;border-radius:10px"><?=count($attendances)?></span></div>
<div class="card-body" style="padding:0"><div class="table-toolbar"><div class="search-box"><i class="fa fa-search"></i><input type="text" data-table-search="attTable" placeholder="Search..."></div></div>
<div class="table-container" style="border:none;border-radius:0">
<div class="desktop-table"><table id="attTable"><thead><tr><th>#</th><th>Subject</th><th>Class</th><th>Date</th><th>Time</th><th>Actions</th></tr></thead>
<tbody><?php if(count($attendances)>0):?><?php foreach($attendances as $r):?><tr><td><?=$r['aid']?></td><td><?=htmlspecialchars($r['sub_title']??$r['subject'])?></td><td><?=htmlspecialchars($r['class'])?></td><td><?=htmlspecialchars($r['date'])?></td><td><?=htmlspecialchars($r['stime'])?></td>
<td class="actions"><a href="attendancelist.php?aid=<?=$r['aid']?>&class=<?=urlencode($r['class'])?>&subject=<?=urlencode($r['subject'])?>&date=<?=urlencode($r['date'])?>&stime=<?=urlencode($r['stime'])?>" class="btn btn-sm btn-gold"><i class="fa fa-check"></i> Manage</a><a href="attendance.php?delete=<?=$r['aid']?>" class="btn btn-sm btn-danger" data-confirm="Delete this attendance session?"><i class="fa fa-trash"></i></a></td></tr><?php endforeach;?><?php else:?><tr><td colspan="6" class="table-empty"><i class="fa fa-check-square-o"></i> No attendance sessions</td></tr><?php endif;?></tbody></table></div>
<div class="mobile-card-view"><?php if(count($attendances)>0):?><?php foreach($attendances as $r):?><div class="mobile-card-item">
<div class="mci-row"><span class="mci-label">Subject</span><span class="mci-value"><strong><?=htmlspecialchars($r['sub_title']??$r['subject'])?></strong></span></div>
<div class="mci-row"><span class="mci-label">Date</span><span class="mci-value"><?=htmlspecialchars($r['date'])?></span></div>
<div class="mci-row"><span class="mci-label">Class</span><span class="mci-value"><?=htmlspecialchars($r['class'])?></span></div>
<div class="mci-actions"><a href="attendancelist.php?aid=<?=$r['aid']?>&class=<?=urlencode($r['class'])?>&subject=<?=urlencode($r['subject'])?>&date=<?=urlencode($r['date'])?>&stime=<?=urlencode($r['stime'])?>" class="btn btn-sm btn-gold"><i class="fa fa-check"></i> Manage</a><a href="attendance.php?delete=<?=$r['aid']?>" class="btn btn-sm btn-danger" data-confirm="Delete?"><i class="fa fa-trash"></i> Delete</a></div></div><?php endforeach;?><?php else:?><div class="empty-state"><i class="fa fa-check-square-o"></i><h3>No Sessions</h3></div><?php endif;?></div></div></div></div></div></div>
<footer class="app-footer">ICST Academic Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'includes/footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Attendance Management';</script>
</body></html>

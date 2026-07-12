<?php
session_start(); include_once 'database.php';
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: logout.php'); exit; }
$message='';
// Sanitize all GET parameters before use
$aid=(int)($_GET['aid']??0);$class=htmlspecialchars($_GET['class']??'');$date=htmlspecialchars($_GET['date']??'');$subject=htmlspecialchars($_GET['subject']??'');$stime=htmlspecialchars($_GET['stime']??'');

// Check if attendance already marked
$existing=db_fetch("SELECT COUNT(*) as c FROM attendancereport WHERE aid=?",[$aid]);
$already_marked=$existing['c']>0;

// Mark attendance
if(isset($_POST['submitatt'])){
  try{
    foreach($_POST['att'] as $id=>$att){
      $sid=$_POST['sid'][$id];$aid_val=$_POST['aid'][$id];
      db_query("INSERT INTO attendancereport(aid,sid,status) VALUES(?,?,?)",[$aid_val,$sid,$att]);
    }
    $message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Attendance marked!</div>';
  }catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}
}

$students=db_fetch_all("SELECT * FROM student WHERE classroom=?",[$class]);
$attendance_records=db_fetch_all("SELECT a.*, s.fname, s.lname FROM attendancereport a LEFT JOIN student s ON a.sid=s.sid WHERE a.aid=?",[$aid]);
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Attendance Report - ICST</title><link rel="icon" href="images/user.png">
<?php include_once 'header.php';?></head><body>
<div class="app-layout"><?php include_once 'sidebar.php';?>
<div class="main-content"><?php include_once 'nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Attendance Report</h1><p>Session: <?=htmlspecialchars($subject)?> | <?=htmlspecialchars($date)?> | <?=htmlspecialchars($stime)?></p></div>
<?=$message?>
<div class="row">
<div class="col-md-4">
<div class="card"><div class="card-body">
<h5>Session Details</h5>
<table style="width:100%;font-size:13px"><tr><td style="padding:8px 0;color:var(--text-muted)">Subject:</td><td style="padding:8px 0"><strong><?=htmlspecialchars($subject)?></strong></td></tr>
<tr><td style="padding:8px 0;color:var(--text-muted)">Date:</td><td style="padding:8px 0"><strong><?=htmlspecialchars($date)?></strong></td></tr>
<tr><td style="padding:8px 0;color:var(--text-muted)">Time:</td><td style="padding:8px 0"><strong><?=htmlspecialchars($stime)?></strong></td></tr>
<tr><td style="padding:8px 0;color:var(--text-muted)">Classroom:</td><td style="padding:8px 0"><strong><?=htmlspecialchars($class)?></strong></td></tr></table>
<div class="mt-3"><?php if(!$already_marked):?><a href="attendancelist.php?mark=1&aid=<?=$aid?>&class=<?=urlencode($class)?>&date=<?=urlencode($date)?>&subject=<?=urlencode($subject)?>&stime=<?=urlencode($stime)?>" class="btn btn-primary"><i class="fa fa-check"></i> Mark Attendance</a>
<?php else:?><a href="attendancelist.php?view=1&aid=<?=$aid?>&class=<?=urlencode($class)?>&date=<?=urlencode($date)?>&subject=<?=urlencode($subject)?>&stime=<?=urlencode($stime)?>" class="btn btn-gold"><i class="fa fa-eye"></i> View Report</a><?php endif;?>
<a href="attendance.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a></div></div></div></div>

<div class="col-md-8">
<?php if(isset($_GET['mark'])):?>
<div class="card"><div class="card-header"><h3>Mark Attendance</h3></div>
<div class="card-body"><form method="post">
<div class="table-container">
<table><thead><tr><th>Student</th><th>Status</th></tr></thead>
<tbody><?php $x=0;foreach($students as $r):?><tr>
<td><?=htmlspecialchars($r['fname'].' '.$r['lname'])?></td>
<td><input type="hidden" name="sid[]" value="<?=htmlspecialchars($r['sid'])?>"><input type="hidden" name="aid[]" value="<?=htmlspecialchars($aid)?>">
<select name="att[<?=$x?>]" class="form-control" style="width:auto;display:inline-block">
<option value="Present">Present</option><option value="Absent">Absent</option></select></td>
</tr><?php $x++;endforeach;?></tbody></table></div>
<div class="form-actions"><button type="submit" name="submitatt" value="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit Attendance</button></div></form></div></div>

<?php elseif(isset($_GET['view']) || $already_marked):?>
<div class="card"><div class="card-header"><h3>Attendance Report</h3></div>
<div class="card-body" style="padding:0">
<div class="table-container" style="border:none;border-radius:0">
<table><thead><tr><th>Student</th><th>Status</th></tr></thead>
<tbody><?php if(count($attendance_records)>0):?><?php foreach($attendance_records as $r):?><tr>
<td><?=htmlspecialchars($r['fname'].' '.$r['lname'])?></td>
<td><span class="badge" style="background:<?=$r['status']=='Present'?'#27ae60':'#e74c3c'?>;color:white;padding:4px 12px;border-radius:10px"><?=htmlspecialchars($r['status'])?></span></td>
</tr><?php endforeach;?><?php else:?><tr><td colspan="2" class="table-empty"><i class="fa fa-check-square-o"></i> No records yet</td></tr><?php endif;?></tbody></table></div></div></div>
<?php endif;?>
</div></div></div>
<footer class="app-footer">ICST Academic Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Attendance Report';</script>
</body></html>

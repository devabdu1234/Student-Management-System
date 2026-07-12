<?php
session_start(); include_once 'database.php';
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: logout.php'); exit; }
$id=$subject=$teacher=$classroom=$day=$stime=$etime=''; $message=''; $is_update=isset($_GET['update']);
$subjects=db_fetch_all("SELECT * FROM subject"); $teachers=db_fetch_all("SELECT * FROM teacher"); $classrooms=db_fetch_all("SELECT * FROM classroom");
if(isset($_POST['submit'])){
  $subject=$_POST['subject'];$teacher=$_POST['teacher'];$classroom=$_POST['classroom'];$day=$_POST['day'];$stime=$_POST['stime'];$etime=$_POST['etime'];
  try{db_query("INSERT INTO schedule(subject,teacher,class,day,stime,etime) VALUES(?,?,?,?,?,?)",[$subject,$teacher,$classroom,$day,$stime,$etime]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Schedule added!</div>';}
  catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}
}
if(isset($_GET['delete'])){try{db_query("DELETE FROM schedule WHERE id=?",[$_GET['delete']]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Schedule deleted!</div>';}catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}}
$rows=db_fetch_all("SELECT s.*, sub.title as sub_title FROM schedule s LEFT JOIN subject sub ON s.subject=sub.sid ORDER BY s.id DESC");
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Schedule - ICST Academic Management</title><link rel="icon" href="images/user.png">
<?php include_once 'header.php';?></head><body>
<div class="app-layout"><?php include_once 'sidebar.php';?>
<div class="main-content"><?php include_once 'nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Schedule Management</h1><p>Manage class schedules and timetables</p></div>
<?=$message?>
<div class="two-column">
<div class="card"><div class="card-header"><h3><i class="fa fa-plus-circle" style="color:var(--icst-red);margin-right:8px"></i> Add Schedule</h3></div>
<div class="card-body"><form method="post" class="needs-validation" novalidate>
<div class="form-group"><label>Subject</label><select name="subject" class="form-control"><option value="">Select Subject</option><?php foreach($subjects as $r):?><option value="<?=htmlspecialchars($r['sid'])?>"><?=htmlspecialchars($r['title'])?></option><?php endforeach;?></select></div>
<div class="form-group"><label>Lecturer</label><select name="teacher" class="form-control"><option value="">Select Lecturer</option><?php foreach($teachers as $r):?><option value="<?=htmlspecialchars($r['tid'])?>"><?=htmlspecialchars($r['fname'].' '.$r['lname'])?></option><?php endforeach;?></select></div>
<div class="form-row"><div class="form-group"><label>Classroom</label><select name="classroom" class="form-control"><option value="">Select</option><?php foreach($classrooms as $r):?><option value="<?=htmlspecialchars($r['hno'])?>"><?=htmlspecialchars($r['title'])?></option><?php endforeach;?></select></div>
<div class="form-group"><label>Day</label><select name="day" class="form-control"><option value="">Select Day</option><option>Monday</option><option>Tuesday</option><option>Wednesday</option><option>Thursday</option><option>Friday</option><option>Saturday</option><option>Sunday</option></select></div></div>
<div class="form-row"><div class="form-group"><label>Start Time</label><input name="stime" type="time" class="form-control"></div>
<div class="form-group"><label>End Time</label><input name="etime" type="time" class="form-control"></div></div>
<div class="form-actions"><button type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fa fa-save"></i> Add Schedule</button></div></form></div></div>
<div class="card"><div class="card-header"><h3><i class="fa fa-list" style="color:var(--icst-red);margin-right:8px"></i> All Schedules</h3><span class="badge" style="background:var(--icst-red);color:white;padding:4px 12px;border-radius:10px"><?=count($rows)?></span></div>
<div class="card-body" style="padding:0"><div class="table-toolbar"><div class="search-box"><i class="fa fa-search"></i><input type="text" data-table-search="schTable" placeholder="Search..."></div></div>
<div class="table-container" style="border:none;border-radius:0">
<div class="desktop-table"><table id="schTable"><thead><tr><th>#</th><th>Subject</th><th>Lecturer</th><th>Class</th><th>Day</th><th>Time</th><th>Actions</th></tr></thead>
<tbody><?php if(count($rows)>0):?><?php foreach($rows as $r):?><tr><td><?=$r['id']?></td><td><?=htmlspecialchars($r['sub_title']??$r['subject'])?></td><td><?=htmlspecialchars($r['teacher'])?></td><td><?=htmlspecialchars($r['class'])?></td><td><?=htmlspecialchars($r['day'])?></td><td><?=htmlspecialchars($r['stime'])?>-<?=htmlspecialchars($r['etime'])?></td>
<td class="actions"><a href="schedule.php?delete=<?=$r['id']?>" class="btn btn-sm btn-danger" data-confirm="Delete this schedule?"><i class="fa fa-trash"></i></a></td></tr><?php endforeach;?><?php else:?><tr><td colspan="7" class="table-empty"><i class="fa fa-calendar"></i> No schedules found</td></tr><?php endif;?></tbody></table></div>
<div class="mobile-card-view"><?php if(count($rows)>0):?><?php foreach($rows as $r):?><div class="mobile-card-item">
<div class="mci-row"><span class="mci-label">Subject</span><span class="mci-value"><strong><?=htmlspecialchars($r['sub_title']??$r['subject'])?></strong></span></div>
<div class="mci-row"><span class="mci-label">Lecturer</span><span class="mci-value"><?=htmlspecialchars($r['teacher'])?></span></div>
<div class="mci-row"><span class="mci-label">Day/Time</span><span class="mci-value"><?=htmlspecialchars($r['day'])?> <?=htmlspecialchars($r['stime'])?></span></div>
<div class="mci-row"><span class="mci-label">Room</span><span class="mci-value"><?=htmlspecialchars($r['class'])?></span></div>
<div class="mci-actions"><a href="schedule.php?delete=<?=$r['id']?>" class="btn btn-sm btn-danger" data-confirm="Delete?"><i class="fa fa-trash"></i> Delete</a></div></div><?php endforeach;?><?php else:?><div class="empty-state"><i class="fa fa-calendar"></i><h3>No Schedules</h3></div><?php endif;?></div></div></div></div></div></div>
<footer class="app-footer">ICST Academic Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Schedule Management';</script>
</body></html>

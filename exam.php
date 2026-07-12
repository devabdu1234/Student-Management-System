<?php
session_start(); include_once 'database.php';
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: logout.php'); exit; }
$message='';
$subjects=db_fetch_all("SELECT * FROM subject"); $teachers=db_fetch_all("SELECT * FROM teacher"); $classrooms=db_fetch_all("SELECT * FROM classroom");
if(isset($_POST['submit'])){
  $subject=$_POST['subject'];$teacher=$_POST['teacher'];$classroom=$_POST['classroom'];$date=$_POST['date'];$stime=$_POST['stime'];$etime=$_POST['etime'];
  try{db_query("INSERT INTO exam(subject,teacher,classroom,`date`,stime,etime) VALUES(?,?,?,?,?,?)",[$subject,$teacher,$classroom,$date,$stime,$etime]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Assessment scheduled!</div>';}
  catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}
}
if(isset($_GET['delete'])){try{db_query("DELETE FROM exam WHERE id=?",[$_GET['delete']]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Assessment deleted!</div>';}catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}}
$rows=db_fetch_all("SELECT e.*, sub.title as sub_title FROM exam e LEFT JOIN subject sub ON e.subject=sub.sid ORDER BY e.id DESC");
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Assessments - ICST Academic Management</title><link rel="icon" href="images/user.png">
<?php include_once 'header.php';?></head><body>
<div class="app-layout"><?php include_once 'sidebar.php';?>
<div class="main-content"><?php include_once 'nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Assessment Management</h1><p>Schedule and manage examinations</p></div>
<?=$message?>
<div class="two-column">
<div class="card"><div class="card-header"><h3><i class="fa fa-plus-circle" style="color:var(--icst-red);margin-right:8px"></i> Schedule Assessment</h3></div>
<div class="card-body"><form method="post" class="needs-validation" novalidate>
<div class="form-group"><label>Subject</label><select name="subject" class="form-control"><option value="">Select Subject</option><?php foreach($subjects as $r):?><option value="<?=htmlspecialchars($r['sid'])?>"><?=htmlspecialchars($r['title'])?></option><?php endforeach;?></select></div>
<div class="form-row"><div class="form-group"><label>Lecturer</label><select name="teacher" class="form-control"><option value="">Select</option><?php foreach($teachers as $r):?><option value="<?=htmlspecialchars($r['tid'])?>"><?=htmlspecialchars($r['fname'].' '.$r['lname'])?></option><?php endforeach;?></select></div>
<div class="form-group"><label>Classroom</label><select name="classroom" class="form-control"><option value="">Select</option><?php foreach($classrooms as $r):?><option value="<?=htmlspecialchars($r['hno'])?>"><?=htmlspecialchars($r['title'])?></option><?php endforeach;?></select></div></div>
<div class="form-row"><div class="form-group"><label>Date <span class="required">*</span></label><input name="date" type="date" class="form-control" required><div class="form-error"></div></div>
<div class="form-group"><label>Start Time</label><input name="stime" type="time" class="form-control"></div></div>
<div class="form-group"><label>End Time</label><input name="etime" type="time" class="form-control"></div>
<div class="form-actions"><button type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fa fa-save"></i> Schedule Assessment</button></div></form></div></div>
<div class="card"><div class="card-header"><h3><i class="fa fa-list" style="color:var(--icst-red);margin-right:8px"></i> All Assessments</h3><span class="badge" style="background:var(--icst-red);color:white;padding:4px 12px;border-radius:10px"><?=count($rows)?></span></div>
<div class="card-body" style="padding:0"><div class="table-toolbar"><div class="search-box"><i class="fa fa-search"></i><input type="text" data-table-search="examTable" placeholder="Search..."></div></div>
<div class="table-container" style="border:none;border-radius:0">
<div class="desktop-table"><table id="examTable"><thead><tr><th>#</th><th>Subject</th><th>Lecturer</th><th>Room</th><th>Date</th><th>Time</th><th>Actions</th></tr></thead>
<tbody><?php if(count($rows)>0):?><?php foreach($rows as $r):?><tr><td><?=$r['id']?></td><td><strong><?=htmlspecialchars($r['sub_title']??$r['subject'])?></strong></td><td><?=htmlspecialchars($r['teacher'])?></td><td><?=htmlspecialchars($r['classroom'])?></td><td><?=htmlspecialchars($r['date'])?></td><td><?=htmlspecialchars($r['stime'])?>-<?=htmlspecialchars($r['etime'])?></td>
<td class="actions"><a href="exam.php?delete=<?=$r['id']?>" class="btn btn-sm btn-danger" data-confirm="Delete this assessment?"><i class="fa fa-trash"></i></a></td></tr><?php endforeach;?><?php else:?><tr><td colspan="7" class="table-empty"><i class="fa fa-line-chart"></i> No assessments scheduled</td></tr><?php endif;?></tbody></table></div>
<div class="mobile-card-view"><?php if(count($rows)>0):?><?php foreach($rows as $r):?><div class="mobile-card-item">
<div class="mci-row"><span class="mci-label">Subject</span><span class="mci-value"><strong><?=htmlspecialchars($r['sub_title']??$r['subject'])?></strong></span></div>
<div class="mci-row"><span class="mci-label">Date</span><span class="mci-value"><?=htmlspecialchars($r['date'])?></span></div>
<div class="mci-row"><span class="mci-label">Room</span><span class="mci-value"><?=htmlspecialchars($r['classroom'])?></span></div>
<div class="mci-row"><span class="mci-label">Time</span><span class="mci-value"><?=htmlspecialchars($r['stime'])?></span></div>
<div class="mci-actions"><a href="exam.php?delete=<?=$r['id']?>" class="btn btn-sm btn-danger" data-confirm="Delete?"><i class="fa fa-trash"></i> Delete</a></div></div><?php endforeach;?><?php else:?><div class="empty-state"><i class="fa fa-line-chart"></i><h3>No Assessments</h3></div><?php endif;?></div></div></div></div></div></div>
<footer class="app-footer">ICST Academic Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Assessment Management';</script>
</body></html>

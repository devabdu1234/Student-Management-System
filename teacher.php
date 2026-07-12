<?php
session_start(); include_once 'database.php';
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: logout.php'); exit; }
$tid=$fname=$lname=$email=$contact=$skill=$dob=$gender=$address='';
$message='';
$is_update=isset($_GET['update']);
if ($is_update) {
  $r=db_fetch("SELECT * FROM teacher WHERE tid=?",[$_GET['update']]);
  if($r){$tid=$r['tid'];$fname=$r['fname'];$lname=$r['lname'];$email=$r['email'];$contact=$r['contact'];$skill=$r['skill'];$dob=$r['bday'];$gender=$r['gender'];$address=$r['address'];}
}
if(isset($_POST['submit'])){
  $tid=$_POST['tid'];$fname=$_POST['fname'];$lname=$_POST['lname'];$email=$_POST['email'];$contact=$_POST['contact'];$skill=$_POST['skill'];$dob=$_POST['dob'];$gender=$_POST['gender'];$address=$_POST['address'];
  try{
    if($is_update){db_query("UPDATE teacher SET fname=?,lname=?,bday=?,address=?,gender=?,skill=?,contact=?,email=? WHERE tid=?",[$fname,$lname,$dob,$address,$gender,$skill,$contact,$email,$tid]);
      $message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Lecturer updated!</div>';}
    else {db_query("INSERT INTO teacher(tid,fname,lname,bday,address,gender,skill,contact,email) VALUES(?,?,?,?,?,?,?,?,?)",[$tid,$fname,$lname,$dob,$address,$gender,$skill,$contact,$email]);
      $message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Lecturer added!</div>';}
  }catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}
}
if(isset($_GET['delete'])){try{db_query("DELETE FROM teacher WHERE tid=?",[$_GET['delete']]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Lecturer deleted!</div>';}catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}}
$rows=db_fetch_all("SELECT * FROM teacher ORDER BY tid DESC");
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Lecturers - ICST Academic Management</title><link rel="icon" href="images/user.png">
<?php include_once 'header.php'; ?>
</head><body>
<div class="app-layout"><?php include_once 'sidebar.php'; ?>
<div class="main-content"><?php include_once 'nav-menu.php'; ?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Lecturer Management</h1><p>Manage all lecturers in the system</p></div>
<?=$message?>
<div class="two-column">
<div class="card"><div class="card-header"><h3><i class="fa <?=$is_update?'fa-pencil':'fa-plus-circle'?>" style="color:var(--icst-red);margin-right:8px"></i> <?=$is_update?'Update Lecturer':'Add Lecturer'?></h3></div>
<div class="card-body"><form method="post" class="needs-validation" novalidate>
<div class="form-row"><div class="form-group"><label>Lecturer ID <span class="required">*</span></label><input name="tid" type="text" class="form-control" value="<?=htmlspecialchars($tid)?>" <?=$is_update?'disabled':''?> required><div class="form-error"></div></div>
<div class="form-group"><label>Email <span class="required">*</span></label><input name="email" type="email" class="form-control" value="<?=htmlspecialchars($email)?>" required><div class="form-error"></div></div></div>
<div class="form-row"><div class="form-group"><label>First Name <span class="required">*</span></label><input name="fname" type="text" class="form-control" value="<?=htmlspecialchars($fname)?>" required><div class="form-error"></div></div>
<div class="form-group"><label>Last Name <span class="required">*</span></label><input name="lname" type="text" class="form-control" value="<?=htmlspecialchars($lname)?>" required><div class="form-error"></div></div></div>
<div class="form-row"><div class="form-group"><label>Date of Birth</label><input name="dob" type="date" class="form-control" value="<?=htmlspecialchars($dob)?>"></div>
<div class="form-group"><label>Gender</label><select name="gender" class="form-control"><option value="">Select</option><option value="Male" <?=$gender=='Male'?'selected':''?>>Male</option><option value="Female" <?=$gender=='Female'?'selected':''?>>Female</option></select></div></div>
<div class="form-group"><label>Address</label><textarea name="address" class="form-control" rows="2"><?=htmlspecialchars($address)?></textarea></div>
<div class="form-row"><div class="form-group"><label>Contact</label><input name="contact" type="text" class="form-control" value="<?=htmlspecialchars($contact)?>"></div>
<div class="form-group"><label>Skills/Specialization</label><textarea name="skill" class="form-control" rows="2"><?=htmlspecialchars($skill)?></textarea></div></div>
<div class="form-actions"><button type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?=$is_update?'Update':'Add'?> Lecturer</button>
<?php if($is_update):?><a href="teacher.php" class="btn btn-secondary"><i class="fa fa-times"></i> Cancel</a><?php endif;?></div></form></div></div>
<div class="card"><div class="card-header"><h3><i class="fa fa-list" style="color:var(--icst-red);margin-right:8px"></i> All Lecturers</h3><span class="badge" style="background:var(--icst-red);color:white;padding:4px 12px;border-radius:10px"><?=count($rows)?></span></div>
<div class="card-body" style="padding:0"><div class="table-toolbar"><div class="search-box"><i class="fa fa-search"></i><input type="text" data-table-search="teacherTable" placeholder="Search..."></div></div>
<div class="table-container" style="border:none;border-radius:0">
<div class="desktop-table"><table id="teacherTable"><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Contact</th><th>Actions</th></tr></thead>
<tbody><?php if(count($rows)>0):?><?php foreach($rows as $r):?><tr><td><?=htmlspecialchars($r['tid'])?></td><td><strong><?=htmlspecialchars($r['fname'].' '.$r['lname'])?></strong></td><td><?=htmlspecialchars($r['email'])?></td><td><?=htmlspecialchars($r['contact'])?></td>
<td class="actions"><a href="teacher.php?update=<?=urlencode($r['tid'])?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a><a href="teacher.php?delete=<?=urlencode($r['tid'])?>" class="btn btn-sm btn-danger" data-confirm="Delete this lecturer?"><i class="fa fa-trash"></i></a></td></tr><?php endforeach;?><?php else:?><tr><td colspan="5" class="table-empty"><i class="fa fa-black-tie"></i> No lecturers found</td></tr><?php endif;?></tbody></table></div>
<div class="mobile-card-view"><?php if(count($rows)>0):?><?php foreach($rows as $r):?><div class="mobile-card-item">
<div class="mci-row"><span class="mci-label">Name</span><span class="mci-value"><strong><?=htmlspecialchars($r['fname'].' '.$r['lname'])?></strong></span></div>
<div class="mci-row"><span class="mci-label">ID</span><span class="mci-value"><?=htmlspecialchars($r['tid'])?></span></div>
<div class="mci-row"><span class="mci-label">Email</span><span class="mci-value"><?=htmlspecialchars($r['email'])?></span></div>
<div class="mci-row"><span class="mci-label">Contact</span><span class="mci-value"><?=htmlspecialchars($r['contact'])?></span></div>
<div class="mci-actions"><a href="teacher.php?update=<?=urlencode($r['tid'])?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a><a href="teacher.php?delete=<?=urlencode($r['tid'])?>" class="btn btn-sm btn-danger" data-confirm="Delete?"><i class="fa fa-trash"></i> Delete</a></div></div><?php endforeach;?><?php else:?><div class="empty-state"><i class="fa fa-black-tie"></i><h3>No Lecturers</h3><p>Add your first lecturer.</p></div><?php endif;?></div></div></div></div></div></div>
<footer class="app-footer">ICST Academic Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Lecturer Management';</script>
</body></html>

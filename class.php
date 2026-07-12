<?php
session_start(); include_once 'database.php';
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: logout.php'); exit; }
$hno=$title=$location=$capacity=''; $message=''; $is_update=isset($_GET['update']);
if($is_update){$r=db_fetch("SELECT * FROM classroom WHERE hno=?",[$_GET['update']]);if($r){$hno=$r['hno'];$title=$r['title'];$location=$r['location'];$capacity=$r['capacity'];}}
if(isset($_POST['submit'])){
  $hno=$_POST['hno'];$title=$_POST['title'];$location=$_POST['location'];$capacity=$_POST['capacity'];
  try{
    if($is_update){db_query("UPDATE classroom SET title=?,location=?,capacity=? WHERE hno=?",[$title,$location,$capacity,$hno]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Classroom updated!</div>';}
    else{db_query("INSERT INTO classroom(hno,title,location,capacity) VALUES(?,?,?,?)",[$hno,$title,$location,$capacity]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Classroom added!</div>';}
  }catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}
}
if(isset($_GET['delete'])){try{db_query("DELETE FROM classroom WHERE hno=?",[$_GET['delete']]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Classroom deleted!</div>';}catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}}
$rows=db_fetch_all("SELECT * FROM classroom ORDER BY hno");
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Classrooms - ICST Academic Management</title><link rel="icon" href="images/user.png">
<?php include_once 'header.php';?></head><body>
<div class="app-layout"><?php include_once 'sidebar.php';?>
<div class="main-content"><?php include_once 'nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Classroom Management</h1><p>Manage lecture halls and classrooms</p></div>
<?=$message?>
<div class="two-column">
<div class="card"><div class="card-header"><h3><i class="fa <?=$is_update?'fa-pencil':'fa-plus-circle'?>" style="color:var(--icst-red);margin-right:8px"></i> <?=$is_update?'Update Classroom':'Add Classroom'?></h3></div>
<div class="card-body"><form method="post" class="needs-validation" novalidate>
<div class="form-group"><label>Room ID <span class="required">*</span></label><input name="hno" type="text" class="form-control" value="<?=htmlspecialchars($hno)?>" <?=$is_update?'disabled':''?> required><div class="form-error"></div></div>
<div class="form-row"><div class="form-group"><label>Room Title <span class="required">*</span></label><input name="title" type="text" class="form-control" value="<?=htmlspecialchars($title)?>" required><div class="form-error"></div></div>
<div class="form-group"><label>Location <span class="required">*</span></label><input name="location" type="text" class="form-control" value="<?=htmlspecialchars($location)?>" required><div class="form-error"></div></div></div>
<div class="form-group"><label>Capacity <span class="required">*</span></label><input name="capacity" type="number" class="form-control" value="<?=htmlspecialchars($capacity)?>" required><div class="form-error"></div></div>
<div class="form-actions"><button type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?=$is_update?'Update Classroom':'Add Classroom'?></button>
<?php if($is_update):?><a href="class.php" class="btn btn-secondary"><i class="fa fa-times"></i> Cancel</a><?php endif;?></div></form></div></div>
<div class="card"><div class="card-header"><h3><i class="fa fa-list" style="color:var(--icst-red);margin-right:8px"></i> All Classrooms</h3><span class="badge" style="background:var(--icst-red);color:white;padding:4px 12px;border-radius:10px"><?=count($rows)?></span></div>
<div class="card-body" style="padding:0"><div class="table-toolbar"><div class="search-box"><i class="fa fa-search"></i><input type="text" data-table-search="classTable" placeholder="Search..."></div></div>
<div class="table-container" style="border:none;border-radius:0">
<div class="desktop-table"><table id="classTable"><thead><tr><th>Room ID</th><th>Title</th><th>Location</th><th>Capacity</th><th>Actions</th></tr></thead>
<tbody><?php if(count($rows)>0):?><?php foreach($rows as $r):?><tr><td><?=htmlspecialchars($r['hno'])?></td><td><strong><?=htmlspecialchars($r['title'])?></strong></td><td><?=htmlspecialchars($r['location'])?></td><td><?=htmlspecialchars($r['capacity'])?></td>
<td class="actions"><a href="class.php?update=<?=urlencode($r['hno'])?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a><a href="class.php?delete=<?=urlencode($r['hno'])?>" class="btn btn-sm btn-danger" data-confirm="Delete this classroom?"><i class="fa fa-trash"></i></a></td></tr><?php endforeach;?><?php else:?><tr><td colspan="5" class="table-empty"><i class="fa fa-bank"></i> No classrooms found</td></tr><?php endif;?></tbody></table></div>
<div class="mobile-card-view"><?php if(count($rows)>0):?><?php foreach($rows as $r):?><div class="mobile-card-item">
<div class="mci-row"><span class="mci-label">Room ID</span><span class="mci-value"><strong><?=htmlspecialchars($r['hno'])?></strong></span></div>
<div class="mci-row"><span class="mci-label">Title</span><span class="mci-value"><?=htmlspecialchars($r['title'])?></span></div>
<div class="mci-row"><span class="mci-label">Location</span><span class="mci-value"><?=htmlspecialchars($r['location'])?></span></div>
<div class="mci-row"><span class="mci-label">Capacity</span><span class="mci-value"><?=htmlspecialchars($r['capacity'])?></span></div>
<div class="mci-actions"><a href="class.php?update=<?=urlencode($r['hno'])?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a><a href="class.php?delete=<?=urlencode($r['hno'])?>" class="btn btn-sm btn-danger" data-confirm="Delete?"><i class="fa fa-trash"></i> Delete</a></div></div><?php endforeach;?><?php else:?><div class="empty-state"><i class="fa fa-bank"></i><h3>No Classrooms</h3></div><?php endif;?></div></div></div></div></div></div>
<footer class="app-footer">ICST Academic Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Classroom Management';</script>
</body></html>

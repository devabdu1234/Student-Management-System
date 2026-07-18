<?php
/* parent.php — Add, update, delete, and list parent/guardian records */
session_start(); include_once 'includes/config.php';
// Redirect unauthenticated or non-admin users
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: logout.php'); exit; }
$pid=$fname=$lname=$email=$contact=$nic=$gender=$address=$job=''; $message=''; $is_update=isset($_GET['update']);
if($is_update){
  // Fetch existing parent for editing
  $r=db_fetch("SELECT * FROM parent WHERE pid=?",[$_GET['update']]);if($r){$pid=$r['pid'];$fname=$r['fname'];$lname=$r['lname'];$email=$r['email'];$contact=$r['contact'];$nic=$r['nic'];$gender=$r['gender'];$address=$r['address'];$job=$r['job'];}}
if(isset($_POST['submit'])){
  $fname=$_POST['fname'];$lname=$_POST['lname'];$email=$_POST['email'];$contact=$_POST['contact'];$nic=$_POST['nic'];$gender=$_POST['gender'];$address=$_POST['address'];$job=$_POST['job'];
  try{
    if($is_update){
      // Update existing parent record
      db_query("UPDATE parent SET fname=?,lname=?,address=?,gender=?,job=?,contact=?,email=?,nic=? WHERE pid=?",[$fname,$lname,$address,$gender,$job,$contact,$email,$nic,$pid]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Parent updated!</div>';}
    else{
      // Insert new parent record
      db_query("INSERT INTO parent(fname,lname,address,gender,job,contact,nic,email) VALUES(?,?,?,?,?,?,?,?)",[$fname,$lname,$address,$gender,$job,$contact,$nic,$email]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Parent added!</div>';}
  }catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}
}
// Process delete request
if(isset($_GET['delete'])){try{db_query("DELETE FROM parent WHERE pid=?",[$_GET['delete']]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Parent deleted!</div>';}catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}}
// Retrieve all parents for table listing
$rows=db_fetch_all("SELECT * FROM parent ORDER BY pid DESC");
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Parents - ICST Academic Management</title><link rel="icon" href="images/user.png">
<?php include_once 'includes/header.php';?></head><body>
<div class="app-layout"><?php include_once 'includes/sidebar.php';?>
<div class="main-content"><?php include_once 'includes/nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Parent Management</h1><p>Manage parents and guardians</p></div>
<?=$message?>
<div class="two-column">
<div class="card"><div class="card-header"><h3><i class="fa <?=$is_update?'fa-pencil':'fa-plus-circle'?>" style="color:var(--icst-red);margin-right:8px"></i> <?=$is_update?'Update Parent':'Add Parent'?></h3></div>
<div class="card-body"><form method="post" class="needs-validation" novalidate>
<div class="form-row"><div class="form-group"><label>First Name <span class="required">*</span></label><input name="fname" type="text" class="form-control" value="<?=htmlspecialchars($fname)?>" required><div class="form-error"></div></div>
<div class="form-group"><label>Last Name <span class="required">*</span></label><input name="lname" type="text" class="form-control" value="<?=htmlspecialchars($lname)?>" required><div class="form-error"></div></div></div>
<div class="form-row"><div class="form-group"><label>NIC <span class="required">*</span></label><input name="nic" type="text" class="form-control" value="<?=htmlspecialchars($nic)?>" required><div class="form-error"></div></div>
<div class="form-group"><label>Gender</label><select name="gender" class="form-control"><option value="">Select</option><option value="Male" <?=$gender=='Male'?'selected':''?>>Male</option><option value="Female" <?=$gender=='Female'?'selected':''?>>Female</option></select></div></div>
<div class="form-row"><div class="form-group"><label>Email <span class="required">*</span></label><input name="email" type="email" class="form-control" value="<?=htmlspecialchars($email)?>" required><div class="form-error"></div></div>
<div class="form-group"><label>Contact <span class="required">*</span></label><input name="contact" type="text" class="form-control" value="<?=htmlspecialchars($contact)?>" required><div class="form-error"></div></div></div>
<div class="form-group"><label>Address</label><textarea name="address" class="form-control" rows="2"><?=htmlspecialchars($address)?></textarea></div>
<div class="form-group"><label>Occupation</label><input name="job" type="text" class="form-control" value="<?=htmlspecialchars($job)?>"></div>
<div class="form-actions"><button type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?=$is_update?'Update Parent':'Add Parent'?></button>
<?php if($is_update):?><a href="parent.php" class="btn btn-secondary"><i class="fa fa-times"></i> Cancel</a><?php endif;?></div></form></div></div>
<div class="card"><div class="card-header"><h3><i class="fa fa-list" style="color:var(--icst-red);margin-right:8px"></i> All Parents</h3><span class="badge" style="background:var(--icst-red);color:white;padding:4px 12px;border-radius:10px"><?=count($rows)?></span></div>
<div class="card-body" style="padding:0"><div class="table-toolbar"><div class="search-box"><i class="fa fa-search"></i><input type="text" data-table-search="parentTable" placeholder="Search..."></div></div>
<div class="table-container" style="border:none;border-radius:0">
<div class="desktop-table"><table id="parentTable"><thead><tr><th>ID</th><th>Name</th><th>NIC</th><th>Contact</th><th>Actions</th></tr></thead>
<tbody><?php if(count($rows)>0):?><?php foreach($rows as $r):?><tr><td><?=$r['pid']?></td><td><strong><?=htmlspecialchars($r['fname'].' '.$r['lname'])?></strong></td><td><?=htmlspecialchars($r['nic'])?></td><td><?=htmlspecialchars($r['contact'])?></td>
<td class="actions"><a href="parent.php?update=<?=$r['pid']?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a><a href="parent.php?delete=<?=$r['pid']?>" class="btn btn-sm btn-danger" data-confirm="Delete this parent?"><i class="fa fa-trash"></i></a></td></tr><?php endforeach;?><?php else:?><tr><td colspan="5" class="table-empty"><i class="fa fa-female"></i> No parents found</td></tr><?php endif;?></tbody></table></div>
<div class="mobile-card-view"><?php if(count($rows)>0):?><?php foreach($rows as $r):?><div class="mobile-card-item">
<div class="mci-row"><span class="mci-label">Name</span><span class="mci-value"><strong><?=htmlspecialchars($r['fname'].' '.$r['lname'])?></strong></span></div>
<div class="mci-row"><span class="mci-label">NIC</span><span class="mci-value"><?=htmlspecialchars($r['nic'])?></span></div>
<div class="mci-row"><span class="mci-label">Contact</span><span class="mci-value"><?=htmlspecialchars($r['contact'])?></span></div>
<div class="mci-actions"><a href="parent.php?update=<?=$r['pid']?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a><a href="parent.php?delete=<?=$r['pid']?>" class="btn btn-sm btn-danger" data-confirm="Delete?"><i class="fa fa-trash"></i> Delete</a></div></div><?php endforeach;?><?php else:?><div class="empty-state"><i class="fa fa-female"></i><h3>No Parents</h3></div><?php endif;?></div></div></div></div></div></div>
<footer class="app-footer">ICST Academic Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'includes/footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Parent Management';</script>
</body></html>

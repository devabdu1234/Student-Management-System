<?php
/* user.php — Create, update, delete, and list system user accounts with role assignment */
session_start(); include_once 'includes/config.php';
// Redirect unauthenticated or non-admin users
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: logout.php'); exit; }
$message='';

// Process delete request
if (isset($_GET['delete'])) {
  try{db_query("DELETE FROM users WHERE email=?",[$_GET['delete']]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> User deleted!</div>';}
  catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}
}

$edit_email='';$edit_role='';
// Fetch existing user data for editing
if(isset($_GET['email'])){
  $r=db_fetch("SELECT * FROM users WHERE email=?",[$_GET['email']]);
  if($r){$edit_email=$r['email'];$edit_role=$r['role'];}
}

// Process form submission for create or update
if(isset($_POST['submit'])){
  if($_POST['submit']=='update_user'){
    $email=$_GET['email'];$password=password_hash($_POST['password'],PASSWORD_DEFAULT);$role=$_POST['role'];
    try{db_query("UPDATE users SET password=?,role=? WHERE email=?",[$password,$role,$email]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> User updated!</div>';}
    catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}
  }else{
    $email=$_POST['email'];$password=password_hash($_POST['password'],PASSWORD_DEFAULT);$role=$_POST['role'];
    try{db_query("INSERT INTO users(email,password,role) VALUES(?,?,?)",[$email,$password,$role]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> User created!</div>';}
    catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}
  }
}
// Fetch data for email dropdown and user listing table
$emails=db_fetch_all("SELECT email FROM (SELECT email FROM parent UNION SELECT email FROM student UNION SELECT email FROM teacher) t");
$users=db_fetch_all("SELECT * FROM users");
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Users - ICST Academic Management</title><link rel="icon" href="images/user.png">
<?php include_once 'includes/header.php';?></head><body>
<div class="app-layout"><?php include_once 'includes/sidebar.php';?>
<div class="main-content"><?php include_once 'includes/nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>User Management</h1><p>Create and manage system user accounts</p></div>
<?=$message?>
<div class="two-column">
<div class="card"><div class="card-header"><h3><i class="fa <?=isset($_GET['email'])?'fa-pencil':'fa-plus-circle'?>" style="color:var(--icst-red);margin-right:8px"></i> <?=isset($_GET['email'])?'Update User':'Create User'?></h3></div>
<div class="card-body"><form method="post" class="needs-validation" novalidate>
<div class="form-group"><label>User Email</label><?php if(isset($_GET['email'])):?><input type="text" class="form-control" value="<?=htmlspecialchars($edit_email)?>" disabled><?php else:?><select name="email" class="form-control" required><option value="">Select Email</option><?php foreach($emails as $r):?><option value="<?=htmlspecialchars($r['email'])?>"><?=htmlspecialchars($r['email'])?></option><?php endforeach;?></select><div class="form-error"></div><?php endif;?></div>
<div class="form-group"><label>Password <span class="required">*</span></label><input name="password" type="text" class="form-control" placeholder="Enter password" required><div class="form-error"></div></div>
<div class="form-group"><label>Role <span class="required">*</span></label><select name="role" class="form-control" required><option value="">Select Role</option><option value="Admin" <?=$edit_role=='Admin'?'selected':''?>>Admin</option><option value="Lecturer" <?=$edit_role=='Lecturer'?'selected':''?>>Lecturer</option><option value="Student" <?=$edit_role=='Student'?'selected':''?>>Student</option><option value="Parent" <?=$edit_role=='Parent'?'selected':''?>>Parent</option></select><div class="form-error"></div></div>
<div class="form-actions"><button type="submit" name="submit" value="<?=isset($_GET['email'])?'update_user':'submit'?>" class="btn btn-primary"><i class="fa fa-save"></i> <?=isset($_GET['email'])?'Update User':'Create User'?></button>
<?php if(isset($_GET['email'])):?><a href="user.php" class="btn btn-secondary"><i class="fa fa-times"></i> Cancel</a><?php endif;?></div></form></div></div>
<div class="card"><div class="card-header"><h3><i class="fa fa-list" style="color:var(--icst-red);margin-right:8px"></i> System Users</h3><span class="badge" style="background:var(--icst-red);color:white;padding:4px 12px;border-radius:10px"><?=count($users)?></span></div>
<div class="card-body" style="padding:0"><div class="table-container" style="border:none;border-radius:0">
<table><thead><tr><th>Email</th><th>Role</th><th>Actions</th></tr></thead>
<tbody><?php if(count($users)>0):?><?php foreach($users as $r):?><tr><td><?=htmlspecialchars($r['email'])?></td><td><span class="badge" style="background:var(--icst-red);color:white;padding:4px 10px;border-radius:10px"><?=htmlspecialchars($r['role'])?></span></td>
<td class="actions"><a href="user.php?delete=<?=urlencode($r['email'])?>" class="btn btn-sm btn-danger" data-confirm="Delete this user?"><i class="fa fa-trash"></i></a><a href="user.php?email=<?=urlencode($r['email'])?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a></td></tr><?php endforeach;?><?php else:?><tr><td colspan="3" class="table-empty"><i class="fa fa-user-plus"></i> No users found</td></tr><?php endif;?></tbody></table></div></div></div></div></div>
<footer class="app-footer">ICST Academic Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'includes/footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='User Management';</script>
</body></html>

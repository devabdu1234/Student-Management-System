<?php
/* add_features.php — Form to add a new system feature */
session_start(); include_once 'includes/config.php';
// Redirect unauthenticated or non-admin users
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: login.php'); exit; }
$message=''; $name=$desc=$facilities=$user=$image='';
if(isset($_POST['submit'])){
  // CSRF check
  if(!isset($_POST['csrf_token'])||!verify_csrf($_POST['csrf_token'])){$message='<div class="alert alert-danger">Invalid request.</div>';}else{
  // Sanitize input fields
  $name=sanitize($_POST['name']??'');$desc=sanitize($_POST['description']??'');$facilities=sanitize($_POST['facilities']??'');$user=sanitize($_POST['user']??'');$image=sanitize($_POST['image']??'');
  if($name){
    try{
      // Insert new feature using prepared statement
      db_query("INSERT INTO features(Features_name,description,facilities,user,image) VALUES(?,?,?,?,?)",[$name,$desc,$facilities,$user,$image]);
      $message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Feature "<strong>'.htmlspecialchars($name).'</strong>" added successfully!</div>';
      $name=$desc=$facilities=$user=$image='';
    }catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}
  }else{$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Feature name is required.</div>';}
  } // end CSRF else
} // end submit
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Add Feature - ICST Academic Management</title><link rel="icon" href="images/user.png">
<?php include_once 'includes/header.php';?></head><body>
<div class="app-layout"><?php include_once 'includes/sidebar.php';?>
<div class="main-content"><?php include_once 'includes/nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Add Feature</h1><p>Register a new system feature</p></div>
<?=$message?>
<div class="card" style="max-width:700px"><div class="card-body">
<form method="post" class="needs-validation" novalidate>
<input type="hidden" name="csrf_token" value="<?=csrf_token()?>">
<div class="form-group"><label>Feature Name <span class="required">*</span></label><input name="name" type="text" class="form-control" value="<?=htmlspecialchars($name)?>" required><div class="form-error"></div></div>
<div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3"><?=htmlspecialchars($desc)?></textarea></div>
<div class="form-group"><label>Facilities / Capabilities</label><textarea name="facilities" class="form-control" rows="2" placeholder="e.g. Feature A, Feature B, Feature C"><?=htmlspecialchars($facilities)?></textarea></div>
<div class="form-row"><div class="form-group"><label>User / Audience</label><input name="user" type="text" class="form-control" value="<?=htmlspecialchars($user)?>" placeholder="e.g. Admin, Lecturer"></div>
<div class="form-group"><label>Image Filename</label><input name="image" type="text" class="form-control" value="<?=htmlspecialchars($image)?>" placeholder="features_icon.jpg"></div></div>
<div class="form-actions"><button type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fa fa-save"></i> Add Feature</button>
<a href="manage_features.php" class="btn btn-secondary"><i class="fa fa-times"></i> Cancel</a></div></form></div></div></div>
<footer class="app-footer">ICST Academic Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'includes/footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Add Feature';</script>
</body></html>

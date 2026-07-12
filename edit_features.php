<?php
session_start(); include_once 'config.php';
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: login.php'); exit; }
$message=''; $id=$_GET['id']??0;
$f=db_fetch("SELECT * FROM features WHERE Features_id=?",[$id]);
if(!$f){echo'<script>window.location="manage_features.php"</script>';exit;}
$name=$f['Features_name'];$desc=$f['description']??'';$facilities=$f['facilities']??'';$user=$f['user']??'';$image=$f['image']??'';
if(isset($_POST['submit'])){
  if(!isset($_POST['csrf_token'])||!verify_csrf($_POST['csrf_token'])){$message='<div class="alert alert-danger">Invalid request.</div>';}else{
  $name=sanitize($_POST['name']??'');$desc=sanitize($_POST['description']??'');$facilities=sanitize($_POST['facilities']??'');$user=sanitize($_POST['user']??'');$image=sanitize($_POST['image']??'');
  if($name){
    try{
      db_query("UPDATE features SET Features_name=?,description=?,facilities=?,user=?,image=? WHERE Features_id=?",[$name,$desc,$facilities,$user,$image,$id]);
      $message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Feature updated!</div>';
      $f=db_fetch("SELECT * FROM features WHERE Features_id=?",[$id]);
    }catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}
  }else{$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Feature name is required.</div>';}
  }} // end CSRF else, end submit
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Edit Feature - ICST Academic Management</title><link rel="icon" href="images/user.png">
<?php include_once 'header.php';?></head><body>
<div class="app-layout"><?php include_once 'sidebar.php';?>
<div class="main-content"><?php include_once 'nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Edit Feature</h1><p>Update feature details</p></div>
<?=$message?>
<div class="card" style="max-width:700px"><div class="card-body">
<form method="post" class="needs-validation" novalidate>
<input type="hidden" name="csrf_token" value="<?=csrf_token()?>">
<div class="form-group"><label>Feature ID</label><input type="text" class="form-control" value="<?=htmlspecialchars($id)?>" disabled></div>
<div class="form-group"><label>Feature Name <span class="required">*</span></label><input name="name" type="text" class="form-control" value="<?=htmlspecialchars($name)?>" required><div class="form-error"></div></div>
<div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3"><?=htmlspecialchars($desc)?></textarea></div>
<div class="form-group"><label>Facilities / Capabilities</label><textarea name="facilities" class="form-control" rows="2"><?=htmlspecialchars($facilities)?></textarea></div>
<div class="form-row"><div class="form-group"><label>User / Audience</label><input name="user" type="text" class="form-control" value="<?=htmlspecialchars($user)?>"></div>
<div class="form-group"><label>Image Filename</label><input name="image" type="text" class="form-control" value="<?=htmlspecialchars($image)?>"></div></div>
<div class="form-actions"><button type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update Feature</button>
<a href="manage_features.php" class="btn btn-secondary"><i class="fa fa-times"></i> Cancel</a></div></form></div></div></div>
<footer class="app-footer">ICST Academic Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Edit Feature';</script>
</body></html>

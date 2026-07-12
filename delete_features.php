<?php
session_start(); include_once 'config.php';
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: login.php'); exit; }
$message=''; $id=$_GET['id']??0;
if($id){
  $f=db_fetch("SELECT * FROM features WHERE Features_id=?",[$id]);
  if($f){
    try{
      db_query("DELETE FROM features WHERE Features_id=?",[$id]);
      $message='<div class="alert alert-success"><i class="fa fa-check-circle"></i> Feature "<strong>'.htmlspecialchars($f['Features_name']).'</strong>" has been removed.</div>';
    }catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}
  }else{$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Feature not found.</div>';}
}
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Delete Feature - ICST Academic Management</title><link rel="icon" href="images/user.png">
<?php include_once 'header.php';?></head><body>
<div class="app-layout"><?php include_once 'sidebar.php';?>
<div class="main-content"><?php include_once 'nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Delete Feature</h1></div>
<?=$message?>
<a href="manage_features.php" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back to Features</a>
</div>
<footer class="app-footer">ICST Academic Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Delete Feature';</script>
</body></html>

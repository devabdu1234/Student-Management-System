<?php
/* manage_features.php — List, view, and manage all system features with CRUD links */
session_start(); include_once 'includes/config.php';
// Redirect unauthenticated or non-admin users
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: login.php'); exit; }
$message='';
// Retrieve all features from database
$features=db_fetch_all("SELECT * FROM features ORDER BY Features_id");
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Manage Features - Student Management System</title><link rel="icon" href="images/user.png">
<?php include_once 'includes/header.php';?></head><body>
<div class="app-layout"><?php include_once 'includes/sidebar.php';?>
<div class="main-content"><?php include_once 'includes/nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Manage Features</h1><p>System features overview — <?=count($features)?> features registered</p></div>
<?=$message?>
<div class="row"><?php foreach($features as $f):?>
<div class="col-md-4 mb-4"><div class="card">
<div class="card-body text-center">
<i class="fa fa-cog" style="font-size:36px;color:var(--icst-red);margin-bottom:12px"></i>
<h5><?=htmlspecialchars($f['Features_name'])?></h5>
<p style="font-size:13px;color:var(--text-muted)"><?=htmlspecialchars(substr($f['description']??'',0,120))?></p>
<?php if($f['facilities']):?><p style="font-size:12px;color:var(--icst-gold)"><i class="fa fa-check-circle"></i> <?=htmlspecialchars(substr($f['facilities'],0,80))?></p><?php endif;?>
<div class="mt-3">
<a href="edit_features.php?id=<?=$f['Features_id']?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a>
<a href="delete_features.php?id=<?=$f['Features_id']?>" class="btn btn-sm btn-danger" data-confirm="Remove this feature?"><i class="fa fa-trash"></i> Delete</a>
</div></div></div></div>
<?php endforeach;?>
<div class="col-md-4 mb-4"><div class="card" style="border:2px dashed var(--text-muted);background:transparent">
<div class="card-body text-center" style="padding:48px 20px">
<a href="add_features.php" style="text-decoration:none;color:var(--text-muted)">
<i class="fa fa-plus-circle" style="font-size:36px;margin-bottom:12px"></i>
<h5>Add New Feature</h5></a></div></div></div>
</div></div>
<footer class="app-footer">Student Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'includes/footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Manage Features';</script>
</body></html>

<?php
/* manage_user.php — List all system user accounts with edit/delete actions */
session_start(); include_once 'includes/config.php';
// Redirect unauthenticated or non-admin users
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: login.php'); exit; }
// Retrieve all users from database
$users = db_fetch_all("SELECT * FROM users");
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Manage Users - ICST</title><link rel="icon" href="images/user.png">
<?php include_once 'includes/header.php';?></head><body>
<div class="app-layout"><?php include_once 'includes/sidebar.php';?>
<div class="main-content"><?php include_once 'includes/nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Manage Users</h1><p>View and manage all system users</p></div>
<div class="card">
<div class="card-header"><h3>System Users</h3><a href="add_user.php" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add User</a></div>
<div class="card-body" style="padding:0">
<div class="table-container" style="border:none;border-radius:0">
<table><thead><tr><th>Email</th><th>Role</th><th>Actions</th></tr></thead>
<tbody><?php if(count($users)>0):?><?php foreach($users as $r):?><tr><td><?=htmlspecialchars($r['email'])?></td><td><span class="badge" style="background:var(--icst-red);color:white;padding:4px 10px;border-radius:10px"><?=htmlspecialchars($r['role'])?></span></td>
<td class="actions"><a href="edit_user.php?email=<?=urlencode($r['email'])?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a>
<a href="delete_user.php?email=<?=urlencode($r['email'])?>" class="btn btn-sm btn-danger" data-confirm="Delete this user?"><i class="fa fa-trash"></i></a></td></tr><?php endforeach;?><?php else:?><tr><td colspan="3" class="table-empty"><i class="fa fa-users"></i> No users</td></tr><?php endif;?></tbody></table></div></div></div></div>
<footer class="app-footer">ICST Academic Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'includes/footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Manage Users';</script>
</body></html>

<?php
session_start(); include_once 'database.php';
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: login.php'); exit; }
if(isset($_GET['email'])){try{db_query("DELETE FROM users WHERE email=?",[$_GET['email']]);}catch(Exception $e){}}
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Delete User - ICST</title><link rel="icon" href="images/user.png">
<?php include_once 'header.php';?></head><body>
<div class="app-layout"><?php include_once 'sidebar.php';?>
<div class="main-content"><?php include_once 'nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Delete User</h1></div>
<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> User has been deleted.</div>
<a href="manage_user.php" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a></div>
<footer class="app-footer">ICST Academic Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Delete User';</script>
</body></html>

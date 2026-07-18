<?php
// Start session and verify user authentication
session_start(); include_once 'includes/config.php';
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: login.php'); exit; }
$message='';
// Retrieve all products from database using prepared statement
$products=db_fetch_all("SELECT * FROM product ORDER BY product_id");
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Manage Products - ICST Academic Management</title><link rel="icon" href="images/user.png">
<?php include_once 'includes/header.php';?></head><body>
<div class="app-layout"><?php include_once 'includes/sidebar.php';?>
<div class="main-content"><?php include_once 'includes/nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Manage Products</h1><p>System products overview — <?=count($products)?> products registered</p></div>
<?=$message?>
<div class="row"><?php foreach($products as $p):?>
<div class="col-md-4 mb-4"><div class="card">
<div class="card-body text-center">
<i class="fa fa-cube" style="font-size:36px;color:var(--icst-red);margin-bottom:12px"></i>
<h5><?=htmlspecialchars($p['product_name'])?></h5>
<p style="font-size:13px;color:var(--text-muted)"><?=htmlspecialchars(substr($p['description']??'',0,120))?></p>
<p style="font-size:14px;font-weight:700;color:var(--navy)">$<?=number_format($p['price'],2)?> <span style="font-size:12px;font-weight:400;color:var(--text-muted)">/ Qty: <?=$p['quantity']?></span></p>
<div class="mt-3">
<a href="edit_product.php?id=<?=$p['product_id']?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a>
<a href="delete_product.php?id=<?=$p['product_id']?>" class="btn btn-sm btn-danger" data-confirm="Remove this product?"><i class="fa fa-trash"></i> Delete</a>
</div></div></div></div>
<?php endforeach;?>
<div class="col-md-4 mb-4"><div class="card" style="border:2px dashed var(--text-muted);background:transparent">
<div class="card-body text-center" style="padding:48px 20px">
<a href="add_product.php" style="text-decoration:none;color:var(--text-muted)">
<i class="fa fa-plus-circle" style="font-size:36px;margin-bottom:12px"></i>
<h5>Add New Product</h5></a></div></div></div>
</div></div>
<footer class="app-footer">ICST Academic Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'includes/footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Manage Products';</script>
</body></html>

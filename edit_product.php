<?php
// Start session and verify user authentication
session_start(); include_once 'includes/config.php';
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: login.php'); exit; }
$message=''; $id=$_GET['id']??0;
// Fetch existing product by ID using prepared statement
$p=db_fetch("SELECT * FROM product WHERE product_id=?",[$id]);
if(!$p){echo'<script>window.location="manage_product.php"</script>';exit;}
$name=$p['product_name'];$desc=$p['description']??'';$price=$p['price']??0;$qty=$p['quantity']??0;
if(isset($_POST['submit'])){
  // Verify CSRF token for security
  if(!isset($_POST['csrf_token'])||!verify_csrf($_POST['csrf_token'])){$message='<div class="alert alert-danger">Invalid request.</div>';}else{
  // Sanitize all input fields
  $name=sanitize($_POST['name']??'');$desc=sanitize($_POST['description']??'');
  $price=sanitize($_POST['price']??'0');$qty=sanitize($_POST['quantity']??'0');
  if($name){
    try{
      // Update product using prepared statement
      db_query("UPDATE product SET product_name=?,description=?,price=?,quantity=? WHERE product_id=?",[$name,$desc,$price,$qty,$id]);
      $message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Product updated!</div>';
      $p=db_fetch("SELECT * FROM product WHERE product_id=?",[$id]);
    }catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}
  }else{$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Product name is required.</div>';}
  }} // end CSRF else, end submit
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Edit Product - ICST Academic Management</title><link rel="icon" href="images/user.png">
<?php include_once 'includes/header.php';?></head><body>
<div class="app-layout"><?php include_once 'includes/sidebar.php';?>
<div class="main-content"><?php include_once 'includes/nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Edit Product</h1><p>Update product details</p></div>
<?=$message?>
<div class="card" style="max-width:700px"><div class="card-body">
<form method="post" class="needs-validation" novalidate>
<input type="hidden" name="csrf_token" value="<?=csrf_token()?>">
<div class="form-group"><label>Product ID</label><input type="text" class="form-control" value="<?=htmlspecialchars($id)?>" disabled></div>
<div class="form-group"><label>Product Name <span class="required">*</span></label><input name="name" type="text" class="form-control" value="<?=htmlspecialchars($name)?>" required><div class="form-error"></div></div>
<div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3"><?=htmlspecialchars($desc)?></textarea></div>
<div class="form-row"><div class="form-group"><label>Price ($)</label><input name="price" type="number" step="0.01" min="0" class="form-control" value="<?=htmlspecialchars($price)?>"></div>
<div class="form-group"><label>Quantity</label><input name="quantity" type="number" min="0" class="form-control" value="<?=htmlspecialchars($qty)?>"></div></div>
<div class="form-actions"><button type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update Product</button>
<a href="manage_product.php" class="btn btn-secondary"><i class="fa fa-times"></i> Cancel</a></div></form></div></div></div>
<footer class="app-footer">ICST Academic Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'includes/footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Edit Product';</script>
</body></html>

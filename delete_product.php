<?php
// Start session and verify user authentication
session_start(); include_once 'includes/config.php';
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: login.php'); exit; }
$message=''; $id=$_GET['id']??0;
if($id){
  // Fetch product to display name in confirmation message
  $p=db_fetch("SELECT * FROM product WHERE product_id=?",[$id]);
  if($p){
    try{
      // Delete product using prepared statement
      db_query("DELETE FROM product WHERE product_id=?",[$id]);
      $message='<div class="alert alert-success"><i class="fa fa-check-circle"></i> Product "<strong>'.htmlspecialchars($p['product_name']).'</strong>" has been removed.</div>';
    }catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}
  }else{$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Product not found.</div>';}
}
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Delete Product - Student Management System</title><link rel="icon" href="images/user.png">
<?php include_once 'includes/header.php';?></head><body>
<div class="app-layout"><?php include_once 'includes/sidebar.php';?>
<div class="main-content"><?php include_once 'includes/nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Delete Product</h1></div>
<?=$message?>
<a href="manage_product.php" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back to Products</a>
</div>
<footer class="app-footer">Student Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'includes/footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Delete Product';</script>
</body></html>

<?php
// Start session for navigation context
session_start();
// Include database connection
require_once 'includes/config.php';
// Fetch all products using prepared statement
$products = db_fetch_all("SELECT * FROM product ORDER BY product_name");
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Products - Student Management System</title>
<link href="assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="assets/css/custom.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head><body>
<div class="public-page">
<div class="public-header">
<div class="logo"><div class="logo-icon">SM</div><div>Student Management System<small>University of Vocational Technology</small></div></div>
<nav><a href="index.php">Home</a><a href="about-us.php">About</a><a href="features.php">Features</a><a href="product.php">Products</a><a href="contact-us.php">Contact</a>
<?php if(isset($_SESSION['user'])):?><a href="dashboard.php" class="btn btn-sm btn-primary">Dashboard</a>
<?php else:?><a href="login.php" class="btn btn-sm btn-gold">Login</a><a href="register.php" class="btn btn-sm btn-primary">Register</a>
<?php endif;?>
</nav></div>
<div class="public-hero" style="background:linear-gradient(135deg,var(--bg-sidebar) 0%,#2a1a1a 100%);padding:48px 32px">
<div class="hero-content"><h1>Our Products</h1><p>Browse available academic products and merchandise</p></div></div>
<div class="public-content">
<div class="row">
<?php if(count($products)>0):?>
<?php foreach($products as $p):?>
<div class="col-md-4 mb-4"><div class="card"><div class="card-body text-center">
<div class="product-icon" style="width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,var(--icst-red),var(--icst-gold));display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
<i class="fa fa-cube" style="font-size:28px;color:#fff"></i></div>
<h5><?=htmlspecialchars($p['product_name'])?></h5>
<p style="font-size:13px;color:var(--text-muted);min-height:40px"><?=htmlspecialchars(substr($p['description']??'',0,150))?></p>
<div class="d-flex justify-content-between align-items-center mt-3">
<span style="font-size:24px;font-weight:800;color:var(--icst-red)">$<?=number_format($p['price'],2)?></span>
<span style="font-size:12px;color:var(--text-muted)"><i class="fa fa-cubes"></i> Qty: <?=$p['quantity']?></span>
</div></div></div></div>
<?php endforeach;?>
<?php else:?>
<div class="col-12 text-center py-5"><i class="fa fa-cube" style="font-size:48px;color:var(--text-muted);opacity:0.4"></i><p style="color:var(--text-muted);margin-top:16px">No products available at this time.</p></div>
<?php endif;?>
</div></div>
<div class="public-footer">Student Management System &copy; <?=date('Y')?></div></div>
</body></html>

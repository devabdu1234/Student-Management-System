<?php session_start(); $sent='';
if(isset($_POST['submit'])){$sent='<div class="alert alert-success"><i class="fa fa-check-circle"></i> Thank you! Your message has been sent.</div>';} ?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Contact Us - ICST Academic Management</title>
<link href="assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="assets/css/custom.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head><body>
<div class="public-page">
<div class="public-header">
<div class="logo"><div class="logo-icon">IC</div><div>ICST Academic Management<small>University of Vocational Technology</small></div></div>
<nav><a href="index.php">Home</a><a href="about-us.php">About</a><a href="features.php">Features</a><a href="contact-us.php">Contact</a><a href="login.php" class="btn btn-sm btn-gold">Login</a></nav></div>
<div class="public-hero" style="background:linear-gradient(135deg,var(--bg-sidebar) 0%,#2a1a1a 100%);padding:48px 32px">
<div class="hero-content"><h1>Contact Us</h1><p>Get in touch with the ICST team</p></div></div>
<div class="public-content">
<div class="row justify-content-center">
<div class="col-md-6">
<?=$sent?>
<div class="card"><div class="card-body">
<form method="post" class="needs-validation" novalidate>
<div class="form-group"><label>Name <span class="required">*</span></label><input type="text" name="name" class="form-control" required><div class="form-error"></div></div>
<div class="form-group"><label>Email <span class="required">*</span></label><input type="email" name="email" class="form-control" required><div class="form-error"></div></div>
<div class="form-group"><label>Message <span class="required">*</span></label><textarea name="message" class="form-control" rows="5" required></textarea><div class="form-error"></div></div>
<button type="submit" name="submit" value="submit" class="btn btn-primary btn-block"><i class="fa fa-send"></i> Send Message</button>
</form></div></div></div>
<div class="col-md-4">
<div class="card"><div class="card-body">
<h5><i class="fa fa-map-marker" style="color:var(--icst-red)"></i> Address</h5><p style="font-size:13px;color:var(--text-muted)">ICST, University of Vocational Technology<br>Colombo, Sri Lanka</p>
<h5><i class="fa fa-phone" style="color:var(--icst-gold)"></i> Phone</h5><p style="font-size:13px;color:var(--text-muted)">+94 11 234 5678</p>
<h5><i class="fa fa-envelope" style="color:var(--icst-red)"></i> Email</h5><p style="font-size:13px;color:var(--text-muted)">info@icst.ac.lk</p>
</div></div></div></div></div>
<div class="public-footer">ICST Academic Management System &copy; <?=date('Y')?></div></div>
</body></html>

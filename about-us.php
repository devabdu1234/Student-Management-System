<?php
/* about-us.php — About Us page for ICST public-facing site */
session_start(); ?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>About Us - ICST Academic Management</title>
<link href="assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="assets/css/custom.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head><body>
<div class="public-page">
<div class="public-header">
<div class="logo"><div class="logo-icon">IC</div><div>ICST Academic Management<small>University of Vocational Technology</small></div></div>
<nav><a href="index.php">Home</a><a href="about-us.php">About</a><a href="features.php">Features</a><a href="product.php">Products</a><a href="contact-us.php">Contact</a><a href="login.php" class="btn btn-sm btn-gold">Login</a></nav></div>
<div class="public-hero" style="background:linear-gradient(135deg,var(--bg-sidebar) 0%,#2a1a1a 100%)">
<div class="hero-content"><h1>About ICST</h1><p>The ICST Academic Management System is a comprehensive platform designed to streamline student information management, attendance tracking, assessment scheduling, and academic reporting for the Institute of Computer Science and Technology.</p></div></div>
<div class="public-content">
<div class="row text-center">
<div class="col-md-4 mb-4"><div class="card"><div class="card-body"><i class="fa fa-eye" style="font-size:36px;color:var(--icst-red);margin-bottom:16px"></i><h4>Our Vision</h4><p style="color:var(--text-muted)">To be the leading academic management solution that empowers educational institutions through technology.</p></div></div></div>
<div class="col-md-4 mb-4"><div class="card"><div class="card-body"><i class="fa fa-bullseye" style="font-size:36px;color:var(--icst-gold);margin-bottom:16px"></i><h4>Our Mission</h4><p style="color:var(--text-muted)">Provide intuitive, accessible, and efficient tools for managing academic operations.</p></div></div></div>
<div class="col-md-4 mb-4"><div class="card"><div class="card-body"><i class="fa fa-graduation-cap" style="font-size:36px;color:var(--icst-red);margin-bottom:16px"></i><h4>Our Values</h4><p style="color:var(--text-muted)">Excellence, Innovation, Integrity, and Student-centered design.</p></div></div></div></div></div>
<div class="public-footer">ICST Academic Management System &copy; <?=date('Y')?></div></div>
</body></html>

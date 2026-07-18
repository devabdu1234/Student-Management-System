<?php
/* features.php — System features overview page for public visitors */
session_start(); ?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Features - ICST Academic Management</title>
<link href="assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="assets/css/custom.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head><body>
<div class="public-page">
<div class="public-header">
<div class="logo"><div class="logo-icon">IC</div><div>ICST Academic Management<small>University of Vocational Technology</small></div></div>
<nav><a href="index.php">Home</a><a href="about-us.php">About</a><a href="features.php">Features</a><a href="product.php">Products</a><a href="contact-us.php">Contact</a><a href="login.php" class="btn btn-sm btn-gold">Login</a></nav></div>
<div class="public-hero" style="background:linear-gradient(135deg,var(--bg-sidebar) 0%,#2a1a1a 100%);padding:48px 32px">
<div class="hero-content"><h1>System Features</h1><p>Comprehensive tools for modern academic management</p></div></div>
<div class="public-content">
<div class="row">
<div class="col-md-4 mb-4"><div class="card"><div class="card-body text-center"><i class="fa fa-users" style="font-size:32px;color:var(--icst-red);margin-bottom:12px"></i><h5>Student Management</h5><p style="font-size:13px;color:var(--text-muted)">Register, update, and manage all student records with ease.</p></div></div></div>
<div class="col-md-4 mb-4"><div class="card"><div class="card-body text-center"><i class="fa fa-black-tie" style="font-size:32px;color:var(--icst-gold);margin-bottom:12px"></i><h5>Lecturer Management</h5><p style="font-size:13px;color:var(--text-muted)">Maintain lecturer profiles, skills, and contact information.</p></div></div></div>
<div class="col-md-4 mb-4"><div class="card"><div class="card-body text-center"><i class="fa fa-book" style="font-size:32px;color:var(--icst-red);margin-bottom:12px"></i><h5>Subject Management</h5><p style="font-size:13px;color:var(--text-muted)">Define subjects, syllabi, and academic courses offered.</p></div></div></div>
<div class="col-md-4 mb-4"><div class="card"><div class="card-body text-center"><i class="fa fa-check-square-o" style="font-size:32px;color:var(--icst-gold);margin-bottom:12px"></i><h5>Attendance Tracking</h5><p style="font-size:13px;color:var(--text-muted)">Mark and monitor student attendance per session.</p></div></div></div>
<div class="col-md-4 mb-4"><div class="card"><div class="card-body text-center"><i class="fa fa-line-chart" style="font-size:32px;color:var(--icst-red);margin-bottom:12px"></i><h5>Assessment Management</h5><p style="font-size:13px;color:var(--text-muted)">Schedule exams and record student results with grades.</p></div></div></div>
<div class="col-md-4 mb-4"><div class="card"><div class="card-body text-center"><i class="fa fa-envelope-o" style="font-size:32px;color:var(--icst-gold);margin-bottom:12px"></i><h5>Notice System</h5><p style="font-size:13px;color:var(--text-muted)">Send targeted notices to students and parents.</p></div></div></div>
<div class="col-md-4 mb-4"><div class="card"><div class="card-body text-center"><i class="fa fa-check-circle" style="font-size:32px;color:var(--icst-red);margin-bottom:12px"></i><h5>Eligibility Tracking</h5><p style="font-size:13px;color:var(--text-muted)">Evaluate student eligibility for exams, promotions, and academic awards.</p></div></div></div>
<div class="col-md-4 mb-4"><div class="card"><div class="card-body text-center"><i class="fa fa-bar-chart" style="font-size:32px;color:var(--icst-gold);margin-bottom:12px"></i><h5>Reports & Analytics</h5><p style="font-size:13px;color:var(--text-muted)">Generate academic performance reports and insights.</p></div></div></div>
</div></div>
<div class="public-footer">ICST Academic Management System &copy; <?=date('Y')?></div></div>
</body></html>

<?php
/* index.php — Home / landing page for Student Management System */
session_start(); ?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Student Management System</title>
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
<div class="public-hero" style="background:linear-gradient(135deg,var(--bg-sidebar) 0%,#2a1a1a 100%);padding:80px 20px">
<div class="hero-content" style="max-width:800px;margin:0 auto;text-align:center">
<i class="fa fa-institution" style="font-size:64px;color:var(--icst-gold);margin-bottom:20px"></i>
<h1 style="font-size:2.5rem;margin-bottom:16px">Institute of Computer Science & Technology</h1>
<p style="font-size:1.1rem;color:var(--text-muted);margin-bottom:30px">Modern academic management platform for students, lecturers, and administrators. Streamline your educational operations with our comprehensive suite of tools.</p>
<?php if(!isset($_SESSION['user'])):?>
<a href="login.php" class="btn btn-gold btn-lg" style="margin-right:10px"><i class="fa fa-sign-in"></i> Sign In</a>
<a href="register.php" class="btn btn-primary btn-lg"><i class="fa fa-user-plus"></i> Register</a>
<?php else:?>
<a href="dashboard.php" class="btn btn-gold btn-lg"><i class="fa fa-dashboard"></i> Go to Dashboard</a>
<?php endif;?>
</div></div>
<div class="public-content">
<div class="row text-center">
<div class="col-md-4 mb-4"><div class="card"><div class="card-body">
<i class="fa fa-users" style="font-size:36px;color:var(--icst-red);margin-bottom:16px"></i>
<h4>Student Management</h4><p style="color:var(--text-muted)">Comprehensive student records, profiles, and academic history at your fingertips.</p>
</div></div></div>
<div class="col-md-4 mb-4"><div class="card"><div class="card-body">
<i class="fa fa-calendar" style="font-size:36px;color:var(--icst-gold);margin-bottom:16px"></i>
<h4>Schedule & Attendance</h4><p style="color:var(--text-muted)">Manage class timetables, track attendance, and generate reports seamlessly.</p>
</div></div></div>
<div class="col-md-4 mb-4"><div class="card"><div class="card-body">
<i class="fa fa-line-chart" style="font-size:36px;color:var(--icst-red);margin-bottom:16px"></i>
<h4>Assessments & Results</h4><p style="color:var(--text-muted)">Schedule exams, record marks, and publish results with automated grade calculation.</p>
</div></div></div>
</div>
<div class="row text-center mt-4">
<div class="col-md-6 mb-4"><div class="card"><div class="card-body text-left">
<h4><i class="fa fa-check-circle" style="color:var(--icst-red);margin-right:8px"></i> Key Features</h4>
<ul style="color:var(--text-muted);line-height:2">
<li>Role-based access for Admin, Lecturer, Student, and Parent</li>
<li>Responsive design works on desktop, tablet, and mobile</li>
<li>Dark and light theme support</li>
<li>Real-time attendance marking and reporting</li>
<li>Automated grade calculation and result publication</li>
<li>Notice broadcasting to targeted audiences</li>
</ul>
</div></div></div>
<div class="col-md-6 mb-4"><div class="card"><div class="card-body text-left">
<h4><i class="fa fa-dashboard" style="color:var(--icst-gold);margin-right:8px"></i> Quick Stats</h4>
<ul style="color:var(--text-muted);line-height:2">
<li>10+ core academic subjects offered</li>
<li>6 modern classrooms and laboratories</li>
<li>3 specialized lecturer profiles</li>
<li>Comprehensive exam and results tracking</li>
<li>Parent portal for student progress monitoring</li>
<li>Fully open-source and XAMPP compatible</li>
</ul>
</div></div></div>
</div>
</div>
<div class="public-footer">
Student Management System &copy; <?=date('Y')?> | HDIT21193 - User Experience and Interface Design
</div>
</div>
</body></html>

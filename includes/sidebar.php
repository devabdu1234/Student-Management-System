<div id="sidebarOverlay" class="sidebar-overlay"></div>
<aside id="sidebar" class="sidebar">
  <div class="sidebar-brand">
    <div class="brand-icon">IC</div>
    <div class="brand-text">
      ICST Academic Management
      <small>University of Vocational Technology</small>
    </div>
  </div>
  <div class="sidebar-user">
    <div class="avatar"><?= strtoupper(substr($_SESSION['user']??'U',0,2)) ?></div>
    <div class="user-info">
      <div class="name"><?= htmlspecialchars($_SESSION['user']??'User') ?></div>
      <div class="role"><?= htmlspecialchars($_SESSION['role']??'Guest') ?></div>
    </div>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-section">Main</div>
    <a href="index.php" class="nav-item"><i class="fa fa-dashboard"></i> Dashboard</a>

    <?php if ($_SESSION['role']=='Admin'): ?>
    <div class="nav-section">Management</div>
    <a href="student.php" class="nav-item"><i class="fa fa-users"></i> Students</a>
    <a href="teacher.php" class="nav-item"><i class="fa fa-black-tie"></i> Lecturers</a>
    <a href="parent.php" class="nav-item"><i class="fa fa-female"></i> Parents</a>
    <a href="subject.php" class="nav-item"><i class="fa fa-book"></i> Subjects</a>
    <a href="class.php" class="nav-item"><i class="fa fa-bank"></i> Classrooms</a>
    <a href="schedule.php" class="nav-item"><i class="fa fa-calendar"></i> Schedule</a>
    <a href="attendance.php" class="nav-item"><i class="fa fa-check-square-o"></i> Attendance</a>
    <a href="exam.php" class="nav-item"><i class="fa fa-line-chart"></i> Assessments</a>
    <a href="examresults.php" class="nav-item"><i class="fa fa-graduation-cap"></i> Results</a>
    <div class="nav-section">System</div>
    <a href="user.php" class="nav-item"><i class="fa fa-user-plus"></i> Users</a>
    <a href="notice.php" class="nav-item"><i class="fa fa-envelope-o"></i> Notices</a>
    <a href="manage_features.php" class="nav-item"><i class="fa fa-cogs"></i> Features</a>
    <a href="manage_product.php" class="nav-item"><i class="fa fa-cube"></i> Products</a>

    <?php elseif ($_SESSION['role']=='Lecturer'): ?>
    <div class="nav-section">Management</div>
    <a href="student.php" class="nav-item"><i class="fa fa-users"></i> Students</a>
    <a href="teacher.php" class="nav-item"><i class="fa fa-black-tie"></i> Lecturers</a>
    <a href="parent.php" class="nav-item"><i class="fa fa-female"></i> Parents</a>
    <a href="subject.php" class="nav-item"><i class="fa fa-book"></i> Subjects</a>
    <a href="class.php" class="nav-item"><i class="fa fa-bank"></i> Classrooms</a>
    <a href="schedule.php" class="nav-item"><i class="fa fa-calendar"></i> Schedule</a>
    <a href="attendance.php" class="nav-item"><i class="fa fa-check-square-o"></i> Attendance</a>
    <a href="exam.php" class="nav-item"><i class="fa fa-line-chart"></i> Assessments</a>
    <a href="examresults.php" class="nav-item"><i class="fa fa-graduation-cap"></i> Results</a>
    <div class="nav-section">System</div>
    <a href="notice.php" class="nav-item"><i class="fa fa-envelope-o"></i> Notices</a>

    <?php elseif ($_SESSION['role']=='Parent'): ?>
    <div class="nav-section">Portal</div>
    <a href="student-par.php" class="nav-item"><i class="fa fa-users"></i> My Children</a>
    <a href="examresults-par.php" class="nav-item"><i class="fa fa-graduation-cap"></i> Results</a>
    <a href="notice-role.php" class="nav-item"><i class="fa fa-envelope-o"></i> Notices</a>

    <?php elseif ($_SESSION['role']=='Student'): ?>
    <div class="nav-section">Portal</div>
    <a href="schedule-stu.php" class="nav-item"><i class="fa fa-calendar"></i> My Schedule</a>
    <a href="examresults-stu.php" class="nav-item"><i class="fa fa-graduation-cap"></i> My Results</a>
    <a href="notice-role.php" class="nav-item"><i class="fa fa-envelope-o"></i> Notices</a>
    <?php endif; ?>
  </nav>
  <div class="sidebar-footer">
    <a href="profile.php" class="nav-item"><i class="fa fa-user-circle"></i> Profile</a>
    <a href="logout.php" class="nav-item"><i class="fa fa-power-off"></i> Logout</a>
  </div>
</aside>

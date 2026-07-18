<?php
/* dashboard.php — Main user dashboard showing system overview stats, recent activity, and quick actions */
session_start();
include_once 'includes/config.php';
// Redirect unauthenticated users
if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit;
}

// Retrieve dashboard statistics from database
$total_students = db_fetch("SELECT COUNT(*) as c FROM student")['c'] ?? 0;
$total_teachers = db_fetch("SELECT COUNT(*) as c FROM teacher")['c'] ?? 0;
$total_subjects = db_fetch("SELECT COUNT(*) as c FROM subject")['c'] ?? 0;
$total_parents = db_fetch("SELECT COUNT(*) as c FROM parent")['c'] ?? 0;
$total_exams = db_fetch("SELECT COUNT(*) as c FROM exam")['c'] ?? 0;
$total_attendance = db_fetch("SELECT COUNT(*) as c FROM attendancereport")['c'] ?? 0;
$total_classrooms = db_fetch("SELECT COUNT(*) as c FROM classroom")['c'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Dashboard - ICST Academic Management</title>
  <link rel="icon" href="images/user.png">
  <?php include_once 'includes/header.php'; ?>
  <style>
    [data-page-title] { display: none; }
  </style>
</head>
<body>
  <div class="app-layout">
    <?php include_once 'includes/sidebar.php'; ?>
    <div class="main-content">
      <?php include_once 'includes/nav-menu.php'; ?>
      <div class="page-content fade-in" role="main">
        <div class="page-header">
          <h1 data-page-title>Dashboard</h1>
          <p>Welcome back, <?= htmlspecialchars($_SESSION['user'] ?? 'User') ?>! Here's your system overview.</p>
        </div>

        <div class="stat-grid">
          <div class="stat-card slide-up" style="animation-delay:0s">
            <div class="stat-icon"><i class="fa fa-users"></i></div>
            <div class="stat-label">Total Students</div>
            <div class="stat-value"><?= $total_students ?></div>
            <div class="stat-change up"><i class="fa fa-arrow-up"></i> Active</div>
          </div>
          <div class="stat-card slide-up" style="animation-delay:0.05s">
            <div class="stat-icon"><i class="fa fa-black-tie"></i></div>
            <div class="stat-label">Total Lecturers</div>
            <div class="stat-value"><?= $total_teachers ?></div>
            <div class="stat-change up"><i class="fa fa-arrow-up"></i> Active</div>
          </div>
          <div class="stat-card slide-up" style="animation-delay:0.1s">
            <div class="stat-icon"><i class="fa fa-book"></i></div>
            <div class="stat-label">Subjects</div>
            <div class="stat-value"><?= $total_subjects ?></div>
            <div class="stat-change up"><i class="fa fa-arrow-up"></i> Available</div>
          </div>
          <div class="stat-card gold slide-up" style="animation-delay:0.15s">
            <div class="stat-icon"><i class="fa fa-female"></i></div>
            <div class="stat-label">Parents Registered</div>
            <div class="stat-value"><?= $total_parents ?></div>
            <div class="stat-change up"><i class="fa fa-arrow-up"></i> Registered</div>
          </div>
          <div class="stat-card slide-up" style="animation-delay:0.2s">
            <div class="stat-icon"><i class="fa fa-line-chart"></i></div>
            <div class="stat-label">Assessments</div>
            <div class="stat-value"><?= $total_exams ?></div>
            <div class="stat-change up"><i class="fa fa-arrow-up"></i> Scheduled</div>
          </div>
          <div class="stat-card gold slide-up" style="animation-delay:0.25s">
            <div class="stat-icon"><i class="fa fa-check-square-o"></i></div>
            <div class="stat-label">Attendance Records</div>
            <div class="stat-value"><?= $total_attendance ?></div>
            <div class="stat-change up"><i class="fa fa-arrow-up"></i> Logged</div>
          </div>
        </div>

        <?php if ($_SESSION['role'] == 'Lecturer' || $_SESSION['role'] == 'Admin'): ?>
        <div class="mb-4">
          <h3 style="font-size:16px;font-weight:600;margin-bottom:12px;color:var(--text-primary)">Quick Actions</h3>
          <div class="quick-actions">
            <a href="student.php" class="quick-action-item"><i class="fa fa-user-plus"></i> Add Student</a>
            <a href="teacher.php" class="quick-action-item"><i class="fa fa-black-tie"></i> Add Lecturer</a>
            <a href="subject.php" class="quick-action-item"><i class="fa fa-book"></i> Add Subject</a>
            <a href="attendance.php" class="quick-action-item"><i class="fa fa-check-square-o"></i> Mark Attendance</a>
            <a href="exam.php" class="quick-action-item"><i class="fa fa-line-chart"></i> New Assessment</a>
            <a href="notice.php" class="quick-action-item"><i class="fa fa-envelope-o"></i> Send Notice</a>
          </div>
        </div>
        <?php endif; ?>

        <div class="two-column">
          <div class="card">
            <div class="card-header">
              <h3><i class="fa fa-clock-o" style="color:var(--icst-red);margin-right:8px"></i> Recent Activity</h3>
            </div>
            <div class="card-body">
              <?php
              // Fetch recent students and notices for activity feed
              $recent_students = db_fetch_all("SELECT fname, lname, 'Student' as type FROM student ORDER BY sid DESC LIMIT 3");
              $recent_notices = db_fetch_all("SELECT notice, date, 'Notice' as type FROM notice ORDER BY id DESC LIMIT 3");
              $activities = array_merge($recent_students, $recent_notices);
              if (count($activities) > 0): ?>
              <div class="recent-list">
                <?php foreach (array_slice($activities, 0, 5) as $act): ?>
                <div class="recent-item">
                  <div class="recent-icon <?= $act['type']=='Student'?'red':($act['type']=='Notice'?'gold':'green') ?>">
                    <i class="fa <?= $act['type']=='Student'?'fa-user':($act['type']=='Notice'?'fa-envelope':'fa-check') ?>"></i>
                  </div>
                  <div class="recent-content">
                    <div class="title"><?= htmlspecialchars($act['type']=='Student' ? $act['fname'].' '.$act['lname'].' added' : ($act['type']=='Notice' ? substr($act['notice'],0,50).'...' : '')) ?></div>
                    <div class="time"><?= htmlspecialchars($act['date'] ?? 'Recent') ?></div>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
              <?php else: ?>
              <div class="empty-state" style="padding:32px 0">
                <i class="fa fa-inbox" style="font-size:32px"></i>
                <p style="font-size:13px">No recent activity</p>
              </div>
              <?php endif; ?>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h3><i class="fa fa-cogs" style="color:var(--icst-gold);margin-right:8px"></i> System Overview</h3>
            </div>
            <div class="card-body">
              <div class="recent-list">
                <div class="recent-item">
                  <div class="recent-icon red"><i class="fa fa-bank"></i></div>
                  <div class="recent-content">
                    <div class="title">Classrooms</div>
                    <div class="time"><?= $total_classrooms ?> rooms available</div>
                  </div>
                  <span class="badge" style="background:var(--icst-red);color:white;padding:4px 10px;border-radius:10px;font-size:11px"><?= $total_classrooms ?></span>
                </div>
                <div class="recent-item">
                  <div class="recent-icon gold"><i class="fa fa-graduation-cap"></i></div>
                  <div class="recent-content">
                    <div class="title">Assessments Scheduled</div>
                    <div class="time"><?= $total_exams ?> exams planned</div>
                  </div>
                  <span class="badge" style="background:var(--icst-gold);color:#1a1a2e;padding:4px 10px;border-radius:10px;font-size:11px"><?= $total_exams ?></span>
                </div>
                <div class="recent-item">
                  <div class="recent-icon green"><i class="fa fa-check-square-o"></i></div>
                  <div class="recent-content">
                    <div class="title">Attendance Entries</div>
                    <div class="time"><?= $total_attendance ?> records</div>
                  </div>
                  <span class="badge" style="background:#27ae60;color:white;padding:4px 10px;border-radius:10px;font-size:11px"><?= $total_attendance ?></span>
                </div>
                <div class="recent-item">
                  <div class="recent-icon red"><i class="fa fa-users"></i></div>
                  <div class="recent-content">
                    <div class="title">Total Enrolled Students</div>
                    <div class="time">Across all classrooms</div>
                  </div>
                  <span class="badge" style="background:var(--icst-red);color:white;padding:4px 10px;border-radius:10px;font-size:11px"><?= $total_students ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="app-footer">
        ICST Academic Management System &copy; <?= date('Y') ?>. All Rights Reserved.
      </footer>
    </div>
  </div>
  <?php include_once 'includes/footer.php'; ?>
  <script>
    document.getElementById('breadcrumbCurrent').textContent = 'Dashboard';
  </script>
</body>
</html>

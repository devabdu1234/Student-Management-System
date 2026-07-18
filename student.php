<?php
/* student.php — Add, update, delete, and list student records with classroom/parent assignment */
session_start();
include_once 'includes/config.php';
// Redirect unauthenticated or non-admin users
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) {
  header('Location: logout.php');
  exit;
}

$sid = $fname = $lname = $classroom = $email = $dob = $gender = $address = $parent = '';
$message = '';
$is_update = isset($_GET['update']);

if ($is_update) {
  // Fetch existing student for editing
  $row = db_fetch("SELECT * FROM student WHERE sid = ?", [$_GET['update']]);
  if ($row) {
    $sid = $row['sid']; $fname = $row['fname']; $lname = $row['lname'];
    $classroom = $row['classroom']; $email = $row['email'];
    $dob = $row['bday']; $gender = $row['gender'];
    $address = $row['address']; $parent = $row['parent'];
  }
}

// Process form submission for add/update
if (isset($_POST['submit'])) {
  $sid = $_POST['sid']; $fname = $_POST['fname']; $lname = $_POST['lname'];
  $email = $_POST['email']; $classroom = $_POST['classroom'];
  $dob = $_POST['dob']; $gender = $_POST['gender']; $address = $_POST['address'];
  $parent = $_POST['parent'] ?? 0;

  try {
    if ($is_update) {
      // Update existing student record
      db_query("UPDATE student SET fname=?, lname=?, bday=?, address=?, gender=?, parent=?, classroom=?, email=? WHERE sid=?",
        [$fname, $lname, $dob, $address, $gender, $parent, $classroom, $email, $sid]);
      $message = '<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Student updated successfully!</div>';
    } else {
      // Insert new student record
      db_query("INSERT INTO student (sid, fname, lname, bday, address, gender, parent, classroom, email) VALUES (?,?,?,?,?,?,?,?,?)",
        [$sid, $fname, $lname, $dob, $address, $gender, $parent, $classroom, $email]);
      $message = '<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Student added successfully!</div>';
    }
  } catch (Exception $e) {
    $message = '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
  }
}
// Process delete request
if (isset($_GET['delete'])) {
  try { db_query("DELETE FROM student WHERE sid=?", [$_GET['delete']]); $message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Student deleted!</div>'; } catch (Exception $e) { $message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>'; }
}
// Retrieve data for form dropdowns and table listing
$students = db_fetch_all("SELECT * FROM student ORDER BY sid DESC");
$classrooms = db_fetch_all("SELECT * FROM classroom");
$parents = db_fetch_all("SELECT * FROM parent");
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Students - ICST Academic Management</title>
  <link rel="icon" href="images/user.png">
  <?php include_once 'includes/header.php'; ?>
</head>
<body>
  <div class="app-layout">
    <?php include_once 'includes/sidebar.php'; ?>
    <div class="main-content">
      <?php include_once 'includes/nav-menu.php'; ?>
      <div class="page-content fade-in">
        <div class="page-header">
          <h1 data-page-title>Student Management</h1>
          <p>Manage all registered students in the system</p>
        </div>

        <?= $message ?>

        <div class="two-column">
          <div class="card">
            <div class="card-header">
              <h3><i class="fa <?= $is_update ? 'fa-pencil' : 'fa-plus-circle' ?>" style="color:var(--icst-red);margin-right:8px"></i> <?= $is_update ? 'Update Student' : 'Add New Student' ?></h3>
            </div>
            <div class="card-body">
              <form method="post" class="needs-validation" novalidate>
                <div class="form-row">
                  <div class="form-group">
                    <label>Student ID <span class="required">*</span></label>
                    <input name="sid" type="text" class="form-control" value="<?= htmlspecialchars($sid) ?>" <?= $is_update ? 'disabled' : '' ?> required>
                    <div class="form-error"></div>
                  </div>
                  <div class="form-group">
                    <label>Email <span class="required">*</span></label>
                    <input name="email" type="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
                    <div class="form-error"></div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group">
                    <label>First Name <span class="required">*</span></label>
                    <input name="fname" type="text" class="form-control" value="<?= htmlspecialchars($fname) ?>" required>
                    <div class="form-error"></div>
                  </div>
                  <div class="form-group">
                    <label>Last Name <span class="required">*</span></label>
                    <input name="lname" type="text" class="form-control" value="<?= htmlspecialchars($lname) ?>" required>
                    <div class="form-error"></div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group">
                    <label>Date of Birth <span class="required">*</span></label>
                    <input name="dob" type="date" class="form-control" value="<?= htmlspecialchars($dob) ?>" required>
                    <div class="form-error"></div>
                  </div>
                  <div class="form-group">
                    <label>Gender <span class="required">*</span></label>
                    <select name="gender" class="form-control" required>
                      <option value="">Select Gender</option>
                      <option value="Male" <?= $gender=='Male'?'selected':'' ?>>Male</option>
                      <option value="Female" <?= $gender=='Female'?'selected':'' ?>>Female</option>
                    </select>
                    <div class="form-error"></div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Address</label>
                  <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($address) ?></textarea>
                </div>
                <div class="form-row">
                  <div class="form-group">
                    <label>Classroom</label>
                    <select name="classroom" class="form-control">
                      <option value="">Select Classroom</option>
                      <?php foreach ($classrooms as $r): ?>
                      <option value="<?= htmlspecialchars($r['hno']) ?>" <?= $classroom==$r['hno']?'selected':'' ?>><?= htmlspecialchars($r['title']) ?> (<?= htmlspecialchars($r['hno']) ?>)</option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Parent</label>
                    <select name="parent" class="form-control">
                      <option value="0">No Parent</option>
                      <?php foreach ($parents as $r): ?>
                      <option value="<?= $r['pid'] ?>" <?= $parent==$r['pid']?'selected':'' ?>><?= htmlspecialchars($r['fname'].' '.$r['lname']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="form-actions">
                  <button type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?= $is_update ? 'Update Student' : 'Add Student' ?></button>
                  <?php if ($is_update): ?>
                  <a href="student.php" class="btn btn-secondary"><i class="fa fa-times"></i> Cancel</a>
                  <?php endif; ?>
                </div>
              </form>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h3><i class="fa fa-list" style="color:var(--icst-red);margin-right:8px"></i> All Students</h3>
              <span class="badge" style="background:var(--icst-red);color:white;padding:4px 12px;border-radius:10px"><?= count($students) ?></span>
            </div>
            <div class="card-body" style="padding:0">
              <div class="table-toolbar">
                <div class="search-box">
                  <i class="fa fa-search"></i>
                  <input type="text" data-table-search="studentTable" placeholder="Search students...">
                </div>
              </div>
              <div class="table-container" style="border:none;border-radius:0">
                <div class="desktop-table">
                  <table id="studentTable">
                    <thead>
                      <tr><th>SID</th><th>Name</th><th>Email</th><th>Classroom</th><th>Gender</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                      <?php if (count($students) > 0): ?>
                      <?php foreach ($students as $r): ?>
                      <tr>
                        <td><?= htmlspecialchars($r['sid']) ?></td>
                        <td><strong><?= htmlspecialchars($r['fname'].' '.$r['lname']) ?></strong></td>
                        <td><?= htmlspecialchars($r['email']) ?></td>
                        <td><?= htmlspecialchars($r['classroom']) ?></td>
                        <td><?= htmlspecialchars($r['gender']) ?></td>
                        <td class="actions">
                          <a href="student.php?update=<?= urlencode($r['sid']) ?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a>
                          <a href="student.php?delete=<?= urlencode($r['sid']) ?>" class="btn btn-sm btn-danger" data-confirm="Delete this student?"><i class="fa fa-trash"></i></a>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                      <?php else: ?>
                      <tr><td colspan="6" class="table-empty"><i class="fa fa-users"></i> No students found</td></tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
                <div class="mobile-card-view">
                  <?php if (count($students) > 0): ?>
                  <?php foreach ($students as $r): ?>
                  <div class="mobile-card-item">
                    <div class="mci-row"><span class="mci-label">Name</span><span class="mci-value"><strong><?= htmlspecialchars($r['fname'].' '.$r['lname']) ?></strong></span></div>
                    <div class="mci-row"><span class="mci-label">ID</span><span class="mci-value"><?= htmlspecialchars($r['sid']) ?></span></div>
                    <div class="mci-row"><span class="mci-label">Email</span><span class="mci-value"><?= htmlspecialchars($r['email']) ?></span></div>
                    <div class="mci-row"><span class="mci-label">Classroom</span><span class="mci-value"><?= htmlspecialchars($r['classroom']) ?></span></div>
                    <div class="mci-row"><span class="mci-label">Gender</span><span class="mci-value"><?= htmlspecialchars($r['gender']) ?></span></div>
                    <div class="mci-actions">
                      <a href="student.php?update=<?= urlencode($r['sid']) ?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a>
                      <a href="student.php?delete=<?= urlencode($r['sid']) ?>" class="btn btn-sm btn-danger" data-confirm="Delete this student?"><i class="fa fa-trash"></i> Delete</a>
                    </div>
                  </div>
                  <?php endforeach; ?>
                  <?php else: ?>
                  <div class="empty-state"><i class="fa fa-users"></i><h3>No Students</h3><p>Add your first student to get started.</p></div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="app-footer">ICST Academic Management System &copy; <?= date('Y') ?></footer>
    </div>
  </div>
  <?php include_once 'includes/footer.php'; ?>
  <script>
    document.getElementById('breadcrumbCurrent').textContent = 'Student Management';
  </script>
</body>
</html>

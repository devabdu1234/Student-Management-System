<?php
/* examresults.php — Record and manage student exam results with marks and grades */
session_start(); include_once 'includes/config.php';
// Redirect unauthenticated or non-admin users
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: logout.php'); exit; }
$message='';
// Fetch exams and students for dropdown selections
$exams=db_fetch_all("SELECT e.*, sub.title as sub_title FROM exam e LEFT JOIN subject sub ON e.subject=sub.sid");
$students=db_fetch_all("SELECT * FROM student ORDER BY fname");
// Process form submission to add a result
if(isset($_POST['submit'])){
  $exam=$_POST['exam'];$student=$_POST['student'];$marks=$_POST['marks'];$grade=$_POST['grade'];
  try{db_query("INSERT INTO examresult(exam,student,marks,grade) VALUES(?,?,?,?)",[$exam,$student,$marks,$grade]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Result added!</div>';}
  catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}
}
// Process delete request
if(isset($_GET['delete'])){try{db_query("DELETE FROM examresult WHERE id=?",[$_GET['delete']]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Result deleted!</div>';}catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}}
// Retrieve all results with exam subject and student name
$rows=db_fetch_all("SELECT r.*, e.subject as exam_subject, CONCAT(s.fname,' ',s.lname) as student_name FROM examresult r LEFT JOIN exam e ON r.exam=e.id LEFT JOIN student s ON r.student=s.sid ORDER BY r.exam DESC");
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Results - ICST Academic Management</title><link rel="icon" href="images/user.png">
<?php include_once 'includes/header.php';?></head><body>
<div class="app-layout"><?php include_once 'includes/sidebar.php';?>
<div class="main-content"><?php include_once 'includes/nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Exam Results</h1><p>Record and manage student assessment results</p></div>
<?=$message?>
<div class="two-column">
<div class="card"><div class="card-header"><h3><i class="fa fa-plus-circle" style="color:var(--icst-red);margin-right:8px"></i> Add Result</h3></div>
<div class="card-body"><form method="post" class="needs-validation" novalidate>
<div class="form-group"><label>Exam <span class="required">*</span></label><select name="exam" class="form-control" required><option value="">Select Exam</option><?php foreach($exams as $r):?><option value="<?=$r['id']?>"><?=htmlspecialchars(($r['sub_title']??$r['subject']).' - '.$r['date'])?></option><?php endforeach;?></select><div class="form-error"></div></div>
<div class="form-group"><label>Student <span class="required">*</span></label><select name="student" class="form-control" required><option value="">Select Student</option><?php foreach($students as $r):?><option value="<?=htmlspecialchars($r['sid'])?>"><?=htmlspecialchars($r['fname'].' '.$r['lname'])?></option><?php endforeach;?></select><div class="form-error"></div></div>
<div class="form-row"><div class="form-group"><label>Marks <span class="required">*</span></label><input name="marks" type="number" class="form-control" required><div class="form-error"></div></div>
<div class="form-group"><label>Grade</label><select name="grade" class="form-control"><option value="">Select Grade</option><option>A+</option><option>A</option><option>A-</option><option>B+</option><option>B</option><option>B-</option><option>C+</option><option>C</option><option>C-</option><option>D+</option><option>D</option><option>E</option></select></div></div>
<div class="form-actions"><button type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fa fa-save"></i> Add Result</button></div></form></div></div>
<div class="card"><div class="card-header"><h3><i class="fa fa-list" style="color:var(--icst-red);margin-right:8px"></i> All Results</h3><span class="badge" style="background:var(--icst-red);color:white;padding:4px 12px;border-radius:10px"><?=count($rows)?></span></div>
<div class="card-body" style="padding:0"><div class="table-toolbar"><div class="search-box"><i class="fa fa-search"></i><input type="text" data-table-search="resultTable" placeholder="Search..."></div></div>
<div class="table-container" style="border:none;border-radius:0">
<div class="desktop-table"><table id="resultTable"><thead><tr><th>Exam</th><th>Student</th><th>Marks</th><th>Grade</th><th>Actions</th></tr></thead>
<tbody><?php if(count($rows)>0):?><?php foreach($rows as $r):?><tr><td><?=htmlspecialchars($r['exam_subject']??$r['exam'])?></td><td><strong><?=htmlspecialchars($r['student_name']??$r['student'])?></strong></td><td><?=$r['marks']?></td><td><span class="badge" style="background:var(--icst-gold);color:#1a1a2e;padding:4px 10px;border-radius:10px"><?=htmlspecialchars($r['grade'])?></span></td>
<td class="actions"><a href="examresults.php?delete=<?=$r['id']?>" class="btn btn-sm btn-danger" data-confirm="Delete this result?"><i class="fa fa-trash"></i></a></td></tr><?php endforeach;?><?php else:?><tr><td colspan="5" class="table-empty"><i class="fa fa-graduation-cap"></i> No results recorded</td></tr><?php endif;?></tbody></table></div>
<div class="mobile-card-view"><?php if(count($rows)>0):?><?php foreach($rows as $r):?><div class="mobile-card-item">
<div class="mci-row"><span class="mci-label">Exam</span><span class="mci-value"><strong><?=htmlspecialchars($r['exam_subject']??$r['exam'])?></strong></span></div>
<div class="mci-row"><span class="mci-label">Student</span><span class="mci-value"><?=htmlspecialchars($r['student_name']??$r['student'])?></span></div>
<div class="mci-row"><span class="mci-label">Marks</span><span class="mci-value"><?=$r['marks']?></span></div>
<div class="mci-row"><span class="mci-label">Grade</span><span class="mci-value"><strong><?=htmlspecialchars($r['grade'])?></strong></span></div>
<div class="mci-actions"><a href="examresults.php?delete=<?=$r['id']?>" class="btn btn-sm btn-danger" data-confirm="Delete?"><i class="fa fa-trash"></i> Delete</a></div></div><?php endforeach;?><?php else:?><div class="empty-state"><i class="fa fa-graduation-cap"></i><h3>No Results</h3></div><?php endif;?></div></div></div></div></div></div>
<footer class="app-footer">ICST Academic Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'includes/footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Exam Results';</script>
</body></html>

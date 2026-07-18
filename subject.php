<?php
/* subject.php — Add, update, delete, and list academic subjects */
session_start(); include_once 'includes/config.php';
// Redirect unauthenticated or non-admin users
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: logout.php'); exit; }
$sid=$title=$description=''; $message=''; $is_update=isset($_GET['update']);
if($is_update){
  // Fetch existing subject for editing
  $r=db_fetch("SELECT * FROM subject WHERE sid=?",[$_GET['update']]);if($r){$sid=$r['sid'];$title=$r['title'];$description=$r['description'];}}
if(isset($_POST['submit'])){
  $sid=$_POST['sid'];$title=$_POST['title'];$description=$_POST['description'];
  try{
    if($is_update){
      // Update existing subject
      db_query("UPDATE subject SET title=?,description=? WHERE sid=?",[$title,$description,$sid]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Subject updated!</div>';}
    else{
      // Insert new subject
      db_query("INSERT INTO subject(sid,title,description) VALUES(?,?,?)",[$sid,$title,$description]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Subject added!</div>';}
  }catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}
}
// Process delete request
if(isset($_GET['delete'])){try{db_query("DELETE FROM subject WHERE sid=?",[$_GET['delete']]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Subject deleted!</div>';}catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}}
// Retrieve all subjects for table listing
$rows=db_fetch_all("SELECT * FROM subject ORDER BY sid DESC");
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Subjects - Student Management System</title><link rel="icon" href="images/user.png">
<?php include_once 'includes/header.php';?></head><body>
<div class="app-layout"><?php include_once 'includes/sidebar.php';?>
<div class="main-content"><?php include_once 'includes/nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Subject Management</h1><p>Manage academic subjects offered</p></div>
<?=$message?>
<div class="two-column">
<div class="card"><div class="card-header"><h3><i class="fa <?=$is_update?'fa-pencil':'fa-plus-circle'?>" style="color:var(--icst-red);margin-right:8px"></i> <?=$is_update?'Update Subject':'Add Subject'?></h3></div>
<div class="card-body"><form method="post" class="needs-validation" novalidate>
<div class="form-group"><label>Subject Code <span class="required">*</span></label><input name="sid" type="text" class="form-control" value="<?=htmlspecialchars($sid)?>" <?=$is_update?'disabled':''?> required><div class="form-error"></div></div>
<div class="form-group"><label>Subject Title <span class="required">*</span></label><input name="title" type="text" class="form-control" value="<?=htmlspecialchars($title)?>" required><div class="form-error"></div></div>
<div class="form-group"><label>Syllabus Details</label><textarea name="description" class="form-control" rows="6"><?=htmlspecialchars($description)?></textarea></div>
<div class="form-actions"><button type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?=$is_update?'Update Subject':'Add Subject'?></button>
<?php if($is_update):?><a href="subject.php" class="btn btn-secondary"><i class="fa fa-times"></i> Cancel</a><?php endif;?></div></form></div></div>
<div class="card"><div class="card-header"><h3><i class="fa fa-list" style="color:var(--icst-red);margin-right:8px"></i> All Subjects</h3><span class="badge" style="background:var(--icst-red);color:white;padding:4px 12px;border-radius:10px"><?=count($rows)?></span></div>
<div class="card-body" style="padding:0"><div class="table-toolbar"><div class="search-box"><i class="fa fa-search"></i><input type="text" data-table-search="subjectTable" placeholder="Search subjects..."></div></div>
<div class="table-container" style="border:none;border-radius:0">
<div class="desktop-table"><table id="subjectTable"><thead><tr><th>Code</th><th>Title</th><th>Description</th><th>Actions</th></tr></thead>
<tbody><?php if(count($rows)>0):?><?php foreach($rows as $r):?><tr><td><?=htmlspecialchars($r['sid'])?></td><td><strong><?=htmlspecialchars($r['title'])?></strong></td><td style="max-width:300px"><?=htmlspecialchars(substr($r['description'],0,100))?></td>
<td class="actions"><a href="subject.php?update=<?=urlencode($r['sid'])?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a><a href="subject.php?delete=<?=urlencode($r['sid'])?>" class="btn btn-sm btn-danger" data-confirm="Delete this subject?"><i class="fa fa-trash"></i></a></td></tr><?php endforeach;?><?php else:?><tr><td colspan="4" class="table-empty"><i class="fa fa-book"></i> No subjects found</td></tr><?php endif;?></tbody></table></div>
<div class="mobile-card-view"><?php if(count($rows)>0):?><?php foreach($rows as $r):?><div class="mobile-card-item">
<div class="mci-row"><span class="mci-label">Code</span><span class="mci-value"><strong><?=htmlspecialchars($r['sid'])?></strong></span></div>
<div class="mci-row"><span class="mci-label">Title</span><span class="mci-value"><?=htmlspecialchars($r['title'])?></span></div>
<div class="mci-row"><span class="mci-label">Description</span><span class="mci-value"><?=htmlspecialchars(substr($r['description'],0,80))?></span></div>
<div class="mci-actions"><a href="subject.php?update=<?=urlencode($r['sid'])?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a><a href="subject.php?delete=<?=urlencode($r['sid'])?>" class="btn btn-sm btn-danger" data-confirm="Delete?"><i class="fa fa-trash"></i> Delete</a></div></div><?php endforeach;?><?php else:?><div class="empty-state"><i class="fa fa-book"></i><h3>No Subjects</h3></div><?php endif;?></div></div></div></div></div></div>
<footer class="app-footer">Student Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'includes/footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Subject Management';</script>
</body></html>

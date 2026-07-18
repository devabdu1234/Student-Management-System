<?php
/* notice.php — Send and manage broadcast notices targeted to specific audiences */
session_start(); include_once 'includes/config.php';
// Redirect unauthenticated or non-admin users
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Lecturer' && $_SESSION['role'] != 'Admin')) { header('Location: logout.php'); exit; }
$message='';
// Process delete request
if(isset($_GET['delete'])){try{db_query("DELETE FROM notice WHERE id=?",[$_GET['delete']]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Notice deleted!</div>';}catch(Exception $e){}}
// Process form submission to send a new notice
if(isset($_POST['submit'])){
  $notice=$_POST['notice'];$odience=$_POST['odience'];
  try{db_query("INSERT INTO notice(notice,odience,`date`) VALUES(?,?,NOW())",[$notice,$odience]);$message='<div class="alert alert-success alert-auto"><i class="fa fa-check-circle"></i> Notice sent!</div>';}
  catch(Exception $e){$message='<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.htmlspecialchars($e->getMessage()).'</div>';}
}
// Retrieve all notices for history table
$rows=db_fetch_all("SELECT * FROM notice ORDER BY id DESC");
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Notices - Student Management System</title><link rel="icon" href="images/user.png">
<?php include_once 'includes/header.php';?></head><body>
<div class="app-layout"><?php include_once 'includes/sidebar.php';?>
<div class="main-content"><?php include_once 'includes/nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Notice Management</h1><p>Send notices to students and parents</p></div>
<?=$message?>
<div class="two-column">
<div class="card"><div class="card-header"><h3><i class="fa fa-plus-circle" style="color:var(--icst-red);margin-right:8px"></i> Send Notice</h3></div>
<div class="card-body"><form method="post" class="needs-validation" novalidate>
<div class="form-group"><label>Notice Content <span class="required">*</span></label><textarea name="notice" class="form-control" rows="8" required></textarea><div class="form-error"></div></div>
<div class="form-group"><label>Audience</label><select name="odience" class="form-control"><option value="All">All</option><option value="Student">Students Only</option><option value="Parent">Parents Only</option></select></div>
<div class="form-actions"><button type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fa fa-send"></i> Send Notice</button></div></form></div></div>
<div class="card"><div class="card-header"><h3><i class="fa fa-list" style="color:var(--icst-red);margin-right:8px"></i> Notice History</h3><span class="badge" style="background:var(--icst-red);color:white;padding:4px 12px;border-radius:10px"><?=count($rows)?></span></div>
<div class="card-body" style="padding:0"><div class="table-toolbar"><div class="search-box"><i class="fa fa-search"></i><input type="text" data-table-search="noticeTable" placeholder="Search..."></div></div>
<div class="table-container" style="border:none;border-radius:0">
<div class="desktop-table"><table id="noticeTable"><thead><tr><th>#</th><th>Notice</th><th>Audience</th><th>Date</th><th>Action</th></tr></thead>
<tbody><?php if(count($rows)>0):?><?php foreach($rows as $r):?><tr><td><?=$r['id']?></td><td><?=htmlspecialchars(substr($r['notice'],0,100))?></td><td><span class="badge" style="background:var(--icst-gold);color:#1a1a2e;padding:4px 10px"><?=htmlspecialchars($r['odience'])?></span></td><td><?=htmlspecialchars($r['date'])?></td>
<td><a href="notice.php?delete=<?=$r['id']?>" class="btn btn-sm btn-danger" data-confirm="Delete this notice?"><i class="fa fa-trash"></i></a></td></tr><?php endforeach;?><?php else:?><tr><td colspan="5" class="table-empty"><i class="fa fa-envelope-o"></i> No notices sent</td></tr><?php endif;?></tbody></table></div>
<div class="mobile-card-view"><?php if(count($rows)>0):?><?php foreach($rows as $r):?><div class="mobile-card-item">
<div class="mci-row"><span class="mci-label">Notice</span><span class="mci-value"><?=htmlspecialchars(substr($r['notice'],0,80))?></span></div>
<div class="mci-row"><span class="mci-label">Date</span><span class="mci-value"><?=htmlspecialchars($r['date'])?></span></div>
<div class="mci-actions"><a href="notice.php?delete=<?=$r['id']?>" class="btn btn-sm btn-danger" data-confirm="Delete?"><i class="fa fa-trash"></i> Delete</a></div></div><?php endforeach;?><?php else:?><div class="empty-state"><i class="fa fa-envelope-o"></i><h3>No Notices</h3></div><?php endif;?></div></div></div></div></div></div>
<footer class="app-footer">Student Management System &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'includes/footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Notice Management';</script>
</body></html>

<?php
/* notice-role.php — Display notices filtered by the current user's role */
session_start(); include_once 'includes/config.php';
// Redirect unauthenticated users
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
$role=$_SESSION['role']??'';
// Fetch notices targeted at this role or 'All'
$rows=db_fetch_all("SELECT * FROM notice WHERE odience='All' OR odience=? ORDER BY id DESC",[$role]);
?>
<!DOCTYPE html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Notices - ICST</title><link rel="icon" href="images/user.png"><?php include_once 'includes/header.php';?></head><body>
<div class="app-layout"><?php include_once 'includes/sidebar.php';?>
<div class="main-content"><?php include_once 'includes/nav-menu.php';?>
<div class="page-content fade-in">
<div class="page-header"><h1 data-page-title>Notices</h1><p>Official announcements and notices</p></div>
<?php if(count($rows)>0):?><?php foreach($rows as $r):?><div class="card mb-4"><div class="card-body">
<div class="d-flex justify-between align-center"><h5 style="font-weight:600"><?=htmlspecialchars(substr($r['notice'],0,80))?></h5>
<small style="color:var(--text-muted)"><?=htmlspecialchars($r['date'])?></small></div>
<hr><p style="color:var(--text-secondary);font-size:14px;white-space:pre-wrap"><?=htmlspecialchars($r['notice'])?></p>
<span class="badge" style="background:var(--icst-gold);color:#1a1a2e;padding:4px 10px;border-radius:10px;font-size:11px"><?=htmlspecialchars($r['odience'])?></span>
</div></div><?php endforeach;?>
<?php else:?><div class="empty-state"><i class="fa fa-envelope-o"></i><h3>No Notices</h3><p>There are no notices for you at this time.</p></div><?php endif;?></div>
<footer class="app-footer">ICST Academic Management &copy; <?=date('Y')?></footer></div></div>
<?php include_once 'includes/footer.php';?>
<script>document.getElementById('breadcrumbCurrent').textContent='Notices';</script>
</body></html>

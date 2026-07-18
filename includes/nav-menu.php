<div class="app-header">
  <div class="header-left">
    <button id="hamburger" class="hamburger" aria-label="Toggle navigation">
      <i class="fa fa-bars"></i>
    </button>
    <nav class="breadcrumb-nav" aria-label="Breadcrumb">
      <a href="index.php">Home</a>
      <span class="separator">/</span>
      <span class="current" id="breadcrumbCurrent">Dashboard</span>
    </nav>
  </div>
  <div class="header-right">
    <button id="themeToggle" class="theme-toggle" aria-label="Toggle theme">
      <i class="fa fa-moon-o"></i>
    </button>
    <div class="header-user">
      <div class="avatar"><?= strtoupper(substr($_SESSION['user']??'U',0,2)) ?></div>
      <span class="name"><?= htmlspecialchars($_SESSION['user']??'User') ?></span>
    </div>
  </div>
</div>

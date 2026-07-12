// ===== ICST Academic Management System =====
(function() {
  'use strict';

  // ---------- Dark Mode ----------
  const themeToggle = document.getElementById('themeToggle');
  const html = document.documentElement;
  const savedTheme = localStorage.getItem('icst-theme') || 'light';
  html.setAttribute('data-theme', savedTheme);

  if (themeToggle) {
    const icon = themeToggle.querySelector('i');
    if (savedTheme === 'dark') {
      icon.className = 'fa fa-sun-o';
    }
    themeToggle.addEventListener('click', function() {
      const current = html.getAttribute('data-theme');
      const next = current === 'dark' ? 'light' : 'dark';
      html.setAttribute('data-theme', next);
      localStorage.setItem('icst-theme', next);
      const i = this.querySelector('i');
      i.className = next === 'dark' ? 'fa fa-sun-o' : 'fa fa-moon-o';
    });
  }

  // ---------- Sidebar ----------
  const hamburger = document.getElementById('hamburger');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');

  function toggleSidebar() {
    sidebar.classList.toggle('open');
    if (overlay) overlay.classList.toggle('show');
  }
  function closeSidebar() {
    sidebar.classList.remove('open');
    if (overlay) overlay.classList.remove('show');
  }
  if (hamburger) hamburger.addEventListener('click', toggleSidebar);
  if (overlay) overlay.addEventListener('click', closeSidebar);

  // Close sidebar on nav click (mobile)
  if (sidebar) {
    sidebar.querySelectorAll('.nav-item').forEach(function(item) {
      item.addEventListener('click', function() {
        if (window.innerWidth <= 1024) closeSidebar();
      });
    });
  }

  // ---------- Active Nav ----------
  const currentPage = window.location.pathname.split('/').pop() || 'index.php';
  document.querySelectorAll('.nav-item').forEach(function(item) {
    const href = item.getAttribute('href');
    if (href && href === currentPage) {
      item.classList.add('active');
    }
  });

  // ---------- Table Search ----------
  document.querySelectorAll('[data-table-search]').forEach(function(input) {
    input.addEventListener('keyup', function() {
      const q = this.value.toLowerCase();
      const tableId = this.getAttribute('data-table-search');
      const table = document.getElementById(tableId);
      if (!table) return;
      const rows = table.querySelectorAll('tbody tr');
      rows.forEach(function(row) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.indexOf(q) > -1 ? '' : 'none';
      });
    });
  });

  // ---------- Form Validation ----------
  document.querySelectorAll('.needs-validation').forEach(function(form) {
    form.addEventListener('submit', function(e) {
      let valid = true;
      this.querySelectorAll('[required]').forEach(function(input) {
        const error = input.parentElement.querySelector('.form-error');
        if (!input.value.trim()) {
          input.classList.add('error');
          input.classList.remove('success');
          if (error) { error.textContent = 'This field is required'; error.classList.add('show'); }
          valid = false;
        } else {
          input.classList.remove('error');
          input.classList.add('success');
          if (error) { error.classList.remove('show'); }
          // Email validation
          if (input.type === 'email') {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!re.test(input.value.trim())) {
              input.classList.add('error');
              if (error) { error.textContent = 'Please enter a valid email'; error.classList.add('show'); }
              valid = false;
            }
          }
        }
      });
      if (!valid) e.preventDefault();
    });

    // Real-time validation
    form.querySelectorAll('[required]').forEach(function(input) {
      input.addEventListener('blur', function() {
        const error = this.parentElement.querySelector('.form-error');
        if (!this.value.trim()) {
          this.classList.add('error');
          this.classList.remove('success');
          if (error) { error.textContent = 'This field is required'; error.classList.add('show'); }
        } else {
          this.classList.remove('error');
          this.classList.add('success');
          if (error) error.classList.remove('show');
        }
      });
    });
  });

  // ---------- Auto-hide alerts ----------
  document.querySelectorAll('.alert-auto').forEach(function(alert) {
    setTimeout(function() {
      alert.style.transition = 'opacity 0.3s';
      alert.style.opacity = '0';
      setTimeout(function() { alert.remove(); }, 300);
    }, 3000);
  });

  // ---------- Confirm deletes ----------
  document.querySelectorAll('[data-confirm]').forEach(function(el) {
    el.addEventListener('click', function(e) {
      if (!confirm(this.getAttribute('data-confirm') || 'Are you sure?')) {
        e.preventDefault();
      }
    });
  });

  // ---------- Breadcrumb auto-set ----------
  const pageTitle = document.querySelector('[data-page-title]');
  const breadcrumbCurrent = document.getElementById('breadcrumbCurrent');
  if (pageTitle && breadcrumbCurrent) {
    breadcrumbCurrent.textContent = pageTitle.textContent;
  }

  // ---------- Responsive table to mobile card ----------
  function handleMobileTables() {
    document.querySelectorAll('[data-mobile-card]').forEach(function(table) {
      const container = table.parentElement;
      const cardView = container.querySelector('.mobile-card-view');
      if (!cardView) return;
      const isMobile = window.innerWidth <= 768;
      table.style.display = isMobile ? 'none' : '';
      cardView.style.display = isMobile ? 'block' : 'none';
    });
  }
  handleMobileTables();
  window.addEventListener('resize', handleMobileTables);

})();

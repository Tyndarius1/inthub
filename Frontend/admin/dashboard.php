
 
<?php include '../../frontend/admin/include/nav.php' ?>

  <!-- Main Content -->
  <div id="main-content">
    <header id="header">
      <h2 id="page-title">Dashboard</h2>
      <button id="toggleSidebar" class="btn-toggle"><i class="bi bi-list"></i></button>
    </header>

    <div class="row g-4">
      <div class="col-12 col-md-6 col-lg-3">
        <div class="card-stats">
          <h4>Registered Students</h4>
          <div class="number" id="studentsCount">--</div>
          <i class="bi bi-people-fill"></i>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-3">
        <div class="card-stats">
          <h4>Registered Employers</h4>
          <div class="number" id="employersCount">--</div>
          <i class="bi bi-building"></i>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-3">
        <div class="card-stats">
          <h4>Internships Posted</h4>
          <div class="number" id="internshipsCount">--</div>
          <i class="bi bi-briefcase-fill"></i>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-3">
        <div class="card-stats">
          <h4>Applications Submitted</h4>
          <div class="number" id="applicationsCount">--</div>
          <i class="bi bi-card-checklist"></i>
        </div>
      </div>
    </div>

    <footer>&copy; <span id="currentYear"></span> Internship Hub — Crafted with ❤️</footer>
  </div>

 <script src="/assets/js/admin/sidebar.js"></script>
  
  
  
</body>
</html>

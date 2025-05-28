 function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const mainContent = document.getElementById('main-content');

      if (window.innerWidth > 768) {
        sidebar.classList.toggle('hide');
        mainContent.classList.toggle('full');
      } else {
        sidebar.classList.toggle('show');
      }
    }

    // Optional: Close sidebar on resize if necessary
    window.addEventListener('resize', () => {
      const sidebar = document.getElementById('sidebar');
      const mainContent = document.getElementById('main-content');

      if (window.innerWidth > 768) {
        sidebar.classList.remove('show');
      } else {
        sidebar.classList.remove('hide');
        mainContent.classList.remove('full');
      }
    });

    document.addEventListener('click', function (e) {
  const sidebar = document.getElementById('sidebar');
  const toggleBtn = document.querySelector('.toggle-btn');

  if (
    window.innerWidth <= 768 &&
    sidebar.classList.contains('show') &&
    !sidebar.contains(e.target) &&
    !toggleBtn.contains(e.target)
  ) {
    sidebar.classList.remove('show');
  }
});

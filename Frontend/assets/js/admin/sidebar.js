const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('main-content');
const toggleBtn = document.getElementById('toggleSidebar');

toggleBtn.addEventListener('click', () => {
  if (window.innerWidth > 768) {
    // Desktop behavior: collapse sidebar width
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('collapsed');
  } else {
    // Mobile behavior: show/hide sidebar by sliding in/out
    sidebar.classList.toggle('show');
  }
});

  

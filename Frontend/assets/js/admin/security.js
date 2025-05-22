// security.js

if (typeof Swal === 'undefined') {
  const script = document.createElement('script');
  script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
  script.onload = checkAuth;
  document.head.appendChild(script);
} else {
  checkAuth();
}

function checkAuth() {
  const token = localStorage.getItem('admin_token');

  if (!token) {
    Swal.fire({
      icon: 'warning',
      title: 'Access Denied',
      text: 'You must log in first to access the admin panel.',
      confirmButtonText: 'Go to Login'
    }).then(() => {
      window.location.href = '/admin/index.php'; // or your actual login page
    });
  }
}

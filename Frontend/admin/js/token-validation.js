
  const token = localStorage.getItem('admin_token');
  const expiry = localStorage.getItem('token_expiry');

  if (!token || (expiry && Date.now() > expiry)) {
    alert('Session expired or not logged in. Redirecting to login...');
    localStorage.removeItem('admin_token');
    localStorage.removeItem('token_expiry');
    window.location.href = '/admin/login.php';
  }

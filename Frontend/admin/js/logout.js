 function logout() {
      localStorage.removeItem('admin_token');
      localStorage.removeItem('token_expiry');
      window.location.href = '/admin/login.php';
    }
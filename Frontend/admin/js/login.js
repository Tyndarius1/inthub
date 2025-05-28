 document.getElementById('loginForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      const message = document.getElementById('message');

      fetch('http://127.0.0.1:8000/api/admin/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ email, password })
      })
      .then(res => res.json())
      .then(data => {
        if (data.token) {
          localStorage.setItem('admin_token', data.token);
          // Optional: Set expiry (1 hour)
          localStorage.setItem('token_expiry', Date.now() + 3600 * 1000);

          message.style.color = 'green';
          message.textContent = 'Login successful! Redirecting...';
          setTimeout(() => {
            window.location.href = '/admin/dashboard.php';
          }, 1000);
        } else {
          message.style.color = 'red';
          message.textContent = data.message || 'Login failed.';
        }
      })
      .catch(err => {
        message.style.color = 'red';
        message.textContent = 'Server error.';
        console.error(err);
      });
    });
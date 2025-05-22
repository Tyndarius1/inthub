    const form = document.getElementById('adminLoginForm');
    const message = document.getElementById('message');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;

      message.textContent = ''; 
      message.className = '';

      try {
        const response = await fetch('http://127.0.0.1:8000/api/admin/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({ email, password })
        });

        const data = await response.json();

        if (!response.ok) {
          throw new Error(data.message || 'Login failed');
        }

        message.textContent = 'Login successful!';
        message.className = 'success';

        localStorage.setItem('admin_token', data.token);
        setTimeout(() => {
          window.location.href = '/admin/dashboard.php';
        }, 1000);
      } catch (err) {
        message.textContent = err.message;
        message.className = 'error';
      }
    });
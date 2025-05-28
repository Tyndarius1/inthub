<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
<link rel="stylesheet" href="/admin/css/login.css">
</head>
<body>
  <div class="login-box">
    <h2>Admin Login</h2>
    <form id="loginForm">
      <div class="form-group">
        <label>Email</label>
        <input type="email" id="email" required>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" id="password" required>
      </div>
      <button type="submit">Log In</button>
      <div class="message" id="message"></div>
          <button type="button" id="qrLoginBtn">Log in with QR</button>
    </form>
  </div>
  <script src="/admin/js/login.js"></script>
</body>
</html>

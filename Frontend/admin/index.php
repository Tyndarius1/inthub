<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Interactive Admin Login</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/admin/login.css">

</head>
<body>
<div class="container" id="mouse-area">
<div class="login-card" id="login-card">
<img src="/assets/images/logo.png" alt="Logo" class="logo" />
<h2>Admin Login</h2>
<div id="message"></div>
<form id="adminLoginForm">
<input type="email" id="email" placeholder="Email" required />
<input type="password" id="password" placeholder="Password" required />
<button type="submit">Login</button>
</form>
</div>
</div>

<script src="/assets/js/admin/login.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Internship Hub Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

</head>
<style>
    
    :root {
      --sidebar-bg: rgba(27, 38, 49, 0.8);
      --sidebar-blur: blur(8px);
      --sidebar-hover: rgba(255, 255, 255, 0.05);
      --primary-color: #4e8cff;
      --accent-color: #f39c12;
      --text-color: #212f3c;
      --text-light: #95a5a6;
      --bg-light: #f4f6f9;
      --card-bg: white;
      --border-radius: 16px;
      --transition-speed: 0.3s;
      --shadow: rgba(0, 0, 0, 0.1);
      --gradient: linear-gradient(135deg, #4e8cff 0%, #70a1ff 100%);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Inter', sans-serif;
    }

    body {
      display: flex;
      min-height: 100vh;
      background-color: var(--bg-light);
      overflow-x: hidden;
      color: var(--text-color);
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 260px;
      background: var(--sidebar-bg);
      backdrop-filter: var(--sidebar-blur);
      color: white;
      transition: transform var(--transition-speed) ease;
      z-index: 1000;
      display: flex;
      flex-direction: column;
      transform: translateX(-100%);
      border-right: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar.show {
      transform: translateX(0);
    }

    @media (min-width: 769px) {
      .sidebar {
        transform: translateX(0);
      }
      .sidebar.hide {
        transform: translateX(-100%);
      }
    }

    .sidebar .logo {
      text-align: center;
      font-size: 1.8rem;
      font-weight: 700;
      padding: 24px 16px;
      background: var(--gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .sidebar nav a {
      color: white;
      padding: 16px 28px;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 14px;
      font-size: 1rem;
      transition: background var(--transition-speed), padding-left var(--transition-speed);
    }

    .sidebar nav a:hover,
    .sidebar nav a.active {
      background-color: var(--sidebar-hover);
      padding-left: 34px;
    }

    .main-content {
      flex-grow: 1;
      margin-left: 260px;
      padding: 32px;
      transition: margin-left var(--transition-speed) ease;
      width: 100%;
    }

    .main-content.full {
      margin-left: 0;
    }

    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
        padding: 24px;
      }
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: white;
      padding: 16px 24px;
      box-shadow: 0 2px 10px var(--shadow);
      margin-bottom: 32px;
      border-radius: var(--border-radius);
    }

    .toggle-btn {
      font-size: 1.4rem;
      cursor: pointer;
      color: var(--primary-color);
      transition: transform 0.2s ease;
    }

    .toggle-btn:hover {
      transform: scale(1.1);
    }

    .search-bar {
      flex-grow: 1;
      margin: 0 24px;
    }

    .search-bar input {
      width: 100%;
      padding: 12px 16px;
      border-radius: var(--border-radius);
      border: 1px solid #ddd;
      font-size: 0.95rem;
    }

    .profile {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .avatar {
      width: 42px;
      height: 42px;
      background: var(--accent-color);
      border-radius: 50%;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 1rem;
      box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.2);
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 32px;
    }

    .card {
      background: var(--card-bg);
      padding: 28px;
      border-radius: var(--border-radius);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
      text-align: center;
      transition: all 0.3s ease;
      position: relative;
    }

    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: var(--gradient);
      border-top-left-radius: var(--border-radius);
      border-top-right-radius: var(--border-radius);
    }

    .card:hover {
      transform: translateY(-8px) scale(1.02);
    }

    .card .icon {
      font-size: 2.8rem;
      color: var(--primary-color);
      margin-bottom: 16px;
    }

    .card .number {
      font-size: 2rem;
      font-weight: 700;
    }

    .card .label {
      color: var(--text-light);
      font-size: 0.95rem;
    }


</style>
<body>
  <div class="sidebar" id="sidebar">
    <div class="logo">InternshipHub</div>
    <nav>
      <a href="/admin/dashboard.php" class="active"><i class="fas fa-home"></i><span class="text"> Dashboard</span></a>
      <a href="/admin/students.php"><i class="fas fa-user-graduate"></i><span class="text"> Students</span></a>
      <a href="/admin/employers.php"><i class="fas fa-building"></i><span class="text"> Employers</span></a>
      <a href="/admin/application.php"><i class="fas fa-briefcase"></i><span class="text"> Application</span></a>
      <a href="/admin/internship.php"><i class="fas fa-briefcase"></i><span class="text"> Internships</span></a>
      <a href="/admin/qr.php"><i class="fas fa-briefcase"></i><span class="text"> Qr</span></a>
      <a href="#" onclick=logout()><i class="fas fa-sign-out-alt"></i><span class="text"> Logout</span></a>
    </nav>
  </div>

  <div class="main-content" id="main-content">
    <div class="topbar">
      <div class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></div>
    </div>

    <script src="/admin/js/dashboard.js"></script>
    <script src="/admin/js/token-validation.js"></script>
    <script src="/admin/js/logout.js"></script>
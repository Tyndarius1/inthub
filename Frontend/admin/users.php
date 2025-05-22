<?php include '../../frontend/admin/include/nav.php' ?> 
<link rel="stylesheet" href="/assets/css/admin/users.css">
<link rel="stylesheet" href="/assets/css/admin/create-users.css">

<div id="main-content">
  <header id="header">
    <h2 id="page-title">User Management</h2>
    <button id="toggleSidebar" class="btn-toggle" aria-label="Toggle Sidebar">
      <i class="bi bi-list"></i>
    </button>
  </header>

  <p class="description">
    Manage all registered students and employers. Use the tabs below to switch views. Click on "Create New User" to add a student or employer manually.
  </p>

  <div class="user-controls">
    <div class="tabs" role="tablist" aria-label="User Types">
      <button class="tab active" data-type="student" role="tab" aria-selected="true">Students</button>
      <button class="tab" data-type="employer" role="tab" aria-selected="false">Employers</button>
    </div>
    <button id="createUserBtn" class="btn btn-primary">Create New User</button>
  </div>

  <div id="userList" class="card-list" aria-live="polite"></div>
</div>

<!-- User Details Modal -->
<div id="userModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
  <div class="modal-content">
    <button class="close" aria-label="Close">&times;</button>
    <div id="modalDetails"></div>
  </div>
</div>

<!-- Create User Modal -->
<div id="createUserModal" class="modal hidden create-user-modal" role="dialog" aria-modal="true" aria-labelledby="createUserTitle">
  <div class="modal-content">
    <button class="close" aria-label="Close">&times;</button>
    <h2 id="createUserTitle">Create New User</h2>
    <form id="createUserForm" enctype="multipart/form-data" novalidate>
      <fieldset>
        <legend>User Type</legend>
        <select name="userType" id="userTypeSelect" aria-describedby="userTypeHelp">
          <option value="student" selected>Student</option>
          <option value="employer">Employer</option>
        </select>
        <small id="userTypeHelp">Select the user type to display relevant fields</small>
      </fieldset>

      <fieldset id="studentFields" class="user-fields">
        <legend>Student Information</legend>
        <label>Name: <input type="text" name="name" required /></label>
        <label>Email: <input type="email" name="email" required /></label>
        <label>Password: <input type="password" name="password" required /></label>
        <label>Confirm Password: <input type="password" name="password_confirmation" /></label>
        <label>Date of Birth: <input type="date" name="dob" /></label>
        <label>Gender:
          <select name="gender">
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
        </label>
        <label>Address: <textarea name="address" placeholder="Address"></textarea></label>
        <label>Phone: <input type="text" name="phone" required /></label>
        <label>School: <input type="text" name="school" required /></label>
        <label>Course: <input type="text" name="course" required /></label>
        <label>Year Level: <input type="text" name="year_level" required /></label>
        <label>Student ID: <input type="text" name="student_id_number" required /></label>
        <label>Student Picture: <input type="file" name="student_pic" accept="image/*" required /></label>
      </fieldset>

      <fieldset id="employerFields" class="user-fields" style="display: none;">
        <legend>Employer Information</legend>
        <label>Company Name: <input type="text" name="company_name" required /></label>
        <label>Email: <input type="email" name="email" required /></label>
        <label>Password: <input type="password" name="password" required /></label>
        <label>Confirm Password: <input type="password" name="password_confirmation" /></label>
        <label>Phone: <input type="text" name="phone" required /></label>
        <label>Industry: <input type="text" name="industry" required /></label>
        <label>Location: <input type="text" name="location" required /></label>
        <label>Contact Person: <input type="text" name="contact_person" required /></label>
        <label>Website: <input type="url" name="website" required /></label>
        <label>Description: <textarea name="description" required></textarea></label>
        <label>Company Picture: <input type="file" name="company_pic" accept="image/*" required /></label>
      </fieldset>

      <button type="submit" class="btn btn-primary" style="margin-top:1rem;">Create User</button>
    </form>
  </div>
</div>

<script src="/assets/js/admin/sidebar.js"></script>
<script src="/assets/js/admin/users.js"></script>

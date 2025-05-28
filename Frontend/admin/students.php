<?php include '../admin/includes/sidebar.php'; ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="fw-bold">Student List</h2>
    <div class="dropdown">
      <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
      </button>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#createStudentModal">Create Student</a></li>
        <li><a class="dropdown-item" href="#">Export Students</a></li>
        <li><a class="dropdown-item" href="#">Import Students</a></li>
        <!-- Removed Verify Account link -->
      </ul>
    </div>
  </div>

  <div class="table-responsive">
    <table id="studentTable" class="table table-striped table-bordered nowrap" style="width:100%">
      <thead class="table-dark">
        <tr>
          <th>Picture</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>DOB</th>
          <th>Address</th>
          <th>School</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="studentBody"></tbody>
    </table>
  </div>
</div>

<!-- Modal for Create Student -->
<div class="modal fade" id="createStudentModal" tabindex="-1" aria-labelledby="createStudentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createStudentModalLabel">Register New Student</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="createStudentForm">
        <div class="modal-body row g-3">
          <div class="col-md-6">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">DOB</label>
            <input type="date" name="dob" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select" required>
              <option value="">Select</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" required>
          </div>
          <div class="col-md-12">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">School</label>
            <input type="text" name="school" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Course</label>
            <input type="text" name="course" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Year Level</label>
            <input type="text" name="year_level" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Student ID</label>
            <input type="text" name="student_id_number" class="form-control" required>
          </div>
          <div class="col-md-12">
            <label class="form-label">Student Picture</label>
            <input type="file" name="student_pic" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Register</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal for Update Student -->
<div class="modal fade" id="updateStudentModal" tabindex="-1" aria-labelledby="updateStudentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateStudentModalLabel">Update Student</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="updateStudentForm">
        <input type="hidden" name="id">
        <div class="modal-body row g-3">
          <!-- Reuse input fields from create modal -->
          <!-- Same fields as create form, just without `required` -->
          <div class="col-md-6">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">DOB</label>
            <input type="date" name="dob" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select">
              <option value="">Select</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Other">Other</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control">
          </div>
          <div class="col-md-12">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">School</label>
            <input type="text" name="school" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Course</label>
            <input type="text" name="course" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Year Level</label>
            <input type="text" name="year_level" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Student ID</label>
            <input type="text" name="student_id_number" class="form-control">
          </div>
          <div class="col-md-12">
            <label class="form-label">Student Picture</label>
            <input type="file" name="student_pic" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Removed Manual Verify Account Modal -->
<!-- Removed OTP Modal -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  fetchStudents();

  document.getElementById('createStudentForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('http://127.0.0.1:8000/api/student/register', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.student) {
        Swal.fire('Success', 'Student successfully registered.', 'success');
        const modal = bootstrap.Modal.getInstance(document.getElementById('createStudentModal'));
        modal.hide();
        fetchStudents(true);
      } else {
        Swal.fire('Error', 'Validation failed. Check form inputs.', 'error');
      }
    })
    .catch(err => {
      console.error(err);
      Swal.fire('Error', 'Something went wrong.', 'error');
    });
  });
});

function fetchStudents(reload = false) {
  fetch('http://127.0.0.1:8000/api/student/list')
    .then(response => response.json())
    .then(data => {
      const tbody = document.getElementById('studentBody');
      tbody.innerHTML = '';

      data.forEach(student => {
        const picUrl = `http://127.0.0.1:8000/storage/${student.student_pic}`;

        const row = `
          <tr data-id="${student.id}">
            <td><img src="${picUrl}" class="rounded-circle" width="40" height="40" onerror="this.src='/default-avatar.png'"></td>
            <td>${student.name}</td>
            <td>${student.email}</td>
            <td>${student.phone}</td>
            <td>${student.dob}</td>
            <td>${student.address}</td>
            <td>${student.school}</td>
            <td>
              <button class="btn btn-sm btn-warning me-1" onclick="editStudent(${student.id})"><i class="fas fa-edit"></i></button>
              <button class="btn btn-sm btn-danger" onclick="deleteStudent(${student.id}, this)"><i class="fas fa-trash"></i></button>
            </td>
          </tr>
        `;
        tbody.innerHTML += row;
      });

      if (reload) {
        $('#studentTable').DataTable().clear().destroy();
      }
      $('#studentTable').DataTable({
        responsive: true
      });
    })
    .catch(error => {
      console.error('Error fetching student data:', error);
    });
}

function deleteStudent(id, button) { const token = localStorage.getItem('admin_token'); Swal.fire({ title: 'Are you sure?', text: 'This will permanently delete the student.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e74c3c', cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, delete it!' }).then((result) => { if (result.isConfirmed) { fetch(`http://127.0.0.1:8000/api/student/delete/${id}`, { method: 'DELETE', headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' } }) .then(response => { if (response.ok) { Swal.fire('Deleted!', 'Student has been deleted.', 'success'); fetchStudents(true); } else { Swal.fire('Failed', 'Could not delete student.', 'error'); } }) .catch(error => { console.error('Delete error:', error); Swal.fire('Error', 'An error occurred.', 'error'); }); } }); }



function editStudent(id) {
  const token = localStorage.getItem('admin_token');

  fetch(`http://127.0.0.1:8000/api/student/profile/${id}`, {
    method: 'GET',
    headers: {
      'Authorization': `Bearer ${token}`
    }
  })
  .then(res => res.json())
  .then(data => {
    const form = document.getElementById('updateStudentForm');

    // Prefill all matching inputs
    for (const key in data) {
      const input = form.querySelector(`[name="${key}"]`);

      if (input) {
        if (input.type === 'file') continue; // skip file inputs
        input.value = data[key] ?? '';
      }
    }

    // Set hidden ID
    form.querySelector('[name="id"]').value = id;

    const modal = new bootstrap.Modal(document.getElementById('updateStudentModal'));
    modal.show();
  })
  .catch(err => {
    console.error('Failed to load student profile:', err);
    Swal.fire('Error', 'Could not load student profile.', 'error');
  });
}


document.getElementById('updateStudentForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const token = localStorage.getItem('admin_token');
  const form = e.target;
  const id = form.querySelector('[name="id"]').value;
  const formData = new FormData(form);

  fetch(`http://127.0.0.1:8000/api/student/update/${id}`, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`
    },
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.student || data.success) {
      Swal.fire('Success', 'Student information updated successfully.', 'success');
      const modal = bootstrap.Modal.getInstance(document.getElementById('updateStudentModal'));
      modal.hide();
      fetchStudents(true);
    } else {
      Swal.fire('Error', 'Failed to update student. Check inputs.', 'error');
    }
  })
  .catch(error => {
    console.error('Update error:', error);
    Swal.fire('Error', 'An error occurred while updating.', 'error');
  });
});


</script>

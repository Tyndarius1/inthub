<?php include '../admin/includes/sidebar.php'; ?>

<!-- Styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Employer List</h2>
    <div class="dropdown">
      <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        Actions
      </button>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#createEmployerModal">Create Employer</a></li>
        <li><a class="dropdown-item" href="#">Export Employers</a></li>
        <li><a class="dropdown-item" href="#">Import Employers</a></li>
      </ul>
    </div>
  </div>

  <div class="table-responsive">
    <table id="employerTable" class="table table-striped table-bordered nowrap" style="width:100%">
      <thead class="table-dark">
        <tr>
          <th>Logo</th>
          <th>Company</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Industry</th>
          <th>Location</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="employerBody"></tbody>
    </table>
  </div>
</div>

<!-- Create Employer Modal -->
<div class="modal fade" id="createEmployerModal" tabindex="-1" aria-labelledby="createEmployerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="createEmployerForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Register Employer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body row g-3">
        <div class="col-md-6">
          <label class="form-label">Company Name</label>
          <input type="text" name="company_name" class="form-control" required>
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
          <label class="form-label">Phone</label>
          <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Industry</label>
          <input type="text" name="industry" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Location</label>
          <input type="text" name="location" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Contact Person</label>
          <input type="text" name="contact_person" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Website</label>
          <input type="text" name="website" class="form-control">
        </div>
        <div class="col-md-12">
          <label class="form-label">Description</label>
          <input type="text" name="description" class="form-control">
        </div>
        <div class="col-md-12">
          <label class="form-label">Company Logo</label>
          <input type="file" name="company_pic" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Register</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Employer Modal -->
<div class="modal fade" id="editEmployerModal" tabindex="-1" aria-labelledby="editEmployerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="editEmployerForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Employer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body row g-3">
        <input type="hidden" name="id" id="editEmployerId">
        
        <div class="col-md-6">
          <label class="form-label">Company Name</label>
          <input type="text" name="company_name" id="editCompanyName" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" id="editEmail" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Password</label>
          <input type="password" name="password" id="editPassword" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" id="editPhone" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Industry</label>
          <input type="text" name="industry" id="editIndustry" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Location</label>
          <input type="text" name="location" id="editLocation" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Contact Person</label>
          <input type="text" name="contact_person" id="editContactPerson" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Website</label>
          <input type="text" name="website" id="editWebsite" class="form-control">
        </div>
        <div class="col-md-12">
          <label class="form-label">Description</label>
          <input type="text" name="description" id="editDescription" class="form-control">
        </div>
        <div class="col-md-12">
          <label class="form-label">Company Logo</label>
          <input type="file" name="company_pic" id="editCompanyPic" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  loadEmployers();

  document.getElementById('createEmployerForm').addEventListener('submit', e => {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    fetch('http://127.0.0.1:8000/api/employer/register', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.employer) {
        Swal.fire('Success', 'Employer registered successfully.', 'success');
        bootstrap.Modal.getInstance(document.getElementById('createEmployerModal')).hide();
        loadEmployers(true);
      } else {
        Swal.fire('Error', 'Validation failed.', 'error');
      }
    })
    .catch(err => {
      console.error(err);
      Swal.fire('Error', 'An error occurred.', 'error');
    });
  });
});

function loadEmployers(reload = false) {
  fetch('http://127.0.0.1:8000/api/employer/list')
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById('employerBody');
      tbody.innerHTML = '';

      data.forEach(emp => {
        const logo = `http://127.0.0.1:8000/storage/${emp.company_pic}`;
        tbody.innerHTML += `
          <tr>
            <td><img src="${logo}" width="40" height="40" class="rounded-circle" onerror="this.src='/default-avatar.png'"></td>
            <td>${emp.company_name}</td>
            <td>${emp.email}</td>
            <td>${emp.phone}</td>
            <td>${emp.industry}</td>
            <td>${emp.location}</td>
            <td>${emp.description}</td>
            <td>
              <button class="btn btn-warning btn-sm" onclick="editEmployer(${emp.id})"><i class="fas fa-edit"></i></button>
              <button class="btn btn-danger btn-sm" onclick="deleteEmployer(${emp.id})"><i class="fas fa-trash"></i></button>
            </td>
          </tr>`;
      });

      if (reload) {
        $('#employerTable').DataTable().clear().destroy();
      }

      $('#employerTable').DataTable({ responsive: true });
    })
    .catch(err => console.error('Failed to fetch employers:', err));
}

function deleteEmployer(id) {
  const token = localStorage.getItem('admin_token');
  Swal.fire({
    title: 'Are you sure?',
    text: 'This employer will be permanently deleted.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete it!',
    confirmButtonColor: '#d33'
  }).then(result => {
    if (result.isConfirmed) {
      fetch(`http://127.0.0.1:8000/api/employer/delete/${id}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        }
      }).then(res => {
        if (res.ok) {
          Swal.fire('Deleted!', 'Employer deleted.', 'success');
          loadEmployers(true);
        } else {
          Swal.fire('Failed', 'Could not delete employer.', 'error');
        }
      });
    }
  });
}

function editEmployer(id) {
  const token = localStorage.getItem('admin_token');

  fetch(`http://127.0.0.1:8000/api/employer/profile/${id}`, {
    method: 'GET',
    headers: {
      'Authorization': `Bearer ${token}`
    }
  })
  .then(res => res.json())
  .then(data => {
    const form = document.getElementById('editEmployerForm');

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

    const modal = new bootstrap.Modal(document.getElementById('editEmployerModal'));
    modal.show();
  })
  .catch(err => {
    console.error('Failed to load employer profile:', err);
    Swal.fire('Error', 'Could not load employer profile.', 'error');
  });
}

document.getElementById('editEmployerForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const token = localStorage.getItem('admin_token');
  const form = e.target;
  const id = form.querySelector('[name="id"]').value;
  const formData = new FormData(form);

  fetch(`http://127.0.0.1:8000/api/employer/update/${id}`, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`
    },
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.employer || data.success) {
      Swal.fire('Success', 'Employer information updated successfully.', 'success');
      const modal = bootstrap.Modal.getInstance(document.getElementById('editEmployerModal'));
      modal.hide();
      loadEmployers(true);
    } else {
      Swal.fire('Error', 'Failed to update employer. Check inputs.', 'error');
    }
  })
  .catch(error => {
    console.error('Update error:', error);
    Swal.fire('Error', 'An error occurred while updating.', 'error');
  });
});
</script>

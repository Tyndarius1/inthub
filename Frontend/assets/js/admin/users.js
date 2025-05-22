document.addEventListener('DOMContentLoaded', () => {
  const tabs = document.querySelectorAll('.tab');
  const userList = document.getElementById('userList');
  const createUserBtn = document.getElementById('createUserBtn');
  const createUserModal = document.getElementById('createUserModal');
  const createUserClose = createUserModal.querySelector('.close');
  const createUserForm = document.getElementById('createUserForm');
  const userTypeSelect = createUserForm.querySelector('select[name="userType"]');
  const studentFields = document.getElementById('studentFields');
  const employerFields = document.getElementById('employerFields');

  let currentType = 'student';

  const endpoints = {
    student: 'http://127.0.0.1:8000/api/student/list',
    employer: 'http://127.0.0.1:8000/api/employer/list',
    register: {
      student: 'http://127.0.0.1:8000/api/student/register',
      employer: 'http://127.0.0.1:8000/api/employer/register',
    }
  };

  async function loadUsers(type) {
    userList.innerHTML = 'Loading...';
    try {
      const res = await fetch(endpoints[type]);
      if (!res.ok) throw new Error('Failed to fetch');
      const users = await res.json();
      renderUsers(users, type);
    } catch {
      userList.innerHTML = '<p style="color:red;">Failed to load users.</p>';
    }
  }

  function renderUsers(users, type) {
    if (!users.length) {
      userList.innerHTML = `<p>No ${type}s found.</p>`;
      return;
    }

    userList.innerHTML = '';
    users.forEach(user => {
      const name = type === 'student' ? user.name : user.company_name;
      const email = user.email || '';
      const picture = user.picture_url && user.picture_url.trim() !== ''
        ? user.picture_url
        : 'http://127.0.0.1:8000/images/default.png';

      const isVerified = user.is_verified === true || user.is_verified === 1;
      const verifiedText = isVerified ? 'Verified' : 'Not Verified';
      const verifiedClass = isVerified ? 'verified' : 'not-verified';

      const card = document.createElement('div');
      card.classList.add('user-card');
      card.innerHTML = `
        <div class="user-card-top">
          <img src="${picture}" onerror="this.src='http://127.0.0.1:8000/images/default.png'" alt="${name}" class="user-picture" />
        </div>
        <span class="verified-status ${verifiedClass}">${verifiedText}</span>
        <h4 class="user-name">${name}</h4>
        <p class="user-email">${email}</p>
        <div class="actions">
          <button class="btn-action btn-more-info" data-id="${user.id}">More Info</button>
          <button class="btn-action btn-update" data-id="${user.id}">Update</button>
          <button class="btn-action btn-delete" data-id="${user.id}">Delete</button>
        </div>
      `;

      card.querySelector('.btn-more-info').addEventListener('click', () => {
        alert(`More info for ${name} (ID: ${user.id})`);
      });

      card.querySelector('.btn-update').addEventListener('click', () => {
        alert(`Update user ID: ${user.id}`);
      });

      card.querySelector('.btn-delete').addEventListener('click', () => {
        if (confirm(`Are you sure you want to delete ${name}?`)) {
          deleteUser(user.id, type);
        }
      });

      userList.appendChild(card);
    });
  }

  async function deleteUser(id, type) {
    try {
      const url = `http://127.0.0.1:8000/api/${type}/delete/${id}`;
      const res = await fetch(url, { method: 'DELETE' });
      if (!res.ok) throw new Error('Delete failed');
      alert('User deleted successfully.');
      loadUsers(currentType);
    } catch (error) {
      alert('Failed to delete user: ' + error.message);
    }
  }

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      currentType = tab.dataset.type;
      tabs.forEach(t => t.classList.toggle('active', t === tab));
      loadUsers(currentType);
    });
  });

  createUserBtn.addEventListener('click', () => {
    createUserModal.classList.remove('hidden');
  });

  createUserClose.addEventListener('click', () => {
    createUserModal.classList.add('hidden');
  });

  function updateFormFields() {
    const selected = userTypeSelect.value;
    if (selected === 'student') {
      studentFields.style.display = 'block';
      employerFields.style.display = 'none';
      toggleFieldState(studentFields, true);
      toggleFieldState(employerFields, false);
    } else {
      studentFields.style.display = 'none';
      employerFields.style.display = 'block';
      toggleFieldState(studentFields, false);
      toggleFieldState(employerFields, true);
    }
  }

  function toggleFieldState(container, isEnabled) {
    container.querySelectorAll('input, select, textarea').forEach(i => {
      i.disabled = !isEnabled;
      i.required = isEnabled;
    });
  }

  userTypeSelect.addEventListener('change', updateFormFields);
  updateFormFields();

  createUserForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    updateFormFields();

    const formData = new FormData(createUserForm);
    const url = endpoints.register[userTypeSelect.value];

    try {
      const res = await fetch(url, {
        method: 'POST',
        body: formData,
      });

      const data = await res.json();

      if (!res.ok) {
        alert('Error:\n' + (data.errors
          ? Object.values(data.errors).flat().join('\n')
          : data.message || 'Registration failed'));
        return;
      }

      alert(data.message || 'User created successfully!');
      createUserModal.classList.add('hidden');
      createUserForm.reset();
      updateFormFields();
      loadUsers(currentType);

    } catch (error) {
      alert('Network error: ' + error.message);
    }
  });

  loadUsers(currentType);
});
